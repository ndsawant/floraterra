<?php
/*
 * Class : GPSecure
 * Package: Genesis Pro
 * Description: Includes all Security options for genesis Pro
 * Author: Swapnil Ghone
 * Since: 19-November-2016
 * Last Modified: 29-November-2016
 * 
 * Filters:
 * 
 * genesis_footer_copyright_shortcode - use to change footer copyright text
 * 
 * genesis_footer_loginout_shortcode - use to change footer login link
 * 
 * genesis_admin_footer_text - use to chane footer text at admin end
 */

class GPSecure extends GPCommon {

    var $gp_secure_settings;

    function __construct() {

        $this->gp_secure_settings = parent::get_gp_json_meta('gp_secure_settings', true);

        $GPSecureLogin = new GPSecureLogin();

        // Disables all core updates
        if ($this->gp_secure_settings['disable_all_auto_core_update']) {
            add_filter('auto_update_core', '__return_false');
        }

        add_filter('auto_update_plugin', array(&$this, 'gp_maybe_auto_update'), 999, 2);

        /*
         *  set sechdule for brut force attack
         */
        add_action('gp_clear_lockout_data_sechdule', array($GPSecureLogin, 'gp_clear_lockout_data'));

        register_activation_hook(__FILE__, array($this, 'gp_schedule_notification_bfa'));
        add_action('wp', array($this, 'gp_schedule_notification_bfa'));

        //disable Comment and pingback 
        add_filter("wp_insert_post_data", array(&$this, 'gp_default_post_comment_status'), 10, 2);

        // For captcha
        add_action('comment_form', array(&$this, 'gp_comment_captcha'));
        add_filter('preprocess_comment', array(&$this, 'gp_check_comment_captcha'));

        // For force ssl pages 
        add_action('template_redirect', array($this, 'gp_force_ssl_url'));

        // Hook to check robot.txt
        add_filter('robots_txt', array(&$this, 'gp_update_robot_txt'), 10, 2);

        // Remove Generator meta-tag
        remove_action('wp_head', 'wp_generator');

        /*
         * Shortcodes for footer link and copyrights
         */
        add_shortcode('wsig_footer_loginout', array(&$this, 'wsig_footer_loginout_callback'));
        add_shortcode('wsig_footer_copyright', array(&$this, 'wsig_footer_copyright_callback'));

        /*
         * Hide plugin and update notice if user is not popadmin
         */
        add_action('admin_init', array(&$this, 'gp_hide_details_for_normal_admin'), 1);

        /*
         * disallow user to access plugin screen if not popadmin
         */
        add_action('current_screen', array(&$this, 'gp_disallow_plugin_page_for_normal_user'));

        /*
         * Hide theme option if user not popadmin
         */
        add_action('admin_head', array(&$this, 'gp_hide_theme_option'));
        /*
         * disallow other admin user from deleting popadmin user account
         */
        add_filter('user_row_actions', array(&$this, 'gp_remove_delete_actions'), 10, 2);
        add_filter('bulk_actions-users', array(&$this, 'gp_disable_bulk_delete'));

        /*
         * change admin footer text
         */
        add_filter('admin_footer_text', array(&$this, 'gp_change_footer_admin_text'));

        /*
         * maintance mode
         */

        add_action('wp', array($this, 'gp_maintenance_active'));

        /*
         * add action for gravity form to enable honey
         */
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if (is_plugin_active('gravityforms/gravityforms.php')) {
            add_action('wp_loaded', array($this, 'gp_change_all_gform_honeybot'));
            add_action('gform_after_save_form', array($this, 'gp_change_new_gform_honeybot'), 10, 2);
        }
        
        /*
         * enqueue captch script
         */
        if($this->gp_secure_settings['comment_form_security'] == 1){
            add_action('wp_enqueue_scripts',array($this,'gp_enqueue_captcha_script'));
        }
        
    }

    function wsig_footer_copyright_callback($atts) {
        $defaults = array(
            'after' => '',
            'before' => '',
            'copyright' => '&copy;',
            'first' => '',
        );
        $atts = shortcode_atts($defaults, $atts);
        $output = $atts['before'] . $atts['copyright'] . ' ';
        if ('' != $atts['first'] && date('Y') != $atts['first'])
            $output .= $atts['first'] . '&ndash;';
        $output .= date('Y') . $atts['after'];
        return apply_filters('genesis_footer_copyright_shortcode', $output, $atts);
    }

    function wsig_footer_loginout_callback($atts) {
        $defaults = array(
            'after' => '',
            'before' => '',
            'redirect' => '',
        );
        $atts = shortcode_atts($defaults, $atts);

        if (!is_user_logged_in()) {

            $front_login_url = '';
            if ($this->gp_secure_settings['secure_login']) {
                $post_data = get_post(parent::get_gp_meta('gp_login_theme_page_id'));
                $front_login_url = get_site_url() . '/' . $post_data->post_name . '/';
            }

            $login_url = $front_login_url ? $front_login_url : wp_login_url($atts['redirect']);
            $link = '<a href="' . esc_url($login_url) . '">' . __('Log in', 'wsigenesis') . '</a>';
        } else {

            $link = '<a href="' . esc_url(wp_logout_url($atts['redirect'])) . '">' . __('Log out', 'wsigenesis') . '</a>';
        }

        $output = $atts['before'] . apply_filters('loginout', $link) . $atts['after'];
        return apply_filters('genesis_footer_loginout_shortcode', $output, $atts);
    }

    /*
     * Hide plugin and update notice if user is not popadmin
     */

    public function gp_hide_details_for_normal_admin() {

        if (!parent::is_super_admin()) {

            remove_menu_page('plugins.php');

            if ($this->gp_secure_settings['disable_wordpress_upgrade']) {

                remove_action('admin_notices', 'update_nag', 3);

                add_action('init', function($a) {
                    remove_action('init', 'wp_version_check');
                }, 2);
                add_filter('pre_site_transient_update_core', function ($a) {
                    global $wp_version;
                    return (object) array(
                                'last_checked' => time(),
                                'version_checked' => $wp_version,
                    );
                });
            }
            if ($this->gp_secure_settings['theme_editor']) {
                remove_submenu_page('themes.php', 'theme-editor.php');
            }

            remove_action('restrict_manage_users', 'action_restrict_manage_users', 10, 0);
        }
    }

    /*
     * Auto Update Genesis tools plugin
     */

    public function gp_maybe_auto_update($update, $item) {

        if (isset($item->slug) && $item->slug == $this->slug) {

            if ($this->gp_secure_settings['auto_update_plugins']) {
                return true;
            }
        }

        return $update;
    }

    // Set schedule to Protect from brute force attack
    function gp_schedule_notification_bfa() {
        if (!wp_next_scheduled('gp_clear_lockout_data_sechdule')) {
            wp_schedule_event(time(), 'daily', 'gp_clear_lockout_data_sechdule');
        }
    }

    // disable comment and pingback if set from security option
    function gp_default_post_comment_status($data, $postarr) {

        if ($this->gp_secure_settings['en_di_comment_ping'] == 1) {
            if (!$data['guid']) {
                $data['comment_status'] = 'closed';
                $data['ping_status'] = 'closed';
            }
        } else {
            if (!$data['guid']) {
                $data['comment_status'] = 'open';
                $data['ping_status'] = 'open';
            }
        }
        return $data;
    }

    // Add captch field to comment form
    function gp_comment_captcha() {
        if ($this->gp_secure_settings['comment_form_security'] != 1)
            return false;
            ?>
            <div class="uk-form-row" id="gp-secure-captcha">
                <div class="g-recaptcha" data-sitekey="<?php echo $this->gp_secure_settings['reCAPTCHA_Site_Key']; ?>"></div>
            </div>
            <script>
                jQuery(function($) {
                    var post_field = $("#comment_post_ID");
                    if (post_field.length > 0) {
                        var parent = post_field.parent();
                        $("#gp-secure-captcha").insertBefore(parent);
                    }
                })
            </script>
            <?php
        
    }

    function gp_hide_theme_option() {
        if (!parent::is_super_admin()) {
            $currentscreen = get_current_screen();
            if ($currentscreen->id == 'themes') {
                ?>
                <style>
                    .theme-actions a.activate,.theme-actions a.load-customize,.add-new-theme{
                        display: none;
                    }
                </style>
                <script>
                    jQuery(function($) {
                        $(".wrap>h1").html('Themes');
                    })
                </script>
            <?php } elseif ($currentscreen->id == 'users') { ?>
                <script>
                    jQuery(function($) {
                        $("#user_1,#cb-select-all-1").on('click', function() {
                            if ($(this).is(':checked')) {
                                $("#new_role").attr('disabled', true);
                            } else {
                                $("#new_role").attr('disabled', false);
                            }
                        })
                        $("#the-list tr#user-1 td.username strong").html('popadmin');
                    })
                </script>          
                <?php
            }
        }
    }

    function gp_check_comment_captcha($comment_data) {

        if ($comment_data['comment_parent'] == 0) {
            if ($this->gp_secure_settings['comment_form_security'] != 1)
                return $comment_data;
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])):
                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $data = array(
                        'secret' => $this->gp_secure_settings['reCAPTCHA_Secret_Key'],
                        'response' => $_POST["g-recaptcha-response"],
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    );
                    $verify = curl_init();
                    curl_setopt($verify, CURLOPT_URL, $url);
                    curl_setopt($verify, CURLOPT_POST, true);
                    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($verify);
                    $check = json_decode($response);

                    if ($check->success):
                        $errMsg = null;
                    else:
                        $errMsg = 'Robot verification failed, please try again.';
                    endif;
                else:
                    $errMsg = 'Please click on the reCAPTCHA box.';
                endif;

                if ($errMsg != null):
                    wp_die($errMsg);
                endif;
            
        }
        return $comment_data;
    }

    function gp_force_ssl_url() {

        if (is_admin() || $_SERVER['SERVER_PORT'] == '443')
            return;

        global $post;

        if ($this->gp_secure_settings['force_ssl_entire']) {

            wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],301);
            exit();
        } else {

            if (is_home() || is_front_page() || is_archive() || is_null($post))
                return;

            $ssl_post = explode(",", $this->gp_secure_settings['force_ssl_post']);
            $ssl_pages = explode(",", $this->gp_secure_settings['force_ssl_pages']);

            if (in_array($post->ID, $ssl_post) || in_array($post->ID, $ssl_pages)) {

                if ($_SERVER['SERVER_PORT'] != '443') {
                    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],301);
                    exit();
                }
            } else {
                if ($_SERVER['SERVER_PORT'] == '443') {
                    wp_redirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],301);
                    exit();
                }
            }
        }
    }

    /*
     * update robust.txt
     */

    function gp_update_robot_txt($robot_txt, $indexing) {

        if ('0' == $indexing) {
            return $robot_txt;
        } else {
            $site_url = parse_url(site_url());
            $path = (!empty($site_url['path']) ) ? $site_url['path'] : '';
            $wp_plugin_url = str_replace(site_url(), '', plugins_url('/'));

            $robot_txt = 'User-agent: Googlebot';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Allow: ' . $path . '/';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'User-agent: Googlebot-Mobile';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Allow: ' . $path . '/';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'User-agent: *';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Disallow: /wp-admin/';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Allow: /wp-admin/admin-ajax.php';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Disallow: ' . $path . $wp_plugin_url;
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Allow: ' . $path . $wp_plugin_url . 'genesispro/';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Allow: ' . $path . $wp_plugin_url . 'widgetkit/';
            $robot_txt .= PHP_EOL;
            // check if woocommerce is installed
            if (class_exists('WooCommerce')) {
                $robot_txt .= 'Allow: ' . $path . $wp_plugin_url . 'woocommerce/assets/js/frontend/';
                $robot_txt .= PHP_EOL;
            }
            $robot_txt .= 'Allow: ' . $path . '/*.js*';
            $robot_txt .= PHP_EOL;
            $robot_txt .= 'Allow: ' . $path . '/*.css*';
        }

        return $robot_txt;
    }

    function gp_disallow_plugin_page_for_normal_user() {

        global $user_ID;
        $currentscreen = get_current_screen();
        if (!parent::is_super_admin()) {
            if ($currentscreen->id === "plugins") {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wsigenesis'));
            }

            if ($currentscreen->id === "theme-editor" && $this->gp_secure_settings['theme_editor']) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'wsigenesis'));
            }

            if ($currentscreen->id === 'user-edit') {
                if ($_GET['user_id'] == 1) {
                    wp_die('You are not allowed to edit this User.');
                }
            }
        }
    }

    function gp_remove_delete_actions($action, $user) {

        $current_user_id = get_current_user_id();

        if ($user->ID == 1 && $current_user_id != 1) {
            unset($action['delete']);
            unset($action['edit']);
        }

        return $action;
    }

    function gp_disable_bulk_delete($actions) {

        if (!parent::is_super_admin()) {
            unset($actions['delete']);
        }
        return $actions;
    }

    function gp_change_footer_admin_text() {
        $footer_text = 'WSI Genesis Administration  | <a href="http://codex.wordpress.org/" target="_blank">Documentation</a>';
        return apply_filters('genesis_admin_footer_text', $footer_text);
    }

    function gp_maintenance_active() {
        if ($this->gp_secure_settings['maintenance_mode']) {
            if (is_admin() || strstr(htmlspecialchars($_SERVER['REQUEST_URI']), '/wp-admin/')) {
                if (!is_user_logged_in()) {
                    auth_redirect();
                }
            } else {
                if (is_user_logged_in()) {
                    return;
                } else if ($this->gp_secure_settings['maintenance_mode_type'] == 'custom') {
                    add_action('template_include', array($this, 'gp_maintenance_custom_page'), 999, 1);
                } else {
                    add_action('wp_head', array($this, 'gp_maintenance_flash'));
                }
            }
        }
    }

    function gp_maintenance_flash() {
        echo "<style type='text/css'>body{background:#002D4C !important; text-align:center;padding-top:50px;}</style>";
        echo '<embed src="' . GENESIS_PRO_ASSETS_URL . 'uc.swf" quality="high" bgcolor="#006699" width="800" height="600" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></body></html>';
        die();
    }

    function gp_maintenance_custom_page() {

        if (file_exists(ABSPATH . 'maintenance.html')) {
            $template_name = ABSPATH . 'maintenance.html';
        } else {
            add_action('wp_head', array($this, 'gp_maintenance_flash'));
        }
        return $template_name;
    }

    /*
     * enable disable comment and pingback
     */

    public function gp_enable_comment_pingback() {

        global $wpdb;
        update_option('default_pingback_flag', '1');
        update_option('default_ping_status', 'open');
        update_option('default_comment_status', 'open');
        $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->posts . " SET comment_status = '%s' , ping_status = '%s' WHERE 1",'open','open'));
    }

    public function gp_disable_comment_pingback() {

        global $wpdb;
        update_option('default_pingback_flag', '');
        update_option('default_ping_status', 'closed');
        update_option('default_comment_status', 'closed');
        $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->posts . " SET comment_status = '%s' , ping_status = '%s' WHERE 1",'closed','closed'));
    }

    public function gp_enable_entire_ssl() {

        /*
         * If options is set active then we check for define('FORCE_SSL_ADMIN', true) line in wp-config
         * if not found then we add that line to wp-config file
         */
        $cnf_file = ABSPATH . 'wp-config.php';
        $fr = fopen($cnf_file, 'r');
        $content = fread($fr, filesize($cnf_file));
        fclose($fr);
        $pos = strpos($content, 'define(\'FORCE_SSL_ADMIN\', true);');
        if ($pos === FALSE) { // if line is not found
            if (is_writable($cnf_file)) { // check if wp-config is writable or not
                $fw = fopen($cnf_file, 'w');
                $temp_str = "define('DB_COLLATE', '');\n\ndefine('FORCE_SSL_ADMIN', true);";
                $new_con = str_replace("define('DB_COLLATE', '');", $temp_str, $content);
                fwrite($fw, $new_con);
                fclose($fw);

                //modify database fields
                if (strpos(get_option('siteurl'), 'https') === FALSE) {
                    update_option('siteurl', str_replace('http', 'https', get_option('siteurl')));
                }

                if (strpos(get_option('home'), 'https') === FALSE) {
                    update_option('home', str_replace('http', 'https', get_option('home')));
                }
            }
        }
    }

    public function gp_disable_entire_ssl() {
        /*
         * If options is disable then we check for define('FORCE_SSL_ADMIN', true) line in wp-config
         * if found we remove that line
         */
        $cnf_file = ABSPATH . 'wp-config.php';
        $fr = fopen($cnf_file, 'r');
        $content = fread($fr, filesize($cnf_file));
        fclose($fr);
        $pos = strpos($content, "define('FORCE_SSL_ADMIN', true);");
        if ($pos !== FALSE) { // if line is not found
            if (is_writable($cnf_file)) { // check if wp-config is writable or not
                $fw = fopen($cnf_file, 'w');
                $new_con = str_replace("define('FORCE_SSL_ADMIN', true);", " ", $content);
                fwrite($fw, $new_con);
                fclose($fw);

                //modify database fields
                if (strpos(get_option('siteurl'), 'https') !== FALSE) {
                    update_option('siteurl', str_replace('https', 'http', get_option('siteurl')));
                }

                if (strpos(get_option('home'), 'https') !== FALSE) {
                    update_option('home', str_replace('https', 'http', get_option('home')));
                }
            }
        }
    }

    public function gp_change_all_gform_honeybot() {

        $change_status = get_option('wsi_gfrom_honeybot_status');
        if (!$change_status) {
            $forms = GFAPI::get_forms();
            foreach ($forms as &$form) {
                $form['enableHoneypot'] = '1';
            }
            GFAPI::update_forms($forms);
            update_option('wsi_gfrom_honeybot_status', 1);
        }


        $captcha_status = get_option('wsi_gfrom_captcha_status');

        if (!$captcha_status) {

            $public_key = get_option('rg_gforms_captcha_public_key');
            $private_key = get_option('rg_gforms_captcha_private_key');

            // if keys exists than replace originial captcha field by default one
            if (!empty($public_key) && $public_key != '' && !empty($private_key) && $private_key != '') {
                $forms = GFAPI::get_forms();
                foreach ($forms as &$form) {
                    $fields = $form['fields'];
                    foreach ($fields as $filed) {
                        if ($filed->type == 'captcha') {
                            $filed->captchaType = '';
                            $filed->captchaTheme = '';
                        }
                    }
                }

                GFAPI::update_forms($forms);
            } else { // if key does not exists than remove captcha filed
                $forms = GFAPI::get_forms();
                foreach ($forms as &$form) {

                    foreach ($form['fields'] as $index => $filed) {
                        if ($filed->type == 'captcha') {
                            unset($form['fields'][$index]);
                        }
                    }
                }
                GFAPI::update_forms($forms);
            }
            update_option('wsi_gfrom_captcha_status', 1);
        }
    }

    public function gp_change_new_gform_honeybot($form, $is_new) {
        if ($is_new) {
            $form['enableHoneypot'] = true;
            GFAPI::update_form($form);
        }
    }

    public function gp_enqueue_captcha_script(){ 
        if ( ! is_admin() ){ 
            $url = 'https://www.google.com/recaptcha/api.js';
            wp_register_script( 'google-recaptcha', $url);
            wp_enqueue_script( 'google-recaptcha');
        }
     }
}

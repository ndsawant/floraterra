<?php
/*
  Plugin Name: Genesis Pro
  Plugin URI: http://genesis.mywsiportal.com/
  Description: Genesis theme options
  Version: 2.8.1
  Author: PAC
  Author URI: http://genesis.mywsiportal.com/
 */
/* ---------------------------------Version wise Changes---------------------------------
  Version: 1.0
  >>> 1: All the general tab options
  >>> 2: All the Secure tab options
  >>> 3: Secure login option on separate page , removed the secure login popup
  Version: 2.0 (11th Jan 2017)
  >>> 1: Added settings tab option
  >>> 2: Added social option
  >>> 3: Added redirect option
  Version: 2.1 (11th Jan 2017)
  >>> Fixed the database update issue, now if database need to update than genesis-login will firstly go to upgrade.php and then will get back to login page
  Version: 2.2 (25th Jan 2017)
  >>> Added the security patch for gravity form to by default enable honeybot span option for all existing forms
  Version: 2.2.1 (2nd Feb 2017)
  >>> Added the security patch for gravity form to set captcha as default captcha or if keys are not set than remove captcha field
  Version: 2.2.2 (22nd March 2017)
  >>> Created the option for custom header and footer script
  Version: 2.2.3 (3rd May 2017)
  >>> Fixed the robot.txt issue
  Version: 2.2.4 (26th May 2017)
  >>> Added the alt tag option for social icons
  Version: 2.2.5 (20th June 2017)
  >>> Added the google captcha option for comment form 
  Version: 2.2.5.1 (29th June 2017)
  >>> Fixed the bug of password protect post
  >>> Updated all ssl redirections to 301
  Version: 2.5.5.2 (8th Aug 2017)
  >>> Fixed the genesis login issue 
  >>> Fixed the captcha layout
  >>> Fixed the redirect for https (15 sep)
  Version: 2.5.5.3 (01st Nov 2017)
  >>> Integrated google recaptcha on comment form and login page
  >>> Fixed the bug for SSL enabled case for making table content disabled 
  Version: 2.5.5.4 (29th Nov 2017)
  >>> Fixed the login issue
  Version: 2.5.5.5 (15th Dec 2017)
  >>> Fixed the prepare query issue
  >>> Fixed the activation hook issue
  Version: 2.6 (5th Jan 2017)
  >>> Fixed the social feeds issue
  >>> fixed the login issue caused due to captcha
  Version: 2.7 (1st Feb 2018)
  >>> 1: Fixed the notice message on forgot password on login form
  Version: 2.8 (12th Feb 2018)
  >>> 1: Fixed the sitemap issue 
  >>> 2: Removed the ssl individual ssl option
  Version: 2.8.1 (14th Feb 2018)
  >>> Made the menu name changes to match with other plugins
 * 
 */

class GenesisPro {
    
    var $GPcommon;
    
    function __construct() {
        global $wpdb;
        define('GENESIS_PRO_URL', plugin_dir_url(__FILE__));
        define('GENESIS_PRO_DIR', dirname(__FILE__));

        // All URL Path of directory
        define('GENESIS_PRO_JS_URL', GENESIS_PRO_URL . 'js/');
        define('GENESIS_PRO_CSS_URL', GENESIS_PRO_URL . 'css/');
        define('GENESIS_PRO_VIEWS_URL', GENESIS_PRO_URL . 'views/');
        define('GENESIS_PRO_WIDGETS_URL', GENESIS_PRO_URL . 'widgets/');
        define('GENESIS_PRO_IMAGES_URL', GENESIS_PRO_URL . 'images/');
        define('GENESIS_PRO_CLASSES_URL', GENESIS_PRO_URL . 'classes/');
        define('GENESIS_PRO_ASSETS_URL', GENESIS_PRO_URL . 'assets/');

        // All DIR Path of directory
        define('GENESIS_PRO_JS_DIR', GENESIS_PRO_DIR . '/js');
        define('GENESIS_PRO_CSS_DIR', GENESIS_PRO_DIR . '/css');
        define('GENESIS_PRO_VIEWS_DIR', GENESIS_PRO_DIR . '/views');
        define('GENESIS_PRO_WIDGETS_DIR', GENESIS_PRO_DIR . '/widgets');
        define('GENESIS_PRO_IMAGES_DIR', GENESIS_PRO_DIR . '/images');
        define('GENESIS_PRO_CLASSES_DIR', GENESIS_PRO_DIR . '/classes');
        define('GENESIS_PRO_CONFIG', get_template_directory() . '/genesisConfig.json');
        define('GENESIS_PRO_DEFAULT_CONFIG', GENESIS_PRO_DIR . '/assets/genesisDefaultConfig.json');
        
        $table_name = $wpdb->prefix . "genesis_pro";
        define('GENESIS_PRO_TABLE_NAME', $table_name);

        /***********************************--Update Checker--**********************************/
        // Update Checker on http://genesis.mywsiportal.com/genesis-updates/wsi-theme-settings/.
        require GENESIS_PRO_CLASSES_DIR.'/plugin-update-checker.php';
        $GtUpdateChecker = new PluginUpdateChecker(
            'http://genesis.mywsiportal.com/genesis-updates/genesispro/metadata.json',
            __FILE__,
            'genesis-pro'
        );

        /*
         * Include classes
         */

        //widget assignment

        include(GENESIS_PRO_CLASSES_DIR.'/widgets/GPWidgetAssignment.php');
        $GPWidgetAssignment = new GPWidgetAssignment();

        // common class
        include(GENESIS_PRO_CLASSES_DIR . '/GPCommon.php');
        $this->GPCommon = new GPCommon();
        
         // helper class
        include(GENESIS_PRO_CLASSES_DIR . '/GPHelper.php');
        $GPHelper = new GPHelper();
        
        //class for General Section
        include(GENESIS_PRO_CLASSES_DIR . '/general/GPGeneral.php');
        $GPGeneral = new GPGeneral();
        
        //class for security login options
        include(GENESIS_PRO_CLASSES_DIR . '/security/GPSecureLogin.php');
        $GPSecureLogin = new GPSecureLogin();

        include(GENESIS_PRO_CLASSES_DIR . '/security/GPSecureLoginHelper.php');
        $GPSecureLoginHelper = new GPSecureLoginHelper();
        
        //class for redirect options
        include(GENESIS_PRO_CLASSES_DIR . '/redirect/GPRedirect.php');
        $GPRedirect = new GPRedirect();
        
        //class for security options
        include(GENESIS_PRO_CLASSES_DIR . '/security/GPSecure.php');
        $GPSecure = new GPSecure();

         //class for settings
        include(GENESIS_PRO_CLASSES_DIR . '/settings/GPSettings.php');
        $GPSettings = new GPSettings();

        //class for social share options
        include(GENESIS_PRO_CLASSES_DIR . '/social/GPSocialShare.php');
        $GPSocialShare = new GPSocialShare();

        //class for social icons options
        include(GENESIS_PRO_CLASSES_DIR . '/social/GPSocialIcons.php');
        $GPSocialIcons = new GPSocialIcons();

        //class for social stream options
        include(GENESIS_PRO_CLASSES_DIR . '/social/GPSocialStream.php');
        $GPSocialStream = new GPSocialStream();
        
        add_action('admin_menu', array($this, 'gp_add_genesis_pro_menu'), 11);
        
        add_action('admin_enqueue_scripts', array($this, 'gp_enqueue_admin_script'));
        
        /*
         * activation hook
         */
        
        register_activation_hook(__FILE__, array($this, 'setup_genesis_pro_data')); 
      
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_script'));
    }
    
    public function setup_genesis_pro_data() {

        global $wpdb;

        $table_name = $wpdb->prefix . "genesis_pro";
        if(!($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)) {
            
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE $table_name (
              gp_meta_id int(9) NOT NULL AUTO_INCREMENT,
              gp_meta_key varchar(64) NOT NULL,
              gp_meta_value longtext NOT NULL,
              primary key(gp_meta_id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
        }
    }

    function gp_add_genesis_pro_menu() {
        if (is_super_admin() || is_admin()) {
            add_menu_page('Genesis Tools', 'Genesis Tools', 'edit_theme_options', 'genesis-pro', array(&$this, 'gp_pro_main_page'), GENESIS_PRO_IMAGES_URL . '/genesis-logo/admin_logo_active.png',51);
        }
    }
    
    
    

    function gp_pro_main_page() {
        
        if($_GET['block_msg'] == 1){ ?>
            <div id="message" class="updated notice notice-success is-dismissible">
                <p>User Unblocked Successfully!!</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        <?php } 
            $plugin_data = get_plugin_data(__FILE__);
        ?>
        <div class="wrap wsigenesis-theme-settings warp7" id="genesis_branding">

            <div id="tool-options" class="warp metabox-holder wsigensis_tabs">
                <div class="tm-content">
                    <!-- Start Of tabs-->

                    <!-- defining main tabs elements -->
                    <div id="tabs-container" class="tm-sidebar">
                        <div class="tm-sidebar-logo uk-panel"><img width="140" height="46" src="<?php echo GENESIS_PRO_IMAGES_URL.'logo.svg'; ?>" alt="">
                            <span class="version uk-text-small">Pro <?php echo $plugin_data['Version']; ?></span></div>
                        <div class="uk-panel">
                            <ul class="tabs uk-nav uk-nav-side">
                                <li class="uk-active"><a href="#"><i class="dashicons dashicons-admin-tools"></i> Genesis Tools</a></li>
                                <li><a href="#"><i class="dashicons dashicons-randomize"></i> Redirect</a></li>
                                <li><a href="#"><i class="dashicons dashicons-shield"></i> Security</a></li>
                                <li><a href="#"><i class="dashicons dashicons-admin-settings"></i> Settings</a></li>
                                <li><a href="#"><i class="dashicons dashicons-share"></i> Social</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- defining top menu -->
                    <div id="nav-container" class="tm-main">
                        <div class="nav uk-form tm-form" >
                            <form id="gp_general" name ="general" method="post" alt="gp_update">
                                <?php
                                $general_settings = $this->GPCommon->get_gp_json_meta('gp_general_settings');
                                require_once(GENESIS_PRO_VIEWS_DIR . '/general.php');
                                ?>
                                <div class="action-wrap">
                                    <input class="button-primary" type="submit" value="Save Changes" name="gp_general_submit" id="gp_general_submit" />
                                    <input type="hidden" name="command" value="gp_general_settings" />
                                    <input type="hidden" name="action" value="gp_save_settings" />
                                </div>
                            </form>
                        </div>
                        <div class="nav uk-form tm-form" style="display: none;">
                            <?php
                            $redirect_settings = $this->GPCommon->get_gp_json_meta('gp_redirect_settings');
                            $redirect_data = $this->GPCommon->get_gp_meta('gp_redirect_data', true);
                            require_once(GENESIS_PRO_VIEWS_DIR . '/redirect.php');
                            ?>
                        </div>
                        <div class="nav uk-form tm-form" style="display: none;">
                            <form id="gp_secure" name="secure" method="post" alt="gp_update">
                                <?php
                                $secure_settings = $this->GPCommon->get_gp_json_meta('gp_secure_settings');
                              
                                require_once(GENESIS_PRO_VIEWS_DIR . '/security.php');
                                ?>
                                <div class="action-wrap">
                                    <input class="button-primary" type="submit" value="Save Changes" name="gp_secure_submit" id="gp_secure_submit" />
                                    <input type="hidden" name="command" value="gp_secure_settings" />
                                    <input type="hidden" name="action" value="gp_save_settings" />
                                </div>
                            </form>
                        </div>
                        <div class="nav uk-form tm-form" style="display: none;">
                            <form id="gp_wsi_settings" name="settings" method="post" alt="gp_update">
                                <?php
                                $wsi_settings = $this->GPCommon->get_gp_json_meta('gp_wsi_settings');
                                $sitemap_settings = $this->GPCommon->get_gp_meta('gp_sitemap_settings',true);
                                $header_scripts =  ($this->GPCommon->get_gp_meta('gp_header_script',true))?$this->GPCommon->get_gp_meta('gp_header_script',true):'';
                                $footer_scripts =  ($this->GPCommon->get_gp_meta('gp_footer_script',true))?$this->GPCommon->get_gp_meta('gp_footer_script',true):'';
                                
                                require_once(GENESIS_PRO_VIEWS_DIR . '/settings.php');
                                ?>
                                <div class="action-wrap">
                                    <input class="button-primary" type="submit" value="Save Changes" name="gp_wsi_settings_submit" id="gp_wsi_settings_submit"/>
                                    <input type="hidden" name="command" value="gp_wsi_settings" />
                                    <input type="hidden" name="action" value="gp_save_settings" />
                                </div>
                            </form>
                        </div>
                        <div class="nav uk-form tm-form" style="display: none;">
                            <div class="gp-view-wrap">
                                <h1 class="uk-article-title">Social Options</h1>
                                <hr class="gp-article-divider">
                                <div id="gp_ver_tab_wrap" class="gp_ver_tab_wrap">
                                    <ul class="vtab">
                                        <li class="vtab-active"><a href="javascript:void(0)" rel="gp_social_icon_wrap">Social Icons</a></li>
                                        <li><a href="javascript:void(0)" rel="gp_social_share_wrap">Social Share</a></li>
                                        <li><a href="javascript:void(0)" rel="gp_social_stream_wrap">Social Stream</a></li>
                                    </ul>
                                    <div class="vtab-content" id="gp_social_icon_wrap">
                                        <?php
                                            $social_icon_settings = $this->GPCommon->get_gp_json_meta('gp_social_icon_settings');
                                            $gp_social_icons = $this->GPCommon->get_gp_meta('gp_social_icons', true);
                                            require_once(GENESIS_PRO_VIEWS_DIR . '/social/social_icons.php');
                                        ?>
                                    </div>
                                    <div class="vtab-content" id="gp_social_share_wrap">
                                        <?php
                                            $social_share_settings = $this->GPCommon->get_gp_json_meta('gp_social_share_settings');
                                            require_once(GENESIS_PRO_VIEWS_DIR . '/social/social_share.php');
                                        ?>
                                    </div>
                                    <div class="vtab-content" id="gp_social_stream_wrap">
                                        <?php
                                        require_once(GENESIS_PRO_VIEWS_DIR . '/social/social_stream.php');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Of Tabs -->
                    <div style="clear:both"></div>
                </div><!-- end of tm-content-->
            </div>
        </div>
        <?php
    }
    
  function gp_enqueue_admin_script($hook){
        wp_enqueue_style('gp_admin_icon_style', GENESIS_PRO_CSS_URL . 'gpAdminIconStyle.css');
        if ('toplevel_page_genesis-pro' != $hook) {
            return;
        }
        
        if (!wp_script_is('jquery-ui-core', 'enqueued')) {
            wp_enqueue_script('jquery-ui-core');
        }
        
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-sortable');

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        
        wp_enqueue_style('gp_admin_style', GENESIS_PRO_CSS_URL . 'gpAdminStyle.css');
        wp_enqueue_style('gp_admin_social_style', GENESIS_PRO_CSS_URL . 'gpAdminSocialStyle.css');
        wp_enqueue_style('gp_post_admin_style', GENESIS_PRO_CSS_URL . 'ssl-post-select.css');
        wp_enqueue_style('gp_fonticonpicker_style', GENESIS_PRO_CSS_URL . 'jquery.fonticonpicker.min.css');
        wp_enqueue_style('gp_fonticonpicker_theme_style', GENESIS_PRO_CSS_URL . 'jquery.fonticonpicker.grey.min.css');
        wp_enqueue_style('gp_fontawesome_style', GENESIS_PRO_CSS_URL . 'font-awesome.min.css');
        wp_enqueue_script('gp_fonticonpicker_script', GENESIS_PRO_JS_URL . 'jquery.fonticonpicker.min.js');
        wp_enqueue_script('gp_admin_script', GENESIS_PRO_JS_URL . 'gpAdminScript.js');
        wp_enqueue_script('gp_post_admin_script', GENESIS_PRO_JS_URL . 'ssl-post-select.js');
        
    }

    public function enqueue_frontend_script() {
        wp_enqueue_script('gp_socialshare_script', GENESIS_PRO_JS_URL . 'gpSocialShare.min.js', array( 'jquery' ));
        wp_enqueue_style('gp_social_style', GENESIS_PRO_CSS_URL . 'gpSocialStyle.min.css');
        wp_enqueue_style('gp_fontawesome_style', GENESIS_PRO_CSS_URL . 'font-awesome.min.css');
        wp_localize_script('gp_socialshare_script', 'wsis', array('ajax_url' => admin_url('admin-ajax.php'), 'home_url' => home_url()));
    }  

}

$GenesisPro = new GenesisPro();

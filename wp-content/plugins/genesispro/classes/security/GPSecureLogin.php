<?php

/*
 * Class : GPSecureLogin
 * Package: Genesis Tools
 * Description: Includes all login Security options for genesis pro
 * Author: Swapnil Ghone
 * Since: 19-November-2016
 * Last Modified: 29-November-2016
 */

class GPSecureLogin extends GPCommon {

    var $secure_settings;
    var $helperobj;

    function __construct() {

        $this->secure_settings = parent::get_gp_json_meta('gp_secure_settings', true);
        $this->helperobj = new GPHelper();


        add_action('wp_loaded', array($this, 'gp_secure_login_theme_active'));

        add_filter('wp_redirect', array($this, 'gp_filter_login_url'), 10, 2);
        add_filter('lostpassword_url', array($this, 'gp_filter_login_url'), 10, 2);
        add_filter('site_url', array($this, 'gp_filter_login_url'), 10, 2);

        add_filter('login_url', array($this, 'gp_modify_login_url'), 10, 3);

        add_action('plugins_loaded', array($this, 'plugins_loaded'), 11);
        add_filter( 'wp_login_errors', array($this, 'wsi_login_errors_modify'), 10, 2 );
    }

    public function wsi_login_errors_modify($errors, $redirect_to) {
        if(isset($errors->errors['confirm'][0]) && $errors->errors['confirm'][0]=="Check your email for the confirmation link."){
            unset($errors->errors['confirm'][0]);
           $errors->add('confirm', __('Check your e-mail address linked to the account for the confirmation link,<br/><font style="color: red;">(including the spam or junk folder)</font>'), 'message');
        }
       return $errors;
    }

    // Manage to proper redirect to genesis-login page
    public function gp_secure_login_theme_active() {

        global $wp_db_version;
        if ($this->secure_settings['secure_login'] && !( isset($_REQUEST['action']) && $_REQUEST['action'] == 'postpass' )) {

            $login_page = parent::get_gp_meta('gp_login_theme_page_id');
//                var_dump($login_page);
            if (!get_permalink($login_page) || get_permalink($login_page) == '' || $this->helperobj->gp_check_post_by_title('Genesis Login', 'login') == '') {
                $this->gp_create_login_page();
            }


            $restricted_before_login = array('login', 'wp-login.php', 'wp-admin');
            $restricted_after_login = array('login', 'wp-login.php');
            $req_url = preg_replace('/\/|(\?.*)/i', '', $_SERVER["REQUEST_URI"]);

            //Skip if it is ajax call
            if (strstr($_SERVER["REQUEST_URI"], 'admin-ajax.php') === FALSE) {
                if ((in_array($req_url, $restricted_before_login) || strstr($req_url, 'wp-admin')) && !is_user_logged_in()) {
                    if (( isset($_GET['loggedout']) && ( $_GET['loggedout'] == 'true' ))) {
                        header("Cache-Control: no-cache, must-revalidate");
                        header("Location: " . home_url(), true, 302);
                    } else {
                        header("Cache-Control: no-cache, must-revalidate");
                        header("Location: " . home_url('not-found'), true, 302);
                    }
                    exit;
                } else if (in_array($req_url, $restricted_after_login) && is_user_logged_in() && !( isset($_GET['action']) && ( $_GET['action'] == 'logout' ) )) {
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Location: " . home_url(), true, 302);
                }
            }


            $current_url_info = parse_url($_SERVER['REQUEST_URI']);
            $current_url = $current_url_info['path'];

            // get login page
            $login_post = get_post(parent::get_gp_meta('gp_login_theme_page_id'));

            $login_path = site_url($login_post->post_name, 'relative');
            $login_path_trailing_slash = site_url($login_post->post_name . '/', 'relative');

            if ($current_url === $login_path || $current_url === $login_path_trailing_slash) {

                if (get_option('db_version') == $wp_db_version) {

                    if (is_user_logged_in()) { //if user logged in
                        header("Cache-Control: no-cache, must-revalidate");
                        wp_redirect(admin_url(), 301);
                    } else { //else show login form
                        header("Cache-Control: no-cache, must-revalidate");
                        if (!function_exists('login_header')) {
                            include( ABSPATH . 'wp-login.php' );
                            exit;
                        }
                    }
                    exit();
                } else {
                    header("Cache-Control: no-cache, must-revalidate");
                    wp_redirect( admin_url( 'upgrade.php?_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ));
                    exit();
                    //echo "hii"; die;
                }
            }
        }elseif ($this->secure_settings['secure_login'] && ( isset($_REQUEST['action']) && $_REQUEST['action'] == 'postpass' )) {
            /*
             * handel protected post request
             */
            error_reporting( 0 );
            @ini_set( 'display_errors', 0 );

            status_header( 200 ); //its a good login page. make sure we say so

            //include the login page where we need it
            if ( ! function_exists( 'login_header' ) ) {
                    include( ABSPATH . '/wp-login.php' );
                    exit;
            }

            //Take them back to the page if we need to
            if ( isset( $_SERVER['HTTP_REFERRER'] ) ) {
                    wp_redirect( sanitize_text_field( $_SERVER['HTTP_REFERRER'] ) );
                    exit();
            }
        }
    }

    function gp_clear_lockout_data() {
        /*
         * clears the lockout field once daily
         */
        parent::update_gp_meta('login_failed_ips_record', '');
    }

    public function gp_create_login_page() {

        $my_post = array(
            'post_title' => 'Genesis Login',
            'post_name' => 'genesis-login',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'login',
            'post_author' => 1,
        );

        if ($this->helperobj->gp_check_post_by_title('Genesis Login', 'login') != '') {
            $login_page_id = $this->helperobj->gp_check_post_by_title('Genesis Login', 'login');
        } else {
            
            $this->helperobj->delete_all_posts_in_post_type('login'); 
            $login_page_id = wp_insert_post($my_post);
        }
        // Insert the post into the database
        parent::update_gp_meta('gp_login_theme_page_id', $login_page_id);
    }

    function gp_is_user_lockout() {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (is_array($login_failed_ips_record) && isset($login_failed_ips_record[$user_ip]['lockout_time'])) {

            $diff = ( time() - $login_failed_ips_record[$user_ip]['lockout_time'] );
            if (($this->secure_settings['time_lockout_unit'] == 'min') && ( ($diff / 60) < $this->secure_settings['time_lockout'])) {
                return true;
            } elseif ($this->secure_settings['time_lockout_unit'] == 'hour' && ( ($diff / (60 * 60))) < $this->secure_settings['time_lockout']) {
                return true;
            }
        }
        return false;
    }

    function gp_get_lockout_time_remaining() {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $time_diff = 0;
        if (is_array($login_failed_ips_record) && isset($login_failed_ips_record[$user_ip])) {
            if (isset($login_failed_ips_record[$user_ip]['lockout_time'])) {

                $diff = ( time() - $login_failed_ips_record[$user_ip]['lockout_time'] );

                if ($this->secure_settings['time_lockout_unit'] == 'min') {

                    $time_diff = $this->secure_settings['time_lockout'] - ($diff / 60);
                } elseif ($this->secure_settings['time_lockout_unit'] == 'hour') {
                    $time_in_min = $this->secure_settings['time_lockout'] * 60;
                    $time_diff = $time_in_min - ($diff / 60);
                }
            }
        }

        return round(abs($time_diff));
    }

    /**
     * Filters redirects for correct login URL
     *
     * @since 4.0
     *
     * @param  string $url URL redirecting to
     *
     * @return string       Correct redirect URL
     */
    function gp_filter_login_url($url) {
        
        if ($this->secure_settings['secure_login']){
            $url = str_replace('wp-login.php', 'genesis-login', $url);
        }
        
        return $url;
    }

    function gp_modify_login_url($login_url, $redirect, $force_reauth) {
        
        if ($this->secure_settings['secure_login']){
            $login_page = home_url('/genesis-login/');
            $login_url = add_query_arg('redirect_to', $redirect, $login_page);
        }
        return $login_url;
    }

    /**
     * Actions for plugins loaded.
     *
     * Makes certain logout is processed on NGINX.
     *
     * @return void
     */
    public function plugins_loaded() {

        if (is_user_logged_in() && isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'logout') {

            check_admin_referer('log-out');
            wp_logout();

            $redirect_to = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : 'wp-login.php?loggedout=true';
            wp_safe_redirect($redirect_to);
            exit();
        }
    }

}

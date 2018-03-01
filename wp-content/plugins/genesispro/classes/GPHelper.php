<?php

/*
 * Class : GPHelper
 * Package: Genesis Pro
 * Description: Includes all genesis pro helper functions
 * Author: Swapnil Ghone
 * Since: 19-November-2016
 * Last Modified: 29-November-2016
 */

class GPHelper extends GPCommon {

    function __construct() {
        add_action('wp_ajax_gp_save_settings', array(&$this, 'gp_save_settings_callback'));
        add_action('wp_loaded', array(&$this, 'gp_check_config_exists'));
    }

    public function gp_save_settings_callback() {
        global $wpdb;
        $command = $_POST['command'];
        switch ($command) {
            case 'gp_general_settings' :

                $logos['admin_logo'] = str_replace(site_url(), '', $_POST['gp_general']['admin_logo']);
                $logos['favicon'] = str_replace(site_url(), '', $_POST['gp_general']['favicon']);
                $logos['login_logo'] = str_replace(site_url(), '', $_POST['gp_general']['login_logo']);
                $GPGeneral = new GPGeneral();
                $GPGeneral->gp_replace_default_favicon($logos['favicon']);
                parent::update_gp_json_meta('gp_general_settings', $logos);

                break;
            case 'gp_secure_settings' :


                $current_securtiy_settings = parent::get_gp_json_meta('gp_secure_settings');

                $GPSecureLogin = new GPSecureLogin();
                $GPSecure = new GPSecure();

                parent::update_gp_json_meta('gp_secure_settings', $_POST['gp_secure']);

                // For Login Theme
                $current_secure_login_status = $current_securtiy_settings['secure_login'];

                if ($_POST['gp_secure']['secure_login'] != $current_secure_login_status) {

                    if ($_POST['gp_secure']['secure_login']) {
                        $GPSecureLogin->gp_create_login_page();
                    }
                }

                // For Disable Comments and Ping back
                if ($current_securtiy_settings['en_di_comment_ping'] != $_POST['gp_secure']['en_di_comment_ping']) {

                    if ($_POST['gp_secure']['en_di_comment_ping']) {
                        $GPSecure->gp_disable_comment_pingback();
                    } else {
                        $GPSecure->gp_enable_comment_pingback();
                    }
                }

                // For force ssl option

                $current_ssl_entire = $current_securtiy_settings['force_ssl_entire'];

                if ($current_ssl_entire != $_POST['gp_secure']['force_ssl_entire']) {

                    $cnf_file = get_home_path() . 'wp-config.php';
                    $fr = fopen($cnf_file, 'r');
                    //if config file is readable
                    if ($fr) {
                        // if force ssl option is set as active for entire site
                        if ($_POST['gp_secure']['force_ssl_entire']) {
                            /*
                             * If options is set active then we check for define('FORCE_SSL_ADMIN', true) line in wp-config
                             * if not found then we add that line to wp-config file
                             */
                            $GPSecure->gp_enable_entire_ssl();
                        } else { // if force ssl option is removed
                            /*
                             * If options is disable then we check for define('FORCE_SSL_ADMIN', true) line in wp-config
                             * if found we remove that line
                             */
                            $GPSecure->gp_disable_entire_ssl();
                        }
                    }
                }

                break;

            case 'gp_redirect_settings' :
                parent::update_gp_json_meta('gp_redirect_settings', $_POST['gp_redirect']);
                break;

            case 'gp_wsi_settings' :
               
                $sitemap_page = get_page_by_path('sitemap');

                if (!is_null($sitemap_page) && $sitemap_page->ID && $_POST['gp_settings']['sitemap']) {
                    parent::update_gp_meta('sitemap_page', $sitemap_page->ID);
                } else {
                    $current_wsi_settings = parent::get_gp_json_meta('gp_wsi_settings');
                    if ($current_wsi_settings['sitemap'] != $_POST['gp_settings']['sitemap']) {
                        if ($_POST['gp_settings']['sitemap']) {
                            if (is_null($sitemap_page) || !$sitemap_page->ID) {
                                // Create post object
                                $sitemap_args = array(
                                    'post_title' => 'Sitemap',
                                    'post_content' => '',
                                    'post_status' => 'publish',
                                    'post_type' => 'page',
                                    'post_author' => 1,
                                );
                                // Insert the post into the database
                                $id = wp_insert_post($sitemap_args);
                                parent::update_gp_meta('sitemap_page', $id);
                            }
                        } else {
                            wp_delete_post(parent::get_gp_meta('sitemap_page'), true);
                        }
                    }
                }
                
                if($_POST['gp_header_script']){
                    parent::update_gp_meta('gp_header_script', stripcslashes($_POST['gp_header_script']));
                }else{
                    parent::delete_gp_meta('gp_header_script');
                }
                
                if($_POST['gp_footer_script']){
                    parent::update_gp_meta('gp_footer_script', stripcslashes($_POST['gp_footer_script']));
                }else{
                     parent::delete_gp_meta('gp_footer_script');
                }
                
                parent::update_gp_json_meta('gp_wsi_settings', $_POST['gp_settings']);
                parent::update_gp_meta('gp_sitemap_settings', maybe_serialize($_POST['gp_sitemap_settings']));
                break;

            case 'gp_social_share_settings' :

                parent::update_gp_json_meta('gp_social_share_settings', $_POST['gp_socialshare']);
                break;
            case 'gp_social_icon_settings' :

                $icons_array = array();
                if (isset($_POST['gp_socialicon']['social']) && is_array($_POST['gp_socialicon']['social'])) {
                    $icons = $_POST['gp_socialicon']['social'];
                    unset($_POST['gp_socialicon']['social']);
                    for ($i = 0; $i < count($icons['icon']); $i++) {
                        if ($icons['icon'][$i] != '') {
                            $icons_array[] = array(
                                'type' => $icons['type'][$i],
                                'icon' => $icons['icon'][$i],
                                'url' => $icons['url'][$i],
                                'alt' => $icons['alt'][$i],
                            );
                        }
                    }
                }

                parent::update_gp_json_meta('gp_social_icon_settings', $_POST['gp_socialicon']);
                parent::update_gp_meta('gp_social_icons', maybe_serialize($icons_array));
                break;
            case 'gp_social_stream_fb_setting' :
                $gp_social_stream_fb_setting = maybe_serialize($_POST['gp_social_stream_fb']);
                parent::update_gp_meta('gp_social_stream_fb_setting', $gp_social_stream_fb_setting);
                break;
            case 'gp_social_stream_twitter_setting' :
                $gp_social_stream_twitter_setting = maybe_serialize($_POST['gp_social_stream_twitter']);
                parent::update_gp_meta('gp_social_stream_twitter_setting', $gp_social_stream_twitter_setting);
                break;
            case 'gp_social_stream_gplus_setting' :
                $gp_social_stream_gplus_setting = maybe_serialize($_POST['gp_social_stream_gplus']);
                parent::update_gp_meta('gp_social_stream_gplus_setting', $gp_social_stream_gplus_setting);
                break;
        }
        exit();
    }

    public function delete_all_posts_in_post_type($post_type) {

        $login_post = get_posts(array('post_type' => $post_type));
        if (!is_array($login_post))
            return;
        foreach ($login_post as $mypost) {
            wp_delete_post($mypost->ID, true);
        }
    }

    function gp_check_post_by_title($page_title, $post_type = 'post', $output = OBJECT) {
        global $wpdb;
        $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type= %s", $page_title, $post_type));
        return $post_id ? $post_id : '';
    }

    function gp_check_config_exists() {
        /*
         * if genesis-config file dosent exists then create new file from genesis.default.config and revert all settings to original
         */
        if (!file_exists(GENESIS_PRO_CONFIG)) {
            parent::gp_create_deafult_file_copy();
        }
        $db_version = get_option('wsi_config_version', '0.0.0');
        $config_data = parent::get_gp_json_default_meta('gp_config_data');
        if ($db_version != $config_data['config_version']) {
            parent::gp_update_db_version();
        }
        return;
    }

}

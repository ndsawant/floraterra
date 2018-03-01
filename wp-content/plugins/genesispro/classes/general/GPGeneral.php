<?php
/*
 * Class : GPGeneral
 * Package: Genesis Pro
 * Description: Includes all the general option on genesis Pro page
 * Author: Swapnil Ghone
 * Since: 21-Nov-2016
 * Last Modified: 29-Nov-2016
 */

class GPGeneral extends GPCommon{
    
    var $gp_general_settings;
    
    function __construct() {
        
        $this->gp_general_settings = parent::get_gp_json_meta('gp_general_settings',true);
        // if value is not set then make default selection
        if($this->gp_general_settings == 0){ 
            $default = array(
                'admin_logo' => '/wp-content/plugins/genesispro/images/genesis-logo/admin_logo.png',
                'favicon' => '/wp-content/plugins/genesispro/images/genesis-logo/favicon.ico',
                'login_logo' =>'/wp-content/plugins/genesispro/images/genesis-logo/login_logo.png'   
            );
            parent::update_gp_meta('gp_general_settings',maybe_serialize($default));
            $this->gp_general_settings = parent::get_gp_json_meta('gp_general_settings',true);
        }
        
        add_action( 'login_enqueue_scripts',array($this, 'gp_custom_login_logo'));
        add_action('wp_before_admin_bar_render', array($this,'gp_adminbar_logo'), 0);
        add_filter('get_site_icon_url',array($this,'gp_change_site_icon_url'));
    }

    function gp_custom_login_logo() {
        if(is_array($this->gp_general_settings) && isset($this->gp_general_settings['login_logo']) && $this->gp_general_settings['login_logo']!=''){
        ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo site_url().$this->gp_general_settings['login_logo']; ?>) !important;
            background-size: contain !important;
            height: 70px !important;
            padding-bottom: 30px;
            width: 320px !important;
        }
        .g-recaptcha{transform:scale(0.895);-webkit-transform:scale(0.895);transform-origin:0 0;-webkit-transform-origin:0 0;margin-top: 10px;}
    </style>
    <?php
        }
    }
    
    function gp_adminbar_logo(){
        if(is_array($this->gp_general_settings) && isset($this->gp_general_settings['admin_logo']) && $this->gp_general_settings['admin_logo']!=''){ ?>
            <style type="text/css">
                #wp-admin-bar-wp-logo { display:none; } 
                #wpadminbar #wp-admin-bar-site-name > .ab-item:before { content: normal;}
                #wpadminbar > #wp-toolbar #wsi_admin_logo span.ab-icon {
                    background-image: url("<?php echo site_url().$this->gp_general_settings['admin_logo']; ?>") !important;
                    background-position: center center;
                    background-repeat: no-repeat;
                    background-size: 100% auto;
                    height: 20px;
                    top: 4px;
                    width: 20px;
                  }
                <?php
                    if($this->gp_general_settings['admin_logo'] == '/wp-content/plugins/genesispro/images/genesis-logo/admin_logo.png'){
                ?>
                  #wpadminbar > #wp-toolbar #wsi_admin_logo span.ab-icon:hover {
                    background-image: url("<?php echo site_url(); ?>/wp-content/plugins/genesispro/images/genesis-logo/admin_logo_hover.png") !important;
                  }
                    <?php } ?>
            </style>
            <script type="text/javascript"> 
                jQuery(document).ready(function($){
                    $("#wp-admin-bar-root-default").prepend(" <li id=\"wsi_admin_logo\"><a href=\"http://wsigenesis.com/\" target=\"_blank\" class=\"ab-item\"><span class=\"ab-icon\"></span></a> </li> ");
                    // active image
                    $("#toplevel_page_genesis-pro a.current div.wp-menu-image img").attr('src','<?php echo site_url(); ?>/wp-content/plugins/genesispro/images/genesis-logo/admin_logo_active.png');
                    
                    // hover image
                    $("#toplevel_page_genesis-pro a").hover(function(){
                        if(!$("#toplevel_page_genesis-pro a").hasClass('current'))
                            $("#toplevel_page_genesis-pro a div.wp-menu-image img").attr('src','<?php echo site_url(); ?>/wp-content/plugins/genesispro/images/genesis-logo/admin_logo_hover.png');
                    },function(){
                        $("#toplevel_page_genesis-pro a div.wp-menu-image img").attr('src','<?php echo site_url(); ?>/wp-content/plugins/genesispro/images/genesis-logo/admin_logo_active.png');
                    })
                })
            </script>
        <?php }
    }
    
    function gp_change_site_icon_url($url){
        if(is_array($this->gp_general_settings) && isset($this->gp_general_settings['favicon']) && $this->gp_general_settings['favicon']!=''){ 
            $url = site_url().$this->gp_general_settings['favicon'];
        }
        return $url;
    }
    
     /**
     * Replace root and theme icon with plugin custom icon
     * @param string $icon_url Path of icon starting from /wp-content
     */
    function gp_replace_default_favicon($icon_url = '') {
        if ($icon_url && defined('ABSPATH')) {
            $icon_url = ltrim($icon_url, '/');
            $icon_path = ABSPATH . $icon_url;            
            $theme_path = get_stylesheet_directory();
            if (file_exists($icon_path)) {
                copy($icon_path,ABSPATH. '/favicon.ico');
                copy($icon_path, $theme_path . '/favicon.ico');
            }
        }
    }
  
}


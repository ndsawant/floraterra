<?php
/*
 * Class : GPSocialShare
 * Package: Genesis Pro
 * Description: Includes all the redirect features
 * Author: Swapnil Ghone
 * Since: 5-Feburary-2016
 * Last Modified: 5-Feburary-2016
 */

class GPSocialShare extends GPCommon {

    var $gp_socailshare_settings;

    function __construct() {

        $this->gp_socailshare_settings = parent::get_gp_json_meta('gp_social_share_settings', true);
        add_filter('the_content',array(&$this,'gp_bind_social_icons'),15);
        
        /* Social Share service count */
        add_action( 'wp_ajax_GPSocial_share_counts_api', array( &$this, 'gp_social_share_counts_api_callback' ) );
        add_action( 'wp_ajax_nopriv_GPSocial_share_counts_api', array( &$this, 'gp_social_share_counts_api_callback' ) );
        
        /*
         * Shortcode for social share
         */
        add_shortcode('wsi_social_share', array( &$this,'wsi_social_share_callback'));
        
    }
     
    function gp_bind_social_icons($content){
        
        global $post;

//        var_dump($this->gp_socailshare_settings);
        if($this->gp_socailshare_settings['post_above_content']||$this->gp_socailshare_settings['post_below_content']||$this->gp_socailshare_settings['page_above_content']||$this->gp_socailshare_settings['page_below_content']||$this->gp_socailshare_settings['category_above_content']||$this->gp_socailshare_settings['category_below_content']){
                
            if(!is_main_query() || !in_the_loop()){
                return $content;
            }
                
                $share_data_config = array(
                    'permalink' => get_permalink($post->ID),
                    'title' => get_the_title($post->ID),
                    'theme' => $this->gp_socailshare_settings['theme'],
                    'heading' => $this->gp_socailshare_settings['heading'],
                    'layout' => $this->gp_socailshare_settings['layout'],
                    'size' => $this->gp_socailshare_settings['size'],
                    'counter' => $this->gp_socailshare_settings['counter'],
                    'service' => $this->gp_socailshare_settings['service']
                );
                
                if(has_post_thumbnail()){
                    $share_data_config['media'] = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                }else{
                    $share_data_config['media'] = GENESIS_PRO_IMAGES_URL.'WSI-logo.jpg';
                }
                
            
                $class =  'gp-social-share'.' theme-'.$this->gp_socailshare_settings['theme'].' layout-'.$this->gp_socailshare_settings['layout'].' size-'.$this->gp_socailshare_settings['size'].' counter-'.$this->gp_socailshare_settings['counter'];
                
            if(is_single()) {
                
                
                if($this->gp_socailshare_settings['post_above_content']){
                    $content = "<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>".$content;
                }
                
                if($this->gp_socailshare_settings['post_below_content']){
                    $content = $content."<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>";
                }
                
            }  elseif(is_page()) {
              
                if($this->gp_socailshare_settings['page_above_content']){
                    $content = "<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>".$content;
                }
                
                if($this->gp_socailshare_settings['page_below_content']){
                    $content = $content."<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>";
                }
                
            } elseif (is_archive()) {
              
                if($this->gp_socailshare_settings['category_above_content']){
                    $content = "<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>".$content;
                }
                
                if($this->gp_socailshare_settings['category_below_content']){
                    $content = $content."<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>";
                }
            }
        }
      
        return $content;
    }

    function gp_social_share_counts_api_callback(){
        include(GENESIS_PRO_CLASSES_DIR.'/social/GPSocialShareCountAPI.php');
        $GPSocialShareCurl = new GPSocialShareCurl($_POST['url'],$_POST['services']); 
        $result = $GPSocialShareCurl->get_counts();
        header('Content-Type: application/json');
        header('Cache-Control: max-age=180'); // 3 minutes
        echo json_encode($result);
        exit;
    }
    
    function wsi_social_share_callback($atts){
        
        global $post;
        
        $atts = shortcode_atts( array(
            'ID' => $post->ID
        ),$atts,'wsi_social_share');

        $share_data_config = array();
        $share_data_config = array(
            'permalink' => get_permalink($post->ID),
            'title' => get_the_title($post->ID),
            'theme' => $this->gp_socailshare_settings['theme'],
            'heading' => $this->gp_socailshare_settings['heading'],
            'layout' => $this->gp_socailshare_settings['layout'],
            'size' => $this->gp_socailshare_settings['size'],
            'counter' => $this->gp_socailshare_settings['counter'],
            'service' => $this->gp_socailshare_settings['service']
        );
        
        if(has_post_thumbnail($post->ID)){
            $share_data_config['media'] = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
        }else{
            $share_data_config['media'] = GENESIS_PRO_IMAGES_URL.'WSI-logo.jpg';
        }
        $class =  'gp-social-share'.' theme-'.$this->gp_socailshare_settings['theme'].' layout-'.$this->gp_socailshare_settings['layout'].' size-'.$this->gp_socailshare_settings['size'].' counter-'.$this->gp_socailshare_settings['counter'];
        
        $content = "<div class='".$class."' data-gp-socialconfig='".  json_encode($share_data_config)."'></div>";

        return $content;
    }
}

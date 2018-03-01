<?php
/*
 * Class : GPSocialIcons
 * Package: Genesis Pro
 * Description: Includes social icons functionality
 * Author: Swapnil Ghone
 * Since: 8-MArch-2016
 * Last Modified: 8-March-2016
 */

class GPSocialIcons extends GPCommon {
    
   var $gp_social_icon_settings; 
   var $gp_social_icons; 
   
   function __construct() {
       $this->gp_social_icon_settings = parent::get_gp_json_meta('gp_social_icon_settings',TRUE);
       $this->gp_social_icons = parent::get_gp_meta('gp_social_icons',TRUE);
       add_shortcode('gp_social_icon',array($this,'gp_social_icon_callback'));
       
       // enqueue front end scripts
       add_action('wp_enqueue_scripts', array($this, 'enqueue_inline_social_script'),99);
   }
   
   function gp_social_icon_callback(){
       
       $blank = '';
       $res = '<ul class="gp_social_icons">';
       if(!empty($this->gp_social_icons)){
           if($this->gp_social_icon_settings['open_new_tab']){
               $blank = 'target="_blank"'; 
           }
           foreach ($this->gp_social_icons as $gsc){
               $res .='<li><a href="'.$gsc['url'].'" '.$blank.'  title="'.$gsc['alt'].'" >';
               if($gsc['type'] == 'custom_icon'){
                   $res .='<img alt="'.$gsc['alt'].'" title="'.$gsc['alt'].'" width="'.$this->gp_social_icon_settings['size'].'px" src="'.site_url().''.$gsc['icon'].'">';
               }else{
                   $res .='<i title="'.$gsc['alt'].'" class="'.$gsc['icon'].'"></i>';
               }
               $res .='</a></li>';
           }
       }
       $res .='</ul>';
       return $res;
   }
   
   function enqueue_inline_social_script(){
       // enqueue style for social icons
        $social_icon_style ='.gp_social_icons{ list-style: none !important; min-height:10px; } .gp_social_icons li{ float: left !important; margin: 0px 5px !important; } .gp_social_icons li a{ text-decoration: none; } .gp_social_icons a i { color:'.$this->gp_social_icon_settings['color'].'; font-size:'.$this->gp_social_icon_settings['size'].'px !important; } .gp_social_icons a:hover i { color:'.$this->gp_social_icon_settings['hover_color'].' }'; 
        wp_add_inline_style( 'gp_social_style', $social_icon_style );
   }
   
}

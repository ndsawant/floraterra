<?php
/*
 * Class : GPSettings
 * Package: Genesis Pro
 * Description: Include all settings tab option
 * Author: Swapnil Ghone
 * Since: 1-June-2016
 * Last Modified: 1-June-2016
 * 
 */

class GPSettings extends GPCommon {

    var $gp_wsi_settings;

    function __construct() {
        $this->gp_wsi_settings = parent::get_gp_json_meta('gp_wsi_settings');
        add_filter('the_title',array($this,'gp_disable_title'));
        add_action('widgets_init', array($this, 'gp_manage_widget'));
        add_filter('the_content', array(&$this, 'gp_filter_archive_content'));
        add_filter('pre_get_posts', array($this, 'gp_search_filter'));
        add_filter('plugins_loaded', array($this, 'gp_modify_auto_save'));
        add_filter('wp_revisions_to_keep', array($this,'gp_modify_revision'));
        
        // initalize feature page post widget
        add_action('widgets_init', array($this, 'gp_initalize_widgets'));
        
        /*
         * add order field to post
         */
        add_action( 'init',function(){
             add_post_type_support('post', 'page-attributes');
        });
        
        add_action('wp_head',array($this, 'enqueue_head_custom_script'));
        add_action('wp_footer',array($this, 'enqueue_footer_custom_script'));
                
    }
    
    function enqueue_head_custom_script(){
        
        $header_script = parent::get_gp_meta('gp_header_script',true);
        
        if($header_script && $header_script!='')
            echo $header_script;
            
    }
    
    function enqueue_footer_custom_script(){
        $footer_script = parent::get_gp_meta('gp_footer_script',true);
        
        if($footer_script && $footer_script!='')
            echo $footer_script;
        
    }

    function gp_disable_title($title){
        global $post;
        
        if (is_admin() || !in_the_loop() || is_page('sitemap'))
            return $title;
        
        if(!isset($this->gp_wsi_settings['gp_hide_title']) || !is_array($this->gp_wsi_settings['gp_hide_title']))
            return $title;
        
        if(in_array(get_post_type(),$this->gp_wsi_settings['gp_hide_title'])){
            $title = ''; 
        }
        
        return $title;
   }
   
   function gp_manage_widget(){
       
        // For Wordpress Default widget
        $default_widget_array = array(
            'WP_Widget_Categories',
            'WP_Widget_Pages',
            'WP_Widget_Recent_Posts',
            'WP_Widget_Tag_Cloud',
        );

        foreach ($default_widget_array as $dwa) {
            if($this->gp_wsi_settings['wp_widgets'][$dwa] == 0){
                unregister_widget($dwa);
            }
        }
   }
   
   function gp_filter_archive_content($content){
       
        if (is_admin() || !in_the_loop())
            return $content;
          
        if (is_archive()) {
            if ($this->gp_wsi_settings['post_content'] != 'content' && $this->gp_wsi_settings['content_limit']){
                if (!has_excerpt()){
                    $content = $this->gp_the_content_limit(strip_tags(strip_shortcodes($content)), $this->gp_wsi_settings['content_limit']);
                } else {
                    $content = $this->gp_the_content_limit(get_the_excerpt(), $this->gp_wsi_settings['content_limit']);
                }
            }
        } elseif (is_page() && $this->gp_wsi_settings['sitemap']) {
            global $post;
            // support to wpml
            if (function_exists('icl_object_id'))
                $sitempa_id = ( icl_object_id(parent::get_gp_meta('sitemap_page'), 'page', false, ICL_LANGUAGE_CODE) == $post->ID ) ? icl_object_id(parent::get_gp_meta('sitemap_page'), 'page', false, ICL_LANGUAGE_CODE) : parent::get_gp_meta('sitemap_page');
            else
                $sitempa_id = parent::get_gp_meta('sitemap_page');
            
            if($post->ID == $sitempa_id){
                require(GENESIS_PRO_CLASSES_DIR.'/settings/GPSitemap.php');
                return GPSitemap::gp_generate_sitemap();
            }
        }
        return $content;
   }
   
   // Set content limit
   function gp_the_content_limit( $content, $limit ) {
        $extension = ( $limit <= count( explode(' ', $content) ) ) ? '[...]' : '';
        $content = ($limit) ? implode(' ', array_slice(explode(' ', $content), 0, $limit)) : $content;
	return ($extension) ? $content.$extension : $content;
   }
   
   function gp_search_filter($query){
       
       if ($query->is_search) {
            add_filter('the_content', array($this, 'gp_search_content_filter'));
            if($this->gp_wsi_settings['search_per_page']){
                $query->set('posts_per_page',$this->gp_wsi_settings['search_per_page']);
            }
       }
       return $query;
   }
   
   function gp_search_content_filter($content){
       
       if (!$this->gp_wsi_settings['search_content_limit']){
            return $content;
       }
      
       return $this->gp_the_content_limit($content,$this->gp_wsi_settings['search_content_limit']);
   }
   
   function gp_modify_auto_save(){
        $wsits_autosave_limit = $this->gp_wsi_settings['auto_save'];
        if (!$wsits_autosave_limit) {
            $wsits_autosave_limit = 360;
        }
        define('AUTOSAVE_INTERVAL', $wsits_autosave_limit);
   }
   
   function gp_modify_revision($revisions){
       
       if($this->gp_wsi_settings['revision']){
           $revisions = $this->gp_wsi_settings['revision'];
       }else{
           $revisions = 5;
       }
       
       return $revisions;
   }
   
   function gp_initalize_widgets() {
       $wsi_widgets = array(
            'gp_featured_page_post' => 'GPFeaturedPagePost.php',
            'gp_categories' => 'GPCategories.php',
            'gp_tag_cloud' => 'GPTagCloud.php',
       );
       foreach ($wsi_widgets as $widget=>$file){
           if($this->gp_wsi_settings['widgets'][$widget]){
               include(GENESIS_PRO_CLASSES_DIR . '/widgets/'.$file);
           }
       }
   }
}

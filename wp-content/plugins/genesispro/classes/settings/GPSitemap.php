<?php

/*
 * Class : GPSitemap
 * Package: Genesis Pro
 * Description: Includes the sitemap options
 * Author: Swapnil Ghone
 * Since: 9-Jun-2016
 * Last Modified: 9-June-2016
 * 
 * @filters
 * wsi_sitemap_posts_args - filter post arguments
 * wsi_sitemap_page_args - filter page arguments
 * wsi_sitemap_category_args - filter category arguments
 * wsi_sitemap_archive_args - filter archive arguments
 * wsi_sitemap_author_args - filter author arguments
 */


class GPSitemap extends GPCommon{
    
    function gp_generate_sitemap(){
        
        $content = '<style>.children {  margin: 0;  padding: 0 20px !important; }</style>';
        $sitemap_options = parent::get_gp_meta('gp_sitemap_settings',true);
        if(!is_array($sitemap_options)){
            $sitemap_options = array(
                'pages' => array('showhide' => 1, 'orderby' => 'post_title', 'order' => 'asc'),
                'authors' => array('showhide' => 1, 'orderby' => 'post_title', 'order' => 'asc'),
                'categories' => array('showhide' => 1, 'orderby' => 'id', 'order' => 'asc'),
                'posts' => array('showhide' => 1,'orderby' => 'date','order' => 'asc'),
                'monthly' => array('showhide' => 1),
                'exclude_page'=>'',
                'exclude_author'=>'',
                'exclude_category'=>'',
                'exclude_post' => ''
             );
        }
        
        $content .='<div class="archive-page">';
        
        /*
         * pages
         */
        // Filter wsi_sitemap_exclude_pages
        $page_args = array(
            'title_li' => '',
            'echo' => 0,
            'sort_column' => $sitemap_options['pages']['orderby'],
            'sort_order'  => $sitemap_options['pages']['order'],
            'exclude'     => $sitemap_options['exclude_page']
        );
       
        if (isset($sitemap_options['pages']) && array_key_exists('showhide', $sitemap_options['pages']) && $sitemap_options['pages']['showhide']) { 
                $content .= '<h4>'.__('Pages:', 'wsigenesis').'</h4>';
                $content .= '<ul>'.wp_list_pages(apply_filters('wsi_sitemap_page_args',$page_args)).'</ul>';
        } 
        
        /*
         * categories
         */

        $cat_args = array(
            'title_li' => '',
            'echo' => 0,
            'hide_empty' => 0,
            'orderby' => $sitemap_options['categories']['orderby'],
            'order' => $sitemap_options['categories']['order'],
            'exclude' => $sitemap_options['exclude_category'],
        );
        
        if (isset($sitemap_options['categories']) && array_key_exists('showhide', $sitemap_options['categories']) && $sitemap_options['categories']['showhide']) {
                $content .= '<h4>'.__('Categories:', 'wsigenesis').'</h4>';
                $content .='<ul>'.wp_list_categories(apply_filters('wsi_sitemap_category_args',$cat_args)).'</ul>';
        }
        
        /*
         * authors
         */
        // Filter wsi_sitemap_exclude_authors
        $author_args = array(
            'orderby' => $sitemap_options['authors']['orderby'],
            'order'   => $sitemap_options['authors']['order'],
            'exclude' => $sitemap_options['exclude_author']
        );
        
        if (isset($sitemap_options['authors']) && array_key_exists('showhide', $sitemap_options['authors']) && $sitemap_options['authors']['showhide']) {
            $content .='<h4>'.__('Authors:', 'wsigenesis').'</h4>';
            $author_array = get_users(apply_filters('wsi_sitemap_author_args',$author_args));
            $content .='<ul>';
            foreach ($author_array as $author) {
                  $content .= '<li><a href="' . site_url() . '/author/' . $author->user_login . '">' . $author->display_name . "</a></li>";
            }
            $content .= '</ul>';
        }
        
        /*
         * monthly
         */
        
        if (isset($sitemap_options['monthly']) && array_key_exists('showhide', $sitemap_options['monthly']) && $sitemap_options['monthly']['showhide']) {
            $archive_args = array(
                'type' => 'monthly',
                'echo' => 0
            );
            $content .= '<h4>'.__('Monthly:', 'wsigenesis').'</h4>';
            $content .='<ul>'.wp_get_archives(apply_filters('wsi_sitemap_archive_args',$archive_args)).'</ul>';
        }
        
        /*
         * Posts 
         */
        $post_args = array(
            'orderby'       => $sitemap_options['posts']['orderby'],
            'order'         => $sitemap_options['posts']['order'],
            'exclude'       => $sitemap_options['exclude_post'],
            'numberposts'   => -1
        );
        
        if (array_key_exists('showhide', $sitemap_options['posts']) && $sitemap_options['posts']['showhide']) {
         
            $content .='<h4>'.__('Posts:', 'wsigenesis').'</h4>';

            $posts_array = get_posts(apply_filters('wsi_sitemap_posts_args', $post_args));
            $content .='<ul>';
            foreach ($posts_array as $post) {
                $content .='<li><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></li>';
            }
            $content .='</ul>';
       } 
        $content .='</div>';
        
        return $content;
    }
}

 
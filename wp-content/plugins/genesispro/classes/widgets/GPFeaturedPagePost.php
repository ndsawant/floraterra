<?php
/**
 * WSI Genesis Featured Page Post.
 *
 * @package WSI Genesis
 * @subpackage Widgets
 * @since May 2016
 * @dev Swapnil Ghone
 */
/* To register Widget */
register_widget('GP_Featured_Page_Post_widget');

class GP_Featured_Page_Post_widget extends WP_Widget {

    /**
     * Constructor. Set the default widget options and create widget.
     */
    function __construct() {
        $this->defaults = array(
            'title' => '',
            'title_link' => 1,
            'posts_cat' => array(),
            'posts_type' => 'post',
            'include_exclude' => '',
            'include_exclude_filter' => '',
            'posts_num' => 1,
            'posts_offset' => 0,
            'orderby' => '',
            'order' => '',
            'show_date' => 0,
            'show_image' => 0,
            'show_image_link' => 1,
            'image_alignment' => '',
            'image_size' => '',
            'show_title' => 0,
            'show_content' => 'excerpt',
            'content_limit' => '',
            'more_text' => __('[Read More...]', 'wsigenesis'),
            'more_from_category' => '',
            'more_from_category_text' => __('More Posts from this Category', 'wsigenesis'),
            'extra_class' => ''
        );

        $widget_ops = array(
            'classname' => 'gp-featured-page-post',
            'description' => __('Displays featured Posts/Pages with thumbnails', 'wsigenesis'),
        );

        $control_ops = array(
            'id_base' => 'gp-featured-page-post',
            'width' => 520,
            'height' => 450,
        );

        $this->WP_Widget('gp-featured-page-post', __('WSI Featured Page-Post', 'wsigenesis'), $widget_ops, $control_ops);
        
        add_action('admin_enqueue_scripts', array($this, 'gp_enqueu_admin_widget_assets'));
    }
    
    function gp_enqueu_admin_widget_assets(){
        wp_enqueue_style('gp_admin_widget_style', GENESIS_PRO_CSS_URL . 'gpAdminWidgetStyle.css');
        wp_enqueue_script('gp_admin_widget_script', GENESIS_PRO_JS_URL . 'gpAdminWidgetScript.js');
    }

    function widget($args, $instance) {
        
        extract($args);
        /** Merge with defaults */
        $instance = wp_parse_args((array) $instance, $this->defaults);
        echo $before_widget;

        /** Set up the author bio */
        if (!empty($instance['title']))
            echo $before_title . apply_filters('widget_title', $instance['title'], $instance, $this->id_base) . $after_title;



        /*
         *  build parameters for page
         */
        $query_args = array(
            'post_type' => $instance['posts_type'],
            'orderby' => $instance['orderby'],
            'order' => $instance['order']
        );

        if ($instance['posts_num']) {
            $query_args['showposts'] = $instance['posts_num'];
        }

        if ($instance['posts_offset']) {
            $query_args['offset'] = $instance['posts_offset'];
        }

        if ($instance['include_exclude'] == 'include') {
            $query_args['post__in'] = explode(',', $instance['include_exclude_filter']);
        }

        if ($instance['include_exclude'] == 'exclude') {
            $query_args['post__not_in'] = explode(',', $instance['include_exclude_filter']);
        }

        if ($instance['posts_type'] == 'page') {

            if ($instance['page_id']) {
                $query_args['page_id'] = $instance['page_id'];
            }
        } else {  // build parameters for post
            if (!empty($instance['posts_cat'])) {
                $query_args['category__in'] = $instance['posts_cat'];
            }
        }
//        _pre($query_args);
//        exit();
        $featured_posts = new WP_Query($query_args);

        global $post, $wpdb;

//        echo $wpdb->last_query;

        if ($featured_posts->have_posts()) :
            echo '<div class="' . $instance['extra_class'] . '">';
            while ($featured_posts->have_posts()) : $featured_posts->the_post();
                echo '<div class="' . implode(' ', get_post_class()) . '">';
                
                if (!empty($instance['show_image'])) {
                    if (has_post_thumbnail()) {
                        if ($instance['show_image_link']) {
                            printf(
                                    '<a href="%s" title="%s" class="%s">', get_permalink(), the_title_attribute('echo=0'), esc_attr($instance['image_alignment'])
                            );
                        }
                        the_post_thumbnail($instance['image_size'], array(
                            'class' => 'wsigenesis_featured_post_image size-auto ' . $instance['image_alignment'] . ' ',
                            'title' => $post->post_title
                                )
                        );
                        if ($instance['show_image_link'])
                            echo '</a>';
                    }
                }
                do_action('wsi_featured_post_before_title',get_the_ID());
                if (!empty($instance['show_title'])) {
                    if ($instance['title_link'])
                        printf('<h2><a href="%s" title="%s">%s</a></h2>', get_permalink(), the_title_attribute('echo=0'), get_the_title());
                    else
                        printf('<h2>%s</h2>', get_the_title());
                }
                do_action('wsi_featured_post_after_title',get_the_ID()); 
                
                if(!empty($instance['show_author']) || !empty($instance['show_date']) || !empty($instance['show_post_categories'])){
                ?>
                    <p class="uk-article-meta">
                        <?php
                        if (!empty($instance['show_author'])) {
                            echo apply_filters('wsi_featured_post_author_pre',' WRITTEN BY ');
                            the_author_posts_link();
                        }

                        if (!empty($instance['show_date'])) {
                            echo apply_filters('wsi_featured_post_date_pre',' POSTED ON ');
                            printf('<time>%s</time>', get_the_date(apply_filters('wsi_featured_post_date_format','')));
                        }

                        if (!empty($instance['show_post_categories'])) {
                            echo apply_filters('wsi_featured_post_category_pre',' POSTED IN ');
                            $categories = wp_get_post_categories (get_the_ID());
                            $temp_str = '';
                            if(!empty($categories)){
                                foreach ($categories as $cat){
                                    $temp_str .= '<a href="'.get_category_link($cat).'">'.get_the_category_by_ID($cat).'</a>,';
                                }
                                echo rtrim($temp_str,',');
                            }
                        }
                        ?>
                    </p>
                <?php
                }
                
                if (!empty($instance['show_social_icons']) && !empty($instance['social_icons_above_content'])) {
                    echo do_shortcode('[wsi_social_share id="'.get_the_ID().'"]');
                }
                if (!empty($instance['show_content'])) {
                    if ('excerpt' == $instance['show_content']) {
                        the_excerpt();
                        echo $link = sprintf('%s <a href="%s" class="more-link">%s</a>', '', get_permalink(), $instance['more_text']);
                    } elseif ('content-limit' == $instance['show_content'])
                        $this->limit_the_content((int) $instance['content_limit'], esc_html($instance['more_text']));
                    else
                        the_content(esc_html($instance['more_text']));
                }
                if (!empty($instance['show_social_icons']) && !empty($instance['social_icons_below_content'])) {
                    echo do_shortcode('[wsi_social_share id="'.get_the_ID().'"]');
                }
                echo '</div><!--end post_class()-->' . "\n\n";
               
            endwhile;
            echo '</div>';
        endif;

        if (!empty($instance['more_from_category']) && !empty($instance['posts_cat']) && $instance['posts_type'] == 'post')
            printf(
                    '<p class="more-from-category"><a href="%1$s" title="%2$s">%3$s</a></p>', esc_url(get_category_link($instance['posts_cat'][0])), esc_attr(get_cat_name($instance['posts_cat'][0])), esc_html($instance['more_from_category_text'])
            );
        
        echo $after_widget;
        wp_reset_query();
    }

    function update($new_instance, $old_instance) {
//        _pre($old_instance);
        $new_instance['title'] = strip_tags($new_instance['title']);
        $new_instance['title_link'] = strip_tags($new_instance['title_link']);
        $new_instance['show_image_link'] = strip_tags($new_instance['show_image_link']);
        $new_instance['more_text'] = strip_tags($new_instance['more_text']);
        return $new_instance;
    }

    function form($instance) {
        /** Merge with defaults */
        $instance = wp_parse_args((array) $instance, $this->defaults);
//        _pre($instance);
//        exit();
        if ($instance['posts_cat']) {
            if (!is_array($instance['posts_cat'])) {
                $instance['posts_cat'] = array($instance['posts_cat']);
            }
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wsigenesis'); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
        </p>
        <div class="genesis-widget"> 
            <div class="genesis-widget-column genesis-widget-left">

                <p class="feature-post-wrapper">
                    <label for="<?php echo $this->get_field_id('posts_type'); ?>"><?php _e('Post Type', 'wsigenesis'); ?>:</label>

                    <select name="<?php echo $this->get_field_name('posts_type'); ?>" id="<?php echo $this->get_field_id('posts_type'); ?>"  >
                        <option value="post">Posts</option>
                        <option value="page" <?php selected('page', $instance['posts_type'], TRUE) ?> >Pages</option>
                    </select>
                </p>
                <?php
                if ($instance['posts_type'] == 'page') {
                    $style_page = '';
                    $style_post = 'style="display:none"';
                } else {
                    $style_post = '';
                    $style_page = 'style="display:none"';
                }
                ?>
                <div id="post_prop" <?php echo $style_post; ?> class="feature-post-wrapper" >
                    <p>
                        <label for="<?php echo $this->get_field_id('posts_cat'); ?>"><?php _e('Category', 'wsigenesis'); ?>:</label>
                        <?php
                        $cat = get_categories(array('hide_empty' => 0));
                        echo '<ul class="list-limit">';
                        foreach ($cat as $cc) {
                            $str = '';
                            if (in_array($cc->term_id, $instance['posts_cat'])) {
                                $str = 'checked="checked"';
                            }
                            echo '<li>';
                            ?>
                            <label>
                                <input type="checkbox" id="<?php echo $this->get_field_id('posts_cat'); ?>" name="<?php echo $this->get_field_name('posts_cat'); ?>[]" value="<?php echo esc_attr($cc->term_id); ?>" <?php echo $str; ?> >
                                <?php _e($cc->name, 'wsigenesis'); ?>
                            </label>

                            <?php
                            echo '</li>';
                        }
                        echo '</ul>';
                        ?>
                    </p>
                    <i>(Only applicable when single category is selected)</i>
                    <p>
                        <input id="<?php echo $this->get_field_id('more_from_category'); ?>" type="checkbox" name="<?php echo $this->get_field_name('more_from_category'); ?>" value="1" <?php checked($instance['more_from_category']); ?>/>
                        <label for="<?php echo $this->get_field_id('more_from_category'); ?>"><?php _e('Show Category Archive Link', 'wsigenesis'); ?></label>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('more_from_category_text'); ?>"><?php _e('Link Text', 'wsigenesis'); ?>:</label>
                        <input type="text" id="<?php echo $this->get_field_id('more_from_category_text'); ?>" name="<?php echo $this->get_field_name('more_from_category_text'); ?>" value="<?php echo esc_attr($instance['more_from_category_text']); ?>" class="widefat" />
                    </p>

                </div>

                <p id="page_prop" <?php echo $style_page; ?> class="feature-post-wrapper" >
                    <label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('Page', 'wsigenesis'); ?>:</label>
                    <?php
                    $pages = get_pages();
                    echo '<select name="' . $this->get_field_name('page_id') . '" id="' . $this->get_field_id('page_id') . '" >';
                    echo '<option value="0">All Pages</option>';
                    foreach ($pages as $pid) {
                        ?>
                    <option value="<?php echo $pid->ID ?>" <?php selected($pid->ID, $instance['page_id'], TRUE) ?> ><?php echo $pid->post_title; ?></option>
                    <?php
                }
                echo '</select>';
                ?>
                </p>

                <div id="exclude-include-prop" class="feature-post-wrapper">
                    <p>
                        <label for="<?php echo $this->get_field_id('include_exclude'); ?>"><?php _e('Include/or Exclude:', 'wsigenesis'); ?></label>
                        <select name="<?php echo $this->get_field_name('include_exclude'); ?>" id="<?php echo $this->get_field_id('include_exclude'); ?>">
                            <option >Select</option>
                            <option value="include" <?php selected('include', $instance['include_exclude'], TRUE) ?> >Include</option>
                            <option value="exclude" <?php selected('exclude', $instance['include_exclude'], TRUE) ?> >Exclude</option>
                        </select>
                    </p>
                    <?php
                    if ($instance['include_exclude'] == 'include' || $instance['include_exclude'] == 'exclude') {
                        echo '<p id="exclude-include-filter-container">';
                    } else {
                        echo '<p id="exclude-include-filter-container" style="display:none">';
                    }
                    ?>

                    <label for="<?php echo $this->get_field_id('include_exclude_filter'); ?>"><?php _e('Page/or Post ID:<br/>(enter ID\'s in csv format eg:1,2,5)', 'wsigenesis'); ?></label>
                    <input type="text" id="<?php echo $this->get_field_id('include_exclude_filter'); ?>" name="<?php echo $this->get_field_name('include_exclude_filter'); ?>" value="<?php echo esc_attr($instance['include_exclude_filter']); ?>" />
                    </p>

                </div>

                <div class="feature-post-wrapper">

                    <p>
                        <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By', 'wsigenesis'); ?>:</label>
                        <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
                            <option value="date" <?php selected('date', $instance['orderby']); ?>><?php _e('Date', 'wsigenesis'); ?></option>
                            <option value="title" <?php selected('title', $instance['orderby']); ?>><?php _e('Title', 'wsigenesis'); ?></option>
                            <option value="menu_order" <?php selected('menu_order', $instance['orderby']); ?>><?php _e('Order', 'wsigenesis'); ?></option>
                            <option value="parent" <?php selected('parent', $instance['orderby']); ?>><?php _e('Parent', 'wsigenesis'); ?></option>
                            <option value="ID" <?php selected('ID', $instance['orderby']); ?>><?php _e('ID', 'wsigenesis'); ?></option>
                            <option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>><?php _e('Comment Count', 'wsigenesis'); ?></option>
                            <option value="rand" <?php selected('rand', $instance['orderby']); ?>><?php _e('Random', 'wsigenesis'); ?></option>
                        </select>
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort Order', 'wsigenesis'); ?>:</label>
                        <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
                            <option value="DESC" <?php selected('DESC', $instance['order']); ?>><?php _e('Descending', 'wsigenesis'); ?></option>
                            <option value="ASC" <?php selected('ASC', $instance['order']); ?>><?php _e('Ascending', 'wsigenesis'); ?></option>
                        </select>
                    </p>

                </div>

                <div class="feature-post-wrapper">
                    <p>
                        <label for="<?php echo $this->get_field_id('posts_num'); ?>"><?php _e('Number of Posts to Show', 'wsigenesis'); ?>:</label>
                        <input type="number" id="<?php echo $this->get_field_id('posts_num'); ?>" name="<?php echo $this->get_field_name('posts_num'); ?>" value="<?php echo esc_attr($instance['posts_num']); ?>" size="2" />
                    </p>

                    <p>
                        <label for="<?php echo $this->get_field_id('posts_offset'); ?>"><?php _e('Number of Posts to Offset', 'wsigenesis'); ?>:</label>
                        <input type="number" id="<?php echo $this->get_field_id('posts_offset'); ?>" name="<?php echo $this->get_field_name('posts_offset'); ?>" value="<?php echo esc_attr($instance['posts_offset']); ?>" size="2" />
                    </p>
                </div>


            </div>

            <div class="genesis-widget-column genesis-widget-column-right">
                <div class="feature-post-wrapper">
                    <p>
                        <input id="<?php echo $this->get_field_id('show_title'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked($instance['show_title']); ?>/>
                        <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Post Title', 'wsigenesis'); ?></label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('title_link'); ?>" type="checkbox" name="<?php echo $this->get_field_name('title_link'); ?>" value="1" <?php checked($instance['title_link']); ?>/>
                        <label for="<?php echo $this->get_field_id('title_link'); ?>"><?php _e('Link to Post Title', 'wsigenesis'); ?></label>
                    </p>

                    <p>
                        <input id="<?php echo $this->get_field_id('show_date'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_date'); ?>" value="1" <?php checked($instance['show_date']); ?>/>
                        <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Show Post Date ', 'wsigenesis'); ?></label>
                    </p>
                    
                    <p>
                        <input id="<?php echo $this->get_field_id('show_author'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_author'); ?>" value="1" <?php checked($instance['show_author']); ?>/>
                        <label for="<?php echo $this->get_field_id('show_author'); ?>"><?php _e('Show Author ', 'wsigenesis'); ?></label>
                    </p>
                    
                    <p>
                        <input id="<?php echo $this->get_field_id('show_post_categories'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_post_categories'); ?>" value="1" <?php checked($instance['show_post_categories']); ?>/>
                        <label for="<?php echo $this->get_field_id('show_post_categories'); ?>"><?php _e('Show Post categories ', 'wsigenesis'); ?></label>
                    </p>
                    
                </div>
                <div class="feature-post-wrapper">
                    <p>
                        <input id="<?php echo $this->get_field_id('show_social_icons'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_social_icons'); ?>" value="1" <?php checked($instance['show_social_icons']); ?>/>
                        <label for="<?php echo $this->get_field_id('show_social_icons'); ?>"><?php _e('Show Social Icons ', 'wsigenesis'); ?></label>
                    </p>
                    <?php if($instance['show_social_icons']){    ?>
                        <div id="social-icon-prop">
                    <?php }else{ ?>
                            <div id="social-icon-prop" style="display: none">
                    <?php } ?>
                    
                        <p>
                            <input id="<?php echo $this->get_field_id('social_icons_above_content'); ?>" type="checkbox" name="<?php echo $this->get_field_name('social_icons_above_content'); ?>" value="1" <?php checked($instance['social_icons_above_content']); ?>/>
                            <label for="<?php echo $this->get_field_id('social_icons_above_content'); ?>"><?php _e('Above content', 'wsigenesis'); ?></label>
                        </p>
                        <p>
                            <input id="<?php echo $this->get_field_id('social_icons_below_content'); ?>" type="checkbox" name="<?php echo $this->get_field_name('social_icons_below_content'); ?>" value="1" <?php checked($instance['social_icons_below_content']); ?>/>
                            <label for="<?php echo $this->get_field_id('social_icons_below_content'); ?>"><?php _e('Below content', 'wsigenesis'); ?></label>
                        </p>
                    </div>
                </div>
                <div class="feature-post-wrapper">
                    <p>
                        <input id="<?php echo $this->get_field_id('show_image'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image'); ?>" value="1" <?php checked($instance['show_image']); ?>/>
                        <label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show Featured Image', 'wsigenesis'); ?></label>
                    </p>
                    <?php if ($instance['show_image']) { ?>
                        <div id="image-prop">
                        <?php } else { ?>
                            <div id="image-prop" style="display: none">
                            <?php } ?>
                            <p>
                                <input id="<?php echo $this->get_field_id('show_image_link'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image_link'); ?>" value="1" <?php checked($instance['show_image_link']); ?>/>
                                <label for="<?php echo $this->get_field_id('show_image_link'); ?>"><?php _e('Link to Featured Image ', 'wsigenesis'); ?></label>
                            </p>

                            <p>
                                <label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size', 'wsigenesis'); ?>:</label>
                                <select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">
                                    <option value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>
                                    <?php
                                    global $_wp_additional_image_sizes;
                                    $sizes = $_wp_additional_image_sizes;
                                    foreach ((array) $sizes as $name => $size)
                                        echo '<option value="' . esc_attr($name) . '" ' . selected($name, $instance['image_size'], FALSE) . '>' . esc_html($name) . ' ( ' . $size['width'] . 'x' . $size['height'] . ' )</option>';
                                    ?>
                                </select>
                            </p>

                            <p>
                                <label for="<?php echo $this->get_field_id('image_alignment'); ?>"><?php _e('Image Alignment', 'wsigenesis'); ?>:</label>
                                <select id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('image_alignment'); ?>">
                                    <option value="alignnone">- <?php _e('None', 'wsigenesis'); ?> -</option>
                                    <option value="alignleft" <?php selected('alignleft', $instance['image_alignment']); ?>><?php _e('Left', 'wsigenesis'); ?></option>
                                    <option value="alignright" <?php selected('alignright', $instance['image_alignment']); ?>><?php _e('Right', 'wsigenesis'); ?></option>
                                </select>
                            </p>
                        </div> 
                    </div>
                    <div class="feature-post-wrapper">
                        <p>
                            <label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Content Type', 'wsigenesis'); ?>:</label>
                            <select id="<?php echo $this->get_field_id('show_content'); ?>" name="<?php echo $this->get_field_name('show_content'); ?>">
                                <option value="content" <?php selected('content', $instance['show_content']); ?>><?php _e('Show Content', 'wsigenesis'); ?></option>
                                <option value="excerpt" <?php selected('excerpt', $instance['show_content']); ?>><?php _e('Show Excerpt', 'wsigenesis'); ?></option>
                                <option value="content-limit" <?php selected('content-limit', $instance['show_content']); ?>><?php _e('Show Content Limit', 'wsigenesis'); ?></option>
                                <option value="" <?php selected('', $instance['show_content']); ?>><?php _e('No Content', 'wsigenesis'); ?></option>
                            </select>
                        </p>

                        <?php if ($instance['show_content'] == 'excerpt' || $instance['show_content'] == 'content-limit') { ?>
                            <div id="content-prop">
                            <?php } else { ?>
                                <div id="content-prop" style="display: none">
                                <?php } ?>

                                <?php if ($instance['show_content'] == 'content-limit') { ?>
                                    <p id="content-limit-prop">
                                    <?php } else { ?>
                                    <p id="content-limit-prop" style="display: none">
                                    <?php } ?>

                                    <label for="<?php echo $this->get_field_id('content_limit'); ?>"><?php _e('Limit content to', 'wsigenesis'); ?>
                                        <input type="text" id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('content_limit'); ?>" value="<?php echo esc_attr(intval($instance['content_limit'])); ?>" size="3" />
                                        <?php _e('characters', 'wsigenesis'); ?>
                                    </label>
                                </p>

                                <p>
                                    <label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('More Text ', 'wsigenesis'); ?>:</label>
                                    <input type="text" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" value="<?php echo esc_attr($instance['more_text']); ?>" />
                                </p>
                            </div>
                        </div>
                        <div class="feature-post-wrapper">
                            <p>
                                <label for="<?php echo $this->get_field_id('extra_class'); ?>"><?php _e('Class', 'wsigenesis'); ?>:</label>
                                <input type="text" id="<?php echo $this->get_field_id('extra_class'); ?>" name="<?php echo $this->get_field_name('extra_class'); ?>" value="<?php echo esc_attr($instance['extra_class']); ?>" class="widefat" />
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }

            function limit_the_content($max_characters, $more_link_text = '(more...)', $stripteaser = false) {

                $content = get_the_content('', $stripteaser);

                /** Strip tags and shortcodes so the content truncation count is done correctly */
                $content = strip_tags(strip_shortcodes($content), apply_filters('get_the_content_limit_allowedtags', '<script>,<style>'));

                /** Inline styles / scripts */
                $content = trim(preg_replace('#<(s(cript|tyle)).*?</\1>#si', '', $content));

                /** Truncate $content to $max_char */
                $content = $this->genesis_truncate_phrase($content, $max_characters);

                /** More link? */
                if ($more_link_text) {
                    $link = sprintf('%s <a href="%s" class="more-link">%s</a>', '&hellip;', get_permalink(), $more_link_text);
                    $output = sprintf('<p>%s %s</p>', $content, $link);
                } else {
                    $output = sprintf('<p>%s</p>', $content);
                }
                echo $output;
            }

            function genesis_truncate_phrase($text, $max_characters) {

                $text = trim($text);

                if (strlen($text) > $max_characters) {
                    /** Truncate $text to $max_characters + 1 */
                    $text = substr($text, 0, $max_characters + 1);

                    /** Truncate to the last space in the truncated string */
                    $text = trim(substr($text, 0, strrpos($text, ' ')));
                }

                return $text;
            }

        }
        
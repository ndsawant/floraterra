<?php
/**
 * Adds the Genesis Category Widget.
 *
 * WSI Genesis Categories widget class.
 *
 * @package Genesis Tools
 * @subpackage Widgets
 * @since 8 June 2016
 * @dev Swapnil Ghone
 */
register_widget('GP_Tag_Cloud_Widget');

class GP_Tag_Cloud_Widget extends WP_Widget {

    /**
     * Constructor. Set the default widget options and create widget.
     */
    function __construct() {
        $widget_ops = array('classname' => 'wsigenesis-tag-cloud', 'description' => __('Display the tags,with highly confrigable settings', 'wsigenesis'));
        $control_ops = array('width' => 505, 'height' => 300, 'id_base' => 'wsigenesis-tag-cloud');
        $this->WP_Widget('wsigenesis-tag-cloud', __('WSIG Tag Cloud', 'wsigenesis'), $widget_ops, $control_ops);
        add_action('admin_enqueue_scripts', array($this, 'gp_enqueu_admin_widget_assets'));
    }
    
    function gp_enqueu_admin_widget_assets(){
        wp_enqueue_style('gp_admin_widget_style', GENESIS_PRO_CSS_URL . 'gpAdminWidgetStyle.css');
        wp_enqueue_script('gp_admin_widget_script', GENESIS_PRO_JS_URL . 'gpAdminWidgetScript.js');
    }

    /**
     * Echo the widget content.
     *
     * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget
     */
    function widget($args, $instance) {
        extract($args);

        echo $before_widget;
//        _pre($instance);
        $title = $instance['genesis_tg_cloud_title'] ? $instance['genesis_tg_cloud_title'] : __('Tag Cloud');
        echo $before_title . apply_filters('widget_title', $title) . $after_title;
        $args = array(
            'smallest' => $instance['gpgc_size_min'],
            'largest' => $instance['gpgc_size_max'],
            'unit' => 'px',
            'number' => $instance['gpgc_max'],
            'orderby' => $instance['gpgc_order_by'],
            'order' => $instance['gpgc_order'],
            'exclude' => $instance['gpgc_exclude'],
            'include' => $instance['gpgc_include'],
            'taxonomy' => $instance['gpgc_taxonomy'],
            'color' => $instance['gpgc_color'],
            'color_set' => $instance['gpgc_color_set_chooser']
        );

        $this->wis_tag_cloud($args);

        echo $after_widget;
    }

    /** Update a particular instance.
     *
     * This function should check that $new_instance is set correctly.
     * The newly calculated value of $instance should be returned.
     * If "false" is returned, the instance won't be saved/updated.
     *
     * @param array $new_instance New settings for this instance as input by the user via form()
     * @param array $old_instance Old settings for this instance
     * @return array Settings to save or bool false to cancel saving
     */
    function update($new_instance, $old_instance) {


//        print_r($new_instance);
//        exit();
        // Force the transient to refresh
        delete_transient($old_instance['genesis_tg_cloud_title']);
        delete_transient($old_instance['gpgc_size_min']);
        delete_transient($old_instance['gpgc_size_max']);
        delete_transient($old_instance['gpgc_max']);
        delete_transient($old_instance['gpgc_color']);
        delete_transient($old_instance['gpgc_color_set_chooser']);

        delete_transient($old_instance['gpgc_order_by']);
        delete_transient($old_instance['gpgc_order']);
        delete_transient($old_instance['gpgc_exclude']);
        delete_transient($old_instance['gpgc_include']);
        delete_transient($old_instance['gpgc_taxonomy']);
//        delete_transient($old_instance['gpgc_color_span_from']);
//        delete_transient($old_instance['gpgc_color_span_to']);

        $new_instance['genesis_tg_cloud_title'] = strip_tags($new_instance['genesis_tg_cloud_title']);
        $new_instance['gpgc_size_min'] = strip_tags($new_instance['gpgc_size_min']);
        $new_instance['gpgc_size_max'] = strip_tags($new_instance['gpgc_size_max']);
        $new_instance['gpgc_max'] = strip_tags($new_instance['gpgc_max']);
        $new_instance['gpgc_color'] = strip_tags($new_instance['gpgc_color']);
        $new_instance['gpgc_color_set_chooser'] = strip_tags($new_instance['gpgc_color_set_chooser']);

        $new_instance['gpgc_order_by'] = strip_tags($new_instance['gpgc_order_by']);
        $new_instance['gpgc_order'] = strip_tags($new_instance['gpgc_order']);
        $new_instance['gpgc_exclude'] = strip_tags($new_instance['gpgc_exclude']);
        $new_instance['gpgc_include'] = strip_tags($new_instance['gpgc_include']);
        $new_instance['gpgc_taxonomy'] = $new_instance['gpgc_taxonomy'];


//        $new_instance['gpgc_color_span_from'] = strip_tags($new_instance['gpgc_color_span_from']);
//        $new_instance['gpgc_color_span_to'] = strip_tags($new_instance['gpgc_color_span_to']);

        return $new_instance;
    }

    /** Echo the settings update form.
     *
     * @param array $instance Current settings
     */
    function form($instance) {

        $instance = wp_parse_args((array) $instance, array(
            'genesis_tg_cloud_title' => '',
            'gpgc_size_min' => '',
            'gpgc_size_max' => '',
            'gpgc_max' => '',
            'gpgc_color' => '',
            'gpgc_color_set_chooser' => '',
            'gpgc_order_by' => '',
            'gpgc_order' => '',
            'gpgc_exclude' => '',
            'gpgc_include' => '',
            'gpgc_taxonomy' => array()
//            'gpgc_color_span_from' => '',
//            'gpgc_color_span_to' => ''
        ));
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wsigenesis') ?></label>
            <input type="text" id="<?php echo $this->get_field_id('genesis_tg_cloud_title'); ?>"
                   name="<?php echo $this->get_field_name('genesis_tg_cloud_title'); ?>"
                   value="<?php echo esc_attr($instance['genesis_tg_cloud_title']) ?>" class="widefat">
        </p>
        <div class="genesis-widget"> 
            <div class="genesis-widget-column genesis-widget-left">
                <p>
                    <a class="utcw-help" title="<?php _e('The tag with the least number of posts will be the smallest, and the tag with the most number of posts will be the biggest.', 'wsigenesis') ?>">?</a>
                    <strong><?php _e('Tag size:', 'wsigenesis') ?></strong><br>
                    <label for="<?php echo $this->get_field_id('gpgc_size_min') ?>"><?php _e('Min', 'wsigenesis') ?></label>
                    <input class="tag-minmax" type="number" name="<?php echo $this->get_field_name('gpgc_size_min') ?>"
                           id="<?php echo $this->get_field_id('gpgc_size_min') ?>" size="3"
                           value="<?php echo esc_attr($instance['gpgc_size_min']) ?>">
                    <label for="<?php echo $this->get_field_id('gpgc_size_max') ?>"><?php _e('Max', 'wsigenesis') ?></label>
                    <input class="tag-minmax" type="number" name="<?php echo $this->get_field_name('gpgc_size_max') ?>"
                           id="<?php echo $this->get_field_id('gpgc_size_max') ?>" size="3"
                           value="<?php echo esc_attr($instance['gpgc_size_max']) ?>"><br>
                </p>

                <p>
                    <a class="utcw-help" title="<?php _e('If the total number of tags exceeds this number, only this many tags will be shown in the cloud.', 'wsigenesis') ?>">?</a>
                    <strong><label
                            for="<?php echo $this->get_field_id('gpgc_max') ?>"><?php _e('Max tags:', 'wsigenesis') ?></label></strong>
                    <input type="number" name="<?php echo $this->get_field_name('gpgc_max') ?>"
                           id="<?php echo $this->get_field_id('gpgc_max') ?>"
                           value="<?php echo esc_attr($instance['gpgc_max']) ?>"><br>
                </p>

                <p>
                    <a class="utcw-help" title="<?php _e('Order of the tags.(Default: name)', 'wsigenesis') ?>">?</a>
                    <strong><?php _e('Order By:', 'wsigenesis') ?></strong><br>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_order_by') ?>" id="<?php echo $this->get_field_id('gpgc_order_by_name') ?>" value="name" <?php echo $instance['gpgc_order_by'] == 'name' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_order_by_name') ?>"><?php _e('Name', 'wsigenesis') ?></label><br/>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_order_by') ?>" id="<?php echo $this->get_field_id('gpgc_order_by_id') ?>" value="ID" <?php echo $instance['gpgc_order_by'] == 'ID' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_order_by_id') ?>"><?php _e('ID', 'wsigenesis') ?></label><br/>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_order_by') ?>" id="<?php echo $this->get_field_id('gpgc_order_by_count') ?>" value="count" <?php echo $instance['gpgc_order_by'] == 'count' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_order_by_count') ?>"><?php _e('Count', 'wsigenesis') ?></label><br/>

                </p>

                <p>
                    <strong><?php _e('Order:', 'wsigenesis') ?></strong><br>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_order') ?>" id="<?php echo $this->get_field_id('gpgc_order_asc') ?>" value="ASC" <?php echo $instance['gpgc_order'] == 'ASC' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_order_asc') ?>"><?php _e('Ascending', 'wsigenesis') ?></label><br/>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_order') ?>" id="<?php echo $this->get_field_id('gpgc_order_desc') ?>" value="DESC" <?php echo $instance['gpgc_order'] == 'DESC' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_order_desc') ?>"><?php _e('Descending', 'wsigenesis') ?></label><br/>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_order') ?>" id="<?php echo $this->get_field_id('gpgc_order_rand') ?>" value="RAND" <?php echo $instance['gpgc_order'] == 'RAND' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_order_rand') ?>"><?php _e('Random', 'wsigenesis') ?></label><br/>
                </p>
            </div>
            <div class="genesis-widget-column genesis-widget-column-right">
                <p>
                    <a class="utcw-help" title="<?php _e('This setting controls how the tags are colored. &#010; 1)Totaly random will choose between all the 16 million colors available. &#010; 2)Random from preset values will choose colors from a predefined set of colors &#010; 3)The colors for the choice \'Random from preset values\' has to be specified as a color hex code with comma separated list. eg : #C4C9DF,#8AC007', 'wsigenesis') ?>">?</a>
                    <strong><?php _e('Coloring:', 'wsigenesis') ?></strong><br/>
                    <i>("Random from preset values" has to be specified as a color hex code with comma separated list.)</i>
                    <br/>
                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_color') ?>"
                           id="<?php echo $this->get_field_id('gpgc_color_none') ?>"
                           class="gp_tag_color"
                           value="none" <?php echo $instance['gpgc_color'] == 'none' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_color_none') ?>"><?php _e('None', 'wsigenesis') ?></label><br/>

                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_color') ?>"
                           id="<?php echo $this->get_field_id('gpgc_color_random') ?>"
                           class="gp_tag_color"
                           value="random" <?php echo $instance['gpgc_color'] == 'random' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_color_random') ?>"><?php _e('Totally random', 'wsigenesis') ?></label><br>

                    <input type="radio" name="<?php echo $this->get_field_name('gpgc_color') ?>"
                           id="<?php echo $this->get_field_id('gpgc_color_set') ?>"
                           class="gp_tag_color"
                           value="set" <?php echo $instance['gpgc_color'] == 'set' ? 'checked="checked"' : ''; ?>>
                    <label for="<?php echo $this->get_field_id('gpgc_color_set') ?>"><?php _e('Random from preset values', 'wsigenesis') ?></label>
                </p>
                <div id="content-set-chooser" style="<?php echo $instance['gpgc_color'] != 'set' ? 'display:none' : ''; ?>" >
                    <label class="screen-reader-text" for="<?php echo $this->get_field_id('gpgc_color_set_chooser') ?>"><?php _e('Random from preset values', 'wsigenesis') ?></label>
                    <input type="text" name="<?php echo $this->get_field_name('gpgc_color_set_chooser') ?>" id="<?php echo $this->get_field_id('gpgc_color_set_chooser') ?>" value="<?php echo esc_attr($instance['gpgc_color_set_chooser']) ?>">
                </div>
                <p>
                    <a class="utcw-help" title="<?php _e('Comma separated list of tags (term_id) to exclude. &#010; For example, exclude=5,27 means tags that have the term_id 5 or 27 will NOT be displayed. &#010; By Defaults to exclude nothing.', 'wsigenesis') ?>">?</a>
                    <strong><?php _e('Exclude:', 'wsigenesis') ?></strong><br>
                    <input type="text" name="<?php echo $this->get_field_name('gpgc_exclude') ?>" id="<?php echo $this->get_field_id('gpgc_exclude') ?>" value="<?php echo esc_attr($instance['gpgc_exclude']) ?>">
                </p>
                <p>
                    <a class="utcw-help" title="<?php _e('Comma separated list of tags (term_id) to include. &#010; For example, include=5,27 means tags that have the term_id 5 or 27 will be the only tags displayed. &#010; Defaults to include everything.(Default: null)', 'wsigenesis') ?>">?</a>
                    <strong><?php _e('Include:', 'wsigenesis') ?></strong><br>
                    <input type="text" name="<?php echo $this->get_field_name('gpgc_include') ?>" id="<?php echo $this->get_field_id('gpgc_include') ?>" value="<?php echo esc_attr($instance['gpgc_include']) ?>">
                </p>
                <p>
                    <a class="utcw-help" title="<?php _e('Which taxanomy should be used in the cloud. You should be able to choose the custom taxanomy as well', 'wsigenesis') ?>">?</a>
                    <strong><?php _e('Taxonomies:', 'wsigenesis') ?></strong><br>
                    <?php
                    $avail_tax = get_taxonomies(array('public' => true), 'objects');
                    foreach ($avail_tax as $taxonomy) {
                        ?>
                        <label><input type="checkbox" value="<?php echo esc_attr($taxonomy->name) ?>" name="<?php echo $this->get_field_name('gpgc_taxonomy') ?>[]" <?php if (in_array($taxonomy->name, $instance['gpgc_taxonomy'])) echo 'checked="checked"' ?> ><?php echo esc_attr($taxonomy->labels->name) ?></label><br>
                    <?php } ?>
                </p>
            </div>
        </div>

        <?php
    }

    function wis_tag_cloud($args = '') {

        $defaults = array(
            'smallest' => 8, 'largest' => 22, 'unit' => 'px', 'number' => 45,
            'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
            'exclude' => '', 'include' => '', 'link' => 'view', 'taxonomy' => 'post_tag', 'post_type' => '', 'echo' => true,
            'color' => 'none', 'set_color' => ''
        );
        $args = wp_parse_args(array_filter($args), $defaults);

        $tags = get_terms($args['taxonomy'], array_merge($args, array('orderby' => 'count', 'order' => 'DESC'))); // Always query top tags

        if (empty($tags) || is_wp_error($tags))
            return;

        foreach ($tags as $key => $tag) {
            if ('edit' == $args['link'])
                $link = get_edit_term_link($tag->term_id, $tag->taxonomy, $args['post_type']);
            else
                $link = get_term_link(intval($tag->term_id), $tag->taxonomy);
            if (is_wp_error($link))
                return false;

            $tags[$key]->link = $link;
            $tags[$key]->id = $tag->term_id;
        }

        $return = $this->wis_generate_tag_cloud($tags, $args); // Here's where those top tags get sorted according to $args

        /**
         * Filter the tag cloud output.
         *
         * @since 2.3.0
         *
         * @param string $return HTML output of the tag cloud.
         * @param array  $args   An array of tag cloud arguments.
         */
        $return = apply_filters('wis_tag_cloud', $return, $args);

        if ('array' == $args['format'] || empty($args['echo']))
            return $return;

        echo $return;
    }

    function wis_generate_tag_cloud($tags, $args = '') {
        $defaults = array(
            'smallest' => 8, 'largest' => 22, 'unit' => 'px', 'number' => 0,
            'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
            'topic_count_text' => null, 'topic_count_text_callback' => null,
            'topic_count_scale_callback' => 'default_topic_count_scale', 'filter' => 1,
            'color' => 'none', 'set_color' => ''
        );

        $args = wp_parse_args($args, $defaults);
        extract($args, EXTR_SKIP);

        $return = ( 'array' === $format ) ? array() : '';

        if (empty($tags)) {
            return $return;
        }

        // Juggle topic count tooltips:
        if (isset($args['topic_count_text'])) {
            // First look for nooped plural support via topic_count_text.
            $translate_nooped_plural = $args['topic_count_text'];
        } elseif (!empty($args['topic_count_text_callback'])) {
            // Look for the alternative callback style. Ignore the previous default.
            if ($args['topic_count_text_callback'] === 'default_topic_count_text') {
                $translate_nooped_plural = _n_noop('%s topic', '%s topics');
            } else {
                $translate_nooped_plural = false;
            }
        } elseif (isset($args['single_text']) && isset($args['multiple_text'])) {
            // If no callback exists, look for the old-style single_text and multiple_text arguments.
            $translate_nooped_plural = _n_noop($args['single_text'], $args['multiple_text']);
        } else {
            // This is the default for when no callback, plural, or argument is passed in.
            $translate_nooped_plural = _n_noop('%s topic', '%s topics');
        }

        /**
         * Filter how the items in a tag cloud are sorted.
         *
         * @since 2.8.0
         *
         * @param array $tags Ordered array of terms.
         * @param array $args An array of tag cloud arguments.
         */
        $tags_sorted = apply_filters('tag_cloud_sort', $tags, $args);
        if (empty($tags_sorted)) {
            return $return;
        }

        if ($tags_sorted !== $tags) {
            $tags = $tags_sorted;
            unset($tags_sorted);
        } else {
            if ('RAND' === $order) {
                shuffle($tags);
            } else {
                // SQL cannot save you; this is a second (potentially different) sort on a subset of data.
                if ('name' === $orderby) {
                    uasort($tags, '_wp_object_name_sort_cb');
                } else {
                    uasort($tags, '_wp_object_count_sort_cb');
                }

                if ('DESC' === $order) {
                    $tags = array_reverse($tags, true);
                }
            }
        }

        if ($number > 0)
            $tags = array_slice($tags, 0, $number);

        $counts = array();
        $real_counts = array(); // For the alt tag
        foreach ((array) $tags as $key => $tag) {
            $real_counts[$key] = $tag->count;
            $counts[$key] = $topic_count_scale_callback($tag->count);
        }

        $min_count = min($counts);
        $spread = max($counts) - $min_count;
        if ($spread <= 0)
            $spread = 1;
        $font_spread = $largest - $smallest;
        if ($font_spread < 0)
            $font_spread = 1;
        $font_step = $font_spread / $spread;

        $a = array();

        foreach ($tags as $key => $tag) {
            $count = $counts[$key];
            $real_count = $real_counts[$key];
            $tag_link = '#' != $tag->link ? esc_url($tag->link) : '#';
            $tag_id = isset($tags[$key]->id) ? $tags[$key]->id : $key;
            $tag_name = $tags[$key]->name;

            if ($translate_nooped_plural) {
                $title_attribute = sprintf(translate_nooped_plural($translate_nooped_plural, $real_count), number_format_i18n($real_count));
            } else {
                $title_attribute = call_user_func($topic_count_text_callback, $real_count, $tag, $args);
            }

            if ('random' == $color) {
                $color_val = sprintf('#%02x%02x%02x', rand() % 256, rand() % 256, rand() % 256);
            } else if ('set' == $color) {
                if ($color_set) {
                    $preset_color = explode(',', $color_set);
                    $color_val = $preset_color[array_rand($preset_color)];
                } else {
                    $color_val = '';
                }
            } else {
                $color_val = '';
            }
            $a[] = "<a href='$tag_link' class='tag-link-$tag_id' title='" . esc_attr($title_attribute) . "' style='font-size: " .
                    str_replace(',', '.', ( $smallest + ( ( $count - $min_count ) * $font_step )))
                    . "$unit;color:" . $color_val . "'>$tag_name</a>";
        }

        switch ($format) :
            case 'array' :
                $return = & $a;
                break;
            case 'list' :
                $return = "<ul class='wp-tag-cloud'>\n\t<li>";
                $return .= join("</li>\n\t<li>", $a);
                $return .= "</li>\n</ul>\n";
                break;
            default :
                $return = join($separator, $a);
                break;
        endswitch;

        if ($filter) {
            /**
             * Filter the generated output of a tag cloud.
             *
             * The filter is only evaluated if a true value is passed
             * to the $filter argument in wis_generate_tag_cloud().
             *
             * @since 2.3.0
             *
             * @see wis_generate_tag_cloud()
             *
             * @param array|string $return String containing the generated HTML tag cloud output
             *                             or an array of tag links if the 'format' argument
             *                             equals 'array'.
             * @param array        $tags   An array of terms used in the tag cloud.
             * @param array        $args   An array of wis_generate_tag_cloud() arguments.
             */
            return apply_filters('wis_generate_tag_cloud', $return, $tags, $args);
        } else
            return $return;
    }

}
?>
<?php
/**
 * Adds the Genesis Menu Widget.
 *
 * WSI Genesis Menu widget class.
 *
 * @package WSI Genesis
 * @subpackage Widgets
 * @since June 2016
 * @dev Swapnil Ghone
 */
register_widget('GP_Custom_Menu_Widget');

class GP_Custom_Menu_Widget extends WP_Widget {

    /**
     * Constructor. Set the default widget options and create widget.
     */
    function __construct() {
        $widget_ops = array('classname' => 'wsigenesis-menu', 'description' => __('Show Menus', 'wsigenesis'));
        $control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'wsigenesis_menu');
        $this->WP_Widget('wsigenesis_menu', __('WSIG Menu', 'wsigenesis'), $widget_ops, $control_ops);
        add_action('wp_enqueue_scripts', array($this, 'gp_menu_load_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'gp_enqueu_admin_widget_assets'));
    }

    function gp_menu_load_scripts() {
        wp_register_script('wsig_menu_script', GENESIS_PRO_JS_URL . 'webwidget_vertical_menu.js');
        wp_enqueue_script('wsig_menu_script');
    }

    function gp_enqueu_admin_widget_assets() {
        wp_enqueue_script('gp_admin_widget_script', GENESIS_PRO_JS_URL . 'gpAdminWidgetScript.js');
    }

    /**
     * Echo the widget content.
     *
     * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget
     */
    function widget($args, $instance) {
        // Get menu
        $nav_menu = !empty($instance['nav_menu']) ? wp_get_nav_menu_object($instance['nav_menu']) : false;
        if (!$nav_menu)
            return;
        ?>
        <script language="javascript" type="text/javascript">
            jQuery(document).ready(function() {
                var width = jQuery("#<?php echo $args['widget_id']; ?>").width();
                jQuery("#<?php echo $args['widget_id']; ?>").webwidget_vertical_menu({
                    menu_width: width,
                    menu_position: '<?php echo $instance['menu_position']; ?>',
                    menu_style: '<?php echo $instance['menu_style']; ?>'
                });
                jQuery("#<?php echo $args['widget_id']; ?> ul li").each(function() {
                    if (typeof jQuery(this).attr('data-menu-active') != 'undefined') {
                        jQuery(this).addClass('wsigenesis_current');
                        jQuery(this).find('a:first').addClass('current');
                    }
                })
                jQuery('#<?php echo $args['widget_id']; ?> .wsigenesis_current').parents('li').addClass('wsigenesis_current');
            });
        </script><?php
        $instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        echo $args['before_widget'];
        if (!empty($instance['title']))
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
        wp_nav_menu(
                array(
                    'menu' => $nav_menu,
                    'menu_class' => $args['widget_id'] . ' wsigenesis_' . $instance['menu_style'] . ' ' . $instance['menu_class'],
                    'container_id' => $args['widget_id'],
                    'container_class' => 'wsigenesis-menu',
                    'walker' => new description_walker()
                )
        );
        echo $args['after_widget'];
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

        delete_transient($old_instance['title']);
        $new_instance['title'] = strip_tags($new_instance['title']);
        return $new_instance;
    }

    /** Echo the settings update form.
     *
     * @param array $instance Current settings
     */
    function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $nav_menu = isset($instance['nav_menu']) ? $instance['nav_menu'] : '';
        $menu_class = isset($instance['menu_class']) ? $instance['menu_class'] : '';
        // Get menus
        $menus = get_terms('nav_menu', array('hide_empty' => false));
        if (!$menus) {
            echo '<p>' . sprintf(__('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php')) . '</p>';
            return;
        }
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
        </p>
        <div class="feature-post-wrapper">
        <p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:', 'wsigenesis'); ?></label>
            <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>"><?php
                foreach ($menus as $menu) {
                    $selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
                    echo '<option' . $selected . ' value="' . $menu->term_id . '">' . $menu->name . '</option>';
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('menu_style'); ?>"><?php _e('Menu Style:', 'wsigenesis'); ?></label>
            <select id="<?php echo $this->get_field_id('menu_style'); ?>" name="<?php echo $this->get_field_name('menu_style'); ?>" class="menu_style">
                <option value="normal" <?php echo selected($instance['menu_style'], 'normal') ?>>Normal</option>
                <option value="vertical" <?php echo selected($instance['menu_style'], 'vertical') ?>>Vertical</option>
                <option value="accordion" <?php echo selected($instance['menu_style'], 'accordion') ?>>Accordion</option>
            </select>
        </p>
        <?php $condition_shows = ($instance['menu_style'] == 'vertical') ? 'style="display:block"' : 'style="display:none"' ?>
        <p id="gp_menu_position" <?php echo $condition_shows; ?>>
            <label for="<?php echo $this->get_field_id('menu_position'); ?>"><?php _e('Menu Position:', 'wsigenesis'); ?></label>
            <select id="<?php echo $this->get_field_id('menu_position'); ?>" name="<?php echo $this->get_field_name('menu_position'); ?>">
                <option value="left" <?php echo selected($instance['menu_position'], 'left') ?>>Left</option>
                <option value="right" <?php echo selected($instance['menu_position'], 'right') ?>>Right</option>
            </select>
        </p>
        </div>
        <p class="feature-post-wrapper">
            <label for="<?php echo $this->get_field_id('menu_class'); ?>"><?php _e('Class:', 'wsigenesis') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('menu_class'); ?>" name="<?php echo $this->get_field_name('menu_class'); ?>" value="<?php echo $menu_class; ?>" />
        </p>
        <?php
    }

}

class description_walker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        if (is_array($args))
            return false;
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        $class_names = $value = '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = ' class="' . esc_attr($class_names) . '"';

        $output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        if ($depth != 0) {
            $description = $append = $prepend = "";
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>' . $args->link_before;
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= ($depth != 0) ? $description : '';
        $item_output .= $args->link_after . '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}
?>
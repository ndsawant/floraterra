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
 * 
 * @filters
 * wsi_category_widget_args - to modify category arguments
 */

register_widget('GP_Categories_Widget');	


class GP_Categories_Widget extends WP_Widget {

	/**
	 * Constructor. Set the default widget options and create widget.
	 */
	function __construct() {
		$widget_ops = array( 'classname' => 'wsigenesis-categories', 'description' => __('Show Category List', 'wsigenesis') );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'wsigenesis-categories' );
		$this->WP_Widget( 'wsigenesis-categories', __('WSIG Categories', 'wsigenesis'), $widget_ops, $control_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget($args, $instance) {
		extract($args);

		$instance = wp_parse_args( (array)$instance, array(
			'genesis_cats_title' => '',
			'wsits_cat_dropdown' => '',
			'wsits_cat_post_count' => '',
			'wsits_cat_hierarchy' => '',
			'wsits_hide_this_category'=>''
		) );

		echo $before_widget;
			$title	=  $instance['genesis_cats_title'] ?  $instance['genesis_cats_title'] :__('Categories');
			echo $before_title . apply_filters('widget_title', $title) . $after_title;
			$geneisis_cat_title	= !empty( $instance['geneisis_cat_title'] ) ? $instance['geneisis_cat_title'] : '';
			$option_name = 'list_hide_category';
			$select_categories = $instance['wsits_hide_this_category'];
						
			$cat_args['show_count'] 	= ! empty( $instance['wsits_cat_post_count'] ) ? '1' : '0';
			$cat_args['hierarchical'] 	= ! empty( $instance['wsits_cat_hierarchy'] ) ? '1' : '0';
			$cat_args['title_li'] = "";
			$cat_args['exclude'] = $select_categories;
			$cat_args['hide_empty'] = 0;
			$cat_args['style'] = 'list';
			$cat_args['orderby'] = 'name';
			$cat_args['order'] = 'ASC';
			if($instance['wsits_cat_dropdown']){
				$cat_args['show_option_none'] = __('Select Category');
				wp_dropdown_categories(apply_filters('wsi_category_widget_args',$cat_args));
				?><script type='text/javascript'>
				/* <![CDATA[ */
					var dropdown = document.getElementById("cat");
					function onCatChange() {
						if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
							location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
						}
					}
					dropdown.onchange = onCatChange;
				/* ]]> */
				</script><?php	
			}else{
				echo '<ul>';
				wp_list_categories($cat_args);
				echo '</ul>';
			}
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

		// Force the transient to refresh
		delete_transient($old_instance['genesis_cats_title']);
		$new_instance['genesis_cats_title'] = strip_tags( $new_instance['genesis_cats_title'] );
		return $new_instance;

	}

	/** Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form($instance) {

		$instance = wp_parse_args( (array)$instance, array(
			'genesis_cats_title' => '',
			'wsits_cat_dropdown' => '',
			'wsits_cat_post_count' => '',
			'wsits_cat_hierarchy' => '',
			'wsits_hide_this_category'=>''
		) );
		$categories = get_all_category_ids();
		$selected_array	=	explode(",",  $instance['wsits_hide_this_category']);
		$select = '';
		foreach($categories as $category_id){
			$cat_name = get_the_category_by_id($category_id);
			if(in_array($category_id, $selected_array))
				$checked = "checked";
			else
				$checked = "";
			$select .= "<br><label><input type='checkbox' name='hide_this_category' value='".$category_id."' class='selc' ".$checked."> ".$cat_name."</label>";
		}
?>
		<p>
			<label for="<?php echo $this->get_field_id('genesis_cats_title'); ?>"><?php _e('Title', 'wsigenesis'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('genesis_cats_title'); ?>" name="<?php echo $this->get_field_name('genesis_cats_title'); ?>" value="<?php echo esc_attr( $instance['genesis_cats_title'] ); ?>" class="widefat" />
		</p>
                <p id="selc-container" class="feature-post-wrapper">
                    <label for="<?php echo $this->get_field_id('wsits_cat_dropdown'); ?>"><strong><?php _e('Exclude Categories', 'wsigenesis'); ?>:</strong></label>
                    <br/><i>Select the categories which should be excluded from output</i>
			<?php echo $select ?>
                    <input type="hidden" id="<?php echo $this->get_field_id('wsits_hide_this_category'); ?>" name="<?php echo $this->get_field_name('wsits_hide_this_category'); ?>" value="<?php echo esc_attr( $instance['wsits_hide_this_category'] ); ?>" class="widefat selected_cat" />
		</p>	
                <div class="feature-post-wrapper">
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('wsits_cat_dropdown'); ?>" name="<?php echo $this->get_field_name('wsits_cat_dropdown'); ?>" value="1" <?php checked('1', esc_attr( $instance['wsits_cat_dropdown'] )); ?> />
			<label for="<?php echo $this->get_field_id('wsits_cat_dropdown'); ?>"><?php _e('Display as dropdown', 'wsigenesis'); ?>:</label>
		</p>		
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('wsits_cat_post_count'); ?>" name="<?php echo $this->get_field_name('wsits_cat_post_count'); ?>" value="1" <?php checked('1', esc_attr( $instance['wsits_cat_post_count'] )); ?> />
			<label for="<?php echo $this->get_field_id('wsits_cat_post_count'); ?>"><?php _e('Show post counts', 'wsigenesis'); ?>:</label>
		</p>		
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('wsits_cat_hierarchy'); ?>" name="<?php echo $this->get_field_name('wsits_cat_hierarchy'); ?>" value="1" <?php checked('1', esc_attr( $instance['wsits_cat_hierarchy'] )); ?> />
			<label for="<?php echo $this->get_field_id('wsits_cat_hierarchy'); ?>"><?php _e('Show hierarchy', 'wsigenesis'); ?>:</label>
		</p>
                </div>
                
		<script type="text/javascript">
			jQuery(document).ready(function($){
				var allVals = [];
				$('.selc').click(function(){
					allVals = [];
                                        $(this).parents('#selc-container').find('.selc:checked').each(function(index){
                                            allVals.push($(this).val());
                                        })
                                        $(this).parents('#selc-container').find('.selected_cat').val(allVals);
				});
			});
		</script>
	<?php
	}
}
?>
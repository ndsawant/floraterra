<?php
$services = array('facebook' => 'Facebook', 'twitter' => 'Twitter', 'gplus' => 'Google+', 'pinterest' => 'Pinterest', 'linkedin' => 'Linkedin', 'reddit' => 'Reddit', 'mail' => 'Email');
$themes = array('classic' => 'WSI Classic', 'square' => 'Flat Square', 'circle' => 'Circle', 'popout' => 'Pop Out');
$layouts = array('horizontal' => 'Horizontal', 'vertical' => 'Vertical');
$size = array('default' => 'Default', 'small' => 'Small', 'rectangle' => 'Rectangle');
$counter = array('off' => 'OFF', 'top' => 'Top', 'side' => 'Side', 'badge' => 'Badge');
//echo '<pre>';
//print_r($social_share_settings);
//echo '</pre>';

?>

<form id="gp_social_share" name ="gp_social_share" method="post" alt="gp_update">
    <h2 class="gp-article-subtitle">Share Settings</h2>
    <div class="gp_section">
        <label>Theme</label>
        <div class="gp_right-section">
            <?php
            foreach ($themes as $slug => $label) {
                $theme_str = '';
                $active = '';
                if ($social_share_settings['theme'] == $slug) {
                    $theme_str = 'checked';
                    $active = 'ss-active';
                }
                echo '<label class="gpss_click_hook ' . $active . '"><input type="radio" name="gp_socialshare[theme]" id="gp_socialshare_theme" value="' . $slug . '" ' . $theme_str . ' ><span class="theme_toggle_icon ' . $slug . '"></span>' . $label . '</label>';
            }
            ?>
        </div>
    </div>

    <div class="gp_section">
        <label>Headline</label>
        <div class="gp_right-section">
            <input type="text" name="gp_socialshare[heading]" value="<?php echo $social_share_settings['heading']; ?>">
        </div>
    </div>
    <div class="gp_section">
        <label>Layout</label>
        <div class="gp_right-section">
            <?php
            foreach ($layouts as $slug => $label) {
                $layout_cls = '';
                $layout_str = '';
                if ($social_share_settings['layout']== $slug) {
                    $layout_cls = 'ss-active';
                    $layout_str = 'checked';
                }
                echo '<label class="gpss_click_hook ' . $layout_cls . '" ><input type="radio" name="gp_socialshare[layout]" value="' . $slug . '" ' . $layout_str . ' /><span class="layout_toggle_icon ' . $slug . '_layout"></span>' . $label . '</label>';
            }
            ?>
        </div>
    </div>
    <div class="gp_section">
        <label>Size</label>
        <div class="gp_right-section">
            <?php
            foreach ($size as $slug => $label) {
                $size_str = '';
                $size_cls = '';
                if ($social_share_settings['size'] == $slug) {
                    $size_str = 'checked';
                    $size_cls = 'ss-active';
                }
                echo '<label class="gpss_click_hook ' . $size_cls . '" ><input type="radio" name="gp_socialshare[size]" id="gp_socialshare_size" value="' . $slug . '" ' . $size_str . ' ><span class="size_toggle_icon ' . $slug . '_layout"></span>' . $label . '</label>';
            }
            ?>
        </div>
    </div>
    <div class="gp_section">
        <label>Counter</label>
        <div class="gp_right-section">
            <?php
            foreach ($counter as $slug => $label) {
                $counter_str = '';
                $counter_cls = '';
                if ($social_share_settings['counter'] == $slug) {
                    $counter_str = 'checked';
                    $counter_cls = 'ss-active';
                }
                echo '<label class="gpss_click_hook ' . $counter_cls . '" ><input type="radio" name="gp_socialshare[counter]" id="gp_socialshare_counter" value="' . $slug . '" ' . $counter_str . ' ><span class="counter_toggle_icon ' . $slug . '_layout"></span>' . $label . '</label>';
            }
            ?>
        </div>
    </div>
    <div class="gp_section">
        <label>Select Service</label>
        <div class="gp_right-section">
            <div class="services_toggle ui-sortable">
            <?php
            $new_service_array = array();
            if(isset($social_share_settings['service']) && is_array($social_share_settings['service'])){
                
                foreach ($social_share_settings['service'] as $service){
                    $new_service_array[$service] = $services[$service];
                }
                
                $new_service_array = $new_service_array+$services;
            
            }else{
                $new_service_array = $services;
            }
           
            foreach ($new_service_array as $sr_slug => $sr_label) {
                $srv_str = '';
                $srv_cls = '';
                if (is_array($social_share_settings['service']) && in_array($sr_slug, $social_share_settings['service'])) {
                    $srv_str = 'checked';
                    $srv_cls = 'ss-active';
                }
                echo '<label class="gpss_click_hook ' . $srv_cls . '"><input type="checkbox" name="gp_socialshare[service][]" value="' . $sr_slug . '" style="margin:0;position:relative;visibility:inherit;" ' . $srv_str . '><span class=' . $sr_slug . '></span>' . $sr_label . '</label>';
            }
            ?>
        </div>
            <p class="description">You can change the position of social icons by Drag/Drop</p>
        </div>
        
    </div>
    <h2 class="gp-article-subtitle">Configuration</h2>
    <div class="gp_section">
        <label>Post sharing</label>
        <div class="gp_right-section">
            <div class="gp_inner_section_wrap">
                <input type="hidden" name="gp_socialshare[post_above_content]"  value="0"/>
                <div class="gp-toggle-wrap">  
                    <input type="checkbox" class="gp-toggle" id="gp_share_post_above_content" name="gp_socialshare[post_above_content]"  value="1" <?php checked('1', $social_share_settings['post_above_content']); ?> />
                    <label for="gp_share_post_above_content"></label>
                </div>
                <label class="description">Above content</label>
            </div>
            <div class="gp_inner_section_wrap">
                <input type="hidden" name="gp_socialshare[post_below_content]"  value="0"/>
                <div class="gp-toggle-wrap">  
                    <input type="checkbox" class="gp-toggle" id="gp_share_post_below_content" name="gp_socialshare[post_below_content]"  value="1" <?php checked('1', $social_share_settings['post_below_content']); ?> />
                    <label for="gp_share_post_below_content"></label>
                </div>
                <label  class="description">Below content</label>
            </div>
        </div>
    </div>

    <div class="gp_section">
        <label>Page Sharing</label>
        <div class="gp_right-section">
            <div class="gp_inner_section_wrap">
                <input type="hidden" name="gp_socialshare[page_above_content]"  value="0"/>
                <div class="gp-toggle-wrap">  
                    <input type="checkbox" class="gp-toggle" id="gp_share_page_abov_content" name="gp_socialshare[page_above_content]"  value="1" <?php checked('1', $social_share_settings['page_above_content']); ?> />
                    <label for="gp_share_page_abov_content"></label>
                </div>
                <label  class="description">Above content</label>
            </div>
            <div class="gp_inner_section_wrap">
                <input type="hidden" name="gp_socialshare[page_below_content]"  value="0"/>
                <div class="gp-toggle-wrap">  
                    <input type="checkbox" class="gp-toggle" id="gp_share_page_below_content" name="gp_socialshare[page_below_content]"  value="1" <?php checked('1', $social_share_settings['page_below_content']); ?> />
                    <label for="gp_share_page_below_content"></label>
                </div>
                <label class="description">Below content</label>
            </div>
        </div>
    </div>

    <div class="gp_section">
        <label>Category Sharing</label>
        <div class="gp_right-section">
            <div class="gp_inner_section_wrap">
                <input type="hidden" name="gp_socialshare[category_above_content]"  value="0"/>
                <div class="gp-toggle-wrap">  
                    <input type="checkbox" class="gp-toggle" id="gp_share_category_above_content" name="gp_socialshare[category_above_content]"  value="1" <?php checked('1', $social_share_settings['category_above_content']); ?> />
                    <label for="gp_share_category_above_content"></label>
                </div>
                <label class="description">Above content</label>
            </div>
            <div class="gp_inner_section_wrap">
                <input type="hidden" name="gp_socialshare[category_below_content]"  value="0"/>
                <div class="gp-toggle-wrap">  
                    <input type="checkbox" class="gp-toggle" id="gp_share_category_below_content" name="gp_socialshare[category_below_content]"  value="1" <?php checked('1', $social_share_settings['category_below_content']); ?> />
                    <label for="gp_share_category_below_content"></label>
                </div>
                <label class="description">Below content</label>
            </div>
        </div>
    </div>
    <h2 class="gp-article-subtitle">Share Shortcode</h2>
    <div class="gp_section">
        <div id="shortcode_container">           
            <pre id="shortcode" name="widget_div" readonly="readonly">[wsi_social_share]</pre>
            <p><span id="shortcode_description">You can use this shortcode to place Share Buttons anywhere, you can also pass the particular post/page id  parameter to shortcode as [wsi_social_share id="2"] </span>     </p>      
        </div>
    </div>
    <div class="action-wrap">
        <input class="button-primary" type="submit" value="Save Changes" name="gp_social_share_submit" id="gp_social_share_submit" />
        <input type="hidden" name="command" value="gp_social_share_settings" />
        <input type="hidden" name="action" value="gp_save_settings" />
    </div> 
</form>
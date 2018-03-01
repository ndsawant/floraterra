<div id="backup_options" class="gp-view-wrap">
 <h1 class="uk-article-title">Genesis Redirects</h1>
 <hr class="gp-article-divider">
    <div id="gp_ver_tab_wrap" class="gp_ver_tab_wrap">
        <ul class="vtab">
            <li class="vtab-active"><a href="javascript:void(0)" rel="gp_add_redirect_wrap">Redirects</a></li>
            <li><a href="javascript:void(0)" rel="gp_redirect_settings_wrap">Settings</a></li>
        </ul>
        <div class="vtab-content" id="gp_add_redirect_wrap">
            <form id="add_redirect" method="post" alt="gp_redirect">
                <div class="gp_section">
                    <label>Source:</label>
                    <div class="gp_right-section">
                        <?php echo site_url('/'); ?>
                        <input type="text" id="gp_source_url" name="gp_source_url">
                    </div>
                </div>
                <div class="gp_section">
                    <label>Destination:</label>
                    <div class="gp_right-section">
                        <label for="page_type"> <input type="radio" name="url_type" id="page_type" value="page" checked="checked" class="gp_url_type" /> Page</label>
                        <label for="post_type"> <input type="radio" name="url_type" id="post_type" value="post" class="gp_url_type" /> Post</label>				
                        <label for="custom_type"> <input type="radio" name="url_type" id="custom_type" value="custom" class="gp_url_type" /> Custom</label>	
                    </div>
                </div>
                <div class="gp_section">
                    <label></label>
                    <div class="gp_right-section">
                        <span id="load_new_url_input"></span>   
                    </div>
                </div>
                <div class="gp_redirect_section">
                    <div class="gp_section">
                        <input class="button-primary" type="submit" value="Save Redirect" name="gp_add_redirect" id="gp_add_redirect" />
                        <input type="hidden" name="command" value="save_redirect">
                        <input type="hidden" name="action" value="gp_redirect_options">
                    </div>
                </div>
            </form>
            <a href="javascript:void(0)" class="button-primary" id="redirect_delete_multiple">Delete Selected</a>
            <div class="gp_section gp_fullwidth_section">
                <table id="redirect_url_list" class="gp_table" width="100%" >
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="all" /></th>
                            <th>Source</th>
                            <th>Target</th>
                            <th>Url Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_array($redirect_data) && !empty($redirect_data)) {
                            foreach ($redirect_data as $url => $url_data) {
                                ?>
                                <tr alt="<?php echo $url; ?>" >
                                    <th><input type="checkbox" name="urls" value="<?php echo $url; ?>" class="urls" /></th>
                                    <?php if ($redirect_settings['remove_slash']) { ?>
                                        <td width="30%"><?php echo site_url('/') . rtrim($url, '/'); ?></td>
                                    <?php } else { ?>
                                        <td width="30%"><?php echo site_url('/') . $url; ?></td>
                                    <?php } ?>
                                    <?php $redirect_to = ( $url_data['url_type'] == 'custom') ? $url_data['redirect_to'] : get_permalink($url_data['redirect_to']); ?>
        <?php $post_page_id = ' alt="' . $url_data['redirect_to'] . '"'; ?>
                                    <td width="30%"<?php echo $post_page_id; ?>><?php echo $redirect_to; ?></td>
                                    <td width="20%"><?php echo $url_data['url_type']; ?></td>
                                    <td width="20%">
                                        <a href="javascript:void(0)" title="Edit" class="edit_url"><span class="dashicons dashicons-edit"></span></a>
                                        <a href="javascript:void(0)" title="Delete" class="delete_url"><span class="dashicons dashicons-trash"></span></a>
                                    </td>
                                </tr>
                            <?php }
                        }else{ ?>
                                <tr>
                                    <td align="center" colspan="5">No Redirects available </td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>	
            </div>
        </div>
        <div class="vtab-content" id="gp_redirect_settings_wrap">
            <form id="gp_redirect" name ="redirect" method="post" alt="gp_update">
                <div class="gp_section">
                    <label>404 Redirect Page:</label>
                    <div class="gp_right-section">
                        <?php
                        $page_args = array(
                            'depth' => 0,
                            'selected' => $redirect_settings['not_found_page'],
                            'echo' => 1,
                            'name' => 'gp_redirect[not_found_page]',
                            'show_option_none' => 'Home page',
                            'option_none_value' => '/'
                        );
                        wp_dropdown_pages($page_args);
                        ?>
                        <label for="gp_backup_enabled">Select page where user will redirect if current page not found</label>
                    </div>
                </div>
                <div class="gp_section">
                    <label>Remove additional slash(/) form url:</label>
                    <div class="gp_right-section">
                        <input type="hidden" name="gp_redirect[remove_slash]" value="0">
                    <div class="gp-toggle-wrap">
                        <input type="checkbox" class="gp-toggle" id="gp_remove_slash" name="gp_redirect[remove_slash]" value="1" <?php checked('1', $redirect_settings['remove_slash']); ?>>
                        <label for="gp_remove_slash"></label>
                    </div>
                        <label> This will remove the slash(/), from the end of all url's.</label>
                    </div>
                </div>
                <div class="gp_section">
                    <input class="button-primary" type="submit" value="Save Changes" name="gp_redirect_submit" id="gp_redirect_submit" />
                    <input type="hidden" name="command" value="gp_redirect_settings" />
                    <input type="hidden" name="action" value="gp_save_settings" />
                </div>
            </form>
        </div>
    </div>
</div>
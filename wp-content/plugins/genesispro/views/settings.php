<div class="gp-view-wrap">
 <h1 class="gp-article-title">Genesis Settings</h1>
 <hr class="gp-article-divider">
 <h2 class="gp-article-subtitle">Hide Title</h2>
    <div class="gp_inside gp-grid">
        <?php
        $post_types = get_post_types(array(), 'objects');
        foreach ($post_types as $pt) {
            $checked = '';
            if(isset($wsi_settings['gp_hide_title']) && is_array($wsi_settings['gp_hide_title'])){
                if(in_array($pt->name, $wsi_settings['gp_hide_title']))
                        $checked = "checked";
            }
            ?>
            <div class="inner_section gp-width-medium-1-3">
                <input type="checkbox" value="<?php echo $pt->name; ?>" name="gp_settings[gp_hide_title][]" id="gp_hide_<?php echo $pt->name; ?>_title" <?php echo $checked; ?> >
                <span class="element">
                    <label for="gp_hide_<?php echo $pt->name; ?>_title"><?php echo $pt->label; ?></label>
                </span>
                <label for="gp_hide_<?php echo $pt->name; ?>_title"><?php echo $pt->label; ?></label>
            </div>
            <?php
        }
        ?>
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Available WSI Genesis Widgets</h2>
    <div class="gp_inside gp-grid">
        <?php
        $gp_widgets = array(
            'gp_featured_page_post' => 'WSI Featured Page Post',
            'gp_categories' => 'WSI Categories',
            'gp_tag_cloud' => 'WSI Tag Cloud',
        );

        foreach ($gp_widgets as $key => $value) {
            ?>
            <div class="inner_section gp-width-medium-1-3">              
                <input type="hidden" value="0" name="gp_settings[widgets][<?php echo $key; ?>]" >
                <input type="checkbox" value="1" name="gp_settings[widgets][<?php echo $key; ?>]" id="gp_widget_<?php echo $key; ?>" <?php checked('1',$wsi_settings['widgets'][$key]); ?> >
                <span class="element">
                    <label for="gp_widget_<?php echo $key; ?>"><?php echo $value; ?></label>
                </span>
                <label for="gp_widget_<?php echo $key; ?>"><?php echo $value; ?></label> 
            </div>
    <?php } ?>
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Wordpress Default Widgets</h2>
    <div class="gp_inside gp-grid">
        <div class="inner_section gp-width-medium-1-3">
            <input type="hidden" value="0" name="gp_settings[wp_widgets][WP_Widget_Categories]">
            <input type="checkbox" value="1" name="gp_settings[wp_widgets][WP_Widget_Categories]" id="gp_settings_WP_Widget_Categories" <?php checked('1',$wsi_settings['wp_widgets']['WP_Widget_Categories']); ?> >
            <span class="element">
                <label for="gp_settings_WP_Widget_Categories">Categories</label>
            </span>
            <label for="gp_settings_WP_Widget_Categories">Categories</label>
        </div>  

        <div class="inner_section gp-width-medium-1-3">             
            <input type="hidden" value="0" name="gp_settings[wp_widgets][WP_Widget_Pages]">
            <input type="checkbox" value="1" name="gp_settings[wp_widgets][WP_Widget_Pages]" id="gp_settings_WP_Widget_Pages" <?php checked('1',$wsi_settings['wp_widgets']['WP_Widget_Pages']); ?> >
            <span class="element">
                <label for="gp_settings_WP_Widget_Pages">Pages</label>
            </span>
            <label for="gp_settings_WP_Widget_Pages">Pages</label>
        </div>  

        <div class="inner_section gp-width-medium-1-3"> 
            <input type="hidden" value="0" name="gp_settings[wp_widgets][WP_Widget_Recent_Posts]">
            <input type="checkbox" value="1" name="gp_settings[wp_widgets][WP_Widget_Recent_Posts]" id="gp_settings_WP_Widget_Recent_Posts" <?php checked('1',$wsi_settings['wp_widgets']['WP_Widget_Recent_Posts']); ?> >
            <span class="element">
                <label for="gp_settings_WP_Widget_Recent_Posts">Recent Posts</label>
            </span>
            <label for="gp_settings_WP_Widget_Recent_Posts">Recent Posts</label>
        </div> 

        <div class="inner_section gp-width-medium-1-3">
            <input type="hidden" value="0" name="gp_settings[wp_widgets][WP_Widget_Tag_Cloud]">
            <input type="checkbox" value="1" name="gp_settings[wp_widgets][WP_Widget_Tag_Cloud]" id="gp_settings_WP_Widget_Tag_Cloud" <?php checked('1',$wsi_settings['wp_widgets']['WP_Widget_Tag_Cloud']); ?> >
            <span class="element">
                <label for="gp_settings_WP_Widget_Tag_Cloud">Tag Cloud</label>      
            </span>
            <label for="gp_settings_WP_Widget_Tag_Cloud">Tag Cloud</label>      
        </div>         
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Content Archives</h2>
    <div class="gp_section">
        <label>Post Content</label>
        <div class="gp_right-section">
            <select name="gp_settings[post_content]" id="gp_settings_post_content">
                <option value="content" >Content</option>
                <option value="excerpt" <?php selected('excerpt',$wsi_settings['post_content']); ?>>Excerpt</option>
            </select>
        </div>
    </div>
    <div class="gp_section">
        <label>Post Content limit</label>
        <div class="gp_right-section">
            <input type="number" id="gp_settings_content_limit" name="gp_settings[content_limit]" value="<?php echo $wsi_settings['content_limit']; ?>">
            <label for="gp_settings_content_limit"></label>
            <p class="description">( in words )Default :- Full content</p>
        </div>
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Search Result Setting</h2>
    <div class="gp_section">
        <label>Post Content Limit</label>
        <div class="gp_right-section">
            <input type="number" value="<?php echo $wsi_settings['search_content_limit']; ?>" id="gp_settings_search_content_limit" name="gp_settings[search_content_limit]">
            <label for="gp_settings_search_content_limit"></label>
            <p class="description">NOTE: Set Content Limit "BLANK" For Full</p>
        </div>
    </div>
    <div class="gp_section">
        <label>Search result per page</label>
        <div class="gp_right-section">
            <input type="number" value="<?php echo $wsi_settings['search_per_page']; ?>" id="gp_settings_search_per_page" name="gp_settings[search_per_page]">
            <label for="gp_settings_search_per_page"></label>
            <p class="description">NOTE: Setting for search result page</p>
        </div>
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Revision / Auto save</h2>
    <div class="gp_section">
        <label>Revision</label>
        <div class="gp_right-section">
            <input type="number" value="<?php echo $wsi_settings['revision']; ?>" id="gp_settings_revision" name="gp_settings[revision]">
            <label for="gp_settings_revision"></label>
            <p class="description">NOTE: Set Revision Limit Default 5</p>
        </div>
    </div>
    <div class="gp_section">
        <label>Auto Save</label>
        <div class="gp_right-section">
            <input type="number" value="<?php echo $wsi_settings['auto_save']; ?>" id="gp_settings_auto_save" name="gp_settings[auto_save]">
            <label for="gp_settings_auto_save"></label>
            <p class="description">NOTE: Set Auto Save time default 360</p>
        </div>
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Custom Script</h2>
    <div class="gp_section">
        <label>Header</label>
        <div class="gp_right-section">
            <textarea rows="10" cols="50" name="gp_header_script"><?php echo $header_scripts; ?></textarea>
        </div>
    </div>
    <div class="gp_section">
        <label>Footer</label>
        <div class="gp_right-section">
            <textarea rows="10" cols="50" name="gp_footer_script"><?php echo $footer_scripts; ?></textarea>
        </div>
    </div>
    <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Enable Sitemap 
        <span class="sitemap_option">
            <label><input type="radio" name="gp_settings[sitemap]" value="1" <?php checked('1',$wsi_settings['sitemap']); ?> id="gp_setting_sitemap_enable" >Yes</label> 
            <label><input type="radio" name="gp_settings[sitemap]" id="gp_setting_sitemap_disable" value="0" <?php checked('0',$wsi_settings['sitemap']); ?> >No </label>
        </span>
    </h2>
    <table width="100%" border="0" style="<?php echo (!$wsi_settings['sitemap'])?'display:none':''; ?>" id="gp_sitemap_config" class="gp_table_single gp_table">
        <thead>
            <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col" class="page-select">
                    Pages&nbsp;
                    <input type="hidden" value="0" name="gp_sitemap_settings[pages][showhide]">
                    <input type="checkbox" value="1" name="gp_sitemap_settings[pages][showhide]" <?php checked('1',$sitemap_settings['pages']['showhide']) ?> >
                </th>
                <th scope="col" class="author-select">
                    Authors&nbsp;
                    <input type="hidden"  value="0" name="gp_sitemap_settings[authors][showhide]">
                    <input type="checkbox"  value="1" name="gp_sitemap_settings[authors][showhide]" <?php checked('1',$sitemap_settings['authors']['showhide']) ?>>
                </th>
                <th scope="col" class="cat-select">
                    Categories&nbsp;
                    <input type="hidden"  value="0" name="gp_sitemap_settings[categories][showhide]">                  
                    <input type="checkbox"  value="1" name="gp_sitemap_settings[categories][showhide]" <?php checked('1',$sitemap_settings['categories']['showhide']) ?>>                  
                </th>
                <th scope="col" class="post-select">
                    Posts&nbsp;
                    <input type="hidden"  value="0" name="gp_sitemap_settings[posts][showhide]">
                    <input type="checkbox"  value="1" name="gp_sitemap_settings[posts][showhide]" <?php checked('1',$sitemap_settings['posts']['showhide']) ?>>
                </th>
                <th scope="col">
                    Monthly&nbsp;
                    <input type="hidden"  value="0" name="gp_sitemap_settings[monthly][showhide]">
                    <input type="checkbox"  value="1" name="gp_sitemap_settings[monthly][showhide]" <?php checked('1',$sitemap_settings['monthly']['showhide']) ?>>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Order By</th>
                <td align="center" class="fs-sort-page-wrap">
                    <select name="gp_sitemap_settings[pages][orderby]">
                        <option value="ID">ID</option>
                        <option value="post_title" <?php selected('post_title',$sitemap_settings['pages']['orderby']); ?> >Name</option>
                        <option value="post_date" <?php selected('post_date',$sitemap_settings['pages']['orderby']); ?> >Date</option>
                        <option value="menu_order" <?php selected('menu_order',$sitemap_settings['pages']['orderby']); ?> >Order</option>
                    </select>
                </td>
                <td align="center" class="fs-sort-author-wrap">
                    <select name="gp_sitemap_settings[authors][orderby]">
                        <option value="ID">ID</option>
                        <option value="display_name" <?php selected('display_name',$sitemap_settings['authors']['orderby']); ?>>Name</option>
                        <option value="post_count" <?php selected('post_count',$sitemap_settings['authors']['orderby']); ?> >Post Count</option>
                    </select>
                </td>
                <td align="center" class="fs-sort-category-wrap">
                    <select name="gp_sitemap_settings[categories][orderby]">
                        <option value="id">ID </option>
                        <option value="name" <?php selected('name',$sitemap_settings['categories']['orderby']); ?>>Name</option>
                        <option value="count" <?php selected('count',$sitemap_settings['categories']['orderby']); ?> >Post Count</option>
                    </select>
                </td>
                <td align="center" scope="col" class="fs-sort-post-wrap">
                    <select name="gp_sitemap_settings[posts][orderby]">
                        <option value="ID">ID </option>
                        <option value="post_title" <?php selected('post_title',$sitemap_settings['posts']['orderby']); ?> >Name</option>
                        <option value="post_date" <?php selected('post_date',$sitemap_settings['posts']['orderby']); ?> >Date</option>
                        <option value="menu_order" <?php selected('menu_order',$sitemap_settings['posts']['orderby']); ?> >Order</option>
                    </select>
                </td>
                <td align="center" scope="col">--</td>
            </tr>
            <tr>
                <th scope="row">Order</th>
                <td align="center" class="fs-sort-page-wrap">
                    <select name="gp_sitemap_settings[pages][order]">
                        <option value="asc"> ASC</option>
                        <option value="desc" <?php selected('desc',$sitemap_settings['pages']['order']); ?> >DESC </option>
                    </select>
                </td>
                <td align="center" class="fs-sort-author-wrap">
                    <select name="gp_sitemap_settings[authors][order]"> 
                        <option value="asc"> ASC </option>
                        <option value="desc" <?php selected('desc',$sitemap_settings['authors']['order']); ?> > DESC  </option>
                    </select>
                </td>
                <td align="center" class="fs-sort-category-wrap">
                    <select name="gp_sitemap_settings[categories][order]">
                        <option value="asc">ASC</option>
                        <option value="desc" <?php selected('desc',$sitemap_settings['categories']['order']); ?> >DESC</option>
                    </select>
                </td>
                <td align="center" class="fs-sort-post-wrap">
                    <select name="gp_sitemap_settings[posts][order]">
                        <option value="asc">ASC</option>
                        <option value="desc" <?php selected('desc',$sitemap_settings['posts']['order']); ?> > DESC</option>
                    </select>   </td>
                <td align="center" scope="col">--</td>
            </tr>
            <tr>
                <td width="20%" scope="row">List<br>
                    <span class="description"><br><b>NOTE:</b> Select items from list to hide in list </span></td>
                <td width="20%" valign="top" class="fs-page-wrap">
                    <?php
                    $sitemap_page_args = array(
                        'sort_order' => 'ASC',
                        'sort_column' => 'post_title',
                        'hierarchical' => 1,
                        'post_type' => 'page',
                        'post_status' => 'publish'
                    );
                    $sitemap_pages = get_pages($sitemap_page_args);
                    $selected_exclude_pages = explode(",", $sitemap_settings['exclude_page']);
                    ?>
                    <select data-hiddenid="gp_sitemap_pages" class="sitemap-pages-tab" multiple="multiple" >
                        <?php
                        foreach ($sitemap_pages as $page) {
                            $selected = "";
                            if (!empty($selected_exclude_pages) && in_array($page->ID, $selected_exclude_pages))
                                $selected = "selected";
                            
                            echo "<option value='" . $page->ID . "' " . $selected . "> " . $page->post_title . "</option>";
                        }
                        ?>
                    </select>
                    <span class="unselect-all" rel="force_ssl_selected_pages">Unselect All</span>
                    <input type="hidden" name="gp_sitemap_settings[exclude_page]" id="gp_sitemap_pages" value="<?php echo $sitemap_settings['exclude_page']; ?>" >          

                </td>
                <td width="20%" valign="top" class="fs-author-wrap">
                    <?php
                    $author_array = get_users();
                    $selected_exclude_author = explode(",", $sitemap_settings['exclude_author']);
                    $select_author = '';
                    ?>
                    <select data-hiddenid="gp_sitemap_author" class="sitemap-author-tab" multiple="multiple" >
                        <?php
                        foreach ($author_array as $author) {
                            $selected = "";
                            if (!empty($selected_exclude_author) && in_array($author->ID, $selected_exclude_author))
                                $selected = "selected";
                                
                            echo "<option value='" . $author->ID . "' " . $selected . "> " . $author->user_login . "</option>";
                        }
                        ?>
                    </select>
                    <span class="unselect-all" rel="force_ssl_selected_author">Unselect All</span>
                    <input type="hidden" name="gp_sitemap_settings[exclude_author]" id="gp_sitemap_author" value="<?php echo $sitemap_settings['exclude_author']; ?>" >  
                </td>
                <td width="20%" valign="top" class="fs-category-wrap">
                    <?php
                    $sitemap_categories = get_categories();
                    $selected_exlude_categories = explode(",", $sitemap_settings['exclude_category']);
                    ?>
                    <select data-hiddenid="gp_sitemap_category" class="sitemap-category-tab" multiple="multiple" >
                        <?php
                        foreach ($sitemap_categories as $categories) {
                            $selected = "";
                            if (!empty($selected_exlude_categories) && in_array($categories->term_id, $selected_exlude_categories))
                                $selected = "selected";
                            
                            echo "<option value='" . $categories->term_id . "' " . $selected . "> " . $categories->name . "</option>";
                        }
                        ?>
                    </select>
                    <span class="unselect-all" rel="force_ssl_selected_categories">Unselect All</span>
                    <input type="hidden" name="gp_sitemap_settings[exclude_category]" id="gp_sitemap_category" value="<?php echo $sitemap_settings['exclude_category']; ?>" >   
                </td>
                <td width="20%" valign="top" class="fs-post-wrap">
                    <?php
                    $ssl_post_args = array(
                        'numberposts' => '-1',
                        'offset' => 0,
                        'post_type' => 'post',
                        'post_status' => 'publish'
                    );
                    $sitemap_posts = get_posts($ssl_post_args);
                    $selected_exclude_post = explode(",", $sitemap_settings['exclude_post']);
                    ?>
                    <select data-hiddenid="gp_sitemap_post" class="sitemap-post-tab" multiple="multiple" >
                        <?php
                        foreach ($sitemap_posts as $post) {
                            if (!empty($selected_exclude_post) && in_array($post->ID, $selected_exclude_post))
                                $selected = "selected";
                            else
                                $selected = "";

                            echo "<option value='" . $post->ID . "' class='selc' " . $selected . "> " . $post->post_title . "</option>";
                        }
                        ?>
                    </select>
                    <span class="unselect-all" rel="force_ssl_selected_post">Unselect All</span>
                    <input type="hidden" name="gp_sitemap_settings[exclude_post]" id="gp_sitemap_post" value="<?php echo $sitemap_settings['exclude_post']; ?>" >                 
                </td>
                <td align="center" scope="col">--</td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    jQuery(function($){
        $('.sitemap-post-tab,.sitemap-category-tab,.sitemap-pages-tab,.sitemap-author-tab').fSelect();
        $('.sitemap-post-tab,.sitemap-category-tab,.sitemap-pages-tab,.sitemap-author-tab').change(function(){
            var hiddenId = $(this).data('hiddenid');
            $("#"+hiddenId).val($(this).val());
        })
    })

</script>
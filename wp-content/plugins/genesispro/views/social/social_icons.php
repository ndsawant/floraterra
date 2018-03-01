<?php
    $social_icons = array(
        'fa-adn' =>'fa fa-adn',
        'fa-android'=>'fa fa-android',
        'fa-apple'=>'fa fa-apple',
        'fa-bitbucket'=>'fa fa-bitbucket',
        'fa-bitbucket-square'=>'fa fa-bitbucket-square',
        'fa-bitcoin'=>'fa fa-bitcoin',
        'fa-css3'=>'fa fa-css3',
        'fa-dribbble'=>'fa fa-dribbble',
        'fa-dropbox'=>'fa fa-dropbox',
        'fa-facebook'=>'fa fa-facebook',
        'fa-facebook-square'=>'fa fa-facebook-square',
        'fa-flickr'=>'fa fa-flickr',
        'fa-foursquare'=>'fa fa-foursquare',
        'fa-github'=>'fa fa-github',
        'fa-github-alt'=>'fa fa-github-alt',
        'fa-github-square'=>'fa fa-github-square',
        'fa-google-plus'=>'fa fa-google-plus',
        'fa-google-plus-square'=>'fa fa-google-plus-square',
        'fa-html5'=>'fa fa-html5',
        'fa-instagram'=>'fa fa-instagram',
        'fa-linkedin'=>'fa fa-linkedin',
        'fa-linkedin-square'=>'fa fa-linkedin-square',
        'fa-linux'=>'fa fa-linux',
        'fa-maxcdn'=>'fa fa-maxcdn',
        'fa-pagelines'=>'fa fa-pagelines',
        'fa-pinterest'=>'fa fa-pinterest',
        'fa-pinterest-square'=>'fa fa-pinterest-square',
        'fa-renren'=>'fa fa-renren',
        'fa-skype'=>'fa fa-skype',
        'fa-stack-exchange'=>'fa fa-stack-exchange',
        'fa-stack-overflow'=>'fa fa-stack-overflow',
        'fa-trello'=>'fa fa-trello',
        'fa-tumblr'=>'fa fa-tumblr',
        'fa-tumblr-square'=>'fa fa-tumblr-square',
        'fa-twitter'=>'fa fa-twitter',
        'fa-twitter-square'=>'fa fa-twitter-square',
        'fa-vimeo-square'=>'fa fa-vimeo-square',
        'fa-vk'=>'fa fa-vk',
        'fa-weibo'=>'fa fa-weibo',
        'fa-windows'=>'fa fa-windows',
        'fa-xing'=>'fa fa-xing',
        'fa-xing-square'=>'fa fa-xing-square',
        'fa-youtube'=>'fa fa-youtube',
        'fa-youtube-play'=>'fa fa-youtube-play',
        'fa-youtube-square'=>'fa fa-youtube-square',
    );
// //    
//    echo '<pre>';
// print_r($gp_social_icons);
// echo '</pre>';


?>

<form id="gp_social_icons" name ="gp_social_icons" method="post" alt="gp_update">
    <h2>Social Icons</h2> <div id="shortcode_container"><p id="shortcode">[gp_social_icon]</p></div>
    <div class="gp_section">
        <label>Icon size(in px)</label>
        <div class="gp_right-section">
            <input type="number" name="gp_socialicon[size]" value="<?php echo $social_icon_settings['size'] ?>"  style="width:110px;">
        </div>
    </div>
    <div class="gp_section">
        <label>Open links in new tab</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_socialicon[open_new_tab]" value="0"/>
            <div class="gp-toggle-wrap">  
                <input type="checkbox" name="gp_socialicon[open_new_tab]" class="gp-toggle" id="gp_socialicon_open_new_tab"  value="1" <?php checked('1', $social_icon_settings['open_new_tab']); ?> />
                <label for="gp_socialicon_open_new_tab"></label>
            </div>
        </div>
    </div>
    <div class="gp_section">
        <label>Icon Color:</label>
        <div class="gp_right-section">
            <input type="text" name="gp_socialicon[color]" value="<?php echo $social_icon_settings['color']; ?>" class="color-field">       
        </div>
    </div>
    <div class="gp_section">
        <label>Icon Hover Color:</label>
        <div class="gp_right-section">
            <input type="text" name="gp_socialicon[hover_color]" value="<?php echo $social_icon_settings['hover_color'] ?>" class="color-hover-field">            
        </div>
    </div>
    <div class="gp_section">
        <label>Social Icons:</label>    
        <div>
            <input type="button" id="gp_add_new_custom_icon" class="button-primary" value="Add New Custom Icon">
            <input type="button" id="gp_add_new_icon" class="button-primary" value="Add New Font Icon">
        </div>
    </div>
    <div class="gp_socailicon_wrap">
        <?php
            if(!empty($gp_social_icons)){
             
                foreach ($gp_social_icons as $gpsi){ ?>
                    <div class="gp_section" >
                        <?php 
                            if($gpsi['type'] == 'font_icon'){
                        ?>
                        <select name="gp_socialicon[social][icon][]" data-socailicon="iconpicker" class="myselect">
                            <option value="">No icon</option>
                            <?php
                                foreach ($social_icons as $val=>$label){
                                    $str ='';
                                    if($gpsi['icon'] == $label){
                                        $str = 'selected';
                                    }
                                    echo '<option '.$str.'>'.$label.'</option>';
                                }
                            ?>
                        </select>
                        <?php }else{ ?>
                            <div class="img_wrap">
                                <img src="<?php echo $gpsi['icon'];?>" width="40px" height="40px">
                                <input type="hidden" name="gp_socialicon[social][icon][]" value="<?php echo $gpsi['icon'];?>">     
                            </div>
                        <?php } ?>
                        <div class="gp_right-section">
                            <input type="text" name="gp_socialicon[social][url][]" value="<?php echo $gpsi['url']; ?>"  placeholder="Enter your url here"> 
                            <input type="text" name="gp_socialicon[social][alt][]" value="<?php echo $gpsi['alt']; ?>"  placeholder="Enter alt text for link">    
                            <input type="hidden" name="gp_socialicon[social][type][]"  value="<?php echo $gpsi['type']; ?>">     
                            <button id="gp_remove_icon" value="Remove" class="gp-button">Remove</button>
                        </div>
                    </div>
               <?php }
            }else{  ?>
        <div class="gp_section" >
            <select name="gp_socialicon[social][icon][]" data-socailicon="iconpicker" class="myselect">
                <option value="">No icon</option>
                <?php
                    foreach ($social_icons as $val=>$label){
                        echo '<option>'.$label.'</option>';
                    }
                ?>
            </select>
            <div class="gp_right-section">
                <input type="text" name="gp_socialicon[social][url][]"  placeholder="Enter your url here">
                <input type="text" name="gp_socialicon[social][alt][]"  placeholder="Enter alt text for link">   
                <input type="hidden" name="gp_socialicon[social][type][]"  value="font_icon">     
                <button id="gp_remove_icon" class="gp-button">Remove</button>
            </div>
        </div>
            <?php } ?>
    </div>

    <div class="action-wrap">
        <input class="button-primary" type="submit" value="Save Changes" name="gp_social_icons_submit" id="gp_social_icons_submit" />
        <input type="hidden" name="command" value="gp_social_icon_settings" />
        <input type="hidden" name="action" value="gp_save_settings" />
    </div> 
</form>
<input type="hidden" id="website_url" value="<?php echo site_url(); ?>">
<div id="gp_cp_options" style="display: none">
       <?php
            foreach ($social_icons as $val=>$label){
                echo '<option>'.$label.'</option>';
            }
        ?>
</div>
<div id="gp_custom_icon_html" style="display: none">
    <div class="gp_section" >
        <div class="img_wrap">
            <input type="button" alt="gp_add_social_icon" class="button-primary" value="Select Image">
        </div>
        <div class="gp_right-section">
            <input type="hidden" id="gp_socialicon_path" name="gp_socialicon[social][icon][]">     
            <input type="text" name="gp_socialicon[social][url][]"  placeholder="Enter your url here">
            <input type="text" name="gp_socialicon[social][alt][]"  placeholder="Enter alt text for link">     
            <input type="hidden" name="gp_socialicon[social][type][]"  value="custom_icon"> 
            <button id="gp_remove_icon" class="gp-button">Remove</button>
        </div>
    </div>
</div>
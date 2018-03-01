<div id="general_options" class="gp-view-wrap">
        <h1 class="gp-article-title">Genesis Tools</h1>
        <hr class="gp-article-divider">
    <h2 class="gp-article-subtitle">Genesis Logo / Favicon</h2>
    <div class="gp_section">
        <label>Admin Logo</label>
        <div class="gp_right-section">
            <div class="gp-image-container">
                <span class="gp-image-warp">
                <?php
                        if(isset($general_settings['admin_logo']) && $general_settings['admin_logo']!=''){
                            $admin_logo = $general_settings['admin_logo'];
                            ?>
                            <img src="<?php echo site_url().$general_settings['admin_logo']; ?>" width="16px" height="16px">
                            <button alt="gp_remove_image" data-source="#gp_general_admin_logo_val" class="gp-button">Remove</button>
                <?php   }  else {
                            $admin_logo = '';
                            echo 'No image selected';
                        }
                    ?>
                </span>
                <button id="gp_general_admin_logo" alt="gp_add_site_icon" data-width="16" data-height="16" class="gp-button">Select Image</button>
            </div>
            
            <input type="hidden" id="gp_general_admin_logo_val" name="gp_general[admin_logo]" value="<?php echo $admin_logo; ?>">
            <p class="description">NOTE: Image Size ( 16 x 16 px )</p>
        </div>
    </div>
    <div class="gp_section">
        <label>Favicon</label>
        <div class="gp_right-section">
                <div class="gp-image-container">
                    <span class="gp-image-warp">
                <?php
                        if(isset($general_settings['favicon']) && $general_settings['favicon']!=''){ 
                                $favicon = $general_settings['favicon'];
                            ?>
                            <img src="<?php echo site_url().$general_settings['favicon']; ?>" width="32px" height="32px">
                            <button alt="gp_remove_image" data-source="#gp_general_favicon_val" class="gp-button">Remove</button>
                <?php   }  else {
                            $favicon = '';
                            echo 'No image selected';
                        }
                ?>
                    </span>
                    <button id="gp_general_favicon" alt="gp_add_site_icon" data-width="32" data-height="32" class="gp-button">Select Image</button>
                </div>
            <input  type="hidden" value="<?php echo $favicon; ?>" id="gp_general_favicon_val" name="gp_general[favicon]">
            <p class="description">NOTE: Image Size ( 16 x 16 px Or 32 x 32 px ) <a href="http://www.convertico.com/" target="_blank">Generate Favicon</a></p>
        </div>
    </div>
    <div class="gp_section">
        <label>Login Logo</label>
        <div class="gp_right-section">
            <div class="gp-image-container">
                <span class="gp-image-warp">
                <?php
                        if(isset($general_settings['login_logo']) && $general_settings['login_logo']!=''){ 
                            $login_logo = $general_settings['login_logo'];
                            ?>
                            <img src="<?php echo site_url().$general_settings['login_logo']; ?>" width="310px" height="70px">
                            <button alt="gp_remove_image" data-source="#gp_general_login_logo_val" class="gp-button">Remove</button>
                <?php   }  else {
                            $login_logo = '';
                            echo 'No image selected';
                        }
                ?>
                </span>
                <button id="gp_general_login_logo" alt="gp_add_site_icon" data-width="310" data-height="70" class="gp-button">Select Image</button>
            </div>
            
            <input type="hidden" value="<?php echo $login_logo; ?>" id="gp_general_login_logo_val" name="gp_general[login_logo]">
            <p class="description">NOTE: Image Size ( 310 x 70 px ) , this Setting is available only for wordpress default login screen and not for genesis-login</p>
        </div>
    </div>
</div>
<div id="backup_options" class="gp-view-wrap">
    <h1 class="gp-article-title">Security Settings</h1>
    <hr class="gp-article-divider">
    <?php if ($this->GPCommon->is_super_admin()) { ?>
        <div class="gp_section">
            <label for="gp_disable_wordpress_upgrade">Hide WP Update Notice:</label>
            <div class="gp_right-section">
                <input type="hidden" name="gp_secure[disable_wordpress_upgrade]" value="0"/>
                <input type="checkbox" class="gp-toggle" id="gp_disable_wordpress_upgrade" name="gp_secure[disable_wordpress_upgrade]"  value="1" <?php checked('1', $secure_settings['disable_wordpress_upgrade']); ?> />
            </div>
        </div>
        <div class="gp_section">
            <label for="gp_disable_all_auto_core_update">Disables all core updates:</label>
            <div class="gp_right-section">
                <input type="hidden" name="gp_secure[disable_all_auto_core_update]" value="0"/>
                <input type="checkbox" class="gp-toggle" id="gp_disable_all_auto_core_update" name="gp_secure[disable_all_auto_core_update]"  value="1" <?php checked('1', $secure_settings['disable_all_auto_core_update']); ?> />
            </div>
        </div>
        <div class="gp_section">
            <label for="gp_auto_update_plugins">Disable Genesis Pro Updates:</label>
            <div class="gp_right-section">
                <input type="hidden" name="gp_secure[auto_update_plugins]" value="0" />
                <input type="checkbox" class="gp-toggle" id="gp_auto_update_plugins" name="gp_secure[auto_update_plugins]"  value="1" <?php checked(1, $secure_settings['auto_update_plugins']); ?> />
            </div>
        </div>
        <div class="gp_section">
            <label for="gp_en_di_comment_ping">Disable Comments and Ping back :</label>
            <div class="gp_right-section">
                <input type="hidden" name="gp_secure[en_di_comment_ping]" value="0" />
                <input type="checkbox" class="gp-toggle" id="gp_en_di_comment_ping" name="gp_secure[en_di_comment_ping]"  value="1" <?php checked('1', $secure_settings['en_di_comment_ping']); ?> />
            </div>
        </div>
        <div class="gp_section">
            <label for="gp_secure_theme_editor">Disables Theme Editor:</label>
            <div class="gp_right-section">
                <input type="hidden" name="gp_secure[theme_editor]" value="0"/>
                <input type="checkbox" class="gp-toggle" id="gp_secure_theme_editor" name="gp_secure[theme_editor]"  value="1" <?php checked('1', $secure_settings['theme_editor']); ?> />
            </div>
        </div>
    <?php } ?>  

    <div class="gp_section">
        <label for="gp_secure_maintenance_mode">Maintenance Mode:</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_secure[maintenance_mode]" value="0"/>
            <input type="checkbox" class="gp-toggle" id="gp_secure_maintenance_mode" name="gp_secure[maintenance_mode]"  value="1" <?php checked('1', $secure_settings['maintenance_mode']); ?> />
        </div>
    </div>
    <div class="gp_section" id="maintenance_type_container">
        <label>Maintenance Mode Type:</label>
        <div class="gp_right-section">
            <label for="gp_secure_maintenance_mode_type_default">Default
                <input type="radio" id="gp_secure_maintenance_mode_type_default" name="gp_secure[maintenance_mode_type]" value="default" <?php checked('default', $secure_settings['maintenance_mode_type']); ?>/>
            </label>
            <?php
            $filename = ABSPATH . 'maintenance.html';
            $disabled = (file_exists($filename)) ? '' : 'disabled="disabled" ';
            ?>
            <label for="gp_secure_maintenance_mode_type_custom">Custom
                <input type="radio" id="gp_secure_maintenance_mode_type_custom" name="gp_secure[maintenance_mode_type]" value="custom" <?php echo $disabled; ?> <?php checked('custom', $secure_settings['maintenance_mode_type']); ?> />
            </label>
            <p class="description">NOTE: please upload your maintenance.html at root folder for CUSTOM Type</p>
        </div>
    </div>

    <div class="gp_section">
        <label for="gp_secure_login">Secure Login:</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_secure[secure_login]" value="0" />
            <input type="checkbox" class="gp-toggle" name="gp_secure[secure_login]" id="gp_secure_login" value="1" <?php checked('1', $secure_settings['secure_login']); ?> />
        </div>
    </div>

    <div class="gp_section">
        <label>Login Attempt:</label>
        <div class="gp_right-section">
            <?php
            if (isset($secure_settings['login_attempt']) && $secure_settings['login_attempt'] != '') {
                $login_attemps = $secure_settings['login_attempt'];
            } else {
                $login_attemps = 5;
            }
            ?>
            <input type="number" id="gp_secure[login_attempt]" name="gp_secure[login_attempt]" value="<?php echo $login_attemps ?>" />
        </div>
    </div>
    <div class="gp_section">
        <label>Time Lockout (in min):</label>
        <div class="gp_right-section">
            <?php
            if (isset($secure_settings['time_lockout']) && $secure_settings['time_lockout'] != '') {
                $time_lockout = $secure_settings['time_lockout'];
            } else {
                $time_lockout = 12;
            }
            ?>
            <input type="number" id="gp_secure[time_lockout]" style="width:75px !important" name="gp_secure[time_lockout]" value="<?php echo $time_lockout; ?>" />
            <select name="gp_secure[time_lockout_unit]" id="gp_secure[time_lockout_unit]" >
                <option value="min" <?php selected('min', $secure_settings['time_lockout_unit']); ?>>Minute</option>
                <option value="hour" <?php selected('hour', $secure_settings['time_lockout_unit']); ?>>Hour</option>
            </select>
        </div>
    </div>
    <div class="gp_section">
        <label for="gp_admin_notify_lockout">Notify Admin on IP Lockout:</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_secure[admin_notify_lockout]" value="0"/>
            <input type="checkbox" class="gp-toggle" name="gp_secure[admin_notify_lockout]" id="gp_admin_notify_lockout" value="1" <?php checked('1', $secure_settings['admin_notify_lockout']); ?> />
        </div>
    </div>

    <div class="gp_section">
        <label for="gp_login_form_captcha">Enable login Form Captcha:</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_secure[login_form_captcha]" value="0"/>
            <input type="checkbox" class="gp-toggle" name="gp_secure[login_form_captcha]" id="gp_login_form_captcha" value="1" <?php checked('1', $secure_settings['login_form_captcha']); ?> />
        </div>
    </div>

    <div class="gp_section">
        <label for="gp_comment_form_security">Enable Comment Form Captcha:</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_secure[comment_form_security]" value="0"/>
            <input type="checkbox" class="gp-toggle" name="gp_secure[comment_form_security]" id="gp_comment_form_security1" value="1" <?php checked('1', $secure_settings['comment_form_security']); ?> />
        </div>
    </div>
    
    <div id="gp_recaptcha_option">
    <div class="gp_section">
        <label>reCAPTCHA Site Key</label>
        <div class="gp_right-section">
            <?php
            if (isset($secure_settings['reCAPTCHA_Site_Key']) && $secure_settings['reCAPTCHA_Site_Key'] != '') {
                $reCAPTCHA_Site_Key = $secure_settings['reCAPTCHA_Site_Key'];
            } else {
                $reCAPTCHA_Site_Key = '';
            }
            ?>
            <input type="text" id="gp_secure[reCAPTCHA_Site_Key]" name="gp_secure[reCAPTCHA_Site_Key]" value="<?php echo $reCAPTCHA_Site_Key ?>" />
        </div>
    </div>

    <div class="gp_section">
        <label>reCAPTCHA Secret Key</label>
        <div class="gp_right-section">
            <?php
            if (isset($secure_settings['reCAPTCHA_Secret_Key']) && $secure_settings['reCAPTCHA_Secret_Key'] != '') {
                $reCAPTCHA_Secret_Key = $secure_settings['reCAPTCHA_Secret_Key'];
            } else {
                $reCAPTCHA_Secret_Key = '';
            }
            ?>
            <input type="text" id="gp_secure[reCAPTCHA_Secret_Key]" name="gp_secure[reCAPTCHA_Secret_Key]" value="<?php echo $reCAPTCHA_Secret_Key ?>" />
        </div>
    </div>
    </div>
    
    <div class="gp_section">
        <label for="force_ssl_entire">Force SSL:</label>
        <div class="gp_right-section">
            <input type="hidden" name="gp_secure[force_ssl_entire]" value="0"/> 
            <input type="checkbox" name="gp_secure[force_ssl_entire]" id="force_ssl_entire" value="1" <?php checked('1', $secure_settings['force_ssl_entire']); ?> >
            <span class="description" style="color: red;font-weight: normal;display: inline-block"> ( Make sure you have an SSL Certificate on your server )</span>
        </div>
    </div>
</div>

<script type="text/javascript">

    jQuery(function($) {
        if (!$("#gp_secure_maintenance_mode").is(":checked")) {
            $("#maintenance_type_container").hide();
        }
        $("#gp_secure_maintenance_mode").change(function() {
            if ($(this).is(":checked")) {
                $("#maintenance_type_container").slideDown();
            } else {
                $("#maintenance_type_container").slideUp();
            }
        })
    })
</script>
<div id="backup_options" class="gp-view-wrap">
    <h2 class="gp-article-subtitle">Stream Shortcodes</h2>
    <div class="gp_section">
        <div id="shortcode_container" class="social"> 
            <div class="gp_section">
                <label>Facebook Short Code</label>
                <div class="gp_right-section">
                    <pre id="shortcode" name="widget_div" readonly="readonly">[wsi-fb-stream-feeds]</pre>
                </div>
            </div>      
            <div class="gp_section">
                <label>Twitter Short Code</label>
                <div class="gp_right-section">
                    <pre id="shortcode" name="widget_div" readonly="readonly">[wsi-twitter-stream-feeds]</pre>
                </div>
            </div>
            <!--div class="gp_section">
                <label>Google Plus Short Code</label>
                <div class="gp_right-section">
                    <pre id="shortcode" name="widget_div" readonly="readonly">[wsi-gplus-stream-feeds]</pre>
                </div>
            </div-->
        </div>
    </div>
    <h2 class="gp-article-subtitle">Stream Configurations</h2>
    <div id="gp_ver_tab_wrap" class="gp_ver_tab_wrap">
        <ul class="vtab">
            <li class="vtab-active fb">
                <a href="javascript:void(0)" rel="facebook">
                    <img src="<?php echo GENESIS_PRO_IMAGES_URL ?>social_stream/facebook.png" alt="" id="img-icon-facebook">
                </a>
            </li>
            <li class="twitter">
                <a href="javascript:void(0)" rel="twitter">
                    <img src="<?php echo GENESIS_PRO_IMAGES_URL ?>social_stream/twitter.png" alt="" id="img-icon-twitter">
                </a>
            </li>
            <!--li class="gplus">
                <a href="javascript:void(0)" rel="gplus">
                    <img src="<?php echo GENESIS_PRO_IMAGES_URL ?>social_stream/google.png" alt="" id="img-icon-google">
                </a>
            </li-->
        </ul>
        <div class="vtab-content" id="facebook">
            <form id="gp_social_stream_fb" name="fb_stream" method="post" alt="gp_update">
                <?php
                    $gp_social_stream_fb_setting = $this->GPCommon->get_gp_meta('gp_social_stream_fb_setting', true);
                    require_once(GENESIS_PRO_VIEWS_DIR . '/social/social_stream_fb.php');
                ?>
                <div class="action-wrap">
                    <input class="button-primary" type="submit" value="Save Changes" name="gp_social_stream_fb_submit" id="gp_social_stream_fb_submit" />
                    <input type="hidden" name="command" value="gp_social_stream_fb_setting" />
                    <input type="hidden" name="action" value="gp_save_settings" />
                </div>
            </form>
        </div>
        <div class="vtab-content" id="twitter">
            <form id="gp_social_stream_twitter" name="twitter_stream" method="post" alt="gp_update">
                <?php
                $gp_social_stream_twitter_setting = $this->GPCommon->get_gp_meta('gp_social_stream_twitter_setting', true);
                require_once(GENESIS_PRO_VIEWS_DIR . '/social/social_stream_twitter.php');
                ?>
                <div class="action-wrap">
                    <input class="button-primary" type="submit" value="Save Changes" name="gp_social_stream_twitter_submit" id="gp_social_stream_twitter_submit" />
                    <input type="hidden" name="command" value="gp_social_stream_twitter_setting" />
                    <input type="hidden" name="action" value="gp_save_settings" />
                </div>
            </form>
        </div>
        <!--div class="vtab-content" id="gplus">
            <form id="gp_social_stream_gplus" name="gplus_stream" method="post" alt="gp_update">
                <?php
                $gp_social_stream_gplus_setting = $this->GPCommon->get_gp_meta('gp_social_stream_gplus_setting', true);
                require_once(GENESIS_PRO_VIEWS_DIR . '/social/social_stream_gplus.php');
                ?>
                <div class="action-wrap">
                    <input class="button-primary" type="submit" value="Save Changes" name="gp_social_stream_gplus_submit" id="gp_social_stream_gplus_submit" />
                    <input type="hidden" name="command" value="gp_social_stream_gplus_setting" />
                    <input type="hidden" name="action" value="gp_save_settings" />
                </div>
            </form>
        </div-->
    </div>
</div>


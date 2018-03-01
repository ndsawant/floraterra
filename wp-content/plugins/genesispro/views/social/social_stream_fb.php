<h3>Facebook social stream</h3>
<div class="gp_section">
    <label>Facebook App ID</label>
    <div class="gp_right-section">
        <input class="large-text" name="gp_social_stream_fb[facebook_app_id]" id="gp_social_stream_fb_app_id" value="<?php echo $gp_social_stream_fb_setting['facebook_app_id']; ?>" type="text">
    </div>
</div>
<div class="gp_section">
    <label>Facebook App Secret</label>
    <div class="gp_right-section">
        <input class="large-text" name="gp_social_stream_fb[facebook_app_secret_id]" id="gp_social_stream_fb_app_secret_id" value="<?php echo $gp_social_stream_fb_setting['facebook_app_secret_id']; ?>" type="text">
    </div>
</div>
<div class="gp_section">
    <label>Id</label>
    <div class="gp_right-section">
        <input class="large-text" name="gp_social_stream_fb[facebook_id]" id="gp_social_stream_fb_id" value="<?php echo $gp_social_stream_fb_setting['facebook_id']; ?>" type="text">
        <p class="description"> Facebook page wall posts - Enter the page ID<br>
            Enter multiple IDs separated by commas
        </p>
    </div>
</div>
<div class="gp_section">
    <label>Intro</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[intro_text]" id="gp_social_stream_fb_intro_text" value="<?php echo $gp_social_stream_fb_setting['intro_text']; ?>" type="text">
        <p class="description">Text for feed item link</p>
    </div>
</div>
<div class="gp_section">
    <label>Image Width</label>
    <div class="gp_right-section">
        <select id="gp_social_stream_fb_method" name="gp_social_stream_fb[image_width]">
            <option value="6" <?php selected(6, $gp_social_stream_fb_setting['image_width']) ?> >Thumb 180px</option>
            <option value="5" <?php selected(5, $gp_social_stream_fb_setting['image_width']) ?> >Small 320px</option>
            <option value="4" <?php selected(4, $gp_social_stream_fb_setting['image_width']) ?> >Medium 480px</option>
            <option value="3" <?php selected(3, $gp_social_stream_fb_setting['image_width']) ?> >Large 600px</option>
        </select>
        <p class="description">Select image width for facebook album posts</p>
    </div>
</div>
<div class="gp_section">
    <label>Stream</label>
    <div class="gp_right-section">
        <div class="radio">
            <input id="gp_social_stream_fb_days" type="radio" name="gp_social_stream_fb[stream_type]" value="days" <?php checked('days', $gp_social_stream_fb_setting['stream_type']) ?> > 
            <label for="gp_social_stream_fb_days">Days</label>
        </div>
        <div class="radio">
            <input id="gp_social_stream_fb_limit" type="radio" name="gp_social_stream_fb[stream_type]" value="limit" <?php checked('limit', $gp_social_stream_fb_setting['stream_type']) ?> >
            <label for="gp_social_stream_fb_limit">Limit</label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Stream Count</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[stream_count]" id="gp_social_stream_fb_stream_count" type="number" value="<?php echo $gp_social_stream_fb_setting['stream_count']; ?>" >
        <p class="description">Number of post to be shown</p>
    </div>
</div>
<div class="gp_section">
    <label>Height</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[height]" id="gp_social_stream_fb_height" value="<?php echo $gp_social_stream_fb_setting['height']; ?>" type="number" >
    </div>
</div>
<div class="gp_section">
    <label>Order</label>
    <div class="gp_right-section">
        <div class="radio">
            <input id="gp_social_stream_fb_oder_day" type="radio" name="gp_social_stream_fb[order]" value="day" <?php checked('day', $gp_social_stream_fb_setting['order']) ?>>
            <label for="gp_social_stream_fb_oder_day">Days</label>
        </div>
        <div class="radio">
            <input id="gp_social_stream_fb_oder_random" type="radio" name="gp_social_stream_fb[order]" value="random" <?php checked('random', $gp_social_stream_fb_setting['order']) ?> >
            <label for="gp_social_stream_fb_oder_random">Random</label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Control</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[control]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_control" name="gp_social_stream_fb[control]" value="1" <?php checked('1', $gp_social_stream_fb_setting['control']) ?> >
            <label for="gp_social_stream_fb_control"></label>
        </div>
        <p class="description">(Check to enable the control arrows)</p>
    </div>
</div>
<hr class="uk-article-divider">
<h3>Content to include in stream output</h3>
<div class="gp_section">
    <label>Intro</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[intro]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_intro" name="gp_social_stream_fb[intro]" value="1" <?php checked('1', $gp_social_stream_fb_setting['intro']) ?> >
            <label for="gp_social_stream_fb_intro"></label>
        </div>
        <p class="description">Item summary - icon, link & date</p>
    </div>
</div>
<div class="gp_section">
    <label>Thumb</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[thumb]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_thumb" name="gp_social_stream_fb[thumb]" value="1" <?php checked('1', $gp_social_stream_fb_setting['thumb']) ?> >
            <label for="gp_social_stream_fb_thumb"></label>
        </div>
        <p class="description">Thumb:Thumbnail (if available)</p>
    </div>
</div>
<div class="gp_section">
    <label>Title</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[title]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_title" name="gp_social_stream_fb[title]" value="1" <?php checked('1', $gp_social_stream_fb_setting['title']) ?> >
            <label for="gp_social_stream_fb_title"></label>
        </div>
        <p class="description">Feed item title</p>
    </div>
</div>
<div class="gp_section">
    <label>Text</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[text]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_text" name="gp_social_stream_fb[text]" value="1" <?php checked('1', $gp_social_stream_fb_setting['text']) ?> >
            <label for="gp_social_stream_fb_text"></label>
        </div>
        <p class="description">Wall post text</p>
    </div>
</div>
<div class="gp_section">
    <label>User</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[user]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_user" name="gp_social_stream_fb[user]" value="1" <?php checked('1', $gp_social_stream_fb_setting['user']) ?> >
            <label for="gp_social_stream_fb_user"></label>
        </div>
        <p class="description">Display user name</p>
    </div>
</div>
<div class="gp_section">
    <label>Share</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_fb[shared]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_fb_shared" name="gp_social_stream_fb[shared]" value="1" <?php checked('1', $gp_social_stream_fb_setting['shared']) ?> >
            <label for="gp_social_stream_fb_shared"></label>
        </div>
        <p class="description">Include share links</p>
    </div>
</div>

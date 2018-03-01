<h3>Google + social stream</h3>
<div class="gp_section">
    <label>Page Id</label>
    <div class="gp_right-section">
        <input type="text" class="large-text" name="gp_social_stream_gplus[page_id]" id="gp_social_stream_gplus_page_id" value="<?php echo $gp_social_stream_gplus_setting['page_id']; ?>" >
        <p class="description"> 
            1. Enter your Google +1 page ID
            Enter multiple IDs separated by commas
        </p>
    </div>
</div>
<div class="gp_section">
    <label>Intro</label>
    <div class="gp_right-section">
        <input type="text" class="large-text" name="gp_social_stream_gplus[intro_text]" id="gp_social_stream_gplus_intro_text" value="<?php echo $gp_social_stream_gplus_setting['intro_text']; ?>" >
        <p class="description">Text for feed item link</p>
    </div>
</div>
<div class="gp_section">
    <label>API Key</label>
    <div class="gp_right-section">
        <input type="text" class="large-text" name="gp_social_stream_gplus[api_key]" id="gp_social_stream_gplus_api_key" value="<?php echo $gp_social_stream_gplus_setting['api_key']; ?>" >
        <p class="description">Google API KEY - required</p>
    </div>
</div>
<div class="gp_section">
    <label>Stream</label>
    <div class="gp_right-section">
        <div class="radio">
            <input id="gp_social_stream_gplus_stream_day" type="radio" name="gp_social_stream_gplus[stream]" value="days"<?php checked('days', $gp_social_stream_gplus_setting['stream']) ?>>
            <label for="gp_social_stream_gplus_stream_day">Days</label>
        </div>
        <div class="radio">
            <input id="gp_social_stream_gplus_stream_day_limit" type="radio" name="gp_social_stream_gplus[stream]" value="limit" <?php checked('limit', $gp_social_stream_gplus_setting['stream']) ?>>
            <label for="gp_social_stream_gplus_stream_day_limit">Limit</label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Stream Count</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[stream_count]" id="gp_social_stream_gplus_stream_count" type="number" value="<?php echo $gp_social_stream_gplus_setting['stream_count']; ?>">
    </div>
</div>
<div class="gp_section">
    <label>Height</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[height]" id="gp_social_stream_gplus_height" type="number" value="<?php echo $gp_social_stream_gplus_setting['height']; ?>">
    </div>
</div>
<div class="gp_section">
    <label>Order</label>
    <div class="gp_right-section">
        <div class="radio">
            <input id="gp_social_stream_gplus_oder_day" type="radio" name="gp_social_stream_gplus[order]" value="day" <?php checked('day', $gp_social_stream_gplus_setting['order']) ?>>
            <label for="gp_social_stream_gplus_oder_day">Days</label>
        </div>
        <div class="radio">
            <input id="gp_social_stream_gplus_oder_random" type="radio" name="gp_social_stream_gplus[order]" value="random" <?php checked('random', $gp_social_stream_gplus_setting['order']) ?> >
            <label for="gp_social_stream_gplus_oder_random">Random</label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Controls</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[controls]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_gplus_controls" name="gp_social_stream_gplus[controls]" value="1" <?php checked('1', $gp_social_stream_gplus_setting['controls']) ?>>
            <label for="gp_social_stream_gplus_controls"></label>
        </div>
        <p class="description">(Check to enable the control arrows)</p>
    </div>
</div>
 <hr class="uk-article-divider">
<h3>Content to include in stream output</h3>
<div class="gp_section">
    <label>Intro</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[intro]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_gplus_intro" name="gp_social_stream_gplus[intro]" value="1" <?php checked('1', $gp_social_stream_gplus_setting['intro']) ?>>
            <label for="gp_social_stream_gplus_intro"></label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Thumb</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[thumb]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_gplus_thumb" name="gp_social_stream_gplus[thumb]" value="1" <?php checked('1', $gp_social_stream_gplus_setting['thumb']) ?>>
            <label for="gp_social_stream_gplus_thumb"></label>
        </div>
        <p class="description">Include profile avatar</p>
    </div>
</div>
<div class="gp_section">
    <label>Title</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[title]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_gplus_title" name="gp_social_stream_gplus[title]" value="1" <?php checked('1', $gp_social_stream_gplus_setting['title']) ?>>
            <label for="gp_social_stream_gplus_title"></label>
        </div>
        <p class="description">Text block</p>
    </div>
</div>
<div class="gp_section">
    <label>Text</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[text]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_gplus_text" name="gp_social_stream_gplus[text]" value="1" <?php checked('1', $gp_social_stream_gplus_setting['text']) ?> >
            <label for="gp_social_stream_gplus_text"></label>
        </div>
        <p class="description">Wall post text</p>
    </div>
</div>
<div class="gp_section">
    <label>Share</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_gplus[shared]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_gplus_shared" name="gp_social_stream_gplus[shared]" value="1" <?php checked('1', $gp_social_stream_gplus_setting['shared']) ?> >
            <label for="gp_social_stream_gplus_shared"></label>
        </div>
        <p class="description">Include share links</p>
    </div>
</div>
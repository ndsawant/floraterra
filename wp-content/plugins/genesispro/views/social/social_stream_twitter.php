<h3>Twitter social stream</h3>
<div class="gp_section">
    <label>Consumer Key</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[consumer_key]" id="gp_social_stream_twitter_consumer_key" class="large-text" value="<?php echo $gp_social_stream_twitter_setting['consumer_key']; ?>" >
    </div>
</div>
<div class="gp_section">
    <label>Consumer Secret</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[consumer_secret]" id="gp_social_stream_twitter_consumer_secret" class="large-text" value="<?php echo $gp_social_stream_twitter_setting['consumer_secret']; ?>">
    </div>
</div>
<div class="gp_section">
    <label>OAuth Access Token</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[access_token]" id="gp_social_stream_twitter_access_token" class="large-text" value="<?php echo $gp_social_stream_twitter_setting['access_token']; ?>" >
    </div>
</div>
<div class="gp_section">
    <label>OAuth Access Token Secret</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[access_secret]" id="gp_social_stream_twitter_access_secret" class="large-text" value="<?php echo $gp_social_stream_twitter_setting['access_secret']; ?>" >
    </div>
</div>
<div class="gp_section">
    <label>Id</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[id]" id="gp_social_stream_twitter_id" class="large-text" value="<?php echo $gp_social_stream_twitter_setting['id']; ?>" >
        <p class="description"> 
            1. Enter a twitter username without the "@"<br>
            2. To use a twitter list enter "/" followed by the list ID - e.g. /123456<br>
            3. To search enter "#" followed by the search terms - e.g. #designchemical
        </p>
    </div>
</div>
<div class="gp_section">
    <label>Intro</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[intro_text]" id="gp_social_stream_twitter_intro_text" value="tweeted" value="<?php echo $gp_social_stream_twitter_setting['intro_text']; ?>">
        <p class="description">Text for feed item link</p>
    </div>
</div>
<div class="gp_section">
    <label>Search Text</label>
    <div class="gp_right-section">
        <input type="text" name="gp_social_stream_twitter[search_text]" id="gp_social_stream_twitter_search_text" value="<?php echo $gp_social_stream_twitter_setting['search_text']; ?>">
        <p class="description">Text for search item link</p>
    </div>
</div>
<div class="gp_section">
    <label>Images</label>
    <div class="gp_right-section">
        <select id="gp_social_stream_fb_method" name="gp_social_stream_twitter[images]">
            <option value="" selected="selected">None</option>
            <option value="thumb" <?php selected('thumb', $gp_social_stream_twitter['images']) ?> >Thumb - w: 150px h: 150px</option>
            <option value="small" <?php selected('small', $gp_social_stream_twitter['images']) ?> >Small - w: 340px h 150px</option>
            <option value="medium" <?php selected('medium', $gp_social_stream_twitter['images']) ?> >Medium - w: 600px h: 264px</option>
            <option value="large" <?php selected('large', $gp_social_stream_twitter['images']) ?> >Large - w: 786px h: 346px</option>
        </select>
        <p class="description">Include Twitter images</p>
    </div>
</div>
<div class="gp_section">
    <label>Retweets</label>
    <div class="gp_right-section">
        <input type="hidden" name="gp_social_stream_twitter[retweets]" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_retweets" name="gp_social_stream_twitter[retweets]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['retweets']) ?>>
            <label for="gp_social_stream_twitter_retweets"></label>
        </div>
        <p class="description">(Include retweets)</p>
    </div>
</div>
<div class="gp_section">
    <label>Replies</label>
    <div class="gp_right-section">
        <input type="hidden"  name="gp_social_stream_twitter[replies]" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_replies" name="gp_social_stream_twitter[replies]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['replies']) ?>>
            <label for="gp_social_stream_twitter_replies"></label>
        </div>
        <p class="description">(Include replies)</p>
    </div>
</div>
<div class="gp_section">
    <label>Stream</label>
    <div class="gp_right-section">
        <div class="radio">
            <input id="gp_social_stream_twitter_stream_day" type="radio" name="gp_social_stream_twitter[stream]" value="days"<?php checked('days', $gp_social_stream_twitter_setting['stream']) ?>>
            <label for="gp_social_stream_twitter_stream_day">Days</label>
        </div>
        <div class="radio">
            <input id="gp_social_stream_twitter_stream_day_limit" type="radio" name="gp_social_stream_twitter[stream]" value="limit" <?php checked('limit', $gp_social_stream_twitter_setting['stream']) ?>>
            <label for="gp_social_stream_twitter_stream_day_limit">Limit</label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Stream Count</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[stream_count]" id="gp_social_stream_twitter_stream_count" type="number" value="<?php echo $gp_social_stream_twitter_setting['stream_count']; ?>">
    </div>
</div>
<div class="gp_section">
    <label>Height</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[height]" id="gp_social_stream_twitter_height" type="number" value="<?php echo $gp_social_stream_twitter_setting['height']; ?>">
    </div>
</div>
<div class="gp_section">
    <label>Order</label>
    <div class="gp_right-section">
        <div class="radio">
            <input id="gp_social_stream_twitter_oder_day" type="radio" name="gp_social_stream_twitter[order]" value="day" <?php checked('day', $gp_social_stream_twitter_setting['order']) ?>>
            <label for="gp_social_stream_twitter_oder_day">Days</label>
        </div>
        <div class="radio">
            <input id="gp_social_stream_twitter_oder_random" type="radio" name="gp_social_stream_twitter[order]" value="random" <?php checked('random', $gp_social_stream_twitter_setting['order']) ?> >
            <label for="gp_social_stream_twitter_oder_random">Random</label>
        </div>
    </div>
</div>
<div class="gp_section">
    <label>Controls</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[controls]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_controls" name="gp_social_stream_twitter[controls]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['controls']) ?>>
            <label for="gp_social_stream_twitter_controls"></label>
        </div>
        <p class="description">(Check to enable the control arrows)</p>
    </div>
</div>
 <hr class="uk-article-divider">
<h3>Content to include in stream output</h3>
<div class="gp_section">
    <label>Intro</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[intro]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_intro" name="gp_social_stream_twitter[intro]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['intro']) ?>>
            <label for="gp_social_stream_twitter_intro"></label>
        </div>
        <p class="description">Include profile avatar</p>
    </div>
</div>
<div class="gp_section">
    <label>Thumb</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[thumb]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_thumb" name="gp_social_stream_twitter[thumb]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['thumb']) ?>>
            <label for="gp_social_stream_twitter_thumb"></label>
        </div>
        <p class="description">Include profile avatar</p>
    </div>
</div>
<div class="gp_section">
    <label>Text</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[text]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_text" name="gp_social_stream_twitter[text]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['text']) ?> >
            <label for="gp_social_stream_twitter_text"></label>
        </div>
        <p class="description">Wall post text</p>
    </div>
</div>
<div class="gp_section">
    <label>Share</label>
    <div class="gp_right-section">
        <input name="gp_social_stream_twitter[shared]" type="hidden" value="0">
        <div class="gp-toggle-wrap">
            <input type="checkbox" class="gp-toggle" id="gp_social_stream_twitter_shared" name="gp_social_stream_twitter[shared]" value="1" <?php checked('1', $gp_social_stream_twitter_setting['shared']) ?> >
            <label for="gp_social_stream_twitter_shared"></label>
        </div>
        <p class="description">Include share links</p>
    </div>
</div>
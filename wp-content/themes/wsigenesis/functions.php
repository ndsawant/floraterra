<?php
// Page Header Image
function wsi_post_top_image() {
    $parents = wp_get_post_parent_id($post->ID);
	/*if ( ! has_excerpt() ) {
		$excerpt = '<h1 class="uk-text-white">'.get_the_title($post->ID).'</h1>';
	}else {
		$excerpt = get_the_excerpt($post->ID);
	}*/
	if ( has_excerpt() ) {
		$excerpt = get_the_excerpt($post->ID);
	}
    if (has_post_thumbnail()) {
        $res = '<div class="uk-page-banner"><div class="uk-background-cover uk-position-cover uk-flex uk-flex-center uk-flex-middle" style="background-image: url('.get_the_post_thumbnail_url($post->ID).');"><div class="uk-text-center uk-text-white">' . $excerpt . '</div></div><p class="uk-margin-remove"><img alt="header-image" class="uk-invisible" src="'.get_the_post_thumbnail_url($post->ID) .'"/></p></div>';
    }
    return $res;
}

add_shortcode('wsi_top_banner', 'wsi_post_top_image');

add_post_type_support( 'page', 'excerpt' );


function html_cut($text, $max_length) {
    $tags = array();
    $result = "";

    $is_open = false;
    $grab_open = false;
    $is_close = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length) {
        $symbol = $text{$i};
        $result .= $symbol;

        switch ($symbol) {
            case '<':
                $is_open = true;
                $grab_open = true;
                break;

            case '"':
                if ($in_double_quotes)
                    $in_double_quotes = false;
                else
                    $in_double_quotes = true;

                break;

            case "'":
                if ($in_single_quotes)
                    $in_single_quotes = false;
                else
                    $in_single_quotes = true;

                break;

            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes) {
                    $is_close = true;
                    $is_open = false;
                    $grab_open = false;
                }

                break;

            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;

                break;

            case '>':
                if ($is_open) {
                    $is_open = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                } else if ($is_close) {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }

                break;

            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;

                if (!$is_open && !$is_close)
                    $stripped++;
        }

        $i++;
    }

    while ($tags)
        $result .= "</" . array_pop($tags) . ">";

    return $result;
}
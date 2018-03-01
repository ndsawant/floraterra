<?php

$attrs_item = [];
$attrs_overlay = [];
$attrs_center = [];
$attrs_cover = [];
$attrs_image = [];
$attrs_hover_image = [];
$attrs_link = [];
$placeholder = '';
$attrs_placeholder = [];
$lightbox_caption = '';

// Display
if (!$element['show_title']) { $item['title'] = ''; }
if (!$element['show_meta']) { $item['meta'] = ''; }
if (!$element['show_content']) { $item['content'] = ''; }
if (!$element['show_link']) { $item['link'] = ''; }
if (!$element['show_hover_image']) { $item['hover_image'] = ''; }

// Animation
if ($element['item_animation'] != 'none' && $element->parent('section', 'animation') && $element->parent->type == 'column') {
    $attrs_item['uk-scrollspy-class'] = $element['item_animation'] ? "uk-animation-{$element['item_animation']}" : true;
}

// Max Width
$attrs_item['class'][] = $element['item_maxwidth'] ? "uk-width-{$element['item_maxwidth']} uk-margin-auto" : '';

// Container
$attrs_item['class'][] = 'el-item';

if ($element['image_box_shadow_bottom']) {
    $attrs_item['class'][] = 'uk-box-shadow-bottom';
} else {
    $attrs_item['class'][] = 'uk-inline-clip';
}

// Mode
if ($element['overlay_mode'] == 'cover' && $element['overlay_style']) {
    $attrs_overlay['class'][] = "uk-position-cover";
    $attrs_overlay['class'][] = $element['overlay_margin'] ? "uk-position-{$element['overlay_margin']}" : '';
}

$attrs_content['class'][] = 'el-content';

// Style
switch ($element['overlay_style']) {
    case '':
        $attrs_content['class'][] = 'uk-panel';
        break;
    default:
        $attrs_overlay['class'][] = "uk-{$element['overlay_style']}";
        $attrs_content['class'][] = 'uk-overlay';
}

// Padding
switch ($element['overlay_padding']) {
    case '':
        $attrs_content['class'][] = !$element['overlay_style'] ? 'uk-padding' : '';
        break;
    case 'none':
        $attrs_content['class'][] = $element['overlay_style'] ? 'uk-padding-remove' : '';
        break;
    default:
        $attrs_content['class'][] = "uk-padding-{$element['overlay_padding']}";
}

// Position
if (in_array($element['overlay_position'], ['center', 'top-center', 'bottom-center', 'center-left', 'center-right'])) {
    $attrs_center['class'][] = "uk-position-{$element['overlay_position']}";
    $attrs_center['class'][] = $element['overlay_margin'] && $element['overlay_style'] ? "uk-position-{$element['overlay_margin']}" : '';
} else {
    $attrs_content['class'][] = "uk-position-{$element['overlay_position']}";
    $attrs_content['class'][] = $element['overlay_margin'] && $element['overlay_style'] ? "uk-position-{$element['overlay_margin']}" : '';
}

// Width
if (!in_array($element['overlay_position'], ['top', 'bottom'])) {
    $attrs_content['class'][] = $element['overlay_maxwidth'] ? "uk-width-{$element['overlay_maxwidth']}" : '';
}

// Transition
if ($element['overlay_hover'] || $element['image_transition'] || $item['hover_image']) {
    $attrs_item['class'][] = 'uk-transition-toggle';
}

if ($element['overlay_hover']) {

    if ($element['overlay_transition_background'] && ($element['overlay_mode'] == 'cover' && $element['overlay_style'])) {
        $attrs_overlay['class'][] = "uk-transition-{$element['overlay_transition']}";
    } else {
        $attrs_overlay['class'][] = "uk-transition-{$element['overlay_transition']}";
        $attrs_content['class'][] = "uk-transition-{$element['overlay_transition']}";
    }

}

// Text color
if (!$element['overlay_style'] || ($element['overlay_mode'] == 'cover' && $element['overlay_style'])) {
    $attrs_item['class'][] = $item['text_color'] ? "uk-{$item['text_color']}" : ($element['text_color'] ? "uk-{$element['text_color']}" : '');
}

// Inverse text color on hover
if ((!$element['overlay_style'] && $item['hover_image']) || ($element['overlay_mode'] == 'cover' && $element['overlay_style'] && $element['overlay_transition_background'])) {
    $attrs_item['uk-toggle'] = $item['text_color_hover'] || $element['text_color_hover'] ? "cls: uk-light uk-dark; mode: hover" : false;
}

// If link is not set use the default image for the lightbox
if (!$item['link'] && $element['lightbox']) {
    $item['link'] = $item['image'];
}

// Image
if ($item['image'] || $item['hover_image']) {

    // Transition
    if ($item['hover_image'] && !$item['image']) {
        $item['image'] = $item['hover_image'];
        $item['hover_image'] = '';
        $attrs_image['class'][] = $element['image_transition'] ? "uk-transition-{$element['image_transition']}" : 'uk-transition-fade';
    } elseif ($item['hover_image'])
        $attrs_hover_image['class'][] = $element['image_transition'] ? "uk-transition-{$element['image_transition']}" : 'uk-transition-fade';
    else {
        $attrs_image['class'][] = $element['image_transition'] ? "uk-transition-{$element['image_transition']} uk-transition-opaque" : '';
    }

    $attrs_placeholder['alt'] = $item['image_alt'];
    $attrs_placeholder['class'][] = 'uk-invisible';

    // Image Placeholder
    if ($this->isImage($item['image']) == 'svg') {
        $placeholder = $this->image($item['image'], array_merge($attrs_placeholder, ['width' => $element['image_width'], 'height' => $element['image_height']]));
    } elseif ($element['image_width'] || $element['image_height']) {
        $placeholder = $this->image([$item['image'], 'thumbnail' => [$element['image_width'], $element['image_height'], $element['image_orientation']]], $attrs_placeholder);
    } else {
        $placeholder = $this->image($item['image'], $attrs_placeholder);
    }

    // Image
    $attrs_image['class'][] = 'el-image uk-inline uk-background-norepeat uk-background-cover';
    $attrs_image['style'][] = $element['image_min_height'] ? "min-height: {$element['image_min_height']}px;" : '';

    if ($element['image_width'] || $element['image_height']) {
        $item['image'] = "{$item['image']}#thumbnail={$element['image_width']},{$element['image_height']},{$element['image_orientation']}";
    }

    $attrs_image['style'][] = "background-image: url('{$app['image']->getUrl($item['image'])}');";
    $item['image'] = "<div{$this->attrs($attrs_image)}>{$placeholder}</div>";

    // Hover Image
    if ($item['hover_image']) {

        $attrs_hover_image['class'][] = 'el-hover-image uk-position-cover uk-background-norepeat uk-background-cover';

        if ($element['image_width'] || $element['image_height']) {
            $item['hover_image'] = "{$item['hover_image']}#thumbnail={$element['image_width']},{$element['image_height']},{$element['image_orientation']}";
        }

        $attrs_hover_image['style'][] = "background-image: url('{$app['image']->getUrl($item['hover_image'])}');";
        $item['image'] .= "<div{$this->attrs($attrs_hover_image)}></div>";

    }

    // Box Shadow
    $attrs_item['class'][] = $element['image_box_shadow'] ? "uk-box-shadow-{$element['image_box_shadow']}" : '';
    $attrs_item['class'][] = $element['image_hover_box_shadow'] ? "uk-box-shadow-hover-{$element['image_hover_box_shadow']}" : '';

}

// Link and Lightbox
if ($element['lightbox']) {

    if ($this->isImage($item['link'])) {

        if (($element['lightbox_image_width'] || $element['lightbox_image_height']) && $this->isImage($item['link']) != 'svg') {
            $item['link'] = "{$item['link']}#thumbnail={$element['lightbox_image_width']},{$element['lightbox_image_height']},{$element['lightbox_image_orientation']}";
        }

        $item['link'] = $app['image']->getUrl($item['link']);
        $attrs_link['data-type'] = 'image';

    } elseif ($this->isVideo($item['link'])) {

        $attrs_link['data-type'] = 'video';

    } elseif (!$this->iframeVideo($item['link'])) {

        $attrs_link['data-type'] = 'iframe';

    } else {

        $attrs_link['data-type'] = true;

    }

    if ($item['title'] && $element['title_display'] != 'item') {
        $lightbox_caption .= "<h4 class='uk-margin-remove'>{$item['title']}</h4>";
        if ($element['title_display'] == 'lightbox') {
            $item['title'] = '';
        }
    }

    if ($item['content'] && $element['content_display'] != 'item') {
        $lightbox_caption .= $item['content'];
        if ($element['content_display'] == 'lightbox') {
            $item['content'] = '';
        }
    }

    $lightbox_caption = $lightbox_caption ? ' data-caption="'.str_replace('"', '&quot;', $lightbox_caption).'"' : '';

} else {
    $attrs_link['target'] = $element['link_target'] ? '_blank' : '';
    $attrs_link['uk-scroll'] = strpos($item['link'], '#') === 0;
}

$attrs_link['href'] = $item['link'];
$attrs_link['class'][] = 'uk-position-cover';

?>

<div<?= $this->attrs($attrs_item) ?>>

    <?php if ($element['image_box_shadow_bottom']) : ?>
    <div class="uk-inline-clip">
    <?php endif ?>

    <?= $item['image'] ?>

    <?php if ($element['overlay_mode'] == 'cover' && $element['overlay_style']) : ?>
    <div<?= $this->attrs($attrs_overlay) ?>></div>
    <?php endif ?>

    <?php if ($item['title'] || $item['meta'] || $item['content']) : ?>

        <?php if ($attrs_center) : ?>
        <div<?= $this->attrs($attrs_center) ?>>
        <?php endif ?>

            <div<?= $this->attrs($attrs_content, !($element['overlay_mode'] == 'cover' && $element['overlay_style']) ? $attrs_overlay : []) ?>>
                <?= $this->render('@builder/gallery/template-content') ?>
            </div>

        <?php if ($attrs_center) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

    <?php if ($item['link']) : ?>
    <a<?= $this->attrs($attrs_link) ?><?= $lightbox_caption ?>></a>
    <?php endif ?>

    <?php if ($element['image_box_shadow_bottom']) : ?>
    </div>
    <?php endif ?>

</div>

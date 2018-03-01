<?php

$id = $element['id'];
$class = [];
$attrs_tile = [];
$attrs_tile_container = [];
$attrs_image = [];
$attrs_overlay = [];
$attrs_container = [];

// Width
$index = $element->index;
$widths = $element['widths'] ?: array_map(function ($widths) use ($index) {
    // Deprecated
    return explode(',', $widths)[$index];
}, explode('|', $element->parent['layout']));
$breakpoints = ['s', 'm', 'l', 'xl'];
$breakpoint = $element->parent['breakpoint'];

// Above Breakpoint
$width = $widths[0] ?: 'expand';
$width = $width === 'fixed' ? $element->parent['fixed_width'] : $width;
$class[] = "uk-width-{$width}".($breakpoint ? "@{$breakpoint}" : '');

// Intermediate Breakpoint
if (isset($widths[1]) && $pos = array_search($breakpoint, $breakpoints)) {
    $breakpoint = $breakpoints[$pos-1];
    $width = $widths[1] ?: 'expand';
    $class[] = "uk-width-{$width}".($breakpoint ? "@{$breakpoint}" : '');
}

// Order
if (!isset($element->parent->children[$index + 1]) && $element->parent['order_last']) {
    $class[] = "uk-flex-first@{$breakpoint}";
}

// Visibility
$visibilities = ['xs', 's', 'm', 'l', 'xl'];
$visible = $element->count() ? 4 : false;

foreach ($element as $el) {
    $visible = min(array_search($el['visibility'], $visibilities), $visible);
}

if ($visible) {
    $element['visibility'] = $visibilities[$visible];
    $class[] = "uk-visible@{$visibilities[$visible]}";
}

/*
 * Column options
 */

// Tile
if ($element['style'] || $element['image']) {

    $class[] = 'uk-grid-item-match';
    $attrs_tile_container['class'][] = $element['style'] ? "uk-tile-{$element['style']}" : '';
    $attrs_tile['class'][] = 'uk-tile';

    // Padding
    switch ($element['padding']) {
        case '':
            break;
        case 'none':
            $attrs_tile['class'][] = 'uk-padding-remove';
            break;
        default:
            $attrs_tile['class'][] = "uk-tile-{$element['padding']}";
    }

    // Image
    if ($element['image']) {

        if ($element['image_width'] || $element['image_height']) {
            if ($this->isImage($element['image']) == 'svg' && !$element['image_size']) {
                $element['image_width'] = $element['image_width'] ? "{$element['image_width']}px" : 'auto';
                $element['image_height'] = $element['image_height'] ? "{$element['image_height']}px" : 'auto';
                $attrs_image['style'][] = "background-size: {$element['image_width']} {$element['image_height']};";
            } else {
                $element['image'] = "{$element['image']}#thumbnail={$element['image_width']},{$element['image_height']}";
            }
        }

        $attrs_image['style'][] = "background-image: url('{$app['image']->getUrl($element['image'])}');";

        // Settings
        $attrs_image['class'][] = 'uk-background-norepeat';
        $attrs_image['class'][] = $element['image_size'] ? "uk-background-{$element['image_size']}" : '';
        $attrs_image['class'][] = $element['image_position'] ? "uk-background-{$element['image_position']}" : '';
        $attrs_image['class'][] = $element['image_visibility'] ? "uk-background-image@{$element['image_visibility']}" : '';
        $attrs_image['class'][] = $element['media_blend_mode'] ? "uk-background-blend-{$element['media_blend_mode']}" : '';
        $attrs_image['style'][] = $element['media_background'] ? "background-color: {$element['media_background']};" : '';
        $attrs_tile_container['class'][] = 'uk-grid-item-match';

        switch ($element['image_effect']) {
            case '':
                break;
            case 'fixed':
                $attrs_image['class'][] = 'uk-background-fixed';
                break;
            case 'parallax':

                $options = [];

                foreach(['bgx', 'bgy'] as $prop) {
                    $start = $element["image_parallax_{$prop}_start"];
                    $end = $element["image_parallax_{$prop}_end"];

                    if (strlen($start) || strlen($end)) {
                        $options[] = "{$prop}: " . (strlen($start) ? $start : 0) . "," . (strlen($end) ? $end : 0);
                    }
                }

                $options[] = $element['image_parallax_breakpoint'] ? "media: @{$element['image_parallax_breakpoint']}" : '';
                $attrs_image['uk-parallax'] = implode(';', array_filter($options));

                break;
        }

        // Overlay
        if ($element['media_overlay']) {
            $attrs_tile_container['class'][] = 'uk-position-relative';
            $attrs_overlay['style'] = "background-color: {$element['media_overlay']};";
        }

    }

}

// Make sure overlay is always below content
if ($attrs_overlay) {
    $attrs_container['class'][] = 'uk-position-relative uk-panel';
}

// Text color
if ($element['style'] == 'primary' || $element['style'] == 'secondary') {
    $attrs_tile_container['class'][] = $element['preserve_color'] ? 'uk-preserve-color' : '';
} elseif (!$element['style'] || $element['image']) {
    $class[] = $element['text_color'] ? "uk-{$element['text_color']}" : '';
}

// Match height if single panel element inside cell
if ($element->parent['match'] && !$element->parent['vertical_align'] && count($element) == 1 && $element->children[0]->type == 'panel') {

    if ($element['style'] || $element['image']) {
        $attrs_tile['class'][] = 'uk-grid-item-match';
    } else {
        $class[] = 'uk-grid-item-match';
    }

}

?>

<div<?= $this->attrs(compact('id', 'class')) ?>>

    <?php if ($attrs_tile) : ?>
    <div<?= $this->attrs($attrs_tile_container, !$attrs_image ? $attrs_tile : []) ?>>
    <?php endif ?>

        <?php if ($attrs_image) : ?>
        <div<?= $this->attrs($attrs_image, $attrs_tile) ?>>
        <?php endif ?>

            <?php if ($attrs_overlay) : ?>
            <div class="uk-position-cover"<?= $this->attrs($attrs_overlay) ?>></div>
            <?php endif ?>

            <?php if ($attrs_container) : ?>
            <div<?= $this->attrs($attrs_container) ?>>
            <?php endif ?>

                <?= $element ?>

            <?php if ($attrs_container) : ?>
            </div>
            <?php endif ?>

        <?php if ($attrs_image) : ?>
        </div>
        <?php endif ?>

    <?php if ($attrs_tile) : ?>
    </div>
    <?php endif ?>

</div>

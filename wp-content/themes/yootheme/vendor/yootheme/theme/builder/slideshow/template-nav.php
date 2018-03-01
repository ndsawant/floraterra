<?php

$attrs_nav = [];
$attrs_nav_container = [];

$attrs_nav['class'][] = 'el-nav';

// Style
$attrs_nav['class'][] = $element['nav'] ? "uk-{$element['nav']}" : '';

if ($element['nav_below']) {

    // Alignment
    $attrs_nav['class'][] = "uk-flex-{$element['nav_align']}";

    // Margin
    switch ($element['nav_margin']) {
        case '':
            $attrs_nav['class'][] = 'uk-margin';
            break;
        default:
            $attrs_nav['class'][] = "uk-margin-{$element['nav_margin']}";
    }


} else {

    // Position
    $attrs_nav_container['class'][] = "uk-position-{$element['nav_position']}";

    // Margin
    $attrs_nav_container['class'][] = $element['nav_position_margin'] ? "uk-position-{$element['nav_position_margin']}" : '';

    // Text Color
    $attrs_nav_container['class'][] = $element['text_color'] ? "uk-{$element['text_color']}" : '';

    // Vertical
    $attrs_nav['class'][] = $element['nav_vertical'] ? "uk-{$element['nav']}-vertical" : '';

}

// Wrapping
if (!$element['nav_vertical']) {

    $attrs_nav['uk-margin'] = true;

    if (!$element['nav_below']) {
        switch ($element['nav_position']) {
            case 'top-right':
            case 'center-right':
            case 'bottom-right':
                $attrs_nav['class'][] = 'uk-flex-right';
                break;
            case 'bottom-center':
                $attrs_nav['class'][] = 'uk-flex-center';
                break;
        }
    }
}

// Breakpoint
$attrs_nav_container['class'][] = $element['nav_breakpoint'] ? "uk-visible@{$element['nav_breakpoint']}" : '';

?>

<?php if (!$element['nav_below']) : ?>
<div<?= $this->attrs($attrs_nav_container) ?>>
<?php endif ?>

<ul<?= $this->attrs($attrs_nav, $element['nav_below'] ? $attrs_nav_container : []) ?>>
    <?php foreach ($element as $i => $item) :

        // Display
        if (!$element['show_title']) { $item['title'] = ''; }
        if (!$element['show_thumbnail']) { $item['thumbnail'] = ''; }

        // Image
        $thumbnail = '';
        $src = $item['thumbnail'] ? $item['thumbnail'] : $item['image'];

        if ($element['nav'] == 'thumbnav' && $src) {

            $attrs_thumbnail['alt'] = $item['image_alt'];

            if ($this->isImage($src) == 'svg') {
                $thumbnail = $this->image($src, array_merge($attrs_thumbnail, ['width' => $element['thumbnav_width'], 'height' => $element['thumbnav_height']]));
            } elseif ($element['thumbnav_width'] || $element['thumbnav_height']) {
                $thumbnail = $this->image([$src, 'thumbnail' => [$element['thumbnav_width'], $element['thumbnav_height']], 'sizes' => '80%,200%'], $attrs_thumbnail);
            } else {
                $thumbnail = $this->image($src, $attrs_thumbnail);
            }

        }

    ?>
    <li uk-slideshow-item="<?= $i ?>">
        <a href="#"><?= $thumbnail ? $thumbnail : $item['title'] ?></a>
    </li>
    <?php endforeach ?>
</ul>

<?php if (!$element['nav_below']) : ?>
</div>
<?php endif ?>
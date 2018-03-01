<?php

$id    = $element['id'];
$class = $element['class'];
$attrs = $element['attrs'];
$attrs_cell = [];

// Grid
if ($element['grid_mode'] == 'parallax') {
    $attrs['uk-grid-parallax'] = "translate: {$element['grid_parallax_y']}";
} else {
    $attrs['uk-grid'] = true;
}

$class[] = "uk-child-width-1-{$element['grid_default']}";

$class[] = $element['grid_small'] ? "uk-child-width-1-{$element['grid_small']}@s" : '';
$class[] = $element['grid_medium'] ? "uk-child-width-1-{$element['grid_medium']}@m" : '';
$class[] = $element['grid_large'] ? "uk-child-width-1-{$element['grid_large']}@l" : '';
$class[] = $element['grid_xlarge'] ? "uk-child-width-1-{$element['grid_xlarge']}@xl" : '';

$class[] = $element['gutter'] ? "uk-grid-{$element['gutter']}" : '';
$class[] = $element['divider'] ? 'uk-grid-divider' : '';

// Lightbox
$attrs['uk-lightbox'] = $element['lightbox'] ? 'toggle: a[data-type]' : false;

// Orientation
$attrs_cell['class'][] = $element['image_orientation'] ? 'uk-flex uk-flex-center uk-flex-middle' : '';

?>

<div<?= $this->attrs(compact('id', 'class'), $attrs) ?>>

    <?php foreach ($element as $item) : ?>
    <div<?= $this->attrs($attrs_cell) ?>><?= $this->render('@builder/gallery/template-item', compact('item')) ?></div>
    <?php endforeach ?>

</div>

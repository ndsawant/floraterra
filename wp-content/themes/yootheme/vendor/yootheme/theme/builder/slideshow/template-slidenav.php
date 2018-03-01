<?php

$attrs_slidenav = [];
$attrs_slidenav_next = [];
$attrs_slidenav_previous = [];
$attrs_slidenav_container = [];

$attrs_slidenav['class'][] = 'el-slidenav';

$attrs_slidenav_previous['uk-slidenav-previous'] = true;
$attrs_slidenav_next['uk-slidenav-next'] = true;

$attrs_slidenav_previous['uk-slideshow-item'] = 'previous';
$attrs_slidenav_next['uk-slideshow-item'] = 'next';

// Position + Margin
if ($element['slidenav'] == 'default') {

    $attrs_slidenav_previous['class'][] = 'uk-position-center-left';
    $attrs_slidenav_next['class'][] = 'uk-position-center-right';
    $attrs_slidenav['class'][] = $element['slidenav_margin'] ? "uk-position-{$element['slidenav_margin']}" : '';


} else {

    $attrs_slidenav_container['class'][] = 'uk-slidenav-container';
    $attrs_slidenav_container['class'][] = "uk-position-{$element['slidenav']}";
    $attrs_slidenav_container['class'][] = "uk-position-{$element['slidenav_margin']}";

}

// Hover
$attrs_slidenav_container['class'][] = $element['slidenav_hover'] ? 'uk-hidden-hover uk-hidden-touch' : '';

// Large
$attrs_slidenav['class'][] = $element['slidenav_large'] ? 'uk-slidenav-large' : '';

// Text Color
$attrs_slidenav_container['class'][] = $element['text_color'] ? "uk-{$element['text_color']}" : '';

// Breakpoint
$attrs_slidenav_container['class'][] = $element['slidenav_breakpoint'] ? "uk-visible@{$element['slidenav_breakpoint']}" : '';

?>

<div<?= $this->attrs($attrs_slidenav_container) ?>>
    <a <?= $this->attrs($attrs_slidenav, $attrs_slidenav_previous) ?>></a>
    <a <?= $this->attrs($attrs_slidenav, $attrs_slidenav_next) ?>></a>
</div>
<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <body>
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

$site = $theme->get('site', []);

// Page
$attrs_page = [];
$attrs_page_container = [];

$attrs_page['class'][] = 'tm-page';

if ($site['layout'] == 'boxed') {

    $attrs_page['class'][] = $site['boxed.alignment'] ? 'uk-margin-auto' : '';

    $attrs_page_container['class'][] = 'tm-page-container';
    $attrs_page_container['class'][] = $site['boxed.padding'] ? 'tm-page-container-padding' : '';
    $attrs_page_container['style'][] = $site['boxed.media'] ? "background-image: url('{$site['boxed.media']}');" : '';

}

?>
<!DOCTYPE html>
<html <?php language_attributes() ?>>
    <head>
        <meta charset="<?php bloginfo('charset') ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="<?= $theme->get('favicon') ?>">
        <link rel="apple-touch-icon-precomposed" href="<?= $theme->get('touchicon') ?>">
        <?php if (is_singular() && pings_open(get_queried_object())) : ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url') ?>">
        <?php endif ?>
        <?php wp_head() ?>
    </head>
    <body <?php body_class($theme->get('body_class')->all()) ?>>

        <?php if (strpos($theme->get('header.layout'), 'offcanvas') === 0 || $theme->get('mobile.animation') == 'offcanvas') : ?>
        <div class="uk-offcanvas-content">
        <?php endif ?>

        <?php if ($site['layout'] == 'boxed') : ?>
        <div<?= get_attrs($attrs_page_container) ?>>
        <?php endif ?>

        <div<?= get_attrs($attrs_page) ?>>

            <div class="tm-header-mobile uk-hidden@<?= $theme->get('mobile.breakpoint') ?>">
            <?= get_view('header-mobile') ?>
            </div>

            <?php if (is_active_sidebar('toolbar-left') || is_active_sidebar('toolbar-right')) : ?>
            <div class="tm-toolbar uk-visible@<?= $theme->get('mobile.breakpoint') ?>">
                <div class="uk-container uk-flex uk-flex-middle <?= $site['toolbar_fullwidth'] ? 'uk-container-expand' : '' ?> <?= $site['toolbar_center'] ? 'uk-flex-center' : '' ?>">

                    <?php if (is_active_sidebar('toolbar-left') || ($site['toolbar_center'] && is_active_sidebar('toolbar-right'))) : ?>
                    <div>
                        <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid="margin: uk-margin-small-top">

                            <?php if (is_active_sidebar('toolbar-left')) : ?>
                            <?php dynamic_sidebar("toolbar-left:cell") ?>
                            <?php endif ?>

                            <?php if ($site['toolbar_center'] && is_active_sidebar('toolbar-right')) : ?>
                            <?php dynamic_sidebar("toolbar-right:cell") ?>
                            <?php endif ?>

                        </div>
                    </div>
                    <?php endif ?>

                    <?php if (!$site['toolbar_center'] && is_active_sidebar('toolbar-right')) : ?>
                    <div class="uk-margin-auto-left">
                        <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid="margin: uk-margin-small-top">
                            <?php dynamic_sidebar("toolbar-right:cell") ?>
                        </div>
                    </div>
                    <?php endif ?>

                </div>
            </div>
            <?php endif ?>

            <?= get_view('header') ?>

            <?php dynamic_sidebar("top:section") ?>

            <?php if (!$theme->get('builder')) : ?>

            <div id="tm-main" class="tm-main uk-section uk-section-default" uk-height-viewport="expand: true">
                <div class="uk-container">

                    <?php
                        $grid = ['uk-grid']; $sidebar = $theme->get('sidebar', []);
                        $grid[] = $sidebar['gutter'] ? "uk-grid-{$sidebar['gutter']}" : '';
                        $grid[] = $sidebar['divider'] ? "uk-grid-divider" : '';
                    ?>

                    <div<?= get_attrs(['class' => $grid, 'uk-grid' => true]) ?>>
                        <div class="uk-width-expand@<?= $theme->get('sidebar.breakpoint') ?>">

                            <?php if ($site['breadcrumbs']) : ?>
                            <div class="uk-margin-medium-bottom">
                                <?= get_section('breadcrumbs') ?>
                            </div>
                            <?php endif ?>

            <?php endif ?>

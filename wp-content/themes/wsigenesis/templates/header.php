<?php

// Options
$layout = $theme->get('header.layout');
$fullwidth = $theme->get('header.fullwidth');
$logo_padding_remove = $theme->get('header.logo_padding_remove');
$logo = $theme->get('logo.image') || $theme->get('logo.text');
$class = array_merge(['tm-header', 'uk-visible@' . $theme->get('mobile.breakpoint')], isset($class) ? (array) $class : []);
$attrs = array_merge(['uk-header' => true], isset($attrs) ? (array) $attrs : []);

// Container
$container = ['class' => ['uk-navbar-container']];

// Navbar
$navbar = $theme->get('navbar', []);

// Dropdown options
if (!(strpos($layout, 'offcanvas') === 0 || strpos($layout, 'modal') === 0)) {

    $attrs_navbar = [
        'class' => 'uk-navbar',
        'uk-navbar' => json_encode(array_filter([
            'align' => $navbar['dropdown_align'],
            'click' => $navbar['dropdown_click'],
            'boundary-align' => $navbar['dropdown_boundary'],
            'dropbar' => $navbar['dropbar'] ? true : null,
            'dropbar-anchor' => $navbar['dropbar'] ? '!.uk-container' : null,
            'dropbar-mode' => $navbar['dropbar']
        ]))
    ];

} else {

    $attrs_navbar = [
        'class' => 'uk-navbar',
        'uk-navbar' => true
    ];

}

// Sticky
if ($sticky = $navbar['sticky']) {
    $container = array_merge($container, array_filter([
        'uk-sticky' => true,
        'media' => 768,
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-active uk-navbar-sticky',
    ]));
}

?>

<div<?= $this->attrs(['class' => $class], $attrs) ?>>

<?php

/*
 * Horizontal layouts
 */

if (in_array($layout, ['horizontal-left', 'horizontal-center', 'horizontal-right'])) : ?>
    <div<?= $this->attrs($container) ?>>

        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?><?= $logo && $logo_padding_remove ? ' uk-padding-remove-left' : '' ?>">
            <nav<?= $this->attrs($attrs_navbar) ?>>

                <?php if ($logo || $layout == 'horizontal-left' && is_active_sidebar('navbar')) : ?>
                <div class="uk-navbar-left">

                    <?= $logo ? $this->render('header-logo', ['class' => 'uk-navbar-item', 'img' => 'uk-responsive-height']) : '' ?>

                    <?php if ($layout == 'horizontal-left') : ?>
                        <?php dynamic_sidebar("navbar") ?>
                    <?php endif ?>

                </div>
                <?php endif ?>

                <?php if ($layout == 'horizontal-center' && is_active_sidebar('navbar')) : ?>
                <div class="uk-navbar-center">
                    <?php dynamic_sidebar("navbar") ?>
                </div>
                <?php endif ?>

                <?php if (is_active_sidebar('header') || $layout == 'horizontal-right' && is_active_sidebar('navbar')) : ?>
                <div class="uk-navbar-right uk-flex-right">
                    <div class="tm-headerbar">
                    <?php dynamic_sidebar("header") ?>
                    </div>
					<div class="tm-navbar uk-flex">
                    <?php if ($layout == 'horizontal-right' && is_active_sidebar('navbar')) : ?>
                        <?php dynamic_sidebar("navbar") ?>
                    <?php endif ?>
					</div>
                </div>
                <?php endif ?>

            </nav>
        </div>

    </div>
<?php endif ?>

<?php

/*
 * Stacked Center layouts
 */

if (in_array($layout, ['stacked-center-a', 'stacked-center-b', 'stacked-center-split'])) : ?>

    <?php if ($logo && $layout != 'stacked-center-split' || $layout == 'stacked-center-a' && is_active_sidebar('header')) : ?>
    <div class="tm-headerbar-top">
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?>">

            <?php if ($logo) : ?>
            <div class="uk-text-center">
                <?= $this->render('header-logo') ?>
            </div>
            <?php endif ?>

            <?php if ($layout == 'stacked-center-a' && is_active_sidebar('header')) : ?>
            <div class="tm-headerbar-stacked uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <?php dynamic_sidebar("header:cell") ?>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if (is_active_sidebar('navbar')) : ?>
    <div<?= $this->attrs($container) ?>>

        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?>">
            <nav<?= $this->attrs($attrs_navbar) ?>>

                <div class="uk-navbar-center">

                    <?php if ($layout == 'stacked-center-split') : ?>

                        <div class="uk-navbar-center-left"><div>
                            <?php dynamic_sidebar("navbar-split") ?>
                        </div></div>

                        <?= $this->render('header-logo', ['class' => 'uk-navbar-item', 'img' => 'uk-responsive-height']); ?>

                        <div class="uk-navbar-center-right"><div>
                            <?php dynamic_sidebar("navbar") ?>
                        </div></div>

                    <?php else: ?>
                        <?php dynamic_sidebar("navbar") ?>
                    <?php endif ?>

                </div>

            </nav>
        </div>

    </div>
    <?php endif ?>

    <?php if (in_array($layout, ['stacked-center-b', 'stacked-center-split']) && is_active_sidebar('header')) : ?>
    <div class="tm-headerbar-bottom">
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?>">
            <div class="uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <?php dynamic_sidebar("header:cell") ?>
            </div>
        </div>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

/*
 * Stacked Left layouts
 */

if ($layout == 'stacked-left-a' || $layout == 'stacked-left-b') : ?>

    <?php if ($logo || is_active_sidebar('header')) : ?>
    <div class="tm-headerbar-top">
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?><?= $logo && $logo_padding_remove ? ' uk-padding-remove-left' : '' ?> uk-flex uk-flex-middle">

            <?= $logo ? $this->render('header-logo') : '' ?>

            <?php if (is_active_sidebar('header')) : ?>
            <div class="uk-margin-auto-left">
                <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid>
                    <?php dynamic_sidebar("header:cell") ?>
                </div>
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if (is_active_sidebar('navbar')) : ?>
    <div<?= $this->attrs($container) ?>>

        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?>">
            <nav<?= $this->attrs($attrs_navbar) ?>>

                <?php if ($layout == 'stacked-left-a') : ?>
                <div class="uk-navbar-left">
                    <?php dynamic_sidebar("navbar") ?>
                </div>
                <?php endif ?>

                <?php if ($layout == 'stacked-left-b') : ?>
                <div class="uk-navbar-left uk-flex-auto">
                    <?php dynamic_sidebar("navbar") ?>
                </div>
                <?php endif ?>

            </nav>
        </div>

    </div>
    <?php endif ?>

<?php endif ?>

<?php

/*
 * Toggle layouts
 */

if (strpos($layout, 'offcanvas') === 0 || strpos($layout, 'modal') === 0) :

    $attrs_toggle = [];

    $attrs_toggle['class'][] = strpos($layout, 'modal') === 0 ? 'uk-modal-body uk-padding-large uk-margin-auto' : 'uk-offcanvas-bar';
    $attrs_toggle['class'][] = $navbar['toggle_menu_center'] ? 'uk-text-center' : '';
    $attrs_toggle['class'][] = 'uk-flex uk-flex-column';

    ?>

    <div<?= $this->attrs($container) ?>>
        <div class="uk-container<?= $fullwidth ? ' uk-container-expand' : '' ?><?= $logo && $logo_padding_remove ? ' uk-padding-remove-left' : '' ?>">
            <nav<?= $this->attrs($attrs_navbar) ?>>

                <?php if ($logo) : ?>
                <div class="uk-navbar-left">
                    <?= $this->render('header-logo', ['class' => 'uk-navbar-item', 'img' => 'uk-responsive-height']) ?>
                </div>
                <?php endif ?>

                <?php if (is_active_sidebar('header') || is_active_sidebar('navbar')) : ?>
                <div class="uk-navbar-right">

                    <?php dynamic_sidebar("header") ?>

                    <?php if (is_active_sidebar('navbar')) : ?>
                    <a class="uk-navbar-toggle" href="#" uk-toggle="!.uk-navbar-container + [uk-offcanvas], [uk-modal]">
                        <?php if ($navbar['toggle_text']) : ?>
                        <span class="uk-margin-small-right"><?= __('Menu', 'yootheme') ?></span>
                        <?php endif ?>
                        <div uk-navbar-toggle-icon></div>
                    </a>
                    <?php endif ?>

                </div>
                <?php endif ?>

            </nav>
        </div>
    </div>

    <?php if (strpos($layout, 'offcanvas') === 0 && is_active_sidebar('navbar')) : ?>
    <div uk-offcanvas="flip: true"<?= $this->attrs($navbar['offcanvas'] ?: []) ?>>
        <div<?= $this->attrs($attrs_toggle) ?>>

            <button class="uk-offcanvas-close uk-close-large uk-margin-remove-adjacent" type="button" uk-close></button>

            <?php dynamic_sidebar("navbar") ?>

        </div>
    </div>
    <?php endif ?>

    <?php if (strpos($layout, 'modal') === 0 && is_active_sidebar('navbar')) : ?>
    <div class="uk-modal-full" uk-modal>
        <div class="uk-modal-dialog uk-flex">

            <button class="uk-modal-close-full uk-close-large uk-margin-remove-adjacent" type="button" uk-close></button>

            <div <?= $this->attrs($attrs_toggle) ?> uk-height-viewport>
                <?php dynamic_sidebar("navbar") ?>
            </div>

        </div>
    </div>
    <?php endif ?>

<?php endif ?>

</div>
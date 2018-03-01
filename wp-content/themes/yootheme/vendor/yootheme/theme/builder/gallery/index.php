<?php

return [

    'name' => 'yootheme/builder-gallery',

    'builder' => 'gallery',

    'inject' => [

        'view' => 'app.view',

    ],

    'render' => function ($element) {

        // Deprecated
        if ($element['show_hover_image'] === null) {
            $element['show_hover_image'] = $element['show_image2'];
        }

        foreach ($element as $child) {

            // Deprecated
            if ($child['hover_image'] === null) {
                $child['hover_image'] = $child['image2'];
            }

            if (empty($child['image']) && empty($child['hover_image'])) {
                $child['image'] = $this->app->url('@assets/images/element-image-placeholder.png');
            }
        }

        return $this->view->render('@builder/gallery/template', compact('element'));
    },

    'config' => [

        'element' => true,
        'defaults' => [

            'show_title' => true,
            'show_meta' => true,
            'show_content' => true,
            'show_link' => true,
            'show_hover_image' => true,

            'grid_default' => '1',
            'grid_medium' => '3',

            'overlay_mode' => 'cover',
            'overlay_hover' => true,
            'overlay_style' => 'overlay-primary',
            'text_color' => 'light',
            'overlay_position' => 'center',
            'overlay_transition' => 'fade',

            'title_element' => 'h3',
            'meta_style' => 'meta',
            'meta_align' => 'bottom',

            'text_align' => 'center',
            'margin' => 'default',

        ],

    ],

    'default' => [

        'children' => array_fill(0, 3, [
            'type' => 'gallery_item',
        ])

    ],

    'include' => [

        'yootheme/builder-gallery-item' => [

            'builder' => 'gallery_item',
            'default' => [

                'props' => [
                    'title' => 'Overlay',
                ],

            ],

        ],

    ],

];

<?php

$config = [

    'name' => 'yootheme/builder-wordpress-area',

    'builder' => 'wordpress_area',

    'inject' => [

        'view' => 'app.view',
        'scripts' => 'app.scripts',

    ],

    'render' => function ($element) {
        return $element['content'] && is_active_sidebar((string) $element['content'])
            ? $this->view->render('@builder/wordpress-area/template', compact('element'))
            : '';
    },

    'events' => [

        'theme.admin' => function () {
            $this->scripts->add('builder-wordpress-area', '@builder/wordpress-area/app/wordpress-area.min.js', 'customizer-builder');
        }

    ],

    'config' => [

        'element' => true,
        'defaults' => [

            'layout' => 'stack',
            'breakpoint' => 'm',

        ],

    ],

];

return defined('WPINC') ? $config : false;

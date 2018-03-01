<?php

namespace YOOtheme\Theme\Wordpress;

use YOOtheme\EventSubscriber;

class UpgradeListener extends EventSubscriber
{
    public $inject = [
        'admin' => 'app.admin',
        'update' => 'app.update',
    ];

    public function onInit($theme)
    {
        if (!$this->admin) {
            return;
        }

        $this->update->register(basename($this->theme->path), 'theme', $this->theme->options['update'], ['key' => $this->theme->get('yootheme_apikey')]);


        add_filter('upgrader_pre_install', function ($return, $package) {

            if (!is_wp_error($return)) {
                $this->move($package);
            }

            return $return;

        }, 10, 2);

        add_filter('upgrader_post_install', function ($return, $package) {

            if (!is_wp_error($return)) {
                $this->move($package, true);
            }

            return $return;

        }, 10, 2);

        // Check child theme's "theme.js" for jQuery
        if (is_child_theme()
            AND $theme->get('jquery') === null
            AND $contents = @file_get_contents(get_stylesheet_directory().'/js/theme.js')
            AND strpos($contents, 'jQuery') !== false
        ) {
            $theme->set('jquery', true);
            set_theme_mod('config', json_encode($theme->config));
        }

    }

    public function move($package, $reverse = false)
    {
        global $wp_filesystem;

        $name = isset($package['theme']) ? $package['theme'] : '';
        $content = $wp_filesystem->wp_content_dir();

        if ($name != basename($this->theme->path)) {
            return;
        }

        $paths = ["{$this->theme->path}/css", "{$content}/upgrade"];

        list($source, $target) = $reverse ? array_reverse($paths) : $paths;

        foreach (glob("{$source}/theme*.css") as $file) {

            $filename = basename($file);

            if (strpos($file, 'update.css')) {
                continue;
            }

            if ($wp_filesystem->exists($file)) {
                $wp_filesystem->move($file, "{$target}/{$filename}", true);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.init' => ['onInit', -10]
        ];
    }
}

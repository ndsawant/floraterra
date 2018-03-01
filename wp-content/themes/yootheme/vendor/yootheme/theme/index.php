<?php

const REGEX_IMAGE = '#\.(gif|png|jpe?g|svg)$#';
const REGEX_VIDEO = '#\.(mp4|ogv|webm)$#';
const REGEX_VIMEO = '#(?:player\.)?vimeo\.com(?:/video)?/(\d+)#i';
const REGEX_YOUTUBE = '#(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})#i';

return [

    'name' => 'yootheme/theme',

    'main' => function ($app) {

        $app['locator']
            ->addPath("{$this->path}/builder", 'builder')
            ->addPath("{$this->path}/assets", 'assets')
            ->addPath("{$this->path}/platforms", 'assets/platforms');
    },

    'require' => 'yootheme/framework',

    'include' => [

        'modules/*/index.php',
        'platforms/*/index.php',

    ],

    'inject' => [

        'view' => 'app.view',
        'assets' => 'app.assets',
        'scripts' => 'app.scripts',
        'modules' => 'app.modules',
        'translator' => 'app.translator',
        'customizer' => 'theme.customizer',

    ],

    'events' => [

        'theme.init' => function () {

            $this->assets->setVersion($this->theme->options['version']);
            $this->scripts->register('vue', "{$this->path}/app/vue.min.js", 'config');
            $this->scripts->register('uikit', 'vendor/assets/uikit/dist/js/uikit.min.js');
            $this->scripts->register('uikit-icons', 'vendor/assets/uikit/dist/js/uikit-icons.min.js', '~uikit');

        },

        'theme.site' => function () {

            $this->view->addFunction('social', function ($link) {

                static $icons;

                if (is_null($icons)) {
                    $icons = json_decode(file_get_contents("{$this->path}/app/data/icons.json"), true);
                    $icons = $icons['Brand Icons'];
                }

                if (strpos($link, 'mailto:') === 0) {
                    return 'mail';
                }

                if (strpos($link, 'tel:') === 0) {
                    return 'receiver';
                }

                if (preg_match('#google\.(.+?)/maps/(.+)#i', $link)) {
                    return 'location';
                }

                $icon = parse_url($link, PHP_URL_HOST);
                $icon = preg_replace('/.*?(plus\.google|[^\.]+)\.[^\.]+$/i', '$1', $icon);
                $icon = str_replace('plus.google', 'google-plus', $icon);

                if (!in_array($icon, $icons)) {
                    $icon = 'social';
                }

                return $icon;
            });

            $this->view->addFunction('iframeVideo', function ($link, $params = []) {

                $query = parse_url($link, PHP_URL_QUERY);

                if ($query) {
                    parse_str($query, $_params);
                    $params = array_merge($_params, $params);
                }

                if (preg_match(REGEX_VIMEO, $link, $matches)) {
                    return $this->app->url("https://player.vimeo.com/video/{$matches[1]}", array_merge([
                        'loop' => 1, 'autoplay' => 1, 'title' => 0, 'byline' => 0, 'setVolume' => 0
                    ], $params));
                }

                if (preg_match(REGEX_YOUTUBE, $link, $matches)) {

                    if (!empty($params['loop'])) {
                        $params['playlist'] = $matches[1];
                    }

                    return $this->app->url("https://www.youtube.com/embed/{$matches[1]}", array_merge([
                        'rel' => 0, 'loop' => 1, 'autoplay' => 1, 'controls' => 0, 'showinfo' => 0, 'modestbranding' => 1, 'wmode' => 'transparent', 'playsinline' => 1
                    ], $params));
                }

            });

            $this->view->addFunction('isImage', function ($link) {

                return $link && preg_match(REGEX_IMAGE, $link, $matches) ? $matches[1] : false;

            });

            $this->view->addFunction('isVideo', function ($link) {

                return $link && preg_match(REGEX_VIDEO, $link, $matches) ? $matches[1] : false;

            });

            if ($this->customizer->isActive()) {
                $this->scripts->add('preventAutofocus', "{$this->path}/assets/js/preventAutofocus.js");
            }
        },

        'theme.admin' => [function () {

            $this->customizer->mergeData([
                'name' => $this->theme->name,
                'base' => $this->app->url($this->theme->path),
                'api' => 'https://yootheme.com/api',
            ]);

            foreach ($this->modules->all() as $module) {

                if ($section = $module->config->get('section')) {

                    if ($fields = $module->config->get('fields')) {
                        $section['fields'] = $fields;
                    }

                    $this->customizer->addSection(basename($module->name), $section);
                }

                if ($panels = $module->config->get('panels')) {
                    $this->customizer->addData('panels', $panels);
                }
            }

            $this->translator->addResource("{$this->path}/languages/{locale}.json");

        }, -10]

    ],

];

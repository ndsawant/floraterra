<?php

$config = array(

    'name' => 'framework/wordpress',

    'main' => 'YOOtheme\\Widgetkit\\Framework\\Wordpress\\WordpressPlugin',

    'autoload' => array(

        'YOOtheme\\Widgetkit\\Framework\\Wordpress\\' => 'src'

    )

);

return defined('WPINC') ? $config : false;

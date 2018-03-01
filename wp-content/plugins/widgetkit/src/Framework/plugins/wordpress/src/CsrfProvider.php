<?php

namespace YOOtheme\Widgetkit\Framework\Wordpress;

use YOOtheme\Widgetkit\Framework\Csrf\DefaultCsrfProvider;

class CsrfProvider extends DefaultCsrfProvider
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return wp_create_nonce();
    }
}

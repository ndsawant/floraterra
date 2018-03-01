<?php

namespace YOOtheme\Widgetkit\Framework\Wordpress;

use YOOtheme\Widgetkit\Framework\Filter\FilterInterface;

class ContentFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return apply_filters('the_content', $value);
    }
}

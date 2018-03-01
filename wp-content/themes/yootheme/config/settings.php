<?php

return [

    'fields' => [

        'settings' => [
            'type' => 'menu',
            'items' => [
                'favicon' => 'Favicon',
                'system' => 'System',
                'advanced' => 'Advanced',
                'custom-code' => 'Custom Code',
                'about' => 'About',
                //'test' => 'Fields Test',
            ],
        ],

    ],

    'panels' => [

        'favicon' => [
            'title' => 'Favicon',
            'width' => 400,
            'fields' => [

                'favicon' => [
                    'label' => 'Favicon',
                    'description' => 'Upload your browser URL icon. It\'s recommended to apply a size of 96x96 pixels to the favicon.png.',
                    'type' => 'image',
                ],

                'touchicon' => [
                    'label' => 'Touch Icon',
                    'description' => 'Upload your iOS icon. It\'s recommended to apply a size of 180x180 pixels to the apple-touch-icon.png.',
                    'type' => 'image',
                ],

            ],
        ],

        'advanced' => [
            'title' => 'Advanced',
            'width' => 400,
            'fields' => [

                'compression' => [
                    'label' => 'Compression',
                    'description' => 'Compile CSS and JavaScript into a single file each to reduce file requests.',
                    'type' => 'select',
                    'default' => 0,
                    'options' => [
                        'None' => 0,
                        'Combine' => 1,
                    ],
                ],

                'jquery' => [
                    'label' => 'jQuery',
                    'description' => 'Enable this option to write custom code based on the jQuery JavaScript library.',
                    'text' => 'Load jQuery',
                    'type' => 'checkbox'
                ],

                'highlight' => [
                    'label' => 'Syntax Highlighting',
                    'description' => 'Select the style for the code syntax highlighting. Use GitHub for light and Monokai for dark backgrounds.',
                    'type' => 'select',
                    'default' => '',
                    'options' => [
                        'None' => '',
                        'GitHub (Light)' => 'github',
                        'Monokai (Dark)' => 'monokai',
                    ],
                ],

                'google_maps' => [
                    'label' => 'Google Maps',
                    'description' => 'Enter your <a href="https://developers.google.com/maps/web/" target="_blank">Google Maps</a> API key to use Google Maps instead of OpenStreetMap. It also enables additional options to style the colors of your maps.'
                ],

                'google_analytics' => [
                    'label' => 'Google Analytics',
                    'attrs' => [
                        'placeholder' => 'UA-XXXXXXX-X',
                    ],
                ],

                'google_analytics_anonymize' => [
                    'description' => 'Enter your <a href="https://developers.google.com/analytics/" target="_blank">Google Analytics</a> ID to enable tracking. <a href="https://support.google.com/analytics/answer/2763052" target="_blank">IP anonymization</a> may reduce tracking accuracy.',
                    'type' => 'checkbox',
                    'text' => 'IP Anonymization',
                ],

                'mailchimp_api' => [
                    'label' => 'Mailchimp API Token',
                    'description' => 'Enter your <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys" target="_blank">Mailchimp</a> API key for using it with the Newsletter element.',
                ],

                'cmonitor_api' => [
                    'label' => 'Campaign Monitor API Token',
                    'description' => 'Enter your <a href="https://help.campaignmonitor.com/topic.aspx?t=206" target="_blank">Campaign Monitor</a> API key for using it with the Newsletter element.',
                ],

                'clear_cache' => [
                    'type' => 'cache',
                    'label' => 'Cache',
                    'description' => 'Clear cached images and assets. Images that need to be resized are stored in the theme\'s cache folder. After reuploading an image with the same name, you\'ll have to clear the cache.',
                ],

            ],
        ],

        'custom-code' => [
            'title' => 'Custom Code',
            'width' => 600,
            'fields' => [

                'custom_js' => [
                    'label' => 'Script',
                    'description' => 'Add custom JavaScript to your site. The &lt;script&gt; tag is not needed.',
                    'type' => 'editor',
                    'editor' => 'code',
                    'mode' => 'javascript',
                    'lazy' => true,
                ],

                'custom_less' => [
                    'label' => 'CSS/LESS',
                    'description' => 'Add custom CSS or LESS to your site. All LESS theme variables and mixins are available. The &lt;style&gt; tag is not needed.',
                    'type' => 'editor',
                    'editor' => 'code',
                    'mode' => 'css',
                    'attrs' => [
                        'id' => 'custom_less',
                        'debounce' => 1000
                    ],
                ],

            ],
        ],

        'about' => [
            'title' => 'About',
            'width' => 400,
        ],

        'test' => [
            'title' => 'Fields Test',
            'width' => 400,
            'fields' => [

                'test.group_text' => [
                    'label' => 'Text',
                    'attrs' => [
                        'placeholder' => 'Placeholder',
                    ],
                ],

                'test.group_select' => [
                    'type' => 'select',
                    'default' => 0,
                    'options' => [
                        'Option 1' => 0,
                        'Option 2' => 1,
                        'Option 3' => 2,
                    ],
                ],

                'test.group_checkbox' => [
                    'type' => 'checkbox',
                    'text' => 'Checkbox',
                ],

                'test.group_checkbox2' => [
                    'type' => 'checkbox',
                    'text' => 'Checkbox',
                ],

                'test.group_radio' => [
                    'type' => 'radio',
                    'options' => [
                        'Option 1' => 0,
                        'Option 2' => 1,
                        'Option 3' => 2,
                    ],
                ],

                'test.group_textarea' => [
                    'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor.',
                    'type' => 'textarea',
                    'attrs' => [
                        'rows' => 5,
                        'placeholder' => 'Placeholder',
                    ],
                ],

                'test.text' => [
                    'label' => 'Text',
                    'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor.',
                    'attrs' => [
                        'placeholder' => 'Placeholder',
                    ],
                ],

                'test.select' => [
                    'label' => 'Select',
                    'type' => 'select',
                    'default' => 0,
                    'options' => [
                        'Option 1' => 0,
                        'Option 2' => 1,
                        'Option 3' => 2,
                    ],
                ],

                'test.select-custom' => [
                    'label' => 'Select Custom',
                    'type' => 'select-custom',
                    'default' => 0,
                    'options' => [
                        'Option 1' => 0,
                        'Option 2' => 1,
                        'Option 3' => 2,
                    ],
                ],

                'test.checkbox' => [
                    'label' => 'Checkbox',
                    'type' => 'checkbox',
                    'text' => 'Checkbox',
                ],

                'test.show' => [
                    'label' => 'Checkbox',
                    'type' => 'checkbox',
                    'text' => 'This is shown only if above checkbox is false',
                    'show' => '!test.checkbox',
                ],

                'test.radio' => [
                    'label' => 'Radio',
                    'type' => 'radio',
                    'options' => [
                        'Option 1' => 0,
                        'Option 2' => 1,
                        'Option 3' => 2,
                    ],
                ],

                'test.range' => [
                    'label' => 'Range',
                    'type' => 'range',
                    'attrs' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],

                'test.textarea' => [
                    'label' => 'Textarea',
                    'type' => 'textarea',
                    'attrs' => [
                        'rows' => 5,
                        'placeholder' => 'Placeholder',
                    ],
                ],

                'test.select-icon' => [
                    'label' => 'Select Icon',
                    'type' => 'select-icon',
                    'default' => '',
                    'options' => [
                        '' => [
                            'label' => 'Always',
                            'icon' => 'phone',
                        ],
                        's' => [
                            'label' => 'Small (Phone Landscape)',
                            'icon' => 'phone-landscape',
                        ],
                        'm' => [
                            'label' => 'Medium (Tablet Landscape)',
                            'icon' => 'tablet-landscape',
                        ],
                        'l' => [
                            'label' => 'Large (Desktop)',
                            'icon' => 'laptop',
                        ],
                        'xl' => [
                            'label' => 'X-Large (Large Screens)',
                            'icon' => 'desktop',
                        ],
                    ],
                ],

                'test.editor' => [
                    'label' => 'Editor',
                    'type' => 'editor',
                ],

                'test.code' => [
                    'label' => 'Editor Code',
                    'type' => 'editor',
                    'editor' => 'code',
                    'mode' => 'js',
                ],

                'test.image' => [
                    'label' => 'Image',
                    'type' => 'image',
                ],

                'test.video' => [
                    'label' => 'Video',
                    'type' => 'video',
                ],

                'test.color' => [
                    'label' => 'Color',
                    'type' => 'color'
                ],

                'test.font' => [
                    'label' => 'Font',
                    'type' => 'font',
                ],

                'test.icon' => [
                    'label' => 'Icon',
                    'type' => 'icon',
                ],

                'test.grid' => [

                    'type' => 'grid',
                    'fields' => [

                        'test.grid.text1' => [
                            'label' => 'Text 1',
                            'width' => '1-2',
                        ],

                        'test.grid.text2' => [
                            'label' => 'Text 2',
                            'width' => '1-2',
                        ],

                    ],

                ],

            ],
        ],

    ],

];

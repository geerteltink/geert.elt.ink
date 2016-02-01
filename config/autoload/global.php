<?php

return [
    'debug' => false,

    'config_cache_enabled' => true,

    'twig' => [
        'globals' => [
            'recaptcha_pub_key'  => '',
            'recaptcha_priv_key' => '',
        ],
    ],

    'mail' => [
        'transport' => [
            'debug'   => false,
            'class'   => 'sendmail',
            'options' => [],
        ],
        'to'        => '',
    ],
];

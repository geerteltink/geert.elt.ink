<?php

return [
    'debug' => false,

    'config_cache_enabled' => true,

    'twig' => [
        'globals' => [
            'recaptcha_pub_key'  => '',
            'recaptcha_priv_key' => '',
            //'ga_tracking'      => '',
        ],
    ],

    'mail' => [
        'transport' => [
            'debug'   => false,
            'class'   => 'sendmail',
            'options' => [],
        ],
        'to'        => '', // Email address to send the contact emails to
        'from'      => '', // Email address to send the contact emails from
    ],
];

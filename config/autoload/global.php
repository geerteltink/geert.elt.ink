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
            'class'   => Zend\Mail\Transport\SendMail::class,
            'options' => [],
        ],
        'to'        => '', // Email address to send the contact emails to
        'from'      => '', // Email address to send the contact emails from
    ],

    'session' => [
        'psr7' => [
            'signature_key'    => '',
            'verification_key' => '',
            'cookie_name'      => 'slsession',
            'cookie_secure'    => false, // false on purpose, unless you have https locally
            'expiration_time'  => 1200, // 20 minutes
        ],
    ],
];

<?php

return [
    'debug' => true,

    'config_cache_enabled' => false,

    'twig' => [
        'globals' => [
            'recaptcha_pub_key'  => '',
            'recaptcha_priv_key' => '',
            //'ga_tracking'      => '',
        ],
    ],

    'mail' => [
        'transport' => [
            'debug'   => true,
            'class'   => Zend\Mail\Transport\InMemory::class,
            'options' => [],
        ],
        'to'        => 'me@example.com', // Email address to send the contact emails to
        'from'      => 'someone@example.com', // Email address to send the contact emails from
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

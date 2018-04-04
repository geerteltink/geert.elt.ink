<?php

declare(strict_types=1);

return [
    'recaptcha' => [
        'site_key'   => '',
        'secret_key' => '',
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
        'cookie_name'     => 'slsession',
        'cookie_secure'   => false, // false on purpose, unless you have https locally
        'expiration_time' => 1200, // 20 minutes
    ],
];

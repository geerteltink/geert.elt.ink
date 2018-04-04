<?php

declare(strict_types=1);

return [
    'templates' => [
        'extension' => 'html.twig',
        'paths'     => [
            'app'    => ['resources/templates/app'],
            'layout' => ['resources/templates/layout'],
            'error'  => ['resources/templates/error'],
        ],
    ],

    'twig' => [
        'cache_dir'      => 'data/cache/twig',
        'assets_url'     => '/',
        'assets_version' => '20180401',
        'extensions'     => [],
        'globals'        => [],
    ],
];

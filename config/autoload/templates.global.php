<?php

return [
    'dependencies' => [
        'factories' => [
            'Zend\Expressive\FinalHandler' =>
                Zend\Expressive\Container\TemplatedErrorHandlerFactory::class,

            Zend\Expressive\Template\TemplateRendererInterface::class =>
                Zend\Expressive\Twig\TwigRendererFactory::class,
        ],
    ],

    'templates' => [
        'cache_dir' => 'data/cache/twig',
        'assets_url' => '/',
        'assets_version' => '2015-11-08',
        'extension' => 'html.twig',
        'paths' => [
            'app'    => ['resources/templates/app'],
            'layout' => ['resources/templates/layout'],
            'error'  => ['resources/templates/error'],
        ]
    ]
];

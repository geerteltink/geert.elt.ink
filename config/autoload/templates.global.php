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
        'assets_version' => '20160312',
        'extensions'     => [],
        'globals'        => [],
    ],
];

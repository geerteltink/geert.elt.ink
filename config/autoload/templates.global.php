<?php

use Zend\Expressive\Container\TemplatedErrorHandlerFactory;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

return [
    'dependencies' => [
        'factories' => [
            'Zend\Expressive\FinalHandler'   => TemplatedErrorHandlerFactory::class,
            TemplateRendererInterface::class => TwigRendererFactory::class,
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
        'assets_version' => '20160319',
        'extensions'     => [],
        'globals'        => [],
    ],
];

<?php

declare(strict_types = 1);

use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;

return [
    'dependencies' => [
        'factories' => [
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

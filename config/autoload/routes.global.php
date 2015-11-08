<?php

use App\Action\ActionFactory;

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            App\Action\HomePageAction::class => ActionFactory::class,
            App\Action\BlogIndexAction::class => ActionFactory::class,
            App\Action\BlogPostAction::class => ActionFactory::class,
            App\Action\CodeAction::class => ActionFactory::class,
        ]
    ],

    'routes' => [
        [
            'name' => 'home',
            'path' => '/',
            'middleware' => App\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'blog',
            'path' => '/blog',
            'middleware' => App\Action\BlogIndexAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'blog.post',
            'path' => '/blog/{id:[0-9a-zA-z\-]+}',
            'middleware' => App\Action\BlogPostAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'code',
            'path' => '/code',
            'middleware' => App\Action\CodeAction::class,
            'allowed_methods' => ['GET'],
        ],
    ],
];

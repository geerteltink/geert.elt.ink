<?php

return [
    'dependencies' => [
        'invokables'         => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories'          => [
            App\Action\ContactAction::class => App\Action\ContactActionFactory::class,
        ],
        'abstract_factories' => [
            App\Action\AbstractActionFactory::class,
        ],
    ],

    'routes' => [
        [
            'name'            => 'home',
            'path'            => '/',
            'middleware'      => App\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'blog',
            'path'            => '/blog',
            'middleware'      => App\Action\BlogIndexAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'blog.post',
            'path'            => '/blog/{id:[0-9a-zA-z\-]+}',
            'middleware'      => App\Action\BlogPostAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'feed.xml',
            'path'            => '/blog/feed.xml',
            'middleware'      => App\Action\BlogXmlFeedAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'code',
            'path'            => '/code',
            'middleware'      => App\Action\CodeAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'contact',
            'path'            => '/contact',
            'middleware'      => App\Action\ContactAction::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
    ],
];

<?php

return [
    'dependencies' => [
        'invokables'         => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories'          => [
            App\Frontend\Action\ContactAction::class => App\Frontend\Action\ContactActionFactory::class,
        ],
        'abstract_factories' => [
            App\Frontend\Action\AbstractActionFactory::class,
        ],
    ],

    'routes' => [
        [
            'name'            => 'home',
            'path'            => '/',
            'middleware'      => App\Frontend\Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'blog',
            'path'            => '/blog',
            'middleware'      => App\Frontend\Action\BlogIndexAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'blog.post',
            'path'            => '/blog/{id:[0-9a-zA-z\-]+}',
            'middleware'      => App\Frontend\Action\BlogPostAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'feed.xml',
            'path'            => '/blog/feed.xml',
            'middleware'      => App\Frontend\Action\BlogXmlFeedAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'code',
            'path'            => '/code',
            'middleware'      => App\Frontend\Action\CodeAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'contact',
            'path'            => '/contact',
            'middleware'      => App\Frontend\Action\ContactAction::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
    ],
];

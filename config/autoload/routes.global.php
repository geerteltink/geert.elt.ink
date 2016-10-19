<?php

use App\Http\Action;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'aliases'            => [
            RouterInterface::class => FastRouteRouter::class,
        ],
        'factories'          => [
            Action\ContactAction::class => App\Factory\Http\Action\ContactActionFactory::class,
            FastRouteRouter::class      => InvokableFactory::class,
        ],
        'abstract_factories' => [
            App\Http\AbstractActionFactory::class,
        ],
    ],

    'routes' => [
        [
            'name'            => 'home',
            'path'            => '/',
            'middleware'      => Action\HomePageAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'blog',
            'path'            => '/blog',
            'middleware'      => Action\BlogIndexAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'blog.post',
            'path'            => '/blog/{id:[0-9a-zA-z\-]+}',
            'middleware'      => Action\BlogPostAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'feed.xml',
            'path'            => '/blog/feed.xml',
            'middleware'      => Action\BlogXmlFeedAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'code',
            'path'            => '/code',
            'middleware'      => Action\CodeAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name'            => 'contact',
            'path'            => '/contact',
            'middleware'      => Action\ContactAction::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
    ],
];

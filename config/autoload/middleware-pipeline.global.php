<?php

use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies'        => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
        ],
        'factories'  => [
            Helper\ServerUrlMiddleware::class           => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelperMiddleware::class           => Helper\UrlHelperMiddlewareFactory::class,
            Helper\UrlHelper::class                     => Helper\UrlHelperFactory::class,
            App\Middleware\CacheMiddleware::class       => App\Middleware\CacheMiddlewareFactory::class,
            PSR7Session\Http\SessionMiddleware::class   => App\Middleware\SessionMiddlewareFactory::class,
            App\Middleware\ErrorLoggerMiddleware::class => App\Middleware\ErrorLoggerMiddlewareFactory::class,
        ],
    ],

    // This can be used to seed pre- and/or post-routing middleware
    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                // Middleware for bootstrapping, pre-conditions and modifications to outgoing responses
                PSR7Session\Http\SessionMiddleware::class,
                Helper\ServerUrlMiddleware::class,
            ],
            'priority'   => PHP_INT_MAX,
        ],

        'routing' => [
            'middleware' => [
                ApplicationFactory::ROUTING_MIDDLEWARE,
                App\Middleware\CacheMiddleware::class,
                Helper\UrlHelperMiddleware::class,
                // Routing based Middleware for authentication, validation, etc.
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ],
            'priority'   => 1,
        ],

        'error' => [
            'middleware' => [
                App\Middleware\ErrorLoggerMiddleware::class,
            ],
            'error'      => true,
            'priority'   => -10000,
        ],
    ],
];

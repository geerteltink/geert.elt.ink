<?php

use App\Factory\Infrastructure\Http\CacheMiddlewareFactory;
use App\Factory\Infrastructure\Http\SessionMiddlewareFactory;
use App\Factory\Infrastructure\Log\ErrorLoggerMiddlewareFactory;
use App\Infrastructure\Http\CacheMiddleware;
use App\Infrastructure\Log\ErrorLoggerMiddleware;
use PSR7Session\Http\SessionMiddleware;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies'        => [
        'factories' => [
            Helper\ServerUrlHelper::class     => InvokableFactory::class,
            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
            Helper\UrlHelper::class           => Helper\UrlHelperFactory::class,

            CacheMiddleware::class       => CacheMiddlewareFactory::class,
            SessionMiddleware::class     => SessionMiddlewareFactory::class,
            ErrorLoggerMiddleware::class => ErrorLoggerMiddlewareFactory::class,
        ],
    ],

    // This can be used to seed pre- and/or post-routing middleware
    'middleware_pipeline' => [
        'always' => [
            'middleware' => [
                SessionMiddleware::class,
                Helper\ServerUrlMiddleware::class,
            ],
            'priority'   => PHP_INT_MAX,
        ],

        'routing' => [
            'middleware' => [
                ApplicationFactory::ROUTING_MIDDLEWARE,
                CacheMiddleware::class,
                Helper\UrlHelperMiddleware::class,
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ],
            'priority'   => 1,
        ],

        'error' => [
            'middleware' => [
                ErrorLoggerMiddleware::class,
            ],
            'error'      => true,
            'priority'   => -10000,
        ],
    ],
];

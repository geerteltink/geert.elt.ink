<?php

use Zend\Expressive\Helper;

return [
    'dependencies'        => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
        ],
        'factories'  => [
            Helper\UrlHelper::class               => Helper\UrlHelperFactory::class,
            Helper\UrlHelperMiddleware::class     => Helper\UrlHelperMiddlewareFactory::class,
            Helper\ServerUrlMiddleware::class     => Helper\ServerUrlMiddlewareFactory::class,
            App\Middleware\CacheMiddleware::class => App\Middleware\CacheMiddlewareFactory::class,
        ],
    ],

    // This can be used to seed pre- and/or post-routing middleware
    'middleware_pipeline' => [
        // An array of middleware to register prior to registration of the
        // routing middleware
        'pre_routing'  => [
            ['middleware' => App\Middleware\CacheMiddleware::class],
            ['middleware' => Helper\UrlHelperMiddleware::class],
            ['middleware' => Helper\ServerUrlMiddleware::class],
        ],

        // An array of middleware to register after registration of the
        // routing middleware
        'post_routing' => [
            //[
            // Required:
            //    'middleware' => 'Name of middleware service, or a callable',
            // Optional:
            //    'path'  => '/path/to/match',
            //    'error' => true,
            //],
        ],
    ],
];

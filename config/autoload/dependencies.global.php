<?php

return [
    'dependencies' => [
        'invokables' => [
        ],
        'factories'  => [
            Zend\Expressive\Application::class => Zend\Expressive\Container\ApplicationFactory::class,
            'cache' => App\Container\CacheFactory::class,
            'logger' => App\Container\LoggerFactory::class,
        ],
    ],
];

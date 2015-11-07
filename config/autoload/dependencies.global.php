<?php

return [
    'dependencies' => [
        'invokables' => [
        ],
        'factories' => [
            'cache' => App\Container\CacheFactory::class,
            Zend\Expressive\Application::class => Zend\Expressive\Container\ApplicationFactory::class,
        ]
    ]
];

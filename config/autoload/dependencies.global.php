<?php

return [
    'dependencies' => [
        'invokables' => [
            Domain\Post\PostRepository::class => Domain\Post\Adapter\FilePostRepository::class,
        ],
        'factories'  => [
            Zend\Expressive\Application::class => Zend\Expressive\Container\ApplicationFactory::class,
            'cache'                            => App\Container\CacheFactory::class,
            'logger'                           => App\Container\LoggerFactory::class,
        ],
    ],
];

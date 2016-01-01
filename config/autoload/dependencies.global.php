<?php

use Zend\Expressive;

return [
    'dependencies' => [
        'invokables' => [
            Domain\Post\PostRepository::class => Domain\Post\Adapter\FilePostRepository::class,
        ],
        'factories'  => [
            Expressive\Application::class      => Expressive\Container\ApplicationFactory::class,
            Doctrine\Common\Cache\Cache::class => App\Container\CacheFactory::class,
            'logger'                           => App\Container\LoggerFactory::class,
        ],
    ],
];

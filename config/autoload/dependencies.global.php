<?php

use Zend\Expressive;

return [
    'dependencies' => [
        'invokables' => [
            App\Domain\Post\PostRepository::class => App\Domain\Post\Adapter\FilePostRepository::class,
        ],
        'factories'  => [
            Expressive\Application::class                 => Expressive\Container\ApplicationFactory::class,
            Doctrine\Common\Cache\Cache::class            => App\Container\CacheFactory::class,
            Psr\Log\LoggerInterface::class                => App\Container\LoggerFactory::class,
            Zend\Mail\Transport\TransportInterface::class => App\Container\MailTransportFactory::class,
        ],
    ],
];

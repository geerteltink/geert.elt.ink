<?php

use App\Domain\Post\Adapter\FilePostRepository;
use App\Domain\Post\PostRepositoryInterface;
use App\Factory\Infrastructure\Cache\CacheFactory;
use App\Factory\Infrastructure\Log\LoggerFactory;
use App\Factory\Infrastructure\Mail\MailTransportFactory;
use Zend\Expressive;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'factories' => [
            FilePostRepository::class                     => InvokableFactory::class,
            Expressive\Application::class                 => Expressive\Container\ApplicationFactory::class,
            Doctrine\Common\Cache\Cache::class            => CacheFactory::class,
            Psr\Log\LoggerInterface::class                => LoggerFactory::class,
            Zend\Mail\Transport\TransportInterface::class => MailTransportFactory::class,
        ],
        'aliases'   => [
            PostRepositoryInterface::class => FilePostRepository::class,
        ],
    ],
];

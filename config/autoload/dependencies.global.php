<?php

declare(strict_types = 1);

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'factories' => [
            App\Domain\Post\Adapter\FilePostRepository::class => InvokableFactory::class,
            App\Http\Action\ContactAction::class              => App\Factory\Http\Action\ContactActionFactory::class,
            App\Infrastructure\Http\CacheMiddleware::class    => App\Factory\Infrastructure\Http\CacheMiddlewareFactory::class,

            Doctrine\Common\Cache\Cache::class                     => App\Factory\Infrastructure\Cache\CacheFactory::class,
            PSR7Sessions\Storageless\Http\SessionMiddleware::class => App\Factory\Infrastructure\Http\SessionMiddlewareFactory::class,
            Psr\Log\LoggerInterface::class                         => App\Factory\Infrastructure\Log\LoggerFactory::class,
            Zend\Mail\Transport\TransportInterface::class          => App\Factory\Infrastructure\Mail\MailTransportFactory::class,

            Zend\Expressive\Application::class                => Zend\Expressive\Container\ApplicationFactory::class,
            Zend\Expressive\Delegate\NotFoundDelegate::class  => Zend\Expressive\Container\NotFoundDelegateFactory::class,
            Zend\Expressive\Helper\ServerUrlHelper::class     => InvokableFactory::class,
            Zend\Expressive\Helper\ServerUrlMiddleware::class => Zend\Expressive\Helper\ServerUrlMiddlewareFactory::class,
            Zend\Expressive\Helper\UrlHelper::class           => Zend\Expressive\Helper\UrlHelperFactory::class,
            Zend\Expressive\Helper\UrlHelperMiddleware::class => Zend\Expressive\Helper\UrlHelperMiddlewareFactory::class,
            Zend\Expressive\Router\FastRouteRouter::class     => InvokableFactory::class,

            Zend\Expressive\Middleware\ErrorResponseGenerator::class => Zend\Expressive\Container\ErrorResponseGeneratorFactory::class,
            Zend\Expressive\Middleware\NotFoundHandler::class        => Zend\Expressive\Container\NotFoundHandlerFactory::class,
            Zend\Stratigility\Middleware\ErrorHandler::class         => App\Factory\Infrastructure\ErrorHandler\ErrorHandlerFactory::class,
        ],
        'aliases'   => [
            App\Domain\Post\PostRepositoryInterface::class  => App\Domain\Post\Adapter\FilePostRepository::class,
            Zend\Expressive\Router\RouterInterface::class   => Zend\Expressive\Router\FastRouteRouter::class,
            Zend\Expressive\Delegate\DefaultDelegate::class => Zend\Expressive\Delegate\NotFoundDelegate::class,
        ],

        'abstract_factories' => [
            App\Http\AbstractActionFactory::class,
        ],
    ],
];

<?php

declare(strict_types=1);

namespace App;

use Doctrine\Common\Cache\Cache;
use Psr\Log\LoggerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use ReCaptcha\ReCaptcha;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() : array
    {
        // @codingStandardsIgnoreStart
        return [
            'factories' => [
                Domain\Post\Adapter\FilePostRepository::class => InvokableFactory::class,
                Handler\BlogIndexHandler::class               => Handler\BlogIndexHandlerFactory::class,
                Handler\BlogPostHandler::class                => Handler\BlogPostHandlerFactory::class,
                Handler\BlogXmlFeedHandler::class             => Handler\BlogXmlFeedHandlerFactory::class,
                Handler\CodeHandler::class                    => Handler\CodeHandlerFactory::class,
                Handler\ContactHandler::class                 => Handler\ContactHandlerFactory::class,
                Handler\HomePageHandler::class                => Handler\HomePageHandlerFactory::class,
                Handler\PrivacyHandler::class                 => Handler\PrivacyHandlerFactory::class,
                Middleware\CacheMiddleware::class             => Middleware\CacheMiddlewareFactory::class,
                Middleware\ReCaptchaMiddleware::class         => Middleware\ReCaptchaMiddlewareFactory::class,

                Cache::class              => Container\CacheFactory::class,
                ErrorHandler::class       => Container\ErrorHandlerFactory::class,
                LoggerInterface::class    => Container\LoggerFactory::class,
                ReCaptcha::class          => Container\ReCaptchaFactory::class,
                SessionMiddleware::class  => Container\SessionMiddlewareFactory::class,
                TransportInterface::class => Container\MailTransportFactory::class,
            ],

            'aliases' => [
                Domain\Post\PostRepositoryInterface::class => Domain\Post\Adapter\FilePostRepository::class,
            ],
        ];
        // @codingStandardsIgnoreEnd
    }
}

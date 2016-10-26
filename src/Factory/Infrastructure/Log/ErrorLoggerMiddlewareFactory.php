<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Log;

use App\Infrastructure\Log\ErrorLoggerMiddleware;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ErrorLoggerMiddlewareFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): ErrorLoggerMiddleware {
        return new ErrorLoggerMiddleware(
            $container->get(LoggerInterface::class)
        );
    }
}

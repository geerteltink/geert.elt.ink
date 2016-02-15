<?php

namespace App\Middleware;

use Interop\Container\ContainerInterface;
use Monolog\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

class ErrorLoggerMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ErrorLoggerMiddleware(
            $container->get(Logger::class)
        );
    }
}

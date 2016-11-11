<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Stratigility\Middleware\ErrorHandler;

class ErrorHandlerFactory
{
    public function __invoke(ContainerInterface $container): ErrorHandler
    {
        return new ErrorHandler(
            new Response(),
            $container->get(TemplatedErrorResponseGenerator::class)
        );
    }
}

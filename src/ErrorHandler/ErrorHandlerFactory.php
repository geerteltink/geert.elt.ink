<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response;
use Zend\Stratigility\Middleware\ErrorHandler;

class ErrorHandlerFactory
{
    public function __invoke(ContainerInterface $container): ErrorHandler
    {
        $errorHandler = new ErrorHandler(
            new Response(),
            $container->get(TemplatedErrorResponseGenerator::class)
        );

        $logger = $container->get(LoggerInterface::class);
        $errorHandler->attachListener(function ($throwable, $request, $response) use ($logger) {
            $logger->error('"{method} {uri}": {message} in {file}:{line}', [
                'date'    => date('Y-m-d H:i:s'),
                'method'  => $request->getMethod(),
                'uri'     => (string) $request->getUri(),
                'message' => $throwable->getMessage(),
                'file'    => $throwable->getFile(),
                'line'    => $throwable->getLine(),
            ]);
        });

        return $errorHandler;
    }
}

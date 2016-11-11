<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
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

        if ($container->has(LoggerInterface::class)) {
            $logger = $container->get(LoggerInterface::class);
            $errorHandler->attachListener(function (
                Throwable $throwable,
                RequestInterface $request,
                ResponseInterface $response
            ) use ($logger) {
                $logger->error('"{method} {uri}": {message} in {file}:{line}', [
                    'date'    => date('Y-m-d H:i:s'),
                    'method'  => $request->getMethod(),
                    'uri'     => (string) $request->getUri(),
                    'message' => $throwable->getMessage(),
                    'file'    => $throwable->getFile(),
                    'line'    => $throwable->getLine(),
                ]);
            });
        }

        return $errorHandler;
    }
}

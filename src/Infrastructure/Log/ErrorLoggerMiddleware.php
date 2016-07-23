<?php

namespace App\Infrastructure\Log;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Zend\Stratigility\ErrorMiddlewareInterface;

class ErrorLoggerMiddleware implements ErrorMiddlewareInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke($error, Request $request, Response $response, callable $next = null)
    {
        if ($error instanceof \Exception) {
            $this->logger->error('Error ({code}): {message} in {file}:{line}', [
                'code'    => $error->getCode(),
                'message' => $error->getMessage(),
                'file'    => $error->getFile(),
                'line'    => $error->getLine(),
            ]);
        }

        return $next($request, $response, $error);
    }
}

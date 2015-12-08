<?php

namespace App\Middleware;

use Fabfuel\Prophiler\Toolbar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Zend\Stratigility\MiddlewareInterface;

class ProfilerMiddleware implements MiddlewareInterface
{
    /**
     * @var \Psr\Log\LoggerInterface the logger instance.
     */
    protected $logger;

    /**
     * @var Toolbar
     */
    protected $toolbar;

    /**
     * @param Toolbar         $toolbar
     * @param LoggerInterface $logger
     */
    public function __construct(Toolbar $toolbar, LoggerInterface $logger)
    {
        $this->toolbar = $toolbar;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        if (null !== $out) {
            $response = $out($request, $response);
        }

        $this->logger->debug('Generating profiler toolbar');

        if (!$response->getBody()->isWritable()) {
            $this->logger->debug('Response is not writable. Skipping Prophiler toolbar generation.');

            return $response;
        }

        $headers = $response->getHeader('Content-Type');
        if (count($headers) === 0) {
            $this->logger->debug('Content-Type of response not set. Skipping Prophiler toolbar generation.');

            return $response;
        }

        if ($headers[0] === 'text/html') {
            $response->getBody()->write($this->toolbar->render());
        } else {
            $this->logger->debug('Content-Type of response is not text/html. Skipping Prophiler toolbar generation.');
        }

        return $response;
    }
}

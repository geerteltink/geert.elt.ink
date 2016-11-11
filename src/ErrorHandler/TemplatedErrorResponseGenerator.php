<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class TemplatedErrorResponseGenerator
{
    private $isDevelopmentMode;

    private $renderer;

    public function __construct(TemplateRendererInterface $renderer, $isDevelopmentMode = false)
    {
        $this->renderer          = $renderer;
        $this->isDevelopmentMode = $isDevelopmentMode;
    }

    public function __invoke($e, ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response = $response->withStatus(500);
        $response->getBody()->write($this->renderer->render('error::error', [
            'exception'        => $e,
            'development_mode' => $this->isDevelopmentMode,
            'status'           => $response->getStatusCode(),
            'reason'           => $response->getReasonPhrase(),
        ]));

        return $response;
    }
}

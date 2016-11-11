<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\Delegate\CallableDelegateDecorator;

class NotFoundHandler implements ServerMiddlewareInterface
{
    private $renderer;

    private $responsePrototype;

    /**
     * NotFoundHandler constructor.
     *
     * @param TemplateRendererInterface $renderer
     * @param ResponseInterface         $responsePrototype
     */
    public function __construct(TemplateRendererInterface $renderer, ResponseInterface $responsePrototype)
    {
        $this->renderer          = $renderer;
        $this->responsePrototype = $responsePrototype;
    }

    /**
     * Proxy to process()
     *
     * Proxies to process, after first wrapping the `$next` argument using the
     * CallableDelegateDecorator.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        return $this->process($request, new CallableDelegateDecorator($next, $response));
    }

    /**
     * Creates and returns a 404 response.
     *
     * @param ServerRequestInterface $request  Ignored.
     * @param DelegateInterface      $delegate Ignored.
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $response = $this->responsePrototype->withStatus(404);
        $response->getBody()->write(
            $this->renderer->render('error::404')
        );

        return $response;
    }
}

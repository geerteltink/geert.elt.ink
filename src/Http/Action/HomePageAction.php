<?php

declare(strict_types = 1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

class HomePageAction implements MiddlewareInterface
{
    private $template;

    public function __construct(TemplateRendererInterface $template)
    {
        $this->template = $template;
    }

    /**
     * @param Request       $request
     * @param Response      $response
     * @param callable|null $next
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function __invoke(Request $request, Response $response, callable $next = null): Response
    {
        return new HtmlResponse($this->template->render('app::home-page'), 200, [
            'Cache-Control' => ['public', 'max-age=3600'],
        ]);
    }
}

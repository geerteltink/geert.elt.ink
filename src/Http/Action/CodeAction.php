<?php

declare(strict_types = 1);

namespace App\Http\Action;

use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

class CodeAction implements MiddlewareInterface
{
    private $template;

    private $cache;

    public function __construct(TemplateRendererInterface $template, Cache $cache)
    {
        $this->template = $template;
        $this->cache    = $cache;
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
        if ($this->cache->contains('github:xtreamwayz-repos')) {
            $repositories = $this->cache->fetch('github:xtreamwayz-repos');
        } else {
            $client       = new HttpClient();
            $apiResponse  = $client->request(
                'GET',
                'https://api.github.com/users/xtreamwayz/repos',
                [
                    'verify' => false,
                ]
            );
            $repositories = (string) $apiResponse->getBody();
            $this->cache->save('github:xtreamwayz-repos', $repositories, 86400);
        }

        $repositories = json_decode($repositories);

        return new HtmlResponse(
            $this->template->render('app::code', [
                'repos' => $repositories,
            ]),
            200,
            [
                'Cache-Control' => ['public', 'max-age=3600'],
            ]
        );
    }
}

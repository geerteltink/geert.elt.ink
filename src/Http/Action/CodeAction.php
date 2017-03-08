<?php

declare(strict_types = 1);

namespace App\Http\Action;

use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client as HttpClient;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class CodeAction implements MiddlewareInterface
{
    private $template;

    private $cache;

    public function __construct(TemplateRendererInterface $template, Cache $cache)
    {
        $this->template = $template;
        $this->cache    = $cache;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
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

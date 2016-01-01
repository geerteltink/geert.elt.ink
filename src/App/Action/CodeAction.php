<?php

namespace App\Action;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Doctrine\Common\Cache\Cache;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class CodeAction
{
    private $template;

    private $cache;

    public function __construct(TemplateRendererInterface $template, Cache $cache)
    {
        $this->template = $template;
        $this->cache = $cache;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($this->cache->contains('github:xtreamwayz-repos')) {
            $repositories = $this->cache->fetch('github:xtreamwayz-repos');
        } else {
            $client = new HttpClient();
            $apiResponse = $client->request(
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

        return new HtmlResponse($this->template->render('app::code', [
            'repos' => $repositories,
        ]), 200, [
            'Cache-Control' => ['public', 'max-age=3600']
        ]);
    }
}

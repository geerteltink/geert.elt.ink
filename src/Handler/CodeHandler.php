<?php

declare(strict_types=1);

namespace App\Handler;

use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use function json_decode;

class CodeHandler implements RequestHandlerInterface
{
    /** @var TemplateRendererInterface */
    private $template;

    /** @var Cache */
    private $cache;

    public function __construct(TemplateRendererInterface $template, Cache $cache)
    {
        $this->template = $template;
        $this->cache    = $cache;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        if ($this->cache->contains('github:xtreamwayz-repos')) {
            $repositories = $this->cache->fetch('github:xtreamwayz-repos');
        } else {
            $client       = new HttpClient();
            $apiResponse  = $client->request(
                'GET',
                'https://api.github.com/users/xtreamwayz/repos',
                ['verify' => false]
            );
            $repositories = (string) $apiResponse->getBody();
            $this->cache->save('github:xtreamwayz-repos', $repositories, 86400);
        }

        $repositories = json_decode($repositories);

        return new HtmlResponse(
            $this->template->render('app::code', ['repos' => $repositories]),
            200,
            [
                'Cache-Control' => ['public', 'max-age=3600'],
            ]
        );
    }
}

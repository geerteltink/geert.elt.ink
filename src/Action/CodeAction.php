<?php

namespace App\Action;

use GuzzleHttp\Client as HttpClient;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class CodeAction
{
    private $container;

    private $template;

    public function __construct(ContainerInterface $container, TemplateRendererInterface $template = null)
    {
        $this->container = $container;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        /** @var \Stash\Pool $cache */
        $cache = $this->container->get('cache');

        $item = $cache->getItem('github/xtreamwayz/repos');
        $repositories = $item->get();
        if ($item->isMiss()) {
            $item->lock();

            $client = new HttpClient();
            $apiResponse = $client->request(
                'GET',
                'https://api.github.com/users/xtreamwayz/repos',
                [
                    'verify' => false,
                ]
            );

            $repositories = (string)$apiResponse->getBody();
            $item->set($repositories, 86400);
        }

        $repositories = json_decode($repositories);

        return new HtmlResponse(
            $this->template->render(
                'app::code',
                [
                    'repos' => $repositories,
                ]
            )
        );
    }
}

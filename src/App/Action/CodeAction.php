<?php

namespace App\Action;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;

class CodeAction extends ActionAbstract
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $cache = $this->get('cache');

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

            $repositories = (string) $apiResponse->getBody();
            $item->set($repositories, 86400);
        }

        $repositories = json_decode($repositories);

        return new HtmlResponse(
            $this->render(
                'app::code',
                [
                    'repos' => $repositories,
                ]
            )
        );
    }
}

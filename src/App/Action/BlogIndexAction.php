<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;

class BlogIndexAction extends ActionAbstract
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

        $item = $cache->getItem('posts');
        $posts = $item->get();
        if ($item->isMiss()) {
            $item->lock();

            $postRepository = $this->get(PostRepository::class);
            $posts = array_reverse($postRepository->findAll());

            $item->set($posts);
        }

        return new HtmlResponse(
            $this->render(
                'app::blog-index',
                [
                    'posts' => $posts,
                ]
            )
        );
    }
}

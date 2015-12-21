<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;

class BlogPostAction extends ActionAbstract
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
        $postRepository = $this->get(PostRepository::class);
        $post = $postRepository->find($request->getAttribute('id'));
        if (!$post) {
            return $next($request, $response->withStatus(404), 'Not found');
        }

        return new HtmlResponse(
            $this->render(
                'app::blog-post',
                [
                    'post' => $post,
                ]
            )
        );
    }
}

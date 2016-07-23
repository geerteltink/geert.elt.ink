<?php

namespace App\Application\Action;

use App\Domain\Post\PostRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogPostAction
{
    private $template;

    private $postRepository;

    public function __construct(TemplateRendererInterface $template, PostRepositoryInterface $postRepository)
    {
        $this->template       = $template;
        $this->postRepository = $postRepository;
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
        $post = $this->postRepository->find($request->getAttribute('id'));
        if (!$post) {
            return $next($request, $response->withStatus(404), 'Not found');
        }

        return new HtmlResponse(
            $this->template->render('app::blog-post', [
                'post' => $post,
            ]),
            200,
            [
                'Cache-Control' => ['public', 'max-age=3600'],
            ]
        );
    }
}

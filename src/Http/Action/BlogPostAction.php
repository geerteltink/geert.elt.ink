<?php

declare(strict_types = 1);

namespace App\Http\Action;

use App\Domain\Post\PostRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

class BlogPostAction implements MiddlewareInterface
{
    private $template;

    private $postRepository;

    public function __construct(TemplateRendererInterface $template, PostRepositoryInterface $postRepository)
    {
        $this->template       = $template;
        $this->postRepository = $postRepository;
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
        $post = $this->postRepository->find($request->getAttribute('id'));
        if (! $post) {
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

<?php

declare(strict_types = 1);

namespace App\Http\Action;

use App\Domain\Post\PostRepositoryInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogPostAction implements MiddlewareInterface
{
    private $template;

    private $postRepository;

    public function __construct(TemplateRendererInterface $template, PostRepositoryInterface $postRepository)
    {
        $this->template       = $template;
        $this->postRepository = $postRepository;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $post = $this->postRepository->find($request->getAttribute('id'));
        if (! $post) {
            return $delegate->process($request);
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

<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\PostRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogPostHandler implements RequestHandlerInterface
{
    /** @var TemplateRendererInterface */
    private $template;

    /** @var PostRepositoryInterface */
    private $postRepository;

    /** @var NotFoundHandler */
    private $notFoundHandler;

    public function __construct(
        TemplateRendererInterface $template,
        PostRepositoryInterface $postRepository,
        NotFoundHandler $notFoundHandler
    ) {
        $this->template        = $template;
        $this->postRepository  = $postRepository;
        $this->notFoundHandler = $notFoundHandler;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $post = $this->postRepository->find($request->getAttribute('id'));
        if (! $post) {
            return $this->notFoundHandler->handle($request);
        }

        return new HtmlResponse(
            $this->template->render('app::blog-post', ['post' => $post]),
            200,
            [
                'Cache-Control' => ['public', 'max-age=3600'],
            ]
        );
    }
}

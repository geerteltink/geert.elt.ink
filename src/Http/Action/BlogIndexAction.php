<?php

declare(strict_types = 1);

namespace App\Http\Action;

use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Interop\Http\Middleware\DelegateInterface;
use Interop\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogIndexAction implements ServerMiddlewareInterface
{
    private $template;

    private $cache;

    private $postRepository;

    public function __construct(
        TemplateRendererInterface $template,
        Cache $cache,
        PostRepositoryInterface $postRepository
    ) {
        $this->template       = $template;
        $this->cache          = $cache;
        $this->postRepository = $postRepository;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        if ($this->cache->contains('blog:posts')) {
            $posts = $this->cache->fetch('blog:posts');
        } else {
            $posts = array_reverse($this->postRepository->findAll());
            $this->cache->save('blog:posts', $posts);
        }

        return new HtmlResponse(
            $this->template->render('app::blog-index', [
                'posts' => $posts,
            ]),
            200,
            [
                'Cache-Control' => ['public', 'max-age=3600'],
            ]
        );
    }
}

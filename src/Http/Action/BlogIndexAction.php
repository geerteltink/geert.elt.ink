<?php

declare(strict_types = 1);

namespace App\Http\Action;

use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

class BlogIndexAction implements MiddlewareInterface
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

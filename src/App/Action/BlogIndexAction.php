<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Doctrine\Common\Cache\Cache;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogIndexAction
{
    private $template;

    private $cache;

    private $postRepository;

    public function __construct(TemplateRendererInterface $template, Cache $cache, PostRepository $postRepository)
    {
        $this->template = $template;
        $this->cache = $cache;
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
        if ($this->cache->contains('blog:posts')) {
            $posts = $this->cache->fetch('blog:posts');
        } else {
            $posts = array_reverse($this->postRepository->findAll());
            $this->cache->save('blog:posts', $posts);
        }

        return new HtmlResponse($this->template->render('app::blog-index', [
            'posts' => $posts,
            'request' => $request,
        ]), 200, [
            'Cache-Control' => ['public', 'max-age=3600']
        ]);
    }
}

<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stash\Pool as Cache;
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
        $item = $this->cache->getItem('posts');
        $posts = $item->get();
        if ($item->isMiss()) {
            $item->lock();
            $posts = array_reverse($this->postRepository->findAll());
            $item->set($posts);
        }

        return new HtmlResponse(
            $this->template->render(
                'app::blog-index',
                [
                    'posts' => $posts,
                ]
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use function array_reverse;

class BlogIndexHandler implements RequestHandlerInterface
{
    /** @var TemplateRendererInterface */
    private $template;

    /** @var Cache */
    private $cache;

    /** @var PostRepositoryInterface */
    private $postRepository;

    public function __construct(
        TemplateRendererInterface $template,
        PostRepositoryInterface $postRepository,
        Cache $cache
    ) {
        $this->template       = $template;
        $this->postRepository = $postRepository;
        $this->cache          = $cache;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        if ($this->cache->contains('blog:posts')) {
            $posts = $this->cache->fetch('blog:posts');
        } else {
            $posts = array_reverse($this->postRepository->findAll());
            $this->cache->save('blog:posts', $posts);
        }

        return new HtmlResponse(
            $this->template->render('app::blog-index', ['posts' => $posts]),
            200,
            [
                'Cache-Control' => ['public', 'max-age=3600'],
            ]
        );
    }
}

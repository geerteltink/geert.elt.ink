<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogIndexAction
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var null|TemplateRendererInterface
     */
    private $template;

    /**
     * BlogIndexAction constructor.
     *
     * @param ContainerInterface             $container
     * @param TemplateRendererInterface|null $template
     */
    public function __construct(ContainerInterface $container, TemplateRendererInterface $template = null)
    {
        $this->container = $container;
        $this->template = $template;
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
        $cache = $this->container->get('cache');

        $item = $cache->getItem('posts');
        $posts = $item->get();
        if ($item->isMiss()) {
            $item->lock();

            $postRepository = $this->container->get(PostRepository::class);
            $posts = array_reverse($postRepository->findAll());

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

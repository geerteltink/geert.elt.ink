<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogPostAction
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * BlogPostAction constructor.
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
        $postRepository = $this->container->get(PostRepository::class);
        $post = $postRepository->find($request->getAttribute('id'));
        if (!$post) {
            return $next($request, $response->withStatus(404), 'Not found');
        }

        return new HtmlResponse(
            $this->template->render(
                'app::blog-post',
                [
                    'post' => $post,
                ]
            )
        );
    }
}

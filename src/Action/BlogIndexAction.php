<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Mni\FrontYAML\Parser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stdlib\Glob;

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
        $parser = new Parser();

        $item = $cache->getItem('posts');
        $posts = $item->get();
        if ($item->isMiss()) {
            $item->lock();

            $posts = [];
            foreach (Glob::glob('data/posts/*.md', Glob::GLOB_BRACE) as $file) {
                $document = $parser->parse(file_get_contents($file));
                $posts[] = $document->getYAML();
            }
            $posts = array_reverse($posts);

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

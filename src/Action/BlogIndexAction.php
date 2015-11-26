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
    private $container;

    private $template;

    public function __construct(ContainerInterface $container, TemplateRendererInterface $template = null)
    {
        $this->container = $container;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        /** @var \Stash\Pool $cache */
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

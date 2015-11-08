<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Mni\FrontYAML\Parser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogPostAction
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
        $file = sprintf('data/posts/%s.md', $request->getAttribute('id'));
        if (!is_file($file)) {
            return $next($request, $response->withStatus(404), 'Not found');
        }

        $parser = new Parser();
        $document = $parser->parse(file_get_contents($file));
        $post = $document->getYAML();
        $post['content'] = $document->getContent();

        return new HtmlResponse($this->template->render('app::blog-post', [
            'post' => $post
        ]));
    }
}

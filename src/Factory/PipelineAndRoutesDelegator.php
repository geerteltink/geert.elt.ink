<?php

namespace App\Factory;

use App\Http\Action;
use App\Infrastructure\Http\CacheMiddleware;
use Psr\Container\ContainerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Zend\Expressive\Application;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\Stratigility\Middleware\ErrorHandler;

class PipelineAndRoutesDelegator
{
    public function __invoke(ContainerInterface $container, $serviceName, callable $callback)
    {
        /** @var $app Application */
        $app = $callback();

        // Setup pipeline:
        $app->pipe(ErrorHandler::class);
        $app->pipe(ServerUrlMiddleware::class);
        $app->pipe(SessionMiddleware::class);
        $app->pipeRoutingMiddleware();
        $app->pipe(ImplicitHeadMiddleware::class);
        $app->pipe(ImplicitOptionsMiddleware::class);
        $app->pipe(UrlHelperMiddleware::class);
        $app->pipe(CacheMiddleware::class);
        $app->pipeDispatchMiddleware();
        $app->pipe(NotFoundHandler::class);

        // Setup routes:
        $app->route('/', Action\HomePageAction::class, ['GET'], 'home');
        $app->route('/blog', Action\BlogIndexAction::class, ['GET'], 'blog');
        $app->route('/blog/feed.xml', Action\BlogXmlFeedAction::class, ['GET'], 'feed');
        $app->route('/blog/{id:[0-9a-zA-Z\-]+}', Action\BlogPostAction::class, ['GET'], 'blog.post');
        $app->route('/code', Action\CodeAction::class, ['GET'], 'code');
        $app->route('/contact', Action\ContactAction::class, ['GET', 'POST'], 'contact');

        return $app;
    }
}

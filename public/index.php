<?php

declare(strict_types = 1);

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Zend\Expressive\Application $app */
$app = $container->get(Zend\Expressive\Application::class);
$app->raiseThrowables();

$app->pipe(Zend\Expressive\Helper\UrlHelperMiddleware::class);
$app->pipe(Zend\Stratigility\Middleware\ErrorHandler::class);
// TODO: ErrorLoggerMiddleware
$app->pipe(PSR7Session\Http\SessionMiddleware::class);
$app->pipeRoutingMiddleware();
$app->pipe(App\Infrastructure\Http\CacheMiddleware::class);
$app->pipe(Zend\Expressive\Helper\UrlHelperMiddleware::class);
$app->pipeDispatchMiddleware();
$app->pipe(App\ErrorHandler\NotFoundHandler::class);

$app->route('/', App\Http\Action\HomePageAction::class, ['GET'], 'home');
$app->route('/blog', App\Http\Action\BlogIndexAction::class, ['GET'], 'blog');
$app->route('/blog/{id:[0-9a-zA-z\-]+}', App\Http\Action\BlogPostAction::class, ['GET'], 'blog.post');
$app->route('/blog/feed.xml', App\Http\Action\BlogXmlFeedAction::class, ['GET'], 'feed.xml');
$app->route('/code', App\Http\Action\CodeAction::class, ['GET'], 'code');
$app->route('/contact', App\Http\Action\ContactAction::class, ['GET', 'POST'], 'contact');

// Run application
$app->run();

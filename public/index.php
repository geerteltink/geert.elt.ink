<?php

declare(strict_types = 1);

//error_reporting(error_reporting() & ~E_USER_DEPRECATED);

use App\Infrastructure\Http\CacheMiddleware;
use PSR7Session\Http\SessionMiddleware;
use Zend\Expressive\Application;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\Stratigility\Middleware\ErrorHandler;

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

// Setup middleware
//$app->pipe(ServerUrlMiddleware::class);
$app->pipe(ErrorHandler::class);
$app->pipe(SessionMiddleware::class);
$app->pipeRoutingMiddleware();
$app->pipe(CacheMiddleware::class);
$app->pipe(UrlHelperMiddleware::class);
$app->pipeDispatchMiddleware();
$app->pipe(NotFoundHandler::class);

// Setup routes
$app->route('/', App\Http\Action\HomePageAction::class, ['GET'], 'home');
$app->route('/blog', App\Http\Action\BlogIndexAction::class, ['GET'], 'blog');
$app->route('/blog/feed.xml', App\Http\Action\BlogXmlFeedAction::class, ['GET'], 'feed');
$app->route('/blog/{id:[0-9a-zA-Z\-]+}', App\Http\Action\BlogPostAction::class, ['GET'], 'blog.post');
$app->route('/code', App\Http\Action\CodeAction::class, ['GET'], 'code');
$app->route('/contact', App\Http\Action\ContactAction::class, ['GET', 'POST'], 'contact');

// Run application
$app->run();

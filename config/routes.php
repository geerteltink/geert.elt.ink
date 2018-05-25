<?php

declare(strict_types=1);

use App\Handler;
use App\Middleware\ReCaptchaMiddleware;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->route('/', Handler\HomePageHandler::class, ['GET'], 'home');
    $app->route('/blog', Handler\BlogIndexHandler::class, ['GET'], 'blog');
    $app->route('/blog/feed.xml', Handler\BlogXmlFeedHandler::class, ['GET'], 'feed');
    $app->route('/blog/{id:[0-9a-zA-Z\-]+}', Handler\BlogPostHandler::class, ['GET'], 'blog.post');
    $app->route('/code', Handler\CodeHandler::class, ['GET'], 'code');
    $app->route('/contact', [
        ReCaptchaMiddleware::class,
        Handler\ContactHandler::class,
    ], ['GET', 'POST'], 'contact');
    $app->route('/privacy', Handler\PrivacyHandler::class, ['GET'], 'privacy');
};

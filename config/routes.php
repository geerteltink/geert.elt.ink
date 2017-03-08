<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

$app->route('/', App\Http\Action\HomePageAction::class, ['GET'], 'home');
$app->route('/blog', App\Http\Action\BlogIndexAction::class, ['GET'], 'blog');
$app->route('/blog/feed.xml', App\Http\Action\BlogXmlFeedAction::class, ['GET'], 'feed');
$app->route('/blog/{id:[0-9a-zA-Z\-]+}', App\Http\Action\BlogPostAction::class, ['GET'], 'blog.post');
$app->route('/code', App\Http\Action\CodeAction::class, ['GET'], 'code');
$app->route('/contact', App\Http\Action\ContactAction::class, ['GET', 'POST'], 'contact');

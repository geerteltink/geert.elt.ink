<?php

declare(strict_types=1);

namespace AppTest;

use Generator;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

class BlogPostsSmokeTest extends TestCase
{
    /**
     * @group        functional
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($uri) : void
    {
        /** @var ContainerInterface $container */
        $container = require 'config/container.php';

        /** @var Application $app */
        $app     = $container->get(Application::class);
        $factory = $container->get(MiddlewareFactory::class);

        // Execute programmatic/declarative middleware pipeline and routing
        // configuration statements
        (require 'config/pipeline.php')($app, $factory, $container);
        (require 'config/routes.php')($app, $factory, $container);

        $request = (new ServerRequest())
            ->withMethod('GET')
            ->withUri(new Uri($uri));

        $response = $app->handle($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function urlProvider() : Generator
    {
        // Get all blog posts
        foreach (new \DirectoryIterator('data/posts') as $file) {
            if ($file->isFile()) {
                // Return file name without extension
                yield ['/blog/' . $file->getBasename('.md')];
            }
        }
    }
}

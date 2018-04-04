<?php

declare(strict_types=1);

namespace AppTest;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

class SmokeTest extends TestCase
{
    /**
     * @group        functional
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($statusCode, $uri) : void
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
        self::assertEquals($statusCode, $response->getStatusCode());
    }

    public function urlProvider() : array
    {
        return [
            [200, '/'],
            [200, '/blog'],
            //[200, '/code'],
            [200, '/contact'],
            [404, '/404'],
        ];
    }
}

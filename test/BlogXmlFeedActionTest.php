<?php

declare(strict_types=1);

namespace AppTest;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

class BlogXmlFeedActionTest extends TestCase
{
    /**
     * @group functional
     */
    public function testFeedBuildIsSuccessful() : void
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
            ->withUri(new Uri('/blog/feed.xml'));

        $response = $app->handle($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());

        self::assertTrue($response->hasHeader('Content-Type'));
        self::assertEquals('application/atom+xml', $response->getHeader('Content-Type')[0]);

        $data = (string) $response->getBody();
        self::assertNotEmpty($data);

        $doc = new DOMDocument();
        $doc->loadXML($data);

        $feed = $doc->getElementsByTagName('feed');
        self::assertEquals(1, $feed->length);
        self::assertEquals('http://www.w3.org/2005/Atom', $doc->documentElement->getAttribute('xmlns'));
    }
}

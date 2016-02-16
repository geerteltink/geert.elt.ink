<?php

namespace AppTest;

use DOMDocument;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Application;

class BlogXmlFeedActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group functional
     */
    public function testFeedBuildIsSuccessful()
    {
        /** @var \Interop\Container\ContainerInterface $container */
        $container = require 'config/container.php';

        /** @var Application $app */
        $app = $container->get('Zend\Expressive\Application');
        $request = new ServerRequest([], [], 'https://example.com/blog/feed.xml', 'GET');
        /** @var ResponseInterface $response */
        $response = $app($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertEquals('application/atom+xml', $response->getHeader('Content-Type')[0]);

        $data = (string) $response->getBody();
        $this->assertNotEmpty($data);

        $doc = new DOMDocument();
        $doc->loadXML($data);

        $feed = $doc->getElementsByTagName('feed');
        $this->assertEquals(1, $feed->length);
        $this->assertEquals('http://www.w3.org/2005/Atom', $doc->documentElement->getAttribute('xmlns'));
    }
}

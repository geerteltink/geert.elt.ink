<?php

declare(strict_types = 1);

namespace AppTest\App;

use AppTest\WebTestCase;
use DOMDocument;
use Psr\Http\Message\ResponseInterface;

class BlogXmlFeedActionTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testFeedBuildIsSuccessful()
    {
        $response = $this->handleRequest('GET', '/blog/feed.xml');

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

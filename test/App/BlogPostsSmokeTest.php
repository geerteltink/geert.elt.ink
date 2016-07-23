<?php

namespace AppTest\App;

use AppTest\WebTestCase;
use Psr\Http\Message\ResponseInterface;

class BlogPostsSmokeTest extends WebTestCase
{
    /**
     * @group        functional
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $response = $this->handleRequest('GET', $url);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals(200, $response->getStatusCode());
    }

    public function urlProvider()
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

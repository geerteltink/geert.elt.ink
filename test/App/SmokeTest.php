<?php

declare(strict_types=1);

namespace AppTest\App;

use AppTest\WebTestCase;
use Psr\Http\Message\ResponseInterface;

class SmokeTest extends WebTestCase
{
    /**
     * @group        functional
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($statusCode, $url) : void
    {
        $response = $this->handleRequest('GET', $url);

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

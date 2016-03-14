<?php

namespace AppTest\App;

use AppTest\WebTestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class SmokeTest extends WebTestCase
{
    /**
     * @group        functional
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($statusCode, $url)
    {
        $response = $this->handleRequest('GET', $url);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertEquals($statusCode, $response->getStatusCode());
    }

    public function urlProvider()
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

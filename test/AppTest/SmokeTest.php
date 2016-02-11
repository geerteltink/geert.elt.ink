<?php

namespace AppTest;

use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Application;

class SmokeTest extends \PHPUnit_Framework_TestCase
{
    public $response;

    public function setUp()
    {
        $this->response = null;
    }

    public function getEmitter()
    {
        $self = $this;
        $emitter = $this->prophesize(EmitterInterface::class);
        $emitter
            ->emit(Argument::type(ResponseInterface::class))
            ->will(
                function ($args) use ($self) {
                    $response = array_shift($args);
                    $self->response = $response;

                    return null;
                }
            )
            ->shouldBeCalled()
        ;

        return $emitter->reveal();
    }

    /**
     * @group        functional
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        /** @var \Interop\Container\ContainerInterface $container */
        $container = require 'config/container.php';

        /** @var Application $app */
        $app = $container->get('Zend\Expressive\Application');
        $request = new ServerRequest([], [], 'https://example.com'.$url, 'GET');
        /** @var ResponseInterface $response */
        $response = $app($request, new Response());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function urlProvider()
    {
        return [
            ['/'],
            ['/blog'],
            //['/code'],
            ['/contact'],
        ];
    }
}

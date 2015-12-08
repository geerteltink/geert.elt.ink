<?php

namespace AppTest\Action;

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
            ['/code'],
        ];
    }
}

/*
            ['/blog/2013-11-05-hello-world'],
            ['/blog/2013-12-06-symfony-2-flash-messages'],
            ['/blog/2014-06-20-jekyll-atom-feed'],
            ['/blog/2014-10-02-symfony-2-dynamic-router'],
            ['/blog/2014-11-03-howto-update-teamspeak-3'],
            ['/blog/2014-11-04-phpunit-selenium-2'],
            ['/blog/2014-11-10-using-sismo-as-your-local-continuous-integration-server'],
            ['/blog/2015-01-24-check-git-status-recursively-on-windows'],
            ['/blog/2015-03-13-grunt-gulp-and-npm'],
            ['/blog/2015-05-20-git-worklow'],
            ['/blog/2015-05-21-git-troubleshooting'],
            ['/blog/2015-06-04-symfony-shibboleth-login-the-easy-way'],
            ['/blog/2015-09-07-pimple-3-container-interop']
 */

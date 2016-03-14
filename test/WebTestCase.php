<?php

namespace AppTest;

use Interop\Container\ContainerInterface;
use Lcobucci\JWT\Builder;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use PSR7Session\Http\SessionMiddleware;
use PSR7Session\Session\DefaultSessionData;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Expressive\Application;
use Zend\ServiceManager\ServiceManager;

class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected static $config;

    /**
     * @var ContainerInterface
     */
    protected static $container;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public static function setUpBeforeClass()
    {
        // Load configuration
        $config = require __DIR__ . '/../config/config.php';

        // Override config settings
        $config['debug'] = true;
        $config['config_cache_enabled'] = false;

        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;

        // Build container
        self::$container = new ServiceManager($dependencies);
    }

    public static function tearDownAfterClass()
    {
        // Clean up
        self::$container = null;
    }

    /**
     * @param string     $method
     * @param string     $uri
     * @param array|null $parameters
     * @param array|null $sessionData
     *
     * @return ResponseInterface
     */
    protected function handleRequest(string $method, string $uri, array $parameters = null, array $sessionData = null)
    {
        // Create request
        $request = (new ServerRequest())
            ->withMethod($method)
            ->withUri(new Uri($uri));

        // Set post parameters
        if ($parameters !== null) {
            $request = $request->withParsedBody($parameters);
        }

        // Set PSR-7 session data
        if ($sessionData !== null) {
            // Get session middleware
            $sessionMiddleWare = self::$container->get(SessionMiddleware::class);

            // Get signer
            $signerReflection = new \ReflectionProperty($sessionMiddleWare, 'signer');
            $signerReflection->setAccessible(true);
            $signer = $signerReflection->getValue($sessionMiddleWare);

            // Get signature key
            $signatureKeyReflection = new \ReflectionProperty($sessionMiddleWare, 'signatureKey');
            $signatureKeyReflection->setAccessible(true);
            $signatureKey = $signatureKeyReflection->getValue($sessionMiddleWare);

            // Set session data as a cookie
            $request = $request->withCookieParams([
                SessionMiddleware::DEFAULT_COOKIE => (string) (new Builder())
                    ->setIssuedAt((new \DateTime('-30 second'))->getTimestamp())
                    ->setExpiration((new \DateTime('+30 second'))->getTimestamp())
                    ->set(SessionMiddleware::SESSION_CLAIM, DefaultSessionData::fromTokenData($sessionData))
                    ->sign($signer, $signatureKey)
                    ->getToken(),
            ]);
        }

        // Get application from container
        $app = self::$container->get(Application::class);

        // Invoke the request
        return $app($request, new Response());
    }
}

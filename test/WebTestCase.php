<?php

declare(strict_types = 1);

namespace AppTest;

use Interop\Container\ContainerInterface;
use Lcobucci\JWT\Builder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use PSR7Sessions\Storageless\Session\DefaultSessionData;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Expressive\Application;
use Zend\Expressive\Delegate\NotFoundDelegate;
use Zend\ServiceManager\ServiceManager;

class WebTestCase extends TestCase
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
    protected static $app;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public static function setUpBeforeClass()
    {
        // Load configuration
        $config = require __DIR__ . '/../config/config.php';

        // Override config settings
        $config['debug']                        = true;
        $config[ConfigAggregator::ENABLE_CACHE] = false;

        // Build container
        self::$container = new ServiceManager($config['dependencies']);
        self::$container->setService('config', $config);

        // Get application from container
        self::$app = self::$container->get(Application::class);
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
     *
     * @throws \BadMethodCallException
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \InvalidArgumentException
     */
    protected function handleRequest(
        string $method,
        string $uri,
        array $parameters = null,
        array $sessionData = null
    ): ResponseInterface {
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

        $delegate = new NotFoundDelegate(new Response());

        // Invoke the request
        return self::$app->process($request, $delegate);
    }
}

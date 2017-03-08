<?php

declare(strict_types = 1);

namespace AppTest;

use App\Http\Action;
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
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\ServiceManager\ServiceManager;
use Zend\Stratigility\Middleware\ErrorHandler;

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

        // Setup middleware
        self::$app->pipe(ServerUrlMiddleware::class);
        self::$app->pipe(ErrorHandler::class);
        self::$app->pipe(SessionMiddleware::class);
        self::$app->pipeRoutingMiddleware();
        self::$app->pipe(UrlHelperMiddleware::class);
        self::$app->pipeDispatchMiddleware();
        self::$app->pipe(NotFoundHandler::class);

        // Setup routes
        self::$app->route('/', Action\HomePageAction::class, ['GET'], 'home');
        self::$app->route('/blog', Action\BlogIndexAction::class, ['GET'], 'blog');
        self::$app->route('/blog/feed.xml', Action\BlogXmlFeedAction::class, ['GET'], 'feed');
        self::$app->route('/blog/{id:[0-9a-zA-Z\-]+}', Action\BlogPostAction::class, ['GET'], 'blog.post');
        self::$app->route('/code', Action\CodeAction::class, ['GET'], 'code');
        self::$app->route('/contact', Action\ContactAction::class, ['GET', 'POST'], 'contact');
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

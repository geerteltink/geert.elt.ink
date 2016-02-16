<?php

namespace App\Middleware;

use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class CacheMiddleware implements MiddlewareInterface
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var bool
     */
    protected $debug;

    public function __construct(Cache $cache, $debug = false)
    {
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $cachedResponse = $this->getCachedResponse($request, $response);

        if (null !== $cachedResponse) {
            return $cachedResponse;
        }

        $response = $next($request, $response);

        if ($this->debug !== true) {
            $this->cacheResponse($request, $response);
        }

        return $response;
    }

    private function getCacheKey(Request $request)
    {
        return 'http-cache:' . $request->getUri()->getPath();
    }

    private function getCachedResponse(Request $request, Response $response)
    {
        if ('GET' !== $request->getMethod()) {
            return null;
        }

        $item = $this->cache->fetch($this->getCacheKey($request));
        if (false === $item) {
            return null;
        }

        $response->getBody()->write($item['body']);
        foreach ($item['headers'] as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }

    private function cacheResponse(Request $request, Response $response)
    {
        if ('GET' !== $request->getMethod() || !$response->hasHeader('Cache-Control')) {
            return;
        }

        $cacheControl = $response->getHeader('Cache-Control');
        $abortTokens = ['private', 'no-cache', 'no-store'];
        if (count(array_intersect($abortTokens, $cacheControl)) > 0) {
            return;
        }

        foreach ($cacheControl as $value) {
            $parts = explode('=', $value);
            if (count($parts) == 2 && 'max-age' === $parts[0]) {
                $this->cache->save(
                    $this->getCacheKey($request),
                    [
                        'headers' => $response->getHeaders(),
                        'body'    => (string)$response->getBody(),
                    ],
                    intval($parts[1])
                );

                return;
            }
        }
    }
}

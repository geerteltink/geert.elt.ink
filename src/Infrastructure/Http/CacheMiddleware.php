<?php

declare(strict_types = 1);

namespace App\Infrastructure\Http;

use Doctrine\Common\Cache\Cache;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response as DefaultResponse;

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

    public function __construct(Cache $cache, bool $debug = false)
    {
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function process(Request $request, DelegateInterface $delegate): Response
    {
        $cachedResponse = $this->getCachedResponse($request);

        if ($cachedResponse !== null && $this->debug !== true) {
            return $cachedResponse;
        }

        $response = $delegate->process($request);

        if ($this->debug !== true) {
            $this->cacheResponse($request, $response);
        }

        return $response;
    }

    private function getCachedResponse(Request $request)
    {
        if ('GET' !== $request->getMethod()) {
            return null;
        }

        $item = $this->cache->fetch($this->getCacheKey($request));
        if (false === $item) {
            return null;
        }

        $response = new DefaultResponse();
        $response->getBody()->write($item['body']);
        foreach ($item['headers'] as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }

    private function getCacheKey(Request $request): string
    {
        return 'http-cache:' . $request->getUri()->getPath();
    }

    private function cacheResponse(Request $request, Response $response)
    {
        if ('GET' !== $request->getMethod() || ! $response->hasHeader('Cache-Control')) {
            return;
        }

        $cacheControl = $response->getHeader('Cache-Control');
        $abortTokens  = ['private', 'no-cache', 'no-store'];
        if (count(array_intersect($abortTokens, $cacheControl)) > 0) {
            return;
        }

        foreach ($cacheControl as $value) {
            $parts = explode('=', $value);
            if (count($parts) === 2 && 'max-age' === $parts[0]) {
                $this->cache->save(
                    $this->getCacheKey($request),
                    [
                        'headers' => $response->getHeaders(),
                        'body'    => (string) $response->getBody(),
                    ],
                    (int) $parts[1]
                );

                return;
            }
        }
    }
}

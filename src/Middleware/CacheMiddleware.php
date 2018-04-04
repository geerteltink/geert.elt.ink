<?php

declare(strict_types=1);

namespace App\Middleware;

use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response as DefaultResponse;
use function array_intersect;
use function count;
use function explode;

class CacheMiddleware implements MiddlewareInterface
{
    /** @var Cache */
    protected $cache;

    /** @var bool */
    protected $debug;

    public function __construct(Cache $cache, ?bool $debug = null)
    {
        $this->cache = $cache;
        $this->debug = $debug ?? false;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $cachedResponse = $this->getCachedResponse($request);

        if ($cachedResponse !== null && $this->debug !== true) {
            return $cachedResponse;
        }

        $response = $handler->handle($request);

        if ($this->debug !== true) {
            $this->cacheResponse($request, $response);
        }

        return $response;
    }

    private function getCachedResponse(ServerRequestInterface $request) : ?ResponseInterface
    {
        if ($request->getMethod() !== 'GET') {
            return null;
        }

        $item = $this->cache->fetch($this->getCacheKey($request));
        if ($item === false) {
            return null;
        }

        $response = new DefaultResponse();
        $response->getBody()->write($item['body']);
        foreach ($item['headers'] as $name => $value) {
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }

    private function getCacheKey(ServerRequestInterface $request) : string
    {
        return 'http-cache:' . $request->getUri()->getPath();
    }

    private function cacheResponse(ServerRequestInterface $request, ResponseInterface $response) : void
    {
        if ($request->getMethod() !== 'GET' || ! $response->hasHeader('Cache-Control')) {
            return;
        }

        $cacheControl = $response->getHeader('Cache-Control');
        $abortTokens  = ['private', 'no-cache', 'no-store'];
        if (count(array_intersect($abortTokens, $cacheControl)) > 0) {
            return;
        }

        foreach ($cacheControl as $value) {
            $parts = explode('=', $value);
            if (count($parts) === 2 && $parts[0] === 'max-age') {
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

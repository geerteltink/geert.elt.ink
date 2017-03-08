<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Http;

use App\Infrastructure\Http\CacheMiddleware;
use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerInterface;

class CacheMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): CacheMiddleware
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $debug  = array_key_exists('debug', $config) ? (bool) $config['debug'] : false;

        $cache = $container->get(Cache::class);

        return new CacheMiddleware($cache, $debug);
    }
}

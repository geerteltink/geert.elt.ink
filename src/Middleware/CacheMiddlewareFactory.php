<?php

declare(strict_types=1);

namespace App\Middleware;

use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerInterface;
use function array_key_exists;

class CacheMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : CacheMiddleware
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $debug  = array_key_exists('debug', $config) ? (bool) $config['debug'] : false;

        $cache = $container->get(Cache::class);

        return new CacheMiddleware($cache, $debug);
    }
}

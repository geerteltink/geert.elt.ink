<?php

namespace App\Middleware;

use Doctrine\Common\Cache\Cache;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $debug = array_key_exists('debug', $config) ? (bool) $config['debug'] : false;

        $cache = $container->get(Cache::class);

        return new CacheMiddleware($cache, $debug);
    }
}

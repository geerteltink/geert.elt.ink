<?php

namespace App\Middleware;

use Doctrine\Common\Cache\Cache;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Cache $cache */
        $cache = $container->get(Cache::class);

        return new CacheMiddleware($cache);
    }
}

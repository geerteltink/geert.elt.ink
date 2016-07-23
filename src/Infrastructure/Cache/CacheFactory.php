<?php

namespace App\Infrastructure\Cache;

use Doctrine\Common\Cache\FilesystemCache;
use Interop\Container\ContainerInterface;

class CacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new FilesystemCache('data/cache/doctrine');
    }
}

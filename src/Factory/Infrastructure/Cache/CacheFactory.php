<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Cache;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\FilesystemCache;
use Interop\Container\ContainerInterface;

class CacheFactory
{
    public function __invoke(ContainerInterface $container): CacheProvider
    {
        return new FilesystemCache('data/cache/doctrine');
    }
}

<?php

declare(strict_types=1);

namespace App\Container;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\FilesystemCache;
use Psr\Container\ContainerInterface;

class CacheFactory
{
    public function __invoke(ContainerInterface $container) : CacheProvider
    {
        return new FilesystemCache('data/cache/doctrine');
    }
}

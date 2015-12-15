<?php

namespace App\Container;

use Interop\Container\ContainerInterface;
use Stash\Driver\FileSystem as CacheDriver;
use Stash\Pool as CachePool;

class CacheFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return CachePool
     */
    public function __invoke(ContainerInterface $container)
    {
        // Create Driver with default options
        $driver = new CacheDriver();
        $driver->setOptions(
            [
                'path' => 'data/cache/stash/',
            ]
        );

        // Inject the driver into a new Pool object.
        $pool = new CachePool($driver);

        return $pool;
    }
}

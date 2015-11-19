<?php

namespace App\Middleware;

use Fabfuel\Prophiler\Adapter\Psr\Log\Logger;
use Fabfuel\Prophiler\Aggregator\Cache\CacheAggregator;
use Fabfuel\Prophiler\Aggregator\Database\QueryAggregator;
use Fabfuel\Prophiler\DataCollector\Request;
use Fabfuel\Prophiler\Toolbar;
use Interop\Container\ContainerInterface;
use Monolog\Handler\PsrHandler;

class ProfilerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $logger = $container->get('logger');

        $profiler = $container->get('profiler');
        $profiler->addAggregator(new QueryAggregator());
        $profiler->addAggregator(new CacheAggregator());

        // Push official logger messages to the profiler
        $logger->pushHandler(new PsrHandler(new Logger($profiler)));

        // Init toolbar
        $toolbar = new Toolbar($profiler);
        $toolbar->addDataCollector(new Request());

        return new ProfilerMiddleware($toolbar, $logger);
    }
}

<?php

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Start Profiler
//$profiler = new \Fabfuel\Prophiler\Profiler();

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Zend\Expressive\Application $app */
$app = $container->get('Zend\Expressive\Application');
$app->run();

/*
// Add aggregator
$profiler->addAggregator(new \Fabfuel\Prophiler\Aggregator\Database\QueryAggregator());
$profiler->addAggregator(new \Fabfuel\Prophiler\Aggregator\Cache\CacheAggregator());

// Collect container data
$profilerContainer = new \Fabfuel\Prophiler\Adapter\Interop\Container\Container($container, $profiler);

// Get main logger
$logger = $container->get('logger');

// Push official logger messages to the profiler
$logger->pushHandler(new \Monolog\Handler\PsrHandler(new \Fabfuel\Prophiler\Adapter\Psr\Log\Logger($profiler)));

// Start toolbar
$toolbar = new \Fabfuel\Prophiler\Toolbar($profiler);
$toolbar->addDataCollector(new \Fabfuel\Prophiler\DataCollector\Request());

// Start app
$appFactory = new \Zend\Expressive\Container\ApplicationFactory();
$app = $appFactory($profilerContainer);

// Start debug middleware
$debugMiddleware = \Zend\Expressive\AppFactory::create();
$debugMiddleware->pipe(new App\Middleware\ProfilerMiddleware($toolbar, $logger));
$debugMiddleware->pipe(function ($request, $response, $next) use ($app) {
    // Add PSR-7 Request DataCollector to toolbar
    $response = $app($request, $response);
    // Add PSR-7 Response DataCollector to toolbar
    return $response;
});
$debugMiddleware->run();
*/

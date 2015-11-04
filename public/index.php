<?php

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Zend\Expressive\Application $app */
$app = $container->get('Zend\Expressive\Application');
$app->run();

/*
$prophiler = new \Fabfuel\Prophiler\Profiler();
$toolbar = new \Fabfuel\Prophiler\Toolbar($prophiler);

$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
$response = new \Zend\Diactoros\Response();

$app = new \Zend\Stratigility\MiddlewarePipe();
$response = $app($request, $response);


$prophilerWrapper = new \Zend\Stratigility\MiddlewarePipe();
$prophilerWrapper->pipe(
    function ($req, $res, $next) use ($app) {
        /** @var \Interop\Container\ContainerInterface $container *
        $container = require 'config/container.php';

        /** @var \Zend\Expressive\Application $app *
        $app = $container->get('Zend\Expressive\Application');
        $response = $app->run($req, $res);
        // do what you want here
        return $response;

        // do what you want here
        return $response;
    }
);

function ($req, $res, $next) use ($app) {
    $response = $app->run($req, $res);
    // do what you want here
    return $response;
}
*/

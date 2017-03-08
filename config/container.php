<?php

declare(strict_types = 1);

use Zend\ServiceManager\ServiceManager;

// Load configuration
$config = require __DIR__ . '/config.php';

// Build container
$container = new ServiceManager($config['dependencies']);

// Inject config as a service
$container->setService('config', $config);

return $container;

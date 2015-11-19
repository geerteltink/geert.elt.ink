<?php

use Zend\ServiceManager\ServiceManager;

// Load configuration
$config = require __DIR__ . '/config.php';
$dependencies = $config['dependencies'];

// Inject config as a service
$dependencies['services']['config'] = $config;
$dependencies['services']['profiler'] = $profiler;

// Build container
$container = new ServiceManager($dependencies);

return $container;

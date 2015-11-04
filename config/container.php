<?php

use Zend\ServiceManager\ServiceManager;

// Load configuration
$config = require 'config.php';
$dependencies = $config['dependencies'];

// Inject config as a service
$dependencies['services']['config'] = $config;
//$dependencies['services']['prophiler'] = new \Fabfuel\Prophiler\Profiler();

// Build container
$container = new ServiceManager($dependencies);

return $container;

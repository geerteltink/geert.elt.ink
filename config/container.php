<?php

use Zend\ServiceManager\ServiceManager;

// Load configuration
$config       = require __DIR__ . '/config.php';
$dependencies = $config['dependencies'];

// Inject config as a service
$dependencies['services']['config'] = $config;

return new ServiceManager($dependencies);

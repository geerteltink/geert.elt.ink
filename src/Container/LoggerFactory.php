<?php

namespace App\Container;

use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $container)
    {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler('data/log/app.log', Logger::WARNING));

        return $logger;
    }
}

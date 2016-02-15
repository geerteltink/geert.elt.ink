<?php

namespace App\Container;

use Interop\Container\ContainerInterface;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

class LoggerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Logger
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        $logger = new Logger('app', [
            new StreamHandler('data/log/app.log', Logger::INFO)
        ], [
            new PsrLogMessageProcessor()
        ]);

        if (isset($config['monolog']['slack'])) {
            $slackHandler = new SlackHandler(
                $config['monolog']['slack']['token'],
                $config['monolog']['slack']['channel'],
                $config['monolog']['slack']['name']
            );
            $slackHandler->setLevel(Logger::ERROR);
            $logger->pushHandler($slackHandler);
        }

        return $logger;
    }
}

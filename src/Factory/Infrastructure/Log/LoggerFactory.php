<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Log;

use Monolog\Handler\SlackHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return LoggerInterface
     *
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @throws \Monolog\Handler\MissingExtensionException
     */
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $config = $container->get('config');

        $logger = new Logger('app', [
            new StreamHandler('data/log/app.log', Logger::INFO),
        ], [
            new PsrLogMessageProcessor(),
        ]);

        if (isset($config['monolog']['slack'])) {
            $slackHandler = new SlackHandler(
                $config['monolog']['slack']['token'],
                $config['monolog']['slack']['channel'],
                $config['monolog']['slack']['name']
            );
            $slackHandler->setLevel(Logger::NOTICE);
            $logger->pushHandler($slackHandler);
        }

        return $logger;
    }
}

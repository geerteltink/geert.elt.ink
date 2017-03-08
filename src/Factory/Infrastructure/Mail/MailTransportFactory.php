<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Mail;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Zend\Mail\Transport;

class MailTransportFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Transport\TransportInterface
     *
     * @throws \Interop\Container\Exception\NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \RuntimeException
     */
    public function __invoke(ContainerInterface $container): Transport\TransportInterface
    {
        $config  = $container->get('config');
        $config  = $config['mail']['transport'];
        $class   = $config['class'];
        $options = $config['options'];
        switch ($class) {
            case Transport\Sendmail::class:
            case 'sendmail':
                return new Transport\Sendmail();

            case Transport\Smtp::class:
            case 'smtp':
                $options = new Transport\SmtpOptions($options);

                return new Transport\Smtp($options);

            case Transport\File::class:
            case 'file':
                $options = new Transport\FileOptions($options);

                return new Transport\File($options);

            case Transport\InMemory::class:
                return new Transport\InMemory();

            default:
                throw new RuntimeException(sprintf('Unknown mail transport type "%s"', $class));
        }
    }
}

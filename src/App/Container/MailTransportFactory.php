<?php

namespace App\Container;

use Interop\Container\ContainerInterface;
use RuntimeException;

class MailTransportFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return \Swift_Mailer
     */
    public function __invoke(ContainerInterface $container)
    {
        $config  = $container->get('config');
        $config  = $config['mail']['transport'];
        $class   = $config['class'];
        $options = $config['options'];
        switch ($class) {
            case 'sendmail':
                $transport = \Swift_SendmailTransport::newInstance();
                break;
            case 'smtp':
                $transport = \Swift_SmtpTransport::newInstance($options['host'], $options['port'])
                    ->setUsername($options['username'])
                    ->setPassword($options['password']);
                break;
            default:
                throw new RuntimeException(sprintf(
                    'Unknown mail transport type "%s"',
                    $class
                ));
        }

        // Create the mailer using the transport
        return \Swift_Mailer::newInstance($transport);
    }
}

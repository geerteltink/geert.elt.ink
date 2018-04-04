<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Mail\Transport\TransportInterface;

class ContactHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ContactHandler
    {
        return new ContactHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(TransportInterface::class),
            $container->get(LoggerInterface::class),
            $container->get('config')['mail']
        );
    }
}

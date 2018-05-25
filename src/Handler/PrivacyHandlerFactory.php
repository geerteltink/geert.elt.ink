<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class PrivacyHandlerFactory
{
    public function __invoke(ContainerInterface $container) : PrivacyHandler
    {
        return new PrivacyHandler($container->get(TemplateRendererInterface::class));
    }
}

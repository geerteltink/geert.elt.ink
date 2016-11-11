<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Expressive\Template\TemplateRendererInterface;

class NotFoundHandlerFactory
{
    public function __invoke(ContainerInterface $container): NotFoundHandler
    {
        return new NotFoundHandler(
            $container->get(TemplateRendererInterface::class),
            new Response()
        );
    }
}

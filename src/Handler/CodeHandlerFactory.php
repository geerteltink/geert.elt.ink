<?php

declare(strict_types=1);

namespace App\Handler;

use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class CodeHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CodeHandler
    {
        return new CodeHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(Cache::class)
        );
    }
}

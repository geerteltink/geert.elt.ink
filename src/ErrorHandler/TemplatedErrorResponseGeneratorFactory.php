<?php

declare(strict_types = 1);

namespace App\ErrorHandler;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class TemplatedErrorResponseGeneratorFactory
{
    public function __invoke(ContainerInterface $container): TemplatedErrorResponseGenerator
    {
        $config = $container->get('config');

        return new TemplatedErrorResponseGenerator(
            $container->get(TemplateRendererInterface::class),
            $config['debug']
        );
    }
}

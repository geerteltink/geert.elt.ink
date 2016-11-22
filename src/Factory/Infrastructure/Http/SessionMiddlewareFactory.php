<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Http;

use Interop\Container\ContainerInterface;
use PSR7Session\Http\SessionMiddleware;
use Zend\ServiceManager\Factory\FactoryInterface;

class SessionMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SessionMiddleware
    {
        $config  = $container->has('config') ? $container->get('config') : [];
        $options = $config['session']['psr7'];

        return SessionMiddleware::fromSymmetricKeyDefaults(
            $options['signature_key'],
            $options['expiration_time']
        );
    }
}

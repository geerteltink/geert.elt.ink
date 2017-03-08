<?php

declare(strict_types = 1);

namespace App\Factory\Infrastructure\Http;

use Interop\Container\ContainerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

class SessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): SessionMiddleware
    {
        $config  = $container->has('config') ? $container->get('config') : [];
        $options = $config['session']['psr7'];

        return SessionMiddleware::fromSymmetricKeyDefaults(
            $options['signature_key'],
            $options['expiration_time']
        );
    }
}

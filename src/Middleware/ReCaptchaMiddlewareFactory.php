<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use ReCaptcha\ReCaptcha;

class ReCaptchaMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ReCaptchaMiddleware
    {
        $config = $container->get('config')['recaptcha'] ?? [];

        return new ReCaptchaMiddleware($container->get(ReCaptcha::class), $config['site_key']);
    }
}

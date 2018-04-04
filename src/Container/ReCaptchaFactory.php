<?php

declare(strict_types=1);

namespace App\Container;

use Psr\Container\ContainerInterface;
use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\Post;

class ReCaptchaFactory
{
    public function __invoke(ContainerInterface $container) : ReCaptcha
    {
        $config = $container->get('config')['recaptcha'] ?? [];

        return new ReCaptcha($config['secret_key'], new Post());
    }
}

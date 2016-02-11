<?php

namespace App\Middleware;

use Dflydev\FigCookies\SetCookie;
use Interop\Container\ContainerInterface;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use PSR7Session\Http\SessionMiddleware;
use Zend\ServiceManager\Factory\FactoryInterface;

class SessionMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $options = $config['session']['psr7'];

        return new SessionMiddleware(
            new Sha256(),
            $options['signature_key'],
            $options['verification_key'],
            SetCookie::create($options['cookie_name'])
                     ->withSecure($options['cookie_secure'])
                     ->withHttpOnly(true),
            new Parser(),
            $options['expiration_time']
        );
    }
}

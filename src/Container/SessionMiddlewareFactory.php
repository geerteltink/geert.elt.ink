<?php

declare(strict_types=1);

namespace App\Container;

use Dflydev\FigCookies\SetCookie;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Psr\Container\ContainerInterface;
use PSR7Sessions\Storageless\Http\SessionMiddleware;

class SessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : SessionMiddleware
    {
        $config = $container->get('config')['session'] ?? [];

        return new SessionMiddleware(
            new Sha256(),
            $config['signature_key'],
            $config['verification_key'],
            SetCookie::create($config['cookie_name'] ?? SessionMiddleware::DEFAULT_COOKIE)
                ->withSecure($config['cookie_secure'] ?? true)
                ->withHttpOnly(true)
                ->withPath('/'),
            new Parser(),
            $config['expiration_time'] ?? 1200,
            new SystemClock()
        );
    }
}

<?php

/**
 * PhpStorm Container Interop code completion
 *
 * Add code completion for container-interop.
 *
 * \App\ClassName::class will automatically resolve to it's own name.
 *
 * Custom strings like ``"cache"`` or ``"logger"`` need to be added manually.
 */
namespace PHPSTORM_META
{
    $STATIC_METHOD_TYPES = [
        \Interop\Container\ContainerInterface::get('') => [
            'config' instanceof \ArrayObject,
            'logger' instanceof \Psr\Log\LoggerInterface,
            '' == '@',
        ],

        \Psr\Http\Message\ServerRequestInterface::getAttribute('') => [
            \PSR7Session\Http\SessionMiddleware::SESSION_ATTRIBUTE instanceof \PSR7Session\Session\SessionInterface,
        ],
    ];
}

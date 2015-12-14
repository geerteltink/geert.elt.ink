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
            "cache" instanceof \Stash\Pool,
            "logger" instanceof \Monolog\Logger,
            "" == "@",
        ],
    ];
}

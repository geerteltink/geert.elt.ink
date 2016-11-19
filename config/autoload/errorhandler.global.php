<?php

declare(strict_types = 1);

return [
    'dependencies' => [
        'invokables' => [
            //'Zend\Expressive\Whoops' => Whoops\Run::class,
            //'Zend\Expressive\WhoopsPageHandler' => Whoops\Handler\PrettyPageHandler::class,
        ],
        'factories'  => [
            Zend\Expressive\Middleware\NotFoundHandler::class =>
                Zend\Expressive\Container\NotFoundHandlerFactory::class,

            Zend\Stratigility\Middleware\ErrorHandler::class =>
                App\Factory\Infrastructure\ErrorHandler\ErrorHandlerFactory::class,
                //Zend\Expressive\Container\ErrorHandlerFactory::class,

            Zend\Expressive\Middleware\ErrorResponseGenerator::class =>
                Zend\Expressive\Container\ErrorResponseGeneratorFactory::class,
                //Zend\Expressive\Container\WhoopsErrorResponseGeneratorFactory::class,
        ],
    ],
];

<?php

declare(strict_types = 1);

return [
    'dependencies' => [
        'invokables' => [
            'Zend\Expressive\FinalHandler' => Zend\Stratigility\NoopFinalHandler::class,

            //'Zend\Expressive\Whoops' => Whoops\Run::class,
            //'Zend\Expressive\WhoopsPageHandler' => Whoops\Handler\PrettyPageHandler::class,
        ],
        'factories'  => [
            //'Zend\Expressive\FinalHandler' => Zend\Expressive\Container\WhoopsErrorHandlerFactory::class,

            App\ErrorHandler\NotFoundHandler::class                 => App\ErrorHandler\NotFoundHandlerFactory::class,
            App\ErrorHandler\TemplatedErrorResponseGenerator::class => App\ErrorHandler\TemplatedErrorResponseGeneratorFactory::class,
            Zend\Stratigility\Middleware\ErrorHandler::class        => App\ErrorHandler\ErrorHandlerFactory::class,
        ],
    ],
];

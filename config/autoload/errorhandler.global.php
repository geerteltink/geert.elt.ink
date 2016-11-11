<?php

declare(strict_types = 1);

return [
    'dependencies' => [
        'invokables' => [
            'Zend\Expressive\FinalHandler' => Zend\Stratigility\NoopFinalHandler::class,
        ],
        'factories'  => [
            App\ErrorHandler\NotFoundHandler::class =>
                App\ErrorHandler\NotFoundHandlerFactory::class,

            App\ErrorHandler\TemplatedErrorResponseGenerator::class =>
                App\ErrorHandler\TemplatedErrorResponseGeneratorFactory::class,

            Zend\Stratigility\Middleware\ErrorHandler::class =>
                App\ErrorHandler\ErrorHandlerFactory::class,
        ],
    ],
];

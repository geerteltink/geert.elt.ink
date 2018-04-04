<?php

declare(strict_types=1);

use Xtreamwayz\HTMLFormValidator\InputFilterFactory;
use Xtreamwayz\HTMLFormValidator\Validator;

return [
    'dependencies' => [
        'factories' => [
            Zend\InputFilter\Factory::class => InputFilterFactory::class,
        ],
    ],

    'zend-inputfilter' => [
        'validators' => [
            // Attach custom validators or override standard validators
            'invokables' => [
                'recaptcha' => Validator\RecaptchaValidator::class,
            ],
        ],
        'filters'    => [
            // Attach custom filters or override standard filters
            'invokables' => [
            ],
        ],
    ],
];

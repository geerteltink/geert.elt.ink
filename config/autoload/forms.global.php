<?php

return [
    'dependencies' => [
        'invokables' => [
        ],
        'factories'  => [
            Zend\InputFilter\Factory::class => Xtreamwayz\HTMLFormValidator\InputFilterFactory::class,
        ],
    ],

    'zend-inputfilter' => [
        'validators' => [
            // Attach custom validators or override standard validators
            'invokables' => [
                'recaptcha' => Xtreamwayz\HTMLFormValidator\Validator\RecaptchaValidator::class,
            ],
        ],
        'filters'    => [
            // Attach custom filters or override standard filters
            'invokables' => [
            ],
        ],
    ],
];

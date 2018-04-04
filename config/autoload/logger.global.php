<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return [
    'monolog' => [
        'handlers' => [
            [
                'type'   => StreamHandler::class,
                'stream' => 'data/log/app-{date}.log',
                'level'  => Logger::DEBUG,
            ],
        ],
    ],
];

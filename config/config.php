<?php

return [
    'slim' => [
        'debug' => false,
    ],
    'twig' => [
        'template_directory' => APP_PATH . '/views',
        'environment' => [
            'charset' => 'utf-8',
            'cache' => APP_PATH . '/cache/twig',
            'auto_reload' => true,
            'strict_variables' => true,
            'autoescape' => 'name',
            'debug' => false
        ]
    ],
    'monolog' => [
        'level' => 'ERROR',
        'path' => APP_PATH . '/log/app.log'
    ]
];
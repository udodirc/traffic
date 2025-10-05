<?php
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__), // mlm/console
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'db' => require(__DIR__ . '/../../common/config/db.php'),
        'errorHandler' => [
            'class' => yii\console\ErrorHandler::class,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
];
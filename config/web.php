<?php

$params = require(__DIR__ . '/params.php');

$config = yii\helpers\ArrayHelper::merge([
        'id' => 'basic',
        'basePath' => dirname(__DIR__),
        'bootstrap' => ['log'],
        'components' => [
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'KzZXK2hprO2Ct0epy11Dno3X4pw2oq_Q',
            ],
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
            'user' => [
                'identityClass' => 'app\models\User',
                'enableAutoLogin' => true,
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                // send all mails to a file by default. You have to set
                // 'useFileTransport' to false and configure a transport
                // for the mailer to send real emails.
                'useFileTransport' => true,
            ],
            'log' => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets' => [
                    [
                        'class' => 'yii\log\FileTarget',
                        'levels' => ['error', 'warning'],
                    ],
                ],
            ],
            'db' => [
                'class' => 'yii\db\Connection',
                'charset' => 'utf8',
            ],
        ],
        'params' => $params,
    ],
    require(__DIR__.'/sensitive.php')
);
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

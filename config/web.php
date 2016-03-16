<?php

$params = require(__DIR__ . '/params.php');

$config = yii\helpers\ArrayHelper::merge([
        'id' => 'basic',
        'basePath' => dirname(__DIR__),
        'bootstrap' => ['log'],
        'components' => [
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
            'urlManager' => [
                'class' => yii\web\UrlManager::className(),
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [
                    '<controller:\w+>/<id:\d+>' => '<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                ],
            ],
            'db' => [
                'class' => 'yii\db\Connection',
                'charset' => 'utf8',
            ],
            'assetManager' => [
                'bundles' => [
                    'app\widgets\MapsAsset' => [
                        'key' => 'AIzaSyBEr0tOImJExGdG9hriZazaa1zgZbLhu7Y',
                    ],
                ]
            ],
            'currencyConverter' => [
                'class' => 'app\components\MockCurrencyConverter',
                'rates' => [
                    'EUR' => 1,
                    'CHF' => 0.91,
                    'HRK' => 0.13,
                    'USD' => 0.9,
                ],
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

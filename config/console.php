<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');

$config = yii\helpers\ArrayHelper::merge([
        'id' => 'basic-console',
        'basePath' => dirname(__DIR__),
        'bootstrap' => ['log', 'gii'],
        'controllerNamespace' => 'app\commands',
        'modules' => [
            'gii' => 'yii\gii\Module',
        ],
        'components' => [
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],
            'log' => [
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

// Unfortunately console app Request is different than web app Request, cannot use same config
$config['components']['request'] = [];

return $config;

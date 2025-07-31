<?php

$config = [
    'id' => 'shortener',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'runtimePath' => dirname(__DIR__) . '/runtime',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@web' => '/',
        '@webroot' => __DIR__ . '/../web',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'your-secret-key-here',
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
        'db' => require __DIR__ . '/db.php',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<code:[a-zA-Z]{6}>' => 'site/redirect',
                'api/shorten' => 'api/shorten',
            ],
        ],
        'botDetector' => [
            'class' => \app\components\BotDetector::class,
        ],
    ],
    'params' => [],
];

return $config;
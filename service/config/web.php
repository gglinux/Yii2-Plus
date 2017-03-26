<?php

$params = require(__DIR__ . '/params.php');

$ENV_CONFIG_PATH = __DIR__.'/'.YII_ENV; //当前环境配置所在目录

Yii::setAlias('@service', dirname(dirname(__DIR__)) . '/service');
Yii::setAlias('@common', dirname(dirname(__DIR__)) . '/common');

$config = [
    'id' => 'service',
    'basePath' => dirname(__DIR__),
    'timeZone'=>'Asia/Chongqing',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'service\controllers',
    'bootstrap' => ['log'],
    'components' => array_merge([
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'true',
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'api\models\User',
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
            'targets' => require($ENV_CONFIG_PATH.'/log.php'),
        ],
        'redis' => require(__DIR__ . '/redis.php'),
        // 'db' => require(__DIR__ . '/hjsk_db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => require('url.php'),
        ],
    ], require($ENV_CONFIG_PATH.'/components.php')),


    'modules' => [
        'user' => [
            'class' => 'service\modules\user\Module',
        ],
        'room' => [
            'class' => 'service\modules\room\Module',
        ],
        'match' => [
            'class' => 'service\modules\match\Module',
        ]
    ],
    'params' => $params,
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '11.11.11.1', '192.168.11.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1','::1','11.11.11.1', '192.168.0.*', '192.168.11.1' ],
    ];
}
//var_dump($config);exit();
return $config;

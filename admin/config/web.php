<?php

$ENV_CONFIG_PATH = __DIR__.'/'.YII_ENV; //当前环境配置所在目录

$params = require($ENV_CONFIG_PATH . '/params.php');

Yii::setAlias('@admin', dirname(dirname(__DIR__)) . '/admin');
Yii::setAlias('@common', dirname(dirname(__DIR__)) . '/common');

$config = [
    'id' => 'admin',
    'basePath' => dirname(__DIR__),
    'timeZone'=>'Asia/Chongqing',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['log'],
    'defaultRoute' => '/config/role',
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
            'identityClass' => 'admin\models\User',
            'enableAutoLogin' => true,
        ],
        'response' => [
            'class' => 'common\components\Response',
        ],

        'errorHandler' => [
            'errorAction' => '/config/role/error-json',
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
        //路由服务
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require('url.php'),
        ],

    ], require($ENV_CONFIG_PATH.'/components.php')),
    'modules' => require(__DIR__ . '/modules.php'),
    'params' => require($ENV_CONFIG_PATH . '/params.php'),
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
return $config;

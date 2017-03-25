<?php

$params = require(__DIR__ . '/params.php');
Yii::setAlias('@service', dirname(dirname(__DIR__)) . '/service');
Yii::setAlias('@common', dirname(dirname(__DIR__)) . '/common');

$config = [
    'id' => 'service',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'controllerNamespace' => 'service\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'true',
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
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'hjsk_db' => require(__DIR__ . '/hjsk_db.php'),
        'redis' => require(__DIR__ . '/redis.php'),
        // 'db' => require(__DIR__ . '/hjsk_db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                'user'=>'/user/login/index',
                'room'=>'room/index'
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'service\modules\user\Module',
        ]
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

return $config;

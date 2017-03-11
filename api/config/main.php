<?php

$ENV_CONFIG_PATH = __DIR__.'/'.YII_ENV; //当前环境配置所在目录

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'user' => 'app\modules\user\Module',
        'project' => 'app\modules\project\Module',
        'order' =>'app\modules\order\Module',
        'common' =>'app\modules\common\Module',
    ],
    'timeZone'=>'Asia/Chongqing',
    'components' => array_merge([
        'response' => [
            'class' => 'jfz\components\Response',
            'errorManager' => 'errorManager',
        ],
        'errorManager' => [
            'class' => 'jfz\components\ErrorManager'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '1LafUEESUoCk__FXRF4iZ9LE4Wk4DcWu',
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\components\jid\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            //'suffix' => '.html',
            'rules' => require('url.php'),
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => 0, //YII_DEBUG ? 3 : 0,
            'targets' => require($ENV_CONFIG_PATH.'/log.php'),
        ],
        'restrict'=>[
            'class'=>'jfz\restrict\QosLimit',
            'redis'=>'redis',
            'enable'=>true,
            'prefix'=>'restrict',
            'bucket' => [
                'smsSendMessage'=>[
                    'prefix'=>'smsSendMessage',
                    'maxSize'=>3,
                    'initSize'=>2,
                    'rate'=>90
                ]
            ]
        ],
        'Curl' => [
            'class' => 'jfz\components\Curl',
            'options' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CONNECTTIMEOUT => 3,
                CURLOPT_TIMEOUT => 3,
            ],
        ],
    ], require($ENV_CONFIG_PATH.'/components.php')),
    'params' => array_merge([
        'payCert'=>realpath(__DIR__.'/certificates/lianlian.pem'),  //连连支付公钥地址
    ], require($ENV_CONFIG_PATH.'/params.php')),
];
if (YII_ENV != 'prod') {
    // configuration adjustments for 'dev' environment

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class'=>'yii\debug\Module',
        'allowedIPs'=>['127.0.0.1', '::1', '192.168.10.1']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
        'allowedIPs'=>['127.0.0.1']
    ];

    $config['modules']['jid'] = [
        'class'=>'app\components\jid\Module',
        'password'=>'gxq',
        'ipFilters'=>['*','::1'],
        'name'=>'三板斧webservice接口调试系统',
        'loginConfig'=>[
            'loginUrl'=>'https://passport.jinfuzi.me/service/login',
            'fieldMapping'=>[
                'account'=>'account',
                'type'   => 'type',
                'password'=>'password',
                'platform'=>'c_platform',
                'version'=>'c_version'
            ],
            'c_identity'=>'jidinvoke',
            'passwordHashUrl'=>'',
        ],
        'signUrl'=>'/jid/default/signature',
        'secretKey'=>[
            '1_1.0.0'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
            '2_1.0.0'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
            '3_1.0.0'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
            '1_1.1.0'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
            '2_1.1.0'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
            '3_1.1.0'=>'dwZgNb7W9NCx8hHA3yBxxLEzpUk=',
        ]
    ];
}

return $config;

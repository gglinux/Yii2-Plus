<?php
return [
    'thrift'=>[
        'class'=>'jfz\thrift\Client',
        'genDir'=>'@app/dao/thrift/gen',
        'serviceConfig'=>require(__DIR__.DIRECTORY_SEPARATOR.'thrift.php')
    ],
    'equityFundThrift' => [
        'class'=>'jfz\thrift\Client',
        'genDir'=>'@app/dao/equityFundThrift/gen',
        'serviceConfig'=>require(__DIR__.DIRECTORY_SEPARATOR.'equityFundThrift.php')
    ],
    'uicThrift' => [
        'class' => 'jfz\thrift\Client',
        'genDir' => '@app/dao/uicThrift/gen',
        'serviceConfig' => [
            'ThriftUserInfoService' => [
                'class'             => '\userinfocenter\ThriftUserInfoServiceClient',
                'serverHost'        => '192.168.1.251',
                'serverPort'        => '50599',
                'sendTimeout'       => 30,
                'recvTimeout'       => 30,
                'maxConnectTimes'   => 2,
            ],
        ]
    ],
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.1.243;dbname=crowdfund',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
        'tablePrefix' => 'jfz_',
        'emulatePrepare' => true,
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '192.168.1.251',
        'port' => 6379,
        'database' => 1,
    ],
    'ocs' => [
        'class' => 'app\components\Cache',
        'keyPrefix' => 'jfz.PASSPORT',
        'hashKey' => false,
        'serializer' => ['\yii\helpers\Json::encode', '\yii\helpers\Json::decode'],
        'redis' => 'redis'
    ],
];
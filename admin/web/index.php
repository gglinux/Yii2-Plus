<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', $_SERVER['RUNTIME_ENVIROMENT']=='DEV');
/**
 * YII_ENV defined in http server soft, DEV-开发环境/PREP-预发环境/PROD-线上环境
 */
defined('YII_ENV') or define('YII_ENV', $_SERVER['RUNTIME_ENVIROMENT']);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
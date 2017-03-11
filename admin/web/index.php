<?php
define('YII_REQUEST_START_TIME',microtime(true));
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', $_SERVER['YII_DEBUG']==='true');
/**
 * YII_ENV defined in http server soft, dev-开发环境/prep-预发环境/prod-线上环境
 */
defined('YII_ENV') or define('YII_ENV', $_SERVER['YII_ENV']);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/main.php');

(new yii\web\Application($config))->run();

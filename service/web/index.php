<?php
// comment out the following two lines when deployed to production
define('YII_REQUEST_START_TIME',microtime(true));
// 定义定义开发环境类型，请在服务器环境变量中设置
$_SERVER['RUNTIME_ENVIROMENT'] = 'dev';
defined('YII_DEBUG') or define('YII_DEBUG', $_SERVER['RUNTIME_ENVIROMENT']=='dev');
defined('YII_ENV_DEV') or define('YII_ENV_DEV', $_SERVER['RUNTIME_ENVIROMENT']=='dev');
/**
 * YII_ENV defined in http server soft, dev-开发环境/prep-预发环境/prod-线上环境
 */

defined('YII_ENV') or define('YII_ENV', $_SERVER['RUNTIME_ENVIROMENT']);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
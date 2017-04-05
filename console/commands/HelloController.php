<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\commands;
use yii\console\Controller;
use service\modules\common\services\DownloadService;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    /**
     * 队列使用实例
     * ./yii hello/download
     */
    public function actionDownload()
    {

        \Yii::$app->queue->push(new DownloadService([
            'url' => 'http://gglinux.com/images/dog.png',
            'file' => '/vagrant/tmp/dog.png',
        ]));
    }
}


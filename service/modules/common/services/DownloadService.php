<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/4/5
 * Time: 上午10:51
 */

namespace service\modules\common\services;

use yii\base\Object;

class DownloadService extends Object implements \zhuravljov\yii\queue\Job
{
    public $url;
    public $file;

    public function run()
    {
        file_put_contents($this->file, file_get_contents($this->url));
    }

    /**
     * RPC 下载服务
     * @param $url
     * @param $file
     */
    public function downloadJob($url, $file)
    {
        \Yii::$app->queue->push(new DownloadService([
            'url' => $url,
            'file' => $file
        ]));
    }
}
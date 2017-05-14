<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/5/14
 * Time: 下午2:00
 */
namespace service\modules\common\controllers;


use Yii;
use service\base\ServiceController;
use service\modules\common\services\DownloadService;
use Hprose\Yii\Server;


class DownloadController extends ServiceController
{

    public function actionIndex()
    {
        $service = new DownloadService();
        $server = new Server();
        $server->add("downloadJob", $service);
        return $server->start();

    }

}
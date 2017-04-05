<?php

namespace service\modules\common\controllers;

use service\modules\common\services\DownloadService;
use Hprose\Http\Server;
use service\base\ServiceController;

/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class SiteController
 * @package service\controllers
 */
class TestController extends ServiceController
{

    /**
     * RPC 推送服务
     * Service 入口文件
     * @return string
     */
    public function actionIndex()
    {
        $service = new DownloadService();
        $server = new Server();
        $server->add('pushByPushtoken', $service);
        $server->add("pushByUid", $service);
        $server->add("downloadJob", $service);
        return $server->start();
    }

    
   

   
}

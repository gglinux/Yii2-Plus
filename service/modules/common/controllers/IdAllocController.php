<?php

namespace service\modules\common\controllers;

use service\base\ServiceController;
use Hprose\Http\Server;
use service\modules\common\services\IdAllocService;

/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class SiteController
 * @package service\controllers
 */
class IdAllocController extends ServiceController
{

    /**
     * Service 入口文件
     * @return string
     */
    public function actionIndex()
    {
        $server = new Server();
        $anObject = new IdAllocService();
        $server->addMethods([
            'allocId',
            'getCurrentId',
        ], $anObject);
        return $server->start();
    }

    
   

   
}

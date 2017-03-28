<?php

namespace service\modules\common\controllers;

use Yii;
use service\base\ServiceController;
use service\modules\common\services\CommonService;

/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class SiteController
 * @package service\controllers
 */
class TestController extends ServiceController
{

    /**
     * Service 入口文件
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $client = CommonService::serviceClient("/common/id-alloc", 'php');
        $ret = $client->getCurrentId('roomid');
        return $ret;
    }

    
   

   
}

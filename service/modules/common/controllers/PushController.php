<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/28
 * Time: 下午3:45
 */

namespace service\modules\common\controllers;


use Hprose\Yii\Server;
use service\base\ServiceController;
use service\modules\common\services\PushService;

class PushController extends ServiceController
{
    /**
     * RPC 对外推送服务
     * @return mixed
     */
    public function actionIndex()
    {
        $service = new PushService();
        $server = new Server();
        $server->add('pushByPushtoken', $service);
        $server->add("pushByUid", $service);
        return $server->start();
    }


}
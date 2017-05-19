<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/28
 * Time: 下午3:45
 */

namespace service\modules\push\controllers;


use Hprose\Yii\Server;
use service\base\ServiceController;
use service\modules\push\service\PushService;
use service\components\AccessLogFilter;

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
        $server->add('pushByPushtoken', $service)->addFilter(new AccessLogFilter());
        return $server->start();
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/28
 * Time: 下午3:55
 */

namespace service\modules\common\services;


use common\othersdk\getui\Push;
use service\base\BaseService;

class PushService extends BaseService
{
    /**
     * 单条信息推送
     */
    public function pushSingle($pushtoken, $data)
    {
        $pushconfig = \Yii::$app->params['getui'];
        $result = Push::getinstance($pushtoken['appkey'],$pushconfig['appid'],$pushtoken['appsecret'])->batch($pushtoken, $data);
        \Yii::info("PUSH-SERVICE-SINGLIE:result=" . json_encode($result) . "|pushtoken={$pushtoken}|".json_encode($data));
        return $result;
    }

    /**
     * 批量推送
     * 经过队列
     */
    public function pushBatch($pushtokens, $data)
    {
        $return = [];




        return $return;
    }

    /**
     *
     */
    public function queueAdd()
    {

    }

}
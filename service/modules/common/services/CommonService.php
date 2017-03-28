<?php

namespace service\modules\common\services;

use Yii;
use service\base\BaseService;


/**
 * Class CommonService
 * @package service\models
 */
class  CommonService extends BaseService
{

    public static function serviceClient($serviceName='', $serviceType = 'php', $host = ''){

        if(strcasecmp($serviceType,'php') == 0) {
            $host = Yii::$app->params['HprosePHPServiceHost'];
        } else if(strcasecmp($serviceType,'node') == 0) {
            $host = Yii::$app->params['HproseNodeServiceHost'];
        } else {

        }
        $client = new \Hprose\Http\Client($host . "/$serviceName", false);
        return $client;
    }

}
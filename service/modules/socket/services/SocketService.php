<?php

namespace service\modules\socket\services;

use Yii;


use service\modules\socket\models\ar\Socket;

use common\base\Exception;
use common\base\BaseService;

/**
 * Class SocketService
 * Socket数据相关
 * @package service\models
 */
class SocketService extends BaseService
{
    /**
     * 存储用户Socket id
     * @param array $arrUserClientInfo
     * @throws
     * @return boolean
     */

    public function updateUserClientSocketId($arrUserClientInfo)
    {

        if(!is_array($arrUserClientInfo) || empty($arrUserClientInfo)){
            throw new Exception('参数错误');
        }

        if(!isset($arrUserClientInfo['user_id']) || !isset($arrUserClientInfo['socket_id'])){
            throw new Exception('参数错误');
        }

        Socket::setUserSocketInfo($arrUserClientInfo);
        
        return true;

    }


   
}
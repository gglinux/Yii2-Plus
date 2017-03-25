<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/25
 * Time: 下午2:36
 */

namespace service\modules\user\services;

use common\base\Exception;
use service\base\BaseService;
use service\modules\user\models\User;
use common\helpers\CommFunction;

class UserService extends BaseService
{

    public function registerthrid($uuid, $headicon, $way, $otherParams = array())
    {
        $userModel = new User();
        if (empty($uuid) || empty($way)) {
            throw new Exception('uuid或注册方式为空');
        }
        if ($userModel->isExistUuid($uuid, $way)) {
            throw new Exception('已经存在该注册方式下的唯一标识符');
        }
        $uid = $userModel->genUserUniqueId($uuid,$way);
        if ($uid) {
            throw new Exception('uid生成失败！');
        }
        $userinfo = $userModel->register($uid, $headicon, $otherParams);
        //第三方登陆，生成账号
        $userModel->genUserAccout($uid);
        if ( $userinfo ) {
            return $userinfo;
        }
    }

    public function registertrad($phone,$password,$otherParams = array())
    {
        $userModel = new User();
        if (empty($phone) || empty($password)) {
            throw new Exception('手机号码或者账号或为空');
        }
        $tradRegisterWay = 0;

        if (CommFunction::isPhoneNum($phone)) {
            $tradRegisterWay = 1;
        } elseif(CommFunction::isEmailAddress($phone)) {
            $tradRegisterWay = 2;
        }
    }
}
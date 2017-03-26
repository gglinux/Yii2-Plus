<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/25
 * Time: 下午2:36
 */

namespace service\modules\user\services;

use service\base\ServiceException;
use service\base\BaseService;
use service\modules\user\models\User;
use common\helpers\CommFunction;

class UserService extends BaseService
{

    public function registerthrid($uuid, $headicon, $way, $otherParams = array())
    {
        $userModel = new User();
        if (empty($uuid) || empty($way)) {
            throw new ServiceException('uuid或注册方式为空');
        }
        if ($userModel->isExistUuid($uuid, $way)) {
            throw new ServiceException('已经存在该注册方式下的唯一标识符');
        }
        $uid = $userModel->genUserUniqueId($uuid,$way);
        if ($uid) {
            throw new ServiceException('uid生成失败！');
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
            throw new ServiceException('手机号码或者账号或为空');
        }
        $tradRegisterWay = 0;

        if (CommFunction::isPhoneNum($phone)) {
            $tradRegisterWay = 1;
        } elseif(CommFunction::isEmailAddress($phone)) {
            $tradRegisterWay = 2;
        } elseif (CommFunction::isHjskAccout($phone)) {
            $tradRegisterWay = 3;
        }
        if ($tradRegisterWay == 0) {
            throw  new ServiceException("非账号，邮箱，手机号码方式注册！");
        }
    }
}
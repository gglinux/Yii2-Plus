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
use service\modules\user\models\ar\UserAuth;
use service\modules\user\models\User;
use common\helpers\CommFunction;

class UserService extends BaseService
{

    /**
     * @param $uuid
     * @param $way
     * @param string $toekn
     * @param array $otherParams
     * @return array|null|\yii\db\ActiveRecord
     * @throws ServiceException
     * 注册成功：返回用户信息
     * 注册失败：返回空数组
     */
    public function registerThrid($uuid, $way, $toekn = '',$otherParams = array())
    {
        $userModel = new User();
        if (empty($uuid) || empty($way)) {
            throw new ServiceException('uuid或注册方式为空');
        }
        if ($userModel->getAuthInfo($uuid, $way)) {
            throw new ServiceException('已经存在该注册方式下的唯一标识符');
        }
        $uid = $userModel->genUserUniqueId();
        if ($uid) {
            throw new ServiceException('uid生成失败！');
        }
        $userModel->register($uid, $uuid, $toekn, $way, $otherParams);
        $userinfo = $userModel->getUserInfoByUid($uid);
        //第三方登陆，生成账号
        $userModel->updateUserAccout($uid);
        //
        if ( $userinfo ) {
            return $userinfo;
        }
        return [];
    }

    /**
     * 传统方式注册
     * @param $phone
     * @param $password
     * @param array $otherParams
     * @return array|null|\yii\db\ActiveRecord
     * @throws ServiceException
     * 注册成功：返回用户信息
     * 注册失败：返回空
     */
    public function registerTrad($phone,$password,$otherParams = array())
    {
        $userModel = new User();
        if (empty($phone) || empty($password)) {
            throw new ServiceException('手机号码或者账号为空',20000);
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
            throw  new ServiceException("非账号，邮箱，手机号码方式注册！",20001);
        }
        if ($userModel->getAuthInfo($phone, $tradRegisterWay)) {
            throw new ServiceException('已经存在该注册方式下的唯一账号',20002);
        }
        $uid = $userModel->genUserUniqueId();
        //非账号登陆，生成唯一账号
        if ($tradRegisterWay != 3) {
            $userModel->updateUserAccout($uid);
        }
        $userModel->register($uid, $phone, $password,$tradRegisterWay,$otherParams);
        return $userModel->getUserInfoByUid($uid);
    }

    /**
     * 第三方登陆
     * @param $uuid
     * @param $token
     * @param $way
     * @param array $otherParams
     * @return array|null|\yii\db\ActiveRecord
     * 登陆成功：返回用户信息
     * 登陆失败：返回空
     */
    public function loginThrid($uuid, $token, $way,  $otherParams = array())
    {
        $userModel = new User();
        $userAuthInfo = $userModel->getAuthInfo($uuid, $way);
        if ($userAuthInfo['uid']) {
            return $userModel->getUserInfoByUid($userAuthInfo['uid']);
        }
        return $this->registerThrid($uuid, $token, $way, $otherParams);
    }

    /**
     * 手机号码，邮箱，账号登陆
     * @param $phone
     * @param $password
     * @param array $otherParams
     * @return array|null|\yii\db\ActiveRecord
     * 登陆成功：返回用户信息
     * 登陆失败：返回空数组
     *
     */
    public function loginTrad($phone,$password,$otherParams = array())
    {
        $userModel = new User();
        $tradRegisterWay = 0;

        if (CommFunction::isPhoneNum($phone)) {
            $tradRegisterWay = 1;
        } elseif(CommFunction::isEmailAddress($phone)) {
            $tradRegisterWay = 2;
        } elseif (CommFunction::isHjskAccout($phone)) {
            $tradRegisterWay = 3;
        }
        if ($tradRegisterWay == 0) {
            throw  new ServiceException("非账号，邮箱，手机号码方式注册！",20001);
        }
        $userAuthInfo = $userModel->getAuthInfo($phone, $tradRegisterWay,$password);
        if ($userAuthInfo['uid']) {
            return $userModel->getUserInfoByUid($userAuthInfo['uid']);
        } else {
            throw  new ServiceException("账号或者密码错误！",20003);
        }
    }
}
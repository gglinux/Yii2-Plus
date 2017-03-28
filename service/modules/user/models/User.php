<?php

namespace service\modules\user\models;

use common\base\Exception;
use service\modules\room\models\ar\IdAlloc;
use service\modules\user\models\ar\UserRegisterLog;
use service\modules\user\models\ar\UserBase;
use service\modules\user\models\ar\UserAuth;
use service\modules\user\models\ar\UserExtra;
use service\base\ServiceModel;

class User extends ServiceModel
{

    const salt = 'happy.mingwei.jinya.huangqi.jiawei.jide.jingjing';
    const REDIS_KEY_USER_EXTEND_INFO = 'user_extend_info_';
    /**
     * @param $uid 用户唯一ID
     * @param $identifier 账号或者用户名
     * @param $certificate 密码凭证
     * @param $identity_type 注册方式
     * @param array $other 其他参数
     * @return array|null|\yii\db\ActiveRecord
     */
    public function register($uid, $identifier,$certificate, $identity_type, $other = [])
    {
        $userAuth = new UserAuth();
        $time_now = time();

        $userAuth->uid = $uid;
        $userAuth->identifier = $identifier;
        $userAuth->certificate = $certificate;
        $userAuth->identity_type = $identity_type;
        $userAuth->create_time = $time_now;
        $userAuth->update_time = $time_now;
        $userAuth->insert();


        $this->_updateResterLog($uid, $identity_type, $other);
        $this->_updateExtraData($uid, $other);
        return $this->_updateUserBase($uid,$other);
    }
    /**
     * 获取用户信息
     * @param $uid
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getUserInfoByUid($uid)
    {
        return UserBase::find()->where(['uid' => $uid])->asArray()->one();
    }

    public function _updateUserBase($uid, $other)
    {
        $userBase = new UserBase();
        $time_now = time();
        $table_keys = array_keys($userBase->attributeLabels());
        $userBase->uid = $uid;
        $userBase->create_time = $time_now;
        $userBase->update_time = $time_now;
        foreach ($table_keys as $value) {
            if ($other[$value]) {
                $userBase->$value = $other[$value];
            }
        }
        return $userBase->save();
    }

    public function _updateResterLog($uid, $register_way, $other)
    {
        $regisertLog = new UserRegisterLog();
        $regisertLog->uid = $uid;
        $regisertLog->register_time = time();
        $regisertLog->register_method =
        $regisertLog->register_ip = $other['client_ip'];
        $regisertLog->register_client = $other['client_flag'];
        $regisertLog->register_method = $register_way;
        return $regisertLog->save();
    }

    private function _updateExtraData($uid, $other)
    {
        $userExtra = new UserExtra();
        $userExtra->uid = $uid;
        $userExtra->create_time = time();
        $userExtra->update_time = time();
        $table_keys = array_keys($userExtra->attributeLabels());
        foreach ($table_keys as $value) {
            if ($other[$value]) {
                $userExtra->$value = $other[$value];
            }
        }
        return $userExtra->save();
    }

    public function genJWTToken()
    {

    }

    public function getAuthInfo($identifier, $way, $password = '')
    {
        $where = ['identifier'=>$identifier,'identity_type'=>(int)$way ];
        if (!empty($password)) {
            $where['identifier'] = $password;
        }
        $result = UserAuth::find()->where($where)->asArray()->one();
        if ($result['id']) {
            return $result;
        }
        return false;
    }

    /**
     * 生成唯一ID
     * @return mixed
     */
    public function genUserUniqueId()
    {
        $userId = IdAlloc::allocId('userid');
        return $userId;
    }

    public function updateUserAccout($uid)
    {
        $account = 'lp_'.substr( md5($uid ),0,10);
        return UserBase::updateAll(['user_name' => $account], "uid = $uid");
    }

    public function loginThird($uuid)
    {

    }

    /**
     * 获取redis实例
     * @return mixed
     */
    public static function getRedis() {
        return \Yii::$app->redis;
    }

    /**
     * 设置用户扩展属性
     * @param $arrUserExtendInfo
     * @return mixed
     * @throws Exception
     */
    public static function setUserExtendInfo ($arrUserExtendInfo) {
        if (empty($arrUserExtendInfo)
            || !isset($arrUserExtendInfo['user_id'])
            || !isset($arrUserExtendInfo['field'])
            || !isset($arrUserExtendInfo['value'])) {
            throw new Exception("参数错误");
        }

        $redis = self::getRedis();
        $strRedisKey = self::REDIS_KEY_USER_EXTEND_INFO . $arrUserExtendInfo['user_id'];
        $strField =  $arrUserExtendInfo['field'];
        $strValue = serialize($arrUserExtendInfo['value']);
        return $redis->hset($strRedisKey, $strField, $strValue);
    }

    /**
     * 获取用户扩展属性
     * @param $arrUserExtendInfo
     * @return mixed
     * @throws Exception
     */
    public static function getUserExtendInfo ($arrUserExtendFieldInfo) {
        if (empty($arrUserExtendFieldInfo)
            || !isset($arrUserExtendFieldInfo['user_id'])
            || !isset($arrUserExtendFieldInfo['field'])) {
            throw new Exception("参数错误");
        }

        $redis = self::getRedis();
        $strRedisKey = self::REDIS_KEY_USER_EXTEND_INFO . $arrUserExtendFieldInfo['user_id'];
        $strField =  $arrUserExtendFieldInfo['field'];
        if($strField == "*") {
            $arrUserExtendInfo =  $redis->hgetall($strRedisKey, $strField);
        } else {
            $arrUserExtendInfo = $redis->hget($strRedisKey, $strField, $strField);
        }

        var_dump($arrUserExtendInfo);
        return $arrUserExtendInfo;


    }
}
<?php

namespace service\modules\user\models;

use service\modules\room\models\ar\IdAlloc;
use service\modules\user\models\ar\UserRegisterLog;
use service\modules\user\models\ar\UserBase;
use service\modules\user\models\ar\UserAuth;
use service\modules\user\models\ar\UserExtra;
use service\base\ServiceModel;

class User extends ServiceModel
{

    const salt = 'happy.mingwei.jinya.huangqi.jiawei.jide.jingjing';

    /**
     * @param $uid 用户唯一ID
     * @param $identifier 账号或者用户名
     * @param $certificate 密码凭证
     * @param $identity_type 注册方式
     * @param array $other 其他参数
     * @return array|null|\yii\db\ActiveRecord
     */
    public function register($uid, $identifier,$certificate = '', $identity_type = 5, $other = [])
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
            if (isset($other[$value])) {
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
        $regisertLog->register_method = $register_way;
        if (isset($other['c_ip'])) {
            $regisertLog->register_ip = $other['c_ip'];
        }
        if (isset($other['c_business'])) {
            $regisertLog->register_client = $other['c_business'];

        }
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
            if (isset($other[$value])) {
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
        $account = 'lp_'.md5($uid);
        return UserBase::updateAll(['user_name' => $account], "uid = $uid");
    }

    public function loginThird($uuid)
    {

    }
}
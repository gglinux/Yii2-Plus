<?php

namespace service\modules\user\models;
use service\modules\user\models\ar\UserBase;
use service\modules\user\models\ar\UserAuth;
use service\base\ServiceModel;

class User extends ServiceModel
{

    const salt = 'happy.mingwei.jinya.huangqi.jiawei.jide.jingjing';

    public function register($uid, $nickname, $other)
    {

    }


    public function genJWTToken()
    {

    }

    public function isExistUuid($uuid, $way)
    {
        $result = UserAuth::find()->where(['identifier'=>$uuid,'identity_type'=>(int)$way ]);
        if ($result['id']) {
            return true;
        }
        return false;
    }

    public function genUserUniqueId($uuid,$way)
    {
        return md5($uuid.$way.time());
    }
}
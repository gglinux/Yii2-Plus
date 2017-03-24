<?php

namespace service\modules\user\models;
use service\modules\user\models\ar\UserBase;

class User extends service\base\ServiceModel
{
    public function register($uid, $nickname)
    {
        $userbase = new UserBase();
        $userbase->uid = $uid;
        $userbase->nick_name = $nickname;
        return $userbase->save();
    }
}
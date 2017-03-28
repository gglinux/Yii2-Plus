<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 上午11:22
 */

namespace service\modules\user\services;

use common\base\Error;
use service\base\ServiceException;
use service\base\BaseService;
use service\modules\user\models\ar\UserRelationBlack;
use service\modules\user\models\ar\UserRelationFriend;
use service\modules\user\models\User;
use common\helpers\CommFunction;

class UserService extends BaseService
{
    public function addFriend($uid, $touid)
    {
        if (empty($uid) || empty($touid)) {
            throw new ServiceException(Error::COMMON_INVALID_PARAM,'参数为空');
        }
        $userblock = UserRelationBlack::find()->where(['uid'=>$touid,'touid'=>$uid])->asArray()->one();
        if ($userblock['id']) {
            $this->error(Error::USER_IN_BLACKLIST,'被拉黑，无法添加！');
        }
        $userFriend = UserRelationFriend::find()->where(['uid'=>$uid,'friend_uid'=>$touid])->asArray()->one();
        if ($userFriend['id'] && $userFriend['status'] == 2) {
            return true;
        } elseif ($userFriend['status'] != 2 && $userFriend['id']) {
            $userFriend = UserRelationFriend::findOne($userFriend['id']);
            $userFriend->status = 1;
            return $userFriend->save();
        } else {
            $userFriendModel = new UserRelationFriend();
            return $userFriendModel->insertOne($uid, $touid);
        }

    }
}
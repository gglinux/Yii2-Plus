<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/27
 * Time: 下午6:46
 */

namespace api\modules\user\controllers;

use common\base\Error;
use common\components\SessionCan;
use Hprose\Http\Client;
use api\base\ApiController;

class Relation extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'nonce' => [
                'class' => \api\filters\Nonce::className(),
            ],
            'login' =>[
                'class' => \api\filters\AccessToken::className()
            ]
        ]);
    }

    public function actionAddFriend()
    {
        $touid = \Yii::$app->request->post('touid');
        $uid = SessionCan::getUid();
        if (empty($touid) || empty($uid)) {
            \Yii::$app->response->error(Error::COMMON_MISS_PARAM,'参数为空');
        }
        $userService = Client::create(\Yii::$app->params['user_rpc']);
        $result = $userService->addFriend($uid,$touid);
        if ($result == true) {

            //todo push 消息
            \Yii::$app->response->ok();
        } else {
            \Yii::$app->response->error(Error::USER_ADD_ERROR,'添加好友失败，请重试！');
        }

    }

}
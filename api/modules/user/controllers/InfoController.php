<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午6:07
 */

namespace api\modules\user\controllers;

use common\base\Error;
use common\components\SessionCan;
use Hprose\Http\Client;
use Yii;
use api\base\ApiController;

class InfoController extends ApiController
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


    public function actionUpdate()
    {
        $sex = \Yii::$app->request->post('gender',0);
        $push_token = Yii::$app->request->post('pushtoken');
        $uid = SessionCan::getUid();
        if (empty($push_token)) {
            Yii::$app->response->error(Error::COMMON_INVALID_PARAM,'pushtoken为空');
        }
        $userService = new Client(Yii::$app->params['user_rpc']);
        $params = [
            'gender' => $sex,
            'push_token' => $push_token,
        ];
        $result = $userService->updateUserBase($uid,$params);
        if ($result) {
            Yii::$app->response->ok();
        }
        Yii::$app->response->error(Error::UPDATE_FAILED,'数据更新失败');
    }

}
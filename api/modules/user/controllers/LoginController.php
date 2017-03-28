<?php

namespace api\modules\user\controllers;

use common\components\Jwt;
use Hprose\Http\Client;
use SebastianBergmann\Diff\LCS\TimeEfficientImplementation;
use Yii;
use api\base\ApiController;
use common\base\Error;

/**
 * Default controller for the `user` module
 */
class LoginController extends ApiController
{

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'nonce' => [
                'class' => \api\filters\Nonce::className(),
                'only'=> ['third','account']
            ],
        ]);
    }

    //第三方登陆
    public function actionThird()
    {
        $uuid = Yii::$app->request->post('uuid');
        $headicon = Yii::$app->request->post('headicon');
        $nickname = Yii::$app->request->post('nickname');
        $loginWay = Yii::$app->request->post('loginway');
        if ( empty($uuid) ) {
            Yii::$app->response->error(Error::COMMON_MISS_PARAM,'uuid为空');
        }
        $userService = new Client(Yii::$app->params['user_rpc']);
        $userInfo = $userService->loginThrid($uuid,$loginWay, ['face'=>$headicon,'nick_name'=>$nickname]);
        if ($userInfo['uid']) {
            $userInfo['access_token'] = Jwt::createJwt($userInfo);
            Yii::$app->response->success($userInfo);
        }
        Yii::$app->response->error($userInfo['code'],$userInfo['msg']);
    }

    //账号手机登陆
    public function actionAccount()
    {

    }
}

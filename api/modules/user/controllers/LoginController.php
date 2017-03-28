<?php

namespace api\modules\user\controllers;

use api\filters\Signature;
use common\components\Jwt;
use common\helpers\client\AppClient;
use \Hprose\Http\Client;
use SebastianBergmann\Diff\LCS\TimeEfficientImplementation;
use Yii;
use api\base\ApiController;
use common\base\Error;
use yii\filters\RateLimiter;

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
//            'sign' => [
//                'class' => Signature::className(),
//            ]
        ]);
    }

    //第三方登陆
    public function actionThird()
    {
        $uuid = Yii::$app->request->post('uuid');
        $headicon = Yii::$app->request->post('headicon');
        $nickname = Yii::$app->request->post('nickname');
        $loginWay = Yii::$app->request->post('loginway',5);
        if ( empty($uuid) ) {
            Yii::$app->response->error(Error::COMMON_MISS_PARAM,'uuid为空');
        }

        $userService = Client::create(Yii::$app->params['user_rpc'],false);

        $other = ['face'=>$headicon,'nick_name'=>$nickname,'c_ip'=> (AppClient::getInstance())->getIP(),'c_business'=>(AppClient::getInstance())->getBusiness()];
        $userInfo = $userService->loginThrid($uuid, $loginWay, '', $other);
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

<?php

namespace admin\modules\admin\controllers;

use common\base\Error;
use common\components\Jwt;
use yii\web\Controller;
use admin\base\AdminController;

/**
 *
 * 管理后台管理员 登陆控制器
 * Login controller for the `admin` module
 */
class UserController extends AdminController
{
    /**
     * cors 开启跨域请求
     * @return array
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
            ],
        ];
    }

    public $adminUsers = [
        [
            'uid'  => 1,
            'name' =>'张三',
            'account' => 'admin@gglinux.com',
            'password'=>'111111',
            'introduction' => '管理员',
            'role' => 'admin',
            'avatar' =>'http://gglinux.com/images/dog.png'
        ],
        [
            'uid'  => 2,
            'name' =>'李四',
            'account' => 'author@gginux.com',
            'password'=>'222222',
            'introduction' => '作者',
            'role' => 'author',
            'avatar' =>'http://gglinux.com/images/dog.png'
        ]
    ];

    public function actionLoginbyemail()
    {
        $request = \Yii::$app->request;
        $account = $request->getBodyParam('email');
        $password = $request->getBodyParam('password');
        $value =  [$account, $password];

        //匹配 account
        foreach ($this->adminUsers as $value) {
            if ($value['account'] == $account && $password == $value['password']) {
                $token = Jwt::createJwt(['uid'=>$value['uid']]);
                $value['token'] = $token;
                \Yii::$app->response->success($value);
            }
        }
        \Yii::$app->response->error(Error::ACCOUNT_PASSWORD_ERROR, '账号或者密码错误');
    }


    public function actionLoginout()
    {

    }

    public function actionInfo()
    {
        $token = \Yii::$app->request->get('token');
        $info = Jwt::getJwtInfo($token);
        $uid = $info['uid'];
        foreach ($this->adminUsers as $value) {
            if ($value['uid'] == $uid) {
                \Yii::$app->response->success($value);
            }
        }
        \Yii::$app->response->error(Error::ACCESS_TOKEN_ERROR,'token错误');

    }
}

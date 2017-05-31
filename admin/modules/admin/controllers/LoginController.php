<?php

namespace admin\modules\admin\controllers;

use yii\web\Controller;

/**
 *
 * 管理后台管理员 登陆控制器
 * Login controller for the `admin` module
 */
class LoginController extends Controller
{

    public $adminUsers = [
        [
            'uid' => '110',
            'name'=>'张三',
            'password'=>'111111',
            'introduction' => '后台管理员',
            ''

        ]

    ];
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLoginbyemail()
    {

    }
}

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
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}

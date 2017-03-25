<?php

namespace service\modules\user\controllers;

use service\base\ServiceController;

/**
 * Default controller for the `user` module
 */
class LoginController extends ServiceController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
//        return $this->render('index');
        echo 'hello';exit();
    }

    /**
     * @param $uuid
     * @param $avator
     * @param $nickname
     */
    public function actionLoginthird($uuid, $avator, $nickname)
    {

    }

    /**
     * @param $phone
     * @param $password
     */
    public function actionLogintrad($phone,$password)
    {

    }

}

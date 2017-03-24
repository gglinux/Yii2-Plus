<?php

namespace service\modules\user\controllers;

use service\base\ServiceController;
use service\modules\user\models\User;

/**
 * Default controller for the `user` module
 */
class RegisterController extends ServiceController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //1:数据校验
        //2:数据获取
        $uid = 123456;
        $nick_name = 'gglinux';

    }
}

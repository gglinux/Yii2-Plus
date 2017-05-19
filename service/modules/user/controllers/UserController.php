<?php

namespace service\modules\user\controllers;

use Hprose\Yii\Server;
use service\modules\user\service\UserService;
use yii\web\Controller;

/**
 * Account controller for the `user` module
 */
class UserController extends Controller
{
    public function actionIndex()
    {
        $service = new UserService();
        $server = new Server();
        $server->add('getUserInfo', $service);
        return $server->start();
    }
}

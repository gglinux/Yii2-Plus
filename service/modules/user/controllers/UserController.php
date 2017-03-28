<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午4:36
 */

namespace service\modules\user\controllers;

use service\base\ServiceController;
use Hprose\Yii\Server;
use service\modules\user\services\UserService;

class UserController extends ServiceController
{

    public $enableCsrfValidation = false;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        $service = new UserService();
        $server = new Server();
        $server->add('loginTrad', $service);
        $server->add('loginThrid', $service);
        return $server->start();
    }

    public function actionRegister()
    {
        $service = new UserService();
        $server = new Server();
        $server->add('registerThrid', $service);
        $server->add('registerTrad', $service);
        return $server->start();
    }

    public function actionIndex()
    {


        $service = new UserService();
        $server = new Server();
//        $server->addInstanceMethods($service);
        $server->add('registerThrid', $service);
        $server->add('registerTrad', $service);
        $server->add('loginTrad', $service);
        $server->add('loginThrid', $service);
        $server->add('updateUserBase',$service);
        return $server->start();
    }
}
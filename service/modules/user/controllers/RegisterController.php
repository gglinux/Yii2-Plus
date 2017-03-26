<?php

namespace service\modules\user\controllers;

use Hprose\Yii\Server;
use service\base\ServiceException;
use service\base\ServiceController;
use service\modules\user\services\UserService;

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
        $service = new UserService();
        $server = new Server();
        $server->add('registerThrid', $service);
        $server->add('registerTrad', $service);
        return $server->start();
    }

}

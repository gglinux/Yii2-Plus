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
        return $this->render('index');
    }
}

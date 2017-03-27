<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/3/26
 * Time: 下午6:07
 */

namespace api\modules\user\controllers;

use Yii;
use api\base\ApiController;

class InfoController extends ApiController
{

    //更新用户信息，性别,push_token
    public function actionUpdate()
    {
        $sex = \Yii::$app->request->post();
    }

}
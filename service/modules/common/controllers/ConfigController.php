<?php
/**
 * Created by PhpStorm.
 * User: guojiawei
 * Date: 2017/5/14
 * Time: 上午12:29
 */

namespace service\modules\common\controllers;

use Yii;
use service\base\ServiceController;


class ConfigController extends ServiceController
{

    public function actionError()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
//            return $exception;
            var_dump($exception);exit();
        }
        return [];
    }

    /**
     * 服务化 文档
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('doc');
    }

}
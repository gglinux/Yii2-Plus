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
use yii\web\NotFoundHttpException;


class ConfigController extends ServiceController
{

    public function actionError()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $exception = Yii::$app->errorHandler->exception;

        $data['code'] = $exception->getCode();
        $data['message'] = $exception->getMessage();
        $data['name'] = $exception->getTraceAsString();

        if ($exception !== null) {
            if ($exception instanceof NotFoundHttpException) {
                $data['code'] = 404;
            } else {
                $data['code'] = 500;
            }
            return $data;
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
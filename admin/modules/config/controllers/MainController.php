<?php

namespace admin\modules\config\controllers;

use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `config` module
 */
class MainController extends Controller
{
    /**
     * 服务化 文档
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial('doc');
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * json 输出错误
     * @return array
     */
    public function actionErrorJson()
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

}

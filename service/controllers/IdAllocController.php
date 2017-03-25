<?php

namespace service\controllers;

use Yii;
use yii\filters\AccessControl;
use service\base\ServiceController;
use yii\filters\VerbFilter;
use Hprose\Http\Server;
use yii\web\Response;
use service\models\IdAlloc;


Yii::$app->response->format=Response::FORMAT_JSON;
/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class SiteController
 * @package service\controllers
 */
class IdAllocController extends ServiceController
{

    public $enableCsrfValidation = false;
    public $defaultAction = 'index';
 

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionGetCurrentId($key)
    {
        $id = IdAlloc::getCurrentId($key);
        return [
            'code' => 0,
            'message' => 0,
            'data' => $id,
        ];
    }

    public function actionAllocId($key)
    {
        $id = IdAlloc::allocId($key);
        return [
            'code' => 0,
            'message' => 0,
            'data' => $id,
        ];
    }

    /**
     * Service 入口文件
     * @return string
     */
    public function actionIndex()
    {
        $server = new Server();
        $anObject = new IdAlloc();

        $server->addInstanceMethods($anObject);
        return $server->start();
    }

    
   

   
}

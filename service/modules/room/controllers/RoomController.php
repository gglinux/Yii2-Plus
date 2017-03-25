<?php

namespace service\modules\room\controllers;

use Yii;
use yii\filters\AccessControl;
use service\base\ServiceController;
use yii\filters\VerbFilter;
use Hprose\Http\Server;

use service\modules\room\models\ar\RoomInfo;
use service\modules\room\models\ar\RoomUser;
use service\modules\room\services\RoomService;

use yii\web\Response;
use service\modules\room\models\ar\IdAlloc;


Yii::$app->response->format=Response::FORMAT_JSON;
/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class RoomController
 * @package service\controllers
 */
class RoomController extends ServiceController
{

    public $enableCsrfValidation = false;

    public static $defaultIntValue = 1;
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

    /**
     *
     * 看这里！！！！！！
     *
     * 这个是服务层service对外提供的用户接口
     *
     * 可以开启某个方法，也可以开启整个类
     *
     * https://github.com/hprose/hprose-yii/wiki/%E4%BD%BF%E7%94%A8%E6%96%B9%E6%B3%95
     *
     * 《注意：测试可以直接调用，需要配置》
     * 访问：http://service.com/user
     * 输出：Fa3{u#s5"hello"s6"getAll"}z
     * @return string
     */
    public function actionIndex()
    {
        // $server = new Server();
        // $anObject = new User();

        // $server->addInstanceMethods($anObject);
        // return $server->start();
    }



    public function actionRoomUserExit(){
        $arrParam = [
            'room_id' => 31,
            'user_id' => 215,
            'exit_status' => 1,
        ];
        return RoomService::updateRoomUserInfo($arrParam);
    }

    /**
     * @brif 创建房间
     * @return string
     */
    public function actionGetRoomById($id)
    {
        var_dump($id);
        $arrRoomInfo = RoomInfo::find()->orderBy('id')->all();
        Yii::$app->response->format=Response::FORMAT_JSON;
        return [
            'errno'=> 0 ,
            'errmsg'=>'success',
            'data' => $arrRoomInfo
        ];
    }


    /**
     * @brif 查看房间
     * @return string
     */
    public function actionGetRooms()
    {
        $arrRoomInfo = RoomInfo::find()->orderBy('id')->all();
        Yii::$app->response->format=Response::FORMAT_JSON;
        return [
            'errno'=> 0 ,
            'errmsg'=>'success',
            'data' => $arrRoomInfo
        ];
    }

    
   
}

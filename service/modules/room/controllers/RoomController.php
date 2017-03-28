<?php

namespace service\modules\room\controllers;

use Yii;
use service\base\ServiceController;
use Hprose\Http\Server;

use service\modules\room\services\RoomService;

use yii\web\Response;


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
    public $layout = false;
    /**
     * @inheritdoc
     */
    public function actions()
    {

    }

    /**
     * 这个是服务层service对外提供的用户接口
     * @return string
     */
    public function actionIndex()
    {

        $server = new Server();
        $service = new RoomService();
        $server->addMethods([
            'createNewRoom',
            'updateRoomInfo',
            'updateRoomUserInfo',
            'getBatchRoomInfo',
            'getBatchRoomUsers',
            'getUserInRoom',
        ], $service);
        return $server->start();
    }


    /**
     * 测试
     * @param $userId
     * @return bool
     */
    public function actionMatch($user_id){

        $tokenList = [
            '123' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjEyMywiaWF0IjoxNDkwNjcwNzcwLCJleHAiOjE1MDYyMjI3NzB9.idwdxswNeLh7ZEujWAck39WQmzn3sb7o2aodoIpJeUo',
            '234' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiIyMzQiLCJpYXQiOjE0OTA2NzIxODIsImV4cCI6MTUwNjIyNDE4Mn0.5x_G5drPGzupKKiz1l--CtLMh2USlfIAHHuyg4yRx1k',
            '345' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiIzNDUiLCJpYXQiOjE0OTA2NzIyMzAsImV4cCI6MTUwNjIyNDIzMH0._qP3SAh-S7pEi22nDseWWhCF5Za5CHVmYCwpz8lTUYw',
            '456' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiI0NTYiLCJpYXQiOjE0OTA2NzIyNTYsImV4cCI6MTUwNjIyNDI1Nn0.Z7nBbEUFkdfUblH8QM_9UP1RYMsSovkVgA6Q9rau9HI',
            '567' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiI1NjciLCJpYXQiOjE0OTA2NzIyNzcsImV4cCI6MTUwNjIyNDI3N30.iJP6DLO6ZL-J8Cw6aKauP_lOVGVXkpgAzzP7dDT4GrQ",
            '678' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiI2NzgiLCJpYXQiOjE0OTA2NzIyOTEsImV4cCI6MTUwNjIyNDI5MX0.zklgwrrs9JuqdrIyG2PLs_SG0_kS_iyBpJxcN9Ir0s8",
            '789' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiI3ODkiLCJpYXQiOjE0OTA2NzIzMDMsImV4cCI6MTUwNjIyNDMwM30.QGNoDFx1IMs52kcmWrZJzPjhBhoXg7GNzUOKUjlgogQ',
            '890' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiI4OTAiLCJpYXQiOjE0OTA2NzIzMTYsImV4cCI6MTUwNjIyNDMxNn0.mQOdqtHXVeORxeJKkN-0dDvvtNSMtJPHwYGHEB6f9nY',

        ];
        $token = $tokenList[$user_id];
        Yii::$app->response->format=Response::FORMAT_HTML;

        return $this->render('socket-test/index', [
            'token' => $token
        ]);
    }



    /**
     * @brif 测试函数
     * @return string
     */
    public function actionCall()
    {
        Yii::$app->response->format=Response::FORMAT_JSON;

        $intRoomId = 39;
        $updateRoomInfo = RoomService::updateRoomInfo([
            'room_id' => $intRoomId,
            'game_status' => 1,
        ]);
        $roomInfo = RoomService::getBatchRoomInfo([$intRoomId]);
        $userInfos = RoomService::getBatchRoomUsers([$intRoomId]);
        $userInRoomInfo = RoomService::getUserInRoom([
            'user_id' => 216,
            'room_id' => $intRoomId
        ]);
        $arrRet = compact('roomInfo', 'userInfos', 'userInRoomInfo', 'updateRoomInfo');
        return $arrRet;
    }

    
   
}

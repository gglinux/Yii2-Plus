<?php

namespace service\modules\room\controllers;

use Yii;
use service\base\ServiceController;
use Hprose\Http\Server;

use service\modules\room\services\RoomService;
use service\modules\room\models\ar\PreRoom;

use yii\web\Response;


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
    public $layout = false;
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
         $server = new Server();
         $server->add(new RoomService());
         return $server->start();
    }

    public function actionTest (){
        return PreRoom::rmBatchUserPreRoomId(
            [229,228]
        );
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
     *
     * @param $userId
     * @return bool
     */
    public function actionMatch($user_id){

        $tokenList = [
            '123' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxMjMsImlhdCI6MTQ4OTkwMzE0MiwiZXhwIjoxNTA1NDU1MTQyfQ.UNiY3obDhFl3EuPWwE5MR1ojXz2n-F_TBI6T7vqKai0',
            '234' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiMjM0IiwiaWF0IjoxNDkwNTg3MTM4LCJleHAiOjE1MDYxMzkxMzh9.59KxajILnQjk_MGFceIz2gPkRaXw76u8ABOGdOYUF8k',
            '345' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiMzQ1IiwiaWF0IjoxNDkwNTg3MTYzLCJleHAiOjE1MDYxMzkxNjN9.ZWqOPIEJ1EgH65Pqx51jjFO4cH00WsrKA3zI8CXasUs',
            '456' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNDU2IiwiaWF0IjoxNDkwNTg3MTc4LCJleHAiOjE1MDYxMzkxNzh9.qjPmmKE33f_QOIUWyR70b3cLMmanZkO9garkSvoP11Y',
            '567' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNTY3IiwiaWF0IjoxNDkwNTg3MTk0LCJleHAiOjE1MDYxMzkxOTR9.kfYDXSQMH7iGvTkk_Ydf2FLue1Kl4ogR_-P3VsM5IcQ',
            '678' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNjc4IiwiaWF0IjoxNDkwNTg3MjE3LCJleHAiOjE1MDYxMzkyMTd9.WRWnlakfFIFSCdmSPM9mwv3vB2EEyD6kYGJEqoxI7F8',
            '789' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNzg5IiwiaWF0IjoxNDkwNTg3MjUzLCJleHAiOjE1MDYxMzkyNTN9.ul8Ae1XRjJi-LtFY3wRXENgvlOj0TRe4DZcz-dZ-E1A',
            '890' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiODkwIiwiaWF0IjoxNDkwNTg3MjczLCJleHAiOjE1MDYxMzkyNzN9.Sw1F6xSQmg54x4KtEMlTRuvHkDk2SmAv12pGse7Waew',

        ];
        $token = $tokenList[$user_id];
        Yii::$app->response->format=Response::FORMAT_HTML;

        return $this->render('socket-test/index', [
            'token' => $token
        ]);
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

    /**
     * @brif 测试函数
     * @return string
     */
    public function actionCall()
    {
        $intRoomId = 31;
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

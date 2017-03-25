<?php

namespace service\controllers;

use Yii;
use yii\filters\AccessControl;
use service\base\ServiceController;
use yii\filters\VerbFilter;
use service\models\LoginForm;
use service\models\ContactForm;
use Hprose\Http\Server;
use service\models\RoomInfo;
use service\models\RoomUser;

use yii\web\Response;
use service\models\IdAlloc;


Yii::$app->response->format=Response::FORMAT_JSON;
/**
 * 服务层对外服务 控制器层（HTTP协议）
 * 请继承 ServiceController
 * Class SiteController
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

    /**
     * @brif 创建房间
     * @return string
     */
    public function actionCreateRoom($user_count, $master_id)
    {
        // 插入新客户的记录
        $roomInfo = new RoomInfo();
        $idAlloc = new IdAlloc();
        $ret =  $idAlloc::allocRoomId();
        if($ret['errno'] !== 0) {
            return $ret;
        }
        $intRoomId = $ret['data'];
        $roomInfo->room_id = intval($intRoomId);
        $roomInfo->user_count = intval($user_count);
        $roomInfo->master_id = intval($master_id);
        $roomInfo->create_time = date("Y-m-d H:i:s");
        $roomInfo->status = self::$defaultIntValue;
        $roomInfo->game_status = 0;
        $ret = $roomInfo->save();  // 等同于 $customer->insert();
        if( false === $ret) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " call roomInfo save error. ". serialize(compact('ret', 'roomInfo'));
            Yii::error($strLog);
            return [
                'errno' => -1,
                'errmsg' => $strLog
            ];
        }

        $arrUserList = [
            [
                'user_id'=> 123,
                'user_role' => 2,
            ],
            [
                'user_id'=> 123,
                'user_role' => 1,
            ],
            [
                'user_id'=> 123,
                'user_role' => 1,
            ],
        ];
        $ret = self::actionInsertUserToRoom($arrUserList, $intRoomId);
        if( false === $ret || !isset($ret['errno'])) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . "  error. ". serialize(compact('ret', 'arrParam'));
            Yii::error($strLog);
            $roomInfo->delete();
            return [
                'errno' => -1,
                'errmsg' => $strLog
            ];
        }

        return [
            'errno'=> 0 ,
            'errmsg'=>'success',
            'data' => $roomInfo
        ];
    }

    /**
     * @brif 将人加入房间内
     * @return string
     */
    private static function actionInsertUserToRoom($arrUserList, $intRoomId)
    {
        // 插入新客户的记录
        //$roomUser = new RoomUser();
        $strTableName = 'room_user';
        $arrParam = [];
        $strNow = date("Y-m-d H:i:s");
        foreach($arrUserList as $item) {
             $arrParam[] = [$intRoomId, $item['user_id'], $item['user_role'],  $strNow, 1, 0];
        }
        $ret = RoomUser::getDB()->createCommand()->batchInsert($strTableName, 
        ['room_id', 'user_id', 'user_role','enter_time', 'status', 'exit_status'], 
        $arrParam )
        ->execute();
        if( false === $ret || !isset($ret['errno'])) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . "  error. ". serialize(compact('ret', 'arrParam'));
            Yii::error($strLog);
            return [
                'errno' => -1,
                'errmsg' => $strLog
            ];
        }
        return [
            'errno'=> 0 ,
            'errmsg'=>'success',
            'data' => $ret
        ];
    }

   

   
}

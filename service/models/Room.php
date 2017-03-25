<?php

namespace service\models;

use Yii;

use yii\base\Model;
use service\models\RoomInfo;
use service\models\RoomUser;
use service\models\IdAlloc;
/**
 * Class Room
 * 房间服务层代码
 * @package service\models
 */
class  Room extends Model
{
    const TABLE_NAME_ROOM_USER = 'room_user';

    const DEFAULT_STATUS_VALUE = 1;
    const USER_ROLE_MASTER = 2;
    const USER_ROLE_NORMAL = 1;

    public static function createNewRoom($arrPreRoomInfo){

        $roomInfo = new RoomInfo();
        $intRoomId =  IdAlloc::allocId(IdAlloc::ROOM_ID_ALLOC_KEY);
        if(false == $intRoomId) {
            return false;
        }
        $arrUserList =$arrPreRoomInfo['user_list'];
        $intUserCount = count($arrUserList);
        $intMasterId = $arrUserList[0]['user_id'];
        $arrUserList[0]['user_role'] = self::USER_ROLE_MASTER;
        $roomInfo->room_id = intval($intRoomId);
        $roomInfo->user_count = intval($intUserCount);
        $roomInfo->master_id = intval($intMasterId);
        $roomInfo->create_time = date("Y-m-d H:i:s");
        $roomInfo->status = self::DEFAULT_STATUS_VALUE;
        $roomInfo->game_status = 0;
        $ret = $roomInfo->save();  // 等同于 $customer->insert();
        if( false === $ret) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " call roomInfo save error. ". serialize(compact('ret', 'roomInfo'));
            Yii::error($strLog);
            return false;
        }
        $ret = self::insertUserToRoom($arrUserList, $intRoomId);
        if( false === $ret ) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . "  error. ". serialize(compact('ret', 'arrParam'));
            Yii::error($strLog);
            $roomInfo->delete();
            return false;
        }

        return true;
    }
    /**
     * @brif 将人加入房间内
     * @return string
     */
    private static function insertUserToRoom($arrUserList, $intRoomId)
    {
        // 插入新客户的记录
        //$roomUser = new RoomUser();
        $strTableName = self::TABLE_NAME_ROOM_USER;
        $arrParam = [];
        $strNow = date("Y-m-d H:i:s");
        var_dump($arrUserList);
        foreach($arrUserList as $item) {
            if(!isset($item['user_role'])) {
                $item['user_role'] = self::USER_ROLE_NORMAL;
            }
            $arrParam[] = [$intRoomId, $item['user_id'], $item['user_role'],  $strNow, self::DEFAULT_STATUS_VALUE, 0];
        }
        $ret = RoomUser::getDB()->createCommand()->batchInsert($strTableName, 
        ['room_id', 'user_id', 'user_role','enter_time', 'status', 'exit_status'], 
        $arrParam )
        ->execute();
        if( false === $ret ) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . "  error. ". serialize(compact('ret', 'arrParam'));
            Yii::error($strLog);
            return false;
        }
        return true;
    }

    /**
     * @brif 修改房间数据
     * @param array $arrRoomInfo
     * @return boolean
     */
    public static function updateRoomInfo($arrRoomInfo)
    {
        $strTableName = self::TABLE_NAME_ROOM_USER;
        $arrParam = [];
        $strNow = date("Y-m-d H:i:s");
        $arrRoomInfo = RoomInfo::find()->where([
            'room_id' => $arrRoomInfo['room_id']
        ])->one();
        if (isset($arrRoomInfo['close_time'])) {
            $arrRoomInfo->close_time = $strNow;
        }
        if (isset($arrRoomInfo['master_id'])) {
            $arrRoomInfo->master_id = $arrRoomInfo['master_id'];
        }

        if (isset($arrRoomInfo['status'])) {
            $arrRoomInfo->status = $arrRoomInfo['status'];
        }

        if (isset($arrRoomInfo['game_status'])) {
            $arrRoomInfo->game_status = $arrRoomInfo['game_status'];
        }
        
        $ret = $arrRoomInfo->update();
        
        return $ret;
    }
    

    /**
     * @brif 修改房间用户的数据
     * @return boolean
     */
    public static function updateRoomUserInfo($arrRoomUserInfo)
    {
        $strTableName = self::TABLE_NAME_ROOM_USER;
        $arrParam = [];
        $strNow = date("Y-m-d H:i:s");
        $arrRoomUser = RoomUser::find()->where([
            'user_id' => $arrRoomUserInfo['user_id'],
            'room_id' => $arrRoomUserInfo['room_id']
        ])->one();

        $arrRoomUser->exit_time = $strNow;
        $arrRoomUser->exit_status = intval($arrRoomUserInfo['exit_status']);
        $ret = $arrRoomUser->save();
        
        return $ret;
    }
    

}
<?php

namespace service\modules\room\services;

use Yii;

use service\modules\room\models\ar\RoomInfo;
use service\modules\room\models\ar\RoomUser;
use common\base\Exception;
use service\base\BaseService;
use service\modules\common\services\CommonService;
use service\modules\common\models\ar\IdAlloc;



/**
 * Class Room
 * 房间服务层代码
 * @package service\models
 */
class  RoomService extends BaseService
{
    const TABLE_NAME_ROOM_USER = 'room_user';

    const DEFAULT_STATUS_VALUE = 1;
    const USER_ROLE_MASTER = 2;
    const USER_ROLE_NORMAL = 1;
    const ROOM_GAME_STATUS_SSWD = 1;


    /**
     * 创建房间
     * @param array userlist
     * @param number roomId
     * @throws exception
     * @return boolean
     */
    public static function createNewRoom($arrPreRoomInfo){

        if(!is_array($arrPreRoomInfo) || empty($arrPreRoomInfo) ){
            throw new Exception('参数错误');
        }
        $roomInfo = new RoomInfo();
        $client = CommonService::serviceClient("/common/id-alloc", 'php');
        $intRoomId =  $client->allocId(IdAlloc::ROOM_ID_ALLOC_KEY);
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
        $roomInfo->save();  // 等同于 $customer->insert();

        self::insertUserToRoom($arrUserList, $intRoomId);

        return $intRoomId;
    }
    /**
     * @brif 将人加入房间内
     * @param array userlist
     * @param number roomId
     * @throws exception
     * @return boolean
     */
    private static function insertUserToRoom($arrUserList, $intRoomId)
    {
        if(!is_array($arrUserList) || empty($arrUserList) || empty($intRoomId)){
            throw new Exception('参数错误');
        }
       
        // 插入新客户的记录
        //$roomUser = new RoomUser();
        $strTableName = self::TABLE_NAME_ROOM_USER;
        $arrParam = [];
        $strNow = date("Y-m-d H:i:s");
        foreach($arrUserList as $item) {
            if(!isset($item['user_role'])) {
                $item['user_role'] = self::USER_ROLE_NORMAL;
            }
            $arrParam[] = [$intRoomId, $item['user_id'], $item['user_role'],  $strNow, self::DEFAULT_STATUS_VALUE, 0];
        }
        return RoomUser::getDB()->createCommand()->batchInsert($strTableName, 
        ['room_id', 'user_id', 'user_role','enter_time', 'status', 'exit_status'], 
        $arrParam )
        ->execute();
        
    }

    /**
     * @brif 修改房间数据
     * @param array $arrRoomInfo
     * @throws exception
     * @return boolean
     */
    public static function updateRoomInfo($arrRoomInfo)
    {
        if(!is_array($arrRoomInfo) || empty($arrRoomInfo)){
            throw new Exception('参数错误');
        }

        if(!isset($arrRoomInfo['room_id'])){
            throw new Exception('参数错误');
        }



        $modelRoomInfo = RoomInfo::find()->where([
            'room_id' => $arrRoomInfo['room_id']
        ])->one();
        if (isset($arrRoomInfo['close_time'])) {
            $strNow = date("Y-m-d H:i:s");
            $modelRoomInfo->close_time = $strNow;
        }
        if (isset($arrRoomInfo['master_id'])) {
            $modelRoomInfo->master_id = $arrRoomInfo['master_id'];
        }

        if (isset($arrRoomInfo['status'])) {
            $modelRoomInfo->status = $arrRoomInfo['status'];
        }

        if (isset($arrRoomInfo['game_status'])) {
            $modelRoomInfo->game_status = intval($arrRoomInfo['game_status']);

        }
        //var_dump($arrRoomInfo);
        
        return $modelRoomInfo->save();
        
    }
    

    /**
     * @brif 修改房间用户的数据
     * @param array
     * @throws exception
     * @return boolean
     */
    public static function updateRoomUserInfo($arrRoomUserInfo)
    {
        if(!is_array($arrRoomUserInfo) || empty($arrRoomUserInfo)){
            throw new Exception('参数错误');
        }

        if(!isset($arrRoomUserInfo['user_id']) || !isset($arrRoomUserInfo['room_id'])){
            throw new Exception('参数错误, lack user_id or room_id;'. serialize($arrRoomUserInfo));
        }

        $strNow = date("Y-m-d H:i:s");
        $arrRoomUser = RoomUser::find()->where([
            'user_id' => $arrRoomUserInfo['user_id'],
            'room_id' => $arrRoomUserInfo['room_id']
        ])->one();

        if (isset($arrRoomInfo['exit_status'])) {
            $arrRoomUser->exit_time = $strNow;
            $arrRoomUser->exit_status = intval($arrRoomUserInfo['exit_status']);
        }

        if (isset($arrRoomInfo['user_role'])) {
            $arrRoomUser->user_role = intval($arrRoomUserInfo['user_role']);
        }


        return $arrRoomUser->save();
    }

    /**
     * @brif 获取房间信息 批量
     * @param array ids
     * @throws exception
     * @return array room info s
     */
    public static function getBatchRoomInfo($arrRoomIds)
    {

        
        if(!is_array($arrRoomIds)){
            throw new Exception('参数错误');
        }
        $arrRoomIds = array_filter($arrRoomIds);
         if(empty($arrRoomIds)){
            throw new Exception('参数为空');
        }
        return RoomInfo::find()->where([
            'room_id' => $arrRoomIds,
        ])->asArray()->all();


    }

    /**
     * @brif 获取房间的用户数据
     * @param array ids
     * @throws exception
     * @return array
     */
    public static function getBatchRoomUsers($arrRoomIds)
    {
        
        if(!is_array($arrRoomIds)){
            throw new Exception('参数错误');
        }
        $arrRoomIds = array_filter($arrRoomIds);
         if(empty($arrRoomIds)){
            throw new Exception('参数为空');
        }

        return RoomUser::find()->where([
            'room_id' => $arrRoomIds,
        ])->orderBy([
            'user_role' => SORT_DESC
        ])->asArray()->all();
    }

    /**
     * @brif 获取用户在房间的信息
     * @param array user_id,room_id
     * @throws exception
     * @return array
     */
    public static function getUserInRoom($arrRoomUserInfo)
    {

        if(!is_array($arrRoomUserInfo)){
            throw new Exception('参数错误');
        }

        if(!isset($arrRoomUserInfo['user_id']) || !isset($arrRoomUserInfo['room_id'])){
            throw new Exception('参数错误, lack user_id or room_id;'. serialize($arrRoomUserInfo));
        }

        return RoomUser::find()->where([
            'room_id' => $arrRoomUserInfo['room_id'],
            'user_id' => $arrRoomUserInfo['user_id']
        ])->asArray()->all();
    }
    

}
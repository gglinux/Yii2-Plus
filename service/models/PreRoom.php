<?php

namespace service\models;

use Yii;

//请继承，serviceModel
use service\base\ServiceModel;
use service\models\IdAlloc;
/**
 * Class MatchStrategy
 * 匹配策略
 * @package service\models
 */
class PreRoom extends ServiceModel
{
    /**
     * @return string 返回该AR类关联的数据表名
     */
    public static function tableName()
    {
        //return 'room_info';
    }

    public static function getDb()
    {
        return \Yii::$app->redis;  // 使用名为 "db" 的应用组件
    }
    const REDIS_KEY_PRE_ROOM= 'pre_room_info';
   
    /**
     * 新增或修改预备房间信息
     * @param array 
     * @return string 
     */
    public static function setPreRoomInfo($arrPreRoomInfo) {
        $redis = self::getDb();
        $intPreRoomId = $arrPreRoomInfo['pre_room_id'];
        if (empty($intPreRoomId)) {
            $intPreRoomId = IdAlloc::allocId(IdAlloc::PRE_ROOM_ID_ALLOC_KEY);
            $arrPreRoomInfo['pre_room_id'] = $intPreRoomId;
        }
        Yii::warning('intPreRoomId: ' . $intPreRoomId);
        $ret = $redis->HSET(self::REDIS_KEY_PRE_ROOM , $intPreRoomId, serialize($arrPreRoomInfo));
        if($ret) {
            return  $arrPreRoomInfo;
        } else {
            return $ret;
        }
        
    }

    /**
     * 获取房间信息
     * @return string 
     */
    public static function getPreRoomInfo($intPreRoomId) {
        $redis = self::getDb();
        $ret = $redis->HGET(self::REDIS_KEY_PRE_ROOM, $intPreRoomId);
        $ret = unserialize($ret);
        return  $ret;
    }

    /**
     * 删除房间信息
     * @param array arrPreRoomInfo 房间从Redis中读取到的信息
     * @return string 
     */
    public static function rmPreRoomInfo($arrPreRoomInfo) {
        $redis = self::getDb();
        $strValue = serialize($arrPreRoomInfo);
        $intPreRoomId = $arrPreRoomInfo['pre_room_id'];
        $ret = $redis->HDEL(self::REDIS_KEY_PRE_ROOM, $intPreRoomId);
        return  $ret;
    }
   
}
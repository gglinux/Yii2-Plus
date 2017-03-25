<?php

namespace service\models;

use Yii;

//请继承，serviceModel
use service\base\ServiceModel;
use service\models\PreRoom;


/**
 * Class MatchStrategy
 * 匹配策略
 * @package service\models
 */
class MatchStrategy extends ServiceModel
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
    const REDIS_KEY_MATCH_QUEUE_MAN = 'match_queue_man';
    const REDIS_KEY_MATCH_QUEUE_WOMAN = 'match_queue_woman';
    const REDIS_KEY_MATCH_QUEUE_PRE_ROOM = 'match_queue_pre_room';

    const SEX_MAN = 1;
    const SEX_WOMAN = 2;

    /**
     * 对房间进行人数匹配
     * @param array 
     * @return string 
     */
    public static function matchRoomLoop(){
        $arrPreRoomQueueInfo = self::getPreRoomFromQueue();
        
    }

    

    /**
     * 将用户送入匹配队列
     * @param array  arrUserInfo
     * @return string 
     */
    public static function pushUserToQueue($arrUserInfo) {
        $redis = self::getDb();
        $intSex = $arrUserInfo['sex'];
        $intUid = $arrUserInfo['user_id'];
        $strRedisKey = self::REDIS_KEY_MATCH_QUEUE_MAN;
        if ($intSex == 2) {
            $strRedisKey = self::REDIS_KEY_MATCH_QUEUE_WOMAN;
        }

        $score = time();

        $ret = $redis->zadd($strRedisKey, $score , $intUid);
        return $ret;
    }

    /**
     * 将用户取出匹配队列
     * @param array  arrUserInfo
     * @return string 
     */
    public static function getUserFromQueue($key, $intCount = 0) {
        $redis = self::getDb();
        $ret = $redis->ZREVRANGE($key, 0 , $intCount);
        return $ret;
    }

     /**
     * 将用户从队列中删除
     * @param array 
     * @return string 
     */
    public static function rmUserFromQuere($arrUserInfos) {
        $redis = self::getDb();
        $arrManList = [
            self::REDIS_KEY_MATCH_QUEUE_MAN
        ];
        $arrWomanList = [
            self::REDIS_KEY_MATCH_QUEUE_WOMAN
        ];
        foreach($arrUserInfos as $item) {
            $intSex = $item['sex'];
            $intUid = $item['user_id'];
            if ($intSex == 1) {
                $arrManList[] = $intUid;
            } else {
                $arrWomanList[] = $intUid;
            }
        }
        
        if(count($arrManList) > 1) {
            $ret = $redis->executeCommand('zrem', $arrManList);
        }

        if (count($arrWomanList) > 1) {
            $ret = $redis->executeCommand('zrem',$arrWomanList);
        }
       
      
        return $ret;
    }

    /**
     * 获取队列长度
     * @param array 参数
     * @return number 返回数量
     */
    public static function getMatchQueueLength($key) {
        $redis = self::getDb();
        $ret = $redis->zcount($key, '-inf' ,'+inf');
        return  $ret;
    }

    /**
     * 获取队列长度
     * @param array 参数
     * @return number 返回数量
     */
    public static function getPreRoomQueueLength($key) {
        $redis = self::getDb();
        $ret = $redis->llen($key);
        return  $ret;
    }

    /**
     * 新建一个预备房间
     * @return arran arrPreRoomInfo
     */
    public static function createNewPreRoom (){
         $arrPreRoomInfo = [
            'pre_room_id' => 0,
            'user_list' => [],
        ];
        $arrPreRoomInfo = PreRoom::setPreRoomInfo($arrPreRoomInfo);
        if(empty($arrPreRoomInfo)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::setPreRoomInfo error. ". serialize(compact('arrPreRoomInfo'));
            Yii::error($strLog);
            return false;
        }
        $ret = MatchStrategy::pushPreRoomIdToQueue($arrPreRoomInfo['pre_room_id']);
        if(empty($ret)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . "  MatchStrategy::pushPreRoomIdToQueue error. ". serialize(compact('ret'));
            Yii::error($strLog);
            return false;
        }
        return $arrPreRoomInfo;
    }

    /**
     * 将人加入到预备房间
     * @return arran arrPreRoomInfo
     */
    public static function addUsersToPreRoom ($arrUserInfos, $intPreRoomId){

        $arrPreRoomInfo = PreRoom::getPreRoomInfo($intPreRoomId);
        var_dump($arrPreRoomInfo);
        //return 1;
        if(empty($arrUserInfos)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " arrUserInfos is empty. ". serialize(compact('arrUserInfos', 'intPreRoomId'));
            Yii::error($strLog);
            return false;
        }
        if(empty($arrPreRoomInfo)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::getPreRoomInfo error. ". serialize(compact('arrPreRoomInfo', 'intPreRoomId'));
            Yii::error($strLog);
            return false;
        }

        $arrPreRoomInfo['user_list'] = array_merge($arrPreRoomInfo['user_list'], $arrUserInfos);
        $ret = PreRoom::setPreRoomInfo($arrPreRoomInfo);
        if(empty($ret)) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " PreRoom::setPreRoomInfo error. ". serialize(compact('arrPreRoomInfo'));
            Yii::error($strLog);
            return false;
        }
        return $arrPreRoomInfo;
        
       
    }

    


    /**
     * 将预备房间送入匹配队列
     * @param array 用户信息
     * @return string 
     */
    public static function pushPreRoomIdToQueue($intPreRoomId) {
        $redis = self::getDb();

        $strRedisKey = self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM;

        $ret = $redis->LPUSH(self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM , $intPreRoomId);
        return  $ret;
    }



    /**
     * 获取队列房间信息
     * @return string 
     */
    public static function getPreRoomIdFromQueue() {
        $redis = self::getDb();
        $strRedisKey = self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM;
        $ret = $redis->RPOPLPUSH(self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM, self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM);

        return  $ret;
    }

    /**
     * 删除列房间信息
     * @param array arrPreRoomInfo 房间从Redis中读取到的信息
     * @return string 
     */
    public static function rmPreRoomIdFromQueue($intPreRoomId) {
        $redis = self::getDb();
        $strRedisKey = self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM;
        $ret = $redis->LREM(self::REDIS_KEY_MATCH_QUEUE_PRE_ROOM, 1 , $intPreRoomId);
        return  $ret;
    }
   
}
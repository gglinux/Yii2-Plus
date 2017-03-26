<?php

namespace service\modules\socket\models\ar;

use Yii;


use common\base\Exception;

/**
 * Class Socket
 * Socket Model
 * @package service\models
 */
class Socket extends \yii\db\ActiveRecord
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
    const REDIS_KEY_USER_SOCKET = 'user_socket';

    public static function getNowDate(){
        return  date("Y-m-d H:i:s");;
    }
    /**
     * 设置用户Socket id 信息 (nodejs set)
     * @param array  $arrUserSocketInfo
     * @throws
     * @return boolean
     */
//    public static function setUserSocketInfo($arrUserSocketInfo) {
//        if(!isset($arrUserSocketInfo['user_id']) || !isset($arrUserSocketInfo['socket_id'])) {
//            throw new Exception('参数错误');
//        }
//        $redis = self::getDb();
//
//        $setInfo = [
//            'socket_id' => $arrUserSocketInfo['socket_id'],
//            'update_time' => self::getNowDate()
//        ];
//
//        return $redis->hset(self::REDIS_KEY_USER_SOCKET, $arrUserSocketInfo['user_id'] , serialize($setInfo));
//    }

    /**
     * 获取用户Socket id 信息
     * @param number  $intUserId
     * @throws
     * @return boolean
     */
    public static function getUserSocketInfo($intUserId) {
        if(empty($intUserId)) {
            throw new Exception('参数错误');
        }
        $redis = self::getDb();

        return $redis->hget(self::REDIS_KEY_USER_SOCKET, $intUserId );
    }

    /**
     * 批量获取用户Socket id 信息
     * @param array  $arrUserIds
     * @throws
     * @return array
     */
    public static function getBatchUserSocketInfo($arrUserIds) {
        if(!is_array($arrUserIds) || empty($arrUserIds)) {
            throw new Exception('参数错误');
        }
        $arrUserIds = array_filter($arrUserIds);
        if(empty($arrUserIds)) {
            throw new Exception('参数错误');
        }
        $redis = self::getDb();
        $arrRedisParam = [];
        $arrRedisParam[] = self::REDIS_KEY_USER_SOCKET;
        $arrRedisParam = array_merge($arrRedisParam, $arrUserIds);
        $ret = $redis->executeCommand('hmget', $arrRedisParam);
        $arrRet = [];
        for($i = 0; $i < count($arrUserIds); $i ++) {
            $arrRet[$arrUserIds[$i]] = unserialize($ret[$i]);
        }

        return  $arrRet;
    }



   
}
<?php

namespace service\models;

use Yii;

//请继承，serviceModel
use service\base\ServiceModel;

/**
 * Class RoomInfo
 * 用户服务层代码
 * @package service\models
 */
class IdAlloc extends ServiceModel
{
    /**
     * @return string 返回该AR类关联的数据表名
     */
    public static function tableName()
    {
        //return 'room_info';
    }

    const ROOM_ID_ALLOC_KEY = 'roomid';
    const USER_ID_ALLOC_KEY = 'userid';
    const PRE_ROOM_ID_ALLOC_KEY = 'preroom';

    public static function getDb()
    {
        return \Yii::$app->redis;  // 使用名为 "db" 的应用组件
    }

    public static function getCurrentId($idallocType) {
        $redis = self::getDb();
        $ret = $redis->get('idalloc_' . $idallocType);
        return $ret;
    }

    public static function allocId($idallocType) {
        $redis = self::getDb();
        $step = 1;
        $ret = $redis->INCRBY('idalloc_' . $idallocType, $step);
        return $ret;
    }
   
}
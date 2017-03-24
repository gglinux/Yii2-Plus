<?php

namespace service\models;

use Yii;
use yii\db\ActiveRecord;

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

    public static function getDb()
    {
        return \Yii::$app->redis;  // 使用名为 "db" 的应用组件
    }

    public static function allocRoomId() {
        $redis = self::getDb();
        $step = 1;
        $idallocType = 'roomid';
        $ret = $redis->INCRBY('idalloc_' . $idallocType, $step);
        if(false === $ret) {
            $strLog = __CLASS__ . "::". __FUNCTION__ . " call redis incrby error. ". serialize(compact('ret', 'input'));
            Yii::error($strLog);
            return [
                'errno' => -1,
                'errmsg' => $strLog
            ];
        }
        return [
            'errno' => 0,
            'errmsg' => 'success',
            'data' => $ret
        ];
    }
   
}
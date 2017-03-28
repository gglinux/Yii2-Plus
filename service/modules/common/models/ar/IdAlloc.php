<?php

namespace service\modules\common\models\ar;



/**
 * Class IdAlloc
 * id生成器
 * @package service\models
 */
class IdAlloc extends \yii\base\Model
{


    const ROOM_ID_ALLOC_KEY = 'roomid';
    const USER_ID_ALLOC_KEY = 'userid';
    const PRE_ROOM_ID_ALLOC_KEY = 'preroomid';

    private static $allowIdList = [
        'roomid',
        'userid',
        'preroomid',
    ];
    public static function getDb()
    {
        return \Yii::$app->redis;  // 使用名为 "db" 的应用组件
    }

    public static function getCurrentId($idallocType) {
        if(!in_array($idallocType, self::$allowIdList)){
            Yii::wraning("id alloc type is not allow." . serialize($idallocType));
            return false;
        }
        $redis = self::getDb();
        $ret = $redis->get('idalloc_' . $idallocType);
        return $ret;
    }

    public static function allocId($idallocType) {
        if(!in_array($idallocType, self::$allowIdList)){
            Yii::wraning("id alloc type is not allow." . serialize($idallocType));
            return false;
        }
        $redis = self::getDb();
        $step = 1;
        $ret = $redis->INCRBY('idalloc_' . $idallocType, $step);
        return $ret;
    }
   
}
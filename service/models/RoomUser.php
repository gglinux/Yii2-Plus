<?php

namespace service\models;

use Yii;
use yii\db\ActiveRecord;

//请继承，serviceModel
use service\base\ServiceModel;

/**
 * Class RoomUser
 * 用户服务层代码
 * @package service\models
 */
class  RoomUser extends ServiceModel
{
    const EXIT_STATUS_BLOCK_OUT = 2;
    CONST EXIT_STATUS_NOMARL_OUT = 1;
    
    /**
     * @return string 返回该AR类关联的数据表名
     */
    public static function tableName()
    {
        return 'room_user';
    }

    public static function getDb()
    {
        return \Yii::$app->hjsk_db;  // 使用名为 "db" 的应用组件
    }
   
}
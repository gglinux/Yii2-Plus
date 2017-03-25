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
    /**
     * @return string 返回该AR类关联的数据表名
     */
    public static function tableName()
    {
        return 'room_user';
    }

    public static function getDb()
    {
        return \Yii::$app->db;  // 使用名为 "db" 的应用组件
    }
   
}
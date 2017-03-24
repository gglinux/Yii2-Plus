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
class RoomInfo extends ServiceModel
{
    /**
     * @return string 返回该AR类关联的数据表名
     */
    public static function tableName()
    {
        return 'room_info';
    }

    public static function getDb()
    {
        return \Yii::$app->db;  // 使用名为 "db" 的应用组件
    }
   
}
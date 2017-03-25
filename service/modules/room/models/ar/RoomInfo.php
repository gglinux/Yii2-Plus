<?php

namespace service\modules\room\models\ar;

use Yii;

/**
 * This is the model class for table "room_info".
 *
 * @property integer $id
 * @property integer $room_id
 * @property integer $user_count
 * @property integer $master_id
 * @property string $create_time
 * @property string $close_time
 * @property integer $status
 * @property integer $game_status
 */
class RoomInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room_info';
    }

    /**
     * @inheritdoc
     */
    public static function getDB()
    {
        return \Yii::$app->hjsk_db;  // 使用名为 "db" 的应用组件
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id'], 'required'],
            [['room_id', 'user_count', 'master_id', 'status', 'game_status'], 'integer'],
            [['create_time', 'close_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'room_id' => '房间id',
            'user_count' => '用户数',
            'master_id' => '房间主id',
            'create_time' => '创建时间',
            'close_time' => '关闭时间',
            'status' => '状态，1为正常',
            'game_status' => '游戏状态，1为游戏中',
        ];
    }
}

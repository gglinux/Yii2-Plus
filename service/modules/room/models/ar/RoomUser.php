<?php

namespace service\modules\room\models\ar;

use Yii;

/**
 * This is the model class for table "room_user".
 *
 * @property integer $id
 * @property integer $room_id
 * @property integer $user_id
 * @property integer $user_role
 * @property string $enter_time
 * @property string $exit_time
 * @property integer $exit_status
 * @property integer $status
 */
class RoomUser extends \yii\db\ActiveRecord
{
    const EXIT_STATUS_BLOCK_OUT = 2;
    CONST EXIT_STATUS_NOMARL_OUT = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room_user';
    }

    /**
     * @inheritdoc
     */
    public static function getDB()
    {
        return \Yii::$app->db_lianpa;  // 使用名为 "db" 的应用组件
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['room_id', 'user_id', 'user_role', 'exit_status', 'status'], 'integer'],
            [['enter_time', 'exit_time'], 'safe'],
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
            'user_id' => '用户id',
            'user_role' => '用户角色，1 为普通用户，2为趴主(房间主)',
            'enter_time' => '进入房间时间',
            'exit_time' => '退出房间时间',
            'exit_status' => '退出状态，1为正常，2 为被趴主踢出',
            'status' => '用户状态，1为正常',
        ];
    }
}

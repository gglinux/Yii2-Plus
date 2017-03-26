<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_relation_friend".
 *
 * @property string $id
 * @property string $uid
 * @property string $friend_uid
 * @property integer $status
 * @property integer $start_time
 * @property integer $start_way
 * @property integer $time_become
 * @property string $notes
 */
class UserRelationFriend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hjsk_user_relation_friend';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_user');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'friend_uid', 'status', 'start_time', 'start_way', 'time_become'], 'required'],
            [['uid', 'friend_uid', 'status', 'start_time', 'start_way', 'time_become'], 'integer'],
            [['notes'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'uid' => '用户ID',
            'friend_uid' => '用户朋友ID',
            'status' => '好友状态',
            'start_time' => '发起好友时间',
            'start_way' => '发起好友方式',
            'time_become' => '成为好友时间',
            'notes' => '备注',
        ];
    }
}

<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_relation_black".
 *
 * @property string $id
 * @property string $uid
 * @property string $friend_uid
 * @property integer $start_time
 * @property integer $status
 */
class UserRelationBlack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hjsk_user_relation_black';
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
            [['uid', 'touid', 'start_time', 'status'], 'required'],
            [['uid', 'touid', 'start_time', 'status'], 'integer'],
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
            'touid' => '拉黑用户ID',
            'start_time' => '拉黑时间',
            'status' => '状态 0未解除，1解除',
        ];
    }
}

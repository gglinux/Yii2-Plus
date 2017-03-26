<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_auth".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $identity_type
 * @property string $identifier
 * @property string $certificate
 * @property integer $create_time
 * @property integer $update_time
 */
class UserAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hjsk_user_auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'identity_type', 'create_time', 'update_time'], 'integer'],
            [['identifier'], 'string', 'max' => 50],
            [['certificate'], 'string', 'max' => 50],
            [['uid', 'identity_type'], 'unique', 'targetAttribute' => ['uid', 'identity_type'], 'message' => 'The combination of 用户id and 1手机号 2邮箱 3用户名 4qq 5微信 6腾讯微博 7新浪微博 has already been taken.'],
        ];
    }

    public static function getDb()
    {
        return Yii::$app->get('db_user');
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户id',
            'identity_type' => '1手机号 2邮箱 3用户名 4qq 5微信 6腾讯微博 7新浪微博',
            'identifier' => '手机号 邮箱 用户名或第三方应用的唯一标识',
            'certificate' => '密码凭证(站内的保存密码，站外的不保存或保存token)',
            'create_time' => '绑定时间',
            'update_time' => '更新绑定时间',
        ];
    }

}

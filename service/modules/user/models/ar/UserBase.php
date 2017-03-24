<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_base".
 *
 * @property integer $uid
 * @property integer $user_role
 * @property integer $register_source
 * @property string $user_name
 * @property string $nick_name
 * @property integer $gender
 * @property integer $birthday
 * @property string $signature
 * @property string $mobile
 * @property integer $mobile_bind_time
 * @property string $email
 * @property integer $email_bind_time
 * @property string $face
 * @property string $face200
 * @property string $srcface
 * @property integer $create_time
 * @property integer $update_time
 */
class UserBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_base}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'create_time', 'update_time'], 'required'],
            [['uid', 'user_role', 'register_source', 'gender', 'birthday', 'mobile_bind_time', 'email_bind_time', 'create_time', 'update_time'], 'integer'],
            [['user_name', 'nick_name'], 'string', 'max' => 32],
            [['signature', 'face', 'face200', 'srcface'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 16],
            [['email'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'user_role' => 'User Role',
            'register_source' => 'Register Source',
            'user_name' => 'User Name',
            'nick_name' => 'Nick Name',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'signature' => 'Signature',
            'mobile' => 'Mobile',
            'mobile_bind_time' => 'Mobile Bind Time',
            'email' => 'Email',
            'email_bind_time' => 'Email Bind Time',
            'face' => 'Face',
            'face200' => 'Face200',
            'srcface' => 'Srcface',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}

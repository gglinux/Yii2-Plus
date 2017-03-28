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
 * @property string $birthday
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
        return 'hjsk_user_base';
    }

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
            'uid' => '用户ID',
            'user_role' => '2正常用户 3禁言用户 4虚拟用户 5运营',
            'register_source' => '注册来源：1手机号 2邮箱 3用户名 4qq 5微信 6腾讯微博 7新浪微博',
            'user_name' => '用户账号，必须唯一',
            'nick_name' => '用户昵称',
            'gender' => '用户性别 0-female 1-male',
            'birthday' => '用户生日',
            'signature' => '用户个人签名',
            'mobile' => '手机号码(唯一)',
            'mobile_bind_time' => '手机号码绑定时间',
            'email' => '邮箱(唯一)',
            'email_bind_time' => '邮箱绑定时间',
            'face' => '头像',
            'face200' => '头像 200x200x80',
            'srcface' => '原图头像',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'push_token'  =>'推送token'
        ];
    }

    /**
     * 获取用户数据
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo()
    {
        return $this->hasOne(UserExtra::className(),['uid' => 'uid']);
    }
}

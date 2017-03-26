<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_register_log".
 *
 * @property integer $id
 * @property string $uid
 * @property integer $register_method
 * @property string $register_minute
 * @property integer $register_time
 * @property string $register_ip
 * @property string $register_client
 */
class UserRegisterLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hjsk_user_register_log';
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
            [['uid', 'register_method', 'register_minute', 'register_time', 'register_ip'], 'required'],
            [['uid', 'register_method', 'register_time'], 'integer'],
            [['register_minute', 'register_ip', 'register_client'], 'string', 'max' => 16],
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
            'register_method' => '注册方式1手机号 2邮箱 3用户名 4qq 5微信 6腾讯微博 7新浪微博',
            'register_minute' => '注册分钟',
            'register_time' => '注册时间',
            'register_ip' => '注册IP',
            'register_client' => '注册客户端',
        ];
    }
}

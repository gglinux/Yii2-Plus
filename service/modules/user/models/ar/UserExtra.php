<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_extra".
 *
 * @property integer $uid
 * @property string $vendor
 * @property string $client_name
 * @property string $client_version
 * @property string $os_name
 * @property string $os_version
 * @property string $device_name
 * @property string $device_id
 * @property string $idfa
 * @property string $idfv
 * @property string $market
 * @property integer $create_time
 * @property integer $update_time
 * @property string $extend1
 * @property string $extend2
 * @property string $extend3
 */
class UserExtra extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hjsk_user_extra';
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
            [['uid'], 'required'],
            [['uid', 'create_time', 'update_time'], 'integer'],
            [['vendor'], 'string', 'max' => 64],
            [['client_name', 'client_version', 'idfa', 'idfv'], 'string', 'max' => 50],
            [['os_name', 'os_version'], 'string', 'max' => 16],
            [['device_name'], 'string', 'max' => 32],
            [['device_id'], 'string', 'max' => 128],
            [['market'], 'string', 'max' => 20],
            [['extend1', 'extend2', 'extend3'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户 ID',
            'vendor' => '手机厂商：apple|htc|samsung，很少用',
            'client_name' => '客户端名称，如hjsk',
            'client_version' => '客户端版本号，如7.0.1',
            'os_name' => '设备号:android|ios',
            'os_version' => '系统版本号:2.2|2.3|4.0|5.1',
            'device_name' => '设备型号，如:iphone6s、u880、u8800',
            'device_id' => '设备ID',
            'idfa' => '苹果设备的IDFA',
            'idfv' => '苹果设备的IDFV',
            'market' => '来源',
            'create_time' => '添加时间',
            'update_time' => '更新时间',
            'extend1' => '扩展字段1',
            'extend2' => '扩展字段2',
            'extend3' => '扩展字段3',
        ];
    }
}

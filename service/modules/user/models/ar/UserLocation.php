<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_location".
 *
 * @property string $uid
 * @property string $curr_nation
 * @property string $curr_province
 * @property string $curr_city
 * @property string $curr_district
 * @property string $location
 * @property string $longitude
 * @property string $latitude
 * @property integer $update_time
 */
class UserLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hjsk_user_location';
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
            [['uid'], 'required'],
            [['uid', 'update_time'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['curr_nation', 'curr_province', 'curr_city'], 'string', 'max' => 10],
            [['curr_district'], 'string', 'max' => 20],
            [['location'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户ID',
            'curr_nation' => '所在地国',
            'curr_province' => '所在地省',
            'curr_city' => '所在地市',
            'curr_district' => '所在地地区',
            'location' => '具体地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'update_time' => '修改时间',
        ];
    }
}

<?php

namespace service\modules\user\models\ar;

use Yii;

/**
 * This is the model class for table "hjsk_user_auth".
 *
 * @property integer $id
 * @property integer $uid
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
            [['certificate'], 'string', 'max' => 20],
            [['uid', 'identity_type'], 'unique', 'targetAttribute' => ['uid', 'identity_type'], 'message' => 'The combination of Uid and Identity Type has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'identity_type' => 'Identity Type',
            'identifier' => 'Identifier',
            'certificate' => 'Certificate',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}

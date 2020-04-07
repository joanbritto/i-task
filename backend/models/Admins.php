<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "Projects".
 *
 * @property int $id
 */
class Admins extends \yii\mongodb\ActiveRecord{
    /**
     * {@inheritdoc}
     */
    public static function collectionName() {
        return ['i-task', 'Users'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes() {
        return [
            '_id', 'fullName', 'email', 'phone', 'password','status', 'tsCreatedAt', 'tsModifiedAt','passwordResetToken','approveStatus'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['_id', 'fullName', 'email', 'phone', 'password', 'status', 'tsCreatedAt', 'tsModifiedAt','passwordResetToken','approveStatus'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'fullName' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'status' => 'Status',
            'tsCreatedAt' => 'Created At',
            'tsModifiedAt' => 'Modified At',
            'approveStatus' => 'Approve/Disapprove'
        ];
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['tsCreatedAt', 'tsModifiedAt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['tsModifiedAt'],
                ],
                'value' => time(),
            ],
        ];
    }

}

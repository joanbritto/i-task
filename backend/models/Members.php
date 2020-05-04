<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
/**
 * This is the model class for table "Projects".
 *
 * @property int $id
 */
class Members extends \yii\mongodb\ActiveRecord{
    /**
     * {@inheritdoc}
     */
    public static function collectionName() {
        return ['i-task', 'Members'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes() {
        return [
            '_id', 'fullName', 'email', 'phone','position','image', 'password','status', 'tsCreatedAt', 'tsModifiedAt','passwordResetToken','approveStatus'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['_id', 'fullName', 'email', 'phone','position','image','password', 'status', 'tsCreatedAt', 'tsModifiedAt','passwordResetToken','approveStatus'], 'safe'],
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
            'position'=>'Position',
            'image' => 'Image',
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
    
    public function getProfileImage(){
        $model = Person::find()->where(['account_id'=>yii::$app->user->identity->id])->one();
        $imageUrl = '';
        if($model){
            $imageUrl = $model->image_url;
        }
        if($imageUrl){
            $locationPath = Yii::$app->params['base_path_profile_images'];
            $path = $locationPath.$imageUrl;
            return Url::to($path);
        }else{
            return Url::to('@web/img/default.jpg');
        }
    }
    
     public function getMemberById($id) {
        $objectId = yii::$app->utilities->getObjectId($id);
        return self::find()->where(['_id'=>(string)$objectId,'status'=>1])->one();
    }

}

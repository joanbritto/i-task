<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "TaskMembers".
 *
 * @property int $id
 */
class MemberTaskReports extends \yii\mongodb\ActiveRecord {

    public $memberId,$taskStatus;
    /**
     * {@inheritdoc}
     */
    public static function collectionName() {
        return ['i-task', 'MemberTaskReports'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes() {
        return [
            '_id', 'taskId', 'memberId', 'notes', 'status', 'tsCreatedAt', 'tsModifiedAt'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['_id', 'taskId', 'memberId', 'notes', 'status', 'tsCreatedAt', 'tsModifiedAt'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'notes' => 'Notes',
            'taskId' => 'Task',
            'memberId' => 'Member',            
            'status' => 'Status',
            'tsCreatedAt' => 'Created At',
            'tsModifiedAt' => 'Modified At',
        ];
    }
    
    public function getTask()
    {
        return $this->hasOne(Tasks::className(), ['_id' => 'taskId']);
    }
    public function getMember()
    {
        return $this->hasOne(Members::className(), ['_id' => 'memberId']);
    }
   
}

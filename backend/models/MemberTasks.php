<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "TaskMembers".
 *
 * @property int $id
 */
class MemberTasks extends \yii\mongodb\ActiveRecord {

    public $taskStatus;

    /**
     * {@inheritdoc}
     */
    public static function collectionName() {
        return ['i-task', 'MemberTasks'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes() {
        return [
            '_id', 'taskId', 'memberId', 'projectId', 'status', 'tsCreatedAt', 'tsModifiedAt'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['_id', 'taskId', 'memberId', 'projectId', 'status', 'tsCreatedAt', 'tsModifiedAt'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'projectId' => 'Project',
            'taskId' => 'Task',
            'memberId' => 'Member',
            'status' => 'Status',
            'tsCreatedAt' => 'Created At',
            'tsModifiedAt' => 'Modified At',
        ];
    }

    public function getTask() {
        return $this->hasOne(Tasks::className(), ['_id' => 'taskId']);
    }

    public function getMember() {
        return $this->hasOne(Members::className(), ['_id' => 'memberId']);
    }

    public function getProject() {
        return $this->hasOne(Projects::className(), ['_id' => 'projectId']);
    }
    
     

}

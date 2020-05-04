<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Tasks".
 *
 * @property int $id
 */
class Tasks extends \yii\mongodb\ActiveRecord {

    public $memberId,$taskStatus;
    /**
     * {@inheritdoc}
     */
    public static function collectionName() {
        return ['i-task', 'Tasks'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes() {
        return [
            '_id', 'projectId','isCompleted', 'taskName', 'dueDate', 'description', 'taskCreatedBy', 'status', 'tsCreatedAt', 'tsModifiedAt','completedDate'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['_id', 'projectId','isCompleted', 'taskName', 'dueDate', 'description', 'taskCreatedBy', 'status', 'tsCreatedAt', 'tsModifiedAt'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'projectId' => 'Project',
            'isCompleted' => 'is Completed?',
            'taskName' => 'Task',
            'dueDate' => 'Due Date',
            'description' => 'Description',
            'taskCreatedBy' => 'Created By',
            'status' => 'Status',
            'tsCreatedAt' => 'Created At',
            'tsModifiedAt' => 'Modified At',
            'memberId' => 'Member',
            'taskStatus'=>'Task Status',
            'completedDate' => 'Completed On'
        ];
    }
    
    public function getCreator()
    {
        return $this->hasOne(Admins::className(), ['_id' => 'taskCreatedBy']);
    }
    
    public function getProject()
    {
        return $this->hasOne(Projects::className(), ['_id' => 'projectId']);
    }
    
    public function getTaskSTatus($memberId,$taskId)
    {
        return $this->hasOne(Projects::className(), ['_id' => 'projectId']);
    }
    
    
}

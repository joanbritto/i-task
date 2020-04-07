<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Projects".
 *
 * @property int $id
 */
class Projects extends \yii\mongodb\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function collectionName() {
        return ['i-task', 'Projects'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes() {
        return [
            '_id', 'projectName', 'dueDate', 'description', 'projectCreatedBy', 'members', 'status', 'tsCreatedAt', 'tsModifiedAt'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['_id', 'projectName', 'dueDate', 'description', 'projectCreatedBy', 'members', 'status', 'tsCreatedAt', 'tsModifiedAt'], 'safe'],
            [['projectName', 'dueDate', 'description', 'projectCreatedBy'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            '_id' => 'ID',
            'projectName' => 'Project',
            'dueDate' => 'Due Date',
            'description' => 'Description',
            'projectCreatedBy' => 'Created By',
            'members' => 'Members',
            'status' => 'Status',
            'tsCreatedAt' => 'Created At',
            'tsModifiedAt' => 'Modified At',
        ];
    }
    
    public function getAdmin()
    {
        return $this->hasOne(Admins::className(), ['_id' => 'projectCreatedBy']);
    }

}

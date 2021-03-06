<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Projects;

/**
 * ProjectsSearch represents the model behind the search form of `backend\models\Projects`.
 */
class ProjectsSearch extends Projects
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ '_id', 'projectName', 'dueDate', 'description', 'projectCreatedBy', 'members', 'status', 'tsCreatedAt', 'tsModifiedAt'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $userId = Yii::$app->user->identity->objectId;
        //echo $userId;exit;
        //$query = Projects::find()->where(['status' => 1,'projectCreatedBy'=>(string)$userId]);
        $objectId = yii::$app->utilities->getObjectId($userId);
        $query = Projects::find()->where(['status' => 1,'projectCreatedBy'=>$objectId]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id]);
            

        return $dataProvider;
    }
}

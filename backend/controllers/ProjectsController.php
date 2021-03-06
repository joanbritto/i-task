<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Person;
use backend\models\Projects;
use backend\models\ProjectsSearch;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use backend\models\Tasks;
use backend\models\TasksSearch;

/**
 * Site controller
 */
class ProjectsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['index','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }   
    
      /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        //$searchModel = new TasksSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $projectObjectId = yii::$app->utilities->getObjectId($id);
        $tasks = Tasks::find()->where(['status' => 1,'projectId'=>$projectObjectId])->all();        
        return $this->render('view', ['model'=>$model,'tasks'=>$tasks]);
    }    
    
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Admins;
use backend\models\AdminsSearch;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class AdminsController extends Controller
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
                        'actions' => ['index','change-approve-status'],
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
        $searchModel = new AdminsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(isset($_SESSION['msg']) && $_SESSION['msg']){
            yii::$app->session->setFlash('success','Updated Successfully');
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }   
    
    public function actionChangeApproveStatus($id){
        $model = $this->findModel($id);
        $status = (isset($model->approveStatus)) ? $model->approveStatus : 0;
        $model->approveStatus = $status ? 0 : 1;
        $model->save();
       $_SESSION['msg'] =  true;
        return $this->redirect('index');
    }
    
    protected function findModel($id)
    {
        if (($model = Admins::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

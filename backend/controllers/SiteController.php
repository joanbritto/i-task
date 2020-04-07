<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\Account;
use backend\models\Person;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error','forgot-password','reset-password-user'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','profile'],
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
        return $this->render('index');
    }
    public function actionProfile(){
        $id = Yii::$app->user->identity->id;
        if(Yii::$app->user->identity->role == 'super-admin'){
            $model = Account::find()->where(['status'=>1,'id'=>$id])->one();
            $password_hash = $model->password_hash;
            $modelPerson = Person::find()->where(['status'=>1,'account_id'=>$id])->one();
            $oldImage = $modelPerson->image_url;
            $params = Yii::$app->request->post();
            if($params){
                if($model->load($params) && $modelPerson->load($params)){
                    if($params['Account']['password']){
                        $model->password_hash = $params['Account']['password'];
                    }else{
                        $model->password_hash = $password_hash;
                    }
                    if($model->validate() && $modelPerson->validate()){
                        $images = UploadedFile::getInstances($modelPerson,'image_url');
                        if($images && !empty($images)){
                            $imageLocation = Yii::$app->params['upload_path_profile_images'];
                            $saveImage = Person::uploadAndSave($images,$imageLocation);
                            if($saveImage){
                            $modelPerson->image_url = $saveImage;
                            }
                        }else{
                            $modelPerson->image_url = $oldImage;
                        }
                        if($params['Account']['password']){
                            $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($params['Account']['password']);
                        }
                        $model->save(false);
                        $modelPerson->save(false);
                        yii::$app->session->setFlash('success','Profile updated successfully');
                        return $this->redirect('index');
                    }
                }
            }
            // echo "<pre>";print_r($model);exit;
            return $this->render('profile',[
                'model' => $model,
                'modelPerson' => $modelPerson
            ]);
        }else{
            $model = Account::find()->where(['status'=>1,'id'=>$id])->one();
            $modelPerson = Person::find()->where(['status'=>1,'account_id'=>$id])->one();
            $oldImage = $modelPerson->image_url;
            $oldPassword = $model->password_hash;
            $oldBranchId = $modelPerson->branch_id;
            $params = Yii::$app->request->post();
            if($params){
                $model->setScenario('user-update');
                if($model->load($params) && $modelPerson->load($params) && $model->validate() && $modelPerson->validate()){
                    if($model->password){
                        $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                    }else{
                        $model->password_hash = $oldPassword;
                    }
                    $model->save(false);
                    $images = UploadedFile::getInstances($modelPerson,'image_url');
                    if($images && !empty($images)){
                        $imageLocation = Yii::$app->params['upload_path_profile_images'];
                        $saveImage = Person::uploadAndSave($images,$imageLocation);
                        if($saveImage){
                            $modelPerson->image_url = $saveImage;
                        }
                    }else{
                        $modelPerson->image_url = $oldImage;
                    }
                    $modelPerson->name = $model->emp_name;
                    $modelPerson->email = $model->email;
                    $modelPerson->account_id = $model->id;
                    if($modelPerson->branch_id){
                        $modelPerson->branch_id = json_encode($modelPerson->branch_id);
                    }else{
                        $modelPerson->branch_id = $oldBranchId;
                    }
                    $modelPerson->date_of_joining = date('Y-m-d',strtotime($modelPerson->date_of_joining));
                    $modelPerson->save(false);
                    yii::$app->session->setFlash('success','Profile updated successfully');
                    return $this->redirect(['index']);
                }
            }
            return $this->render('profile-update',[
                'model' => $model,
                'modelPerson' => $modelPerson
            ]);
        }
    }
    public function actionForgotPassword(){
        $model = new LoginForm();
        $params = Yii::$app->request->post();
        if($params){
            if($params['LoginForm']['email'] == ''){
                $model->addError('email','Email cannot be blank.');
            }else{
                $modelUser = $model->getUserData($params['LoginForm']['email']);
                if($modelUser){
                    $modelUser->generatePasswordResetToken();
                    $modelUser->update(false);
                    Yii::$app->email->sendPasswordReset($modelUser);
                    Yii::$app->session->setFlash('success', "We have sent mail containing link to reset your password.");
                    return $this->redirect('login');
                }else{
                    $model->addError('email','Invalid email address.');
                }
            }
        }
        return $this->render('forgot-password',[
            'model' => $model
        ]);
    }
    public function actionResetPasswordUser($token) {
        if(Yii::$app->user&&Yii::$app->user->identity&& Yii::$app->user->identity->id) Yii::$app->utilities->show404();
         $modelAccount = $this->findModelByResetToken($token);
         $post = Yii::$app->request->post();
         while(true) {
             $modelAccount->setScenario('reset-user-password');
        $proceed = $modelAccount->load(Yii::$app->request->post()) && $modelAccount->validate();
        $modelAcc = Account::find()->where(['password_reset_token'=>$token])->one();
        if(!$proceed)
         break;
        $password = $post['Account']['password'];
        $password_hash = $modelAcc->password_hash;
        if($post['Account']['password']){
            $modelAcc->password_hash = $post['Account']['password'];
        }else{
            $modelAcc->password_hash = $password_hash;
        }
        if($post['Account']['password']){
            $modelAcc->password_hash = Yii::$app->getSecurity()->generatePasswordHash($post['Account']['password']);
        }
        $modelAcc->save(false);
        return Yii::$app->getResponse()->redirect(['site/index']);
        break;
       }
       
         return $this->render('reset-password-user',['modelAccount'=>$modelAccount]);
     }
     protected function findModelByResetToken($token)
    {
        $model = Account::find()->where(['password_reset_token' => $token])->andWhere(['status'=> 1])->one();
        if ($model !== null) {
                return $model;
        } else {
                throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

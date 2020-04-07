<?php

namespace backend\models;
use yii\helpers\Url;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $password_hash
 * @property int|null $fk_person
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property string|null $password_token_expiry
 * @property string|null $email
 * @property int|null $is_banned
 * @property int $status
 * @property string|null $created_at
 * @property string $modified_at
 * @property string|null $phone
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }
    public $name,$password,$new_password,$branch,$qatarId,$address,$phone1,$date_of_joining,$image_url;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [ 
            [['status'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['username', 'password_hash', 'role', 'password_reset_token', 'auth_key','password_generated_yii','emp_code','emp_name','email'], 'string', 'max' => 255],
            [['username','password_hash'],'required'],
            [['password_hash','username'],'string','min'=>6],
            [['phone'],'number'],
            [['phone'],'is_unique'],
            [['username'],'is_unique_username'],
            [['password','new_password'],'string','min'=>6],
            [['password'],'is_pass_required'],
            ['new_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
            [['new_password','password'],'required','on'=>'reset-user-password'],
            [['emp_code','emp_name','email','new_password','password'],'required','on'=>'user-creation'],
            [['emp_code','emp_name','email'],'required','on'=>'user-update'],
            [['emp_code'],'is_unique_code'],
            [['email'],'is_unique_email'],
            [['first_approve','second_approve','third_approve','fourth_approve','fifth_approve','sixth_approve','seventh_approve'],'safe'],
           
        ];
    }
    public function scenarios() {
        return [
                  self::SCENARIO_DEFAULT => [
                    'username','password_hash','role', 'password_reset_token', 'auth_key','password_generated_yii',
                    'password','new_password','phone','first_approve','second_approve','third_approve','fourth_approve',
                    'fifth_approve','sixth_approve','seventh_approve'
                  ],
                  'reset-user-password' => ['new_password','password'],
                  'user-creation' => ['emp_code','emp_name','email','new_password','password','first_approve','second_approve','third_approve','fourth_approve',
                  'fifth_approve','sixth_approve','seventh_approve'],
                  'user-update' => ['emp_code','emp_name','email','new_password','password','first_approve','second_approve','third_approve','fourth_approve',
                  'fifth_approve','sixth_approve','seventh_approve'],
        ];
      }
      public function is_pass_required($attribute){
          $password = $this->password;
          $newPassword = $this->new_password;
          if($password && $newPassword == ''){
              $this->addError('new_password','Re-type password cannot be blank.');
              return false;
          }
      }
    public function is_unique($attribute){
        $phone = $this->phone;
        $query = static::find()->where(['phone'=>$phone])->andWhere(['status' => 1]);
        if($this->id) {
            $query->andWhere(['!=','id',$this->id]);
        }
        $err  = $query->one()!=null?true:false;
        if($err)
            $this->addError($attribute, Yii::t('app', ' phone "'.$phone. '" has already been taken.'));
            return $err;
    }
    public function is_unique_code($attribute){
        $emp_code = $this->emp_code;
        $query = static::find()->where(['emp_code'=>$emp_code])->andWhere(['status' => 1]);
        if($this->id) {
            $query->andWhere(['!=','id',$this->id]);
        }
        $err  = $query->one()!=null?true:false;
        if($err)
            $this->addError($attribute, Yii::t('app', ' emp_code "'.$emp_code. '" has already been taken.'));
            return $err;
    }
    public function is_unique_email($attribute){
        $email = $this->email;
        $query = static::find()->where(['email'=>$email])->andWhere(['status' => 1]);
        if($this->id) {
            $query->andWhere(['!=','id',$this->id]);
        }
        $err  = $query->one()!=null?true:false;
        if($err)
            $this->addError($attribute, Yii::t('app', ' email "'.$email. '" has already been taken.'));
            return $err;
    }
    public function is_unique_username($attribute){
        $username = $this->username;
        $query = static::find()->where(['username'=>$username])->andWhere(['status' => 1]);
        if($this->id) {
            $query->andWhere(['!=','id',$this->id]);
        }
        $err  = $query->one()!=null?true:false;
        if($err)
            $this->addError($attribute, Yii::t('app', ' username "'.$username. '" has already been taken.'));
            return $err;
    }
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'fk_person' => 'Fk Person',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'password_token_expiry' => 'Password Token Expiry',
            'email' => 'Email',
            'is_banned' => 'Is Banned',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'phone' => 'Phone',
            'emp_code' => 'Employee code',
            'emp_name' => 'Employee name',
            'new_password' => 'Re-type password',
            'first_approve' => 'First approval by',
            'second_approve' => 'Second approval by',
            'third_approve' => 'Third approval by',
            'fourth_approve' => 'Fourth approval by',
            'fifth_approve' => 'Fifth approval by',
            'sixth_approve' => 'Sixth approval by',
            'seventh_approve' => 'Seventh approval by',
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
            return Url::to('@web/img/default.png');
        }
    }

    public function search($params,$type){
        $query = Account::find()
        ->leftJoin('person','person.account_id=account.id')
        // ->leftJoin('branches','branches.id=person.branch_id')
        ->where(['account.status'=>1,'person.status'=>1])
        ->andWhere(['account.role'=>$type])
        ->select('account.*,person.qatar_id as qatarId')
        ->orderBy('account.id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);
        if(isset($params['Account']['emp_code'])){
            $query->andFilterWhere(['like', 'account.emp_code', $params['Account']['emp_code']]);
        }
        return $dataProvider;
    }

    public function getEmpCode(){
        return rand(10000,99999);
    }

    public function getBranch($id){
        $modelPerson = Person::find()->where(['account_id'=>$id])->one();
        $array = [];
        if($modelPerson->branch_id){
            $branchIds = json_decode($modelPerson->branch_id);
            if($branchIds && is_array($branchIds)){
                foreach($branchIds as $branchId){
                    $model = Branches::find()->where(['id'=>$branchId])->one();
                    if($model){
                        $array[] = ucfirst($model->name);
                    }
                }
            }
        }
        if($array){
            return implode(', ', $array);
        }else{
            return '-';
        }
    }

    public function getBuisnessEntityId($id){
        $modelPerson = Person::find()->where(['account_id'=>$id])->one();
        $array = [];
        if($modelPerson->buisness_entity_id){
            $branchIds = json_decode($modelPerson->buisness_entity_id);
            if($branchIds && is_array($branchIds)){
                foreach($branchIds as $branchId){
                    $model = BuisnessEntities::find()->where(['id'=>$branchId])->one();
                    if($model){
                        $array[] = ucfirst($model->name);
                    }
                }
            }
        }
        if($array){
            return implode(', ', $array);
        }else{
            return '-';
        }
    }
}

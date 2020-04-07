<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property int|null $account_id
 * @property string|null $image_url
 * @property int|null $lsgi_id
 * @property int $status
 * @property string $created_at
 * @property string $modified_at
 * @property string|null $gender
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $designation
 * @property string|null $district_id
 * @property int|null $lsgi_type_id
 * @property int|null $lsgi_block_id
 * @property string|null $phone
 */
class Person extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }
    public $addressVal,$phoneVal;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'lsgi_id', 'status', 'lsgi_type_id', 'lsgi_block_id'], 'integer'],
            [['created_at', 'modified_at','date_of_joining','address','branch_id','buisness_entity_id'], 'safe'],
            [['district_id'], 'string'],
            [[
                'name', 'email', 'image_url', 'gender', 'middle_name', 'last_name', 'designation', 
                'phone','qatar_id'
            ], 'string', 'max' => 255],
            [['phone'],'string','min'=>8,'max'=>15],
            [['image_url'], 'file', 'maxFiles' => 1,'extensions' => 'png, jpg'],
            [['phone'],'number'],
            [['qatar_id'],'is_unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'account_id' => 'Account ID',
            'image_url' => 'Image Url',
            'lsgi_id' => 'Lsgi ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'gender' => 'Gender',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'designation' => 'Designation',
            'district_id' => 'District ID',
            'lsgi_type_id' => 'Lsgi Type ID',
            'lsgi_block_id' => 'Lsgi Block ID',
            'phone' => 'Phone',
            'buisness_entity_id' => 'Buisness Entity',
            'branch_id' => 'Branch'
        ];
    }
    public function is_unique($attribute){
        $qatar_id = $this->qatar_id;
        $query = static::find()->where(['qatar_id'=>$qatar_id])->andWhere(['status' => 1]);
        if($this->id) {
            $query->andWhere(['!=','id',$this->id]);
        }
        $err  = $query->one()!=null?true:false;
        if($err)
            $this->addError($attribute, Yii::t('app', ' qatar id "'.$qatar_id. '" has already been taken.'));
            return $err;
    }
    public function uploadAndSave($images,$params=null)
    {
       $retId = false;
       if(!$images)
  	     $images = UploadedFile::getInstances($this,'uploaded_files');
       if(!is_array($images))
       {
         $images = [$images];
         $retId = true;
       }
       $ret= [];
       $uploads_path = Yii::$app->params['uploads_path'];
       if($params){
         $uploads_path = $params;
       }
       foreach($images as $image) {
            $newImage = Person::renameImage($image);
            $image_path = $uploads_path.$newImage;
            $image_full_path = Yii::getAlias($image_path);
            $image->saveAs($image_full_path);
            $ret = $newImage;
       }
       return $ret;
    }
    public static function renameImage($image)
    {
        $name_tmp = isset($image->name)?$image->name:$image['name'];
        $name_tmp = explode('.',$name_tmp );
        $ext = $name_tmp[sizeof($name_tmp)-1];
        array_pop($name_tmp);
        $unique_num  = sha1(time());
        $name_tmp[] = $unique_num;
        $name_tmp = implode('-',$name_tmp).'.'.$ext;
        $image = $name_tmp;
        return $image;
    }
}

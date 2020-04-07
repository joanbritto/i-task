<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\BuisnessEntities;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model backend\models\BuisnessEntities */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Update Profile: ' . $modelPerson->name;
$this->params['breadcrumbs'][] = 'Update';
?>
<?php 
    $buisnessEntities = BuisnessEntities::find()->where(['status' => 1])->all();
    $buisnessEntitiesData = ArrayHelper::map($buisnessEntities, 'id', 'name');
?>
<div class="accout-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'emp_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelPerson, 'image_url')->fileInput() ?>

    <?= $form->field($modelPerson, 'address')->textArea(['maxlength' => true,'rows'=>6]) ?> 

    <?= $form->field($modelPerson, 'qatar_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelPerson, 'phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("
$('#person-date_of_joining').datepicker({
    format: 'dd-mm-yyyy'
});
$('#s2-togall-buisness-id').hide();
$('#s2-togall-branch-id').hide();

");
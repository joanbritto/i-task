<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\District */

$this->title = 'Update Profile: ' . $modelPerson->name;
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="district-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="district-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelPerson, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelPerson, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelPerson, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelPerson, 'image_url')->fileInput() ?>
    <img src="<?=$model->getProfileImage()?>" class="user-image" alt="User Image"/ width="100" height="100">
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'autocomplete'=>'off']) ?>
    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 
</div>
    

</div>

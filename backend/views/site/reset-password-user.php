<?php
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use backend\models\Person;
 $name = Person::find()->where(['status'=>1,'account_id'=>$modelAccount->id])->one()->name;

?>
<div class="container">
	<div class="col-md-12">
		<h3>Hi <?= $name ?> ,</h3>
		<br>
		<p>Reset your password</p>
        <br>

	</div>
	<div class="col-md-12">

    <?php

      Pjax::begin(['enablePushState'=>false]);
      $form = ActiveForm::begin(['action'=>['site/reset-password-user','token'=>$modelAccount->password_reset_token]]);
    ?>
    <div class="form-group">
    <?= $form->field($modelAccount, 'password')->passwordInput(['maxlength' => true,'class'=>'form-control','placeholder'=>'Enter password here','value'=>""])->label('Password'); ?>
    </div>

    <div class="form-group">
    <?= $form->field($modelAccount, 'new_password')->passwordInput(['maxlength' => true,'class'=>'form-control','placeholder'=>'Repeat password here'])->label('Password Repeat'); ?>

     <?= $form->field($modelAccount, 'id',['template'=>'{input}'])->hiddenInput()->label('') ?>
    </div>
      <button type="submit"   class="btn btn-primary">RESET</button>
    <?php
    ActiveForm::end();
    Pjax::end();
    ?>


	</div>
	<div class="col-md-12">
	<hr />
	<br />
	<br />
	<br />
	<br />
	</div>
</div>
<style>
.sidebar:hover {
    width: 300px;
    visibility: hidden;
}
.sidebar {
    width: 300px;
    visibility: hidden;
}
</style>
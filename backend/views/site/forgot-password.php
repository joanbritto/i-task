<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Reset Password';

?>

<div class="login-box">
    <!-- <div class="login-logo">
        <a href="#"><b>Haritha Keralam</b></a>
    </div> -->
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Reset Password</p>

        <?php $form = ActiveForm::begin(['id' => 'reset-passsword-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'email')
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <a class='btn btn-danger btn-block btn-flat' href="<?=Url::to(['site/login'])?>">Back to login</a>
            </div>
            <!-- <div class="col-xs-4">
            </div> -->
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Reset', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>


    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

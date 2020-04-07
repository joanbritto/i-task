<?php
use yii\helpers\Html;
use backend\models\Account;
use yii\helpers\Url;

$id = Yii::$app->user->identity->id;
$model = Account::find()->leftJoin('person','person.account_id=account.id')
         ->where(['account.status'=>1])->andWhere(['person.status'=>1])->andWhere(['account.id'=>$id])
         ->select('person.*,account.username as username,person.phone as phone,account.emp_code as emp_code,account.role as role')->one();
/* @var $this \yii\web\View */
/* @var $content string */
$phone = $model->phone;
if(!$phone){
    $phone = '-';
}
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">iTask</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <!-- Tasks: style can be found in dropdown.less -->
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=$model->getProfileImage()?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?=Ucfirst($model->name)?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?=$model->getProfileImage()?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?=Ucfirst($model->name)?>
                                <?php if($model->role == "super-admin"){ ?>
                                    <small>username : <?=$model->username?></small>
                                    <small>phone : <?=$phone?></small>
                                <?php }else{ ?>
                                    <small>Emp code : <?=$model->emp_code?></small>
                                    <small>phone : <?=$phone?></small>
                                <?php }?>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=Url::to(['site/profile'])?>" class="btn btn-default btn-flat">Profile update</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
            
            </ul>
        </div>
    </nav>
</header>
<?php 
if(yii::$app->session->hasFlash('success')){ 
    $msg = yii::$app->session->getFlash('success');
    $this->registerJs("
        swal('$msg');
    ");
}
?>
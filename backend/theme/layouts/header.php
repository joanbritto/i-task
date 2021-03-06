<?php

use yii\helpers\Html;
use backend\models\Account;
use yii\helpers\Url;
use backend\models\Admins;

$id = Yii::$app->user->identity->id;
$username = Yii::$app->user->identity->username;
$role = yii::$app->user->identity->role;

$model = Account::find()->leftJoin('person', 'person.account_id=account.id')
                ->where(['account.status' => 1])->andWhere(['person.status' => 1])->andWhere(['account.id' => $id])
                ->select('person.*,account.username as username,person.phone as phone,account.emp_code as emp_code,account.role as role')->one();
/* @var $this \yii\web\View */
/* @var $content string */
if (!$model) {
    $model = Admins::find()->where(['email' => $username, 'status' => 1, 'approveStatus' => 1])->one();
}
$phone = (isset($model->phone)) ? $model->phone : '-';
$name = (isset($model->name)) ? $model->name : (isset($model->fullName) ? $model->fullName : '-');
$profileImage = ($model && $model->getProfileImage()) ? $model->getProfileImage() : '';
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
                        <img src="<?= $profileImage ?>" class="user-image" alt="Profile Image"/>
                        <span class="hidden-xs"><?= Ucfirst($name) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $profileImage ?>" class="img-circle"  alt="Profile Image"/>

                            <p>
                                <?= ucfirst($name) ?>
                                <small>Username : <?= $username ?></small>
                                <small>Phone : <?= $phone ?></small>

                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php if ($role == 'super-admin') { ?>
                                    <a href="<?= Url::to(['site/profile']) ?>" class="btn btn-default btn-flat">Profile Update</a>
                                <?php } ?>
                            </div>
                            <div class="pull-right">
                                <?=
                                Html::a(
                                        'Logout',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                )
                                ?>
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
if (yii::$app->session->hasFlash('success')) {
    $msg = yii::$app->session->getFlash('success');
    $this->registerJs("
        swal('$msg');
    ");
}
?>
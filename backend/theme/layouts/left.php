<?php

use yii\helpers\Url;
use yii\web\View;

$role = yii::$app->user->identity->role;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        

        <!-- search form -->
        
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site']],
                    ['label' => 'Projects', 'icon' => 'file-code-o', 'url' => ['/projects'],'visible' => ($role=='admin') ? true : false],
                    ['label' => 'Administrators', 'icon' => 'user-circle-o', 'url' => ['/admins'],'visible' => ($role=='super-admin') ? true : false],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],                   
                    ['label' => 'Logout','icon'=>'sign-out','url' => ['site/logout'], 'visible' => !Yii::$app->user->isGuest,'options'=>['class'=>'logout']],                   
                ],
            ]
        ) ?>

    </section>

</aside>

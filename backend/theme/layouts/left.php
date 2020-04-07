<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        

        <!-- search form -->
        
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Dashboard', 'icon' => 'file-code-o', 'url' => ['/site']],
                    ['label' => 'Projects', 'icon' => 'file-code-o', 'url' => ['/projects']],
                    ['label' => 'Administrators', 'icon' => 'file-code-o', 'url' => ['/admins']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],                   
                ],
            ]
        ) ?>

    </section>

</aside>

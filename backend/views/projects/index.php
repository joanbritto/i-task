<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //Pjax::begin();  ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'projectName',
            'dueDate',
            [
                'attribute' => 'projectCreatedBy',
                'format' => 'raw',
                'value' => function ($model) {
                    return (isset($model->admin) && isset($model->admin->fullName)) ? $model->admin->fullName : '-'; //$model->status;
                },
            ],
            ['class' => ActionColumn::className(),'template'=>'{view}' ]
        //['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

    <?php //Pjax::end(); ?>

</div>

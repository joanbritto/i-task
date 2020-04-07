<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Administrators');
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
            'fullName',
            'email',
            'phone',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return (isset($model->status) && $model->status) ? 'Active' : 'Inactive';
                },
            ],
            [
                'attribute' => 'approveStatus',
                'format' => 'raw',
                'value' => function ($model) {
                    $res = '';
                    if (isset($model->approveStatus) && $model->approveStatus) {
                        $res = "<a href='" . Url::to(['admins/change-approve-status', 'id' => (string) $model->_id]) . "' class='btn btn-danger' onclick='confirmDisapprove()'>Disapprove</a>";
                    } else {
                        $res = "<a href='" . Url::to(['admins/change-approve-status', 'id' => (string) $model->_id]) . "' class='btn btn-success' onclick='confirmApprove()'>Approve</a>";
                    }
                    return $res;
                },
            ],
        //['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

<?php //Pjax::end();  ?>

</div>
<script>
    function confirmApprove() {
        var r = confirm("Are you sure to Approve");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    }
    function confirmDisapprove() {
        var r = confirm("Are you sure to Disapprove");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    }
</script>

<?php
$message = '';
if (yii::$app->session->hasFlash('success')):
    $title = 'Success';
    $type = 'success';
    $title = Html::encode(trim($title));
    $message = yii::$app->session->getFlash('success');
endif;
if (yii::$app->session->hasFlash('error')):
    $title = 'Error';
    $type = 'error';
    $title = Html::encode(trim($title));
    $message = yii::$app->session->getFlash('error');
endif;

if ($message) {
    $this->registerJs("
  swal({title:'$title',text: '$message', type:'$type'});
  ");
    $_SESSION['msg'] = '';
}
?>
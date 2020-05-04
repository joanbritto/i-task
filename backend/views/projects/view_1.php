<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use backend\models\Tasks;
use backend\models\MemberTasks;
use backend\models\MemberTaskReports;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'View Project : ' . $model->projectName);
$this->params['breadcrumbs'][] = ['label'=>'Projects','url'=>'index'];
$this->params['breadcrumbs'][] = $this->title;
$taskStatusArr = ['Task Completed','Task Pending'];
?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>
    <!--Card-->
    <!--Card content-->
    <div class="card-body" style="margin-left: 100px;">
        <canvas id="pieChart" style="max-width: 800px;"></canvas>
    </div>

    <div class="users-index">

        <h1>Tasks</h1>       
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Task</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Description</th>
                    <th scope="col">Member</th>
                    <th scope="col">Task Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $datatArr = [];
                $completedCount=$pendongCount=0;
                if ($tasks) {

                    foreach ($tasks as $row) {
                        $taskObjectId = yii::$app->utilities->getObjectId($row->_id);
                        $projectObjectId = yii::$app->utilities->getObjectId($model->_id);

                        $memberData = MemberTasks::find()->where(['taskId' => $taskObjectId, 'projectId' => $projectObjectId, 'status' => 1])->one();
                        //$memberData = MemberTasks::find()->where(['status' => 1])->all();
                        $memberId = ($memberData && isset($memberData->memberId)) ? $memberData->memberId : '';
                        $memberName = $statusName = '';
                        if ($memberId) {
                            $members = MemberTasks::getMemberById($memberId);
                            $memberName = ($members) ? $members->fullName : '';
                            $memberObjectId = yii::$app->utilities->getObjectId($memberId);
                            $taskReports = MemberTaskReports::find()->where(['taskId' => $taskObjectId, 'memberId' => $memberObjectId, 'status' => 1])->one();

                            $statusName = ($taskReports && isset($taskReports->notes)) ? ucwords($taskReports->notes) : '';
                            //$datatArr[(string)$taskObjectId] = $statusName;
                            $datatArr[] = $statusName;
                            if($statusName=='Task Completed'){
                                $completedCount++;
                            }
                            if($statusName=='Task Pending'){
                                $pendongCount++;
                            }
                        }
                        ?>
                        <tr>
                            <th scope="row"><?= $row->taskName ?></th>
                            <td><?= $row->dueDate ?></td>
                            <td><?= $row->description ?></td>
                            <td><?= $memberName ? ucwords($memberName) : ''; ?></td>
                            <td><?= $statusName ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$vals = "'" . implode ( "', '", $taskStatusArr ) . "'";

//echo '<pre>';print_r($vals);exit;
$this->registerJs("
    //pie
    var ctxP = document.getElementById('pieChart').getContext('2d');
    var myPieChart = new Chart(ctxP, {
      type: 'pie',
      data: {
        //labels: ['Red', 'Green', 'Yellow', 'Grey', 'Dark Grey'],
        labels:[".$vals."],
        datasets: [{
          data: [$completedCount, $pendongCount],
          data: [$completedCount, $pendongCount],
          backgroundColor: ['#46BFBD', '#F7464A',],
          hoverBackgroundColor: ['#5AD3D1','#FF5A5E']
        }]
      },
      options: {
        responsive: true
      }
    });
");
?>

<?php
/*$vals = implode(",", $taskStatusArr);
//echo '<pre>';print_r($vals);exit;
$this->registerJs("
    //pie
    var ctxP = document.getElementById('pieChart').getContext('2d');
    var myPieChart = new Chart(ctxP, {
      type: 'pie',
      data: {
        //labels: ['Red', 'Green', 'Yellow', 'Grey', 'Dark Grey'],
        labels:".$vals.",
        datasets: [{
          data: [300, 50, 100, 40, 120],
          backgroundColor: ['#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
          hoverBackgroundColor: ['#FF5A5E', '#5AD3D1', '#FFC870', '#A8B3C5', '#616774']
        }]
      },
      options: {
        responsive: true
      }
    });
");*/
?>
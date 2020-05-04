<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use backend\models\Tasks;
use backend\models\Members;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'View Project : ' . $model->projectName);
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;
//$taskStatusArr = ['Task Completed', 'Task Pending'];
$taskStatusArr = ['Task Completed', 'Ongoing', 'Overdue'];
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
        <table class="table table-dark" style="color: #fff;background-color: #343a40;">
            <thead>
                <tr>
                    <th scope="col">Task</th>

                    <th scope="col">Description</th>
                    <th scope="col">Member</th>
                    <th scope="col">Task Status</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Completed On</th>
                    <th scope="col">No of Days</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $datatArr = [];
                $completedCount = $pendongCount = $overdueCount = $noOfDays = 0;
                if ($tasks) {

                    foreach ($tasks as $row) {
                        $taskObjectId = yii::$app->utilities->getObjectId($row->_id);
                        $projectObjectId = yii::$app->utilities->getObjectId($model->_id);
                        $memberId = $row->memberId;
                        $memberObjectId = yii::$app->utilities->getObjectId($memberId);

                        //$memberData = MemberTasks::find()->where(['taskId' => $taskObjectId, 'projectId' => $projectObjectId, 'status' => 1])->one();
                        //$memberData = MemberTasks::find()->where(['status' => 1])->all();
                        //$memberId = ($memberData && isset($memberData->memberId)) ? $memberData->memberId : '';
                        $dueDate = $row->dueDate;
                        $taskCreated = yii::$app->utilities->convertDate($row->tsCreatedAt);

                        $memberName = $statusName = $completedOn = '';
                        if ($memberId) {
                            $members = Members::getMemberById($memberId);
                            $memberName = ($members) ? $members->fullName : '';
                            $statusName = '';
                            $currentDate = date('d-m-Y');
                            if ($row->isCompleted == true) {
                                $bgColor = '#00C67A';
                                $statusName = 'Task Completed';
                                //$completedOn = ($row->completedDate) ? yii::$app->utilities->dateFormat($row->completedDate) : '';
                                $completedOn = ($row->completedDate) ? $row->completedDate : '';
                                $noOfDays = yii::$app->utilities->dateDiffInDays($completedOn, $dueDate);
                                $noOfOverDue = yii::$app->utilities->dateDiffInDays($completedOn, $currentDate);
                                
                                 if (strtotime($completedOn) >= strtotime('today')) {
                                      $days = "Completed in $noOfDays days";
                                    $bgColor = '#A01497';
                                 }else{
                                      if($noOfOverDue==1){
                                        $days = "Completed $noOfDays day ago";
                                    }else{
                                        $days = "Completed $noOfDays days ago";
                                    }
                                    $bgColor = '#009AE0';   
                                 }
                               /* if ($noOfOverDue > 0) {
                                                                    
                                } else {
                                   
                                }*/

                                $completedCount++;
                            } else {
                                $noOfDays = yii::$app->utilities->dateDiffInDays($currentDate, $dueDate);

                                if (strtotime($dueDate) >= strtotime('today')) { //if ($statusName == 'Task Pending') {
                                    $bgColor = '#F7464A';
                                    $statusName = 'Ongoing';
                                    $pendongCount++;
                                } else if (strtotime($dueDate) < strtotime('today')) { //if ($statusName == 'Overdue') {
                                    $bgColor = '#D0021B';
                                    $statusName = 'Overdue';
                                    $overdueCount++;
                                }
                                if ($noOfDays == 1) {
                                    $days = "$noOfDays day $statusName";
                                } else {
                                    $days = "$noOfDays days $statusName";
                                }
                            }
                        }
                        ?>
                        <tr  style="background-color:<?= $bgColor ?>">
                            <th scope="row"><?= $row->taskName ?></th>

                            <td><?= $row->description ?></td>
                            <td><?= $memberName ? ucwords($memberName) : ''; ?></td>
                            <td><?= $statusName ?></td>
                            <td><?= $row->dueDate ?></td>
                            <td><?= ($statusName == 'Task Completed') ? $completedOn : ''; ?></td>
                            <td><?= $days ?></td>
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
$vals = "'" . implode("', '", $taskStatusArr) . "'";

//echo '<pre>';print_r($vals);exit;
$this->registerJs("
    //pie
    var ctxP = document.getElementById('pieChart').getContext('2d');
    var myPieChart = new Chart(ctxP, {
    plugins: [ChartDataLabels],
      type: 'pie',
      data: {
        //labels: ['Red', 'Green', 'Yellow', 'Grey', 'Dark Grey'],
        labels:[" . $vals . "],
        datasets: [{
          data: [$completedCount, $pendongCount,$overdueCount],
          backgroundColor: ['#00C67A', '#F7464A','#D0021B'],
          hoverBackgroundColor: ['#00C67A','#FF5A5E','#D0021B']
        }]
      },
      options: {
        responsive: true,
        legend: {
      position: 'right',
      labels: {
        padding: 20,
        boxWidth: 10
      }
    },
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
        console.log(value);
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value * 100 / sum).toFixed(2) +  '%' ;
          return percentage;
        },
        color: 'white',
        labels: {
          title: {
            font: {
              size: '16'
            }
          }
        }
      }
      }
    
      }
    });
");
?>

<?php
/* $vals = implode(",", $taskStatusArr);
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
  "); */
?>
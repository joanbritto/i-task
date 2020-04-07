<?php
use yii\helpers\Url;
// $logo = Url::to('@web/images/logo.png');
// $logoUrl =  Url::to([$logo],true);
$from = $this->params['from'];
$to = $this->params['to'];
$toName = $to['name'];
$subject = $this->params['subject'];
// $fromName = Yii::$app->utilities->getFullName($from);
$fromName = $from;
$fromPosition = isset($from['position'])?$from['position']:'Waste Management';
$heading = isset($this->params['heading'])?$this->params['heading']:$subject;
// $fromName = ucwords($fromName);
$toName = ucwords($toName);
$fromPosition = ucwords($fromPosition);
$footerPersonName = $fromName['email'];
$footerPersonPosition = $fromPosition;
if(isset($this->params['signatureInfo'])) {
  $signatureInfo = $this->params['signatureInfo'];
  $footerPersonName = $signatureInfo['name'];
  $footerPersonPosition = '';
  if(isset($signatureInfo['position'])) {
    $footerPersonPosition = $signatureInfo['position'];
  }
}
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Waste Management</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
          .cont * {
                max-width: 700px !important;
                word-wrap: break-word !important;
          }
        </style>
    </head>
    <body style="margin:0;padding:0;width:100%;">
      <div class="cont" style="width:700px;margin:auto;">
        <header style="height:100px;width:100%;margin:0;">
          <a href = 'http://britejobs.co.in'>
            <img src="<?php //echo $logoUrl;?>" style="height:50px;margin:25px 50px;" />
          </a>
        </header>
        <div style="background:#fafafa;width:100%;padding:25px 50px 0 50px;box-sizing:border-box;overflow:hidden;word-wrap:break-word">
          <h2 style="color:#555;font-size:26px;"><?= $heading?></h2>

          <p style="color:#9c9c9c;font-size:16px;font-weight:bold">
            Dear <?= $toName?>,<br />
          </p>
			       <?= $content;?>
            <div style="color:#9c9c9c;font-size:16px;">
        </div>
       </div>
      </div>
    </body>
</html>

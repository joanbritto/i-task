<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use frontend\models\TbMessage;
use frontend\models\TbPackage;
use backend\models\Person;
use jobs\models\TbFile;
class EmailComponent extends Component
{
  public $SENDGRID_API_KEY;
  function __construct($config=[]) {
      parent::__construct($config);

  }
  public function getList($name) {
    $url = "https://api.sendgrid.com/v3/contactdb/lists";
    $sendgrid = Yii::$app->sendGrid;
    $response = $this->curlGet($url,$headers);
  }
  public function addList($name) {
    $url = 'https://api.sendgrid.com/v3/contactdb/lists';
    $sendgrid = Yii::$app->sendGrid;
    $data = [
      'name' => $name
    ];
    $data = json_encode($data);
    $token = $this->SENDGRID_API_KEY;
    $headers = [
      "Authorization: Bearer $token"
    ];
    $response = $this->curlPost($url,$data,$headers);
    $response = json_decode($response);
    return $response;
  }
  public function addSubscriber($email) {
    $listName = isset(Yii::$app->params['subscriber-list-name'])?Yii::$app->params['subscriber-list-name']:'marketing';
    $this->addList($listName);
    $url = "https://api.sendgrid.com/v3/contactdb/recipients";
    $sendgrid = Yii::$app->sendGrid;
    $token = $this->SENDGRID_API_KEY;
    $data = [
      [
        'email' => $email
      ]
    ];
    $data =  json_encode($data);
    $headers = [
      "Authorization: Bearer $token"
    ];
    $response = $this->curlPost($url,$data,$headers);
    $params = [];
    $response = json_decode($response);
    return $response;
  }
  public function curlPost($url, $data=NULL, $headers = NULL) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if(!empty($data)){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);

    if (curl_error($ch)) {
        trigger_error('Curl Error:' . curl_error($ch));
    }

    curl_close($ch);
    return $response;
}

  public function curlGet($url, $headers = NULL) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);

    if (curl_error($ch)) {
        trigger_error('Curl Error:' . curl_error($ch));
    }

    curl_close($ch);
    return $response;
}
public function sendEmail($modelMemo) {
 // $modelPerson = $modelUser->fk_person;
 //  if(!$modelPerson) return;
 //  $modelPerson = $this->findModelPerson($modelUser->fk_person);
 $email = $modelMemo->email;
 $name = $modelMemo->name;
 $amount = $modelMemo->amount;
 $description = $modelMemo->description;
 $memoId = $modelMemo->id;
    // $resetToken = $modelUser->password_reset_token;
 $to = [
  'name' => $name,
  'email' => $email
 ];
 $from = [
   'email'=>'no-reply@wms.com'
 ];

 $params = [
    // 'reset-token'=>$resetToken,
    // 'heading'=>'Forgot your password?. No problem',
    'memoId' => $memoId,
    'amount' => $amount,
    'name'=>$name,
    'description' => $description
  ];
 $view = 'memo-send-mail';
 // $subject = 'Reset your password | Obeid-al-abdi.com';
 $subject = $modelMemo->subject;
 $message = '';

  $this->sendMail($from,$to,$subject,$message,$view,$params);

}
public function sendPaidEmail($modelMemo) {
 // $modelPerson = $modelUser->fk_person;
 //  if(!$modelPerson) return;
 //  $modelPerson = $this->findModelPerson($modelUser->fk_person);
 $email = $modelMemo->email;
 $name = $modelMemo->name;
 $amount = $modelMemo->amount;
 $description = $modelMemo->description;
 // $resetToken = $modelUser->password_reset_token;
 $to = [
  'name' => $name,
  'email' => $email
 ];
 $from = [
   'email'=>'no-reply@wms.com'
 ];

 $params = [
    // 'reset-token'=>$resetToken,
    // 'heading'=>'Forgot your password?. No problem',
    'amount' => $amount,
    'name'=>$name,
    'description' => $description
  ];
 $view = 'memo-paid-send-mail';
 // $subject = 'Reset your password | Obeid-al-abdi.com';
 $subject = $modelMemo->subject;
 $message = '';

  $this->sendMail($from,$to,$subject,$message,$view,$params);

}
public function sendPaidInvoice($modelMemo,$modelLsgi) {
 // $modelPerson = $modelUser->fk_person;
 //  if(!$modelPerson) return;
 //  $modelPerson = $this->findModelPerson($modelUser->fk_person);
 $email = $modelMemo->email;
 $name = $modelMemo->name;
 $amount = $modelMemo->amount;
 $description = $modelMemo->description;

 if($modelLsgi){
   $modelLogoImage        = $modelMemo->getImage($modelLsgi->image_id);
   if(isset($modelLogoImage)?$modelLogoImage:''){
     $url = $modelLogoImage->uri_full;
     $path =  Yii::$app->params['logo_image_base_url'];
     $logoUrl = $modelLogoImage->getFullUrl($url,$path);
    }
  }
  if($modelLsgi)
    $lsgiAddress = $modelLsgi->address;
 // $resetToken = $modelUser->password_reset_token;
 $to = [
  'name' => $name,
  'email' => $email
 ];
 $from = [
   'email'=>'no-reply@wms.com'
 ];

 $params = [
    // 'reset-token'=>$resetToken,
    // 'heading'=>'Forgot your password?. No problem',
    'amount' => $amount,
    'name'=>$name,
    'description' => $description,
    'logoUrl' => $logoUrl,
    'lsgiAddress' => $lsgiAddress
  ];
 $view = 'invoice';
 // $subject = 'Reset your password | Obeid-al-abdi.com';
 $subject = $modelMemo->subject;
 $message = '';

  $this->sendMail($from,$to,$subject,$message,$view,$params);

}
  public function sendPasswordReset($modelUser) {
    $modelPerson = Person::find()->where(['status'=>1,'account_id'=>$modelUser->id])->one();
    $email = $modelPerson->email;
    $firstName = $modelPerson->name;
    $to = [
      'first-name' => $firstName,
      'name' => $firstName,
      'email' => $email,
    ];
    $from = [
      'email'=>'joseph@cocoalabs.in'
    ];
    // $accountUrl = Yii::$app->frontendUtilities->getUrl('account');
    $params = [
      'heading'=>'Reset Your Password',
      'name'=>$firstName,
      'reset-token'=>$modelUser->password_reset_token,
      // 'accountUrl' => $accountUrl
    ];
    $view = 'password-reset-mail';
    $subject = 'Reset your password';
    $heading = 'Reset password';
    $message = '';
    $this->sendMail($from,$to,$subject,$message,$view,$params);
  }


  public function sendMailOld($from,$to,$subject,$message,$view='default-mail',$params =[],$ccs=[],$bccs=[]) {
    // $msg = "First line of text\nSecond line of text";
    // $msg = wordwrap($msg,70);
    // ini_set("SMTP","smtp.gmail.com");
    // ini_set("smtp_port","587");
    // ini_set('sendmail_from', 'joanbritto18@gmail.com');
    // $res = mail("jinokt34@gmail.com","My subject",$msg);
    // if($res){
    //   print_r("sent");
    // }else{
    //   prnt_r("error");exit;
    // }
    $view = '/mail/'.$view;
    Yii::$app->controller->layout = 'email';
    $view = Yii::$app->controller->renderPartial($view,['params'=>$params]);
    $message=$view;
    $boundary = md5("random");
     // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (RFC)
    $eol = "\r\n";
    
    // main header (multipart mandatory)
    $headers = "From: Harithakeralam <joanbritto18@gmail.com>" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    //$headers .= "This is a MIME encoded message." . $eol;
    

    // message
    $body = "--" . $separator . $eol;
    /*$body .= "Content-Type: appilcation/pdf; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol;
    $body .= $message . $eol;*/
    
    $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
   // $body .= "Content-Type: text/html; charset=UTF-8\r\n";

    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";  
    //$body .= chunk_split(base64_encode($message));  
     $body .= $message . $eol;
    
    
    $to = "jinokt34@gmail.com";
    // $to = $to['email'];
    mail($to, $subject, $body, $headers);
 }
 public function sendMail($from,$to,$subject,$message,$view='default-mail',$params =[],$ccs=[],$bccs=[], $attachment = null) {
  $sendGrid = Yii::$app->sendGrid;
  $fromEmail = $from['email'];
  $toEmail = $to['email'];

  $paramsTemp = [
   'from' => $from,
   'to' => $to,
   'subject' => $subject,
   'message' => $message
  ];
  $params = array_merge($params, $paramsTemp);
    $sendGrid->view->params['from'] = $from;
    $sendGrid->view->params['to'] = $to;
    $sendGrid->view->params['subject'] = $subject;
    foreach($params as $param => $val) {
      $sendGrid->view->params[$param] = $val;
    }

    $message = $sendGrid->compose($view,['params'=>$params]);
    $message->setFrom($fromEmail)->setTo($toEmail)->setSubject($subject);
    // foreach($ccs as $cc) {
    //   $message->setCc($cc);
    // }
    $message->getSendGridMessage()->setCcs($ccs);
    $message->getSendGridMessage()->setBccs($bccs);
    
    if($attachment){

      $message->attach($attachment);

    }
   
    
  $response = $message->send($sendGrid);
  

    foreach($sendGrid->view->params as $param => $val) { // otherwise headings may overlapp if two mails sent one after another in same method
      unset($sendGrid->view->params[$param]);
    }

 }
  protected function findModelPerson($personId)
  {
      $modelPerson = Person::find()->where(['status'=>1])->andWhere(['id' => $personId])->one();
        if($modelPerson)
      {
          return $modelPerson;
      }

      throw new NotFoundHttpException('The requested page does not exist.');
  }
}

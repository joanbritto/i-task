<?php
 use yii\helpers\Url;
 $token = $params['reset-token'];
 $activationUrl =  Url::toRoute(['site/reset-password-user','token'=>$token],'http' );
 $name = ucfirst($params['name']);
?>
<p style="color:#9c9c9c;font-size:16px;">
  Somebody tried to reset your password. If that were you, you can use the following button to reset your password.
</p>
<a href="<?=$activationUrl?>" target="_blank" style="margin:45px 0;outline:0;display:block;width:200px;margin-left:50px;background:#3c4451;color:#fff;font-size:18px;text-decoration:none;padding:13px 16px;border-radius:5px;text-align:center;">
  Reset Password
</a>
<p style="color:#9c9c9c;font-size:16px;">
  or you can copy the below link in your browser
</p>
 <?= $activationUrl; ?>

<?php
define('UPLOADS_PATH','http://172.104.61.150/i-task/common/uploads');
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,



    'uploads_path' => '@common/uploads/',

    'upload_path_profile_images' => '@uploads/profile/',
    'base_path_profile_images' => UPLOADS_PATH.'/profile/',
];

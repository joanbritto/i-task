<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@uploads' => '@common/uploads',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'email' => [
            'class'=>'backend\components\EmailComponent'
        ],
        'sendGrid' => [
            'class' => 'bryglen\sendgrid\Mailer',
            'username' => 'joancocoalabs',
            'password' => '@N;ie;Mw&QuU2?L',
            'viewPath' => '@app/views/mail'
        ],
        'utilities' => [
            'class' => 'backend\components\UtilityComponent'
        ],
    ],
];

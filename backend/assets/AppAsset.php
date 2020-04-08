<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap',
        //'css/bootstrap.min.css',
        'css/site.css',
        'css/sweetalert.css',  
        'css/mdb.min.css',
       
    ];
    public $js = [        
        //'js/jquery.min.js',
        //'js/bootstrap.min.js',
        'js/sweetalert.min.js',
        'js/popper.min.js',        
        'js/mdb.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

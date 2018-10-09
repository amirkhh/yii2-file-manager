<?php

namespace amirkhh\filemanager;

use yii\web\AssetBundle;


class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/amirkh/yii2-file-manager/assets';

    public $css = [
        'css/file-manager.css'
    ];

    public $js = [
        'js/ng-file-upload.js',
        'js/paging.js',
        'js/file-manager.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset',
        AngularAsset::class,
    ];
}

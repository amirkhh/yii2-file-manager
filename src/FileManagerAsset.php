<?php

namespace amirkh\FileManager;

use yii\web\AssetBundle;


class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/amirkh/yii2-file-manager';

    public $css = [
        ''
    ];

    public $js = [
        'js/angular.min.js',
        'js/admin/angular-module.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset',
    ];
}

<?php

namespace amirkhh\filemanager;

use yii\web\AssetBundle;


class AngularAsset extends AssetBundle
{
    public $sourcePath = '@vendor/amirkh/yii2-file-manager/assets';

    public $js = [
        'js/angular.min.js',
    ];
}

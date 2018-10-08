<?php

namespace amirkhh\filemanager;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UploadFileAction for images and files.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'upload-image' => [
 *             'class' => 'vova07\imperavi\actions\UploadFileAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'unique' => true,
 *             'validatorOptions' => [
 *                 'maxWidth' => 1000,
 *                 'maxHeight' => 1000
 *             ]
 *         ],
 *         'file-upload' => [
 *             'class' => 'vova07\imperavi\actions\UploadFileAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'uploadOnlyImage' => false,
 *             'translit' => true,
 *             'validatorOptions' => [
 *                 'maxSize' => 40000
 *             ]
 *         ]
 *     ];
 * }
 * ```
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07/yii2-imperavi-widget
 */
class UploadAction extends Action
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        $model = new UploadForms();

        $data['ok'] = false;

        if(Yii::$app->request->isPost)
        {
            $model->files = UploadedFile::getInstancesByName('files');

            if (($file = $model->upload()) !== false) {
                $data['ok'] = true;
            }
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;
    }
}

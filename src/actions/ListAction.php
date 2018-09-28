<?php

namespace amirkh\FileManager;

use app\models\File;
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
class ListAction extends Action
{
    /**
     * Generate a human readable size informations from provided Byte/s size
     *
     * @param integer $size The size to convert in Byte
     * @return string The readable size definition
     */
    private function humanReadableFilesize($size)
    {
        $mod = 1024;
        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; ++$i) {
            $size /= $mod;
        }

        return round($size, 2).' '.$units[$i];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $data['ok'] = false;

        if(($models = File::find()->all()) != null)
        {
            $data['ok'] = true;

            /** @var File $model */
            foreach ($models as $model)
            {
                $data['models'][$model->id] = [
                    'id'        => $model->id,
                    'user_id'   => $model->user_id,
                    'name'      => $model->name,
                    'mimeType'  => $model->mime_type,
                    'isImage'   => (in_array($model->extension, ['jpg', 'png', 'gif'])),
                    'extension' => $model->extension,
                    'size'      => $this->humanReadableFilesize($model->size),
                    'createdAt' => $model->created_at,
                ];
            }
        }

        $response         = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data   = $data;
    }
}

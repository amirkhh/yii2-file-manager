<?php

namespace amirkhh\filemanager\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\web\UploadedFile;
use amirkhh\filemanager\models\UploadForms;

/**
 * UploadFileAction for upload files.
 *
 * Usage:
 *
 * ```php
 * class SiteController extends Controller
 * {
 *     public function actions()
 *     {
 *         return [
 *             'file-upload' => [
 *                 'class' => UploadAction::class,
 *                 'uploadDirectory' => 'uploads/files/',# Optional
 *             ]
 *         ];
 *     }
 * }
 * ```
 *
 * @author Amir Khoshhal <amirkhoshhal@gmail.com>
 *
 * @link https://github.com/amirkhh/yii2-file-manager
 */
class UploadAction extends Action
{
    public $uploadDirectory = 'uploads/files/';

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

            if (($file = $model->upload($this->uploadDirectory)) !== false) {
                $data['ok'] = true;
            }
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;
    }
}

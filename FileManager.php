<?php

namespace amirkhh\filemanager;

use yii\base\Widget;
use amirkhh\filemanager\models\UploadForms;

class FileManager extends Widget
{
    public $form;

    public $files;/** Already Files Uploaded For Edit Page */
    public $fileListUrl     = 'site/file-list';
    public $fileUploadUrl   = 'site/file-upload';
    //public $uploadDirectory = 'uploads/files/';
    public $filesOutputName = 'filesData';
    public $allowExtension  = null;
    public $accept          = 'image/*';
    public $maxFileCount    = 10;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $model = new UploadForms();

        return $this->render('file-manager', [
            'model' => $model,
            'form' => $this->form,
            'filesOutputName' => $this->filesOutputName,
            'files' => $this->files,
            'fileListUrl'   => $this->fileListUrl,
            'fileUploadUrl' => $this->fileUploadUrl,
            'maxFileCount' => $this->maxFileCount,
            'accept' => $this->accept,
        ]);
    }
}

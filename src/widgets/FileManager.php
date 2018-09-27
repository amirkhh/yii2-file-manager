<?php

namespace amirkh\FileManager;

use yii\base\Widget;
use yii\web\View;

class FileManager extends Widget
{
    public $form;

    public $filesOutputName = 'filesData';

    /* Already Files Uploaded */
    public $files;

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
        ]);
    }
}

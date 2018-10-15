<?php

namespace amirkhh\filemanager;

use Yii;
use yii\base\Widget;
use amirkhh\filemanager\models\UploadForms;
use yii\web\BadRequestHttpException;

class FileManager extends Widget
{
    public $form;

    public $files;/** Already Files Uploaded For Edit Page */
    public $fileListUrl     = '../site/file-list';
    public $fileUploadUrl   = '../site/file-upload';
    public $filesOutputName = 'filesData';
    public $allowExtension  = null;
    public $accept          = 'image/*';
    public $maxFileCount    = 10;

    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;

        $i18n->translations['widgets/filemanager/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@vendor/amirkh/yii2-file-manager/messages',
            'fileMap' => [
                'widgets/filemanager/messages' => 'messages.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [])
    {
        return Yii::t('widgets/filemanager/' . $category, $message, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if($this->form == null)
            throw new BadRequestHttpException(FileManager::t('messages', 'Please Send "form" Parameter.'));

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

<?php
namespace amirkhh\filemanager\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use amirkhh\filemanager\models\File;

class UploadForms extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    private $dangerousMimeTypes = [
        'application/x-msdownload',
        'application/x-msdos-program',
        'application/x-msdos-windows',
        'application/x-download',
        'application/bat',
        'application/x-bat',
        'application/com',
        'application/x-com',
        'application/exe',
        'application/x-exe',
        'application/x-winexe',
        'application/x-winhlp',
        'application/x-winhelp',
        'application/x-javascript',
        'application/hta',
        'application/x-ms-shortcut',
        'application/octet-stream',
        'vms/exe',
        'text/javascript',
        'text/scriptlet',
        'text/x-php',
        'text/plain',
        'application/x-spss',
    ];

    private $dangerousExtensions = [
        'html', 'php', 'phtml', 'php3', 'exe', 'bat', 'js',
    ];

    public function rules()
    {
        return [
            ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
        ];
    }

    public function upload($uploadDirectory)
    {
        if($this->validate())
        {
            $now = time();

            foreach($this->files as $file)
            {
                if(!in_array($file->type, $this->dangerousMimeTypes) && !in_array($file->extension, $this->dangerousExtensions))
                {
                    /* Upload Files */
                    $dir      = $uploadDirectory;
                    $fileName = Yii::$app->security->generateRandomString(5) . time() . '.' . strtolower($file->extension);
                    $filePath = $dir.$fileName;
                    $hashFile = $this->md5sum($file->tempName);

                    if(($model = File::find()->where(['hash_file' => $hashFile])->one()) == null)
                    {
                        if(!file_exists($dir)) mkdir($dir, 0777, true);

                        $file->saveAs($filePath);

                        $model = new File();

                        $model->name       = $fileName;
                        $model->mime_type  = $file->type;
                        $model->extension  = $file->extension;
                        $model->hash_file  = $hashFile;
                        $model->size       = $file->size;
                        $model->created_at = $now;

                        $model->save();
                    }
                }
                else
                {
                    continue;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    private function md5sum($sourceFile)
    {
        return file_exists($sourceFile) ? hash_file('md5', $sourceFile) : false;
    }
}
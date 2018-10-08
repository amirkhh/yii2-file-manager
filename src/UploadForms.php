<?php
namespace app\modules\admin\models;

use app\components\FileHelper;
use Yii;
use app\models\File;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForms extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules()
    {
        return [
            ['files', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if($this->validate() || true)
        {
            $userId = Yii::$app->user->id;
            $now    = time();

            foreach($this->files as $file)
            {
                if(!in_array($file->type, FileHelper::DANGEROUS_MIME_TYPES) && !in_array($file->extension, FileHelper::DANGEROUS_EXTENSIONS))
                {
                    /* Upload Files */
                    $dir      = 'uploads/files/';
                    $fileName = Yii::$app->security->generateRandomString(5) . time() . '.' . strtolower($file->extension);
                    $filePath = $dir.$fileName;
                    $hashFile = FileHelper::md5sum($file->tempName);

                    if(($model = File::find()->where(['hash_file' => $hashFile])->one()) == null)
                    {
                        if(!file_exists($dir)) mkdir($dir, 0777, true);

                        $file->saveAs($filePath);

                        $model = new File();

                        $model->user_id    = $userId;
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
}
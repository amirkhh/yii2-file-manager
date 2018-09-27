<?php
namespace amirkh\FileManager;

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
    public $file;

    public function rules()
    {
        return [
            ['file', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],
        ];
    }

    public function upload()
    {
        if($this->validate() || true)
        {
            $userId = Yii::$app->user->id;
            $now    = time();

            if(!in_array($this->file->type, FileHelper::DANGEROUS_MIME_TYPES) && !in_array($this->file->extension, FileHelper::DANGEROUS_EXTENSIONS))
            {
                /* Upload Files */
                $dir      = 'uploads/files/';
                $fileName = Yii::$app->security->generateRandomString(5) . time() . '.' . strtolower($this->file->extension);
                $file     = $dir.$fileName;
                $hashFile = FileHelper::md5sum($this->file->tempName);

                if(($model = File::find()->where(['hash_file' => $hashFile])->one()) == null)
                {
                    if(!file_exists($dir)) mkdir($dir, 0777, true);

                    $this->file->saveAs($file);

                    $model = new File();

                    $model->user_id = $userId;
                    $model->name = $fileName;
                    $model->mime_type = $this->file->type;
                    $model->extension = $this->file->extension;
                    $model->hash_file = $hashFile;
                    $model->size = $this->file->size;
                    $model->created_at = $now;

                    $model->save();
                }

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
<?php

namespace amirkhh\filemanager\models;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $name
 * @property string $mime_type
 * @property string $extension
 * @property string $hash_file
 * @property string $hash_name
 * @property int $size
 * @property int $created_at
 *
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'mime_type', 'extension', 'hash_file', 'hash_name', 'size', 'created_at'], 'required'],
            [['name', 'mime_type', 'extension', 'hash_file', 'hash_name'], 'string', 'max' => 255],
            [['size', 'created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ک.ا',
            'user_id' => 'ک.کاربر',
            'telegram_file_id' => 'فایل آیدی تلگرام',
            'name' => 'نام',
            'mime_type' => 'نوع',
            'extension' => 'پسوند',
            'hash_file' => 'هش فایل',
            'hash_name' => 'هش نام',
            'size' => 'حجم',
            'created_at' => 'زمان ساخت',
        ];
    }
}

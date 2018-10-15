<?php

namespace amirkhh\filemanager\actions;

use Yii;
use amirkhh\filemanager\models\File;
use yii\base\Action;
use yii\data\Pagination;
use yii\web\Response;

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
 *             'file-list' => [
 *                 'class' => ListAction::class,
 *                 'uploadDirectory' => 'uploads/files/',# Optional
 *                 'pageSize' => 13,# Optional, For Pagination
 *             ],
 *         ];
 *     }
 * }
 * ```
 *
 * @author Amir Khoshhal <amirkhoshhal@gmail.com>
 *
 * @link https://github.com/amirkhh/yii2-file-manager
 */
class ListAction extends Action
{
    public $uploadDirectory = 'uploads/files/';

    public $pageSize = 13;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $data['ok'] = false;

        $queryString = Yii::$app->request->get('queryString');

        $query = File::find();

        if($queryString !== null)
            $query->where(['LIKE', 'name', $queryString]);

        $pagination = new Pagination(['totalCount' => $query->count(), 'defaultPageSize' => $this->pageSize]);

        if(($models = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy(['id' => SORT_DESC])->all()) != null)
        {
            $data['ok']         = true;
            $data['pagination'] = $pagination;

            /** @var File $model */
            foreach ($models as $model)
            {
                $data['models'][$model->id] = [
                    'id'        => $model->id,
                    'name'      => $this->uploadDirectory.$model->name,
                    'mimeType'  => $model->mime_type,
                    'isImage'   => (in_array($model->extension, ['jpg', 'png', 'gif'])),
                    'extension' => $model->extension,
                    'size'      => $this->humanReadableFilesize($model->size),
                    'createdAt' => $this->persianDate($model->created_at, 'HH:mm - yyyy/MM/dd', 'fa-IR'),
                ];
            }
        }

        $response         = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data   = $data;
    }

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
     * @param null $timestamp
     * @param string $format
     * @param string $calenderLang
     * @return string
     */
    private function persianDate($timestamp = null, $format = 'yyyy/MM/dd', $calenderLang = 'en-US')
    {
        if($timestamp == null) $timestamp = time();
        $fmt = datefmt_create($calenderLang."@calendar=persian", \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, 'Asia/Tehran', \IntlDateFormatter::TRADITIONAL, $format);
        return $fmt->format($timestamp);
    }
}

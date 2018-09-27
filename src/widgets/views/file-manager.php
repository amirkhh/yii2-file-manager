<?php

/** @var \yii\web\View $this */
/** @var \app\modules\admin\models\UploadForms $model */
/** @var ActiveForm $form */
/** @var string $filesOutputName */
/** @var string $files */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use amirkh\FileManager\FileManagerAsset;

FileManagerAsset::register($this);

/*$this->registerJsFile('@storage-url/js/file-manager.js', ['depends' => AngularAsset::className()]);
$this->registerCssFile('@storage-url/css/file-manager.css');*/
?>

<div ng-app="fileManager" ng-controller="fileController">

<p>افزودن فایل:</p>

<?php
Modal::begin([
    'id' => 'modal',
    'size' => 'modal-lg',
    'closeButton' => [
        'class' => 'close',
        'data-dismiss' =>'modal',
    ],
    'clientOptions' => [
        'backdrop' => false, 'keyboard' => true
    ]
]);
?>

<div id="fileList" class="entityListPage">

    <div class="filemanager-files">
        <div class="filemanager-file-actions">
            <div class="filemanager-file-actions-left">

                <!-- Start Upload File -->
                <div class="form-group">
                    <?= $form->field($model, 'file')->fileInput(['id' => 'myFileField', 'class' => 'hide', 'file-model' => 'myFile', 'onchange' => 'uploadFile()'])->label(false) ?>

                    <button type="button" trigger-file class="btn btn-primary">Upload File</button>

                </div>

                <div>{{serverResponse}}</div>
                <!-- End Upload File -->

                <p>
                    <input class="filemanager-search" type="text" placeholder="Enter search term...">
                </p>

            </div>
        </div>
        <div class="filemanager-files-table">
            <div class="table-responsive-wrapper">
                <table class="table table-hover table-striped table-align-middle mt-4">
                    <thead class="thead-default">
                        <tr>
                            <th>
                                <div class="table-sorter-wrapper is-active">
                                    <div class="table-sorter table-sorter-up is-sorting ng-scope">
                                        <span>Name</span>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <span>Type</span>
                            </th>
                            <th>
                                <span>Creation Date</span>
                            </th>
                            <th>
                                <span>File size</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ngRepeat: file in filesData -->
                        <tr ng-repeat="model in models track by $index" class="filemanager-file ng-scope">
                            <th scope="row" ng-click="toggleSelection(file)">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" ng-click="chooseFile(model.id)" ng-checked="currentId == model.id">
                                    <label></label>
                                </div>
                            </th>
                            <td class="text-center ng-isolate-scope">
                                <span ng-if="model.isImage"><img class="responsive-img filmanager-thumb" ng-src="<?= Url::base() ?>/uploads/files/{{model.name}}"></span>
                            </td>
                            <td>{{model.name}}</td>
                            <td>{{model.size}}</td>
                            <td>{{model.createdAt}} PM</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php
Modal::end();
?>

<ul ng-cloak="">
    <li ng-repeat="file in files track by $index">
        <?= Html::button('{{file.label}}', ['class' => 'btn btn-primary', 'ng-click' => 'openModal($index, file.id)']) ?>
        <?= Html::button('-', ['class' => 'btn btn-danger', 'ng-click' => 'delete($index)']) ?>
    </li>
</ul>

<p>
    <?= Html::hiddenInput($filesOutputName, $files == null ? '[]' : $files, ['class' => 'filesData']) ?>
    <?= Html::button('+', ['class' => 'btn btn-info', 'ng-click' => 'addChooseFile()']) ?>
</p>

</div>
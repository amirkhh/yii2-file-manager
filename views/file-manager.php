<?php

/** @var \yii\web\View $this */
/** @var \amirkhh\filemanager\models\UploadForms $model */
/** @var string $fileUploadUrl */
/** @var string $fileListUrl */
/** @var ActiveForm $form */
/** @var string $filesOutputName */
/** @var string $files */
/** @var string $accept */
/** @var string $maxFileCount */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use amirkhh\filemanager\FileManager;
use amirkhh\filemanager\FileManagerAsset;

FileManagerAsset::register($this);

$this->registerJs('
var fileUploadUrl = "'.$fileUploadUrl.'";
var fileListUrl   = "'.$fileListUrl.'";
var maxFileCount  = "'.$maxFileCount.'";
', \yii\web\View::POS_BEGIN);
?>

<div ng-app="fileManager" ng-controller="fileController">

    <p><?= FileManager::t('messages', 'Add File') ?>:</p>

    <?php
    Modal::begin([
        'id' => 'modal',
        'header' => '<h3>'.FileManager::t('messages', 'File Manager').'</h3>',
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

    <div class="progress" ng-show="progress >= 0">
        <div style="width:{{progress}}%"></div>
    </div>

    <div id="fileList" class="entityListPage">

        <div class="filemanager-files">
            <div class="filemanager-file-actions">
                <div class="filemanager-file-actions-left">

                    <!-- Start Upload File -->
                    <div class="form-group">

                        <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => $accept, 'ngf-select' => 'true', 'ng-model' => '$uploadfiles', 'ng-change' => 'uploadFiles($uploadfiles)', 'class' => 'filesInput hide'])->label(false) ?>

                        <!--<input type="file" ngf-select ng-model="$uploadfiles" multiple
                               name="file"
                               accept="image/*" ngf-max-size="2MB"
                               ngf-model-invalid="errorFile" ng-change="uploadFiles($uploadfiles)" />-->
                    </div>
                    <!-- End Upload File -->

                    <?= Html::button(FileManager::t('messages', 'Upload'), ['class' => 'btn btn-success aliasFileInput']) ?>

                    <?= Html::textInput('filter', null, [
                        'class' => 'filemanager-search',
                        'placeholder' => FileManager::t('messages', 'Search File Name . . .'),
                        'ng-change' => 'filter()',
                        'ng-model' => 'filterQuery',
                        'ng-model-options' => '{debounce: 1000}',
                    ]) ?>

                </div>
            </div>
            <div class="filemanager-files-table">
                <div class="table-responsive-wrapper">
                    <table class="table table-hover table-striped table-align-middle mt-4 <?= FileManager::t('messages', 'ltr') ?>">
                        <thead class="thead-default">
                        <tr>
                            <th><?= FileManager::t('messages', 'Choose') ?></th>
                            <th><?= FileManager::t('messages', 'Preview') ?></th>
                            <th><?= FileManager::t('messages', 'Name') ?></th>
                            <th><?= FileManager::t('messages', 'Type') ?></th>
                            <th><?= FileManager::t('messages', 'Size') ?></th>
                            <th><?= FileManager::t('messages', 'Created Time') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- ngRepeat: file in filesData -->
                        <tr ng-repeat="model in models track by $index" class="filemanager-file ng-scope" ng-class="{'selectedRow' : currentId == model.id}">
                            <th scope="row" ng-click="toggleSelection(file)">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" ng-click="chooseFile($index)" ng-checked="currentId == model.id">
                                    <label></label>
                                </div>
                            </th>
                            <td class="text-center ng-isolate-scope">
                                <span ng-if="model.isImage"><img class="responsive-img filmanager-thumb" ng-src="<?= Url::base() ?>/{{directory}}/{{model.name}}" data-toggle="tooltip" title="tooltip"></span>
                            </td>
                            <td>{{model.name}}</td>
                            <td>{{model.extension}}</td>
                            <td>{{model.size}}</td>
                            <td>{{model.createdAt}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <section>
                    <div paging class="col-xs-12 text-center"
                         page="page.currentPage"
                         page-size="page.pageSize"
                         total="page.total"
                         paging-action="changePage(page)"
                         hide-if-empty="true"
                         show-prev-next="true"
                         show-first-last="true">
                </section>
            </div>

        </div>

    </div>

    <?php
    Modal::end();
    ?>

    <ul ng-cloak="" class="fileListSelected">
        <li ng-repeat="file in files track by $index">
            <?= Html::button('{{file.label}}', ['class' => 'btn btn-primary', 'ng-click' => 'openModal($index, file.id)']) ?>
            <?= Html::button('-', ['class' => 'btn btn-danger', 'ng-click' => 'delete($index)']) ?>
        </li>
    </ul>

    <p>
        <?= Html::hiddenInput($filesOutputName, $files == null ? '[]' : $files, ['class' => 'filesData']) ?>
        <?= Html::hiddenInput('fileManagerLabel', FileManager::t('messages', 'Choose File'), ['class' => 'fileManagerLabel']) ?>
        <?= Html::button('+', ['class' => 'btn btn-info btnAddFile', 'ng-click' => 'addChooseFile()']) ?>
    </p>

</div>
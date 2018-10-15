

var fileManager = angular.module('fileManager', ['ngFileUpload', 'bw.paging']);

fileManager.controller('fileController', ['$scope', '$http', 'Upload', '$timeout', function ($scope, $http, Upload, $timeout) {

    $scope.baseUrl      = '';
    $scope.result       = false;
    $scope.emptyResult  = false;
    $scope.requestData  = null;
    $scope.files        = JSON.parse($('.filesData').val());
    $scope.currentIndex = 0;
    $scope.currentId    = 0;

    $scope.addChooseFile = function() {

        $scope.files.push({id: -1, label: 'انتخاب فایل'});

        toggleFileAddButton();
    };

    $scope.openModal = function(index, id){

        $scope.getResponseList();

        $scope.currentIndex = index;
        $scope.currentId    = id;

        $("#modal").modal();
    };

    $scope.chooseFile = function(index){

        $scope.currentId = index;

        $scope.files[$scope.currentIndex].id    = $scope.models[index].id;
        $scope.files[$scope.currentIndex].label = $scope.models[index].name;

        $("#modal").modal("hide");

        fileExport();
        
    };

    $scope.delete = function(index) {

        $scope.files.splice(index, 1).sort();

        toggleFileAddButton();

        fileExport();
    };

    function toggleFileAddButton(){
        if($scope.files.length == parseInt(maxFileCount)){
            $('.btnAddFile').addClass('hide');
        } else {
            $('.btnAddFile').removeClass('hide');
        }
    }

    function fileExport(){

        let json = JSON.stringify($scope.files);

        $('.filesData').val(json);
    }

    $scope.currentPage = 1;
    $scope.filterQuery = null;

    $scope.getResponseList = function () {

        $scope.result = false;
        $http({
            method: 'GET',
            url: fileListUrl,
            params: {queryString: $scope.filterQuery, page: $scope.currentPage}
        }).then(function successCallback(response) {
            $scope.result      = true;
            $scope.requestData = response.data;

            if($scope.requestData.ok === true) {
                let models = [];
                let keys   = Object.keys($scope.requestData.models).reverse();/* For Desc Sort File List */

                for(let i = 0; i < keys.length; i++)
                    models.push($scope.requestData.models[keys[i]]);

                $scope.requestData.models = models;
                $scope.emptyResult        = false;
                $scope.models             = $scope.requestData['models'];
                $scope.page.currentPage   = $scope.currentPage;
                $scope.page.pageSize      = response.data.pagination.defaultPageSize;
                $scope.page.total         = response.data.pagination.totalCount;
            }
            else {
                $scope.emptyResult = true;
            }
        });
    };

    $scope.changePage = function (page) {

        $scope.currentPage = page;
        $scope.getResponseList();
    };

    $scope.filter = function () {
        if($scope.filterQuery == '' || $scope.filterQuery.length >= 3)
            $scope.getResponseList();
    };

    /* Upload */
    $scope.uploadFiles = function (files) {

        $scope.uploadfiles = files;

        if (files && files.length) {
            Upload.upload({
                url: fileUploadUrl,
                data: {
                    files: files,
					_csrf: yii.getCsrfToken()
                }
            }).then(function (response) {
                $timeout(function () {
                    $scope.getResponseList();

                    $scope.progress = -1;
                });
            }, function (response) {
                if (response.status > 0) {
                    $scope.errorMsg = response.status + ': ' + response.data;
                }
            }, function (evt) {
                $scope.progress =
                    Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });
        }
    };
}]);

$('.aliasFileInput').click(function () {
    $('.filesInput').click();
});
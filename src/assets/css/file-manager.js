

var fileManager = angular.module('fileManager', []);

fileManager.controller('fileController', function($scope, $http, fileUploadService) {

    $scope.baseUrl      = baseUrl;
    $scope.result       = false;
    $scope.emptyResult  = false;
    $scope.requestData  = null;
    $scope.files        = JSON.parse($('.filesData').val());
    $scope.currentIndex = 0;
    $scope.currentId    = 0;

    $scope.addChooseFile = function() {
        $scope.files.push({id: -1, label: 'Choose File'});
    };

    $scope.openModal = function(index, id){

        $scope.getResponseList();

        $scope.currentIndex = index;
        $scope.currentId    = id;

        $("#modal").modal();
    };

    $scope.chooseFile = function(id){

        $scope.currentId = id;

        $scope.files[$scope.currentIndex].id    = $scope.models[id].id;
        $scope.files[$scope.currentIndex].label = $scope.models[id].name;

        $("#modal").modal("hide");

        fileExport();
        
    };

    $scope.delete = function(index) {

        $scope.files.splice(index, 1).sort();

        fileExport();
    };

    function fileExport(){

        let json = JSON.stringify($scope.files);

        /*let json = JSON.stringify($scope.files.map(function(item, index) {
            return [item.id, item.label].join(",");
        }));*/

        $('.filesData').val(json);
    }

    $scope.getResponseList = function () {
        $scope.result = false;

        $http.get('../file/explore')
            .then(function (response) {
                $scope.result      = true;
                $scope.requestData = response.data;
                if($scope.requestData.ok === true)
                {
                    $scope.emptyResult = false;
                    $scope.models      = $scope.requestData['models'];
                }
                else
                {
                    $scope.emptyResult = true;
                }
            });
    };

    /* Upload */
    $scope.uploadFile = function () {
        alert('salam');
        var file = $scope.myFile;
        var uploadUrl = "../file/upload", //Url of web service
            promise = fileUploadService.uploadFileToUrl(file, uploadUrl);

        promise.then(function (response) {
            $scope.serverResponse = response.ok;

            $scope.getResponseList();

        }, function () {
            $scope.serverResponse = 'An error has occurred';
        })
    };
});

/* A directive to enable two way binding of file field */
fileManager.directive('fileModel', function ($parse) {
    return {
        restrict: 'A', //the directive can be used as an attribute only
        /*
        link is a function that defines functionality of directive
        scope: scope associated with the element
        element: element on which this directive used
        attrs: key value pair of element attributes
         */
        link: function (scope, element, attrs) {
            var model = $parse(attrs.fileModel),
                modelSetter = model.assign; //define a setter for fileModel

            //Bind change event on the element
            element.bind('change', function () {
                //Call apply on scope, it checks for value changes and reflect them on UI
                scope.$apply(function () {
                    //set the model value
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
});

fileManager.directive('triggerFile', function () {
    return {
        restrict: 'A',
        link: function(scope, element) {

            element.bind('click', function(e) {

                $('#myFileField').trigger('click');
            });
        }
    };
});

fileManager.service('fileUploadService', function ($http, $q) {

    this.uploadFileToUrl = function (file, uploadUrl) {
        var fileFormData = new FormData();
        fileFormData.append('file', file);

        var deffered = $q.defer();

        $http.post(uploadUrl, fileFormData, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}

        }).then(function (response) {
            deffered.resolve(response.data);

        }, function (response) {
            deffered.reject(response.data);

        });

        return deffered.promise;
    }
});
//Angular Functions
var app = angular.module('007App', ['ng-reset-on', 'ui.bootstrap']);

app.run(function ($rootScope) {
    $rootScope.mysqlDateToJSDate = function (dateString) {
        return new Date(Date.parse(dateString.replace('-', '/', 'g')));
    };
});

app.filter('toJSDate', function () {
    return function (dateString) {
        return new Date(Date.parse(dateString.replace('-', '/', 'g')));
    };
});

app.directive('input', [function () {
        return {
            restrict: 'E',
            require: '?ngModel',
            link: function (scope, element, attrs, ngModel) {
                if (
                        'undefined' !== typeof attrs.type
                        && 'number' === attrs.type
                        && ngModel
                        ) {
                    ngModel.$formatters.push(function (modelValue) {
                        return Number(modelValue);
                    });

                    ngModel.$parsers.push(function (viewValue) {
                        return Number(viewValue);
                    });
                }
            }
        }
    }]);

app.directive('select', [function () {
    return {
        restrict: 'E',
        require: '?ngModel',
        link: function (scope, element, attrs, ngModel) {
            if (ngModel) {
                ngModel.$isEmpty = function (value) {
                    return !value || value.length === 0;
                }
            }
        }
    }
}]);

app.controller('dataProviderCtrl', ['$scope', '$window', function ($scope, $window) {
        $scope.sensors = [];

        angular.element(document).ready(function () {
            if (!isEmpty($window.sensor_items)) {
                var items = JSON.parse($window.sensor_items);
                items.forEach(function (currentValue, index, arr) {
                    console.log(currentValue);
                    $scope.sensors.push({
                        Parameter: currentValue.Parameter,
                        ParameterOther: currentValue.ParameterOther,
                        Brand: currentValue.Brand,
                        InstallationHeight: currentValue.InstallationHeight,
                        Status: currentValue.Status,
                        Calibration: currentValue.Calibration
                    });
                });
            } else {
                $scope.add();
            }

            $scope.$apply();
        });

        $scope.add = function () {
            $scope.sensors.push({
                Parameter: '',
                ParameterOther: '',
                Brand: '',
                InstallationHeight: '',
                Status: '',
                Calibration: ''
            });
        };

        $scope.remove = function (index) {
            $scope.sensors.splice(index, 1);
        };

        function handleUnloadEvent(event) {
            if ($scope.receptionForm.$dirty) {
                event.returnValue = "Some message";
            }
        }
        ;

        if (window.addEventListener) {
            window.addEventListener("beforeunload", handleUnloadEvent);
        } else {
            //For IE browsers
            window.attachEvent("onbeforeunload", handleUnloadEvent);
        }

        $scope.saveStation = function () {
            var $form = $("#station-add-form");

            if (!$form.valid()) {
                $('#feedback').html('<div class="alert alert-danger animated shake">One or more validations have failed. Please relook at your answers.</div>');
                scrollToElement('feedback');
                
                setTimeout(function() {
                    $('#feedback').fadeOut().html('');
                }, 10000);
                
                return false;
            }

            var data = {
                RecordingDate: $('#RecordingDate').val(),
                Name: $("#Name").val(),
                SerialNumber: $("#SerialNumber").val(),
                DataLoggerType: $("#DataLoggerType").val(),
                DataLoggerTypeOtherSpecify: $("#DataLoggerTypeOtherSpecify").val(),
                Supplier: $("#Supplier").val(),
                SupplierOtherSpecify: $("#SupplierOtherSpecify").val(),
                Owner: $("#Owner").val(),
                OwnerOtherSpecify: $("#OwnerOtherSpecify").val(),
                ContactPersonName: $("#ContactPersonName").val(),
                ContactPersonPhoneNumber: $("#ContactPersonPhoneNumber").val(),
                ContactPersonEmailAddress: $("#ContactPersonEmailAddress").val(),
                DateInstalled: $("#DateInstalled").val(),
                LocationAccessibility: $("#LocationAccessibility").val(),
                Latitude: $("#Latitude").val(),
                Longitude: $("#Longitude").val(),
                HeightAboveSeaLevel: $("#HeightAboveSeaLevel").val(),
                TowerLength: $("#TowerLength").val(),
                TowerType: $("#TowerType").val(),
                TransmissionMode: $("#TransmissionMode").val(),
                TransmissionModeOtherSpecify: $("#TransmissionModeOtherSpecify").val(),
                MeasurementFrequency: $("#MeasurementFrequency").val(),
                DataTransferFrequency: $("#DataTransferFrequency").val(),
                DataTransferFrequencyOtherSpecify: $("#DataTransferFrequencyOtherSpecify").val(),
                MaintenanceFrequency: $("#MaintenanceFrequency").val(),
                MaintenanceFrequencySpecify: $("#MaintenanceFrequencySpecify").val(),
                Issues: $("#Issues").val(),
                GeneralComments: $("#GeneralComments").val(),
                Sensors: $scope.sensors,
                csrf_token: $scope.csrf_token
            }, $btn = $("#station-add-btn");

            $btn.button('loading');

            try {
                $.ajax({
                    type: "POST",
                    url: $scope.post_url + (!isEmpty($scope.ID) ? $scope.ID : ''),
                    data: data,
                    success: function (data) {
                        $('#feedback').html('<div class="alert alert-' + (data.success ? 'success' : 'danger') + ' animated shake">' + data.message + '</div>');
                        scrollToElement('feedback');

                        if (data.success) {
                            $scope.ID = data.ID;
                            $scope.stationForm.$setPristine();
                            setTimeout(function () {
                                javascript:history.go(-1);
                            }, 1500);
                        } else {
                            $btn.button('reset');
                        }
                    },
                    dataType: 'json'
                })
                        .fail(function (xhr, status, error) {
                            $('#feedback').html('<div class="alert alert-danger animated shake">' + error + '</div>');
                            scrollToElement('feedback');
                            $btn.button('reset');
                        });
            } catch (err) {
                $('#feedback').html('<div class="alert alert-danger animated shake">' + err + '</div>');
                scrollToElement('feedback');
                $btn.button('reset');
            }
        };
}]);

app.controller('quizCtrl', ['$scope', '$window', function ($scope, $window) {
    console.log('Logging from controller');
    
    $scope.questions = [];
    
    $scope.add = function () {
        $scope.questions.push({
            question: '',
            options: []
        });
    };

    $scope.remove = function (index) {
        $scope.questions.splice(index, 1);
    };
    
    $scope.addOption =function(index) {
        $scope.questions[index].options.push({
            option: ''
        });
    };
    
    $scope.removeOption = function(index, innerIndex) {
        $scope.questions[index].options.splice(innerIndex,1);
    };
}]);
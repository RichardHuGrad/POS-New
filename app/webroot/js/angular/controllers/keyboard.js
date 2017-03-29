keyboardApp.controller('keyboardCtrl', ['$scope', '$http', '$location',
    function($scope, $http, $location) {
        $scope.screen = 0
        var mode = ['normal', 'count']
        $scope.mode = mode[0]
        var initCount =
        $scope.count = {
            '$0.05': 0,
            '$0.1': 0,
            '$0.25': 0,
            '$1': 0,
            '$2': 0,
            '$5': 0,
            '$10': 0,
            '$20': 0,
            '$50': 0,
            '$100': 0,
        }
        $scope.prev = []
        $scope.attach = function(num) {
            $scope.screen = _.round($scope.screen * 10 + num * 0.01, 2)
        }

        $scope.add = function(num) {
            $scope.prev.push(num)
            console.log('$' + num.toString())
            ++$scope.count['$' + num.toString()]
            $scope.screen = _.round($scope.screen + num, 2)
        }

        $scope.back = function() {
            $scope.prev.push($scope.screen)
            $scope.screen = _.floor($scope.screen / 10, 2)
        }


        $scope.clear = function() {
            $scope.prev.push($scope.screen)
            $scope.screen = 0
            _.forEach($scope.count, function(value, index) {
                $scope.count[index] = 0
            })
            $scope.prev = []
        }

        function minus(num) {
            --$scope.count['$' + num.toString()]
            $scope.screen = _.round($scope.screen - num, 2)
        }

        // only for count mode
        $scope.revert = function() {
            // $scope.screen = _.last($scope.prev)
            if ($scope.prev.length > 0){
                var num = _.last($scope.prev)
                $scope.prev.splice(-1, 1)
                minus(num)
            }
        }

        $scope.switchMode = function() {
            if ($scope.mode == 'count') {
                $scope.mode = 'normal'
            } else if ($scope.mode == 'normal') {
                $scope.mode = 'count'
            }
            // clear screen
            $scope.clear()
        }
    }
])

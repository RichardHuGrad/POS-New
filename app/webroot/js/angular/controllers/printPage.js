app.controller('printPageCtrl', ['$scope',
    function($scope) {
        $scope.data = [{
                            "content": "line 1",
                            "offset_x": 100,
                            "line_index" : 0
                        }, {
                            "content": "line 2",
                            "offset_x": 150,
                            "line_index" : 1
                        }, {
                            "content": "line 3",
                            "offset_x": 200,
                            "line_index" : 2
                        }, {
                            "content": "line 4",
                            "offset_x": 100,
                            "line_index" : 3
                        }]
        $scope.types = ['receipt-header', 'kitchen-header', 'receipt-footer'];

        $scope.insertLine = function(type) {
            var empty_data = {
                                "content": "",
                                "offset_x": 0,
                                "line_index" : $scope.data.length
                            }
            $scope.data.push(empty_data)
            console.log($scope.data)
        }

        $scope.updateLine = function(type, content, line_index, offset_x) {
            // update backend with ajax

            console.log($scope.data)
        }
        $scope.deleteLine = function (type, line_index) {
            $scope.data.forEach(function(value, index) {
                console.log(value.line_index)
                console.log(line_index)
                if (value.line_index == line_index) {
                    $scope.data.splice(index, 1)
                }
            })
            // reorder the data
            // _.sortBy($scope.data, "line_index")
            $scope.data.forEach( function(value, index) {
                value.line_index = index
            })

            //  send ajax to backend to update data

            console.log("delete")
            console.log($scope.data)
            // after delete line, if the deleted is not the last one, reorder the lines
        }
    }
])

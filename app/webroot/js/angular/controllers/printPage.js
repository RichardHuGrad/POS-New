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

        $scope.insertLine = function(type) {
            console.log(type)
        }
        $scope.updateLine = function(type, content, line_index, offset_x) {
            console.log(type, content, line_index, offset_x)
        }
        $scope.deleteLine = function (type, content, line_index, offset_x) {
            console.log("delete")
            console.log(type, content, line_index, offset_x)
            // after delete line, if the deleted is not the last one, reorder the lines
        }
    }
])

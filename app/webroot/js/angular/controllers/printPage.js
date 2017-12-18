app.controller('printPageCtrl', ['$scope', '$http', '$location',
    function($scope, $http, $location) {
        var baseUrl = $location.$$absUrl;
        init()

        function init() {
            $http.get(baseUrl + '/getAllLines').then(function(response) {
                $scope.data = response.data
                console.log($scope.data)
                $scope.types = _.values(_.mapValues(_.uniqBy($scope.data, 'type'), (item)=>{ return item.type }));
                $scope.selectedType = $scope.types[0]
            })
        }

        $scope.insertType = function(type) {
            if (_.includes($scope.types, type)) {
                console.log("type already exist")
            } else {
                console.log("insert new type")
            }
        }

        $scope.insertLine = function(type) {
            var index = _.filter($scope.data, {'type': type}).length

            var empty_data = {
                                type: type,
                                content: "",
                                offset_x: 0,
                                line_index : index,
                                lang_code: "en",
                                bold: 0
                            }
            $scope.data.push(empty_data)

            // $http.post()
            // console.log($location.path())
            // var url = baseUrl + '/insertType'
            console.log(empty_data)
            console.log($.param(empty_data))
            var req = {
                method: 'POST',
                url: baseUrl + '/insertLine',
                data: $.param(empty_data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }


            $http(req).then(function(res) {
                console.log(res.data)
                console.log(empty_data)
            })

            // console.log($scope.data)
        }

        $scope.updateLine = function(id, type, content, offset_x, line_index, lang_code, bold) {
            // update backend with ajax
            var updatedData = {
                id: id,
                type: type,
                content: content,
                offset_x: offset_x,
                line_index : line_index,
                lang_code: lang_code,
                bold: bold
            }
            console.log(updatedData)
            var req = {
                method: 'POST',
                url: baseUrl + '/updateLine',
                data: $.param(updatedData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }

            $http(req).then(function(res) {
                // console.log(res.data)
            })

            console.log($scope.data)
        }
        $scope.deleteLine = function (id) {
            $scope.data.forEach(function(value, index) {
                if (value.id == id) {
                    $scope.data.splice(index, 1)
                }
            })
            // reorder the data
            // _.sortBy($scope.data, "line_index")
            $scope.data.forEach( function(value, index) {
                value.line_index = index
            })

            //  send ajax to backend to update data
            var req = {
                method: 'POST',
                url: baseUrl + '/deleteLine',
                data: $.param({id: id}),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }


            $http(req).then(function(res) {
                console.log(res.data)
                console.log(empty_data)
            })

            console.log("delete")
            console.log($scope.data)
            // after delete line, if the deleted is not the last one, reorder the lines
        }

        $scope.typeFilter = function(type) {
            return function(item) {
                return item.type == type
            }
        }

        $scope.boolFilter = function() {
            return function(num) {
                if (num == 0) {
                    return "false"
                } else {
                    return "true"
                }
            }
        }

    }
])

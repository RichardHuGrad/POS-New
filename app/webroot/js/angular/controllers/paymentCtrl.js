paymentApp.controller('paymentCtrl', ['$scope', '$http', '$location',
    function($scope, $http, $location) {
        var params = $location.search()
        var tableNo = params.table
        var tableType = params.type

        init()

        function init() {
            var req = {
                method: 'POST',
                url: $location.path() + '/getOrderInfoByTable',
                data: $.param({'table': tableNo, 'type': tableType}),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }

            $http(req).then(function(res) {
                console.log(res.data)
            })
        }


        console.log($location.path())
    }
])

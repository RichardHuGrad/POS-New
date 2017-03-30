var paymentApp = angular.module('paymentApp', [])
    .config(['$locationProvider', function($locationProvider) {
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }])

paymentApp.factory('keyboardData', function($rootScope) {

})

reportApp.controller('reportCtrl',
	function($scope, $location, $http) {
		var baseUrl = $location.$$absUrl;
		console.log(baseUrl)
		init()

		function init() {
			$scope.today = new Date();
			initCalendar()
		}



		function initCalendar() {
			var data = {
				currentMonth: $scope.today.getMonth() + 1,
				currentYear: $scope.today.getFullYear
			}

			jQuery('#calendar').fullCalendar({
				header: {
					left: 'prev, next today',
					center: 'title',
					right: 'month'
				},
				firstDay: 0,
				editable: true,
				dayClick: function (date, jsEvent, view) {
					var selectedDate = new Date( (date.format()).replace(/-/g,'/') );
					$scope.selectedDate = selectedDate
					console.log(selectedDate)
					if (selectedDate <= $scope.today) {
						jQuery('#calendar-popup').modal('show')
					}

				},
				dayRender: function (date, cell) {
					var curDate = new Date( (date.format()).replace(/-/g,'/') );
					if (curDate < $scope.today) {
                        cell.css("background-color", "#E0F8EC");
                    }
				}
			})
		}

		$scope.getSaleAmount = function() {
			// based on selected Date
			var req = {
				method: 'POST',
				url: baseUrl + '/getAmountInfo',
				data: $.param({time: $scope.selectedDate.getTime() / 1000}),
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}

			$http(req).then(function() {
				console.log('success')
			})

		}
		$scope.printSaleAmount = function() {
			// based on selected Date
			var req = {
				method: 'POST',
				url: baseUrl + '/getAmountInfo',
				data: $.param({time: $scope.selectedDate.getTime() / 1000}),
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}

			$http(req).then(function() {
				console.log('success')
			})
		}

		$scope.getSaleItems = function() {
			// based on selected Date
			var req = {
				method: 'POST',
				url: baseUrl + '/getItemsInfo',
				data: $.param({time: $scope.selectedDate.getTime() / 1000}),
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}

			$http(req).then(function() {
				console.log('success')
			})
		}

		$scope.printSaleItems = function() {
			// based on selected Date
			var req = {
				method: 'POST',
				url: baseUrl + '/getItemsInfo',
				data: $.param({time: $scope.selectedDate.getTime() / 1000}),
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}

			$http(req).then(function() {
				console.log('success')
			})
		}
	})

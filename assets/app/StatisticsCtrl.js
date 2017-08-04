webApp.controller('StatisticsController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
	$scope.gridOptions = {
		enableFiltering: true,
		columnDefs: [
			// default
			{ name: 'Fecha', field: 'start', type: 'date', cellFilter: 'date:"M/d/yy H:mm"' },
			{ name: 'Equipo', field: 'team_name' },
			{ name: 'Tipo', field: 'team_type' },
			{ name: 'Tiempo', field: 'time', type: 'number', cellFilter: 'date:"H:mm:ss":"UTC"' },
			{ name: 'Pistas', field: 'total_clues', type: 'number' },
			{ name: 'Personas', field: 'people', type: 'number' }
		]
	};

	$timeout(function() {
		$http.get('/json_info/statistics/'+$scope.roomId).then(function(response) {
			$scope.gridOptions.data = response.data;
		});
	},100);

}]);

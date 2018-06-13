webApp.controller('StatisticsController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
	$scope.gridOptions = {
		enableFiltering: true,
		columnDefs: [
			// default
			{ name: 'Fecha', field: 'start', type: 'date', cellFilter: 'date:"M/d/yy H:mm"' },
			{ name: 'Equipo', field: 'team_name' },
			{ name: 'Tiempo', field: 'time', type: 'number', cellFilter: 'clock' },
			{ name: 'Pistas', field: 'total_clues', type: 'number' },
			{ name: 'Personas', field: 'people', type: 'number' },
            { name: 'Acciones', cellTemplate : '<div class="ui-grid-cell-contents"><button type="button" class="btn btn-xs btn-default" data-container="body" ng-click="grid.appScope.borrarGrupo(row.entity.id)">Borrar</button></div>'}
		]
	};
//    { name: 'Tipo', field: 'team_type' },

    $scope.borrarGrupo = function(e) {
        var r = confirm("Seguro que desea borrar este grupo?");
        if (r == true) {
            $http.get('/json_info/deletegroup/'+e).then(function(response) {
                updateData ();
            });
        }
    }

    function updateData () {
        $http.get('/json_info/statistics/'+$scope.roomId).then(function(response) {
            $scope.gridOptions.data = response.data.groups;
            $scope.stats = response.data.stats;
        });
    };

	$timeout(updateData,100);

}]);

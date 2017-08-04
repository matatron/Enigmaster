webApp.controller('PostgameController', ['$scope', '$http', function($scope, $http) {
	var ctrl = this;

	ctrl.save = function() {
		$http.post('/json_info/savepostgroup/'+ctrl.groupId, {comments: $('#comments').val()}).then(function(response) {
		}, function() {
		});
	}
}]);

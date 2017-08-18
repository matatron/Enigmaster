webApp = angular.module('Enigmaster', [
]);
webApp.controller('PlayerviewController', ['$scope', '$http', '$timeout', '$interval', function($scope, $http, $timeout, $interval) {
    $scope.data={};
    $scope.data.status = 0;
    $scope.data.end = 0;
    $scope.clue = '';
    var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');

    function getBackend() {
        $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
            $scope.data = response.data;
            $scope.pistas = [];
            for(var i=parseInt($scope.data.total_clues); i>0; i--) {
                console.log(i);
                $scope.pistas.push(i);
            }
            if ($scope.data.clue && $scope.data.clue.value != $scope.clue) {
                $scope.clue = $scope.data.clue.value;
                ping.play();
            }
        });
    }


    $interval(getBackend,2000);
    $interval(function() {
        $now = (new Date()).getTime();
        $scope.timeLeft = $scope.data.end - $now;
        $scope.timePass = 60*60*1000 - $scope.timeLeft;
    },100);
    getBackend();

}]);

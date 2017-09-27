webApp = angular.module('Enigmaster', [
]);
webApp.controller('PlayerviewController', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
    $scope.data={};
    $scope.data.status = 0;
    $scope.data.end = 0;
    $scope.clue = '';
    var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
    var lastStatus = null;
    var bgAudio = null;


    function getBackend() {
        $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
            $scope.data = response.data;
            if ($scope.data.status != lastStatus) {
                lastStatus = $scope.data.status;
                switch($scope.data.status) {
                    case "1":
                        bgAudio = new Audio('/assets/audio/'+window.bgsound);
                        bgAudio.play();
                        break;
                    case "0":
                        bgAudio.stop();
                        //play end game sound
                        break;
                    case "2":
                        bgAudio.stop();
                        break;

                }
            }
            $scope.pistas = [];
            for(var i=parseInt($scope.data.total_clues); i>0; i--) {
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

        $scope.timeLeft = $filter('date')($scope.timeLeft, 'HH:mm:ss', 'UTC');
        $scope.timePass = ($scope.timePass < 0 ? "-"+$filter('date')(-$scope.timePass, 'HH:mm:ss', 'UTC') : $filter('date')($scope.timePass, 'HH:mm:ss', 'UTC') );
    },100);
    getBackend();

}]);

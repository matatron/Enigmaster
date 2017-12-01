webApp = angular.module('Enigmaster', [
]).filter('clock', function() {
    return function(date) {
        var prefix = '';
        function pad(n) {
            return (n<10)?"0"+n:n;
        }
        date = new Date(date);
        date = date.getTime();
        if (date<0) {
            prefix = '-';
            date = -date;
        }
        var s = date/1000;
        var h = Math.floor(s/3600);
        var m = Math.floor(s/60)%60;
        var s = Math.round(s%60);
        return prefix+h+":"+pad(m)+":"+pad(s);
    };
}).controller('PlayerviewController', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
    $scope.data={};
    $scope.data.status = 0;
    $scope.data.end = 0;
    $scope.clue = '';
    var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
    var lastStatus = null;
    var lastMusic = '';
    var bgAudio = null;


    function getBackend() {
        $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
            $scope.data = response.data;
            if ($scope.data.status != lastStatus) {
                lastStatus = $scope.data.status;
                switch($scope.data.status) {
                    case 2:
                    case 1:
                        if (bgAudio) bgAudio.play();
                        break;
                    case 3:
                    case 0:
                        if (bgAudio) bgAudio.pause();
                        $scope.clue = '';
                        //play end game sound
                        break;

                }
            }
            $scope.pistas = [];
            for(var i=parseInt($scope.data.total_clues); i>0; i--) {
                $scope.pistas.push(i);
            }
            $scope.punishment = Math.max(0, $scope.data.total_clues-$scope.data.free_clues)*$scope.data.minutesxclue;
            if ($scope.data.clue && $scope.data.clue.value != $scope.clue) {
                $scope.clue = $scope.data.clue.value;
                ping.play();
            }
            if ($scope.data.progress != undefined && ($scope.data.status==2 || $scope.data.status==1) && lastMusic != window.music[$scope.data.progress]) {
                lastMusic = window.music[$scope.data.progress];
                if (bgAudio) bgAudio.pause();
                bgAudio = new Audio(lastMusic);
                bgAudio.play();
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

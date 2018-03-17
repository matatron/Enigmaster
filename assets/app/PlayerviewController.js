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
        var s = Math.floor(s%60);
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
            response.data.progress = parseInt(response.data.progress);
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
                        if (bgAudio) {
                            bgAudio.pause();
                            bgAudio.currentTime = 0;
                        }
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
            $scope.punishment += $scope.data.punishment;
            if ($scope.data.clue && $scope.data.clue.value != $scope.clue) {
                $scope.clue = $scope.data.clue.value;
                ping.play();
            }
            if ($scope.data.progress != undefined && ($scope.data.status==2 || $scope.data.status==1) && lastMusic != window.music[$scope.data.progress]) {
                console.log($scope.data.progress, window.music[$scope.data.progress], lastMusic);
                lastMusic = window.music[$scope.data.progress];
                if (bgAudio) bgAudio.pause();
                bgAudio = new Audio(lastMusic);
                bgAudio.play();
            }
        });
    }


    $interval(getBackend,1000);
    $interval(function() {
        $now = (new Date()).getTime();
        $scope.timeLeft = $scope.data.end - $now;
        $scope.timePass = 60*60*1000 - $scope.timeLeft;
        $scope.counters = [];
        for(var i =0; i<12; i++) {
            if (i*300000 < $scope.timeLeft ) {
                $scope.counters.push(i);
            }
        }
    },100);
    getBackend();

}]).controller('PlayerviewControllerTV', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
    $scope.data={};
    $scope.data.status = 0;
    $scope.data.end = 0;
    var lastStatus = null;

    var currentPuzzles = null;

    var videos = {
        1: "/assets/video/ruido2.mp4",
        2: "/assets/video/retratos1.mp4",
        3: "/assets/video/ruido1.mp4",
        4: "/assets/video/risa1.mp4",
        6: "/assets/video/Olivia1.mp4",
        7: "/assets/video/Video1.mp4",
        8: "/assets/video/Video2.mp4",
        9: "/assets/video/Video3.mp4",
        10: "/assets/video/Video4.mp4",
        11: "/assets/video/Video5.mp4",
        12: "/assets/video/Video6.mp4",
        21: "/assets/video/Olivia2.mp4",
        22: "/assets/video/grito2.mp4",
        23: "/assets/video/static.mp4",
        24: "/assets/video/grito1.mp4",
        25: "/assets/video/Olivia.mp4"
    }

    function getBackend() {
        $http.get('/json_info/roompuzzles/'+$scope.roomId).then(function(response) {
            response.data.progress = parseInt(response.data.progress);
            $scope.data = response.data;
            if ($scope.data.status != lastStatus) {
                lastStatus = $scope.data.status;
                switch($scope.data.status) {
                    case 2:
                    case 1:
                        if (currentPuzzles == null) currentPuzzles = response.data.puzzles;
                        break;
                    case 3:
                        currentPuzzles = response.data.puzzles;
                        break;
                    case 0:
                        //play end game sound
                        break;

                }
            }
            if ($scope.data.progress != undefined && $scope.data.status==2) {
                var player = document.getElementById('tvvideo');
                $.each($scope.data.puzzles, function(i,e) {
                    if (currentPuzzles[i] != e) {
                        //cambio detectado
                        currentPuzzles[i] = e;
                        if (currentPuzzles[i] && videos.hasOwnProperty(i+1)) {
                            console.log("Video "+videos[i+1]);
                            player.src = videos[i+1];
                            player.play();
                        }
                    }
                })
            }
        });
    }


    $interval(getBackend,500);
    getBackend();

}]);

webApp = angular.module('Enigmaster', [])
    .filter('clock', function() {
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
})
    .filter('percent', function() {
    return function(date) {
        date = new Date(date);
        return (date.getTime()/36000).toFixed(1)+"%";
    };
})
    .controller('PlayerviewController', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.data={};
        $scope.data.status = 0;
        $scope.data.start = 0;
        $scope.clue = '';
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
        var lastStatus = null;
        var lastProgress = 0;
        var lastMusic = '';
        var bgAudio = null;
        var fxAudio = null;
        var flashDiv = $(".flash");

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
                if ($scope.data.clue && $scope.data.clue.value != $scope.clue) {
                    $scope.clue = $scope.data.clue.value;
                    if ($scope.clue != '') ping.play();
                    if ($scope.data.visualflash) {
                        flashDiv.show();
                        setTimeout(function() {
                            flashDiv.hide();
                            setTimeout(function() {
                                flashDiv.show();
                                setTimeout(function() {
                                    flashDiv.hide();
                                }, 250);
                            }, 250);
                        }, 250);
                    }
                }
                if ($scope.data.progress != undefined && ($scope.data.status==2 || $scope.data.status==1) && lastMusic != window.music[$scope.data.progress]) {
                    console.log($scope.data.progress, window.music[$scope.data.progress], lastMusic);
                    lastMusic = window.music[$scope.data.progress];
                    if (bgAudio) bgAudio.pause();
                    bgAudio = new Audio(lastMusic);
                    bgAudio.play();
                }
                if (lastProgress != $scope.data.progress) {
                    lastProgress = $scope.data.progress;
                    if (window.sounds[$scope.data.progress]) {
                        fxAudio = new Audio(window.sounds[$scope.data.progress]);
                        fxAudio.play();
                    }
                }
            });
        }


        $interval(getBackend,1000);
        $interval(function() {
            $now = (new Date()).getTime();
            $scope.data.punishment = $scope.data.punishment || 0;
            $scope.timePass = $now - $scope.data.start + $scope.data.punishment*60000;
            $scope.timeLeft = 3600000 - $scope.timePass;
            $scope.counters = [];
            for(var i =0; i<12; i++) {
                if (i*300000 < $scope.timeLeft ) {
                    $scope.counters.push(i);
                }
            }
        },100);
        getBackend();

    }])
    .controller('PlayerviewControllerTV', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.data={};
        $scope.data.status = 0;
        $scope.data.start = 0;
        var lastStatus = null;

        var currentPuzzles = null;

        var player;
        var videos = {
            1: "/assets/video/ruido2.mp4",
            2: "/assets/video/Video1.mp4",
            3: "/assets/video/ruido1.mp4",
            4: "/assets/video/risa1.mp4",
            6: "/assets/video/Olivia1.mp4",
            7: "/assets/video/ruidoCreepy.mp4",
            8: "/assets/video/ojo.mp4",
            9: "/assets/video/Video2.mp4",
            10: "/assets/video/Video3.mp4",
            11: "/assets/video/Video4.mp4",
            13: "/assets/video/retratos1.mp4",
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

                if ($scope.data.progress != undefined && ($scope.data.status==2 || $scope.data.status==1)) {
                    player = document.getElementById('tvvideo');
                    if (player != null) {
                        player.addEventListener('ended',function() {
                            $(player).hide();
                        },false);
                    }
                    $.each($scope.data.puzzles, function(i,e) {
                        if (currentPuzzles[i] != e) {
                            //cambio detectado
                            currentPuzzles[i] = e;
                            if (currentPuzzles[i] && videos.hasOwnProperty(i+1)) {
                                console.log("Video "+videos[i+1]);
                                player.src = videos[i+1];
                                $(player).show();
                                player.play();
                            }
                        }
                    })
                }
            });
        }


        $interval(getBackend,500);
        getBackend();

    }])
    .controller('PlayerviewControllerPi1', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.data={};
        $scope.data.status = 0;
        $scope.data.start = 0;
        $scope.screen = ''
        $scope.clue = '';
        $scope.isAlien = true;
        var lastStatus = null;
        var currentPuzzles = null;
        var currentParams = null;
        var player;
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
        var alarma = new Audio('/assets/audio/alarma.mp3');


        var videos = {
            1: "",
            2: "",
            3: "",
            4: "",
            6: "",
            7: "",
            8: "",
            9: "",
            10: "",
            11: "",
            13: "",
            21: "",
            22: "",
            23: "",
            24: "",
            25: ""
        }
        var music = [
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
        ];
        var sounds = [
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
        ];

        function playVideo(src) {
            console.log("Video "+src);
            if (player) {
                player.src = '/assets/video/'+src;
                $(player).show();
                player.currentTime = 0;
                player.play();
            }
        }

        function getBackend() {
            if (player == null && document.getElementById('tvvideo') != null) {
                player = document.getElementById('tvvideo');
                player.addEventListener('ended',function() {
                    $(player).hide();
                },false);
                $(player).hide();
            }

            $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
                response.data.progress = parseInt(response.data.progress);
                $scope.data = response.data;
                if (currentPuzzles == null) currentPuzzles = response.data.puzzles;
                if (currentParams == null) currentParams = response.data.params;
                if ($scope.data.status != lastStatus) {
                    lastStatus = $scope.data.status;
                    if (player) $(player).hide();
                    switch($scope.data.status) {
                        case 2:
                            alarma.play();
                            break;
                        case 1:
                            break;
                        case 3:
                            break;
                        case 0:
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
                    if ($scope.clue != '') ping.play();
                }

                if ($scope.data.status==2 || $scope.data.status==1) {
                    if ($scope.data.puzzles) $.each($scope.data.puzzles, function(i,e) {
                        if (currentPuzzles[i] != e) {
                            //cambio detectado
                            currentPuzzles[i] = e;

                            if (currentPuzzles[i] && videos.hasOwnProperty(i+1)) {
                                playVideo(videos[i+1]);
                            }
                            if (currentPuzzles[i]) {
                                switch(i) {
                                    case 2:
                                        alarma.pause();
                                        $scope.screen = "cluesInfo";
                                        break;
                                }
                            }

                        }
                    });
                    if ($scope.data.params) $.each($scope.data.params, function(i,e) {
                        if (currentParams[i] != e) {
                            //cambio detectado
                            currentParams[i] = e;
                            i = String(i).toLowerCase();
                            console.log(i, e);
                            switch(String(i).toLowerCase()) {
                                case 'adn':
                                    $scope.isAlien = (e != "humano");
                                    switch(e) {
                                        case 'escaneando':
                                            playVideo('adnscan.mp4');
                                            break;
                                    }
                                    break;
                                case 'alarma':
                                    switch(e) {
                                        case 'encendida':
                                            alarma.play();
                                            break;
                                        default:
                                            alarma.pause();
                                            break;
                                    }
                                    break;
                                case '':
                                    switch(e) {
                                        case '':
                                            break;
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }

                    });
                }
            });
        }

        $interval(function() {
            var $now = (new Date()).getTime();
            $scope.data.punishment = $scope.data.punishment || 0;
            $scope.timePass = $now - $scope.data.start + $scope.data.punishment*60000;
            $scope.timeLeft = 3600000 - $scope.timePass;
        },100);

        $interval(getBackend,500);
        getBackend();

    }]);

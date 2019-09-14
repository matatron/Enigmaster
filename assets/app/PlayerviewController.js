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
                    console.log("New status", lastStatus, "->", $scope.data.status);
                    lastStatus = $scope.data.status;
                    if (player) $(player).hide();
                    switch($scope.data.status) {
                        case 2:
                            console.log(alarma);
                            const playPromise = alarma.play();
                            if (playPromise !== null){
                                playPromise.catch(() => { alarma.play(); })
                            }
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

                            if (currentPuzzles[i]) {
                                switch(i+1) {
                                    case 3:
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
                                        case 'apagada':
                                            alarma.pause();
                                            break;
                                        default:
                                            alarma.play();
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

    }])
    .controller('PlayerviewControllerPi2', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.data={};
        $scope.data.status = 0;
        $scope.data.start = 0;
        $scope.screen = ''
        $scope.clue = '';
        $scope.section = '';
        $scope.combustible = 0;
        $scope.celdas = new Array(18);

        var lastStatus = null;
        var currentPuzzles = null;
        var currentParams = null;
        var player;
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');

        function playVideo(src) {
            if (player == null && document.getElementById('videoAndromeda') != null) {
                player = document.getElementById('videoAndromeda');
                player.addEventListener('ended',function() {
                    $scope.section = '';
                },false);
            }

            console.log("Video "+src, player);
            if (player) {
                player.src = src;
                $(player).show();
                player.currentTime = 0;
                player.play();
            }
        }

        function getBackend() {
            $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
                response.data.progress = parseInt(response.data.progress);
                $scope.data = response.data;
                if (currentPuzzles == null) currentPuzzles = response.data.puzzles;
                if (currentParams == null) currentParams = response.data.params;
                if ($scope.data.status != lastStatus) {
                    console.log("New status", lastStatus, "->", $scope.data.status);
                    lastStatus = $scope.data.status;
                    if (player) $(player).hide();
                    switch($scope.data.status) {
                        case 2:
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

                            if (currentPuzzles[i]) {
                                switch(i+1) {
                                    case 9:
                                        $scope.screen = "menuHex";
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
                                case 'nivel':
                                    var nivel = parseInt(e);
                                    $scope.combustible = nivel*(nivel+3);
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

        document.addEventListener('keydown', (event) => {
            const keyName = event.key;
            $("#pi2").removeClass().addClass("hex"+keyName);
            $scope.section = "section"+keyName;
            if (player) player.pause();
            if (keyName == 6) {
                setTimeout(function() {
                    //playVideo('file:///home/pi/andromeda.mp4');
                    playVideo('http://127.0.0.1/andromeda.mp4');
                }, 200);
            }
        });

        $interval(getBackend,1000);
        getBackend();

    }])
    .controller('PlayerviewControllerPi3', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.data={};
        $scope.data.status = 0;
        $scope.data.start = 0;
        $scope.screen = ''
        $scope.clue = '';
        var lastStatus = null;
        $scope.currentPuzzles = null;
        $scope.currentParams = null;
        $scope.validFront = false;
        $scope.validLeft = false;
        $scope.validRight = false;
        $scope.leftLocation = '';
        $scope.rightLocation = '';
        var player;
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');

        var nextKeyPress = (new Date()).getTime();

        $scope.posX = 0;
        $scope.posY = 0;
        reportGizmo();
        var direccion = 0;
        var lastDireccion = -1;
        var letras = ["A", "B", "C", "D", "E", "F", "G"];
        var mapa = [
            [  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0],
            [  0,  8,  0,  8,  0,  8,  1,  8,  1,  8,  0,  8,  1,  8,  0],
            [  0,  1,  0,  1,  0,  0,  0,  1,  0,  0,  0,  1,  0,  0,  0],
            [  0,  8,  1,  8,  1,  8,  0,  8,  1,  8,  0,  8,  1,  8,  0],
            [  0,  0,  0,  1,  0,  0,  0,  1,  0,  0,  0,  1,  0,  0,  0],
            [  0,  8,  1,  8,  0,  8,  0,  8,  1,  8,  1,  8,  0,  8,  0],
            [  0,  0,  0,  1,  0,  1,  0,  0,  0,  1,  0,  0,  0,  1,  0],
            [  0,  8,  0,  8,  1,  8,  1,  8,  1,  8,  0,  8,  1,  8,  0],
            [  0,  1,  0,  0,  0,  1,  0,  0,  0,  0,  0,  1,  0,  1,  0],
            [  0,  8,  1,  8,  1,  8,  1,  8,  0,  8,  1,  8,  0,  8,  0],
            [  0,  1,  0,  0,  0,  0,  0,  1,  0,  1,  0,  1,  0,  0,  0],
            [  0,  8,  0,  8,  1,  8,  1,  8,  1,  8,  0,  8,  1,  8,  0],
            [  0,  0,  0,  1,  0,  0,  0,  1,  0,  1,  0,  0,  0,  0,  0],
            [  0,  8,  1,  8,  0,  8,  1,  8,  0,  8,  1,  8,  1,  8,  0],
            [  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0,  0]
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


        $scope.currentLocation = function() {
            return letras[$scope.posX] + "-" + $scope.posY;
        }
        $scope.nextLocation = function() {
            var nextX, nextY;
            switch (direccion) {
                case 0:
                    nextX = $scope.posX;
                    nextY = $scope.posY-1;
                    $scope.leftLocation = letras[$scope.posX-1] + "-" + $scope.posY;
                    $scope.rightLocation = letras[$scope.posX+1] + "-" + $scope.posY;
                    $scope.validFront = (mapa[$scope.posY*2+0][$scope.posX*2+1] == 1);
                    $scope.validLeft = (mapa[$scope.posY*2+1][$scope.posX*2+0] == 1);
                    $scope.validRight = (mapa[$scope.posY*2+1][$scope.posX*2+2] == 1);
                    break;
                case 1:
                    nextX = $scope.posX+1;
                    nextY = $scope.posY;
                    $scope.leftLocation = letras[$scope.posX] + "-" + ($scope.posY-1);
                    $scope.rightLocation = letras[$scope.posX] + "-" + ($scope.posY+1);
                    $scope.validFront = (mapa[$scope.posY*2+1][$scope.posX*2+2] == 1);
                    $scope.validLeft = (mapa[$scope.posY*2+0][$scope.posX*2+1] == 1);
                    $scope.validRight = (mapa[$scope.posY*2+2][$scope.posX*2+1] == 1);
                    break;
                case 2:
                    nextX = $scope.posX;
                    nextY = $scope.posY+1;
                    $scope.leftLocation = letras[$scope.posX+1] + "-" + $scope.posY;
                    $scope.rightLocation = letras[$scope.posX-1] + "-" + $scope.posY;
                    $scope.validFront = (mapa[$scope.posY*2+2][$scope.posX*2+1] == 1);
                    $scope.validLeft = (mapa[$scope.posY*2+1][$scope.posX*2+2] == 1);
                    $scope.validRight = (mapa[$scope.posY*2+1][$scope.posX*2+0] == 1);
                    break;
                case 3:
                    nextX = $scope.posX-1;
                    nextY = $scope.posY;
                    $scope.leftLocation = letras[$scope.posX] + "-" + ($scope.posY+1);
                    $scope.rightLocation = letras[$scope.posX] + "-" + ($scope.posY-1);
                    $scope.validFront = (mapa[$scope.posY*2+1][$scope.posX*2+0] == 1);
                    $scope.validLeft = (mapa[$scope.posY*2+2][$scope.posX*2+1] == 1);
                    $scope.validRight = (mapa[$scope.posY*2+0][$scope.posX*2+1] == 1);
                    break;
            }
            return letras[nextX] + "-" + nextY;
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
                if ($scope.currentPuzzles == null) $scope.currentPuzzles = response.data.puzzles;
                if ($scope.currentParams == null) $scope.currentParams = response.data.params;
                if ($scope.data.status != lastStatus) {
                    console.log("New status", lastStatus, "->", $scope.data.status);
                    lastStatus = $scope.data.status;
                    if (player) $(player).hide();
                    switch($scope.data.status) {
                        case 2:
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
                        if ($scope.currentPuzzles[i] != e) {
                            //cambio detectado
                            $scope.currentPuzzles[i] = e;

                            if ($scope.currentPuzzles[i]) {
                                switch(i+1) {
                                    case 3:
                                        break;
                                }
                            }

                        }
                    });
                    if ($scope.data.params) $.each($scope.data.params, function(i,e) {
                        if ($scope.currentParams[i] != e) {
                            //cambio detectado
                            $scope.currentParams[i] = e;
                            i = String(i).toLowerCase();
                            console.log(i, e);
                            switch(String(i).toLowerCase()) {
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

        function animateSpace() {
            if (direccion == 0 && lastDireccion == 3) {
                $(".space").animate({
                    left: -4*800,
                }, 500, function() {
                    $(".space").css("left", 0);
                    // Animation complete.
                });
            } else if (direccion == 3 && lastDireccion == 0) {
                $(".space").css("left", -3200).animate({
                    left: -direccion*800,
                }, 500, function() {
                    // Animation complete.
                });
            } else {
                $(".space").animate({
                    left: -direccion*800,
                }, 500, function() {
                    // Animation complete.
                });
            }
        }

        function reportGizmo() {
            $http.get('/gizmo/reportjson/PI3/?posX='+$scope.posX+"&posY="+$scope.posY);
        }

        document.addEventListener('keydown', (event) => {
            const keyName = event.key;
            if ((new Date()).getTime() > nextKeyPress) {
                nextKeyPress = (new Date()).getTime();
                console.log(nextKeyPress);
                switch(keyName) {
                    case "a":
                        lastDireccion = direccion;
                        direccion = (direccion+3)%4;
                        nextKeyPress += 500;
                        animateSpace();
                        break;
                    case "d":
                        lastDireccion = direccion;
                        direccion = (direccion+1)%4;
                        nextKeyPress += 500;
                        animateSpace();
                        break;
                    case "w":
                        if ($scope.validFront) {
                            switch(direccion) {
                                case 0:
                                    $scope.posY--;
                                    break;
                                case 1:
                                    $scope.posX++;
                                    break;
                                case 2:
                                    $scope.posY++;
                                    break;
                                case 3:
                                    $scope.posX--;
                                    break;
                            };
                        }
                        reportGizmo();
                        if ($scope.posX == 4 && $scope.posY == 0) {
                            playVideo("http://127.0.0.1/aterrizaje.mp4");
                        } else {
                            //playVideo("http://127.0.0.1/ftl.mp4");
                            //nextKeyPress += 10000;
                        }
                        break;
                }
            }
        });


        $interval(function() {
            var $now = (new Date()).getTime();
            $scope.data.punishment = $scope.data.punishment || 0;
            $scope.timePass = $now - $scope.data.start + $scope.data.punishment*60000;
            $scope.timeLeft = 3600000 - $scope.timePass;
        },100);

        $interval(getBackend,500);
        getBackend();

    }]);

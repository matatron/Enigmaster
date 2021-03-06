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
        var loading = false;
        var lastStatus = null;
        var currentPuzzles = null;
        var currentParams = null;
        var player = null;
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
        var alarma = new Audio('/assets/audio/alarma.mp3');
        var musica = new Audio('/assets/audio/espacio1.mp3');

		$scope.playPing = function () {
			ping.play();
		}
        function playVideo(src) {
            if (player == null && document.getElementById('tvvideo') != null) {
                player = document.getElementById('tvvideo');
                player.addEventListener('ended',function() {
                    $(player).hide();
                },false);
            }
            if (player) {
                player.src = '/assets/video/'+src;
                $(player).show();
                player.currentTime = 0;
                player.play();
            }
        }
        function stopVideo() {
            console.log("Deteniendo video", player);
            if (player == null && document.getElementById('tvvideo') != null) {
                console.log(1,player);
                player = document.getElementById('tvvideo');
                player.addEventListener('ended',function() {
                    $(player).hide();
                },false);
            }
            if (player) {
                console.log(2,player);
                $(player).hide();
                console.log(3,player);
                player.currentTime = 0;
                player.pause();
                console.log(4,player);
            }
        }


        function getBackend() {
            if (loading) return;
            loading = true;
            $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
                loading = false;
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
                            stopVideo();
                            $scope.screen = '';
                            $scope.isAlien = true;
                            musica.play();
                            const playPromise = alarma.play();
                            if (playPromise !== null){
                                playPromise.catch(() => { alarma.play(); })
                            }
                            break;
                        case 1:
                            break;
                        case 3:
                            $(player).hide();
                            $scope.isAlien = true;
                            $scope.screen = '';
                            alarma.currentTime = 0;
                            musica.currentTime = 0;
                            alarma.pause();
                            musica.pause();
                            break;
                        case 0:
                            $(player).hide();
                            alarma.currentTime = 0;
                            musica.currentTime = 0;
                            alarma.pause();
                            musica.pause();
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
                                        $scope.screen = "cluesInfo";
                                        alarma.pause();
										ping.play();
                                        console.log("alarma apagada");
                                        stopVideo();
                                        break;
                                    case 4:
                                        $scope.screen = "cluesInfo";
                                        alarma.pause();
										ping.play();
                                        stopVideo();
                                        $scope.isAlien = false;
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
                                        case 'humano':
                                            $scope.screen = "cluesInfo";
                                            break;
                                        case 'escaneando':
                                            playVideo('adnscan.mp4');
                                            break;
                                        default:
                                            stopVideo();
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

        $interval(getBackend,1000);
        getBackend();
		ping.play();

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
        $scope.currentPuzzles = null;

        var loading = false;
        var lastStatus = null;
        var currentParams = null;
        var player;
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
        var blip = new Audio('/assets/audio/blip.mp3');

        function playVideo(src) {
            if (player == null && document.getElementById('videoAndromeda') != null) {
                player = document.getElementById('videoAndromeda');
                player.addEventListener('ended',function() {
                    selectScreen(0);
                },false);
            }

            console.log("Video "+src, player);
            if (player) {
                player.src = src;
                $(player).show();
                $(player).css("display", "block");
                player.currentTime = 0;
                player.play();
            }
        }

        function getBackend() {
            if (loading) return;
            loading = true;
            $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
                loading = false;
                response.data.progress = parseInt(response.data.progress);
                $scope.data = response.data;
                if ($scope.currentPuzzles == null) $scope.currentPuzzles = response.data.puzzles;
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
//                    if ($scope.screen == "menuHex") {
//                        selectScreen(5);
//                    }
                }

                if ($scope.data.status==2 || $scope.data.status==1) {
                    if ($scope.data.puzzles) $.each($scope.data.puzzles, function(i,e) {
                        if ($scope.currentPuzzles[i] != e) {
                            //cambio detectado
                            $scope.currentPuzzles[i] = e;

                            if ($scope.currentPuzzles[i]) {
                                switch(i+1) {
                                    case 9:
                                        $scope.screen = "hexagons";
                                        //                                        $scope.screen = "authorizado";
                                        console.log($scope.screen);
                                        console.log($(".hex"));
                                        $timeout(function() {
                                            $(".hex").each(function(i, e) {
                                                console.log(i*60);
                                                $(e).data("angle", i*60);
                                                $(e).css("transform", "rotate("+(i*60)+"deg)");
                                            });
                                        }, 500);

                                        break;
                                    case 12:
                                        $scope.screen = "authorizado";
                                        setTimeout(function() {
                                            $scope.screen = "menuHex";
                                            selectScreen(0);
                                        }, 2000);

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
                                case 'generado':
                                    ping.play();
                                    $scope.data.params.generado = parseInt($scope.data.params.generado) || 0;
                                case 'extrafuel':
                                    $scope.data.params.extrafuel = parseInt($scope.data.params.extrafuel) || 0;
                                case 'consumido':
                                    selectScreen(3);
                                    $scope.data.params.consumido = parseInt($scope.data.params.consumido) || 0;
                                    $scope.combustible = $scope.data.params.generado - $scope.data.params.consumido + $scope.data.params.extrafuel;
                                    break;
                                case '':
                                    switch(e) {
                                        case '':
                                            break;
                                    }
                                    break;
                                case 'dificultad':
                                    $scope.dificultad = $scope.data.params.dificultad;
                                    break;
                                case 'sumas':
                                    $scope.sumas = $scope.data.params.sumas;
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

        function selectScreen(n) {
            $("#pi2").removeClass().addClass("hex"+n);
            $scope.section = "section"+n;
            if (player) player.pause();
            if (n == 6) {
                setTimeout(function() {
                    $(player).show();
                    if (document.location.host == "localhost:8081") {
                        if ($scope.data.lang =="en") {
                            playVideo('/assets/video/andromedaEn.mp4');
                        } else {
                            playVideo('/assets/video/andromeda.mp4');
                        }
                    } else {
                        if ($scope.data.lang =="en") {
                            playVideo('http://127.0.0.1/andromedaEn.mp4');
                        } else {
                            playVideo('http://127.0.0.1/andromeda.mp4');
                        }
                    }
                }, 200);
            }

        }

        document.addEventListener('keydown', (event) => {
            console.log(event.key);
            switch(event.key) {
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                    (new Audio('/assets/audio/blip.mp3')).play();
                    if ($scope.screen == "hexagons") {
                        var hex = $(".h"+event.key); 
                        var angle = hex.data("angle") || 0;
                        var win = true;
                        angle += 60;
                        hex.css("transform", "rotate("+angle+"deg)");
                        hex.data("angle", angle);
                        $(".hex").each(function(i, e) {
                            if ($(e).data("angle")%360 != 0) {
                                win = false;
                            }
                        });
                        if (win) {
                            $scope.screen = "authorizado";
                            setTimeout(function() {
                                $scope.screen = "menuHex";
                                selectScreen(0);
                            }, 2000);
                        }
                    } else if ($scope.screen == "menuHex") {
                        selectScreen(event.key)
                    }
                    break;
            }
            reportGizmo();
        });

        function reportGizmo() {
            $http.get('/gizmo/reportjson/PI2/?pantalla='+$scope.screen+"&seccion="+$scope.section);
        }

        $interval(getBackend,1000);
        getBackend();

    }])
    .controller('PlayerviewControllerHouston', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.extrafuel = 0;
        $scope.easymode = 0;
        $scope.sumas = 0;
        var letras = ["x", "A", "B", "C", "D", "E", "F", "G", "x"];
        var mapa = [
            //     A       B       C       D       E       F       G
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


        $scope.reportGizmo = function () {
            $http.get('/gizmo/reportjson/houston/?extrafuel='+$scope.extrafuel+'&dificultad='+($scope.easymode?"facil":"normal")+'&sumas='+($scope.sumas?"visible":"coded"));
        }
        $scope.reportGizmo();
    }])
    .controller('PlayerviewControllerPi3', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
        $scope.data={};
        $scope.data.status = 0;
        $scope.data.start = 0;
        $scope.clue = '';
        var lastStatus = null;
        $scope.missionCompleted = false;
        $scope.currentPuzzles = null;
        $scope.currentParams = null;
        $scope.currentLocation = '';
        $scope.validFront = false;
        $scope.validLeft = false;
        $scope.validRight = false;
        $scope.leftLocation = '';
        $scope.rightLocation = '';
        $scope.fuel = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
        $scope.consumido = 0;
        $scope.generado = 18;
        var player;
        var ping = new Audio('/assets/audio/glass_ping-Go445-1207030150.mp3');
        var loading = false;
        var nextKeyPress = (new Date()).getTime() + 3600000*24;

        $scope.posX = 3;
        $scope.posY = 5;
        reportGizmo();
        $scope.direccion = 0;
        var lastDireccion = -1;
        var letras = ["x", "A", "B", "C", "D", "E", "F", "G", "x"];
        var mapa = [
            //     A       B       C       D       E       F       G
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
                player.src = src;
                $(player).show();
                player.currentTime = 0;
                player.play();
            }
        }

        function getBackend() {
            if (loading) return;
            loading = true;
            if (document.getElementById('tvvideo')!= null && player != document.getElementById('tvvideo')) {
                player = document.getElementById('tvvideo');
                player.addEventListener('ended',function() {
                    $(player).hide();
                },false);
                $(player).hide();
            }

            $http.get('/json_info/roomcompact/'+$scope.roomId).then(function(response) {
                loading = false;
                response.data.progress = parseInt(response.data.progress);
                $scope.data = response.data;

                var $now = (new Date()).getTime();
                $scope.data.punishment = $scope.data.punishment || 0;
                $scope.timePass = $now - $scope.data.start + $scope.data.punishment*60000;
                $scope.timeLeft = 3600000 - $scope.timePass;
                if ($scope.timeLeft > 0) {
                    $(".o2").css("width", $scope.timeLeft/20000);
                } else {
                    $(".o2").css("width", 0);
                }

                if ($scope.currentPuzzles == null) $scope.currentPuzzles = response.data.puzzles;
                if ($scope.currentParams == null) $scope.currentParams = response.data.params;

                $scope.generado = (response.data.params && response.data.params.generado) ? response.data.params.generado : 18;

                if ($scope.data.status != lastStatus) {
                    console.log("New status", lastStatus, "->", $scope.data.status);
					console.log(player);
                    lastStatus = $scope.data.status;
                    if (player) $(player).hide();
                    switch($scope.data.status) {
                        case 2:
                            animateSpace();
                            break;
                        case 1:
                            animateSpace();
                            break;
                        case 3:
							$(player).hide();
                            $scope.missionCompleted = false;
                            $scope.currentLocation = '';
                            $scope.consumido = 0;
                            nextKeyPress = (new Date()).getTime() + 3600000*24;
                            break;
                        case 0:
                            $(player).hide();
                            $scope.missionCompleted = false;
                            $scope.currentLocation = '';
                            $scope.consumido = 0;
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

                            if ($scope.currentPuzzles[17]==1 && $scope.currentPuzzles[19]==1) {
                                nextKeyPress = (new Date()).getTime();
                            }

                            if ($scope.currentPuzzles[i]) {
                                switch(i+1) {
                                    case 1:
                                    case 2:
                                    case 9:
										$(player).hide();
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
                            switch(String(i).toLowerCase()) {
                                case '':
                                    switch(e) {
                                        case '':
                                            break;
                                    }
                                    break;
                                case 'generado':
                                case 'extrafuel':
                                case 'consumido':
                                    $scope.data.params.generado = parseInt($scope.data.params.generado) || 0;
                                    $scope.data.params.consumido = parseInt($scope.data.params.consumido) || 0;
                                    $scope.data.params.extrafuel = parseInt($scope.data.params.extrafuel) || 0;
                                    $scope.combustible = $scope.data.params.generado - $scope.data.params.consumido + $scope.data.params.extrafuel;
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
            $scope.location0 = letras[$scope.posX+1]+"-"+($scope.posY);
            $scope.valid0 = (mapa[$scope.posY*2+0][$scope.posX*2+1] == 1);
            $scope.location1 = letras[$scope.posX+2]+"-"+($scope.posY+1);
            $scope.valid1 = (mapa[$scope.posY*2+1][$scope.posX*2+2] == 1);
            $scope.location2 = letras[$scope.posX+1]+"-"+($scope.posY+2);
            $scope.valid2 = (mapa[$scope.posY*2+2][$scope.posX*2+1] == 1);
            $scope.location3 = letras[$scope.posX+0]+"-"+($scope.posY+1);
            $scope.valid3 = (mapa[$scope.posY*2+1][$scope.posX*2+0] == 1);

            $(".space").css("background-position", "0 -"+(450*($scope.posX+$scope.posY))+"px");
            $("#planeta").attr("src", "/assets/images/planetas/planeta"+letras[$scope.posX+1]+($scope.posY+1)+".png");
            $scope.currentLocation = letras[$scope.posX+1] + "-" + ($scope.posY+1);

            switch ($scope.direccion) {
                case 0:
                    $scope.validFront = $scope.valid0;
                    break;
                case 1:
                    $scope.validFront = $scope.valid1;
                    break;
                case 2:
                    $scope.validFront = $scope.valid2;
                    break;
                case 3:
                    $scope.validFront = $scope.valid3;
                    break;
            }

            if ($scope.direccion == 0 && lastDireccion == 3) {
                $(".space").animate({
                    left: -4*800,
                }, 500, function() {
                    $(".space").css("left", 0);
                    // Animation complete.
                });
            } else if ($scope.direccion == 3 && lastDireccion == 0) {
                $(".space").css("left", -3200).animate({
                    left: -$scope.direccion*800,
                }, 500, function() {
                    // Animation complete.
                });
            } else {
                $(".space").animate({
                    left: -$scope.direccion*800,
                }, 500, function() {
                    // Animation complete.
                });
            }
            reportGizmo();
        }

        function reportGizmo() {
            $http.get('/gizmo/reportjson/PI3/?PLANETA='+$scope.currentLocation+'&posX='+$scope.posX+"&posY="+$scope.posY+"&consumido="+$scope.consumido+"&mission="+($scope.missionCompleted?"completa":"incompleta"));
        }

        document.addEventListener('keydown', (event) => {
            const keyName = event.key;
            if ((new Date()).getTime() > nextKeyPress) {
                nextKeyPress = (new Date()).getTime();
                console.log(nextKeyPress);
				 
                switch(keyName) {
                    case "a":
                        lastDireccion = $scope.direccion;
                        $scope.direccion = ($scope.direccion+3)%4;
                        nextKeyPress += 500;
						(new Audio('/assets/audio/blip.mp3')).play();
                        animateSpace();
                        break;
                    case "d":
                        lastDireccion = $scope.direccion;
                        $scope.direccion = ($scope.direccion+1)%4;
                        nextKeyPress += 500;
						(new Audio('/assets/audio/blip.mp3')).play();
                        animateSpace();
                        break;
                    case "w":
                        if ($scope.validFront && $scope.combustible > 0) {
							(new Audio('/assets/audio/blip.mp3')).play();
                            switch($scope.direccion) {
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
                            if ($scope.data.people >= 4) {
                                $scope.consumido+=2;
                            } else {
                                $scope.consumido++;
                            }
                            if ($scope.posX == 4 && $scope.posY == 0) {
                                reportGizmo();
                                if (document.location.host == "localhost:8081") {
                                    playVideo("/assets/video/aterrizaje.mp4");
                                } else {
                                    playVideo("http://127.0.0.1/aterrizaje.mp4");
                                }
                                $timeout(function() {
									$(player).hide();
                                    $scope.missionCompleted = true;
                                    reportGizmo();
                                }, 13000);
                            } else {
                                if (document.location.host == "localhost:8081") {
                                    playVideo("/assets/video/ftl.mp4");
                                }else {
                                    playVideo("http://127.0.0.1/ftl.mp4");
                                }
                                nextKeyPress += 6500;
                                animateSpace();
                            }
                        } else {
							(new Audio('/assets/audio/chicharra-error-incorrecto-.mp3')).play();
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
            //$scope.time = 
        },100);

        $interval(getBackend,500);
        getBackend();

    }]);

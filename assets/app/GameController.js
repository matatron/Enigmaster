webApp.controller('GameController', ['$scope', '$http', '$timeout', '$interval', '$filter', function($scope, $http, $timeout, $interval, $filter) {
    var ctrl = this;
    var flux;
    var updatingBackend = false;
    var loadTimer = null;

    ctrl.modifier = 0;
    ctrl.now = 0;
    ctrl.tiempoPistas = 0;

    ctrl.startTime = function() {
        var now = new Date();
        ctrl.data.status = 2;
        ctrl.js_start = now;
        ctrl.js_end = now;
        ctrl.data.start = Math.round(ctrl.js_start.getTime()/1000);
        ctrl.updateBackend(['start', 'status']);
        ctrl.getTime();
    }

    ctrl.getTime = function () {
        ctrl.now = new Date();
        //        ctrl.timeLeft = ctrl.js_end - (new Date(now.getTime() + ctrl.data.total_clues*ctrl.minutesPerClue*60000)) ;
        //        ctrl.timeLeft = (ctrl.js_end < ctrl.now) ? "-"+$filter('date')(ctrl.now-ctrl.js_end,'HH:mm:ss','UTC'):$filter('date')(ctrl.js_end - ctrl.now,'HH:mm:ss','UTC');
        //        ctrl.timeLeft = $filter('date')(ctrl.js_end - ctrl.now,'HH:mm:ss','UTC');
        ctrl.timePass = ctrl.now - ctrl.js_start;
        ctrl.tiempoPistas = Math.max(0, ctrl.data.total_clues-ctrl.data.free_clues)*ctrl.data.minutesxclue;
        ctrl.timeLeft = 3600000 - ctrl.timePass - (ctrl.data.punishment + ctrl.tiempoPistas)*60000;
        //        ctrl.timeLeft = ;
        ctrl.percent = Math.floor(ctrl.timePass/36000);
        ctrl.percent2 = Math.floor(((ctrl.data.punishment + ctrl.tiempoPistas)*60000)/36000);
    }

    ctrl.addTime = function () {
        ctrl.js_end.setTime(ctrl.js_end.getTime()+ctrl.modifier*60000);
        ctrl.data.end = Math.round(ctrl.js_end.getTime()/1000);
        ctrl.modifier = 0;
        ctrl.updateBackend(['end']);
        ctrl.getTime();
    }

    ctrl.updateBackend = function(fields) {
        updatingBackend = true;
        $http.post('/json_info/savegroup/'+ctrl.roomId, _.pick(ctrl.data, fields)).then(function(response) {
            updatingBackend = false;
            switch(response.data.status) {
                case 'success':
                    break;
            }
        }, function() {

        });

    }

    ctrl.togglePuz = function(n, force) {
        n %= ctrl.puzzles.length;
        if (force == undefined) {
            ctrl.puzzles[n].complete = 1-ctrl.puzzles[n].complete;
            if (ctrl.puzzles[n].complete) {
                ctrl.puzzles[n].solvedAt = Math.round((new Date()).getTime()/1000);
            }
            updateClues();
            ctrl.data.puzzles = JSON.stringify(ctrl.puzzles);
            ctrl.updateBackend(['puzzles', 'progress']);
        } else {
            if (ctrl.puzzles[n].complete != force) {
                ctrl.puzzles[n].complete = force;
                updateClues();
                ctrl.data.puzzles = JSON.stringify(ctrl.puzzles);
                ctrl.updateBackend(['puzzles', 'progress']);
            }
        }
    }

    function updateClues() {
        var clues = [
            {es: '¿Pista?', en: 'Clue?', value: '¿Pista?'}
        ],
            progress = 0;
        _.forEach(ctrl.puzzles, function (puzzle, pos){
            if (!puzzle.complete) {
                clues = clues.concat(puzzle.clues);
            } else {
                progress = pos+1;
            }
        });
        ctrl.clues = clues;
        ctrl.data.progress = Math.max(progress, ctrl.data.progress);
    }

    ctrl.copyClue = function(clue, counts) {
        $scope.customClue = clue.value;
    }

    ctrl.sendClue = function(clue, counts) {
        console.log(clue);
        if (angular.isObject(clue)) {
            if (counts) clue.sent = true;
            ctrl.cluesSent.unshift({date: (new Date()).getTime(), value: clue.value, counts: counts});
            ctrl.data.clues = JSON.stringify(ctrl.cluesSent);
            ctrl.data.total_clues = _.filter(ctrl.cluesSent, 'counts').length;
            ctrl.data.puzzles = JSON.stringify(ctrl.puzzles);
            ctrl.updateBackend(['clues', 'total_clues', 'puzzles']);
        } else {
            if (clue == '') counts = false;
            ctrl.cluesSent.unshift({date: (new Date()).getTime(), value: clue, counts: counts});
            $scope.customClue = '';
            ctrl.data.clues = JSON.stringify(ctrl.cluesSent);
            ctrl.data.total_clues = _.filter(ctrl.cluesSent, 'counts').length;
            ctrl.updateBackend(['clues', 'total_clues']);
        }
    }

    ctrl.updateClueTimes = function() {
        ctrl.updateBackend(['minutesxclue', 'free_clues']);
    }

    ctrl.updatePunishment = function(n) {
      console.log(n);
      if (parseInt(n)) {
        ctrl.data.punishment+= parseInt(n);
      }
        ctrl.updateBackend(['punishment']);
    }

    ctrl.changeLang = function() {
        _.forEach(ctrl.puzzles, function(puzzle) {
            _.forEach(puzzle.clues, function(clue) {
                clue.value = clue[ctrl.data.language];
            })
        });
        ctrl.updateBackend(['language']);
    }


    window.ctrl = this;
    function getFirstData() {
        $http.get('/json_info/group/'+ctrl.roomId).then(function(response) {
            response.data.free_clues = parseInt(response.data.free_clues);
            response.data.minutesxclue = parseInt(response.data.minutesxclue);
            response.data.status = parseInt(response.data.status);
            response.data.total_clues = parseInt(response.data.total_clues);
            response.data.punishment = parseInt(response.data.punishment || 0);
            response.data.id = parseInt(response.data.id);

            ctrl.data = response.data;
            ctrl.js_start = new Date(ctrl.data.start*1000);
            ctrl.data.show_progress = (ctrl.data.show_progress == 1);
            ctrl.puzzles = JSON.parse(ctrl.data.puzzles);
            if (ctrl.data.language != 'en') ctrl.data.language = 'es';
            _.forEach(ctrl.puzzles, function(puzzle) {
                _.forEach(puzzle.clues, function(clue) {
                    clue.value = clue[ctrl.data.language];
                })
            });
            ctrl.cluesSent = JSON.parse(ctrl.data.clues) || [];
            updateClues();
            ctrl.getTime();
            if (response.data.status == 2) {
                $interval.cancel(loadTimer);
                getProgress();
                $interval(ctrl.getTime,100);
            }
        });
    }
    function getProgress() {
        $http.get('/json_info/progress/'+ctrl.roomId).then(function(response) {
            response.data.id = parseInt(response.data.id);
            response.data.progress = parseInt(response.data.progress);
            if (ctrl.data.id == response.data.id) {
                if (!updatingBackend) {
                    if (ctrl.data.progress < response.data.progress) {
                        ctrl.data.progress = response.data.progress;
                    }

                    if (response.data.puzzles.length) {
                        _.forEach(response.data.puzzles, function(p, i) {
                            ctrl.togglePuz(i, p)
                        });
                        ctrl.data.status = parseInt(response.data.status);
                        ctrl.data.punishment = parseInt(response.data.punishment || 0);
                        ctrl.gizmos = response.data.gizmos;

                    }

                }
            }else {
                window.location.reload();
            }
            //            if (ctrl.data.status == 1) $interval.cancel(flux);
        });
    }

    loadTimer = $interval(getFirstData,3000);
    flux = $interval(getProgress, 500);
    $timeout(function() {
        getFirstData();
    }, 50);
}]);

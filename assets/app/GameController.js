webApp.controller('GameController', ['$scope', '$http', '$timeout', '$interval', function($scope, $http, $timeout, $interval) {
    var ctrl = this;

    ctrl.modifier = 0;

    ctrl.startTime = function() {
        var now = new Date();
        ctrl.data.status = 1;
        ctrl.js_start = new Date(now.getTime() + 5000);
        ctrl.js_end = new Date(now.getTime() + ctrl.minutes*60000 + 5000);
        ctrl.data.start = Math.round(ctrl.js_start.getTime()/1000);
        ctrl.data.end = Math.round(ctrl.js_end.getTime()/1000);
        ctrl.updateBackend(['start', 'end', 'status']);
        ctrl.getTime();
    }

    ctrl.getTime = function () {
        var now = new Date();
//        ctrl.timeLeft = ctrl.js_end - (new Date(now.getTime() + ctrl.data.total_clues*ctrl.minutesPerClue*60000)) ;
        ctrl.timeLeft = ctrl.js_end - (new Date(now.getTime()));
        ctrl.percent = 100 - Math.floor(ctrl.timeLeft/3600000*100);
        if (ctrl.timeLeft<0) {

        }
    }

    ctrl.addTime = function () {
        ctrl.js_end.setTime(ctrl.js_end.getTime()+ctrl.modifier*60000);
        ctrl.data.end = Math.round(ctrl.js_end.getTime()/1000);
        ctrl.modifier = 0;
        ctrl.updateBackend(['end']);
        ctrl.getTime();
    }

    ctrl.updateBackend = function(fields) {
        $http.post('/json_info/savegroup/'+ctrl.roomId, _.pick(ctrl.data, fields)).then(function(response) {
            switch(response.data.status) {
                case 'success':
                    break;
            }
        }, function() {

        })

    }

    ctrl.togglePuz = function(n) {
        ctrl.puzzles[n].complete = 1-ctrl.puzzles[n].complete;
        if (ctrl.puzzles[n].complete) {
            ctrl.puzzles[n].solvedAt = Math.round((new Date()).getTime()/1000);
        }
        updateClues();
        ctrl.data.puzzles = JSON.stringify(ctrl.puzzles);
        ctrl.updateBackend(['puzzles', 'progress']);
    }

    function updateClues() {
        var clues = [],
            progress = 0;
        _.forEach(ctrl.puzzles, function (puzzle, pos){
            if (!puzzle.complete) {
                clues = clues.concat(puzzle.clues);
            } else {
                progress = pos+1;
            }
        });
        ctrl.clues = clues;
        ctrl.data.progress = progress;
    }

    ctrl.sendClue = function(clue, counts) {
        if (angular.isObject(clue)) {
            if (counts) clue.sent = true;
            ctrl.cluesSent.unshift({date: (new Date()).getTime(), value: clue.value, counts: counts});
            ctrl.data.clues = JSON.stringify(ctrl.cluesSent);
            ctrl.data.total_clues = _.filter(ctrl.cluesSent, 'counts').length;
            ctrl.data.puzzles = JSON.stringify(ctrl.puzzles);
            ctrl.updateBackend(['clues', 'total_clues', 'puzzles']);
        } else if (clue) {
            ctrl.cluesSent.unshift({date: (new Date()).getTime(), value: clue, counts: counts});
            $scope.customClue = '';
            ctrl.data.clues = JSON.stringify(ctrl.cluesSent);
            ctrl.data.total_clues = _.filter(ctrl.cluesSent, 'counts').length;
            ctrl.updateBackend(['clues', 'total_clues']);
        }
    }

    $timeout(function() {
        $http.get('/json_info/group/'+ctrl.roomId).then(function(response) {
            ctrl.data = response.data;
            ctrl.minutes = ctrl.data.room.minutes;
            ctrl.minutesPerClue = parseInt(ctrl.data.room.minPerClue);
            ctrl.js_start = new Date(ctrl.data.start*1000);
            ctrl.js_end = new Date(ctrl.data.end*1000);
            ctrl.data.show_progress = (ctrl.data.show_progress == 1);
            ctrl.data.total_clues = 0;
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
        });
        $interval(getGizmos, 3000);
        getGizmos();
    },50);

    window.ctrl = this;
    function getGizmos() {
        $http.get('/json_info/gizmos/'+ctrl.roomId).then(function(response) {
            ctrl.gizmos = response.data;
        });
    }

    $interval(ctrl.getTime,100);

}]);

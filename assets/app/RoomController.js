webApp.controller('RoomController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
    var ctrl = this;
    ctrl.alerts = [];
    ctrl.closeAlert = function(index) {
        ctrl.alerts.splice(index, 1);
    };
    ctrl.selected = null;

    ctrl.addPuzzle = function() {
        ctrl.puzzles.push({
            name: 'New Puzzle',
            description: '',
            music: '',
            clues: [],
            gizmo: '',
            complete: false,
            solvedAt: 0,
            reset: ''
        });
    }

    ctrl.selectPuzzle = function(n) {
        ctrl.selected = n;
    }

    ctrl.removePuzzle = function() {
        if (ctrl.selected != null) {
            ctrl.puzzles.splice(ctrl.selected, 1);
            ctrl.selected = null;
        }
    }

    ctrl.swapPuzzle = function(n) {
        if (n>0) {
            var r = ctrl.puzzles[n];
            ctrl.puzzles[n] = ctrl.puzzles[n-1];
            ctrl.puzzles[n-1] = r;
        }
    }

    ctrl.addClue = function(n) {
        ctrl.puzzles[n].clues.push({es: '', en: ''});
    }

    ctrl.removeClue = function(n, k) {
        ctrl.puzzles[n].clues.splice(k, 1);
    }

    ctrl.save = function() {
        ctrl.data.puzzles = JSON.stringify(ctrl.puzzles);
        ctrl.data.config = JSON.stringify(ctrl.config);
        $http.post('/json_info/saveroom/'+ctrl.roomId, ctrl.data).then(function(response) {
            switch(response.data.status) {
                case 'success':
                    ctrl.alerts.push({msg: 'Datos guardados'});
                    break;
            }
        }, function() {

        });
    }

    $timeout(function() {
        $http.get('/json_info/room/'+ctrl.roomId).then(function(response) {
            ctrl.data = response.data;
            if (!ctrl.data.puzzles) {
                ctrl.puzzles = [];
            } else {
                ctrl.puzzles = JSON.parse(ctrl.data.puzzles);
            }
            if (!ctrl.data.config) {
                ctrl.config = {};
            } else {
                ctrl.config = JSON.parse(ctrl.data.config);
            }
        });
    },100);

}]);

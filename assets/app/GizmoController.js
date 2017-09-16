webApp.controller('GizmoController', ['$scope', '$http', function($scope, $http) {
    var ctrl = this;

    ctrl.alerts = [];
    ctrl.closeAlert = function(index) {
        ctrl.alerts.splice(index, 1);
    };

    ctrl.loadGizmo = function (uid) {
        $http.get('/json_info/gizmo/'+uid).then(function(response) {
            ctrl.gizmo = response.data;
            console.log(ctrl.gizmo.ifttt);
            ctrl.rules = JSON.parse(ctrl.gizmo.ifttt) || [];
        });

    }

    ctrl.addRule = function(n) {
        ctrl.rules.push({if: '', this: '', then: '', that: ''});
    }

    ctrl.removeRule = function(k) {
        ctrl.rules.splice(k, 1);
    }

    ctrl.saveGizmo = function() {
        ctrl.gizmo.ifttt = JSON.stringify(ctrl.rules);
        $http.post('/json_info/savegizmo/'+ctrl.gizmo.uid, ctrl.gizmo).then(function(response) {
            ctrl.alerts.push({msg: 'Datos guardados'});
        }, function() {

        });

    }


}]);

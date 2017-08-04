webApp.controller('PregameController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
    var ctrl = this;
    ctrl.name = this.roomId;
    ctrl.alerts = [];
    ctrl.closeAlert = function(index) {
        ctrl.alerts.splice(index, 1);
    };

    ctrl.save = function(admin) {
        ctrl.data.people_info = JSON.stringify(ctrl.people_info);
        $http.post('/json_info/savegroup/'+ctrl.roomId, _.pick(ctrl.data, ['team_name', 'comments', 'people', 'people_info', 'team_type', 'language'])).then(function(response) {
            switch(response.data.status) {
                case 'success':
                    ctrl.alerts.push({msg: 'Datos guardados'});
                    if (admin) document.location = "/main/game/"+ctrl.roomId;
                    break;
            }
        }, function() {

        });
    }

    ctrl.updatePeople = function () {
        if (parseInt(ctrl.data.people) > ctrl.people_info.length) {
            while(ctrl.people_info.length < parseInt(ctrl.data.people)) {
                ctrl.people_info.push({name: Math.random()*Math.random(), sex: 'female'})
            }
        } else {
            ctrl.people_info = _.take(ctrl.people_info, parseInt(ctrl.data.people));
        }
    }

    ctrl.personSex = function(n) {
        ctrl.people_info[n].sex = (ctrl.people_info[n].sex == 'male') ? 'female' : 'male';
    }

    ctrl.addPerson = function(n) {
        ctrl.data.people = parseInt(ctrl.data.people) + n;
        if (ctrl.data.people < 1) ctrl.data.people=1;
        ctrl.updatePeople();
    }

    $timeout(function() {
        $http.get('/json_info/group/'+ctrl.roomId).then(function(response) {
            ctrl.data = response.data;
            ctrl.data.show_progress = (ctrl.data.show_progress == 1);
            if(!ctrl.data.people_info) {
                ctrl.people_info = [];
                ctrl.updatePeople();
            } else {
                ctrl.people_info = JSON.parse(ctrl.data.people_info);
            }
        });
    },50);
}]);

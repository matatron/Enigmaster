<div ng-controller="PregameController as ctrl" ng-init="ctrl.roomId = <?=$roomId; ?>">
    <h1>Información Preliminar</h1>

    <h3>Juego: {{ctrl.data.room.name}}</h3>
    <form class="form-horizontal">
        <div class="form-group">
            <label for="fTeamName" class="col-sm-2 control-label">Nombre del Equipo</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fTeamName" placeholder="Nombre" ng-model="ctrl.data.team_name">
            </div>
        </div>
        <div class="form-group">
            <label for="fType" class="col-sm-2 control-label">Tipo de Equipo</label>
            <div class="col-sm-10">
                <select class="form-control" id="fType" ng-model="ctrl.data.team_type">
                    <option value="Normal">Normal</option>
                    <option value="Corporativo">Corporativo</option>
                    <option value="BETA">BETA</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="fComments" class="col-sm-2 control-label">Comentarios</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="fComments" ng-model="ctrl.data.comments"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fLanguage" class="col-sm-2 control-label">Idioma</label>
            <div class="col-sm-10">
                <select class="form-control" id="fLanguage" ng-model="ctrl.data.language">
                    <option value="es" selected>Español</option>
                    <option value="en">Inglés</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-2 text-right">
                <label class="control-label">Personas:</label>
                {{ctrl.data.people}}
            </div>

            <div class="col-xs-4 col-sm-1">
                <button class="btn btn-default btn-block" ng-click="ctrl.addPerson(-1)"><i class="glyphicon glyphicon-minus"></i></button>
            </div>
            <div class="col-xs-4 col-sm-1">
                <button class="btn btn-default btn-block" ng-click="ctrl.addPerson(1)"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
            <div class="col-xs-12 col-sm-8 people-icons">
                <div class="well">
                    <span ng-repeat="person in ctrl.people_info track by $index">
                        <i class="fa fa-{{person.sex}}" aria-hidden="true" ng-click="ctrl.personSex($index)"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-2">
                <div uib-alert ng-repeat="alert in ctrl.alerts" ng-class="'alert-' + (alert.type || 'success')" close="ctrl.closeAlert($index)" dismiss-on-timeout="3000">{{alert.msg}}</div>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-offset-2">
                <button type="button" class="btn btn-default btn-block" ng-click="ctrl.save(false)">Guardar</button>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-offset-2">
                <button type="button" class="btn btn-primary btn-block" ng-click="ctrl.save(true)">Controlar juego</button>
            </div>
        </div>
    </form>
</div>

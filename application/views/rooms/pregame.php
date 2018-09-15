<div ng-controller="PregameController as ctrl" ng-init="ctrl.roomId = <?=$roomId; ?>">
    <h1>Información Preliminar</h1>

    <h3>Juego: {{ctrl.data.room.name}}</h3>

    <div class="row">
        <div class="col-sm-12 col-md-8">

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
                    <label for="fTeamName" class="col-xs-2 control-label">Estilo de Pistas: {{ctrl.optpistas}}</label>
                    <div class="col-xs-5">
                        <button class="btn btn-block" ng-class="{'btn-info': ctrl.optpistas == 0}" ng-click="ctrl.updatePistas(0)">3 min 0 gratis</button>
                    </div>
                    <div class="col-xs-5">
                        <button class="btn btn-block" ng-class="{'btn-info': ctrl.optpistas == 1}" ng-click="ctrl.updatePistas(1)">5 min 1 gratis</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fTeamName" class="col-xs-2 control-label">Idioma</label>
                    <div class="col-xs-5">
                        <button class="btn btn-block" ng-class="{'btn-info': ctrl.data.language == 'es'}" ng-click="ctrl.data.language = 'es'">Español</button>
                    </div>
                    <div class="col-xs-5">
                        <button class="btn btn-block" ng-class="{'btn-info': ctrl.data.language == 'en'}" ng-click="ctrl.data.language = 'en'">Inglés</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fTeamName" class="col-xs-2 control-label">Ayudas Visuales</label>
                    <div class="col-xs-5">
                        <button class="btn btn-block" ng-class="{'btn-info': ctrl.data.visualflash == 0}" ng-click="ctrl.data.visualflash = 0">Desactivadas</button>
                    </div>
                    <div class="col-xs-5">
                        <button class="btn btn-block" ng-class="{'btn-info': ctrl.data.visualflash == 1}" ng-click="ctrl.data.visualflash = 1">Activadas</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-2 text-right">
                        <label class="control-label">Personas:</label>
                        {{ctrl.data.people}}
                    </div>

                    <div class="col-xs-5 col-sm-1">
                        <button class="btn btn-default btn-block" ng-click="ctrl.addPerson(-1)"><i class="glyphicon glyphicon-minus"></i></button>
                    </div>
                    <div class="col-xs-5 col-sm-1">
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
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-danger btn-block" ng-click="ctrl.startTime()">Iniciar tiempo</button>
                        <br class="hidden-md" />
                    </div>
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-default btn-block" ng-click="ctrl.save(false)">Guardar</button>
                        <br class="hidden-md" />
                    </div>
                    <div class="col-xs-4">
                        <button type="button" class="btn btn-primary btn-block" ng-click="ctrl.save(true)">Controlar juego</button>
                        <br class="hidden-md" />
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="fa fa-microchip" aria-hidden="true"></i> Gizmos</div>
                <div class="panel-body">
                    <table class="table table-hover table-condensed ">
                        <tr>
                            <th width="10%" class="text-center"><i class="fa fa-circle-o" aria-hidden="true"></i></th>
                            <th>Gizmo</th>
                            <th width="50%">Datos</th>
                        </tr>
                        <?php foreach($gizmos as $gizmo) { ?>
                        <tr class="gizmo-<?=$gizmo->id;?> gizmouid-<?=$gizmo->uid;?>"  ng-class="{'danger': !ctrl.gizmos[<?=$gizmo->id; ?>].active}">
                            <td class="text-center">
                                <i class="fa fa-circle" aria-hidden="true" ng-class="{'text-success': ctrl.gizmos[<?=$gizmo->id; ?>].active, 'text-danger': !ctrl.gizmos[<?=$gizmo->id; ?>].active}"></i>
                            </td>
                            <td>
                                <span uib-popover="<?=$gizmo->description; ?>" popover-trigger="'mouseenter'" popover-placement="left">
                                    <?=$gizmo->name; ?>
                                </span>
                            </td>
                            <td>
                                <div ng-repeat="(key, value) in ctrl.gizmos[<?=$gizmo->id; ?>].data track by $index">
                                    <strong>{{key}}</strong>: {{value}}
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

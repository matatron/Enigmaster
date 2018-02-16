<h1>Gizmos</h1>
<div class="row" ng-controller="GizmoController as ctrl">
    <div class="col-xs-3 btn-group-vertical">
        <?php foreach($gizmos as $gizmo) { ?>
        <button class="btn btn-default " ng-click="ctrl.loadGizmo('<?= $gizmo->uid; ?>')">
            <?= $gizmo->name; ?> (<?= $gizmo->lastActive; ?>)
        </button>
        <?php } ?>
    </div>
    <div class="col-xs-9" ng-show="ctrl.gizmo">
        <div class="panel panel-info">
            <div class="panel-heading">{{ctrl.gizmo.uid}}</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        Nombre:
                        <input type="text" class="form-control" id="fName" placeholder="Nombre" ng-model="ctrl.gizmo.name">
                    </div>
                    <div class="col-xs-6">
                        Cuarto:
                        <input type="text" class="form-control" id="fRoom" placeholder="Nombre" ng-model="ctrl.gizmo.room">
                    </div>
                    <div class="col-xs-12">
                        Descripción: <br />
                        <textarea ng-model="ctrl.gizmo.description" class="form-control"></textarea>
                    </div>
                    <div class="col-xs-12">
                        Configuración: <br />
                        <textarea ng-model="ctrl.gizmo.config" class="form-control"></textarea>
                    </div>
                    <div class="col-xs-12">
                        {{ctrl.gizmo.data}}
                    </div>
                    <div class="col-xs-12">
                        <div class="clearfix">
                            <button class="btn btn-xs pull-right" ng-click="ctrl.addRule();"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            <strong>Reglas:</strong> <br/>
                        </div>
                        <div ng-repeat="rule in ctrl.rules track by $index">
                            <button class="btn btn-xs pull-right" ng-click="ctrl.removeRule($index);"><i class="fa fa-minus" aria-hidden="true"></i></button>
                            Regla {{$index+1}}
                            <div class="row">
                                <div class="col-xs-3">
                                    Si
                                    <input class="form-control" ng-model="rule.if" />
                                </div>
                                <div class="col-xs-3">
                                    es igual a
                                    <input class="form-control" ng-model="rule.this" />
                                </div>
                                <div class="col-xs-3">
                                    entonces
                                    <input class="form-control" ng-model="rule.then" />
                                </div>
                                <div class="col-xs-3">
                                    deberá ser
                                    <input class="form-control" ng-model="rule.that" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <button class="btn btn-primary" type="button" ng-click="ctrl.saveGizmo()">Guardar cambios</button>
                        <div uib-alert ng-repeat="alert in ctrl.alerts" ng-class="'alert-' + (alert.type || 'success')" close="ctrl.closeAlert($index)" dismiss-on-timeout="3000">{{alert.msg}}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

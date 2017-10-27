<div ng-controller="RoomController as ctrl" ng-init="ctrl.roomId = <?=$room->id;?>">
    <h1><?= $room->name; ?></h1>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">Configuración</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="fName" class="col-sm-2 control-label">Nombre del Cuarto</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fName" placeholder="Nombre" ng-model="ctrl.data.name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fTime" class="col-sm-2 control-label">Minutos de juego</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="fTime" placeholder="Minutos" ng-model="ctrl.data.minutes">
                            </div>
                            <label for="fTime" class="col-sm-2 control-label">Minutos por pista</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="fTime" placeholder="Minutos" ng-model="ctrl.data.minPerClue">
                            </div>
                            <label for="fType" class="col-sm-2 control-label">Plantilla de visor</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="fType" ng-model="ctrl.data.view">
                                    <?php
                                    foreach(Kohana::list_files('views/playerviews') as $viewfile) {
                                        $viewfileName = $viewfile;
                                        $parts = explode('views\playerviews\\', $viewfile);
                                        if(!isset($parts[1])) $parts = explode('views\/playerviews\/', $viewfile);
                                        $viewfileName = str_replace('.php','',$parts[1]);
                                    ?>
                                    <option value="<?=$viewfileName;?>"><?=$viewfile;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-sm-12">
            <div class="panel panel-info">
                <div class="panel-heading">Acertijos</div>
                <div class="panel-body">
                    <div class="row puzzle-container" ng-repeat="puzzle in ctrl.puzzles">
                        <div class="col-xs-3">
                            <button class="btn btn-xs" ng-click="ctrl.swapPuzzle($index)" ng-hide="$first"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                            Acertijo {{$index+1}}: <br />
                            <input ng-model="puzzle.name" class="form-control"/>
                            <br/>
                            Música: <br />
                            <input ng-model="puzzle.music" class="form-control"/>
                            <br/>
                            <button class="btn btn-block btn-xs" ng-click="ctrl.selectPuzzle($index)" data-toggle="modal" data-target="#modalFinish">Eliminar Acertijo</button>
                            <br/>
                            <button class="btn btn-xs" ng-click="ctrl.swapPuzzle($index+1)" ng-hide="$last"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-xs-3">
                            Descripción: <br />
                            <textarea ng-model="puzzle.description" class="form-control"></textarea>
                            Reset: <br />
                            <textarea ng-model="puzzle.reset" class="form-control"></textarea>
                        </div>
                        <div class="col-xs-6">
                            <div class="clearfix">
                                <button class="btn btn-xs pull-right" ng-click="ctrl.addClue($index);"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                <strong>Pistas:</strong> <br/>
                            </div>
                            <div ng-repeat="clue in puzzle.clues track by $index">
                                <button class="btn btn-xs pull-right" ng-click="ctrl.removeClue($parent.$index, $index);"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                Pista {{$index+1}}
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input class="form-control" ng-model="clue.es" />
                                    </div>
                                    <div class="col-xs-6">
                                        <input class="form-control" ng-model="clue.en" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button ng-click="ctrl.addPuzzle()" class="btn">Agregar acertijo</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div uib-alert ng-repeat="alert in ctrl.alerts" ng-class="'alert-' + (alert.type || 'success')" close="ctrl.closeAlert($index)" dismiss-on-timeout="3000">{{alert.msg}}</div>
        <button class="btn btn-primary" ng-click="ctrl.save()">Guardar</button>
    </div>

    <div class="modal fade" id="modalFinish" tabindex="-1" role="dialog" aria-labelledby="modalFinishLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    ¿Está seguro que desea finalizar el acertijo?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" ng-click="ctrl.removePuzzle()" data-dismiss="modal">Eliminar</button>
                </div>
            </div>
            <div class="modal fade" id="modalFinish" tabindex="-1" role="dialog" aria-labelledby="modalFinishLabel">
            </div>
        </div>
    </div>

</div>

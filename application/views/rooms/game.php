<style>
    .fixed-text {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 2017;
        font-size: 2em;
        background: black;
        text-align: center;
        right: 0;
        color: greenyellow;
        padding: 0 14px;
    }
    .lcd {
        font-family: 'LCD-Bold';
    }
</style>
<div ng-controller="GameController as ctrl" ng-init="ctrl.roomId = <?=$roomId; ?>">
    <div class="fixed-text giant-text" ng-show="ctrl.data.status == 2">
        <span class="lcd">{{ctrl.timePass | clock}}</span> + <span class="lcd">{{ctrl.tiempoPistas}}</span> + <span class="lcd">{{ctrl.data.punishment}}</span> => <span class="lcd">{{ctrl.timeLeft | clock}}</span>
    </div>
    <h1>{{ctrl.name}}</h1>
    <div class="row">
        <div class="col-sm-2">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="fa fa-info-circle" aria-hidden="true"></i> Info</div>
                <div class="panel-body">
                    <dl>
                        <dt>Cuarto:</dt><do>{{ctrl.data.room.name}}</do>
                        <dt>Equipo:</dt><do>{{ctrl.data.team_name}}</do>
                        <dt>Jugadores:</dt><do>{{ctrl.data.people}}</do>
                        <dt>Estado:</dt><do>{{ctrl.data.status}}</do>
                    </dl>
                    <button class="btn btn-info btn-block" data-toggle="modal" data-target="#modalComments" uib-tooltip="Editar comentarios sobre este grupo">Comentarios</button>
                </div>
            </div>
            <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalFinish" ng-if="ctrl.data.status == 2" uib-tooltip="Finalizar juego antes de tiempo">Finalizar Juego</button>
        </div>


        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="fa fa-clock-o" aria-hidden="true"></i> Tiempo</div>
                        <div class="panel-body">
                            <div class="row" ng-if="ctrl.data.status != 2">
                                <div class="col-xs-6 form-inline">
                                    <strong>Castigo:</strong>
                                    <input type="number" class="form-control" ng-model="ctrl.data.punishment" ng-change="ctrl.updatePunishment()"/> minutos

                                </div>
                                <div class="col-xs-6">
                                    <button class="btn btn-danger btn-block" ng-click="ctrl.startTime()">INICIAR TIEMPO</button>
                                </div>
                            </div>
                            <div class="row" ng-if="ctrl.data.status == 2">
                                <div class="col-xs-6 col-sm-3">
                                    <strong>Hora de inicio:</strong> {{ctrl.js_start | date:'mediumTime'}}<br />
                                    <strong>Hora de salida:</strong> {{ctrl.js_end | date:'mediumTime'}}<br />
                                    <strong>Transcurrido:</strong> {{ctrl.timePass | clock}}<br />
                                </div>
                                <div class="col-xs-6 col-sm-5 text-center lcd giant-text">
                                    {{ctrl.timeLeft | clock}}
                                </div>
                                <div class="col-xs-6 col-sm-3">
                                    <div class="input-group">
                                        <strong>Castigo:</strong>
                                        <input type="number" class="form-control" ng-model="ctrl.data.punishment" ng-change="ctrl.updatePunishment()"/>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped active" ng-class="{'progress-bar-warning': ctrl.percent > 50, 'progress-bar-danger': ctrl.percent > 75}" role="progressbar" aria-valuenow="{{ctrl.percent}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ctrl.percent}}%;">
                                            {{ctrl.percent}}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" ng-if="ctrl.data.status == 1">
                                <div class="col-sm-4">
                                    Juego finalizado
                                </div>
                                <div class="col-sm-4">
                                    <a type="button" class="btn btn-primary btn-block" href="/main/finish/<?= $roomId; ?>">Ir a pantalla de resultados</a>
                                    <a type="button" class="btn btn-primary btn-block" href="/main/archive/<?= $roomId; ?>">Archivar y volver al inicio</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" ng-show="ctrl.data.status == 2">
                <div class="col-sm-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="fa fa-key" aria-hidden="true"></i> Pistas disponibles</div>
                        <div class="panel-body noscroll-height">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Pista personalizada..." ng-model="customClue">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" ng-dblclick="ctrl.sendClue(customClue, false);" uib-tooltip="Enviar como comentario" tooltip-placement="auto bottom-left"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                    <button class="btn btn-primary" type="button" ng-dblclick="ctrl.sendClue(customClue, true);" uib-tooltip="Enviar como pista" tooltip-placement="auto bottom-left"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                </span>
                            </div>
                            <table class="table table-hover table-condensed ">
                                <tr ng-repeat="clue in ctrl.clues" ng-class="{'success': clue.sent}">
                                    <td>
                                        <button class="btn btn-default btn-xs" type="button" ng-dblclick="ctrl.copyClue(clue, false);" uib-tooltip="Editar antes de enviar" tooltip-placement="auto right"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
                                    </td>
                                    <td>{{clue.value}}</td>
                                    <td class="text-right">
                                        <button class="btn btn-default btn-xs" type="button" ng-click="ctrl.sendClue(clue, false);" uib-tooltip="Enviar como comentario" tooltip-placement="auto left"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                        <button ng-hide="clue.sent" class="btn btn-primary btn-xs" type="button" ng-dblclick="ctrl.sendClue(clue, true);" ng-disabled="clue.sent"  uib-tooltip="Enviar como pista" tooltip-placement="auto left"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="fa fa-key" aria-hidden="true"></i> Pistas enviadas: {{ctrl.data.total_clues}}</div>
                        <div class="panel-body scroll-height">
                            <table class="table table-hover table-condensed ">
                                <tr>
                                    <th width="20%">Hora</th>
                                    <th>Pista</th>
                                </tr>
                                <tr ng-repeat="clue in ctrl.cluesSent track by $index">
                                    <td>{{clue.date | date:'HH:mm:ss'}}</td>
                                    <td>
                                        <i class="fa fa-key pull-right" aria-hidden="true" ng-if="clue.counts"></i>
                                        {{clue.value}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="fa fa-lock" aria-hidden="true"></i> Acertijos</div>
                        <div class="panel-body">
                            Progreso: {{ctrl.data.progress}}
                            <!--div class="checkbox">
<label for="progreso"><input type="checkbox" ng-model="ctrl.data.show_progress" ng-click="ctrl.updateBackend(['show_progress'])" id="progreso" name="progreso" /> Mostrar progreso</label>
</div -->
                            <table class="table table-hover table-condensed "  ng-show="ctrl.data.status == 2">
                                <tr ng-repeat="puzzle in ctrl.puzzles track by $index" ng-class="{success: ($index+1==ctrl.data.progress)}">
                                    <td>{{$index+1}}</td>
                                    <td>{{puzzle.name}}</td>
                                    <td class="text-right">
                                        <button class="btn btn-primary btn-xs" ng-click="ctrl.togglePuz($index)" ng-if="!puzzle.complete" uib-tooltip="Marcar como completo" tooltip-placement="auto left"><i class="fa fa-check" aria-hidden="true"></i></button>
                                        <button class="btn btn-default btn-xs" ng-click="ctrl.togglePuz($index)" ng-if="puzzle.complete" uib-tooltip="Desmarcar" tooltip-placement="auto left"><i class="fa fa-undo" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-sm-6">
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


            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-info" ng-if="ctrl.data.status != null">
                        <div class="panel-heading"><i class="fa fa-tachometer" aria-hidden="true"></i> Otros</div>
                        <div class="panel-body">
                            <label for="fFreeClues">Pistas grátis:</label>
                            <input type="number" id="fFreeClues" class="form-control" ng-model="ctrl.data.free_clues"  ng-change="ctrl.updateClueTimes()"/>
                            <br/>
                            <label for="fMinutesperclue">Minutos por pista:</label>
                            <input type="number" id="fMinutesperclue" class="form-control" ng-model="ctrl.data.minutesxclue" ng-change="ctrl.updateClueTimes()" />
                            <br/>
                            <label for="fMinutesperclue">Idioma:</label>
                            <select class="form-control" id="fLanguage" ng-model="ctrl.data.language"  ng-change="ctrl.changeLang()">
                                <option value="es">Español</option>
                                <option value="en">Inglés</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="fa fa-video-camera" aria-hidden="true"></i> Ver</div>
                        <div class="panel-body">
                            <a href="https://user.zmodo.com/device/list" target="_blank">Cámaras ZMODO</a><br/>
                            <a href="/room/<?=$roomId; ?>" target="visor">Vista de jugadores</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalFinish" tabindex="-1" role="dialog" aria-labelledby="modalFinishLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        ¿Está seguro que desea finalizar el juego?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <a type="button" class="btn btn-danger" href="/main/finish/<?= $roomId; ?>">Finalizar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalComments" tabindex="-1" role="dialog" aria-labelledby="modalCommentsLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <textarea class="form-control" ng-model="ctrl.data.comments" ng-blur="ctrl.updateBackend(['comments'])"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

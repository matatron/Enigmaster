<div ng-controller="PostgameController as ctrl" ng-init="ctrl.groupId = <?=$group->id; ?>">
    <h1>Información post-juego</h1>
    <a href="/main/archive/<?=$group->room->id; ?>" class="btn btn-primary btn-large btn-block">Archivar</a>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <h4>Fecha: {{<?= $group->start*1000; ?> | date:'short'}}</h4>
            <div class="well">
                <h3>Equipo: <?= $group->team_name; ?></h3>
                <h3>Tiempo: <span class="lcd">{{<?= $group->time*1000; ?> | date:'HH:mm:ss': 'UTC'}}</span></h3>
                <h3>Pistas: <?= $group->total_clues; ?></h3>
                <h3>Juego: <?= $group->room->name; ?></h3>
                <h3>Personas: <?= $group->people; ?></h3>
            </div>
            <h4 class="">Acertijos</h4>
            <table class="table">
                <tr>
                    <th>Nombre</th>
                    <th>Resuelto en</th>
                </tr>
<?php $puzzles =  json_decode($group->puzzles);
foreach($puzzles as $puzz) {
if (!$puzz->solvedAt) $puzz->solvedAt = $group->end;
?>
                <tr>
                    <td><?=$puzz->name; ?></td>
                    <td>{{<?=($puzz->solvedAt-$group->start)*1000; ?> | date:'HH:mm:ss': 'UTC'}}</td>
                </tr>
<?php
}
?>
            </table>
    </div>
    <div class="col-xs-12 col-sm-6">
        <form>
            <h4 class="">Comentarios</h4>
            <textarea id="comments" class="form-control" ng-blur="ctrl.save()" style="height: 400px;"><?= $group->comments; ?></textarea>
        </form>
    </div>
</div>
</div>

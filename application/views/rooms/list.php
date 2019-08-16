<h1>Cuartos</h1>
<div class="row">
    <?php foreach($rooms as $room) { ?>
    <div class="col-xs-12 col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control" readonly value="<?=$room->name; ?>">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="<?= URL::site('cuarto/modificar/'.$room->id); ?>"><i class="fa fa-search" aria-hidden="true"></i> Administrar pistas</a></li>
                    <li><a href="<?= URL::site('cuarto/statistics/'.$room->id); ?>"><i class="fa fa-list" aria-hidden="true"></i> Ver estadísticas</a></li>
                </ul>
            </div>
        </div>
        <?php	if ($room->group->loaded()) {

    switch($room->group->status) {
        case 3: //reseted
        ?>
        <div class="panel panel-info">
            <div class="panel-heading"><strong>Status:</strong> Listo para empezar</div>
            <div class="panel-body">
                <a class="btn btn-block btn-info" href="<?= URL::site('main/pregame/'.$room->id); ?>">Info preliminar</a>
                <a class="btn btn-block btn-warning" href="<?= URL::site('main/archive/'.$room->id); ?>">Eliminar</a>
                <a class="btn btn-block btn-default" href="<?= URL::site('playerview/lasttime/'.$room->id); ?>">Ver Ultimo Tiempo</a>
            </div>
        </div>
        <?php
            break;
        case 2: //onplay
            $punish = max(0, $room->group->total_clues-$room->group->free_clues)*$room->group->minutesxclue + $room->group->punishment;
            $percent = ($room->group->start > 0) ? round((time() - $room->group->start + $punish*60 )/(3600)*100)%100 : 0;
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Status:</strong> Juego en marcha<br /></div>
            <div class="panel-body">
                <a class="btn btn-block btn-default" href="<?= URL::site('main/game/'.$room->id); ?>">Controlar juego</a>

                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?=$percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent; ?>%;">
                        <?=$percent; ?>%
                    </div>
                </div>
                <strong>Hora de inicio:</strong> <?php echo date('h:i:s A', $room->group->start); ?><br />
                <strong>Hora de salida:</strong> <?php echo date('h:i:s A', $room->group->end); ?><br />
                <strong>Pistas:</strong> <?= $room->group->total_clues; ?><br />
                <strong>Personas:</strong> <?= $room->group->people; ?><br />

            </div>
        </div>
        <?php
            break;
        case 1: //reseted
        ?>
        <div class="panel panel-info">
            <div class="panel-heading"><strong>Status:</strong> Finalizado</div>
            <div class="panel-body">
                <a class="btn btn-block btn-default" href="<?= URL::site('main/archive/'.$room->id); ?>">Archivar juego</a>
                <a class="btn btn-block btn-default" href="<?= URL::site('playerview/lasttime/'.$room->id); ?>">Ver Ultimo Tiempo</a>
            </div>
        </div>
        <?php
            break;
    }
} else {?>
        <div class="panel panel-danger">
            <div class="panel-heading"><strong>Status:</strong> Cuarto esperando reset</div>
            <div class="panel-body">
                <a class="btn btn-block btn-info" href="<?= URL::site('cuarto/ready/'.$room->id); ?>">Iniciar nuevo juego</a>
                <a class="btn btn-block btn-default" href="<?= URL::site('cuarto/checklist/'.$room->id); ?>">Ver checklist</a>
                <a class="btn btn-block btn-default" href="<?= URL::site('playerview/lasttime/'.$room->id); ?>">Ver Ultimo Tiempo</a>
            </div>
        </div>
        <?php } ?>

    </div>
    <?php } ?>
</div>

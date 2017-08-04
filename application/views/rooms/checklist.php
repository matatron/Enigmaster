<h1><?=$room->name;?></h1>
<br />
<br />
<br />
<?php
	if ($room->puzzles) {
		foreach(json_decode($room->puzzles) as $puzzle) { ?>
	<div class="clearfix checklist-item">
		<div class="pull-right">
		<input type="checkbox" data-toggle="toggle" data-on="Listo" data-off="No Listo" data-onstyle="success" data-offstyle="danger" >
		</div>
		<li class="fa fa-chevron-right"></li>
		<strong>
		<?=$puzzle->reset; ?>
		</strong>
	</div>
<?php }
	} else {  ?><h2>No hay tareas pendientes</h2><?php
	}?>
<br />
<a class="btn btn-default pull-right" href="<?= URL::site('cuarto/ready/'.$room->id); ?>">Marcar como listo</a>


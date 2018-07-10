<br/>
<br/>
<div><?= $group->team_name; ?></div>
<div style="font-size: 2em;">
<div class="lcd giant-text"><?php
    $minutos = floor($group->time/60);
    $segundos = $group->time%60;
    echo $minutos.":".$segundos;
?></div>
</div>
<br/>
<br/>

<script>
    setTimeout(function(){
        window.location.reload(1);
    }, 60000);
</script>
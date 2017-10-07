<style>
</style>
<script>
    window.bgsound = "cueva1.mp3";
</script>
<div ng-if="data.status == 2">
    <div class="well font-diogenes">
        ¿LISTOS PARA EMPEZAR?
    </div>
</div>
<div ng-if="data.status == 1">
    <div class="lcd giant-text">
        {{timeLeft}}
    </div>
    <div class="">
        <i class="fa fa-key" ng-repeat="n in pistas"></i> <span ng-if="pistas.length>0" class="lcd">= {{pistas.length*3}}:00</span>
    </div>
    <div class="font-diogenes" style="width: 60%; margin: 0 auto;">
        {{clue}}
    </div>
</div>
<div ng-if="data.status == 0">
    <div class="lcd giant-text">
        {{data.time | date : 'HH:mm:ss' : 'UTC'}}
    </div>
    <div class="well font-diogenes">
        GAME OVER!
    </div>
</div>

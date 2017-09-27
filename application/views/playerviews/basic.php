<style>
</style>
<script>
    window.bgsound = "cueva1.mp3";
</script>
<div ng-if="data.status == 2">
    <div class="well">
        ¿LISTOS PARA EMPEZAR?
    </div>
</div>
<div ng-if="data.status == 1">
    <div class="lcd giant-text">
        {{timePass}}
    </div>
    <div class="medium-text">
        <i class="fa fa-key" ng-repeat="n in pistas"></i>
    </div>
    <div class="medium-text font-diogenes" style="width: 60%; margin: 0 auto;">
        {{clue}}
    </div>
</div>
<div ng-if="data.status == 0">
    <div class="lcd giant-text">
        {{data.time | date : 'HH:mm:ss' : 'UTC'}}
    </div>
    <div class="well">
        GAME OVER!
    </div>
</div>

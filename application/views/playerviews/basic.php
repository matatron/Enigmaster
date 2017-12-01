<style>
</style>
<div ng-if="data.status == 3">
    <div class="well font-diogenes">
        ¿LISTOS PARA EMPEZAR?
    </div>
</div>
<div ng-if="data.status == 2">
    <div class="lcd giant-text">
        {{timeLeft | clock}}
    </div>
    <div class="small-text">
        Pistas usadas: 
        <i class="fa fa-key" ng-repeat="n in pistas"></i> <span ng-if="pistas.length>0" class="lcd">= {{punishment}}:00</span>
    </div>
    <div class="lcd small-text">
        {{timeLeft-punishment*60000 | clock}}
    </div>
    <div class="font-diogenes" style="width: 60%; margin: 0 auto;">
        {{clue}}
    </div>
</div>
<div ng-if="data.status == 1">
    <div class="lcd giant-text">
        {{data.time | clock}}
    </div>
    <div class="well font-diogenes">
        GAME OVER!
    </div>
</div>

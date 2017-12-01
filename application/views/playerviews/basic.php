<style>
    .flame {
        height: 100px;
    }
</style>
<div ng-if="data.status == 3">
    <div class="well font-diogenes">
        ¿LISTOS PARA EMPEZAR?
    </div>
</div>
<div ng-if="data.status == 2">
    <div style="position: absolute; left:0; top:0">
        <div class="small-text">
            Pistas usadas: 
            <i class="fa fa-key" ng-repeat="n in pistas"></i> <span ng-if="pistas.length>0" class="lcd">= {{punishment}}:00</span>
        </div>
        <div class="small-text">
            Tiempo transcurrido: <span class="lcd ">{{timePass | clock}}</span>
        </div>
    </div>
    <div class="lcd giant-text">
        {{timeLeft-punishment*60000 | clock}}
    </div>
    <div>
        <img src="/assets/images/flama.webp" ng-repeat="n in counters" class="flame">
    </div>
    <div class="font-diogenes" style="margin: 0 auto;">
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

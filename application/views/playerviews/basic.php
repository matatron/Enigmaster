<div ng-controller="PlayerviewController" ng-init="roomId = <?=$roomId; ?>" >
    <style>
        .flame {
            height: 150px;
            margin: -20px -19px;
        }
    </style>
    <div ng-if="data.status == 3">
        <div class="well font-diogenes">
            ¿LISTOS PARA EMPEZAR?
        </div>
    </div>
    <div ng-if="data.status == 2">
        <div class="lcd giant-text">
            {{timePass | clock}}
        </div>
        <div class="small-text">
            Pistas usadas:
            <i class="fa fa-key" ng-repeat="n in pistas"></i>
            <span ng-if="pistas.length>0" class="lcd">= {{punishment}}:00</span>
            <span ng-if="pistas.length==0">0</span>
        </div>
        <div class="small-text">
            Tiempo total: <span class="lcd ">{{timePass+punishment*60000 | clock}}</span>
        </div>
        <div class="small-text">
            Tiempo restante: <span class="lcd ">{{timeLeft-punishment*60000 | clock}}</span>
        </div>
        <div class="font-diogenes" style="margin: 0 auto;">
            {{clue}}
        </div>
    </div>
    <div ng-if="data.status == 1">
        <div class="font-diogenes">
            Tiempo Final:
        </div>
        <div class="lcd giant-text">
            {{data.time | clock}}
        </div>
    </div>
    <div ng-if="data.status == 0">
        <div class="font-diogenes">
            ENIGMATA
        </div>
    </div>
</div>

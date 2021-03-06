<div ng-controller="PlayerviewController" ng-init="roomId = <?=$roomId; ?>" >
    <style>
        .flame {
            height: 150px;
            margin: -20px -19px;
        }
        .animacion {
            position: absolute;
            bottom: -61px;
            left: 40%;
        }
        .animacion img{
            height: 450px;
        }
        .cuadroblanco {
            position: absolute;
            bottom: 8px;
            left: 43.6%;
            height: 106px;
            width: 172px;
            background-color: white;
            border-radius: 8px;
        }
    </style>
    <div ng-if="data.status == 3">
      <div class="well font-diogenes">
        <div>{{data.team_name}}</div>
            <span ng-if="data.lang == 'es'">¿LISTOS PARA EMPEZAR?</span>
            <span ng-if="data.lang == 'en'">¿READY TO START?</span>
        </div>
    </div>
    <div ng-if="data.status == 2">
      <div class="well font-diogenes">{{data.team_name}}</div>
        <div class="lcd giant-text">
            {{timePass | clock}}
        </div>
        <div class="small-text">
            <span ng-if="data.lang == 'es'">Pistas usadas:</span>
            <span ng-if="data.lang == 'en'">Clues used:</span>

            <i class="fa fa-key" ng-repeat="n in pistas"></i>
            <span ng-if="pistas.length>0" class="lcd">= {{punishment}}:00</span>
            <span ng-if="pistas.length==0">0</span>
        </div>
        <div class="small-text">
            <span ng-if="data.lang == 'es'">Tiempo total:</span>
            <span ng-if="data.lang == 'en'">Total time:</span>
            <span class="lcd ">{{timePass+punishment*60000 | clock}}</span>
        </div>
        <div class="small-text">
            <span ng-if="data.lang == 'es'">Tiempo restante:</span>
            <span ng-if="data.lang == 'en'">Time left:</span>
            <span class="lcd ">{{timeLeft-punishment*60000 | clock}}</span>
        </div>
        <div class="font-diogenes" style="margin: 0 auto;">
            {{clue}}
        </div>
    </div>
    <div ng-if="data.status == 1">
        <div class="font-diogenes">
            <span ng-if="data.lang == 'es'">Tiempo Final (con pistas):</span>
            <span ng-if="data.lang == 'en'">Final time (clues added):</span>

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
    <div class="flash"></div>
    <div class="cuadroblanco"></div>
</div>

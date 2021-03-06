<div ng-controller="PlayerviewController" ng-init="roomId = <?=$roomId; ?>" >
    <div ng-if="data.status == 3">
        <div class="well font-bloodcrow">
            <div>{{data.team_name}}</div>
            <span ng-if="data.lang == 'es'">¿LISTOS PARA EMPEZAR?</span>
            <span ng-if="data.lang == 'en'">¿READY TO START?</span>
        </div>
    </div>
    <div ng-if="data.status == 2">
      <div class="well font-bloodcrow">{{data.team_name}}</div>
        <div class="lcd giant-text">
            {{timePass+39600000 | clock}} pm
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
        <div class="font-bloodcrow" style="margin: 0 auto;">
            {{clue}}
        </div>
    </div>
    <div ng-if="data.status == 1">
        <div class="font-bloodcrow">
            <span ng-if="data.lang == 'es'">Tiempo Final (con pistas):</span>
            <span ng-if="data.lang == 'en'">Final time (clues added):</span>

        </div>
        <div class="lcd giant-text">
            {{data.time | clock}}
        </div>
    </div>
    <div ng-if="data.status == 0">
        <div class="font-bloodcrow">
            ENIGMATA
        </div>
    </div>
    <div class="flash"></div>
</div>


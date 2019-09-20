<style>
    html {
        height: 100%;
    }
    body {
        height: 100%;
        margin: 0 !important;
        background-size: contain;
        background-color: black;
        background-image: url(/assets/images/hexagons.jpg);
    }
</style>
<div ng-controller="PlayerviewControllerPi3" class="spacecontainer" ng-init="roomId = <?=$roomId; ?>" >
    <div class="text"></div>
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2 || data.status == 1">
        <div ng-if="currentPuzzles[17]==0">
            <div class="futurepanel danger">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        <span ng-if="data.lang == 'es'">Advertencia:</span>
                    </h1>
                </div>
                <div class="futurepanel__body">
                    <div>Ubicación desconocida.</div>
                    <div>Activar Giroscopio Espacial</div> 
                </div>
            </div> 

        </div>
        <div ng-if="currentPuzzles[19]==0">
            <div class="futurepanel danger">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        <span ng-if="data.lang == 'es'">Advertencia:</span>
                    </h1>
                </div>
                <div class="futurepanel__body">
                    <div>Usuario no autorizado</div>
                    <div>Coloque mano en el identificador</div> 
                </div>
            </div> 
        </div>
        <div ng-if="currentPuzzles[19]==0">
            <div class="futurepanel danger">
                <div class="futurepanel__body">
                    <div>Utilice los botones blancos para controlar la nave</div>
                </div>
            </div> 
        </div>
        <div ng-if="currentPuzzles[17] == 1 && currentPuzzles[19] == 1 && !missionCompleted" class="dir{{direccion}}">
            <div class="space">
                <img id="planeta" src="/assets/images/planetas/planeta0.png" />
            </div>
            <div class="spaceship"></div>
            <div class="fuelContainer">
                <div ng-repeat="cell in fuel track by $index" class="fuel" ng-class="{'full':($index < generado - consumido)}"></div>
            </div>
            <div class="o2Container">
                <div class="o2">O<sub>2</sub></div>
            </div>
            <div class="arrowLeft"><img src="/assets/images/arrowLeft.png" /></div>
            <div class="arrowRight"><img src="/assets/images/arrowRight.png" /></div>
            <div class="arrowFront"  ng-show="validFront"><img src="/assets/images/arrowFront.png" /></div>
            <div class="currentLocation"><span class="small">POSICIÓN ACTUAL</span>{{currentLocation()}}</div>

            <div class="next0" ng-show="valid0">{{location0}}</div>
            <div class="next1" ng-show="valid1">{{location1}}</div>
            <div class="next2" ng-show="valid2">{{location2}}</div>
            <div class="next3" ng-show="valid3">{{location3}}</div>
        </div>
        <div ng-if="missionCompleted">
            ¡BIENVENIDOS A LA TIERRA!<br/>
            Fin del juego

        </div>
        <video id="tvvideo" autoplay></video>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

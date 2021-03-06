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
    .fuelMessage {
        position: absolute;
        top: 32px;
        text-transform: uppercase;
        font: normal 100 15px/1.2em "Exo 2", Arial, sans-serif;
        color: red;
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
                        <span ng-if="data.lang == 'en'">Warning:</span>
                    </h1>
                </div>
                <div class="futurepanel__body" ng-if="data.lang == 'es'">
                    <div>Ubicación desconocida.</div>
                    <div>Activar Giroscopio Espacial</div> 
                </div>
                <div class="futurepanel__body" ng-if="data.lang == 'en'">
                    <div>Location Unknown.</div>
                    <div>Activate Space Gyroscope</div> 
                </div>
            </div> 

        </div>
        <div ng-if="currentPuzzles[19]==0">
            <div class="futurepanel danger">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        <span ng-if="data.lang == 'es'">Advertencia:</span>
                        <span ng-if="data.lang == 'en'">Warning:</span>
                    </h1>
                </div>
                <div class="futurepanel__body" ng-if="data.lang == 'es'">
                    <div>Usuario no autorizado</div>
                    <div>Coloque mano autorizada en el identificador</div> 
                </div>
                <div class="futurepanel__body" ng-if="data.lang == 'en'">
                    <div>Unauthorized user</div>
                    <div>Place authorized hand on identifier</div> 
                </div>
            </div> 
        </div>
        <div ng-if="currentPuzzles[19]==0">
            <div class="futurepanel danger">
                <div class="futurepanel__body">
                    <span ng-if="data.lang == 'es'">Utilice los botones blancos para controlar la nave</span>
                    <span ng-if="data.lang == 'en'">Use the white buttons to control the ship</span>
                </div>
            </div> 
        </div>
        <div ng-if="currentPuzzles[17] == 1 && currentPuzzles[19] == 1 && !missionCompleted" class="dir{{direccion}}">
            <div class="space">
                <img id="planeta" src="/assets/images/planetas/planeta0.png" />
            </div>
            <div class="spaceship"></div>
            <div class="fuelContainer">
                <div ng-repeat="cell in fuel track by $index" class="fuel" ng-class="{'full':($index < combustible)}"></div>
            </div>
            <div class="fuelMessage" ng-show="combustible<5">
                <span ng-if="data.lang == 'es'">Recargar mas combustible</span>
                <span ng-if="data.lang == 'en'">Synth more fuel</span>
            </div>
            <div class="o2Container">
                <div class="o2">O<sub>2</sub></div>
            </div>
            <div class="arrowLeft"><img src="/assets/images/arrowLeft.png" /></div>
            <div class="arrowRight"><img src="/assets/images/arrowRight.png" /></div>
            <div class="arrowFront"  ng-show="validFront"><img src="/assets/images/arrowFront.png" /></div>
            <div class="currentLocation">
                <span class="small" ng-if="data.lang == 'es'">POSICIÓN ACTUAL</span>
                <span class="small" ng-if="data.lang == 'en'">CURRENT LOCATION</span>
                {{currentLocation}}
            </div>

            <div class="next0" ng-show="valid0">{{location0}}</div>
            <div class="next1" ng-show="valid1">{{location1}}</div>
            <div class="next2" ng-show="valid2">{{location2}}</div>
            <div class="next3" ng-show="valid3">{{location3}}</div>
        </div>
        <div ng-if="missionCompleted">
            <p ng-if="data.lang == 'es'">
                ¡BIENVENIDOS A LA TIERRA!<br/>
                {{data.team_name}}<br/>
                Fin del juego<br/>
            </p>
            <p ng-if="data.lang == 'en'">
                ¡WELCOME BACK TO EARTH!<br/>
                {{data.team_name}}<br/>
                Game Over<br/>
            </p>

        </div>
        <video id="tvvideo" autoplay></video>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

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
    .container {
        width: 800px;
        height: 600px;
        overflow: hidden;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
<div ng-controller="PlayerviewControllerPi3" ng-init="roomId = <?=$roomId; ?>" >
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
                    <div>Usuario no reconocido</div>
                    <div>Coloque su mano en el identificador</div> 
                </div>
            </div> 
        </div>
        <div class="container" ng-if="currentPuzzles[17] == 1 && currentPuzzles[19] == 1">
            <div class="space"></div>
            <video id="tvvideo" autoplay></video>
            <div class="spaceship"></div>
            <div class="arrowLeft"><img src="/assets/images/arrowLeft.png" /></div>
            <div class="arrowRight"><img src="/assets/images/arrowRight.png" /></div>
            <div class="arrowFront"  ng-show="validFront"><img src="/assets/images/arrowFront.png" /></div>
            <div class="currentLocation">{{currentLocation()}}</div>
            <div class="nextLocation" ng-show="validFront">{{nextLocation()}}</div>
            <div class="leftLocation" ng-show="validLeft">{{leftLocation}}</div>
            <div class="rightLocation" ng-show="validRight">{{rightLocation}}</div>
        </div>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

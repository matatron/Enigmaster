<style>
    html {
        height: 100%;
    }
    body {
        height: 100%;
        margin: 0 !important;
        background-color: black;
        color: white;
        font-family: "Exo 2";
    }
</style>
<div ng-controller="PlayerviewControllerPi1" ng-init="roomId = <?=$roomId; ?>" >
    <audio id="alarma">
        <source src="/assets/audio/alarma.mp3">
    </audio>
    <div ng-show="data.status == 3">
        &#128077;
    </div>
    <div ng-show="data.status == 2 || data.status == 1" ng-class="{'isAlien': isAlien}">
        <div class="" ng-show="screen==''">
            <div class="alien" style="color: red; margin: 3em auto;">ALERTA</div>
        </div>
        <div class="pi1" ng-show="screen=='cluesInfo'">
            <video id="tvvideo" autoplay></video>
            <div class="O2timer">
                <div class="futurepanel danger">
                    <div class="futurepanel__header">
                        <h1 class="futurepanel__title">
                            <span ng-if="data.lang == 'es'">Oxígeno:</span>
                            <span ng-if="data.lang == 'en'">Oxigen:</span>
                        </h1>
                    </div>
                    <div class="futurepanel__body">
                        <div class="futuremetric futuremetric--circle">
                            <div class="futuremetric__label">
                                <span ng-if="data.lang == 'es'">Restante</span>
                                <span ng-if="data.lang == 'en'">Remaining</span>
                            </div>
                            <div class="futuremetric__value lcd">{{timeLeft | percent}}</div>
                        </div>
                        <br/>
                        <br/>
                        <br/>
                        <h6 class="heading">
                            <span ng-if="data.lang == 'es'">Tiempo restante:</span>
                            <span ng-if="data.lang == 'en'">Time left:</span>
                        </h6>
                        <h2 class="lcd"><span>{{timeLeft | clock}}</span></h2>
                    </div>
                </div> 
            </div>
            <div class="moscovium">
                <div class="futurepanel">
                    <div class="futurepanel__header">
                        <h1 class="futurepanel__title">
                            <span ng-if="data.lang == 'es'">Pistas usadas:</span>
                            <span ng-if="data.lang == 'en'">Clues used:</span>
                        </h1>
                    </div>
                    <div class="futurepanel__body">
                        <i class="fa fa-key" ng-repeat="n in pistas"></i>
                        <span ng-if="pistas.length>0" class="lcd">= {{punishment}}:00</span>
                        <span ng-if="pistas.length==0">0</span>

                    </div>
                </div> 

            </div>
            <div class="clues">
                <div class="futurepanel">
                    <div class="futurepanel__header">
                        <h1 class="futurepanel__title">
                            <span ng-if="data.lang == 'es'">Misión:</span>
                            <span ng-if="data.lang == 'en'">Mission:</span>
                            {{data.team_name}}
                        </h1>
                    </div>
                    <div class="futurepanel__body cluebody">
                        {{clue}}
                    </div>
                </div>  

            </div>
        </div>
    </div>
    <div ng-show="data.status == 1">
        Fin del juego
    </div>
    <div ng-show="data.status == 0">
        Modo Reset. No hay juego iniciado
    </div>
</div>

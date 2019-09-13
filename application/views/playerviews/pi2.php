<style>
    html {
        height: 100%;
    }
    body {
        height: 100%;
        margin: 0 !important;
        background-color: black;
    }
</style>
<div ng-controller="PlayerviewControllerPi2" ng-init="roomId = <?=$roomId; ?>" >
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2 || data.status == 1">
        <div id="pi2" ng-if="screen=='menuHex'">
            <div class="hexagonBig"></div>
            <div class="section1 futurepanel" ng-show="section=='section1'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Orden Pendiente
                    </h1>
                </div>
                <div class="futurepanel__body">
                    <div>Planeta C4: Listo</div>
                    <div>Planeta E3: Listo</div>
                    <div>Planeta F2: Listo</div>
                    <div>Planeta E1: Listo</div>
                    <div>Misión Completa</div>
                    <div>Volver al planeta hogar</div>
                </div>
            </div>
            <div class="section2 futurepanel" ng-show="section=='section2'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Estado de la Nave
                    </h1>
                </div>
                <div class="futurepanel__body">
                    <div class="">
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

                </div>
            </div>
            <div class="section3 futurepanel" ng-show="section=='section3'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Abrir Puerta al Centro de Mando
                    </h1>
                </div>
                <div class="futurepanel__body">
                </div>
            </div>
            <div class="section4 futurepanel" ng-show="section=='section4'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Receta correcta del combustible
                    </h1>
                </div>
                <div class="futurepanel__body" style="font-size: 3em; line-height: 1.5em">
                    <div>C2 + C3 + C4 = 15</div>
                    <div>C3 + C4 = 11</div>
                    <div>C1 + C2 = 14</div>
                    <div>C1 + C2 = 10</div>
                </div>
            </div>
            <div class="section5 futurepanel" ng-show="section=='section5'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Pistas
                    </h1>
                </div>
                <div class="futurepanel__body">
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
                    <div class="futurepanel__body cluebody">
                        {{clue}}
                    </div>

                </div>
            </div>
            <div class="section6 futurepanel" ng-show="section=='section6'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Publicidad de Andromeda
                    </h1>
                </div>
                <div class="futurepanel__body">
                    <video id="videoAndromeda" autoplay></video>
                </div>
            </div>
        </div>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

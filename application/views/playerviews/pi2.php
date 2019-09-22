<style>
    html {
        height: 100%;
    }
    body {
        height: 100%;
        margin: 0 !important;
        background-color: black;
    }
    .colorX {
        color: blue;
    }
    .colorY {
        color: green;
    }
    .colorZ {
        color: yellow;
    }
    .colorW {
        color: red;
    }
    .sum {
        background-color: black;
        color: white;
        letter-spacing: 12px;
    }
    .third {
        display: inline-block;
        width: 30%;
        border: solid 1px #215d91;
        height: 300px;
        vertical-align: top;
        margin: 18px 0;
    }
    .third img {
        display: block;
        margin: 0 auto;
        width: 70%;
    }
    .half {
        opacity: 0.5;
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
                    <div>Planeta C-5: Visitado</div>
                    <div>Planeta C-4: Visitado</div>
                    <div>Planeta E-3: Visitado</div>
                    <div>Planeta D-2: Visitado</div>
                    <div>Planeta E-1: Visitado</div>
                    <div>Misión Completa</div>
                    <div style="color: red">Volver al planeta hogar</div>
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
                    <div>
                        Combustible: {{combustible}} / 18
                    </div>
                    <div>
                        <div ng-repeat="i in celdas track by $index" class="Cell" ng-class="{'filled': $index<combustible}">
                        </div>

                    </div>
                    <div ng-if="combustible < 18">
                        <div>Error: no hay combustible suficiente para esta operación</div>
                        <div class="third" ng-class="{'half': currentPuzzles[14] == 1}">Ajustar ingredientes a su valor correcto
                            <img src="/assets/images/diagram1.png" width="100%" />
                        </div>
                        <div class="third">Mezclar ingredientes<br/>(botón verde)
                            <img src="/assets/images/diagram2.png" width="100%" />
                        </div>
                        <div class="third">
                            Abrir valvulas de paso al tanque
                            <img src="/assets/images/diagram3.png" width="100%" />
                        </div>
                        <div>Recuerde esperar a que termine la mezcla antes de abrir las valvulas</div>
                    </div>
                    <div ng-if="combustible >= 18">
                        Acceso al Centro de Mando autorizado
                    </div>
                </div>
            </div>
            <div class="section4 futurepanel" ng-show="section=='section4'">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">
                        Receta correcta del combustible
                    </h1>
                </div>
                <div class="futurepanel__body" style="font-size: 3em; line-height: 1.5em">
                    <div><span class="alien colorY">Y</span> + <span class="alien colorZ">Z</span> + <span class="alien colorW">W</span> = <span class="alien sum">E</span></div>
                    <div><span class="alien colorZ">Z</span> + <span class="alien colorW">W</span> = <span class="alien sum">O</span></div>
                    <div><span class="alien colorX">X</span> + <span class="alien colorZ">Z</span> = <span class="alien sum">M</span></div>
                    <div><span class="alien colorX">X</span> + <span class="alien colorY">Y</span> = <span class="alien sum">U</span></div>
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

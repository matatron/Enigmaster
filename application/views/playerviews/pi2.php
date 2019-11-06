<style>
    html {
        height: 100%;
    }
    body {
        font-family: "Exo 2", Arial, sans-serif;
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
    .authorizado {
        padding-top: 3em;
        font-size: 2em;
    }

    .hex {
        background-image: url(/assets/images/hex.png);
        width: 220px;
        height: 190px;
        position: absolute;
        transition: transform 0.5s;
        cursor: pointer;
        font-family: 'Alien';
        font-size: 50px;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        -webkit-tap-highlight-color: transparent;
    }



    .lado {
        position: absolute;
        width: 50px;
        height: 50px;
        text-align: center;
        pointer-events: none;
        color: black;
    }

    .l1 {
        bottom: 1px;
        left: 85px;
    }
    .l2 {
        transform: rotate(-60deg);
        top: 103px;
        left: 146px;
    }
    .l3 {
        transform: rotate(-120deg);
        top: 35px;
        left: 145px;
    }
    .l4 {
        transform: rotate(180deg);
        top: 0px;
        left: 85px;
    }
    .l5 {
        transform: rotate(120deg);
        top: 36px;
        left: 25px;
    }
    .l6 {
        transform: rotate(60deg);
        top: 105px;
        left: 25px;
    }

    .h1 {
        top: 50px;
        left: 175px;
    }
    .h2 {
        top: 150px;
        left: 350px;
    }
    .h3 {
        top: 350px;
        left: 350px;
    }
    .h4 {
        top: 450px;
        left: 175px;
    }
    .h5 {
        top: 350px;
        left: 0px;
    }
    .h6 {
        top: 150px;
        left: 0px;
    }
</style>
<div id="pi2" ng-controller="PlayerviewControllerPi2" ng-init="roomId = <?=$roomId; ?>" >
    <div ng-show="data.status == 3">
    </div>
    <div ng-show="data.status == 2 || data.status == 1">
        <div ng-show="screen == 'hexagons'"  style="transform: rotate(30deg) scale(1.5) translate(860px, 0px)">
            <div class="hex h1">
                <div class="lado l1">H</div>
                <div class="lado l2">A</div>
                <div class="lado l3">S</div>
                <div class="lado l4">T</div>
                <div class="lado l5">D</div>
                <div class="lado l6">V</div>
            </div>

            <div class="hex h2">
                <div class="lado l1">H</div>
                <div class="lado l2">D</div>
                <div class="lado l3">V</div>
                <div class="lado l4">T</div>
                <div class="lado l5">A</div>
                <div class="lado l6">S</div>
            </div>

            <div class="hex h3">
                <div class="lado l1">S</div>
                <div class="lado l2">A</div>
                <div class="lado l3">T</div>
                <div class="lado l4">H</div>
                <div class="lado l5">V</div>
                <div class="lado l6">D</div>
            </div>

            <div class="hex h4">
                <div class="lado l1">H</div>
                <div class="lado l2">V</div>
                <div class="lado l3">D</div>
                <div class="lado l4">A</div>
                <div class="lado l5">T</div>
                <div class="lado l6">S</div>
            </div>

            <div class="hex h5">
                <div class="lado l1">V</div>
                <div class="lado l2">T</div>
                <div class="lado l3">H</div>
                <div class="lado l4">S</div>
                <div class="lado l5">A</div>
                <div class="lado l6">D</div>
            </div>

            <div class="hex h6">
                <div class="lado l1">S</div>
                <div class="lado l2">A</div>
                <div class="lado l3">V</div>
                <div class="lado l4">T</div>
                <div class="lado l5">H</div>
                <div class="lado l6">D</div>
            </div>

        </div>
        <div ng-show="screen == 'authorizado'"  class="authorizado">
            <div class="">ACCESO AUTHORIZADO</div>
        </div>
        <div ng-show="screen=='menuHex'">
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
                        <div>Error: Mezclador automático de combustible dañado. Mezclar combustible manualmente.</div>
                        <div class="third" ng-class="{'half': currentPuzzles[14] == 1}">Ajustar ingredientes a su valor correcto
                            <img src="/assets/images/diagram1.png" width="100%" />
                        </div>
                        <div class="third">Mezclar ingredientes<br/>(botón gris)
                            <img src="/assets/images/diagram2.png" width="100%" />
                        </div>
                        <div class="third">
                            Abrir valvulas de paso al tanque
                            <img src="/assets/images/diagram3.png" width="100%" />
                        </div>
                        <div>Antes de presionar los botones redondos, observe la secuencia de luces</div>
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
                        Misión Andromeda
                    </h1>
                </div>
                <div class="futurepanel__body">
                    <video id="videoAndromeda" autoplay></video>
                </div>
            </div>
        </div>
    </div>
    <div ng-show="data.status == 1">
    </div>
    <div ng-show="data.status == 0">
    </div>
</div>

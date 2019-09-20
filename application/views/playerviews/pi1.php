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
<div ng-controller="PlayerviewControllerPi1" ng-init="roomId = <?=$roomId; ?>" >
    <audio id="alarma">
      <source src="/assets/audio/alarma.mp3">
    </audio>
    <audio autoplay loop  id="bgmusic">
      <source src="/assets/audio/tension.mp3">
    </audio>
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2 || data.status == 1" ng-class="{'isAlien': isAlien}">
        <div class="pi1" ng-if="screen=='cluesInfo'">
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
                        <h1 class="futurepanel__title">Asistente</h1>
                    </div>
                    <div class="futurepanel__body cluebody">
                        {{clue}}
                    </div>
                </div>  

            </div>

        </div>
        <div class="screen" ng-if="screen=='muestra'">

            <div class="futurepanel">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">Text Options</h1>
                </div>
                <div class="futurepanel__body">
                    <h2 class="heading">title</h2>
                    <p>This is default text.</p>
                    <h3 class="heading">title</h3>
                    <p>This is default text.</p>
                    <h4 class="heading">title</h4>
                    <p>This is default text.</p>
                    <h5 class="heading">title</h5>
                    <p>This is default text.</p>
                    <h6 class="heading">title</h6>
                    <p>This is default text.</p>
                </div>
            </div>  
            <div class="futurepanel ">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">Metrics</h1>
                </div>
                <div class="futurepanel__body">
                    <div class="futuremetric futuremetric--circle">
                        <div class="futuremetric__value">74</div>
                        <div class="futuremetric__label">tonnes</div>
                    </div>
                    <div class="futuremetric futuremetric--circle">
                        <div class="futuremetric__value futuremetric__value--optimal">94</div>
                        <div class="futuremetric__label">LBS</div>
                    </div>
                    <div class="futuremetric futuremetric--circle">
                        <div class="futuremetric__value futuremetric__value--warning">54</div>
                        <div class="futuremetric__label">Hours</div>
                    </div>
                    <div class="futuremetric futuremetric--circle">
                        <div class="futuremetric__value futuremetric__value--alert">24</div>
                        <div class="futuremetric__label">pixels</div>
                    </div>
                </div>
                <div class="futurepanel__footer">
                    <div>#0002134678 TFFC:3 ///////////// </div>
                </div>
            </div>  

            <div class="futurepanel">
                <div class="futurepanel__header">
                    <h1 class="futurepanel__title">Map Grid</h1>
                </div>
                <div class="futurepanel__body">
                    <div class="futuregrid">
                        <div class="futuregrid__row">
                            <div class="futuregrid__cell">A1</div>
                            <div class="futuregrid__cell">A2</div>
                            <div class="futuregrid__cell">A3</div>
                            <div class="futuregrid__cell">A4</div>
                            <div class="futuregrid__cell">A5</div>
                        </div>
                    </div>
                </div>
                <div class="futurepanel__footer">
                    <div>#0002134678 TFFC:3 ///////////// </div>
                </div>
            </div>
        </div>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

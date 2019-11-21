<style>
    body {
        font-family: "Exo 2", Arial, sans-serif;
        font-size: 2em;
    }
    input {
        height: 50px;
        font-size: 2rem;
        width: 100px;
    }
    .config {
        border: solid 1px white;
        margin: 2px auto;
        padding: 20px;
        max-width: 1000px;
    }
</style>
<div id="houston" ng-controller="PlayerviewControllerHouston" ng-init="roomId = <?=$roomId; ?>" >
    <div class="config">
        Combustible generado extra: <input type="number" ng-model="extrafuel"  ng-change="reportGizmo()"/>
    </div>
    <div class="config">
        Dificultad: 
        <label><input type="radio" ng-model="easymode" ng-value="false" ng-change="reportGizmo()" />Normal</label>
        <label><input type="radio" ng-model="easymode" ng-value="true" ng-change="reportGizmo()"/>Fácil</label>
    </div>
    <div class="config">
    </div>
</div>
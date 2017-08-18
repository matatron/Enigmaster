<style>
</style>
<div ng-if="data.status == 2">
    <div class="well">
        ¿LISTOS PARA EMPEZAR?
    </div>
</div>
<div ng-if="data.status == 1">
    <div class="lcd giant-text">
        {{timePass | date : 'HH:mm:ss' : 'UTC'}}
    </div>
    <div class="well">
        {{clue}}
    </div>
    <div>
        <i class="fa fa-key" ng-repeat="n in pistas"></i>
    </div>
</div>
<div ng-if="data.status == 0">
    <div class="lcd giant-text">
        {{data.time | date : 'HH:mm:ss' : 'UTC'}}
    </div>
    <div class="well">
        GAME OVER!
    </div>
</div>

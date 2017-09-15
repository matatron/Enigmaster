<style>
</style>
<div ng-if="data.status == 2">
    <div class="well">
        ¿LISTOS PARA EMPEZAR?
    </div>
</div>
<div ng-if="data.status == 1">
    <div class="lcd giant-text">
        {{timeLeft}}
    </div>
    <div class="well">
        {{clue}}
    </div>
</div>
<div ng-if="data.status == 0">
    <div class="lcd giant-text">
        {{data.time}}
    </div>
    <div class="well">
        GAME OVER!
    </div>
</div>

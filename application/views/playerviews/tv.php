<style>
    body {
        margin: 0 !important;
    }
</style>
<div ng-controller="PlayerviewControllerTV" ng-init="roomId = <?=$roomId; ?>" >
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2">
        <video style="width: 100%; height: 100%;" id="tvvideo" autoplay></video>
    </div>
    <div ng-if="data.status == 1">
        <div class="">
            Tiempo Final:
        </div>
        <div class="lcd giant-text">
            {{data.time | clock}}
        </div>
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

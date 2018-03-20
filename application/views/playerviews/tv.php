<style>
    body {
        margin: 0 !important;
    }
    .endtext {
        color: black;
        position: absolute;
        width: 100%;
        z-index: 10;
        top: 30%;
    }
</style>
<div ng-controller="PlayerviewControllerTV" ng-init="roomId = <?=$roomId; ?>" >
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2 || data.status == 1">
        <video style="width: 100%; height: 75vw;" id="tvvideo" autoplay></video>
    </div>
    <div ng-if="data.status == 1">
        <div class="giant-text endtext">
            THE END
        </div>
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

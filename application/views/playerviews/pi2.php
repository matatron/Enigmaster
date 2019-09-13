<style>
    html {
        height: 100%;
    }
    body {
        height: 100%;
        margin: 0 !important;
        background-image: url('/assets/images/fondo_pi1_1.jpg');
        background-size: contain;
    }
</style>
<div ng-controller="PlayerviewControllerPi1" ng-init="roomId = <?=$roomId; ?>" >
    <div class="text"></div>
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2 || data.status == 1">
        <video id="tvvideo" autoplay></video>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>

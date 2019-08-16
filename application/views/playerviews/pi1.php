<script type="text/javascript">
    var myWidth = 0, myHeight = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
        myWidth = window.innerWidth; myHeight = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth ||document.documentElement.clientHeight ) ) {
        myWidth = document.documentElement.clientWidth; myHeight = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        myWidth = document.body.clientWidth; myHeight = document.body.clientHeight;
    }
</script>

<style>
    body {
        margin: 0 !important;
        background-image: url('/assets/images/fondo_pi1_1.jpg');
        background-position: center;
        background-size: cover;
    }
</style>
<div ng-controller="PlayerviewControllerTV" ng-init="roomId = <?=$roomId; ?>" >
    <div class="text"></div>
    <div ng-if="data.status == 3">
    </div>
    <div ng-if="data.status == 2 || data.status == 1">
        <video style="width: 100%; height: 75vw;" id="tvvideo" autoplay></video>
    </div>
    <div ng-if="data.status == 1">
    </div>
    <div ng-if="data.status == 0">
    </div>
</div>
<script type="text/javascript">
    document.write('<p>' + myWidth + 'x' + myHeight + '</p>');
    document.write('<p>' + window.screen.availHeight + 'x' + window.screen.availWidth + '</p>');
</script>

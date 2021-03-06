<!DOCTYPE html>
<html id="ng-app" ng-app="Enigmaster">
    <head>
        <title>Enigmaster ∑ <?= $title; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="/assets/css/roomonly.css"/>
<?php
            if (isset($music)) {
?>
        <script>
window.music = [
    "/assets/audio/<?=$music[0];?>.mp3",
<?php foreach($music as $file) { ?>
    "/assets/audio/<?=$file;?>.mp3",
<?php } ?>
    ""
];
window.sounds = [
    null,
<?php foreach($sound as $file) {
    if ($file != '') { ?>
    "/assets/audio/<?=$file;?>.mp3",
<?php
     } else echo '  null,
';

    } ?>
    ""
];
        </script>
<?php
    }
?>

    </head>
    <body class="no-gutter">
        <div class="container">
<?php echo $content; ?>
        </div> <!-- /container -->

        <script src="/assets/js/jquery-3.4.1.min.js"></script>
        <script src="/assets/js/lodash.min.js"></script>
        <script src="/assets/angular/angular.min.js"></script>
        <script src="/assets/app/PlayerviewController.js"></script>
    </body>
</html>

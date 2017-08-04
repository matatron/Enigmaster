<!DOCTYPE html>
<html id="ng-app" ng-app="Enigmaster">
    <head>
        <title>Enigmaster ∑ <?= $title; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="/assets/css/roomonly.css"/>
    </head>
    <body class="no-gutter">
        <div class="container" ng-controller="PlayerviewController" ng-init="roomId = <?=$roomId; ?>">
<?php echo $content; ?>
        </div> <!-- /container -->

        <script src="/assets/js/jquery-2.1.4.min.js"></script>
        <script src="/assets/js/lodash.min.js"></script>
        <script src="/assets/angular/angular.min.js"></script>
        <script src="/assets/app/PlayerviewController.js"></script>
    </body>
</html>

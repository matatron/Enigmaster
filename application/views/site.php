<!DOCTYPE html>
<html id="ng-app" ng-app="Enigmaster">
    <head>
        <title>Enigmaster ∑ <?= $title; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
        <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.<?= Session::instance()->get('skin'); ?>.min.css"/>
        <link rel="stylesheet" href="/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="/assets/angular/angular-ui-grid/ui-grid.min.css"/>
        <link rel="stylesheet" href="/assets/css/bootstrap-toggle.min.css"/>
        <link rel="stylesheet" href="/assets/css/style.css"/>
    </head>
    <body class="mt">

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="/assets/images/logo.png" class="mainlogo" /></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/main/upload">Subir video</a>
                        </li>
                        <li>
                            <a href="/main/gizmos">Gizmos</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Skin: <?= Session::instance()->get('skin'); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="/system/skin/default">Default</a></li>
                                <li><a href="/system/skin/cyborg">Cyborg</a></li>
                                <li><a href="/system/skin/darkly">Darkly</a></li>
                                <li><a href="/system/skin/journal">Journal</a></li>
                                <li><a href="/system/skin/lumen">Lumen</a></li>
                                <li><a href="/system/skin/readable">Readable</a></li>
                                <li><a href="/system/skin/sandstone">Sandstone</a></li>
                                <li><a href="/system/skin/slate">Slate</a></li>
                                <li><a href="/system/skin/spacelab">Spacelab</a></li>
                                <li><a href="/system/skin/superhero">Superhero</a></li>
                                <li><a href="/system/skin/united">United</a></li>
                                <li><a href="/system/skin/yeti">Yeti</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container">
<?php echo $content; ?>
        </div> <!-- /container -->

        <script src="/assets/js/jquery-2.1.4.min.js"></script>
        <script src="/assets/js/lodash.min.js"></script>
        <script src="/assets/angular/api-check.min.js"></script>
        <script src="/assets/angular/angular.min.js"></script>
        <script src="/assets/angular/angular-route.min.js"></script>
        <script src="/assets/angular/angular-local-storage.js"></script>
        <script src="/assets/angular/angular-translate.min.js"></script>
        <script src="/assets/js/ui-bootstrap-tpls-2.5.0.min.js"></script>
        <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/angular/angular-ui-grid/ui-grid.min.js"></script>
        <script src="/assets/js/bootstrap-toggle.min.js"></script>

        <script src="/assets/app/app.js"></script>
        <script src="/assets/app/PregameController.js"></script>
        <script src="/assets/app/GameController.js"></script>
        <script src="/assets/app/PostgameController.js"></script>
        <script src="/assets/app/RoomController.js"></script>
        <script src="/assets/app/GizmoController.js"></script>
        <script src="/assets/app/StatisticsCtrl.js"></script>
    </body>
</html>

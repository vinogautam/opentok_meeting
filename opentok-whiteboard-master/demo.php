<?php
$sessionId = $_GET['sessionId']; 
$token = $_GET['token'];
 ?>
<!DOCTYPE html>
<html ng-app="demo">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>OpenTok Whiteboard Demo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <script src="https://code.jquery.com/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.9.25/paper-core.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="//static.opentok.com/v2.6/js/opentok.js" type="text/javascript" charset="utf-8"></script>
        <script src="../OpenTok-Angular-master/opentok-angular.js" type="text/javascript" charset="utf-8"></script>
        <script src="opentok-whiteboard.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="opentok-whiteboard.css" type="text/css" media="screen" charset="utf-8">

        <script type="text/javascript" charset="utf-8">
        // A bit cheeky: Forcing checkSystemRequirements to pass so that this works on mobile Safari
        OT.checkSystemRequirements = function () {
            return true;
        };
        angular.module('demo', ['opentok', 'opentok-whiteboard'])
        .controller('DemoCtrl', ['$scope', 'OTSession', function ($scope, OTSession) {
            $scope.connected = false;
            OTSession.init('45593352', '<?= $sessionId?>', '<?= $token?>', function (err) {
                if (!err) {
                    $scope.$apply(function () {
                        $scope.connected = true;
                    });
                }
            });
        }]);
        </script>
        <style type="text/css" media="screen">
            body {
                margin: 0;
                padding: 0;
            }

            ot-whiteboard {
                display: block;
                width: 1280px;
                height: 720px;
                background-color: #ccc;
                position: absolute;
                left: 0;
                right: 0;
            }
        </style>
    </head>
    <body ng-controller="DemoCtrl">
        <ot-whiteboard width="1280" height="720"></ot-whiteboard>
    </body>
</html>

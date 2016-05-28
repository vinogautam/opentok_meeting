<?php
$autoloader = 'vendor/autoload.php';
if (!file_exists($autoloader)) {
  die('You must run `composer install` in the sample app directory');
}
require($autoloader);

use OpenTok\OpenTok;
$API_KEY = '45593352';
$API_SECRET = '95761869dfa8c20921a6178a633e7738bf4590a6';
$apiObj = new OpenTok($API_KEY, $API_SECRET);
$session = $apiObj->createSession();
$sessionId = $session->getSessionId(); 
$token = $apiObj->generateToken($sessionId);
 ?>
<!DOCTYPE html>
<html ng-app="demo">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>OpenTok-Angular Demo</title>
         <style type="text/css" media="screen">
             ot-publisher,ot-subscriber,ot-layout {
                 display: block;
                 overflow: hidden;
             }
             ot-layout {
                 position: absolute;
                 top: 0;
                 left: 0;
                 right: 0;
                 bottom: 0;
             }
         </style>
    </head>
    <body>
        Join Meeting Url as admin : <a target="blank" href="meeting-with-whiteboard/?admin&sessionId=<?= $sessionId;?>&token=<?= $token;?>">Join conf</a><br>
		Join Meeting Url as client: <a target="blank" href="meeting-with-whiteboard/?sessionId=<?= $sessionId;?>&token=<?= $token;?>">Join conf</a><br>
    </body>
</html>

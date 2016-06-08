<?php
$sessionId = $_GET['sessionId']; 
$token = $_GET['token'];
 ?>
<html>
<body>
  <button onclick="javascript:screenshare();" disabled id="shareBtn">Share your screen</button>
  <div id="camera-publisher"></div>
  <div id="camera-subscriber"></div>
  <div id="screen-subscriber"></div>
  <script src="//static.opentok.com/v2/js/opentok.js"></script>
  <script type="text/javascript">

    // Go to https://dashboard.tokbox.com/ to get your OpenTok API Key and to generate
    // a test session ID and token:
    var apiKey    = '45593352',
      sessionId = '<?= $sessionId?>',
      token     = '<?= $token?>';

    // Replace this with the ID for your Chrome screen-sharing extension, which you can
    // get at chrome://extensions/:

    var extensionId = 'pfobnffdhkcjnpmcgjmfdgbgcpaijbpd';

    // If you register your domain with the the Firefox screen-sharing whitelist, instead of using
    // a Firefox screen-sharing extension, set this to the Firefox version number, such as 36, in which
    // your domain was added to the whitelist:

    var ffWhitelistVersion; // = '36';

    var session = OT.initSession(apiKey, sessionId);

    session.connect(token, function(error) {
      if (error) {
        alert('Error connecting to session: ' + error.message);
        return;
      }
      // publish a stream using the camera and microphone:
      var publisher = OT.initPublisher('camera-publisher');
      session.publish(publisher);
      document.getElementById('shareBtn').disabled = false;
    });

    session.on('streamCreated', function(event) {
      if (event.stream.videoType === 'screen') {
        // This is a screen-sharing stream published by another client
        var subOptions = {
          width: event.stream.videoDimensions.width / 2,
          height: event.stream.videoDimensions.height / 2
        };
        session.subscribe(event.stream, 'screen-subscriber', subOptions);
      } else {
        // This is a stream published by another client using the camera and microphone
        session.subscribe(event.stream, 'camera-subscriber');
      }
    });

    // For Google Chrome only, register your extension by ID,
    // You can find it at chrome://extensions once the extension is installed
    OT.registerScreenSharingExtension('chrome', extensionId, 2);

    function screenshare() {
      OT.checkScreenSharingCapability(function(response) {
        console.info(response);
        if (!response.supported || response.extensionRegistered === false) {
          alert('This browser does not support screen sharing.');
        } else if (response.extensionInstalled === false
            && (response.extensionRequired || !ffWhitelistVersion)) {
          alert('Please install the screen-sharing extension and load this page over HTTPS.');
        } else if (ffWhitelistVersion && navigator.userAgent.match(/Firefox/)
          && navigator.userAgent.match(/Firefox\/(\d+)/)[1] < ffWhitelistVersion) {
            alert('For screen sharing, please update your version of Firefox to '
              + ffWhitelistVersion + '.');
        } else {
          // Screen sharing is available. Publish the screen.
          // Create an element, but do not display it in the HTML DOM:
          var screenContainerElement = document.createElement('div');
          var screenSharingPublisher = OT.initPublisher(
            screenContainerElement,
            { videoSource : 'screen' },
            function(error) {
              if (error) {
                alert('Something went wrong: ' + error.message);
              } else {
                session.publish(
                  screenSharingPublisher,
                  function(error) {
                    if (error) {
                      alert('Something went wrong: ' + error.message);
                    }
                  });
              }
            });
          }
        });
    }
  </script>
</body>
</html>

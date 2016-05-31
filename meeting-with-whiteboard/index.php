<?php
$sessionId = $_GET['sessionId']; 
$token = $_GET['token'];
 ?>
<!DOCTYPE html>
<html ng-app="demo">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>OpenTok-Angular Demo</title>
         
		 <link rel="stylesheet" type="text/css" href="slick.css">
		<link rel="stylesheet" type="text/css" href="slick-theme.css">
		<style type="text/css" media="screen">
             ot-publisher,ot-subscriber,ot-layout {
                 display: block;
                 overflow: hidden;
             }
             ot-layout {
				 display: block;
                width: 30%;
                height: 300px;
                position: absolute;
                top: 0;
                left: 0;
             }
			 .video_container{}
			 ot-whiteboard {
                display: block;
                width: 50%;
                height: 300px;
                background-color: #ccc;
                position: absolute;
                top: 350px;
                left: 0;
            }
			.slider1 {
				width: 40%;
                margin:auto;
                top: 0;
				position:absolute;
				right:0;
			}
			.slider2 {
				width: 40%;
				margin:auto;
                top: 390px;
				position:absolute;
				right:100px;
			}
			.slick-prev::before, .slick-next::before {
				color: black;
			}
			.slick-slide{text-align:center;}
			.slick-slide img{display:inline-block;}
			iframe{position:absolute; right:0; top:0; width:25%; left:0; margin:auto;height:250px;}
         </style>
    </head>
    <body ng-controller="MyCtrl">
        <div class="video_container" >
            <ot-layout props="{animate:true}">
                <ot-subscriber ng-repeat="stream in streams" 
                    stream="stream" 
                    props="{style: {nameDisplayMode: 'off'}}">
                </ot-subscriber>
                <ot-publisher id="publisher" 
                    props="{style: {nameDisplayMode: 'off'}, resolution: '500x300', frameRate: 30}">
                </ot-publisher>
            </ot-layout>
        </div>
        
		<div class="whiteboard_container" >
			<ot-whiteboard width="1280" height="720"></ot-whiteboard>
		</div>
		
		<section class=" slider1">
			<?php for($i=1;$i<=6;$i++){?>
			<div>
			  <img src="http://placehold.it/350x300?text=<?= $i?>">
			</div>
			<?php }?>
		  </section>
		<?php if(isset($_GET['admin'])){?>
		  <section class=" slider2">
			<?php for($i=1;$i<=6;$i++){?>
			<div>
			  <img src="http://placehold.it/100x150?text=<?= $i?>">
			</div>
			<?php }?>
		  </section>
		<?php }?>
		
		<iframe <?php if(!isset($_GET['admin'])){?>style="pointer-events:none;"<?php }?> id="youtube-player" width="640" height="360" src="http://www.youtube.com/embed/geTgZcHrXTc?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allowfullscreen="true" allowscriptaccess="always"></iframe>
		
        <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript" charset="utf-8"></script>
        
		
		<script src="slick.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.9.25/paper-core.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="//static.opentok.com/v2.6/js/opentok.js" type="text/javascript" charset="utf-8"></script>
		<script src="../OpenTok-Angular-master/opentok-layout.js" type="text/javascript" charset="utf-8"></script>
        <script src="../OpenTok-Angular-master/opentok-angular.js" type="text/javascript" charset="utf-8"></script>
        <script src="../opentok-whiteboard-master/opentok-whiteboard.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="opentok-whiteboard.css" type="text/css" media="screen" charset="utf-8">
		<script src="https://www.youtube.com/iframe_api"></script>
		
        <script type="text/javascript" charset="utf-8">
		
		  var player;
		  var scope;
		  function onYouTubeIframeAPIReady() {
			scope = angular.element($("body")).scope();
			player = new YT.Player( 'youtube-player', {
			  events: { 'onStateChange': onPlayerStateChange }
			});
			console.log(player);
		  }
		  
		  function onPlayerStateChange(event) {
				switch(event.data) {
				  case 0:
					console.log('video ended');
					break;
				  case 1:
					console.log('video playing from '+player.getCurrentTime());
					scope.video_noti('start');
					break;
				  case 2:
					console.log('video paused at '+player.getCurrentTime());
					scope.video_noti('pause');
				}
			}
		angular.module('demo', ['opentok', 'opentok-whiteboard'])
            .controller('MyCtrl', ['$scope', 'OTSession', 'apiKey', 'sessionId', 'token', function($scope, OTSession, apiKey, sessionId, token) {
                
				$scope.video_noti = function(st){
					OTSession.session.signal( 
							{  type: 'youtube-player',
							   data: st
							}, 
							function(error) {
								if (error) {
								  console.log("signal error ("
											   + error.code
											   + "): " + error.message);
								} else {
								  console.log("signal sent.");
								}
							});
				};
				
				$('#stop').on('click', function() {
					$('#popup-youtube-player')[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
				});
				
				$scope.connected = false;
				OTSession.init(apiKey, sessionId, token, function (err) {
					if (!err) {
						$scope.$apply(function () {
							$scope.connected = true;
						});
					}
				});
				$scope.streams = OTSession.streams;
				
				$('.slider1').slick({
				  slidesToShow: 1,
				  slidesToScroll: 1,
				  arrows: false,
				  fade: true,
				  <?php if(isset($_GET['admin'])){?>
				  asNavFor: '.slider2'
				  <?php }else{?>
				  swipe:false
				  <?php }?>
				});
				<?php if(isset($_GET['admin'])){?>
				$('.slider2').slick({
				  slidesToShow: 3,
				  slidesToScroll: 1,
				  asNavFor: '.slider1',
				  dots: true,
				  centerMode: true,
				  focusOnSelect: true
				});
				<?php }?>
				
				<?php if(isset($_GET['admin'])){?>
				OTSession.session.on({
                    sessionConnected: function() {
						$('.slider1').on('afterChange', function(event, slick, currentSlide){
						  console.log(currentSlide);
						  OTSession.session.signal( 
							{  type: 'presentationControl',
							   data: {slide:currentSlide}
							}, 
							function(error) {
								if (error) {
								  console.log("signal error ("
											   + error.code
											   + "): " + error.message);
								} else {
								  console.log("signal sent.");
								}
							});
						});
						
                    }
				});
				<?php }else{?>
				OTSession.session.on('signal:presentationControl', function (event) {
					console.log(event);
					//var currentSlide = $('.slider1').slick('slickCurrentSlide');
					$('.slider1').slick('slickGoTo', event.data.slide);
				});
				OTSession.session.on('signal:youtube-player', function (event) {
					if(event.data == 'start')
						player.playVideo();
					else
						player.pauseVideo();
				});
				<?php }?>
			}])
			.value({
                apiKey: '45593352',
                sessionId: '<?= $sessionId?>',
                token: '<?= $token?>'
            });
        </script>
    </body>
</html>

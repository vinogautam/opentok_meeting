<?php
$sessionId = $_GET['sessionId']; 
$token = $_GET['token'];
 ?>
<!DOCTYPE html>
<html ng-app="demo">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>OpenTok-Angular Demo</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="slick.css">
		<link rel="stylesheet" type="text/css" href="slick-theme.css">
		<style type="text/css" media="screen">
            .slick-prev::before, .slick-next::before {
				color: black;
			}
			.slick-slide{text-align:center;}
			.slick-slide img{display:inline-block;}
			.OT_publisher, .OT_subscriber {
				height: 200px !important;
				position: relative !important;
				width: 100% !important;
				left:0 !important;
			}
			.OT_widget-container{border-radius:5%;}
			header{background-color:#790303;width:100%; height:40px;margin-bottom:15px;position:relative;}
			header .fa{font-size:16px;width:16px;height:16px;position:absolute;margin:auto;top:0;bottom:0;color:#fff;}
			header .fa.fa-bars{left:1%;}
			header .fa.fa-times{right:1%;}
			.overall_container{height:500px;}
			.opentok_actions div{display:inline-block; padding:5px;background-color:#790303;color:#fff;}
			.opentok_actions div.selected{background-color:#fff;border:1px solid #790303;color:#790303;}
         </style>
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    </head>
    <body ng-controller="MyCtrl">
        
		<div class="container-fluid">
			<div class="row">
				<header>
					<i class="fa fa-bars"></i>
					<i class="fa fa-times"></i>
				</header>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-3 col-lg-3" ng-cloak>
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
					<div class="text_chat_container" ng-class="{visible:visible}">
						<div id="messagesDiv" style="height:250px;overflow:auto;">
							<p ng-repeat="c in chat" ng-class="{align_right: c.email != data.email}">
								<img ng-if="c.email == data.email" ng-src="http://www.gravatar.com/avatar/{{c.hash}}/?s=30"> 
								{{c.msg}}
								<img ng-if="c.email != data.email" ng-src="http://www.gravatar.com/avatar/{{c.hash}}/?s=30"> 
							</p>
						</div>
						<p ng-show="noti">{{noti.name}} is typing...<p>
						<form>
							<input size="43" type="text" ng-model="data.name" placeholder="Name">
							<input size="43" type="text" ng-model="data.email" placeholder="Email">
							<textarea rows="2" cols="33" ng-model="data.msg" ng-keyup="send_noti()" placeholder="Message" ng-enter="add();"></textarea>
							<button ng-click="add();">Post</button>
						</form>
					</div>
				</div>
				<div class="col-sm-8 col-md-8 col-lg-8">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 overall_container">
							<div class="whiteboard_container" ng-show="whiteboard">
								<ot-whiteboard width="1280" height="720"></ot-whiteboard>
							</div>
							
							<section class=" slider1" ng-show="presentation">
								<?php for($i=1;$i<=6;$i++){?>
								<div>
								  <img src="http://placehold.it/350x300?text=<?= $i?>">
								</div>
								<?php }?>
							  </section>
							<?php if(isset($_GET['admin'])){?>
							  <section class=" slider2" ng-show="presentation">
								<?php for($i=1;$i<=6;$i++){?>
								<div>
								  <img src="http://placehold.it/100x150?text=<?= $i?>">
								</div>
								<?php }?>
							  </section>
							<?php }?>
							
							<iframe ng-show="video" <?php if(!isset($_GET['admin'])){?>style="pointer-events:none;"<?php }?> id="youtube-player" width="640" height="360" src="//www.youtube.com/embed/geTgZcHrXTc?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allowfullscreen="true" allowscriptaccess="always"></iframe>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-12 opentok_actions">
							<div ng-init="presentation=true;" ng-class="{selected:presentation}" ng-click="presentation=true;whiteboard=false;video=false;">
								Presentation
							</div>
							<div ng-class="{selected:whiteboard}" ng-click="presentation=false;whiteboard=true;video=false;">
								Whiteboard
							</div>
							<div ng-class="{selected:video}" ng-click="presentation=false;whiteboard=false;video=true;">
								Video
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
        <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript" charset="utf-8"></script>
        
		
		<script src="slick.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.9.25/paper-core.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="//static.opentok.com/v2.6/js/opentok.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../OpenTok-Angular-master/opentok-layout.js" type="text/javascript" charset="utf-8"></script>
        <script src="../OpenTok-Angular-master/opentok-angular.js" type="text/javascript" charset="utf-8"></script>
        <script src="../opentok-whiteboard-master/opentok-whiteboard.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="opentok-whiteboard.css" type="text/css" media="screen" charset="utf-8">
		<script src="https://www.youtube.com/iframe_api"></script>
		<script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/md5-min.js'></script>
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
		.directive('ngEnter', function() {
			return function(scope, element, attrs) {
				element.bind("keydown keypress", function(event) {
					if(event.which === 13) {
							scope.$apply(function(){
									scope.$eval(attrs.ngEnter);
							});
							
							event.preventDefault();
					}
				});
			};
		})
            .controller('MyCtrl', ['$scope', 'OTSession', 'apiKey', 'sessionId', 'token', '$timeout', function($scope, OTSession, apiKey, sessionId, token, $timeout) {
                $scope.chat = [];
				$scope.noti = false;
				var statusRef = new Firebase('https://vinogautam.firebaseio.com/opentok/<?= $sessionId?>');
				statusRef.on('child_added', function(snapshot) {
					//angular.forEach(snapshot.val(), function(v,k){
						v = snapshot.val();
						if(v.noti)
						{
							$scope.noti = v;
							console.log("fdgdfg tert");
							$timeout(function(){
								$scope.noti = false;
							}, 3000);
						}
						else
						{
							v.hash = CryptoJS.MD5(v.email).toString();
							$scope.chat.push(v);
							$timeout(function(){
								jQuery("#messagesDiv").scrollTop(jQuery("#messagesDiv")[0].scrollHeight);
							}, 100);
							$scope.visible = true;
						}
					//});
				});
				
				$scope.gravatar = function(email){
					encrypt = CryptoJS.MD5(email).toString();
					return "https://www.gravatar.com/avatar/"+encrypt+"?s=40";
				};
				
				$scope.send_noti = function()
				{
					console.log("noti send");
					statusRef.push({name: $scope.data.name, email: $scope.data.email, noti:true, ts: new Date().getTime()});
				};
				
				$scope.add = function(){
					statusRef.push($scope.data);
					$scope.data.msg = '';
				};
				
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

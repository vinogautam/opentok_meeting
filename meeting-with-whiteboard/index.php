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
			.side_menu li.selected{background-color:#790303;border:1px solid #790303;}
			.side_menu{position:absolute;top:40px;bottom:0;left:0;background-color:#890101;z-index:99;}
			.side_menu ul{
				color: #fff;
				list-style: outside none none;
				margin: 0;
				padding: 0;
			}
			.side_menu ul li{padding:15px;position:relative;}
			ot-whiteboard {
                display: block;
                width: 100%;
                height:400px;
                position: absolute;
                left: 0;
                right: 0;
				z-index:11;
            }
			.slider1{margin-bottom:20px;z-index:10;}
			.sub_menu{position:absolute;left:100%;display:none;top:0;background-color:#A13535;width:300px;}
			.side_menu ul li:hover .sub_menu{display:block;}
			.sub_menu h3{margin:0;font-size:16px;background-color:#790303;padding: 16px 5px;}
			.sub_menu input[type='text']{background:none;border:none;border-bottom:1px solid #fff;width:100%;}
			.sub_menu ul{margin:20px 0;}
         </style>
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    </head>
    <body ng-controller="MyCtrl" class="<?= isset($_GET['admin']) ? 'admin_view' : 'desktop_view'; ?>">
        
		<div class="container-fluid">
			<div class="row">
				<header>
					<i class="fa fa-bars"></i>
					<i class="fa fa-times"></i>
				</header>
			</div>
			<?php if(isset($_GET['admin'])){?>
			<div class="side_menu" ng-show="slide_menu">
				<ul>
					<li>Fi</li>
					<li ng-class="{selected:presentation}">
						<i class="fa fa-desktop"  ng-click="presentation=true;whiteboard=false;video=false;signal('presentation');"></i>
						<div class="sub_menu">
							<h3>Presentations</h3>
							<span><input ng-model="psearch"></span>
							<ul>
								<li ng-repeat="p in presentation_files" ng-click="selected_file(p.folder, p.files)">{{p.name}}</li>
							</ul>
							<div class="menu_bottom">
								<input id="convert_ppt" type="file" >
							</div>
						</div>
					</li>
					<li ng-class="{selected:video}" >
						<i class="fa fa-youtube-play" ng-click="presentation=false;whiteboard=false;video=true;signal('video');"></i>
					</li>
				</ul>
			</div>
			<?php }?>
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
				<div class="col-sm-12 col-md-9 col-lg-9">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12 overall_container">
							<div class="presentation_container" ng-show="presentation">
								<ot-whiteboard width="1280" height="720"></ot-whiteboard>
								<section class=" slider1">
									<?php for($i=1;$i<=6;$i++){?>
									<div>
									  <img src="http://placehold.it/700x400?text=<?= $i?>">
									</div>
									<?php }?>
								</section>
							</div>
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
		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+d.toUTCString();
			document.cookie = cname + "=" + cvalue + "; " + expires;
		}

		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
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
				$scope.presentation = true;
				
				$scope.presentation_files = getCookie('presentation') ? JSON.parse(getCookie('presentation')) : [];
				
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
				
				$(document).on("change", "#convert_ppt", function(e) {
					handleFileSelect(e, true);
				});
				
				var formdata = !!window.FormData;

				function handleFileSelect(evt, manual) {
					evt.stopPropagation();
					evt.preventDefault();
					var files;
					files = evt.target.files;
					
					for (var i = 0, f; f = files[i]; i++) {
						if (f.type !== "") {
							var filename = f.name;
							var formData = formdata ? new FormData() : null;
							formData.append('File', files[i]);
							formData.append('OutputFormat', 'jpg');
							formData.append('StoreFile', 'true');
							formData.append('ApiKey', '938074523');
							formData.append('JpgQuality', 100);
							formData.append('AlternativeParser', 'false');

							file_convert_to_jpg(formData, filename);
						} else {
							progress_status(random_id, 0, "Invalid File Format...");
						}
					}

				}

				function file_convert_to_jpg(formData, filename) {
					$.ajax({
						url: "https://do.convertapi.com/PowerPoint2Image",
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						success: function(response, textStatus, request) {
							$.post("save.php", {data:request.getResponseHeader('FileUrl')}, function(data){
								if(data != 'error')
								{	
									old_data = getCookie('presentation') ? JSON.parse(getCookie('presentation')) : [];
									new_data = JSON.parse(data);
									new_data.name = filename;
									old_data.push(new_data);
									setCookie('presentation', JSON.stringify(old_data), 365);
									$scope.$apply(function(){
										$scope.presentation_files = old_data;
										$scope.selected_file(new_data.folder, new_data.files);
									});
								}
							});
						},
						error: function(jqXHR) {
							alert("Error in file conversion");
						}
					});
				}
				
				$scope.selected_file = function(folder, files)
				{
					console.log(folder, files);
					$(".slider1").slick('unslick');
					$(".slider2").slick('unslick');
					$(".slider1").empty();
					$(".slider2").empty();
					angular.forEach(files, function(v,k){
						$('.slider1').append("<div><img width='700' height='400' src='extract/"+folder+"/"+v+"'></div>");
						$('.slider2').append("<div><img width='100' height='150' src='extract/"+folder+"/"+v+"'></div>");
					});
					
					$scope.construct_slider();
				}
				
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
				
				$scope.signal = function(data){
					player.stopVideo();
					OTSession.session.signal( 
							{  type: 'admin-signal',
							   data: data
							}, 
							function(error) {
								if (error) {
								  console.log("signal error ("
											   + error.code
											   + "): " + error.message);
								} else {
								  console.log("signal sent.");
								}
							}
					);
				};
				
				$scope.construct_slider = function(){
				
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
				
				};
				
				$scope.construct_slider();
				
				<?php if(isset($_GET['admin'])){?>
				OTSession.session.on({
                    sessionConnected: function() {
						$scope.$apply(function(){$scope.slide_menu = true;});
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
							$scope.clear();
							OTSession.session.signal( 
							{  type: 'otWhiteboard_clear'
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
				OTSession.session.on('signal:admin-signal', function (event) {
					if(event.data == 'presentation')
					{
						$scope.$apply(function(){
							$scope.presentation = true;
							$scope.video = false;
						});
						player.pauseVideo();
					}
					else if(event.data == 'video')
					{
						$scope.$apply(function(){
							$scope.presentation = false;
							$scope.video = true;
						});
						player.stopVideo();
					}
					
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

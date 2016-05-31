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
				width: 50%;
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
        <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript" charset="utf-8"></script>
        
		
		<script src="slick.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paper.js/0.9.25/paper-core.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="//static.opentok.com/v2.6/js/opentok.js" type="text/javascript" charset="utf-8"></script>
		<script src="../OpenTok-Angular-master/opentok-layout.js" type="text/javascript" charset="utf-8"></script>
        <script src="../OpenTok-Angular-master/opentok-angular.js" type="text/javascript" charset="utf-8"></script>
        <script src="../opentok-whiteboard-master/opentok-whiteboard.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="opentok-whiteboard.css" type="text/css" media="screen" charset="utf-8">

        <script type="text/javascript" charset="utf-8">
            angular.module('demo', ['opentok', 'opentok-whiteboard'])
            .controller('MyCtrl', ['$scope', 'OTSession', 'apiKey', 'sessionId', 'token', function($scope, OTSession, apiKey, sessionId, token) {
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

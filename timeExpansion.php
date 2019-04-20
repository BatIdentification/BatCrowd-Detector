<html>
	<head>
		<title>Time expansion</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<?php
			if(isset($_GET['stop'])){
				shell_exec("pkill -6 sox; pkill -6 aplay");
			}
		?>
		<script>
			statusLabels = ["Device's speakers", "Detector's Speakers"]
			speakerStatus = 1;
			staticElements = 2;
			$(document).ready(function(){

				addPageButtons();

				//Switch output devices
				$(".img-button").click(function(event){
						speakerStatus = parseInt($(event.target).attr('value'));
						$("#speaker-status").html("Current: " + statusLabels[speakerStatus]);
						isLiveAvailable();
				});

				//When an audiofile is clicked send the information to the endpoint
				//Need two things: 1. The file we want to play (or live)
				//								 2. Where we want to output to
				$(".audiofile").click(function(){

					isAvailable = !($(event.target).attr("class").includes("unavilable"));
					source = $(event.target)[0].innerHTML;
					amplify = document.getElementById('amplify').checked;

					console.log(speakerStatus);

					if(speakerStatus == 0 && isAvailable){
						//Device output
						$.get("time-expansion-audio/" + source)
					    .done(function() {
									audio = new Audio("time-expansion-audio/" + source);
									audio.play();
					    }).fail(function() {
					        playTimeExpansion(source, amplify);
					  	})

					}else{

						playTimeExpansion(source, amplify)

					}

				});


			});

			//Live only allowed for detecotor's speakers
			function isLiveAvailable(){
				if(speakerStatus == 0){
					$("#live-audio").addClass("unavailable");
				}else{
					$("#live-audio").removeClass("unavailable");
				}
			}
			//Send a request to the endpoint to create the time expansion audio file
			//Source -> The raw bat call
			//Amplify -> Wether the volume should be amplified

			function playTimeExpansion(source, amplify){

				$.post("endpoint.php", {time_expansion: source, output: speakerStatus, amplifyAudio: amplify}, function(data){
					if(speakerStatus == 0){
						audio = new Audio("time-expansion-audio/" + source);
						audio.play();
					}
				});

			}
		</script>
	</head>
	<body>
		<?php
			$audio = true;
			$liveAvailable = true;
			include("includes/navigation.php");
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10">
					<div class="content actions">
						<div class="header-div">
							<p>Time-Expansion Playback</p>
						</div>
						<div class="row output-options">
							<div class="col-sm-6">
								<img class="img-button" src="images/external-speakers.png" value="1">
							</div>
							<div class="col-sm-6">
								<img class="img-button" src="images/internal-speakers.png" value="0">
							</div>
						</div>
						<div>
						<a id='speaker-status'>Current: Detector's Speakers</a>
						<button id="stop_action" value="timeExpansion">Stop</button></a>
						</div>
						<span>Amplify:<input type="checkbox" id="amplify"></span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

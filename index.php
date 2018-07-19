<html>
	<?php
		putenv("commands/setupAudioCard.sh");
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
<<<<<<< HEAD
  ?>
=======
		if(isset($_GET['recordingStart'])){
			shell_exec("/var/www/commands/startRecording.sh");
		}elseif(isset($_GET['recordingStop'])){
			shell_exec("pkill rec");
		}elseif(isset($_GET['soundStart'])){
			shell_exec("/var/www/commands/startSoundActivatedRecording.sh");
		}elseif(isset($_GET['soundStop'])){
			shell_exec("pkill -f startSoundActivatedRecording.sh; pkill -9 rec;");
			shell_exec("find *.wav -type f -size -100 -delete");
		}
  	 ?>
>>>>>>> master
	<head>
		<title>BatPi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script>

			var num_of_calls = 0;
			var count_interval;

			function updateCallsRecorded(){

				$.post("endpoint.php", {num_calls: true}, function(data){

					var response = jQuery.parseJSON(data);

					num_of_calls = num_of_calls == 0 ? response['num_calls'] : num_of_calls;

					$("#sa_recorded").text("<b>Calls recorded:</b> " + (response['num_calls'] - num_of_calls));

					console.log(response['num_calls']);

				});

			}

			$(document).ready(function(){
				tellBatPiTime();
				addPageButtons();
				$(".sound_activated_button").click(function(){
					$.post("commands.php", {sound_activated: $(this).val()});
					if($(this).val() == "true"){
						$("#sound_activated_status").show();
						count_interval = setInterval(updateCallsRecorded, 4000);
					}else{
						$("#sound_activated_status").hide();
						clearInterval(count_interval);
						$("#sa_recorded").text("Calls recorded: 0");
					}
				})
				$(".recording_button").click(function(){
					$.post("commands.php", {recording: $(this).val()});
				})
			})
		</script>
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapseable">
					 	<span class="sr-only">Toggle navigation</span>
					 	<span class="icon-bar"></span>
					 	<span class="icon-bar"></span>
					 	<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand">BatPi</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav side-nav">
						<h4>Audio files</h3>
						<?php
							$files = scandir(getcwd() . '/audiofiles', SCANDIR_SORT_DESCENDING);
							foreach($files as $key => $value){
								if(strpos($value, ".wav") !== false){
									echo("<li><a class='audiofile' href='audiofiles/{$value}' download>{$value}</a></li>");
								}
							}
						?>
					</ul>
					<ul class="nav navbar-nav">
						<li><a id="settings">Shutdown</a></li>
						<li><a id="settings" href="settings.php">Settings</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 content-container">
					<div class="content actions">
						<div class="header-div">
							<p>Actions</p>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<button class="option recording_button" value="true">Start Recording</button>
							</div>
							<div class="col-sm-4">
								<button class="option sound_activated_button" value="true">Start Sound activated recording</button>
							</div>
							<div class="col-sm-4">
								<a href="timeExpansion.php"><button class="option">Time Expansion</button></a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<button class="option recording_button" value="false">Stop Recording</button>
							</div>
							<div class="col-sm-4">
								<button class="option sound_activated_button" value="false">Stop sound activated recording</button>
							</div>
							<div class="col-sm-4">
								<a href="spectogram.php"><button class="option">Spectrogram display</button></a>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-offset-4" id="sound_activated_status">
						 <h4>Sound Activated Recording Status</h4>
						 <span id="sa_recorded"><b>Calls recorded:</b> 0</span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

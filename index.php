<html>
	<?php
		putenv("commands/setupAudioCard.sh");
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
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
	<head>
		<title>BatPi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
				tellBatPiTime();
				addPageButtons();
				$(".audiofile").click(function(event){
					$(event.target).addClass("selected");
					console.log($(event.target));
					source = $(event.target)[0].innerHTML;
					audio = new Audio(source);
					audio.play();
					audio.addEventListener("ended", function(){
    					$(event.target).removeClass("selected");
					});
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
							$files = scandir(getcwd(), SCANDIR_SORT_DESCENDING);
							foreach($files as $key => $value){
								if(strpos($value, ".wav") !== false){
									echo("<li><a class='audiofile'>{$value}</a></li>");
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
				<div class="col-sm-10">
					<div class="content actions">
						<div class="header-div">
							<p>Actions</p>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<a href="?recordingStart"><button class="option">Start Recording</button></a></td>
							</div>
							<div class="col-sm-4">
								<a href="?soundStart""><button class="option">Start Sound activated recording</button></td>
							</div>
							<div class="col-sm-4">
								<a href="timeExpansion.php"><button class="option">Time Expansion</button></a></td>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<td><a href="?recordingStop"><button class="option">Stop Recording</button></a></td>
							</div>
							<div class="col-sm-4">
								<td><a href="?soundStop"><button class="option">Stop sound activated recording</button></a></td>
							</div>
							<div class="col-sm-4">
								<td><a href="spectogram.php"><button class="option">Spectrogram display</button></a></t>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

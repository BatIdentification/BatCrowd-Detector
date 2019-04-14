<?php
	// if(isset($_GET['f'])){
	// 	if($_GET['status'] == "Internal Speakers"){
	// 		if($_GET['playback'] == "time-expansion"){
	// 			shell_exec("sox audiofiles/{$_GET['f']} -c 2 time-expansion-audio/{$_GET['f']} speed 0.1 &");
	// 		}else{
	// 			shell_exec("commands/heterodyne.sh {$_GET['f']} internal > /dev/null");
	// 		}
	// 	}else{
	// 		if($_GET['playback'] == "time-expansion"){
	// 			shell_exec("commands/timeExpansion.sh {$_GET['f']} > /dev/null");
	// 		}else{
	// 			shell_exec("commands/heterodyne.sh {$_GET['f']} external > /dev/null");
	// 		}
	// 	}
	// }
?>
<html>
	<head>
		<title>Playback</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script src="js/playback.js" type="text/javascript"></script>
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
					<a class="navbar-brand" href="index.php">BatPi</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav">
						<li><a id="shutdown">Shutdown</a></li>
						<li><a href="settings.php">Settings</a></li>
					</ul>
					<ul class="nav navbar-nav side-nav">
						<h4>Audio files</h3>
						<li><a id="live-audio" class="audiofile">Live</a></li>
						<?php
						  $files = scandir(getcwd() . '/audiofiles', SCANDIR_SORT_DESCENDING);
							foreach($files as $key => $value){
								if(strpos($value, ".wav") !== false && $value != "liveSpec.wav"){
									echo("<li><a class='audiofile'>{$value}</a></li>");
								}
							}
						?>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10">
					<div class="content actions">
						<div class="header-div">
							<p>Playback</p>
							<ul class="header-options nav navbar-nav">
								<li>
									<a class="header-option header-option-active" value="time-expansion">Time Expansion</a>
								</li>
								<li>
									<a class="header-option" value="heterodyne">Heterodyne</a>
								</li>
							</ul>
						</div>
						<div class="row output-options">
							<div class="col-sm-6">
								<img class="img-button" src="images/external-speakers.png" value="Dectector's Speakers">
							</div>
							<div class="col-sm-6">
								<img class="img-button" src="images/internal-speakers.png" value="Internal Speakers">
							</div>
						</div>
						<div>
						<label for="frequency" class="frequency-control">Frequency: </label>
						<input class="form-control frequency-control" id="frequency" name="frequency" value="32000" type="number" min="20000">
						<a id='speaker-status'>Current: Detector's Speakers</a>
						<button id="stop_action" value="time-expansion">Stop</button>
						</div>
						<span>Amplify:<input type="checkbox" id="amplify"></span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

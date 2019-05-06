<html>
	<head>
		<title>BatPi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script src="js/index.js" type="text/javascript"></script>
	</head>
	<body>
		<?php
			$audio = true;
			$link = true;
			include("includes/navigation.php");
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10 content-container">
					<div class="content actions">
						<div class="header-div">
							<img src="images/location.png" id="gps_status" class="greyed">
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
								<a href="playback.php"><button class="option">Playback</button></a>
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
					<div class="col-md-4 col-sm-offset-4" id="detector_status">
						 <h4 id="detector_status_type">Sound Activated Recording Status</h4>
						 <div id="sa_count">
						 		<b>Calls recorded: </b><span id="sa_recorded">0</span>
					 	 </div>
						 <button id="detector_status_stop">Stop</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

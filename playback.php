
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
								<img class="img-button" src="images/external-speakers.png" value="Detector's Speakers">
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

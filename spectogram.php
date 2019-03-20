<html>
	<head>
		<title>Spectrogram</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script>
			var foundPlayingFile = false;
			staticElements = 2;
			$(document).ready(function(){
				addPageButtons();
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					$(event.target).addClass("selected");
					if(source == "Live"){
						$.post('endpoint.php', {live_spectrogram: true}, function(data){
							setInterval(function(){
									var randInt = Math.random();
									$("#spectrogram-img").attr("src", "spec.png?v=" + randInt);
							}, 4000);
						});
					}else{
						$.post('endpoint.php', {spectrogram: source}, function(data){
							console.log(data);
							$("#spectrogram-img").attr("src", "spec.png?v=" + new Date().getTime());
						});
					}
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
					<a class="navbar-brand" href="index.php">BatPi</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav side-nav">
						<h4>Audio files</h3>
						<li><a class="audiofile">Live</a></li>
						<?php
							$files = scandir(getcwd() . '/audiofiles', SCANDIR_SORT_DESCENDING);
							foreach($files as $key => $value){
								if(strpos($value, ".wav") !== false && $value != "liveSpec.wav"){
									echo("<li><a class='audiofile'>{$value}</a></li>");
								}
							}
						?>
					</ul>
					<ul class="nav navbar-nav">
						<li><a id="shutdown">Shutdown</a></li>
						<li><a href="settings.php">Settings</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10">
					<div class="content">
						<div class="header-div">
							<p>Spectrogram</p>
						</div>
						<div>
							<?php
								$lastChange = @filemtime('spec.png');
								echo("<img id='spectrogram-img' src='spec.png?v={$lastChange}'>");
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

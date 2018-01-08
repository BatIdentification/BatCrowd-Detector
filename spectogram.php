<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
		if(isset($_GET['f'])){
			if($_GET['f'] == "Live"){
				shell_exec("commands/liveSpectrogram.sh &");
			}elseif(file_exists($_GET['f'])){
				if(!file_exists("spectrogram-images/{$_GET['f']}.png")){
					shell_exec("sox /var/www/{$_GET["f"]} -n remix 1 rate 192k spectrogram -o /var/www/spectrogram-images/{$_GET['f']}.png >> log.txt & wait; cp 'spectrogram-images/{$_GET['f']}.png' spec.png");
				}else{
					shell_exec("cp -p 'spectrogram-images/{$_GET['f']}.png' spec.png");	
				}
			}			
		}
	?>
	<head>
		<title>Spectrogram</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<?php
			$fileName = isset($_GET['f']) ? $_GET['f'] : "";
			echo("<script>fileName='$fileName';</script>");
		?>
		<script>
			var foundPlayingFile = false;
			staticElements = 2;
			$(document).ready(function(){
				addPageButtons();
				if(fileName != ""){
					$(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
				}
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					window.location = "?f=" + source;
				})
				setInterval(function(){
					var randInt = Math.random();
					$("#spectrogram-img").attr("src", "spec.png?v=" + randInt);
				}, 2000);
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
					<a class="navbar-brand">Spectrogram display</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav side-nav">
						<h4>Audio files</h3>
						<li><a class="audiofile">Live</a></li>
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
						<li><a href="index.php">Home</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10">
					<div class="content">
						<img id="spectrogram-img" src="spec.png">
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

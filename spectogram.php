<html>
	<head>
		<title>Spectogram</title>
		<link rel="stylesheet" href="style.css?v=0.11">
		<script src="jquery-3.2.1.min.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
				$(".audiofile").click(function(event){
					
					source = $(event.target)[0].innerHTML;
					console.log($(event.target));
					audio = new Audio(source);
					console.log(audio);
					audio.play();
				})
			})
		</script>
	</head>
	<body>	
		<div class="content">
			<h3 class="heading">Spectogram display</h3>
			<div class="actions">
				<a href="index.php" class="back-button">Back</a>
				<img id="spectogram-img">
			</div>
		</div>
		<div class="side-bar">
			<h3 class="subheading">Audio files</h3>
			<a id="live-button">Live</a>
			<?php
				$files = scandir(getcwd());
				foreach($files as $key => $value){
					if(strpos($value, ".wav") !== false){
						echo("<a class='audiofile'>{$value}</a>");
					}
				}
			?>
		</div>
	</body>
</html>
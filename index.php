<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
		if(isset($_GET['recordingStart'])){
				shell_exec("/var/www/commands/startRecording.sh");
		}elseif(isset($_GET['recordingStop'])){
				shell_exec("pkill -f rec");
		}
    ?>
	<head>
		<title>BatPi</title>
		<link rel="stylesheet" href="style.css?v=0.1">
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
			<h3 class="heading">BatPi Interface</h3>
			<div class="actions">
				<table class="options">
					<tr>
						<td><a href="?recordingStart"><button class="option">Start Recording</button></a></td>
						<td><button class="option">Start Sound activated recording</button></td>
						<td><a href="timeExpansion.php"><button class="option">Time Expansion</button></a></td>
					</tr>
					<tr>
						<td><a href="?recordingStop"><button class="option">Stop Recording</button></a></td>
						<td><a href=""><button class="option">Stop sound activated recording</button></a></td>
						<td><a href="spectogram.php"><button class="option">Spectrogram display</button></a></t>
					</tr>
				</table>
			</div>
		</div>
		<div class="side-bar">
			<h3 class="subheading">Audio files</h3>
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

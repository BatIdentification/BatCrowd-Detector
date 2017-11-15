<html>
	<?php
		if(isset($_GET['recordingStart'])){
				shell_exec("/var/www/commands/startRecording.sh");
		}elseif(isset($_GET['recordingStop'])){
				shell_exec("pkill -f arecord");
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
						<td><button class="option"><a href="?recordingStart">Start Recording</a></button></td>
						<td><button class="option">Real-time frequency display</button></td>
						<td><button class="option" id="shut-down">Shut Down</button></td>
					</tr>
					<tr>
						<td><button class="option"><a href="?recordingStop">Stop Recording</a></button></td>
						<td><button class="option">Spectrogram display</button></td>
						<td><button class="option">Time expansion audio</button></td>
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
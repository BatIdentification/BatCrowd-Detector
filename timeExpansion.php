<html>
	<head>
		<title>Time expansion</title>
		<link rel="stylesheet" href="style.css?v=0.11">
		<script src="jquery-3.2.1.min.js" type="text/javascript"></script>
		<?php
			echo("<script>var fileName='{$_GET['f']}';</script>");
			if(isset($_GET['f'])){
				if($_GET['f'] == "Live"){
					shell_exec("arecord -Dhw:sndrpiwsp -r 192000 -c 2 -f S16_LE | sox -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdin -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdout sinc 10k speed 0.1 | aplay -D hw:sndrpiwsp -");
				}else{
					shell_exec("sox -t wav -e signed-integer -b16 -r 192000 -c 2 {$_GET['f']} -t wav -e signed-integer -b16 -r 192000 -c 2 /dev/stdout sinc 10k speed 0.1 |  aplay -D hw:sndrpiwsp -");
				}
			}elseif(isset($_GET['stop'])){
				shell_exec("pkill -6 sox");
			}
		?>
		<script>
			$(document).ready(function(){
				if(fileName != ""){
					$(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
					$(".stop-button").css("display", "block");
				}
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					window.location = "?f=" + source;		
				})
			})
		</script>
	</head>
	<body>	
		<div class="content">
			<a href="index.php" class="back-button">Back</a>
			<h3 class="heading">Time expansion</h3>
			<div class="actions">
				<a href="?stop"><button class="stop-button">Stop</button></a>
				<img class="img-button" src="images/internal-speakers.png">
				<img class="img-button" src="images/external-speakers.png">
			</div>
		</div>
		<div class="side-bar">
			<h3 class="subheading">Audio files</h3>
			<a class="audiofile">Live</a>
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

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
		<title>Spectogram</title>
		<link rel="stylesheet" href="style.css?v=0.11">
		<script src="jquery-3.2.1.min.js" type="text/javascript"></script>
		<?php
			echo("<script>var fileName='{$_GET['f']}';</script>");
		?>
		<script>
			$(document).ready(function(){
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
		<div class="content">
			<a href="index.php" class="back-button">Back</a>
			<h3 class="heading">Spectogram display</h3>
			<div class="actions">
				<img id="spectrogram-img" src="spec.png">
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

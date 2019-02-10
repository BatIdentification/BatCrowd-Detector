<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
		if(isset($_GET['f'])){
			if($_GET['f'] == "Live"){
				shell_exec("commands/liveSpectrogram.sh > log.txt 2>&1 &");
			}elseif(file_exists('audiofiles/' . $_GET['f'])){
				if(!file_exists("spectrogram-images/{$_GET['f']}.png")){
					shell_exec("sox /var/www/audiofiles/{$_GET["f"]} -n remix 1 rate 192k spectrogram -o /var/www/spectrogram-images/{$_GET['f']}.png >> log.txt & wait; cp 'spectrogram-images/{$_GET['f']}.png' spec.png");
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
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
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
				if(fileName == "Live"){
					setInterval(function(){
						var randInt = Math.random();
						$("#spectrogram-img").attr("src", "spec.png?v=" + randInt);
					}, 4000);
				}
			})
		</script>
	</head>
	<body>
		<?php
			$audio = true;
			include("includes/navigation.php");
		?>
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

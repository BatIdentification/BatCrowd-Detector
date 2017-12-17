<html>
	<head>
		<title>Time expansion</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style2.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
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
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapseable">
					 	<span class="sr-only">Toggle navigation</span>
					 	<span class="icon-bar"></span>
					 	<span class="icon-bar"></span>
					 	<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand">Time Expansion</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav">
						<li><a href="index.php">Home</a></li>
					</ul>
					<ul class="nav navbar-nav side-nav">
						<h4>Audio files</h3>
						<?php
							$files = scandir(getcwd());
							foreach($files as $key => $value){
								if(strpos($value, ".wav") !== false){
									echo("<li><a class='audiofile'>{$value}</a></li>");
								}
							}
						?>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-10">
					<div class="content actions">
						<div class="row output-options">
							<div class="col-sm-6">
								<img class="img-button" src="images/internal-speakers.png">
							</div>
							<div class="col-sm-6">
								<img class="img-button" src="images/external-speakers.png">
							</div>
						</div>
						<div> 
						<a id='speaker-status'>Current: Internal Speakers</a>
						<a href="?stop"><button class="stop-button">Stop</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

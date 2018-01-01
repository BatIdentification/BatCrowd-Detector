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
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<?php
			$fileName = isset($_GET['f']) ? $_GET['f'] : "";
			echo("<script>var fileName='$fileName';</script>");
		?>
		<script>
			var foundPlayingFile = false;
			$(document).ready(function(){
				if(fileName != ""){
					$(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
				}
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					window.location = "?f=" + source;
				})
				$(document).on("click", "a#nextPage" , function() {
            		console.log($( this ).prevAll())
            		$(this).parent().prevAll().slice(0, -2).remove();
            		$(this).remove();
            		addNextButton();
       			});
				addNextButton();
				setInterval(function(){
					var randInt = Math.random();
					$("#spectrogram-img").attr("src", "spec.png?v=" + randInt);
				}, 2000);
			})
			function addNextButton(){
				foundElement = undefined;
				sideNav = $(".side-nav")[0];
				//Check if there are more files than the height of the bar
				if(sideNav.offsetHeight < sideNav.scrollHeight){
					$(".audiofile").each(function(){
						//Check if a file is currently playing
						if(fileName != "" && foundPlayingFile == false){	
							//Check if this <li> element corresponds to that file
							foundPlayingFile = this.innerHTML == fileName ? true : false;
							foundElement = this
						}
						rect = this.getBoundingClientRect();
						//The bottom of this <a> tag is not visible
						if(rect.bottom > sideNav.offsetHeight){
							i = $(".audiofile").index(this)	
							lastElement = (rect.bottom - sideNav.offsetHeight) < 21 ? this : $(".audiofile")[i - 1]
							//We are playing a file and it is not in this row of files, therefore we need to get rid of all of the currently visible audiofiles
							//Runs if we havn't found the file yet, if the playing audio file is the not going to be visible. Because this function found it, it will stop here but we need it to go forward one more page to see the audiofile
							if(fileName != "" && foundPlayingFile == false || fileName == this.innerHTML || foundElement == lastElement){
								$(lastElement).parent().prevAll().slice(0, -2).remove();
							}else{
								$("<li><a id='nextPage'>Next</a></li>").insertBefore($(lastElement).parent());
								return false;
							}
						}
					});
				}					
			}
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
							$files = scandir(getcwd());
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

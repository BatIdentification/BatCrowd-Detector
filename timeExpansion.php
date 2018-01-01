<html>
	<head>
		<title>Time expansion</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<?php
			echo("<script>var fileName='{$_GET['f']}';\n var status = '{$_GET['status']}'</script>");
			if(isset($_GET['f'])){
				if($_GET['status'] == "Internal Speakers"){
					shell_exec("sox {$_GET['f']} -c 2 time-expansion-audio/{$_GET['f']} speed 0.1 &");
				}else{
					shell_exec("commands/timeExpansion.sh {$_GET['f']} > /dev/null");
				}			
			}elseif(isset($_GET['stop'])){
				shell_exec("pkill -6 sox; pkill -6 aplay");
			}
		?>
		<script>
			speakerStatus = "BatPi's Speaker";
			var foundPlayingFile = false;
			$(document).ready(function(){
				if(fileName != ""){
					$(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
					$(".stop-button").css("display", "block");
				}
				if(status.includes("Internal Speakers")){
					playAudio(fileName);
					$("#speaker-status").html("Current: Internal Speakers");
					speakerStatus = "Internal Speakers";
					isLiveAvailable()
				}
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					if(speakerStatus == 'Internal Speakers' && ($(event.target).attr("class").includes("unavilable") == false)){
						$.get("time-expansion-audio/" + source)
    							.done(function() {
								audio = new Audio("time-expansion-audio/" + source);
        			 				audio.play();
    							}).fail(function() { 
       								window.location="?f=" + source + "&status=" + speakerStatus;
    							})
					}else{
						window.location = "?f=" + source + "&status=" + speakerStatus.replace("\'", "");		
					}	
				});
				$(".img-button").click(function(event){
					$("#speaker-status").html("Current: " + $(event.target).attr('value'));
					speakerStatus = $(event.target).attr('value');
					isLiveAvailable();
				});
				$(document).on("click", "a#nextPage" , function() {
            		console.log($( this ).prevAll())
            		$(this).parent().prevAll().slice(0, -2).remove();
            		$(this).remove();
            		addNextButton();
       			});
				addNextButton();
			});
			function playAudio(filePath){
				$.ajax({
    					url:'time-expansion-audio/' + filePath,
    					type:'HEAD',
    					error: function()
    					{
        					setInterval(playAudio, 500, filePath);
    					},
    					success: function()
    					{
						audio = new Audio("time-expansion-audio/" + filePath);
						audio.play();
    					}	
				});
			}
			function isLiveAvailable(){
				if(speakerStatus == "Internal Speakers"){
					$("#live-audio").addClass("unavailable");
				}else{
					$("#live-audio").removeClass("unavailable");
				}
			}
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
					<a class="navbar-brand">Time Expansion</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav">
						<li><a href="index.php">Home</a></li>
					</ul>
					<ul class="nav navbar-nav side-nav">
						<h4>Audio files</h3>
						<li><a id="live-audio" class="audiofile">Live</a></li>
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
								<img class="img-button" src="images/external-speakers.png" value="BatPis Speakers">
							</div>
							<div class="col-sm-6">
								<img class="img-button" src="images/internal-speakers.png" value="Internal Speakers">
							</div>
						</div>
						<div> 
						<a id='speaker-status'>Current: BatPi's Speakers</a>
						<a href="?stop"><button class="stop-button">Stop</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

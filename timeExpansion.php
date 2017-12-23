<html>
	<head>
		<title>Time expansion</title>
		<link rel="stylesheet" href="style.css?v=0.124">
		<script src="jquery-3.2.1.min.js" type="text/javascript"></script>
		<?php
			echo("<script>var fileName='{$_GET['f']}';\n var status = '{$_GET['status']}'</script>");
			if(isset($_GET['f'])){
				if($_GET['status'] == "External Speakers"){
					shell_exec("sox {$_GET['f']} time-expansion-audio/{$_GET['f']} speed 0.1");
				}else{
					shell_exec("commands/timeExpansion.sh {$_GET['f']} > /dev/null &");
				}			
			}elseif(isset($_GET['stop'])){
				shell_exec("pkill -6 sox");
			}
		?>
		<script>
			speakerStatus = "Internal Speakers";
			$(document).ready(function(){
				if(fileName != ""){
					$(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
					$(".stop-button").css("display", "block");
				}else if(status.includes("External Speakers")){
					console.log("Hello");
					$("#speaker-status").html("Current: External Speakers");
				}
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					if(speakerStatus == 'External Speakers'){
						$.get("time-expansion-audio/" + source)
    							.done(function() {
								audio = new Audio("time-expansion-audio/" + source);
        			 				audio.play();
    							}).fail(function() { 
       								window.location="?f=" + source + "&status=" + speakerStatus;
    							})
					}else{
						window.location = "?f=" + source + "&status=" + speakerStatus;		
					}	
				});
				$(".img-button").click(function(event){
					$("#speaker-status").html("Current: " + $(event.target).attr('value'));
					speakerStatus = $(event.target).attr('value');
				});
			})
		</script>
	</head>
	<body>	
		<div class="content">
			<a href="index.php" class="back-button">Back</a>
			<h3 class="heading">Time expansion</h3>
			<div class="actions">
				<a href="?stop"><button class="stop-button">Stop</button></a>
				<img class="img-button" value="Internal Speakers" src="images/internal-speakers.png">
				<img class="img-button" value="External Speakers" src="images/external-speakers.png">
				<a id="speaker-status">Current: Internal Speakers</a>
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

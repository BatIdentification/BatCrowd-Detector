<html>
	<head>
		<title>Time expansion</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<?php
			echo("<script>fileName='{$_GET['f']}';\n var status = '{$_GET['status']}'</script>");
			if(isset($_GET['f'])){
				if($_GET['status'] == "Internal Speakers"){
					shell_exec("sox audiofiles/{$_GET['f']} -c 2 time-expansion-audio/{$_GET['f']} speed 0.1 &");
				}else{
					shell_exec("commands/timeExpansion.sh audiofiles/{$_GET['f']} > /dev/null");
				}
			}elseif(isset($_GET['stop'])){
				shell_exec("pkill -6 sox; pkill -6 aplay");
			}
		?>
		<script>
			speakerStatus = "BatPi's Speaker";
			staticElements = 2;
			$(document).ready(function(){
				addPageButtons();
				if(fileName != ""){
					$(".audiofile:contains(" + fileName + ")").eq(0).addClass("selected");
					$(".stop-button").css("display", "block");
				}
				if(status.includes("Internal Speakers")){
					$("#speaker-status").html("Current: Internal Speakers");
					speakerStatus = "Internal Speakers";
					isLiveAvailable()
					playAudio(fileName);
				}
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					if(speakerStatus == 'Internal Speakers' && ($(event.target).attr("class").includes("unavilable") == false)){
						$.ajax({
							url: "time-expansion-audio/" + source,
							type: 'HEAD',
    							error: function() {
								window.location="?f=" + source + "&status=" + speakerStatus;
    							},
							success: function() {
								audio = new Audio("time-expansion-audio/" + source);
        			 				audio.play();
    							}
    						});
					}else{
						if(source == "Live" & document.getElementById('amplify').checked){
							source = "Live-Amplify"
						}
						window.location = "?f=" + source + "&status=" + speakerStatus.replace("\'", "");
					}
				});
				$(".img-button").click(function(event){
					$("#speaker-status").html("Current: " + $(event.target).attr('value'));
					speakerStatus = $(event.target).attr('value');
					isLiveAvailable();
				});
			});
			function playAudio(filePath){
				$.ajax({
    					url:'time-expansion-audio/' + filePath,
    					type:'HEAD',
    					error: function()
    					{
        					setInterval(playAudio, 1000, filePath);
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
					<div class="content actions">
						<div class="header-div">
							<p>Time-Expansion Playback</p>
						</div>
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
						<span>Amplify:<input type="checkbox" id="amplify"></span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

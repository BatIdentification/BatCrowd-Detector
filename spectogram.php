<html>
	<head>
		<title>Spectrogram</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script>
			var foundPlayingFile = false;
			staticElements = 2;
			$(document).ready(function(){
				addPageButtons();
				$(".audiofile").click(function(event){
					source = $(event.target)[0].innerHTML;
					$(event.target).addClass("selected");
					if(source == "Live"){
						$.post('endpoint.php', {live_spectrogram: true}, function(data){
							setInterval(function(){
									var randInt = Math.random();
									$("#spectrogram-img").attr("src", "spec.png?v=" + randInt);
							}, 4000);
						});
					}else{
						$.post('endpoint.php', {spectrogram: source}, function(data){
							console.log(data);
							$("#spectrogram-img").attr("src", "spec.png?v=" + new Date().getTime());
						});
					}
				})
			})
		</script>
	</head>
	<body>
		<?php
			$audio = true;
			$liveAvailable = true;
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

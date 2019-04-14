<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
 		if(isset($_POST['password']) && isset($_POST['ssid'])){
			$newConfig = 'network={ ssid="'.$_POST['ssid'].'" psk="'.$_POST['password'].'"}';
			$file = "/etc/wpa_supplicant/wpa_supplicant.conf";
			file_put_contents($file, $newConfig, FILE_APPEND);
		}
	?>
	<head>
		<title>BatPi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
				$(".new-network, .popup .btn-danger").click(function(){
					$("#ssid").val($(this).parents("tr").children().eq(0).text());
					$(".popup").toggle();
				})
				$(".setting-option:not(.seleted)").click(function(){
					 $.post("commands.php", {toggleWifi: true});
				});
				$.post('endpoint.php', {networks: true}, function(data){
					response = jQuery.parseJSON(data);
					for(var i = 0; i < response['networks'].length; i++){
						current = response['networks'][i]
						networkRow = $(`<tr><td>${current[0]}</td><td>${current[1]}</td><td><a class='new-network'>${current[2]}</a></td></tr>`);
						$("#available-networks").append($(networkRow));
					}
				})
			})
		</script>
	</head>
	<body>
		<?php
			include("includes/navigation.php");
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="content settings">
						<div class="header-div">
								<p>Wifi</p>
						</div>
						<div class="setting-category">
							<div>
								<p class="setting-title">Current wifi mode:</p>
								<a class="setting-option selected">Access Point</a>
								<a class="setting-option">Client</a>
							</div>
							<div>
								<p class="setting-title">Available networks</p>
								<table id="available-networks">
										<tr>
											<th>Name</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="popup">
			<div class="content">
				<div class="header-div">
						<p>Add new network</p>
				</div>
				<div class="popup-content">
					<form method="POST" action="">
						<div class="form-group">
							<p>Please enter the wifi password:</p>
							<br>
							<input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="Network password">
							<input type="hidden" name="ssid" id="ssid">
						</div>
						<input type="submit" class="btn btn-primary" value="Connect">
						<input class="btn btn-danger" value="Cancel">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>

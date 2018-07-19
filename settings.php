<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
 		if(isset($_POST['password']) && isset($_POST['ssid'])){
			$newConfig = 'network={
	ssid="'.$_POST['ssid'].'"
	psk="'.$_POST['password'].'"
}';
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
					$(".popup").toggle();
					$("#ssid").val($(this).parents("tr").children().eq(0).text());					
				})
				$(".setting-option:not(.seleted)").click(function(){
					 $.post("commands.php", {toggleWifi: true});
				});
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
					<a class="navbar-brand" href="index.php">BatPi</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav">
						<li><a id="settings">Shutdown</a></li>
						<li><a id="settings" href="settings.php">Settings</a></li>
					</ul>
				</div>
			</div>
		</nav>
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
										<?php
											$command = "sudo /var/www/libraries/iw-4.9/iw wlan0 scan ap-force | grep SSID";
											$network_from_command = shell_exec($command);
											$available_networks = explode('SSID: ', $network_from_command);
											unset($available_networks[0]);
											$currentNetwork = shell_exec("/sbin/iwgetid"); 
											$i = 0;
											foreach($available_networks as $network){
												$ssid = substr($network, 0, -1);
												$ssid = rtrim($ssid);
												$isKnown = shell_exec("cat /etc/wpa_supplicant/wpa_supplicant.conf | grep '$ssid'");
												$status = ($isKnown == "" ? "New network" : "Not connected");
												$status = (strpos($currentNetwork, $ssid) !== false ? "Connected" : $status);
												$action = "";
												if($status != "Connected"){
													$action = ($status == "New network" ? "Add Network" : "Connect");
												}
												echo("<tr><td>{$ssid}</td><td>{$status}</td><td><a class='new-network'>{$action}</a></td></tr>");
											}

										?>
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

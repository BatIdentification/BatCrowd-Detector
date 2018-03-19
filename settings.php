<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
  ?>
	<head>
		<title>BatPi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<link href="dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="dist/js/bootstrap.min.js"></script>
		<script src="js/default.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
				$(".new-network").click(function(){
					$(".popup").toggle();
				})
				$(".popup .btn").click(function(){
					$(".popup").toggle();
					if(this.value == "Connect"){
						console.log("Connecting");
					}
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
										<tr>
											<td>Johnsfield</td>
											<td>New network</td>
											<td><a class="new-network">Add network</a></td>
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
					<div class="form-group">
						<p>Please enter the wifi password:</p>
						<input type="text" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="Network password">
					</div>
					<input type="submit" class="btn btn-primary" value="Connect">
					<input type="submit" class="btn btn-danger" value="Cancel">
				</div>
			</div>
		</div>
	</body>
</html>

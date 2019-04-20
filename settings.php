<html>
	<?php
		shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
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
				$("#available-networks").on("click", ".new-network", function(){
					$(".popup").toggle();
                                        $("#ssid").val($(this).parents("tr").children().eq(0).text());
				});
				$(".popup .btn-danger").click(function(){
					$(".popup").toggle();
					$("#ssid").val($(this).parents("tr").children().eq(0).text());
				})
				$(".setting-option:not(.seleted)").click(function(){
					 $.post("commands.php", {toggleWifi: true});
				});
				$.get('endpoint.php', {networks: true}, function(data){
					response = jQuery.parseJSON(data);
					for(var i = 0; i < response['networks'].length; i++){
						current = response['networks'][i]
						networkRow = $(`<tr><td>${current[0]}</td><td>${current[1]}</td><td><a class='new-network'>${current[2]}</a></td></tr>`);
						$("#available-networks").append($(networkRow));
					}
				})
				$.get('endpoint.php', {network_status: true}, function(data){
					response = jQuery.parseJSON(data);
					if(response['status'] == 0){
						$("a#ap").addClass("selected");
					}else if(response['status'] == 1){
						$("a#client").addClass("selected");
					}
				});
				$("#new_network_form").on("submit", function(e){
					e.preventDefault();
					$(".popup").toggle();
					data = $("#new_network_form").serializeArray();
					console.log(data);
					$.post("endpoint.php", {new_network_ssid: data[1]["value"], new_network_password: data[0]["value"]}, function(response){
						json = jQuery.parseJSON(response)
						if(json['success'] == 0){
							alert("Sorry, something went wrong")
						}
					})
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
					<a class="navbar-brand" href="index.php">BatPi</a>
				</div>
				<div class="collapse navbar-collapse" id="collapseable">
					<ul class="nav navbar-nav">
						<li><a id="shutdown">Shutdown</a></li>
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
							<!-- <div>
								<p class="setting-title">Current wifi mode:</p>
								<a id='ap' class="setting-option">Access Point</a>
								<a id='client' class="setting-option">Client</a>
							</div> -->
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
					<form id="new_network_form">
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

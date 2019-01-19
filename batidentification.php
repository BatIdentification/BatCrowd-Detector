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

			function handleApiResponse(data, callback){

				try{
					response = JSON.parse(data);
				}catch(err){
					$("#warning-label").show();
					setInterval($("#warning-label").hide(), 3000);
				}

				if(response['success'] == true){

					callback()

				}else{

					$("#warning-label").show();
					setInterval($("#warning-label").hide(), 3000);

				}

			}

      $(document).ready(function(){

					$.get("endpoints/env.php", function(data){

						response = JSON.parse(data);

						if(response['token'] == true){
							$(".batid-connect").hide();
							$(".batid-settings").show();
						}

					})

					$("#edit-call").submit(function(e){

						e.preventDefault();

						$.post('endpoints/calls.php', $("#edit-call").serialize(), function(data){

							handleApiResponse(data, function(){

									console.log("Success");

							});

							$(".popup").toggle();

						})

					});

					$(".option-link, .btn-danger").click(function(){
							$(".popup").toggle();
							id = $(this).attr('id')
							$("#call_id").val(id)
					})

					$(".upload-call").click(function(){

						callid = $(this).attr("id")

						var result = {}
						$.each($("#bat-call-" + callid).serializeArray(), function() {
								result[this.name] = this.value
						});

						result['call_id'] = callid;

						$.post("endpoints/upload.php", result, function(data){

							handleApiResponse(data, function(){

								console.log("Success")

							})

						})

					})

      })

    </script>
	</head>
	<body>
		<?php
			include("includes/navigation.php")
		?>
		<div class="container-fluid">
			<h5 id="warning-label">Sorry, something went wrong</h5>
			<div class="row">
				<div class="col-sm-12">
					<div class="content">
						<div class="header-div">
								<p>BatIdentification</p>
						</div>
            <div class="batid-connect">
              <img src="images/bat-icon.png" id="batidentification-logo">
              <p class="discription">BatIdentification is a citizen science website dedicated to creating a repository of bat calls from around the world</p>
              <p class="discription">Using this data we can accurate record bat populations and trends concerning them. This information is crucial to properly conserve our bat species</p>
              <p class="discription">With help from <i>citizen-scientists</i> such as yourself we can accomplish this mission</p>

              <a href="endpoints/auth.php"><button class="btn btn-success" id="connect-to-batid">Connect with BatIdentification</button></a>

              <p class="disclaimer"> By connecting the BatPi will start uploading your bat calls to BatIdentification along with their metadata(time recorded and location)<p>
              <p class="disclaimer"> Please note these settings can be changed for manual uploading </p>
            </div>
            <div class="batid-settings">

                <div class="my-calls">
                  <?php

										require_once("includes/dbconnect.php");

										$result = $db->query("SELECT rowid, date_recorded, lat, lng, url, uploaded from bat_calls");

										foreach($result as $row){

											$attr = ($row['date_recorded'] != "" && $row['lat'] != "" && $row['lng'] != "") ? '' : 'disabled';

											if($row['lat'] == NULL || $row['lng'] == NULL){
												$locationTag = '<a class="option-link" id=' . $row['rowid'] . '>Pick Location</a>';
											}else{
												$locationTag = "<i class='lat'>{$row['lat']}</i>, <i class='lng'>{$row['lng']}</i>";
											}

											echo '
												<div class="bat-call container-fluid">
													<div class="row">
														<div class="col-md-10">
															<h4>Unknown</h4>
															<span>' . $row['date_recorded'] . ' </span>
															' . $locationTag .'
															<br>
															<b>Not uploaded</b>
														</div>
														<div class="col-md-2">
															<button ' . $attr . ' class="btn btn-primary upload-call" id=' . $row['rowid'] . '>Upload</button>
														</div>
													</div>
													<form id="bat-call-' . $row['rowid'] . '">
														<input type="hidden" name="lat" value="' . $row['lat'] . '">
														<input type="hidden" name="lng" value="' . $row['lng'] . '">
														<input type="hidden" name="date_recorded" value="' . $row['date_recorded'] . '">
														<input type="hidden" name="url" value="' . $row['url'] . '">
													</form>
												</div>

											';

										}

										$db = NULL;

                  ?>
                </div>

            </div>
        	</div>
				</div>
			</div>
		</div>
		<div class="popup">
			<div class="content">
				<div class="header-div">
						<p>Where did you record the call?</p>
				</div>
				<div class="popup-content" id="latlng">

						<form id="edit-call">

							<input name="call_id" id="call_id" type="hidden">

							<label for="lat">Lat:</label>
							<div class="input-group">
								<input class="form-control" name="lat" placeholder="52.43434">
							</div>

							<label for="lng">Lng:</label>
							<div class="input-group">
								<input class="form-control" name="lng" placeholder="-6.35623">
							</div>

							<input type="submit" class="btn btn-primary" value="Confirm">
							<input class="btn btn-danger" value="Cancel">

						</form>

				</div>
			</div>
		</div>
	</body>
</html>

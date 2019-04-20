<?php

  require("../includes/LocationHandler.php");
  require("../includes/dbconnect.php");

  $handler = new LocationHandler($db);

  #Returns the most accurate lat, lng co-ordinates the detector currently ezmlm_hash

  if(isset($_GET['gps_pos'])){

    $pos = $handler->getPosition();

    echo '{"lat": ' . $pos['lat'] . ', "lng": ' . $pos['lng'] . '}';

  }

  #Updates a database with lat & lng from client
  if(isset($_POST['lat']) && isset($_POST['lng'])){

    if($handler->updateLatLng($_POST['lat'], $_POST['lng'])){
      echo ('{"success": true}');
    }else{
      echo ('{"error": "Sorry, something went wrong while updating the database"}');
    }

  }

  // What pheripheral are we using to get the location

  if(isset($_GET['gps_status'])){

    $status = $handler->getStatus();

    echo ('{"gps_status":' . $status . '}');

  }

?>

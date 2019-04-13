<?php

  require_once("../includes/dbconnect.php");

  $dir = getcwd() . '/../database/batpi.db';

  #Have to handle three situtations
  #1. Does not have a GPS module so using client
  #2. Has a GPS module which is reporting
  #3. Has a GPS module but currently does not have a fix

  #Updates a database with lat & lng from client
  if(isset($_POST['lat']) && isset($_POST['lng'])){
    $query = "UPDATE environment SET lat = :lat, lng = :lng WHERE rowid = 1";
    try{
      $stmt = $db->prepare($query);
      if($stmt != false){
        $params = ["lat" => $_POST['lat'], "lng" => $_POST['lng']];
        $stmt->execute($params);
        $stmt = null;
        echo '{"success": true}';
      }else{
        echo '{"error": "Something went wrong while connecting to the database"}';
      }
    }catch (PDOException $e){
      echo '{"error": "Something went wrong while sending location to database"}';
    }

  }

?>

<?php

  $dir = getcwd() . '/../database/batpi.db';

  if(!isset($_POST['lat']) || !isset($_POST['lng']) || !isset($_POST['call_id'])){

    header("Location: ../batidentification.php?warning=Some%20parameters%20were%20missing");

  }

  try{
      $db = new PDO("sqlite:" . $dir);
  }catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }

  $stmt = $db->prepare("UPDATE bat_calls SET lat = :lat, lng = :lng WHERE rowid = :rowid");
  $stmt->execute([':lat' => $_POST['lat'], 'lng' => $_POST['lng'], 'rowid' => $_POST['call_id']]);
  $stmt = null;

  header("Location: ../batidentification.php");

?>

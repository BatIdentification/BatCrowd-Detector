<?php

  $dir = getcwd() . '/../database/batpi.db';

  if(!isset($_POST['call_id'])){

    header("Location: ../batidentification.php?warning=Some%20parameters%20were%20missing");

  }

  try{
      $db = new PDO("sqlite:" . $dir);
  }catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }

  

?>

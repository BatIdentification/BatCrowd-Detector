<?php

  $dir = str_replace("/includes", "", __DIR__) . '/database/batpi.db';

  try{
      $db = new PDO("sqlite:" . $dir);
  }catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }

?>

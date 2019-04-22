<?php

  require_once("../includes/dbconnect.php");

  $result = $db->query("SELECT access_token FROM oauth_tokens WHERE rowid = 1");

  $return = '{"token": false}';

  foreach($result as $row){
    if($row['access_token'] != ''){
      $return = '{"token": true}';
    }
  }

  echo $return;

?>

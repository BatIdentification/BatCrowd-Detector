<?php

  require_once("../includes/dbconnect.php");

  $result = $db->query("SELECT access_token FROM oauth_tokens WHERE rowid = 1");

  foreach($result as $row){
    if($row['access_token'] != ''){
      exit('{"token": true}');
    }else{
      exit('{"token": false}');
    }
  }

?>

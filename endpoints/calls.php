<?php

  require_once("../includes/dbconnect.php");

  $dir = getcwd() . '/../database/batpi.db';

  if(!isset($_POST['call_id'])){

    exit('{"error": "missing_param", "error_description": "No call id was provided"}');

  }

  $allowed_keys = ['lat', 'lng', 'uploaded'];

  $toUpdate = array_intersect($allowed_keys, array_keys($_POST));

  if($toUpdate == []){

    exit('{"error": "missing_param", "error_description": "No values to change were specified"}');

  }else{

    $query = "UPDATE bat_calls SET";
    $params = ["rowid" => $_POST['call_id']];

    foreach($toUpdate as $param){

      $query .= ' ' . $param . ' = :' . $param . ',';
      $params[$param] = $_POST[$param];

    }

    $query = rtrim($query, ",");
    $query .= " WHERE rowid = :rowid";

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $stmt = null;

    echo('{"success": true}');

  }

?>

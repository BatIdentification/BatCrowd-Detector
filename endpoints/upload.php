<?php

  require_once("../includes/dbconnect.php");
  require_once("../vendor/autoload.php");
  require_once("../includes/apiHandler.php");

  function getCallDetails($db, $call_id){

    $results = $db->prepare("SELECT date_recorded, lat, lng, url FROM bat_calls WHERE rowid = :rowid");
    $results->execute([':rowid' => $call_id]);
    $row = $results->fetch();

    return $row;

  }

  if(!isset($_POST['call_id'])){

    exit('{"error": "param", "error_description": "No call_id was provided"}');

  }

  $handler = new batidAPI($db);

  if($handler != false){

    $call = getCallDetails($db, $_POST['call_id']);

    if($call == false){
      die('{"error": "param", "error_description": "The call id provided does not exist"}');
    }

    $file_to_upload = new CurlFile("../audiofiles/" . $call["url"], "audio/x-wav", "bat_call");

    $data = array('bat_call' => $file_to_upload, 'date_recorded' => $call['date_recorded'], 'lat' => $call['lat'], 'lng' => $call['lng']);

    $response = $handler->sendAuthRequest('upload', $data, ['Content-type: multipart/form-data']);

    $output = json_decode($response);

    if(isset($output->success)){
      $stmt = $db->prepare("UPDATE bat_calls SET uploaded = 1 WHERE rowid = :callid");

      $stmt->execute([":callid" => $_POST['call_id']]);

      $stmt = NULL;

      echo('{"success": true}');
    }else{
      echo ($output);
    }

  }else{

    echo('{"error": "wrong_env", "error_description": "No access token was found"}');

  }

?>

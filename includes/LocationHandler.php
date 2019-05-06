<?php

  #Have to handle three situtations
    #1. Does not have a GPS module so using client
    #2. Has a GPS module which is reporting
    #3. Has a GPS module but currently does not have a fix

  #Returns the status of the GPS
    #0 => Device does not have GPS
    #1 => Device has GPS and it has a fix
    #2 => Device has GPS but there is no fix

  class LocationHandler{

    function __construct($db){
      $this->db = $db;
    }

    //Status of GPS pheripherals
    function getStatus(){
      if(shell_exec("ls /dev/ttyUSB0") == "/dev/ttyUSB0"){

        $gpsd = shell_exec("timout 20 gpspipe -w -n 4");

        if(strpos($gpsd, "lat") === false){
          return 2;
        }else{
          return 1;
        }

      }else{
        return 0;
      }

    }

    function getPosition(){
        $gpsd = shell_exec("timout 25 gpspipe -w -n 8 | grep -m 1 lat");

        if(strpos($gpsd, "lat") === false){

          #Have to return lat, lng from database
          $query = "SELECT lat, lng FROM environment WHERE rowid = 1";
          $result = $this->db->query($query);

          foreach ($result as $row){
            return ["lat"=>$row['lat'], "lng"=>$row['lng']];
          }

        }else{

          var_dump($gpsd);

          $decoded = json_decode($gpsd);
          return ["lat"=>$decoded->lat, "lng"=>$decoded->lon];

        }
    }

    function updateLatLng($lat, $lng){
      $query = "UPDATE environment SET lat = :lat, lng = :lng WHERE rowid = 1";
      try{
        $stmt = $this->db->prepare($query);
        if($stmt != false){
          $params = ["lat" => $lat, "lng" => $lng];
          $stmt->execute($params);
          $stmt = null;
          return true;
        }else{
          return false;
        }
      }catch (PDOException $e){
        return false;
      }
    }

  }

?>

<?php

  if(isset($_POST['num_calls'])){

    $fi = new FilesystemIterator(getcwd() . '/audiofiles', FilesystemIterator::SKIP_DOTS);
    $count = iterator_count($fi);
    echo('{"num_calls":' . $count . '}');

  }

  if(isset($_POST['networks'])){

    $networks = array();

    $command = "sudo /var/www/libraries/iw-4.9/iw wlan0 scan ap-force | grep SSID";
    $network_from_command = shell_exec($command);
    $available_networks = explode('SSID: ', $network_from_command);
    unset($available_networks[0]);
    $currentNetwork = shell_exec("/sbin/iwgetid");
    $i = 0;
    foreach($available_networks as $network){
      $ssid = substr($network, 0, -1);
      $ssid = rtrim($ssid);
      $isKnown = shell_exec("cat /etc/wpa_supplicant/wpa_supplicant.conf | grep '$ssid'");
      $status = ($isKnown == "" ? "New network" : "Not connected");
      $status = (strpos($currentNetwork, $ssid) !== false ? "Connected" : $status);
      $action = "";
      if($status != "Connected"){
        $action = ($status == "New network" ? "Add Network" : "Connect");
      }
      $networks[] = [$ssid, $status, $action];
    }

    echo('{"networks": ' . json_encode($networks) . '}');

  }

?>

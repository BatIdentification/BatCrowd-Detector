<?php
  if(isset($_POST['num_calls'])){

    $fi = new FilesystemIterator(getcwd() . '/audiofiles', FilesystemIterator::SKIP_DOTS);
    $count = iterator_count($fi);
    echo('{"num_calls":' . $count . '}');

  }
  if(isset($_GET['network_status'])){
	if(shell_exec('systemctl status hostapd | grep "(running)"') != ""){
		echo '{"status": 0}';
	}else{
		echo '{"status": 1}';
	}
  }

  if(isset($_POST["new_network_ssid"]) && isset($_POST["new_network_password"])){
     $newConfig = 'network={ ssid="'.$_POST['new_network_ssid'].'" psk="'.$_POST['new_network_password'].'"}';
     $file = "/etc/wpa_supplicant/wpa_supplicant.conf";
     file_put_contents($file, $newConfig, FILE_APPEND);
     echo '{"status": 1}';
  }

  if(isset($_GET['networks'])){

    $networks = array();

    if(intval(shell_exec("cat /etc/debian_version")) > 6.9){
    	$command = "sudo /sbin/iw wlan0 scan ap-force | grep SSID";
    }else{
       $dir = getcwd();
       $command = "sudo {$dir}/libraries/iw-4.9/iw wlan0 scan ap-force | grep SSID";
    }

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

  if(isset($_GET['status'])){

    // Code has to be made omn the Raspberry Pi

   $status = rtrim(shell_exec(". commands/status.sh"));

   $status = $status != "" ? $status : 0;

   echo('{"status":' . $status . '}');

  }

?>

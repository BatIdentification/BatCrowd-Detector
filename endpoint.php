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

  if(isset($_POST['spectrogram'])){

    if(!file_exists("spectrogram-images/{$_POST['spectrogram']}.png")){
      shell_exec("sox audiofiles/{$_POST['spectrogram']} -n remix 1 rate 192k spectrogram -o spectrogram-images/{$_POST['spectrogram']}.png >> log.txt & wait; cp 'spectrogram-images/{$_POST['spectrogram']}.png' spec.png");
      echo("Created new spectrogram");
    }else{
      shell_exec("cp -p 'spectrogram-images/{$_POST['spectrogram']}.png' spec.png");
      echo("Copied old spectrogram");
    }

  }

  if(isset($_POST['live_spectrogram'])){

    if($_POST['live_spectrogram'] == "true"){
      shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
      shell_exec("commands/liveSpectrogram.sh > log.txt 2>&1 &");
    }elseif($_POST['live_spectrogram'] == "false"){
      shell_exec("pkill -f /bin/bash\ commands/liveSpectrogram.sh");
      echo('{"success": "true"}');
    }

  }

  if(isset($_POST['time_expansion'])){

    if(isset($_POST['output'])){

      if($_POST['output'] == "0"){
        shell_exec("sox audiofiles/{$_POST['time_expansion']} -c 2 time-expansion-audio/{$_POST['time_expansion']} speed 0.1");
        die('{"success": true}');
      }elseif($_POST['output'] == "1"){
        shell_exec("commands/timeExpansion.sh {$_POST['time_expansion']} > /dev/null &");
	die('{"success": true}');
     }

    }else{
      echo('{"error": "missing parameter", "error_description": "Please provide where you want the sound to be outputed to"}');
    }

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

?>

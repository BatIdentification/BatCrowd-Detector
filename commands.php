<?php

	//We set the PI's time to whatever the client sent. By doing this we don't need a RTC
	if(isset($_POST['time'])){
		shell_exec("sudo /bin/date -s '{$_POST['time']}'");
	}

	//Shutdown the Pi
	if(isset($_POST['shutdown'])){
		shell_exec("sudo /sbin/shutdown now");
	}elseif(isset($_POST['toggleWifi'])){
		shell_exec("commands/toggleWifi.sh");
	}

	//Enables or disables sound_activated recording
	if(isset($_POST['sound_activated'])){

		if($_POST['sound_activated'] == "true"){

			shell_exec("/var/www/commands/startSoundActivatedRecording.sh &");

		}else{
			echo("Stopping ");
			shell_exec("pkill -f /var/www/commands/startSoundActivatedRecording.sh; pkill -9 rec;");
			//Delete all the empty wav files so they don't clutter up the directory
			shell_exec("find *.wav -type f -size -100 -delete");

		}

	}

	//Enables or disables normal recording

	if(isset($_POST['recording'])){
		if($_POST['recording'] == "true"){
			shell_exec(". /var/www/commands/startRecording.sh &");

		}else{

			shell_exec("pkill rec &");
                }
	}

	//Enables and disables timeExpansion recording_button

	if(isset($_POST['timeExpansion'])){

		if($_POST['timeExpansion'] == "false"){

			shell_exec("pkill -6 sox; pkill -6 aplay");

		}

	}
?>

<?php

	//We set the PI's time to whatever the client sent. By doing this we don't need a RTC
	if(isset($_POST['time'])){
		shell_exec("sudo /bin/date -s '{$_POST['time']}'");
	}

	//Shutdown the Pi
	if(isset($_POST['shutdown'])){
		shell_exec("sudo /sbin/shutdown now");
	}

	//Enables or disables sound_activated recording
	if(isset($_POST['sound_activated'])){

		if($_POST['sound_activated'] == True){

			shell_exec("/var/www/commands/startSoundActivatedRecording.sh");

		}else{

			shell_exec("pkill -f startSoundActivatedRecording.sh; pkill rec;");
			//Delete all the empty wav files so they don't clutter up the directory
			shell_exec("find *.wav -type f -size -100 -delete");

		}

	}

	//Enables or disables normal recording

	if(isset($_POST['recording'])){

		if($_POST['recording'] == True){

			shell_exec("/var/www/commands/startRecording.sh");

		}else{

			shell_exec("pkill rec");

		}

	}
?>

<?php
	$config = include("config.php");
	//We set the PI's time to whatever the client sent. By doing this we don't need a RTC
	if(isset($_POST['time']) && $config["clientSetsTime"]){
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

	//Starts and stops timeExpansion playback
	//Has to handle following events
	//Stop -> If true, stop whatever is running
	//Internal or external -> Either output directly or create a file

	if(isset($_POST['time-expansion']) && isset($_POST['source'])){

		$output = isset($_POST['output']) ? $_POST['output'] : "External";

		if($_POST['time-expansion'] == false){
			shell_exec("pkill -6 sox; pkill -6 aplay");
			echo('{"success": true}');
		}else{

			if($_POST['output'] == "Internal"){

				shell_exec("sox audiofiles/{$_POST['source']} -c 2 time-expansion-audio/{$_POST['source']} speed 0.1 &");
				echo('{"success": true}');

			}elseif($_POST['output'] == "External"){

				// shell_exec("commands/timeExpansion.sh {$_POST['source']} > /dev/null");
				echo('{"success": true}');

			}

		}

	}

	if(isset($_POST['heterodyne']) && isset($_POST['source'])){

		$output = isset($_POST['output']) ? $_POST['output'] : "External";

		if($_POST['heterodyne'] == false){
			shell_exec("pkill -6 sox; pkill -6 aplay");
			echo('{"success": true}');
		}else{

			if($_POST['output'] == "Internal"){

				shell_exec("sox audiofiles/{$_POST['source']} -c 2 heterodyne-audio/{$_POST['source']} speed 0.1 &");
				echo('{"success": true}');

			}elseif($_POST['output' == "External"]){

				$frequency = isset($_POST['$frequency']) ? $_POST['$frequency'] : 32000;

				shell_exec("commands/bat-heterodyne/heterodyne.sh -i {$_POST['source']} {$frequency} -o /dev/stdout |  aplay -D hw:sndrpiwsp -");
				echo('{"success": true}');

			}

		}

	}
?>

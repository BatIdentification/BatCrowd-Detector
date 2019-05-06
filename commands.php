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

			shell_exec("/var/www/html/commands/startSoundActivatedRecording.sh &");

		}else{
			echo("Stopping ");
			shell_exec("pkill -f /var/www/html/commands/startSoundActivatedRecording.sh; pkill -9 rec;");
			//Delete all the empty wav files so they don't clutter up the directory
			shell_exec("find *.wav -type f -size -100 -delete");

		}

	}

	//Enables or disables normal recording

	if(isset($_POST['recording'])){

		if($_POST['recording'] == "true"){

			$output = shell_exec("/var/www/html/commands/startRecording.sh");

		}else{

			shell_exec("pkill rec &");

    }

	}

	//Starts and stops timeExpansion playback
	//Has to handle following events
	//Stop -> If true, stop whatever is running
	//Internal or external -> Either output directly or create a file

	if(isset($_POST['time-expansion'])){

		$output = isset($_POST['output']) ? $_POST['output'] : "External";

		if($_POST['time-expansion'] == "false"){
			shell_exec("pkill -6 sox; pkill -6 aplay");
			echo('{"success": true}');
		}elseif(isset($_POST['source'])){

			if($_POST['output'] == "Internal"){

				shell_exec("sox audiofiles/{$_POST['source']} -c 2 time-expansion-audio/{$_POST['source']} speed 0.1 &");
				echo('{"success": true}');

			}elseif($_POST['output'] == "External"){

			 	shell_exec("commands/timeExpansion.sh {$_POST['source']} > /dev/null");
				echo('{"success": true}');

			}

		}else{
			echo '{"error": "Insufficent data provided"}';
		}

	}

	if(isset($_POST['heterodyne']) && isset($_POST['source'])){

		$output = isset($_POST['output']) ? $_POST['output'] : "External";

		if($_POST['heterodyne'] == false){
			shell_exec("pkill -6 sox; pkill -6 aplay");
			echo('{"success": true}');
		}else{

			$frequency = isset($_POST['$frequency']) ? $_POST['$frequency'] : 50000;

			if($_POST['output'] == "Internal"){

				shell_exec("commands/bat-heterodyne/heterodyne.sh -i audiofiles/{$_POST['source']} -f {$frequency} -o heterodyne-audio/{$_POST['source']}");
				echo('{"success": true}');

			}elseif($_POST['output'] == "External"){

				shell_exec("commands/bat-heterodyne/heterodyne.sh -i audiofiles/{$_POST['source']} -f {$frequency} -p");
				echo('{"success": true}');

			}

		}

	}

	//Spectrograms
	if(isset($_POST['spectrogram'])){

    if(!file_exists("spectrogram-images/{$_POST['spectrogram']}.png")){
      shell_exec("sox audiofiles/{$_POST['spectrogram']} -n remix 1 rate 192k spectrogram -o spectrogram-images/{$_POST['spectrogram']}.png & wait; cp 'spectrogram-images/{$_POST['spectrogram']}.png' spec.png");
      echo("Created new spectrogram");
    }else{
      shell_exec("cp -p 'spectrogram-images/{$_POST['spectrogram']}.png' spec.png");
      echo("Copied old spectrogram");
    }

  }

  if(isset($_POST['live_spectrogram'])){

    if($_POST['live_spectrogram'] == "true"){
      shell_exec("pkill -f commands/liveSpectrogram.sh");
      shell_exec("commands/liveSpectrogram.sh > log.txt 2>&1 &");
			echo('{"success": "true"}');
    }elseif($_POST['live_spectrogram'] == "false"){
      shell_exec("pkill -f commands/liveSpectrogram.sh");
      echo('{"success": "true"}');
    }

  }
?>

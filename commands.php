<?php
	if(isset($_POST['time'])){
		shell_exec("sudo /bin/date -s '{$_POST['time']}'");
	}elseif(isset($_POST['shutdown'])){
		shell_exec("sudo /sbin/shutdown now");
	}elseif(isset($_POST['toggleWifi'])){
		shell_exec("commands/toggleWifi.sh");
	}
?>

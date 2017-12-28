<?php
	echo("Hello");
	if(isset($_POST['time'])){
		shell_exec("sudo /bin/date -s '{$_POST['time']}'");
	}
?>

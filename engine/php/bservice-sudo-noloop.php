<?php 
	//clear magick tmp files
	$output=null;
	$retval=null;
	//exec('find /tmp -type f -mmin +15 -delete', $output, $retval);
	exec('find /tmp -name "magick*" -type f -mmin +5 -exec rm -f {} \;', $output, $retval);
	
	$noloop = 1;
	$bg_action = 'sudo_bg_service';
	include 'bservice.php';
?>
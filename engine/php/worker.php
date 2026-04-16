<?php
	$delay = 60;
	if (isset($argv[1]) && $argv[1]) {
		$delay = intval($argv);
	}
	while(true){
		$timestamp = date('Y-m-d H:i:s');
		// echo "\n[". $timestamp ."] Worker Execution \n";
		exec("php " . dirname(__FILE__) ."/bg_processes.php" );
		sleep( $delay );
	}
?>
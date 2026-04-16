<?php 
	
	$_GET['action'] = "audit";
	$_GET['todo'] = "background_app_update_in_background";
	//$_GET['todo'] = "load_database_tables";
	$_GET['default'] = "default";
	$global_debug = 1;
	include 'ajax_request_processing_script.php';
?>
<?php
	$_GET['default'] = "default";
	$_GET['action'] = "nwp_queue";
	$_GET['todo'] = "execute";
	$_GET['nwp_action'] = "job_queue";
	$_GET['nwp_todo'] = "run_all_background_processes";
	include 'ajax_request_processing_script.php';
?>
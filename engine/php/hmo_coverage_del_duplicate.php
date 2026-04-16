<?php 
	// print_r( array_chunk( preg_split('(:::|@@)', $argv[1] ), 2 ) );exit;
	
	//error_reporting (~E_ALL);	//hides error
	exit;
	//$argv[1] = "action@@audit:::todo@@sync:::user_id@@35991362173:::no_session@@1:::reference@@1:::show_window@@1:::key@@";
	$_GET['action'] = "hmo_coverage";
	$_GET['todo'] = "flag_unmatched";
	$_GET['default'] = "default";
	$_POST = array();
	include 'ajax_request_processing_script.php';
?>
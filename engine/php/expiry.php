<?php 
	// print_r( array_chunk( preg_split('(:::|@@)', $argv[1] ), 2 ) );exit;
	
	//error_reporting (~E_ALL);	//hides error
	 

	//$argv[1] = "action@@audit:::todo@@sync:::user_id@@35991362173:::no_session@@1:::reference@@1:::show_window@@1:::key@@";
	$_GET['action'] = "inventory_ent";
	$_GET['todo'] = "update_expiry_batch";
	$_GET['default'] = "default";
	$_POST = array();
	include 'ajax_request_processing_script.php';
?>
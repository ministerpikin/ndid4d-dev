<?php 
	/**
	 * IPIN DATA SYNC
	 *
	 * @used in  				plugins/nwp_ipin_sync
	 */
	$background_data_upload_only_session = 1;
	$GLOBALS["request_g"] = $_GET;
	$_GET = array();
	
	$_GET['default'] = "default";
	$_GET['action'] = 'nwp_ipin_sync';
	$_GET['todo'] = 'execute';
	$_GET['nwp_action'] = 'ipin_sync_ping_log';
	
	if( isset( $GLOBALS["request_g"]["nwp_request"] ) && $GLOBALS["request_g"]["nwp_request"] ){
		$_GET['nwp_todo'] = 'process_request';
	}else{
		//used by bg process, when dedicated cron job is created
		$_GET['nwp_todo'] = 'bg_synchronized_dedicated';
	}
	$_GET["development_mode_off"] = 1;
	include 'ajax_request_processing_script.php';
?>
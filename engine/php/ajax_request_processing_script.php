<?php
	//CONFIGURATION
	if( ! isset( $pagepointer ) )$pagepointer = dirname( dirname( __FILE__ ) ) . '/';
    $display_pagepointer = '';
	if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
		require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
		cNwp_app_core::app( [ 'pagepointer' => $pagepointer ] );
	}else{
		require_once "ajax_request_processing_script_legacy.php";
	}


?>
<?php 
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */
	 
	$_GET['action'] = "audit";
	$_GET['todo'] = "refresh_cache_in_background";
	$_GET['default'] = "default";
	include 'ajax_request_processing_script.php';
?>
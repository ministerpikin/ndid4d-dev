<?php 
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */
	 
	$_GET['action'] = "audit";
	$_GET['todo'] = "sync";
	$_GET['default'] = "default";
	include 'ajax_request_processing_script.php';
?>
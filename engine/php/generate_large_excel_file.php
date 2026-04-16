<?php 
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */
	 
	$_GET['action'] = "myexcel";
	$_GET['todo'] = "generate_large_excel_file";
	$_GET['default'] = "default";
	include 'ajax_request_processing_script.php';
	exit;
?>
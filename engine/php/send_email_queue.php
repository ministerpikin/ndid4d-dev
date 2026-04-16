<?php 
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */
	 
	$_GET['action'] = "emails";
	$_GET['todo'] = "send_mail_queue";
	$_GET['default'] = "default";
	include 'ajax_request_processing_script.php';
?>
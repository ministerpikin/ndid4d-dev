<?php
	//$skip_required_files = 1;
	//$pagepointer = '../';
    //$display_pagepointer = '';
	$GLOBALS["request_g"] = $_GET;
	
	$_GET['default'] = "default";
	$_GET['action'] = 'api';
	$_GET['todo'] = 'execute_api';

	$_GET['default'] = "default";
	$_GET['action'] = 'nwp_health';
	$_GET['todo'] = 'execute';
	$_GET['nwp_action'] = 'tele_health_connect';
	//$_GET['nwp_action'] = 'tele_health';
	$_GET['nwp_todo'] = 'process_request';
	$_GET["development_mode_off"] = 1;

	if( isset( $_GET[ 'daction' ] ) && isset( $_GET[ 'dtodo' ] ) ){
		$_GET['action'] = $_GET[ 'daction' ];
		$_GET['todo'] = $_GET[ 'dtodo' ];
		if( isset( $_GET[ 'dnwp_action' ] ) && isset( $_GET[ 'dnwp_todo' ] ) ){
			$_GET['nwp_action'] = $_GET[ 'dnwp_action' ];
			$_GET['nwp_todo'] = $_GET[ 'dnwp_todo' ];
		}
	}
	
	if( ! defined('SET_DEFAULT_USER') && isset( $_POST["uid"] ) && $_POST["uid"] ){
		$defid = preg_replace('/[^A-Za-z0-9]/', '', $_POST["uid"] );
		define( 'SET_DEFAULT_USER', $defid );
		unset( $defid );
	}
	
	include '../php/ajax_request_processing_script.php';
?>
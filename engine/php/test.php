<?php 
	
	/**
	 * Framtech Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Farmtech Process AJAX Request File
	|--------------------------------------------------------------------------
	|
	| Receives and processes all AJAX Server requests from the clients
	|
	*/
	
	
	//CONFIGURATION
	$pagepointer = '../';
	require_once $pagepointer . "settings/Config.php";
	require_once $pagepointer . "settings/Setup.php";
	
	$file_information = pathinfo( 'C:\Users\OD1\Downloads\jan-2019.xlsx' );
	print_r( $file_information ); exit;
	
	$callStartTime = microtime(true);
	echo '<br /><br />before<br /><br />';
	//run_in_background( "bprocess", 0, 
	run_in_background( "convert", 0, 
		array( 
			"action" => 'import_items', 
			"todo" => "test", 
			"user_id" => '21', 
			"reference" => '99', 
			//"show_window" => 1, 
			"wait_for_execution" => 1, 
			"argument3" => 'C:\Users\OD1\Downloads\jan-2019.xlsx',
			"argument4" => 'C:\Users\OD1\Downloads\jan-2019-amen2.csv',
		) 
	);
	echo '<br /><br />after<br /><br />';
	
	$callEndTime = microtime(true);
	$callTime = $callEndTime - $callStartTime;
	
	echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds<br /><br />";
	// Echo memory usage
	echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB<br /><br />";
	
	//run_in_background( "refresh_items_category_bank" );
	exit;
	$page = 'ajax_request_processing_script';
	
	//INCLUDE CLASSES
	$class = $classes[$page];
	foreach( $class as $required_php_file ){
		require_once $pagepointer . "classes/" . $required_php_file . ".php";
	}
	
	//REQUEST FILE OUTPUT
	if((isset($_GET['action']) && isset($_GET['todo'])) && $_GET['todo'] && $_GET['action']){
		$classname = $_GET['action'];
		$action = $_GET['todo'];
		
		if(isset($_GET['module']))$_SESSION['module'] = $_GET['module'];
		
		$settings = array(
			'pagepointer' => $pagepointer ,
			'user_cert' => $current_user_session_details ,
			'database_connection' => $database_connection ,
			'database_name' => $database_name , 
			'classname' => $classname , 
			'action' => $action ,
			'skip_authentication' => true,
		);
		echo reuse_class( $settings );
		
		exit;
	}
?>
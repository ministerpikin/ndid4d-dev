<?php
	error_reporting (~E_ALL);	//hides error
	include 'app_request_processor.php';
	session_write_close();
	
	//$pagepointer = dirname( dirname( __FILE__ ) ) . "/";
	//echo $pagepointer;
	
	$settings = array(
		'pagepointer' => $pagepointer,
		'user_cert' => $current_user_session_details ,
		'database_connection' => $database_connection ,
		'database_name' => $database_name , 
		'classname' => 'category' , 
		'action' => 'refresh_cache',
		'language' => SELECTED_COUNTRY_LANGUAGE,
		'skip_authentication' => true,
	);
	
	$r = reuse_class( $settings );
	echo "Category Refresh Complete \n\n";
	
	$settings = array(
		'pagepointer' => $pagepointer,
		'user_cert' => $current_user_session_details ,
		'database_connection' => $database_connection ,
		'database_name' => $database_name , 
		'classname' => 'banks' , 
		'action' => 'refresh_cache',
		'language' => SELECTED_COUNTRY_LANGUAGE,
		'skip_authentication' => true,
	);
	reuse_class( $settings );
	echo "Banks Refresh Complete \n\n";
	
	$settings = array(
		'pagepointer' => $pagepointer,
		'user_cert' => $current_user_session_details ,
		'database_connection' => $database_connection ,
		'database_name' => $database_name , 
		'classname' => 'items' , 
		'action' => 'refresh_cache',
		'language' => SELECTED_COUNTRY_LANGUAGE,
		'skip_authentication' => true,
	);
	reuse_class( $settings );
	echo "Items Refresh Complete \n\n";
?>
<?php 
	$background_data_upload_only = 1;
	$background_data_upload_only_session = 1;
	
	//$pagepointer = "C:\hyella\htdocs\feyi-hyella\engine\php\\";
	
	include 'app_request_processor.php';
	session_write_close();
	
	echo 'Running Hyella Business Manager. Please do not close this window...';
	$terminate_updates = 180;
	$sequence = 0;
	$updates = 0;
	$loop = true;
	
	$settings = array(
			'pagepointer' => $pagepointer,
			'user_cert' => $current_user_session_details ,
			'database_connection' => $database_connection ,
			'database_name' => $database_name , 
			'classname' => 'emails' , 
			'action' => 'send_mail_queue' ,
			'skip_authentication' => true,
		);
		echo reuse_class( $settings );
		
		exit;
		
	$settings = array(
		'cache_key' => "bg-processor-stop",
	);
	clear_cache_for_special_values( $settings );
	
	//automatic update
	if( get_automatic_update_settings() ){
		$settings = array(
			'pagepointer' => $pagepointer,
			'user_cert' => $current_user_session_details ,
			'database_connection' => $database_connection ,
			'database_name' => $database_name , 
			'classname' => 'audit' , 
			'action' => 'background_data_upload_only' ,
			'skip_authentication' => true,
		);
		$r = reuse_class( $settings );
		
		print_r( $r );
		
		if( $r ){
			$updates = 0;
		}else{
			++$updates;
		}
	}
		
	
	stopbgp();
	function stopbgp(){
		$settings = array(
			'cache_key' => "bg-processor",
		);
		clear_cache_for_special_values( $settings );
		
		$settings = array(
			'cache_key' => "bg-processor-stop",
		);
		clear_cache_for_special_values( $settings );
		
		$settings = array(
			'cache_key' => "bg-processor-running",
		);
		clear_cache_for_special_values( $settings );
	}
?>
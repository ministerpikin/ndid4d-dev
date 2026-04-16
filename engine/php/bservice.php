<?php 
	//sc Create northwindproject binPath=C:\xampp\htdocs\feyi-hyella\engine\php\bservice.bat
	//start= demand displayname= "DisplayName"
	$background_data_upload_only = 1;
	$background_data_upload_only_session = 1;
	
	include 'app_request_processor.php';
	session_write_close();
	
	echo "\n\nIMPORTANT!!! Running NWP BG SERVICE. Please do not close this window...\n\n";
	echo "Optionally, you can minimize this window...\n\n";
	$delay = 120;
	$delay = 300;
	$terminate_op = 21600 / $delay;	//run 6 hours and then restart
	$sequence = 0;
	$loop = true;
	
	//file_put_contents( 'abc1.txt', date("d-M-Y H:i:s") );
	while( $loop ){
		$settings = array(
			'cache_key' => "bg-service-stop",
		);
		$st = get_cache_for_special_values( $settings );
		if( $sequence >= $terminate_op || $st ){
		//if( $sequence >= $terminate_op || get_cache_for_special_values( $settings ) ){
			$loop = false;
			if( ! ( isset( $st["permanent_stop"] ) && $st["permanent_stop"] ) ){
				clear_cache_for_special_values( $settings );
			}
			
			$settings = array(
				'cache_key' => "bg-service-running",
			);
			clear_cache_for_special_values( $settings );
			
			if( isset( $st["sleep_after_stop"] ) && $st["sleep_after_stop"] ){
				sleep( doubleval( $st["sleep_after_stop"] ) );
			}
			exit;
		}
		
		if( isset( $noloop ) && $noloop ){
			$loop = false;
		}
		
		++$sequence;
		
		echo "\n\n";
		echo "time: " . date("d-M-Y H:i:s") . "\n";
		echo "sequence: " . $sequence . "\n\n";
		
		$settings = array(
			'pagepointer' => $pagepointer,
			'user_cert' => $current_user_session_details ,
			'database_connection' => $database_connection ,
			'database_name' => $database_name , 
			'classname' => 'audit', 
			'action' => ( isset( $bg_action ) && $bg_action ) ? $bg_action : 'bg_service',
			'language' => SELECTED_COUNTRY_LANGUAGE,
			'skip_authentication' => true,
		);
		echo reuse_class( $settings );
		
		echo "end sequence: " . $sequence . "\n\n";
		
		$settings = array(
			'cache_key' => "bg-service-running",
			'cache_values' => array( "sequence" => $sequence, "time" => date("d-M-Y H:i:s") ),
		);
		set_cache_for_special_values( $settings );
		
		if( isset( $noloop ) && $noloop ){
			$loop = false;
			if( isset( $database_connection ) && $database_connection ){
				mysqli_close( $database_connection );
			}
			exit;
		}
		sleep( $delay );	//0 - 15 minutes
	}
	if( isset( $database_connection ) && $database_connection ){
		mysqli_close( $database_connection );
	}
?>
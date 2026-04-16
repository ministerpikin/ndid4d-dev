<?php 
	$background_data_upload_only = 1;
	$background_data_upload_only_session = 1;
	
	$run_once = isset( $_POST["upload_once"] )?$_POST["upload_once"]:0;
	//$pagepointer = "C:\hyella\htdocs\feyi-hyella\engine\php\\";
	
	include 'app_request_processor.php';
	session_write_close();
	
	echo "\n\nIMPORTANT!!! Running Hyella Business Manager. Please do not close this window...\n\n";
	echo "Optionally, you can minimize this window...\n\n";
	$terminate_updates = 96;	//run 288 times and interval of 15 minutes in every 1hour
	$sequence = 0;
	$updates = 0;
	$loop = true;
	
	
	if( ! $run_once ){
		//start bg-processor to prevent more windows
		$settings = array(
			'cache_key' => "bg-processor",
			'cache_values' => 1,
			'cache_time' => 'mini-time',
		);
		set_cache_for_special_values( $settings );
		
		//start bg-processor-stop to exit any other window
		$settings = array(
			'cache_key' => "bg-processor-stop",
			'cache_values' => 1,
			'cache_time' => 'mini-time',
		);
		set_cache_for_special_values( $settings );
		
		echo "Cleaning any previous process...\n\n";
		stopbgp( 1 );	//stop bg-processor-stop and any other code except the bg-processor
		
		echo "Cleaning process successful (".date("H:i").")...\n\n";
	}
	
	file_put_contents( 'abc1.txt', date("H:i:s") );
	while( $loop ){
		
		$settings = array(
			'cache_key' => "bg-processor",
			'cache_values' => 1,
			'cache_time' => 'mini-time',
		);
		set_cache_for_special_values( $settings );
		/*
		$settings = array(
			'cache_key' => "bg-processor-backup",
		);
		if( get_cache_for_special_values( $settings ) ){
			$settings = array(
				'pagepointer' => $pagepointer,
				'user_cert' => $current_user_session_details ,
				'database_connection' => $database_connection ,
				'database_name' => $database_name , 
				'classname' => 'audit' , 
				'action' => 'backup' ,
				'skip_authentication' => true,
			);
			reuse_class( $settings );
			
			$settings = array(
				'cache_key' => "bg-processor-backup",
			);
			clear_cache_for_special_values( $settings );
		}
		*/
		++$sequence;
		
		//automatic update
		if( get_hyella_development_mode() ){
			echo "automatic updates are disabled in development mode\n\n";
		}else{
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
				
				if( $r ){
					$updates = 0;
				}else{
					++$updates;
				}
			}
		}
		
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
		
		echo "\n\n";
		echo "time: " . date("d-M-Y H:i") . "\n";
		echo "sequence: " . $sequence . "\n";
		echo "updates: " . $updates . "\n\n";
		
		if( $run_once ){
			$loop = false;
		}else{
			$settings = array(
				'cache_key' => "bg-processor-running",
				'cache_values' => array( "sequence" => $sequence, "updates" => $updates ),
			);
			set_cache_for_special_values( $settings );
			
			if( $terminate_updates && $updates >= $terminate_updates ){
				$loop = false;
				stopbgp();
				break;
			}
			
			$settings = array(
				'cache_key' => "bg-processor-stop",
			);
			if( get_cache_for_special_values( $settings ) ){
				$loop = false;
				stopbgp();
				break;
			}
			
			//900
			sleep(900);	//15 minutes
			
			$settings = array(
				'cache_key' => "bg-processor-stop",
			);
			if( get_cache_for_special_values( $settings ) ){
				$loop = false;
				stopbgp();
				break;
			}
		}
	}
	
	if( ! $run_once ){
		stopbgp();
	}
	file_put_contents( 'abc.txt', date("jS F H:i:s") );
	
	function stopbgp( $option = 0 ){
		
		if( $option != 1 ){
			$settings = array(
				'cache_key' => "bg-processor",
			);
			clear_cache_for_special_values( $settings );
		}
		
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
<?php
	
	//Initialize PHP Sessions
	/* if( isset( $_GET["nwp_no_session"] ) && $_GET["nwp_no_session"] ){
		
	}else  */
	if( ! isset( $background_data_upload_only_session ) ){
		ini_set('session.use_cookies', 1);
		
		$time_limit = 0;
		if( defined("HYELLA_ENABLE_SESSION_TIMEOUT") && HYELLA_ENABLE_SESSION_TIMEOUT ){
			define( 'HYELLA_SESSION_TIMEOUT_LIMIT' , HYELLA_ENABLE_SESSION_TIMEOUT );
			
			if( defined("HYELLA_SESSION_TIMEOUT_LIMIT") ){
				$time_limit = doubleval( HYELLA_SESSION_TIMEOUT_LIMIT );
				if( $time_limit ){
					ini_set("session.gc_maxlifetime", $time_limit * 2 );
				}
			}
		}
		
		if( ! $time_limit ){
			ini_set("session.gc_maxlifetime", 3600 * 4 );
		}
		
		if( isset( $GLOBALS["session_id"] ) && $GLOBALS["session_id"] ){
			session_id( $GLOBALS["session_id"] );
		}
		
		session_start();
		
		if( $time_limit ){
			$now_time = date("U");
			if( isset( $_SESSION["idle_timeout"] ) && $now_time > ( $_SESSION["idle_timeout"] + $time_limit ) ){
				define("IDLE_TIMEOUT", 1 );
			}
			$_SESSION["idle_timeout"] = $now_time;
		}
		
	}else{
		//echo ' session off';
	}
	
	// IP Adddress of Database Server Host PC
	$database_host_ip_address = 'localhost';
	
	// Username of the database user account that would be used in establishing 
	// connection with the database
	$database_user = 'ej8phil7chin';
	
	// Password of the database user account that would be used in establishing 
	// connection with the database
	$database_user_password = 'Passionhill-56-f&g*%#-HAM-29-@-*34gim';
	
    //$default_country_id = '1228';
    $default_country_id = '1';
    
    $default_country = get_default_country_details();
	
	if( isset( $GLOBALS['provisioning'] ) || isset( $_POST["license"] ) || isset( $_POST["mobile_persistent"]["license"] ) ){
		
		require_once "license_info.php";
		//define( 'HYELLA_MODULE_ACCOUNTING' , 1 );	
		//this is kept here as a temporary measure, bcos i cannot start putting it in each person provisioning file now
	}else{
		require_once "provisioning.php";
	}
	
	if( ! ( defined("HYELLA_LYTICS_SERVER") && HYELLA_LYTICS_SERVER ) ){
		$database_connection = mysqli_connect( $database_host_ip_address, $database_user, $database_user_password );
		
		// Display error message if connection fail
		if ( ! $database_connection ) {
			echo 'Could not connect to DB in settings/Config.php'; exit;
			$a = array( "status" => "new-status", "html_replacement_selector" => "body" , "html_replacement" => "<h1>" . 'Unable to connect to database server: ' . $database_host_ip_address . ' with username ' . $database_user . "</h1>" );
			echo json_encode( $a );
			exit;
		}
	}
	
	if( isset( $_SESSION["test_server"] ) && $_SESSION["test_server"] ){
		$database_name .= '_test';
	}
	
	if( ! ( defined("HYELLA_LYTICS_SERVER") && HYELLA_LYTICS_SERVER ) ){
		if( $database_connection && ! mysqli_select_db ( $database_connection , $database_name ) ){
			$m = '';
			if( isset( $_SESSION["test_server"] ) && $_SESSION["test_server"] ){
				unset( $_SESSION["test_server"] );
				$m = '<p><a href="">Click here to return to live database</a></p>';
			}
			
			$a = array( "status" => "new-status", "html_replacement_selector" => "body" , "html_replacement" => "<h1>" . 'Missing Database: ' . $database_name . "</h1>" . $m );
			echo json_encode( $a );
			exit;
		}
	}
	
	date_default_timezone_set('Africa/Lagos');
	
	//ini_set('post_max_size' , 10240);
	//ini_set('upload_max_filesize' , 10240);
	
	setlocale(LC_CTYPE, 'POSIX');
	// Determine whether or not all errors should be reported 
	
    function get_default_country_details(){
        return array( 
            'id' => '1',
            'country' => 'Worldwide',
            'iso_code' => 'GLOBAL', 
            'flag' => 'GLOBAL', 
            'conversion_rate' => 1,
            //'currency' => '$',
            'currency' => 'NGN',
            'language' => 'US',
        );
    }
?>
<?php 
	/**
	 * 42Doc EDMS Process AJAX Request File
	 *
	 * @used in  				my_js/*.js
	 * @created  				none
	 * @database table name   	none
	 */
	
	
	//CONFIGURATION
	//$pagepointer = '../';
	if( ! isset( $pagepointer ) )$pagepointer = dirname( dirname( __FILE__ ) ) . '/';
	$display_pagepointer = '';
	
	require_once $pagepointer . "settings/Config.php";
	require_once $pagepointer . "settings/Setup.php";
	
	
	$page = 'ajax_request_processing_script';
	
	//INCLUDE CLASSES
	$class = $classes[$page];
	foreach( $class as $required_php_file ){
		require_once $pagepointer . "classes/" . $required_php_file . ".php";
	}
	
	if( isset( $package_config["plugins_loaded"] ) && is_array( $package_config["plugins_loaded"] ) && ! empty( $package_config["plugins_loaded"] ) ){
		foreach( $package_config["plugins_loaded"] as $rk => $required_php_file ){
			if( file_exists( $pagepointer . "plugins/" . $rk . "/" . $required_php_file . ".php" ) ){
				$GLOBALS["plugins"][ $rk ] = 'c'.ucwords( $rk );
				//$GLOBALS["plugin_classes"] = $classes;
				require_once $pagepointer . "plugins/" . $rk . "/" . $required_php_file . ".php";
			}else{
				//echo $required_php_file; exit;//$class; 
				log_m( array( "missing_file" => $required_php_file ) );
			}
		}
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
			'language' => SELECTED_COUNTRY_LANGUAGE,
			'skip_authentication' => true,
		);
		echo reuse_class( $settings );
		
		exit;
	}
?>
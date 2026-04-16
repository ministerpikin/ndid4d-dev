<?php
	//CONFIGURATION
	$pagepointer = '../';
	require_once $pagepointer . "settings/Config.php";
	$_SESSION['key'] = 'loading';
	require_once $pagepointer . "settings/Setup.php";
	unset( $_SESSION['key'] );
	$page = 'upload';
	
	//INCLUDE CLASSES
	$class = $classes[$page];
	foreach( $class as $required_php_file ){
		require_once $pagepointer . "classes/" . $required_php_file . ".php";
	}
	
	// list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$allowedExtensions = array();
	
	// max file size in bytes
	$sizeLimit = 10 * 1024 * 1024;

	$uploader = new cUploader($allowedExtensions, $sizeLimit);
	$uploader->calling_page = $pagepointer;

	$uploader->user_id = "admin";
	$uploader->priv_id = "admin";
	
	$uploader->form_id = 1;
	$uploader->table_id = "";
	$uploader->filename = "license";
	
	$formControlElementName = 'default';
	
	$allowedExtensions = array( "hyella" );
	
	$returned_value = $uploader->handleUpload( $pagepointer , $formControlElementName, $allowedExtensions );
	
	//rename it to a zip file & trigger update
	if( isset( $returned_value["stored_name"] ) && file_exists( $pagepointer . $returned_value["stored_name"] ) ){
		set_time_limit(0); //Unlimited max execution time
		
		copy( $pagepointer . $returned_value["stored_name"], $pagepointer . $pagepointer . "update.zip" );
		unlink( $pagepointer . $returned_value["stored_name"] );
		//echo htmlspecialchars(json_encode($returned_value), ENT_NOQUOTES);
		//exit;
		$settings = array(
			'cache_key' => 'mac-address',
			'permanent' => true,
		);
		clear_cache_for_special_values( $settings );
		
		remove_app_version_file( $pagepointer );
		
		//extract
		$f = $pagepointer . $pagepointer . "update.zip";
		$zip = new ZipArchive;
		$res = $zip->open( $f );
		if ($res === TRUE) {
			$zip->extractTo( $pagepointer . $pagepointer );
			$zip->close();
			
			$_SESSION["skip_payload_download"] = 1;
			unlink( $f );
			
			$p = get_l_key();
			$settings = array(
				'cache_key' => $p."-last",
				'cache_values' => doubleval( file_get_contents("ldl.txt") ),
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			rebuild();
		}else{
			echo "Zip Archive Problem"; exit;
		}
					
		$settings = array(
			'display_pagepointer' => $pagepointer,
			'pagepointer' => $pagepointer,
			'user_cert' => $current_user_session_details,
			'database_connection' => $database_connection,
			'database_name' => $database_name, 
			'classname' => "audit", 
			'action' => 'start_update',
			'language' => SELECTED_COUNTRY_LANGUAGE,
		);
		reuse_class( $settings );
	}
	
	// to pass data through iframe you will need to encode all html tags
	echo htmlspecialchars(json_encode($returned_value), ENT_NOQUOTES);
	exit;
?>
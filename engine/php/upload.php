<?php
	//CONFIGURATION
	if( ! isset( $pagepointer ) )$pagepointer = '../';
	if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
		require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
		//cNwp_app_core::loadENV();
		cNwp_app_core::db_connect();
	}
	//require_once $pagepointer . "settings/Config.php";
	require_once $pagepointer . "settings/Setup.php";
	
	$page = 'upload';
	
	//INCLUDE CLASSES
	$class = $classes[$page];
	foreach( $class as $required_php_file ){
		require_once $pagepointer . "classes/" . $required_php_file . ".php";
	}
	
	//GET DETAILS OF CURRENTLY LOGGED IN USER
	require_once $pagepointer . "settings/Current_user_details_session_settings.php";
	
	// list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$allowedExtensions = array();
	
	// max file size in bytes
	if( defined( 'BULK_IMPORT_MEMORY_LIMIT' ) && BULK_IMPORT_MEMORY_LIMIT ){

		$str = BULK_IMPORT_MEMORY_LIMIT;
	    $val = doubleval(trim($str));
	    $last = strtolower($str[strlen($str) - 1]);
	   
		switch($last) {
		case 'g': $val *= 1024;
		// case 'm': $val *= 1024;
		// case 'k': $val *= 1024;        
		}

	    $fsize = $val * 0.01; // 1%
	    
	}else{
		$fsize = ( isset( $_GET["max_size"] ) && doubleval( $_GET["max_size"] ) )?doubleval( $_GET["max_size"] ):0;
		if( $fsize ){
			$fsize = $fsize / ( 1024 * 1024 );
		}else{
			$fsize = 10;
		}
	}
	
	$fsize2 = 10;
	if( $fsize > 10 ){
		$fsize2 = $fsize;
	}
	$fsize2 = ceil( $fsize2 );
	$fsize = ceil( $fsize );
	// $fsize = 39;
	// $fsize2 = 200;

	$sizeLimit = ( $fsize + 1 ) * 1024 * 1024;
	ini_set('upload_max_filesize', $fsize . 'M');
	//ini_set('max_execution_time', '999');
	ini_set('memory_limit', ( $fsize2 * 5 ) . 'M');
	ini_set('post_max_size', $fsize2 . 'M' );

	$uploader = new cUploader($allowedExtensions, $sizeLimit);
	$uploader->calling_page = $pagepointer;

	if( ! $current_user_session_details['id'] ){
		exit;
	}
	$uploader->user_id = $current_user_session_details['id'];
	$uploader->priv_id = $current_user_session_details['privilege'];
	
	if( ! is_dir( $pagepointer . 'files' ) ){
		create_folder( $pagepointer . 'files', '', '', 0775 );
	}

	$fpath = $pagepointer.'files/'.$_GET['tableID'];
	if( defined("NWP_FILES_PATH") && NWP_FILES_PATH ){
		$apdir = ( defined("NWP_APP_DIR")?NWP_APP_DIR:'app' ) . '/';
		$apdir .= ( defined("NWP_FILE_DIR")?( NWP_FILE_DIR . '/' ):'' );
		$fpath = NWP_FILES_PATH.$apdir.$_GET['tableID'];
	}

	if( defined( 'HYELLA_V3_DIGITAL_IMAGES_DIRECTORY' ) && HYELLA_V3_DIGITAL_IMAGES_DIRECTORY && isset( $_GET[ 'qqfile' ] ) ){
		$exp = explode( '.', $_GET[ 'qqfile' ] );
		if( isset( $exp[ count($exp)-1 ] ) && $exp[ count($exp)-1 ] ){
			if( in_array( $exp[ count($exp)-1 ], array( 'dcm', 'ecg', 'ekg' ) ) ){
				$fpath = $pagepointer.HYELLA_V3_DIGITAL_IMAGES_DIRECTORY;
			}
		}
	}
	// echo $fpath;

	create_folder( $fpath, '', '' );
	
	if( isset($_GET['tableID']) && $_GET['tableID'] && is_dir( $fpath ) && isset($_GET['formID']) && $_GET['formID'] ){
		
		$uploader->field_label = isset( $_GET['label'] )?$_GET['label']:'';
		$uploader->original_file_name = isset( $_GET['qqfile'] )?$_GET['qqfile']:'';
		$uploader->form_id = $_GET['formID'];
		$uploader->table_id = $_GET['tableID'];
		
		$formControlElementName = 'default';
		if( isset( $_GET['name'] ) && $_GET['name'] ){
			$formControlElementName = $_GET['name'];
		}
		
		//$allowedExtensions = array( "jpg","jpeg","png","bmp","gif","tiff","pdf","xls","doc","txt","docx","ppt","pptx","rtf","xlsx","odt","csv" );
		$allowedExtensions = array( "jpg","jpeg","png","bmp","gif","tiff","pdf","xls","txt","docx","pptx","xlsx","csv" );
		if( defined( 'HYELLA_V3_RADIOLOGY_SCANS' ) && HYELLA_V3_RADIOLOGY_SCANS ){
			$allowedExtensions[] = 'dcm';
			$allowedExtensions[] = 'ecg';
			$allowedExtensions[] = 'ekg';
		}
		
		if( isset($_GET['acceptable_files_format']) && $_GET['acceptable_files_format'] != "undefined" && $_GET['acceptable_files_format'] ){
			$allowedExtensions = explode( ':::', $_GET['acceptable_files_format'] );
		}
		
		$returned_value = $uploader->handleUpload( $fpath . '/', $formControlElementName, $allowedExtensions );
		
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars( json_encode( $returned_value ), ENT_NOQUOTES );
	}else{
		//Invalid Table
		$returned_value = array( "error" => 'Invalid Directory to Upload File' );
		echo htmlspecialchars( json_encode( $returned_value ), ENT_NOQUOTES);
	}
	exit;
?>
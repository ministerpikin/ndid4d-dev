<?php
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	//INITIALIZE USER PERMISSION
	$allow_delete = 0;
	$allow_edit = 0;
	$allow_view = 0;
	$allow_verify = 0;
    
	//$allow_no_lang = 1;
	//CONFIGURATION
	$pagepointer = '../';
	$fakepointer = '';
	require_once $fakepointer.$pagepointer."settings/Config.php";
	require_once $fakepointer.$pagepointer."settings/Setup.php";
	
	require_once $fakepointer.$pagepointer."classes/cError.php";
	
	//SET TABLE
	$getpage = explode('/',$_SERVER['SCRIPT_FILENAME']);
	$page = $getpage[sizeof($getpage)-1];

	$table = str_replace('_server.php','',$page);
	
	if( file_exists( $fakepointer.$pagepointer."classes/cCustomer_call_log.php" ) )
		require_once $fakepointer.$pagepointer."classes/cCustomer_call_log.php";
	
	if( file_exists( $fakepointer.$pagepointer."classes/c".ucwords( $table ).".php" ) ){
		require_once $fakepointer.$pagepointer."classes/c".ucwords( $table ).".php";
	}else{
		if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE && file_exists( $fakepointer.$pagepointer."classes/package/". HYELLA_PACKAGE ."/c".ucwords( $table ).".php" ) ){
			require_once $fakepointer.$pagepointer."classes/package/". HYELLA_PACKAGE ."/c".ucwords( $table ).".php";
		}
	}
	
	//GET DETAILS OF CURRENTLY LOGGED IN USER
	require_once $pagepointer."settings/Current_user_details_session_settings.php";
	
	//Set Ajax Query
	require_once $pagepointer."includes/ajax_server_query.php";

	/* Data set length after filtering */
	$iFilteredTotal = 0;
	//$query = "SELECT COUNT(*) as 'count' FROM `".$database_name."`.`".$table."` $sWhere";
	$query = "SELECT ".$sCount_From." $sWhere";
	
	$query_settings = array(
		'database'=>$database_name,
		'connect'=>$database_connection,
		'query'=>$query,
		'query_type'=>'SELECT',
		'set_memcache'=>1,
		'tables'=>array($table),
	);
	$sql_result = execute_sql_query($query_settings);
	
	if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0])){
		$iFilteredTotal = $sql_result[0]['count'];
	}else{
		//REPORT INVALID TABLE ERROR
		$err = new cError('000001');
		$err->action_to_perform = 'notify';
		
		$err->class_that_triggered_error = $table.'_server.php';
		$err->method_in_class_that_triggered_error = '';
		$err->additional_details_of_error = 'executed query '.str_replace("'","",$query);
		
		return $err->error();
	}
	
	//Configure Settings for JSON dataset
	$json_settings = array(
		'show_details' => 1,		//Determine whether or not details button will be displayed
			'special_details_functions' => array(),	//Determine whether or not function will be called to display special details
			'show_details_more' => 0,				//Determine whether to show more details
			
		'show_serial_number' => 1,	//Determine whether or not to show serial number
		
		
		'special_table_formatting_visible_columns' => count($fields),	//Number of Columns Displayed in Table
		'special_table_formatting_modify_row_data' => '',	//Function that determines how row data will be modified
	);
	
	//Further Ajax Request that will be made by details button
	$future_request = '';
	
	//Set JSON datasets
	require_once $pagepointer."includes/ajax_server_json_data.php";
	
	echo json_encode( $output );
?>
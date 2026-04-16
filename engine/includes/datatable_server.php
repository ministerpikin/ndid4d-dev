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
    // exit;
	//$allow_no_lang = 1;
	//CONFIGURATION
	if( ! isset( $pagepointer ) )$pagepointer = '../';
	$fakepointer = '';

	if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
	 	require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
	 	cNwp_app_core::db_connect();
		$database_connection = cNwp_app_core::$def_cs["database_connection"];
		$database_name = cNwp_app_core::$def_cs["database_name"];
	}else{
		// print_r( HYELLA_PACKAGE );exit;
		require_once $fakepointer.$pagepointer."settings/Config.php";
	}
	require_once $fakepointer.$pagepointer."settings/Setup.php";
	
	require_once $fakepointer.$pagepointer."classes/cError.php";
	
	$g_more_data = array();
	
	if( isset( $_POST["dt"] ) && $_POST["dt"] ){
		
		$dt = json_decode( $_POST["dt"], true );
		unset( $_POST["dt"] );
		
		if( ! empty( $dt ) ){
			foreach( $dt as $dv ){
				if( isset( $dv["name"] ) && isset( $dv["value"] ) ){
					$_GET[ $dv["name"] ] = $dv["value"];
				}
			}
			
			if( isset( $_GET[ 'more_data' ]["table"] ) ){
				$g_more_data = $_GET[ 'more_data' ];
				//print_r( $_GET[ 'more_data' ] ); exit;
				unset( $_GET[ 'more_data' ] );
				
			}
		}
	}
	
	$table = isset( $g_more_data["table"] )?$g_more_data["table"]:'';
	$table_package = isset( $g_more_data["table_package"] )?$g_more_data["table_package"]:'';
	$real_table = $table;
	$plugin = isset( $g_more_data["plugin"] )?$g_more_data["plugin"]:'';
	
	if( isset( $g_more_data["real_table"] ) && $g_more_data["real_table"] ){
		$real_table = $g_more_data["real_table"];
	}
	if( isset( $g_more_data["no_plugin"] ) && $g_more_data["no_plugin"] ){
		$plugin = '';
		$real_table = $g_more_data["no_plugin"];
	}
	$controller_table = isset( $g_more_data["db_table"] )?$g_more_data["db_table"]:$real_table;

	$selection_button_action = '';
	$selection_button_action_field_values = array();

	if( isset( $g_more_data["show_selection"]["button_action"] ) && $g_more_data["show_selection"]["button_action"] ){
		if( isset( $g_more_data["show_selection"]["button_action_field_values"] ) && $g_more_data["show_selection"]["button_action_field_values"] ){
			$selection_button_action_field_values = $g_more_data["show_selection"]["button_action_field_values"];
		}
		$selection_button_action = $g_more_data["show_selection"]["button_action"];
	}
	
	$selection_button_title = '';
	if( isset( $g_more_data["show_selection"]["button_title"] ) && $g_more_data["show_selection"]["button_title"] ){
		$selection_button_title = $g_more_data["show_selection"]["button_title"];
	}
	
	$selection_show_details = '';
	if( isset( $g_more_data["show_selection"]["show_details"] ) && $g_more_data["show_selection"]["show_details"] ){
		$selection_show_details = $g_more_data["show_selection"]["show_details"];
	}
	if( $real_table ){
		
		if( 0 ){
			include $fakepointer.$pagepointer."ajax_server/".$real_table."_server.php";
		}else{
			if( file_exists( $fakepointer.$pagepointer."classes/cCustomer_call_log.php" ) ){
				require_once $fakepointer.$pagepointer."classes/cCustomer_call_log.php";
				require_once $fakepointer.$pagepointer."classes/cBase_class.php";
			}
			
			if( $plugin && file_exists( $fakepointer.$pagepointer."plugins/". $plugin ."/c".ucwords( $plugin ).".php" ) && file_exists( $fakepointer.$pagepointer."classes/cPlugin_class.php" ) ){
				$allow_no_lang = 1;
				
				require_once $fakepointer.$pagepointer."classes/cPlugin_class.php";
				require_once $fakepointer.$pagepointer."plugins/". $plugin ."/c".ucwords( $plugin ).".php";
				$p_cl = "c".ucwords( $plugin );
				
				if( class_exists( $p_cl ) ){
					$p_cls = new $p_cl();
					if( isset($p_cls->namespace) && $p_cls->namespace ){
						if( file_exists( $fakepointer.$pagepointer."classes/package/human-resource/c".ucwords( $real_table ).".php" ) ){
							require_once $fakepointer.$pagepointer."classes/package/human-resource/c".ucwords( $real_table ).".php";
						}
					}
					$error_msg = $p_cls->load_class( array("class" => array( $real_table ) ) );
					
				}else{
					$error_msg = 'Unregistered Plugin';
				}
			}else if( $table_package && file_exists( $fakepointer.$pagepointer."classes/package/". $table_package ."/c".ucwords( $real_table ).".php" ) ){
				require_once $fakepointer.$pagepointer."classes/package/". $table_package ."/c".ucwords( $real_table ).
				".php";
			}else if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE && file_exists( $fakepointer.$pagepointer."classes/package/". HYELLA_PACKAGE ."/c".ucwords( $real_table ).".php" ) ){
				
				require_once $fakepointer.$pagepointer."classes/package/". HYELLA_PACKAGE ."/c".ucwords( $real_table ).
				".php";
			}else if( file_exists( $fakepointer.$pagepointer."classes/c".ucwords( $real_table ).".php" ) ){
				
				require_once $fakepointer.$pagepointer."classes/c".ucwords( $real_table ).".php";
			}else if( file_exists( $fakepointer.$pagepointer."classes/package/enterprise/c".ucwords( $real_table ).".php" ) ){
				require_once $fakepointer.$pagepointer."classes/package/enterprise/c".ucwords( $real_table ).".php";
			}else if( file_exists( $fakepointer.$pagepointer."classes/package/human-resource/c".ucwords( $real_table ).".php" ) ){
				require_once $fakepointer.$pagepointer."classes/package/human-resource/c".ucwords( $real_table ).".php";
			}else if( file_exists( $fakepointer.$pagepointer."classes/package/family_tree/c".ucwords( $real_table ).".php" ) ){
				require_once $fakepointer.$pagepointer."classes/package/family_tree/c".ucwords( $real_table ).".php";
			} if( file_exists( $fakepointer.$pagepointer."classes/dev/c".ucwords( $real_table ).".php" ) ){
				if( ! class_exists( "c".ucwords( $real_table ) ) )require_once $fakepointer.$pagepointer."classes/dev/c".ucwords( $real_table ).".php";
			}
			
			//GET DETAILS OF CURRENTLY LOGGED IN USER
			require_once $pagepointer."settings/Current_user_details_session_settings.php";
			
			//Set Ajax Query
			require_once $pagepointer."includes/ajax_server_query.php";

			/* Data set length after filtering */
			$iFilteredTotal = 0;
			
			$mode = '';
			if( defined("DB_MODE") ){
				$mode = DB_MODE;
			}
			
			switch( $mode ){
			case 'mongoDB':

				$query_settings = array(
					'database' => $database_name,
					'table' => $table,
					'where' => array( 'record_status' => 1 ),
					'select' => array(),
					'query_type' => 'count',
				);

				$mongo_result = execute_mongo_query( $query_settings );

			break;
			default:
				//$query = "SELECT COUNT(*) as 'count' FROM `".$database_name."`.`".$table."` $sWhere";
				if( isset( $unions_count ) && is_array( $unions_count ) && ! empty( $unions_count ) ){
					$query = "SELECT ( " . implode( " ) + ( ", $unions_count ) ." ) as 'count' ";
				}else{
					$query = "SELECT ".$sCount_From. " " .$sWhere . $additional_where_of_query ." ". $sGroup_count;
				}
			
				$query_settings = array(
					'database'=>$database_name,
					'connect'=>$database_connection,
					'query'=>$query,
					'query_type'=>'SELECT',
					'set_memcache'=>1,
					'tables'=>array($table),
				);
				$sql_result = execute_sql_query($query_settings);
				//$sql_result[0]['count'] = 1000;
			break;
			}
			
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0])){
				$iFilteredTotal = $sql_result[0]['count'];
			}else{
				$error_msg = 'executed query '.str_replace("'","",$query);
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
		}
		exit;
	}else{
		$error_msg = 'Undefined Data Source';
	}
	
	if( ! $error_msg ){
		$error_msg = 'Unknown Server Error';
	}
	
	//REPORT INVALID TABLE ERROR
	$err = new cError('000001');
	$err->action_to_perform = 'notify';
	
	$err->class_that_triggered_error = $real_table.'_server.php';
	$err->method_in_class_that_triggered_error = '';
	
	$err->additional_details_of_error = $error_msg;
	
	return $err->error();
?>
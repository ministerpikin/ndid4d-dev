<?php
/**
 * audit_trail Class
 *
 * @used in  				audit_trail Function
 * @created  				Hyella Nathan | 10:33 | 01-Sep-2023
 * @database table name   	audit_trail
 */
	
	class cAudit_trail extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'audit_trail';
		
		public $default_reference = '';
		
		public $label = 'Audit Trail';
		public $plugin_instance = '';
		public $plugin = '';
		public $view_dir = '';
		public $view_path = '';
		
		private $associated_cache_keys = array(
			'audit_trail',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 0,
				
				'utility_buttons' => array(
					'comments' => 1,
					//'tags' => 1,
					'view_details' => 1,
				),
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
		);
			
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/audit_trail.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/audit_trail.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function audit_trail(){
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete_from_popup2':
			case 'delete_from_popup':
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_frontend_history':
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
			case 'filter_manage_data_access_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'edit_popup_form_in_popup':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'view_details2':
			case 'view_details':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'save_line_items':
				$returned_value = $this->_save_line_items();
			break;
			case 'manage_data_access_view':
				$returned_value = $this->_manage_data_access_view();
			break;
			case 'save_audit':
				$returned_value = $this->_save_audit();
			break;
			case 'audit_config':
			case 'save_audit_config':
				$returned_value = $this->_custom_capture_form_version_2();
			break;
			case 'bg_process':
				$returned_value = $this->_process_background_refresh();
			break;
			case 'clear_audit_trail':
				$returned_value = $this->_clear_audit_trail();
			break;
			case 'display_data_access_view':
				$returned_value = $this->_display_data_access_view();
			break;
			case 'audit_trail_backup':
				$returned_value = $this->_audit_trail_backup();
			break;
			case 'handle_file_backup':
				$returned_value = $this->_handle_file_backup();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		private function _audit_trail_backup(){
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$title = 'Export Database';
			$exports2 = array();
			$exports = array();
			
			$platform = '';
			if( defined( 'PLATFORM' ) ){
				$platform = PLATFORM;
			}
			
			$show_export = 1;
			$dump_path = 'php';
			
			switch( $platform ){
			case "linux":
				$show_export = 0;
				$dump_path = 'tmp';
			break;
			}
			
			if( is_dir( $this->class_settings[ 'calling_page' ] . $dump_path . '/dump/' ) ){
				//3. Open & Read all files in directory
				$cdir =  $this->class_settings[ 'calling_page' ] . $dump_path . '/dump/';
				
				foreach( glob( $cdir . "*.ela" ) as $filename ) {
					if ( is_file( $filename ) ) {
						$p = pathinfo( $filename );
						$p["dir"] = $dump_path . '/dump/';
						$exports[ $p["filename"] ] = $p;
					}
				}
				
				asort( $exports );
			}
			
			if( is_dir( $this->class_settings[ 'calling_page' ] . $dump_path . '/dump-files/' ) ){
				//3. Open & Read all files in directory
				$cdir =  $this->class_settings[ 'calling_page' ] . $dump_path . '/dump-files/';
				
				foreach( glob( $cdir . "*.hyella" ) as $filename ) {
					if ( is_file( $filename ) ) {
						$p = pathinfo( $filename );
						$p["dir"] = $dump_path . '/dump-files/';
						$exports2[ $p["filename"] ] = $p;
					}
				}
				
				asort( $exports2 );
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/export-database-prompt.php' );
			$this->class_settings[ 'data' ]["show_export"] = $show_export;
			$this->class_settings[ 'data' ]["title"] = $title;
			$this->class_settings[ 'data' ]["exported_databases"] = $exports;
			$this->class_settings[ 'data' ]["exported_files"] = $exports2;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		public function auto_bg_service( $opt = array() ){
			$this->_process_background_refresh();
		}
		
		protected function _display_data_access_view(){
			$js = array( 'set_function_click_event' );
			$html_q = '';
			$html = '';
			$error_msg = '';
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$data = [];
			$ad = new cAudit();
			$opt = [];
			$opt['group_plugin'] = 1;
			$opt['no_labels'] = 1;
			$opt['no_fields'] = 1;
			$opt['for_database_table_name'] = 1;
			$opt['excluded_classes'] = [
				'cNwp_tickets' => 1,
				'cNwp_orm' => 1,
				'cApi' => 1,
				'cStores' => 1,
				'cChart_js' => 1,
				'cPlugin_class' => 1,
				'cBanks' => 1,
				'cState_list' => 1,
				'cCities_list' => 1,
				'cLocked_files' => 1,
				'cAll_reports' => 1,
				'cLogged_in_users' => 1,
				'cMobile_app' => 1,
				'cNwp_enrolment_data' => 1,
				'cNwp_device_management' => 1,
				// 'cNwp_reports' => 1,
				'cParent_data' => 1,
				'cJson_options' => 1,
				'cNotifications' => 1,
				'cModules' => 1,
				'cItems_raw_data_import' => 1,
				'cImport_items' => 1,
				'cFunctions' => 1,
				'cDashboard' => 1,
				'cParent_child_data' => 1,
				'cCustomer_call_log' => 1,
			];

			$opt['excluded_plugin_classes'] = [
				'es_monitoring' => 1,
				'reports_ui' => 1,
				'social_accountability_monitoring' => 1,
				'communications' => 1,
				'suspicious_activity' => 1,
				'financial_indicators_on_enrolment_services' => 1,
				'grm_data' => 1,
				'project_development' => 1,
				'indicators' => 1,
			];

			$data["databases"] = $ad->_get_project_classes( $opt );
			
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( $this->view_path.'display-data-access-view.php' );
			$html = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $html,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ) 
			);
		}

		protected function _handle_file_backup( $o = array() ){

			$executed = 0;

			$key = 'FILE_BACKUP_DURATION'; #in hours
			
			$pd = $this->_get_project_settings_data( [ 'reference_table' => $this->table_name, 'keys' => [ $key ] ] );

			$duration = 2419200; //A month in seconds

			$ckey = md5( 'last_file_backup' );

			$settings = array(
				'cache_key' => $ckey,
				'directory_name' => $this->table_name,
				'permanent' => 1
			);

			$last_backup = (int) get_cache_for_special_values( $settings );
			
			$duration = isset( $pd['duration'] ) && $pd['duration'] ? (int)$pd['duration'] : $duration;

			$time_since_last_backup = (int)date("U") - $last_backup;

			if( $time_since_last_backup > $duration){
				
				$audit = new cAudit();
				
				$audit->class_settings = $this->class_settings;
				
				$audit->_back_up_files('', $last_backup);
				
				$settings['cache_values'] = date("U");

				set_cache_for_special_values( $settings );

				$executed = 1;
			}

			return array(
				'executed' => $executed
			);
		}


		protected function _process_background_refresh(){

			$this->_handle_file_backup();

			if ( defined('AUDIT_TRAIL_RETENTION_CYCLE') && AUDIT_TRAIL_RETENTION_CYCLE ) {
				
				$time = doubleval( AUDIT_TRAIL_RETENTION_CYCLE );
				$time *= 3600;
				$date = date( "U" );
				
				$ckey = md5( $this->table_name );
				$settings = array(
					'cache_key' => $ckey,
					'directory_name' => $this->table_name,
					'permanent' => 1
				);

				$fs = get_cache_for_special_values( $settings );

				if( isset( $fs['last_update'] ) && $fs['last_update'] && isset( $fs['trail'] ) && $fs['trail'] ){
					if( ( $date - $fs['last_update'] ) > $time ){
						$fs['last_update'] = $date;
						$this->class_settings['line_items'] = $fs['trail'];
						$fs['trail'] = [];
						if( $this->_save_line_items() ){
							$settings['cache_values'] = $fs;
							set_cache_for_special_values( $settings );
						}
					}
				}
			}

			$key = 'AUDIT_TRAIL_CONFIG';
			$pd = $this->_get_project_settings_data( [ 'reference_table' => $this->table_name, 'keys' => [ $key ] ] );

			$retention = 0;
			$retention2 = 0;

			if( isset( $pd[ strtoupper( $key ) ] ) && isset( $pd[ strtoupper( $key ) ][ 'value' ] ) ){
				$ds = json_decode( $pd[ strtoupper( $key ) ][ 'value' ], 1 );

				if( isset( $ds[ 'rention_period' ] ) && $ds[ 'rention_period' ] ){
					$retention = doubleval( $ds[ 'rention_period' ] );
				}
				if( isset( $ds[ 'audit_backup_retention' ] ) && $ds[ 'audit_backup_retention' ] ){
					$retention2 = doubleval( $ds[ 'audit_backup_retention' ] );
				}
			}

			if( ! ( $retention || $retention2 ) ){
				return;
			}

			$bdpk = "bg-". $this->table_name ."-retention-in-progress";
			$bdpk2 = "bg-". $this->table_name ."-audit-backup-retention-in-progress";

			$bdpk_exec = 0;
			$bdpk2_exec = 0;

			$settings = array( 'cache_key' => $bdpk, );
			$s = get_cache_for_special_values( $settings );
			if( get_hyella_development_mode() ){
				$s = 0;
			}

			$settings = array( 'cache_key' => $bdpk2, );
			$s2 = get_cache_for_special_values( $settings );
			if( get_hyella_development_mode() ){
				$s2 = 0;
			}

			if( !( $s || ! $retention ) ){
				$_GET = array();
				$_POST = array();

				$settings = array(
					'cache_key' => $bdpk,
					'cache_values' => 1,
				);
				$s = set_cache_for_special_values( $settings );
				
				$cut = time();
				$next_check2 = 0;
				$ty_mins = $retention * 60 * 60;

				$dirname = $this->class_settings["calling_page"] . "tmp";
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}
				$dirname .= "/filescache";
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}
				$dirname .= "/bg-processes";
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}
				$dirname .= "/".$this->table_name;
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}

				$nots = array();
				$new_id = get_new_id();
				$sn = 0;

				if( file_exists( $dirname . '/'. $bdpk .'.json' ) ){
					$ltc = json_decode( file_get_contents( $dirname . '/'. $bdpk .'.json' ), true );
					if( isset( $ltc["next_check"] ) ){
						$next_check2 = doubleval( $ltc["next_check"] );
					}
				}else{
					$next_check2 = $cut - $ty_mins;
				}

				if( $cut > $next_check2 ){

					$bdpk_exec = 1;
					$ds[ 'end_date' ] = time() - $ty_mins;

					$this->_clear_audit_trail( $ds );

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
				}
				
				$settings = array( 'cache_key' => $bdpk );
				$s = clear_cache_for_special_values( $settings );
			}

			if( !( $s2 || ! $retention2 ) ){
				$_GET = array();
				$_POST = array();

				$settings = array(
					'cache_key' => $bdpk2,
					'cache_values' => 1,
				);
				$s2 = set_cache_for_special_values( $settings );
				
				$cut = time();
				$next_check2 = 0;
				$ty_mins = $retention2 * 60 * 60;

				$dirname = $this->class_settings["calling_page"] . "tmp";
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}
				$dirname .= "/filescache";
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}
				$dirname .= "/bg-processes";
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}
				$dirname .= "/".$this->table_name;
				if( ! is_dir( $dirname ) ){
					create_folder( "", "", $dirname );
				}

				$nots = array();
				$new_id = get_new_id();
				$sn = 0;

				if( file_exists( $dirname . '/'. $bdpk2 .'.json' ) ){
					$ltc = json_decode( file_get_contents( $dirname . '/'. $bdpk2 .'.json' ), true );
					if( isset( $ltc["next_check"] ) ){
						$next_check2 = doubleval( $ltc["next_check"] );
					}
				}else{
					$next_check2 = $cut - $ty_mins;
				}

				if( $cut > $next_check2 ){

					$bdpk2_exec = 1;
					$ds[ 'end_date' ] = time() - $ty_mins;

					$tb_name = 'audit_trail_sr';

					// Delete the data from the table
					$query = "DELETE FROM `".$this->class_settings["database_name"]."`.`". $tb_name ."` WHERE `". $this->table_fields[ 'date' ] ."` <= ". $ds[ 'end_date' ] ." ";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'DELETE',
						'set_memcache' => 0,
						'plugin' => $this->plugin,
						'action_to_perform' => 'clear_audit_trail',
						'tables' => array( $tb_name ),
					);
					$sql = execute_sql_query($query_settings);

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					file_put_contents( $dirname . '/'. $bdpk2 .'.json', json_encode( $ltc ) );
				}
				
				$settings = array( 'cache_key' => $bdpk2 );
				$s2 = clear_cache_for_special_values( $settings );
			}

			if( $bdpk_exec || $bdpk2_exec ){
				return [ 'executed' => 1 ];
			}
			return;
		}

		protected function _clear_audit_trail( $opt = [] ){
			// echo '<pre>';
			if( isset( $opt[ 'end_date' ] ) && $opt[ 'end_date' ] && class_exists( 'cNwp_orm' ) ){
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'date' ] ."` <= ". $opt[ 'end_date' ] ." ";
				$d = $this->_get_records();
				if( ! empty( $d ) ){
					
					// Copy data to elastic search
					if( isset( $opt[ 'push_to_es' ] ) && $opt[ 'push_to_es' ] == 'on' && class_exists( 'cNwp_orm' ) ){
						
						// check if index exists before inserting
						require_once  dirname( dirname( dirname( __FILE__ ) ) ) . '/nwp_orm/classes/elasticonn.php';

						$cn = new cNwp_orm;
						$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );

						$client = cOrm_elasticsearch::$esClient;
						$response = $client->info();

						$params = [
						    'index' => $this->table_name
						];

						// $exists = $client->indices()->delete($params);exit;
						$exists = $client->indices()->exists( $params )->asBool();

						if( ! $exists ){
							// $db_mode = '';
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => array(
									'labels' => audit_trail(),
								),
								'query_type' => 'CREATE_TABLE',
								'db_mode' => 'elasticsearch',
								'set_memcache' => 0,
								'plugin' => $this->plugin,
								'action_to_perform' => 'clear_audit_trail',
								'tables' => array( $this->table_name ),
							);
							
							$dxd = execute_sql_query( $query_settings );
						}

						$tfields = $this->table_fields;
						$d2 = array_map(function( $d ) use ( $tfields ) {
						    return array_combine(array_map(function($k) use ($tfields) {
						        return $tfields[$k] ?? $k;
						    }, array_keys($d)), $d);
						}, $d);

						
						$d2_chunks = array_chunk($d2, 10000); //Break Down into chunks because of elasticsearch request limit
						
						unset( $d2 );
						
						foreach($d2_chunks as $d3){
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => array( 'values' => $d3 ),
								'db_mode' => 'elasticsearch',
								'query_type' => 'INSERT',
								'plugin' => $this->plugin,
								'action_to_perform' => 'clear_audit_trail',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							
							execute_sql_query($query_settings);
						}
						
						unset( $d2_chunks );
					}
					// exit;
					// zip data and save in folder
					if( isset( $opt[ 'zip_path' ] ) && $opt[ 'zip_path' ] && class_exists('ZipArchive') ){
						
						$zip = new ZipArchive();
						
						$zipname = time() . '_audit_clean_backup_'. count( $d ) .'.zip';

						if( $zip->open( $zipname, ZipArchive::CREATE) === true ){
						    foreach( $d as $key => $value ){
								$content = json_encode( $value );
								$zip->addFromString( $value[ 'id' ] . '.json', $content);
						    }
						    $zip->close();
						}

						$destinationFolder = $this->class_settings['calling_page'] . $opt[ 'zip_path' ];
						
						if (!file_exists($destinationFolder)) {
							mkdir($destinationFolder, 0777, true); // `true` enables recursive creation
						}
						// Save the zip file to a folder
						copy( $zipname, $destinationFolder . '/' . $zipname );
					}

					
					// move data to audit_trail_sr for backup
					if( isset( $opt[ 'audit_backup' ] ) && $opt[ 'audit_backup' ] == 'on' && class_exists( 'cNwp_endpoint' ) ){

						$ep = new cNwp_endpoint;
						$al = $ep->load_class( [ 'class' => [ 'endpoint' ], 'initialize' => 1 ] );
						$al[ 'endpoint' ]->class_settings = $this->class_settings;

						$fieldsKeys = [];
						foreach( $d[0] as $ddk => $dd ){
							if( isset( $this->table_fields[ $ddk ] ) && $this->table_fields[ $ddk ] ){
								$fieldsKeys[] = $this->table_fields[ $ddk ];
							}else{
								$fieldsKeys[] = $ddk;
							}
						}

						$d2 = array_chunk( $d, 10000 );
						foreach( $d2 as $d3 ){
							$_v = $al[ 'endpoint' ]->mysqlBulk( $d3, 'audit_trail_sr', 'loaddata_unsafe', [
								'link_identifier' => $this->class_settings["database_connection"],
								'database' => $this->class_settings['database_name'],
								'field_keys' => $fieldsKeys
							] );
						}
						unset( $d2 );
					}

					unset( $d );

					// Delete the data from the table
					$query = "DELETE FROM `".$this->class_settings["database_name"]."`.`".$this->table_name."` WHERE `". $this->table_fields[ 'date' ] ."` <= ". $opt[ 'end_date' ] ." ";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'DELETE',
						'set_memcache' => 0,
						'plugin' => $this->plugin,
						'action_to_perform' => 'clear_audit_trail',
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
				}
			}
		}

		protected function _custom_capture_form_version_2(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = str_replace( '_', '-', $action_to_perform );
			
			$modal = 0;
			$params = '';
			$html = '';
			$close_modal = '';
			$error = '';
			$etype = 'error';
			$error_container = '';
			$manual_close = 0;
			$mjs = array( 'set_function_click_event', 'prepare_new_record_form_new' );

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			
			$data = array();
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'audit_config':

				$key = 'AUDIT_TRAIL_CONFIG';
				$pd = $this->_get_project_settings_data( [ 'reference_table' => $this->table_name, 'keys' => [ $key ] ] );

				if( isset( $pd[ strtoupper( $key ) ] ) && isset( $pd[ strtoupper( $key ) ][ 'value' ] ) ){
					$data[ 'settings' ] = json_decode( $pd[ strtoupper( $key ) ][ 'value' ], 1 );
				}

			break;
			case 'save_audit_config':
				$key = 'AUDIT_TRAIL_CONFIG';

				$ps = new cProject_settings;
				$ps->class_settings = $this->class_settings;

				$ps->class_settings[ 'current_record_id' ] = $key;
				$e = $ps->_get_record();

				$json = $_POST;

				if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
					$_POST[ 'id' ] = $key;
				}else{
					$_POST[ 'id' ] = '';
					$_POST[ 'tmp' ] = $key;
				}

				$ps->class_settings[ 'update_fields' ] = [
					'key' => $key,
					'value' => json_encode( $json ),
					'comment' => '',
					'reference' => '',
					'reference_table' => $this->table_name,
				];

				$r = $ps->_update_table_field();

				return [
					'status' => 'new-status',
					'html_replacement' => $r[ 'html' ],
					'html_replacement_selector' => '#'.$handle,
				];
			break;
			}

			if( $error ){
				$ex = array( "type" => $etype, "message" => $error );
				if( $error_container ){
					$ex[ 'do_not_display' ] = 1;
					$ex[ 'html_replacement_selector' ] = $error_container;
				}
				if( $close_modal ){
					$ex[ 'callback' ][] = array( $close_modal );
				}
				if( $manual_close ){
					$ex[ 'manual_close' ] = 1;
				}
				return $this->_display_notification( $ex );
			}

			$data[ 'params' ] = '&html_replacement_selector=' . $handle . $params;
				
			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( $this->view_path . $filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			if( $modal ){
				return $this->_launch_popup( $html, "#" . $handle, $mjs );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				// 'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $mjs,
			);
			
			if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
				// $return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
				// $return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
			}
			return $return;
		}

		public function _save_audit( $o = array() ){

			if( isset( $o[ 'line_items' ] ) && $o[ 'line_items' ] ){
				
				$line_items = $o[ 'line_items' ];

				if( isset( $line_items[ 'parameters' ] ) && $line_items[ 'parameters' ] ){
					
					$dd = json_decode( $line_items[ 'parameters' ], 1 );

					if( isset( $dd[ 'nwp_action' ] ) && isset( $dd[ 'class' ] ) && isset( $dd[ 'nwp_todo' ] ) ){
						$line_items[ 'plugin' ] = $dd[ 'class' ];
						$line_items[ 'class' ] = $dd[ 'nwp_action' ];
						$line_items[ 'method' ] = $dd[ 'nwp_todo' ];
					}elseif( isset( $dd[ 'class' ] ) && $dd[ 'class' ] ){
						$line_items[ 'class' ] = $dd[ 'class' ];
						$line_items[ 'method' ] = $dd[ 'action' ];
					}

					if( isset( $dd[ 'record_id' ] ) ){
						$line_items[ 'reference' ] = $dd[ 'record_id' ];
					}
					if( isset( $dd[ 'record_table' ] ) ){
						$line_items[ 'reference_table' ] = $dd[ 'record_table' ];
					}
					if( isset( $dd[ 'record_plugin' ] ) ){
						$line_items[ 'reference_plugin' ] = $dd[ 'record_plugin' ];
					}
				}

				$retain_trail = 1;
				if ( defined('AUDIT_TRAIL_RETENTION_CYCLE') && AUDIT_TRAIL_RETENTION_CYCLE ) {
					$retain_trail = 0;
					$ckey = md5( $this->table_name );
					$settings = array(
						'cache_key' => $ckey,
						'directory_name' => $this->table_name,
						'permanent' => 1
					);

					$date = date("U");
					$line_items['bcreation_date'] = $date;
					$line_items['bmodification_date'] = $date;

					$fs = get_cache_for_special_values( $settings );
					$fs = $fs ? $fs : [];
					$fs['last_update'] = isset( $fs['last_update'] ) && $fs['last_update'] ? $fs['last_update'] : $date;
					$fs['trail'][] = $line_items;
					$settings['cache_values'] = $fs;
					set_cache_for_special_values( $settings );
				}

				if( $retain_trail ){

					$this->class_settings['line_items'] = [ $line_items ];

					return $this->_save_line_items();					
				}
			}
		}

		protected function _manage_data_access_view( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'manage_data_access_view':
				case 'filter_manage_data_access_view':
					$data = [];
					switch ($action_to_perform) {

					}

					$filename = 'data-access-view';
					$this->class_settings['data'] = $data;
					$this->class_settings['html'] = array( $this->view_path . $filename );
					$html = $this->_get_html_view();
					return array(
						'status' => 'new-status',
						'html_replacement' => $html,
						'html_replacement_selector' => '#'.$handle,
						'javascript_functions' => $js
					);
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		protected function _view_details2(){
			
			$filename = '';
				
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$error_msg = '';
			$hide_buttons = isset( $_GET[ 'hide_buttons' ] ) ? $_GET[ 'hide_buttons' ] : '';
			$e_type = 'error';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg = 'Invalid Reference';
			}
			
			switch( $action_to_perform ){
			case 'view_details2':
				if( ! $error_msg ){
					$this->class_settings["current_record_id"] = $_POST[ 'id' ];
					$e = $this->_get_record();
					if( isset( $e["id"] ) ){
						
					}else{
						$error_msg = 'Unable to retrieve patient details';
					}
				}
			break;
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
				if( ! $error_msg ){
					$opt = array();
					if( isset( $_GET[ 'click_btn' ] ) && $_GET[ 'click_btn' ] )$opt[ 'click_btn' ] = $_GET[ 'click_btn' ];
					$id = $e['id'];
					$opt[ 'id' ] = $id;

					/* $opt[ 'general_tasks' ][ 'administer_vaccine_btn' ] = array(
						'action' => 'antenatal_care_progress',
						'todo' => 'show_capture_options&use_get_customer=1&customer='.$e[ 'customer' ],
						'title' => 'New Care Progress',
					); */

					$opt[ 'general_actions' ][ 'view_details' ] = array(
						'action' => $this->table_name,
						'todo' => 'view_details',
						'title' => 'View Details',
					);

					$opt[ 'details_todo' ] = 'view_details2';
					$opt[ 'title_text' ] = get_name_of_referenced_record( array( 'id' => $e[ 'customer' ], 'table' => 'customers' ) );
					$_GET[ 'hide_buttons' ] = 1;

					$this->class_settings[ 'open_module' ] = $opt;

					//set_current_customer( array( "customer" => $id ) );
					return $this->_open_module();
				}
			break;
			case 'view_details2':
				$_GET["modal"] = 1;
				$filename = "view-details.php";
					
				if( ! $error_msg ){
					if( isset( $e["id"] ) ){
						$this->_apply_basic_data_control( $e );
						
						/* $stb = array();
						if( isset( $e["status2"] ) ){
							switch( $e["status2"] ){
							case "sent_to_nurse":
								$stb = array( 'an_vi' => 1 );
							break;
							}
						}
						if( empty( $stb ) ){
							$stb = array( 'of' => 1 );
						} 
						
						$this->class_settings[ 'data' ][ 'standalone_buttons' ] = $stb;
						*/
						
						//$this->class_settings[ 'data' ][ 'more_data' ][ 'additional_params' ] = '&reference=' . $e["id"] . '&reference_table=' . $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
						$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
						$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
						
						$b["more_actions"] = $this->_get_html_view();
						$b["id"] = $e["id"];
						//$b["auth_data"] = $qd;
						
						$this->class_settings["data_items"] = array( $e["id"] => $e );
						$this->class_settings["other_params"] = $b;
					}else{
						$error_msg = 'Unable to Retrieve Record';
					}
				}
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			if( $hide_buttons )$this->class_settings[ 'data' ][ 'hide_buttons' ] = $hide_buttons;
			
			$this->class_settings[ 'data' ][ 'no_columns' ] = isset( $_GET["no_column"] )?$_GET["no_column"]:'';
			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
				if( $filename ){
					$this->class_settings["html_filename"] = array( $this->view_path . $filename );
				}
			}else{
				if( $filename ){
					$this->class_settings["html_filename"] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
			}
			
			$this->class_settings["modal_dialog_style"] = "width:40%;";
				
			$return = $this->_view_details();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_details2':
				if( isset( $return["html_replacement_selector"] ) && $return["html_replacement_selector"] ){
					$return["html_replacement_selector"] = $handle;
				}
			break;
			}
			
			return $return;
		}
		
		protected function _search_form2(){
			$where = "";
			
			foreach( array( 'customer', 'staff_responsible' ) as $key ){
				if( isset( $this->table_fields[ $key ] ) && isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
					//$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
					$where .= " AND `".$this->table_fields[ $key ]."` = '".$_POST[ $this->table_fields[ $key ] ]."' ";
				}
			}
			
			$key = 'date';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] ] , 1 );
				$where .= " AND `".$this->table_fields[ $key ]."` >= " . $this->class_settings[ $key ];
			}
			
			$key = 'date';
			$skey = "_range";

			if( isset( $_POST[ $this->table_fields[ $key ] . $skey ] ) && $_POST[ $this->table_fields[ $key ] . $skey ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] . $skey ] , 2 );

				$where .= " AND `".$this->table_fields[ $key ]."` <= " . $this->class_settings[ $key ];
			}
			
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}

		protected function _display_all_records_full_view2(){
			$where = '';
			$disable_btn = 1;
			$spilt_screen = 0;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;
			
			switch( $action_to_perform ){
				case "display_all_records_full_view_search":
				case "display_all_records_frontend_history":
					$show_form = 1;
					unset( $this->class_settings[ "full_table" ] );
				break;
				case "display_all_records_frontend":
					$this->class_settings['full_table'] = 1;
					// $spilt_screen = 1;
				break;
				case 'filter_manage_data_access_view':
					$table = isset($_POST['table']) && $_POST['table'] ? $_POST['table'] : '';
					$user = isset($_POST['user']) && $_POST['user'] ? $_POST['user'] : '';
					$start_date = isset($_POST['start_date']) && $_POST['start_date'] ? convert_date_to_timestamp( $_POST['start_date'], 1 ) : '';
					$end_date = isset($_POST['end_date']) && $_POST['end_date'] ?  convert_date_to_timestamp( $_POST['end_date'], 2 ) : '';
					$this->class_settings['action_to_perform'] = 'display_all_records_frontend';
					$this->class_settings['full_table'] = 1;
					// $this->datatable_setting['show_edit_button'] = 0;
					unset( $this->datatable_settings['utility_buttons'] );
					
					$where = '';

					switch( $table ){
					case 'plugin:::endpoint':
						// $where .= " AND `".$this->table_fields['class'] ."` = 'endpoint_log' ";
						$c = 'cNwp_endpoint';
						$c = new $c;
						$c->class_settings = $this->class_settings;
						$tb = "endpoint_log";
						$c = $c->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) )[ $tb ];
					break;
					case 'class:::logged_in_users':

						$c = 'cLogged_in_users';
						$c = new $c;
						$c->class_settings = $this->class_settings;

					break;
					case 'class:::revision_history':

						$c = 'cRevision_history';
						$c = new $c;
						$c->class_settings = $this->class_settings;

					break;
					}


					if( $table ){

						switch( $table ){
						case 'class:::logged_in_users':
						case 'plugin:::endpoint':
						case 'class:::revision_history':
						break;
						default:

							$table = explode(':::', $table);
							if( isset($table[0]) && isset( $table[1] ) ){
								switch( $table[0] ){
									case 'class':
										// $where .= " AND `". $this->table_fields['table'] ."` REGEXP '". $table[1] ."' ";
									break;
									default:
										// $where .= " AND `". $this->table_fields['parameters'] ."` REGEXP '\"nwp_action\":\"". $table[1] ."\"'";
									break;
								}
							}

						break;
						}

						if( $user ){
							$fl = "`". $this->table_fields['user'] ."`";

							switch( $table ){
							case 'class:::logged_in_users':
								$fl = " `". $c->table_fields[ 'staff_responsible' ] ."` ";
							break;
							case 'plugin:::endpoint':
							case 'class:::revision_history':
								$fl = " `created_by` ";
							break;
							}

							$where .= " AND ". $fl ." = '". $user ."' ";
						}
						if( $start_date ){
							$fl = "`". $this->table_fields['date'] ."`";

							switch( $table ){
							case 'class:::logged_in_users':
							case 'plugin:::endpoint':
							case 'class:::revision_history':
								$fl = "`". $c->table_fields['date'] ."`";
							}

							$where .= " AND ". $fl ." >= '". $start_date ."' ";
						}
						if( $end_date ){
							$fl = "`". $this->table_fields['date'] ."`";

							switch( $table ){
							case 'class:::logged_in_users':
							case 'plugin:::endpoint':
							case 'class:::revision_history':
								$fl = "`". $c->table_fields['date'] ."`";
							break;
							}

							$where .= " AND ". $fl ." <= '". $end_date ."' ";
						}
					}

					switch( $table ){
					case 'plugin:::endpoint':

						$c->class_settings['filter']['where'] = $where;
						$c->class_settings[ 'action_to_perform' ] = 'execute';
						$c->class_settings[ 'nwp_action' ] = 'endpoint_log';
						$c->class_settings[ 'nwp_todo' ] = 'display_all_records_frontend';
						$c->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';
						return $c->endpoint_log();

					break;
					case 'class:::logged_in_users':

						$c->class_settings['filter']['where'] = $where;
						$c->class_settings[ 'action_to_perform' ] = 'display_all_records_full_view';
						return $c->logged_in_users();

					break;
					case 'class:::revision_history':

						$c->class_settings['filter']['where'] = $where;
						$c->class_settings[ 'action_to_perform' ] = 'display_all_records_full_view';
						return $c->revision_history();

					break;
					}

					$this->class_settings['filter']['where'] = $where;
				break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 0;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				unset( $this->basic_data['more_actions'] );
			}
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_history":
				$this->datatable_settings[ "show_edit_button" ] = 1;
				unset( $this->basic_data['more_actions'] );
			break;
			}
			
			if( $show_form ){
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
				$this->class_settings[ 'add_empty_select_option' ] = 1;
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "date":
						$this->class_settings["form_values_important"][ $val ] = date("U");
						$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					case "staff_responsible":
					case "customer":
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					default:
						$this->class_settings["hidden_records"][$val] = 1;
					break;
					}
				}
			}

			if( $spilt_screen ){
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;
				
				$this->datatable_settings["split_screen"] = array();
				
				$this->datatable_settings["datatable_split_screen"] = array(
					'action' => '?action='. $this->plugin .'&todo=execute&nwp_action=' . $this->table_name . '&nwp_todo=view_details2&no_column=1',
					'col' => 4,
					'content' => get_quick_view_default_message_settings(),
				);
			}
			
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2(){
			
			$ddx = array();
			$error = '';
			$e = array();
			$etype = 'error';
			$save_app = 1;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'save_app_changes':
			break;
			}
			
			if( $error ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error ) );
			}
			
			switch( $save_app ){
			case 1:
				$return = $this->_save_app_changes();
			break;
			case 2:
				$return = $this->_save_changes();
			break;
			case 3:
				$return = $this->_update_table_field();
			break;
			case 4:
				$return = $this->_save_line_items();
				if( $return )$return = array( 'saved_record_id' => 1 );
			break;
			}
			
			// print_r( $return );exit;
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = "#dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				switch( $action_to_perform ){
				case 'save_app_changes':
				break;
				}
				
			}
			
			// Send notification to assigned persons
			switch( $action_to_perform ){
			case 'save_new_popup':
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$action_to_perform = $this->class_settings['action_to_perform'];
			//$this->class_settings["skip_link"] = 1;
			
			switch ( $action_to_perform ){
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
			break;
			default:
			break;
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		protected function _apply_basic_data_control( $e = array() ){
			/* if( ! isset( $e["status"] ) ){
				return;
			} */
			
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
			
			/* 
			$show_cancel = 0;
			switch( $e["status"] ){
			case "cancelled":
			case "in_active":
				$show_cancel = 1;
			break;
			}

			if( ! $show_cancel ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1'] );
			}
			 */
			 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),

			'more_actions' => array(),
		);
	}
	
	function audit_trail(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/audit_trail.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/audit_trail.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){

					$key = $return[ "fields" ][ "user" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'users',
						'reference_keys' => array( 'firstname', 'lastname' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'class' ] = 'select2 allow-clear';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' field_key="parent" action="?action=users&todo=get_select2" minlength="2" ';

				}
				return $return[ "labels" ];
			}
		}
	}
?>
<?php
/**
 * job_queue Class
 *
 * @used in  				job_queue Function
 * @created  				Hyella Nathan | 10:28 | 26-Sep-2023
 * @database table name   	job_queue
 */
	
	$rabbit_queue_path = __DIR__ . '/vendor/autoload.php';
	if( file_exists($rabbit_queue_path) ){
		require_once $rabbit_queue_path;
	}
	
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	
	class cJob_queue extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'job_queue';
		
		public $default_reference = '';
		
		public $label = 'Job Queue';
		
		private $associated_cache_keys = array(
			'job_queue',
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
		
		private static $isTesting = false;

		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/job_queue.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/job_queue.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			if( class_exists('cNwp_app_core') && cNwp_app_core::$def_cs["isTesting"] ){
				self::$isTesting = cNwp_app_core::$def_cs["isTesting"];
			}
		}
	
		function job_queue(){
			
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
			case 'update_queue_item':
			case 'cancel_job_item':
				$returned_value = $this->_update_queue_item();
			break;
			case 'queue_config':
			case 'save_queue_config':
			case 'get_action_select2':
				$returned_value = $this->_queue_config();
			break;
			case 'run_all_background_processes':
				$returned_value = $this->_run_all_background_processes();
			break;
			case 'clear_job_queue':
				$returned_value = $this->_clear_job_queue();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _clear_job_queue(){
			$key = 'AUDIT_TRAIL_CONFIG';
			
			$pd = $this->_get_project_settings_data( [ 'reference_table' => 'audit_trail', 'keys' => [ $key ] ] );

			$retention = 0;
			
			if( isset( $pd[ strtoupper( $key ) ] ) && isset( $pd[ strtoupper( $key ) ][ 'value' ] ) ){
				
				$ds = json_decode( $pd[ strtoupper( $key ) ][ 'value' ], 1 );

				if( isset( $ds[ 'rention_period' ] ) && $ds[ 'rention_period' ] ){
					$retention = doubleval( $ds[ 'rention_period' ] );
				}
			}

			if( ! ( $retention ) ){
				return;
			}

			$bdpk = "bg-". $this->table_name ."-retention-in-progress";
			$settings = array( 'cache_key' => $bdpk, );
			$s = get_cache_for_special_values( $settings );
			if( get_hyella_development_mode() ){
				$s = 0;
			}

			if( $s ){
				return array( 'status' => 'error', 'message' => 'Job Queue Retention Process is already in progress' );
			}

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
				if( self::$isTesting ){
					echo  "\n". $this->label . ": " ."=== Clearing Queue Trail ==\n";
				}
				
				$this->_clear_queue_trail( $ds );

				$ltc[ 'next_check' ] = $cut + $ty_mins;
				file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
			}

		}

		protected function _clear_queue_trail( $opt = array() ){
			
			$error_msg = '';

			if( isset( $opt[ 'end_date' ] ) && $opt[ 'end_date' ] && class_exists( 'cNwp_orm' ) ){

				$where = " AND `". $this->table_fields[ 'date' ] ."` <= ". $opt[ 'end_date' ] ." ";

				$this->class_settings[ 'where' ] = $where;
				
				$d = $this->_get_records();
				
				if( ! empty( $d ) ){

					// Copy data to elastic search
					if( isset( $opt[ 'push_to_es' ] ) && $opt[ 'push_to_es' ] == 'on' && class_exists( 'cNwp_orm' ) ){

						if( self::$isTesting ){
							echo "\n". $this->label .": " ."=== Pushing to Elastic Search ==\n";
						}
						
						// check if index exists before inserting
						$cn = new cNwp_orm;
						$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
						$client = cOrm_elasticsearch::$esClient;
						if( cOrm_elasticsearch::$esClientError ){
							$error_msg = cOrm_elasticsearch::$esClientError;
						}else{
							$params = [ 'index' => $this->table_name ];
							$exists = $client->indices()->exists( $params )->asBool();
							if( ! $exists ){
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => array( 'labels' => job_queue() ),
									'query_type' => 'CREATE_TABLE',
									'db_mode' => 'elasticsearch',
									'set_memcache' => 0,
									'plugin' => $this->plugin,
									'action_to_perform' => $this->class_settings['action_to_perform'],
									'tables' => array( $this->table_name ),
								);
								
								execute_sql_query( $query_settings );
							}

							$tfields = $this->table_fields;
							$d2 = array_map(function($d) use ($tfields) {
							    return array_combine( array_map(function( $k ) use ( $tfields ) {
							        return $tfields[$k] ?? $k;
							    }, array_keys($d)), $d);
							}, $d);

							$d2_chunks = array_chunk($d2, 10000);
							unset( $d2 );
							foreach ( $d2_chunks as $d3 ) {
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => array( 'values' => $d3 ),
									'db_mode' => 'elasticsearch',
									'query_type' => 'INSERT',
									'plugin' => $this->plugin,
									'action_to_perform' => $this->class_settings['action_to_perform'],
									'set_memcache' => 1,
									'tables' => array( $this->table_name ),
								);
								
								execute_sql_query($query_settings);
							}

							unset( $d2_chunks );
							unset( $d2 );
						}
					}

					// zip data and save in folder
					if( isset( $opt[ 'zip_path' ] ) && $opt[ 'zip_path' ] && class_exists('ZipArchive') ){

						if( self::$isTesting ){
							echo "\n". $this->label .": " ."=== Ziping Log into Archive ==\n";
						}
						
						$zip = new ZipArchive();
						$zipname = date('d-M-Y-H:i') . '_' . count( $d ) .'.zip';

						if( $zip->open( $zipname, ZipArchive::CREATE) === true ){
						    foreach( $d as $value ){
								$content = json_encode($value);
								$zip->addFromString( $value[ 'id' ] . '.json', $content);
						    }
						    $zip->close();
						}

						$destinationFolder = $this->class_settings['calling_page'] . $opt[ 'zip_path' ] . '/' . $this->table_name;
						if (!file_exists($destinationFolder)) {
							mkdir($destinationFolder, 0777, true); // `true` enables recursive creation
						}

						// Save the zip file to a folder
						copy( $zipname, $destinationFolder . '/' . $zipname );
					}
					
					if( !$error_msg ){

						if( self::$isTesting ){
							echo "\n". $this->label .": " ."=== Deleting Data From Endpoint Table ==\n";
						}

						// Delete the data from the table
						$query = "DELETE FROM `".$this->class_settings["database_name"]."`.`".$this->table_name."` WHERE `record_status` = '1' ".$where;
						
						$query_settings = array(
							'database'=>$this->class_settings['database_name'],
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'DELETE',
							'set_memcache' => 0,
							'plugin' => $this->plugin,
							'action_to_perform' => 'clear_endpoint_log_trail',
							'tables' => array( $this->table_name ),
						);
						$sql = execute_sql_query($query_settings);
					}

					if( self::$isTesting ){
						if( $error_msg ){
							echo "\n=== Error:";
							echo $error_msg;
							echo "\n";
						}
					}				
				}
			}
		}

		protected function _queue_config( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'queue_config':
					$filename = 'queue-config.php';
					$ds = $this->_get_settings();
					if( isset($ds['action_to_watch']) && $ds['action_to_watch'] ){
						$dsx = explode(',', $ds['action_to_watch']);
						$dsx1 = [];
						foreach ($dsx as $dsval) {
							$vbs = explode(':::', $dsval);
							if(  array_intersect([0, 1, 2, 3], array_keys($vbs)) ){
								$key = implode(':::', array_slice($vbs, 0, 3));
								$value = $key . ':::'.$vbs[3];
								$dsx1[ $key ] = $value;
							}
						}
						$ds['action_to_watch'] = $dsx1;
					}
					$this->class_settings['data']['settings'] = $ds;

					$this->class_settings['html'] = array( $this->view_path . $filename );
					$html = $this->_get_html_view();

					return array(
						'status' => 'new-status',
						'html_replacement' => $html,
						'html_replacement_selector' => '#'.$handle,
						'javascript_functions' => $js
					);
				break;
				case 'save_queue_config':
					$actions = isset($_POST['actions_to_perform']) && $_POST['actions_to_perform'] ? json_decode($_POST['actions_to_perform'], true) : [];
					// if( $actions ){
						$c_sett = array(
							'cache_key' => md5('queue-config'),
							'directory_name' => $this->table_name,
							'permanent' => 1
						);
						$c_sett['cache_values']['action_to_watch'] = implode(',', array_values($actions));
						if( isset($_POST['concurrency']) && $_POST['concurrency'] ){
							$c_sett['cache_values']['concurrency'] = $_POST['concurrency'];
						}

						set_cache_for_special_values( $c_sett );

						$error_msg = "<h4><b>Saved Successfully</b></h4><p>Queue Config Saved Successfully</p>";
						$e_type = 'success';
						$n_params['html_replacement_selector'] = '#'.$handle;
					// }else{
					// 	$error_msg = "<h4><b>Invalid Submission</b></h4><p>Kindly select valid actions</p>";
					// }
				break;
				case 'get_action_select2':
					$dataSource = isset($_POST['data_source']) && $_POST['data_source'] ? $_POST['data_source'] : '';
					if( $dataSource ){
						$dataSource = explode(':::', $dataSource);
						$tba = array();
						if( ! empty( $dataSource ) ){
							foreach( $dataSource as $tbv ){
								$tba2 = explode( '=', $tbv );
								if( isset( $tba2[1] ) && $tba2[1] ){
									$tba[ $tba2[0] ] = $tba2[1];
								}
							}
						}


						$tb_type = isset( $tba["type"] )?$tba["type"] : '';
						$table2 = isset( $tba["value"] )?$tba["value"] : '';
						$dtable = $table2;

						switch( $tb_type ){
							case "plugin":
								if( isset( $tba["key"] ) && $tba["key"] ){
									$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
									if( class_exists( $clp ) ){
										$ptb = new $clp();
										$in = $ptb->load_class( array( 'class' => array( $dtable ), 'initialize' => 1 ) );
										if( isset( $in[ $dtable ]->table_name ) ){
											$cl = $in[ $dtable ];
										}else{
											$error_msg = "Invalid Plugin Table. The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.";
										}
									}else{
										$error_msg = "Invalid Plugin. The Plugin '". $clp ."' is invalid or missing in the project.";
									}
								}else{
									$error_msg = "Undefined Plugin. The Plugin is missing in the project.";
								}
						
							break;
							default:
								$cls = 'c' . ucwords( $dtable );
								if( class_exists( $cls ) ){
									$cl = new $cls();
								}else{
									$error_msg = "Class '". $cls ."' Not Found";
								}
							break;
						}
					}

					if( !$error_msg ){

						if( method_exists($cl, '_class_cases') && is_callable([$cl, '_class_cases']) ){
							$cases = $cl->_class_cases();
							if( $cases ){
								$a = [];
								$sn = 0;
								foreach ($cases as $_case) {
									$a[] = [ 'id' => $_case, 'text' => $_case, 'serial_num' => ++$sn ];
								}
								return [ 'items' => $a ];
							}
						}

						return [ 'items' => [] ];

					}else{
						return [ 'items' => ['id' => '', 'text' => $error_msg ]];
					}
				break;
			}

			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';

			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _run_all_background_processes(){
			
			$nwp = new cNwp_endpoint;
			$nwp->class_settings = $this->class_settings;
			$tb = 'endpoint';
			$tb1 = 'endpoint_log';
			$in = $nwp->load_class( array( 'class' => [ $tb, $tb1 ], 'initialize' => 1 ) );

			$return = [];
			$in[ $tb ]->class_settings['action_to_perform'] = 'bg_process';
			$return['process'][ $nwp->table_name ][ $tb ][ $in[ $tb ]->class_settings['action_to_perform'] ] = $in[ $tb ]->$tb();

			$in[ $tb ]->class_settings['action_to_perform'] = 'exec_folder_import';
			$return['process'][ $nwp->table_name ][ $tb ][ $in[ $tb ]->class_settings['action_to_perform'] ] = $in[ $tb ]->$tb();

			$in[ $tb1 ]->class_settings['action_to_perform'] = 'bg_process';
			$return['process'][ $nwp->table_name ][ $tb1 ][ $in[ $tb1 ]->class_settings['action_to_perform'] ] = $in[ $tb1 ]->$tb1();

			$nwp = new cNwp_reports;
			$nwp->class_settings = $this->class_settings;
			$tb2 = 'report_config';
			$in = array_merge($in, $nwp->load_class( array( 'class' => [ $tb2 ], 'initialize' => 1 ) ) );

			$in[ $tb2 ]->class_settings['action_to_perform'] = 'report_auto_generation';
			
			$return['process'][ $nwp->table_name ][ $tb2 ][ $in[ $tb2 ]->class_settings['action_to_perform'] ] = $in[ $tb2 ]->$tb2();

			$nwp = new cNwp_logging;
			$nwp->class_settings = $this->class_settings;
			$tb3 = 'audit_trail';
			$in = array_merge( $in, $nwp->load_class( array( 'class' => [ $tb3 ], 'initialize' => 1 ) ) );
			$in[ $tb3 ]->class_settings['action_to_perform'] = 'bg_process';
			$return['process'][ $nwp->table_name ][ $tb3 ][ $in[ $tb3 ]->class_settings['action_to_perform'] ] = $in[ $tb3 ]->$tb3();
			
			$this->class_settings['action_to_perform'] = 'clear_job_queue';
			$return['process'][ $this->table_name ][ $this->class_settings['action_to_perform'] ] = $this->_clear_job_queue(); 

			return $return;
		}

		protected function _update_queue_item( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';

			if( !$id ){
				$error_msg = "<h4><b>Invalid Reference</b></h4><p>Select a valid record</p>";
			}

			switch( $action_to_perform ){
				case 'cancel_job_item':
					$_POST['status'] = 'cancelled';
				case 'update_queue_item':
					if( isset($_POST['status']) && $_POST['status'] ){
						
						$this->class_settings['current_record_id'] = $id;
						
						$e = $this->_get_record();

						if( isset($e['status']) && $e['status'] ){

							switch ($e['status']) {
								case 'cancelled':
									$error_msg = "<h4><b>Invalid Status Update</b></h4><p>". $this->label ." item is already in cancelled</p>";
								break;
								case 'complete':
									$error_msg = "<h4><b>Invalid Status Update</b></h4><p>". $this->label ." item is already complete</p>";
								break;
								case 'stopped':
									$error_msg = "<h4><b>Invalid Status Update</b></h4><p>An error occurred with this ". $this->label ." item</p>";
									$error_msg .= '<hr>';
									$error_msg .= $e['reason'];
								break;
								case $_POST['status']:
									// $error_msg = "<h4><b>Invalid Status Update</b></h4><p>". $this->label ." item is already in set status</p>";
								break;
								default:
									switch ($action_to_perform) {
										case 'cancel_job_item':
											if( $e['status'] != 'in_queue' ){
												$error_msg = "<h4><b>Access Denied</b></h4><p>Only Items Pending in the queue can be cancelled</p>";
											}
										break;
									}
								break;
							}
							
							if( !$error_msg ){
								$this->class_settings['update_fields'] = $_POST;
								if( isset($this->class_settings['update_fields']['id']) && $this->class_settings['update_fields']['id'] ){
									unset( $this->class_settings['update_fields']['id'] );
								}
								return $this->_update_table_field();
							}
						}else{
							$error_msg = "<h4><b>Invalid Job Queue Item</b></h4><p>This job item can not be updated</p>";
						}				
					}else $error_msg = "<h4><b>Invalid Status</b></h4><p>Kindly provide a valid status</p>";
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		protected function _get_settings(){
			// return array(
			// 	'concurrency' => 2,
			// 	'action_to_watch' => 'type=plugin:::key=nwp_reports:::value=reports_bay:::action=save_generate_csv_bg,',
			// );

			$c_sett = array(
				'cache_key' => md5('queue-config'),
				'directory_name' => $this->table_name,
				'permanent' => 1
			);
			return get_cache_for_special_values( $c_sett );
		}

		protected function _get_jobs_in_queue(){
			$this->class_settings['where'] = " AND `". $this->table_fields['status'] ."` = 'in_queue' ";
			$this->class_settings['order_by'] = " ORDER BY `". $this->table_fields['date'] ."` ASC";
			return $this->_get_records();
		}

		protected function _get_jobs_running(){
			$this->class_settings['where'] = " AND `". $this->table_fields['status'] ."` = 'running' ";
			$this->class_settings['order_by'] = " ORDER BY `". $this->table_fields['date'] ."` ASC";
			return $this->_get_records();
		}

		protected function _cancel_job_in_queue( $o ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			

			$id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
			if( !$id ){
				if (isset($o['id']) && $o['id']) {
					$id = $o['id'];
				}
			}

			if( $id ){
				$this->class_settings['current_record_id'] = $id;
				$e = $this->_get_record();
				if( isset($e['id']) && $e['id'] ){
					switch ( $e['status'] ) {
						case 'running':
							$this->class_settings['update_fields']['status'] = 'stopped';
							if( isset($o['update_status']) && $o['update_status'] ){
								$this->class_settings['update_fields']['status'] = $o['update_status'];
							}
							$r = $this->_update_table_field();
							if( isset($r['saved_record_id']) && $r['saved_record_id'] ){
								// Clear cache
								clear_cache_for_special_values(array(
									''
								));
								$e_type = 'success';
								$error_msg = "<h4><b>Job cancelled successfully</b></h4><p>Background Task cancelled successfully</p>";								
							}else{
								$error_msg = "<h4><b>Failed to update Queue</b></h4><p>Unknown Error saving queue item</p>";
							}
						break;						
						default:
							$error_msg = "<h4><b>Access Denied</b></h4><p>Job Queue item is not currently running</p>";
						break;
					}
				}else{
					$error_msg = "<h4><b>Invalid Reference</b></h4><p>Invalid Record: Record does not exist</p>";
				}
			}else{
				$error_msg = "<h4><b>Invalid Reference</b></h4><p>Invalid Record</p>";
			}

			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
			
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
			
			return $n_params;
		}

		protected function _validate_concurrent_job_queue(){
			$settings = $this->_get_settings();
			$concurrent_queue_items = isset($settings['concurrency']) && $settings['concurrency'] ? (int)$settings['concurrency'] : 0;
			if( $concurrent_queue_items ){
				$queue = $this->_get_jobs_running();
				if( $queue ){
					if( count( $queue ) >= $concurrent_queue_items ){
						return false;
					}
				}
			}

			return true;
		}

		protected function _validate_multiple_queue_items( $params ){
			if( $params ){
				$keys = ['class', 'method', /*'user'*/];
				foreach ($keys as $key) {
					if( !(isset($params[ $key ]) && $params[ $key ]) ){
						return false;
					}
				}
				// AND `". $this->table_fields['user'] ."` = '". $params['user'] ."'
				$this->class_settings['where'] = " AND `". $this->table_fields['class'] ."` = '". $params['class'] ."' AND `". $this->table_fields['method'] ."` = '". $params['method'] ."' AND `". $this->table_fields['status'] ."` IN ('in_queue', 'running') ";
				if( isset($params['plugin']) && $params['plugin'] ){
					$this->class_settings['where'] .= " AND `". $this->table_fields['plugin'] ."` = '". $params['plugin'] ."'";
				}
				
				if( isset($params['job_id']) && $params['job_id'] ){
					$this->class_settings['where'] .= " AND `". $this->table_fields['job_id'] ."` = '". $params['job_id'] ."'";
				}

				$queue = $this->_get_records();
				if( $queue ){
					return false;
				}
				
				return true;
			}
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

		protected function _run_as_console_process($Command = '', $priority = 0, $args_array = [] ){

			$development = get_hyella_development_mode();

			$args = "";

			if( isset( $args_array["action"] ) && isset( $args_array["todo"] ) ){
				if( ! isset( $args_array["reference"] ) )$args_array["reference"] = '';
				if( ! isset( $args_array["user_id"] ) )$args_array["user_id"] = '';
				
				if( isset( $args_array["no_session"] ) ){
					$args_array["key"] = '';
				}else{
					$args_array["key"] = session_id();
				}

				$args = '"'.implode_associative_array( ':::', $args_array, '@@' ).'"';

			}else{	
				if( isset( $args_array["text"] ) && $args_array["text"] ){
					$args = '"'.$args_array["text"].'"';
				}
			}
			
			if( isset( $args_array["show_window"] ) && $args_array["show_window"] ){
				$development = 1;
			}
			
			if( $args && $development ){
				$development = 'hyella_development';
				$args .= ' "' . $development . '"';
			}else{
				$args .= ' "0"';
			}
			
			$wait_for_execution = 0;
			if( isset( $args_array["wait_for_execution"] ) && $args_array["wait_for_execution"] ){
				$wait_for_execution = 1;
			}
			
			if( isset( $args_array["argument3"] ) ){
				$args .= ' "' . $args_array["argument3"] . '"';
			}else{
				$args .= ' "0"';
			}
			
			if( isset( $args_array["argument4"] ) ){
				$args .= ' "' . $args_array["argument4"] . '"';
			}else{
				$args .= ' "0"';
			}
			
			if( isset( $args_array["argument5"] ) ){
				$args .= ' "' . $args_array["argument5"] . '"';
			}else{
				$args .= ' "0"';
			}


			if( defined("PLATFORM") && PLATFORM ){
				
				$start_time = microtime();
				
				switch ( PLATFORM ) {
					case 'linux':
						if( $Command ){
						    if( defined("NWP_ORIGIN_PATH") && NWP_ORIGIN_PATH ){
								$Command = NWP_ORIGIN_PATH ."/". $Command . ".php";
							}else{
								$Command = $this->class_settings['calling_page'] ."/php/". $Command . ".php";
							}									
							
							$output=null;
							$retval=null;
							if( $wait_for_execution ){
								exec("php ".$Command." " .  addslashes( escapeshellarg( str_replace('"','', $args) ) ), $output, $retval);
							}else{
								exec( "php ".$Command." " .  addslashes( escapeshellarg( str_replace('"','', $args) ) ) . " > /dev/null &");
							}									
						}
					break;
					case 'mac':
					break;
					default:
						if( $Command ){
							$check = 1;
							switch( $Command ){
								case "openfile":
									$check = 0;
								break;
							}
							
							if( $check && defined("NWP_ORIGIN_PATH") && NWP_ORIGIN_PATH ){
								$Command = NWP_ORIGIN_PATH ."\\". $Command . ".bat";
							}elseif( isset( $args_array[ 'absolute_bat_path' ] ) && $args_array[ 'absolute_bat_path' ] ){
								$Command = $Command;
							}else{
								$Command = dirname( dirname( dirname( dirname( __FILE__ )  ) ) ) ."\\php\\". $Command . ".bat";
							}
							
							if( $wait_for_execution ){
								ob_start();
								system( $Command . "\" " . escapeshellarg($args));
								ob_end_clean();
							}else{
								pclose(popen("start \"HYELLA\" \"" . $Command . "\" " . escapeshellarg($args) , "r"));
							}
						  }
					break;
				}

				$end_time = microtime();
			}

			$return['executed'] = 1;
		}

		protected function _run_as_rabbit_process( $params, $queue_id, $headless_mode = false ){
			$return = [];
			try{
				$host = defined('RABBITMQ_HOST') && RABBITMQ_HOST ? RABBITMQ_HOST : '';
				$port = defined('RABBITMQ_PORT') && RABBITMQ_PORT ? RABBITMQ_PORT : '';

				$connection = new AMQPStreamConnection($host, $port, 'guest', 'guest');
				$channel = $connection->channel();
				$channel->queue_declare('task_queue', false, true, false, false);

				$data = json_encode( array( 'args' => $params, 'queue' => $queue_id, 'headless_mode' => $headless_mode ) );
				$task = new AMQPMessage(
				    $data,
				    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
				);

				$channel->basic_publish($task, '', 'task_queue');
				$channel->close();
				$connection->close();
				$return['executed'] = 1;

			}catch(Exception $e){
				$return['error'] = $e->getMessage();
			}

			return $return;
		}

		public function _run_background_task( $params ){
			$return = [];
			$error_msg = '';
			if( $params ){
				// $params['headless_mode'] = 1;
				if( isset($params['arguments']) && $params['arguments'] ){
					// get settings
					$settings = self::_get_settings();
					$save_item = 0;
					$run_as_console = true;
					$headless_mode = isset($params['headless_mode']) && $params['headless_mode'] ? $params['headless_mode'] : false;
					$headless_mode = isset( $params['arguments']['headless_mode'] ) && $params['arguments']['headless_mode'] ? $params['arguments']['headless_mode'] : false;

					$line_item = array(
						'new_id' => 'jue'.get_new_id(),
						'date' => date("U"), 
						'user' => isset( $this->class_settings['user_id'] ) ? $this->class_settings['user_id'] : (isset($params['arguments']['user']) && $params['arguments']['user'] ? $params['arguments']['user'] : ''),
						'status' => 'in_queue', 
						'data' => json_encode( $params ),
						'job_id' => isset($params['arguments']['reference']) && $params['arguments']['reference'] ? $params['arguments']['reference'] : ''
					);
					
					$watch_method = '';
					if( isset($params['arguments']['action']) && $params['arguments']['action'] ){
						if( isset( $params['arguments']['nwp_action']) && $params['arguments']['nwp_action'] ){
							$line_item['plugin'] = $params['arguments']['action'];
							$line_item['class'] = $params['arguments']['nwp_action'];
							$line_item['method'] = $params['arguments']['nwp_todo'];
							$watch_method = 'type=plugin:::key='. $line_item['plugin'] . ':::value='.$line_item['class'].':::action='.$line_item['method'];
						}else{
							$line_item['class'] = $params['arguments']['action'];
							$line_item['method'] = $params['arguments']['todo'];
							$watch_method = 'type=base:::key=*base:::value='.$line_item['class'].':::action='.$line_item['method'];
						}

						if( !$headless_mode ){
							if( isset($settings['action_to_watch']) && $settings['action_to_watch'] ){
								if( in_array( $watch_method, array_map('trim', explode(',', $settings['action_to_watch'])) ) ){
									$run_as_console = false;
								}
							}							
						}else{
							$run_as_console = false;
						}
					}

					if( !$run_as_console ){
						
						$save_item = $headless_mode ? 0 : 1;

						if( !(isset($line_item['class']) && $line_item['class'] && isset( $line_item['method'] ) && $line_item['method'] )){
							$error_msg = "<h4><b>Invalid Parameters</b></h4><p>Invalid Background Task Settings</p>";
						}

						if( !$error_msg ){

							// if( !$headless_mode ){
							// 	if( !$this->_validate_concurrent_job_queue() ){
							// 		$error_msg = "<h4><b>Job Queue Full</b></h4><p>Task would be run when queue is free</p>";
							// 		$save_item = 1;						
							// 	}

							// 	if( !$error_msg ){
							// 		if( !$this->_validate_multiple_queue_items( $line_item ) ){
							// 			$error_msg = "<h4><b>Job Queue</b></h4><p>This action currently running in queue</p>";
							// 			$save_item = 0;
							// 		}
							// 	}								
							// }

							if( $save_item || $headless_mode ){

								if( !$headless_mode ){
									$this->class_settings['action_to_perform'] = 'save_line_items';
									$this->class_settings['line_items'][] = $line_item;
									$this->_save_line_items();									
								}


								if( ! isset( $params['arguments']["reference"] ) )$params['arguments']["reference"] = '';
								if( ! isset( $params['arguments']["user_id"] ) )$params['arguments']["user_id"] = '';
								
								if( isset( $params['arguments']["no_session"] ) ){
									$params['arguments']["key"] = '';
								}else{
									$params['arguments']["key"] = session_id();
								}

								$args = implode_associative_array( ':::', $params['arguments'], '@@' );

								$bg = $this->_run_as_rabbit_process( $args, $line_item['new_id'], $headless_mode );
								
								if( isset($bg['error']) && $bg['error'] ){
									
									$error_msg = $bg['error'];
									
									if( !$headless_mode ){
										$this->_update_records([
											'where' => " `id` = '". $line_item['new_id'] ."' ",
											'field_and_values' => [ 
												$this->table_fields['status'] => [ "value" => "stopped" ] , 
												$this->table_fields['reason'] => [ "value" => $error_msg ] 
											]
										]);										
									}
								}

								$return = array_merge( $return, $bg );
							}
						}
					}else{
						$comm = isset($params['command']) && $params['command'] ? $params['command'] : '';
						$pri = isset($params['priority']) && $params['priority'] ? $params['priority'] : '';
						$this->_run_as_console_process($comm, $pri, $params['arguments'] );
					}
				}
			}
			
			if( $error_msg ){
				$return['error_msg'] = $error_msg;
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
					$this->class_settings[ "show_popup_form" ] = 1;
					$this->class_settings[ 'frontend' ] = 1;
					$this->class_settings['full_table'] = 1;
					$this->datatable_settings[ "show_add_new" ] = 0;
					unset( $this->datatable_settings['utility_buttons'] );
					$disable_btn = 0;
				break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_edit_button" ] = 1;
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
			'access_to_crud' => 1,	//all crud functions (add, edit, view, delete) will be available in access control
			'exclude_from_crud' => array( 'create', 'edit' ),

			'more_actions' => array(
				'sb2' => array(
					'todo' => 'cancel_job_item',
					'title' => 'Cancel Process',
					'text' => 'Cancel Process',
					'button_class' => 'dark',
					'empty_container' => 1,
					'attributes' => ' confirm-prompt="cancel this job?" ',
				),
				// 'actions' => array(
				// 	'title' => 'More',
				// 	'data' => array(
				// 		'p1' => array(
				// 			'sb1' => array(
				// 				'todo' => 'view_details',
				// 				'title' => 'Sample Button',
				// 				'text' => 'Sample Button',
				// 				'standalone_class' => ' dark ',
				// 				'html_replacement_key' => 'phtml_replacement_selector',
				// 			),
				// 		),
				// 	),
				// ),
			),
		);
	}
	
	function job_queue(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/job_queue.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/job_queue.json' ), true );
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
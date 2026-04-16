<?php
/**
 * endpoint_log Class
 *
 * @used in  				endpoint_log Function
 * @created  				Hyella Nathan | 00:08 | 11-Aug-2023
 * @database table name   	endpoint_log
 */
	
	class cEndpoint_log extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'endpoint_log';
		
		public $default_reference = '';
		
		public $label = 'API Call Logs';
		
		private $associated_cache_keys = array(
			'endpoint_log',
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
					// 'comments' => 1,
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			if( class_exists('cNwp_app_core') && isset(cNwp_app_core::$def_cs["isTesting"] ) ){
				//set test parameters
				self::$isTesting = cNwp_app_core::$def_cs["isTesting"];
			}
		}
	
		function endpoint_log(){
			
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
			case 'bg_process':
				$returned_value = $this->_process_background_refresh();
			break;
			case 'bg_mail_service':
				$returned_value = $this->_bg_mail_service();
			break;
			case 'rerun_endpoint':
			case 'purge_endpoint':
			case 'purge_rerun_endpoint':
				$returned_value = $this->_rerun_endpoint();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _rerun_endpoint( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'rerun_endpoint':
				case 'purge_rerun_endpoint':
				case 'purge_endpoint':
					$this->class_settings['current_record_id'] = $_POST['id'];
					$e = $this->_get_record();

					if( isset($e['parent']) && $e['parent'] ){
						$error_msg = "<h4><b>Access Denied</b></h4><p>This can only be run on parent endpoint calls. Selected Log Item belongs to an adhoc (child) endpoint call</p>";
					}
					
					if( !$error_msg ){
						switch ($e['status']) {
							case 'failed':
							case 'success':
							break;
							default:
								if( !self::$isTesting ){
									// $error_msg = "<h4><b>Access Denied</b></h4><p>Current Log Item State does not support selected function</p>";
									switch ($action_to_perform) {
										case 'purge_endpoint':
										case 'purge_rerun_endpoint':
											if( in_array($e['status'], ['p_rerun', 'purged']) ){
												$error_msg = "<h4><b>Access Denied</b></h4><p>Current Log Item State does not support selected function</p>";
											}
										break;
									}
								}
							break;
						}
					}

					// print_r($e);exit;

					if( !$error_msg ) {
						if( isset($e['endpoint']) && $e['endpoint'] ){
							$tb = "endpoint";
							$in = $this->plugin_instance->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );

							$u_status = 'rerun';
							
							switch ($action_to_perform) {
								case 'purge_endpoint':
								case 'purge_rerun_endpoint':
									$u_status = 'p_rerun';
									switch ($action_to_perform) {
										case 'purge_endpoint':
											$u_status = 'purged';
										break;
									}
									try{
										if( class_exists( 'cNwp_orm' ) ){
											$cn = new cNwp_orm;
											$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
											$client = cOrm_elasticsearch::$esClient;
											if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
												$error_msg = cOrm_elasticsearch::$esClientError;
											}
										}else{
											$error_msg = '<h4><strong>Missing Plugin</strong></h4>cNwp_orm';
										}

										if( !$error_msg ){

											if( isset( $client ) ){

												$in[ $tb ]->class_settings['current_record_id'] = $e['endpoint'];
												
												$e1 = $in[ $tb ]->_get_record();

												if( isset($e1['datatable']) && $e1['datatable'] ){

													$tbd = explode(":::", $e1['datatable']);
													$tba = array();
													if( ! empty( $tbd ) ){
														foreach( $tbd as $tbv ){
															$tba2 = explode( '=', $tbv );
															if( isset( $tba2[1] ) && $tba2[1] ){
																$tba[ $tba2[0] ] = $tba2[1];
															}
														}
													}

													if( isset( $tba['value'] ) && $tba['value'] ){
														$params = [
														    'index' => $tba['value'],
														    'body'  => [
														        'query' => [
														            'match' => [
														                'creator_role' => $e['id']
														            ]
														        ]
														    ]
														];
														$response = $client->deleteByQuery( $params );
														$status = $response->getStatusCode();
														switch( $status ){
															case 200:
																if( isset($response['errors']) && $response['errors'] ){
																	$error_msg = array_reduce($response['errors'], function($a, $e){
																		return $a . ($a ? "<br>" : '') . $e['type'] . ": " . $e['reason'];
																	});
																	throw new Exception( $error_msg );
																}
																
																if( isset($response['deleted']) && $response['deleted'] ){
																	if( isset($e['data']) && $e['data'] ){
																		$e['data'] = json_decode( $e['data'], true );
																		if( isset($e['data']['prev_last_run']) ){
																			if( isset( $e1['last_run'] ) ){
																				if( $e1['last_run'] != $e['data']['prev_last_run'] ){
																					$in[ $tb ]->_update_records( array(
																						'where' => " `id` = '". $e1['id'] ."' ",
																						'fields_and_values' => [ $in[ $tb ]->table_fields['last_run'] => ['value' => json_encode( $e['data']['prev_last_run'] ) ] ]
																					) );
																				}
																			}
																		}
																	}
																}
															break;
															default:
																throw new Exception("Error Sending Elastic Delete Request", 1);
															break;
														}

													}else $error_msg = "<h4><b>Invalid Action</b></h4><p>Endpoint has no table to purge</p>";

												}else $error_msg = "<h4><b>Invalid Log Entry</b></h4><p>Kindly specify a valid log</p>";
											}else{
												throw new Exception("Unable to connect to Elastic Server", 1);
											}
										}

									}catch(Throwable $e ){
										$error_msg = "<h4><b>Rerun Endoint Failed</b></h4><p>". $e->getMessage() ."</p>";
									}catch(Exception $e ){
										$error_msg = "<h4><b>Rerun Endoint Failed</b></h4><p>". $e->getMessage() ."</p>";
									}
								break;
							}

							if( !$error_msg ){

								$_POST['id'] = $e['id'];
								$this->class_settings['update_fields']['status'] = $u_status;
								$this->class_settings['update_fields']['last_run_time'] = date("Y-m-dTH:i:s");
								$r = $this->_update_table_field();
								if( !(isset($r['saved_record_id']) && $r['saved_record_id'] ) ){
									return $r;
								}

								switch ($action_to_perform) {
									case 'purge_endpoint':
										$e_type = 'success';
										$error_msg = "<h4><b>". ( self::$isTesting ? 'TEST: ' : '' )."Data Purged Successfully</b></h4><p>Data Collected has been purged successfully</p>";
										$n_params['callback'][] = '$nwProcessor.reload_datatable';
									break;
									default:
										if( !self::$isTesting ){
											$_POST = array( 'id' => $e['endpoint'] );
											$in[ $tb ]->class_settings['action_to_perform'] = 'call_endpoint';
											$return = $in[ $tb ]->$tb();
										}else{
											$return['data'] = [
												'action' => $this->plugin,
												'todo' => 'execute',
												'nwp_action' => $tb,
												'nwp_todo' => 'call_endpoint_bg',
												'user_id' => 'phpunit',
												'reference' => $e1['id'],
											];
										}
										$return['javascript_functions'][] = '$nwProcessor.reload_datatable';
										return $return;
									break;
								}
							}
						}else{
							$error_msg = "<h4><b>Invalid Log Entry</b></h4><p>There was an error with this log entry. Kindly Contact Technical Team</p>";
						}
					}
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
				case 'view_details':
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
				case 'view_details':
					$filename = "view-details.php";
				break;
				case 'view_details2':
					$_GET["modal"] = 1;
					$filename = "view-details.php";
						
					if( ! $error_msg ){
						if( isset( $e["id"] ) ){
							$this->_apply_basic_data_control( $e );
														
							unset($this->datatable_settings['utility_buttons']['view_details']);
							
							$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
							$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
							$this->class_settings['data']['plugin'] = $this->plugin;
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

		protected function _clear_endpoint_log_trail( $opt = [] ){

			$error_msg = "";

			if( isset( $opt[ 'end_date' ] ) && $opt[ 'end_date' ] && class_exists( 'cNwp_orm' ) ){

				$where = " AND `". $this->table_fields[ 'date' ] ."` <= ". $opt[ 'end_date' ] ." AND ( `". $this->table_fields['status'] ."` != 'failed' OR ( `". $this->table_fields['status'] ."` = 'failed'  AND (`". $this->table_fields['parent'] ."` IS NULL OR `". $this->table_fields['parent'] ."` = '' ) ) ) ";

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
									'query' => array( 'labels' => endpoint_log() ),
									'query_type' => 'CREATE_TABLE',
									'db_mode' => 'elasticsearch',
									'set_memcache' => 0,
									'plugin' => $this->plugin,
									'action_to_perform' => 'clear_endpoint_log_trail',
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
									'action_to_perform' => 'clear_endpoint_log_trail',
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
						$zipname = time() . '_endpoint_log_clean_backup_'. count( $d ) .'.zip';

						if( $zip->open( $zipname, ZipArchive::CREATE) === true ){
						    foreach( $d as $key => $value ){
								$content = json_encode($value);
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
					if( isset( $opt[ 'audit_backup' ] ) && $opt[ 'audit_backup' ] == 'on' ){
						if( self::$isTesting ){
							echo "\n". $this->label .": " ."=== Audit Backup Into MySQL ==\n";
						}

						$fieldsKeys = array_values($this->table_fields);
						$tb = "endpoint";
						$in = $this->plugin_instance->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );

						$dchunks = array_chunk($d, 10000);
						foreach ($dchunks as $d2) {
							$_v = $in[ $tb ]->mysqlBulk( $d2, 'endpoint_log_sr', 'loaddata_unsafe', [
								'link_identifier' => $this->class_settings["database_connection"],
								'database' => $this->class_settings['database_name'],
								'field_keys' => $fieldsKeys
							] );
						}
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

		protected function _process_background_refresh(){

			$key = 'AUDIT_TRAIL_CONFIG';
			
			$pd = $this->_get_project_settings_data( [ 'reference_table' => 'audit_trail', 'keys' => [ $key ] ] );

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
				if( self::$isTesting ){
					echo "\n". $this->label .": " . "===== No Retention ====";
				}
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


			if( !($s || ! $retention) ){
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
						echo  "\n". $this->label . ": " ."=== Clearing Endpoint Trail ==\n";
					}
					$this->_clear_endpoint_log_trail( $ds );

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
				}
				
				$settings = array( 'cache_key' => $bdpk );
				if( !self::$isTesting ){
					$s = clear_cache_for_special_values( $settings );
				}
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

					$tb_name = 'endpoint_log_sr';

					if( self::$isTesting ){
						echo  "\n". $this->label . ": " ."=== Clearing MySQL Backup Table ==";
					}

					// Delete the data from the table
					$query = "DELETE FROM `".$this->class_settings["database_name"]."`.`". $tb_name ."` WHERE `". $this->table_fields[ 'date' ] ."` <= ". $ds[ 'end_date' ] ." ";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'DELETE',
						'set_memcache' => 0,
						'plugin' => $this->plugin,
						'action_to_perform' => 'clear_endpoint_log_trail',
						'tables' => array( $tb_name ),
					);
					$sql = execute_sql_query($query_settings);

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					file_put_contents( $dirname . '/'. $bdpk2 .'.json', json_encode( $ltc ) );
				}
				
				$settings = array( 'cache_key' => $bdpk2 );
				if( !self::$isTesting ){
					$s2 = clear_cache_for_special_values( $settings );
				}
			}

			if( class_exists('cNwp_logging') ){
				$nwp = new cNwp_logging();
				$nwp->class_settings = $this->class_settings;
				$tb = "log_main_logger";
				$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
				$in[ $tb ]->class_settings['action_to_perform'] = 'bg_process';
				$in[ $tb ]->$tb();
			}

			if( defined('ENDPOINT_CALL_REPORT_MAIL')  && ENDPOINT_CALL_REPORT_MAIL ){
				$this->_bg_mail_service();
			}

			if( $bdpk_exec || $bdpk2_exec ){
				return [ 'executed' => 1 ];
			}else{
				return;
			}
		}

		protected function _bg_mail_service(){
			$ctime = defined('ENDPOINT_LOG_MAIL_REPORT_FREQUENCY') && ENDPOINT_LOG_MAIL_REPORT_FREQUENCY ? (int)ENDPOINT_LOG_MAIL_REPORT_FREQUENCY : 0;
			$send_time =  $ctime * 3600;
			$cache = array(
				'cache_key' => $this->table_name .'-mail-report',
				'directory_name' => $this->table_name,
				'permanent' => true
			);

			$ltime = date("U") - $send_time;
			if( !self::$isTesting ){
				$c_value = get_cache_for_special_values( $cache );
				if( isset($c_value['last_sent_time']) && $c_value['last_sent_time'] ){
					$ltime = $c_value['last_sent_time'];
					if( ($ltime + $send_time) < date("U") ){
						return;
					}
				}
			}

			$this->class_settings['where'] = " AND `". $this->table_fields['date'] ."` >= ". $ltime ." AND (`". $this->table_fields['parent'] ."` = '' OR `". $this->table_fields['parent'] ."` IS NULL) ";
			$this->class_settings['order_by'] = " ORDER BY ". $this->table_fields['date']. " ASC ";
			$esx = $this->_get_records();
			
			if( $esx ){
				$summary['failed'] = 0;
				$summary['success'] = 0;
				$summary['total_execution_time'] = 0;
				$summary['total_memory_usage'] = 0;

				foreach ($esx as $sval) {
					
					$summary[ $sval['status'] ] = isset($summary[ $sval['status'] ]) && $summary[ $sval['status'] ] ? $summary[ $sval['status'] ]++ : 1 ;
					$_json = json_decode( $sval['data'], true );
					
					if( isset($_json['execution_time']) && $_json['execution_time'] ){
						$_mt = (float)$_json['execution_time'];
						$summary['total_execution_time'] += $_mt;
						$summary[ 'endpoint' ][ $sval['endpoint'] ]['execution_time'][] = $_mt;
					}
					
					if( isset($_json['memory_usage']) && $_json['memory_usage'] ){
						$_mu = (float)$_json['memory_usage'];
						$summary['total_memory_usage'] += $_mu;
						$summary[ 'endpoint' ][ $sval['endpoint'] ]['memory_usage'][] = $_mu;
					}
					
					if( isset($summary[ 'endpoint' ][ $sval['endpoint'] ]['count']) && $summary[ 'endpoint' ][ $sval['endpoint'] ]['count'] ){
						$summary[ 'endpoint' ][ $sval['endpoint'] ][ 'count' ]++;
					}else{
						$summary[ 'endpoint' ][ $sval['endpoint'] ]['count'] = 1;
					}

					if( isset($summary[ 'endpoint' ][ $sval['endpoint'] ][$sval['status']]) && $summary[ 'endpoint' ][ $sval['endpoint'] ][$sval['status']] ){
						$summary[ 'endpoint' ][ $sval['endpoint'] ][ $sval['status'] ]++;
					}else{
						$summary[ 'endpoint' ][ $sval['endpoint'] ][ $sval['status'] ] = 1;
					}

					$summary[ 'endpoint' ][ $sval['endpoint'] ]['status'] =  (isset($summary[ 'endpoint' ][ $sval['endpoint'] ]['status']) && $summary[ 'endpoint' ][ $sval['endpoint'] ]['status'] ) ? $summary[ 'endpoint' ][ $sval['endpoint'] ][ 'status' ]++ : $summary[ 'endpoint' ][ $sval['endpoint'] ]['status'] = 1;
				}

				$subject = "ENDPOINT CONSUMPTION SUMMARY";
				$info = "<p>Kindly find below a summary report on endpoint calls made over the past ". ( $ctime ) ."hr(s)</p>";
				$info .= '<hr />';
				$body = '<table class="table table-bordered table-striped">';
					$body .= '<thead>';
						$body .= '<tr>';
							$body .= '<th>API Endpoint</th>';
							$body .= '<th>Total Calls</th>';
							$body .= '<th>Success</th>';
							$body .= '<th>Failed</th>';
							$body .= '<th>Retries</th>';
							$body .= '<th>Average Execution Time</th>';
							$body .= '<th>Average Memory Usage</th>';
							$body .= '<th>Total Execution Time</th>';
							$body .= '<th>Total Memory Usage</th>';
						$body .= '</tr>';
					$body .= '</thead>';
					$body .= '<tbody>';
						foreach ($summary['endpoint'] as $key => $value) {
							$rr = (isset( $value['rerun'] ) ? (int)$value['rerun'] : 0) + ( isset($value['p_rerun']) ? (int)$value['p_rerun'] : 0 );
							$rr = ($rr ? $rr.' times' : '-');
							$total_e = array_sum( $value['execution_time']);
							$total_m = (array_sum( $value['memory_usage']) / 1024);
							$avg_e = $total_e / (int)$value['count'];
							$avg_m = $total_m / (int)$value['count'];
							
							$body .= '<tr>';
								$body .= '<td>'. get_name_of_referenced_record( array( 'id' => $key, 'table' => 'endpoint' ) ) .'</td>';
								$body .= '<td>' . $value['count'] . '</td>';
								$body .= '<td>' . (isset( $value['success'] ) ? $value['success'] : '-') . '</td>';
								$body .= '<td>' . (isset( $value['failed'] ) ? $value['failed'] : '-') . '</td>';
								$body .= '<td>' . $rr . '</td>';
								$body .= '<td>&nbsp;approx. ' . round( $avg_e, 2 ) . 's per call</td>';
								$body .= '<td>&nbsp;approx. ' . round( $avg_m, 2 ) . 'MB per call</td>';
								$body .= '<td>' . round( $total_e, 2 ) . 's</td>';
								$body .= '<td>' . round( $total_m, 2 ) . 'MB</td>';
							$body .= '</tr>';
						}
					$body .= '</tbody>';
				$body .= '</table>';

				$this->_notify_people( array(
					'content' => $info .'<br />' . $body,
					'subject' => $subject,
					'sending_option' => 'bcc',
					// 'specific_users' => ["'urs2917135391'"],
					'capability' => $this->table_name .'.log_report.log_report'
				) );

				$cache['cache_values']['last_sent_time'] = date("U");

				if( !self::$isTesting ){
					set_cache_for_special_values( $cache );
				}

				return ['executed' => 1];
			}
		}

		public function _save_line_items(){
				
			if( isset($this->class_settings['line_items']) && $this->class_settings['line_items'] ){
		
				$max_memory = (defined('ENDPOINT_CALL_MAX_MEMORY') && ENDPOINT_CALL_MAX_MEMORY) ? ENDPOINT_CALL_MAX_MEMORY * 1024 : 0;
				$max_time =  ( defined('ENDPOINT_CALL_MAX_EXECUTION_TIME') && ENDPOINT_CALL_MAX_EXECUTION_TIME ) ? ENDPOINT_CALL_MAX_EXECUTION_TIME : 0;
				$sid = 'eng'.get_new_id();
				$sn = 0;
				if( $max_memory || $max_time ){
					$max_memory_store = [];
					$max_time_store = [];
					// if( class_exists('cNwp_logging') ){
						// $nwp = new cNwp_logging();
						// $nwp->class_settings = $this->class_settings;
						// $tb = "audit_trail";
						// $in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
						
						// $date = date("U");
						// $ip = get_ip_address();
						// $email = get_name_of_referenced_record( ['id' => $this->class_settings['user_id'], 'table' => 'users', 'field_key' => 'email']);
						// $nm = get_name_of_referenced_record([ 'id' => $this->class_settings['user_id'], 'table' => 'users' ]);

						// $value['status'] = 'failed';
						// $value['parent'] = '';
						$l_items = [];
						foreach ($this->class_settings['line_items'] as $i => $value) {
							// if( isset($value['status']) && $value['status'] ){
							// 	if( !( $value['status'] == 'failed' && ( isset($value['parent']) && !$value['parent'] ) ) ){
							// 		$line_items[] = array(
							// 			'user' => $this->class_settings["user_id"],
							// 			'user_name' => $nm,
							// 			'user_action' => 'insert',
							// 			'table' => $this->table_name,
							// 			'parameters' => json_encode( array(
							// 				'record' => $value,
							// 				'class' => $this->table_name,
							// 				'action' => $this->class_settings['action_to_perform'],
							// 				'record_table' => $this->table_name,
							// 				'email' => $email
							// 			) ),
							// 			'date' => $date,
							// 			'plugin' => $this->plugin, 
							// 			'ip_address'=> $ip
							// 		);
							// 		unset( $this->class_settings[ 'line_items' ][ $i ] );
							// 	}
							// }

							if( isset($value['data']) && $value['data'] ){
								$_data = json_decode($value['data'], true);
								if( $max_memory ){
									if( isset($_data['memory_usage']) && $_data['memory_usage'] && $_data['memory_usage'] > $max_memory ){
										$c_id = $sid . $sn;
										$l_items[] = array(
											'date' => date("U"),
											'new_id' => $c_id,
											'endpoint' => $value['endpoint'],
											'status' => 'success',
											'message' => 'Memory Usage Limit Exceeded During Endpoint Call',
											'reference' => isset( $value['new_id'] ) && $value['new_id'] ? $value['new_id'] : '',
											'reference_table' => $this->table_name,
											'data' => json_encode( $value )
										);
										$sn += 2;
										$max_memory_store[] = isset( $value['new_id'] ) && $value['new_id'] ? $value['new_id'] : $c_id;
									}
								}

								if( $max_time ){
									if( isset($_data['execution_time']) && $_data['execution_time'] && $_data['execution_time'] > $max_time ){
										$c_id = $sid . $sn;
										$l_items[] = array(
											'date' => date("U"),
											'new_id' => $c_id,
											'endpoint' => $value['endpoint'],
											'status' => 'success',
											'message' => 'Execution Time Limit Exceeded During Endpoint Call',
											'reference' => isset( $value['new_id'] ) && $value['new_id'] ? $value['new_id'] : '',
											'reference_table' => $this->table_name,
											'data' => json_encode( $value )
										);
										$sn += 2;
										$max_time_store[] = isset( $value['new_id'] ) && $value['new_id'] ? $value['new_id'] : $c_id;
									}
								}
							}
						}

						if( $l_items ){
							$this->class_settings['line_items'] = array_merge( $this->class_settings['line_items'], $l_items );
						}

						// if( isset($line_items) && $line_items ){
						// 	$in[ $tb ]->class_settings['line_items'] = $line_items;
						// 	$in[ $tb ]->_save_line_items();
						// }
					// }
				}
			}


			$return = parent::_save_line_items();
			if( $return && ( isset($l_items) && $l_items ) ){
				$this->_limit_exceed_notification( array(
					// 'line_items' => $l_items,
					'max_memory' => $max_memory_store,
					'max_time' => $max_time_store
				) );
			}
			return $return;
		}

		protected function _limit_exceed_notification( $opt ){
			// if( isset($opt['line_items']) && $opt['line_items'] ){
				// $opt['line_items'] = itemize_by_key($opt['line_items'], 'new_id');
				if( isset($opt['max_memory']) && $opt['max_memory'] ){
					$msg = "<p>Kindly be notified that certain endpoint calls have exceeded the allocated memory limit. <br>Find below endpoint log references.</p> <p>Kindly log in to the M & E Reporting MIS to examine for more details</p>";
					$msg .= '<ol>';
						$msg .= array_reduce($opt['max_memory'], function($a, $e){
							return $a .= '<li><i>Endpoint Log: Ref. #'.$e . '</li>';
						});
					$msg .= '</ol>';

					$this->_notify_people( array(
						'subject' => 'ENDPOINT CALL LIMIT EXCEEDED: MEMORY',
						'content' => $msg,
						'sending_option' => 'bcc',
						// 'specific_users' => ["'urs2917135391'"],
						'capability' => $this->table_name .'.max_memory_notif.max_memory_notif'
					) );
				}

				if( isset($opt['max_time']) && $opt['max_time'] ){
					$msg = "<p>Kindly be notified that certain endpoint calls have exceeded the allocated execution time limit. <br>Find below endpoint log references.</p> <p>Kindly log in to the M & E Reporting MIS to examine for more details</p>";
					$msg .= '<ol>';
						$msg .= array_reduce($opt['max_time'], function($a, $e){
							return $a .= '<li><i>Endpoint Log: Ref. #'.$e . '</li>';
						});
					$msg .= '</ol>';

					$this->_notify_people( array(
						'subject' => 'ENDPOINT CALL LIMIT EXCEEDED: EXECUTION TIME',
						'content' => $msg,
						'sending_option' => 'bcc',
						// 'specific_users' => ["'urs2917135391'"],
						'capability' => $this->table_name .'.max_exec.max_exec'
					) );
				}
			// }
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

				$show_form = 0;
				$spilt_screen = 0;
				$status = isset($_GET['status']) && $_GET['status'] ? $_GET['status'] : '';

				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;

				if( $status ){
					switch ($status) {
					case 'failed':
						$this->class_settings['filter']['where'] = " AND `". $this->table_fields['status'] ."` = '". $status ."' AND (`". $this->table_fields['parent'] ."` IS NULL OR `". $this->table_fields['parent'] ."` = '' )  ";
					break;
					}
				}
			break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 0;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				if( $status ){
					switch ($status) {
						case 'failed':
						break;
					}
				}else{
					$stv = endpoint_log_access_buttons();
					if( $stv ){
						$access = get_accessed_functions();
						$super = !is_array( $access ) && $access == 1 ? 1 : 0;
						if( !$super ){
							foreach ($stv as $key => $value) {
								if( isset($access['accessible_functions'][ $this->table_name . '.' . 'basic_data_action' .'.' . $key ]) && $access['accessible_functions'][ $this->table_name . '.' . 'basic_data_action' .'.' . $key ] ){
									if( isset($this->basic_data['more_actions']['actions']['data']['p1'][ $key ]) && $this->basic_data['more_actions']['actions']['data']['p1'][ $key ] ){
										unset( $this->basic_data['more_actions']['actions']['data']['p1'][ $key ] );
									}
								}
							}
						}
					}
				}
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
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
			
			$show_rerun = 0;
			$show_rerun2 = 0;
			$show_purge = 0;

			switch( $e["status"] ){
				case "failed":
					if( !(isset($e['parent']) && $e['parent'] )){
						$show_rerun = 1;
						$show_rerun2 = 1;
					}
				break;
				case 'success':
					$show_purge = 0;
				break;
			}

			if( ! $show_rerun ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1']['sb1'] );
			}

			if( ! $show_rerun2 ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1']['sb2'] );
			}
			
			if( ! $show_purge ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1']['sb3'] );
			}
			
			 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
			'special_actions' => array(
				'basic_data_action' => array(
					'todo' => 'endpoint_log_access_buttons',
					'type' => 'function',
					'label' => 'All API Logs'
				),
				'log_report' => array(
					'todo' => 'log_report',
					'type' => '',
					'title' => 'Frequent Log Reports'
				),
				'max_memory_notif' => array(
					'todo' => 'max_memory_notif',
					'type' => '',
					'title' => 'API Call Limit Notification: Memory'
				),
				'max_exec' => array(
					'todo' => 'max_exec',
					'type' => '',
					'title' => 'API Call Limit Notification: Execution Time'
				)
			),

			'more_actions' => array(
				'sa1' => array(
				),
				'actions' => array(
					'title' => 'More',
					'data' => array(
						'p1' => array(
							'sb1' => array(
								'todo' => 'rerun_endpoint',
								'title' => 'Re-Run',
								'text' => 'Re-Run',
								'use_plugin' => 1,
								'standalone_class' => ' dark ',
								'html_replacement_key' => 'phtml_replacement_selector',
							),
							'sb2' => array(
								'todo' => 'purge_rerun_endpoint',
								'title' => 'Purge Data & Re-Run',
								'text' => 'Purge Data & Re-Run',
								'standalone_class' => ' bg-green ',
								'html_replacement_key' => 'phtml_replacement_selector',
							),
							'sb3' => array(
								'todo' => 'purge_endpoint',
								'title' => 'Purge Data Only',
								'text' => 'Purge Data Only',
								'standalone_class' => ' bg-green ',
								'html_replacement_key' => 'phtml_replacement_selector',
							),
						),
					),
				),
			),
		);
	}
	
	function endpoint_log(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return['fields'] ) ){
					$key = $return[ "fields" ][ "endpoint" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'endpoint',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'class' ] = 'select2 allow-clear';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' field_key="endpoint" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=get_select2" minlength="2" ';

					$key = $return[ "fields" ][ "reference" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'endpoint',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'class' ] = 'select2 allow-clear';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' field_key="reference" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=get_select2" minlength="2" ';
				}
				return $return[ "labels" ];
			}
		}
	}
?>
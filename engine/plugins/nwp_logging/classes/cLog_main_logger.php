<?php
/**
 * log_main_logger Class
 *
 * @used in  				log_main_logger Function
 * @created  				Hyella Nathan | 14:02 | 09-Jul-2023
 * @database table name   	log_main_logger
 */
	
	class cLog_main_logger extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'log_main_logger';
		
		public $default_reference = '';
		
		public $label = 'Main Logger';
		
		private $associated_cache_keys = array(
			'log_main_logger',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 0,
				
				'utility_buttons' => array(
					'comments' => 0,
					//'tags' => 1,
					'view_details' => 0,
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/log_main_logger.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/log_main_logger.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			if( class_exists('cNwp_app_core') && isset( cNwp_app_core::$def_cs["isTesting"] ) ){
				//set test parameters
				self::$isTesting = cNwp_app_core::$def_cs["isTesting"];
			}
		}
	
		function log_main_logger(){
			
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
				case 'test_data':
					$returned_value = $this->_test_data();
				break;
				case 'main_logger':
					$returned_value = $this->_logger( $opts );
				break;
				case 'purge_data':
					$returned_value = $this->_purge_data();
				break;
				case 'bg_process':
					$returned_value = $this->_process_background_refresh();
				break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		private function _test_data(){
			if( self::$isTesting ){
				$this->class_settings['where'] = " AND `". $this->table_fields['elevel'] ."` IS NULL OR `". $this->table_fields['elevel'] ."` = '' ";
				$this->class_settings['limit'] = "LIMIT 1";
				return $this->_get_records();
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

			if( $s || ! $retention ){

			}else{
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
					if( self::$isTesting ){
						echo  "\n". $this->label . ": " ."=== Clearing Logger Trail ==\n";
					}
					$this->_clear_log_trail( $ds );

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
				}
				
				$settings = array( 'cache_key' => $bdpk );
				if( !self::$isTesting ){
					$s = clear_cache_for_special_values( $settings );
				}
			}

			if( $bdpk_exec || $bdpk2_exec ){
				return [ 'executed' => 1 ];
			}else{
				return;
			}
		}

		protected function _clear_log_trail( $opt = [] ){

			$error_msg = "";

			if( isset( $opt[ 'end_date' ] ) && $opt[ 'end_date' ] && class_exists( 'cNwp_orm' ) ){

				$where = " AND `". $this->table_fields[ 'date' ] ."` <= ". $opt[ 'end_date' ] ."";

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
									'query' => array( 'labels' => log_main_logger() ),
									'query_type' => 'CREATE_TABLE',
									'db_mode' => 'elasticsearch',
									'set_memcache' => 0,
									'plugin' => $this->plugin,
									'action_to_perform' => 'clear_bulkload_log_trail',
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

							$d2_chunks = array_chunk( $d2, 10000 );
							foreach ( $d2_chunks as $d3 ) {
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => array( 'values' => $d3 ),
									'db_mode' => 'elasticsearch',
									'query_type' => 'INSERT',
									'plugin' => $this->plugin,
									'action_to_perform' => 'clear_bulkload_log_trail',
									'set_memcache' => 1,
									'tables' => array( $this->table_name ),
								);
								
								execute_sql_query($query_settings);
							}
						}
					}
					// zip data and save in folder
					if( isset( $opt[ 'zip_path' ] ) && $opt[ 'zip_path' ] && class_exists('ZipArchive') ){
						
						if( self::$isTesting ){
							echo "\n". $this->label .": " ."=== Ziping Log into Archive ==\n";
						}

						$zip = new ZipArchive();
						$zipname = time() . '_bulkload_log_clean_backup_'. count( $d ) .'.zip';

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
							'action_to_perform' => 'clear_bulkload_log_trail',
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

		public function _logger(string $fn, string $cls, string $plugin, mixed $data, string $elevel, string $errmsg,  array $opts = [] ){
			extract($opts);
			$arr = array(
				'date' => date("U"),
				'function' => $fn,
				'class' => $cls,
				'plugin' => $plugin,
				'data' => json_encode($data),
				'elevel' => $elevel,
				'error' => $errmsg,
				// 'bactrace' => json_encode( debug_backtrace() )
			);

			$this->class_settings['line_items'][] = $arr;
			$this->_save_line_items();
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

		protected function _purge_data( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];

			switch( $action_to_perform ){
				case 'purge_data':
					if( class_exists( 'cNwp_orm' ) ){
						$cn = new cNwp_orm;
						$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
						$client = cOrm_elasticsearch::$esClient;
						if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
							$error_msg = cOrm_elasticsearch::$esClientError;
						}

						if( !$error_msg ){
							if( isset($_POST['id']) && $_POST['id'] ){
								$ids = isset($_POST['ids']) && $_POST['ids'] ? $_POST['ids'] : [];
								if( $ids ){
									$ids = array_map('trim', explode(":::", $ids));
								}else{
									$ids[] = $_POST['id'];
								}
								
								if( $ids ){
									$p = $p1 = [];
									foreach( $ids as $id ){
										$skip = 0;
										$this->class_settings['current_record_id'] = $id;
										$e = $this->_get_record();
										if( isset( $e['elevel'] ) ){
											switch ( $e['elevel'] ) {
												case 'purged':
													if( count( $ids ) <= 1 ){
														$error_msg = "<h4><b>Access Denied <i>#". $e['id'] ."</i></b></h4><p>Data has already been purged</p>";
													}else{
														$error_msg = "<h4><b>Log Warning</b></h4><p>Some selected items have been purged previously</p>";
													}
													$skip = 1;
												break;
											}
										}

										if( !$skip ){
											if( isset($e['data']) && $e['data'] ){
												$_db = json_decode($e['data'], true);
												if( isset($_db['reference']) && $_db['reference'] && isset($_db['table']) && $_db['table'] ){
													$p[ $_db['table'] ][] = $_db['reference'];
												}else $error_msg .= "<h4><b>Invalid Log Details</b></h4><p>Unable to find log details - <i>#". $e['id'] ."</i> </p>";
											}else $error_msg .= "<h4><b>Invalid Reference</b></h4><p>Invalid Record - <i>#". $e['id'] ."</i></p>";
										}
									}
									

									if( $p ){
										try{
											foreach ($p as $table => $values) {
												$params = [
												    'index' => $table,
												    'body'  => [
												        'query' => [
												            'terms' => [ 'created_source' => $values ]
												        ]
												    ]
												];

												$response = $client->deleteByQuery( $params );
												$status = $response->getStatusCode();

												switch( $status ){
													case 200:
														if( isset($response['errors']) && $response['errors'] ){
															$error_msg .= array_reduce($response['errors'], function($a, $b){
																return $a . ($a ? "<br>" : '') . $b['type'] . ": " . $b['reason'];
															});
														}
														
														if( isset($response['deleted']) && $response['deleted'] ){
															$p1[] = $id;
															$e_type = 'success';
															$error_msg .= "<h4><b>Data Purged Successfully</b></h4><p>Data associated to selected log item has been purged successfully</p>";
														}else{
															$error_msg .= "<h4><b>Data Purged Warning</b></h4><p>No item matched selected log entries for index: ". $table ."</p>";
															$e_type = 'info';
														}
													break;
													default:
														$error_msg .= "<p>Error Sending Elastic Search Delete Request</p>";
													break;
												}											
											}
										}catch(Exception $e){
											$error_msg = $e->getMessage();
											$e_type = 'error';
										}catch(Throwable $e){
											$error_msg = $e->getMessage();
											$e_type = 'error';
										}
										
										if( $p1 ){
											$this->_update_records([
												'where' => " `id` IN ('". implode("', '", $p1) ."') ",
												'field_and_values' => [ $this->table_fields['elevel'] => [ 'value' => 'purged' ]  ]
											]);
											$n_params['callback'][] = '$nwProcessor.reload_datatable';
										}

									}else{
										$error_msg .= "<h4><b>Invalid Selection</b></h4><p>Selected Log Records have been previously purged or Invalid. <br/> Kindly select valid log records</p>";
									}
									
								}
							}else{
								$error_msg = "<h4><b>Invalid Reference</b></h4><p>Kindly select a valid record</p>";
							}
						}			
					}else{
						$error_msg = '<h4><strong>Missing Plugin</strong></h4>cNwp_orm';
					}
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;

			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
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
					$spilt_screen = 0;
					$this->class_settings['full_table'] = 1;
					$s = isset( $_GET['function'] ) && $_GET['function'] ? $_GET['function'] : '';
					$t = isset( $_GET['table'] ) && $_GET['table'] ? $_GET['table'] : '';

					if( $s ){
						switch( $s ){
							case 'file_load':
								$this->class_settings['filter'][ 'where' ] = " AND `". $this->table_fields['function'] ."` IN ('_dump_elastic_data', '_save_dataload') ";
								if( $t ){
									$this->class_settings['filter']['where'] .= " AND `". $this->table_fields['data'] ."` REGEXP '\"table\":\"". $t ."\"' ";
								}
							break;
						}
					}
				break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 0;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				// unset( $this->basic_data['more_actions'] );
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

			'more_actions' => array(
				'actions' => array(
					'title' => 'More',
					'data' => array(
						'p1' => array(
							'sb1' => array(
								'todo' => 'purge_data',
								'title' => 'Purge Data',
								'text' => 'Purge Data',
								'use_plugin' => 1,
								'multiple' => 1,
								'standalone_class' => ' dark ',
								'html_replacement_key' => 'phtml_replacement_selector',
							),
							'sb2' => array(
								// 'todo' => 'purge_rerun_endpoint',
								// 'title' => 'Purge Data & Re-Run',
								// 'text' => 'Purge Data & Re-Run',
								// 'standalone_class' => ' bg-green ',
								// 'html_replacement_key' => 'phtml_replacement_selector',
							),
						),
					),
				),
			),
		);
	}
	
	function log_main_logger(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/log_main_logger.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/log_main_logger.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
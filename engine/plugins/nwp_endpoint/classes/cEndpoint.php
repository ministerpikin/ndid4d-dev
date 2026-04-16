<?php
/**
 * endpoint Class
 *
 * @used in  				endpoint Function
 * @created  				Hyella Nathan | 06:50 | 30-Jun-2023
 * @database table name   	endpoint
 */
	
	class cEndpoint extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'endpoint';
		
		public $default_reference = '';
		
		public $label = 'Endpoint';
			
		private $associated_cache_keys = array(
			'endpoint',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();

		private static $persistInfo = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 0,				
				
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
		private static $skipBULK_IMPORT_MEMORY_LIMIT = false;
			
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/endpoint.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/endpoint.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			if( class_exists('cNwp_app_core') && cNwp_app_core::$def_cs["isTesting"] ){
				//set test parameters
				self::$isTesting = cNwp_app_core::$def_cs["isTesting"];
				if( cNwp_app_core::$def_cs["setTestParams"] ){
					self::$skipBULK_IMPORT_MEMORY_LIMIT = true;
				}
			}
		}
	
		function endpoint( $o = array() ){

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
				case 'display_all_records_frontend':
				case 'display_all_records_full_view':
				case 'display_all_records_frontend_history':
				case 'display_all_records_full_view_search':
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
				case 'get_select2':
					$returned_value = $this->_get_select2();
				break;
				case 'display_home':
				case 'format_react_data':
					$returned_value = $this->_display_home();
				break;
				case 'save_dataload':
				case 'save_data_to_table_bg':
				case 'check_bg_load_progress':
				case 'stop_bg_load':
					$returned_value = $this->_save_dataload();
				break;
				case 'call_endpoint':
				case 'call_endpoint_bg':
					$returned_value = $this->_call_endpoint( $o );
				break;
				case 'call_endpoint_bg_manage':
				case 'check_callpoint_progress':
				case 'reset_last_call_checkpoint':
					$returned_value = $this->_call_endpoint2( $o );
				break;
				case 'bg_process':
					$returned_value = $this->auto_bg_service();
				break;
				case 'bg_execute':
					$returned_value = $this->_bg_execute();
				break;
				case 'exec_folder_import':
					$returned_value = $this->_exec_folder_import();
				break;
				case 'stop_endpoint_bg_load':
					$returned_value = $this->_endpoint_bg_load();
				break;
				case 'pagination_default':
					$returned_value = $this->_pagination_default();
				break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		public function _class_cases(){
			// $arr = parent::_class_cases();
			$arr = [];
			return array_unique( array_merge($arr, array(
				"call_endpoint_bg",
			)) );
		}

		protected function _pagination_default( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'pagination_default':
					return array( "data" => "0" );
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		public function auto_bg_service( $opt = array() ){
			$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'status' ] ."` = 'active' AND `". $this->table_fields[ 'recurrence' ] ."` IN ( 'ss','min','hour','days','week','year' ) ";
			$r = $this->_get_records();
			$a = [];
			if( ! empty( $r ) ){
				foreach( $r as $rv ){
					$a[] = $this->_process_background_refresh( $rv );
					sleep(5);
				}
			}

			return $a;
		}

		protected function _endpoint_bg_load( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];

			$ckey = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
		
			switch( $action_to_perform ){
				case 'stop_endpoint_bg_load':
					$sett = array(
						'cache_key' => $ckey . '-bg-call-endpoint',
						'cache_values' => ['status' => 'success', 'error' => [ 'message' => '<h4>Endpoint Call Manually Stopped</h4>' ]],
						'directory_name' => $this->table_name,
						'permanent' => 1
					);

					set_cache_for_special_values( $sett );

					$e_type = 'success';

					$error_msg = "<h4><b>Background Process Stopped</b></h4><p>Endpoint Call Check Stopped by user</p>";

				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		protected function _exec_folder_import(){
			// print_r( $_POST );
			// print_r( $_GET ); 
			if( self::$skipBULK_IMPORT_MEMORY_LIMIT ){
				ini_set('memory_limit', '50M');
			}else{
				if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
					ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
				}else{
					ini_set('memory_limit', '4G' );
				}
			}

			$pp = $_POST;
			$gg = $_GET;

			if( ! ( defined( 'BULK_UPLOAD_CHECK_INTERVAL' ) && BULK_UPLOAD_CHECK_INTERVAL ) ){
				return;
			}

			if( ! ( defined( 'BULK_UPLOAD_PATH' ) && BULK_UPLOAD_PATH ) ){
				return;
			}

			if( ! ( defined( 'BULK_UPLOAD_CHUNK_SIZES' ) && BULK_UPLOAD_CHUNK_SIZES ) ){
				return;
			}

			$path2 = BULK_UPLOAD_PATH;
			$path3 = BULK_UPLOAD_PATH . '-loaded';
			$extensions = array( 'xlsx', 'xls', 'csv' );

			if( isset( $_GET[ 'folder_path' ] ) && $_GET[ 'folder_path' ] && isset( $_GET[ 'nw_table_source' ] ) && $_GET[ 'nw_table_source' ] ){
				$folderPath = $_GET[ 'folder_path' ];
				// $path2 = BULK_UPLOAD_PATH . '/' . $filename;
				$ffinfo = pathinfo( $folderPath );

				if( strpos( $_GET[ 'nw_table_source' ], '.' ) > -1 ){
					$sp = explode( '.', $_GET[ 'nw_table_source' ] );
					$_POST[ 'dtable' ] = "type=plugin:::key=".$sp[0].":::value=".$sp[1];
					// code...
				}else{
					$_POST[ 'dtable' ] = "type=package:::key=".$_GET[ 'nw_table_source' ].":::value=".$_GET[ 'nw_table_source' ];
				}

				// print_r( $folderPath );exit;
				if( isset( $ffinfo[ 'filename' ] ) && $ffinfo[ 'filename' ] ){
					$splitPath = $ffinfo[ 'dirname' ] . '\\' . $ffinfo[ 'filename' ];

					if( file_exists( $splitPath ) ){

						if( ! file_exists( $path3 ) ){
							create_folder( $path3, '', '' );
						}

						$path5 = $path3 . '/' . $ffinfo[ 'filename' ];
						if( ! file_exists( $path5 ) ){
							create_folder( $path5, '', '' );
						}

						foreach( scandir( $splitPath ) as $file ){
							$history = array();

						    if ( $file !== '.' && $file !== '..' ) {
						    	$read_message = "Reading File '". $file ."'\n";
						    	echo $read_message;
						    	$history[] = $read_message;

						    	$fullPath = $splitPath . '/'. $file;
						    	$info = pathinfo( $fullPath );

						    	if( isset( $info[ 'extension' ] ) && in_array( $info[ 'extension' ], $extensions ) ){
						    		$fp = str_replace( '\\', '/', $fullPath );
						    		$extract = str_replace( '\\', '/', HYELLA_DOC_ROOT . HYELLA_INSTALL_PATH );

						    		$_POST['_file'] = str_replace( $extract, '', $fp );
						    		$_POST['type'] = 'csv';

						    		$this->class_settings[ 'action_to_perform' ] = 'save_dataload';

						    		// print_r( $this->endpoint() );exit;
						    		$aa = $this->_save_dataload( [ 'wait4Execution' => 1 ] );

			        				rename( $fullPath, $path5 . '/' . $file );

						    	}

					    	}

				    	}
			       
			       		rename( $folderPath, $path3 . '/' . $ffinfo[ 'basename' ] );
			       		
			       		unlink( str_replace( '.csv', '.bat', $folderPath ) );
			       		rmdir( str_replace( '.csv', '', $folderPath ) );
					}
				}
				
			}

			unset( $this->class_settings["callback"] );

			// sleep( 100 );
			print_r( 'ENDED!!!' );
		}

		protected function _bg_execute(){

			$_GET = array();
			$_POST = array();

			if( ! ( defined( 'BULK_UPLOAD_CHECK_INTERVAL' ) && intval( BULK_UPLOAD_CHECK_INTERVAL ) ) ){
				return 'Undefined BULK_UPLOAD_CHECK_INTERVAL';
			}

			if( ! ( defined( 'BULK_UPLOAD_PATH' ) && BULK_UPLOAD_PATH ) ){
				return 'Undefined BULK_UPLOAD_PATH';
			}

			if( ! ( defined( 'BULK_UPLOAD_CHUNK_SIZES' ) && BULK_UPLOAD_CHUNK_SIZES ) ){
				return 'Undefined BULK_UPLOAD_CHUNK_SIZES';
			}

			$cut = time();
			$next_check2 = 0;
			$ty_mins = 60 * intval( BULK_UPLOAD_CHECK_INTERVAL ); // 10mins
			$dirname = $this->table_name;
			

			$path1 = $this->class_settings["calling_page"] . 'tmp/filescache/' . $this->class_settings[ 'database_name' ] . '/' . $this->table_name ;
			$path = $path1 . '/bulk_upload.json';

			$path2 = BULK_UPLOAD_PATH;
			$path3 = BULK_UPLOAD_PATH . '-loaded';

			if( file_exists( ( $path ) ) ){
			// if( 0 && file_exists( ( $path ) ) ){
				// echo 'yeah';
				$ltc = json_decode( file_get_contents( $path ), true );
				if( isset( $ltc["next_check"] ) ){
					$next_check2 = doubleval( $ltc["next_check"] );
				}
			}else{
				$next_check2 = $cut - $ty_mins;
			}

			if( $cut > $next_check2 ){

				if( file_exists( $path2 ) && dir( $path2 ) ){
					if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
						ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
					}else{
						ini_set('memory_limit', '4G' );
					}

				    foreach( scandir( $path2 ) as $file ){
				    	$history = array();

				        if ( $file !== '.' && $file !== '..' ) {
				        	$read_message = "Reading File '". $file ."'\n";
				        	echo $read_message;
				        	$history[] = $read_message;
				        	
							$history = $this->_splitFiles( $path2, $file, $history );
			        	}

					}
				}

				$ltc[ 'next_check' ] = $cut + $ty_mins;
				if( ! file_exists( $path1 ) ){
					create_folder( $path1, '', '' );
				}
				file_put_contents( $path, json_encode( $ltc ) );
				//print_r( $path.'---' );
				//print_r( $next_check2 );exit;
			}
		}

		protected function _validateDataforUpload( $o = array() ){
			$error_msg = '';
			$headers = [];

			//validate fields
			if( ! $error_msg ){
				if( isset( $o["type"] ) && $o["type"]  ){
					$file = ( isset( $o["file"] ) && $o["file"] )?$o["file"]:'';
					$isFile = ( isset( $o["isFile"] ) && $o["isFile"] )?$o["isFile"]:false;
					$tdata = ( isset( $o["data"] ) && $o["data"] )?$o["data"]:'';
					$fields = ( isset( $o["fields"] ) && $o["fields"] )?$o["fields"]:[];
					$labels = ( isset( $o["labels"] ) && $o["labels"] )?$o["labels"]:[];

					switch( $o["type"] ){
					case 'json':
						if( $isFile ){
							$_data = json_decode( file_get_contents( $file ), true );
						}else{
							$_data = json_decode( $tdata, true );
						}
					break;
					case 'csv':
						if( $isFile ){
							$fileObj = new SplFileObject($file);

							$fileObj->setFlags(SplFileObject::READ_CSV 
							| SplFileObject::SKIP_EMPTY 
							| SplFileObject::READ_AHEAD 
							| SplFileObject::DROP_NEW_LINE
							);
							$fileObj->setCsvControl(',');


							foreach( $fileObj as $row ){
								extract( self::validateHeaders( $row, $fields, strtoupper( $o["type"] ),$labels ) );
								break;
							}

							//$error_msg .= '<br>Halt!!!';

						}
					break;
					case 'xml':
						
					break;
					}
		
				}else{
					$error_msg = "<h4>Missing Data Type in Contract</h4><p>Acceptable data types are csv, json, xml";
				}
			}

			//ensure es table exists

			//validate loaded data
			//log
			return [
				'success' => ( ! $error_msg )?true:false,
				'headers' => $headers,
				'error' => $error_msg,
				'data' => isset($_data) ? $_data : []
			];
		}

		private function validateHeaders( $row, $fields, $type, $labels = [] ){
			$headers = [];
			$error_msg = '';
			$flipFields = array_flip( $fields );
			//06-oct-23
			//1. we removed the ID field as part of validation criteria bcos its automatically generated if empty
			//2. we removed the creation_date field as part of validation criteria bcos we need simplier date format

			if( is_array( $row ) ){
				//after october 6th 2023
				foreach( $row as $fk => $fv ){
					if( isset( $fields[ $fv ] ) || isset( $flipFields[ $fv ] ) ){
						if( isset( $flipFields[ $fv ] ) ){
							$headers[ $fk ] = $fv;
							unset( $fields[ $flipFields[ $fv ] ] );
						}else{
							$headers[ $fk ] = $fields[ $fv ];
							unset( $fields[ $fv ] );
						}
					}else{
						$headers[ $fk ] = $fv;
					}
				}
				
				if( $fields ){
					foreach($fields as $fk => $fv){
						if( isset($labels[ $fv ]['data']['hide_on_import_table']) && $labels[ $fv ]['data']['hide_on_import_table'] ){
							unset($fields[ $fk ]);
						}
						if( isset($labels[ $fv ]['data']['do_not_validate_header']) && $labels[ $fv ]['data']['do_not_validate_header'] ){
							unset($fields[ $fk ]);
						}
					}
				}

			}else{
				$error_msg = 'Malformed '. $type .' file. The '. $type .' file columns must be delimited by comma(,)';
			}

			if( empty( $headers ) ){
				//corrected my mistake pato
				if( ! $error_msg ){
					$error_msg = 'Malformed '. $type .' file. The first row of the file must contain the field names';
				}
			}else if( ! empty( $fields ) ){
				$error_msg = 'Missing Fields in '. $type .' file. The first row of the file must contain the following fields: ' . implode(", ", array_keys( $fields ) );
			}

			if( !$error_msg ){
				// $headers['created_source'] = 'created_source';
			}

			return [
				'error_msg' => $error_msg,
				'headers' => $headers,
			];
		}

		protected function _startDataUploadFg( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			extract( $o );

			if( isset($dtable) && $dtable ){

				if( class_exists('cNwp_logging') ){
					$nwp = new cNwp_logging();
					$nwp->class_settings = $this->class_settings;
					$tb = "log_main_logger";
					$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
					$logger = $in[ $tb ];
					$log_id = isset($this->class_settings['_created_source']) && $this->class_settings['_created_source'] ? $this->class_settings['_created_source'] : 'lr'.get_new_id();
				}

				$tbd = explode(":::", $dtable);
				$tba = array();
				if( ! empty( $tbd ) ){
					foreach( $tbd as $tbv ){
						$tba2 = explode( '=', $tbv );
						if( isset( $tba2[1] ) && $tba2[1] ){
							$tba[ $tba2[0] ] = $tba2[1];
						}
					}
				}


				$tb_type = isset( $tba["type"] )?$tba["type"]:'';
				$table2 = isset( $tba["value"] )?$tba["value"]:'';
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
									$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.</p>";
								}
							}else{
								$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
							}
						}else{
							$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
						}
				
					break;
					default:
						$cls = 'c' . ucwords( $dtable );
						if( class_exists( $cls ) ){
							$cl = new $cls();
						}else{
							$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
						}
					break;
				}

				if( !$error_msg ){
					if(isset($data) && $data){
						$data = json_decode($data, true);
						if( $data ){
							$lbs = $cl->table_name;
							$lbs = function_exists( $lbs ) && is_callable( $lbs ) ? $lbs() : [];
							$isLoaded = $this->_save_data_to_table( $data, [
								'table' => $dtable,
								'fields' => $cl->table_fields,
								'labels' => $lbs
							]);
							
							if( isset($isLoaded['status']) && $isLoaded['status'] ){
								
								$e_type = $isLoaded['status'];
								
								if( $e_type == 'success' ){

									if( ( isset( $cl->refresh_cache_after_save_line_items ) && $cl->refresh_cache_after_save_line_items ) ){

										if (defined("ELASTIC_DELAY_FOR_CACHE_REFRESH") && ELASTIC_DELAY_FOR_CACHE_REFRESH) {
											$sleep_value = intval(ELASTIC_DELAY_FOR_CACHE_REFRESH);
											if( $sleep_value ){
												if( get_hyella_development_mode() ){
													echo "\n.... Elastic Refresh Delay..... \n";
												}
												sleep( $sleep_value );
											}
										}
										
										$cl->class_settings = $this->class_settings;
										
										$cl->class_settings['action_to_perform'] = 'refresh_cache';
										
										$cl->$dtable();
									}
								}
								$error_msg = isset($isLoaded['msg']) && $isLoaded['msg'] ? $isLoaded['msg'] : "<h4><b>Unknown Loading Error</b></h4><p>Data Failed to Load</p>";
							}else{
								$error_msg = "<h4><b>Invlaid Load Response</b></h4><p>Unable to determine data load status</p>";
							}
						}else{
							$error_msg = "<h4><b>Invalid Data Object</b></h4><p>Failed to parse received data</p>";
						}
					}else{
						$error_msg = "<h4><b>Invalid Data Object</b></h4><p>Empty Received Data Object</p>";
					}				
				}
			}else{
				$error_msg = "<h4><b>Invalid Parameters</b></h4><p>Invalid Data Table Parameter</p>";
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
			$n_params = $this->_display_notification( $n_params );
			if( $e_type == 'error' ){
				$n_params['data']['required'] = 1;
			}

			if( isset( $logger ) ){
				$logger->_logger( __FUNCTION__, get_class( $this ), $this->plugin, [ 'reference' => $log_id, 'table' => ( isset($dtable) && $dtable ? $dtable : '' ) ], '', $error_msg, [] );
			}

			return $n_params;
		}

		protected function _startDataUploadBg( $opt = [] ){
			$error_msg = "Unknow Error... _startDataUploadBg";
			extract($opt);
			if( isset($fields) && $fields && isset($labels) ){

				$ckey = "EP".get_new_id();

				set_cache_for_special_values( array(
					'cache_key' => $this->class_settings["user_id"] . '-user-upload',
					'cache_values' => $ckey,
					'directory_name' => $this->table_name,
					'permanent' => 1,
				) );

				set_cache_for_special_values( array(
					'cache_key' => $ckey,
					'cache_values' => $opt,
					'directory_name' => $this->table_name,
					'permanent' => 1,
				) );

				set_cache_for_special_values( array(
					'cache_key' => $ckey. '-progress',
					'cache_values' => array(
						'start' => date("U"),
						'now' => date("U"),
						'status' => 'ongoing',
						'error' => ''
					),
					'directory_name' => $this->table_name,
					'permanent' => 1
				) );

				if( ! self::$isTesting ){
					run_in_background("bprocess", 0, 
						array( 
							"action" => $this->plugin, 
							"todo" => "execute", 
							"nwp_action" => $this->table_name, 
							"nwp_todo" => "save_data_to_table_bg", 
							"user_id" => $this->class_settings["user_id"], 
							"reference" => $ckey
						)
					);
				}
				
				return array(
					'status' => 'success',
					'success' => $ckey,
				);

			}else{
				$error_msg = "Undefined Parameters to format fields";
			}

			return array(
	        	'status' => 'error',
	        	'msg' => $error_msg,
	       );
		}

		protected function _loadDataContract( $opt = [] ){
			$info_msg = "";
			$error_msg = "";
			$success = false;
			$cch = [];
			$sett = [];
			if( isset( $opt['id'] ) && $opt['id'] ){
				$ckey = $opt['id'];

				$cch = get_cache_for_special_values( array(
					'cache_key' => $ckey,
					'directory_name' => $this->table_name,
					'permanent' => 1
				) );

				$sett = array(
					'cache_key' => $ckey . "-progress",
					'directory_name' => $this->table_name,
					'permanent' => 1
				);

				$setStopped = array(
					'cache_key' => $ckey . "-stopped",
					'directory_name' => $this->table_name
				);
			}

			if( !$cch ){
				$cch = $opt;
			}
			
			if( !$cch ){
				$error_msg = "<h4><b>Invalid Batch Contract</b></h4><p>Failed to load batch contract from cache</p>";
			}

			if( ! class_exists( 'cNwp_orm' ) ){
				$error_msg = "<h4><b>Missing Plugin</b></h4><p>cNwp_orm</p>";
			}

			if( ! $error_msg ){
				if( ! ( isset( $cch["table"] ) && $cch["table"] ) ){
					$error_msg = "<h4><b>Invalid Data Source</b></h4><p>Data Loading halted because no data source was specified</p>";
				}
			}


			$headers = [];
			if( ! $error_msg ){
				if( ( isset( $cch["headers"] ) && $cch["headers"] ) ){
					$headers = $cch["headers"];
				}else{
					$error_msg = "<h4><b>Invalid Field Names</b></h4><p>Data Loading halted because no field names was specified in the source file</p>";
				}
			}

			$hasChunks = '';
			$chunkSize = 0;
			if( ! $error_msg ){
				if( isset( $cch["type"] ) && $cch["type"]  ){
					$file = ( isset( $cch["file"] ) && $cch["file"] )?$cch["file"]:'';
					$isFile = ( isset( $cch["isFile"] ) && $cch["isFile"] )?$cch["isFile"]:false;
					$tdata = ( isset( $cch["data"] ) && $cch["data"] )?$cch["data"]:'';
					$addedConstantData = isset($cch['added_data']) && $cch['added_data'] ? $cch['added_data'] : [];

					switch( $cch["type"] ){
						case 'json':
							if( $isFile ){
								$_data = json_decode( file_get_contents( $file ), true );
							}else{
								$_data = json_decode( $tdata, true );
							}
						break;
						case 'csv':
							if( $isFile ){
								$chunks = self::_getRecordsPerBatch( $file );

								$error_msg = isset( $chunks["error"] )?$chunks["error"]:'';
								$info_msg = isset( $chunks["info"] )?$chunks["info"]:'';

								$chunkSize = isset( $chunks["recordsPerBatch"] )?$chunks["recordsPerBatch"]:0;
								if( $chunkSize ){
									$hasChunks = 'csv';
								}else{
									$_data = $this->_load_csv_file( ['file' => $file ] );
									if(is_string($_data)){
										$error_msg = $_data;
									}
								}

							}else{
								$_data = $this->_format_csv( $tdata );
								if( isset($_data['data']) && $_data['data'] ){
									$_data = $_data['data'];
								}else{
									$error_msg = isset($_data['error_msg']) && $_data['error_msg'] ? $_data['error_msg'] : 'Unknown Data Format Error';
								}
							}
						break;
						case 'xml':
							try{
								if( $isFile ){
									$xml = simplexml_load_file( $file );
								}else{
									$xml = simplexml_load_string( $tdata );
								}

								$_data = json_encode( $xml );
								$_data = json_decode( $_data , true);

								$error_msg = $error_msg ? $error_msg : json_last_error();

							}catch(Exception $e){
								$error_msg = $e->getMessage();
							}
						break;
					}

					if( !$error_msg ){
						if( isset($cch['fields']) && $cch['fields'] && isset($cch['labels']) && $cch['labels'] ){
							if( isset( $_data ) && $_data ){
								$_data = $this->_format_data_fields($_data, $cch['fields'], $cch['labels'], $addedConstantData);
								if( !is_array($_data) || is_string($_data) ){
									$error_msg = $_data;
								}
							}
						}else{
							$error_msg = "<h4><b>Missing Field and Labels</b></h4><p>Neccessary Fields and Labels are missing in Data</p>";
						}
					}
		
				}else{
					$error_msg = "<h4>Missing Data Type in Contract</h4><p>Acceptable data types are csv, json, xml";
				}
			}

			if( ! $error_msg ){
				$isCancelled = get_cache_for_special_values( $setStopped );
				if( isset( $isCancelled["cancelled"] ) && $isCancelled["cancelled"] ){
					$error_msg = "Operation cancelled by user";
					$sett['cache_values']['status'] = 'error';
					$sett['cache_values']['error'] = $error_msg;
				}
			}

			if( $error_msg ){
				$sett['cache_values'] = array( 'status' => 'error', 'error' => $error_msg );
			}else{
				
				try{
					$_v = null;
					$created_source = 'lr'.get_new_id();
					switch( $hasChunks ){
						case "csv":
							$fileObj = new SplFileObject($file);

							$fileObj->setFlags(SplFileObject::READ_CSV 
							| SplFileObject::SKIP_EMPTY 
							| SplFileObject::READ_AHEAD 
							| SplFileObject::DROP_NEW_LINE
							);
							$fileObj->setCsvControl(',');

							$totalCount = 0;
							$lineCounter = 0;
							$batchCounter = 0;
							$batchData = [ $headers ];
							$oInfo = $info_msg;
							$firstRow = 1;
							$totalLoadTime = 0;

							// $created_source_id = 'lr'.get_new_id();
							$created_source = '';

							foreach($fileObj as $row){
								if( $firstRow ){
									//skip first row bcos headers are already defined
									$firstRow = 0;
									continue;
								}
								$created_source = $ckey . 'b' . $batchCounter;
								$row['created_source'] = $created_source;
								$batchData[] = $row;
								
								if( $lineCounter >= $chunkSize ){

									//progress update
									$sett['cache_values']['status'] = 'ongoing';
									$sett['cache_values']['info'] = $info_msg;
									$sett['cache_values']['now'] = date("U");

									$isCancelled = get_cache_for_special_values( $setStopped );
									if( isset( $isCancelled["cancelled"] ) && $isCancelled["cancelled"] ){
										$error_msg = 'Operation cancelled by user';
										$sett['cache_values']['status'] = 'error';
										$sett['cache_values']['error'] = $error_msg;
										break;
									}

									sleep(1);
									$lineCounter = 0;
									++$batchCounter;
									$info_msg = $oInfo . '<br>Processing batch ' . $batchCounter . ' with ' . number_format( count( $batchData ) , 0 ) . ' records';

									if( self::$isTesting ){
										++$totalLoadTime;
										$info_msg .= "<br>Fake: Loaded batch " . $batchCounter;
									}else{

										$formattedData = self::_format_csv( $batchData, false);

										if( isset($formattedData['data']) && $formattedData['data'] ){
											
											$formattedData['data'] = $this->_format_data_fields( $formattedData['data'], $cch['fields'], $cch['labels'] );
											
											if( is_array($formattedData['data']) ){

												$_stat = $this->_dump_elastic_data( $formattedData['data'], $cch['table'], array(
													'created_source' => $created_source,
													'has_created_source' => true
												) );
												
												unset($formattedData['data']);
												
												if( isset($_stat['_v']) && $_stat['_v'] ){
													$totalLoadTime += doubleval( $_stat['_v']  );
													$info_msg .= "<br>Loaded batch " . $batchCounter;
												}else{
													$error_msg = "<h4>Error Loading Batch ". $batchCounter ."</h4>" . ( ( isset($_stat['error']) && $_stat['error'] )?$_stat['error']:'Batch error');
													$sett['cache_values']['status'] = 'error';
													$sett['cache_values']['error'] = $error_msg;
													break;
												}

											}else{
												$error_msg = "<h4>Error Loading Batch ". $batchCounter ."</h4>" . ( $formattedData['data'] );
												$sett['cache_values']['status'] = 'error';
												$sett['cache_values']['error'] = $error_msg;
												break;
											}
										}else{
											$error_msg = isset($formattedData['error_msg']) && $formattedData['error_msg'] ? $formattedData['error_msg'] : 'Unknown Data Format Error';
										}
									}

									if( !$error_msg ){
										$batchData = [ $headers ];

										//progress update
										$isCancelled = get_cache_for_special_values( $setStopped );
										if( isset( $isCancelled["cancelled"] ) && $isCancelled["cancelled"] ){
											$error_msg = 'Operation cancelled by user';
											$sett['cache_values']['status'] = 'error';
											$sett['cache_values']['error'] = $error_msg;
											break;
										}
										set_cache_for_special_values( $sett );
									}

								}else{
									++$lineCounter;
								}
								++$totalCount;
							}

							if( ! $error_msg && count( $batchData ) > 1 ){
								++$batchCounter;
								$info_msg .= '<br>Processing final batch ' . $batchCounter . ' with ' . number_format( count( $batchData ) , 0 ) . ' records';

								if( self::$isTesting ){
									++$totalLoadTime;
									$info_msg .= "<br>Fake: Loaded batch " . $batchCounter;
								}else{
									
									$formattedData = self::_format_csv( $batchData, false);

									if( isset($formattedData['data']) && $formattedData['data'] ){

										$formattedData['data'] = $this->_format_data_fields( $formattedData['data'], $cch['fields'], $cch['labels'] );

										if( is_array($formattedData['data']) ){
											$_stat = $this->_dump_elastic_data( $formattedData['data'], $cch['table'], array(
													'created_source' => $created_source,
													'has_created_source' => true
												) );
											unset($formattedData['data']);
											if( isset($_stat['_v']) && $_stat['_v'] ){
												$totalLoadTime += doubleval( $_stat['_v']  );
												$info_msg .= "<br>Loaded final batch " . $batchCounter;
											}else{
												$error_msg = "<h4>Error Loading Batch ". $batchCounter ."</h4>" . ( ( isset($_stat['error']) && $_stat['error'] )?$_stat['error']:'Batch error');
												$sett['cache_values']['status'] = 'error';
												$sett['cache_values']['error'] = $error_msg;
											}

										}else{
											$error_msg = "<h4>Error Loading Batch ". $batchCounter ."</h4>" . ( $formattedData['data'] );
											$sett['cache_values']['status'] = 'error';
											$sett['cache_values']['error'] = $error_msg;
											break;
										}

									}else{
										$error_msg = isset($formattedData['error_msg']) && $formattedData['error_msg'] ? $formattedData['error_msg'] : 'Unknown Data Format Error';
									}
								}
								
								$batchData = [];
							}

							if( ! $error_msg ){
								$error_msg = "<h4>Data Loaded Successfully II</h4>";
								if( self::$isTesting ){
									$error_msg = "<h4>Fake: Data Loaded Successfully</h4>";
								}
								$error_msg .= '<p>Total of ' . number_format( $batchCounter , 0 ) . ' batches with ' . number_format( $totalCount, 0 ) . ' records</p>';
								$sett['cache_values'] = array( 'status' => 'complete', 'time' => $totalLoadTime, 'error' => $error_msg );
								$success = true;
							}
						break;
						default:
							if( self::$isTesting ){
								$error_msg = "<h4>Fake: Data Loaded Successfully</h4>";
								$sett['cache_values'] = array( 'status' => 'complete', 'time' => 0.1980 , 'error' => $error_msg );
								$success = true;
							}else{
								$_stat = $this->_dump_elastic_data( $_data, $cch['table'], array(
									'has_created_source' => true,	//26-oct-23 steve last change
									'created_source' => $created_source,
								) );

								if( isset($_stat['_v']) && $_stat['_v'] ){
									$error_msg = "<h4>Data Loaded Successfully I</h4>";
									$sett['cache_values'] = array( 'status' => 'complete', 'time' => $_stat['_v'] , 'error' => $error_msg );
									$success = true;
								}else{
									$error_msg = "<h4>Data Load Error B1</h4>" . ( ( isset($_stat['error']) && $_stat['error'] )?$_stat['error']:'System resource exhausted');
									$sett['cache_values'] = array( 'status' => 'error', 'error' => $error_msg );
								}
							}
						break;
					}
					
				}catch(Throwable $e ){
					
					$error_msg = "<h4><b>Failed To Load Data</b></h4><p>". $e->getMessage() ."</p>";
					$sett['cache_values'] = array( 'status' => 'error', 'error' => $error_msg );

				}catch(Exception $e ){
					
					$error_msg = "<h4><b>Failed To Load Data</b></h4><p>". $e->getMessage() ."</p>";
					$sett['cache_values'] = array( 'status' => 'error', 'error' => $error_msg );
				}
			}
			
			if( strlen( $info_msg ) > 600 ){
				$sett['cache_values']['info'] = substr( $info_msg, -600 );
			}else{
				$sett['cache_values']['info'] = $info_msg;
			}
			$sett['cache_values']['now'] = date("U");
			set_cache_for_special_values( $sett );

			return [
				'chunks' => isset( $chunks ) ? $chunks : [] ,
				'success' => $success,
				'error' => $error_msg,
				'dtable' => isset($cch['table']) && $cch['table'] ? $cch['table'] : ''
			];
		}

		protected function _getRecordsPerBatch( $file ){
			$error_msg = '';
			$info_msg = '';
			$recordsPerBatch = 0;
			$estBatchCount = 0;

			$extensions = array( 'csv' );
			$info = pathinfo( $file );

			$recordColsCount = 32;
			
			$recordMultiplier = 3;	//2 records = 1KB for 32 fields/columns
			$recordMultiplierCols = 32;
			if( defined( 'NWP_NUMBER_OF_RECORDS_PER_KILOBYTE' ) && NWP_NUMBER_OF_RECORDS_PER_KILOBYTE ){
				$recordMultiplier = intval( NWP_NUMBER_OF_RECORDS_PER_KILOBYTE );
			}

			if( isset( $info[ 'extension' ] ) && in_array( $info[ 'extension' ], $extensions ) ){
				$filename = $info[ 'filename' ];

				//get accepted file size based on system memory settings & available memory
				$maxFileSize = cNwp_endpoint::getMaxFileSize();

				$fileSize = filesize( $file );
				if( $fileSize > $maxFileSize ){
					//convert max file size to no of lines of records
					$recordsPerBatch = floor( ( $maxFileSize / 1000 ) * ( $recordMultiplier / floor( $recordColsCount / $recordMultiplierCols ) ) );
					$estBatchCount = ceil( ( $fileSize/ $maxFileSize ) );
					
					$info_msg = 'Splitting into a rough estimate of '. number_format( $estBatchCount, 0 ) .' batches of '. number_format( $recordsPerBatch, 0 ) .' records per batch for ' . $filename;
				}else{
					$info_msg = 'Acceptable File Size of '. cNwp_app_core::nw_pretty_number( $fileSize ) .' for ' . $filename;
				}
			}else{
				$error_msg = 'Invalid File Type. Only '. implode(", ", $extensions) .' files are accepted';
			}

			return [
				'batchCount' => $estBatchCount,
				'recordsPerBatch' => $recordsPerBatch,
				'info' => $info_msg,
				'error' => $error_msg,
			];
		}

		private function _splitFilesWindows( $path2, $file ){
			//archived
			$extensions = array( 'xlsx', 'xls', 'csv' );
			$info = pathinfo( $path2 . '/'. $file );

			if( isset( $info[ 'extension' ] ) && in_array( $info[ 'extension' ], $extensions ) ){
				$ext = isset( $info[ 'extension' ] ) ? $info[ 'extension' ] : '';
				$filename = $info[ 'filename' ];

				if( strpos( $info[ 'filename' ], ' ' ) > -1 ){
					// print_r( $file );
					$filename = str_replace( " ", "-", trim( $info[ 'filename' ] ) );
					
					rename_win( $info[ 'dirname' ] . '/' . $info[ 'basename' ], $info[ 'dirname' ] . '/' . $filename.'.'.$ext );
					// rename( $info[ 'dirname' ] . '/' . $info[ 'basename' ], $info[ 'dirname' ] . '/' . $filename.'.'.$ext );
					$file = $filename.'.'.$ext;
					$info[ 'filename' ] = $filename;
					// print_r( $info[ 'filename' ] );exit();
				}
	
				$path4 = $path2 . '/' . $filename;
				if( ! file_exists( $path4 ) ){
					create_folder( $path4, '', '' );
				}
	
				$codes = HYELLA_DOC_ROOT . HYELLA_INSTALL_PATH . 'php/split_file.vbs "'. $path2 . '/'. $file .'" "'. $path4 . '/' . $filename .'" '. BULK_UPLOAD_CHUNK_SIZES .' "C:\xampp\php\php.exe" "'.HYELLA_DOC_ROOT . HYELLA_INSTALL_PATH . 'php\bprocess.php" "action@@'. $this->plugin .':::todo@@execute:::nwp_action@@'. $this->table_name .':::nwp_todo@@exec_folder_import:::user_id@@system:::wait_for_execution@@0:::show_window@@1:::key@@7jk2ohpo95ln74fsr2vpk190i7" ';
	
				if( get_hyella_development_mode() ){
					$codes .= "\n".' pause';
				}
	
				$batname = $path2 . '/' . $filename . '.bat';
	
				file_put_contents( $batname, $codes );
	
				run_in_background( $batname, 0, 
					array(
						"wait_for_execution" => 1,
						"show_window" => 0,	
						"absolute_bat_path" => 1,	
						// "argument4" => $converted,
					)
				);

			}
		}

		protected function _process_background_refresh( $opt = [] ){
			if( ! ( isset( $opt[ 'id' ] ) && $opt[ 'id' ] ) ){
				return;
			}

			$bdpk = "bg-". $opt[ 'id' ] ."-in-progress";

			$bdpk_exec = 0;
			$ty_mins = 0;
			$value = isset( $opt[ 'recurrence_value' ] ) ? doubleval( $opt[ 'recurrence_value' ] ) : 0;

			if( ! ( $opt[ 'recurrence' ] || $value ) ){
				return;
			}

			switch( $opt[ 'recurrence' ] ){
				case 'none':
				break;
				case 'ss':
					$ty_mins = $value;
				break;
				case 'min':
					$ty_mins = $value * 60;
				break;
				case 'hour':
					$ty_mins = $value * 60 * 60;
				break;
				case 'days':
					$ty_mins = $value * 60 * 60 * 24;
				break;
				case 'week':
					$ty_mins = $value * 60 * 60 * 24 * 7;
				break;
				case 'year':
					$ty_mins = $value * 60 * 60 * 24 * 7 * 52;
				break;
			}

			if( ! $ty_mins ){
				return;
			}

			$settings = array( 'cache_key' => $bdpk, );
			$s = get_cache_for_special_values( $settings );
			if( get_hyella_development_mode() ){
				$s = 0;
			}

			if( !$s ){

				$_GET = array();
				$_POST = array();

				$settings = array(
					'cache_key' => $bdpk,
					'cache_values' => 1,
				);
				$s = set_cache_for_special_values( $settings );
				
				$cut = time();
				$next_check2 = 0;

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
				// if( 0 && file_exists( ( $dirname . '/'. $bdpk .'.json' ) ) ){
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

					$_POST[ 'id' ] = $opt[ 'id' ];
					$this->class_settings[ 'bg_process' ] = 1;
					$this->class_settings[ 'action_to_perform' ] = 'call_endpoint';
					$this->endpoint();

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
				}
				
				$settings = array( 'cache_key' => $bdpk );
				$s = clear_cache_for_special_values( $settings );
			}

			if( $bdpk_exec ){
				return [ 'executed' => 1 ];
			}else{
				return [ 'executed' => 0 ];
			}
		}

		protected function _call_endpoint2( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
			$ckey = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
		
			switch( $action_to_perform ){
				case 'check_callpoint_progress':
					if( $ckey ){
						if( !$error_msg ){
							$sett = array(
								'cache_key' => $ckey . '-bg-call-endpoint',
								'directory_name' => $this->table_name,
								'permanent' => 1
							);
							
							sleep(10);
							$n = get_cache_for_special_values( $sett );
							
							if( isset($n['notif']) && $n['notif'] ){
								clear_cache_for_special_values( $sett );
								$n['notif']['javascript_functions'][] = '$nwProcessor.reload_datatable';
								return $n['notif'];
							}
							
							if( isset( $n['status'] ) && $n['status'] ){
								switch ($n['status']) {
									case 'ongoing':
										$e_type = 'info';
										$error_msg = "<h4><b>Data Loading</b></h4><p>Data Loading Processing ...</p>";

										$cancelBtn = ' <a href="javascript:;" class="btn btn-sm btn-danger custom-single-selected-record-button" override-selected-record="'. $ckey .'" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=stop_endpoint_bg_load&html_replacement_selector='. $handle .'"  title="Stop Currrent Operation" confirm-prompt="Stop Data Loading Operation">Stop</a> ';

										$manualBtn = '<br><a href="javascript:;" class="btn btn-sm dark custom-single-selected-record-button" override-selected-record="'. $ckey .'" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=check_callpoint_progress&html_replacement_selector='. $handle .'" id="manual-progress-check" title="Manual Progress Check... wait 5 seconds before clicking">Manual Progress Check</a>'. $cancelBtn .'<br>';

										$error_msg .= $manualBtn;

										$this->class_settings['callback'][ $this->table_name ][ $this->class_settings['action_to_perform'] ] = array(
											'id' => $ckey,
											're_process_delay' => 5000,
											'action' => "?action=". $this->plugin . "&todo=execute&nwp_action=". $this->table_name . "&nwp_todo=check_callpoint_progress"
										);
									break;
									case 'error':
									case 'success':
										$error_msg = isset($n['error']['message']) && $n['error']['message'] ? $n['error']['message'] : "<h4><b>Data Load Halt</b></h4><p>Unable to define error</p>";
										$e_type = $n['status'];
										clear_cache_for_special_values( $sett );
									break;
								}

							}else $error_msg = "<h4><b>Invalid Data Loading</b></h4><p>Failed to verify background state</p>";

						}
					}else $error_msg = "<h4><b>Invalid Cache</b></h4><p>Invalid Cache Key</p>";
				break;
				case 'reset_last_call_checkpoint':
					if( $ckey ){
						// $sett = array(
						// 	'cache_key' => $ckey .'-last-run',
						// 	'directory_name' => $this->table_name,
						// 	'permanent' => true
						// );

						// clear_cache_for_special_values( $sett );
						$this->class_settings['update_fields']['last_run'] = "";
						$r = $this->_update_table_field();
						if( isset($r['saved_record_id']) && $r['saved_record_id'] ){
							$e_type = 'success';
							$error_msg = "<h4><b>Endpoint Cache Refreshed</b></h4><p>Endpoint cache call refreshed correctly</p>";
							// $n_params['callback'][] = '$nwProcessor.reload_datatable';
							$n_params['html_replacement_selector'] = '#datatable-split-screen-endpoint';
						}else{
							return $r;
						}

					}else $error_msg = "<h4><b>Invalid Endpoint</b></h4><p>Select a valid record</p>";
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _display_home( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			$filename = 'home';
			switch( $action_to_perform ){
				case 'display_home':
					$this->class_settings['data'] = array(
						'table' => $this->table_name,
						'plugin' => $this->plugin
					);
				break;
				case 'format_react_data':
					$_action = isset($_GET['_a']) && $_GET['_a'] ? $_GET['_a'] : "";
					if( $_action ){
						$tb = "react_handler";
						$filename .= '/content.php';
						$in = $this->plugin_instance->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
						$in[ $tb ]->class_settings['action_to_perform'] = $_action;
						if( isset($_GET['nwp_tab']) && $_GET['nwp_tab'] ){
							$_GET['nwp_action'] = $tb;
							$_GET['nwp_todo'] = $_action;
							$_GET['nwp_target'] = 1;
							unset($_GET['nwp_tab']);
							unset( $_GET['_a'] );
							unset( $_GET['html_replacement_selector'] );
							$url = http_build_query( $_GET );
							$this->class_settings['data']['iframe'] = $url;
						}else{
							$r = $in[ $tb ]->$tb();
							$this->class_settings[ 'data' ][ 'r' ] = isset( $r['data'] ) && $r['data'] ? $r['data'] : $r;
						}
						$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
						$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;

					}else $error_msg = "<h4><b>Invalid Action</b></h4><p>Kindly provide an action</p>";
				break;
			}

			if( !$error_msg ){
				
				$this->class_settings['html'] = array( $this->view_path . $filename );
				
				$html = $this->_get_html_view();

				return array(
					'status' => 'new-status',
					'html_replacement' => $html,
					'html_replacement_selector' => '#'.$handle,
					'javascript_functions' => $js
				);
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _get_select2(){
			$join = '';
			$where = '';
			$limit = '';
			$search_term = '';
			$action_to_perform = $this->class_settings["action_to_perform"];
			$formId = isset($_GET['form_id']) && $_GET['form_id'] ? $_GET['form_id'] : '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` REGEXP '".$search_term."' OR `".$this->table_name."`.`serial_num` = '".$search_term."' ) ";
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num` ";

			if( $formId ){
				$where .= " AND `". $this->table_name ."`.`id` <> '". $formId ."' ";
			}
			
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
					case "name":
						$select .= ", `". $val ."` as 'text' ";
					break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
				}

				if( isset( $_POST[ $key ] ) && $_POST[ $key ] ){
					$where .= " AND `".$val."` = '".$_POST[ $key ]."' ";
				}
			}
												
			if( $join ){
				$this->class_settings["join"] = $join;
			}

			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$this->class_settings['limit'] = $limit;
			
			$sqlx = $this->_get_records();
			$sqlx = utf8ize( $sqlx );

			return array( "items" => $sqlx, "do_not_reload_table" => 1 );
		}


		// To whom it may concern, this function is the deepest depth of hell and hades, and the final boss of the afterlife
		// I dont know how anything here works and I fear for the distraught you may feel when attempting to maintain/understand this
		// Lord knows what was going through my mind when it was written
		// If it helps, I added some commentary when life began to make sense again
		// Proceed further at your own peril 🙂
		// YOU HAVE BEEN WARNED 💀
		protected function _call_endpoint( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];

			$id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';

			if( !$id ){
				$error_msg = "<h4><b>Invalid Reference</b></h4><p>Kindly select a valid record</p>";
			}

			$set_state = 1;
			$skip_status_check = 0;

			$tb = "endpoint_log";
			$in = $this->plugin_instance->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
			$log = [];
			$callback_error = null;
			
			$start_time = microtime(true);
			$mem_start = memory_get_usage();
		
			if( !$error_msg ){
				$this->class_settings['current_record_id'] = $id;
				$this->class_settings['do_not_check_cache'] = 1;
				$e = $this->_get_record();

				$sleep_time = 0;

				$log['new_id'] = 'eog'.get_new_id();
				$log['date'] = date("U");
				$log['status'] = 'failed';
				if( self::$isTesting ){
					$log['data']['Testing'] = 1;
				}

				if( !(isset($e['id']) && $e['id'] ) ){
					$error_msg = "<h4>Invalid Endpoint Call</h4><p>Endpoint does not exist</p>";
				}

				if( !$error_msg ){
					switch( $action_to_perform ){
						case 'call_endpoint':
						case 'call_endpoint_bg':
							if( isset($_GET['_parent']) && $_GET['_parent'] ){
								// Used to tied an endpoint callback to an ancestor
								$e['parent'] = $_GET['_parent'];
								unset($_GET['_parent']);
							}
							
							if( isset($o['_data']) && $o['_data'] ){
								$e = array_merge( $e, $o['_data'] );
								if( isset($e['reference']) && $e['reference'] ){
									$set_state = 0;
									$skip_status_check = 1;
								}
							}

							if( isset($e['id']) && $e['id'] ){
								
								// $_opt =  {
								//   "auth_key": "headers, Authorization-Token",
								//   "transform_type": "csv_to_json", [csv_to_json, json_to_json]
								//   "count_key": "data, count",
								//   "data_key": "data, wards, data",
								//   "start_row": 1,
								//   "pagination_sleep": 10,
								//   "starting_page": 1,        initial_page
								//   "expected_page_size": 5 ,  required for mismatching pages
								//   "count_type": "records",  [records, pages, record_offset, page_offset ],
								//   "max_number_records": 200, maximum number of records per endpoint call
								//   "page_increment":{
								//     "increment_locator": "body", [body, url]
								//     "increment_string": "@val@"
								//   }
								// }


								$log['endpoint'] = $e['id'];
								$log['reference'] = isset($e['reference']) && $e['reference'] ? $e['reference'] : '';
								$log['parent'] = isset($e['parent']) && $e['parent'] ? $e['parent'] : '';

								switch( $action_to_perform ){
									case 'call_endpoint':
										// Check if current endpoint call status is active before continuing
										// 08-10-2023 //Skipping status check for children/pagination/authentication callbacks, 
										// Reason: performance overhead for children calls
										if( !$skip_status_check ){
											if( isset($e['status']) && $e['status'] ){
												switch ($e['status']) {
													case 'active':
														if( !$log['reference'] ){
															$tmp = 'pending-'. get_new_id() . date("U"); // Temp status id uniquely identifying a call
															$this->_update_records( array(
																'where' => " `id` = '". $e['id'] ."' AND `". $this->table_fields['status'] ."` = 'active' ",
																'field_and_values' => array(
																	$this->table_fields['status'] => ['value' => $tmp ]
																)
															) );

															// Cant remeber why we decided to do this, but I remember it was important
															// I think because of background calls, so they dont get mixed up with other background calls
															$this->class_settings['current_record_id'] = $e['id'];
															$this->class_settings['do_not_check_cache'] = 1;
															$e = $this->_get_record();
															if( isset($e['status']) && $e['status'] ){
																switch ( $e['status'] ) {
																	case $tmp:
																		$_POST['id'] = $e['id'];
																		$this->class_settings['update_fields']['status'] = 'in_progress';
																		$this->_update_table_field();
																		$e['status'] = 'in_progress';
																	break;
																	default:
																		$error_msg = "<h4><b>1. Access Denied</b></h4><p>Endpoint Record is in an invalid state: ". $e[ 'status' ] ."</p>";
																	break;
																}															
															}
														}
													break;
													default:
														if( !$log['reference'] ){
															$error_msg = "<h4><b>2. Access Denied</b></h4><p>Endpoint Record is in an invalid state: ". $e[ 'status' ] ."</p>";
														}
													break;
												}
											}
										}
									break;
									case 'call_endpoint_bg':
										if( isset($e['status']) && $e['status'] ){
											switch ($e['status']) {
												case 'in_progress':
												break;
												default:
													if( !self::$isTesting ){
														$error_msg = "<h4><b>Access Denied</b></h4><p> Background Process For Endpoint Record is in an invalid state: ". $e[ 'status' ] ."</p>";
													}
												break;
											}
										}
									break;
								}

								if( !$error_msg ){
									$sett2 = [];
									switch ( $action_to_perform ) {
										case 'call_endpoint':
											if( isset($e['count_endpoint']) && $e['count_endpoint'] && isset($e['callback']) && $e['callback'] ){
												switch ($e['callback']) {
													case 'load_data':
														// Initiates the background process to run the call and load data into elastic search
														$sett2 = array(
															'cache_key' => $e['id'] . '-bg-call-endpoint',
															'cache_values' => array( 'status' => 'ongoing', 'error' => [] ),
															'directory_name' => $this->table_name,
															'permanent' => 1
														);

														set_cache_for_special_values( $sett2 );

														$_args_ = array( 
															"action" => $this->plugin, 
															"todo" => "execute", 
															"nwp_action" => $this->table_name, 
															"nwp_todo" => "call_endpoint_bg", 
															"user_id" => $this->class_settings["user_id"], 
															"reference" => $e['id']
														);

														// Link current log to common log ancestor: log trail
														if( $log['parent'] ){
															$_args_['_parent'] = $log['parent'];
														}

														$set_state = 0;
														if( !self::$isTesting ){
															$rbg = run_in_background("bprocess", 0, $_args_ );
														}else{
															return [ 'data' => $_args_ ];
														}

														$log = [];
														$e_type = 'info';
														$error_msg = "<h4><b>Endpoint Call Initialised</b></h4><p>Data Loading Processing....</p>";
														$mc = 1;
														if( isset( $rbg['error_msg'] ) && $rbg['error_msg'] ){
															$error_msg = $rbg['error_msg'];
															$e_type = 'error';
															$mc = 0;
															$_POST['id'] = $e['id'];
															$this->class_settings['update_fields']['status'] = 'active';
															$this->_update_table_field();
														}

														if( isset($rbg['executed']) && $rbg['executed'] ){
															$error_msg = "<h4><b>Endpoint Call Initialised</b></h4><p>Endpoint Call has been added to queue. You will be notified when job is completed</p>";
															// $mc = 0;
														}
														
														if( $mc ){
															$this->class_settings['callback'][ $this->table_name ][ $this->class_settings['action_to_perform'] ] = array(
																'id' => $e['id'],
																'action' => "?action=". $this->plugin . "&todo=execute&nwp_action=". $this->table_name . "&nwp_todo=check_callpoint_progress"
															);															
														}
													break;
												}
											}
										break;
										case 'call_endpoint_bg':
											$sett2 = array(
												'cache_key' => $e['id'] . '-bg-call-endpoint',
												'directory_name' => $this->table_name,
												'permanent' => 1
											);
										break;
									}
								}

								if( !$error_msg ){

									$_opt = isset( $e['options'] ) && $e['options'] ? json_decode( $e['options'], true ) : [];

									$sleep_time = defined( "ENDPOINT_PAGINATION_SLEEP" ) && ENDPOINT_PAGINATION_SLEEP ? (int)ENDPOINT_PAGINATION_SLEEP : $sleep_time;
									if( isset($_opt['pagination_sleep']) && $_opt['pagination_sleep'] ){
										$sleep_time = (int)$_opt['pagination_sleep'];
									}

									// GET authentication and Authorizaton to proceed with the endpoint call
									if( isset($e['authentication']) && $e['authentication'] ){
										$_POST['id'] = $e['authentication'];
										$this->class_settings['action_to_perform'] = 'call_endpoint';
										$x = $this->_call_endpoint( [ 'return_data' => 1, '_data' => [
											'type2' => 'authentication_call',
											'reference' => $e['id'],
											'parent' => $log['new_id'] //Log Trail/ Ancestor: @Line 1624
											 ] ] 
										);

										// Authentication Call fails
										// Saves to log and end endpoint call
										if( isset($x['data']['required']) && $x['data']['required'] ){

											$log['message'] = '<h3>Failed to authenticate endpoint.</h3>'. ( isset($x['err']) && $x['err'] ? $x['err'] : '' ) . ( isset($x['msg']) && $x['msg'] ? $x['msg'] : '' );
											
											if( $e['headers'] ){
												$log['request_params']['headers'] = $e['headers'];
											}
											
											if( $e['body'] ){
												$log['request_params']['body'] = $e['body'];
											}
											
											if( $_opt ){
												$log['request_params']['options'] = $_opt;
											}

											$log['data']['execution_time'] = (microtime(true) - $start_time);
											$log['data']['memory_usage'] = ((memory_get_usage() - $mem_start) / 1024);

											$log = fLog( $log );
											$in[ $tb ]->class_settings['line_items'][] = $log;
											$in[ $tb ]->_save_line_items();

											if( self::$isTesting ){
												$x['data']['log'] = $log['new_id'];
											}
											
											switch ($action_to_perform) {
												case 'call_endpoint_bg':
													$sett2['cache_values']['notif'] = $x;
													set_cache_for_special_values( $sett2 );
												break;
											}

											$log = [];
											$callback_error = $x;
										}else{
											$auth_key = isset( $_opt['auth_key'] ) && $_opt['auth_key'] ? $_opt['auth_key'] : '';
											if( $auth_key ){
												$auth_key = array_map('trim', explode(',', $auth_key) );
												$authval = [];
												foreach ( $auth_key as $key ) {
													if( !$authval ){
														$authval = isset($x[ $key ]) && $x[ $key ] ? $x[ $key ] : [];
													}else{
														$authval = isset($authval[ $key ]) && $authval[ $key ] ? $authval[ $key ] : $authval;
													}
												}

												if( is_array($authval) ){
													$error_msg = "<h4><b>Invalid Authorization</b></h4><p>Unable for verify Auth Token</p>";
												}else{
													$e['headers'] = json_decode($e['headers'], true);
													$e['headers'][] = "Authorization: Bearer ". $authval;
													$e['headers'] = json_encode( $e['headers'] );
												}
											}
										}
									}
								}

								if( !$error_msg && !$callback_error ){
									$call_start_time = 0;
									$call_end_time = date("U");

									// Temporary solution to missing records for date filter
									if ( defined("IGNORE_ENDPOINT_DATE_FILTERS") && constant("IGNORE_ENDPOINT_DATE_FILTERS") ) {
										$call_end_time += 84600;
									}

									// Call pagination callback 
									// Cant remember why I called it count_endpoint but ... we move
									if( isset( $e['count_endpoint']) && $e['count_endpoint'] && !( isset($e['reference']) && $e['reference'] ) ){
										
										$x = [];
										$n = isset($e['last_run']) && $e['last_run'] ? json_decode($e['last_run'], true) : []; // Pagination Parameters saved from the last time the endpoint call was made
										$log['data']['prev_last_run'] = $n;

										$count_key = isset( $_opt[ 'count_key' ] ) && $_opt[ 'count_key' ] ? $_opt['count_key'] : '';
										$count_type = isset( $_opt[ 'count_type' ] ) && $_opt[ 'count_type' ] ? $_opt[ 'count_type' ] : '';
										$call_count = 0;
										switch ( $count_type ) {
											case 'pages':
											case 'records':
												$call_count = 1;
											break;
											default:
												$call_count = !$n ? 1 : $call_count;
												if( !$call_count ){
													$x = $n;
												}
											break;
										}

										if( $call_count ){
											$_POST['id'] = $e['count_endpoint'];
											$_a = $e;
											unset( $_a['url'] );
											unset( $_a['body'] );
											unset( $_a['options'] );
											unset( $_a['callback'] );
											unset( $_a['headers'] );
											unset( $_a['name'] );
											unset( $_a['type'] );
											unset( $_a['status'] );
											unset( $_a['datatable'] );
											unset( $_a['last_run'] );
											unset( $_a['id'] );

											
											$_a['authentication'] = '';
											$_a['count_endpoint'] = '';
											$_a['type2'] = 'pagination_count_call'; //Type to indentify child call
											$_a['reference'] = $e['id'];
											$_a['parent'] = $log['new_id'];

											$this->class_settings['action_to_perform'] = 'call_endpoint';
											$x = $this->_call_endpoint( [ 'return_data' => 1, '_data' => $_a ] );
										}

										if( isset($x['data']['required']) && $x['data']['required'] ){
											$log['message'] = '<h3>Failed to utilize AdHOC endpoint </h3>'. ( isset($x['err']) && $x['err'] ? $x['err'] : '' ) . ( isset($x['msg']) && $x['msg'] ? $x['msg'] : '' );
											if( $e['headers'] ){
												$log['request_params']['headers'] = $e['headers'];
											}
											if( $e['body'] ){
												$log['request_params']['body'] = $e['body'];
											}
											if( $_opt ){
												$log['request_params']['options'] = $_opt;
											}

											$log['data']['execution_time'] = (microtime(true) - $start_time);
											$log['data']['memory_usage'] = ((memory_get_usage() - $mem_start) / 1024);

											$log = fLog( $log );
											$in[ $tb ]->class_settings['line_items'][] = $log;
											$in[ $tb ]->_save_line_items();

											if( self::$isTesting ){
												$x['data']['log'] = $log['new_id'];
											}

											switch ($action_to_perform) {
												case 'call_endpoint_bg':
													$sett2['cache_values']['notif'] = $x;
													set_cache_for_special_values( $sett2 );
												break;
											}
											$log = [];
											$callback_error = $x;
										}

										if( !$callback_error ){
												
											if( $count_key ){
												$count_value = $x;
												$count_key = array_map('trim', explode(',', $count_key)); 
												foreach ( $count_key as $key ) {
													$count_value = isset( $count_value[ $key ] ) ? $count_value[ $key ] : $count_value;
												}

												if( is_array($count_value) ){
													$error_msg = "<h4><b>AdHOC Count Response Error</b></h4><p>Unable to locate config count value from Count Endpoint Response</p>";
													if( $n ){
														$error_msg = "<h4><b>AdHOC Count Response Error</b></h4><p>Unable to locate config count value from Last Run Response</p>";
													}
												}
											}

											if( !$error_msg ){
												$page_counter = isset($_opt['starting_page']) && $_opt['starting_page'] ? (int)$_opt['starting_page'] : 0;
												$increment_parameter = isset($_opt['page_increment']) && $_opt['page_increment'] ? $_opt['page_increment'] : [];
												$skipInitPage = isset( $_opt['skip_init_page_from_last_run'] ) && $_opt['skip_init_page_from_last_run'] ? true : false;
												if( !$skipInitPage ){
													$skipInitPage = ( strpos( $e['body'], '@call_start_time@') !== false && strpos( $e['body'], '@call_end_time@' ) !== false );
												}
												switch ( $count_type ) {
													case 'page_offset':
													case 'record_offset':
														$page_counter = isset($_opt['starting_page']) ? $page_counter : $count_value;
														if( $n && !$skipInitPage ){
															$page_counter = $count_value;
															switch ($count_type) {
																case 'page_offset':
																	$page_counter++;
																break;
															}
														}
													break;
													case 'pages':
													case 'records':
														if( $n && !$skipInitPage ){
															$count_value2 = $n;
															foreach ( $count_key as $key ) {
																$key = trim($key);
																$count_value2 = isset( $count_value2[ $key ] ) && $count_value2[ $key ] ? $count_value2[ $key ] : $count_value2;
															}
															if( is_array($count_value2) ){
																$error_msg = "<h4><b>AdHOC Count Response Error</b></h4><p>Unable to locate config count value from Last Run Response</p>";
															}
															if( !$error_msg ){
																switch ( $count_type ) {
																	case 'pages':
																		if( $count_value2 < $count_value ){
																			$page_counter = $count_value2 + 1;
																		}
																	break;
																	default:
																		$page_counter = (int)$count_value2 + 1;
																	break;
																}
															}
														}
													break;
												}

												if( !$error_msg ){

													if( isset( $n['date_filter']) && $n['date_filter'] && !(defined("IGNORE_ENDPOINT_DATE_FILTERS") && constant("IGNORE_ENDPOINT_DATE_FILTERS")) ){
														if( isset( $n['date_filter']['call_start_time'] ) && isset( $n['date_filter']['call_end_time']) ){
															if( isset( $n['mismatching_page_size']) && $n['mismatching_page_size'] ){
																$call_start_time = intval( $n['date_filter']['call_start_time'] );
																$call_end_time = intval( $n['date_filter']['call_end_time'] );
															}else{
																$call_start_time = intval( $n['date_filter']['call_end_time'] );
															}
														}
													}
													
													$e['body'] = str_replace( '@call_start_time@', $call_start_time, $e['body']);
													$e['body'] = str_replace( '@call_end_time@', $call_end_time, $e['body']);


													// 24-07-2024 @steve 
													// mismatching_page_size is no longer in use as we now replace pulled records

													// if( isset( $n['mismatching_page_size']) && $n['mismatching_page_size'] ){
														// if( isset($n['mismatching_page_size']['page_counter']) && $n['mismatching_page_size']['page_counter'] ){
															// $page_counter = $n['mismatching_page_size']['page_counter'];
														// }
													// }
													
													if( $increment_parameter ){
														if( isset($increment_parameter['increment_locator']) && $increment_parameter['increment_locator'] && ( isset($increment_parameter['increment_string']) && $increment_parameter['increment_string'] ) ){
															switch ( $increment_parameter['increment_locator'] ) {
																case 'body':
																	$e['obody'] = $e['body'];
																	$e['body'] = str_replace( $increment_parameter['increment_string'], $page_counter, $e['body']);
																break;
																case 'url':
																	$e['ourl'] = $e['url'];
																	$e['url'] = str_replace( $increment_parameter['increment_string'], $page_counter, $e['url']);
																break;
															}
														}else $error_msg = "<h4><b>Invalid Configuration</b></h4><p>Increment Location Definition Not Found</p>";

													}else $error_msg = "<h4><b>Invalid Configuration</b></h4><p>No Page Increment Definition Found</p>";
												}
											}
										}
									}
								}

								if( !$callback_error ){
									$response = '';
									if( !$error_msg ){
										try{
											$_headers = [];
											$content_type = 0;
											if( isset( $e['headers']) && $e['headers'] ){
												$h = json_decode($e['headers'], true);
												if( $h && is_array( $h ) ){
													foreach ($h as $_hKey => $_hValue) {
														$_headers[] = $_hKey . ": ".$_hValue;
														if( strpos($_hKey, "Content-Type") !== false ){
															$content_type = 1;
														}
													}													
												}
											}
											
											if( !$content_type ){
												$_headers[] = 'Content-Type: application/json';
											}

											$opts = array('http' =>
											    array(
											        'method'  => strtoupper( $e['type'] ),
											        'header'  => $_headers,
											        'content' => $e['body'],
											        // 'ignore_errors' =>true
											    )
											);

											if( get_hyella_development_mode() ){
												if( $e['count_endpoint'] ){
													echo "\n=================BODY====================\n";
													echo "\t";
													print_r(json_decode($e['body'], true));
													echo "\n=================BODY====================\n";
													echo "\n";
												}
											}

											$context  = stream_context_create( $opts );
											set_error_handler(function($errno, $errstr, $errfile, $errline){ throw new ErrorException($errstr, 0, $errno, $errfile, $errline); }, E_WARNING);
											$response = file_get_contents($e['url'], false, $context);

											restore_error_handler();
											if( !$response ){
												throw new Exception("<h4><b>Invalid Response</b></h4><p>Endpoint Call Returned Empty Response</p>", 1);
											}else{
												$response1 = json_decode($response, true);
												if( !$response1 ){
													$log['response'] = $response;
													throw new Exception("<h4><b>Invalid Response</b></h4><p>Response Received is not a valid json</p><p>" . json_last_error_msg() . "</p>", 1);
												}
												$response = $response1;
												if( get_hyella_development_mode() ){
													if( $e['count_endpoint'] ){
														echo "\n==================RESPONSE===========================\n";												
														echo "\t";
														print_r( json_encode( $response, JSON_PRETTY_PRINT ) );
														echo "\n==================RESPONSE===========================\n";
														echo "\n";
													}
												}
											}

										}catch(Throwable $_e){
											$error_msg = $_e->getMessage();
										}catch(Exception $_e){
											$error_msg = $_e->getMessage();
										}catch(ErrorException $_e){
											$error_msg = $_e->getMessage();
										}
									}	

									if( !$error_msg && $response ){

										$e_type = 'success';

										$error_msg = "<h4><b>". ( self::$isTesting ? "TEST: " : '') ."Endpoint Call Successful</b></h4>@@@";

										if( isset($_opt['transform_type']) && $_opt['transform_type'] ){
											$tb1 = "transform_template";
											$in[ $tb1 ] = $this->plugin_instance->load_class( array( 'class' => [ $tb1 ], 'initialize' => 1 ) )[ $tb1 ];
											switch ($_opt['transform_type']) {
												case 'csv_to_json':
													$response = $in[ $tb1 ]->_csv_to_json( $response, $_opt);
												break;
												default:
													$response = $in[ $tb1 ]->_json_to_json( $response, $_opt);
												break;
											}

											if( !$response ){
												$log['response'] = $response1;
												unset( $response1 );
												$error_msg = "<h4><b>Response Transformation Failed</b></h4><p>Failed to transform receive response as defined in config</p>";
												$e_type = 'error';
											}
										}

										if( $response ){
											if( $e['callback'] ){
												switch ( $e['callback'] ) {
													case 'load_data':
														if( $e['datatable'] ){

															if( isset($n['mismatching_page_size']) && $n['mismatching_page_size'] ){
																$l_id = isset($n['mismatching_page_size']['log_id']) && $n['mismatching_page_size']['log_id'] ? $n['mismatching_page_size']['log_id'] : '';
																$lps = isset($n['mismatching_page_size']['last_page_size']) && $n['mismatching_page_size']['last_page_size'] ? (int)$n['mismatching_page_size']['last_page_size'] : 0;
																
																if( $l_id && $lps ){
																	if( $lps == count($response) ){
																		$error_msg = "<h4><b>Response Data Unmodified</b></h4><p>Received Same Response as Last Endpoint Run</p>";
																		$e_type = 'error';
																	}else{
																		if (class_exists('cNwp_orm')) {
																			$cn = new cNwp_orm;
																			$cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
																			$client = cOrm_elasticsearch::$esClient;
																			if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
																				$error_msg = cOrm_elasticsearch::$esClientError;
																				$e_type = 'error';
																			}

																			if( $e_type != 'error' ){
																				$tbd = explode(":::", $e['datatable']);
																				$tba = array();
																				if( ! empty( $tbd ) ){
																					foreach( $tbd as $tbv ){
																						$tba2 = explode( '=', $tbv );
																						if( isset( $tba2[1] ) && $tba2[1] ){
																							$tba[ $tba2[0] ] = $tba2[1];
																						}
																					}

																					if( isset( $tba['value'] ) && $tba['value'] ){
																						$params = [
																						    'index' => $tba['value'],
																						    'body'  => [ 'query' => [ 'match' => [ 'created_source' => $l_id ] ] ]
																						];
																						$b = $client->deleteByQuery($params);
																						$status = $b->getStatusCode();
																						switch( $status ){
																							case 200:
																								if( isset( $b['errors'] ) && $b['errors'] ){
																									$error_msg = array_reduce($b['errors'], function($a, $b){
																										return $a . ($a ? "<br>" : '') . $b['type'] . ": " . $b['reason'];
																									});
																									$e_type = 'error';
																								}elseif(isset($b['deleted']) && $b['deleted']){
																									unset( $n['mismatching_page_size'] );
																								}else{
																									$error_msg = $error_msg = str_replace("@@@", '<p>Unable to Purge previous page data</p>', $error_msg);
																									$e_type = 'info';
																								}
																							break;
																							default:
																								$error_msg = "<h4>Error Sending Elastic Delete Request On Last Run Data Mismatch</h4>";
																								$e_type = 'error';
																							break;
																						}
																					}
																				}
																			}
																		}
																	}
																}
															}
															
															if( $e_type != 'error' ){

																$this->class_settings['action_to_perform'] = 'save_dataload';
																$this->class_settings['_creator_role'] = $log['parent'] ? $log['parent'] : $log['new_id'];
																$this->class_settings['_created_source'] = $log['new_id'];
																$this->class_settings['run_filter']['date_filter'] = array(
																	'call_start_time' => $call_start_time,
																	'call_end_time' => $call_end_time
																);
																$a = $this->_startDataUploadFg([
																	'dtable' => $e['datatable'], 
																	'data' => json_encode( $response )
																]);


																//Checks if theres an error parsing and saving response
																if( isset($a['data']['required']) && $a['data']['required'] ){
																	
																	$log['message'] = '<h3>Failed to save data</h3>' . ( isset($a['err']) && $a['err'] ? $a['err'] : '' ) . ( isset($a['msg']) && $a['msg'] ? $a['msg'] : '' );
																	
																	//Keeps previous request params for debugging purposes
																	if( $e['headers'] ){
																		$log['request_params']['headers'] = $e['headers'];
																	}
																
																	if( $e['body'] ){
																		$log['request_params']['body'] = $e['body'];
																	}
																
																	if( $_opt ){
																		$log['request_params']['options'] = $_opt;
																	}

																	$log['response'] = $response;

																	$log['data']['execution_time'] = (microtime(true) - $start_time);
																	$log['data']['memory_usage'] = ((memory_get_usage() - $mem_start) / 1024);

																	$log = fLog( $log );
																	$in[ $tb ]->class_settings['line_items'][] = $log;
																	$in[ $tb ]->_save_line_items();

																	if( self::$isTesting ){
																		$a['data']['log'] = $log['new_id'];
																	}

																	switch ($action_to_perform) {
																		case 'call_endpoint_bg':
																			//Saves error for background process cache
																			$sett2['cache_values']['notif'] = $a;
																			set_cache_for_special_values( $sett2 );
																		break;
																	}
																	$log = [];
																	$callback_error = $a;
																}else{
																	// @steve for other possible callback options
																	$error_msg = str_replace("@@@", "<p>Data Load Successful</p>", $error_msg);
																	$e_type = 'success';
																}
															}
														}
													break;
												}
											}

											if( !$callback_error ){

												if( isset($e['count_endpoint']) && $e['count_endpoint'] && !( isset($e['reference']) && $e['reference'] ) && $e_type == 'success' ){

													if( isset($count_type) && ( isset($count_value) || isset( $page_counter ) ) ){
														
														$wloop = true;
														$total_records_saved = 0;
														$current_page_no = isset($_opt['starting_page']) && $_opt['starting_page'] ? (int)$_opt['starting_page'] : 0;
														
														do{
															$page_size = count( $response );
															if( !$page_size ){
																$wloop = false;
															}

															$current_page_no = $page_counter;
															if( $wloop ){
																$total_records_saved += $page_size;
																if( isset($_opt['expected_page_size']) && $_opt['expected_page_size'] ){
																	if( (int)$_opt['expected_page_size'] != $page_size ){
																		$wloop = false;
																		// Commenting this because we are using a data replace mechanism instead.
																		/*$n[ 'mismatching_page_size' ] = array(
																			'last_page_size' => $page_size,
																			'page_counter' => $page_counter,
																			'total_records_saved' => $total_records_saved,
																			'log_id' => isset(self::$persistInfo['prev_page_log_id']) && self::$persistInfo['prev_page_log_id'] ? self::$persistInfo['prev_page_log_id'][ count( self::$persistInfo['prev_page_log_id'] ) - 1 ] : $log['new_id'],
																		);*/
																	}
																}

																if( $wloop ){
																	if( isset($_opt['max_number_records']) && $_opt['max_number_records'] ){
																		if( $total_records_saved >= (int)$_opt['max_number_records'] ){
																			$wloop = false;
																		}
																	}


																	switch ($count_type) {
																		case 'records':
																		case 'page_offset':
																		case 'pages':
																			if( $count_value ){
																				switch ($count_type) {
																					case 'records':
																						if( $total_records_saved >= $count_value ){
																							$wloop = false;
																						}
																					break;
																					case 'pages':
																						if( $page_counter >= $count_value ){
																							$wloop = false;
																						}
																					break;
																				}
																				if( $wloop ){
																					$page_counter++;
																				}
																			}
																		break;
																		case 'record_offset':
																			$page_counter += $page_size;
																		break;
																	}
																}														
															}
															
															// echo "============================================================\n";
															// print_r($wloop);
															// echo "\n";
															// print_r($page_size);
															// echo "\n";
															// print_r($page_counter);
															// echo "\n";
															// print_r($total_records_saved);
															// echo "\n";
															// echo "============================================================\n\n";

															if( $wloop ){
																$_POST['id'] = $e['id'];
																$this->class_settings['action_to_perform'] = 'call_endpoint';
																
																if( !(isset($e['obody']) && $e['obody'] ) ){
																	$e['obody'] = $e['body'];
																}
																if( !(isset($e['ourl']) && $e['ourl'] ) ){
																	$e['ourl'] = $e['url'];
																}
																
																$_a = $e;
																$_a['reference'] = $e['id'];
																$_a['parent'] = $log['new_id'];
																$_a['current_page_no'] = $current_page_no;
																$_a['type2'] = 'pagination_data_call';

																switch ( $increment_parameter['increment_locator'] ) {
																	case 'body':
																		$_a['body'] = str_replace( $increment_parameter['increment_string'], $page_counter, $e['obody']);
																	break;
																	case 'url':
																		$_a['url'] = str_replace( $increment_parameter['increment_string'], $page_counter, $e['ourl']);
																	break;
																}

																if( isset($_a['count_endpoint']) && $_a['count_endpoint'] ){
																	$_a['count_endpoint'] = '';
																}
																
																if( isset($_a['authentication']) && $_a['authentication'] ){
																	$_a['authentication'] = '';
																}

																if( $sleep_time ){
																	sleep( $sleep_time );
																	$_a['sleep_time'] = $sleep_time;
																}

																$response = $this->_call_endpoint([
																	'return_data' => 1, 
																	'_data' => $_a
																]);

																if( isset( $response['data']['required'] ) && $response['data']['required'] ){

																	$log['message'] = '<h3>Failed to call paginated endpoint</h3>'. ( isset($response['err']) && $response['err'] ? $response['err'] : '' ) . ( isset($response['msg']) && $response['msg'] ? $response['msg'] : '' );
																	if( $e['headers'] ){
																		$log['request_params']['headers'] = $e['headers'];
																	}

																	if( $e['body'] ){
																		$log['request_params']['body'] = $e['body'];
																	}

																	if( $_opt ){
																		$log['request_params']['options'] = $_opt;
																	}

																	$log[ 'request_params' ]['additional_params'] = array(
																		'page_size' => $page_size,
																		'counter' => $page_counter,
																		'current_page_no' => $current_page_no,
																		'total_records_saved' => $total_records_saved,
																	);

																	$log['data']['execution_time'] = (microtime(true) - $start_time);
																	$log['data']['memory_usage'] = ((memory_get_usage() - $mem_start) / 1024);

																	$log = fLog( $log );
																	$in[ $tb ]->class_settings['line_items'][] = $log;
																	$in[ $tb ]->_save_line_items();
																	if( self::$isTesting ){
																		$response['data']['log'] = $log['new_id'];
																	}
																	
																	switch ($action_to_perform) {
																		case 'call_endpoint_bg':
																			$sett2['cache_values']['notif'] = $response;
																			$lks = ($n ? $n : $x);
																			$_n = updateNestedArrayValue( $lks, $count_key, $current_page_no );
																			$this->class_settings['update_fields']['last_run'] = json_encode( $_n );
																			set_cache_for_special_values( $sett2 );
																		break;
																	}

																	$log = [];
																	$callback_error = $response;
																}
															}else{
																if( $count_key ){
																	$lks = ($n ? $n : $x);
																	$_n = updateNestedArrayValue($lks, $count_key, $current_page_no	);
																	$this->class_settings['update_fields']['last_run'] = json_encode( $_n );
																}
															}

														}while($wloop);

														$lastRunArray = isset($this->class_settings['update_fields']['last_run']) && $this->class_settings['update_fields']['last_run'] ? json_decode($this->class_settings['update_fields']['last_run'], true) : [];

														$lastRunArray['date_filter']['call_start_time'] = $call_start_time;
														$lastRunArray['date_filter']['call_end_time'] = $call_end_time;

														$this->class_settings['update_fields']['last_run'] = json_encode( $lastRunArray );
														
														$log['data']['total_records_saved'] = $total_records_saved;
													}
												}

												if( $e_type == 'success' ){

													if( isset($o['return_data']) && $o['return_data'] ){
														$log['status'] = 'success';
														$msg = 'Unknown Callback Call Return';
														if( isset($e['type2']) && $e['type2'] ){
															switch( $e['type2'] ){
																case 'authentication_call':
																	$msg = "AUTHENTICATION CALLBACK";
																break;
																case 'pagination_data_call':
																	$msg = 'PAGINATION DATA FETCH CALLBACK';
																break;
																case 'pagination_count_call':
																	$msg = 'PAGINATION COUNT FETCH CALLBACK';
																break;
															}
														}

														self::$persistInfo['prev_page_log_id'][] = $log['new_id'];
														
														$log['message'] = $msg;
														$log['data']['page_size'] = count( $response );
														if( isset($e['current_page_no']) && $e['current_page_no'] ){
															$log['message'] .= ': PAGE '.intval($e['current_page_no']);
															$log['data']['page_no'] = $e['current_page_no'];
														}

														if( isset($e['sleep_time']) && $e['sleep_time'] ){
															$log['data']['sleep_time'] = $e['sleep_time'];
														}

														$log['data']['execution_time'] = (microtime(true) - $start_time);
														$log['data']['memory_usage'] = ((memory_get_usage() - $mem_start) / 1024);

														$log = fLog( $log );
														$in[ $tb ]->class_settings['line_items'][] = $log;
														$in[ $tb ]->_save_line_items();
														return $response;
													}

													$error_msg = str_replace("@@@", '<style>.scrollable-pre{overflow:auto;max-height:300px;white-space:pre-wrap;border:1px solid #ccc;padding:10px;font-family:monospace;}</style><pre class="scrollable-pre">'.json_encode( $response, JSON_PRETTY_PRINT ).'</pre>', $error_msg);

													switch ($action_to_perform) {
														case 'call_endpoint':
															$n_params['manual_close'] = 1;
														break;
													}
												}
											}
										}
									}
								}

							}else $error_msg = "<h4><b>Invalid Reference</b></h4><p>Record does not exist</p>";
						break;
					}
				}
			}

			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			
			$n_params['message'] = $error_msg;

			if( $log ){
				$log['message'] = $error_msg;
			}

			
			switch ( $e_type ) { 
				case 'error':
					$n_params['data']['required'] = 1; 
					
					if( $log ){
						if( isset($e['headers']) && $e['headers'] ){
							$log['request_params']['headers'] = $e['headers'];
						}
						
						if( isset($e['body']) && $e['body'] ){
							$log['request_params']['body'] = $e['body'];
						}						
					}

				break;
				case 'success':
					if( $log ){
						$log['status'] = $e_type;
					 	$log['response'] = '';						
					}
				break;
				case 'info':
					$log = [];
				break;
			}

			$n_params['callback'][] = '$nwProcessor.reload_datatable';

			if( $log ){

				$log['data']['execution_time'] = (microtime(true) - $start_time);
				$log['data']['memory_usage'] = ((memory_get_usage() - $mem_start) / 1024);

				$log = fLog( $log );
				$in[ $tb ]->class_settings['line_items'][] = $log;
				$in[ $tb ]->_save_line_items();
			}

			if( $callback_error ){
				$return = $callback_error;
				// $set_state = 1;
			}else{
				$return = $this->_display_notification( $n_params );
			}

			switch ($action_to_perform) {
				case 'call_endpoint_bg':
					if( isset($sett2) && $sett2 ){
						$sett2[ 'cache_values' ][ 'error' ] = $n_params;
						$sett2[ 'cache_values' ][ 'status' ] = $e_type;
						$sett2[ 'cache_values' ][ 'notif' ] = $return;
						set_cache_for_special_values( $sett2 );
						// $set_state = 1;
					}
				break;
			}
			
			if( $set_state && isset($e['id']) ){
				$_POST['id'] = $e['id'];
				$this->class_settings['update_fields']['status'] = 'active';
				$this->_update_table_field();				
			}

			return $return;
		}

		protected function _format_csv( $csv, $file=false ){
			$a = [ 'error_msg' => 'Unknown CSV Formatting Error', 'data' => [] ];
			if( $csv ){
				try{
					if( $file ){
						$csv = array_map('str_getcsv', file( $csv ) );
						//print_r($csv); exit;

						/*$fileObj = new SplFileObject($csv);

						$fileObj->setFlags(SplFileObject::READ_CSV 
						| SplFileObject::SKIP_EMPTY 
						| SplFileObject::READ_AHEAD 
						| SplFileObject::DROP_NEW_LINE
						);
						$fileObj->setCsvControl(';');

						$i = 0;
						$ar = [];
						foreach($fileObj as $row){
							//do something 
							$ar[] = explode( ",", $row[0] );
							++$i;
							if( $i > 20000 ){
								break;
							}
						}
						print_r( count( $ar ) ); exit;
						print_r( $ar ); exit;
						return $ar;*/
					}else{
						if( ! is_array( $csv ) ){
							$csv = explode(PHP_EOL, $csv);
							$csv = array_map('str_getcsv', $csv );
						}
					}
					array_walk_recursive( $csv, '_clean_array_values_of_non_utf8' );
					array_walk($csv, function(&$a) use ($csv) {
						if( count($csv[0]) == count($a) ){
					    	$a = array_combine($csv[0], $a);
						}else{
							throw new Exception("Mismatching Header and Data", 1);
						}
					});
					array_shift($csv);
					$a['data'] = $csv;
					unset($a['error_msg']);
				}catch(Exception $e){
					$a['error_msg'] = $e->getMessage();
					// return $error_msg;
				}catch(Throwable $e){
					$a['error_msg'] = $e->getMessage();
					// return $error_msg;
				}
			}
			return $a;
		}

		protected function _load_csv_file( $o = array() ){
			$error_msg = '';
			extract($o);
			if( isset($file) && $file ){
				if ( file_exists( $file ) ){
					$source_path = realpath( $file );
					$finfo = pathinfo( $source_path );
					switch ( $finfo['extension'] ) {
					case 'csv':
					break;
					case 'xls':
					case 'xlsx':
						if( isset($convert) && $convert ){
							$source = $finfo["dirname"] . '\\'. $finfo["filename"] . '.' . $finfo["extension"];
							$converted = $finfo["dirname"] . '\\'. $finfo["filename"] . '.csv';
							$preconverted = str_replace( '.' . $file_information["extension"], '.csv', $file );
							if( file_exists( $preconverted ) ){
								unlink( $preconverted );
							}
							run_in_background( "convert", 0, 
								array( 
									"action" => 'convert', 
									"todo" => "convert", 
									"user_id" => 'convert',
									"reference" => 'convert',
									"wait_for_execution" => 1,
									"argument3" => $source,
									"argument4" => $converted,
								) 
							);

							if( $converted && file_exists( $converted ) ){
								$file = $preconverted;
							}

						}else $error_msg = "<h4><b>Invalid File Extension</b></h4>";
					break;
					default:
						$error_msg = "<h4><b>Invalid File Extension</b></h4>";
					break;
					}

					if( !$error_msg ){
						if( get_hyella_development_mode() ){
							error_reporting (E_ALL);
						}
						$formattedData = $this->_format_csv( $file, true );
						if( isset($formattedData['data']) && $formattedData['data'] ){
							return $formattedData['data'];
						}else{
							$error_msg = isset( $formattedData['error_msg'] ) && $formattedData['error_msg'] ? $formattedData['error_msg'] : 'Unknown Formatting Error';
						}
					}
				}
			}

			return $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';		
		}

		protected function _dump_elastic_data( array $data, string $table, $opt = [] ){
			// print_r( $data );exit;
			$error_msg = "";
			$_v = null;

			try {
				
				if( class_exists('cNwp_logging') ){
					$nwp = new cNwp_logging();
					$nwp->class_settings = $this->class_settings;
					$tb = "log_main_logger";
					$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
					$logger = $in[ $tb ];
				}

				if( class_exists( 'cNwp_orm' ) ){

					$cn = new cNwp_orm;
					$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
					$client = cOrm_elasticsearch::$esClient;
					if( cOrm_elasticsearch::$esClientError ){
						$error_msg = cOrm_elasticsearch::$esClientError;
					}
				}else{
					$error_msg = '<h4><strong>Missing Plugin</strong></h4>cNwp_orm';
				}

				if( ! $error_msg ){
					if( $data ){

						if( isset($opt['has_created_source']) && $opt['has_created_source'] && isset($opt['created_source']) && $opt['created_source'] ){
							foreach ($data as &$sval) {
								$sval['created_source'] = $opt['created_source'];
							}
						}

						if( $table ){
							if( isset( $client ) ){
								
								$params = $this->_format_elastic_data($data, $table, array( 'use_replace' => 1 ) );
								$time = microtime(true);
								$response = $client->bulk( $params );
								if ( isset($response['errors']) && $response['errors'] ) {
									$error_msg .= "Some documents failed to be inserted:\n";
									foreach ($response['items'] as $item) {
										if ( isset($item['index']['error']) ) {
											$error_msg .= "Error for document with ID {$item['index']['_id']}: {$item['index']['error']['reason']}\n";
										}
									}
								} else {
									$error_msg .= "Bulk insert completed successfully!";
									$etime = microtime(true);
									$_v = $etime - $time;
								}

							}else{
								$error_msg = "<h4><b>Elastic Connection Error</b></h4><p>Failed Connecting to Elastic</p>";
							}
						}else{
							$error_msg = "<h4><b>Empty Data Source</b></h4><p>Data source is not specified</p>";
						}
					}else{
						$error_msg = "<h4><b>Empty Dataset</b></h4><p>Dataset is not specified</p>";
					}
				}

				if( isset( $logger ) && isset($opt['created_source']) ){
					$logger->_logger( __FUNCTION__, get_class( $this ), $this->plugin, [ 'reference' => $opt['created_source'], 'table' => ( isset($table) && $table ? $table : '' ) ], '', $error_msg, [] );
				}
					
			} catch (Exception $e) {
				$error_msg = $e->getMessage();
			}

			return array(
				'error' => $error_msg,
				'_v' => $_v
			);
		}

		public function dump_elastic_data( array $data, string $table, $opt = [] ){
			return $this->_dump_elastic_data( $data, $table, $opt );
		}

		public function isLoadingFile(){
			$ckey = get_cache_for_special_values( array(
				'cache_key' => $this->class_settings["user_id"] . '-user-upload',
				'directory_name' => $this->table_name,
			) );

			if( $ckey ){
				$loading = array(
					'cache_key' => $ckey . "-progress",
					'directory_name' => $this->table_name,
				);
				$loading = get_cache_for_special_values( $loading );

				if( isset( $loading["status"] ) && $loading["status"] == 'ongoing' ){
					
					if( isset( $loading["now"] ) && date("U") > $loading["now"] + ( 60 * 3 ) ){
						//five minutes has passed, with no activity
					}else{
						//active loading
						$_POST["id"] = $ckey;
						$this->class_settings["action_to_perform"] = 'check_bg_load_progress';
						return $this->endpoint();
					}
				}
	
			}
		}

		protected function _save_dataload( $o = array() ){
			if( self::$skipBULK_IMPORT_MEMORY_LIMIT ){
				ini_set('memory_limit', '50M');
			}else{
				if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
					ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
				}else{
					ini_set('memory_limit', '4G' );
				}
			}
			
			//ini_set('memory_limit', '50M' );
			//echo ini_get('memory_limit'); exit;

			if( class_exists('cNwp_logging') ){
				$nwp = new cNwp_logging();
				$nwp->class_settings = $this->class_settings;
				$tb = "log_main_logger";
				$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
				$logger = $in[ $tb ];
				$log_id = isset($this->class_settings['_created_source']) && $this->class_settings['_created_source'] ? $this->class_settings['_created_source'] : 'lr'.get_new_id();
			}


			$info_msg = '';
			$error_msg = '';
			
			$e_type = 'error';
			
			$n_params = array();
			
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$replaceContainer = false;

			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$_data = [];

			switch ( $action_to_perform ) {
				case 'check_bg_load_progress':
					//stop session locking
					session_write_close();
				case 'save_data_to_table_bg':
				case 'stop_bg_load':
				break;
				default:
					if( !empty($o) && !null_array( $o ) ){
						$_POST = $o;
					}

					$dtable = isset($_POST['dtable']) && $_POST['dtable'] ? $_POST['dtable'] : '';
				
					$type = isset($_POST['type']) && $_POST['type'] ? $_POST['type'] : '';

					$tdata = isset($_POST['dataupload']) && $_POST['dataupload'] ? $_POST['dataupload'] : '';

					$fdata = isset($_POST['_file']) && $_POST['_file'] ? $_POST['_file'] : '';

					$bg = isset($_POST['bg']) ? $_POST['bg'] : true;

					$aData = isset($_POST['aData']) && $_POST['aData'] ? $_POST['aData'] : [];

					if( !( $fdata || $tdata ) ){
						$error_msg = "<h4><b>Invalid Submission</b></h4><p>Kindly upload a file or fill in data in the provided textarea</p>";
					}

					if( $fdata && $tdata ){
						$error_msg = "<h4><b>Invalid Submission</b></h4><p>Kindly upload a file <h5><b>OR</b></h5> fill in data in the provided textarea <br>Not both</p>";
					}

					if( ! $error_msg ){
						if( $fdata && ! $tdata ){
							if( ! file_exists( $this->class_settings["calling_page"] . $fdata ) ){
								$error_msg = "<h4><b>Invalid Submission</b></h4><p>Kindly upload a file</p>";
							}
						}
					}
				break;
			}
			$logError = true;


			switch ( $action_to_perform ) {
				case 'save_dataload':
					$logError = false;
					if( !$dtable  ){
						$error_msg = "<h4><b>Invalid Submission</b></h4><p>Kindly select a table to save data to</p>";
					}else{
						$tbd = explode(":::", $dtable);
						$tba = array();
						if( ! empty( $tbd ) ){
							foreach( $tbd as $tbv ){
								$tba2 = explode( '=', $tbv );
								if( isset( $tba2[1] ) && $tba2[1] ){
									$tba[ $tba2[0] ] = $tba2[1];
								}
							}
						}


						$tb_type = isset( $tba["type"] )?$tba["type"]:'';
						$table2 = isset( $tba["value"] )?$tba["value"]:'';
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
											$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.</p>";
										}
									}else{
										$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
									}
								}else{
									$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
								}
						
							break;
							default:
								$cls = 'c' . ucwords( $dtable );
								if( class_exists( $cls ) ){
									$cl = new $cls();
								}else{
									$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
								}
							break;
							}
						}

				break;
				case 'stop_bg_load':
				case 'check_bg_load_progress':
					$logError = false;
				case 'save_data_to_table_bg':
					$ckey = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
					if( $ckey ){
						switch ( $action_to_perform ) {
						case 'stop_bg_load':
						case 'check_bg_load_progress':
							$replaceContainer = true;
							switch ( $action_to_perform ) {
								case 'check_bg_load_progress':
									$log_id = isset( $_GET['log_id'] ) && $_GET['log_id'] ? $_GET['log_id'] : $log_id;
									$dtable = isset( $_GET['dtable'] ) && $_GET['dtable'] ? $_GET['dtable'] : '';
								break;
							}
						break;
						default:
							$isLoaded = self::_loadDataContract([
								'id' => $ckey
							]);

							if( isset( $isLoaded["success"] ) && $isLoaded["success"] ){
								$e_type = 'success';
								$error_msg = ( isset( $isLoaded["error"] ) && $isLoaded["error"] )?strip_tags( $isLoaded["error"], 'p,br,b' ):'Successful Load Operation';
							}else{
								$error_msg = ( isset( $isLoaded["error"] ) && $isLoaded["error"] )?strip_tags( $isLoaded["error"], 'p,br,b' ):'Unable to load data contract... System config error';
								$dtable = isset($isLoaded['dtable']) && $isLoaded['dtable'] ? $isLoaded['dtable'] : '';
							}
							
						break;
						}
					}else{
						$error_msg = "<b>Invalid Cache Reference</b><p>Invalid Cache Key</p>";
					}

					if( !$error_msg ){
					
						$sett = array(
							'cache_key' => $ckey . "-progress",
							'directory_name' => $this->table_name,
							'permanent' => 1
						);

						switch ( $action_to_perform ) {
						case 'save_data_to_table_bg':
						break;
						case 'stop_bg_load':
							$setStopped = array(
								'cache_key' => $ckey . "-stopped",
								'directory_name' => $this->table_name,
								'permanent' => 1
							);
							$setStopped["cache_values"]["cancelled"] = date("U");
							$setStopped["cache_values"]["cancelled_by"] = $this->class_settings["user_id"];
							set_cache_for_special_values( $setStopped );
							
							$e_type = 'success';
							$error_msg = "<h4><b>Data Loading Stopped</b></h4><p>Data loading operation successfully stopped</p>";
							$sett = array(
								'cache_key' => $ckey . "-progress",
								'cache_values' => array( 'status' => 'complete', 'error' => $error_msg ),
								'directory_name' => $this->table_name,
								'permanent' => 1
							);
							set_cache_for_special_values( $sett );
						break;
						default:
							sleep(5);
							$n = get_cache_for_special_values( $sett );
							$info_msg = isset($n['info']) && $n['info'] ? $n['info'] : "";
							$setStopped = array(
								'cache_key' => $ckey . "-stopped",
								'directory_name' => $this->table_name,
								'permanent' => 1
							);


							if( isset( $n['status'] ) && $n['status'] ){
								switch ($n['status']) {
									case 'ongoing':
										$setStoppedCache = get_cache_for_special_values( $setStopped );
										if( $setStoppedCache ){
											$e_type = 'success';
											$error_msg = "<h4><b>Data Loading Stopped</b></h4><p>Data loading operation successfully stopped</p>";
											clear_cache_for_special_values( $setStopped );
										}else{
											$e_type = 'info';
											$error_msg = "<h4><b>Data Loading</b></h4><p>Processing Data since ". ( ( isset( $n["start"] ) && $n["start"] )?date("d-M-Y H:i", $n["start"] ):'' ) ."...</p>";
											$error_msg .= isset($n['error']) && $n['error'] ? $n['error'] : "";

											$this->class_settings['callback'][ $this->table_name ][ $this->class_settings['action_to_perform'] ] = array(
												'id' => $ckey,
												're_process_delay' => 4000,
												'action' => "?action=". $this->plugin . "&todo=execute&nwp_action=". $this->table_name . "&nwp_todo=check_bg_load_progress&log_id=".$log_id . '&dtable='. $dtable . "&html_replacement_selector=" . $handle
											);

											$cancelBtn = ' <a href="javascript:;" class="btn btn-sm btn-danger custom-single-selected-record-button" override-selected-record="'. $ckey .'" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=stop_bg_load&html_replacement_selector='. $handle .'"  title="Stop Currrent Operation" confirm-prompt="Stop Data Loading Operation">Stop</a> ';

											$manualBtn = '<br><a href="javascript:;" class="btn btn-sm dark custom-single-selected-record-button" override-selected-record="'. $ckey .'" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=check_bg_load_progress&html_replacement_selector='. $handle .'" id="manual-progress-check" title="Manual Progress Check... wait 5 seconds before clicking">Manual Progress Check</a>'. $cancelBtn .'<br>';

											$error_msg .= $manualBtn;
										}
										// if( $info_msg ){
										// 	$info_msg .= '<br>' . $manualBtn;
										// }
									break;
									case 'complete':
									case 'error':
										$error_msg = isset($n['error']) && $n['error'] ? $n['error'] : "<h4><b>Data Load Halt</b></h4><p>Unable to define error</p>";
										$e_type = $n['status'] == 'complete' ? 'success' : $e_type;
										$error_msg .= ( (isset($n['status']) && $n['status'] == "complete" && isset($n["time"]) && $n["time"] ) ? "Time Taken: ".$n["time"] : "" );
									break;
								}

							}else $error_msg = "<h4><b>Invalid Data Loading</b></h4><p>Failed to verify state</p>";
						break;
						}
					}
				break;			
			}

			switch( $action_to_perform ){
				case 'save_dataload':
					if( !$error_msg ){
						if( $type ){
							$file = $fdata && file_exists( $this->class_settings['calling_page'] . $fdata ) ? $this->class_settings['calling_page'] . $fdata : "";
							$lbs = $cl->table_name;
							$lbs = function_exists( $lbs ) && is_callable( $lbs ) ? $lbs() : [];

							$isValid = self::_validateDataforUpload( [
								'file' => $file,
								'type' => $type,
								'isFile' => $fdata,
								'table' => $dtable,
								'fields' => $cl->table_fields,
								'labels' => $lbs
							] );

							if( isset( $isValid["success"] ) && $isValid["success"] ){

								$ckey = self::_startDataUploadBg([
									'headers' => ( isset( $isValid["headers"] ) && $isValid["headers"] )?$isValid["headers"]:[],
									'file' => $file,
									'type' => $type,
									'data' => $tdata,
									'isFile' => $fdata,
									'table' => $dtable,
									'fields' => $cl->table_fields,
									'labels' => $lbs,
									'added_data' => $aData
								]);

								if( isset( $ckey['success']) && $ckey['success'] ){
									$error_msg = "<h4><b>Data Loading</b></h4><p>Data Loading Processing....</p>";
									$e_type = 'info';
									$replaceContainer = true;

									$this->class_settings['callback'][ $this->table_name ][ $this->class_settings['action_to_perform'] ] = array(
										'id' => $ckey['success'],
										'action' => "?action=". $this->plugin . "&todo=execute&nwp_action=". $this->table_name . "&nwp_todo=check_bg_load_progress&log_id=&html_replacement_selector=" . $handle
									);
								}else{
									if( isset($ckey['status']) && $ckey['status']  ){
										$e_type = $ckey['status'];
										$error_msg = isset($ckey['msg']) && $ckey['msg'] ? $ckey['msg'] : "<h4><b>Unknown Data Load Failure</b></h4><p>No response from save function</p>";
									}else{
										$error_msg = "Unable to successfully launch... _startDataUploadBg";
									}
								}

							}else{
								$error_msg = ( isset( $isValid["error"] ) && $isValid["error"] )?$isValid["error"]:'Unable to commence data upload... System config error';
							}
							

						}else $error_msg = "<h4><b>Invalid Type</b></h4><p>Data Load type must be specified</p>";
					}
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
			$n_params['message'] = $error_msg . ( $info_msg?('<br>' . $info_msg):'' );
			switch ( $e_type ) {
				case 'error':
					$n_params['data']['required'] = 1;
				break;
				case 'success':
					$logError = false;
				break;
			}

			if( $replaceContainer ){
				$n_params['do_not_display'] = 1;
				$n_params['html_replacement_selector'] = "#".$handle;
			}

			if( $logError && isset( $logger ) ){
				// $logger->_logger( __FUNCTION__, get_class( $this ), $this->plugin, $_data, '', $error_msg, [] ); //steve $_data might be very large so as not to fill up table
				$logger->_logger( __FUNCTION__, get_class( $this ), $this->plugin, [ 'reference' => $log_id, 'table' => isset( $dtable ) && $dtable ? $dtable : '' ], '', $error_msg, [] );
			}
			
			return $this->_display_notification( $n_params );
		}

		protected function _format_elastic_data(array $data, $table, $opt = array()){
			$return = array( 'body' => [] );
			$use_replace = isset($opt['use_replace']) && $opt['use_replace'] ? $opt['use_replace'] : 0;
			foreach ($data as $sval) {
				$index = [
					'_index' => $table
				];
				if( $use_replace ){
					if( isset( $sval["id"] ) ){
						$index['_id'] = $sval["id"];
					}
				}
			    $return['body'][] = [
			        'index' => $index
			    ];
			    $return['body'][] = $sval;
			}
			return $return;
		}

		protected function _format_data_fields($data, array $fields, array $labels, $added_data=[]){
			$date = date("U");
			$ip_address = get_ip_address();
			if( $data && $fields ){
				$_id = get_new_id() . 'R' . rand();
				$sn = 0;
				try{
					foreach ($data as $_key => &$sval) {
						$sn++;
						$sval2 = [];
						foreach ( $fields as $_k => $_v) {
							if( isset( $sval[ $_v ] ) && $sval[ $_v ] ){
								$sval2[ $_v ] = $sval[ $_v ];
							}

							if( !isset( $sval2[ $_v ] ) ){
								$def_val = '';
								if( isset( $labels[ $_v ]['form_field'] ) &&  $labels[ $_v ]['form_field'] ){
									switch ( $labels[ $_v ]['form_field'] ) {
										case 'date-5time':
										case 'date-5':
										case 'date':
										case 'datetime':
										case 'number':
										case 'decimal':
										case 'decimal_long':
										case 'currency':
											$def_val = 0;
										break;
									}
								}
								
								if( isset( $labels[ $_v ]['default_value'] ) && $labels[ $_v ]['default_value'] && isset( $labels[ $_v ]['default_value']['type'] ) && $labels[ $_v ]['default_value']['type'] ){
									switch ( $labels[ $_v ]['default_value']['type'] ) {
										case 'value':
											$def_val = isset( $labels[ $_v ]['default_value'][ $labels[ $_v ]['default_value']['type'] ] ) && $labels[ $_v ]['default_value'][ $labels[ $_v ]['default_value']['type'] ] ? $labels[ $_v ]['default_value'][ $labels[ $_v ]['default_value']['type'] ] : $def_val;
										break;
										case 'function':
											$fn = isset( $labels[ $_v ]['default_value'][ $labels[ $_v ]['default_value']['type'] ] ) && $labels[ $_v ]['default_value'][ $labels[ $_v ]['default_value']['type'] ] ? $labels[ $_v ]['default_value'][ $labels[ $_v ]['default_value']['type'] ] : '';
											if( $fn && is_callable( $fn ) ){
												$def_val2 = $fn( $sval );
												$def_val = !is_array($def_val2) && $def_val2 ? $def_val2 : $def_val;
											}
										break;
									}
								}

								$sval2[ $_v ] = isset( $sval[ $_k ]) && $sval[ $_k ] ? $sval[ $_k ] : $def_val;
								if( !$sval2[ $_v ] && isset( $added_data[ $_k ]) && $added_data[ $_k ] ){
									$sval2[ $_v ] = $added_data[ $_k ];
								}
							}

							if( $sval2[ $_v ] ){
								$sval2[ $_v ] = trim( $sval2[ $_v ] );
								if( isset( $labels[ $_v ]['form_field'] ) &&  $labels[ $_v ]['form_field'] ){
									switch ( $labels[ $_v ]['form_field'] ) {
										case 'number':
										case 'decimal_long':
										case 'decimal':
										case 'currency':
											$sval2[ $_v ] = doubleval( $sval2[ $_v ] );
										break;
										case 'date-5time':
										case 'date-5':
										case 'date':
										case 'datetime':
											$match = [];
											$format = 'YYYY-MM-DD';
											$gn = 'Date';
											$pattern = '/\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/';
											switch ( $labels[ $_v ]['form_field'] ) {
												case 'datetime':
												case 'date-5time':
													$gn = 'Time';
													$format = 'YYYY-MM-DD HH:MM:SS';
													$pattern = '/\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])/';
												break;
											}
											preg_match($pattern, $sval2[ $_v ], $match);
											if( $match ){
												$sval2[ $_v ] = strtotime( $sval2[ $_v ] );
											}else{
												throw new Exception("Invalid ". $gn ." Format for field <b>". $_k ."</b> on Data Item number " . $sn. ". ". $gn ." must match format <i>". $format ."</i>");
											}
										break;
									}
								}
							}					
						}

						if( null_array( $sval2 ) ){
							unset( $data[ $_key ] );
							continue;
						}

						$sval2['id'] = isset($sval[ 'id' ]) && $sval[ 'id' ] ? $sval['id'] : $_id . $sn;
						$sval2['creator_role'] = isset( $sval[ 'creator_role' ] ) && $sval[ 'creator_role' ] ? $sval[ 'creator_role' ] : ( isset($this->class_settings['_creator_role']) && $this->class_settings['_creator_role'] ? $this->class_settings['_creator_role'] : $this->class_settings['priv_id'] ) ;
						$sval2['created_source'] = isset($sval['created_source']) && $sval['created_source'] ? $sval['created_source'] : ( isset($this->class_settings['_created_source']) && $this->class_settings['_created_source'] ? $this->class_settings['_created_source'] : $this->class_settings['priv_id'] );
						$sval2['created_by'] = isset( $sval[ 'created_by' ] ) && $sval[ 'created_by' ] ? $sval[ 'created_by' ] :  $this->class_settings[ 'user_id' ];
						$sval2['creation_date'] = isset( $sval[ 'creation_date' ] ) && $sval[ 'creation_date' ] ? format_time_value( $sval[ 'creation_date' ] ) :  $date;
						$sval2['modified_by'] = isset( $sval[ 'modified_by' ] ) && $sval[ 'modified_by' ] ? $sval[ 'modified_by' ] :  $this->class_settings[ 'user_id' ];
						$sval2['modification_date'] = isset( $sval[ 'modification_date' ] ) && $sval[ 'modification_date' ] ? format_time_value( $sval[ 'modification_date' ] ) :  $date;
						$sval2['ip_address'] = isset( $sval[ 'ip_address' ] ) && $sval[ 'ip_address' ] ? $sval[ 'ip_address' ] :  $ip_address;
						$sval2['serial_num'] = isset( $sval['serial_num'] ) ? $sval['serial_num'] : '';
						$sval2['record_status'] = isset( $sval[ 'record_status' ] ) ? $sval[ 'record_status' ] : 1;
						if( self::$isTesting ){
							$sval2['test_case_data'] = 1;
						}
						$sval = $sval2;
					}

					if( !$data ){
						throw new Exception("Mismatching Fields", 1);
					}

					return $data;

				}catch( Exception $e ){
					return "<h4><b>Failed to format data into fields</b></h4><p>". $e->getMessage() ."</p>" ;
				}catch( Throwable $e ){
					return "<h4><b>Failed to format data into fields</b></h4><p>". $e->getMessage() ."</p>" ;
				}
			}
		}

		protected function _format_data_to_queries( $data, $opt = [] ){
			if( $data && $opt ){
				extract($opt);
				$load_type = 1;
				switch ($DB_MODE) {
					case 'mssql':
					break;
					default:
						if( $load_type ){
							switch ($load_type) {
								case 'insert':
									$q = [];
									$query = isset( $type ) && $type ? strtoupper($type) : "INSERT";
									$query .= " INTO `".$table ."` VALUES ";
									foreach ($data as $sval) {
										$fields = array_keys($sval);
										$values = array_values($sval);
										$q[] = $query . "('". implode("', '", $fields) ."') VALUES ('". implode("', '", $values) ."')";
									}
								break;
							}
						}
					break;
				}
			}
		}

		public function mysqlBulk(&$data, $table, $method = 'transaction', $options = array()) {
				
			  // Default options
			  if (!isset($options['query_handler'])) {
				  $options['query_handler'] = 'mysqli_query';
			  }
			  if (!isset($options['trigger_errors'])) {
				  $options['trigger_errors'] = true;
			  }
			  if (!isset($options['trigger_notices'])) {
				  $options['trigger_notices'] = true;
			  }
			  if (!isset($options['eat_away'])) {
				  $options['eat_away'] = false;
			  }
			  if (!isset($options['database'])) {
				  $options['database'] = false;
				  trigger_error('Database name is not specified', E_USER_ERROR);
				return false;
			  }
			  
			  if (!isset($options['in_file'])) {
				  $options['in_file'] = $_SERVER['DOCUMENT_ROOT'] . MYSQL_BULK_LOAD_FILE_PATH;
			  }
			  $options['in_file'] = _wp_normalize_path( $options['in_file'] );
			  
			  if (!isset($options['link_identifier'])) {
				  $options['link_identifier'] = null;
			  }
			  extract($options);

			  $table_name = '';
			  if ( !is_array($data) ) {
				  if ( $trigger_errors ) {
					  trigger_error('First argument "queries" must be an array', E_USER_ERROR);
				  }
				  return false;
			  }
			  if (empty($table)) {
				  if ($trigger_errors) {
					  trigger_error('No insert table specified', E_USER_ERROR);
				  }
				  return false;
			  }else{
				$table_name = $table;
				$table = '`'.$options['database'].'`.`'.$table.'`';
			  }
			  if (count($data) > 10000) {
				  if ($trigger_notices) {
					  trigger_error('It\'s recommended to use <= 10000 queries/bulk',
						  E_USER_NOTICE);
				  }
			  }
			  if (empty($data)) {
				  return 0;
			  }

			if( $table_name && class_exists("cNwp_full_text_search") ){
				$GLOBALS["nwp_full_text_search"][ $table_name ] = 1;
			}
			
			  if (!function_exists('__exe')) {
				  function __exe ($sql, $query_handler, $trigger_errors, $link_identifier = null) {
					
					mysqli_options($link_identifier,MYSQLI_OPT_LOCAL_INFILE, true );
					$query_settings = array(
						'database' => '',
						'connect' => $link_identifier,
						'query' => $sql,
						'query_type' => 'EXECUTE',
						'set_memcache' => 0,
						'tables' => array(),
					);
					execute_sql_query( $query_settings );
					mysqli_options($link_identifier,MYSQLI_OPT_LOCAL_INFILE, false );
					return true;
				  }
			  }

			  if (!function_exists('__sql2array')) {
				  function __sql2array($sql, $trigger_errors) {
					  if (substr(strtoupper(trim($sql)), 0, 6) !== 'INSERT') {
						  if ($trigger_errors) {
							  trigger_error('Magic sql2array conversion '.
								  'only works for inserts',
								  E_USER_ERROR);
						  }
						  return false;
					  }

					  $parts   = preg_split("/[,\(\)] ?(?=([^'|^\\\']*['|\\\']" .
											"[^'|^\\\']*['|\\\'])*[^'|^\\\']" .
											"*[^'|^\\\']$)/", $sql);
					  $process = 'keys';
					  $dat     = array();

					  foreach ($parts as $k=>$part) {
						  $tpart = strtoupper(trim($part));
						  if (substr($tpart, 0, 6) === 'INSERT') {
							  continue;
						  } else if (substr($tpart, 0, 6) === 'VALUES') {
							  $process = 'values';
							  continue;
						  } else if (substr($tpart, 0, 1) === ';') {
							  continue;
						  }

						  if (!isset($data[$process])) $data[$process] = array();
						  $data[$process][] = $part;
					  }

					  return array_combine($data['keys'], $data['values']);
				  }
			  }

			  $start = microtime(true);
			  $count = count($data);

				switch ($method) {
				case 'loaddata':
				case 'loaddata_unsafe':
				case 'loadsql_unsafe':
					$buf    = '';
					foreach($data as $i=>$row) {
						if ($method === 'loadsql_unsafe') {
						  $row = __sql2array($row, $trigger_errors);
						}
						$buf .= implode('::::,', $row)."^^^^";
					}

					if( isset( $options[ 'field_keys' ] ) && $options[ 'field_keys' ] ){
						$fields = implode( ', ', $options[ 'field_keys' ] );
					}else{
						$fields = implode(', ', array_keys($row));
					}

					if (!@file_put_contents($in_file, $buf)) {
						if( $trigger_errors ){
							trigger_error('Cant write to buffer file: "'.$in_file.'"', E_USER_ERROR);
						}
						return false;
					}

					if ($method === 'loaddata_unsafe') {
						if (!__exe("SET UNIQUE_CHECKS=0", $query_handler, $trigger_errors, $link_identifier)) return false;
						if (!__exe("set foreign_key_checks=0", $query_handler, $trigger_errors, $link_identifier)) return false;
						if (!__exe("set unique_checks=0", $query_handler, $trigger_errors, $link_identifier)) return false;
					}

					$et = " LOCAL ";
					if( defined("PLATFORM") ){
						switch( PLATFORM ){
						case "linux":
							// $et = "";
						break;
						}
					}

					// print_r( "LOAD DATA ".$et." INFILE '{$in_file}'
					//  INTO TABLE {$table}
					//  FIELDS TERMINATED BY '::::,'
					//  LINES TERMINATED BY '^^^^'
					//  ({$fields})" );exit;
					if (!__exe("
					 LOAD DATA ".$et." INFILE '{$in_file}'
					 INTO TABLE {$table}
					 FIELDS TERMINATED BY '::::,'
					 LINES TERMINATED BY '^^^^'
					 ({$fields})
					", $query_handler, $trigger_errors, $link_identifier)) return false;

				break;
				}

			  // Stop timer
			  $duration = microtime(true) - $start;
			  $qps      = round ($count / $duration, 2);

			  if ($eat_away) {
				$data = array();
			  }

			  return $qps;
		}

		protected function _save_data_to_table( $data, $opt = [] ){
			$error_msg = "Unknow Error";
			if( $data && $opt ){
				extract($opt);
				if( isset($fields) && $fields && isset($labels) && isset($table) ){
					$_data = $this->_format_data_fields($data, $fields, $labels );
					
					if( $_data && is_array( $_data ) ){
						if( isset($bg) && $bg ){
							$ckey = "EP".get_new_id();
							set_cache_for_special_values( array(
								'cache_key' => $ckey,
								'cache_values' => array(
									'table' => $table,
									'data' => $_data
								),
								'directory_name' => $this->table_name,
								'permanent' => 1,
							) );

							set_cache_for_special_values( array(
								'cache_key' => $ckey. '-progress',
								'cache_values' => array( 'status' => 'ongoing', 'error' => '' ),
								'cache_values' => array(
									'status' => 'ongoing',
									'error' => ''
								),
								'directory_name' => $this->table_name,
								'permanent' => 1
							) );

							if( ! self::$isTesting ){
								run_in_background("bprocess", 0, 
									array( 
										"action" => $this->plugin, 
										"todo" => "execute", 
										"nwp_action" => $this->table_name, 
										"nwp_todo" => "save_data_to_table_bg", 
										"user_id" => $this->class_settings["user_id"], 
										"reference" => $ckey
									)
								);
							}

							return $ckey;
						}else{
							try{
								$_stat = $this->_dump_elastic_data( $_data, $table );
								$_v = isset($_stat['_v']) && $_stat['_v'] ? $_stat['_v'] : null;
								$error_msg = isset($_stat['error']) && $_stat['error'] ? $_stat['error'] : 'Unknown Elastic Error';
								if( $_v ){
									return array(
										'status' => 'success',
										'msg' => $error_msg ,
										'time' => $_v
									);
								}
								

							}catch(Throwable $e ){
								$error_msg = "<h4><b>Failed To Load Data</b></h4><p>". $e->getMessage() ."</p>";
							}catch(Exception $e ){
								$error_msg = "<h4><b>Failed To Load Data</b></h4><p>". $e->getMessage() ."</p>";
							}
						}
					}else{
						$error_msg = $_data;
					}
				}else{
					$error_msg = "Undefined Parameters to format fields";
				}
			}

			return array(
	        	'status' => 'error',
	        	'msg' => $error_msg,
	       );
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
														
							$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
							$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
							$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
							// $this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
							$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
							
							$b["more_actions"] = $this->_get_html_view();
							$b["id"] = $e["id"];
							
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
			// debug_print_backtrace();	 exit;
			
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
					$spilt_screen = 1;
					// $this->class_settings['full_table'] = 1;
					$this->class_settings['show_popup_form'] = 1;
				break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 1;
				$this->datatable_settings[ "show_edit_button" ] = 1;
				$this->datatable_settings[ "show_delete_button" ] = 1;
				if( get_hyella_development_mode() ){
					$this->datatable_settings[ "show_refresh_cache" ] = 1;
				}
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

		public function _get_record(){
			$e = parent::_get_record();
			$e = !isset($e['id']) && isset( $e[0] ) && $e[0] ? $e[0] : $e;
			if( isset( $e['id'] ) && $e['id'] ){
				if( isset( $e['headers'] ) && $e['headers'] ){
					$e['headers'] = urldecode( $e['headers'] );
				}
				if( isset( $e['body'] ) && $e['body'] ){
					$e['body'] = urldecode( $e['body'] );
				}
				if( isset( $e['options'] ) && $e['options'] ){
					$e['options'] = urldecode( $e['options'] );
				}
			}
			return $e;
		}

		protected function _save_app_changes2(){
			
			$ddx = array();
			$error = '';
			$e = array();
			$etype = 'error';
			$save_app = 1;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'save_new_popup':
				if(  isset( $_POST[ $this->table_fields['headers'] ] ) && $_POST[ $this->table_fields['headers'] ] ){
					$_POST[ $this->table_fields['headers'] ] = urlencode( $_POST[ $this->table_fields['headers'] ] );
				}
				if(  isset( $_POST[ $this->table_fields['body'] ] ) && $_POST[ $this->table_fields['body'] ] ){
					$_POST[ $this->table_fields['body'] ] = urlencode( $_POST[ $this->table_fields['body'] ] );
				}
				if(  isset( $_POST[ $this->table_fields['options'] ] ) && $_POST[ $this->table_fields['options'] ] ){
					$_POST[ $this->table_fields['options'] ] = urlencode( $_POST[ $this->table_fields['options'] ] );
				}
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
			$this->class_settings["skip_link"] = 1;
			$this->class_settings['hidden_records'][ $this->table_fields['last_run'] ] = 1;
			switch ( $action_to_perform ){
				case 'edit_popup_form_in_popup':
				case 'edit_popup_form':
					$_POST["mod"] = 'edit-'.md5($this->table_name);
					$this->class_settings["modal_title"] = 'Edit ' . $this->label;
					$this->class_settings["override_defaults"] = 1;
					
					// $this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
				break;
				default:
				break;
			}

			if( isset( $_GET['selected_record'] ) && $_GET['selected_record'] && isset( $_GET['sel_id'] ) && $_GET['sel_id'] ){
				$_POST['id'] = $_GET['selected_record'];
			}

			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$this->class_settings['current_record_id'] = $_POST['id'];
				$e = $this->_get_record();
				if( isset( $e['id'] ) && $e['id'] ){
					foreach (['headers', 'body', 'options'] as $key ) {
						if( isset( $e[ $key ] ) && $e[ $key ] ){
							$this->class_settings['form_values_important'][ $this->table_fields[ $key ] ] = $e[ $key ];
						}
					}
				}

				if( isset( $_GET['selected_record'] ) && $_GET['selected_record'] && isset( $_GET['sel_id'] ) && $_GET['sel_id'] && isset( $_POST['id'] ) && $_POST['id'] ){
					unset( $_POST['id'] );
				}
			}

			foreach ($this->table_fields as $key => $value) {
				switch ( $key ) {
					case 'child_type':
					case 'parent_type':
						$this->class_settings['hidden_records'][ $value ] = 1;
					break;
				}
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		protected function _apply_basic_data_control( $e = array() ){
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
						 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
	
		public $basic_data = array(
			'access_to_crud' => 1,	
			//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),

			'more_actions' => array(
				'sb2' => array(
					'todo' => 'call_endpoint',
					'title' => 'Call Endpoint',
					'text' => 'Call',
					'standalone_class' => ' dark ',
					'html_replacement_key' => 'phtml_replacement_selector',
				),
				'sb3' => array(
					'todo' => 'reset_last_call_checkpoint',
					'title' => 'Reset Last Call',
					'text' => 'Reset Call Cache',
					'attributes' => ' confirm-prompt="reset endpoint call cache? This would make the the next enpoint call to pull all pagenated data" ',
					// 'development' => 1,
					'button_class' => ' btn-danger ',
					'html_replacement_key' => 'phtml_replacement_selector',
				),
				'actions' => array(
					'title' => 'More',
					'data' => array(
						'p1' => array(),
					),
				),
			),
		);
	}
	
	function endpoint(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/endpoint.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/endpoint.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				if( isset($return["fields"]) && $return["fields"] ){

					$key = $return[ "fields" ][ "datatable" ];
					$return[ "labels" ][ $key ][ 'form_field_options' ] = 'get_database_tables3';
					$return[ "labels" ][ $key ][ 'add_empty' ] = 1;

					
					$key = $return[ "fields" ][ "authentication" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'endpoint',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'placeholder' ] = 'Authentication Endpoint';
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'class' ] = 'select2 allow-clear';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' field_key="parent" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=get_select2" minlength="2" ';


					$key = $return[ "fields" ][ "count_endpoint" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'endpoint',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'placeholder' ] = 'Count Endpoint';
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'class' ] = 'select2 allow-clear';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' field_key="parent" action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=get_select2" minlength="2" ';
				}

				return $return[ "labels" ];
			}
		}
	}
?>

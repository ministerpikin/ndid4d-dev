<?php
/**
 * reports_bay Class
 *
 * @used in  				reports_bay Function
 * @created  				Hyella Nathan | 22:48 | 05-Jul-2023
 * @database table name   	reports_bay
 */
	
	class cReports_bay extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		
		public $table_name = 'reports_bay';
		
		public $default_reference = '';
		
		public $label = 'Reports';

		private $export_completion_key = "user-export-";
		
		private $associated_cache_keys = array(
			'reports_bay',
			'operators-tree-view' => 'operators-tree-view',
		);

		private $max_export_page_size = 10000;
		
		private $max_export_page_limit = 20000;
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/reports_bay.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/reports_bay.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			if( class_exists('cNwp_app_core') && cNwp_app_core::$def_cs["isTesting"] ){
				self::$isTesting = cNwp_app_core::$def_cs["isTesting"];
			}

			$this->max_export_page_size = defined("ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT") && ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT ? (int)ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT : $this->max_export_page_size;
		}
	
		function reports_bay(){
			
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
				case 'saved_reports':
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
				case 'edit_query':
				case 'view_result':
				case 'filter_card':
				case 'modify_card':
				case 'save_modify_card':
				case 'exec_filter_card_all':
				case 'exec_filter_card':
				case 'filter_card_all':
				case 'exec_report_url':
				case 'geenrate_report':
				case 'get_export_view':
				case 'get_export_data':
				case 'geenrate_report_tb':
					$returned_value = $this->_custom_capture_form_version_2();
				break;
				case 'display_dashboard_personal':
				case 'display_dashboard':
				case 'ping_db':
					$returned_value = $this->_display_dashboard_page();
				break;
				case 'report_dashboard':
					$returned_value = $this->_report_dashboard();
				break;
				case 'get_select2':
					$returned_value = $this->_get_select2();
				break;
				case 'run_search_query':
					$returned_value = $this->_execute_search_window_query();
				break;
				case 'run_search_query2':
					$returned_value = $this->_run_search_query2();
				break;
				case 'bulk_edit':
				case 'search_window':
					$returned_value = $this->_search_window();
				break;
				case 'export_as_csv':
				case 'export_as_csv2':
				case 'save_export_as_csv':
				case 'save_export_as_csv2':
					$returned_value = $this->_export_as_csv();
				break;
				case 'check_bg_process':
				case 'stop_bg_load':
				case 'save_generate_csv_bg':
					$returned_value = $this->_background_export();
				break;
				case 'save_generate_csv':
					$returned_value = $this->_generate_csv();
				break;
				case 'save_generate_csv2':
					$returned_value = $this->_save_generate_csv2();
				break;
				case 'bg_settings':
				case 'save_bg_settings':
					$returned_value = $this->_bg_settings();
				break;
				case 'reset_report':
					$returned_value = $this->_reset_report();
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
				"save_generate_csv_bg",
			)) );
		}

		protected function _reset_report( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];

			$id = isset( $_POST['id'] ) && $_POST['id'] ? $_POST['id'] : '';
			if ($id) {
				$this->class_settings['current_record_id'] = $id;
				$e = $this->_get_record();
			}
		
			switch( $action_to_perform ){
				case 'reset_report':
					if( isset( $e['id'] ) && $e['id'] ){
						if (isset( $e['staff_responsible'] ) && $e['staff_responsible'] && isset( $e['parent_report'] ) && $e['parent_report'] ) {
							$this->class_settings['current_record_id'] = $e['parent_report'];
							$e2 = $this->_get_record();
							if (isset( $e2['id'] ) && $e2['id']) {
								$this->class_settings['update_fields']['data'] = $e2['data'];
								$this->class_settings['update_fields']['custom_data'] = $e2['custom_data'];
								$this->class_settings['update_fields']['saved_query'] = '{}';
								$r = $this->_update_table_field();
								if (isset( $r['saved_record_id'] ) && $r['saved_record_id']) {
									$e_type = 'success';
									$error_msg = "<h4>Saved Query Cleared</h4><p>Saved Query Cleared Successfully</p>";
									$n_params['callback'] = [  'nwResizeWindow.resizeWindow', '$nwProcessor.recreateDataTables', '$nwProcessor.update_column_view_state', '$nwProcessor.set_function_click_event', 'prepare_new_record_form_new' ];
								}
							}else{
								$error_msg = "<h4>Invalid Parent Report</h4><p>Unable to locate parent report</p>";
							}
						}else{
							$error_msg = "<h4><b>Invalid Report</b></h4><p>This report has no saved query</p>";
						}
					}else{
						$error_msg = "<h4><b>Invalid Reference</b></h4><p>Kindly select a valid record</p>";
					}
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _bg_settings( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				
				case 'bg_settings':
					$c_sett = array(
						'cache_key' => md5( 'report-config' ),
						'directory_name' => $this->table_name,
						'permanent' => 1
					);

					$ad = new cAudit();
					$opt = [];
					$opt['group_plugin'] = 1;
					$opt['no_labels'] = 1;
					$opt['no_fields'] = 1;
					$opt['for_database_table_name'] = 1;
					$opt['selected_class_only'] = [
						'cNwp_reports' => 1,
						'cNwp_grm' => 1,
					];

					$this->class_settings['data']["databases"] = $ad->_get_project_classes( $opt );

					$this->class_settings['data']['settings'] = get_cache_for_special_values( $c_sett );
					
					$this->class_settings['html'] = array( $this->view_path . 'report-config.php' );
					
					$html = $this->_get_html_view();

					return array(
						'status' => 'new-status',
						'html_replacement' => $html,
						'html_replacement_selector' => '#'.$handle,
						'javascript_functions' => $js
					);

				break;
				case 'save_bg_settings':
					
					$c_sett = array(
						'cache_key' => md5( 'report-config' ),
						'cache_values' => array(
							'users' =>  isset( $_POST['users'] ) && $_POST['users'] ? $_POST['users'] : '',
							'report' =>  isset( $_POST['report'] ) && $_POST['report'] ? $_POST['report'] : '',
						),
						'directory_name' => $this->table_name,
						'permanent' => 1
					);
										
					set_cache_for_special_values( $c_sett );

					$error_msg = "<h4><b>Saved Successfully</b></h4><p>Report Config Saved Successfully</p>";
					
					$e_type = 'success';
					
					$n_params['html_replacement_selector'] = '#'.$handle;

				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		private function _generate_csv(){

			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$error_msg = '';
			
			$handle = "display_mis_menu-sub";
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			
			$use_container = 1;
			$name = isset( $_POST[ 'name' ] ) ? $_POST[ 'name' ] : '';

			switch( $action_to_perform ){
				case "save_generate_csv":
					if( isset( $this->class_settings["export_data"] ) && $this->class_settings["export_data"] ){
						$data = $this->class_settings["export_data"];
					}else if( isset( $_POST[ 'data' ] ) && $_POST[ 'data' ] ){
						$data = json_decode( $_POST[ 'data' ], true );
					}
					$_POST = array();

					if( isset( $data["tables"] ) && ! empty( $data["tables"] ) ){
						$plugin = isset( $data["plugin"] ) && $data["plugin"] ? $data["plugin"] : '';
						$mine_plugin = isset( $data["mine_plugin"] ) && $data["mine_plugin"] ? $data["mine_plugin"] : '';

						$pl = $plugin;
						if( $mine_plugin )$pl = $mine_plugin;

						if( $pl ){
							$nwp = 'c' . ucwords( $pl );
							if( class_exists( $nwp ) ){
								$nwp = new $nwp();
								$nwp->class_settings = $this->class_settings;
								$nwp->load_class( array( 'class' => array_keys( $data[ 'tables' ] ) ) );
							}else{
								$error_msg = "<h4><strong>Plugin Not Found</strong></h4>".$nwp;
							}
						}
						$outfile = 0;
						
						$data[ 'use_outfile' ] = $outfile;

						$dir = create_report_directory( $this->class_settings, array( 'add_day' => 1 ) );
						
						if( ! $error_msg ){
							$scroll_timeout = '';
							if( isset( $data['export_all'] ) && $data['export_all'] ){
								$data['scroll'] = $scroll_timeout = '5m';
								unset( $data['page_size'] );
							}

							$txd = $this->_get_transformed_data( $data );
							
							$name = isset( $this->class_settings[ 'filename' ] ) ? $this->class_settings[ 'filename' ] : ( $name ? $name : ( isset( $txd["view_name"] ) ? $txd["view_name"] : '' ) );

							unset( $data );

							if( isset( $txd["data"] ) && $txd['data'] && isset( $txd["view_name"] ) && $txd["view_name"]  ){

								$files = [];
								
								$opt = array( "transformed" => 1, "table" => $this->table_name, 'overwrite' => 1 );

								$mdata = isset( cOrm_elasticsearch::$last_query_metadata ) && cOrm_elasticsearch::$last_query_metadata ? cOrm_elasticsearch::$last_query_metadata : [];

								$nm = 0;

								$file_path = str_replace($this->class_settings['calling_page'], '', $dir).'/'.$name . $nm;

								download_csv( $txd["data"], $this->class_settings["calling_page"], $dir.'/'.$name . $nm, $opt);

								unset($txd['data']);

								$files[] = $file_path . '.csv';

								if( isset( $mdata['_scroll_id'] ) && $mdata['_scroll_id'] ){
									$scroll = true;
									$scrollId = $mdata['_scroll_id'];
									$header2 = [];
									foreach($txd['header'] as $hk => $hv ){
										$header2[ $hv['field'] ] = $hv;
									}
									do{
										$nm++;
									    $scrollParams = [
									        'scroll_id' => $scrollId,
									        'scroll' => $scroll_timeout
									    ];

									    $scrollResponse = cOrm_elasticsearch::$esClient->scroll( $scrollParams );

									    if( isset( $scrollResponse['hits']['hits'] ) && $scrollResponse['hits']['hits'] ){

										    if ( count( $scrollResponse['hits']['hits']) > 0) {

										        $txd['data'] = array_column( $scrollResponse['hits'][ 'hits' ], '_source' );
										        $txd['no_header'] = 1;
										        $txd['no_page_header'] = 1;
												$txd['header'] = $header2;
										        $tpage = $this->_get_transformed_data_page( $txd );
												if( isset( $tpage['data'] ) && $tpage['data'] ){
													
													$txd['data'] = $tpage['data'];
													
													unset( $tpage );
													
													$file_path = $dir.'/'.$name . $nm;
	
													download_csv( $txd["data"], $this->class_settings["calling_page"], $file_path, $opt);
													
													unset($txd['data']);
													
													$files[] = str_replace($this->class_settings['calling_page'], '', $file_path) . '.csv';
													
													$scrollId = isset($scrollResponse['_scroll_id']) ? $scrollResponse['_scroll_id'] : '';
												}else{
													$scroll = false;
												}
										    }else{
										      $scroll = false;
										    }									    	
									    }else{
											$scroll = false;
									    }
									}while ( $scroll && $scrollId );
								}

								if( ! empty( $files ) ){
									
									$pr = get_project_data();
									
									$dir = create_report_directory( $this->class_settings );

									$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/' . $name . '.csv';

									$this->_join_files( $files, $file_url, array( 'unlink_files' => 1 ) );

									if( file_exists( $this->class_settings['calling_page'] . $file_url ) ){
										
										$pr = get_project_data();
										
										$did = rand();

										$hash = get_file_hash( array( "hash" => 1, "file_id" => $did, "date_filter" => 'd-M-Y' ) );

										$cn_path = $pr["domain_name"].'print.php?hash='.$hash.'&id='.$did.'&durl='. get_file_hash( array( "encrypt" => $file_url, "key" => $hash ) ) .'&name='. rawurlencode( $name );
										
										$error = '<h4>Successful Data Export</h4><a class="btn btn-success" href="'. $cn_path .'" target="_blank">Download CSV</a>';

										$ra = array( "type" => 'success', "message" => $error, 'manual_close' => 1 );
										
										$ra[ 'do_not_display' ] = 1;
										
										$ra[ 'html_replacement_selector' ] = '#'.$handle;

										return $this->_display_notification( $ra );

									}else{
										$error_msg = "<h4>Invalid File</h4><p>Unable to generate file</p>";
									}
								}else{
									$error_msg = "<h4>Invalid File</h4><p>Unable to generate files</p>";
								}

							}else{
								$error_msg = "<h4>No Dataset</h4>";
								if( isset( $txd['error'] ) && $txd['error'] ){
									$error_msg .= '<p>'. $txd['error'] . '</p>';
								}
							}
						}
					}else{
						$error_msg = "<h4>Invalid Data Fields</h4>Please select the data fields to export";
					}
				break;
			}
			
			$error_msg .=  '<p>'. $this->label . '::' . $action_to_perform . '</p>';
			
			return $this->_display_notification( array( "type" => "error", "message" => $error_msg ) );
		}

		private function _background_export(){

			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			switch( $action_to_perform ){
				case 'check_bg_process':
					$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? constant("DISPLAY_MAIN_AREA_CONTAINER") : "dash-board-main-content-area";
					$handle = $ccx;
					if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
						$handle = $this->class_settings[ 'html_replacement_selector' ];
					}
					
					$action_to_perform = $this->class_settings["action_to_perform"];
					$return = array();
					set_time_limit(0);
					
					$id = '';
					if( ( isset( $_POST["id"] ) && $_POST["id"] ) ){
						$id = $_POST["id"];
					}else{
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>No Reference</h4><p>Process has ended</p>';
						return $err->error();
					}
					
					$cache_key = $this->table_name;
					$run_in_bg = 0;
					$run_in_bg_log = '';
					
					
					sleep( 10 );
					
					$settings = array(
						'cache_key' => $cache_key . "-bg-process-" . $id,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$cid = get_cache_for_special_values( $settings );
					
					$return['status'] = 'new-status';
					
					if( ( isset( $cid["clear"] ) && $cid["clear"] ) ){
						
						$settings = array(
							'cache_key' => $cache_key . "-bg-process-" . $id,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						clear_cache_for_special_values( $settings );
						
						
						if( ( isset( $cid["return"] ) && $cid["return"] ) ){
							return $cid;
						}
						
						$ecode = '010014';
						$msg = '<p>Failed load operation</p>';
						if( ( isset( $cid["success"] ) && $cid["success"] ) ){
							$ecode = '010011';
							$msg = '<p>Successful load operation</p>';
						}

						if( ( isset( $cid["log"] ) && $cid["log"] ) ){
							$msg .= $cid["log"];
						}
						
						$err = new cError( $ecode );
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>Data Export Complete</h4>' . $msg;
						$return = $err->error();
						
						$return['status'] = 'new-status';
						
						$return['html_replacement_selector'] = '#'.$handle;
						$return["html_replacement"] = $return["html"];
						unset( $return["html"] );
						
					}else{
						$e_type = 'info';
						$error_msg = "<h4><b>Backgroung Process Running</b></h4>";
						$cancelBtn = '<br><a href="javascript:;" class="btn btn-block btn-sm btn-danger custom-single-selected-record-button" override-selected-record="'. $id .'" action="?action='. $this->plugin .'&todo=execute&nwp_action='. $this->table_name .'&nwp_todo=stop_bg_load&html_replacement_selector='. $handle .'"  title="Stop Currrent Operation" confirm-prompt="Stop Data Processing Operation">Stop</a> ';

						$error_msg .= $cancelBtn;

						$return = $this->_display_notification( array( 'type' => $e_type, 'message' => $error_msg ) );
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 1;
						$return['id'] = $id;
						$return['post_id'] = $id;
						$return['action'] = '?nwp_action='.$this->table_name.'&nwp_todo=check_bg_process&action='.$this->plugin.'&todo=execute&html_replacement_selector='.$handle;
						// $return['nwp_silent'] = 1;
						$return['re_process_delay'] = 4000;
					}
					return $return;
				break;
				case 'stop_bg_load':
					$id = '';
					if( ( isset( $_POST["id"] ) && $_POST["id"] ) ){
						$id = $_POST["id"];
					}else{
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>No Reference</h4><p>Process has ended</p>';
						return $err->error();
					}
					
					$cache_key = $this->table_name;

					$settings = array(
						'cache_key' => $cache_key . "-bg-process-" . $id,
						'directory_name' => $cache_key,
						'permanent' => true,
					);

					$error_msg = "<h4><b>Data Processing Stopped</b></h4><p>Data loading operation stopped by user</p>";
					
					$cid = get_cache_for_special_values( $settings );
					
					if( !( isset( $cid["success"] ) && $cid["success"] ) ){
						
						$settings['cache_values'] = array(
							'clear' => 1,
							'log' => $error_msg
						);

						set_cache_for_special_values( $settings );
						
						return $this->_display_notification( array( 'type' => 'info', 'message' => $error_msg ) );

					}else{
						$this->class_settings['action_to_perform'] = 'check_bg_process';
						return $this->reports_bay();
					}
				break;
				case 'save_generate_csv_bg':
					$cid = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';

					if( $cid ){
						set_time_limit(0);
						$x1 = array( 'cache_key' => $cid );

						$bg = get_cache_for_special_values( $x1 );
						clear_cache_for_special_values( $x1 );

						$_GET = isset( $bg[ 'get' ] ) ? $bg[ 'get' ] : $_GET;
						$_POST = isset( $bg[ 'post' ] ) ? $bg[ 'post' ] : $_POST;
						$this->class_settings['html_replacement_selector'] = isset($_GET['html_replacement_selector']) && $_GET['html_replacement_selector'] ? $_GET['html_replacement_selector'] : $this->class_settings['html_replacement_selector'];

						$sq = md5('search_query'.$_SESSION['key']);
						if( isset( $bg[ 'cache' ] ) && is_array( $bg[ 'cache' ] ) && ! empty( $bg[ 'cache' ] ) ){
							$_SESSION[$sq] = $bg[ 'cache' ];
						}

						if( defined( "USE_SESSION_FOR_REPORT_FILTER_CACHE" ) && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE")){
							$_SESSION["active_filters"] = isset( $bg[ 'active_filters' ] ) ? $bg[ 'active_filters' ] : array();
						}

						if( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] && isset( $_GET[ 'todo' ] ) && $_GET[ 'todo' ] ){

							$return = $this->_save_generate_csv2();
							$return[ 'return' ] = 1;
							$return[ 'clear' ] = 1;

							$settings = array(
								'cache_key' => $this->table_name . "-bg-process-" . $cid,
								'cache_values' => $return,
								'directory_name' => $this->table_name,
								'permanent' => true,
							);
							set_cache_for_special_values( $settings );
							
							if( isset( $return[ 'typ' ] ) && $return[ 'typ' ] !== 'uerror' ){
								$return[ 'name' ] = isset( $bg[ 'post' ][ 'name' ] ) ? $bg[ 'post' ][ 'name' ] : '';
								$settings = array(
									'cache_key' => $this->export_completion_key . time(),
									'cache_values' => $return,
									'directory_name' => $this->table_name.'/completed-'.$this->class_settings[ 'user_id' ],
									'permanent' => true,
								);
								set_cache_for_special_values( $settings );
							}
						}
					}
					return 1;
				break;
			}
		}

		protected function _get_transformed_data_page( $options = array() ){
			
			if( isset( $options["view_name"] ) && $options["view_name"] ){
				$view_name = $options["view_name"];
				$header = isset( $options["header"] )?$options["header"]:array();
				$format = isset( $options["format"] )?$options["format"]:'';
				$no_page_header = isset( $options["no_page_header"] )?$options["no_page_header"]:'';
				$use_keys_in_result = isset( $options["use_keys_in_result"] )?$options["use_keys_in_result"]:'';
				$tables = isset( $options["tables"] )?$options["tables"]:array();
				$flbl = isset( $options["flbl"] )?$options["flbl"]:array();
				$tcl = isset( $options["tcl"] )?$options["tcl"]:(object) array();
				$ddx = isset( $options["data"] )?$options["data"]:array();
				$db_mode = isset( $options["db_mode"] )?$options["db_mode"]:'elasticsearch';
				$use_field_keys = isset( $options["use_field_keys"] )?$options["use_field_keys"]:0;

				if( isset( $options["page_offset"] ) && isset( $options["page_size"] ) && $options["page_size"] ){
					$page_size = $options["page_size"];
					$offset = $options["page_offset"];
					$page_limit = intval( isset( $options["page_limit"] )?$options["page_limit"]:0 );
					$current_loop_count = intval( isset( $options["current_loop_count"] )?$options["current_loop_count"]:0 );
					
					$txd = array();
					
					if( isset( $ddx[0] ) && ! empty( $ddx[0] ) ){
						$count = 0;
						
						$page_no = ( $offset / $page_size ) + 1;
						if( ! $no_page_header ){
							if( get_hyella_development_mode() ){
								$txd[][] = 'Page ' . $page_no;
							}
						}
						
						if( ! empty( $header ) && ! ( isset( $options["no_header"] ) && $options["no_header"] ) ){
							$row = array();
							foreach( $header as $hv ){
								$row[] = $hv["text"];
							}
							$txd[] = $row;
						}
						
						foreach( $ddx as $dv ){
							
							if( $page_limit && ( $count + ( $page_size * $current_loop_count ) ) >= $page_limit ){
								break;
							}
							++$count;
							
							$row = array();
							foreach( $dv as $dk => $dv2 ){
								if( isset( $header[ $dk ][ 'text' ] ) && $header[ $dk ][ 'text' ] ){
									$flbl = $header[ $dk ][ 'text' ];
								}else if( isset( $header[ $dk ][ 'labels' ][ 'text' ] ) && $header[ $dk ][ 'labels' ][ 'text' ] ){
									$flbl = $header[ $dk ][ 'labels' ][ 'text' ];
								}else if( isset( $header[ $dk ][ 'labels' ][ 'display_field_label' ] ) && $header[ $dk ][ 'labels' ][ 'display_field_label' ] ){
									$flbl = $header[ $dk ][ 'labels' ][ 'display_field_label' ];
								}else{
									$flbl = isset( $header[ $dk ][ 'labels' ][ 'form_field' ] ) ? $header[ $dk ][ 'labels' ][ 'form_field' ] : $dk;
								}

								if( $use_field_keys ){
									$flbl = $dk;
								}

								if( isset( $header[ $dk ]["labels"] ) ){

									$check_class = 0;
									switch( $format ){
									case 'json_formatted':
										if( isset( $flbl[ $dk ] ) ){
											switch( $flbl[ $dk ] ){
											case 'data':
												if( is_object( $tcl ) && method_exists( $tcl, 'format_data_export' ) ){
													$check_class = $tcl->format_json_api( array( 'value' => $dv2 ) );
												}
											break;
											}
										}
									break;
									}

									if( $check_class ){
										if( $use_keys_in_result ){
											$row[ $flbl ] = $check_class;
										}else{
											$row[] = $check_class;
										}
									}else{
										if( isset( $header[ $dk ]["labels"][ 'calculations' ] ) ){
											$header[ $dk ]["labels"][ 'calculations' ][ 'variables' ][ 0 ][ 0 ] = $dk;
										}
										
										$gbal = array(
											'labels' => array( $dk => $header[ $dk ]["labels"] ),
											'fields' => array( $dk => $dk ),
										);
										$vall = '';
										if( isset( $header[ $dk ][ 'field' ] ) && isset( $this->system_fields[ $header[ $dk ][ 'field' ] ] ) && $this->system_fields[ $header[ $dk ][ 'field' ] ] ){
											$vall = $dv2;
										}else{
											$aggr = '';
											if( isset( $tcl->table_name ) && isset( $tables[ $tcl->table_name ][ $dk ][ 'aggregation' ] ) ){
												$aggr = $tables[ $tcl->table_name ][ $dk ][ 'aggregation' ];
											}

											switch( $aggr ){
												case "daily_date":
												case "weekly_date":
												case "monthly_date":
												case "yearly_date":
												case "avg_resolve_hours":
												case "avg_resolve_days":
													$vall = $dv2;
												break;
												default:
													$vall = __get_value( trim( $dv2 ? $dv2 : '' ), $dk, array( 'globals' => $gbal ) );
												break;
											}
										}

										if( $use_keys_in_result ){
											$row[ $flbl ] = $vall;
										}else{
											$row[] = $vall;
										}
									}
								}else{
									if( $use_keys_in_result ){
										$row[ $flbl ] = trim( $dv2 );
									}else{
										$row[] = trim( $dv2 );
									}
								}
								
								unset( $dv[ $dk ] );
							}
							// exit;
							$txd[] = $row;
						}
						
						$return = array( 'data' => $txd, 'view_name' => $view_name, 'header' => $header, 'format' => $format, 'tables' => $tables, 'tcl' => $tcl, 'flbl' => $flbl );
						
						if( $count >= $page_size ){
							//check for next page
							$return['page_offset'] = $offset + $page_size;
							$return['page_size'] = $page_size;
							$return['page_limit'] = $page_limit;
							$return['current_loop_count'] = ++$current_loop_count;
						}
						
						return $return;
					}
				}
			}
		}
		
		protected function _get_transformed_data( $data = array() ){
			$error_msg = '<h4>Unknown Transformation Error</h4>';
			$error_msg2 = '';
			$no_default_group = isset( $data[ 'no_default_group' ] )?$data[ 'no_default_group' ]:0;
			$format = isset( $data[ 'format' ] )?$data[ 'format' ]:'csv';
			$use_field_keys = isset( $data[ 'use_field_keys' ] )?$data[ 'use_field_keys' ]:0;
			$page_size = intval( isset( $data[ 'page_size' ] )?$data[ 'page_size' ]:0 );
			if( ! $page_size ){
				$page_size = $this->max_export_page_size;
			}
			if( $page_size > $this->max_export_page_size ){
				$page_size = $this->max_export_page_size;
			}
			
			$page_limit = intval( isset( $data[ 'page_limit' ] )?$data[ 'page_limit' ]:0 );
			if( ! $page_limit ){
				$page_limit = $this->max_export_page_limit;
			}
			if( $page_limit > $this->max_export_page_limit ){
				$page_limit = $this->max_export_page_limit;
			}
			
			$offset = intval( isset( $data[ 'page_offset' ] )?$data[ 'page_offset' ]:0 );

			$exclude_fields = array(
				'id' => 'id',
				'created_role' => 'created_role',
				'created_by' => 'created_by',
				'creation_date' => 'creation_date',
				'modified_by' => 'modified_by',
				'modification_date' => 'modification_date',
				'ip_address' => 'ip_address',
				'record_status' => 'record_status',
				'serial_num' => 'serial_num',
			);

			$db_mode = '';
			if( isset( $this->class_settings[ 'db_mode' ] ) && $this->class_settings[ 'db_mode' ] ){
				$db_mode = $this->class_settings[ 'db_mode' ];
			}
			
			if( isset( $data[ 'tables' ] ) && is_array( $data[ 'tables' ] ) && ! empty( $data[ 'tables' ] ) ){
				$replace_where = isset( $data[ 'query' ][ 'replace_where' ] )? $data[ 'query' ][ 'replace_where' ] : 0;
				$replace_join = isset( $data[ 'query' ][ 'replace_join' ] )? $data[ 'query' ][ 'replace_join' ] : 0;
				
				$where = ( isset( $data[ 'query' ][ 'where' ] ) && $data[ 'query' ][ 'where' ] ) ? ( $data[ 'query' ][ 'where' ] . ' ' ) : '';
								
				$group = ( isset( $data[ 'query' ][ 'group' ] ) && $data[ 'query' ][ 'group' ] ) ? ( $data[ 'query' ][ 'group' ] . ' ' ) : '';
				$join = ( isset( $data[ 'query' ][ 'join' ] ) && $data[ 'query' ][ 'join' ] ) ? ( $data[ 'query' ][ 'join' ] . ' ' ) : '';

				$nfl = array();
				$header = array();
				$select = array();
				$select2 = array();
				$tbs = array();
				$sn = 0;

				// ORM
				$Oselect = array();
				$Ojoin = array();
				$Ofrom = array();
				$Oview = array();
				$Ogroup = array();
				$Olimit = array();
				$Ooffset = array();
				$Oorder = array();
				$Owhere = array();
				
				$use_selects = isset( $data[ 'use_selects' ] ) ? $data[ 'use_selects' ] : array();
				$use_groups = isset( $data[ 'use_groups' ] ) ? $data[ 'use_groups' ] : array();
				$use_sorts = isset( $data[ 'use_sorts' ] ) ? $data[ 'use_sorts' ] : array();
				$no_concat = isset( $data[ 'no_concat' ] ) ? $data[ 'no_concat' ] : 0;
				$use_keys_in_result = isset( $data[ 'use_keys_in_result' ] ) ? $data[ 'use_keys_in_result' ] : 0;
				$no_page_header = isset( $data[ 'no_page_header' ] ) ? $data[ 'no_page_header' ] : 0;

				if( isset( $data[ 'table' ] ) && $data[ 'table' ] ){
					$tcl = 'c'.ucwords( $data[ 'table' ] );

					if( class_exists( $tcl ) ){
						$tcl = new $tcl;

						if( isset( $tcl->children ) && is_array( $tcl->children ) && ! empty( $tcl->children ) ){

							$dd = array();
							$dd[ 'plugin' ] = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
							$dd[ 'ptable' ] = $data[ 'table' ];
							$dd[ 'tables' ] = array_keys( $tcl->children );
							$dd[ 'get_join' ] = 1;

							$sh = new cSearch();
							$sh->class_settings = $this->class_settings;

							if( isset( $this->class_settings[ 'mine_data_source' ] ) && $this->class_settings[ 'mine_data_source' ] ){
								$sh->class_settings[ 'data' ][ 'social_registry' ] = $this->class_settings[ 'mine_data_source' ];
							}

							// print_r( $data );exit;
							// @mx look later
							if( ! $join )$join .= $sh->_execute_search_window_query( $dd );

							if( is_array( $join ) ){
								return $join;
							}
							unset( $sh );
						}
					}
				}

				if( ! $no_default_group ){
					if( count( $data[ 'tables' ] ) == 1 && ! $group ){
						$group = " GROUP BY `". $data[ 'table' ] ."`.`id` ";
					}
				}

				$sss = new cSearch;
				$allLabels = [];
				if( class_exists( 'cNwp_reports' ) ){
					$nwr = new cNwp_reports;
					$nwr->class_settings = $this->class_settings;
					$als = $nwr->load_class( [ 'class' => [ 'reports_bay' ], 'initialize' => 1 ] );
					$sss = $als[ 'reports_bay' ];
				}
				
				foreach( $data[ 'tables' ] as $key => $value ){
					$tbs[ $key ] = $key;
					$tb = 'c' . ucwords( $key );

					if( isset( $data[ 'query' ][ 'query_options' ] ) && $data[ 'query' ][ 'query_options' ] ){
						$pp = $_POST;
						$_POST[ 'data' ] = json_encode( array( 'cart_items' => $data[ 'query' ][ 'query_options' ], 'dataSource' => $key ) );
						$_POST[ 'plugin' ] = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
						$qq = $sss->_execute_search_window_query( array( 'return_query' => 1 ) );

						if( is_array( $qq ) ){
							$Owhere = array_merge( $qq, $Owhere );
						}
						$_POST = $pp;
					}
					
					$sq = md5('search_query'.$_SESSION['key']);
					if( isset( $qq ) && ! is_array( $qq ) ){
						$where .= ' AND '.$qq;
					}elseif( isset( $_SESSION[$sq][ $key ]['query'] ) && $_SESSION[$sq][ $key ]['query'] ){
						
						$cache_key = md5( $key .'-'. $this->class_settings[ 'user_id' ] . '-search');
						$stx = array(
							'cache_key' => $cache_key,
							'directory_name' => 'search',
							'permanent' => true,
						);
						$da = get_cache_for_special_values( $stx );
						if( isset( $da[ 'query' ] ) && $da[ 'query' ] ){
							$where .= " AND " . $da[ 'query' ];
						}
					}

					// Gets the where tied to a datatable
					if( ! ( isset( $this->class_settings[ 'ignore_cache_conditions' ] ) && $this->class_settings[ 'ignore_cache_conditions' ] ) ){
						if( isset( $_SESSION[ $key ]['filter'][ 'additional_where' ] ) && $_SESSION[ $key ]['filter'][ 'additional_where' ] ){
							$where .= $_SESSION[ $key ]['filter'][ 'additional_where' ];
						}

						if( isset( $_SESSION[ $key ]['filter'][ 'join' ] ) && $_SESSION[ $key ]['filter'][ 'join' ] ){
							// $additional_join = $_SESSION[ $key ]['filter'][ 'join' ];
						}
					}
					
					if( class_exists( $tb ) && function_exists( $key ) ){
						$cl = new $tb();
						$labels = $key();
						$allLabels[ $cl->table_name ] = $labels;

						if( isset( $data[ 'plugin' ] ) && $data[ 'plugin' ] ){
							$cl->plugin = $data[ 'plugin' ];
						}
						
						if( ! isset( $data[ 'table' ] ) ){
							$data[ 'table' ] = $key;
						}
						
						foreach( $value as $k => $v ){
							$arg = array();
							$fd = '';
							if( isset( $v[ 'field' ] ) && $v[ 'field' ] ){
								$k = $v[ 'field' ];
							}

							if( isset( $cl->table_fields[ $k ] ) ){
								$fd = $cl->table_fields[ $k ];
							}

							if( ! $fd && isset( $exclude_fields[ $k ] ) ){
								$fd = $exclude_fields[ $k ];
							}

							if( $fd ){
								$v1 = $fd;
								
								$t = $k;
								
								switch( $format ){
								case "json":
									if( isset( $v[ 'alias' ] ) ){
										$t = $v[ 'alias' ];
									}
									$rt = "'" . $t ."' ";
								break;
								default:
									if( isset( $v[ 'alias' ] ) ){
										$t = $v[ 'alias' ];
									}else if( isset( $exclude_fields[ $k ] ) ){
										$t = $v1;
									}else if( isset( $labels[ $v1 ]["text"] ) ){
										$t = $labels[ $v1 ]["text"];
									}else if( isset($labels[ $v1 ]["abbreviation"] ) && $labels[ $v1 ]["abbreviation"] ){
										$t = $labels[ $v1 ]["abbreviation"];
									}else{
										$t = $labels[ $v1 ]["field_label"];
									}

									if( isset( $cl->basic_data[ 'include_in_export' ][ $v1 ] ) && $cl->basic_data[ 'include_in_export' ][ $v1 ] ){
										$t = $cl->basic_data[ 'include_in_export' ][ $v1 ];
									}
									
									++$sn;
									$rt_value = 'f'.$sn;

									switch( $db_mode ){
									case 'elasticsearch':
										$rt_value = $k;
									break;
									}

									switch( $format ){
									case "json_formatted":
										$rt_value = $k;
									break;
									}

									if( isset( $v[ 'alias' ] ) && $v[ 'alias' ] ){
										$rt_value = $v[ 'alias' ];
									}
									$rt = "'". $rt_value ."' ";
									
									$header[ $rt_value ] = array(
										"table" => $key,
										"key" => $k,
										"field" => $v1,
										"text" => $t,
										"labels" => isset( $labels[ $v1 ] )?$labels[ $v1 ]:array(),
									);
								break;
								}

								$fxll = trim( str_replace( "'", '', $rt ) );
								switch( $format ){
								case "json_formatted":
								case "csv":
									$flx = "`". $key ."`.`". $v1 ."`";
									$aggr = '';
									if( isset( $v[ 'aggregation' ] ) && $v[ 'aggregation' ] ){
										$aggr = $v[ 'aggregation' ];
									}elseif( isset( $use_selects[ $key ][ $k ] ) && $use_selects[ $key ][ $k ] ){
										$aggr = $use_selects[ $key ][ $k ];
									}
									if( $aggr ){
										$arg[ $aggr ] = $aggr;
										switch( $aggr ){
										case 'min':
											$flx = " MIN( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'max':
											$flx = " MAX( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'count':
											$flx = " COUNT( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'sum':
											$flx = " SUM( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'avg':
											$flx = " AVG( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'percentage':
											$flx = " ROUND( COUNT( `". $key ."`.`". $v1 ."` ) * 100.0 / ( SELECT COUNT(*) FROM `". $key ."` ), 2 ) ";
										break;
										case 'daily_date':
											$flx = " DATE( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) ";
										break;
										case 'weekly_date':
											$flx = " CONCAT( YEARWEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ), '-W', WEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) ) ";
										break;
										case 'monthly_date':
											$flx = " DATE_FORMAT( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ), '%Y-%m' ) ";
										break;
										case 'yearly_date':
											$flx = " DATE_FORMAT( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ), '%Y' ) ";
										break;
										case 'avg_resolve_hours':
										case 'avg_resolve_days':
											$ssd = '';
											$eed = '';
											if( isset( $value[ 'start_date' ][ 'field' ] ) && $value[ 'start_date' ][ 'field' ] ){
												if( isset( $cl->table_fields[ $value[ 'start_date' ][ 'field' ] ] ) ){
													$ssd = $cl->table_fields[ $value[ 'start_date' ][ 'field' ] ];
												}else{
													$ssd = $value[ 'start_date' ][ 'field' ];
												}
											}
											if( isset( $value[ 'end_date' ][ 'field' ] ) && $value[ 'end_date' ][ 'field' ] ){
												if( isset( $cl->table_fields[ $value[ 'end_date' ][ 'field' ] ] ) ){
													$eed = $cl->table_fields[ $value[ 'end_date' ][ 'field' ] ];
												}else{
													$eed = $value[ 'end_date' ][ 'field' ];
												}
											}

											if( $ssd && $eed ){
												$ddr = '60 * 60';

												switch( $aggr ){
												case 'avg_resolve_days':
													$ddr = '24 * 60 * 60';
												break;
												}

												$flx = " AVG( ( `". $key ."`.`". $eed ."` - `". $key ."`.`". $ssd ."` ) / ( ". $ddr ." ) ) ";
											}else{
												continue 3;
											}
										break;
										}
									}

									$ffn = '_get_'.$k;
									if( method_exists( $cl, $ffn ) ){
										$cl->class_settings = $this->class_settings;
										$fl2 = $cl->$ffn();
										if( is_array( $fl2 ) && ! empty( $fl2 ) && isset( $fl2[ 'query' ] ) && $fl2[ 'query' ] ){
											$flx = " ( ". $fl2[ 'query' ] ." ) ";
										}

										if( isset( $fl2[ 'group' ] ) && $fl2[ 'group' ] && count( $data[ 'tables' ] ) > 1 ){
											if( isset( $fl2[ 'overide_group' ] ) && $fl2[ 'overide_group' ] ){
												$group = " GROUP BY ".$fl2[ 'group' ];
											}else{
												if( $group ){
													$group .= ", " . $fl2[ 'group' ];
												}else{
													$group = " GROUP BY ".$fl2[ 'group' ];
												}
											}
										}
									}

									$as = " as ". $rt;
									if( isset( $data[ 'use_outfile' ] ) && $data[ 'use_outfile' ] ){
										$select2[] = $header[ $fxll ][ 'text' ];
										// $as = "";
									}

									$qqy = '';
									if( isset( $no_concat ) && $no_concat ){
										$qqy = $flx . $as;
									}else{
										$qqy = " CONCAT( ". '"\\t"' .", ". $flx ." ) " . $as;
										$arg[ 'concat' ] = array(
											'params' => array(
												"\\t",
												"__field__",
											)
										);
									}
									$select[] = $qqy;

									$cols = array(
										'field' => $v1,
										'table' => $key,
										'alias' => $fxll,
										'aggregation' => $arg,
									);
									if( $aggr ){
										switch( $aggr ){
										case 'percentage':
										case 'daily_date':
										case 'weekly_date':
										case 'monthly_date':
										case 'yearly_date':
										case 'avg_resolve_hours':
										case 'avg_resolve_days':
											$cols[ 'custom' ] = $flx;
										break;
										}
									}
									$Oselect[] = $cols;
								break;
								default:
									if( isset( $use_selects[ $key ][ $k ] ) && $use_selects[ $key ][ $k ] ){
										switch( $use_selects[ $key ][ $k ] ){
										case 'min':
											$select[] = " MIN( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'max':
											$select[] = " MAX( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'count':
											$select[] = " COUNT( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'sum':
											$select[] = " SUM( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'avg':
											$select[] = " AVG( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'daily_date':
											$select[] = " DATE( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) as " . $rt;
										break;
										case 'weekly_date':
											$select[] = " CONCAT( YEARWEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ), '-W', WEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) ) as " . $rt;
										break;
										case 'monthly_date':
											$select[] = " DATE_FORMAT(FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ), '%Y-%m' ) as " . $rt;
										break;
										default:
											$select[] = " `". $key ."`.`". $v1 ."` as " . $rt;
										break;
										}
									}else{
										$select[] = " `". $key ."`.`". $v1 ."` as " . $rt;
									}
								break;
								}
								$nfl[ $fxll ] = $k;
							}
						}
						
						if( isset( $use_groups[ $key ] ) && $use_groups[ $key ] ){
							$group = "";
							foreach( $use_groups[ $key ] as $gpp => $gppc ){
								$gxp = $gpp;
								$gxp2 = array(
									'alias' => $gpp,
								);
								if( isset( $cl->table_fields[ $gpp ] ) && $cl->table_fields[ $gpp ] ){
									$gxp = " `". $key ."`.`". $cl->table_fields[ $gpp ] ."` ";
									$gxp2 = array(
										'table' => $key,
										'field' => $cl->table_fields[ $gpp ],
									);
									switch( $db_mode ){
									case 'elasticsearch':
										$gxp2[ 'alias' ] = $gpp;
									break;
									}
								}

								if( $gxp ){
									$Ogroup[] = $gxp2;
									if( $group ){
										$group .= " , ".$gxp;
									}else{
										$group = " GROUP BY ".$gxp;
									}
								}
							}
						}

						if( isset( $use_sorts[ $key ] ) && $use_sorts[ $key ] ){
							$order = "";
							if( isset( $use_sorts[ $key ][ 'sort_asc' ] ) && $use_sorts[ $key ][ 'sort_asc' ] ){
								$ofl = isset( $cl->table_fields[ $use_sorts[ $key ][ 'sort_asc' ] ] ) ? $cl->table_fields[ $use_sorts[ $key ][ 'sort_asc' ] ] : $use_sorts[ $key ][ 'sort_asc' ];
								$order = " ORDER BY `". $key ."`.`". $ofl ."` ASC ";
								$Oorder = array(
									'field' => $ofl,
									'table' => $key,
									'sort' => 'asc', // desc/asc
								);
							}elseif( isset( $use_sorts[ $key ][ 'sort_desc' ] ) && $use_sorts[ $key ][ 'sort_desc' ] ){
								$ofl = isset( $cl->table_fields[ $use_sorts[ $key ][ 'sort_desc' ] ] ) ? $cl->table_fields[ $use_sorts[ $key ][ 'sort_desc' ] ] : $use_sorts[ $key ][ 'sort_desc' ];
								$order = " ORDER BY `". $key ."`.`". $ofl ."` DESC ";
								$Oorder = array(
									'field' => $ofl,
									'table' => $key,
									'sort' => 'desc', // desc/asc
								);
							}
						}

						unset( $cl );
						unset( $labels );
					}else{
						$error_msg2 = '<h4><strong>Class Not Found</strong></h4>'.$tb;
					}
				}

							
				if( ! $error_msg2 ){
					if( ! empty( $select ) && isset( $data[ 'table' ] ) && $data[ 'table' ] ){
						$tx = $data[ 'table' ];
						
						if( $replace_join ){
							$join = ( isset( $data[ 'query' ][ 'join' ] ) && $data[ 'query' ][ 'join' ] ) ? ( $data[ 'query' ][ 'join' ] . ' ' ) : '';
						}else{

							if( count( $data[ 'tables' ] ) > 1 ){
							}
						}
						
						if( ! $join && isset( $data[ 'query' ][ 'join' ] ) && $data[ 'query' ][ 'join' ] ){
							$join = $data[ 'query' ][ 'join' ];
						}
						
						$sel = "SELECT " . implode(", ", $select ) . " FROM `". $this->class_settings["database_name"] ."`.`". $tx ."` ";
						$where1 = " WHERE `". $tx ."`.`record_status` = '1' ";
						
						if( $replace_where ){
							$where1 = '';
						}

						switch( $format ){
							default:
								$view_name = 'csv_' . get_new_id('cs').rand(1, 100);
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query_type' => 'EXECUTE',
									'set_memcache' => 0,
									'tables' => array(),
								);

								if( !(isset( $data[ 'use_outfile' ] ) && $data[ 'use_outfile' ] ) ){

									$Ofrom = array(
										'database' => $this->class_settings['database_name'],
										'table' => $tx,
									);

									$Oview = array(
										'view_name' => $view_name,
										'database' => $this->class_settings['database_name'],
									);
									
									switch( $db_mode ){
										case 'elasticsearch':
											if( isset( $page_size ) && $page_size ){
												$Olimit[] = $page_size;
											}
											if( isset( $data['page_offset'] ) && $data['page_offset'] ){
												$Ooffset[] = $data['page_offset'];
											}
										break;
									}

									// $query = "CREATE OR REPLACE VIEW `".$this->class_settings['database_name']."`.`".$view_name."` AS ( ".  $sel . $join . $additional_join . $where1 . $where . $group . $order ." ) ";
									// $db_mode = '';
									$query_settings = array(
										'database' => $this->class_settings['database_name'] ,
										'connect' => $this->class_settings['database_connection'] ,
										// 'query' => $query,
										'query' => array(
											'columns' => $Oselect,
											'from' => $Ofrom,
											'joins' => $Ojoin,
											'where' => $Owhere,
											'group' => $Ogroup,
											'order' => $Oorder,
											'offset' => $Ooffset,
											'limit' => $Olimit,
											'view' => $Oview,
											'scroll' => isset( $data['scroll'] ) && $data['scroll'] ? $data['scroll'] : '',
										),
										'allLabels' => $allLabels,
										'query_type' => 'EXECUTE',
										'query_type' => 'SELECT',
										'db_mode' => $db_mode,
										'set_memcache' => 0,
										'tables' => array( $view_name ),
									);
									
									$dxd = execute_sql_query( $query_settings );

									if( isset( $dxd[ 'error' ] ) && $dxd[ 'error' ] ){
										unset( $dxd['data'] );
										return $dxd;
									}

									switch( $db_mode ){
										case 'elasticsearch':
										break;
										default:
											$dxd = array();
										break;
									}

									$no_header = isset( $this->class_settings[ 'no_header' ] ) ? $this->class_settings[ 'no_header' ] : 0;
									return $this->_get_transformed_data_page(
										array( 
											'db_mode' => $db_mode, 
											'data' => $dxd, 
											'tables' => $data[ 'tables' ], 
											'flbl' => $nfl, 
											'tcl' => ( isset( $tcl ) ? $tcl : (object) array() ), 
											'format' => $format, 
											"no_header" => $no_header, 
											"current_loop_count" => 0, 
											"header" => $header, 
											"use_keys_in_result" => $use_keys_in_result, 
											"view_name" => $view_name, 
											"page_size" => $page_size, 
											"page_offset" => $offset, 
											"page_limit" => $page_limit,
											"no_page_header" => $no_page_header,
											"use_field_keys" => $use_field_keys,
										)
									);
								}
								
							break;
						}
						
						
					}else{
						$error_msg = '<h4>Invalid Parameters</h4>Selection fields or Data Source not defined';
					}
				}else{
					$error_msg = $error_msg2;
				}
			}else{
				$error_msg = '<h4>Invalid Transformation Data</h4>';
			}
			
			return $this->_display_notification( array( "type" => 'error', "message" => $error_msg ) );
		}

		protected function _save_generate_csv2( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
						
			$name = isset( $_POST[ 'name' ] ) ? $_POST[ 'name' ] : '';
			$dx = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], true ) : array();

			if( isset($dx['report_id']) && $dx['report_id'] ){
				$this->class_settings['current_record_id'] = $dx['report_id'];
				$ex = $this->_get_record();
				$_json = isset( $ex['data'] ) && $ex['data'] ? json_decode($ex['data'], true) : [];
				$_saved_data = isset( $ex['saved_query'] ) && $ex['saved_query'] ? json_decode( $ex['saved_query'], true ) : [];
				
				if( isset($_GET['filter']) && $_GET['filter'] ){
					$cache_key = md5( $dx['report_id'] . "-" . $this->class_settings["user_id"] . "-search");
					$_cjson = [];
					if( defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE") ){
						$_cjson = isset( $_SESSION["active_filters"][ $ex['report_key'] ][ $cache_key ] ) && $_SESSION["active_filters"][ $ex['report_key'] ][ $cache_key ] ? $_SESSION["active_filters"][ $ex[ 'report_key' ] ][ $cache_key ] : $_cjson;
					}else{
						$_cjson = get_cache_for_special_values( array(
							'cache_key' => $cache_key,
							'directory_name' => 'search',
							'permanent' => true,
						) );						
					}

					if( !$_cjson && isset( $ex['report_key'] ) && $ex['report_key'] ){
						$cache_key = md5( $ex['report_key'] . $this->class_settings[ 'user_id' ] . '-search' );
						if( defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE") ){
							$_cjson = isset( $_SESSION["active_filters"][ $cache_key ] ) && $_SESSION["active_filters"][ $cache_key ] ? $_SESSION["active_filters"][ $ex[ 'report_key' ] ][ $cache_key ] : $_cjson;
						}else{
							$_cjson = get_cache_for_special_values( array(
								'cache_key' => $cache_key,
								'directory_name' => 'search',
								'permanent' => true,
							) );
						}
					}
										
					if( isset( $_cjson["cart_items"] ) && $_cjson['cart_items'] ){
						$cnt = 1;
						if( $_json && isset( $_json[ 'query' ] ) && $_json[ 'query' ] && isset( $_json[ 'query' ][ 'query_options' ] ) && $_json[ 'query' ][ 'query_options' ] ){
							$cnt = count( $_json[ 'query' ][ 'query_options' ] ) + 1;
						}

						foreach( $_cjson[ 'cart_items' ] as $cv ){
							if( ! empty( $_json[ 'query' ][ 'query_options' ] ) && ! ( isset( $cv[ 'condition' ] ) && $cv[ 'condition' ] ) ){
								$cv[ 'condition' ] = 'AND';
							}
							$_json[ 'query' ][ 'query_options' ][ strval( $cnt ) ] = $cv;
							$cnt++;
						}
					}
				}elseif( isset($_saved_data['cart_items']) && $_saved_data['cart_items']){
					$cnt = 1;
					if( $_json && isset( $_json[ 'query' ] ) && $_json[ 'query' ] && isset( $_json[ 'query' ][ 'query_options' ] ) && $_json[ 'query' ][ 'query_options' ] ){
						$cnt = count( $_json[ 'query' ][ 'query_options' ] ) + 1;
					}

					foreach( $_saved_data[ 'cart_items' ] as $cv ){
						if( ! empty( $_json[ 'query' ][ 'query_options' ] ) && !( isset( $cv[ 'condition' ] ) && $cv[ 'condition' ] ) ){
							$cv[ 'condition' ] = 'AND';
						}
						$_json[ 'query' ][ 'query_options' ][ strval( $cnt ) ] = $cv;
						$cnt++;
					}
				}

				$dx = array_merge( $_json, $dx );
				if( isset($dx['use_selects']) && $dx['use_selects'] ){
					unset($dx['use_selects']);
				}

				if( isset($dx['use_groups']) && $dx['use_groups'] ){
					unset($dx['use_groups']);
				}
				$name = isset($ex['name']) && $ex['name'] ? $ex['name'] : ( isset($ex['title']) && $ex['title'] ? $ex['title'] : $name );
				$_POST['name'] = $name;
				$_POST['data'] = json_encode( $dx );
			}
			
			$this->class_settings[ 'action_to_perform' ] = 'save_generate_csv';
			
			$this->class_settings['db_mode'] = 'elasticsearch';
			
			if( !$error_msg ){

				$files = array();
				
				$this->class_settings[ 'return_name' ] = 1;
				
				return $this->_generate_csv();										
			}

			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		private function _join_files( $files = array(), $result = '', $opt = array() ){
		    if( ! is_array( $files ) ){
		        throw new Exception( '`$files` must be an array' );
		    }

		    $wH = fopen( $this->class_settings[ 'calling_page' ] . $result, "w+" );

		    foreach( $files as $file ){
		        $fh = fopen( $this->class_settings[ 'calling_page' ] . $file, "r" );
		        while( ! feof( $fh ) ){
		            fwrite( $wH, fgets( $fh ) );
		        }

		        fclose( $fh );
		        unset( $fh );

		        if( isset( $opt[ 'unlink_files' ] ) && $opt[ 'unlink_files' ] ){
		        	unlink( $this->class_settings[ 'calling_page' ] . $file );
		        }
		    }

		    fclose( $wH );

		    unset( $wH );
		}

		protected function _export_as_csv( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			$id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';

			$data = isset($_POST['data']) && $_POST['data'] ? json_decode($_POST['data'], true) : [];

			switch( $action_to_perform ){
				case 'export_as_csv':
				case 'export_as_csv2':
					if( !$id ){
						$error_msg = "<h4><b>Invalid Reference</b></h4><p>Select a valid report item</p>";
					}
					if( !$error_msg ){

						switch ( $action_to_perform ) {
							case 'export_as_csv':
								$this->class_settings['current_record_id'] = $id;
								$e = $this->_get_record();
							break;
							default:
								$tbd = explode( ":::", $id );
								$tba = array();
								$tb3 = [];
								if( ! empty( $tbd ) ){
									foreach( $tbd as $tbv ){
										$tba2 = explode( '=', $tbv );
										if( isset( $tba2[1] ) && $tba2[1] ){
											$tba[ $tba2[0] ] = $tba2[1];
										}
									}
									$tb_type = isset( $tba["type"] )?$tba["type"]:'';
									$tb3['table'] = isset( $tba["value"] )?$tba["value"]:'';
									if( $tb_type ){
										switch ( $tb_type ) {
											case 'plugin':
												$tb3['plugin'] = isset($tba['key']) && $tba['key'] ? $tba['key'] : '';
											break;
										}										
									}
								}
								$e = [ 'id' => $action_to_perform, 'data' => json_encode( $tb3 ) ];
							break;
						}

						if( isset($e['id']) && $e['id'] ){
							$df = isset($e['data']) && $e['data'] ? json_decode( $e['data'], 1 ) : [];
							if( $df ){
								$cls = null;
								if( isset( $df['plugin'] ) && $df['plugin'] ){
									$nwp = 'c'. ucfirst($df['plugin']);
									$nwp = new $nwp();
									$nwp->class_settings = $this->class_settings;
									$tb = isset($df['table']) && $df['table'] ? $df['table'] : ( isset( $df['tables'] ) && $df['tables'] ? array_key_first( $df['tables'] ) : '' );
									if( $tb ){
										$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
										$cls = $in[ $tb ];
									}else{
										$error_msg = "<h4><b>Invalid Report</b></h4><p>Table is not defined</p>";	
									}
								}else{
									$tb = isset($df['table']) && $df['table'] ? $df['table'] : ( isset( $df['tables'] ) && $df['tables'] ? array_key_first( $df['tables'] ) : '' );
									if( $tb ){
										$tb = 'c'.ucfirst($tb);
										$cls = new $tb();
										$cls->class_settings = $this->class_settings;
									}else{
										$error_msg = "<h4><b>Invalid Report</b></h4><p>Table is not defined</p>";
									}
								}

								switch( $action_to_perform ){
									case 'export_as_csv2':
										$e['title'] = $cls->label;
									break;
								}

								if( !$error_msg ){
									$data = [];
									$data['filter'] = isset($_GET['filter']) && $_GET['filter'] ? $_GET['filter'] : '';
									$data['report_id'] = $e;
									$data["table"] = $cls->table_name;
									$data["tables"][ $cls->table_name ]["title"] = $e['title'];
									$data["tables"][ $cls->table_name ]["fields"] = $cls->table_fields;
									$data["tables"][ $cls->table_name ]["labels"] = $tb();

									$data['event']['data']['fields_title'] = "Display Fields";
									$data['event']['data']['plugin'] = isset( $df['plugin'] ) && $df['plugin'] ? $df['plugin'] : $this->plugin;
									$data['event']['data']['table'] = $cls->table_name;
									$data['event']['data']['current_tab'] = $cls->table_name;
									$data['event']['data']['report_id'] = $e['id'];
									$data['event']['data']['hide_max_records'] = 1;
									
									$data['action'] = $this->table_name;
									$data['todo'] = 'save_' . $action_to_perform;

									$this->class_settings['modal_title'] = 'Export Configurations';
									$this->class_settings['data'] = $data;
									$this->class_settings['html'] = array( $this->view_path . 'export-data-csv.php' );
									$html = $this->_get_html_view();
									return $this->_launch_popup( $html, '', ['prepare_new_record_form_new', 'set_function_click_event'] );
								}

							}else $error_msg = "<h4><b>Invalid Report</b></h4><p>Filters are not defined</p>";
						}else{
							$error_msg = "<h4><b>Invalid Report item</b></h4><p>Kindly select a valid report item</p>";
						}
					}
				break;
				case 'save_export_as_csv':
				case 'save_export_as_csv2':
					if( !$data ){
						$error_msg = "<h4><b>Invalid Report Settings</b></h4><p>Kindly Contact Technical Team</p>";
					}
					
					if( !$error_msg && (!( isset($data['report_id']) && $data['report_id'] )) ){
						$error_msg = "<h4><b>Invalid Report Settings</b></h4><p>Invalid Report ID. Kindly Contact Technical Team</p>";
					}
					
					if( !$error_msg && !(isset($data['tables']) && $data['tables']) ){
						$error_msg = "<h4><b>Invalid Report Settings</b></h4><p>Kindly Select at least one field to display in CSV</p>";
					}
					
					if( !$error_msg ){

						switch ( $action_to_perform ) {
							case 'save_export_as_csv':
								$this->class_settings['current_record_id'] = $data['report_id'];
								$e = $this->_get_record();
							break;
							default:
								$_GET['nwp_todo'] = 'export_as_csv';
								$e = [ 'id' => 'export_as_csv2' ];
								if( isset( $_POST[ 'data' ] ) ){
									$_das = json_decode( $_POST[ 'data' ], true );
									$_das['no_concat'] = 1;
									$_POST['data'] = json_encode($_das);
									unset( $_das );
								}
							break;
						}

						if( isset($e['id']) && $e['id'] ){						
							$data = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], true ) : array();
							$current_tab = isset( $data[ 'current_tab' ] ) ? $data[ 'current_tab' ] : 'mis';
							$selected_id = isset( $data[ 'selected_id' ] ) ? $data[ 'selected_id' ] : '';

							$not_cache_key = 'notification-dashboard';
							// $cache_date_key = get_new_id();
							$cache_date_key = md5( $this->class_settings['user_id'] . md5( json_encode($data) ));

							$pd[ 'get' ] = $_GET;
							$pd[ 'post' ] = $_POST;

							$sq = md5('search_query'.$_SESSION['key']);
							if( isset( $_SESSION[$sq] ) && $_SESSION[$sq] ){
								$pd[ 'cache' ] = $_SESSION[$sq];
							}

							if( defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE") ){
								$pd['active_filters'] = isset( $_SESSION["active_filters"] ) && $_SESSION["active_filters"] ? $_SESSION["active_filters"] : [];
							}

							$settings = array(
								'cache_key' => $not_cache_key,
							);
							$fw = get_cache_for_special_values( $settings );
							if( $fw && ! is_array( $fw ) )$fw = array();

							if( ! ( isset( $fw[ $current_tab ] ) && $fw[ $current_tab ] ) )$fw[ $current_tab ] = array();
							if( ! ( isset( $fw[ $current_tab ][ $selected_id ] ) && $fw[ $current_tab ][ $selected_id ] ) )$fw[ $current_tab ][ $selected_id ] = array();

							$fw[ $current_tab ][ $selected_id ] = array(
								'user' => $this->class_settings[ 'user_id' ],
								'time' => time(),
								'status' => 'active',
								'id' => $cache_date_key,
								'data' => $pd,
							);

							// Make permanent
							$sxt = array(
								'cache_key' => $not_cache_key,
								'cache_values' => $fw,
								'permanent' => 1,
								'directory_name' => $not_cache_key,
							);
							set_cache_for_special_values( $sxt );

							
							$sxt = array(
								'cache_key' => $cache_date_key,
								'cache_values' => $pd,
							);
							set_cache_for_special_values( $sxt );

							$_POST[ 'id' ] = $cache_date_key;
							$bg_settings = array(
								"action" => $this->plugin,
								"todo" => 'execute',
								"nwp_action" => $this->table_name,
								"nwp_todo" => 'save_generate_csv_bg',
								"user_id" => $this->class_settings["user_id"],
								"reference" => $cache_date_key,
								"use_session" => $_SESSION['key'],
								"html_replacement_selector" => $this->class_settings['html_replacement_selector']
							);

							$rbg = run_in_background( "bprocess", 0, $bg_settings );							
							// if( self::$isTesting ) exit;
							$mc = 1;
							$e_type = 'info';
							$error_msg = '<h4><strong>Export has been Queued</strong></h4>You shall be notified when export is completed';
							if( isset( $rbg['error_msg']) && $rbg['error_msg'] ){
								$error_msg = $rbg['error_msg'];
								$e_type = 'error';
								$mc = 0;
							}

							if( isset($rbg['executed']) && $rbg['executed'] ){
								// $mc = 0;
							}
							
							if( $mc ){
								$this->class_settings["callback"][ $this->table_name ][ $action_to_perform ] = array(
									'id' => $cache_date_key,
									'action' => '?nwp_action=' . $this->table_name . '&nwp_todo=check_bg_process&action='.$this->plugin . '&todo=execute&html_replacement_selector='.$handle,
								);
							}

						}else{
							$error_msg = "<h4><b>Invalid Report</b></h4><p>Report Item is currently unavailable. Kindly Contact Technical Team</p>";
						}
					}
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		protected function _get_select2(){
			
			$where = '';
			$limit = '';
			$search_term = '';

			$content = isset( $_GET[ 'include_content' ] ) && $_GET[ 'include_content' ]  ? 'data' : '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` regexp '".$search_term."' ) ";
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
				case $content:
				case "name":
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
				break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
				}
			}
			
			$select .= ", concat( `".$this->table_fields["name"]."`, ' - ', `serial_num` ) as 'text'";
			// $where = " AND `".$this->table_fields[ 'status' ]."` = '".$_GET[ $key ]."' ";
			
			$this->class_settings["overide_select"] = $select;
			// $this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			
			$data1 = $this->_get_all_customer_call_log();

			if( $content && isset( $data1[0][ 'id' ] ) && $data1[0][ 'id' ] ){
				foreach( $data1 as &$dx1 ){
					$dx1[ 'content' ] = rawurldecode( $dx1[ 'data' ] );
				}
			}

			return array( "items" => $data1, "do_not_reload_table" => 1 );
		}
		
		protected function _report_dashboard(){

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

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			
			$data = array();
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'report_dashboard':
				$this->class_settings[ 'where' ] = " AND `". $this->table_name ."`.`". $this->table_fields[ 'status' ] ."` = 'active' ";
				$data[ 'rdata' ] = $this->_get_records();
				$data[ 'add_url' ] = '&daction='.$this->plugin.'&dtodo=execute&dnwp_action='.$this->table_name.'&dnwp_todo=exec_report_url';
				if( isset($this->class_settings['return_data']) && $this->class_settings['return_data'] ){
					return $data;
				}
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
							// print_r( $html );exit;
				
			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( $this->view_path.'/'.$filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			if( $modal ){
				return $this->_launch_popup( $html, "#" . $handle, array( 'set_function_click_event', 'prepare_new_record_form_new' ) );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
			
			if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
				$return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
				$return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
			}
			return $return;
		}
		
		protected function _set_edit_types( $e = [] ){
			if( isset( $e[ 'type' ] ) && $e[ 'type' ] ){
				
				$types = get_report_bay_type();

				switch( $e[ 'type' ] ){
				case 'card':
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] = 'card:'. $types[ 'card' ] .';';
				break;
				case 'bar':
				case 'hbar':
				case 'pie':
				case 'line':
				case 'pline':
				case 'pline2':
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] = 'bar:'. $types[ 'bar' ] .';';
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] .= 'hbar:'. $types[ 'hbar' ] .';';
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] .= 'pie:'. $types[ 'pie' ] .';';
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] .= 'line:'. $types[ 'line' ] .';';
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] .= 'pline:'. $types[ 'pline' ] .';';
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] .= 'pline2:'. $types[ 'pline2' ] .';';
				break;
				case 'nested_card':
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] = 'nested_card:'. $types[ 'nested_card' ] .';';
				break;
				case 'key_value':
					$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'options' ] = 'key_value:'. $types[ 'key_value' ] .';';
				break;
				}

				$this->class_settings[ 'form_extra_options' ][ $this->table_fields[ 'type' ] ][ 'form_field_options' ] = '';
				
			}

			if( isset($e['report_key']) && $e['report_key'] ){
				switch ( $e['report_key'] ) {
					case 'indicator_dashboard':
						$this->class_settings['form_extra_options'][ $this->table_fields['type'] ]['form_field_options'] = array( 'report_value' => ' Report Value');
						$this->class_settings['add_empty_select_option'] = 1;
						$this->class_settings['form_extra_options'][ $this->table_fields['type'] ]['class'] = 'onchange2';
						$this->class_settings['form_extra_options'][ $this->table_fields['type'] ]['attributes'] = ' nwp-hide-class="type" ';
						$this->class_settings['hidden_records'][ $this->table_fields['group'] ] = 1;
						$this->class_settings['custom_fields'] = array(
							'unit_of_measure' => array(
								'field_label' => 'Unit of Measure',
								'required_field' => 'yes',
								'form_field' => 'select',
								'form_field_options' => 'get_unit_of_measure',
								'data' => array( 'form_field_options_source' => 2 ),
								'class' => '',
								'attributes' => '',
								'display_position' => 'display-in-table-row',
								'serial_number' => 130.2,
							),
							'baseline' => array(
								'field_label' => 'Baseline',
								'required_field' => 'yes',
								'form_field' => 'text',
								'class' => '',
								'attributes' => '',
								'display_position' => 'display-in-table-row',
								'serial_number' => 140.2,
							),
							'end_target' => array(
								'field_label' => 'End Target',
								'required_field' => 'yes',
								'form_field' => 'text',
								'class' => '',
								'attributes' => '',
								'display_position' => 'display-in-table-row',
								'serial_number' => 150.2,
							)
						);

						$this->class_settings['custom_fields']['actual_value'] = array(
							'field_label' => 'Actual Value',
							'required_field' => '',
							'form_field' => 'text',
							'class' => ' type type-report_value ',
							'attributes' => '',
							'display_position' => 'display-in-table-row',
							'serial_number' => 160.2,
						);

						if( isset($e['custom_data']) && $e['custom_data'] ){
							$e['custom_data'] = json_decode( $e['custom_data'], true );
							foreach (array_keys( $this->class_settings['custom_fields'] ) as $key) {
								if( isset($e['custom_data'][ $key ]) ){
									$this->class_settings['form_values_important'][ $key ] = $e['custom_data'][ $key ];
									// $this->class_settings['custom_fields']['value'] = $e['custom_data'][ $key ];
								}
							}
						}
						unset( $this->class_settings['hidden_records'][ $this->table_fields['group'] ] );
					break;
				}
			}
		}

		private function _sanitize_data_for_filter(array $saved_query, array $session_data, array $dashboard_filter_data){
			$rd = [];
			$use_session = false;
			$use_dashboard = false;

			if( isset($dashboard_filter_data['cart_items']) && $dashboard_filter_data['cart_items'] ){
				$use_dashboard = true;
				foreach( $dashboard_filter_data['cart_items'] as $dcv ){
					if(!( isset( $dcv[ 'condition' ] ) && $dcv[ 'condition' ] )){
						$dcv[ 'condition' ] = 'AND';
					}
					$rd['cart_items'][] = $dcv;
				}
			}
			
			if( /*!$use_dashboard &&*/ $session_data && isset($session_data['cart_items']) && $session_data['cart_items'] ){
				$use_session = true;
				foreach( $session_data['cart_items'] as $scv ){
					if(!( isset( $scv[ 'condition' ] ) && $scv[ 'condition' ] )){
						$scv[ 'condition' ] = 'AND';
					}
					$rd['cart_items'][] = $scv;
				}
			}

			if( !$use_dashboard && isset($session_data['report_limit']) && $session_data['report_limit'] ){
				$rd['report_limit'] = $session_data['report_limit'];
				$use_session = true;
			}
			
			if(!$use_session && $saved_query && isset($saved_query['cart_items']) && $saved_query['cart_items']){
				foreach($saved_query['cart_items'] as $cv){				
					if(!( isset( $cv[ 'condition' ] ) && $cv[ 'condition' ] )){
						$cv[ 'condition' ] = 'AND';
					}
					$rd['cart_items'][] = $cv;
				}
			}

			if( !$use_session && isset($saved_query['report_limit']) && $saved_query['report_limit'] ){
				$rd['report_limit'] = $saved_query['report_limit'];
			}

			if( isset($rd['cart_items']) && $rd['cart_items'] ){

				$rd['configurations'] = isset($rd['configurations']) && $rd['configurations'] ? $rd['configurations'] : (isset($saved_query['configurations']) && $saved_query['configurations'] ? $saved_query['configurations'] : [] );
				$rd['accept_empty'] = isset( $rd['accept_empty'] ) && $rd['accept_empty'] ? $rd['accept_empty'] : ( isset( $saved_query['accept_empty'] ) && $saved_query['accept_empty'] ? $saved_query['configurations'] : 1 );
				$rd['report_limit'] = isset( $rd['report_limit'] ) && $rd['report_limit'] ? $rd['report_limit'] : ( isset( $saved_query['report_limit'] ) && $saved_query['report_limit'] ? $saved_query['report_limit'] : 0 );
				$rd['cart_items'] = array_unique( $rd['cart_items'], SORT_REGULAR );
			}	

			return $rd;
		}

		protected function _custom_capture_form_version_2(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = 'validate-bill-and-finalize-discharge';
			
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
			
			$add2return = array();
			$data = array();
			$tabs = array(
				// 'select-fields' => 'Select Fields',
				'create-query' => 'Build Search Query',
				'create-mine' => 'Configure',
				'download-csv' => 'Fetch Data',
			);
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'save_modify_card':
				// @steve Error on Changing Card Report Type
				$mod = isset($_GET['_parent']) && $_GET['_parent'] ? $_GET['_parent'] : '';
				$r = $this->_save_app_changes();
				if( isset($r['saved_record_id']) && $r['saved_record_id'] ){
					if( $r['saved_record_id'] != $mod ){
						$r['data']['parent_container'] = $mod;
					}
					$this->class_settings['data']['rdata'] = $r['record'];
					$this->class_settings['html'] = array( $this->view_path . 'homepage/prepare_single_card.php' );
					$r['html_replacement_two'] = $this->_get_html_view();
				}

				$r[ 'javascript_functions' ] = [ 'nwReportsBay2.singleModify' ];

				return $r;
			break;
			case 'modify_card':
				if( $id ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$e = $this->_get_record();

					if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){

						$hr = 'hidden_records';
						// @steve create duplicate on modify personal record
						if( !( isset($e['parent_report']) && $e['parent_report'] && isset($e['staff_responsible']) && $e['staff_responsible'] ) ){

							$this->class_settings[ 'overide_select' ] = " id ";
							$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'parent_report' ] ."` = '". $e[ 'id' ] ."' AND `". $this->table_fields[ 'staff_responsible' ] ."` = '". $this->class_settings[ 'user_id' ] ."' ";
							$ee = $this->_get_records();

							if( isset( $ee[0][ 'id' ] ) && $ee[0][ 'id' ] ){
								$_POST[ 'id' ] = $ee[0][ 'id' ];
								$_POST["mod"] = 'edit-'.md5( $this->table_name );
							}else{
								$_POST[ 'id' ] = '';
								$hr = 'hidden_records_css';

								foreach( $e as $ek => $ev ){
									if( isset( $this->table_fields[ $ek ] ) ){
										$this->class_settings[ 'form_values_important' ][ $this->table_fields[ $ek ] ] = $ev;
									}
								}
								$this->class_settings[ 'form_values_important' ][ $this->table_fields[ 'staff_responsible' ] ] = $this->class_settings[ 'user_id' ];
								$this->class_settings[ 'form_values_important' ][ $this->table_fields[ 'parent_report' ] ] = $e[ 'id' ];

								$this->class_settings["skip_link"] = 1;
							}
						}else{
							$_POST["mod"] = 'edit-'.md5( $this->table_name );
						}

						$this->_set_edit_types( $e );

						$this->class_settings[ 'modal_title' ] = 'Modify '.$e[ 'name' ];
						$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_modify_card&_parent='.$e['id'];

						$this->class_settings[ $hr ][ $this->table_fields[ 'date' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'group' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'category' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'staff_responsible' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'parent_report' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'status' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'endpoint' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'report_key' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'data' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'custom_data' ] ] = 1;
						$this->class_settings[ $hr ][ $this->table_fields[ 'saved_query' ] ] = 1;

						$this->class_settings["override_defaults"] = 1;

						return $this->_new_popup_form();

					}else{
						$error = '<h4><strong>Invalid Report Reference</strong></h4>';
					}
				}else{
					$error = '<h4><strong>Undefined Report Reference</strong></h4>';
				}
			break;
			case 'exec_filter_card_all':
				$dd = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], 1 ) : [];
				$report = '';

				$return = [];
				
				if( isset( $dd[ 'cart_items' ] ) && ! empty( $dd[ 'cart_items' ] ) ){
					$report = isset( $dd[ 'configurations' ][ 'report_key' ] ) ? $dd[ 'configurations' ][ 'report_key' ] : '';

					$cache_key = "DashboardAllFilter-" . $this->class_settings["user_id"] . "-search";
					if( $report ){
						$cache_key = md5( $report . $this->class_settings[ 'user_id' ] . '-search' );
					}
					
					unset( $dd['configurations'] );

					if (defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") &&  constant("USE_SESSION_FOR_REPORT_FILTER_CACHE")) {
						$_SESSION["active_filters"][ $cache_key ] = $dd;
						if( isset( $_SESSION["active_filters"][ $report ] ) ){
							unset( $_SESSION["active_filters"][ $report ] );
						}

					}else{
						$settings = array(
							'cache_key' => $cache_key,
							'cache_values' => $dd,
							'directory_name' => 'search',
							'permanent' => true,
						);
						set_cache_for_special_values( $settings );						
					}
					
					$return[ 'data' ] = $dd[ 'cart_items' ];
				}

				$return[ 'report' ] = $report;
				$return[ 'close_modal' ] = 1;
				$return[ 'status' ] = 'new-status';
				$return[ 'javascript_functions' ] = [ 'nwReportsBay2.allFilter' ];

				return $return;
			break;
			case 'exec_filter_card':
				$dd = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], 1 ) : [];
				$save_options = isset( $_POST[ 'save_options' ] ) ? $_POST[ 'save_options' ] : '';
				$use_limit = isset( $_POST['use_limit'] ) && $_POST['use_limit'] ? $_POST['use_limit'] : 0;
				$report_limit = isset( $_POST['query_limit'] ) && $_POST['query_limit'] && $use_limit ? $_POST['query_limit'] : 0;
				
				if( isset( $dd[ 'configurations' ][ 'report_id' ] ) && $dd[ 'configurations' ][ 'report_id' ] ){

					$this->class_settings[ 'current_record_id' ] = $dd[ 'configurations' ][ 'report_id' ];
					$e = $this->_get_record();

					if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
						$initial_id = $e[ 'id' ];
						$ed = $e[ 'data' ] ? json_decode( $e[ 'data' ], true ) : [];

						$cnt = 1;
						if( $ed && isset($ed[ 'query' ]) && $ed[ 'query' ] && isset($ed[ 'query' ][ 'query_options' ]) && $ed[ 'query' ][ 'query_options' ] ){
							$cnt = count( $ed[ 'query' ][ 'query_options' ] ) + 1;
						}

						$dd['report_limit'] = $report_limit;
						if( $report_limit && $ed && isset($ed[ 'query' ]) && $ed[ 'query' ] ){
							$ed[ 'query' ]['limit'] =  $report_limit;
						}

						if( isset( $dd[ 'cart_items' ] ) && ! empty( $dd[ 'cart_items' ] ) ){
							foreach( $dd[ 'cart_items' ] as $cv ){
								// @steve 
								// I changed a very weird condition, previous condition ! empty( empty( $ed[ 'query' ][ 'query_options' ] ) ), fails on some reports
								if( !empty( $ed[ 'query' ][ 'query_options' ] ) && !( isset( $cv[ 'condition' ] ) && $cv[ 'condition' ] ) ){
									$cv[ 'condition' ] = 'AND';
								}
								$ed[ 'query' ][ 'query_options' ][ strval( $cnt ) ] = $cv;
								$cnt++;
							}
						}

						unset( $dd['fields'] );
						
						switch( $save_options ){
							case 'save':
								if( isset( $e[ 'parent_report' ] ) && $e[ 'parent_report' ] ){
									$ee[0][ 'id' ] = $e[ 'id' ];
								}else{
									$this->class_settings[ 'overide_select' ] = " id ";
									$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'parent_report' ] ."` = '". $e[ 'id' ] ."' AND `". $this->table_fields[ 'staff_responsible' ] ."` = '". $this->class_settings[ 'user_id' ] ."' ";
									$ee = $this->_get_records();
								}

								$xp = $_POST;

								$hr = 'hidden_records';
								$update_fields = [];

								if( isset( $ee[0][ 'id' ] ) && $ee[0][ 'id' ] ){
									$_POST[ 'id' ] = $ee[0][ 'id' ];
								}else{
									$_POST[ 'id' ] = '';
									$update_fields = $e;
									$update_fields[ 'staff_responsible' ] = $this->class_settings[ 'user_id' ];
									$update_fields[ 'parent_report' ] = $e[ 'id' ];
									$update_fields[ 'data' ] = $e['data'];
								}

								$update_fields[ 'saved_query' ] = json_encode( $dd );
								$update_fields[ 'date' ] = date('Y-m-d');
								unset( $update_fields[ 'id' ] );
								unset( $update_fields[ 'created_by' ] );
								unset( $update_fields[ 'creation_date' ] );
								unset( $update_fields[ 'modification_date' ] );
								unset( $update_fields[ 'modified_by' ] );

								$this->class_settings[ 'update_fields' ] = $update_fields;
								$rc = $this->_update_table_field();
								
								if( isset( $ee[0][ 'id' ] ) && $ee[0][ 'id' ] ){
									$this->class_settings[ 'current_record_id' ] = 'pass_condition';
									$this->class_settings[ 'do_not_check_cache' ] = 1;
									$this->class_settings[ 'cache_where' ] = " AND id = '". $ee[0][ 'id' ] ."' ";
									$this->_get_record();
								}

								$_POST = $xp;

							break;
						}

						if( isset( $rc[ 'saved_record_id' ] ) && $rc[ 'saved_record_id' ] ){
							$e['id'] = $rc['saved_record_id'];
							$dd[ 'configurations' ][ 'report_id' ] = $rc['saved_record_id'];
						}

						$cache_key = md5( $e['id'] . "-" . $this->class_settings["user_id"] . "-search" );
						if (defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") &&  constant("USE_SESSION_FOR_REPORT_FILTER_CACHE")) {
							$_SESSION["active_filters"][ $e['report_key'] ][ $cache_key ] = $dd;
						}else{
							$settings = array(
								'cache_key' => $cache_key,
								'cache_values' => $dd,
								'directory_name' => 'search',
								'permanent' => true,
							);
							set_cache_for_special_values( $settings );
						}

						$_POST = [ 'data' => $ed, 'action' => 'execute' ];
						$this->class_settings[ 'action_to_perform' ] = 'exec_report_url';

						$return = $this->reports_bay();
						$return[ 'close_modal' ] = 1;
						$return[ 'status' ] = 'new-status';
						$return[ 'rtype' ] = $e[ 'type' ];
						$return[ 'report' ] = $e;
						$return[ 'rID' ] = $e[ 'id' ];
						$return[ 'javascript_functions' ] = [ 'nwReportsBay2.singleFilter' ];
						$return[ 'initial_id' ] = $initial_id;

						if( isset( $cache_key ) && $cache_key ){
							$return[ 'cache_key' ] = $cache_key;
						}

						if ($report_limit) {
							$return['report']['more_data']['query_limit'] = $report_limit;
						}

						return $return;

					}else{
						$error = '<h4><strong>Invalid Report Reference</strong></h4>';
					}

				}else{
					$error = '<h4><strong>Undefined Report Reference</strong></h4>';
				}
			break;
			case 'filter_card_all':
				$tb = isset( $_GET[ 'table' ] ) ? $_GET[ 'table' ] : '';
				$pl = isset( $_GET[ 'plugin' ] ) ? $_GET[ 'plugin' ] : '';
				$ckey = isset( $_GET[ 'ckey' ] ) ? $_GET[ 'ckey' ] : '';
				$report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : '';

				if( $tb ){

					$rones = '';
					$rone = '';
					$mjs = array( 'prepare_new_record_form_new', 'nwSearch.init' );
					if( $pl ){
						$exp = [
							'type' => 'plugin',
							'value' => $tb,
							'key' => $pl,
						];
					}else{
						$exp = [
							'type' => 'table',
							'value' => $tb,
							'key' => $tb,
						];
					}

					$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'configurations' ] = array( 
						'hide_preview_result' => 1,
						'report_id' => '',
						'report_key' => $report,
					);

					if( $report ){
						$this->class_settings[ 'overide_cache_key' ] = md5( $report . $this->class_settings[ 'user_id' ] . '-search' );
					}

					$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'accept_empty' ] = 1;
					$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'post' ] = $exp;

					if( isset( $this->class_settings[ 'query_options' ] ) && $this->class_settings[ 'query_options' ] ){
						$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'cart_items' ] = $this->class_settings[ 'query_options' ];
					}
					if( isset( $this->class_settings[ 'query_select' ] ) && $this->class_settings[ 'query_select' ] ){
						$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'select' ] = $this->class_settings[ 'query_select' ];
					}

					$this->class_settings[ 'search_options' ][ 'action' ] = $this->plugin;
					$this->class_settings[ 'search_options' ][ 'todo' ] = 'execute&nwp_action='. $this->table_name .'&nwp_todo=exec_'.$action_to_perform;

					$this->class_settings[ 'search_options' ][ 'add_system_fields' ] = 1;
					$this->class_settings[ 'search_options' ][ 'tabs' ] = array( 'create-query' => 'Build Search Query');

					$this->class_settings[ 'data' ][ 'return_html' ] = 1;

					if( $ckey ){
						$this->class_settings[ 'cache_key' ] = $ckey;
					}

					$_GET[ 'table' ] = $tb;
					$_GET[ 'plugin' ] = ( isset( $pl ) ? $pl : '' );

					$return =  $this->_search_window();

					if( ! is_array( $return ) ){
						$html = $return;
						$modal = 1;
						$this->class_settings["modal_dialog_class"] = ' modal-xl ';
					}else{
						$error = '<h4>Unable to Get Search View</h4>';
					}
				}else{
					$error = '<h4><strong>Undefined Report Table</strong></h4>';
				}

			break;
			case 'filter_card':
				$cache_key = isset( $_GET[ 'cache_key' ] ) ? $_GET[ 'cache_key' ] : '';
				if( $id ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$e = $this->_get_record();

					if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
						$dd = $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];
						$sd = isset( $e['saved_query'] ) && $e['saved_query'] ? json_decode( $e['saved_query'], true ) : [];
						$asd = [];
						$fsd = [];
						
						if (defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE")) {
							$session_cache_key = isset( $cache_key ) && $cache_key ? $cache_key : md5( $e['report_key'] . $this->class_settings[ 'user_id' ] . '-search' );
							$asd = isset( $_SESSION["active_filters"][ $e['report_key'] ][ $session_cache_key ] ) && $_SESSION["active_filters"][ $e['report_key'] ][ $session_cache_key ] ? $_SESSION["active_filters"][ $e['report_key'] ][ $session_cache_key ] : [];
							$dashboard_cache_key = md5( $e['report_key'] . $this->class_settings[ 'user_id' ] . '-search' );
							$fsd = isset( $_SESSION["active_filters"][ $dashboard_cache_key ] ) && $_SESSION["active_filters"][ $dashboard_cache_key ] ? $_SESSION["active_filters"][ $dashboard_cache_key ] : [];
						}

						$bd = $this->_sanitize_data_for_filter( $sd, $asd, $fsd );
						
						if ( $bd ) {							
							$bd['configurations']['report_id'] = $e['id'];
							$bd['configurations']['hide_preview_result'] = 1;
							$this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ] = $bd;
						}

						if( isset( $dd[ 'table' ] ) && $dd[ 'table' ] ){
						
							$rones = '';
							$rone = '';
							$mjs = array( 'prepare_new_record_form_new', 'nwSearch.init' );
							if( isset( $dd[ 'plugin' ] ) && $dd[ 'plugin' ] ){
								$exp = [
									'type' => 'plugin',
									'value' => $dd[ 'table' ],
									'key' => $dd[ 'plugin' ],
								];
							}else{
								$exp = [
									'type' => 'table',
									'value' => $dd[ 'table' ],
									'key' => $dd[ 'table' ],
								];
							}

							if( $cache_key ){
								$this->class_settings[ 'overide_cache_key' ] = $cache_key;
							}

							$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'configurations' ] = array(
								'hide_preview_result' => 1,
								'report_id' => $id,
							);

							switch ( $e['type'] ) {
								case 'pie':
								case 'card':
								case 'nested_card':
								case 'key_value':
								case 'report_value':
								break;
								default:
									$this->class_settings[ 'search_options' ]['show_display_limit'] = 1;
								break;
							}

							$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'accept_empty' ] = 1;
							$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'post' ] = $exp;

							if( isset( $this->class_settings[ 'query_options' ] ) && $this->class_settings[ 'query_options' ] ){
								$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'cart_items' ] = $this->class_settings[ 'query_options' ];
							}
							if( isset( $this->class_settings[ 'query_select' ] ) && $this->class_settings[ 'query_select' ] ){
								$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'select' ] = $this->class_settings[ 'query_select' ];
							}
							if( isset( $dd['query']['limit'] ) && $dd['query']['limit'] ){
								$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'report_limit' ] = intval( $dd['query']['limit'] );
							}

							if( isset( $bd['report_limit'] ) && $bd['report_limit'] ){
								$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'report_limit' ] = intval( $bd['report_limit'] );
							}

							$this->class_settings[ 'search_options' ][ 'action' ] = $this->plugin;
							$this->class_settings[ 'search_options' ][ 'todo' ] = 'execute&nwp_action='. $this->table_name .'&nwp_todo=exec_'.$action_to_perform;

							$this->class_settings[ 'search_options' ][ 'add_system_fields' ] = 1;
							$this->class_settings[ 'search_options' ][ 'tabs' ] = array( 'create-query' => 'Build Search Query' );

							$this->class_settings[ 'data' ][ 'return_html' ] = 1;
							$this->class_settings[ 'search_options' ][ 'show_save_options' ] = [ 'save' => 'Save', 'exec' => 'Execute Only' ];

							$_GET[ 'table' ] = $dd[ 'table' ];
							$_GET[ 'plugin' ] = ( isset( $dd[ 'plugin' ] ) ? $dd[ 'plugin' ] : '' );

							$return =  $this->_search_window();

							if( ! is_array( $return ) ){
								$html = $return;
								$modal = 1;
								$this->class_settings["modal_dialog_class"] = ' modal-xl ';
							}else{
								$error = '<h4>Unable to Get Search View</h4>';
							}

						}else{
							$error = '<h4><strong>Invalid Report Data</strong></h4>';
						}
					}else{
						$error = '<h4><strong>Invalid Report Reference</strong></h4>';
					}
				}else{
					$error = '<h4><strong>Undefined Report Reference</strong></h4>';
				}

			break;
			case 'edit_query':
				if( $id ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$d = $this->_get_record();
					$e = isset( $d[ 'data' ] ) ? json_decode( $d[ 'data' ], 1 ) : [];

					if( isset( $e[ 'table' ] ) && $e[ 'table' ] ){
						$sel = [];
						$pl = isset( $e[ 'plugin' ] ) ? $e[ 'plugin' ] : '';

						$gi = get_new_id();
						$sn = 0;
						if( isset( $e[ 'tables' ][ $e[ 'table' ] ] ) && $e[ 'tables' ][ $e[ 'table' ] ] ){
							foreach( $e[ 'tables' ][ $e[ 'table' ] ] as $tk => $tv ){
								$gid = $gi . $sn++;
								$sel[ $gid ] = [
									"id" => $gid,
									"table_name" => $e[ 'table' ],
									"field" => isset( $tv[ 'field' ] ) ? $tv[ 'field' ] : $tk,
									"aggregation_text" => ( isset( $tv[ 'aggregation' ] ) ? $tv[ 'aggregation' ] : '' ),
									"aggregation" => ( isset( $tv[ 'aggregation' ] ) ? $tv[ 'aggregation' ] : '' ),
									"alias" => ( isset( $tv[ 'alias' ] ) ? $tv[ 'alias' ] : '' ),
								];
							}
						}
						if( isset( $e[ 'use_groups' ][ $e[ 'table' ] ] ) && $e[ 'use_groups' ][ $e[ 'table' ] ] ){
							foreach( $e[ 'use_groups' ][ $e[ 'table' ] ] as $tk => $tv ){
								$gid = $gi . $sn++;
								$sel[ $gid ] = [
									"id" => $gid,
									"table_name" => $e[ 'table' ],
									"field" => isset( $tv[ 'field' ] ) ? $tv[ 'field' ] : $tk,
									"aggregation_text" => $tv,
									"aggregation" => $tv,
									"alias" => '',
								];
							}
						}
						if( isset( $e[ 'use_sorts' ][ $e[ 'table' ] ] ) && $e[ 'use_sorts' ][ $e[ 'table' ] ] ){
							foreach( $e[ 'use_sorts' ][ $e[ 'table' ] ] as $tk => $tv ){
								$gid = $gi . $sn++;
								$sel[ $gid ] = [
									"id" => $gid,
									"table_name" => $e[ 'table' ],
									"field" => isset( $tv[ 'field' ] ) ? $tv[ 'field' ] : $tk,
									"aggregation_text" => $tv,
									"aggregation" => $tv,
									"alias" => '',
								];
							}
						}
						if( isset( $e[ 'query' ][ 'query_options' ] ) && $e[ 'query' ][ 'query_options' ] ){
							foreach( $e[ 'query' ][ 'query_options' ] as &$tiv ){
								foreach( $tiv[ 'data' ] as &$tiv2 ){
									$tiv2[ 'condition_text' ] = $tiv2[ 'condition' ];
								}
							}
						}

						if (isset( $d['type'] ) && $d['type']) {
							switch ( $d['type'] ) {
								case 'pie':
								case 'card':
								case 'nested_card':
								case 'key_value':
								case 'report_value':
								break;
								default:
									switch ( $d['type'] ) {
										case 'bar':
										case 'hbar':
											$this->class_settings['search_options']['show_chart_options'] = 1;
											if( isset( $e['report_display_options'] ) && $e['report_display_options'] ){
												$this->class_settings['search_options']['report_display_options'] = $e['report_display_options'];
												$this->class_settings['search_options']['cart_data']['report_display_options'] = $e['report_display_options'];
											}
										break;
									}
									$this->class_settings[ 'search_options' ]['show_display_limit'] = 1;
								break;
							}
						}

						$this->class_settings[ 'query_options' ] = isset( $e[ 'query' ][ 'query_options' ] ) ? $e[ 'query' ][ 'query_options' ] : [];
						if( isset( $e['query']['limit'] ) && $e['query']['limit'] ){
							$this->class_settings['query_limit'] = $e['query']['limit']; 
						}
						$this->class_settings[ 'query_select' ] = $sel;
						$this->class_settings[ 'report_id' ] = $id;

						$this->class_settings[ 'action_to_perform' ] = 'geenrate_report_tb';
						$_POST["data_source"] = 'type=plugin:::key='. $pl .':::value='. $e[ 'table' ];

						return $this->reports_bay();

					}else{
						$error = '<h4><strong>Invalid Reference Data</strong></h4>';
					}
				}else{
					$error = '<h4><strong>Undefined Reference ID</strong></h4>';
				}
			break;
			case 'view_result':
				if( $id ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$e = $this->_get_record();

					if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
						$dd = isset( $e[ 'data' ] ) && $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : array();

						if( isset( $dd[ 'tables' ] ) && $dd[ 'tables' ] ){
							$this->class_settings[ 'action_to_perform' ] = 'exec_report_url';
							$_POST[ 'action' ] = 'execute';
							if( isset($this->class_settings['modQueryOptions']['addQuery']) && $this->class_settings['modQueryOptions']['addQuery'] ){
								$aQ = $this->class_settings['modQueryOptions']['addQuery'];
								if( isset($dd['table']) && $dd['table'] ){
									$cnt = 1;
									if( isset($dd['query']) && $dd['query'] && isset($dd['query']['query_options']) && $dd['query']['query_options'] ){
										$cnt = count( $dd['query']['query_options'] ) + 1;
									}
									foreach ($aQ as $aQKey => &$aQVal) {
										if( isset($aQVal['data']) && $aQVal['data'] ){
											foreach ($aQVal['data'] as &$aQVal2) {
												$aQVal2['sub_query'] = $cnt;
												$aQVal2['table_name'] = $dd['table'];
											}
											$dd['query']['query_options'][ $cnt ] = $aQVal;
											$cnt++;
										}
									}					
								}
							}
							$_POST[ 'data' ] = json_encode( $dd );
							$r = $this->reports_bay();
							if( isset($this->class_settings['return_r_data']) && $this->class_settings['return_r_data'] ){
								return $r;
							}
							if( isset( $r[ 'error' ] ) && $r[ 'error' ] ){
								$error = '<h4><strong>'. $r[ 'error' ] .'</strong></h4>';
							}else{
								$modal = 1;
								$data[ 'data' ] = $r;
							}
						}else{
							$error = '<h4><strong>Unsupported Data</strong></h4>Verify report type or generate a new query';
						}
					}else{
						$error = '<h4><strong>Invalid Reference ID</strong></h4>';
					}
				}else{
					$error = '<h4><strong>Undefined Reference ID</strong></h4>';
				}
			break;
			case 'geenrate_report':
				$filename = 'generate-report';

				$data[ 'plugin' ] = $this->plugin;
				$data[ 'table' ] = $this->table_name;
				$data[ 'todo' ] = $action_to_perform.'_tb';

				$optx = array( "group_plugin" => 1 );
				$optx['selected_class_only'] = [
					'cNwp_reports' => 1,
					'cNwp_grm' => 1,
					'cNwp_device_management' => 1
				];

				$optx['excluded_plugin_classes'] = [
					'frequent_changes' => 1,
					'non_frequent_changes' => 1,
					'frequent_changes_sr' => 1,
					'non_frequent_changes_sr' => 1,

					'reports_bay' => 1,
					'report_config' => 1,
					'reports_ui' => 1,

				];


				$data[ 'db_tables' ] = get_database_tables( $optx );
			break;
			case 'exec_report_url':
				$action = isset( $_POST[ 'action' ] ) ? $_POST[ 'action' ] : '';

				switch( $action ){
				case 'execute':
					$m = new cMyexcel;
					$m->class_settings = $this->class_settings;

					$_GET[ 'action' ] = $m->table_name;
					$_GET[ 'todo' ] = 'save_generate_json_formatted';

					$dx = array();
					if( is_array( $_POST[ 'data' ] ) ){
						$dx = $_POST[ 'data' ];
					}else{
						$dx = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], 1 ) : array();
					}

					$dx[ 'use_field_keys' ] = 1;
					$dx[ 'use_keys_in_result' ] = 1;
					$dx[ 'no_page_header' ] = 1;
					$m->class_settings[ 'no_header' ] = 1;
					$_POST[ 'data' ] = json_encode( $dx );

					$m->class_settings[ 'action_to_perform' ] = $_GET[ 'todo' ];
					$m->class_settings[ 'ignore_cache_conditions' ] = 1;
					$m->class_settings[ 'db_mode' ] = 'elasticsearch';

					$return = $m->myexcel();

					if( isset( $return[ 'json' ][ 'msg' ] ) && $return[ 'json' ][ 'msg' ] ){
						return array(
							'type' => 0,
							'msg' => $return[ 'json' ][ 'msg' ],
							'data' => [],
						);
					}elseif( isset( $return[ 'json' ][ 'data' ] ) && $return[ 'json' ][ 'data' ] ){
						return array(
							'type' => 1,
							'msg' => '',
							'data' => $return[ 'json' ][ 'data' ],
						);
					}elseif( isset( $return[ 'typ' ] ) && $return[ 'msg' ] ){
						return array(
							'type' => 0,
							'msg' => $return[ 'msg' ],
							'data' => [],
						);
					}elseif( isset( $return[ 'error' ] ) ){
						return array(
							'type' => 0,
							'msg' => $return[ 'error' ],
							'data' => [],
						);
					}else{
						return array(
							'type' => 0,
							'msg' => 'No Data Found',
							'data' => [],
						);
					}
				break;
				case 'fields':
					$formFields = [
					    [
					        'type' => 'text',
					        'label' => 'Full Name',
					        'name' => 'fullName',
					        'required' => true,
					    ],
					    [
					        'type' => 'email',
					        'label' => 'Email',
					        'name' => 'email',
					        'required' => true,
					    ],
					    [
					        'type' => 'select',
					        'label' => 'Country',
					        'name' => 'country',
					        'required' => true,
					        'options' => ['USA', 'Canada', 'UK', 'Australia'],
					    ],
					    [
					        'type' => 'radio',
					        'label' => 'Gender',
					        'name' => 'gender',
					        'required' => true,
					        'options' => ['Male', 'Female', 'Other'],
					    ],
					    [
					        'type' => 'checkbox',
					        'label' => 'Hobbies',
					        'name' => 'hobbies',
					        'options' => ['Reading', 'Gaming', 'Cooking', 'Traveling'],
					    ],
					];

					function getRandomFormFields($formFields, $numSamples) {
					    if ($numSamples >= count($formFields)) {
					        return $formFields;
					    }

					    $randomKeys = array_rand($formFields, $numSamples);
					    if (!is_array($randomKeys)) {
					        $randomKeys = [$randomKeys];
					    }

					    $randomFormFields = [];
					    foreach ($randomKeys as $key) {
					        $randomFormFields[] = $formFields[$key];
					    }

					    return $randomFormFields;
					}

					$numSamples = rand(1, count($formFields));
					$randomSample = getRandomFormFields($formFields, $numSamples);
					return array(
						'status' => "new-status",
						'data' => $randomSample
					);
				break;
				case 'save':
					$xd = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], 1 ) : [];

					$this->class_settings[ 'update_fields' ] = array(
						'date' => date( 'Y-m-d' ),
						'name' => isset( $_POST[ 'name' ] ) && $_POST[ 'name' ] ? $_POST[ 'name' ] : 'Report '.date( 'Y-m-d H:i' ),
						'title' => isset( $_POST[ 'title' ] ) && $_POST[ 'title' ] ? $_POST[ 'title' ] : '',
						'report_key' => isset( $_POST[ 'report_key' ] ) && $_POST[ 'report_key' ] ? $_POST[ 'report_key' ] : '',
						'group' => isset( $_POST[ 'group' ] ) && $_POST[ 'group' ] ? $_POST[ 'group' ] : '',
						'type' => isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] ? $_POST[ 'type' ] : 'auto',
						'endpoint' => isset( $_POST[ 'url' ] ) && $_POST[ 'url' ] ? $_POST[ 'url' ] : '',
						'status' => 'active',
						'data' => isset( $_POST[ 'data' ] ) && $_POST[ 'data' ] ? $_POST[ 'data' ] : '',
					);

					$pp = $_POST;
					$_POST = array( 'id' => ( isset( $xd[ 'configurations' ][ 'report_id' ] ) ? $xd[ 'configurations' ][ 'report_id' ] : '' ) );
					$r = $this->_update_table_field();
					if( isset( $r['html'] ) && $r['html'] ){
						return $r['html'];
					}
					$_POST = $pp;
					return $r;

				break;
				}
			break;
			case 'get_export_data':
				$data = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], 1 ) : array();
				$tb = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
				$download_csv = isset( $_POST[ 'download_csv' ] ) ? $_POST[ 'download_csv' ] : '';

				$data[ 'no_default_group' ] = 1;

				if( $download_csv ){

					$m = new cMyexcel;
					$m->class_settings = $this->class_settings;
					$m->class_settings[ 'ignore_cache_conditions' ] = 1;

					$_GET[ 'action' ] = $m->table_name;
					$_GET[ 'todo' ] = 'save_generate_csv3';

					$_POST[ 'data' ] = json_encode( $data );

					$m->class_settings[ 'action_to_perform' ] = $_GET[ 'todo' ];

					return $m->myexcel();

				}elseif( isset( $data[ 'tables' ] ) && $data[ 'tables' ] ){

					$this->class_settings[ 'data' ][ 'action' ] = 'action='. $this->plugin .'&todo=execute&nwp_action='. $this->table_name .'&nwp_todo=get_ffields';
					$this->class_settings[ 'data' ][ 'post' ] = $data;

					$this->class_settings[ 'data' ][ 'post' ] = $data;

					if( isset( $data[ 'configurations' ][ 'report_id' ] ) && $data[ 'configurations' ][ 'report_id' ] ){
						$this->class_settings[ 'current_record_id' ] = $data[ 'configurations' ][ 'report_id' ];
						$this->class_settings[ 'data' ][ 'form_data' ] = $this->_get_record();
					}

					$req_type = 'download_med_rec';

					$cget = array();
					if( isset( $data[ 'configurations' ] ) && $data[ 'configurations' ] ){
						foreach( $data[ 'configurations' ] as $cv => $ck ){
							$cget[ $cv ] = $ck;
							switch( $cv ){
							case 'exclude_fields':
								if( isset( $ck[ $tb ] ) && $ck[ $tb ] ){
									$cget[ $cv ] = implode( ':::', $ck[ $tb ] );
								}
							break;
							}
						}
					}

					$access = get_report_hash_options( array( 'rkey' => $req_type ) )[ 'keys' ];

					$nr = new cNwp_reports;

					$this->class_settings[ 'data' ][ 'url' ] = $nr->get_endpoint( array( 'request_type' => $req_type, 'endpoint_type' => '', 'custom_get' => $cget, 'hash' => $access ) );

					$this->class_settings[ 'data' ][ 'form_params' ] = '&daction='.$this->plugin.'&dtodo=execute&dnwp_action='.$this->table_name.'&dnwp_todo=exec_report_url';
					
					$this->class_settings[ 'html' ] = array( $this->view_path.'/get-records-data.php' );

					$html = $this->_get_html_view();

					return array(
						'html_replacement_selector' => '#download-csv',
						'html_replacement' => $html,
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'new-status',
						'javascript_functions' => array( 'prepare_new_record_form_new', 'benefitProgram.viewDownloads' )
					);
				}
			break;
			case 'get_export_view':
				$dd = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], 1 ) : array();
				$plugin = isset( $_POST[ 'plugin' ] ) ? $_POST[ 'plugin' ] : '';
				$limit = isset( $_POST['query_limit'] ) && $_POST['query_limit'] && isset( $_POST['use_limit'] ) && $_POST['use_limit'] ? $_POST['query_limit'] : 0;
				if( ! empty( $dd ) ){
					
					if( ! ( isset( $dd[ 'select' ] ) && $dd[ 'select' ] ) ){
						$error = '<h4>Invalid Select Fields</h4>Please select some fields in the <b>Select Fields</b> section';
					}else{

						$tables = array();
						$sels = array();
						$gps = array();
						$sorts = array();

						foreach( $dd[ 'select' ] as $sl ){
							switch( $sl[ 'aggregation' ] ){
							case 'sort_asc':
							case 'sort_desc':
								$sorts[ $sl[ 'table_name' ] ][ $sl[ 'aggregation' ] ] = $sl[ 'field' ];
							break;
							case 'group':
							// case 'group_cumulative':
								$gps[ $sl[ 'table_name' ] ][ $sl[ 'field' ] ] = $sl[ 'aggregation' ];
								if( isset( $dd[ 'select' ][ $sl[ 'field' ] ][ 'field' ] ) && $dd[ 'select' ][ $sl[ 'field' ] ][ 'field' ] ){
									$fxl = $dd[ 'select' ][ $sl[ 'field' ] ][ 'field' ];
									if( isset( $dd[ 'select' ][ $sl[ 'field' ] ][ 'alias' ] ) && $dd[ 'select' ][ $sl[ 'field' ] ][ 'alias' ] ){
										$fxl = $dd[ 'select' ][ $sl[ 'field' ] ][ 'alias' ];
									}
									
									unset( $gps[ $sl[ 'table_name' ] ][ $sl[ 'field' ] ] );
									$gps[ $sl[ 'table_name' ] ][ $fxl ] = $fxl;
								}
							break;
							default:
								if( isset( $sl[ 'alias' ] ) && $sl[ 'alias' ] ){
									$tables[ $sl[ 'table_name' ] ][ $sl[ 'alias' ] ] = array( 
										'alias' => $sl[ 'alias' ], 
										'field' => $sl[ 'field' ],
										'aggregation' => $sl[ 'aggregation' ]
									);
								}else{
									$tables[ $sl[ 'table_name' ] ][ $sl[ 'field' ] ] = 1;
								}
							break;
							}
						}
					}
					
				}else{
					$error = '<h4>Invalid Data</h4>';
				}


				if( ! $error ){

					$rones = '';
					$rone = '';
					$fnn = array( 'prepare_new_record_form_new', 'nwSearch.showCreateMine' );

					$this->class_settings[ 'other_data' ] = array();
				
					$this->class_settings["other_data"]["plugin"] = $plugin;
					$this->class_settings["other_data"]["tables"] = $tables;
					$this->class_settings["other_data"]["use_selects"] = $sels;
					$this->class_settings["other_data"]["use_groups"] = $gps;
					$this->class_settings["other_data"]["use_sorts"] = $sorts;
					$this->class_settings["other_data"]["query"]["where"] = '';
					$this->class_settings["other_data"]["hide_name"] = 1;
					$this->class_settings["other_data"]["hide_max_records"] = 1;
					$this->class_settings["other_data"]["hide_data_points"] = 1;
					$this->class_settings["other_data"]["always_open"] = 1;
					$this->class_settings["other_data"]["no_concat"] = 1;
					$this->class_settings["other_data"]['query']["limit"] = $limit;
					$this->class_settings["other_data"]["report_display_options"] = isset( $dd["report_display_options"] ) && $dd["report_display_options"] ? $dd['report_display_options'] : [];

					$this->class_settings[ 'myexcel_action' ] = $this->plugin;
					$this->class_settings[ 'myexcel_todo' ] = 'execute&nwp_action='. $this->table_name .'&nwp_todo=get_export_data';
					$this->class_settings[ 'data' ][ 'submit_text' ] = 'Proceed &rarr;';

					if( ! isset( $tabs[ 'query-result' ] ) ){
						$this->class_settings[ 'do_not_run_query' ] = 1;
					}

					$this->class_settings[ 'action_to_perform' ] = 'run_search_query2';
					$return =  $this->_run_search_query2();

					if( is_array( $return ) ){
						$return[ 'javascript_functions' ][] = 'nwSearch.showCreateMine';
						return $return;
					}elseif( $return ){
						return array(
							'html_replacement_selector_one' => $rones,
							'html_replacement_one' => $rone,
							'html_replacement_selector' => $handle,
							'html_replacement' => $return,
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							'javascript_functions' => $fnn
						);
					}else{
						$error = '<h4>Unable to Get Search View</h4>';
					}
				}

			break;
			case 'geenrate_report_tb':

				$plugin = '';
				if( isset( $_POST["data_source"] ) && $_POST["data_source"] ){
					$exp = is_array( $_POST["data_source"] ) ? $_POST["data_source"] : _convert_id_into_actions( $_POST["data_source"], array() );
					
					if( isset( $exp["value"] ) && $exp["value"] ){
						
						$tb = $exp["value"];
						switch( $exp[ 'type' ] ){
						case 'plugin':
							$plugin = $exp[ 'key' ];
						break;
						}
						
					}else{
						$error = 'Unable to Retrieve Data Source';
					}
				}else{
					$error = 'Invalid Data Source';
				}

				if( ! $error ){
					
					$rones = '';
					$rone = '';
					$fnn = array( 'prepare_new_record_form_new', 'nwSearch.init' );

					$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'configurations' ] = array( 'hide_preview_result' => 1 );

					$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'accept_empty' ] = 1;
					$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'post' ] = $exp;

					if( isset( $this->class_settings[ 'query_options' ] ) && $this->class_settings[ 'query_options' ] ){
						$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'cart_items' ] = $this->class_settings[ 'query_options' ];
					}
					if( isset( $this->class_settings[ 'query_select' ] ) && $this->class_settings[ 'query_select' ] ){
						$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'select' ] = $this->class_settings[ 'query_select' ];
					}
					if( isset( $this->class_settings[ 'query_limit' ] ) && $this->class_settings[ 'query_limit' ] ){
						$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'report_limit' ] = $this->class_settings[ 'query_limit' ];
					}
					if( isset( $this->class_settings[ 'report_id' ] ) && $this->class_settings[ 'report_id' ] ){
						$this->class_settings[ 'search_options' ][ 'cart_data' ][ 'configurations' ][ 'report_id' ] = $this->class_settings[ 'report_id' ];
					}

					$this->class_settings[ 'search_options' ][ 'action' ] = $this->plugin;
					$this->class_settings[ 'search_options' ][ 'todo' ] = 'execute&nwp_action='. $this->table_name .'&nwp_todo=get_export_view';

					$this->class_settings[ 'search_options' ][ 'add_system_fields' ] = 1;
					$this->class_settings[ 'search_options' ][ 'add_select_fields' ] = 1;
					$this->class_settings[ 'search_options' ][ 'tabs' ] = $tabs;

					$this->class_settings[ 'data' ][ 'return_html' ] = 1;

					$_GET[ 'table' ] = $tb;
					$_GET[ 'plugin' ] = $plugin;

					$return =  $this->_search_window();

					if( is_array( $return ) ){
						return $return;
					}elseif( $return ){
						return array(
							'html_replacement_selector_one' => $rones,
							'html_replacement_one' => $rone,
							'html_replacement_selector' => '#'.$handle,
							'html_replacement' => $return,
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							'javascript_functions' => $fnn
						);
					}else{
						$error_msg = '<h4>Unable to Get Search View</h4>';
					}

				}

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
				$this->class_settings[ 'html' ] = array( $this->view_path.'/'.$filename );
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
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);

			if( ! empty( $add2return ) ){
				foreach( $add2return as $ak => $av ){
					$return[ $ak ] = $av;
				}
			}
			
			return $return;
		}

		protected function _display_dashboard_page(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = 'validate-bill-and-finalize-discharge';
			
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
			$iframe = isset( $_GET[ 'iframe' ] ) ? $_GET[ 'iframe' ] : '';
			
			$data = array();
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'ping_db':
				if( class_exists( 'cNwp_orm' ) ){
					if( class_exists( 'cNwp_endpoint' ) ){

						require_once  dirname( dirname( dirname( __FILE__ ) ) ) . '/nwp_orm/classes/elasticonn.php';

						if( isset( $response[ 'error' ] ) && $response[ 'error' ] ){
							$error = '<h4><strong>Unable to Ping Database</strong></h4>'.$response[ 'error' ];
						}else{
							$error = '<h4><strong>Database Online</strong></h4>';
							$etype = 'success';
						}
					}else{
						$error = '<h4><strong>Missing Plugin</strong></h4>cNwp_endpoint';
					}
				}else{
					$error = '<h4><strong>Missing Plugin</strong></h4>cNwp_orm';
				}
			break;
			case 'display_dashboard_personal':
			case 'display_dashboard':
				$no_side_bar = isset( $_GET[ 'no_side_bar' ] ) ? $_GET[ 'no_side_bar' ] : '';
				$sidebar_pad = $no_side_bar && (isset( $_GET['spd']) && $_GET['spd'] ? $_GET['spd'] : 0);
				$date_range = isset( $_POST[ 'date_range' ] ) ? $_POST[ 'date_range' ] : '';
				$report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : '';
				$custom_filter_value = isset($_GET['cs_filter']) && $_GET['cs_filter'] ? $_GET['cs_filter'] : '';

				if( defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE") ){
					//Clears the current dashboard indictor filter
					$allIndicatorFilterCacheKey = "DashboardAllFilter-" . $this->class_settings["user_id"] . "-search";
					if( $report ){
						$allIndicatorFilterCacheKey = md5( $report . $this->class_settings[ 'user_id' ] . '-search' );
					}
					if (isset( $_SESSION["active_filters"][ $allIndicatorFilterCacheKey ] ) && $_SESSION["active_filters"][ $allIndicatorFilterCacheKey ]) {
						unset( $_SESSION["active_filters"][ $allIndicatorFilterCacheKey ] );
					}

					//Clears individual report filter cache
					if ( isset($_SESSION["active_filters"][ $report ]) && $_SESSION["active_filters"][ $report ] ) {
						unset( $_SESSION["active_filters"][ $report ] );
					}
				}

				session_write_close();


				$filename = 'homepage';
				$addQuery = [];

				$search_date = "creation_date";

				unset( $_GET[ 'menu_title' ] );

				if( $date_range ){
					// Define the start and end dates based on the selected option
				    $endDate = date('Y-m-d'); // Today's date as end date
				    $startDate = '';

				    switch( $date_range ){
			        case 'this_month':
			            $startDate = date('Y-m-01'); // First day of the current month
		            break;
			        case 'last_month':
			            $startDate = date('Y-m-d', strtotime('first day of last month')); // First day of last month
			            $endDate = date('Y-m-t', strtotime('last month')); // Last day of last month
		            break;
			        case 'last3_month':
			            $startDate = date('Y-m-d', strtotime('-2 months')); // 3 months ago from today
		            break;
			        case 'ytd':
			            $startDate = date('Y-01-01'); // First day of the current year
		            break;
			        case 'last_year':
			            $startDate = date('Y-01-01', strtotime('last year')); // First day of last year
			            $endDate = date('Y-12-t', strtotime('last year')); // Last day of last year
		            break;
			        case 'last_3_years':
			            $startDate = date('Y-m-d', strtotime('-2 years')); // 3 years ago from today
		            break;
			        default:
			            // Handle any other cases or errors
		            break;
				    }

				    if( $startDate ){
				    	$nid = get_new_id();
					    $addQuery[ '1' ][ 'data' ][ $nid ] = array(
					        "field" => $search_date,
					        "condition_text" => "BETWEEN",
					        "condition" => "between",
					        "search_key" => "end_date",
					        "search_value" => $endDate,
					        "start_date" => $startDate,
					        "end_date" => $endDate,
					        "logical_operator" => ""
					    );
				    }
				}

				$this->class_settings[ 'overide_select' ] = " id, `". $this->table_name ."`.`". $this->table_fields[ 'name' ] ."` as 'name', `". $this->table_name ."`.`". $this->table_fields[ 'endpoint' ] ."` as 'endpoint', `". $this->table_name ."`.`". $this->table_fields[ 'title' ] ."` as 'title', `". $this->table_name ."`.`". $this->table_fields[ 'type' ] ."` as 'type', `". $this->table_name ."`.`". $this->table_fields[ 'group' ] ."` as 'group', `". $this->table_name ."`.`". $this->table_fields[ 'data' ] ."` as 'data', `". $this->table_name ."`.`". $this->table_fields[ 'custom_data' ] ."` as 'custom_data' ";
				$this->class_settings[ 'where' ] = " AND `". $this->table_name ."`.`". $this->table_fields[ 'status' ] ."` = 'active' ";
				$this->class_settings[ 'where' ] .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'report_key' ] ."` = '". $report ."' ";

				$data[ 'rdata' ] = $this->_get_records( array( 'index_field' => 'id', 'cache' => 0 ) );

				if( isset($data['rdata']) && $data['rdata'] ){
					switch ( $report ) {
						case 'indicator_dashboard':
							foreach ($data['rdata']  as &$sval) {
								switch ( $sval['type'] ) {
									case 'report_value':
									break;
									default:
										if( isset( $sval['data'] ) && $sval['data'] && isset( $sval['custom_data']) && $sval['custom_data'] ){
											$_POST['id'] = $sval['id'];
											$this->class_settings['action_to_perform'] = 'view_result';
											$this->class_settings['return_r_data'] = 1;
											$this->class_settings['modQueryOptions']['addQuery'] = $addQuery;
											$ar = $this->_custom_capture_form_version_2();
											if( isset( $ar['data'] ) && $ar['data'] ){
												$jcode = isset($sval['custom_data']) && $sval['custom_data'] ? json_decode( $sval['custom_data'], true ) : [];
												if( isset($jcode['unit_of_measure']) && $jcode['unit_of_measure'] ){
													switch( $jcode['unit_of_measure'] ){
														case 'number':
														case 'percent':
															if( !( isset( $jcode['actual_value'] ) && $jcode['actual_value'] ) ){
																$jcode['actual_result'] = array_reduce($ar['data'], function($carry, $asv) {
																    return $carry + (isset($asv['Value']) && $asv['Value'] ? doubleval($asv['Value']) : 0);
																}, 0);																
															}
															$sval['custom_data'] = json_encode( $jcode );
														break;
													}
												}
											}
										}										
									break;
								}
							}
						break;
						case 'device_dashboard':
						case 'device_dashboard2':
							if( $custom_filter_value ){
						    	
						    	$nid = get_new_id();

							    $addQuery[ '1' ][ 'data' ][ $nid ] = array(
									"field" => "partner",
									"condition" => "in",
									"search_key" => "options",
									"search_value" => $custom_filter_value,
									"options_id" => $custom_filter_value,
									"options" => $custom_filter_value,
									"logical_operator" => ""
							    );

								foreach ( $data['rdata']  as &$sval) {
									if( isset( $sval['data'] ) && $sval['data']){
										$dd = json_decode( $sval[ 'data' ], 1 );
										if( isset( $dd[ 'tables' ] ) && $dd[ 'tables' ] ){
											if( isset($dd['table']) && $dd['table'] ){
												$cnt = 1;
												if( isset($dd['query']) && $dd['query'] && isset($dd['query']['query_options']) && $dd['query']['query_options'] ){
													$cnt = count( $dd['query']['query_options'] ) + 1;
												}
												foreach($addQuery as $aQKey => &$aQVal) {
													if( isset($aQVal['data']) && $aQVal['data'] ){
														foreach ($aQVal['data'] as &$aQVal2) {
															$aQVal2['sub_query'] = $cnt;
															$aQVal2['table_name'] = $dd['table'];
														}
														$dd['query']['query_options'][ $cnt ] = $aQVal;
														$cnt++;
													}
												}
											}
											$sval['data'] = json_encode( $dd );
										}
									}
								}
							}
						break;
					}

					switch ( $report ) {
						case 'device_dashboard':
						case 'device_dashboard2':
						case 'project_dashboard':
						case 'environmental_and_social_safeguard_indicators':
						case 'nin_enrolments':
						case 'suspicious_dashboard':
						case 'grm_dashboard':
							$tbl = 'type=plugin:::key=nwp_reports:::value=es_monitoring';
							switch ( $report ) {
								case 'device_dashboard':
									$tbl = 'type=plugin:::key=nwp_device_management:::value=device_mgt_store';
								break;
								case 'device_dashboard2':
									$tbl = 'type=plugin:::key=nwp_device_management:::value=device_mgt_store_sr';
								break;
								case 'suspicious_dashboard':
									$tbl = 'type=plugin:::key=nwp_reports:::value=suspicious_activity';
								break;
								case 'grm_dashboard':
									$tbl = 'type=plugin:::key=nwp_reports:::value=grm_data';
								break;
							}

							if( class_exists('cNwp_endpoint') ){
								$nwp = new cNwp_endpoint;
								$nwp->class_settings = $this->class_settings;
								$tb1 = 'endpoint';
								$tb = 'endpoint_log';
								$in = $nwp->load_class( array( 'class' => [ $tb, $tb1 ], 'initialize' => 1 ) );
								$in[ $tb ]->class_settings['overide_select'] = " `". $in[ $tb ]->table_name ."`.`". $in[ $tb ]->table_fields['date'] ."` as 'date' ";
								$in[ $tb ]->class_settings['join'] = " JOIN `". $this->class_settings['database_name'] ."`.`". $in[ $tb1 ]->table_name ."` ON `". $in[ $tb1 ]->table_name ."`.`id` = `". $in[ $tb ]->table_name ."`.`". $in[ $tb ]->table_fields['endpoint'] ."`";
								$in[ $tb ]->class_settings['where'] = " AND `". $in[ $tb ]->table_name ."`.`". $in[ $tb ]->table_fields['status'] ."` IN ('success', 'failed') AND `". $in[ $tb1 ]->table_name ."`.`". $in[ $tb1 ]->table_fields['datatable'] ."` = '". $tbl ."' AND (`". $in[ $tb ]->table_name ."`.`". $in[ $tb ]->table_fields['parent'] ."` IS NULL  OR  `". $in[ $tb ]->table_name ."`.`". $in[ $tb ]->table_fields['parent'] ."` = '') ORDER BY `". $in[ $tb ]->table_name ."`.`". $in[ $tb ]->table_fields['date'] ."` DESC";

								$in[ $tb ]->class_settings['limit'] = " LIMIT 1";
								$ls = $in[ $tb ]->_get_records();
								$data['last_pull_date'] = isset( $ls[0]['date'] ) && $ls[0]['date'] ? $ls[0]['date'] : 0;
							}
						break;
					}
				}

				switch( $action_to_perform ){
				case 'display_dashboard_personal':
					
					$this->class_settings[ 'overide_select' ] = " id, `". $this->table_name ."`.`". $this->table_fields[ 'name' ] ."` as 'name', `". $this->table_name ."`.`". $this->table_fields[ 'endpoint' ] ."` as 'endpoint', `". $this->table_name ."`.`". $this->table_fields[ 'title' ] ."` as 'title', `". $this->table_name ."`.`". $this->table_fields[ 'type' ] ."` as 'type', `". $this->table_name ."`.`". $this->table_fields[ 'data' ] ."` as 'data', `". $this->table_name ."`.`". $this->table_fields[ 'saved_query' ] ."` as 'saved_query', `". $this->table_name ."`.`". $this->table_fields[ 'parent_report' ] ."` as 'parent_report' ";
					$this->class_settings[ 'where' ] = " AND `". $this->table_name ."`.`". $this->table_fields[ 'status' ] ."` = 'active' AND `". $this->table_name ."`.`". $this->table_fields[ 'staff_responsible' ] ."` = '". $this->class_settings[ 'user_id' ] ."' AND ( `". $this->table_name ."`.`". $this->table_fields[ 'parent_report' ] ."` IS NOT NULL OR `". $this->table_name ."`.`". $this->table_fields[ 'parent_report' ] ."` <> '' ) ";
					$this->class_settings[ 'where' ] .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'report_key' ] ."` = '". $report ."' ";

					$data[ 'rpdata' ] = $this->_get_records( array( 'index_field' => 'parent_report', 'cache' => 0 ) );

				break;
				case 'display_dashboard':
					if( $report ){
						$settings = array(
							'cache_key' => md5( $report . $this->class_settings[ 'user_id' ] . '-search' ),
							'directory_name' => 'search',
							'permanent' => true,
						);
						clear_cache_for_special_values( $settings );
					}
				break;
				}

				$nr = new cNwp_reports;

				$data[ 'add_url' ] = $nr->report_endpoint_action;
								
				$data["report"] = $report;
				$data['iframe'] = $iframe;
				$data['action_to_perform'] = $action_to_perform;
				$data["date_range"] = $date_range;
				$data["no_side_bar"] = $no_side_bar;
				$data["spd"] = $sidebar_pad;
				$data["addQuery"] = $addQuery;
				$data["logo"] = isset( $_POST["logo"] )?$_POST["logo"]:'';
				$data["logo"] = ( isset( $_POST["dashboard_logo"] ) && $_POST["dashboard_logo"] )?$_POST["dashboard_logo"]:$data["logo"];

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
			
			if( $iframe ){
				return $this->_display_html_only( [ 'html' => $html ] );
			}

			if( $modal ){
				return $this->_launch_popup( $html, "#" . $handle, $mjs );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
			
			return $return;
		}

		public function _execute_search_window_query( $e = array() ){
			$error_msg = '';
			$q = '';
			$first = 1;
			$save_query = isset( $_POST[ 'save_query' ] ) ? $_POST[ 'save_query' ] : '';
			
			if( isset( $_POST["set_cache_query"] ) ){
				$this->class_settings[ 'set_cache_query' ] = $_POST["set_cache_query"];
			}
			$pplugin = isset( $_POST[ 'plugin' ] ) ? $_POST[ 'plugin' ] : '';
			$ptable = isset( $_POST[ 'table' ] ) ? $_POST[ 'table' ] : '';
			$ptable2 = isset( $_POST[ 'db_table' ] ) ? $_POST[ 'db_table' ] : '';
			
			if( $pplugin ){
				$p_cl = "c".ucwords( $pplugin );
				if( class_exists( $p_cl ) ){
					$p_cls = new $p_cl();
					$p_cls->load_class( array( "class" => array( $ptable ) ) );
				}
			}
						
			$base = new cCustomer_call_log();
			$base->class_settings = $this->class_settings;
	
			$tables = array();
			$ormWhere = array();
			
			if( isset( $e[ 'ptable' ] ) && $e[ 'ptable' ] && isset( $e[ 'tables' ] ) && $e[ 'tables' ] ){
				$ptable = $e[ 'ptable' ];
				$tables = $e[ 'tables' ];
			}else{
				
				if( isset( $_POST[ 'data' ] ) && $_POST[ 'data' ] ){
					
					$data = json_decode( $_POST[ 'data' ], true );
					$data[ 'conditions' ] = array();

					if( isset( $data[ 'cart_items' ] ) && ! empty( $data[ 'cart_items' ] ) ){
						if( ! $ptable && isset( $data[ 'dataSource' ] ) && $data[ 'dataSource' ] ){
							$ptable = $data[ 'dataSource' ];
						}
						$ctables = array();
						
						foreach( $data[ 'cart_items' ] as $kc => $vc ){
							$q1 = '';
							if( is_array( $vc ) && isset( $vc[ 'data' ] ) && ! empty( $vc[ 'data' ] ) ){
								
								$f1 = 0;
								foreach( $vc[ 'data' ] as $kq => $vq ){
									$oww = array();
									$q2 = '';
									$operand = '';
									
									if( $f1 && isset( $vq[ "logical_operator" ] ) && $vq[ "logical_operator" ] ){
										$operand = " " . $vq["logical_operator"];
									}
									
									//@nw5
									if( isset( $vq["db_table"] ) && $vq["db_table"] ){
										$vq["table_name"] = $vq["db_table"];
									}
									if( ! isset( $vq["table_name"] ) ){
										$vq["table_name"] = $ptable;
									}

									if( ! isset( $ctables[ $vq[ 'table_name' ] ] ) ){
										$xc = 'c'.$vq[ 'table_name' ];
										if( class_exists( $xc ) ){
											$xc = new $xc;
											$ctables[ $vq[ 'table_name' ] ] = $xc->table_fields;
										}
									}

									if( isset( $ctables[ $vq[ 'table_name' ] ][ $vq[ 'field' ] ] ) && $ctables[ $vq[ 'table_name' ] ][ $vq[ 'field' ] ] ){
										$vq[ 'field' ] = $ctables[ $vq[ 'table_name' ] ][ $vq[ 'field' ] ];
									}

									$tables[ $vq["table_name"] ] = $vq["table_name"];
									
									$oww = array(
										'key' => array(
											'table' => $vq[ "table_name" ],
											'field' => $vq[ "field" ],
										),
									);

									if( ( isset( $vq[ 'start_date' ] ) || isset( $vq[ 'end_date' ] ) || isset( $vq[ 'from_value' ] ) || isset( $vq[ 'to_value' ] ) ) || ( isset( $vq[ 'min' ] ) && isset( $vq[ 'max' ] ) ) ){
										
										switch( $vq[ 'condition' ] ){
										case 'between_relative':

											$oww[ 'condition' ] = $vq[ 'condition' ];
											foreach( [ "from_type","from_value","to_type","to_value","invert" ] as $cvo ){
												if( isset( $vq[ $cvo ] ) ){
													$oww[ $cvo ] = $vq[ $cvo ];
												}
											}
											
										break;
										case 'between':
											
											$oww[ 'condition' ] = 'BETWEEN';
											if( isset( $vq[ 'min' ] ) ){
												$oww[ 'min' ] = $vq[ "min" ];
												$oww[ 'max' ] = $vq[ "max" ];
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` BETWEEN '" . $vq[ "min" ] . "' AND '" . $vq[ "max" ] . "' )";
											}else if( isset( $vq[ 'start_date' ] ) || isset( $vq[ 'end_date' ] ) ){
												if( isset( $vq[ "start_date" ] ) && $vq[ "start_date" ] ){
													$oww[ 'min' ] = convert_date_to_timestamp( $vq[ "start_date" ], 1 );
												}
												if( isset( $vq[ "end_date" ] ) && $vq[ "end_date" ] ){
													$oww[ 'max' ] = convert_date_to_timestamp( $vq[ "end_date" ], 2 );
												}
												if( isset( $vq[ "start_date" ] ) && $vq[ "start_date" ] && isset( $vq[ "end_date" ] ) && $vq[ "end_date" ] ){
													$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` BETWEEN " . convert_date_to_timestamp( $vq[ "start_date" ], 1 ) . " AND " . convert_date_to_timestamp( $vq[ "end_date" ], 2 ) . " )";
												}else if( isset( $vq[ "start_date" ] ) && $vq[ "start_date" ] ){
													$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` >= " . convert_date_to_timestamp( $vq[ "start_date" ], 1 ) . " )";
												}else if( isset( $vq[ "end_date" ] ) && $vq[ "end_date" ] ){
													$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` <= " . convert_date_to_timestamp( $vq[ "end_date" ], 2 ) . " )";
												}
											}
											
										break;
										default:
											$gt = '';
											$lt = '';
											switch( $vq[ 'condition' ] ){
											case 'gte_lte':
												$gt = '>=';
												$lt = '<=';
											break;
											case 'gt_lt':
												$gt = '>';
												$lt = '<';
											break;
											}

											if( isset( $vq[ 'min' ] ) ){
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $gt,
													'value' => $vq[ "min" ],
												);
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $lt,
													'value' => $vq[ "max" ],
												);
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $gt ." '" . $vq[ "min" ] . "' AND `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $lt ." '" . $vq[ "max" ] . "' )";
											}else if( isset( $vq[ 'start_date' ] ) ){
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $gt,
													'value' => $vq[ "start_date" ],
												);
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $lt,
													'value' => $vq[ "end_date" ],
												);
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $gt ." '" . $vq[ "start_date" ] . "' AND `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $lt ." '" . $vq[ "end_date" ] . "' )";
											}
											
										break;
										}
										
									}else{
										$q2 .= " `" . $vq[ 'table_name' ] . "`.`" . $vq[ 'field' ] . "` ";
										
										$cdn = '';
										switch( $vq[ 'condition' ] ){
										case 'equal_to':
											$cdn = '=';
										break;
										case 'not_equal_to':
											$cdn = '<>';
										break;
										case 'less_than':
											$cdn = '<';
										break;
										case 'greater_than':
											$cdn = '>';
										break;
										case 'less_than_equal_to':
											$cdn = '<=';
										break;
										case 'greater_than_equal_to':
											$cdn = '>=';
										break;
										case 'contains':
											$cdn = 'regexp';
										break;
										case 'in':
											$cdn = "IN ";
										break;	
										case 'not_in':
											$cdn = "NOT IN ";
										break;
										case 'not_empty':
											$cdn = "NOT NULL ";
										break;
										}

										$q2 .= $cdn;

										switch( $vq[ 'condition' ] ){
										case 'in':
										case 'not_in':
											$q2 .= " ( ";
											switch( $vq[ 'condition' ] ){
											case 'in':
												$oww[ 'condition' ] = ' IN ';
											break;
											case 'not_in':
												$oww[ 'condition' ] = ' NOT IN ';
											break;
											}
											$sv2 = nl2br( $vq[ 'search_value' ] );
											if( strpos( $sv2, "<br />" ) > -1 ){
												$vq[ 'search_value' ] = preg_replace( "/\r|\n/", ",", $vq[ 'search_value' ] );
											}
											
											$oww[ 'value' ] = explode( ',', $vq[ 'search_value' ] );
											$q2 .= "'" . implode( "','", explode( ',', $vq[ 'search_value' ] ) ) . "'";
											$q2 .= ' ) ';
										break;
										default:	
											$oww[ 'condition' ] = $cdn;
											$oww[ 'value' ] = $vq[ 'search_value' ];
											$q2 .= " '" . $vq[ 'search_value' ] . "' ";
										break;
										}
									}
									
									$q1 .= $operand . $q2;

									if( ! isset( $data[ 'conditions' ] ) )$data[ 'conditions' ] = array();

									$opd = $operand ? $operand : 'and';
									if( isset( $oww[ 'condition' ] ) && $oww[ 'condition' ] ){
										$ormWhere[ trim( $opd ) ][] = $oww;
									}
									$data[ 'conditions' ][ $vq["table_name"] ][] = array(
										'query' => $q2,
										'operand' => $opd , 
									);
									$f1++;
								}
							}else{
								continue;
							}
							
							if( isset( $vc[ 'condition' ] ) && $vc[ 'condition' ] && $q1 ){
								$q .= $vc[ 'condition' ] . ' ';
							}
							$q .= ' ( ' . $q1 . ' ) ';
						}
					}
				}
			}

			if( isset( $e[ 'return_query' ] ) && $e[ 'return_query' ] ){
				if( class_exists( 'cNwp_orm' ) && ! empty( $ormWhere ) ){
					return $ormWhere;
				}else{
					return $q;
				}
			}
			
			$stats_data = array();
			$select_count = "";
			
			if( isset( $this->class_settings[ 'accept_empty' ] ) && $this->class_settings[ 'accept_empty' ] ){
				if( empty( $tables ) ){
					$tables = array( $ptable => $ptable );
				}
				
			}
			
			if( ! empty( $tables ) && $ptable ){
				$join = '';
				$group = '';
				if( isset( $tables[ $ptable ] ) )unset( $tables[ $ptable ] );
				
				$tb = 'c' . ucwords( $ptable );

				if( class_exists( $tb ) ){
					$cl = new $tb();
					//@nw5
					if( $ptable2 ){
						$ptable = $ptable2;
						$cl->table_name = $ptable2;
					}

					$stats_data[ $cl->table_name ] = $cl->label;
					if( isset( $cl->children ) && is_array( $cl->children ) && ! empty( $cl->children ) ){
						$select_count = " COUNT( DISTINCT `". $cl->table_name ."`.`id` ) as '". $cl->table_name ."'";

						foreach( $cl->children as $key => $value ){
							$clx = 'c' . ucwords( $key );
							$cls = new $clx();
							
							$join .= " LEFT JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $key ."` ON `". $key ."`.`". ( $value[ 'link_field' ] == 'id' ? 'id' : $cls->table_fields[ $value[ 'link_field' ] ] ) ."` = `". $ptable ."`.`id` AND `". $key ."`.`record_status` = '1' ";
							
							$select_count .= ", COUNT( DISTINCT `". $key ."`.`id` ) as '". $cls->table_name ."' ";
							$stats_data[ $cls->table_name ] = $cls->label;
						}
					}

					if( $join ){
						if( isset( $e[ 'get_join' ] ) && $e[ 'get_join' ] ){
							return $join;
						}

						$group = " GROUP BY `". $ptable ."`.`id` ";
					}

					$base->table_name = $ptable;
					$base->class_settings[ 'where' ] = "";
					if( isset( $this->class_settings[ 'accept_empty' ] ) && $this->class_settings[ 'accept_empty' ] ){
						if( ! $q ){
							$q = ' ';
						}
					}

					if( $q ){

						$data[ 'query' ] = $q;
						if( class_exists( 'cNwp_orm' ) && ! empty( $ormWhere ) ){
							$data[ 'query' ] = $ormWhere;
						}
						if( $save_query ){
							foreach( $data[ 'conditions' ][ $vq["table_name"] ] as & $fv ){
								$fv[ 'query' ] = addslashes( $fv[ 'query' ] );
							}

							$data[ 'query' ] = addslashes( $data[ 'query' ] );
							$f = new cFiles();
							$f->class_settings = $this->class_settings;
							$f->class_settings[ 'action_to_perform' ] = 'save_line_items';

							$f->class_settings[ 'line_items' ][] = array(
								'date' => time(),
								'state' => '',
								'lga' => '',
								'ward' => '',
								'community' => '',
								'name' => $save_query,
								'description' => '',
								'content' => rawurlencode( json_encode( $data ) ),
								// 'content' => json_encode( addslashes( $data ) ),
								'file_url' => '',
								'type' => 'search',
								'reference' => '',
								'reference_table' => $ptable,
							);

							$f->files();
						}
						
						if( isset( $this->class_settings[ 'set_cache_query' ] ) && $this->class_settings[ 'set_cache_query' ] ){
							$cache_key = isset( $this->class_settings[ 'cache_query_key' ] )?$this->class_settings[ 'cache_query_key' ]: md5( $ptable . "-" . $this->class_settings["user_id"] . "-search" );
							
							$settings = array(
								'cache_key' => $cache_key,
								'cache_values' => $data,
								'directory_name' => 'search',
								'permanent' => true,
							);
							set_cache_for_special_values( $settings );
						}
						
						$s = array(
							"select" => 'SELECT',
							"from" => 1,
							"clear" => 1,
							"group" => $group,
							"join" => $join,
							"where" => "WHERE `".$ptable."`.`record_status` = '1' " . " AND ( " . $q . ") ",
							"table" => $ptable,
							"select_count" => $select_count,
							"stats_data" => $stats_data,
							"database" => $this->class_settings["database_name"],
						);
						//stop here data, s and ptable
						if( isset( $this->class_settings[ 'return_query' ] ) && $this->class_settings[ 'return_query' ] ){
							unset( $data["fields"] );
							
							$array =  array(
								'table' => $ptable,
								'data' => $s,
								'query' => $q,
								'stats_data' => $stats_data,
							);
							
							if( ! ( isset( $this->class_settings[ 'exclude_filter_data' ] ) && $this->class_settings[ 'exclude_filter_data' ] ) ){
								$array['filter_data'] = $data;
							}
							
							return $array;
						}
						$this->_set_search_query( $s );
						
					}
				}
				
				$r1 = $base->_display_notification( array( "message" => "<h4>Search Query Executed</h4><p>Successful execution of search query</p>", "type" => "success" ) );
				unset( $r1["html"] );
				
				$return = array(
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( '$nwProcessor.reload_datatable' ),
				);

				$return = array_merge( $r1, $return );
				
				if( ! is_array( $return[ 'javascript_functions' ] ) ){
					$return[ 'javascript_functions' ] = array();
				}
				$return[ 'javascript_functions' ][] = 'nwSearch.closeModal';
				$return[ 'javascript_functions' ][] = 'nwSearch.showClearSearch';

				return $return;
			}else{
				$error_msg = "Invalid Dataset to Query";
			}
			
			return $base->_display_notification( array( "message" => $error_msg ) );
		}

		public function _run_search_query2( $e = array() ){
			if( isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ){
				$action_to_perform = $this->class_settings[ 'action_to_perform' ];
				$plugin = isset( $_POST[ 'plugin' ] ) && $_POST[ 'plugin' ] ? $_POST[ 'plugin' ] : '';

				$dx = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], true ) : array();
				$additional_info = isset( $dx[ 'additional_info' ] ) ? $dx[ 'additional_info' ] : array();
				// unset( $dx ); // don't need it again. Maybe in d future
				
				$table = $_POST[ 'table' ];

				if( $plugin ){
					$p_cl = "c".ucwords( $plugin );
					if( class_exists( $p_cl ) ){
						$p_cls = new $p_cl();
						$pc = $p_cls->load_class( array( "class" => array( $table ) ) );
					}
				}

				$tb = 'c' . ucwords( $table );
				$tb = new $tb();
				$tb->class_settings = $this->class_settings;

				$mine_key = md5( "mine-" . $table . "-" . $this->class_settings["user_id"] );
				$mine_source = isset( $_GET[ 'mine_source' ] ) && $_GET[ 'mine_source' ] ? $_GET[ 'mine_source' ] : '';
				$mine_source_value = isset( $_GET[ 'sr' ] ) && $_GET[ 'sr' ] ? $_GET[ 'sr' ] : '';

				$result = array();
				$js = array( 'benefitProgram.viewResult' );

				$reference_tb = '';
				$filename = '';
				
				$handle = "dash-board-main-content-area";
				if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
					$handle = $this->class_settings["html_replacement_selector"];
				}

				switch( $action_to_perform ){
				case 'run_search_query2':
					$this->class_settings[ 'return_query' ] = 1;
					$this->class_settings[ 'add_status_in_join' ] = 'active';
					
					$tb->class_settings[ 'where' ] = '';

					switch( $action_to_perform ){
					case 'run_search_query2':
						if( $mine_source_value ){
							$this->class_settings[ 'data' ][ 'social_registry' ] = $mine_source_value;
						}

						$this->class_settings[ 'action_to_perform' ] = 'run_search_query';
						$this->class_settings[ 'set_cache_query' ] = 1;
						$filename = 'query-result.php';
					break;
					}

					if( isset( $dx[ 'accept_empty' ] ) && $dx[ 'accept_empty' ] ){
						$this->class_settings[ 'accept_empty' ] = $dx[ 'accept_empty' ];
					}
					
					$this->class_settings[ 'cache_query_key' ] = $mine_key;
					$r = $this->reports_bay();
					$wwhere = '';
					// print_r( $r );exit;
					if( isset( $r[ 'query' ] ) && trim( $r[ 'query' ] ) ){
						$wwhere = ' AND '.$r[ 'query' ];
						$tb->class_settings[ 'where' ] .= $wwhere;
					}
					
					$result[ 'data_query' ] = $r;
					switch( $action_to_perform ){
					case 'run_search_query2':
						if( ! ( isset( $dx[ 'accept_empty' ] ) && $dx[ 'accept_empty' ] ) ){
							if( isset( $r[ 'msg' ] ) && $r[ 'msg' ] ){
								return $this->_display_notification( array( "type" => "error", "message" => $r[ 'msg' ] ) );
							}
						}

						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
						);


						$drx = array();
						if( ! ( isset( $this->class_settings[ 'do_not_run_query' ] ) && $this->class_settings[ 'do_not_run_query' ] ) ){
							foreach( array( $table ) as $tbxz ){
								
								$jn = '';
								if( isset( $r[ 'data' ][ 'join' ] ) && $r[ 'data' ][ 'join' ] ){
									$jn = $r[ 'data' ][ 'join' ];
								}

								$queryx = " SELECT COUNT( DISTINCT `". $tbxz ."`.`id` ) as 'count' FROM `". $this->class_settings[ 'database_name' ] ."`.`". $table ."` ". $jn ." WHERE `". $table ."`.`record_status` = '1' ". $tb->class_settings[ 'where' ] ."  ";
									// print_r( $queryx );exit;

								$query_settings[ 'query' ] = $queryx;
								$query_settings[ 'tables' ] = array( $tbxz );
	            				$dxz = execute_sql_query($query_settings);
	            				// print_r( $dxz );exit;

	            				if( isset( $dxz[0][ 'count' ] ) && $dxz[0][ 'count' ] ){
	            					$drx[0][ $tbxz ] = $dxz[0][ 'count' ];
	            				}else{
	            					$drx[0][ $tbxz ] = 0;
	            				}
							}
						}
						$r2 = $drx;
						
					break;
					default:
						$r2 = $tb->_get_records();
					break;
					}

					$result[ 'data_source' ] = $tb->table_name;
					$result[ 'mine_source_value' ] = $mine_source_value;
					$result[ 'mine_source' ] = $mine_source;
					$result[ 'mine_key' ] = $mine_key;
					$result[ 'stats' ] = is_array( $r2 ) ? $r2 : array();

					if( isset( $_GET[ 'callback' ] ) && $_GET[ 'callback' ] ){
						$js[] = $_GET[ 'callback' ];
					}
				break;
				}



				if( $plugin )$result["plugin"] = $plugin;
				if( ! empty( $additional_info ) )$result["additional_info"] = $additional_info;

				$return = array();
				
				$return["status"] = "new-status";						
				
				$return["javascript_functions"] = ( isset( $_POST[ 'callback' ] ) ? array_merge( array( $_POST[ 'callback' ] ), $js ) : $js );
				
				// print_r( $result );exit;
				switch( $action_to_perform ){
				case 'run_search_query2':
					$pd = $this->class_settings[ 'data' ];
					$return[ 'status' ] = 'new-status';
					$return["javascript_functions"] = ( isset( $_GET[ 'callback' ] ) ? array_merge( array( $_GET[ 'callback' ] ), $js ) : $js );
					$this->class_settings[ 'data' ] = $result;
					$this->class_settings[ 'html' ] = array( $this->view_path.'/'.$filename );
					// $this->class_settings[ 'html' ] = array( 'html-files/templates-1/search/advance-search-window/' . $filename );

					$return["html_replacement_selector"] = "#" . $handle;

					$base = new cCustomer_call_log();
					$base->class_settings = $this->class_settings;

					$return["html_replacement"] = $base->_get_html_view();
					$this->class_settings[ 'data' ] = $pd;
					
					$_POST["hidden_fields"] = json_encode( array( "table" => $table ) );
					
					$cls = new cMyexcel();
					$cls->class_settings = $this->class_settings;
					$cls->class_settings["action_to_perform"] = 'generate_csv';
					$cls->class_settings["show_all_fields"] = 1;
					$cls->class_settings["return_html"] = $handle;

					if( isset( $this->class_settings[ 'myexcel_action' ] ) && $this->class_settings[ 'myexcel_action' ] ){
						$cls->class_settings["data"][ 'action' ] = $this->class_settings[ 'myexcel_action' ];
					}
					if( isset( $this->class_settings[ 'myexcel_todo' ] ) && $this->class_settings[ 'myexcel_todo' ] ){
						$cls->class_settings["data"][ 'todo' ] = $this->class_settings[ 'myexcel_todo' ];
					}

					$cls->class_settings["other_data"] = isset( $this->class_settings[ 'other_data' ] ) ? $this->class_settings[ 'other_data' ] : array();

					if( isset( $dx[ 'configurations' ] ) && $dx[ 'configurations' ] ){
						$cls->class_settings["other_data"][ 'configurations' ] = $dx[ 'configurations' ];
					}

					// print_r( $cls->class_settings["other_data"] );exit;
					if( isset( $r[ 'filter_data' ][ 'cart_items' ] ) && $r[ 'filter_data' ][ 'cart_items' ] ){
						foreach( $r[ 'filter_data' ][ 'cart_items' ] as &$rv ){
							if( isset( $rv[ 'data' ] ) && ! empty( $rv[ 'data' ] ) ){
								foreach( $rv[ 'data' ] as &$ry ){
									unset( $ry[ 'id' ] );
									unset( $ry[ 'sub_query_text' ] );
									unset( $ry[ 'table_name_text' ] );
									unset( $ry[ 'table_name' ] );
									unset( $ry[ 'condition_text' ] );
									unset( $ry[ 'undefined' ] );
									unset( $ry[ 'search' ] );
									unset( $ry[ 'options_tags' ] );
									unset( $ry[ 'undefined' ] );
									unset( $ry[ 'options_text' ] );
									unset( $ry[ 'logical_operator_text' ] );
								}
							}
						}
						// $cls->class_settings["other_data"]["query"]["where"] = $wwhere;
						if( ! ( isset( $cls->class_settings["other_data"]["query"][ 'where' ] ) && $cls->class_settings["other_data"]["query"][ 'where' ] ) ){
							unset( $cls->class_settings["other_data"]["query"][ 'where' ] );
						}
						$cls->class_settings["other_data"]["query"]["query_options"] = $r[ 'filter_data' ][ 'cart_items' ];
					}

					$r = $cls->myexcel();
					
					// print_r( $cls->class_settings["other_data"] );exit;
					if( isset( $r["html_replacement"] ) ){
						$return["html_replacement_one"] = $r["html_replacement"];
						$return["html_replacement_selector_one"] = '#create-mine';
						$return["javascript_functions"][] = 'prepare_new_record_form_new';
					}
				break;
				}
				
				return $return;
			}	
		}

		private function _search_window(){
			$js = array( 'prepare_new_record_form_new', 'nwSearch.init' );
			$html = '';
			$d = isset( $this->class_settings[ 'search_options' ] )?$this->class_settings[ 'search_options' ]:array();
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			$title = '';
			$table_name = '';
			
			$error_msg = '';
			$filename = 'advance-search-window';
			
			$t = isset( $d[ "key" ] )?$d[ "key" ]:'';
			
			//@nw5
			$t1 = '';
			
			if( isset( $_GET["table"] ) && $_GET["table"] ){
				//@nw5
				if( isset( $_GET["real_table"] ) && $_GET["real_table"] ){
					$t1 = $_GET["table"];
					$_GET["table"] = $_GET["real_table"];
				}
				
				$t = strtolower( trim( $_GET["table"] ) );
				
				if( isset( $_GET["plugin"] ) && $_GET["plugin"] ){
					$p_cl = "c".ucwords( $_GET["plugin"] );
					
					if( class_exists( $p_cl ) ){
						$p_cls = new $p_cl();
						$p_cls->load_class( array( "class" => array( $t ) ) );
						
						$d[ "plugin" ] = $_GET["plugin"];
						$d[ "tables" ][ $t ][ 'plugin' ] = $_GET["plugin"];
					}else{
						$error_msg = 'Unregistered Plugin';
					}
				}
				
				$cl = "c" . ucwords( $t );
				if( class_exists( $cl ) ){
					$cls = new $cl();
					
					if( isset( $cls->table_name ) ){
						$table_name = isset( $cls->label )?$cls->label:$cls->table_name;
						$d[ "tables" ][ $cls->table_name ][ 'text' ] = $table_name;
						$d[ "cart_data" ][ 'fields' ][ $t ] = $t();

						if( isset( $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $t ] ) && $d[ 'cart_data' ][ 'configurations' ] ){
							foreach( $d[ "cart_data" ][ 'fields' ][ $t ] as $dvv => $dtt ){
								if( isset( $dtt[ 'field_identifier' ] ) && in_array( $dtt[ 'field_identifier' ], $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $t ] ) ){
									unset( $d[ "cart_data" ][ 'fields' ][ $t ][ $dvv ] );
								}
							}
						}
						
						if( isset( $cls->children ) && is_array( $cls->children ) && ! empty( $cls->children ) ){
							foreach( $cls->children as $ct => $ch ){
								$cl1 = "c" . ucwords( $ct );
								if( class_exists( $cl1 ) ){
									$cls1 = new $cl1();
									$d[ "tables" ][ $cls1->table_name ][ 'text' ] = isset( $cls1->label )?$cls1->label:$cls1->table_name;
									$d[ "cart_data" ][ 'fields' ][ $ct ] = $ct();
								
									if( isset( $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $ct ] ) && $d[ 'cart_data' ][ 'configurations' ] ){
										foreach( $d[ "cart_data" ][ 'fields' ][ $ct ] as $dvv => $dtt ){
											if( isset( $dtt[ 'field_identifier' ] ) && in_array( $dtt[ 'field_identifier' ], $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $ct ] ) ){
												unset( $d[ "cart_data" ][ 'fields' ][ $ct ][ $dvv ] );
											}
										}
									}
									
								}
							}
						}
					}
				}
			}
			
			if( ! ( isset( $d[ "tables" ] ) && is_array( $d[ "tables" ] ) && ! empty( $d[ "tables" ] ) ) ){
				$error_msg = '<h4>Undefined Search Tables</h4>Please try again';
			}

			if( ! $error_msg ){	
				//@nw5
				if( $t1 ){
					$d[ "real_table" ] = $t;
					$d[ "db_table" ] = isset( $_GET["db_table"] )?$_GET["db_table"]:'';
					
					$d[ "tables" ][ $t ][ 'real_table' ] = $t;
					$d[ "tables" ][ $t ][ 'db_table' ] = $d[ "db_table" ];
					$d[ "tables" ][ $t ][ 'o_table' ] = $t1;
				}
				
				$d[ "db_table_filter" ] = isset( $_GET["db_table_filter"] )?$_GET["db_table_filter"]:'';
				$d[ "tables" ][ $t ][ 'db_table_filter' ] = $d[ "db_table_filter" ];
			}
			
			if( isset( $this->class_settings[ 'data' ][ 'action' ] ) && $this->class_settings[ 'data' ][ 'action' ] ){
				$d[ 'action' ] = $this->class_settings[ 'data' ][ 'action' ];
			}

			if( isset( $this->class_settings[ 'data' ][ 'todo' ] ) && $this->class_settings[ 'data' ][ 'todo' ] ){
				$d[ 'todo' ] = $this->class_settings[ 'data' ][ 'todo' ];
			}

			if( isset( $this->class_settings[ 'data' ][ 'params' ] ) && $this->class_settings[ 'data' ][ 'params' ] ){
				$d[ 'params' ] = $this->class_settings[ 'data' ][ 'params' ];
			}

			if( isset( $this->class_settings[ 'data' ][ 'return_html' ] ) && $this->class_settings[ 'data' ][ 'return_html' ] ){
				$d[ 'preview_results' ] = 1;
			}
			
			$base = new cCustomer_call_log();
			$base->class_settings = $this->class_settings;
			
			if( ! $error_msg ){
				$title = ': ' . $table_name;
				
				if( isset( $this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ] ) && $this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ] ){
					$old_search = $this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ];
				}else{

					$xck = '';
					if( isset( $d[ 'cart_data' ][ 'configurations' ][ 'report_id' ] ) && $d[ 'cart_data' ][ 'configurations' ][ 'report_id' ] ){
						$xck = $d[ 'cart_data' ][ 'configurations' ][ 'report_id' ];
					}

					if( isset( $this->class_settings[ 'cache_key' ] ) && $this->class_settings[ 'cache_key' ] ){
						$xck = $this->class_settings[ 'cache_key' ];
					}

					$cack = md5( $xck . "-" . $this->class_settings["user_id"] . "-search" );
					if( isset( $this->class_settings[ 'overide_cache_key' ] ) && $this->class_settings[ 'overide_cache_key' ] ){
						$cack = $this->class_settings[ 'overide_cache_key' ];
					}

					if( $xck ){
						if (defined("USE_SESSION_FOR_REPORT_FILTER_CACHE") && constant("USE_SESSION_FOR_REPORT_FILTER_CACHE")) {
							$old_search = isset( $_SESSION["active_filters"][ $xck ] ) && $_SESSION["active_filters"][ $xck ] ? $_SESSION["active_filters"][ $xck ] : [];
						}else{
							$settings = array(
								'cache_key' => $cack,
								'directory_name' => 'search',
								'permanent' => true,
							);
							$old_search = get_cache_for_special_values( $settings );
						}
					}
				}
				
				// @steve empty saved filter
				if( isset( $old_search["cart_items"] ) && $old_search['cart_items'] ){
					$d["old_search"] = $old_search;
				}
				if( isset( $old_search['report_limit'] ) && $old_search['report_limit'] ){
					$d['old_search']['report_limit'] = $old_search['report_limit'];
				}
				
				$d[ 'parent_table' ] = $t;
				
				$base->class_settings[ 'data' ] = $d;
				$base->class_settings[ 'html' ] = array( $this->view_path.'/'.$filename );
				$html = $base->_get_html_view();
				
				$base->class_settings["modal_dialog_style"] = ' width:60%; ';
				$base->class_settings["modal_dialog_class"] = 'modal-lg';
				switch( $action_to_perform ){
				case 'bulk_edit':
					$base->class_settings["modal_title"] = 'Bulk Edit Window' . $title;
				break;
				default:
					$base->class_settings["modal_title"] = 'Advance Search Window' . $title;
				break;
				}


				if( isset( $this->class_settings[ 'data' ][ 'return_html' ] ) && $this->class_settings[ 'data' ][ 'return_html' ] ){
					return $html;
				}
				
				return $base->_launch_popup( $html, '#dash-board-main-content-area', $js );
			}
			
			return $base->_display_notification( array( "message" => $error_msg ) );
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
				$spilt_screen = 1;
			break;
			case "saved_reports":
				$disable_btn = 0;
				if( !get_hyella_development_mode() ){
					// $this->datatable_settings[ "show_add_new" ] = 0;
				}
				$this->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;

				$access = get_accessed_functions();
				$super = ( !is_array( $access ) && $access == 1 ) ? 1 : 0;
				if ( !$super && !( isset( $access[ 'accessible_functions' ][ $this->table_name . '.all_reports.all_reports' ] ) && $access[ 'accessible_functions' ][ $this->table_name . '.all_reports.all_reports' ] ) ) {
					$this->class_settings['filter'][ 'where' ] = " AND (`". $this->table_fields['staff_responsible'] ."` = '". $this->class_settings['user_id'] ."'";
					$ac_reports = array_keys( get_all_report_ac_types() );
					$vat = get_vat_mapping();
					if( $ac_reports ){
						$al = [];
						foreach ( $ac_reports as $key ) {
							if( isset( $access['accessible_functions'][ $this->table_name . '.rt_opt.' . $key ] ) && $access['accessible_functions'][ $this->table_name . '.rt_opt.' . $key ] ){
								$al[] = isset( $vat[ $key ] ) && $vat[ $key ] ? $vat[ $key ] : $key;
							}
						}
						if( $al ){
							$this->class_settings['filter']['where'] .= " OR (`". $this->table_fields['report_key'] ."` IN ('". implode("', '", $al) ."') )";
						}
					}
					$this->class_settings['filter']['where'] .= ")";
				}
			break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
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
			case 'save_new_popup':
				if( (isset($_POST['unit_of_measure']) ) || (isset($_POST['baseline'])) || ( isset($_POST['end_target']) ) ){
					$this->class_settings['current_record_id'] = $_POST['id'];
					$e = $this->_get_record();
					$a = isset( $e['custom_data'] ) && $e['custom_data'] ? json_decode($e['custom_data'], true) : [];
					$a['unit_of_measure'] = isset( $_POST['unit_of_measure'] ) ? $_POST['unit_of_measure'] : ( isset( $a['unit_of_measure'] ) && $a['unit_of_measure'] ? $a['unit_of_measure'] : '');
					$a['baseline'] = isset( $_POST['baseline']) ? $_POST['baseline'] : ( isset( $a['baseline'] ) && $a['baseline'] ? $a['baseline'] : '');
					$a['end_target'] = isset( $_POST['end_target']) ? $_POST['end_target'] : ( isset( $a['end_target'] ) && $a['end_target'] ? $a['end_target'] : '');
					$a['actual_value'] = isset( $_POST['actual_value']) ? $_POST['actual_value'] : ( isset( $a['actual_value'] ) && $a['actual_value'] ? $a['actual_value'] : '');

					$_POST[ $this->table_fields['custom_data'] ] = json_encode( $a );
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
			
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = "#dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
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
			
			switch ( $action_to_perform ){
				case 'new_popup_form':
					$this->class_settings['before_html'] = '<div class="note note-warning">
																<span><b>Only Revised PDO Indicators are supported via this form </b></span>
															</div>';
					$this->class_settings['hidden_records'][ $this->table_fields['endpoint'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['parent_report'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['data'] ] = 1;
					
					// $this->class_settings['hidden_records'][ $this->table_fields['group'] ] = 1;

					$this->class_settings['hidden_records_css'][ $this->table_fields['date'] ] = 1;
					$this->class_settings['hidden_records_css'][ $this->table_fields['category'] ] = 1;
					$this->class_settings['hidden_records_css'][ $this->table_fields['staff_responsible'] ] = 1;
					$this->class_settings['hidden_records_css'][ $this->table_fields['report_key'] ] = 1;
					$this->class_settings['hidden_records_css'][ $this->table_fields['custom_data'] ] = 1;
					$this->class_settings['hidden_records_css'][ $this->table_fields['save_query'] ] = 1;
					$this->class_settings['hidden_records_css'][ $this->table_fields['type'] ] = 1;

					$this->class_settings['form_values_important'][ $this->table_fields['date'] ] = date("U");
					$this->class_settings['form_values_important'][ $this->table_fields['staff_responsible'] ] = $this->class_settings['user_id'];
					$this->class_settings['form_values_important'][ $this->table_fields['type'] ] = 'report_value';
					$this->class_settings['form_values_important'][ $this->table_fields['category'] ] = 'custom';
					$this->class_settings['form_values_important'][ $this->table_fields['report_key'] ] = 'indicator_dashboard';

					$this->_set_edit_types(
						array( 
							'type' => $this->class_settings['form_values_important'][ $this->table_fields['type']],
							'report_key' => $this->class_settings['form_values_important'][ $this->table_fields['report_key']],
						 )
					);
				break;
				case 'edit_popup_form_in_popup':
				case 'edit_popup_form':
					$_POST["mod"] = 'edit-'.md5($this->table_name);
					$this->class_settings["modal_title"] = 'Edit ' . $this->label;
					$this->class_settings["override_defaults"] = 1;
					
					$this->class_settings['hidden_records'][ $this->table_fields['endpoint'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['category'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['staff_responsible'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['report_key'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['parent_report'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['data'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['date'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['group'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['custom_data'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['saved_query'] ] = 1;

					if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
						$this->class_settings[ 'current_record_id' ] = $_POST[ 'id' ];
						$this->_set_edit_types( $this->_get_record() );
					}

				break;
			}
						
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
						 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, edit, view, delete) will be available in access control
			'exclude_from_crud' => array( 'delete' => 1 ),
			'special_actions' => array(
				'e_export' => array(
					'title' => 'Export As CSV option',
					'todo' => 'e_export',
					'type' => '',
				),
				'all_reports' => array(
					'title' => 'Access All Reports',
					'todo' => 'all_reports',
					'type' => '',
				),
				'rt_opt' => array(
					'label' => 'Specific Report Category',
					'todo' => 'get_all_report_ac_types',
					'type' => 'function'
				),
			),

			'more_actions' => array(
				'sb2' => array(
					'todo' => 'view_result',
					'title' => 'View Query Result',
					'text' => 'View Result',
					'button_class' => 'dark',
				),
				'eq' => array(
					'todo' => 'edit_query',
					'title' => 'Edit Query',
					'text' => 'Edit Query',
					'button_class' => 'dark',
				),
				'actions' => array(
					'title' => 'More',
					'data' => array(
						'p1' => array(
							'sb1' => array(
								'todo' => 'reset_report',
								'title' => 'Clear Saved Query',
								'text' => 'Clear Saved Query',
								'button_class' => 'secondary',							
							)
						),
					),
				),
			),
		);
	}
	
	function reports_bay(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/reports_bay.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/reports_bay.json' ), true );
			if( isset( $return[ "labels" ] ) ){


				$key = $return[ "fields" ][ "staff_responsible" ];
				$return[ "labels" ][ $key ][ 'calculations' ] = array(
					'type' => 'record-details',
					'show_in_form' => 1,
					'multiple' => 1,
					'reference_table' => 'users',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( $key ) ),
				);
				$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
				$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=users&todo=get_users_select2" tags="true" ';
				$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';

				$key = $return[ "fields" ][ "report_key" ];
				$return[ "labels" ][ $key ][ 'form_field' ] = 'select';
				$return[ "labels" ][ $key ][ 'form_field_options' ] = 'get_report_key';
				

				return $return[ "labels" ];
			}
		}
	}
?>
<?php
	/**
	 * customer_call_log Class
	 *
	 * @used in  				customer_call_log Function
	 * @created  				08:22 | 06-06-2011
	 * @database table name   	customer_call_log
	 */
	
	class cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'customer_call_log';
		public $table_name_static = 'customer_call_log';
		public $customer_source = 'customers';
		
		public $default_reference = "customer";

		//php 8.2 Dynamic Property Notice=============== @steve
		public $plugin_instance; 
		public $plugin;
		public $view_dir;
		public $view_path;
		public $plugin_dir;
		public $table_labels;
		// //php 8.2 Dynamic Property Notice============

		public $basic_data;  //steve revision history button

		public $default_reference_field = "";
		public $reset_members_cache = "";
		
		public $label = 'Customers Call Log';
		
		public $build_list = 0;
		public $build_list_condition = '';
		public $build_list_field = '';
		public $build_list_field_bracket = '';
		
		public $refresh_cache_after_save_line_items = 1;
		
		private $associated_cache_keys = array(
			'customer_call_log',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'customer_call_log001',
			'customer' => 'customer_call_log002',
			'reason_for_call' => 'customer_call_log003',
			'feedback' => 'customer_call_log004',
			'category' => 'customer_call_log005',
			'comment' => 'customer_call_log006',
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
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
			
		}
	
		function customer_call_log(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					if( ! ( isset( $this->plugin ) && $this->plugin ) ){
						return $this->_display_notification( array( "type" => 'no_language', "message" => 'no language file') );
					}
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
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
			case 'split_datatable':
			case 'display_all_records':
			case 'display_deleted_records':
				$returned_value = $this->_display_data_table();
			break;
			case 'restore':
			case 'delete_from_popup':
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'display_all_deleted_records':
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
			break;
			case 'delete_with_query':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
				$returned_value = $this->_new_popup_form();
			break;
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
			break;
			case 'delete_with_prompt':
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form();
			break;
			case "display_spreadsheet_new":
			case "display_spreadsheet_view":
				$returned_value = $this->_display_spreadsheet_view();
			break;
			case "save_display_spreadsheet_new":
			case "save_display_spreadsheet_view":
				$returned_value = $this->_save_display_spreadsheet_view();
			break;
			case "store_extracted_line_items_data":
				$returned_value = $this->_store_extracted_line_items_data();
			break;
			case "display_callback_prompt":
				$returned_value = $this->_display_callback_prompt();
			break;
			}
			
			if( isset( $this->class_settings["success_callback_action"] ) && $this->class_settings["success_callback_action"] ){
				$returned_value['re_process'] = 1;
				$returned_value['re_process_code'] = 1;
				$returned_value['mod'] = isset( $this->class_settings["success_callback_mod"] )?$this->class_settings["success_callback_mod"]:'-';
				$returned_value['id'] = isset( $this->class_settings["success_callback_id"] )?$this->class_settings["success_callback_id"]:1;
				$returned_value['action'] = $this->class_settings["success_callback_action"];
			}
			
			return $returned_value;
		}

		// 24-may-23 steve job queue, watch for action to perform
		public function _class_cases(){
			//Default action to perform to be caught by rabbit queue
			return array(
				// "create_new_record",
				// "edit",
				// "display_all_records",
				// "split_datatable",
				// "display_all_records",
				// "display_deleted_records",
				// "restore",
				// "delete_from_popup",
				// "delete",
				// "save",
				// "display_all_deleted_records",
				// "display_all_records_frontend",
				// "display_all_records_full_view",
				// "display_app_manager",
				// "display_app_view",
				// "save_new_popup",
				// "save_app_changes",
				// "delete_with_query",
				// "delete_app_record",
				// "new_popup_form_in_popup",
				// "new_popup_form",
				// "search_customer_call_log",
				// "delete_with_prompt",
				// "view_details",
				// "search_form",
				// "display_spreadsheet_new",
				// "display_spreadsheet_view",
				// "save_display_spreadsheet_new",
				// "save_display_spreadsheet_view",
				// "store_extracted_line_items_data",
				// "display_callback_prompt",
			);
		}
		
		//10-may-23
		public function _load_variant_class(){
            if( defined('HR_VARIANT') && HR_VARIANT ){
                $_ = '_'. strtolower(HR_VARIANT);
                $cls = 'cNwp_human_resource'.$_ ;
                if( class_exists( $cls ) ){
                    $nwp = new $cls();
                    $nwp->class_settings = $this->class_settings;
                    $tb = $this->table_name;
                    $in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
                    if( isset($in[ $tb ]) && $in[ $tb ] ){
                        return $in[ $tb ]->$tb();
                    }
                }
            }
        }

		protected function _display_callback_prompt( $opt = array() ){
			$title = 'Title';
			$msg = 'Message';
			$err = "error";
			if( isset( $_GET[ 'error_msg' ] ) && $_GET[ 'error_msg' ] ){
				$msg = urldecode( $_GET[ 'error_msg' ] );
			}
			if( isset( $_GET[ 'err_type' ] ) && $_GET[ 'err_type' ] ){
				$err = $_GET[ 'err_type' ];
			}
			if( isset( $_GET[ 'err_title' ] ) && $_GET[ 'err_title' ] ){
				$title = urldecode( $_GET[ 'err_title' ] );
			}
			if( isset( $_GET[ 'button' ] ) && $_GET[ 'button' ] ){
				$msg .= '<br>'.rawurldecode( $_GET[ 'button' ] );
			}
			return $this->_display_notification( array( "message" => "<h4><strong>". $title ."</strong></h4>".$msg, "type" => $err, 'manual_close' => 1 ) );
		}

		protected function _get_customer_debt( $opt = array() ){
			$customer = isset( $opt[ 'customer' ] ) && $opt[ 'customer' ] ? $opt[ 'customer' ] : ( isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '' );

			$handle = "modal-replacement-handle";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			if( isset( $customer ) && $customer ){

				$c = new cCustomers();
				$c->class_settings = $this->class_settings;
				$c->class_settings[ 'action_to_perform' ] = "check_customer_financial_position";
				$c->class_settings[ 'current_record_id' ] = $customer;

				$r = $c->customers();

				if( isset( $r[ 'html_msg' ] ) && $r[ 'html_msg' ] ){
					return array(
						'data' => $r,
						'html_replacement_selector' => '#'.$handle,
						'html_replacement' => $r[ 'html_msg' ],
						'status' => 'new-status',
						'javascript_functions' => array(), 
					);
				}
			}
			
			return array(
				'status' => 'new-status',
				'javascript_functions' => array(), 
			);
		}
		
		public function _trigger_notification( $opt ){
			$error = '';
			if( isset( $opt["multiple"] ) && is_array( $opt["multiple"] ) && ! empty( $opt["multiple"] ) ){
				
			}else{
				$opt["multiple"] = array( $opt );
			}
			
			foreach( $opt["multiple"] as & $ov ){
				foreach( array( 'message', 'title', 'id', 'type', 'key', 'store' ) as $var ){
					if( ! ( isset( $ov[ $var ] ) && $ov[ $var ] ) ){
						$error = 'Undefined '.$var;
						break 2;
					}
				}
				$ov[ 'table' ] = isset( $ov[ 'table' ] ) ? $ov[ 'table' ] : $this->table_name;
			}

			// echo $error;
			if( ! $error ){
				// Send Notification
				$nb = 'cNwp_client_notification';
				$nbc = 'cn_user_settings';
				if( class_exists( $nb ) ){
					$nb = new $nb();
					$nb->class_settings = $this->class_settings;
					$bb = $nb->load_class( array( 'initialize' => 1, 'class' => array( $nbc ) ) );

					if( isset( $bb[ $nbc ]->table_name ) ){
						$bb[ $nbc ]->class_settings[ 'action_to_perform' ] = 'send_notification';
						$bb[ $nbc ]->class_settings[ 'not_details' ] = $opt["multiple"];
						// print_r( 'out_patient'.get_current_store() );exit();
						return $bb[ $nbc ]->$nbc();
					}
				}
			}

		}
		
		protected function _capture_signature(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Patient</h4><p>Please try again</p>';
				return $err->error();
			}
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$customer_id = $_POST["id"];
			
			$handle = "#capture-container";
			$handle2 = '';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
			}

			$build_query = http_build_query( $_GET );

			$this->class_settings[ 'data' ][ 'container' ] = $handle2;
			$this->class_settings[ 'data' ][ 'type' ] = $action_to_perform;
			$this->class_settings[ 'data' ][ 'id' ] = $customer_id;
			$this->class_settings[ 'data' ][ 'action' ] = $this->table_name;
			$this->class_settings[ 'data' ][ 'plugin' ] = isset( $this->plugin ) ? $this->plugin : '';
			$this->class_settings[ 'data' ][ 'todo' ] = 'capture_' . $action_to_perform;
			$this->class_settings[ 'data' ][ 'build_query' ] = $build_query;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/signature-capture.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				"html_replacement_selector" => $handle,
				"html_replacement" => $returning_html_data,
				"status" => "new-status",
				//"javascript_functions" => array( "nwInventory.activateRecentSupplyItems" )
			);
		}

		public function _set_datatable_settings( $opt = array() ){
			$this->datatable_settings = $opt;
		}
		
		protected function _charts_dashboard( $opt = array() ){
			if( ! ( isset( $opt[ 'charts' ] ) && is_array( $opt[ 'charts' ] ) && ! empty( $opt[ 'charts' ] ) ) ){
				return $this->_display_notification( array( 'type' => 'error', 'message' => 'Undefined Chart Key' ) );
			}

			$data = array();

			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = $ccx;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];

			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);

			$all = 1;
			$filter_where = '';
			$chart = isset( $this->class_settings[ 'data' ][ 'chart' ] ) && $this->class_settings[ 'data' ][ 'chart' ] ? $this->class_settings[ 'data' ][ 'chart' ] : '';
			$single = isset( $this->class_settings[ 'data' ][ 'return_complete_single' ] ) && $this->class_settings[ 'data' ][ 'return_complete_single' ] ? 0 : $chart;

			if( isset( $this->class_settings[ 'data' ][ 'where' ] ) && $this->class_settings[ 'data' ][ 'where' ] ){
				$filter_where = " AND " . $this->class_settings[ 'data' ][ 'where' ];
			}

			if( $chart )$all = 0;

			$empty_chart = array(
				'data' => array(
					'labels' => '-',
					'datasets' => array(),
				),
				'type' => 'horizontalBar',
			);

			$chart_js = new cChart_js();
			$chart_js->class_settings = $this->class_settings;

			$chart_js->class_settings[ 'query_action' ] = $this->table_name;
			$chart_js->class_settings[ 'query_todo' ] = $this->class_settings[ 'action_to_perform' ];

			$chart_type = isset( $_POST[ 'chart_type' ] ) && $_POST[ 'chart_type' ] ? $_POST[ 'chart_type' ] : '';
			
			$options[ 'chart_js' ] = 1;

			$type = isset( $opt[ 'type' ] ) ? $opt[ 'type' ] : '';

			// 	if( $charts !== 'no_of_individuals_with_valid_id_bvn' ){continue;}
							// print_r( $all.'-' );
							// print_r( $chart.'-' );
							// print_r( $opt );exit;
			foreach( $opt[ 'charts' ] as $cts ){
				$chart_key = $cts[ 'key' ];
				if( ( $chart == $chart_key ) || $all ){
					$dx = array();

					switch( $cts[ 'type' ] ){
					case 'chart_from_report':

						$options[ 'filter_where' ] = $filter_where;
						$options[ 'return_data' ] = 1;
						$options[ 'based_on' ] = isset( $_POST[ 'based_on' ] ) && $_POST[ 'based_on' ] ? $_POST[ 'based_on' ] : '';

						$options[ 'based_on' ] = isset( $cts[ 'based_on' ] ) ? $cts[ 'based_on' ] : $options[ 'based_on' ];

						$options[ 'report' ] = $chart_key;
						$options[ 'report_type' ] = substr($action_to_perform, 0, strpos($action_to_perform, '_dashboard' ));

						$dx = $this->_display_report( $options );
					break;
					default:

						if( isset( $cts[ 'data' ] ) && $cts[ 'data' ] ){
							$dx[ 'data' ] = $cts[ 'data' ];
						}else if( isset( $cts[ 'select' ] ) && $cts[ 'select' ] ){
							$query = $cts[ 'select' ] . ( isset( $cts[ 'join' ] ) ? $cts[ 'join' ] : '' ) . ' WHERE ' . ( isset( $cts[ 'where' ] ) ? $cts[ 'where' ] : '' ) . $filter_where . ( isset( $cts[ 'group' ] ) ? $cts[ 'group' ] : '' ) . $filter_where . ( isset( $cts[ 'order_by' ] ) ? $cts[ 'order_by' ] : '' );

							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => isset( $cts[ 'query_type' ] ) ? $cts[ 'query_type' ] : 'SELECT',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							// execute query here
							$dx[ 'data' ] = execute_sql_query( $query_settings );
						}
					break;
					}

					if( isset( $dx[ 'data' ][0] ) ){
						if( ! $chart_js->chart_timeout )$chart_js->chart_timeout = 500;
						
						$ex = array();
						// $ex = $cts;

						$ex[ 'data' ] = $cts;
						$ex[ 'data' ][ 'data' ] = $dx[ 'data' ];
						$ex[ 'data' ][ 'label_key' ] = ( isset( $cts[ 'label_key' ] ) ? $cts[ 'label_key' ] : ( isset( $dx[ 'group' ] ) ? $dx[ 'group' ] : '' ) );
						$ex[ 'data' ][ 'value_key' ] = ( isset( $cts[ 'value_key' ] ) ? ( $cts[ 'value_key' ] == 'use_group' && isset( $dx[ 'group' ] ) ? $dx[ 'group' ] : $cts[ 'value_key' ] ) : 'count' );
						$ex[ 'data' ][ 'data_key' ] = ( isset( $cts[ 'data_key' ] ) ? ( $cts[ 'data_key' ] == 'use_group' && isset( $dx[ 'group' ] ) ? $dx[ 'group' ] : $cts[ 'data_key' ] ) : '' );
						//  ]'data_key' = ( isset( $cts[ 'data_key' ] ) ? $cts[ 'data_key' ] : '' );
						$ex[ 'data' ][ 'type' ] = $chart_type ? $chart_type : ( isset( $cts[ 'chart_type' ] ) ? $cts[ 'chart_type' ] : 'bar' );
						$ex[ 'data' ][ 'tooltip' ] = ( isset( $cts[ 'tooltip' ] ) ? $cts[ 'tooltip' ] : ( isset( $dx[ 'group' ] ) ? ucwords( $dx[ 'group' ] ) : '' ) );
						$ex[ 'data' ][ 'index' ] = ( isset( $cts[ 'index' ] ) ? $cts[ 'index' ] : array() );

						if( ! ( isset( $cts[ 'unset_transform_keys' ] ) && $cts[ 'unset_transform_keys' ] ) && isset( $dx[ 'headers' ] ) && ! empty( $dx[ 'headers' ] ) ){
							$ex[ 'data' ][ 'transform_keys' ] = array_keys( $dx[ 'headers' ] );
							$ex[ 'data' ][ 'index' ] = $dx[ 'headers' ];
						}

						if( isset( $cts[ 'chart_height' ] ) && $cts[ 'chart_height' ] ){
							$ex[ 'chart_height' ] = $cts[ 'chart_height' ];
						}

						if( isset( $cts[ 'chart_width' ] ) && $cts[ 'chart_width' ] ){
							$ex[ 'chart_width' ] = $cts[ 'chart_width' ];
						}

						if( isset( $cts[ 'mike' ] ) ){
							// print_r( $ex );exit;
						}

						$ex[ 'key' ] = $ex[ 'data' ][ 'label_key' ];
						$ex[ 'options' ] = $options;
						$ex[ 'single' ] = $single;
						$ex[ 'chart_key' ] = $chart_key;
						$ex[ 'table' ] = $this->table_name;
						$ex[ 'title' ] = isset( $options[ 'report' ] ) && isset( $report_opt[ $options[ 'report_type' ] ][ 'options' ][ $options[ 'report' ] ] ) ? $report_opt[ $options[ 'report_type' ] ][ 'options' ][ $options[ 'report' ] ] : ( isset( $cts[ 'title' ] ) ? $cts[ 'title' ] : '' );

							// print_r( $ex );exit;
						$data[ $chart_key ][ 'html' ] = $chart_js->_prepare_chart_html( $ex );
						// print_r( $data[ $chart_key ][ 'html' ] );exit;
					}
				}
			}
			
			// print_r( $data );exit;
			if( $chart && isset( $data[ $chart ] ) ){

				return $data[ $chart ];
			}

			if( isset( $opt[ 'return_data' ] ) && $opt[ 'return_data' ] ){
				return $data;
			}

			$this->class_settings[ 'data' ][ 'charts' ] = $data;
			$this->class_settings[ 'data' ][ 'col' ] = isset( $opt[ 'col' ] ) ? $opt[ 'col' ] : 12;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/display-charts.php' );
			
			$returning_html_data = $this->_get_html_view();
			
			$return = array();
			
			$return["status"] = "new-status";
			$return["html_replacement_selector"] = '#'.$handle;
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		protected function _open_module( $opt = array() ){

			if( ! ( isset( $opt["id"] ) && $opt["id"] ) ){
				$opt = isset( $this->class_settings[ 'open_module' ] ) ? $this->class_settings[ 'open_module' ] : array();
			}
			
			if( ! ( isset( $opt["id"] ) && $opt["id"] ) ){
				return $this->_display_notification( array( "type" => 'error', "message" => 'Invalid View Details ID') );
			}

			$exclude_get = array( 'action', 'todo', 'nwp_action', 'nwp_todo', 'html_replacement_selector', 'nwp_target' );
			$get = $_GET;
			$get2 = '';

			foreach( $_GET as $ek => $ev ){
				if( ! in_array( $ek, $exclude_get ) ){
					$get2 .= '&'.$ek.'='.$ev;
				}
			}
			// print_r( $get2 );exit;

			$error = '';
			$data = $opt;
			$data[ 'refresh_params' ] = $get2;
			if(  isset( $opt[ 'refresh_params' ] ) && $opt[ 'refresh_params' ] ){
				$data[ 'refresh_params' ] .= $opt[ 'refresh_params' ];
			}

			$data[ 'action_to_perform' ] = $this->class_settings[ 'action_to_perform' ];
			$data[ 'table' ] = $this->table_name;
			
			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle2 = $ccx;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$data[ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}

			$view_details_html = isset( $opt[ 'view_details_html' ] ) ? $opt[ 'view_details_html' ] : '';
			$details_no_plugin = isset( $opt[ 'details_no_plugin' ] ) ? $opt[ 'details_no_plugin' ] : '';
			$details_tb = isset( $opt[ 'details_action' ] ) ? $opt[ 'details_action' ] : $this->table_name;
			$details_todo = isset( $opt[ 'details_todo' ] ) ? $opt[ 'details_todo' ] : 'view_details2';

			if( ( ! $details_no_plugin ) && isset( $this->plugin ) && $this->plugin ){
				$details_tb = $this->plugin;
			}

			if( isset( $opt[ 'locked_reference' ] ) && $opt[ 'locked_reference' ] && class_exists( "cLocked_files" ) ){
				$lock_params = array();
				$lock_params["reference"] = isset( $opt[ 'locked_reference' ] ) && $opt[ 'locked_reference' ] ? $opt[ 'locked_reference' ] : $opt[ 'id' ];
				$lock_params["reference_table"] = isset( $opt[ 'locked_reference_table' ] ) && $opt[ 'locked_reference_table' ] ? $opt[ 'locked_reference_table' ] : $details_tb;
				
				// print_r( $lock_params );exit;
				$cx = new cLocked_files();
				$cx->class_settings = $this->class_settings;
				$lr = $cx->_check_for_full_access( $lock_params );
				// print_r( $lr );exit;
				
				if( isset( $lr["locked"] ) ){
					$data["locked"] = $lr;
				}else{
					$data["show_close"] = 1;
				}
			}
			
			$_POST[ 'id' ] = $opt["id"];
			$cl = 'c'.ucwords( $details_tb );

			if( class_exists( $cl ) ){
				$cl = new $cl();
				$cl->class_settings = $this->class_settings;
				// print_r( $opt[ 'id' ] );exit;

				if( ( ! $details_no_plugin ) && isset( $this->plugin ) && $this->plugin ){
					$cl->class_settings[ 'action_to_perform' ] = "execute";
					$cl->class_settings["nwp_action"] = $this->table_name;
					$cl->class_settings["nwp_todo"] = $details_todo;

					// print_r( $_POST[ 'id' ] );exit;
					$dx = $cl->$details_tb();

					if( isset( $dx[ 'data' ] ) && ! empty( $dx[ 'data' ] ) ){
						$data[ 'item' ] = $dx[ 'data' ];
					}else{
						$cl->class_settings[ 'current_record_id' ] = $opt[ 'id' ];

						$cl->class_settings[ 'action_to_perform' ] = "execute";
						$cl->class_settings["nwp_action"] = $this->table_name;
						$cl->class_settings["nwp_todo"] = 'get_record';

						$data[ 'item' ] = $cl->$details_tb();
					}
				
					$data[ 'plugin' ] = $this->plugin;
					$data[ 'action_to_perform' ] = "execute&nwp_action=". $this->table_name ."&nwp_todo=". $data[ 'action_to_perform' ];
				}else{
					$cl->class_settings[ 'action_to_perform' ] = $details_todo;

					$dx = $cl->$details_tb();

					if( isset( $dx[ 'data' ] ) && ! empty( $dx[ 'data' ] ) ){
						$data[ 'item' ] = $dx[ 'data' ];
					}else{
						$cl->class_settings[ 'current_record_id' ] = $opt[ 'id' ];
						$data[ 'item' ] = $cl->_get_record();
					}
				}

				if( $view_details_html ){
					$data[ 'details' ] = $view_details_html;
				}elsE{
					if( isset( $dx[ 'html_replacement' ] ) && $dx[ 'html_replacement' ] ){
						$data[ 'details' ] = $dx[ 'html_replacement' ];

						// $data[ 'table' ] = $cl->table_name;
						$data[ 'table_label' ] = $cl->label;
					}else{
						$data[ 'details' ] = "Undefined Details View";
					}
					
					if( isset( $opt[ 'details_content' ] ) ){
						if( $opt[ 'details_content' ] == 'single_view' ){
							$data[ 'single_view' ] = isset( $data[ 'details' ] )?$data[ 'details' ]:'';
							$opt[ 'details_content' ] = '&nbsp;';
						}
						$data[ 'details' ] = $opt[ 'details_content' ];
					}
				}

			}else{
				$error = "Invalid Class";
			}
			// print_r( $data[ 'table' ] );exit;
			
			if( ! $error ){
				$js = array();
				$tb = $this->table_name;
				if( isset( $opt[ 'table_real' ] ) && $opt[ 'table_real' ] ){
					$tb = $opt[ 'table_real' ];
					$details_tb = $opt[ 'table_real' ];
				}
				$data[ 'show_refresh' ] = isset( $opt[ 'show_refresh' ] ) ? $opt[ 'show_refresh' ] : 1;

				$data[ 'details_column' ] = isset( $opt[ 'details_column' ] ) ? $opt[ 'details_column' ] : 5;
				$data[ 'action_column' ] = isset( $opt[ 'action_column' ] ) ? $opt[ 'action_column' ] : 3;
				$data[ 'comments_column' ] = isset( $opt[ 'comments_column' ] ) ? $opt[ 'comments_column' ] : 3;

				if( isset( $data[ 'hide_view_comments' ] ) && $data[ 'hide_view_comments' ] ){
					$data[ 'details_column' ] = 6;
					$data[ 'action_column' ] = 6;
					$data[ 'comments_column' ] = 0;
				}

				$ga_params = '&source=' . $this->table_name;
				$plugin = isset( $this->plugin ) ? $this->plugin : '';
				if( $plugin )$ga_params .= '&plugin='.$plugin;
				
				if( isset( $opt[ 'link_params' ] ) && $opt[ 'link_params' ] ){
					$ga_params .= $opt[ 'link_params' ];
				}

				if( ! ( isset( $opt[ 'hide_files' ] ) && $opt[ 'hide_files' ] ) ){
					if( ! ( isset( $opt[ 'hide_files2' ] ) && $opt[ 'hide_files2' ] ) ){
						$data[ 'general_actions' ][] = array(
							'custom_id' => isset( $opt[ 'files_id' ] )?$opt[ 'files_id' ]:'',
							'action' => 'files',
							'todo' => 'attach_file_to_record'.$ga_params,
							'title' => 'Attach File',
						);
					}
					
					$cflg = '';
					//print_r($opt["flags"]); exit;
					if( isset( $opt["flags"]["files"] ) && $opt["flags"]["files"] ){
						$cflg = $opt["flags"]["files"];
					}
					
					$data[ 'tabs' ][] = array(
						'custom_id' => isset( $opt[ 'files_id' ] )?$opt[ 'files_id' ]:'',
						'action' => 'files',
						'todo' => 'files_search_list2&table='. ( $plugin ? $tb : $details_tb ).$ga_params,
						'title' => 'Files'.$cflg,
					);
				}

				if( ! ( isset( $opt[ 'hide_comments' ] ) && $opt[ 'hide_comments' ] ) ){
					if( ! ( isset( $opt[ 'hide_comments1' ] ) && $opt[ 'hide_comments1' ] ) ){
						$data[ 'show_comments' ] = 1;
					}
					if( ! ( isset( $opt[ 'hide_comments2' ] ) && $opt[ 'hide_comments2' ] ) ){
						$data[ 'general_actions' ][] = array(
							'custom_id' => isset( $opt[ 'comments_id' ] )?$opt[ 'comments_id' ]:'',
							'action' => 'comments',
							'todo' => 'add_comment'.$ga_params,
							'title' => 'Comment',
						);
					}
					
					$cflg = '';
					if( isset( $opt["flags"]["comments"] ) && $opt["flags"]["comments"] ){
						$cflg = $opt["flags"]["comments"];
					}
					$data[ 'tabs' ][] = array(
						'custom_id' => isset( $opt[ 'comments_id' ] )?$opt[ 'comments_id' ]:'',
						'action' => 'comments',
						'todo' => 'comments_search_list2&table='. ( $plugin ? $tb : $details_tb ).$ga_params,
						'title' => 'Comments' . $cflg,
					);
				}

				//$data[ 'general_tasks' ] = isset( $opt[ 'general_tasks' ] ) ? $opt[ 'general_tasks' ] : array();

				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/nw-open-module' );
				$html = $this->_get_html_view();

				if( isset( $dx[ 'callback' ] ) && $dx[ 'callback' ] ){
					$return = $dx[ 'callback' ];
				}

				$return[ 'html_replacement' ] = $html;
				$return[ 'html_replacement_selector' ] = '#'.$handle2;
				$return[ 'method_executed' ] = $this->class_settings['action_to_perform'];
				$return[ 'status' ] = 'new-status';
				$return[ 'javascript_functions' ] = $js;

				return $return;
			}

			$error = $error ? $error : 'Undefined Error';

			return $this->_display_notification( array( "type" => 'error', "message" => $error ) );
		}

		protected function _transform_data_for_table_report( $opt = array() ){
			$return = array();
			$d = array();

			if( isset( $opt[ 'data' ] ) && ! empty( $opt[ 'data' ] ) ){
				$d = $opt[ 'data' ];

				if( isset( $opt[ 'key' ] ) && isset( $opt[ 'key2' ] ) ){
					$switch = isset( $opt[ 'switch' ] ) ? $opt[ 'switch' ] : 1;
					$key = $opt[ 'key' ];
					$key2 = $opt[ 'key2' ];

					$headers = isset( $opt[ 'headers' ] ) && is_array( $opt[ 'headers' ] ) ? $opt[ 'headers' ] : array();
					$headers2 = isset( $opt[ 'headers2' ] ) && is_array( $opt[ 'headers2' ] ) ? $opt[ 'headers2' ] : array();
					$no_summation = isset( $opt[ 'no_summation' ] ) ? $opt[ 'no_summation' ] : 0;
					// print_r( $no_summation );exit;

					$key_header = isset( $opt[ 'key_header' ] ) && is_array( $opt[ 'key_header' ] ) ? $opt[ 'key_header' ] : array();
					$key2_header = isset( $opt[ 'key2_header' ] ) && is_array( $opt[ 'key2_header' ] ) ? $opt[ 'key2_header' ] : $headers;

					$append_count_to_key = isset( $opt[ 'append_count_to_key' ] ) ? $opt[ 'append_count_to_key' ] : 0;

					switch( $switch ){
					case 1:
						foreach( $d as &$d2 ){
							if( isset( $d2[ $key ] ) && isset( $d2[ $key2 ] ) ){
								$ktext = isset( $key_header[ $d2[ $key ] ] ) ? $key_header[ $d2[ $key ] ] : $d2[ $key ];
								
								if( isset( $d2[ 'count' ] ) && $d2[ 'count' ] && $append_count_to_key ){
									$ktext .= ' ('. $d2[ 'count' ] .')';
								}
								
								if( ! isset( $return[ $d2[ $key2 ] ][ $d2[ $key ] ][ 'count' ] ) ){
									$text = isset( $headers[ $d2[ $key2 ] ] ) ? $headers[ $d2[ $key2 ] ] : $d2[ $key2 ];
									$text = isset( $key2_header[ $d2[ $key2 ] ] ) ? $key2_header[ $d2[ $key2 ] ] : $text;

									$return[ $text ][ $d2[ $key ] ][ 'count' ] = 0;
									$return[ $text ][ $d2[ $key ] ][ $key ] = $ktext;
								}
								if( ! $no_summation )$return[ $text ][ $d2[ $key ] ][ 'count' ] += doubleval( $d2[ 'count' ] );
								else $return[ $text ][ $d2[ $key ] ][ 'count' ] = $d2[ 'count' ];

							}
						}
					break;
					case 2:
						if( ! empty( $key2_header ) ){
							foreach( $d as &$d2 ){
								foreach( $key2_header as $x => $key2 ){
									if( isset( $d2[ $key ] ) && isset( $d2[ $x ] ) ){
										$khead = $d2[ $key ];
								
										if( isset( $d2[ 'count' ] ) && $d2[ 'count' ] && $append_count_to_key ){
											$khead .= ' ('. $d2[ 'count' ] .')';
										}

										if( ! isset( $return[ $key2 ][ $khead ][ 'count' ] ) ){
											$return[ $key2 ][ $khead ][ 'count' ] = 0;
										}

										$text2 = isset( $headers2[ $khead ] ) ? $headers2[ $khead ] : $khead;
										$text2 = isset( $key_header[ $khead ] ) ? $key_header[ $khead ] : $text2;

										if( ! $no_summation )$return[ $key2 ][ $khead ][ 'count' ] += doubleval($d2[ $x ] );
										else $return[ $key2 ][ $khead ][ 'count' ] = $d2[ $x ];
										$return[ $key2 ][ $khead ][ $key ] = $text2;
										$d2[ $key2 ] = $d2[ $x ];
									}
								}
							}
						}
					break;
					}
					// print_r( $return );exit;

				}else{
					$return = $opt[ 'data' ];
				}
			}
			
			return array( 'return' => $d, 'transformed' => $return );
		}
		
		public function _update_records( $s = array() ){
			
			if( isset( $s["where"] ) && $s["where"] && isset( $s["field_and_values"] ) && is_array( $s["field_and_values"] ) && ! empty( $s["field_and_values"] ) ){
				$w2 = "";
				if( ( isset( $s[ 'where2' ] ) && $s[ 'where2' ] ) ){
					$w2 = $s[ 'where2' ];
				}
				
				if( ! ( isset( $s[ 'database_table' ] ) && $s[ 'database_table' ] ) ){
					$s[ 'database_table' ] = $this->table_name;
				}
				
				$f = array(
					'modification_date' => array(
						'value' => date("U"),
					),
					'modified_by' => array(
						'value' => $this->class_settings['user_id'] ,
					),
					'ip_address' => array(
						'value' => get_ip_address(),
					),
				);
				if( ( isset( $s[ 'no_modification' ] ) && $s[ 'no_modification' ] ) ){
					$f = array();
				}
				$f = array_merge( $f, $s["field_and_values"] );
				
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $s[ 'database_table' ],
					'field_and_values' => $f,
					'where' => "WHERE " . $s["where"] . $w2,
				);
				//print_r($settings_array); exit;
				
				$return = update( $settings_array );
				
				if( isset( $this->refresh_cache_after_save_line_items ) && $this->refresh_cache_after_save_line_items ){
					$this->class_settings[ 'current_record_id' ] = 'pass_condition';
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					
					$this->class_settings[ 'cache_where' ] = " AND " . $s["where"];
					$this->class_settings[ 'cache_where_result' ] = 1;
					$this->class_settings[ 'updated_data' ] = $this->_get_record();
					
				}
				
				return $return;
			}
		}
		
		protected function _get_default_select2(){
			
			$where = '';
			$limit = '';
			$search_term = '';
			$mongo_where = array();
			$mongo_filter = array();
			$mongo_others = array();

			$name_field = 'name';
			if( isset( $this->class_settings[ 'name_field' ] ) && $this->class_settings[ 'name_field' ] ){
				$name_field = $this->class_settings[ 'name_field' ];
			}

			if( ! ( isset( $this->table_fields[ $name_field ] ) && $this->table_fields[ $name_field ] )  ){
				return;
			}
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				//OR `".$this->table_name."`.`".$this->table_fields["code"]."` regexp '".$search_term."'
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields[ $name_field ]."` regexp '".$search_term."' ) ";
				$mongo_where[ '$or' ][0][ $this->table_fields[ $name_field ] ][ '$regex' ] = '^'.$search_term;
				$mongo_where[ '$or' ][0][ $this->table_fields[ $name_field ] ][ '$options' ] = 'i';
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
				$mongo_filter[ 'limit' ] = get_default_select2_limit();
			}

			if( ( isset( $_GET[ 'exclude' ] ) && $_GET[ 'exclude' ] ) || ( isset( $_GET[ 'include' ] ) && $_GET[ 'include' ] ) ){
				$filter = array( 'exclude' => ( isset( $_GET[ 'exclude' ] ) ? $_GET[ 'exclude' ] : '' ), 'include' => ( isset( $_GET[ 'include' ] ) ? $_GET[ 'include' ] : '' ) );

				foreach( $filter as $f1 => $f2 ){
					
					if( $f2 ){
						$fil = '';
						switch( $f1 ){
						case 'exclude':
							$fil = '<>';
						break;
						case 'include':
							$fil = '=';
						break;
						}

						$z = preg_split( '(-|:)', $f2 );
						end( $z );
						if( ! $z[ key( $z ) ] )unset( $z[ key( $z ) ] );
						$x =  array_chunk( $z, 2 );

						$xx =  array_combine( array_column( $x, 0 ), array_column( $x, 1 ) );

						if( ! empty( $xx ) ){
							foreach( $xx as $kk => $vv ){
								switch( $kk ){
								case 'id':
								case 'created_by':
								case 'creation_date':
								case 'modified_by':
								case 'modified_date':
									$where .= " AND `".$this->table_name."`.`". $kk ."` ". $fil ." '".$vv."' ";
								break;
								default:
									if( isset( $this->table_fields[ $kk ] ) && $this->table_fields[ $kk ] ){
										$where .= " AND `".$this->table_name."`.`".$this->table_fields[ $kk ]."` ". $fil ." '".$vv."' ";
									}
								break;
								}
							}
						}
					}
				}
			}
			
			if( isset( $_GET[ "all" ] ) && $_GET[ "all" ] ){
				$limit = "";
				$mongo_filter[ 'limit' ] = "";
			}
			
			if( isset( $_GET[ "check_access" ] ) && $_GET[ "check_access" ] ){
				$access = get_accessed_functions();
				$super = 0;
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
				
				if( ! $super ){
					switch( $this->table_name ){
					case "state_list":
						
						if( isset( $access["states"] ) && ! empty( $access["states"] ) ){
							$ids = array();
							foreach( $access["states"] as $st => $sv ){
								$ids[] = "'". $st ."'";
							}
							$where = " AND `".$this->table_name."`.`id` IN ( ". implode(",", $ids ) ." ) ";
						}else{
							$where = " AND `".$this->table_name."`.`id` IS NULL ";
						}
					break;
					}
				}
			}
			
			$selecta = array();
			$select = "";
			
			$selecta[] = "`".$this->table_name."`.`id` ";
			$selecta[] = "`".$this->table_name."`.`serial_num` ";
			
			$mongo_filter[ 'projection' ][ '_id' ] = 0;
			$mongo_filter[ 'projection' ][ 'id' ] = 1;
			$mongo_filter[ 'projection' ][ 'serial_num' ] = 1;
			
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
				case "code":
				case $name_field:
					$selecta[] = " `".$val."` as '".$key."' ";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				break;
				}
				
				if( isset( $this->class_settings["select_fields"][ $key ] ) ){
					$selecta[] = " `".$val."` as '".$key."' ";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
					$mongo_where[ '$and' ][][ $val ] = $_GET[ $key ];
				}
				if( isset( $_POST[ $key ] ) && $_POST[ $key ] ){
					$where .= " AND `".$val."` = '".$_POST[ $key ]."' ";
					$mongo_where[ '$and' ][][ $val ] = $_POST[ $key ];
				}									
			}
			
			$select = implode(", ", $selecta );
			
			if( isset( $_GET["source"] ) && $_GET["source"] ){
				if( $this->class_settings[ 'get_select2_option_type' ] && isset( $this->class_settings[ 'get_select2_option_type' ] ) ){
					
					switch( $this->class_settings[ 'get_select2_option_type' ] ){
					case 'state_list':
						$checks = array( "country" );
						$found_check = 0;
						
						switch( $_GET["source"] ){
						case "lga_list":
							$lga_list = new cLga_list();
							
							foreach( $checks as $cv ){
								if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
									$where .= " AND `".$this->table_fields[ $cv ]."` = '".$_POST[ $lga_list->table_fields[ $cv ] ]."' ";
									$mongo_where[ '$and' ][][ $this->table_fields[ $cv ] ] = $_POST[ $lga_list->table_fields[ $cv ] ];
									$found_check = 1;
									
								}
							}
						break;
						default:
							
							foreach( $checks as $cv ){
								if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
									$where .= " AND `".$this->table_fields[ $cv ]."` = '".$_POST[ $cv ]."' ";
									$mongo_where[ '$and' ][][ $this->table_fields[ $cv ] ] = $_POST[ $cv ];
									$found_check = 1;
								}
							}
						break;
						}
					break;
					case 'lga_list':
						$checks = array( "country", "state" );
						$found_check = 0;
						
						switch( $_GET["source"] ){
						case "workflow":
						case "survey_assignment":
						case "households":
						case "community":
						case "community_list":
						case "ward_list":
						case "people":
							$c1 = 'c' . strtolower( $_GET["source"] );
							
							if( class_exists( $c1 ) ){
								$lga_list = new $c1();
								
								foreach( $checks as $cv ){
									$field = '';
									$value = '';
									
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$field = $lga_list->table_fields[ $cv ];
										
										if( isset( $this->table_fields[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
										}
										
										$value = $_POST[ $lga_list->table_fields[ $cv ] ];
									}
									
									if( ! $field ){
										if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
											$value = $_POST[ $cv ];
										}
									}
									
									if( $field ){
										$where .= " AND `".$field."` = '".$value."' ";
										$mongo_where[ '$and' ][][ $field ] = $value;
										$found_check = 1;
									}
								}
							}else{
								return array( "items" => array(), "do_not_reload_table" => 1 );
							}
						break;
						}
					break;
					case 'ward_list':
						$checks = array( "lga" );
						$found_check = 0;
						
						switch( $_GET["source"] ){
						case "workflow":
						case "survey_assignment":
						case "households":
						case "community":
						case "community_list":
						case "ward_list":
						case "people":
							$c1 = 'c' . strtolower( $_GET["source"] );
							
							if( class_exists( $c1 ) ){
								$lga_list = new $c1();
								
								foreach( $checks as $cv ){
									
									$field = '';
									$value = '';
									
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$field = $lga_list->table_fields[ $cv ];
										
										if( isset( $this->table_fields[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
										}
										
										$value = $_POST[ $lga_list->table_fields[ $cv ] ];
									}
									
									if( ! $field ){
										if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
											$value = $_POST[ $cv ];
										}
									}
									
									if( $field ){
										$where .= " AND `".$field."` = '".$value."' ";
										$mongo_where[ '$and' ][][ $field ] = $value;
										$found_check = 1;
									}
								}
							}else{
								return array( "items" => array(), "do_not_reload_table" => 1 );
							}
						break;
						}
					break;
					case 'community_list':
						$checks = array( "ward" );
						$found_check = 0;
						
						switch( $_GET["source"] ){
						case "workflow":
						case "survey_assignment":
						case "community":
						case "households":
							$c1 = 'c' . strtolower( $_GET["source"] );
							
							if( class_exists( $c1 ) ){
								$lga_list = new $c1();
								
								foreach( $checks as $cv ){
									
									$field = '';
									$value = '';
									
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$field = $lga_list->table_fields[ $cv ];
										
										if( isset( $this->table_fields[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
										}
										
										$value = $_POST[ $lga_list->table_fields[ $cv ] ];
									}
									
									if( ! $field ){
										if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
											$value = $_POST[ $cv ];
										}
									}
									
									if( $field ){
										$where .= " AND `".$field."` = '".$value."' ";
										$mongo_where[ '$and' ][][ $field ] = $value;
										$found_check = 1;
									}
									
								}
							}
						break;
						}
						
						if( ! $found_check ){
							return array( "items" => array(), "do_not_reload_table" => 1 );
						}
					break;
					default:
						if( isset( $_GET["source_check"] ) && $_GET["source_check"] ){
							$checks = explode(",", $_GET["source_check"] );
							
							$found_check = 0;
							
							$tb = strtolower( $_GET["source"] );
							$tb1 = "c" . ucwords( $tb );
							
							if( class_exists( $tb1 ) && ! empty( $checks ) ){
								$lga_list = new $tb1();
								
								foreach( $checks as $cv ){
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$where .= " AND `".$this->table_fields[ $cv ]."` = '".$_POST[ $lga_list->table_fields[ $cv ] ]."' ";
										$mongo_where[ '$and' ][][ $this->table_fields[ $cv ] ] = $_POST[ $lga_list->table_fields[ $cv ] ];
										$found_check = 1;
										
									}
								}
							}
						}
					break;
					}
				}
				
				/*
				if( ! $found_check ){
					return array( "items" => array(), "do_not_reload_table" => 1 );
				}
				*/
			}
			
			// print_r( $where );exit;
			$select .= ", `".$this->table_fields[ $name_field ]."` as 'text'";
			$mongo_others[ 'concat' ][ 'value' ][] = '$'.$this->table_fields[ $name_field ];
			
			if( isset( $this->table_fields["code"] ) ){
				$mongo_others[ 'concat' ][ 'value' ][] = ' - ';
				$mongo_others[ 'concat' ][ 'value' ][] = '$'.$this->table_fields["code"];
			}
			$mongo_others[ 'concat' ][ 'text' ] = 'text';

			$mongo_others[ 'use_aggregate' ] = 1;
			
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["order_by"] = " ORDER BY `".$this->table_fields[ $name_field ]."` ";
			$this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			$this->class_settings["mongo_where"] = $mongo_where;
			$this->class_settings["mongo_filter"] = $mongo_filter;
			$this->class_settings["mongo_others"] = $mongo_others;
			
			$data1 = $this->_get_records();
			
			if( isset( $this->class_settings[ 'return_indexed' ] ) && $this->class_settings[ 'return_indexed' ] ){
				$d_index = array();
				
				$indexed_fields = $this->class_settings[ 'return_indexed' ];
				
				if( ! empty( $data1 ) ){
					
					if( ! is_array( $indexed_fields ) ){
						unset( $indexed_fields );
						$indexed_fields = array( "id" );
					}
					
					$ix = count( $indexed_fields );
					
					foreach( $data1 as $d2 ){
						
						switch( $ix ){
						case 1:
							$d_index[ $d2[ $indexed_fields[ 0 ] ] ] = $d2;
						break;
						case 2:
							$d_index[ $d2[ $indexed_fields[ 0 ] ] ][ $d2[ $indexed_fields[ 1 ] ] ] = $d2;
						break;
						case 3:
							$d_index[ $d2[ $indexed_fields[ 0 ] ] ][ $d2[ $indexed_fields[ 1 ] ] ][ $d2[ $indexed_fields[ 2 ] ] ] = $d2;
						break;
						}
						
						
					}
				}
				
				return $d_index;
			}
			
			// print_r( $data1 );exit;
			return array( "items" => $data1, "do_not_reload_table" => 1 );
		}
		
		protected function _search_form(){
			$where = "";
			
			if( isset( $this->class_settings[ "additional_where" ] ) ){
				$_SESSION[ $this->table_name ][ 'filter' ][ 'additional_where' ] = $this->class_settings[ "additional_where" ];
			}else{
				unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
				if( $this->class_settings["where"] )$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $this->class_settings["where"];
			}
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( '$nwProcessor.reload_datatable' ),
			);

			$this->class_settings[ 'searching' ] = 1;
			$this->class_settings['return_form_data_only'] = 1;
			$return = $this->_save_changes();

			print_r( $return );
		}

		public function _view_details(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				return $this->_display_notification( array( "type" => 'error', "message" => 'Invalid Item ID') );
			}
			
			$js = array();
			$this->class_settings["current_record_id"] = $_POST["id"];
			
			$skip_data = 0;
			if( isset( $this->class_settings["data_items"] ) && $this->class_settings["data_items"] ){
				$skip_data = 1;
			}
			
			$html_content = '';
			if( isset( $this->class_settings["html_content"] ) && $this->class_settings["html_content"] ){
				$html_content = $this->class_settings["html_content"];
			}
			
			switch( $_POST["id"] ){
			case "preview":
				$e = $_POST;
				$this->class_settings[ 'data' ][ "preview" ] = 1;
			break;
			default:
				if( ! $skip_data ){
					$e = $this->_get_record();
				}
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/view-details.php' );
			
			if( isset( $this->class_settings["html_filename"] ) && $this->class_settings["html_filename"] ){
				$this->class_settings[ 'html' ] = $this->class_settings["html_filename"];
			}
			
			$handle = "";
			
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			if( isset( $this->class_settings["other_params"] ) ){
				$this->class_settings[ 'data' ][ "other_params" ] = $this->class_settings["other_params"];
				unset( $this->class_settings["other_params"] );
			}
			
			$t = $this->table_name;
			$this->class_settings[ 'data' ][ "reference" ] = $this->default_reference;
			$this->class_settings[ 'data' ][ "view" ] = 1;
			$this->class_settings[ 'data' ][ "table" ] = $t;
			
			if( ! isset( $this->class_settings["exclude_labels"] ) ){
				$this->class_settings[ 'data' ][ "labels" ] = $t();
			}
			
			if( ! isset( $this->class_settings["exclude_fields"] ) ){
				$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
			}
			
			if( isset( $this->custom_fields ) ){
				$this->class_settings[ 'data' ][ "custom_fields" ] = $this->custom_fields;
			}
			
			if( isset( $this->class_settings["data_items"] ) && $this->class_settings["data_items"] ){
				$this->class_settings[ 'data' ][ "items" ] = $this->class_settings["data_items"];
			}else{
				$this->class_settings[ 'data' ][ "items" ] = array( $this->class_settings["current_record_id"] => $e );
			}
			
			$modal = 0;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'delete_with_prompt':
				$this->class_settings[ 'data' ][ "delete_prompt" ] = 1;
				$js = array( 'set_function_click_event' );
			break;
			case 'view_invoice_app':
				$modal = 1;
				$this->class_settings[ 'data' ][ "print_preview" ] = 1;
			break;
			}
			
			if( $html_content ){
				$html = $html_content;
			}else{
				$html = $this->_get_html_view();
			}
			
			if( isset( $this->class_settings["before_html"] ) && $this->class_settings["before_html"] ){
				$html = $this->class_settings["before_html"] . $html;
			}
			if( isset( $this->class_settings["after_html"] ) && $this->class_settings["after_html"] ){
				$html .= $this->class_settings["after_html"];
			}
			
			if( isset( $this->class_settings["return_html"] ) && $this->class_settings["return_html"] ){
				$modal = 1;
			}
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				$modal = 1;
			}
			
			if( $modal ){
				$container = "#modal-replacement-handle";
				if( $handle ){
					$container = $handle;
				}
				
				return array(
					'do_not_reload_table' => 1,
					'data' => isset( $e )?$e:array(),
					'html_replacement' => $html,
					'html_replacement_selector' => $container,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js,
				);
			}
			
			if( ( isset( $this->class_settings[ "modal_default_container" ] ) && $this->class_settings[ "modal_default_container" ] ) || ! $handle ){
				unset( $this->class_settings[ "modal_default_container" ] );
				$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
				$handle = '#'.$ccx;
			}
			return $this->_launch_popup( $html, $handle, $js );
		}
		
		function _launch_popup( $html = '', $selector = '', $js = array(), $options = array() ){

			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$selector = $selector ? $selector : "#".$ccx;
			if( isset( $options["close"] ) && $options["close"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget-removal-script.php' );
				return $this->_get_html_view();
			}
			
			$title = ucwords( $this->table_name ) . " Details";
			if( isset( $this->label ) && $this->label ){
				$title = ucwords( $this->label ) . " Details";
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			
			if( isset( $this->class_settings["modal_dialog_style"] ) && $this->class_settings["modal_dialog_style"] ){
				$this->class_settings[ 'data' ]["modal_dialog_style"] = $this->class_settings["modal_dialog_style"];
			}
			
			if( isset( $this->class_settings["modal_dialog_class"] ) && $this->class_settings["modal_dialog_class"] ){
				$this->class_settings[ 'data' ]["modal_dialog_class"] = $this->class_settings["modal_dialog_class"];
			}
			
			if( isset( $this->class_settings["modal_dialog_static"] ) ){
				$this->class_settings[ 'data' ]["modal_dialog_static"] = $this->class_settings["modal_dialog_static"];
			}
			
			if( isset( $this->class_settings["modal_callback"] ) && $this->class_settings["modal_callback"] ){
				$this->class_settings[ 'data' ]["modal_callback"] = $this->class_settings["modal_callback"];
				unset( $this->class_settings["modal_callback"] );
			}
			
			if( isset( $this->class_settings["modal_title"] ) && $this->class_settings["modal_title"] ){
				$title = $this->class_settings["modal_title"];
			}
			
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'data' => isset( $options["data"] )?$options["data"]:array(),
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => $selector,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		protected function _search_customer_call_log(){
			$where = "";
			$filename = 'item-list.php';
			$title = '';
			$filter = 0;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$check_for_reference = 0;
			
			if( isset( $this->options[ $action_to_perform ]["check_for_reference"] ) ){
				$check_for_reference = $this->options[ $action_to_perform ]["check_for_reference"];
			}
			
			switch( $action_to_perform ){
			case 'search_list2':
				$filter = 1;
			case 'search_list':
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
			break;
			default:
				$check_for_reference = 1;
			break;
			}
			
			if( $check_for_reference ){
				if( ! ( isset( $_POST[ $this->default_reference ] ) && $_POST[ $this->default_reference ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.$this->customer_source.' first';
					return $err->error();
				}
				
				$where = " AND `".$this->table_name."`.`".$this->table_fields[ $this->default_reference ]."` = '".$_POST[ $this->default_reference ]."' ";
			}
			
			$allow = 0;
			$get_count = 0;
				
			if( $filter && isset( $_POST[ "field" ] ) && $_POST[ "field" ] ){
				
				$field = $_POST[ "field" ];
				
				switch( $field ){
				case "_most_recent":
					
					if( isset( $_POST[ "search_number" ] ) && intval( $_POST[ "search_number" ] ) ){
						$limit = intval( $_POST[ "search_number" ] );
						$allow = 1;
						$get_count = 1;
						
						$this->class_settings["limit"] = " LIMIT " . $limit;
						
						$this->class_settings["order_by"] = " ORDER BY `".$this->table_name."`.`modification_date` DESC ";
						$title = "Most Recent ".$limit." record(s)";
					}else{
						$where = '';	//prevents requesting for all records
					}
					
				break;
				default:
				
					if( isset( $this->table_fields[ $_POST[ "field" ] ] ) ){
						
						$val = $this->table_fields[ $_POST[ "field" ] ];
						$t = $this->table_name;
						$lbl = $t();
						
						if( isset( $lbl[ $val ][ "form_field" ] ) ){
							
							switch( $lbl[ $val ][ "form_field" ] ){
							case "date-5":
								
								if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
									$where .= " AND `".$val."` >= " . convert_date_to_timestamp( $_POST["start_date"], 1 );
								}
								
								if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
									$where .= " AND `".$val."` <= " . convert_date_to_timestamp( $_POST["end_date"], 1 );
								}
								
								if( ! $where )$allow = 1;
							break;
							default:
								
								if( isset( $_POST[ "search" ] ) ){
									if( $_POST[ "search" ] ){
										$where = " AND `".$this->table_name."`.`".$val."` REGEXP '".$_POST[ "search" ]."' ";
									}else{
										
										if( isset( $_POST[ "search_select" ] ) && $_POST[ "search_select" ] ){
											$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_select" ]."' ";
										}else{
											if( isset( $_POST[ "search_number" ] ) && $_POST[ "search_number" ] ){
												$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_number" ]."' ";
											}else{
												$allow = 1;
											}
										}
										
									}
								}
							break;
							}
						}
					}
					
				}
			
				if( ! ( $where || $allow ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Search Criteria</h4>Please try again';
					return $err->error();
				}
				
				unset( $_POST["start_date"] );
				unset( $_POST["end_date"] );
			}
			
			$hidden_field = array();
			if( isset( $_POST["hidden_field"] ) && is_array( $_POST["hidden_field"] ) && ! empty( $_POST["hidden_field"] ) ){
				foreach( $this->table_fields as $k => $v ){
					if( isset( $_POST["hidden_field"][ $k ] ) ){
						$where .= " AND `".$this->table_fields[ $k ]."` = '". $_POST["hidden_field"][ $k ] ."' ";
					}
				}
				
				$kk = "start_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$kk = "end_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$hidden_field = $_POST["hidden_field"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"], 1 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				else $where = " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"], 2 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
					else $where = " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			
			$this->class_settings["where"] = $where;
			$data = $this->_get_all_customer_call_log();
			
			if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
				return $data;
			}
			
			if( empty( $data ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No '.ucwords( $this->label ).' Record(s)</h4>';
				$return = $err->error();
				$returning_html_data = $return["html"];
			}else{
				if( $get_count ){
					$this->class_settings["overide_select"] = " COUNT(*) as 'count' ";
					$this->class_settings["limit"] = "";
					$this->class_settings["order_by"] = "";
					$this->class_settings["where"] = $where;
					$ct = $this->_get_all_customer_call_log();
					if( isset( $ct[0]['count'] ) ){
						$title .= " of " . number_format( doubleval( $ct[0]['count'] ), 0 ) . " record(s)";
					}
				}
				
				if( isset( $this->plugin ) && $this->plugin ){
					$this->class_settings[ 'data' ][ "plugin" ] = $this->plugin;
				}
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$this->class_settings[ 'data' ][ "items" ] = $data;
				
				$tb = $this->table_name;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'table_fields' ] = $this->table_fields;
				$this->class_settings[ 'data' ][ 'table_labels' ] = $tb();
				$this->class_settings[ 'data' ][ 'action' ] = $action_to_perform;
				$this->class_settings[ 'data' ][ 'hidden_field' ] = $hidden_field;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				
				if( isset( $this->use_package ) && $this->use_package ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}else if( isset( $this->package_name ) && $this->package_name ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->package_name.'/'.$this->table_name.'/'.$filename );
				}
				
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		protected function _new_popup_form(){
			$err = 0;
			$c_name = "";
			$pre_html = '';
			
			$px = isset( $this->class_settings[ "form_params" ] )?$this->class_settings[ "form_params" ]:array();
			if( is_array( $px ) && ! empty( $px ) ){
				foreach( $px as $pxv ){
					if( isset( $pxv["key"] ) && isset( $pxv["type"] ) ){
						switch( $pxv["type"] ){
						case "class_settings":
							$this->class_settings[ $pxv["key"] ] = $pxv["value"];
						break;
						case "field_label":
						case "attributes":
						case "form_values_important":
							$this->class_settings[ $pxv["type"] ][ $pxv["key"] ] = $pxv["value"];
						break;
						case "hidden_records_css":
						case "hidden_records":
							$this->class_settings[ $pxv["type"] ][ $pxv["key"] ] = 1;
						break;
						case "un_hide":
							unset( $this->class_settings[ "hidden_records_css" ][ $pxv["key"] ] );
							unset( $this->class_settings[ "hidden_records" ][ $pxv["key"] ] );
						break;
						case "custom_form_fields":
							$this->class_settings["attributes"]["custom_form_fields"][ 'field_ids' ][] = $pxv["key"];
							$this->class_settings["attributes"]["custom_form_fields"][ 'form_label' ][ $pxv["key"] ] = $pxv["value"];
						break;
						}
					}
				}
			}
			
			if( ! ( isset( $this->class_settings["skip_link"] ) && $this->class_settings["skip_link"] ) ){
				
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					$err = 1;
				}else{
					switch( $this->class_settings["action_to_perform"] ){
					case "new_popup_form":
						//$c = get_record_details( array( "id" => $_POST["id"], "table" => $this->customer_source ) );
						//$c = get_customers_details( array( "id" => $_POST["id"], "table" => $this->customer_source ) );
						$c = get_name_of_referenced_record( array( "id" => $_POST["id"], "table" => $this->customer_source, "return_data" => 1 ) );
						
						if( isset( $c[ "id" ] ) && $c[ "id" ] ){
							
							switch( $this->customer_source ){
							case "users":
								$c_name = $c[ "firstname" ] . ' ' . $c[ "lastname" ];
							break;
							default:
								$c_name = isset( $c[ "name" ] )?$c[ "name" ]:'';
							break;
							}
							
							if( ! ( isset( $this->class_settings["skip_pre_html"] ) && $this->class_settings["skip_pre_html"] ) ){
								if( $c_name ){
									$pre_html .= '<h5 class="container"><b>'. $c_name .'</b></h5>';
								}
							}
						}else{
							$err = 1;
						}
					break;
					}
				}
				
				if( $err ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.( $this->customer_source ).' first';
					return $err->error();
				}
				
				if( ! ( isset( $this->class_settings["override_defaults"] ) && $this->class_settings["override_defaults"] ) ){
					if( isset( $this->table_fields[ $this->default_reference ] ) ){
						$this->class_settings[ "form_values_important" ][ $this->table_fields[ $this->default_reference ] ] = $_POST["id"];
						$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ $this->default_reference ] ] = 1;
					}
					
					if( isset( $this->table_fields[ "date" ] ) ){
						if( ! ( isset( $this->class_settings["skip_link_date"] ) && $this->class_settings["skip_link_date"] ) ){
							$this->class_settings[ "form_values_important" ][ $this->table_fields[ "date" ] ] = date("U");
							$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ "date" ] ] = 1;
						}
					}
					if( isset( $this->table_fields[ "appointment" ] ) ){
						$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ "appointment" ] ] = 1;
					}
					
					if( isset( $this->class_settings[ "not_hidden_records_css" ] ) && is_array( $this->class_settings[ "not_hidden_records_css" ] ) && ! empty( $this->class_settings[ "not_hidden_records_css" ] ) ){
						foreach( $this->class_settings[ "not_hidden_records_css" ] as $key => $val ){
							unset( $this->class_settings[ "hidden_records_css" ][ $key ] );
						}
					}
					
					if( isset( $this->table_fields[ "time" ] ) && ! isset( $this->class_settings[ "skip_auto_time" ] ) ){
						$this->class_settings[ "form_values_important" ][ $this->table_fields[ "time" ] ] = date("H:i");
					}
				}
			}
			
			
			if( ! ( isset( $this->class_settings[ 'form_action' ] ) && $this->class_settings[ 'form_action' ] ) ){
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_popup';
			}
			
			$container = "#modal-replacement-handle";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'form_action' ] .= '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			if( isset( $_GET[ 'empty_container' ] ) && $_GET[ 'empty_container' ] ){
				$this->class_settings[ 'form_action' ] .= '&empty_container=' . $_GET[ 'empty_container' ];
			}

			if( ( isset( $this->class_settings[ 'form_action_params' ] ) && $this->class_settings[ 'form_action_params' ] ) ){
				$this->class_settings[ 'form_action' ] .= $this->class_settings[ 'form_action_params' ];
			}
			
			$this->class_settings['do_not_show_headings'] = 1;
			if( isset( $this->view_path ) && $this->view_path ){

				$this->class_settings[ 'html' ] = array( $this->view_path . 'new_popup_script.js' );
				$this->class_settings[ 'skip_demo' ] = 0;
				$html = $this->_get_html_view();
				// print_r( $this->view_path . 'new_popup_script.js' );exit;

				if( $html ){
					$tfl = '';
					foreach( $this->table_fields as $tgk => $tgv ){
						$tfl .= '"'. $tgk .'":"'. $tgv .'",';
					}
					$html = '$formfield = {'.$tfl.'"":""}; $formid = "'. $this->table_name .'"; '.$html;

					if( ! isset( $this->class_settings["after_html"] ) )$this->class_settings["after_html"] = '';
					
					$this->class_settings["after_html"] .= '<script>'. ( isset( $this->class_settings["after_html"] ) ? $this->class_settings["after_html"] . $html : $html ) . '</script>';
				}
			}
			$d = $this->_generate_new_data_capture_form();
			
			$html = "";
			if( isset( $d["html"] ) )$html = $d["html"];
			
			if( isset( $this->class_settings["before_html"] ) && $this->class_settings["before_html"] ){
				$html = $this->class_settings["before_html"] . $html;
			}
			$html = $pre_html . $html;
			
			if( isset( $this->class_settings["after_html"] ) && $this->class_settings["after_html"] ){
				$html .= $this->class_settings["after_html"];
			}
			
			if( isset( $_GET["nwp_mid"] ) && $_GET["nwp_mid"] ){
				$html = '<div class="row"><div class="col-md-3"></div><div class="col-md-6">'.$html . '</div></div>';
			}
			
			if( isset( $this->class_settings["no_popup"] ) && $this->class_settings["no_popup"] ){
				$this->class_settings["action_to_perform"] = "capture_version_2";
			}
			
			$label = isset( $this->label )?$this->label:ucwords( $this->table_name );
			
			if( isset( $this->class_settings["modal_popup_active"] ) && $this->class_settings["modal_popup_active"] ){
				$this->class_settings["action_to_perform"] = 'new_popup_in_popup';
			}else if( isset( $_GET["modal_popup_active"] ) && $_GET["modal_popup_active"] ){
				$this->class_settings["action_to_perform"] = 'new_popup_in_popup';
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "new_popup_in_popup":
			case "new_popup_form_in_popup":
			case "capture_version_2":
			case 'edit_popup_form_in_popup':
			
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => $container,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
				);
			break;
			case "new_popup_form_i_menu":
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'html_replacement_one' => isset( $this->class_settings["modal_title"] )?( $this->class_settings["modal_title"] ):( $c_name.": New " . $label ),
					'html_replacement_selector_one' => "#form-title",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "$.fn.hyellaIMenu.activateForm" ),
				);
			break;
			}
			
			$js = array( "prepare_new_record_form_new" , "set_function_click_event" );
			
			if( isset( $_GET["data_source"] ) ){
				switch( $_GET["data_source"] ){
				case "table2":
					$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
				break;
				}
			}
			
			if( ! ( isset( $this->class_settings["no_modal_callback"] ) && $this->class_settings["no_modal_callback"] ) ){
				if( ! ( isset( $this->class_settings["modal_callback"] ) && $this->class_settings["modal_callback"] ) ){
					$this->class_settings["modal_callback"] = '$nwProcessor.reload_datatable';
				}
			}
			
			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$con = '#'.$ccx;
			if( ( isset( $this->class_settings["modal_container"] ) && $this->class_settings["modal_container"] ) ){
				$con = $this->class_settings["modal_container"];
			}
			
			return $this->_launch_popup( $html, $con, $js );
			/*
			if( isset( $this->class_settings["modal_dialog_style"] ) && $this->class_settings["modal_dialog_style"] ){
				$this->class_settings[ 'data' ]["modal_dialog_style"] = $this->class_settings["modal_dialog_style"];
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = isset( $this->class_settings["modal_title"] )?( $this->class_settings["modal_title"] ):( $c_name.": New " . $label );
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => ,
			);
			*/
			
		}
		
		protected function _delete_app_record(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'delete_app_manager':
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Record</h4>Please select a record by clicking on it first';
					return $err->error();
				}
				
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				
				$ids = explode( ',', $_POST["id"] );
				$_POST["ids"] = implode( ':::', $ids );
				$return = $this->_delete_records();
				if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
					unset( $return["html"] );
					$return["status"] = "new-status";
					
					foreach( $ids as $id )$return["html_removals"][] = "#".$id;
				}
				return $return;
			break;
			case 'delete_with_query':
				if( ! ( isset( $this->class_settings["where"] ) && $this->class_settings["where"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Query</h4>Please specify the query';
					return $err->error();
				}
				$where = $this->class_settings["where"];
				
				if( ! $where )return 0;
				//30-mar-23
				$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_name."`.`record_status` = '0', `".$this->table_name."`.`modification_date` = ". date("U") .", `".$this->table_name."`.`modified_by` = '". $this->class_settings["user_id"] ."' WHERE ".$where;
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				return execute_sql_query($query_settings);
			break;
			}
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				$return = $this->_delete_records();
				if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
					unset( $return["html"] );
					$return["status"] = "new-status";
					
					$return["html_removal"] = "#".$return['deleted_record_id'];
					$return["javascript_functions"] = array( "nwcustomer_call_log.emptyNewItem" );
					return $return;
					
				}
			}
		}
		
		protected function _save_app_changes(){
			$return = array();
			$js = array( 'nwCustomer_call_log.init' );
			$new = 0;
			
			$error_msg = '';
			$filename = "item-list.php";
			$no_skip = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				$no_skip = 0;
				$filename = "view-details.php";
			break;
			}
			
			
			$filename_full = array();
			if( isset( $this->class_settings["html_filename_full"] ) && $this->class_settings["html_filename_full"] ){
				$filename_full = $this->class_settings["html_filename_full"];
			}
			
			$mobile_framework = '';
			if( isset( $_POST["mobile_framework"] ) && $_POST["mobile_framework"] ){
				$mobile_framework = $_POST["mobile_framework"];
			}
			
			if( isset( $_POST['id'] ) ){
				
				if( $no_skip ){
					foreach( $this->table_fields as $key => $val ){
						if( isset( $_POST[ $key ] ) ){
							$_POST[ $val ] = $_POST[ $key ];
							unset( $_POST[ $key ] );
						}
					}
					
					if( ! $_POST['id'] ){
						//new mode
						$js[] = 'nwcustomer_call_log.reClick';
						$new = 1;
					}
					
					$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
					$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
					$_POST[ "table" ] = $this->table_name;
					$_POST[ "processing" ] = md5(1);
					if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
						define('SKIP_USE_OF_FORM_TOKEN', 1);
				}
				
				$return = $this->_save_changes();
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
					
					$handle = 'modal-replacement-handle';
					if( isset( $this->class_settings[ 'use_selector' ] ) && $this->class_settings[ 'use_selector' ] ){
						if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
							$handle = $this->class_settings[ 'html_replacement_selector' ];
						}
					}
				
					if( isset( $return['record']['id'] ) && $return['record']['id'] ){
						$e = $return['record'];
					}else{
						$this->class_settings[ 'current_record_id' ] = $return['saved_record_id'];
						unset( $this->class_settings[ 'do_not_check_cache' ] );
						$e = $this->_get_customer_call_log();
					}
					
					
					if( isset( $e["id"] ) && $return['saved_record_id'] == $e["id"] ){
						$return["data"] = $e;
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
						if( ! empty( $filename_full ) ){
							$this->class_settings[ 'html' ] = $filename_full;
						}
						
						switch( $this->class_settings["action_to_perform"] ){
						case 'save_new_popup':
						
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/'.$filename );
							
							if( ! empty( $filename_full ) ){
								$this->class_settings[ 'html' ] = $filename_full;
							}
							
							$t = $this->table_name;
							
							if( isset( $this->class_settings["validate_override"] ) ){
								$this->class_settings[ 'data' ][ "validate_override" ] = $this->class_settings["validate_override"];
							}
							
							if( isset( $this->class_settings["other_params"] ) ){
								$this->class_settings[ 'data' ][ "other_params" ] = $this->class_settings["other_params"];
								unset( $this->class_settings["other_params"] );
							}
							
							if( isset( $this->plugin ) && $this->plugin ){
								$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
							}
							
							$this->class_settings[ 'data' ][ "validate_time" ] = get_validate_new_record_count_down_settings();
							$this->class_settings[ 'data' ][ "validate_prompt" ] = get_validate_new_record_settings();
							$this->class_settings[ 'data' ][ "reference" ] = $this->default_reference;
							$this->class_settings[ 'data' ][ "html_replacement_selector" ] = $handle;
							$this->class_settings[ 'data' ][ "table" ] = $t;
							$this->class_settings[ 'data' ][ "labels" ] = $t();
							$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
							$this->class_settings[ 'data' ][ "items" ] = array( $return['saved_record_id'] => $e );
							$this->class_settings[ 'data' ][ "mobile_framework" ] = $mobile_framework;
							
							if( isset( $this->custom_fields ) ){
								$this->class_settings[ 'data' ][ "custom_fields" ] = $this->custom_fields;
							}
							
							$returning_html_data = $this->_get_html_view();
							
							$js = array( "set_function_click_event" );
							
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							$return["html_replacement_selector"] = "#" . $handle;
							$return["html_replacement"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						break;
						default:
							$return = array_merge( $this->_display_notification( array( "type" => "success", "message" => '<h4>Successful Update</h4>', "do_not_display" => 1, "html_replacement_selector" => "#" . $handle ) ), $return );
						break;
						}
						/* 
						//disabled on 24-jan-2021, due to effect on datatable as seen in Outpatient->Update Status->Change Queue
						$this->class_settings[ 'data' ]["item"] = $e;
						$returning_html_data = $this->_get_html_view();
						
						if( $new ){
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							$return["html_prepend_selector"] = "#recent-expenses tbody";
							$return["html_prepend"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						}
						
						
						$return["html_replace_selector"] = "#".$e["id"];
						$return["html_replace"] = $returning_html_data;
						 */
						unset( $return["html"] );
						$return["status"] = "new-status";
						
						$return["javascript_functions"] = $js;
						return $return;
					}
					
				}
				
			}else{
				$error_msg = '<h4>Invalid ID Field</h4>';
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
			
			return $return;
		}
		
		protected function _display_app_view(){
			$file = 'display-app-view';
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'view_version_2_in_active':
			case 'view_version_2_active':
			case 'view_version_2_completed':
			case 'view_version_2_in_theatre':
			case "view_version_2_pending_approval_lab":
			case "view_version_2_pending_approval_rad":
			case 'view_version_2_dispensed':
			case 'view_version_2_cancelled':
			case 'view_version_2_pending':
			case 'view_version_3_dispensed':
			case 'view_version_3_cancelled':
			case 'view_version_3_pending':
			case 'view_version_3':
			case 'view_version_2':
			case 'view_version_2_referred':
			case 'view_version_2_lab':
			case 'view_version_2_rad':
			case 'view_version_2_pending':
			case 'view_version_2_pending_referred':
			case 'view_version_2_complete_referred':
			case 'view_version_2_complete':
			case 'view_version_2_complete_lab':
			case 'view_version_2_complete_rad':
			case 'view_version_2_pending_lab':
			case 'view_version_2_pending_rad':
			case 'view_version_2_inactive':
			case 'view_version_2_inactive_referred':
			case 'view_version_2_inactive_lab':
			case 'view_version_2_inactive_rad':
			case 'view_pending_investigation_result_rad':
			case 'view_pending_investigation_result_lab':
			case 'view_pending_investigation_result_referred':
			case 'view_pending_investigation_result':
			case 'view_version2_report':
			case "nursing_prescription2_pending":
			case "nursing_prescription2_completed":
			case "nursing_prescription2_cancelled":
			case "nursing_prescription2_sent_to_dispensary":
				$this->class_settings[ 'data' ][ 'hide_delete_button' ] = 1;
				$this->class_settings[ 'data' ][ 'hide_new_button' ] = 1;
				$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			case 'display_app_manager':	
			case 'view_version2_report2':
				$t = $this->table_name;
				
				$this->class_settings[ 'data' ][ 'table_label' ] = $t();
				$this->class_settings[ 'data' ][ 'table_fields' ] = $this->table_fields;
				$this->class_settings[ 'data' ][ 'label' ] = $this->label;
				
				if( isset( $this->class_settings["hidden_table_fields"] ) ){
					$this->class_settings[ 'data' ]["hidden_table_fields"] = $this->class_settings["hidden_table_fields"];
					unset( $this->class_settings["hidden_table_fields"] );
				}
				
				$file = 'display-app-manager';
			break;
			case 'display_app_view':
				//$this->class_settings[ 'data' ]['customer_call_log'] = $this->_get_all_customer_call_log();
				$this->class_settings[ 'data' ]['reference_value'] = isset( $this->class_settings["reference"] )?$this->class_settings["reference"]:'';
				$this->class_settings[ 'data' ]['reference'] = $this->default_reference;
				$this->class_settings[ 'data' ]['title'] = 'Add New ' . $this->label;
				$this->class_settings[ 'data' ]['label'] = 'New ' . $this->label;
			break;
			}
			// print_r( $this->plugin );exit;
			
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$this->class_settings[ 'data' ][ 'hide_delete_button' ] = 1;
				$this->class_settings[ 'data' ][ 'hide_new_button' ] = 1;
			}
			
			$this->class_settings[ 'data' ]['table'] = $this->table_name;
			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ]['plugin'] = $this->plugin;
			}

			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = "#".$ccx;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/'.$file );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.refreshAcitveTab' ) 
			);
		}
		
		protected function _get_all_customer_call_log( $fparams = array() ){
			return $this->_get_records( $fparams );
		}
		
		public function _get_records( $fparams = array() ){
			$tb = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name;
			}
			if( isset( $fparams["table_name"] ) && $fparams["table_name"] ){
				$tb = $fparams["table_name"];
			}
			
			if( isset( $this->class_settings["overide_select"] ) && $this->class_settings["overide_select"] ){
				$select = $this->class_settings["overide_select"];
				unset( $this->class_settings["overide_select"] );
				$this->class_settings["no_order"] = 1;
			}else if( isset( $this->class_settings["custom_select"] ) && $this->class_settings["custom_select"] ){
				$this->class_settings["no_order"] = 1;
				$select = $this->class_settings["custom_select"];
				unset( $this->class_settings["custom_select"] );
			}else{
				$o1 = array();
				if( isset( $fparams["select_fields"] ) && $fparams["select_fields"] ){
					$o1["fields"] = $fparams["select_fields"];
				}
				
				$select = $this->_get_select( $o1 );
			}
			
			$where = "";
			if( isset( $this->class_settings["where"] ) && $this->class_settings["where"] ){
				$where = $this->class_settings["where"];
				unset( $this->class_settings["where"] );
			}
		
			$join = "";
			if( isset( $this->class_settings["join"] ) && $this->class_settings["join"] ){
				$join = $this->class_settings["join"];
				unset( $this->class_settings["join"] );
			}
			
			$group = "";
			if( isset( $this->class_settings["group"] ) && $this->class_settings["group"] ){
				$group = $this->class_settings["group"];
				unset( $this->class_settings["group"] );
			}
			
			$e_select = "";
			if( isset( $this->class_settings["select"] ) && $this->class_settings["select"] ){
				$e_select = $this->class_settings["select"];
				unset( $this->class_settings["select"] );
			}
			
			$limit = "";
			if( isset( $this->class_settings["limit"] ) && $this->class_settings["limit"] ){
				$limit = $this->class_settings["limit"];
				unset( $this->class_settings["limit"] );
			}
			
			$from = " FROM `".$this->class_settings['database_name']."`.`".$tb."` ";
			if( isset( $this->class_settings["from"] ) && $this->class_settings["from"] ){
				$from = $this->class_settings["from"];
				unset( $this->class_settings["from"] );
			}
			
			$order = "";
			if( isset( $this->table_fields["date"] ) ){
				$order = " ORDER BY `".$tb."`.`".$this->table_fields["date"]."` DESC ";
			}
			
			$fwhere = " WHERE `".$tb."`.`record_status` = '1' ";
			if( isset( $this->class_settings["no_where"] ) && $this->class_settings["no_where"] ){
				$fwhere = '';
				unset( $this->class_settings["no_where"] );
				$order = "";
			}
			
			if( isset( $this->class_settings["order_by"] ) && $this->class_settings["order_by"] ){
				$order = $this->class_settings["order_by"];
				unset( $this->class_settings["order_by"] );
			}
			
			if( isset( $this->class_settings["no_order"] ) && $this->class_settings["no_order"] ){
				$order = "";
				unset( $this->class_settings["no_order"] );
			}
			
			if( isset( $this->class_settings["order_by2"] ) && $this->class_settings["order_by2"] ){
				$order = $this->class_settings["order_by2"];
				unset( $this->class_settings["order_by2"] );
			}
			
				
			$qtype = 'SELECT';
			$view = '';
			if( isset( $this->class_settings["view_name"] ) && $this->class_settings["view_name"] ){
				$view = " CREATE OR REPLACE VIEW " . $this->class_settings["view_name"] . " AS ";
				unset( $this->class_settings["view_name"] );
				$qtype = 'EXECUTE';
			}
			
			$use_cache = 1;
			if( isset( $fparams["cache"] ) ){
				$use_cache = intval( $fparams["cache"] );
			}
			
			$skip_log = 0;
			if( isset( $fparams["skip_log"] ) ){
				$skip_log = intval( $fparams["skip_log"] );
			}
			
			$index_field = '';
			if( isset( $fparams["index_field"] ) ){
				$index_field = $fparams["index_field"];
			}
			
			$index_type = '';
			if( isset( $fparams["index_type"] ) ){
				$index_type = $fparams["index_type"];
			}
			
			$query = $view . "SELECT ".$select.$e_select.$from.$join.$fwhere.$where.$group.$order.$limit;
			if( isset( $fparams["query"] ) && $fparams["query"] ){
				$query = $fparams["query"];
			}
			
			if( isset( $fparams["return_query"] ) && $fparams["return_query"] ){
				return $query;
			}
			//echo $query . '<br /><br />'; exit;
			/*
			switch( $tb ){
			case "labour_outcome":
			case "obstetric_history":
			case "investigation":
			case "users_disciplinary_history":
			case "vitals2":
			echo $query; exit;
			break;
			}
			*/

			if( $use_cache == 2 ){
				// echo $query; exit;
			}
			
			$tables = array( $tb );
			if( isset( $this->class_settings["tables"] ) && is_array( $this->class_settings["tables"] ) ){
				$tables = $this->class_settings["tables"];
			}

			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => $qtype,
				'set_memcache' => $use_cache,
				'tables' => $tables,
				'index_field' => $index_field,
				'index_type' => $index_type,
				'skip_log' => $skip_log,
			);
			
			$query_settings[ 'parent_tb' ] = $this->table_name;
			$query_settings[ 'table_name' ] = $tb;
			$query_settings[ 'action_to_perform' ] = $this->class_settings[ 'action_to_perform' ];
			if( isset( $this->plugin ) && $this->plugin ){
				$query_settings[ 'plugin' ] = $this->plugin;
			}
			$sql_result = execute_sql_query($query_settings);

			switch( DB_MODE ){
			case 'mssql':
				$query_settings['query'] = str_replace( '`'.$query_settings["database"].'`', '"'.$query_settings["database"].'".'.'"dbo"', $query_settings['query'] );
				
				$query_settings['query'] = str_replace("`", '"', $query_settings['query'] );
			break;
			}
			$this->last_query = $query_settings;
			
			return $sql_result;
		}
		
		public $last_query = array();
		
		protected function _display_all_records_full_view(){
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			/*
			if( isset( $this->class_settings[ "current_tab" ] ) ){
				switch( $this->class_settings[ "current_tab" ] ){
				case "ssr":
				case "nsr":
					if( isset( $this->sr_table ) ){
						$this->table_name = $this->sr_table;
					}
				break;
				}
			}
			*/
			$rdata = array();
			$js = array( 'nwResizeWindow.resizeWindow', '$nwProcessor.recreateDataTables', '$nwProcessor.update_column_view_state', '$nwProcessor.set_function_click_event', 'prepare_new_record_form_new' );
			$fwhere = isset( $this->class_settings[ 'filter' ][ 'where' ] )?$this->class_settings[ 'filter' ][ 'where' ]:'';
			
			switch( $action_to_perform ){
			case "display_selected_records_frontend":
				$this->class_settings[ "hide_title" ] = 1;
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "frontend" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				
				if( isset( $_GET["show_title"] ) && $_GET["show_title"] ){
					$this->class_settings[ "hide_title" ] = 0;
				}
				if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
					$js = array_merge( array( '$.fn.cCallBack.displayTabTitle' ), $js );
					$rdata["tab_title"] = rawurldecode( $_GET["menu_title"] );
					
				}else if( isset( $_GET["prefix_title"] ) && $_GET["prefix_title"] ){
					$this->class_settings[ "prefix_title" ] = rawurldecode( $_GET["prefix_title"] ) . ': ';
				}
				
				if( isset( $_GET["no_buttons"] ) && $_GET["no_buttons"] ){
					
					$this->datatable_settings[ "show_refresh_cache" ] = 0;
					$this->datatable_settings[ "show_add_new" ] = 0;
					$this->datatable_settings[ "show_edit_button" ] = 0;
					$this->datatable_settings[ "show_delete_button" ] = 0;
				}
				
				if( isset( $_GET["field"] ) && isset( $_POST["id"] ) && $_POST["id"] && isset( $this->table_fields[ $_GET["field"] ] ) ){
					$tb = isset( $this->db_table_name )?$this->db_table_name:$this->table_name;
					$fwhere .= " AND `".$tb."`.`".$this->table_fields[ $_GET["field"] ]."` = '". $_POST["id"] ."' ";
					
					$this->datatable_settings[ "skip_fields" ][ $_GET["field"] ] = $this->table_fields[ $_GET["field"] ];
					if( ! isset( $this->datatable_settings["button_params"] ) ){
						$this->datatable_settings["button_params"] = '';
					}
					
					$this->datatable_settings["button_params"] .= '&'.$_GET["field"].'=' . $_POST["id"];
				}
				
				$this->class_settings[ 'filter' ][ 'where' ] = $fwhere;
				
			break;
			case "display_my_records_frontend":
				$this->class_settings[ "frontend" ] = 1;
				$where = " AND `".$this->table_name."`.`created_by` = '". $this->class_settings[ 'user_id' ] ."' ";
				
				$this->class_settings['title'] = 'My ' . $this->label;
				
				if( ! isset( $this->class_settings[ 'filter' ][ 'where' ] ) ){
					$this->class_settings[ 'filter' ][ 'where' ] = '';
				}
				$this->class_settings[ 'filter' ][ 'where' ] .= $where;
			break;
			case "display_shared_records":
			case "display_shared_with_me":
			case "display_shared_with_me_unread":
			case "display_shared_with_me_read":
				$this->class_settings[ "frontend" ] = 1;
				
				//$this->class_settings['title'] = 'My ' . $this->label;
				
				$share = new cShare();
				$join_where = " AND ( `".$share->table_name."`.`". $share->table_fields["share"] ."` = '". $this->class_settings[ 'user_id' ] ."' AND `".$share->table_name."`.`". $share->table_fields["share_table"] ."` = 'users' ) ";
				
				$dt = 1;
				$join_prefix = '';
				
				switch( $action_to_perform ){
				case "display_shared_with_me_unread":
					$join_where .= " AND `".$share->table_name."`.`". $share->table_fields["status"] ."` = 'unread' ";
					$this->class_settings['suffix_title'] = ' - Unread';
				break;
				case "display_shared_with_me_read":
					$join_where .= " AND `".$share->table_name."`.`". $share->table_fields["status"] ."` = 'read' ";
					$this->class_settings['suffix_title'] = ' - Read';
				break;
				case "display_shared_records":
					$dt = 0;
					$status = isset( $_GET['r_status'] )? $_GET['r_status'] : '';
					$r_type = isset( $_GET['r_type'] )? $_GET['r_type'] : '';
					$where = "";
					
					$ftype2 = 1;
					$ftype = $r_type;
					switch( $r_type ){
					case "assigned":
						switch( $status ){
						case "unshared":
							$where = " AND `".$share->table_name."`.`id` IS NULL ";
							
							$join_prefix = " LEFT";
							$join_where = " AND `".$share->table_name."`.`". $share->table_fields["type"] ."` IN ( '".$ftype."', 'reviewed' ) ";
							
							$ftype2 = 0;
						break;
						case "shared":
						case "shared_by_me":
							$join_where = "";
						break;
						case "my_return_shared":
							$ftype2 = 0;
							$join_where = " AND `".$share->table_name."`.`". $share->table_fields["type"] ."` IN ( 'revoked', 'reviewed' ) ";
						break;
						case "return_shared":
							$join_where = "";
							$ftype = 'reviewed';
						break;
						case "my_shared":
							//$join_where = "";
							$this->class_settings['suffix_title'] = ' - Assigned to Me';
						break;
						}
						
						switch( $status ){
						case "shared_by_me":
						case "my_return_shared":
							$where .= " AND `".$share->table_name."`.`created_by` = '". $this->class_settings[ 'user_id' ] ."' ";
						break;
						}
						
					break;
					}
					
					if( $ftype2 ){
						$join_where .= " AND `".$share->table_name."`.`". $share->table_fields["type"] ."` = '".$ftype."' ";
					}
					
					if( isset( $this->datatable_settings["datatable_split_screen"]["action"] ) && $this->datatable_settings["datatable_split_screen"]["action"] ){
						$this->datatable_settings["datatable_split_screen"]["action"] = '?action='.$share->table_name.'&todo=split_screen_view&nwp_share_type='. $r_type .'&nwp_share_status=' . $status . '&oaction=' . rawurlencode( $this->datatable_settings["datatable_split_screen"]["action"] );
					}
					
					if( ! isset( $this->class_settings[ 'filter' ][ 'where' ] ) ){
						$this->class_settings[ 'filter' ][ 'where' ] = '';
					}
					$this->class_settings[ 'filter' ][ 'where' ] .= $where;
					
					
					if( ! ( isset( $this->class_settings[ 'custom_ds_control' ] ) && $this->class_settings[ 'custom_ds_control' ] ) ){
						$this->datatable_settings[ "show_add_new" ] = 0;
						$this->datatable_settings[ "show_edit_button" ] = 0;
						$this->datatable_settings[ "show_delete_button" ] = 0;
					}
				break;
				}
				
				if( $this->table_name == $share->table_name ){
					$this->class_settings['title'] = 'Shared with Me';
					$this->class_settings[ 'filter' ][ 'where' ] = $join_where;
				}else{
					if( $dt ){
						$this->class_settings['title'] = $this->label . ' - Shared with Me';
						$this->class_settings[ 'filter' ][ 'select' ] = " `".$this->table_name."`.*, `".$share->table_name."`.`". $share->table_fields["date"] ."` as '". $this->table_fields["date"] ."' ";
					}
					
					$this->datatable_settings['show_selection']["index_fields"]["share_id"] = "share_id";
					
					if( ! isset( $this->class_settings[ 'filter' ][ 'e_select' ] ) ){
						$this->class_settings[ 'filter' ][ 'e_select' ] = '';
					}
					$this->class_settings[ 'filter' ][ 'e_select' ] .= ", `".$share->table_name."`.`id` as 'share_id' ";
					
					$this->class_settings[ 'filter' ][ 'join' ] = $join_prefix . " JOIN `". $this->class_settings[ 'database_name' ] ."`.`".$share->table_name."` ON ( ( `".$share->table_name."`.`". $share->table_fields["reference"] ."` = `".$this->table_name."`.`id` AND `".$share->table_name."`.`". $share->table_fields["reference_table"] ."` = '". $this->table_name ."' ) OR ( `".$share->table_name."`.`". $share->table_fields["reference"] ."` = `".$this->table_name."`.`".$this->table_fields["reference"]."` AND `".$share->table_name."`.`". $share->table_fields["reference_table"] ."` = `".$this->table_fields["reference_table"]."` ) ) AND `".$share->table_name."`.`record_status` = '1' " . $join_where;
					
					$this->class_settings[ 'filter' ][ 'join_count' ] = $this->class_settings[ 'filter' ][ 'join' ];
				}
			break;
			case "display_all_deleted_records":
			case "display_all_records_full_view_front":
			case "display_all_records_frontend":
				$this->class_settings[ "frontend" ] = 1;
			break;
			case "display_all_records_frontend_branch":
				$this->class_settings[ "frontend" ] = 1;
				
				if( isset( $this->table_fields["parish"] ) || isset( $this->table_fields["store"] ) ){
					$branch = get_current_customer( "branch" );
					if( $branch ){
						if( isset( $this->table_fields["parish"] ) ){
							$fwhere .= " AND `".$this->table_name."`.`". $this->table_fields["parish"] ."` = '". $branch ."' ";
						}else if( $this->table_fields["store"] ){
							$fwhere .= " AND `".$this->table_name."`.`". $this->table_fields["store"] ."` = '". $branch ."' ";
						}
					}
				}
				
				$this->class_settings[ 'filter' ][ 'where' ] = $fwhere;
			break;
			case "display_all_records_frontend_ssr":
			case "display_all_records_frontend_nsr":
				
				$this->class_settings[ "frontend" ] = 1;
				$f = $this->_set_nsr_filters();
				if( isset( $f["error"] ) && $f["error"] ){
					return $this->_display_notification( array( "type" => 'error', "message" => $f["error"] ) );
				}
				
				if( isset( $f["where"] ) && $f["where"] ){
					$this->class_settings[ 'filter' ][ 'where' ] = $f["where"];
				}
				
				if( isset( $f["join"] ) && $f["join"] ){
					$this->class_settings[ 'filter' ][ 'join' ] = $f["join"];
					$this->class_settings[ 'filter' ][ 'join_count' ] = $f["join"];
				}
			break;
			}
			
			//to correct mistake --remove later
			if( ! ( isset( $this->class_settings[ 'filter' ][ 'additional_where' ] ) && $this->class_settings[ 'filter' ][ 'additional_where' ] ) ){
				$this->class_settings[ 'filter' ][ 'additional_where' ] = '';
			}
			if( isset( $this->class_settings[ 'filter' ][ 'where' ] ) && $this->class_settings[ 'filter' ][ 'where' ] ){
				$this->class_settings[ 'filter' ][ 'additional_where' ] .= $this->class_settings[ 'filter' ][ 'where' ];
				unset( $this->class_settings[ 'filter' ][ 'where' ] );
			}
			// print_r( $this->class_settings[ 'filter' ][ 'additional_where' ] );exit;
			
			if( isset( $this->class_settings[ 'filter' ][ 'department' ] ) && $this->class_settings[ 'filter' ][ 'department' ] ){
				$access = get_accessed_functions();
				$super = 0;
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
				
				if( ! ( $super || isset( $access["accessible_functions"][ "departments.all_dept" ] ) ) ){
					$ud = isset( $this->class_settings["user_dept"] )?$this->class_settings["user_dept"]:'';
					$this->class_settings[ 'filter' ][ 'additional_where' ] .= " AND  `".$this->table_name."`.`".$this->class_settings[ 'filter' ][ 'department' ]."` = '". $ud ."'";
					if( ! isset( $this->class_settings['prefix_title'] ) ){
						$this->class_settings['prefix_title'] = '';
					}
					$this->class_settings['prefix_title'] .= get_name_of_referenced_record( array( "id" => $ud, "table" => "departments" ) ) . ': ';
				}
			}
			
			if( isset( $this->class_settings[ 'datatable_settings' ] ) && is_array( $this->class_settings[ 'datatable_settings' ] ) && ! empty( $this->class_settings[ 'datatable_settings' ] ) ){
				$this->datatable_settings = array_merge( $this->datatable_settings, $this->class_settings[ 'datatable_settings' ] );
			}
			

			$this->_set_custom_datatable_buttons();
			
			$new_format = 0;
			if( isset( $this->class_settings[ "frontend" ] ) && $this->class_settings[ "frontend" ] ){
				$this->class_settings[ 'data' ]['frontend'] = 1;
				$new_format = 1;
			}
			
			if( isset( $this->class_settings[ "return_new_format" ] ) && $this->class_settings[ "return_new_format" ] ){
				$new_format = 1;
			}
			
			if( $new_format ){
				if( ! isset( $this->datatable_settings["button_params"] ) ){
					$this->datatable_settings["button_params"] = '';
				}
				
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$this->datatable_settings["button_params"] .= '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				if( isset( $_GET["all_store"] ) && $_GET["all_store"] ){
					if( isset( $this->use_current_store ) )$this->use_current_store = 0;
				}
				
				if( ! get_single_store_settings() && isset( $this->use_current_store ) && $this->use_current_store ){
					if( ( isset( $this->class_settings[ 'current_store' ] ) && $this->class_settings[ 'current_store' ] ) ){
						$this->datatable_settings["button_params"] .= '&current_store=' . $this->class_settings[ 'current_store' ];
						
						if( isset( $this->table_fields[ 'store' ] ) ){
							if( ! isset( $this->class_settings[ 'filter' ][ 'additional_where' ] ) ){
								$this->class_settings[ 'filter' ][ 'additional_where' ] = '';
							}
							
							$this->class_settings['prefix_title'] = get_name_of_referenced_record( array( "id" => $this->class_settings[ 'current_store' ], "table" => "stores" ) ) . ': ';
							
							$sub_stores = array();
							
							if( isset( $_GET["tab_key"] ) && $_GET["tab_key"] && ! get_single_store_settings() ){
								$st = new cStores();
								$st->class_settings = $this->class_settings;
								$sub_stores = $st->_get_the_stores( array( "tab_key" => $_GET["tab_key"], "parent" => $this->class_settings[ 'current_store' ], "type" => "sub_stores_id" ) );
							}
							
							if( empty( $sub_stores ) ){
								$this->class_settings[ 'filter' ][ 'additional_where' ] .= " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` = '". $this->class_settings[ 'current_store' ] ."' ";
							}else{
								$sub_stores[] = $this->class_settings[ 'current_store' ];
								$this->class_settings[ 'filter' ][ 'additional_where' ] .= " AND `".$this->table_name."`.`".$this->table_fields[ 'store' ]."` IN ( '". implode("', '", $sub_stores ) ."' ) ";
							}
						}
					}
				}
				
				if( isset( $this->datatable_settings['show_advance_search'] ) && $this->datatable_settings['show_advance_search'] ){
					$this->datatable_settings['show_advance_search'] = 0;
					$this->datatable_settings['popup_search'] = 1;
				}
			}
			
			if( defined("HYELLA_SMALL_SCREEN_FRIENDLY") && HYELLA_SMALL_SCREEN_FRIENDLY ){
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				if( ! isset( $this->datatable_settings["button_params"] ) ){
					$this->datatable_settings["button_params"] = '';
				}
				$this->datatable_settings["button_params"] .= '&small_screen=1';
			}

			if( isset( $this->class_settings[ "new_window_popup" ] ) && ! empty( $this->class_settings[ "new_window_popup" ] ) ){
				$this->datatable_settings['new_window_popup'] = $this->class_settings[ "new_window_popup" ];
			}			
			
			if( isset( $this->class_settings[ "show_popup_form" ] ) && $this->class_settings[ "show_popup_form" ] ){
				$dkey = 'show_add_new';
				if( isset( $this->datatable_settings[$dkey] ) && $this->datatable_settings[$dkey] ){
					if( ! is_array( $this->datatable_settings[$dkey] ) ){
						unset( $this->datatable_settings[$dkey] );
						$this->datatable_settings[$dkey] = array();
					}
					
					if( ! isset( $this->datatable_settings[$dkey]['function-name'] ) ){
						$this->datatable_settings[$dkey]['function-text'] = 'New';
						$this->datatable_settings[$dkey]['function-name'] = 'new_popup_form';
					}
				}
				
				$dkey = 'show_edit_button';
				if( isset( $this->datatable_settings[$dkey] ) && $this->datatable_settings[$dkey] ){
					if( ! is_array( $this->datatable_settings[$dkey] ) ){
						unset( $this->datatable_settings[$dkey] );
						$this->datatable_settings[$dkey] = array();
					}
					
					if( ! isset( $this->datatable_settings[$dkey]['function-name'] ) ){
						$this->datatable_settings[$dkey]['function-text'] = 'Edit';
						$this->datatable_settings[$dkey]['function-name'] = 'edit_popup_form';
					}
				}
			}
			
			if( isset( $this->class_settings["show_form"] ) && $this->class_settings["show_form"] ){
				$sp = isset( $this->class_settings["search_popup"] )?$this->class_settings["search_popup"]:'';
				unset( $this->class_settings["search_popup"] );
				
				$this->class_settings[ 'search_form' ] = 1;
				$form = $this->_generate_new_data_capture_form();
				if( $sp || ( defined("HYELLA_SMALL_SCREEN_FRIENDLY") && HYELLA_SMALL_SCREEN_FRIENDLY ) ){
					$this->datatable_settings['form_data'] = isset( $form['html'] ) ? $form[ 'html' ] : ( isset( $form[ 'html_replacement' ] ) ? $form[ 'html_replacement' ] : '' );
					//$this->class_settings[ "full_table" ] = 1;
				}else{
					//$this->datatable_settings['form_data'] = isset( $form['html'] ) ? $form[ 'html' ] : ( isset( $form[ 'html_replacement' ] ) ? $form[ 'html_replacement' ] : '' );
					
					$this->class_settings[ 'data' ]['form_data'] = isset( $form['html'] ) ? $form[ 'html' ] : ( isset( $form[ 'html_replacement' ] ) ? $form[ 'html_replacement' ] : '' );
				}
			}
			
			if( isset( $this->default_reference ) &&  isset( $this->emr_search ) && $this->emr_search ){
				if( isset( $this->table_fields[ $this->default_reference ] ) && isset( $this->customer_source ) ){
					
					$this->datatable_settings["table_data"]["quick_details"]["emr_search"] = array(
						"search_field" => 'customers2626',	//emr no
						"field" => $this->table_fields[ $this->default_reference ],
						"table" => $this->customer_source,
					);
					
					$this->datatable_settings["table_data"]["quick_search"]["emr_search"] = '<input type="text" placeholder="EMR No" d-custom="1" name="emr_search" style="width:25%;" />';
				}
			}
			
			//@dan-31-mar-22
			if( isset( $this->serial_num_search ) && $this->serial_num_search ){
				$this->datatable_settings["table_data"]["quick_details"]["serial_num_search"] = array(
					"search_field" => 'serial_num',
					"field" => 'serial_num',
					"table" => $this->table_name,
				);
				$this->datatable_settings["table_data"]["quick_search"]["serial_num_search"] = '<input type="text" placeholder="Serial No" d-custom="1" name="serial_num_search" style="width:20%;" />';
			}
			
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/display-all-records-full-view' );
			
			
			// print_r( $this->datatable_settings[ "show_edit_button" ] );exit;
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			//$this->class_settings[ 'data' ]['title'] = "Manage Patient Medication";
			$this->class_settings[ 'data' ]['title'] = ucwords( $this->label );
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			if( isset( $this->class_settings['title'] ) && $this->class_settings['title'] ){
				$this->class_settings[ 'data' ]['title'] = $this->class_settings['title'];
			}else{
				if( isset( $_GET[ 'title' ] ) && $_GET[ 'title' ] ){
					$this->class_settings[ 'data' ][ 'title' ] = rawurldecode( $_GET[ 'title' ] );
				}else if( isset( $_GET[ 'menu_title2' ] ) && $_GET[ 'menu_title2' ] ){
					$this->class_settings[ 'data' ][ 'title' ] = rawurldecode( $_GET[ 'menu_title2' ] );
				}else if( isset( $_GET[ 'menu_title' ] ) && $_GET[ 'menu_title' ] ){
					$this->class_settings[ 'data' ][ 'title' ] = rawurldecode( $_GET[ 'menu_title' ] );
				}
			}
			
			if( isset( $this->class_settings[ 'data' ]['title'] ) && isset( $this->class_settings['prefix_title'] ) ){
				$this->class_settings[ 'data' ]['title'] = $this->class_settings['prefix_title'] . $this->class_settings[ 'data' ]['title'];
			}
			
			if( isset( $this->class_settings[ 'data' ]['title'] ) && isset( $this->class_settings['suffix_title'] ) ){
				$this->class_settings[ 'data' ]['title'] .= $this->class_settings['suffix_title'];
			}
			
			if( isset( $this->class_settings['hide_title'] ) ){
				$this->class_settings[ 'data' ]['hide_title'] = $this->class_settings['hide_title'];
			}
			
			if( isset( $this->class_settings[ "full_table" ] ) && $this->class_settings[ "full_table" ] ){
				$this->class_settings[ 'data' ]['no_col_1'] = 1;
			}

			if( isset( $this->class_settings[ "table_filter" ] ) && $this->class_settings[ "table_filter" ] ){
				$this->class_settings[ 'data' ]['table_filter'] = $this->class_settings[ "table_filter" ];
			}

			$returning_html_data = $this->_get_html_view();
			
			// print_r($this->class_settings);exit;
			if( $new_format ){
				$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
				$handle = "#".$ccx;
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				}
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'data' => $rdata,
					'javascript_functions' => $js, 
				);
			}
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'nwResizeWindow.resizeWindow', '$nwProcessor.recreateDataTables', '$nwProcessor.set_function_click_event', '$nwProcessor.update_column_view_state', 'prepare_new_record_form_new' ) 
			);
		}
		
		
		function _set_custom_datatable_buttons( $o = array() ){
			$ub = array();
			$html = '';
			$return = 0;
			
			
			if( isset( $o['custom_settings'] ) && ! empty( $o['custom_settings'] ) ){
				$return = 1;
				$this->datatable_settings = $o['custom_settings'];
			}
			
			if( isset( $this->datatable_settings['utility_buttons'] ) && ! empty( $this->datatable_settings['utility_buttons'] ) ){
				$ub[ 'utility_buttons' ] = $this->datatable_settings['utility_buttons'];
				if( isset( $this->datatable_settings['set_html_container'] ) && ! empty( $this->datatable_settings['set_html_container'] ) ){
					$ub[ 'set_html_container' ] = $this->datatable_settings['set_html_container'];
				}
				
				if( defined("HYELLA_NO_DOCUMENT") ){
					unset( $ub[ 'utility_buttons' ][ 'attach_file' ] );
					unset( $ub[ 'utility_buttons' ][ 'view_attachments' ] );
				}
			}
			
			if( isset( $this->basic_data['more_actions'] ) && ! empty( $this->basic_data['more_actions'] ) ){
				$ub[ 'more_actions' ] = $this->basic_data["more_actions"];
			}
			
			if( ! empty( $ub ) ){
				$this->class_settings[ 'data' ] = $ub;
				$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
				$this->class_settings[ 'data' ][ 'records_table' ] = $this->table_name;
				
				if( isset( $this->class_settings['access_role_table'] ) && $this->class_settings['access_role_table'] ){
					$this->class_settings[ 'data' ][ 'table' ] = $this->class_settings['access_role_table'];
				}

				if( isset( $this->class_settings['more_data'] ) && $this->class_settings['more_data'] ){
					$this->class_settings[ 'data' ][ 'more_data' ] = $this->class_settings['more_data'];
				}
				
				if( isset( $this->plugin ) && $this->plugin ){
					$this->class_settings[ 'data' ]['plugin'] = $this->plugin;
				}
				
				$this->class_settings[ 'data' ][ 'development' ] = get_hyella_development_mode();
				
				if( isset( $this->class_settings["html_replacement_selector"] ) ){
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings["html_replacement_selector"];
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
				$html = $this->_get_html_view();
			}
			
			if( isset( $o['return_html'] ) && $o['return_html'] ){
				return $html;
			}
			
			if( isset( $this->class_settings['custom_edit_button'] ) ){
				$html .= $this->class_settings['custom_edit_button'];
			}
			$this->datatable_settings['custom_edit_button'] = $html;
			
			if( $return ){
				return $this->datatable_settings;
			}
		}
		
		protected function _check_accounting_settings(){
			return;
			$rv = __map_financial_accounts();
			$problem_accounts = array();
			$accounts_not_found = array();
			$error_msg = '';
			$accounts_data = array();
			
			$accounts = $this->linked_accounts;
			
			foreach( $accounts as $k ){
				$title = strtoupper( str_replace( "_", " ", $k ) );
				
				if( isset( $rv[ $k ] ) && $rv[ $k ] ){
					$details = get_chart_of_accounts_details( array( "id" => $rv[ $k ] ) );
					if( ! ( isset( $details[ "id" ] ) && $details[ "id" ] ) ){
						$problem_accounts[] = $title;
					}else{
						
						$accounts_data[ $k ] = $details;
						
					}
				}else{
					$accounts_not_found[] = $title;
				}
			}
			
			$acc_types = get_types_of_account();
			
			foreach( $accounts as $k ){
				$check = '';
				$title = strtoupper( str_replace( "_", " ", $k ) );
				
				switch( $k ){
				case "payroll_net_pay":
					$check = 'current_liabilities';
					$check = 'liabilities';
				break;
				case "salary_expense_account":
					$check = 'operating_expense';
				break;
				}
				
				if( $check ){
					$k1 = $check;
					if( ! ( isset( $accounts_data[ $k ] ) && isset( $accounts_data[ $k1 ] ) && $accounts_data[ $k ]["type"] == $accounts_data[ $k1 ][ "type" ] ) ){
						$ap1 = isset( $accounts_data[ $k1 ] )?$accounts_data[ $k1 ][ "type" ]:'';
						$atype = isset( $acc_types[ $ap1 ] ) ? $acc_types[ $ap1 ] : 'N/A';
						$problem_accounts[] = 'Set the account type of <strong>'.$title.'</strong> to equal <strong>'. $atype .'</strong>';
					}
				}
			}
			
			if( ! empty( $problem_accounts ) ){
				$error_msg = '<p>The following accounts are not properly configured in your chart of accounts</p>';
				$error_msg .= '<p><ol><li>'. implode( "</li><li>", $problem_accounts ).'</li></ol></p>';
			}
			
			if( ! empty( $accounts_not_found ) ){
				$error_msg = '<p>The following accounts do not exist in your license file</p>';
				$error_msg .= '<p><ol><li>'. implode( "</li><li>", $accounts_not_found ).'</li></ol></p>';
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Chart of Account Settings</h4><p>Please contact your support team or configure your chart of accounts properly</p><p>or You can disabled the settings <i>DO NOT POST PAY ROLL EXPENSE</i> in the GENERAL SETTINGS MENU to bypass this check</p><br />' . $error_msg;
				return $err->error();
			}
		}
		
		//BASED METHODS
		protected function _refresh_cache(){
			//empty permanent cache folder
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			
			//if( isset( $_POST["id"] ) && $_POST["id"] ){
			//file_put_contents( 'aaa.txt', json_encode( $_POST ) );
			//$this->class_settings[ 'cache_where' ] = " AND `creation_date` = '1659566726' ";
			
			if( isset( $_POST["ids2"] ) && $_POST["ids2"] ){
				//$this->class_settings[ 'current_record_id' ] = $_POST["id"];
				
				$this->class_settings[ 'current_record_id' ] = 'pass_condition';
				//if( isset( $_POST["ids2"] ) && $_POST["ids2"] ){
					$this->class_settings[ 'cache_where' ] = " AND `id` IN ( " . $_POST["ids2"] . ") ";
				//}
				
			}else{
				
				if( ! ( isset( $this->class_settings["refresh_continue"] ) && $this->class_settings["refresh_continue"] ) ){
					/*
					clear_cache_for_special_values_directory( array(
						"permanent" => true,
						"directory_name" => $this->table_name,
					) );
					*/
					$settings = array(
						'cache_key' => $this->table_name . "-list",
						'directory_name' => $this->table_name,
						'permanent' => true,
					);
					//clear_cache_for_special_values( $settings );
				}
				
				$this->class_settings[ 'current_record_id' ] = 'pass_condition';
			}
			
			//$this->class_settings[ 'cache_where' ] = " AND `serial_num` > 18354 ";
			
			$this->_get_record();
			
			$err = new cError('010011');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Cache Successfully Refreshed</h4>';
			return $err->error();
		}
		
		public function _save_line_items(){
			
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$msg = '<h4>Read Only Mode Active</h4>You are not allowed to modify these records';
				return $this->_display_notification( array( "type" => "error", "message" => $msg ) );
			}
			
			if( ! ( isset( $this->class_settings["line_items"] ) && is_array( $this->class_settings["line_items"] ) ) ){
				return 0;
			}
			
			$reference = '';
			$reference_table = '';
				
			if( ( isset( $this->class_settings[ 'reference' ] ) && $this->class_settings[ 'reference' ] && isset( $this->class_settings[ 'reference_table' ] ) && $this->class_settings[ 'reference_table' ] ) ){
				
				$reference = $this->class_settings[ 'reference' ];
				$reference_table = $this->class_settings[ 'reference_table' ];
				
			}
			
			
			if( isset( $this->class_settings[ "clear_ids" ] ) && is_array( $this->class_settings[ "clear_ids" ] ) && ! empty( $this->class_settings[ "clear_ids" ] ) ){
				foreach ($this->class_settings['clear_ids'] as &$_id) {
					$_id = str_replace(['"', "'"], '', $_id);
				}
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( '". implode( "', '", $this->class_settings[ "clear_ids" ] ) ."' ) ";	//LIMIT 1 										   
				//$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( ". implode( ",", $this->class_settings[ "clear_ids" ] ) ." ) ";	//LIMIT 1 
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
			}
			
			if( isset( $this->class_settings[ "clear_reference" ] ) && $this->class_settings[ "clear_reference" ] && isset( $this->class_settings[ "reference_table" ] ) && $this->class_settings[ "reference_table" ] ){
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `".$this->table_fields["reference"]."` = '". $this->class_settings[ "clear_reference" ] ."' AND `".$this->table_fields["reference_table"]."` = '". $this->class_settings[ "reference_table" ] ."' ";	//LIMIT 1 
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
			}
			
			if( isset( $this->class_settings[ "clear_child_reference" ] ) && $this->class_settings[ "clear_child_reference" ] && isset( $this->class_settings[ "child_reference_table" ] ) ){
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `".$this->table_fields["child_reference"]."` = '". $this->class_settings[ "clear_child_reference" ] ."' AND `".$this->table_fields["child_reference_table"]."` = '". $this->class_settings[ "child_reference_table" ] ."' ";	//LIMIT 1 
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
			}
			
			$array_of_dataset = array();
			$array_of_dataset_update = array();
			$array_of_update_conditions = array();
			
			
			$new_record_id = get_new_id();
			$new_record_id_serial = 0;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			$use_replace = '';
			if( isset( $this->class_settings[ 'use_replace' ] ) ){
				$use_replace = $this->class_settings[ 'use_replace' ];
			}
			$skip_manifest = 0;
			if( isset( $this->class_settings[ 'skip_manifest' ] ) ){
				$skip_manifest = $this->class_settings[ 'skip_manifest' ];
				unset( $this->class_settings['skip_manifest'] );
			}		  
			
			$use_single_ref = isset( $this->class_settings[ "use_single_ref" ] )?$this->class_settings[ "use_single_ref" ]:'';
			
			if( $use_single_ref ){
				$reference = '';
			}
			
			foreach( $this->class_settings["line_items"] as $k => $v ){
				
				if( $reference ){
					$v["reference"] = $reference;
					$v["reference_table"] = $reference_table;
				}
				
				
				$id = $new_record_id . 'W' . ++$new_record_id_serial;
				if( isset( $v["new_id"] ) && $v["new_id"] ){
					$id = $v["new_id"];
				}
				
				$dataset_to_be_inserted = array(
					'id' => $id,
					'creator_role' => isset( $this->class_settings[ 'priv_id' ] ) && $this->class_settings[ 'priv_id' ] ? $this->class_settings[ 'priv_id' ]: '',
					'created_by' => isset( $this->class_settings[ 'user_id' ] ) && $this->class_settings[ 'user_id' ] ? $this->class_settings[ 'user_id' ]: '',
					'creation_date' => $date,
					'modified_by' => isset( $this->class_settings[ 'user_id' ] ) && $this->class_settings[ 'user_id' ] ? $this->class_settings[ 'user_id' ] : '',
					'modification_date' => $date,
					'ip_address' => $ip_address,
					'record_status' => 1,
				);
				
				foreach( $v as $kv => $v1 ){
					if( isset( $this->table_fields[ $kv ] ) ){
						$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $v1;
					}
				}
				
				if( isset( $v["created_role"] ) && $v["created_role"] ){
					$dataset_to_be_inserted["creator_role"] = $v["created_role"];
				}
				if( isset( $v["creator_role"] ) && $v["creator_role"] ){
					$dataset_to_be_inserted["creator_role"] = $v["creator_role"];
				}
				
				if( isset( $v["bcreation_date"] ) && $v["bcreation_date"] ){
					$dataset_to_be_inserted["creation_date"] = $v["bcreation_date"];
				}

				if( isset( $v["bmodification_date"] ) && $v["bmodification_date"] ){
					$dataset_to_be_inserted["modification_date"] = $v["bmodification_date"];
				}
				
				if( isset( $v["created_by"] ) && $v["created_by"] ){
					$dataset_to_be_inserted["created_by"] = $v["created_by"];
				}
				
				if( $use_replace ){
					if( isset( $v["serial_num"] ) && $v["serial_num"] ){
						$dataset_to_be_inserted["serial_num"] = $v["serial_num"];
					}
				}
				
				if( isset( $v["created_source"] ) && $v["created_source"] ){
					$dataset_to_be_inserted["created_source"] = $v["created_source"];
				}
				
				if( isset( $v["modified_source"] ) && $v["modified_source"] ){
					$dataset_to_be_inserted["modified_source"] = $v["modified_source"];
				}
				
				if( isset( $v["modified_by"] ) && $v["modified_by"] ){
					$dataset_to_be_inserted["modified_by"] = $v["modified_by"];
				}
				
				if( isset( $v[ "update_key" ] ) && $v[ "update_key" ] && isset( $v[ "update_value" ] ) && $v[ "update_value" ] ){
					
					//update
					unset( $dataset_to_be_inserted[ 'id' ] );
					unset( $dataset_to_be_inserted[ 'created_by' ] );
					unset( $dataset_to_be_inserted[ 'creation_date' ] );
					unset( $dataset_to_be_inserted[ 'creator_role' ] );
					unset( $dataset_to_be_inserted[ 'created_source' ] );
					
					$update_conditions_to_be_inserted = array(
						'where_fields' => $v[ "update_key" ],
						'where_values' => $v[ "update_value" ],
					);

					$array_of_dataset_update[] = $dataset_to_be_inserted;

					$array_of_update_conditions[] = $update_conditions_to_be_inserted;
					
				}else{
					//new
					$array_of_dataset[] = $dataset_to_be_inserted;
				}
				
			}
			
			$saved = 0;
			$test_save = 0;
			
			$select_key = "creation_date";
			
			if( ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset_update,
					'skip_manifest' => $skip_manifest
				);
				
				$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
				
				if( isset( $this->class_settings[ 'use_replace' ] ) )$function_settings[ 'use_replace' ] = $this->class_settings[ 'use_replace' ];

				$returned_data = insert_new_record_into_table( $function_settings );
				
				$select_key = "modification_date";
				$test_save = 1;
			}
			
			if( ! empty( $array_of_dataset ) ){
				
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset,
					'skip_manifest' => $skip_manifest
				);
				
				if( $use_replace ){
					$function_settings[ 'use_replace' ] = $use_replace;
				}
				
				$returned_data = insert_new_record_into_table( $function_settings );
				$test_save = 1;
			}
			
			if( isset( $this->do_not_test_after_save_line_items ) && $this->do_not_test_after_save_line_items ){
				$test_save = 0;
			}
			
			if( $test_save ){
				
				if( isset( $this->table_fields["reference"] ) && $reference ){
					$where = " AND `".$this->table_fields["reference"]."` = '".$reference."' ";
					if( isset( $this->table_fields["reference_table"] ) && $reference_table ){
						$where .= " AND `".$this->table_fields["reference_table"]."` = '".$reference_table."' ";
					}
				}else{
					$where = " AND `".$select_key."` = '".$date."' ";
				}
				
				
				$query = "SELECT `id`, `serial_num` FROM `" . $this->class_settings['database_name'] . "`.`". ( isset( $this->db_table_name ) ? $this->db_table_name : $this->table_name ) ."` where `record_status`='1' ".$where;	//LIMIT 1 
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				
				$bills = execute_sql_query($query_settings);
				
				if( isset( $bills[0]["id"] ) ){
					$saved = count( $bills );
					$this->class_settings[ 'created_records' ] = $bills;
					
					if( isset( $this->refresh_cache_after_save_line_items ) && $this->refresh_cache_after_save_line_items ){
						$this->class_settings[ 'current_record_id' ] = 'pass_condition';
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						$this->class_settings[ 'cache_where' ] = $where;
						$this->_get_record();
					}
				}
			}
			
			return $saved;
		}
		
		protected function _send_email(){
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
			$email->class_settings[ 'destination' ] = array();
			
			if( isset( $this->class_settings[ 'destination_bcc_emails' ] ) && $this->class_settings[ 'destination_bcc_emails' ] ){
				$email->class_settings[ 'destination' ]['bcc_emails'] = $this->class_settings[ 'destination_bcc_emails' ];
				
				unset( $this->class_settings[ 'destination_bcc_emails' ] );
			}
			
			if( isset( $this->class_settings[ 'destination_cc_emails' ] ) && $this->class_settings[ 'destination_cc_emails' ] ){
				$email->class_settings[ 'destination' ]['cc_emails'] = $this->class_settings[ 'destination_cc_emails' ];
				
				unset( $this->class_settings[ 'destination_cc_emails' ] );
			}
			
			
			if( isset( $this->class_settings[ 'destination_email' ] ) && $this->class_settings[ 'destination_email' ] ){
				$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'destination_email' ];
				$email->class_settings[ 'destination' ]['full_name'][] = isset( $this->class_settings[ 'destination_full_name' ] )?$this->class_settings[ 'destination_full_name' ]:$this->class_settings[ 'destination_email' ];
				$email->class_settings[ 'destination' ]['id'][] = isset( $this->class_settings[ 'destination_id' ] )?$this->class_settings[ 'destination_id' ]:$this->class_settings[ 'destination_email' ];
				
				unset( $this->class_settings[ 'destination_email' ] );
			}
			
			if( isset( $this->class_settings[ 'copy_current_user' ] ) && $this->class_settings[ 'copy_current_user' ] ){
				
				$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'user_email' ];
				$email->class_settings[ 'destination' ]['full_name'][] = $this->class_settings[ 'user_full_name' ];
				$email->class_settings[ 'destination' ]['id'][] = $this->class_settings[ 'user_id' ];
				
				unset( $this->class_settings[ 'copy_current_user' ] );
			}
			
			return $email->emails();
		}
		
		/*
		HOW IT WORKS
		PASS 2 INPUTS
			$e = array(
				"content" => 'HTML BODY OF THE EMAIL',
				"subject" => '',
				"record_id" => 'Email Recipient Record ID',
				"record_table" => 'customers_table',
				"email_field" => '',
				
				"single_email" => '',
				"single_name" => '',
				
				"multiple_emails" => '',
				"sending_option" => '',
				"attachments" => array(),
				"system_notification" => array(),
				
			);
		*/
		
		public function _send_email_notification( $e = array(), $option = array() ){
			
			$pr = get_project_data();
			
			$pass = 0;
			$failed = 0;
			$success_msg = '';
			$response = '';
			$message_type = 99;
			
			$sending_option = isset( $e["sending_option"] )?$e["sending_option"]:'bcc';
			
			if( isset( $option["message_type"] ) && $option["message_type"] ){
				$message_type = $option["message_type"];
			}
			
			if( isset( $e["content"] ) && isset( $e["subject"] ) && $e["content"] ){
				
				$system_notification_response = '';
				if( isset( $e["system_notification"] ) && is_array( $e["system_notification"] ) && ! empty( $e["system_notification"] ) ){
					if( class_exists("cNotifications") ){
						$not = new cNotifications();
						$not->class_settings = $this->class_settings;
						
						$not->class_settings["notification_data"] = array(
							"recipients" => $e["system_notification"],
							"title" => $e["subject"],
							"detailed_message" => $e["content"],
							"send_email" => 0,
							"notification_type" => 0,
							"class_name" => $this->table_name,
							"trigger" => isset( $e["trigger"] )?$e["trigger"]:'',
							"method_name" => $this->class_settings["action_to_perform"],
						);
						
						$not->class_settings["action_to_perform"] = "add_notification";
						if( $not->notifications() ){
							$system_notification_response = 'System Notifications Sent.<br />';
						}
					}
				}
				
				$d = array();
				if( isset( $e["record_id"] ) && $e["record_id"] && isset( $e["record_table"] ) && isset( $e["email_field"] ) ){
					
					$c = get_record_details( array( "id" => $e["record_id"], "table" => $e["record_table"], "force" => 1 ) );
					if( isset( $c[ $e["email_field"] ] ) && $c[ $e["email_field"] ] ){
						$d["name"] = get_name_of_referenced_record( array( "id" => $e["record_id"], "table" => $e["record_table"] ) );
						$d["email"] = $c[ $e["email_field"] ];
					}
					
				}
				
				if( ! ( isset( $d["email"] ) && $d["email"] ) ){
					$d["email"] = isset( $e["single_email"] )?$e["single_email"]:'';
					$d["name"] = ( isset( $e["single_name"] ) && $e["single_name"] )?$e["single_name"]:$d["email"];
				}
				
				if( isset( $e["multiple_emails"] ) && $e["multiple_emails"] ){
					$pass = 1;
					if( ( isset( $d["email"] ) && $d["email"] ) && strpos( $e["multiple_emails"], $d["email"] ) < 0 ){
						$e["multiple_emails"] .= ',' . $d["email"];
					}
				}
				
				if( ( isset( $d["email"] ) && $d["email"] && filter_var( $d["email"], FILTER_VALIDATE_EMAIL ) ) || $pass ){
					
					if( $pass ){
						$d["name"] = '';
					}
					$d1["full_name"] = $d["name"];
					$d1["title"] = $e["subject"];
					if( isset( $e["content_title"] ) && $e["content_title"] ){
						$d1["title"] = $e["content_title"];
					}
					
					$d1["html"] = $e["content"];
					
					if( isset( $e["info"] ) && $e["info"] ){
						$d1["info"] = $e["info"];
					}
					
					if( isset( $e["mail_type"] ) && $e["mail_type"] ){
						$d1["mail_type"] = $e["mail_type"];
					}
					
					if( isset( $e["custom_heading"] ) && $e["custom_heading"] ){
						$d1["custom_heading"] = $e["custom_heading"];
					}
					
					$this->class_settings[ 'data' ] = $d1;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/email-message.php' );
					$this->class_settings['message'] = $this->_get_html_view();
					
					$this->class_settings['subject'] = strtoupper( $pr["company_name"] ) . ': ' . $e["subject"];
					
					if( isset( $e["attachments"] ) && is_array( $e["attachments"] ) && ! empty( $e["attachments"] ) ){
						$this->class_settings['attachment'] = $e["attachments"];
					}
					
					$this->class_settings['message_type'] = $message_type;
					$this->class_settings['direct_message'] = 1;
					
					if( isset( $d["email"] ) && $d["email"] ){
						$this->class_settings[ 'destination_full_name' ] = isset( $d["name"] )?$d["name"]:$d["email"];
						$this->class_settings[ 'destination_id' ] = $d["email"];
						$this->class_settings[ 'destination_email' ] = $d["email"];
					}
					
					if( isset( $e["multiple_emails"] ) && $e["multiple_emails"] ){
						$skey = 'bcc_emails';
						if( $sending_option == 'cc' ){
							$skey = 'cc_emails';
						}
						$this->class_settings[ 'destination_' . $skey ] = $e["multiple_emails"];
					}
					
					$status = $this->_send_email();
					$response .= ( isset( $status["response"] )?$status["response"]:'' );
					if( isset( $status["status"] ) && $status["status"] ){
						$success_msg .= '<br />' . $d["name"] .': '. $status["response"];
					}else{
						++$failed;
					}
				}else{
					$response = 'Invalid Recipient Email Address';
				}
				
			}else{
				$response = 'No Email Message Found';
			}
			
			return array(
				"response" => $response,
				"success" => $success_msg,
			);
		}
		
		public function _notify_people( $options = array() ){
			$ids = array();
			$q1 = 0;
			
			if( isset( $options["content"] ) && $options["content"] && isset( $options["subject"] ) && $options["subject"] ){
				
				$class = isset( $options[ 'user_class' ] ) ? $options[ 'user_class' ] : 'users';
				$plugin = isset( $options[ 'user_plugin' ] ) ? $options[ 'user_plugin' ] : '';

				if( $plugin ){
					$nwp = 'c' . ucwords( $plugin );
					if( class_exists( $nwp ) ){
						$nwp = new $nwp();
						$nwp->class_settings = $this->class_settings;
						$nwp->load_class( array( 'class' => array( $class ) ) );
					}
				}

				$cls = 'c'.ucwords( $class );

				if( class_exists( $cls ) ){
					$users = new $cls();
					$users->class_settings = $this->class_settings;
					
					if( isset( $options["specific_users"] ) && ! empty( $options["specific_users"] ) && is_array( $options["specific_users"] )  ){
						
						$users->class_settings["specific_users"] = $options["specific_users"];
						$q1 = 1;
					}else if( ( isset( $options["capability"] ) && $options["capability"] ) || ( isset( $options["capabilities"] ) && is_array( $options["capabilities"] ) && ! empty( $options["capabilities"] ) ) ){
						
						$users->class_settings["capability"] = isset( $options["capability"] )?$options["capability"]:'';
						$users->class_settings["capabilities"] = isset( $options["capabilities"] )?$options["capabilities"]:array();
						
						$users->class_settings["exclude_capabilities"] = isset( $options["exclude_capabilities"] )?$options["exclude_capabilities"]:array();
						
						$users->class_settings["users_filter"] = isset( $options["users_filter"] )?$options["users_filter"]:array();
						
						$users->class_settings["users_filter_condition"] = isset( $options["users_filter_condition"] )?$options["users_filter_condition"]:'';
						
						$users->class_settings["get_ids"] = 1;
						$q1 = 1;
					}
					
					
					if( $q1 ){
						$users->class_settings["action_to_perform"] = "get_email_of_users_based_on_capability";
						$ux = $users->$class();
						
						$emails = array();
						$recipient_ids = array();
						if( is_array( $ux ) && ! empty( $ux ) ){
							foreach( $ux as $u_val ){
								$ids[ $u_val["id"] ] = $u_val["id"];
								$recipient_ids[] = $u_val["id"];
								
								$ck = 'email';
								if( isset( $u_val[ $ck ] ) && filter_var( $u_val[ $ck ], FILTER_VALIDATE_EMAIL ) ){
									$emails[] = $u_val[ $ck ];
								}
							}
						}
						// print_r( $emails );exit;
						
						if( ! empty( $recipient_ids ) ){
							$opt = array(
								"data" => isset( $options["data"] )?$options["data"]:array(),
								"content" => $options["content"],
								"subject" => strtoupper( $options["subject"] ),
								//"multiple_emails" => implode(",", $recipients_emails ),
								"sending_option" => 'bcc',
								"system_notification" => $recipient_ids,
								"trigger" => isset( $options["trigger"] )?$options["trigger"]:'',
							);
							
							if( ! empty( $emails ) && isset( $options["sending_option"] ) && $options["sending_option"] ){
								$opt['sending_option'] = $options["sending_option"];
								$opt['multiple_emails'] = implode(",", $emails );
							}
							
							$r = $this->_send_email_notification( $opt, array() );
						}
					}
				}
			}
			
			return array(
				"people_notified" => $ids,
			);
		}

		public function _update_table_field(){
			$applicant = array();
			$return = array();
			//input: $this->class_settings["update_fields"]
			
			if( ! isset( $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
			$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
			$_POST[ "table" ] = $this->table_name;
			$_POST[ "processing" ] = md5(1);
			if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
				define('SKIP_USE_OF_FORM_TOKEN', 1);
			
			$push = 0;
			if( isset( $this->class_settings["update_fields"] ) && is_array( $this->class_settings["update_fields"] ) ){
				foreach( $this->class_settings["update_fields"] as $key => $val ){
					if( isset( $this->table_fields[ $key ] ) ){
						$_POST[ $this->table_fields[ $key ] ] = $val;
						$push = 1;
					}
				}
			}
			
			if( $push ){
				unset( $this->class_settings[ 'form_action' ] );
				$return = $this->_save_changes();
			}
			
			return $return;
		}
		
		function _get_html_view(){
			if( defined("NWP_FLAG_UTILITY") && NWP_FLAG_UTILITY ){
				if( isset( $this->class_settings[ 'data' ][ 'selected_record' ] ) && $this->class_settings[ 'data' ][ 'selected_record' ] && isset( $this->class_settings[ 'html' ] ) && is_array( $this->class_settings[ 'html' ] ) && in_array( 'html-files/templates-1/globals/custom-buttons.php', $this->class_settings[ 'html' ] ) ){
					$ox = array();
					if( isset( $this->datatable_settings[ 'utility_buttons' ][ 'comments' ] ) ){
						$ox["show_comments"] = 1;
					}
					if( isset( $this->datatable_settings[ 'utility_buttons' ][ 'view_attachments' ] ) ){
						$ox["show_files"] = 1;
					}
					if( ! empty( $ox ) ){
						$ox['source'] = 'utility_buttons';
						$ox['record']['reference_table'] = isset( $this->class_settings[ 'data' ][ 'table' ] )?$this->class_settings[ 'data' ][ 'table' ]:$this->table_name;
						
						$ox['record']['reference_table'] = isset( $this->class_settings[ 'data' ][ 'flag_reference_table' ] )?$this->class_settings[ 'data' ][ 'flag_reference_table' ]:$ox['record']['reference_table'];
						
						$ox['record']['reference'] = isset( $this->class_settings[ 'data' ][ 'flag_reference' ] )?$this->class_settings[ 'data' ][ 'flag_reference' ]:$this->class_settings[ 'data' ][ 'selected_record' ];
						
						$this->class_settings[ 'data' ][ 'uflag' ] = $this->_get_flags( $ox );
					}
				}
			}
			
			if( isset( $this->class_settings[ 'html' ] ) && is_array( $this->class_settings[ 'html' ] ) && in_array( 'html-files/templates-1/globals/custom-buttons.php', $this->class_settings[ 'html' ] ) ){
				if( isset( $this->class_settings["no_utility"] ) && $this->class_settings["no_utility"] ){
					$this->class_settings['data'][ 'utility_buttons' ] = array();
				}
				if( isset( $this->class_settings["no_more_actions"] ) && $this->class_settings["no_more_actions"] ){
					$this->class_settings['data'][ 'more_actions' ] = array();
				}
			}
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			if( isset( $this->plugin_hooks ) && ! empty( $this->plugin_hooks ) ){
				$script_compiler->class_settings[ 'plugin_hooks' ] = $this->plugin_hooks;
			}
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			$return = $script_compiler->script_compiler();
			
			if( isset( $script_compiler->class_settings["returned_data"] ) && $script_compiler->class_settings["returned_data"] ){
				$this->class_settings["returned_data"] = $script_compiler->class_settings["returned_data"];
			}
			
			return $return;
		}
		
		protected function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			if( defined( "FORM_" . strtoupper( $this->table_name ) ."_LABEL" ) && constant( "FORM_" . strtoupper( $this->table_name ) ."_LABEL" ) ){
				$this->label = constant( "FORM_" . strtoupper( $this->table_name ) ."_LABEL" );
			}
			
			if( ! isset( $this->class_settings['form_heading_title'] ) ){
				$this->class_settings['form_heading_title'] = 'New ' . ucwords( $this->label );
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify ' . ucwords( $this->label );
			break;
			}
			
			if( isset( $this->use_custom_fields ) && isset( $this->custom_fields ) && $this->use_custom_fields ){
				if( isset( $this->table_fields["data"] ) ){
					$this->class_settings["custom_fields_data"] = $this->table_fields["data"];
				}
				$this->class_settings["custom_fields"] = $this->custom_fields;
			}
			if( isset( $this->class_settings["custom_fields"] ) && is_array( $this->class_settings["custom_fields"] ) && ! empty( $this->class_settings["custom_fields"] ) ){
				$f1 = array_keys( $this->class_settings["custom_fields"] );
				if( ! empty( $f1 ) ){
					$f2 = array();
					
					foreach( $f1 as $f1v ){
						if( isset( $this->class_settings["custom_fields"][ $f1v ] ) ){
							$f2[ $f1v ] = $this->class_settings["custom_fields"][ $f1v ];
						}
					}
					unset( $this->class_settings["custom_fields"] );
					
					$this->class_settings["attributes"]["custom_form_fields"] = array(
						"field_ids" => $f1,
						"form_label" => $f2,
					);
				}
			}
			
			$branch = '';
			if( isset( $this->class_settings["has_branch"] ) && $this->class_settings["has_branch"] ){
				$branch = get_current_customer( "branch" );
				if( $branch ){
					if( isset( $this->branch_field ) && $this->branch_field && isset( $this->table_fields[ $this->branch_field ] ) ){
						unset( $this->class_settings["hidden_records"][ $this->table_fields[ $this->branch_field ] ] );
						$this->class_settings["hidden_records_css"][ $this->table_fields[ $this->branch_field ] ] = 1;
						$this->class_settings["form_values_important"][ $this->table_fields[ $this->branch_field ] ] = $branch;
					}
				}
			}
			
			$dckey = 'CREATE_FORM_';
			if( isset($_POST['mod']) && $_POST['mod']=='edit-'.md5( $this->table_name ) && isset($_POST['id']) ){
				$dckey = 'EDIT_FORM_';
			}
			
			foreach( $this->table_fields as $key => $val ){
				if( defined( $dckey . strtoupper( $this->table_name ) ."_HIDE_" . strtoupper( $key ) ) && constant( $dckey . strtoupper( $this->table_name ) ."_HIDE_" . strtoupper( $key ) ) ){
					$this->class_settings['hidden_records'][ $val ] = 1;
				}else if( defined( $dckey . strtoupper( $this->table_name ) ."_HIDE_" . strtoupper( $key ) ) ){
					$this->class_settings['form_display_not_editable_value'][ $val ] = 1;
				}else if( defined( $dckey . strtoupper( $this->table_name ) ."_HIDE_VALUE_" . strtoupper( $key ) ) && constant( $dckey . strtoupper( $this->table_name ) ."_HIDE_VALUE_" . strtoupper( $key ) ) ){
					$this->class_settings['hidden_records_css'][ $val ] = 1;
					if( ! isset( $this->class_settings['form_values_important'][ $val ] ) ){
						$this->class_settings['form_values_important'][ $val ] = constant( $dckey . strtoupper( $this->table_name ) ."_HIDE_VALUE_" . strtoupper( $key ) );
					}
				}else if( defined( $dckey . strtoupper( $this->table_name ) ."_VALUE_" . strtoupper( $key ) ) && constant( $dckey . strtoupper( $this->table_name ) ."_VALUE_" . strtoupper( $key ) ) ){
					if( ! isset( $this->class_settings['form_values_important'][ $val ] ) ){
						$this->class_settings['form_values_important'][ $val ] = constant( $dckey . strtoupper( $this->table_name ) ."_VALUE_" . strtoupper( $key ) );
					}
				}
			}
			
			if( ! isset( $this->class_settings['form_submit_button'] ) )
				$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			if( ! isset( $this->class_settings['form_class'] ) )
				$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$process_handler->class_settings[ 'db_table' ] = $this->db_table_name;
			}
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			if( isset( $this->plugin ) && $this->plugin ){
				$process_handler->class_settings[ 'plugin' ] = $this->plugin;
			}
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			$handle = "#form-content-area";
			$nwp_old_form_con = isset( $_GET["nwp_old_form_con"] )?$_GET["nwp_old_form_con"]:'';
			if( $nwp_old_form_con ){
				$this->class_settings['return_new_format'] = 1;
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#".$this->class_settings[ 'html_replacement_selector' ];
				}
			}
			
			if( isset( $this->class_settings['return_new_format'] ) && $this->class_settings['return_new_format'] ){
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $returning_html_data[ 'html' ],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'message' => 'Returned form data capture form',
					'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
					'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
				);
			}else{
				return array(
					'html' => $returning_html_data[ 'html' ],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'display-data-capture-form',
					'message' => 'Returned form data capture form',
					'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
				);				
			}
		}

		protected function _delete_records(){
			$returning_html_data = array();
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$this->class_settings["action_called"] = $this->class_settings[ 'action_to_perform' ];
			
			$a1 = 'delete_records';
			$a2 = 'deleted-records';
			$a3 = 'deleted_record_id';
			$a4 = 'delete';
			switch ( $action_to_perform ){
			case 'restore_only':
			case 'restore':
				$a1 = 'restore_records';
				//$a2 = 'restored-records';
				//$a3 = 'restored_record_id';
				//$a4 = 'restore';
				if( isset( $_GET["source"] ) && $_GET["source"] ){
				
					$this->class_settings[ 'database_controller_table' ] = $this->table_name;
					$plugin = 0;
					if( isset( $_GET["source_plugin"] ) && $_GET["source_plugin"] ){
						$actual_name_of_class = 'c'.ucwords( $_GET["source_plugin"] );
						$plugin = 1;
					}else{
						$actual_name_of_class = 'c'.ucwords( $_GET["source"] );
					}
					
					if( class_exists( $actual_name_of_class ) ){
						$module = new $actual_name_of_class();
						$module->class_settings = $this->class_settings;
						if( $plugin ){
							$md = $module->load_class( array( "class" => array( $_GET["source"] ), "initialize" => 1 ) );
							$this->table_fields = $md[ $_GET["source"] ]->table_fields;
							$this->table_name = $md[ $_GET["source"] ]->table_name;
						}else{
							$this->table_fields = $module->table_fields;
							$this->table_name = $module->table_name;
						}
					}
				}
			break;
			}
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'parent_tb' ] = $this->table_name;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = $a1;
			
			if( isset( $this->plugin ) ){
				$process_handler->class_settings[ 'plugin' ] = $this->plugin;
			}
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $action_to_perform;
			$returning_html_data['status'] = $a2;
			
			if( isset( $returning_html_data[ $a3 ] ) && $returning_html_data[ $a3 ] ){
				$cache_key = $this->table_name;
				
				$ids = explode( ":::", $returning_html_data[ $a3 ] );
				
				switch ( $action_to_perform ){
				case 'restore_only':
				case 'restore':
					
					if( ! empty( $ids ) && isset( $this->table_name_sub ) && $this->table_name_sub && $this->table_name_sub != $this->table_name ){
						$table = $this->table_name_sub;
						$actual_name_of_class = 'c'.ucwords( $table );
						$module = new $actual_name_of_class();
						$module->class_settings = $this->class_settings;
						
						if( isset( $module->default_reference ) && isset( $module->table_fields[ $module->default_reference ] ) ){
							
							$s1 = array();
							//$s1["database_table"] = $module->table_name;
							$s1["where"] = " `record_status` = '0' AND " . " `".$module->table_name."`.`".$module->table_fields[ $module->default_reference ]."` IN ('" . implode( "', '", $ids ) . "') ";
							$s1["field_and_values"][ "record_status" ] = array(
								"key" => 'record_status',
								"value" => 1,
							);
							$module->refresh_cache_after_save_line_items = 1;
							$module->_update_records( $s1 );	
						}
					}
					
					
					$this->class_settings[ 'current_record_id' ] = 'pass_condition';
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings[ 'cache_where' ] = " AND `id` IN ('" . implode( "', '", $ids ) . "') ";
					$this->_get_record();
				break;
				default:
					$settings = array(
						'cache_key' => $cache_key . "-list",
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$list = get_cache_for_special_values( $settings );
					
					foreach( $ids as $id ){
						if( isset( $list[ $id ] ) )unset( $list[ $id ] );
					}
					
					$settings = array(
						'cache_key' => $cache_key . "-list",
						'cache_values' => $list,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
					switch ( $action_to_perform ){
					case 'delete_from_popup':
						$returning_html_data["status"] = "new-status";
						unset( $returning_html_data["html"] );
						$returning_html_data["javascript_functions"] = array( "$('#modal-popup-close').click" );
					break;
					case 'delete_from_popup2':
						$returning_html_data["status"] = "new-status";
						unset( $returning_html_data["html"] );
						
						if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
							$returning_html_data["html_replacement_selector"] = "#" . $this->class_settings[ 'html_replacement_selector' ];
							$returning_html_data["html_replacement"] = "&nbsp";
						}
					break;
					}
					
					$d = array();
					$d1 = $ids;
					foreach( $d1 as $dd ){
						if( ! $dd )continue;
						
						$d[] = " '" . $dd . "' ";
						
						$ggsettings = array(
							'cache_key' => $cache_key . "-" . $dd,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						$returning_html_data[ "deleted_records" ][ $dd ] = get_cache_for_special_values( $ggsettings );
						clear_cache_for_special_values( $ggsettings );
					}
					
					if( isset( $this->table_name_sub ) && $this->table_name_sub && $this->table_name_sub != $this->table_name ){
						//09-may-23
						$table = $this->table_name_sub;
						if( isset( $this->plugin_instance ) && $this->plugin_instance ){
							$plc = $this->plugin_instance->load_class( array( 'class' => array( $this->table_name_sub ), 'initialize' => 1 ) );
							$module = $plc[ $this->table_name_sub ];
						}else{
							$actual_name_of_class = 'c'.ucwords( $table );
							$module = new $actual_name_of_class();
							$module->class_settings = $this->class_settings;
						}
						$module->class_settings["action_to_perform"] = "delete_with_query";
						
						if( $d && isset( $module->default_reference ) && isset( $module->table_fields[ $module->default_reference ] ) ){
							$module->class_settings["where"] = " `".$module->table_name."`.`".$module->table_fields[ $module->default_reference ]."` IN (" . implode( ',', $d ) . ") ";
							$module->$table();
						}
					}
				break;
				}
				
				if( isset( $this->delete_linked_transaction ) && $this->delete_linked_transaction ){
					switch ( $action_to_perform ){
					case 'restore':
					case 'delete':
					case 'delete_from_popup':
					case 'delete_from_popup2':
						$table = 'transactions';
						$_POST["mod"] = $a4 .'-' . md5( $table );
						
						$actual_name_of_class = 'c'.ucwords( $table );
						$module = new $actual_name_of_class();
						$module->class_settings = $this->class_settings;
						$module->class_settings["action_to_perform"] = $a4. '_only';
						//30-mar-23
						if( $this->delete_linked_transaction == 2 ){
							$module->class_settings["action_to_perform"] = $a4;
						}
						$module->$table();
					break;
					}
				}
				
			}
			
			if( method_exists( $this, "__after_delete_and_refresh" ) ){
				$this->__after_delete_and_refresh( $returning_html_data );
			}
			
			return $returning_html_data;
		}
		
		protected function _display_data_table(){
			$action_to_perform = $this->class_settings["action_to_perform"];
			$reset_filters = 1;
			
			$tb = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name;
				$this->datatable_settings['db_table'] = $tb;
			}
			
			switch( $action_to_perform ){
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
			break;
			case 'display_deleted_records':
			case 'display_all_deleted_records':
				$this->class_settings[ 'filter' ]['show_deleted_records'] = 1;
				
				if( isset( $this->class_settings[ 'base_call' ] ) ){
					$this->datatable_settings['show_restore_button']['no_plugin'] = 1;
					$this->datatable_settings['show_restore_button']['function-class'] = $this->table_name_static;
					$this->datatable_settings['show_restore_button']['function-name'] = 'restore&source=' . $this->table_name;
				}else{
					$this->datatable_settings['show_restore_button'] = 1;
				}
				$this->datatable_settings['skip_access_control'] = 1;
				
			break;
			case 'split_datatable':
				$reset_filters = 0;
				//$this->datatable_settings['split_screen'] = array( 'checked' => ' checked="checked" ' );
				//$this->datatable_settings['split_screen'] = array();
				
				if( isset( $_SESSION[ $this->table_name ][ 'filter' ]['settings'] ) ){
					$this->datatable_settings = $_SESSION[ $this->table_name ][ 'filter' ]['settings'];
				}
				
			break;
			default:
				$this->datatable_settings['datatable_method'] = $action_to_perform;
			break;
			}
			
			
			if( isset( $this->class_settings[ 'apply_access_control' ] ) && $this->class_settings[ 'apply_access_control' ] ){
				unset( $this->datatable_settings['skip_access_control'] );
				unset( $this->datatable_settings['show_restore_button'] );
				$this->datatable_settings['show_refresh_cache'] = 0;
			}
			
			if( isset( $this->table_package ) && $this->table_package ){
				$this->datatable_settings['table_data']['table_package'] = $this->table_package;
			}
			
			if( isset( $this->plugin ) && $this->plugin ){
				$this->datatable_settings['plugin'] = $this->plugin;
			}
			
			if( isset( $this->class_settings[ 'current_store' ] ) && $this->class_settings[ 'current_store' ] ){
				$this->datatable_settings['current_store'] = $this->class_settings[ 'current_store' ];
			}
			
			if( isset( $this->class_settings[ "datatable_split_screen" ] ) && $this->class_settings[ "datatable_split_screen" ] ){
				$this->datatable_settings['datatable_split_screen'] = $this->class_settings[ "datatable_split_screen" ];
				
				if( isset( $this->datatable_settings['datatable_split_screen']['col'] ) && intval( $this->datatable_settings['datatable_split_screen']['col'] ) ){
					$this->datatable_settings['split_screen']['checked'] = ' checked="checked" ';
				}else{
					$this->datatable_settings['split_screen']['checked'] = '';
				}
			}
			
			//print_r( $this->class_settings[ 'filter' ] ); exit;
			if( $reset_filters ){
				unset( $_SESSION[ $this->table_name ][ 'filter' ] );
				//GET ALL FIELDS IN TABLE
				//unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
				
				$this->class_settings[ 'filter' ]['settings'] = $this->datatable_settings;
				
				if( isset( $this->class_settings[ 'filter' ] ) && $this->class_settings[ 'filter' ] ){
					$_SESSION[ $this->table_name ][ 'filter' ] = $this->class_settings[ 'filter' ];
				}
			}
			
			$query = '';
			$fields = array();

			if( isset( $this->class_settings[ 'overide_fields_describe' ] ) && $this->class_settings[ 'overide_fields_describe' ] ){
				$fields = $this->class_settings[ 'overide_fields_describe' ];
			}else{
				switch( DB_MODE ){
				case 'mssql':
					$query = $this->table_name;
				break;
				default:
					$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$tb."`";
				break;
				}
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'DESCRIBE',
					'set_memcache' => 1,
					'tables' => array( $tb ),

					//MongoDB
					'table' => $tb,
					'where' => array( 
							'table_fields' => 'table_fields',
						),
					'select' => array( 
						'projection' => array( 
							'_id' => 0,
							'table_fields' => 0,
								),
						),
				);
				$result = execute_sql_query($query_settings);
				
				if( empty( $result ) && defined("DB_MODE") && DB_MODE == 'mongoDB' ){
					foreach( $this->table_fields as $key => $value ){
						$result[][] = $value;
					}
					$result[][] = 'serial_num';
					$result[][] = 'id';
					$result[][] = 'creator_role';
					$result[][] = 'created_by';
					$result[][] = 'creation_date';
					$result[][] = 'modified_by';
					$result[][] = 'modification_date';
					$result[][] = 'ip_address';
					$result[][] = 'device_id';
					$result[][] = 'record_status';
				}

				if( $result && is_array( $result ) && ! empty( $result ) ){
					$array2 = array( 0 => '1', 'Field' => '1' );
					foreach( $result as $sval ){
						if( isset( $this->class_settings[ 'exclude_db_fields' ][ $sval['Field'] ] ) ){
							
						}else{
							$fields[] = array_intersect_key( $sval , $array2 );
						}
					}
				}

			}
			// print_r( $fields );exit;

			if( empty( $fields ) ){
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cregistered_users.php';
				$err->method_in_class_that_triggered_error = '_save';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 246';
				return $err->error();
			}
			
			if( isset( $this->set_datatable_access_control ) && $this->set_datatable_access_control ){
				$this->_set_datatable_access_control();
			}

			if( !(isset($this->class_settings['current_module']) ) ){
				$this->class_settings['current_module'] = '';
			}
			
			$this->datatable_settings[ 'table_data' ][ 'fields' ] = $fields;
			if( isset( $_SESSION[ $this->table_name ] ) ){
				$this->datatable_settings[ 'table_data' ][ 'session' ] = $_SESSION[ $this->table_name ];
			}else{
				$this->datatable_settings[ 'table_data' ][ 'session' ] = array();
			}
			// print_r( $this->datatable_settings );exit;
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			
			$form->datatables_settings = $this->datatable_settings;

			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		protected function _save_changes(){
			$returning_html_data = array();
			
			$this->class_settings["action_called"] = $this->class_settings[ 'action_to_perform' ];
			
			if( method_exists( $this, "__before_save_changes" ) ){
				$r = $this->__before_save_changes();
				
				if( isset( $r ) && isset( $r["status"] ) && $r["status"] == "new-status" ){
					return $r;
				}
			}
			
			$r = $this->_before_save_prevent_duplicates();
			if( isset( $r ) && ! empty( $r ) && is_array( $r ) ){
				return $r;
			}
			
			$e1 = []; //13-mar-23
			if( isset( $this->use_custom_fields ) && isset( $_POST["extra_fields"] ) && $_POST["extra_fields"] && $this->use_custom_fields ){
				$ef = json_decode( $_POST["extra_fields"], true );
				if( isset( $ef["custom_fields"] ) && is_array( $ef["custom_fields"] ) && ! empty( $ef["custom_fields"] ) ){
					$d = $ef["custom_fields"];
					
					$ed = array();
					if( isset( $_POST[ $this->table_fields["data"] ] ) ){
						$ed = json_decode( $_POST[ $this->table_fields["data"] ], true );
					}else if( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ){
						$this->class_settings["current_record_id"] = $_POST[ "id" ];
						$e1 = $this->_get_record();
						if( isset( $e1["data"] ) && $e1["data"] ){
							$ed = json_decode( $e1["data"], true );
						}
					}
					
					foreach( $d as $dv ){
						if( isset( $_POST[ $dv ] ) ){
							$vd = $_POST[ $dv ];
							if( is_array( $_POST[ $dv ] ) ){
								$vd = implode(":::", $_POST[ $dv ] );
							}
							$ed["custom_fields"][ $dv ] = $vd; 
						}
					}
					
					$_POST[ $this->table_fields["data"] ] = json_encode( $ed );
					unset( $ed );
				}
			}
			
			

			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$tb = $this->table_name; //13-mar-23
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'parent_tb' ] = $tb;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name; //13-mar-23
				$process_handler->class_settings[ 'db_table' ] = $this->db_table_name;
			}
			if( isset( $this->plugin ) ){
				$process_handler->class_settings[ 'plugin' ] = $this->plugin;
			}
			
			//13-mar-23
			if( defined( "NWP_FORMS_STREAMLINE_UPDATE_QUERY" ) && NWP_FORMS_STREAMLINE_UPDATE_QUERY ){
				if( ! ( isset( $_POST[ 'nw_checksum' ] ) && $_POST[ 'nw_checksum' ] ) ){
					if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] && ! ( isset( $e1 ) && $e1 ) ){
						$this->class_settings["current_record_id"] = $_POST[ "id" ];
						$e1 = $this->_get_record();
					}
					if( isset( $e1 ) && $e1 && isset( $this->table_fields ) ){
						foreach( $e1 as $tk => $tv ){
							if( isset( $this->table_fields[ $tk ] ) ){
								$process_handler->class_settings[ 'attributes' ]['old_values'][ $this->table_fields[ $tk ] ] = $tv;
							}else{
								$process_handler->class_settings[ 'attributes' ]['old_values'][ $tk ] = $tv;
							}
						}
						$process_handler->class_settings[ 'attributes' ]['old_values'][ "_t" ] = $tb;
						$process_handler->class_settings[ 'attributes' ]['old_values'][ "_s" ] = "_cls";
					}
				}
			}
			//print_r( $process_handler->class_settings[ 'attributes' ] );exit;
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			// print_r( $this->plugin );exit;
			
			//wait until all tables have been updated
			/* $GLOBALS[ 'table_fields' ] = $this->table_fields;
			if( isset( $this->class_settings[ 'table_fields' ] ) && $this->class_settings[ 'table_fields' ] ){
				$GLOBALS[ 'table_fields' ] = $this->class_settings[ 'table_fields' ];
			} */
			
			// print_r( 'mike' );exit;
			$returning_html_data = $process_handler->process_handler();
			
			if( isset( $process_handler->class_settings['more_form_data'] ) ){
				$this->class_settings['more_form_data'] = $process_handler->class_settings['more_form_data'];
			}
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					
					if( method_exists( $this, "__after_save_changes" ) ){
						$r = $this->__after_save_changes( $returning_html_data );
					}
					
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$returning_html_data['record'] = $this->_get_record();
					
					if( method_exists( $this, "__after_save_changes_and_refresh" ) ){
						$r = $this->__after_save_changes_and_refresh( $returning_html_data );
					}
					
					if( isset( $_GET[ 'empty_container' ] ) && $_GET[ 'empty_container' ] ){
						$returning_html_data[ 'html_replacement_selector_two' ] = '#' . $_GET[ 'empty_container' ];
						$returning_html_data[ 'html_replacement_two' ] = '&nbsp;';
						
						if( isset( $r ) ){
							$r[ 'html_replacement_selector_two' ] = '#' . $_GET[ 'empty_container' ];
							$r[ 'html_replacement_two' ] = '&nbsp;';
						}
					}
					// print_r( $returning_html_data );exit;
					
					if( isset( $r ) && isset( $r["status"] ) && $r["status"] == "new-status" ){
						return $r;
					}
				}
			}
			
			return $returning_html_data;
		}
		
		public function before_save_prevent_duplicates(){
			return $this->_before_save_prevent_duplicates();
		}
		
		protected function _before_save_prevent_duplicates(){
			
			$id = ( isset( $_POST["id"] ) && $_POST["id"] )?$_POST["id"]:'';
		
			if( isset( $this->prevent_duplicate["fields"] ) && ! empty( $this->prevent_duplicate["fields"] ) ){
				
				$dup_where = array();
				$dup_select = array();
				$dup_selected = array();
				
				foreach( $this->prevent_duplicate["fields"] as $v ){
					
					if( isset( $this->table_fields[ $v ] ) ){
						$val = '';
						if( isset( $_POST[ $this->table_fields[ $v ] ] ) ){
							$val = trim( strtolower( $_POST[ $this->table_fields[ $v ] ] ) );
						}else if( isset( $_POST[ $v ] ) ){
							$val = trim( strtolower( $_POST[ $v ] ) );
						}
						
						$fs = isset( $this->prevent_duplicate["field_settings"][ $v ] )?$this->prevent_duplicate["field_settings"][ $v ]:array();
						$skip_if_empty = isset( $fs['skip_if_empty'] )?$fs['skip_if_empty']:0;
						
						if( ! $skip_if_empty ){
							if( isset( $fs['skip_values'] ) && is_array( $fs['skip_values'] ) && in_array( $val, $fs['skip_values'] ) ){
								$val = 1;
								$skip_if_empty = 1;
							}
						}
						
						if( ! $val || ( ! $val && $skip_if_empty ) ){
							continue;
						}
						
						$dup_selected[ $v ] = $this->table_fields[ $v ];
						$dup_select[] = " `".$this->table_name."`.`".$this->table_fields[ $v ]."` as '". $v ."' ";
						
						$dup_where[] = " TRIM( LOWER( `".$this->table_name."`.`".$this->table_fields[ $v ]."` ) ) = '". addslashes( $val ) ."' ";
					}
					
				}
					//print_r( $dup_where );exit;
				
				if( ! empty( $dup_where ) ){
					
					$condition = "AND";
					if( isset( $this->prevent_duplicate["condition"] ) && $this->prevent_duplicate["condition"] ){
						$condition = $this->prevent_duplicate["condition"];
					}
					
					$dup_select[] = " `id` ";
					$dup_select[] = " `creation_date` ";
					$dup_select[] = " `created_by` ";
					
					$this->class_settings["overide_select"] = implode( ", ", $dup_select );
					$this->class_settings["where"] = " AND ( " . implode( " ".$condition." ", $dup_where ) . " )";
					if( method_exists( $this, '_addition_duplicate_where' ) ){
						$this->class_settings["where"] .= $this->_addition_duplicate_where();
					}																		  
					
					if( $id ){
						$this->class_settings["where"] .= " AND `id` != '". $id ."'";
					}
					
					$r = $this->_get_records();

					if( isset( $r[0]["id"] ) && $r[0]["id"] ){
						$msg = '';
						
						if( isset( $this->prevent_duplicate["error_title"] ) && $this->prevent_duplicate["error_title"] ){
							$msg .= '<h4>'. $this->prevent_duplicate["error_title"] .'</h4>';
						}else{
							$msg .= '<h4>Duplicate Record</h4>';
						}
						
						
						if( isset( $this->prevent_duplicate["error_message"] ) && $this->prevent_duplicate["error_message"] ){
							$msg .= '<p>'. $this->prevent_duplicate["error_message"] .'</p>';
						}else{
							$msg .= '<p>There is an existing record:</p>';
						}
						
						$msgs = array();
						
						$labels = array();
						
						$tb = $this->table_name;
						if( function_exists( $tb ) ){
							$labels = $tb();
						}
						
						foreach( $dup_selected as $dk => $dv ){
							if( isset( $r[0][ $dk ] ) ){
								$lb = isset( $labels[ $dv ][ "field_label" ] )?$labels[ $dv ][ "field_label" ]:$dk;
								$fi = isset( $labels[ $dv ][ "field_identifier" ] )?$labels[ $dv ][ "field_identifier" ]:'';
								$fiv = isset( $labels[ $dv ][ "calculations" ][ 'reference_table' ] )?$labels[ $dv ][ "calculations" ][ 'reference_table' ]:'';
								// print_r( $fi );exit;
								$rval = $r[0][ $dk ];
								if( $fi && $fiv && isset( $this->prevent_duplicate["field_settings"][ $fi ][ 'calculated' ] ) && $this->prevent_duplicate["field_settings"][ $fi ][ 'calculated' ] ){
									$rval1 = get_name_of_referenced_record( array( 'id' => $rval, 'table' => $fiv ) );
									$rval = $rval1 ? $rval1 : $rval;
								}
								
								$msgs[] = $lb . ': <strong>' . $rval . '</strong>';
							}
						}
						
						$msgs[] = 'Ref: <strong>' . $r[0][ "id" ] . '</strong>';
						$msgs[] = 'Created By: <strong>' . get_name_of_referenced_record( array( "id" => $r[0][ "created_by" ], "table" => "users" ) ) . '</strong>';
						$msgs[] = 'Created On: <strong>' . date("d-M-Y", doubleval( $r[0][ "creation_date" ] ) ) . '</strong>';
						
						$msg .= '<ul><li style="list-style:none; padding:5px 0;">' . implode( '</li><li style="list-style:none; padding:5px 0;">', $msgs ) . '</li></ul>';
						
						$return = $this->_display_notification( array( "type" => "error", "message" => $msg ) );
						
						
						return $return;
					}
				}
			}
		
		}
		
		protected function _get_customer_call_log(){
			return $this->_get_record();
		}
		
		public function _get_record(){
			$db_table = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$db_table = $this->db_table_name;
			}
			
			$cache_key = $this->table_name;
			if( ! isset( $this->class_settings[ 'current_record_id' ] ) )return 0;
			
				// print_r( '$select' );exit;
			//print_r( $this->class_settings[ 'current_record_id' ] );exit;
			$settings = array(
				'cache_key' => $cache_key . "-" . $this->class_settings[ 'current_record_id' ],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			$return_all = 0;
			
			$returning_array = array(
				'html' => '',
				'status' => 'get-product-features',
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK IF CACHE IS SET
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
				
			}
			$select = $this->_get_select();
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$db_table."` ";
			
			if( isset( $this->class_settings[ 'cache_where' ] ) && $this->class_settings[ 'cache_where' ] ){
				if( isset( $this->class_settings[ 'cache_join' ] ) && $this->class_settings[ 'cache_join' ] ){
					$query .= $this->class_settings[ 'cache_join' ];
					unset( $this->class_settings[ 'cache_join' ] );
				}
				$query .= " WHERE `".$db_table."`.`record_status` = '1' " . $this->class_settings[ 'cache_where' ];
				$return_all = 1;
				unset( $this->class_settings[ 'cache_where' ] );
			}else{
				if( $this->build_list && $this->build_list_condition ){
					$query .= " where `".$db_table."`.`record_status` = '1' ";
				}else{
					if( $this->class_settings[ 'current_record_id' ] == 'pass_condition' ){
						$query .= " where `".$db_table."`.`record_status` = '1' ";
						
						if( isset( $this->class_settings["limit"] ) && $this->class_settings["limit"] ){
							$query .= $this->class_settings["limit"];
						}
						
					}else{
						 if( isset( $this->class_settings[ 'default_reference_field_id' ] ) && $this->class_settings[ 'default_reference_field_id' ] && $this->default_reference_field && isset( $this->table_fields[ $this->default_reference_field ] ) ){
							$query .= " where  `".$db_table."`.`" . $this->table_fields[ $this->default_reference_field ] . "` = '".$this->class_settings[ 'default_reference_field_id' ]."' AND `".$db_table."`.`record_status` = '1' ";
						 }else{
							$query .= " where `".$db_table."`.`id` = '".$this->class_settings[ 'current_record_id' ]."' AND `".$db_table."`.`record_status` = '1' ";
						 }
					}
				}
			}
			
			/*
			switch( $this->table_name ){
			case "leave_requests":
			//case "leave_types":
			//case "users":
				echo $query; //exit;
			break;
			case "investigation":
			case "assets_category":
				echo $query; exit;
			break;
			}
			*/
			
			//echo $query; exit;
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				//'skip_log' => 1,
				'set_memcache' => 1,
				'tables' => array( $db_table ),
			);
			$query_settings[ 'parent_tb' ] = $this->table_name;
			$query_settings[ 'table_name' ] = $db_table;
			$query_settings[ 'record_id' ] = $this->class_settings[ 'current_record_id' ];
			$query_settings[ 'action_to_perform' ] = $this->class_settings[ 'action_to_perform' ];
			if( isset( $this->plugin ) && $this->plugin ){
				$query_settings[ 'plugin' ] = $this->plugin;
			}
			switch( $db_table ){
			case 'hmo_bill':
				// print_r( $query );exit;
			break;
			}
			
			$all_departments = execute_sql_query($query_settings);
			
			$list = array();
			$departments = array();
			
			$this->class_settings[ 'cache_count' ] = count( $all_departments );
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				
				if( $this->build_list && $this->build_list_field ){
					$settings = array(
						'cache_key' => $cache_key . "-list",
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$list = get_cache_for_special_values( $settings );
				}
				
				foreach( $all_departments as $category ){
					
					if( $this->build_list && $this->build_list_field ){
						if( is_array( $this->build_list_field ) ){
							$list[ $category["id"] ] = $category;
						}else{
							$f1 = isset( $category[ $this->build_list_field ] )?$category[ $this->build_list_field ]:$category["id"];
							$f2 = '';
							if( isset( $this->build_list_field1 ) && $this->build_list_field1 ){
								$f2 = ' ' . ( isset( $category[ $this->build_list_field1 ] )?$category[ $this->build_list_field1 ]:'' );
							}
							
							if( isset( $this->build_list_field_bracket ) && $this->build_list_field_bracket ){
								$f2 = ' (' .trim($f2) . ') ';
							}
							$list[ $category["id"] ] = $f1 . $f2;
						}
						
					}
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key . "-" . $category["id"],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					if( $this->reset_members_cache ){
						switch( $this->reset_members_cache ){
						case "group_by_default_reference_field":
							if( $this->default_reference_field && isset( $category[ $this->default_reference_field ] ) ){
								$this->_reset_members_cache( $category );
							}
						break;
						}	
					}
				}
				
				if( $this->build_list && $this->build_list_field ){
					
					$settings = array(
						'cache_key' => $cache_key . "-list",
						'cache_values' => $list,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				if( isset( $this->class_settings[ 'cache_where_result' ] ) && $this->class_settings[ 'cache_where_result' ] ){
					unset( $this->class_settings[ 'cache_where_result' ] );
					return $all_departments;
				}
				
				if( $return_all ){
					return $all_departments;
				}
				
				return $category;
			}
			
		}
		
		public function _get_select( $o = array() ){
			$tb = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name;
			}
			$table = isset( $o["table_name"] )?$o["table_name"]:$tb;
			$table_fields = isset( $o["table_fields"] )?$o["table_fields"]:$this->table_fields;
			
			if( defined("HYELLA_LYTICS_CONNECTED") ){
				$fields = array();
				switch( DB_MODE ){
				case 'mssql':
					$query = $table;
				break;
				default:
					$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$table."`";
				break;
				}
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'query' => $query,
					'query_type' => 'DESCRIBE',
					'set_memcache' => 1,
					'tables' => array( $table ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( $sql_result && is_array( $sql_result ) ){
					foreach( $sql_result as $sval ){
						$fields[ $sval["Field"] ] = $sval;
					}
				}
				
			}else{
				if( isset( $o["fields"] ) && ! empty( $o["fields"] ) ){
					$fields = $o["fields"];
				}
			}
			
			$tf = $table_fields;
			$tf["id"] = "id";
			$tf["serial_num"] = "serial_num";
			$tf["created_by"] = "created_by";
			$tf["modification_date"] = "modification_date";
			$tf["creation_date"] = "creation_date";
			$tf["modified_by"] = "modified_by";
			
			$sels = array();
			foreach( $tf as $key => $val ){
				if( isset( $fields ) ){
					if( isset( $fields[ $val ] ) ){
						$sels[] = " `".$table."`.`".$val."` as '".$key."' ";
					}
				}else{
					$sels[] = " `".$table."`.`".$val."` as '".$key."' ";
				}
			}
			
			return implode( ",", $sels );
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->table_name.'-'.$record[ $this->default_reference_field ],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$members = get_cache_for_special_values( $settings );
			
			if( ! is_array( $members ) )$members = array();
			
			if( is_array( $members ) ){
				if( $clear ){
					unset( $members[ $record['id'] ] );
				}else{
					$members[ $record['id'] ] = $record;
				}
				$settings = array(
					'cache_key' => $cache_key.'-'.$this->table_name.'-'.$record[ $this->default_reference_field ],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
		
		protected function _store_extracted_line_items_data(){
			//STORE DATA IN exploration_drilling TABLE
			/*---------------------------*/
			$error_msg = "";
			
			if( isset($_GET['nwp_source_tb']) && $_GET['nwp_source_tb'] ){
				
				$tb = $_GET['nwp_source_tb'];
				
				if( isset($_GET['nwp_source_plugin']) && $_GET['nwp_source_plugin'] ){
					$clp = 'c'.ucwords( $_GET['nwp_source_plugin'] );
					if( class_exists( $clp ) ){
						$ptb = new $clp();
						$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
						if( isset( $in[ $tb ]->table_name ) ){
							$class = $in[ $tb ];
							$this->table_fields = $class->table_fields;
							$this->table_name = $class->table_name;
						}else{
							$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
						}
					}else{
						$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
					}
				}else{
					//$this->table_name = $_GET['nwp_source_tb'];
					$cls = 'c'.ucwords( $_GET['nwp_source_tb'] );
					if( class_exists( $cls ) ){
						$class = new $cls();
						$this->table_fields = $class->table_fields;
						$this->table_name = $class->table_name;
					}else{
						$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
					}
				}
			}
			$cache_key = $this->table_name;
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => "error", "message" => $error_msg ) );
			}
			
			//for testing only
			if( isset($_GET['id']) )
				$_POST['id'] = $_GET['id'];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$run_in_background = get_import_items_in_background_settings();
				
				$running_in_background = 0;
				if( $this->class_settings['running_in_background'] && $this->class_settings['running_in_background'] ){
					$run_in_background = 0;
					$running_in_background = 1;
					
					$running_in_background = $_POST['id'];
					
					$xdata = array();
					$xdata["processing"] = 1;
					$xdata["pending"] = 0;
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />Commencing '.$this->label.' Loading Sequence';
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
				}
				
				if( $run_in_background ){
					
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => $this->class_settings["action_to_perform"], 
							"user_id" => $this->class_settings["user_id"], 
							"reference" => $_POST['id'],
						) 
					);
					
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Loading Extracted Excel Records</h4><p>Please wait...</p>';
					$return = $err->error();

					$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";

					$return["status"] = 'new-status';
					$return["html_replacement"] = $return["html"];
					$return["html_replacement_selector"] = "#".$ccx;
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = $_POST['id'];
					$return['action'] = '?action='. $this->table_name .'&todo=check_background_process';
					
					return $return;
				}
				
				set_time_limit(0);
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$return_table = 1;
				$barcode_items = array();
				
				//RETRIEVE EXTRACTED LINE ITEMS
				$settings = array(
					'cache_key' => 'items_raw_data_import-line-items-data-'.$this->class_settings['current_record_id'],
					'directory_name' => $this->table_name,
				);
				$line_items = get_cache_for_special_values( $settings );
				//print_r( $line_items ); exit;
				
				if( $running_in_background ){
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.number_format( count( $line_items ), 0 ).' '.$this->label.' are waiting to be Loaded';
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				//check for existing line items
				//$existing_line_items = $this->_get_existing_itemss();
				$existing_line_items = array();
				
				$array_of_dataset = array();
				$array_of_dataset_update = array();
				$array_of_update_conditions = array();
				
				//$new_record_id = get_new_id();
				$new_record_id = $this->class_settings['current_record_id'];
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				$unique = get_new_id();
				
				//$store = get_current_store(1);
				$store = get_main_store();
				$default_values = isset( $this->basic_data["import_default"] )?$this->basic_data["import_default"]:array();
				
				$previous_pre_key = "";
				
				foreach( $line_items as $k => $v ){
					
					$dataset_to_be_inserted = array(
						'id' => $new_record_id .'CTS'. ++$new_record_id_serial,
						'creator_role' => $unique,
						'created_by' => $this->class_settings[ 'user_id' ],
						'creation_date' => $date,
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
					);
					
					if( ! empty( $v ) ){
						foreach( $v as $kv => $vv ){
							if( isset( $this->table_fields[ $kv ] ) ){
								$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $vv;
							}
						}
						
						if( isset( $this->table_fields[ "store" ] ) && ! ( isset( $dataset_to_be_inserted[ $this->table_fields[ "store" ] ] ) && $dataset_to_be_inserted[ $this->table_fields[ "store" ] ] ) ){
							$dataset_to_be_inserted[ $this->table_fields[ "store" ] ] = $store;
						}
						
						if( ! empty( $default_values ) ){
							foreach( $default_values as $kv => $vv ){
								if( isset( $this->table_fields[ $kv ] ) && isset( $vv["value"] ) ){
									if( ! ( isset( $dataset_to_be_inserted[ $this->table_fields[ $kv ] ] ) && $dataset_to_be_inserted[ $this->table_fields[ $kv ] ] ) ){
										$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $vv["value"];
									}
								}
							}
						}
						$array_of_dataset[] = $dataset_to_be_inserted;
					}
					
				}
				
				if( $running_in_background ){
					$text = $this->label . " Data Transformation Completed";
					echo $text . " \n\n";
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
					
					$text = number_format( $new_record_id_serial, 0 ) . " ".$this->label." will be Created";
					echo $text . " \n\n";
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				$saved = 0;
				
				if( ! empty( $array_of_dataset ) ){
					
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset,
					);
					
					$returned_data = insert_new_record_into_table( $function_settings );
					$saved = 1;
					
					if( $running_in_background ){
						$text = "Finished Creating New " . $this->label;
						echo $text . " \n\n";
						$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
						
						$settings = array(
							'cache_key' => $cache_key . '-' . $running_in_background,
							'directory_name' => $cache_key,
							'cache_values' => $xdata,
							'permanent' => true,
						);
						set_cache_for_special_values( $settings );
					}
				}
				
				if( ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset_update,
					);
					
					$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
					
					$returned_data = insert_new_record_into_table( $function_settings );
					
					$saved = 1;
				}
				
				if( $saved ){
					//update cache
					//$this->class_settings['where'] = " AND `".$this->table_name."`.`modification_date` = ".$date;
					$this->class_settings['where'] = " AND `".$this->table_name."`.`creator_role` = '".$unique . "' ";
					$this->class_settings['overide_select'] = " COUNT(*) as 'count' ";
					$sc = $this->_get_records();
					
					if( isset( $sc[0]['count'] ) && $sc[0]['count'] ){
						$error_msg = '<h4>'.number_format( doubleval( $sc[0]['count'] ), 0 ).' New Entries</h4>The import operation was successful';
						
						$return = $this->_display_notification( array( "type" => "success", "message" => $error_msg, "do_not_display" => 1, "html_replacement_selector" => '#excel-file-import-progress-list' ) );
					}else{
						//delete changes
						$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`creator_role` = '".$unique . "' ";
				
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'UPDATE',
							'set_memcache' => 0,
							'tables' => array( $this->table_name ),
						);
						execute_sql_query($query_settings);
						
						$error_msg = '<h4>Import Failed</h4>Changes were not saved';
						return $this->_display_notification( array( "type" => "error", "message" => $error_msg, "do_not_display" => 1, "html_replacement_selector" => '#excel-file-import-progress-list' ) );
					}
					
					
					$this->class_settings['where'] = " AND `".$this->table_name."`.`creator_role` = '".$unique . "' ";
				}
				
				
				/* $err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Loading Extracted Excel Rows &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Started Creation of Load Queries' ),
						1 => array( 'title' => 'Successfully Created Load Queries' ),
						2 => array( 'title' => 'Started Loading Data' ),
						3 => array( 'title' => 'Successfully Loaded Data' ),
						4 => array( 'title' => 'Finished! <i class="icon-thumbs-up-alt"></i>' ),
					),
				);
				
				
				$return['html_replacement_selector'] = '#excel-file-import-progress-list';
				$return['html_replacement'] = $this->_get_html_view(); */
				
				/* $return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view(); */
				//settings for ajax request reprocessing
				/* $return['re_process'] = 1;
				$return['re_process_code'] = 1;
				
				$return['action'] = '?action='.$this->table_name.'&todo=display_all_records_full_view'; */
				
				if( isset( $this->class_settings['where'] ) && $this->class_settings['where'] ){
					$this->class_settings['where_clause'] = $this->class_settings['where'];
					$this->class_settings['cache_where'] = $this->class_settings['where_clause'];
					
					$this->class_settings["return_data"] = 0;
					$this->class_settings["query_cache"] = 1;
					$this->class_settings["do_not_check_cache"] = 1;
					$this->_refresh_cache();
				}
				
				
				if( $running_in_background ){
					$return = array();
					$return['status'] = 'new-status';
					/* $return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "-";
					$return['action'] = '?action='. $this->table_name .'&todo=display_all_records_full_view'; */
					$xdata["returned_data"] = $return;
					
					$text = $this->label . " Loading Completed " . $running_in_background;
					echo $text . " \n\n";
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
					$xdata["status"] = 1;
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		public function _display_notification( $params = array() ){
			$code = '010014';
			$msg = isset( $params["message"] )?$params["message"]:'';
			$do_not_display = isset( $params["do_not_display"] )?$params["do_not_display"]:0;
			
			$html_add = isset( $params["html_add"] )?$params["html_add"]:'';
			$html_replacement = isset( $params["html_replacement"] )?$params["html_replacement"]:'';
			
			$html_replacement_selector = isset( $params["html_replacement_selector"] )?$params["html_replacement_selector"]:'';
			$js = isset( $params["callback"] )?$params["callback"]:array();
			$data = isset( $params["data"] )?$params["data"]:array();
			
			if( isset( $params["type"] ) ){

				switch( $params["type"] ){
				case "success":
					$code = '010011';
				break;
				case "info":
					$code = '010016';
				break;
				case "warning":
					$code = '010018';
				break;
				case "no_language":
					$code = '000017';
				break;
				}
			}
			
			$err = new cError( $code );
			$err->html_format = 2;
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = isset( $this->class_settings['action_to_perform'] )?$this->class_settings['action_to_perform']:'';
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			
			if( isset( $params[ 'manual_close' ] ) && $params[ 'manual_close' ] ){
				$return["manual_close"] = 1;
			}
			
			$new = 0;
			if( $html_replacement_selector ){
				$return["html_replacement_selector"] = $html_replacement_selector;
				$return["html_replacement"] = $return["html"];
				
				if( $html_add ){
					$return["html_replacement"] = $return["html"] . $html_add;
				}
				
				if( $html_replacement ){
					$return["html_replacement"] = $html_replacement;
				}
				
				if( $do_not_display ){
					unset( $return["msg"] );
					unset( $return["typ"] );
				}
				$new = 1;
			}
			
			if( ! empty( $js ) ){
				$new = 1;
				$return["javascript_functions"] = $js;
			}
			
			if( ! empty( $data ) ){
				$new = 1;
				$return["data"] = $data;
			}
			
			if( $new ){
				$return["status"] = "new-status";
				unset( $return["html"] );
			}
			
			$return["nwp_status"] = isset( $params["type"] )?$params["type"]:"";
			
			return $return;
		}
		
		protected function _generate_quotation( $e = array() ){
			if( isset( $e["line_items"] ) && ! empty( $e["line_items"] ) ){
				$q = new cQuotation();
				$q->class_settings = $this->class_settings;
				$q->class_settings[ "action_to_perform" ] = 'save_line_items';
				
				if( isset( $e[ "reference" ] ) && isset( $e[ "reference_table" ] ) ){
					$q->class_settings[ "reference" ] = $e[ "reference" ];
					$q->class_settings[ "reference_table" ] = $e[ "reference_table" ];
				}
				
				if( isset( $e[ "clear_ids" ] ) ){
					$q->class_settings[ "clear_ids" ] = $e[ "clear_ids" ];
				}
				
				foreach( $e["line_items"] as $dk => & $dv ){
					$total = 0;
					$desc = array();
					if( ( isset( $dv["data"] ) && $dv["data"] ) ){
						$jd = json_decode( $dv["data"], true );
						if( isset( $jd["services"] ) && $jd["services"] ){
							foreach( $jd["services"] as $sk => & $s ){

								if( ! isset( $s["date"] ) ){
									$s["date"] = date("U");
								}
								
								if( isset( $s["description"] ) && $s["description"] ){
									$desc[] = $s["description"];
								}else if( isset( $s["text"] ) && $s["text"] ){
									$desc[] = $s["text"];
								}
								
								if( isset( $s["line_total"] ) && $s["line_total"] ){
									$total += $s["line_total"];
								}else if( isset( $s["quantity"] ) && $s["quantity"] && isset( $s["selling_price"] ) && $s["selling_price"] ){
									$total += doubleval( $s["selling_price"] ) * doubleval( $s["quantity"] );
									if( isset( $s["discount"] ) && $s["discount"] ){
										$total -= doubleval( $s["discount"] );
									}
								}
							}
							
							 // mike Nov 1, 2022
							if( isset( $jd[ 'services' ] ) && ! empty( $jd[ 'services' ] ) ){
								$dv["data"] = json_encode( $jd );
							}else{
								unset( $e["line_items"][ $dk ] );
								continue;
							}
						}
					}
					if( ! ( isset( $dv["description"] ) && $dv["description"] ) ){
						$dv["description"] = implode("<br />", $desc );
					}
					if( ! ( isset( $dv["amount_due"] ) && $dv["amount_due"] ) ){
						$dv["amount_due"] = $total;
					}
				}

				// echo 'e22';print_r($e); 
				if( ! empty( $e["line_items"] ) ){
					$q->class_settings[ "line_items" ] = $e["line_items"];
					return $q->quotation();
				}
			}
		}
		
		protected function _notify_creator_of_changes( $d = array(), $options = array() ){
			if( ! empty( $d ) && isset( $options["action"] ) ){
				
				$this->class_settings["overide_select"] = " `id`, `creation_date`, `created_by`, `modification_date`, `modified_by`, '". $this->table_name ."' as 'table' ";
				$this->class_settings["where"] = " WHERE `id` IN (" . implode( ',', $d ) . ") AND `record_status` = '0' AND `created_by` != `modified_by` ";
				$this->class_settings["no_where"] = 1;
				
				$dr = $this->_get_records();
				$recipients = array();
				
				$pr = get_project_data();
				
				if( isset( $dr[0]["id"] ) ){
					foreach( $dr as $drv ){
						
						$recipients[ $drv["created_by"] ]["message"][] = '<tr><td><a href="'. $pr["domain_name"] .'deleted-record.php?id='. md5( 'deleted-' . $drv["id"] ) .'&source='. $this->table_name .'" target="_blank" title="Click to view details">'. $drv["id"] .'</a></td><td>'. date("d-M-Y", doubleval( $drv["creation_date"] ) ) .'</td><td>'. get_name_of_referenced_record( array( "id" => $drv["modified_by"], "table" => "users" ) ) . ' ' . date("d-M-Y H:i", doubleval( $drv["modification_date"] ) ) .'</td></tr>';
					}
				}
				
				if( ! empty( $recipients ) ){
					
					$subject = 'DELETE RECORDS NOTIFICATION';
					
					foreach( $recipients as $rvs_id => $rvs ){
						
						$content = '<div class="report-table-preview-20"><table class="table table-bordered table-striped" cellspacing="0" width="100%"><thead><tr><td>Record Ref</td><td>Creation Date</td><td>Deleted By</td></tr></thead><tbody>'. implode( "", $rvs["message"] ) .'</tbody></table></div>';
						
						$opt = array(
							"content" => $content,
							"subject" => strtoupper( $subject ),
							"record_id" => $rvs_id,
							"record_table" => 'users',
							"system_notification" => array( $rvs_id ),
							"trigger" => $options["action"],
						);
						$this->_send_email_notification( $opt, array() );
					}
				}
			}
		}
		
		protected function _view_deleted(){
			$error_type = 'error';
			$error_msg = '';
			
			$return = array();
			
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings["do_not_show_header"] = 1;
			$web_data = $website->_setup_website();
			
			if( isset( $_GET["source"] ) && $_GET["source"] && isset( $_GET["id"] ) && $_GET["id"] ){
				$tb = strtolower( trim( $_GET["source"] ) );
				$ctb = "c" . ucwords( $tb );
				
				if( class_exists( $ctb ) ){
					$cl = new $ctb();
					$cl->class_settings = $this->class_settings;
					$cl->class_settings["action_to_perform"] = "get_data";
					$cl->class_settings["no_where"] = 1;
					$cl->class_settings["where"] = " WHERE MD5( CONCAT( 'deleted-', `id` ) ) = '" . clean_ids( trim( $_GET["id"] ) ) . "'";
					$r = $cl->$tb();
					
					if( isset( $r[0]["id"] ) ){
						$_POST["id"] = $r[0]["id"];
						$_GET["modal"] = 1;
						
						$cl->class_settings = $this->class_settings;
						$cl->class_settings["data_items"] = array( $r[0]["id"] => $r[0] );
						$cl->class_settings["action_to_perform"] = "view_details";
						$r2 = $cl->$tb();
						
						if( ! isset( $r2["html_replacement"] ) ){
							$this->table_fields = $cl->table_fields;
							$this->table_name = $cl->table_name;
							$this->class_settings["action_to_perform"] = "view_details";
							$this->class_settings["data_items"] = $cl->class_settings["data_items"];
							$r2 = $this->_view_details();
						}
						
						if( isset( $r2["html_replacement"] ) ){
							
							$r2["html"] = '<div class="row"><div class="col-lg-6 col-md-offset-3"><div class="note note-warning"><h4><strong>Deleted Record: #'. $r[0]["id"] .'</strong></h4><p>Created By: '. get_name_of_referenced_record( array( "id" => $r[0]["created_by"], "table" => "users" ) ) .' '. date("d-M-Y H:i", doubleval( $r[0]["creation_date"] ) ) .'</p><p>Deleted By: '. get_name_of_referenced_record( array( "id" => $r[0]["modified_by"], "table" => "users" ) ) .' '. date("d-M-Y H:i", doubleval( $r[0]["modification_date"] ) ) .'</p></div>' . $r2["html_replacement"] . '</div></div>';
							
							$return = $r2;
						}else{
							$error_msg = '<h4>Unable to Render View</h4>';
						}
					}else{
						$error_msg = '<h4>Deleted Record was not Found</h4>';
					}
				}
			}
			
			if( $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>';
				$return = $this->_display_notification( array( "type" => $error_type, "message" => $error_msg ) );
			}
			
			//$return["status"] = "new-status";
			//unset( $return["html"] );
			
			return array_merge( $web_data, $return );
		}
		
		protected function _save_display_spreadsheet_view( $o1 = array() ){
			
			$error_msg = '';
			
			if( isset( $_POST["s_table"] ) && $_POST["s_table"] && isset( $_POST["data"] ) && $_POST["data"] ){
				$js = array( "nwBulkDataCapture.clear" );
				$s_plugin = isset( $_POST["s_plugin"] )?$_POST["s_plugin"]:'';
				$s_preview = isset( $_POST["s_preview"] )?$_POST["s_preview"]:0;
				$json = json_decode( $_POST["data"], true );
				
				$columns = array();
				$columns2 = array();
				$data = array();
				
				$s_tb = $_POST["s_table"];
				
				if( $s_plugin ){
					$clp = 'c' . ucwords( strtolower( $s_plugin ) );
					if( class_exists( $clp ) ){
						$ptb = new $clp();
						$in = $ptb->load_class( array( 'class' => array( $s_tb ), 'initialize' => 1 ) );
						if( isset( $in[ $s_tb ]->table_name ) ){
							$cl = $in[ $s_tb ];
						}else{
							$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $s_tb ."' is invalid or missing in the project.</p>";
						}
					}
				}
				
				if( ! $error_msg ){
					if( $s_tb != $this->table_name ){
						$cs = 'c' . ucwords( $s_tb );
						if( class_exists( $cs ) ){
							$cl = new $cs();
						}else{
							$error_msg = '<h4>Class Not Found</h4>';
						}
					}else{
						$cl = $this;
					}
				}
				
				if( ! $error_msg ){
					$this->table_fields = $cl->table_fields;
					$this->table_name = $cl->table_name;
					$this->label = ( isset( $cl->label )?$cl->label:$cl->table_name );
					
					if( isset( $cl->spreadsheet_options ) && $cl->spreadsheet_options ){
						$this->spreadsheet_options = $cl->spreadsheet_options;
					}
				}
				$tb = $this->table_name;
				if( isset( $o1["labels"] ) && ! empty( $o1["labels"] ) ){
					$table_labels = $o1["labels"];
					//print_r( $table_labels ); exit;
				}else if( function_exists( $tb ) ){
					$table_labels = $tb();
				}
				
				if( ! $error_msg ){
					if( isset( $json["columns"] ) && is_array( $json["columns"] ) && ! empty( $json["columns"] ) ){
						foreach( $json["columns"] as $k => $cval ){
							$columns2[ $cval["data"] ] = $k;
							$columns[ $k ] = $cval["data"];
							
							if( isset( $this->table_fields[ $cval["data"] ] ) ){
								$k1 = $this->table_fields[ $cval["data"] ];
								
								if( isset( $table_labels[ $k1 ] ) ){
									
									switch( $table_labels[ $k1 ]['form_field'] ){
									case "select":
									case 'radio':
										/* 
										if( isset( $table_labels[ $k1 ]['form_field_options'] ) && $table_labels[ $k1 ]['form_field_options'] ){
											$f2 = $table_labels[ $k1 ]['form_field_options'];
											if( function_exists( $f2 ) ){
												$f3 = $f2();
												if( is_array( $f3 ) && ! empty( $f3 ) ){
													
													$f2 = $table_labels[ $k1 ]['form_field_options_array'] = $f3;
													
													foreach( $f3 as $f3k => $f3v ){
														$table_labels[ $k1 ]["form_field_options_reversed"][ clean3( strtolower( $f3v ) ) ] = $f3k;
													}
												}
											}
										}
										*/
									break;
									}
								}
							}
							
						}
					}else{
						$error_msg = '<h4>Invalid Column Details</h4>';
					}
					
					if( isset( $json["data"] ) && is_array( $json["data"] ) && ! empty( $json["data"] ) ){
						$data = $json["data"];
					}else{
						$error_msg = '<h4>Invalid Changes</h4>';
					}
				}
				
				$success_msg = '';
				$line_items = array();
				$line_items_skipped = array();
				
				if( ! $error_msg ){
					//print_r( $columns );
					//print_r( $data ); exit;
					$clear_ids = array();
					$new_records = 0;
					$update_records = 0;
					
					foreach( $data as $dk => $dval ){
						$lt = array();
						$record_id = '';
						$record_id2 = get_new_id() . 'bk';
						$record_serial = 0;
						$row_add = 0;
						$skip = 0;
						$empty_row = 1;
						
						foreach( $columns2 as $ck => $cv ){
							
							$add = 0;
							$add_val = '';
							if( isset( $dval[ $cv ] ) && $dval[ $cv ] ){
								$add_val = trim( $dval[ $cv ] );
								$add = 1;
							}
							
							switch( $ck ){
							case "id":
								$add = 0;
								$record_id = $add_val;
								//$add_val = $record_id2 . ++$record_serial;
							break;
							default:
								
								if( isset( $this->table_fields[ $ck ] ) ){
									$k1 = $this->table_fields[ $ck ];
									
									if( isset( $table_labels[ $k1 ] ) ){
										
										switch( $table_labels[ $k1 ]['form_field'] ){
										case "select":
										case 'radio':
											/* if( isset( $table_labels[ $k1 ]['form_field_options_array'] ) && isset( $table_labels[ $k1 ]['form_field_options_array'][ $add_val ] ) ){
												
												$lt[ $ck . '_text_' ] = $table_labels[ $k1 ]['form_field_options_array'][ $add_val ];
											}else{
												$add_val3 = clean3( strtolower( $add_val ) );
												
												if( isset( $table_labels[ $k1 ]['form_field_options_reversed'] ) && isset( $table_labels[ $k1 ]['form_field_options_reversed'][ $add_val3 ] ) ){
													$add_val4 = $table_labels[ $k1 ]['form_field_options_reversed'][ $add_val3 ];
													
													
													if( isset( $table_labels[ $k1 ]['form_field_options_array'] ) && isset( $table_labels[ $k1 ]['form_field_options_array'][ $add_val4 ] ) ){
														$add_val = $add_val4;
														$lt[ $ck . '_text_' ] = $table_labels[ $k1 ]['form_field_options_array'][ $add_val4 ];
													}
												}else{
													//$skip = 1;
													//$lt[ $ck . '_flag_' ] = 1;
													$add_val = '';
												}
											}
											*/
										break;
										case 'date':
										case 'datetime':
										case "date-5time":
										case "date-5":
											$cty = 1;
											
											switch( $table_labels[ $k1 ]['form_field'] ){
											case 'datetime':
											case "date-5time":
												$cty = 107;
											break;
											}
											
											if( $add_val ){
												//$add_val .= 'T00:00:00+01:00';
												$add_val = convert_date_to_timestamp( $add_val , $cty );
											}
										break;
										case 'calculated':
											if( isset( $o1[ 'process_auto_select' ] ) && $o1[ 'process_auto_select' ] ){
												
												if( isset( $json[ 'auto_sel' ][ $dk ][ $cv ] ) && $json[ 'auto_sel' ][ $dk ][ $cv ] ){
													$add_val = $json[ 'auto_sel' ][ $dk ][ $cv ];
												}
												
											}
											// print_r( $json[ 'data' ] );exit;
										break;
										}
										
										if( isset( $table_labels[ $k1 ][ "required_field" ] ) && $table_labels[ $k1 ][ "required_field" ] == 'yes' ){
											if( ! $add_val ){
												$skip = 1;
												$lt[ $ck . '_flag_' ] = 1;
											}
										}else{
											if( $add_val )$add = 1;
										}
									}
								}
								
							break;
							}
							
							if( $add ){
								$row_add = 1;
								$lt[ '_flag_num_' ] = $dk;
								$lt[ $ck ] = $add_val;
								$empty_row = 0;
							}else{
							}
							
						}
						
						if( $skip ){
							$row_add = 0;
						}
						
						if( $row_add ){
							
							if( $record_id ){
								//edit record
								$clear_ids[] = "'". $record_id ."'";
								$lt["new_id"] = $record_id;
								++$update_records;
							}else{
								++$new_records;
							}
							
							$line_items[] = $lt;
						}else{
							if( ! $empty_row )$line_items_skipped[] = $lt;
						}
						
					}
					
					
				}
				
				/*
				if( isset( $json["deleted"] ) && is_array( $json["deleted"] ) && ! empty( $json["deleted"] ) ){
					$del = array();
					foreach( $json["deleted"] as $dk => $dv ){
						$del[] = "'". $dk ."'";
					}
					
					$success_msg .= '<p>'.number_format( count( $del ), 0 ).' Deleted Entries</p>';
					
					$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( ". implode( ",", $del ) ." ) ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
				}
				*/


				if( !$error_msg ){
					$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
					$tb = $this->table_name;
					
					$show_preview = 0;
					if( $s_preview ){
						$show_preview = 1;
					}else if( ! empty( $line_items_skipped ) && $s_preview ){
						$show_preview = 1;
						$line_items = array();
					}
					
					if( $show_preview ){
						$this->class_settings[ 'data' ]["unsaved_items"] = $line_items_skipped;
						$this->class_settings[ 'data' ]["saved_items"] = $line_items;
						
						$this->class_settings[ 'data' ]["table_fields"] = $this->table_fields;
						$this->class_settings[ 'data' ]["table_labels"] = $table_labels;
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/bulk-data-capture-preview.php' );
						$html = $this->_get_html_view();
						
						$title = 'Bulk Data Capture Preview: ' . $this->label;
						$js = array( "set_function_click_event", "prepare_new_record_form_new" );
						
						$this->class_settings[ "modal_title"] = $title;
						$this->class_settings[ "modal_dialog_style"] = "width:90%;";
						
						$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
						return $this->_launch_popup( $html, '#'.$ccx, $js );
					}else if( ! empty ( $line_items ) ){
						//sharepoint hack
						/* $r2 = $this->_save_multiple_new_popup_form( array( "data" => $line_items ) );
						$r = 0;
						if( isset( $r2["success"] ) && $r2["success"] ){
							$r = doubleval( $r2["success"] );
						}else{
							return $r2;
						} */

						if( isset( $o1[ 'return_data' ] ) && $o1[ 'return_data' ] ){
							return array( 'line_items' => $line_items );
						}
						
						//direct save
						$this->class_settings["line_items"] = $line_items;
						$r = $this->_save_line_items();
						
						if( $r ){
							$error_msg = '<h4>Successful Save Operation</h4>' . number_format( $r, 0 ) . ' records were saved';
							return $this->_display_notification( array( "message" => $error_msg, "type" => "success", "callback" => $js ) );
						}else{
							$error_msg = '<h4>Unable to Save Records</h4>';
						}
					}else{
						$error_msg = '<h4>No Records Found</h4>';
					}
				}
				
			}else{
				$error_msg = '<h4>No Pending Changes</h4>';
			}
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>';
			}
			
			return $this->_display_notification( array( "message" => $error_msg, "type" => "error" ) );
		}
		
		protected function _display_spreadsheet_view( $o = array() ){
			$error = '';
			$error_typ = 'error';
			
			$filename = '';
			$title = $this->label;
			
			$data = isset( $o[ 'data' ] )?$o[ 'data' ]:array();
			$ojs = isset( $o[ 'callback' ] )?$o[ 'callback' ]:array();
			$opt[ 'select2_keys' ] = isset( $data[ 'select2_keys' ] ) ? $data[ 'select2_keys' ] : array();
			
			$js = array( "prepare_new_record_form_new" , "set_function_click_event", "nwBulkDataCapture.activateHandsontable" );
			
			if( isset( $o[ 'auto_refresh' ] ) && $o[ 'auto_refresh' ] ){
				$js[] = "nwBulkDataCapture.autoPopuplateChanges";
			}
			
			if( ! empty( $ojs ) ){
				$js = array_merge( $js, $ojs );
			}
			
			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = $ccx;
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			
			$s_type = 'insert';
			$action_to_perform = $this->class_settings["action_to_perform"];
			switch( $action_to_perform ){
			case "display_spreadsheet_view":
				$s_type = 'replace';
			break;
			case "display_spreadsheet_new":
			break;
			}
			
			if( isset( $o["fields"] ) && ! empty( $o["fields"] ) ){
				$opt[ 'fields' ] = $o["fields"];
			}else if( isset( $this->table_fields ) && ! empty( $this->table_fields ) ){
				$labels = array();
				$tb = $this->table_name;
				if( isset( $o["labels"] ) && ! empty( $o["labels"] ) ){
					$labels = $o["labels"];
				}else if( function_exists( $tb ) ){
					$labels = $tb();
				}
				
				if( isset( $this->spreadsheet_options["options"] ) && $this->spreadsheet_options["options"] ){
					$opt = $this->spreadsheet_options["options"];
				}
				
				foreach( $this->table_fields as $key => $val ){
					$x1 = array();
					if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
						switch( $labels[ $val ][ 'display_position' ] ){
						case "display-in-table-row":
							
							if( ! ( isset( $labels[ $val ][ 'no_bulk_capture' ] ) && $labels[ $val ][ 'no_bulk_capture' ] ) ){
								
								$x1 = __get_value( '', $key, array( "get_label_and_value" => 1, "add_options" => 1, "globals" => array( "fields" => array( $key => $val ), "labels" => array( $val => $labels[ $val ] ) ) ) );
								
								$x1['type'] = 'text';
								//echo $labels[ $val ]['form_field'];
								switch( $labels[ $val ]['form_field'] ){
								case "select":
									//$x1['type'] = 'checkbox';
									///$x1['type'] = 'dropdown';
									//$x1['source'] = __get_source_list( $key );
								break;
								case 'date':
								case 'datetime':
								case "date-5time":
								case "date-5":
									$x1['type'] = 'date';
								break;
								case "number":
									$x1['type'] = 'numeric';
								break;
								case "currency":
								case "decimal":
								case "decimal_long":
									$x1['type'] = 'currency';
								break;
								case "calculated":
									if( isset( $o[ 'allow_calculated_fields' ] ) && $o[ 'allow_calculated_fields' ] ){
										$field_details = $labels[ $val ];
										if( isset( $field_details['form_field_action'] ) && $field_details['form_field_action'] ){
											$x1['action'] = '?'.$field_details['form_field_action'];
										} 
									}
									// print_r( $field_details );exit;
								break;
								}
								
								if( ! isset( $opt["required_field"] ) ){
									if( isset( $labels[ $val ]['required_field'] ) && $labels[ $val ]['required_field'] == 'yes' ){
										$x1['required'] = true;
									}
								}else if( isset( $opt["required_field"][ $key ] ) ){
									$x1['required'] = true;
								}
							}
						break;
						}
					}
					
					
					if( ! empty( $x1 ) ){
						$opt[ 'fields' ][ $key ] = $x1;
					}
				}
			}
			
			if( isset( $o[ 'empty_rows' ] ) && $o[ 'empty_rows' ] ){
				$opt[ 'empty_rows' ] = $o[ 'empty_rows' ];
				unset( $o[ 'empty_rows' ] );
			}
			
			$opt[ 'data' ] = isset( $o[ 'data_object' ] )?$o[ 'data_object' ]:array();
			$data[ 'spreadsheet' ] = $this->_display_spreadsheet( $opt );
			// print_r( $data ); exit;
			// print_r( $data[ 'spreadsheet' ] ); exit;
			
			
			$df = array();
			$df[ 'table' ] = $this->table_name;
			if( isset( $this->plugin ) && $this->plugin ){
				$df[ 'plugin' ] = $this->plugin;
			}

			$df[ 'type' ] = $s_type;
			$df[ 'container' ] = 'entire-bulk-container';
			if( isset( $o[ 'use_action' ] ) && $o[ 'use_action' ] ){
				$df[ 'action' ] = $o[ 'use_action' ];
			}else{
				$df[ 'action' ] = '?action=' . $this->table_name_static . '&todo=save_'.$action_to_perform.'&html_replacement_selector=' . $df[ 'container' ];
			}
			$df[ 'title' ] = $title;
			
			foreach( array( 'table', 'type', 'container', 'action', 'title', 'plugin' ) as $chk ){
				if( ! ( isset( $data[ $chk ] ) ) && isset( $df[ $chk ] ) ){
					$data[ $chk ] = $df[ $chk ];
				}
			}
			
			// print_r( $data );exit;
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/bulk-data-capture.php' );

			$html = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => '#'.$handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		protected function _display_spreadsheet( $opt = array() ){
			$data = array();
			$hide_width = 0.1;
			$empty_rows = intval( isset( $opt[ 'empty_rows' ] )?$opt[ 'empty_rows' ]:100 );
			$data[ 'data_object' ] = isset( $opt[ 'data' ] )?$opt[ 'data' ]:array();
			unset( $opt[ 'data' ] );

			$auto_sel = array();
			$select2_keys = isset( $opt[ 'select2_keys' ] )?$opt[ 'select2_keys' ]:array();
			
			$default_width = 250;

			if( isset( $opt[ 'fields' ] ) && is_array( $opt[ 'fields' ] ) && ! empty( $opt[ 'fields' ] ) ){

				$data[ 'colHeaders' ] = array();
				$data[ 'columns' ] = array();
				$empty_value = array();

				foreach( $opt[ 'fields' ] as $key => $field ){
					if( ! isset( $field[ 'type' ] ) ){
						$field[ 'type' ] = 'text';
					}
					
					$o = $field;
					$o["data"] = $key;

					if( ! ( isset( $field[ 'width' ] ) && $field[ 'width' ] ) )$o[ 'width' ] = $default_width;
					if( isset( $field[ 'hide' ] ) && $field[ 'hide' ] ){
						$o[ 'width' ] = $hide_width;
						unset( $o[ 'hide' ] );
					}

					if( isset( $field[ 'required' ] ) && $field[ 'required' ] ){
						$o[ 'label' ] .= ' *';
						unset( $o[ 'required' ] );
					}
					
					if( isset( $field[ 'edit' ] ) && ! $field[ 'edit' ] ){
						$o[ 'readOnly' ] = true;
						unset( $o[ 'edit' ] );
					}
					
					if( isset( $field[ 'options' ] ) && is_array( $field[ 'options' ] ) && ! empty( $field[ 'options' ] ) ){
						$o[ 'editor' ] = 'select';
						$o[ 'selectOptions' ] = $field[ 'options' ];
						$o[ 'type' ] = 'dropdown';
						unset( $o[ 'options' ] );
					}

					$ev = '';
					switch( $o[ 'type' ] ){
					case 'currency':
						$o[ 'numericFormat' ] = array(
							'pattern' => '0,0.00',
							'culture' => 'en-US',
						);
						$o["type"] = "numeric";
					break;
					case 'numeric':
						$o[ 'numericFormat' ] = array(
							'pattern' => '0,0',
							'culture' => 'en-US',
						);
					break;
					case 'date':
						$o[ 'dateFormat' ] = 'YYYY-MM-DD';
						//$o[ 'correctFormat' ] = true;
						
						$o['defaultDate'] = date("Y-m-d");
						$o['correctFormat'] = true;
					break;
					case 'time':
						$o["type"] = "time";
						$o[ 'timeFormat' ] = 'h:mm a';
						//$o[ 'timeFormat' ] = 'HH:mm';
						$o[ 'correctFormat' ] = true;
					break;
					case 'checkbox':
						$ev = false;
						/* $o[ 'label' ] = array(
							'position' => 'after',
							//'property' => $key, // Read value from row object [key to read]
							'value' => 'Yes', // Read value from row object [key to read]
						); */
					break;
					case 'html':
						$o["type"] = "text";
						$o["renderer"] = 'html';
					break;
					}
					
					
					if( isset( $field[ 'action' ] ) && $field[ 'action' ] ){
						/* $o1 = $o;
						$o1[ 'data' ] .= "_rid";
						$o1[ 'readOnly' ] = true;
						$o1[ 'width' ] = $o1[ 'width' ] / 2;
						$empty_value[ $o1[ 'data' ] ] = $ev;
						$data[ 'colHeaders' ][] = "'". ( isset( $o[ 'label' ] ) ? ( $o[ 'label' ] . " RID" ) : ucwords( $key . "_rid" ) ) ."*'";
						$data[ 'columns' ][] = $o1; */
						
						$o[ 'type' ] = 'autocomplete';
						$o[ 'strict' ] = false;
					}
					
					$empty_value[ $key ] = $ev;
					
					$data[ 'colHeaders' ][] = "'". ( isset( $o[ 'label' ] ) ? $o[ 'label' ] : ucwords( $key ) ) ."'";
					$data[ 'columns' ][] = $o;
					
				}
				
				if( empty( $data[ 'data_object' ] ) && $empty_rows ){
					for( $i = 0; $i < $empty_rows; $i++ ){
						$data[ 'data_object' ][] = $empty_value;
					}
				}
				
				$fls = array_keys( $opt[ 'fields' ] );

				// print_r( $select2_keys );
				// print_r( $fls );
				// print_r( $data );exit;
				
				if( ! empty( $data[ 'data_object' ] ) ){
					foreach( $data[ 'data_object' ] as $skk => & $sval ){
						$sval2 = array();
						foreach( $fls as $fkk => $fv ){
							$typ = isset( $fv[ 'type' ] ) ? $fv[ 'type' ] : '';

							if( isset( $sval[ $fv ] ) && $sval[ $fv ] ){
								if( isset( $select2_keys[ $fv ][ $sval[ $fv ] ] ) && $select2_keys[ $fv ][ $sval[ $fv ] ] ){
									$auto_sel[ $skk ][ $fkk ] = $select2_keys[ $fv ][ $sval[ $fv ] ];
								}
							}

							switch( $typ ){
							case 'currency':
							case 'numeric':
								if( isset( $sval[ $fv ] ) )$sval[ $fv ] = intval( $sval[ $fv ] );
								else $sval[ $fv ] = 0;
							break;
							default:
								if( ! isset( $sval[ $fv ] ) )$sval[ $fv ] = '';
							break;
							}

						}
					}
				}
				
			}

			$data[ 'auto_sel' ] = $auto_sel;

			return $data;
		}
		
		public function _display_html_only( $o = array() ){
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings["do_not_show_header"] = 1;
			$website->class_settings["action_to_perform"] = 'setup_website';
			$r2 = $website->website();
			
			$data = $o;
			if( ! is_array( $data ) ){
				echo $o; exit;
				unset( $data );
				$data = array();
			}
			$data["website_data"] = $r2;
			
			// echo '<pre>';
			// print_r( $r2 );exit;
			
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/html.php' );
			echo $this->_get_html_view(); exit;
		}
		
		public function _get_project_settings_data( $opt = array() ){
			if( class_exists( 'cProject_settings' ) && isset( $opt["reference_table"] ) && isset( $opt["keys"] ) && is_array( $opt["keys"] ) ){
				$imp = $opt["keys"];
				
				if( ! empty( $imp ) ){
					$p = new cProject_settings();
					$p->class_settings = $this->class_settings;
					$p->class_settings[ 'overide_select' ] = " `". $p->table_name ."`.`". $p->table_fields[ 'key' ] ."` as 'key', `". $p->table_name ."`.`". $p->table_fields[ 'value' ] ."` as 'value' ";
					$p->class_settings[ 'where' ] = " AND `". $p->table_name ."`.`". $p->table_fields[ 'reference_table' ] ."` = '". $opt["reference_table"] ."' AND `". $p->table_name ."`.`". $p->table_fields[ 'key' ] ."` IN ( '". implode("', '", $imp ) ."' ) ";
					return $p->_get_records( array( "index_field" => 'key' ) );
				}
			}
		}
		
		
		public function _get_flags( $opt = array() ){
			$r = array();
			
			if( isset( $opt["record"]["reference"] ) && $opt["record"]["reference"] && isset( $opt["record"]["reference_table"] ) ){
				$sc = 1;
				$sf = 1;
				if( isset( $opt["source"] ) ){
					switch( $opt["source"] ){
					case 'utility_buttons':
						$sc = isset( $opt["show_comments"] )? $opt["show_comments"] :0;
						$sf = isset( $opt["show_files"] )? $opt["show_files"] :0;
					break;
					}
				}
				
				if( $sf && class_exists("cFiles") ){
					$fd = new cFiles();
					$fd->class_settings = $this->class_settings;
					$fd->class_settings["overide_select"] = " MAX(`creation_date`) as 'date' ";
					
					$wh = " `".$fd->table_fields["reference"]."` = '". $opt["record"]["reference"] ."' AND `".$fd->table_fields["reference_table"]."` = '". $opt["record"]["reference_table"] ."' ";
					
					if( isset( $opt["community"] ) && $opt["community"] && isset( $fd->table_fields["community"] ) ){
						$wh = " (".$wh.") OR `".$fd->table_fields["community"]."` = '". $opt["community"] ."' ";
					}
					$fd->class_settings["where"] = " AND ". $wh;
					
					$rflg = $fd->_get_records();
					
					if( isset( $rflg[0]['date'] ) && $rflg[0]['date'] ){
						//$cflg = ' <sup class="text-danger">' . get_age( $rflg[0]['date'], 0, 1, 0 ) . '</sup>';
						$r["files"] = ' <sup class="text-danger">~' . time_passed_since_action_occurred( date("U") - doubleval( $rflg[0]['date'] ), 6 ) . '</sup>';
						
						$r["g_time"] = doubleval( $rflg[0]['date'] );
						$r["g_text"] = $r["files"];
					}
				}
				
				if( $sc && class_exists("cComments") ){
					$fd = new cComments();
					$fd->class_settings = $this->class_settings;
					$fd->class_settings["overide_select"] = " MAX(`creation_date`) as 'date' ";
					$fd->class_settings["where"] = " AND `".$fd->table_fields["reference"]."` = '". $opt["record"]["reference"] ."' AND `".$fd->table_fields["reference_table"]."` = '". $opt["record"]["reference_table"] ."' ";
					$rflg = $fd->_get_records();
					
					if( isset( $rflg[0]['date'] ) && $rflg[0]['date'] ){
						//$cflg = ' <sup class="text-danger">' . get_age( $rflg[0]['date'], 0, 1, 0 ) . '</sup>';
						$r["comments"] = ' <sup class="text-danger">~' . time_passed_since_action_occurred( date("U") - doubleval( $rflg[0]['date'] ), 6 ) . '</sup>';
						
						$ax = 1;
						if( isset( $r["g_time"] ) && $r["g_time"] > doubleval($rflg[0]['date']) ){
							$ax = 0;
						}
						if( $ax ){
							$r["g_time"] = $rflg[0]['date'];
							$r["g_text"] = $r["comments"];
						}
					}
				}
			}
			
			return $r;
		}
	
	}
?>
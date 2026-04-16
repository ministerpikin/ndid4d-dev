<?php
/**
 * api Class
 *
 * @used in  				api Function
 * @created  				09:40 | 12-Mar-2020
 * @database table name   	api
 */
	
	class cApi extends cCustomer_call_log{
		
		public $class_settings = array();
		public $api_data = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'api';
		
		public $default_reference = '';
		
		public $label = 'API Client';
		
		private $associated_cache_keys = array(
			'api',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $prevent_duplicate = array(
			'fields' => array( 'application_name' ),
			'error_title' => 'Duplicate Application Name',
			'error_message' => 'There is an existing API Client:',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, eidt, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
		);
		
		public $table_fields = array();
		
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/api.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/api.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function api(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError('000017');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form2();
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
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
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
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_records();
			break;
			case "get_record":
				$returned_value = $this->_get_record();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'execute_api':
				$returned_value = $this->_execute_api();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		private $data_options = array( 'hash_key' );
		
		protected function _before_generate_form(){
			$this->class_settings["hidden_records"][ $this->table_fields["data"] ] = 1;
			
			$this->class_settings["attributes"]["custom_form_fields"] = array(
				"field_ids" => $this->data_options,
				"form_label" => array(
					'hash_key' => array(
						'field_label' => 'Hash Key',
						'form_field' => 'text',
						'required_field' => 'yes',
						'note' => 'Secret Key Phrase that would be used to Hash Data.<br /><b>NOTE: Do not give this key to anyone</b><br /><b>Do not send this key as part of your API request</b>',
						
						'display_position' => 'display-in-table-row',
						'serial_number' => '',
					),
				),
			);
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$this->class_settings["current_record_id"] = $_POST["id"];
				$e = $this->_get_record();
				
				if( isset( $e["data"] ) && $e["data"] ){
					$ed = json_decode( $e["data"], true );
					if( is_array( $ed ) && ! empty( $ed ) ){
						foreach( $this->data_options as $opt ){
							if( isset( $ed[ $opt ] ) ){
								$this->class_settings[ "form_values_important" ][ $opt ] = $ed[ $opt ];
							}
						}
					}
				}
			}
			
			if( ! isset( $this->class_settings[ "form_values_important" ][ "hash_key" ] ) ){
				$this->class_settings[ "form_values_important" ][ "hash_key" ] = generateCodes( 8, 1, 1, 1, 1 );
			}
		}
		
		protected function _generate_new_data_capture_form2(){
			$this->_before_generate_form();
			return $this->_generate_new_data_capture_form();
		}
		
		protected function __before_save_changes(){
			if( isset( $_POST[ $this->table_fields["application_name"] ] ) && $_POST[ $this->table_fields["application_name"] ] ){
				$_POST[ $this->table_fields["application_key"] ] = md5( trim(strtolower( $_POST[ $this->table_fields["application_name"] ] ) ) );
			}
			
			if( isset( $_POST[ $this->table_fields["host_name"] ] ) && $_POST[ $this->table_fields["host_name"] ] ){
				$_POST[ $this->table_fields["host_name"] ] = trim( $_POST[ $this->table_fields["host_name"] ] );
			}
			
			$d = array();
			foreach( $this->data_options as $opt ){
				if( isset( $_POST[ $opt ] ) && $_POST[ $opt ] ){
					if( is_array( $_POST[ $opt ] ) ){
						$v = implode(":::", $_POST[ $opt ] );
					}else{
						$v = $_POST[ $opt ];
					}
					$d[ $opt ] = $v;
				}
			}
			$_POST[ $this->table_fields["data"] ] = json_encode( $d );
		}
		
		protected function _execute_api(){

			$err_msg = '';
			$return = array(
				'status' => 0,
				'message' => '',
				'data' => '',
			);
			
			$host = isset( $_SERVER["HTTP_HOST"] )?$_SERVER["HTTP_HOST"]:'';
			$api_hash_value = '';
			$transaction_otp = '';
			//echo '<pre>'; print_r( $_SERVER["HTTP_HOST"] ); echo '</pre>'; exit;
			
			$nw_widget = isset( $_GET[ 'nw_widget' ] ) ? $_GET[ 'nw_widget' ] : 0;
			$method = isset( $_GET[ 'api_method' ] ) ? $_GET[ 'api_method' ] : '';
			$security_check = 1;
			switch( $method ){
			case 'new_ticket_widget':
				$security_check = 0;
			break;
			}
			
			$api_data = array();
			if( ( isset( $_GET[ 'api_hash_key' ] ) && $_GET[ 'api_hash_key' ] ) ){
				$err_msg = "SECURITY RISK. Do not send your API HASH KEY as part of the request";
			}
			
			if( $security_check && ! $err_msg ){
				if( ( isset( $_GET[ 'api_hash_value' ] ) && $_GET[ 'api_hash_value' ] ) ){
					$api_hash_value = $_GET[ 'api_hash_value' ];
				}else{
					$err_msg = "Undefined API HASH VALUE. The request was not accepted";
				}
			}
			
			if( $security_check && ! $err_msg ){
				if( ( isset( $_GET[ 'transaction_otp' ] ) && $_GET[ 'transaction_otp' ] ) ){
					$transaction_otp = $_GET[ 'transaction_otp' ];
				}else{
					$err_msg = "Undefined TRANSACTION_OTP. The request was not accepted";
				}
			}
			
			if( ! $err_msg ){
				if( ( isset( $_GET[ 'api_key' ] ) && $_GET[ 'api_key' ] ) ){
					$this->class_settings["where"] = " AND `".$this->table_fields["application_key"]."` = '". $_GET[ 'api_key' ] ."' ";
					$api = $this->_get_records();
					if( isset( $api[0]["id"] ) && $api[0]["id"] ){
						if( count( $api ) == 1 ){
							$api[0]["data"] = json_decode( $api[0]["data"], true );
							$api[0]["request_log"] = json_decode( $api[0]["request_log"], true );
							$api_data = $api[0];
							
							unset( $api );
							
							if( strtolower( $host ) != strtolower( $api_data["host_name"] ) ){
								$err_msg = "Invalid Application Host Name. Please contact your support team to update your new hostname";
							}
						}else{
							$err_msg = "Multiple API Keys. Please contact your support team to resolve this issue";
						}
					}else{
						$err_msg = "Invalid API Key. Please contact your support team to resend you the API key";
					}
				}else{
					$err_msg = "The 'api_key' is not defined. Please refer to the API Documentation.";
				}
			}

			$get = $_GET;
			$_GET = array();
			
			$ddx = $_POST;
			$format = isset( $_POST[ 'format' ] ) ? $_POST[ 'format' ] : 'json';
			$limit = isset( $_POST[ 'limit' ] ) ? $_POST[ 'limit' ] : 100;
			$offset = isset( $_POST[ 'offset' ] ) ? $_POST[ 'offset' ] : 0;

			//$err_msg = '';
			if( ! $err_msg ){
				$ad = new cApi_definition();
				$ad->class_settings = $this->class_settings;
				$ad->class_settings[ 'api_method' ] = $method;
				$ad->class_settings[ 'action_to_perform' ] = 'get_api_method_details';

				$dy = $ad->api_definition();
				
				$api_data["widget_title"] = isset( $dy["title"] )?$dy["title"]:'';
				$api_data["widget_description"] = isset( $dy["description"] )?$dy["description"]:'';
				
				//echo '<pre>'; print_r( $dy ); echo '</pre>'; exit;
				
				if( isset( $dy[ 'err' ] ) && isset( $dy[ 'msg' ] ) ){
					$err_msg = $dy[ 'msg' ];
				}elseif( isset( $dy[ 'input' ] ) ){

					$d = urldecode( $dy[ 'input' ] );
					if( ! is_array( $d ) ){
						$d = json_decode( $d, true );
					}
					
					if( isset( $d[ 'input' ] ) && ! empty( $d[ 'input' ] ) ){
						
						$hash_values = array(); 
						if( isset( $api_data["data"]["hash_key"] ) ){
							$hash_values[] = $api_data["data"]["hash_key"];
						}
						
						foreach( $d[ 'input' ] as $k => $v ){
							if( isset( $v[ 'required' ] ) && $v[ 'required' ] ){
								$field = '';
								switch( $v[ 'request_type' ] ){
								case 'GET':
									$field = isset( $get[ $k ] ) ? $get[ $k ] : '';
								break;
								case 'POST':
									$field = isset( $ddx[ $k ] ) ? $ddx[ $k ] : '';
								break;
								}
								if( $field ){
									//$hash_values[] = $field;
									
									switch( $k ){
									case 'fields':
										$field = json_decode( $field, true );
										if( ! ( isset( $field[ 'tables' ] ) && $field[ 'tables' ] ) ){
											$err_msg .= "The structure of the '". $k ."' parameter is incorrect.";
										}
									break;
									default:
										if( gettype( $field ) !== $v[ 'type' ] ){
											$err_msg .= "The field '". $k ."' is not of the correct type.";
										}
									break;
									}
								}else{
									$err_msg .= "The field '". $k ."' is not defined. It is a required request parameter.";
								}
							}
						}
						
						if( isset( $dy["default_settings"]["input"]["api_hash_value"]["default_hash_values"] ) ){
							$hs = $dy["default_settings"]["input"]["api_hash_value"]["default_hash_values"];
							
							if( ! empty( $hs ) ){
								foreach( $hs as $hsv ){
									$hash_values[] = isset( $get[ $hsv ] ) ? $get[ $hsv ] : '';
								}
							}
						}
						
						//echo '<pre>'; print_r( $hash_values ); echo '</pre>'; exit;
						//echo '<pre>'; print_r( md5( implode( "", $hash_values ) ) ); echo '</pre>'; exit;
						
						if( $security_check ){
							if( isset( $api_data["request_log"][ $api_hash_value ] ) ){
								$err_msg = "SECURITY VIOLATION. Duplicate TRANSACTION_OTP & API_HASH_VALUE";
							}else{
								if( md5( implode( "", $hash_values ) ) == $api_hash_value ){
									
								}else{
									$err_msg = "Unable to Authenticate API Request. Invalid API_HASH_VALUE";
								}
							}
						}
					}
				}
			}

			//$err_msg = '';
			$save_log = 1;
			
			if( ! $err_msg ){
				switch( $method ){
				case 'get_lgas':
				case 'get_wards':
				case 'get_communities':
				case 'get_states':
				case 'get_countries':
				
				case 'get_community':
				case 'get_community_sr':
				case 'get_households':
				case 'get_households_sr':
				case 'get_ssn_program_purposes':
				case 'get_mines':
				
				case 'get_beneficiaries':
				case 'get_ssn_programs':
					$dx[ 'export_options' ] = array();
					
					switch( $method ){
					case 'get_beneficiaries':
						if( isset( $_GET[ 'mine_id' ] ) && $_GET[ 'mine_id' ] ){
							$bn = new cBeneficiaries();
							
							$mn = new cMines();
							$mn->class_settings = $this->class_settings;
							$mn->class_settings[ 'action_to_perform' ] = 'view_details_api';
							$_POST[ 'id' ] = $_GET[ 'mine_id' ];
							$dd = $mn->mines();
							
							unset( $_POST[ 'id' ] );
							//echo '<pre>'; print_r( $dd ); echo '</pre>'; exit;
							
							if( isset( $dd[ 'data' ]["export_options"]["table"] ) ){
								$dx["export_options"] = $dd[ 'data' ]["export_options"];
								
								if( ! isset( $dx["export_options"]["query"]["join"] ) ){
									$dx["export_options"]["query"]["join"] = '';
								}
								
								$dx["export_options"]["query"]["replace_where"] = 1;
								$dx["export_options"]["query"]["where"] = "WHERE `".$dd[ 'data' ]["export_options"]["table"]."`.`record_status` = '1' AND `". $bn->table_name ."`.`record_status` = '1' AND `". $bn->table_name ."`.`".$bn->table_fields["mine"]."` = '".$dd[ 'id' ]."' ";
								
								$dx["export_options"]["query"]["replace_join"] = 1;
								$dx["export_options"]["query"]["join"] = "JOIN `".$this->class_settings["database_name"]."`.`". $bn->table_name ."` ON `". $bn->table_name ."`.`".$bn->table_fields["reference"]."` = `".$dd[ 'data' ]["export_options"]["table"]."`.`id` AND `". $bn->table_name ."`.`".$bn->table_fields["reference_table"]."` = '".$dd[ 'data' ]["export_options"]["table"]."' " . $dx["export_options"]["query"]["join"];
								
								//$dx["export_options"]["query"]["group"] = "GROUP BY `".$dd[ 'data' ]["export_options"]["table"]."`.`id` ";
								$dx["export_options"]["query"]["group"] = "GROUP BY `". $bn->table_name ."`.`id` ";
								
							}else{
								$err_msg = "Could not retrieve mine details. Please re-check the mine_id.";
							}
						}else{
							$err_msg = "Mine ID was not specified";
						}
		
					break;
					}
					
					extract( $this->_validate( array( "post_data" => $ddx, "validate_data" => array( "fields" => array(), "where" => array( "optional" => 1 ), "order" => array() ) ) ) );
					
					if( ! $err_msg ){
						
						if( $where ){

							$_POST[ 'data' ] = $where;

							$sh = new cSearch();
							$sh->class_settings = $this->class_settings;
							$sh->class_settings[ 'exclude_filter_data' ] = 1;
							$sh->class_settings[ 'return_query' ] = 1;
							$sh->class_settings[ 'action_to_perform' ] = 'run_search_query';

							$r = $sh->search();
							unset( $_POST[ 'data' ] );
							//echo '<pre>'; print_r( $r ); echo '</pre>'; exit;

							if( isset( $r[ 'query' ] ) && $r[ 'query' ] ){
								if( ! isset( $dx[ 'export_options' ][ 'query' ][ 'where' ] ) ){
									$dx[ 'export_options' ][ 'query' ][ 'where' ] = '';
								}
								
								$dx[ 'export_options' ][ 'query' ][ 'where' ] .= " AND " . $r[ 'query' ];
							}
							
							if( isset( $r["table"] ) && $r["table"] ){
								$dx[ 'export_options' ][ 'table' ] = $r["table"];
							}
						}

						if( $order ){
							$dx[ 'export_options' ][ 'query' ][ 'order' ] = $order;
						}

						if( ! empty( $fields ) ){
							$dx[ 'export_options' ][ 'tables' ] = $fields;
						}

						$_POST = array();
						//echo '<pre>'; print_r( $dx[ 'export_options' ] ); echo '</pre>'; exit;

						$me = new cMyexcel();
						$me->class_settings = $this->class_settings;

						switch ( $format ) {
						case 'csv':
							$me->class_settings[ 'action_to_perform' ] = 'save_generate_csv';
						break;
						case 'json':
							$me->class_settings[ 'action_to_perform' ] = 'save_generate_json';
						break;
						}
						
						$dx[ 'export_options' ][ 'page_offset' ] = $offset;
						$dx[ 'export_options' ][ 'page_limit' ] = $limit;
						$dx[ 'export_options' ][ 'format' ] = $format;
						$me->class_settings["export_data"] = $dx[ 'export_options' ];
						$dd = $me->myexcel();

						if( isset( $dd[ 'err' ] ) ){
							$err_msg = ( isset( $dd[ 'msg' ] ) && $dd[ 'msg' ] )?$dd[ 'msg' ]:'';
						}else if( ! empty( $dd ) ){
							//$dd[ 'msg' ] = rawurlencode( $dd[ 'msg' ] );
							$return[ 'status' ] = 1;
							$return[ 'data' ] = $dd;
							$return[ 'message' ] = "Successfully Read";
						}
					}
				break;
				case 'create_program':
					$pr = new cPrograms();
					$pr->class_settings = $this->class_settings;
					$pr->class_settings[ 'action_to_perform' ] = 'save_programs';
					$pr->class_settings[ 'user_id' ] = $_GET[ 'api_key' ];

					$d = $pr->programs();

					if( isset( $d[ 'saved_record_id' ] ) && $d[ 'saved_record_id' ] ){
						$return[ 'data' ] = json_encode( $d );
						$return[ 'message' ] = "SSN Program Created Successfully";
						$return[ 'status' ] = 1;
					}else if( isset( $d[ 'err' ] ) && isset( $d[ 'msg' ] ) && $d[ 'msg' ] ){
						$err_msg = $d[ 'msg' ];
					}else{
						$err_msg = "Unable to create SSN Program";
					}
				break;
				case 'beneficiaries_update':

					if( isset( $ddx[ $_GET[ 'update_type' ] ] ) && $ddx[ $_GET[ 'update_type' ] ] ){
						$where = '';

						$b = new cBeneficiaries();
						$b->class_settings = $this->class_settings;

						switch ( $_GET[ 'update_type' ] ) {
						case 'beneficiaries_id':
							$dxx = json_decode( $ddx[ $_GET[ 'update_type' ] ], true );

							if( isset( $dxx[ 'beneficiaries' ] ) && ! empty( $dxx[ 'beneficiaries' ] ) ){
								$where .= " `". $b->table_name ."`.`id` IN ( '". implode( "','", $dxx[ 'beneficiaries' ] ) ."' ) ";
							}else{
								$err_msg = " 'beneficiaries' not set in '". $_GET[ 'update_type' ] ."'.";
							}
						break;
						case 'mine_id':
							$where .= " `". $b->table_name ."`.`". $b->table_fields[ 'mine' ] ."` = '". $ddx[ $_GET[ 'update_type' ] ] ."' ";
						break;
						case 'purpose_id':
							$where .= " `". $b->table_name ."`.`". $b->table_fields[ 'purpose' ] ."` = '". $ddx[ $_GET[ 'update_type' ] ] ."' ";
						break;
						case 'program_id':
							$where .= " `". $b->table_name ."`.`". $b->table_fields[ 'program' ] ."` = '". $ddx[ $_GET[ 'update_type' ] ] ."' ";
						break;
						default:
							$err_msg = "This 'update_type' is invalid.";
						break;
						}

						$b->class_settings[ 'overide_select' ] = " `id` ";
						$b->class_settings[ 'where' ] = " AND " . $where;

						$dd = $b->_get_records();

						if( is_array( $dd ) && isset( $dd[0][ 'id' ] ) ){
							$bu = new cBeneficiaries_update();
							$bu->class_settings = $this->class_settings;
							$bu->class_settings[ 'user_id' ] = $_GET[ 'api_key' ];

							$benefit_date = isset( $_POST[ 'benefit_date' ] ) ? $_POST[ 'benefit_date' ] : '';
							$type = isset( $_POST[ 'type' ] ) ? $_POST[ 'type' ] : '';
							$count = isset( $_POST[ 'count' ] ) ? $_POST[ 'count' ] : '';
							$comment = isset( $_POST[ 'comment' ] ) ? $_POST[ 'comment' ] : '';
							$comment = isset( $_POST[ 'comment' ] ) ? $_POST[ 'comment' ] : '';
							$reference_table = $b->table_name;

							$line_items = array();
							foreach( $dd as $v ){
								$line_items[] = array(
									'date' => date("U"),
									'benefit_date' => strtotime( $benefit_date ),
									'type' => $type,
									'count' => $count,
									'comment' => $comment,
									'reference' => $v[ 'id' ],
									'reference_table' => $reference_table,
								);
							}

						// print_r( $line_items );exit;
							$bu->class_settings[ 'line_items' ] = $line_items;
							$bu->class_settings[ 'action_to_perform' ] = 'save_line_items';
							
							if( $bu->beneficiaries_update() ){
								$return[ 'message' ] = "Beneficiaries Updated Successfully";
								$return[ 'status' ] = 1;
							}else{
								$err_msg .= "Unable to save beneficiaries update.";
							}
						}else{
							$err_msg .= "No beneficiaries found. Plese re-check the 'mine_id' or the 'beneficiaries_id'.";
						}

					}else{
						$err_msg = "'". $_GET[ 'update_type' ] ."' is undefined.";
					}
				break;
				case 'create_program_purpose':
					$m = new cSsn_program_purpose();
					$m->class_settings = $this->class_settings;
					$m->class_settings[ 'action_to_perform' ] = 'save_create_purpose_api';
					$m->class_settings[ 'user_id' ] = $_GET[ 'api_key' ];

					foreach( $m->table_fields as $k => $v ){
						if( isset( $_GET[ $k ] ) )$_POST[ $v ] = $_GET[ $k ];
					}
					if( ! isset( $_POST[ 'date' ] ) )$_POST[ 'date' ] = date("U");

					$d = $m->ssn_program_purpose();

					if( isset( $d[ 'saved_record_id' ] ) && $d[ 'saved_record_id' ] ){
						$return[ 'data' ] = json_encode( $d );
						$return[ 'message' ] = "SSN Program Purpose Created Successfully";
						$return[ 'status' ] = 1;
					}else if( isset( $d[ 'msg' ] ) && $d[ 'msg' ] ){
						$err_msg = $d[ 'msg' ];
					}else{
						$err_msg = "Unable to save SSN Program";
					}
				break;
				
				case 'households_info':
				case 'households_sr_info':
				case 'community_sr_info':
				case 'community_info':
				
				case 'mine_info':
				case 'ssn_program_purpose_info':
				case 'ssn_program_info':
				case 'beneficiaries_info':
				
				case 'track_ticket_widget':
					$table_map = array(
						"mine_info" => array( "table" => "mines", "id" => "mine_id" ),
						"ssn_program_info" => array( "table" => "programs", "id" => "program_id" ),
						"ssn_program_purpose_info" => array( "table" => "ssn_program_purpose", "id" => "program_purpose_id" ),
						"beneficiaries_info" => array( "table" => "beneficiaries", "id" => "beneficiary_id" ),
						
						"community_info" => array( "table" => "community", "id" => "community_id" ),
						"households_info" => array( "table" => "households", "id" => "household_id" ),
						
						"community_sr_info" => array( "table" => "community_sr", "id" => "community_id" ),
						"households_sr_info" => array( "table" => "households_sr", "id" => "household_id" ),
						
						"track_ticket_widget" => array( "table" => "tickets", "id" => "ticket_id" ),
					);
					
					if( isset( $table_map[ $method ] ) && $table_map[ $method ] ){
						if( isset( $get[ $table_map[ $method ]["id"] ] ) && $get[ $table_map[ $method ]["id"] ] ){
							$cx = $table_map[ $method ]["table"];
							$cl = "c" . ucwords( $cx );
							if( class_exists( $cl ) ){
								$mn = new $cl();
								$mn->class_settings = $this->class_settings;
								$mn->class_settings[ 'current_record_id' ] = $get[ $table_map[ $method ]["id"] ];
								$mn->class_settings[ 'action_to_perform' ] = 'view_details_api';
								$dd = $mn->$cx();
								
								if( isset( $dd["error"] ) ){
									$err_msg = $dd["error"];
								}else{
									$return[ 'status' ] = 1;
									$return[ 'data' ] = $dd;
									$return[ 'message' ] = $mn->label . " Details Returned Successfully.";
								}
							}else{
								$err_msg = "Mapped class does not exist";
							}
						}else{
							$err_msg = "UNIQUE IDENTIFIER was not specified";
						}
					}else{
						$err_msg = "API method is not Mapped";
					}
					
				break;
				case 'new_ticket_widget':
				case 'save_new_ticket_widget':
				case 'create_ticket':
					$t = new cTickets();
					$t->class_settings = $this->class_settings;
					
					//display_capture_window
					switch( $method ){
					case 'new_ticket_widget':
						$t->class_settings[ 'get_params' ][ 'transaction_otp' ] = md5( $api_data["id"] . date("U") . $method . rand() );
						$t->class_settings[ 'get_params' ][ 'api_hash_value' ] = md5( $api_data["data"]["hash_key"] . $t->class_settings[ 'get_params' ][ 'transaction_otp' ] );
						
						$t->class_settings[ 'get_params' ][ 'nw_widget' ] = 1;
						$t->class_settings[ 'get_params' ][ 'api_method' ] = 'create_ticket';
						$t->class_settings[ 'get_params' ][ 'api_key' ] = $api_data["application_key"];
						
						$t->class_settings[ 'more_params' ] = $api_data;
						
						$t->class_settings[ 'action_to_perform' ] = 'get_api_widget';
						return $t->tickets();
					break;
					case 'create_ticket':
					case 'save_new_ticket_widget':
						$t->class_settings[ 'user_type' ] = 'api';
						$t->class_settings[ 'user_id' ] = $api_data["id"];
						$t->class_settings[ 'action_to_perform' ] = 'save_new_ticket_form';
						$_POST[ 'type' ] = 'grievance';

						$dd = $t->tickets();

						if( $nw_widget ){
							$return = $dd;
							if( isset( $return["typ"] ) && $return["typ"] != 'saved' ){
								$save_log = 0;
							}
							
							unset( $dd );
						}else{
							$return[ 'status' ] = 1;
							$return[ 'data' ] = $dd;
							$return[ 'message' ] = "Ticket has been Created Successfully.";
						}
					break;
					case 'track_ticket_widget':
						$t->class_settings[ 'action_to_perform' ] = 'view_details_api';
						$_POST[ 'id' ] = $_GET[ 'ticket_id' ];

						$dd = $t->tickets();

						if( isset( $dd[ 'id' ] ) ){
							$return[ 'status' ] = 1;
							$return[ 'data' ] = $dd;
							$return[ 'message' ] = "Ticket Status Retrived Successfully.";
						}
					break;
					}
					
				break;
				default:
					$return[ 'message' ] = 'This API method is not defined on the NASSCO MIS';
				break;
				}
			}
			
			if( $err_msg ){
				$return[ 'message' ] = $err_msg . " Please refer to the API documentation for more details.";
				if( $nw_widget ){
					return $this->_display_notification( array( "type" => "error", "message" => $return[ 'message' ] ) );
				}
			}
			
			if( $save_log && isset( $api_data["id"] ) ){
				if( ! isset( $api_data["request_log"] ) ){
					$api_data["request_log"] = array();
				}
				
				if( count( $api_data["request_log"] ) > 100 ){
					foreach( $api_data["request_log"] as $ka => $kv ){
						unset( $api_data["request_log"][ $ka ] );
						break;
					}
				}
				
				$api_data["request_log"][ $api_hash_value ]["m"] = $method;
				$api_data["request_log"][ $api_hash_value ]["d"] = date("U");
				
				$s = array();
				$s["where"] = " `id` = '". $api_data["id"] ."' ";
				$s["field_and_values"][ $this->table_fields["request_log"] ]['value'] = json_encode( $api_data["request_log"] );
				
				$this->refresh_cache_after_save_line_items = 0;
				$this->_update_records( $s );
			}
			
			if( isset( $api_data["application_name"] ) ){
				$return[ 'application_name' ] = $api_data["application_name"];
			}
			
			//echo "<pre>";print_r( $return );echo "</pre>";exit;
			
			return $return;
		}
		
		protected function _validate( $opt = array() ){
			$r = array();
			$err_msg = '';
			$tbs = array();
			$where = '';
			$order = '';
			
			$ddx = isset( $opt["post_data"] )?$opt["post_data"]:array();
			
			$exclude_fields = array(
				'id' => 'id',
				'created_role' => 'created_role',
				'created_source' => 'created_source',
				'created_by' => 'created_by',
				'creation_date' => 'creation_date',
				'modified_source' => 'modified_source',
				'modified_by' => 'modified_by',
				'modification_date' => 'modification_date',
				'ip_address' => 'ip_address',
				'record_status' => 'record_status',
				'serial_num' => 'serial_num',
			);
			
			if( isset( $opt["validate_data"] ) && is_array( $opt["validate_data"] ) && ! empty( $opt["validate_data"] ) ){
				foreach( $opt["validate_data"] as $vkey => $vval ){
					
					switch( $vkey ){
					case "fields":
						// Validate field options
						if( isset( $ddx[ 'fields' ] ) ){
							$dxx = json_decode( $ddx[ 'fields' ], true );
							
							if( isset( $dxx[ 'tables' ] ) && ! empty( $dxx[ 'tables' ] ) ){
								foreach( $dxx[ 'tables' ] as $k => $v ){
									$c = 'c' . ucwords( $k );

									if( class_exists( $c ) ){
										$cl = new $c();

										if( ! isset( $tbs[ $k ] ) )$tbs[ $k ] = array();
										$xx = array();

										if( isset( $v[ 'fields' ] ) && ! empty( $v[ 'fields' ] ) ){
											foreach( $v[ 'fields' ] as $k1 => $v1 ){
												if( ( isset( $cl->table_fields[ $k1 ] ) && $cl->table_fields[ $k1 ] ) || isset( $exclude_fields[ $k1 ] ) ){
													
													$tbs[ $k ][ $k1 ] = array( 'db_field' => isset( $cl->table_fields[ $k1 ] )?$cl->table_fields[ $k1 ]:$exclude_fields[ $k1 ] );
													
													if( isset( $v1[ 'custom' ] ) && $v1[ 'custom' ] && isset( $v1[ 'alias' ] ) && $v1[ 'alias' ] ){
														$tbs[ $k ][ $k1 ][ 'alias' ] = $v1[ 'alias' ];
													}
													
												}else{
													$err_msg .= "The field '". $k1 ."' does not exist in the dataset of '". $k ."'";
												}
											}
										}else{
											$err_msg .= "The fields for the table '". $k ."' is not set";
										}

										if( ! $err_msg && ! empty( $xx ) ){
											foreach( $xx as $k1 => $v1 ){
												if( isset( $cl->table_fields[ $k1 ] ) && $cl->table_fields[ $k1 ] ){
													if( $custom )$tbs[ $k ][ $k1 ][ 'api' ] = $v1;
													else $tbs[ $k ][ $k1 ] = 1;
												}else{
													$err_msg .= "The field '". $k1 ."' does not exist in the dataset of '". $k ."'.";
												}
											}
										}
									}
								}
							}else{
								$err_msg = "Tables not defined in the fields parameter.";
							}
						}
					break;
					case "where":
						// Validate where condition
						if( isset( $ddx[ 'where' ] ) && $ddx[ 'where' ] ){
							$dxx = json_decode( $ddx[ 'where' ], true );
							if( isset( $dxx[ 'dataSource' ] ) && $dxx[ 'dataSource' ] ){
								if( isset( $dxx[ 'cart_items' ] ) && $dxx[ 'cart_items' ] ){
									foreach( $dxx[ 'cart_items' ] as $k => & $where_v ){
										
										if( isset( $where_v[ 'data' ] ) && ! empty( $where_v[ 'data' ] ) ){
											foreach( $where_v[ 'data' ] as $k1 => & $w_v1 ){

												if( isset( $w_v1[ 'table_name' ] ) && $w_v1[ 'table_name' ] ){
													
													if( ! isset( $tbs[ $w_v1[ 'table_name' ] ] ) )$tbs[ $w_v1[ 'table_name' ] ] = array();

													$c = 'c' . ucwords( $w_v1[ 'table_name' ] );

													if( class_exists( $c ) ){
														$cl = new $c();
														$cl2 = $w_v1[ 'table_name' ]();

														// Check if all parameters are defined
														$err = "The field '{option}' in '". $k1 ."' is not defined.";

														if( isset( $d[ 'input' ][ 'fields' ][ 'cart_items' ][ '1' ][ 'data' ][0] ) && $d[ 'input' ][ 'fields' ][ 'cart_items' ][ '1' ][ 'data' ][0] ){
															foreach( $d[ 'input' ][ 'fields' ][ 'cart_items' ][ '1' ][ 'data' ][0] as $k2 => $v2 ){
																if(! ( isset( $w_v1[ $k2 ] ) && $w_v1[ $k2 ] ) ){
																	$err_msg .= str_replace( "{option}", $k2, $err );
																}
															}
														}

														// Check if field parameter exists in the specified table_name
														if( ! $err_msg && ( isset( $cl->table_fields[ $w_v1[ 'field' ] ] ) || isset( $cl2[ $w_v1[ 'field' ] ] ) || isset( $exclude_fields[ $w_v1[ 'field' ] ] ) ) ){

															// Fields can either be 'country' or 'households199'. Change in necesssary
															if( isset( $cl->table_fields[ $w_v1[ 'field' ] ] ) && $cl->table_fields[ $w_v1[ 'field' ] ] ){
																$w_v1[ 'field' ] = $cl->table_fields[ $w_v1[ 'field' ] ];
															}
														}else{
															$err_msg .= "The field '". $k1 ."' does not exist in the dataset of '". $k ."'.";
														}
													}else{
														$err_msg .= "The table name '". $v1[ 'table_name' ] ."' does not exist in the MIS.";
													}
												}else{
													$err_msg .= "The table name for entry '". $k1 ."' in dataset '". $k ."' is not defined.";
												}
											}
										}else{
											$err_msg .= "The 'data' parameter is not defined for dataset '". $k ."'.";
										}
									}
								}else{
									$err_msg = "'cart_items' not defined in the fields parameter.";
								}
							}else{
								$err_msg = "DataSource is not defined.";
							}
							
						}else{
							$err_msg = "Where parameters was not defined.";
						}
						
						if( ! $err_msg ){
							$where = json_encode( $dxx );
						}
						
						if( isset( $vval["optional"] ) && $vval["optional"] ){
							if( $err_msg ){
								$err_msg = '';
								$where = json_encode( array() );
							}
						}
					break;
					case "order":
						// Validate field options
						if( isset( $ddx[ 'order' ] ) && $ddx[ 'order' ] ){
							$dxx = json_decode( $ddx[ 'order' ], true );
							if( ! empty( $dxx[ 'tables' ] ) ){
								foreach( $dxx[ 'tables' ] as $k => $v ){
									$c = 'c' . ucwords( $k );

									if( class_exists( $c ) ){
										$cl = new $c();

										if( ! empty( $v ) ){
											foreach( $v as $k1 => $v1 ){

												if( ( isset( $v1[ 'order' ] ) && $v1[ 'order' ] ) && ( isset( $v1[ 'field' ] ) && $v1[ 'field' ] ) ){
													if( isset( $cl->table_fields[ $v1[ 'field' ] ] ) && $cl->table_fields[ $v1[ 'field' ] ] ){
														$order .= " ORDER BY `". $k ."`.`". $cl->table_fields[ $v1[ 'field' ] ] ."` " . $v1[ 'order' ];
													}else{
														$err_msg .= "The field '". $k1 ."' does not exist in the dataset of '". $k ."'.";
													}

												}else{
													$err_msg .= "The 'order' or 'field' parameter for the table '". $k ."' is not properly defined.";
												}
											}
										}
									}
								}
							}
						}

					break;
					}
				}
			}
			
			//$r["ddx"] = $ddx;
			$r["order"] = $order;
			$r["where"] = $where;
			$r["fields"] = $tbs;
			$r["err_msg"] = $err_msg;
			return $r;
		}
		
		protected function _get_select2(){
			return $this->_get_default_select2();
		}
		
		protected function _search_form2(){
			$where = "";
			
			$key = 'customer';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
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
			$this->class_settings[ "return_new_format" ] = 1;
			/* 
			$this->class_settings[ "show_form" ] = 1;
			$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
			$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
			$this->class_settings[ "add_empty_select_option" ] = 1;

			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "date":
					$this->class_settings["form_values_important"][ $val ] = date("U");
					$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
				break;
				case "customer":
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
				break;
				default:
					$this->class_settings["hidden_records"][$val] = 1;
				break;
				}
			} */
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
			$this->class_settings["skip_link"] = 1;
			$this->_before_generate_form();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["amount_paid"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["payment_method"] ] = 1;
			break;
			}
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
	}
	
	function api(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/api.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/api.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				if( isset( $return[ "fields" ] ) ){
					$key = $return[ "fields" ][ "role" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'access_roles',
						'reference_keys' => array( 'role_name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=access_roles&todo=get_select2&role_type=api" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
				}
				
				return $return[ "labels" ];
			}
		}
	}
?>
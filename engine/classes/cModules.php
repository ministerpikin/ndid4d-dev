<?php
/**
 * modules Class
 *
 * @used in  				modules Function
 * @created  				14:16 | 04-Jul-2019
 * @database table name   	modules
 */
	
	class cModules extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'modules';
		
		public $default_reference = '';
		
		public $build_list = 1;
		public $build_list_condition = '';
		public $build_list_field = 'module_name';
		
		public $label = 'Modules';
		
		private $associated_cache_keys = array(
			'modules',
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
				'show_refresh_cache' => 1,		//Determines whether or not to show delete button
				
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/modules.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/modules.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function modules(){
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
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log2();
			break;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case "save_line_items":
				$returned_value = $this->_save_line_items();
			break;
			case "display":
				$returned_value = $this->_display();
			break;
			case "refresh_cache":
				$returned_value = $this->_refresh_cache();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
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
			}
			return $this->_display_all_records_full_view();
		}

		protected function _display_app_view2(){
			$c = array();
			//$c = get_record_details( array( "id" => get_current_customer(), "table" => "customers" ) );
			
			$this->class_settings[ 'data' ]["class"] = 'col-md-3';
			$this->class_settings[ 'data' ]["select_field"][] = array(
				"class" => 'refresh-form select2 remote-loading',
				"action" => '?module=&action=customers&todo=get_customers_select2',
				"placeholder" => 'Select Client',
				"name" => 'customer',
				"value" => isset( $c["id"] )?$c["id"]:'',
				"value_label" => isset( $c["name"] )?$c["name"]:'',
				"refresh_form" => 'form#' . $this->table_name,
			);
			
			$this->class_settings[ 'data' ]["new_reference_value"] = isset( $c["id"] )?$c["id"]:'';
			
			$return = $this->_display_app_view();
			return $return;
		}
		
		protected function _save_app_changes2(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//check for project details & update
				
			break;
			}
			
			$return = $this->_save_app_changes();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//display payment info
				/*
				if( isset( $return["data"]["id"] ) && $return["data"]["id"] ){
					$_POST["id"] = '';
					$_POST["tmp"] = $return["data"]["id"];
					
					$stores = new cStores();
					$stores->class_settings = $this->class_settings;
					$stores->class_settings["action_to_perform"] = "update_table_field";
					$stores->class_settings["update_fields"] = array(
						"name" => $return["data"]["name"],
						"address" => $return["data"]["location"],
						"comment" => $return["data"]["comment"],
					);
					$return1 = $stores->stores();
					
					if( ! ( isset( $return1["saved_record_id"] ) && $return1["saved_record_id"] ) ){
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = '_resend_verification_email';
						$err->additional_details_of_error = 'Project creation failed due to business units issues';
						return $err->error();
					}
				}
				*/
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
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
		
		protected function _search_customer_call_log2(){
			$where = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'search_list':
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
			break;
			case 'search_list2':
				$where = "";
				//$filename = 'list.php';
				//$item_key = "item";
				
				if( isset( $_POST[ "field" ] ) && $_POST[ "field" ] && isset( $this->table_fields[ $_POST[ "field" ] ] ) ){
					
					$val = $this->table_fields[ $_POST[ "field" ] ];
					$field = $_POST[ "field" ];
					
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
									$allow = 1;
								}
								
							}
						break;
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
				
			break;
			default:
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
			break;
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"], 1 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				else $where = " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"] , 2 );
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
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$this->class_settings[ 'data' ][ "items" ] = $data;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/item-list.php' );
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		
		private function _display(){
			$returning_html_data = '';
			$hr = array();
			$modules_used_in_entity_right_click_menu = array();
			
			$query1 = '';
			//Pull up user role record to get functions data
			$query = "SELECT * FROM `" . $this->class_settings['database_name'] . "`.`access_roles` WHERE `id`='" . $this->class_settings['priv_id'] . "' AND `record_status`='1'";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array('access_roles'),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				$a = $sql_result[0];
				
				//Check if functions data = access all
				if($a['access_roles002']=='universal' ){
					//If access all = retrieve all modules 
					$query1 = "SELECT `" . $this->table_name . "`.`".$this->table_fields["module_name"]."` as 'module_name', `" . $this->table_name . "`.`id` as 'id_', `functions`.* FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "`, `" . $this->class_settings['database_name'] . "`.`functions` WHERE `" . $this->table_name . "`.`record_status`='1'  AND `functions`.`record_status`='1' AND `" . $this->table_name . "`.`id`=`functions`.`functions002`";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query1,
						'query_type' => 'SELECT',
						'set_memcache' => 1,
						'tables' => array($this->table_name, 'functions'),
					);
					
					
					$sql_result1 = execute_sql_query($query_settings);
					
				}else{
					//IF not explode functions data
					$funs = explode(':::',$a['access_roles002']);
					
					if(is_array($funs)){
						foreach($funs as $fun){
							if($fun){
								//Prepare query
								if($query1)$query1 .= " OR `functions`.`id`='".$fun."'";
								else $query1 = "SELECT `" . $this->table_name . "`.`".$this->table_fields["module_name"]."` as 'module_name', `" . $this->table_name . "`.`id` as 'id_', `functions`.* FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "`, `" . $this->class_settings['database_name'] . "`.`functions` WHERE `" . $this->table_name . "`.`record_status`='1' AND `functions`.`record_status`='1' AND `" . $this->table_name . "`.`id`=`functions`.`functions002` AND ( `functions`.`id`='".$fun."' ";
								
							}
						}
						//Retrieve only accessible modules
						$query1 .= ")";
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							
							'query' => $query1,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
							'tables' => array($this->table_name, 'functions'),
						);
						
						$sql_result1 = execute_sql_query($query_settings);
					}
				}
				
				//Retrieve Members
				$nn = 0;
				if(isset($sql_result1) && is_array($sql_result1)){
					$nn = count($sql_result1);
					
					if($nn){
						
						
						foreach($sql_result1 as $a1){
							
							//Exclude save functions
							switch($a1['functions003']){
							case 'reply_ticket':
							case 'add_product_variations_button':
							case 'manage_product_variations_button':
							
							case 'restore':
							case 'fetch_all_records':
							case 'fetch_my_records':
							case 'push_all_records':
							case 'push_my_records':
							case 'new':
							case 'create_new_record':
							case 'edit':
							case 'delete':
							case 'save':
							case 'verify':
							case 'timeline':
							case 'location_differential':
							
							case 'edit_password':
							
							case 'attach_files_to_gas_sales_agreements':
							
							case 'generate_report':
							case 'save_generated_report':
							
							case 'approve':
							case 'check':
							case 'status_update':
							case 'record_assign':
							case 'add_new_cash_call':
							case 'add_new_line_item':
							case 'add_new_child_work_program':
							case 'import_excel_table':
							
							case 'save_rename':
							case 'save_entity_edit':
							case 'new_memo_report_letter':
							case 'new_scanned_file':
							case 'save_new_memo_report_letter':
							case 'save_send_a_copy':
							case 'save_move_to_label':
							case 'save_edit_entity_properties':
							
							case 'delete_forever':
							case 'restore_all':
							case 'restore_selected':
							
							case 'print_authenticated_report':
							case 'select_audit_trail':
							case 'navigation_pane':
							
							break;
							
							case 'status_update_monthly_returns_recommended':
							case 'status_update_monthly_returns_operator_returns':
							case 'status_update_monthly_returns_payment_by_fad':
							
							case 'status_update_projects_weekly_status':
							case 'status_update_projects_monthly_status':
							
							case 'status_update_monthly_cash_call_submitted':
							case 'status_update_monthly_cash_call_approved':
							case 'status_update_monthly_cash_call_payment_by_fad':
							case 'status_update_monthly_cash_call_crosschecked':
							case 'status_update_monthly_cash_call_reviewed':
							case 'status_update_monthly_cash_call_operator_proposal':
							
							case 'status_update_annual_budget':
							case 'status_update_budget_realignment':
							case 'status_update_budget_performance_return':
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key][$a1['functions004']][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
								);
							break;
							
							case 'status_update_annual_budget_opcom_approval':
							case 'status_update_annual_budget_operator_proposal':
							case 'status_update_annual_budget_subcom_review':
							case 'status_update_annual_budget_tecom_recommendation':
							
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key_2]['status_update_annual_budget'][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
									'class' => $a1['functions004'],
								);
							break;
							case 'status_update_budget_realignment_opcom_approval':
							case 'status_update_budget_realignment_operator_proposal':
							case 'status_update_budget_realignment_subcom_review':
							case 'status_update_budget_realignment_tecom_recommendation':
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key_2]['status_update_budget_realignment'][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
									'class' => $a1['functions004'],
								);
							break;
							case 'status_update_budget_performance_return_opcom_approval':
							case 'status_update_budget_performance_return_operator_proposal':
							case 'status_update_budget_performance_return_subcom_review':
							case 'status_update_budget_performance_return_tecom_recommendation':
								//Return Functions for Status Update Select Box
								$_SESSION[$status_update_key_2]['status_update_budget_performance_return'][$a1['id']] = array(
									'todo' => $a1['functions003'],
									'name' => $a1['functions001'],
									'class' => $a1['functions004'],
								);
							break;
							
							case 'mark_as_public':
							case 'move_to_label':
							case 'rename_entity':
							case 'view_entity':
							case 'edit_entity':
							case 'edit_entity_properties':
							case 'delete_entity':
							case 'send_a_copy_to':
								//Return Functions for Entity[Labels | Documents] Right-click
								$modules_used_in_entity_right_click_menu[$a1['id_']][$a1['module_name']][] = array(
									'todo' => $a1['functions003'],
									'phpclass' => $a1['functions004'],
									'name' => $a1['functions001'],
									'id' => $a1['id'],
								);
							break;
							default:
								
								
								$hr[$a1['id_']][$a1['module_name']][] = array(
									'todo' => $a1['functions003'],
									'phpclass' => $a1['functions004'],
									'name' => $a1['functions001'],
									'id' => $a1['id'],
								);
							break;
							}
						}
					}
				}
			}
			
			//Update Currently Logged In User Name
			$hr['user_details']['full_name'] = 'N/A';
			
			//Get user certificate session variable
			$current_user_session_details = array(
				'fname'=>'',
				'lname'=>'',
				'login_time'=>'',
				'role'=>'',
				'remote_user_id' => '',
				'email' => '',
			);
			
			$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
			if(isset($_SESSION[$current_user_details_session_key]))$current_user_session_details = $_SESSION[$current_user_details_session_key];
			
			if($current_user_session_details['fname'] && $current_user_session_details['lname']){
				$hr['user_details']['full_name'] = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
			}
			
			$project_title = '';
			
			$settings = array(
				'cache_key' => 'appsettings_properties',
			);
			$cached_values = get_cache_for_special_values( $settings );
			if( $cached_values ){
				$project_title = $cached_values[ 'appsettings_name' ];
			}
			
			//4. CHECK FOR APPLICATION VERSION
			$application_version = '';
			if( function_exists( "get_app_version" ) ){
				$application_version = get_app_version( $this->class_settings[ 'calling_page' ] );
			}
			
			return array(
				'modules' => $hr,
				'entity_modules' => $modules_used_in_entity_right_click_menu,
				'login_time' => date("d-M-Y H:i", doubleval( $current_user_session_details['login_time'] ) ),
				'role' => $current_user_session_details['role'],
				'fullname' => $current_user_session_details['fname'].' '.$current_user_session_details['lname'],
				'remote_user_id' => $current_user_session_details['remote_user_id'],
				'email' => $current_user_session_details['email'],
				'project_title' => $project_title,
				'project_version' => $application_version,
				'status' => 'generate_modules',
				/*
				'cash_calls' => $this->_generate_cash_calls_breakdown(),
				'cash_calls_summary' => $this->_generate_cash_calls_summary_breakdown(),
				'budget_details' => $this->_generate_budgets_breakdown(),
				
				'pipeline_vandalism' => $this->_generate_pipeline_vandalism_breakdown(),
				*/
			);
		}
		
		private function _get_modules(){
			
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key,
				'permanent' => true,
			);
			
			$returning_array = array(
				'html' => '',
				'method_executed' => $this->class_settings['action_to_perform'],
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
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1'";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_modules = execute_sql_query($query_settings);
			
			$modules = array();
			
			if( is_array( $all_modules ) && ! empty( $all_modules ) ){
				
				foreach( $all_modules as $category ){
					$modules[ $category['id'] ] = $category;
				}
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key,
					'cache_values' => $modules,
					'permanent' => true,
				);
				
				if( ! set_cache_for_special_values( $settings ) ){
					//report cache failure message
				}
				
				return $modules;
			}
		}
		
	}
	
	function modules(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/modules.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/modules.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
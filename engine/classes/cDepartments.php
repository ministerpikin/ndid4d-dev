<?php
	/**
	 * empowerment Class
	 *
	 * @used in  				empowerment Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	empowerment
	 */
	
	class cDepartments extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'departments';
		public $label = 'Departments';
		
		public $build_list = 1;
		public $build_list_field = 'name';
		public $build_list_field1 = '';
		
		public $branch_field = 'store';
		
		private $associated_cache_keys = array(
			'departments',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, eidt, view, delete) will be available in access control
			'special_actions' => array(
				'all_dept' => array(
					'title' => 'All Departments',
					'todo' => 'all_depts',
					'type' => '',
				),
			),
		);
		
		public $table_fields = array(
			'name' => 'departments001',
			'description' => 'departments002',
			'head' => 'departments003',
			//'assistant' => 'departments004',
			//'secretary' => 'departments005',
			'store' => 'departments006',
			'pay_roll' => 'departments007', //[exempt tax only, exempt pensions only, exempt tax & pensions]
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
	
		function departments(){
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
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$this->class_settings["has_branch"] = 1;
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
			case 'get_departments':
				$returned_value = $this->_get_departments();
			break;
			case 'display_all_records_frontend_branch':
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
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
			case 'search_list2_datatable':
			case 'search_list2_handsontable':
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
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
			case 'get_select2_access':
			case 'get_select2':
			case 'get_select2_special':
				$returned_value = $this->_get_select2();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _get_select2(){
			$join = '';
			$where = '';
			$limit = '';
			$search_term = '';
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` regexp '".$search_term."' OR `".$this->table_name."`.`serial_num` = '".$search_term."' ) ";
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$select = "";
			$select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num` ";
			
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
				case "name":
					$select .= ", `".$val."` as 'text' ";
				break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
				}
			}
			
			if( function_exists("get_hyella_has_independent_branches_settings") && get_hyella_has_independent_branches_settings() ){
				$branch = get_current_customer( "branch" );
				if( $branch ){
					if( isset( $this->branch_field ) && $this->branch_field && isset( $this->table_fields[ $this->branch_field ] ) ){
						$where .= " AND `".$this->table_name."`.`". $this->table_fields[ $this->branch_field ] ."` = '" . $branch . "' ";
					}
				}
			}
			
			$dep_fo = intval( get_general_settings_value( array( "key" => "DEPARTMENT FILTER OPTIONS FOR SPECIFIC BRANCH", "table" => "general_settings" ) ) );
			
			//2 - use organization structure,0 - use store field in dept table(this is also the default option), 1 - disregard all conditions
			
			if( isset( $_GET["in_store"] ) && $_GET["in_store"] && isset( $_POST["store"] ) && $_POST["store"] && function_exists("get_single_clinic_settings") && ! get_single_clinic_settings() ){
				$pst = $_POST["store"];
				
				switch( $dep_fo ){
				case 1:
				break;
				case 2:
					$ac = new cStores();
					$join .= " JOIN `".$this->class_settings["database_name"]."`.`".$ac->table_name."` ON `".$ac->table_name."`.`".$ac->table_fields["department"]."` = `".$this->table_name."`.`id` AND ( `".$ac->table_name."`.`id` = '". $pst ."' OR `".$ac->table_name."`.`".$ac->table_fields["parent"]."` = '". $pst ."' ) AND `".$ac->table_name."`.`record_status` = '1' ";
				break;
				default:
					$where .= " AND `".$this->table_name."`.`". $this->table_fields[ "store" ] ."` LIKE '%" . $pst . "%' ";
				break;
				}
			}
			
			switch( $action_to_perform ){
			case "get_select2_access":
				$access = get_accessed_functions();
				$super = 0;	//correction 13-jul-23 @pato
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
				
				//$where .= " AND `".$this->table_name."`.`".$this->table_fields["status"]."` = 'active' ";
				if( $super || isset( $access["accessible_functions"][ $this->table_name . ".all_dept" ] ) || isset( $access["accessible_functions"][ $this->table_name . ".all_dept.all_dept" ] ) ){
					
				}else{
					$user_id = $this->class_settings["user_id"];
					
					$ac = new cUsers();
					$join .= " JOIN `".$this->class_settings["database_name"]."`.`".$ac->table_name."` ON `".$ac->table_name."`.`id` = '".$user_id."' AND `".$ac->table_name."`.`".$ac->table_fields["department"]."` = `".$this->table_name."`.`id` ";
				}
				
			break;
			}
			
			if( $join ){
				$this->class_settings["join"] = $join;
			}
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$sqlx = $this->_get_records();
			//26-may-23: fixed json_encode error on mis.od1 server
			if( function_exists("utf8ize") && defined("USE_STEVE_UTF8IZE") ){
				$sqlx = utf8ize($sqlx);
			}
			switch ($action_to_perform) {
				case 'get_select2_special':
					if( $_POST['term'] ){
						$sqlx[] = array( 'id' => $_POST['term'].':::typed', 'text' => $_POST['term'] );
					}
				break;
			}					
			return array( "items" => $sqlx, "do_not_reload_table" => 1 );
		}
		
		private function _display_app_view2(){
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$this->class_settings["skip_link"] = 1;
			
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
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
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
			$this->class_settings[ "show_popup_form" ] = 1;
			$this->class_settings[ "return_new_format" ] = 1;
			$this->class_settings[ "full_table" ] = 1;
			/* $this->class_settings[ "show_form" ] = 1;
			$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
			$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';

			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "date":
					//$this->class_settings["form_values_important"][ $val ] = date("U");
					$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
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

		protected function _save_departments(){
			return $this->_update_table_field();
		}
		
		protected function _authenticate_membership(){
			$action = 'authenticate_membership';
			
			switch( $this->class_settings["action_to_perform"] ){
			case "direct_mark_atttendance":
				$action = 'authenticate_membership_direct';
				
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] && isset( $_POST["mod"] ) && $_POST["mod"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Pass Code</h4>Please enter your pass code';
					return $err->error();
				}
				
				$this->class_settings["customer"] = $_POST["id"];
				$this->class_settings["access_code"] = $_POST["mod"];
			break;
			default:
			
				if( !( isset( $_POST["pass_code"] ) && $_POST["pass_code"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Pass Code</h4>Please enter your pass code';
					return $err->error();
				}
				$this->class_settings["pass_code"] = $_POST["pass_code"];
				
			break;
			}
			
			$membership_plan = new cMembership();
			$membership_plan->class_settings = $this->class_settings;
			$membership_plan->class_settings["action_to_perform"] = $action;
			$sql = $membership_plan->membership();
			
			if( isset( $sql["log"] ) && is_array( $sql["log"] ) ){
				//log departments
				$_POST["id"] = '';
				$this->class_settings["update_fields"] = $sql["log"];
				$r = $this->_update_table_field();
				
				switch( $this->class_settings["action_to_perform"] ){
				case "direct_mark_atttendance":
					if( isset( $r["saved_record_id"] ) && $r["saved_record_id"] ){
						$err = new cError('010011');
						$err->action_to_perform = 'notify';
						$err->html_format = 2;
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = '<h4>departments Successfully Marked</h4>departments Successfully Marked<br />@ <strong>'.date("d-M-Y H:i") . '</strong>';
						$return = $err->error();
						
						unset( $return["html"] );
						$return["html_removals"][] = "#departments-" . $this->class_settings["customer"];
						$return["status"] = 'new-status';
						return $return;
					}
				break;
				}
			}
			
			return $sql;
			//221
			//13657852365
			//0220
		}
		
		protected function _display_departments_view(){
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-departments-view' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
		}
		
	}
?>
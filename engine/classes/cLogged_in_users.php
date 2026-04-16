<?php
/**
 * logged_in_users Class
 *
 * @used in  				logged_in_users Function
 * @created  				08:47 | 20-Nov-2019
 * @database table name   	logged_in_users
 */
	
	class cLogged_in_users extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'logged_in_users';
		
		public $default_reference = '';
		
		public $label = 'Logged In Users';
		
		private $associated_cache_keys = array(
			'logged_in_users',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
		public $basic_data = array(
			'access_to_crud' => 0,
			'exclude_from_crud' => array( 'create_new_record' => 1, 'edit' => 1 ),
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/logged_in_users.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/logged_in_users.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function logged_in_users(){
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
			case "control_user_session":
				$returned_value = $this->_control_user_session();
			break;
			case "update_log":
				$returned_value = $this->_update_log();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		public function _update_log( $opts = array() ){

			if( isset( $opts[ 'action' ] ) && $opts[ 'action' ] ){
				$user_id = isset( $opts[ 'user_id' ] ) ? $opts[ 'user_id' ] : $this->class_settings["user_id"];
				$session_id = session_id();

				if( ! ( isset( $this->class_settings["user_id"] ) && $this->class_settings["user_id"] ) ){
					$this->class_settings["user_id"] = $user_id;
				}

				$line_items = array();
				$dl = array(
					"date" => date("U"),
					"session" => $session_id,
					"status" => $opts[ 'action' ],
					"staff_responsible" => $user_id,
				);
				
				$line_items[] = $dl;
				
				$this->class_settings['skip_manifest'] = 1;
				$this->class_settings["line_items"] = $line_items;
				if( $this->_save_line_items() ){
					$salt = get_websalter();
					$settings = array(
						'cache_key' => md5( $salt . $user_id ),
						'cache_values' => array( "key" => md5( $salt . $session_id ), "login" => date("U") ),
						'permanent_only' => true,
						'permanent' => true,
						'directory_name' => $this->table_name,
					);
					set_cache_for_special_values( $settings );
				}
			}
		}

		public function get_index_fields( $opts = array() ){
			$r = array();
			
			$r["title"] = " `".$this->table_name."`.`".$this->table_fields["status"]."` ";
			$r["summary"] = " CONCAT( FROM_UNIXTIME( `".$this->table_name."`.`".$this->table_fields["date"]."` ), '<br />', `".$this->table_name."`.`".$this->table_fields["session"]."` ) ";
			
			return $r;
		}
		
		protected function _control_user_session(){
			//check for previous session
			if( isset( $this->class_settings["user_id"] ) && $this->class_settings["user_id"] ){
				$user_id = $this->class_settings["user_id"];
				//$p_session = $_SESSION;
				$session_id = session_id();
				
				$this->class_settings["where"] = " AND `".$this->table_fields["staff_responsible"]."` = '". $user_id ."' AND `".$this->table_fields["status"]."` = 'login' ";
				//$r = $this->_get_records();
				
				$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `modification_date` = ". date("U") .", `".$this->table_fields["status"]."` = 'logout', `".$this->table_fields["session"]."` = '' WHERE `".$this->table_name."`.`".$this->table_fields["status"]."` = 'login' AND `".$this->table_name."`.`".$this->table_fields["staff_responsible"]."` = '". $user_id ."' ";
			
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
					'skip_manifest' => 1
				);
				execute_sql_query($query_settings);
				
				/* if ( $session_id ) {
					session_commit();
				}
				
				if( isset( $r[0]["id"] ) ){
					foreach( $r as $rv ){
						session_id( $rv["session"] );
						
						// if (ini_get("session.use_cookies")) {
							// $params = session_get_cookie_params();
							// setcookie(session_name(), '', time() - 42000,
								// $params["path"], $params["domain"],
								// $params["secure"], $params["httponly"]
							// );
						// }
						
						session_start();
						$_SESSION = array();
						//session_destroy();
						session_commit();
					}
				}
				
				session_id( $session_id );
				session_start();
				$_SESSION = $p_session;
				session_commit(); */
				
				//print_r( $_SESSION ); exit;
				$line_items = array();
				$dl = array(
					"date" => date("U"),
					"session" => $session_id,
					"status" => "login",
					"staff_responsible" => $user_id,
				);
				
				$line_items[] = $dl;
				
				$this->class_settings['skip_manifest'] = 1;
				$this->class_settings["line_items"] = $line_items;
				if( $this->_save_line_items() ){
					$salt = get_websalter();
					$settings = array(
						'cache_key' => md5( $salt . $user_id ),
						'cache_values' => array( "key" => md5( $salt . $session_id ), "login" => date("U") ),
						'permanent_only' => true,
						'permanent' => true,
						'directory_name' => $this->table_name,
					);
					set_cache_for_special_values( $settings );
				}
			}
		}
		
		protected function _search_form2(){
			$where = "";
			
			$key = 'staff_responsible';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'status';
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
			
			$this->class_settings[ "show_form" ] = 1;
			$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
			$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
			$this->class_settings[ "add_empty_select_option" ] = 1;
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "date":
					//$this->class_settings["form_values_important"][ $val ] = date("U");
					$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
				break;
				case "status":
				case "staff_responsible":
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
						$err = new cError(010014);
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
					$err = new cError(010014);
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
		
	}
	
	function logged_in_users(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/logged_in_users.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/logged_in_users.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				if( isset( $return[ "fields" ] ) ){
					
					$key = $return[ "fields" ][ "staff_responsible" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'users',
						'reference_keys' => array( 'firstname', 'lastname' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=users&todo=get_users_select2" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "session" ];
					$return[ "labels" ][ $key ][ 'display_position' ] = 'do-not-display-in-table';
					
					$key = $return[ "fields" ][ "status" ];
					$return[ "labels" ][ $key ][ 'form_field' ] = 'select';
					$return[ "labels" ][ $key ][ 'form_field_options' ] = '__logged_in_users_status';
					
				}
				
				return $return[ "labels" ];
			}
		}
	}
	
	function __logged_in_users_status(){
		return array(
			"login" => "Login",
			"logout" => "Logout",

			"reset_pass" => "Reset Password",
			"locked_account" => "Locked Account",
			"unlocked_account" => "Unlocked Account",
			"activate_account" => "Activated Account",
			"inactive_account" => "Inavtive Account Login",

			"account_created" => "Account Created",
			"account_edited" => "Account Edited",
			"account_deleted" => "Account Deleted",
			"otp_generation" => "OTP Generated",
			"otp_validation" => "OTP Validated",
		);
	}
	
	function __logged_in_users_validate( $cs = array(), $session_id = '' ){
		//print_r($cs); print_r($session_id); exit;
		if( defined("NWP_OPEN2_ACCESS") && NWP_OPEN2_ACCESS ){
			return true;
		}
		
		if( isset( $_SESSION ) && ! empty( $_SESSION ) ){
			if( isset( $cs["privilege"] ) && $cs["privilege"] && $cs["privilege"] == 'system' ){
				return true;
			}
			
			if( isset( $cs["id"] ) && $cs["id"] ){
				$salt = get_websalter();
				//echo md5( $salt . $cs["id"] ); exit;
				$settings = array(
					'cache_key' => md5( $salt . $cs["id"] ),
					'directory_name' => 'logged_in_users',
					'permanent_only' => true,
					'permanent' => true,
				);
				$sess = get_cache_for_special_values( $settings );
				//print_r( $sess ); exit;
				//echo md5( $salt . $session_id ); exit;
				/* if( $sess ){
					echo $sess; exit;
				} */
				if( isset( $sess["key"] ) && $sess["key"] == md5( $salt . $session_id ) ){
					$sess["last_action"] = date("U");
					$settings["cache_values"] = $sess;
					set_cache_for_special_values( $settings );
					return true;
				}
			}
			return false;
		}else{
			return true;
		}
	}
?>
<?php
/**
 * locked_files Class
 *
 * @used in  				locked_files Function
 * @created  				14:14 | 18-Nov-2019
 * @database table name   	locked_files
 */
	
	class cLocked_files extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'locked_files';
		
		public $default_reference = '';
		
		public $label = 'Locked Files';
		
		public $basic_data = array(
			'access_to_crud' => 0,
			'exclude_from_crud' => array( 'create_new_record' => 1, 'edit' => 1 ),
		);
		
		private $associated_cache_keys = array(
			'locked_files',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/locked_files.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/locked_files.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function locked_files(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
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
			case "update_lock_time":
			case "check_for_full_access2":
				$returned_value = $this->_check_for_full_access();
			break;
			case "close_file":
				$returned_value = $this->_close_file();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _close_file(){
			$error = '';
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle =  "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			if( isset( $_GET["reference_table"] ) && $_GET["reference_table"] &&  isset( $_GET["reference"] ) && $_GET["reference"] ){
				
				$this->_clear_user_lock();
				return $this->_display_notification( array( "type" => "success", "message" => "<h4>File Closed</h4>Access to the file has been given-up", "html_replacement_selector" => $handle ) );
			}else{
				$error = 'Invalid Reference';
			}
			
			return $this->_display_notification( array( "type" => "error", "message" => $error ) );
		}
		
		function update_locked_files( $e = array() ){
			$tables = array(
				"workflow" => 1,
				"locked_files" => 1,
			);
			
			$update = 0;
			
			if( isset( $e["table"] ) && isset( $tables[ $e["table"] ] ) ){
				$update = 1;
				
				if( $e["table"] == "locked_files" )return;
			}else{
				$this->_clear_user_lock();
			}
			
			if( $update ){
				if( isset( $this->class_settings["user_id"] ) && $this->class_settings["user_id"] ){
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["date"]."` = ". date("U") ." WHERE `".$this->table_name."`.`". $this->table_fields["staff_responsible"] ."` = '". $this->class_settings["user_id"] ."' ";
			
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
			}
		}
		
		function _check_for_full_access( $e = array(), $options = array() ){
			$timespan = 60 * 5;	//time in seconds
			$date = date("U") - $timespan;
			$setTimeout = 0;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case "check_for_full_access2":
				foreach( $this->table_fields as $tk => $tv ){
					if( isset( $_GET[ $tk ] ) ){
						$e[ $tk ] = $_GET[ $tk ];
					}
				}
			break;
			}
			
			switch( $action_to_perform ){
			case "update_lock_time":
				if( isset( $_POST["reference_action"] ) && $_POST["reference_action"] &&  isset( $_POST["reference_table"] ) && $_POST["reference_table"] && isset( $_POST["interval"] ) && intval( $_POST["interval"] ) > 1 ){
					$e["reference"] = $_POST["reference_action"];
					$e["reference_table"] = $_POST["reference_table"];
					
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_fields["date"]."` = ( `".$this->table_fields["date"]."` + ". intval( $_POST["interval"] ) ." ) WHERE `".$this->table_name."`.`".$this->table_fields["reference_table"]."` = '". $_POST["reference_table"] ."' AND `".$this->table_name."`.`".$this->table_fields["reference"]."` = '". $_POST["reference_action"] ."' AND `".$this->table_name."`.`".$this->table_fields["staff_responsible"]."` = '". $this->class_settings["user_id"] ."' ";
			
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
					$setTimeout = 1;
				}
			break;
			default:
				//print_r( $e ); exit;
				if( isset( $e["reference"] ) && $e["reference"] && isset( $e["reference_table"] ) && $e["reference_table"] ){
					$this->class_settings["where"] = " AND `".$this->table_fields["reference"]."` = '". $e["reference"] ."'  AND `".$this->table_fields["reference_table"]."` = '". $e["reference_table"] ."' AND `".$this->table_fields["date"]."` > ". $date;
					
					if( isset( $e["division"] ) && $e["division"] ){
						$this->class_settings["where"] .= " AND ( `".$this->table_fields["division"]."` = '". $e["division"] ."' OR `".$this->table_fields["division"]."` = '' OR `".$this->table_fields["division"]."` IS NULL ) ";
						
						if( isset( $e["department"] ) && $e["department"] ){
							$this->class_settings["where"] .= " AND ( `".$this->table_fields["department"]."` = '". $e["department"] ."' OR `".$this->table_fields["department"]."` = '' OR `".$this->table_fields["department"]."` IS NULL ) ";
							
							if( isset( $e["unit"] ) && $e["unit"] ){
								$this->class_settings["where"] .= " AND ( `".$this->table_fields["unit"]."` = '". $e["unit"] ."' OR `".$this->table_fields["unit"]."` = '' OR `".$this->table_fields["unit"]."` IS NULL ) ";
							}
							
						}
						
					}
					
					if( isset( $options["has_children"] ) && $options["has_children"] ){
						$this->class_settings["where"] .= " AND `".$this->table_fields["staff_responsible"]."` != '". $this->class_settings["user_id"] ."' ";
					}
					
					$d = $this->_get_records();
					
					$clear_lock = 0;
					
					if( isset( $d[0]["id"] ) ){
						
						$check_user = 1;
						$blocked = 0;
						
						if( isset( $options["has_children"] ) && $options["has_children"] ){
							if( count( $d ) > 1 ){
								$check_user = 0;
							}
						}
						
						if( $check_user ){
							if( $d[0]["staff_responsible"] == $this->class_settings["user_id"] ){
								$clear_lock = 1;
							}else{
								$blocked = 1;
							}
						}else{
							$blocked = 1;
						}
						
						if( $blocked ){
							//clear previous lock by this user
							$this->_clear_user_lock();
							
							//locked by another user
							$uname = get_name_of_referenced_record( array( "id" => $d[0]["staff_responsible"], "table" => "users" ) );
							
							$msg = '<h4>Restricted Access</h4><p>The file is already being edited by <strong>'. $uname .'</strong></p><br /><p>You will be able to edit this file after <strong>'. date("d-M-Y H:i", $d[0]["date"] + $timespan ) .'</strong>, If no further input is made by <strong>'.$uname.'</strong> or when he/she closes the file</p><small>Ref No: ' . $d[0]["reference"] . '</small>';
							
							$return = $this->_display_notification( array( "type" => "error", "message" => $msg ) );
							//$return["manual_close"] = 1;
							$return["locked"] = 1;
							
							$flags = array();
							
							$e["action"] = $this->table_name;
							$e["todo"] = 'check_for_full_access2';
							
							foreach( $e as $ak => $av ){
								$flags[] = $ak . '=' . $av;
							}
							$return["flag_action"] = "?" . implode( "&", $flags );
							
							return $return;
						}
					}else{
						$clear_lock = 1;
					}
					
					if( $clear_lock ){
						//clear previous lock by this user
						$this->_clear_user_lock();
						
						//lock the file
						$line_items = array();
						$dl = array(
							"date" => date("U"),
							"reference" => $e["reference"],
							"reference_table" => $e["reference_table"],
							"staff_responsible" => $this->class_settings["user_id"],
						);
						
						foreach( $this->table_fields as $tk => $tv ){
							if( isset( $e[ $tk ] ) && ! isset( $dl[ $tk ] ) ){
								$dl[ $tk ] = $e[ $tk ];
							}
						}
						
						$line_items[] = $dl;
						
						$this->class_settings["line_items"] = $line_items;
						$this->_save_line_items();
						$setTimeout = 1;
						
					}
					
				}
			break;
			}
			
			switch( $action_to_perform ){
			case "check_for_full_access2":
				return $this->_display_notification( array( "type" => "success", "message" => '<h4>Full Access</h4><p>Please re-open file to gain full access</p>' ) );
			break;
			}
			
			if( $setTimeout ){
				$return = array();
				
				$msg = '<h4>Full Access</h4><p>This file was opened with full access</p>';
				$return = $this->_display_notification( array( "type" => "success", "message" => $msg ) );
				unset( $return["html"] );
				
				$return["locked_file_timeout"] = $timespan;
				$return["locked_file_action"] = $e["reference"];
				$return["locked_file_table"] = $e["reference_table"];
				
				switch( $action_to_perform ){
				case "update_lock_time":
					$return["status"] = "new-status";
				break;
				}
				
				return $return;
			}
			
		}
		
		protected function _clear_user_lock(){
			if( isset( $this->class_settings["user_id"] ) ){
				$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`". $this->table_fields["staff_responsible"] ."` = '". $this->class_settings["user_id"] ."' ";
			
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'DELETE',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
			}
		}
		
		protected function _search_form2(){
			$where = "";
			
			$key = 'staff_responsible';
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
			
			$this->datatable_settings[ "show_add_new" ] = 0;
			$this->datatable_settings[ "show_edit_button" ] = 0;
			
			$this->class_settings[ "return_new_format" ] = 1;
			
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
		
	}
	
	function locked_files(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/locked_files.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/locked_files.json' ), true );
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
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=users&todo=get_select2" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					/* 
					$key = $return[ "fields" ][ "department" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'departments',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=departments&todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					 */
				}
				
				return $return[ "labels" ];
			}
		}
	}
?>
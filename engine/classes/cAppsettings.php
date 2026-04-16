<?php
	/**
	 * appsettings Class
	 *
	 * @used in  				appsettings Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	appsettings
	 */

	/*
	|--------------------------------------------------------------------------
	| appsettings Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cAppsettings{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'appsettings';
		
        private $table_fields = array(
			'app_name' => 'appsettings001',
			'app_logo' => 'appsettings002',
            
			'slogan' => 'appsettings004',
			'contact_address' => 'appsettings005',
			'contact_phone_number' => 'appsettings006',
			'contact_email_address' => 'appsettings007',
            
			'support_line' => 'appsettings008',
		);
        
		function appsettings(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
			case 'create_new_appsettings':
			case 'proceed_to_next_session':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
				
				//Add id of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'display':
				$returned_value = $this->_display();
			break;
			case 'get_appsettings':
				$returned_value = $this->_get_appsettings();
			break;
			case 'modify_appsettings_settings':
				$returned_value = $this->_modify_appsettings_settings();
			break;
			case 'save_created_super_administrator_account':
				$returned_value = $this->_save_created_super_administrator_account();
			break;
			case 'save_proceed_to_next_session':
				$returned_value = $this->_save_proceed_to_next_session();
			break;
			case 'get_properties_of_appsettings_from_database':
				 $function_settings = array(
					'check_cache' => 1,
				 );
				$returned_value = $this->_get_properties_of_appsettings_from_database( $function_settings );
			break;
			}
			
			return $returned_value;
		}
		
		
		private function _modify_appsettings_settings(){
			
			$returned_value = $this->_save_changes();
			
			if( isset( $returned_value[ 'typ' ] ) && $returned_value[ 'typ' ]=='saved' ){
				
				return $this->_get_properties_of_appsettings();
				
			}
			
			return $returned_value;
		}
		
		private function _get_appsettings(){
			//RETURN ALL PROPERTIES OF A appsettings
			//this method is called when initializing the application
			
			//1. CHECK FOR LOGGED IN USER
			
			if( isset( $this->class_settings['user_email'] ) && $this->class_settings['user_email'] &&  isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id']  ){
				
				//YES
				//Redirect to dashboard
				$appsettings_setup_page_html_content = '';
				//Redirect to dashboard
				$appsettings_setup_page_html_content = '';
				if( file_exists( ( $this->class_settings['calling_page'] . 'html-files/templates-1/dashboard-page.php' ) ) ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/dashboard-page.php' );
					$appsettings_setup_page_html_content = $this->_get_html_view();
				}
					
				$returning_array = array(
					'html' => $appsettings_setup_page_html_content,
					'method_executed' => $this->class_settings['action_to_perform'],
					'appsettings_properties' => $this->_get_properties_of_appsettings_from_database( array( 'check_cache' => true ) ),
					'status' => 'redirect-to-dashboard',
					'message' => 'Existing appsettings Properties and authenticated user',
				);
				
				return $returning_array;
			}else{
				//NO
				//go to no. 2
			
			//2. CHECK FOR appsettings SETTINGS / PROPERTIES
				//YES
				//set appsettings settings in filecache
				//return appsettings settings 
				$appsettings_properties = $this->_get_properties_of_appsettings_from_database();
				if( is_array( $appsettings_properties ) && ! empty( $appsettings_properties ) ){
					
					$returned_values = $this->_create_super_administrator_account();
					
					if( $returned_values ){
						
						$returning_array = array(
							'html' => $returned_values[ 'html' ],
							'method_executed' => $this->class_settings['action_to_perform'],
							'appsettings_properties' => $appsettings_properties,
							'status' => 'modify-appsettings-settings',
							'message' => 'Created appsettings',
						);
						
						return $returning_array;
					}
					
					$login_page = '';
					if( defined("HYELLA_LOGIN_PAGE") && HYELLA_LOGIN_PAGE ){
						$login_page = HYELLA_LOGIN_PAGE;
					}
					
					return array(
						"redirect_url" => "../sign-in/" . $login_page,
						'status' => 'new-status',
					);
					
					//display login page
					$appsettings_login_form_content = '';
					//if( file_exists( ( $this->class_settings['calling_page'] . 'html-files/templates-1/login-page.php' ) ) )
						//$appsettings_setup_page_html_content = read_file( '', $this->class_settings['calling_page'] . 'html-files/templates-1/login-page.php' );
					$classname = 'authentication';
					$actual_name_of_class = 'c'.ucwords($classname);
					
					$module = new $actual_name_of_class();
					$module->class_settings = array(
						'database_connection' => $this->class_settings[ 'database_connection' ],
						'database_name' => $this->class_settings[ 'database_name' ],
						'calling_page' => $this->class_settings[ 'calling_page' ],
						
						'action_to_perform' => 'users_login_process',
					);
					$appsettings_login_form_content = $module->$classname();
					
					$pr = get_project_data();
					
					$returning_array = array(
						'html' => '<h5 id="appsettings-name" style="text-align:center;"><strong>'.$pr["company_name"].'</strong></h5><div class="container">' . $appsettings_login_form_content['html'] . '</div>',
						'method_executed' => $this->class_settings['action_to_perform'],
						'appsettings_properties' => $appsettings_properties,
						'status' => 'redirect-to-login',
						'message' => 'Super administrator account already set',
					);
					
					return $returning_array;
					
				}else{
					//NO
					//display appsettings set-up page
					$appsettings_setup_page_html_content = '';
					if( file_exists( ( $this->class_settings['calling_page'] . 'html-files/templates-1/appsettings-setup-page.php' ) ) )
						$appsettings_setup_page_html_content = read_file( '', $this->class_settings['calling_page'] . 'html-files/templates-1/appsettings-setup-page.php' );
					
					$returning_array = array(
						'html' => $appsettings_setup_page_html_content,
						'create_new_appsettings_data' => array(
							'function_class' => $this->table_name,
							'function_name' => 'create_new_appsettings',
							'module_id' => $this->class_settings['current_module'],
						),
						'method_executed' => $this->class_settings['action_to_perform'],
						'status' => 'display-appsettings-setup-page',
						'message' => 'No created appsettings',
					);
				}
				return $returning_array;
			}
			
		}
		//BASED METHODS
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		private function _get_properties_of_appsettings_from_database( $function_settings = array() ){
			$settings = array(
				'cache_key' => $this->table_name,
				'permanent' => true,
			);
			
			if( isset( $function_settings[ 'check_cache' ] ) && $function_settings[ 'check_cache' ] ){
				$cached_values = get_cache_for_special_values( $settings );
				if( is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
			}
			//CHECK IF CACHE IS SET
			
                
                $query = "";
                
                $select = '';
                foreach( $this->table_fields as $key => $val ){
                    if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
                    else $select = "`".$this->table_name."`.`id`, `".$val."` as '".$key."'";
                }
                
				$query = "SELECT ".$select." FROM `".$this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`record_status`='1' LIMIT 1";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				
				$sql_result = execute_sql_query($query_settings);
				
				if( isset( $sql_result[0] ) ){
					
					//Cache Settings
					$settings = array(
						'cache_key' => $this->table_name,
						'cache_values' => $sql_result[0],
                        'permanent' => true,
					);
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					return $sql_result[0];
				}
			
			return 0;
		}
		
		private function _create_super_administrator_account(){
			//CHECK FOR EXISTING SUPER ADMINISTRATOR ACCOUNT
			$u = new cUsers();
			$users_table = $u->table_name;;
			// print_r( $u->table_fields );exit;
			$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $users_table . "` WHERE `".$users_table."`.`record_status`='1' AND `".$users_table."`.`". $u->table_fields[ 'role' ] ."`='1300130013'";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $users_table ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if( ! ( isset( $sql_result[0] ) && is_array( $sql_result[0] ) && ! empty( $sql_result[0] ) ) ){
				//Display Users Account Creation Form
				$process_handler = new cProcess_handler();
				$process_handler->class_settings = $this->class_settings;
				
				$process_handler->class_settings[ 'database_table' ] = $users_table;
				$process_handler->class_settings[ 'form_action_todo' ] = 'save_created_super_administrator_account';
				$process_handler->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_created_super_administrator_account';
				
				$process_handler->class_settings['form_heading_title'] = '2. Set-up Super Administrator Account';
				
				$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
				
				$process_handler->class_settings[ 'form_values' ] = array(
					$u->table_fields[ 'role' ] => '1300130013',
				);
			
				
				$process_handler->class_settings['hidden_records'] = array(
					$u->table_fields[ 'oldpassword' ] => 1,
					$u->table_fields[ 'department' ] => 1,
					$u->table_fields[ 'date_of_birth' ] => 1,
					$u->table_fields[ 'sex' ] => 1,
					$u->table_fields[ 'address' ] => 1,
					$u->table_fields[ 'date_employed' ] => 1,
					$u->table_fields[ 'ref_no' ] => 1,
					$u->table_fields[ 'account_number' ] => 1,
				);
				
				return $process_handler->process_handler();
			}
			
			return 0;
		}
		
		private function _set_current_session(){
		
		}
		
		private function _save_proceed_to_next_session(){
			//CHECK IF SESSION RECORDS EXISTS IN GRADEBOOK
			if( isset($_POST['table']) && $_POST['table'] == $this->table_name && isset($_POST['appsettings005']) && $_POST['appsettings005'] ){
				$this->class_settings['selected_session'] = $_POST['appsettings005'];
				
				$gradebook = new cGradebook();
				$gradebook->class_settings = $this->class_settings;
				
				$gradebook->class_settings['action_to_perform'] = 'check_for_existing_session_records';
				
				$returned_data = $gradebook->Gradebook();
				
				if( isset( $returned_data[ 'session_gradebook_records' ] ) && ! $returned_data[ 'session_gradebook_records' ] ){
					//ALLOW PROCEED TO SESSION
					$returned_value = $this->_save_changes();
					
					if( isset( $returned_value[ 'typ' ] ) && $returned_value[ 'typ' ]=='saved' ){
						//UPDATE SESSION OF ALL CLASSES & CREATE GRADEBOOK ENTRIES
						$classes = new cClasses();
						$classes->class_settings = $this->class_settings;
						
						$classes->class_settings['action_to_perform'] = 'update_session_of_all_classes_and_create_gradebook_entries';
						
						$returned_data = $classes->Classes();
					}
					
					return $returned_value;
				}
			}
			
			//RETURN INVALid SELECTION ERROR
			$err = new cError(000105);
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'cappsettings.php';
			$err->method_in_class_that_triggered_error = '_save_proceed_to_next_session';
			return $err->error();
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'create_new_appsettings':
				$process_handler->class_settings[ 'form_heading_title' ] = '1. Set-up Your App Information';
				$process_handler->class_settings[ 'form_action_todo' ] = 'modify_appsettings_settings';
			break;
			default:
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
				$process_handler->class_settings['form_heading_title'] = 'App Settings Modification';
			break;
			}
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			$process_handler->class_settings[ 'form_class' ] = 'form-horizontal';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
			);
		}
		
		private function _delete_records(){
			$returning_html_data = '';
			
			if( isset($_POST['mod']) && $_POST['mod']=='delete-'.md5( $this->table_name ) && ( isset($_POST['id']) || isset($_POST['ids']) ) ){
				$condition = "";
				$fields_to_delete = "";
				$values_to_delete = "";
				$select_clause_for_query = "";
				
				if( isset($_POST['ids']) && $_POST['ids'] ){
					$condition = "OR";
					
					$array_of_ids = explode(':::' , $_POST['ids']);
					if( is_array($array_of_ids) ){
						foreach( $array_of_ids as $ids ){
							if( $ids ){
								if($values_to_delete)$values_to_delete .= '<>'.$ids;
								else $values_to_delete = $ids;
								
								if($fields_to_delete)$fields_to_delete .= ',id';
								else $fields_to_delete = 'id';
								
								if($select_clause_for_query)$select_clause_for_query .= " OR `" . $this->table_name . "`.`id`='".$ids."'";
								else $select_clause_for_query = "`" . $this->table_name . "`.`id`='".$ids."'";
							}
						}
					}
				}
				
				if( ! ($fields_to_delete && $values_to_delete) ){
					$fields_to_delete = 'id';
					$values_to_delete = $_POST['id'];
					
					$select_clause_for_query = "`" . $this->table_name . "`.`id`='".$_POST['id']."'"; 
				}
				
				//delete items
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->table_name ,
					'field_and_values' => array(
						'record_status' => array(
							'value' => '0',
						),
						'modification_date' => array(
							'value' => date("U"),
						),
						'modified_by' => array(
							'value' => $this->class_settings['user_id'] ,
						),
					) ,
					'where_fields' => $fields_to_delete ,
					'where_values' => $values_to_delete ,
					'condition' => $condition ,
				);
				$save = update( $settings_array );
				
				
				if($save){
					//Auditor
					auditor( $this->class_settings['calling_page'] , $this->class_settings['priv_id'] , $this->class_settings['user_id'] , $this->class_settings['user'] , $this->class_settings['database_connection'] , $this->class_settings['database_name'] , 'delete' , $this->table_name , 'deleted record with '.$fields_to_delete.' '.$values_to_delete.' in the table' );
					
				
					//Return Successful write operation to database
					$err = new cError('010001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cappsettings.php';
					$err->method_in_class_that_triggered_error = '_delete';
					$err->additional_details_of_error = 'updated record with '.$fields_to_delete.' '.$values_to_delete.' on line 218';
					
					return $err->error();
				}
			}
			
			//Return unsuccessful update operation
			$err = new cError('000006');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'cappsettings.php';
			$err->method_in_class_that_triggered_error = '_delete';
			$err->additional_details_of_error = 'could not update record on line 218';
			return $err->error();
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALid TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cappsettings.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$form->datatables_settings = array(
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
				
				'current_module_id' => $this->class_settings['current_module'],	//Set id of the currently viewed module
			);
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
			//Update appsettings properties
			$this->_get_properties_of_appsettings_from_database();
			
			return $returning_html_data;
		}
		
		private function _save_created_super_administrator_account(){
			$returning_html_data = array();
			
			$process_handler = new cUsers();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save';
			
			$returning_html_data = $process_handler->users();
			
			if( isset( $returning_html_data[ 'typ' ] ) && $returning_html_data[ 'typ' ]=='saved' ){
				//Authenticate New Super Administrator User into application
				//**ADD LATER
				
				//Redirect to dashboard
				$appsettings_setup_page_html_content = '';
				if( file_exists( ( $this->class_settings['calling_page'] . 'html-files/templates-1/dashboard-page.php' ) ) )
					$appsettings_setup_page_html_content = read_file( '', $this->class_settings['calling_page'] . 'html-files/templates-1/dashboard-page.php' );
					
				$returning_array = array(
					'html' => $appsettings_setup_page_html_content,
					'method_executed' => $this->class_settings['action_to_perform'],
					'appsettings_properties' => $this->_get_properties_of_appsettings_from_database( array( 'check_cache' => true ) ),
					'status' => 'redirect-to-dashboard',
					'message' => 'Successfully created and authenticated',
				);
				
				return $returning_array;
				
			}
			
		}
		

	}
?>
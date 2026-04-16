<?php
	/**
	 * cities_list Class
	 *
	 * @used in  				cities_list Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	cities_list
	 */

	/*
	|--------------------------------------------------------------------------
	| cities_list Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cCities_list{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'cities_list';
		
		private $associated_cache_keys = array(
			'cities_list',
		);
		
		public $table_fields = array(
			'country' => 'cities_list001',
			'state' => 'cities_list002',
			'city' => 'cities_list003',
			'dhl_remote_location_zone' => 'cities_list004',
		);
		
		private $datatable_settings = array(
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
			
		}
	
		function cities_list(){
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
			case 'get_cities_list':
				$returned_value = $this->_get_cities_list();
			break;
            case 'get_multiple_cities_in_a_country_option_list':
			case 'get_cities_in_a_country':
			case 'get_cities_in_a_country_option_list':
				$returned_value = $this->_get_cities_in_a_country();
			break;
			case 'refresh_cache':
			case 'cache':
				$returned_value = $this->_update_cache();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			}
			
			return $returned_value;
		}
		
        private function _update_cache(){
            $this->class_settings[ 'do_not_check_cache' ] = 1;
            $this->_get_cities_list1();
        }
        
        private function _get_cities_in_a_country(){
            $return = array();
            if( isset( $_GET['record_id'] ) && $_GET['record_id'] ){
                $this->class_settings['state'] = $_GET['record_id'];
                
                switch ( $this->class_settings['action_to_perform'] ){
                case 'get_multiple_cities_in_a_country_option_list':
                    $this->class_settings['state'] = explode( ',' , $_GET['record_id'] );
                break;
                }
                //$this->class_settings['country'] = $_GET['record_id'];
                
                $cities = $this->_get_cities_list();
                $specify_option = 1;
                
                if( is_array($cities) ){
                    switch ( $this->class_settings['action_to_perform'] ){
                    case 'get_multiple_cities_in_a_country_option_list':
                        $specify_option = 0;
                    case 'get_cities_in_a_country_option_list':
						
						if( empty( $cities ) || count( $cities ) < 3 ){
							$return["do_not_reload_table"] = 1;
							$return["status"] = "new-status";
							$return["javascript_functions"] = array("activate_no_city_select_field");
							return $return;
						}
						
                        $script_compiler = new cScript_compiler();
                        $script_compiler->class_settings = $this->class_settings;
                        
                        $script_compiler->class_settings[ 'data' ] = array(
                            'cities' => $cities,
                            'specify_option' => $specify_option,
                        );
                        $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/get-cities-in-a-country-option-list.php' );
                        $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                        $return['html'] = $script_compiler->script_compiler();
                        $return['status'] = 'got-cities-in-a-country-option-list';
                        
						$return["do_not_reload_table"] = 1;
						$return["status"] = "new-status";
						$return["html_replacement"] = $return["html"];
						$return["html_replacement_selector"] = "select.cities-select-field";
						$return["javascript_functions"] = array("activate_city_select_field");
						
						unset( $return["html"] );
                    break;
                    case 'get_cities_in_a_country':
                        foreach( $cities as $id => $val ){
                            if( isset( $val['state'] ) )
                                $return[ $id ] = $val['state'];
                        }
                    break;
                    }
                    
                    return $return;
                }
            }
            //Unverified Account / Missing Record
            $err = new cError('000021');
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
            $err->additional_details_of_error = 'No cities / Provinces were found for the selected country';
            $return  = $err->error();
            
			
            $return['status'] = 'new-status';
            unset( $return['html'] );
			$return["javascript_functions"] = array("activate_no_city_select_field");
            //$return['status'] = 'no-cities-in-country';
            return $return;
        }
         
		private function _display_datatable_view(){
			//RETURN CASH CALLS VIEW FOR SELECTED MONTH
			/*---------------------------------------*/
			$budget_id = '';
			if( isset( $_GET['budget_id'] ) && $_GET['budget_id'] ){
				$budget_id = $_GET['budget_id'];
			}
			
			//SET FILTER QUERY
			$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_name."`.`".$this->table_fields[ 'budget_id' ]."` = '".$budget_id."' ";
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/line-items-datatable-view.php' );
			$datatable = $this->_display_data_table();
			
			$this->class_settings[ 'data' ]['title'] = get_select_option_value( array( 'id' => $budget_id, 'function_name' => 'get_all_budgets' ) ).' All Line Items';
			$this->class_settings[ 'data' ]['form_title'] = 'Add / Edit Line Items';
			
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
				'html_replacement_selector' => '#main-table-view',
			);
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage Cities / LGA";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 'prepare_new_record_form_new' ) 
			);
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
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => $returning_html_data[ 'record_id' ],
			);
		}

		private function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->class_settings['current_record_id'] = $_POST['id'];
				$this->class_settings['country'] = $this->_get_country_id();
				$this->_get_cities_list();
			}
			
			return $returning_html_data;
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
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'ccities_list.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
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
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$this->class_settings['country'] = $this->_get_country_id();
					$this->_get_cities_list();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_cities_list(){
			if( ! ( isset( $this->class_settings['state'] ) && $this->class_settings['state'] ) && isset( $_GET['data'] ) && $_GET['data'] ){
                $this->class_settings['state'] = $_GET['data'];
            }
            
			if( isset( $this->class_settings['state'] ) && $this->class_settings['state'] ){
				if( ! is_array( $this->class_settings['state'] ) ){
                    $cache_key = $this->table_name;
                    $settings = array(
                        'cache_key' => $cache_key . '-' . $this->class_settings['state'],
                        'directory_name' => $cache_key,
                        'permanent' => true,
                    );
                    
                    $returning_array = array(
                        'html' => '',
                        'method_executed' => $this->class_settings['action_to_perform'],
                        'status' => 'got-cities-list',
                    );
                    
                    //CHECK WHETHER TO CHECK FOR CACHE VALUES
                    if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
                        
                        //CHECK FOR CACHED VALUES
                        
                        //CHECK IF CACHE IS SET
                        $cached_values = get_cache_for_special_values( $settings );
                        if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
                            
                            return $cached_values;
                        }
                        
                    }
                }
                
				$select = "";
				
				foreach( $this->table_fields as $key => $val ){
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
				}
				
                //PREPARE FROM DATABASE
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['state']."`='".$this->class_settings['state']."' ";
                
                $where = "";
                if( is_array( $this->class_settings['state'] ) ){
                    foreach( $this->class_settings['state'] as $v ){
                        if( $where )$where .= " OR `".$this->table_fields['state']."`='".$v."' ";
                        else $where = " `".$this->table_fields['state']."`='".$v."' ";
                    }
                }
                
                if( $where ){
                    $query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND ( ".$where." ) ";
                }
                
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$all_cities_list = execute_sql_query($query_settings);
				
				$cities_list = array();
				
				if( is_array( $all_cities_list ) && ! empty( $all_cities_list ) ){
					
					foreach( $all_cities_list as $category ){
						$cities_list[ $category['id'] ] = $category;
					}
					
                    if( ! is_array( $this->class_settings['state'] ) ){
                        //Cache Settings
                        $settings = array(
                            'cache_key' => $cache_key.'-'.$this->class_settings['state'],
                            'cache_values' => $cities_list,
                            'directory_name' => $cache_key,
                            'permanent' => true,
                        );
                        
                        if( ! set_cache_for_special_values( $settings ) ){
                            //report cache failure message
                        }
					}
                    
					return $cities_list;
				}
			}
			return array();
		}
		
		private function _get_cities_list1(){
			
            if( ! isset( $this->class_settings['state'] ) )return;
            $cache_key = $this->table_name;
            $settings = array(
                'cache_key' => $cache_key . '-' . $this->class_settings['state'],
                'directory_name' => $cache_key,
                'permanent' => true,
            );
            
            $select = "";
            
            foreach( $this->table_fields as $key => $val ){
                if( $select )$select .= ", `".$val."` as '".$key."'";
                else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
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
			$all_cities_list = execute_sql_query($query_settings);
				
			$cities_list = array();
				
            if( is_array( $all_cities_list ) && ! empty( $all_cities_list ) ){
                foreach( $all_cities_list as $category ){
                    $cities_list[ $category['state'] ][ $category['id'] ] = $category;
                }
			}		
            foreach( $cities_list as $state => $d ){
                //Cache Settings
                $settings = array(
                    'cache_key' => $cache_key.'-'.$state,
                    'cache_values' => $d,
                    'directory_name' => $cache_key,
                    'permanent' => true,
                );
                set_cache_for_special_values( $settings );
            }
            return $cities_list;
		}
		
		private function _get_country_id(){
			$returned_data = array();
			
			$cache_key = $this->table_name;
			
			if( isset( $this->class_settings['current_record_id'] ) && $this->class_settings['current_record_id'] ){
				
				if( ! isset( $this->class_settings['where'] ) ){
					$this->class_settings['where'] = " WHERE `id`='".$this->class_settings['current_record_id']."' AND `record_status`='1'";
				}
				
				$select = " `".$this->table_fields['country']."` as 'country' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if(isset($sql_result) && is_array($sql_result) && isset( $sql_result[0]['country'] ) ){
					return $sql_result[0]['country'];
				}
			}
			
		}
	}
?>
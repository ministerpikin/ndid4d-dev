<?php
/**
 * state_list Class
 *
 * @used in  				state_list Function
 * @created  				11:24 | 11-Sep-2019
 * @database table name   	state_list
 */
	
	class cState_list extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'state_list';
		
		public $default_reference = '';
		
		public $label = 'State List';
		
		private $associated_cache_keys = array(
			'state_list',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, eidt, view, delete) will be available in access control
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
			'utility_buttons' => array(
				'comments' => 'comments',
				'share' => 'share',
				'attach_file' => 'attach_file',
				'tags' => 'tags',
				'view_details' => 'view_details',
				'revision_history' => 'revision_history',
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
			
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/state_list.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/state_list.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function state_list(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					return $this->_display_notification( array( "type" => 'no_language', "message" => 'no language file' ) );
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
			case 'delete_from_popup2':
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_frontend_nsr':
			case 'display_all_records_frontend_ssr':
			case 'display_all_records_frontend':
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
			case 'get_state_list':
				$returned_value = $this->_get_state_list();
			break;
			case 'get_states_in_a_country':
			case 'get_states_in_a_country_option_list':
				$returned_value = $this->_get_states_in_a_country();
			break;
			case 'get_all_states':
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
				$this->_get_state_list();
			break;
			case 'get_record':
				$returned_value = $this->_get_record();
			break;
			case 'get_state_data_for_mobile':
				$returned_value = $this->_get_state_data_for_mobile();
			break;
			}
			
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _get_state_data_for_mobile(){
			$state_data = array();
			
			if( isset( $this->class_settings["state"] ) && $this->class_settings["state"] ){
				//get lgas in state
				unset( $_GET["source"] );
				
				$key = 'state';
				$_GET[ $key ] = $this->class_settings["state"];
				
				$tb = 'lga_list';
				$tbc = "c" . ucwords( $tb );
				
				$n = new $tbc();
				$n->class_settings = $this->class_settings;
				$n->class_settings["action_to_perform"] = 'get_select2';
				$state_data["lga"] = $n->$tb();
				
				$tb = 'ward_list';
				$tbc = "c" . ucwords( $tb );
				
				$n = new $tbc();
				$n->class_settings = $this->class_settings;
				$n->class_settings["action_to_perform"] = 'get_select2';
				$n->class_settings["select_fields"] = array( 'lga' => 1 );
				$state_data["ward"] = $n->$tb();
				
				$tb = 'community_list';
				$tbc = "c" . ucwords( $tb );
				
				$n = new $tbc();
				$n->class_settings = $this->class_settings;
				$n->class_settings["action_to_perform"] = 'get_select2';
				$n->class_settings["select_fields"] = array( 'lga' => 1, 'ward' => 1 );
				$state_data["community"] = $n->$tb();
				
				unset( $_GET[ $key ] );
			}
			return $state_data;
		}
		
		protected function _get_select2(){
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'get_all_states':
				$this->class_settings[ 'return_indexed' ] = 1;
			break;
			}

			$this->class_settings[ 'get_select2_option_type' ] = 'state_list';
			return $this->_get_default_select2();
		}
        
		protected function _search_form2(){
			$where = "";
			
			$key = 'country';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			/* 
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
			 */
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
				case "country":
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
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
		}
		
		protected function _save_app_changes2(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//check for project details & update

				$GLOBALS[ 'table_fields' ] = $this->table_fields;
				
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
			$this->class_settings["skip_link"] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
			break;
			}
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
        private function _get_states_in_a_country(){
            $return = array();
            if( isset( $_GET['record_id'] ) && $_GET['record_id'] ){
                $this->class_settings['country'] = $_GET['record_id'];
                $states = $this->_get_state_list();
                
                if( is_array($states) ){
                    switch ( $this->class_settings['action_to_perform'] ){
                    case 'get_states_in_a_country_option_list':
                        //$states['all'] = array( 'state' => 'Nationwide', 'state_id' => 'all' );
                        
                        $script_compiler = new cScript_compiler();
                        $script_compiler->class_settings = $this->class_settings;
                        
                        $script_compiler->class_settings[ 'data' ] = array(
                            'states' => $states,
                        );
                        $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/get-states-in-a-country-option-list.php' );
                        $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                        $return['html'] = $script_compiler->script_compiler();
                        $return['status'] = 'got-states-in-a-country-option-list';
                        
						$return["do_not_reload_table"] = 1;
						$return["status"] = "new-status";
						$return["html_replacement"] = $return["html"];
						$return["html_replacement_selector"] = "select.state-select-to-city-field";
						$return["javascript_functions"] = array("activate_state_select_field");
						unset( $return["html"] );
					
                    break;
                    case 'get_states_in_a_country':
                        foreach( $states as $id => $val ){
                            if( isset( $val['state'] ) )
                                $return[ $id ] = $val['state'];
                        }
                    break;
                    }
                    
                    return $return;
                }
            }
            //Unverified Account / Missing Record
            $err = new cError(000021);
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
            $err->additional_details_of_error = 'No States / Provinces were found for the selected country';
            $return  = $err->error();
            
            $return['status'] = 'new-status';
            unset( $return['html'] );
			$return["javascript_functions"] = array("activate_no_state_select_field");
            return $return;
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
        
		private function _get_state_list(){
			
			if( ! ( isset( $this->class_settings['country'] ) && $this->class_settings['country'] ) && isset( $_GET['data'] ) && $_GET['data'] ){
                $this->class_settings['country'] = $_GET['data'];
            }
			
            $this->class_settings['country'] = '1157';
			
			if( isset( $this->class_settings['country'] ) && $this->class_settings['country'] ){
				$cache_key = $this->table_name;
				$settings = array(
					'cache_key' => $cache_key . '-' . $this->class_settings['country'],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				$returning_array = array(
					'html' => '',
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'got-state-list',
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
				$select = "";
				
				foreach( $this->table_fields as $key => $val ){
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
				}
				
				//PREPARE FROM DATABASE
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['country']."`='".$this->class_settings['country']."' ";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$all_state_list = execute_sql_query($query_settings);
				
				$state_list = array();
				$state_list_geo = array();
				
				if( is_array( $all_state_list ) && ! empty( $all_state_list ) ){
					
					foreach( $all_state_list as $category ){
						$state_list[ $category['id'] ] = $category;
						if( $category['geo_ip'] )$state_list_geo[ strtolower($category['geo_ip']) ] = $category;
					}
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$this->class_settings['country'],
						'cache_values' => $state_list,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					/*
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-geo-ip-'.$this->class_settings['country'],
						'cache_values' => $state_list_geo,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					*/
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					return $state_list;
				}
			}
		}
		
	}
	
	function state_list(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/state_list.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/state_list.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				if( isset( $return[ "fields" ] ) ){
				
					$key = $return[ "fields" ][ "country" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'country_list',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=country_list&todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					
					$key = $return[ "fields" ][ "geo_zone" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'banks',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=banks&todo=get_select2&hide_type=1&type=geo_political_zone" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
				}
				
				return $return[ "labels" ];
			}
		}
	}
?>
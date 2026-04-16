<?php
	/**
	 * Functions Class
	 *
	 * @used in  				Functions Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	functions
	 */

	/*
	|--------------------------------------------------------------------------
	| Functions Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cFunctions{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'functions';
		
		public $table_fields = array(
			'function_name' => 'functions001',
			'module_name' => 'functions002',
			'function_action' => 'functions003',
			'function_class' => 'functions004',
			/* 
			'tooltip' => 'functions005',
			'help' => 'functions006',
			'source' => 'functions007', */
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
		
		function functions(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
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
				
				//Add ID of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'get_functions':
				$returned_value = $this->_get_functions();
			break;
			case 'rebuild_custom_menu':
			case 'rebuild_backend_sub_menu':
			case 'rebuild_backend_menu':
			case 'rebuild_stores_submenu':
			case 'rebuild_hospital_othermenu':
			case 'rebuild_hospital_submenu':
			case 'rebuild_hospital_menu':
				$returned_value = $this->_rebuild_hospital_menu();
			break;
			}
			
			return $returned_value;
		}
		
		private function _rebuild_hospital_menu(){
			set_time_limit(0);
			
			$e = array();
			$prefix = '';
			$module_names_array = array();
			$module_name = '';
			$skip_actions = 0;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'rebuild_hospital_submenu':
				if( function_exists("hospital_version2_submenu") ){
					$e = hospital_version2_submenu();
					$e1 = hospital_version2_menu_settings();
					$prefix = $e1["prefix"];
					$module_name = $e1["module_name"];
				}
			break;
			case 'rebuild_hospital_menu':
				if( function_exists("hospital_version2_menu") ){
					$e = hospital_version2_menu();
					$e1 = hospital_version2_menu_settings();
					$prefix = $e1["main_prefix"];
					$module_name = $e1["main_module_name"];
				}
			break;
			case 'rebuild_hospital_othermenu':
				if( function_exists("hospital_version2_othermenu") ){
					$e = hospital_version2_othermenu();
					$e1 = hospital_version2_menu_settings();
					$prefix = $e1["prefix2"];
					$module_name = $e1["module_name2"];
				}
			break;
			case 'rebuild_backend_sub_menu':
				$module_names_array = get_app_modules();
				
				$prefix = get_app_modules_full_prefix() . 'sub';
				$e = get_app_sub_modules_full();
			break;
			case 'rebuild_backend_menu':
				$skip_actions = 1;
				if( function_exists("get_app_modules_full") ){
					$e = get_app_modules_full();
					$prefix = get_app_modules_full_prefix();
					$module_name = 'MODULES';
					
					if( ! empty( $e ) ){
						$line_items = array();
						
						foreach( $e as $key => $eval ){
							
							$line_items[] = array(
								"new_id" => md5( $prefix . $key ),
								"module_name" => $eval["title"],
							);
						}
						
						$m = new cModules();
						$m->class_settings = $this->class_settings;
						$m->class_settings["action_to_perform"] = "save_line_items";
						$m->class_settings["line_items"] = $line_items;
						
						$query = "TRUNCATE `" . $this->class_settings['database_name'] . "`.`".$m->table_name."` ";
				
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'EXECUTE',
							'set_memcache' => 0,
							'tables' => array( $m->table_name ),
						);
						execute_sql_query($query_settings);
						
						$m->modules();
					}
				}
			break;
			case 'rebuild_custom_menu':
				
				switch( get_package_option() ){
				case "catholic":
				
					if( function_exists("catholic_version2_menu") ){
						
						$e1 = catholic_version2_menu_settings();
						
						if( isset( $_GET["type"] ) ){
							switch( $_GET["type"] ){
							case "menu":
								$e = array_merge( catholic_version2_menu(), frontend_tabs() );
								
								$prefix = $e1["main_prefix"];
								$module_name = $e1["main_module_name"];
							break;
							case "submenu":
								$e = catholic_version2_submenu();
								
								$prefix = $e1["prefix"];
								$module_name = $e1["module_name"];
							break;
							}
						}
						
					}
					
				break;
				}
				
			break;
			case 'rebuild_stores_submenu':
				
				$sel_store = '';
				if( isset( $this->class_settings["selected_store"] ) && $this->class_settings["selected_store"] ){
					$sel_store = $this->class_settings["selected_store"];
				}
				
				$store = new cStores();
				$store->class_settings = $this->class_settings;
				$store->class_settings["selected_store"] = $sel_store;
				$store->class_settings["action_to_perform"] = 'get_all_stores';
				$all_stores = $store->stores();
				
				$module_name = $store->table_name;
				$prefix = $store->table_name;
				
				$e = array();
				
				if( is_array( $all_stores ) && ! empty( $all_stores ) ){
					foreach( $all_stores as $sval ){
						$e[ $sval["id"] ] = array(
							"new_id" => $sval["id"],
							"title" => $sval["name"],
							"class" => $prefix,
							"action" => $sval["id"],
						);
					}
				}
			break;
			}
			
			if( ! empty( $e ) ){
				$ids = array();
				
				$array_of_dataset = array();
			
				$new_record_id = '';
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				if( ! isset( $this->class_settings[ 'user_id' ] ) ){
					$this->class_settings[ 'user_id' ] = 'system';
				}
				
				foreach( $e as $key => $eval ){
					$id = md5( $prefix . $key );
					
					if( isset( $eval["new_id"] ) ){
						$id = $eval["new_id"];
					}
					
					$ids[] = "'". $id ."'";
					
					$mn = $module_name;
					
					$title = strip_tags( $eval["title"] );
					
					if( isset( $eval["prefix"] ) && $eval["prefix"] ){
						$title = $eval["prefix"] . " " . $title;
					}
					
					if( isset( $eval["module_name"] ) && $eval["module_name"] ){
						/*
						if( isset( $module_names_array[ $eval["module_name"] ] ) ){
							$title = $module_names_array[ $eval["module_name"] ] . ' - ' . $title;
						}
						*/
						$mn = $eval["module_name"];
					}
					
					if( isset( $eval["suffix"] ) && $eval["suffix"] ){
						$title .= " " . strtoupper( $eval["suffix"] );
					}
					
					if( $skip_actions ){
						unset( $eval["action"] );
					}
					
					$dataset_to_be_inserted = array(
						'id' => $id,
						'created_role' => $this->class_settings[ 'priv_id' ],
						'created_by' => $this->class_settings[ 'user_id' ],
						'creation_date' => $date,
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
						
						$this->table_fields["function_name"] => strtoupper( $title ),
						$this->table_fields["function_class"] => isset( $eval["class"] )?$eval["class"]:'-',
						$this->table_fields["function_action"] => isset( $eval["action"] )?$eval["action"]:'-',
						$this->table_fields["module_name"] => $mn,
					);
					
					$array_of_dataset[] = $dataset_to_be_inserted;
				}
				
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`id` IN ( ".implode( "," , $ids )." ) ";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
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
					
					return $this->_refresh_cache();
				}
				
				
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = '<h4>Oops, Sorry we experienced an Error! Please reload your browser and try again</h4>';
			return $err->error();
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$show_hospital = 0;
			
			switch( get_package_option() ){
			case "hospital":
			case "gynae":
				$show_hospital = 1;
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
			$this->class_settings[ 'data' ][ "hospital" ] = $show_hospital;
			$this->datatable_settings[ "custom_edit_button" ] = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Functions List";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( '$nwProcessor.recreateDataTables', '$nwProcessor.set_function_click_event', '$nwProcessor.update_column_view_state', 'prepare_new_record_form_new' ) 
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
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
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
			
			if( isset( $returning_html_data["deleted_records"] ) && is_array( $returning_html_data["deleted_records"] ) && ! empty( $returning_html_data["deleted_records"] ) ){
				//delete cache keys
				foreach( $returning_html_data["deleted_records"] as $val ){
					$cache_key = $this->table_name;
					$settings = array(
						'cache_key' => $cache_key."-".$val,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$file = get_cache_for_special_values( $settings );
					clear_cache_for_special_values( $settings );
					if( isset( $file["id"] ) && isset( $file["function_action"] ) && isset( $file["function_class"] ) ){
						$settings = array(
							'cache_key' => $cache_key."-".$file['function_action']."-".$file['function_class'],
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						clear_cache_for_special_values( $settings );
					}
				}
			}
			$this->_refresh_cache();
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			$process_handler->class_settings["datatables_settings"] = $this->datatable_settings;
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'display_data_table';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data[ 'method_executed' ] = $this->class_settings['action_to_perform'];
			$returning_html_data[ 'status' ] = 'display-datatable';
			$returning_html_data[ 'message' ] = 'Returned form data table';
			
			return $returning_html_data;
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ) ){
				if( isset( $_POST[ $this->table_fields["function_name"] ] ) && $_POST[ $this->table_fields["function_name"] ] ){
					$_POST["tmp"] = strtolower( clean3( $_POST[ $this->table_fields["function_name"] ] ) );
				}
			}
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					$this->class_settings[ 'current_record_id' ] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->_get_functions();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_functions(){
			
			$cache_key = $this->table_name;
			$modules = array();
			
			$where = "";
			
			if( isset( $this->class_settings[ 'where' ] ) && $this->class_settings[ 'where' ] ){
				$where = $this->class_settings[ 'where' ];
			}
			
			if( isset( $this->class_settings[ 'current_record_id' ] ) && $this->class_settings[ 'current_record_id' ] ){
				
				$settings = array(
					'cache_key' => $cache_key."-".$this->class_settings[ 'current_record_id' ],
					'directory_name' => $cache_key,
					'permanent' => true,
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
				
				
				$where = " where `record_status`='1' AND `id`='".$this->class_settings[ 'current_record_id' ]."' ";
			}	
			
			if( ! $where )return 0;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `".$val."` as '".$key."'";
			}
				
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` " . $where;
			//return $query;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_modules = execute_sql_query($query_settings);
			
			if( isset( $this->class_settings["return_data"] ) && $this->class_settings["return_data"] ){
				return $all_modules;
			}
			
			$fsettings = array(
				'cache_key' => $cache_key."-all-functions",
				'permanent' => true,
			);
			$all_functions = get_cache_for_special_values( $fsettings );
			
			if( ! is_array( $all_functions ) )$all_functions = array();
			
			$m = get_modules_in_application();
			
			if( is_array( $all_modules ) && ! empty( $all_modules ) ){
				
				foreach( $all_modules as $category ){
					$all_functions[ $category['id'] ] = ( isset( $m[ $category["module_name"] ] )?( $m[ $category["module_name"] ] . " - " ):"" ) . $category["function_name"];
					//$modules[ $category['id'] ] = $category;
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-".$category['id'],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-".$category['function_action']."-".$category['function_class'],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
				}
				
				if( ! empty( $all_functions ) ){
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-all-functions",
						'cache_values' => $all_functions,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
				}
				
			}
			
			return $modules;
			
		}
		
		private function _refresh_cache(){
			//recreate functions of stores
			
			//recreate functions of bank accounts
			
			
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->class_settings[ 'where' ] = " WHERE `record_status` = '1' ";
			unset( $this->class_settings[ 'current_record_id' ] );
			
			$test = $this->_get_functions();
			
			if( isset( $test ) && is_array( $test ) ){
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Function List for Access Control has been rebuilt</h4>';
				return $err->error();
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = '<h4>Oops, Sorry we experienced an Error! Please reload your browser and try again</h4>';
			return $err->error();
		}
	}
?>
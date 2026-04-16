<?php
	/**
	 * empowerment Class
	 *
	 * @used in  				empowerment Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	empowerment
	 */
	
	class cDatabase_fields extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = 'database_table';
		public $table_name = 'database_fields';
		
		public $default_reference = 'table_name';
		
		public $label = 'Database Fields';
		
		private $associated_cache_keys = array(
			'database_fields',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 0,
			'exclude_from_crud' => array( 'delete' => 1 ),
			'more_actions' => array(
				'actions' => array(
					'title' => 'More Actions',
					'data' => array(
						'df1' => array(
							'uts' => array(
								'todo' => 'upgrade_table_structure',
								'title' => 'Upgrade Table Structure',
								'text' => 'Upgrade Table Structure&nbsp;',
							),
							'raf' => array(
								'multiple' => 1,
								'todo' => 'refresh_all_fields',
								'title' => 'Refresh All Table Fields',
								'text' => 'Refresh All Table Fields',
							),
						),
					),
				),
			),
		);
		
		public $table_fields = array(
			'table_name' => 'database_fields001',
			'serial_number' => 'database_fields002',
			'field_type' => 'database_fields003',
			'filed_length' => 'database_fields004',
			'field_label' => 'database_fields005',
			
			'display_field_label' => 'database_fields019',
			//'abbreviation' => 'database_fields020',
			
			'form_field' => 'database_fields006',
			
			'form_field_options' => 'database_fields008',
			'required_field' => 'database_fields007',
			
			'data' => 'database_fields009',
			'database_objects' => 'database_fields010',
			
			'attributes' => 'database_fields013',
			'class' => 'database_fields014',
			'default_appearance_in_table_fields' => 'database_fields016',
			'display_position' => 'database_fields015',
			
			'acceptable_files_format' => 'database_fields018',
			'field_id' => 'database_fields021',
			'group' => 'database_fields017',
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
	
		function database_fields(){
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
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes2();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_view':
				$returned_value = $this->_display_app_view();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_task':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
			break;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "save_line_items":
				$returned_value = $this->_save_line_items();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "refresh_cache":
				$returned_value = $this->_refresh_cache();
			break;
			case "refresh_all_fields":
			case "upgrade_table_structure":
				$returned_value = $this->_refresh_table_fields();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		private $data_options = array( 'access_control', 'form_field_options_source', 'note', 'api_note' );
		
		protected function _before_generate_form(){
			$this->class_settings["hidden_records"][ $this->table_fields["data"] ] = 1;
			
			$this->class_settings["attributes"]["custom_form_fields"] = array(
				"field_ids" => $this->data_options,
				"form_label" => array(
					'access_control' => array(
						'field_label' => 'Access Control',
						'form_field' => 'multi-select',
						'class' => ' select2 ',
						'form_field_options' => 'get_form_field_access_control',
						
						'display_position' => 'display-in-table-row',
						'serial_number' => '',
					),
					'form_field_options_source' => array(
						'field_label' => 'Form Fields Option Source',
						'form_field' => 'select',
						'form_field_options' => 'get_form_field_options_source',
						
						'display_position' => 'display-in-table-row',
						'serial_number' => '',
					),
					'note' => array(
						'field_label' => 'Notes',
						'form_field' => 'textarea',
						
						'display_position' => 'display-in-table-row',
						'serial_number' => '',
					),
					'api_note' => array(
						'field_label' => 'API Notes',
						'form_field' => 'textarea',
						
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
				
				if( ! ( isset( $e["created_by"] ) && $e["created_by"] == $this->class_settings["user_id"] ) ){
					if( ! get_hyella_development_mode() ){
						$this->class_settings[ "form_display_not_editable_value" ][ $this->table_fields["field_label"] ] = 1;
						$this->class_settings[ "form_display_not_editable_value" ][ $this->table_fields["table_name"] ] = 1;
					}
				}
			}
		}
		
		protected function _generate_new_data_capture_form2(){
			$this->_before_generate_form();

			return $this->_generate_new_data_capture_form();
		}
		
		protected function _refresh_table_fields(){
			if( ! ( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ) ){
				return $this->_display_notification( array( 'type' => 'error', 'message' => '<h4>Invalid Field ID</h4>Please select a field record first' ) );
			}

			$this->class_settings[ 'current_record_id' ] = $_POST[ 'id' ];
			$d = $this->_get_record();

			if( isset( $d[ 'table_name' ] ) && $d[ 'table_name' ] ){
				$_POST[ 'id' ] = $d[ 'table_name' ];

				$dt = new cDatabase_table();
				$dt->class_settings = $this->class_settings;

				return $dt->database_table();
			}
		}
		
		protected function _search_form2(){
			$where = "";
			
			$key = 'form_field';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'table_name';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}
		
		protected function __after_save_changes_and_refresh( $r = array() ){
			if( isset( $r['new_record_created'] ) && $r['new_record_created'] && isset( $r['record']['table_name'] ) && $r['record']['table_name'] ){
				$dbt = new cDatabase_table();
				$dbt->class_settings = $this->class_settings;
				$dbt->class_settings["current_record_id"] = $r['record']['table_name'];
				$t = $dbt->_get_record();
				
				if( isset( $t["table_name"] ) && $t["table_name"] ){
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_name."`.`".$this->table_fields["field_id"]."` = concat( '". $t["table_name"] ."', `".$this->table_name."`.`serial_num` ) WHERE `".$this->table_name."`.`id` = '". $r['saved_record_id'] ."' ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query( $query_settings );
				}
			}
		}
		
		protected function _save_changes2(){
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
			
			$return = $this->_save_changes();
			//print_r( $return["record"] ); exit;
			if( isset( $return["record"][ "id" ] ) && $return["record"][ "id" ] ){
				//update json file if exists
				$table_d = get_record_details( array( "id" => $return["record"][ "table_name" ], "table" => "database_table" ) );
				if( isset( $table_d["table_name"] ) && $table_d["table_name"] ){
					
					$table_package = isset( $table_d["table_package"] )?$table_d["table_package"]:'';		
					$package_path = '';
					
					if( $table_package ){
						$package_path = 'package/'.$table_package.'/';
					}
					
					$file = $this->class_settings["calling_page"] . "classes/". $package_path ."dependencies/" . $table_d["table_name"] . ".json";
					
					if( file_exists( $file ) ){
						$table_data = json_decode( file_get_contents( $file ), true );
						
						$field_key = strtolower( $table_d["table_name"] . $return["record"][ "serial_num" ] );
						if( isset( $return["record"][ 'field_id' ] ) && $return["record"][ 'field_id' ] && $return["record"][ 'field_id' ] !== 'undefined' ){
							$field_key = $return["record"][ 'field_id' ];
						}
						
						$table_data[ "fields" ][ clean3( strtolower( $return["record"][ 'field_label' ] ) ) ] = $field_key;
						
						if( isset( $return["record"]["display_field_label"] ) && $return["record"]["display_field_label"] && $return["record"]["display_field_label"] != "undefined" ){
							$return["record"][ 'field_label' ] = $return["record"]["display_field_label"];
						}
						
						if( isset( $return["record"]["data"] ) && $return["record"]["data"] ){
							$fx = json_decode( $return["record"]["data"], true );
							if( is_array( $fx ) ){
								$return["record"] = array_merge( $return["record"], $fx );
							}
						}
						
						$table_data[ "labels" ][ $field_key ] = $return["record"];
						
						file_put_contents( $file, json_encode( $table_data ) );
					}
				}
			}
			
			return $return;
		}
		
		
		protected function _display_all_records_full_view2(){
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_all_records_frontend':
			break;
			default:
				$this->class_settings[ "return_new_format" ] = 1;
			break;
			}
			
			$development = get_hyella_development_mode();
			if( ! $development ){
				$this->datatable_settings["show_delete_button"] = 0;
			}
			
			$this->class_settings[ "show_form" ] = 1;
			$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
			$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
			$this->class_settings[ 'add_empty_select_option' ] = 1;

			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "form_field":
				case "table_name":
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
				break;
				default:
					$this->class_settings["hidden_records"][$val] = 1;
				break;
				}
			}
			return $this->_display_all_records_full_view();
		}
	
	
	}
?>
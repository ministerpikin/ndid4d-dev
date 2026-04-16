<?php
/**
 * json_options Class
 *
 * @used in  				json_options Function
 * @created  				01:40 | 26-Sep-2017
 * @database table name   	json_options
 */
	
	class cJson_options extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		private $current_table = '';
		
		public $table_name = 'json_options';
		
		public $label = '';
		
		private $associated_cache_keys = array(
			'json_options',
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
			$error_msg = '<h4>Failed to Initialize Control</h4><p>Ensure that the option type was passed correctly</p>';
			
			if( ( isset( $_GET["option_table"] ) && $_GET["option_table"] ) && ( isset( $_GET["reference_id"] ) && $_GET["reference_id"] ) && ( isset( $_GET["reference_table"] ) && $_GET["reference_table"] ) ){
				$this->table_name = $_GET["option_table"];
				$this->current_record_id = $_GET["reference_id"];
				$this->current_table = $_GET["reference_table"];
			}
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/'.$this->table_name.'.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/'.$this->table_name.'.json' ), true );
				
				if( isset( $return[ "fields" ] ) ){
					$this->table_fields = $return[ "fields" ];
					
					$fields = array();
					foreach( $this->table_fields as $key => $field ){
						$fields[] = $field;
						$this->table_fields_reverse[ $field ] = $key;
					}
					$GLOBALS["table_fields"] = $fields;
			
				}else{
					$error_msg = '<h4>Failed to Initialize Control</h4><p>Could not retrieve option fields</p>';
				}
				
				if( isset( $return[ "labels" ] ) ){
					$this->table_labels = $return[ "labels" ];
					$GLOBALS["table_labels"] = $this->table_labels;
				}else{
					$error_msg = '<h4>Failed to Initialize Control</h4><p>Could not retrieve option labels</p>';
				}
				
				if( isset( $return[ "table" ] ) ){
					$this->table_data = $return[ "table" ];
					$this->label = $this->table_data["table_label"];
				}else{
					$error_msg = '<h4>Failed to Initialize Control</h4><p>Could not retrieve option table data</p>';
				}
			}else{
				$error_msg = '<h4>Failed to Initialize Control</h4><p>Could not retrieve options data</p>';
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = 'construct';
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
		}
	
		function json_options(){
			//LOAD LANGUAGE FILE
			/*
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
			*/
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings['table_temp'] = 'json_options';
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
			case 'save_json_option':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_i_menu':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
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
			$error_msg = '';
			
			if( $this->current_record_id && $this->current_table ){
				$this->class_settings['return_form_data_only'] = 1;
				$return = $this->_save_changes();
				
				if( isset( $return["id"] ) && $return["id"] && isset( $return["form_data"] ) && is_array( $return["form_data"] ) && ! empty( $return["form_data"] ) ){
					$data = array();
					$data[ "id" ] = $return["id"];
					
					foreach( $return["form_data"] as $key => $sval ){
						if( isset( $sval["value"] ) && isset( $this->table_fields_reverse[ $key ] ) ){
							$data[ $this->table_fields_reverse[ $key ] ] = $sval["value"];
						}
					}
					
					$this->_clear_globals();
					$_POST["id"] = $this->current_record_id;
					
					$table = $this->current_table;
					$rt = "c" . ucwords( $table );
					$class = new $rt();
					$class->class_settings = $this->class_settings;
					$class->class_settings["update_fields"] = array();
					$class->class_settings["update_fields"][ $this->table_name ] = json_encode( $data );
					
					$class->class_settings["action_to_perform"] = "update_table_field";
					$return = $class->$table();
					
					if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
						$return["html_replacement"] = $return["html"];
						$return["html_replacement_selector"] = "#modal-replacement-handle";
						
						unset( $return["html"] );
						$return["status"] = "new-status";
						
					}
					return $return;
				}else{
					return $return;
					//$error_msg = '<h4>Data Transformation Failed</h4><p>Could not retrieve reference info</p>';
				}
			}else{
				$error_msg = '<h4>Invalid Reference</h4><p>Could not retrieve reference info</p>';
			}
			
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = 'construct';
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
			
			return $return;
		}
		
		protected function _clear_globals(){
			if( isset( $GLOBALS["table_labels"] ) )unset( $GLOBALS["table_labels"] );
			if( isset( $GLOBALS["table_fields"] ) )unset( $GLOBALS["table_fields"] );
			unset( $this->class_settings['table_temp'] );
			unset( $this->class_settings['return_form_data_only'] );
		}
		
		protected function _new_popup_form2(){
			$this->class_settings["skip_link"] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				$this->class_settings[ "hidden_records" ][ $this->table_fields["customer"] ] = 1;
			break;
			default:
			break;
			}
			
			$r = get_record_details( array( "id" => $this->current_record_id, "table" => $this->current_table ) );
			
			if( isset( $r[ $this->table_name ] ) && $r[ $this->table_name ] ){
				$r1 = json_decode( $r[ $this->table_name ], true );
				
				if( isset( $r1["id"] ) && $r1["id"] ){
					$_POST["id"] = $r1["id"];
					
					foreach( $this->table_fields as $key => $val ){
						if( isset( $r1[ $key ] ) )$this->class_settings[ "form_values_replace" ][ $val ] = $r1[ $key ];
					}
				}
			}
			
			$this->class_settings[ 'form_action' ] = '?action=' . $this->class_settings['table_temp'] . '&todo=save_json_option&option_table='.$this->table_name.'&reference_id='.$this->current_record_id.'&reference_table=' . $this->current_table;
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
	}
	
	function json_options(){
		if( isset( $GLOBALS["table_labels"] ) && is_array( $GLOBALS["table_labels"] ) && $GLOBALS["table_labels"] ){
			return $GLOBALS["table_labels"];
		}
	}
	
?>
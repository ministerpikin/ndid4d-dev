<?php
/**
 * list_box_options Class
 *
 * @used in  				list_box_options Function
 * @created  				07:36 | 20-Dec-2019
 * @database table name   	list_box_options
 */
	
	class cList_box_options extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'list_box_options';
		
		public $default_reference = '';
		
		public $label = 'List Box Options';
		
		private $associated_cache_keys = array(
			'list_box_options',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,
		);
		
		public $table_fields = array();
		
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/list_box_options.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/list_box_options.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function list_box_options(){
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
			case 'modify_list_box2':
			case 'modify_list_box':
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
			case "get_record":
				$returned_value = $this->_get_record();
			break;
			case 'refresh_cache':
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
			/* $this->class_settings[ "show_form" ] = 1;
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
			} */
			$this->class_settings["show_popup_form"] = 1;
			$this->class_settings["full_table"] = 1;
			
			return $this->_display_all_records_full_view();
		}

		protected function _display_app_view2(){
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
		}
		
		protected function _save_app_changes2(){
			$error_msg = '';
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				$options = isset($_POST['options']) && $_POST['options'] ? $_POST['options'] : ( isset($_POST[ $this->table_fields['data'] ]) && $_POST[ $this->table_fields['data'] ] ? $_POST[ $this->table_fields['data'] ]  : [] );
				if( $options ){
					
					if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
						$_POST["tmp"] = md5( $_POST[ $this->table_fields["key"] ] );
					}
					
					$options = explode( ";", $options );
					$list = array();
					
					if( is_array( $options ) && ! empty( $options ) ){
						foreach( $options as $ovf ){
							
							$o2 = explode(":", $ovf );
							if( isset( $o2[0] ) && isset( $o2[1] ) ){
								// $key = strtolower( trim( $o2[0] ) );
								$key = trim( $o2[0] );
								
								if( $key ){
									$list[ $key ] = trim( $o2[1] );
								}
							}
						}
					}
					
					if( empty( $list ) ){
						$error_msg = 'Invalid Options';
					}else{
						$dlist = array();
						
						if( isset( $_POST["sort"] ) && $_POST["sort"] ){
							$dlist[ "sort" ] = $_POST["sort"];
							
							if( $dlist[ "sort" ] == 'yes' ){
								asort( $list );
							}
						}
						
						$dlist[ "options" ] = $list;
						
						$_POST[ $this->table_fields["data"] ] = json_encode( $dlist );
					}
					
				}else{
					$error_msg = 'Invalid Options';
				}
				
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => 'error', "message" => $error_msg ) );
			}
			
			//$return = $this->_save_changes();
			$this->class_settings["html_filename_full"] = array( 'html-files/templates-1/'.$this->table_name.'/view-list.php' );
			
			$return = $this->_save_app_changes();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$this->class_settings["skip_link"] = 1;
			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_formX':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
			break;
			}
			
			switch( $this->class_settings['action_to_perform'] ){
			case "modify_list_box2":
				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
					$this->class_settings[ 'data' ][ 'function' ] = $_POST[ 'id' ];
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/show_function.php' );
					$this->class_settings[ 'modal_title' ] = "Function Options";

					return $this->_launch_popup( $this->_get_html_view(), '#'.$handle, array( 'set_function_click_event', 'prepare_new_record_form_new' ) );
				}else{
					return $this->_display_notification( array( "type" => 'error', "message" => 'No Function Name' ) );
				}
			break;
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
			case 'modify_list_box':
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					$id = $_POST["id"];
					
					switch( $this->class_settings['action_to_perform'] ){
					case 'modify_list_box':
						$this->class_settings["current_record_id"] = md5( $id );
					break;
					default:
						$this->class_settings["current_record_id"] = $id;
					break;
					}
					
					$r = $this->_get_record();
					
					if( isset( $r["id"] ) && $r["id"] ){
						$_POST["id"] = $r["id"];
						$_POST["mod"] = 'edit-'.md5($this->table_name);
						
						$jd = json_decode( $r["data"], true );
						$hl = '';
						
						if( isset( $jd[ "options" ] ) && is_array( $jd[ "options" ] ) && ! empty( $jd[ "options" ] ) ){
							foreach( $jd[ "options" ] as $jdk => $jdv ){
								$hl .= $jdk . " : " . $jdv . ";\n";
							}
						}
						
						if( $hl ){
							$this->class_settings["form_values_important"][ "options" ] = $hl;
						}
					}
					
					$this->class_settings["hidden_records"][ $this->table_fields["data"] ] = 1;
					
					$this->class_settings["form_values_important"][ $this->table_fields["key"] ] = $id;
					$this->class_settings["hidden_records_css"][ $this->table_fields["key"] ] = $id;
					
					$this->class_settings["before_html"] = '<pre>'.$id.'</pre>';
					
					$this->class_settings["attributes"]["custom_form_fields"] = array(
						"field_ids" => array( 'options', 'sort' ),
						"form_label" => array(
							'options' => array(
								'field_label' => 'Options for List',
								'form_field' => 'textarea',
								
								'display_position' => 'display-in-table-row',
								'placeholder' => "key1 : Value 1;\nkey2 : Value 2;",
								'note' => 'Enter each option on a seperate line. In the following format<br />key1 : Value 1;',
								'serial_number' => '',
							),
							'sort' => array(
								'field_label' => 'Sort Options from A - Z',
								'form_field' => 'select',
								'form_field_options' => 'get_no_yes',
								
								'display_position' => 'display-in-table-row',
								'serial_number' => '',
							),
						),
					);
					
					$this->class_settings['action_to_perform'] = 'new_popup_form';
				}else{
					return $this->_display_notification( array( "type" => 'error', "message" => 'No Reference' ) );
				}
				
			break;
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			$this->class_settings["no_modal_callback"] = 1;
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
	}
	
	function list_box_options(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/list_box_options.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/list_box_options.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
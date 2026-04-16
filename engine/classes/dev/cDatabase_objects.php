<?php
/**
 * database_objects Class
 *
 * @used in  				database_objects Function
 * @created  				09:42 | 17-Jul-2019
 * @database table name   	database_objects
 */
	
	class cDatabase_objects extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $use_package = 1;
		public $table_package = 'dev';
		public $customer_source = '';
		public $table_name = 'database_objects';
		
		public $default_reference = '';
		
		public $label = 'Database Objects';
		
		private $associated_cache_keys = array(
			'database_objects',
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
				'show_refresh_cache' => 1,
				
				'utility_buttons' => array(
					//'comments' => 1,
					//'tags' => 1,
					'view_details' => 1,
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/database_objects.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/database_objects.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function database_objects(){
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
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log2();
			break;
			case 'view_details':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'remove_database_object':
			case 'clone_database_object':
				$returned_value = $this->_clone_database_object();
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
		
		private function _clone_database_object(){
			
			$err_code = '010014';
			$msg = '';
			$action_to_perform = $this->class_settings["action_to_perform"];
			$clone_fix = 1;
			
			if( isset( $_POST["current_value"] ) && $_POST["current_value"] && isset( $_POST["data_field"] ) && $_POST["data_field"] && isset( $_POST["data_nfield"] ) && $_POST["data_nfield"] && isset( $_POST["data_nid"] ) && $_POST["data_nid"] ){
				
				$nfield = $_POST["data_nfield"];
				$field = $_POST["data_field"];
				$nid = $_POST["data_nid"];
				
				$handle = $field."-nw-object-container";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = $this->class_settings[ 'html_replacement_selector' ];
				}
				
				$cv = json_decode( $_POST["current_value"], true );
				if( isset( $cv["data"]["fields"][ $nfield ] ) ){
					
					$html = '';
					switch( $action_to_perform ){
					case "remove_database_object":
						if( $clone_fix ){
							$handle = "nw-obj-button-".$handle;
							$html = '&nbsp;';
						}
						unset( $cv["data"]["fields"][ $nfield ] );
					break;
					default:
						$cv["data"]["fields"][ $nfield . '_c1' ] = $cv["data"]["fields"][ $nfield ];
						$cv["data"]["fields"][ $nfield . '_c1' ]["cloned"] = 1;
					break;
					}
					
					if( ! $html ){
						$ax = array();
						$ax["field_id"] = $field;
						$ax["object_ids"] = $nid;
						$ax["values"] = json_encode( $cv );
						$ax["get_form"] = 1;

						if( $clone_fix )$ax["limit_field"] = $nfield;

						$ax["get_form"] = 1;

						$html = get_nw_database_object( $ax );
					}
					// print_r( $html );exit;
					$rkey = 'html_replacement';
					if( $clone_fix ){
						switch( $action_to_perform ){
						case "clone_database_object":
							$rkey = 'html_insertafter';
						break;
						}
						
					}

					return array(
						"status" => "new-status",
						$rkey => $html,
						$rkey."_selector" => "#".$handle,
						// "html_replacement_selector" => "#".$field."-nw-object-container",
						"javascript_functions" => array("prepare_new_record_form_new"),
					);
					
				}else{
					$msg = '<h4>Invalid Data Structure</h4><p>Please try again</p>';
				}
				
			}else{
				$msg = '<h4>Invalid Parameters</h4><p>Please try again</p>';
			}
			
			if( ! $msg ){
				$msg = '<h4>Unknown Error</h4>';
			}
			
			$err = new cError( $err_code );
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			return $err->error();
		}
		
		protected function _get_select2(){
			
			$where = '';
			$limit = '';
			$search_term = '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["object_name"]."` regexp '".$search_term."' ) ";
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
				case "object_name":
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
				break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
				}
			}
			
			$select .= ", concat( `".$this->table_fields["object_name"]."`, ' - ', `serial_num` ) as 'text'";
			
			
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			
			$data1 = $this->_get_all_customer_call_log();
			return array( "items" => $data1, "do_not_reload_table" => 1 );
		}
		
		protected function _view_details2(){
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$this->class_settings["modal_dialog_style"] = " width:60%; ";
			$this->class_settings["html_filename"] = array( 'html-files/templates-1/development/'.$this->table_name.'/view-object.php' );
			
			return $this->_view_details();
		}
		
		protected function __before_save_changes(){
			
			if( isset( $_POST[ $this->table_fields["object_field_source"] ] ) && $_POST[ $this->table_fields["object_field_source"] ] ){
				
				$prefix = "__object_fs_";
				
				$source = $prefix . $_POST[ $this->table_fields["object_field_source"] ];
				
				if( function_exists( $source ) ){
					$_POST[ $this->table_fields["data"] ] = json_encode( $source() );
				}
			}
			
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
			$where = '';
			$disable_btn = 1;
			$spilt_screen = 0;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;
			
			switch( $action_to_perform ){
			case "display_all_records_full_view_search":
			case "display_all_records_frontend_history":
				$show_form = 1;
				unset( $this->class_settings[ "full_table" ] );
			break;
			case "display_all_records_frontend":
				$spilt_screen = 1;
			break;
			case "display_all_records_full_view":
				$show_form = 1;
				$this->class_settings[ 'frontend' ] = 1;
				$disable_btn = 0;
			break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 1;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				unset( $this->basic_data['more_actions'] );
			}
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_history":
				$this->datatable_settings[ "show_edit_button" ] = 1;
				unset( $this->basic_data['more_actions'] );
			break;
			}
			
			if( $show_form ){
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
				$this->class_settings[ 'add_empty_select_option' ] = 1;
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "date":
						$this->class_settings["form_values_important"][ $val ] = date("U");
						$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					case "staff_responsible":
					case "customer":
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					default:
						$this->class_settings["hidden_records"][$val] = 1;
					break;
					}
				}
			}

			if( $spilt_screen ){
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;
				
				$this->datatable_settings["split_screen"] = array();
				
				$this->datatable_settings["datatable_split_screen"] = array(
					'action' => '?action=' . $this->table_name . '&todo=view_details2&no_column=1',
					'col' => 4,
					'content' => get_quick_view_default_message_settings(),
				);
			}
			
			return $this->_display_all_records_full_view();
		}

	}
	
	function database_objects(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/database_objects.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/database_objects.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				$key = $return[ "fields" ][ "object_field_source" ];
				$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
				
				return $return[ "labels" ];
			}
		}
	}
	
	include dirname( __FILE__ ) . "/database_objects/ivf_objects.php";
	include dirname( __FILE__ ) . "/database_objects/operation_objects.php";
	include dirname( __FILE__ ) . "/database_objects/nurse_objects.php";
	include dirname( __FILE__ ) . "/database_objects/consent_forms.php";
	//include dirname( __FILE__ ) . "/database_objects/cla_objects.php";
	
	function get_database_object_field_source(){
		$r = array();
		if(function_exists('_ivf_database_object_field_source')){
			$r = array_merge( $r, _ivf_database_object_field_source() );
		}
		if(function_exists('_ivf_consent_database_object_field_source')){
			$r = array_merge( $r, _ivf_consent_database_object_field_source() );
		}
		if(function_exists('_cla_database_object_field_source')){
			$r = array_merge( $r, _cla_database_object_field_source() );
		}
		if(function_exists('_op_database_object_field_source')){
			$r = array_merge( $r, _op_database_object_field_source() );
		}
		if(function_exists('_an_database_object_field_source')){
			$r = array_merge( $r, _an_database_object_field_source() );
		}
		if(function_exists('_cf_database_object_field_source')){
			$r = array_merge( $r, _cf_database_object_field_source() );
		}
		return $r;
	}
?>
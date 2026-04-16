<?php
/**
 * lgas Class
 *
 * @used in  				lgas Function
 * @created  				21:56 | 16-Oct-2019
 * @database table name   	lgas
 */
	
	class cLgas extends cBase_class{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $use_package = 1;
		
		public $customer_source = '';
		public $table_name = 'lgas';
		public $table_package = 'mis';
		
		public $default_reference = '';
		
		public $label = 'LGA List';
		
		public $basic_data = array(
			'access_to_crud' => 1,
			'api_custom_fields' => array( 'id' => array( 'custom' => 1 ) ),
			'ignore_access_control' => array( 'lga' => 1 ),
			'transform_imported_data' => array( 
				'primary_field' => 'name', 
				'other_fields' => array( 'state', 'country' ),
			),
			'include_in_export' => array(
				'id' => 'ID',
			),
		);
		
		private $associated_cache_keys = array(
			'lgas',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/lgas.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/lgas.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function lgas(){
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
			case "get_record":
				$returned_value = $this->_get_record();
			break;
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'transform_imported_data':
				$returned_value = $this->_transform_imported_data();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _get_select2(){

			if( isset( $_GET[ 'form_table' ] ) && $_GET[ 'form_table' ] ){
				switch( $_GET[ 'form_table' ] ){
				case 'wag_supervision_checklist':
				case 'disability_report':
				case 'bcc_work_schedule':
				case 'bp_entry_form':				
				case 'wag_livelihood_collectives':
					if( isset( $_POST[ 'state' ] ) && ! $_POST[ 'state' ] ){
						return array( 'items' => array() );
					}
				break;
				}
			}

			$this->class_settings[ 'get_select2_option_type' ] = 'lgas';

			if( ! ( isset( $_GET[ 'no_status' ] ) && $_GET[ 'no_status' ] ) ){
				$_GET[ 'status' ] = 'active';
			}

			return $this->plugin_instance->_get_default_select2( [ 'table_name' => $this->table_name, 'table_fields' => $this->table_fields ] );
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
				case "customer":
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
				
			break;
			}
			
			$return = $this->_save_app_changes();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
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
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["amount_paid"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["payment_method"] ] = 1;
			break;
			}
			
			foreach( array( 'country', 'state' ) as $location ){
				$val = '';
				if( isset( $_GET[ 'enforce_'.$location ] ) && $_GET[ 'enforce_'.$location ] ){
					$val = $_GET[ 'enforce_'.$location ];
				}else if( isset( $_GET[ $location ] ) && $_GET[ $location ] ){
					$val = $_GET[ $location ];

				}
				if( $val ){
					$this->class_settings[ 'form_values_important' ][ $this->table_fields[ $location ] ] = $val;
					$this->class_settings[ 'hidden_records_css' ][ $this->table_fields[ $location ] ] = 1;
				}
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
	}
	
	function lgas(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/lgas.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/lgas.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				if( isset( $return[ "fields" ] ) ){
					
					$key = $return[ "fields" ][ "country" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'countries',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=countries&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' selected-country select2 ';
					
					$key = $return[ "fields" ][ "state" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'states',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=states&nwp_todo=get_select2&source=lgas" data-params=".selected-country" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
				}
				
				return $return[ "labels" ];
			}
		}
	}
?>
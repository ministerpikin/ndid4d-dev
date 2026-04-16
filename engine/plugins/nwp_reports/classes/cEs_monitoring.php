<?php
/**
 * cradle Class
 *
 * @used in  				cradle Function
 * @created  				Hyella Nathan | 19:19 | 03-Jan-2022
 * @database table name   	cradle
 */
	
	class cEs_monitoring extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $db_table_name = 'es_monitoring';
		public $table_name = 'es_monitoring';
		
		public $default_reference = '';
		
		public $label = 'Environmental and Social Monitoring';
		
		private $associated_cache_keys = array(
			'es_monitoring',
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
				'show_refresh_cache' => 0,
				
				'utility_buttons' => array(
					'comments' => 1,
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/es_monitoring.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/es_monitoring.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function es_monitoring(){
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
				$this->class_settings["has_branch"] = 1;
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
			case 'get_departments':
				$returned_value = $this->_get_departments();
			break;
			case 'display_all_records_frontend_branch':
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache2();
			break;
			
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
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
			case 'get_select2_access':
			case 'get_select2':
			case 'get_select2_special':
				$returned_value = $this->_get_select2();
			break;
			case 'get_es_select2':
				$returned_value = $this->_get_select2();
			break;
			}
			
			return $returned_value;
		}

		protected function _get_select2(){
			if (class_exists('cNwp_device_management')) {
				$nwp = new cNwp_device_management();
				$nwp->class_settings = $this->class_settings;
				$tb = "device_mgt";
				$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
				$in[ $tb ]->table_name = $this->table_name;
				$in[ $tb ]->table_fields = $this->table_fields;

				$tbs = $this->table_name;
				$in[ $tb ]->labels = function_exists($tbs) && is_callable($tbs) ? $tbs() : [];
				$in[ $tb ]->class_settings['action_to_perform'] = 'get_es_select3';
				return $in[ $tb ]->_get_es_select3();
			}
		}

		protected function _refresh_cache2( $o = array() ){
			return;
		}
		
		protected function _apply_basic_data_control( $e = array() ){
			/* if( ! isset( $e["status"] ) ){
				return;
			} */
			
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
			
			 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),

			'more_actions' => array(
			),
		);
	}
	
	function es_monitoring(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/es_monitoring.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/es_monitoring.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){
					$key = $return[ "fields" ][ "partner" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_partner',
						'reference_plugin' => 'nwp_enrolment_data',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_partner&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'display_options' ] = array( 'disable_special_value_link' => 1 );

					$key = $return[ "fields" ][ "lga" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'lga';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'lgas',
						'reference_plugin' => 'nwp_locations',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=lgas&nwp_todo=get_select2" data-params=".selected-state" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-lga ';

					$key = $return[ "fields" ][ "residence_lga" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'residence_lga';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'lgas',
						'reference_plugin' => 'nwp_locations',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_reports&todo=execute&nwp_action=es_monitoring&nwp_todo=get_es_select2&field=residence_lga" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'display_options' ] = array( 'disable_special_value_link' => 1 );


					$key = $return[ "fields" ][ "state" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'state';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'states',
						'reference_plugin' => 'nwp_locations',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_reports&todo=execute&nwp_action=es_monitoring&nwp_todo=get_es_select2&field=state" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'display_options' ] = array( 'disable_special_value_link' => 1 );

					$key = $return[ "fields" ][ "ward" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'ward';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'ward',
						'reference_plugin' => 'nwp_locations',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=wards&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'display_options' ] = array( 'disable_special_value_link' => 1 );


					$key = $return[ "fields" ][ "enrollmentusecase" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'enrollmentusecase';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array();
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_reports&todo=execute&nwp_action=es_monitoring&nwp_todo=get_es_select2&field=enrollmentusecase" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'display_options' ] = array( 'disable_special_value_link' => 1 );
					$return[ "labels" ][ $key ][ 'data' ]['do_not_validate_header'] = 1;


					$key = $return[ "fields" ][ "agent" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_agent',
						'reference_keys' => array( 'agentfirstname', 'agentlastname' ), 
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);

					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_agent&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'display_options' ] = array( 'disable_special_value_link' => 1 );
				}
				return $return[ "labels" ];
			}
		}
	}
?>
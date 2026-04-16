<?php
/**
 * device_mgt_sr Class
 *
 * @used in  				device_mgt_sr Function
 * @created  				Bay4 Mike | 19:51 | 10-Nov-2023
 * @database table name   	device_mgt_sr
 */
	if( ! class_exists( 'cDevice_mgt' ) ){
		include dirname( __FILE__ ) . '/cDevice_mgt.php';
	}
	
	class cDevice_mgt_sr extends cDevice_mgt{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'device_mgt_sr';
		
		public $default_reference = '';
		
		public $label = 'Pending Enrolment Devices';
		
		private $associated_cache_keys = array(
			'device_mgt_sr',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/device_mgt.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/device_mgt.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function device_mgt_sr(){
			return $this->device_mgt();
		}			
	}
	
	function device_mgt_sr(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/device_mgt_sr.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/device_mgt_sr.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){

					$key = $return[ "fields" ][ "custodian" ];
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
					
					$key = $return[ "fields" ][ "center" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_center',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_center&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "fep_agent_nin" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'users',
						'reference_keys' => array( 'firstname', 'lastname' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' disabled ';
					
					$key = $return[ "fields" ][ "partner" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_partner',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_partner&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "ward" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'ward';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'wards',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=wards&nwp_todo=get_select2" data-params=".selected-lga" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-ward ';
					
					$key = $return[ "fields" ][ "lga" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'lga';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'lgas',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=lgas&nwp_todo=get_select2" data-params=".selected-state" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-lga ';
					
					$key = $return[ "fields" ][ "state" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'state';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'states',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=states&nwp_todo=get_select2" data-params=".selected-country" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-state ';
					
					$key = $return[ "fields" ][ "country" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'country';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'countries',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=countries&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-country ';
					
				}
				return $return[ "labels" ];
			}
		}
	}
?>
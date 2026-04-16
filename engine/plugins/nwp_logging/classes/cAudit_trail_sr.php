<?php
/**
 * audit_trail Class
 *
 * @used in  				audit_trail Function
 * @created  				Hyella Nathan | 10:33 | 01-Sep-2023
 * @database table name   	audit_trail
 */
	if( ! class_exists("cAudit_trail") ){
		include "cAudit_trail.php";
	}
	
	class cAudit_trail_sr extends cAudit_trail{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'audit_trail_sr';
		
		public $default_reference = '';
		
		public $label = 'Audit Trail - SR';
		
		private $associated_cache_keys = array(
			'audit_trail',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/audit_trail.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/audit_trail.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function audit_trail_sr(){
			return $this->audit_trail();
		}

	}
	
	function audit_trail_sr(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/audit_trail.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/audit_trail.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){

					$key = $return[ "fields" ][ "user" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'users',
						'reference_keys' => array( 'firstname', 'lastname' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'class' ] = 'select2 allow-clear';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' field_key="parent" action="?action=users&todo=get_select2" minlength="2" ';

				}
				return $return[ "labels" ];
			}
		}
	}
?>
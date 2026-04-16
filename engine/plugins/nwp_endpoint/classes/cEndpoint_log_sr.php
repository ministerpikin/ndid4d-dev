<?php
/**
 * endpoint_log_sr Class
 *
 * @used in  				endpoint_log_sr Function
 * @created  				Hyella Nathan | 10:33 | 01-Sep-2023
 * @database table name   	endpoint_log_sr
 */
	if( ! class_exists("cEndpoint_log") ){
		include "cEndpoint_log.php";
	}
	
	class cEndpoint_log_sr extends cEndpoint_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'endpoint_log_sr';
		
		public $default_reference = '';
		
		public $label = 'Endpoint Log - SR';
		
		private $associated_cache_keys = array(
			'endpoint_log',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			$this->basic_data = [];
		}
	
		function endpoint_log_sr(){
			return $this->endpoint_log();
		}
	}
	
	function endpoint_log_sr(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/endpoint_log.json' ), true );
			return $return[ "labels" ];
		}
	}
?>
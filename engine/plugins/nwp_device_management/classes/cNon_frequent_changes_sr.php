<?php
/**
 * non_frequent_changes_sr Class
 *
 * @used in  				non_frequent_changes_sr Function
 * @created  				Bay4 Mike | 19:51 | 10-Nov-2023
 * @database table name   	non_frequent_changes_sr
 */
	if( ! class_exists( 'cNon_frequent_changes' ) ){
		include dirname( __FILE__ ) . '/cNon_frequent_changes.php';
	}
	
	class cNon_frequent_changes_sr extends cNon_frequent_changes{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'non_frequent_changes_sr';
		
		public $default_reference = '';
		
		public $label = 'Non-Frequent Changes - SR';
		
		private $associated_cache_keys = array(
			'non_frequent_changes_sr',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/non_frequent_changes.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/non_frequent_changes.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function non_frequent_changes_sr(){
			return $this->non_frequent_changes();
		}
			
		public $basic_data = array();
	}
	
	function non_frequent_changes_sr(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/non_frequent_changes.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/non_frequent_changes.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
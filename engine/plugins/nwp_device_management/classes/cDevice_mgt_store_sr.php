<?php
/**
 * device_mgt_store_sr Class
 *
 * @used in  				device_mgt_store_sr Function
 * @created  				Bay4 Mike | 19:51 | 10-Nov-2023
 * @database table name   	device_mgt_store_sr
 */
	if( ! class_exists( 'cDevice_mgt_sr' ) ){
		include dirname( __FILE__ ) . '/cDevice_mgt_sr.php';
	}

	if( ! trait_exists( 'DeviceManagementHelpers' ) ){
		include dirname( __FILE__ ) . '/cDevice_mgt_store.php';
	} 
	
	class cDevice_mgt_store_sr extends cDevice_mgt_sr{

		use DeviceManagementHelpers;
		
		public $class_settings = array();
				
		public $customer_source = '';
		public $table_name = 'device_mgt_store_sr';

		public $real_table = 'device_mgt_sr';
		
		public $default_reference = '';
		
		public $label = 'Pending Enrolment Devices - Store';
		
		private $associated_cache_keys = array(
			'device_mgt_store_sr',
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
	
		function device_mgt_store_sr(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
				case 'refresh_cache':
					$returned_value = $this->_refresh_cache2();
				break;
				default:
					$returned_value = $this->device_mgt_sr();
				break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _refresh_cache2() {
			$error_msg = '';
			$e_type = 'error';
			
			try {
				// Initialize endpoint
				$nwp = new cNwp_endpoint;
				$nwp->class_settings = $this->class_settings;
				$tb = 'endpoint';
				$in = $nwp->load_class(['class' => [ $tb ], 'initialize' => 1]);

				$call_filter = isset( $this->class_settings['run_filter'] ) && $this->class_settings['run_filter'] ? $this->class_settings['run_filter'] : [];
				if( !$call_filter ){
					// Build table name
					$edp_table_name = "type=plugin:::key={$this->plugin}:::value={$this->table_name}";

					// Get records
					$in[$tb]->class_settings['where'] = " AND `{$in[$tb]->table_name}`.`{$in[$tb]->table_fields['datatable']}` = '{$edp_table_name}' AND `{$in[$tb]->table_name}`.`{$in[$tb]->table_fields['status']}` = 'active'";
					$in[$tb]->class_settings['order_by'] = " ORDER BY `{$in[$tb]->table_name}`.`modification_date` DESC";
					$as = $in[$tb]->_get_records();
					$as = isset($as[0]) && $as[0] ? $as[0] : [];
					$call_filter = isset($as['last_run']) ? json_decode($as['last_run'], true) : [];					
				}

				
				// Initialize elasticsearch
				$cn = new cNwp_orm;
				$al = $cn->load_class(['class' => ['orm_elasticsearch'], 'initialize' => 1])['orm_elasticsearch'];
				$client = $al::$esClient;
				
				if (cOrm_elasticsearch::$esClientError) {
					throw new Exception(cOrm_elasticsearch::$esClientError);
				}

				// Set elasticsearch params
				$efields = ["serial_num", "creator_role", "created_source", "created_by", "creation_date", 
						"modified_source", "modified_by", "modification_date", "ip_address", "device_id", "record_status", "id"];
				
				$params = [
					'size' => 0,
					'index' => $this->table_name,
					'body' => [
						'aggs' => [
							"unique_dev_ids" => [
								"composite" => [
									"size" => cOrm_elasticsearch::$defaultLimit,
									"sources" => [["dev_id" => ["terms" => ["field" => $this->table_fields['dev_id']]]]]
								],
								"aggs" => [
									"latest_doc" => [
										"top_hits" => [
											"size" => 1,
											"sort" => [["modification_date" => ["order" => "desc"]]],
											"_source" => [
												"includes" => array_merge($efields, array_keys($this->table_fields))
											]
										]
									]
								]
							]
						]
					],
					'track_total_hits' => true
				];

				// Add date filters if available
				if ( $call_filter ) {
					$start_time = isset($call_filter['date_filter']['call_start_time']) ? (int)$call_filter['date_filter']['call_start_time'] : 0;
					$end_time = isset($call_filter['date_filter']['call_end_time']) ? (int)$call_filter['date_filter']['call_end_time'] : 0;
					if ($end_time) {
						$params['body']['query']['range']['modification_date'] = [
							'gte' => $start_time * 1000,
							'lte' => $end_time * 1000
						];
					}
				}

				// Process elasticsearch results
				do {
					$rq = $client->search($params);
					$this->updateQueryMetadata($rq);

					if (!isset($rq['aggregations']['unique_dev_ids']['buckets']) || 
						empty($rq['aggregations']['unique_dev_ids']['buckets'])) {
						break;
					}

					$buckets = $this->processBuckets($rq['aggregations']['unique_dev_ids']['buckets']);
					
					if ($buckets) {
						$this->processAndDumpData($buckets, $in[ $tb ], $this->real_table);
					}

					// Check if we need to continue pagination
					if (count( $buckets) < cOrm_elasticsearch::$defaultLimit || 
						!isset($rq['aggregations']['unique_dev_ids']['after_key'][$this->table_fields['dev_id']])) {
						break;
					}

					$params['body']['aggs']['unique_dev_ids']['composite']['after'] = $rq['aggregations']['unique_dev_ids']['after_key'];

				} while (true);

				return;

			} catch (BadRequest400Exception $e) {
				$error_msg = $e->getMessage();
			} catch (Exception $e) {
				$error_msg = $e->getMessage();
			}

			$error_msg = $error_msg ?: '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
			return $this->_display_notification([
				'type' => $e_type,
				'message' => $error_msg
			]);
		}			
	}
	
	function device_mgt_store_sr(){
		return device_mgt_sr();
	}
?>
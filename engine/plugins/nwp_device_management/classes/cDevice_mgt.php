<?php
/**
 * device_mgt Class
 *
 * @used in  				device_mgt Function
 * @created  				Hyella Nathan | 14:47 | 16-Aug-2023
 * @database table name   	device_mgt
 */
// Turning Point for Mike
	
	class cDevice_mgt extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'device_mgt';
		public $sr_table = 'device_mgt_sr';
		
		public $default_reference = '';
		
		public $label = 'Approved Enrolment Devices';
		
		private $associated_cache_keys = array(
			'device_mgt',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		public $table_clone = array();
		
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
		
		public $end_point1 = '';
		public $app_key = '';
		public $app_id = '';
		public $end_point_dev = ''; //php 8.2 deprecated error
		public $widget_key = ''; //php 8.2 deprecated error
		public $labels = [];
		
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/device_mgt.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/device_mgt.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			if( defined( 'NWP_V3_ENDPOINT' ) && NWP_V3_ENDPOINT ){
				$this->end_point1 = NWP_V3_ENDPOINT;
				$this->end_point_dev = NWP_V3_ENDPOINT;
			}

			$this->app_key = get_websalter();
			$this->widget_key = $this->app_key;
			if( defined( 'HYELLA_V3_TELE_HEALTH_APP_SECRET' ) && constant("HYELLA_V3_TELE_HEALTH_APP_SECRET") ){
				$this->app_key = constant("HYELLA_V3_TELE_HEALTH_APP_SECRET");
			}
			$this->app_key = md5( $this->app_key );

			if( defined( 'HYELLA_V3_TELE_HEALTH_APP_ID' ) && constant("HYELLA_V3_TELE_HEALTH_APP_ID") ){
				$this->app_id = constant("HYELLA_V3_TELE_HEALTH_APP_ID");
			}
		}
	
		function device_mgt(){
			$this->_save_datatable_cache();
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form2();
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
			case 'display_all_records_frontend_history':
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
			case 'view_records_workflow':
			case 'view_records_workflow2':
			case 'display_all_records_frontend_flagged':
			case 'display_all_records_frontend_pending':
				$returned_value = $this->_display_all_records_full_view2();
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
			case 'reactivate_device':
			case 'deactivate_device':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'view_details2':
			case 'view_details':
			case 'control_panel':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache2();
			break;
			case 'save_line_items':
				$returned_value = $this->_save_line_items();
			break;
			case 'get_select2':
				$this->class_settings[ 'name_field' ] = 'model';
				$returned_value = $this->_get_default_select2();
			break;
			case 'gis_map':
			case 'new_device':
			case 'audit_config':
			case 'device_trail':
			case 'get_data_filter':
			case 'view_map_device':
			case 'view_map_devices':
			case 'save_new_device':
			case 'save_audit_config':
			case 'start_trf_workflow':
			case 're_onboard_device':
			case 'assign_enrolment_center_workflow':
			case 'save_assign_enrolment_center_workflow':
				$returned_value = $this->_custom_capture_form_version_2();
			break;
			case 'bg_execute':
				$returned_value = $this->_bg_execute();
			break;
			case 'generate_key':
			case 'generate_cert':
				$returned_value = $this->_kms();
			break;
			case 'create_device_endpoint':
				$returned_value = $this->_create_device_endpoint();
			break;
			case 'fep_email_user_verify':
				$returned_value = $this->_fep_email_user_verify();
			break;
			case 'create_pings':
				$returned_value = $this->_create_pings();
			break;
			case 'after_workflow_completion':
				$returned_value = $this->_after_workflow_completion();
			break;
			case 'get_es_select2':
				$returned_value = $this->_get_es_select2();
			break;
			case 'get_es_select3':
				$returned_value = $this->_get_es_select3();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _refresh_cache2(){
			return;
		}

		// List without ids records in a table
		public function _get_es_select3( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'get_es_select3':
					$field = isset($_GET['field']) && $_GET['field'] ? $_GET['field'] : '';
					if( $field && isset($this->table_fields[ $field ]) && $this->table_fields[ $field ] ){
						$field = $this->table_fields[ $field ];
						$cn = new cNwp_orm;
						$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
						$client = cOrm_elasticsearch::$esClient;
						if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
							$error_msg = cOrm_elasticsearch::$esClientError;
						}else{
							$params = [
								'index' => $this->table_name,
								'body' => [
									'_source' => $field,
									'size' => get_default_select2_limit()
								]
							];

							if( isset($_POST['term']) && $_POST['term'] ){
								$query = [];
								$form_field = isset( $this->labels[ $field ]['form_field'] ) && $this->labels[ $field ]['form_field'] ? $this->labels[ $field ]['form_field'] : 'text';
								switch ($form_field) {
			        				case 'calculated':
			        				case 'text':
										$query = ['wildcard' => [ $field => [ 'value' => '*'. $_POST['term'] .'*', 'case_insensitive' => true ] ] ];
									break;
									default:
										$query = ['term' => [ $field => $_POST['term'] ] ];
									break;
								}
								$params['body']['query'] = $query;
							}

							try{
								$response = $client->search( $params );
								$rqs = [];
								if( isset( $response['hits'][ 'hits' ] ) && $response['hits'][ 'hits'] ){
									$rq = array_unique( array_column( array_column($response['hits']['hits'], '_source'), $field) );
									$c = 0;
									foreach( $rq as $a ){
										if( $a ){
											$c++;
											$rqs[] = array( "id" => $a, "serial_num" => $c, "text" => $a );											
										}
									}
								}

								return array( "items" => $rqs, "do_not_reload_table" => 1 );

							}catch(Throwable $e){
								$error_msg = $e->getMessage();
							}
						}
					}
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		// List with ids
		public function _get_es_select2( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'get_es_select2':
					$field = isset($_GET['field']) && $_GET['field'] ? $_GET['field'] : '';
					$search_fields = isset($_GET['search_fields']) && $_GET['search_fields'] ? explode(',', $_GET['search_fields']) : [];
					if( $field ){
						$search_fields[] = $field;
					}

					if( $search_fields ){
						$search_fields = array_unique( $search_fields );
						foreach ($search_fields as $i => $_field) {
							if( !(isset($this->table_fields[ $_field ]) && $this->table_fields[ $_field ]) ){
								unset( $search_fields[ $i ] );
							}
						}
					}

					if( $search_fields ){

						$cn = new cNwp_orm;
						$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
						$client = cOrm_elasticsearch::$esClient;
						if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
							$error_msg = cOrm_elasticsearch::$esClientError;
						}else{
							$params = [
								'index' => $this->table_name,
								'body' => [
									'_source' => [...$search_fields, 'serial_num'],
									'size' => get_default_select2_limit(),
									'query' => [
										'bool' => [
											'must' => [
												[ 'match' => [ 'record_status' => '1' ] ]
											]
										]
									]
								],
							];

							if( isset($_POST['term']) && $_POST['term'] ){
								$_should = [];
								foreach ($search_fields as $_field) {
									$_should[] = array( 'wildcard' => array( $_field => array( 'value' => '*'. $_POST['term'] .'*', 'case_insensitive' => true ) ) );
								}
								$params['body']['query']['bool']['must'][] = ['bool' => ['should' => $_should]];
							}

							try{
								// exit;
								$response = $client->search( $params );
								$rqs = [];
								$stringify_source = function(array $a){
									$b2 = [];
									foreach ($a as $key => $value) {
										if( !in_array($key, ['serial_num', /*'serial_number'*/]) ){
											$b2[] = $value;
										}
									}
									return implode(' ', $b2);
								};

								if( isset( $response['hits'][ 'hits' ] ) && $response['hits'][ 'hits'] ){
									$c = 0;
									$sid = get_new_id();
									foreach ($response[ 'hits' ][ 'hits' ] as $value) {
										$c++;
										$rqs[] = array( 
											"id" => isset( $value['_id'] ) &&  $value['_id'] ? $value['_id'] : $sid . $c, 
											"serial_num" => isset( $value['_source']['serial_number'] ) && $value['_source']['serial_number'] ? $value['_source']['serial_number'] : ( isset($value['_source']['serial_num']) && $value['_source']['serial_num'] ? $value['_source']['serial_num'] : $c ),
											"text" => $stringify_source( $value['_source'] )
										);											
									}
								}

								return array( "items" => $rqs, "do_not_reload_table" => 1 );

							}catch(Throwable $e){
								$error_msg = $e->getMessage();
							}
						}
					}else{
						$error_msg = "<h4><b>Invalid Search Field</b></h4>";
					}
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		private function _create_pings(){
			$path = dirname( dirname( dirname( dirname(  __FILE__  ) ) ) ) . '/' . DEVICE_PINGS_PATH;

			$body = isset( $_POST[ 'body' ] ) ? json_decode( $_POST[ 'body' ], 1 ) : [];
			if( isset( $body[ 'data' ] ) && $body[ 'data' ] ){
	        	$fname = get_new_id();
	    		$body[ 'data' ][ 'uniqueId' ] = $fname;
	    		$body[ 'data' ] = json_encode( $body[ 'data' ] );

	        	file_put_contents( $path . '/' . $fname . '.json', $body[ 'data' ] );

			}
		}

		private function _fep_email_user_verify(){
			$error = '';
			$etype = 'error';

			// print_r( $_GET );exit;
			if( isset( $_GET[ '_u' ] ) && $_GET[ '_u' ] && isset( $_GET[ '_d' ] ) && $_GET[ '_d' ] ){
				$id = $_GET[ '_d' ];

				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();

				if( isset( $e[ 'status' ] ) && $e[ 'status' ] !== 'pending_nin' ){
					$etype = 'info';
					$error = '<h4><strong>Invalid Status</strong></h4>This device is not Pending NIN Verification.<br>Please contact your system administrator';
				}elseif( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
					$st = 'verified_nin';

					$efd = $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];
					$efd[ 'trail' ][] = [
						'date' => time(),
						'user' => $_GET[ '_u' ],
						'status' => $st,
						'action' => $this->class_settings[ 'action_to_perform' ],
						'note' => 'Successful FEP Agent Email Verification',
					];

					$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `". $this->table_fields[ 'status' ] ."` = '". $st ."', `". $this->table_fields[ 'data' ] ."` = '". json_encode( $efd ) ."' WHERE `".$this->table_name."`.`record_status`='1' AND id = '". $id ."' ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
					
					$this->class_settings[ 'cache_where' ] = " AND id = '". $id ."' ";
					$this->class_settings[ 'current_record_id' ] = 'pass_condition';
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					
					$this->_get_record();

					$error = '<h4><strong>NIN Verified Successfully</strong></h4>';
					$etype = 'success';

					$this->class_settings[ 'action_to_perform' ] = 'create_workflow';
					$this->_create_workflow( [ 'id' => $e[ 'id' ] ] );

				}else{
					$error = '<h4><strong>Invalid Device Reference</strong></h4>Please contact your system administrator';
				}
			}else{
				$error = '<h4><strong>Undefined Device Reference</strong></h4>Please contact your system administrator';
			}

			if( ! $error ){
				$error = '<h4><strong>Unknown Error</strong></h4>Please contact your system administrator';
			}

			$r = $this->_display_notification( array( "type" => $etype, "message" => $error ) );

			return $this->_display_html_only( [ 'html' => $r[ 'html' ] ] );
		}

		public function _create_workflow( $opt = array() ){

			$workflow_settings = isset( $opt[ 'workflow_settings' ] ) ? $opt[ 'workflow_settings' ] : 'wgs26u3454905821508347268';

			$this->class_settings[ 'current_record_id' ] = $opt[ 'id' ];
			$e = $this->_get_record();
			// print_r( $e );exit;

			if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
				$params = [
					'plugin' => $this->plugin,
					'table' => $this->table_name,
					'partner' => isset( $opt[ 'partners' ] ) ? $opt[ 'partners' ] : [ $e[ 'partner' ] ],
					'state' => $e[ 'state' ],
					'country' => $e[ 'country' ],
				];

				$serial = isset( $e[ 'serial_num' ] ) ? $e[ 'serial_num' ] : 0;

				if( $serial ){
					$unique = get_name_of_referenced_record( array( 'reference_number' => 1, 'id' => $e[ 'id' ], 'table' => $this->table_name ) );
				}else{
					$unique = $ef[ 'id' ];
				}

				$name = "<strong>". $unique . '</strong> '. $this->label .' Workflow - ' . date("d-M-Y H:i");

				$description = 'Workflow for '. $this->label . ' - Device ID: ' . $e[ 'dev_id' ];
				$description = isset( $opt[ 'description' ] ) ? $opt[ 'description' ] : $description;
				$comment = isset( $opt[ 'comment' ] ) ? $opt[ 'comment' ] : '';
				
				$_POST[ 'name' ] = $name;
				$_POST[ 'description' ] = $description;
				$_POST[ 'workflow_settings' ] = $workflow_settings;
				$_POST[ 'data' ] = json_encode( $params );
				$_POST[ 'comment' ] = isset( $comment ) && $comment ? $comment : '';

				$nw = new cNwp_workflow;
				$nw->class_settings = $this->class_settings;
				$nw->class_settings[ 'nwp_action' ] = 'workflow';
				$nw->class_settings[ 'nwp_todo' ] = 'create_new_workflow';
				$nw->class_settings[ 'action_to_perform' ] = 'execute';
				$nw->class_settings[ 'do_not_open_workflow' ] = 1;

				$reference_field = "id";

				$add_where = " AND `". $this->table_name ."`.`". $reference_field ."` = '". $e[ 'id' ] ."' ";
				$nw->class_settings[ 'workflow_wheres' ] = $add_where;

				$r = $nw->nwp_workflow();

				$efd = $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];
				$efd[ 'trail' ][] = [
					'date' => time(),
					'user' => $this->class_settings[ 'user_id' ],
					'status' => $e[ 'status' ],
					'action' => $this->class_settings[ 'action_to_perform' ],
					'note' => isset( $opt[ 'note' ] ) ? $opt[ 'note' ] : 'Automatic Onboarding Workflow Initiated',
				];

				// Update status "pending_nin"
				$_POST[ 'id' ] = $e[ 'id' ];
				if( isset( $opt[ 'sr_device' ] ) && $opt[ 'sr_device' ] ){
					$efd[ 'sr_device' ] = $opt[ 'sr_device' ];
				}
				$this->class_settings[ 'update_fields' ] = [
					'data' => json_encode( $efd ),
				];
				$this->_update_table_field();

				return $r;
			}
			// print_r( $r );exit;
		}

		public function _after_workflow_completion(){

			$id = isset( $this->class_settings[ 'workflow_data' ][ 'id' ] ) ? $this->class_settings[ 'workflow_data' ][ 'id' ] : '';

			if( $id ){
				$this->class_settings[ 'overide_select' ] = " id, `". $this->table_fields[ 'data' ] ."` as 'data', `". $this->table_fields[ 'status' ] ."` as 'status' ";
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'workflow' ] ."` = '". $id ."' ";
				$e = $this->_get_records();

				if( isset( $e[0][ 'data' ] ) ){

					$ws = isset( $this->class_settings[ 'workflow_data' ][ 'workflow_settings' ] ) ? $this->class_settings[ 'workflow_data' ][ 'workflow_settings' ] : '';
					$info = 'Successful Onboarding Workflow Completion';

					$efd = $e[0][ 'data' ] ? json_decode( $e[0][ 'data' ], 1 ) : [];
					switch( $ws ){
					case 'wgs34940408611856564707':
						$info = 'Successful Device FEP Transfer Workflow Completion';
						
						if( isset( $efd[ 'sr_device' ] ) && $efd[ 'sr_device' ] ){
							$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`". $this->sr_table ."` SET `". $this->table_fields[ 'status' ] ."` = 'transferred' WHERE `".$this->sr_table."`.`id` = '". $efd[ 'sr_device' ] ."' ";
							
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 1,
								'tables' => array( $this->sr_table ),
							);
							$eee = execute_sql_query($query_settings);
						}
						
					break;
					}

					$efd[ 'trail' ][] = [
						'date' => time(),
						'user' => $this->class_settings[ 'user_id' ],
						'status' => $e[0][ 'status' ],
						'action' => $this->class_settings[ 'action_to_perform' ],
						'info' => $info,
					];

					// Update status "pending_nin"
					$_POST[ 'id' ] = $e[0][ 'id' ];
					$this->class_settings[ 'update_fields' ] = [
						'data' => json_encode( $efd ),
					];
					switch( $ws ){
					case 'wgs34940408611856564707':
						$this->class_settings[ 'update_fields' ][ 'status' ] = 'draft';
					break;
					}
					$this->_update_table_field();

					return $r;
				}
			}
			// print_r( $r );exit;
		}

		public function _create_device_endpoint( $opt = array() ){

			$body = isset( $_POST[ 'body' ] ) ? json_decode( $_POST[ 'body' ], 1 ) : [];
			// print_r( $body );
			if( isset( $body[ 'data' ] ) && $body[ 'data' ] ){
				$status = 'draft';

				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'dev_id' ] ."` = '". $body[ 'data' ][ 'dev_id' ] ."' AND `". $this->table_fields[ 'status' ] ."` IN ( 'draft', 'pending_nin', 'verified_nin', 'pending' ) ";
				$e = $this->_get_records();

				$dt = isset( $e[0][ 'data' ] ) ? json_decode( $e[0][ 'data' ], 1 ) : [];
				$dt[ 'trail' ][] = [
					'date' => time(),
					'user' => $this->class_settings[ 'user_id' ],
					'status' => 'draft',
					'partner' => isset( $body[ 'data' ][ 'partner' ] ) ? $body[ 'data' ][ 'partner' ] : '',
					'action' => 'create_device',
				];

				if( isset( $e[0][ 'id' ] ) && $e[0][ 'id' ] ){

					$set = '';
					foreach( $body[ 'data' ] as $bk => $bd ){
						switch( $bk ){
						case 'date':
							continue 2;
						break;
						}
						if( $set ){
							$set .= ",";
						}
						if( isset( $this->table_fields[ $bk ] ) && $this->table_fields[ $bk ] ){
							$set .= " `". $this->table_fields[ $bk ] ."` = '". $bd ."' ";
						}
					}
					
					$query = "UPDATE `".$this->class_settings["database_name"]."`.`". $this->table_name ."` SET ". $set .", `". $this->table_fields[ 'data' ] ."` = '". json_encode( $dt ) ."', `". $this->table_fields[ 'status' ] ."` = '". $status ."', `". $this->table_fields[ 'increment' ] ."` = `". $this->table_fields[ 'increment' ] ."` + 1 WHERE `id` = '". $e[0][ 'id' ] ."' ";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 0,
						'plugin' => $this->plugin,
						'action_to_perform' => 'create_device_endpoint',
						'tables' => array( $this->table_name ),
					);
					return execute_sql_query($query_settings);

				}else{
					$body[ 'data' ][ 'increment' ] = 1;
					$body[ 'data' ][ 'status' ] = $status;
					$body[ 'data' ][ 'data' ] = json_encode( $dt );
					$this->class_settings[ 'line_items' ] = [ $body[ 'data' ] ];
					return $this->_save_line_items();
				}
			}
		}

		public function get_endpoint( $opt = array() ){
			$rtype = isset( $opt[ 'request_type' ] ) ? $opt[ 'request_type' ] : '';
			$etype = isset( $opt[ 'endpoint_type' ] ) ? $opt[ 'endpoint_type' ] : '';
			$custom_get = isset( $opt[ 'custom_get' ] ) ? $opt[ 'custom_get' ] : '';
			$hash = isset( $opt[ 'hash' ] ) ? $opt[ 'hash' ] : null;

			$ep = $this->end_point1;

			$rand = rand();

			$input = array();
			$input['get'] = $custom_get ? $custom_get : [];
			// print_r( $input['get'] );exit;
			$input['get']['app_secret'] = $this->app_key;
			// $input['get']['app_secret'] = '';
			// $input['get']['cid'] = $hid;
			$input['get']['token'] = $rand;
			$input['get']['public_key'] = md5( $rand . $input['get']['app_secret'] );
			$input['get']['cid'] = $this->app_id;

			if( $hash && ! empty( $hash ) ){
				$hask_key = '';
				// $hh = $input[ 'get' ];
				$hh = $hash;
				foreach( $hh as $hk => $hv ){
					if( isset( $input[ 'get' ][ $hk ] ) && $input[ 'get' ][ $hk ] ){
						$hask_key .= $input[ 'get' ][ $hk ];
					}
				}
				$hask_key = md5( $hask_key );
				$hash_value = md5( $hask_key . get_websalter() );

				$input['get']['nwp_hash'] = get_file_hash( [ "hash" => 1, "file_id" => $hash_value, "no_session" => 1 ] );
				$input['get']['nwp_hash_id'] = $hask_key;
			}

			$output = array(
				'end_point_type' => $etype,
				'end_point' => $ep,
				'request_type' => $rtype,
				'input' => $input,
				// 'widget_key' => $this->widget_key,
			);
			// print_r( $output );exit;
			
			$url = $ep.get_Endpoint( $output );

			return $url;
		}
		
		protected function _kms(){
		}

		protected function _save_datatable_cache(){

			$dir = $this->class_settings["calling_page"] . "includes/datatable-settings";
			if( ! is_dir( $dir ) ){
				create_folder( "", "", $dir );
			}
			$dir .= "/".$this->table_name;
			if( ! is_dir( $dir ) ){
				create_folder( "", "", $dir );
			}

			$filesdir = $this->class_settings["calling_page"] . "tmp";
			if( ! is_dir( $filesdir ) ){
				create_folder( "", "", $filesdir );
			}
			$filesdir .= "/filescache";
			if( ! is_dir( $filesdir ) ){
				create_folder( "", "", $filesdir );
			}
			$filesdir .= "/".$this->class_settings[ 'database_name' ];
			if( ! is_dir( $filesdir ) ){
				create_folder( "", "", $filesdir );
			}
			$filesdir .= "/datatable-settings";
			if( ! is_dir( $filesdir ) ){
				create_folder( "", "", $filesdir );
			}
			$filesdir .= "/".$this->table_name;
			if( ! is_dir( $filesdir ) ){
				create_folder( "", "", $filesdir );
			}

			$settings_dir = $dir . "/settings.json";
			$settings = array();
			if( file_exists( $settings_dir ) ){
				$settings = json_decode( file_get_contents( $settings_dir ), true );
			}

			$field = $this->table_fields[ 'online_status' ];
			if( ! ( isset( $settings[ 'device_online' ] ) && $settings[ 'device_online' ] ) ){
				$settings[ 'device_online' ] = array(
					'field' => $field,
					'style' => 'button',
					'type' => 'all_records',
					'options' => array(
						'online' => array(
							'color' => '#3fd87c',
						),
						'offline' => array(
							'color' => '#d8d87c',
						),
					),
				);
				
				file_put_contents( $settings_dir , json_encode( $settings ) );
			}

			$field = $this->table_fields[ 'status' ];
			if( ! ( isset( $settings[ 'status' ] ) && $settings[ 'status' ] ) ){
				$settings[ 'status' ] = array(
					'field' => $field,
					'style' => 'button',
					'type' => 'all_records',
					'options' => array(
						'in-active' => array(
							'color' => '#d87c7c',
						),
					),
				);
				
				file_put_contents( $settings_dir , json_encode( $settings ) );
			}
		}

		protected function _bg_execute( $opt = [] ){
			$bdpk = "bg-". $this->table_name ."-background-in-progress";

			$bdpk_exec = 0;

			$settings = array( 'cache_key' => $bdpk, );
			$s = get_cache_for_special_values( $settings );
			if( get_hyella_development_mode() ){
				$s = 0;
			}

			if( $s ){

			}else{

				$settings = array(
					'cache_key' => $bdpk,
					'cache_values' => 1,
				);
				set_cache_for_special_values( $settings );
				
				$this->_bg_execute_load_pings( $opt );
				$this->_bg_device_onboarding( $opt );
				foreach( [ 'frequent_changes', 'non_frequent_changes' ] as $tb ){
					$opt[ 'table' ] = $tb;
					$this->_bg_execute_clean_table( $opt );
				}

				$settings = array( 'cache_key' => $bdpk );
				clear_cache_for_special_values( $settings );
				
			}
		}

		protected function _bg_execute_clean_table( $opt = [] ){
			if( ! ( isset( $opt[ 'table' ] ) && $opt[ 'table' ] ) ){
				return;
			}

			$key = 'DEVICE_MGT_CONFIG';
			$pd = $this->_get_project_settings_data( [ 'reference_table' => $this->table_name, 'keys' => [ $key ] ] );

			$retention = 0;
			$retention2 = 0;

			if( isset( $pd[ strtoupper( $key ) ] ) && isset( $pd[ strtoupper( $key ) ][ 'value' ] ) ){
				$ds = json_decode( $pd[ strtoupper( $key ) ][ 'value' ], 1 );

				$pre = '';
				switch( $opt[ 'table' ] ){
				case 'frequent_changes':
					$pre = 'fc';
				break;
				case 'non_frequent_changes':
					$pre = 'nfc';
				break;
				}

				if( isset( $ds[ $pre . '_rention_period' ] ) && $ds[ $pre . '_rention_period' ] ){
					$retention = doubleval( $ds[ $pre . '_rention_period' ] );
				}
				if( isset( $ds[ $pre . '_audit_backup_retention' ] ) && $ds[ $pre . '_audit_backup_retention' ] ){
					$retention2 = doubleval( $ds[ $pre . '_audit_backup_retention' ] );
				}
			}

			if( ! ( $retention || $retention2 ) ){
				return;
			}


			$bdpk = "bg-". $opt[ 'table' ] ."-retention-in-progress";
			$bdpk2 = "bg-". $opt[ 'table' ] ."-audit-backup-retention-in-progress";

			$_GET = array();
			$_POST = array();

			$cut = time();
			$next_check2 = 0;
			$ty_mins = $retention * 60 * 60;

			$dirname = $this->class_settings["calling_page"] . "tmp";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/filescache";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/bg-processes";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/".$this->table_name;
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}

			$nots = array();
			$new_id = get_new_id();
			$sn = 0;

			if( isset( $opt[ 'update_cache_only' ] ) && $opt[ 'update_cache_only' ] ){
				$ltc[ 'next_check' ] = $cut + $ty_mins;
				file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
				
				$settings = array( 'cache_key' => $bdpk );
				$s = clear_cache_for_special_values( $settings );

				return;
			}

			if( file_exists( $dirname . '/'. $bdpk .'.json' ) ){
			// if( 0 && file_exists( ( $dirname . '/'. $bdpk .'.json' ) ) ){
				$ltc = json_decode( file_get_contents( $dirname . '/'. $bdpk .'.json' ), true );
				if( isset( $ltc["next_check"] ) ){
					$next_check2 = doubleval( $ltc["next_check"] );
				}
			}else{
				$next_check2 = $cut - $ty_mins;
			}

			if( $cut > $next_check2 ){

				$bdpk_exec = 1;
				$ds[ 'end_date' ] = time() - $ty_mins;
				$ds[ 'table' ] = $opt[ 'table' ];

				$this->_clear_device_pings_tb( $ds );
				// print_r( $ds );exit;

				$ltc[ 'next_check' ] = $cut + $ty_mins;
				file_put_contents( $dirname . '/'. $bdpk .'.json', json_encode( $ltc ) );
			}


			$_GET = array();
			$_POST = array();

			$cut = time();
			$next_check2 = 0;
			$ty_mins = $retention2 * 60 * 60;

			$dirname = $this->class_settings["calling_page"] . "tmp";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/filescache";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/bg-processes";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/".$this->table_name;
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}

			if( isset( $opt[ 'update_cache_only' ] ) && $opt[ 'update_cache_only' ] ){
				$ltc[ 'next_check' ] = $cut + $ty_mins;
				file_put_contents( $dirname . '/'. $bdpk2 .'.json', json_encode( $ltc ) );
				
				$settings = array( 'cache_key' => $bdpk2 );
				$s = clear_cache_for_special_values( $settings );

				return;
			}

			$nots = array();
			$new_id = get_new_id();
			$sn = 0;

			if( file_exists( $dirname . '/'. $bdpk2 .'.json' ) ){
			// if( 0 && file_exists( ( $dirname . '/'. $bdpk2 .'.json' ) ) ){
				$ltc = json_decode( file_get_contents( $dirname . '/'. $bdpk2 .'.json' ), true );
				if( isset( $ltc["next_check"] ) ){
					$next_check2 = doubleval( $ltc["next_check"] );
				}
			}else{
				$next_check2 = $cut - $ty_mins;
			}

			if( $cut > $next_check2 ){

				$bdpk2_exec = 1;
				$ds[ 'end_date' ] = time() - $ty_mins;

				$tb_name = $opt[ 'table' ] . '_sr';

				// Delete the data from the table
				$query = "DELETE FROM `".$this->class_settings["database_name"]."`.`". $tb_name ."` WHERE `". $this->table_fields[ 'date' ] ."` <= ". $opt[ 'end_date' ] ." ";
				
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'DELETE',
					'set_memcache' => 0,
					'plugin' => $this->plugin,
					'action_to_perform' => 'clear_audit_trail',
					'tables' => array( $tb_name ),
				);
				$sql = execute_sql_query($query_settings);

				$ltc[ 'next_check' ] = $cut + $ty_mins;
				file_put_contents( $dirname . '/'. $bdpk2 .'.json', json_encode( $ltc ) );
			}
		}

		protected function _clear_device_pings_tb( $opt = [] ){
			// echo '<pre>';
			$tb = isset( $opt[ 'table' ] ) ? $opt[ 'table' ] : '';

			if( isset( $opt[ 'end_date' ] ) && $opt[ 'end_date' ] && $tb ){
				$al = $this->plugin_instance->load_class( [ 'class' => [ $tb ], 'initialize' => 1 ] );
				$al[ $tb ]->class_settings[ 'select' ] = ", `record_status` ";
				$al[ $tb ]->class_settings[ 'where' ] = " AND `creation_date` <= ". $opt[ 'end_date' ] ." ";
				$d = $al[ $tb ]->_get_records();

				$pre = '';
				switch( $tb ){
				case 'frequent_changes':
					$pre = 'fc';
				break;
				case 'non_frequent_changes':
					$pre = 'nfc';
				break;
				}

				if( ! empty( $d ) ){

					// Copy data to elastic search
					if( isset( $opt[ 'push_to_es' ] ) && $opt[ 'push_to_es' ] == 'on' && class_exists( 'cNwp_orm' ) ){
						
						// check if index exists before inserting
						require_once  dirname( dirname( dirname( __FILE__ ) ) ) . '/nwp_orm/classes/elasticonn.php';

						$cn = new cNwp_orm;
						$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );

						$client = cOrm_elasticsearch::$esClient;
						$response = $client->info();

						$params = [
						    'index' => $this->table_name
						];

						// $exists = $client->indices()->delete($params);exit;
						$exists = $client->indices()->exists( $params )->asBool();

						if( ! $exists ){
							// $db_mode = '';
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => array(
									'labels' => audit_trail(),
								),
								'query_type' => 'CREATE_TABLE',
								'db_mode' => 'elasticsearch',
								'set_memcache' => 0,
								'plugin' => $this->plugin,
								'action_to_perform' => 'clear_audit_trail',
								'tables' => array( $this->table_name ),
							);
							
							$dxd = execute_sql_query( $query_settings );

						}

						$tfields = $this->table_fields;
						$d2 = array_map(function($d) use ($tfields) {
						    return array_combine(array_map(function($k) use ($tfields) {
						        return $tfields[$k] ?? $k;
						    }, array_keys($d)), $d);
						}, $d);

						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => array(
								'values' => $d2
							),
							'db_mode' => 'elasticsearch',
							'query_type' => 'INSERT',
							'plugin' => $this->plugin,
							'action_to_perform' => 'clear_audit_trail',
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						
						$old = execute_sql_query($query_settings);

						// print_r( audit_trail() );exit;

					}
					// exit;
					// zip data and save in folder
					if( isset( $opt[ $pre . '_zip_path' ] ) && $opt[ $pre . '_zip_path' ] ){
						
						$zip = new ZipArchive();
						$zipname = time() . '_'. $tb .'_clean_backup_'. count( $d ) .'.zip';

						if( $zip->open( $zipname, ZipArchive::CREATE) === true ){
						    foreach( $d as $key => $value ){
								$content = json_encode($value);
								$zip->addFromString( $value[ 'id' ] . '.json', $content);
						    }
						    $zip->close();
						}

						// Save the zip file to a folder
						rename( $zipname, $opt[ $pre . '_zip_path' ] . '/' . $zipname );

					}
					
					// move data to audit_trail_sr for backup
					if( isset( $opt[ $pre . '_audit_backup' ] ) && $opt[ $pre . '_audit_backup' ] == 'on' ){

						$fieldsKeys = [];
						foreach( $d[0] as $ddk => $dd ){
							if( isset( $al[ $tb ]->table_fields[ $ddk ] ) && $al[ $tb ]->table_fields[ $ddk ] ){
								$fieldsKeys[] = $al[ $tb ]->table_fields[ $ddk ];
							}else{
								$fieldsKeys[] = $ddk;
							}
						}

						try{
							$_v = $this->mysqlBulk( $d, $tb . '_sr', 'loaddata_unsafe', [
								'link_identifier' => $this->class_settings["database_connection"],
								'database' => $this->class_settings['database_name'],
								'field_keys' => $fieldsKeys,
								'field_keys' => $fieldsKeys,
							] );
						}catch( Exception $e ){

						}
						
					}

					// Delete the data from the table
					$query = "DELETE FROM `".$this->class_settings["database_name"]."`.`".$tb."` WHERE `creation_date` <= ". $opt[ 'end_date' ] ." ";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'DELETE',
						'set_memcache' => 0,
						'plugin' => $this->plugin,
						'action_to_perform' => 'clear_device_pings_tb',
						'tables' => array( $tb ),
					);
					$sql = execute_sql_query($query_settings);
					
				}
			}
		}

		public function mysqlBulk(&$data, $table, $method = 'transaction', $options = array()) {
			$uniq = cNwp_app_core::getLastTenDigitsMiliSeconds() . rand( 1, 10000 );
			$dirname = $this->class_settings["calling_page"] . "tmp";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/filescache";
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			$dirname .= "/" . $this->table_name;
			if( ! is_dir( $dirname ) ){
				create_folder( "", "", $dirname );
			}
			// print_r( realpath( $dirname . '/'. $uniq .'.txt' ) );exit;

			// Default options
			if (!isset($options['query_handler'])) {
				$options['query_handler'] = 'mysqli_query';
			}
			if (!isset($options['trigger_errors'])) {
				$options['trigger_errors'] = true;
			}
			if (!isset($options['trigger_notices'])) {
				$options['trigger_notices'] = true;
			}
			if (!isset($options['eat_away'])) {
				$options['eat_away'] = false;
			}
			if (!isset($options['database'])) {
				$options['database'] = false;
				trigger_error('Database name is not specified', E_USER_ERROR);
				return false;
			}

			if (!isset($options['in_file'])) {
				file_put_contents( $dirname . '/'. $uniq .'.txt', '' );
				$options['in_file'] = realpath( $dirname . '/'. $uniq .'.txt' );
			}
			$options['in_file'] = _wp_normalize_path( $options['in_file'] );

			if (!isset($options['link_identifier'])) {
				$options['link_identifier'] = null;
			}
			extract($options);

			$table_name = '';
			if ( !is_array($data) ) {
				if ( $trigger_errors ) {
					trigger_error('First argument "queries" must be an array', E_USER_ERROR);
				}
				if( file_exists( $in_file ) ){
				    unlink( $in_file );
				}
				return false;
			}
			if (empty($table)) {
				if ($trigger_errors) {
					trigger_error('No insert table specified', E_USER_ERROR);
				}
				if( file_exists( $in_file ) ){
				    unlink( $in_file );
				}
				return false;
			}else{
				$table_name = $table;
				$table = '`'.$options['database'].'`.`'.$table.'`';
			}
			if (count($data) > 10000) {
			  if ($trigger_notices) {
				  trigger_error('It\'s recommended to use <= 10000 queries/bulk',
					  E_USER_NOTICE);
			  }
			}
			if (empty($data)) {

					if( file_exists( $in_file ) ){
					    unlink( $in_file );
					}
			  return 0;
			}

			if( $table_name && class_exists("cNwp_full_text_search") ){
				$GLOBALS["nwp_full_text_search"][ $table_name ] = 1;
			}
			
			if (!function_exists('__exe')) {
			  function __exe ($sql, $query_handler, $trigger_errors, $link_identifier = null) {
				
				mysqli_options($link_identifier,MYSQLI_OPT_LOCAL_INFILE, true );
				$query_settings = array(
					'database' => '',
					'connect' => $link_identifier,
					'query' => $sql,
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array(),
				);
				execute_sql_query( $query_settings );
				mysqli_options($link_identifier,MYSQLI_OPT_LOCAL_INFILE, false );
				return true;
			  }
			}

			if (!function_exists('__sql2array')) {
				function __sql2array($sql, $trigger_errors) {
					if (substr(strtoupper(trim($sql)), 0, 6) !== 'INSERT') {
						if ($trigger_errors) {
						  	trigger_error('Magic sql2array conversion '.
								'only works for inserts',
								E_USER_ERROR);
						}
						return false;
					}

					$parts   = preg_split("/[,\(\)] ?(?=([^'|^\\\']*['|\\\']" .
										"[^'|^\\\']*['|\\\'])*[^'|^\\\']" .
										"*[^'|^\\\']$)/", $sql);
					$process = 'keys';
					$dat     = array();

					foreach ($parts as $k=>$part) {
						$tpart = strtoupper(trim($part));
						if (substr($tpart, 0, 6) === 'INSERT') {
							continue;
						} else if (substr($tpart, 0, 6) === 'VALUES') {
							$process = 'values';
							continue;
						} else if (substr($tpart, 0, 1) === ';') {
							continue;
						}

						if (!isset($data[$process])) $data[$process] = array();
						$data[$process][] = $part;
					}

					return array_combine($data['keys'], $data['values']);
				}
			}

			$start = microtime(true);
			$count = count($data);

			switch ($method) {
			case 'loaddata':
			case 'loaddata_unsafe':
			case 'loadsql_unsafe':
				$buf    = '';
				foreach($data as $i=>$row) {
					if ($method === 'loadsql_unsafe') {
					  $row = __sql2array($row, $trigger_errors);
					}
					$buf .= implode('::::,', $row)."^^^^";
				}

				if( isset( $options[ 'field_keys' ] ) && $options[ 'field_keys' ] ){
					$fields = implode( ', ', $options[ 'field_keys' ] );
				}else{
					$fields = implode(', ', array_keys($row));
				}

				if (!@file_put_contents($in_file, $buf)) {
					if( $trigger_errors ){
						trigger_error('Cant write to buffer file: "'.$in_file.'"', E_USER_ERROR);
					}
					if( file_exists( $in_file ) ){
					    unlink( $in_file );
					}
					return false;
				}

				if ($method === 'loaddata_unsafe') {
					if (!__exe("SET UNIQUE_CHECKS=0", $query_handler, $trigger_errors, $link_identifier)) return false;
					if (!__exe("set foreign_key_checks=0", $query_handler, $trigger_errors, $link_identifier)) return false;
					if (!__exe("set unique_checks=0", $query_handler, $trigger_errors, $link_identifier)) return false;
				}

				$et = " LOCAL ";
				if( defined("PLATFORM") ){
					switch( PLATFORM ){
					case "linux":
						$et = "";
					break;
					}
				}

				// print_r( "LOAD DATA ".$et." INFILE '{$in_file}'
				//  INTO TABLE {$table}
				//  FIELDS TERMINATED BY '::::,'
				//  LINES TERMINATED BY '^^^^'
				//  ({$fields})" );exit;
				if (!__exe("
				 LOAD DATA ".$et." INFILE '{$in_file}'
				 INTO TABLE {$table}
				 FIELDS TERMINATED BY '::::,'
				 LINES TERMINATED BY '^^^^'
				 ({$fields})
				", $query_handler, $trigger_errors, $link_identifier)){
					if( file_exists( $in_file ) ){
					    unlink( $in_file );
					}
					return false;
				}
				
			break;
			}

			if( file_exists( $in_file ) ){
			    unlink( $in_file );
			}

			// Stop timer
			$duration = microtime(true) - $start;
			$qps      = round ($count / $duration, 2);

			if ($eat_away) {
				$data = array();
			}

			return $qps;
		}

		protected function _bg_execute_load_pings(){

			$_GET = array();
			$_POST = array();

			if( ! ( defined( 'DEVICE_PINGS_CHECK_INTERVAL' ) && intval( DEVICE_PINGS_CHECK_INTERVAL ) ) ){
				return 'Undefined DEVICE_PINGS_CHECK_INTERVAL';
			}

			if( ! ( defined( 'DEVICE_PINGS_PATH' ) && DEVICE_PINGS_PATH ) ){
				return 'Undefined DEVICE_PINGS_PATH';
			}

			$bdpk = "bg-". $this->table_name ."-in-progress";

			$cut = time();
			$next_check2 = 0;
			$ty_mins = 60 * intval( DEVICE_PINGS_CHECK_INTERVAL ); // 10mins
			$dirname = $this->table_name;
			

			$path1 = $this->class_settings["calling_page"] . 'tmp/filescache/' . $this->class_settings[ 'database_name' ] . '/' . $this->table_name ;
			$path = $path1 . '/enrol_data.json';

			$path2 = DEVICE_PINGS_PATH;
			$path3 = DEVICE_PINGS_PATH . '-loaded';

			$path2 = $this->class_settings[ 'calling_page' ] . $path2;
			$path3 = $this->class_settings[ 'calling_page' ] . $path3;

			// if( file_exists( ( $path ) ) ){
			if( file_exists( ( $path ) ) ){
				// echo 'yeah';
				$ltc = json_decode( file_get_contents( $path ), true );
				if( isset( $ltc["next_check"] ) ){
					$next_check2 = doubleval( $ltc["next_check"] );
				}
			}else{
				$next_check2 = $cut - $ty_mins;
			}

			$proceed = 0;
			if( $cut > $next_check2 ){
				$proceed = 1;
			}

			if( $proceed ){

				// print_r( scandir( $path2 ) );exit;
					// print_r( dirname( $this->class_settings[ 'calling_page' ] ) );exit;
					// print_r( dirname( __FILE__ ) );exit;
				if( file_exists( $path2 ) && dir( $path2 ) ){
					if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
						ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
					}else{
						ini_set('memory_limit', '4G' );
					}

				    foreach( scandir( $path2 ) as $file ){
				    	$history = array();

				        if ( $file !== '.' && $file !== '..' ) {

    			        	if( ! file_exists( $path3 ) ){
    			        		create_folder( $path3, '', '' );
    			        	}

    	       				rename( $path2 . '/' . $file, $path3 . '/' . $file );

				        	$read_message = "Reading File - '". $file ."'\n";
				        	echo $read_message;
				        	$history[] = $read_message;

				        	$content = file_get_contents( $path3 . '/' . $file );
				        	if( $content ){
				        		$json = json_decode( $content, 1 );

				        		if( is_array( $json ) && ! empty( $json ) ){

									$rr = $this->_loadDevicePings( $json, $file );
									if( isset( $rr[ 'error' ] ) && $rr[ 'error' ] ){
					        			$read_message = $rr[ 'error' ] ."'\n";
					        			echo $read_message;
					        			$history[] = $read_message;
									}

				        		}else{

				        			$read_message = "Corrupt JSON - '". $file ."'\n";
				        			echo $read_message;
				        			$history[] = $read_message;

				        		}
				        		
				        	}else{

				        		$read_message = "Empty File - '". $file ."'\n";
				        		echo $read_message;
				        		$history[] = $read_message;

				        	}
				        	// print_r( file_get_contents( $path2 . '/' . $file ) );exit;
				        	
			        	}

					}
				}

				$ltc[ 'next_check' ] = $cut + $ty_mins;
				if( ! file_exists( $path1 ) ){
					create_folder( $path1, '', '' );
				}
				file_put_contents( $path, json_encode( $ltc ) );
				//print_r( $path.'---' );
			}
		}

		protected function _bg_device_onboarding( $opt ){

			$_GET = array();
			$_POST = array();

			if( ! ( defined( 'DEVICE_ONBOARDING_CHECK_INTERVAL' ) && intval( DEVICE_ONBOARDING_CHECK_INTERVAL ) ) ){
				return 'Undefined DEVICE_ONBOARDING_CHECK_INTERVAL';
			}

			$bdpk = "bg-onboarding-". $this->table_name ."-in-progress";

			$check_fep_exist = 1;
			$check_fep_dev = 1;
			$send_nin_verify = 1;

			$cut = time();
			$next_check2 = 0;
			$ty_mins = 60 * intval( DEVICE_ONBOARDING_CHECK_INTERVAL ); // 10mins
			$dirname = $this->table_name;
			

			$path1 = $this->class_settings["calling_page"] . 'tmp/filescache/' . $this->class_settings[ 'database_name' ] . '/' . $this->table_name ;
			$path = $path1 . '/dev_onboard_data.json';

			// if( file_exists( ( $path ) ) ){
			if( 0 && file_exists( ( $path ) ) ){
				// echo 'yeah';
				$ltc = json_decode( file_get_contents( $path ), true );
				if( isset( $ltc["next_check"] ) ){
					$next_check2 = doubleval( $ltc["next_check"] );
				}
			}else{
				$next_check2 = $cut - $ty_mins;
			}

			$proceed = 0;
			if( $cut > $next_check2 ){
				$proceed = 1;
			}

			if( $proceed ){

				// pull all devices in pending table with status of draft
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'status' ] ."` = 'draft' ";
				$e = $this->_get_records();

				// $all = $this->plugin_instance->load_class( [ 'class' => [ 'enrolment_partner' ], 'initialize' => 1 ] );

				if( isset( $e[0][ 'id' ] ) && $e[0][ 'id' ] ){
					// For each
					foreach( $e as $ed ){
						$continue = 0;
						$note = '';
						$sub_status = '';

						if( $check_fep_exist ){
							$gnor = get_name_of_referenced_record( array( 'id' => $ed[ 'partner' ], 'table' => 'enrolment_partner' ) );

							if( $ed[ 'partner' ] == $gnor ){
								$note = 'FEP not found';

								$content = 'This device with the following details: <br>';
								$content .= '<b>Device ID:</b> '. $ed[ 'dev_id' ] . '<br>';
								$content .= '<b>Device Model:</b> '. $ed[ 'model' ] . '<br>';
								$content .= '<b>FEP:</b> '. get_name_of_referenced_record( array( 'id' => $ed[ 'partner' ], 'table' => 'enrolment_partner' ) ) . '<br>';
								$content .= '<b>Status:</b> '. ( isset( $stat[ $ed[ 'status' ] ] ) ? $stat[ $ed[ 'status' ] ] : $ed[ 'status' ] ) . '<br>';

								$content .= '<br>has requested to be onboarded. But the FEP code of \''. $ed[ 'partner' ] .'\' does not exist <br>';

								$content .= '<br>As a result, the onboarding process has now been cancelled. Please login to the Device Management or escalate for resolution';

								$this->_send_notifications( [ 
									'id' => $ed[ 'id' ],
									'fep' => $ed[ 'partner' ],
									'subject' => $note,
									'content' => $content,
									'info' => $note,
								] );

								$continue = 1;
								$sub_status = 'no_fep';

							}

						}

						if( ! $continue && $check_fep_dev ){
							// Dont log if onboarding status has been approved
							// Checks if device id is found in any other FEP
							$this->class_settings[ 'overide_select' ] = " id, `". $this->table_fields[ 'dev_id' ] ."` as 'dev_id', `". $this->table_fields[ 'model' ] ."` as 'model', `". $this->table_fields[ 'status' ] ."` as 'status', `". $this->table_fields[ 'partner' ] ."` as 'partner' ";
							$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'dev_id' ] ."` = '". $ed[ 'dev_id' ] ."' AND `". $this->table_fields[ 'status' ] ."` IN ( 'active', 'test' ) ";
							$e2 = $this->_get_records( [ 'table_name' => 'device_mgt_sr' ] );

							if( isset( $e2[0][ 'id' ] ) && $e2[0][ 'id' ] ){
								$note = 'Failed to Initiate Onboarding Approval Workflow - Duplicate Device Found';
								$stat = get_list_box_options( 'get_device_status', array( "return_type" => 2 ) );

								$content = 'This device with the following details: <br>';
								$content .= '<b>Device ID:</b> '. $ed[ 'dev_id' ] . '<br>';
								$content .= '<b>Device Model:</b> '. $ed[ 'model' ] . '<br>';
								$content .= '<b>FEP:</b> '. get_name_of_referenced_record( array( 'id' => $ed[ 'partner' ], 'table' => 'enrolment_partner' ) ) . '<br>';
								$content .= '<b>Status:</b> '. ( isset( $stat[ $ed[ 'status' ] ] ) ? $stat[ $ed[ 'status' ] ] : $ed[ 'status' ] ) . '<br>';

								$content .= '<br>has requested to be onboarded. But it matches with the following existing device: <br>';
								$content .= '<b>Device ID:</b> '. $e2[0][ 'dev_id' ] . '<br>';
								$content .= '<b>Device Model:</b> '. $e2[0][ 'model' ] . '<br>';
								$content .= '<b>FEP:</b> '. get_name_of_referenced_record( array( 'id' => $e2[0][ 'partner' ], 'table' => 'enrolment_partner' ) ) . '<br>';
								$content .= '<b>Status:</b> '. ( isset( $stat[ $e2[0][ 'status' ] ] ) ? $stat[ $e2[0][ 'status' ] ] : $e2[0][ 'status' ] ) . '<br>';

								$content .= '<br>As a result, the onboarding process has now been cancelled. Please login to the Device Management or escalate for resolution';

								$this->_send_notifications( [ 
									'id' => $ed[ 'id' ],
									'fep' => $ed[ 'partner' ],
									'subject' => $note,
									'content' => $content,
									'info' => $note,
								] );

								$continue = 1;
								if( $e2[0][ 'partner' ] == $ed[ 'partner' ] ){
									$sub_status = 'dup_fep';
								}else{
									$sub_status = 'diff_fep';
								}

							}

						}

						if( ! $continue ){
							if( $send_nin_verify ){

								if( isset( $ed[ 'fep_agent_nin' ] ) && $ed[ 'fep_agent_nin' ] ){
									$ur = new cUsers;
									$ur->class_settings = $this->class_settings;

									$ur->class_settings[ 'overide_select' ] = " `". $ur->table_fields[ 'email' ] ."` as 'email', `". $ur->table_fields[ 'firstname' ] ."` as 'firstname', `". $ur->table_fields[ 'lastname' ] ."` as 'lastname', `id` ";
									$ur->class_settings[ 'where' ] = " AND `". $ur->table_fields[ 'division' ] ."` = '". $ed[ 'fep_agent_nin' ] ."' ";
									$eu = $ur->_get_records();

									if( isset( $eu[0][ 'id' ] ) && $eu[0][ 'id' ] ){
										$atd = 'fep_email_user_verify';
										$esubject = "FEP AGENT VERIFICATION";
										$etitle = 'FEP Agent Onboarding Verification - Device '.$ed[ 'dev_id' ];
										$emsg = 'Click here to complete the verification';
										$emsg1 = '';

										$url = $this->get_endpoint( array( "source" => "emr", "fsource" => "" ) );
										$url2 = '&daction='. $this->plugin .'&dtodo=execute&dnwp_action='. $this->table_name . '&dnwp_todo=' . $atd;

										$url .= $url2 .'&_u='.$eu[0][ 'id' ] .'&_d='.$ed["id"];

										$econtent = "Or you can copy and paste the link below in your browser address bar:<br>".$url;

										$fn_key = 'firstname';
										$ln_key = 'lastname';
										switch( $this->table_name ){
										case 'enrolment_partner':
											$fn_key = 'name';
											$ln_key = '';
										break;
										}

										$this->_send_email_notification( array(
											"subject" => $esubject,
											"content" => $econtent,
											"info" => $emsg1 . '<br /><a target="_blank" href="'. $url .'" title="'. $etitle .'" class="btnx redx">'. $emsg .'</a>',
											"single_email"  => $eu[0][ 'email' ],
											"single_name" => $eu[0][ $fn_key ] . ( isset( $eu[0][ 'lastname' ] ) ? ' ' . $eu[0][ 'lastname' ] : '' )
										) );

										$efd = $ed[ 'data' ] ? json_decode( $ed[ 'data' ], 1 ) : [];
										$efd[ 'trail' ][] = [
											'date' => time(),
											'user' => $this->class_settings[ 'user_id' ],
											'status' => 'pending_nin',
											'action' => $this->class_settings[ 'action_to_perform' ],
											'note' => 'Email sent to FEP Agent email for Verification',
										];

										// Update status "pending_nin"
										$_POST[ 'id' ] = $ed[ 'id' ];
										$this->class_settings[ 'update_fields' ] = [
											'status' => 'pending_nin',
											'data' => json_encode( $efd ),
										];
										$this->_update_table_field();

									}else{
										$note = 'Unable to Retrieve FEP Agent NIN Details';
										$continue = 1;
									}
								}else{
									$note = 'FEP Agent NIN Not Found';
									$continue = 1;
								}

							}else{
								$this->_create_workflow( [ 'id' => $ed[ 'id' ] ] );
							}
						}

						if( $continue ){
							// Set status to flagged

							$efd = $ed[ 'data' ] ? json_decode( $ed[ 'data' ], 1 ) : [];
							$efd[ 'trail' ][] = [
								'date' => time(),
								'user' => $this->class_settings[ 'user_id' ],
								'status' => 'flagged',
								'sub_status' => $sub_status,
								'action' => 'pre_workflow_check',
								'note' => $note,
							];

							$_POST[ 'id' ] = $ed[ 'id' ];
							$this->class_settings[ 'update_fields' ] = [
								'status_msg' => $note,
								'status' => 'flagged',
								'sub_status' => $sub_status,
								'data' => json_encode( $efd ),
							];
							$this->_update_table_field();

							continue;
						}


					}

				}


				$ltc[ 'next_check' ] = $cut + $ty_mins;
				if( ! file_exists( $path1 ) ){
					create_folder( $path1, '', '' );
				}
				file_put_contents( $path, json_encode( $ltc ) );
				//print_r( $path.'---' );
			}
		}
		
		public function _send_notifications( $e = array() ){

			$all_mails = $this->_get_access_role_mails( array( 'fep' => $e[ 'fep' ] ) );
			if( ! empty( $all_mails[ 'ids' ] ) ){
				$content = isset( $e[ 'content' ] ) ? $e[ 'content' ] : '';
				$subject = isset( $e[ 'subject' ] ) ? $e[ 'subject' ] : '';

				// @pat2echo
				$un = [];
				foreach( $all_mails[ 'ids' ] as $am ){
					$un[] = "'". $am ."'";
				}
					
				$not = array(
					"data" => array( 'reference' => $e[ "id" ], 'reference_table' => $this->table_name, 'reference_plugin' => $this->plugin ),
					"content" => $content,
					"subject" => $subject,
					"specific_users" => $un,
					"sending_option" => "bcc",
					"multiple_emails" => implode(",", $all_mails[ 'mails' ] ),
				);
				$this->class_settings["users_filter"]["status"] = 'active';
				$h = $this->_send_email_notification( $not, [] );
			}	
		}

		public function _get_access_role_mails( $e = array() ){

			$access = get_accessed_functions();
			$super = 0;
			if( ! is_array( $access ) && $access == 1 ){
				$super = 1;
			}

			$ids = array();
			$mails = array();
			
			$a = new cAccess_roles();
			$u = new cUsers();
			$u->class_settings = $this->class_settings;

			$overide_select = " `". $u->table_name ."`.`". $u->table_fields[ 'email' ] ."` as 'email', `". $u->table_name ."`.`id` ";
			$join = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $a->table_name ."` ON `". $u->table_name ."`.`". $u->table_fields[ 'role' ] ."` = `". $a->table_name ."`.`id` ";

			$u->class_settings[ 'overide_select' ] = $overide_select;
			$u->class_settings[ 'join' ] = $join;
			
			$u->class_settings[ 'where' ] = " AND `". $a->table_name ."`.`". $a->table_fields[ 'data' ] ."` LIKE '%". '"'. $this->table_name .'.onboarding_email_not.all":1' ."%' ";

			$r = $u->_get_records();
			if( isset( $r[0][ 'id' ] ) && $r[0][ 'id' ] ){
				$ids = array_merge( $ids, array_column( $r, 'id' ) );
				$mails = array_merge( $mails, array_column( $r, 'email' ) );
			}

			if( $e[ 'fep' ] ){
				
				$u->class_settings[ 'overide_select' ] = $overide_select;
				$u->class_settings[ 'join' ] = $join;
				
				$u->class_settings[ 'where' ] = " AND `". $a->table_name ."`.`". $a->table_fields[ 'data' ] ."` LIKE '%". '"'. $this->table_name .'.onboarding_email_not.per_fep":1' ."%' AND `". $u->table_name ."`.`". $u->table_fields[ 'ref_no' ] ."` = '". $e[ 'fep' ] ."' ";

				$r = $u->_get_records();
				if( isset( $r[0][ 'id' ] ) && $r[0][ 'id' ] ){
					$ids = array_merge( $ids, array_column( $r, 'id' ) );
					$mails = array_merge( $mails, array_column( $r, 'email' ) );
				}

			}

			return [ 'mails' => $mails, 'ids' => $ids ];
			print_r( $mails );exit;
		}
		
		protected function _loadDevicePings( $json = [], $filename = '' ){
			$error = '';

			if( is_array( $json ) && ! empty( $json ) ){

				$this->_saveDeviceChanges( $json );

				$this->_updateDevice( $json );
				
			}else{
				$error = 'Invalid JSON Data';
			}

			if( $error ){
				return [ 'error' => $error ];
			}
		}

		protected function _updateDevice( $json = [] ){

			$al = $this->plugin_instance->load_class( [ 'class' => [ 'frequent_changes', 'non_frequent_changes' ], 'initialize' => 1 ] );

			$device_id = '';
			if( isset( $json[ 'deviceMgtUid' ] ) && $json[ 'deviceMgtUid' ] ){
				$this->class_settings[ 'current_record_id' ] = $json[ 'deviceMgtUid' ];
				$d = $this->_get_record();

				if( isset( $d[ 'id' ] ) && $d[ 'id' ] ){
					$device_id = $d[ 'id' ];

					$_POST[ 'id' ] = $d[ 'id' ];

					$line_items = [
						'online_status' => 'online',
						'storage' => isset( $json[ 'totalStorage' ] ) ?  cNwp_app_core::convertStorageToKilobytes( $json[ 'totalStorage' ] ) : '',
						'os_name' => isset( $json[ 'osName' ] ) ? $json[ 'osName' ] : '',
						'os_version' => isset( $json[ 'osVersion' ] ) ? $json[ 'osVersion' ] : '',
						'processor_speed' => isset( $json[ 'processorSpeed' ] ) ? $json[ 'processorSpeed' ] : '',
						'avail_storage' => isset( $json[ 'availableStorage' ] ) ? cNwp_app_core::convertStorageToKilobytes( $json[ 'availableStorage' ] ): '',
						'network_strength' => isset( $json[ 'networkStrength' ] ) ? $json[ 'networkStrength' ] : '',
						'latitude' => isset( $json[ 'latitude' ] ) ? $json[ 'latitude' ] : '',
						'longitude' => isset( $json[ 'longitude' ] ) ? $json[ 'longitude' ] : '',
						'capture_time' => isset( $json[ 'timestamp' ] ) ? date( 'Y-m-dTH:i:s', doubleval( $json[ 'timestamp' ] ) ) : '',
						'update_time' => date( 'Y-m-dTH:i:s' ),
						'battery' => isset( $json[ 'battery' ] ) ? $json[ 'battery' ] : '',
						'gps_module' => isset( $json[ 'gps_module' ] ) ? $json[ 'gps_module' ] : '',
						'country_pings' => 'nigeria',
						'state_pings' => 'AB',
						'lga_pings' => '1001',
					];

					$this->class_settings[ 'update_fields' ] = $line_items;
					$r = $this->_update_table_field();
					// print_r( $r );exit;

				}
			}
		}

		protected function _saveDeviceChanges( $json = [] ){
			
			$al = $this->plugin_instance->load_class( [ 'class' => [ 'frequent_changes', 'non_frequent_changes' ], 'initialize' => 1 ] );

			$device_id = '';
			if( isset( $json[ 'deviceId' ] ) && $json[ 'deviceId' ] ){
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'dev_id' ] ."` = '". $json[ 'deviceId' ] ."' ";
				$d = $this->_get_records();

				if( isset( $d[0][ 'id' ] ) && $d[0][ 'id' ] ){
					$device_id = $d[0][ 'id' ];
				}else{
					$device_id = 'NO_DEVICE';
				}
			}else{
				$device_id = 'NO_DEVICE';
			}

			$date = date("Y-m-dTH:i:s");

			if( isset( $d[ 'id' ] ) && $d[ 'id' ] ){
				foreach( [ 'storage', 'os_name', 'ram', 'imsi', 'model', 'imei' ] as $fl ){
					$ffl = $fl;
					switch( $fl ){
					case 'os_name':
						$ffl = 'osName';
					break;
					case 'storage':
						$ffl = 'totalStorage';
					break;
					case 'ram':
						$ffl = 'ramSize';
					break;
					}

					$v1 = $d[ $fl ];
					$v2 = $json[ $ffl ];

					switch( $fl ){
					case 'storage':
					case 'ram':
						$v1 = round( doubleval( $d[ $fl ] ), 2 );
						$v2 = cNwp_app_core::convertStorageToKilobytes( $json[ $ffl ] );
					break;
					}

					if( $v1 !== $v2 ){
						$_POST[ 'id' ] = '';

						$line_items = [
							'date' => $date,
							'device' => $device_id,
							'model' => isset( $json[ 'model' ] ) ? $json[ 'model' ] : '',
							'status' => '',
							'center' => '-',
							'custodian' => '-',
							'purpose' => '-',
							'serial_number' => '-',
							'os_name' => isset( $json[ 'osName' ] ) ? $json[ 'osName' ] : '',
							'storage' => isset( $json[ 'totalStorage' ] ) ?  cNwp_app_core::convertStorageToKilobytes( $json[ 'totalStorage' ] ) : '',
							'ram' => isset( $json[ 'ramSize' ] ) ?  cNwp_app_core::convertStorageToKilobytes( $json[ 'ramSize' ] ) : '',
							'imsi' => isset( $json[ 'imsi' ] ) ? $json[ 'imsi' ] : '',
							'imei' => isset( $json[ 'imei' ] ) ? $json[ 'imei' ] : '',
							'latitude' => isset( $json[ 'latitude' ] ) ? $json[ 'latitude' ] : '',
							'longitude' => isset( $json[ 'longitude' ] ) ? $json[ 'longitude' ] : '',
							'battery' => isset( $json[ 'battery' ] ) ? $json[ 'battery' ] : '',
							'data' => json_encode( $json ),
						];

						$al[ 'non_frequent_changes' ]->class_settings[ 'update_fields' ] = $line_items;
						$r = $al[ 'non_frequent_changes' ]->_update_table_field();

						break;
						
					}

				}

			}

			$_POST[ 'id' ] = '';
			if( isset( $r[ 'saved_record_id' ] ) && $r[ 'saved_record_id' ] ){
				$_POST[ 'tmp' ] = $r[ 'saved_record_id' ];
			}

			$line_items = [
				'date' => $date,
				'device' => $device_id,
				'storage' => isset( $json[ 'totalStorage' ] ) ?  cNwp_app_core::convertStorageToKilobytes( $json[ 'totalStorage' ] ) : '',
				'os_name' => isset( $json[ 'osName' ] ) ? $json[ 'osName' ] : '',
				'os_version' => isset( $json[ 'osVersion' ] ) ? $json[ 'osVersion' ] : '',
				'processor_speed' => isset( $json[ 'processorSpeed' ] ) ? $json[ 'processorSpeed' ] : '',
				'avail_storage' => isset( $json[ 'availableStorage' ] ) ? cNwp_app_core::convertStorageToKilobytes( $json[ 'availableStorage' ] ): '',
				'network_strength' => isset( $json[ 'networkStrength' ] ) ? $json[ 'networkStrength' ] : '',
				'latitude' => isset( $json[ 'latitude' ] ) ? $json[ 'latitude' ] : '',
				'longitude' => isset( $json[ 'longitude' ] ) ? $json[ 'longitude' ] : '',
				'capture_time' => isset( $json[ 'timestamp' ] ) ? date( 'Y-m-dTH:i:s', doubleval( $json[ 'timestamp' ] ) ) : '',
				'battery' => isset( $json[ 'battery' ] ) ? $json[ 'battery' ] : '',
				'gps_module' => isset( $json[ 'gps_module' ] ) ? $json[ 'gps_module' ] : '',
				'fingerprint' => isset( $json[ 'fingerprint' ] ) ? $json[ 'fingerprint' ] : '',
				'data' => json_encode( $json ),
			];

			$al[ 'frequent_changes' ]->class_settings[ 'update_fields' ] = $line_items;
			$r = $al[ 'frequent_changes' ]->_update_table_field();
		}

		protected function _custom_capture_form_version_2(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = str_replace( '_', '-', $action_to_perform );
			
			$modal = 0;
			$params = '';
			$html = '';
			$close_modal = [];
			$returnData = [];
			$error = '';
			$etype = 'error';
			$error_container = '';
			$manual_close = 0;
			$mjs = array( 'set_function_click_event', 'prepare_new_record_form_new' );

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			
			$data = array();
			
			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'view_map_device':
				if( $id ){
					$_POST[ 'dev_id' ] = $id;
				}else{
					return $this->_display_notification( array( 'type' => 'error', 'message' => '<h4><strong>Device Id Not Found</strong></h4>' ) );
				}
			case 'view_map_devices':
			case 'get_data_filter':
				$where = " AND `". $this->table_name ."`.`". $this->table_fields[ 'longitude' ] ."` IS NOT NULL ";

				$search_field = isset( $_POST[ 'search_field' ] ) ? $_POST[ 'search_field' ] : '';
				$country = isset( $_POST[ 'country' ] ) ? $_POST[ 'country' ] : '';
				$state = isset( $_POST[ 'state' ] ) ? $_POST[ 'state' ] : '';
				$lga = isset( $_POST[ 'lga' ] ) ? $_POST[ 'lga' ] : '';
				$ward = isset( $_POST[ 'ward' ] ) ? $_POST[ 'ward' ] : '';
				$ward = isset( $_POST[ 'ward' ] ) ? $_POST[ 'ward' ] : '';
				$dev_id = isset( $_POST[ 'dev_id' ] ) ? $_POST[ 'dev_id' ] : '';
				$partner = isset( $_GET[ 'partner' ] ) ? $_GET[ 'partner' ] : ( isset( $_POST[ 'partner' ] ) ? $_POST[ 'partner' ] : '' );

				$from = isset( $_GET[ 'from' ] ) ? $_GET[ 'from' ] : '';
				$from_id = isset( $_GET[ 'from_id' ] ) ? $_GET[ 'from_id' ] : '';

				$this->class_settings[ 'overide_select' ] = " `". $this->table_name ."`.`id`, `". $this->table_name ."`.`". $this->table_fields[ 'latitude' ] ."` as 'latitude', `". $this->table_name ."`.`". $this->table_fields[ 'longitude' ] ."` as 'longitude' , `". $this->table_name ."`.`". $this->table_fields[ 'model' ] ."` as 'text' ";

				if( $from && $from_id ){
					$from_value = $from;
					switch( $from ){
					case 'enrolment_center':
						$from_value = 'center';
					break;
					}

					$em = 'enrolment_metadata';
					$al = $this->plugin_instance->load_class( [ 'class' => [ $em ], 'initialize' => 1 ] );

					$this->class_settings[ 'join' ] = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $em ."` ON `". $this->table_name ."`.`id` = `". $em ."`.`device` AND `". $em ."`.`". $from_value ."` = '". $from_id ."' ";
					$this->class_settings[ 'group' ] = " GROUP BY `". $this->table_name ."`.`id` ";
				}

				$country_field = 'country';
				$state_field = 'state';
				$lga_field = 'lga';
				$ward_field = 'ward';

				switch( $search_field ){
				case 'last_loc':
					$country_field = 'country_pings';
					$state_field = 'state_pings';
					$lga_field = 'lga_pings';
					$ward_field = 'ward_pings';
				break;
				}

				if( $partner ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'partner' ] ."` = '". $partner ."' ";
				}elseif( $dev_id ){
					$where .= " AND `". $this->table_name ."`.`id` = '". $dev_id ."' ";
				}elseif( $ward ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ $ward_field ] ."` = '". $ward ."' ";
				}elseif( $lga ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ $lga_field ] ."` = '". $lga ."' ";
				}elseif( $state ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ $state_field ] ."` = '". $state ."' ";
				}elseif( $country ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ $country_field ] ."` = '". $country ."' ";
				}

				$this->class_settings[ 'where' ] = $where;

				$r = $this->_get_records();
				// print_r( $this->last_query );exit;
				$return = array(
					'status' => 'new-status',
				);

				if( isset( $r[0] ) && $r[0] ){
					$return[ 'data' ][ 'items' ] = $r;
					$return[ 'javascript_functions' ] = array( 'nwOpenLayer.init' );
				}else{
					$return = $this->_display_notification( array( 'type' => 'info', 'message' => '<h4>No Data Found</h4><p>This filter returned an empty dataset</p>' ) );
				}
				// print_r( $r );exit;

				
				switch ( $action_to_perform ){
				case 'view_map_devices':
				case 'view_map_device':
					$returnData = isset( $return[ 'data' ] ) ? $return[ 'data' ] : [];
					$data[ 'no_filter' ] = 1;
					$filename = 'gis-map';
				break;
				default:
					return $return;
				break;
				}
				
			break;
			case 're_onboard_device':
				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();

				if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
					$al = $this->plugin_instance->load_class( [ 'class' => [ $this->sr_table ], 'initialize' => 1 ] );
					$al[ $this->sr_table ]->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'dev_id' ] ."` = '". $e[ 'dev_id' ] ."' ";
					$re = $al[ $this->sr_table ]->_get_records();

					if( isset( $re[0][ 'id' ] ) && $re[0][ 'id' ] ){
						$note = 'Device has been requested to be re-onboarded by '.get_name_of_referenced_record( array( 'id' => $this->class_settings[ 'user_id' ], 'table' => 'users' ) );
						$arr = [
							'date' => time(),
							'user' => $this->class_settings[ 'user_id' ],
							'status' => 'in-active',
							'action' => $action_to_perform,
							'note' => $note,
						];

						$efd = $re[0][ 'data' ] && $re[0][ 'data' ] ? json_decode( $re[0][ 'data' ], 1 ) : [];
						$efd[ 'trail' ][] = $arr;

						$al[ $this->sr_table ]->class_settings[ 'update_fields' ] = [
							'status' => 'in-active',
							'status_msg' => $note,
							'data' => json_encode( $efd ),
						];

						$_POST[ 'id' ] = $re[0][ 'id' ];
						$al[ $this->sr_table ]->_update_table_field();

						$efd = $e[ 'data' ] && $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];
						$arr[ 'status' ] = 'draft';
						$efd[ 'trail' ][] = $arr;
						$this->class_settings[ 'update_fields' ] = [
							'status' => 'draft',
							'status_msg' => '',
							'data' => json_encode( $efd ),
						];

						$_POST[ 'id' ] = $e[ 'id' ];
						$this->_update_table_field();

						$error = '<h4><strong>Successful Operation</strong></h4>This device ID has been sent back to draft for Onboarding';
						$etype = 'success';
						$close_modal = [ '$nwProcessor.reload_datatable' ];

					}else{
						$error = '<h4><strong>Unable to Retrieve Device Id</strong></h4>This device ID is not found in the Approved Devices Data Source';
					}
				}
			break;
			case 'start_trf_workflow':
				$modal = 1;

				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();

				if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){

					$al = $this->plugin_instance->load_class( [ 'class' => [ $this->sr_table ], 'initialize'  => 1] );
					$al[ $this->sr_table ]->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'dev_id' ] ."` = '". $e[ 'dev_id' ] ."' ";
					// $al[ $this->sr_table ]->class_settings[ 'where' ] .= " AND `". $this->table_fields[ 'status' ] ."` IN ( 'active', 'test' ) ";
					$ee = $al[ $this->sr_table ]->_get_records();

					if( isset( $ee[0][ 'id' ] ) && $ee[0][ 'id' ] ){
						if( $ee[0][ 'partner' ] !== $e[ 'partner' ] ){

							$dt = isset( $ee[0][ 'data' ] ) && $ee[0][ 'data' ] ? json_decode( $ee[0][ 'data' ], 1 ) : [];
							$dt[ 'trail' ][] = [
								'date' => time(),
								'user' => $this->class_settings[ 'user_id' ],
								'status' => 'transferred',
								'action' => $action_to_perform,
								'info' => 'A Transfer Workflow has been Initiated to this FEP: '. get_name_of_referenced_record( array( 'id' => $e[ 'partner' ], 'table' => 'enrolment_partner' ) ),
							];

							$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->sr_table."` SET `". $this->table_fields[ 'data' ] ."` = '". json_encode( $dt ) ."' WHERE `".$this->sr_table."`.`id` = '". $ee[0][ 'id' ] ."' ";
							
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							
							execute_sql_query($query_settings);
							
							$al[ $this->sr_table ]->class_settings[ 'current_record_id' ] = 'pass_condition';
							$al[ $this->sr_table ]->class_settings[ 'do_not_check_cache' ] = 1;
							
							$al[ $this->sr_table ]->class_settings[ 'cache_where' ] = " AND `". $this->sr_table ."`.`id` = '". $ee[0][ 'id' ] ."' ";
							$al[ $this->sr_table ]->class_settings[ 'cache_where_result' ] = 1;
							$al[ $this->sr_table ]->_get_record();
							// print_r( $al[ $this->sr_table ]->table_name );exit;

							$opt = [
								'id' => $e[ 'id' ],
								'description' => 'Transfer Workflow for Device ID "' . $e[ 'dev_id' ] . '" to FEP "'. get_name_of_referenced_record( array( 'id' => $e[ 'partner' ], 'table' => 'enrolment_partner' ) ) .'"',
								'partners' => [ $e[ 'partner' ], $ee[0][ 'partner' ] ],
								'comment' => 'This device is being transsferred from '.get_name_of_referenced_record( array( 'id' => $ee[0][ 'partner' ], 'table' => 'enrolment_partner' ) ). ' to ' .get_name_of_referenced_record( array( 'id' => $e[ 'partner' ], 'table' => 'enrolment_partner' ) ),
								'workflow_settings' => 'wgs34940408611856564707',
								'note' => 'Workflow Initiated for Transferring Device to another FEP',
								'sr_device' => $ee[0][ 'id' ],
							];

							$r = $this->_create_workflow( $opt );

							$error = '<h4><strong>Workflow Created Successfully</strong></h4>';
							$etype = 'success';

							$close_modal = [ '$nwProcessor.reload_datatable' ];

						}else{
							$error = '<h4><strong>Same FEP</strong></h4>This device\'s FEP and the corresponding device in the approved FEP are the same.<br>They must be different to initiate an FEP Transfer Workflow';
						}
					}else{
						$error = '<h4><strong>Unable to Retrieve Device Id</strong></h4>This device ID cannot be found in the Approved Devices table';
					}

				}else{
					$error = '<h4><strong>Unable to Retrieve Device Id</strong></h4>';
				}

			break;
			case 'device_trail':
				$modal = 1;

				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();
				if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
					$data[ 'data' ] = $e;
				}else{
					$error = '<h4><strong>Unable to Retrieve Device Id</strong></h4>';
				}

			break;
			case 'new_device':

				$this->class_settings[ 'no_popup' ] = 1;
				$_GET[ 'nwp_mid' ] = 1;
				$_GET[ 'nwp_mid_id' ] = 'new-device-container';

				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_device';
				$this->class_settings["skip_link"] = 1;

				$this->class_settings["before_html"] = "<h4><b>Device Onboarding</b></h4>";

				$this->_generate_new_data_capture_form2();
				$h = $this->_new_popup_form();

				if( isset( $h[ 'html_replacement' ] ) && $h[ 'html_replacement' ] ){
					$html = $h[ 'html_replacement' ];
				}

			break;
			case 'gis_map':

				$from = isset( $_GET[ 'from' ] ) ? $_GET[ 'from' ] : "";
				if( $from && $id ){
					$data[ 'from' ] = $from;
					$data[ 'from_id' ] = $id;
				}

			break;
			case 'save_assign_enrolment_center_workflow':

				$center = isset( $_POST[ 'center' ] ) ? $_POST[ 'center' ] : '';
				$workflow = isset( $_GET[ 'workflow' ] ) ? $_GET[ 'workflow' ] : '';
				if( $center && $workflow ){
					
					$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'workflow' ] ."` = '". $workflow ."' ";
					$e = $this->_get_records();

					if( isset( $e[0][ 'id' ] ) && $e[0][ 'id' ] ){

						$efd = $e[0][ 'data' ] ? json_decode( $e[0][ 'data' ], 1 ) : [];
						$efd[ 'trail' ][] = [
							'date' => time(),
							'user' => $this->class_settings[ 'user_id' ],
							'status' => $e[0][ 'status' ],
							'action' => $this->class_settings[ 'action_to_perform' ],
							'note' => 'Assigned Enrolment Center: ' . get_name_of_referenced_record( array( 'id' => $center, 'table' => 'enrolment_center' ) ),
						];

						$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `". $this->table_fields[ 'center' ] ."` = '". $center ."', `". $this->table_fields[ 'data' ] ."` = '". json_encode( $efd ) ."' WHERE `".$this->table_name."`.`id` = '". $e[0][ 'id' ] ."' ";
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'UPDATE',
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						
						execute_sql_query($query_settings);
						
						$this->class_settings[ 'current_record_id' ] = 'pass_condition';
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						
						$this->class_settings[ 'cache_where' ] = " AND `".$this->table_name."`.`workflow` = '". $workflow ."' ";
						$this->class_settings[ 'cache_where_result' ] = 1;
						$this->_get_record();

						$error_container = '#modal-replacement-handle';
						$error = '<h4><strong>Enrolment Center Assigned Successfully</strong></h4>';
						$etype = 'success';
					}else{
						$error = '<h4><strong>Unable to Retrieve Device Details</strong></h4>';
					}

				}else{
					$error = '<h4><strong>Invalid Center</strong></h4>Please select a center';
				}

			break;
			case 'assign_enrolment_center_workflow':

				$modal = 1;

				$this->class_settings[ 'nwp_todo' ] = 'get_records';
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'workflow' ] ."` = '". $id ."' ";
				
				$ee = $this->_get_records();

				if( isset( $ee[0][ 'id' ] ) && $ee[0][ 'id' ] ){
					$data[ 'workflow' ] = $id;
					$data[ 'data' ] = $ee;
				}else{
					$error = '<h4><strong>Unable to Retrive Devices</strong></h4>';
				}

			break;
			case 'save_new_device':

				$this->class_settings[ 'use_selector' ] = 1;
				$this->class_settings[ 'action_to_perform' ] = 'save_new_popup';
				return $this->_save_app_changes2();

			break;
			case 'audit_config':

				$key = 'DEVICE_MGT_CONFIG';
				$pd = $this->_get_project_settings_data( [ 'reference_table' => $this->table_name, 'keys' => [ $key ] ] );

				if( isset( $pd[ strtoupper( $key ) ] ) && isset( $pd[ strtoupper( $key ) ][ 'value' ] ) ){
					$data[ 'settings' ] = json_decode( $pd[ strtoupper( $key ) ][ 'value' ], 1 );
				}

			break;
			case 'save_audit_config':
				$key = 'DEVICE_MGT_CONFIG';

				$ps = new cProject_settings;
				$ps->class_settings = $this->class_settings;

				$ps->class_settings[ 'current_record_id' ] = $key;
				$e = $ps->_get_record();

				$json = $_POST;

				if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
					$_POST[ 'id' ] = $key;
				}else{
					$_POST[ 'id' ] = '';
					$_POST[ 'tmp' ] = $key;
				}

				$ps->class_settings[ 'update_fields' ] = [
					'key' => $key,
					'value' => json_encode( $json ),
					'comment' => '',
					'reference' => '',
					'reference_table' => $this->table_name,
				];

				$r = $ps->_update_table_field();

				$this->_bg_execute( [ 'update_cache_only' => 1 ] );

				return [
					'status' => 'new-status',
					'html_replacement' => $r[ 'html' ],
					'html_replacement_selector' => '#'.$handle,
				];
			break;
			}

			if( $error ){
				$ex = array( "type" => $etype, "message" => $error );
				if( $error_container ){
					$ex[ 'do_not_display' ] = 1;
					$ex[ 'html_replacement_selector' ] = $error_container;
				}
				if( ! empty( $close_modal ) ){
					$ex[ 'callback' ] = $close_modal;
				}
				if( $manual_close ){
					$ex[ 'manual_close' ] = 1;
				}
				return $this->_display_notification( $ex );
			}

			$data[ 'params' ] = '&html_replacement_selector=' . $handle . $params;
							// print_r( $html );exit;
				
			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( 'plugins/' . $this->plugin . '/views/device_mgt/' . $filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			if( $modal ){
				return $this->_launch_popup( $html, "#" . $handle, $mjs );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $mjs,
			);

			if( isset( $returnData ) && $returnData ){
				$return[ 'data' ] = $returnData;
			}
			
			if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
				// $return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
				// $return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
			}
			return $return;
		}
		
		protected function _generate_new_data_capture_form2(){

			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'storage' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'os_name' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'os_version' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'processor_speed' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'avail_storage' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'ram' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'imsi' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'network_strength' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'network_type' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'latitude' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'longitude' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'capture_time' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'update_time' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'battery' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'camera' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'screen_size' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'screen_resolution' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'architecture' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'gps_module' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'fingerprint' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'otg' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'printer' ] ] =  1;
			// $this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'data' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'country_pings' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'state_pings' ] ] =  1;
			$this->class_settings[ 'hidden_records' ][ $this->table_fields[ 'lga_pings' ] ] =  1;

			$this->class_settings[ 'hidden_records_css' ][ $this->table_fields[ 'onboarded_type' ] ] = 1;
			$this->class_settings[ 'form_values_important' ][ $this->table_fields[ 'onboarded_type' ] ] = 'created';

			switch( $this->class_settings[ 'action_to_perform' ] ){
			case 'edit':
				$this->class_settings[ "hidden_records" ][ $this->table_fields["status"] ] = 1;
			break;
			}

			return $this->_generate_new_data_capture_form();
		}
		
		public function _import_callback( $o = array() ){
			$o1 = array();
			if( isset( $o['creator_role'] ) && $o['creator_role'] ){
				$where = " AND `".$this->table_name."`.`creator_role` = '". $o['creator_role'] ."' ";
				
				$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `onboarded_type` = 'migrated' WHERE `".$this->table_name."`.`record_status` = '1' " . $where;
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				execute_sql_query($query_settings);
				
			}
		}
		
		protected function _view_details2(){
			
			$filename = '';
				
			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = "#".$handle;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$error_msg = '';
			$hide_buttons = isset( $_GET[ 'hide_buttons' ] ) ? $_GET[ 'hide_buttons' ] : '';
			$e_type = 'error';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg = 'Invalid Reference';
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
			case 'view_details2':
				if( ! $error_msg ){
					$this->class_settings["current_record_id"] = $_POST[ 'id' ];
					$e = $this->_get_record();
					if( isset( $e["id"] ) ){
						
					}else{
						$error_msg = 'Unable to retrieve patient details';
					}
				}
			break;
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
				if( ! $error_msg ){
					$opt = array();
					if( isset( $_GET[ 'click_btn' ] ) && $_GET[ 'click_btn' ] )$opt[ 'click_btn' ] = $_GET[ 'click_btn' ];
					$id = $e['id'];
					$opt[ 'id' ] = $id;

					$ak = 'pings';
					$opt[ 'action_groups' ][ $ak ]['title'] = 'Device Pings';
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=frequent_changes&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&join_from=device',
						'title' => 'Frequent Changes',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=non_frequent_changes&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&join_from=device',
						'title' => 'Non-Frequent Changes',
					);
					
					$ak = 'heartbeatas';
					$opt[ 'action_groups' ][ $ak ]['title'] = 'View Other Data';
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_metadata&nwp_todo=display_all_records_others&hide_title=1&disable_btn=1&join_from=device',
						'title' => 'All Enrolment Data',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_center&nwp_todo=display_all_records_others&hide_title=1&disable_btn=1&join_from=device',
						'title' => 'All Enrolment Centers',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_agent&nwp_todo=display_all_records_others&hide_title=1&disable_btn=1&join_from=device',
						'title' => 'All Enrolment Custodians',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action='. $this->table_name .'&nwp_todo=view_map_device&hide_title=1&disable_btn=1&join_from=device',
						'title' => 'View Device on Map',
					);

					$opt[ 'general_actions' ][ 'view_device' ] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_agent&nwp_todo=view_details',
						'title' => 'View Current Custodian',
						'custom_id' => $e[ 'custodian' ],
					);

					$opt[ 'general_actions' ][ 'gcert' ] = array(
						'todo' => 'generate_cert',
						'title' => 'Generate Certificate',
					);

					$opt[ 'general_actions' ][ 'gkey' ] = array(
						'todo' => 'generate_key',
						'title' => 'Generate Key',
					);

					$opt[ 'hide_files' ] = 1;
					// $opt[ 'hide_comments' ] = 1;
					
					$opt[ 'plugin' ] = $this->plugin;
					$opt[ 'details_todo' ] = 'view_details';
					$opt[ 'title_text' ] = "Enrolment Device - <b>" . $e[ 'model' ] . '</b>';
					$_GET[ 'modal' ] = 1;

					$this->class_settings[ 'open_module' ] = $opt;

					//set_current_customer( array( "customer" => $id ) );
					return $this->_open_module();
				}
			break;
			case 'view_details2':
				$_GET["modal"] = 1;
				$filename = "view-details.php";
					
				if( ! $error_msg ){
					if( isset( $e["id"] ) ){
						$this->_apply_basic_data_control( $e );
						
						/* $stb = array();
						if( isset( $e["status2"] ) ){
							switch( $e["status2"] ){
							case "sent_to_nurse":
								$stb = array( 'an_vi' => 1 );
							break;
							}
						}
						if( empty( $stb ) ){
							$stb = array( 'of' => 1 );
						} 
						
						$this->class_settings[ 'data' ][ 'standalone_buttons' ] = $stb;
						*/
						
						//$this->class_settings[ 'data' ][ 'more_data' ][ 'additional_params' ] = '&reference=' . $e["id"] . '&reference_table=' . $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
						$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
						$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
						
						
						$this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
						$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
						
						$b["more_actions"] = $this->_get_html_view();
						$b["id"] = $e["id"];
						//$b["auth_data"] = $qd;
						
						$this->class_settings["data_items"] = array( $e["id"] => $e );
						$this->class_settings["other_params"] = $b;
					}else{
						$error_msg = 'Unable to Retrieve Record';
					}
				}
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			if( $hide_buttons )$this->class_settings[ 'data' ][ 'hide_buttons' ] = $hide_buttons;
			
			$this->class_settings[ 'data' ][ 'no_columns' ] = isset( $_GET["no_column"] )?$_GET["no_column"]:'';
			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
				if( $filename ){
					$this->class_settings["html_filename"] = array( 'plugins/' . $this->plugin . '/views/device_mgt/' . $filename );
				}
			}else{
				if( $filename ){
					$this->class_settings["html_filename"] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
			}
			
			$this->class_settings["modal_dialog_style"] = "width:40%;";
				
			$return = $this->_view_details();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_details2':
				if( isset( $return["html_replacement_selector"] ) && $return["html_replacement_selector"] ){
					$return["html_replacement_selector"] = $handle;
				}
			break;
			}
			
			return $return;
		}
		
		protected function _search_form2(){
			$where = "";
			
			foreach( array( 'customer', 'staff_responsible' ) as $key ){
				if( isset( $this->table_fields[ $key ] ) && isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
					//$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
					$where .= " AND `".$this->table_fields[ $key ]."` = '".$_POST[ $this->table_fields[ $key ] ]."' ";
				}
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
			$join = '';
			$disable_btn = 0;
			$spilt_screen = 1;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;
			
			$hide_title = isset( $_GET[ 'hide_title' ] ) ? $_GET[ 'hide_title' ] : '';
			$disable_btn = isset( $_GET[ 'disable_edit_buttons' ] ) ? $_GET[ 'disable_edit_buttons' ] : '';
			$show_form = isset( $_GET[ 'show_form' ] ) ? $_GET[ 'show_form' ] : $show_form;

			$type = ( isset( $_GET[ "type" ] ) ? $_GET[ "type" ] : '' );
			$status = ( isset( $_POST[ "status" ] ) ? $_POST[ "status" ] : ( isset( $_GET[ 'status' ] ) ? $_GET[ 'status' ] : '' ) );
			$partner = ( isset( $_GET[ "partner" ] ) ? $_GET[ "partner" ] : '' );
			$callback = ( isset( $_GET[ "callback" ] ) ? $_GET[ "callback" ] : '' );
			$child = ( isset( $_GET[ "child" ] ) ? $_GET[ "child" ] : '' );
			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';

			$s_where = "";
			$merged_workflow = 0;

			switch( $action_to_perform ){
			case 'existing_in_mis':
			case 'view_records_workflow':
				$extra = '';
				$this->class_settings[ 'full_table' ] = 1;

				if( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ){

					$this->class_settings['title'] = 1;
					$this->class_settings['hide_title'] = 1;
					$this->datatable_settings['show_add_new'] = 0;
					$this->datatable_settings['show_delete_button'] = 1;

					$id = $_POST[ "id" ];
					$this->class_settings[ "frontend" ] = 1;
					
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ "workflow" ] ."` = '". $id ."' " . $s_where;
							
					switch( $action_to_perform ){
					case 'existing_in_mis':				
						$this->datatable_settings['show_delete_button'] = 1;
						$extra = '&unset_revision_history=1';
						$join = " INNER JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $this->sr_table ."` t2 ON t2.`".$this->table_fields["workflow"]."` <> '". $id ."' AND t2.`". $this->table_fields[ 'household_id' ] ."` = `". $this->table_name ."`.`". $this->table_fields[ 'household_id' ] ."` ";
					
					break;
					}

					$this->class_settings[ 'data' ][ 'workflow_id' ] = $id;
				}else{
					return $this->_display_notification( array( "type" => "error", "message" => "Invalid Workflow" ) );
				}

				// print_r( $type );exit;
				
				switch( $type ){
				case 'return':
				case 'submit':
				case 'select':
					
					switch( $type ){
					case 'return':
						$this->datatable_settings[ 'utility_buttons' ][ 'return_workflow' ] = 1;
					break;
					case 'submit':
						$this->datatable_settings[ 'utility_buttons' ][ 'submit_workflow' ] = 1;
					break;
					case 'select':
						$this->datatable_settings[ 'show_selection' ]['action'] = '?action=nwp_workflow&todo=execute&nwp_action=workflow&nwp_todo=process_selection&id='. $id .'&html_replacement_selector=datatable-select-all';
						
						$this->datatable_settings[ 'show_selection' ]['button_label'] = 'Save and Confirm Action';
						$this->datatable_settings[ 'show_selection' ]['fields'] = array(
							"description" => array(
								'field_label' => 'Reason',
								'required_field' => 'yes',
								'form_field' => 'textarea',
								
								'display_position' => 'display-in-table-row',
								'serial_number' => 14.2,
							),
							"form_action" => array(
								'field_label' => 'Action to Perform',
								'required_field' => 'yes',
								'form_field' => 'radio',
								'form_field_options' => 'get_workflow_selection_action',
								
								'display_position' => 'display-in-table-row',
								'serial_number' => 14.1,
							),
							"supporting_document" => array(
								'field_label' => 'Attachment',
								'required_field' => 'no',
								'form_field' => 'file',
								'form_field_options' => '',
								
								'display_position' => 'display-in-table-row',
								'serial_number' => 14.1,
							),
						);

						// If returning to start, show a different set of options
						/*$w = new cNwp_workflow();
						$al = $w->load_class( [ 'class' => [ 'workflow' ], 'initialize' => 1 ] );
						$al[ 'workflow' ]->class_settings = $this->class_settings;
						$al[ 'workflow' ]->class_settings[ 'current_record_id' ] = $id;
						$cr = $al[ 'workflow' ]->_get_record();
						// print_r( $cr );exit;

						if( isset( $cr[ 'status' ] ) && $cr[ 'status' ] ){
							switch( $cr[ 'status' ] ){
							case 'record.households.crude_data_prep':
								$this->datatable_settings[ 'show_selection' ]['fields'][ 'form_action' ][ 'form_field_options' ] = 'get_workflow_selection_action2';
							break;
							}
						}*/
						
						$this->datatable_settings[ 'show_edit_button' ] = 0;
						$this->datatable_settings[ 'show_refresh_cache' ] = 0;
						$this->datatable_settings[ 'utility_buttons' ] = array( 'view_details' => 1 );
						
						unset( $this->class_settings[ "custom_edit_button" ] );
					break;
					}
					
				break;
				default:

				break;
				}
			break;
			case 'view_records_workflow2':
				$extra = '';
				$this->class_settings[ 'full_table' ] = 1;
				$spilt_screen = 1;
				
				$this->class_settings[ "frontend" ] = 1;
				$this->datatable_settings[ 'show_selection' ]['action'] = '?action=nwp_workflow&todo=execute&nwp_action=workflow&nwp_todo=process_selection&id='. $id .'&html_replacement_selector=datatable-select-all';
				
				if( $merged_workflow ){
					$this->datatable_settings[ 'show_selection' ]['form_title'] = '<div class="note note-warning"><h4><strong>This Workflow has Been Merged</strong></h4>Merged workflows can neither be submitted or returned in parts. Please unmerge to use this feature.</div>';
				}else{

					// print_r( $this->datatable_settings['show_selection'] );exit;
					$this->datatable_settings[ 'show_selection' ]['button_label'] = 'Save and Confirm Action';
					$this->datatable_settings[ 'show_selection' ]['fields'] = array(
						"description" => array(
							'field_label' => 'Reason',
							'required_field' => 'yes',
							'form_field' => 'textarea',
							
							'display_position' => 'display-in-table-row',
							'serial_number' => 14.2,
						),
						"form_action" => array(
							'field_label' => 'Action to Perform',
							'required_field' => 'yes',
							'form_field' => 'radio',
							'form_field_options' => 'get_workflow_selection_action',
							
							'display_position' => 'display-in-table-row',
							'serial_number' => 14.1,
						),
					);

					// If returning to start, show a different set of options
					/*$w = new cNwp_workflow();
					$al = $w->load_class( [ 'class' => [ 'workflow' ], 'initialize' => 1 ] );
					$al[ 'workflow' ]->class_settings = $this->class_settings;
					$al[ 'workflow' ]->class_settings[ 'current_record_id' ] = $id;
					$cr = $al[ 'workflow' ]->_get_record();
					// print_r( $cr );exit;

					if( isset( $cr[ 'status' ] ) && $cr[ 'status' ] ){
						switch( $cr[ 'status' ] ){
						case 'record.households.crude_data_prep':
							$this->datatable_settings[ 'show_selection' ]['fields'][ 'form_action' ][ 'form_field_options' ] = 'get_workflow_selection_action2';
						break;
						}
					}*/
				}
				
				$this->datatable_settings[ 'show_edit_button' ] = 1;
				$this->datatable_settings[ 'show_refresh_cache' ] = 0;
				$this->datatable_settings[ 'utility_buttons' ] = array( 'view_details' => 1 );
				
				unset( $this->class_settings[ "custom_edit_button" ] );
			break;
			case "display_all_records_full_view_search":
			case "display_all_records_frontend_history":
				$show_form = 1;
				unset( $this->class_settings[ "full_table" ] );
			break;
			case "display_all_records_frontend_flagged":
				
				$action_to_perform = 'display_all_records_frontend';
				$this->class_settings[ 'action_to_perform' ] = $action_to_perform;
				
				$status = "flagged";

			break;
			case "display_all_records_frontend_pending":
				
				$action_to_perform = 'display_all_records_frontend';
				$this->class_settings[ 'action_to_perform' ] = $action_to_perform;
				
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["status"]."` IN ( 'draft', 'pending', 'pending_nin', 'verified_nin' ) ";
				
			break;
			case "display_all_records_frontend":
				$spilt_screen = 1;
				if( $disable_btn ){
					$this->class_settings[ 'full_table' ] = 1;
				}
				$where .= " AND `". $this->table_fields[ 'status' ] ."` <> 'onboarded' ";
			break;
			}
			
			if( $partner ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["partner"]."` = '". $partner ."' ";
			}

			if( $status ){

				$this->basic_data[ 'datatable_filter' ][ 'status' ][ 'selected' ] = $status;
				if( ! ( isset( $this->class_settings["filter"]["where"] ) && $this->class_settings["filter"]["where"] ) )$this->class_settings["filter"]["where"] = '';

				$where .= " AND `".$this->table_name."`.`".$this->table_fields["status"]."` = '". $status ."' ";
				
			}

			$this->class_settings[ 'table_filter' ][ 'fields' ] = $this->basic_data[ 'datatable_filter' ];
			$this->class_settings[ 'table_filter' ][ 'form_action' ] = '?'.http_build_query( $_GET );
			
			unset( $this->basic_data['more_actions'] );

			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 1;
				$this->datatable_settings[ "show_delete_button" ] = 0;
			}
			
			if( $hide_title ){
				$this->class_settings[ "hide_title" ] = 1;
			}
			
			if( $join ){
				$this->class_settings[ 'filter' ][ 'join' ] = $join;
				$this->class_settings[ 'filter' ][ 'join_count' ] = $join;
			}
			
			if( $where ){
				$this->class_settings[ 'filter' ][ 'where' ] = $where;
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
					'action' => '?action='. $this->plugin .'&todo=execute&nwp_action=' . $this->table_name . '&nwp_todo=view_details2&no_column=1',
					'col' => 4,
					'content' => get_quick_view_default_message_settings(),
				);
			}
			
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2(){
			
			$ddx = array();
			$error = '';
			$e = array();
			$etype = 'error';
			$save_app = 1;
			$origin = isset( $_POST[ 'origin' ] ) ? $_POST[ 'origin' ] : '';

			parse_str( ltrim( $origin, '?' ), $originR );

			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'save_app_changes':
			break;
			}
			
			if( $error ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error ) );
			}
			
			switch( $save_app ){
			case 1:
				$return = $this->_save_app_changes();
			break;
			case 2:
				$return = $this->_save_changes();
			break;
			case 3:
				$return = $this->_update_table_field();
			break;
			case 4:
				$return = $this->_save_line_items();
				if( $return )$return = array( 'saved_record_id' => 1 );
			break;
			}
			
			// print_r( $return );exit;
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
				$container = "#".$container;
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				switch( $action_to_perform ){
				case 'save_app_changes':
				break;
				}
				
				if( isset( $originR[ 'nwp_todo' ] ) && $originR[ 'nwp_todo' ] && isset( $return[ 'record' ][ 'id' ] ) ){
					switch( $originR[ 'nwp_todo' ] ){
					case 'deactivate_device':
					case 'reactivate_device':

						$txt = 'Activated';
						switch( $originR[ 'nwp_todo' ] ){
						case 'deactivate_device':
							$txt = 'Deactivated';
						break;
						}
						$role = isset( $_SESSION[ 'reuse_settings' ][ 'user_cert' ][ 'table' ] ) ? $_SESSION[ 'reuse_settings' ][ 'user_cert' ][ 'table' ] : 'users';
						
						$dt = isset( $return[ 'record' ][ 'data' ] ) && $return[ 'record' ][ 'data' ] ? json_decode( $return[ 'record' ][ 'data' ], 1 ) : [];
						$dt[ $originR[ 'nwp_todo' ] ][] = [
							'user' => $this->class_settings[ 'user_id' ],
							'date' => time(),
							'privilege' => $this->class_settings[ 'priv_id' ],
							'role' => $role,
						];
						$dt[ 'trail' ][] = [
							'date' => time(),
							'user' => $this->class_settings[ 'user_id' ],
							'status' => $return[ 'record' ][ 'status' ],
							'action' => $originR[ 'nwp_todo' ],
							'info' => 'This device was '. $txt .'.<br>'. $return[ 'record' ][ 'status_msg' ],
						];

						$_POST[ 'id' ] = $return[ 'record' ][ 'id' ];
						$this->class_settings[ 'update_fields' ] = [ 'data' => json_encode( $dt ) ];
						$this->_update_table_field();

					break;
					}
				}

			}
			
			// Send notification to assigned persons
			switch( $action_to_perform ){
			case 'save_new_popup':
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$action_to_perform = $this->class_settings['action_to_perform'];
			//$this->class_settings["skip_link"] = 1;
			
			switch ( $action_to_perform ){
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				$this->class_settings[ "hidden_records" ][ $this->table_fields["status"] ] = 1;
			break;
			case 'reactivate_device':
				$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
				$role = isset( $_SESSION[ 'reuse_settings' ][ 'user_cert' ][ 'table' ] ) ? $_SESSION[ 'reuse_settings' ][ 'user_cert' ][ 'table' ] : 'users';

				if( $role !== 'users' ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$e = $this->_get_record();
					$dd = isset( $e[ 'data' ] ) && $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];

					if( isset( $dd[ 'deactivate_device' ] ) && $dd[ 'deactivate_device' ] ){
						$lastElement = end( $dd[ 'deactivate_device' ] );
						if( $lastElement[ 'role' ] == 'users' ){
							return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>Device was deactivated by a higher role. Please contact your system administrators" ) );
						}
					}
				}

			case 'deactivate_device':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Deactivate ' . $this->label;
				$this->class_settings["override_defaults"] = 1;

				$this->class_settings['form_submit_button'] = 'Deactivate Device &rarr;';
				
				foreach( $this->table_fields as $tk => $tv ){
					switch( $tk ){
					case 'status_msg':
						$this->class_settings[ 'form_values_important' ][ $tv ] = ' ';
						$this->class_settings[ 'form_extra_options' ][ $tv ][ 'required_field' ] = 'yes';
					break;
					case 'status':

						switch ( $action_to_perform ){
						case 'reactivate_device':
							// $this->class_settings["disable_form_element"][ $tv ] = ' disabled="disabled" ';
							$this->class_settings[ 'form_values_important' ][ $tv ] = 'active';
							$this->class_settings[ 'form_extra_options' ][ $tv ][ 'required_field' ] = 'yes';
						break;
						case 'deactivate_device':
							$stats = get_list_box_options( 'get_device_status', array( "return_type" => 2 ) );
							$ns = [];
							if( isset( $stats[ 'in-active' ] ) && $stats[ 'in-active' ] ){
								$ns[ 'in-active' ] = $stats[ 'in-active' ];
							}
							if( isset( $stats[ 'disabled' ] ) && $stats[ 'disabled' ] ){
								$ns[ 'disabled' ] = $stats[ 'disabled' ];
							}
							if( isset( $stats[ 'retired' ] ) && $stats[ 'retired' ] ){
								$ns[ 'retired' ] = $stats[ 'retired' ];
							}
							if( ! empty( $ns ) ){
								$result = implode(';', array_map(
								    function ($v, $k) { return sprintf("%s:%s", $k, $v); },
								    $ns,
								    array_keys($ns)
								));
								$this->class_settings[ 'form_extra_options' ][ $tv ][ 'required_field' ] = 'yes';
								$this->class_settings[ 'form_extra_options' ][ $tv ][ 'options' ] = $result;
								$this->class_settings[ 'form_extra_options' ][ $tv ][ 'form_field_options' ] = 'xxx';
							}
						break;
						}

					break;
					default:
						$this->class_settings[ "hidden_records" ][ $tv ] = 1;
					break;
					}
				}
			break;
			default:
			break;
			}
						
			if( ! ( isset( $this->class_settings['form_submit_button'] ) && $this->class_settings['form_submit_button'] ) ){
				$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			}
			return $this->_new_popup_form();
		}
		
		protected function _apply_basic_data_control( $e = array() ){
			
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}

			// print_r( $e );exit;
			$show_dev_fep_trf = 0;
			$show_dev_re_onboard = 0;
			$show_activate_dev = 0;

			switch( $e["sub_status"] ){
			case "diff_fep":
				$show_dev_fep_trf = 1;
			break;
			case "dup_fep":
				$show_dev_re_onboard = 1;
			break;
			}

			switch( $e["status"] ){
			case "disabled":
			case "retired":
			case "in-active":
				$show_activate_dev = 1;
			break;
			}

			if( ! $show_dev_fep_trf ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1'][ 'trf_workflow' ] );
			}

			if( ! $show_dev_re_onboard ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1'][ 're_onboard' ] );
			}

			if( ! $show_activate_dev ){
				unset( $this->basic_data['more_actions'][ 'reactivate' ] );
			}else{
				unset( $this->basic_data['more_actions'][ 'deactivate' ] );
			}
			

			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}			
	}
	
	function device_mgt(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/device_mgt.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/device_mgt.json' ), true );
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
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_center&nwp_todo=get_select2" minlength="0" ';
					
					
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
					
					$key = $return[ "fields" ][ "ward" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'ward';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'wards',
						'reference_plugin' => 'nwp_locations',
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
						'reference_plugin' => 'nwp_locations',
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
						'reference_plugin' => 'nwp_locations',
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
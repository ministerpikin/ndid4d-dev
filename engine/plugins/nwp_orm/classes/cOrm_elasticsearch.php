	<?php
/** from mike 06-oct-23
 * orm_elasticsearch Class
 *
 * @used in  				orm_elasticsearch Function
 * @created  				Bay4 Mike | 02:15 | 21-Jul-2023
 * @database table name   	orm_elasticsearch
 */

	$pxx = dirname( __FILE__ );
	$epath = '';
	if( defined( $pxx .'ES_VERSION_PATH' ) && $pxx . ES_VERSION_PATH ){
		$epath = $pxx .ES_VERSION_PATH; 
	}elseif( defined( 'ES_VERSION' ) && ES_VERSION ){
		$epath = ES_VERSION;
	}
	if( file_exists( $epath ) ){
		require $epath;
	}
	
	use Elastic\Elasticsearch\ClientBuilder;
	
    if( ! class_exists("Elastic\Elasticsearch\ClientBuilder" ) ){
		cNwp_orm::error( [ "title" => 'Invalid ES Settings', 'message' => 'Invalid ES_VERSION or ES_VERSION_PATH settings.<br><br> Unable to load ES Client in cOrm_elasticsearch', 'output' => true ] );
	}
	
	class cOrm_elasticsearch extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'orm_elasticsearch';
		
		public $default_reference = '';
		
		public $label = 'ORM Elastic Search';
		
		private $associated_cache_keys = array(
			'orm_elasticsearch',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		public static $esClient;
		public static $esClientError;
		public static $last_query_metadata = array();
		
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
			if( file_exists( dirname( __FILE__ ).'/dependencies/orm_elasticsearch.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/orm_elasticsearch.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}

			$username = "";
			if( defined( 'ES_USERNAME' ) && ES_USERNAME ){
				$username = ES_USERNAME;
			}
			$password = "";
			if( defined( 'ES_PASSWORD' ) && ES_PASSWORD ){
				$password = ES_PASSWORD;
			}

			self::$defaultLimit = defined("ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT") && ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT ? (int)ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT : self::$defaultLimit;

			try {
				if( $username && $password ){

					$client = ClientBuilder::create()
						->setBasicAuthentication( $username, $password );
				}else{
					$client = ClientBuilder::create();
				}

				$host = defined('ELASTIC_HOST') && ELASTIC_HOST ? ELASTIC_HOST : '';
				if( $host ){
					$client->setHosts([$host]);
				}

				if( defined('ELASTIC_SSL_DIR') && ELASTIC_SSL_DIR ){
					$client->setCABundle( __DIR__ . ELASTIC_SSL_DIR );
				}
				
				// print_r( $client );exit;
				cOrm_elasticsearch::$esClient = $client->build();
			} catch (Exception $e) {
				cOrm_elasticsearch::$esClientError = $e->getMessage();
				
			}
			// print_r(cOrm_elasticsearch::$esClient ); exit;
        }
	
		function orm_elasticsearch(){
			
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
			case 'display_all_records_frontend_history':
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
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
				$returned_value = $this->_new_popup_form2();
			break;
			case 'view_details2':
			case 'view_details':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'save_line_items':
				$returned_value = $this->_save_line_items();
			break;
			case 'load_query':
				$returned_value = $this->_load_query();
			break;
			case 'ping':
				$returned_value = $this->ping();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected $queryLabels = [];
		public static $defaultLimit = 10000;

		public function ping(){
			$filename = 'validate-bill-and-finalize-discharge';
			
			$params = '';
			$html = '';
			$error = '';
			$etype = 'error';

			$data = array();
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}

			if( cOrm_elasticsearch::$esClientError ){
				$error = cOrm_elasticsearch::$esClientError;
			}else{

				try {
					$response = cOrm_elasticsearch::$esClient->info();
				} catch (Exception $e) {
					$error = '<h4><strong>Error in ES Instance</strong></h4>'.$e->getMessage();
				}
				//print_r( $response->getStatusCode() ); exit;

				if( ! $error ){
					if( $response->getStatusCode() == 200 ){
						$error = '<h4><strong>Database Online</strong></h4>';
						$etype = 'success';
					}else{
						$error = '<h4><strong>Unable to Ping Reporting Server</strong></h4>';
					}
				}
			}

			if( $error ){
				$ex = array( "type" => $etype, "message" => $error );
				return $this->_display_notification( $ex );
			}

			$data[ 'params' ] = '&html_replacement_selector=' . $handle . $params;
				
			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( $this->view_path . $filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			$return = array(
				// 'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
			
			return $return;
		}

		public function _load_query( $settings = array() ){
			$error = '';

			if( gettype( cOrm_elasticsearch::$esClient ) !== 'object' ){
				$error = 'Undefined ElasticSearch Namespace. Please check credentials';
			}elseif( isset( $settings[ 'query_type' ] ) && $settings[ 'query_type' ] ){
				if( isset( $settings[ 'allLabels' ] ) && $settings[ 'allLabels' ] ){
					$this->queryLabels = $settings[ 'allLabels' ];
				}

				$ptable = '';
				$ec = array();
				switch( $settings[ 'query_type' ] ){
					case 'SELECT':
						$ec = array( 	'from' );
					break;
					case 'INSERT':
						$ec = array( 'values' );
					break;
				}

				if( ! empty( $ec ) ){
					foreach( $ec as $ee ){
						if( ! ( isset( $settings[ 'query' ][ $ee ] ) && is_array( $settings[ 'query' ][ $ee ] ) && ! empty( $settings[ 'query' ][ $ee ] ) ) ){
							$error = $ee . ' is invalid';
							break;
						}
					}
				}

				if( ! $error ){
					$r = array();
					switch( $settings[ 'query_type' ] ){
						case 'SELECT':
							$r = $this->_select( $settings );
						break;
						case 'INSERT':
							$r = $this->_insert( $settings );
						break;
						case 'CREATE_TABLE':
							$r = $this->_create_table( $settings );
						break;
					}

					if( isset( $r[ 'error' ] ) && $r[ 'error' ] ){
						$error = $r[ 'error' ];
					}else{
						return $r;
					}
				}
			}

			if( ! $error ){
				$error = 'Unknown Database Operation Error';
			}

			if( $error ){

				$audit_params["comment"] = $error;
				$audit_params["trace"] = array_column( debug_backtrace(), 'line', 'file' );
				auditor( "", "sql_error", $ptable, $audit_params );

				return array( 'error' => $error );
			}
		}

		protected function _select( $settings ){
			// echo '<pre>';print_r($settings['query']);echo '</pre>';
			$result = array();
			$params = array();

			$sSelect = [];
			$sWhere = array();
			$sJoin = '';

			$sAggr = array();
			$AllAggr = array();
			$adHoc = array();

			$aggr = '';

			$q = isset( $settings[ 'query' ] ) && is_array( $settings[ 'query' ] ) ? $settings[ 'query' ] : array();
			$sLimit = isset( $q['limit'][0] ) && $q['limit'][0] ? intval( $q['limit'][0] ) : 0;
			$sOffset = isset( $q['offset'][0] ) && $q['offset'][0] ? intval( $q['offset'][0] ) : 0;

			if( isset( $q[ 'columns' ] ) && ! empty( $q[ 'columns' ] ) ){
				$sels = array();
				foreach( $q[ 'columns' ] as $cv ){
					if( isset( $cv[ 'field' ] ) && $cv[ 'field' ] ){
						$sels[ ( isset( $cv[ 'alias' ] ) ? $cv[ 'alias' ] : $cv[ 'field' ] ) ] = $cv;
						if( isset( $cv[ 'aggregation' ] ) && $cv[ 'aggregation' ] && isset( $cv[ 'alias' ] ) && $cv[ 'alias' ] ){
							foreach( $cv[ 'aggregation' ] as $agv ){
								$adHoc[ 'aggr' ][ $cv[ 'alias' ] ] = [
									'type' => $agv,
									'field' => $cv[ 'field' ],
								];
								
								switch( $agv ){
									case 'percentage':
									break;
									case 'avg_resolve_days':
									case 'avg_resolve_hours':
										if( isset( $sels[ 'start_date' ][ 'field' ] ) && isset( $sels[ 'start_date' ][ 'field' ] ) ){

											$ddr = 60 * 60;
											switch( $agv ){
											case 'avg_resolve_days':
												$ddr = 24 * 60 * 60;
											break;
											}

											$sAggr[ $cv[ 'alias' ] ] = array(
												'avg' => array(
													'script' => [
														'source' => "((doc['". $sels[ 'end_date' ][ 'field' ] ."'].value.getMillis() - doc['". $sels[ 'start_date' ][ 'field' ] ."'].value.getMillis() ) / ". $ddr .")/1000"
													],
												)
											);
											$AllAggr[ $cv[ 'alias' ] ] = $cv[ 'alias' ];
										}
									break;
									default:
										switch( $agv ){
										case 'count':
											$agv = 'value_count';
										break;
										case 'group_cumulative':
											$agv = 'cardinality';
										break;
										}
										$sAggr[ $cv[ 'alias' ] ] = array(
											$agv => array(
												'field' => $cv[ 'field' ],
											)
										);
										if( isset( $sLimit ) && $sLimit ){
											switch ( $agv ) {
												case 'value_count':
												case 'group_cumulative':
												case 'sum':
												break;
												default:
													$sAggr[ $cv[ 'alias' ] ][ $agv ]['size'] = $sLimit;
												break;
											}
										}
										$AllAggr[ $cv[ 'alias' ] ] = $cv[ 'alias' ];
									break;
								}
								break;
							}
						}else{
							$sSelect[] = $cv[ 'field' ];
						}
					}
				}
				// Remove the counts cos the aggregation already has it
				if( ! empty( $sAggr ) ){
					foreach( $sAggr as $agk => &$agv ){
						if( isset( $agv[ 'count' ] ) && $agv[ 'count' ] ){
							if( isset( $q[ 'group' ] ) && ! empty( $q[ 'group' ] ) ){
								unset( $sAggr[ $agk ] );
								unset( $AllAggr[ $agk ] );
								continue;
							}else if( count( $sAggr ) > 1 ){
								unset( $sAggr[ $agk ] );
								unset( $AllAggr[ $agk ] );
								continue;
							}
						}
					}
					unset( $agv );
				}
				if( ! empty( $sSelect ) ){
					$params[ '_source' ] = $sSelect;
				}
			}

			if( isset( $q[ 'joins' ] ) && ! empty( $q[ 'joins' ] ) ){
				foreach( $q[ 'joins' ] as $cl ){
					if( isset( $cl[ 'database' ] ) && $cl[ 'database' ] && isset( $cl[ 'table' ] ) && $cl[ 'table' ] && isset( $cl[ 'on' ] ) && is_array( $cl[ 'on' ] ) && ! empty( $cl[ 'on' ] ) ){
						$jj = $this->_nested_where( $cl[ 'on' ] );

						if( $jj ){
							$jn = 'JOIN';
							if( isset( $cl[ 'type' ] ) && $cl[ 'type' ] ){
								$jn = $cl[ 'type' ];
							}
							$sJoin .= $jn . " `". $cl[ 'database' ] ."`.`". $cl[ 'table' ] ."` ON " . $jj;
						}
					}
				}
			}

			if( isset( $q[ 'from' ][ 'table' ] ) && $q[ 'from' ][ 'table' ] ){
				$params[ 'index' ] = $q[ 'from' ][ 'table' ];
			}

			if( isset( $q[ 'where' ] ) && ! empty( $q[ 'where' ] ) ){
				$sWhere = $this->_nested_where( $q[ 'where' ] );
			}

			$aggOrder = array();
			if( isset( $q[ 'group' ] ) && ! empty( $q[ 'group' ] ) ){
				$allAggr = $this->_nested_group( $q[ 'group' ], $sAggr, $sLimit );

				if( isset( $adHoc[ 'aggr' ] ) && $adHoc[ 'aggr' ] ){
					foreach( $adHoc[ 'aggr' ] as $agk => $agv ){
						switch( $agv[ 'type' ] ){
						case 'percentage':
							$abc = [
								'filter' => [
				                    'term' => [ 'record_status' => '1' ]
				                ]
							];
							if( ! empty( $sWhere ) ){
								$abc = [ 'filter' => $sWhere ];
								$sWhere = [];
							}
							$aggOrder = array_merge( [ $agk => $agk ], $aggOrder );
							if( ! empty( $allAggr ) ){
								$abc[ "aggs" ] = $allAggr;
							}
							$allAggr = [ $agk => $abc ];
						break;
						case 'daily_date':
						case 'weekly_date':
						case 'monthly_date':

							$cinterval = '';
							$cformat = '';

							switch( $agv[ 'type' ] ){
							case 'daily_date':
								$cinterval = 'day';
								$cformat = 'yyyy-MM-dd';
							break;
							case 'weekly_date':
								$cinterval = 'week';
								$cformat = "yyyy-'W'ww";
							break;
							case 'monthly_date':
								$cinterval = 'month';
								$cformat = "yyyy-MM";
							break;
							case 'yearly_date':
								$cinterval = 'year';
								$cformat = "yyyy";
							break;
							}

							$abc = [
								'date_histogram' => [
				                    'field' => $agv[ 'field' ],
				                    'calendar_interval' => $cinterval,
				                    'format' => $cformat,
				                    'min_doc_count' => 1
				                ]
							];
							$aggOrder = array_merge( [ $agk => $agk ], $aggOrder );
							switch ( $agv['type'] ) {
								case 'daily_date':
								case 'weekly_date':
								case 'monthly_date':
									$sze = 10;
									switch ( $agv['type'] ) {
										case 'daily_date':
											$sze = 30;
										break;
									}

									$sze = $sLimit ? $sLimit : $sze;

									$allAggr[ $cv['alias'] . '_bucket_sort' ] = [
										'bucket_sort' => [ 'size' => $sze, 'sort' => [ [ "_key" => [ "order" => "desc" ] ] ] ]
									];
								break;
							}

							$abc[ "aggs" ] = $allAggr;
							$allAggr = [ $agk => $abc ];
						break;
						}
					}
				}

				foreach( $q[ 'group' ] as $gg ){
					if( isset( $gg[ 'field' ] ) && $gg[ 'field' ] ){
						$aggOrder[ $gg[ 'field' ] ] = 'group_'.( isset( $gg[ 'alias' ] ) ? $gg[ 'alias' ] : $gg[ 'field' ] );
					}
				}

				if( ! empty( $sAggr ) ){
					foreach( $sAggr as $ak => $av ){
						unset( $aggOrder[ $ak ] );
						$aggOrder[ $ak ] = $ak;
					}
				}

				$params[ 'body' ][ 'aggs' ] = $allAggr;
			}else{
				if( isset( $adHoc[ 'aggr' ] ) && $adHoc[ 'aggr' ] ){
					foreach( $adHoc[ 'aggr' ] as $agk => $agv ){
						switch( $agv[ 'type' ] ){
						case 'percentage':
							$abc = [
								'filter' => [ 'term' => [ 'record_status' => '1' ] ]
							];
							if( ! empty( $sWhere ) ){
								$abc = [ 'filter' => $sWhere ];
								$sWhere = [];
							}
							$ta = $sAggr;
							$sAggr = [ $agk => $abc ];
							$aggOrder = array_merge( [ $agk => $agk ], $aggOrder );
							if( ! empty( $ta ) ){
								$sAggr[ 'aggs' ] = $ta;
								$ta = [];
							}
						break;
						case 'daily_date':
						case 'weekly_date':
						case 'monthly_date':

							$cinterval = '';
							$cformat = '';

							switch( $agv[ 'type' ] ){
							case 'daily_date':
								$cinterval = 'day';
								$cformat = 'yyyy-MM-dd';
							break;
							case 'weekly_date':
								$cinterval = 'week';
								$cformat = "yyyy-'W'ww";
							break;
							case 'monthly_date':
								$cinterval = 'month';
								$cformat = "yyyy-MM";
							break;
							case 'yearly_date':
								$cinterval = 'year';
								$cformat = "yyyy";
							break;
							}

							$abc = [
								'date_histogram' => [
				                    'field' => $agv[ 'field' ],
				                    'calendar_interval' => $cinterval,
				                    'format' => $cformat,
				                    'min_doc_count' => 1
				                ]
							];
							$aggOrder = array_merge( [ $agk => $agk ], $aggOrder );
							// $abc[ "aggs" ] = $sAggr;
							switch ( $agv['type'] ) {
								case 'daily_date':
								case 'weekly_date':
								case 'monthly_date':
									$sze = 12;
									switch ( $agv['type'] ) {
										case 'daily_date':
											$sze = 30;
										break;
									}
									$sze = $sLimit ? $sLimit : $sze;
									$abc['aggs'][ $cv['alias'] . '_bucket_sort' ] = [
										'bucket_sort' => [ 'size' => $sze, 'sort' => [ [ "_key" => [ "order" => "desc" ] ] ] ]
									];
								break;
							}

							$sAggr = [
								$agk => $abc,
							];
						break;
						}
					}
				}

				if( ! empty( $AllAggr ) ){
					foreach( $AllAggr as $qg ){
						$aggOrder[ $qg ] = $qg;
					}
				}
				if( ! empty( $sAggr ) ){
					$params[ 'body' ][ 'aggs' ] = $sAggr;
				}
			}
				
			if( count( $aggOrder ) > 1 ){
				unset( $aggOrder[ 'count' ] );
			}
				
			if( ! empty( $sWhere ) ){
				$params[ 'body' ][ 'query' ] = $sWhere;
			}

			if( isset( $q[ 'order' ][ 'field' ] ) && $q[ 'order' ][ 'field' ] ){
				$params[ 'sort' ][ $q[ 'order' ][ 'field' ] ] = [];
				if( isset( $q[ 'order' ][ 'sort' ] ) && in_array( strtolower( $q[ 'order' ][ 'sort' ] ), array( 'asc', 'desc' ) ) ){
					$params[ 'sort' ][ $q[ 'order' ][ 'field' ] ] = [ 'order' => $q[ 'order' ][ 'sort' ] ];
				}
			}

			if( isset( $q[ 'limit' ][ 'max' ] ) && $q[ 'limit' ][ 'max' ] ){
				$params[ 'size' ] = $q[ 'limit' ][ 'max' ];
				if( isset( $q[ 'limit' ][ 'min' ] ) && $q[ 'limit' ][ 'min' ] ){
					$params[ 'from' ] = $q[ 'limit' ][ 'min' ];
				}
			}else{
				if( isset( $params[ 'body' ][ 'aggs' ] ) && $params[ 'body' ][ 'aggs' ] ){
					$params[ 'size' ] = 0;
				}else{
					$params[ 'size' ] = $sLimit ? $sLimit : self::$defaultLimit;
				}
			}
			if( isset( $sOffset ) && $sOffset ){
				$params[ 'from' ] = $sOffset;
			}

			if( isset( $q['scroll'] ) && $q['scroll'] ){
				$params[ 'scroll' ] = $q['scroll'];
			}
			// echo "<pre>";
			// print_r( $q );
			// print_r( $params );exit;

			if( ( isset( $params[ 'body' ] ) && ! empty( $params[ 'body' ] ) ) || ( isset( $params[ '_source' ] ) && ! empty( $params[ '_source' ] ) )  ){
				$params[ 'track_total_hits' ] = true;
				if( ! isset( $params[ 'size' ] ) ){
					$params[ 'size' ] = 0;
				}

				$this->last_query = $params;
				// print_r( $params );

				//Clean SQL QUERY
				$err = '';
				try{
					$rq = cOrm_elasticsearch::$esClient->search( $params );
				}catch( BadRequest400Exception $e ){
				    // Handle bad request exception
				    $err = $e->getMessage();
				}catch( \Exception $e ){
				    // Handle other exceptions
				    $err = $e->getMessage();
				}

				if( $err ){
					// echo $err . "\n\n";
					if( ! ( isset( $settings['skip_log'] ) && $settings['skip_log'] ) ){
						return array( 'error' => $err );
					}
				}else{

					// print_r( $rq['hits'] );exit;
					if( isset( $rq['hits'][ 'hits' ] ) && $rq['hits'][ 'hits' ] ){
						cOrm_elasticsearch::$last_query_metadata['took'] = isset($rq['took']) ? $rq['took'] : '';
						cOrm_elasticsearch::$last_query_metadata['timed_out'] = isset($rq['timed_out']) ? $rq['timed_out'] : '';
						cOrm_elasticsearch::$last_query_metadata['_shards'] = isset($rq['_shards']) ? $rq['_shards'] : '';
						if( isset( $rq['hits']['total'] ) && $rq['hits']['total'] ){
							cOrm_elasticsearch::$last_query_metadata['hits']['total'] = $rq['hits']['total'];
						} 
						if( isset( $rq['hits']['max_score'] ) && $rq['hits']['max_score'] ){
							cOrm_elasticsearch::$last_query_metadata['hits']['max_score'] = $rq['hits']['max_score'];
						} 
						if( isset( $q['scroll'] ) && $q['scroll'] ){
							cOrm_elasticsearch::$last_query_metadata['_scroll_id'] = isset($rq['_scroll_id']) ? $rq['_scroll_id'] : '';
						}
						if( ! ( isset( $rq[ 'aggregations' ] ) && $rq[ 'aggregations' ] ) ){
							$result = array_column( $rq['hits'][ 'hits' ], '_source' );

							$result = $this->_replaceAlias( $result, $q );
							// check for alias and transform
						}
					}

					if( isset( $rq['aggregations'] ) && $rq['aggregations'] ){
						// print_r( '----------------' );
						// print_r( $aggOrder );
						// echo '<pre>';
						// print_r( $rq['hits'] );
						// print_r($q);
						// print_r( $rq[ 'aggregations' ] );
						// print_r( $aggOrder );
						$result = $this->_transformArray( $rq[ 'aggregations' ], ( isset( $rq[ 'hits' ] ) ? $rq[ 'hits' ] : [] ), array_values( $aggOrder ), $adHoc );
						// print_r($result);exit;
						// echo "string";
						// print_r( '----------------' );
						// print_r( $rq[ 'aggregations' ] );
						// print_r( $result );exit;
					}
				}
			}

			return (array) $result;
		}
		
		protected function _replaceAlias( $d, $params ){

			if( isset( $params[ 'columns' ][0] ) && $params[ 'columns' ][0] ){
				$ref = [];
				foreach( $params[ 'columns' ] as $ck => $cv ){
					if( isset( $cv[ 'alias' ] ) && $cv[ 'alias' ] ){
						$ref[ $cv[ 'field' ] ] = $cv[ 'alias' ];
					}
				}

				if( ! empty( $ref ) ){
					foreach( $d as &$dd ){
						foreach( $ref as $rk => $rv ){
							if( isset( $dd[ $rk ] ) && $dd[ $rk ] && $rk != $rv ){
								$dd[ $rv ] = $dd[ $rk ];
								unset( $dd[ $rk ] );
							}
						}
					}
				}
			}

			return $d;
		}

		protected function _transformArray( $d, $hits, $aggregates, $oParams, $index = 0, $previousData = [] ){
			// print_r( $aggregates );
			// print_r( $d );
		    $result = [];

	        if (empty($aggregates)) {
	            // If all nested loops are completed, return the final result
	            $result[] = $previousData;
	            return $result;
	        }
	        $agr = $aggregates;

	        $currentAggregate = '';
	        foreach( $aggregates as $aak => $aag ){
	        	if( isset( $d[ $aag ] ) && $d[ $aag ] ){
			        $currentAggregate = $aag;
	        		unset( $aggregates[ $aak ] );
	        		break;
	        	}
	        }

       		$tot = isset( $hits[ 'total' ][ 'value' ] ) ? doubleval( $hits[ 'total' ][ 'value' ] ) : 0;
        	if( isset( $oParams[ 'itotal' ] ) && $oParams[ 'itotal' ] ){
	        	$tot = $oParams[ 'itotal' ];
        	}elseif( isset( $d[$currentAggregate]['buckets'] ) && $d[$currentAggregate]['buckets'] ){
	        	$tot = array_reduce( $d[$currentAggregate]['buckets'], function ($carry, $item) {
	        	    return $carry + $item['doc_count'];
	        	}, 0);
	        	$oParams[ 'itotal' ] = $tot;
        	}

	        $currentData = isset( $d[$currentAggregate]['buckets'] ) ? $d[$currentAggregate]['buckets'] : array();
	        if( empty( $currentData ) && ! empty( $agr ) ){
	        	foreach( $agr as $ax ){
	        		$xky = 'group_' . $ax;
	        		if( isset( $d[ $currentAggregate ][ $ax ] ) && ! empty( $d[ $currentAggregate ][ $ax ] ) ){
				        $currentData = [
				        	[
				        		'key' => 'kk',
				        		'doc_count' => isset( $d[ $currentAggregate ][ 'doc_count' ] ) ? $d[ $currentAggregate ][ 'doc_count' ] : '',
				        		$ax => $d[ $currentAggregate ][ $ax ],
				        	]
				        ];
				        break;
	        		}else if( isset( $d[ $currentAggregate ][ $xky ] ) && $d[ $currentAggregate ][ $xky ] ){
				        $currentData = [
				        	[
				        		'key' => 'kk',
				        		'doc_count' => isset( $d[ $currentAggregate ][ 'doc_count' ] ) ? $d[ $currentAggregate ][ 'doc_count' ] : '',
				        		$xky => $d[ $currentAggregate ][ $xky ],
				        	]
				        ];
				        break;
	        		}
	        	}
	        }

	        if( ! empty( $currentData ) ){
		        foreach( $currentData as $data ){
		            $currentBucket = [];
	            	$kk = str_replace( 'group_', '', $currentAggregate );

		            // If this is not the last loop, recursively call the function to process the next level
		            if (count( $aggregates ) > 0 ) {
		            	$kval = $data['key'];
		            	if( isset( $data[ 'key_as_string' ] ) && $data[ 'key_as_string' ] ){
			            	$kval = $data[ 'key_as_string' ];
		            	}
		                $currentBucket[ $kk ] = $kval;
		                $nextData = $this->_transformArray( $data, $hits, $aggregates, $oParams, $index + 1, $currentBucket );
		                foreach ($nextData as $nestedData) {
		                    $result[] = array_merge($previousData, $nestedData);
		                }
		            } else {
		            	$kval = $data['key'];
		            	if( isset( $data[ 'key_as_string' ] ) && $data[ 'key_as_string' ] ){
			            	$kval = $data[ 'key_as_string' ];
		            	}

		                // If this is the last loop, directly access the required values and append them to the result
		                $currentBucket['count'] = $data['doc_count'];
		                $currentBucket[$kk] = $kval;

		                if( isset( $oParams[ 'aggr' ] ) && $oParams[ 'aggr' ] ){
		                	foreach( $oParams[ 'aggr' ] as $ak => $ao ){
			                	switch( $ao[ 'type' ] ){
			                	case 'percentage':
			                		if( $tot ){
						                $currentBucket[ $ak ] = number_format( ( doubleval( $currentBucket[ 'count' ] ) / $tot ) * 100, 2 );
			                		}
			                	break;
								case 'sum':
									if( $tot && isset( $data[ $ak ][ 'value' ] ) ){
										$currentBucket[ $ak ] = doubleval( $data[ $ak ][ 'value' ] );
									}
								break;
			                	}
		                	}
		                }

		                $result[] = array_merge( $previousData, $currentBucket );
		            }
		        }
	        }else if( isset( $d[ $currentAggregate ][ 'value' ] ) && $d[ $currentAggregate ][ 'value' ] ){
            	$kk = $currentAggregate;

	            $currentBucket = [];
                $currentBucket[ $kk ] = $d[ $currentAggregate ][ 'value' ];

                if( isset( $oParams[ 'aggr' ] ) && $oParams[ 'aggr' ] ){
                	foreach( $oParams[ 'aggr' ] as $ak => $ao ){
	                	switch( $ao[ 'type' ] ){
	                	case 'percentage':
	                		if( $tot ){
				                $currentBucket[ $ak ] = number_format( ( doubleval( $d[ $currentAggregate ][ 'value' ] ) / $tot ) * 100, 2 );
	                		}
	                	break;
	                	}
                	}
                }

	            if( isset( $d['doc_count'] ) && $d['doc_count'] ){
	                $currentBucket['count'] = $d['doc_count'];
	            }

                $result[] = array_merge( $previousData, $currentBucket );

	        }else if( isset( $d[ $currentAggregate ][ 'doc_count' ] ) && $d[ $currentAggregate ][ 'doc_count' ] ){

	            $currentBucket = [];
                $currentBucket[ $currentAggregate ] = $d[ $currentAggregate ][ 'doc_count' ];

                if( isset( $oParams[ 'aggr' ] ) && $oParams[ 'aggr' ] ){
                	foreach( $oParams[ 'aggr' ] as $ak => $ao ){
	                	switch( $ao[ 'type' ] ){
	                	case 'percentage':
	                		if( $tot ){
				                $currentBucket[ $ak ] = number_format( ( doubleval( $d[ $currentAggregate ][ 'doc_count' ] ) / $tot ) * 100, 2 );
	                		}
	                	break;
	                	}
                	}
                }

                $result[] = array_merge( $previousData, $currentBucket );

	        }

	        return $result;
		}

		protected function _nested_group( $opt, $aggr, $sLimit ){
		    $aggs = [];
		    if( ! empty( $opt ) ){
			    $currentAgg = &$aggs;

			    $sn = 0;
		        foreach( $opt as $groupInfo ){
				    $sn++;
		        	if( isset( $groupInfo[ 'field' ] ) && $groupInfo[ 'field' ] ){
		                $fieldName = $groupInfo[ 'field' ];
		                $f2 = $fieldName;
		                if( isset( $groupInfo[ 'alias' ] ) && $groupInfo[ 'alias' ] ){
			                $f2 = $groupInfo[ 'alias' ];
		                }
		                $groupKey = 'group_' . $f2;

		                $currentAgg[ $groupKey ][ 'terms' ] = [
		                    'field' => $fieldName
		                ];
						if( $sLimit && $sn == 1 ){
							$currentAgg[ $groupKey ]['terms']['size'] = $sLimit;
						}
		                if( $sn !== count( $opt ) ){
			                $currentAgg = &$currentAgg[ $groupKey ][ 'aggs' ];
		                }elseif( $sn == count( $opt ) ){
		                	if( ! empty( $aggr ) ){
				                $currentAgg[ $groupKey ][ 'aggs' ] = $aggr;
		                	}
		                }
		        	}
	            }
		    }else{
		    	if( ! empty( $aggr ) ){
		    		// $aggs = $aggr;
		    	}
		    }

		    return $aggs;
		}

		protected function _nested_where( $opt = array(), $condition = '' ){
			$w1 = [];
			if( is_array( $opt ) && ! empty( $opt ) ){
				$xn = 0;
				ksort( $opt );
				if( isset( $opt[ 'or' ] ) && isset( $opt[ 'and' ] ) && count( $opt[ 'and' ] ) == 1 && count( $opt[ 'or' ] ) == 1 ){
					$opt[ 'or' ][] = $opt[ 'and' ];
					unset( $opt[ 'and' ] );
				}

				foreach( $opt as $cdtn => $cv ){
					$codtn = $cdtn;
					$pre_codtn = 'bool';
					$add = [];
					switch( $cdtn ){
					case 'and':
						$codtn = 'must';
					break;
					case 'or':
						$codtn = 'should';
						$add[ 'minimum_should_match' ] = 1;
					break;
					}

					$sn = 0;
					$w2 = [];

					foreach( $cv as $ck => $cvl ){
						if( isset( $cvl[ 'and' ][0][ 'key' ] ) || isset( $cvl[ 'or' ][0][ 'key' ] ) ){
							$w2[] = $this->_nested_where( $cvl, '' );
						}elseif( isset( $cvl[0][ 'key' ] ) || isset( $cvl[0][ 'key' ])){
							$w2[] = $this->_nested_where( [ $cdtn => $cvl ], '' );
						}else{
							if( isset( $cvl[ 'key' ] ) && isset( $cvl[ 'condition' ] ) && $cvl[ 'condition' ] ){
								$sn++;
								$ky = "";
								$vy = "";
								$cdd = "";
								$ftype = '';
								$cvl[ 'condition' ] = trim( $cvl[ 'condition' ] );

								switch( $cvl[ 'condition' ] ){
								case '=':
									$cdd = "term";
								break;
								case 'IN':
									$cdd = "terms";
								break;
								case '>':
								case '>=':
								case '<':
								case '<=':
								case 'BETWEEN':
								case 'between_relative':
									$cdd = "range";
								break;
								case "regexp":
									$cdd = "wildcard";
								break;
								}

								if( isset( $cvl[ 'value' ][ 'field' ] ) && $cvl[ 'value' ][ 'field' ] ){
									$cdd = "script";
								}

								if( isset( $cvl[ 'value' ] ) && is_array( $cvl[ 'value' ] ) && ! empty( $cvl[ 'value' ] ) ){
									$cdd = "terms";
								}

								if( isset( $cvl[ 'key' ][ 'table' ] ) && $cvl[ 'key' ][ 'table' ] && isset( $cvl[ 'key' ][ 'field' ] ) && $cvl[ 'key' ][ 'field' ] ){
									$ky = $cvl[ 'key' ][ 'field' ];

									if( isset( $this->queryLabels[ $cvl[ 'key' ][ 'table' ] ][ $cvl[ 'key' ][ 'field' ] ][ 'form_field' ] ) && $this->queryLabels[ $cvl[ 'key' ][ 'table' ] ][ $cvl[ 'key' ][ 'field' ] ][ 'form_field' ] ){
										$ftype = $this->queryLabels[ $cvl[ 'key' ][ 'table' ] ][ $cvl[ 'key' ][ 'field' ] ][ 'form_field' ];
									}
								}elseif( isset( $cvl[ 'value' ] ) ){
									$ky = $cvl[ 'value' ];
								}

								switch( $cvl[ 'condition' ] ){
								case '>':
								case '>=':
								case '<':
								case '<=':
								case 'between_relative':
								case 'BETWEEN':

									$cnx = $cvl[ 'condition' ];
									switch( $cvl[ 'condition' ] ){
									case '>':
										$cnx = 'gt';
									break;
									case '>=':
										$cnx = 'gte';
									break;
									case '<':
										$cnx = 'lt';
									break;
									case '<=':
										$cnx = 'lte';
									break;
									}

									if( isset( $cvl[ 'min' ] ) && isset( $cvl[ 'max' ] ) ){
										$mx = $cvl[ 'max' ];
										$mn = $cvl[ 'min' ];

										$isdate = 0;

										switch( $ftype ){
										case 'date-5':
										case 'date-5time':
											$isdate = 1;
											// $mx = date( 'Y-m-d\TH:i:s', doubleval( $cvl[ 'max' ] ) );
											// $mn = date( 'Y-m-d\TH:i:s', doubleval( $cvl[ 'min' ] ) );
										break;
										default:
											switch( $cvl[ 'key' ][ 'field' ] ){
											case 'creation_date':
											case 'modification_date':
												$isdate = 1;
											break;
											}
										break;
										}

										if( $isdate ){
											$mx = intval( $cvl[ 'max' ] ) * 1000;
											$mn = intval( $cvl[ 'min' ] ) * 1000;
										}

										// print_r( $cvl[ 'key' ][ 'field' ] );
										// print_r( $this->queryLabels[ $cvl[ 'key' ][ 'table' ] ][ $cvl[ 'key' ][ 'field' ] ] );exit;
										$vy = [
											'gte' => $mn,
											'lte' => $mx,
										];
									}elseif( isset( $cvl[ 'from_type' ] ) || isset( $cvl[ 'to_type' ] ) ){
										$vy = [];
										$invert = 0;
										if( isset( $cvl[ 'invert' ] ) && $cvl[ 'invert' ] ){
											$invert = 1;
										}

										if( isset( $cvl[ 'from_type' ] ) && $cvl[ 'from_type' ] && isset( $cvl[ 'from_value' ] ) && $cvl[ 'from_value' ] ){
											$cdvl = 'gte';
											if( $invert ){
												$cdvl = 'lt';
											}
											$vy[ $cdvl ] = "now-". $cvl[ 'from_value' ] . $cvl[ 'from_type' ] ."/". $cvl[ 'from_type' ] ."";
										}
										$cdvl = 'lte';
										if( $invert ){
											$cdvl = 'gt';
										}
										if( isset( $cvl[ 'to_type' ] ) && $cvl[ 'to_type' ] && isset( $cvl[ 'to_value' ] ) && $cvl[ 'to_value' ] ){
											$vy[ $cdvl ] = "now+". $cvl[ 'to_value' ] . $cvl[ 'to_type' ] ."/". $cvl[ 'to_type' ] ."";
										}else{
											if( ! $invert ){
												$vy[ $cdvl ] = "now";
											}
										}
									}elseif( isset( $cvl[ 'value' ][ 'table' ] ) && $cvl[ 'value' ][ 'table' ] && isset( $cvl[ 'value' ][ 'field' ] ) && $cvl[ 'value' ][ 'field' ] ){

										$dc = " doc['". $ky ."'].value ". $cvl[ 'condition' ] ." doc['". $cvl[ 'value' ][ 'field' ] ."'].value ";
										$ky = 'script';
										$vy = [
											'source' => $dc,
										];
									}elseif( isset( $cvl[ 'value' ] ) ){

										$vl = $cvl[ 'value' ];
										switch( $ftype ){
										case 'date-5':
										case 'date-5time':
											// $vl = date( 'Y-m-d\TH:i:s.u\Z', doubleval( $cvl[ 'value' ] ) );
										break;
										}

										$vy = [
											$cnx => $vl,
										];
									}

								break;
								case 'NOT IN':
									if( isset( $cvl[ 'value' ] ) && is_array( $cvl[ 'value' ] ) && ! empty( $cvl[ 'value' ] ) ){
										$vy = [
											"terms" => [
												$ky => $cvl[ 'value' ]
											]
										];
										$cdd = "bool";
										$ky = 'must_not';
									}
								break;
								case 'NOT NULL':
									$vy = [
										"exists" => [
											"field" => $ky
										]
									];
									$cdd = "bool";
									$ky = 'should';
								break;
								case 'regexp':
									$vy = [
										'value' => '*'.$cvl[ 'value' ].'*',
										'case_insensitive' => true
									];
								break;
								case '<>':
									$vy = [
										"term" => [
											$ky => $cvl[ 'value' ]
										]
									];
									$cdd = "bool";
									$ky = 'must_not';
								break;
								default:
									if( isset( $cvl[ 'value' ][ 'table' ] ) && $cvl[ 'value' ][ 'table' ] && isset( $cvl[ 'value' ][ 'field' ] ) && $cvl[ 'value' ][ 'field' ] ){
										$dc = " doc['". $ky ."'].value ". $cvl[ 'condition' ] ." doc['". $cvl[ 'value' ][ 'field' ] ."'].value ";
										$ky = 'script';
										$vy = [
											'source' => $dc,
										];
									}elseif( isset( $cvl[ 'value' ] ) ){
										$vy = $cvl[ 'value' ];
									}
								break;
								}

								if( $ky && $vy ){
									$w2[][ $cdd ][ $ky ] = $vy;
								}
							}
						}
					}

					switch( $cdtn ){
					case 'or':
						if( isset( $w1[ $pre_codtn ][ 'must' ] ) && $w1[ $pre_codtn ][ 'must' ] ){
							$wadd[ $pre_codtn ][ $codtn ] = $w2;
							$wadd[ $pre_codtn ] = array_merge( $wadd[ $pre_codtn ], $add );
							$w1[ $pre_codtn ][ 'must' ][] = $wadd;
						}else{
							$w1[ $pre_codtn ][ $codtn ] = $w2;
							$w1[ $pre_codtn ] = array_merge( $w1[ $pre_codtn ], $add );
						}
					break;
					default:
						$w1[ $pre_codtn ][ $codtn ] = $w2;
						$w1[ $pre_codtn ] = array_merge( $w1[ $pre_codtn ], $add );
					break;
					}

					$xn++;
				}
			}
			return $w1;
		}
		
		protected function _insert( $settings ){
			$error = '';

			if( isset( $settings[ 'tables' ][0] ) && is_array( $settings[ 'query' ][ 'values' ] ) && ! empty( $settings[ 'query' ][ 'values' ] ) ){
				$tb = $settings[ 'tables' ][0];
				if( ! isset( $settings[ 'query' ][ 'values' ][0] ) ){
					$settings[ 'query' ][ 'values' ] = array( $settings[ 'query' ][ 'values' ] );
				}

				// Build the bulk request
				$params = [ 'body' => [] ];
				foreach( $settings[ 'query' ][ 'values' ] as $doc ){
				    $params['body'][] = [
				        'index' => [
				            '_index' => $tb
				        ]
				    ];
				    $params['body'][] = $doc;
				}

				// Index the documents
				$response = cOrm_elasticsearch::$esClient->bulk( $params );

				if( isset( $response[ 'errors' ] ) && $response[ 'errors' ] ){
					$error = 'Error Inserting document(s)';
				}else{
					return $response;
				}
				// print_r( $response['items'] );
				// print_r( $response );exit;


			}else{
				$error = 'Missing Parameter to Index Record';
			}

			if( ! $error ){
				$error = 'Unknown Indexing Error';
			}

			return array( 'error' => $error );
		}
		
		protected function _create_table( $settings ){
			$error = '';

			if( isset( $settings[ 'query' ][ 'labels' ] ) && is_array( $settings[ 'query' ][ 'labels' ] ) && ! empty( $settings[ 'query' ][ 'labels' ] ) && isset( $settings[ 'tables' ][0] ) && $settings[ 'tables' ][0] ){
			    
			    $params = [
			        'index' => $settings[ 'tables' ][0]
			    ];

			    // check if index exists and delete
			    $exists = cOrm_elasticsearch::$esClient->indices()->exists($params)->asBool();

			    if( $exists ){
			    	$response = cOrm_elasticsearch::$esClient->indices()->delete( $params );
			    }

			    $params[ 'body' ][ 'mappings' ]['properties'][ 'id' ] = [ 'type' => 'keyword' ];

				foreach( $settings[ 'query' ][ 'labels' ] as $fieldKey => $field ){

					$fieldType = isset( $field[ 'form_field' ] ) ? $field[ 'form_field' ] : '';
				    $fieldMapping = [];

				    switch( $fieldType ){
			        case 'email':
			        case 'checkbox':
			        case 'select':
			        case 'radio':
			        case 'calculated':
			        case 'text':	//06-oct-23
			            $fieldMapping['type'] = 'keyword';
		            break;
			        case 'date-5':
			        case 'date-5time':
			            $fieldMapping['type'] = 'date';
			            $fieldMapping['format'] = 'epoch_second';
		            break;
			        case 'decimal':
			        case 'decimal_long':
			            $fieldMapping['type'] = 'float'; // Use 'float' for decimal fields
		            break;
			        case 'number':
			            $fieldMapping['type'] = 'integer'; // Use 'integer' for number fields
		            break;
			        default:
			            $fieldMapping['type'] = 'text';
		            break;
				    }

				    $params[ 'body' ][ 'mappings' ]['properties'][$fieldKey] = $fieldMapping;

			    }

			    $params[ 'body' ][ 'mappings' ]['properties'][ "serial_num" ] = [ 'type' => 'integer' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "creator_role" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "created_source" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "created_by" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "creation_date" ] = [ 'type' => 'date', 'format' => 'epoch_second' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "modified_source" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "modified_by" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "modification_date" ] = [ 'type' => 'date', 'format' => 'epoch_second' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "ip_address" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "device_id" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][ "record_status" ] = [ 'type' => 'keyword' ];
			    $params[ 'body' ][ 'mappings' ]['properties'][$fieldKey] = $fieldMapping;

			    // print_r( $params );exit;
			    try{
			  		$response = cOrm_elasticsearch::$esClient->indices()->create( $params );
			    }catch( BadRequest400Exception $e ){
			        // Handle bad request exception
			        $error = $e->getMessage();
			    }catch( \Exception $e ){
			        // Handle other exceptions
			        $error = $e->getMessage();
			    }

			    if( ! $error ){
			    	return [ 'successful' => 1 ];
			    }

			}else{
				$error = 'Missing Parameter to Create Index';
			}

			if( ! $error ){
				$error = 'Unknown Indexing Error';
			}

			return array( 'error' => $error );
		}
			
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),

			'more_actions' => array(
				'sb2' => array(
					'todo' => 'view_details',
					'title' => 'Sample Button II',
					'text' => 'Sample Button II',
					'button_class' => 'dark',
					'empty_container' => 1,
					'attributes' => ' confirm-prompt="Sample Button II" ',
				),
				'actions' => array(
					'title' => 'More',
					'data' => array(
						'p1' => array(
							'sb1' => array(
								'todo' => 'view_details',
								'title' => 'Sample Button',
								'text' => 'Sample Button',
								'standalone_class' => ' dark ',
								'html_replacement_key' => 'phtml_replacement_selector',
							),
						),
					),
				),
			),
		);
	}
	
	function orm_elasticsearch(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/orm_elasticsearch.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/orm_elasticsearch.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>

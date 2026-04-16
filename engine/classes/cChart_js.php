<?php
/**
 * users_employee_profile Class
 *
 * @used in  				users_employee_profile Function
 * @created  				Hyella Nathan | 23:03 | 13-Jun-2020
 * @database table name   	users_employee_profile
 */
	
	class cChart_js extends cCustomer_call_log{
		
		public $class_settings = array();
		public $table_name = 'chart_js';

		public $chart_types = array( 
			'horizontalBar' => 'Horizontal Bar',
			'bar' => 'Vertical Bar',
			'doughnut' => 'Full Doughnut',
			'semi-doughnut' => 'Semi Doughnut',
			'line' => 'Line Graph',
		);
		public $chart_timeout = 0;

		public $backgroundColor = array(
	        'rgba(255, 99, 132, 0.2)',
	        'rgba(54, 162, 235, 0.2)',
	        'rgba(100, 200, 01, 0.2)',
	        'rgba(111, 0, 255, 0.2)',
	        'rgba(75, 192, 192, 0.2)',
	        'rgba(153, 102, 255, 0.2)',
	        'rgba(255, 159, 64, 0.2)',
	        'rgba(63, 201, 99, 0.2)',
	        'rgba(255, 206, 86, 0.2)',
	        'rgba(100, 134, 190, 0.2)',
	        'rgba(54, 255, 60, 0.2)',
	        'rgba(255, 255, 106, 0.2)',
	    );

		public $borderColor = array(
	        'rgba(255, 99, 132, 1)',
	        'rgba(54, 162, 235, 1)',
	        'rgba(111, 0, 255, 1)',
	        'rgba(255, 206, 86, 1)',
	        'rgba(75, 192, 192, 1)',
	        'rgba(153, 102, 255, 1)',
	        'rgba(255, 159, 64, 1)',
	        'rgba(63, 201, 99, 1)',
	        'rgba(100, 200, 01, 1)',
	        'rgba(100, 134, 190, 1)',
	        'rgba(54, 255, 60, 1)',
	        'rgba(255, 255, 106, 1)',
	    );
		
		function __construct(){}
	
		function chart_js(){
			//LOAD LANGUAGE FILE

			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'format_chart_data':
				$returned_value = $this->_format_chart_data();
			break;
			case 'load_chart_filter':
				$returned_value = $this->_load_chart_filter();
			break;
			case 'execute_chart_filter':
				$returned_value = $this->_execute_chart_filter();
			break;
			case 'get_form_fields_from_table_name':
				$returned_value = $this->_get_form_fields_from_table_name();
			break;
			}
			
			return $returned_value;
		}
		
		public function _prepare_chart_html( $e = array() ){
			$html = '';
			if( isset( $e[ 'data' ] ) && $e[ 'data' ] && isset( $e[ 'chart_key' ] ) && $e[ 'chart_key' ]  ){
				$d = $this->_format_chart_data_new( $e[ 'data' ] );
							// print_r( $e );exit;

				$params = array(
					'filter1btn' => ( isset( $e[ 'filter1btn' ] ) && $e[ 'filter1btn' ] ? $e[ 'filter1btn' ] : 1 ),	
					'filter2btn' => ( isset( $e[ 'filter2btn' ] ) && $e[ 'filter2btn' ] ? $e[ 'filter2btn' ] : 1 ),	
					'query_action' => ( isset( $this->class_settings[ 'query_action' ] ) && $this->class_settings[ 'query_action' ] ? $this->class_settings[ 'query_action' ] : '' ),	
					'query_todo' => ( isset( $this->class_settings[ 'query_todo' ] ) && $this->class_settings[ 'query_todo' ] ? $this->class_settings[ 'query_todo' ] : '' ),
					'timeout' => $this->chart_timeout,	
				);
				$chart_key = isset( $e[ 'chart_key' ] ) && $e[ 'chart_key' ] ? $e[ 'chart_key' ] : '';

				if( isset( $d[ 'data' ] ) && ! empty( $d[ 'data' ] ) ){
					$e[ 'data' ] = $d;
					if( isset( $e[ 'single' ] ) && $e[ 'single' ] ){
						return $e;
					}
					$html = __prepare_chart( $chart_key, $e, $params  );

					$settings = array(
						'cache_key' => md5( $chart_key ) . '-chart',
						'cache_values' => $html,
						'directory_name' => 'charts',
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
			}
			return $html;
		}

		public function _format_chart_data_new( $e = array() ){
			if( isset( $e[ 'data' ] ) && ! empty( $e[ 'data' ] ) ){

				$error = '';

				$data = $e[ 'data' ];

				$type = isset( $e[ 'type' ] ) && $e[ 'type' ] ? $e[ 'type' ] : 'bar';
				$label = isset( $e[ 'tooltip' ] ) && $e[ 'tooltip' ] ? $e[ 'tooltip' ] : '';
				
				$transform_keys = isset( $e[ 'transform_keys' ] ) && $e[ 'transform_keys' ] ? $e[ 'transform_keys' ] : array();
				
				$label_key = isset( $e[ 'label_key' ] ) && $e[ 'label_key' ] ? $e[ 'label_key' ] : '';
				$value_key = isset( $e[ 'value_key' ] ) && $e[ 'value_key' ] ? $e[ 'value_key' ] : '';
				$data_key = isset( $e[ 'data_key' ] ) && $e[ 'data_key' ] ? $e[ 'data_key' ] : '';

				$options = isset( $e[ 'options' ] ) && $e[ 'options' ] ? $e[ 'options' ] : array();
				$index = isset( $e[ 'index' ] ) && $e[ 'index' ] ? $e[ 'index' ] : array();
				$label_index = isset( $e[ 'label_index' ] ) && $e[ 'label_index' ] ? $e[ 'label_index' ] : array();

				// $data_key = "start_date_type";
				// $value_key = "start_date_ref";


				if( isset( $e[ 'mike' ] ) ){
					// print_r( $e );exit;
				}
				if( ! empty( $transform_keys ) ){
					$ab = array();
					foreach( $data as $av ){
						foreach( $transform_keys as $ax ){
							if( isset( $av[ $ax ] ) ){
								$av["transform_value"] = $av[ $ax ];
								$av["transform_key"] = $ax;
								// $av["transform_key"] = isset( $index[ $ax ] ) ? $index[ $ax ] : $ax;
								$ab[] = $av;
							}
						}
					}
					// print_r( $data );exit;
					$data = $ab;

					$data_key = "transform_key";
					$value_key = "transform_value";
					if( ! isset( $label_key ) ){
						// $label_key = $data_key;
					}
				}

				$datasets = array();
				$labels2 = array();
				$labels = array();

				$backgroundColor = array();
				$borderColor = array();

				// $label_key = "duration";
				$k = 0;
				foreach( $data as $ak => $av ){
					if( ! isset( $av[ $label_key ] ) )continue;
					if( ! isset( $av[ $value_key ] ) )continue;

					if( isset( $label_index[ $av[ $label_key ] ] ) && $label_index[ $av[ $label_key ] ] ){
						$av[ $label_key ] = $label_index[ $av[ $label_key ] ];
					}else if( isset( $index[ $av[ $label_key ] ] ) ){
						$av[ $label_key ] = $index[ $av[ $label_key ] ];
					}
					
					if( ! isset( $data_key ) ){
						$data_key = '';
					}
					if( ! isset( $av[ $data_key ] ) ){
						$av[ $data_key ] = '';
					}

					$lbl2 = $av[ $label_key ];
					if( isset( $index2[ $av[ $label_key ] ] ) && $index2[ $av[ $label_key ] ] ){
						$lbl2 = $index2[ $av[ $label_key ] ];
					}

					if( isset( $labels2[ $av[ $data_key ] ][ $lbl2 ] ) ){
						$labels2[ $av[ $data_key ] ][ $lbl2 ] += doubleval( $av[ $value_key ] );
					}else{		
						$d[ $lbl2 ] = $label_key;
						$labels2[ $av[ $data_key ] ][ $lbl2 ] = doubleval( $av[ $value_key ] );
					}

					if( ! isset( $backgroundColor[ $av[ $data_key ] ] ) ){
						if( ! isset ($this->backgroundColor[ $k ] ) )$k = 0;
						$backgroundColor[ $av[ $data_key ] ] = $this->backgroundColor[ $k ];
						$borderColor[ $av[ $data_key ] ] = str_replace( "0.2", "1", $backgroundColor[ $av[ $data_key ] ] );
						$k++;
					}
				}

				if( ! empty( $d ) && ! empty( $labels2 ) ){
					$labels = array_keys( $d );
					foreach( $labels2 as $bk => $bv ){
						$k = 0;
						$k2 = 0;
						$ax = array();
						
						$ax[ 'label' ] = isset( $index[ $bk ] ) ? $index[ $bk ] : ( $bk ? $bk : 'Undefined' );
						$ax[ 'borderWidth' ] = 1;

						foreach( $labels as $bv1 ){
							if( isset( $bv[ $bv1 ] ) ){
								$ax["data"][] = $bv[ $bv1 ];
							}else{
								$ax["data"][] = 0;
							}
							
							if( ! empty( $transform_keys ) || $data_key ){
								if( ! isset( $ax[ 'backgroundColor' ][ $k ] ) ){
									$ax[ 'backgroundColor' ][ $k ] = $backgroundColor[ $bk ];
									$ax[ 'borderColor' ][ $k ] = str_replace( "0.2", "1", $ax[ 'backgroundColor' ][ $k ] );
								}
							}else{
								if( ! isset( $this->backgroundColor[ $k2 ] ) )$k2 = 0;
								$ax[ 'backgroundColor' ][ $k ] = $this->backgroundColor[ $k2 ];
								$ax[ 'borderColor' ][ $k ] = str_replace( "0.2", "1", $ax[ 'backgroundColor' ][ $k ] );
							}
							$k++;
							$k2++;
						}
						$datasets[] = $ax;
					}
				}

				if( empty( $options ) ){
					$options[ 'scales' ][ 'yAxes' ] = array(
						array( 
							'ticks' => array(
								'beginAtZero' => true,
							),
						),
					);
				}

				switch( $type ){
				case 'semi-doughnut':
					$type = 'doughnut';
					$options[ 'circumference' ] = pi();
					$options[ 'rotation' ] = -pi();
				break;
				}

				$return = array(
					'type' => $type,
					'data' => array(
						'labels' => $labels,
						'datasets' => $datasets,
					),
					'options' => $options,
				);

				return $return;
			}
		}

		protected function _load_chart_filter(){
			$error = '';
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}

			if( ! ( isset( $_GET[ 'type' ] ) && $_GET[ 'type' ] ) ){
				$error .= "Undefined Filter Type<br>";
			}else{
				$type = $_GET[ 'type' ];
			}

			if( ! $error && ! ( isset( $_GET[ 'query_action' ] ) && $_GET[ 'query_action' ] || isset( $_GET[ 'query_todo' ] ) && $_GET[ 'query_todo' ] ) ){
				$error .= "Undefined Query Action or Todo<br>";
			}

			if( ! $error && ! isset( $_GET[ 'html_replacement_selector' ] ) && $_GET[ 'html_replacement_selector' ] ){
				$error .= "Undefined Replacement Container<br>";
			}
			$chart_key = isset( $_GET[ 'key' ] ) && $_GET[ 'key' ] ? $_GET[ 'key' ] : '';

			$return = array();

			if( ! $error ){
				$query_action = $_GET[ 'query_action' ];
				$query_todo = $_GET[ 'query_todo' ];

				$cache_id = 0;
				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){

					$settings = array(
						'cache_key' => md5( $query_action.$query_todo ) . '-filter-id',
						'cache_values' => $_POST[ 'id' ],
						'directory_name' => 'charts',
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					$cache_id = 1;
				}

				switch( $type ){
				case 'advanced_filter':
					if( ! ( isset( $_GET[ 'table' ] ) && $_GET[ 'table' ] ) ){
						$error .= "Undefined Search Table<br>";
					}

					$table = $_GET[ 'table' ];
					if( ! $error ){
						$s = new cSearch();
						$s->class_settings = $this->class_settings;

						$join_table = isset( $_GET[ 'join_tables' ] ) && $_GET[ 'join_tables' ] ? explode( ":::", $_GET[ 'join_tables' ] ) : array();

						$s->class_settings[ 'action_to_perform' ] = 'search_window';
						$s->class_settings[ 'data' ][ 'join_tables' ] = $join_table;

						$s->class_settings[ 'data' ][ 'action' ] = $this->table_name;
						$s->class_settings[ 'data' ][ 'todo' ] = 'execute_chart_filter';
						$s->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector='. $this->class_settings[ 'html_replacement_selector' ] .'&query_action='. $query_action .'&query_todo='. $query_todo .'&type='. $type . ( $cache_id ? '&cache_id='. $cache_id : '' );

						$s->class_settings[ 'data' ][ 'fields_action' ] = '?action='. $this->table_name .'&todo=get_form_fields_from_table_name&filter=list_options';

						$return = $s->search();
						return $return;
					}
				break;
				case 'basic_filter':
					if( ! ( isset( $_GET[ 'report' ] ) &&isset( $_GET[ 'report_type' ] ) && $_GET[ 'report' ] && $_GET[ 'report_type' ] ) ){
						$error .= "Undefined Report / Report Type<br>";
					}

					if( ! $error ){
						$s = new cAll_reports();
						$s->class_settings = $this->class_settings;

						$s->class_settings[ 'action_to_perform' ] = 'get_report_filter_form';
						$_POST[ 'report_type' ] = $_GET[ 'report_type' ];
						$_POST[ 'report' ] = $_GET[ 'report' ];

						$s->class_settings[ 'data' ][ 'action' ] = $this->table_name;
						$s->class_settings[ 'data' ][ 'todo' ] = 'execute_chart_filter';
						$s->class_settings[ 'data' ][ 'chart_filter' ] = $this->chart_types;
						$s->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector='. $this->class_settings[ 'html_replacement_selector' ] .'&query_action='. $query_action .'&query_todo='. $query_todo .'&type='. $type;

						$return = $s->all_reports();
						// print_r( $return );exit;
						if( isset( $return[ 'html_replacement' ] ) && $return[ 'html_replacement' ] ){
							$this->class_settings[ 'modal_title' ] = "Basic Chart Filter Form";
							return $this->_launch_popup( $return[ 'html_replacement' ] , '#dash-board-main-content-area', array( 'prepare_new_record_form_new' ) );
						}else{
							$error = "Unable to Generate Filter Form. ".( isset( $return[ 'msg' ] ) && $return[ 'msg' ] ? $return[ 'msg' ]  : '' );
						}
					}
				break;
				case 'full_screen':
					if( $chart_key ){
						$settings = array(
							'cache_key' => md5( $chart_key ) . "-chart",
							'directory_name' => 'charts',
							'permanent' => true,
						);
						// print_r( $settings );exit;
						$html = get_cache_for_special_values( $settings );
						$this->class_settings[ 'data' ][ 'html' ] = $html;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/chart-fullscreen.php' );
						
						$returning_html_data = $this->_get_html_view();
						echo $returning_html_data;exit;
					}
				break;
				}

			}

			return $this->_display_notification( array( 'type' => 'error', 'message' => $error ) );
		}

		protected function _execute_chart_filter(){
			$error = '';

			if( ! ( isset( $_GET[ 'type' ] ) && $_GET[ 'type' ] ) ){
				$error .= "Undefined Filter Type<br>";
			}else{
				$type = $_GET[ 'type' ];
			}

			if( ! ( isset( $_GET[ 'query_action' ] ) && $_GET[ 'query_action' ] || isset( $_GET[ 'query_todo' ] ) && $_GET[ 'query_todo' ] ) ){
				$error .= "Undefined Query Action or Todo<br>";
			}

			if( ! isset( $_GET[ 'html_replacement_selector' ] ) && $_GET[ 'html_replacement_selector' ] ){
				$error .= "Undefined Replacement Container<br>";
			}

			$cache_id = isset( $_GET[ 'cache_id' ] ) && $_GET[ 'cache_id' ] ? $_GET[ 'cache_id' ] : '';

			if( ! $error ){
				$return = array();
				$return[ 'status' ] = 'new-status';
				$return[ 'javascript_functions' ] = array( 'nwChartJs.closeModal' );

				$query_action = $_GET[ 'query_action' ];
				$query_todo = $_GET[ 'query_todo' ];
				$html_replacement_selector = $_GET[ 'html_replacement_selector' ];
				$return_complete_single = isset( $_GET[ 'return_complete_single' ] ) && $_GET[ 'return_complete_single' ] ? $_GET[ 'return_complete_single' ] : '';
				$plugin = isset( $_GET[ 'plugin' ] ) && $_GET[ 'plugin' ] ? $_GET[ 'plugin' ] : ( isset( $_POST[ 'plugin' ] )?$_POST[ 'plugin' ]:'' );

				$query = '';
				$tb = '';
				switch( $type ){
				case 'advanced_filter':

					if( ! ( isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ) ){
						$error .= "Undefined Search Table<br>";
					}
					$tb = $_POST[ 'table' ];

					if( ! $error ){
						$s = new cSearch();
						$s->class_settings = $this->class_settings;

						$s->class_settings[ 'action_to_perform' ] = 'run_search_query';
						$s->class_settings[ 'return_query' ] = 1;
						$s->class_settings[ 'exclude_filter_data' ] = 1;

						$d = $s->search();

						// print_r( $d );exit;
						if( isset( $d[ 'query' ] ) && $d[ 'query' ] ){
							$query = $d[ 'query' ];
						}else{
							$error = "Unable to Prepare Query for Chart";
						}
					}
				break;
				case 'basic_filter':
				break;
				}

				if( ! $error ){
					$q2 = $query_action;
					$q1 = $query_todo;

					if( $plugin ){
						$cl = 'c'.ucwords( $plugin );
						$q1 = 'execute';
						$q2 = $plugin;
					}else{
						$cl = 'c' . ucwords( $query_action );
					}

					if( class_exists( $cl ) ){
						//print_r( $cl );exit;
						$cl = new $cl();

						if( $cache_id ){
							$settings = array(
								'cache_key' => md5( $q2.$q1 ) . '-filter-id',
								'directory_name' => 'charts',
								'permanent' => true,
							);
							// print_r( $settings );exit;
							$_POST[ 'id' ] = get_cache_for_special_values( $settings );
						}

						$cl->class_settings = $this->class_settings;
						$cl->class_settings[ 'action_to_perform' ] = $q1;
						
						if( $plugin ){
							$cl->class_settings[ 'nwp_action' ] = $query_action;
							$cl->class_settings[ 'nwp_todo' ] = $query_todo;
						}
						
						$cl->class_settings[ 'data' ][ 'where' ] = $query;
						$cl->class_settings[ 'data' ][ 'table' ] = $tb;
						$cl->class_settings[ 'data' ][ 'chart' ] = str_replace( "-", "_", $html_replacement_selector );
						if( $return_complete_single ){
							$cl->class_settings[ 'data' ][ 'chart' ] = $return_complete_single;
							$cl->class_settings[ 'data' ][ 'return_complete_single' ] = 1;
						}

						$r = $cl->$q2();
						//print_r( $cl->class_settings );exit;
						switch( $q2 ){
						case 'all_reports':
							$r[ 'javascript_functions' ] = isset( $return[ 'javascript_functions' ] ) ? array_merge( $return[ 'javascript_functions' ], $r[ 'javascript_functions' ] ) : $return[ 'javascript_functions' ];
							$return = $r;
						break;
						default:
							if( isset( $r[ 'html' ][ 'data' ] ) && ! empty( $r[ 'html' ][ 'data' ] ) ){
								$return[ 'data' ] = $r[ 'html' ][ 'data' ];
							}else if( isset( $r[ 'html' ] ) && $r[ 'html' ] ){
								$return[ 'html_replacement' ] = $r[ 'html' ];
							}
							$return[ 'html_replacement_selector' ] = ( $return_complete_single ? '#' : '' ) . $html_replacement_selector;
							$return[ 'javascript_functions' ][] = 'nwChartJs.loadSingleChart';
						break;
						}
					}
				}

			}

			if( $error ){
				return $this->_display_notification( array( 'type' => 'error', 'message' => $error ) );
			}

			return $return;
		}

		protected function _get_form_fields_from_table_name(){
			$error = '';

			if( ! ( isset( $_GET[ 'table_name' ] ) && $_GET[ 'table_name' ] ) ){
				$error .= "Undefined Table Name<br>";
			}

			$filter = array();

			if( isset( $_GET[ 'filter' ] ) && $_GET[ 'filter' ] ){
				switch( $_GET[ 'filter' ] ){
				case 'list_options':
					// $filter[ 'select' ] = 'select';
					// $filter[ 'radio' ] = 'radio';
					// $filter[ 'checkbox' ] = 'checkbox';
					// $filter[ 'field_group' ] = 'field_group';
					// $filter[ 'multi-select' ] = 'multi-select';
				break;
				}
			}

			$dt = new cDatabase_table();
			$dt->class_settings = $this->class_settings;
			$dt->class_settings[ 'data' ][ 'filter' ] = $filter;

			return $dt->database_table();
		}
		
	}

function __chart_filter_button( $type = '', $params = array(), $query_action = '', $query_todo = '' ){
	$return = '';
	$ctitle = ( isset( $params[ 'title' ] ) ? $params[ 'title' ] : '' );
	$canvas_id = ( isset( $params[ 'html_replacement_selector' ] ) ? $params[ 'html_replacement_selector' ] : '' );
	$o_params = '&html_replacement_selector='. $canvas_id;
	$o_params .= '&query_action='. $query_action .'&query_todo='. $query_todo;

	$key = isset( $params[ 'key' ] ) ? $params[ 'key' ] : '-';
	$o_params .= isset( $params[ 'plugin' ] ) ? '&plugin='.$params[ 'plugin' ] : '';

	$action = '?module=&action=chart_js&todo=load_chart_filter';
	$title = '';
	$attr = '';
	$text = '';
	$href = '#';
	$class = ' custom-single-selected-record-button ';
	switch( $type ){
	case 'advanced_filter':
		if( isset( $params[ 'table' ] ) && $params[ 'table' ] ){
			
			$o_params .= '&table='. $params[ 'table' ];
			$o_params .= '&type=advanced_filter';

			$title = 'Click to Filter this Chart (Advanced)';
			$text = 'Advanced';

			$o_params .= isset( $params[ 'join_tables' ] ) && ! empty( $params[ 'join_tables' ] ) ? '&join_tables='.implode( ":::", $params[ 'join_tables' ] ) : '';
		}
	break;
	case 'basic_filter':
		if( isset( $params[ 'report' ] ) && isset( $params[ 'report_type' ] ) && $params[ 'report' ] && $params[ 'report_type' ] ){

			$o_params .= '&type=basic_filter';
			$o_params .= '&report='. $params[ 'report' ];
			$o_params .= '&report_type='. $params[ 'report_type' ];

			$title = 'Click to Filter this Chart (Basic)';
			$text = 'Simple';

		}
	break;
	case 'full_screen':
		if( $key){
			$class = '';

			$attr .= ' target="_blank" ';
			$o_params .= '&type=full_screen';
			$o_params .= '&key='.$key;

			$title = 'Click to Enlarge this Chart';
			$text = '<i class="icon-zoom-in"></i>';
			$href = '../engine/php/ajax_request_processing_script.php'. $action. $o_params;
		}

	break;
	case 'print':
		if( $key){
			$class = '';

			$attr .= ' id="print-'.$canvas_id.'" data-canvas="'.$canvas_id.'" data-name="'.$ctitle.'" data-type="print" onclickX="$.fn.cProcessForm.printCanvas('."'print-". $canvas_id ."'".'); return false;" ';
			$action = '';
			$o_params = '';

			$title = 'Click to Print ' . $ctitle;
			$text = '<i class="icon-print"></i>';
			$href = 'javascript:$.fn.cProcessForm.printCanvas('."'print-". $canvas_id ."'".');';
		}

	break;
	case 'save':
		if( $key){
			$class = '';

			$attr .= ' id="save-'.$canvas_id.'" data-canvas="'.$canvas_id.'" data-name="'.$ctitle.'" data-type="save" ';
			$action = '';
			$o_params = '';

			$title = 'Click to Save ' . $ctitle;
			$text = '<i class="icon-download-alt"></i>';
			$href = 'javascript:$.fn.cProcessForm.printCanvas('."'save-". $canvas_id ."'".');';
		}

	break;
	}
	// print_r( $params );exit;
	$return = '';
	if( $text ){
		$return = '<a class="active btn btn-outline-dark '. $class .'" '. $attr .' href="'. $href .'" override-selected-record="'. $key .'" action="'. $action .$o_params .'" title="'. $title .'">'. $text .'</a>';
	}
	return $return;
}
	
function __prepare_chart( $key = '', $data = array(), $params = array() ){

	$filter1btn = isset( $params[ 'filter1btn' ] ) && $params[ 'filter1btn' ] ? 1 : 0;
	$filter2btn = isset( $params[ 'filter2btn' ] ) && $params[ 'filter2btn' ] ? 1 : 0;
	$timeout = isset( $params[ 'timeout' ] ) && $params[ 'timeout' ] ? $params[ 'timeout' ] : 0;
	$query_todo = isset( $params[ 'query_todo' ] ) && $params[ 'query_todo' ] ? $params[ 'query_todo' ] : 0;
	$query_action = isset( $params[ 'query_action' ] ) && $params[ 'query_action' ] ? $params[ 'query_action' ] : 0;
	$plugin = isset( $data[ 'plugin' ] ) && $data[ 'plugin' ] ? $data[ 'plugin' ] : '';
	$params[ 'plugin' ] = $plugin;

	$html = '';
	if( $key && isset( $data[ 'data' ] ) ){
		$container = str_replace( "_", "-", $key );
		$data[ 'html_container' ] = $container;

		$title = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';
		$params[ 'title' ] = $title;
		
		$btn1 = '';
		$btn2 = '';
		$params[ 'key' ] = $key;
		$params[ 'html_replacement_selector' ] = $container;

		$btn0 = __chart_filter_button( 'full_screen', $params, '-', '-' );
		$btn0 .= __chart_filter_button( 'print', $params, '-', '-' );
		$btn0 .= __chart_filter_button( 'save', $params, '-', '-' );

		if( $filter1btn && isset( $data[ 'table' ] ) ){
			$params[ 'key' ] = isset( $data[ 'key' ] ) ? $data[ 'key' ] : '';
			$params[ 'join_tables' ] = isset( $data[ 'join_tables' ] ) ? $data[ 'join_tables' ] : '';
			$params[ 'table' ] = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';

			//$btn1 = __chart_filter_button( 'advanced_filter', $params, $query_action, $query_todo );
		}


		// if( $key == 'work_spent' ){print_r( $data );exit;}
		if( $filter2btn && isset( $data[ 'options' ] ) ){
			$data[ 'options' ][ 'plugin' ] = $plugin;
			$data[ 'options' ][ 'html_replacement_selector' ] = $container;
			//$btn2 = __chart_filter_button( 'basic_filter', $data[ 'options' ], $query_action, $query_todo );
		}

		$html .= '
			<div class="rm-border card-header">
				<div>
					<h6 class="menu-header-title text-capitalize text-primary">'. $title .'</h6>
				</div>';
				if( $btn1 || $btn2 || $btn0 ){
					$html .= '<div class="btn-actions-pane-right pull-right">';
						if( $btn0 ){
							$html .= '<div role="group" class="btn-group-sm btn-group">'. $btn0 .'</div>';
						}
						if( $btn1 ){
							$html .= '<div role="group" class="btn-group-sm btn-group">'. $btn1 .'</div>';
						}
						if( $btn2 ){
							$html .= '<div role="group" class="btn-group-sm btn-group">'. $btn2 .'</div>';
						}
					$html .= '</div>';
				}

		$width = 400;
		$height = 200;
		$html .= '</div>
				<div class="pl-3 pr-3 pb-2" id="'. $container.'-container">
					<canvas id="'. $container .'"  width="'. $width .'" height="'. $height .'"></canvas>
				</div>
			<br>';
		$html .= '<script>setTimeout(function(){nwChartJs.loadChartData('. json_encode( $data ) .')},'. $timeout .');</script>';
	}
	return $html;
}
?>
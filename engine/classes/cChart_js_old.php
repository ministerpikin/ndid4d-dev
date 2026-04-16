<?php
/**
 * users_employee_profile Class
 *
 * @used in  				users_employee_profile Function
 * @created  				Hyella Nathan | 23:03 | 13-Jun-2020
 * @database table name   	users_employee_profile
 */
	
	class cChart_js_old{
		
		public $class_settings = array();
		public $table_name = 'chart_js';

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

				// $data_key = "start_date_type";
				// $value_key = "start_date_ref";

				if( ! empty( $transform_keys ) ){
					$ab = array();
					foreach( $data as $av ){
						foreach( $transform_keys as $ax ){
							if( isset( $av[ $ax ] ) ){
								$av["transform_value"] = $av[ $ax ];
								$av["transform_key"] = $ax;
								$ab[] = $av;
							}
						}
					}
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

				foreach( $data as $ak => $av ){
					if( ! isset( $av[ $label_key ] ) )continue;
					if( ! isset( $av[ $value_key ] ) )continue;
					
					if( ! isset( $data_key ) ){
						$data_key = '';
					}
					if( ! isset( $av[ $data_key ] ) ){
						$av[ $data_key ] = '';
					}

					if( isset( $labels2[ $av[ $data_key ] ][ $av[ $label_key ] ] ) ){
						$labels2[ $av[ $data_key ] ][ $av[ $label_key ] ] += doubleval( $av[ $value_key ] );
					}else{		
						$d[ $av[ $label_key ] ] = $label_key;
						$labels2[ $av[ $data_key ] ][ $av[ $label_key ] ] = doubleval( $av[ $value_key ] );
					}

					if( isset( $av[ $data_key ] ) ){
						if( ! isset( $backgroundColor[ $av[ $data_key ] ] ) ){
							if( isset( $this->backgroundColor[ $ak ] ) ){
								$backgroundColor[ $av[ $data_key ] ] = $this->backgroundColor[ $ak ];
								$borderColor[ $av[ $data_key ] ] = str_replace( "0.2", "1", $backgroundColor[ $av[ $data_key ] ] );
							}
						}
					}
				}

				// if( isset( $e[ 'mike' ] ) ){
				// 	print_r( $labels2 );exit;
				// }

				if( ! empty( $d ) && ! empty( $labels2 ) ){
					$labels = array_keys( $d );
					foreach( $labels2 as $bk => $bv ){
						$k = 0;
						$ax = array();
						
						$ax[ 'label' ] = $bk ? $bk : $label;
						$ax[ 'borderWidth' ] = 1;

						foreach( $labels as $bv1 ){
							if( isset( $bv[ $bv1 ] ) ){
								$ax["data"][] = $bv[ $bv1 ];
							}else{
								$ax["data"][] = 0;
							}
							
							if( ! empty( $transform_keys ) || $data_key ){
								if( ! isset( $ax[ 'backgroundColor' ][ $k ] ) ){
									if( isset( $backgroundColor[ $bk ] ) ){
										$ax[ 'backgroundColor' ][ $k ] = $backgroundColor[ $bk ];
										$ax[ 'borderColor' ][ $k ] = str_replace( "0.2", "1", $ax[ 'backgroundColor' ][ $k ] );
									}
								}
							}else{
								$ax[ 'backgroundColor' ][ $k ] = $this->backgroundColor[ $k ];
								$ax[ 'borderColor' ][ $k ] = str_replace( "0.2", "1", $ax[ 'backgroundColor' ][ $k ] );
							}
							$k++;
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

			if( ! ( isset( $_GET[ 'table' ] ) && $_GET[ 'table' ] ) ){
				$error .= "Undefined Search Table<br>";
			}

			if( ! ( isset( $_GET[ 'query_action' ] ) && $_GET[ 'query_action' ] || isset( $_GET[ 'query_todo' ] ) && $_GET[ 'query_todo' ] ) ){
				$error .= "Undefined Query Action or Todo<br>";
			}

			if( ! isset( $_GET[ 'html_replacement_selector' ] ) && $_GET[ 'html_replacement_selector' ] ){
				$error .= "Undefined Replacement Container<br>";
			}

			$return = array();

			if( ! $error ){
				$table = $_GET[ 'table' ];
				$query_action = $_GET[ 'query_action' ];
				$query_todo = $_GET[ 'query_todo' ];

				$s = new cSearch();
				$s->class_settings = $this->class_settings;

				$join_table = isset( $_GET[ 'join_tables' ] ) && $_GET[ 'join_tables' ] ? explode( ":::", $_GET[ 'join_tables' ] ) : array();

				$s->class_settings[ 'action_to_perform' ] = 'search_window';
				$s->class_settings[ 'data' ][ 'join_tables' ] = $join_table;

				$s->class_settings[ 'data' ][ 'action' ] = $this->table_name;
				$s->class_settings[ 'data' ][ 'todo' ] = 'execute_chart_filter';
				$s->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector='. $this->class_settings[ 'html_replacement_selector' ] .'&query_action='. $query_action .'&query_todo='. $query_todo;

				$s->class_settings[ 'data' ][ 'fields_action' ] = '?action='. $this->table_name .'&todo=get_form_fields_from_table_name&filter=list_options';

				$return = $s->search();

				return $return;
			}
		}

		protected function _execute_chart_filter(){
			$error = '';

			if( ! ( isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ) ){
				$error .= "Undefined Search Table<br>";
			}

			if( ! ( isset( $_GET[ 'query_action' ] ) && $_GET[ 'query_action' ] || isset( $_GET[ 'query_todo' ] ) && $_GET[ 'query_todo' ] ) ){
				$error .= "Undefined Query Action or Todo<br>";
			}

			if( ! isset( $_GET[ 'html_replacement_selector' ] ) && $_GET[ 'html_replacement_selector' ] ){
				$error .= "Undefined Replacement Container<br>";
			}

			$return = array();
			$return[ 'status' ] = 'new-status';
			$return[ 'javascript_functions' ] = array( 'nwHRDashboard.closeModal' );

			$query_action = $_GET[ 'query_action' ];
			$query_todo = $_GET[ 'query_todo' ];
			$html_replacement_selector = $_GET[ 'html_replacement_selector' ];

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

					$cl = 'c' . ucwords( $query_action );

					if( class_exists( $cl ) ){
						$cl = new $cl();
						$cl->class_settings = $this->class_settings;
						$cl->class_settings[ 'action_to_perform' ] = $query_todo;
						$cl->class_settings[ 'data' ][ 'where' ] = $query;
						$cl->class_settings[ 'data' ][ 'chart' ] = str_replace( "-", "_", $html_replacement_selector );

						$r = $cl->$query_action();

						if( isset( $r[ 'data' ] ) && ! empty( $r[ 'data' ] ) ){
							$return[ 'data' ] = $r[ 'data' ];
							$return[ 'html_replacement_selector' ] = $html_replacement_selector;
							$return[ 'javascript_functions' ][] = 'nwHRDashboard.loadSingleChart';
						}
					}
				}else{
					$error = "Unable to Prepare Query for Chart";
				}

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
	
?>
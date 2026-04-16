<?php
/**
 * orm_mysql Class
 *
 * @used in  				orm_mysql Function
 * @created  				Bay4 Mike | 02:15 | 21-Jul-2023
 * @database table name   	orm_mysql
 */
	
	class cOrm_mysql extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'orm_mysql';
		
		public $default_reference = '';
		
		public $label = 'ORM MySQL';
		
		private $associated_cache_keys = array(
			'orm_mysql',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		public $last_query = '';
		
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/orm_mysql.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/orm_mysql.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function orm_mysql(){
			
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
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		public function _load_query( $settings ){
			$error = '';

			if( isset( $settings[ 'query_type' ] ) && $settings[ 'query_type' ] ){
				$ptable = '';
				$ec = array();
				switch( $settings[ 'query_type' ] ){
				case 'SELECT':
					$ec = array( 'columns', 'from' );
				break;
				default:
					// code...
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
					default:
						// code...
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
			
			$result = array();

			$sSelect = '';
			$sFrom = '';
			$sWhere = '';
			$having = '';
			$sJoin = '';
			$sGroup = '';
			$sOrder = '';
			$sLimit = '';

			$q = isset( $settings[ 'query' ] ) && is_array( $settings[ 'query' ] ) ? $settings[ 'query' ] : array();

			// echo '<pre>';
			// print_r( $q[ 'columns' ] );exit;
			// print_r( $q[ 'columns' ] );exit;
			if( isset( $q[ 'columns' ] ) && ! empty( $q[ 'columns' ] ) ){
				foreach( $q[ 'columns' ] as $cv ){
					if( isset( $cv[ 'field' ] ) && $cv[ 'field' ] && isset( $cv[ 'table' ] ) && $cv[ 'table' ] ){
						if( $sSelect ){
							$sSelect .= ', ';
						}
						$sf = " `". $cv[ 'table' ] ."`.`". $cv[ 'field' ] ."` ";
						if( isset( $cv[ 'custom' ] ) && $cv[ 'custom' ] ){
							$sf = $cv[ 'custom' ];
						}elseif( isset( $cv[ 'aggregation' ] ) && is_array( $cv[ 'aggregation' ] ) && ! empty( $cv[ 'aggregation' ] ) ){
							foreach( $cv[ 'aggregation' ] as $agk => $agv ){
								switch( $agk ){
								default:
									$parms = $sf;
									if( isset( $agv[ 'params' ] ) && ! empty( $agv[ 'params' ] ) ){
										$parms = '';
										$xn = 0;
										foreach( $agv[ 'params' ] as $agv2 ){
											$xn++;
											$no_quotes = 0;

											switch( $agv2 ){
											case '__field__':
												$no_quotes = 1;
												$agv2 = $sf;
											break;
											}

											if( in_array( gettype( $agv2 ), array( 'integer', 'double' ) ) ){
												$parms .= " ". $agv2 ." ";
											}else{
												if( $no_quotes ){
													$parms .= " ". $agv2 ." ";
												}else{
													$parms .= " '". $agv2 ."' ";
												}
											}
											if( isset( $agv[ 'params' ][ $xn ] ) ){
												$parms .= ",";
											}
										}
									}
									$sf = " ". strtoupper( $agk ) ."( ". $parms ." ) ";
								break;
								}
							}
						}
						$sSelect .= " ". $sf ." ";
						if( isset( $cv[ 'alias' ] ) && $cv[ 'alias' ] ){
							$sSelect .= " as '". $cv[ 'alias' ] ."' ";
						}
					}
				}
				if( $sSelect ){
					$sSelect = " SELECT " . $sSelect;
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

				// print_r( $q );
			if( isset( $q[ 'where' ] ) && ! empty( $q[ 'where' ] ) ){
				// print_r( $this->_nested_where( $q[ 'where' ] ) );exit;
				$sWhere = $this->_nested_where( $q[ 'where' ] );
				if( $sWhere ){
					$sWhere = " WHERE " . $sWhere;
				}
			}

			if( isset( $q[ 'from' ][ 'database' ] ) && $q[ 'from' ][ 'database' ] && isset( $q[ 'from' ][ 'table' ] ) && $q[ 'from' ][ 'table' ] ){
				$sFrom .= " FROM `". $q[ 'from' ][ 'database' ] ."`.`". $q[ 'from' ][ 'table' ] ."` ";
				if( isset( $q[ 'from' ][ 'alias' ] ) && $q[ 'from' ][ 'alias' ] ){
					$sFrom .= " '". $q[ 'from' ][ 'alias' ] ."' ";
				}
			}

			if( isset( $q[ 'group' ] ) && ! empty( $q[ 'group' ] ) ){
				foreach( $q[ 'group' ] as $cv ){
					if( isset( $cv[ 'alias' ] ) && $cv[ 'alias' ] ){
						if( $sGroup ){
							$sGroup .= ', ';
						}
						$sGroup .= " ". $cv[ 'alias' ] ." ";
					}elseif( isset( $cv[ 'field' ] ) && $cv[ 'field' ] && isset( $cv[ 'table' ] ) && $cv[ 'table' ] ){
						if( $sGroup ){
							$sGroup .= ', ';
						}
						$sGroup .= " `". $cv[ 'table' ] ."`.`". $cv[ 'field' ] ."` ";
					}
				}
				if( $sGroup ){
					$sGroup = " GROUP BY " . $sGroup;
				}
			}

			if( isset( $q[ 'order' ][ 'table' ] ) && $q[ 'order' ][ 'table' ] && isset( $q[ 'order' ][ 'field' ] ) && $q[ 'order' ][ 'field' ] ){
				$sOrder .= " ORDER BY `". $q[ 'order' ][ 'table' ] ."`.`". $q[ 'order' ][ 'field' ] ."` ";
				if( isset( $q[ 'order' ][ 'sort' ] ) && in_array( strtolower( $q[ 'order' ][ 'sort' ] ), array( 'asc', 'desc' ) ) ){
					$sOrder .= " ". strtoupper( $q[ 'order' ][ 'sort' ] ) ." ";
				}
			}

			if( isset( $q[ 'limit' ][ 'max' ] ) && $q[ 'limit' ][ 'max' ] ){
				$sLimit = " LIMIT ". $q[ 'limit' ][ 'max' ] ." ";
				if( isset( $q[ 'limit' ][ 'min' ] ) && $q[ 'limit' ][ 'min' ] ){
					$sLimit = " LIMIT ". $q[ 'limit' ][ 'min' ] .", ".$q[ 'limit' ][ 'max' ];
				}
			}

			if( $sSelect && $sFrom ){
				$query = $sSelect.$sFrom.$sJoin.$sWhere.$having.$sGroup.$sOrder.$sLimit;
				$view = 0;
				if( isset( $q[ 'view' ][ 'view_name' ] ) && $q[ 'view' ][ 'view_name' ] && isset( $q[ 'view' ][ 'database' ] ) && $q[ 'view' ][ 'database' ] ){
					$query = "CREATE OR REPLACE VIEW `". $q[ 'view' ][ 'database' ] ."`.`". $q[ 'view' ][ 'view_name' ] ."` AS ( ".  $query ." ) ";
					$view = 1;
				}


				$this->last_query = $query;
				// echo $query; exit;

				//Clean SQL QUERY
				try{
					$result_of_sql_query = mysqli_query( $settings['connect'], $query );
				}catch( Exception $e ){
					$result_of_sql_query = 0;
				}
				
				//echo $settings['query'] . "\n\n";
				if ( ! $result_of_sql_query ) {
					if( ! ( isset( $settings['skip_log'] ) && $settings['skip_log'] ) ){
						
						$e = mysqli_error( $settings['connect'] );
						
						if( isset( $e['message'] ) && $e['message'] ){
							return array( 'error' => $e['message'] );
						}else{
							return array( 'error' => $e );
						}
					}
				}else{
					if( $view ){
						$result = $result_of_sql_query;
					}else{
						$size = mysqli_num_rows( $result_of_sql_query );
					
						//$array = array();
						$index_type = isset( $settings[ 'index_type' ] ) ? $settings[ 'index_type' ] : '';
						if( isset( $settings['index_field'] ) && $settings['index_field'] ){
							$result = array();
						}else{
							$result = new SplFixedArray( $size );
						}
						
						$counter = 0;
	                    while( ($row = mysqli_fetch_assoc( $result_of_sql_query )) != false ){
							//$result[] = $row;
							if( isset( $settings['index_field'] ) && isset( $row[ $settings['index_field'] ] ) ){
								
								switch( $index_type ){
								case "multiple":
									$result[ $row[ $settings['index_field'] ] ][] = $row;
								break;
								default:
									$result[ $row[ $settings['index_field'] ] ] = $row;
								break;
								}
							}else{
								$result[ $counter ] = $row;
							}
							++$counter;
						}
					}
					
				}

			}

			return (array) $result;
		}
			
		protected function _nested_where( $opt = array(), $condition = '' ){
			$where = '';
			if( is_array( $opt ) && ! empty( $opt ) ){
				$xn = 0;
				foreach( $opt as $cdtn => $cv ){
					$sn = 0;
					if( $xn ){
						$where .= " ". strtoupper( $cdtn ) ." ";
					}
					$twhr = '';
					foreach( $cv as $ck => $cvl ){
						if( isset( $cvl[ 'and' ][0][ 'key' ] ) || isset( $cvl[ 'or' ][0][ 'key' ] ) ){
							$where .= ' ( '.$this->_nested_where( $cvl, '' ) . ' ) ';
						}else{
							if( isset( $cvl[ 'key' ] ) && isset( $cvl[ 'condition' ] ) && $cvl[ 'condition' ] ){
								$cvl[ 'condition' ] = trim( $cvl[ 'condition' ] );
								$sn++;
								$ky = "";
								$vy = "";
								if( isset( $cvl[ 'key' ][ 'table' ] ) && $cvl[ 'key' ][ 'table' ] && isset( $cvl[ 'key' ][ 'field' ] ) && $cvl[ 'key' ][ 'field' ] ){
									$ky = " `". $cvl[ 'key' ][ 'table' ] ."`.`". $cvl[ 'key' ][ 'field' ] ."` ";
								}elseif( isset( $cvl[ 'value' ] ) ){
									if( in_array( gettype( $cvl[ 'value' ] ), array( 'integer', 'double' ) ) ){
										$ky = $cvl[ 'value' ];
									}else{
										$ky = "'".$cvl[ 'value' ]."'";
									}
								}

								if( isset( $cvl[ 'min' ] ) && isset( $cvl[ 'max' ] ) ){
									$vy = " ". $cvl[ 'min' ] ." AND ". $cvl[ 'max' ] ." ";
								}elseif( isset( $cvl[ 'value' ][ 'table' ] ) && $cvl[ 'value' ][ 'table' ] && isset( $cvl[ 'value' ][ 'field' ] ) && $cvl[ 'value' ][ 'field' ] ){
									$vy = " `". $cvl[ 'value' ][ 'table' ] ."`.`". $cvl[ 'value' ][ 'field' ] ."` ";
								}elseif( in_array( strtolower( $cvl[ 'condition' ] ), [ 'in', 'not_in' ] ) && isset( $cvl[ 'values' ][0] ) ){
									$vy = " ( '". implode( "','", $cvl[ 'values' ] ) ."' ) ";
								}elseif( isset( $cvl[ 'value' ] ) ){
									if( in_array( gettype( $cvl[ 'value' ] ), array( 'integer', 'double' ) ) ){
										$vy = $cvl[ 'value' ];
									}else{
										$vy = "'".$cvl[ 'value' ]."'";
									}
								}
								if( $ky && $vy ){
									$twhr .= $ky . ' '. $cvl[ 'condition' ] .' '. $vy;
									if( $sn != count( $cv ) ){
										$twhr .= " ". strtoupper( $cdtn ) ." ";
									}
								}
							}
						}
					}
					if( count( $cv ) == 1 ){
						$where .= ' '. $twhr . ' ';
					}else{
						$where .= ' ( '. $twhr . ' ) ';
					}
					$xn++;
				}
			}
			return $where;
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
	
	function orm_mysql(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/orm_mysql.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/orm_mysql.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
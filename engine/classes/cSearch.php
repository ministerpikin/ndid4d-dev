<?php
	/**
	 * Search Class
	 *
	 * @used in  				All Advance Search / Clear Search Functions
	 * @created  				21:11 | 17-08-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Advance Search / Clear Search in all Modules
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cSearch{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'search';
		
		function search(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'search':
				//ADVANCE SEARCH AND FILTER
				$returned_value = $this->_search();
			break;
			case 'perform_search':
				//ADVANCE SEARCH AND FILTER
				$returned_value = $this->_perform_search();
			break;
			case 'clear_search_window':
			case 'clear_search':
				//CLEAR ADVANCE SEARCH AND FILTER
				$returned_value = $this->_clear_search();
			break;
			case 'bulk_edit':
			case 'search_window':
				$returned_value = $this->_search_window();
			break;
			case 'run_search_query':
				$returned_value = $this->_execute_search_window_query();
			break;
			case 'run_search_query2':
				$returned_value = $this->_run_search_query2();
			break;
			case 'get_search_query':
				$returned_value = $this->_view_all_records();
			break;
			}
			
			return $returned_value;
		}
		
		private function _view_all_records(){
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$callback = ( isset( $_GET[ "callback" ] ) ? $_GET[ "callback" ] : '' );
				
			$handle = '';
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = '#' . $this->class_settings["html_replacement_selector"];
			}
			
			$cache_key = isset( $this->class_settings["cache_query_key"] )?$this->class_settings["cache_query_key"]:'';
			
			if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] && isset( $_GET[ 'key' ] ) && $_GET[ 'key' ] ){
				$_POST[ 'table' ] = $_POST[ 'id' ];
				$cache_key = $_GET[ 'key' ];
			}
			
			if( isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] && $cache_key ){
				$t = $_POST[ 'table' ];

				$cl = new cCustomer_call_log();
				$cl->class_settings = $this->class_settings;

				$tb = 'c' . ucwords( $t );
				$cll = new $tb;
				
				$cl->label = $cll->label;
				$cl->table_name = $cll->table_name;
				$cl->table_fields = $cll->table_fields;

				if( isset( $cl->db_table_name ) && $cl->db_table_name ){
					$cl->db_table_name = $cll->db_table_name;
				}

				$cl->class_settings[ "frontend" ] = 1;

				$cl->class_settings[ 'datatable_settings' ]["show_refresh_cache"] = 0;
				$cl->class_settings[ 'datatable_settings' ]["show_add_new"] = 0;
				$cl->class_settings[ 'datatable_settings' ]["show_delete_button"] = 0;
				$cl->class_settings[ 'datatable_settings' ]["show_edit_button"] = 0;
				$cl->class_settings[ 'datatable_settings' ]["show_advance_search"] = 0;
				$cl->class_settings[ 'datatable_settings' ]['show_add_new'] = 0;
				$cl->class_settings[ 'datatable_settings' ]['show_delete_button'] = 0;
				$cl->class_settings[ 'datatable_settings' ]['utility_buttons'] = array();
					
				$cl->class_settings['title'] = 1;
				$cl->class_settings['hide_title'] = 1;
				
				$settings = array(
					'cache_key' => $cache_key,
					'directory_name' => 'search',
					'permanent' => true,
				);
				$d = get_cache_for_special_values( $settings );
				
				if( isset( $d[ 'query' ] ) && $d[ 'query' ] && isset( $d[ 'dataSource' ] ) && $d[ 'dataSource' ] ){

					if( isset( $this->class_settings[ 'return_query' ] ) && $this->class_settings[ 'return_query' ] ){
						return array( 'query' => $d[ 'query' ] );
					}
					
					$qp = $this->_get_query_parameters(
						array(
							"table" => $d[ 'dataSource' ],
							"primary_table" => $t,
						)
					);
					$join = isset( $qp["join"] )?$qp["join"]:'';
					
					// print_r( $d[ 'query' ] );exit;
					$cl->class_settings[ 'filter' ][ 'select_count' ] = " COUNT( DISTINCT `". $t ."`.`id` ) as 'count' ";
					$cl->class_settings[ 'filter' ][ 'group' ] = " GROUP BY `". $t ."`.`id` ";
					if( trim( $d[ 'query' ] ) )$cl->class_settings[ 'filter' ][ 'where' ] = " AND " . $d[ 'query' ];
					$cl->class_settings[ 'filter' ][ 'join_count' ] = $join;
					$cl->class_settings[ 'filter' ][ 'join' ] = $join;

					$cl->class_settings[ 'full_table' ] = 1;

					$cl->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';
					$return = $cl->customer_call_log();

					if( is_array( $return ) ){
						$return[ "javascript_functions" ] = $callback ? array_merge( array( $callback ), $return[ "javascript_functions" ] ) : $return[ "javascript_functions" ];
						$return[ "html_replacement_selector" ] = $handle;
					}

					return $return;
				}
			}
			
		}

		public function _get_query_parameters( $e = array() ){
			$join = '';
			if( isset( $e["table"] ) && $e["table"] && isset( $e["primary_table"] ) && $e["primary_table"] ){
				$tb1 = 'c' . $e["primary_table"];
				$tb2 = 'c' . $e["table"];
				
				if( class_exists( $tb2 ) && class_exists( $tb1 ) ){
					$cl = new $tb1();
					$cl2 = new $tb2();

					if( isset( $cl2->children ) && is_array( $cl2->children ) && ! empty( $cl2->children ) ){
						
						
						if( isset( $cl2->children[ $cl->table_name ] ) ){
							$af = array( 'link_field' => 'id', 'join' => "JOIN" );
							
							if( isset( $cl->table_fields[ $cl2->children[ $cl->table_name ][ 'link_field' ] ] ) ){
								$af['join_field'] = "`". $cl->table_name ."`.`". $cl->table_fields[ $cl2->children[ $cl->table_name ][ 'link_field' ] ] ."`";
							}else{
								$af['join_field'] = "`". $cl->table_name ."`.`id`";
							}
							
							$cl_s2 = array_merge( array( $e["table"] => $af ), $cl2->children );
							unset( $cl_s2[ $cl->table_name ] );
						}else{
							$cl_s2 = $cl2->children;
						}
						
						
						foreach( $cl_s2 as $key => $value ){
							$clx = 'c' . ucwords( $key );
							if( class_exists( $clx ) ){
								$cls = new $clx();

								if( isset( $value[ "link_field" ] ) && $value[ "link_field" ] ){
									$lf = '';
									if( isset( $cls->table_fields[ $value[ "link_field" ] ] ) ){
										$lf =  $cls->table_fields[ $value[ "link_field" ] ];
									}else if( $value[ 'link_field' ] == 'id' ){
										$lf = $value[ 'link_field' ];
									}
									
									$join_type = 'LEFT JOIN';
									if( isset( $value[ "join" ] ) && $value[ "join" ] ){
										$join_type = $value[ "join" ];
									}
									
									if( $lf ){
										$join_field = "`". $cl2->table_name ."`.`id`";
										if( isset( $value[ "join_field" ] ) && $value[ "join_field" ] ){
											$join_field = $value[ "join_field" ];
										}
										
										$join .= " ". $join_type ." `". $this->class_settings[ 'database_name' ] ."`.`". $cls->table_name ."` ON `". $cls->table_name ."`.`".$lf."` = ".$join_field." AND `". $cls->table_name ."`.`record_status` = '1' ";
									}
									
								}
							}
						}
					}
					
				}
			}
			
			return array(
				"join" => $join,
			);
		}
		
		protected function _display_notification( $e = array() ){

			$base = new cCustomer_call_log();
			$base->class_settings = $this->class_settings;
			return $base->_display_notification( $e );

		}

		public function _run_search_query2( $e = array() ){
			if( isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ){
				$action_to_perform = $this->class_settings[ 'action_to_perform' ];
				$plugin = isset( $_POST[ 'plugin' ] ) && $_POST[ 'plugin' ] ? $_POST[ 'plugin' ] : '';

				$dx = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], true ) : array();
				$additional_info = isset( $dx[ 'additional_info' ] ) ? $dx[ 'additional_info' ] : array();
				// unset( $dx ); // don't need it again. Maybe in d future
				// print_r( $additional_info );exit;
				
				$table = $_POST[ 'table' ];

				if( $plugin ){
					$p_cl = "c".ucwords( $plugin );
					if( class_exists( $p_cl ) ){
						$p_cls = new $p_cl();
						$pc = $p_cls->load_class( array( "class" => array( $table ) ) );
					}
				}

				$tb = 'c' . ucwords( $table );
				$tb = new $tb();
				$tb->class_settings = $this->class_settings;

				$mine_key = md5( "mine-" . $table . "-" . $this->class_settings["user_id"] );
				$mine_source = isset( $_GET[ 'mine_source' ] ) && $_GET[ 'mine_source' ] ? $_GET[ 'mine_source' ] : '';
				$mine_source_value = isset( $_GET[ 'sr' ] ) && $_GET[ 'sr' ] ? $_GET[ 'sr' ] : '';

				$result = array();
				$js = array( 'benefitProgram.viewResult' );

				$reference_tb = '';
				$filename = '';
				
				$handle = "dash-board-main-content-area";
				if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
					$handle = $this->class_settings["html_replacement_selector"];
				}

				switch( $action_to_perform ){
				case 'run_search_query2':
					$this->class_settings[ 'return_query' ] = 1;
					$this->class_settings[ 'add_status_in_join' ] = 'active';
					
					$tb->class_settings[ 'where' ] = '';
					if( isset( $tb->table_fields[ 'status' ] ) && $tb->table_fields[ 'status' ] ){
						// $tb->class_settings[ 'where' ] .= " AND `". $tb->table_name ."`.`". $tb->table_fields[ 'status' ] ."` IN ( '". implode( "','", explode( ',', $this->class_settings[ 'add_status_in_join' ] ) ) ."' ) ";
					}

					switch( $action_to_perform ){
					case 'run_search_query2':
						if( $mine_source_value ){
							$this->class_settings[ 'data' ][ 'social_registry' ] = $mine_source_value;
						}

						$this->class_settings[ 'action_to_perform' ] = 'run_search_query';
						$this->class_settings[ 'set_cache_query' ] = 1;
						$filename = 'query-result.php';
					break;
					}

					if( isset( $dx[ 'accept_empty' ] ) && $dx[ 'accept_empty' ] ){
						$this->class_settings[ 'accept_empty' ] = $dx[ 'accept_empty' ];
					}
					
					$this->class_settings[ 'cache_query_key' ] = $mine_key;
					$r = $this->search();
					$wwhere = '';
					// print_r( $r );exit;
					if( isset( $r[ 'query' ] ) && trim( $r[ 'query' ] ) ){
						$wwhere = ' AND '.$r[ 'query' ];
						$tb->class_settings[ 'where' ] .= $wwhere;
					}
					
					$result[ 'data_query' ] = $r;
					switch( $action_to_perform ){
					case 'run_search_query2':
						if( ! ( isset( $dx[ 'accept_empty' ] ) && $dx[ 'accept_empty' ] ) ){
							if( isset( $r[ 'msg' ] ) && $r[ 'msg' ] ){
								return $this->_display_notification( array( "type" => "error", "message" => $r[ 'msg' ] ) );
							}
						}

						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
						);

						if( isset( $r[ 'data' ][ 'select_count' ] ) && $r[ 'data' ][ 'select_count' ] ){
							// $tb->class_settings[ 'overide_select' ] = $r[ 'data' ][ 'select_count' ];
						}

						$drx = array();
						// print_r( strpos( $table, '_sr' ) );exit;
						// foreach( array( 'households', 'individuals' ) as $tbxz ){
						foreach( array( $table ) as $tbxz ){
							
							$jn = '';
							if( isset( $r[ 'data' ][ 'join' ] ) && $r[ 'data' ][ 'join' ] ){
								$jn = $r[ 'data' ][ 'join' ];
							}

							$queryx = " SELECT COUNT( DISTINCT `". $tbxz ."`.`id` ) as 'count' FROM `". $this->class_settings[ 'database_name' ] ."`.`". $table ."` ". $jn ." WHERE `". $table ."`.`record_status` = '1' ". $tb->class_settings[ 'where' ] ."  ";
								// print_r( $queryx );exit;

							$query_settings[ 'query' ] = $queryx;
							$query_settings[ 'tables' ] = array( $tbxz );
            				$dxz = execute_sql_query($query_settings);
            				// print_r( $dxz );exit;

            				if( isset( $dxz[0][ 'count' ] ) && $dxz[0][ 'count' ] ){
            					$drx[0][ $tbxz ] = $dxz[0][ 'count' ];
            				}else{
            					$drx[0][ $tbxz ] = 0;
            				}
						}
						$r2 = $drx;
						
					break;
					default:
						$r2 = $tb->_get_records();
					break;
					}
					// print_r( $r );exit;

					$result[ 'data_source' ] = $tb->table_name;
					$result[ 'mine_source_value' ] = $mine_source_value;
					$result[ 'mine_source' ] = $mine_source;
					$result[ 'mine_key' ] = $mine_key;
					$result[ 'stats' ] = is_array( $r2 ) ? $r2 : array();

					if( isset( $_GET[ 'callback' ] ) && $_GET[ 'callback' ] ){
						$js[] = $_GET[ 'callback' ];
					}

				break;
				}
					// print_r( $result );exit;

				if( $plugin )$result["plugin"] = $plugin;
				if( ! empty( $additional_info ) )$result["additional_info"] = $additional_info;

				$return = array();
				
				$return["status"] = "new-status";						
				
				$return["javascript_functions"] = ( isset( $_POST[ 'callback' ] ) ? array_merge( array( $_POST[ 'callback' ] ), $js ) : $js );
				
				// print_r( $result );exit;
				switch( $action_to_perform ){
				case 'run_search_query2':
					$pd = $this->class_settings[ 'data' ];
					$return[ 'status' ] = 'new-status';
					$return["javascript_functions"] = ( isset( $_GET[ 'callback' ] ) ? array_merge( array( $_GET[ 'callback' ] ), $js ) : $js );
					$this->class_settings[ 'data' ] = $result;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/search/advance-search-window/' . $filename );

					$return["html_replacement_selector"] = "#" . $handle;

					$base = new cCustomer_call_log();
					$base->class_settings = $this->class_settings;

					$return["html_replacement"] = $base->_get_html_view();
					$this->class_settings[ 'data' ] = $pd;
					
					$_POST["hidden_fields"] = json_encode( array( "table" => $table ) );
					
					$cls = new cMyexcel();
					$cls->class_settings = $this->class_settings;
					$cls->class_settings["action_to_perform"] = 'generate_csv';
					$cls->class_settings["show_all_fields"] = 1;
					$cls->class_settings["return_html"] = $handle;

					if( isset( $this->class_settings[ 'myexcel_action' ] ) && $this->class_settings[ 'myexcel_action' ] ){
						$cls->class_settings["data"][ 'action' ] = $this->class_settings[ 'myexcel_action' ];
					}
					if( isset( $this->class_settings[ 'myexcel_todo' ] ) && $this->class_settings[ 'myexcel_todo' ] ){
						$cls->class_settings["data"][ 'todo' ] = $this->class_settings[ 'myexcel_todo' ];
					}

					$cls->class_settings["other_data"] = isset( $this->class_settings[ 'other_data' ] ) ? $this->class_settings[ 'other_data' ] : array();

					if( isset( $dx[ 'configurations' ] ) && $dx[ 'configurations' ] ){
						$cls->class_settings["other_data"][ 'configurations' ] = $dx[ 'configurations' ];
					}

					// print_r( $r );exit;
					if( isset( $r[ 'filter_data' ][ 'cart_items' ] ) && $r[ 'filter_data' ][ 'cart_items' ] ){
						foreach( $r[ 'filter_data' ][ 'cart_items' ] as &$rv ){
							if( isset( $rv[ 'data' ] ) && ! empty( $rv[ 'data' ] ) ){
								foreach( $rv[ 'data' ] as &$ry ){
									unset( $ry[ 'id' ] );
									unset( $ry[ 'sub_query_text' ] );
									unset( $ry[ 'table_name_text' ] );
									unset( $ry[ 'table_name' ] );
									unset( $ry[ 'condition_text' ] );
									unset( $ry[ 'undefined' ] );
									unset( $ry[ 'search' ] );
									unset( $ry[ 'options_tags' ] );
									unset( $ry[ 'undefined' ] );
									unset( $ry[ 'options_text' ] );
									unset( $ry[ 'logical_operator_text' ] );
								}
							}
						}
						// $cls->class_settings["other_data"]["query"]["where"] = $wwhere;
						if( ! ( isset( $cls->class_settings["other_data"]["query"][ 'where' ] ) && $cls->class_settings["other_data"]["query"][ 'where' ] ) ){
							unset( $cls->class_settings["other_data"]["query"][ 'where' ] );
						}
						$cls->class_settings["other_data"]["query"]["query_options"] = $r[ 'filter_data' ][ 'cart_items' ];
					}

					$r = $cls->myexcel();
					
					// print_r( $cls->class_settings["other_data"] );exit;
					if( isset( $r["html_replacement"] ) ){
						$return["html_replacement_one"] = $r["html_replacement"];
						$return["html_replacement_selector_one"] = '#create-mine';
						$return["javascript_functions"][] = 'prepare_new_record_form_new';
					}
				break;
				}
				
				return $return;
			}	
		}

		public function _execute_search_window_query( $e = array() ){
			$error_msg = '';
			$q = '';
			$first = 1;
			$save_query = isset( $_POST[ 'save_query' ] ) ? $_POST[ 'save_query' ] : '';
			
			if( isset( $_POST["set_cache_query"] ) ){
				$this->class_settings[ 'set_cache_query' ] = $_POST["set_cache_query"];
			}
			$pplugin = isset( $_POST[ 'plugin' ] ) ? $_POST[ 'plugin' ] : '';
			$ptable = isset( $_POST[ 'table' ] ) ? $_POST[ 'table' ] : '';
			$ptable2 = isset( $_POST[ 'db_table' ] ) ? $_POST[ 'db_table' ] : '';
			
			if( $pplugin ){
				$p_cl = "c".ucwords( $pplugin );
				if( class_exists( $p_cl ) ){
					$p_cls = new $p_cl();
					$p_cls->load_class( array( "class" => array( $ptable ) ) );
				}
			}
			
			// print_r( print_r( isset( $_POST ) ) );exit;
			
			$base = new cCustomer_call_log();
			$base->class_settings = $this->class_settings;
	
			$tables = array();
			$ormWhere = array();
			
			if( isset( $e[ 'ptable' ] ) && $e[ 'ptable' ] && isset( $e[ 'tables' ] ) && $e[ 'tables' ] ){
				$ptable = $e[ 'ptable' ];
				$tables = $e[ 'tables' ];
			}else{
				
				if( isset( $_POST[ 'data' ] ) && $_POST[ 'data' ] ){
					
					$data = json_decode( $_POST[ 'data' ], true );
					$data[ 'conditions' ] = array();

					if( isset( $data[ 'cart_items' ] ) && ! empty( $data[ 'cart_items' ] ) ){
						if( ! $ptable && isset( $data[ 'dataSource' ] ) && $data[ 'dataSource' ] ){
							$ptable = $data[ 'dataSource' ];
						}
						$ctables = array();
						
						foreach( $data[ 'cart_items' ] as $kc => $vc ){
							$q1 = '';
							if( is_array( $vc ) && isset( $vc[ 'data' ] ) && ! empty( $vc[ 'data' ] ) ){
								
								$f1 = 0;
								foreach( $vc[ 'data' ] as $kq => $vq ){
									$oww = array();
									$q2 = '';
									$operand = '';
									
									if( $f1 && isset( $vq[ "logical_operator" ] ) && $vq[ "logical_operator" ] ){
										$operand = " " . $vq["logical_operator"];
									}
									
									//@nw5
									if( isset( $vq["db_table"] ) && $vq["db_table"] ){
										$vq["table_name"] = $vq["db_table"];
									}
									if( ! isset( $vq["table_name"] ) ){
										$vq["table_name"] = $ptable;
									}

									if( ! isset( $ctables[ $vq[ 'table_name' ] ] ) ){
										$xc = 'c'.$vq[ 'table_name' ];
										if( class_exists( $xc ) ){
											$xc = new $xc;
											$ctables[ $vq[ 'table_name' ] ] = $xc->table_fields;
										}
									}

									if( isset( $ctables[ $vq[ 'table_name' ] ][ $vq[ 'field' ] ] ) && $ctables[ $vq[ 'table_name' ] ][ $vq[ 'field' ] ] ){
										$vq[ 'field' ] = $ctables[ $vq[ 'table_name' ] ][ $vq[ 'field' ] ];
									}

									$tables[ $vq["table_name"] ] = $vq["table_name"];
									
									$oww = array(
										'key' => array(
											'table' => $vq[ "table_name" ],
											'field' => $vq[ "field" ],
										),
									);

									if( ( isset( $vq[ 'start_date' ] ) && isset( $vq[ 'end_date' ] ) ) || ( isset( $vq[ 'min' ] ) && isset( $vq[ 'max' ] ) ) ){
										
										switch( $vq[ 'condition' ] ){
										case 'between':
											
											$oww[ 'condition' ] = 'BETWEEN';
											if( isset( $vq[ 'min' ] ) ){
												$oww[ 'min' ] = $vq[ "min" ];
												$oww[ 'max' ] = $vq[ "max" ];
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` BETWEEN '" . $vq[ "min" ] . "' AND '" . $vq[ "max" ] . "' )";
											}else if( isset( $vq[ 'start_date' ] ) ){
												$oww[ 'min' ] = convert_date_to_timestamp( $vq[ "start_date" ], 1 );
												$oww[ 'max' ] = convert_date_to_timestamp( $vq[ "end_date" ], 2 );
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` BETWEEN " . convert_date_to_timestamp( $vq[ "start_date" ], 1 ) . " AND " . convert_date_to_timestamp( $vq[ "end_date" ], 2 ) . " )";
											}
											
										break;
										default:
											$gt = '';
											$lt = '';
											switch( $vq[ 'condition' ] ){
											case 'gte_lte':
												$gt = '>=';
												$lt = '<=';
											break;
											case 'gt_lt':
												$gt = '>';
												$lt = '<';
											break;
											}

											if( isset( $vq[ 'min' ] ) ){
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $gt,
													'value' => $vq[ "min" ],
												);
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $lt,
													'value' => $vq[ "max" ],
												);
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $gt ." '" . $vq[ "min" ] . "' AND `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $lt ." '" . $vq[ "max" ] . "' )";
											}else if( isset( $vq[ 'start_date' ] ) ){
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $gt,
													'value' => $vq[ "start_date" ],
												);
												$ormWhere[ 'and' ][] = array(
													'key' => array(
														'table' => $vq[ "table_name" ],
														'field' => $vq[ "field" ],
													),
													'condition' => $lt,
													'value' => $vq[ "end_date" ],
												);
												$q2 .= "( `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $gt ." '" . $vq[ "start_date" ] . "' AND `" . $vq[ "table_name" ] . "`.`" . $vq[ "field" ] . "` ". $lt ." '" . $vq[ "end_date" ] . "' )";
											}
											
										break;
										}
										
									}else{
										$q2 .= " `" . $vq[ 'table_name' ] . "`.`" . $vq[ 'field' ] . "` ";
										
										$cdn = '';
										switch( $vq[ 'condition' ] ){
										case 'equal_to':
											$cdn = '=';
										break;
										case 'not_equal_to':
											$cdn = '<>';
										break;
										case 'less_than':
											$cdn = '<';
										break;
										case 'greater_than':
											$cdn = '>';
										break;
										case 'less_than_equal_to':
											$cdn = '<=';
										break;
										case 'greater_than_equal_to':
											$cdn = '>=';
										break;
										case 'contains':
											$cdn = 'regexp';
										break;
										case 'in':
											$cdn = "IN ";
										break;	
										case 'not_in':
											$cdn = "NOT IN ";
										break;
										}

										$q2 .= $cdn;

										switch( $vq[ 'condition' ] ){
										case 'in':
										case 'not_in':
											$q2 .= " ( ";
											switch( $vq[ 'condition' ] ){
											case 'in':
												$oww[ 'condition' ] = ' IN ';
											break;
											case 'not_in':
												$oww[ 'condition' ] = ' NOT IN ';
											break;
											}
											$sv2 = nl2br( $vq[ 'search_value' ] );
											if( strpos( $sv2, "<br />" ) > -1 ){
												$vq[ 'search_value' ] = preg_replace( "/\r|\n/", ",", $vq[ 'search_value' ] );
											}
											
											$oww[ 'values' ] = explode( ',', $vq[ 'search_value' ] );
											$q2 .= "'" . implode( "','", explode( ',', $vq[ 'search_value' ] ) ) . "'";
											$q2 .= ' ) ';
										break;
										default:	
											$oww[ 'condition' ] = $cdn;
											$oww[ 'value' ] = $vq[ 'search_value' ];
											$q2 .= " '" . $vq[ 'search_value' ] . "' ";
										break;
										}
									}
									
									$q1 .= $operand . $q2;

									if( ! isset( $data[ 'conditions' ] ) )$data[ 'conditions' ] = array();

									$opd = $operand ? $operand : 'and';
									if( isset( $oww[ 'condition' ] ) && $oww[ 'condition' ] ){
										$ormWhere[ trim( $opd ) ][] = $oww;
									}
									$data[ 'conditions' ][ $vq["table_name"] ][] = array(
										'query' => $q2,
										'operand' => $opd , 
									);
									$f1++;
								}
							}else{
								continue;
							}
							
							if( isset( $vc[ 'condition' ] ) && $vc[ 'condition' ] && $q1 ){
								$q .= $vc[ 'condition' ] . ' ';
							}
							$q .= ' ( ' . $q1 . ' ) ';
							// $q .= $q1;
						}
					}
				}
			}

			if( isset( $e[ 'return_query' ] ) && $e[ 'return_query' ] ){
				if( class_exists( 'cNwp_orm' ) && ! empty( $ormWhere ) ){
					return $ormWhere;
				}else{
					return $q;
				}
			}
			
			// print_r( $q );exit;
			// if( isset( $this->class_settings[ 'mike' ] ) )print_r( $tables );exit;
			$stats_data = array();
			$select_count = "";
			
			if( isset( $this->class_settings[ 'accept_empty' ] ) && $this->class_settings[ 'accept_empty' ] ){
				if( empty( $tables ) ){
					$tables = array( $ptable => $ptable );
				}
				
			}
			// print_r( $tables );exit;
			if( ! empty( $tables ) && $ptable ){
				$join = '';
				$group = '';
				if( isset( $tables[ $ptable ] ) )unset( $tables[ $ptable ] );
				
				$tb = 'c' . ucwords( $ptable );

				if( class_exists( $tb ) ){
					$cl = new $tb();
					//@nw5
					if( $ptable2 ){
						$ptable = $ptable2;
						$cl->table_name = $ptable2;
					}

					$stats_data[ $cl->table_name ] = $cl->label;
					if( isset( $cl->children ) && is_array( $cl->children ) && ! empty( $cl->children ) ){
						$select_count = " COUNT( DISTINCT `". $cl->table_name ."`.`id` ) as '". $cl->table_name ."'";

						foreach( $cl->children as $key => $value ){
							$clx = 'c' . ucwords( $key );
							$cls = new $clx();
							
							$join .= " LEFT JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $key ."` ON `". $key ."`.`". ( $value[ 'link_field' ] == 'id' ? 'id' : $cls->table_fields[ $value[ 'link_field' ] ] ) ."` = `". $ptable ."`.`id` AND `". $key ."`.`record_status` = '1' ";
							
							$select_count .= ", COUNT( DISTINCT `". $key ."`.`id` ) as '". $cls->table_name ."' ";
							$stats_data[ $cls->table_name ] = $cls->label;
						}
					}

					if( $join ){
						if( isset( $e[ 'get_join' ] ) && $e[ 'get_join' ] ){
							return $join;
						}

						$group = " GROUP BY `". $ptable ."`.`id` ";
					}

					$base->table_name = $ptable;
					$base->class_settings[ 'where' ] = "";
					if( isset( $this->class_settings[ 'accept_empty' ] ) && $this->class_settings[ 'accept_empty' ] ){
						if( ! $q ){
							$q = ' ';
						}
					}

					if( $q ){

						$data[ 'query' ] = $q;
						if( class_exists( 'cNwp_orm' ) && ! empty( $ormWhere ) ){
							$data[ 'query' ] = $ormWhere;
						}
						if( $save_query ){
							foreach( $data[ 'conditions' ][ $vq["table_name"] ] as & $fv ){
								$fv[ 'query' ] = addslashes( $fv[ 'query' ] );
							}

							$data[ 'query' ] = addslashes( $data[ 'query' ] );
							$f = new cFiles();
							$f->class_settings = $this->class_settings;
							$f->class_settings[ 'action_to_perform' ] = 'save_line_items';

							$f->class_settings[ 'line_items' ][] = array(
								'date' => time(),
								'state' => '',
								'lga' => '',
								'ward' => '',
								'community' => '',
								'name' => $save_query,
								'description' => '',
								'content' => rawurlencode( json_encode( $data ) ),
								// 'content' => json_encode( addslashes( $data ) ),
								'file_url' => '',
								'type' => 'search',
								'reference' => '',
								'reference_table' => $ptable,
							);

							$f->files();
						}
						
						if( isset( $this->class_settings[ 'set_cache_query' ] ) && $this->class_settings[ 'set_cache_query' ] ){
							$cache_key = isset( $this->class_settings[ 'cache_query_key' ] )?$this->class_settings[ 'cache_query_key' ]: md5( $ptable . "-" . $this->class_settings["user_id"] . "-search" );
							
							$settings = array(
								'cache_key' => $cache_key,
								'cache_values' => $data,
								'directory_name' => 'search',
								'permanent' => true,
							);
							set_cache_for_special_values( $settings );
						}
						
						// print_r( $q );exit;
						$s = array(
							"select" => 'SELECT',
							"from" => 1,
							"clear" => 1,
							"group" => $group,
							"join" => $join,
							"where" => "WHERE `".$ptable."`.`record_status` = '1' " . " AND ( " . $q . ") ",
							"table" => $ptable,
							"select_count" => $select_count,
							"stats_data" => $stats_data,
							"database" => $this->class_settings["database_name"],
						);
						//stop here data, s and ptable
						if( isset( $this->class_settings[ 'return_query' ] ) && $this->class_settings[ 'return_query' ] ){
							unset( $data["fields"] );
							
							$array =  array(
								'table' => $ptable,
								'data' => $s,
								'query' => $q,
								'stats_data' => $stats_data,
							);
							
							if( ! ( isset( $this->class_settings[ 'exclude_filter_data' ] ) && $this->class_settings[ 'exclude_filter_data' ] ) ){
								$array['filter_data'] = $data;
							}
							
							return $array;
						}
						$this->_set_search_query( $s );
						
					}
				}
				
				$r1 = $base->_display_notification( array( "message" => "<h4>Search Query Executed</h4><p>Successful execution of search query</p>", "type" => "success" ) );
				unset( $r1["html"] );
				
				$return = array(
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( '$nwProcessor.reload_datatable' ),
				);
				//$return = array_merge( $r1, $base->_search_form() );
				$return = array_merge( $r1, $return );
				
				if( ! is_array( $return[ 'javascript_functions' ] ) ){
					$return[ 'javascript_functions' ] = array();
				}
				$return[ 'javascript_functions' ][] = 'nwSearch.closeModal';
				$return[ 'javascript_functions' ][] = 'nwSearch.showClearSearch';

				return $return;
			}else{
				$error_msg = "Invalid Dataset to Query";
			}
			
			return $base->_display_notification( array( "message" => $error_msg ) );
		}

		private function _search_window(){
			$js = array( 'prepare_new_record_form_new', 'nwSearch.init' );
			$html = '';
			$d = isset( $this->class_settings[ 'search_options' ] )?$this->class_settings[ 'search_options' ]:array();
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			$title = '';
			$table_name = '';
			
			$error_msg = '';
			$filename = 'advance-search-window';
			
			$t = isset( $d[ "key" ] )?$d[ "key" ]:'';
			
			//@nw5
			$t1 = '';
			
			if( isset( $_GET["table"] ) && $_GET["table"] ){
				//@nw5
				if( isset( $_GET["real_table"] ) && $_GET["real_table"] ){
					$t1 = $_GET["table"];
					$_GET["table"] = $_GET["real_table"];
				}
				
				$t = strtolower( trim( $_GET["table"] ) );
				
				if( isset( $_GET["plugin"] ) && $_GET["plugin"] ){
					$p_cl = "c".ucwords( $_GET["plugin"] );
					
					if( class_exists( $p_cl ) ){
						$p_cls = new $p_cl();
						$p_cls->load_class( array( "class" => array( $t ) ) );
						
						$d[ "plugin" ] = $_GET["plugin"];
						$d[ "tables" ][ $t ][ 'plugin' ] = $_GET["plugin"];
					}else{
						$error_msg = 'Unregistered Plugin';
					}
				}
				
				$cl = "c" . ucwords( $t );
				if( class_exists( $cl ) ){
					$cls = new $cl();
					
					if( isset( $cls->table_name ) ){
						$table_name = isset( $cls->label )?$cls->label:$cls->table_name;
						$d[ "tables" ][ $cls->table_name ][ 'text' ] = $table_name;
						$d[ "cart_data" ][ 'fields' ][ $t ] = $t();

						if( isset( $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $t ] ) && $d[ 'cart_data' ][ 'configurations' ] ){
							foreach( $d[ "cart_data" ][ 'fields' ][ $t ] as $dvv => $dtt ){
								if( isset( $dtt[ 'field_identifier' ] ) && in_array( $dtt[ 'field_identifier' ], $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $t ] ) ){
									unset( $d[ "cart_data" ][ 'fields' ][ $t ][ $dvv ] );
								}
							}
						}
						
						if( isset( $cls->children ) && is_array( $cls->children ) && ! empty( $cls->children ) ){
							foreach( $cls->children as $ct => $ch ){
								$cl1 = "c" . ucwords( $ct );
								if( class_exists( $cl1 ) ){
									$cls1 = new $cl1();
									$d[ "tables" ][ $cls1->table_name ][ 'text' ] = isset( $cls1->label )?$cls1->label:$cls1->table_name;
									$d[ "cart_data" ][ 'fields' ][ $ct ] = $ct();
								
									if( isset( $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $ct ] ) && $d[ 'cart_data' ][ 'configurations' ] ){
										foreach( $d[ "cart_data" ][ 'fields' ][ $ct ] as $dvv => $dtt ){
											if( isset( $dtt[ 'field_identifier' ] ) && in_array( $dtt[ 'field_identifier' ], $d[ 'cart_data' ][ 'configurations' ][ 'exclude_fields' ][ $ct ] ) ){
												unset( $d[ "cart_data" ][ 'fields' ][ $ct ][ $dvv ] );
											}
										}
									}
									
								}
							}
						}
					}
				}
				
				
			}
			
			if( ! ( isset( $d[ "tables" ] ) && is_array( $d[ "tables" ] ) && ! empty( $d[ "tables" ] ) ) ){
				$error_msg = '<h4>Undefined Search Tables</h4>Please try again';
			}

			if( ! $error_msg ){	
				//@nw5
				if( $t1 ){
					$d[ "real_table" ] = $t;
					$d[ "db_table" ] = isset( $_GET["db_table"] )?$_GET["db_table"]:'';
					
					$d[ "tables" ][ $t ][ 'real_table' ] = $t;
					$d[ "tables" ][ $t ][ 'db_table' ] = $d[ "db_table" ];
					$d[ "tables" ][ $t ][ 'o_table' ] = $t1;
				}
				
				$d[ "db_table_filter" ] = isset( $_GET["db_table_filter"] )?$_GET["db_table_filter"]:'';
				$d[ "tables" ][ $t ][ 'db_table_filter' ] = $d[ "db_table_filter" ];
			}
			
			if( isset( $this->class_settings[ 'data' ][ 'action' ] ) && $this->class_settings[ 'data' ][ 'action' ] ){
				$d[ 'action' ] = $this->class_settings[ 'data' ][ 'action' ];
			}

			if( isset( $this->class_settings[ 'data' ][ 'todo' ] ) && $this->class_settings[ 'data' ][ 'todo' ] ){
				$d[ 'todo' ] = $this->class_settings[ 'data' ][ 'todo' ];
			}

			if( isset( $this->class_settings[ 'data' ][ 'params' ] ) && $this->class_settings[ 'data' ][ 'params' ] ){
				$d[ 'params' ] = $this->class_settings[ 'data' ][ 'params' ];
			}

			if( isset( $this->class_settings[ 'data' ][ 'return_html' ] ) && $this->class_settings[ 'data' ][ 'return_html' ] ){
				$d[ 'preview_results' ] = 1;
				// return $html;
			}
			
			$base = new cCustomer_call_log();
			$base->class_settings = $this->class_settings;
			
			if( ! $error_msg ){
				$title = ': ' . $table_name;
				
				if( isset( $this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ] ) && $this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ] ){
					$old_search = $this->class_settings[ 'data' ][ 'data' ][ 'filter_data' ];
				}else{
					$settings = array(
						'cache_key' => md5( $t . "-" . $this->class_settings["user_id"] . "-search"),
						'directory_name' => 'search',
						'permanent' => true,
					);
					$old_search = get_cache_for_special_values( $settings );
				}
				
				//print_r( $old_search ); exit;
				
				if( isset( $old_search["cart_items"] ) ){
					$d["old_search"] = $old_search;
				}
				
				$d[ 'parent_table' ] = $t;
				
				$base->class_settings[ 'data' ] = $d;
				$base->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				$html = $base->_get_html_view();
				
				$base->class_settings["modal_dialog_style"] = ' width:60%; ';
				$base->class_settings["modal_dialog_class"] = 'modal-lg';
				switch( $action_to_perform ){
				case 'bulk_edit':
					$base->class_settings["modal_title"] = 'Bulk Edit Window' . $title;
				break;
				default:
					$base->class_settings["modal_title"] = 'Advance Search Window' . $title;
				break;
				}


				if( isset( $this->class_settings[ 'data' ][ 'return_html' ] ) && $this->class_settings[ 'data' ][ 'return_html' ] ){
					return $html;
				}
				
				return $base->_launch_popup( $html, '#dash-board-main-content-area', $js );
			}
			
			return $base->_display_notification( array( "message" => $error_msg ) );
		}
		
		private function _search(){
			$returning_html_data = '';
			
			//Check if edit mode is on
			$values = array();
			$values[3] = 'none';
			
			//Check if Table to Search isset
			if(isset($_GET['search_table']) && $_GET['search_table']){
				//Validate Table
				$classname = $_GET['search_table'];
				
				//Return Searchable Form by directly inheriting new method of class
				$result_of_sql_query = 'c'.ucwords($classname);
				
				$module = new $result_of_sql_query();
				$module->class_settings = $this->class_settings;
				
				$module->class_settings['action_to_perform'] = 'create_new_record';
				$module->class_settings['searching'] = 1;
				
				$module->class_settings['form_heading_title'] = 'Advance Search';
				$module->class_settings['form_action'] = '?action='.$this->table_name.'&todo=perform_search&search_table='.$classname;
				$module->class_settings['form_html_id'] = 'searchable';
				$module->class_settings['form_submit_button'] = 'Search';
				
				$data = $module->$classname();
				
				return array(
					'html_replacement' => $returning_html_data.$data['html'],
					'html_replacement_selector' => "#form-content-area",
					'method_executed' => $this->class_settings['action_to_perform'],
					//'status' => 'display-advance-search-form',
					'status' => 'new-status',
					'message' => 'Returned advance search form',
					'javascript_functions' => array( "prepare_new_record_form_new", "bind_search_field_select_control" ),
				);
				
			}else{
				//Log Error and return error message
				$returning_html_data .= '<h3>No Searchable Dataset was found!</h3>';
				return array('html' => $returning_html_data);
			}
		}
		
		private function _perform_search(){
			//SET TABLE
			$save = 0;
			$query = '';
			
			$search_table = $this->table_name;
			
			//Check if Table to Search was set
			if(isset($_GET['search_table']) && $_GET['search_table']){
				//Validate Table
				$search_table = $_GET['search_table'];
			}
			
            $classname = $search_table;
            //Return Searchable Form by directly inheriting new method of class
            $result_of_sql_query = 'c'.ucwords($classname);
            
            $module = new $result_of_sql_query();
            $module->class_settings = $this->class_settings;
            $module->class_settings['action_to_perform'] = '';
            $module->$classname();
			/**************************************************************************/
			/*****************RECIEVE USER INPUT FROM FILLED FORM**********************/
			/**************************************************************************/
			//Validate Table
			if(isset($_POST['table']) && $_POST['table']){
				//CHANGE TABLE TO SEARCHABLE TABLE
				$this->table_name = $_POST['table'];
				
				$multiple_search_condition = '';
				if(isset($_POST['multiple_search_condition']) && ($_POST['multiple_search_condition']=='OR' || $_POST['multiple_search_condition']=='AND' ))
					$multiple_search_condition = $_POST['multiple_search_condition'];
				
				$single_search_condition = '';
				if(isset($_POST['single_search_condition']) && ($_POST['single_search_condition']=='OR' || $_POST['single_search_condition']=='AND' ))
					$single_search_condition = $_POST['single_search_condition'];
				
				//2. PREPARE FORM OPTIONS
				//GET ALL FIELDS IN TABLE
				$fields = array();
				$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect'=>$this->class_settings['database_connection'],
					'query'=>$query,
					'query_type'=>'DESCRIBE',
					'set_memcache'=>1,
					'tables'=>array($this->table_name),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if($sql_result && is_array($sql_result)){
					foreach($sql_result as $sval)
						$fields[] = $sval[0];
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError('000001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cSearch.php';
					$err->method_in_class_that_triggered_error = '_perform_search';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 125';
					return $err->error();
				}
				
				/**************************************************************************/
				/**************************SELECT FORM GENERATOR***************************/
				/**************************************************************************/
				$form = new cForms();
				$form->setDatabase($this->class_settings['database_connection'],$this->table_name);
				$form->setFormActionMethod('','post');
				$form->uid = $this->class_settings['user_id']; //Currently logged in user id
				$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
				$form->maxstep = 1;
				$form->step = 1;
				
				$form->searching = 1;	//Set Search Mode
				
				/**************************************************************************/
			
				//2. Transform posted form data into array
				$field_values_pair = $form->myphp_post($fields);
				
				//3. Update the current step
				$form->step = $form->nextstep;
				
				//4. Pick current record id
				$this->record_id = $form->record_id;
				$values[0] = $form->record_id;
				
				
				//5. Insert array into database
				if(isset($field_values_pair) && is_array($field_values_pair)){
					//print_r($field_values_pair['field'].'--'.$field_values_pair['value']);
					//exit;
					
					//6. Update existing record
					$settings_array = array(
						'database_name' => $this->class_settings['database_name'] ,
						'database_connection' => $this->class_settings['database_connection'] ,
						'table_name' => $this->table_name ,
						'field_and_values' => $field_values_pair['form_data'] ,
						'where_fields' => 'id' ,
						'where_values' => $field_values_pair['id'] ,
						'where_condition' => $single_search_condition ,
					);
					$save = search( $settings_array );
					
					//$save = search( $this->class_settings['database_name'],$this->table_name,$this->class_settings['database_connection'] , $field_values_pair['search_condition'],$field_values_pair['field'],$field_values_pair['value'],$single_search_condition );
					
					if(is_array($save) && isset($save[0]) && isset($save[1]) && isset($save[2])){
						//Set Temporary Search Query for Ajax_server
						$s = array(
							"select" => $save[0],
							"from" => $save[1],
							"where" => $save[2],
							"table" => $search_table,
						);
						$this->_set_search_query( $s );
						
						$query = convert_to_highlevel_query( $_SESSION[$sq][$search_table]['where'] , $this->table_name );
					}
				}else{
					if($field_values_pair == '-1'){
						//RETURN INVALID TOKEN ERROR
						$err = new cError('000002');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cSearch.php';
						$err->method_in_class_that_triggered_error = '_perform_search';
						$err->additional_details_of_error = 'invalid token on line 167 during transformation';
							
						return $err->error();
					}else{
						//RETURN ERROR IN SUBMITTED DATA STRUCTURE
						$err = new cError('000101');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cSearch.php';
						$err->method_in_class_that_triggered_error = '_perform_search';
						$err->additional_details_of_error = 'invalid data submitted via data capture form';
						
						return $err->error();
					}
				}
			}
			
			if($save){
				
				
				//RETURN SUCCESS NOTIFICATION
				$err = new cError('040101');
				$err->action_to_perform = 'notify';
				
				$array = $err->error();
			
				
				$sq = md5('search_query'.$_SESSION['key']);
				$_SESSION[$sq][$search_table]['query'] = $query;
				
				//Append Search Query to Returned Value
				$array['search_query'] = strip_tags( $query );
				
				$array['html_replacement'] = strip_tags( $query );
				$array['html_replacement_selector'] = "#search-title";
				$array['searched_table'] = $search_table;
				$array['status'] = 'new-status';//'reload-datatable';
				$array[ 'data_table_name' ] = $search_table;
				
				unset( $array["html"] );
				//$array[ 'javascript_functions' ] = array( "" );
				
				return $array;
			}
			
			return $save;
		}

		public function _set_search_query( $opt = array() ){
			if( isset( $opt[ 'table' ] ) && $opt[ 'table' ] && isset( $opt[ 'select' ] ) && $opt[ 'select' ] && isset( $opt[ 'from' ] ) && $opt[ 'from' ]  && isset( $opt[ 'where' ] ) && $opt[ 'where' ] ){
				$search_table = $opt[ 'table' ];
				$sq = md5('search_query'.$_SESSION['key']);
				$_SESSION[$sq][$search_table]['select'] = $opt[ 'select' ];
				$_SESSION[$sq][$search_table]['from'] = $opt[ 'from' ];
				
				$_SESSION[$sq][$search_table]['join'] = $opt[ 'join' ];
				$_SESSION[$sq][$search_table]['group'] = $opt[ 'group' ];
				
				if( isset( $opt[ 'clear' ] ) && $opt[ 'clear' ] ){
					unset( $_SESSION[$sq][$search_table]['where'] );
				}
				
				$w = $opt[ 'where' ];
				//Check if query was previously set
				if(isset($_SESSION[$sq][$search_table]['where']) && $_SESSION[$sq][$search_table]['where']){
					$w = str_replace("WHERE","",$w);
					$_SESSION[$sq][$search_table]['where'] .= " ".$multiple_search_condition.str_replace("AND `".$this->table_name."`.`record_status`='1'","",$w);
				}else{
					$_SESSION[$sq][$search_table]['where'] = $w;
				}
				
				
				$_SESSION[ $sq ][ $search_table ]['query'] = "Advance Search";
				
				$_SESSION['search_trigger'] = 1;
			}
		}
		
		private function _clear_search(){
			$returning_html_data = '';
			
			//Check if Table to Search isset
			$action_to_perform = $this->class_settings["action_to_perform"];
			$js = array( '$nwProcessor.reload_datatable' );
			
			switch( $action_to_perform ){
			case "clear_search_window":
				if( isset($_GET['table']) && $_GET['table'] ){
					$_GET['search_table'] = $_GET['table'];
				}
				
				$js[] = 'nwSearch.hideClearSearch';
			break;
			}
			
			if(isset($_GET['search_table']) && $_GET['search_table']){
				//Validate Table
				$classname = $_GET['search_table'];
				
				//Clear Search Query
				$sq = md5('search_query'.$_SESSION['key']);
				if(isset($_SESSION[$sq][$classname])){
					unset($_SESSION[$sq][$classname]);
					
					$_SESSION['search_trigger'] = 1;
					
					//RETURN SEARCH QUERY SUCCESSFULLY CLEARED NOTIFICATION
					$err = new cError('040201');
					$err->action_to_perform = 'notify';
					
					$array = $err->error();
					$array['searched_table'] = $classname;
					$array['search_query'] = '';
					$array['status'] = 'reload-datatable';
					
					$array['javascript_functions'] = $js;
					
					$array['html_replacement'] = "&nbsp;";
					$array['html_replacement_selector'] = "#search-title";
					$array['status'] = 'new-status';//'reload-datatable';
					
					unset( $array["html"] );
							
					$settings = array(
						'cache_key' => md5( $classname . "-" . $this->class_settings["user_id"] . "-search"),
						'directory_name' => 'search',
						'permanent' => true,
					);
					clear_cache_for_special_values( $settings );
					
					return $array;
				}
			}
			
			//RETURN NO SEARCH QUERY FOUND NOTIFICATION
			$err = new cError('040202');
			$err->action_to_perform = 'notify';
			
			return $err->error();
		}
		
	}
?>
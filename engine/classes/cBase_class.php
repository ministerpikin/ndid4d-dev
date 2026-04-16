<?php
	/**
	 * customer_call_log Class
	 *
	 * @used in  				customer_call_log Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	customer_call_log
	 */
	
	class cBase_class{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'customer_call_log';
		public $table_name_static = 'customer_call_log';
		public $customer_source = 'customers';
		
		public $default_reference = "customer";


		//php 8.2 Dynamic Property Notice=============== @steve
		public $plugin_instance; 
		public $plugin;
		public $view_dir;
		public $view_path;
		public $plugin_dir;
		private $table_labels;
		private $last_query;
		// //php 8.2 Dynamic Property Notice============
		
		
		public $default_reference_field = "";
		public $reset_members_cache = "";
		
		public $label = 'Customers Call Log';
		
		public $build_list = 0;
		public $build_list_condition = '';
		public $build_list_field = '';
		public $build_list_field_bracket = '';
		
		public $refresh_cache_after_save_line_items = 1;
		
		private $associated_cache_keys = array(
			'customer_call_log',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'customer_call_log001',
			'customer' => 'customer_call_log002',
			'reason_for_call' => 'customer_call_log003',
			'feedback' => 'customer_call_log004',
			'category' => 'customer_call_log005',
			'comment' => 'customer_call_log006',
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
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
			
		}
	
		function base_class(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					return $this->_display_notification( array( "type" => 'no_language', "message" => 'no language file') );
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'split_datatable':
			case 'display_all_records':
			case 'display_deleted_records':
				$returned_value = $this->_display_data_table();
			break;
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
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
			break;
			case 'delete_with_query':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
				$returned_value = $this->_new_popup_form();
			break;
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
			break;
			case 'delete_with_prompt':
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form();
			break;
			}
			
			if( isset( $this->class_settings["success_callback_action"] ) && $this->class_settings["success_callback_action"] ){
				$returned_value['re_process'] = 1;
				$returned_value['re_process_code'] = 1;
				$returned_value['mod'] = isset( $this->class_settings["success_callback_mod"] )?$this->class_settings["success_callback_mod"]:'-';
				$returned_value['id'] = isset( $this->class_settings["success_callback_id"] )?$this->class_settings["success_callback_id"]:1;
				$returned_value['action'] = $this->class_settings["success_callback_action"];
			}
			
			return $returned_value;
		}

		public function _trigger_notification( $opt ){
			$c = new cCustomer_call_log;
			$c->class_settings = $this->class_settings;
			return $c->_trigger_notification( $opt );
		}

		public function _check_bg_process(){

			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = $ccx;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$return = array();
			set_time_limit(0);
			
			$id = '';
			if( ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$id = $_POST["id"];
			}else{
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No Reference</h4><p>Process has ended</p>';
				return $err->error();
			}
			
			$cache_key = $this->table_name;
			$run_in_bg = 0;
			$run_in_bg_log = '';
			
			switch( $action_to_perform ){
			case 'check_bg_operation':
			default:
				sleep( 10 );
				
				$settings = array(
					'cache_key' => $cache_key . "-bg-process-" . $id,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cid = get_cache_for_special_values( $settings );
				// print_r( $settings[ 'cache_key' ] );exit;
				
				$return['status'] = 'new-status';
				
						// print_r( $cid );
				if( ( isset( $cid["clear"] ) && $cid["clear"] ) ){
				// if( ( isset( $cid["clear"] ) && $cid["clear"] ) || ! $cid ){
					if( ( isset( $cid["clear"] ) && $cid["clear"] ) ){
						$settings = array(
							'cache_key' => $cache_key . "-bg-process-" . $id,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						clear_cache_for_special_values( $settings );
					}
					
					if( ( isset( $cid["return"] ) && $cid["return"] ) ){
						return $cid;
					}
					
					$ecode = '010014';
					$msg = '<p>Failed load operation</p>';
					if( ( isset( $cid["success"] ) && $cid["success"] ) ){
						$ecode = '010011';
						$msg = '<p>Successful load operation</p>';
					}

					if( ( isset( $cid["log"] ) && $cid["log"] ) ){
						$msg .= $cid["log"];
					}
					
					$err = new cError( $ecode );
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Data Loading Complete</h4>' . $msg;
					$return = $err->error();
					
					$return['status'] = 'new-status';
					
					$return['html_replacement_selector'] = '#'.$handle;
					$return["html_replacement"] = $return["html"];
					unset( $return["html"] );
					
				}else{
					// $return['html_prepend_selector'] = '#'.$handle;
					// $return['html_prepend'] = '<li>Loading in progress. Last check at ' . date("d-M-Y H:i") . '</li>';
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 1;
					$return['id'] = $id;
					$return['post_id'] = $id;
					$return['action'] = '?action='.$this->table_name.'&todo=check_bg_process';
					$return['nwp_silent'] = 1;
				}
				
			break;
			}
			
			return $return;
		}

		protected function _merge_tables( $opt = array() ){

			$join = isset( $opt[ 'join' ] ) && is_array( $opt[ 'join' ] ) ? $opt[ 'join' ] : array(); 
			// Place the table with the most number of children as the first key of the array e.g. Individuals may have more children than Household Assets, so individuals should come first in the array

			$pl = isset( $opt[ 'plugin' ] ) ? $opt[ 'plugin' ] : '';
			$this_table = isset( $opt[ 'table' ] ) ? $opt[ 'table' ] : $this->table_name;
			$parent_tb = isset( $opt[ 'parent_table' ] ) ? $opt[ 'parent_table' ] : '';
			$tb_name = isset( $opt[ 'tb_name' ] ) ? $opt[ 'tb_name' ] : '';
			$action = isset( $opt[ 'action' ] ) ? $opt[ 'action' ] : '';
			$recreate = isset( $opt[ 'recreate' ] ) ? $opt[ 'recreate' ] : 0;
			$parent_join_field = isset( $opt[ 'parent_join_field' ] ) ? $opt[ 'parent_join_field' ] : array();
			$record = isset( $opt[ 'record' ] ) ? $opt[ 'record' ] : array();
			$pj_fields = isset( $opt[ 'parent_join_fields' ] ) ? $opt[ 'parent_join_fields' ] : array();
			$unique_field = 'unique';
			
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$post = $_POST;
			$get = $_GET;

			$error = '';

			if( ! $parent_tb ){
				$error = '<h4><strong>Undefined Table Name</strong></h4>Please contact your support team';
			}

			if( ! $error && ! $action ){
				$error = '<h4><strong>Undefined Merge Action</strong></h4>Please contact your support team';
			}

			if( ! $error && ! $tb_name ){
				$error = '<h4><strong>Undefined Merge Table Name</strong></h4>Please contact your support team';
			}

			if( ! $error && ! $this_table ){
				$error = '<h4><strong>Undefined This Table Name</strong></h4>Please contact your support team';
			}

			if( ! $error && $action == 'insert' && empty( $record ) ){
				$error = '<h4><strong>Unable to Update Merge Table</strong></h4>Missing Saved Record Details';
			}

			if( $pl ){
				$pc = "c".ucwords( $pl );
				
				if( class_exists( $pc ) ){
					$pc = new $pc();
					$pcx = $p_cls->load_class( array( "class" => array( $parent_tb ) ) );
				}else{
					if( ! $error )$error = '<h4><strong>Unregistered Plugin</strong></h4>Please contact your support team';
				}
			}

			$ctb = 'c'.$parent_tb;
			if( ! class_exists( $ctb ) && ! $error ){
				$error = '<h4><strong>'. $ctb .' does not exist</strong></h4>';
			}

			if( ! $error ){
				$all_class = array();
				$all_class[ $parent_tb ] = new $ctb();
				$all_class[ $parent_tb ]->class_settings = $this->class_settings;

				$db = new cDatabase_table();
				$this->class_settings[ 'exclude_plugins' ] = 1;
				$this->class_settings[ 'action_to_perform' ] = 'generate_class';
				$this->class_settings[ 'return_field_properties' ] = $all_class[ $parent_tb ]->table_name;

				$db->class_settings = $this->class_settings;
				$r = $db->database_table();
						// print_r( $r );exit();

				$all_fields = array();
				$group = '';

				if( isset( $r[ 'fields' ] ) && is_array( $r[ 'fields' ] ) && ! empty( $r[ 'fields' ] ) ){

					$join_condition = array();
					$extra_fields = array();

					$extra_fields[ $parent_tb ][ 'id' ] = array(
						'merge_field' => 'id',
						'field' => 'id',
					);
					
					$fields = array_merge( array( 'id' => '`id` varchar(33) NOT NULL' ), $r[ 'fields' ] );
					$all_fields[ $all_class[ $parent_tb ]->table_name ] = $fields;

					if( ! empty( $join ) ){
						foreach( $join as $jk => $jv ){
							if( isset( $jv[ 'plugin' ] ) && $jv[ 'plugin' ] ){

								if( isset( $pc->table_name ) && $jv[ 'plugin' ] == $pc->table_name ){
									$pc->load_class( array( "class" => array( $jk ) ) );
								}else{
									$pc2 = "c".ucwords( $jv[ 'plugin' ] );
									
									if( class_exists( $pc2 ) ){
										$p_cls = new $pc2();
										$pc = $p_cls->load_class( array( "class" => array( $jk ) ) );
									}
								}
							}

							$ctb2 = 'c'.$jk;
							if( isset( $jv[ 'link_field' ] ) && $jv[ 'link_field' ] && class_exists( $ctb2 ) ){
								$all_class[ $jk ] = new $ctb2();
								$all_class[ $jk ]->class_settings = $this->class_settings;

								$jfield = isset( $all_class[ $jk ]->table_fields[ $jv[ 'link_field' ] ] ) ? $all_class[ $jk ]->table_fields[ $jv[ 'link_field' ] ] : $jv[ 'link_field' ];
								$pj_field = isset( $pj_fields[ $jk ] ) ? $pj_fields[ $jk ] : 'id';

								$join_condition[ $jk ] = " LEFT JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $all_class[ $jk ]->table_name ."` ON `". $all_class[ $jk ]->table_name ."`.`". $jfield ."` = `". $all_class[ $parent_tb ]->table_name ."`.`". $pj_field ."` AND `". $all_class[ $jk ]->table_name ."`.`record_status` = '1' ";

								$this->class_settings[ 'return_field_properties' ] = $all_class[ $jk ]->table_name;

								$db->class_settings = $this->class_settings;
								$r = $db->database_table();

								if( isset( $r[ 'fields' ] ) && is_array( $r[ 'fields' ] ) && ! empty( $r[ 'fields' ] ) ){

									$fields = array_merge( array(  $all_class[ $jk ]->table_name .'_id' => '`'. $all_class[ $jk ]->table_name .'_id` varchar(33) NOT NULL' ), $r[ 'fields' ] );
									$extra_fields[ $jk ][ 'id' ] = array(
										'merge_field'  => $all_class[ $jk ]->table_name .'_id',
										'field'  => 'id',
									);
									$all_fields[ $all_class[ $jk ]->table_name ] = $fields;

									if( ! $group )$group = ' GROUP BY `'. $jk .'`.`id` ';
								}
							}
						}

						$arr = array_keys( $join );
						$arr[] = $parent_tb;
						// unset( $arr[0] );;
						$reverse_tables = $arr;
							// print_r( $join );exit();

						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query_type' => 'EXECUTE',
							'set_memcache' => 1,
							'tables' => array( $tb_name ),
						);
						// execute query here
						$create_fl = array();
							
						foreach( $arr as $ak ){
							$record_stat = $ak.'_record_status';
							$extra_fields[ $ak ][] = array(
								'field' => "record_status",
								'merge_field' => $record_stat,
							);
							$create_fl[] = " `". $record_stat ."` varchar(11) NOT NULL ";
						}

						$drop = 0;
						$create = 0;
						$insert = 0;
						$delete = 0;
						$update = 0;

						switch( $action ){
						case 'create':
							$drop = 1;
							$create = 1;
						break;
						case 'create_insert':
							$drop = 1;
							$create = 1;
							$insert = 1;
						break;
						// 	$delete = 1;
						// break;
						case 'delete':
						case 'update':
						case 'insert':
							$update = 1;
						break;
						}

						if( $drop ){
							$query_settings[ 'query' ] = " DROP TABLE IF EXISTS `". $this->class_settings['database_name'] ."`.`". $tb_name ."` ";
							execute_sql_query( $query_settings );
						}

						if( $create ){

							$create_fl[] = " `". $unique_field ."` varchar(33) NOT NULL ";

							$extra_fields[ $unique_field ] = $create_fl;
							
							foreach( $all_fields as $ak => $av ){
								$create_fl = array_merge( $create_fl, $av );
							}

							$query_settings[ 'query' ] = "CREATE TABLE `". $this->class_settings['database_name'] ."`.`". $tb_name ."` ( ". implode( ',', $create_fl ) ." ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ";
							if( $tb_name == 'a3_sr' ){
							}
							// print_r( $query_settings[ 'query' ] );exit();
							execute_sql_query( $query_settings );

							$query_settings[ 'query' ] = " 
								ALTER TABLE `". $this->class_settings['database_name'] ."`.`". $tb_name ."`
								  ADD UNIQUE KEY `". $unique_field ."` (`". $unique_field ."`) ";
							execute_sql_query( $query_settings );
						}
							// print_r( $reverse_tables );

						if( $insert ){
							$fls = array();
							$fls2 = array();

							foreach( $extra_fields as $efk => $efv ){

								switch( $efk ){
								case $unique_field:
									if( ! empty( $reverse_tables ) ){
										$fls[] = " `". $tb_name ."`.`". $unique_field ."` ";
										$ufl = " ( CASE";
										foreach( $reverse_tables as $rt ){
											$ufl .= " WHEN `". $rt ."`.`serial_num` IS NOT NULL THEN CONCAT( '". $rt ."', '_', `". $rt ."`.`serial_num` ) ";
										}
										$ufl .= " END )";
										$fls2[] = $ufl;
									}
								break;
								default:
									foreach( $efv as $efvv ){
										$fls[] = " `". $tb_name ."`.`". $efvv[ 'merge_field' ] ."` ";
										$fls2[] = " `". $efk ."`.`". $efvv[ 'field' ] ."` ";	
									}
								break;
								}
							}

							foreach( $all_fields as $ak => $av ){
								foreach( $av as $avk => $avv ){
									switch( $avk ){
									case 'id':
									case $ak.'_id':
										continue 2;
									break;
									}

									$fls[] = " `". $tb_name ."`.`". $avk ."` ";
									$fls2[] = " `". $ak ."`.`". $avk ."` ";

								}
							}
								// print_r( $fls );
								// print_r( $fls2 );exit();

							$query_settings[ 'query' ] = "INSERT INTO `". $this->class_settings['database_name'] ."`.`". $tb_name ."` ( ". implode( ',', $fls ) ." ) SELECT ". implode( ',', $fls2 ) ." FROM `". $this->class_settings[ 'database_name' ] ."`.`". $all_class[ $parent_tb ]->table_name ."` ". implode( ' ', array_values( $join_condition ) ) ." WHERE `". $all_class[ $parent_tb ]->table_name ."`.`record_status` = '1' ";
							execute_sql_query( $query_settings );
						}

						if( $update ){

							$new_id = '';
							
							if( ! isset( $record[ 'record_status' ] ) )$record[ 'record_status' ] = '1';
							$val[ $this_table ] = $record;

							switch( $action ){
							case 'delete':
								if( isset( $record[ 'delete_id' ] ) && $record[ 'delete_id' ] ){
									$query_settings[ 'query_type' ] = "SELECT";
									$select = "";
									foreach( $all_class[ $this_table ]->table_fields as $ark => $arv ){
										if( $select )$select .= ", `".$this_table."`.`".$arv."` as '".$ark."'";
										else $select = " `".$this_table."`.`id`, `".$this_table."`.`record_status`, `".$this_table."`.`serial_num`, `".$this_table."`.`created_by`, `".$this_table."`.`modification_date`, `".$this_table."`.`creation_date`, `".$this_table."`.`modified_by`, `".$this_table."`.`".$arv."` as '".$ark."'";
									}
									$query_settings[ 'query' ] = "SELECT ". $select ." FROM `" . $this->class_settings['database_name'] . "`.`" . $this_table . "` WHERE `". $this_table ."`.`record_status` = '0' AND `". $this_table ."`.`id` = '". $record[ 'delete_id' ] ."' ";
									$dr = execute_sql_query( $query_settings );
									$dr = isset( $dr[0][ 'serial_num' ] ) ? $dr[0] : array();
									$val[ $this_table ] = $dr;
									$record = $dr;
									$query_settings[ 'query_type' ] = "EXECUTE";
								}
							break;
							}

							switch( $this_table ){
							case $parent_tb:

								foreach( $join as $jk => $jv ){
									$jkey = isset( $all_class[ $jk ]->table_fields[ $jv[ 'link_field' ] ] ) ? $all_class[ $jk ]->table_fields[ $jv[ 'link_field' ] ] : $jv[ 'link_field' ];
									$pj_val = isset( $pj_fields[ $this_table ] ) ? $pj_fields[ $this_table ] : 'id';
									$pj_val = isset( $record[ $pj_val ] ) ? $record[ $pj_val ] : '';

									$all_class[ $jk ]->class_settings[ 'where' ] = " AND `". $jk ."`.`". $jkey ."` = '". $pj_val ."' ";
									$gr = $all_class[ $jk ]->_get_records();

									if( isset( $gr[0][ 'id' ] ) ){
										$val[ $jk ] = $gr[0];
										$val[ $jk ][ 'record_status' ] = '1';
									}else{
										foreach( $all_class[ $jk ]->table_fields as $tcx => $tcy ){
											$val[ $jk ][ $tcx ] = '';
										}
										if( isset( $extra_fields[ $jk ][0] ) ){
											foreach( $extra_fields[ $jk ] as $xft ){
												$val[ $jk ][ $xft[ 'field' ] ] = '';
											}
										}
									}
								}
							break;
							default:

									// print_r( $extra_fields );
								foreach( $all_class as $txb => $txy ){

									if( ! isset( $val[ $txb ] ) ){

										switch( $txb ){
										case $parent_tb:

											$jval = isset( $join[ $this_table ][ 'link_field' ] ) && isset( $record[ $join[ $this_table ][ 'link_field' ] ] ) ? $record[ $join[ $this_table ][ 'link_field' ] ] : $join[ $this_table ][ 'link_field' ];
											$pj_field = isset( $pj_fields[ $this_table ] ) ? $pj_fields[ $this_table ] : 'id';

											$txy->class_settings[ 'where' ] = " AND `". $txb ."`.`". $pj_field ."` = '". $jval ."' ";
											$gr = $txy->_get_records();

											if( isset( $gr[0][ 'id' ] ) ){
												$val[ $txb ] = $gr[0];
												$val[ $txb ][ 'record_status' ] = '1';
											}else{
												foreach( $txy->table_fields as $tcx => $tcy ){
													$val[ $txb ][ $tcx ] = '';
												}
												if( isset( $extra_fields[ $txb ][0] ) ){
													foreach( $extra_fields[ $txb ] as $xft ){
														$val[ $txb ][ $xft[ 'field' ] ] = '';
													}
												}
											}
										break;
										default:

											$pj_field = isset( $pj_fields[ $txb ] ) ? $pj_fields[ $txb ] : 'id';
											$jfield = isset( $all_class[ $txb ]->table_fields[ $join[ $txb ][ 'link_field' ] ] ) ? $all_class[ $txb ]->table_fields[ $join[ $txb ][ 'link_field' ] ] : $jv[ 'link_field' ];

											$jcd = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $parent_tb ."` ON `". $parent_tb ."`.`". $pj_field ."` = `". $txb ."`.`". $jfield ."` AND `". $txb ."`.`record_status` = '1' ";
											$txy->class_settings[ 'where' ] = "";
											// $txy->class_settings[ 'where' ] = " AND `". $this_table ."`.`". $extra_fields[ $this_table ][ 'id' ][ 'field' ] ."` = '". $record[ 'id' ] ."' ";

											foreach( $join as $jk => $jv ){
												switch( $jk ){
												case $txb:
												// case $this_table:
												break;
												default:

													$jfield = isset( $all_class[ $jk ]->table_fields[ $jv[ 'link_field' ] ] ) ? $all_class[ $jk ]->table_fields[ $jv[ 'link_field' ] ] : $jv[ 'link_field' ];
													$pj_field = isset( $pj_fields[ $jk ] ) ? $pj_fields[ $jk ] : 'id';

													$jcd .= " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $jk ."` ON `". $jk ."`.`". $jfield ."` = `". $parent_tb ."`.`". $pj_field ."` AND `". $jk ."`.`record_status` = '1' ";
													
													if( isset( $record[ $jv[ 'link_field' ] ] ) )$txy->class_settings[ 'where' ] .= " AND `". $jk ."`.`". $jfield ."` = '". $record[ $jv[ 'link_field' ] ] ."' ";

												break;
												}
											}
													// print_r( $txy->class_settings[ 'where' ] );
													// print_r( $record );

											$txy->class_settings[ 'join' ] = $jcd;
											$gr = $txy->_get_records();
													// print_r( $gr );exit;

											if( isset( $gr[0][ 'id' ] ) ){
												$val[ $txb ] = $gr[0];
												$val[ $txb ][ 'record_status' ] = '1';
											}else{
												foreach( $txy->table_fields as $tcx => $tcy ){
													$val[ $txb ][ $tcx ] = '';
												}
												if( isset( $extra_fields[ $txb ][0] ) ){
													foreach( $extra_fields[ $txb ] as $xft ){
														$val[ $txb ][ $xft[ 'field' ] ] = '';
													}
												}
											}

										break;
										}
									}

								}
							break;
							}

							$fields = array();
							$values = array();
							
							$val2 = array();
							foreach( $reverse_tables as $vv ){
								$val2[ $vv ] = $val[ $vv ];
							}
							$val = $val2;
							unset( $val2 );

							$unique_val = '';
							foreach( $val as $t_key => $t_val ){

								if( isset( $t_val[ 'serial_num' ] ) && $t_val[ 'serial_num' ] !== '' && ! $unique_val ){
									$unique_val = $t_key.'_'.$t_val[ 'serial_num' ];
								}
								if( ! isset( $t_val[ 'id' ] ) )$t_val[ 'id' ] = '';

								switch( $action ){
								case 'delete':
								case 'update':
									switch( $t_key ){
									case $this_table:
									break;
									default:
										continue 3;
									break;
									}
								break;
								}

								foreach( $all_class[ $t_key ]->table_fields as $atk => $atv ){
									if( isset( $t_val[ $atk ] ) && $t_val[ $atk ] !== '' ){
									// if( isset( $t_val[ $atk ] ) ){
										$fields[] = $atv;
										$values[] = $t_val[ $atk ];
									}
								}
							}

							foreach( $extra_fields as $efk => $efv ){
								switch( $action ){
								case 'delete':
								case 'update':
									switch( $efk ){
									case $this_table:
									break;
									default:
										continue 3;
									break;
									}
								break;
								}
								if( isset( $efv[0][ 'field' ] ) ){
									foreach( $efv as $efvv ){

										$fields[] = $efvv[ 'merge_field' ];
										$values[] = isset( $val[ $efk ][ $efvv[ 'field' ] ] ) ? $val[ $efk ][ $efvv[ 'field' ] ] : '';
									}
								}
							}
						
							// print_r( $record );
							// print_r( $val );
							// print_r( $fields );
							// print_r( $values );

							switch( $action ){
							case 'delete':
							case 'update':
								$set = array();
								foreach( $fields as $tfk => $tfd ){
									$set[] = " `". $tb_name ."`.`". $tfd ."` = '". $values[ $tfk ] ."' ";
								}

								$query_settings[ 'query' ] = "UPDATE `" . $this->class_settings['database_name'] . "`.`" . $tb_name . "` SET ". implode( ',', $set ) ." WHERE `". $tb_name ."`.`". $extra_fields[ $this_table ][ 'id' ][ 'merge_field' ] ."` = '". $val[ $this_table ][ 'id' ] ."' ";

								// print_r( $query_settings[ 'query' ] );exit;
								execute_sql_query( $query_settings );

							break;
							default:
								
								$fields[] = $unique_field;
								$values[] = $unique_val;

								$query_settings[ 'query' ] = "REPLACE INTO `" . $this->class_settings['database_name'] . "`.`" . $tb_name . "` ( `". implode( '`,`', $fields ) ."` ) VALUES ( '". implode( "','", $values ) ."' )";
								// print_r( $query_settings[ 'query' ] );exit;
								execute_sql_query( $query_settings );

							break;
							}
						}

						unset( $all_fields );
						unset( $all_class );
						// print_r( $all_fields );exit();
					}
				}else{
					return $r;
				}
			}

			$this->class_settings[ 'action_to_perform' ] = $action_to_perform;
			$_POST = $post;
			$_GET = $get;

			unset( $post );
			unset( $get );

			return $this->_display_notification( array( 'type' => 'error', 'message' => $error ) );
		}

		protected function _charts_dashboard( $opt = array() ){
			
			if( ! ( isset( $opt[ 'charts' ] ) && is_array( $opt[ 'charts' ] ) && ! empty( $opt[ 'charts' ] ) ) ){
				return $this->_display_notification( array( 'type' => 'error', 'message' => 'Undefined Chart Key' ) );
			}

			$data = array();

			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];

			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);

			$all = 1;
			$filter_where = '';
			$chart = isset( $this->class_settings[ 'data' ][ 'chart' ] ) && $this->class_settings[ 'data' ][ 'chart' ] ? $this->class_settings[ 'data' ][ 'chart' ] : '';
			$single = isset( $this->class_settings[ 'data' ][ 'return_complete_single' ] ) && $this->class_settings[ 'data' ][ 'return_complete_single' ] ? 0 : $chart;

			if( isset( $this->class_settings[ 'data' ][ 'where' ] ) && $this->class_settings[ 'data' ][ 'where' ] ){
				$filter_where = " AND " . $this->class_settings[ 'data' ][ 'where' ];
			}

			if( $chart )$all = 0;

			$empty_chart = array(
				'data' => array(
					'labels' => '-',
					'datasets' => array(),
				),
				'type' => 'horizontalBar',
			);

			$chart_js = new cChart_js();
			$chart_js->class_settings = $this->class_settings;

			$chart_js->class_settings[ 'query_action' ] = $this->table_name;
			$chart_js->class_settings[ 'query_todo' ] = $this->class_settings[ 'action_to_perform' ];

			$chart_type = isset( $_POST[ 'chart_type' ] ) && $_POST[ 'chart_type' ] ? $_POST[ 'chart_type' ] : '';
			
			$options[ 'chart_js' ] = 1;

			$type = isset( $opt[ 'type' ] ) ? $opt[ 'type' ] : '';

			// 	if( $charts !== 'no_of_individuals_with_valid_id_bvn' ){continue;}
							// print_r( $all.'-' );
							// print_r( $chart.'-' );
							//print_r( $opt );exit;
			foreach( $opt[ 'charts' ] as $cts ){
				$chart_key = $cts[ 'key' ];
				if( ( $chart == $chart_key ) || $all ){
					$dx = array();

					switch( $cts[ 'type' ] ){
					case 'chart_from_report':

						$options[ 'filter_where' ] = $filter_where;
						$options[ 'return_data' ] = 1;
						$options[ 'based_on' ] = isset( $_POST[ 'based_on' ] ) && $_POST[ 'based_on' ] ? $_POST[ 'based_on' ] : '';

						$options[ 'based_on' ] = isset( $cts[ 'based_on' ] ) ? $cts[ 'based_on' ] : $options[ 'based_on' ];

						$options[ 'report' ] = $chart_key;
						$options[ 'report_type' ] = substr($action_to_perform, 0, strpos($action_to_perform, '_dashboard' ));

						$dx = $this->_display_report( $options );
					break;
					default:

						if( isset( $cts[ 'data' ] ) && $cts[ 'data' ] ){
							$dx[ 'data' ] = $cts[ 'data' ];
						}else if( isset( $cts[ 'select' ] ) && $cts[ 'select' ] ){
							$query = $cts[ 'select' ] . ( isset( $cts[ 'join' ] ) ? $cts[ 'join' ] : '' ) . ' WHERE ' . ( isset( $cts[ 'where' ] ) ? $cts[ 'where' ] : '' ) . $filter_where . ( isset( $cts[ 'group' ] ) ? $cts[ 'group' ] : '' ) . $filter_where . ( isset( $cts[ 'order_by' ] ) ? $cts[ 'order_by' ] : '' );

							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => isset( $cts[ 'query_type' ] ) ? $cts[ 'query_type' ] : 'SELECT',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							// execute query here
							$dx[ 'data' ] = execute_sql_query( $query_settings );
						}
					break;
					}

					if( is_array( $dx[ 'data' ] ) && ! empty( $dx[ 'data' ] ) ){
						if( ! $chart_js->chart_timeout )$chart_js->chart_timeout = 500;
						
						$ex = array();
						$ex[ 'data' ] = array(
							'data' => $dx[ 'data' ],
							'label_key' => ( isset( $cts[ 'label_key' ] ) ? $cts[ 'label_key' ] : ( isset( $dx[ 'group' ] ) ? $dx[ 'group' ] : '' ) ),
							'value_key' => ( isset( $cts[ 'value_key' ] ) ? ( $cts[ 'value_key' ] == 'use_group' && isset( $dx[ 'group' ] ) ? $dx[ 'group' ] : $cts[ 'value_key' ] ) : 'count' ),
							'data_key' => ( isset( $cts[ 'data_key' ] ) ? ( $cts[ 'data_key' ] == 'use_group' && isset( $dx[ 'group' ] ) ? $dx[ 'group' ] : $cts[ 'data_key' ] ) : '' ),
							// 'data_key' => ( isset( $cts[ 'data_key' ] ) ? $cts[ 'data_key' ] : '' ),
							'type' => $chart_type ? $chart_type : ( isset( $cts[ 'chart_type' ] ) ? $cts[ 'chart_type' ] : 'bar' ),
							'tooltip' => ( isset( $cts[ 'tooltip' ] ) ? $cts[ 'tooltip' ] : ( isset( $dx[ 'group' ] ) ? ucwords( $dx[ 'group' ] ) : '' ) ),
							'index' => ( isset( $cts[ 'index' ] ) ? $cts[ 'index' ] : array() ),
							'label_index' => ( isset( $cts[ 'label_index' ] ) ? $cts[ 'label_index' ] : array() ),
						);

						if( ! ( isset( $cts[ 'unset_transform_keys' ] ) && $cts[ 'unset_transform_keys' ] ) && isset( $dx[ 'headers' ] ) && ! empty( $dx[ 'headers' ] ) ){
							$ex[ 'data' ][ 'transform_keys' ] = array_keys( $dx[ 'headers' ] );
							$ex[ 'data' ][ 'index' ] = $dx[ 'headers' ];
						}

						if( isset( $cts[ 'chart_height' ] ) && $cts[ 'chart_height' ] ){
							$ex[ 'chart_height' ] = $cts[ 'chart_height' ];
						}

						if( isset( $cts[ 'chart_width' ] ) && $cts[ 'chart_width' ] ){
							$ex[ 'chart_width' ] = $cts[ 'chart_width' ];
						}

							// print_r( $cts );exit;
						if( isset( $cts[ 'mike' ] ) ){
							$ex[ 'data' ][ 'mike' ] = 1;
						}

						$ex[ 'key' ] = $ex[ 'data' ][ 'label_key' ];
						$ex[ 'options' ] = $options;
						$ex[ 'single' ] = $single;
						$ex[ 'chart_key' ] = $chart_key;
						$ex[ 'table' ] = $this->table_name;
						$ex[ 'title' ] = isset( $options[ 'report' ] ) && isset( $report_opt[ $options[ 'report_type' ] ][ 'options' ][ $options[ 'report' ] ] ) ? $report_opt[ $options[ 'report_type' ] ][ 'options' ][ $options[ 'report' ] ] : ( isset( $cts[ 'title' ] ) ? $cts[ 'title' ] : '' );

						$data[ $chart_key ][ 'html' ] = $chart_js->_prepare_chart_html( $ex );
						// print_r( $data[ $chart_key ][ 'html' ] );exit;
					}else{
						return $this->_display_notification( array( 'type' => 'error', 'message' => 'Undefined Chart Data' ) );
					}
				}
			}
			
			// print_r( $data );exit;
			if( $chart && isset( $data[ $chart ] ) ){

				return $data[ $chart ];
			}

			if( isset( $opt[ 'return_data' ] ) && $opt[ 'return_data' ] ){
				return $data;
			}

			$this->class_settings[ 'data' ][ 'charts' ] = $data;
			$this->class_settings[ 'data' ][ 'col' ] = isset( $opt[ 'col' ] ) ? $opt[ 'col' ] : 12;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/display-charts.php' );
			
			$returning_html_data = $this->_get_html_view();
			
			$return = array();
			
			$return["status"] = "new-status";
			$return["html_replacement_selector"] = '#'.$handle;
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}

		protected function _open_module( $opt = array() ){

			if( ! ( isset( $opt["id"] ) && $opt["id"] ) ){
				$opt = isset( $this->class_settings[ 'open_module' ] ) ? $this->class_settings[ 'open_module' ] : array();
			}
			
			if( ! ( isset( $opt["id"] ) && $opt["id"] ) ){
				return $this->_display_notification( array( "type" => 'error', "message" => 'Invalid View Details ID') );
			}

			$exclude_get = array( 'action', 'todo', 'nwp_action', 'nwp_todo', 'html_replacement_selector', 'nwp_target' );
			$get = $_GET;
			$get2 = '';

			foreach( $_GET as $ek => $ev ){
				if( ! in_array( $ek, $exclude_get ) ){
					$get2 .= '&'.$ek.'='.$ev;
				}
			}
			//print_r( $get );exit;

			$error = '';
			$data = $opt;
			$data[ 'refresh_params' ] = $get2;
			if(  isset( $opt[ 'refresh_params' ] ) && $opt[ 'refresh_params' ] ){
				$data[ 'refresh_params' ] .= $opt[ 'refresh_params' ];
			}

			$data[ 'action_to_perform' ] = $this->class_settings[ 'action_to_perform' ];
			$data[ 'table' ] = $this->table_name;
			
			$handle2 = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$data[ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}

			$details_tb = isset( $opt[ 'details_action' ] ) ? $opt[ 'details_action' ] : $this->table_name;
			$details_todo = isset( $opt[ 'details_todo' ] ) ? $opt[ 'details_todo' ] : 'view_details2';

			if( isset( $this->plugin ) && $this->plugin ){
				$details_tb = $this->plugin;
			}

			if( class_exists( "cLocked_files" ) ){
				$lock_params = array();
				$lock_params["reference"] = $opt[ 'id' ];
				$lock_params["reference_table"] = $details_tb;
				
				$cx = new cLocked_files();
				$cx->class_settings = $this->class_settings;
				$lr = $cx->_check_for_full_access( $lock_params );
				
				if( isset( $lr["locked"] ) ){
					$dd["locked"] = $lr;
				}
			}
			
			$_POST[ 'id' ] = $opt["id"];
			$cl = 'c'.ucwords( $details_tb );

			if( class_exists( $cl ) ){
				$cl = new $cl();
				$cl->class_settings = $this->class_settings;

				if( isset( $this->plugin ) && $this->plugin ){
					$cl->class_settings[ 'action_to_perform' ] = "execute";
					$cl->class_settings["nwp_action"] = $this->table_name;
					$cl->class_settings["nwp_todo"] = $details_todo;

					// print_r( $_POST[ 'id' ] );exit;
					$dx = $cl->$details_tb();

					if( isset( $dx[ 'data' ] ) && ! empty( $dx[ 'data' ] ) ){
						$data[ 'item' ] = $dx[ 'data' ];
					}else{
						$cl->class_settings[ 'current_record_id' ] = $opt[ 'id' ];

						$cl->class_settings[ 'action_to_perform' ] = "execute";
						$cl->class_settings["nwp_action"] = $this->table_name;
						$cl->class_settings["nwp_todo"] = 'get_record';

						$data[ 'item' ] = $cl->$details_tb();
					}
				
					$data[ 'plugin' ] = $this->plugin;
					$data[ 'action_to_perform' ] = "execute&nwp_action=". $this->table_name ."&nwp_todo=". $data[ 'action_to_perform' ];
				}else{
					$cl->class_settings[ 'action_to_perform' ] = $details_todo;

					$dx = $cl->$details_tb();

					if( isset( $dx[ 'data' ] ) && ! empty( $dx[ 'data' ] ) ){
						$data[ 'item' ] = $dx[ 'data' ];
					}else{
						$cl->class_settings[ 'current_record_id' ] = $opt[ 'id' ];
						$data[ 'item' ] = $cl->_get_record();
					}
				}

				if( isset( $dx[ 'html_replacement' ] ) && $dx[ 'html_replacement' ] ){
					$data[ 'details' ] = $dx[ 'html_replacement' ];

					// $data[ 'table' ] = $cl->table_name;
					$data[ 'table_label' ] = $cl->label;
				}else{
					$data[ 'details' ] = "Undefined Details View";
				}
				
				if( isset( $opt[ 'details_content' ] ) ){
					if( $opt[ 'details_content' ] == 'single_view' ){
						$data[ 'single_view' ] = isset( $data[ 'details' ] )?$data[ 'details' ]:'';
						$opt[ 'details_content' ] = '&nbsp;';
					}
					$data[ 'details' ] = $opt[ 'details_content' ];
				}

			}else{
				$error = "Invalid Class";
			}
			// print_r( $data[ 'table' ] );exit;
			
			if( ! $error ){
				$js = array();
				$tb = $this->table_name;
				if( isset( $opt[ 'table_real' ] ) && $opt[ 'table_real' ] ){
					$tb = $opt[ 'table_real' ];
					$details_tb = $opt[ 'table_real' ];
				}
				$data[ 'show_refresh' ] = isset( $opt[ 'show_refresh' ] ) ? $opt[ 'show_refresh' ] : 1;

				$data[ 'details_column' ] = isset( $opt[ 'details_column' ] ) ? $opt[ 'details_column' ] : 5;
				$data[ 'action_column' ] = isset( $opt[ 'action_column' ] ) ? $opt[ 'action_column' ] : 3;
				$data[ 'comments_column' ] = isset( $opt[ 'comments_column' ] ) ? $opt[ 'comments_column' ] : 3;

				if( isset( $data[ 'hide_view_comments' ] ) && $data[ 'hide_view_comments' ] ){
					$data[ 'details_column' ] = 6;
					$data[ 'action_column' ] = 6;
					$data[ 'comments_column' ] = 0;
				}

				$ga_params = '&source=' . $this->table_name;
				$plugin = isset( $this->plugin ) ? $this->plugin : '';
				if( $plugin )$ga_params .= '&plugin='.$plugin;
				
				if( isset( $opt[ 'link_params' ] ) && $opt[ 'link_params' ] ){
					$ga_params .= $opt[ 'link_params' ];
				}

				if( ! ( isset( $opt[ 'hide_files' ] ) && $opt[ 'hide_files' ] ) ){
					if( ! ( isset( $opt[ 'hide_files2' ] ) && $opt[ 'hide_files2' ] ) ){
						$data[ 'general_actions' ][] = array(
							'custom_id' => isset( $opt[ 'files_id' ] )?$opt[ 'files_id' ]:'',
							'action' => 'files',
							'todo' => 'attach_file_to_record'.$ga_params,
							'title' => 'Attach File',
						);
					}
					
					$cflg = '';
					if( isset( $opt["flags"]["files"] ) && $opt["flags"]["files"] ){
						$cflg = $opt["flags"]["files"];
					}
					
					$data[ 'tabs' ][] = array(
						'custom_id' => isset( $opt[ 'files_id' ] )?$opt[ 'files_id' ]:'',
						'action' => 'files',
						'todo' => 'files_search_list2&table='. ( $plugin ? $tb : $details_tb ).$ga_params,
						'title' => 'Files'.$cflg,
					);
				}

				if( ! ( isset( $opt[ 'hide_comments' ] ) && $opt[ 'hide_comments' ] ) ){
					if( ! ( isset( $opt[ 'hide_comments1' ] ) && $opt[ 'hide_comments1' ] ) ){
						$data[ 'show_comments' ] = 1;
					}
					if( ! ( isset( $opt[ 'hide_comments2' ] ) && $opt[ 'hide_comments2' ] ) ){
						$data[ 'general_actions' ][] = array(
							'custom_id' => isset( $opt[ 'comments_id' ] )?$opt[ 'comments_id' ]:'',
							'action' => 'comments',
							'todo' => 'add_comment'.$ga_params,
							'title' => 'Comment',
						);
					}
					
					$cflg = '';
					if( isset( $opt["flags"]["comments"] ) && $opt["flags"]["comments"] ){
						$cflg = $opt["flags"]["comments"];
					}
					$data[ 'tabs' ][] = array(
						'custom_id' => isset( $opt[ 'comments_id' ] )?$opt[ 'comments_id' ]:'',
						'action' => 'comments',
						'todo' => 'comments_search_list2&table='. ( $plugin ? $tb : $details_tb ).$ga_params,
						'title' => 'Comments' . $cflg,
					);
				}

				//$data[ 'general_tasks' ] = isset( $opt[ 'general_tasks' ] ) ? $opt[ 'general_tasks' ] : array();

				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/nw-open-module' );
				$html = $this->_get_html_view();

				return array(
					'html_replacement' => $html,
					'html_replacement_selector' => '#'.$handle2,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js,
				);
			}

			$error = $error ? $error : 'Undefined Error';

			return $this->_display_notification( array( "type" => 'error', "message" => $error ) );
		}

		protected function _transform_data_for_table_report( $opt = array() ){
			$return = array();
			$d = array();

			if( isset( $opt[ 'data' ] ) && ! empty( $opt[ 'data' ] ) ){
				$d = $opt[ 'data' ];

				if( isset( $opt[ 'key' ] ) && isset( $opt[ 'key2' ] ) ){
					$switch = isset( $opt[ 'switch' ] ) ? $opt[ 'switch' ] : 1;
					$key = $opt[ 'key' ];
					$key2 = $opt[ 'key2' ];

					$headers = isset( $opt[ 'headers' ] ) && is_array( $opt[ 'headers' ] ) ? $opt[ 'headers' ] : array();
					$headers2 = isset( $opt[ 'headers2' ] ) && is_array( $opt[ 'headers2' ] ) ? $opt[ 'headers2' ] : array();
					$no_summation = isset( $opt[ 'no_summation' ] ) ? $opt[ 'no_summation' ] : 0;
					//print_r( $d );exit;

					$key_header = isset( $opt[ 'key_header' ] ) && is_array( $opt[ 'key_header' ] ) ? $opt[ 'key_header' ] : array();
					$key2_header = isset( $opt[ 'key2_header' ] ) && is_array( $opt[ 'key2_header' ] ) ? $opt[ 'key2_header' ] : $headers;

					switch( $switch ){
					case 1:	//spready
						foreach( $d as &$d2 ){
							if( isset( $d2[ $key ] ) && isset( $d2[ $key2 ] ) ){
								$ktext = isset( $key_header[ $d2[ $key ] ] ) ? $key_header[ $d2[ $key ] ] : $d2[ $key ];
								if( ! isset( $return[ $d2[ $key2 ] ][ $d2[ $key ] ][ 'count' ] ) ){
									$text = isset( $headers[ $d2[ $key2 ] ] ) ? $headers[ $d2[ $key2 ] ] : $d2[ $key2 ];
									$text = isset( $key2_header[ $d2[ $key2 ] ] ) ? $key2_header[ $d2[ $key2 ] ] : $text;

									$return[ $text ][ $d2[ $key ] ][ 'count' ] = 0;
									$return[ $text ][ $d2[ $key ] ][ $key ] = $ktext;
								}
								if( ! $no_summation )$return[ $text ][ $d2[ $key ] ][ 'count' ] += $d2[ 'count' ];
								else $return[ $text ][ $d2[ $key ] ][ 'count' ] = $d2[ 'count' ];
							}
						}
					break;
					case 2:
						if( ! empty( $key2_header ) ){
							foreach( $d as & $d2 ){
								foreach( $key2_header as $x => $key2 ){
									if( isset( $d2[ $key ] ) && isset( $d2[ $x ] ) ){
										if( ! isset( $return[ $key2 ][ $d2[ $key ] ][ 'count' ] ) ){
											$return[ $key2 ][ $d2[ $key ] ] = $d2;
											$return[ $key2 ][ $d2[ $key ] ][ 'count' ] = 0;
										}
										$text2 = isset( $headers2[ $d2[ $key ] ] ) ? $headers2[ $d2[ $key ] ] : $d2[ $key ];
										$text2 = isset( $key_header[ $d2[ $key ] ] ) ? $key_header[ $d2[ $key ] ] : $text2;

										if( ! $no_summation )$return[ $key2 ][ $d2[ $key ] ][ 'count' ] += $d2[ $x ];
										else $return[ $key2 ][ $d2[ $key ] ][ 'count' ] = $d2[ $x ];
										$return[ $key2 ][ $d2[ $key ] ][ $key ] = $text2;
										$d2[ $key2 ] = $d2[ $x ];
									}
								}
							}
						}
					break;
					}
					//print_r( $return );exit;

				}else{
					$return = $opt[ 'data' ];
				}
			}
			
			return array( 'return' => $d, 'transformed' => $return );
		}

		protected function _transform_imported_data(){
			$params = isset( $this->class_settings[ 'params' ] )?$this->class_settings[ 'params' ]:array();
			$error_msg = '';
			
			if( isset( $params[ 'f_key' ] ) && $params[ 'f_key' ] && isset( $params[ 'f_id' ] ) && $params[ 'f_id' ] ){
				if( isset( $this->basic_data['transform_imported_data']['primary_field'] ) && $this->basic_data['transform_imported_data']['primary_field'] ){
					
					$bd = $this->basic_data['transform_imported_data'];
					if( ! isset( $this->table_fields[ $bd["primary_field"] ] ) ){
						$error_msg = 'Invalid PRIMARY_FIELD:'.$bd["primary_field"].'  in ' . $this->table_name;
					}
					
					if( ! $error_msg ){
					
						// $query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."`, `" . $this->class_settings['database_name'] . "`.`".$params[ 'table' ]."` SET `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = LOWER( `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` ) ";

						// log_in_console( $query, 'tx1' );
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							// 'query' => $query,
							'query_type' => 'EXECUTE_SYS',
							'set_memcache' => 0,
							'tables' => array( $this->table_name ),
						);
						// execute_sql_query($query_settings);

						$query = "ALTER TABLE `". $this->class_settings[ 'database_name' ] ."`.`".$params[ 'table' ]."` ADD INDEX( `".$params[ 'f_id' ]."` ) ";
						$query_settings[ 'query' ] = $query;
						execute_sql_query($query_settings);

						$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."`, `" . $this->class_settings['database_name'] . "`.`".$params[ 'table' ]."` SET `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = `".$this->table_name."`.`id` WHERE `".$this->table_name."`.`record_status` = '1' AND `".$params[ 'table' ]."`.`record_status` = '1' AND `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = `".$this->table_name."`.`".$this->table_fields[ $bd["primary_field"] ]."` ";
						
						$query_settings["query"] = $query;
						execute_sql_query($query_settings);
						
						$insert_cols = array();
						$insert_cols[] = 'id';
						$insert_cols[] = $this->table_fields[ $bd["primary_field"] ];
						$insert_cols[] = 'serial_num';
						$insert_cols[] = 'creator_role';
						$insert_cols[] = 'created_by';
						$insert_cols[] = 'creation_date';
						$insert_cols[] = 'modified_by';
						$insert_cols[] = 'modification_date';
						$insert_cols[] = 'record_status';
						
						$rand = get_new_id();
						$sel_cols = array();
						$sel_cols[] = "MD5( CONCAT( '". $rand ."', `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` ) )";
						$sel_cols[] = "`".$params[ 'table' ]."`.`".$params[ 'f_id' ]."`";
						$sel_cols[] = "'0'";
						$sel_cols[] = "`".$this->table_name."`.`id`";
						$sel_cols[] = "`".$params[ 'table' ]."`.`created_by`";
						$sel_cols[] = $params[ 'date' ];
						$sel_cols[] = "`".$params[ 'table' ]."`.`modified_by`";
						$sel_cols[] = $params[ 'date' ];
						$sel_cols[] = "'1'";
						
						if( isset( $bd["other_fields"] ) && is_array( $bd["other_fields"] ) && ! empty( $bd["other_fields"] ) ){
							foreach( $bd["other_fields"] as $bdv ){
								if( isset( $this->table_fields[ $bdv ] ) && isset( $params['table_fields'][ $bdv ] ) ){
									$insert_cols[] = $this->table_fields[ $bdv ];
									$sel_cols[] = "`".$params['table'] ."`.`".$params['table_fields'][ $bdv ]."`";
								}
							}
						}
						
						$query = "INSERT INTO `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ( ".implode( ", ", $insert_cols )." ) ( SELECT ".implode( ", ", $sel_cols )." FROM `" . $this->class_settings['database_name'] . "`.`".$params[ 'table' ]."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ON `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = `".$this->table_name."`.`id` WHERE `".$this->table_name."`.`id` IS NULL AND `".$params[ 'table' ]."`.`record_status` = '1' GROUP BY `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` ) ";
						
						$query_settings["query"] = $query;
						execute_sql_query($query_settings);
						
						// log_in_console( $query, 'tx2' );

						$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."`, `" . $this->class_settings['database_name'] . "`.`".$params[ 'table' ]."` SET `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = `".$this->table_name."`.`id` WHERE `".$this->table_name."`.`record_status` = '1' AND `".$params[ 'table' ]."`.`record_status` = '1' AND `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = `".$this->table_name."`.`".$this->table_fields[ $bd["primary_field"] ]."` ";
						
						$query_settings["query"] = $query;
						execute_sql_query($query_settings);

						$query = "ALTER TABLE `". $this->class_settings[ 'database_name' ] ."`.`".$params[ 'table' ]."` DROP INDEX `".$params[ 'f_id' ]."` ";
						$query_settings[ 'query_type' ] = "EXECUTE";
						$query_settings[ 'query' ] = $query;
						execute_sql_query($query_settings);
						

						// $query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."`, `" . $this->class_settings['database_name'] . "`.`".$params[ 'table' ]."` SET `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` = LOWER( `".$params[ 'table' ]."`.`".$params[ 'f_id' ]."` ) ";

						// $query_settings["query"] = $query;
						// execute_sql_query($query_settings);
						
						// log_in_console( $query, 'tx3' );

						$this->class_settings[ 'current_record_id' ] = 'pass_condition';
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						
						$this->class_settings[ 'cache_where' ] = " AND `".$this->table_name."`.`creation_date` = " . $params[ 'date' ];
						$this->class_settings[ 'cache_where_result' ] = 1;
						$this->_get_record();
					}
				}else{
					$error_msg = 'Undefined Parameter PRIMARY_FIELD in ' . $this->table_name;
				}
			}
			
			if( $error_msg ){
				$par = array();
				$par["elevel"] = 'warning';
				$par["status"] = "import_transformation";
				$par["comment"] = $error_msg;
				auditor("", "console", $this->table_name, $par );
			}
		}
		
		public function _display_handsontable( $opt = array() ){

			$data = array();
			
			$error = '';
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
				$data["html_replacement_selector"] = $handle;
			}
			
			$title = 'New Pay Roll: Spreadsheet';

			$this->class_settings[ 'where' ] = "";
			$saved_data = $this->_get_records();

			$data["table"] = $this->table_name;
			
			$hide_col_width = 1;

			$hidden_fields = array(
				'id' => 'id',
				'created_role' => 'created_role',
				'created_by' => 'created_by',
				'creation_date' => 'creation_date',
				'modified_by' => 'modified_by',
				'modification_date' => 'modification_date',
				'ip_address' => 'ip_address',
				'record_status' => 'record_status',
				'serial_num' => 'serial_num',
			);
			
			if( ! empty( $x ) ){
				$x = array_merge( $hidden_fields, $this->table_fields );
				// print_r( $x );exit;
				$dedc = 0;
				$tb = $this->table_name;
				$labels = $tb();

				foreach( $x as $vx1 => $vx2 ){
					$width = strlen( $vx2 ) * 10;
					$columns = array( 'data' => $vx1, 'type' => 'text' );

					$ftype = $labels[ $vx2 ][ 'form_field' ];

					if( isset( $hidden_fields[ $vx1 ] ) ){
						$width = 1;
					}

					switch( $ftype ){
					case 'hidden_fields':
						$columns[ 'type' ] = 'numeric';
					break;
					}

					switch( $vx1 ){
					case 'employee':
						$columns[ 'readOnly' ] = true;
						$width = 250;
					break;
					}

					$colHeaders[ $vx1 ][ 'label' ] = $vx2;
					$colHeaders[ $vx1 ][ 'width' ] = $width;
					$colHeaders[ $vx1 ][ 'columns' ] = $columns;
				}
			}

			$e = array();
			$sn = 0;
			foreach( $colHeaders as $key => $value ){
				//hidden columns
				if( $hide_col_width == $value[ 'width' ] ){
					$e[ 'hidden_columns' ][] = $sn;
				}
				
				$e[ 'data_column_width' ][] = $value[ 'width' ];
				$e[ 'data_column_header' ][] = $value[ 'label' ];
				$e[ 'data_columns' ][] = $value[ 'columns' ];
				$e[ 'keys' ][ $key ] = $sn++;
				
			}

			$data_object = array();
			$params = array();
			$prev = 0;
			
			if( isset( $d ) && ! empty( $d ) ){
				//$act = get_types_of_account();
				
				//print_r( $d ); exit;
				foreach( $d as $type => $d1 ){
					if( ! empty( $saved_data ) && ! ( isset( $saved_data[ $d1[ 'id' ] ] ) && $saved_data[ $d1[ 'id' ] ] ) ){
						$params[ 'unset' ][] = count( $data_object );
					}
					
					$f = array();
					foreach( $colHeaders as $key => $value ){
						$fempty[ $key ] = '';


						switch( $key ){
						case 'blank':
						break;
						case 'staff_name':
							$f[ $key ] = $d1[ 'id' ];
						break;
						case 'staff_ref':
							$f[ $key ] = $d1[ 'ref_no' ];
						break;
						case 'rank':
							$f[ $key ] = $d1[ 'rank' ];
						break;
						case 'grade_level':
							$f[ $key ] = $d1[ 'grade_level' ];
						break;
						case 'status':
							$f[ $key ] = $d1[ 'status' ];
						break;
						case 'terminal_benefit':
							$f[ $key ] = $d1[ 'terminal_benefit' ] ? $d1[ 'terminal_benefit' ] : ( isset( $saved_data[ $d1[ 'id' ] ][ $key ] ) && $saved_data[ $d1[ 'id' ] ][ $key ] ? doubleval( $saved_data[ $d1[ 'id' ] ][ $key ] ) : 0 ) ;
						break;
						case 'employee':
							if( isset( $d1[ 'name' ] ) ){
								$f[ $key ] = $d1[ 'name' ];
							}else{
								$f[ $key ] = get_name_of_referenced_record( array( 'id' => $d1[ 'id' ], 'table' => 'users_employee_profile' ) );
							}
							
						break;
						// case 'id':
						// 	$f[ $key ] = '';
						// 	if( isset( $saved_data[ $d1[ 'id' ] ][ $key ] ) && $saved_data[ $d1[ 'id' ] ][ $key ] == $d[ 'id' ] )
						// 		$f[ $key ] = $saved_data[ $d1[ 'id' ] ][ $key ];
						// break;
						default:
							$f[ $key ] = 0;
							if( isset( $saved_data[ $d1[ 'id' ] ] ) && $saved_data[ $d1[ 'id' ] ] ){
								$f[ $key ] = doubleval( $saved_data[ $d1[ 'id' ] ][ $key ] );
							}
						break;
						}
						
					}
					$data_object[] = $f;

				}
			}

			//print_r( $params[ 'index' ] );exit;
			$e["data_object"] = $data_object;
			$e["params"] = $params;
			$e["form_key"] = $form_key;

			$data[ 'action_to_perform' ] = $action_to_perform;
			$e[ 'data' ] = $data;

			$return = array(
				'data' => $e,
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwResizeWindow.resizeWindowHeight' ),
			);
			$filename = 'new-pay-row-form';
			
			switch( $action_to_perform ){
			case 'new_pay_row_form':
			case 'edit_payrow':

				$auto_gen->class_settings[ 'action_to_perform' ] = 'auto_generate_form_salary_details';
				$auto_gen->class_settings[ 'show_custom_fields' ] = 1;

				if( $month && $year ){
					$auto_gen->class_settings[ 'form_values_important' ][ $auto_gen->table_fields[ 'month' ] ] = $month;
					$auto_gen->class_settings[ 'form_values_important' ][ $auto_gen->table_fields[ 'year' ] ] = $year;
					
					$auto_gen->class_settings[ 'form_values_important' ][ 'salary_schedule' ] = $salary_schedule;
					$auto_gen->class_settings[ 'form_values_important' ][ 'total_days' ] = date("t", $start_month );
					$auto_gen->class_settings[ 'form_values_important' ][ 'exchange_rate' ] = 1;
					
					$return[ 'javascript_functions' ][] = 'nwPayRow.submit';
				}
				$handle2 = 'spreadsheet-view';

				$auto_gen->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=start_payrow&html_replacement_selector='. $handle2;
				$auto_gen->class_settings[ 'form_submit_button' ] = "Generate Spreadsheet &rarr;";

				$h = $auto_gen->pay_roll_auto_generate();

				$data[ 'formulae' ] = $this->_get_formulae_data( array( 'type' => 'PAYROLL-FORMULAE', 'reference_table' => $this->table_name ) );

				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'data' ][ 'title' ] = $title;
				$this->class_settings[ 'data' ][ 'handle' ] = $handle2;
				$this->class_settings[ 'data' ][ 'html' ] = isset( $h[ 'html' ] ) ? $h[ 'html' ] : '';
				$filename .= '/pre-spreadsheet.php';
			break;
			case 'start_payrow':
			case 'edit_payrow':
				$return[ 'javascript_functions' ][] =  'nwPayRow.updateItem2';

			break;
			}
			
			if( isset( $opt[ 'skip_view' ] ) && $opt[ 'skip_view' ] ){
				$filename = '';
			}

			if( $filename ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->package_name.'/'.$this->table_name.'/' . $filename );
				$returning_html_data = $this->_get_html_view();

				$return[ 'html_replacement_selector' ] = "#" . $handle;
				$return[ 'html_replacement' ] = $returning_html_data;
			}

			// print_r( $return );exit;
			return $return;
		}
		
		public function _get_api_widget(){
			
			if( isset( $this->class_settings[ 'get_params' ] ) && $this->class_settings[ 'get_params' ] ){
				
				$this->class_settings[ 'data' ]["get_params"] = array();
				$this->class_settings[ 'data' ]["get_params"] = $this->class_settings[ 'get_params' ];
				
				$eparams = '';
				if( is_array( $this->class_settings[ 'get_params' ] ) ){
					foreach( $this->class_settings[ 'get_params' ] as $gk => $gv ){
						$eparams .= '&' . $gk . '=' . $gv; 
					}
				}
				$this->class_settings["api_get_params"] = $eparams;
				$this->class_settings[ 'data' ]["api_get_params"] = $eparams;
				
				//$this->class_settings[ 'action_to_perform' ] = 'display_capture_window2';
				//$r1 = $this->_new_ticket_window();
				$tb = $this->table_name;
				$r1 = $this->$tb();
				
				if( isset( $this->class_settings[ 'more_params' ][ 'return_only' ] ) && $this->class_settings[ 'more_params' ][ 'return_only' ] ){
					if( is_array( $r1 ) ){
						$r1["api_get_params"] = $eparams;
					}
					return $r1;
				}
				//$r1["javascript_functions"][] = 'App.reHandleSidebarMenu';
				
				$website = new cWebsite();
				$website->class_settings = $this->class_settings;
				$website->class_settings["do_not_show_header"] = 1;
				$website->class_settings["action_to_perform"] = 'setup_website';
				$r2 = $website->website();
				
				$this->class_settings[ 'data' ]["more_params"] = isset( $this->class_settings[ 'more_params' ] )?$this->class_settings[ 'more_params' ]:array();
				
				$this->class_settings[ 'data' ]["content"] = $r1;
				
				$this->class_settings[ 'data' ]["website_data"] = $r2;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/widget.php' );
				$html = $this->_get_html_view();
			}else{
				$html = 'Invalid API Parameters. Widget Not Initialized';
			}
			echo $html; exit;
		}
		
		public function _update_records( $s = array() ){
			
			if( isset( $s["where"] ) && $s["where"] && isset( $s["field_and_values"] ) && is_array( $s["field_and_values"] ) && ! empty( $s["field_and_values"] ) ){
				
				if( ! ( isset( $s[ 'database_table' ] ) && $s[ 'database_table' ] ) ){
					$s[ 'database_table' ] = $this->table_name;
				}
				
				$f = array(
					'modification_date' => array(
						'value' => date("U"),
					),
					'modified_by' => array(
						'value' => $this->class_settings['user_id'] ,
					),
					'ip_address' => array(
						'value' => get_ip_address(),
					),
				);
				$f = array_merge( $f, $s["field_and_values"] );
				
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $s[ 'database_table' ],
					'field_and_values' => $f,
					'where' => "WHERE " . $s["where"],
				);
				
				$return = update( $settings_array );
				
				if( isset( $this->refresh_cache_after_save_line_items ) && $this->refresh_cache_after_save_line_items ){
					$this->class_settings[ 'current_record_id' ] = 'pass_condition';
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					
					$this->class_settings[ 'cache_where' ] = " AND " . $s["where"];
					$this->class_settings[ 'cache_where_result' ] = 1;
					$this->class_settings[ 'updated_data' ] = $this->_get_record();
					
				}
				
				return $return;
			}
		}
		
		public function _search_form(){
			$where = "";
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			if( $this->class_settings["where"] )$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $this->class_settings["where"];
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( '$nwProcessor.reload_datatable' ),
			);

			$this->class_settings[ 'searching' ] = 1;
			$this->class_settings['return_form_data_only'] = 1;
			$return = $this->_save_changes();

			print_r( $return );
		}

		public function _view_details(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_details_api":
				if( isset( $this->class_settings["current_record_id"] ) && $this->class_settings["current_record_id"] ){
					$e = $this->_get_record();
					
					if( isset( $e["id"] ) && $e["id"] ){
						if( isset( $e["data"] ) && $e["data"] ){
							$e["data"] = json_decode( $e["data"], true );
						}
						
						if( isset( $this->children ) && is_array( $this->children ) && ! empty( $this->children ) ){
							foreach( $this->children as $key => $value ){
								$clx = 'c' . ucwords( $key );
								$cls = new $clx();
								$cls->class_settings = $this->class_settings;
								
								if( isset( $value[ 'api_display_fields' ] ) && is_array( $value[ 'api_display_fields' ] ) && ! empty( $value[ 'api_display_fields' ] ) ){
									$sel = array();
									foreach( $value[ 'api_display_fields' ] as $fv ){
										$sel[] = "`" . $cls->table_name . "`.`" . ( isset( $cls->table_fields[ $fv ] )?( $cls->table_fields[ $fv ] ."` as '". $fv ."'" ): ( $fv . "`" ) );
									}
									$cls->class_settings["overide_select"] = implode( ", ", $sel );
								}
								
								$cls->class_settings["where"] = " AND `". $key ."`.`". ( $value[ 'link_field' ] == 'id' ? 'id' : $cls->table_fields[ $value[ 'link_field' ] ] ) ."` = '". $e["id"] ."' ";
								
								$e[ $cls->table_name ] = $cls->_get_records();
							}
						}
						
						if( isset( $this->basic_data["api_get_reference"] ) && $this->basic_data["api_get_reference"] ){
							if( isset( $e["reference_table"] ) && isset( $e["reference"] ) && $e["reference"] && $e["reference_table"] ){
								$key = $e["reference_table"];
								$clx = 'c' . ucwords( $key );
								if( class_exists( $clx ) ){
									$cls = new $clx();
									$cls->class_settings = $this->class_settings;
									$cls->class_settings["current_record_id"] = $e["reference"];
									$cls->class_settings["action_to_perform"] = "view_details_api";
									$e[ $e["reference_table"] ] = $cls->$key();
								}
							}
						}
						
						return $e;
					}else{
						return array( 'error' => 'API: Unable to Retrieve Record' );
					}
				}else{
					return array( 'error' => 'API: Invalid Reference' );
				}
			break;
			default:
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					return $this->_display_notification( array( "type" => 'error', "message" => 'Invalid Item ID') );
				}
				
				$js = array();
				$this->class_settings["current_record_id"] = $_POST["id"];
			break;
			}
			
			
			$skip_data = 0;
			if( isset( $this->class_settings["data_items"] ) && $this->class_settings["data_items"] ){
				$skip_data = 1;
			}
			
			$html_content = '';
			if( isset( $this->class_settings["html_content"] ) && $this->class_settings["html_content"] ){
				$html_content = $this->class_settings["html_content"];
			}
			
			switch( $_POST["id"] ){
			case "preview":
				$e = $_POST;
				$this->class_settings[ 'data' ][ "preview" ] = 1;
			break;
			default:
				if( ! $skip_data ){
					$e = $this->_get_record();
				}
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/view-details.php' );
			
			if( isset( $this->class_settings["html_filename"] ) && $this->class_settings["html_filename"] ){
				$this->class_settings[ 'html' ] = $this->class_settings["html_filename"];
			}
			
			$handle = "";
			
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			if( isset( $this->class_settings["other_params"] ) ){
				$this->class_settings[ 'data' ][ "other_params" ] = $this->class_settings["other_params"];
				unset( $this->class_settings["other_params"] );
			}
			
			$t = $this->table_name;
			$this->class_settings[ 'data' ][ "reference" ] = $this->default_reference;
			$this->class_settings[ 'data' ][ "view" ] = 1;
			$this->class_settings[ 'data' ][ "table" ] = $t;
			$this->class_settings[ 'data' ][ "labels" ] = $t();
			$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
			
			if( isset( $this->class_settings["data_labels"] ) && $this->class_settings["data_labels"] ){
				$this->class_settings[ 'data' ][ "labels" ] = $this->class_settings["data_labels"];
			}
			
			if( isset( $this->class_settings["data_fields"] ) && $this->class_settings["data_fields"] ){
				$this->class_settings[ 'data' ][ "fields" ] = $this->class_settings["data_fields"];
			}
			
			if( isset( $this->class_settings["data_items"] ) && $this->class_settings["data_items"] ){
				$this->class_settings[ 'data' ][ "items" ] = $this->class_settings["data_items"];
			}else{
				$this->class_settings[ 'data' ][ "items" ] = array( $this->class_settings["current_record_id"] => $e );
			}
			
			if( isset( $this->custom_fields ) ){
				$this->class_settings[ 'data' ][ "custom_fields" ] = $this->custom_fields;
				foreach( $this->custom_fields as $ck => $cv ){
					$this->class_settings[ 'data' ][ "fields" ][ $ck ] = $ck;
					$this->class_settings[ 'data' ][ "labels" ][ $ck ] = $cv;
				}
			}
			
			$modal = 0;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'delete_with_prompt':
				$this->class_settings[ 'data' ][ "delete_prompt" ] = 1;
				$js = array( 'set_function_click_event' );
			break;
			case 'view_invoice_app':
				$modal = 1;
				$this->class_settings[ 'data' ][ "print_preview" ] = 1;
			break;
			}
			
			if( $html_content ){
				$html = $html_content;
			}else{
				$html = $this->_get_html_view();
			}
			
			if( isset( $this->class_settings["before_html"] ) && $this->class_settings["before_html"] ){
				$html = $this->class_settings["before_html"] . $html;
			}
			if( isset( $this->class_settings["after_html"] ) && $this->class_settings["after_html"] ){
				$html .= $this->class_settings["after_html"];
			}
			
			if( isset( $this->class_settings["return_html"] ) && $this->class_settings["return_html"] ){
				$modal = 1;
			}
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				$modal = 1;
			}

			if( $modal ){
				$container = "#modal-replacement-handle";
				if( $handle ){
					$container = $handle;
				}
				
				return array(
					'do_not_reload_table' => 1,
					'data' => isset( $e )?$e:array(),
					'html_replacement' => $html,
					'html_replacement_selector' => $container,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js,
				);
			}
			
			if( ! $handle ){
				$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "#dash-board-main-content-area";
			}
			return $this->_launch_popup( $html, $handle, $js );
		}
		
		public function _launch_popup( $html = '', $selector = '', $js = array(), $options = array() ){

			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$selector = $selector ? $selector : '#'.$ccx;						  
			
			if( isset( $options["close"] ) && $options["close"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget-removal-script.php' );
				return $this->_get_html_view();
			}
			
			$title = ucwords( $this->table_name ) . " Details";
			if( isset( $this->label ) && $this->label ){
				$title = ucwords( $this->label ) . " Details";
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			
			if( isset( $this->class_settings["modal_dialog_style"] ) && $this->class_settings["modal_dialog_style"] ){
				$this->class_settings[ 'data' ]["modal_dialog_style"] = $this->class_settings["modal_dialog_style"];
			}
			
			if( isset( $this->class_settings["modal_dialog_class"] ) && $this->class_settings["modal_dialog_class"] ){
				$this->class_settings[ 'data' ]["modal_dialog_class"] = $this->class_settings["modal_dialog_class"];
			}
			
			if( isset( $this->class_settings["modal_dialog_static"] ) ){
				$this->class_settings[ 'data' ]["modal_dialog_static"] = $this->class_settings["modal_dialog_static"];
			}
			
			if( isset( $this->class_settings["modal_callback"] ) && $this->class_settings["modal_callback"] ){
				$this->class_settings[ 'data' ]["modal_callback"] = $this->class_settings["modal_callback"];
				unset( $this->class_settings["modal_callback"] );
			}
			
			if( isset( $this->class_settings["modal_title"] ) && $this->class_settings["modal_title"] ){
				$title = $this->class_settings["modal_title"];
			}
			
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'data' => isset( $options["data"] )?$options["data"]:array(),
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => $selector,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		protected function _search_customer_call_logOLD(){
			$where = "";
			$mongo_where = array();
			$mongo_filter = array();
			$filename = 'item-list.php';
			$title = '';
			$filter = 0;
			
			$action_to_perform = $this->class_settings[ "action_to_perform" ];
			
			switch( $action_to_perform ){
			case 'search_list2':
				$filter = 1;
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
			case 'search_list':
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
				if( isset( $this->class_settings[ "mongo_where" ] ) && $this->class_settings[ "mongo_where" ] ){
					$mongo_where = $this->class_settings[ "mongo_where" ];
				}
			break;
			default:
				if( ! ( isset( $_POST[ $this->default_reference ] ) && $_POST[ $this->default_reference ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.$this->customer_source.' first';
					return $err->error();
				}
				
				$where = " AND `".$this->table_name."`.`".$this->table_fields[ $this->default_reference ]."` = '".$_POST[ $this->default_reference ]."' ";
				$mongo_where[ '$and' ][][ $this->table_fields[ $this->default_reference ] ] = $_POST[ $this->default_reference ];
			break;
			}
			
			$allow = 0;
				
			if( $filter && isset( $_POST[ "field" ] ) && $_POST[ "field" ] ){
				
				$field = $_POST[ "field" ];
				
				switch( $field ){
				case "_most_recent":
					
					if( isset( $_POST[ "search_number" ] ) && intval( $_POST[ "search_number" ] ) ){
						$limit = intval( $_POST[ "search_number" ] );
						$allow = 1;
						
						$this->class_settings["limit"] = " LIMIT " . $limit;
						$mongo_filter[ 'limit' ] = intval( $_POST[ "search_number" ] );
						$title = "Most Recent ".$limit." Record(s)";
					}else{
						$where = '';	//prevents requesting for all records
					}
					
				break;
				default:
				
					if( isset( $this->table_fields[ $_POST[ "field" ] ] ) ){
						
						$val = $this->table_fields[ $_POST[ "field" ] ];
						$t = $this->table_name;
						$lbl = $t();
						
						if( isset( $lbl[ $val ][ "form_field" ] ) ){
							
							switch( $lbl[ $val ][ "form_field" ] ){
							case "date-5":
								
								if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
									$where .= " AND `".$val."` >= " . convert_date_to_timestamp( $_POST["start_date"], 1 );
									$mongo_where[ '$and' ][][ $val ][ '$gte' ] = convert_date_to_timestamp( $_POST["start_date"], 1 );
								}
								
								if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
									$where .= " AND `".$val."` <= " . convert_date_to_timestamp( $_POST["end_date"], 1 );
									$mongo_where[ '$and' ][][ $val ][ '$lte' ] = convert_date_to_timestamp( $_POST["end_date"], 1 );
								}
								
								if( ! $where )$allow = 1;
								if( empty( $mongo_where ) )$allow = 1;
							break;
							default:
								
								if( isset( $_POST[ "search" ] ) ){
									if( $_POST[ "search" ] ){
										$where = " AND `".$this->table_name."`.`".$val."` REGEXP '".$_POST[ "search" ]."' ";
										$mw = array();
										$mw[ $val ][ '$regex' ] = '^'.$_POST[ "search" ];
										$mw[ $val ][ '$options' ] = 'i';
										$mongo_where[ '$and' ][] = $mw;
									}else{
										
										if( isset( $_POST[ "search_select" ] ) && $_POST[ "search_select" ] ){
											$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_select" ]."' ";
											$mongo_where[ '$and' ][][ $val ] = $_POST[ "search_select" ];
										}else{
											if( isset( $_POST[ "search_number" ] ) && $_POST[ "search_number" ] ){
												$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_number" ]."' ";
												$mongo_where[ '$and' ][][ $val ] = $_POST[ "search_number" ];
											}else{
												$allow = 1;
											}
										}
										
									}
								}
							break;
							}
						}
					}
					
				}
			
				if( ! ( $where || $allow ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Search Criteria</h4>Please try again';
					return $err->error();
				}
				
				unset( $_POST["start_date"] );
				unset( $_POST["end_date"] );
			}
			
			$hidden_field = array();
			if( isset( $_POST["hidden_field"] ) && is_array( $_POST["hidden_field"] ) && ! empty( $_POST["hidden_field"] ) ){
				foreach( $this->table_fields as $k => $v ){
					if( isset( $_POST["hidden_field"][ $k ] ) ){
						$where .= " AND `".$this->table_fields[ $k ]."` = '". $_POST["hidden_field"][ $k ] ."' ";
						$mongo_where[ '$and' ][][ $this->table_fields[ $k ] ] = $_POST[ "hidden_field" ][ $k ];
					}
				}
				
				$kk = "start_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$kk = "end_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$hidden_field = $_POST["hidden_field"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"], 1 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				else $where = " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];

				$mongo_where[ '$and' ][][ $this->table_fields["date"] ][ '$gte' ] = $this->class_settings["start_date"];
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"], 2 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
					else $where = " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];

				$mongo_where[ '$and' ][][ $this->table_fields["date"] ][ '$lte' ] = $this->class_settings["end_date"];
			}

			$mongo_filter[ 'projection' ][ '_id' ] = 0;
			
			$this->class_settings["where"] = $where;
			$this->class_settings["mongo_where"] = $mongo_where;
			$this->class_settings["mongo_filter"] = $mongo_filter;
			$this->class_settings["mike"] = 1;//$mongo_filter;
			$data = $this->_get_all_customer_call_log();
			// print_r( $data );exit;
			
			if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
				return $data;
			}
			
			if( empty( $data ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No '.ucwords( $this->label ).' Record(s)</h4>';
				$return = $err->error();
				$returning_html_data = $return["html"];
			}else{
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$this->class_settings[ 'data' ][ "items" ] = $data;
				
				$tb = $this->table_name;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'table_fields' ] = $this->table_fields;
				$this->class_settings[ 'data' ][ 'table_labels' ] = $tb();
				$this->class_settings[ 'data' ][ 'action' ] = $action_to_perform;
				$this->class_settings[ 'data' ][ 'hidden_field' ] = $hidden_field;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				
				if( isset( $this->use_package ) && $this->use_package ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
				
			// print_r( $this->class_settings[ 'html' ] );exit;
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		//new by steve on 05-nov-22
		protected function _search_customer_call_log(){
			$where = "";
			$filename = 'item-list.php';
			$title = '';
			$filter = 0;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$check_for_reference = 0;
			
			if( isset( $this->options[ $action_to_perform ]["check_for_reference"] ) ){
				$check_for_reference = $this->options[ $action_to_perform ]["check_for_reference"];
			}
			
			switch( $action_to_perform ){
			case 'search_list2':
				$filter = 1;
			case 'search_list':
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
			break;
			default:
				$check_for_reference = 1;
			break;
			}
			
			if( $check_for_reference ){
				if( ! ( isset( $_POST[ $this->default_reference ] ) && $_POST[ $this->default_reference ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.$this->customer_source.' first';
					return $err->error();
				}
				
				$where = " AND `".$this->table_name."`.`".$this->table_fields[ $this->default_reference ]."` = '".$_POST[ $this->default_reference ]."' ";
			}
			
			$allow = 0;
			$get_count = 0;
				
			if( $filter && isset( $_POST[ "field" ] ) && $_POST[ "field" ] ){
				
				$field = $_POST[ "field" ];
				
				switch( $field ){
				case "_most_recent":
					
					if( isset( $_POST[ "search_number" ] ) && intval( $_POST[ "search_number" ] ) ){
						$limit = intval( $_POST[ "search_number" ] );
						$allow = 1;
						$get_count = 1;
						
						$this->class_settings["limit"] = " LIMIT " . $limit;
						
						$this->class_settings["order_by"] = " ORDER BY `".$this->table_name."`.`modification_date` DESC ";
						$title = "Most Recent ".$limit." record(s)";
					}else{
						$where = '';	//prevents requesting for all records
					}
					
				break;
				default:
				
					if( isset( $this->table_fields[ $_POST[ "field" ] ] ) ){
						
						$val = $this->table_fields[ $_POST[ "field" ] ];
						$t = $this->table_name;
						$lbl = $t();
						
						if( isset( $lbl[ $val ][ "form_field" ] ) ){
							
							switch( $lbl[ $val ][ "form_field" ] ){
							case "date-5":
								
								if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
									$where .= " AND `".$val."` >= " . convert_date_to_timestamp( $_POST["start_date"], 1 );
								}
								
								if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
									$where .= " AND `".$val."` <= " . convert_date_to_timestamp( $_POST["end_date"], 1 );
								}
								
								if( ! $where )$allow = 1;
							break;
							default:
								
								if( isset( $_POST[ "search" ] ) ){
									if( $_POST[ "search" ] ){
										$where = " AND `".$this->table_name."`.`".$val."` REGEXP '".$_POST[ "search" ]."' ";
									}else{
										
										if( isset( $_POST[ "search_select" ] ) && $_POST[ "search_select" ] ){
											$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_select" ]."' ";
										}else{
											if( isset( $_POST[ "search_number" ] ) && $_POST[ "search_number" ] ){
												$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_number" ]."' ";
											}else{
												$allow = 1;
											}
										}
										
									}
								}
							break;
							}
						}
					}
					
				}
			
				if( ! ( $where || $allow ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Search Criteria</h4>Please try again';
					return $err->error();
				}
				
				unset( $_POST["start_date"] );
				unset( $_POST["end_date"] );
			}
			
			$hidden_field = array();
			if( isset( $_POST["hidden_field"] ) && is_array( $_POST["hidden_field"] ) && ! empty( $_POST["hidden_field"] ) ){
				foreach( $this->table_fields as $k => $v ){
					if( isset( $_POST["hidden_field"][ $k ] ) ){
						$where .= " AND `".$this->table_fields[ $k ]."` = '". $_POST["hidden_field"][ $k ] ."' ";
					}
				}
				
				$kk = "start_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$kk = "end_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$hidden_field = $_POST["hidden_field"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"], 1 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				else $where = " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"], 2 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
					else $where = " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			
			$this->class_settings["where"] = $where;
			$data = $this->_get_all_customer_call_log();
			
			if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
				return $data;
			}
			
			if( empty( $data ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No '.ucwords( $this->label ).' Record(s)</h4>';
				$return = $err->error();
				$returning_html_data = $return["html"];
			}else{
				if( $get_count ){
					$this->class_settings["overide_select"] = " COUNT(*) as 'count' ";
					$this->class_settings["limit"] = "";
					$this->class_settings["order_by"] = "";
					$this->class_settings["where"] = $where;
					$ct = $this->_get_all_customer_call_log();
					if( isset( $ct[0]['count'] ) ){
						$title .= " of " . number_format( doubleval( $ct[0]['count'] ), 0 ) . " record(s)";
					}
				}
				
				if( isset( $this->plugin ) && $this->plugin ){
					$this->class_settings[ 'data' ][ "plugin" ] = $this->plugin;
				}
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$this->class_settings[ 'data' ][ "items" ] = $data;
				
				$tb = $this->table_name;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'table_fields' ] = $this->table_fields;
				$this->class_settings[ 'data' ][ 'table_labels' ] = $tb();
				$this->class_settings[ 'data' ][ 'action' ] = $action_to_perform;
				$this->class_settings[ 'data' ][ 'hidden_field' ] = $hidden_field;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				
				if( isset( $this->use_package ) && $this->use_package ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}else if( isset( $this->package_name ) && $this->package_name ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->package_name.'/'.$this->table_name.'/'.$filename );
				}
				
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		protected function _new_popup_form(){
			$err = 0;
			$c_name = "";
			
			// print_r( $this->class_settings[ 'skip_link' ] );exit;
			if( ! ( isset( $this->class_settings["skip_link"] ) && $this->class_settings["skip_link"] ) ){
				
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					$err = 1;
				}else{
					switch( $this->class_settings["action_to_perform"] ){
					case "new_popup_form":
						//$c = get_record_details( array( "id" => $_POST["id"], "table" => $this->customer_source ) );
						$c = get_customers_details( array( "id" => $_POST["id"], "table" => $this->customer_source ) );
						
						if( isset( $c[ "id" ] ) && $c[ "id" ] ){
							
							switch( $this->customer_source ){
							case "users":
								$c_name = $c[ "firstname" ] . ' ' . $c[ "lastname" ];
							break;
							default:
								$c_name = isset( $c[ "name" ] )?$c[ "name" ]:'';
							break;
							}
							
						}else{
							$err = 1;
						}
					break;
					}
				}
				
				if( $err ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.( $this->customer_source ).' first';
					return $err->error();
				}
				
				if( ! ( isset( $this->class_settings["override_defaults"] ) && $this->class_settings["override_defaults"] ) ){
					if( isset( $this->table_fields[ $this->default_reference ] ) ){
						$this->class_settings[ "form_values_important" ][ $this->table_fields[ $this->default_reference ] ] = $_POST["id"];
						$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ $this->default_reference ] ] = 1;
					}
					
					if( isset( $this->table_fields[ "date" ] ) ){
						$this->class_settings[ "form_values_important" ][ $this->table_fields[ "date" ] ] = date("U");
						$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ "date" ] ] = 1;
					}
					if( isset( $this->table_fields[ "appointment" ] ) ){
						$this->class_settings[ "hidden_records_css" ][ $this->table_fields[ "appointment" ] ] = 1;
					}
					
					if( isset( $this->class_settings[ "not_hidden_records_css" ] ) && is_array( $this->class_settings[ "not_hidden_records_css" ] ) && ! empty( $this->class_settings[ "not_hidden_records_css" ] ) ){
						foreach( $this->class_settings[ "not_hidden_records_css" ] as $key => $val ){
							unset( $this->class_settings[ "hidden_records_css" ][ $key ] );
						}
					}
					
					if( isset( $this->table_fields[ "time" ] ) ){
						$this->class_settings[ "form_values_important" ][ $this->table_fields[ "time" ] ] = date("H:i");
					}
				}
			}
			
			
			if( ! ( isset( $this->class_settings[ 'form_action' ] ) && $this->class_settings[ 'form_action' ] ) ){
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_popup';
			}
			
			if( isset( $this->basic_data["enforce_form_fields"] ) && ! empty( $this->basic_data["enforce_form_fields"] ) ){
				
				foreach( $this->basic_data["enforce_form_fields"] as $ek => $ev ){
					if( isset( $this->table_fields[ $ek ] ) && isset( $_GET[ "enforce_" . $ek ] ) ){
						if( $ev && $_GET[ "enforce_" . $ek ] != get_current_customer( $ek ) ){
							return $this->_display_notification( array( 'type' => 'error', 'message' => '<h4>Access Violation</h4>Please sign-out and sign-in and try again' ) );
						}
						
						$this->class_settings["form_values_important"][ $this->table_fields[ $ek ] ] = $_GET[ "enforce_" . $ek ];
						//$this->class_settings["hidden_records_css"][ $this->table_fields[ $ek ] ] = 1;
						$this->class_settings["disable_form_element"][ $this->table_fields[ $ek ] ] = ' readonly="readonly" ';
						
						$enforce = 1;
					}
				}
				
			}
			
			
			$container = "#modal-replacement-handle";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'form_action' ] .= '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			if( isset( $_GET[ 'empty_container' ] ) && $_GET[ 'empty_container' ] ){
				$this->class_settings[ 'form_action' ] .= '&empty_container=' . $_GET[ 'empty_container' ];
			}
			
			$this->class_settings['do_not_show_headings'] = 1;
			$d = $this->_generate_new_data_capture_form();
			// print_r( $d );exit;
			
			$html = isset( $this->class_settings[ 'use_html' ] ) ? $this->class_settings[ 'use_html' ] : ( isset( $d[ 'html' ] ) ? $d[ 'html' ] : '' );
			
			if( isset( $this->class_settings["before_html"] ) && $this->class_settings["before_html"] ){
				$html = $this->class_settings["before_html"] . $html;
			}
			
			if( isset( $this->class_settings["after_html"] ) && $this->class_settings["after_html"] ){
				$html .= $this->class_settings["after_html"];
			}
			
			if( isset( $_GET["nwp_mid"] ) && $_GET["nwp_mid"] ){
				$html = '<div class="row"><div class="col-md-3"></div><div class="col-md-6">'.$html . '</div></div>';
			}
			
			if( isset( $this->class_settings["no_popup"] ) && $this->class_settings["no_popup"] ){
				$this->class_settings["action_to_perform"] = "capture_version_2";
			}
			
			$label = isset( $this->label )?$this->label:ucwords( $this->table_name );
			
			if( isset( $this->class_settings["modal_popup_active"] ) && $this->class_settings["modal_popup_active"] ){
				$this->class_settings["action_to_perform"] = 'new_popup_in_popup';
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "new_popup_in_popup":
			case "new_popup_form_in_popup":
			case "capture_version_2":
			case 'edit_popup_form_in_popup':
			
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => $container,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
				);
			break;
			case "new_popup_form_i_menu":
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'html_replacement_one' => isset( $this->class_settings["modal_title"] )?( $this->class_settings["modal_title"] ):( $c_name.": New " . $label ),
					'html_replacement_selector_one' => "#form-title",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "$.fn.hyellaIMenu.activateForm" ),
				);
			break;
			case "edit_popup_form":
			case "new_popup_form":
				
				if( ! ( isset( $this->class_settings["no_modal_callback"] ) && $this->class_settings["no_modal_callback"] ) ){
					if( ! ( isset( $this->class_settings["modal_callback"] ) && $this->class_settings["modal_callback"] ) ){
						$this->class_settings["modal_callback"] = '$nwProcessor.reload_datatable';
					}
				}
				
			break;
			}
			// exit;
			$js = array( "prepare_new_record_form_new" , "set_function_click_event" );
			
			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? '#'.DISPLAY_MAIN_AREA_CONTAINER : $container;
			return $this->_launch_popup( $html, $ccx, $js );
			/*
			if( isset( $this->class_settings["modal_dialog_style"] ) && $this->class_settings["modal_dialog_style"] ){
				$this->class_settings[ 'data' ]["modal_dialog_style"] = $this->class_settings["modal_dialog_style"];
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = isset( $this->class_settings["modal_title"] )?( $this->class_settings["modal_title"] ):( $c_name.": New " . $label );
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => ,
			);
			*/
			
		}
		
		public function _delete_app_record(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'delete_app_manager':
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Record</h4>Please select a record by clicking on it first';
					return $err->error();
				}
				
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				
				$ids = explode( ',', $_POST["id"] );
				$_POST["ids"] = implode( ':::', $ids );
				$return = $this->_delete_records();
				if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
					unset( $return["html"] );
					$return["status"] = "new-status";
					
					foreach( $ids as $id )$return["html_removals"][] = "#".$id;
				}
				return $return;
			break;
			case 'delete_with_query':
				if( ! ( isset( $this->class_settings["where"] ) && $this->class_settings["where"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Query</h4>Please specify the query';
					return $err->error();
				}
				$where = $this->class_settings["where"];
				
				if( ! $where )return 0;
				
				$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_name."`.`record_status` = '0' WHERE ".$where;
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				return execute_sql_query($query_settings);
			break;
			}
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				$return = $this->_delete_records();
				if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
					unset( $return["html"] );
					$return["status"] = "new-status";
					
					$return["html_removal"] = "#".$return['deleted_record_id'];
					$return["javascript_functions"] = array( "nwcustomer_call_log.emptyNewItem" );
					return $return;
					
				}
			}
		}
		
		protected function _save_app_changes(){
			$return = array();
			$js = array( 'nwCustomer_call_log.init' );
			$new = 0;
			
			$error_msg = '';
			$filename = "item-list.php";
			$no_skip = 1;
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				$no_skip = 0;
				$filename = "view-details.php";
			break;
			}
			
			
			$filename_full = array();
			if( isset( $this->class_settings["html_filename_full"] ) && $this->class_settings["html_filename_full"] ){
				$filename_full = $this->class_settings["html_filename_full"];
			}
			
			$mobile_framework = '';
			if( isset( $_POST["mobile_framework"] ) && $_POST["mobile_framework"] ){
				$mobile_framework = $_POST["mobile_framework"];
			}
			
			if( isset( $_POST['id'] ) ){
				
				if( $no_skip ){
					foreach( $this->table_fields as $key => $val ){
						if( isset( $_POST[ $key ] ) ){
							$_POST[ $val ] = $_POST[ $key ];
							unset( $_POST[ $key ] );
						}
					}
					
					if( ! $_POST['id'] ){
						//new mode
						$js[] = 'nwcustomer_call_log.reClick';
						$new = 1;
					}
					
					$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
					$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
					$_POST[ "table" ] = $this->table_name;
					$_POST[ "processing" ] = md5(1);
					if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
						define('SKIP_USE_OF_FORM_TOKEN', 1);
				}
				
				$return = $this->_save_changes();
				// print_r( $return );exit;
				if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){

					if( isset( $this->merge_table ) ){

						$opt = $this->merge_table;
						$opt[ 'action' ] = ! $_POST[ 'id' ] ? 'insert' : 'update';
						$opt[ 'record' ] = $return[ 'record' ];

						$this->_merge_tables( $opt );
					}
					
					$handle = 'modal-replacement-handle';
					if( isset( $this->class_settings[ 'use_selector' ] ) && $this->class_settings[ 'use_selector' ] ){
						if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
							$handle = $this->class_settings[ 'html_replacement_selector' ];
						}
					}
				
					if( isset( $return['record']['id'] ) && $return['record']['id'] ){
						$e = $return['record'];
					}else{
						$this->class_settings[ 'current_record_id' ] = $return['saved_record_id'];
						unset( $this->class_settings[ 'do_not_check_cache' ] );
						$e = $this->_get_customer_call_log();
					}
					
					// print_r( $this->class_settings["action_to_perform"] );
					// print_r( $return[ 'saved_record_id' ] );
					if( isset( $e["id"] ) && $return['saved_record_id'] == $e["id"] ){
						$return["data"] = $e;
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
						if( ! empty( $filename_full ) ){
							$this->class_settings[ 'html' ] = $filename_full;
						}
						
						switch( $this->class_settings["action_to_perform"] ){
						case 'save_new_popup':
						
							$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/'.$filename );
							
							if( ! empty( $filename_full ) ){
								$this->class_settings[ 'html' ] = $filename_full;
							}
							
							$t = $this->table_name;
							
							if( isset( $this->class_settings["validate_override"] ) ){
								$this->class_settings[ 'data' ][ "validate_override" ] = $this->class_settings["validate_override"];
							}
							
							if( isset( $this->class_settings["other_params"] ) ){
								$this->class_settings[ 'data' ][ "other_params" ] = $this->class_settings["other_params"];
								unset( $this->class_settings["other_params"] );
							}
							
							if( isset( $this->plugin ) && $this->plugin ){
								$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
							}
							
							$this->class_settings[ 'data' ][ "validate_time" ] = get_validate_new_record_count_down_settings();
							$this->class_settings[ 'data' ][ "validate_prompt" ] = get_validate_new_record_settings();
							$this->class_settings[ 'data' ][ "reference" ] = $this->default_reference;
							$this->class_settings[ 'data' ][ "html_replacement_selector" ] = $handle;
							$this->class_settings[ 'data' ][ "table" ] = $t;
							$this->class_settings[ 'data' ][ "labels" ] = $t();
							$this->class_settings[ 'data' ][ "fields" ] = $this->table_fields;
							$this->class_settings[ 'data' ][ "items" ] = array( $return['saved_record_id'] => $e );
							$this->class_settings[ 'data' ][ "mobile_framework" ] = $mobile_framework;
							$returning_html_data = $this->_get_html_view();
							
							$js = array( "set_function_click_event" );
							
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							$return["html_replacement_selector"] = "#" . $handle;
							$return["html_replacement"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						break;
						}
						
						$this->class_settings[ 'data' ]["item"] = $e;
						$returning_html_data = $this->_get_html_view();
						
						if( $new ){
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							$return["html_prepend_selector"] = "#recent-expenses tbody";
							$return["html_prepend"] = $returning_html_data;
							$return["javascript_functions"] = $js;
							return $return;
						}
						
						unset( $return["html"] );
						
						$return["status"] = "new-status";
						
						$return["html_replace_selector"] = "#".$e["id"];
						$return["html_replace"] = $returning_html_data;
						$return["javascript_functions"] = $js;
						return $return;
					}
					
				}
			}else{
				$error_msg = '<h4>Invalid ID Field</h4>';
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
			
			return $return;
		}
		
		protected function _get_default_select2( $opt = array() ){

			$where = isset( $this->class_settings[ 'select2_where' ] ) ? $this->class_settings[ 'select2_where' ] : '';
			$limit = '';
			$search_term = '';
			$mongo_where = array();
			$mongo_filter = array();
			$mongo_others = array();
			$plugin = isset( $_GET[ 'plugin' ] ) ? $_GET[ 'plugin' ] : '';

			if( ! ( isset( $this->table_fields[ 'name' ] ) && $this->table_fields[ 'name' ] )  ){
				return;
			}
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				//OR `".$this->table_name."`.`".$this->table_fields["code"]."` regexp '".$search_term."'
				
				//$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` regexp '".$search_term."' ) ";
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` LIKE '%".$search_term."%' OR `".$this->table_name."`.`id` LIKE '%".$search_term."%' ) ";
				
				$mongo_where[ '$or' ][0][ $this->table_fields["name"] ][ '$regex' ] = '^'.$search_term;
				$mongo_where[ '$or' ][0][ $this->table_fields["name"] ][ '$options' ] = 'i';
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
				$mongo_filter[ 'limit' ] = get_default_select2_limit();
			}

			if( ( isset( $_GET[ 'exclude' ] ) && $_GET[ 'exclude' ] ) || ( isset( $_GET[ 'include' ] ) && $_GET[ 'include' ] ) ){
				$filter = array( 'exclude' => ( isset( $_GET[ 'exclude' ] ) ? $_GET[ 'exclude' ] : '' ), 'include' => ( isset( $_GET[ 'include' ] ) ? $_GET[ 'include' ] : '' ) );

				foreach( $filter as $f1 => $f2 ){
					
					if( $f2 ){
						$fil = '';
						switch( $f1 ){
						case 'exclude':
							$fil = '<>';
						break;
						case 'include':
							$fil = '=';
						break;
						}

						$z = preg_split( '(-|:)', $f2 );
						end( $z );
						if( ! $z[ key( $z ) ] )unset( $z[ key( $z ) ] );
						$x =  array_chunk( $z, 2 );

						$xx =  array_combine( array_column( $x, 0 ), array_column( $x, 1 ) );

						if( ! empty( $xx ) ){
							foreach( $xx as $kk => $vv ){
								switch( $kk ){
								case 'id':
								case 'created_by':
								case 'creation_date':
								case 'modified_by':
								case 'modified_date':
									$where .= " AND `".$this->table_name."`.`". $kk ."` ". $fil ." '".$vv."' ";
								break;
								default:
									if( isset( $this->table_fields[ $kk ] ) && $this->table_fields[ $kk ] ){
										$where .= " AND `".$this->table_name."`.`".$this->table_fields[ $kk ]."` ". $fil ." '".$vv."' ";
									}
								break;
								}
							}
						}
					}
				}
			}
			
			if( isset( $_GET[ "all" ] ) && $_GET[ "all" ] ){
				$limit = "";
				$mongo_filter[ 'limit' ] = "";
			}
			
			if( isset( $_GET[ "check_access" ] ) && $_GET[ "check_access" ] ){
				$access = get_accessed_functions();
				$super = 0;
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
				
				if( ! $super ){
					switch( $this->table_name ){
					case "state_list":
						
						if( isset( $access["status"]["access_all_states"] ) && $access["status"]["access_all_states"] ){
							
						}else if( isset( $access["states"] ) && ! empty( $access["states"] ) ){
							$ids = array();
							foreach( $access["states"] as $st => $sv ){
								$ids[] = "'". $st ."'";
							}
							$where = " AND `".$this->table_name."`.`id` IN ( ". implode(",", $ids ) ." ) ";
						}else{
							$where = " AND `".$this->table_name."`.`id` IS NULL ";
						}
					break;
					}
				}
			}
			
			$selecta = array();
			$select = "";
			$join = "";
			
			$selecta[] = "`".$this->table_name."`.`id` ";
			$selecta[] = "`".$this->table_name."`.`serial_num` ";
			
			$mongo_filter[ 'projection' ][ '_id' ] = 0;
			$mongo_filter[ 'projection' ][ 'id' ] = 1;
			$mongo_filter[ 'projection' ][ 'serial_num' ] = 1;
			
			$xkeys = array( 'name', 'code' );
			if( isset( $opt[ 'keys' ][0] ) && $opt[ 'keys' ][0] ){
				$xkeys = array_merge( $opt[ 'keys' ], $xkeys );
			}

			foreach( $this->table_fields as $key => $val ){
				if( $key == 'data' )continue;

				if( in_array( $key, $xkeys ) ){
					$selecta[] = " `".$this->table_name."`.`".$val."` as '".$key."' ";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
				
				if( isset( $this->class_settings["select_fields"][ $key ] ) ){
					$selecta[] = " `".$this->table_name."`.`".$val."` as '".$key."' ";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
				
				$xwhere = array();
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$xwhere = explode( ',', $_GET[ $key ] );
				}else if( isset( $_POST[ $key ] ) && $_POST[ $key ] ){
					$xwhere = explode( ',', $_POST[ $key ] );
				}
				
				if( ! empty( $xwhere ) ){
					$where .= " AND `".$val."` IN ( '". implode( "','", $xwhere ) ."' ) ";
					foreach( $xwhere as $xw ){
						$mongo_where[ '$and' ][][ $val ] = $xw;
					}
				}
			}
			
			$select = implode(", ", $selecta );
			
			if( isset( $_GET["source"] ) ){
				if( isset( $this->class_settings[ 'get_select2_option_type' ] ) && $this->class_settings[ 'get_select2_option_type' ] ){

					if( $plugin ){
						$pcl = 'c'.$plugin;

						if( class_exists( $pcl ) ){
							$this->class_settings[ 'plugin' ] = $plugin;
							$pcl = new $pcl;
							$pcl->load_class( array( 'class' => array( $_GET[ 'source' ] ) ) );
						}
					}

					switch( $this->class_settings[ 'get_select2_option_type' ] ){
					case 'state_list':
						$checks = array( "country" );
						$found_check = 0;
						
						switch( $_GET["source"] ){
						case "lga_list":
							$lga_list = new cLga_list();
							
							foreach( $checks as $cv ){
								if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
									$where .= " AND `".$this->table_fields[ $cv ]."` = '".$_POST[ $lga_list->table_fields[ $cv ] ]."' ";
									$mongo_where[ '$and' ][][ $this->table_fields[ $cv ] ] = $_POST[ $lga_list->table_fields[ $cv ] ];
									$found_check = 1;
									
								}
							}
						break;
						}
					break;
					case 'lga_list':
						$checks = array( "country", "state" );
						$found_check = 0;
						$select .= ", `". $this->table_fields[ 'state' ] ."` as 'state' ";
						
						switch( $_GET["source"] ){
						case "wag":
						case "policy_collaborators":
						case "files":
						case "users":
						case "workflow":
						case "survey_assignment":
						case "households":
						case "community":
						case "community_list":
						case "ward_list":
							$c1 = 'c' . strtolower( $_GET["source"] );
							
							if( class_exists( $c1 ) ){
								$lga_list = new $c1();
								
								foreach( $checks as $cv ){
									$field = '';
									$value = '';
									
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$field = $lga_list->table_fields[ $cv ];
										
										if( isset( $this->table_fields[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
										}
										
										$value = $_POST[ $lga_list->table_fields[ $cv ] ];
									}
									
									if( ! $field ){
										if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
											$value = $_POST[ $cv ];
										}
									}
									
									if( $field ){
										if( strpos( $value, ',' ) > -1 ){
											$where .= " AND `". $field ."` IN ( '". implode( "','", explode( ',', $value ) ) ."' ) ";
										}else{
											$where .= " AND `".$field."` = '".$value."' ";
										}
										$mongo_where[ '$and' ][][ $field ] = $value;
										$found_check = 1;
									}
								}
							}else{
								return array( "items" => array(), "do_not_reload_table" => 1 );
							}
						break;
						}
					break;
					case 'ward_list':
						$checks = array( "lga" );
						$found_check = 0;
						$select .= ", `". $this->table_fields[ 'state' ] ."` as 'state' ";
						$select .= ", `". $this->table_fields[ 'lga' ] ."` as 'lga' ";
						
						switch( $_GET["source"] ){
						case "wag":
						case "policy_collaborators":
						case "files":
						case "users":
						case "workflow":
						case "survey_assignment":
						case "households":
						case "community":
						case "community_list":
						case "ward_list":
							$c1 = 'c' . strtolower( $_GET["source"] );
							
							if( class_exists( $c1 ) ){
								$lga_list = new $c1();
								
								foreach( $checks as $cv ){
									
									$field = '';
									$value = '';
									
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$field = $lga_list->table_fields[ $cv ];
										
										if( isset( $this->table_fields[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
										}
										
										$value = $_POST[ $lga_list->table_fields[ $cv ] ];
									}
									
									if( ! $field ){
										if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
											$value = $_POST[ $cv ];
										}
									}
									
									if( $field ){
										if( strpos( $value, ',' ) > -1 ){
											$where .= " AND `". $field ."` IN ( '". implode( "','", explode( ',', $value ) ) ."' ) ";
										}else{
											$where .= " AND `".$field."` = '".$value."' ";
										}
										$mongo_where[ '$and' ][][ $field ] = $value;
										$found_check = 1;
									}
								}
							}else{
								return array( "items" => array(), "do_not_reload_table" => 1 );
							}
						break;
						}
					break;
					case 'community_list':
						$checks = array( "ward" );
						$found_check = 0;
						$select .= ", `". $this->table_fields[ 'ward' ] ."` as 'ward' ";
						$select .= ", `". $this->table_fields[ 'state' ] ."` as 'state' ";
						$select .= ", `". $this->table_fields[ 'lga' ] ."` as 'lga' ";
						
						switch( $_GET["source"] ){
						case "wag":
						case "policy_collaborators":
						case "files":
						case "users":
						case "workflow":
						case "survey_assignment":
						case "community":
						case "households":
							$c1 = 'c' . strtolower( $_GET["source"] );
							
							if( class_exists( $c1 ) ){
								$lga_list = new $c1();
								
								foreach( $checks as $cv ){
									
									$field = '';
									$value = '';
									
									if( isset( $lga_list->table_fields[ $cv ] ) && isset( $_POST[ $lga_list->table_fields[ $cv ] ] ) ){
										$field = $lga_list->table_fields[ $cv ];
										
										if( isset( $this->table_fields[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
										}
										
										$value = $_POST[ $lga_list->table_fields[ $cv ] ];
									}
									
									if( ! $field ){
										if( isset( $this->table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
											$field = $this->table_fields[ $cv ];
											$value = $_POST[ $cv ];
										}
									}
									
									if( $field ){
										if( strpos( $value, ',' ) > -1 ){
											$where .= " AND `". $field ."` IN ( '". implode( "','", explode( ',', $value ) ) ."' ) ";
										}else{
											$where .= " AND `".$field."` = '".$value."' ";
										}
										$mongo_where[ '$and' ][][ $field ] = $value;
										$found_check = 1;
									}
									
								}
							}
						break;
						}
						
						if( ! $found_check ){
							return array( "items" => array(), "do_not_reload_table" => 1 );
						}
					break;
					}
				}
				
				/*
				if( ! $found_check ){
					return array( "items" => array(), "do_not_reload_table" => 1 );
				}
				*/
			}

			$select .= ", `".$this->table_name."`.`".$this->table_fields["name"]."` as 'text'";
			$mongo_others[ 'concat' ][ 'value' ][] = '$'.$this->table_fields["name"];
			
			if( isset( $this->table_fields["code"] ) ){
				$mongo_others[ 'concat' ][ 'value' ][] = ' - ';
				$mongo_others[ 'concat' ][ 'value' ][] = '$'.$this->table_fields["code"];
			}
			$mongo_others[ 'concat' ][ 'text' ] = 'text';

			$mongo_others[ 'use_aggregate' ] = 1;
			
			$this->class_settings["overide_select"] = $select;
			if( isset( $this->class_settings["custom_select"] ) && $this->class_settings["custom_select"] ){
				$this->class_settings["overide_select"] = $this->class_settings["custom_select"];
			}
			$this->class_settings["order_by"] = " ORDER BY `".$this->table_name."`.`".$this->table_fields["name"]."` ";
			$this->class_settings["join"] = $join;
			$this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			$this->class_settings["mongo_where"] = $mongo_where;
			$this->class_settings["mongo_filter"] = $mongo_filter;
			$this->class_settings["mongo_others"] = $mongo_others;
			
			$data1 = $this->_get_records();
			
			if( isset( $this->class_settings[ 'return_indexed' ] ) && $this->class_settings[ 'return_indexed' ] ){
				$d_index = array();
				
				$indexed_fields = $this->class_settings[ 'return_indexed' ];
				
				if( ! empty( $data1 ) ){
					
					if( ! is_array( $indexed_fields ) ){
						unset( $indexed_fields );
						$indexed_fields = array( "id" );
					}
					
					$ix = count( $indexed_fields );
					
					foreach( $data1 as $d2 ){
						
						switch( $ix ){
						case 1:
							$d_index[ $d2[ $indexed_fields[ 0 ] ] ] = $d2;
						break;
						case 2:
							$d_index[ $d2[ $indexed_fields[ 0 ] ] ][ $d2[ $indexed_fields[ 1 ] ] ] = $d2;
						break;
						case 3:
							$d_index[ $d2[ $indexed_fields[ 0 ] ] ][ $d2[ $indexed_fields[ 1 ] ] ][ $d2[ $indexed_fields[ 2 ] ] ] = $d2;
						break;
						}
						
						
					}
				}
				
				return $d_index;
			}
			
			// print_r( $data1 );exit;
			return array( "items" => $data1, "do_not_reload_table" => 1 );
		}
		
		protected function _display_app_view(){
			$file = 'display-app-view';
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'view_version_2_in_active':
			case 'view_version_2_active':
			case 'view_version_2_completed':
			case 'view_version_2_in_theatre':
			case "view_version_2_pending_approval_lab":
			case "view_version_2_pending_approval_rad":
			case 'view_version_2_dispensed':
			case 'view_version_2_cancelled':
			case 'view_version_2_pending':
			case 'view_version_3_dispensed':
			case 'view_version_3_cancelled':
			case 'view_version_3_pending':
			case 'view_version_3':
			case 'view_version_2':
			case 'view_version_2_referred':
			case 'view_version_2_lab':
			case 'view_version_2_rad':
			case 'view_version_2_pending':
			case 'view_version_2_pending_referred':
			case 'view_version_2_complete_referred':
			case 'view_version_2_complete':
			case 'view_version_2_complete_lab':
			case 'view_version_2_complete_rad':
			case 'view_version_2_pending_lab':
			case 'view_version_2_pending_rad':
			case 'view_version_2_inactive':
			case 'view_version_2_inactive_referred':
			case 'view_version_2_inactive_lab':
			case 'view_version_2_inactive_rad':
			case 'view_pending_investigation_result_rad':
			case 'view_pending_investigation_result_lab':
			case 'view_pending_investigation_result_referred':
			case 'view_pending_investigation_result':
			case 'view_version2_report':
			case "nursing_prescription2_pending":
			case "nursing_prescription2_completed":
			case "nursing_prescription2_cancelled":
			case "nursing_prescription2_sent_to_dispensary":
				$this->class_settings[ 'data' ][ 'hide_delete_button' ] = 1;
				$this->class_settings[ 'data' ][ 'hide_new_button' ] = 1;
				$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			case 'display_app_manager':
			case 'display_app_manager_closed_tickets':
			case 'view_version2_report2':
				$t = $this->table_name;
				
				$this->class_settings[ 'data' ][ 'table_label' ] = $t();
				$this->class_settings[ 'data' ][ 'table_fields' ] = $this->table_fields;
				$this->class_settings[ 'data' ][ 'label' ] = $this->label;
				
				if( isset( $this->class_settings["hidden_table_fields"] ) ){
					$this->class_settings[ 'data' ]["hidden_table_fields"] = $this->class_settings["hidden_table_fields"];
					unset( $this->class_settings["hidden_table_fields"] );
				}
				
				$file = 'display-app-manager';
			break;
			case 'display_app_view':
				//$this->class_settings[ 'data' ]['customer_call_log'] = $this->_get_all_customer_call_log();
				
				$this->class_settings[ 'data' ]['title'] = 'Add New ' . $this->label;
				$this->class_settings[ 'data' ]['label'] = 'New ' . $this->label;
			break;
			}
			
			if( isset( $this->class_settings["reference"] ) && $this->class_settings["reference"] && isset( $this->default_reference ) && $this->default_reference ){
				$this->class_settings[ 'data' ]['reference_value'] = $this->class_settings["reference"];
				$this->class_settings[ 'data' ]['reference'] = $this->default_reference;
			}
			
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$this->class_settings[ 'data' ][ 'hide_delete_button' ] = 1;
				$this->class_settings[ 'data' ][ 'hide_new_button' ] = 1;
			}
			
			$this->class_settings[ 'data' ]['table'] = $this->table_name;
			
			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = "#".$ccx;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/'.$file );
			// print_r( $this->class_settings[ 'html' ] );exit;
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.refreshAcitveTab' ) 
			);
		}
		
		protected function _get_all_customer_call_log( $fparams = array() ){
			return $this->_get_records( $fparams );
		}
		
		public function _get_select( $o = array() ){
			$tb = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name;
			}
			$table = isset( $o["table_name"] )?$o["table_name"]:$tb;
			$table_fields = isset( $o["table_fields"] )?$o["table_fields"]:$this->table_fields;
			
			if( defined("HYELLA_LYTICS_CONNECTED") ){
				$fields = array();
				$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$table."`";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'query' => $query,
					'query_type' => 'DESCRIBE',
					'set_memcache' => 1,
					'tables' => array( $table ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( $sql_result && is_array( $sql_result ) ){
					foreach( $sql_result as $sval ){
						$fields[ $sval["Field"] ] = $sval;
					}
				}
				
			}else{
				if( isset( $o["fields"] ) && ! empty( $o["fields"] ) ){
					$fields = $o["fields"];
				}
			}
			
			$tf = $table_fields;
			$tf["id"] = "id";
			$tf["serial_num"] = "serial_num";
			$tf["created_by"] = "created_by";
			$tf["modification_date"] = "modification_date";
			$tf["creation_date"] = "creation_date";
			$tf["modified_by"] = "modified_by";
			
			$sels = array();
			foreach( $tf as $key => $val ){
				if( isset( $fields ) ){
					if( isset( $fields[ $val ] ) ){
						$sels[] = " `".$table."`.`".$val."` as '".$key."' ";
					}
				}else{
					$sels[] = " `".$table."`.`".$val."` as '".$key."' ";
				}
			}
			
			return implode( ",", $sels );
		}
		
		public function _get_records( $fparams = array() ){
			$tb = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name;
			}
			if( isset( $fparams["table_name"] ) && $fparams["table_name"] ){
				$tb = $fparams["table_name"];
			}
			
			$select = "";
			$mongo_filter = array();
			foreach( $this->table_fields as $key => $val ){
				if( $select || ! empty( $mongo_filter ) ){
					$select .= ", `".$tb."`.`".$val."` as '".$key."'";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}else{ 
					$select = " `".$tb."`.`id`, `".$tb."`.`serial_num`, `".$tb."`.`created_by`, `".$tb."`.`modification_date`, `".$tb."`.`creation_date`, `".$tb."`.`modified_by`, `".$tb."`.`".$val."` as '".$key."'";
					$mongo_filter[ 'projection' ][ '_id' ] = 0;
					$mongo_filter[ 'projection' ][ 'id' ] = 1;
					$mongo_filter[ 'projection' ][ 'serial_num' ] = 1;
					$mongo_filter[ 'projection' ][ 'created_by' ] = 1;
					$mongo_filter[ 'projection' ][ 'modification_date' ] = 1;
					$mongo_filter[ 'projection' ][ 'creation_date' ] = 1;
					$mongo_filter[ 'projection' ][ 'modified_by' ] = 1;
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
			}
			
			if( isset( $this->class_settings["overide_select"] ) && $this->class_settings["overide_select"] ){
				$select = $this->class_settings["overide_select"];
				unset( $this->class_settings["overide_select"] );
			}else if( isset( $this->class_settings["custom_select"] ) && $this->class_settings["custom_select"] ){
				$select = $this->class_settings["custom_select"];
				unset( $this->class_settings["custom_select"] );
			}else{
				$o1 = array();
				if( isset( $fparams["select_fields"] ) && $fparams["select_fields"] ){
					$o1["fields"] = $fparams["select_fields"];
				}
				
				$select = $this->_get_select( $o1 );
			}
			
			$where = "";
			if( isset( $this->class_settings["where"] ) && $this->class_settings["where"] ){
				$where = $this->class_settings["where"];
				unset( $this->class_settings["where"] );
			}
		
			$order = "";
			if( isset( $this->table_fields["date"] ) ){
				$order = " ORDER BY `".$tb."`.`".$this->table_fields["date"]."` DESC ";
				
			}
			
			if( isset( $this->class_settings["order_by"] ) && $this->class_settings["order_by"] ){
				$order = $this->class_settings["order_by"];
				unset( $this->class_settings["order_by"] );
			}
			
			$join = "";
			if( isset( $this->class_settings["join"] ) && $this->class_settings["join"] ){
				$join = $this->class_settings["join"];
				unset( $this->class_settings["join"] );
			}
			
			$group = "";
			if( isset( $this->class_settings["group"] ) && $this->class_settings["group"] ){
				$group = $this->class_settings["group"];
				unset( $this->class_settings["group"] );
			}
			
			$e_select = "";
			if( isset( $this->class_settings["select"] ) && $this->class_settings["select"] ){
				$e_select = $this->class_settings["select"];
				unset( $this->class_settings["select"] );
			}
			
			$limit = "";
			if( isset( $this->class_settings["limit"] ) && $this->class_settings["limit"] ){
				$limit = $this->class_settings["limit"];
				unset( $this->class_settings["limit"] );
			}
			
			$from = " FROM `".$this->class_settings['database_name']."`.`".$tb."` ";
			if( isset( $this->class_settings["from"] ) && $this->class_settings["from"] ){
				$from = $this->class_settings["from"];
				unset( $this->class_settings["from"] );
			}
			
			$fwhere = " WHERE `".$tb."`.`record_status` = '1' ";
			if( isset( $this->class_settings["no_where"] ) && $this->class_settings["no_where"] ){
				$fwhere = '';
				unset( $this->class_settings["no_where"] );
			}
			
			$use_cache = 1;
			if( isset( $fparams["cache"] ) ){
				$use_cache = intval( $fparams["cache"] );
			}
			
			$skip_log = 0;
			if( isset( $fparams["skip_log"] ) ){
				$skip_log = intval( $fparams["skip_log"] );
			}
			
			$index_field = '';
			if( isset( $fparams["index_field"] ) ){
				$index_field = $fparams["index_field"];
			}
			
			$index_type = '';
			if( isset( $fparams["index_type"] ) ){
				$index_type = $fparams["index_type"];
			}
			
			if( isset( $this->class_settings["mongo_filter"] ) && $this->class_settings["mongo_filter"] ){
				$mongo_filter = $this->class_settings["mongo_filter"];
				unset( $this->class_settings["mongo_filter"] );
			}

			$mongo_where = array();
			if( isset( $this->class_settings["mongo_where"] ) && $this->class_settings["mongo_where"] ){
				$mongo_where = $this->class_settings["mongo_where"];
				unset( $this->class_settings["mongo_where"] );
			}

			$mongo_others = array();
			if( isset( $this->class_settings["mongo_others"] ) && $this->class_settings["mongo_others"] ){
				$mongo_others = $this->class_settings["mongo_others"];
				unset( $this->class_settings["mongo_others"] );
			}

			if( ! $select && ! $e_select ){
				$select = '*';
			}
			
			$query = "SELECT ".$select.$e_select.$from.$join.$fwhere.$where.$group.$order.$limit;
			
			if( isset( $fparams["query"] ) && $fparams["query"] ){
				$query = $fparams["query"];
			}
			// echo $query . '<br /><br />'; 
			/*
			switch( $tb ){
			case "labour_outcome":
			case "obstetric_history":
			case "investigation":
			case "users_disciplinary_history":
			case "vitals2":
			echo $query; exit;
			break;
			}
			*/
			switch( $tb ){
			case 'wag_leader':
				// print_r( $query );exit;
			break;
			}
			
			$tables = array( $tb );
			if( isset( $this->class_settings["tables"] ) && is_array( $this->class_settings["tables"] ) ){
				$tables = $this->class_settings["tables"];
			}
			$mongo_others[ 'use_aggregate' ] = 1;

			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => $use_cache,
				'tables' => $tables,

				// MongoDB
				'table' => $tb,
				'where' => $mongo_where,
				'select' => $mongo_filter,
				'others' => $mongo_others,
				'index_field' => $index_field,
				'index_type' => $index_type,
				'skip_log' => $skip_log,
			);
			// print_r( $query_settings );exit;
			$this->last_query = $query_settings;
			$result = execute_sql_query( $query_settings );
			
			// print_r( $result ); exit;
			
			return $result;
		}
		
		protected function _set_nsr_filters(){
			$error_msg = '';
			$where = "";
			$join = "";
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$params = isset( $this->class_settings["params"] )?$this->class_settings["params"]:$_GET;
			
			$country = 'nigeria';
			$state = isset( $params["state"] )?$params["state"]:'';
			$lga = isset( $params["lga"] )?$params["lga"]:'';
			$ward = isset( $params["ward"] )?$params["ward"]:'';
			$community = isset( $params["community"] )?$params["community"]:'';
			
			$access = get_accessed_functions();
			$super = 0;
			if( ! is_array( $access ) && $access == 1 ){
				$super = 1;
			}
			
			$f_ward = "";
			$f_lga = "";
			$f_state = "";
			
			$check_ward = 1;
			$check_lga = 1;
			$check_state = 1;
			if( isset( $this->basic_data["ignore_access_control"]["lga"] ) && $this->basic_data["ignore_access_control"]["lga"] ){
				$check_lga = 0;
			}
			
			if( isset( $this->basic_data["ignore_access_control"]["state"] ) && $this->basic_data["ignore_access_control"]["state"] ){
				$check_state = 0;
			}
			
			if( ! $super ){
				if( isset( $access["status"]["access_all_states"] ) && $access["status"]["access_all_states"] ){
					$check_state = 0;
				}
				
				if( isset( $access["status"]["access_all_lgas"] ) && $access["status"]["access_all_lgas"] ){
					$check_lga = 0;
				}
			}
			
			if( method_exists( $this, "_set_nsr_filters_params" ) ){
				$j = $this->_set_nsr_filters_params();
				
				if( isset( $j["join_table"] ) && $j["join_table"] && isset( $j["join_table_field"] ) && $j["join_table_field"] && isset( $j["join_field"] ) && $j["join_field"] ){
					$join = " JOIN `".$this->class_settings["database_name"]."`.`". $j["join_table"] ."` ON `". $j["join_table"] ."`.`". $j["join_table_field"] ."` = `". $this->table_name ."`.`". $j["join_field"] ."` AND `". $j["join_table"] ."`.`record_status` = '1' ";
				}
				
				if( isset( $j["lga_table"] ) && isset( $j["lga_field"] ) && $j["lga_table"] && $j["lga_field"] ){
					$f_lga = "`". $j["lga_table"] ."`.`". $j["lga_field"] ."`";
				}
				
				if( isset( $j["state_table"] ) && isset( $j["state_field"] ) && $j["state_table"] && $j["state_field"] ){
					$f_state = "`". $j["state_table"] ."`.`". $j["state_field"] ."`";
				}
			}
			
			if( ! $f_ward ){
				if( isset( $this->table_fields["ward"] ) ){
					$f_ward = "`". $this->table_name ."`.`". $this->table_fields["ward"] ."`";
				}
			}
			
			if( ! $f_lga ){
				if( isset( $this->table_fields["lga"] ) ){
					$f_lga = "`". $this->table_name ."`.`". $this->table_fields["lga"] ."`";
				}
			}
			
			if( ! $f_state ){
				if( isset( $this->table_fields["state"] ) ){
					$f_state = "`". $this->table_name ."`.`". $this->table_fields["state"] ."`";
				}
			}
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_ssr":
				if( ! $state ){
					$state = get_current_customer( "state" );
					if( ! $state ){
						$error_msg = '<h4>Invalid State</h4><p>Please select a State</p>';
					}
				}
				
				if( ! $error_msg ){
					
					$this->datatable_settings["button_params"] = '&enforce_state=' . $state . '&enforce_country=' . $country;
					
					if( $check_lga && ! $lga ){
						
						if( ! $f_lga ){
							$error_msg = '<h4>Invalid LGA field #1</h4><p>'.$this->table_name.' does not have LGA field</p>';
						}
						
						if( ! $error_msg ){
							if( ! $super ){	
								if( isset( $access["lga"][ $state ]["all"] ) ){
								
								}else if( isset( $access["lga"][ $state ] ) && ! empty( $access["lga"][ $state ] ) ){
									$ids = array();
									
									foreach( $access["lga"][ $state ] as $st => $sv ){
										$ids[] = "'". $st ."'";
									}
									
									$where = " AND ".$f_lga." IN ( ". implode(",", $ids ) ." ) ";
								}else{
									$where = " AND ".$f_lga." IS NULL ";
								}
							}
						}
					}
					
				}
			break;
			case 'display_all_records_frontend_ward':
				if( ! $ward ){
					$ward = get_current_customer( "ward" );
					if( ! $ward ){
						$error_msg = '<h4>Invalid WARD</h4><p>Please select a WARD</p>';
					}
				}
				
				if( ! $error_msg ){
					
					$this->datatable_settings["button_params"] = '&enforce_ward=' . $lga . '&enforce_country=' . $country;
					
					if( ! $super ){	
						if( isset( $access["ward"][ $lga ]["all"] ) ){
						
						}else if( isset( $access["ward"][ $lga ] ) && ! empty( $access["ward"][ $lga ] ) ){
							$ids = array();
							
							foreach( $access["ward"][ $lga ] as $st => $sv ){
								$ids[] = "'". $st ."'";
							}
							
							$where = " AND ".$f_ward." IN ( ". implode(",", $ids ) ." ) ";
						}else{
							$where = " AND ".$f_ward." IS NULL ";
						}
					}
					
				}
			break;
			case 'display_all_records_frontend_lga':
				if( ! $lga ){
					$lga = get_current_customer( "lga" );
					if( ! $lga ){
						$error_msg = '<h4>Invalid LGA</h4><p>Please select a LGA</p>';
					}
				}
				
				if( ! $error_msg ){
					
					$this->datatable_settings["button_params"] = '&enforce_lga=' . $lga . '&enforce_country=' . $country;
					
					if( ! $super ){	
						if( isset( $access["ward"][ $lga ]["all"] ) ){
						
						}else if( isset( $access["ward"][ $lga ] ) && ! empty( $access["ward"][ $lga ] ) ){
							$ids = array();
							
							foreach( $access["ward"][ $lga ] as $st => $sv ){
								$ids[] = "'". $st ."'";
							}
							
							$where = " AND ".$f_ward." IN ( ". implode(",", $ids ) ." ) ";
						}else{
							$where = " AND ".$f_ward." IS NULL ";
						}
					}
					
				}
			break;
			case "display_all_records_frontend_nsr":
				if( $check_state && ! $state ){
					if( ! $super ){
						
						if( ! $f_state ){
							$error_msg = '<h4>Invalid STATE field #1</h4><p>'.$this->table_name.' does not have STATE field</p>';
						}
						
						if( ! $error_msg ){
							if( isset( $access["states"] ) && ! empty( $access["states"] ) ){
								$ids = array();
								foreach( $access["states"] as $st => $sv ){
									$ids[] = "'". $st ."'";
								}
								$where = " AND ".$f_state." IN ( ". implode(",", $ids ) ." ) ";
							}else{
								$where = " AND ".$f_state." IS NULL ";
							}
						}
					}
				}
			break;
			}
			
			if( $check_state && $state ){
				
				if( $f_state ){
					$where .= " AND ".$f_state." = '". $state ."' ";
				}else{
					$error_msg = '<h4>Invalid STATE field #2</h4><p>'.$this->table_name.' does not have STATE field</p>';
				}
				
			}
			
			if( $check_lga && $lga ){
		
				if( $f_lga ){
					$where .= " AND ".$f_lga." = '". $lga ."' ";
				}else{
					$error_msg = '<h4>Invalid LGA field #2</h4><p>'.$this->table_name.' does not have LGA field</p>';
				}
				
			}
			
			if( $check_ward && $ward ){
		
				if( $f_ward ){
					$where .= " AND ".$f_ward." = '". $ward ."' ";
				}else{
					$error_msg = '<h4>Invalid Ward field #2</h4><p>'.$this->table_name.' does not have Ward field</p>';
				}
				
			}
			
			return array(
				'error' => $error_msg,
				'where' => $where,
				'join' => $join,
			);
		}
		
		protected function _display_all_records_full_view(){
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			/*
			if( isset( $this->class_settings[ "current_tab" ] ) ){
				switch( $this->class_settings[ "current_tab" ] ){
				case "ssr":
				case "nsr":
					if( isset( $this->sr_table ) ){
						$this->table_name = $this->sr_table;
					}
				break;
				}
			}
			*/
			switch( $action_to_perform ){
			case "display_selected_records_frontend":
				$this->class_settings[ "hide_title" ] = 1;
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "frontend" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				
				if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
					$js = array_merge( array( '$.fn.cCallBack.displayTabTitle' ), $js );
					$rdata["tab_title"] = rawurldecode( $_GET["menu_title"] );
				}
				
				$fwhere = '';
				if( isset( $_GET["field"] ) && isset( $_POST["id"] ) && $_POST["id"] && isset( $this->table_fields[ $_GET["field"] ] ) ){
					$fwhere .= " AND `".$this->table_name."`.`".$this->table_fields[ $_GET["field"] ]."` = '". $_POST["id"] ."'  ";
				}
				
				$this->class_settings[ 'filter' ][ 'where' ] = $fwhere;
				
			break;
			case "display_all_records_frontend":
				$this->class_settings[ "frontend" ] = 1;
			break;
			case "display_my_records_frontend":
				$this->class_settings[ "frontend" ] = 1;
				$where = " AND `".$this->table_name."`.`created_by` = '". $this->class_settings[ 'user_id' ] ."' ";
				
				$this->class_settings['title'] = 'My ' . $this->label;
				
				if( ! isset( $this->class_settings[ 'filter' ][ 'where' ] ) ){
					$this->class_settings[ 'filter' ][ 'where' ] = '';
				}
				$this->class_settings[ 'filter' ][ 'where' ] .= $where;
			break;
			case "display_shared_records":
			case "display_shared_with_me":
			case "display_shared_with_me_unread":
			case "display_shared_with_me_read":
				$this->class_settings[ "frontend" ] = 1;
				
				//$this->class_settings['title'] = 'My ' . $this->label;
				
				$share = new cShare();
				$join_where = " AND ( `".$share->table_name."`.`". $share->table_fields["share"] ."` = '". $this->class_settings[ 'user_id' ] ."' AND `".$share->table_name."`.`". $share->table_fields["share_table"] ."` = 'users' ) ";
				
				$dt = 1;
				$join_prefix = '';
				
				switch( $action_to_perform ){
				case "display_shared_with_me_unread":
					$join_where .= " AND `".$share->table_name."`.`". $share->table_fields["status"] ."` = 'unread' ";
					$this->class_settings['suffix_title'] = ' - Unread';
				break;
				case "display_shared_with_me_read":
					$join_where .= " AND `".$share->table_name."`.`". $share->table_fields["status"] ."` = 'read' ";
					$this->class_settings['suffix_title'] = ' - Read';
				break;
				case "display_shared_records":
					$dt = 0;
					$status = isset( $_GET['r_status'] )? $_GET['r_status'] : '';
					$r_type = isset( $_GET['r_type'] )? $_GET['r_type'] : '';
					$where = "";
					
					$ftype2 = 1;
					$ftype = $r_type;
					switch( $r_type ){
					case "assigned":
						switch( $status ){
						case "unshared":
							$where = " AND `".$share->table_name."`.`id` IS NULL ";
							
							$join_prefix = " LEFT";
							$join_where = " AND `".$share->table_name."`.`". $share->table_fields["type"] ."` IN ( '".$ftype."', 'reviewed' ) ";
							
							$ftype2 = 0;
						break;
						case "shared":
						case "shared_by_me":
							$join_where = "";
						break;
						case "my_return_shared":
							$ftype2 = 0;
							$join_where = " AND `".$share->table_name."`.`". $share->table_fields["type"] ."` IN ( 'revoked', 'reviewed' ) ";
						break;
						case "return_shared":
							$join_where = "";
							$ftype = 'reviewed';
						break;
						case "my_shared":
							//$join_where = "";
							$this->class_settings['suffix_title'] = ' - Assigned to Me';
						break;
						}
						
						switch( $status ){
						case "shared_by_me":
						case "my_return_shared":
							$where .= " AND `".$share->table_name."`.`created_by` = '". $this->class_settings[ 'user_id' ] ."' ";
						break;
						}
						
					break;
					}
					
					if( $ftype2 ){
						$join_where .= " AND `".$share->table_name."`.`". $share->table_fields["type"] ."` = '".$ftype."' ";
					}
					
					if( isset( $this->datatable_settings["datatable_split_screen"]["action"] ) && $this->datatable_settings["datatable_split_screen"]["action"] ){
						$this->datatable_settings["datatable_split_screen"]["action"] = '?action='.$share->table_name.'&todo=split_screen_view&nwp_share_type='. $r_type .'&nwp_share_status=' . $status . '&oaction=' . rawurlencode( $this->datatable_settings["datatable_split_screen"]["action"] );
						
					}
					
					if( ! isset( $this->class_settings[ 'filter' ][ 'where' ] ) ){
						$this->class_settings[ 'filter' ][ 'where' ] = '';
					}
					$this->class_settings[ 'filter' ][ 'where' ] .= $where;
					
					
					if( ! ( isset( $this->class_settings[ 'custom_ds_control' ] ) && $this->class_settings[ 'custom_ds_control' ] ) ){
						$this->datatable_settings[ "show_add_new" ] = 0;
						$this->datatable_settings[ "show_edit_button" ] = 0;
						$this->datatable_settings[ "show_delete_button" ] = 0;
					}
				break;
				}
				
				if( $this->table_name == $share->table_name ){
					$this->class_settings['title'] = 'Shared with Me';
					$this->class_settings[ 'filter' ][ 'where' ] = $join_where;
				}else{
					if( $dt ){
						$this->class_settings['title'] = $this->label . ' - Shared with Me';
					}
					
					$this->class_settings[ 'filter' ][ 'order' ] = " ORDER BY `".$share->table_name."`.`modification_date` DESC ";
					
					$this->class_settings[ 'filter' ][ 'select' ] = " `".$this->table_name."`.*, `".$share->table_name."`.`modification_date` as '". $this->table_fields["date"] ."' ";
					$this->datatable_settings['show_selection']["index_fields"]["share_id"] = "share_id";
					
					if( ! isset( $this->class_settings[ 'filter' ][ 'e_select' ] ) ){
						$this->class_settings[ 'filter' ][ 'e_select' ] = '';
					}
					$this->class_settings[ 'filter' ][ 'e_select' ] .= ", `".$share->table_name."`.`id` as 'share_id' ";
					
					$this->class_settings[ 'filter' ][ 'join' ] = $join_prefix . " JOIN `". $this->class_settings[ 'database_name' ] ."`.`".$share->table_name."` ON ( ( `".$share->table_name."`.`". $share->table_fields["reference"] ."` = `".$this->table_name."`.`id` AND `".$share->table_name."`.`". $share->table_fields["reference_table"] ."` = '". $this->table_name ."' ) OR ( `".$share->table_name."`.`". $share->table_fields["reference"] ."` = `".$this->table_name."`.`".$this->table_fields["reference"]."` AND `".$share->table_name."`.`". $share->table_fields["reference_table"] ."` = `".$this->table_fields["reference_table"]."` ) ) AND `".$share->table_name."`.`record_status` = '1' " . $join_where;
					
					$this->class_settings[ 'filter' ][ 'join_count' ] = $this->class_settings[ 'filter' ][ 'join' ];
				}
			break;
			case "display_all_records_frontend_ward":
			case "display_all_records_frontend_lga":
			case "display_all_records_frontend_ssr":
			case "display_all_records_frontend_nsr":
				
				$this->class_settings[ "frontend" ] = 1;
				$f = $this->_set_nsr_filters();
				if( isset( $f["error"] ) && $f["error"] ){
					return $this->_display_notification( array( "type" => 'error', "message" => $f["error"] ) );
				}
				
				if( ! ( isset( $this->class_settings[ 'filter' ][ 'where' ] ) && $this->class_settings[ 'filter' ][ 'where' ] ) ){
					$this->class_settings[ 'filter' ][ 'where' ] = '';
				}
				
				if( isset( $f["where"] ) && $f["where"] ){
					$this->class_settings[ 'filter' ][ 'where' ] .= $f["where"];
				}
				
				if( isset( $f["join"] ) && $f["join"] ){
					$this->class_settings[ 'filter' ][ 'join' ] = $f["join"];
					$this->class_settings[ 'filter' ][ 'join_count' ] = $f["join"];
				}
			break;
			}

			//to correct mistake --remove later
			if( ! ( isset( $this->class_settings[ 'filter' ][ 'additional_where' ] ) && $this->class_settings[ 'filter' ][ 'additional_where' ] ) ){
				$this->class_settings[ 'filter' ][ 'additional_where' ] = '';
			}

			if( isset( $this->class_settings[ 'filter' ][ 'where' ] ) && $this->class_settings[ 'filter' ][ 'where' ] ){
				$this->class_settings[ 'filter' ][ 'additional_where' ] .= $this->class_settings[ 'filter' ][ 'where' ];
				unset( $this->class_settings[ 'filter' ][ 'where' ] );
			}

			if( isset( $this->class_settings[ 'datatable_settings' ] ) && is_array( $this->class_settings[ 'datatable_settings' ] ) && ! empty( $this->class_settings[ 'datatable_settings' ] ) ){
				$this->datatable_settings = array_merge( $this->datatable_settings, $this->class_settings[ 'datatable_settings' ] );
			}
			
			$this->_set_custom_datatable_buttons();
			
			$new_format = 0;
			if( isset( $this->class_settings[ "frontend" ] ) && $this->class_settings[ "frontend" ] ){
				$dkey = 'show_add_new';
				if( isset( $this->datatable_settings[$dkey] ) && $this->datatable_settings[$dkey] ){
					if( ! is_array( $this->datatable_settings[$dkey] ) ){
						unset( $this->datatable_settings[$dkey] );
						$this->datatable_settings[$dkey] = array();
					}
					
					if( ! isset( $this->datatable_settings[$dkey]['function-name'] ) ){
						$this->datatable_settings[$dkey]['function-text'] = 'New';
						$this->datatable_settings[$dkey]['function-name'] = 'new_popup_form';
					}
				}
				
				$dkey = 'show_edit_button';
				if( isset( $this->datatable_settings[$dkey] ) && $this->datatable_settings[$dkey] ){
					if( ! is_array( $this->datatable_settings[$dkey] ) ){
						unset( $this->datatable_settings[$dkey] );
						$this->datatable_settings[$dkey] = array();
					}
					
					if( ! isset( $this->datatable_settings[$dkey]['function-name'] ) ){
						$this->datatable_settings[$dkey]['function-text'] = 'Edit';
						$this->datatable_settings[$dkey]['function-name'] = 'edit_popup_form';
					}
				}
				$new_format = 1;
			}else if( isset( $this->class_settings[ "show_popup_form" ] ) && $this->class_settings[ "show_popup_form" ] ){
				$dkey = 'show_add_new';
				if( isset( $this->datatable_settings[$dkey] ) && $this->datatable_settings[$dkey] ){
					if( ! is_array( $this->datatable_settings[$dkey] ) ){
						unset( $this->datatable_settings[$dkey] );
						$this->datatable_settings[$dkey] = array();
					}
					
					if( ! isset( $this->datatable_settings[$dkey]['function-name'] ) ){
						$this->datatable_settings[$dkey]['function-text'] = 'New';
						$this->datatable_settings[$dkey]['function-name'] = 'new_popup_form';
					}
				}
				
				$dkey = 'show_edit_button';
				if( isset( $this->datatable_settings[$dkey] ) && $this->datatable_settings[$dkey] ){
					if( ! is_array( $this->datatable_settings[$dkey] ) ){
						unset( $this->datatable_settings[$dkey] );
						$this->datatable_settings[$dkey] = array();
					}
					
					if( ! isset( $this->datatable_settings[$dkey]['function-name'] ) ){
						$this->datatable_settings[$dkey]['function-text'] = 'Edit';
						$this->datatable_settings[$dkey]['function-name'] = 'edit_popup_form';
					}
				}
			}
			
			if( isset( $this->class_settings[ "return_new_format" ] ) && $this->class_settings[ "return_new_format" ] ){
				$new_format = 1;
			}
			
			if( $new_format ){
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					if( ! isset( $this->datatable_settings["button_params"] ) ){
						$this->datatable_settings["button_params"] = '';
					}
					
					$this->datatable_settings["button_params"] .= '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				if( isset( $this->datatable_settings['show_advance_search'] ) && $this->datatable_settings['show_advance_search'] ){
					$this->datatable_settings['show_advance_search'] = 0;
					$this->datatable_settings['popup_search'] = 1;
				}
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/display-all-records-full-view' );
			
			
			$datatable = $this->_display_data_table();
			
			if( isset( $this->class_settings["show_form"] ) && $this->class_settings["show_form"] ){
				$form = $this->_generate_new_data_capture_form();
				$this->class_settings[ 'data' ]['form_data'] = isset( $form['html'] )?$form['html']:( isset( $form['html_replacement'] )?$form['html_replacement']:'' );
			}
			
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			//$this->class_settings[ 'data' ]['title'] = "Manage Patient Medication";
			$this->class_settings[ 'data' ]['title'] = ucwords( $this->label ); // . " Register";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;

			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ]['plugin'] = $this->plugin;
				// $this->class_settings[ 'data' ]['plugin'] = ( isset( $this->redirect_plugin ) ? $this->redirect_plugin : $this->plugin );
			}
			// print_r( $this->class_settings[ 'filter' ][ 'additional_where' ] );exit;
			
			if( isset( $this->class_settings['title'] ) && $this->class_settings['title'] ){
				$this->class_settings[ 'data' ]['title'] = $this->class_settings['title'];
			}else{
				if( isset( $_GET[ 'title' ] ) && $_GET[ 'title' ] ){
					$this->class_settings[ 'data' ][ 'title' ] = rawurldecode( $_GET[ 'title' ] );
				}else if( isset( $_GET[ 'menu_title2' ] ) && $_GET[ 'menu_title2' ] ){
					$this->class_settings[ 'data' ][ 'title' ] = rawurldecode( $_GET[ 'menu_title2' ] );
				}else if( isset( $_GET[ 'menu_title' ] ) && $_GET[ 'menu_title' ] ){
					$this->class_settings[ 'data' ][ 'title' ] = rawurldecode( $_GET[ 'menu_title' ] );
				}
			}
			
			if( isset( $this->class_settings[ 'data' ]['title'] ) && isset( $this->class_settings['prefix_title'] ) ){
				$this->class_settings[ 'data' ]['title'] = $this->class_settings['prefix_title'] . $this->class_settings[ 'data' ]['title'];
			}
			
			if( isset( $this->class_settings[ 'data' ]['title'] ) && isset( $this->class_settings['suffix_title'] ) ){
				$this->class_settings[ 'data' ]['title'] .= $this->class_settings['suffix_title'];
			}
			
			if( isset( $this->class_settings['hide_title'] ) ){
				$this->class_settings[ 'data' ]['hide_title'] = $this->class_settings['hide_title'];
			}
			
			if( isset( $this->class_settings[ "frontend" ] ) && $this->class_settings[ "frontend" ] ){
				$this->class_settings[ 'data' ]['no_col_1'] = 1;
			}

			if( isset( $this->class_settings[ "table_filter" ] ) && $this->class_settings[ "table_filter" ] ){
				$this->class_settings[ 'data' ]['table_filter'] = $this->class_settings[ "table_filter" ];
			}
			
			$returning_html_data = $this->_get_html_view();
			
			if( $new_format ){
				
				$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
				$handle = "#".$ccx;
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				}
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'nwResizeWindow.resizeWindow',  '$nwProcessor.recreateDataTables', '$nwProcessor.update_column_view_state', '$nwProcessor.set_function_click_event', 'prepare_new_record_form_new' ) 
				);
			}
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'nwResizeWindow.resizeWindow', '$nwProcessor.recreateDataTables', '$nwProcessor.set_function_click_event', '$nwProcessor.update_column_view_state', 'prepare_new_record_form_new' ) 
			);
		}
		
		
		function _set_custom_datatable_buttons( $o = array() ){
			$ub = array();
			$html = '';
			$return = 0;
			
			if( isset( $o['custom_settings'] ) && ! empty( $o['custom_settings'] ) ){
				$return = 1;
				$this->datatable_settings = $o['custom_settings'];
			}
			
			if( isset( $this->datatable_settings['utility_buttons'] ) && ! empty( $this->datatable_settings['utility_buttons'] ) ){
				$ub[ 'utility_buttons' ] = $this->datatable_settings['utility_buttons'];
				if( isset( $this->datatable_settings['set_html_container'] ) && ! empty( $this->datatable_settings['set_html_container'] ) ){
					$ub[ 'set_html_container' ] = $this->datatable_settings['set_html_container'];
				}
				
				if( defined("HYELLA_NO_DOCUMENT") ){
					unset( $ub[ 'utility_buttons' ][ 'attach_file' ] );
					unset( $ub[ 'utility_buttons' ][ 'view_attachments' ] );
				}
			}
			
			if( isset( $this->basic_data['more_actions'] ) && ! empty( $this->basic_data['more_actions'] ) ){
				$ub[ 'more_actions' ] = $this->basic_data["more_actions"];
			}
			
			if( ! empty( $ub ) ){
				$this->class_settings[ 'data' ] = $ub;
				$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
				$this->class_settings[ 'data' ][ 'records_table' ] = $this->table_name;
				
				if( isset( $this->class_settings['access_role_table'] ) && $this->class_settings['access_role_table'] ){
					$this->class_settings[ 'data' ][ 'table' ] = $this->class_settings['access_role_table'];
				}

				if( isset( $this->class_settings['more_data'] ) && $this->class_settings['more_data'] ){
					$this->class_settings[ 'data' ][ 'more_data' ] = $this->class_settings['more_data'];
				}
				
				if( isset( $this->plugin ) && $this->plugin ){
					$this->class_settings[ 'data' ]['plugin'] = $this->plugin;
				}
				
				$this->class_settings[ 'data' ][ 'development' ] = get_hyella_development_mode();
				
				if( isset( $this->class_settings["html_replacement_selector"] ) ){
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings["html_replacement_selector"];
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
				$html = $this->_get_html_view();
			}
			
			if( isset( $o['return_html'] ) && $o['return_html'] ){
				return $html;
			}
			
			if( isset( $this->class_settings['custom_edit_button'] ) ){
				$html .= $this->class_settings['custom_edit_button'];
			}
			$this->datatable_settings['custom_edit_button'] = $html;
			
			if( $return ){
				return $this->datatable_settings;
			}
		}
		
		
		protected function _check_accounting_settings(){
			$rv = __map_financial_accounts();
			$problem_accounts = array();
			$accounts_not_found = array();
			$error_msg = '';
			$accounts_data = array();
			
			$accounts = $this->linked_accounts;
			
			foreach( $accounts as $k ){
				$title = strtoupper( str_replace( "_", " ", $k ) );
				
				if( isset( $rv[ $k ] ) && $rv[ $k ] ){
					$details = get_chart_of_accounts_details( array( "id" => $rv[ $k ] ) );
					if( ! ( isset( $details[ "id" ] ) && $details[ "id" ] ) ){
						$problem_accounts[] = $title;
					}else{
						
						$accounts_data[ $k ] = $details;
						
					}
				}else{
					$accounts_not_found[] = $title;
				}
			}
			
			$acc_types = get_types_of_account();
			
			foreach( $accounts as $k ){
				$check = '';
				$title = strtoupper( str_replace( "_", " ", $k ) );
				
				switch( $k ){
				case "payroll_net_pay":
					$check = 'current_liabilities';
					$check = 'liabilities';
				break;
				case "salary_expense_account":
					$check = 'operating_expense';
				break;
				}
				
				if( $check ){
					$k1 = $check;
					if( ! ( isset( $accounts_data[ $k ] ) && isset( $accounts_data[ $k1 ] ) && $accounts_data[ $k ]["type"] == $accounts_data[ $k1 ][ "type" ] ) ){
						$ap1 = isset( $accounts_data[ $k1 ] )?$accounts_data[ $k1 ][ "type" ]:'';
						$atype = isset( $acc_types[ $ap1 ] ) ? $acc_types[ $ap1 ] : 'N/A';
						$problem_accounts[] = 'Set the account type of <strong>'.$title.'</strong> to equal <strong>'. $atype .'</strong>';
					}
				}
			}
			
			if( ! empty( $problem_accounts ) ){
				$error_msg = '<p>The following accounts are not properly configured in your chart of accounts</p>';
				$error_msg .= '<p><ol><li>'. implode( "</li><li>", $problem_accounts ).'</li></ol></p>';
			}
			
			if( ! empty( $accounts_not_found ) ){
				$error_msg = '<p>The following accounts do not exist in your license file</p>';
				$error_msg .= '<p><ol><li>'. implode( "</li><li>", $accounts_not_found ).'</li></ol></p>';
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Chart of Account Settings</h4><p>Please contact your support team or configure your chart of accounts properly</p><p>or You can disabled the settings <i>DO NOT POST PAY ROLL EXPENSE</i> in the GENERAL SETTINGS MENU to bypass this check</p><br />' . $error_msg;
				return $err->error();
			}
		}
		
		//BASED METHODS
		protected function _refresh_cache(){
			//empty permanent cache folder
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			
			//if( isset( $_POST["id"] ) && $_POST["id"] ){
			//file_put_contents( 'aaa.txt', json_encode( $_POST ) );
			
			if( isset( $_POST["ids2"] ) && $_POST["ids2"] ){
				//$this->class_settings[ 'current_record_id' ] = $_POST["id"];
				
				$this->class_settings[ 'current_record_id' ] = 'pass_condition';
				//if( isset( $_POST["ids2"] ) && $_POST["ids2"] ){
					$this->class_settings[ 'cache_where' ] = " AND `id` IN ( " . $_POST["ids2"] . ") ";
				//}
				
			}else{
				
				if( ! ( isset( $this->class_settings["refresh_continue"] ) && $this->class_settings["refresh_continue"] ) ){
					/*
					clear_cache_for_special_values_directory( array(
						"permanent" => true,
						"directory_name" => $this->table_name,
					) );
					*/
					$settings = array(
						'cache_key' => $this->table_name . "-list",
						'directory_name' => $this->table_name,
						'permanent' => true,
					);
					//clear_cache_for_special_values( $settings );
				}
				
				$this->class_settings[ 'current_record_id' ] = 'pass_condition';
			}
			
			//$this->class_settings[ 'cache_where' ] = " AND `serial_num` > 18354 ";
			
			$this->_get_record();
			
			$err = new cError('010011');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Cache Successfully Refreshed</h4>';
			return $err->error();
		}
		
		public function _save_line_items(){
			
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$msg = '<h4>Read Only Mode Active</h4>You are not allowed to modify these records';
				return $this->_display_notification( array( "type" => "error", "message" => $msg ) );
			}
			
			if( ! ( isset( $this->class_settings["line_items"] ) && is_array( $this->class_settings["line_items"] ) ) ){
				return 0;
			}
			
			$reference = '';
			$reference_table = '';
				
			if( ( isset( $this->class_settings[ 'reference' ] ) && $this->class_settings[ 'reference' ] && isset( $this->class_settings[ 'reference_table' ] ) && $this->class_settings[ 'reference_table' ] ) ){
				
				$reference = $this->class_settings[ 'reference' ];
				$reference_table = $this->class_settings[ 'reference_table' ];
				
			}
			
			
			if( isset( $this->class_settings[ "clear_ids" ] ) && is_array( $this->class_settings[ "clear_ids" ] ) && ! empty( $this->class_settings[ "clear_ids" ] ) ){
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( ". implode( ",", $this->class_settings[ "clear_ids" ] ) ." ) ";	//LIMIT 1 
				
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
			
			if( isset( $this->class_settings[ "clear_reference" ] ) && $this->class_settings[ "clear_reference" ] ){
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `".$this->table_fields["reference"]."` = '". $this->class_settings[ "clear_reference" ] ."' AND `".$this->table_fields["reference_table"]."` = '". $this->class_settings[ "reference_table" ] ."' ";	//LIMIT 1 
				
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
			
			if( isset( $this->class_settings[ "clear_child_reference" ] ) && $this->class_settings[ "clear_child_reference" ] && isset( $this->class_settings[ "child_reference_table" ] ) ){
				$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `".$this->table_fields["child_reference"]."` = '". $this->class_settings[ "clear_child_reference" ] ."' AND `".$this->table_fields["child_reference_table"]."` = '". $this->class_settings[ "child_reference_table" ] ."' ";	//LIMIT 1 
				
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
			
			$array_of_dataset = array();
			$array_of_dataset_update = array();
			$array_of_update_conditions = array();
			
			
			$new_record_id = get_new_id();
			$new_record_id_serial = 0;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			foreach( $this->class_settings["line_items"] as $k => $v ){
				
				if( $reference ){
					$v["reference"] = $reference;
					$v["reference_table"] = $reference_table;
				}
				
				
				$id = $new_record_id . 'W' . ++$new_record_id_serial;
				if( isset( $v["new_id"] ) && $v["new_id"] ){
					$id = $v["new_id"];
				}
				
				$dataset_to_be_inserted = array(
					'id' => $id,
					'created_role' => $this->class_settings[ 'priv_id' ],
					'created_by' => $this->class_settings[ 'user_id' ],
					'creation_date' => $date,
					'modified_by' => $this->class_settings[ 'user_id' ],
					'modification_date' => $date,
					'ip_address' => $ip_address,
					'record_status' => 1,
					'serial_num' => '',
					// 'serial_num' => '0',
				);
				
				foreach( $v as $kv => $v1 ){
					if( isset( $this->table_fields[ $kv ] ) ){
						$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $v1;
					}
				}
				
				if( isset( $v["created_source"] ) && $v["created_source"] ){
					$dataset_to_be_inserted["created_source"] = $v["created_source"];
				}
				
				if( isset( $v["created_by"] ) && $v["created_by"] ){
					$dataset_to_be_inserted["created_by"] = $v["created_by"];
				}
				
				if( isset( $v["modified_by"] ) && $v["modified_by"] ){
					$dataset_to_be_inserted["modified_by"] = $v["modified_by"];
				}
				
				//@nw4
				if( isset( $v["modified_source"] ) && $v["modified_source"] ){
					$dataset_to_be_inserted["modified_source"] = $v["modified_source"];
				}
				
				if( isset( $v[ "update_key" ] ) && $v[ "update_key" ] && isset( $v[ "update_value" ] ) && $v[ "update_value" ] ){
					
					//update
					unset( $dataset_to_be_inserted[ 'id' ] );
					unset( $dataset_to_be_inserted[ 'created_by' ] );
					unset( $dataset_to_be_inserted[ 'creation_date' ] );
					unset( $dataset_to_be_inserted[ 'creator_role' ] );
					
					$update_conditions_to_be_inserted = array(
						'where_fields' => $v[ "update_key" ],
						'where_values' => $v[ "update_value" ],
					);

					$array_of_dataset_update[] = $dataset_to_be_inserted;

					$array_of_update_conditions[] = $update_conditions_to_be_inserted;
					
				}else{
					//new
					$array_of_dataset[] = $dataset_to_be_inserted;
				}
				
			}
			
			//print_r($array_of_dataset); exit;
			$saved = 0;
			$test_save = 0;
			
			$select_key = "creation_date";
			
			if( ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset_update,
				);
				
				$function_settings[ 'update_conditions' ] = $array_of_update_conditions;

				if( isset( $this->class_settings[ 'use_replace' ] ) )$function_settings[ 'use_replace' ] = $this->class_settings[ 'use_replace' ];
				
				$returned_data = insert_new_record_into_table( $function_settings );
				
				$select_key = "modification_date";
				$test_save = 1;
			}
			
			if( ! empty( $array_of_dataset ) ){
				
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset,
				);

				if( isset( $this->class_settings[ 'use_replace' ] ) )$function_settings[ 'use_replace' ] = $this->class_settings[ 'use_replace' ];
				
				$returned_data = insert_new_record_into_table( $function_settings );
				$test_save = 1;
			}
			
			if( $test_save ){
				
				if( isset( $this->table_fields["reference"] ) && $reference ){
					$where = " AND `".$this->table_fields["reference"]."` = '".$reference."' AND `".$this->table_fields["reference_table"]."` = '".$reference_table."' ";
				}else{
					$where = " AND `".$select_key."` = '".$date."' ";
				}
				
				
				$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ".$where;	//LIMIT 1 
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				
				$bills = execute_sql_query($query_settings);
				
				if( isset( $bills[0]["id"] ) ){
					$saved = 1;
					$this->class_settings[ 'created_records' ] = $bills;
					
					if( isset( $this->refresh_cache_after_save_line_items ) && $this->refresh_cache_after_save_line_items ){
						$this->class_settings[ 'current_record_id' ] = 'pass_condition';
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						$this->class_settings[ 'cache_where' ] = $where;
						$this->_get_record();
					}
				}
			}
			
			return $saved;
		}
		
		protected function _send_email(){
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
			$email->class_settings[ 'destination' ] = array();
			
			if( isset( $this->class_settings[ 'destination_bcc_emails' ] ) && $this->class_settings[ 'destination_bcc_emails' ] ){
				$email->class_settings[ 'destination' ]['bcc_emails'] = $this->class_settings[ 'destination_bcc_emails' ];
				
				unset( $this->class_settings[ 'destination_bcc_emails' ] );
			}
			
			if( isset( $this->class_settings[ 'destination_cc_emails' ] ) && $this->class_settings[ 'destination_cc_emails' ] ){
				$email->class_settings[ 'destination' ]['cc_emails'] = $this->class_settings[ 'destination_cc_emails' ];
				
				unset( $this->class_settings[ 'destination_cc_emails' ] );
			}
			
			
			if( isset( $this->class_settings[ 'destination_email' ] ) && $this->class_settings[ 'destination_email' ] ){
				$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'destination_email' ];
				$email->class_settings[ 'destination' ]['full_name'][] = isset( $this->class_settings[ 'destination_full_name' ] )?$this->class_settings[ 'destination_full_name' ]:$this->class_settings[ 'destination_email' ];
				$email->class_settings[ 'destination' ]['id'][] = isset( $this->class_settings[ 'destination_id' ] )?$this->class_settings[ 'destination_id' ]:$this->class_settings[ 'destination_email' ];
				
				unset( $this->class_settings[ 'destination_email' ] );
			}
			
			if( isset( $this->class_settings[ 'copy_current_user' ] ) && $this->class_settings[ 'copy_current_user' ] ){
				
				$email->class_settings[ 'destination' ]['email'][] = $this->class_settings[ 'user_email' ];
				$email->class_settings[ 'destination' ]['full_name'][] = $this->class_settings[ 'user_full_name' ];
				$email->class_settings[ 'destination' ]['id'][] = $this->class_settings[ 'user_id' ];
				
				unset( $this->class_settings[ 'copy_current_user' ] );
			}
			
			return $email->emails();
		}
		
		/*
		HOW IT WORKS
		PASS 2 INPUTS
			$e = array(
				"content" => 'HTML BODY OF THE EMAIL',
				"subject" => '',
				"record_id" => 'Email Recipient Record ID',
				"record_table" => 'customers_table',
				"email_field" => '',
				
				"single_email" => '',
				"single_name" => '',
				
				"multiple_emails" => '',
				"sending_option" => '',
				"attachments" => array(),
				"system_notification" => array(),
				
			);
		*/
		
		protected function _send_email_notification( $e = array(), $option = array() ){
			
			$pr = get_project_data();
			
			$pass = 0;
			$failed = 0;
			$success_msg = '';
			$response = '';
			$message_type = 99;
			
			$sending_option = isset( $e["sending_option"] )?$e["sending_option"]:'bcc';
			
			if( isset( $option["message_type"] ) && $option["message_type"] ){
				$message_type = $option["message_type"];
			}
			
			if( isset( $e["content"] ) && isset( $e["subject"] ) && $e["content"] ){
				
				$system_notification_response = '';
				if( isset( $e["system_notification"] ) && is_array( $e["system_notification"] ) && ! empty( $e["system_notification"] ) ){
					if( class_exists("cNotifications") ){
						$not = new cNotifications();
						$not->class_settings = $this->class_settings;
						
						$not->class_settings["notification_data"] = array(
							"recipients" => $e["system_notification"],
							"title" => $e["subject"],
							"detailed_message" => $e["content"],
							"send_email" => 0,
							"notification_type" => 0,
							"class_name" => $this->table_name,
							"data" => isset( $e["data"] )?$e["data"]:array(),
							"trigger" => isset( $e["trigger"] )?$e["trigger"]:'',
							"method_name" => $this->class_settings["action_to_perform"],
						);
						
						$not->class_settings["action_to_perform"] = "add_notification";
						if( $not->notifications() ){
							$system_notification_response = 'System Notifications Sent.<br />';
						}
					}
				}
				
				$d = array();
				if( ! ( isset( $option[ 'skip_email_not' ] ) && $option[ 'skip_email_not' ] ) ){
					if( isset( $e["record_id"] ) && $e["record_id"] && isset( $e["record_table"] ) && isset( $e["email_field"] ) ){
						
						$c = get_record_details( array( "id" => $e["record_id"], "table" => $e["record_table"] ) );
						
						if( ! ( isset( $c[ $e["email_field"] ] ) && $c[ $e["email_field"] ] ) ){
							$tb = $e["record_table"];
							$ctb = "c" . ucwords( strtolower( $tb ) );
							if( class_exists( $ctb ) ){
								$cls = new $ctb();
								$ctb->class_settings = $this->class_settings;
								$ctb->class_settings["action_to_perform"] = "get_record";
								$ctb->class_settings[ 'current_record_id' ] = $e["record_id"];
								$c = $ctb->$tb();
							}
						}
						
						if( isset( $c[ $e["email_field"] ] ) && $c[ $e["email_field"] ] ){
							$d["name"] = get_name_of_referenced_record( array( "id" => $e["record_id"], "table" => $e["record_table"] ) );
							$d["email"] = $c[ $e["email_field"] ];
						}
						
					}
					
					if( ! ( isset( $d["email"] ) && $d["email"] ) ){
						$d["email"] = isset( $e["single_email"] )?$e["single_email"]:'';
						$d["name"] = ( isset( $e["single_name"] ) && $e["single_name"] )?$e["single_name"]:$d["email"];
					}
					
					if( isset( $e["multiple_emails"] ) && $e["multiple_emails"] ){
						$pass = 1;
					}
					
					if( ( isset( $d["email"] ) && $d["email"] && filter_var( $d["email"], FILTER_VALIDATE_EMAIL ) ) || $pass ){
						
						if( $pass ){
							$d["name"] = '';
						}
						$d1["full_name"] = $d["name"];
						$d1["title"] = $e["subject"];
						$d1["html"] = $e["content"];
						
						if( isset( $e["info"] ) && $e["info"] ){
							$d1["info"] = $e["info"];
						}
						$this->class_settings[ 'data' ] = $d1;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/email-message.php' );
						$this->class_settings['message'] = $this->_get_html_view();
						
						$this->class_settings['subject'] = strtoupper( $pr["company_name"] ) . ': ' . $d1["title"];
						
						if( isset( $e["attachments"] ) && is_array( $e["attachments"] ) && ! empty( $e["attachments"] ) ){
							$this->class_settings['attachment'] = $e["attachments"];
						}
						
						$this->class_settings['message_type'] = $message_type;
						$this->class_settings['direct_message'] = 1;
						
						if( isset( $d["email"] ) && $d["email"] ){
							$this->class_settings[ 'destination_full_name' ] = isset( $d["name"] )?$d["name"]:$d["email"];
							$this->class_settings[ 'destination_id' ] = $d["email"];
							$this->class_settings[ 'destination_email' ] = $d["email"];
						}
						
						if( isset( $e["multiple_emails"] ) && $e["multiple_emails"] ){
							$skey = 'bcc_emails';
							if( $sending_option == 'cc' ){
								$skey = 'cc_emails';
							}
							$this->class_settings[ 'destination_' . $skey ] = $e["multiple_emails"];
						}
						
						$status = $this->_send_email();
						$response .= ( isset( $status["response"] )?$status["response"]:'' );
						if( isset( $status["status"] ) && $status["status"] ){
							$success_msg .= '<br />' . $d["name"] .': '. $status["response"];
						}else{
							++$failed;
						}
					}else{
						$response = 'Invalid Recipient Email Address';
					}
				}
				
			}else{
				$response = 'No Email Message Found';
			}
			
			return array(
				"response" => $response,
				"success" => $success_msg,
			);
		}
		
		public function _notify_people( $options = array() ){
			$ids = array();
			$q1 = 0;
			
			if( isset( $options["content"] ) && $options["content"] && isset( $options["subject"] ) && $options["subject"] ){
				
				$class = isset( $options[ 'user_class' ] ) ? $options[ 'user_class' ] : 'users';
				$plugin = isset( $options[ 'user_plugin' ] ) ? $options[ 'user_plugin' ] : '';

				if( $plugin ){
					$nwp = 'c' . ucwords( $plugin );
					if( class_exists( $nwp ) ){
						$nwp = new $nwp();
						$nwp->class_settings = $this->class_settings;
						$nwp->load_class( array( 'class' => array( $class ) ) );
					}
				}

				$class = 'c'.$class;

				if( class_exists( $class ) ){
					$users = new $class();
					$users->class_settings = $this->class_settings;
					
					if( isset( $options["specific_users"] ) && is_array( $options["specific_users"] ) && ! empty( $options["specific_users"] )  ){
						
						$users->class_settings["specific_users"] = $options["specific_users"];
						$q1 = 1;
					}else if( ( isset( $options["capability"] ) && $options["capability"] ) || ( isset( $options["capabilities"] ) && is_array( $options["capabilities"] ) && ! empty( $options["capabilities"] ) ) ){
						
						$users->class_settings["capability"] = isset( $options["capability"] )?$options["capability"]:'';
						$users->class_settings["capabilities"] = isset( $options["capabilities"] )?$options["capabilities"]:array();
						
						$users->class_settings["exclude_capabilities"] = isset( $options["exclude_capabilities"] )?$options["exclude_capabilities"]:array();
						
						$users->class_settings["users_filter"] = isset( $options["users_filter"] )?$options["users_filter"]:array();
						
						$users->class_settings["users_filter_condition"] = isset( $options["users_filter_condition"] )?$options["users_filter_condition"]:'';
						
						$users->class_settings["get_ids"] = 1;
						$q1 = 1;
					}
					
					
					if( $q1 ){
						$users->class_settings["action_to_perform"] = "get_email_of_users_based_on_capability";
						$ux = $users->users();
						
						$emails = array();
						$recipient_ids = array();
						if( is_array( $ux ) && ! empty( $ux ) ){
							foreach( $ux as $u_val ){
								$ids[ $u_val["id"] ] = $u_val["id"];
								$recipient_ids[] = $u_val["id"];
								
								$ck = 'email';
								if( isset( $u_val[ $ck ] ) && filter_var( $u_val[ $ck ], FILTER_VALIDATE_EMAIL ) ){
									$emails[] = $u_val[ $ck ];
								}
							}
						}
						// print_r( $emails );exit;
						
						if( ! empty( $recipient_ids ) ){
							$opt = array(
								"data" => isset( $options["data"] )?$options["data"]:array(),
								"content" => $options["content"],
								"subject" => strtoupper( $options["subject"] ),
								//"multiple_emails" => implode(",", $recipients_emails ),
								"sending_option" => 'bcc',
								"system_notification" => $recipient_ids,
								"trigger" => isset( $options["trigger"] )?$options["trigger"]:'',
							);
							
							if( ! empty( $emails ) && isset( $options["sending_option"] ) && $options["sending_option"] ){
								$opt['sending_option'] = $options["sending_option"];
								$opt['multiple_emails'] = implode(",", $emails );
							}
							
							$opt2 = array();
							if( isset( $options[ 'opt_params' ] ) && $options[ 'opt_params' ] ){
								$opt2 = $options[ 'opt_params' ];
							}

							$r = $this->_send_email_notification( $opt, $opt2 );
						}
					}
				}
			}
			
			return array(
				"people_notified" => $ids,
			);
		}
		
		public function _update_table_field(){
			$applicant = array();
			$return = array();
			//input: $this->class_settings["update_fields"]
			
			if( ! isset( $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$_POST[ "uid" ] = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:"system";
			$_POST[ "user_priv" ] = isset( $this->class_settings["user_privilege"] )?$this->class_settings["user_privilege"]:"system";
			$_POST[ "table" ] = $this->table_name;
			$_POST[ "processing" ] = md5(1);
			if( ! defined('SKIP_USE_OF_FORM_TOKEN') )
				define('SKIP_USE_OF_FORM_TOKEN', 1);
			
			$push = 0;
			if( isset( $this->class_settings["update_fields"] ) && is_array( $this->class_settings["update_fields"] ) ){
				foreach( $this->class_settings["update_fields"] as $key => $val ){
					if( isset( $this->table_fields[ $key ] ) ){
						$_POST[ $this->table_fields[ $key ] ] = $val;
						$push = 1;
					}
					/* else{
						switch( $key ){
						case 'serial_num':
						case 'id':
						case 'creator_role':
						case 'created_by':
						case 'creation_date':
						case 'modified_by':
						case 'modification_date':
						case 'ip_address':
						case 'device_id':
						case 'record_status':
							$_POST[ $key ] = $val;
							$push = 1;
						break;
						}
					} */
				}
			}
			
			if( $push ){
				unset( $this->class_settings[ 'form_action' ] );
				$return = $this->_save_changes();
			}
			
			return $return;
		}
		
		public function _get_html_view(){
			if( defined("NWP_FLAG_UTILITY") && NWP_FLAG_UTILITY ){
				if( isset( $this->class_settings[ 'data' ][ 'selected_record' ] ) && $this->class_settings[ 'data' ][ 'selected_record' ] && isset( $this->class_settings[ 'html' ] ) && is_array( $this->class_settings[ 'html' ] ) && in_array( 'html-files/templates-1/globals/custom-buttons.php', $this->class_settings[ 'html' ] ) ){
					$ox = array();
					if( isset( $this->datatable_settings[ 'utility_buttons' ][ 'comments' ] ) ){
					}
						$ox["show_comments"] = 1;
					if( isset( $this->datatable_settings[ 'utility_buttons' ][ 'view_attachments' ] ) ){
						$ox["show_files"] = 1;
					}
					if( ! empty( $ox ) ){
						$ox['source'] = 'utility_buttons';
						
						$ox['record']['reference_table'] = isset( $this->class_settings[ 'data' ][ 'table' ] )?$this->class_settings[ 'data' ][ 'table' ]:$this->table_name;
						
						$ox['record']['reference_table'] = isset( $this->class_settings[ 'data' ][ 'flag_reference_table' ] )?$this->class_settings[ 'data' ][ 'flag_reference_table' ]:$ox['record']['reference_table'];
						
						$ox['record']['reference'] = isset( $this->class_settings[ 'data' ][ 'flag_reference' ] )?$this->class_settings[ 'data' ][ 'flag_reference' ]:$this->class_settings[ 'data' ][ 'selected_record' ];
						
						$this->class_settings[ 'data' ][ 'uflag' ] = $this->_get_flags( $ox );
					}
				}
			}
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			if( isset( $this->plugin_hooks ) && ! empty( $this->plugin_hooks ) ){
				$script_compiler->class_settings[ 'plugin_hooks' ] = $this->plugin_hooks;
			}
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			$return = $script_compiler->script_compiler();
			
			if( isset( $script_compiler->class_settings["returned_data"] ) && $script_compiler->class_settings["returned_data"] ){
				$this->class_settings["returned_data"] = $script_compiler->class_settings["returned_data"];
			}
			
			return $return;
		}
		
		protected function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			if( ! isset( $this->class_settings['form_heading_title'] ) ){
				$this->class_settings['form_heading_title'] = 'New ' . ucwords( $this->label );
			}

			if( ! ( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ) ){
				if( ! ( isset( $this->ignore_default_country ) && $this->ignore_default_country ) ){
					if( isset( $this->table_fields[ 'country' ] ) && defined( 'HYELLA_DEFAULT_COUNTRY' ) && HYELLA_DEFAULT_COUNTRY ){
						$this->class_settings[ 'form_values_important' ][ $this->table_fields[ 'country' ] ] = HYELLA_DEFAULT_COUNTRY;
					}
				}
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify ' . ucwords( $this->label );
			break;
			}
			
			if( isset( $this->use_custom_fields ) && isset( $this->custom_fields ) && $this->use_custom_fields ){
				if( isset( $this->table_fields["data"] ) ){
					$this->class_settings["custom_fields_data"] = $this->table_fields["data"];
				}
				$this->class_settings["custom_fields"] = $this->custom_fields;
			}
			if( isset( $this->class_settings["custom_fields"] ) && is_array( $this->class_settings["custom_fields"] ) && ! empty( $this->class_settings["custom_fields"] ) ){
				$f1 = array_keys( $this->class_settings["custom_fields"] );
				if( ! empty( $f1 ) ){
					$f2 = array();
					
					foreach( $f1 as $f1v ){
						if( isset( $this->class_settings["custom_fields"][ $f1v ] ) ){
							$f2[ $f1v ] = $this->class_settings["custom_fields"][ $f1v ];
						}
					}
					unset( $this->class_settings["custom_fields"] );
					
					$this->class_settings["attributes"]["custom_form_fields"] = array(
						"field_ids" => $f1,
						"form_label" => $f2,
					);
				}
			}
			
			if( ! isset( $this->class_settings['form_submit_button'] ) )
				$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			if( ! isset( $this->class_settings['form_class'] ) )
				$this->class_settings['form_class'] = 'activate-ajax';
			
			$GLOBALS["table_fields"] = $this->table_fields;
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$process_handler->class_settings[ 'db_table' ] = $this->db_table_name;
			}
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			if( isset( $this->plugin ) && $this->plugin ){
				$process_handler->class_settings[ 'plugin' ] = ( isset($this->redirect_plugin) ? $this->redirect_plugin : $this->plugin );
			}
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			// print_r( $returning_html_data );exit();
			if( isset( $this->class_settings['return_new_format'] ) && $this->class_settings['return_new_format'] ){
				return array(
					'html_replacement_selector' => "#form-content-area",
					'html_replacement' => $returning_html_data[ 'html' ],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'message' => 'Returned form data capture form',
					'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
					'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
				);
			}else{
				return array(
					'html' => $returning_html_data[ 'html' ],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'display-data-capture-form',
					'message' => 'Returned form data capture form',
					'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
				);				
			}
		}

		protected function _delete_records(){
			$returning_html_data = array();
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
					// print_r( $returning_html_data );exit;
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records'; 
			
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){

				if( isset( $this->merge_table ) ){

					$opt = $this->merge_table;
					$opt[ 'action' ] = 'delete';
					$opt[ 'record' ][ 'delete_id' ] = $returning_html_data['deleted_record_id'];

					// print_r( $this->_merge_tables( $opt ) );exit;
					$this->_merge_tables( $opt );
				}

				$cache_key = $this->table_name;
				
				$ids = explode( ":::", $returning_html_data['deleted_record_id'] );
				$settings = array(
					'cache_key' => $cache_key . "-list",
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$list = get_cache_for_special_values( $settings );
				
				foreach( $ids as $id ){
					if( isset( $list[ $id ] ) )unset( $list[ $id ] );
				}
				
				$settings = array(
					'cache_key' => $cache_key . "-list",
					'cache_values' => $list,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				switch ( $this->class_settings['action_to_perform'] ){
				case 'delete_from_popup':
					$returning_html_data["status"] = "new-status";
					unset( $returning_html_data["html"] );
					$returning_html_data["javascript_functions"] = array( "$('#modal-popup-close').click" );
				break;
				case 'delete_from_popup2':
					$returning_html_data["status"] = "new-status";
					unset( $returning_html_data["html"] );
					
					if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
						$returning_html_data["html_replacement_selector"] = "#" . $this->class_settings[ 'html_replacement_selector' ];
						$returning_html_data["html_replacement"] = "&nbsp";
					}
				break;
				}
				
				$d = array();
				$d1 = $ids;
				foreach( $d1 as $dd ){
					if( ! $dd )continue;
					
					$d[] = " '" . $dd . "' ";
					
					$ggsettings = array(
						'cache_key' => $cache_key . "-" . $dd,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$returning_html_data[ "deleted_records" ][ $dd ] = get_cache_for_special_values( $ggsettings );
					clear_cache_for_special_values( $ggsettings );
				}
				
				if( isset( $this->table_name_sub ) && $this->table_name_sub && $this->table_name_sub != $this->table_name ){
					$table = $this->table_name_sub;
					$actual_name_of_class = 'c'.ucwords( $table );

					$pl = isset( $this->plugin ) ? $this->plugin : '';

					if( $pl ){
						$nwp = 'c' . ucwords( $pl );
						if( class_exists( $nwp ) ){
							$nwp = new $nwp();
							$nwp->class_settings = $this->class_settings;
							$nwp->load_class( array( 'class' => array( $table ) ) );
						}
					}

					if( class_exists( $actual_name_of_class ) ){
						$module = new $actual_name_of_class();
						$module->class_settings = $this->class_settings;
						$module->class_settings["action_to_perform"] = "delete_with_query";
						
						if( $d ){
							$module->class_settings["where"] = " `".$module->table_name."`.`".$module->table_fields[ $module->default_reference ]."` IN (" . implode( ',', $d ) . ") ";
							$module->$table();
						}
					}
				}
				
				if( isset( $this->delete_linked_transaction ) && $this->delete_linked_transaction ){
					switch ( $action_to_perform ){
					case 'delete':
						$table = 'transactions';
						$_POST["mod"] = 'delete-' . md5( $table );
						
						$actual_name_of_class = 'c'.ucwords( $table );
						$module = new $actual_name_of_class();
						$module->class_settings = $this->class_settings;
						$module->class_settings["action_to_perform"] = 'delete_only';
						$module->$table();
					break;
					}
				}
				
			}
			
			if( method_exists( $this, "__after_delete_and_refresh" ) ){
				$this->__after_delete_and_refresh( $returning_html_data );
			}
			
			return $returning_html_data;
		}
		
		protected function _display_data_table(){
			$action_to_perform = $this->class_settings["action_to_perform"];
			$reset_filters = 1;
			
			$tb = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$tb = $this->db_table_name;
				$this->datatable_settings['db_table'] = $tb;
			}
			
			switch( $action_to_perform ){
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
			break;
			case 'display_deleted_records':
			case 'display_all_deleted_records':
				$this->class_settings[ 'filter' ]['show_deleted_records'] = 1;
				
				if( isset( $this->class_settings[ 'base_call' ] ) ){
					$this->datatable_settings['show_restore_button']['no_plugin'] = 1;
					$this->datatable_settings['show_restore_button']['function-class'] = $this->table_name_static;
					$this->datatable_settings['show_restore_button']['function-name'] = 'restore&source=' . $this->table_name;
				}else{
					$this->datatable_settings['show_restore_button'] = 1;
				}
			break;
			case 'split_datatable':
				$reset_filters = 0;
				//$this->datatable_settings['split_screen'] = array( 'checked' => ' checked="checked" ' );
				//$this->datatable_settings['split_screen'] = array();
				
				if( isset( $_SESSION[ $this->table_name ][ 'filter' ]['settings'] ) ){
					$this->datatable_settings = $_SESSION[ $this->table_name ][ 'filter' ]['settings'];
				}
				
			break;
			default:
				$this->datatable_settings['datatable_method'] = $action_to_perform;
			break;
			}
			
			if( isset( $this->table_package ) && $this->table_package ){
				$this->datatable_settings[ 'table_data' ]['table_package'] = $this->table_package;
			}
			
			if( !( isset( $this->class_settings['do_not_set_plugin'] ) && $this->class_settings['do_not_set_plugin'] ) && isset( $this->plugin ) && $this->plugin ){
				$this->datatable_settings['plugin'] = ( isset( $this->redirect_plugin ) ? $this->redirect_plugin : $this->plugin );
			}
				// print_r( $this->datatable_settings['plugin'] );exit;
			
			if( isset( $this->class_settings[ "datatable_split_screen" ] ) && $this->class_settings[ "datatable_split_screen" ] ){
				$this->datatable_settings['datatable_split_screen'] = $this->class_settings[ "datatable_split_screen" ];
				
				if( isset( $this->datatable_settings['datatable_split_screen']['col'] ) && intval( $this->datatable_settings['datatable_split_screen']['col'] ) ){
					$this->datatable_settings['split_screen']['checked'] = ' checked="checked" ';
				}else{
					$this->datatable_settings['split_screen']['checked'] = '';
				}
			}
			
			
			if( $reset_filters ){
				//GET ALL FIELDS IN TABLE
				unset( $_SESSION[ $this->table_name ][ 'filter' ] );
				//unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
				
				$this->class_settings[ 'filter' ]['settings'] = $this->datatable_settings;
				
				if( isset( $this->class_settings[ 'filter' ] ) && $this->class_settings[ 'filter' ] ){
					$_SESSION[ $this->table_name ][ 'filter' ] = $this->class_settings[ 'filter' ];
				}
			}
			
			$query = '';
			$fields = array();

			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$tb."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $tb ),

				//MongoDB
				'table' => $this->table_name,
				'where' => array( 
						'table_fields' => 'table_fields',
					),
				'select' => array( 
					'projection' => array( 
						'_id' => 0,
						'table_fields' => 0,
							),
					),
			);
			$result = execute_sql_query($query_settings);
			//echo $query; exit;
			if( empty( $result ) && DB_MODE == 'mongoDB' ){
				foreach( $this->table_fields as $key => $value ){
					$result[][] = $value;
				}
				$result[][] = 'serial_num';
				$result[][] = 'id';
				$result[][] = 'creator_role';
				$result[][] = 'created_by';
				$result[][] = 'creation_date';
				$result[][] = 'modified_by';
				$result[][] = 'modification_date';
				$result[][] = 'ip_address';
				$result[][] = 'device_id';
				$result[][] = 'record_status';
			}

			// print_r( $result );exit;
			if( $result && is_array( $result ) && ! empty( $result ) ){
				$array2 = array( 0 => '1', 'Field' => '1' );
				foreach( $result as $sval ){
					$fields[] = array_intersect_key( $sval , $array2 );
				}
			}

			if( empty( $fields ) ){
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cregistered_users.php';
				$err->method_in_class_that_triggered_error = '_save';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 246';
				return $err->error();
			}
			
			if( isset( $this->set_datatable_access_control ) && $this->set_datatable_access_control ){
				$this->_set_datatable_access_control();
			}
			
			$this->datatable_settings[ 'table_data' ][ 'fields' ] = $fields;
			if( isset( $_SESSION[ $this->table_name ] ) ){
				$this->datatable_settings[ 'table_data' ][ 'session' ] = $_SESSION[ $this->table_name ];
			}else{
				$this->datatable_settings[ 'table_data' ][ 'session' ] = array();
			}
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = $this->class_settings['current_module'];
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		protected function _save_changes(){
			$handle = isset( $this->class_settings[ 'html_replacement_selector' ] ) ? $this->class_settings[ 'html_replacement_selector' ] : '';
			$do_not_display = isset( $_GET[ 'do_not_display' ] ) ? $_GET[ 'do_not_display' ] : '';
			$returning_html_data = array();
			
			$this->class_settings["action_called"] = $this->class_settings[ 'action_to_perform' ];
			
			if( method_exists( $this, "__before_save_changes" ) ){
				$r = $this->__before_save_changes();
				
				if( isset( $r ) && isset( $r["status"] ) && $r["status"] == "new-status" ){
					return $r;
				}
			}

			$r = $this->_before_save_prevent_duplicates();
			if( isset( $r ) && ! empty( $r ) && is_array( $r ) ){
				return $r;
			}
			
			if( isset( $this->use_custom_fields ) && isset( $_POST["extra_fields"] ) && $_POST["extra_fields"] && $this->use_custom_fields ){
				$ef = json_decode( $_POST["extra_fields"], true );
				if( isset( $ef["custom_fields"] ) && is_array( $ef["custom_fields"] ) && ! empty( $ef["custom_fields"] ) ){
					$d = $ef["custom_fields"];
					
					$ed = array();
					if( isset( $_POST[ $this->table_fields["data"] ] ) ){
						$ed = json_decode( $_POST[ $this->table_fields["data"] ], true );
					}else if( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ){
						$this->class_settings["current_record_id"] = $_POST[ "id" ];
						$e1 = $this->_get_record();
						if( isset( $e1["data"] ) && $e1["data"] ){
							$ed = json_decode( $e1["data"], true );
						}
					}
					
					foreach( $d as $dv ){
						if( isset( $_POST[ $dv ] ) ){
							$vd = $_POST[ $dv ];
							if( is_array( $_POST[ $dv ] ) ){
								$vd = implode(":::", $_POST[ $dv ] );
							}
							$ed["custom_fields"][ $dv ] = $vd; 
						}
					}
					
					$_POST[ $this->table_fields["data"] ] = json_encode( $ed );
					unset( $ed );
				}
			}
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$process_handler->class_settings[ 'db_table' ] = $this->db_table_name;
			}
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';

			$GLOBALS[ 'table_fields' ] = $this->table_fields;
			if( isset( $this->class_settings[ 'table_fields' ] ) && $this->class_settings[ 'table_fields' ] ){
				$GLOBALS[ 'table_fields' ] = $this->class_settings[ 'table_fields' ];
			}
			
			$returning_html_data = $process_handler->process_handler();
			
			if( isset( $process_handler->class_settings['more_form_data'] ) ){
				$this->class_settings['more_form_data'] = $process_handler->class_settings['more_form_data'];
			}
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					
					if( method_exists( $this, "__after_save_changes" ) ){
						$r = $this->__after_save_changes( $returning_html_data );
					}
					
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$returning_html_data['record'] = $this->_get_record();
					
					if( method_exists( $this, "__after_save_changes_and_refresh" ) ){
						$r = $this->__after_save_changes_and_refresh( $returning_html_data );
					}
					
					if( isset( $_GET[ 'empty_container' ] ) && $_GET[ 'empty_container' ] ){
						$returning_html_data[ 'html_replacement_selector_two' ] = '#' . $_GET[ 'empty_container' ];
						$returning_html_data[ 'html_replacement_two' ] = '&nbsp;';
						
						if( isset( $r ) ){
							$r[ 'html_replacement_selector_two' ] = '#' . $_GET[ 'empty_container' ];
							$r[ 'html_replacement_two' ] = '&nbsp;';
						}
					}
					
					if( isset( $r ) && isset( $r["status"] ) && $r["status"] == "new-status" ){
						return $r;
					}
				}

				if( $handle && $do_not_display ){
					$returning_html_data["html_replacement_selector"] = '#'.$handle;
					$returning_html_data["html_replacement"] = $returning_html_data["html"];
					$returning_html_data["status"] = "new-status";
					
				// print_r( $returning_html_data );exit;
					unset( $returning_html_data["msg"] );
					unset( $returning_html_data["typ"] );
					
				}
			}
			
			return $returning_html_data;
		}
		
		protected function _before_save_prevent_duplicates(){
			
			$id = ( isset( $_POST["id"] ) && $_POST["id"] )?$_POST["id"]:'';
		
			if( isset( $this->prevent_duplicate["fields"] ) && ! empty( $this->prevent_duplicate["fields"] ) ){
				
				$dup_where = array();
				$dup_select = array();
				$dup_selected = array();
				$dup_val = array();
				$dup_details = isset( $this->prevent_duplicate["details"] )?$this->prevent_duplicate["details"]:'';
				
				foreach( $this->prevent_duplicate["fields"] as $v ){
					
					if( isset( $this->table_fields[ $v ] ) ){
						$val = '';
						if( isset( $_POST[ $this->table_fields[ $v ] ] ) ){
							$val = trim( strtolower( $_POST[ $this->table_fields[ $v ] ] ) );
						}else{
							if( isset( $_POST[ $v ] ) ){
								$val = trim( strtolower( $_POST[ $v ] ) );
							}
						}
						
						$fs = isset( $this->prevent_duplicate["field_settings"][ $v ] )?$this->prevent_duplicate["field_settings"][ $v ]:array();
						$skip_if_empty = isset( $fs['skip_if_empty'] )?$fs['skip_if_empty']:0;
						
						if( ! $val || ( ! $val && $skip_if_empty ) ){
							continue;
						}
						
						$dup_val[ $v ] = $val;
						$dup_selected[ $v ] = $this->table_fields[ $v ];
						$dup_select[] = " `".$this->table_name."`.`".$this->table_fields[ $v ]."` as '". $v ."' ";
						
						$dup_where[] = " TRIM( LOWER( `".$this->table_name."`.`".$this->table_fields[ $v ]."` ) ) = '". addslashes( $val ) ."' ";
					}
					
				}
				// print_r( $dup_where );exit();	


				if( ! empty( $dup_where ) ){
					
					$condition = "AND";
					if( isset( $this->prevent_duplicate["condition"] ) && $this->prevent_duplicate["condition"] ){
						$condition = $this->prevent_duplicate["condition"];
					}
					
					$dup_select[] = " `id` ";
					$dup_select[] = " `creation_date` ";
					$dup_select[] = " `created_by` ";
					
					$this->class_settings["overide_select"] = implode( ", ", $dup_select );
					$this->class_settings["where"] = " AND ( " . implode( " ".$condition." ", $dup_where ) . " )";
					
					if( $id ){
						$this->class_settings["where"] .= " AND `id` != '". $id ."'";
					}
					
					$r = $this->_get_records();
					
					if( isset( $r[0]["id"] ) && $r[0]["id"] ){
						$msg = '';
						
						if( isset( $this->prevent_duplicate["error_title"] ) && $this->prevent_duplicate["error_title"] ){
							$msg .= '<h4>'. $this->prevent_duplicate["error_title"] .'</h4>';
						}else{
							$msg .= '<h4>Duplicate Record</h4>';
						}
						
						
						if( isset( $this->prevent_duplicate["error_message"] ) && $this->prevent_duplicate["error_message"] ){
							$msg .= '<p>'. $this->prevent_duplicate["error_message"] .'</p>';
						}else{
							$msg .= '<p>There is an existing record:</p>';
						}
						
						$msgs = array();
						
						$labels = array();
						
						$tb = $this->table_name;
						if( function_exists( $tb ) ){
							$labels = $tb();
						}
						
						foreach( $dup_selected as $dk => $dv ){
							$add_dup = 0;
							if( $dup_details ){
								if( isset( $r[0][ $dk ] ) ){
									$add_dup = 1;
								}
							}else{
								if( isset( $r[0][ $dk ] ) && isset( $dup_val[ $dk ] ) && strtolower( trim( $r[0][ $dk ] ) ) == $dup_val[ $dk ] ){
									$add_dup = 1;
								}
							}
							
							if( $add_dup ){
								$lb = isset( $labels[ $dv ][ "field_label" ] )?$labels[ $dv ][ "field_label" ]:$dk;
								$msgs[] = $lb . ': <strong>' . $r[0][ $dk ] . '</strong>';
							}
						}
						
						if( $dup_details ){
							$msgs[] = 'Ref: <strong>' . $r[0][ "id" ] . '</strong>';
							$msgs[] = 'Created By: <strong>' . get_name_of_referenced_record( array( "id" => $r[0][ "created_by" ], "table" => "users" ) ) . '</strong>';
							$msgs[] = 'Created On: <strong>' . date("d-M-Y", doubleval( $r[0][ "creation_date" ] ) ) . '</strong>';
						}
						
						$msg .= '<ul><li style="list-style:none; padding:5px 0;">' . implode( '</li><li style="list-style:none; padding:5px 0;">', $msgs ) . '</li></ul>';
						
						$return = $this->_display_notification( array( "type" => "error", "message" => $msg ) );
						$return["status"] = "new-status";
						unset( $return["html"] );
						
						return $return;
					}
				}
			}
		
		}
		
		protected function _get_customer_call_log(){
			return $this->_get_record();
		}
		
		public function _get_record(){
			$db_table = $this->table_name;
			if( isset( $this->db_table_name ) && $this->db_table_name ){
				$db_table = $this->db_table_name;
			}
			
			//echo '<pre>';print_r( $this->class_settings ); echo '</pre>';exit;
			$cache_key = $this->table_name;
			if( ! isset( $this->class_settings[ 'current_record_id' ] ) )return 0;
			if( ! isset( $this->class_settings[ 'database_name' ] ) )return 0;
			
			$settings = array(
				'cache_key' => $cache_key . "-" . $this->class_settings[ 'current_record_id' ],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			$return_all = 0;
			
			$returning_array = array(
				'html' => '',
				'status' => 'get-product-features',
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK IF CACHE IS SET
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
				
			}
			$select = "";
			$table_fields = array();
			$mongo_criteria = array();
			$mongo_filter = array();
			
			foreach( $this->table_fields as $key => $val ){
				if( $select || ! empty( $mongo_filter ) ){
					$select .= ", `".$val."` as '".$key."'";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
				else {
					//else $select = "`id`, `modified_by`, `creation_date`, `modification_date`, `serial_num`, `".$val."` as '".$key."'";
					$select = " `".$db_table."`.`id`, `".$db_table."`.`serial_num`, `".$db_table."`.`created_by`, `".$db_table."`.`modification_date`, `".$db_table."`.`creation_date`, `".$db_table."`.`modified_by`, `".$db_table."`.`".$val."` as '".$key."'";
					$select .= ( isset($this->has_created_source) ? ", `".$db_table."`.`created_source`" : '' );
					// $select = " `".$db_table."`.`id`, `".$db_table."`.`serial_num`, `".$db_table."`.`created_by`, `".$db_table."`.`modification_date`, `".$db_table."`.`creation_date`, `".$db_table."`.`modified_by`, `".$db_table."`.`".$val."` as '".$key."'";
					$mongo_filter[ 'projection' ][ '_id' ] = 0;
					$mongo_filter[ 'projection' ][ 'id' ] = 1;
					$mongo_filter[ 'projection' ][ 'serial_num' ] = 1;
					$mongo_filter[ 'projection' ][ 'created_by' ] = 1;
					$mongo_filter[ 'projection' ][ 'modification_date' ] = 1;
					$mongo_filter[ 'projection' ][ 'creation_date' ] = 1;
					$mongo_filter[ 'projection' ][ 'modified_by' ] = 1;
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
			}

			if( isset( $this->class_settings[ 'overide_select' ] ) && $this->class_settings[ 'overide_select' ] ){
				$select = $this->class_settings[ 'overide_select' ];
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$db_table."` ";
			
			if( isset( $this->class_settings[ 'cache_where' ] ) && $this->class_settings[ 'cache_where' ] ){
				if( isset( $this->class_settings[ 'cache_join' ] ) && $this->class_settings[ 'cache_join' ] ){
					$query .= $this->class_settings[ 'cache_join' ];
					unset( $this->class_settings[ 'cache_join' ] );
				}
				$query .= " WHERE `".$db_table."`.`record_status` = '1' " . $this->class_settings[ 'cache_where' ];
				$return_all = 1;
				unset( $this->class_settings[ 'cache_where' ] );
			}else{
				if( $this->build_list && $this->build_list_condition ){
					$query .= " where `".$db_table."`.`record_status` = '1' ";
				}else{
					if( $this->class_settings[ 'current_record_id' ] == 'pass_condition' ){
						$query .= " where `".$db_table."`.`record_status` = '1' ";
						
						if( isset( $this->class_settings["limit"] ) && $this->class_settings["limit"] ){
							$query .= $this->class_settings["limit"];
						}
						
					}else{
						 if( isset( $this->class_settings[ 'default_reference_field_id' ] ) && $this->class_settings[ 'default_reference_field_id' ] && $this->default_reference_field && isset( $this->table_fields[ $this->default_reference_field ] ) ){
							$query .= " where  `".$db_table."`.`" . $this->table_fields[ $this->default_reference_field ] . "` = '".$this->class_settings[ 'default_reference_field_id' ]."'";
						 }else{
							$query .= " where `".$db_table."`.`id` = '".$this->class_settings[ 'current_record_id' ]."'";
						 }
					}
				}
			}
			
			/*
			switch( $this->table_name ){
			case "leave_requests":
			//case "leave_types":
			//case "users":
				echo $query; //exit;
			break;
			case "investigation":
			case "assets_category":
				echo $query; exit;
			break;
			}
			*/
			if( isset( $this->class_settings[ "mikee" ] ) ){
				// echo $query; exit;
			}
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),

				//MongoDB
				'table' => $this->table_name,
				'where' => $mongo_criteria,
				'select' => $mongo_filter,
				'others' => array( 'use_aggregate' => 1 ),
			);

			$all_departments = execute_sql_query($query_settings);
			// print_r( $all_departments );exit;
			switch( DB_MODE ){
				case "mongoDB":
				break;
				case 'mysql':
				break;
			}
			
			$list = array();
			$departments = array();
			
			$this->class_settings[ 'cache_count' ] = 0;
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				$this->class_settings[ 'cache_count' ] = count( $all_departments );
				
				if( $this->build_list && $this->build_list_field ){
					$settings = array(
						'cache_key' => $cache_key . "-list",
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					$list = get_cache_for_special_values( $settings );
				}
				
				foreach( $all_departments as $category ){
					
					if( $this->build_list && $this->build_list_field ){
						if( is_array( $this->build_list_field ) ){
							$list[ $category["id"] ] = $category;
						}else{
							$f1 = isset( $category[ $this->build_list_field ] )?$category[ $this->build_list_field ]:$category["id"];
							$f2 = '';
							if( isset( $this->build_list_field1 ) && $this->build_list_field1 ){
								$f2 = ' ' . ( isset( $category[ $this->build_list_field1 ] )?$category[ $this->build_list_field1 ]:'' );
							}
							
							if( isset( $this->build_list_field_bracket ) && $this->build_list_field_bracket ){
								$f2 = ' (' .trim($f2) . ') ';
							}
							$list[ $category["id"] ] = $f1 . $f2;
						}
						
					}
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key . "-" . $category["id"],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
					
					if( $this->reset_members_cache ){
						switch( $this->reset_members_cache ){
						case "group_by_default_reference_field":
							if( $this->default_reference_field && isset( $category[ $this->default_reference_field ] ) ){
								$this->_reset_members_cache( $category );
							}
						break;
						}	
					}
				}
				
				if( $this->build_list && $this->build_list_field ){
					
					$settings = array(
						'cache_key' => $cache_key . "-list",
						'cache_values' => $list,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				if( isset( $this->class_settings[ 'cache_where_result' ] ) && $this->class_settings[ 'cache_where_result' ] ){
					return $all_departments;
				}
				
				if( $return_all ){
					return $all_departments;
				}
				
				return $category;
			}
			
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->table_name.'-'.$record[ $this->default_reference_field ],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$members = get_cache_for_special_values( $settings );
			
			if( ! is_array( $members ) )$members = array();
			
			if( is_array( $members ) ){
				if( $clear ){
					unset( $members[ $record['id'] ] );
				}else{
					$members[ $record['id'] ] = $record;
				}
				
				$settings = array(
					'cache_key' => $cache_key.'-'.$this->table_name.'-'.$record[ $this->default_reference_field ],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
		
		protected function _store_extracted_line_items_data(){
			//STORE DATA IN exploration_drilling TABLE
			/*---------------------------*/
			$cache_key = $this->table_name;
			
			//for testing
			if( isset($_GET['id']) )
				$_POST['id'] = $_GET['id'];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$run_in_background = get_import_items_in_background_settings();
				
				$running_in_background = 0;
				if( $this->class_settings['running_in_background'] && $this->class_settings['running_in_background'] ){
					$run_in_background = 0;
					$running_in_background = 1;
					
					$running_in_background = $_POST['id'];
					
					$xdata = array();
					$xdata["processing"] = 1;
					$xdata["pending"] = 0;
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />Commencing '.$this->label.' Loading Sequence';
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
					
				}
				
				if( $run_in_background ){
					
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => $this->class_settings["action_to_perform"], 
							"user_id" => $this->class_settings["user_id"], 
							"reference" => $_POST['id'],
						) 
					);
					
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Loading Extracted Excel Records</h4><p>Please wait...</p>';
					$return = $err->error();
					
					$return["status"] = 'new-status';
					$return["html_replacement"] = $return["html"];

					$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
					$return["html_replacement_selector"] = "#".$ccx;
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = $_POST['id'];
					$return['action'] = '?action='. $this->table_name .'&todo=check_background_process';
					
					return $return;
				}
				
				set_time_limit(0);
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$return_table = 1;
				$barcode_items = array();
				
				//RETRIEVE EXTRACTED LINE ITEMS
				$settings = array(
					'cache_key' => 'items_raw_data_import-line-items-data-'.$this->class_settings['current_record_id'],
					'directory_name' => $this->table_name,
				);
				$line_items = get_cache_for_special_values( $settings );
				
				if( $running_in_background ){
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.number_format( count( $line_items ), 0 ).' '.$this->label.' are waiting to be Loaded';
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				//check for existing line items
				//$existing_line_items = $this->_get_existing_itemss();
				$existing_line_items = array();
				
				$array_of_dataset = array();
				$array_of_dataset_update = array();
				$array_of_update_conditions = array();
				
				//$new_record_id = get_new_id();
				$new_record_id = $this->class_settings['current_record_id'];
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				//$store = get_current_store(1);
				$store = get_main_store();
				
				$previous_pre_key = "";
				
				foreach( $line_items as $k => $v ){
					
					$dataset_to_be_inserted = array(
						'id' => $new_record_id .'CTS'. ++$new_record_id_serial,
						'created_role' => $this->class_settings[ 'priv_id' ],
						'created_by' => $this->class_settings[ 'user_id' ],
						'creation_date' => $date,
						'modified_by' => $this->class_settings[ 'user_id' ],
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
					);
					
					if( ! empty( $v ) ){
						foreach( $v as $kv => $vv ){
							if( isset( $this->table_fields[ $kv ] ) )
								$dataset_to_be_inserted[ $this->table_fields[ $kv ] ] = $vv;
						}
						
						$array_of_dataset[] = $dataset_to_be_inserted;
					}
					
				}
				
				if( $running_in_background ){
					$text = $this->label . " Data Transformation Completed";
					echo $text . " \n\n";
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
					
					$text = number_format( $new_record_id_serial, 0 ) . " ".$this->label." will be Created";
					echo $text . " \n\n";
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				$saved = 0;
				
				if( ! empty( $array_of_dataset ) ){
					
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset,
					);
					
					$returned_data = insert_new_record_into_table( $function_settings );
					$saved = 1;
					
					if( $running_in_background ){
						$text = "Finished Creating New " . $this->label;
						echo $text . " \n\n";
						$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
						
						$settings = array(
							'cache_key' => $cache_key . '-' . $running_in_background,
							'directory_name' => $cache_key,
							'cache_values' => $xdata,
							'permanent' => true,
						);
						set_cache_for_special_values( $settings );
					}
				}
				
				if( ! empty( $array_of_dataset_update ) && ! empty( $array_of_update_conditions ) ){
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset_update,
					);
					
					$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
					
					$returned_data = insert_new_record_into_table( $function_settings );
					
					$saved = 1;
				}
				
				if( $saved ){
					//update cache
					$this->class_settings['where'] = " AND `".$this->table_name."`.`modification_date` = ".$date;
				}
				
				
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Loading Extracted Excel Rows &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Started Creation of Load Queries' ),
						1 => array( 'title' => 'Successfully Created Load Queries' ),
						2 => array( 'title' => 'Started Loading Data' ),
						3 => array( 'title' => 'Successfully Loaded Data' ),
						4 => array( 'title' => 'Finished! <i class="icon-thumbs-up-alt"></i>' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				
				$return['action'] = '?action='.$this->table_name.'&todo=display_all_records_full_view';
				
				if( isset( $this->class_settings['where'] ) && $this->class_settings['where'] ){
					$this->class_settings['where_clause'] = $this->class_settings['where'];
					
					$this->class_settings["return_data"] = 0;
					$this->class_settings["query_cache"] = 1;
					$this->class_settings["do_not_check_cache"] = 1;
					$this->_refresh_cache();
				}
				
				
				if( $running_in_background ){
					$return = array();
					$return['status'] = 'new-status';
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "-";
					$return['action'] = '?action='. $this->table_name .'&todo=display_all_records_full_view';
					$xdata["returned_data"] = $return;
					
					$text = $this->label . " Loading Completed " . $running_in_background;
					echo $text . " \n\n";
					$xdata["status_text"][] = date("d-M-Y H:i:s") . ':<br />'.$text;
					$xdata["status"] = 1;
					
					$settings = array(
						'cache_key' => $cache_key . '-' . $running_in_background,
						'directory_name' => $cache_key,
						'cache_values' => $xdata,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
				
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		function _display_notification( $params = array() ){
			$code = '010014';
			$msg = isset( $params["message"] )?$params["message"]:'';
			$do_not_display = isset( $params["do_not_display"] )?$params["do_not_display"]:0;
			
			$html_add = isset( $params["html_add"] )?$params["html_add"]:'';
			$html_replacement = isset( $params["html_replacement"] )?$params["html_replacement"]:'';
			
			$html_replacement_selector = isset( $params["html_replacement_selector"] )?$params["html_replacement_selector"]:'';
			$js = isset( $params["callback"] )?$params["callback"]:array();
			$data = isset( $params["data"] )?$params["data"]:array();
			
			if( isset( $params["type"] ) ){
				switch( $params["type"] ){
				case "success":
					$code = '010011';
				break;
				case "info":
					$code = '010016';
				break;
				case "no_language":
					$code = '000017';
				break;
				}
			}
			
			$err = new cError( $code );
			$err->html_format = 2;
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			
			$new = 0;
			if( $html_replacement_selector ){
				$return["html_replacement_selector"] = $html_replacement_selector;
				$return["html_replacement"] = $return["html"];
				
				if( $html_add ){
					$return["html_replacement"] = $return["html"] . $html_add;
				}
				
				if( $html_replacement ){
					$return["html_replacement"] = $html_replacement;
				}
				
				if( $do_not_display ){
					unset( $return["msg"] );
					unset( $return["typ"] );
				}
				$new = 1;
			}
			
			if( ! empty( $js ) ){
				$new = 1;
				$return["javascript_functions"] = $js;
			}
			
			if( ! empty( $data ) ){
				$new = 1;
				$return["data"] = $data;
			}
			
			if( $new ){
				$return["status"] = "new-status";
				unset( $return["html"] );
			}
			
			return $return;
		}
		
		protected function _get_form_fields_and_labels( $e = array() ){
			
			if( ( isset( $e["id"] ) && $e["id"] ) || ( isset( $e["ids"] ) && $e["ids"] ) ){
				$f = new cDatabase_forms();
				$f->class_settings = $this->class_settings;
				
				if( ( isset( $e["ids"] ) && $e["ids"] ) ){
					$f->class_settings["action_to_perform"] = "get_form_fields3";
					$f->class_settings["ids"] = $e["ids"];
				}else{
					$f->class_settings["action_to_perform"] = "get_form_fields2";
					$f->class_settings["current_record_id"] = $e["id"];
				}
				
				if( isset( $e["version"] ) && $e["version"] == 2 ){
					$f->class_settings["params"][ 'return_plugins' ] = 'return_plugins';
				}
				
				$return = $f->database_forms();
				/*
				if( isset( $e["select2"] ) && $e["select2"] ){
					if( isset( $return["fields"] ) && is_array( $return["fields"] ) && ! empty( $return["fields"] ) ){
						foreach( $return["fields"] as $fk ){
							if( isset( $_POST[ $fk ] ) && isset( $return["labels"][ $fk ]["field_key"] ) ){
								$_POST[ $return["labels"][ $fk ]["field_key"] ] = $_POST[ $fk ];
								unset( $_POST[ $fk ] );
							}
						}
					}
				}
				*/
				
				return $return;
			}
			
		}
		
		protected function _save_display_spreadsheet_view( $o1 = array() ){
			
			$error_msg = '';
			
			if( isset( $_POST["s_table"] ) && $_POST["s_table"] && isset( $_POST["data"] ) && $_POST["data"] ){
				$js = array( "nwBulkDataCapture.clear" );
				$s_plugin = isset( $_POST["s_plugin"] )?$_POST["s_plugin"]:'';
				$s_preview = isset( $_POST["s_preview"] )?$_POST["s_preview"]:0;
				$json = json_decode( $_POST["data"], true );
				
				$columns = array();
				$columns2 = array();
				$data = array();
				
				$s_tb = $_POST["s_table"];
				
				if( $s_plugin ){
					$clp = 'c' . ucwords( strtolower( $s_plugin ) );
					if( class_exists( $clp ) ){
						$ptb = new $clp();
						$in = $ptb->load_class( array( 'class' => array( $s_tb ), 'initialize' => 1 ) );
						if( isset( $in[ $s_tb ]->table_name ) ){
							$cl = $in[ $s_tb ];
						}else{
							$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $s_tb ."' is invalid or missing in the project.</p>";
						}
					}
				}
				
				if( ! $error_msg ){
					if( $s_tb != $this->table_name ){
						$cs = 'c' . ucwords( $s_tb );
						if( class_exists( $cs ) ){
							$cl = new $cs();
						}else{
							$error_msg = '<h4>Class Not Found</h4>';
						}
					}else{
						$cl = $this;
					}
				}
				
				if( ! $error_msg ){
					$this->table_fields = $cl->table_fields;
					$this->table_name = $cl->table_name;
					$this->label = ( isset( $cl->label )?$cl->label:$cl->table_name );
					
					if( isset( $cl->spreadsheet_options ) && $cl->spreadsheet_options ){
						$this->spreadsheet_options = $cl->spreadsheet_options;
					}
				}
				$tb = $this->table_name;
				if( isset( $o1["labels"] ) && ! empty( $o1["labels"] ) ){
					$table_labels = $o1["labels"];
					//print_r( $table_labels ); exit;
				}else if( function_exists( $tb ) ){
					$table_labels = $tb();
				}
				
				if( ! $error_msg ){
					if( isset( $json["columns"] ) && is_array( $json["columns"] ) && ! empty( $json["columns"] ) ){
						foreach( $json["columns"] as $k => $cval ){
							$columns2[ $cval["data"] ] = $k;
							$columns[ $k ] = $cval["data"];
							
							if( isset( $this->table_fields[ $cval["data"] ] ) ){
								$k1 = $this->table_fields[ $cval["data"] ];
								
								if( isset( $table_labels[ $k1 ] ) ){
									
									switch( $table_labels[ $k1 ]['form_field'] ){
									case "select":
									case 'radio':
										/* 
										if( isset( $table_labels[ $k1 ]['form_field_options'] ) && $table_labels[ $k1 ]['form_field_options'] ){
											$f2 = $table_labels[ $k1 ]['form_field_options'];
											if( function_exists( $f2 ) ){
												$f3 = $f2();
												if( is_array( $f3 ) && ! empty( $f3 ) ){
													
													$f2 = $table_labels[ $k1 ]['form_field_options_array'] = $f3;
													
													foreach( $f3 as $f3k => $f3v ){
														$table_labels[ $k1 ]["form_field_options_reversed"][ clean3( strtolower( $f3v ) ) ] = $f3k;
													}
												}
											}
										}
										*/
									break;
									}
								}
							}
							
						}
					}else{
						$error_msg = '<h4>Invalid Column Details</h4>';
					}
					
					if( isset( $json["data"] ) && is_array( $json["data"] ) && ! empty( $json["data"] ) ){
						$data = $json["data"];
					}else{
						$error_msg = '<h4>Invalid Changes</h4>';
					}
				}
				
				$success_msg = '';
				
				if( ! $error_msg ){
					//print_r( $columns );
					//print_r( $data ); exit;
					$clear_ids = array();
					$line_items = array();
					$line_items_skipped = array();
					$new_records = 0;
					$update_records = 0;
					
					foreach( $data as $dk => $dval ){
						$lt = array();
						$record_id = '';
						$record_id2 = get_new_id() . 'bk';
						$record_serial = 0;
						$row_add = 0;
						$skip = 0;
						$empty_row = 1;
						
						foreach( $columns2 as $ck => $cv ){
							
							$add = 0;
							$add_val = '';
							if( isset( $dval[ $cv ] ) && $dval[ $cv ] ){
								$add_val = trim( $dval[ $cv ] );
								$add = 1;
							}
							
							switch( $ck ){
							case "id":
								$add = 0;
								$record_id = $add_val;
								//$add_val = $record_id2 . ++$record_serial;
							break;
							default:
								
								if( isset( $this->table_fields[ $ck ] ) ){
									$k1 = $this->table_fields[ $ck ];
									
									if( isset( $table_labels[ $k1 ] ) ){
										
										switch( $table_labels[ $k1 ]['form_field'] ){
										case "select":
										case 'radio':
											/* if( isset( $table_labels[ $k1 ]['form_field_options_array'] ) && isset( $table_labels[ $k1 ]['form_field_options_array'][ $add_val ] ) ){
												
												$lt[ $ck . '_text_' ] = $table_labels[ $k1 ]['form_field_options_array'][ $add_val ];
											}else{
												$add_val3 = clean3( strtolower( $add_val ) );
												
												if( isset( $table_labels[ $k1 ]['form_field_options_reversed'] ) && isset( $table_labels[ $k1 ]['form_field_options_reversed'][ $add_val3 ] ) ){
													$add_val4 = $table_labels[ $k1 ]['form_field_options_reversed'][ $add_val3 ];
													
													
													if( isset( $table_labels[ $k1 ]['form_field_options_array'] ) && isset( $table_labels[ $k1 ]['form_field_options_array'][ $add_val4 ] ) ){
														$add_val = $add_val4;
														$lt[ $ck . '_text_' ] = $table_labels[ $k1 ]['form_field_options_array'][ $add_val4 ];
													}
												}else{
													//$skip = 1;
													//$lt[ $ck . '_flag_' ] = 1;
													$add_val = '';
												}
											}
											*/
										break;
										case "date-5":
										case "date":
											if( $add_val ){
												//$add_val .= 'T00:00:00+01:00';
												$add_val = convert_date_to_timestamp( $add_val , 1 );
											}
										break;
										}
										
										if( isset( $table_labels[ $k1 ][ "required_field" ] ) && $table_labels[ $k1 ][ "required_field" ] == 'yes' ){
											if( ! $add_val ){
												$skip = 1;
												$lt[ $ck . '_flag_' ] = 1;
											}
										}else{
											if( $add_val )$add = 1;
										}
									}
								}
								
							break;
							}
							
							if( $add ){
								$row_add = 1;
								$lt[ '_flag_num_' ] = $dk;
								$lt[ $ck ] = $add_val;
								$empty_row = 0;
							}else{
							}
							
						}
						
						if( $skip ){
							$row_add = 0;
						}
						
						if( $row_add ){
							
							if( $record_id ){
								//edit record
								$clear_ids[] = "'". $record_id ."'";
								$lt["new_id"] = $record_id;
								++$update_records;
							}else{
								++$new_records;
							}
							
							$line_items[] = $lt;
						}else{
							if( ! $empty_row )$line_items_skipped[] = $lt;
						}
						
					}
					
					
				}
				
				/*
				if( isset( $json["deleted"] ) && is_array( $json["deleted"] ) && ! empty( $json["deleted"] ) ){
					$del = array();
					foreach( $json["deleted"] as $dk => $dv ){
						$del[] = "'". $dk ."'";
					}
					
					$success_msg .= '<p>'.number_format( count( $del ), 0 ).' Deleted Entries</p>';
					
					$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `id` IN ( ". implode( ",", $del ) ." ) ";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
				}
				*/
				
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$tb = $this->table_name;
				
				$show_preview = 0;
				if( $s_preview ){
					$show_preview = 1;
				}else if( ! empty( $line_items_skipped ) && $s_preview ){
					$show_preview = 1;
					$line_items = array();
				}
				
				if( $show_preview ){
					$this->class_settings[ 'data' ]["unsaved_items"] = $line_items_skipped;
					$this->class_settings[ 'data' ]["saved_items"] = $line_items;
					
					$this->class_settings[ 'data' ]["table_fields"] = $this->table_fields;
					$this->class_settings[ 'data' ]["table_labels"] = $table_labels;
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/bulk-data-capture-preview.php' );
					$html = $this->_get_html_view();
					
					$title = 'Bulk Data Capture Preview: ' . $this->label;
					$js = array( "set_function_click_event", "prepare_new_record_form_new" );
					
					$this->class_settings[ "modal_title"] = $title;
					$this->class_settings[ "modal_dialog_style"] = "width:90%;";

					$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
					return $this->_launch_popup( $html, '#'.$ccx, $js );
				}else if( ! empty ( $line_items ) ){
					//sharepoint hack
					/* $r2 = $this->_save_multiple_new_popup_form( array( "data" => $line_items ) );
					$r = 0;
					if( isset( $r2["success"] ) && $r2["success"] ){
						$r = doubleval( $r2["success"] );
					}else{
						return $r2;
					} */
					
					//direct save
					$this->class_settings["line_items"] = $line_items;
					$r = $this->_save_line_items();
					
					if( $r ){
						$error_msg = '<h4>Successful Save Operation</h4>' . number_format( $r, 0 ) . ' records were saved';
						return $this->_display_notification( array( "message" => $error_msg, "type" => "success", "callback" => $js ) );
					}else{
						$error_msg = '<h4>Unable to Save Records</h4>';
					}
				}else{
					$error_msg = '<h4>No Records Found</h4>';
				}
			}else{
				$error_msg = '<h4>No Pending Changes</h4>';
			}
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>';
			}
			
			return $this->_display_notification( array( "message" => $error_msg, "type" => "error" ) );
		}
		
		protected function _display_spreadsheet_view( $o = array() ){
			$error = '';
			$error_typ = 'error';
			
			$filename = '';
			$title = $this->label;
			
			$data = isset( $o[ 'data' ] )?$o[ 'data' ]:array();
			$ojs = isset( $o[ 'callback' ] )?$o[ 'callback' ]:array();
			
			$js = array( "prepare_new_record_form_new" , "set_function_click_event", "nwBulkDataCapture.activateHandsontable" );
			
			if( isset( $o[ 'auto_refresh' ] ) && $o[ 'auto_refresh' ] ){
				$js[] = "nwBulkDataCapture.autoPopuplateChanges";
			}
			
			if( ! empty( $ojs ) ){
				$js = array_merge( $js, $ojs );
			}
			
			$ccx = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			$handle = $ccx;
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			
			$s_type = 'insert';
			$action_to_perform = $this->class_settings["action_to_perform"];
			switch( $action_to_perform ){
			case "display_spreadsheet_view":
				$s_type = 'replace';
			break;
			case "display_spreadsheet_new":
			break;
			}
			
			if( isset( $o["fields"] ) && ! empty( $o["fields"] ) ){
				$opt[ 'fields' ] = $o["fields"];
			}else if( isset( $this->table_fields ) && ! empty( $this->table_fields ) ){
				$labels = array();
				$tb = $this->table_name;
				if( isset( $o["labels"] ) && ! empty( $o["labels"] ) ){
					$labels = $o["labels"];
				}else if( function_exists( $tb ) ){
					$labels = $tb();
				}
				
				if( isset( $this->spreadsheet_options["options"] ) && $this->spreadsheet_options["options"] ){
					$opt = $this->spreadsheet_options["options"];
				}
				
				foreach( $this->table_fields as $key => $val ){
					$x1 = array();
					if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
						switch( $labels[ $val ][ 'display_position' ] ){
						case "display-in-table-row":
							
							if( ! ( isset( $labels[ $val ][ 'no_bulk_capture' ] ) && $labels[ $val ][ 'no_bulk_capture' ] ) ){
								
								$x1 = __get_value( '', $key, array( "get_label_and_value" => 1, "add_options" => 1, "globals" => array( "fields" => array( $key => $val ), "labels" => array( $val => $labels[ $val ] ) ) ) );
								
								$x1['type'] = 'text';
								
								switch( $labels[ $val ]['form_field'] ){
								case "select":
									//$x1['type'] = 'checkbox';
									///$x1['type'] = 'dropdown';
									//$x1['source'] = __get_source_list( $key );
								break;
								case "date-5time":
								case "date-5":
									$x1['type'] = 'date';
									$x1['dateFormat'] = 'YYYY-MM-DD';
									$x1['defaultDate'] = date("Y-m-d");
									$x1['correctFormat'] = true;
								break;
								}
								
								if( ! isset( $opt["required_field"] ) ){
									if( isset( $labels[ $val ]['required_field'] ) && $labels[ $val ]['required_field'] == 'yes' ){
										$x1['required'] = true;
									}
								}else if( isset( $opt["required_field"][ $key ] ) ){
									$x1['required'] = true;
								}
							}
						break;
						}
					}
					
					
					if( ! empty( $x1 ) ){
						$opt[ 'fields' ][ $key ] = $x1;
					}
				}
			}
			//print_r( $opt ); exit;
			$opt[ 'data' ] = isset( $o[ 'data_object' ] )?$o[ 'data_object' ]:array();
			$data[ 'spreadsheet' ] = $this->_display_spreadsheet( $opt );
			
			
			$df = array();
			$df[ 'table' ] = $this->table_name;
			if( isset( $this->plugin ) && $this->plugin ){
				$df[ 'plugin' ] = $this->plugin;
			}
			$df[ 'type' ] = $s_type;
			$df[ 'container' ] = 'entire-bulk-container';
			
			$df[ 'action' ] = '?action=' . $this->table_name_static . '&todo=save_'.$action_to_perform.'&html_replacement_selector=' . $df[ 'container' ];
			$df[ 'title' ] = $title;
			
			foreach( array( 'table', 'type', 'container', 'action', 'title', 'plugin' ) as $chk ){
				if( ! ( isset( $data[ $chk ] ) ) && isset( $df[ $chk ] ) ){
					$data[ $chk ] = $df[ $chk ];
				}
			}
			
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/bulk-data-capture.php' );

			$html = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => '#'.$handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		protected function _display_spreadsheet( $opt = array() ){
			$data = array();
			$hide_width = 0.1;
			$empty_rows = intval( isset( $opt[ 'empty_rows' ] )?$opt[ 'empty_rows' ]:100 );
			$data[ 'data_object' ] = isset( $opt[ 'data' ] )?$opt[ 'data' ]:array();
			unset( $opt[ 'data' ] );
			
			$default_width = 250;

			if( isset( $opt[ 'fields' ] ) && is_array( $opt[ 'fields' ] ) && ! empty( $opt[ 'fields' ] ) ){

				$data[ 'colHeaders' ] = array();
				$data[ 'columns' ] = array();
				$empty_value = array();

				foreach( $opt[ 'fields' ] as $key => $field ){
					if( ! isset( $field[ 'type' ] ) ){
						$field[ 'type' ] = 'text';
					}
					
					$o = $field;
					$o["data"] = $key;

					if( ! ( isset( $field[ 'width' ] ) && $field[ 'width' ] ) )$o[ 'width' ] = $default_width;
					if( isset( $field[ 'hide' ] ) && $field[ 'hide' ] ){
						$o[ 'width' ] = $hide_width;
						unset( $o[ 'hide' ] );
					}

					if( isset( $field[ 'required' ] ) && $field[ 'required' ] ){
						$o[ 'label' ] .= ' *';
						unset( $o[ 'required' ] );
					}
					
					if( isset( $field[ 'edit' ] ) && ! $field[ 'edit' ] ){
						$o[ 'readOnly' ] = true;
						unset( $o[ 'edit' ] );
					}
					
					if( isset( $field[ 'options' ] ) && is_array( $field[ 'options' ] ) && ! empty( $field[ 'options' ] ) ){
						$o[ 'editor' ] = 'select';
						$o[ 'selectOptions' ] = $field[ 'options' ];
						$o[ 'type' ] = 'dropdown';
						unset( $o[ 'options' ] );
					}

					$ev = '';
					switch( $o[ 'type' ] ){
					case 'currency':
						$o[ 'numericFormat' ] = array(
							'pattern' => '0,0.00',
							'culture' => 'en-US',
						);
						$o["type"] = "numeric";
					break;
					case 'numeric':
						$o[ 'numericFormat' ] = array(
							'pattern' => '0,0',
							'culture' => 'en-US',
						);
					break;
					case 'date':
						$o[ 'dateFormat' ] = 'YYYY-MM-DD';
					break;
					case 'time':
						$o["type"] = "time";
						$o[ 'timeFormat' ] = 'h:mm a';
						//$o[ 'timeFormat' ] = 'HH:mm';
						$o[ 'correctFormat' ] = true;
					break;
					case 'checkbox':
						$ev = false;
						/* $o[ 'label' ] = array(
							'position' => 'after',
							//'property' => $key, // Read value from row object [key to read]
							'value' => 'Yes', // Read value from row object [key to read]
						); */
					break;
					case 'html':
						$o["type"] = "text";
						$o["renderer"] = 'html';
					break;
					}
					
					
					if( isset( $field[ 'action' ] ) && $field[ 'action' ] ){
						/* $o1 = $o;
						$o1[ 'data' ] .= "_rid";
						$o1[ 'readOnly' ] = true;
						$o1[ 'width' ] = $o1[ 'width' ] / 2;
						$empty_value[ $o1[ 'data' ] ] = $ev;
						$data[ 'colHeaders' ][] = "'". ( isset( $o[ 'label' ] ) ? ( $o[ 'label' ] . " RID" ) : ucwords( $key . "_rid" ) ) ."*'";
						$data[ 'columns' ][] = $o1; */
						
						$o[ 'type' ] = 'autocomplete';
						$o[ 'strict' ] = false;
					}
					
					$empty_value[ $key ] = $ev;
					
					$data[ 'colHeaders' ][] = "'". ( isset( $o[ 'label' ] ) ? $o[ 'label' ] : ucwords( $key ) ) ."'";
					$data[ 'columns' ][] = $o;
					
				}
				
				if( empty( $data[ 'data_object' ] ) && $empty_rows ){
					for( $i = 0; $i < $empty_rows; $i++ ){
						$data[ 'data_object' ][] = $empty_value;
					}
				}
				
				$fls = array_keys( $opt[ 'fields' ] );
				
				if( ! empty( $data[ 'data_object' ] ) ){
					foreach( $data[ 'data_object' ] as & $sval ){
						$sval2 = array();
						foreach( $fls as $fv ){
							$typ = isset( $fv[ 'type' ] ) ? $fv[ 'type' ] : '';

							switch( $typ ){
							case 'currency':
							case 'numeric':
								if( isset( $sval[ $fv ] ) )$sval[ $fv ] = intval( $sval[ $fv ] );
								else $sval[ $fv ] = 0;
							break;
							default:
								if( ! isset( $sval[ $fv ] ) )$sval[ $fv ] = '';
							break;
							}

						}
					}
				}
				
			}
			return $data;
		}
		
		
		public function _display_html_only( $o = array() ){
			$website = new cWebsite();
			$website->class_settings = $this->class_settings;
			$website->class_settings["do_not_show_header"] = 1;
			$website->class_settings["action_to_perform"] = 'setup_website';
			$r2 = $website->website();
			
			$data = $o;
			$data["website_data"] = $r2;
			
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/html.php' );
			echo $this->_get_html_view(); exit;
		}
		
		public function _get_project_settings_data( $opt = array() ){
			if( class_exists( 'cProject_settings' ) && isset( $opt["reference_table"] ) && isset( $opt["keys"] ) && is_array( $opt["keys"] ) ){
				$imp = $opt["keys"];
				
				if( ! empty( $imp ) ){
					$p = new cProject_settings();
					$p->class_settings = $this->class_settings;
					$p->class_settings[ 'overide_select' ] = " `". $p->table_name ."`.`". $p->table_fields[ 'key' ] ."` as 'key', `". $p->table_name ."`.`". $p->table_fields[ 'value' ] ."` as 'value' ";
					$p->class_settings[ 'where' ] = " AND `". $p->table_name ."`.`". $p->table_fields[ 'reference_table' ] ."` = '". $opt["reference_table"] ."' AND `". $p->table_name ."`.`". $p->table_fields[ 'key' ] ."` IN ( '". implode("', '", $imp ) ."' ) ";
					return $p->_get_records( array( "index_field" => 'key' ) );
				}
			}
		}
		
		public function _get_flags( $opt = array() ){
			$r = array();
			
			if( isset( $opt["record"]["reference"] ) && $opt["record"]["reference"] && isset( $opt["record"]["reference_table"] ) ){
				$sc = 1;
				$sf = 1;
				if( isset( $opt["source"] ) ){
					switch( $opt["source"] ){
					case 'utility_buttons':
						$sc = isset( $opt["show_comments"] )? $opt["show_comments"] :0;
						$sf = isset( $opt["show_files"] )? $opt["show_files"] :0;
					break;
					}
				}
				
				if( $sf && class_exists("cFiles") ){
					$fd = new cFiles();
					$fd->class_settings = $this->class_settings;
					$fd->class_settings["overide_select"] = " MAX(`creation_date`) as 'date' ";
					$wh = " `".$fd->table_fields["reference"]."` = '". $opt["record"]["reference"] ."' AND `".$fd->table_fields["reference_table"]."` = '". $opt["record"]["reference_table"] ."' ";
					
					if( isset( $opt["community"] ) && $opt["community"] && isset( $fd->table_fields["community"] ) ){
						$wh = " (".$wh.") OR `".$fd->table_fields["community"]."` = '". $opt["community"] ."' ";
					}
					$fd->class_settings["where"] = " AND ". $wh;
					$rflg = $fd->_get_records();
					
					if( isset( $rflg[0]['date'] ) && $rflg[0]['date'] ){
						//$cflg = ' <sup class="text-danger">' . get_age( $rflg[0]['date'], 0, 1, 0 ) . '</sup>';
						$r["files"] = ' <sup class="text-danger">~' . time_passed_since_action_occurred( date("U") - doubleval( $rflg[0]['date'] ), 6 ) . '</sup>';
						
						$r["g_time"] = doubleval( $rflg[0]['date'] );
						$r["g_text"] = $r["files"];
					}
				}
				
				if( $sc && class_exists("cComments") ){
					$fd = new cComments();
					$fd->class_settings = $this->class_settings;
					$fd->class_settings["overide_select"] = " MAX(`creation_date`) as 'date' ";
					$fd->class_settings["where"] = " AND `".$fd->table_fields["reference"]."` = '". $opt["record"]["reference"] ."' AND `".$fd->table_fields["reference_table"]."` = '". $opt["record"]["reference_table"] ."' ";
					$rflg = $fd->_get_records();
					
					if( isset( $rflg[0]['date'] ) && $rflg[0]['date'] ){
						//$cflg = ' <sup class="text-danger">' . get_age( $rflg[0]['date'], 0, 1, 0 ) . '</sup>';
						$r["comments"] = ' <sup class="text-danger">~' . time_passed_since_action_occurred( date("U") - doubleval( $rflg[0]['date'] ), 6 ) . '</sup>';
						
						$ax = 1;
						if( isset( $r["g_time"] ) && $r["g_time"] > doubleval($rflg[0]['date']) ){
							$ax = 0;
						}
						if( $ax ){
							$r["g_time"] = $rflg[0]['date'];
							$r["g_text"] = $r["comments"];
						}
					}
				}
			}
			
			return $r;
		}
	
	}
?>
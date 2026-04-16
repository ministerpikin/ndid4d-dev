<?php
	/**
	 * import_items Class
	 *
	 * @used in  				import_items Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	import_items
	 */

	/*
	|--------------------------------------------------------------------------
	| import_items Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cImport_items{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'import_items';
		
		private $associated_cache_keys = array(
			'import_items',
		);
		
		public $table_fields = array(
			'file' => 'import_items001',
			'operator' => 'import_items002',
			'import_template_type' => 'import_items003',
			'unit' => 'import_items004',
			'month' => 'import_items005',
			'department' => 'import_items006',
			'budget_code' => 'import_items007',
			
			'starting_row' => 'import_items008',
			'code_column' => 'import_items009',
			'description_column' => 'import_items010',
			'weight_column' => 'import_items011',
			'cost_price_column' => 'import_items012',
			'selling_price_column' => 'import_items013',
			'percentage_markup_column' => 'import_items014',
			
			'color_column' => 'import_items015',
			'category_column' => 'import_items016',
			
			'vendor_column' => 'import_items030',
			'currency_column' => 'import_items031',
			
			'quantity_column' => 'import_items017',
			'barcode_column' => 'import_items018',
			
			'unit_column' => 'import_items019',
			'unit_of_measure_column' => 'import_items020',
			
			'approved_budget_naira_column' => 'import_items021',
			'approved_budget_dollar_column' => 'import_items022',
			
			'remarks' => 'import_items023',
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				
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
	
		function import_items(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError('000017');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
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
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'get_import_items':
				$returned_value = $this->_get_import_items();
			break;
			case 'save_pre_import':
			case 'pre_import':
			case 'excel_import_form_options_map':
			case 'excel_import_form_options_nomap':
			case 'excel_import_form_options':
			case 'generate_excel_import_form':
				$returned_value = $this->_generate_excel_import_form();
			break;
			case 'save_and_start_import_with_callback':
			case 'save_and_start_operator_excel_import':
				$returned_value = $this->_save_and_start_operator_excel_import();
			break;
			case 'import_excel_file_data2':
			case 'import_excel_file_data':
				$returned_value = $this->_import_excel_file_data();
			break;
			case 'import_items':
			case 'import_napims_cash_calls':
			case 'import_napims_returns':
			case 'import_returns':
			case 'import_budget':
			case 'import_napims_budget':
				$returned_value = $this->_import_items();
			break;
			case 'get_field_mapping_form':
				$returned_value = $this->_get_field_mapping_form();
			break;
			case 'save_direct_import_no_bg':
			case 'save_direct_update_import':
			case 'save_update_import':
			case 'save_direct_import':
			case 'save_and_proceed_with_import':
				$returned_value = $this->_save_and_proceed_with_import();
			break;
			case 'save_direct_update_import_in_background':
			case 'save_direct_import_in_background':
			case 'check_bg_operation':
				$returned_value = $this->_check_bg_operation();
			break;
			case 'how_to_import_no_map':
				$returned_value = $this->_how_to_import_no_map();
			break;
			case 'a':
				$returned_value = $this->_bulk_import_from_folder();
			break;
			}
			
			return $returned_value;
		}
		
		private function _bulk_import_from_folder(){
			echo '<pre>';

			$table = isset( $_GET[ 'table' ] ) && $_GET[ 'table' ] ? $_GET[ 'table' ] : '';

			$extensions = array( 'xlsx', 'xls', 'csv' );
			$invalid_extensions = array();

			$cp = $this->class_settings[ 'calling_page' ];
			$directory = '../test-forms/nsr-excels';

			if( file_exists( $cp.$directory ) && dir( $cp.$directory ) ){
				if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
					ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
				}else{
					ini_set('memory_limit', '4G' );
				}

				$files = glob( $cp.$directory . "/*.{". implode(",", $extensions ) ."}", GLOB_BRACE );

			    $files = array();
			    foreach(scandir( $cp.$directory ) as $file){
			    	$history = array();

			        if ( $file !== '.' && $file !== '..' ) {
			        	$read_message = "Reading File '". $file ."'\n";
			        	echo $read_message;
			        	$history[] = $read_message;

			        	$info = pathinfo( $cp.$directory . '/'. $file );

			        	if( in_array( $info[ 'extension' ], $extensions ) ){
			        		$_POST[ 'id' ] = 'is23998037749';
			        		$opt = array( 
			        			'file' => $directory . '/' . $info[ 'basename' ], 
			        			'return_data' => 1,
			        		);

			        		$data = $this->_import_excel_file_data( $opt );
							// print_r( $data );exit;

			        	}else{
				        	$read_message = "File '". $file ."' has an Invalid Extension '". $info[ 'extension' ] ."'\n\n";
				        	echo $read_message;
				        	$history[] = $read_message;
			        		$invalid_extensions[] = $info;
			        	}
			        }
						echo $file . '<br>';
			    }
				exit;
			}
		}
		
		private function _how_to_import_no_map(){
			$error_msg = '';
			$e_type = 'error';
			$container1 = '';
			$container = '';
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$container1 = $this->class_settings["html_replacement_selector"];
				$container = '#' . $container1;
			}else{
				$error_msg = 'Invalid Container';
			}
			
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$filename = 'learn-how-to-import.php';
			$d = array();
			$action = '';
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				
				
				$tbd = explode( ':::' , $_POST[ 'id' ] );
				$tba = array();
				if( ! empty( $tbd ) ){
					foreach( $tbd as $tbv ){
						$tba2 = explode( '=', $tbv );
						if( isset( $tba2[1] ) && $tba2[1] ){
							$tba[ $tba2[0] ] = $tba2[1];
						}
					}
				}
				$tb_type = isset( $tba["type"] )?$tba["type"]:'';
				$tb = isset( $tba["value"] )?$tba["value"]:'';
					
				if( ! $tb && isset( $_GET["dt"] ) && $_GET["dt"] ){
					$tb = $_POST["id"];
				}
				
				switch( $tb_type ){
				case "plugin":
					
					if( isset( $tba["key"] ) && $tba["key"] ){
						$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
						if( class_exists( $clp ) ){
							$ptb = new $clp();
							$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
							if( isset( $in[ $tb ]->table_name ) ){
								$t = $in[ $tb ];
								$table = $tb;
							}else{
								$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
							}
						}else{
							$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
						}
					}else{
						$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
					}
					
				break;
				default:
					$cls = 'c' . ucwords( $tb );
					if( class_exists( $cls ) ){
						$t = new $cls();
						$table = $tb;
					}else{
						$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
					}
				break;
				}
					
				//$table = $_POST["id"];
				//$table1 = 'c' . ucwords( $table );
				if( isset( $t ) && function_exists( $table ) ){
					//$t = new $table1();
					
					if( isset( $t->basic_data["import_template"]["fields"] ) ){
						$d["import_fields"] = $t->basic_data["import_template"]["fields"];
					}else{
						$d["import_fields"] = $t->table_fields;
					}
					$d["fields"] = $t->table_fields;
					
					$d["labels"] = $table();
					$d["title"] = isset( $t->label )?$t->label:$t->table_name;
					$d["table"] = $t->table_name;
				}
			}
			
			if( ! $error_msg ){
				
				$this->class_settings[ 'data' ][ "html_replacement_selector" ] = $container1;
				$this->class_settings[ 'data' ] = $d;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
					
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => $container,
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js
				);
			}
			
			if( ! $error_msg ){
				$error_msg = 'Unknown Error';
			}
			
			if( $error_msg ){
				$cs = new cCustomer_call_log();
				return $cs->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
		}
		
		private function _check_bg_operation(){
			$action_to_perform = $this->class_settings["action_to_perform"];
			$return = array();
			set_time_limit(0);

			if( isset( $_GET[ 'use_id' ] ) && $_GET[ 'use_id' ] ){
				$_POST[ 'id' ] = $_GET[ 'use_id' ];
			}
			
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
			case 'save_direct_update_import_in_background':
			case 'save_direct_import_in_background':
				if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
					ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
				}else{
					ini_set('memory_limit', '2G' );
				}
				$run_in_bg = 1;
			break;
			}
			
			switch( $action_to_perform ){
			case 'save_direct_import_in_background':
			case 'save_direct_update_import_in_background':
				
				$this->class_settings[ 'database_name' ] = 'meta_data2';
				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_import_items();
				// print_r( $e );
				
				if( isset( $e["id"] ) && isset( $e["import_template_type"] ) && $e["id"] ){
					
					$tbd = explode( ':::' , $e["import_template_type"] );
					$tba = array();
					if( ! empty( $tbd ) ){
						foreach( $tbd as $tbv ){
							$tba2 = explode( '=', $tbv );
							if( isset( $tba2[1] ) && $tba2[1] ){
								$tba[ $tba2[0] ] = $tba2[1];
							}
						}
					}
					$tb_type = isset( $tba["type"] )?$tba["type"]:'';
					$tb = isset( $tba["value"] )?$tba["value"]:'';

					$error_msg = '';
					
					switch( $tb_type ){
					case "plugin":
						
						if( isset( $tba["key"] ) && $tba["key"] ){
							$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
							if( class_exists( $clp ) ){
								$ptb = new $clp();
								$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
								if( isset( $in[ $tb ]->table_name ) ){
									$t2 = $in[ $tb ];
									$t = $tb;
								}else{
									$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
								}
							}else{
								$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
							}
						}else{
							$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
						}
						
					break;
					default:
						$cls = 'c' . ucwords( $tb );
						if( class_exists( $cls ) ){
							$t2 = new $cls();
							//$t2->class_settings = $this->class_settings;
							$t = $tb;
						}else{
							$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
						}
					break;
					}

					if( $error_msg ){
						if( $run_in_bg ){
							$text = $error_msg;
							
							$run_in_bg_log .= $text;
							echo $text;
						}
					}else{

						//$t = strtolower( $e["import_template_type"] );
						//$t1 = "c" . ucwords( $t );
						
						if( isset( $t2 ) ){
						//if( class_exists( $t1 ) ){
							//$t2 = new $t1();
							$t2->class_settings = $this->class_settings;
							
							$t2_labels = array();
							
							if( function_exists( $t ) ){
								$t2_labels = $t();
							}
							
							if( $run_in_bg ){
								$settings = array(
									'cache_key' => $cache_key . "-bg-process-" . $id,
									'cache_values' => $id,
									'directory_name' => $cache_key,
									'permanent' => true,
								);
								set_cache_for_special_values( $settings );
							}
							
							$data = $this->_import_excel_file_data( array( "return_data" => 1 ) );
							
							$date_key = '';
							$file_name_key = '';
							$error = '';
							
							$new_id = 'di' . get_new_id();
							$new_id_serial = 1;
							$store = get_current_store();
							$currency = get_default_currency_settings();
							$tmp_cache = array();
							$date = date("U");
							
							if( is_array( $data ) && ! empty( $data ) ){
								
								//check row zero to get template parameters
								$columns_data = array();
								$fields_data = array();
								
								if( isset( $data[0] ) && is_array( $data[0] ) && ! empty( $data[0] ) ){
									foreach( $data[0] as $k => $v1 ){
										$v = strtolower( trim( $v1 ) );
										
										if( $v && isset( $t2->table_fields[ $v ] ) ){
											$columns_data[ $k ] = array( "f_key" => $v, "f_id" => $t2->table_fields[ $v ] );
											$columns_data[ $k ]["labels"] = isset( $t2_labels[ $t2->table_fields[ $v ] ] )?$t2_labels[ $t2->table_fields[ $v ] ]:array();
										}
										
										switch( $v ){
										case "modification_date":
										case "creation_date":
											$columns_data[ $k ] = array( "f_key" => $v, "f_id" => $v );
										break;
										}
										
										$fields_data[ $v ] = $k;
									}
									
									unset( $data[0] );
								}
								
								$invalid_records = array();
								
								if( ! $error ){
									
									switch( $action_to_perform ){
									case 'save_direct_update_import_in_background':
										$select = "";
										$where_fields = array();
										$where_condition = "";
											
										switch( $t2->table_name ){
										case "pay_row":
											
											if( ( isset( $fields_data[ "staff_ref" ] ) && isset( $fields_data[ "month" ] ) ) ){
												
												$where_fields[ "staff_ref" ] = $fields_data[ "staff_ref" ];
												$where_fields[ "month" ] = $fields_data[ "month" ];
												
												foreach( $t2->table_fields as $key => $val ){
													if( $select )$select .= ", `".$t2->table_name."`.`".$val."` as '".$key."'";
													else $select = " `".$t2->table_name."`.`id`, `".$t2->table_name."`.`serial_num`, `".$t2->table_name."`.`".$val."` as '".$key."'";
												}
												
											}else{
												$error = "Invalid Staff Ref & Month\n\nTemplate has missing fields";
											}
											
										break;
										}
										
										//$where_fields = $fields_data;
										
										if( empty( $where_fields ) ){
											$error = "Invalid Matching Fields";
										}
										
										if( ! $error ){
											
											$query_settings = array(
												'database'=> $this->class_settings['database_name'],
												'connect' => $this->class_settings['database_connection'],
												'query_type' => 'EXECUTE',
												'set_memcache' => 0,
												'tables' => array( $t2->table_name ),
											);
											
											foreach( $data as $kk => $vv ){
												
												$line = array();
												$u_where = array();
												
												foreach( $where_fields as $cv1 => $ck1 ){
													
													if( isset( $vv[ $ck1 ] ) ){
														
														switch( $cv1 ){
														case "month":
															$_date = convert_date_to_timestamp( $vv[ $ck1 ], 1 );
															
															if( $_date ){
																
																$start_date = mktime( 0, 0, 0, date("n", $_date), 1, date("Y", $_date) );
																$end_date = mktime( 23, 59, 59, date("n", $_date), date("t", $_date), date("Y", $_date) );
																
																$u_where[] = " ( `". $t2->table_fields[ "date" ] ."` BETWEEN " . $start_date . " AND " . $end_date . " ) ";
															}else{
																$error = "Invalid Start Date";
															}
															
														break;
														default:
															if( isset( $t2->table_fields[ $cv1 ] ) ){
																$u_where[] = " `" . $t2->table_fields[ $cv1 ] . "` = '". $vv[ $ck1 ] ."' ";
																
															}
														break;
														}
														
														
													}
													
												}
												
												if( ! empty( $u_where ) ){
													foreach( $columns_data as $ck => $cv ){
														if( isset( $where_fields[ $cv["f_key"] ] ) ){
															continue;
														}
														
														if( isset( $vv[ $ck ] ) && $vv[ $ck ] ){
															
															$lbl = $vv[ $ck ];
															
															if( $lbl && isset( $cv[ "labels" ]["form_field"] ) ){
																switch( $cv[ "labels" ]["form_field"] ){
																case "date-5":
																	$lbl = convert_date_to_timestamp( $lbl );
																break;
																}
															}
															
															$line[] = " `". $cv["f_id"] ."` = '" . $lbl . "' ";
														}
													}
												}
												
												if( ! empty( $line ) ){
													
													$line[] = " `modified_by` = '" . $this->class_settings["user_id"] . "' ";
													$line[] = " `modification_date` = '" . $date . "' ";
													
													switch( $t2->table_name ){
													case "pay_row":
														$query_settings["query"] = "SELECT ". $select ." FROM `".$this->class_settings['database_name']."`.`". $t2->table_name ."` WHERE `record_status` = '1' " . $where_condition . " AND ". implode( " AND ", $u_where );
														
														$query_settings["query_type"] = "SELECT";
														
														$sql = execute_sql_query( $query_settings );
														
														if( isset( $sql[0]["id"] ) ){
															
															$sval = $sql[0];
															
															$u_where = array( " `id` = '". $sql[0]["id"] ."' " );
															
															$sval["calculate"] = 1;
															$sval = __pay_row__calculate_pay( $sval );
															
															$line[] = " `". $t2->table_fields["gross_pay"] ."` = '" . $sval["gross_pay"] . "' ";
															
															$line[] = " `". $t2->table_fields["net_deduction"] ."` = '" . $sval["net_deduction"] . "' ";
															
															$line[] = " `". $t2->table_fields["total_salary"] ."` = '" . ( $sval["gross_pay"] - $sval["net_deduction"] ) . "' ";
														}
														
													break;
													}
													
													$query_settings["query_type"] = "EXECUTE";
													
													$query_settings["query"] = "UPDATE `".$this->class_settings['database_name']."`.`". $t2->table_name ."` SET " . implode(", ", $line ) . " WHERE `record_status` = '1' " . $where_condition . " AND ". implode( " AND ", $u_where );
													execute_sql_query( $query_settings );
													
													$text = "Updated " . $kk;
													echo $text . " \n\n";
											
												}else{
													$invalid_records[ $kk ] = $vv;
													
													$text = "Invalid " . $kk;
													echo $text . " \n\n";
												}
												
											}
										
										}
									break;
									default:
										//print_r($columns_data);
										// echo 'mikeXX';
										// print_r($data);
										foreach( $data as $kk => $vv ){
											
											$line = array();
											$add_line = 0;
											
											foreach( $columns_data as $ck => $cv ){
												$add_col_info = 0;
												
												if( isset( $cv[ "labels" ][ "do_not_import" ] ) && $cv[ "labels" ][ "do_not_import" ] ){
													
												}else if( isset( $t2->basic_data[ "import_template" ] ) ){
													if( isset( $t2->basic_data[ "import_template" ]["fields"][ $cv["f_key"] ] ) ){
														$add_col_info = 1;
													}
												}else{
													$add_col_info = 1;
												}
												//add required field & user confirmation is later updates: refer to cItems_raw_data_import.php & search for [//@req-f implemented]
												
												if( $add_col_info && isset( $vv[ $ck ] ) ){
													
													$lbl = clean2( trim( $vv[ $ck ] ) );
													
													if( $lbl && isset( $cv[ "labels" ]["form_field"] ) ){
														switch( $cv[ "labels" ]["form_field"] ){
														case "date-5time":
														case "date-5":
															$lbl = convert_date_to_timestamp( $lbl );
														break;
														case "currency":
														case "number":
														case "decimal_long":
														case "decimal":
															$lbl = format_and_convert_numbers( $lbl, 3 );
														break;
														case "checkbox":
														case "radio":
														case "multi-select":
														case "select":
															if( ! isset( $tmp_cache[ $cv["f_id"] ]['select_options'] ) ){
																$fd = __get_select_values( array( "labels" => $cv[ "labels" ] ) );
																if( ! empty( $fd ) ){
																	$fd = array_flip( json_decode( strtolower( json_encode( $fd ) ), true ) );
																}
																$tmp_cache[ $cv["f_id"] ]['select_options'] = $fd;
																unset( $fd );
															}
															
															if( isset( $tmp_cache[ $cv["f_id"] ]['select_options'][ strtolower( $lbl ) ] ) ){
																$lbl = $tmp_cache[ $cv["f_id"] ]['select_options'][ strtolower( $lbl ) ];
															}
														break;
														}
													}
													
													$line[ $cv["f_id"] ] = $lbl;
													if( $lbl ){
														$add_line = 1;
													}
												}
											}
											
											if( $add_line && ! empty( $line ) ){
												//default values addition
												//print_r( $t2->basic_data[ "import_default" ] );
												//print_r( $t2->table_fields );
												
												$line["id"] = $new_id . 'd' . ++$new_id_serial;
												$line["record_status"] = '1';
												$line["serial_num"] = null;
												$line["creator_role"] = 'import-' . $id;
												$line["modified_by"] = $this->class_settings["user_id"];
												
												if( isset( $line["creation_date"] ) && $line["creation_date"] ){
													
													$line["creation_date"] = convert_date_to_timestamp( $line["creation_date"] );
												}else{
													$line["creation_date"] = $date;
												}
												
												if( isset( $line["modification_date"] ) ){
													$line["modification_date"] = convert_date_to_timestamp( $line["modification_date"] );
												}else{
													$line["modification_date"] = $date;
												}
												
												
												if( isset( $t2->basic_data[ "import_default" ] ) && ! empty( $t2->basic_data[ "import_default" ] ) ){
													foreach( $t2->basic_data[ "import_default" ] as $kvd => $vvd ){
														if( isset( $t2->table_fields[ $kvd ] ) && isset( $vvd["value"] ) ){
															
															if( ! ( isset( $line[ $t2->table_fields[ $kvd ] ] ) && $line[ $t2->table_fields[ $kvd ] ] ) ){
																if( isset( $vvd["function"] ) && function_exists( $vvd["function"] ) ){
																	$fn = $vvd["function"];
																	$line[ $t2->table_fields[ $kvd ] ] = $fn();
																}else{
																	$line[ $t2->table_fields[ $kvd ] ] = $vvd["value"];
																}
															}
															
														}else if( isset( $t2->table_fields[ $kvd ] ) && isset( $vvd["reuse_value"] ) ){
															
															if( isset( $line[ $vvd["reuse_value"] ] ) ){
																$line[ $t2->table_fields[ $kvd ] ] = $line[ $vvd["reuse_value"] ];
															}else if( isset( $t2->table_fields[ $vvd["reuse_value"] ] ) && isset( $line[ $t2->table_fields[ $vvd["reuse_value"] ] ] ) ){
																$line[ $t2->table_fields[ $kvd ] ] = $line[ $t2->table_fields[ $vvd["reuse_value"] ] ];
															}
															
														}
													}
												}
												
												
												if( isset( $t2->table_fields[ "store" ] ) && ! ( isset( $line[ $t2->table_fields[ "store" ] ] ) && $line[ $t2->table_fields[ "store" ] ] ) ){
													$line[ $t2->table_fields[ "store" ] ] = $store;
												}
												
												if( isset( $t2->table_fields[ "currency" ] ) && ! ( isset( $line[ $t2->table_fields[ "currency" ] ] ) && $line[ $t2->table_fields[ "currency" ] ] ) ){
													$line[ $t2->table_fields[ "currency" ] ] = $currency;
												}
												
												if( isset( $t2->table_fields[ "exchange_rate" ] ) && ! ( isset( $line[ $t2->table_fields[ "exchange_rate" ] ] ) && $line[ $t2->table_fields[ "exchange_rate" ] ] ) ){
													$line[ $t2->table_fields[ "exchange_rate" ] ] = 1;
												}
												
												//print_r( $line );
												
												$data_to_load[] = $line;
											}else{
												//only lines that are not blank
												if( $add_line ){
													$invalid_records[ $kk ] = $vv;
												}
											}
											
										}
										unset( $tmp_cache );
										unset( $data );
										
										if( ! empty( $data_to_load ) ){
											//print_r( $data_to_load ); exit;
											$total = count( $data_to_load );
											create_folder( $this->class_settings["calling_page"] . 'tmp/import', '', '' );
											create_folder( $this->class_settings["calling_page"] . 'tmp/import/' . $t2->table_name, '', '' );

											
											
											if( defined("NWP_SECURE_FILE_PRIV") && NWP_SECURE_FILE_PRIV ){
												$filename = NWP_SECURE_FILE_PRIV . $t2->table_name . '-'. $id .'.txt';
												if( file_exists( $filename ) ){
													unlink( $filename );
												}
												file_put_contents( $filename , '' );
											}else{
												$filename = $this->class_settings["calling_page"] . 'tmp/import/' . $t2->table_name . '/data-load'. $id .'.txt';
												if( file_exists( $filename ) ){
													unlink( $filename );
												}
												file_put_contents( $filename , '' );
											}
											
											
											$saved = mysqlBulk( $data_to_load, $t2->table_name, 'loaddata', array( 
												"link_identifier" => $this->class_settings["database_connection"],
												"database" => $this->class_settings["database_name"],
												"trigger_notices" => false,
												"eat_away" => true,
												"in_file" => realpath( $filename ),
											) );
											
											$t2->class_settings["overide_select"] = " COUNT(`id`) as 'count' ";
											$t2->class_settings["where"] = " AND `".$t2->table_name."`.`creator_role` = '". 'import-' . $id ."' ";
											$rc = $t2->_get_records();
											
											if( file_exists( $filename ) ){
												unlink( $filename );
											}
											
											if( isset( $rc[0]["count"] ) && intval( $rc[0]["count"] ) == $total ){
												
												if( method_exists( $t2, "_import_callback" ) ){
													$t2->_import_callback( array( "run_in_bg" => $run_in_bg, "creator_role" => 'import-' . $id ) );
												}
												
												$text = "Finished Loading " . $total;
												$run_in_bg_log .= $text;
												echo $text . " \n\n";
												
												$settings = array(
													'cache_key' => $cache_key . "-bg-process-" . $id,
													'directory_name' => $cache_key,
													'cache_values' => array(
														"success" => 1,
														"clear" => 1,
														"log" => $run_in_bg_log,
													),
													'permanent' => true,
												);
												set_cache_for_special_values( $settings );
												//clear_cache_for_special_values( $settings );
												
												/*
												//prepare & display summary info
												$cache_key = $this->table_name;
												
												$settings = array(
													'cache_key' => $cache_key . '-data-' . $id,
													'cache_values' => $data_to_load,
													'directory_name' => $cache_key,
													'permanent_only' => 1,
													'permanent' => true,
												);
												set_cache_for_special_values( $settings );
												
												//wait for user confirmation b4 proceeding
												$filename = 'user-validation-form.php';
												
												$ed["save_table"] = $save_table;
												$ed["table"] = $this->table_name;
												$ed["count"] = count( $data_to_load );
												$ed["id"] = $id;
												$ed["invalid_data"] = $invalid_records;
												$ed["data"] = $valid_loan_types;
												$ed["html_replacement_selector"] = $handle;
												
												$this->class_settings[ 'data' ] = $ed;
												$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
												$returning_html_data = $this->_get_html_view();
												
												$return = array();
												
												$return["status"] = "new-status";						
												$return["html_replacement_selector"] = "#" . $handle;
												$return["html_replacement"] = $returning_html_data;
												$return["javascript_functions"] = array( "set_function_click_event", "prepare_new_record_form_new" );
												
												return $return;
												*/
												return 1;
											}else{
												$error = "Only Loaded " . ( isset( $rc[0]["count"] )?number_format( intval( $rc[0]["count"] ) , 0 ):0 ) . " out of " . number_format( $total, 0 );
												
												$error .= "<br />";
												$error .= "Operation has been reversed & all loaded records deleted";
												
												$query = "DELETE FROM `". $this->class_settings[ 'database_name' ] ."`.`". $t2->table_name ."` WHERE `".$t2->table_name."`.`creator_role` = '". 'import-' . $id ."' ";
												$query_settings = array(
													'database' => $this->class_settings['database_name'] ,
													'connect' => $this->class_settings['database_connection'] ,
													'query_type' => 'EXECUTE',
													'set_memcache' => 1,
													'tables' => array( $t2->table_name ),
												);
												$query_settings["query"] = $query;
												execute_sql_query( $query_settings );	
											}
										}else{
											if( $run_in_bg ){
												$text = 'No Valid Data Found';
												$run_in_bg_log .= $text;
												echo $text;
											}
										}
										
									break;
									}
								}
								
								if( $error ){
									if( $run_in_bg ){
										echo $error;
										$run_in_bg_log .= $error;
									}
								}
								
							}else{
								if( $run_in_bg ){
									$text = 'No Data Found in excel file';
									$run_in_bg_log .= $text;
									echo $text;
								}
							}
							
						}else{
							if( $run_in_bg ){
								$text = "Invalid Class " . $t1;
								
								$run_in_bg_log .= $text;
								echo $text;
							}
						}
					}
					
				}
				
				
				$settings = array(
					'cache_key' => $cache_key . "-bg-process-" . $id,
					'directory_name' => $cache_key,
					'cache_values' => array(
						"clear" => 1,
						"log" => $run_in_bg_log,
					),
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				//clear_cache_for_special_values( $settings );
			break;
			case 'check_bg_operation':
				sleep( 5 );
				
				$settings = array(
					'cache_key' => $cache_key . "-bg-process-" . $id,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cid = get_cache_for_special_values( $settings );
				
				$return['status'] = 'new-status';
				
				
				if( ( isset( $cid["clear"] ) && $cid["clear"] ) || ! $cid ){
					if( ( isset( $cid["clear"] ) && $cid["clear"] ) ){
						$settings = array(
							'cache_key' => $cache_key . "-bg-process-" . $id,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						clear_cache_for_special_values( $settings );
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
					
					$return["html_replacement_selector"] = '#excel-file-import-progress-list';
					$return["html_replacement"] = $return["html"];
					unset( $return["html"] );
					
				}else{
					$return['html_prepend_selector'] = '#excel-file-import-progress-list';
					$return['html_prepend'] = '<li>Loading in progress. Last check at ' . date("d-M-Y H:i") . '</li>';
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 1;
					$return['id'] = $id;
					$return['post_id'] = $id;
					$return['action'] = '?action='.$this->table_name.'&todo=check_bg_operation';
				}
				
			break;
			}
			
			return $return;
		}
		
		private function _save_and_proceed_with_import(){
			//SAVE AND PROCEED WITH IMPORT PROCESS
			/*----------------------------------*/
			$import_table = 'items_raw_data_import';
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$this->class_settings[ 'current_record_id' ] = $_POST['id'];
				
				//print_r( $this->_get_import_items() );
				//print_r( $this->table_fields );
				$this->_get_table_data( $this->_get_import_items() );
				//print_r( $this->table_fields );
				//exit;
			}
			
			switch( $action_to_perform ){
			case 'save_direct_update_import':
				
				if( ! ( isset( $_POST[ $this->table_fields["import_template_type"] ] ) && $_POST[ $this->table_fields["import_template_type"] ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = 'Invalid Template';
					return $err->error();
				}
				
			break;
			}
			

			switch( $action_to_perform ){
			case 'save_direct_update_import':
			case 'save_direct_import':
			case 'save_direct_update_import':
				if( ! ( isset( $_POST[ $this->table_fields["file"] ] ) && $_POST[ $this->table_fields["file"] ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Invalid Excel / CSV File</h4>Please select the source excel / csv file';
					return $err->error();
				}
			break;
			}
			
			$pt = $_POST;
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$settings = array(
					'cache_key' => $this->table_name.'-p-data-'.$return['saved_record_id'],
					'cache_values' => $pt,
					'directory_name' => $this->table_name,
				);
				set_cache_for_special_values( $settings );
				
				switch( $action_to_perform ){
				case 'save_update_import':
					
					$return['status'] = 'new-status';
					unset( $return['html'] );
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-update-from-excel.php' );
					$this->class_settings[ 'data' ]["event"] = $this->class_settings[ 'current_record_details' ];
					
					$return['html_replacement_selector'] = '#excel-import-form-container';
					$return['html_replacement'] = $this->_get_html_view();
				break;
				case 'save_direct_update_import':
				case 'save_direct_import':
					$return['status'] = 'new-status';
					$return['err'] = 'Loading Data &darr;';
					$return['msg'] = 'Please wait...';
					
					//Display Update
					unset( $return['html'] );
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-started.php' );
					$this->class_settings[ 'data' ] = array(
						'current_record_details' => $this->class_settings[ 'current_record_details' ], 
						'import_progress' => array( 
							0 => array( 'title' => 'Loading Data' ),
							1 => array( 'title' => 'Running background process for data loading...' ),
						),
					);
					
					$return['html_replacement_selector'] = '#excel-import-form-container';
					$return['html_replacement'] = $this->_get_html_view();
					
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => $action_to_perform . '_in_background', 
							"user_id" => $this->class_settings["user_id"], 
							"no_session" => 1,
							"reference" => $return['saved_record_id'],
						) 
					);
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = $return['saved_record_id'];
					$return['action'] = '?action='. $this->table_name .'&todo=check_bg_operation';
					
				break;
				default:
					$return['status'] = 'new-status';
					$return['err'] = 'Storing Mapped Columns &darr;';
					$return['msg'] = 'Please wait...';
					
					//Display Update
					unset( $return['html'] );
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
					$this->class_settings[ 'data' ] = array(
						'import_progress' => array( 
							0 => array( 'title' => 'Storing Mapped Columns' ),
							1 => array( 'title' => 'Successfully Stored Mapped Columns' ),
						),
					);
					
					$return['html_prepend_selector'] = '#excel-file-import-progress-list';
					$return['html_prepend'] = $this->_get_html_view();
					
					//settings for ajax request reprocessing
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-'.md5( $this->table_name );
					$return['id'] = $return['saved_record_id'];
					$return['post_id'] = $return['id'];
					$return['action'] = '?action='.$import_table.'&todo=extract_line_items_data';
					
				break;
				}
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		private function _get_last_mapping_data( $table_name = "" ){
			//RETURN CODES OF EXISTING LINE ITEMS FOR EACH MONTH
			if( ! ( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id']) )
				return array();
			
			if( ! $table_name )$table_name = $this->table_name;
			
			$csettings = array(
				'cache_key' => 'stored-mapping-' . $table_name . $this->class_settings['user_id'],
				'permanent' => true,
				'directory_name' => $this->table_name,
			);
			$import_map_id = get_cache_for_special_values( $csettings );
			
			if( $import_map_id ){
				if( isset( $this->class_settings['current_record_id'] ) )
					$keep = $this->class_settings['current_record_id'];
				
				$this->class_settings['current_record_id'] = $import_map_id;
				$import_details = $this->_get_import_items();
				
				if( isset( $keep ) )
					$this->class_settings['current_record_id'] = $keep;
				
				if( isset( $import_details["id"] ) && $import_details["id"] )
					return $import_details;
			}
		}
		
		private function _get_table_data( $d = array() ){
			if( ! isset( $d["import_template_type"] ) )return 0;
			
			$cl = $d["import_template_type"];
			
			$tbd = explode( ':::' , $d["import_template_type"] );
			$tba = array();
			if( ! empty( $tbd ) ){
				foreach( $tbd as $tbv ){
					$tba2 = explode( '=', $tbv );
					if( isset( $tba2[1] ) && $tba2[1] ){
						$tba[ $tba2[0] ] = $tba2[1];
					}
				}
			}
			$tb_type = isset( $tba["type"] )?$tba["type"]:'';
			$tb = isset( $tba["value"] )?$tba["value"]:'';
				
			switch( $tb_type ){
			case "plugin":
				
				if( isset( $tba["key"] ) && $tba["key"] ){
					$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
					if( class_exists( $clp ) ){
						$ptb = new $clp();
						$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
						if( isset( $in[ $tb ]->table_name ) ){
							$t = $in[ $tb ];
							$table = $tb;
						}else{
							$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
						}
					}else{
						$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
					}
				}else{
					$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
				}
				
			break;
			default:
				$cls = 'c' . ucwords( $tb );
				if( class_exists( $cls ) ){
					$t = new $cls();
					$table = $tb;
				}else{
					$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
				}
			break;
			}
				
			$r = $table();
			//$cls = 'c'.ucwords( $cl );
			$class = $t;
			
			$tf = $this->table_fields;
			$this->table_fields = array();
			
			foreach( $tf as $k => $v ){
				switch( $k ){
				case 'budget_code':
				case 'department':
				case 'month':
				case 'unit':
				case 'import_template_type':
				case 'operator':
				case 'file':
				case 'starting_row':
					$this->table_fields[ $k ] = $v;
				break;
				}
			}
			
			$serial = 8;
			//print_r($class->table_fields); exit;
			foreach( $class->table_fields as $key => $val ){
				if( isset( $r[ $val ] ) ){
					
					//print_r( $r[ $val ] );
					if( isset( $r[ $val ][ "do_not_import" ] ) && $r[ $val ][ "do_not_import" ] ){
						$this->class_settings['hidden_records'][ $val ] = 1;
					}else{
						
						++$serial;
						if( $serial < 10 )$vkey = 'import_items00'.$serial;
						else $vkey = 'import_items0'.$serial;
						
						$this->table_fields[ $key ] = $vkey;
						
						$this->class_settings["attributes"]["custom_form_fields"]['field_ids'][] = $key;
						$this->class_settings["attributes"]["custom_form_fields"]['form_label'][ $key ] = array(
							'field_label' => $r[ $val ][ "field_label" ],
							'form_field' => 'number',
							'class' => ' column-select-field ',
							'attributes' => ' col-label="'. $r[ $val ][ "field_label" ] .'" ',
							
							'display_position' => 'display-in-table-row',
							'serial_number' => ( 100 + $serial ),
						);
						
						if( isset( $this->class_settings['hidden_records'][ $vkey ] ) ){
							unset( $this->class_settings['hidden_records'][ $vkey ] );
						}
						
						if( isset( $this->class_settings['hidden_records_css'][ $vkey ] ) ){
							unset( $this->class_settings['hidden_records_css'][ $vkey ] );
						}
						
					}
				}
			}
			//print_r($this->table_fields); exit;
			define( "IMPORT_TEMPLATE_TYPE", $cl );
		}
		
		private function _get_field_mapping_form(){
			//ALLOW USERS SELECT COLUMNS FOR DATA EXTRACTION
			/*--------------------------------------------*/
			
			$table = "items";
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$this->class_settings[ 'current_record_id' ] = $_POST['id'];
				$this->class_settings[ 'current_record_details' ] = $this->_get_import_items();
			}
			
			$d = $this->class_settings[ 'current_record_details' ];
			if( isset( $d["import_template_type"] )  ){
				//&& $d["import_template_type"]
				//echo $d["import_template_type"]; exit;
				$tbd = explode( ':::' , $d["import_template_type"] );
				$tba = array();
				if( ! empty( $tbd ) ){
					foreach( $tbd as $tbv ){
						$tba2 = explode( '=', $tbv );
						if( isset( $tba2[1] ) && $tba2[1] ){
							$tba[ $tba2[0] ] = $tba2[1];
						}
					}
				}
				$tb_type = isset( $tba["type"] )?$tba["type"]:'';
				$table2 = isset( $tba["value"] )?$tba["value"]:$d["import_template_type"];
				$datasource = $table2;
				
				$previous_mapping = $this->_get_last_mapping_data( $table2 );
				
				switch( $table2 ){
				case "stores":
				case "users":
				case "contribution":
				case "vendors":
				case "site_users":
				case "pay_row":
				case "items":
				case "customers":
				case "hmo_coverage":
				case "assets":
				default:
					
					foreach( $this->table_fields as $k => $v ){
						switch( $k ){
						case 'starting_row':
						break;
						default:
							$this->class_settings['hidden_records'][ $v ] = 1;
						break;
						}
					}
					
					//$this->table_fields = array();
					$this->_get_table_data( $d );
					
					if( is_array( $previous_mapping ) && ! empty( $previous_mapping ) ){
						foreach( $previous_mapping as $k => $v ){
							if( isset( $this->table_fields[$k] ) )$this->class_settings[ 'form_values_important' ][ $this->table_fields[$k] ] = $v;
						}
					}
				break;
				case 'de':
					foreach( $this->table_fields as $k => $v ){
						switch( $k ){
						case 'starting_row':
						case 'code_column':
						case 'description_column':
						case 'weight_column':
						case 'cost_price_column':
						case 'selling_price_column':
						case 'color_column':
						case 'percentage_markup_column':
						case 'category_column':
						case 'vendor_column':
						case 'currency_column':
						case 'quantity_column':
						case 'barcode_column':
						case 'unit_column':
						case 'unit_of_measure_column':
						break;
						default:
							$this->class_settings['hidden_records'][ $v ] = 1;
						break;
						}
						
						if( isset( $this->class_settings['hidden_records'][ $v ] ) && $this->class_settings['hidden_records'][ $v ] ){
							continue;
						}
						
						if( isset( $previous_mapping[ $k ] ) && $previous_mapping[ $k ] ){
							$this->class_settings[ 'form_values_important' ][ $v ] = $previous_mapping[ $k ];
						}
					}
				break;
				}
			}
			
			
			unset( $this->class_settings[ 'form_values_important' ][ $this->table_fields[ 'starting_row' ] ] );
			
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			/*
			$this->class_settings[ 'form_values_important' ] = array(
				$this->table_fields["budget_code"] => $budget_id,
				$this->table_fields["import_template_type"] => $import_type,
			);
			*/
			
			
			$this->class_settings['hide_clear_form_button'] = 1;
			$this->class_settings['do_not_show_headings'] = 1;
			$this->class_settings['form_submit_button'] = 'Continue &rarr;';
			$this->class_settings['form_action_todo'] = 'save_and_proceed_with_import';
			
			//print_r( $this->_generate_new_data_capture_form()["html"] ); exit;
			return $this->_generate_new_data_capture_form();
		}
		
		private function _import_items(){
			//IMPORT WIZARD
			/*-----------*/
			$budget_id = '';
			$month = 0;
			if( isset( $_GET['month'] ) && $_GET['month'] ){
				$month = $_GET['month'];
			}
			if( isset( $_GET['budget'] ) && $_GET['budget'] ){
				$budget_id = $_GET['budget'];
			}
			
			if( isset( $_GET['budget_id'] ) && $_GET['budget_id'] ){
				$budget_id = $_GET['budget_id'];
			}
			
			$mapped_values = array(
				'import_budget' => 'operator-budget',
				'import_napims_budget' => 'napims-budget',
				'import_items' => 'operator-cash-calls',
				'import_returns' => 'operator-performance-returns',
				
				'import_napims_cash_calls' => 'napims-cash-calls',
				'import_napims_returns' => 'napims-performance-returns',
				
				'import_tendering' => 'tendering',
				'import_tendering_status_updates' => 'tendering-status-update',
			);
			
			$import_type = "";
			if( isset( $mapped_values[ $this->class_settings['action_to_perform'] ] ) )
				$import_type = $mapped_values[ $this->class_settings['action_to_perform'] ];
				
			$this->class_settings[ 'form_values_important' ] = array(
				$this->table_fields["budget_code"] => $budget_id,
				$this->table_fields["import_template_type"] => $import_type,
			);
			
			switch( $this->class_settings['action_to_perform'] ){
			case 'import_items':
			case 'import_returns':
				//$this->class_settings['hidden_records'][ $this->table_fields["operator"] ] = 1;
				//$this->class_settings['hidden_records'][ $this->table_fields["unit"] ] = 1;
				$this->class_settings["recent_activity_type"] = "items";
			break;
			}
			
			$this->class_settings["import_type"] = get_select_option_value( array( 'id' => $import_type, 'function_name' => "get_import_template_types" ) );
			
			if( $budget_id && $budget_id != "-" ){
				//$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["budget_code"] ] = 1;
				$this->class_settings["operator_text"] = get_select_option_value( array( 'id' => $budget_id, 'function_name' => "get_all_budgets" ) );
			}
			
			if( $import_type ){
				//$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["import_template_type"] ] = 1;
			}
			
			if( ! defined( "SHOW_ALL_DEPARTMENTS" ) ){
				define( "SHOW_ALL_DEPARTMENTS", 1 );
			}
			
			if( ! defined( "SHOW_NO_UNITS" ) ){
				define( "SHOW_NO_UNITS", 1 );
			}
			
			$return = $this->_generate_excel_import_form();
			
			$return["html_replacement"] = $return["html"];
			unset( $return["html"] );
			$return["html_replacement_selector"] = "#main-table-view";
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}
		
		public function _import_excel_file_data( $options = array() ){
			//IMPORT INTO CASH CALLS RAW DATA TABLE & TRIGGER CREATION OF CACHED VIEW FOR IMPORTED DATA
			/*---------------------------------------------------------------------------------------*/
			$cache_key = '';
			$files = '';
			$import_table = 'items_raw_data_import';

			if( isset( $options[ 'file' ] ) && $options[ 'file' ] ){
				$files = $options[ 'file' ];
			}else{
				if( isset( $_POST['id'] ) && $_POST['id'] ){
					
					$this->class_settings[ 'current_record_id' ] = $_POST['id'];
					$d = $this->class_settings[ 'current_record_details' ] = $this->_get_import_items();

					$files = isset( $d[ 'file' ] ) ? $d[ 'file' ] : '';
				}
			}

			if( $files ){
				$files = explode( ':::', $files );
				// print_r( $_POST[ 'id' ] );exit;

				/*
				$use_php_excel_library = 1;	//old - to use php excel and load into temporary database table
				$return_data = 0;	//old - to use php excel and load into temporary database table
				*/
				
				$use_php_excel_library = 0;
				$return_data = 1;
				
				$callback_action = '';
				$callback_todo = '';
				
				switch( $this->class_settings["action_to_perform"] ){
				case "import_excel_file_data2":
					$use_php_excel_library = 0;
					
					
					if( isset( $_GET["callback_action"] ) && isset( $_GET["callback_todo"] ) ){
						$return_data = 1;
						$callback_action = $_GET["callback_action"];
						$callback_todo = $_GET["callback_todo"];
					}
					
				break;
				}

				$cp = $this->class_settings[ 'calling_page' ];
				if( defined("NWP_FILES_PATH") && NWP_FILES_PATH ){
					$apdir = ( defined("NWP_APP_DIR")?NWP_APP_DIR:'app' ) . '/';
					$cp = NWP_FILES_PATH . $apdir . '/';

					if( defined( 'NWP_ORIGIN_PATH' ) && NWP_ORIGIN_PATH ){
						$cp = NWP_ORIGIN_PATH . '/' . $cp;
					}
				}
				
				// print_r( $cp );
				// print_r( $files );
				foreach( $files as $file ){
					if( $file && file_exists( $cp . $file ) ){
						$this->class_settings['excel_reference_id'] = $_POST['id'];
						$this->class_settings['excel_file_name'] = $cp . $file;
						$this->class_settings['import_table'] = $import_table;
						$this->class_settings['excel_field_mapping_option'] = 'serial-import';
						$this->class_settings['update_existing_records_based_on_fields'] = '';
						$this->class_settings['keep_excel_file_after_import'] = 1;
						$this->class_settings['accept_blank_excel_cells'] = 1;
						
						$myexcel = new cMyexcel();
						$myexcel->class_settings = $this->class_settings;
						$myexcel->class_settings[ 'action_to_perform' ] = 'bulk_import_excel_file_data';
						$myexcel->class_settings[ 'use_php_excel_library' ] = $use_php_excel_library;
						$myexcel->class_settings['return_data'] = $return_data;
						$excel = $myexcel->myexcel();
								// print_r( '$data' ); exit;
						
						if( isset( $options["return_data"] ) && $options["return_data"] ){
							return $excel;
						}
						
						if( $return_data ){
							//when data is returned for custom processing
							$cache_key = $this->table_name;
							
							$settings = array(
								'cache_key' => $cache_key . '-data-' . $this->class_settings[ 'current_record_id' ],
								'cache_values' => $excel,
								'directory_name' => $cache_key,
								'permanent_only' => 1,
								'permanent' => true,
							);
							
							set_cache_for_special_values( $settings );
							
						}else{
							//when data is not being returned
							if( ! ( isset( $excel['typ'] ) && $excel['typ'] == 'saved' ) ){
								//Return excel import error
								return $excel;
							}
						}
					}else{
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
						$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
						$err->additional_details_of_error = 'Invalid Excel File for Import';
						return $err->error();
					}
				}
					
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Extracting Data from Excel File &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Opened Excel File ' . ( memory_get_usage(true) / 1024 / 1024) . ' MB' ),
						1 => array( 'title' => 'Checked total number of columns in Excel File' ),
						2 => array( 'title' => 'Data Extraction Routines Started' ),
						3 => array( 'title' => 'Data Loading Routines Started' ),
						4 => array( 'title' => 'Data Successfully Loaded' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $this->class_settings[ 'current_record_id' ];
				$return['post_id'] = $return['id'];
				
				if( $callback_action && $callback_todo ){
					$return['action'] = '?action='. $callback_action .'&todo=' . $callback_todo . '&html_replacement_selector=excel-file-import-progress-list&cache_key=' . $cache_key;
				}else{
					//settings for ajax request reprocessing
					if( $return_data ){
						$return['action'] = '?action='.$import_table.'&todo=map_excel_columns';
					}else{
						//old method when loading into temporary table
						$return['action'] = '?action='.$import_table.'&todo=create_cached_view_for_imported_records';
					}
					
				}
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		private function _save_and_start_operator_excel_import(){
			//SAVE EXCEL FILE IN DATABASE & TRIGGER IMPORT INTO CASH CALLS RAW DATA TABLE
			/*-------------------------------------------------------------------------*/
			if( ! ( isset( $_POST[ $this->table_fields["file"] ] ) && $_POST[ $this->table_fields["file"] ] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Excel / CSV File</h4>Please select the source excel / csv file';
				return $err->error();
			}
			$return = $this->_save_changes();
			
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				
				$return['status'] = 'new-status';
				$return['err'] = 'Excel Import Started &darr;';
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-started.php' );
				$this->class_settings[ 'data' ] = array(
					'current_record_details' => $this->class_settings[ 'current_record_details' ],
				);
				
				$return['html_replacement_selector'] = '#excel-import-form-container';
				$return['html_replacement'] = $this->_get_html_view();
				
				$todo = 'import_excel_file_data';
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'save_and_start_import_with_callback':
					
					$todo = 'import_excel_file_data2';
					
					if( isset( $_GET["callback_action"] ) && isset( $_GET["callback_todo"] ) ){
						$todo .= '&callback_action=' . $_GET["callback_action"] . '&callback_todo=' . $_GET["callback_todo"];
					}
					
					/*
					$todo = 'track_and_display_background_updates';
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => "import_excel_file_data2", 
							"user_id" => $this->class_settings["user_id"], 
							"reference" => $return['saved_record_id'], 
							//"show_window" => 1, 
						) 
					);
					*/
				break;
				}
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $return['saved_record_id'];
				$return['post_id'] = $return['id'];
				$return['action'] = '?action='.$this->table_name.'&todo='.$todo;
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		private function _generate_excel_import_form(){
			//GENERATE EXCEL IMPORT FORM FOR CASH CALLS, RETURNS, BUDGET
			/*---------------------------------------------------------*/
			$action_to_perform = $this->class_settings["action_to_perform"];

			switch( $action_to_perform ){
			case 'save_pre_import':
				if( isset( $_GET["import_step"] ) && $_GET["import_step"] && isset( $_POST[ $this->table_fields["import_template_type"] ] ) && $_POST[ $this->table_fields["import_template_type"] ] ){
					$action_to_perform = $_GET["import_step"];
					$_GET["table"] = $_POST[ $this->table_fields["import_template_type"] ];
					
					$this->class_settings["action_to_perform"] = $action_to_perform;
				}else{
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = 'Invalid Import Template';
					return $err->error();
				}
			break;
			}
			
			switch( $action_to_perform ){
			case 'excel_import_form_options_map':
				$action_to_perform = 'excel_import_form_options';
				$_POST[ 'id' ] = 'map';
			break;
			case 'excel_import_form_options_nomap':
				$action_to_perform = 'excel_import_form_options';
				$_POST[ 'id' ] = 'no-map';
			break;
			case 'pre_import':
				$_POST["id"] = $action_to_perform;
				/*
				#perform these settings on mysql 8
				#add www-data user to mysql group
				sudo adduser www-data mysql
				#check dir of SHOW VARIABLES LIKE 'secure_file_priv'; & set permission to enable group access
				sudo chmod 770 /var/lib/mysql-files/
				#test grp access
				sudo runuser -u www-data -- ls /var/lib/mysql-files/
				sudo runuser -u www-data -- cp /var/www/html/snh.hyella.com.ng/public_html/engine/tmp/import/assets/data-loadims3u3100066441.txt /var/lib/mysql-files/a.txt

				*/
				
				//DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`
				//$query = "SHOW VARIABLES LIKE 'local_infile'";
				/*
				GRANT ALL ON hyella_business_manager_test.* TO 'hyella'@'localhost';
		GRANT FILE ON *.* to 'hyella'@'localhost';
		FLUSH PRIVILEGES;
		
				SHOW VARIABLES LIKE 'local_infile';
				SHOW VARIABLES LIKE 'secure_file_priv';
				
				sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"
				sql_mode = ""
				
				sudo nano /etc/mysql/my.cnf (or in version 8: sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf)
				The content of the .my.cnf file is as follows:
				[mysqld]
				secure_file_priv = ""
				sudo service mysql restart
				
				https://sebhastian.com/mysql-fix-secure-file-priv-error/
				
				local-infile=1
				*/
				
				/*	mysqli_options($this->class_settings['database_connection'],MYSQLI_OPT_LOCAL_INFILE, true );
				
				
				$query = "LOAD DATA INFILE '/var/www/html/demo/emr-v1.3/engine/tmp/import/vendors/data-loadims2965537271.txt' INTO TABLE `demo_emr_2021`.`vendors` FIELDS TERMINATED BY '::::,' LINES TERMINATED BY '^^^^' (vendors001, vendors006, vendors002, vendors003, vendors004, vendors005, vendors007, vendors008, vendors009, vendors010, vendors011, vendors012, vendors013, vendors014, vendors015, vendors016, vendors017, id, record_status, serial_num, creator_role, modified_by, creation_date, modification_date);";
				//$query = "SET GLOBAL local_infile = 1;";
				$query_settings = array(
					'database'=>$this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					//'query_type' => 'SELECT',
					'query_type' => 'EXECUTE',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				print_r( $sql_result ); exit; */
			break;
			}
			
			$handle1 = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle1 = $this->class_settings[ 'html_replacement_selector' ];
			}
			$handle = "#" . $handle1;
			
			$view = isset( $this->class_settings["import_option"] )?$this->class_settings["import_option"]:'';
			$title = 'Import Bulk Data';
			$datasource = '';
			$terms = array();
			
			switch( $action_to_perform ){
			case 'pre_import':
			case "excel_import_form_options":
				$option = isset( $_POST["id"] )?$_POST["id"]:'';
				
				foreach( $this->table_fields as $k => $v ){
					switch( $k ){
					case 'file':
					case 'import_template_type':
					break;
					default:
						$this->class_settings['hidden_records'][ $v ] = 1;
					break;
					}
				}
				
				$this->class_settings['hide_clear_form_button'] = 1;
				
				switch( $option ){
				case 'pre_import':
					$this->class_settings['hidden_records'][ $this->table_fields["file"] ] = 1;
					
					$this->class_settings['form_heading_title'] = '';
					if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
						$title = 'Step 1: ' . rawurldecode( $_GET["menu_title"] );
					}
					
					$this->class_settings['form_submit_button'] = 'Proceed to Step 2 &rarr;';
					$this->class_settings['form_action_todo'] =  'save_pre_import&import_step=' . ( isset( $_GET["step"] )?$_GET["step"]:'' ) . '&html_replacement_selector=' . $handle1;
					$i1 = $this->_generate_new_data_capture_form();
				break;
				default:
					
					$table = isset( $_GET["table"] )?$_GET["table"]:'';
					if( $table ){
						$it = get_class_names();
						if( isset( $it[ $table ] ) ){
							$this->class_settings['form_values_important'][ $this->table_fields["import_template_type"] ] = $table;
							$this->class_settings['hidden_records_css'][ $this->table_fields["import_template_type"] ] = 1;
							
							$title .= ': ' . $it[ $table ];
							$datasource = $it[ $table ];
						}
					}
					
					switch( $option ){
					case "no-map":
						$this->class_settings['form_heading_title'] = '<h4>Import without Mapping &darr;</h4>';
						if( ! isset( $this->class_settings['form_submit_button'] ) )$this->class_settings['form_submit_button'] = 'Start Direct Import &rarr;';
						$this->class_settings['form_action_todo'] = 'save_direct_import';
						$i1 = $this->_generate_new_data_capture_form();
					break;
					case "no-map-update":
						$this->class_settings['form_heading_title'] = '<h4>Update from Excel without Mapping &darr;</h4>';
						if( ! isset( $this->class_settings['form_submit_button'] ) )$this->class_settings['form_submit_button'] = 'Start Update &rarr;';
						$this->class_settings['form_action_todo'] = 'save_direct_update_import';
						$i1 = $this->_generate_new_data_capture_form();
						
					break;
					default:
						$this->class_settings['form_heading_title'] = '<h4>Select Excel File & Start Import &darr;</h4>';
						$this->class_settings['form_submit_button'] = 'Start Import &rarr;';
						$this->class_settings['form_action_todo'] = 'save_and_start_operator_excel_import';
						
						switch( $option ){
						case "callback":
							$this->class_settings['form_action_todo'] = 'save_and_start_import_with_callback';
							if( isset( $_GET["callback_action"] ) && $_GET["callback_action"] ){
								$this->class_settings['form_action_todo'] .= '&callback_action=' . $_GET["callback_action"];
							}
							
							if( isset( $_GET["callback_todo"] ) && $_GET["callback_todo"] ){
								$this->class_settings['form_action_todo'] .= '&callback_todo=' . $_GET["callback_todo"];
							}
						break;
						}
						
						if( isset( $this->class_settings["custom_form_action_todo"] ) && $this->class_settings["custom_form_action_todo"] ){
							$this->class_settings['form_action_todo'] = $this->class_settings["custom_form_action_todo"];
						}
						$i1 = $this->_generate_new_data_capture_form();
					break;
					}
					
					$table2 = $table;
					if( $table ){
						$tbd = explode( ':::' , $table );
						$tba = array();
						if( ! empty( $tbd ) ){
							foreach( $tbd as $tbv ){
								$tba2 = explode( '=', $tbv );
								if( isset( $tba2[1] ) && $tba2[1] ){
									$tba[ $tba2[0] ] = $tba2[1];
								}
							}
						}
						$tb_type = isset( $tba["type"] )?$tba["type"]:'';
						$table2 = isset( $tba["value"] )?$tba["value"]:'';
						$datasource = $table2;
					}
					
					if( $datasource ){
						$terms["Data Source"] = $datasource;
					}
					
					$terms["Step 1 - Learn How to Import"] = '<a href="#" class="btn dark btn-sm custom-single-selected-record-button " override-selected-record="'. ( $table?$table:1 ) .'" action="?action='.$this->table_name.'&todo=how_to_import_no_map&html_replacement_selector=how-to-import-guide&dt=1">How to Import Guide</a>';
					
					$file = 'files/resource_library/'. $table2 .'-import-template.csv';
					if( $datasource && file_exists( $this->class_settings["calling_page"] . $file ) ){
						$terms["Step 2 - Download Import Template"] = '<a href="'. $this->class_settings[ 'project_data' ]["domain_name"] . $file .'" class="btn dark btn-sm" target="_blank">CSV File</a>';
					}
					
					
					$file = 'files/resource_library/'. $table2 .'-import-template.xls';
					if( $datasource && file_exists( $this->class_settings["calling_page"] . $file ) ){
						if( isset( $terms["Step 2 - Download Import Template"] ) && $terms["Step 2 - Download Import Template"] ){
							$terms["Step 2 - Download Import Template"] .= ' '; 
						}else{
							$terms["Step 2 - Download Import Template"] = '';
						}
						$terms["Step 2 - Download Import Template"] .= '<a href="'. $this->class_settings[ 'project_data' ]["domain_name"] . $file .'" class="btn dark btn-sm" target="_blank">EXCEL File</a>';
					}
					
					$file = 'files/resource_library/'. $table2 .'-import-template.xlsx';
					if( $datasource && file_exists( $this->class_settings["calling_page"] . $file ) ){
						if( isset( $terms["Step 2 - Download Import Template"] ) && $terms["Step 2 - Download Import Template"] ){
							$terms["Step 2 - Download Import Template"] .= ' '; 
						}else{
							$terms["Step 2 - Download Import Template"] = '';
						}
						$terms["Step 2 - Download Import Template"] .= '<a href="'. $this->class_settings[ 'project_data' ]["domain_name"] . $file .'" class="btn dark btn-sm" target="_blank">EXCEL File</a>';
					}
					
				break;
				}
				
				
				$recent_activity_data = array();
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-excel-form.php' );
				$this->class_settings[ 'data' ] = array(
					'title' => $title,
					'terms' => $terms,
					'excel_import_form' => $i1,
					'import_type' => isset( $this->class_settings["import_type"] )?$this->class_settings["import_type"]:"",
					'recent_activity' => '',
					'option' => $option,
					'view' => $view,
				);
				$returning_html_data = $this->_get_html_view();
			break;
			default:
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-excel-form-options.php' );
				$returning_html_data = $this->_get_html_view();
			break;
			}
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'do_not_reload_table' => 1,
				'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event', 'nwResizeWindow.resizeWindowHeight' ),
			);
		}
		
		//BASED METHODS
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			$GLOBALS["table_fields"] = $this->table_fields;
			$this->class_settings['form_class'] = 'activate-ajax';
			
			//print_r($this->class_settings["hidden_records"]); exit;
			//print_r($GLOBALS["table_fields"]); exit;
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
			);
		}

		private function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_import_items();
			
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cimport_items.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
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
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ),
				//'status' => 'display-datatable',
			);
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			if( is_array( $returning_html_data ) ){
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';
				
				if( isset( $returning_html_data['saved_record_id'] ) && $returning_html_data['saved_record_id'] ){
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings[ 'current_record_id' ] = $returning_html_data['saved_record_id'];
					$this->class_settings[ 'current_record_details' ] = $this->_get_import_items();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_import_items(){
			
			$cache_key = $this->table_name;
			
			if( ! isset( $this->class_settings['current_record_id'] ) )return array();
			
			$settings = array(
				'cache_key' => $cache_key.'-'.$this->class_settings['current_record_id'],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			$returning_array = array(
				'html' => '',
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'get-product-features',
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK FOR CACHED VALUES
				$cached_values = get_cache_for_special_values( $settings );
				if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
					return $cached_values;
				}
				
			}
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `".$val."` as '".$key."'";
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `id`='".$this->class_settings['current_record_id']."'";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_data = execute_sql_query($query_settings);
			//echo $query;
			//print_r($all_data); exit;
			$single_data = array();
			
			if( is_array( $all_data ) && ! empty( $all_data ) ){
				
				foreach( $all_data as $record ){
					$single_data = $record;
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key.'-'.$record['id'],
						'cache_values' => $record,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
				}
				
				return $single_data;
			}
		}
		
	}
?>
<?php
	/**
	 * items_raw_data_import Class
	 *
	 * @used in  				items_raw_data_import Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	items_raw_data_import
	 */

	/*
	|--------------------------------------------------------------------------
	| items_raw_data_import Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cItems_raw_data_import{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'items_raw_data_import';
		
		private $associated_cache_keys = array(
			'import_data' => 'import_items',
			'items_raw_data_import',
		);
		
		public $table_fields = array(
			'reference_id' => 'items_raw_data_import041',
			'1' => 'items_raw_data_import001', 
			'2' => 'items_raw_data_import002', 
			'3' => 'items_raw_data_import003', 
			'4' => 'items_raw_data_import004',
			'5' => 'items_raw_data_import005',
			'6' => 'items_raw_data_import006',
			'7' => 'items_raw_data_import007',
			'8' => 'items_raw_data_import008',
			'9' => 'items_raw_data_import009',
			'10' => 'items_raw_data_import010',
			'11' => 'items_raw_data_import011',
			'12' => 'items_raw_data_import012',
			'13' => 'items_raw_data_import013',
			'14' => 'items_raw_data_import014',
			'15' => 'items_raw_data_import015',
			'16' => 'items_raw_data_import016',
			'17' => 'items_raw_data_import017',
			'18' => 'items_raw_data_import018',
			'19' => 'items_raw_data_import019',
			'20' => 'items_raw_data_import020',
			'21' => 'items_raw_data_import021',
			'22' => 'items_raw_data_import022',
			'23' => 'items_raw_data_import023',
			'24' => 'items_raw_data_import024',
			'25' => 'items_raw_data_import025',
			'26' => 'items_raw_data_import026',
			'27' => 'items_raw_data_import027',
			'28' => 'items_raw_data_import028',
			'29' => 'items_raw_data_import029',
			'30' => 'items_raw_data_import030',
			'31' => 'items_raw_data_import031',
			'32' => 'items_raw_data_import032',
			'33' => 'items_raw_data_import033',
			'34' => 'items_raw_data_import034',
			'35' => 'items_raw_data_import035',
			'36' => 'items_raw_data_import036',
			'37' => 'items_raw_data_import037',
			'38' => 'items_raw_data_import038',
			'39' => 'items_raw_data_import039',
			'40' => 'items_raw_data_import040',
		);
		
		public $datatable_settings = array(
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
	
		function items_raw_data_import(){
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
			case 'create_cached_view_for_imported_records':
				$returned_value = $this->_create_cached_view_for_imported_records();
			break;
			case 'identify_table_fields':
				$returned_value = $this->_identify_table_fields();
			break;
			case 'extract_line_items_data':
				$returned_value = $this->_extract_line_items_data();
			break;
			case 'map_excel_columns':
				$returned_value = $this->_map_excel_columns();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _extract_line_items_dataOLD(){
			//EXTRACT LINE ITEMS DATA & TRIGGER STORAGE IN RESPECTIVE TABLE
			/*-----------------------------------------------------------*/
			
			if( class_exists("cItems_raw_data_import_draft") ){
				$sales_items = new cItems_raw_data_import_draft();
				$sales_items->class_settings = $this->class_settings;
				return $sales_items->items_raw_data_import_draft();
			}
			
			//for testing
			if( isset($_GET['id']) )
				$_POST['id'] = $_GET['id'];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$progress_data[] = array( 'title' => 'Line Items Data Extraction Routine Started' );
				
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$headings_row = get_import_items_details( array( 'id' => $this->class_settings['current_record_id'] ) );
				
				//GET IMPORT TYPE FROM IMPORT TEMPLATE [ BUDGET ~ CASH CALL ~ RETURNS ]
				$Import_items_details = $headings_row; 
				//get_Import_items_details( array( 'id' => $this->class_settings['current_record_id'] ) );
				
				//$import_destination_table = 'budget_line_items';
				$cash_calls = 1;
				
				
				$start_at = 0;
				if( ! ( is_array( $headings_row ) && ! empty( $headings_row ) ) ){
					return 'Error in Retrieving Mapped Columns';
				}
				
				if( ! ( isset( $headings_row['starting_row'] ) && $headings_row['starting_row'] ) ){
					$headings_row['starting_row'] = 1;
					$progress_data[] = array( 'title' => 'Undefined Starting Row' );
					$progress_data[] = array( 'title' => 'Setting Starting Row to (Row 1)' );
				}else{
					$start_at = intval( $headings_row['starting_row'] );
				}
				
				
				if( ! ( isset( $headings_row['description_column'] ) && $headings_row['description_column'] ) ){
					return 'Error Invalid Item Description';
				}
				
				if( function_exists("get_package_option") ){
					switch( get_package_option() ){
					case "jewelry":
						if( ! ( isset( $headings_row['weight_column'] ) && $headings_row['weight_column'] ) ){
							return 'Error Invalid Item Weight Columns';
						}
					break;
					}
				}
				
				if( ! ( isset( $headings_row['code_column'] ) && $headings_row['code_column'] ) ){
					return 'Error Invalid Picture Code';
				}
				
				if( ! ( isset( $headings_row['selling_price_column'] ) && $headings_row['selling_price_column'] ) ){
					return 'Error Invalid Selling Price Column';
				}
				
				if( ! ( isset( $headings_row['cost_price_column'] ) && $headings_row['cost_price_column'] ) ){
					return 'Error Invalid Cost Price Column';
				}
				/*
				if( ! ( isset( $headings_row['percentage_markup_column'] ) && $headings_row['percentage_markup_column'] ) ){
					return 'Error Invalid Percentage Markup Column';
				}
				*/
				if( ! ( isset( $headings_row['category_column'] ) && $headings_row['category_column'] ) ){
					return 'Error Invalid Category Column';
				}
				
				if( ! ( isset( $headings_row['vendor_column'] ) && $headings_row['vendor_column'] ) ){
					return 'Error Invalid Vendor Column';
				}
				
				if( ! ( isset( $headings_row['color_column'] ) && $headings_row['color_column'] ) ){
					return 'Error Invalid Color Column';
				}
				
				$cache = $this->_get_items_raw_data_import();
				if( ! ( is_array( $cache ) && ! empty( $cache ) ) ){
					return 'Error Retrieving Cache';
				}
				
				$line_items = array();
				
				$last_found_codes = "";
				$counter = 0;
				
				foreach( $cache as $k => $vv ){
					++$counter;
					if( $counter < ( $start_at ) )continue;
					
					if( isset( $vv[ $headings_row['code_column'] ] ) ){
						$desc = "";
						if( isset( $vv[ $headings_row['description_column'] ] ) && $vv[ $headings_row['description_column'] ] ){
							$desc = trim( $vv[ $headings_row['description_column'] ] );
						}
						
						$new_code = "";
						$accept_line = 1;
						
						if( $accept_line ){
							//now check for description
							if( $desc ){
								//retrieve operators request
								//if( isset( $vv[ $headings_row['operator_request_dollar_column'] ] ) && isset( $vv[ $headings_row['operator_request_naira_column'] ] ) ){
									if( isset( $vv[ $headings_row['category_column'] ] ) ){
										$cat = str_replace( ' ', '', $vv[ $headings_row['category_column'] ] );
										if( ! $cat ){
											$cat = "uncategorized";
										}
									}
									if( isset( $vv[ $headings_row['currency_column'] ] ) ){
										$currency = $vv[ $headings_row['currency_column'] ];
									}
									if( ! $currency ){
										$currency = get_default_currency_settings();
									}
									
									$line_items[] = array(
										'image' => $vv[ $headings_row['code_column'] ],
										'description' => $vv[ $headings_row['description_column'] ],
										
										'source' => isset($vv[ $headings_row['vendor_column'] ])?$vv[ $headings_row['vendor_column'] ]:0,
										'category' => $cat,
										'cost_price' => isset($vv[ $headings_row['cost_price_column'] ])?$vv[ $headings_row['cost_price_column'] ]:0,
										'selling_price' => isset($vv[ $headings_row['selling_price_column'] ])?$vv[ $headings_row['selling_price_column'] ]:0,
										'percentage_markup' => isset($vv[ $headings_row['percentage_markup_column'] ])?$vv[ $headings_row['percentage_markup_column'] ]:0,
										'color_of_gold' => isset($vv[ $headings_row['color_column'] ])?$vv[ $headings_row['color_column'] ]:0,
										'weight_in_grams' => isset($vv[ $headings_row['weight_column'] ])?$vv[ $headings_row['weight_column'] ]:0,
										'currency' => $currency,
										'quantity' => isset( $vv[ $headings_row['quantity_column'] ] )?$vv[ $headings_row['quantity_column'] ]:1,
										'barcode' => isset( $vv[ $headings_row['barcode_column'] ] )?$vv[ $headings_row['barcode_column'] ]:"",
										
										'unit' => ( isset( $headings_row['unit_column'] ) && isset( $vv[ $headings_row['unit_column'] ] ) )?$vv[ $headings_row['unit_column'] ]:"",
										'unit_of_measure' => ( isset( $headings_row['unit_of_measure'] ) && isset( $vv[ $headings_row['unit_of_measure'] ] ) )?$vv[ $headings_row['unit_of_measure'] ]:"",
										
									);
									
								//}
							}
						}
					}
				}
				//$import_destination_table = 'budget_line_items';
				$import_destination_table = 'items';
				
				//STORE IN CACHE
				$settings = array(
					'cache_key' => $this->table_name.'-line-items-data-'.$this->class_settings['current_record_id'],
					'cache_values' => $line_items,
				);
				set_cache_for_special_values( $settings );
				
				//SET MAPPING FOR SUBSEQUENT OPERATION
				$csettings = array(
					'cache_key' => 'stored-mapping-' . $import_destination_table . $this->class_settings['user_id'],
					'cache_values' => $this->class_settings['current_record_id'],
					'permanent' => true,
					'directory_name' => "import_items",
				);
				set_cache_for_special_values( $csettings );
				
				/*
				CHECK PIC
				if( ! isset( $Import_items_details['import_template_type'] ) ){
					return 'Error Image Folder Not Specified';
				}
				*/
				
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Line Items Data Extraction Routine &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$progress_data[] = array( 'title' => 'Line Items Data Extraction Routine Successful' );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => $progress_data,
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				if( $import_destination_table ){
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-'.md5( $this->table_name );
					$return['id'] = $this->class_settings[ 'current_record_id' ];
					$return['post_id'] = $return['id'];
					$return['action'] = '?action='.$import_destination_table.'&todo=store_extracted_line_items_data';
				}
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		protected function _extract_line_items_data(){
			//EXTRACT LINE ITEMS DATA & TRIGGER STORAGE IN RESPECTIVE TABLE
			/*-----------------------------------------------------------*/
			
			//for testing
			if( isset($_GET['id']) )
				$_POST['id'] = $_GET['id'];
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$progress_data[] = array( 'title' => 'Line Items Data Extraction Routine Started' );
				
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$headings_row = get_import_items_details( array( 'id' => $this->class_settings['current_record_id'] ) );
				
				$error_msg = '';
				//echo $this->class_settings['current_record_id'];
				//print_r( $headings_row );
				//exit;
				//GET IMPORT TYPE FROM IMPORT TEMPLATE [ BUDGET ~ CASH CALL ~ RETURNS ]
				$Import_items_details = $headings_row; 
				//get_Import_items_details( array( 'id' => $this->class_settings['current_record_id'] ) );
				
				$cash_calls = 1;
				$start_at = 0;
				if( ! ( is_array( $headings_row ) && ! empty( $headings_row ) ) ){
					return 'Error in Retrieving Mapped Columns';
				}
				
				if( ! ( isset( $headings_row['starting_row'] ) && $headings_row['starting_row'] ) ){
					$headings_row['starting_row'] = 1;
					$progress_data[] = array( 'title' => 'Undefined Starting Row' );
					$progress_data[] = array( 'title' => 'Setting Starting Row to (Row 1)' );
				}else{
					$start_at = intval( $headings_row['starting_row'] );
				}
				
				$import_destination_table = $headings_row["import_template_type"];
				
				$tbd = explode( ':::' , $headings_row["import_template_type"] );
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
				$import_destination_table = isset( $tba["value"] )?$tba["value"]:$headings_row["import_template_type"];
				
				if( ! $import_destination_table )$import_destination_table = 'items';
				
				//echo $import_destination_table; exit;
				//$cc = get_class_names();
				$cache_key = $this->associated_cache_keys['import_data'];
				$settings = array(
					'cache_key' =>  $cache_key.'-p-data-'.$this->class_settings[ 'current_record_id' ],
					'directory_name' => $cache_key,
				);
				$pdt = get_cache_for_special_values( $settings );
				if( ! empty( $pdt ) ){
					foreach( $pdt as $pdk => $pdv ){
						if( isset( $headings_row[ $pdk ] ) ){
							$headings_row[ $pdk ] = $pdv;
						}
					}
				}
				//print_r($headings_row); exit;
				$plugin = '';
				switch( $tb_type ){
				case "plugin":
					
					if( isset( $tba["key"] ) && $tba["key"] ){
						$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
						if( class_exists( $clp ) ){
							$plugin = strtolower( $tba["key"] );
							$ptb = new $clp();
							$in = $ptb->load_class( array( 'class' => array( $import_destination_table ), 'initialize' => 1 ) );
							if( isset( $in[ $import_destination_table ]->table_name ) ){
								$class = $in[ $import_destination_table ];
							}else{
								$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $import_destination_table ."' is invalid or missing in the project.</p>";
							}
						}else{
							$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
						}
					}else{
						$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
					}
					
				break;
				default:
					$cls = 'c'.ucwords( $import_destination_table );
					if( class_exists( $cls ) ){
						$class = new $cls();
					}else{
						$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
					}
				break;
				}
				
				if( $error_msg ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = $error_msg;
					return $err->error();
				}
				
				if( isset( $class->table_name ) && function_exists( $import_destination_table ) ){
					$r = $import_destination_table();
					
					foreach( $class->table_fields as $key => $val ){
						$add = 0;
					
						if( isset( $r[ $val ][ "do_not_import" ] ) && $r[ $val ][ "do_not_import" ] ){
							
						}else if( isset( $class->basic_data[ "import_template" ] ) ){
							if( isset( $class->basic_data[ "import_template" ]["fields"][ $key ] ) ){
								$add = 1;
							}
						}else{
							$add = 1;
						}
						
						if( $add ){
							//@req-f implemented
							if( isset( $r[ $val ]['required_field'] ) && $r[ $val ]['required_field'] == "yes" ){
								if( ! ( isset( $headings_row[ $key ] ) && $headings_row[ $key ] ) ){
									$err = new cError('010014');
									$err->action_to_perform = 'notify';
									
									$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
									$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
									$err->additional_details_of_error = '<h4>Missing Value</h4>Required Field <strong>'. $r[ $val ]["field_label"] .'</strong> not specified';
									return $err->error();
								}
							}
							
						}
						
					}
				}
				
				$cache_key = $this->associated_cache_keys['import_data'];
				$settings = array(
					'cache_key' => $cache_key . '-data-' . $this->class_settings[ 'current_record_id' ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cache_data = get_cache_for_special_values( $settings );
				//$cache = $this->_get_items_raw_data_import();
				
				if( ! ( isset( $cache_data ) && is_array( $cache_data ) && ! empty( $cache_data ) ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Error Retrieving Cache</h4>';
					return $err->error();
				}
				$cache = $cache_data;
				
				

				//print_r($headings_row);
				//print_r($cache);
				$line_items = array();
				
				$last_found_codes = "";
				$counter = 0;
				
				foreach( $cache as $k => $vv ){
					++$counter;
					if( $counter < ( $start_at ) )continue;
					
					$tmp_items = array();
					
					if( isset( $class ) ){
						foreach( $class->table_fields as $key => $val ){
							if( ! isset( $r[ $val ] ) ){
								continue;
							}
							
							if( ( isset( $r[ $val ][ "do_not_import" ] ) && $r[ $val ][ "do_not_import" ] ) ){
								continue;
							}
					
							if( isset( $class->basic_data[ "import_template" ] ) ){
								if( isset( $class->basic_data[ "import_template" ]["fields"][ $key ] ) ){
									$add = 1;
								}else{
									continue;
								}
							}
							
							if( isset( $r[ $val ]['required_field'] ) && $r[ $val ]['required_field'] == "yes" && ( ! isset( $vv[ $headings_row[ $key ] ] ) ) ){
								$tmp_items = array();
								break;
							}
							
							$dv = isset( $vv[ $headings_row[ $key ] ] )?$vv[ $headings_row[ $key ] ]:'';
							
							if( isset( $r[ $val ] ) ){
								switch( $r[ $val ]['form_field'] ){
								case "currency":
								case "decimal":
								case "number":
									$dv = doubleval( clean_numbers( $dv ) );
								break;
								case "date":
								case "date-5":
									$dv = convert_date_to_timestamp( $dv , 1 );
								break;
								default:
									$dv = trim( $dv );
								break;
								}
							}
							
							$tmp_items[ $key ] = $dv;
						}
					}
					$line_items[] = $tmp_items;
				}
				
				//print_r( $line_items ); exit;
				
				//STORE IN CACHE
				$settings = array(
					'cache_key' => $this->table_name.'-line-items-data-'.$this->class_settings['current_record_id'],
					'cache_values' => $line_items,
				);
				set_cache_for_special_values( $settings );
				
				//SET MAPPING FOR SUBSEQUENT OPERATION
				$csettings = array(
					'cache_key' => 'stored-mapping-' . $import_destination_table . $this->class_settings['user_id'],
					'cache_values' => $this->class_settings['current_record_id'],
					'permanent' => true,
					'directory_name' => "import_items",
				);
				set_cache_for_special_values( $csettings );
				
				/*
				CHECK PIC
				if( ! isset( $Import_items_details['import_template_type'] ) ){
					return 'Error Image Folder Not Specified';
				}
				*/
				
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Line Items Data Extraction Routine &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$progress_data[] = array( 'title' => 'Line Items Data Extraction Routine Successful' );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => $progress_data,
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				if( $import_destination_table ){
					$eps = '';
					$todo = 'store_extracted_line_items_data';
					
					switch( $import_destination_table ){
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
					break;
					default:
						$eps .= '&nwp_source_tb=' . $import_destination_table;
						
						if( $plugin ){
							$eps .= '&nwp_source_plugin=' . $plugin;
						}
						
						$import_destination_table = 'customer_call_log';
					break;
					}
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-'.md5( $this->table_name );
					$return['id'] = $this->class_settings[ 'current_record_id' ];
					$return['post_id'] = $return['id'];
					$return['action'] = '?action='.$import_destination_table.'&todo=' . $todo . $eps;
				}
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		protected function _map_excel_columns(){
			//ALLOW USERS TO MAP EXCEL COLUMNS TO BUDGET/CASH CALLS FIELDS
			/*----------------------------------------------------------*/
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				
				$import_table = 'items_raw_data_import';
				$this->class_settings[ 'current_record_id' ] = $_POST['id'];
				
				//get newly import records from db based on post[id] - budget id
				//display form in left + table in right
				$Import_items = new cImport_items();
				$Import_items->class_settings = $this->class_settings;
				$Import_items->class_settings[ 'action_to_perform' ] = 'get_field_mapping_form';
				$i = $Import_items->import_items();

				$cache_key = $this->associated_cache_keys['import_data'];
				$settings = array(
					'cache_key' => $cache_key . '-data-' . $this->class_settings[ 'current_record_id' ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$cache = get_cache_for_special_values( $settings );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-columns-map' );
				$this->class_settings[ 'data' ] = array(
					'data_entry_form' => $i["html"],
					'html_data' => $cache,
					//'html_data' => $this->_get_items_raw_data_import(),
				);
				$map = $this->_get_html_view();
				
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => $map ),
					),
				);
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
					
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Mapping Columns for Data Extraction &darr;';
				$return = $err->error();
				
				unset( $return["html"] );
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				$return['javascript_functions'] = array( 'prepare_new_record_form_new' );
				
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		protected function _create_cached_view_for_imported_records(){
			//CREATE CACHED VIEW FOR IMPORTED DATA & TRIGGER FIELDS IDENTIFICATION
			/*-------------------------------------------------------------------*/
				
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				
				$this->class_settings['current_record_id'] = $_POST['id'];
				
				$cache = $this->_get_items_raw_data_import();
				if( ! ( is_array( $cache ) && ! empty( $cache ) ) ){
					return 'Error Creating Cache';
				}
					
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Generating Cached View &darr;';
				$return = $err->error();
				
				$return['status'] = 'new-status';
				
				$return['err'] = $err->additional_details_of_error;
				$return['msg'] = 'Please wait...';
				
				//Display Update
				unset( $return['html'] );
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/import-progress.php' );
				$this->class_settings[ 'data' ] = array(
					'import_progress' => array( 
						0 => array( 'title' => 'Cached View Generation Started' ),
						1 => array( 'title' => 'Cached View Successfully Generated' ),
						2 => array( 'title' => 'Generating View for Column Mapping' ),
					),
				);
				
				$return['html_prepend_selector'] = '#excel-file-import-progress-list';
				$return['html_prepend'] = $this->_get_html_view();
				
				//settings for ajax request reprocessing
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = $this->class_settings[ 'current_record_id' ];
				$return['post_id'] = $return['id'];
				//$return['action'] = '?action='.$this->table_name.'&todo=identify_table_fields';
				$return['action'] = '?action='.$this->table_name.'&todo=map_excel_columns';
			}
			$return['do_not_reload_table'] = 1;
			
			return $return;
		}
		
		//BASED METHODS
		protected function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		protected function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
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
				'record_id' => $returning_html_data[ 'record_id' ],
			);
		}

		protected function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_items_raw_data_import();
			
			return $returning_html_data;
		}
		
		protected function _display_data_table(){
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
				
				$err->class_that_triggered_error = 'citems_raw_data_import.php';
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
		
		protected function _save_changes(){
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
					$this->class_settings[ 'current_record_details' ] = $this->_get_items_raw_data_import();
				}
			}
			
			return $returning_html_data;
		}
		
		protected function _get_items_raw_data_import(){
			
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields['reference_id']."`='".$this->class_settings['current_record_id']."'";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$all_data = execute_sql_query($query_settings);
			
			$single_data = array();
			
			if( is_array( $all_data ) && ! empty( $all_data ) ){
				
				foreach( $all_data as $record ){
					$single_data[ $record['id'] ] = $record;
				}
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key.'-'.$this->class_settings['current_record_id'],
					'cache_values' => $single_data,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				if( ! set_cache_for_special_values( $settings ) ){
					//report cache failure message
					return 0;
				}
				
				return $single_data;
			}
		}
		
	}
?>
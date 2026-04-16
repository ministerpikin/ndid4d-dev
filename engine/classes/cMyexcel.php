<?php
	/**
	 * My Excel Class
	 *
	 * @used in  				Generating Excel Spreadsheet
	 * @created  				18:14 | 31-10-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| My Excel Class
	|--------------------------------------------------------------------------
	|
	| Generate excel reports from data in the Gas Helix datatables
	|
	*/
	
	class cMyexcel{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'myexcel';
		private $label = 'Myexcel';
		private $process_large_files = 1;
		
		private $report_title = '';

		private $html;
		private $filename;
		
		//Directory for Entities
		private $entity_directory = 'files';
		private $max_export_page_size = 2000;
		private $max_export_page_limit = 20000;

		private $system_fields = array(
			'id' => 'id',
			'creator_role' => 'creator_role',
			'record_status' => 'record_status',
			'created_by' => 'created_by',
			'creation_date' => 'creation_date',
			'modified_by' => 'modified_by',
			'created_source' => 'created_source',
			'modified_source' => 'modified_source',
			'modification_date' => 'modification_date',
			'ip_address' => 'ip_address',
			'device_id' => 'device_id',
		);
		
		function myexcel(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create':
				$returned_value = $this->_create();
			break;
			case 'generate_excel':
			case 'generate_excel_front':
				$returned_value = $this->_generate_excel();
			break;
			case 'save_imported_excel_data':
				$returned_value = $this->_save_imported_excel_data();
			break;
			case 'import_excel_table':
				$returned_value = $this->_display_import_data_capture_form();
			break;
			case 'bulk_import_excel_file_data':
				$returned_value = $this->_bulk_import_excel_file_data();
			break;
			case 'check_background_export_to_excel':
				$returned_value = $this->_check_background_export_to_excel();
			break;
			case 'generate_large_excel_file':
				$returned_value = $this->_generate_large_excel_file();
			break;
			case 'save_generate_json_formatted':
			case 'save_generate_json':
			case 'save_generate_csv':
			case 'generate_csv_inline':
			case 'generate_csv':
			case 'generate_csv_from_mine':
				$returned_value = $this->_generate_csv();
			break;
			case 'save_generate_csv2':
				$returned_value = $this->_generate_csv2();
			break;
			case 'save_generate_csv3':
			case 'check_bg_process':
			case 'save_generate_csv_bg':
				$returned_value = $this->_background_export();
			break;
			case 'clear_completed_export':
				$returned_value = $this->_clear_completed_export();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				// print_r( $callback );exit;
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		private function _clear_completed_export(){
			if( isset( $_GET[ 'directory_name' ] ) && $_GET[ 'directory_name' ] && isset( $_GET[ 'file_name' ] ) && $_GET[ 'file_name' ] ){
				$x1 = array(
					"permanent" => true,
					'cache_key' => $_GET[ 'file_name' ],
					'directory_name' => $_GET[ 'directory_name' ],
				);
				clear_cache_for_special_values( $x1 );
			}
			
			$handle = "display_mis_menu-sub";
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$handle = $this->class_settings["html_replacement_selector"];
			}

			return array(
				'status' => 'new-status',
				'html_replacement_selector' => '#'.$handle,
				'html_replacement' => 'Cleared Successfully',
			);
		}

		private function _join_files( $files = array(), $result = '', $opt = array() ){
		    if( ! is_array( $files ) ){
		        throw new Exception( '`$files` must be an array' );
		    }

		    $wH = fopen( $this->class_settings[ 'calling_page' ] . $result, "w+" );

		    foreach( $files as $file ){
		        $fh = fopen( $this->class_settings[ 'calling_page' ] . $file, "r" );
		        // print_r( $fh );exit;
		        while( ! feof( $fh ) ){
		            fwrite( $wH, fgets( $fh ) );
		        }

		        fclose( $fh );
		        unset( $fh );
		        // fwrite( $wH, "\n" ); //usually last line doesn't have a newline

		        if( isset( $opt[ 'unlink_files' ] ) && $opt[ 'unlink_files' ] ){
		        	unlink( $this->class_settings[ 'calling_page' ] . $file );
		        }
		    }

		    fclose( $wH );

		    unset( $wH );
		}
		
		private $export_completion_key = "user-export-";
		private function _background_export(){

			$base = new cBase_class();
			$base->table_name = $this->table_name;
			$base->class_settings = $this->class_settings;
			
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			switch( $action_to_perform ){
			case 'check_bg_process':
				// return array();
				return $base->_check_bg_process();
			break;
			case 'save_generate_csv_bg':

				// print_r( $this->class_settings );
				$cid = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
				// echo $cid;

				if( $cid ){
					set_time_limit(0);

					$x1 = array(
						'cache_key' => $cid,
					);

					$bg = get_cache_for_special_values( $x1 );
					clear_cache_for_special_values( $x1 );
						// print_r( $bg );exit;

					$_GET = isset( $bg[ 'get' ] ) ? $bg[ 'get' ] : $_GET;
					$_POST = isset( $bg[ 'post' ] ) ? $bg[ 'post' ] : $_POST;

					$sq = md5('search_query'.$_SESSION['key']);
					if( isset( $bg[ 'cache' ] ) && is_array( $bg[ 'cache' ] ) && ! empty( $bg[ 'cache' ] ) ){
						$_SESSION[$sq] = $bg[ 'cache' ];
					}

					if( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] && isset( $_GET[ 'todo' ] ) && $_GET[ 'todo' ] ){
						$cc = 'c' . $_GET[ 'action' ];
						// print_r( $_GET );
						if( class_exists( $cc ) ){

							$cc = new $cc;
							$cc->class_settings = $this->class_settings;
							$cc->class_settings[ 'action_to_perform' ] = $_GET[ 'todo' ];
							$ctn = $cc->table_name;

							if( isset( $_GET[ 'html_replacement_selector' ] ) && $_GET[ 'html_replacement_selector' ] ){
								$cc->class_settings[ 'html_replacement_selector' ] = $_GET[ 'html_replacement_selector' ];
								$cc->class_settings[ 'use_default_replacement_selector' ] = 1;
							}
							
							$return = $cc->$ctn();
							// print_r( $cc );

							if( is_array( $return ) && empty( $return ) ){
								$return = $base->_display_notification( array( 'type' => 'error', 'message' => '<h4><strong>Unable to Generate Data Export</strong></h4>' ) );
							}

							$return[ 'return' ] = 1;
							$return[ 'clear' ] = 1;

							$settings = array(
								'cache_key' => $this->table_name . "-bg-process-" . $cid,
								'cache_values' => $return,
								'directory_name' => $this->table_name,
								'permanent' => true,
							);
							set_cache_for_special_values( $settings );
							
							if( isset( $return[ 'typ' ] ) && $return[ 'typ' ] !== 'uerror' ){
								$return[ 'name' ] = isset( $bg[ 'post' ][ 'name' ] ) ? $bg[ 'post' ][ 'name' ] : '';
								$settings = array(
									'cache_key' => $this->export_completion_key . time(),
									'cache_values' => $return,
									'directory_name' => $this->table_name.'/completed-'.$this->class_settings[ 'user_id' ],
									'permanent' => true,
								);
								set_cache_for_special_values( $settings );
							}
							
						}
					}

				}
				// exit;
				return 1;
			break;
			case 'save_generate_csv3':
				$xd = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], true ) : array();
				$current_tab = isset( $xd[ 'current_tab' ] ) ? $xd[ 'current_tab' ] : 'mis';
				$selected_id = isset( $xd[ 'selected_id' ] ) ? $xd[ 'selected_id' ] : '';

				$not_cache_key = 'notification-dashboard';
				$cache_date_key = get_new_id();

				$pd[ 'get' ] = $_GET;
				$pd[ 'post' ] = $_POST;
				$pd[ 'get' ][ 'todo' ] = 'save_generate_csv2';

				$sq = md5('search_query'.$_SESSION['key']);
				if( isset( $_SESSION[$sq] ) && $_SESSION[$sq] ){
					$pd[ 'cache' ] = $_SESSION[$sq];
				}

				$settings = array(
					'cache_key' => $not_cache_key,
					// 'mike' => $cache_key,
				);
				$fw = get_cache_for_special_values( $settings );
				if( $fw && ! is_array( $fw ) )$fw = array();

				if( ! ( isset( $fw[ $current_tab ] ) && $fw[ $current_tab ] ) )$fw[ $current_tab ] = array();
				if( ! ( isset( $fw[ $current_tab ][ $selected_id ] ) && $fw[ $current_tab ][ $selected_id ] ) )$fw[ $current_tab ][ $selected_id ] = array();

				$fw[ $current_tab ][ $selected_id ] = array(
					'user' => $this->class_settings[ 'user_id' ],
					'time' => time(),
					'status' => 'active',
					'id' => $cache_date_key,
					'data' => $pd,
				);

				// Make permanent
				$sxt = array(
					'cache_key' => $not_cache_key,
					'cache_values' => $fw,
					'permanent' => 1,
					'directory_name' => $not_cache_key,
				);
				set_cache_for_special_values( $sxt );

				$pid = get_new_id();
				$sxt = array(
					'cache_key' => $cache_date_key,
					'cache_values' => $pd,
				);
				set_cache_for_special_values( $sxt );

				$_POST[ 'id' ] = $cache_date_key;
				// $_GET[ 'use_silent' ] = 1;
				$bg_settings = array(
					"action" => $this->table_name,
					"todo" => 'save_generate_csv_bg',
					"user_id" => $this->class_settings["user_id"], 
					"reference" => $cache_date_key,
					// "wait_for_execution" => 1,
					// "no_session" => 1,
					"use_session" => $_SESSION['key'],
				);

				// echo time();
				run_in_background( "bprocess", 0, $bg_settings );

				// return $base->_check_bg_process();

				$this->class_settings["callback"][ $this->table_name ][ $action_to_perform ] = array(
					'id' => $cache_date_key,
					'action' => '?action=' . $this->table_name . '&todo=check_bg_process',
					// 'nwp_silent' => 'test',
				);
				// echo 'mike';exit;
				return $base->_display_notification( array( "type" => "success", "message" => '<h4><strong>Export has been Queued</strong></h4>You shall be notified when export is completed', 'manual_close' => 1 ) );

			break;
			}
		}

		private function _generate_csv2(){
			// exit;

			$name = isset( $_POST[ 'name' ] ) ? $_POST[ 'name' ] : '';
			$file_type = isset( $_POST[ 'file_type' ] ) ? $_POST[ 'file_type' ] : ( isset( $_GET[ 'file_type' ] ) ? $_GET[ 'file_type' ] : '' );
			$dx = isset( $_POST[ 'data' ] ) ? json_decode( $_POST[ 'data' ], true ) : array();
			$limit = isset( $dx[ 'page_limit' ] ) ? doubleval( $dx[ 'page_limit' ] ) : 0;
			
			$base = new cBase_class();
			$base->class_settings = $this->class_settings;

			$handle = "display_mis_menu-sub";
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$handle = $this->class_settings["html_replacement_selector"];
			}

			$use_container = 0;
			$default_limit = 20000;
			// $default_limit = 100;
			// $limit = 1000;

			$this->class_settings[ 'action_to_perform' ] = 'save_generate_csv';
			switch( $file_type ){
			case 'json':
				$this->class_settings[ 'action_to_perform' ] = 'save_generate_json';
			break;
			}

			if( $limit > $default_limit ){
				$files = array();
				$this->class_settings[ 'return_name' ] = 1;
				for( $i = 0; $i < floor( $limit/$default_limit ) ; $i++ ){
					
					$_POST[ 'name' ] = $name . $i;

					$dx[ 'page_offset' ] = strval( $default_limit * ( $i ) );
					$dx[ 'page_limit' ] = $default_limit;
					$_POST[ 'data' ] = json_encode( $dx );

					if( $i ){
						$this->class_settings[ 'no_header' ] = 1;
					}

					// print_r( $dx );
					$x = $this->myexcel();
					if( isset( $x[ 'path' ] ) && $x[ 'path' ] ){
						$files[] = $x[ 'path' ];
					}
				}

				$remainder = $limit % $default_limit;
				if( $remainder ){
					$dx[ 'page_offset' ] = strval( $default_limit * $i );
					// $dx[ 'page_offset' ] = strval( $default_limit * ( $i + 1 ) );
					$dx[ 'page_limit' ] = $default_limit;
					$_POST[ 'data' ] = json_encode( $dx );
					
					// print_r( $dx );
					// echo $dx[ 'page_offset' ].' - offset<br>';
					// echo $dx[ 'page_limit' ].' - limit<br>';
					$x = $this->myexcel();
					if( isset( $x[ 'path' ] ) && $x[ 'path' ] ){
						$files[] = $x[ 'path' ];
					}
				}
				// echo $remainder; exit;

				// print_r( $files );exit;
				$etype = 'error';
				if( ! empty( $files ) ){
					$pr = get_project_data();
					$dir = create_report_directory( $this->class_settings );

					$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/' . $name . '.csv';

					$this->_join_files( $files, $file_url, array( 'unlink_files' => 1 ) );
					$error = '<h4>Successful Data Export</h4><a class="btn btn-success" href="'. $pr["domain_name"] .$file_url .'" target="_blank">Download CSV</a>';

					$etype = 'success';

				}else{
					$error = '<h4>Unable to Create Files</h4>Please ensure the datable has contents';
				}

				$ra = array( "type" => $etype, "message" => $error, 'manual_close' => 1 );
				if( $use_container ){
					$ra[ 'do_not_display' ] = 1;
					$ra[ 'html_replacement_selector' ] = '#'.$handle;
				}

				return $base->_display_notification( $ra );
				// exit;
			}else{
				return $this->myexcel();
			}

		}

		private function _generate_csv(){
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$base = new cBase_class();
			$base->class_settings = $this->class_settings;
			//$base->table_name = $this->table_name;
			$error_msg = '';
			
			$handle = "display_mis_menu-sub";
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			$use_container = isset( $_GET[ 'use_container' ] ) ? $_GET[ 'use_container' ] : '';
			$name = isset( $_POST[ 'name' ] ) ? $_POST[ 'name' ] : '';
			
			switch( $action_to_perform ){
			case "save_generate_json_formatted":
			case "save_generate_json":
			case "save_generate_csv":
				if( isset( $this->class_settings["export_data"] ) && $this->class_settings["export_data"] ){
					$data = $this->class_settings["export_data"];
				}else if( isset( $_POST[ 'data' ] ) && $_POST[ 'data' ] ){
					$data = json_decode( $_POST[ 'data' ], true );
				}
				$_POST = array();

				if( isset( $data["tables"] ) && ! empty( $data["tables"] ) ){
					$plugin = isset( $data["plugin"] ) && $data["plugin"] ? $data["plugin"] : '';
					$mine_plugin = isset( $data["mine_plugin"] ) && $data["mine_plugin"] ? $data["mine_plugin"] : '';

					$pl = $plugin;
					if( $mine_plugin )$pl = $mine_plugin;

					if( $pl ){
						$nwp = 'c' . ucwords( $pl );
						if( class_exists( $nwp ) ){
							$nwp = new $nwp();
							$nwp->class_settings = $this->class_settings;
							$nwp->load_class( array( 'class' => array_keys( $data[ 'tables' ] ) ) );
						}else{
							$error_msg = "<h4><strong>Plugin Not Found</strong></h4>".$nwp;
						}
					}
					$outfile = 0;
					$data[ 'use_outfile' ] = $outfile;
					$dir = create_report_directory( $this->class_settings, array( 'add_day' => 1 ) );
					
					switch( $action_to_perform ){
					case "save_generate_json_formatted":
						$data[ 'format' ] = 'json_formatted';
						$data[ 'page_limit' ] = isset( $data[ 'page_size' ] ) ? $data[ 'page_size' ] : 1000;
					break;
					}

					if( ! $error_msg ){
						$txd = $this->_get_transformed_data( $data );
						$name = isset( $this->class_settings[ 'filename' ] ) ? $this->class_settings[ 'filename' ] : ( $name ? $name : ( isset( $txd["view_name"] ) ? $txd["view_name"] : '' ) );
						// exit;
						unset( $data );

						switch( $action_to_perform ){
						case "save_generate_json_formatted":
							if( isset( $txd[ 'error' ] ) && $txd[ 'error' ] ){
								return $txd;
							}else{
								return array( 'json' => $txd );
							}
						break;
						}

						if( isset( $txd["data"] ) ){
							if( isset( $txd["view_name"] ) ){

								if( ! $outfile ){
									$opt = array( "transformed" => 1, "table" => $this->table_name );
									// print_r( $dir.'/'.$name );exit;
									$x = download_csv( $txd["data"], $this->class_settings["calling_page"], $dir.'/'.$name, $opt );
									unset( $txd["data"] );
									
									if( isset( $txd["page_offset"] ) && isset( $txd["page_size"] ) && $txd["page_size"] ){
										set_time_limit(0);
										$loop = 1;
										
										if( isset( $this->class_settings[ 'return_name' ] ) && $this->class_settings[ 'return_name' ] ){
											$txd[ 'no_header' ] = 1;
										}
										
										while( $loop ){
											$txd["no_header"] = 1;
											$txd = $this->_get_transformed_data_page( $txd );
											
											if( isset( $txd["data"] ) && isset( $txd["view_name"] ) ){
												$x = download_csv( $txd["data"], $this->class_settings["calling_page"], $dir.'/'.$name, $opt );
												unset( $txd["data"] );
											}else{
												$loop = 0;
											}
										}
									}
								}

								$pr = get_project_data();
								$etype = 'success';

								// print_r( $pr["domain_name"] );exit;
								if( isset( $txd[ 'file_path' ] ) && $txd[ 'file_path' ] ){
									$file_url = $txd[ 'file_path' ];
								}else{
									$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/' . $name . '.csv';
								}
								// print_r( $file_url );exit;
								
								if( isset( $this->class_settings[ 'return_name' ] ) && $this->class_settings[ 'return_name' ] ){
									return array(
										'path' => $file_url,
									);
								}
								
								$reports_handler = new cReports();
								$reports_handler->class_settings = $this->class_settings;
								$reports_handler->class_settings[ 'action_to_perform' ] = 'add_generated_report_record';
								
								$reports_handler->class_settings[ 'file_properties' ] = array( 
									'file_name' => 'Exported CSV',
									'file_url' => $file_url,
									'file_reference' => 'csv',
									'file_source' => $this->table_name,
									'file_keywords' => 'Exported CSV',
									'file_description' => 'system generated report',
								);
								$reports_handler->reports();
								
								$error = '<h4>Successful Data Export</h4><a class="btn btn-success" href="'. $pr["domain_name"] .$file_url .'" target="_blank">Download CSV</a>';

								$ra = array( "type" => $etype, "message" => $error, 'manual_close' => 1 );

								if( $use_container ){
									$ra[ 'do_not_display' ] = 1;
									$ra[ 'html_replacement_selector' ] = '#'.$handle;
								}

								return $base->_display_notification( $ra );
							}else{
								return $txd;
							}
						}else{
							$error_msg = "<h4>No Dataset</h4>";
						}
					}
				}else{
					$error_msg = "<h4>Invalid Data Fields</h4>Please select the data fields to export";
				}
				
			break;
			default:
				
				if( isset( $_POST["hidden_fields"] ) && $_POST["hidden_fields"] ){
					$hf = json_decode( $_POST["hidden_fields"], true );
				}
				
				if( isset( $this->class_settings["hidden_fields"] ) && $this->class_settings["hidden_fields"] ){
					$hf = $this->class_settings["hidden_fields"];
				}
				
				$plugin = isset( $hf["plugin"] ) && $hf["plugin"] ? $hf["plugin"] : '';
				$mine_plugin = isset( $hf["mine_plugin"] ) && $hf["mine_plugin"] ? $hf["mine_plugin"] : '';
				$current_tab = isset( $hf["current_tab"] ) && $hf["current_tab"] ? $hf["current_tab"] : '';
				$selected_id = isset( $hf["selected_id"] ) && $hf["selected_id"] ? $hf["selected_id"] : '';

				$pl = $plugin;
				if( $mine_plugin )$pl = $mine_plugin;

				if( $pl ){
					$nwp = 'c' . ucwords( $pl );
					if( class_exists( $nwp ) ){
						$nwp = new $nwp();
						$nwp->class_settings = $this->class_settings;
						$nwp->load_class( array( 'class' => array( $hf["table"] ) ) );
					}else{
						$error_msg = "The Plugin Class '". $nwp ."' does not exist";
					}
				}

				// print_r( $hf );exit;
				if( isset( $hf["table"] ) && $hf["table"] ){
					$tb = strtolower( trim( $hf["table"] ) );
					$cl = "c" . ucwords( $tb );
					if( class_exists( $cl ) ){
						$data = array();
						if( isset( $this->class_settings["data"] ) && $this->class_settings["data"] ){
							$data = $this->class_settings["data"];
						}
						
						$cls = new $cl();
						
						$data["table"] = $cls->table_name;
						$data["tables"][ $cls->table_name ]["title"] = $cls->label;
						$data["tables"][ $cls->table_name ]["fields"] = $cls->table_fields;
						$data["tables"][ $cls->table_name ]["labels"] = $tb();

						$other_data = array();
						if( isset( $this->class_settings["other_data"] ) && $this->class_settings["other_data"] ){
							$other_data = $this->class_settings["other_data"];
						}
						
						if( isset( $other_data[ 'exclude_fields' ] ) && $other_data[ 'exclude_fields' ] ){
							foreach( $other_data[ 'exclude_fields' ] as $ax ){
								if( isset( $data["tables"][ $cls->table_name ]["fields"][ $ax ] ) ){
									unset( $data["tables"][ $cls->table_name ]["fields"][ $ax ] );
								}
							}
						}

						if( isset( $cls->basic_data[ 'include_in_export' ] ) && $cls->basic_data[ 'include_in_export' ] ){
							$data["tables"][ $cls->table_name ]["additional_fields"] = $cls->basic_data[ 'include_in_export' ];
						}

						if( isset( $hf[ 'change_form_action' ] ) && $hf[ 'change_form_action' ] )$data[ 'change_form_action' ] = 1;

						if( isset( $cls->children ) && is_array( $cls->children ) && ! empty( $cls->children ) ){
							if( $pl ){
								$nwp->load_class( array( 'class' => array_keys( $cls->children ) ) );
							}
							foreach( $cls->children as $child_table => $top ){
								$ch_ls = 'c'. ucwords( $child_table );
								if( class_exists( $ch_ls ) && isset( $top["link_field"] ) ){
									$ch_ls2 = new $ch_ls();
									
									$data["tables"][ $ch_ls2->table_name ]['link_field'] = $top["link_field"];
									$data["tables"][ $ch_ls2->table_name ]['title'] = $ch_ls2->label;
									$data["tables"][ $ch_ls2->table_name ]['labels'] = $child_table();
									
									$data["tables"][ $ch_ls2->table_name ]['fields'] = $ch_ls2->table_fields;
									
									if( ! isset( $hf[ 'tables' ][ $child_table ] ) )$hf[ 'tables' ][ $child_table ] = array();

									if( isset( $ch_ls2->basic_data[ 'include_in_export' ] ) && $ch_ls2->basic_data[ 'include_in_export' ] ){
										$data["tables"][ $ch_ls2->table_name ]["additional_fields"] = $ch_ls2->basic_data[ 'include_in_export' ];
									}
								}
							}
						}
						
						
						if( ! isset( $other_data["query"]["where"] ) && isset( $_SESSION[ $cls->table_name ]['filter'] ) ){
							foreach( array( 'where', 'join' ) as $qv ){
								if( isset( $_SESSION[ $cls->table_name ]['filter'][ $qv ] ) ){
									$other_data["query"][ $qv ] = $_SESSION[ $cls->table_name ]['filter'][ $qv ];
								}
							}
							if( ! ( isset( $other_data["query"][ 'where' ] ) && $other_data["query"][ 'where' ] ) && isset( $_SESSION[ $cls->table_name ][ 'filter' ][ 'additional_where' ] ) && $_SESSION[ $cls->table_name ][ 'filter' ][ 'additional_where' ] ){
								$other_data["query"][ 'where' ] = $_SESSION[ $cls->table_name ][ 'filter' ][ 'additional_where' ];
							}
						}
						
						if( $plugin )$other_data["plugin"] = $plugin;
						if( $mine_plugin )$other_data["mine_plugin"] = $mine_plugin;
						
						$other_data["table"] = $cls->table_name;
						$other_data["current_tab"] = $current_tab;
						$other_data["selected_id"] = $selected_id;
						$data["event"]["data"] = $other_data;

						if( isset( $this->class_settings[ 'show_all_fields' ] ) && $this->class_settings[ 'show_all_fields' ] ){
							$data["show_all_fields"] = $this->class_settings[ 'show_all_fields' ];
						}

						if( isset( $other_data[ 'configurations' ] ) && $other_data[ 'configurations' ] ){
							$data["configurations"] = $other_data[ 'configurations' ];
						}

						$data[ 'action_to_perform' ] = $action_to_perform;
						
						$filename = 'export-csv-data';
						$base->class_settings[ 'data' ] = $data;
						$base->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
						
						$cp = dirname( dirname( __FILE__ ) ) . '/tmp/filescache/'.$this->class_settings[ 'database_name' ].'/';
						$dn	= $this->table_name.'/completed-'.$this->class_settings[ 'user_id' ];
						$before_html = '';
						$gid = get_new_id();
						$gsn = 0;

						// print_r( $cp . $dn );exit;
						if( file_exists( $cp . $dn ) ){
						    foreach( scandir( $cp . $dn ) as $file ){
						    	$fl = $cp . $dn . "/" . $file;
								
								if ( is_file( $fl ) ) {
									$p = json_decode( file_get_contents( $fl ), true );
									$fln = str_replace( '.json', '', $file );
									// print_r( $p );exit;

									if( isset( $p[ 'msg' ] ) && $p[ 'msg' ] ){
										// if( $before_html )$before_html .= '<br>';
										$name = isset( $p[ 'name' ] ) ? ' <strong>[ '. $p[ 'name' ] .' ]</strong>' : '';
										$clr = '&nbsp;&nbsp;<a href="#" class="btn btn-md btn-default custom-single-selected-record-button" action="?action='. $this->table_name .'&todo=clear_completed_export&directory_name='. $dn .'&file_name='. $fln .'&html_replacement_selector='. $fln .'" override-selected-record="'. $fl .'" title="Clear Notification">Clear '. $name .'</a>';
										$before_html .= $base->_display_notification( array( "type" => "success", "message" => '<div id="'. $fln .'">'.$p[ 'msg' ].$clr.'</div>' ) )[ 'html' ];
									}
								}
							}
						}

						// print_r( $before_html );exit;

						// print_r( $data );exit;
						$html = $base->_get_html_view();
						if( ! $html )$html = '';

						if( $before_html ){
							$before_html = '<fieldset style="border:1px dashed; margin-bottom:20px; padding:10px;">  <legend>Export History:</legend>'.$before_html . '</fieldset>';
						}
						$html = $before_html . $html;

						$js = array( "prepare_new_record_form_new" );
						
						if( isset( $this->class_settings["return_html"] ) && $this->class_settings["return_html"] ){
							$return = array();
							$return["status"] = "new-status";						
							$return["html_replacement_selector"] = $this->class_settings["return_html"];
							$return["html_replacement"] = $html;
							$return["javascript_functions"] = isset( $_GET[ 'callback' ] ) ? array_merge( array( $_GET[ 'callback' ] ), $js ) : $js;
							
							return $return;
						}
						
						$base->class_settings[ 'modal_dialog_style' ] = ' width:40%; ';
						$base->class_settings[ 'modal_title' ] = 'Export '.$cls->label.' Data';
						
						return $base->_launch_popup( $html, '#dash-board-main-content-area', $js );
					}else{
						$error_msg = '<h4>Invalid Data Source Class</h4>';
					}
				}else{
					$error_msg = '<h4>Invalid Data Source</h4>';
				}
				
			break;
			}
			
			$error_msg .=  '<p>'. $this->label . '::' . $action_to_perform . '</p>';
			
			return $base->_display_notification( array( "type" => "error", "message" => $error_msg ) );
		}
		
		protected function _get_transformed_data_page( $options = array() ){
			
			if( isset( $options["view_name"] ) && $options["view_name"] ){
				$view_name = $options["view_name"];
				$header = isset( $options["header"] )?$options["header"]:array();
				$format = isset( $options["format"] )?$options["format"]:'';
				$no_page_header = isset( $options["no_page_header"] )?$options["no_page_header"]:'';
				$use_keys_in_result = isset( $options["use_keys_in_result"] )?$options["use_keys_in_result"]:'';
				$tables = isset( $options["tables"] )?$options["tables"]:array();
				$flbl = isset( $options["flbl"] )?$options["flbl"]:array();
				$tcl = isset( $options["tcl"] )?$options["tcl"]:(object) array();
				$ddx = isset( $options["data"] )?$options["data"]:array();
				$db_mode = isset( $options["db_mode"] )?$options["db_mode"]:'';
				$use_field_keys = isset( $options["use_field_keys"] )?$options["use_field_keys"]:0;

				if( isset( $options["page_offset"] ) && isset( $options["page_size"] ) && $options["page_size"] ){
					$page_size = $options["page_size"];
					$offset = $options["page_offset"];
					$page_limit = intval( isset( $options["page_limit"] )?$options["page_limit"]:0 );
					$current_loop_count = intval( isset( $options["current_loop_count"] )?$options["current_loop_count"]:0 );
					
					switch( $db_mode ){
					case 'elasticsearch':
					break;
					default:
						$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`". $view_name ."` LIMIT ". $offset . ", " . $page_size;
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'SELECT',
							'set_memcache' => 0,
							'tables' => array(),
						);
						$ddx = execute_sql_query($query_settings);
					break;
					}

					$txd = array();
					
					if( isset( $ddx[0] ) && ! empty( $ddx[0] ) ){
						$count = 0;
						
						$page_no = ( $offset / $page_size ) + 1;
						if( ! $no_page_header ){
							if( get_hyella_development_mode() ){
								$txd[][] = 'Page ' . $page_no;
							}
						}
						
						if( ! empty( $header ) && ! ( isset( $options["no_header"] ) && $options["no_header"] ) ){
							$row = array();
							foreach( $header as $hv ){
								$row[] = $hv["text"];
							}
							$txd[] = $row;
						}
						
						// print_r( $current_loop_count . '---' ); 
						// print_r( $page_size . '--' ); 
						// print_r( $page_limit . '--' ); 
						// print_r( $offset ); exit;
						foreach( $ddx as $dv ){
							
							if( $page_limit && ( $count + ( $page_size * $current_loop_count ) ) >= $page_limit ){
							// if( $page_limit && ( $count + $offset ) >= $page_limit ){
								break;
							}
							++$count;
							
							$row = array();

							foreach( $dv as $dk => $dv2 ){
								if( isset( $header[ $dk ][ 'text' ] ) && $header[ $dk ][ 'text' ] ){
									$flbl = $header[ $dk ][ 'text' ];
								}else if( isset( $header[ $dk ][ 'labels' ][ 'text' ] ) && $header[ $dk ][ 'labels' ][ 'text' ] ){
									$flbl = $header[ $dk ][ 'labels' ][ 'text' ];
								}else if( isset( $header[ $dk ][ 'labels' ][ 'display_field_label' ] ) && $header[ $dk ][ 'labels' ][ 'display_field_label' ] ){
									$flbl = $header[ $dk ][ 'labels' ][ 'display_field_label' ];
								}else{
									$flbl = isset( $header[ $dk ][ 'labels' ][ 'form_field' ] ) ? $header[ $dk ][ 'labels' ][ 'form_field' ] : $dk;
								}

								if( $use_field_keys ){
									$flbl = $dk;
								}

								// echo '<pre>';
								// print_r( $dv2 );
								// print_r( $header );exit;
								if( isset( $header[ $dk ]["labels"] ) ){

									$check_class = 0;
									switch( $format ){
									case 'json_formatted':
										if( isset( $flbl[ $dk ] ) ){
											switch( $flbl[ $dk ] ){
											case 'data':
												if( is_object( $tcl ) && method_exists( $tcl, 'format_data_export' ) ){
													$check_class = $tcl->format_json_api( array( 'value' => $dv2 ) );
												}
											break;
											}
										}
									break;
									}

									if( $check_class ){
										if( $use_keys_in_result ){
											$row[ $flbl ] = $check_class;
										}else{
											$row[] = $check_class;
										}
									}else{
										if( isset( $header[ $dk ]["labels"][ 'calculations' ] ) ){
											$header[ $dk ]["labels"][ 'calculations' ][ 'variables' ][ 0 ][ 0 ] = $dk;
										}
										
										$gbal = array(
											'labels' => array( $dk => $header[ $dk ]["labels"] ),
											'fields' => array( $dk => $dk ),
										);
										$vall = '';
										if( isset( $header[ $dk ][ 'field' ] ) && isset( $this->system_fields[ $header[ $dk ][ 'field' ] ] ) && $this->system_fields[ $header[ $dk ][ 'field' ] ] ){
											$vall = $dv2;
										}else{
											$aggr = '';
											if( isset( $tcl->table_name ) && isset( $tables[ $tcl->table_name ][ $dk ][ 'aggregation' ] ) ){
												$aggr = $tables[ $tcl->table_name ][ $dk ][ 'aggregation' ];
											}

											// print_r( $gbal );

											switch( $aggr ){
											case "daily_date":
											case "weekly_date":
											case "monthly_date":
											case "yearly_date":
											case "avg_resolve_hours":
											case "avg_resolve_days":
												$vall = $dv2;
											break;
											default:
												$vall = __get_value( trim( $dv2 ? $dv2 : '' ), $dk, array( 'globals' => $gbal ) );
												// $vall = 'mike';
											break;
											}
										}

										if( $use_keys_in_result ){
											$row[ $flbl ] = $vall;
										}else{
											$row[] = $vall;
										}
									}
								}else{
									if( $use_keys_in_result ){
										$row[ $flbl ] = trim( $dv2 );
									}else{
										$row[] = trim( $dv2 );
									}
								}
								
								unset( $dv[ $dk ] );
							}
							// exit;
							$txd[] = $row;
						}
						
						$return = array( 'data' => $txd, 'view_name' => $view_name, 'header' => $header, 'format' => $format, 'tables' => $tables, 'tcl' => $tcl, 'flbl' => $flbl );
						
						if( $count >= $page_size ){
							//check for next page
							$return['page_offset'] = $offset + $page_size;
							$return['page_size'] = $page_size;
							$return['page_limit'] = $page_limit;
							$return['current_loop_count'] = ++$current_loop_count;
						}else{
							switch( $db_mode ){
							case 'elasticsearch':
							break;
							default:
								$query = "DROP VIEW `".$this->class_settings['database_name']."`.`".$view_name."`";
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query,
									'query_type' => 'EXECUTE',
									'set_memcache' => 0,
									'tables' => array(),
								);
								execute_sql_query($query_settings);
							break;
							}
						}
						
						return $return;
					}
					
				}
			}
		}
		
		protected function _get_transformed_data( $data = array() ){
			$error_msg = '<h4>Unknown Transformation Error</h4>';
			$error_msg2 = '';
			$base = new cBase_class();
			$base->class_settings = $this->class_settings;
			
			$no_default_group = isset( $data[ 'no_default_group' ] )?$data[ 'no_default_group' ]:0;
			$format = isset( $data[ 'format' ] )?$data[ 'format' ]:'csv';
			$use_field_keys = isset( $data[ 'use_field_keys' ] )?$data[ 'use_field_keys' ]:0;
			$page_size = intval( isset( $data[ 'page_size' ] )?$data[ 'page_size' ]:0 );
			if( ! $page_size ){
				$page_size = 500;
			}
			if( $page_size > $this->max_export_page_size ){
				$page_size = $this->max_export_page_size;
			}
			
			$page_limit = intval( isset( $data[ 'page_limit' ] )?$data[ 'page_limit' ]:0 );
			if( ! $page_limit ){
				$page_limit = $this->max_export_page_limit;
			}
			if( $page_limit > $this->max_export_page_limit ){
				$page_limit = $this->max_export_page_limit;
			}
			// print_r( $page_limit );exit;
			
			$offset = intval( isset( $data[ 'page_offset' ] )?$data[ 'page_offset' ]:0 );

			$exclude_fields = array(
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

			$db_mode = '';
			if( isset( $this->class_settings[ 'db_mode' ] ) && $this->class_settings[ 'db_mode' ] ){
				$db_mode = $this->class_settings[ 'db_mode' ];
			}
			
			if( isset( $data[ 'tables' ] ) && is_array( $data[ 'tables' ] ) && ! empty( $data[ 'tables' ] ) ){
				$replace_where = isset( $data[ 'query' ][ 'replace_where' ] )? $data[ 'query' ][ 'replace_where' ] : 0;
				$replace_join = isset( $data[ 'query' ][ 'replace_join' ] )? $data[ 'query' ][ 'replace_join' ] : 0;
				
				$where = ( isset( $data[ 'query' ][ 'where' ] ) && $data[ 'query' ][ 'where' ] ) ? ( $data[ 'query' ][ 'where' ] . ' ' ) : '';
				
				$order = ( isset( $data[ 'query' ][ 'order' ] ) && $data[ 'query' ][ 'order' ] ) ? ( $data[ 'query' ][ 'order' ] . ' ' ) : '';
				
				$group = ( isset( $data[ 'query' ][ 'group' ] ) && $data[ 'query' ][ 'group' ] ) ? ( $data[ 'query' ][ 'group' ] . ' ' ) : '';
				$join = ( isset( $data[ 'query' ][ 'join' ] ) && $data[ 'query' ][ 'join' ] ) ? ( $data[ 'query' ][ 'join' ] . ' ' ) : '';
				
				$display_limit = isset( $data['query']['limit'] ) && $data['query']['limit'] ? $data['query']['limit'] : 0;
				
				$additional_join = '';

				$nfl = array();
				$header = array();
				$select = array();
				$select2 = array();
				$tbs = array();
				$sn = 0;

				// ORM
				$Oselect = array();
				$Ojoin = array();
				$Ofrom = array();
				$Oview = array();
				$Ogroup = array();
				$Olimit = array();
				$Oorder = array();
				$Owhere = array();
				
				$use_selects = isset( $data[ 'use_selects' ] ) ? $data[ 'use_selects' ] : array();
				$use_groups = isset( $data[ 'use_groups' ] ) ? $data[ 'use_groups' ] : array();
				$use_sorts = isset( $data[ 'use_sorts' ] ) ? $data[ 'use_sorts' ] : array();
				$no_concat = isset( $data[ 'no_concat' ] ) ? $data[ 'no_concat' ] : 0;
				$use_keys_in_result = isset( $data[ 'use_keys_in_result' ] ) ? $data[ 'use_keys_in_result' ] : 0;
				$no_page_header = isset( $data[ 'no_page_header' ] ) ? $data[ 'no_page_header' ] : 0;

				if( isset( $data[ 'table' ] ) && $data[ 'table' ] ){
					$tcl = 'c'.ucwords( $data[ 'table' ] );

					if( class_exists( $tcl ) ){
						// print_r( $tcl );exit;
						$tcl = new $tcl;

						if( isset( $tcl->children ) && is_array( $tcl->children ) && ! empty( $tcl->children ) ){

							$dd = array();
							$dd[ 'plugin' ] = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
							$dd[ 'ptable' ] = $data[ 'table' ];
							$dd[ 'tables' ] = array_keys( $tcl->children );
							$dd[ 'get_join' ] = 1;

							$sh = new cSearch();
							$sh->class_settings = $this->class_settings;

							if( isset( $this->class_settings[ 'mine_data_source' ] ) && $this->class_settings[ 'mine_data_source' ] ){
								$sh->class_settings[ 'data' ][ 'social_registry' ] = $this->class_settings[ 'mine_data_source' ];
							}

							// @mx look later
							if( ! $join )$join .= $sh->_execute_search_window_query( $dd );

							if( is_array( $join ) ){
								return $join;
							}
							unset( $sh );
						}
					}
				}

				if( ! $no_default_group ){
					if( count( $data[ 'tables' ] ) == 1 && ! $group ){
						$group = " GROUP BY `". $data[ 'table' ] ."`.`id` ";
					}
				}

				$sss = new cSearch;
				$allLabels = [];
				if( class_exists( 'cNwp_reports' ) ){
					$nwr = new cNwp_reports;
					$nwr->class_settings = $this->class_settings;
					$als = $nwr->load_class( [ 'class' => [ 'reports_bay' ], 'initialize' => 1 ] );
					$sss = $als[ 'reports_bay' ];
				}
				
				foreach( $data[ 'tables' ] as $key => $value ){
					$tbs[ $key ] = $key;
					$tb = 'c' . ucwords( $key );

					if( isset( $data[ 'query' ][ 'query_options' ] ) && $data[ 'query' ][ 'query_options' ] ){
						$pp = $_POST;
						$_POST[ 'data' ] = json_encode( array( 'cart_items' => $data[ 'query' ][ 'query_options' ], 'dataSource' => $key ) );
						$_POST[ 'plugin' ] = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
						$qq = $sss->_execute_search_window_query( array( 'return_query' => 1 ) );

						if( is_array( $qq ) ){
							$Owhere = array_merge( $qq, $Owhere );
						}
						$_POST = $pp;
					}
					
					$sq = md5('search_query'.$_SESSION['key']);
					if( isset( $qq ) && ! is_array( $qq ) ){
						$where .= ' AND '.$qq;
					}elseif( isset( $_SESSION[$sq][ $key ]['query'] ) && $_SESSION[$sq][ $key ]['query'] ){
						
						$cache_key = md5( $key . '-' . $this->class_settings[ 'user_id' ] . '-search');
						$stx = array(
							'cache_key' => $cache_key,
							'directory_name' => 'search',
							'permanent' => true,
						);
						$da = get_cache_for_special_values( $stx );

						if( isset( $da[ 'query' ] ) && $da[ 'query' ] ){
							$where .= " AND " . $da[ 'query' ];
						}
					}

					// Gets the where tied to a datatable
					if( ! ( isset( $this->class_settings[ 'ignore_cache_conditions' ] ) && $this->class_settings[ 'ignore_cache_conditions' ] ) ){
						if( isset( $_SESSION[ $key ]['filter'][ 'additional_where' ] ) && $_SESSION[ $key ]['filter'][ 'additional_where' ] ){
							$where .= $_SESSION[ $key ]['filter'][ 'additional_where' ];
						}
					}
					
					if( class_exists( $tb ) && function_exists( $key ) ){
						$cl = new $tb();
						$labels = $key();
						$allLabels[ $cl->table_name ] = $labels;

						if( isset( $data[ 'plugin' ] ) && $data[ 'plugin' ] ){
							$cl->plugin = $data[ 'plugin' ];
						}
						
						if( ! isset( $data[ 'table' ] ) ){
							$data[ 'table' ] = $key;
						}
						
						foreach( $value as $k => $v ){
							$arg = array();
							$fd = '';
							if( isset( $v[ 'field' ] ) && $v[ 'field' ] ){
								$k = $v[ 'field' ];
							}

							if( isset( $cl->table_fields[ $k ] ) ){
								$fd = $cl->table_fields[ $k ];
							}

							if( ! $fd && isset( $exclude_fields[ $k ] ) ){
								$fd = $exclude_fields[ $k ];
							}

							if( $fd ){
								$v1 = $fd;
								
								$t = $k;
								
								switch( $format ){
								case "json":
									if( isset( $v[ 'alias' ] ) ){
										$t = $v[ 'alias' ];
									}
									$rt = "'" . $t ."' ";
								break;
								default:
									if( isset( $v[ 'alias' ] ) ){
										$t = $v[ 'alias' ];
									}else if( isset( $exclude_fields[ $k ] ) ){
										$t = $v1;
									}else if( isset( $labels[ $v1 ]["text"] ) ){
										$t = $labels[ $v1 ]["text"];
									}else if( isset($labels[ $v1 ]["abbreviation"] ) && $labels[ $v1 ]["abbreviation"] ){
										$t = $labels[ $v1 ]["abbreviation"];
									}else{
										$t = $labels[ $v1 ]["field_label"];
									}

									if( isset( $cl->basic_data[ 'include_in_export' ][ $v1 ] ) && $cl->basic_data[ 'include_in_export' ][ $v1 ] ){
										$t = $cl->basic_data[ 'include_in_export' ][ $v1 ];
									}
									
									++$sn;
									$rt_value = 'f'.$sn;

									switch( $db_mode ){
									case 'elasticsearch':
										$rt_value = $k;
									break;
									}

									switch( $format ){
									case "json_formatted":
										$rt_value = $k;
									break;
									}

									if( isset( $v[ 'alias' ] ) && $v[ 'alias' ] ){
										$rt_value = $v[ 'alias' ];
									}
									$rt = "'". $rt_value ."' ";
									
									$header[ $rt_value ] = array(
										"table" => $key,
										"key" => $k,
										"field" => $v1,
										"text" => $t,
										"labels" => isset( $labels[ $v1 ] )?$labels[ $v1 ]:array(),
									);
									
								break;
								}

								$fxll = trim( str_replace( "'", '', $rt ) );
								switch( $format ){
								case "json_formatted":
								case "csv":
									$flx = "`". $key ."`.`". $v1 ."`";
									$aggr = '';
									if( isset( $v[ 'aggregation' ] ) && $v[ 'aggregation' ] ){
										$aggr = $v[ 'aggregation' ];
									}elseif( isset( $use_selects[ $key ][ $k ] ) && $use_selects[ $key ][ $k ] ){
										$aggr = $use_selects[ $key ][ $k ];
									}
									if( $aggr ){
										$arg[ $aggr ] = $aggr;
										switch( $aggr ){
										case 'min':
											$flx = " MIN( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'max':
											$flx = " MAX( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'count':
											$flx = " COUNT( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'sum':
											$flx = " SUM( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'avg':
											$flx = " AVG( `". $key ."`.`". $v1 ."` ) ";
										break;
										case 'percentage':
											$flx = " ROUND( COUNT( `". $key ."`.`". $v1 ."` ) * 100.0 / ( SELECT COUNT(*) FROM `". $key ."` ), 2 ) ";
										break;
										case 'daily_date':
											$flx = " DATE( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) ";
										break;
										case 'weekly_date':
											$flx = " CONCAT( YEARWEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ), '-W', WEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) ) ";
										break;
										case 'monthly_date':
											$flx = " DATE_FORMAT( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ), '%Y-%m' ) ";
										break;
										case 'yearly_date':
											$flx = " DATE_FORMAT( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ), '%Y' ) ";
										break;
										case 'avg_resolve_hours':
										case 'avg_resolve_days':
											$ssd = '';
											$eed = '';
											if( isset( $value[ 'start_date' ][ 'field' ] ) && $value[ 'start_date' ][ 'field' ] ){
												if( isset( $cl->table_fields[ $value[ 'start_date' ][ 'field' ] ] ) ){
													$ssd = $cl->table_fields[ $value[ 'start_date' ][ 'field' ] ];
												}else{
													$ssd = $value[ 'start_date' ][ 'field' ];
												}
											}
											if( isset( $value[ 'end_date' ][ 'field' ] ) && $value[ 'end_date' ][ 'field' ] ){
												if( isset( $cl->table_fields[ $value[ 'end_date' ][ 'field' ] ] ) ){
													$eed = $cl->table_fields[ $value[ 'end_date' ][ 'field' ] ];
												}else{
													$eed = $value[ 'end_date' ][ 'field' ];
												}
											}

											if( $ssd && $eed ){
												$ddr = '60 * 60';

												switch( $aggr ){
												case 'avg_resolve_days':
													$ddr = '24 * 60 * 60';
												break;
												}

												$flx = " AVG( ( `". $key ."`.`". $eed ."` - `". $key ."`.`". $ssd ."` ) / ( ". $ddr ." ) ) ";
											}else{
												continue 3;
											}
										break;
										}
									}

									$ffn = '_get_'.$k;
									if( method_exists( $cl, $ffn ) ){
										$cl->class_settings = $this->class_settings;
										$fl2 = $cl->$ffn();
										if( is_array( $fl2 ) && ! empty( $fl2 ) && isset( $fl2[ 'query' ] ) && $fl2[ 'query' ] ){
											$flx = " ( ". $fl2[ 'query' ] ." ) ";
										}

										if( isset( $fl2[ 'group' ] ) && $fl2[ 'group' ] && count( $data[ 'tables' ] ) > 1 ){
											if( isset( $fl2[ 'overide_group' ] ) && $fl2[ 'overide_group' ] ){
												$group = " GROUP BY ".$fl2[ 'group' ];
											}else{
												if( $group ){
													$group .= ", " . $fl2[ 'group' ];
												}else{
													$group = " GROUP BY ".$fl2[ 'group' ];
												}
											}

										}
									}

									$as = " as ". $rt;
									if( isset( $data[ 'use_outfile' ] ) && $data[ 'use_outfile' ] ){
										$select2[] = $header[ $fxll ][ 'text' ];
										// $as = "";
									}

									$qqy = '';
									if( isset( $no_concat ) && $no_concat ){
										$qqy = $flx . $as;
									}else{
										$qqy = " CONCAT( ". '"\\t"' .", ". $flx ." ) " . $as;
										$arg[ 'concat' ] = array(
											'params' => array(
												"\\t",
												"__field__",
											)
										);
									}
									$select[] = $qqy;

									$cols = array(
										'field' => $v1,
										'table' => $key,
										'alias' => $fxll,
										'aggregation' => $arg,
									);
									if( $aggr ){
										switch( $aggr ){
										case 'percentage':
										case 'daily_date':
										case 'weekly_date':
										case 'monthly_date':
										case 'yearly_date':
										case 'avg_resolve_hours':
										case 'avg_resolve_days':
											$cols[ 'custom' ] = $flx;
										break;
										}
									}
									$Oselect[] = $cols;
								break;
								default:
									if( isset( $use_selects[ $key ][ $k ] ) && $use_selects[ $key ][ $k ] ){
										switch( $use_selects[ $key ][ $k ] ){
										case 'min':
											$select[] = " MIN( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'max':
											$select[] = " MAX( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'count':
											$select[] = " COUNT( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'sum':
											$select[] = " SUM( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'avg':
											$select[] = " AVG( `". $key ."`.`". $v1 ."` ) as " . $rt;
										break;
										case 'daily_date':
											$select[] = " DATE( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) as " . $rt;
										break;
										case 'weekly_date':
											$select[] = " CONCAT( YEARWEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ), '-W', WEEK( FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ) ) ) as " . $rt;
										break;
										case 'monthly_date':
											$select[] = " DATE_FORMAT(FROM_UNIXTIME( `". $key ."`.`". $v1 ."` ), '%Y-%m' ) as " . $rt;
										break;
										default:
											$select[] = " `". $key ."`.`". $v1 ."` as " . $rt;
										break;
										}
									}else{
										$select[] = " `". $key ."`.`". $v1 ."` as " . $rt;
									}
								break;
								}
								$nfl[ $fxll ] = $k;
							}
						}
						
						if( isset( $use_groups[ $key ] ) && $use_groups[ $key ] ){
							$group = "";

							foreach( $use_groups[ $key ] as $gpp => $gppc ){
								$gxp = $gpp;
								$gxp2 = array(
									'alias' => $gpp,
								);

								if( isset( $cl->table_fields[ $gpp ] ) && $cl->table_fields[ $gpp ] ){
									$gxp = " `". $key ."`.`". $cl->table_fields[ $gpp ] ."` ";
									$gxp2 = array(
										'table' => $key,
										'field' => $cl->table_fields[ $gpp ],
									);
									switch( $db_mode ){
									case 'elasticsearch':
										$gxp2[ 'alias' ] = $gpp;
									break;
									}
									
									$header[ $gpp ] = array(
										"table" => $key,
										"key" => $gpp,
										"field" => $cl->table_fields[ $gpp ],
										"text" => $gpp,
										"labels" => isset( $labels[ $cl->table_fields[ $gpp ] ] )?$labels[ $cl->table_fields[ $gpp ] ]:array(),
									);

								}

								if( $gxp ){
									$gxp2['type'] = $gppc;
									$Ogroup[] = $gxp2;
									if( $group ){
										$group .= " , ".$gxp;
									}else{
										$group = " GROUP BY ".$gxp;
									}
								}
							}
						}

						if( isset( $use_sorts[ $key ] ) && $use_sorts[ $key ] ){
							$order = "";
							if( isset( $use_sorts[ $key ][ 'sort_asc' ] ) && $use_sorts[ $key ][ 'sort_asc' ] ){
								$ofl = isset( $cl->table_fields[ $use_sorts[ $key ][ 'sort_asc' ] ] ) ? $cl->table_fields[ $use_sorts[ $key ][ 'sort_asc' ] ] : $use_sorts[ $key ][ 'sort_asc' ];
								$order = " ORDER BY `". $key ."`.`". $ofl ."` ASC ";
								$Oorder = array(
									'field' => $ofl,
									'table' => $key,
									'sort' => 'asc', // desc/asc
								);
							}elseif( isset( $use_sorts[ $key ][ 'sort_desc' ] ) && $use_sorts[ $key ][ 'sort_desc' ] ){
								$ofl = isset( $cl->table_fields[ $use_sorts[ $key ][ 'sort_desc' ] ] ) ? $cl->table_fields[ $use_sorts[ $key ][ 'sort_desc' ] ] : $use_sorts[ $key ][ 'sort_desc' ];
								$order = " ORDER BY `". $key ."`.`". $ofl ."` DESC ";
								$Oorder = array(
									'field' => $ofl,
									'table' => $key,
									'sort' => 'desc', // desc/asc
								);
							}
						}

						unset( $cl );
						unset( $labels );
					}else{
						$error_msg2 = '<h4><strong>Class Not Found</strong></h4>'.$tb;
					}
				}

							
				if( ! $error_msg2 ){
					if( ! empty( $select ) && isset( $data[ 'table' ] ) && $data[ 'table' ] ){
						$tx = $data[ 'table' ];
						
						if( $replace_join ){
							$join = ( isset( $data[ 'query' ][ 'join' ] ) && $data[ 'query' ][ 'join' ] ) ? ( $data[ 'query' ][ 'join' ] . ' ' ) : '';
						}
						
						if( ! $join && isset( $data[ 'query' ][ 'join' ] ) && $data[ 'query' ][ 'join' ] ){
							$join = $data[ 'query' ][ 'join' ];
						}

						// unset( $data );
						//create view and return header
						
						$sel = "SELECT " . implode(", ", $select ) . " FROM `". $this->class_settings["database_name"] ."`.`". $tx ."` ";
						$where1 = " WHERE `". $tx ."`.`record_status` = '1' ";
						
						if( $replace_where ){
							$where1 = '';
						}

						// echo '<pre>';
						// print_r( $data[ 'tables' ] );exit;
						switch( $format ){
						case "json":
							$query = $sel . $join . $where1 . $where . $group . $order . " LIMIT ". $offset .",". $page_limit;
							// echo "<pre>";print_r( $query );echo "</pre>";exit; 
							
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'SELECT',
								'set_memcache' => 1,
								'tables' => array( 'myexcel' ),
							);
							
							$array = array();
							if( get_hyella_development_mode() ){
								$array["query"] = $query;
							}
							
							$array["data"] = execute_sql_query($query_settings);
							
							return $array;
						break;
						default:
							$view_name = 'csv_' . get_new_id('cs').rand(1, 100); //@steve 19/07/2023  Issue where $view_name is not unique enough when mulltiple requests hit the server at the same time
							// $view_name = 'aaa';
							// echo $view_name;
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query_type' => 'EXECUTE',
								'set_memcache' => 0,
								'tables' => array(),
							);

							if( isset( $data[ 'use_outfile' ] ) && $data[ 'use_outfile' ] ){
								$p1 = dirname( dirname( __FILE__ ) );
								if( ! is_dir( $p1 . '/tmp' ) ){
									create_folder( $p1 . '/tmp', '', '', 0777 );
								}
								if( ! is_dir( $p1 . '/tmp/' . $this->table_name ) ){
									create_folder( $p1 . '/tmp/' . $this->table_name, '', '', 0777 );
								}
								$query = "CREATE OR REPLACE VIEW `".$this->class_settings['database_name']."`.`".$view_name."` AS ( SELECT '". implode( "', '", $select2 ) ."' UNION ".  $sel . $join . $additional_join . $where1 . $where . $group . $order ." ) ";
								
								$p3 = '\\\\tmp\\\\' . $this->table_name ."\\\\". $view_name . '.csv';
								$p2 = str_replace( '\\', '\\\\', $p1 ) . $p3;
								// print_r( $p2 );exit; 
								$query_settings[ 'query' ] = $query;
								execute_sql_query($query_settings);

								$query = " SELECT * INTO OUTFILE '". $p2 ."' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '". '"' ."' LINES TERMINATED BY ". '"\\n"' ." FROM `". $this->class_settings["database_name"] ."`.`". $view_name ."` ";

								$query_settings[ 'query' ] = $query;
								execute_sql_query($query_settings);

								// Export the file
								// drop the view

								$query = " DROP VIEW `".$this->class_settings['database_name']."`.`".$view_name."` ";

								$query_settings[ 'query' ] = $query;
								execute_sql_query($query_settings);

								return array(
									'file_path' => $p3,
									'view_name' => $view_name,
									'data' => array()
								);
							}else{

								switch ( $db_mode ) {
									case 'elasticsearch':
										if( $display_limit ){
											$Olimit = [ $display_limit ];
										}
									break;
								}

								$Ofrom = array(
									'database' => $this->class_settings['database_name'],
									'table' => $tx,
								);

								$Oview = array(
									'view_name' => $view_name,
									'database' => $this->class_settings['database_name'],
								);
								
								$query = "CREATE OR REPLACE VIEW `".$this->class_settings['database_name']."`.`".$view_name."` AS ( ".  $sel . $join . $additional_join . $where1 . $where . $group . $order ." ) ";
								// $db_mode = '';
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									// 'query' => $query,
									'query' => array(
										'columns' => $Oselect,
										'from' => $Ofrom,
										'joins' => $Ojoin,
										'where' => $Owhere,
										'group' => $Ogroup,
										'order' => $Oorder,
										'limit' => $Olimit,
										'view' => $Oview,
									),
									'allLabels' => $allLabels,
									'query_type' => 'EXECUTE',
									'query_type' => 'SELECT',
									'db_mode' => $db_mode,
									'set_memcache' => 0,
									'tables' => array( $view_name ),
								);

								$dxd = execute_sql_query( $query_settings );
								// print_r($dxd);exit;
								if( isset( $dxd[ 'error' ] ) && $dxd[ 'error' ] ){
									return $dxd;
								}

								switch( $db_mode ){
								case 'elasticsearch':
								break;
								default:
									$dxd = array();
								break;
								}

								$no_header = isset( $this->class_settings[ 'no_header' ] ) ? $this->class_settings[ 'no_header' ] : 0;
								return $this->_get_transformed_data_page( 
									array( 
										'db_mode' => $db_mode, 
										'data' => $dxd, 
										'tables' => $data[ 'tables' ], 
										'flbl' => $nfl, 
										'tcl' => ( isset( $tcl ) ? $tcl : (object) array() ), 
										'format' => $format, 
										"no_header" => $no_header, 
										"current_loop_count" => 0, 
										"header" => $header, 
										"use_keys_in_result" => $use_keys_in_result, 
										"view_name" => $view_name, 
										"page_size" => $page_size, 
										"page_offset" => $offset, 
										"page_limit" => $page_limit,
										"no_page_header" => $no_page_header,
										"use_field_keys" => $use_field_keys,
									)
								);
							}

							// echo "<pre>";print_r( $query );echo "</pre>";exit; 
							// print_r( $where );exit; 
							
						break;
						}
						
						
					}else{
						$error_msg = '<h4>Invalid Parameters</h4>Selection fields or Data Source not defined';
					}
				}else{
					$error_msg = $error_msg2;
				}
			}else{
				$error_msg = '<h4>Invalid Transformation Data</h4>';
			}
			
			return $base->_display_notification( array( "type" => 'error', "message" => $error_msg ) );
		}
		
		private function _check_background_export_to_excel(){
			if( isset( $_POST["id"] ) && $_POST["id"] && isset( $_POST["mod"] ) && $_POST["mod"] ){
				$this->filename = $_POST["id"];
				$success_message = $_POST["mod"];
				
				sleep(8);
				
				if( file_exists( $this->filename ) ){
					//excel generation complete
					$err = new cError('010009');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = $success_message;
					$return = $err->error();	
					
					$return[ 'msg' ] = $success_message;
					return $return;
				}
				
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Still Exporting to Excel</h4><p>Date: '.date("d-M-Y H:i:s").'<br /><br />Please wait...</p>';
				$return = $err->error();
				
				unset( $return['html'] );
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['id'] = $this->filename;
				$return['mod'] = $success_message;
				$return['action'] = '?action='.$this->table_name.'&todo=check_background_export_to_excel';
				
				return $return;
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Unknown Export Error</h4><p>Please try again</p>';
			return $err->error();
		}
		
		private function _generate_large_excel_file(){
			$this->process_large_files = 0;
			$cache_key = "generate-excel-queue";
			$settings = array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$a = get_cache_for_special_values( $settings );
			if( $a && is_array( $a ) ){
				foreach( $a as $k => $file ){
					
					if( file_exists( $file . ".html" ) && file_exists( $file . ".json" ) ){
						$this->html = file_get_contents( $file . ".html" );
						unlink( $file . ".html" );
						
						$_POST = json_decode( file_get_contents( $file . ".json" ), true );
						unlink( $file . ".json" );
						
						$this->filename = $file;
						$this->_create();
					}
					
					unset( $a[$k] );
					$settings = array(
						'cache_key' => $cache_key,
						'cache_values' => $a,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					set_cache_for_special_values( $settings );
				}
			}
			
		}
		
		private function _generate_excel(){
			$returning_html_data = 'Generating Excel';
			$html_only = '';
			
			$authenticate_report = 0;
			$authenticated_report_id = 0;
			$this->html = '';
			
			//CHECK IF HTML DATA HAS BEEN RECEIVED
			if(isset($_POST['html']) && $_POST['html']){
				
				//Set Records Id
				$new_record_id_base = get_new_id();
				
				//Set Current Timestamp
				$current_date_time = date("U");
				
				//DATE REPORT WAS GENERATED
				$date_of_generation = date("jS F Y H:i");
				
				//Get Users Full Name
				$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
				if(isset($_SESSION[$current_user_details_session_key])){
					$current_user_session_details = $_SESSION[$current_user_details_session_key];
				}
				
				$creator = '';
				$created_by = '';
				if(isset($current_user_session_details['fname']) && isset($current_user_session_details['lname'])){
					$creator = $current_user_session_details['fname'].' '.$current_user_session_details['lname'];
					
					$created_by = '<br />Created By: ' . $creator;
				}
				
				//Set html Data
				$this->html .= $_POST['html'];
				
				$dir = create_report_directory( $this->class_settings );
				
				//Set Output File Name
				$this->filename = $dir.'/'.$new_record_id_base.'.xlsx';
				
				//report title
				$report_title = 'undefined';
				if( isset( $_POST[ 'report_title' ] ) && $_POST[ 'report_title' ] ){
					$report_title = strip_tags( $_POST[ 'report_title' ] );
					$this->report_title = $report_title;
				}
					
				//Generate Report
				$r = $this->_create();
				
				$file_url = str_replace( $this->class_settings[ 'calling_page' ], '', $dir ) . '/'.$new_record_id_base . '.xlsx';
				
				switch( $this->class_settings["action_to_perform"] ){
				case "generate_excel_front":
					$file_rule = $file_url;
					$file_url = "../engine/" . $file_url;
				break;
				}
					
				$success_message = 'File Created On: '.$date_of_generation . $created_by . '<br /><a class="btn btn-primary" href="' . $file_url . '" target="_blank" ><i class="icon-download-alt icon-white">&nbsp;&nbsp;</i> Download Excel File</a>';
					
				if( $r == "background" ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Export to Excel In Progress</h4><p>Please wait...</p>';
					$return = $err->error();
					
					unset( $return['html'] );
					$return['status'] = "new-status";
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['id'] = $this->filename;
					$return['mod'] = $success_message;
					$return['action'] = '?action='.$this->table_name.'&todo=check_background_export_to_excel';
					
					return $return;
				}
				
				//Check if report was created
				if( file_exists( $this->filename ) ){
					//ADD REPORT TO DATABASE
					$reports_handler = new cReports();
					$reports_handler->class_settings = $this->class_settings;
					$reports_handler->class_settings[ 'action_to_perform' ] = 'add_generated_report_record';
					
					$report_reference = 'undefined';
					if( isset( $_POST[ 'current_module' ] ) && $_POST[ 'current_module' ] ){
						$report_reference = $_POST[ 'current_module' ];	//table name
					}
					
					$reports_handler->class_settings[ 'file_properties' ] = array( 
						'file_name' => $report_title,
						'file_url' => $file_url,
						'file_reference' => $report_reference,
						'file_source' => $report_reference,
						'file_keywords' => $report_title,
						'file_description' => 'system generated report',
					);
					
					if( $reports_handler->reports() ){
						//successful notification
						$err = new cError('010009');
					}else{
						//record was not created
						$err = new cError('010010');
					}
					
					//SUCCESSFUL REPORT CREATION
					$err->action_to_perform = 'notify';
						
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_generate_excel';
					$err->additional_details_of_error = '';
						
					$return = $err->error();
					
					$return[ 'msg' ] = $success_message;
					
					return $return;
				}else{
					//REPORT CREATION FAILED ERROR
					$err = new cError('000008');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cmyexcel.php';
					$err->method_in_class_that_triggered_error = '_generate_pdf';
					$err->additional_details_of_error = 'could not create pdf on line 253';
	
					return $err->error();
				}
			}else{
				//NO DATA RECEIVED ERROR
				$err = new cError('000009');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cmyexcel.php';
				$err->method_in_class_that_triggered_error = '_generate_pdf';
				$err->additional_details_of_error = 'could not update pdf on line 253 because no data was received';

				return $err->error();
			}
			
			return array(
				'typ' => 'success',
				'html' => $returning_html_data
			);
		}
		
		private function _display_import_data_capture_form(){
			
			$import_table = '';
			unset( $_SESSION['temp_storage'][ 'excel_import_table' ] );
			if( isset($_GET[ 'search_table' ]) && $_GET[ 'search_table' ] ){
				$import_table = $_GET[ 'search_table' ];
				
				$_SESSION['temp_storage'][ 'excel_import_table' ] = $import_table;
			}
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'database_table_temp' ] = '_' . $import_table;
			
			$process_handler->class_settings[ 'form_heading_title' ] = 'Excel File Import';
			
			$process_handler->class_settings[ 'form_action_todo' ] = 'save_imported_excel_data';
			$process_handler->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo='.$process_handler->class_settings[ 'form_action_todo' ];
			
			$process_handler->class_settings['form_submit_button'] = 'Import Excel File';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			$process_handler->class_settings[ 'form_class' ] = 'form-horizontal';
			
			$process_handler->class_settings[ 'hidden_records_css' ] = array(
				'myexcel006' => $import_table,
			);
			
			$process_handler->class_settings[ 'form_values' ] = array(
				'myexcel006' => $import_table,
			);
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
			);
		}
		
		private function _save_imported_excel_data(){
			$returning_html_data = '';
			
			$import_table = '';
			
			if(isset($_POST['table']) && $_POST['table'] == $this->table_name ){
				
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
					
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_save';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 293';
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
				
				if( isset( $_POST[ 'myexcel006' ] ) && $_POST[ 'myexcel006' ] )
					$form->table_field_temp = '_' . $_POST[ 'myexcel006' ];
				/**************************************************************************/
			
				//2. Transform posted form data into array
				$field_values_pair = $form->myphp_post($fields);
				
				//3. Update the current step
				$form->step = $form->nextstep;
				
				//4. Pick current record id
				$this->record_id = $form->record_id;
				
				if( isset( $field_values_pair['form_data']['myexcel005'] ) && is_array( $field_values_pair['form_data']['myexcel005'] ) ){
					
					$file = explode( ':::', $field_values_pair['form_data']['myexcel005']['value'] );
					
					$name_of_file_to_be_imported = $this->class_settings['calling_page'] . $file[0];
					
				}
				
				if( isset( $field_values_pair['form_data']['myexcel006'] ) && $field_values_pair['form_data']['myexcel006'] ){
					
					$import_table = $field_values_pair['form_data']['myexcel006'][ 'value' ];
					
				}
				
				$mapping_options = '';
				if( isset( $field_values_pair['form_data']['myexcel007'] ) && $field_values_pair['form_data']['myexcel007'] ){
					
					$mapping_options = $field_values_pair['form_data']['myexcel007'][ 'value' ];
					
				}
				
				$update_existing_records_based_on_fields = '';
				
				if( isset( $field_values_pair['form_data']['myexcel008'][ 'value' ] ) && $field_values_pair['form_data']['myexcel008'][ 'value' ] == '200' && isset( $field_values_pair['form_data']['myexcel009'][ 'value' ] ) && $field_values_pair['form_data']['myexcel009'][ 'value' ] ){
					
					$update_existing_records_based_on_fields = $field_values_pair['form_data']['myexcel009'][ 'value' ];
					
				}
				
				if( isset( $name_of_file_to_be_imported ) && file_exists( $name_of_file_to_be_imported ) ){
					//Get Excel File And Create Insert Queries
					
					/** PHPExcel_IOFactory */
					require_once $this->class_settings['calling_page'].'classes/PHPExcel/IOFactory.php';
					
					$imported_records_details = array();
					
					$file_information = array();
					
					$file_information = pathinfo($name_of_file_to_be_imported);
					
					if ( isset( $file_information['extension'] ) && $file_information['extension'] == 'xls'  ) {
						/** Load File to be imported **/
						
						//Set function name that would be used to retrieve mapped fields for import
						$mapped_fields_function_name = $import_table;
						
						//Initialize mapped fields array
						$mapped_fields = array();
						
						if( function_exists( $mapped_fields_function_name ) ){
							$mapped_fields = $mapped_fields_function_name();
							
							//Add Field Id's Property
							$field_counter = 0;
							foreach( $mapped_fields as $key => $value ){
								$mapped_fields[$key][ 'field_id' ] = $key;
								
								//USE SERIAL IMPORT OPTION
								if( $mapping_options == '100' )
									$mapped_fields[ $field_counter ] = $mapped_fields[$key];
								
								++$field_counter;
							}
						}
						
						if(! empty($mapped_fields) ){
							$objPHPExcel = PHPExcel_IOFactory::load($name_of_file_to_be_imported);
							
							$array_of_dataset = array();
							$array_of_update_conditions = array();
							
							$new_record_id = get_new_id();
							$new_record_id_serial = 0;
							
							$ip_address = get_ip_address();
							$date = date("U");
							
							//$mapping_options == 200
							$row_counter = 0;
							
							$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
							foreach($rowIterator as $row){
								
								$dataset_to_be_inserted = array();
								$update_conditions_to_be_inserted = array();
								
								$cellIterator = $row->getCellIterator();
								
								$cell_counter = 0;
								
								$insert_recordset = false;
								
								foreach( $cellIterator as $cell ){
									$excel_column_name = $cell->getColumn();
									
									if( ( $mapping_options == '200' || $mapping_options == '400' ) && $row_counter == 0 ){
										
										$cell_value = strtolower( trim( $cell->getCalculatedValue() ) );
										
										if( $mapping_options == '400' && $cell_counter == 13 || $cell_counter == 14 ){
											switch( $cell_counter ){
											case 13:
												$cell_value = "current month request n'000";
											break;
											case 14:
												$cell_value = "current month request $'000";
											break;
											}
										}
										
										//Map Headings
										foreach( $mapped_fields as $key => $value ){
											
											if( strtolower( $mapped_fields[ $key ][ 'field_label' ] ) == $cell_value ){
												$mapped_fields[ $cell_counter ] = $mapped_fields[$key];
												break;
											}
										}
										
									}else{
										
										if( isset( $mapped_fields[ $cell_counter ] ) ){
											
											$form_field_data = $mapped_fields[ $cell_counter ];
											
											//Special Processing for Individual Tables
											switch( $form_field_data[ 'form_field' ] ){
											case 'select':
												$select_value_key = '';
												
												$select_value = ucwords( strtolower( trim( $cell->getCalculatedValue() ) ) );
												
												if( isset( $form_field_data[ 'form_field_options' ] ) ){
												
													$select_value_array_function = $form_field_data[ 'form_field_options' ];
													
													if( function_exists( $select_value_array_function ) ){
														
														$select_value_array = $select_value_array_function();
														
														if( is_array( $select_value_array ) ){
														
															if( in_array( $select_value , $select_value_array ) ){
																foreach( $select_value_array as $key => $value ){
																	if( strtolower( $value ) == strtolower ( $select_value ) ){
																		$select_value_key = $key;
																		break;
																	}
																}
															}
															
														}
													}
													
												}
												
												if( $select_value_key ){
													$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $select_value_key;
												}else{
													$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $cell->getCalculatedValue();
												}
												
											break;
											default:
												//$function_settings[$field_name] = strtoupper(htmlspecialchars($cell->getCalculatedValue()));
												$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $cell->getCalculatedValue();
											break;
											}
											
											//CHECK IF UPDATE RECORDS MODE IS ACTIVE
											if( $update_existing_records_based_on_fields && $update_existing_records_based_on_fields == $form_field_data[ 'field_id' ] ){
												$update_conditions_to_be_inserted[ 'where_fields' ] = $update_existing_records_based_on_fields;
												$update_conditions_to_be_inserted[ 'where_values' ] = $dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ];
											}
											
											if( !( $insert_recordset ) && $dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] ){
												$insert_recordset = true;
											}
											
										}
									}	
									
									++$cell_counter;
								}
								
								
								switch( $import_table ){
								case 'cash_calls':
									if( $mapping_options == '400' ){
										if( ! ( isset( $dataset_to_be_inserted['cash_calls003'] ) && isset( $dataset_to_be_inserted['cash_calls002'] ) ) ){
											$insert_recordset = false;
										}
										
										if( isset( $dataset_to_be_inserted['cash_calls003'] ) && isset( $dataset_to_be_inserted['cash_calls002'] ) && ! ( $dataset_to_be_inserted['cash_calls002'] && $dataset_to_be_inserted['cash_calls003'] ) ){
											$insert_recordset = false;
										}
										
										if( isset( $dataset_to_be_inserted['cash_calls002'] ) && preg_match( "/total/" , strtolower($dataset_to_be_inserted['cash_calls002']) ) ){
											$insert_recordset = false;
											//$dataset_to_be_inserted['cash_calls003'] = 'remove me';
										}
										
										if( isset( $dataset_to_be_inserted['cash_calls003'] ) && preg_match( "/total/" , strtolower($dataset_to_be_inserted['cash_calls003']) ) ){
											$insert_recordset = false;
											//$dataset_to_be_inserted['cash_calls003'] = 'remove me';
										}
									}
								break;
								}
								
								if( $insert_recordset ){
									$dataset_to_be_inserted['id'] = $new_record_id . ++$new_record_id_serial;
									
									$dataset_to_be_inserted['created_role'] = $this->class_settings[ 'priv_id' ];
									$dataset_to_be_inserted['created_by'] = $this->class_settings[ 'user_id' ];
									$dataset_to_be_inserted['creation_date'] = $date;
									
									$dataset_to_be_inserted['modified_by'] = $this->class_settings[ 'user_id' ];
									$dataset_to_be_inserted['modification_date'] = $date;
									
									$dataset_to_be_inserted['ip_address'] = $ip_address;
									$dataset_to_be_inserted['record_status'] = 1;
									
									switch( $import_table ){
									case 'budget_details':
										//budget id
										if( isset( $field_values_pair['form_data']['myexcel001'][ 'value' ] ) && $field_values_pair['form_data']['myexcel001'][ 'value' ] ){
											$dataset_to_be_inserted['budget_details001'] = $field_values_pair['form_data']['myexcel001'][ 'value' ];
										}
										
										//set approved = proposed by default
										$dataset_to_be_inserted['budget_details006'] = $dataset_to_be_inserted['budget_details004'];
										$dataset_to_be_inserted['budget_details007'] = $dataset_to_be_inserted['budget_details005'];
									break;
									case 'pipeline_vandalism':
										//year
										if( isset( $field_values_pair['form_data']['myexcel001'][ 'value' ] ) && $field_values_pair['form_data']['myexcel001'][ 'value' ] ){
											$dataset_to_be_inserted['pipeline_vandalism007'] = $field_values_pair['form_data']['myexcel001'][ 'value' ];
										}
										
										//month
										if( isset( $field_values_pair['form_data']['myexcel002'][ 'value' ] ) && $field_values_pair['form_data']['myexcel002'][ 'value' ] ){
											$dataset_to_be_inserted['pipeline_vandalism006'] = $field_values_pair['form_data']['myexcel002'][ 'value' ];
										}
									break;
									case 'cash_calls':
										//budget id
										if( isset( $field_values_pair['form_data']['myexcel001'][ 'value' ] ) && $field_values_pair['form_data']['myexcel001'][ 'value' ] ){
											$dataset_to_be_inserted['cash_calls001'] = $field_values_pair['form_data']['myexcel001'][ 'value' ];
										}
										
										//current month
										if( isset( $field_values_pair['form_data']['myexcel002'][ 'value' ] ) && $field_values_pair['form_data']['myexcel002'][ 'value' ] ){
											$dataset_to_be_inserted['cash_calls025'] = $field_values_pair['form_data']['myexcel002'][ 'value' ];
										}
										
										//set approved = proposed by default
										if( isset( $dataset_to_be_inserted['cash_calls012'] ) )
											$dataset_to_be_inserted['cash_calls016'] = $dataset_to_be_inserted['cash_calls012'];
										
										if( isset( $dataset_to_be_inserted['cash_calls012'] ) )
											$dataset_to_be_inserted['cash_calls017'] = $dataset_to_be_inserted['cash_calls013'];
										
										//clear out all other data
										for( $i = 4; $i < 24; $i++ ){
											switch($i){
											case 4:	case 5: case 6: case 7: case 8: case 9:
												$dataset_to_be_inserted['cash_calls00'.$i] = '';
											break;
											case 12: case 13: case 16: case 17: case 24:
											break;
											default:
												$dataset_to_be_inserted['cash_calls0'.$i] = '';
											break;
											}
										}
									break;
									}
									
									$array_of_dataset[] = $dataset_to_be_inserted;
									
									$array_of_update_conditions[] = $update_conditions_to_be_inserted;
								}
								
								
								++$row_counter;
							}
							
							
							//Run Insert of Multiple Records in One Query
							$save = 0;
							if( !empty( $array_of_dataset ) ){
								
								$function_settings = array(
									'database' => $this->class_settings['database_name'],
									'connect' => $this->class_settings['database_connection'],
									'table' => $import_table,
									
									'dataset' => $array_of_dataset,
								);
								
								if( $update_existing_records_based_on_fields && ! empty( $array_of_update_conditions ) ){
									$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
								}
								
								$returned_data = insert_new_record_into_table( $function_settings );
								
							}
							
							//DELETE FILE
							unlink($name_of_file_to_be_imported);
							
							//RETURN SUCCESS NOTIFICATION
							$err = new cError(010005);
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cMyexcel.php';
							$err->method_in_class_that_triggered_error = '_save_imported_excel_data';
							$err->additional_details_of_error = 'records successful imported to database';
							
							$returning_html_data = $err->error();
							
							$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
							$returning_html_data['status'] = 'saved-form-data';
			
							return $returning_html_data;
							
							/*
							//RETURN ERROR IN RECORD CREATION PROCESS
							$err = new cError('000007');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cAnnual_w_p.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not create record on line 473';
							
							return $err->error();
							*/
						}	
					}
					
					//RETURN INVALID FILE TO UPLOAD ERROR
					$err = new cError(000010);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_save';
					$err->additional_details_of_error = 'could not create record on line 473';
					
					$returning_html_data = $err->error();
							
					$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
					$returning_html_data['status'] = 'saved-form-data';
	
					return $returning_html_data;
				}
			}
		}
		
		private function _create(){
			if( $this->process_large_files && strlen( $this->html ) > 599999 ){
				//set values for the php process
				file_put_contents( $this->filename . ".html", $this->html );
				file_put_contents( $this->filename . ".json", json_encode( $_POST ) );
				
				$cache_key = "generate-excel-queue";
				$settings = array(
					'cache_key' => $cache_key,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				$a = get_cache_for_special_values( $settings );
				if( ! is_array( $a ) )$a = array();
				
				$a[ $this->filename ] = $this->filename;
				$settings = array(
					'cache_key' => $cache_key,
					'cache_values' => $a,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				run_in_background( "generate_large_excel_file" );
				
				return "background";
			}
			
			//ini_set('memory_limit', '512M');
			define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

			//date_default_timezone_set('Europe/London');

			
			/** Simple HTML DOM */
			
			if( file_exists( $this->class_settings['calling_page'] . 'classes/vendor/simple-html-dom/autoload.php' ) ){
				require_once $this->class_settings['calling_page'] . 'classes/vendor/simple-html-dom/autoload.php';
			}
			if( class_exists("voku\helper\HtmlDomParser") ){
				//echo 34; exit;
			}else{
				require($this->class_settings['calling_page'].'classes/simplehtmldom/simple_html_dom.php');
			}

			/** Include PHPExcel */
			require_once $this->class_settings['calling_page'].'classes/PHPExcel.php';
			
			/** PHPExcel_IOFactory */
			require_once $this->class_settings['calling_page'].'classes/PHPExcel/IOFactory.php';
			
			//$objReader = PHPExcel_IOFactory::createReader('Excel5');
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel = $objReader->load($this->class_settings['calling_page']."classes/PHPExcel/templates/30template.xlsx");
			
			$borderThin = array(
				'inside' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			);
			
			$borderThick = array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			);
			
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("Hyella")
										 ->setLastModifiedBy("Hyella")
										 ->setTitle("Office 2007 XLSX Test Document")
										 ->setSubject("Office 2007 XLSX Test Document")
										 ->setDescription("Hyella Report")
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Hyella Report");


			//Traverse HTML
			if( class_exists("voku\helper\HtmlDomParser") ){
				$html_dom = voku\helper\HtmlDomParser::str_get_html($this->html);
			}else if( function_exists( "str_get_html" ) ){
				$html_dom = str_get_html($this->html);
			}else{
				echo 'Missing Dependencies: SIMPLE_HTML_DOM'; exit;
			}
			
			if( isset( $this->report_title ) && $this->report_title )$objPHPExcel->getActiveSheet()->setCellValue('A1', $this->report_title );
			$pr = get_project_data();
			if( isset( $pr["company_name"] ) && $pr["company_name"] )$objPHPExcel->getActiveSheet()->setCellValue('A2', strtoupper( $pr["company_name"] ) );
			
			/*
			// Create a first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', "Firstname");
			$objPHPExcel->getActiveSheet()->setCellValue('B1', "Lastname");
			$objPHPExcel->getActiveSheet()->setCellValue('C1', "Phone");
			$objPHPExcel->getActiveSheet()->setCellValue('D1', "Fax");
			$objPHPExcel->getActiveSheet()->setCellValue('E1', "Is Client ?");
			*/
			
			$i = 2;
			
			$thead = $html_dom->find('thead tr');
			$col_span = 0;
			
			$start_col[$i] = 0;
			$first = '';
			$skip_cells = array();
			
			foreach($thead as $tr) {
				$first = '';
				$cols = $start_col[$i];
				
				if( ! isset($start_col[$i+1]) )
					$start_col[$i+1] = 0;
				
				$col_alphabet_count = 1;
				$col_span_alphabet_count = 1;
				$col_aplhabets = array();
				$col_span_aplhabets = array();
				
				foreach($tr->find('th') as $td){
					++$cols;
					
					if( isset( $skip_cells[ $i ][ $cols ] ) ){
						
						$while_count = $cols;
						$while_condition = 1;
						
						while( $while_condition ){
							
							if( isset( $skip_cells[ $i ][ $while_count ] ) ){
								unset( $skip_cells[ $i ][ $while_count ] );
								++$while_count;
							}else{
								$while_condition = 0;
							}
						}
						
						$cols = $while_count;
					}
					
					if( isset($td->colspan) && intval($td->colspan) > 1 ){
						$col_span = $cols + intval($td->colspan) - 1;
					}
					
					if($cols  > 26){
						$cols = 1;
						
						$col_aplhabets[$col_alphabet_count] = chr(64+$col_alphabet_count);
						++$col_alphabet_count;
					}
					
					$col_aplhabets[$col_alphabet_count] = chr($cols+64);
					
					if( $col_span ){
						if($col_span  > 26){
							$col_span_aplhabets[$col_span_alphabet_count] = chr(64+$col_span_alphabet_count);
							++$col_span_alphabet_count;
						}
						$col_span_aplhabets[$col_span_alphabet_count] = chr($col_span+64);
						
						$objPHPExcel->getActiveSheet()->mergeCells( implode($col_aplhabets). $i.':'.implode($col_span_aplhabets). $i );
						
						$cols = $col_span;
						$col_span = 0;
					}
					
					if( isset($td->rowspan) && intval($td->rowspan) > 1 ){
						for( $x = 1; $x < intval($td->rowspan); $x++ ){
							$skip_cells[ $i + $x ][ $cols ] = 1;
							if( isset( $start_col[$i+$x] ) )
								++$start_col[$i+$x];
							else
								$start_col[$i+$x] = 1;
						}
						
						$objPHPExcel->getActiveSheet()->mergeCells(implode($col_aplhabets). $i.':'.implode($col_aplhabets). ( $i + intval($td->rowspan) - 1) );
					}
					
					if( ! $first ){
						$first = implode($col_aplhabets). $i;
					}
					$objPHPExcel->getActiveSheet()->setCellValue( implode($col_aplhabets). $i, strip_tags($td->innertext));
					$objPHPExcel->getActiveSheet()->getStyle( implode($col_aplhabets).$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC3F092');
					
					switch($cols){
					case 1:
						$objPHPExcel->getActiveSheet()->getColumnDimension(implode($col_aplhabets))->setWidth(10);
					break;
					default:
						$objPHPExcel->getActiveSheet()->getColumnDimension(implode($col_aplhabets))->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getFont()->setBold(true);
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getAlignment()->setWrapText(true);

						//$objPHPExcel->getActiveSheet()->getColumnDimension(implode($col_aplhabets))->setWidth(32);
					break;
					}
					
				}
				
				if( $first ){
					$objPHPExcel->getActiveSheet()->getStyle( $first.":" . implode($col_aplhabets).$i)->getBorders()->applyFromArray( $borderThin );
					$objPHPExcel->getActiveSheet()->getStyle( $first.":" . implode($col_aplhabets).$i)->getBorders()->applyFromArray( $borderThick );
				}
				
				++$i;
			}
			
			
			
			// Rows to repeat at top
			$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, $i);
			
			
			//$i = 3;
			
			$rows = $html_dom->find('tr');
			$start_col[$i] = 0;
			$col_span = 0;
			
			$skip_cells = array();
			
			foreach($rows as $tr) {
				
				$cols = 0;
				
				if( isset( $start_col[$i] ) )$cols = $start_col[$i];
				
				$col_alphabet_count = 1;
				$col_aplhabets = array();
				
				$col_span_alphabet_count = 1;
				$col_span_aplhabets = array();
				
				$first = '';
				$getfirst = '';
				
				$heading = 0;
				$subheading = 0;
				if( isset( $tr->class ) && ( preg_match("/heading-row/", $tr->class ) || preg_match("/total-row/", $tr->class ) ) ){
					$heading = 1;
				}
				if( isset( $tr->class ) && ( preg_match("/heading-sub-row/", $tr->class ) ) ){
					$subheading = 1;
				}
				
				foreach($tr->find('td') as $td){
					++$cols;
					
					if( isset( $skip_cells[ $i ][ $cols ] ) ){
						
						$while_count = $cols;
						$while_condition = 1;
						
						while( $while_condition ){
							
							if( isset( $skip_cells[ $i ][ $while_count ] ) ){
								unset( $skip_cells[ $i ][ $while_count ] );
								++$while_count;
							}else{
								$while_condition = 0;
							}
						}
						
						$cols = $while_count;
					}
					
					if( isset( $td->colspan ) && intval( $td->colspan ) > 1 ){
						$col_span = $cols + intval($td->colspan) - 1;
					}
					
					if($cols  > 26){
						$cols = 1;
						
						$col_aplhabets[$col_alphabet_count] = chr(64+$col_alphabet_count);
						++$col_alphabet_count;
					}
					
					$col_aplhabets[$col_alphabet_count] = chr($cols+64);
					
					if( $col_span ){
						if($col_span  > 26){
							$col_span_aplhabets[$col_span_alphabet_count] = chr(64+$col_span_alphabet_count);
							++$col_span_alphabet_count;
						}
						$col_span_aplhabets[$col_span_alphabet_count] = chr($col_span+64);
						
						
						//echo implode($col_aplhabets). $i.':'.  . $i;
						//echo '<hr />';
						if( implode($col_aplhabets) != stripslashes( implode($col_span_aplhabets) ) ){
							$objPHPExcel->getActiveSheet()->mergeCells(implode($col_aplhabets). $i.':'.implode($col_span_aplhabets). $i);
						}
						
						$cols = $col_span;
						$col_span = 0;
					}
					
					if( isset($td->rowspan) && intval($td->rowspan) > 1 ){
						for( $x = 1; $x < intval($td->rowspan); $x++ ){
							$skip_cells[ $i + $x ][ $cols ] = 1;
							
							//if( isset( $start_col[$i+$x] ) )
								//++$start_col[$i+$x];
							//else
								//$start_col[$i+$x] = $cols;
						}
						
						//$objPHPExcel->getActiveSheet()->mergeCells(implode($col_aplhabets). $i.':'.implode($col_aplhabets). ( $i + intval($td->rowspan) - 1) );
					}
					
					$vat = trim( html_entity_decode( strip_tags( $td->plaintext, '<strong><b><p><br />' ) ) );
					if( isset( $td->class ) && ( preg_match("/number/", $td->class ) ) ){
						$vat = format_and_convert_numbers( $vat, 3 );
						
						$objPHPExcel->getActiveSheet()->getStyle( implode($col_aplhabets).$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
						$objPHPExcel->getActiveSheet()->getStyle( implode($col_aplhabets).$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue( implode($col_aplhabets). $i, $vat );
					
					if( $heading || $subheading ){
						$objPHPExcel->getActiveSheet()->getStyle(implode($col_aplhabets).$i)->getFont()->setBold(true);
						if( $heading ){
							$objPHPExcel->getActiveSheet()->getStyle( implode($col_aplhabets).$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC3F092');
						}
						if( ! $first ){
							$first = implode($col_aplhabets).$i;
						}
					}
					
				}
				
				if( $heading && $first ){
					$objPHPExcel->getActiveSheet()->getStyle( $first.":" . implode($col_aplhabets).$i)->getBorders()->applyFromArray( $borderThin );
					$objPHPExcel->getActiveSheet()->getStyle( $first.":" . implode($col_aplhabets).$i)->getBorders()->applyFromArray( $borderThick );
				}
				++$i;
			}
			
			// Add data
			/*
			for ($i = 2; $i <= 5000; $i++) {
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "FName $i")
											  ->setCellValue('B' . $i, "LName $i")
											  ->setCellValue('C' . $i, "PhoneNo $i")
											  ->setCellValue('D' . $i, "FaxNo $i")
											  ->setCellValue('E' . $i, true);
			}
			*/

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($this->filename);
			
			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			//$objWriter->save( $this->filename);

		}
		
		private function _bulk_import_excel_file_data(){
			
			$use_php_excel_library = isset( $this->class_settings[ 'use_php_excel_library' ] )?$this->class_settings[ 'use_php_excel_library' ]:0;
			
			$always_convert_to_csv = 1;
			if( $use_php_excel_library ){
				$always_convert_to_csv = 0;
			}
			
			if( isset( $this->class_settings['excel_file_name'] ) && file_exists( $this->class_settings['excel_file_name'] ) ){
				//Get Excel File And Create Insert Queries
				set_time_limit(0); //Unlimited max execution time
				
				$imported_records_details = array();
				
				$file_information = array();
				
				$file_information = pathinfo( $this->class_settings['excel_file_name'] );
				
				$pass = 0;
				if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
					$pass = 1;
				}
				
				//Set function name that would be used to retrieve mapped fields for import
				$mapped_fields_function_name = $this->class_settings['import_table'];
				
				//Initialize mapped fields array
				$mapped_fields = array();
				
				if( ! $pass ){
					
					if( function_exists( $mapped_fields_function_name ) ){
						$mapped_fields = $mapped_fields_function_name();
						
						//Add Field Id's Property
						$field_counter = 0;
						foreach( $mapped_fields as $key => $value ){
							$mapped_fields[$key][ 'field_id' ] = $key;
							
							//USE SERIAL IMPORT OPTION
							if( $this->class_settings['excel_field_mapping_option'] == 'serial-import' )
								$mapped_fields[ $field_counter ] = $mapped_fields[$key];
							
							++$field_counter;
						}
					}
					
				}
			
				
				if ( isset( $file_information['extension'] ) && ( $file_information['extension'] == 'xlsx' || $file_information['extension'] == 'xls' || $file_information['extension'] == 'csv' )  ){
					/** Load File to be imported **/
					
					if( $always_convert_to_csv ){
						
						//1. convert file to csv if not in csv format
						switch( $file_information['extension'] ){
						case "csv":
						break;
						default:
							$source_path = realpath( $this->class_settings["excel_file_name"] );
							
							$finfo = pathinfo( $source_path );
							
							$source = $finfo["dirname"] . '\\'. $finfo["filename"] . '.' . $finfo["extension"];
							
							$converted = $finfo["dirname"] . '\\'. $finfo["filename"] . '.csv';
							
							$preconverted = str_replace( '.' . $file_information["extension"], '.csv', $this->class_settings["excel_file_name"] );
							if( file_exists( $preconverted ) ){
								unlink( $preconverted );
							}
							
							// print_r( $converted );exit;
							run_in_background( "convert", 0, 
								array( 
									"action" => 'convert', 
									"todo" => "convert", 
									"user_id" => 'convert',
									"reference" => 'convert',
									"wait_for_execution" => 1,
									//"show_window" => 1,	
									//"argument3" => 'C:\Users\OD1\Downloads\jan-2019.xlsx',
									//"argument4" => 'C:\Users\OD1\Downloads\jan-2019-amen2.csv',
									"argument3" => $source,
									"argument4" => $converted,
								) 
							);
							
							if( $converted && file_exists( $converted ) ){
								
								$this->class_settings["excel_file_name"] = $preconverted;
								
							}else{
								//log in audit trail database & send notification
								
								$err = new cError('010014');
								$err->action_to_perform = 'notify';
								$err->html_format = 2;
								$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
								$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
								$err->additional_details_of_error = '<h4>Unable to Convert File to CSV</h4>Please ensure the file is not corrupt<br /><br />If problem exists contact your support team';
								return $err->error();
							}
						break;
						}
						
					}
					
					if( ! empty($mapped_fields) || $pass ){
						
						if( $use_php_excel_library ){
							/** PHPExcel_IOFactory */
							require_once $this->class_settings['calling_page'].'classes/PHPExcel/IOFactory.php';
							
							$objPHPExcel = PHPExcel_IOFactory::load( $this->class_settings['excel_file_name'] );
							$callStartTime = microtime(true);
							/*
							$row_counter = 0;
							$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
							$data = array();
							foreach($rowIterator as $row){
								$cellIterator = $row->getCellIterator();
								$cell_counter = 0;
								$tmp_data = array();
								
								foreach( $cellIterator as $cell ){
									$tmp_data[ strtoupper( $cell->getColumn() ) ] = iconv( "UTF-8", "ASCII//IGNORE", trim( $cell->getValue() ) );
									//$tmp_data[ strtoupper( $cell->getColumn() ) ] = iconv( "UTF-8", "ASCII//IGNORE", trim( $cell->getCalculatedValue() ) );
								}
								$data[] = $tmp_data;
							}
							unset( $rowIterator );
							*/
							$data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
							
						}else{
							if( get_hyella_development_mode() ){
								error_reporting (~E_ALL);
							}
							//use new library that allows only csv
							// print_r( file( $this->class_settings['excel_file_name'] ) );exit;
							// $data = csv_to_array( $this->class_settings['excel_file_name'] );
							$data = array_map('str_getcsv', file( $this->class_settings['excel_file_name'] ) );
							// print_r( $data );exit;
							// log_in_console( $data, 'data-raw' );
							array_walk_recursive( $data, '_clean_array_values_of_non_utf8' );
							
							if( get_hyella_development_mode() ){
								error_reporting (E_ALL);
							}
							
							$ref_value = "0";
							if( isset( $this->class_settings['reference_value'] ) && $this->class_settings['reference_value'] ){
								$ref_value = $this->class_settings['reference_value'];
							}
							
							$xrow = count( $data );
							for( $i = 0; $i < $xrow; $i++ ){
								if( isset( $data[ $i ] ) ){
									array_unshift( $data[ $i ], $ref_value );
								}
							}
						}
						
						if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
							return $data;
						}
						
					}
				}else{
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid File</h4>Please ensure that the file to be loaded has the following extension <strong>csv or xls or xlsx</strong>';
					return $err->error();
				}
				
				if( isset( $data ) && is_array( $data ) && ! empty( $data ) ){
					$array_of_dataset = array();
					$array_of_update_conditions = array();
					
					$new_record_id = get_new_id();
					$new_record_id_serial = 0;
					
					$ip_address = get_ip_address();
					$date = date("U");
					
					//$this->class_settings['excel_field_mapping_option'] == 200
					$row_counter = 0;
					
					$rowIterator = $data;
					foreach($rowIterator as $row){
						
						$dataset_to_be_inserted = array();
						$update_conditions_to_be_inserted = array();
						
						$cellIterator = $row;
						
						$cell_counter = 0;
						
						$insert_recordset = false;
						
						foreach( $cellIterator as $cell => $cell_value ){
							$excel_column_name = $cell;
							
							if( ( $this->class_settings['excel_field_mapping_option'] == '200' || $this->class_settings['excel_field_mapping_option'] == '400' ) && $row_counter == 0 ){
								
								//Map Headings
								foreach( $mapped_fields as $key => $value ){
									
									if( strtolower( $mapped_fields[ $key ][ 'field_label' ] ) == $cell_value ){
										$mapped_fields[ $cell_counter ] = $mapped_fields[$key];
										break;
									}
								}
								
							}else{
								
								if( isset( $mapped_fields[ $cell_counter ] ) ){
									
									$form_field_data = $mapped_fields[ $cell_counter ];
									
									//Special Processing for Individual Tables
									switch( $form_field_data[ 'form_field' ] ){
									case 'select':
										$select_value_key = '';
										
										$select_value = ucwords( strtolower( trim( $cell_value ) ) );
										
										if( isset( $form_field_data[ 'form_field_options' ] ) ){
										
											$select_value_array_function = $form_field_data[ 'form_field_options' ];
											
											if( function_exists( $select_value_array_function ) ){
												
												$select_value_array = $select_value_array_function();
												
												if( is_array( $select_value_array ) ){
												
													if( in_array( $select_value , $select_value_array ) ){
														foreach( $select_value_array as $key => $value ){
															if( strtolower( $value ) == strtolower ( $select_value ) ){
																$select_value_key = $key;
																break;
															}
														}
													}
													
												}
											}
											
										}
										
										if( $select_value_key ){
											$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $select_value_key;
										}else{
											$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = $cell_value;
										}
										
									break;
									default:
										//$function_settings[$field_name] = strtoupper(htmlspecialchars($cell->getCalculatedValue()));
										//$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = iconv( "UTF-8", "ASCII//TRANSLIT", $cell_value );
										
										$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = iconv( "UTF-8", "ASCII//IGNORE", $cell_value );
										//$dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] = iconv( "UTF-8", "UTF-8//IGNORE", $cell_value );
									break;
									}
									
									//CHECK IF UPDATE RECORDS MODE IS ACTIVE
									if( $this->class_settings['update_existing_records_based_on_fields'] && $this->class_settings['update_existing_records_based_on_fields'] == $form_field_data[ 'field_id' ] ){
										$update_conditions_to_be_inserted[ 'where_fields' ] = $this->class_settings['update_existing_records_based_on_fields'];
										$update_conditions_to_be_inserted[ 'where_values' ] = $dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ];
									}
									
									if( !( $insert_recordset ) && $dataset_to_be_inserted[ $form_field_data[ 'field_id' ] ] ){
										$insert_recordset = true;
									}
									
								}
							}	
							
							++$cell_counter;
						}
						
						if( $insert_recordset ){
							$dataset_to_be_inserted['id'] = $new_record_id . ++$new_record_id_serial;
							
							$dataset_to_be_inserted['created_role'] = $this->class_settings[ 'priv_id' ];
							$dataset_to_be_inserted['created_by'] = $this->class_settings[ 'user_id' ];
							$dataset_to_be_inserted['creation_date'] = $date;
							
							$dataset_to_be_inserted['modified_by'] = $this->class_settings[ 'user_id' ];
							$dataset_to_be_inserted['modification_date'] = $date;
							
							$dataset_to_be_inserted['ip_address'] = $ip_address;
							$dataset_to_be_inserted['record_status'] = 1;
							
							switch( $this->class_settings['import_table'] ){
							case 'newsletter_raw_data_import':
							case 'items_raw_data_import':
								$dataset_to_be_inserted[ $this->class_settings['import_table'] . '041' ] = $this->class_settings['excel_reference_id'];
							break;
							}
							
							$array_of_dataset[] = $dataset_to_be_inserted;
							
							$array_of_update_conditions[] = $update_conditions_to_be_inserted;
						}
						
						
						++$row_counter;
					}
					
					//Run Insert of Multiple Records in One Query
					$save = 0;
					if( !empty( $array_of_dataset ) ){
						
						$function_settings = array(
							'database' => $this->class_settings['database_name'],
							'connect' => $this->class_settings['database_connection'],
							'table' => $this->class_settings['import_table'],
							
							'dataset' => $array_of_dataset,
						);
						
						if( $this->class_settings['update_existing_records_based_on_fields'] && ! empty( $array_of_update_conditions ) ){
							$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
						}
						
						$returned_data = insert_new_record_into_table( $function_settings );
						
					}
					
					//DELETE FILE
					if( ! ( isset( $this->class_settings['keep_excel_file_after_import'] ) && $this->class_settings['keep_excel_file_after_import'] ) )
						unlink($this->class_settings['excel_file_name']);
					
					//RETURN SUCCESS NOTIFICATION
					$err = new cError(010005);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cMyexcel.php';
					$err->method_in_class_that_triggered_error = '_save_imported_excel_data';
					$err->additional_details_of_error = 'records successful imported to database';
					
					$returning_html_data = $err->error();
					
					$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
					$returning_html_data['status'] = 'saved-form-data';
	
					return $returning_html_data;
				}
				
				//RETURN INVALID FILE TO UPLOAD ERROR
				$err = new cError(000010);
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cMyexcel.php';
				$err->method_in_class_that_triggered_error = '_save';
				$err->additional_details_of_error = 'cMyexcel.php: Could not import excel file';
				
				$returning_html_data = $err->error();
						
				$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returning_html_data['status'] = 'saved-form-data';

				return $returning_html_data;
			}
		}
		
		private function _temp($title,$creator){
			$returning_html_data = '';
			
			$returning_html_data .= '<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">';
				$returning_html_data .= '<tr><td style="float:left;">';
					$returning_html_data .= $creator;
					$returning_html_data .= ' | '.date("j-M-y H:i");
			$returning_html_data .= '</td>';
			$returning_html_data .= '</tr><tr>';
			$returning_html_data .= '<td style="font-size:20px;  padding-top:5pt; text-align:center;" valign="top">';
				$returning_html_data .= $title;
			$returning_html_data .= '</td>';
			$returning_html_data .= '</tr></table>';
			
			return $returning_html_data;
		}
		
		private function _html_head($authenticated_report_id = ''){
			if(!$authenticated_report_id)
				$authenticated_report_id = '';
				
			$returning_html_data = '<head>';
			$returning_html_data .= '<style>';
				if(file_exists($this->class_settings['calling_page'].'css/'.$this->report_css_file.'.css'))
					$returning_html_data .= read_file('',$this->class_settings['calling_page'].'css/'.$this->report_css_file.'.css');
			$returning_html_data .= '</style>';
			$returning_html_data .= '</head>';
			$returning_html_data .= '<body><div id="watermark"><br />'.$authenticated_report_id.'&nbsp;&nbsp;&nbsp;</div>';
			return $returning_html_data;
		}
	}

?>

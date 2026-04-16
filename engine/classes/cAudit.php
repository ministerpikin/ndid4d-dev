<?php
	/**
	 * Audit Trail Class
	 *
	 * @used in  				Audit Trail Function
	 * @created  				19:57 | 22-01-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Audit Trail Function in Users Manager Module
	|--------------------------------------------------------------------------
	|
	| Stores log of all users activites and create JSON files of such logs at
	| intervals of 24 hours, and creates snapshots of the database
	| //grant file on *.* to 'foreman'@'localhost' identified by 'rty-566-#43-@44-4-FGT-ff%-w'; flush privileges;
	*/
	
	class cAudit{
		public $table_name = 'audit';
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		//Table that contained records
		public $table = '';
		public $user_action = '';
		//public $user_action = ''; //php 8.2 deprecated Notice
		public $plugin_instance = '';
		public $plugin = '';
		public $view_dir = '';
		public $view_path = '';
		
		//Comment describing action performed by user
		public $comment = '';
		public $parameters = array();
		
		private $database_name_store = 'db';
		private $tmail = 'trail.gashelix@gmail.com';
		
		private $trail = 'tr';
		public $trail_types = array( 'tr', 'login', 'page_view', 'read', 'sql_error', 'rebuild_cache', 'console' );
		
		private $app = "";
		private $url = 'http://www.basviewtech.com.ng/provision/provision/';
		private $ping_url = 'http://ping.northwindproject.com/';
		private $install_full_path = "C:\\hyella";
		
		private $trail_json = 'audit_logs';

		
		public $saved = 0;
		public $refresh_data_break = 2500;
		private $number_of_hours = 1;
		private $upgrade_database = "";
		
		private $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 0,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'show_delete_button' => 0,		//Determines whether or not to show delete button
				
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
			if( function_exists("get_ping_url") ){
				$this->ping_url = get_ping_url();
			}
		}
	
		function audit(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			$this->app = get_app_id();
			
			if( defined("HYELLA_INSTALL_ENGINE_FULL_PATH") && HYELLA_INSTALL_ENGINE_FULL_PATH ){
				$this->install_full_path = HYELLA_INSTALL_ENGINE_FULL_PATH;
			}
			$this->class_settings['development'] = get_hyella_development_mode();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'record':
				$returned_value = $this->_record_user_action();
			break;
			case 'view':
				unset($_SESSION[$this->table]['filter']);
				
				$returned_value = $this->_view_audit_trail();
			break;
			case 'select_audit_trail':
				$returned_value = $this->_select_days_audit_trail_to_display_data_table();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view();
			break;
			case 'backup_unique':
			case 'backup':
				$returned_value = $this->_back_up();
			break;
			case 'sync':
				$returned_value = $this->_export_db();
				//$returned_value = $this->_sync_to_cloud();
				//$returned_value = $this->_sync_to_local();
			break;
			case 'export_db':
				$returned_value = $this->_sync();
			break;
			case "finish_import":
			case "start_import_db":
			case 'import_db':
				$returned_value = $this->_import_db();
			break;
			case 'sync_progress':
				$returned_value = $this->_sync_progress();
			break;
			case 'trace':
				$returned_value = $this->_trace();
			break;
			case 'start_update':
				$returned_value = $this->_start_update();
			break;
			case 'app_update':
				$returned_value = $this->_app_update();
			break;
			case 'push_data':
				//$returned_value = $this->_perform_app_update();
				//$returned_value = $this->_push_data_to_cloud();
				//$returned_value = $this->_load_database_tables();
			break;
			case "analyze_refresh_cache":
			case 'clear_cache':
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'background_app_update_in_background':
			case 'refresh_cache_in_background':
				$returned_value = $this->_refresh_cache_in_background();
			break;
			case 'background_app_update_check':
			case 'background_app_update':
			case 'refresh_cache_via_background_check':
			case 'refresh_cache_via_background':
				$returned_value = $this->_refresh_cache_via_background();
			break;
			case 'perform_app_update':
				$this->class_settings["extract_files"] = 1;
				$returned_value = $this->_perform_app_update();
			break;
			case 'empty_database':
				$returned_value = $this->_empty_database();
			break;
			case 'confirm_empty_database':
				$returned_value = $this->_confirm_empty_database();
			break;
			case 'display_erd':
			case 'display_plugin_erd':
			case 'display_export_updated_files':
			case 'generate_audit_trail_analysis':
			case 'display_audit_trail_analysis_view':
			case 'display_more_settings':
			case 'display_admin_panel_view':
				$returned_value = $this->_display_more_settings();
			break;
			case 'display_data_view':
			case 'save_display_data_access_view2':
			case 'save_display_data_access_view':
			case 'display_data_access_view':
			case 'move_data_from_old_fields_to_new':
			case 'move_data_from_old_fields_to_new_background':
			case 'drop_views_background':
			case 'drop_views':
			case 'save_export_updated_files_background':
			case 'save_export_updated_files':
			case "application_update_start_import_background":
			case 'application_update_start_import':
			case 'display_application_update_view':

			case 'process_duplicates':
				$returned_value = $this->_display_application_update_view();
			break;
			case 'hide_print_dialog_box':
				$returned_value = $this->_hide_print_dialog_box();
			break;
			case 'background_data_upload_only':
				$returned_value = $this->_background_data_upload_only();
			break;
			case 'app_update_automatic':
				$returned_value = $this->_app_update_automatic();
			break;
			case 'upload_mail_to_remote_server':
				$returned_value = $this->_upload_mail_to_remote_server();
			break;
			case 'move_data_from_old_fields_to_new_prompt':
			case 'upgrade_database_prompt':
			case 'upgrade_database_from_database_background':
			case 'upgrade_database_from_database':
			case 'db_upgrade':
				$returned_value = $this->_upgrade_database_from_database();
			break;
			case 'load_database_tables':
				$returned_value = $this->_load_database_tables();
				$returned_value = $this->_confirm_load_database_tables();
			break;
			case 'export_db_prompt':
				$returned_value = $this->_export_db_prompt();
			break;
			case 'open_backup_directory':
				$returned_value = $this->_open_backup_directory();
			break;
			case 'notify_dept':
				$returned_value = $this->_notify_dept();
			break;
			case 'switch_to_test_database':
				$returned_value = $this->_use_test_data();
			break;
			case 'nwp_target':
				$returned_value = $this->_nwp_target();
			break;
			case 'bg_service_status':
			case 'sudo_bg_service':
			case 'bg_service':
				$returned_value = $this->_bg_service2();
			break;
			case 'system_debug_monitor':
			case 'system_debug_start':
			case 'system_debug':
				$returned_value = $this->_system_debug();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _system_debug(){
			
			$data = array();
			$error_msg = '';
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			$handle = 'dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$run_in_bg = 0;
			$run_in = 0;
			$dev_mode = get_hyella_development_mode();
			$refresh_cache = 0;
			
			$settings = array(
				'cache_key' => "system_debug",
			);
			
			switch ( $action_to_perform ){
			case 'system_debug_start':
				$data["updated"] = date("U");
				$settings['cache_values'] = $data;
				set_cache_for_special_values( $settings );
				
				run_in_background( "bprocess", 0, 
					array( 
						"action" => $this->table_name, 
						"todo" => "system_debug", 
						"user_id" => 1, 
						"reference" => "import_files",
						"show_window" => 1,
					) 
				);
				
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "start_export_process";
				$return['action'] = '?action=audit&todo=system_debug_monitor&html_replacement_selector=' . $handle;
				
				$return['html_replacement'] = "<h1>Running Automated System Debug</h1>Please wait...";
				$return['html_replacement_selector'] = $handle;
				
				return $return;
			break;
			case 'system_debug_monitor':
				sleep(20);
			case 'system_debug':
				$now = date("U");
				$int = 60 * 5;	//5 mins
				$data = get_cache_for_special_values( $settings );
				if( isset( $data["updated"] ) && ( doubleval( $data["updated"] ) + $int ) >= $now ){
					//debug in-progress
					//return report...
					if( isset( $data["finished"] ) && $data["finished"] ){
						
					}else{
						$run_in = 1;
					}
				}else{
					//display last debug report
					
				}
				
				
				switch ( $action_to_perform ){
				case 'system_debug':
					$run_in_bg = 1;
					ini_set( 'memory_limit', '2G' );
					
					$par = array();
					$par["status"] = $action_to_perform;
					$par["comment"] = "debug started";
					auditor("", "console", $this->table_name, $par );
				break;
				case 'system_debug_monitor':
					
				break 2;
				}
				
				$db = $this->class_settings["database_name"];
				
				// check indexes, table fields, default null
				$tables = $this->_get_project_classes();
				if( ! empty( $tables ) ){
					$perf_query = "";
					if( file_exists( $this->class_settings["calling_page"] . "/classes/perf-query.sql" ) ){
						$perf_query = str_replace( " ", "", file_get_contents( $this->class_settings["calling_page"] . "/classes/perf-query.sql" ) );
					}
					if( isset( $data["finished"] ) ){
						unset( $data["finished"] );
					}
					$data["updated"] = date("U");
					
					//get mysql version
					$query_settings = array(
						'database' => $db,
						'connect' => $this->class_settings["database_connection"] ,
						//'query' => "SHOW VARIABLES LIKE '%version%'",
						'query' => "SHOW VARIABLES WHERE variable_name IN ( 'innodb_version', 'version', 'version_compile_os', 'version_compile_machine' )",
						//'query' => "SELECT * FROM information_schema.global_variables WHERE variable_name IN ( 'innodb_version', 'version', 'version_compile_os', 'version_compile_machine' )",
						'query_type' => 'SELECT',
						'index_field' => 'Variable_name',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					$rsql = execute_sql_query($query_settings);
					//print_r( $rsql );
					$data["db version"] = $rsql;
					$data["db version"]["title"] = date("d-M-Y H:i:s") . ' - CHECK INSTALLED DB & PHP VERSION';
					
					$int_type = 'int(11)';
					if( isset( $rsql["version_compile_os"]["Value"] ) ){
						switch( strtolower( $rsql["version_compile_os"]["Value"] ) ){
						case "linux":
							if( isset( $rsql["innodb_version"]["Value"] ) ){
								$dv = explode(".", $rsql["innodb_version"]["Value"] );
								if( isset( $dv[0] ) && doubleval( $dv[0] ) >= 8 ){
									$int_type = 'int';
								}
							}
						break;
						}
					}
					
					$data["db version"]["PHP version"] = phpversion();
					if( ! function_exists("imagepng") ){
						$data["db version"]["err"][] = "PHP GD Lib not installed. **MANUAL FIX: sudo apt-get install php-gd or sudo apt-get install php7.3-gd (to target a specific version). You can confirm if installed by running: php -i | grep 'GD' or IP_ADDR/engine/phpinfo.php. NOTE: Restart apache after installation";
					}
					
					//echo $int_type;
					$settings["cache_values"] = $data;
					set_cache_for_special_values( $settings );
					//return array( 'status' => 'a' );
					
					/* $int_type = 'int';
					$data["finished"] = date("U");
					$settings["cache_values"] = $data;
					set_cache_for_special_values( $settings );
					return array( 'status' => 'a' ); */
					
					$err = '';
					$msg = date("d-M-Y H:i:s") . ' - CHECK INDEXES, DATABASE STRUCTURE & DEFAULT NULL';
					if( $run_in_bg ){
						echo strip_tags( $msg ) . "\n\n";
					}
					$data["db structure"]["title"] = $msg;
					
					$skip_tables = array( "all_reports", "autopsy_report", "cart", "chart_js", "cradle", "dashboard", "drug_chart", "files_movement", "hospital_dashboard", "json_options", "mobile_app", "morgue", "mortuary_cabinet", "my_patient_view", "address", "bulk_finance_receivables", "bulk_operations", "backend_loader", "snh_migrate", "tele_health", "tele_health_connect", "disciplinary_history", "frontend_users", "leave_request2", "pay_row2", "imenu_order", "work_order", "workflow_redirect", "obstetric_history", "obstetrics_services_record", "parent_child_data", "parent_data", "plugin_class", "ward_round" );
					
					foreach( $tables as $tb ){
						
						//print_r($tb);
						$tbn = isset( $tb["db_table_name"] )?$tb["db_table_name"]:( isset( $tb["table_name"] )?$tb["table_name"]:'' );
						if( in_array( $tbn, $skip_tables ) ){
							continue;
						}
						
						if( $tbn ){
							$query = "DESCRIBE `".$this->class_settings["database_name"]."`.`".$tbn."` ";
							$query_settings = array(
								'database' => $db,
								'connect' => $this->class_settings["database_connection"] ,
								'query' => $query,
								'query_type' => 'DESCRIBE',
								'set_memcache' => 0,
								'tables' => array( $tbn ),
							);
							$r2 = execute_sql_query($query_settings);
							
							//print_r($r2); exit;
							if( isset( $r2[0] ) ){
								
								$sys_fields = array(
									"id" => array(
										"Type" => "varchar(55)",
										"Null" => "NO",
										"Key" => "UNI",
									),
									"serial_num" => array(
										"Type" => $int_type,
										"Null" => "NO",
										"Key" => "PRI",
										"Extra" => "auto_increment",
									),
									"creator_role" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"created_source" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"created_by" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"modified_source" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"modified_by" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"ip_address" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"device_id" => array(
										"Type" => "text",
										"Null" => "YES",
									),
									"record_status" => array(
										"Type" => "varchar(100)",
										"Null" => "YES",
									),
									"creation_date" => array(
										"Type" => $int_type,
										"Null" => "YES",
									),
									"modification_date" => array(
										"Type" => $int_type,
										"Null" => "YES",
									),
								);
								
								$qf = array();
								
								$sys_field_count = 0;
								$found_sys_field = array();
								
								$perf = array();
								$tf = array();
								if( isset( $tb["table_fields"] ) && is_array( $tb["table_fields"] ) && ! empty( $tb["table_fields"] ) ){
									$tf = array_flip( $tb["table_fields"] );
								}
								$spec_index = array( "store" => 1, "department" => 1, "reference_table" => 1, "child_reference_table" => 1, "hmo" => 1, "item" => 1, "customer" => 1, "status" => 1, "type" => 1, "state" => 1, "lga" => 1, "ward" => 1, "category" => 1 );
								
								
								$has_pri = 0;
								foreach( $r2 as $r2v ){
									if( isset( $r2v["Field"] ) ){
										if( isset( $sys_fields[ $r2v["Field"] ] ) ){
											$found_sys_field[ $r2v["Field"] ] = $r2v["Field"];
											
											++$sys_field_count;
											$fix = 0;
											if( ! empty( $sys_fields[ $r2v["Field"] ] ) ){
												
												foreach( $sys_fields[ $r2v["Field"] ] as $sk => $sv ){
													
													if( isset( $r2v[ $sk ] ) ){
														if( strtolower( $r2v[ $sk ] ) == strtolower( $sv ) ){
															
														}else{
															//apply fix
															$data["db structure"]["err"][] = "Fixing Out-dated Field: " . $tbn . "." .$r2v["Field"] . " = ". $sk;
															
															$fix = 1;
														}
													}else{
														//apply fix
														$data["db structure"]["err"][] = "Fixing Corrupt Field: " . $tbn . "." .$r2v["Field"] . " = ". $sk;
														
														$fix = 1;
													}
													
													
												}
											}
											
											if( $fix && isset( $sys_fields[ $r2v["Field"] ]["Type"] ) ){
												switch( $r2v["Field"] ){
												case "id":
													if( isset( $r2v["Key"] ) && $r2v["Key"] == "PRI" ){
														//check if ID is truly the primary key
														$query_settings = array(
															'database' => $db,
															'connect' => $this->class_settings["database_connection"] ,
															'query' => "SHOW INDEX FROM `".$db."`.`". $tbn ."` WHERE column_name = 'id' AND key_name = 'PRIMARY' ",
															'query_type' => 'SELECT',
															'set_memcache' => 0,
															'tables' => array( $tbn ),
														);
														$rsql = execute_sql_query($query_settings);
														
														if( isset( $rsql[0] ) && ! empty( $rsql[0] ) ){
															$has_pri = 1;
															$data["db structure"]["msg"][] = "<b>WARNING!</b> ".$tbn." has the ID as the Primary Key"; 
														}
														
													}else{
														$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` CHANGE `id` `id` ".$sys_fields[ $r2v["Field"] ]["Type"]." NOT NULL;";
														
														//CHARACTER SET latin1 COLLATE latin1_swedish_ci 
														
														$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` ADD UNIQUE(`id`);";
													}
												break;
												case "serial_num":
													if( $has_pri ){
														
													}else{
														//check if table is empty
														$query_settings = array(
															'database' => $db,
															'connect' => $this->class_settings["database_connection"] ,
															'query' => "SELECT COUNT(*) as 'count' FROM `".$db."`.`". $tbn ."` ",
															'query_type' => 'SELECT',
															'set_memcache' => 0,
															'tables' => array( $tbn ),
														);
														$rsql = execute_sql_query($query_settings);
														
														if( isset( $rsql[0]['count'] ) && doubleval( $rsql[0]['count'] ) > 1 ){
															$data["db structure"]["err"][] = "Could not apply fix, run the following queries to manually fix:"; 
															
															$data["db structure"]["err"][] = "MANUAL FIX - SELECT @i :=0; UPDATE `".$db."`.`".$tbn."` SET `serial_num` = ( SELECT @i := @i +1 ) ;  ALTER TABLE `".$db."`.`".$tbn."` ADD PRIMARY KEY(`serial_num`); ALTER TABLE `".$db."`.`".$tbn."` CHANGE `serial_num` `serial_num` ".$int_type." NOT NULL AUTO_INCREMENT;";
														}else{
															$data["db structure"]["msg"][] = $tbn . ".serial_num set as the Primary Key"; 
															
															$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` ADD PRIMARY KEY(`serial_num`); ";
															
															$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` CHANGE `serial_num` `serial_num` ".$int_type." NOT NULL AUTO_INCREMENT; ";
														}
													}
												break;
												default:
													$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` CHANGE `".$r2v["Field"]."` `".$r2v["Field"]."` ".$sys_fields[ $r2v["Field"] ]["Type"]." NULL DEFAULT NULL; ";
													//CHARACTER SET latin1 COLLATE latin1_swedish_ci 
												break;
												}
											}
										}else{
											
											if( isset( $r2v["Key"] ) && $r2v["Key"] != "MUL" ){
												
												//index special fields
												if( isset( $tb["default_reference"] ) && isset( $tf[ $r2v["Field"] ] ) && $tf[ $r2v["Field"] ] == $tb["default_reference"] ){
													$perf[ $tb["table_fields"][ $tb["default_reference"] ] ] = $tb["table_fields"][ $tb["default_reference"] ];
												}else if( isset( $tf[ $r2v["Field"] ] ) && isset( $spec_index[ $tf[ $r2v["Field"] ] ] ) ){
													$perf[ $r2v["Field"] ] = $r2v["Field"];
												}else if( strpos( $perf_query, "`". $r2v["Field"] ."`" ) > -1 ){
													$perf[ $r2v["Field"] ] = $r2v["Field"];
												}
												
											}
											
											//test user defined fields
											if( isset( $tf[ $r2v["Field"] ] ) ){
												unset( $tf[ $r2v["Field"] ] );
											}
										}
									}else{
										$data["db structure"]["err"][] = "No Field: " . $tbn . "." . json_encode( $r2v );
									}
								}
								
								if( $sys_field_count != count( $sys_fields ) ){
									//$data["db structure"]["err"][] = "Missing System Fields: " . $tbn . "." . implode( ", ", array_keys( $sys_fields ) );
									
									if( ! empty( $sys_fields ) ){
										$previous_field = "id";
										foreach( $sys_fields as $sks => $svs ){
											if( ! isset( $found_sys_field[ $sks ] ) ){
												$data["db structure"]["err"][] = "Missing System Fields: " . $tbn . "." . $sks;
												
												$is_null = ( isset( $svs["Null"] ) && strtolower( $svs["Null"] ) == "yes" )?"":"NOT";
												
												$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` ADD `".$sks."` ".$svs["Type"]." DEFAULT ".$is_null." NULL AFTER `".$previous_field."`; ";
											}
											$previous_field = $sks;
										}
									}
								}
								
								if( ! empty( $tf ) ){
									$sequence = array();
									$pseq = 'id';
									foreach( $tb["table_fields"] as $tfk => $tfv ){
										$sequence[ $tfv ] = $pseq;
										$pseq = $tfv;
									}
									foreach( $tf as $tfk => $tfv ){
										$previous_field = isset( $sequence[ $tfk ] )?$sequence[ $tfk ]:"id";
										
										$data_type = "varchar(200)";
										$fft = isset( $tb["table_labels"][ $tfk ]["form_field"] )?$tb["table_labels"][ $tfk ]["form_field"]:"text";
										
										switch( $fft ){
										case "date":
										case "date-5time":
										case "date-5":
										case "number":
											$data_type = $int_type;
										break;
										case "decimal_long":
											$data_type = "decimal(20,10)";
										break;
										case "decimal":
										case "currency":
											$data_type = "decimal(20,4)";
										break;
										case "multi-select":
											$data_type = "varchar(4000)";
										break;
										case "calculated":
										case "select":
											$data_type = "varchar(100)";
										break;
										case "file":
										case "textarea":
										case "field_group":
										case "html":
										case "textarea-unlimited":
											$data_type = "text";
										break;
										case "textarea-unlimited-med":
											$data_type = "mediumtext";
										break;
										}
										
										$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` ADD `".$tfk."` ".$data_type." DEFAULT NULL AFTER `".$previous_field."`; ";
									}
								}
								
								if( ! empty( $perf ) ){
									foreach( $perf as $qff ){
										$qf[] = "ALTER TABLE `".$db."`.`".$tbn."` ADD INDEX(`".$qff."`); ";
									}
								}
								
								if( ! empty( $qf ) ){
									//apply fixes
									foreach( $qf as $qff ){
										$query_settings = array(
											'database' => $db,
											'connect' => $this->class_settings["database_connection"] ,
											'query' => $qff,
											'query_type' => 'EXECUTE_SYS',
											'set_memcache' => 0,
											'tables' => array( $tbn ),
										);
										$edr = execute_sql_query($query_settings);
										usleep(10000);
										if( is_string( $edr ) ){
											$msg = "Error applying fix: " . $qff;
											$data["db structure"]["err"][] = $msg;
											if( $run_in_bg ){
												echo strip_tags( $msg ) . "\n\n";
											}
										}
										
									}
									
									if( $dev_mode ){
										$data["db structure"]["fix"] = $qf;
									}
								}
								
								$qf = array();
							}else{
								$msg = "No Table: " . $tbn;
								if( $run_in_bg ){
									echo strip_tags( $msg ) . "\n\n";
								}
								$data["db structure"]["err"][] = $msg;
							}
						}
					}
					
					$settings["cache_values"] = $data;
					set_cache_for_special_values( $settings );
				}
				
				// directory permissions, php ini settings, mysql settings
				
				//chart of accounts & constants
				if( class_exists("cChart_of_accounts") ){
					$data["updated"] = date("U");
					
					$msg = date("d-M-Y H:i:s") . ' - CHECKING CHART OF ACCOUNTS, INVENTORY & CATEGORIES';
					if( $run_in_bg ){
						echo strip_tags( $msg ) . "\n\n";
					}
					
					$coa = new cChart_of_accounts();
					$coa->class_settings = $this->class_settings;
					$data["chart of accounts"] = $coa->system_debug();
					
					$data["chart of accounts"]["title"] = $msg;
					
					if( isset( $data["chart of accounts"]["fix"] ) && ! empty( $data["chart of accounts"]["fix"] ) && is_array( $data["chart of accounts"]["fix"] ) ){
						$refresh_cache = 1;
						
						foreach( $data["chart of accounts"]["fix"] as $qff ){
							$query_settings = array(
								'database' => $db,
								'connect' => $this->class_settings["database_connection"] ,
								'query' => $qff,
								'query_type' => 'EXECUTE_SYS',
								'set_memcache' => 0,
								'tables' => array( $coa->table_name ),
							);
							$edr = execute_sql_query($query_settings);
							usleep(10000);
							
							if( is_string( $edr ) ){
								$msg = "Error applying fix: " . $qff;
								$data["chart of accounts"]["err"][] = $msg;
								if( $run_in_bg ){
									echo strip_tags( $msg ) . "\n\n";
								}
							}
						}
						if( ! $dev_mode ){
							unset( $data["chart of accounts"]["fix"] );
						}
						
					}
					unset( $coa );
					
					
					$settings["cache_values"] = $data;
					set_cache_for_special_values( $settings );
				}

				// items settings, categories, cost & selling prices
				
				// workflow settings, 

				// hospital data - customer fields & status...

				// autobackup, 

				// cronjobs running...
				
				$data["finished"] = date("U");
				$settings["cache_values"] = $data;
				set_cache_for_special_values( $settings );
				
				$par = array();
				$par["debug"] = $data;
				$par["status"] = $action_to_perform;
				$par["comment"] = "debug ended";
				auditor("", "console", $this->table_name, $par );
				
				$this->class_settings["new_trail"] = 2;
				$this->_new_day();
				
				if( $refresh_cache ){
					$tp["get"] = $_GET;
					$tp["post"] = $_POST;
					
					$_GET = array();
					$_POST = array();
					
					$_POST["id"] = "-";
					$this->class_settings["action_to_perform"] = 'refresh_cache_in_background';
					$this->_refresh_cache_in_background();
					
					$_GET = $tp["get"];
					$_POST = $tp["post"];
					unset( $tp );
				}
				
		
				return array( 'status' => 'new-status', 'debug_finished' => 1 );
			break;
			}
			
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/system-debug.php' );
			$returning_html_data = $this->_get_html_view();
			
			$return = array(
				'html_replacement_selector' => "#".$handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ),
			);
			
			if( $run_in ){
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "start_export_process";
				$return['action'] = '?action=audit&todo=system_debug_monitor&html_replacement_selector=' . $handle;
			}
			
			return $return;
		}
		
		protected function _bg_service2(){
			$check_status = 0;
			$r = array();
			$run_ocr = 0;
			$run_indexing = 0;
			$GLOBALS["nwp_skip_manifest"] = 'bg_process';	//15-mar-23
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'bg_service_status':
				$check_status = 1;
			break;
			case "sudo_bg_service":
				$r = $this->_move_remote_backup();
				
				return $r;
			break;
			default:
				$run_ocr = 1;
				$run_indexing = 1;
			break;
			}
			
			if( defined("HYELLA_REMOTE_CONNECTION") && HYELLA_REMOTE_CONNECTION ){
				$tb = array( 'parish', 'parishioner', 'parish_zones', 'payment_exemption', 'payment_template', 'people', 'pledge', 'sacrament', 'parish_transaction', 'transactions', 'debit_and_credit', 'users', 'assets', 'assets_category', 'chart_of_accounts', 'items', 'stores', 'vendors', 'customers' );
				$r = $this->_pull_replication_data( array( "tables" => $tb, "check_status" => $check_status ) );
			}
			
			//rebuild cache
			if( defined("AUTO_RELOAD_MISSING_CACHE_FOR_TABLES") && AUTO_RELOAD_MISSING_CACHE_FOR_TABLES ){
				$mc = explode(",", AUTO_RELOAD_MISSING_CACHE_FOR_TABLES );
				$set2 = array(
					'cache_key' => 'nwp-cache-auto-reloaded',
					'permanent' => true,
				);
				$auto2 = get_cache_for_special_values( $set2 );
				
				$set = array(
					'cache_key' => 'nwp-cache-auto-reload',
					'permanent' => true,
				);
				$auto = get_cache_for_special_values( $set );
				
				if( ! empty( $auto ) ){
					foreach( $auto as $cache_key => $cid ){
						if( in_array( $cache_key, $mc ) ){
							$cl = 'c' . ucwords( $cache_key );
							if( class_exists( $cl ) ){
								$_POST["ids2"] = "'". implode( "', '", array_keys( $cid ) ) ."'";
								$direct = 0;
								
								$cls = new $cl();
								$cls->class_settings = $this->class_settings;
								
								switch( $cache_key ){
								case "debit_and_credit":
									
									$_POST["ids2"] = str_replace( "debit_and_credit-", "", $_POST["ids2"] );
									
									/* $cls->class_settings["overide_select"] = " `" . $cls->table_fields[ $cls->default_reference_field ] . "` as 'tid' ";
									$cls->class_settings["where"] = " AND `".$cache_key."`.`id` IN (". $_POST["ids2"] .") ";
									$cls->class_settings["group"] = " GROUP BY `" . $cls->table_fields[ $cls->default_reference_field ] . "` ";
									$ds = $cls->_get_records();
									
									print_r($cls->last_query); exit;
									print_r($ds); exit; */
									
									$cls->class_settings["cache_where"] = " AND `".$cache_key."`.`" . $cls->table_fields[ $cls->default_reference_field ] . "` IN (". $_POST["ids2"] .") ";
									$direct = 1;
								break;
								}
								
								$cls->class_settings["action_to_perform"] = 'refresh_cache';
								if( $direct ){
									$cls->class_settings["current_record_id"] = 'pass_condition';
									$cls->_get_record();
								}else{
									$cls->$cache_key();
								}
							}
						}
						
						if( ! empty( $cid ) ){
							foreach( $cid as $cvk => $cvv ){
								$auto2[ $cache_key . $cvk ] = 1;
							}
						}
					}
					unset( $_POST["ids2"] );
					
					if( isset( $auto2["today"] ) && $auto2["today"] && $auto2["today"] != date("d-M-Y") ){
						clear_cache_for_special_values( $set2 );
					}else{
						$auto2["today"] = date("d-M-Y");
						$set2["cache_values"] = $auto2;
						set_cache_for_special_values( $set2 );
					}
					
					clear_cache_for_special_values( $set );
				}
				
			}
			
			//13-mar-23
			if( defined("NWP_IPIN_SYNC_WITH_DEFAULT_CRON") && NWP_IPIN_SYNC_WITH_DEFAULT_CRON && class_exists("cNwp_ipin_sync") ){
				$fts = new cNwp_ipin_sync();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'execute';
				$fts->class_settings['nwp_action'] = 'ipin_sync_ping_log';
				$fts->class_settings['nwp_todo'] = 'bg_synchronized';
				$fts->nwp_ipin_sync();
				$r['ipin_synced'] = date("U");
				unset( $fts );
			}
			
			//13-mar-23
			if( class_exists("cNwp_logging") ){
				$fts = new cNwp_logging();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'execute';
				$fts->class_settings['nwp_action'] = 'audit_trail';
				$fts->class_settings['nwp_todo'] = 'bg_process';
				$rr = $fts->nwp_logging();
				if( isset( $rr[ 'executed' ] ) && $rr[ 'executed' ] ){
					$r[ 'audit_logged' ] = date('U');
				}
				unset( $fts );
			}
			
			if( class_exists("cNwp_endpoint") ){
				$fts = new cNwp_endpoint();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'execute';
				$fts->class_settings['nwp_action'] = 'endpoint';
				$fts->class_settings['nwp_todo'] = 'bg_process';
				$rr = $fts->nwp_endpoint();
				if( isset( $rr[ 'executed' ] ) && $rr[ 'executed' ] ){
					$r[ 'endpoint_logged' ] = date('U');
				}
				unset( $fts );
			}
			
			if( class_exists("cAdmission2") ){
				$fts = new cAdmission2();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'auto_bg_service';
				$fts->auto_bg_service( array( "source" => "audit" ) );
				unset( $fts );
			}
			
			if( $run_ocr && class_exists("cOcr_pdf2text") ){
				$ocrd = new cOcr_pdf2text();
				$ocr = $ocrd->ocr_service( array(
					"check_queue" => 1,
					"clear_cache" => 1,
					"wait_for_execution" => 1,
				) );
				$r['ocr'] = date("U");
				unset( $ocrd );
				unset( $ocr );
			}
			
			if( $run_indexing && class_exists("cNwp_full_text_search") ){
				
				$fts = new cNwp_full_text_search();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'execute';
				$fts->class_settings['nwp_action'] = 'index_queue';
				$fts->class_settings['nwp_todo'] = 'start_indexing';
				$fts->nwp_full_text_search();
				$r['indexed'] = date("U");
				unset( $fts );
			}
			
			if( class_exists("cNwp_client_notification") ){
				
				$ncl = new cNwp_client_notification();
				$ncl->class_settings = $this->class_settings;
				$ncl->class_settings['action_to_perform'] = 'execute';
				$ncl->class_settings['nwp_action'] = 'cn_user_settings';
				$ncl->class_settings['nwp_todo'] = 'bg_clear_notification';
				$ncl->nwp_client_notification();
				$r['notification_date'] = date("U");
				unset( $ncl );
			}

			if( class_exists("cInventory_ent") ){
				$fts = new cInventory_ent();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'process_background_refresh';
				$fts->auto_bg_service( array( "source" => "audit" ) );
				unset( $fts );
			}

			if( defined( 'HYELLA_V3_HMO_CREDIT_LIMIT' ) && HYELLA_V3_HMO_CREDIT_LIMIT && class_exists("cHmo_organization") ){
				$fts = new cHmo_organization();
				$fts->class_settings = $this->class_settings;
				$fts->class_settings['action_to_perform'] = 'process_background_refresh';
				$fts->auto_bg_service( array( "source" => "audit" ) );
				unset( $fts );
			}
			
			if( class_exists("cNwp_42doc_edms") ){
				$ncl = new cNwp_42doc_edms();
				$ncl->class_settings = $this->class_settings;
				$ncl->class_settings['action_to_perform'] = 'execute';
				$ncl->class_settings['nwp_action'] = 'orthanc';
				$ncl->class_settings['nwp_todo'] = 'process_background_refresh';
				$ncl->nwp_42doc_edms();
				$r['orthanc_date'] = date("U");
				unset( $ncl );
			}

			return $r;
		}
		
		public function _move_remote_backup( $opt = array() ){
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			switch( $action_to_perform ){
			case "download_auto_db":
			break;
			}
			
			$platform = '';
			if( defined( 'PLATFORM' ) ){
				$platform = PLATFORM;
			}
			
			switch( $platform ){
			case "linux":
				if( defined("NWP_BACKUP_DIRECTORY") && NWP_BACKUP_DIRECTORY ){
					//define( "NWP_BACKUP_DIRECTORY", "/var/lib/automysqlbackup/daily/" );
					
					$info = array();
					$r = array();
					$r["data"] = array();
					
					$db = isset( $this->class_settings["database_name"] )?$this->class_settings["database_name"]:'no-db';
					$source = '/var/lib/automysqlbackup/daily/'.$db;
					$destination = '/var/www/html';
					
					$dirname = 'tmp/dump';
					if( ! is_dir( $this->class_settings["calling_page"] . $dirname ) ){
						create_folder( $this->class_settings["calling_page"] . $dirname, '', '' );
					}
					$destination = $this->class_settings["calling_page"] . $dirname;
					
					$destination_off = '';
					if( defined("NWP_OFFSITE_BACKUP_DIRECTORY") && NWP_OFFSITE_BACKUP_DIRECTORY ){
						$destination_off = NWP_OFFSITE_BACKUP_DIRECTORY;
					}
					
					$source = NWP_BACKUP_DIRECTORY . $db;
					
					$existing_files = array();
					
					foreach( glob( $source . "/*.sql.gz" ) as $filename ) {
						if( is_file( $filename ) ) {
							$bsn = basename( $filename, ".sql.gz" );
							$existing_files[ $bsn ] = $filename;
							if( ! file_exists( $destination .'/'. $bsn ) ) {
								//echo "copied " . $filename . " to " . $destination . '/'. $bsn . "\n";
								
								if( copy( $filename, $destination . '/'. $bsn . '.ela' ) ){
									$r["data"]["sfiles"][] = $bsn . '.ela';
								}else{
									$r["data"]["ffiles"][] = $bsn . '.ela';
								}
								chown( $destination . '/'. $bsn . '.ela' , "www-data");
							}
							if( $destination_off ){
								if( ! file_exists( $destination_off .'/'. $bsn ) ) {
									//echo "copied " . $filename . " to " . $destination . '/'. $bsn . "\n";
									
									if( copy( $filename, $destination_off . '/'. $bsn . '.ela' ) ){
										$r["data_off"]["sfiles"][] = $bsn . '.ela';
									}else{
										$r["data_off"]["ffiles"][] = $bsn . '.ela';
									}
								}
							}
						}
					}
					
					
					$par = array();
					$r["class"] = 'border-danger';
					$msg = '';
					
					//$r["action"] = '?action=audit&todo=export_db_prompt';
						
					if( isset( $r["data"]["sfiles"][0] ) && $r["data"]["sfiles"][0] ){
						$r["number"] = count( $r["data"]["sfiles"] );
						$r["label_color"] = 'text-success';
						$r["label"] = 'successful db backup';
						$msg = 'Download the DB back-up file and save in an offsite location';
						
						$r["action"] = '?action=audit&todo=export_db_prompt&db=' . $r["data"]["sfiles"][0];
					}else if( isset( $r["data"]["ffiles"][0] ) && $r["data"]["ffiles"][0] ){
						$r["number"] = count( $r["data"]["ffiles"] );
						$r["label_color"] = 'text-danger';
						$r["label"] = 'failed db backup';
						$msg = 'Check back after 24 hours, if problem persists contact support team';
						$par["elevel"] = 'fatal';
					}else{
						$r["number"] = '!';
						$r["label_color"] = 'text-danger';
						$r["label"] = 'fatal backup failure';
						$msg = 'Contact support team';
						$par["elevel"] = 'fatal';
					}
					
					$r["date"] = date("U");
					$r["title"] = 'Auto DB Backup';
					$r["message"] = $msg;
					$info["data"]["db_backup"] = $r;
					
					foreach( glob( $destination . "/*.ela" ) as $filename ) {
						if( is_file( $filename ) && ! isset( $existing_files[ basename( $filename, ".ela" ) ] ) ) {
							unlink( $filename );
						}
					}
					
					if( $destination_off ){
						foreach( glob( $destination_off . "/*.ela" ) as $filename ) {
							if( is_file( $filename ) && ! isset( $existing_files[ basename( $filename, ".ela" ) ] ) ) {
								unlink( $filename );
							}
						}
					}
					
					if( ! empty( $info ) && class_exists("cDashboard") ){
						$d = new cDashboard();
						$d->class_settings = $this->class_settings;
						$d->add_information( $info );
					}
					
					$par["status"] = "db_backup";
					$par["comment"] = $msg;
					auditor("", "console", $this->table_name, $par );
					
					return $r;
				}
			break;
			}
		}
		
		public function _pull_replication_data( $opt = array() ){
			//check for online db
			$return = array();
			//echo 'Jane'; exit;
			if( ! $this->app ){
				$this->app = get_app_id();
			}
			
			
			/* $dirname = 'tmp/sync-lib';
			$srcd = $this->class_settings["calling_page"] . $dirname;
			if( is_dir( $srcd ) ){
				$dird = opendir( $srcd );
				while(false !== ( $filed = readdir($dird)) ) {
					if (( $filed != '.' ) && ( $filed != '..' )) {
						$fulld = $srcd . '/' . $filed;
						if ( is_dir($fulld) ) {
							closedir(opendir($fulld));
							rmdir($fulld);
						}
					}
				}
				closedir($dird);
			}
			
			exit; */
			
			if( isset( $opt["check_status"] ) && $opt["check_status"] ){
				$settings = array(
					'cache_key' => "pull_replication_data",
					'permanent' => true,
				);
				$par = get_cache_for_special_values( $settings );
				
				if( isset( $opt["return_html"] ) && $opt["return_html"] ){
					$h = '';
					
					if( isset( $par["start_time"] ) && isset( $par["end_time"] ) ){
						$h .= 'Last Synchronization: <b>'. date("d-M-Y H:i:s", doubleval( $par["start_time"] ) ) .' to '. date("d-M-Y H:i:s", doubleval( $par["end_time"] ) ) .' ['.get_age( $par["end_time"], $par["start_time"] ).']</b><br />';
					}
					
					if( isset( $par["error"] ) && $par["error"] ){
						$h .= 'Synchronization Error: <span class="label label-danger"><b>'. $par["error"] . '</b></span><br />';
					}
					
					if( isset( $par["loops"] ) && $par["loops"] ){
						$h .= 'Number of Loops: <b>'. number_format( doubleval( $par["loops"] ), 0 ) . '</b><br />';
					}
					
					if( isset( $par["requests"] ) && $par["requests"] ){
						$h .= 'Number of Requests: <b>'. number_format( doubleval( $par["requests"] ), 0 ) . '</b><br />';
					}
					
					if( isset( $par["updates"] ) && $par["updates"] ){
						$h .= 'Number of Updates: <b>'. number_format( doubleval( $par["updates"] ), 0 ) . '</b><br />';
					}
					
					return $h;
				}
				return $par;
			}
			
			$tb = array();
			$limit_check = 1;	//check automatically 2 times a day {7:42pm and 7:42am}
			if( isset( $opt["unlimited"] ) && $opt["unlimited"] ){
				$limit_check = 0;
			}
			
			if( isset( $opt["tables"] ) && ! empty( $opt["tables"] ) ){
				$tb = $opt["tables"];
			}
			
			$count_conn = 0;
			$count_updates = 0;
			$count_loops = 0;
			$max_loops = 12;
			
			$fm = "";
			$cut = date("U");
			$repeat = true;
			while( $repeat ){
			
			++$count_loops;
			$repeat = false;
			if( ! empty( $tb ) && defined("HYELLA_REMOTE_CONNECTION") && HYELLA_REMOTE_CONNECTION ){
				$local_key = HYELLA_REMOTE_CONNECTION;
				$mac_address = get_mac_address();
				$token = date("U") . generatePassword(32,1,1,1,0) . rand();
				$fname = md5( $this->app . $token );
				
				$uri = $this->url;
				if( isset( $this->class_settings['development'] ) && $this->class_settings['development'] ){
					$pr = get_project_data();
					//$uri = $pr["domain_name"] . 'php/';
					unset( $pr );
				}
				
				$url = $uri.'sync.php?pub_k=' . $this->app.'&mac='.$mac_address.'&st='. $token .'&tk=' . md5( $fname . md5( $local_key . $mac_address ) );
				//echo $url;
				//echo $mac_address; exit;
				//echo $this->app; exit;
				
				if( (  isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
					$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
				}else{
					$background_pagepointer = dirname( dirname( __FILE__ ) ) . "/";
					$this->class_settings["calling_page"] = $background_pagepointer;
				}
				
				$dirname = 'tmp/sync-lib';
				if( ! is_dir( $this->class_settings["calling_page"] . $dirname ) ){
					create_folder( $this->class_settings["calling_page"] . $dirname, '', '' );
				}
				create_folder( $this->class_settings["calling_page"] . $dirname . '/' . $fname, '', '' );
				
				if( $limit_check ){
					$lc1 = mktime( 6, 42, 0, date("n"), date("j"), date("Y") );
					$lc2 = mktime( 19, 42, 0, date("n"), date("j"), date("Y") );
					
					$last_check = 0;
					$run_check = 0;
					if( file_exists( ( $this->class_settings["calling_page"] . $dirname . '/ctrl.json' ) ) ){
						$ltc = json_decode( file_get_contents( $this->class_settings["calling_page"] . $dirname . '/ctrl.json' ), true );
						if( isset( $ltc["last_check"] ) ){
							$last_check = doubleval( $ltc["last_check"] );
						}
					}
					
					if( $lc1 > $last_check && $cut >= $lc1 ){
						$run_check = $lc1 + 10;
					}else if( $lc2 > $last_check && $cut >= $lc2 ){
						$run_check = $lc2 + 10;
					}
					
					if( $run_check ){
						$ltc["last_time"] = $cut;
						$ltc["last_check"] = $run_check;
						file_put_contents( $this->class_settings["calling_page"] . $dirname . '/ctrl.json', json_encode( $ltc ) );
					}else{
						$last_check = isset( $ltc["last_time"] )?doubleval( $ltc["last_time"] ):$last_check;
						
						echo 'Not checking...\nLast checked ' . date("d-M-Y H:i:s", $last_check );
						return;
					}
				}
				
				//get last modified date
				
				//$tb = array( 'parish', 'parishioner', 'parish_zones', 'payment_exemption', 'payment_template', 'people', 'pledge', 'sacrament' );
				//$tb = array( 'people' );
				//$tb = array( 'debit_and_credit' );
				
				$qd = array();
				foreach( $tb as $tbv ){
					echo $tbv . " check \n\n";
					
					$jpath = $this->class_settings["calling_page"] . $dirname .'/'. $fname .'/'. $fname.$tbv . '.od1';
					$fpath = $this->class_settings["calling_page"] . $dirname .'/'. $fname.$tbv . '.zip';
					
					if( file_exists( $jpath ) ){
						echo $tbv . " Already Downloaded \n\n";
					}else{
					
						//$qd[] = "(SELECT '". $tbv ."' as 'table', `id`, `creation_date`, `modification_date`, `serial_num` FROM `".$this->class_settings["database_name"]."`.`". $tbv ."` WHERE `record_status` = '1' ORDER BY `modification_date` DESC LIMIT 1)";
						$query = "SELECT '". $tbv ."' as 'table', MAX(`creation_date`) as 'creation_date', MAX(`modification_date`) as 'modification_date', MAX(`serial_num`) as 'msn' FROM `".$this->class_settings["database_name"]."`.`". $tbv ."` ";
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'SELECT',
							'set_memcache' => 0,
							'tables' => array( $this->table_name ),
							'index_field' => 'table',
						);
						$access_data = execute_sql_query($query_settings);
						
						foreach( array( 'm_' => 'modification_date', 'c_' => 'creation_date' ) as $mk => $mv ){
							if( isset( $access_data[ $tbv ][ $mv ] ) ){
								$query = "SELECT `id` as '".$mk."id', `serial_num` as '".$mk."sn', `creation_date` as '".$mk."cd', `modification_date` as '".$mk."md' FROM `".$this->class_settings["database_name"]."`.`". $tbv ."` WHERE `".$mv."` = " . $access_data[ $tbv ][ $mv ] . " ORDER BY `serial_num` DESC LIMIT 1";
								$query_settings['query'] = $query;
								unset( $query_settings['index_field'] );
								
								$ad2 = execute_sql_query($query_settings);
								//print_r($ad2);
								if( isset( $ad2[0][ $mk.'id' ] ) ){
									$access_data[ $tbv ] = array_merge( $ad2[0], $access_data[ $tbv ] );
								}
							}
						}
						
						//print_r( $access_data ); exit;
						$data = array( 'table' => $tbv, 'table_data' => json_encode( $access_data ) );
						unset( $access_data );
						
						$options = array(
							'http' => array(
								'method' => 'POST',
								'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
								"content" => http_build_query( $data )
							)
						);
						unset( $data );
						$stream = stream_context_create($options);
						unset( $options );
						
						/* $ent = base64_encode('jame the baller. Are you BACK?');
						echo $ent . '<br /><br />Dboy: ';
						echo base64_decode( $ent ) . '<br /><hr />'; exit;  */

						//echo file_get_contents( $url, false, $stream ); exit; 
						echo "Sending Sync Request ".date("d-M-Y H:i:s")."\n\n";
						
						++$count_conn;
						file_put_contents( $fpath, file_get_contents( $url, false, $stream ) );
						
						if( file_exists( $fpath ) ){
							$destinationFolder = $this->class_settings["calling_page"] . $dirname . '/' . $fname . '/';
							$zip = new ZipArchive;
							
							if ($zip->open($fpath) === TRUE) {
								$zip->extractTo($destinationFolder);
								$zip->close();
								unset( $zip );
								unlink( $fpath );
								
								echo "\n\nFile extraction successful\n\n";
								echo $fname.$tbv ."\n\n";
								
								
								//execute queries, with device id
							} else {
								echo "\n\nERROR: Extraction failed\n\n";
								unlink( $fpath );
							}
						}else{
							
						}
					}
					
					//check filename
					if( file_exists( $jpath ) ){
						$jdf = json_decode( base64_decode( file_get_contents( $jpath ) ), true );
						
						$rec2 = '';
						if( isset( $jdf["recall"] ) ){
							$rec2 = $jdf["recall"];
							unset( $jdf["recall"] );
						}
						if( is_array( $jdf ) && ! empty( $jdf ) ){
							foreach( $jdf as $jdk => $jdv ){
								
								//print_r( $jdf ); exit;
								$count_updates += count( $jdv );
								if( $rec2 && $rec2 != 'serial_num' && count( $jdv ) < 1000 ){
									$rec2 = '';
								}
								
								if( $rec2 || count( $jdv ) >= 1000 ){
									$return["recall"][] = $jdk;
									if( $rec2 ){
										echo $jdk . " has more ".$rec2." data online \n\n";
									}else{
										echo $jdk . " has more data online \n\n";
									}
								}else{
									echo $jdk . " loaded \n\n";
								}
								
								$ads = array_chunk( $jdv, 100 );
								unset( $jdv );
								
								foreach( $ads as $adv ){
									$function_settings = array(
										'database' => $this->class_settings['database_name'],
										'connect' => $this->class_settings['database_connection'],
										'table' => $jdk,
										'dataset' => $adv,
									);
									$function_settings[ 'use_replace' ] = 1;
									
									insert_new_record_into_table( $function_settings );
									unset( $function_settings );
								}
								unset( $ads );
							}
							unset( $jdf );
						}
						unlink( $jpath );
						
					}
					
					sleep( 2 );
				}
				
				if( is_dir( $this->class_settings["calling_page"] . $dirname . '/' . $fname ) ){
					closedir(opendir( $this->class_settings["calling_page"] . $dirname . '/' . $fname ));
					rmdir( $this->class_settings["calling_page"] . $dirname . '/' . $fname );
				}
			}
			
			if( isset( $return["recall"] ) && ! empty( $return["recall"] ) ){
				file_put_contents( $this->class_settings["calling_page"] . $dirname . '/recall'.$fname.'.json', json_encode( $return["recall"] ) );
				
				$repeat = true;
				$limit_check = 0;
				$tb = $return["recall"];
				
				$x = array( "tables" => $return["recall"], "unlimited" => 1 );
				unset( $return["recall"] );
				sleep( 3 );
				
				//$this->_pull_replication_data( $x );
				unset( $x );
			}
			
			if( $count_loops > $max_loops ){
				$repeat = false;
				$fm = "PREMATURE TERMINATION: Max Loop of " . $max_loops . " was exhausted.";
				
				echo $fm;
				$par = array();
				$par["elevel"] = 'fatal';
				$par["status"] = "pull_replication_data";
				$par["comment"] = $fm;
				$par["tables"] = $tb;
				auditor("", "console", $this->table_name, $par );
			}
			}
			
			$srcd = $this->class_settings["calling_page"] . $dirname;
			if( is_dir( $srcd ) ){
				$dird = opendir( $srcd );
				while(false !== ( $filed = readdir($dird)) ) {
					if (( $filed != '.' ) && ( $filed != '..' )) {
						$fulld = $srcd . '/' . $filed;
						if ( is_dir($fulld) ) {
							closedir(opendir($fulld));
							rmdir($fulld);
						}
					}
				}
				closedir($dird);
			}

			$par = array();
			$par["start_time"] = $cut;
			$par["end_time"] = date("U");
			
			$par["error"] = $fm;
			$par["loops"] = $count_loops;
			$par["requests"] = $count_conn;
			$par["updates"] = $count_updates;
			$return["success"] = $count_updates;
			
			$settings = array(
				'cache_key' => "pull_replication_data",
				'permanent' => true,
				'cache_values' => $par,
			);
			set_cache_for_special_values( $settings );
			
			if( $count_updates ){
				if( isset( $opt["refresh_cache"] ) && $opt["refresh_cache"] ){
					$tp["get"] = $_GET;
					$tp["post"] = $_POST;
					
					$_GET = array();
					$_POST = array();
					
					$_POST["id"] = "-";
					$this->class_settings["action_to_perform"] = 'refresh_cache_in_background';
					$this->_refresh_cache_in_background();
					
					$_GET = $tp["get"];
					$_POST = $tp["post"];
					unset( $tp );
				}
			}
			
			return $return;
			//check for online db
		}
		
		protected function _nwp_target(){
			$_GET = $GLOBALS["nwp_target"];
			unset( $GLOBALS["nwp_target"] );
			
			if( isset( $_GET['pdata'] ) && $_GET['pdata'] ){
				$_POST = json_decode( rawurldecode( $_GET['pdata'] ), true );
				unset( $_GET['pdata'] );
			}
			
			$todo = '';
			$error_msg = "";
			if( isset( $_GET["action"] ) && $_GET["action"] && isset( $_GET["todo"] ) && $_GET["todo"] ){
				$tb = $_GET["action"];
				$todo = $_GET["todo"];
				$_GET["modal"] = 1;
				
				if( isset( $_GET["nwp_action"] ) && $_GET["nwp_action"] && isset( $_GET["nwp_todo"] ) && $_GET["nwp_todo"] ){
					$clp = 'c' . ucwords( strtolower( $_GET["action"] ) );
					$tb = $_GET["nwp_action"];
					$todo = $_GET["nwp_todo"];
					if( class_exists( $clp ) ){
						$ptb = new $clp();
						$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
						if( isset( $in[ $tb ]->table_name ) ){
							$cl = $in[ $tb ];
						}else{
							$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
						}
					}else{
						$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
					}
				}else{
					$cls = 'c' . ucwords( $tb );
					if( class_exists( $cls ) ){
						$cl = new $cls();
					}else{
						$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
					}
				}
			}
			
			$cll = new cCustomer_call_log();
			
			if( ! $error_msg ){
				$cl->class_settings = $this->class_settings;
				$cl->class_settings["action_to_perform"] = $todo;
				$r = $cl->$tb();
				
				$cll->class_settings = $this->class_settings;
				return $cll->_display_html_only( $r );
			}
			
			return $cll->_display_notification( array( "type" => "error", "message" => $error_msg ) );
		}
		
		protected function _use_test_data(){
			$msg = '';
			$err_code = '010014';
			
			if( isset( $_SESSION["test_server"] ) && $_SESSION["test_server"] ){
				unset( $_SESSION["test_server"] );
				$msg = '<h4>Using Live Database</h4><p>The Application has switched to the LIVE mode</p>';
				$err_code = '010011';
			}else{
				$_SESSION["test_server"] = 1;
				$msg = '<h4>Using Test Database</h4><p>The application is now in test mode</p>';
			}
			
			$err = new cError( $err_code );
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			$return["manual_close"] = 1;
			
			unset( $return["html"] );
			$return["status"] = 'new-status';
			
			$return["redirect_url"] = 'index.html';
			
			return $return;
		}
		
		protected function _get_capabilities(){
			$ff = new cFunctions();
			
			$query = "SELECT `id`, `".$ff->table_fields["function_class"]."` as 'function_class', `".$ff->table_fields["function_action"]."` as 'function_action' FROM `".$this->class_settings['database_name']."`.`".$ff->table_name."` WHERE `record_status` = '1' AND `".$ff->table_fields["function_class"]."` = '". $this->table_name ."' ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $ff->table_name ),
			);
			
			$access_data1 = array();
			
			$access_data = execute_sql_query($query_settings);
			if( ! empty( $access_data ) ){
				foreach( $access_data as $avs ){
					$access_data1[ $avs["function_action"] ][ $avs["function_class"] ] = $avs["id"];
				}
			}
			
			return $access_data1;
		}
		
		protected function _notify_dept(){
			
			$running_in_background = 0;
			
			if( isset( $this->class_settings['running_in_background'] ) && $this->class_settings['running_in_background'] ){
				$running_in_background = ( isset( $_POST['id'] ) && $_POST['id'] )?$_POST['id']:0;
			}
			
			//$running_in_background = '1565440188a132';
			
			if( $running_in_background ){
				sleep(1);
				
				$bsettings = array(
					'cache_key' => 'notify_dept-' . $running_in_background,
					'directory_name' => $this->table_name,
					'permanent' => true,
				);
				$d = get_cache_for_special_values( $bsettings );
				clear_cache_for_special_values( $bsettings );
				
				$exclude = array();
				
				if( isset( $this->class_settings["database_connection"] ) && $this->class_settings["database_connection"] ){
					
					if( isset( $d["key"] ) && $d["key"] && isset( $d["subject"] ) && $d["subject"] && isset( $d["content"] ) && $d["content"] ){
						//get users with view all division
						//$access_data1 = $this->_get_capabilities();
						
						$kk = $d["key"];
						$tid = 'users.rnotice.rnotice';
						
						if( $tid ){
							$options = $d;
							//$options["subject"] = 'System Notice';
							$options["sending_option"] = 'bcc';
							$options["capability"] = $tid;
							$options["trigger"] = 'audit.' . $kk;
							$options["users_filter"] = array( "role" => "1300130013" );
							$options["users_filter_condition"] = " OR ";
							
							$c = new cCustomer_call_log();
							$c->class_settings = $this->class_settings;
							$c->_notify_people( $options );
						}
					}
					
				}
			}
		}
		
		private function _open_backup_directory(){
			
			$err_code = '010014';
			$msg = '<h4>Back-up Directory Not Found</h4><p>There is no back-up directory on the server</p>';
			$dir = ( isset( $_GET["dir"] ) && $_GET["dir"] )?$_GET["dir"]:'dump';
			
			if( is_dir( $this->class_settings[ 'calling_page' ] . 'php/'.$dir.'/' ) ){
				run_in_background("open_backup_directory", 0, array( "text" => realpath( $this->class_settings[ 'calling_page' ] . 'php/'.$dir.'/' ) ) );
				
				$err_code = '010011';
				$msg = '<h4>Back-up Directory Open on the Server</h4><p>The back-up directory has been opened on the Server</p>';
			}
			
			$err = new cError( $err_code );
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			return $err->error();
		}
		
		private function _export_db_prompt(){
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$title = 'Export Database';
			$exports2 = array();
			$exports = array();
			
			$platform = '';
			if( defined( 'PLATFORM' ) ){
				$platform = PLATFORM;
			}
			
			$show_export = 1;
			$dump_path = 'php';
			
			switch( $platform ){
			case "linux":
				$show_export = 0;
				$dump_path = 'tmp';
			break;
			}
			
			if( is_dir( $this->class_settings[ 'calling_page' ] . $dump_path . '/dump/' ) ){
				//3. Open & Read all files in directory
				$cdir =  $this->class_settings[ 'calling_page' ] . $dump_path . '/dump/';
				
				foreach( glob( $cdir . "*.ela" ) as $filename ) {
					if ( is_file( $filename ) ) {
						$p = pathinfo( $filename );
						$p["dir"] = $dump_path . '/dump/';
						$exports[ $p["filename"] ] = $p;
					}
				}
				
				asort( $exports );
			}
			
			if( is_dir( $this->class_settings[ 'calling_page' ] . $dump_path . '/dump-files/' ) ){
				//3. Open & Read all files in directory
				$cdir =  $this->class_settings[ 'calling_page' ] . $dump_path . '/dump-files/';
				
				foreach( glob( $cdir . "*.hyella" ) as $filename ) {
					if ( is_file( $filename ) ) {
						$p = pathinfo( $filename );
						$p["dir"] = $dump_path . '/dump-files/';
						$exports2[ $p["filename"] ] = $p;
					}
				}
				
				asort( $exports2 );
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/export-database-prompt.php' );
			$this->class_settings[ 'data' ]["show_export"] = $show_export;
			$this->class_settings[ 'data' ]["title"] = $title;
			$this->class_settings[ 'data' ]["exported_databases"] = $exports;
			$this->class_settings[ 'data' ]["exported_files"] = $exports2;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _display_application_update_view(){
			$running_in_background = 0;
			// $this->class_settings['running_in_background'] = 1;
			if( $this->class_settings['running_in_background'] && $this->class_settings['running_in_background'] ){
				$running_in_background = ( isset( $_POST['id'] ) && $_POST['id'] )?$_POST['id']:1;
			}
			
			$js = array( 'set_function_click_event' );
			$html_q = '';
			$html = '';
			$error_msg = '';
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$ifil = array();
			
			switch( $action_to_perform ){
			case 'process_duplicates':
				$tb = isset( $_POST[ 'table' ] ) ? $_POST[ 'table' ] : '';
				$pl = isset( $_POST[ 'plugin' ] ) ? $_POST[ 'plugin' ] : '';
				$field = isset( $_POST[ 'field' ] ) ? $_POST[ 'field' ] : '';
				$limit = isset( $_POST[ 'limit' ] ) ? $_POST[ 'limit' ] : '';
						
				if( ! ( $tb && $field && $limit ) )$error_msg = "<h4><strong>Undefined Parameters</strong></h4><p>Please contact your support team</p>";
						
				if( isset( $pl ) && $pl ){
					$clp = 'c' . ucwords( strtolower( $pl ) );
					if( class_exists( $clp ) ){
						$ptb = new $clp();
						$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
						if( isset( $in[ $tb ]->table_name ) ){
							$cl = $in[ $tb ];
						}else{
							$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
						}
					}else{
						$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
					}
				}else{
					$cls = 'c' . ucwords( $tb );
					if( class_exists( $cls ) ){
						$cl = new $cls();
					}else{
						$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
					}
				}
				
				if( ! function_exists( $tb ) )$error_msg = "<h4><strong>Undefined Function</strong></h4><p>". $tb ." could not be found.</p>";

				if( ! $error_msg ){
					$tbl = $tb();

					$havnig = '';
					if( $limit )$havnig = ' HAVING count > '.$limit;

					$where = " `". $tb ."`.`record_status` = '1' ";
					$join = "";
					$qf = "`". $tb ."`.`". $field ."`";
					// print_r( $tbl[ $field ] );exit;
					if( isset( $tbl[ $field ] ) && isset( $tbl[ $field ][ 'calculations' ][ 'type' ] ) ){
						switch( $tbl[ $field ][ 'calculations' ][ 'type' ] ){
						case "customers":
							$tbl[ $field ][ 'calculations' ][ 'reference_table' ] = 'customers';
							$tbl[ $field ][ 'calculations' ][ 'reference_keys' ][0] = 'name';
							$tbl[ $field ][ 'calculations' ][ 'reference_keys' ][1] = 'first_name';
							$tbl[ $field ][ 'calculations' ][ 'reference_keys' ][2] = 'other_name';
							$tbl[ $field ][ 'calculations' ][ 'reference_keys' ][3] = 'file_number';
						break;
						case "item-details":
							$tbl[ $field ][ 'calculations' ][ 'reference_table' ] = 'items';
							$tbl[ $field ][ 'calculations' ][ 'reference_keys' ][0] = 'description';
							$tbl[ $field ][ 'calculations' ][ 'reference_keys' ][1] = 'barcode';
						break;
						}
					}
					
					if( isset( $tbl[ $field ] ) && isset( $tbl[ $field ][ 'calculations' ][ 'reference_table' ] ) && $tbl[ $field ][ 'calculations' ][ 'reference_table' ] && isset( $tbl[ $field ][ 'calculations' ][ 'reference_keys' ][0] ) && $tbl[ $field ][ 'calculations' ][ 'reference_keys' ][0] ){
						$jc = 'c'.$tbl[ $field ][ 'calculations' ][ 'reference_table' ];
						if( class_exists( $jc ) ){
							$jc = new $jc;
							$qf2 = "";

							foreach( $tbl[ $field ][ 'calculations' ][ 'reference_keys' ] as $rk => $rv ){
								if( isset( $jc->table_fields[ $rv ] ) && $jc->table_fields[ $rv ] ){
									if( $qf2 ){
										$qf2 .= ", ' - ', ";
									}
									$qf2 .= " `". $jc->table_name ."`.`". $jc->table_fields[ $rv ] ."` ";
								}
							}
							$qf .= ", CONCAT( ". $qf2 ." ) as 'label' ";
							$join = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $jc->table_name ."` ON `". $jc->table_name ."`.`id` = `". $cl->table_name ."`.`". $field ."` ";
						}
					}

					//$query = " SELECT COUNT(*) AS 'count', ". $qf ." FROM `". $tb ."` ". $join ." WHERE ". $where ." GROUP BY `". $tb ."`.`". $field ."` ". $havnig ." ORDER BY `". $tb ."`.`". $field ."` ";
					$query = " SELECT COUNT(*) AS 'count', ". $qf ." FROM `". $tb ."` ". $join ." WHERE ". $where ." GROUP BY `". $tb ."`.`". $field ."` ". $havnig ." ORDER BY count DESC ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query ,
						'query_type' => 'SELECT' ,
						'set_memcache' => 1 ,
						'tables' => array( $tb ),
					);
					$sql_result = execute_sql_query($query_settings);

					$this->class_settings[ 'data' ][ 'field_values' ] = array();
					// print_r( get_workflow_status() );exit;
					if( isset( $tbl[ $field ] ) && isset( $tbl[ $field ][ 'form_field_options' ] ) && function_exists( $tbl[ $field ][ 'form_field_options' ] ) ){
						$ffo = $tbl[ $field ][ 'form_field_options' ];
						$this->class_settings[ 'data' ][ 'field_values' ] = $ffo();
					}

					$this->class_settings[ 'data' ][ 'table' ] = $tb;
					$this->class_settings[ 'data' ][ 'plugin' ] = $pl;
					$this->class_settings[ 'data' ][ 'field' ] = $field;
					$this->class_settings[ 'data' ][ 'field_name' ] = isset( $tbl[ $field ] ) && isset( $tbl[ $field ][ 'field_label' ] ) ? $tbl[ $field ][ 'field_label' ] : '';
					$this->class_settings[ 'data' ][ 'data' ] = $sql_result;
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/duplicates-datatable-result.php' );
					$html = $this->_get_html_view();
				}
			break;
			case 'display_data_view':
			case 'save_display_data_access_view':
			case 'save_display_data_access_view2':
				$duplicates = 0;
				
				switch( $action_to_perform ){
				case 'display_data_view':
					if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
						$_POST[ 'data_source' ] = $_POST[ 'id' ];
					}
					$this->class_settings[ 'apply_access_control' ] = 1;
					if( isset( $_GET[ 'filter' ] ) && $_GET[ 'filter' ] ){
						$ifil = json_decode( rawurldecode( $_GET[ 'filter' ] ), true );
					}
				break;
				case 'save_display_data_access_view2':
					$_POST[ 'data_source' ] = isset( $_GET[ 'data_source' ] ) ? $_GET[ 'data_source' ] : '';
					$_POST[ 's_option' ] = isset( $_GET[ 's_option' ] ) ? $_GET[ 's_option' ] : '';
					$duplicates = 1;
				break;
				}
				
				if( isset( $_POST[ 'data_source' ] ) && $_POST[ 'data_source' ] ){
					$s_option = isset( $_POST[ 's_option' ] )?$_POST[ 's_option' ]:'';
					$tbd = explode( ':::' , $_POST[ 'data_source' ] );
					$tba = array();
					if( ! empty( $tbd ) ){
						foreach( $tbd as $tbv ){
							$tba2 = explode( '=', $tbv );
							if( isset( $tba2[1] ) && $tba2[1] ){
								$tba[ trim( $tba2[0] ) ] = trim( $tba2[1] );
							}
						}
					}
					$tb_type = isset( $tba["type"] ) ? $tba["type"] : '';
					$tb = isset( $tba["value"] ) ? $tba["value"] : '';

					$todo = 'display_all_records_frontend';
					$skip = 0;
					$testing = 0;
					
					switch( $s_option ){
					case "spreadsheet":
						$todo = 'display_spreadsheet_view';
						$todo = 'display_spreadsheet_new';
					break;
					case "datatable_trash":
						$todo = 'display_all_deleted_records';
					break;
					case "run_tests":
						$testing = 1;
					break;
					}
					
					switch( $tb_type ){
					case "plugin":
						
						if( isset( $tba["key"] ) && $tba["key"] ){
							$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
							if( class_exists( $clp ) ){
								$ptb = new $clp();


								if( $testing ){
									$ptb->class_settings = $this->class_settings;
									$html = $ptb->test([ 'return_string' => true ]);
									break 2;
								}else{
									$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
									if( isset( $in[ $tb ]->table_name ) ){
										$cl = $in[ $tb ];
									}else{
										$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
									}
								}

							}else{
								$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
							}
						}else{
							$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
						}
						
					break;
					default:
						if( $testing ){
							$error_msg = "<h4><strong>Automated Tests are only compatible with Plugins</strong></h4>";
						}else{
							$cls = 'c' . ucwords( $tb );
							if( class_exists( $cls ) ){
								$cl = new $cls();
							}else{
								$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
							}
						}
					break;
					}
					
					if( ! $error_msg ){
						switch( $s_option ){
						case "duplicates":
							$skip = 1;
							$ctb = 'c'.$tb;
							if( class_exists( $ctb ) && function_exists( $tb ) ){
								$ctb = new $ctb;
								$this->class_settings[ 'data' ][ 'table' ] = $tba["value"];
								$this->class_settings[ 'data' ][ 'plugin' ] = isset( $ptb->table_name ) ? $ptb->table_name : '';

								$tbl = $tb();
								foreach( $ctb->table_fields as $tbk => $tbv ){
									if( ! isset( $tbl[ $tbv ] ) )continue;
									switch( $tbl[ $tbv ][ 'form_field' ] ){
									case 'calculated':
										if( isset( $tbl[ $tbv ][ 'attributes' ] ) && strpos( $tbl[ $tbv ][ 'attributes' ], 'tags=' ) > -1 )continue 2;
									case 'text':
									case 'number':
									case 'select':
									case 'radio':
									case 'decimal_long':
									case 'decimal':
									case 'currency':
										$this->class_settings[ 'data' ][ 'field_options' ][ $tbv ] = $tbl[ $tbv ][ 'field_label' ];
									break;
									}
								}

								$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/duplicates-datatable.php' );
								$html = $this->_get_html_view();
								$js[] = 'prepare_new_record_form_new';
							}else{
								$error_msg = "<h4><strong>Undefined Function</strong></h4><p>". $tb ." could not be found.</p>";
							}
						break;
						}
						
						if( ! $skip ){
							$cc = new cCustomer_call_log();
							$cc->class_settings = $this->class_settings;
							
							$tb2 = $cc->table_name;
							$cc->table_fields = $cl->table_fields;
							$cc->table_name = $cl->table_name;
							
							if( isset( $cl->db_table_name ) && $cl->db_table_name ){
								$cc->table_name = $cl->db_table_name;
							}
							$cc->label = ( isset( $cl->label )?$cl->label:$cl->table_name );
							
							if( isset( $cl->spreadsheet_options ) && $cl->spreadsheet_options ){
								$cc->spreadsheet_options = $cl->spreadsheet_options;
							}
							
							if( isset( $cl->plugin ) && $cl->plugin ){
								$cc->plugin = $cl->plugin;
							}
							
							$where = "";
							if( ! empty( $ifil ) ){
								foreach( $ifil as $ifk => $ifv ){
									if( isset( $cc->table_fields[ $ifk ] ) ){
										$where .= " AND `".$cc->table_name."`.`".$cc->table_fields[ $ifk ]."` = '".$ifv."' ";
									}
								}
								
								switch( $cc->table_name ){
								case "banks":
									if( isset( $ifil["type"] ) ){
										$cc->class_settings[ 'datatable_settings' ]["button_params"] = '&type=' . $ifil["type"];
									}
								break;
								}
							}

							switch( $action_to_perform ){
							case 'save_display_data_access_view2':
								if( isset( $_GET[ 'field' ] ) && $_GET[ 'field' ] ){
									if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] == '-' ){
										$where .= " AND ( `".$cc->table_name."`.`". $_GET[ 'field' ] ."` = '' OR `".$cc->table_name."`.`". $_GET[ 'field' ] ."` IS NULL ) ";
									}else  if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
										$where .= " AND `".$cc->table_name."`.`". $_GET[ 'field' ] ."` = '". $_POST[ 'id' ] ."' ";
										$cc->class_settings['title'] = $cc->label . ' - '.$_POST[ 'id' ];
									}
								}
							break;
							}

							switch( $cc->table_name ){
							case "users":
							case "access_roles":
							case "parish_transaction":
							case "vendor_bills":
							case "vendors":
							case "production":
							case "expenditure":
							case "sales":
							case "customers":
							case "void_transactions":
							case "transactions":
							case "general_settings":
							case "online_payment":
							case "payment":
							case "revision_history":
							case "debit_and_credit":
							case "inventory_ent":
								$hide = 0;
								if( ! get_hyella_development_mode() ){
									$hide = 1;
									if( $this->class_settings[ 'user_id' ] == '35991362173x' ){
										$hide = 0;
									}
								}
								if( $hide ){
									$cc->class_settings[ 'datatable_settings' ]['show_add_new'] = 0;
									$cc->class_settings[ 'datatable_settings' ]['show_edit_button'] = 0;
									$cc->class_settings[ 'datatable_settings' ]['show_delete_button'] = 0;
								}
							break;
							}
							
							if( $where )$cc->class_settings["filter"][ 'where' ] = $where;
							$cc->class_settings[ 'datatable_settings' ]['utility_buttons']['view_details'] = 1;
							if( $duplicates ){
								$cc->class_settings[ 'full_table' ] = 1;
								$cc->class_settings[ 'datatable_settings' ]['show_add_new'] = 0;
								$cc->class_settings[ 'datatable_settings' ]['show_edit_button'] = 0;
								
							}
							$cc->class_settings[ 'base_call' ] = 1;
							$cc->class_settings[ 'action_to_perform' ] = $todo;

							return $cc->$tb2();
							/* $r = $cc->$tb2();

							if( isset( $r[ 'html_replacement' ] ) && $r[ 'html_replacement' ] ){
								$html = $r[ 'html_replacement' ];

								$js = $r[ 'javascript_functions' ];
							}else{
								$error_msg = "<h4><strong>Unable to Retrieve Datatable</strong></h4><p>The datatable for '". $cc->label ."' has not been configured.</p>";
							} */
						}
					}
					
				}else{
					$error_msg = "<h4><strong>Undefined/Missing Table Name </strong></h4><p>The table name is not found. Please define a table name</p>";
				}
			break;
			case 'display_data_access_view':
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-data-access-view.php' );
				$html = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $html,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ) 
				);
			break;
			case 'move_data_from_old_fields_to_new_background':
				set_time_limit(0);
				$tables = array();
				
				if( $running_in_background ){
					$tables = $this->_get_project_classes( array( "for_data_movement" => 1 ) );
					
				}else if( isset( $_POST["tables"] ) && $_POST["tables"] ){
					$show_queries_only = ( isset( $_POST["show_queries_only"] ) && $_POST["show_queries_only"] )?1:0;
					
					$tbs = explode(',', $_POST["tables"] );
					if( ! empty( $tbs ) ){
						$tb2 = array();
						foreach( $tbs as $tbk ){
							$tb2[ $tbk ] = 1;
						}
						
						$tables = $this->_get_project_classes( array( "for_data_movement" => 1, "selected_class_only" => $tb2 ) );
						
					}else{
						$error_msg = '<h4>No classes were specified</h4>';
					}
				}else{
					$error_msg = '<h4>Invalid Input</h4>';
				}
				
				
				if( ! empty( $tables ) ){
					$test_table = 1;
					
					foreach( $tables as $table => $tv ){
						$skip_add = 0;
						$update_parameters = array();
						
						if( isset( $tv["old_table_fields"] ) && is_array( $tv["old_table_fields"] ) && ! empty( $tv["old_table_fields"] ) ){
							
							if( $test_table ){
								$query = "DESCRIBE `" . $this->class_settings['database_name'] . "`.`" . $table . "`";
				
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query ,
									'query_type' => 'DESCRIBE' ,
									'set_memcache' => 1 ,
									'tables' => array( $table ),
								);
								$sql_result = execute_sql_query($query_settings);
								
								if( $sql_result && is_array($sql_result) ){
									$flip = array_flip( $tv["old_table_fields"] );
									$flip_new = array_flip( $tv["table_fields"] );
									
									foreach($sql_result as $sval){
										if( isset( $flip[ $sval[0] ] ) ){
											unset( $flip[ $sval[0] ] );
										}
										
										if( isset( $flip_new[ $sval[0] ] ) ){
											unset( $flip_new[ $sval[0] ] );
										}
									}
									
									
									if( ! empty( $flip ) ){
										$html .= '<div class="note note-danger">'.$tv['label'].' does not have the following <strong>old</strong> fields<br /><ol><li>'. implode( '</li><li>', $flip ) .'</li></ol></div>';
										$skip_add = 1;
									}
									
									if( ! empty( $flip_new ) ){
										$html .= '<div class="note note-danger">'.$tv['label'].' does not have the following <strong>new</strong> fields<br /><ol><li>'. implode( '</li><li>', $flip_new ) .'</li></ol></div>';
										$skip_add = 1;
									}
								}else{
									$html .= '<div class="note note-danger">'.$tv['label'].' does not have table fields. Describe Query Failed</div>';
									$skip_add = 1;
								}
							}
							
							foreach( $tv["old_table_fields"] as $okey => $oval ){
								if( isset( $tv["table_fields"][ $okey ] ) ){
									$update_parameters[] = " `". $tv["table_fields"][ $okey ] ."` = `".$oval."` ";
								}else{
									$html .= '<div class="note note-danger">'.$tv['label'].' . '.$okey.': does not exist in the <strong>new table fields</strong></div>';
									$skip_add = 1;
								}
							}
							
						}else{
							$html .= '<div class="note note-danger">'.$tv['label'].': old table fields does not exists</div>';
						}
						
						if( $skip_add ){
							$update_parameters = array();
						}
						
						if( ! empty( $update_parameters ) ){
							$query = "UPDATE `".$this->class_settings["database_name"]."`.`". $table ."` SET " . implode( ", ", $update_parameters );
							
							if( $show_queries_only ){
								$html_q .= $query . ';<br /><br />';
							}else{
								$query_settings = array(
									'database' => $this->class_settings["database_name"],
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query,
									'query_type' => 'EXECUTE_SYS',
									'set_memcache' => 0,
									'tables' => array( $table ),
								);
								execute_sql_query($query_settings);
							}
							
							if( $running_in_background ){
								echo $query . "\n\n";
							}else{
								$html .= '<div class="note note-success">'.$tv['label'].' successfully migrated</div>';
								//$html .= $query;
							}
							
							//drop old fields
							$query = "ALTER TABLE `".$this->class_settings["database_name"]."`.`". $table ."` DROP ". implode(", DROP ", $tv["old_table_fields"] );
							
							if( $show_queries_only ){
								$html_q .= $query . ';<br /><br />';
							}else{
								$query_settings = array(
									'database' => $this->class_settings["database_name"],
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query,
									'query_type' => 'EXECUTE_SYS',
									'set_memcache' => 0,
									'tables' => array( $table ),
								);
								execute_sql_query($query_settings);
							}
							
							if( $running_in_background ){
								echo $query . "\n\n";
							}else{
								$html .= '<div class="note note-success">Old fields have been deleted</div>';
								//$html .= $query;
							}
							
						}
						
						
					}
				}
				
			break;
			case 'drop_views_background':
				//$db = 'perspectiva_test';
				$db = $this->class_settings["database_name"];
				
				$query = "SELECT `TABLE_NAME` as 'view' FROM information_schema.views WHERE table_schema = '".$db."'";
				
				$query_settings = array(
					'database' => $db,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				
				if( is_array( $all_data ) && ! empty( $all_data ) ){
					foreach( $all_data as $av ){
						$q = "DROP VIEW `".$db."`.`".$av["view"]."` ";
						
						$query_settings = array(
							'database' => $db,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $q,
							'query_type' => 'EXECUTE_SYS',
							'set_memcache' => 0,
							'tables' => array(),
						);
						execute_sql_query($query_settings);
						
						//sleep(1);
						echo $q . "\n";
					}
				}
			break;
			case 'move_data_from_old_fields_to_new':
			case 'drop_views':
				run_in_background( "bprocess", 0, 
					array( 
						"action" => $this->table_name, 
						"todo" => $action_to_perform . "_background", 
						"user_id" => 1, 
						"reference" => "import_files",
						"show_window" => 1,
					) 
				);
				
				$msg = '<h4>Dropping Views</h4><p></p>';
				
				switch( $action_to_perform ){
				case 'move_data_from_old_fields_to_new':
					$msg = '<h4>Moving Data</h4><p></p>';
				break;
				}
				
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = $msg;
				return $err->error();
			break;
			case "application_update_start_import_background":
				if( $running_in_background ){
					$settings = array(
						'cache_key' => $running_in_background,
						'permanent' => true,
					);
					$c = get_cache_for_special_values( $settings );
					
					if( isset( $c["file"] ) && $c["file"] && file_exists( $this->class_settings["calling_page"] . $c["file"] ) ){
						
						$date_filter2 = 'Y.m.d.H.i';
						$major_build = 0;
						
						$exclude_files = array();
						
						$version = date( $date_filter2 );
						
						echo "\n\nBegining export of old version \n\n";
						
						$package = get_package_option();
						
						//$destinationFolder = 'C:\hyella\htdocs\feyi\update\\' . $package . '-' . $version . '.zip';
						
						if( (  isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
							$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
						}else{
							$background_pagepointer = dirname( dirname( __FILE__ ) ) . "/";
							$this->class_settings["calling_page"] = $background_pagepointer;
						}

						if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/version-history' ) ){
							create_folder( $this->class_settings["calling_page"] . 'tmp/version-history', '', '' );
						}
						
						if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/version-new-updates' ) ){
							create_folder( $this->class_settings["calling_page"] . 'tmp/version-new-updates', '', '' );
						}
						
						$destinationFolder = $this->class_settings["calling_page"] . 'tmp/version-new-updates/new-update.zip';
						
						$archive = $this->class_settings["calling_page"] . 'tmp/version-history/' . $package . '-' . $version . '.zip';
						
						if( file_exists( $destinationFolder ) ){
							unlink( $destinationFolder );
						}
						
						copy( $this->class_settings["calling_page"] . $c["file"], $destinationFolder );
						
						$r = $this->_create_zip( 
							array(), 
							$archive,
							true, 
							array( 
								'directory_path' => HYELLA_DOC_ROOT . HYELLA_INSTALL_ENGINE, 
								'exclude_files' => $exclude_files, 
								'exclude_folders' => array( 'engine\files', 'client', 'engine\assets', 'engine\images', 'engine\tmp', 'engine\php\download', 'engine\php\request-log', 'engine\php\dump-files','engine\php\dump', 'engine\php\mail-status', 'engine\frontend-assets', 'engine\classes\dompdf', 'engine\classes\PHPExcel', 'engine\classes\PHPMailer-master', 'engine\classes\simplehtmldom', 'engine\classes\formula-parser-master', 'engine\classes\basic-excel', 'sign-in\assets\assets', 'sign-in\assets\fonts', 'sign-in\assets\img', 'sign-in\assets\plugins', 'sign-in\assets\scripts', 'sign-in\hospital-assets', 'sign-in-mcs', 'i-menu', 'update', 'h-dashboard', 'blood-hound', '.git', 'engine\plugins\.git' ),
								//'show_info' => 1 
							) 
						);
						
						if( file_exists( $archive ) ){
							//extract $destinationFolder
							
							$filepath = $destinationFolder;
							$destinationFolder = 'C:\hyella\htdocs\test\\';
							
							if( (  isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
								$destinationFolder = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_ENGINE;
							}else{
								$destinationFolder = dirname( dirname( dirname( __FILE__ ) ) ) . "/";
							}
							
							$zip = new ZipArchive;
							
							if ($zip->open($filepath) === TRUE) {
								$zip->extractTo($destinationFolder);
								$zip->close();
								echo "\n\nFile extraction successful\n\n";
								
							} else {
								echo "\n\nERROR: Extraction failed\n\n";
							}
							
							
						}else{
							echo "\n\nERROR: Unable to save historical file \n\n";
						}
						
					}else{
						echo "\n\nERROR: Unable to Retrieve Cache \n\n";
					}
				}
			break;
			case "application_update_start_import":
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					if( file_exists( $this->class_settings["calling_page"] . $_POST["id"] ) ){
						
						$settings = array(
							'cache_key' => "import_files",
							'permanent' => true,
							'cache_values' => array( "file" => $_POST["id"] ),
						);
						set_cache_for_special_values( $settings );
						
						run_in_background( "bprocess", 0, 
							array( 
								"action" => $this->table_name, 
								"todo" => "application_update_start_import_background", 
								"user_id" => 1, 
								"reference" => "import_files",
								"show_window" => 1,
							) 
						);
						
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cAudit.php';
						$err->method_in_class_that_triggered_error = '_sync_progress';
						$err->additional_details_of_error = '<h4>Update Operation in Progress</h4><p>Please check console window for progress...</p>';
						$return = $err->error();
						
						$return["status"] = "new-status";
						$return["html_replacement_selector"] = "#dash-board-main-content-area";
						$return["html_replacement"] = $return["html"];
						unset( $return["html"] );
						
						return $return;
						
					}else{
						$err_title = "Failed to Locate Update File";
						$err_msg = "Please ensure you have the right file and then try again";
					}
				}else{
					$err_title = "Oops. Sorry an unknown error occurred";
					$err_msg = "Please ensure you have the right file and then try again";
				}
				
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
				return $err->error();
			break;
			case "save_export_updated_files_background":
						// print_r( $running_in_background );exit;
				if( $running_in_background ){
					$settings = array(
						'cache_key' => $running_in_background,
						'permanent' => true,
					);
					$c = get_cache_for_special_values( $settings );
					
					if( isset( $c["start_date"] ) && $c["start_date"] ){
						
						$date_filter = 'd-M-Y H:i';
						$date_filter2 = 'Y.m.d.H.i';
						$major_build = 0;
						
						$exclude_files = array();
						$exclude_files[] = 'engine\api\request-log';
						
						if( isset( $c["major"] ) && $c["major"] ){
							$date_filter = 'd-M-Y';
							$date_filter2 = 'Y.m.d';
							$major_build = 1;
							
							$exclude_files[] = 'engine\settings\provisioning.php';
							$exclude_files[] = 'engine\settings\Config.php';
							$exclude_files[] = 'engine\settings\url_settings.php';
							$exclude_files[] = 'sign-in\assets\custom.plugin.url.js';
						}
						
						$start_date = date( $date_filter , doubleval( $c["start_date"] ) );
						$version = date( $date_filter2 );
						
						echo "\n\nBegining export of updated files from: " . $start_date . "\n\n";
						
						$package = get_package_option();
						
						$version_info[ $package ] = array(
							"major_build" => $major_build,
							"package" => $package,
							"last_version" => get_app_version(),
							"version" => $version,
						);
						
						if( file_exists( $this->class_settings[ 'calling_page' ] . 'BUILD.hyella' ) ){
							unlink( $this->class_settings[ 'calling_page' ] . 'BUILD.hyella' );
						}
						
						if( file_put_contents( $this->class_settings[ 'calling_page' ] . 'BUILD.hyella', json_encode( $version_info ) ) ){
							echo "\n\nVersion File created: " . $version . "\n\n";
						}
						
						//$destinationFolder = 'C:\hyella\htdocs\feyi\update\\' . $package . '-' . $version . '.zip';
						
						if( (  isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
							$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
						}else{
							$background_pagepointer = dirname( dirname( __FILE__ ) ) . "/";
							$this->class_settings["calling_page"] = $background_pagepointer;
						}

						if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/version-new-updates' ) ){
							create_folder( $this->class_settings["calling_page"] . 'tmp/version-new-updates', '', '' );
						}
						
						$destinationFolder = $this->class_settings["calling_page"] . 'tmp/version-new-updates/' . $package . '-' . $version . '.zip';
						
						$destinationFolder2 = $this->class_settings["calling_page"] . 'tmp/version-new-updates/' . $package . '-' . $version . '.hyella';
						
						//realpath( $_SERVER["DOCUMENT_ROOT"] ) . 
						
						$r = $this->_create_zip( 
							array(), 
							$destinationFolder, 
							true, 
							array( 
								'directory_path' => HYELLA_DOC_ROOT . HYELLA_INSTALL_ENGINE, 
								'exclude_files' => $exclude_files, 
								'exclude_folders' => array( 'engine\files', 'client', 'engine\assets', 'engine\images', 'engine\tmp', 'engine\php\download', 'engine\php\request-log', 'engine\php\dump-files','engine\php\dump', 'engine\php\mail-status', 'engine\frontend-assets', 'engine\classes\dompdf', 'engine\classes\PHPExcel', 'engine\classes\PHPMailer-master', 'engine\classes\simplehtmldom', 'engine\classes\formula-parser-master', 'engine\classes\basic-excel', 'sign-in\assets\assets', 'sign-in\assets\fonts', 'sign-in\assets\img', 'sign-in\assets\plugins', 'sign-in\assets\scripts', 'sign-in\hospital-assets', 'sign-in-mcs', 'i-menu', 'update', '.git', 'engine\plugins\.git', 'h-dashboard', 'blood-hound' ), 
								'start_time' => doubleval( $c["start_date"] ), 
								'show_info' => 1 
							) 
						);
						
						if( file_exists( $destinationFolder ) ){
							
							if( copy( $destinationFolder, $destinationFolder2 ) ){
								unlink( $destinationFolder );
							}
							
							echo "\n\nUpdate Successful: " . $version . "\n\n";
							
							run_in_background("open_backup_directory", 0, array( "text" => realpath( $this->class_settings[ 'calling_page' ] . 'tmp/version-new-updates' ) ) );
						}else{
							echo "\n\nERROR: Unable to save zipped file \n\n";
						}
						
					}else{
						echo "\n\nERROR: Unable to Retrieve Cache \n\n";
					}
				}
			break;
			case "save_export_updated_files":
				if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
					$major = isset( $_POST["major"] )?$_POST["major"]:0;
					$start_date = convert_date_to_timestamp( $_POST["start_date"], 4 );
					
					// print_r( $_POST["start_date"] . '<br>' );
					// print_r( $start_date );exit;
					$settings = array(
						'cache_key' => "export_files",
						'permanent' => true,
						'cache_values' => array( "start_date" => $start_date, "major" => $major ),
					);
					set_cache_for_special_values( $settings );
					
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => "save_export_updated_files_background", 
							"user_id" => 1, 
							"reference" => "export_files",
							"show_window" => 1,
						) 
					);
					
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'cAudit.php';
					$err->method_in_class_that_triggered_error = '_sync_progress';
					$err->additional_details_of_error = '<h4>Export Operation in Progress</h4><p>Please check console window for progress...</p>';
					$return = $err->error();
					
					$return["status"] = "new-status";
					$return["html_replacement_selector"] = $handle;
					$return["html_replacement"] = $return["html"];
					unset( $return["html"] );
					
					return $return;
					
				}else{
					$err_title = "Invalid Date";
					$err_msg = "Please try again";
				}
				
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
				return $err->error();
			break;
			default:
				$form = $this->_generate_new_data_capture_form();
				
				$exports = array();
				if( is_dir( $this->class_settings[ 'calling_page' ] . 'tmp/version-history/' ) ){
					//3. Open & Read all files in directory
					$cdir =  $this->class_settings[ 'calling_page' ] . 'tmp/version-history/';
					$tcount = 10;
					$count = 0;
					
					foreach( glob( $cdir . "*.zip" ) as $filename ) {
						
						if ( is_file( $filename ) ) {
							
							$p = pathinfo( $filename );
							$p["full_name"] = $filename;
							$exports[ filemtime( $filename ) ] = $p;
							
						}
					}
					
					krsort( $exports );
					
					if( count( $exports ) > $tcount ){
						foreach( $exports as $pk => $pv ){
							++$count;
							if( $count > $tcount ){
								unlink( $pv["full_name"] );
								unset( $exports[ $pk ] );
							}
						}
					}
				}
				
				$this->class_settings[ 'data' ][ 'title' ] = 'Current Version: ' . get_app_version( $this->class_settings["calling_page"] );
				$this->class_settings[ 'data' ][ 'versions' ] = $exports;
				$this->class_settings[ 'data' ][ 'upload_form' ] = $form["html"];
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-application-update.php' );
				$html = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $html,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ) 
					//'recreateDataTables', 'update_column_view_state', 
				);
			break;
			}
			
			if( $html ){
				if( $html_q ){
					$html = '<h4>Database Queries</h4><div contenteditable="true" style="background:#222; color:#eee; overflow:auto; max-height:300px;">'. $html_q .'</div>' . $html;
				}
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $html,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js 
				);
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
		}
		
		private function _upgrade_database_from_database(){
			
			
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle =  "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			switch ( $action_to_perform ){
			case 'upgrade_database_from_database':
				
				if( isset( $_POST["new_database"] ) && $_POST["new_database"] ){
					$uts = array();
					
					if( isset( $_POST["utables"] ) && is_array( $_POST["utables"] ) && ! empty( $_POST["utables"] ) ){
						
						foreach( $_POST["utables"] as $ut ){
							$tbd = explode( ':::' , $ut );
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
							
							if( $tb ){
								$uts['tables'][ $tb ] = 1;
							}
						}
						
					}
					
					$settings = array(
						'cache_key' => "db-upgrade-" . $_POST["new_database"],
						'permanent' => true,
						'cache_values' => $uts,
					);
					set_cache_for_special_values( $settings );
					
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => "upgrade_database_from_database_background", 
							"user_id" => 1, 
							"reference" => $_POST["new_database"],
						) 
					);
					
					$err = new cError('010011');
					$err->additional_details_of_error = '<h4>Background Processing Launched</h4><p>Please wait...</p>';
					
					
					$pr = get_project_data();
					$err->additional_details_of_error .= '<a href="'.$pr["domain_name"].'classes/update-queries.html" target="_blank" class="btn btn-default">Queries</a>';
					$err->additional_details_of_error .= '<a href="'.$pr["domain_name"].'classes/update-log.html" target="_blank" class="btn btn-default">Log</a>';
				}else{
					$err = new cError('010014');
					$err->additional_details_of_error = '<h4>No Database Specified</h4><p>Background process was not launched</p>';
				}
				
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				
				$return = $err->error();
				$return["html_replacement"] = $return["html"];
				$return["html_replacement_selector"] = $handle;
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				
				return $return;
			break;
			case "move_data_from_old_fields_to_new_prompt":
				
				$this->class_settings[ 'data' ]["tables"] = $this->_get_project_classes( array( "for_data_movement" => 1 ) );
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/data-movement-form.php' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new' ),
				);
			break;
			case "upgrade_database_prompt":
				
				$query = "SELECT schema_name FROM information_schema.schemata WHERE schema_name NOT IN ('information_schema', 'mysql', 'performance_schema', 'phpmyadmin' )";
				$query_settings = array(
					'database' => $this->class_settings["database_name"] ,
					'connect' => $this->class_settings["database_connection"] ,
					'query' => $query,
					'query_type' => 'DESCRIBE',
					'set_memcache' => 0,
					'tables' => array(),
				);
				$dbs = execute_sql_query($query_settings);
				
				$this->class_settings[ 'data' ]["current_database"] = $this->class_settings["database_name"];
				$this->class_settings[ 'data' ]["databases"] = $dbs;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/database-upgrade-form.php' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new' ),
				);
			break;
			case 'db_upgrade':
				$this->upgrade_database = $this->class_settings["database_name"];
				$running_in_background = 1;
			break;
			default:
				$running_in_background = 0;
				if( $this->class_settings['running_in_background'] && $this->class_settings['running_in_background'] ){
					$running_in_background = ( isset( $_POST['id'] ) && $_POST['id'] )?$_POST['id']:0;
					$this->upgrade_database = $running_in_background;
				}
				
				if( ! $running_in_background ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>No Database Specified</h4>';
					return $err->error();
				}
			break;
			}
			
			$code = '010014';
			$msg = '<h4>Unknown Error</h4><p>Please contact support team</p>';
			
			$database_name2 = $this->upgrade_database;
			//$database_name2 = $this->class_settings["database_name"];
			
			$settings = array(
				'cache_key' => "db-upgrade-" . $database_name2,
				'permanent' => true,
			);
			$uts = get_cache_for_special_values( $settings );
			clear_cache_for_special_values( $settings );
			
			set_time_limit(0);
			
			$tables = array( "sales_items" );
			
			$query = "SELECT table_name FROM information_schema.tables where table_schema='".$database_name2."'";
			
			$query_settings = array(
				'database' => $this->class_settings["database_name"] ,
				'connect' => $this->class_settings["database_connection"] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 0,
				'tables' => array(),
			);
			$tables = execute_sql_query($query_settings);
			
			$query = "SELECT table_name FROM information_schema.tables where table_schema='".$this->class_settings[ 'database_name' ]."'";
			
			$query_settings = array(
				'database' => $this->class_settings["database_name"] ,
				'connect' => $this->class_settings["database_connection"] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 0,
				'tables' => array(),
			);
			$tables2 = execute_sql_query($query_settings);
			// print_r( $tables );exit;
			
			$queries = '';
			$html = '';
			if( ! empty( $tables ) ){
				$tbss = array_column( $tables2, 'table_name' );
				foreach( $tables as $stable ){
					
					$table = $stable["table_name"];
					if( isset( $uts["tables"] ) && ! empty( $uts["tables"] ) ){
						if( ! isset( $uts["tables"][ $table ] ) ){
							continue;
						}
					}
					
					if( $running_in_background ){
						$text = "Checking " . $table;
						echo $text . " \n\n ";
					}
					
					$r2 = array();
					if( in_array( $table, $tbss ) ){
						$query = "DESCRIBE `".$this->class_settings["database_name"]."`.`".$table."` ";
						
						$query_settings = array(
							'database' => $database_name2,
							'connect' => $this->class_settings["database_connection"] ,
							'query' => $query,
							'query_type' => 'DESCRIBE',
							'set_memcache' => 0,
							'tables' => array( $table ),
						);
						$r2 = execute_sql_query($query_settings);
					}
					
					$query = "DESCRIBE `".$database_name2."`.`".$table."` ";
					$query_settings["query"] = $query;
					$r1 = execute_sql_query($query_settings);
					
					$after = array();
					$s2 = array();
					$after_field = array();
					
					$database_fields = "";
					
					//print_r($r1); exit;
					//ALTER TABLE `users` CHANGE `device_id` `device_id` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
					//add default null
					/* foreach( $r1 as $r2 ){
						if( $r2["Null"] != "YES" && ! in_array( $r2["Field"], array("id", "serial_num") ) ){
							if( strpos( $r2["Type"], "var" ) > -1 || strpos( $r2["Type"], "text" ) > -1 ){
								$q = "ALTER TABLE `".$table."` CHANGE `".$r2["Field"]."` `".$r2["Field"]."` ".$r2["Type"]." CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
							}else{
								$q = "ALTER TABLE `".$table."` CHANGE `".$r2["Field"]."` `".$r2["Field"]."` ".$r2["Type"]." NULL DEFAULT NULL;";
							}
							$html .= $q . "<br /><br />";
							$queries .= $q . "\n\n";
						}
					}
					continue;  */
					//add default null
					
					//transfer data
					/* $ar = array_column( array_values( $r1 ), 'Field' );
					if( in_array( 'creation_date', $ar ) ){
						//$q = "SELECT @i :=0; UPDATE `".$table."` SET serial_num = ( SELECT @i := @i +1 ); ";
						$q = "INSERT INTO `correct_hyella_db`.`".$table."` SELECT * FROM `newadonai`.`".$table."`; ";
						
						//$q = "ALTER TABLE `".$table."` ADD `created_source` varchar(100) DEFAULT NULL AFTER `creator_role`; ";
						//$q .= "ALTER TABLE `".$table."` ADD `modified_source` varchar(100) DEFAULT NULL AFTER `creation_date`; ";
						$html .= $q . "<br /><br />";
						$queries .= $q . "\n\n";
					}
					continue; */
					//transfer data
					
					//upgrade all tables
					/* $ar = array_column( array_values( $r1 ), 'Field' );
					if( ! in_array( 'created_source', $ar ) ){
						$q = "ALTER TABLE `".$table."` CHANGE `id` `id` VARCHAR(55) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL; ";
						
						//$q = "ALTER TABLE `".$table."` ADD `created_source` varchar(100) DEFAULT NULL AFTER `creator_role`; ";
						//$q .= "ALTER TABLE `".$table."` ADD `modified_source` varchar(100) DEFAULT NULL AFTER `creation_date`; ";
						$html .= $q . "<br /><br />";
						$queries .= $q . "\n\n";
					}
					continue; */
					//upgrade all tables
					
					
					/*
					if( ! empty( $r2 ) ){
						foreach( $r2 as $v1 ){
							//print_r( $v1 );
							switch( $v1["Type"] ){
							case "float":
							case "decimal(15,2)":
							case "decimal(15,4)":
							case "decimal(12,4)":
							case "decimal(12,2)":
							//case "decimal(20,4)":
								$text = "FOUND " . $table . " " . $v1["Type"];
								echo $text . " \n\n ";
								
								$q = "ALTER TABLE `".$this->class_settings["database_name"]."`.`".$table."` CHANGE `".$v1["Field"]."` `".$v1["Field"]."` decimal(20,4) ";
								$query_settings = array(
									'database' => $this->class_settings["database_name"] ,
									'connect' => $this->class_settings["database_connection"] ,
									'query' => $q,
									'query_type' => 'EXECUTE',
									'set_memcache' => 0,
									'tables' => array( $table ),
								);
								execute_sql_query($query_settings);
							break;
							}
						}
						
					}
					*/
					
					if( empty( $r2 ) ){
						if( $running_in_background ){
							$text = "Creating " . $table;
							echo $text . " \n\n ";
						}
						
						//create table
						$query = "SHOW CREATE TABLE `" . $database_name2 . "`.`".$table."` ";
						$query_settings = array(
							'database' => $database_name2 ,
							'connect' => $this->class_settings["database_connection"] ,
							'query' => $query,
							'query_type' => 'DESCRIBE',
							'set_memcache' => 0,
							'tables' => array( $table ),
						);
						$sql = execute_sql_query($query_settings);
						
						if( isset( $sql[0]["Create Table"] ) ){
							
							$q = str_replace( "CREATE TABLE `".$table."`", "CREATE TABLE `" . $this->class_settings['database_name'] . "`.`".$table."`", $sql[0]["Create Table"] );
							$queries .= $q . "; <br /><br />";
							
							$query_settings = array(
								'database' => $this->class_settings["database_name"] ,
								'connect' => $this->class_settings["database_connection"] ,
								'query' => $q,
								'query_type' => 'EXECUTE',
								'set_memcache' => 0,
								'tables' => array( $table ),
							);
							execute_sql_query($query_settings);
							
							$html .= "<li>Created new table <strong>".$table."</strong></li>";
							
							continue;
						}else{
							
							foreach( $r1 as $v1 ){
								$field_key = $v1["Field"];
								$data_type = $v1["Type"];
								
								if( $database_fields )$database_fields .= ", `".$field_key."` ".$data_type." DEFAULT NULL ";
								else $database_fields = " `".$field_key."` ".$data_type." DEFAULT NULL ";
							}
							
							$q = " CREATE TABLE `" . $this->class_settings['database_name'] . "`.`".$table."` ( ".$database_fields." ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ";
							$queries .= $q . "; <br /><br />";
							
							$query_settings = array(
								'database' => $this->class_settings["database_name"] ,
								'connect' => $this->class_settings["database_connection"] ,
								'query' => $q,
								'query_type' => 'EXECUTE',
								'set_memcache' => 0,
								'tables' => array( $table ),
							);
							execute_sql_query($query_settings);
							
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$table."` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ";
							$queries .= $q . "; <br /><br />";
							
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$table."` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ";
							$queries .= $q . "; <br /><br />";
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							
							$html .= "<li>Created new table <strong>".$table."</strong> with failover</li>";
							continue;
						}
						   
					}
					
					foreach( $r2 as $v1 ){
						//print_r( $v1 );
						$s2[ $v1["Field"] ] = $v1;
					}
					
					$after_field = array();
					foreach( $r1 as $v1 ){
						$after[ $v1["Field"] ] = $after_field;
						$after_field = $v1;
					}
					
					foreach( $r1 as $v1 ){
						$q = '';
						if( isset( $s2[ $v1["Field"] ] ) ){
							if( $s2[ $v1["Field"] ]["Type"] != $v1["Type"] ){
								$q = "ALTER TABLE `".$this->class_settings["database_name"]."`.`".$table."` CHANGE `".$v1["Field"]."` `".$v1["Field"]."` ".$v1["Type"]." ";
								$queries .= $q . "; <br /><br />";
								
								//echo $q; exit;
								$html .= "<li>Modify table field <strong>".$v1["Field"]."</strong> from <strong>".$s2[ $v1["Field"] ]["Type"]."</strong> to <strong>".$v1["Type"]."</strong> in table <strong>".$table."</strong></li>";
							}
						}else{
							if( isset( $after[ $v1["Field"] ]["Field"] ) ){
								$q = "ALTER TABLE `".$this->class_settings["database_name"]."`.`".$table."` ADD `".$v1["Field"]."` ".$v1["Type"]." NULL AFTER `".$after[ $v1["Field"] ]["Field"]."`";
								$queries .= $q . "; <br /><br />";
							}
							$html .= "<li>Add new field <strong>".$v1["Field"]."</strong> to table <strong>".$table."</strong></li>";
						}
						
						if( $q ){
							if( $running_in_background ){
								$text = "Altering " . $table;
								echo $text . " \n\n ";
							}
							
							$query_settings = array(
								'database' => $this->class_settings["database_name"] ,
								'connect' => $this->class_settings["database_connection"] ,
								'query' => $q,
								'query_type' => 'EXECUTE',
								'set_memcache' => 0,
								'tables' => array( $table ),
							);
							execute_sql_query( $query_settings );
						}
						//echo $q;
					}
					
				}
				//exit;
				$code = '010011';
				
				if( $html ){
					$msg = '<h4>Successful Database Upgrade</h4>';
				}else{
					$msg = '<h4>No Pending Updates</h4>';
				}
				
			}else{
				$msg = '<h4>Table List Not Found</h4><p>Unable to retrieve list of tables from the information_schema</p>';
			}
			
			$err = new cError($code);
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'cAudit.php';
			$err->method_in_class_that_triggered_error = '_sync_progress';
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			
			if( $html ){
				file_put_contents( dirname( __FILE__ ) . '/update-queries.html', date("d-M-Y H:i") . "<br /><br />" . $queries );
				file_put_contents( dirname( __FILE__ ) . '/update-log.html', date("d-M-Y H:i") . "<br /><br /><ol>$html</ol>" );
				
				unset( $return["html"] );
				
				$return['status'] = "new-status";
				$return['html_replacement_selector'] = "#dash-board-main-content-area";
				$return['html_replacement'] = '<h1>Queries</h1><pre style="max-height:250px; overflow:auto; background:#000; color:#fff;">'.$queries.'</pre><div style="max-height:250px; overflow:auto;"><ol>'.$html.'</ol></div>';
			}
			
			return $return;
		}
		
		private function _refresh_cache_via_background(){
			
			$check_action = '';
			$update_msg = '';
			
			$selector = "#dash-board-main-content-area";
			$filename = 'start-cache-refresh.php';
			
			switch( $this->class_settings["action_to_perform"] ){
			case "background_app_update":
				run_in_background( "background_app_update" );
				
				$data["refreshing"] = 1;
				$data["data"] = array();
				
				$settings = array(
					'cache_key' => "refreshing_cache",
					'permanent' => true,
					'cache_values' => $data,
				);
				set_cache_for_special_values( $settings );
				
				$selector = "#updates-container";
				$check_action = 'background_app_update_check';
				
				$filename = 'start-app-update.php';
			break;
			case "refresh_cache_via_background":
				run_in_background( "refresh_cache_in_background" );
				
				$data["refreshing"] = 1;
				$data["data"] = array();
				
				$settings = array(
					'cache_key' => "refreshing_cache",
					'permanent' => true,
					'cache_values' => $data,
				);
				set_cache_for_special_values( $settings );
				
				$check_action = 'refresh_cache_via_background_check';
			break;
			case "background_app_update_check":
			case "refresh_cache_via_background_check":
				$check_action = $this->class_settings["action_to_perform"];
				sleep(10);
			break;
			}
			
			$rb = "";
			switch( $this->class_settings["action_to_perform"] ){
			case "background_app_update":
			case "background_app_update_check":
				$update_msg = 'App Update';
				
				$back_url = "./";
				if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
					if( file_exists( "../../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
						$back_url = "../sign-in/";
					}
				}
				$rb = "<li><a href='".$back_url."' class='btn red'>Return back to Application</a></li>";
				
			break;
			case "refresh_cache_via_background_check":
			case "refresh_cache_via_background":
				$update_msg = 'Cache Refresh';
			break;
			}
			
			$settings = array(
				'cache_key' => "refreshing_cache",
				'permanent' => true,
			);
			$value = get_cache_for_special_values( $settings );
			
			if( isset( $value["refreshing"] ) && isset( $value["data"] ) && $value["refreshing"] ){
				
				if( ! empty( $value["data"] ) ){
					foreach( $value["data"] as $key => $val ){
						switch( $key ){
						case "html_replacement":
						case "html_replacement_selector":
						case "html_prepend":
						case "html_prepend_selector":
							$return[ $key ] = $val;
						break;
						}
					}
				}else{
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
					
					$return["html_replacement"] = $this->_get_html_view();
					$return["html_replacement_selector"] = $selector;
					$return["javascript_functions"] = array( "set_function_click_event" );
				}
				
				$return['status'] = 'new-status';
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "check";
				$return['action'] = '?action=audit&todo='.$check_action;
				
			}else{
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>'.$update_msg.' Complete</h4>';
				$return = $err->error();
				unset( $return["html"] );
				
				$return['status'] = "new-status";
				$return['html_prepend_selector'] = "#update-progress-container";
				$return['html_prepend'] = "<li><strong>".$update_msg." Complete!!!</strong></li>" . $rb;
				
			}
			
			return $return;
		}
		
		private function _refresh_cache_in_background(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'background_app_update_in_background':
				echo "COMMENCING HYELLA APPLICATION UPDATE \n\n";
				echo "PLEASE DO NOT CLOSE THIS WINDOW OR TURN OFF YOUR SYSTEM UNTIL UPDATE IS COMPLETE \n\n";
				echo "THIS WINDOW WILL CLOSE AUTOMATICALLY WHEN UPDATE IS COMPLETE \n\n";
			break;
			default:
				echo "REFRESHING CACHE \n\n";
				echo "PLEASE DO NOT CLOSE THIS WINDOW OR TURN OFF YOUR SYSTEM UNTIL CACHE HAS BEEN REFRESHED \n\n";
				echo "THIS WINDOW WILL CLOSE AUTOMATICALLY WHEN CACHE REFRESH IS COMPLETE \n\n";
			break;
			}
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$loop = true;
			while( $loop ){
				
				$this->class_settings["running_in_background"] = 1;
				
				switch( $action_to_perform ){
				case 'background_app_update_in_background':
					$this->class_settings["action_to_perform"] = 'app_update';
					$return = $this->_app_update();
				break;
				default:
					$return = $this->_refresh_cache();
				break;
				}
				
				//echo $action_to_perform . $this->class_settings["action_to_perform"] . "\n";
				if( isset( $return["html_replacement"] ) && $return["html_replacement"] ){
					echo trim( str_replace( "<br />", "\n", strip_tags( $return["html_replacement"], "br" ) ) ) . "\n";
					//echo strip_tags( str_replace(  $return["html_replacement"] ) ) . "\n";
				}
				
				if( isset( $return["html_prepend"] ) && $return["html_prepend"] ){
					echo trim( str_replace( "<br />", "\n", strip_tags( $return["html_prepend"], "br" ) ) ) . "\n";
					//echo strip_tags( str_replace( "<br />", "\n", $return["html_prepend"] ) ) . "\n";
				}
				
				
				$data["refreshing"] = 1;
				$data["data"] = $return;
				
				$settings = array(
					'cache_key' => "refreshing_cache",
					'permanent' => true,
					'cache_values' => $data,
				);
				
				set_cache_for_special_values( $settings );
				
				if( isset( $return['re_process'] ) && $return['re_process'] ){
					
					if( isset( $return['id'] ) ){
						$_POST["id"] = $return['id'];
					}
					
					if( isset( $return['mod'] ) ){
						$_POST["mod"] = $return['mod'];
					}
					
					if( isset( $return['action'] ) && $return['action'] ){
						
						switch( $return['action'] ){
						case '?action=audit&todo=refresh_cache':
							$action_to_perform = '?action=audit&todo=refresh_cache';
						break;
						}
						
						$a = str_replace( "?", "", $return['action'] );
						$as = explode( "&", $a );
						foreach( $as as $aa ){
							$aas = explode( "=", $aa );
							if( isset( $aas[0] ) && isset( $aas[1] ) && $aas[0] == "todo" ){
								$this->class_settings["action_to_perform"] = $aas[1];
							}
						}
					}
					
				}else{
					$loop = false;
				}
			}
			
			$settings = array(
				'cache_key' => "refreshing_cache",
				'permanent' => true,
			);
			clear_cache_for_special_values( $settings );
			
		}
		
		private function _app_update_automatic(){
			//check if update has already occurred today
			if( check_for_last_automatic_update() ){
				analytics_update( array( "event" => 'autoAppUpdate' ) );
				$this->class_settings["action_to_perform"] = 'app_update';
				return $this->_app_update();
			}
			
			//notice
			$err = new cError('010011');
			$err->action_to_perform = 'notify';
			$err->html_format = 2;
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = '<h4>Automatic Update Already Performed</h4>Please wait for 24hours before trying again';
			$return = $err->error();
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			$return["redirect_url"] = get_default_location();
			
			return $return;
		}
		
		private function _background_data_upload_only(){
			//1. Check for Internet Access
			$this->class_settings["skip_data_download"] = 1;
			
			//marker to determine if background processor is running
			$settings = array(
				'cache_key' => "bg-processor",
				'cache_values' => 1,
				'cache_time' => 'mini-time',
			);
			set_cache_for_special_values( $settings );
			
			if( ! get_update_manifest() ){
				return 0;
			}
			
			$_POST["id"] = "";
			$return = $this->_app_update();
			$error = 1;
			if( isset( $return['id'] ) && $return['id'] ){
				//2. Check App Status
				$_POST["id"] = $return['id'];
				$return = $this->_app_update();
				
				$loop = 1;
				while( $loop ){
					if( isset( $return['id'] ) && $return['id'] ){
						analytics_update( array( "event" => 'instantDataUpload' ) );
						
						//3. Push Data
						$_POST["id"] = $return['id'];
						$return = $this->_app_update();
						$error = 0;
						if( isset( $return['id'] ) && $return['id'] != "start_update" ){
							$loop = 0;
						}
						
					}else{
						$loop = 0;
					}
				}
				
				if( ! $error ){
					$err = new cError();
					$err->action_to_perform = 'notify';
					$err->push_notification_data = array( 
						"code" => '010011',
						"html" => '<h4>Successful Data Upload</h4><p>Your changes have been successfully uploaded to our cloud servers</p>',
					);
					return $err->error();
				}
				return $return;
			}
		}
		
		private function _hide_print_dialog_box(){
			$settings = array(
				'cache_key' => "hidden-print-dialog",
				'permanent' => true,
			);
			$value = get_cache_for_special_values( $settings );
			
			$msg = '<h4>Unknown</h4>Please try again';
			$caption = '';
			$status = '';
			if( $value ){
				$value = 0;
				$msg = '<h4>Enabled Print Dialog Box</h4><p>Print Dialog Box successfully enabled</p><p><strong>You must restart the Application for your changes to take effect</strong></p>';
				$caption = 'Hide Print Dialog Box';
				$status = 'ENABLED';
				
				clear_cache_for_special_values( $settings );
			}else{
				$value = 1;
				$msg = '<h4>Hidden Print Dialog Box</h4><p>Print Dialog Box successfully hidden</p><p><strong>You must restart the Application for your changes to take effect</strong></p>';
				$caption = 'Enable Print Dialog Box';
				$status = 'HIDDEN';
				
				$settings["cache_values"] = $value;
				set_cache_for_special_values( $settings );
			}
			
			$err = new cError('010011');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			
			unset( $return["html"] );
			$return["status"] = 'new-status';
			$return["html_replacement_selector"] = '#print-dialog-box-caption';
			$return["html_replacement"] = $caption;
			
			$return["html_replacement_selector_one"] = '#print-dialog-box-status';
			$return["html_replacement_one"] = $status;
			
			return $return;
		}
		
		private function _analyze_audit_trail(){
			$error_msg = '';
			
			$rtypes = get_audit_trail_report_types();
			$atypes = get_audit_trail_report_actions();
			$ftypes = get_audit_trail_report_format();
			
			$report_type = '';
			$title = '';
			$t_format = '';
			$format = '';
			
			
			$raction = isset( $_POST["report_action"] )?$_POST["report_action"]:'';
			$t_action = isset( $atypes[ $raction ] )?( ' ['.$atypes[ $raction ].']' ):'';
			
			if( isset( $_POST["report_type"] ) && isset( $rtypes[ $_POST["report_type"] ] ) ){
				$report_type = $_POST["report_type"];
				$title = $rtypes[ $_POST["report_type"] ];
				
			}else{
				$error_msg = '<h4>Invalid Report Type</h4>';
			}
			
			if( isset( $_POST["report_format"] ) && isset( $ftypes[ $_POST["report_format"] ] ) ){
				
				$format = $_POST["report_format"];
				$t_format = $ftypes[ $_POST["report_format"] ];
				
			}else{
				$error_msg = '<h4>Invalid Report Format</h4>';
			}
			
			$start = 0;
			$end = 0;
			
			if( ! $error_msg ){
				if( isset( $_POST["start_date"] ) && $_POST["start_date"] && isset( $_POST["end_date"] ) && $_POST["end_date"] ){
					$start = convert_date_to_timestamp( $_POST["start_date"], 1 );
					$end = convert_date_to_timestamp( $_POST["end_date"], 2 );
					
					if( $end >= $start ){
						
						$interval = $end - $start;
						if( $interval > 0 && $interval < (366 * 24 * 3600) ){
							
						}else{
							$error_msg = '<h4>Restricted Access</h4><p>The time interval between the start and end date must be less than 366 days</p>';
						}
						
					}else{
						$error_msg = '<h4>Invalid End Date</h4><p>The end date must be greater than or equal to the start date</p>';
					}
					
				}else{
					$error_msg = '<h4>Invalid Start / End Date</h4><p>Please specify a valid start & end dates</p>';
				}
			}
			
			$index_data = array();
			$days_data = array();
			$monthly_data = array();
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4><p>Please contact the system administrator</p>';
			}
			
			$returning_html_data = '';
			
			if( $start && $end ){
				$duration = ( $end - $start ) / ( 3600 * 24 );
				
				//constraint on interval
				switch( $report_type ){
				case "monthly":
					if( $duration >= 62 ){
						$error_msg = '<h4>Time interval is too large</h4><p>The time interval for <strong>'.$title.'</strong> must be less than 62 days</p>';
						$end = 0;
						$start = 0;
					}
				break;
				case "daily":
				default:
					
					if( $duration > 31 ){
						$error_msg = '<h4>Time interval is too large</h4><p>The time interval for <strong>'.$title.'</strong> must be less than 32 days</p>';
						$end = 0;
						$start = 0;
					}
					
				break;
				}
			}
			
			if( $start && $end ){
				set_time_limit(0); //Unlimited max execution time
				$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
				$hour = 3600;
				$use_index = '';
				if( defined("NWP_INDEX_AUDIT_TRAIL") && NWP_INDEX_AUDIT_TRAIL ){
					$hour *= 24;
					$use_index = NWP_INDEX_AUDIT_TRAIL;
				}
				
				$count = 0;
				
				$error_msg = '';
				for( $i = $start; $i <= $end; $i += $hour ){
					
					$month = date("M-Y", $i );
					$day = date("d-M-Y", $i );
					
					//$returning_html_data .= $day .' == ' . $i. '<br />';
					$trail_types = $this->trail_types;
					
					switch( $raction ){
					case "page_view":
					case "read":
					case "login":
					case "console":
						$trail_types = array( $raction );
					break;
					}
					
					if( $use_index && is_dir( $this->class_settings["calling_page"] . 'tmp/'. $use_index ) ){
						$t1 = array();
						$f1 = $this->class_settings["calling_page"] . 'tmp/'.$use_index.'/'.$i.'.json';
						if( file_exists( $f1 ) ){
							$t1 = json_decode( file_get_contents( $f1 ) , true );
						}
						
						if( is_array( $t1 ) && ! empty( $t1 ) ){
							foreach( $t1 as $s1 ){
								
								$trail = array();
								$filename = $this->class_settings["calling_page"] . 'tmp/'. $s1;
								if( file_exists( $filename ) ){
									$trail = json_decode( file_get_contents( $filename ) , true );
								}
								
								if( is_array( $trail ) && ! empty( $trail ) ){
									foreach( $trail as $kx => $sval ){
										
										++$count;
										$sval["id"] = $kx;
										
										if( isset( $sval["parameters"]["email"] ) && $sval["parameters"]["email"] ){
											$sval["user_id"] = $sval["user"];
											$sval["user"] = $sval["parameters"]["email"];
										}
										
										if( isset( $sval["user"] ) ){
											if( $raction ){
												if( ! isset( $sval["user_action"] ) ){
													continue;
												}
												if( isset( $sval["user_action"] ) && $raction != $sval["user_action"] ){
													continue;
												}
											}
											
											switch( $report_type ){
											case "daily":
												
												$sval["file_dir"] = $this->trail;
												$sval["file_name"] = $filename;
												
												$id = $day .'-'. $count;
												$index_data[ $id ] = $sval;
												$ii = date("H:i", $i );
												
												switch( $format ){
												case "day":
													$days_data[ $day ]["day"][ $ii ][ $id ] = $id;
												break;
												case "user":
													$days_data[ $day ]["user"][ $sval["user"] ][ $ii ][ $id ] = $id;
												break;
												case "action":
													$days_data[ $day ]["action"][ $sval["user_action"] ][ $ii ][ $id ] = $id;
												break;
												}
												
											break;
											case "monthly":
												$ii = date("jS", $i );
												
												switch( $format ){
												case "day":
												case "user":
													if( ! isset( $days_data[ $month ]["user"][ $sval["user"] ][ $ii ] ) ){
														$days_data[ $month ]["user"][ $sval["user"] ][ $ii ] = 0;
													}
													
													$days_data[ $month ]["user"][ $sval["user"] ][ $ii ] += 1;
												break;
												case "action":
													
													if( ! isset( $days_data[ $month ]["user_action"][ $sval["user_action"] ][ $ii ] ) ){
														$days_data[ $month ]["user_action"][ $sval["user_action"] ][ $ii ] = 0;
													}
													
													$days_data[ $month ]["user_action"][ $sval["user_action"] ][ $ii ] += 1;
													
												break;
												}
												
											break;
											}
											
										}
										
									}
								}
							}
						}
						
					}else{
						foreach( $trail_types as $tr ){
							
							$trail = array();
							$filename = $this->class_settings["calling_page"] . 'tmp/'.$tr.'/'.$i.'.json';
							if( file_exists( $filename ) ){
								$trail = json_decode( file_get_contents( $filename ) , true );
							}
							
							if( is_array( $trail ) && ! empty( $trail ) ){
								foreach( $trail as $kx => $sval ){
									
									++$count;
									$sval["id"] = $kx;
									
									if( isset( $sval["user"] ) ){
										if( $raction ){
											if( ! isset( $sval["user_action"] ) ){
												continue;
											}
											if( isset( $sval["user_action"] ) && $raction != $sval["user_action"] ){
												continue;
											}
										}
										
										switch( $report_type ){
										case "daily":
											
											$sval["file_dir"] = $this->trail;
											$sval["file_name"] = $filename;
											
											$id = $day .'-'. $count;
											$index_data[ $id ] = $sval;
											$ii = date("H:i", $i );
											
											switch( $format ){
											case "day":
												$days_data[ $day ]["day"][ $ii ][ $id ] = $id;
											break;
											case "user":
												$days_data[ $day ]["user"][ $sval["user"] ][ $ii ][ $id ] = $id;
											break;
											case "action":
												$days_data[ $day ]["action"][ $sval["user_action"] ][ $ii ][ $id ] = $id;
											break;
											}
											
										break;
										case "monthly":
											$ii = date("jS", $i );
											
											switch( $format ){
											case "day":
											case "user":
												if( ! isset( $days_data[ $month ]["user"][ $sval["user"] ][ $ii ] ) ){
													$days_data[ $month ]["user"][ $sval["user"] ][ $ii ] = 0;
												}
												
												$days_data[ $month ]["user"][ $sval["user"] ][ $ii ] += 1;
											break;
											case "action":
												
												if( ! isset( $days_data[ $month ]["user_action"][ $sval["user_action"] ][ $ii ] ) ){
													$days_data[ $month ]["user_action"][ $sval["user_action"] ][ $ii ] = 0;
												}
												
												$days_data[ $month ]["user_action"][ $sval["user_action"] ][ $ii ] += 1;
												
											break;
											}
											
										break;
										}
										
									}
									
								}
							}
						}
					}
					
				}
				
				$filename = 'audit-trail-analysis-view.php';
				
				$e["report_type"] = $report_type;
				$e["data"] = $days_data;
				$e["title"] = $title . $t_action;
				$e["format"] = $format;
				$e["index"] = $index_data;
				$e["count"] = $count;
				$this->class_settings[ 'data' ] = $e;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => "#dash-board-main-content-area",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					//'javascript_functions' => $js,
				);
			}
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4><p>Please contact the system administrator</p>';
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			$err->html_format = 2;
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $error_msg;
			return $err->error();
			
		}
		
		private function _display_more_settings(){
			$returning_html_data = '';
			$filename = '';
			$handle = '#dash-board-main-content-area';
			
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$js = array( 'set_function_click_event' );
			$action_to_perform = $this->class_settings["action_to_perform"];
			$this->class_settings[ 'data' ][ 'action_to_perform' ] = $action_to_perform;
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$this->class_settings[ 'data' ][ 'id' ] = $_POST["id"];
			}
			
			switch( $action_to_perform ){
			case 'display_audit_trail_analysis_view':
				$filename = 'audit-trail-analysis';
				//$handle = "#page-sub-content";
				$js[] = 'prepare_new_record_form_new';
				$js[] = 'nwResizeWindow.resizeWindowHeight';
			break;
			case 'generate_audit_trail_analysis':
				//$filename = 'audit-trail-analysis-view.php';
				$handle = "#audit-trail-analysis-container";
				
				//$js[] = 'prepare_new_record_form_new';
				$r = $this->_analyze_audit_trail();
				
				if( isset( $r["html_replacement"] ) && $r["html_replacement"] ){
					$returning_html_data = $r["html_replacement"];
				}else{
					return $r;
				}
			break;
			case 'display_admin_panel_view':
				$title = 'Admin Control Panel';
				
				$filename = 'admin-panel-view.php';
				$this->class_settings[ 'data' ]["title"] = $title;
			break;
			case 'display_export_updated_files':
				$title = 'Export Updated Files';
				
				$filename = 'export-updated-files-form.php';
				$this->class_settings[ 'data' ]["title"] = $title;
				
				$js[] = 'prepare_new_record_form_new';
			break;
			case 'display_erd':
			case 'display_plugin_erd':
				$title = 'Data Model (ERD)';
				
				switch( $action_to_perform ){
				case 'display_plugin_erd':
					if( isset( $_POST["id"] ) && $_POST["id"] ){
						$plugin_name = $_POST["id"];
						
						$plugin = new cPlugin_class();
						$plugin->class_settings = $this->class_settings;
						$tables = $plugin->get_plugin_classes( array( "plugin" => $plugin_name ) );
						
					}else{
						$tables = '<h4>Unspecified Plugin</h4>';
					}
					
					
					if( ! is_array( $tables ) ){
						$this->class_settings[ 'data' ][ 'error' ] = $tables;
					}
					
					//$this->class_settings[ 'data' ][ 'hide_title' ] = 1;
				break;
				default:
					$tables = $this->_get_project_classes();
				break;
				}
				
				$filename = 'show-erd.php';
				$this->class_settings[ 'data' ]["title"] = $title;
				$this->class_settings[ 'data' ]["tables"] = $tables;
				
				$js[] = "nwResizeWindow.resizeWindowHeight";
			break;
			default:
				$filename = 'display-more-settings';
				
				$settings = array(
					'cache_key' => "hidden-print-dialog",
					'permanent' => true,
				);
				$value = get_cache_for_special_values( $settings );
				if( $value ){
					$this->class_settings[ 'data' ]["hide_print_dialog_box_caption"] = "Enable Print Dialog Box";
				}
			break;
			}
			
			if( $filename ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				$returning_html_data = $this->_get_html_view();
			}
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		public function _get_project_classes( $opt = array() ){
			$tables_group = array();
			$tables = array();
			$tables2 = array();
			
			if( isset( $GLOBALS["classes"][ 'ajax_request_processing_script' ] ) && is_array( $GLOBALS["classes"][ 'ajax_request_processing_script' ] ) && ! empty( $GLOBALS["classes"][ 'ajax_request_processing_script' ] ) ){
				
				$c = array();
				$clx = $GLOBALS["classes"][ 'ajax_request_processing_script' ];
				//print_r($clx); exit;
				$for_basic_crud = isset( $opt["for_basic_crud"] )?$opt["for_basic_crud"]:0;
				$group_plugin = isset( $opt["group_plugin"] )?$opt["group_plugin"]:0;
				$group_plugin2 = isset( $opt["group_plugin2"] )?$opt["group_plugin2"]:0;

				if( isset( $GLOBALS[ 'plugins' ] ) && is_array( $GLOBALS[ 'plugins' ] ) && ! empty( $GLOBALS[ 'plugins' ] ) ){
					$clx = array_merge( $clx, $GLOBALS[ 'plugins' ] );
				}
				
				foreach( $clx as $key => $val ){
					if( $val ){
						$b = explode("/", $val);
						
						$xc = count( $b ) - 1;
						if( isset( $b[ $xc ] ) ){
							
							$dir = isset( $b[ $xc - 1 ] )?$b[ $xc - 1 ]:'';
							$d = $b[ $xc ];

							$dir = isset( $b[ $xc - 1 ] ) ? $b[ $xc - 1 ] : '';
							
							$continue = 0;
							switch( $d ){
							case "AfricasTalkingGateway":
								$continue = 1;
							break;
							}
							
							if( isset( $opt["excluded_classes"] ) && isset( $opt["excluded_classes"][ $d ] ) ){
								continue;
							}
							
							if( ( isset( $opt["selected_class_only"] ) && ! isset( $opt["selected_class_only"][ $d ] ) ) ){
								continue;
							}
							
							if( $continue ){
								continue;
							}
							
							if( class_exists( $d ) ){
								$cl = new $d();
								
								if( isset( $cl->table_classes ) && ! empty( $cl->table_classes ) ){
									
									if( isset( $cl->ignore_table_list ) && $cl->ignore_table_list ){
										continue;
									}
									
									if( $for_basic_crud && isset( $cl->ignore_basic_crud ) && $cl->ignore_basic_crud ){
										continue;
									}
									
									$opt[ 'has_plugin' ] = 1;
									foreach( $cl->table_classes as $pc ){
										if( isset( $opt["excluded_plugin_classes"] ) && isset( $opt["excluded_plugin_classes"][ $pc['table_name'] ] ) ){
											continue;
										}

										//ignore specific plugins
										$ini = $cl->load_class( array( 'initialize' => 1, 'class' => array( $pc[ 'table_name' ] ) ) );
										/* $d = 'c'.ucwords( $pc[ 'table_name' ] );
										$clxx = new $d(); */
										if( isset( $ini[ $pc[ 'table_name' ] ] ) ){
											$clxx = $ini[ $pc[ 'table_name' ] ];

											$r = $this->_get_project_class( $clxx, $opt, $d );

											if( isset( $r[ 'key' ] ) && isset( $r[ 'value' ] ) ){
												$tables_group[ $cl->table_name ][ 'classes' ][ $r[ 'key' ] ] = $r[ 'value' ];
												
												$tables2[ 'type=plugin:::key='. $cl->table_name .':::value='. $r[ 'key' ] ] = $r[ 'value' ];
												$tables[ $r[ 'key' ] ] = $r[ 'value' ];
											}
										}
									}
									
									if( isset( $tables_group[ $cl->table_name ]["classes"] ) ){
										$tables_group[ $cl->table_name ][ 'label' ] = $cl->label;
										$tables_group[ $cl->table_name ][ 'type' ] = 'plugin';
									}
									
								}elseif( isset( $cl->table_fields ) && isset( $cl->table_name ) ){
									$opt[ 'has_plugin' ] = 0;
									$r = $this->_get_project_class( $cl, $opt, $d );
									
									$pkt = 'base';
									$pkc = '*base';
									if( isset( $cl->package_name ) && $cl->package_name ){
										$pkt = 'package';
										$pkc = $cl->package_name;
									}else if( $dir ){
										$pkt = $dir;
										$pkc = $dir;
									}
									$tables_group[ $pkc ][ 'label' ] = $pkc;
									$tables_group[ $pkc ][ 'type' ] = $pkt;
									
									if( isset( $r[ 'key' ] ) && isset( $r[ 'value' ] ) ){
										$tables[ $r[ 'key' ] ] = $r[ 'value' ];
										$tables2[ 'type='.$pkt.':::key='. $pkc .':::value='. $r[ 'key' ] ] = $r[ 'value' ];
										$tables_group[ $pkc ][ 'classes' ][ $r[ 'key' ] ] = $r[ 'value' ];
									}
									
								}
								
								unset( $cl );
							}
							
						}
						
					}
				}
				// print_r( $r );exit;
				
				if( ! empty( $tables ) ){
					ksort( $tables_group );
					ksort( $tables );
					ksort( $tables2 );
				}
				
			}
			
			if( $group_plugin2 ){
				return $tables2;
			}
			if( $group_plugin ){
				return $tables_group;
			}
			return $tables;
		}
		
		private function _get_project_class( $cl, $opt, $d ){
			
			$tables = array();
			$add = 1;
			$add_label = 1;
			$has_plugin = isset( $opt[ 'has_plugin' ] ) && $opt[ 'has_plugin' ] ? 1 : 0;

			if( ( isset( $opt["for_database_table_name"] ) && $opt["for_database_table_name"] ) ){
				$tables[ 'key' ] = $cl->table_name;
				$value = isset( $cl->label ) && $cl->label ? $cl->label : $d;

				if( isset( $opt[ 'return_pulgin_check' ] ) && $opt[ 'return_pulgin_check' ] ){
					$tables[ 'value' ][ 'has_plugin' ] = $has_plugin;
					$tables[ 'value' ][ 'value' ] = $value;
				}else{
					$tables[ 'value' ] = $value;
				}
			}else{
				
				if( isset( $opt["for_basic_crud"] ) && $opt["for_basic_crud"] ){
					$add_label = 0;
					$opt["no_labels"] = 1;
					
					if( ! ( isset( $cl->basic_data["access_to_crud"] ) && $cl->basic_data["access_to_crud"] ) ){
						$add = 0;
					}
				}
				
				$lb = array();
				if( ! ( isset( $opt["no_labels"] ) && $opt["no_labels"] ) ){
					if( function_exists( $cl->table_name ) ){
						$tx = $cl->table_name;
						if( $add_label ){
							$lb = $tx();
						}
					}
				}
				
				
				if( ( isset( $opt["no_fields"] ) && $opt["no_fields"] ) ){
					$cl->table_fields = array();
				}
				
				$to_add = array(
					"class" => $d,
					"label" => isset( $cl->label )?$cl->label:$d,
					"table_name" => $cl->table_name,
					"table_fields" => $cl->table_fields,
					"table_clone" => isset( $cl->table_clone )?$cl->table_clone:array(),
					"default_reference" => isset( $cl->default_reference )?$cl->default_reference:'',
					"table_labels" => $lb,
				);
				if( isset( $cl->basic_data["real_table"] ) && $cl->basic_data["real_table"] ){
					$to_add[ "real_table" ] = $cl->basic_data["real_table"];
				}
				
				if( isset( $opt["for_basic_crud"] ) && $opt["for_basic_crud"] &&  isset( $cl->basic_data ) && is_array( $cl->basic_data ) && ! empty( $cl->basic_data ) ){
					foreach( $cl->basic_data as $bk => $bd ){
						$to_add[$bk] = $bd;
					}
				}
				
				if( isset( $opt["for_data_movement"] ) && $opt["for_data_movement"] ){
					$add = 0;
					if( isset( $cl->old_table_fields ) && ! empty( $cl->old_table_fields ) ){
						$add = 1;
						$to_add["old_table_fields"] = $cl->old_table_fields;
					}
				}
				
				if( $add ){
					$tables[ 'key' ] = $cl->table_name;
					if( isset( $cl->plugin ) ){
						$to_add[ 'plugin' ] = $cl->plugin;
						$tables[ 'key' ] = $cl->plugin . '.' . $cl->table_name;
					}
					$tables[ 'value' ] = $to_add;
				
					if( isset( $opt[ 'return_pulgin_check' ] ) && $opt[ 'return_pulgin_check' ] ){
						$tables[ 'value' ][ 'has_plugin' ] = $has_plugin;
					}
				}
			}

			return $tables;
		}

		private function _confirm_empty_database(){
			$stage = "";
			if( isset( $_POST["id"] ) && $_POST["id"] )
				$stage = $_POST["id"];
			
			$handle2 = 'dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			
			$back_url = "./";
			if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
				if( file_exists( "../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
					$back_url = "../sign-in/";
				}
			}
			$rb = "<li><a href='".$back_url."' class='btn red'>Go Back</a></li>";
			
			switch( $stage ){
			case "empty_db":
				set_time_limit(0); //Unlimited max execution time
				$keys = $this->_get_database_tables();
				
				foreach( $keys as $table_name ){
					$query = "TRUNCATE `".$this->class_settings["database_name"]."`.`".$table_name."`";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 0,
						'tables' => array( $table_name ),
					);
					execute_sql_query($query_settings);
				}
				
				$return['status'] = "new-status";
				$return["html_prepend"] = $rb . "<li>Database Empty Operation Successful</li>";
				$return["html_prepend_selector"] = "#sync";
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['action'] = '?action=audit&todo=refresh_cache&html_replacement_selector=' . $handle2;
			break;
			case "export_db":
				$this->class_settings[ 'sync_action' ] = '?action=audit&todo=confirm_empty_database&html_replacement_selector=' . $handle2;
				$this->class_settings[ 'sync_id' ] = "export_db";
				$return = $this->_sync_progress();
				
				if( isset( $return["complete"] ) && $return["complete"] ){
					$return["html_prepend"] = "<li>Preparing to Start Empty Operation</li><li>Database Backup Successful</li>";
				}
				
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "empty_db";
				$return['action'] = '?action=audit&todo=confirm_empty_database&html_replacement_selector=' . $handle2;
			break;
			case "start_export_process":
				//run sync in background
				run_in_background( "bprocess", 0, 
					array( 
						"action" => $this->table_name, 
						"todo" => "sync",
						"user_id" => $this->class_settings["user_id"], 
						"reference" => 1,
					) 
				);
				//run_in_background( "start_sync" );
				
				//store variable for sync in progress
				$settings = array(
					'cache_key' => "synchronization",
					'cache_values' => 1,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				$this->class_settings[ 'sync_action' ] = '?action=audit&todo=confirm_empty_database&html_replacement_selector=' . $handle2;
				$this->class_settings[ 'sync_id' ] = "export_db";
				$return = $this->_sync_progress();
			break;
			default:
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "start_export_process";
				$return['action'] = '?action=audit&todo=confirm_empty_database&html_replacement_selector=' . $handle2;
				
				$return['html_replacement'] = "<ol id='sync' style='font-size:16px; margin:10px;'><li>Starting Request Processing. Please do not perform any action until this process is complete</li></ol>";
				$return['html_replacement_selector'] = "#confirm_empty_database";
			break;
			}
			
			return $return;
		}
		
		private function _get_database_tables(){
			$keys = array(
				"reports",
				"assets",
				"assets_category",
				"expenditure_payment",
				"expenditure",
				"discount",
				"category",
				"customers",
				"vendors",
				"expenditure",
				"inventory",
				"items",
				"notifications",
				"payment",
				"pay_row",
				"pay_roll_post",
				"production",
				"production_items",
				"sales",
				"sales_items",
				"transactions",
				"transactions_draft",
				"debit_and_credit",
				"debit_and_credit_draft",
				"pay_roll_auto_generate",
			);
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "hotel":
					$keys[] = "hotel_checkin";
					$keys[] = "hotel_room_checkin";
					$keys[] = "hotel_room_type_checkin";
				break;
				case "ngo":
					$keys[] = "widows";
					$keys[] = "medical_treatment";
					$keys[] = "scholarship";
					$keys[] = "events";
					$keys[] = "event_notes";
					$keys[] = "empowerment";
					$keys[] = "donations";
					$keys[] = "customer_call_log";
					$keys[] = "children";
				break;
				case "jewelry":
					$keys[] = "repairs";
					$keys[] = "appraisal";
					$keys[] = "appraised_items";
					$keys[] = "import_items";
					$keys[] = "items_raw_data_import";
					$keys[] = "customer_call_log";
					$keys[] = "customer_wish_list";
				break;
				}
			}
			
			return $keys;
		}
		
		private function _empty_database(){
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/confirm-empty-prompt.php' );
			$this->class_settings[ 'data' ]["keys"] = $this->_get_database_tables();
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'nwResizeWindow.resizeWindowHeight' )
			);
		}
		
		private function _refresh_cache(){
			$return = array();
			
			$stage = "";
			$action = "";
			if( isset( $_POST["id"] ) && $_POST["id"] && $_POST["id"] != '-' ){
				$stage = $_POST["id"];
				$action = "refresh_cache";
			}
			
			$mod = '';
			if( isset( $_POST["mod"] ) && $_POST["mod"] ){
				$mod = $_POST["mod"];
			}
			
			$running_in_background = 0;
			if( isset( $this->class_settings["running_in_background"] ) && $this->class_settings["running_in_background"] ){
				$running_in_background = 1;
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case "analyze_refresh_cache":
				$action = $this->class_settings['action_to_perform'];
			break;
			}
			
			$back_url = "./";
			if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
				if( file_exists( "../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
					$back_url = "../sign-in/";
				}
			}
			$rb = "<li><a href='".$back_url."' class='btn red'>Go Back</a></li>";
			
			$keys = get_cache_refresh_keys();
			
			if( defined("HYELLA_LYTICS_CONNECTED") ){
				$loop = 1;
				
				while( $loop ){
					if( $stage && ( isset(  $keys[ $stage ] ) ) ){
						switch( $stage ){
						case "sales":
						case "discount":
						case "grade_level":
						case "assets":
						case "production":
							$stage = $keys[ $stage ];
						break;
						default:
							$loop = 0;
						break;
						}
					}else{
						$loop = 0;
					}
				}
			}
			
			if( $stage && ! ( isset(  $keys[ $stage ] ) ) ){
				$action = "finish";
			}
				
			if( isset( $_SESSION["reports_server"] ) && $_SESSION["reports_server"] ){
				$rb = '';
			}
			switch( $action ){
			case "finish":
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = $rb . "<li><strong><i class='icon-check'></i> Cache Refresh Completed</strong></li>";
				
				if( $running_in_background ){
					echo strip_tags( $return["html_prepend"] ) . "\n\n";
				}
			break;
			case "refresh_cache":
				$next_table = $keys[ $stage ];
				$r_action = '?action=audit&todo=analyze_refresh_cache';
				$extra = '';

				$ostage = $stage;

				$exx = explode( ':::', $stage );
				if( isset( $exx[1] ) && $exx[1] ){
					$cexx = 'c'.$exx[0];
					if( class_exists( $cexx ) ){
						$cexx = new $cexx;
						$cexx->load_class( array( 'class' => array( $exx[1] ) ) );
						$stage = $exx[1];
					}
				}
				
				$c = $stage;
				$cl = "c".ucwords( $stage );
				if( class_exists( $cl ) ){
					$cls = new $cl();
					$cls->class_settings = $this->class_settings;
					
					$settings = array(
						'cache_key' => 'refresh-cache-' . $stage,
					);
					$stage_sub = get_cache_for_special_values( $settings );
					
					if( is_array( $stage_sub ) && ! empty( $stage_sub ) ){
						foreach( $stage_sub as $k => $sval ){
							if( $sval ){
								break;
							}
						}
						
						unset( $stage_sub[ $k ] );
						$settings = array(
							'cache_key' => 'refresh-cache-' . $stage,
							'cache_values' => $stage_sub,
						);
						set_cache_for_special_values( $settings );
						
						if( $mod != "analyze" ){
							$cls->class_settings["refresh_continue"] = 1;
						}
						$cls->class_settings["limit"] = $sval;
						
						if( ! empty( $stage_sub ) ){
							$r_action = '?action=audit&todo=refresh_cache&dd='.$sval;
							
							$next_table = $ostage;
							$extra = "<li><strong>".ucwords( str_replace( "_", " ", $next_table ) )."</strong> Breakpoint No: " . count( $stage_sub ) . "</li>";
						}else{
							$extra = "<li>Closing <strong>".ucwords( str_replace( "_", " ", $ostage ) )."</strong></li>";
						}
					}
					
					if( $next_table == "finish" ){
						//$r_action = '?action=audit&todo=refresh_cache';
					}
					
					//execute refresh action
					$cls->$c();
				}else{
					$settings = array(
						'cache_key' => 'refresh-cache-' . $stage,
					);
					$stage_sub = get_cache_for_special_values( $settings );
					
					if( is_array( $stage_sub ) && ! empty( $stage_sub ) ){
						foreach( $stage_sub as $k => $sval ){
							if( $sval ){
								break;
							}
						}
						
						unset( $stage_sub[ $k ] );
						$settings = array(
							'cache_key' => 'refresh-cache-' . $stage,
							'cache_values' => $stage_sub,
						);
						set_cache_for_special_values( $settings );
						
						if( ! empty( $stage_sub ) ){
							$r_action = '?action=audit&todo=refresh_cache&dd='.$sval;
							
							$next_table = $ostage;
							$extra = "<li><strong>".ucwords( str_replace( "_", " ", $next_table ) )."</strong> Breakpoint No: " . count( $stage_sub ) . "</li>";
						}else{
							$extra = "<li>Closing <strong>".ucwords( str_replace( "_", " ", $ostage ) )."</strong></li>";
						}
					}
				}
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = $next_table;
				$return['action'] = $r_action;
				
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = "<li class='task'>Preparing to Refresh <strong>".ucwords( str_replace( "_", " ", $return['id'] ) )."</strong>...</li>" . $extra . "<li><i class='icon-check'></i> ".ucwords( str_replace( "_", " ", $stage ) )." Cache Refreshed</li>";
				
				if( $running_in_background ){
					echo strip_tags( $return["html_prepend"] ) . "\n\n";
				}
			break;
			case "analyze_refresh_cache":
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'analyze';
				$return['id'] = $stage;
				$return['action'] = '?action=audit&todo=refresh_cache';
				
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = "<li class='task'>Analyzing <strong>".ucwords( str_replace( "_", " ", $return['id'] ) )."</strong> Data...</li>";
				
				//$this->refresh_data_break = 25;

				$ostage = $stage;
				
				$exx = explode( ':::', $stage );
				if( isset( $exx[1] ) && $exx[1] ){
					$cexx = 'c'.$exx[0];
					if( class_exists( $cexx ) ){
						$cexx = new $cexx;
						$cexx->load_class( array( 'class' => array( $exx[1] ) ) );
						$stage = $exx[1];
					}
				}
				
				//transform 
				$c = $stage;
				$cl = "c".ucwords( $stage );
				if( class_exists( $cl ) ){
					$cls = new $cl();
					
					$query = "SELECT COUNT( `id` ) as 'count' FROM `".$this->class_settings["database_name"]."`.`".$cls->table_name."` WHERE `record_status` = '1' ";
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $cls->table_name ),
					);
					$sql = execute_sql_query($query_settings);
					
					$stage_sub = array();
					
					if( isset( $sql[0]["count"] ) && $sql[0]["count"] ){
						$count = doubleval( $sql[0]["count"] );
						if( $count >= $this->refresh_data_break ){
							$count = doubleval( $sql[0]["count"] ) + $this->refresh_data_break;
							for( $i = 0; $i < $count; $i += $this->refresh_data_break ){
								$stage_sub[] = " LIMIT " . $i . ", " . $this->refresh_data_break;
							}
						}
					}
					
					if( ! empty( $stage_sub ) ){
						$settings = array(
							'cache_key' => 'refresh-cache-' . $stage,
							'cache_values' => $stage_sub,
						);
						set_cache_for_special_values( $settings );
						$return["html_prepend"] = "<li class='task'><strong>".count( $stage_sub )."</strong> ".ucwords( str_replace( "_", " ", $return['id'] ) )." Breakpoints</li>" . $return["html_prepend"];
					}
				}
				
				if( $running_in_background ){
					echo strip_tags( $return["html_prepend"] ) . "\n\n";
				}
			break;
			default:
				clear_load_time_cache();
				
				switch( $this->class_settings["action_to_perform"] ){
				case "clear_cache":
					//clear compiled settings
					clear_cache_for_special_values_directory( array(
						"permanent" => true,
						"directory_name" => "compiled-settings",
					) );
					
					$this->class_settings["new_trail"] = 1;
					$this->_new_day();
					
					$err = new cError('010011');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Cache Cleared Successfully</h4>';
					return $err->error();
				break;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/start-cache-refresh.php' );
				$return["html_replacement"] = $this->_get_html_view();
				
				$handle = '#dash-board-main-content-area';
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				$return["html_replacement_selector"] = $handle;
				
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "users";
				$return['action'] = '?action=audit&todo=analyze_refresh_cache';
				
				if( $running_in_background ){
					echo strip_tags( $return["html_replacement"] ) . "\n\n";
				}
			break;
			}
			
			$return["status"] = "new-status";
			return $return;
		}
		
		private function _app_update(){
			$return = array();
			
			$stage = "";
			if( isset( $_POST["id"] ) && $_POST["id"] )
				$stage = $_POST["id"];
			
			$back_url = "./";
			if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){
				if( file_exists( "../../" . $this->class_settings["calling_page"] . "sign-in/index.html" ) ){
					$back_url = "../sign-in/";
				}
			}
			$rb = "<li><a href='".$back_url."' class='btn red'>Return back to Application</a></li>";
			
			switch( $stage ){
			case "excute_update":
				$status = $this->_start_update();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "updated":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li>Update Successfully Executed</li>";
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li>".$status["status"]."</li>";
					break;
					}
					$return["html_prepend"] = $rb . $return["html_prepend"];
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "users";
					$return['action'] = '?action=audit&todo=refresh_cache';
				}
			break;
			case "extract_update":
				$this->class_settings["extract_files"] = 1;
				$status = $this->_perform_app_update();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "extracted":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Checking for executables...please wait</li><li>Update Successfully Loaded</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "excute_update";
						$return['action'] = '?action=audit&todo=app_update';
						
						analytics_update( array( "event" => 'updateExtracted' ) );
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb."<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "check_for_update":
				$status = $this->_perform_app_update();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "download":
						$version = isset( $status["version"] )?$status["version"]:"";
						
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Preparing to load updates...please wait</li><li>Update".$version." Successfully Downloaded</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "extract_update";
						$return['action'] = '?action=audit&todo=app_update';
						
						analytics_update( array( "event" => 'updateDownloaded' ) );
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "users";
						$return['action'] = '?action=audit&todo=refresh_cache';
					break;
					}
				}
			break;
			case "clean_up":
				clear_load_time_cache();
				
				$return["html_prepend_selector"] = "#update-progress-container";
				$return["html_prepend"] = "<li>Update Successfully Completed</li>";
				//$return["html_prepend"] = "<li><a href='".$pr["domain_name"]."' class='btn red'>Return back to Application</a></li><li>Update Successfully Completed</li>";
				
				//check for app update
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "check_for_update";
				$return['action'] = '?action=audit&todo=app_update';
			break;
			case "confirm_load_database":
				$status = $this->_confirm_load_database_tables();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "database_loaded":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Cleaning up files...please wait</li><li>Database Tables Successfully Verified</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "clean_up";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "load_database":
				$status = $this->_load_database_tables();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "database_loaded":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Verifying database tables...please wait</li><li>Database Successfully Loaded</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "confirm_load_database";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "extract_database":
				if( function_exists("get_upload_data_to_server_only") && get_upload_data_to_server_only() ){
					$return["html_prepend_selector"] = "#update-progress-container";
					$return["html_prepend"] = "<li class='task'>Skipping Database Download...please wait</li>";
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "check_for_update";
					$return['action'] = '?action=audit&todo=app_update';
					return $return;
				}
				
				$status = $this->_extract_database_tables();
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "database_extracted":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Loading Database...please wait</li><li>Database Successfully Extracted</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "load_database";
						$return['action'] = '?action=audit&todo=app_update';
						
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$status["status"]."</li>";
					break;
					}
				}
			break;
			case "start_update":
				//check if licence permits databack up
				$msg = "Database is already up-to-date";
				$pr = get_project_data();
				if( ( isset( $pr["auto_backup"] ) && $pr["auto_backup"] == "none" ) ){
					$status["status"] = "downloaded";
					$msg = "<strong>Your License is Limited & does not permit you to use our Auto Back-up Service</strong>";
					$start_app_update = 1;
				}else{
					$status = $this->_push_data_to_cloud();
				}
				
				$size = 0;
				if( isset( $status["size"] ) && $status["size"] )$size = $status["size"];
				
				if( isset( $status["status"] ) && $status["status"] ){
					switch( $status["status"] ){
					case "success":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Preparing next activity...please wait</li><li>Successfully Uploaded ".format_bytes( $size )."</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "start_update";
						$return['action'] = '?action=audit&todo=app_update';
					break;
					case "downloaded":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = $rb . "<li>".$msg."</li>";
						$start_app_update = 1;
					break;
					case "download":
						$return["html_prepend_selector"] = "#update-progress-container";
						$return["html_prepend"] = "<li class='task'>Preparing to refresh database...please wait</li><li>Successfully Downloaded ".format_bytes( $size )."</li>";
						
						$return['re_process'] = 1;
						$return['re_process_code'] = 1;
						$return['mod'] = 'import-';
						$return['id'] = "extract_database";
						$return['action'] = '?action=audit&todo=app_update';
					break;
					default:
						$return["html_prepend_selector"] = "#update-progress-container";
						$start_app_update = 1;
						$return["html_prepend"] = "<li class='task'>Preparing to check for upgrades...please wait</li><li>".$status["status"]."</li>";
					break;
					}
				}else{
					$start_app_update = 1;
					$return["html_prepend_selector"] = "#update-progress-container";
					$return["html_prepend"] = $rb . "<li>Unknown Error</li>";
				}
				
				if( isset( $start_app_update ) ){
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "check_for_update";
					$return['action'] = '?action=audit&todo=app_update';
					
					analytics_update( array( "event" => 'updateCheck' ) );
				}
			break;
			case "trace":
				$status = $this->_trace();
				if( $status ){
					//next step
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "start_update";
					$return['action'] = '?action=audit&todo=app_update';
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/internet-connection.php' );
					$return["html_prepend_selector"] = "#update-progress-container";
					$return["html_prepend"] = "<li class='task'>Preparing to Start Update...please wait</li>";
					
					
					analytics_update( array( "event" => 'DataUpload' ) );
				}else{
					//expired
					$pr = get_project_data();
					$return['redirect_url'] = $pr["domain_name"] . "html-files/expired-message.php";
				}
			break;
			default:			
				$connection = $this->_check_for_internet_connection();
				//$connection = 0;
				//echo $connection . 'internet'; exit;
				if( $connection ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/internet-connection.php' );
					$return["html_replacement"] = $this->_get_html_view();
					
					$return['re_process'] = 1;
					$return['re_process_code'] = 1;
					$return['mod'] = 'import-';
					$return['id'] = "trace";
					$return['action'] = '?action=audit&todo=app_update';
					
				}else{
					//no internet connection
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/no-internet-connection.php' );
					$return["html_replacement"] = $this->_get_html_view();
					$return["javascript_functions"] = array("set_function_click_event");
				}
				
				$return["html_replacement_selector"] = "#updates-container";
			break;
			}
			/*
			echo $stage . "\n\n";
			if( isset( $return["html_replacement"] ) )echo $return["html_replacement"] . "\n\n";
			if( isset( $return["html_prepend"] ) )echo $return["html_prepend"] . "\n\n";
			*/
			
			$return["status"] = "new-status";
			return $return;
		}
		
		private function _display_all_records_full_view(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notice.php' );
			$notice = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-filter-form.php' );
			$form = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$this->datatable_settings[ 'show_add_new' ] = 0;
            $this->datatable_settings[ 'show_edit_button' ] = 0;
            
			$this->datatable_settings[ 'show_delete_button' ] = 1;
			$this->datatable_settings[ 'show_advance_search' ] = 1;
			$this->datatable_settings[ 'custom_view_button' ] = '';
			
			$this->class_settings[ 'data' ]['form_data'] = $form;
			$this->class_settings[ 'data' ]['html'] = $notice;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['title'] = "Audit Trail";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ) 
				//'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 
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
		
		private function _record_user_action(){
			//Check if today is start of new day
			if( ( isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
				$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
			}else{
				$background_pagepointer = dirname( dirname( __FILE__ ) ) . "/";
				$this->class_settings["calling_page"] = $background_pagepointer;
			}
			
			$this->class_settings["user_id"] = "anonymous";
			$this->class_settings["user_email"] = "anonymous";
			
			$stamp = $this->_new_day();
			
            $trail = array();

			if( in_array( $this->user_action, $this->trail_types ) ){
				$this->trail = $this->user_action;
			}
			
			if( ! is_dir( $this->class_settings["calling_page"] . 'tmp' ) ){
				create_folder( $this->class_settings["calling_page"] . 'tmp', '', '' );
			}
			
			if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/'.$this->trail ) ){
				create_folder( $this->class_settings["calling_page"] . 'tmp/'.$this->trail, '', '' );
			}
			
			$tf = $this->trail;
			$collapse = 0;
			if( defined("NWP_COLLATE_AUDIT_TRAIL") && NWP_COLLATE_AUDIT_TRAIL ){
				$collapse = 1;
				$tf = NWP_COLLATE_AUDIT_TRAIL;
				if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/'.$tf ) ){
					create_folder( $this->class_settings["calling_page"] . 'tmp/'.$tf, '', '' );
				}
			}
			
			//Get Current File
			$filename = $this->class_settings["calling_page"] . 'tmp/'.$tf.'/'.$stamp.'.json';
			$fexisting = 0;
			if( ( ! $collapse ) && file_exists( $filename ) ){
				$fexisting = 1;
				$trail = json_decode( file_get_contents( $filename ) , true );
            }
            // print_r( $trail );exit;
            
			$lu_sn = '';
			if( isset( $_SESSION['key'] ) ){
				$cu = md5( 'ucert' . $_SESSION['key'] );
				
				if( isset( $_SESSION[ $cu ]["id"] ) ) {
					$this->class_settings["user_id"] = $_SESSION[ $cu ]["id"];
					$lu_sn = $this->class_settings["user_id"];
					$this->class_settings["user_email"] = $_SESSION[ $cu ]["fname"] . ' ' . $_SESSION[ $cu ]["lname"] .' ( '. $_SESSION[ $cu ]["email"] . ' )';
					
					$this->parameters["email"] = $this->class_settings["user_email"];
					
					if( isset( $_SESSION["ucert"]["lu_sn"] ) && $_SESSION["ucert"]["lu_sn"] ){
						$lu_sn = $_SESSION["ucert"]["lu_sn"] . 'u';
					}
				}
			}
			
			//Prepare new content
			$date = date("U");
			$ip = get_ip_address();
			
			$tx = array( 'user'=> $this->class_settings["user_id"], 'user_action'=>$this->user_action, 'table'=>$this->table, 'parameters'=> $this->parameters , 'date'=>$date, 'ip_address'=>$ip );
			if( class_exists('cNwp_logging') ){
				switch ($this->table) {
				case 'audit_trail':
				break;
				default:
					$nwp = new cNwp_logging();
					$nwp->class_settings = $this->class_settings;
					$tb = "audit_trail";
					$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
					$xt = $tx;
					$xt['user_name'] = get_name_of_referenced_record( array( 'id' => $xt['user'], 'table' => 'users' ) );
					$xt['parameters'] = json_encode( $xt['parameters'] );
					$in[ $tb ]->_save_audit( [ 'line_items' => $xt ] );
					// return;
				break;
				}
			}

			if( $collapse ){
				$tx["stamp"] = $stamp;
				
				$stamp = $lu_sn . clean2( $this->table, '-' ) . $date;
				$filename = $this->class_settings["calling_page"] . 'tmp/'.$tf.'/'.$stamp.'.json';
				if( file_exists( $filename ) ){
					$trail = json_decode( file_get_contents( $filename ) , true );
				}
				$tx["trail"] = $this->trail;
			}
			$trail[] = $tx;
			
			if( isset( $this->parameters["comment"] ) && isset( $this->parameters["elevel"] ) && $this->parameters["elevel"] == "fatal" ){
				$idx = $date .'a'. rand(2,9000);
				
				$h = '';
				/* foreach( $this->parameters as $k => $v ){
					if( is_array( $v ) ){
						$h .= '<strong>' . strtoupper( $k ) . '</strong><br />' . json_encode( $v ) . '<br /><br />';
					}else{
						$h .= '<strong>' . strtoupper( $k ) . '</strong><br />' . $v . '<br /><br />';
					}
				} */
				
				$e = array(
					"key" => $this->parameters["elevel"],
					"subject" => 'SYSTEM NOTICE',
					//"content" => $h,
					"content" => $this->parameters["comment"],
				);
				
				$bsettings = array(
					'cache_key' => 'notify_dept-' . $idx,
					'cache_values' => $e,
					'directory_name' => $this->table_name,
					'permanent' => true,
				);
				set_cache_for_special_values( $bsettings );
				
				run_in_background( "bprocess", 0, 
					array( 
						"action" => $this->table_name, 
						"todo" => "notify_dept", 
						"user_id" => $this->class_settings["user_id"], 
						"reference" => $idx,
						"no_session" => 1,
						"show_window" => 1,
					) 
				);
			}
			
			//Write file
            if( ! empty( $trail ) ){
                file_put_contents( $filename , json_encode( $trail ) );
				if( ! $fexisting ){
					nwp_file_clean_up( $filename, array( "new" => 1 ) );
				}
            }
			
		}
		
		public function _new_day(){
			//Check if today is start of new day
			$returning_html_data = '';
			$sn = 0;
			
            $stamp = 0;
			
			try{
				if( file_exists($this->class_settings['calling_page'].'tmp/'.$this->trail.'/stamp.php') ){
					$stamp = doubleval( file_get_contents( $this->class_settings['calling_page'] . 'tmp/' . $this->trail.'/stamp.php' ) );
					if( ! $stamp ){
						$stamp = mktime( date("H") , date("m"), 0, date("n"), date("j"), date("Y") );
					}
				}else{
					$stamp = mktime( date("H") , date("m"), 0, date("n"), date("j"), date("Y") );
					
					if( ! is_dir( $this->class_settings['calling_page'] . 'tmp/' . $this->trail ) ){
						create_folder( $this->class_settings["calling_page"] . 'tmp/'.$this->trail, '', '' );
					}
					file_put_contents( $this->class_settings['calling_page'] . 'tmp/' . $this->trail . '/stamp.php' , $stamp );
				}
			}catch(Exception $e) {
			  echo 'Message: ' .$e->getMessage();
			}
			
			//2. Check date with today date
			$date = date("U");
			//if($date >= ($stamp + (60*60*24))){
			$new_trail = 0;
			if( isset( $this->class_settings["new_trail"] ) && $this->class_settings["new_trail"] ){
				$new_trail = $this->class_settings["new_trail"];
			}
			
			if( $new_trail || $date >= ($stamp + (60 * 60 * $this->number_of_hours )) ){
				file_put_contents( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/pstamp.php' , $stamp );
				$stamp = $date;
                $stamp = mktime( date("H") ,date("m"), 0, date("n"), date("j"), date("Y") );
				
				file_put_contents( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/stamp.php' , $stamp );
				
				//trigger data back-up
				$settings = array(
					'cache_key' => "bg-processor-backup",
					'cache_values' => 1,
					'cache_time' => 'mini-time',
				);
				set_cache_for_special_values( $settings );
				
				if( $new_trail == 2 ){
					
				}else{
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => "sync",
							"user_id" => $this->class_settings["user_id"], 
							"no_session" => 1,
							"reference" => 1,
							"show_window" => 1,
						) 
					);
				}
			}
			
			return $stamp;
		}
		
		public function _back_up_files( $offline_backup = '', $date = '' ){
			//begin directory back-up
			$date = isset($date) && $date ? $date : mktime( 0, 0, 0, intval( date("m") ), date("j"), date("Y") );
			if( $date ){
				
				$date_filter = 'd-M-Y';
				$date_filter2 = 'Y.m.d';
				$major_build = 0;
				
				$exclude_files = array();
				
				$start_date = date( $date_filter, $date );
				$version = date( $date_filter2, $date );
				
				echo "\n\nBegining files backup from: " . $start_date . "\n\n";
				
				if( (  isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
					$this->class_settings["calling_page"] = $_SERVER["DOCUMENT_ROOT"].'/'.HYELLA_INSTALL_PATH;
				}else{
					$background_pagepointer = dirname( dirname( __FILE__ ) ) . "/";
					$this->class_settings["calling_page"] = $background_pagepointer;
				}

				if( ! is_dir( $this->class_settings["calling_page"] . 'php/dump-files' ) ){
					create_folder( $this->class_settings["calling_page"] . 'php/dump-files', '', '' );
				}
				
				$destinationFolder = $this->class_settings["calling_page"] . 'php/dump-files/' .  $version . '.zip';
				
				$destinationFolder2 = $this->class_settings["calling_page"] . 'php/dump-files/' . $version . '.hyella';
				
				$r = $this->_create_zip(
					array(), 
					$destinationFolder, 
					true, 
					array( 
						//'directory_path' => 'C:\hyella\htdocs\\' . HYELLA_INSTALL_ENGINE . '\\engine\files', 
						'directory_path' => $this->class_settings["calling_page"] . 'files/', 
						'exclude_files' => $exclude_files, 
						'exclude_folders' => array(), 
						'start_time' => $date, 
						'show_info' => 1 
					) 
				);
				
				if( file_exists( $destinationFolder ) ){
					
					$files_backup = $destinationFolder2;
					
					if( copy( $destinationFolder, $destinationFolder2 ) ){
						unlink( $destinationFolder );
					}
					
					echo "\n\nFiles Back-up Successful: " . $version . "\n\n";
					
					if( $offline_backup ){
						if( copy( $files_backup, $offline_backup."\\".$files_backup ) ){
							echo "\n\nOffline Files Back-up Successful: " . $version . "\n\n";
						}
					}
					
					if( get_hyella_development_mode() ){
						run_in_background("open_backup_directory", 0, array( "text" => realpath( $this->class_settings[ 'calling_page' ] . 'php/dump-files' ) ) );
					}
				}else{
					if( $r < 0 ){
						echo "\n\nERROR: Unable to save zipped file \n\n";
						
						//log failed files back-up
						$par = array();
						$par["elevel"] = 'fatal';
						$par["status"] = "backup";
						$par["comment"] = "File Back-up Failed";
						auditor("", "console", $this->table_name, $par );
					}
				}
				
			}
			
		}
		
		private function _back_up(){
			
			$this->_trace();
			
			$filename = '';
			
			echo "\n\nBegining database backup " . "\n\n";
			$offline_backup = "";
			if( function_exists("get_local_backup_directory_settings") ){
				$offline_backup = get_local_backup_directory_settings();
			}else{
				$par = array();
				$par["elevel"] = 'fatal';
				$par["status"] = "backup";
				$par["comment"] = "No offline Back-up Media Found. Please configure an offline back-up drive";
				auditor("", "console", $this->table_name, $par );
			}
			
			
			if( defined("PLATFORM") && PLATFORM == "linux" ){
				//unix
				$mysqlDatabaseName ='adebisi';
				$mysqlUserName ='root';
				$mysqlPassword ='';
				$mysqlHostName ='localhost';
				//$mysqlExportPath ='/var/www/lrcn/engine/php/dump/d.sql';
				$date = date("M-Y");
				$mysqlExportPath ='/var/www/lrcn/engine/php/dump/'.$date.'.ela';
				
				$command='/usr/bin/mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;
				exec( $command , $output = array() , $worked );
				
				switch($worked){
				case 0:
					$filename = $date.'.ela';
				break;
				case 1:
					//$filename = $worked;
					echo 'There was a warning during the export of <b>' .$mysqlDatabaseName .'</b> to <b>~/' .$mysqlExportPath .'</b>';
				break;
				case 2:
					//$filename = $worked;
					echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
				break;
				}
				
				if( $offline_backup ){
					copy( $filename, $offline_backup."\back-up-".date("d-M-Y-H-i").".ela" );
				}
			}else{
				$args = "";
				if( file_exists( "dump/dump.ela" ) ){
					copy( "dump/dump.ela", "dump/".date("d-M-Y").".ela" );
					unlink( "dump/dump.ela" );
				}
				
				//$offline_backup = "F:\new-backup";
				
				$ddd = $this->install_full_path . "\mysql\bin\mysqldump -uroot -hlocalhost ".$this->class_settings["database_name"]." > dump\dump.ela \n ";
				if( defined("DB_PORT") && DB_PORT ){
					$ddd = $this->install_full_path . "\mysql\bin\mysqldump -uroot -hlocalhost -P". DB_PORT ." ".$this->class_settings["database_name"]." > dump\dump.ela \n ";
				}
				
				if( $offline_backup ){
					//$ddd .= $this->install_full_path . "\mysql\bin\mysqldump -uroot -hlocalhost ".$this->class_settings["database_name"]." > ".$offline_backup."\back-up-".date("d-M-Y-H-i").".sql";
					$ddd .= "copy dump\dump.ela ".$offline_backup."\back-up-".date("d-M-Y-H-i").".ela";
				}
				$ddd .= " \nexit ";
				
				file_put_contents( "real_dumpdb.bat", $ddd );
				
				$ddd = "title HyellaBusinessManager \n";
				$ddd .= 'IF "%2%" == "hyella_development" ( ';
				$ddd .= "\n";
				$ddd .= 'ECHO DEVELOPMENT_MODE '; $ddd .= "\n";
				$ddd .= ') ELSE ( '; $ddd .= "\n";
				$ddd .= $this->install_full_path . '\nircmd.exe win hide ititle "HyellaBusinessManager" '; $ddd .= "\n";
				$ddd .= ') ';
				$ddd .= "\n";
				$ddd .= $this->install_full_path . '\php\php.exe -f "'.HYELLA_DOC_ROOT. str_replace( '/', '\\', HYELLA_INSTALL_ENGINE ) . '\engine\php\bprocess.php" %1 %2 %3';
				$ddd .= "\n";
				$ddd .= 'IF "%2%" == "hyella_development" ('; $ddd .= "\n";
				$ddd .= 'pause';  $ddd .= "\n";
				$ddd .= ') '; $ddd .= "\n";
				$ddd .= 'exit';
				
				file_put_contents( "bprocess.bat", $ddd );
				//show back-up window
				//run_in_background( "real_dumpdb", 0, array( "wait_for_execution" => 1, "show_window" => 1 ) );
				
				//do not show
				run_in_background( "dumpdb", 0, array( "wait_for_execution" => 1 ) );
				
				$filename = 'dump\dump.ela';
				
				if( file_exists( 'dump\dump.zip' ) ){
					unlink( 'dump\dump.zip' );
				}
				
				if( file_exists( $filename ) ){
					
					if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2' ) ){
						create_folder( $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2', '', '' );
					}
					
					$new_filename = $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2/dump.ela';
					
					if( copy( $filename, $new_filename ) ){
						$destinationFolder = $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2/dump.zip';
						
						if( $this->_create_zip( array(), $destinationFolder, true, array( 'directory_path' => $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2/' ) ) ){
							
							if( copy( $destinationFolder, 'dump\dump.zip' ) ){
								unlink( $destinationFolder );
								$filename = 'dump\dump.zip';
							}
							
						}
						
						unlink( $new_filename );
					}
				}else{
					//log failed database back-up
					$par = array();
					$par["elevel"] = 'fatal';
					$par["status"] = "backup";
					$par["comment"] = "Database Back-up Failed";
					$par[ 'class' ] = $this->table_name;
					$par[ 'action' ] = $this->class_settings[ 'action_to_perform' ];
					auditor("", "console", $this->table_name, $par );
				}
				
			}
			
			$this->_back_up_files( $offline_backup );
			
			return $filename;
            
		}
		
		private function _send_audit_log(){
			
			if( get_hyella_development_mode() ){
				return 0;
			}
			$special_mail = '';
			if( ( defined("NWP_EMAIL_AUDIT_REPORT") && NWP_EMAIL_AUDIT_REPORT ) ){
				$special_mail = NWP_EMAIL_AUDIT_REPORT;
			}else{
				return 0;
			}
			
			$attached_file = '';
			
			if( file_exists($this->class_settings['calling_page'].'tmp/'.$this->trail.'/pstamp.php') ){
				$stamp = file_get_contents( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/pstamp.php' );
				
				//NEW DAY
				//1. Get All records
				$filename = $this->class_settings['calling_page'].'tmp/'.$this->trail.'/'.$stamp.'.json';
				if( file_exists( $filename ) ){
					$trail = json_decode( file_get_contents( $filename ) , true );
					
					if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2' ) ){
						create_folder( $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2', '', '' );
					}
					
					$new_filename = $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2/'.$stamp.'.json';
					
					if( copy( $filename, $new_filename ) ){
						
						//$url = $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE;
						
						//$destinationFolder = 'C:\\hyella\\htdocs\\stamp.zip';
						//$destinationFolder = $url . '/stamp.zip';
						$destinationFolder = $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2/stamp.zip';
						
						if( $this->_create_zip( array(), $destinationFolder, true, array( 'directory_path' => $this->class_settings["calling_page"] . 'tmp/'.$this->trail.'2/' ) ) ){
							$attached_file = $destinationFolder;
						}
						
						unlink( $new_filename );
					}
					
				}
				
				$sn = 0;
				$returning_html_data = '';
				//2. Prepare Email and PDF
				if(isset($trail) && is_array($trail)){
					
					//Set table header
					$returning_html_data .= '<table>';
						$returning_html_data .= '<thead>';
							$returning_html_data .= '<th>S/N</th>';
							//$returning_html_data .= '<th>User ID</th>';
							//$returning_html_data .= '<th>User Action</th>';
							$returning_html_data .= '<th>Table</th>';
							$returning_html_data .= '<th>Comment</th>';
							$returning_html_data .= '<th>Date</th>';
							//$returning_html_data .= '<th>IP Address</th>';
						$returning_html_data .= '</thead>';
						$returning_html_data .= '<tbody>';
					
						foreach($trail as $tr){
							$returning_html_data .= '<tr>';
							$returning_html_data .= '<td>'.++$sn.'</td>';
							//$returning_html_data .= '<td>'.$tr['user'].'</td>';
							//$returning_html_data .= '<td>'.$tr['user_action'].'</td>';
							$returning_html_data .= '<td>'.$tr['table'].'</td>';
							$returning_html_data .= '<td>'. ( isset( $tr['parameters']['query'] )?$tr['parameters']['query']:'' ) .'</td>';
							$returning_html_data .= '<td>'.date('j-M-y H:i', doubleval($tr['date']) ).'</td>';
							//$returning_html_data .= '<td>'.$tr['ip_address'].'</td>';
							$returning_html_data .= '</tr>';
						}
						
						$returning_html_data .= '</tbody>';
					$returning_html_data .= '</table>';
					
				}
				
				$project = get_project_data();
				
				//3. Send Email
				$message = $returning_html_data;
				$subject = ' Audit Trail of ' . date('j-M-y', doubleval($stamp) );
				
				$project_name = '';
				if( isset( $project['project_title'] ) ){
					$subject = $project['project_title'] . $subject;
					$project_name = $project['project_title'];
				}
				if( isset( $project['admin_email'] ) ){
					$this->tmail = $project['admin_email'];
				}
				if( $special_mail && filter_var( $special_mail, FILTER_VALIDATE_EMAIL ) ){
					$this->tmail = $special_mail;
				}
				
				$this->class_settings[ 'destination_id' ] = '1';
				//$this->class_settings['topdf'][] = $message;
				
				$this->class_settings['subject'] = $subject;
				$this->class_settings['message'] = $message;
				
				//$this->class_settings['direct_message'] = 1;
				$this->class_settings['message_type'] = 99;//100; 101;
				$this->class_settings[ 'destination_id' ] = '1';
				$this->class_settings[ 'destination_full_name' ] = $project_name." Admin";
				$this->class_settings[ 'destination_email' ] = $this->tmail;
				
				if( $attached_file ){
					$this->class_settings['attachment'] = array( $attached_file );
				}
				
				$status = $this->_send_email();
				
				if( file_exists( $attached_file ) ){
					unlink( $attached_file );
				}
				
				if( isset( $status["status"] ) && $status["status"] ){
					$log["message"] = 'Audit Trail Sent to : '. $this->tmail;
					$log["event"] = 'audit-saved';
				}else{
					$log["message"] = 'Audit Trail Failed to : '. $this->tmail .' '. ( isset( $status["response"] )?$status["response"]:'' );
					$log["event"] = 'audit-failed';
					
				}
				
				log_in_console( $log["message"], $log["event"] );
				analytics_update( $log );
				
				unlink( $this->class_settings['calling_page'].'tmp/'.$this->trail.'/pstamp.php' );
				
				rebuild();
			}
		}
		
		private function _sync(){
			$stage = "";
			if( isset( $_POST["id"] ) && $_POST["id"] )
				$stage = $_POST["id"];
			
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch( $stage ){
			case "start_export_process":
				//run sync in background
				run_in_background( "bprocess", 0, 
					array( 
						"action" => $this->table_name, 
						"todo" => "sync",
						"user_id" => $this->class_settings["user_id"], 
						"reference" => 1,
					) 
				);
				
				//store variable for sync in progress
				$settings = array(
					'cache_key' => "synchronization",
					'cache_values' => 1,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//reprocess to check for completion every 20 seconds
				$return = $this->_sync_progress();
			break;
			default:
				$return['status'] = "new-status";
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = "start_export_process";
				$return['action'] = '?action=audit&todo=export_db';
				
				$return['html_replacement'] = "<ol id='sync' style='font-size:20px; margin:40px;'><li>Starting Request Processing. Please do not perform any action until this process is complete</li></ol>";
				$return['html_replacement_selector'] = $handle;
			break;
			}
			
			return $return;
		}
		
		private function _check_for_internet_connection(){
			if( function_exists( "check_for_internet_connection" ) ){
				return check_for_internet_connection();
			}else{
				error_reporting (~E_ALL);
				return file_get_contents( $this->ping_url . "ping.txt");
			}
		}
		
		private function _trace(){
			//remove to enable for license
			$connection = $this->_check_for_internet_connection();
			if( isset( $connection ) && ( $connection == '1' || $connection == 1 ) ){
				$version = get_app_version( $this->class_settings["calling_page"] );
				$status = file_get_contents( $this->ping_url."l/?project=" . $this->app ."&mac_address=".rawurlencode( get_mac_address() )."&app_version=".$version );
				//echo $this->ping_url."l/?project=" . $this->app ."&mac_address=".get_mac_address()."&app_version=".$version . "<br />";
				//echo $status; exit;
				if( $status == "expired" ){
					//invalid license
					reglob( $this->class_settings["calling_page"] );
				}
				
				if( doubleval( $status ) != 1 ){
					$status = 0;
				}
				
				return $status;
			}
		}
		
		private function _sync_progress(){
			//reprocess to check for completion every 20 seconds
			sleep(10);
			
			$settings = array(
				'cache_key' => "synchronization",
				'permanent' => true,
			);
			$value = get_cache_for_special_values( $settings );
			if( $value == 2 ){
				//sync complete
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>Request Complete</h4>';
				$return = $err->error();
				unset( $return["html"] );
				
				$return['html_prepend'] = "<li><strong>Request Complete!!!</strong></li>";
				
				$settings = array(
					'cache_key' => "synchronization-message",
					'permanent' => true,
				);
				$msg = get_cache_for_special_values( $settings );
				if( isset( $msg["title"] ) && isset( $msg["msg"] ) ){
					$return['html_prepend'] .= "<li><strong>".$msg["title"]."</strong><br />".$msg["msg"]."</li>";
				}
				
				$return['status'] = "new-status";
				$return['html_prepend_selector'] = "#sync";
				$return['complete'] = 1;
			
				return $return;
			}
			
			$return['html_prepend'] = "<li>Please wait. Processing Request...</li>";
			$return['html_prepend_selector'] = "#sync";
			
			$return['status'] = "new-status";
			$return['re_process'] = 1;
			$return['re_process_code'] = 1;
			$return['mod'] = 'import-';
			
			$return['id'] = ( isset( $this->class_settings[ 'sync_id' ] )?$this->class_settings[ 'sync_id' ]:1 );
			$return['action'] = ( isset( $this->class_settings[ 'sync_action' ] )?$this->class_settings[ 'sync_action' ]:'?action=audit&todo=sync_progress' );
			
			return $return;
		}
		
		private function _push_data_to_cloud(){
			$uri = $this->url;
			$url = $uri.'provision.php';
			
			$mac_address = get_mac_address();
			$task = true;
			$post_data = "";
			
			//while( $task ){
				clear_load_time_cache();
				$data = get_update_manifest();
				
				if( isset( $data["data"] ) && isset( $data["key"] ) ){
					$post_data = "&queries=" . rawurlencode( json_encode( $data["data"] ) );
				}else{
					if( isset( $this->class_settings["skip_data_download"] ) && $this->class_settings["skip_data_download"] ){
						return;
					}
					//download table contents
					$task = false;
					$post_data = "&download=1";
					
					clear_cache_for_special_values_directory( array(
						"permanent" => true,
						"directory_name" => "update-manifest",
					) );
				}
				
				if( $post_data ){
					$ch = curl_init();
					//set the url, number of POST vars, POST data
					curl_setopt($ch,CURLOPT_URL, $url);
					curl_setopt($ch,CURLOPT_POST, true );
					curl_setopt($ch,CURLOPT_POSTFIELDS, "license=".$this->app."&mac_address=". rawurlencode($mac_address).$post_data );
					//curl_setopt($ch, CURLOPT_HEADER, 0);  
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
					
					//execute post
					$result = curl_exec($ch);
					//echo $result; exit;
					
					//close connection
					curl_close($ch);
					
					$j = json_decode( $result, true );
					
					if( isset( $j["status"] ) && $j["status"] == "success" ){
						clear_update_manifest( $data["key"] );
						$j["size"] = strlen( $post_data ) - 9;
					}else{
						if( isset( $j["status"] ) && $j["status"] == "download" ){
							if( isset( $j["url"] ) && $j["url"] ){
								$file = file_get_contents( $uri . $j["url"] );
								file_put_contents( $this->class_settings["calling_page"]."tmp/db/dump.zip", $file );
								$j["size"] = filesize( $this->class_settings["calling_page"]."tmp/db/dump.zip" );
							}
						}else{
							//no data to upload
							if( ! isset( $j["status"] ) )$j = array( "status" => "No data to upload"	);
						}
					}
					return $j;
				}
			//}
		}
	
		private function _upload_mail_to_remote_server(){
			if( ! ( isset( $this->class_settings["data"] ) && $this->class_settings["data"] && is_array( $this->class_settings["data"] ) ) ){
				return 0;
			}
			
			$data = $this->class_settings["data"];
			$url = $this->ping_url.'update/send_mail.php';
			
			if( isset( $data["attachments"] ) && is_array( $data["attachments"] ) && ! empty( $data["attachments"] ) ){
				foreach( $data["attachments"] as $i => $filename ){
					$handle = fopen( $filename, "r" );
					$fdata = fread($handle, filesize($filename));
					
					$data["attachments"][ $i ] = pathinfo( $filename );
					$data["attachments"][ $i ]["file"] = base64_encode($fdata);
				}
			}
			
			$post_data["mac_address"] = rawurlencode( get_mac_address() );
			$post_data["license"] = $this->app;
			$post_data["version"] = get_application_version( $this->class_settings["calling_page"] );
			
			$extra = '';
			foreach( $post_data as $k => $v ){
				$extra .= '&'.$k.'='.$v;
			}
			
			//print_r( $post_data ); exit;
			
			$ch = curl_init();
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, true );
			curl_setopt($ch,CURLOPT_POSTFIELDS, $url.'&data='.rawurlencode( json_encode( $data ) ).$extra );
			//curl_setopt($ch, CURLOPT_HEADER, 0);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
			
			//execute post
			$result = curl_exec($ch);
			//echo $result; exit;
			
			//close connection
			curl_close($ch);
			
			$j = json_decode( $result, true );
			
			if( isset( $j["status"] ) && $j["status"] == "success" ){
				return $j;
			}else{
				return 0;
			}
		}
		
		private function _perform_app_update(){
			//extract files
			if( isset( $this->class_settings["extract_files"] ) && $this->class_settings["extract_files"] ){
				if( file_exists( $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE . "update.zip" ) ){
					$f = $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE . "update.zip";
					remove_app_version_file( $this->class_settings["calling_page"] );
					
					//$url = $this->class_settings["calling_page"];
					$url = $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE;
					
					$zip = new ZipArchive;
					$res = $zip->open($f);
					if ($res === TRUE) {
						$zip->extractTo( $url );
						$zip->close();
						unlink( $f );
						return array( "status" => "extracted" );
					}else{
						return array( "status" => "Could not open zipped file, try upgrading your app again" );
					}
					
				}
			}
			//get app version
			
			//check for app version upgrade
			
			//download upgrade
			$mac_address = get_mac_address();
			
			$uri = $this->ping_url;
			$url = $uri . 'update/update.php';
			
			$task = true;
			
			//while( $task ){
				
				$task = false;
				$post_data = "license=" . $this->app . "&mac_address=" . rawurlencode( $mac_address ) . "&app_version=" . rawurlencode( get_application_version( $this->class_settings["calling_page"] ) );
				
				$ch = curl_init();
				//set the url, number of POST vars, POST data
				curl_setopt($ch,CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_POST, true );
				curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data );
				//curl_setopt($ch, CURLOPT_HEADER, 0);  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
				
				//execute post
				$result = curl_exec($ch);
				//echo $result;
				
				//close connection
				curl_close($ch);
				
				$j = json_decode( $result, true );
				
				if( isset( $j["status"] ) && $j["status"] == "download" ){
					if( isset( $j["url"] ) && $j["url"] ){
						$file = file_get_contents( $uri . $j["url"] );
						file_put_contents( $_SERVER["DOCUMENT_ROOT"] . "/" . HYELLA_INSTALL_ENGINE . "update.zip", $file );
						$j["size"] = filesize( $this->class_settings["calling_page"]."update.zip" );
					}
					
					if( isset( $j["version"] ) ){
						$j["version"] = " for version " . $j["version"];
					}else{
						$j["version"] = "";
					}
				}
				
				return $j;
			//}
		}
		
		private function _import_db(){
			$handle2 = 'dash-board-main-content-area';
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case "finish_import":
				clear_load_time_cache();
				
				//clear update manifest
				clear_cache_for_special_values_directory( array(
					"permanent" => true,
					"directory_name" => "update-manifest",
				) );
				
				return $this->_refresh_cache();
				/*
				return array(
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'redirect_url' => "./",
				);
				*/
			break;
			case "start_import_db":
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					if( file_exists( $this->class_settings["calling_page"] . $_POST["id"] ) ){
						//move file to load folder
						if( file_exists( "download/loaddb.sql" ) )unlink( "download/loaddb.sql" );
						if( ! is_dir( "download/loaddb.sql" ) ){
							create_folder( "download", "", "" );
						}
						
						if( copy( $this->class_settings["calling_page"] . $_POST["id"], "download/loaddb.sql" ) ){
							$this->_load_database();
							
							$err = new cError( '010011' );
							$err->action_to_perform = 'notify';
							$err->class_that_triggered_error = 'cAudit.php';
							$err->method_in_class_that_triggered_error = '_sync_progress';
							$err->additional_details_of_error = '<h4>Import Complete</h4><p>Please wait 10 minutes and then refresh the app</p>';
							$return = $err->error();
							
							$return["html_replacement"] = $return["html"];
							$return["html_replacement_selector"] = $handle;
							unset( $return["html"] );
							$return["status"] = "new-status";
							
							return $return;
							
							return array(
								'html_replacement_selector' => $handle,
								'html_replacement' => "<a href='#' id='finish-import' class='btn red btn-block custom-action-button' function-name='finish_import' function-class='audit' function-id='audit-1' style='display:none;'>Restart Application</a><br /><ol id='sync' style='font-size:20px; margin:40px;'><li>Time Elapsed: <span id='reload-time'>0</span></li><li>Please wait. Application will refresh in about 60 seconds</li><li>Import Database Request Processing. Please do not perform any action until this process is complete</li></ol><script type='text/javascript'>setTimeout(function(){ $('#finish-import').fadeIn('slow').click(); }, 60000 ); setInterval( function(){ var t = $('#reload-time').text()*1; ++t; $('#reload-time').text(t); }, 1000 );</script>",
								'method_executed' => $this->class_settings['action_to_perform'],
								'status' => 'new-status',
								'javascript_functions' => array( "set_function_click_event" ),
							);
							
						}else{
							$err_title = "Failed to Load Database File";
							$err_msg = "Please ensure you have the right file and then try again";
						}
					}else{
						$err_title = "Failed to Locate Database File";
						$err_msg = "Please ensure you have the right file and then try again";
					}
				}else{
					$err_title = "Oops. Sorry an unknown error occurred";
					$err_msg = "Please ensure you have the right file and then try again";
				}
				
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
				return $err->error();
			break;
			default:
				//$handle2
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=start_import_db&html_replacement_selector=' . $handle2;
				$form = $this->_generate_new_data_capture_form();
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $form["html"],
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'prepare_new_record_form_new' ) 
					//'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 
				);
			break;
			}
		}
		
		private function _export_db(){
			$err_title = "";
			$code = "010014";
			$err_msg = "";
			$success  = 0;
			
			/* if( class_exists("cAssets_log") ){
				$asset = new cAssets_log();
				$asset->class_settings = $this->class_settings;
				$asset->class_settings["action_to_perform"] = "reamortize_assets_in_background";
				$asset->assets_log();
			} */
			if( class_exists("cProperty") ){
				$asset = new cProperty();
				$asset->class_settings = $this->class_settings;
				$asset->class_settings["action_to_perform"] = "background_update_all_tenure";
				$asset->property();
			}
			//sleep(10);
			if( defined("NWP_COLLATE_AUDIT_TRAIL") && NWP_COLLATE_AUDIT_TRAIL ){
				$tf = NWP_COLLATE_AUDIT_TRAIL;
				//$this->class_settings["calling_page"] . 'tmp/'.$tf
				if( defined("NWP_COLLATE_AUDIT_TRAIL_MEMORY_LIMIT") && NWP_COLLATE_AUDIT_TRAIL_MEMORY_LIMIT ){
					ini_set('memory_limit', NWP_COLLATE_AUDIT_TRAIL_MEMORY_LIMIT );
				}else{
					ini_set('memory_limit', '512M' );
				}
				$tr = [];
				foreach( glob( $this->class_settings["calling_page"] . 'tmp/'.$tf . "/*.json" ) as $filename ) {
					if ( is_file( $filename ) ) {
						$jb = json_decode( file_get_contents( $filename ), true );
						if( is_array( $jb ) && ! empty( $jb ) ){
							foreach( $jb as $jv ){
								if( isset( $jv["stamp"] ) && $jv["stamp"] && isset( $jv["trail"] ) && $jv["trail"] ){
									$sjt = $jv["stamp"];
									$jt = $jv["trail"];
									unset( $jv["trail"] );
									unset( $jv["stamp"] );
									$tr[ $jt ][ $sjt ][] = $jv;
								}
							}
							unlink( $filename );
							unset( $jb );
						}
					}
				}
				
				if( ! empty( $tr ) ){
					foreach( $tr as $trail => $jb ){
						if( is_array( $jb ) && ! empty( $jb ) ){
							if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/'.$trail ) ){
								create_folder( $this->class_settings["calling_page"] . 'tmp/'.$trail, '', '' );
							}
							
							foreach( $jb as $stamp => $jv ){
								$filename = $this->class_settings["calling_page"] . 'tmp/'.$trail . '/' . $stamp . '.json';
								file_put_contents( $filename , json_encode( $jv ) );
								nwp_file_clean_up( $filename, array( "new" => 1 ) );
								unset( $tr[ $trail ][ $stamp ] );
							}
							unset( $tr[ $trail ] );
						}
					}
					unset( $tr );
				}
			}
			
			if( defined("NWP_INDEX_AUDIT_TRAIL") && NWP_INDEX_AUDIT_TRAIL ){
				$tf = NWP_INDEX_AUDIT_TRAIL;
				$backlog = 0;
				if( ! is_dir( $this->class_settings["calling_page"] . 'tmp/'.$tf ) ){
					$backlog = 1;
					create_folder( $this->class_settings["calling_page"] . 'tmp/'.$tf, '', '' );
				}
				
				$indexes = array();
				if( $backlog ){
					ini_set('memory_limit', '1G' );
					
					if( ! empty( $this->trail_types ) ){
						foreach( $this->trail_types as $tr ){
							if( is_dir( $this->class_settings["calling_page"] . 'tmp/'.$tr ) ){
								
								foreach( glob( $this->class_settings["calling_page"] . 'tmp/'.$tr . "/*.json" ) as $filename ) {
									if ( is_file( $filename ) ) {
										$jb = pathinfo( $filename );
										if( isset( $jb["filename"] ) && $jb["filename"] ){
											$fd = doubleval( $jb["filename"] );
											$index = mktime( 0, 0, 0, date("n", $fd ), date("j", $fd ), date("Y", $fd ) );
											
											$indexes[ $index ][] = $tr . "/". $jb["basename"];
										}
									}
								}
								
							}
						}
					}
				}else if( file_exists( $this->class_settings["calling_page"] . "tmp/". $this->trail ."/pstamp.php" ) ){
					$pfile = file_get_contents( $this->class_settings["calling_page"] . "tmp/". $this->trail ."/pstamp.php" );
					
					foreach( $this->trail_types as $tr ){
						$filename  = $this->class_settings["calling_page"] . 'tmp/'.$tr . "/". $pfile .".json";
							
						if( file_exists( $filename ) ) {
							$jb = pathinfo( $filename );
							if( isset( $jb["filename"] ) && $jb["filename"] ){
								$fd = doubleval( $jb["filename"] );
								$index = mktime( 0, 0, 0, date("n", $fd ), date("j", $fd ), date("Y", $fd ) );
								$indexes[ $index ][] = $tr . "/". $jb["basename"];
							}
						}
					}
				}
				
				if( ! empty( $indexes ) ){
					foreach( $indexes as $ik => $iv ){
						$filename = $this->class_settings["calling_page"] . 'tmp/'.$tf . '/' . $ik . ".json";
						file_put_contents(  $filename, json_encode( $iv ) );
						nwp_file_clean_up( $filename, array( "new" => 1 ) );
						unset( $indexes[ $ik ] );
					}
				}
			}
			
			//1. Get SQL Database File
			$this->_send_audit_log();
			
			if( defined("PLATFORM") && PLATFORM == "windows" ){
				$file_name = $this->_back_up();
				
				sleep( 2 );
				$dump = $file_name;
				if( file_exists( $dump ) ){
					$success = 1;
					
				}else{
					//could not export local db
					$err_title = "File Not Found";
					$err_msg = "Local database file could not be exported";
				}
				
				$settings = array(
					'cache_key' => "synchronization",
					'cache_values' => 2,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				if( $success ){
					$pr = get_project_data();
					
					$code = "010011";
					$err_title = "Successful Database Export";
					$err_msg = "<a href='".$pr["domain_name"]."php/" . $dump . "' class='btn blue' target='_blank' download>Click to Download Database: " . format_bytes( filesize( $dump ) ) . " <i class='icon-download-alt'></i></a>";
				}
				
				$settings = array(
					'cache_key' => "synchronization-message",
					'cache_values' => array( "title" => $err_title, "msg" => $err_msg ),
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
			}
			
			$err = new cError( $code );
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'cAudit.php';
			$err->method_in_class_that_triggered_error = '_sync_progress';
			$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
			return $err->error();
		}
		
		private function _sync_to_cloud(){
			$err_title = "";
			$err_msg = "";
			$success  = 0;
			
			$connection = $this->_check_for_internet_connection();
			if( $connection ){
				//1. Get SQL Database File
				$file_name = $this->_back_up();
				sleep( 20 );
				$dump = "dump/dump.ela";
				if( file_exists( $dump ) ){
					//ZIP FILE
					$local_file = "dump/dump.zip";
					$this->_create_zip( array( $dump ), $local_file, 1 );
					if( file_exists( $local_file ) ){
						$sync = $this->_ftp_store( $local_file );
						if( $sync ){
							//get vps ip address
							$ip = 0;
							//$ip = file_get_contents("http://ping.northwindproject.com/vps.txt");
							if( $ip ){
								//trigger background process on vps
								$res = file_get_contents( "http://".$ip."/remote/?project=" . $this->app );
								if( $res ){
									//Send Email to Admin - successful sync
									$success = 1;
								}else{
									//could not store zipped file in ftp server
									$err_title = "Cloud Server Process Error";
									$err_msg = "Cloud server could not process the synchronization request";
								}
							}else{
								//could not store zipped file in ftp server
								$err_title = "Invalid Cloud Server";
								$err_msg = "Could not resolve cloud server address";
							}
							
						}else{
							//could not store zipped file in ftp server
							$err_title = "Error Uploading Zipped File to Cloud Server";
							$err_msg = "Local database file could not be uploaded to the cloud server";
						}
					}else{
						//could not zip local db
						$err_title = "Error Compressing Database File";
						$err_msg = "Local database file could not be compressed";
					}
				}else{
					//could not export local db
					$err_title = "File Not Found";
					$err_msg = "Local database file could not be exported";
				}
			}else{
				//No internet ERROR
				$err_title = "No Internet Access";
				$err_msg = "Please ensure you have access to the internet prior to synchronization";
			}
			
			$settings = array(
				'cache_key' => "synchronization",
				'cache_values' => 2,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$code = "010014";
			if( $success ){
				$code = "010011";
				$err_title = "Successful Synchronization";
				$err_msg = "Data Transferred: 20KB";
			}
			
			$settings = array(
				'cache_key' => "synchronization-message",
				'cache_values' => array( "title" => $err_title, "msg" => $err_msg ),
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$err = new cError( $code );
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'cAudit.php';
			$err->method_in_class_that_triggered_error = '_sync_progress';
			$err->additional_details_of_error = '<h4>'.$err_title.'</h4><p>'.$err_msg.'</p>';
			return $err->error();
		}
		
		private function _confirm_load_database_tables(){
			//extract zip file
			set_time_limit(0); //Unlimited max execution time
			
			$mac_address = get_mac_address();
			$url = $this->class_settings["calling_page"] . "tmp/db/" . $mac_address  . "/";
			
			//read all files in dir & replace db table
			$csettings = array(
				'cache_key' => "loaded-tables",
			);
			$affected_tables = get_cache_for_special_values( $csettings );
			
			$error_tables = array();
			
			if( is_array( $affected_tables ) && ! empty( $affected_tables ) ){
				
				foreach( $affected_tables as $table_name ) {
					if( ! $table_name )continue;
					
					$query = "SELECT `id` FROM `".$this->class_settings["database_name"]."`.`".$table_name."` LIMIT 1";
					
					$query_settings = array(
						'database'=>$this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $table_name ),
					);
					$sql = execute_sql_query($query_settings);
					
					if( isset( $this->class_settings["debug"] ) && $this->class_settings["debug"] ){
						if( isset( $this->class_settings['development'] ) && $this->class_settings['development'] ){
							echo $query_settings["query"] . "\n\n";
						}else{
							echo "NOTICE: Verifying data in " . $table_name . "\n\n";
						}
					}
					
					sleep( 3 );
					
					if( ! ( isset( $sql[0]["id"] ) ) ){
						$error_tables[] = $table_name;
						if( isset( $this->class_settings["debug"] ) && $this->class_settings["debug"] ){
							echo "ERROR: Unable to synchronize " . $table_name . "\n\n";
						}
					}
				}
				
				if( ! empty( $error_tables ) ){
					//send email notifications of corrupted tables
					analytics_update( array( "event" => 'corruptedTables' ) );
					
					//notify user to perform manual override operation
					$connection = $this->_check_for_internet_connection();
					if( intval( $connection ) ){
						$version = get_application_version( $this->class_settings["calling_page"] );
						
						$action = 'corrupted_tables';
						$message = 'Time: ' . date("d-M-Y H:i -- ") . implode( ", ", $error_tables );
						$extra = '&action=' . $action . '&msg=' . rawurlencode( $message );
						
						file_get_contents( $this->ping_url."l/?project=" . $this->app ."&mac_address=".rawurlencode( get_mac_address() )."&app_version=".$version.$extra );
					}
					
					return array( "status" => "Corrupted Tables. " . implode( ", ", $error_tables ) );
				}
				
				//delete files
				foreach( glob( $url . "*.*" ) as $filename ) {
					if ( is_file( $filename ) )unlink( $filename );
				}
				rmdir( $url );
				//return array( "status" => "database_loaded" );
				
				//clear tmp cache
				clear_cache_for_special_values( $csettings );
				
				return array( "status" => "database_loaded" );
				
			}
			return array( "status" => "No Affected Tables. Verification Aborted" );
			
		}
		
		private function _load_database_tables(){
			//extract zip file
			set_time_limit(0); //Unlimited max execution time
			$files = 1;
			$url = $this->class_settings["calling_page"];
			
			if( $files ){
				$mac_address = get_mac_address();
				$url .= "tmp/db/" . $mac_address  . "/";
				$uri = get_hyella_path()."tmp/db/" . $mac_address  . "/";
				
				//read all files in dir & replace db table
				mysql_select_db( $this->class_settings['database_name'] );
				
				$affected_tables = array();
				$empty_tables = array();
				
				foreach( glob( $url . "*.sql" ) as $filename ) {
					
					$table_name = basename($filename , ".sql");
					$backup_file = $uri . $table_name.".sql";
					
					if ( is_file( $filename ) ) {
						$filecontent = file_get_contents( $filename );
						
						if( $filecontent ){
							$affected_tables[] = $table_name;
							
							$query = "TRUNCATE `".$this->class_settings["database_name"]."`.`".$table_name."`";
							$query_settings = array(
								'database'=>$this->class_settings['database_name'],
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'EXECUTE',
								'set_memcache' => 0,
								'tables' => array( $table_name ),
							);
							execute_sql_query($query_settings);
							
							$query_settings["query"] = "LOAD DATA INFILE '$backup_file' INTO TABLE $table_name";
							
							if( isset( $this->class_settings["debug"] ) && $this->class_settings["debug"] ){
								if( isset( $this->class_settings['development'] ) && $this->class_settings['development'] ){
									echo $query_settings["query"] . "\n\n";
								}else{
									echo "NOTICE: Load data into ".$table_name." \n\n";
								}
							}
							
							execute_sql_query( $query_settings );
							sleep( 5 );
						}else{
							
							if( isset( $this->class_settings["debug"] ) && $this->class_settings["debug"] ){
								echo "NOTICE: Empty " . $table_name . "\n\n";
							}
							
							$empty_tables[] = $table_name;
						}
					}
				}
				$settings = array(
					'cache_key' => "loaded-tables",
					'cache_values' => $affected_tables,
					'cache_time' => 'mini-time',
				);
				set_cache_for_special_values( $settings );
				
				return array( "status" => "database_loaded" );
				//delete files
				//clear tmp cache
			}
		}
		
		private function _extract_database_tables(){
			//extract zip file
			$files = 0;
			$f = $this->class_settings["calling_page"]."tmp/db/dump.zip";
			if( file_exists( $f ) ){
				$url = $this->class_settings["calling_page"];
				$zip = new ZipArchive;
				$res = $zip->open($f);
				if ($res === TRUE) {
					$zip->extractTo( $url );
					$zip->close();
					unlink( $f );
					$files = 1;
					return array( "status" => "database_extracted" );
				}else{
					return array( "status" => "Could not open zipped file, try downloading database again" );
				}
			}else{
				return array( "status" => "Database file not found, due to failed download" );
			}
		}
		
		private function _save_changes(){
			$this->table = 'audit';
			
			//CHECK FOR SELECT AUDIT TRAIL TO VIEW
			if(isset($_POST['table']) && $_POST['table']==$this->table && isset($_POST['q1']) && $_POST['q1']){
				$_SESSION[$this->table]['filter']['audit_trail'] = $_POST['q1'];
				
				//RETURN SUCCESS NOTIFICATION
				$err = new cError('060104');
				$err->action_to_perform = 'notify';
				
				return $err->error();
			}
		}
		
		private function _view_audit_trail(){
			$this->table = 'audit';
			
			//GET ALL FIELDS IN TABLE
			/*
			$fields = array(
				array('ID'),
				array('USER_DT1_DT1'),
				array('USER_ACTION_DT1_DT1'),
				array('TABLE_DT1_DT1'),
				array('DATE_DT4_DT1'),
				array('IP_ADDRESS_DT1_DT1'),
				array('COMMENT_DT1_DT1'),
			);
			*/
			$time = 0;
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$time = $this->_convert_date_to_timestamp( $_POST["start_date"] );
			}
			
			$end_time = date("U");
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$end_time = $this->_convert_date_to_timestamp( $_POST["end_date"] );
			}
			
			$user = '';
			if( isset( $_POST["user"] ) && $_POST["user"] ){
				$user = $_POST["user"];
			}
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			
			$files = array();
			
			if( $time ){
				$x = $time;
				while( $x < $end_time ){
					$filename = $this->class_settings['calling_page'].'tmp/'.$this->trail.'/'.$x.'.json';
					if( file_exists( $filename ) ){
						$files[] = 'tmp/'.$this->trail.'/'.$x.'.json';
					}
					$x += 3600;
				}
			}
			
			if( empty( $files ) ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/no-data.php' );
				$notice = $this->_get_html_view();
				return array(
					'html_replacement' => $notice,
					'html_replacement_selector' => "#data-table-section",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
				);
			}
			
			$_SESSION[ $this->table_name ][ 'filter' ][ 'file' ] = $files;
			$_SESSION[ $this->table_name ][ 'filter' ][ 'user' ] = $user;
			
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
				
				$err->class_that_triggered_error = 'cshare.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$this->datatable_settings['current_module_id'] = "2";
			
			$form->datatables_settings = $this->datatable_settings;
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state' ), 
			);
		}
		
		private function _convert_date_to_timestamp( $date ){
			$values = explode( '-' , $date );
			
			if( isset( $values[0] ) && isset( $values[1] ) && isset( $values[2] ) ){
				$year = intval( $values[0] );
				$month = intval( $values[1] );
				$day = intval( $values[2] );
				
				return mktime( 0, 0, 0, $month , $day , $year );
			}
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$this->class_settings['hidden_records'] = array(
				"user" => 1,
				"user_mail" => 1,
				"table" => 1,
				"audit004" => 1,
				"user_action" => 1,
				"comment" => 1,
				"date" => 1,
			);
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'import_db':
				$this->class_settings['form_submit_button'] = 'Import Database';
				$this->class_settings['hidden_records'][ "audit001" ] = 1;
				$this->class_settings[ 'form_action_todo' ] = "start_import_db";
			break;
			case 'display_application_update_view':
				$this->class_settings['form_submit_button'] = 'Begin Offline Update';
				$this->class_settings['hidden_records'][ "audit001" ] = 1;
				$this->class_settings[ 'form_extra_options' ][ "id" ]['field_label'] = 'Select Update (*.hyella, *.ela)';
				
				$this->class_settings[ 'form_action_todo' ] = "application_update_start_import";
			break;
			default:
				$this->class_settings['form_submit_button'] = 'Show Audit Trail &rarr;';
				$this->class_settings['hidden_records'][ "id" ] = 1;
			break;
			}
			
			$this->class_settings['do_not_show_headings'] = 1;
			$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'view';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => isset($returning_html_data[ 'record_id' ])?$returning_html_data[ 'record_id' ]:"",
			);
		}
		
		private function _get_files_in_sub_directory( $pathdir = '', $pathdir2 = '', $valid_files = array(), $options = array() ) {
			//$pathdir2 = $pathdir .'\\'. $file;
			if( isset( $options["exclude_folders"] ) && in_array( $pathdir , $options["exclude_folders"] ) ){
				return $valid_files;
			}
			$start_time = isset( $options["start_time"] )?$options["start_time"]:0;
			$show_info = isset( $options["show_info"] )?$options["show_info"]:0;
			
			if( $show_info ){
				echo "\n\n DIR: " . $pathdir;
			}
			
			$dir = opendir( $pathdir );
			
			while( $file = readdir( $dir ) ){
				//echo $file;
				//echo $pathdir . $file;
				
				if( is_file( $pathdir .'\\'. $file ) ){
					
					if( ! ( isset( $options["exclude_files"] ) && in_array( $pathdir .'\\'. $file , $options["exclude_files"] ) ) ){
						$add = 1;
						
						if( $start_time ){
							$add = 0;
							
							if( filemtime( $pathdir .'\\'. $file ) >= $start_time ){
								$add = 1;
							}
						}
						
						if( $add ){
							if( $show_info ){
								echo "\n FILE: " . $pathdir2 . $file;
							}
							
							$valid_files[] = $pathdir2 . $file;
						}
					}
					
				}else{
					if( $file != '.' && $file != '..' && is_dir( $pathdir .'\\'. $file ) ){
						$valid_files = $this->_get_files_in_sub_directory( $pathdir .'\\'. $file, $pathdir2 . $file .'\\' , $valid_files, $options );
					}
				}
			}
			
			return $valid_files;
		}
		
		public function _create_zip( $files = array(), $destination = '', $overwrite = false, $options = array() ) {
			//if the zip file already exists and overwrite is false, return false
			if( file_exists($destination) && ! $overwrite) { return false; }
			if( file_exists($destination) )unlink( $destination );
			//vars
			
			$valid_files = array();
			
			if( isset( $options["directory_path"] ) && $options["directory_path"] && file_exists( $options["directory_path"] ) && is_dir( $options["directory_path"] ) ){
				//$pathdir = "C:\hyella\htdocs\feyi";
				$pathdir = $options["directory_path"];
				
				if( is_array( $files ) && ! empty( $files ) ) {
					//$valid_files[] = $files;
					
					//cycle through each file
					foreach($files as $file) {
						//make sure the file exists
						//if(file_exists($file)) {
							$valid_files[] = realpath( $file );
						//}
					}
					
				}else{
					$opt = array();
					
					if( isset( $options['show_info'] ) && $options['show_info'] ){
						$opt['show_info'] = $options['show_info'];
					}
					
					if( isset( $options['start_time'] ) && $options['start_time'] ){
						$opt['start_time'] = doubleval( $options['start_time'] );
					}
					
					if( isset( $options['exclude_files'] ) && ! empty( $options['exclude_files'] ) ){
						foreach( $options['exclude_files'] as $v ){
							$opt['exclude_files'][] = $pathdir .'\\'. $v;
						}
					}
					
					if( isset( $options['exclude_folders'] ) && ! empty( $options['exclude_folders'] ) ){
						foreach( $options['exclude_folders'] as $v ){
							$opt['exclude_folders'][] = $pathdir .'\\'. $v;
						}
					}
					
					$valid_files = $this->_get_files_in_sub_directory( $pathdir, '', array(), $opt );
					
					if( isset( $options['show_info'] ) && $options['show_info'] ){
						echo "\n\n\nTotal of ".count( $valid_files )." items found \n\n\n";
					}
					
				}
				
			}
			
			//print_r( $valid_files ); exit;
			//if we have good files...
			if(count($valid_files)) {
				//create the archive
				$zip = new ZipArchive();
				if($zip->open($destination, ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				
				//add the files
				
				foreach( $valid_files as $file2 ) {
					//$pf = pathinfo( $file2 );
					//print_r( $pf ); exit;
					//echo realpath( $file );
					
					$zip->addFile( $pathdir . '\\' . $file2, $file2 );
				}
				
				//$zip->addFile( 'C:\hyella\htdocs\\feyi\\engine\\tmp\\tr\\1552561200.json', '1552561200.json' );
				//debug
				//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
				
				//close the zip -- done!
				$zip->close();
				
				//check to make sure the file exists
				
				return file_exists($destination);
			}
			else
			{
				return false;
			}
		}
		
		private function _ftp_store( $local_file ){
			set_time_limit(0); //Unlimited max execution time
			/**
			 * Transfer (Export) Files Server to Server using PHP FTP
			 * @link https://shellcreeper.com/?p=1249
			 */
			 
			/* Remote File Name and Path */
			$remote_file = 'dump.zip';
			 
			/* FTP Account (Remote Server) */
			$ftp_host = 'ftp.northwindproject.com'; /* host */
			$ftp_user_name = 'adebisi'; /* username */
			$ftp_user_pass = 'b456a8$#$-fgf-*&-343FDsls4355dssf.gfd.sa'; /* password */
			 
			/* File and path to send to remote FTP server */
			//$local_file = 'files.zip';
			 
			/* Connect using basic FTP */
			$connect_it = ftp_connect( $ftp_host );
			 
			/* Login to FTP */
			$login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
			 
			/* Send $local_file to FTP */
			if ( ftp_put( $connect_it, $remote_file, $local_file, FTP_BINARY ) ) {				
				/* Close the connection */
				ftp_close( $connect_it );
				return 1;
			}
			else {
				ftp_close( $connect_it );
				return 0;
			}
			 
		}
		
		private function _load_database(){
			//execute load command
			set_time_limit(0);
			
			if( defined("PLATFORM") && PLATFORM == "linux" ){
				//unix
				$mysqlUserName ='root';
				$mysqlHostName ='localhost';
				$p = pathinfo( "download/loaddb.sql" );
				$mysqlExportPath = $p["dirname"]."/".$p["basename"];
				
				$command='/usr/bin/mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' ' .$this->class_settings["database_name"] .' < ' .$mysqlExportPath;
				exec( $command , $output = array() , $worked );
				
			}else{
				$args = "";
				//prepare bat file
				
				
				if( defined("DB_PORT") && DB_PORT ){
					file_put_contents( "real_loaddb.bat", $this->install_full_path . "\mysql\bin\mysql -uroot -hlocalhost -P". DB_PORT ." ".$this->class_settings["database_name"]." < download\loaddb.sql \nexit" );
				}else{
					file_put_contents( "real_loaddb.bat", $this->install_full_path . "\mysql\bin\mysql -uroot -hlocalhost ".$this->class_settings["database_name"]." < download\loaddb.sql \nexit" );
				}
				
				run_in_background( "loaddb", 0, array( "wait_for_execution" => 1, "show_window" => 1 ) );
			}
			clear_load_time_cache();
		}
		
		private function _start_update(){
			if( file_exists( $this->class_settings["calling_page"] . "update/update.php" ) ){
				set_time_limit(0);
				
				include $this->class_settings["calling_page"] . "update/update.php";
				
				if( isset( $updater ) && is_array( $updater ) ){
					foreach( $updater as $val ){
						switch( $val["type"] ){
						case "database"	:
							if( isset( $val["query"] ) ){
								
								$query = str_replace( "@db@", $this->class_settings["database_name"], $val["query"] );
								
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query,
									'query_type' => 'INSERT',
									'set_memcache' => 0,
									'tables' => array(),
								);
								execute_sql_query( $query_settings );
							}
						break;
						case "loaddatabase":
							if( isset( $val["database"] ) && $val["database"] ){
								$this->class_settings["database_name"] = $val["database"];
							}
							$this->_load_database();
						break;
						case "method":
							if( isset( $val["action"] ) && isset( $val["todo"] ) ){
							
								$current_user_session_details = array(
									'id' => 1300130013,
									'email' => 'pat2echo@gmail.com',
									'fname' => 'Patrick',
									'lname' => 'Ogbuitepu',
									'privilege' => '1300130013',
									'login_time' => date("U"),
									'verification_status' => 1,
									'remote_user_id' => '',
									'country' => 'NG',
								);
								
								$settings = array(
									'display_pagepointer' => $this->class_settings["calling_page"],
									'pagepointer' => $this->class_settings["calling_page"],
									'user_cert' => $current_user_session_details,
									'database_connection' => $this->class_settings['database_connection'],
									'database_name' => $this->class_settings['database_name'], 
									'classname' => $val["action"], 
									'action' => $val["todo"],
									'language' => SELECTED_COUNTRY_LANGUAGE,
									'skip_authentication' => 1,
								);
								
								reuse_class( $settings );
							}
						break;
						}
						
					}
					clear_load_time_cache();
					unlink( $this->class_settings["calling_page"] . "update/update.php" );
					rmdir( $this->class_settings["calling_page"] . "update" );
					return array( "status" => "updated" );
				}
			}
			return array( "status" => "No Update" );
		}
		
		private function _send_email(){
			//Send Successful Email Verification Message
			$email = new cEmails();
			$email->class_settings = $this->class_settings;
			
			$email->class_settings[ 'action_to_perform' ] = 'send_mail';
			
			$email->class_settings[ 'destination' ] = array();
			
			$email->class_settings[ 'destination' ]['email'][] = isset( $this->class_settings[ 'destination_email' ] )?$this->class_settings[ 'destination_email' ]:$this->class_settings[ 'user_email' ];
			$email->class_settings[ 'destination' ]['full_name'][] = isset( $this->class_settings[ 'destination_full_name' ] )?$this->class_settings[ 'destination_full_name' ]:$this->class_settings[ 'user_full_name' ];
			$email->class_settings[ 'destination' ]['id'][] = isset( $this->class_settings[ 'destination_id' ] )?$this->class_settings[ 'destination_id' ]:$this->class_settings[ 'user_id' ];
			
			return $email->emails();
		}
	}
?>

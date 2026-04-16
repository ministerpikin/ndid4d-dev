<?php
	/**
	 * empowerment Class
	 *
	 * @used in  				empowerment Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	empowerment
	 */
	
	class cAll_reports extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'all_reports';
		public $table_package = 'accounting';
		
		public $default_reference = '';
		
		public $label = 'Summary';
		
		private $associated_cache_keys = array(
			'all_reports',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		function __construct(){
			
		}
	
		function all_reports(){
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_reporting_view':
				$returned_value = $this->_display_reporting_view();
			break;
			case 'display_reporting_view_popup':
				$returned_value = $this->_display_reporting_view_popup();
			break;
			case 'view_version2_report':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'view_obstetrics_services_record_report':
				$returned_value = $this->_obstetrics_services_record_report();
			break;
			case "summary_laboratory":
			case "summary_obstetrics":
			case "summary_gynae":
			case "summary_consultation":
				$returned_value = $this->_summary_consultation();
			break;
			case 'display_app_reports_full_view':
			case 'display_all_reports_full_view':
			case 'display_all_reports_full_view2':
				$returned_value = $this->_display_all_reports_full_view();
			break;
			case 'new_bill2':
			case "edit_bills2":
			case 'capture_payment2':
				$returned_value = $this->_capture_payment2();
			break;
			case "display_home_menu":
			case "display_system_menu":
			case "display_home_menu_edms":
			case "display_inventory_menu":
			case "display_search_engine":
			case "display_frontend_inventory":
			case "display_frontend_hospital":
			case "display_dashboard_hospital":
			case "active_tab_and_display_menu":
			case "display_report_menu":
			case "reports_center":
			case "get_report_filter_form":
			case "display_report":
				$returned_value = $this->_active_tab_and_display_menu();
			break;
			case "past_records_report":
				$returned_value = $this->_past_records_report();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _past_records_report(){
			
			if( ! ( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid Patient</h4>Please select a patient first';
				return $err->error();
			}
			$customer = $_POST["id"];
			
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = '#' . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$return = array();
			
			$filename = "summary-consultation";
			
			//1. get last patient activity
			$app = new cAppointment2();
			
			$query = "SELECT `".$app->table_fields["date"]."` as 'date' FROM `".$this->class_settings['database_name']."`.`".$app->table_name."` WHERE `".$app->table_name."`.`record_status` = '1' AND `".$app->table_name."`.`".$app->table_fields["customer"]."` = '".$customer."' ORDER BY `".$app->table_fields["date"]."` DESC LIMIT 2 ";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $app->table_name ),
			);
			$sql = execute_sql_query($query_settings);
			
			$start_date = 0;
			$end_date = 0;
			if( isset( $sql[0]['date'] ) ){
				$end_date = convert_date_to_timestamp( date("Y-m-d", doubleval( $sql[0]['date'] ) ), 2 );
			}
			
			if( isset( $sql[1]['date'] ) ){
				$start_date = convert_date_to_timestamp( date("Y-m-d", doubleval( $sql[1]['date'] ) ), 2 );
			}
			
			$table_of_content = array();
			
			if( $end_date ){
				//get table of contents
				$tables = get_hospital_medical_record_table_of_content();
				foreach( $tables as $table => $label ){
					
					$cl = 'c'.ucwords( $table );
					
					if( class_exists( $cl ) ){
						$cls = new $cl();
						
						$query = "SELECT `id` FROM `".$this->class_settings['database_name']."`.`".$cls->table_name."` WHERE `".$cls->table_name."`.`record_status` = '1' AND `".$cls->table_name."`.`".$cls->table_fields["customer"]."` = '".$customer."' AND `".$cls->table_name."`.`".$cls->table_fields["date"]."` BETWEEN ".$start_date." AND " . $end_date;
						
						$query_settings = array(
							'database'=>$this->class_settings['database_name'],
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'SELECT',
							'set_memcache' => 1,
							'tables' => array( $cls->table_name ),
						);
						$sql = execute_sql_query($query_settings);
			
						if( isset( $sql[0]["id"] ) && $sql[0]["id"] ){
							$table_of_content[ $table ] = array(
								"action" => "?action=". $table . "&todo=show_view&customer=" . $customer . "&start_date=" . $start_date . "&end_date=" . $end_date,
								"id" => $sql,
								"label" => $label,
							);
						}
						
					}
				}
			}
			
			$d3["table_of_content"] = $table_of_content;
			$d3["end_date"] = $end_date;
			$d3["start_date"] = $start_date;
			$d3["customer"] = $customer;
			$this->class_settings[ 'data' ] = $d3;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->table_package.'/'.$this->table_name.'/'.$filename.'.php' );
			$returning_html_data = $this->_get_html_view();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = $handle;
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		protected function _active_tab_and_display_menu(){
			$container = "#dash-board-main-content-area";
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$data_final = array();
			$data = array();
			
			$error_msg = '';
			$html = '';
			$e_type = 'error';
			
			$filename = 'display-manager';
			
			$handle = $container;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$data[ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			if( isset( $_POST["container"] ) && $_POST["container"] ){
				$container = $_POST["container"];
			}
			
			if( isset( $this->class_settings["force_password_change"] ) && $this->class_settings["force_password_change"] ){
				$us = new cUsers();
				$us->class_settings = $this->class_settings;
				$us->class_settings["html_replacement_selector"] = str_replace( '#', '', $container );
				$us->class_settings["action_to_perform"] = 'display_my_password_change';
				return $us->users();
			}
			
			$replacement_one = '';
			$replacement_one_selector = '';
			
			$params = array();
			$menu_container = isset( $_GET["menu_container"] )?$_GET["menu_container"]:'';
			$params["current_tab"] = isset( $_GET["current_tab"] )?$_GET["current_tab"]:'';
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$action_to_perform2 = $this->class_settings["action_to_perform"];
			
			$access = get_accessed_functions();
			$super = 0;
			if( ! is_array( $access ) && $access == 1 ){
				$super = 1;
			}
			
			$db_todo = 'display_parish_dashboard';
			
			$data["type"] = isset( $_GET["type"] )?$_GET["type"]:'';
			$data["current_tab"] = $params["current_tab"];
			$data["action_to_perform"] = $action_to_perform;
			$default_table_package = $this->table_package;
			
			switch( $action_to_perform ){
			case "display_frontend_inventory":
				$action_to_perform = 'display_frontend_hospital';
				$action_to_perform2 = 'display_frontend_hospital';
				$this->class_settings[ 'action_to_perform' ] = $action_to_perform;
				$this->table_package = 'hospital';
			break;
			}
			
			switch( $action_to_perform ){
			case "display_inventory_menu":
			
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				$js[] = '$.fn.cCallBack.activateSidebarMenu';
				
				$container2 = 'inventories-asset-center';
				$data["container"] = $container2;
				
				switch( $action_to_perform ){
				case "display_inventory_menu":
					$single_store = '';
					switch( $data["current_tab"] ){
					case "hr2":
					case "hr":
						if( ! ( defined("IPIN_MULTIPLE_HR_LOCATION") && IPIN_MULTIPLE_HR_LOCATION ) ){
							$single_store = 1;
							break;
						}
					default:
						if( get_single_store_settings() ){
							$single_store = get_single_store_settings();
						}
					break;
					}
					
					if( $single_store ){
						$pr = get_project_data();
						$data["stores"] = '&nbsp;';
						$data["single_store"] = get_main_store();
						
					}else{
						$stores = new cStores();
						$stores->class_settings = $this->class_settings;
						
						$stores->class_settings["action_to_perform"] = 'get_stores_list4';
						$st = $stores->stores();
						
						if( isset( $st["html_replacement"] ) && $st["html_replacement"] ){
							$data["stores"] = '<br /><div id="current-store-container">' . $st["html_replacement"] . '</div><button class="btn-sm btn blue custom-single-selected-record-button" style="display:none;" id="current-store-button" action="?module=&action=stores&todo=change_store" title="Go">Go</button>';
						}
					}
					
					//$error_msg = '<h4>Access Denied</h4>';
				break;
				}
				
				switch( $action_to_perform2 ){
				case "display_inventory_menu":
				case "display_nsr_menu":
				case "display_report_menu":
					$filename = str_replace( "_", "-", $action_to_perform2 );
				break;
				}
				
			break;
			case "display_system_menu":
			case "active_tab_and_display_menu":
			case "display_parish_tab":
			case "display_home_menu":
			case "display_home_menu_edms":
			case "display_report_menu":
			case "display_home_tab":
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				$js[] = '$.fn.cCallBack.activateSidebarMenu';
				
				$data["container"] = 'inventories-asset-center';
				
				switch( $action_to_perform ){
				case "display_parish_tab":
					$parish_label = 'Parish';
					if( function_exists( "get_catholic_parish_label_settings" ) ){
						$parish_label = get_catholic_parish_label_settings();
					}
					
					$app_ref = get_current_customer( 'parish' );
					
					$container2 = 'parish-container';
					$form_action = '?action=parish&todo='.$db_todo.'&container=' . $container2 . '&form_id=' . $container2 . '-form';
					
					$skip_search = 1;
					
					$js[] = '$.fn.cCallBack.submitVisibleForm2';
					$form_class = 'refresh-form2';
					
					if( ! $skip_search ){
						if( $app_ref ){
							$js[] = 'nwCustomers.search';
						}
					}
					
					$cname = '';
					$cid = '';
					if( $app_ref ){
						$cname = get_name_of_referenced_record( array( "id" => $app_ref, "table" => "parish" ) );
						if( $cname ){
							$cid = $app_ref;
						}
					}
					
					$new_customer_action = 'new_customer_popup_form';
					
					$data[ "parish" ] = $cid;
					$data[ "version" ] = 2;
					$data[ "container" ] = $container2;
					$data[ "form_id" ] = $container2 . '-form';
					$data[ "form_class" ] = $form_class;
					
					$data["hide_date_button"] = 1;
					$data["table"] = $this->table_name;
					$data["class"] = 'col-md-4';
					$data["select_field"][] = array(
						"class" => 'refresh-form2 refresh-form select2',
						"action" => '?module=&action=parish&todo=get_select2',
						"placeholder" => 'Select ' . $parish_label,
						"name" => 'parish',
						"value" => $cid,
						"value_label" => $cname,
						"refresh-form" => 'form#' . $container2 . '-form',
						"refresh_form" => 'form#' . $container2 . '-form',
					);
					$data["form_action"] = $form_action;
					
					$data["submit_button_class"] = " dark ";
					$data["submit_button_label"] = "GO";
				break;
				case "display_home_menu":
				case "display_home_menu_edms":
					$not = new cNotifications();
					$not->class_settings = $this->class_settings;

					$sh = new cShare();
					$sh->class_settings = $this->class_settings;
					$share_query = '';
					switch( $data["type"] ){
					case "workflow":
						$share_query = " AND `". $sh->table_fields[ 'reference_table' ] ."` = 'files' ";
					break;
					}
					
					$not->class_settings[ 'overide_select' ] = " COUNT(*) as 'count' ";
					$not->class_settings[ 'where' ] = " AND `". $not->table_fields[ 'target user' ] ."` = '". $this->class_settings[ 'user_id' ] ."' AND `". $not->table_fields[ 'status' ] ."` = 'unread' ";
					$d = $not->_get_records();
					$data[ 'notifications' ] = isset( $d[0]['count'] ) ? $d[0]['count'] : '';


					$sh->class_settings[ 'overide_select' ] = " COUNT(*) as 'count' ";
					$sh->class_settings[ 'where' ] = " AND `". $sh->table_fields[ 'share' ] ."` = '". $this->class_settings[ 'user_id' ] ."' AND `". $sh->table_fields[ 'status' ] ."` = 'unread' " . $share_query;
					$d = $sh->_get_records();
					$data[ 'comments' ] = isset( $d[0]['count'] ) ? $d[0]['count'] : '';
					
					
					if( defined("NWP_SHARED_ASSIGNMENT_COUNT") && NWP_SHARED_ASSIGNMENT_COUNT ){
						$sh->class_settings[ 'overide_select' ] = " COUNT(*) as 'count' ";
						$sh->class_settings[ 'where' ] = " AND `". $sh->table_fields[ 'share' ] ."` = '". $this->class_settings[ 'user_id' ] ."' AND `". $sh->table_fields[ 'type' ] ."` = 'assigned' ";
						$d = $sh->_get_records();
						$data[ 'assigned_to_me' ] = isset( $d[0]['count'] ) ? $d[0]['count'] : '';
						
						$sh->class_settings[ 'overide_select' ] = " COUNT(*) as 'count' ";
						$sh->class_settings[ 'where' ] = " AND `created_by` = '". $this->class_settings[ 'user_id' ] ."' AND `". $sh->table_fields[ 'type' ] ."` = 'assigned' ";
						$d = $sh->_get_records();
						$data[ 'assigned_by_me' ] = isset( $d[0]['count'] ) ? $d[0]['count'] : '';
					}


					// $cm = new cComments();
					// $cm->class_settings = $this->class_settings;

					// $cm->class_settings[ 'overide_select' ] = " COUNT(*) as 'count' ";
					// $cm->class_settings[ 'where' ] = " AND `". $cm->table_fields[ 'participant_id' ] ."` REGEXP '". $this->class_settings[ 'user_id' ] ."' ";
					// $d = $cm->_get_records();
					// $data[ 'training_participants' ] = isset( $d[0]['count'] ) ? $d[0]['count'] : '';
					switch( $data["type"] ){
					case "workflow":
						if( class_exists("cNwp_workflow") ){
							$f2 = new cNwp_workflow();
							$fx = $f2->load_class( array( 'class' => array( 'workflow' ), 'initialize' => 1 ) );
							$fx[ 'workflow' ]->class_settings = $this->class_settings;
							switch( $action_to_perform ){
								case 'display_home_menu_edms':
									$data['sub_container'] = 1;
									$action_to_perform2 = 'display_home_menu';
									$container = $handle;
								break;
								default:
									$data['refreshable_home'] = 1;
								break;
							}
							$fx[ 'workflow' ]->class_settings["d_values"] = $data;
							$fx[ 'workflow' ]->class_settings["action_to_perform"] = 'data_approval_dashboard';
							$rh = $fx[ 'workflow' ]->workflow();
							if( isset( $rh["html_replacement"] ) ){
								$data["content"] = $rh["html_replacement"];
								switch ($action_to_perform) {
									case 'display_home_menu_edms':
										$html = $rh["html_replacement"];
										unset( $data['content'] );
									break;
								}
								unset( $f2 );
								unset( $fx );
								unset( $rh );
							}else{
								return $rh;
							}
						}
					break;
					default:
						$this->class_settings["callback"][ $this->table_name ][ $action_to_perform ] = array(
							'id' => '-',
							'action' => '?action=files&todo=view_recent_report&app_manager_control=1&html_replacement_selector=recent-reports',
						);
					break;
					}

					
				break;
				}
				
				switch( $action_to_perform ){
				case "display_parish_tab":
					$filename = str_replace( "_", "-", $action_to_perform );
					
					$db_todo .= '_direct';
					
					$this->class_settings["callback"][ $this->table_name ][ $action_to_perform ] = array(
						'id' => $cid,
						'action' => '?action=parish&todo='. $db_todo .'&html_replacement_selector='. $container2 .'&current_tab=' . $params["current_tab"].'&menu_container=second-side-menu',
					);
					
				break;
				case "display_system_menu":
					$action_to_perform2 = "display_report_menu";
				break;
				}
				
				switch( $action_to_perform2 ){
				case "display_home_menu":
				case "display_report_menu":
					$filename = str_replace( "_", "-", $action_to_perform2 );
				break;
				}
				
			break;
			case "display_manage_appointment3":
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				$filename = 'display-manage-appointment-3';
				$js[] = 'nwAppointment.submitForm';
			break;
			case "display_frontend_hospital":
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				$filename = 'display-manager-hospital';
				$js[] = '$.fn.cCallBack.clickActiveTab';
				
				
				if( ! $super ){
					/* $key = 'a5ae06fe11454a32ae405212de7992a6';
					if( ! isset( $access[ $key ] ) ){
						$r = get_record_details( array( "id" => $this->class_settings["user_id"], "table" => "users" ) );
						$my_parish = isset( $r["parish"] )?$r["parish"]:'';
						set_current_customer( array( 'parish' => $my_parish ) );
					} */
				}
			break;
			case "reports_center":
				$filename = 'reports-center';
				$container = $handle;
				$e = array();
				
				if( isset( $_GET["report"] ) && $_GET["report"] ){
					$rt = get_ims_report_types();
					
					if( isset( $rt[ $_GET["report"] ]["title"] ) ){
						$e["report"] = $_GET["report"];
						$e["report_title"] = $rt[ $_GET["report"] ]["title"];
						$e["options"] = $rt[ $_GET["report"] ]["options"];
						
						$js[] = 'prepare_new_record_form_new';
					}else{
						$error_msg = '<h4>Invalid Report Type</h4>';
					}
				}else{
					$error_msg = '<h4>Undefined Report Type</h4>';
				}
				
				$data[ 'event' ] = $e;
			break;
			case "get_report_filter_form":
			case "display_report":
				if( isset( $this->class_settings[ 'data' ] ) && ! empty( $this->class_settings[ 'data' ] ) ){
					$data = $this->class_settings[ 'data' ];
				}
				$filename = 'reports-filter';
				$container = $handle;
				$e = array();
				
				if( isset( $_POST["report"] ) && $_POST["report"] && isset( $_POST["report_type"] ) && $_POST["report_type"] ){
					$rt1 = get_ims_report_types( array( "report" => $_POST["report"], "report_type" => $_POST["report_type"] ) );
					
					if( isset( $rt1[ $_POST["report"] ][ $_POST["report_type"] ]["title"] ) ){
						$rt = $rt1[ $_POST["report"] ][ $_POST["report_type"] ];
						
						$e["report_type"] = $_POST["report_type"];
						$e["report"] = $_POST["report"];
						$e["report_title"] = $rt["title"];
						
						// print_r( $action_to_perform );exit;
						switch( $action_to_perform ){
						case "display_report":
							$js = array();
							
							$filename = 'display-reports.php';
							
							if( isset( $rt["settings"] ) ){
								$r = $this->_display_reporting_view(); 

								if( isset( $r[ 'html_replacement' ] ) && $r[ 'html_replacement' ] ){
									$html = $r[ 'html_replacement' ];
									
									$r[ 'html_replacement_selector' ] = $container;
									$r[ 'javascript_functions' ][] = 'nwAll_reports.showReport';
									$r[ 'javascript_functions' ][] = 'prepare_new_record_form_new';
									
									return $r;
								}else{
									$error_msg = '<h4>Unable to Get Report</h4>';
								}
							}else{
								$error_msg = '<h4>Invalid Report Settings</h4>';
							}
							
							$js[] = 'nwAll_reports.showReport';
						break;
						default:
							if( isset( $rt["settings"] ) ){
								$e["settings"] = $rt["settings"];
							}
							
							if( isset( $rt["settings"]["new_tab"] ) ){
								$data_final["new_tab"] = $rt["settings"]["new_tab"];
								$js[] = 'nwAll_reports.showNewTab';
								
								$html = 'Loading...';
							}else{
								$js[] = 'prepare_new_record_form_new';
							}
							

							$this->class_settings["callback"][ $this->table_name ][ $action_to_perform ] = array(
								'id' => $e["report_type"],
								'action' => '?action=files&todo=view_recent_report&reference='. md5( $this->class_settings[ 'user_id' ] ) .'&report_type='. $_POST["report_type"] .'&app_manager_control=1&html_replacement_selector=' . ( isset( $_GET[ 'html_replacement_selector2' ] ) ? $_GET[ 'html_replacement_selector2' ] : '' ),
							);
						break;
						}
						
					}else{
						$error_msg = '<h4>Invalid Report Type</h4>';
					}
				}else{
					$error_msg = '<h4>Undefined Report Type</h4>';
				}
				
				$data["event"] = $e;
			break;
			default:
				$js[] = 'nwCustomers.refreshAcitveTab';
			break;
			}
			
			switch( $action_to_perform ){
			case "display_frontend_inventory":
			case "display_frontend_hospital":
				$this->table_package = $default_table_package;
				//20-jun-23: 2echo
				if( ( defined("NWP_SHOW_ADMIN_SELECTION_OPTION") && NWP_SHOW_ADMIN_SELECTION_OPTION ) ){
					if( $super || isset( $access[ "accessible_functions" ]["frontend_tabs.admin-tab"] ) ){
						$data["sel_site"] = 1;
						if( ( isset( $_SESSION["ucert"]["_sel_site"] ) && $_SESSION["ucert"]["_sel_site"] ) ){
							$data["_sel_site"] = 0;
						}else{
							$data["stores"] = array();
							$data["stores"][] = array( "id" => "home", "site" => 1, "text" => "EDMS Front Office" );
							$data["stores"][] = array( "id" => "admin-tab", "site" => 1, "text" => "Admin Area" );
							$filename = 'select-store.php';
						}
					}
				}
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			$this->class_settings[ 'data' ] = $data;
			if( ! $html ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->table_package.'/'.$this->table_name.'/'.$filename );
				
				$returning_html_data = $this->_get_html_view();
			}else{
				$returning_html_data = $html;
			}
			
			return array(
				'html_replacement_selector' => $container,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
				'data' => $data_final,
			);
		}
		
		protected function _capture_payment2(){
			
			switch ( $this->class_settings['action_to_perform'] ){
			case "edit_bills2":
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Customer</h4>Please check your customer name';
					return $err->error();
				}
				$id = $_POST["id"];
				
				$this->class_settings["action_to_perform"] = 'view_version2_report';
				
				$sales = new cSales();
				$this->table_name = $sales->table_name;
				
				$sales_table_fields = array();
				foreach( $sales->table_fields as $k => $v ){
					switch( $k ){
					case "date":
						$sales_table_fields[ $k ] = $v;
					break;
					}
				}
				
				$this->table_fields = $sales_table_fields;
				
				$_POST["hidden_field"]["customer"] = $id;
				
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				}
				$this->class_settings[ 'data' ]["search_todo"] = 'search_report2';
				//$this->class_settings[ 'data' ][ 'form' ] = $this->_get_html_view();
				
				$return = $this->_display_app_view2();
				
				if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
					$return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
					$return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
				}
				
				return $return;
			break;
			case 'new_bill2':
			case 'capture_payment2':
				
				$action ='display_cashier_screen_all_stores';
				$cart = new cCart();
				$cart->class_settings = $this->class_settings;
				$cart->class_settings["action_to_perform"] = $action;
				$return = $cart->cart();
				
				$handle = "#dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
					$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				$return['html_replacement_selector'] = $handle;
				$return['do_not_reload_table'] = 1;
				
				if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
					$return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
					$return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
				}
				
				return $return;
			break;
			}
		}
		
		protected function _display_all_reports_full_view(){
			//$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-reports-full-view' );
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->table_package.'/'.$this->table_name.'/display-all-reports-full-view' );
			
			$this->class_settings[ 'data' ][ 'report_action' ] = "display_reporting_view";
			
			$this->class_settings[ 'data' ][ 'report_type2' ] = get_items_grouped_goods();
			$this->class_settings[ 'data' ][ 'selected_option2' ] = "all-items";
			
			$this->class_settings[ 'data' ][ 'report_type3' ] = get_report_periods_without_weeks();
			$this->class_settings[ 'data' ][ 'selected_option3' ] = "monthly";
			
			$this->class_settings[ 'data' ][ 'report_type5' ] = get_all_medical_reports2();
			$this->class_settings[ 'data' ][ 'selected_option5' ] = 'services_rendered_summary';
			$this->class_settings[ 'data' ][ "start_date" ] = date("U");
			//$this->class_settings[ 'data' ][ 'selected_option5' ] = 'customers_owing_summary';
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'display_app_reports_full_view':
				$this->class_settings[ 'data' ][ 'report_action' ] = "generate_app_sales_report";
			break;
			}
			
			$returning_html_data = $this->_get_html_view();
			
			$handle = "#dash-board-main-content-area";
			if( isset( $_POST["container"] ) && $_POST["container"] ){
				$handle = $_POST["container"];
			}
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			return array(
				'html_replacement_selector' =>  $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwResizeWindow.resizeWindowHeight' ) 
			);
		}
		
		protected function _summary_consultation(){
			$data = array();
			$filename = str_ireplace( '_', '-', $this->class_settings["action_to_perform"] );
			$filename = "summary-consultation";
			
			$_POST["report_type"] = $this->class_settings["action_to_perform"];
			$return = $this->_display_reporting_view();
			
			if( ! ( isset( $return["report_data"] ) && ! empty( $return["report_data"] ) ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No '.ucwords( $this->label ).' Record(s)</h4>';
				$return = $err->error();
				$returning_html_data = $return["html"];
			}else{
				$this->class_settings[ 'data' ] = $return;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->table_package.'/'.$this->table_name.'/'.$filename.'.php' );
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		protected function _obstetrics_services_record_report(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Report Data</h4>Please refresh the app and try again';
				return $err->error();
			}
			
			$record_data = get_record_details( array( "id" => $_POST["id"], "table" => "obstetrics_services_record" ) );
			if( isset( $record_data[ "customer" ] ) && $record_data[ "customer" ] ){
				$_POST["customer"] = $record_data[ "customer" ];
				$customer_data = get_record_details( array( "id" => $record_data[ "customer" ], "table" => "customers" ) );
				$this->class_settings[ "where_extra" ] = " AND `id` = '".$record_data[ "id" ]."' ";
			}
			
			$_POST["report_type"] = "obstetrics_services_record";
			$return = $this->_display_reporting_view();
			
			if( isset( $return[ 'html_replacement' ] ) && $return[ 'html_replacement' ] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/zero-out-negative-budget' );
				$this->class_settings[ 'data' ]['html'] = $return[ 'html_replacement' ];
				$this->class_settings[ 'data' ]['html_title'] = "Obstetrics Services Record: " . ( isset( $customer_data["name"] )?$customer_data["name"]:'' );
				$this->class_settings[ 'data' ]['modal_dialog_style'] = " width:95%; ";
				
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'do_not_reload_table' => 1,
					'html_prepend' => $returning_html_data,
					'html_prepend_selector' => "#dash-board-main-content-area",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "set_function_click_event" ),
				);
			}
			return $return;
		}
		
		protected function _display_app_view2(){
			$c = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			//$c = get_record_details( array( "id" => get_current_customer(), "table" => "customers" ) );
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_app_manager':
			case 'view_version2_report':
				$this->label = '';
				if( ! ( isset( $this->class_settings[ 'data' ]["search_todo"] ) && $this->class_settings[ 'data' ]["search_todo"] ) ){
					$this->class_settings[ 'data' ]["search_todo"] = 'search_report';
				}
				
				if( isset( $_POST["hidden_field"] ) && is_array( $_POST["hidden_field"] ) ){
					$this->class_settings[ 'data' ]["hidden_table_fields"] = $_POST["hidden_field"];
				}
				
				$js[] = 'nwCustomers.loadForm';
			break;
			default:
				$this->class_settings[ 'data' ]["parent_class1"] = 'col-md-2';
				$this->class_settings[ 'data' ]["parent_class2"] = 'col-md-10';
				
				$this->class_settings[ 'data' ]["submit_button_class"] = 'btn-block';
				$this->class_settings[ 'data' ]["hide_new_button"] = 1;
				$this->class_settings[ 'data' ]["form_action"] = '?action='.$this->table_name.'&todo=display_reporting_view';
				
				$this->class_settings[ 'data' ]["class"] = 'col-md-12 row-spacing';
				
				$this->class_settings[ 'data' ]["select_field"][] = array(
					"class" => '',
					"type" => 'select',
					"placeholder" => 'Select Client',
					"name" => 'report_type',
					"value" => isset( $c["id"] )?$c["id"]:'',
					"value_label" => isset( $c["name"] )?$c["name"]:'',
					"option" => get_all_medical_reports(),
				);
				
				$this->class_settings[ 'data' ]["select_field"][] = array(
					"class" => 'refresh-form select2 remote-loading',
					"action" => '?module=&action=customers&todo=get_customers_select2',
					"placeholder" => 'Select Client',
					"name" => 'customer',
					"value" => isset( $c["id"] )?$c["id"]:'',
					"value_label" => isset( $c["name"] )?$c["name"]:'',
					"refresh_form" => 'form#' . $this->table_name,
				);
				
				$this->class_settings[ 'data' ]["new_reference_value"] = isset( $c["id"] )?$c["id"]:'';
				
			break;
			}
			
			$return = $this->_display_app_view();
			$return["javascript_functions"] = $js;
			
			return $return;
		}
		
		protected function _display_reporting_view_popup(){
			$f = array(
				"report_type", "report_status", "start_date", "end_date", "gstore", "customer", "staff", "hmo", "investigation_status", "type", "appointment2_reason"
			);
			
			foreach( $f as $fv ){
				if( isset( $_GET[ $fv ] ) ){
					
					$kfv = $fv;
					
					switch( $fv ){
					case "gstore":
						$kfv = "store";
					break;
					}
					
					$_POST[ $kfv ] = $_GET[ $fv ];
				}
			}
			
			$modal = 0;
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				$modal = 1;
			}
			
			$_GET["modal"] = 1;
			$r = $this->_display_reporting_view();
			if( isset( $r["html_replacement"] ) ){
				
				if( $modal ){
					$r["html_replacement_selector"] = '#modal-replacement-handle';
					return $r;
				}else{
					$this->class_settings["modal_dialog_style"] = " width:65%; ";
					return $this->_launch_popup( $r["html_replacement"], '#dash-board-main-content-area', array() );
				}
			}
		}
		
		protected function _display_reporting_view(){
			if( ! ( isset( $_POST["report_type"] ) && $_POST["report_type"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Report Type</h4>Please select a report type';
				return $err->error();
			}
				
			$report_type = $_POST["report_type"];
			$data = array();
			$all_table_fields = array();
			$all_table_label = array();
			$return_data = 0;
			$show_print = 1;
			$date_filter = '';
			
			$selector = "#".$this->table_name."-record-search-result";
			
			$js = array( 'set_function_click_event' );
			
			$start_date_timestamp = 0;
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$start_date_timestamp = convert_date_to_timestamp( $_POST["start_date"], 1 );
			}
			$start_date = $start_date_timestamp;
			
			$end_date_timestamp = 0;
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$end_date_timestamp = convert_date_to_timestamp( $_POST["end_date"], 2 );
			}
			$end_date = $end_date_timestamp;
			
			$report_status = '';
			if( ( isset( $_POST["report_status"] ) && $_POST["report_status"] ) ){
				$report_status = $_POST["report_status"];
			}
			
			$customer = '';
			if( ( isset( $_POST["customer"] ) && $_POST["customer"] ) ){
				$customer = $_POST["customer"];
			}
			
			if( ! $customer && ( isset( $_POST["file_number"] ) && $_POST["file_number"] ) ){
				$customer = $_POST["file_number"];
			}
			
			$store = '';
			if( ( isset( $_POST["store"] ) && $_POST["store"] != 'all' && $_POST["store"] ) ){
				$store = $_POST["store"];
			}
			
			$return_view = isset( $_POST["return_view"] )?$_POST["return_view"]:0;
			$return_data = isset( $_POST["return_data"] )?$_POST["return_data"]:0;
			$return_class_action = isset( $_POST["return_class_action"] )?$_POST["return_class_action"]:0;
			
			$report_title = isset( $_POST["report_title"] )?$_POST["report_title"]:'';
			$table = isset( $_POST["action"] )?$_POST["action"]:'';
			$action = isset( $_POST["todo"] )?$_POST["todo"]:'';
			
			$staff_filter_names = array();
			$staff_filter = array();
			if( ( isset( $_POST["staff"] ) && $_POST["staff"] ) ){
				$staff_filter = explode( ",", $_POST["staff"] );
				
				foreach( $staff_filter as & $sv ){
					$staff_filter_names[] = get_name_of_referenced_record( array( "id" => $sv, "table" => "users" ) );
					$sv = "'". $sv ."'";
				}
			}
			
			$table_new = '';
			switch( $report_type ){
			case "investigation_summary":
				$table_new = 'investigation';
			break;
			case "patient_summary":
			case "patient_details":
				$action = 'view_version2_report';
				$customer = '';
				$table_new = 'customers';
			break;
			}
			
			if( $customer ){
				$_POST["hidden_field"][ "customer" ] = $customer;
			}
			if( $start_date ){
				$_POST["hidden_field"][ "start_date" ] = $_POST["start_date"];
			}
			if( $end_date ){
				$_POST["hidden_field"][ "end_date" ] = $_POST["end_date"];
			}
			
			if( $table_new ){
				$rt = "c" . ucwords( $table_new );
				$class = new $rt();
				$class->class_settings = $this->class_settings;
				
				$class->class_settings["action_to_perform"] = 'view_version2_report';
				
				$class->class_settings[ 'html_replacement_selector' ] = "data-table-section";
				return $class->$table_new();
			}
			
			$returning_html_data = '';
			switch( $report_type ){
			case "obstetrics_services_record":
				if( ! ( isset( $_POST["customer"] ) && $_POST["customer"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = 'Invalid Customer';
					return $err->error();
				}
				
				$this->class_settings[ 'customer' ] = $_POST["customer"];
				
				//get query
				$table = 'obstetrics_services_record';
				$action = 'search_customer_call_log';
				
				$this->class_settings['return_data'] = 1;
				
				$rt = "c" . ucwords( $table );
				$class = new $rt();
				$class->class_settings = $this->class_settings;
				$class->class_settings["action_to_perform"] = $action;
				$ob_record = $class->$table();
				
				$related = array(
					'obstetric_history',
					'preventive_treatment',
					'past_pregnancy_outcome',
					'post_operative_note',
					'operation_register',
					'antenatal_care_progress',
					'labour_and_peuperium_details',
					'labour_outcome',
				);
				
				if( isset( $ob_record[0][ 'id' ] ) && $ob_record[0][ 'id' ] ){
					$reference = $ob_record[0][ 'id' ];
					$ob_value = $ob_record[0];
					
					//get details of each
					foreach( $related as $related_table ){
						$rt = "c" . ucwords( $related_table );
						$class = new $rt();
						
						switch( $related_table ){
						case "past_pregnancy_outcome":
							$class->default_reference = 'customer';
							$_POST[ $class->default_reference ] = $this->class_settings[ 'customer' ];
						break;
						default:
							$class->default_reference = 'reference';
							$_POST[ $class->default_reference ] = $reference;
						break;
						}
						
						$class->class_settings = $this->class_settings;
						$class->class_settings["action_to_perform"] = $action;
						$data[ $reference ][ $related_table ] = $class->$related_table();
						
						$all_table_fields[ $related_table ] = $class->table_fields;
					}
					
					$data[ $reference ][ 'obstetrics_services_record' ] = $ob_value;
				}
				
			break;
			case "summary_laboratory":
			case "summary_obstetrics":
			case "summary_gynae":
			case "summary_consultation":
				if( ! ( isset( $_POST["customer"] ) && $_POST["customer"] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = 'Invalid Customer';
					return $err->error();
				}
				
				$this->class_settings[ 'customer' ] = $_POST["customer"];
				
				//get query
				$action = 'search_customer_call_log';
				
				switch( $this->class_settings["action_to_perform"] ){
				case "summary_consultation":
					$related = array(
						'appointment',
						'vitals',
						'notes',
						'diagnosis',
					);
				break;
				case "summary_obstetrics":
					$related = array(
						'appointment',
						'gynae_history',
						'gynae_consultation',
						'husband_history',
						'vitals',
						'diagnosis',
					);
				break;
				case "summary_gynae":
					$related = array(
						'appointment',
						'gynae_history',
						'gynae_consultation',
						'husband_history',
						'vitals',
						'diagnosis',
					);
				break;
				case "summary_laboratory":
					$related = array(
						'appointment',
						'notes',
						'diagnosis',
					);
				break;
				}
				
				//get details of each
				foreach( $related as $related_table ){
					$rt = "c" . ucwords( $related_table );
					$class = new $rt();
					
					switch( $related_table ){
					case "past_pregnancy_outcome":
					break;
					default:
						//$class->default_reference = 'customer';
						$_POST[ $class->default_reference ] = $this->class_settings[ 'customer' ];
					break;
					}
					
					$class->class_settings = $this->class_settings;
					$class->class_settings['return_data'] = 1;
					$class->class_settings["action_to_perform"] = $action;
					$data[ $related_table ] = $class->$related_table();
					
					$all_table_label[ $related_table ] = $class->label;
					$all_table_fields[ $related_table ] = $class->table_fields;
				}
				
				$return_data = 1;
				$this->class_settings[ 'data' ][ 'customer' ] = $this->class_settings[ 'customer' ];
			break;
			case "services_rendered_summary":
				$this->class_settings["action_to_perform"] = 'view_version2_report';
				$this->class_settings[ 'html_replacement_selector' ] = "data-table-section";
				
				$sales = new cSales();
				$this->table_name = $sales->table_name;
				$this->table_fields = $sales->table_fields;
				
				if( isset( $_POST["hidden_field"] ) && is_array( $_POST["hidden_field"] ) ){
					$this->class_settings[ 'data' ]["hidden_table_fields"] = $_POST["hidden_field"];
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->table_package.'/'.$sales->table_name.'/search-form-1.php' );
				$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
				$this->class_settings[ 'data' ][ 'search_todo' ] = 'search_report';
				
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				}
				
				$this->class_settings[ 'data' ][ 'form' ] = $this->_get_html_view();
				
				return $this->_display_app_view2();
			break;
			default:
				//remove later
				$selector = "#data-table-section";
				
				if( ! $table ){
					$table = $report_type;
				}
				
				if( ! $action ){
					$action = 'search_list';
				}
				
				$report_view = 'table_view';
				
				if( $report_title ){
					$title = $report_title;
				}else{
					$med_reports = get_all_medical_reports2();
					$title = isset( $med_reports[ $report_type ] )?$med_reports[ $report_type ]:'';
				}
				
				$subtitle = '';
				
				switch( $report_type ){
				case "nurse_report":
				case "nurse_duty_report":
					$table = "nurse_report";
					$report_view = $report_type;
					$selector = "#data-table-section";
				break;
				case "vitals_summary":
					$table = "vitals2";
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'search_list';
				break;
				case "consultation_summary":
					$table = "consultation";
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'get_data';
					//$action = 'search_list';
				break;
				case "operation_register_summary":
					//$table = "operation_register";
					$table = "operation_booking";
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					
					$action = 'get_data';
				break;
				case "delivery_register_summary":
					$table = "delivery_register";
					$selector = "#data-table-section";
					$report_view = $report_type;
				break;
				case "notes_summary":
					$table = "notes";
					$selector = "#data-table-section";
					$report_view = $report_type;
				break;
				case "admission_summary":
					$table = "admission2";
					$selector = "#data-table-section";
					$report_view = $report_type;
				break;
				case "ward_round_summary":
					$table = "ward_round";
					$selector = "#data-table-section";
					$report_view = $report_type;
				break;
				case "investigation_food_handler_emailed":
				case "investigation_food_handler":
				case "investigation_analysis_patient":
				case "investigation_analysis_summary":
				case "investigation_analysis_staff":
				case "investigation_analysis_details":
				case "investigation_summary":
				case "lab_request_summary":
					$table = "investigation";
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'search_report';
				break;
				case "doctor_performance":
				case "doctor_financial_performance":
				case "general_financial_performance":
				case "general_financial_performance_summary":
					$table = "sales";
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'search_report';
				break;
				case "prescription_summary":
					$table = "prescription2";
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'get_data';
					//$action = 'search_list_prescription';
				break;
				case "drug_chart_summary":
					$table = "drug_chart";
					$selector = "#data-table-section";
					$report_view = $report_type;
				break;
				case "diagnosis_summary":
					$table = "diagnosis";
					$selector = "#data-table-section";
					$report_view = $report_type;
				break;
				case "visit_log_patient":
				case "treatment_log_summary":
				case "treatment_log":
				case "visit_log_summary":
				case "visit_log":
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'search_report';
					$table = "appointment2";
				break;
				case "diagnosis_report_summary_type":
				case "diagnosis_report_summary_sex":
				case "diagnosis_report_summary":
				case "diagnosis_report_rank":
				case "diagnosis_report_detailed":
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'search_report';
					$table = "consultation";
				break;
				case "appointment_list":
				case "appointment_performance":
				case "appointment_summary2":
				case "appointment_summary":
					$selector = "#data-table-section";
					$report_view = $report_type;
					
					$action = 'search_report';
					$table = "calendar";
					
					$diff = ( $end_date - $start_date ) / (3600 * 24);
					
					switch( $report_type ){
					case "appointment_summary2":
						$show_print = 0;
						if( $diff > 0 && $diff < 15 ){
							$date_filter = 'd-M-Y';
						}
					break;
					case "appointment_performance":
						$show_print = 0;
						$date_filter = 'd-M-Y';
						
						$today = mktime( 0, 0, 0, intval( date("m") ), date("j"), date("Y") );
						
						$start_date = $today - ( 86400 * 3 );	//1 day ago
						$end_date = $today + ( 86400 * 3 );	//2 days
						
						$start_date_timestamp = $start_date;
						$end_date_timestamp = $end_date;
					break;
					case "appointment_summary":
						if( $diff > 0 && $diff < 8 ){
							$date_filter = 'd-M-Y';
						}
					break;
					}
					
				break;
				case "appointment_status":
					$selector = "#data-table-section";
					$table = "appointment2";
					
					$report_view = 'appointment_report';
					$action = 'search_list_report';
				break;
				case "cash_book_transactions_analysis_income":
				case "cash_book_transactions_analysis_bank_income":
				case "customers_owing_summary":
					$finances = new cTransactions();
					$finances->class_settings = $this->class_settings;
					
					$_POST["report_type"] = 'customers_debtor_list';
					
					switch( $report_type ){
					case "cash_book_transactions_analysis_bank_income":
					case "cash_book_transactions_analysis_income":
						$_POST["report_type"] = $report_type;
					break;
					}
					
					$finances->class_settings["report_title"] = $title;
					$finances->class_settings["action_to_perform"] = "generate_sales_report";
					return $finances->transactions();
				break;
				}
				
				$op = array();
				$subtitles = array();
				
				if( isset( $_POST["hmo"] ) && $_POST["hmo"] ){
					$op["hmo"] = strtoupper( get_name_of_referenced_record( array( "id" => $_POST["hmo"], "table" => "hmo" ) ) );
					$subtitles[] = $op["hmo"];
				}
				
				if( isset( $customer ) && $customer ){
					$op["customer"] = strtoupper( get_name_of_referenced_record( array( "id" => $customer, "table" => "customers" ) ) );
					$subtitles[] = $op["customer"];
				}
				
				if( isset( $_POST["investigation_status"] ) && $_POST["investigation_status"] ){
					$op["investigation_status"] = strtoupper( get_select_option_value( array( "id" => $_POST["investigation_status"], "function_name" => "get_hospital_investigation_status" ) ) );
					
					$subtitles[] = $op["investigation_status"];
				}
				
				if( isset( $_POST["appointment2_reason"] ) && $_POST["appointment2_reason"] ){
					$op["appointment2_reason"] = $_POST["appointment2_reason"];
					
					$subtitles[] = strtoupper( get_select_option_value( array( "id" => $op["appointment2_reason"], "function_name" => "get_appointment_types3" ) ) );
				}
				
				$op[ 'date_filter' ] = $date_filter;
				$op[ 'end_date' ] = date("Y-m-d", $end_date_timestamp );
				$op[ 'end_date_timestamp' ] = $end_date_timestamp;
				$op[ 'start_date' ] = date("Y-m-d", $start_date_timestamp );
				$op[ 'start_date_timestamp' ] = $start_date_timestamp;
				$op[ 'report_status' ] = $report_status;
				$op[ 'report_type' ] = $report_type;
				
				if( $return_data ){
					$this->class_settings['return_data'] = $return_data;
				}

				$rt = "c" . ucwords( $table );
				$class = new $rt();
				$class->class_settings = $this->class_settings;
				
				if( $customer ){
					$class->class_settings['where'] = " AND `".$class->table_fields["customer"]."` = '".$customer."' ";
				}
				$class->class_settings["customer_filter"] = $customer;
				$class->class_settings["store_filter"] = $store;
				$class->class_settings["staff_filter"] = $staff_filter;
				$class->class_settings["end_date_timestamp"] = $end_date_timestamp;
				$class->class_settings["start_date_timestamp"] = $start_date_timestamp;
				
				$class->class_settings["report_title"] = $title;
				
				$class->class_settings["other_params"] = $op;
				$class->class_settings["report_type"] = $report_type;
				$class->class_settings["action_to_perform"] = $action;
				$class->class_settings[ 'data' ]["report_reference_key"] = md5( $this->class_settings[ 'user_id' ] );

				$data = $class->$table();
				
				if( $return_class_action ){
					return $data;
				}
				// print_r( $data );exit;
				
				$subtitle = implode( " - ", $subtitles );
				
				$this->class_settings[ 'data' ][ 'table_labels' ] = $table();
				$this->class_settings[ 'data' ][ 'table_fields' ] = $class->table_fields;
				$this->class_settings[ 'data' ][ 'table' ] = $table;
				$this->class_settings[ 'data' ][ 'store' ] = $store;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'report_subtitle' ] = $subtitle;
				$this->class_settings[ 'data' ][ 'show_print' ] = $show_print;
				
				$this->class_settings[ 'data' ][ 'other_params' ] = $op;
				
				switch( $report_type ){
				//case "investigation_food_handler_emailed":
				case "investigation_food_handler":
					$js[] = "prepare_new_record_form_new";
				break;
				case "appointment_status":
					/*
					$finances = new cTransactions();
					$finances->class_settings = $this->class_settings;
					
					$_POST["report_type"] = "customers_transactions";
					
					$finances->class_settings["return_data"] = 1;
					$finances->class_settings["action_to_perform"] = "generate_sales_report";
					$this->class_settings[ 'data' ][ 'finance_data' ] = $finances->transactions();
					*/
				break;
				case "prescription_summary":
				break;
				}
				
				$subtitle1 = "";
				
				if( $start_date ){
					$subtitle1 .= "From: <strong>" . date( "d-M-Y", doubleval( $start_date ) ) . "</strong> ";
				}
				
				if( $end_date ){
					$subtitle1 .= " To: <strong>" . date( "d-M-Y", doubleval( $end_date ) ) . "</strong>";
				}
				
				
				if( ! empty( $staff_filter_names ) ){
					$subtitle .= "<br />By: <strong>" . implode( ", ", $staff_filter_names ) . "</strong>";
				}
				
				if( $subtitle ){
					$subtitle1 = '<br />' . $subtitle1;
				}
				
				$this->class_settings[ 'data' ][ 'report_subtitle' ] = $subtitle . $subtitle1;
				
				$report_type = $report_view;
			break;
			}
			
			$this->class_settings[ 'data' ]["report_reference_key"] = md5( $this->class_settings[ 'user_id' ] );

			$this->class_settings[ 'data' ][ 'all_table_fields' ] = $all_table_fields;
			$this->class_settings[ 'data' ][ 'report_data' ] = $data;
			$this->class_settings[ 'data' ][ 'report_type' ] = $report_type;
			$this->class_settings[ 'data' ][ 'all_table_label' ] = $all_table_label;
			
			if( $return_data && ! $return_view ){
				return $this->class_settings[ 'data' ];
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.$this->table_package.'/'.$this->table_name.'/display-reporting-view' );
			$returning_html_data = $this->_get_html_view();
			
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$selector = '#' . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$return = array(
				'html_replacement_selector' => $selector,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js, 
			);
			
			if( isset( $this->class_settings["returned_data"]["highchart_data"] ) && isset( $this->class_settings["returned_data"]["highchart_container_selector"] ) ){
				$return["javascript_functions"] = array( 'nwResizeWindow.resizeWindowHeight', '$.fn.cProcessForm.activate_highcharts' );
				
				$return["highchart_data"] = $this->class_settings["returned_data"]["highchart_data"];
				$return["highchart_container_selector"] = $this->class_settings["returned_data"]["highchart_container_selector"];
			}
			
			return $return;
		}
		
	}
?>
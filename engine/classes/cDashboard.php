<?php
	/**
	 * dashboard Class
	 *
	 * @used in  				dashboard Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	dashboard
	 */

	/*
	|--------------------------------------------------------------------------
	| dashboard Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cDashboard{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'dashboard';
		public $label = 'Dashboard';
		
		public $table_fields = array();
		
        private $page_title = '';
        
		private $page_keywords = '';
        
		private $page_description = '';
        
		public $basic_data = array(
			'access_to_crud' => 0,
			'exclude_from_crud' => array( 'delete' => 1, 'create_new_record' => 1, 'edit' => 1 ),
			'special_actions' => array(
				/* 's_inform' => array(
					'label' => 'Notice Board',
					'todo' => 'get_nwp_info_dashboard_types',
					'type' => 'function',
				),*/
			),
		);
		
		function __construct(){
			
		}
	
		function dashboard(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError('000017');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cProduct_features.php';
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
			case 'setup_dashboard':
				$returned_value = $this->_setup_dashboard();
			break;
			case 'get_dashboard_notice_row':
				$returned_value = $this->_get_dashboard_notice_row();
			break;
			case 'get_dashboard_notification_task_row':
				$returned_value = $this->_get_dashboard_notification_task_row();
			break;
			case 'get_read_notification':
			case 'get_all_notification':
			case 'get_unread_notification':
				$returned_value = $this->_get_dashboard_notification();
			break;
			case 'get_pending_task':
			case 'get_completed_task':
			case 'get_all_task':
				$returned_value = $this->_get_dashboard_task();
			break;
			case 'update_user_profile':
				$returned_value = $this->_update_user_profile();
			break;
			case 'populate_dashboard':
				$returned_value = $this->_populate_dashboard();
			break;
			case 'display_app_more_menu':
				$returned_value = $this->_display_app_more_menu();
			break;
			case "customers_dashboard":
				$returned_value = $this->_dashboard();
			break;
			case "display_ssr_menu":
			case "display_nsr_menu":
			case "display_mis_menu":
				$returned_value = $this->_display_mis_menu();
			break;
			case "display_dashboard_mis":
			case "active_tab_and_display_menu":
				$returned_value = $this->_active_tab_and_display_menu();
			break;
			case "display_all_procurement_dashboard":
			case "display_procurement_dashboard":
			case "display_hr_dashboard":
				$returned_value = $this->_display_procurement_dashboard();
			break;
			case 'display_my_tree_view':
			case 'get_tree_children':
			case 'display_tree_view':
			case "data_approval_dashboard_metrics":
				$returned_value = $this->_data_approval_dashboard();
			break;
			case "add_information":
			case "view_information":
			case "start_generate_shared_report_bg":
			case "start_generate_shared_report":
				$returned_value = $this->_start_generate_shared_report();
			break;
			}
			
			return $returned_value;
		}
		
		public function add_information( $info = array() ){
			$this->class_settings[ 'action_to_perform' ] = "add_information";
			$this->_start_generate_shared_report( $info );
		}
		
		public function _start_generate_shared_report( $info = array() ){
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$return = array( "status" => "new-status" );
			
			$settings = array(
				'cache_key' => "dashboard-reports",
				'permanent' => true,
				'directory_name' => $this->table_name,
			);
			
			$mine = 0;
			switch( $action_to_perform ){
			case "view_information":
				if( isset( $_GET["mine"] ) && $_GET["mine"] ){
					$mine = 1;
					$settings["cache_key"] .= "-" . $this->class_settings["user_id"];
				}
			break;
			}
				
			$lbill = get_cache_for_special_values( $settings );
			
			switch( $action_to_perform ){
			case "view_information":
				$error_msg = '';
				if( isset( $_GET["flash"] ) && $_GET["flash"] && isset( $lbill["flash"][ $_GET["flash"] ] ) ){
					$lbill["flash"][ $_GET["flash"] ]["viewed"][ $this->class_settings["user_id"] ] = date("U");
					
					if( $mine ){
						unset( $lbill["flash"][ $_GET["flash"] ] );
					}
					
					$settings['cache_values'] = $lbill;
					set_cache_for_special_values( $settings );
				}
				
				if( isset( $_GET["redirect"] ) && $_GET["redirect"] ){
					$_GET = _convert_id_into_actions( rawurldecode( $_GET["redirect"] ), array( "delimiter1" => "&" ) );
					// print_r( $_GET );exit;
					
					if( isset( $_GET["action"] ) && $_GET["action"] && isset( $_GET["todo"] ) && $_GET["todo"] ){
						if( isset( $_GET["post_params"] ) && $_GET["post_params"] ){
							$_POST = json_decode( rawurldecode( $_GET["post_params"] ), true );
						}
						if( isset( $_GET["pid"] ) && $_GET["pid"] ){
							$_POST["id"] = $_GET["pid"];
						}
						$tb = $_GET["action"];
						$todo = $_GET["todo"];
						
						if( isset( $_GET["nwp_action"] ) && $_GET["nwp_action"] && isset( $_GET["nwp_todo"] ) && $_GET["nwp_todo"] ){
							$clp = 'c' . ucwords( strtolower( $_GET["action"] ) );
							$tb = $_GET["nwp_action"];
							$todo = $_GET["nwp_todo"];
							if( class_exists( $clp ) ){
								$ptb = new $clp();
								$ptb->class_settings = $this->class_settings;					 
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
					
					if( $error_msg ){
						echo $error_msg; exit;
					}else{
						$cl->class_settings = $this->class_settings;
						$cl->class_settings["action_to_perform"] = $todo;
						return $cl->$tb();
					}
					
				}
				
			break;
			case "add_information":
				if( isset( $info["data"] ) && is_array( $info["data"] ) && ! empty( $info["data"] ) ){
					foreach( $info["data"] as $ik => $iv ){
						$lbill["flash"][ $ik ] = $iv;
					}
					if( isset( $info["user_id"] ) && $info["user_id"] ){
						$settings["cache_key"] .= "-" . $info["user_id"];
					}
					
					$settings['cache_values'] = $lbill;
					set_cache_for_special_values( $settings );
				}
			break;
			case "start_generate_shared_report_bg":
				sleep( 2 );
				if( class_exists("cAssets_log") ){
					$asset = new cAssets_log();
					$asset->class_settings = $this->class_settings;
					$asset->class_settings["action_to_perform"] = "reamortize_assets_in_background";
					$asset->assets_log();
				}
				
				sleep( 2 );
				if( class_exists("cItems") ){
					$items = new cItems();
					$items->class_settings = $this->class_settings;
					$items->class_settings["action_to_perform"] = 'get_cost_of_sales_increase_widget';
					$lbill["flash"]["cost_price"] = $items->items();
					
					echo "Cost Price Changes\n\n";
				}
				
				$settings['cache_values'] = $lbill;
				set_cache_for_special_values( $settings );
			break;
			default:
				
				//$billing_time = mktime( 23, 50, 0, date("n"), date("j"), date("Y") );	//23:59 pm
				$flash_count = 5;	//number of times flashed notifications are displayed
				
				$now = date("U");
				$next_bill = 0;
				if( isset( $lbill["next_bill"] ) ){
					$next_bill = doubleval( $lbill["next_bill"] );
				}
				
				if( $next_bill == 0 || $now >= $next_bill ){
					$settings['cache_values'] = array();
					$settings['cache_values']["by"] = $this->class_settings["user_id"];
					$settings['cache_values']["type"] = $action_to_perform;
					$settings['cache_values']["next_bill"] = mktime( 23, 59, 0, date("n"), date("j"), date("Y") );
					
					set_cache_for_special_values( $settings );
					
					run_in_background( "bprocess", 0, 
						array( 
							"action" => $this->table_name, 
							"todo" => "start_generate_shared_report_bg", 
							"no_session" => 1, 
							"user_id" => 1, 
							"reference" => $this->class_settings["user_id"],
							"show_window" => 1,
						) 
					);
					
				}
				
				$access = get_accessed_functions();
				$super = 0;
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
				$access_id2 = 'dashboard.s_inform.';
				$akey = 'accessible_functions';
				
				//check shared reports
				if( isset( $lbill["flash"] ) && is_array( $lbill["flash"] ) && ! empty( $lbill["flash"] ) ){
					foreach( $lbill["flash"] as $mk => $mv ){
						if( isset( $access[ $akey ][ $access_id2 . $mk ] ) || $super ){
							if( ! isset( $mv["viewed"][ $this->class_settings["user_id"] ] ) ){
								$return["information"][$mk] = $mv;
							}
						}
					}
				}
				
				$settings = array(
					'cache_key' => "dashboard-reports-" . $this->class_settings["user_id"],
					'permanent' => true,
					'directory_name' => $this->table_name,
				);
				$lbill = get_cache_for_special_values( $settings );
				if( isset( $lbill["flash"] ) && is_array( $lbill["flash"] ) && ! empty( $lbill["flash"] ) ){
					foreach( $lbill["flash"] as $mk => $mv ){
						$return["information"][ $mk ] = $mv;
						$return["information"][ $mk ]["mine"] = 1;
						
						if( ! isset( $lbill["flash"][ $mk ]["flash_count"] ) ){
							$lbill["flash"][ $mk ]["flash_count"] = 0;
						}
						++$lbill["flash"][ $mk ]["flash_count"];
						if( ( isset( $mv["viewed"] ) ) ){
							unset( $return["information"][ $mk ] );
						}
						if( ( isset( $mv["viewed"] ) ) || ( $lbill["flash"][ $mk ]["flash_count"] >= $flash_count ) ){
							unset( $lbill["flash"][ $mk ] );
						}
					}
					$settings["cache_values"] = $lbill;
					set_cache_for_special_values( $settings );
				}
			break;
			}
			
			return $return;
		}
		
		protected function _data_approval_dashboard(){
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$container = "#dash-board-main-content-area";
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$error_msg = '';
			$e_type = 'error';
			
			$handle = $container;
			$handle2 = 'dash-board-main-content-area';
			
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$data = array();
			$filename = 'data-approval-dashboard';
			
			switch( $action_to_perform ){
			case "data_approval_dashboard_metrics":
				$state = '';
				$lga = '';
				$opt = array();
				
				switch( $action_to_perform ){
				case "data_approval_dashboard_metrics":
					$opt["no_group"] = 1;
					$opt["count_states_lga"] = 1;
				break;
				}
				
				$opt["check_access"] = 1;
				$opt["table"] = "households";
				$opt["state"] = $state;
				$opt["lga"] = $lga;
				
				if( ! $error_msg ){
					
				}
				/*
				$select = " COUNT( `".$individuals->table_name."`.`id` ) as 'count' ";
				$individuals->class_settings["join"] = " LEFT JOIN `". $this->class_settings["database_name"] ."`.`".$households->table_name."` ON `". $households->table_name ."`.`id` = `".$individuals->table_name."`.`".$individuals->table_fields["household"]."` ";
				
				$individuals->class_settings["where"] = $where;
				$individuals->class_settings["overide_select"] = $select;
				$data["unprocessed"][ $individuals->table_name ] = $individuals->individuals();
				*/
			break;
			case "display_my_tree_view":
			case "get_tree_children":
			case "display_tree_view":
				$data = array();
				
				$params = isset( $this->class_settings["params"] )?$this->class_settings["params"]:$_GET;
				$idd = isset( $_GET[ 'id' ] ) ? $_GET[ 'id' ] : '';
				$idd = isset( $_GET[ 'id2' ] ) && ( !$idd || $idd == '#' ) ? $_GET[ 'id2' ] : $idd;
				
				if( $idd ){
					$params2 = _convert_id_into_actions( $idd );
					unset( $_GET["id"] );
					unset( $_GET["id2"] );
					
					if( isset( $params2["action"] ) && $params2["action"] && isset( $params2["todo"] ) && $params2["todo"] ){
						if( isset( $params2["nwp_action"] ) && $params2["nwp_action"] && isset( $params2["nwp_todo"] ) && $params2["nwp_todo"] ){
							$this->class_settings[ 'nwp_action' ] = $params2["nwp_action"];
							$this->class_settings[ 'nwp_todo' ] = $params2["nwp_todo"];
						}

						//$params["development_mode_off"] = isset( $params["development_mode_off"] )?$params["development_mode_off"]:0;
						
						$rtable = $params2["action"];
						$ctable = "c".ucwords( $rtable );
						
						$i = new $ctable();
						$i->class_settings = $this->class_settings;
						$i->class_settings["action_to_perform"] = $params2["todo"];
						$i->class_settings["params"] = $params2;
						return $i->$rtable();
					}
					
				}
				
				switch( $action_to_perform ){
				case "get_tree_children":
				case "display_tree_view":
				break;
				case "display_my_tree_view":
					
					$params = isset( $this->class_settings["params"] )?$this->class_settings["params"]:$_GET;
					
					if( isset( $_GET["id"] ) && $_GET["id"] ){
						$params2 = _convert_id_into_actions( $_GET["id"] );
						unset( $_GET["id"] );
						
						if( isset( $params2["action"] ) && $params2["action"] && isset( $params2["todo"] ) && $params2["todo"] ){
							
							//$params["development_mode_off"] = isset( $params["development_mode_off"] )?$params["development_mode_off"]:0;
							
							$rtable = $params2["action"];
							$ctable = "c".ucwords( $rtable );
							
							$i = new $ctable();
							$i->class_settings = $this->class_settings;
							$i->class_settings["action_to_perform"] = $params2["todo"];
							$i->class_settings["params"] = $params2;
							return $i->$rtable();
						}
						
					}

					$table = isset( $params["table"] )?$params["table"]:'';
					$folders = array(
						'id' => 'folders',
						'text' =>  'My Folders',
						'children' => array(
							array(
								'id' => 'action=tags:::todo=display_shared_with_me:::get_children=1:::html_replacement_selector=' . $handle2,
								'text' =>  'Shared with me',
								'children' => true,
							),
							array(
								'id' => 'action=tags:::todo=display_my_records_frontend:::get_children=1:::html_replacement_selector=' . $handle2,
								'text' => 'Created by Me',
								'children' => true,
							),
							
						),
					);

					if( class_exists( 'cCalendar' ) ){
						$data[] = array(
							'id' => 'action=calendar:::todo=display_dashboard2:::home=1:::get_children=1:::html_replacement_selector=' . $handle2,
							'icon' =>  'icon-calendar',
							'text' =>  'Schedule',
							//'children' => true,
						);
					}
					
					if( defined("NWP_HR_PORTAL") && NWP_HR_PORTAL && class_exists( 'cNwp_human_resource' ) ){
						$data[] = array(
							'id' => 'action=nwp_human_resource:::todo=execute:::nwp_action=frontend_users:::nwp_todo=portal_access:::home=1:::get_children=1:::html_replacement_selector=' . $handle2,
							'icon' =>  'icon-signin',
							'text' =>  'HR Portal',
							//'children' => true,
						);
					}
					
					$docs = array(
						'id' => 'a=1:::action=files:::todo=display_my_records_frontend:::html_replacement_selector=' . $handle2,
						'text' =>  'Documents',
						'children' => array(
							array(
								//'id' => 'action=nwp_42doc_edms:::todo=execute:::nwp_action=tags_library:::nwp_todo=new_library_item:::view=tags_library:::html_replacement_selector=' . $handle2,
								 'id' => 'action=files:::todo=display_capture_window:::html_replacement_selector=' . $handle2,
								'text' =>  'New',
								'icon' => 'icon-file',
							),
							array(
								'id' => 'action=files:::todo=display_my_records_frontend:::html_replacement_selector=' . $handle2,
								'text' => 'My Documents',
							),
							array(
								'id' => 'action=files:::todo=display_shared_with_me:::get_children=1:::html_replacement_selector=' . $handle2,
								'text' => 'Shared with me',
								'children' => true,
							),
						),
					);
					
					if( class_exists( "cNwp_full_text_search" ) ){
						$data[] = array(
							'icon' => ' icon-search ',
							'id' => 'action=nwp_full_text_search:::todo=execute:::nwp_action=index_queue:::nwp_todo=search_index:::get_children=1:::html_replacement_selector=' . $handle2,
							'text' =>  'Search',
						);
					}
					
					if( class_exists( "cNwp_42doc_edms" ) ){
						$se = 1;
						if( ( defined("NWP_DCM_VIEWER") && NWP_DCM_VIEWER ) || ( defined("HYELLA_V3_ORTHANC") && HYELLA_V3_ORTHANC ) ){
							$se = 0;
							if( defined("NWP_ENABLE_42DOCS_EDMS") && NWP_ENABLE_42DOCS_EDMS ){
								$se = 1;
							}
						}
						
						if( $se ){
						/* $f2 = new cNwp_42doc_edms();
							$f2->load_class( array( 'class' => array( 'tags_library' , 'edms_group' ) ) );
						}

						if( class_exists( "cTags_library" ) ){ */
							
							$data[] = array(
								'id' => 'action=all_reports:::todo=display_home_menu:::type=workflow:::html_replacement_selector=' . $handle2,
								'icon' =>  'icon-home',
								'text' =>  'Home'
							);
							$data[] = array(
								'id' => 'action=nwp_42doc_edms:::todo=execute:::nwp_action=tags_library:::nwp_todo=display_my_library_category:::get_children=1:::html_replacement_selector=' . $handle2 . ':::nw_breadcrum=1:::title=' . rawurlencode( 'My Dept.' ),
								// 'id' => 'action=tags_library:::todo=display_my_library:::get_children=1:::html_replacement_selector=' . $handle2,
								'text' =>  'My Dept.',
								'children' => true,
							);
							$docs['children'][] = array(
								'icon' => 'icon-file',
								'id' => 'action=nwp_42doc_edms:::nwp_action=tags_library:::todo=execute:::get_children=1:::nwp_todo=display_my_attachments:::view=tags_library:::html_replacement_selector=' . $handle2 . ':::title=' . rawurlencode( 'My Attachments' ),
								'text' => 'My Attachments',
								'children' => true,
							);
							
							$docs["text"] = 'My Desk';
							$docs["children"][] = $folders;
							$data[] = $docs;
							
							$docs = array();
							$folders = array();
						}
					}
					
					if( ! empty( $folders ) )$data[] = $folders;
					
					switch( get_package_option() ){
					case "gynae":
						$dx =  array(
							'id' => 'patients',
							'text' =>  'Patients',
							'children' => array(
								array(
									'id' => 'action=appointment2:::todo=display_patients_on_queue_mine:::get_children=1:::html_replacement_selector=' . $handle2,
									'text' =>  'My Queue',
								),
								/* array(
									'id' => 'action=my_patient_view:::todo=display_my_patient_view_group:::get_children=1:::html_replacement_selector=' . $handle2 . ':::menu_title=' . rawurlencode( 'Seen Today' ),
									'text' => 'Seen Today',
								), */
								array(
									'id' => 'action=my_patient_view:::todo=display_my_patient_view:::get_children=1:::html_replacement_selector=' . $handle2 . ':::menu_title=' . rawurlencode( 'My Treatment' ),
									'text' => 'My Treatment',
								),
								
							),
						);
					
						if( class_exists( 'cNwp_advanced_procedures' ) ){
							$dx[ 'children' ][] = array(
								'id' => 'action=nwp_advanced_procedures:::todo=execute:::nwp_action=advanced_procedures:::nwp_todo=display_my_procedures:::get_children=1:::html_replacement_selector=' . $handle2,
								'text' =>  'My Procedures',
								'children' => true,
							);
						}

						$data[] = $dx;
					break;
					}
					
					if( ! defined("HYELLA_NO_MY_REPORT") ){
						$data[] = array(
							'id' => 'reports',
							'text' =>  'Reports',
							'children' => array(
								array(
									'id' => 'action=files:::todo=display_shared_with_me:::filter=report:::get_children=1:::html_replacement_selector=' . $handle2,
									'text' =>  'Shared with me',
									//'children' => true,
								),
								array(
									'id' => 'my-reports',
									'text' =>  'My Reports',
									'id' => 'action=files:::todo=display_my_report:::html_replacement_selector=' . $handle2,
								),
								
							),
						);
					}
					
					if( ! defined("HYELLA_NO_MESSAGE") ){
						$data[] = array(
							'id' => 'action=comments:::todo=get_message_tree_children:::get_children=1:::html_replacement_selector=' . $handle2,
							'text' =>  'Messages',
							'children' => true,
						);
					}
					
					if( class_exists("cNwp_workflow") ){
						$data[] = array(
							'id' => 'action=nwp_workflow:::nwp_action=workflow:::nwp_todo=data_approval_dashboard:::todo=execute:::html_replacement_selector=' . $handle2,
							//'id' => 'action=nwp_workflow:::nwp_action=workflow:::nwp_todo=data_approval_home:::todo=execute:::html_replacement_selector=' . $handle2,
							//@mike: we need to revise this
							'text' =>  'Approvals',
							//'children' => true
						);
					}
					
					if( class_exists("cNwp_work_order") ){
						$data[] = array(
							'id' => 'action=nwp_work_order:::nwp_action=work_order:::nwp_todo=display_my_records_frontend:::todo=execute:::html_replacement_selector=' . $handle2,
							'text' =>  'Work Order',
							//'children' => true
						);
					}
					
					if( class_exists("cStock_request") ){
						$data[] = array(
							'id' => 'action=stock_request:::todo=display_my_records_frontend:::html_replacement_selector=' . $handle2,
							'text' =>  'Requisitions',
							//'children' => true
						);
					}
					
					if( class_exists("cNwp_bulk_finance") ){
						$data[] = array(
							'id' => 'action=nwp_bulk_finance:::todo=execute:::nwp_action=bulk_finance_posting:::nwp_todo=display_my_transactions:::html_replacement_selector=' . $handle2,
							'text' =>  'Finance Posting',
							//'children' => true
						);
					}
					
					if( ! defined("HYELLA_NO_DOCUMENT") ){
						if( ! empty( $docs ) )$data[] = $docs;
					}
					
					// print_r( $data );
					if( class_exists("cNwp_meta_data") ){
						extract( cNwp_meta_data::js_tree_menu( $handle2 ) );
					}
					// print_r( $data );exit;
					
					if( class_exists("cNwp_client_notification") ){
						$data[] = array(
							'id' => 'action=nwp_client_notification:::todo=execute:::nwp_action=cn_user_settings:::nwp_todo=notification_settings:::get_children=1:::html_replacement_selector=' . $handle2,
							'text' =>  'Notifications',
							//'children' => true,
						);
					}else{
						$data[] = array(
							'id' => 'action=notifications:::todo=display_my_notifications:::get_children=1:::html_replacement_selector=' . $handle2,
							'text' =>  'Notifications',
							'children' => true,
						);
					}
					
					if( class_exists("cNwp_help") ){
						$data[] = array(
							'id' => 'action=nwp_help:::todo=execute:::nwp_action=help_documentation:::nwp_todo=display_help:::get_children=1:::html_replacement_selector=' . $handle2,
							'text' =>  'User Guide',
						);
					}
					
					if( ! defined("HYELLA_NO_MORE_DATA") ){
						$data[] = array(
							'id' => 'more-data',
							'text' =>  'More Data',
							'open' => true,
							'children' => array(
								array(
									'id' => 'action=tickets:::todo=my_assigned_tickets:::get_children=1:::html_replacement_selector=' . $handle2,
									'text' =>  'Tickets',
								),
								array(
									'id' => 'action=training:::todo=my_trainings:::get_children=1:::html_replacement_selector=' . $handle2,
									'text' =>  'Trainings',
								),
								array(
									'id' => 'action=share:::todo=display_shared_with_me:::get_children=1:::html_replacement_selector=' . $handle2,
									'text' =>  'Shared with me',
									'children' => true,
								),
							),
						);
					}

				break;
				}
				
				return $data;
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			switch( $action_to_perform ){
			case "data_approval_dashboard_metrics":
				return $data;
			break;
			}
			
			$this->class_settings[ 'data' ] = $data;
				
			$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $handle2;
			$this->class_settings[ 'data' ]["action_to_perform"] = $action_to_perform;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js
			);
		}
		
		protected function _display_procurement_dashboard(){
			$container = "#dash-board-main-content-area";
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$error_msg = '';
			$e_type = 'error';
			
			$data = array();
			
			$handle = $container;
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$filename = 'display_mis_menu';
			
			switch( $action_to_perform ){
			case "display_all_procurement_dashboard":
				$action_to_perform = "display_procurement_dashboard";
				$store = '';
			break;
			case "display_procurement_dashboard":
				$store = get_current_store(1);
			break;
			}
			
			$filename = str_replace( "_", "-", $action_to_perform );
			
			switch( $action_to_perform ){
			case "display_procurement_dashboard":
				
				//$store = get_main_store();
				$pyear = date("Y");
				$pmonth = date("n") - 1;
				if( $pmonth < 1 ){
					$pmonth = 12;
					--$pyear;
				}
				
				$start_month2 = mktime( 0, 0, 0, $pmonth, 1, $pyear );
				
				$data["store"] = $store;
				$data["last_month"] = $start_month2;
				
				$start_month = mktime( 0, 0, 0, date("n"), 1, date("Y") );
				$end_month = mktime( 23, 59, 59, date("n"), date("t"), date("Y") );
				
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				
				$js[] = '$.fn.cCallBack.activateSidebarMenu';
				
				$this->class_settings[ 'data' ]["container"] = $handle;
				$this->class_settings[ 'data' ]["action_to_perform"] = $action_to_perform;
				
				$exp = new cExpenditure();
				$inv = new cInventory_ent();
				
				$extra_where_inv = "";
				$extra_where = "";
				if( $store ){
					$extra_where = " AND a.`".$exp->table_fields["store"]."` = '".$store."' ";
					$extra_where_inv = " AND `".$inv->table_name."`.`".$inv->table_fields["store"]."` = '".$store."' ";
				}
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				//`".$this->table_fields["production_id"]."`
				//production_id
				$view_name = 'view_d1' . $store;
				
				$query = "CREATE OR REPLACE VIEW `" . $this->class_settings['database_name'] . "`.".$view_name." as ( SELECT a.`id` as 'count', ( SELECT SUM( `".$inv->table_fields["quantity_ordered"]."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$inv->table_name."` WHERE `".$inv->table_name."`.`".$inv->table_fields["reference"]."` = a.`id` AND `".$inv->table_name."`.`".$inv->table_fields["reference_table"]."` = 'expenditure' ) as 'qty', SUM( c.`".$inv->table_fields["quantity"]."` ) as 'qty2' FROM `" . $this->class_settings['database_name'] . "`.`expenditure` a JOIN `" . $this->class_settings['database_name'] . "`.`expenditure` b ON b.`".$exp->table_fields["production_id"]."` = a.`id` AND b.`record_status`='1' AND b.`".$exp->table_fields["reference_table"]."` = 'expenditure' LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$inv->table_name."` c ON c.`".$inv->table_fields["reference"]."` = b.`id` AND c.`record_status` = '1' AND c.`".$inv->table_fields["reference_table"]."` = 'expenditure' WHERE a.`record_status`='1' AND a.`".$exp->table_fields["status"]."` = 'draft' ".$extra_where." GROUP BY a.`id` HAVING qty > qty2 ) ";
				
				$query_settings["query"] = $query;
				$query_settings["query_type"] = "EXECUTE";
				$query_settings["skip_log"] = 1;
				execute_sql_query( $query_settings );
				
				$query = "SELECT COUNT(*) as 'count' FROM `" . $this->class_settings['database_name'] . "`.`".$view_name."` ";
				$query_settings["query"] = $query;
				$query_settings["query_type"] = "SELECT";
				$query_settings["tables"] = array( $view_name );
				$sql = execute_sql_query( $query_settings );
				
				$data["purchase"]["partial_supply"] = isset( $sql[0]["count"] )?$sql[0]["count"]:0;
				
				$query = "DROP VIEW `" . $this->class_settings['database_name'] . "`.`".$view_name."` ";
				$query_settings["query"] = $query;
				$query_settings["query_type"] = "EXECUTE";
				execute_sql_query( $query_settings );
				
				
				$view_name = 'view_d2' . $store;
				$query = "CREATE OR REPLACE VIEW `" . $this->class_settings['database_name'] . "`.".$view_name." as ( SELECT COUNT(*) as 'count', a.`".$exp->table_fields["date_cleared"]."` as 'date_cleared', b.`id` as 'bid' FROM `" . $this->class_settings['database_name'] . "`.`".$exp->table_name."` a LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$exp->table_name."` b ON b.`".$exp->table_fields["production_id"]."` = a.`id` AND b.`record_status`='1' AND b.`".$exp->table_fields["reference_table"]."` = 'expenditure' where a.`record_status`='1' AND a.`".$exp->table_fields["status"]."` = 'draft' ".$extra_where." GROUP BY a.`id` HAVING bid IS NULL ) ";
				
				$query_settings["query"] = $query;
				$query_settings["query_type"] = "EXECUTE";
				execute_sql_query( $query_settings );
				
				$query = "SELECT SUM(`count`) as 'count' FROM `" . $this->class_settings['database_name'] . "`.`".$view_name."` ";
				$query_settings["query"] = $query;
				$query_settings["tables"] = array( $view_name );
				$query_settings["query_type"] = "SELECT";
				$sql = execute_sql_query( $query_settings );
				
				$data["purchase"]["awaiting_supply"] = isset( $sql[0]["count"] )?$sql[0]["count"]:0;
				
				
				$query = "SELECT SUM(`count`) as 'count' FROM `" . $this->class_settings['database_name'] . "`.`".$view_name."` WHERE `date_cleared` < " . date("U");
				$query_settings["query"] = $query;
				$query_settings["tables"] = array( $view_name );
				$query_settings["query_type"] = "SELECT";
				$sql = execute_sql_query( $query_settings );
				
				$data["purchase"]["past_due"] = isset( $sql[0]["count"] )?$sql[0]["count"]:0;
				
				$query = "DROP VIEW `" . $this->class_settings['database_name'] . "`.`".$view_name."` ";
				$query_settings["query"] = $query;
				$query_settings["tables"] = array( $view_name );
				$query_settings["query_type"] = "EXECUTE";
				execute_sql_query( $query_settings );
				
				$data["purchase"]["total"] = $data["purchase"]["partial_supply"] + $data["purchase"]["awaiting_supply"];
				
				
				//SUPPLIES
				$query = "SELECT FROM_UNIXTIME( `".$inv->table_fields["date"]."`, '%M-%Y' ) as 'month', SUM( `".$inv->table_fields["quantity"]."` ) as 'quantity', SUM( CASE  WHEN `".$inv->table_fields["addition_type"]."` = 'draft' THEN `".$inv->table_fields["quantity_ordered"]."` ELSE 0 END ) as 'quantity_ordered' FROM `" . $this->class_settings['database_name'] . "`.`".$inv->table_name."` WHERE `record_status` = '1' AND `".$inv->table_fields["reference_table"]."` = 'expenditure' ". $extra_where_inv ." AND `".$inv->table_fields["date"]."` BETWEEN " . $start_month2 . " AND " . $end_month . " GROUP BY FROM_UNIXTIME( `".$inv->table_fields["date"]."`, '%M-%Y' ) ";
				$query_settings["query"] = $query;
				$query_settings["tables"] = array( $inv->table_name );
				$query_settings["query_type"] = "SELECT";
				$sql = execute_sql_query( $query_settings );
				
				$data["supplies"] = $sql;
				//SUPPLIES
				
				
				//LOW STOCK LEVEL
				$_POST["report_type"] = 'low_stock_level_report_dashboard';
				$_POST["store"] = $store;
				$_POST["start_date"] = date("Y-m-d");
				$_POST["end_date"] = date("Y-12-31");
				$_POST["category"] = "all-categories";
				$_POST["type"] = "monthly";
				
				$inv = new cInventory_ent();
				$inv->class_settings = $this->class_settings;
				$inv->class_settings["action_to_perform"] = 'generate_sales_report';
				$data["low_stock"] = $inv->inventory_ent();
				//LOW STOCK LEVEL
				
				if( class_exists("cAssets") ){
					//ASSETS NOTICE
					$as = new cAssets();
					$as->class_settings = $this->class_settings;
					if( $store ){
						$as->class_settings["use_store"] = $store;
					}
					$as->class_settings["action_to_perform"] = "view_fixed_assets_dashboard";
					$as->class_settings["return_data"] = 1;
					$data["assets"] = $as->assets();
					
					$as->class_settings["action_to_perform"] = "view_fixed_assets_dashboard2";
					$as->class_settings["return_data"] = 1;
					$data["assets2"] = $as->assets();
					//ASSETS NOTICE
				}
				
				if( class_exists("cStock_request") ){
					//INTERNAL REQUISITIONS
					$st = new cStock_request();
					
					$extra_where_st = "";
					$extra_where_st2 = "";
					if( $store ){
						$extra_where_st = " AND `".$st->table_name."`.`".$st->table_fields["store"]."` = '".$store."' ";
						$extra_where_st2 = " AND `".$st->table_name."`.`".$st->table_fields["destination_store"]."` = '".$store."' ";
					}
					
					$query = "SELECT COUNT(*) as 'count', ( CASE WHEN `".$st->table_fields["status"]."` IN ( 'submitted' ) THEN 'stock-request-unvalidated' WHEN `".$st->table_fields["status"]."` IN ( 'stock-request-validated', 'stock-request' ) THEN 'stock-request-validated' ELSE `".$st->table_fields["status"]."` END ) as 'status', 'internal_requisition' as 'type' FROM `" . $this->class_settings['database_name'] . "`.`".$st->table_name."` WHERE `record_status` = '1' AND `".$st->table_fields["status"]."` IN ( 'stock-request-returned', 'submitted' ) AND `".$st->table_fields["type"]."` IN ( 'internal_requisition', 'asset_requisition' ) ". $extra_where_st2 ." GROUP BY `".$st->table_fields["status"]."` ";
					
					$query_settings["query"] = $query;
					$query_settings["query_type"] = "SELECT";
					$query_settings["tables"] = array( $st->table_name );
					$sql_sub = execute_sql_query( $query_settings );
					
					$query = "SELECT COUNT(*) as 'count', ( CASE WHEN `".$st->table_fields["status"]."` IN ( 'submitted' ) THEN 'stock-request-unvalidated' WHEN `".$st->table_fields["status"]."` IN ( 'stock-request-validated', 'stock-request' ) THEN 'stock-request-validated' ELSE `".$st->table_fields["status"]."` END ) as 'status', 'internal_requisition' as 'type' FROM `" . $this->class_settings['database_name'] . "`.`".$st->table_name."` WHERE `record_status` = '1' AND `".$st->table_fields["status"]."` IN (  'stock-request-validated', 'stock-request' ) AND `".$st->table_fields["type"]."` IN ( 'internal_requisition', 'asset_requisition' ) ". $extra_where_st ." GROUP BY `".$st->table_fields["status"]."` ";
					
					//echo $query;exit;
					$query_settings["query"] = $query;
					$query_settings["query_type"] = "SELECT";
					$query_settings["tables"] = array( $st->table_name );
					$sql = execute_sql_query( $query_settings );
					
					$query = "SELECT COUNT(*) as 'count', ( CASE WHEN `".$st->table_fields["status"]."` IN ( 'submitted' ) THEN 'stock-request-unvalidated' WHEN `".$st->table_fields["status"]."` IN ( 'stock-request-validated', 'stock-request' ) THEN 'stock-request-validated' ELSE `".$st->table_fields["status"]."` END ) as 'status', `".$st->table_fields["type"]."` as 'type' FROM `" . $this->class_settings['database_name'] . "`.`".$st->table_name."` WHERE `record_status` = '1' AND `".$st->table_fields["status"]."` IN (  'stock-request-unvalidated', 'stock-request-returned', 'stock-request-validated', 'stock-request', 'submitted' ) AND `".$st->table_fields["type"]."` IN (  'purchase_requisition', 'purchase_requisition_s' ) ". $extra_where_st ." GROUP BY `".$st->table_fields["status"]."` ";
					
					//echo $query; exit;
					$query_settings["query"] = $query;
					$query_settings["query_type"] = "SELECT";
					$query_settings["tables"] = array( $st->table_name );
					$sqlp = execute_sql_query( $query_settings );
					
					$data["requisitions"] = array_merge( $sql, $sql_sub, $sqlp );
					//INTERNAL REQUISITIONS
				}
			break;
			case 'display_hr_dashboard':
				if( class_exists( 'cUsers_employee_profile' ) ){
					$u = new cUsers_employee_profile();
					$u->class_settings = $this->class_settings;
					$u->class_settings[ 'action_to_perform' ] = 'dashboard';

					$x = $u->users_employee_profile();
					$html = isset( $x[ 'html_replacement' ] ) ? $x[ 'html_replacement' ] : '';
				}
			break;
			}
			
			if( isset( $html ) && $html ){
				$returning_html_data = $html;
			}else{
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				
				$returning_html_data = $this->_get_html_view();
			}
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js
			);
		}
		
		protected function _active_tab_and_display_menu(){
			$container = "#dash-board-main-content-area";
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$filename = 'display-manager';
			
			switch( $this->class_settings["action_to_perform"] ){
			case "active_tab_and_display_menu":
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				$js[] = '$.fn.cCallBack.activateSidebarMenu';
				
				$this->class_settings[ 'data' ]["container"] = 'inventories-asset-center';
			break;
			case "display_dashboard_mis":
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				$filename = 'display-manager-mis';
				$js[] = '$.fn.cCallBack.clickActiveTab';
			break;
			default:
				$js[] = 'nwCustomers.refreshAcitveTab';
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $container,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js
			);
		}
		
		protected function _display_mis_menu(){
			$container = "#dash-board-main-content-area";
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$filename = 'display_mis_menu';
			
			switch( $action_to_perform ){
			case "display_ssr_menu":
			case "display_nsr_menu":
			case "display_mis_menu":
				/*
				if( isset( $_POST["container"] ) && $_POST["container"] ){
					$container = $_POST["container"];
				}
				*/
				$js[] = '$.fn.cCallBack.activateSidebarMenu';
				
				$this->class_settings[ 'data' ]["container"] = $action_to_perform;
				$this->class_settings[ 'data' ]["action_to_perform"] = $action_to_perform;
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $container,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js
			);
		}
		
		private function _dashboard(){
			$package = '';
			
			if( defined("HYELLA_PACKAGE") ){
				$package = HYELLA_PACKAGE;
				
				switch( HYELLA_PACKAGE ){
				case "giftcodes":
					$g = new cGiftcodes();
					$g->class_settings = $this->class_settings;
					return $g->giftcodes();
				break;
				}
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = 'Invalid Dashboard for Package: ' . strtoupper( $package );
			return $err->error();
		}
		
		private function _display_app_more_menu(){
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-app-more-menu' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ) 
			);
		}
		
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		
		private function _get_general_settings(){
			$general_settings = new cGeneral_settings();
			$general_settings->class_settings = $this->class_settings;
			$general_settings->class_settings[ 'action_to_perform' ] = 'get_general_settings';
			return $general_settings->general_settings();
		}
		
		private function _get_dashboard_notification(){
			$notifications = new cNotifications();
			$notifications->class_settings = $this->class_settings;
			$notification = $notifications->notifications();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'notification' => $notification,
			);
			
			if( empty( $notification ) ){
				$returning[ 'html' ] = 'No Notifications';
			}else{
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notification-cell-item.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html' ] = $script_compiler->script_compiler();
			}
			$returning[ 'status' ] = 'got-dashboard-notification';
			
			return $returning;
		}
		
		private function _get_dashboard_task(){
			$tasks = new cTasks();
			$tasks->class_settings = $this->class_settings;
			$task = $tasks->tasks();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
				'pending_task' => $task,
			);
			
			if( empty( $task ) ){
				$returning[ 'html' ] = 'No Task';
			}else{
				$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-task-cell-item.php' );
				$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
				$returning[ 'html' ] = $script_compiler->script_compiler();
			}
			$returning[ 'status' ] = 'got-dashboard-task';
			
			return $returning;
		}
		
		private function _get_dashboard_notification_task_row(){
			$tasks = new cTasks();
			$tasks->class_settings = $this->class_settings;
			$tasks->class_settings[ 'action_to_perform' ] = 'get_pending_task';
			$pending_task = $tasks->tasks();
			
			$notifications = new cNotifications();
			$notifications->class_settings = $this->class_settings;
			$notifications->class_settings[ 'action_to_perform' ] = 'get_unread_notification';
			$unread_notification = $notifications->notifications();
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array( 
				'pending_task' => $pending_task,
				'notification' => $unread_notification,
			);
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notification-task-row.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] = $script_compiler->script_compiler();
			
			return $returning;
		}
		
		private function _get_dashboard_notice_row(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'data' ] = array(
                'notice' => $this->_notice_row_data(),
            );
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notice-row.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] = $script_compiler->script_compiler();
			
			return $returning;
		}
		
		private function _setup_dashboard(){
			
            //check for logged in user
            if( ! ( isset( $this->class_settings['user_id'] ) && $this->class_settings['user_id'] ) ){
                header('Location: ?page=login');
            }
            
            $user_details = get_site_user_details( array( 'id' => $this->class_settings['user_id'] ) );
            if( empty( $user_details ) ){
                header('Location: ?page=login');
            }
            
			if( isset( $this->class_settings[ 'bundle-name' ] ) ){
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'bundle-name' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'bundle-name' ];
			}else{
				$this->class_settings[ 'script_name' ] = $this->class_settings[ 'action_to_perform' ];
				$this->class_settings[ 'stylesheet' ] = $this->class_settings[ 'action_to_perform' ];
			}
			
			//$this->class_settings[ 'js_lib' ] = array( 'js/jquery-1.7.1.min.js', 'js/fileuploader.js' ,'js/tiny_mce/jquery.tinymce.min.js' , 'js/tiny_mce/tinymce.min.js' , 'js/ui/jquery-ui-1.10.3.custom.min.js' , 'bootstrap-2.3.2/docs/assets/js/html5shiv.js' , 'js/amplify.min.js', 'site-admin-assets/js/bootstrap.js' , 'site-admin-assets/js/plugins/metisMenu/metisMenu.min.js' , 'site-admin-assets/js/plugins/morris/raphael.min.js' , 'site-admin-assets/js/sb-admin-2.js', 'js/jquery.dataTables.js' );
		
			$this->class_settings[ 'js_lib' ] = $this->get_js_lib();
			$this->class_settings[ 'js' ] = $this->get_js();
			$this->class_settings[ 'css' ] = $this->get_css();
			
			$this->class_settings[ 'html' ] = array( 'html-files/static-markup.php' );
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
			$script_compiler->class_settings[ 'action_to_perform' ] = 'compile_scripts';
			
			$script_data = $script_compiler->script_compiler();
			
			if( isset( $script_data[ 'js_file' ] ) ){
				$returning[ 'javascript' ] = $script_data[ 'js_file' ];
			}
			
			if( isset( $script_data[ 'css_file' ] ) ){
				$returning[ 'stylesheet' ] = $script_data[ 'css_file' ];
			}
			
			if( isset( $script_data[ 'html_markup' ] ) ){
				$returning[ 'html_markup' ] = $script_data[ 'html_markup' ];
			}
			
            $script_compiler->class_settings[ 'data' ]['title'] = $this->page_title;
			$script_compiler->class_settings[ 'data' ]['keywords'] = $this->page_keywords;
			$script_compiler->class_settings[ 'data' ]['description'] = $this->page_description;
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/html-head-tag.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_head_tag' ] = $script_compiler->script_compiler();
			
            $product = new cProduct();
            $product->class_settings = $this->class_settings;
            $product->class_settings[ 'action_to_perform' ] = 'get_product_categories';
            
            $product->class_settings['ignore_selection'] = 1;
            
			$script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
				'menu' => get_website_menu_items( array( 'top_menu_bar_right' , 'top_menu_bar_left' ) ),
				'dashboard_menu' => $this->get_dashboard_menu(),
				'categories' => $product->product(),
                'terminating_categories' => get_array_of_all_terminating_categories(),
                'countries' => get_countries_data(),
			);
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/header-dashboard.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html_header' ] = $script_compiler->script_compiler();
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
		}
		
		private function get_dashboard_menu(){
			return array(
				1 => array(
					'text' => 'Dashboard',
					'title' => 'Dashboard',
					'link' => '?page=user-dashboard',
					'icon-class' => 'fa fa-dashboard fa-fw',
				),
                
				2 => array(
					'text' => 'Notifications',
					'title' => 'View all Notifications',
					'link' => '?action=notifications&todo=site_notifications_manager',
					'icon-class' => 'fa fa-bell fa-fw',
				),
				3 => array(
					'text' => 'Tasks',
					'title' => 'View all Tasks',
					'link' => '?action=tasks&todo=site_tasks_manager',
					'icon-class' => 'fa fa-tasks fa-fw',
				),
                
				4 => array(
					'text' => 'Payment History',
					'title' => 'View / Manage Payment Status',
					'link' => '?action=order&todo=site_order_manager',
					'icon-class' => 'fa fa-bar-chart-o fa-fw',
				),
				5 => array(
					'text' => 'Bids History',
					'title' => 'View all your Bids',
					'link' => '?action=bids&todo=site_bids_manager',
					'icon-class' => 'fa fa-bar-chart-o fa-fw',
				),
				6 => array(
					'text' => 'Selling <span class="fa arrow"></span>',
					'title' => 'Sell',
					'link' => '#',
					'icon-class' => 'fa fa-upload fa-fw',
					'children' => array(
						1 => array(
							'text' => 'Inventory Manager',
							'title' => 'Add / Manage products for sale',
							'link' => '?action=product&todo=site_inventory_manager',
						),
						2 => array(
							'text' => 'Auctioned Items',
							'title' => 'View items placed on auction',
							'link' => '?action=auctioned_product&todo=site_auctioned_items_manager',
						),
						3 => array(
							'text' => 'Advertised Items',
							'title' => 'View items being advertised',
							'link' => '?action=advertised_product&todo=site_advertised_items_manager',
						),
						4 => array(
							'text' => 'Sold Items',
							'title' => 'View items that were purchased',
							'link' => '?action=order&todo=site_sales_stats_manager',
						),
						7 => array(
							'text' => 'Store Manager',
							'title' => 'Configure & Manage your store',
							'link' => '?action=store&todo=site_store_manager',
						),
						9 => array(
							'text' => 'My Store Banners',
							'title' => 'Upload your customized store banners',
							'link' => '?action=store_banners&todo=custom_store_banners_manager',
						),
						10 => array(
							'text' => 'Shipping Options Manager',
							'title' => 'Configure & Manage your shipping options',
							'link' => '?action=custom_shipping_options&todo=site_custom_shipping_options_manager',
						),
						11 => array(
							'text' => 'Coupons Manager',
							'title' => 'Create & Manage your sales coupons',
							'link' => '?action=coupons&todo=coupons_manager',
						),
						12 => array(
							'text' => 'Pay On Delivery Manager',
							'title' => 'Create & Manage locations where you offer the pay on delivery service',
							'link' => '?action=pay_on_delivery&todo=pay_on_delivery_manager',
						),
					),
				),
				7 => array(
					'text' => 'My Finance <span class="fa arrow"></span>',
					'title' => 'View all my Financial Information',
					'link' => '#',
					'icon-class' => 'fa fa-upload fa-fw',
					'children' => array(
						1 => array(
							'text' => 'Bank Account Manager',
							'title' => 'Verify / Activate Bank Accounts',
							'link' => '?action=merchant_accounts&todo=bank_account_manager',
						),
						2 => array(
							'text' => 'Request A Payout',
							'title' => 'Request A Payout',
							'link' => '?action=payout_requests&todo=payout_requests_manager',
						),
					),
				),
				20 => array(
					'text' => 'My Profile',
					'title' => 'Manage My Profile',
					'link' => '?action=site_users&todo=display_user_details',
					'icon-class' => 'fa fa-user fa-fw',
				),
			);
		}
		
		private function get_js_lib(){
			$js_lib[] = 'js/jquery-1.7.1.min.js';
			$js_lib[] = 'js/fileuploader.js';
			$js_lib[] = 'js/tiny_mce/jquery.tinymce.min.js';
			$js_lib[] = 'js/tiny_mce/tinymce.min.js';
			$js_lib[] = 'site-admin-assets/js/bootstrap.js';
			/*
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-collapse.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-transition.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-alert.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-modal.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-dropdown.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-scrollspy.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-tab.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-tooltip.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-popover.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-button.js';
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/bootstrap-typeahead.js';
			*/
			$js_lib[] = 'bootstrap-2.3.2/docs/assets/js/html5shiv.js';
			$js_lib[] = 'js/amplify.min.js';
			$js_lib[] = 'site-admin-assets/js/plugins/metisMenu/metisMenu.min.js';
			$js_lib[] = 'site-admin-assets/js/plugins/morris/raphael.min.js';
			$js_lib[] = 'site-admin-assets/js/sb-admin-2.js';
            
			if( isset( $this->class_settings[ 'js_lib' ] ) && is_array($this->class_settings[ 'js_lib' ]) ){
				foreach( $this->class_settings[ 'js_lib' ] as $val ){
					$js_lib[] = $val;
				}
			}
			
            $js_lib[] = 'js/chosen.jquery.js';
            $js_lib[] = 'css/docsupport/prism.js';
            
			return $js_lib;
		}
		
		private function get_js(){
			$js[] = 'my_js/ajax-requests.js';
			$js[] = 'my_js/form-handler.js';
			$js[] = 'my_js/navigate.js';
			
			if( isset( $this->class_settings[ 'js' ] ) && is_array($this->class_settings[ 'js' ]) ){
				foreach( $this->class_settings[ 'js' ] as $val ){
					$js[] = $val;
				}
			}
			$js[] = 'my_js/custom/dashboard.js';
            $js[] = 'my_js/custom/select.js';
            
			return $js;
		}
		
		private function get_css(){
			$css[] = 'site-admin-assets/css/bootstrap.css';
			$css[] = 'css/form.css';
			$css[] = 'site-admin-assets/css/plugins/metisMenu/metisMenu.min.css';
			$css[] = 'site-admin-assets/css/plugins/timeline.css';
			$css[] = 'site-admin-assets/css/sb-admin-2.css';
			$css[] = 'site-admin-assets/css/plugins/dataTables.bootstrap.css';
			$css[] = 'site-admin-assets/css/plugins/morris.css';
			$css[] = 'site-admin-assets/font-awesome-4.1.0/css/font-awesome.css';
			$css[] = 'css/template-1/dashboard_welcome_message.css';
			$css[] = 'bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css';
			$css[] = 'css/chosen.css';
            
			if( isset( $this->class_settings[ 'css' ] ) && is_array($this->class_settings[ 'css' ]) ){
				foreach( $this->class_settings[ 'css' ] as $val ){
					$css[] = $val;
				}
			}
			
			return $css;
		}
        
        private function _notice_row_data(){
            //check for verified bank account
            $merchants_accounts = get_merchant_accounts_details( array( 'id' => $this->class_settings['user_id'] ) );
            
            $bank_account_verified_status = array(
                'title' => 'Verify Bank Account',
                'link_caption' => 'Pending',
                'link_title' => 'Click here to verify your bank account',
                'link' => '?action=merchant_accounts&todo=bank_account_manager',
                'icon' => 'none',
                'status' => 'pending',
                'status_icon' => 'fa-times-circle',
                'theme' => 'panel-default',
            );
            
            $listed_product_status = array(
                'title' => 'List an Item for Sale',
                'link_caption' => 'Pending',
                'link_title' => 'Click here to list an item for sale',
                'link' => '?action=product&todo=site_inventory_manager',
                'icon' => 'none',
                'status' => 'pending',
                'status_icon' => 'fa-times-circle',
                'theme' => 'panel-default',
            );
                    
			if( isset( $merchants_accounts['active'] ) && strtolower( $merchants_accounts['active'] ) != 'no' ){
				$bank_account_verified_status = array(
                    'title' => 'Verify Bank Account',
                    'link_caption' => 'Completed',
                    'link_title' => '',
                    'link' => '#',
                    'icon' => 'none',
                    'status' => 'done',
                    'status_icon' => 'fa-check-circle',
                    'theme' => 'panel-yellow',
                );
                
                //check for listed products
                $product = new cProduct();
                $product->class_settings = $this->class_settings;
                $product->class_settings[ 'action_to_perform' ] = 'get_merchant_products_count';
                $items_count = $product->product();
                
                if( $items_count ){
                    $listed_product_status = array(
                        'title' => 'List an Item for Sale',
                        'link_caption' => 'Completed',
                        'link_title' => '',
                        'link' => '#',
                        'icon' => 'none',
                        'status' => 'done',
                        'status_icon' => 'fa-check-circle',
                        'theme' => 'panel-red',
                    );
                    
                    $notifications = new cNotifications();
                    $notifications->class_settings = $this->class_settings;
                    $notifications->class_settings[ 'action_to_perform' ] = 'get_unread_notification_count';
                    $notification = $notifications->notifications();
                    
                    $tasks = new cTasks();
                    $tasks->class_settings = $this->class_settings;
                    $tasks->class_settings[ 'action_to_perform' ] = 'get_pending_task_count';
                    $task = $tasks->tasks();
                    
                    $product->class_settings[ 'action_to_perform' ] = 'get_merchant_sales_count';
                    $sales_count = $product->product();
                    
                    return array(
                        1 => array(
                            'title' => 'Pending Task',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => $task,
                            'status_icon' => 'fa-tasks',
                            'theme' => 'panel-primary',
                        ),
                        2 => array(
                            'title' => 'Notifications',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => $notification,
                            'status_icon' => 'fa-bell',
                            'theme' => 'panel-green',
                        ),
                        3 => array(
                            'title' => 'Sales',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => $sales_count,
                            'status_icon' => 'fa-shopping-cart',
                            'theme' => 'panel-yellow',
                        ),
                        4 => array(
                            'title' => 'Inventory',
                            'link_caption' => 'View Details',
                            'link_title' => '',
                            'link' => '#',
                            'icon' => 'none',
                            'status' => doubleval($items_count),
                            'status_icon' => 'fa-support',
                            'theme' => 'panel-red',
                        ),
                    );            
                }
			}
            
            return array(
                1 => array(
                    'title' => 'Review Our Policies',
                    'link_caption' => 'Completed',
                    'link_title' => '',
                    'link' => '#',
                    'icon' => 'none',
                    'status' => 'done',
                    'status_icon' => 'fa-check-circle',
                    'theme' => 'panel-primary',
                ),
                2 => array(
                    'title' => 'Create an Account',
                    'link_caption' => 'Completed',
                    'link_title' => '',
                    'link' => '#',
                    'icon' => 'none',
                    'status' => 'done',
                    'status_icon' => 'fa-check-circle',
                    'theme' => 'panel-green',
                ),
                3 => $bank_account_verified_status,
                4 => $listed_product_status,
            );
        }
        
        private function _populate_dashboard(){
             $return = array();
            $return['status'] = 'new-status';
            /*
            $notifications = new cNotifications();
            $notifications->class_settings = $this->class_settings;
            $notifications->class_settings[ 'action_to_perform' ] = 'get_all_notification';
			$not = $notifications->notifications();
			
            $notifications->class_settings[ 'action_to_perform' ] = 'get_unread_notification_count';
			$not_count = $notifications->notifications();
			*/
            $expenditure = new cExpenditure();
            $expenditure->class_settings = $this->class_settings;
            $expenditure->class_settings[ 'action_to_perform' ] = 'get_stats';
			//$stats = $expenditure->expenditure();
			$stats = 0;
			
			$stats1 = array();
			
            //$site_users->class_settings[ 'action_to_perform' ] = 'get_unread_notification_count';
			//$not_count = $site_users->site_users();
			
            $script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
            
            $script_compiler->class_settings[ 'data' ] = array( 
				'pagepointer' => $this->class_settings['calling_page'],
                'stats' => $stats,
                //'unread_notifications_count' => $not_count,
			);
            
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/admin-dashboard' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			$return['html_replacement'] = $script_compiler->script_compiler();
			$return['html_replacement_selector'] = "#dash-board-main-content-area";
            /*
			$notifications->class_settings[ 'action_to_perform' ] = 'get_unread_notification';
			$not = $notifications->notifications();
			$script_compiler->class_settings[ 'data' ] = array( 
                'notifications' => $not,
			);
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/dashboard-notification-dropdown.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			$return['html_replacement_two'] = $script_compiler->script_compiler();
			$return['html_replacement_selector_two'] = "#dashboard-notifications-alert";
            
			$return['html_replacement_one'] = $not_count;
			$return['html_replacement_selector_one'] = ".unread-notification-count";
            */
            $return['status'] = 'new-status';
            $return['javascript_functions'] = array("set_function_click_event", "$.fn.cProcessForm.activateEmptyTab");
            
			if( class_exists("cVoid_transactions") ){
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-';
				$return['id'] = 1;
				
				$return['action'] = '?action=void_transactions&todo=view_version2_report&html_replacement_selector=chart-container-1';
				//$return['action'] = '?action=expenditure&todo=get_users_pie_chart';
				$return['javascript_functions'][] = 'nwResizeWindow.resizeWindowHeight';
			}
				
            return $return;
        }
    }
	
	function get_nwp_info_dashboard_types(){
		$return = array(
			'cost_price' => 'Cost Price Variation',
			'posting_error' => 'Journal Posting Error',
			'db_backup' => 'Database Back-up',
		);

		if( class_exists('cNwp_human_resource') ){
			$nwp = new cNwp_human_resource();
			if( method_exists($nwp, 'get_nwp_info_dashboard_types') ){
				$return = array_merge($return, $nwp->get_nwp_info_dashboard_types()  );
			}
		}

		return $return;
	}
?>
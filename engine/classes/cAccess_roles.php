<?php
	/**
	 * access_roles Class
	 *
	 * @used in  				access_roles Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	access_roles
	 */
	/*
	UPDATE QUERIES:
	ALTER TABLE `access_roles`  ADD `serial_num` INT(11) NOT NULL  AFTER `access_roles008`;
	ALTER TABLE `access_roles`  ADD `device_id` TEXT DEFAULT NULL  AFTER `ip_address`;
	UPDATE `access_roles` SET `access_roles004` = 'users' WHERE `access_roles004` != 'api';
	*/
	
	class cAccess_roles extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'access_roles';
		
		public $customer_source = '';
		public $default_reference = '';
		public $label = 'Access Role';
		
		public $basic_data = array(
			'access_to_crud' => 1,	//(add, eidt, view, delete, export) will be available in access control
			//'exclude_from_crud' => array(),
			'more_actions' => array(
				'actions' => array(
					'title' => 'Actions',
					'data' => array(
						'd1' => array(
							'clone' => array(
								'todo' => 'save_clone_role',
								'title' => 'Clone Role & Capabilities',
								'text' => 'Clone Role',
								'attributes' => ' confirm-prompt="Clone this Access Role & Capabilities" ',
							),
						),
					),
				),
				'manage_cap' => array(
					'todo' => 'manage_access_roles',
					'title' => 'Manage Capabilities',
					'text' => 'Manage Capabilities',
				),
			),
		);
		
		public $table_fields = array(
			'role_name' => 'access_roles001',
			'accessible_functions' => 'access_roles002',
			'data' => 'access_roles003',
			'role_type' => 'access_roles004',
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
			'hide_signatories' => 1             //Determines whether or not to enable the Signatory portions of the export component
		);
		
		function access_roles(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
			case 'create_new_access_roles':
				$returned_value = $this->_generate_new_data_capture_form2();
			break;
			case 'display_all_records':
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
				
				//Add ID of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'manage_access_roles':
				$returned_value = $this->_manage_access_roles();
			break;
			case 'save_manage_access_role3':
			case 'save_manage_access_role2':
			case 'save_manage_access_role':
				$returned_value = $this->_save_manage_access_role();
			break;
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'save_clone_role':
				$returned_value = $this->_save_clone_role();
			break;
			case 'view_details2':
				$_GET["modal"] = 1;
				$returned_value = $this->_view_details();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _save_clone_role(){
			$ids = array();
			$error_msg = '';
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$ids = array( $_POST["id"] );
			}
			if( isset( $_POST["ids"] ) && $_POST["ids"] ){
				$ids = explode( ":::", $_POST["ids"] );
			}
			
			if( empty( $ids ) ){
				$error_msg = 'Invalid Selection';
			}
			
			if( ! $error_msg ){
				$now = date("U");
				$rand = rand(0,999);
				
				//new
				$f = array();
				$v = $f;
				
				$f[] = "`id`";
				$v[] = " MD5( CONCAT( 'x".$rand."', `".$this->table_name."`.`id` ) ) ";
				
				$f[] = "`created_by`";
				$v[] = "'" . $this->class_settings["user_id"] . "'";
				
				$f[] = "`modified_by`";
				$v[] = "'" . $this->class_settings["user_id"] . "'";
				
				$f[] = "`creation_date`";
				$v[] = "'" . $now . "'";
				
				$f[] = "`modification_date`";
				$v[] = "'" . $now . "'";
				
				$f[] = "`record_status`";
				$v[] = "'1'";
				
				foreach( $this->table_fields as $k => $v1 ){
					switch( $k ){
					case "role_name":
					case "accessible_functions":
					case "data":
					case "role_type":
						$f[] = "`". $v1 ."`";
					break;
					}
					
					switch( $k ){
					case "role_name":
						$v[] = " CONCAT( 'Clone - ', `".$this->table_name."`.`". $this->table_fields["role_name"] ."` ) ";
					break;
					default:
						$v[] = " `".$v1."` ";
					break;
					}
				}
				
				$query = "INSERT INTO `".$this->class_settings['database_name']."`.`".$this->table_name."` ( ". implode(', ', $f ) ." ) SELECT ".implode(', ', $v )." FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`id` IN ('". implode("', '", $ids ) ."') ";
			
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'UPDATE',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				execute_sql_query($query_settings);
				
				if( class_exists( 'cAccess_role_status' ) ){
					$acs = new cAccess_role_status();
					//new
					$f = array();
					$v = $f;
					
					$f[] = "`id`";
					$v[] = " MD5( CONCAT( 'x".$rand."', `".$acs->table_name."`.`id` ) ) ";
					
					$f[] = "`created_by`";
					$v[] = "'" . $this->class_settings["user_id"] . "'";
					
					$f[] = "`modified_by`";
					$v[] = "'" . $this->class_settings["user_id"] . "'";
					
					$f[] = "`creation_date`";
					$v[] = "'" . $now . "'";
					
					$f[] = "`modification_date`";
					$v[] = "'" . $now . "'";
					
					$f[] = "`record_status`";
					$v[] = "'1'";
					
					foreach( $acs->table_fields as $k => $v1 ){
						switch( $k ){
						case "access_role_id":
						case "status":
						case "reference":
						case "reference_table":
							$f[] = "`". $v1 ."`";
						break;
						}
						
						switch( $k ){
						case "access_role_id":
							$v[] = " MD5( CONCAT( 'x".$rand."', `".$acs->table_name."`.`". $acs->table_fields["access_role_id"] ."` ) ) ";
						break;
						default:
							$v[] = " `".$v1."` ";
						break;
						}
					}
					
					$query = "INSERT INTO `".$this->class_settings['database_name']."`.`".$acs->table_name."` ( ". implode(', ', $f ) ." ) SELECT ".implode(', ', $v )." FROM `".$this->class_settings['database_name']."`.`".$acs->table_name."` WHERE `".$acs->table_name."`.`". $acs->table_fields["access_role_id"] ."` IN ('". implode("', '", $ids ) ."') ";
				
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'EXECUTE',
						//'set_memcache' => 1,
						'tables' => array( $acs->table_name ),
					);
					execute_sql_query($query_settings);
				}
				return $this->_display_notification( array( 
					'type' => 'success', 
					'message' => '<h4>Clones Created</h4>Clones were created successfully', 
					'callback' => array( '$nwProcessor.reload_datatable' ) 
				) );
			}
			
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>';
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( 'type' => 'error', 'message' => $error_msg ) );
			}
		}
		
		protected function _get_select2(){
			$select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`".$this->table_fields["role_name"]."` as 'text' ";
			  
			$where = '';
			$limit = '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$_POST["term"] = trim( $_POST["term"] );
				$where = " AND `".$this->table_name."`.`".$this->table_fields["role_name"]."` LIKE '%".$_POST["term"]."%' ";
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$order = " ORDER BY `".$this->table_name."`.`".$this->table_fields["role_name"]."` ";
			if( isset( $_GET["role_type"] ) && $_GET["role_type"] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["role_type"]."` = '".$_GET["role_type"]."' ";
			}
			
			if( isset( $_GET["role_ids"] ) && $_GET["role_ids"] ){
				$where .= " AND `".$this->table_name."`.`id` IN ( '". implode( "', '", explode( ",", $_GET["role_ids"] ) ) ."' ) ";
			}
			
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$this->class_settings["order"] = $order;
			
			$return["items"] = $this->_get_records();
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}
		
		protected function _before_generate_form(){
			$this->class_settings["hidden_records"][ $this->table_fields["accessible_functions"] ] = 1;
		}
		
		protected function _generate_new_data_capture_form2(){
			$this->_before_generate_form();
			return $this->_generate_new_data_capture_form();
		}
		
		private function _save_manage_access_role(){
			$error_msg = '';
			if( ( isset( $_POST["id"] ) && $_POST["id"] && isset( $_POST["role_name"] ) && $_POST["role_name"] ) ){
				$id = $_POST["id"];
			}else{
				$error_msg = 'Invalid Parameters';
			}
			
			if( ! $error_msg ){
				$handle2 = '';
				$handle = '#dash-board-main-content-area';
				$action_to_perform = $this->class_settings['action_to_perform'];
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle2 = $this->class_settings[ 'html_replacement_selector' ];
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				$functions = '';
				$update = "";
				
				$u_edms = isset($_POST['user_wf_status']) && $_POST['user_wf_status'] ? $_POST['user_wf_status'] : '';

				if( isset( $_POST["accessible_functions"] ) && is_array( $_POST["accessible_functions"] ) && ! empty( $_POST["accessible_functions"] ) ){
					$functions = implode( ":::", $_POST["accessible_functions"] );
					$update = ", `".$this->table_name."`.`".$this->table_fields["accessible_functions"]."` = '".$functions."' ";
				}
				
				if( isset( $_POST["data"] ) && $_POST["data"] ){
					$data = $_POST["data"];
					$update = ", `".$this->table_name."`.`".$this->table_fields["data"]."` = '".$data."' ";
				}

				$dd = json_decode( $data, true );

				switch ($action_to_perform) {
					case 'save_manage_access_role3':
						if( $u_edms ){
							$post = $_POST;
							$iid = md5($u_edms);
							$this->class_settings['current_record_id'] = $iid;
							$ex = $this->_get_record();
							$line_item = array(
								'role_name' => 'IDV-'.rand(),
								'role_type' => 'wf_status',
								'data' => isset($dd['status']) && $dd['status'] ? ($dd['status']) : []
							);

							$r = [];
							if( isset($ex['id']) && $ex['id'] ){
								$_POST = array('id' => $ex['id']);
								$line_item['data'] = json_encode(array_merge( json_decode($ex['data'], true), $line_item['data'] ));
								// print_r($line_item);exit;
								$this->class_settings['update_fields'] = $line_item;
								$r = $this->_update_table_field();
							}else{
								$line_item['new_id'] = $iid;
								$line_item['data'] = json_encode($line_item['data']);
								$this->class_settings['line_items'][] = $line_item;
								$r = $this->_save_line_items();
								$r = $r ? ['saved_record_id' => $iid] : 0;
							}

							if( !(isset($r['saved_record_id']) && $r['saved_record_id']) ){
								$error_msg = "<h4><b>Save Failed</b></h4><p>Failed to save new access control settings</p>";
							}
						}else $error_msg = "<h4><b>Invalid User</b></h4><p>User selection was invalid</p>";
					break;
					default:
						$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET  `".$this->table_name."`.`".$this->table_fields["role_name"]."` = '".$_POST["role_name"]."', `".$this->table_name."`.`modified_by` = '".$this->class_settings["user_id"]."', `".$this->table_name."`.`modification_date` = '".date("U")."' ".$update ." WHERE `".$this->table_name."`.`id` = '".$id."' ";
						$query_settings = array(
							'revision_history' => array( "where" => " WHERE `".$this->table_name."`.`id` = '".$id."' " ),
							'u_field' => 'id',
							'u_value' => $id,
							'query_type' => 'UPDATE',
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'table' => $this->table_name,
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						execute_sql_query($query_settings);
					break;
				}

				if( !$error_msg ){
					if( isset( $dd[ 'status' ] ) && is_array( $dd[ 'status' ] ) && ! empty( $dd[ 'status' ] ) ){
						
						if( class_exists( 'cAccess_role_status' ) ){
							$line_items = array();
							$acs = new cAccess_role_status();
							foreach( $dd[ 'status' ] as $sk => $sv ){
								$sp = explode( ":::", $sk );

								if( isset( $sp[0] ) && $sp[0] == 'access_role_status' && ! empty( $sv ) ){
									if( isset( $sp[1] ) && $sp[1] && isset( $sp[2] ) && $sp[2] ){
										$dl = array(
											'reference_table' => $sp[1],
											'reference' => $sp[2],
											'access_role_id' => $id,
										);

										foreach( $sv as $svk => $svv ){
											$tdl = $dl;
											$tdl[ 'status' ] = $svk;
											if($u_edms){
												$tdl['user'] = $u_edms;
												unset($tdl['access_role_id']);
											}

											$line_items[] = $tdl;
										}
									}
								}
							}

							switch ($action_to_perform) {
								case 'save_manage_access_role3':
									$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$acs->table_name."` WHERE `". $acs->table_fields[ 'user' ] ."` = '". $u_edms ."' AND `". $acs->table_fields[ 'reference' ] ."` IN ('". implode("', '", array_unique( array_column( $line_items, 'reference') ) ) ."') ";
								break;
								default:
									$query = "DELETE FROM `".$this->class_settings['database_name']."`.`".$acs->table_name."` WHERE `". $acs->table_fields[ 'access_role_id' ] ."` = '". $id ."' ";
								break;
							}

							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'EXECUTE',
								'set_memcache' => 0,
								'tables' => array( $acs->table_name ),
							);
							execute_sql_query($query_settings);

							if( ! empty( $line_items ) ){
								$acs->class_settings = $this->class_settings;
								//$acs->class_settings[ 'action_to_perform' ] = 'save_line_items';
								$acs->class_settings[ 'line_items' ] = $line_items;
								$acs->_save_line_items();

								// print_r( $acs->access_role_status() );exit();
								//$acs->access_role_status();
							}
						}
					}
					
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					$this->class_settings[ 'current_record_id' ] = $id;
					//$this->class_settings["return_data"] = 1;
					$this->_get_record();
					
					$err = new cError('010011');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Successful Update</h4>';
					$return = $err->error();
					$return["status"] = "new-status";

					switch ($action_to_perform) {
						case 'save_manage_access_role3':
							$return['html_replacement'] = $return['html'];
							$return['html_replacement_selector'] = '#'.$handle2;
						break;
						default:
							unset( $return["html"] );
							$return["re_process"] = 1;
							$return["re_process_code"] = 1;
							$return["mod"] = 1;
							$return["id"] = 1;
							$return["action"] = '?action='.$this->table_name.'&todo=display_all_records_full_view';
							
							if( $handle2 ){
								$return["action"] .= '&html_replacement_selector=' . $handle2;
							}
							
							$return["do_not_reload_table"] = 1;
						break;
					}
					
					return $return;

				}
				
				
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => "error", "message" => $error_msg ) );
			}
		}
		
		private function _manage_access_roles(){
			
			$status_table = array();
			$error_msg = '';
			
			$hide_title = isset( $_GET["hide_title"] )?$_GET["hide_title"]:'';
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$id = $_POST["id"];
				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();
				
				if( ! ( isset( $e["id"] ) && $e["id"] ) ){
					$error_msg = 'Unable to Retrieve Access Role';
				}
			}else{
				$error_msg = 'Invalid Record ID';
			}
			
			if( ! $error_msg ){
				$filename = 'manage-access-roles';
				
				$handle = '#dash-board-main-content-area';
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				//$this->class_settings[ 'data' ][ 'functions' ] = get_accessible_functions();
				$package_function = '';
				$package_function2 = '';
				$package_function3 = '';
				$pkc = get_package_option( array( "package" => 1 ) );
				
				switch( $pkc ){
				case "mis":
					
					switch( $e["role_type"] ){
					case "api":
					break;
					default:
						//get states & get lgas
						$_GET["all"] = 1;
						
						$audit = new cState_list();
						$audit->class_settings = $this->class_settings;
						//$audit->class_settings["action_to_perform"] = 'get_all_states';
						$audit->class_settings["action_to_perform"] = 'get_select2';
						$states = $audit->state_list();
						
						$audit = new cLga_list();
						$audit->class_settings = $this->class_settings;
						//$audit->class_settings["action_to_perform"] = 'get_all_states';
						
						$audit->class_settings["select_fields"] = array();
						$audit->class_settings["select_fields"]["state"] = $audit->table_fields["state"];
						
						$audit->class_settings["return_indexed"] = array( 'state', 'id' );
						$audit->class_settings["action_to_perform"] = 'get_select2';
						$lgas = $audit->lga_list();
						
						$this->class_settings[ 'data' ][ 'states_smart_options' ] = array(
							'access_all_states' => 'Access All States',
							'access_all_lgas' => 'Access All LGAs',
							'access_my_state' => 'Access My State',
							'access_my_lga' => 'Access My LGA',
						);
						$this->class_settings[ 'data' ][ 'states' ] = isset( $states["items"] )?$states["items"]:array();
						$this->class_settings[ 'data' ][ 'lga' ] = $lgas;
						
						
						$status_table["workflow"] = get_workflow_status();
						$status_table["tickets"] = get_ticket_status_options();

						
						$this->class_settings[ 'data' ][ 'status_table' ] = $status_table;
					break;
					}
					
					$package_function = 'mis_version2_menu';
					$package_function2 = 'mis_version2_submenu';
				break;
				default:
					if( function_exists( $pkc . "_version2_menu" ) && function_exists( $pkc . "_version2_submenu" ) ){
						$package_function = $pkc . "_version2_menu";
						$package_function2 = $pkc . "_version2_submenu";
					}
				break;
				}
			}
			
			if( class_exists( 'cNwp_workflow' ) ){
				$nw = new cNwp_workflow();

				$w = 'workflow';
				$ws = 'workflow_settings';
				$ccl = $nw->load_class( array( 'class' => array( $w, $ws ), 'initialize' => 1 ) );

				if( isset( $ccl[ $ws ]->table_name ) && isset( $ccl[ $w ]->table_name ) ){
					$ccl[ $ws ]->class_settings = $this->class_settings;
					$es = $ccl[ $ws ]->_get_records();

					if( isset( $es[0]["id"] ) && $es[0]["id"] ){
						//print_r( $status_table );exit();
						foreach( $es as $ek ){
							$ek["custom_options"]["for_access_role"] = 1;
							
							$status_table[ $ek[ 'name' ] ][ 'access_role_status:::'. $ccl[ $ws ]->table_name .':::'.$ek[ 'id' ] ] = $ccl[ $w ]->get_workflow_status( $ek );
							
							//print_r( $ccl[ $w ]->get_workflow_status( $ek ) );exit();
							// $status_table[ $ek[ 'name' ] ] = get_workflow_status( $ek );
						}
								//print_r( $status_table );exit();
					}else{
						// $error_msg = '<h4><strong>Invalid Workflow Settings</strong></h4>';
					}
				}
			}
			
			if( defined("NWP_REPORT_ACCESS_ROLE") && NWP_REPORT_ACCESS_ROLE ){
				$repo = get_ims_report_types();
				$ttls = array();
				if( ! empty( $repo ) && is_array( $repo ) ){
					foreach( $repo as $rk => $rv ){
						if( isset( $rv["options"] ) && is_array( $rv["options"] ) && ! empty( $rv["options"] ) ){
							foreach( $rv["options"] as $rk2 => $rv2 ){
								if( ! $rv2 ){
									unset( $rv["options"][ $rk2 ] );
								}
							}
							
							$rk1 = isset( $rv["title"] )?$rv["title"]:$rk;
							if( isset( $ttls[ $rk1 ] ) && $ttls[ $rk1 ] != $rk ){
								$rk1 .= ' - '.$rk;
							}
							$ttls[ $rk1 ] = $rk;
							$status_table[ $rk1 ][ 'access_role_status:::nwp_report:::'.$rk ] = $rv["options"];
						}
					}
				}
				//print_r( get_ims_report_types() ); exit;
			}
			
			if( class_exists( 'cNwp_client_notification' ) ){
				$nw = new cNwp_client_notification();
				$status_table = array_merge( $status_table, $nw->get_capabilities( $e ) );
			}
			
			if( ! empty( $status_table ) )$this->class_settings[ 'data' ][ 'status_table' ] = $status_table;
			
			
			if( ! $error_msg ){
				switch( $e["role_type"] ){
				case "api":
					$api = new cApi_definition();
					$api->class_settings = $this->class_settings;
					
					$api->class_settings[ 'overide_select' ] = " `id`, `". $api->table_fields[ 'api_method' ] ."` as 'api_method', `". $api->table_fields[ 'request_type' ] ."` as 'request_type', `". $api->table_fields[ 'title' ] ."` as 'name' ";
					
					$this->class_settings[ 'data' ][ 'api_methods' ] = $api->_get_records();
				break;
				default:
					/* $functions = new cFunctions();
					$functions->class_settings = $this->class_settings;
					$functions->class_settings["action_to_perform"] = 'get_functions';
					$functions->class_settings["where"] = " WHERE `record_status` = '1' ORDER BY `functions001` ";
					$functions->class_settings["current_record_id"] = "";
					$functions->class_settings["return_data"] = 1;
					$fs = $functions->functions();
					
					$this->class_settings[ 'data' ][ 'functions' ] = $fs; */
					$this->class_settings[ 'data' ][ 'functions' ] = [];
					
					if( function_exists("frontend_tabs") ){
						$this->class_settings[ 'data' ][ 'frontend_tabs' ] = frontend_tabs();
						if( defined("NWP_NO_TABS") && NWP_NO_TABS ){
							$this->class_settings[ 'data' ][ 'frontend_tabs' ] = [];
						}
					}
					
					if( function_exists( $package_function ) && function_exists( $package_function2 ) ){
						$this->class_settings[ 'data' ][ 'frontend_menus' ] = $package_function();
						$this->class_settings[ 'data' ][ 'frontend_menus_function' ] = $package_function2();
					}
					
					
					if( (! get_single_store_settings()) && class_exists( 'cStores' ) ){
						$stores = new cStores();
						$stores->class_settings = $this->class_settings;
						$stores->class_settings["overide_select"] = " `id` as id, `".$stores->table_fields["name"]."` as 'name', 'Head Office' as 'branch' ";
						$this->class_settings[ 'data' ][ 'stores' ] = $stores->_get_records();

						$_cps = defined("ACCESS_ROLE_STORE_ALIAS") && ACCESS_ROLE_STORE_ALIAS ? ACCESS_ROLE_STORE_ALIAS : "Store";
						
						$this->class_settings[ 'data' ][ 'stores_smart_options' ] = array(
							'access_all_stores' => 'Access All '.$_cps.'s',
							'access_my_store' => 'Access My '.$_cps,
							'select_store' => 'Select '.$_cps.' after Login',
							'select_sub_store' => 'Select Sub-'.$_cps.' after Login',
						);
					}
					
					if( defined("NWP_REPORT_ACCESS_ROLE") && NWP_REPORT_ACCESS_ROLE ){
						$this->class_settings[ 'data' ][ 'stores_smart_options' ]['_all_reports_'] = 'All Reports';
					}
					
					$this->class_settings[ 'data' ][ 'stores_smart_options' ]['_all_caps_'] = '*All Capabilities';
					
					$audit = new cAudit();
					$this->class_settings[ 'data' ][ 'basic_crud' ] = $audit->_get_project_classes( array( "for_basic_crud" => 1 ) );
				
					$this->class_settings[ 'data' ][ 'modules' ] = get_modules_in_application();
				break;
				}
				
				$this->class_settings[ 'data' ][ "hide_title" ] = $hide_title; 
				$this->class_settings[ 'data' ][ "event" ] = $e; 
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				$returning_html_data = $this->_get_html_view();
			}
		
			if( $error_msg ){
				return $this->_display_notification( array( "type" => "error", "message" => $error_msg ) );
			}
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( '$nwProcessor.set_function_click_event', "prepare_new_record_form_new", "nwResizeWindow.resizeWindowHeight" ),
			);
		}
		
		protected function _display_all_records_full_view2(){
			$this->class_settings[ "return_new_format" ] = 1;
			/* 
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$this->class_settings[ 'data' ][ 'development' ] = get_hyella_development_mode();
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->class_settings[ "custom_edit_button" ] = $this->_get_html_view();
			 */
			 
			/* 
			$this->class_settings[ "show_form" ] = 1;
			$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
			$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
			$this->class_settings[ "add_empty_select_option" ] = 1;

			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "date":
					$this->class_settings["form_values_important"][ $val ] = date("U");
					$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
				break;
				case "customer":
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
				break;
				default:
					$this->class_settings["hidden_records"][$val] = 1;
				break;
				}
			} */

			$this->class_settings['filter']['where'] = " AND `". $this->table_fields['role_type'] ."` != 'wf_status' ";
			return $this->_display_all_records_full_view();
		}
		
	}
?>
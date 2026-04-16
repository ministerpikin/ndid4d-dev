<?php
/**
 * enrolment_agent Class
 *
 * @used in  				enrolment_agent Function
 * @created  				Hyella Nathan | 12:11 | 15-Aug-2023
 * @database table name   	enrolment_agent
 */
	
	class cEnrolment_agent extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';

		public $table_name = 'enrolment_agent';
		
		public $default_reference = '';
		
		public $label = 'Enrolment Agent';
		
		private $associated_cache_keys = array(
			'enrolment_agent',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
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
			if( file_exists( dirname( __FILE__ ).'/dependencies/enrolment_agent.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/enrolment_agent.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
		}
	
		function enrolment_agent(){
			
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
			case 'control_panel':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache2();
			break;
			case 'save_line_items':
				$returned_value = $this->_save_line_items();
			break;
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _get_es_select2( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'get_select2':
					$nwp = new cNwp_device_management();
					$nwp->class_settings = $this->class_settings;
					$tb = "device_mgt";
					$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
					$_GET['search_fields'] = 'agentfirstname,agentlastname';
					$in[ $tb ]->table_name = $this->table_name;
					$in[ $tb ]->table_fields = $this->table_fields;
					$in[ $tb ]->class_settings['action_to_perform'] = 'get_es_select2';
					return $in[ $tb ]->_get_es_select2();
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		protected function _get_select2(){

			if( isset($this->plugin_instance->plugin_classes_config[ $this->table_name ]['use_es']) && $this->plugin_instance->plugin_classes_config[ $this->table_name ]['use_es'] ){
				return $this->_get_es_select2();
			}

			$select = " CONCAT( `". $this->table_name ."`.`". $this->table_fields[ 'agentfirstname' ] ."`, ' ', `". $this->table_name ."`.`". $this->table_fields[ 'agentlastname' ] ."`, ' - ', `". $this->table_name ."`.`serial_num` ) as 'text' ";
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "agentemail":
					if( $select )$select .= ", `". $this->table_name ."`.`".$val."` as '".$key."'";
					else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `". $this->table_name ."`.`".$val."` as '".$key."'";
				break;
				}
			}
			
			$where = isset( $this->class_settings['where'] ) && $this->class_settings['where'] ? $this->class_settings['where'] : '';
			$join = isset( $this->class_settings['join'] ) && $this->class_settings['join'] ? $this->class_settings['join'] : '';

			if( $where )unset( $this->class_settings['where'] );
			if( $join )unset( $this->class_settings['join'] );
			
			$limit = '';
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$_POST["term"] = trim( $_POST["term"] );
				$where .= " AND ( `".$this->table_name."`.`".$this->table_fields["agentemail"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["agentfirstname"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["agentlastname"]."` regexp '".$_POST["term"]."' ";

				if( is_numeric( $_POST[ 'term' ] ) ){
					$where .= " OR `".$this->table_name."`.`serial_num` = '".$_POST["term"]."' ";
				}

				$where .= " ) ";

			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ". $join ." WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields["agentfirstname"]."`, `".$this->table_name."`.`".$this->table_fields["agentlastname"]."` ".$limit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_items = execute_sql_query($query_settings);
			
			if( isset( $this->class_settings["return_data"] ) && $this->class_settings["return_data"] ){
				return $all_items;
			}
			
			$return = array( "items" => $all_items );
			
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}

		protected function _refresh_cache2(){
			$e_type = '';
			$err = '';

			if( isset($this->plugin_instance->plugin_classes_config[ $this->table_name ]['use_es']) && $this->plugin_instance->plugin_classes_config[ $this->table_name ]['use_es'] ){
				if ( class_exists('cNwp_orm') ) {
					$cn = new cNwp_orm;
					$al = $cn->load_class( [ 'class' => [ 'orm_elasticsearch' ], 'initialize' => 1 ] );
					$client = cOrm_elasticsearch::$esClient;
					if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
						$err = cOrm_elasticsearch::$esClientError;
					}

					if( !$err ){
						$size = defined("ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT") && ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT ? (int)ELASTIC_SEARCH_PAGINATION_DEFAULT_LIMIT : 10000;
						$params = [
						    'index' => $this->table_name,
							'size' => $size,
						    'body'  => [ 
						    	'query' => [
						    		'match' => [ 'record_status' => 1 ]
					    		]
					    	]
						];
						
						try{
							$rq = cOrm_elasticsearch::$esClient->search( $params );
							if( isset( $rq['hits']['hits'] ) && $rq['hits']['hits'] ){
								$rq = array_column( $rq['hits'][ 'hits' ], '_source' );
								if( is_array( $rq ) && ! empty( $rq ) ){
									$cache_key = $this->table_name;
									if( $this->build_list && $this->build_list_field ){
										$settings = array(
											'cache_key' => $cache_key . "-list",
											'directory_name' => $cache_key,
											'permanent' => true,
										);
										$list = get_cache_for_special_values( $settings );
									}
									
									foreach( $rq as $category ){
										
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

										$settings = array(
											'cache_key' => $cache_key . "-" . $category["id"],
											'cache_values' => $category,
											'directory_name' => $cache_key,
											'permanent' => true,
										);

										set_cache_for_special_values( $settings );
										
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
								}
							}
							return;
						}catch( \Exception $e ){
						    $err = $e->getMessage();
						}
					}
				}
			}
			// debug_print_backtrace();
			if( $err ){
				auditor("", "console", $this->table_name, array(
					"elevel" => 'fatal',
					"status" => $this->action_to_perform,
					"comment" => $err,
				) );
			}else{
				// return $this->_refresh_cache();
			}
		}
		
		protected function _view_details2(){
			
			$filename = '';
				
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$error_msg = '';
			$hide_buttons = isset( $_GET[ 'hide_buttons' ] ) ? $_GET[ 'hide_buttons' ] : '';
			$e_type = 'error';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg = 'Invalid Reference';
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
			case 'view_details2':
				if( ! $error_msg ){
					$this->class_settings["current_record_id"] = $_POST[ 'id' ];
					$e = $this->_get_record();
					if( isset( $e["id"] ) ){
						
					}else{
						$error_msg = 'Unable to retrieve patient details';
					}
				}
			break;
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
				if( ! $error_msg ){
					$opt = array();
					if( isset( $_GET[ 'click_btn' ] ) && $_GET[ 'click_btn' ] )$opt[ 'click_btn' ] = $_GET[ 'click_btn' ];
					$id = $e['id'];
					$opt[ 'id' ] = $id;

					$ak = 'heartbeatas';
					$opt[ 'action_groups' ][ $ak ]['title'] = 'View Other Data';
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_metadata&nwp_todo=display_all_records_others&hide_title=1&disable_btn=1&join_from=agent',
						'title' => 'All Enrolment Data',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_center&nwp_todo=display_all_records_others&hide_title=1&disable_btn=1&join_from=agent',
						'title' => 'All Enrolment Centers',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_device&nwp_todo=display_all_records_others&hide_title=1&disable_btn=1&join_from=agent',
						'title' => 'All Enrolment Devices',
					);
					
					$opt[ 'general_actions' ][ 'view_device' ] = array(
						'action' => 'nwp_enrolment_data',
						'todo' => 'execute&nwp_action=enrolment_device&nwp_todo=view_details',
						'title' => 'View Current Device',
						'custom_id' => $e[ 'device' ],
					);

					$opt[ 'hide_files' ] = 1;
					// $opt[ 'hide_comments' ] = 1;
					
					$opt[ 'plugin' ] = $this->plugin;
					$opt[ 'details_todo' ] = 'view_details';
					$opt[ 'title_text' ] = "Enrolment Agent - <b>" . get_name_of_referenced_record( array( 'id' => $e[ 'id' ], 'table' => $this->table_name ) ) . '</b>';
					$_GET[ 'modal' ] = 1;

					$this->class_settings[ 'open_module' ] = $opt;

					//set_current_customer( array( "customer" => $id ) );
					return $this->_open_module();
				}
			break;
			case 'view_details2':
				$_GET["modal"] = 1;
				$filename = "view-details.php";
					
				if( ! $error_msg ){
					if( isset( $e["id"] ) ){
						$this->_apply_basic_data_control( $e );
						
						/* $stb = array();
						if( isset( $e["status2"] ) ){
							switch( $e["status2"] ){
							case "sent_to_nurse":
								$stb = array( 'an_vi' => 1 );
							break;
							}
						}
						if( empty( $stb ) ){
							$stb = array( 'of' => 1 );
						} 
						
						$this->class_settings[ 'data' ][ 'standalone_buttons' ] = $stb;
						*/
						
						//$this->class_settings[ 'data' ][ 'more_data' ][ 'additional_params' ] = '&reference=' . $e["id"] . '&reference_table=' . $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
						$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
						$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
						
						$b["more_actions"] = $this->_get_html_view();
						$b["id"] = $e["id"];
						//$b["auth_data"] = $qd;
						
						$this->class_settings["data_items"] = array( $e["id"] => $e );
						$this->class_settings["other_params"] = $b;
					}else{
						$error_msg = 'Unable to Retrieve Record';
					}
				}
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			if( $hide_buttons )$this->class_settings[ 'data' ][ 'hide_buttons' ] = $hide_buttons;
			
			$this->class_settings[ 'data' ][ 'no_columns' ] = isset( $_GET["no_column"] )?$_GET["no_column"]:'';
			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
				if( $filename ){
					$this->class_settings["html_filename"] = array( $this->view_path . $filename );
				}
			}else{
				if( $filename ){
					$this->class_settings["html_filename"] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
			}
			
			$this->class_settings["modal_dialog_style"] = "width:40%;";
				
			$return = $this->_view_details();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_details2':
				if( isset( $return["html_replacement_selector"] ) && $return["html_replacement_selector"] ){
					$return["html_replacement_selector"] = $handle;
				}
			break;
			}
			
			return $return;
		}
		
		protected function _search_form2(){
			$where = "";
			
			foreach( array( 'customer', 'staff_responsible' ) as $key ){
				if( isset( $this->table_fields[ $key ] ) && isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
					//$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
					$where .= " AND `".$this->table_fields[ $key ]."` = '".$_POST[ $this->table_fields[ $key ] ]."' ";
				}
			}
			
			$key = 'date';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] ] , 1 );
				$where .= " AND `".$this->table_fields[ $key ]."` >= " . $this->class_settings[ $key ];
			}
			
			$key = 'date';
			$skey = "_range";

			if( isset( $_POST[ $this->table_fields[ $key ] . $skey ] ) && $_POST[ $this->table_fields[ $key ] . $skey ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] . $skey ] , 2 );

				$where .= " AND `".$this->table_fields[ $key ]."` <= " . $this->class_settings[ $key ];
			}
			
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}

		protected function _display_all_records_full_view2(){
			$where = '';
			$join = '';
			$group = '';
			$disable_btn = isset( $_GET[ 'disable_btn' ] ) ? $_GET[ 'disable_btn' ] : '';
			$spilt_screen = 0;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			$join_from = isset( $_GET[ 'join_from' ] ) ? $_GET[ 'join_from' ] : '';
			
			switch( $action_to_perform ){
			case "display_all_records_full_view_search":
			case "display_all_records_frontend_history":
				$show_form = 1;
				unset( $this->class_settings[ "full_table" ] );
			break;
			case "display_all_records_frontend":
				$spilt_screen = 0;

			break;
			case "display_all_records_others":
				$spilt_screen = 0;

				if( $join_from && $id ){

					$key = '';
					switch( $join_from ){
					case 'device':
						$key = 'device';
					break;
					}

					$ed = 'enrolment_metadata';
					$al = $this->plugin_instance->load_class( [ 'class' => [ $ed ], 'initialize' => 1 ] );

					$join = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $ed ."` ON `". $ed ."`.`". $al[ $ed ]->table_fields[ 'agent' ] ."` = `". $this->table_name ."`.`id` AND `". $ed ."`.`record_status` = '1' AND `". $ed ."`.`". $al[ $ed ]->table_fields[ $key ] ."` = '". $id ."' ";

					$this->class_settings['prefix_title'] = get_name_of_referenced_record( array( 'id' => $id, 'table' => 'enrolment_' . $key ) ) . ' - ';

					$group = " GROUP BY `". $ed ."`.`". $al[ $ed ]->table_fields[ 'agent' ] ."` ";
					// $this->label = 'Yoooooooooo';

					$this->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';

				}

			break;
			}

			if( $join ){
				$this->class_settings[ 'filter' ][ 'join' ] = $join;
				$this->class_settings[ 'filter' ][ 'join_count' ] = $join;
			}
			
			if( $group ){
				$this->class_settings[ 'filter' ][ 'group' ] = $group;
				$this->class_settings[ 'filter' ][ 'group_count' ] = $group;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 1;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				unset( $this->basic_data['more_actions'] );
			}
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_history":
				$this->datatable_settings[ "show_edit_button" ] = 1;
				unset( $this->basic_data['more_actions'] );
			break;
			}
			
			if( $show_form ){
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
				$this->class_settings[ 'add_empty_select_option' ] = 1;
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "date":
						$this->class_settings["form_values_important"][ $val ] = date("U");
						$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					case "staff_responsible":
					case "customer":
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					default:
						$this->class_settings["hidden_records"][$val] = 1;
					break;
					}
				}
			}

			if( $spilt_screen ){
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;
				
				$this->datatable_settings["split_screen"] = array();
				
				$this->datatable_settings["datatable_split_screen"] = array(
					'action' => '?action='. $this->plugin .'&todo=execute&nwp_action=' . $this->table_name . '&nwp_todo=view_details2&no_column=1',
					'col' => 4,
					'content' => get_quick_view_default_message_settings(),
				);
			}
			
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2(){
			
			$ddx = array();
			$error = '';
			$e = array();
			$etype = 'error';
			$save_app = 1;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'save_app_changes':
			break;
			}
			
			if( $error ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error ) );
			}
			
			switch( $save_app ){
			case 1:
				$return = $this->_save_app_changes();
			break;
			case 2:
				$return = $this->_save_changes();
			break;
			case 3:
				$return = $this->_update_table_field();
			break;
			case 4:
				$return = $this->_save_line_items();
				if( $return )$return = array( 'saved_record_id' => 1 );
			break;
			}
			
			// print_r( $return );exit;
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = "#dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				switch( $action_to_perform ){
				case 'save_app_changes':
				break;
				}
				
			}
			
			// Send notification to assigned persons
			switch( $action_to_perform ){
			case 'save_new_popup':
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$action_to_perform = $this->class_settings['action_to_perform'];
			$this->class_settings["skip_link"] = 1;
			
			switch ( $action_to_perform ){
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
			break;
			default:
			break;
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		protected function _apply_basic_data_control( $e = array() ){
			/* if( ! isset( $e["status"] ) ){
				return;
			} */
			
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
			
			/* 
			$show_cancel = 0;
			switch( $e["status"] ){
			case "cancelled":
			case "in_active":
				$show_cancel = 1;
			break;
			}

			if( ! $show_cancel ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1'] );
			}
			 */
			 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),

			'more_actions' => array(
				'sb2' => array(
					'todo' => 'control_panel',
					'title' => 'Open',
					'text' => 'Open',
					'button_class' => 'dark',
					'empty_container' => 1,
					// 'attributes' => ' confirm-prompt="Sample Button II" ',
				),
			),
		);
	}
	
	function enrolment_agent(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/enrolment_agent.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/enrolment_agent.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){

					$key = $return[ "fields" ][ "partner" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_partner',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_partner&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "device" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_device',
						'reference_plugin' => 'nwp_enrolment_data',
						'reference_keys' => array( 'model' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_device&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "lga" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'lga';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'lgas',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=lgas&nwp_todo=get_select2" data-params=".selected-state" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "state" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'state';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'states',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=states&nwp_todo=get_select2" data-params=".selected-country" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-state ';
					
					$key = $return[ "fields" ][ "country" ];
					$return[ "labels" ][ $key ][ 'field_key' ] = 'country';
					$return[ "labels" ][ $key ][ 'field_key_actual' ] = $key;
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'countries',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_locations&todo=execute&nwp_action=countries&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 selected-country ';
					
					$key = $return[ "fields" ][ "agentnin" ];
					$return[ "labels" ][ $key ][ 'obfuscate' ] = 1;
					
				}
				return $return[ "labels" ];
			}
		}
	}
?>
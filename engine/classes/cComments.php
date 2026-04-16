<?php
/**
 * comments Class
 *
 * @used in  				comments Function
 * @created  				15:58 | 18-Nov-2019
 * @database table name   	comments
 */
	
	class cComments extends cBase_class{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $package = '';
		public $table_package = '';
		public $table_name = 'comments';
		
		public $default_reference = '';
		public $use_package = 0;
		
		public $no_of_comments_limit = 3;
		
		public $label = 'Comments';
		
		private $associated_cache_keys = array(
			'comments',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, eidt, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
			'utility_buttons' => array(
				'comments' => 1,
				//'share' => 1,
				'attach_file' => 1,
				//'tags' => 1,
				'view_details' => 1,
				//'revision_history' => 1,
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/comments.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/comments.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function comments( $o = array() ){
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
				$returned_value = $this->_delete_records2();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case "display_queries":
			case "display_shared_with_me_unread":
			case "display_shared_with_me_read":
			case 'display_my_records_frontend':
			case 'get_message_tree_children':
			case 'display_all_records_full_view':
			case 'comments_search_list2':
			case 'display_all_records_frontend':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_multiple_new_comments':
			case 'save_new_popup':
			case 'save_app_changes':
			case 'save_new_comment_form':
			case 'save_child_comment_form':
			case 'mark_read':		
				$returned_value = $this->_save_app_changes2( $o );
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'edit_popup_form_in_popup':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
			case 'edit_label_from_comment':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list2_datatable':
			case 'search_list2_handsontable':
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log2();
			break;
			case 'view_details2_most_recent':
			case 'view_details_by_reference':
			case 'view_details2':
			case 'view_details':
			case 'view_more_comment2':
			case 'view_more_comment':
				$returned_value = $this->_view_details2( $o );
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case "get_record":
				$returned_value = $this->_get_record();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case "display_capture_window":
			case "add_comment2":
			case "add_comment":
			case "reply_comment":
				$returned_value = $this->_new_comment_window( $o );
			break;
			case 'dasboard_display_unresolved':
			case 'dasboard_display_resolved':
			case 'query_dashboard_options':
				$returned_value = $this->_query_dashboard();
			break;
			case "custom_save_comments":
				$returned_value = $this->_custom_save_comments();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _check_comment_crude_access(){
			if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
				$this->class_settings[ 'current_record_id' ] = $_POST[ 'id' ];
				$e = $this->_get_record();

				if( isset( $e[ 'created_by' ] ) && $e[ 'created_by' ] == $this->class_settings[ 'user_id' ] ){
					$this->class_settings[ 'overide_select' ] = " `". $this->table_name ."`.`id` ";
					$this->class_settings[ 'limit' ] = " LIMIT 1 ";
					$this->class_settings[ 'where' ] = " AND `". $this->table_name ."`.`". $this->table_fields[ 'reference' ] ."` = '". $_POST[ 'id' ] ."' AND `". $this->table_name ."`.`". $this->table_fields[ 'reference_table' ] ."` = '". $this->table_name ."' ";
					$r = $this->_get_records();

					if( isset( $r[0][ 'id' ] ) && $r[0][ 'id' ] ){
						return $this->_display_notification( array( "type" => 'error', "message" => '<h4><strong>Access Denied</strong></h4>Comment cannot be modified because it has children' ) );
					}
				}else{
					return $this->_display_notification( array( "type" => 'error', "message" => '<h4><strong>Access Denied</strong></h4>Comment created by <b>'.get_name_of_referenced_record( array( 'id' => $e[ 'created_by' ], 'table' => 'users' ) ) . '</b> therefore you cannot EDIT/DELETE this comment.' ) );
				}
			}
		}

		protected function _delete_records2(){
			$r = $this->_check_comment_crude_access();
			if( is_array( $r ) && ! empty( $r ) ){
				return $r;
			}

			return $this->_delete_records();
		}

		protected function _custom_save_comments(){
			$error = '';

			if( isset( $_POST[ 'reference' ] ) && $_POST[ 'reference' ] ) {
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query_type' => 'SELECT',
					'set_memcache' => 0,
					'tables' => array( $this->table_name ),
				);

				$time = time();
				$id2 = get_new_id();
				$ip_address = get_ip_address();

				//@bay4 - @steve @needs correction
				$query = "INSERT INTO `".$this->class_settings["database_name"]."`.`". $this->table_name ."` ( 

					`". $this->table_name ."`.`id`,

					`". $this->table_name ."`.`". $this->table_fields[ 'date' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'title' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'message' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'type' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'reference' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'reference_table' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'child_reference' ] ."`,
					`". $this->table_name ."`.`". $this->table_fields[ 'child_reference_table' ] ."`,

					`". $this->table_name ."`.`creator_role`,
					`". $this->table_name ."`.`record_status`,
					`". $this->table_name ."`.`creation_date`,
					`". $this->table_name ."`.`modification_date`,
					`". $this->table_name ."`.`modified_by`,
					`". $this->table_name ."`.`created_by`,
					`". $this->table_name ."`.`created_source`,
					`". $this->table_name ."`.`ip_address`  ) VALUES ( 
					'". $id2 ."', 
					
					'". ( isset( $_POST[ 'date' ] ) ? $_POST[ 'date' ] : time() ) ."',
					'". ( isset( $_POST[ 'title' ] ) ? $_POST[ 'title' ] : '' ) ."',
					'". ( isset( $_POST[ 'message' ] ) ? $_POST[ 'message' ] : '' ) ."',
					'". ( isset( $_POST[ 'type' ] ) ? $_POST[ 'type' ] : '' ) ."',
					'". ( isset( $_POST[ 'reference' ] ) ? $_POST[ 'reference' ] : '' ) ."',
					'". ( isset( $_POST[ 'reference_table' ] ) ? $_POST[ 'reference_table' ] : '' ) ."',
					
					'". ( isset( $_POST[ 'child_reference' ] ) ? $_POST[ 'child_reference' ] : '' ) ."',
					'". ( isset( $_POST[ 'child_reference_table' ] ) ? $_POST[ 'child_reference_table' ] : '' ) ."',
					'". ( isset( $_POST[ 'creator_role' ] ) ? $_POST[ 'creator_role' ] : '' ) ."',
					'1',
					'". $time ."', 
					'". $time ."', 
					'". $this->class_settings[ 'user_id' ] ."', 
					'". $this->class_settings[ 'user_id' ] ."', 
					'',
					'". $ip_address ."' ) ";

				$query_settings[ 'query_type' ] = 'EXECUTE';
				$query_settings[ 'query' ] = $query;
				// print_r( $query );exit;
				execute_sql_query($query_settings);

				$return = $this->_display_notification( array( "type" => 'error', "message" => 'Saved Successfully') );
				$return[ 'javascript_functions' ] = array( ( isset( $_GET[ 'callback' ] ) ? $_GET[ 'callback' ] : '' ) );

				return $return;
			}

		}

		protected function _get_select2(){
			
			$where = '';
			$limit = '';
			$search_term = '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` regexp '".$search_term."' ) ";
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
				case "name":
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
				break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
				}
			}
			
			$select .= ", concat( `".$this->table_fields["name"]."`, ' - ', `serial_num` ) as 'text'";
			
			
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			
			$data1 = $this->_get_all_customer_call_log();
			return array( "items" => $data1, "do_not_reload_table" => 1 );
		}
		
		protected function _query_dashboard(){


			$handle = isset( $this->class_settings['html_replacement_selector'] ) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'dashboard-con';
			
			$js = $data = $a =  array();
			
			$error = "";
			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = 'nipc-dashboard';

			$d_status = 'unresolved';

			switch($action_to_perform){
				case 'dasboard_display_resolved':
					$d_status = 'resolved';
				case 'dasboard_display_unresolved':
					$data['sub-container'] = 1;
				break;
				default:
					$this->class_settings['where'] = "AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'eosic_business_registration'";
					$r = $this->_get_records();			
					foreach( $r as $record ){
						switch($record['status']){
							case 'resolved':
							case 'unresolved':
								if(isset($a[ $record[ 'status' ] ]) && $a[ $record[ 'status' ] ] ) $a[ $record[ 'status' ] ] += 1;
								else $a[ $record[ 'status' ] ] = 1;
							break;
						}
					}
					$data['total'] = count($r);
					
				break;
			}

			$this->class_settings['where'] = "AND `".$this->table_name."`.`".$this->table_fields['reference_table']."` = 'eosic_business_registration' AND `".$this->table_name."`.`".$this->table_fields['status']."` = '".$d_status."'";

			$data[ 'records' ][$d_status] = $this->_get_records();

			$data['progress_count'] = $a;

			$data['active_status'] = $d_status;

			$data['menu_options'] = array(
				's' => array(
					"title" => "Resolved",
					"menu_key" => "resolved",
					"action" => $this->table_name,
					"todo" => "dasboard_display_resolved",
					"html_replacement_selector" => "quick-view-placeholder"
				),
				'a' => array(
					"title" => "Unresolved",
					"menu_key" => "unresolved",
					"action" => $this->table_name,
					"todo" => "dasboard_display_unresolved",
					"html_replacement_selector" => "quick-view-placeholder"
				),
			);

			$this->class_settings['data'] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			
			$html = $this->_get_html_view();
			
			return array(
				'status' => 'new-status',
				'html_replacement' => $html,
				'html_replacement_selector' => '#'.$handle,
				'javascript_functions' => $js
			);
			
			$error = $error ? $error : "Unknown Error";
			
			return $this->_display_notification( array ('type' => 'error', 'message' => $error ) );
		}
		
		public function _close_comments( $o = array() ){
			if( isset( $o["type"] ) && $o["type"] ){
				switch( $o["type"] ){
				case "query":
					$where = " AND `". $this->table_fields["reference"] ."` = '". $o["reference"] ."' AND `". $this->table_fields["reference_table"] ."` = '". $o["reference_table"] ."' AND `". $this->table_fields["type"] ."` = '". $o["type"] ."' ";
					
					
					if( isset( $o["notify_creator"] ) && $o["notify_creator"] ){
						//check for queries
					}
					
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_name."`.`". $this->table_fields[ 'status' ] ."` = 'read', `".$this->table_name."`.`". $this->table_fields[ 'status2' ] ."` = 'resolved' WHERE `record_status` = '1' AND `". $this->table_fields[ 'status2' ] ."` = 'pending' " . $where;
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query( $query_settings );
				break;
				}
			}
			
		}
		
		protected function _view_details2( $o = array() ){
			
			$filename = 'view-file-and-children.php';
			$set_replacement_handle = 0;
			$modal_style = "min-width:35%;";
			$get_customer_balance = 0;
			$emails = '';
			$action_to_perform = $this->class_settings['action_to_perform'];

			$id = isset( $_POST["id"] ) ? $_POST["id"] : '';
			
			switch ( $action_to_perform ){
			case 'view_details2':
				$_GET[ 'table' ] = $this->table_name;
				$_GET["modal"] = 1;
				$set_replacement_handle = 1;
			break;
			case 'view_details':
				$_GET[ 'table' ] = $this->table_name;
			break;
			case 'view_details2_most_recent':
				$set_replacement_handle = 1;
			break;
			}

			if( isset( $_GET[ 'preview_replacement_selector' ] ) && $_GET[ 'preview_replacement_selector' ] ){
				$this->class_settings[ 'data' ][ 'preview_replacement_selector' ] = $_GET[ 'preview_replacement_selector' ];
			}

			$title = isset( $_GET[ 'title' ] ) && $_GET['title'] ? rawurldecode( $_GET[ 'title' ] ) : 'Comment';

			switch ( $action_to_perform ){
			case 'view_details2':
			case 'view_details':
			case 'view_details2_most_recent':
			case 'view_details_by_reference':

				$this->class_settings["hide_export"] = 1;

				$no_error = isset( $_GET[ "no_error" ] )?$_GET[ "no_error" ]:'';
				$parent_id = '';
				
				if( ! ( isset( $id ) && $id ) ){
					return $this->_display_notification( array( "type" => 'error', "message" => 'Invalid Reference ID') );
				}
				$type2 = isset( $_GET[ 'type' ] ) ? $_GET[ 'type' ] : '';
				$table = isset( $_GET[ 'table' ] ) ? $_GET[ 'table' ] : '';

				switch( $table ){
				case 'workflow':
					$this->no_of_comments_limit = 1;
				break;
				}
				
				$ids = array();
				
				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();

				switch ( $action_to_perform ){
				case 'view_details2_most_recent':
					$no_error = 1;
					$this->class_settings["return_html"] = 1;
				case 'view_details_by_reference':
					// Get parent Comment

					switch ( $table ){
					case $this->table_name:
						$ids[] = $e;
					break;
					default:
						$this->class_settings["where"] = " AND `". $this->table_fields["reference"] ."` = '". $id ."' AND `". $this->table_fields["reference_table"] ."` = '". $table ."' AND `". $this->table_fields["type"] ."` = 'parent' ";
						
						switch( $type2 ){
						case "query":
							$this->class_settings["where"] = " AND `". $this->table_fields["reference"] ."` = '". $id ."' AND `". $this->table_fields["reference_table"] ."` = '". $table ."' AND `". $this->table_fields["type"] ."` = 'query' AND `". $this->table_fields["status2"] ."` = 'pending' ";
						break;
						}
						
						if( isset( $o["where"] ) && $o["where"] ){
							$this->class_settings["where"] = $o["where"];
						}
						
						$this->class_settings["order_by"] = " ORDER BY `modification_date` DESC ";
						$this->class_settings["limit"] = " LIMIT " . $this->no_of_comments_limit;
						$ids = $this->_get_records();
					break;
					}

					$this->class_settings["other_params"][ 'parent' ] = $ids;
				break;
				case 'view_details2':
				case 'view_details':

					// $this->class_settings[ 'data_items' ][] = $e;

					if( ! empty( $e ) && isset( $e['id'] ) ){
						
						switch( $e['type'] ){
						case "query":
							$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_name."`.`". $this->table_fields[ 'status' ] ."` = 'read' WHERE `record_status` = '1' AND `id` = '". $e['id'] ."' AND `". $this->table_fields[ 'status' ] ."` = 'unread' ";
							
							$query_settings = array(
								'database' => $this->class_settings['database_name'],
								'connect' => $this->class_settings['database_connection'],
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 1,
								'tables' => array( $this->table_name ),
							);
							if( ! execute_sql_query( $query_settings ) ){
								$audit_params = array();
								$audit_params["elevel"] = 'fatal';
								$audit_params["status"] = $action_to_perform;
								$audit_params["comment"] = 'Unable to Update Query Status to read';
								auditor( "", "console", $this->table_name , $audit_params );
							}
							$title = 'Query';
							$this->label = $title;
						break;
						default:
							// Mark notification as read
							if( class_exists( 'cShare' ) ){
								$sh = new cShare();
								$sh->class_settings = $this->class_settings;
					
								$query = "UPDATE `".$this->class_settings['database_name']."`.`".$sh->table_name."` SET `".$sh->table_name."`.`". $sh->table_fields[ 'status' ] ."` = 'read' WHERE `record_status` = '1' AND `". $sh->table_fields[ 'reference' ] ."` = '". $e['id'] ."' AND `". $sh->table_fields[ 'share' ] ."` = '". $this->class_settings[ 'user_id' ] ."' AND `". $sh->table_fields[ 'reference_table' ] ."` = '". $this->table_name ."' AND `". $sh->table_fields[ 'status' ] ."` = 'unread' ";
								
								$query_settings = array(
									'database' => $this->class_settings['database_name'],
									'connect' => $sh->class_settings['database_connection'],
									'query' => $query,
									'query_type' => 'UPDATE',
									'set_memcache' => 1,
									'tables' => array( $sh->table_name ),
								);
								if( ! execute_sql_query( $query_settings ) ){
									$audit_params = array();
									$audit_params["elevel"] = 'fatal';
									$audit_params["status"] = $action_to_perform;
									$audit_params["comment"] = 'Unable to Update Comment Status to read';
									auditor( "", "console", $this->table_name , $audit_params );
								}
							}
						break;
						}						

						if( $e['reference_table'] == $this->table_name && $e[ 'type' ] == 'child' ){
							$ids[0]['id'] = $e[ 'reference' ];
							$this->class_settings[ 'current_record_id' ] = $e[ 'reference' ];
							$this->class_settings["other_params"][ 'parent' ][] = $this->_get_record();
						}else{
							$ids[] = $e;
							$this->class_settings["other_params"][ 'parent' ][] = $e;
						}
					}

				break;
				}
				
				if( ! empty( $ids ) ){
					
					// Get child Comment of each parent
					$this->class_settings["where"] = " AND `". $this->table_fields["reference"] ."` IN ( '". implode( "','", array_column( $ids, 'id' ) )."' ) AND `". $this->table_fields["reference_table"] ."` = '". $this->table_name ."' ";
					
					switch( $table ){
					case 'workflow':
						$this->no_of_comments_limit = 100;
					break;
					}
					
					$this->class_settings["limit"] = " LIMIT " . $this->no_of_comments_limit;

					$this->class_settings["other_params"]["children"] = $this->_get_records();
				}else{
					$error_msg = 'No '. $title .' Found';
					
					if( $no_error ){
						return array(
							"status" => "new-status"
						);
					}
				}
				
				if( isset( $this->class_settings["hide_export"] ) && $this->class_settings["hide_export"] ){
					$this->class_settings["other_params"][ 'hide_export' ] = $this->class_settings["hide_export"];
				}
				
				$this->class_settings["other_params"][ 'tablex' ] = $table;
				$this->class_settings["other_params"]["id"] = $id;
				$filename = 'view-comment';

			break;
			case 'view_more_comment2':
			case 'view_more_comment':
				
				if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
					return $this->_display_notification( array( "type" => 'error', "message" => 'Invalid Reference ID') );
				}

				$reference = isset( $_GET[ 'reference' ] ) ? $_GET[ 'reference' ] : '';
				$reference_table = isset( $_GET[ 'reference_table' ] ) ? $_GET[ 'reference_table' ] : '';
				
				// Get parent Comment
				$this->class_settings["where"] = " AND `". $this->table_fields["reference"] ."` = '". $_POST["id"] ."' AND `". $this->table_fields["reference_table"] ."` = '". $this->table_name ."' ";
				switch ( $action_to_perform ){
				case 'view_more_comment2':
					$this->class_settings["where"] = " AND `". $this->table_fields["reference"] ."` = '". $reference ."' AND `". $this->table_fields["reference_table"] ."` = '". $reference_table ."' ";
				break;
				}

				$limit = ( isset( $_POST[ 'limit' ] ) ? (int)$_POST[ 'limit' ] : 0 ) + $this->no_of_comments_limit;
				$this->class_settings["limit"] = " LIMIT " . $limit;

				$e = $this->_get_records();
				if( is_array( $e ) && ! empty( $e ) && isset( $e[0] ) ){
					$this->class_settings[ 'data' ]["items"] = $e;
				}else{
					$error_msg = 'No '. $title .' Found';
				}
				$this->class_settings["html"] = array( 'html-files/templates-1/'.$this->table_name.'/append-reply.php' );
				
				return array(
					"status" => "new-status",
					'html_replacement' => $this->_get_html_view(),
					'html_replacement_selector' => '.replies-' . $_POST[ 'id' ],
					'javascript_functions' => isset( $_POST[ 'callback' ] ) ? array( $_POST[ 'callback' ] ) : array(),
				);
			break;
			}
			
			if( $set_replacement_handle ){
				$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
				$handle = '#'.$handle;
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
					$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				}
			}

			if( isset( $this->class_settings[ 'plugin' ] ) && $this->class_settings[ 'plugin' ] ){
				$this->class_settings[ 'data' ][ 'plugin' ] = $this->class_settings[ 'plugin' ];
			}
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				$this->class_settings["other_params"]["no_popup"] = 1;
			}
			
			//set to effectively pass custom data
			//$this->class_settings["data_items"] = array();
			
			$this->class_settings[ 'data' ][ 'action_to_perform' ] = $action_to_perform;
			$this->class_settings['data']['title'] = $title;
			$this->class_settings["html_filename"] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$this->class_settings["modal_dialog_style"] = $modal_style;
				
			$return = $this->_view_details();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_details2_most_recent':
			case 'view_details2':
				if( isset( $return["html_replacement_selector"] ) && $return["html_replacement_selector"] ){
					
					$return["html_replacement_selector"] = $handle;
				}
			break;
			}
			
			
			return $return;
		}
		
		protected function _search_customer_call_log2(){
			switch( $this->class_settings[ 'action_to_perform' ] ){
			case 'search_list2':
			case 'workflow_search_list2':
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'type' ] ."` = 'parent' ";

				// if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
				// 	switch( $this->class_settings[ 'action_to_perform' ] ){
				// 	case 'workflow_search_list2':
				// 		$this->class_settings[ 'data' ][ 'hidden_table_fields' ] = array( "reference" => $_POST[ 'id' ] );
					
				// 		$this->class_settings[ 'action_to_perform' ] = 'display_app_manager';
				// 		$return = $this->_display_app_view();

				// 		if( isset( $return[ 'html_replacement_selector' ] ) && isset( $_GET[ 'html_replacement_selector' ] ) ){
				// 			$return[ 'html_replacement_selector' ] = '#' . $_GET[ 'html_replacement_selector' ];
				// 		}
			
				// 		$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
				// 		// print_r( $_GET[ 'html_replacement_selector' ] );exit;
				// 		return $return;
				// 	break;
				// 	}
				// }
			break;
			}
			return $this->_search_customer_call_log();
		}

		protected function _new_comment_window( $o = array() ){

			$error_msg = '';
			$filename = 'new-comment';
			
			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			// print_r( $handle );exit;
			
			$this->class_settings[ 'data' ][ 'plugin' ] = isset( $_GET[ 'plugin' ] ) ? $_GET[ 'plugin' ] : '';
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			switch( $action_to_perform ){
			case 'display_capture_window2':
			case 'display_capture_window':
				
				$this->class_settings["action_to_perform"] = "new_popup_in_popup";

				// $fh = $this->_new_popup_form2();

				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-new-comment-form' );
				$this->class_settings[ 'data' ][ 'hide_fields' ] = array( 
					'tag' => 1,
				);
				$dd[ 'comment_form' ] = $this->_get_html_view();

				$dd["title"] = 'New Message';
			
				$this->class_settings[ 'data' ] = $dd;
			break;
			case 'reply_comment':
				$data = array();
				$this->class_settings["modal_title"] = 'Reply Comment';
				
				//$_POST[ 'id' ] = 'cs22165428814';
				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
					
					$this->class_settings["current_record_id"] = $_POST[ 'id' ];
					$parent = $this->_get_record();
					
					if( isset( $parent["id"] ) && $parent["id"] ){
						$this->class_settings[ 'data' ][ 'parent' ] = $parent;
						$this->class_settings[ 'data' ][ 'reference' ] = $parent[ 'id' ];
						$this->class_settings[ 'data' ][ 'reference_table' ] = $this->table_name;
						$filename = 'child-comment-form';

						$x = $this->table_name;
						$this->class_settings[ 'data' ][ 'fields' ] = $this->table_fields;
						$this->class_settings[ 'data' ][ 'labels' ] = $x();
					}else{
						$error_msg = '<h4>Unable to Retrieve Parent Reference</h4>';
					}
				}else{
					$error_msg = '<h4>Invalid Parent Reference</h4>';
				}
			break;
			case 'add_comment2':
			case 'add_comment':
				$no_popup = ( isset( $_GET[ 'modal' ] ) && $_GET[ 'modal' ] )?$_GET[ 'modal' ]:0;
				$this->class_settings["modal_title"] = isset( $this->class_settings["modal_title"] ) && $this->class_settings["modal_title"] ? $this->class_settings["modal_title"] : 'Add Comment';
				$this->class_settings[ 'data' ][ 'preview_html_replacement_selector' ] = isset( $this->class_settings["preview_html_replacement_selector"] ) && $this->class_settings["preview_html_replacement_selector"] ? $this->class_settings["preview_html_replacement_selector"] : '';

				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] && isset( $_GET[ 'table' ] ) && $_GET[ 'table' ] ){

					$title = isset($_GET['title']) && $_GET['title'] ? $_GET['title'] : ucfirst( $this->table_name );

					$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : 'parent';
					
					$data = array();
					$ids = explode( ',', $_POST[ 'id' ] );
					if( isset( $_POST["ids"] ) && $_POST["ids"] ){
						$ids = array_unique( explode(":::", $_POST["ids"] ) );	//04-feb-23
					}
					foreach( $ids as $idk => $idv ){
						if( ! $idv ){
							unset( $ids[ $idk ] );
						}
					}
					$ids = array_values( $ids );
					
					$count = count( $ids );

					$this->class_settings[ 'data' ][ 'reference' ] = implode( ",", $ids );
					$this->class_settings[ 'data' ][ 'reference_table' ] = $_GET[ 'table' ];
					if( isset( $_GET[ 'plugin' ] ) && $_GET[ 'plugin' ] ){
						$this->class_settings[ 'data' ][ 'plugin' ] = $_GET[ 'plugin' ];
					}
					
					$this->class_settings[ 'data' ][ 'hide_fields' ] = array( 
						'assigned_to' => 1,
						'title' => 1,
						'tag' => 1,
					);
					$cb_todo = 'view_details_by_reference';
					$cb_params = '';
					
					switch( $type ){
					case 'query':
						
						$this->class_settings["modal_title"] = 'New Query';
						unset( $this->class_settings[ 'data' ][ 'hide_fields' ][ 'title' ] );
						
						$this->class_settings[ 'data' ][ 'recent_title' ] = 'Recent Queries';
						$o["more_data"]['message_title'] = 'New Query';

						if( isset( $this->class_settings['data']['form_action'] ) ) $o['more_data']['form_action'] = $this->class_settings['data']['form_action'];
						
						//$cb_todo = 'view_details_by_reference2';
						$cb_params = '&type=' . $type . '&title=' . rawurlencode( 'Query' );
						
					break;
					}
					
					
					if( isset( $o["more_data"] ) ){
						$this->class_settings[ 'data' ][ 'more_data' ] = $o["more_data"];
					}
					
					if( ! $error_msg ){

						if( isset( $o["more_data"]["hide_most_recent"] ) && $o["more_data"]["hide_most_recent"] ){
							
						}else if( $count > 1 ){
							$this->class_settings[ 'data' ][ 'hide_fields' ][ 'tag' ] = 0;
						}else{
							
							$this->class_settings["callback"][ $this->table_name ][ $action_to_perform ] = array(
								'id' => $ids[0],
								//'action' => '?action=' . $this->table_name . '&todo=view_details_by_reference&html_replacement_selector=most-recent-comments&modal=1&table='.$_GET[ 'table' ].'&title='.$title,
								'action' => '?action=' . $this->table_name . '&todo='. $cb_todo .'&html_replacement_selector=most-recent-comments&modal=1&table=' . $_GET[ 'table' ] . $cb_params,
							);
						}
						$this->class_settings[ 'data' ][ 'count' ] = $count;

						$this->class_settings[ 'data' ][ 'title' ] = $title;

						$this->class_settings[ 'data' ][ 'type' ] = $type;


						$this->class_settings["modal_callback"] = ( isset( $_GET[ 'modal_callback' ] ) ? $_GET[ 'modal_callback' ] : '' );
						
						$filename = 'custom-new-comment-form';
					}
				}else{
					$error_msg = '<h4>Invalid Reference</h4>';
				}
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => 'error', "message" => $error_msg ) );
			}
			// print_r( $this->class_settings[ 'html' ] );exit;
			$returning_html_data = $this->_get_html_view();
			$js = array( "prepare_new_record_form_new" , "set_function_click_event" );

			if( isset( $_GET[ 'callback' ] ) && $_GET[ 'callback' ] )$js[] = $_GET[ 'callback' ];

			switch( $action_to_perform ){
			case 'add_comment2':
			case 'add_comment':
				if( ! $no_popup )return $this->_launch_popup( $returning_html_data, '#'.$handle, $js );
			break;
			}

			$return = array();
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#" . $handle;
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = $js;
			
			return $return;
		}
        
		protected function _search_form2(){
			$where = "";
			
			$key = 'customer';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
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
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$get_children = 0;
			
			$handle2 = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$handle = "#" . $handle2;
			
			$this->datatable_settings["split_screen"] = array();
			
			$this->datatable_settings["datatable_split_screen"] = array(
				'action' => '?action=' . $this->table_name . '&todo=view_details2',
				'col' => 4,
				'content' => get_quick_view_default_message_settings(),
			);
			
			switch( $action_to_perform ){
			// case "workflow_search_list2":
			case "comments_search_list2":
				$error_msg = '';
				if( isset( $_GET[ 'table' ] ) && $_GET[ 'table' ] ){
					$tb = $_GET[ 'table' ];

					$this->class_settings[ "frontend" ] = 1;
					
					$this->datatable_settings["show_refresh_cache"] = 0;
					$this->datatable_settings["show_add_new"] = 0;
					// $this->datatable_settings["show_edit_button"] = 0;
					$this->datatable_settings["show_advance_search"] = 0;
					//$this->datatable_settings["show_details"] = 0;

					$this->datatable_settings["utility_buttons"] = array();
					$this->datatable_settings["utility_buttons"][ 'view_details' ] = 1;
						
					$this->class_settings['title'] = 1;
					$this->class_settings['hide_title'] = 1;
					$this->datatable_settings['show_add_new'] = 0;
					// $this->datatable_settings['show_delete_button'] = 0;
					
					if( isset( $_POST["id"] ) && $_POST["id"] ){
						$this->class_settings[ 'filter' ][ 'where' ] = " AND `".$this->table_fields["reference"]."` = '". $_POST["id"] ."' AND `".$this->table_fields["reference_table"]."` = '". $tb ."' AND `".$this->table_fields["type"]."` = 'parent' ";
					}else{
						$error_msg = '<h4>Invalid Workflow Reference</h4>';
						
					}
				}else{
					$error_msg = "Undefined Source Table";
				}
				
				if( $error_msg ){
					return $this->_display_notification( array( "type" => 'error', "message" => $error_msg, "html_replacement_selector" => '#'.$handle2, 'do_not_display' => 1 ) );
				}
				
			break;
			case "get_message_tree_children":
				$data = array();
				
				if( isset( $_GET["get_children"] ) && $_GET["get_children"] ){
					$this->class_settings["action_to_perform"] = 'display_my_records_frontend';
				}else{
				
					$data[] = array(
						'id' => 'action='.$this->table_name.':::todo=display_capture_window:::html_replacement_selector=' . $handle2,
						'text' =>  'Compose',
						'icon' => 'icon-pencil',
					);
					
					$d = array(
						'reference_table' => $this->table_name,
						'share_tables' => array( "'users'" ),
						'shares' => array( "'". $this->class_settings["user_id"] ."'" ),
						'status' => 'unread',
					);
					$sh = new cShare();
					$sh->class_settings = $this->class_settings;
					$sh->class_settings["action_to_perform"] = 'get_share_count';
					$sh->class_settings["data"] = $d;
					$count = $sh->share();
					
					$utext = '';
					if( $count ){
						$utext = ' ('. nw_pretty_number( $count ) .')';
					}
					
					$box = array(
						"unread" => array(
							'text' => 'Unread' . $utext,
							'icon' => 'icon-envelope',
							'id' => 'action='.$this->table_name.':::todo=display_shared_with_me_unread:::html_replacement_selector=' . $handle2,
						),
						"read" => array(
							'text' => 'Read',
							'id' => 'action='.$this->table_name.':::todo=display_shared_with_me_read:::html_replacement_selector=' . $handle2,
						),
						"sent" => array(
							'text' => 'My Messages',
							'id' => 'action='.$this->table_name.':::todo=display_my_records_frontend:::html_replacement_selector=' . $handle2,
						),
					);
					
					foreach( $box as $bk => $bv ){
						$data[] = $bv;
					}
					return $data;
				}
				$this->datatable_settings["show_refresh_cache"] = 0;
				$this->datatable_settings["show_advance_search"] = 0;
				$where = " AND `". $this->table_name ."`.`". $this->table_fields['type'] ."` = 'parent'";
				$this->class_settings['filter']['where'] = $where;
			break;
			case "display_shared_with_me_unread":
			case "display_shared_with_me_read":
			case "display_my_records_frontend":
			case "display_all_records_frontend":
				$this->label = 'Messages';

				$where = " AND `". $this->table_name ."`.`". $this->table_fields['type'] ."` = 'parent'";
				if( isset( $_GET['status'] ) && $_GET['status'] ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields['status'] ."` = '". $_GET['status'] ."'";
				}
				$this->class_settings['filter']['where'] = $where;

				
				$this->datatable_settings["utility_buttons"] = array(
					'attach_file' => 1,
					//'tags' => 1,
					//'view_folders' => 1,
					'view_details' => 1,
				);
				
				$this->datatable_settings["show_refresh_cache"] = 0;
				$this->datatable_settings["show_add_new"] = 0;
				$this->datatable_settings["show_delete_button"] = 0;
				$this->datatable_settings["show_edit_button"] = 0;
				$this->datatable_settings["show_advance_search"] = 0;
				
				switch( $action_to_perform ){
				case "display_my_records_frontend":
					$this->datatable_settings["show_edit_button"] = 1;
				break;
				}
				
			break;
			case "display_queries":
				$this->datatable_settings["show_refresh_cache"] = 0;
				$this->datatable_settings["show_add_new"] = 0;
				$this->datatable_settings["show_delete_button"] = 0;
				$this->datatable_settings["show_edit_button"] = 0;
				$this->datatable_settings["show_advance_search"] = 0;
				unset( $this->datatable_settings["utility_buttons"] );
				$this->class_settings["frontend"] = 1;
				$this->class_settings["full_table"] = 1;
				
				$this->datatable_settings["split_screen"] = array();
				$this->datatable_settings["show_selection"]["table_fields_filter"] = 'query';
			
				$this->datatable_settings["datatable_split_screen"] = array();
				
				
				if( isset( $_GET['status'] ) && $_GET['status'] ){
					$this->class_settings['filter']['where'] = " AND `". $this->table_name ."`.`". $this->table_fields['status2'] ."` = '". $_GET['status'] ."'";
				}

				$this->datatable_settings["table_class"] = 'has-button';
				$this->datatable_settings["show_selection"]["title"] = '&nbsp;';
				$this->datatable_settings["show_selection"]["button_title"] = 'View Details';
				
				
				$this->datatable_settings["show_selection"]["button_action"] = "?action=". $this->table_name ."&todo=view_details&html_replacement_selector=" . $handle2;
			break;
			default:
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
				}
			break;
			}
			
			$this->datatable_settings["utility_buttons"] = array();
			
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2( $o = array() ){
			
			$updated = array();
			$ddx = array();
			$save_app = 1;
			$error = '';
			$e = array();
			$etype = 'error';
			
			$action_to_perform = $this->class_settings["action_to_perform"];
				
			$handle = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
			}

			if( isset( $this->class_settings["use_post"] ) && $this->class_settings["use_post"] ){
				$_POST = $this->class_settings["use_post"];
			}

			$uhsel = ( isset( $_POST[ 'preview_html_replacement_selector' ] ) && $_POST[ 'preview_html_replacement_selector' ] ) ? $_POST[ 'preview_html_replacement_selector' ] : '';

			switch( $action_to_perform ){
			case 'mark_read':
				$this->class_settings['update_fields'] = array( 'status' => 'read' );
				$save_app = 3;
			break;
			case 'save_new_comment_form':
			case 'save_child_comment_form':
				//$this->class_settings["action_to_perform"] = 'save_new_popup';
				
				switch( $action_to_perform ){
				case 'save_child_comment_form':
					if( ! ( isset( $_POST[ 'reference_table' ] ) && $_POST[ 'reference_table' ] && isset( $_POST[ 'reference' ] ) && $_POST[ 'reference' ] ) ){
						return $this->_display_notification( array( "type" => $etype, "message" => "Invalid Reference" ) );
					}
				break;
				}

				$ddx = $_POST;
				$_POST[ 'id' ] = '';

				if( isset( $ddx[ '_wysihtml5_mode' ] ) )unset( $ddx[ '_wysihtml5_mode' ] );

				$ref = ( isset( $ddx[ 'reference' ] ) && $ddx[ 'reference' ] ) ? $ddx[ 'reference' ] : '';

				if( isset( $ddx[ 'tags_name_existing' ] ) && $ddx[ 'tags_name_existing' ] && strpos( $ref, "," ) > -1 ){

					$tg = new cTags();
					$tg->class_settings = $this->class_settings;
					$tg->class_settings[ 'action_to_perform' ] = 'save_create_tag';

					$dx = array();
					$dx[ 'reference' ] = $ref;
					$dx[ 'reference_table' ] = $ddx[ 'reference_table' ];
					$_POST[ 'use_existing' ] = $ddx[ 'tags_name_existing' ];
					$_POST[ 'data' ] = json_encode( $dx );
					$r = $tg->tags()[ 'saved_record_id' ];

					if( ! ( isset( $r ) && $r ) ){
						$error = 'Unable to create tag items';
					}else{
						$ddx[ 'reference' ] = $r;
						$ddx[ 'reference_table' ] = $tg->table_name;
					}
				
				}else if( isset( $ddx[ 'tags_name' ] ) && $ddx[ 'tags_name' ] ){

					$tg = new cTags();
					$tg->class_settings = $this->class_settings;
					$tg->class_settings[ 'action_to_perform' ] = 'save_new_tag';

					$dx = array();
					$dx[ 'name' ] = $ddx[ 'tags_name' ];

					foreach( explode( ",", $ddx[ 'reference' ] ) as $value ){
						$dx[ 'tag_items' ][] = array(
							'reference' => $value,
							'reference_table' => $ddx[ 'reference_table' ],
						);
					}
					$_POST = $dx;
					$r = $tg->tags();

					if( ! ( isset( $r[ 'saved_record_id' ] ) && ! empty( $r ) ) ){
						$error = 'Unable to create tag';
					}else{
						$ddx[ 'reference' ] = $r[ 'saved_record_id' ];
						$ddx[ 'reference_table' ] = $tg->table_name;
					}
				}

				if( $ref ){
				}
				if( isset( $ddx[ 'reference_table' ] ) && $ddx[ 'reference_table' ] && $ref ){
					$this->class_settings["current_record_id"] = $ddx[ 'reference' ];
					$e = $this->_get_record();

					$plugin = '';
					$ptb = array();
					if( isset( $ddx[ 'plugin' ] ) && $ddx[ 'plugin' ] ){
						$plugin = $ddx[ 'plugin' ];
						$ptb[ $ddx[ 'reference_table' ] ] = $ddx[ 'reference_table' ];
					}else{
						$sptb = '';
						if( isset( $e[ 'reference_table' ] ) && $e[ 'reference_table' ] ){
							$sptb = $e[ 'reference_table' ];
						}else if( $ddx[ 'reference_table' ] !== $this->table_name ){
							$sptb = $ddx[ 'reference_table' ];
						}
					
						// print_r( $ddx );exit;
						if( ! $plugin && $sptb ){
							$db = new cDatabase_table();
							$db->class_settings = $this->class_settings;
							$stb = $sptb;
							if( substr( $sptb, -3 ) == '_sr' ){
								$stb = str_replace( "_sr", "", $sptb );
							}
							$db->class_settings[ 'where' ] = " AND `". $db->table_name ."`.`". $db->table_fields[ 'table_name' ] ."` = '". $stb ."' AND `". $db->table_name ."`.`". $db->table_fields[ 'table_classification' ] ."` = 'plugin_table' ";
							$dr = $db->_get_records();

							// print_r( $stb );exit;
							if( isset( $dr[0][ 'table_package' ] ) && $dr[0][ 'table_package' ] ){
								$plugin = 'nwp_' . $dr[0][ 'table_package' ];
								$ptb[ $sptb ] = $sptb;
							}
						}
					}

					if( $plugin && ! empty( $ptb ) ){
						$pcl = 'c'.$plugin;

						if( class_exists( $pcl ) ){
							$this->class_settings[ 'plugin' ] = $plugin;
							$pcl = new $pcl;
							$pcl->load_class( array( 'class' => $ptb ) );
						}
					}

					switch( $ddx[ 'reference_table' ] ){
					case "comments":
						// print_r( $e );exit;
						$ddx[ 'title' ] = ( isset( $e[ 'title' ] ) ? $e[ 'title' ] . ' - ' : '' ).ucwords( $e[ 'reference_table' ] ) . ' ['. ( isset( $e[ 'serial_num' ] ) ? '#' . $e[ 'serial_num' ] : '' ) .']';
					break;
					default:
						$tb1 = strtolower( trim( $ddx[ 'reference_table' ] ) );
						switch( $tb1 ){
						case 'customers':
							$ddx[ 'title' ] = get_name_of_referenced_record( array( 'id' => $ddx[ 'reference' ], 'table' => 'customers' ) );
						break;
						default:
							$tb = 'c' . ucwords( $tb1 );

							if( class_exists( $tb ) ){
								$cl = new $tb();
								if( $plugin )$cl->plugin = $plugin;
								$cl->class_settings = $this->class_settings;
								$cl->class_settings[ 'current_record_id' ] = $ref;
								$cl->class_settings[ 'action_to_perform' ] = "get_record";
								$r = $cl->$tb1();

								$ddx[ 'title' ] = $cl->label . ( isset( $r[ 'serial_num' ] ) ? '[#' . $r[ 'serial_num' ] . ']' : '' );
							}
						break;
						}
					break;
					}
				}

				$ddx[ 'date' ] =  date("Y-m-j\TH:i");
				if( ! ( isset( $ddx[ 'type' ] ) && $ddx[ 'type' ] ) ){
					$ddx[ 'type' ] = 'parent';
				}

				if( ! ( isset( $ddx[ 'status' ] ) && $ddx[ 'status' ] ) ){
					$ddx[ 'status' ] = 'unresolved';
				}
				if( isset( $ddx[ 'ctitle' ] ) && $ddx[ 'ctitle' ] ){
					$ddx[ 'title' ] = $ddx[ 'ctitle' ];
				}
				$ddx[ 'supporting_document' ] =  isset( $ddx[ 'supporting_document_json' ] ) ? $ddx[ 'supporting_document_json' ] : '';

				$this->class_settings[ 'update_fields' ] = $ddx;

				$save_app = 3;
			break;
			case "save_multiple_new_comments":
				$save_app = 4;
				if( isset( $o["line_items"] ) && ! empty( $o["line_items"] ) && is_array( $o["line_items"] ) ){
					$ddx = array();
					foreach( $o["line_items"] as $lv ){
						if( isset( $lv["type"] ) ){
							switch( $lv["type"] ){
							case "query":
								$lv["date"] = date("U");
								$lv["status"] = 'unread';
								$lv["status2"] = 'pending';
							break;
							}
						}
						$ddx[] = $lv;
					}
					$this->class_settings[ 'line_items' ] = $ddx;
				}else{
					$error = '<h4>Invalid ' . $this->label . ' Data</h4>';
				}
			break;
			}
			
			if( $error ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error ) );
			}
			
					// print_r( $this->class_settings[ 'update_fields' ] );exit;
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

			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = defined( 'DISPLAY_MAIN_AREA_CONTAINER' ) ? DISPLAY_MAIN_AREA_CONTAINER : "dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				switch( $action_to_perform ){
				case 'save_child_comment_form':
					if( isset( $return[ 'record' ][0][ 'id' ] ) && $return[ 'record' ][0][ 'id' ] )$return[ 'record' ] = $return[ 'record' ][0];

					$id = $return["record"]["reference"];
					$handle = 'new-replyx-' . $id;

					$sa = array();
					$sa["where"] = " `id` = '". $id ."' ";
					$sa["database_table"] = $this->table_name;
					$sa["field_and_values"][ 'modification_date' ]['value'] = date("U");
					$ur = $this->_update_records( $sa );

					if( ! $ur ){
						$audit_params = array();
						$audit_params["elevel"] = 'fatal';
						$audit_params["status"] = $action_to_perform;
						$audit_params["comment"] = 'Unable to Update Parent Reference Modification Date';
						auditor( "", "console", $this->table_name , $audit_params );
					}

					$this->class_settings[ 'data' ]["items"][] = $return["record"];
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/append-reply.php' );
					$returning_html_data = $this->_get_html_view();
					
					//@mike
					
					if( isset( $e["reference"] ) && $e["reference"] && isset( $e["reference_table"] ) && $e["reference_table"] ){
						$ae = array(
							"plugin" => isset( $e["plugin"] )?$e["plugin"]:'',
							"reference" => $e["reference"],
							"reference_table" => $e["reference_table"],
						);
					}else{
						$ae = array(
							"parent_id" => $id,
							"creator_of_parent" => 1,
						);
					}

					$ae[ 'record' ] = $return[ 'record' ];
					$this->_notify_participants( $ae );
					
					$return = array();
					$return["status"] = "new-status";
					$return["html_replacement_selector"] = "#" . $handle;
					$return["html_replacement"] = $returning_html_data;
					$return["id"] = $id;
					
					$return["html_replacement_selector_one"] = "#reply-comment-" . $id;
					$return["html_replacement_one"] = '&nbsp;';
					
					$return["javascript_functions"] = array( "set_function_click_event", "commentView.appendReply" );
					
					
					return $return;
					/*
					$this->class_settings["html_replacement_selector"] = "modal-replacement-handle";
					$this->class_settings["current_record_id"] = $return["record"]["reference"];
					$p = $this->_get_record();
					
					
					$_POST["id"] = $p["reference"];
					$_GET["table"] = $p["reference_table"];
					$_GET["modal"] = 1;
					$this->class_settings["action_to_perform"] = 'view_details_by_reference';
					return $this->_view_details2();
					*/
				break;
				case 'save_new_comment_form':
					if( ( isset( $ddx[ 'supporting_document_json' ] ) && $ddx[ 'supporting_document_json' ] ) ){
						$fs = new cFiles();
						$fs->class_settings = $this->class_settings;
						$fs->class_settings[ 'action_to_perform' ] = 'save_files_from_other_class';
						$fs->class_settings[ 'files_data' ][ 'supporting_document_json' ] = $ddx[ 'supporting_document_json' ];
						$fs->class_settings[ 'files_data' ][ 'share_type' ] = 'attachment';
						$fs->class_settings[ 'files_data' ][ 'reference' ] = $return[ 'saved_record_id' ];
						$fs->class_settings[ 'files_data' ][ 'reference_table' ] = $this->table_name;

						// print_r( $files );exit;
						$x = $fs->files();

						if( ! $x ){
							return $this->_display_notification( array( "type" => "error", "message" => "<h4>Unable to save documents</h4>Please review and try again" ) );
						}
					}

					if( isset( $ddx[ 'assigned_to' ] ) && $ddx[ 'assigned_to' ] ){
						$ids = explode( ',', $ddx[ 'assigned_to' ] );
						$data = array();

						$sh = new cShare();
						$sh->class_settings = $this->class_settings;

						$data[ 'users' ] = $ids;
						$data[ 'reference' ] = $return[ 'saved_record_id' ];
						$data[ 'reference_table' ] = $this->table_name;
						$data[ 'skip_notification' ] = 1;

						$sh->class_settings[ 'action_to_perform' ] = 'save_share';
						$sh->class_settings[ 'share_data' ] = $data;
						$x = $sh->share();

						if( ! $x ){
							return $this->_display_notification( array( "type" => "error", "message" => "<h4>Unable to process assigned users</h4>Please contact your administrator and try again" ) );
						}
					
						$ae = array(
							"assigned_to" => $ddx[ 'assigned_to' ],
							"creator_of_parent" => $return[ 'record' ][ 'created_by' ],
						);
						$ae[ 'record' ] = $return[ 'record' ];
						$this->_notify_participants( $ae );
					}else{
						$ae = array(
							"reference" => $return[ 'record' ][ 'reference' ],
							"reference_table" => $return[ 'record' ][ 'reference_table' ],
						);
						$ae[ 'record' ] = $return[ 'record' ];
						$this->_notify_participants( $ae );
					}
					
					if( $uhsel ){
						$_GET[ 'preview_replacement_selector' ] = $uhsel;
					}
					
					$_POST["id"] = $return[ 'record' ]["reference"];
					$_POST["id"] = $return[ 'record' ]["id"];
					// $_GET["table"] = $return[ 'record' ]["reference_table"];
					// $_GET["modal"] = 1;
					$this->class_settings["action_to_perform"] = 'view_details2';
					return $this->_view_details2();
					// return $this->_display_notification( array( "type" => "success", "message" => "<h4>Comments Saved Successfully</h4>", "do_not_display" => 1, "html_replacement_selector" => '#' . $handle ) );
				break;
				case 'mark_read':
					if( isset( $_GET['callback'] ) && $_GET['callback'] ){
						$return = array(
							'status' => 'new-status',
							'method_executed' => $action_to_perform,
							'javascript_functions' => array( $_GET['callback'] )
						);
					}
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
		
		protected function _notify_participants( $e = array() ){
			$notify = 0;
			$users = array();
			$text = '';
			$content = '';
			$error_msg = '';

			$da =  array( 'action' => $this->table_name, 'todo' => 'view_details', 'title' => '' );
			
			if( isset( $e["creator_of_parent"] ) && $e["creator_of_parent"] ){
				if( ( isset( $e["assigned_to"] ) && $e["assigned_to"] ) ){
					$notify = 1;
					foreach( explode( ',', $e["assigned_to"] ) as $u ){
						if( $this->class_settings[ 'user_id' ] == $u )continue;
						$users[][ 'created_by' ] = $u;
					}
					$text = "New Message From " . get_name_of_referenced_record( array( "id" => $e["creator_of_parent"], "table" => "users" ) );
					if( isset( $e[ 'record' ][ 'id' ] ) && $e[ 'record' ][ 'id' ] ){
						$da["reference"] = $e[ 'record' ][ 'id' ];
						$da["reference_table"] = $this->table_name;
					}
				}
			}
			
			if( ( isset( $e["parent_id"] ) && $e["parent_id"] ) ){
				$da["id"] = $e["parent_id"];
				$da["reference"] = $e["parent_id"];
				$da["reference_table"] = $this->table_name;
				
				if( isset( $e["creator_of_parent"] ) && $e["creator_of_parent"] ){
					$notify = 1;
				}
				
				if( isset( $e["creator_of_children"] ) && $e["creator_of_children"] ){
					$notify = 1;
				}

				$this->class_settings[ 'current_record_id' ] = $e[ 'parent_id' ];
				$a = $this->_get_record();
				// print_r( $a );exit;

				$this->class_settings[ 'overide_select' ] = " DISTINCT `created_by` ";
				$this->class_settings[ 'where' ] = " AND `created_by` <> '". $this->class_settings[ "user_id" ] ."' AND `id` = '". $e[ "parent_id" ] ."' OR ( `". $this->table_fields[ 'reference' ] ."` = '". $e["parent_id"] ."' AND `". $this->table_fields[ 'reference_table' ] ."` = '". $this->table_name ."' ) ";

				$users = $this->_get_records();
				$text = "Reply to Comment on " . get_name_of_referenced_record( array( "id" => $e["parent_id"], "table" => $this->table_name ) );
			}
			
			//print_r($e); exit;
			if( ( isset( $e["reference"] ) && $e["reference"] && isset( $e["reference_table"] ) && $e["reference_table"] ) ){
				
				$da["reference_plugin"] = isset( $e[ 'plugin' ] )?$e[ 'plugin' ]:( isset( $e['record'][ 'plugin' ] )?$e['record'][ 'plugin' ]:'' );
				$da["reference"] = $e[ 'record' ]["id"];
				$da["reference_table"] = $this->table_name;
				
				$tb = $e["reference_table"];
				if( $da["reference_plugin"] ){
					$clp = 'c' . ucwords( strtolower( $da["reference_plugin"] ) );
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
					$tb = 'c' . ucwords( $tb );
					$cl = new $tb();
				}

				if( ! $error_msg ){
					$f = $cl->table_fields;

					$notify = 1;
					$this->class_settings[ 'overide_select' ] = " `id` ";
					$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'reference' ] ."` = '". $e["reference"] ."' AND `". $this->table_fields[ 'reference_table' ] ."` = '". $e["reference_table"] ."' ";
					$r = $this->_get_records();

					if( is_array( $r ) && ! empty( $r ) && isset( $r[0] ) ){
						$this->class_settings[ 'overide_select' ] = " DISTINCT `created_by` ";
						$this->class_settings[ 'where' ] = " AND `id` IN ( '". implode( "','", array_column( $r, 'id' ) ) ."' ) OR ( `". $this->table_fields[ 'reference' ] ."` IN ( '". implode( "','", array_column( $r, 'id' ) ) ."' ) AND `". $this->table_fields[ 'reference_table' ] ."` = '". $this->table_name ."' ) ";

						$users = $this->_get_records();
					}

					// If table isn't comment, get user id of the creator
					if( $e[ 'reference_table' ] !== $this->table_name ){
						$r = [];
						$tb = 'c' . ucwords( $e[ 'reference_table' ] );
						if( class_exists($tb) ){
							$cl = new $tb();
							$cl->class_settings = $this->class_settings;
							$cl->class_settings[ 'current_record_id' ] = $e[ 'reference' ];
							$r = $cl->_get_record();
						}
						if( ! $r ){
							$r = get_record_details( array( 'id' => $e['reference'], 'table' => $e['reference_table'] ) );
						}
						if( isset( $r[ 'created_by' ] ) && $r[ 'created_by' ] ){
							$users[][ 'created_by' ] = $r[ 'created_by' ];
						}
					}
					// print_r( $users );exit;

					$text = "New Comment on " . $e[ 'reference_table' ] . ' (' . get_name_of_referenced_record( array( "id" => $e["reference"], "table" => $e["reference_table"] ) ) . ') ';
					
					switch( $e["reference_table"] ){
					case "workflow":
						$da =  array( 'reference_table' => $e["reference_table"], 'todo' => 'open_workflow', 'title' => '', 'reference' => $e["reference"], 'reference_plugin' => $da["reference_plugin"] );
					break;
					}
				}
			}
			
			if( isset( $e["record"] ) && is_array( $e["record"] ) && isset( $e["record"][ 'message' ] ) ){
				$content = $e["record"][ 'message' ];
			}
			
			if( $notify && ! empty( $users ) ){
				$us = array();
				if( ! ( isset( $e["include_current_user"] ) && $e["include_current_user"] ) ){
					foreach( $users as $c => $u ){
						if( $u[ 'created_by' ] == $this->class_settings[ 'user_id' ] )continue;
						$us[ $u[ 'created_by' ] ] = $u[ 'created_by' ];
					}
				}
				// print_r( $us );exit;
				
				$un = $us;
				foreach( $un as & $unv ){
					$unv = "'". $unv ."'";
				}
				
				if( ! empty( $un ) ){
					$not = array(
						"data" => $da,
						"content" => $content,
						"subject" => $text,
						"specific_users" => $un,
						"sending_option" => "bcc",
					);
					$this->_notify_people( $not );
				}
			}
		}
		
		protected function _new_popup_form2(){
			$this->class_settings["skip_link"] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["amount_paid"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["payment_method"] ] = 1;
				
				foreach( $this->table_fields as $k => $v ){
					switch( $k ){
					case "date":
						$this->class_settings[ "form_values_important" ][ $v ] = date("U");
						$this->class_settings[ "hidden_records_css" ][ $v ] = 1;
					break;
					case "title":
					case "message":
					break;
					default:
						$this->class_settings[ "hidden_records" ][ $v ] = 1;
					break;
					}
				}
			break;
			case 'edit_label_from_comment':

				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
					$this->class_settings[ 'current_record_id' ] = $_POST[ 'id' ];
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					// $this->class_settings[ 'overide_select' ] = ' `id`, `'. $this->table_fields[ 'label' ] .'` as "labels" ';
					$d = $this->_get_customer_call_log();
					$labels = array();
					if( strpos($d[ 'labels' ], ',') ){
						foreach( explode(',', $d[ 'labels' ]) as $label ){
							$a = get_record_details( array( "id" => $label, "table" => "labels" ) );

							if( $a && ! empty( $a ) )
								$labels[] = $a;
						}
					}else{
						$a = get_record_details( array( "id" => $d[ 'labels' ], "table" => "labels" ) );

						if( $a && ! empty( $a ) )
							$labels[] = $a;
					}
					// print_r( $labels );exit;
					
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/edit-label/' );
					$this->class_settings[ 'data' ][ 'labels' ] = $labels;
					$this->class_settings[ 'data' ][ 'comment_id' ] = $_POST[ 'id' ];

					$this->class_settings[ 'use_html' ] = $this->_get_html_view();
				}
				
			break;
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			// $this->class_settings['action_to_perform'] = 'view_details';
			return $this->_new_popup_form();
		}
		
	}
	
	function comments( $tf = '' ){
		if( file_exists( dirname( __FILE__ ).'/dependencies/comments.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/comments.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				switch( $tf ){
				case "query":
					$key = $return[ "fields" ][ "message" ];
					$return[ "labels" ][ $key ][ 'default_appearance_in_table_fields' ] = 'hide';
					/* 
					$key = $return[ "fields" ][ "reference_table" ];
					$return[ "labels" ][ $key ][ 'default_appearance_in_table_fields' ] = 'show';
					$return[ "labels" ][ $key ][ 'display_position' ] = 'display-in-table-row';
					
					$return[ "labels" ][ $key ][ 'field_label' ] = 'Source';
					$return[ "labels" ][ $key ][ 'display_field_label' ] = $return[ "labels" ][ $key ][ 'field_label' ];
					$return[ "labels" ][ $key ][ 'abbreviation' ] = $return[ "labels" ][ $key ][ 'field_label' ];
					$return[ "labels" ][ $key ][ 'text' ] = $return[ "labels" ][ $key ][ 'field_label' ];
					 */
				break;
				}
				return $return[ "labels" ];
			}
		}
	}
	
	
	function get_comment_type(){
		return array(
			"parent" => "Parent",
			"child" => "Child",
			"query" => "Query",
			"response" => "Response",
		);
	}
	
	function get_comment_action(){
		return array(
			"approve" => "Approve",
			"reject" => "Reject",
		);
	}
	
	function get_comment_status(){
		return array(
			"read" => "Read",
			"unread" => "Unread",
			"recalled" => "Recalled",
			"returned" => "Returned",
		);
	}
	
	function get_comment_status2(){
		return array(
			"internal" => "Internal Comment",
			"assigned" => "Assignment",
			"pending" => "Pending",
			"resolved" => "Resolved",
		);
	}
	
?>
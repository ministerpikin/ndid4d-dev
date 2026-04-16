<?php
	/**
	 * notifications Class
	 *
	 * @used in  				Notifications Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	notifications
	 */

	/*
	|--------------------------------------------------------------------------
	| Notifications Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cNotifications extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $label = 'Notifications';
		public $table_name = 'notifications';
		
		public $table_fields = array(
			'recipients' => 'notifications007',
			'send email' => 'notifications006',
			'detailed message' => 'notifications005',
			
			'type' => 'notifications004',
			'target user' => 'notifications003',
			'trigger function' => 'notifications002',
			
			'generating class' => 'notifications008',
			'generating method' => 'notifications009',
			
			'title' => 'notifications001',
			
			'status' => 'notifications010',
			'data' => 'notifications011',
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
        
		function notifications(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
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
			case 'add_notification':
				$returned_value = $this->_add_notification();
			break;
			case 'get_unread_notification':
				$returned_value = $this->_get_unread_notification();
			break;
			case 'get_unread_notification_count':
				$returned_value = $this->_get_unread_notification_count();
			break;
			case 'get_read_notification':
				$returned_value = $this->_get_read_notification();
			break;
			case 'get_all_notification':
				$returned_value = $this->_get_all_notification();
			break;
			case 'mark_as_read':
				$returned_value = $this->_mark_as_read();
			break;
			case 'delete_notification':
				$returned_value = $this->_delete_notification();
			break;
			case 'site_notifications_manager':
				$returned_value = $this->_site_notifications_manager();
			break;
			case 'display_my_notifications':
			case 'display_all_records_full_view_and_compose':
			case 'display_all_records_full_view_filter':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'create_notification':
				$returned_value = $this->_create_notification();
			break;
			case 'send_user_generated_notification':
				$returned_value = $this->_send_user_generated_notification();
			break;
			case 'display_notification_details':
			case 'get_view_details':
				$returned_value = $this->_display_notification_details();
			break;
			case 'delete_all_notifications':
				$returned_value = $this->_delete_all_notifications();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			}
			
			return $returned_value;
		}
		
		private function _delete_all_notifications(){
			
			$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DELETE',
				'set_memcache' => 0,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			$err = new cError('010011');
			$err->action_to_perform = 'notify';
			$err->html_format = 2;
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = '<h4>Notifications successfully cleared</h4>';
			$return = $err->error();
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			$return["javascript_functions"] = array( '$nwProcessor.reload_datatable' );
			
			return $return;
		}
		
		private function _display_notification_details(){
			$return = array();
			$id = "";
			$return['do_not_reload_table'] = 1;
			
			if( isset( $_GET["month"] ) && $_GET["month"] ){
				$id = $_GET["month"];
				$this->class_settings['where'] = " WHERE `record_status`='1' AND `id` = '".$id."' ";
				$n = $this->_get_notification();
				// print_r( $n );exit();
				
				if( isset( $n[$id]["title"] ) ){
					switch( $this->class_settings[ 'action_to_perform' ] ){
					case 'get_view_details':
						$this->class_settings[ 'data' ] = $n[$id];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/view-details.php' );
				
						return array(
							"status" => "new-status",
							'html_replacement' => $this->_get_html_view(),
							'html_replacement_selector' => '#view-details',
							'javascript_functions' => array( 'set_function_click_event' ),
						);
					break;
					default:
						$_GET[ 'record_id' ] = $id;
						$this->_mark_as_read();
						
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/pop-up' );
						$this->class_settings[ 'data' ][ "html_title" ] = $n[$id]["title"];
						
						$this->class_settings[ 'data' ]['html'] = $n[$id]["detailed message"];
						$returning_html_data = $this->_get_html_view();
						
						return array(
							'do_not_reload_table' => 1,
							'html_prepend' => $returning_html_data,
							'html_prepend_selector' => "#notification-container",
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							//'javascript_functions' => array( 'prepare_new_record_form_new' ),
						);
					break;
					}
				}
			}
		}
		
		private function _send_user_generated_notification(){
			$return = array();
			$this->class_settings['return_form_data_only'] = 1;
			$dreturn = $this->_save_changes();
			
			if( isset( $dreturn[ "form_data" ][ $this->table_fields["title"] ]["value"] ) && $dreturn[ "form_data" ][ $this->table_fields["title"] ]["value"]  && isset( $dreturn[ "form_data" ][ $this->table_fields["recipients"] ]["value"] ) && $dreturn[ "form_data" ][ $this->table_fields["recipients"] ]["value"] ){
				
				$msg = $dreturn[ "form_data" ][ $this->table_fields["detailed message"] ]["value"];
				
				$recipients_names = array();
				$sharer = $this->class_settings["user_id"];
				
				$title = $dreturn[ "form_data" ][ $this->table_fields["title"] ]["value"];
				$recipients = explode( ":::", $dreturn[ "form_data" ][ $this->table_fields["recipients"] ]["value"] );
				
				//GET SHARED FILES
				$recipient_ids = array();
				$recipients_emails = array();
				$recipient_query = "";
				foreach( $recipients as $recipient ){
					$recipient_ids[] = "'".$recipient."'";
					
					$f = get_record_details( array( "id" => $recipient, "table" => "users" ) );
					if( isset( $f[ "firstname" ] ) ){
						$recipients_names[ $recipient ] = $f[ "firstname" ] . " " . $f[ "lastname" ];
						
						$emailx = trim( $f["email"] );
						if( $emailx && filter_var( $emailx, FILTER_VALIDATE_EMAIL ) ){
							$recipients_emails[] = $emailx;
						}
					}
				}
				
				if( ! empty( $recipients_emails ) ){
					$opt = array(
						"content" => $msg,
						"subject" => $title,
						"multiple_emails" => implode(",", $recipients_emails ),
						"sending_option" => 'bcc',
						"system_notification" => $recipients,
					);
					$r = $this->_send_email_notification( $opt, array() );
					
				}
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Notification have been successfully sent</h4>';
				$return = $err->error();
				
				$return['do_not_reload_table'] = 1;
				
				$return['saved_record_id'] = 0;
				$this->class_settings[ 'data' ] = array( 
					'sharer' => $this->class_settings["user_full_name"],
					'title' => $title,
					'recipient' => $recipients_names,
					'message' => $msg,
				);
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sent-notification.php' );
				$returning_html_data = $this->_get_html_view();
				
				unset( $return['html'] );
				$return['html_replacement'] = $returning_html_data;
				$return['html_replacement_selector'] = "#message-section";
				$return['status'] = 'new-status';
				
				//$return['javascript_functions'] = array( 'refresh_tree_view' );
			}else{
				$return = $dreturn;
				/*
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Department to Move Line Items to, Please Select a valid department and try again';
				$return = $err->error();
				*/
			}
				
			
			return $return;
		}
		
		private function _create_notification(){
			$this->class_settings['hidden_records'] = array(
				//$this->table_fields['recipients'] => 1,
				$this->table_fields['send email'] => 1,
				$this->table_fields['type'] => 1,
				$this->table_fields['target user'] => 1,
				$this->table_fields['trigger function'] => 1,
				$this->table_fields['generating class'] => 1,
				$this->table_fields['generating method'] => 1,
				$this->table_fields['status'] => 1,
				$this->table_fields['data'] => 1,
			);
			$this->class_settings["do_not_show_headings"] = 1;
			$this->class_settings['form_submit_button'] = 'Send Notification &rarr;';
			$this->class_settings['form_action_todo'] = 'send_user_generated_notification';
			
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/activate-select-2.php' );
			$script = $this->_get_html_view();
			
			return array(
				'html_replacement' => $form['html'] . $script,
				'html_replacement_selector' => "#message-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _display_all_records_full_view2(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$params = isset( $this->class_settings["params"] )?$this->class_settings["params"]:array();
			$where = " AND `". $this->table_fields["target user"] ."` = '". $this->class_settings["user_id"] ."' ";
			
			$status = '';
			$status2 = get_notification_states();
			
			switch( $action_to_perform ){
			case 'display_my_notifications':
				$status = isset( $_GET["status"] )?$_GET["status"]:'';
				
			break;
			}
			
			$open_tab = '';
			if( isset( $_GET[ 'open_tab' ] ) && $_GET[ 'open_tab' ] ){
				$open_tab = isset( $_GET[ 'title' ] )?rawurldecode( $_GET[ 'title' ] ):'';
				if( $open_tab ){
					$this->class_settings["hide_title"] = 1;
				}
			}
			
			$status = isset( $params["status"] )?$params["status"]:$status;
			$get_children = isset( $params["get_children"] )?$params["get_children"]:'';
			
			$handle2 = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$handle = "#" . $handle2;
			
			if( $status ){
				$where .= " AND `". $this->table_fields["status"] ."` = '". $status ."' ";
			}
			
			if( $get_children ){
				$data = array();
				
				if( $status ){
					
				}else{
					$this->class_settings["overide_select"] = " COUNT(*) as 'count', `". $this->table_fields["status"] ."` as 'status' ";
					$this->class_settings["where"] = $where;
					$this->class_settings["group"] = " GROUP BY `". $this->table_fields["status"] ."` ";
					$r = $this->_get_records();
					
					if( isset( $r[0]["status"] ) ){
						foreach( $r as $rv ){
							$text = isset( $status2[ $rv["status"] ] )?$status2[ $rv["status"] ]:$rv["status"];
							$num = nw_pretty_number( $rv["count"] );
							
							$data[] = array(
								'id' => 'action='.$this->table_name.':::todo=display_my_notifications:::get_children=1:::status='. $rv["status"] .':::html_replacement_selector=' . $handle2,
								'text' =>  $text . ' ('. $num .')',
								//'children' => true,
							);
							
						}
					}
				}
				
				
				return $data;
			}
			
			$this->datatable_settings[ 'show_add_new' ] = 0;
            $this->datatable_settings[ 'show_edit_button' ] = 0;
            
			$this->datatable_settings[ 'show_delete_button' ] = 0;
			$this->datatable_settings[ 'show_advance_search' ] = 0;
			$this->datatable_settings[ 'custom_view_button' ] = '';
			$this->datatable_settings[ 'show_selection' ]["button_title"] = 'notice';
			$this->datatable_settings[ 'show_selection' ]["button_text"] = '<i style="font-size:14px;" class="fa fa-2x icon-bell"></i>';
			$this->datatable_settings[ 'show_selection' ]["show_details"] = 1;
			
			$compose = 0;
			$super = 0;
			$access = get_accessed_functions();
			if( ! is_array( $access ) && $access == 1 ){
				$super = 1;
			}
			
			if( $super ){
				$this->datatable_settings[ 'show_delete_button' ] = 1;
			}
			
			$ack = '1a00471ef7da641d0e1f4f75a9e063b1';
			if( isset( $access[ $ack ] ) || $super ){
				$compose = 1;
			}
			
			$this->class_settings[ 'data' ][ 'compose' ] = $compose;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->datatable_settings[ "custom_edit_button" ] = $this->_get_html_view();
				
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-all-records-full-view' );
			
			$this->class_settings["filter"]["where"] = $where;
			
			if( $status ){
				$this->class_settings["filter"]["where"] .= " AND `". $this->table_fields["status"] ."` = '". $status ."' ";
			}
			
			switch( $action_to_perform ){
			case "display_all_records_full_view_filter":
				if( isset( $_GET["filter"] ) && $_GET["filter"] ){
					$this->class_settings["filter"]["where"] .= " AND `". $this->table_fields["trigger function"] ."` = '". $_GET["filter"] ."' ";
					/*
					$pc = explode( ".", $_GET["filter"] );
					if( isset( $pc[0] ) && $pc[0] ){
						$this->class_settings["filter"]["where"] .= " AND `". $this->table_fields["generating class"] ."` = '". $pc[0] ."' ";
					}
					
					if( isset( $pc[1] ) && $pc[1] ){
						$this->class_settings["filter"]["where"] .= " AND `". $this->table_fields["generating method"] ."` = '". $pc[1] ."' ";
					}
					*/
				}
			break;
			case 'display_my_notifications':
				if( ! get_hyella_development_mode() ){
					unset( $this->datatable_settings[ "custom_edit_button" ] );
				}
			break;
			}
			
			$datatable = $this->_display_data_table();
			//$form = $this->_generate_new_data_capture_form();
			
			//$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['title'] = "My Notifications";
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 6;
			$this->class_settings[ 'data' ]['col_2'] = 6;
			
			if( isset( $this->class_settings['hide_title'] ) ){
				$this->class_settings[ 'data' ]['hide_title'] = $this->class_settings['hide_title'];
			}
			
			$returning_html_data = $this->_get_html_view();
			
			$return = array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'nwResizeWindow.resizeWindow', '$nwProcessor.recreateDataTables', '$nwProcessor.set_function_click_event', '$nwProcessor.update_column_view_state', 'nwNotifications.init' ) 
			);
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_all_records_full_view_and_compose':
				$return['re_process'] = 1;
				$return['re_process_code'] = 1;
				$return['mod'] = 'import-'.md5( $this->table_name );
				$return['id'] = 1;
				$return['action'] = '?action='.$this->table_name.'&todo=create_notification';
			break;
			case 'display_my_notifications':
				
				$return["status"] = 'new-status';
				$return["html_replacement"] = $return["html"];
				$return["html_replacement_selector"] = $handle;
				unset( $return["html"] );
				
				if( $open_tab ){
					if( ! isset( $return["javascript_functions"] ) )$return["javascript_functions"] = array();
					array_unshift( $return["javascript_functions"], 'nwMisDashboard.switch_tab' );
					
					$return["data"]['title'] = $open_tab;
					$return["data"]['get_params'] = http_build_query( $_GET );
				}
			break;
			}
			
			return $return;
		}
		
		//BASED METHODS
		private function _delete_notification(){
			
			$this->class_settings['record_id'] = 0;
			
			if( isset( $_GET[ 'record_id' ] ) ){
				$this->class_settings['record_id'] = doubleval( $_GET['record_id'] );
			}
			
			if( $this->class_settings['record_id'] ){
				
				$_POST['mod'] = 'delete-'.md5( $this->table_name );
				$_POST['id'] = $this->class_settings['record_id'];
				
				$returning_html_data = $this->_delete_records();
				
				$returning_html_data['deleted_record_id'] = $this->class_settings['record_id'];
				
				return $returning_html_data;
			}else{
				//INVALID RECORD ID
				$err = new cError('000101');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid input data';
				
				return $err->error();
			}
			
		}
		
		private function _get_unread_notification(){
			
			$this->class_settings['where'] = " WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields['status']."`='unread' ORDER BY `creation_date` DESC LIMIT 0, 10 ";
			
			return $this->_get_notification();
		}
        
		private function _get_unread_notification_count(){
			
            $query = "SELECT COUNT(`id`) as 'count' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields['status']."`='unread' ";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if( isset($sql_result[0]['count']) && $sql_result[0]['count'] ){
				return doubleval($sql_result[0]['count']);
			}
			
			return 0;
            
		}
		
		private function _get_read_notification(){
			
			$this->class_settings['where'] = " WHERE `record_status`='1' AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields['status']."`='read' ORDER BY `creation_date` desc LIMIT 0, 10 ";
			
			return $this->_get_notification();
		}
		
		private function _get_all_notification(){
			
			$this->class_settings['overide_select'] = " COUNT(*) as 'count', MIN(`creation_date`) as 'date', `".$this->table_fields["generating class"]."` as 'action', `".$this->table_fields["generating method"]."` as 'todo', `".$this->table_fields["trigger function"]."` as 'trigger', `".$this->table_fields["title"]."` as 'title' ";
			
			$this->class_settings['where'] = " AND `".$this->table_fields['target user']."` = '".$this->class_settings['user_id']."' AND `".$this->table_fields["trigger function"]."` != '' ";
			
			$this->class_settings['where'] .= " AND `".$this->table_fields['status']."` = 'unread' ";
			
			$this->class_settings['group'] = " GROUP BY `".$this->table_fields["trigger function"]."` ";
			
			//$this->class_settings['group'] = " GROUP BY `".$this->table_fields["generating class"]."`, `".$this->table_fields["generating method"]."` ";
			
			$this->class_settings['order_by'] = " ORDER BY `creation_date` asc ";
			$this->class_settings['limit'] = " LIMIT 0, 10 ";
			
			return $this->_get_all_customer_call_log();
		}
		
		private function _get_notification(){
			$returned_data = array();
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "title":
				case "data":
				case "trigger function":
				case "detailed message":
				case "generating class":
				case "generating method":
				case "status":
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `serial_num`, `creation_date`, `created_by`, `".$val."` as '".$key."'";
				break;
				}
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ $s_val['id'] ] = $s_val;
				}
			}
			
			return $returned_data;
		}
		
		private function _mark_as_read(){
			
			$this->class_settings['record_id'] = 0;
			
			if( isset( $_POST[ 'id' ] ) ){
				$_GET[ 'record_id' ] = $_POST[ 'id' ];
			}
			if( isset( $_GET[ 'record_id' ] ) ){
				$this->class_settings['record_id'] = doubleval( $_GET['record_id'] );
			}
			
			if( $this->class_settings['record_id'] ){
				//ADD NOTIFICATION
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->table_name ,
					'field_and_values' => array(
						
						$this->table_fields['status'] => array( 'value' => 'read' ),
						
						'modified_by' => array( 'value' => $this->class_settings['user_id'] ),
						'modification_date' => array( 'value' => date("U") ),
						'ip_address' => array( 'value' => get_ip_address() ),
					) ,
					'where_fields' => 'id',
					'where_values' => $this->class_settings['record_id'],
				);
				
				$save = update( $settings_array );
			
				if( $save ){
					//RETURN SUCCESS IN UPDATE PROCESS
					$err = new cError('010002');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = 'could not update record on line 168';
					
					$returning_html_data = $err->error();
					
					$returning_html_data['saved_record_id'] = $this->class_settings['record_id'];
					$returning_html_data['status'] = 'notification-marked-as-read';
				}else{
					//RETURN ERROR IN RECORD UPDATE PROCESS
					$err = new cError('000006');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = 'could not update record on line 168';
					
					$returning_html_data = $err->error();
				}
			}else{
				//INVALID RECORD ID
				$err = new cError('000101');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords($this->table_name).'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid input data';
				
				$returning_html_data = $err->error();
			}
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'nwNotifications.viewDetails', '$nwProcessor.reload_datatable' ),
			);
			
			return $returning_html_data;
		}
		
		private function _add_notification(){
			
			$save = 0;
			$recent_notifications = array();
			
			$fgroup = '';
			if( isset( $this->class_settings['notification_data'][ 'trigger' ] ) && $this->class_settings['notification_data'][ 'trigger' ] ){
				$fgroup = $this->class_settings['notification_data'][ 'trigger' ];
			}
			
			if( ! $fgroup ){
				if( isset( $this->class_settings['notification_data'][ 'class_name' ] ) && isset( $this->class_settings['notification_data'][ 'method_name' ] ) ){
					$fgroup = $this->class_settings['notification_data'][ 'class_name' ] . '.' . $this->class_settings['notification_data'][ 'method_name' ];
				}
			}
			
			
			//ADD NOTIFICATION
			if( isset( $this->class_settings['notification_data'][ 'recipients' ] ) && is_array( $this->class_settings['notification_data'][ 'recipients' ] ) && ! empty ( $this->class_settings['notification_data'][ 'recipients' ] ) ){
				$array_of_dataset = array();
				
				$new_record_id = get_new_id();
				$new_record_id_serial = 0;
				
				$ip_address = get_ip_address();
				$date = date("U");
				
				foreach( $this->class_settings['notification_data'][ 'recipients' ] as $user_id ){
					
					$recent_notifications[] = array( "title" => $this->class_settings['notification_data'][ 'title' ], "rec" => $user_id );
					
					$j = '';
					if( isset( $this->class_settings['notification_data'][ 'data' ] ) && is_array( $this->class_settings['notification_data'][ 'data' ] ) ){
						$j = json_encode( $this->class_settings['notification_data'][ 'data' ] );
					}
					
					$dataset_to_be_inserted = array(
						'id' => $new_record_id . ++$new_record_id_serial,
						'created_role' => $this->class_settings[ 'priv_id' ],
						'created_by' => $user_id,
						'creation_date' => $date,
						'modified_by' => $user_id,
						'modification_date' => $date,
						'ip_address' => $ip_address,
						'record_status' => 1,
						
						$this->table_fields['title'] => $this->class_settings['notification_data'][ 'title' ],
						$this->table_fields['detailed message'] => $this->class_settings['notification_data'][ 'detailed_message' ],
						$this->table_fields['send email'] => $this->class_settings['notification_data'][ 'send_email' ],
						$this->table_fields['type'] => $this->class_settings['notification_data'][ 'notification_type' ],
						$this->table_fields['target user'] => $user_id,
						
						$this->table_fields['trigger function'] => $fgroup,
						$this->table_fields['generating class'] => $this->class_settings['notification_data'][ 'class_name' ],
						$this->table_fields['generating method'] => $this->class_settings['notification_data'][ 'method_name' ],
						$this->table_fields['data'] => $j,
						
						$this->table_fields['status'] => 'unread',
					);
					
					$array_of_dataset[] = $dataset_to_be_inserted;
				}
				
				if( ! empty( $array_of_dataset ) ){
					
					$function_settings = array(
						'database' => $this->class_settings['database_name'],
						'connect' => $this->class_settings['database_connection'],
						'table' => $this->table_name,
						'dataset' => $array_of_dataset,
					);
					
					$save = insert_new_record_into_table( $function_settings );
				}
				
			}else{
				$this->class_settings['notification_data'][ 'id' ] = get_new_id();
				
				$recent_notifications[] = array( "title" => $this->class_settings['notification_data'][ 'title' ], "rec" => $this->class_settings['notification_data'][ 'target_user_id' ] );
				
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->table_name ,
					'field_and_values' => array(
						
						'id' => array( 'value' => $this->class_settings['notification_data'][ 'id' ] ),
						
						$this->table_fields['title'] => array( 'value' => $this->class_settings['notification_data'][ 'title' ] ),
						$this->table_fields['detailed message'] => array( 'value' => $this->class_settings['notification_data'][ 'detailed_message' ] ),
						$this->table_fields['send email'] => array( 'value' => $this->class_settings['notification_data'][ 'send_email' ] ),
						$this->table_fields['type'] => array( 'value' => $this->class_settings['notification_data'][ 'notification_type' ] ),
						$this->table_fields['target user'] => array( 'value' => $this->class_settings['notification_data'][ 'target_user_id' ] ),
						
						$this->table_fields['trigger function'] => array( 'value' => $fgroup ),
						$this->table_fields['generating class'] => array( 'value' => $this->class_settings['notification_data'][ 'class_name' ] ),
						$this->table_fields['generating method'] => array( 'value' => $this->class_settings['notification_data'][ 'method_name' ] ),
						
						$this->table_fields['status'] => array( 'value' => 'unread' ),
						//$this->table_fields['recipients'] => array( 'value' => ),
						
						'created_by' => array( 'value' => $this->class_settings['notification_data'][ 'target_user_id' ] ),
						'creation_date' => array( 'value' => date("U") ),
						'modified_by' => array( 'value' => $this->class_settings['notification_data'][ 'target_user_id' ] ),
						'modification_date' => array( 'value' => date("U") ),
						'ip_address' => array( 'value' => get_ip_address() ),
						'record_status' => array( 'value' => 1 ),
					) ,
				);
				$save = create( $settings_array );
			}
			
			$this->_set_recent_notifications( $recent_notifications );
			
			if( $save ){
				if( class_exists("cTasks") ){
					if( $this->class_settings['notification_data'][ 'notification_type' ] == 'pending_task' ){
						//CREATE PENDING TASK
						$tasks = new cTasks();
						$tasks->class_settings = $this->class_settings;
						$tasks->class_settings[ 'action_to_perform' ] = 'add_task';
						$tasks->tasks();
					}
					
					if( $this->class_settings['notification_data'][ 'notification_type' ] == 'completed_task' ){
						//UPDATE PENDING TASK STATUS IF ANY
						$tasks = new cTasks();
						$tasks->class_settings = $this->class_settings;
						$tasks->class_settings[ 'action_to_perform' ] = 'update_task_status';
						$tasks->tasks();
					}
				}
				
				if( $this->class_settings['notification_data'][ 'send_email' ] == 'yes' ){
                    //GET USER DETAILS
                    /*
                    $user_details = get_users_details( array( 'id' => $this->class_settings['notification_data'][ 'target_user_id' ] ) );
                    
                    if( isset( $user_details['email'] ) && $user_details['email'] ){
                        //SEND EMAIL MESSAGE
                        $email = new cEmails();
                        $email->class_settings = $this->class_settings;
                        
                        $email->class_settings[ 'action_to_perform' ] = 'send_mail';
                        
                        $email->class_settings[ 'destination' ]['email'][] = $user_details['email'];
                        $email->class_settings[ 'destination' ]['full_name'][] = $user_details['firstname']." ".$user_details['lastname'];
                        $email->class_settings[ 'destination' ]['id'][] = $this->class_settings['notification_data'][ 'target_user_id' ];
                        
                        $email->emails();
                    }else{
                        //LOG INTERNAL ERROR - FAILED TO RETRIEVE USER DETAILS
                    }
                    */
				}
			}
			
			return $save;
		}
		
		private function _set_recent_notifications( $not ){
			if( isset( $this->class_settings["user_id"] ) ){
				
				foreach( $not as $n ){
					
					$settings["cache_key"] = "my-notifications-" . $n["rec"];
					$g = get_cache_for_special_values( $settings );
					if( ! is_array( $g ) )$g = array();
					
					$g[] = $n["title"];
					
					$settings["cache_time"] = "mini-time";
					$settings["cache_values"] = $g;
					set_cache_for_special_values( $settings );
				
				}
				
			}
		}
		
        private function _site_notifications_manager(){
            $additional_html = '';
			
			$this->class_settings[ 'bundle-name' ] = $this->class_settings[ 'action_to_perform' ];
			
			$this->class_settings[ 'js_lib' ][] = 'js/jquery.dataTables.js';
			$this->class_settings[ 'js' ][] = 'my_js/activate-datatable.js';
			
			//$this->class_settings[ 'css' ][] = 'css/template-1/order_manager.css';
			
			$dashboard = new cDashboard();
			$dashboard->class_settings = $this->class_settings;
			$dashboard->class_settings[ 'action_to_perform' ] = 'setup_dashboard';
			$returning = $dashboard->dashboard();
			
			$this->class_settings = $dashboard->class_settings;
			
			$returning[ 'html' ] = '<div id="page-wrapper"><br />';
			
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			
            $this->datatable_settings[ 'show_add_new' ] = 0;
            $this->datatable_settings[ 'show_edit_button' ] = 0;
            
			$this->datatable_settings[ 'show_delete_button' ] = 1;
			$this->datatable_settings[ 'show_advance_search' ] = 1;
			$this->datatable_settings[ 'custom_view_button' ] = '';
			/*
			$script_compiler->class_settings[ 'data' ] = '';
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-edit-button.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$this->datatable_settings[ 'custom_edit_button' ] = $script_compiler->script_compiler();
			*/
			$script_compiler->class_settings[ 'data' ] = $this->_display_data_table();
			$script_compiler->class_settings[ 'data' ]['title'] = 'Notifications';
			
			$script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/notifications-manager.php' );
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$returning[ 'html' ] .= $script_compiler->script_compiler();
			
			$returning[ 'html' ] .= '</div>';
			
			$returning[ 'action_performed' ] = $this->class_settings[ 'action_to_perform' ];
			
			return $returning;
        }
	
    }
?>
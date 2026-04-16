<?php
/**
 * revision_history Class
 *
 * @used in  				revision_history Function
 * @created  				22:32 | 06-Oct-2019
 * @database table name   	revision_history
 */
	
	class cRevision_history extends cBase_class{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'revision_history';
		
		public $default_reference = '';
		
		public $label = 'Revision History';
		public $history_max_no = 3;
		
		private $associated_cache_keys = array(
			'revision_history',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			//'access_to_crud' => 1,	//all crud functions (add, eidt, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
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
		
		//13-mar-23
		public $old_values = array();
		
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/revision_history.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/revision_history.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function revision_history(){
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
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
			case 'display_all_record_filter':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list2_datatable':
			case 'search_list2_handsontable':
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log2();
			break;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case "set_revision_history":
				$returned_value = $this->_set_revision_history();
			break;
			case "check_revision_history":
			case "show_revision_history":
			case "show_revision_history2":
				$returned_value = $this->_check_revision_history();
			break;
			case "view_dashboard":
				$returned_value = $this->_view_dashboard();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _view_dashboard(){
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			$action_to_perform = $this->class_settings["action_to_perform"];
			$get_children = 0;
			$suffix_title = "";
			$where = "";
			$data = array();
			$get = $_GET;
			
			$handle2 = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$handle = "#" . $handle2;
			
			$params = isset( $this->class_settings["params"] )?$this->class_settings["params"]:array();
			
			$filename = 'display-chart';
			$type = 'reference_table';

			$title = ( isset( $params["title"] ) && $params["title"] ) ? $params["title"] : '';
			$title = ( isset( $_GET["title"] ) && $_GET["title"] ) ? $_GET["title"] : $title;

			$start_date = ( isset( $_POST["start_date"] ) && $_POST["start_date"] ) ? $_POST["start_date"] : '';
			$end_date = ( isset( $_POST["end_date"] ) && $_POST["end_date"] ) ? $_POST["end_date"] : '';
			$user = ( isset( $_POST["user"] ) && $_POST["user"] ) ? $_POST["user"] : '';
			
			if( isset( $params["html_replacement_selector"] ) && $params["html_replacement_selector"] ){
				$handle2 = $params["html_replacement_selector"];
			}
			
			if( ( isset( $this->class_settings[ 'current_store' ] ) && $this->class_settings[ 'current_store' ] ) ){
				$current_store = $this->class_settings[ 'current_store' ];
			}
			
			$data["html_replacement_selector"] = $handle2;
			$data["action_to_perform"] = $action_to_perform;
			
			if( ! $start_date ){
				$start_date = date("Y-m-d\TH:i", date("U") - (86400*4) );
			}
			
			$data["start_date"] = $start_date;
			$data["end_date"] = $end_date;
			
			switch( $action_to_perform ){
			case "view_dashboard":
				
				$dx = array();
				if( $start_date && $end_date ){
					$where .= " AND `". $this->table_name ."`.`".$this->table_fields["date"]."` BETWEEN ". strtotime( $start_date ) ." AND ". strtotime( $end_date ) ." ";
				}else{
					$where .= " AND `". $this->table_name ."`.`".$this->table_fields["date"]."` >= ". strtotime( $start_date ) ." ";
				}
				if( $user ){
					$where .= " AND `". $this->table_name ."`.`".$this->table_fields["data"]."` LIKE '%modified_by\":\"". $user ."\"%' ";
				}
				
				$this->class_settings[ 'where' ] = $where;
			
				$data[ 'title' ] = $this->label . ": Status";
				$opt = function_exists( 'get_precription_status' ) ? get_precription_status() : [];

				//$this->class_settings[ 'overide_select' ] = " COUNT( DISTINCT `". $this->table_name ."`.`". $this->table_fields[ 'reference' ] ."` ) as 'count', `". $this->table_name ."`.`". $this->table_fields[ $type ] ."` as 'id', `". $this->table_name ."`.`". $this->table_fields[ $type ] ."` as 'text' ";
				
				$this->class_settings[ 'overide_select' ] = " `". $this->table_name ."`.`". $this->table_fields[ $type ] ."` as 'id', `". $this->table_name ."`.`". $this->table_fields[ $type ] ."` as 'text' ";
				
				$this->class_settings[ 'group' ] = " GROUP BY `". $this->table_name ."`.`". $this->table_fields[ $type ] ."` ";

				$dx = $this->_get_records();
				switch( $type ){
				case 'status':
				break;
				}
				
				$title2 = $this->label . ': ';
				if( isset( $title ) ){
					$data[ 'title' ] = rawurldecode( $title );
					$title2 .= $data[ 'title' ] . ' - ';
				}
				
				// print_r( $dx );exit;
				if( ! empty( $dx ) && isset( $dx[0] ) ){
					foreach( $dx as $k => & $w ){
						if( gettype( $k ) == 'integer' ){
							$tex = explode( ':::', $w[ 'text' ] );
							$plugin = '';
							if( isset( $tex[1] ) && $tex[1] ){
								$w[ 'id' ] = $tex[1];
								$w[ 'text' ] = $tex[1];
								$plugin = $tex[0];
							}

							$id = $w[ 'id' ];
							$w[ 'item_id' ] = $id;
							if( isset( $opt[ $w[ 'text' ] ] ) && $opt[ $w[ 'text' ] ] )$w[ 'text' ] = $opt[ $w[ 'text' ] ];
							
							$title3 = rawurlencode( $title2 . $w[ 'text' ] );
							//$w[ 'text' ] .= ' ('. $w[ 'count' ] .')';

							// $w[ 'children' ] = true;
							$w['id'] = "action=".$this->table_name.":::todo=display_all_record_filter:::get_children=1:::title=". $title3 .":::gtype=". $type .":::". $type ."=" . $w['id'] .":::start_date=" . $start_date .":::end_date=" . $end_date .":::user=" . $user.":::plugin=" . $plugin;
							// unset( $dps[ $k ] );
						}
					}
				}
				
				$data[ 'data' ] = $dx;

			break;
			}
			
			$this->class_settings[ 'data' ] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			
			$html = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $html,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js
			);
		}
		
		protected function _set_revision_history(){

			$error_msg = '';
			if( isset( $this->class_settings[ 'rdata' ] ) && $this->class_settings[ 'rdata' ] ){
				$settings = $this->class_settings[ 'rdata' ];
				//print_r( $settings );
				//print_r( $settings );exit;

				if( ! ( isset( $settings[ 'revision_history' ][ 'where' ] ) && $settings[ 'revision_history' ][ 'where' ] ) ){
					
				}else if( isset( $settings[ 'u_field' ] ) && $settings[ 'u_field' ] && isset( $settings[ 'u_value' ] ) && $settings[ 'u_value' ] ){
					$settings[ 'revision_history' ][ 'where' ] = " WHERE `".$settings[ 'u_field' ]."` = '". $settings[ 'u_value' ] . "' ";
				}
				
				//13-mar-23
				if( isset( $settings[ 'u_field1' ] ) && $settings[ 'u_field1' ] && isset( $settings[ 'u_value1' ] ) && $settings[ 'u_value1' ] ){
					$settings[ 'revision_history' ][ 'where' ] = " WHERE `".$settings[ 'u_field1' ]."` = '". $settings[ 'u_value1' ] . "' ";
				}
				
				//print_r( $settings );
				if( isset( $settings[ 'revision_history' ][ 'where' ] ) && $settings[ 'revision_history' ][ 'where' ] ){

					if( isset( $settings[ 'tables' ][0] ) && $settings[ 'tables' ][0] ){
						$select = '';
						$plugin = isset( $settings[ 'plugin' ] ) ? $settings[ 'plugin' ] : '';

						$t = $settings[ 'tables' ][0];

						$cpl = 'c'. $plugin;
						if( class_exists( $cpl ) ){
							$cpl = new $cpl;
							$cpl->load_class( array( 'class' => array( $t ) ) );
						}

						$tb = 'c'.ucwords( $t );
						if( class_exists( $tb ) ){
							$tb = new $tb;
							$tb_fields = $tb->table_fields;
							foreach( $tb_fields as $key => $val ){
								if( $select )$select .= ", `".$val."` as '".$key."'";
								else $select .= " `id`, `serial_num`, `created_by`, `modification_date`, `creation_date`, `modified_by`, `".$val."` as '".$key."'";
							}

							$query = "SELECT ". $select ." FROM `". (isset( $settings[ 'database' ] ) ? $settings[ 'database' ] : $this->class_settings[ 'database_name' ]) ."`.`".$t."` ". $settings[ 'revision_history' ][ 'where' ];
							$query_settings = array(
								'database' => isset( $settings[ 'database' ] ) ? $settings[ 'database' ] : $this->class_settings[ 'database_name' ],
								'connect' => isset( $settings[ 'connect' ] ) ? $settings[ 'connect' ] : $this->class_settings[ 'database_connection' ],
								'query' => $query,
								'query_type' => 'SELECT',
								'set_memcache' => 0,
								'tables' => array(),
								'skip_log' => 1,
							);
							$data = execute_sql_query($query_settings);
							//print_r($data); exit;
							// if( $t == 'individuals' ){ print_r( $settings ); exit;}
							//print_r( $data );
							if( isset( $data ) && is_array( $data ) && $data ){
								//13-mar-23
								$this->old_values = $data;
								if( count( $data ) == 1 ){
									$this->old_values = $data[0];
									$this->old_values["_t"] = $t;
									$this->old_values["_s"] = 'revh';
								}
								
								$line_items = array();
								foreach( $data as $k => $v ){

									//check how many history record already has
									$ss = array(
										'reference' => $v[ 'id' ],
										'reference_table' => $t,
									);
									$a = $this->_check_revision_history( $ss );

									$line_items[] = array(
										'date' => time(),
										'type' => isset( $settings[ 'revision_history' ][ 'type' ] ) ? $settings[ 'revision_history' ][ 'type' ] : '',
										'data' => json_encode( $v ),
										'comment' => ( isset( $settings[ 'revision_history' ][ 'comment' ] ) ? $settings[ 'revision_history' ][ 'comment' ] : '' ),
										'reference' => $v[ 'id' ],
										'reference_table' => $plugin ? $plugin . ':::' . $t : $t,
									);
								}
								// print_r( $line_items );
								$this->class_settings[ 'line_items' ] = $line_items;

								if( ! $this->_save_line_items() ){
									$error_msg = 'Unable to save revision history';
								}else{
									return 1;
								}

							}else{
								$error_msg = 'Could not Locate Record Details';
							}
						}
					}
				}
			}
			if( $error_msg ){
				return $this->_display_notification( array( "type" => 'error', "message" => $error_msg ) );
			}
		}

		protected function _check_revision_history( $settings = array() ){
			
			$error_msg = '';
			$etype = 'error';
			$filename = '';
			$modal_style = "width:60%;";

			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$handle = "dash-board-main-content-area";
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
			}

			switch( $action_to_perform ){
			case 'show_revision_history':
			case 'show_revision_history2':
				$settings[ 'reference' ] = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
				$settings[ 'reference_table' ] = isset( $_GET[ 'table' ] ) ? $_GET[ 'table' ] : '';
			break;
			}

			switch( $action_to_perform ){
			case 'show_revision_history2':
				$this->class_settings[ 'overide_select' ] = " `id`, `created_by`, `creation_date` ";
			break;
			}

			$return = array();
			if( isset( $settings[ 'reference' ] ) && $settings[ 'reference' ] && isset( $settings[ 'reference_table' ] ) && $settings[ 'reference_table' ]  ){
				$ref_tb = $settings[ 'reference_table' ];
				$plugin = '';
				if( isset( $_GET[ 'plugin' ] ) && $_GET[ 'plugin' ] ){
					$plugin = $_GET[ 'plugin' ];
					$ref_tb = $_GET[ 'plugin' ] . ':::' . $settings[ 'reference_table' ];
				}
				
				$this->class_settings[ 'where' ] = " AND `". $this->table_fields[ 'reference' ] ."` = '". $settings[ 'reference' ] ."' AND `". $this->table_fields[ 'reference_table' ] ."` = '". $ref_tb ."' ";
				$this->class_settings[ 'order_by' ] = " ORDER BY `". $this->table_fields[ 'date' ] ."` ASC ";
				$this->class_settings[ 'no' ] = " ORDER BY `". $this->table_fields[ 'date' ] ."` ASC ";
				$data = $this->_get_records();
				// print_r( $data );exit;

				if( ! empty( $data ) && isset( $data[0] ) ){

					switch( $action_to_perform ){
					case 'show_revision_history':
					case 'show_revision_history2':

						$x = $settings[ 'reference_table' ];

						if( $plugin ){
							$cpl = 'c'. $plugin;
							if( class_exists( $cpl ) ){
								$cpl = new $cpl;
								$cpl->class_settings = $this->class_settings;
								$cpl->load_class( array( 'class' => array( $x ) ) );
							}
						}

						$xc = 'c' . ucwords( $x );
						if( class_exists( $xc ) ){
							$cl = new $xc;
							$cl->class_settings = $this->class_settings;
							$cl->class_settings[ 'current_record_id' ] = $settings[ 'reference' ];
							$this->class_settings[ 'data' ][ 'data' ] = $data;

							switch( $action_to_perform ){
							case 'show_revision_history':
								$this->class_settings[ 'data' ][ 'current_record' ] = $cl->_get_record();
								$this->class_settings[ 'other_params' ][ 'fields' ] = $cl->table_fields;
								$this->class_settings[ 'other_params' ][ 'labels' ] = $x();
								$filename = 'show-revision-history.php';
							break;
							case 'show_revision_history2':
								$this->class_settings[ 'other_params' ][ 'table' ] = $x;
								$this->class_settings[ 'other_params' ][ 'id' ] = $settings[ 'reference' ];
								$filename = 'show-revision-history2.php';
								$_GET[ 'modal' ] = 1;
							break;
							}
				
							$this->class_settings["data"]["action_to_perform"] = $action_to_perform;
							$this->class_settings["html_filename"] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
							$this->class_settings["modal_dialog_style"] = $modal_style;
							$this->class_settings["modal_dialog_class"] = ' modal-xl ';

							$return = $this->_view_details();
						}
						
					break;
					default:
						$count = count( $data );
						$history_limit = ( isset( $this->history_max_no ) ? $this->history_max_no : 5 );

						if( $count >= $history_limit ){
							for( $i = 0; $i <= ( $count - $history_limit ); $i++ ) {

								$query = "DELETE FROM `". $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `id` = '". ( isset( $data[ $i ][ 'id' ] ) ? $data[ $i ][ 'id' ] : '' ) ."' ";
									
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query,
									'query_type' => 'DELETE',
									'set_memcache' => 1,
									'tables' => array( $this->table_name ),
									'revision_history' => array( 'skip' => 1 ),
								);
								execute_sql_query($query_settings);
							}
						}
					break;
					}
				}else{
					$error_msg = 'No History Found for this Record';
				}
			}

			if( $error_msg ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error_msg ) );
			}

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
			$where = '';
			$spilt_screen = 0;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;

			$hide_title = isset( $_GET[ 'hide_title' ] ) ? $_GET[ 'hide_title' ] : '';
			$disable_btn = isset( $_GET[ 'disable_btn' ] ) ? $_GET[ 'disable_btn' ] : '';
			$id = isset( $_GET[ 'customer' ] ) ? $_GET[ 'customer' ] : '';
			
			switch( $action_to_perform ){
			case "display_all_records_full_view_search":
			case "display_all_records_frontend_history":
				$show_form = 1;
				unset( $this->class_settings[ "full_table" ] );
			break;
			case "display_all_record_filter":
				$start_date = ( isset( $_GET["start_date"] ) && $_GET["start_date"] ) ? $_GET["start_date"] : '';
				$end_date = ( isset( $_GET["end_date"] ) && $_GET["end_date"] ) ? $_GET["end_date"] : '';
				$user = ( isset( $_GET["user"] ) && $_GET["user"] ) ? $_GET["user"] : '';
				$gtype = ( isset( $_GET["gtype"] ) && $_GET["gtype"] ) ? $_GET["gtype"] : '';
				$ref = ( isset( $_GET[ $gtype ] ) && $_GET[ $gtype ] ) ? $_GET[ $gtype ] : '';
				
				
				if( $start_date && $end_date ){
					$where .= " AND `". $ref ."`.`modification_date` BETWEEN ". strtotime( $start_date ) ." AND ". strtotime( $end_date ) ." ";
				}else if( $start_date ){
					$where .= " AND `". $ref ."`.`modification_date` >= ". strtotime( $start_date ) ." ";
				}

				if( $user ){
					$where .= " AND `". $this->table_name ."`.`".$this->table_fields["data"]."` LIKE '%modified_by\":\"". $user ."\"%' ";
				}
				
				if( $ref && isset( $this->table_fields[ $gtype ] ) ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ $gtype ] ."` = '". $ref ."' ";
				}

				if( isset( $_GET[ 'plugin' ] ) && $_GET[ 'plugin' ] ){
					$cpl = 'c'. $_GET[ 'plugin' ];
					if( class_exists( $cpl ) ){
						$cpl = new $cpl;
						$cpl->class_settings = $this->class_settings;
						$cpl->load_class( array( 'class' => array( $ref ) ) );
					}
				}

				$ccll = 'c'.$ref;
				$tb = $ref;
				if( class_exists( $ccll ) ){
					$ccll = new $ccll;
					$ccll->class_settings = $this->class_settings;

					unset( $_GET[ 'title' ] );

					/* if( isset( $cpl->table_name ) && $cpl->table_name ){
						$ccll = $cpl;
						$ccll->class_settings[ 'action_to_perform' ] = 'execute';
						$ccll->class_settings[ 'nwp_action' ] = $ref;
						$ccll->class_settings[ 'nwp_todo' ] = 'display_all_records_frontend';
						$tb = $cpl->table_name;
					}else{
						$ccll->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';
					} */
					
					$cd = new cCustomer_call_log();
					$cd->class_settings = $this->class_settings;
					$cd->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';
					$cd->label = $ccll->label;
					$cd->table_name = $ccll->table_name;
					$cd->table_fields = $ccll->table_fields;

					$jooin = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $this->table_name ."` ON `". $this->table_name ."`.`". $this->table_fields[ 'reference' ] ."` = `". $ref ."`.`id` AND `". $this->table_name ."`.`record_status` = '1' ";
					$cd->class_settings[ 'filter' ][ 'join' ] = $jooin;
					$cd->class_settings[ 'filter' ][ 'join_count' ] = $jooin;

					$gp = " GROUP BY `". $this->table_name ."`.`". $this->table_fields[ 'reference' ] ."` ";
					$cd->class_settings[ 'filter' ][ 'group' ] = $gp;
					$cd->class_settings[ 'filter' ][ 'select_count' ] = " COUNT( DISTINCT( `". $this->table_name ."`.`". $this->table_fields[ 'reference' ] ."` ) ) as 'count' ";

					$cd->class_settings[ 'full_table' ] = 1;
					$cd->class_settings[ 'filter' ][ 'where' ] = $where . " OR ( `". $ref ."`.`record_status` != '1' ".$where." ) ";

					$cd->class_settings[ 'datatable_settings' ][ "show_add_new" ] = 0;
					$cd->class_settings[ 'datatable_settings' ][ "show_edit_button" ] = 0;
					$cd->class_settings[ 'datatable_settings' ][ "show_delete_button" ] = 0;
					$cd->class_settings[ 'datatable_settings' ][ "show_refresh_cache" ] = 0;
					$cd->class_settings[ 'datatable_settings' ][ "show_advance_search" ] = 0;
					$cd->class_settings['more_data']['additional_params'] = '&app_manager_control=1&table='.$cd->table_name;
					$cd->basic_data = array(
						'access_to_crud' => 1,
						'more_actions' => array(
							'sb2' => array(
								'action' => $this->table_name,
								'todo' => 'show_revision_history',
								'title' => 'View Details',
								'text' => 'View Details',
							)
						)
					);

					// $cd->class_settings[ 'datatable_settings' ][ "utility_buttons" ] = array( 'revision_history' => array( 'label' => 'View Details' ) );
					//unset( $cd->basic_data );

					return $cd->customer_call_log();
				}
				
			break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 1;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				unset( $this->basic_data['more_actions'] );
			}

			if( $where )$this->class_settings[ 'filter' ][ 'where' ] = $where;
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_history":
				$this->datatable_settings[ "show_edit_button" ] = 1;
				unset( $this->basic_data['more_actions'] );
			break;
			}
			
			$this->class_settings[ "full_table" ] = 1;
			$this->class_settings[ "frontend" ] = 1;
			$this->class_settings[ "show_popup_form" ] = 1;
			$this->class_settings[ "search_popup" ] = 1;

			$this->datatable_settings[ "show_add_new" ] = 0;

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

		protected function _display_app_view2(){
			$c = array();
			//$c = get_record_details( array( "id" => get_current_customer(), "table" => "customers" ) );
			
			$this->class_settings[ 'data' ]["class"] = 'col-md-3';
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
			
			$return = $this->_display_app_view();
			return $return;
		}
		
		protected function _save_app_changes2(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//check for project details & update
				
			break;
			}
			
			$return = $this->_save_app_changes();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//display payment info
				/*
				if( isset( $return["data"]["id"] ) && $return["data"]["id"] ){
					$_POST["id"] = '';
					$_POST["tmp"] = $return["data"]["id"];
					
					$stores = new cStores();
					$stores->class_settings = $this->class_settings;
					$stores->class_settings["action_to_perform"] = "update_table_field";
					$stores->class_settings["update_fields"] = array(
						"name" => $return["data"]["name"],
						"address" => $return["data"]["location"],
						"comment" => $return["data"]["comment"],
					);
					$return1 = $stores->stores();
					
					if( ! ( isset( $return1["saved_record_id"] ) && $return1["saved_record_id"] ) ){
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = '_resend_verification_email';
						$err->additional_details_of_error = 'Project creation failed due to business units issues';
						return $err->error();
					}
				}
				*/
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["amount_paid"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["payment_method"] ] = 1;
			break;
			}
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		protected function _search_customer_call_log2(){
			$where = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'search_list':
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
			break;
			case 'search_list2':
				$where = "";
				//$filename = 'list.php';
				//$item_key = "item";
				
				if( isset( $_POST[ "field" ] ) && $_POST[ "field" ] && isset( $this->table_fields[ $_POST[ "field" ] ] ) ){
					
					$val = $this->table_fields[ $_POST[ "field" ] ];
					$field = $_POST[ "field" ];
					
					$t = $this->table_name;
					$lbl = $t();
					
					if( isset( $lbl[ $val ][ "form_field" ] ) ){
						switch( $lbl[ $val ][ "form_field" ] ){
						case "date-5":
							
							if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
								$where .= " AND `".$val."` >= " . convert_date_to_timestamp( $_POST["start_date"], 1 );
							}
							
							if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
								$where .= " AND `".$val."` <= " . convert_date_to_timestamp( $_POST["end_date"], 1 );
							}
							
							if( ! $where )$allow = 1;
						break;
						default:
							
							if( isset( $_POST[ "search" ] ) ){
								if( $_POST[ "search" ] ){
									$where = " AND `".$this->table_name."`.`".$val."` REGEXP '".$_POST[ "search" ]."' ";
								}else{
									$allow = 1;
								}
								
							}
						break;
						}
					}
					
				}
				
				if( ! ( $where || $allow ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Search Criteria</h4>Please try again';
					return $err->error();
				}
				
				unset( $_POST["start_date"] );
				unset( $_POST["end_date"] );
				
			break;
			default:
				if( ! ( isset( $_POST[ $this->default_reference ] ) && $_POST[ $this->default_reference ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.$this->customer_source.' first';
					return $err->error();
				}
				
				$where = " AND `".$this->table_name."`.`".$this->table_fields[ $this->default_reference ]."` = '".$_POST[ $this->default_reference ]."' ";
			break;
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"], 1 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				else $where = " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"] , 2 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
					else $where = " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			
			$this->class_settings["where"] = $where;
			$data = $this->_get_all_customer_call_log();
			
			if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
				return $data;
			}
			
			if( empty( $data ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No '.ucwords( $this->label ).' Record(s)</h4>';
				$return = $err->error();
				$returning_html_data = $return["html"];
			}else{
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$this->class_settings[ 'data' ][ "items" ] = $data;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/item-list.php' );
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
	}
	
	function revision_history(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/revision_history.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/revision_history.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
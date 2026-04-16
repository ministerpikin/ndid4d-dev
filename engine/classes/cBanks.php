<?php
	/**
	 * banks Class
	 *
	 * @used in  				banks Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	banks
	 */

	/*
	|--------------------------------------------------------------------------
	| banks Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cBanks  extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $label = "Manage General Options";
		public $table_name = 'banks';
		public $table_name_static = 'customer_call_log';
		
		private $associated_cache_keys = array(
			'banks',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, eidt, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
		);
		
		public $table_fields = array(
			'type' => 'banks005',
			'name' => 'banks001',
			'location' => 'banks002',
			'comment' => 'banks004',
			
			'serial' => 'banks006',
			'value' => 'banks007',
			'percentage' => 'banks008',
			'expense_account' => 'banks009',
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,		//Determines whether or not to show delete button
				
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
	
		function banks(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					return $this->_display_notification( array( "type" => 'no_language', "message" => 'no language file' ) );
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
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
				$returned_value = $this->_delete_records2();
			break;
			case 'save':
				$returned_value = $this->_save_changes2();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache2();
			break;
			case 'get_select2':
			case 'get_item_classification':
			case 'get_types_of_purchase_import_cost':
				$returned_value = $this->_get_select2();
			break;
			case 'create_new_record':
			case 'edit':
			case 'edit_popup_form_in_popup':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
			break;
			case 'get_list_for_search':
				$returned_value = $this->_get_list_for_search();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'get_list':
			case 'get_data':
				$returned_value = $this->_get_records();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _search_form2(){
			$where = "";
			
			$key = 'type';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			/* 
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
			 */
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}

		protected function _display_all_records_full_view2(){
			$action_to_perform = $this->class_settings[ "action_to_perform" ];
			$show_search = 1;
			
			switch( $action_to_perform ){
			case "display_all_records_frontend":
				$op = get_options_type();
				
				if( isset( $_GET["type"] ) && isset( $op[ $_GET["type"] ] ) ){
					$this->label = $op[ $_GET["type"] ];
					$show_search = 0;
					$this->class_settings["filter"]["where"] = " AND `".$this->table_name."`.`".$this->table_fields["type"]."` = '". $_GET["type"] ."' ";
					
					$this->datatable_settings["button_params"] = '&type=' . $_GET["type"];
				}
			break;
			}
			
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings["html_replacement_selector"];
			}
			
			if( $show_search ){
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';

				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "type":
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					default:
						$this->class_settings["hidden_records"][$val] = 1;
					break;
					}
				}
			}else{
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
			}
			
			return $this->_display_all_records_full_view();
		}
		
		protected function _get_list_for_search(){
			$search = '';
			if( isset( $_POST[ 'term' ] ) && $_POST[ 'term' ] ){
				$search = trim( $_POST[ 'term' ] );
			}
			
			$function = '';
			if( isset( $_GET[ 'function' ] ) && $_GET[ 'function' ] ){
				$function = trim( $_GET[ 'function' ] );
			}
			
			$source = '';
			if( isset( $_GET[ 'source' ] ) && $_GET[ 'source' ] ){
				$source = trim( $_GET[ 'source' ] );
			}
			
			$search_role = '';
			$sp_where_caml = "";
			$condition = array();
			
			switch( $this->class_settings["action_to_perform"] ){
			case "get_fiaps_group_head_per_department":
				//search by group
				$dept = new cFiaps_department();
				$dept->class_settings = $this->class_settings;
				$dept->class_settings["action_to_perform"] = "get_select_options";
				return $dept->fiaps_department();
			break;				
			case 'get_list_for_search':
				$return = array( "items" => array() );
				$rs = array();
				if( $search )$search = strtolower( $search );
				$opts = array();
				
				switch( $source ){
				case 2:
				case "2":
					if( $function ){
						$opts = get_list_box_options( $function, array( "return_type" => 2 ) );
					}
				break;
				default:
					if( $function && function_exists( $function ) ){
						$opts = $function();
					}
				break;
				}
				// print_r( $function );exit;
				
				if( ! empty( $opts ) ){
					foreach( $opts as $id => $sval ){
						if( $search && ( preg_match( "/".$search."/", strtolower( $sval ) ) ) ){
							$rs[] = array( "id" => $id, "text" => $sval );
						}
						$return["items"][] = array( "id" => $id, "text" => $sval );
					}
				}
				
				if( ! empty( $rs ) ){
					$return["items"] = $rs;
				}
				
				return $return;
			break;
			}
			
		}
		
		protected function _save_app_changes(){
			$return = array();
			
			$return = $this->_save_changes();
			if( isset( $return['saved_record_id'] ) && $return['saved_record_id'] ){
				$returning_html_data = $return["html"];
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				
				$return["html_replacement_selector"] = "#modal-replacement-handle";
				$return["html_replacement"] = $returning_html_data;
			}
			
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			
			$type = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
			break;
			default:
				$type_list = get_options_type();
				if( ( isset( $_GET["type"] ) && $_GET["type"] && isset( $type_list[ $_GET["type"] ] ) ) ){
					$type = $_GET["type"];
					$this->label = $type_list[ $type ];
				}
			break;
			}
			
			foreach( $this->table_fields as $k => $v ){
				switch( $k ){
				case "type":
					if( $type ){
						$this->class_settings["hidden_records_css"][ $v ] = 1;
						$this->class_settings["form_values_important"][ $v ] = $type;
					}
				break;
				case "name":
				case "comment":
				break;
				default:
					$this->class_settings["hidden_records"][ $v ] = 1;
				break;
				}
				
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				return $this->_generate_new_data_capture_form();
			break;
			default:
				$this->class_settings["skip_link"] = 1;
			break;
			}
			
			return $this->_new_popup_form();
		}
		
		protected function _new_popup_formX(){
			$err = 0;
			$c_name = "";
			
			if( ! ( isset( $this->class_settings[ 'form_action' ] ) && $this->class_settings[ 'form_action' ] ) ){
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_new_popup';
			}
			
			$this->class_settings['do_not_show_headings'] = 1;
			$d = $this->_generate_new_data_capture_form();
			
			$html = "";
			if( isset( $d["html"] ) )$html = $d["html"];
			
			if( isset( $this->class_settings["before_html"] ) && $this->class_settings["before_html"] ){
				$html = $this->class_settings["before_html"] . $html;
			}
			
			$label = isset( $this->label )?$this->label:ucwords( $this->table_name );
			
			switch( $this->class_settings["action_to_perform"] ){
			case "new_popup_form_in_popup":
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
				);
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = isset( $this->class_settings["modal_title"] )?( $this->class_settings["modal_title"] ):( $label );
			$this->class_settings[ 'data' ]['html'] = $html;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( "prepare_new_record_form_new" , "set_function_click_event" ),
			);
			
		}
		
		// protected function _get_select2(){

		// 	return $this->_get_default_select2();
		// }
		
		private function _get_select2(){
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
			}
			$where = '';
			$limit = '';
			$add_items = array();
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$_POST["term"] = trim( $_POST["term"] );
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["type"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["name"]."` regexp '".$_POST["term"]."' ";
				
				$where .= " ) ";
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$hide_type = 0;
			$join = '';
			$order = " ORDER BY `".$this->table_name."`.`".$this->table_fields["name"]."` ";
			$group = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'get_types_of_purchase_import_cost':
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["type"]."` IN ( 'cfr', 'local_charges' ) ";
				
			break;
			case 'get_item_classification':
					
				$items = new cItems();
				$join = " JOIN `" . $this->class_settings['database_name'] . "`.`".$items->table_name."` ON ";
					
				if( class_exists( 'cGeneric_name' ) ){
					$gn = new cGeneric_name();
					$gn->class_settings = $this->class_settings;
					$gn->class_settings[ 'action_to_perform' ] = 'get_select2';
					$gn->class_settings[ 'join' ] = $join."`".$items->table_name."`.`".$items->table_fields["sub_category"]."` =  `".$gn->table_name."`.`id` ";
					$gn->class_settings[ 'group' ] = "GROUP BY `".$gn->table_name."`.`id` ";

					return $gn->generic_name();
				}else{

					$join .= " `".$items->table_name."`.`".$items->table_fields["sub_category"]."` =  `".$this->table_name."`.`id` ";

					$group = "GROUP BY `".$this->table_name."`.`id` ";
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["type"]."` IN ( 'item_classification' ) ";
					
					$hide_type = 1;
				}
			break;
			default:
				if( ! ( isset( $_GET["type"] ) && $_GET["type"] ) ){
					$return = array( "items" => array(), "do_not_reload_table" => 1 );
				}else{
					switch( $_GET[ 'type' ] ){
					case 'item_classification':
						if( class_exists( 'cGeneric_name' ) ){
							$gn = new cGeneric_name();
							$gn->class_settings = $this->class_settings;
							$gn->class_settings[ 'action_to_perform' ] = 'get_select2';

							return $gn->generic_name();
						}
					break;
					}
				}
				
				$hide_type = isset( $_GET["hide_type"] )?$_GET["hide_type"]:0;
				$type = $_GET["type"];
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["type"]."` = '".$type."' ";
				
				switch( $type ){
				case "reason_for_discharge":
					if( function_exists("get_default_hos_reason_for_discharge") ){
						$add_items = get_default_hos_reason_for_discharge( $type );
						
					}
				break;
				}
			break;
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$join." WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ".$group.$order.$limit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_items = execute_sql_query($query_settings);
			$return = array( "items" => $add_items );
			$option = get_options_type();
			
			if( ! empty( $all_items ) && is_array( $all_items ) ){
				foreach( $all_items as $sval ){
					
					// if( $hide_type ){
						$name = $sval["name"];
					// }else{
						// $name = $sval["name"] . ( isset( $option[ $sval["type"] ] )?( ' - ' . $option[ $sval["type"] ] ):'' );
					// }
					
					$return["items"][] = array( "id" => $sval["id"], "text" => $name, "type" => $sval["type"] );
				}
			}
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}
		
		private function _refresh_cache2(){
			//empty permanent cache folder
			clear_cache_for_special_values_directory( array(
				"permanent" => true,
				"directory_name" => $this->table_name,
			) );
			
			unset( $this->class_settings['user_id'] );
			$this->class_settings[ 'do_not_check_cache' ] = 1;
			$this->_get_banks();
			
			$error_msg = '<h4>Successful Refresh</h4>';
			return $this->_display_notification( array( "type" => "success", "message" => $error_msg ) );
		}
		
		private function _delete_records2(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
			if( isset( $returning_html_data['deleted_record_id'] ) && $returning_html_data['deleted_record_id'] ){
				$cache_key = $this->table_name;
				
				$this->class_settings[ 'do_not_check_cache' ] = 1;
				$this->_get_banks();
			}
			return $returning_html_data;
		}
		
		private function _save_changes2(){
			$returning_html_data = array();
			/*
			if( isset( $_POST[ $this->table_fields["name"] ] ) && $_POST[ $this->table_fields["name"] ] ){
				$_POST["tmp"] = 'bnk_'.clean1( strtolower( $_POST[ $this->table_fields["name"] ] ) );
			}
			*/
			
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
					$this->class_settings['current_record_id'] = $returning_html_data['saved_record_id'];
					$record = $this->_get_banks();
				}
			}
			
			return $returning_html_data;
		}
		
		private function _get_banks(){
			
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			
			$returning_array = array(
				'html' => '',
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'get-product-features',
			);
			
			//CHECK WHETHER TO CHECK FOR CACHE VALUES
			if( ! ( isset( $this->class_settings[ 'do_not_check_cache' ] ) && $this->class_settings[ 'do_not_check_cache' ] ) ){
				
				//CHECK FOR CACHED VALUES
				
				//CHECK IF CACHE IS SET
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ORDER BY `".$this->table_fields['serial']."` ASC ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_departments = execute_sql_query($query_settings);
			
			$departments = array();
			
			if( is_array( $all_departments ) && ! empty( $all_departments ) ){
				
				foreach( $all_departments as $category ){
					$departments[ $category[ 'type' ] ][ $category[ 'id' ] ] = $category['name'];
					
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-".$category["id"],
						'cache_values' => $category,
						'directory_name' => $cache_key,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
				}
				
				foreach( $departments as $key => $val ){
					//Cache Settings
					$settings = array(
						'cache_key' => $cache_key."-".$key,
						'directory_name' => $cache_key,
						'cache_values' => $val,
						'permanent' => true,
					);
					
					if( ! set_cache_for_special_values( $settings ) ){
						//report cache failure message
					}
				}
				
				
				return $departments;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-banks-',//.$record["member_id"],
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			$members = get_cache_for_special_values( $settings );
			
			if( is_array( $members ) ){
				if( $clear ){
					unset( $members[ $record['id'] ] );
				}else{
					$members[ $record['id'] ] = $record;
				}
				
				$settings = array(
					'cache_key' => $cache_key.'-banks-'.$record["member_id"],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
	}
	/*
	ALTER TABLE `banks`  ADD `banks005` VARCHAR(100) NOT NULL  AFTER `id`;
	ALTER TABLE `banks`  ADD `banks004` VARCHAR(100) NOT NULL  AFTER `banks003`;
	*/
?>
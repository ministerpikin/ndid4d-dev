<?php
	/**
	 * stores Class
	 *
	 * @used in  				stores Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	stores
	 */

	/*
	|--------------------------------------------------------------------------
	| stores Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cStores extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'stores';
		public $label = 'Stores & Branches';
		
		private $associated_cache_keys = array(
			'stores',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,
			'exclude_from_crud' => array( 'delete' => 1 ),
		);
		
		public $prevent_duplicate = array(
			'condition' => 'AND',
			'field_settings' => array( 'type' => array( 'skip_values' => array( 'main-store' ) ), 'department' => array( 'skip_if_empty' => 1 ), 'parent' => array( 'skip_if_empty' => 1 ), 'section' => array( 'skip_if_empty' => 1 ) ),
			'fields' => array( 'name', 'department', 'type', 'parent', 'section' ),
			'error_title' => 'Duplicate Store',
			'error_message' => 'There is an existing store account:',
		);
		
		public $table_fields = array(
			'name' => 'stores001',
			'department' => 'stores010',
			'address' => 'stores002',
			'phone' => 'stores003',
			'email' => 'stores004',
			'store_options' => 'stores005',
			'geo_code' => 'stores006',
			'type' => 'stores007',	//branch, main store, sub-store
			'parent' => 'stores008',
			'section' => 'stores009',
			'zone' => 'stores011'
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 0,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
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
			
			//19-jun-23: 2echo
			if( defined("USERS_COUNTRY_LABEL") && USERS_COUNTRY_LABEL ){
				$this->label = USERS_COUNTRY_LABEL;
			}
			
		}
	
		function stores(){
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
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
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
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes();
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'get_stores_list4':
			case 'get_stores_list3':
			case 'get_stores_list2':
			case 'get_stores_list':
				$returned_value = $this->_get_stores_list();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'change_store_backend':
			case 'change_store_and_reload_tree':
			case 'change_store':
				$returned_value = $this->_change_store();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case 'get_i_menu_stores':
				$returned_value = $this->_get_i_menu_stores();
			break;
			case 'move_all_transactions_to_store':
				$returned_value = $this->_move_all_transactions_to_store();
			break;
			case 'get_all_stores':
				$returned_value = $this->_get_all_stores();
			break;
			case 'get_active_store':
			case 'get_select2':
			case 'get_select2_special':
				$returned_value = $this->_get_select2();
			break;
			case 'edit_popup_form_in_popup':
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
				$returned_value = $this->_search_customer_call_log();
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
			case "get_record":
				$returned_value = $this->_get_record();
			break;
			case "store_extracted_line_items_data":
				$returned_value = $this->_store_extracted_line_items_data();
			break;
			case "get_stores_distribution_fixed_assets_count":
			case "get_stores_distribution":
				$returned_value = $this->_get_stores_distribution();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _get_stores_distribution(){
			$error_msg = '';
			$e_type = 'error';
			
			$container1 = '';
			$container = '';
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$js = array( 'set_function_click_event' );
			
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$container1 = $this->class_settings["html_replacement_selector"];
				$container = '#' . $container1;
			}
			
			$filename = 'store-summaries.php';
			$ed = array();
			
			switch( $action_to_perform ){
			case "get_stores_distribution":
				if( isset( $_POST["hc-key"] ) && $_POST["hc-key"] ){
					
					$this->class_settings["where"] = " AND `". $this->table_fields["geo_code"] ."` = '" . $_POST["hc-key"] . "' ";
					$ed["stores"] = $this->_get_records();
					
					$st2 = array();
					if( ! empty( $ed["stores"] ) && is_array( $ed["stores"] ) ){
						foreach( $ed["stores"] as $sv ){
							$st2[] = "'". $sv["id"] ."'";
						}
						
						$assets = new cAssets();
						$assets->class_settings = $this->class_settings;
						$assets->class_settings["action_to_perform"] = "get_data";
						
						$assets->class_settings["overide_select"] = " COUNT(*) as 'count', `".$assets->table_fields["category"]."` as 'category', `".$assets->table_fields["type"]."` as 'type', `".$assets->table_fields["store"]."` as 'store' ";
						
						$assets->class_settings["where"] = " AND `".$assets->table_fields["store"]."` IN (". implode(",", $st2 ) .") AND `".$assets->table_fields["status"]."` != 'sold' ";
						
						$assets->class_settings["group"] = " GROUP BY `".$assets->table_fields["category"]."` , `".$assets->table_fields["type"]."`, `".$assets->table_fields["store"]."` ";
						
						$ed["assets"] = $assets->assets();
						
					}
				}else{
					$error_msg = 'Invalid Selection';
				}
			break;
			case "get_stores_distribution_fixed_assets_count":
				$assets = new cAssets();
				
				$this->class_settings["overide_select"] = " `".$this->table_fields["geo_code"]."` as 'geo_code', ( SELECT COUNT(*) FROM `".$this->class_settings["database_name"]."`.`".$assets->table_name."` WHERE `".$assets->table_fields["store"]."` = `".$this->table_name."`.`id` AND `".$assets->table_name."`.`record_status` = '1' AND `".$assets->table_fields["status"]."` != 'sold' ) as 'count' ";
				
				$this->class_settings["where"] = " AND ( `".$this->table_fields["geo_code"]."` != '' OR `".$this->table_fields["geo_code"]."` IS NOT NULL ) ";
				
				$this->class_settings["group"] = " GROUP BY `id`, `".$this->table_fields["geo_code"]."` ";
				//$this->class_settings["group"] = " GROUP BY `".$this->table_fields["geo_code"]."` ";
				return $this->_get_records();
			break;
			}
			
			if( ! $error_msg ){
				$this->class_settings[ 'data' ] = $ed;
				$this->class_settings[ 'data' ][ "html_replacement_selector" ] = $container1;
				
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
					
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => $container,
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js
				);
			}
			
			if( ! $error_msg ){
				$error_msg = 'Unknown Error';
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
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
			break;
			}
			
			$this->class_settings['form_extra_options']['modal'] = 1;
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';

			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		public function _get_the_stores( $opt = array() ){
			$this->class_settings["action_to_perform"] = 'get_active_store';
			
			$o = array( "where" => "" );
			$xparent = '';
			if( isset( $opt["type"] ) ){
				switch( $opt["type"] ){
				case "first_sub_store":
					$o["limit"] = " LIMIT 1 ";
					
					$o["where"] .= " AND `".$this->table_name."`.`".$this->table_fields["type"]."` = 'sub-store' ";
				break;
				case "sub_stores_id":
					$o["where"] .= " AND `".$this->table_name."`.`".$this->table_fields["type"]."` = 'sub-store' ";
				break;
				case "section_store":
					$subw = "";
					$tget = $_GET;
					$_GET = array();
					if( isset( $opt["parent"] ) && $opt["parent"] ){
						$subw = "( `".$this->table_name."`.`".$this->table_fields["type"]."` = 'sub-store' AND `".$this->table_name."`.`".$this->table_fields["parent"]."` = '". $opt["parent"] ."' )";
						
						$xparent = $opt["parent"];
						unset( $opt["parent"] );
					}
					
					if( $subw ){
						$subw = " OR " . $subw;
					}
					$o["where"] .= " AND ( `".$this->table_name."`.`".$this->table_fields["type"]."` = 'main-store' ". $subw ." ) ";
					
				break;
				}
			}
			
			if( isset( $opt["tab_key"] ) && $opt["tab_key"] ){
				$o["where"] .= " AND `".$this->table_name."`.`".$this->table_fields["section"]."` = '". $opt["tab_key"] ."' ";
			}
			if( isset( $opt["parent"] ) && $opt["parent"] ){
				$o["where"] .= " AND `".$this->table_name."`.`".$this->table_fields["parent"]."` = '". $opt["parent"] ."' ";
			}
			
			$r = $this->_get_select2( array( "params" => $o, "return" => 1 ) );
			
			if( isset( $opt["type"] ) ){
				switch( $opt["type"] ){
				case "first_sub_store":
					if( isset( $r[0]["id"] ) ){
						return $r[0];
					}
				break;
				case "section_store":
				
					$_GET = $tget;
					if( isset( $r[0]["parent"] ) ){
						$second_choice = '';
						foreach( $r as $rv ){
							if( $xparent == $rv["parent"] ){
								return $rv["id"];
							}else if( $rv["type"] == 'main-store' ){
								$second_choice = $rv["id"];
							}
						}
						//print_r( $r ); exit;
						return $second_choice;
					}
				break;
				case "sub_stores_id":
					if( isset( $r[0]["id"] ) ){
						return array_column( $r, 'id');
					}
				break;
				}
			}
		}
		
		public function _get_select2( $opt = array() ){
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$where = '';
			$limit = '';
			$search_term = '';
			$select = "";
			$select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num` ";
			
			if( isset( $opt["params"]["where"] ) && $opt["params"]["where"] ){
				$where = $opt["params"]["where"];
			}else{
				if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
					$search_term = trim( $_POST["term"] );
					
					$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` LIKE '%".$search_term."%' ) ";
					
				}else{
					if( ! ( isset( $opt["no_limit"] ) && $opt["no_limit"] ) ){
						$limit = "LIMIT 0, " . get_default_select2_limit();
					}
				}
				

				$branch = isset( $_POST[ $this->table_fields[ 'type' ] ] ) ? $_POST[ $this->table_fields[ 'type' ] ] : '';
				$twhere = '';

				switch( $branch ){
				case 'sub-store':
					$twhere = 'main-store';
				break;
				case 'main-store':
					$twhere = 'branch';
				break;
				case 'branch':
					$twhere = '';
				break;
				}
				
				if( $twhere ){
					$where .= " AND `".$this->table_name."`.`".$this->table_fields["type"]."` = '". $twhere ."' ";
				}
			}
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "name":
					$select .= ", `".$this->table_name."`.`".$val."` as 'text' ";
				break;
				case "type":
				case "parent":
					$select .= ", `".$this->table_name."`.`".$val."` as '".$key."' ";
				break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$this->table_name."`.`".$val."` = '".$_GET[ $key ]."' ";
				}
			}
			
			switch( $action_to_perform ){
			case "get_active_store":
				$access = get_accessed_functions();
				$super = 0;
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
				
				//$where .= " AND `".$this->table_name."`.`".$this->table_fields["status"]."` = 'active' ";
				if( $super || isset( $access["status"]["stores"]["access_all_stores"] ) ){
					
				}else{
					$priv_id = $this->class_settings["priv_id"];
					
					$ac = new cAccess_roles();
					$this->class_settings["join"] = " JOIN `".$this->class_settings["database_name"]."`.`".$ac->table_name."` ON `".$ac->table_name."`.`id` = '".$priv_id."' AND `".$ac->table_name."`.`".$ac->table_fields["data"]."` REGEXP `".$this->table_name."`.`id` ";
					
				}
			break;
			}
			
			if( isset( $opt["params"]["limit"] ) && $opt["params"]["limit"] ){
				$limit = $opt["params"]["limit"];
			}
			
			if( isset( $_GET[ "parent" ] ) && $_GET[ "parent" ] && isset( $_GET[ "include_parent" ] ) && $_GET[ "include_parent" ] ){
				$ex = explode( " AND ", $where );
				unset( $ex[0] );
				$where = " AND ( `".$this->table_name."`.`id` = '".$_GET[ "parent" ]."' OR ( ". implode(" AND ", $ex ) ." ) ) ";
			}
			
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			$sqlx = $this->_get_records();
			// switch ($action_to_perform) {
			// 	case 'get_select2_special':
			// 		if( $_POST['term'] ){
			// 			$sqlx[] = array( 'id' => md5( $_POST['term'] ), 'text' => $_POST['term'] );
			// 		}
			// 	break;
			// }					   
			
			if( isset( $opt["return"] ) && $opt["return"] ){
				return $sqlx;
			}
			
			if( isset( $GLOBALS["access"]["my_store"] ) && $GLOBALS["access"]["my_store"] ){
				$GLOBALS["user_cert"]["store"] = $GLOBALS["access"]["my_store"];
			}
			if( isset( $GLOBALS["user_cert"]["store"] ) && $GLOBALS["user_cert"]["store"] ){
				if( ! in_array( $GLOBALS["user_cert"]["store"] , array_column( $sqlx, "id" ) ) ){
					$sqlx[] = array(
						"serial_num" => $GLOBALS["user_cert"]["store"],
						"id" => $GLOBALS["user_cert"]["store"],
						"text" => get_name_of_referenced_record( array( "id" => $GLOBALS["user_cert"]["store"], "table" => "stores" ) ),
					);
				}
			}
			
			return array( "items" => $sqlx, "do_not_reload_table" => 1 );
		}
		
		private function _move_all_transactions_to_store(){
			$error_msg = '';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg = '<h4>Invalid Store / Location</h4>Please select a valid store / location';
			}
			
			if( ! $error_msg ){
				$store = $_POST["id"];
				$stores = $this->_get_stores();
				
				$key = "name";
				$package = HYELLA_PACKAGE;
				switch( $package ){
				case "farm":
				case "property":
				break;
				case "hotel":
					$key = "address";
				break;
				}
				
				$store_name = '';
				$updated = array();
				
				if( isset( $stores[ $store ][ $key ] ) && $stores[ $store ][ $key ] ){
					$store_id = $store;
					$store_name = $stores[ $store ][ $key ];
					
					$loop = array();
					
					$loop["sales"] = new cSales();
					$loop["inventory"] = new cInventory();
					$loop["expenditure"] = new cExpenditure();
					$loop["debit_and_credit"] = new cDebit_and_credit();
					$loop["transactions"] = new cTransactions();
					$loop["category"] = new cCategory();
					$loop["production"] = new cProduction();
					$loop["pay_roll"] = new cPay_row();
					
					foreach( $loop as $k => $v ){
						if( isset( $v->table_fields ) && isset( $v->table_name ) ){
							
							if( isset( $v->table_fields["store"] ) && $v->table_fields["store"] ){
								
								$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$v->table_name."` SET  `".$v->table_name."`.`".$v->table_fields["store"]."` = '". $store_id ."' WHERE `".$v->table_name."`.`record_status` = '1' ";
								
								//echo $query; exit;
								
								$query_settings = array(
									'database' => $this->class_settings['database_name'] ,
									'connect' => $this->class_settings['database_connection'] ,
									'query' => $query,
									'query_type' => 'UPDATE',
									'set_memcache' => 1,
									'tables' => array( $v->table_name ),
								);
								execute_sql_query($query_settings);
								
								$updated[] = ( isset( $v->label )?( $v->label . ' ' ):'' ) . ': ' . $v->table_name;
							}
							
						}
					}
					
					
					$err = new cError('010011');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>All transactions has been moved to <strong>'.$store_name.'</strong></h4><ol><li>'. implode( '</li><li>', $updated ) .'</li></ol>';
					return $err->error();
				}else{
					$error_msg = '<h4>Unable to Retrieve Store</h4>Please try again';
				}
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = $error_msg;
			return $err->error();
		}
		
		private function _get_i_menu_stores(){
			$stores = $this->_get_all_stores();
			
			//check for vat, service charge & service tax
			$surcharge = get_general_settings_value_array( 
				array( 
					"key" => array(
						"VAT" => 0,
						"SERVICE CHARGE" => 0,
						"SERVICE TAX" => 0,
						"SHOW CUSTOMER CHANGE DURING PAYMENT" => 0,
					),
					"table" => "sales" 
				)
			);
			
			$tables = '';
			$i_stores = array();
			foreach( $stores as & $sval ){
				if( isset( $sval["store_options"] ) && $sval["store_options"] ){
					$opt = json_decode( $sval["store_options"], true );
					
					if( isset( $opt["display_text"] ) && $opt["display_text"] ){
						$sval["text"] = $opt["display_text"];
					}
					if( isset( $opt["show_in_imenu"] ) && $opt["show_in_imenu"] == "yes" ){
						unset( $sval["store_options"] );
						//print_r($opt);
					//print_r($sval); exit;
						if( isset( $opt["first_table_number"] ) && $opt["first_table_number"] && isset( $opt["last_table_number"] ) && $opt["last_table_number"] ){
							$start = intval( $opt["first_table_number"] );
							$end = intval( $opt["last_table_number"] );
							
							$prefix = '';
							if( isset( $opt["table_number_prefix"] ) && $opt["table_number_prefix"] ){
								$prefix = strtoupper( $opt["table_number_prefix"] );
							}
							
							if( $end >= $start  ){
							$tables .= '<optgroup label="'.$sval["text"].'">';
								for( $i = $start; $i <= $end; $i++ ){
									 $tables .= '<option value="'.$sval["text"].' '.$prefix.$i.'">'.$prefix.$i.'</option>';
								}
							$tables .= '</optgroup>';
							}
						}
						
						$i_stores[ $sval["id"] ] = array_merge( $opt, $sval );
					}
				}
			}
			
			
			$a["vat"] = $surcharge["VAT"];
			$a["service_charge"] = $surcharge["SERVICE CHARGE"];
			$a["service_tax"] = $surcharge["SERVICE TAX"];
			
			$data["tables"] = $tables;
			$data["stores"] = $i_stores;
			$data["surcharge"] = $a;
			
			if( defined("HYELLA_I_MENU_ORDER") && HYELLA_I_MENU_ORDER ){
				$data["hide_payment"] = 1;
				$data["hide_customer"] = 1;
				$data["compulsory_table"] = 1;
				$data["server_action"] = 'place-order';
			}
			
			if( defined("HYELLA_SPECIAL_POS_OPTION_LABEL") && HYELLA_SPECIAL_POS_OPTION_LABEL && defined("HYELLA_SPECIAL_POS_OPTION") && HYELLA_SPECIAL_POS_OPTION ){
				
				$sp_opt = explode(";", HYELLA_SPECIAL_POS_OPTION );
				if( ! empty( $sp_opt ) ){
					$data["pos_sp_option_label"] = HYELLA_SPECIAL_POS_OPTION_LABEL;
					$data["pos_sp_option"] = '';
					
					foreach( $sp_opt as $v ){
						$data["pos_sp_option"] .= '<option value="'.strtoupper( $v ).'">'. $v .'</option>';
					}
					
					if( defined("HYELLA_SPECIAL_POS_OPTION_REQUIRED") && "HYELLA_SPECIAL_POS_OPTION_REQUIRED" ){
						$data["pos_sp_option_required"] = 1;
					}
				}
				
			}
			
			if( isset( $data["compulsory_table"] ) && $data["compulsory_table"] ){
				$data["tables"] = '<option value="">--select table--</option>' . $data["tables"];
			}
			
			/********Payment Methods********/
			$payment_method = get_payment_method();
			$pm = get_payment_method();
			$default_pm = get_defualt_bank_account_for_financial_transactions();
			$default_pm_text = '';
			if( isset( $pm[ $default_pm ] ) ){
				$default_pm_text = $pm[ $default_pm ];
				unset( $pm[ $default_pm ] );
			}
			asort( $pm );
			if( $default_pm_text ){
				$payment_method = array_merge( array( $default_pm => $default_pm_text ), $pm );
			}
			$data["payment_method"] = $payment_method;
			/********Payment Methods********/
			
			return array( 
				"status" => "new-status",
				"data" => $data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'javascript_functions' => array( "$.fn.hyellaIMenu.displayStores" ),
			);
		}
		
		private function _change_store(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Store / Location</h4>Please select a valid store / location';
				return $err->error();
			}
			
			//20-jun-23: 2echo
			$site = isset( $_GET["site"] )?$_GET["site"]:'';
			$add_sel = isset( $_GET["add_sel"] )?$_GET["add_sel"]:'';
			$js_callback = isset( $_GET["callback_js"] )?$_GET["callback_js"]:'';
			$set_tab = 1;
			
			$key = "name";
			$package = HYELLA_PACKAGE;
			switch( $package ){
			case "farm":
			case "property":
			break;
			case "hotel":
				$key = "address";
			break;
			}
			
			$store = $_POST["id"];
			//$stores = $this->_get_stores();
			//20-jun-23: 2echo
			if( $site ){
				$stores = array( "id" => $store );
				$key = "id";
			}else{
				$this->class_settings["current_record_id"] = $store;
				$stores = $this->_get_record();
			}
			
			//if( isset( $stores[ $store ][ $key ] ) && $stores[ $store ][ $key ] ){
			if( isset( $stores["id"] ) && $stores["id"] ){
				//20-jun-23: 2echo
				if( ! $site ){
					$_SESSION[ "store" ] = $store;
					$_SESSION[ "tmp_branch" ] = $store;
				}
				
				if( $add_sel ){
					//20-jun-23: 2echo
					if( $site ){
						$_SESSION["ucert"]["_sel_site"] = $add_sel;
					}else{
						$_SESSION["ucert"]["_sel_store"] = $add_sel;
					}
					$set_tab = 0;
					
					//force notification
					$nb = 'cNwp_client_notification';
					if( class_exists( $nb ) ){
						$nb = new $nb();
						$nb->force_client_notification( $this->table_name );
					}
				}else{
					//force notification
					$nb = 'cNwp_client_notification';
					if( class_exists( $nb ) ){
						$nb = new $nb();
						$nb->force_client_notification( $this->table_name . '-' . $this->class_settings["action_to_perform"] );
					}
				}
				
				$msg = '<h4>Loading <strong>'.$stores[ $key ].'</strong></h4>Please wait...';
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'change_store_backend':
					$msg = '<h4>Successfully Loaded <strong>'.$stores[ $key ].'</strong></h4>';
				break;
				default:
				break;
				}
				
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = $msg;
				$return = $err->error();
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'change_store_backend':
					if( $js_callback ){
						$return["javascript_functions"] = array( $js_callback );
					}
				break;
				default:
					$return["redirect_url"] = "main.html";
					if( defined( "HYELLA_DEFAULT_LOCATION" ) ){
						$return["redirect_url"] = HYELLA_DEFAULT_LOCATION;
					}
					
					//20-jun-23: 2echo
					if( $site ){
						switch( $stores["id"] ){
						case "home":
							$return["status"] = 'reload-page';
							unset( $return["redirect_url"] );
						break;
						case "admin-tab":
							if( ( defined("NWP_SHOW_ADMIN_SELECTION_OPTION") && NWP_SHOW_ADMIN_SELECTION_OPTION ) ){
								$return["redirect_url"] = NWP_SHOW_ADMIN_SELECTION_OPTION;
							}
						break;
						}
					}
					
					if( $set_tab ){
						$a["type"] = 'set';
						$a["callback"] = array(
							'active_tab' => array(
								'tab_key' => 'operations',
							),
						);
						nwp_reload_callback( $a );
					}
					
					
					/* 
					$loc = __map_store_locations();
					if( isset( $loc[ $store ][ "file" ] ) && $loc[ $store ][ "file" ] ){
						$return["redirect_url"] = $loc[ $store ][ "file" ];
					} */
				break;
				}
				
				
				return $return;
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = '_resend_verification_email';
			$err->additional_details_of_error = '<h4>Missing Information</h4>The selected store / location could not be found';
			return $err->error();
		}
		
		private function _get_stores_list(){
			$js = '$.fn.pHost.loadShoppingCart';
			$current_tab = isset( $_GET["tab_key"] )?$_GET["tab_key"]:'';
			$params = '';
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "property":
					$js = '$.fn.pHost.loadPropertyView';
				break;
				}
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_stores_list4':
				$js = '$.fn.pHost.loadTransferStock';
			break;
			case 'get_stores_list3':
				$js = '$.fn.pHost.loadStock';
			break;
			case 'get_stores_list2':	
				$js = '$.fn.pHost.viewRoomsReport';
				//$js = '$.fn.pHost.loadMakeReservation';
			break;
			}
			
			if( isset( $_POST["callback_action"] ) && $_POST["callback_action"] ){
				$js = $_POST["callback_action"];
			}
			
			if( get_single_store_settings() ){
				$access = 1;
			}else{
				$all_stores = get_accessible_stores( array(), $this->class_settings["priv_id"] );
				
				$access = 0;
				if( defined("HYELLA_DEFAULT_STORE") && isset( $all_stores[ HYELLA_DEFAULT_STORE ] ) ){
					$this->class_settings[ 'data' ]['selected_store'] = HYELLA_DEFAULT_STORE;
					if( ! isset( $_SESSION[ "store" ] ) )$_SESSION[ "store" ] = HYELLA_DEFAULT_STORE;
				}
				//print_r( $_SESSION[ "store" ] ); exit;
				if( ! empty( $all_stores ) ){
					if( ! isset( $_SESSION[ "store" ] ) ){
						foreach( $all_stores as $key => $val ){
							$_SESSION[ "store" ] = $key;
							break;
						}
					}
					$access = 1;
				}
				
				$this->class_settings[ 'data' ]['stores'] = $all_stores;
			}
			
			if( $access == 1 ){
				$cstore = get_current_store( 1 );
				$this->class_settings[ 'data' ]['selected_store'] = $cstore;
				
				if( defined( "HYELLA_PACKAGE" ) ){
					$this->class_settings[ 'data' ][ "package" ] = HYELLA_PACKAGE;
				}
				
				if( defined( "HYELLA_MAIN_STORE" ) ){
					$this->class_settings[ 'data' ][ "main_store" ] = HYELLA_MAIN_STORE;
				}
				
				if( $current_tab ){
					$ft = frontend_tabs();
					//print_r( $current_tab ); exit;
					//print_r( $ft ); exit;
					
					$params .= '&current_tab=' . $current_tab;
					$params2 = '';
					$has_sub = 0;
					if( isset( $ft[ $current_tab ]["store_filter"] ) && ! empty( $ft[ $current_tab ]["store_filter"] ) ){
						foreach( $ft[ $current_tab ]["store_filter"] as $stk => $stv ){
							switch( $stk ){
							case "sub_store":
								$params2 .= '&type=sub-store';
								$has_sub = 1;
							break;
							case "tab_store":
								$params2 .= '&section=' . $current_tab;
							break;
							case "all":
								$has_sub = 2;
							break;
							}
						}
					}
					
					//print_r( $has_sub ); exit;
					if( ! $has_sub ){
						$params2 .= '&type=main-store';
					}else if( $has_sub == 2 ){
						$params2 = '';
					}
					$params .= $params2;
				}
				
				if( $params ){
					$this->class_settings[ 'data' ][ "params" ] = $params;
				}
				
				$store_loc = 'main.html';
				
				if( defined( "HYELLA_DEFAULT_LOCATION" ) ){
					$store_loc = HYELLA_DEFAULT_LOCATION;
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/display-app-store-select' );
				$returning_html_data = $this->_get_html_view();
				
				return array(
					'html_replacement_selector' => "#current-store-container",
					'html_replacement' => $returning_html_data,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( $js ) 
				);
			}
			
			//redirect to no store page
			return array(
				'redirect_url' => "no-access.html",
				'status' => 'new-status',
			);
		}
		
		private function _display_app_view2(){
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
		}
		
		private function _get_all_stores(){
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$where = '';
			$sel_store = '';
			if( isset( $this->class_settings["selected_store"] ) && $this->class_settings["selected_store"] ){
				$where = " AND `id` = '" . $this->class_settings["selected_store"] . "'";
			}
			
			$select .= ", UCASE( `".$this->table_name."`.`".$this->table_fields["address"]."` ) as 'text' ";
			
			$query = "SELECT ".$select." FROM `".$this->class_settings['database_name']."`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status` = '1' ". $where ." ORDER BY `".$this->table_name."`.`".$this->table_fields["name"]."` ";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			return $sql_result;
		}
		
		protected function _display_all_records_full_view2(){
			
			$show_form = 1;
			$hide_title = isset( $_GET[ 'hide_title' ] ) ? $_GET[ 'hide_title' ] : '';
			$quick_manage = isset( $_GET[ 'quick_manage' ] ) ? $_GET[ 'quick_manage' ] : '';
			$disable_btn = isset( $_GET[ 'disable_edit_buttons' ] ) ? $_GET[ 'disable_edit_buttons' ] : '';
			
			
			if( $hide_title ){
				$this->class_settings[ "hide_title" ] = 1;
			}
			
			if( $quick_manage ){
				$show_form = 0;
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;
				unset( $this->basic_data['more_actions'] );
			}
			
			if( $show_form ){
				$move_all_transactions_to_store = 1;
				
				if( ! get_disable_access_control_settings() ){
					$move_all_transactions_to_store = 0;
					
					$access = get_accessed_functions( 0 );
					if( ( ! is_array( $access ) && $access ) ){
						$move_all_transactions_to_store = 1;
					}
				}
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
				$this->class_settings[ 'data' ][ 'move_all_transactions_to_store' ] = $move_all_transactions_to_store;
				$this->datatable_settings['custom_edit_button'] = $this->_get_html_view();
				
				if( defined( "HYELLA_PACKAGE" ) ){
					switch( HYELLA_PACKAGE ){
					case "property":
						$this->datatable_settings['show_add_new'] = 1;
					break;
					}
				}
			
			}
			
			if( get_single_store_settings() ){
				$this->datatable_settings['show_add_new'] = 0;
				$this->datatable_settings['show_delete_button'] = 0;
			}
			
			if( get_hyella_development_mode() ){
				$this->datatable_settings['show_delete_button'] = 1;
			}
			
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
			return $this->_display_all_records_full_view();
		}

		protected function _display_all_records_full_view2OLD(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$datatable = $this->_display_data_table();
			$form = $this->_generate_new_data_capture_form();
			
			$this->class_settings[ 'data' ]['data_entry_form'] = $form['html'];
			$this->class_settings[ 'data' ]['html'] = $datatable['html'];
			
			$this->class_settings[ 'data' ]['title'] = "Manage " . $this->label;
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "property":
					$this->class_settings[ 'data' ]['title'] = "Manage Property Locations";
				break;
				}
			}
			
			$this->class_settings[ 'data' ]['hide_main_title'] = 1;
			
			$this->class_settings[ 'data' ]['hide_clear_tab'] = 1;
			//$this->class_settings[ 'data' ]['hide_details_tab'] = 1;
			$this->class_settings[ 'data' ]['hide_reports_tab'] = 1;
			
			$this->class_settings[ 'data' ]['col_1'] = 3;
			$this->class_settings[ 'data' ]['col_2'] = 9;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'recreateDataTables', 'set_function_click_event', 'update_column_view_state', 'prepare_new_record_form_new' ) 
			);
		}
		
		//BASED METHODS
		private function _generate_new_data_capture_form2(){
			$returning_html_data = array();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit':
				$this->class_settings['form_heading_title'] = 'Modify ' . $this->label;
				//$this->class_settings['hidden_records'][ $this->table_fields["name"] ] = 1;
			break;
			default:
				$this->class_settings['form_heading_title'] = 'New ' . $this->label;
			break;
			}
			
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "property":
					$this->class_settings['form_heading_title'] = 'New Location';
					switch ( $this->class_settings['action_to_perform'] ){
					case 'edit':
						$this->class_settings['form_heading_title'] = 'Modify Location';
						unset( $this->class_settings['hidden_records'][ $this->table_fields["name"] ] );
					break;
					}
					
					$this->class_settings['hidden_records'][ $this->table_fields["address"] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields["phone"] ] = 1;
					
					if( isset( $_POST["id"] ) && get_main_store() == $_POST["id"] ){
						unset( $this->class_settings['hidden_records'][ $this->table_fields["address"] ] );
						unset( $this->class_settings['hidden_records'][ $this->table_fields["phone"] ] );
					}
				break;
				}
			}
				
			$this->class_settings['form_submit_button'] = 'Save Changes &rarr;';
			
			if( ! isset( $this->class_settings['form_class'] ) )
				$this->class_settings['form_class'] = 'activate-ajax';
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			
			if( ! isset( $process_handler->class_settings[ 'form_action_todo' ] ) )
				$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
				'record_id' => isset( $returning_html_data[ 'record_id' ] )?$returning_html_data[ 'record_id' ]:"",
			);
		}

		private function _delete_records2(){
			$returning_html_data = array();
			
			$main_store = get_main_store();
			
			if( isset( $_POST["id"] ) && $_POST["id"] == $main_store ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Restricted Access</h4><p>You cannot delete the CENTRAL STORE</p>';
				return $err->error();
			}
			
			$main_store = get_administrative_store();
			if( isset( $_POST["id"] ) && $_POST["id"] == $main_store ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Restricted Access</h4><p>You cannot delete the AMINISTRATIVE STORE</p>';
				return $err->error();
			}
			
			
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
				$this->_get_stores();
			}
			return $returning_html_data;
		}
		
		protected function __after_save_changes( $e = array() ){
			if( isset( $e['saved_record_id'] ) && $e['saved_record_id'] ){
				
				if( isset( $e['new_record_created'] ) && $e['new_record_created'] ){
					$query = "UPDATE `".$this->class_settings['database_name']."`.`".$this->table_name."` SET `".$this->table_name."`.`id` = 100 + `".$this->table_name."`.`serial_num` WHERE `id` = '".$e['saved_record_id']. "' ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
				}
			}
		}
		
		/* 
		protected function __after_save_changes_and_refresh( $e = array() ){
			if( isset( $e['saved_record_id'] ) && $e['saved_record_id'] ){
				$this->_update_access_control_functions_list( $e['saved_record_id'] );
				$this->_get_stores();
			}
		}
		 */
		private function _update_access_control_functions_list( $store = '' ){
			
			$f = new cFunctions();
			$f->class_settings = $this->class_settings;
			$f->class_settings["selected_store"] = $store;
			$f->class_settings["action_to_perform"] = 'rebuild_stores_submenu';
			$f->functions();
			
		}
		
		private function _get_stores(){
			
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
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' ORDER BY `".$this->table_fields["name"]."`";
			
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
				$package = "";
				$store_name = "";
				if( defined( "HYELLA_PACKAGE" ) )$package = HYELLA_PACKAGE;
				if( defined( "HYELLA_MAIN_STORE" ) )$store_name = HYELLA_MAIN_STORE;
				
				switch( $package ){
				case "hotel":
					foreach( $all_departments as $category ){
						$departments[ $category[ 'id' ] ] = $category;
						
						if( $category['name'] == "." )$category['address'] = $store_name;
						$departments[ 'all' ][ $category[ 'id' ] ] =  $category['address'];
						
						$settings = array(
							'cache_key' => $cache_key.'-'.$category[ 'id' ],
							'cache_values' => $category,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						
						set_cache_for_special_values( $settings );
					}
				break;
				case "property":
					foreach( $all_departments as $category ){
						$departments[ 'all' ][ $category[ 'id' ] ] = $category['name'] . " ( ".$category['email']." )";
						$departments[ $category[ 'id' ] ] = $category;
						
						$settings = array(
							'cache_key' => $cache_key.'-'.$category[ 'id' ],
							'cache_values' => $category,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						
						set_cache_for_special_values( $settings );
					}
				break;
				case "accounting":
				case "hospital":
				case "farm":
					foreach( $all_departments as $category ){
						$departments[ 'all' ][ $category[ 'id' ] ] = $category['name'];
						$departments[ $category[ 'id' ] ] = $category;
						
						$settings = array(
							'cache_key' => $cache_key.'-'.$category[ 'id' ],
							'cache_values' => $category,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						
						set_cache_for_special_values( $settings );
					}
				break;
				default:
					foreach( $all_departments as $category ){
						$departments[ 'all' ][ $category[ 'id' ] ] = $category['name'] . " ( ".$category['address']." )";
						$departments[ $category[ 'id' ] ] = $category;
						
						$settings = array(
							'cache_key' => $cache_key.'-'.$category[ 'id' ],
							'cache_values' => $category,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						
						set_cache_for_special_values( $settings );
					}
				break;
				}
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key,
					'cache_values' => $departments,
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				
				if( ! set_cache_for_special_values( $settings ) ){
					//report cache failure message
				}
				
				return $departments;
			}
		}
		
		private function _reset_members_cache( $record , $clear = 0 ){
			$cache_key = $this->table_name;
			
			$settings = array(
				'cache_key' => $cache_key.'-stores-',//.$record["member_id"],
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
					'cache_key' => $cache_key.'-stores-'.$record["member_id"],
					'directory_name' => $cache_key,
					'cache_values' => $members,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				return $members;
			}
		}
	}
?>
<?php
	/**
	 * parent_data Class
	 *
	 * @used in  				parent_data Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	parent_data
	 */

	/*
	|--------------------------------------------------------------------------
	| parent_data Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cParent_data extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'parent_data';
		public $table_name_static = 'production';
		public $table_name_sub = 'parent_data_items';
		
		public $customer_source = 'parent_data';
		public $default_reference = "";
		
		public $label = 'parent_data';
		
		private $associated_cache_keys = array(
			'parent_data',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'date' => 'parent_data001',
			'quantity' => 'parent_data002',
			'cost' => 'parent_data003',
			'extra_cost' => 'parent_data004',
			
			'factory' => 'parent_data005',
			'store' => 'parent_data006',
			
			'comment' => 'parent_data007',
			'status' => 'parent_data008',
			
			'staff_responsible' => 'parent_data009',
			'reference' => 'parent_data010',
			'reference_table' => 'parent_data011',
			'customer' => 'parent_data012',
			
		);
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 0,		//Determines whether or not to show edit button
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
			
		function __construct(){
			
		}
	
		function parent_data(){
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
			case 'refresh_parent_data_info':
				$returned_value = $this->_refresh_parent_data_info();
			break;
			case 'save_parent_data_and_return_receipt':
				$returned_value = $this->_save_parent_data_and_return_receipt();
			break;
			case 'update_parent_data_status':
				$returned_value = $this->_update_parent_data_status();
			break;
			case 'delete_parent_data_manifest':
				$returned_value = $this->_delete_parent_data_manifest();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'search_sales_invoice_picking_slips':
				$returned_value = $this->_search_sales_invoice_picking_slips();
			break;
			case 'get_transfer_slips':
			case 'get_direct_deductions':
			case 'get_picking_slips':
				$returned_value = $this->_get_picking_slips();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _get_picking_slips(){
			if( ! ( isset( $this->class_settings["reference_table"] ) && $this->class_settings["reference_table"] ) )return 0;
			
			
			switch( $this->table_name ){
			case "stock_request":
				$select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`created_by`, `".$this->table_name."`.`".$this->table_fields["date"]."` as 'date', `".$this->table_name."`.`".$this->table_fields["staff_responsible"]."` as 'staff_responsible', `".$this->table_name."`.`".$this->table_fields["description"]."` as 'description', `".$this->table_name."`.`".$this->table_fields["comment"]."` as 'comment', `".$this->table_name."`.`".$this->table_fields["store"]."` as 'store' ";
				
				$reference = "parent_id";
			break;
			default:
				$reference = "parent_data_id";
				$select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`created_by`, `".$this->table_name."`.`".$this->table_fields["date"]."` as 'date', `".$this->table_name."`.`".$this->table_fields["staff_responsible"]."` as 'staff_responsible', `".$this->table_name."`.`".$this->table_fields["quantity"]."` as 'quantity', `".$this->table_name."`.`".$this->table_fields["comment"]."` as 'comment' ";
			break;
			}
			
			switch( $this->class_settings["reference_table"] ){
			case "sales":
				//get previous picking slips
				$produced_items = new cparent_data_items();
				$select .= ", `".$produced_items->table_name."`.`".$produced_items->table_fields["item_id"]."` as 'item_id', `".$produced_items->table_name."`.`".$produced_items->table_fields["quantity"]."` as 'unit_quantity' ";
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` LEFT JOIN `" . $this->class_settings['database_name'] . "`.`".$produced_items->table_name."` ON `".$produced_items->table_name."`.`".$produced_items->table_fields[ $reference ]."` = `".$this->table_name."`.`id` WHERE `".$this->table_name."`.`record_status`='1' AND `".$produced_items->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["reference"]."` = '".$this->class_settings["reference"]."' AND `".$this->table_name."`.`".$this->table_fields["reference_table"]."` = '".$this->class_settings["reference_table"]."' GROUP BY `".$produced_items->table_name."`.`id` ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC ";
			break;
			case "cart":
				$table = $this->table_name_sub;
				$actual_name_of_class = 'c'.ucwords( $table );
				$produced_items = new $actual_name_of_class();
				
				$select .= ", ( SELECT SUM( `".$produced_items->table_name."`.`".$produced_items->table_fields["quantity"]."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$produced_items->table_name."` WHERE `".$produced_items->table_name."`.`".$produced_items->table_fields[ $reference ]."` = `".$this->table_name."`.`id` AND `".$produced_items->table_name."`.`record_status`='1' ) as 'quantity_picked', ( SELECT SUM( `".$produced_items->table_name."`.`".$produced_items->table_fields["quantity_instock"]."` ) FROM `" . $this->class_settings['database_name'] . "`.`".$produced_items->table_name."` WHERE `".$produced_items->table_name."`.`".$produced_items->table_fields[ $reference ]."` = `".$this->table_name."`.`id` AND `".$produced_items->table_name."`.`record_status`='1' ) as 'quantity_instock' ";
				
				$status = "'materials-utilized', 'damaged-materials', 'direct-deductions'";
				
				switch( $this->class_settings["action_to_perform"] ){					
				case 'get_transfer_slips':
					$status = "'materials-transfer'";
				break;
				case 'get_direct_deductions':
					$status = "'direct-deductions'";
				break;
				case 'get_picking_slips':
					$status = "'materials-utilized'";
				break;
				case 'get_stock_request_slips':
					$status = "'stock-request'";
				break;
				}
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`".$this->table_fields["status"]."` IN ( ".$status." ) ORDER BY `".$this->table_name."`.`".$this->table_fields["date"]."` DESC LIMIT 10 ";
			break;
			default:
				$where = '';
				if( isset( $this->class_settings["where"] ) && $this->class_settings["where"] )
					$where = $this->class_settings["where"];
				
				$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status`='1' " . $where;
			break;
			}
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			return execute_sql_query($query_settings);
		}
			
		protected function _search_sales_invoice_picking_slips(){
			if( ! ( isset( $this->class_settings["reference_table"] ) && $this->class_settings["reference_table"] ) )return 0;
			if( ! ( isset( $this->class_settings["reference"] ) && $this->class_settings["reference"] ) )return 0;
			if( ! ( isset( $this->class_settings["sales_info"] ) && $this->class_settings["sales_info"] ) )return 0;
			
			$all_data = $this->_get_picking_slips();
			$returning_html_data1 = "";
			
			if( isset( $all_data[0]["id"] ) && $all_data[0]["id"] ){
				$this->class_settings[ 'data' ][ "picking_slips" ] = $all_data;
			}
			
			if( isset( $this->class_settings["update_bank_transaction"] ) && $this->class_settings["update_bank_transaction"] ){
				$this->class_settings[ 'data' ]['update_bank_transaction'] = $this->class_settings["update_bank_transaction"];
			}
			
			$this->class_settings[ 'data' ]['staff_responsible'] = get_employees();
			$this->class_settings[ 'data' ]["sales_info"] = $this->class_settings["sales_info"];
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/display-new-picking-slip' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		protected function _record_in_accounting(){
			
			if( ! ( isset( $this->class_settings["current_record_id"] ) && $this->class_settings["current_record_id"] ) )return 0;
			
			$this->class_settings["parent_data_id"] = $this->class_settings["current_record_id"];
			$e = $this->_get_customer_call_log();
			
			$parent_data_items = new cparent_data_items();
			$parent_data_items->class_settings = $this->class_settings;
			$parent_data_items->class_settings["action_to_perform"] = "get_specific_parent_data_items";
			$i = $parent_data_items->parent_data_items();
			
			$items_categories = array();
			
			$cost = 0;
			if( is_array( $i ) && ! empty( $i ) ){			
				foreach( $i as $items ){
					$item_details = get_items_details( array( "id" => $items["item_id"] ) );
					if( isset( $item_details["category"] ) ){
						if( ! isset( $items_categories[ $item_details["category"] ] ) ){
							$items_categories[ $item_details["category"] ] = 0;
						}
						$items_categories[ $item_details["category"] ] += $items["quantity"] * $items["cost"];
					}
					$cost += $items["quantity"] * $items["cost"];
				}
			}
			
			if( ! $cost )return 0;
			
			$dc = array();
			
			$rv = __map_financial_accounts();
			//$expense_account = $rv[ "used_goods" ];
			$expense_account = $rv[ "cost_of_goods_sold" ];
			$expense_account_type = $rv[ "cost_of_goods_sold" ]; //$rv[ "operating_expense" ];
			
			$inventory_account = $rv[ "inventory" ];
			
			$desc = "Used Goods ";
			switch( $e["status"] ){
			case "direct-deductions":
				return 1;
			break;
			case 'damaged-materials':
				$expense_account = $rv[ "damaged_goods" ];
				$expense_account_type = $rv[ "damaged_goods" ];
				$desc = "Damaged Goods #".mask_serial_number( $e["serial_num"], 'IN' ) . "";
			break;
			case 'sales-order':
				$expense_account = $rv[ "cost_of_goods_sold" ];
				$expense_account_type = $rv[ "cost_of_goods_sold" ];
				
				$si = get_sales_details( array( "id" => $e["reference"] ) );
				$s_ref = '';
				if( isset( $si["serial_num"] ) )$s_ref = mask_serial_number( $si["serial_num"], 'S' );
				$desc = "Cost of Sales #".mask_serial_number( $e["serial_num"], 'IN' ) . " for sales #" . $s_ref . " ";
			break;
			case 'materials-transfer':
				$desc = "Material Transfer #".mask_serial_number( $e["serial_num"], 'IN' ) . " ";
			break;
			default:
				$desc = "Material Used #".mask_serial_number( $e["serial_num"], 'IN' ) . " ";
			break;
			}
			
			if( isset( $this->class_settings["parent_data"]["account"] ) && isset( $rv[ $this->class_settings["parent_data"]["account"] ] ) ){
				
				$expense_account = $rv[ $this->class_settings["parent_data"]["account"] ];
				$expense_account_type = $rv[ $this->class_settings["parent_data"]["account"] ];
				$desc = "Material Used for Marketing #".mask_serial_number( $e["serial_num"], 'IN' ) . " ";
			}
			
			$dc1 = array();
			
			if( ! empty( $items_categories ) ){
				foreach( $items_categories as $cat => $am ){
					$final = $am;
					
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "debit",
						"account_type" => $expense_account_type,
						"account_source" => "category",
						"extra_reference" => $e[ "id" ],
						"reference_table" => $this->table_name,
					);
					
					$dc[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "credit",
						"account_type" => $rv[ "inventory" ],
						"account_source" => "category",
						"extra_reference" => $e[ "id" ],
						"reference_table" => $this->table_name,
					);
					
					$dc1[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "credit",
						"account_type" => $rv[ "inventory" ],
						"account_source" => "category",
						"extra_reference" => $e[ "id" ],
						"reference_table" => $this->table_name,
					);
					
					$dc1[] = array(
						"transaction_id" => $e["id"],
						"account" => $cat,
						"amount" => $final,
						"type" => "debit",
						"account_type" => $rv[ "inventory" ],
						"account_source" => "category",
						"extra_reference" => $e[ "id" ],
						"reference_table" => $this->table_name,
						"store" => $e["factory"],
					);
				}
			}else{
				//debit operating expense
				$dc[] = array(
					"transaction_id" => $e["id"],
					"account" => $expense_account,
					"amount" => $cost,
					"type" => "debit",
					"account_type" => $expense_account_type,
					"extra_reference" => $e[ "id" ],
					"reference_table" => $this->table_name,
				);
					
				//credit inventory for goods or raw materials that would be stocked
				$dc[] = array(
					"transaction_id" => $e["id"],
					"account" => $inventory_account,
					"amount" => $cost,
					"type" => "credit",
					"account_type" => $rv[ "inventory" ],
					
					"extra_reference" => $e[ "id" ],
					"reference_table" => $this->table_name,
				);
				
				$dc1[] = array(
					"transaction_id" => $e["id"],
					"account" => $inventory_account,
					"amount" => $cost,
					"type" => "credit",
					"account_type" => $rv[ "inventory" ],
					
					"extra_reference" => $e[ "id" ],
					"reference_table" => $this->table_name,
				);
				
				$dc1[] = array(
					"transaction_id" => $e["id"],
					"account" => $inventory_account,
					"amount" => $cost,
					"type" => "debit",
					"account_type" => $rv[ "inventory" ],
					
					"extra_reference" => $e[ "id" ],
					"reference_table" => $this->table_name,
					"store" => $e["factory"],
				);
			}
			
			switch( $e["status"] ){
			case 'materials-transfer':
				$dc = $dc1;
			break;
			}
			
			$data = array(
				"id" => $e["id"] ,
				"date" => date( "Y-n-j", doubleval( $e["date"] ) ) ,
				"reference" => $e["id"] ,
				"reference_table" => $this->table_name,
				"description" => $desc . ( ( $e["comment"] )?$e["comment"]:"" ),
				"credit" => $cost,
				"debit" => $cost,
				"status" => "approved",
				'submitted_by' => $e["staff_responsible"],
				'submitted_on' => $e["creation_date"],
				'store' => $e["store"],
				'item' => $dc,
			);
			
			$transactions = new cTransactions();
			$transactions->class_settings = $this->class_settings;
			$transactions->class_settings["data"] = $data;
			$transactions->class_settings["action_to_perform"] = "add_transaction_from_sales";
			return $transactions->transactions();
		}
		
		protected function _delete_parent_data_manifest(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			$_POST["mod"] = "delete-".md5( $this->table_name );
			$return = $this->_delete_records();
			
			if( isset( $return['deleted_record_id'] ) && $return['deleted_record_id'] ){
				$this->class_settings["parent_data_id"] = $return['deleted_record_id'];
				
				//delete inventory
				$inventory = new cInventory();
				$inventory->class_settings = $this->class_settings;
				$inventory->class_settings["action_to_perform"] = 'delete_goods_produced';
				$inventory->inventory();
				
				$return["html_removal"] = "#" . $return['deleted_record_id'] ;
				
				$return["html_replace_selector"] = "#manifest-" . $return['deleted_record_id'];
				
				$project = get_project_data();
				$return["html_replace"] = '<div style="text-align:center;"><img src="'.$project['domain_name'].'frontend-assets/img/logo_blue.png" alt="" align="center"></div>';
			}
			
			unset( $return["html"] );
			$return["status"] = "new-status";
			
			return $return;
		}
		
		protected function _update_parent_data_status(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid Record ID';
				return $err->error();
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$this->class_settings["parent_data_id"] = $this->class_settings["current_record_id"];
			$e["event"] = $this->_get_customer_call_log();
			
			$this->class_settings["date"] = $e["event"]["date"];
			$this->class_settings["store"] = $e["event"]["store"];
			$this->class_settings["source"] = $e["event"]["factory"];
			$this->class_settings["vendor"] = $e["event"]["factory"];
			
			$_POST["mod"] = "edit-".md5( $this->table_name );
			
			$this->class_settings["do_not_show_headings"] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["date"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["extra_cost"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["quantity"] ] = 1;
			$this->class_settings["hidden_records"][ $this->table_fields["cost"] ] = 1;
			
			if( isset( $e["event"]["status"] ) && ( $e["event"]["status"] == "materials-transfer"  ) ){
				$this->class_settings['disable_form_element'][ $this->table_fields["factory"] ] = ' disabled="disabled" ';
				$this->class_settings["hidden_records"][ $this->table_fields["status"] ] = 1;
			}
			
			if( isset( $e["event"]["status"] ) && ( $e["event"]["status"] == "materials-utilized" || $e["event"]["status"] == "damaged-materials" ) ){
				$this->class_settings['hidden_records'][ $this->table_fields["store"] ] = 1;
				$this->class_settings['hidden_records'][ $this->table_fields["factory"] ] = 1;
				$this->class_settings["hidden_records"][ $this->table_fields["status"] ] = 1;
			}
			
			$this->class_settings[ 'form_action_todo' ] = 'save_update_parent_data_status';
			
			$e["parent_data_form"] = $this->_generate_new_data_capture_form();
			
			$parent_data_items = new cparent_data_items();
			$parent_data_items->class_settings = $this->class_settings;
			$parent_data_items->class_settings["action_to_perform"] = 'view_all_parent_data_items_editable';
			$e['materials'] = $parent_data_items->parent_data_items();
			
			$title = "Update parent_data Manifest Status";
			
			if( ! ( isset( $e["event"]["status"] ) && ( $e["event"]["status"] == "materials-utilized" || $e["event"]["status"] == "damaged-materials" ) ) ){
				if( isset( $e["event"]["status"] ) && $e["event"]["status"] == "materials-transfer" ){
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["action_to_perform"] = 'view_all_produced_items_editable';
					$e['produced_items'] = $inventory->inventory();
					
					$title = "Update Material Transfer Status";
				}else{
					
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					$expenditure->class_settings["action_to_perform"] = 'view_all_parent_data_items_editable';
					$e['expenses'] = $expenditure->expenditure();
				}
			}else{
				$title = "Update Material Utilization Status";
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/parent_data-manifest-status-update.php' );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["backend"] = 1;
			
			if( isset( $e["event"]["status"] ) && $e["event"]["status"] == "materials-transfer" ){
				$this->class_settings[ 'data' ]["goods_produced_title"] = "Goods Received";
				$this->class_settings[ 'data' ]["raw_materials_title"] = "Goods Issued";
			}
			
			$html = $this->_get_html_view();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
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
		
		protected function _save_parent_data_and_return_receipt(){
			
			if( ! ( isset( $this->class_settings["parent_data"] ) && is_array( $this->class_settings["parent_data"] ) ) ){
				return 0;
			}
			if( ! ( isset( $this->class_settings[ 'parent_data_id' ] ) ) ){
				return 0;
			}
			
			$array_of_dataset = array();
			
			$new_record_id = $this->class_settings[ 'parent_data_id' ];
			$this->class_settings["current_record_id"] = $new_record_id;
			
			$ip_address = get_ip_address();
			$date = date("U");
			
			$comment = "Materials Utilized";
			if( isset( $this->class_settings["parent_data"]["comment"] ) ){
				$comment = $this->class_settings["parent_data"]["comment"];
			}
			
			$description = 'Materials Request';
			if( isset( $this->class_settings["parent_data"]["description"] ) ){
				$description = $this->class_settings["parent_data"]["description"];
			}
			
			$dataset_to_be_inserted = array(
				'id' => $new_record_id,
				'created_role' => $this->class_settings[ 'priv_id' ],
				'created_by' => $this->class_settings[ 'user_id' ],
				'creation_date' => $date,
				'modified_by' => $this->class_settings[ 'user_id' ],
				'modification_date' => $date,
				'ip_address' => $ip_address,
				'record_status' => 1,
				
				$this->table_fields["date"] => $date,
				$this->table_fields["staff_responsible"] => $this->class_settings["parent_data"][ 'staff_responsible' ],
				$this->table_fields["store"] => $this->class_settings["parent_data"][ 'store' ],
				$this->table_fields["status"] => $this->class_settings["parent_data"]["stock_status"],
				$this->table_fields["reference"] => ( isset( $this->class_settings["parent_data"]["reference"] )?$this->class_settings["parent_data"]["reference"] : "" ),
				$this->table_fields["reference_table"] => ( isset( $this->class_settings["parent_data"]["reference_table"] )?$this->class_settings["parent_data"]["reference_table"] : "" ),
			);
			
			switch( $this->table_name ){
			case "prescription":
				$dataset_to_be_inserted[ $this->table_fields["customer"] ] = ( isset( $this->class_settings["parent_data"]["customer"] )?$this->class_settings["parent_data"]["customer"] : "" );
				$dataset_to_be_inserted[ $this->table_fields["comment"] ] = $comment;
			break;
			case "stock_request":
				if( isset( $this->class_settings["parent_data"]["serial_num"] ) && $this->class_settings["parent_data"]["serial_num"] ){
					unset( $dataset_to_be_inserted[ $this->table_fields["store"] ] );
					unset( $dataset_to_be_inserted[ $this->table_fields["reference"] ] );
					unset( $dataset_to_be_inserted[ $this->table_fields["reference_table"] ] );
					unset( $dataset_to_be_inserted[ $this->table_fields["staff_responsible"] ] );
					unset( $dataset_to_be_inserted[ $this->table_fields["description"] ] );
				}
				$d = array(
					$this->table_fields["description"] => $description,
					$this->table_fields["comment"] => $comment,
					
				);
				$dataset_to_be_inserted = array_merge( $dataset_to_be_inserted, $d );
			break;
			default:
				$d = array(
					$this->table_fields["extra_cost"] => $this->class_settings["parent_data"]["extra_cost"],
					$this->table_fields["cost"] => $this->class_settings["parent_data"]["total_cost"],
					$this->table_fields["quantity"] => isset( $this->class_settings["parent_data"]["quantity"] )?$this->class_settings["parent_data"]["quantity"]:1,
					$this->table_fields["factory"] => $this->class_settings["parent_data"]["factory"],
					
					$this->table_fields["comment"] => ( isset( $this->class_settings["goods_produced"] )?( "parent_data of " . $this->class_settings["goods_produced"] ) : $comment ),
					
					$this->table_fields["customer"] => ( isset( $this->class_settings["parent_data"]["customer"] )?$this->class_settings["parent_data"]["customer"] : "" ),
					
				);
				$dataset_to_be_inserted = array_merge( $dataset_to_be_inserted, $d );
			break;
			}
			
			if( isset( $this->class_settings["parent_data"]["serial_num"] ) && $this->class_settings["parent_data"]["serial_num"] ){
				unset( $dataset_to_be_inserted[ "id" ] );
				unset( $dataset_to_be_inserted[ 'created_role' ] );
				unset( $dataset_to_be_inserted[ 'created_by' ] );
				unset( $dataset_to_be_inserted[ 'creation_date' ] );
				
				$new_record_id = $this->class_settings["parent_data"]["id"];
				
				$update_conditions_to_be_inserted = array(
					'where_fields' => 'serial_num',
					'where_values' => $this->class_settings["parent_data"]["serial_num"],
				);	
				$array_of_update_conditions[] = $update_conditions_to_be_inserted;
			}
			
			//new
			$array_of_dataset[] = $dataset_to_be_inserted;
				
			$saved = 0;
			if( ! empty( $array_of_dataset ) ){
				
				$function_settings = array(
					'database' => $this->class_settings['database_name'],
					'connect' => $this->class_settings['database_connection'],
					'table' => $this->table_name,
					'dataset' => $array_of_dataset,
				);
				
				if( isset( $array_of_update_conditions ) && $array_of_update_conditions ){
					$function_settings[ 'update_conditions' ] = $array_of_update_conditions;
					$this->class_settings[ 'do_not_check_cache' ] = 1;
				}
				$returned_data = insert_new_record_into_table( $function_settings );
				
				if( isset( $this->class_settings["parent_data"]["stock_status"] ) ){
					switch( $this->class_settings["parent_data"]["stock_status"] ){
					case "materials-utilized":
					case "sales-order":
					case "damaged-materials":
					case 'materials-transfer':
						//record in accounting
						$this->class_settings["current_record_id"] = $this->class_settings[ 'parent_data_id' ];
						$this->_record_in_accounting();
					break;
					}
				}
				/*
				//Enable for single cost expense i.e extra cost during parent_data
				if( doubleval( $this->class_settings["parent_data"]["extra_cost"] ) ){
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					
					$expenditure->class_settings["action_to_perform"] = "save_expenditure";
					$expenditure->expenditure();
				}
				*/
				
				//multiple cost during parent_data
				if( isset( $this->class_settings["parent_data"]["expenses"] ) && is_array( $this->class_settings["parent_data"]["expenses"] ) && ! empty( $this->class_settings["parent_data"]["expenses"] ) ){
					$expenditure = new cExpenditure();
					$expenditure->class_settings = $this->class_settings;
					
					$expenditure->class_settings["action_to_perform"] = "save_mulitple_expenses";
					$expenditure->expenditure();
				}
				
				$saved = $this->class_settings["current_record_id"];
			}
			
			$_POST["id"] = $new_record_id;
			
			if( isset( $this->class_settings["return_after_save"] ) && $this->class_settings["return_after_save"] ){
				return $saved;
			}
			
			$this->class_settings["hide_buttons"] = 1;
			$return = $this->_view_invoice();
			$return["javascript_functions"][] = "nwCart.emptyCart";
			$return["saved"] = $saved;
			
			return $return;
		}
		
		protected function _track_invoice(){
			$return["html_replacement_selector"] = "#dash-board-main-content-area";
			
			$error = '<div class="note note-danger"><h4 class="block">Invalid Invoice Number</h4><p>Please Provide a valid invoice number </p></div>';
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				
				$return['html_replacement'] = $error;
				$return['status'] = "new-status";
				return $return;
			}
			
			$this->class_settings["current_record_id"] = $_POST["id"];
			$event = $this->_get_customer_call_log();
			
			if( ! ( isset( $event["id"] ) && $event["id"] ) ){
				$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `serial_num` = ".$this->class_settings[ 'current_record_id' ];
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_data = execute_sql_query($query_settings);
				if( isset( $all_data[0]["id"] ) && $all_data[0]["id"] ){
					$this->class_settings["current_record_id"] = $all_data[0]["id"];
					$event = $this->_get_customer_call_log();
				}
			}
			
			if( ! ( isset( $event["id"] ) && $event["id"] ) ){
				$return['html_replacement'] = $error;
				$return['status'] = "new-status";
				return $return;
			}
			
			$return['status'] = "new-status";
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/book-event-success-message.php' );
			$this->class_settings[ 'data' ]["hide_info"] = 1;
			$this->class_settings[ 'data' ]["event"] = $event;
			$return["html_replacement"] = $this->_get_html_view();
			
			
			return $return;
		}
		
		protected function _view_invoice(){
			$error = '';
			$action_to_perform = $this->class_settings['action_to_perform'];
			$handle1 = 'dash-board-main-content-area';
			
			$card_view = isset( $_GET[ 'card_view' ] ) && $_GET[ 'card_view' ] ? $_GET[ 'card_view' ] : '';
			$r_source = isset( $_GET[ 'r_source' ] ) ? $_GET[ 'r_source' ] : '';
			$empty_container = isset( $_GET[ 'empty_container' ] ) ? $_GET[ 'empty_container' ] : '';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle1 = $this->class_settings[ 'html_replacement_selector' ];
			}
			$handle = '#' . $handle1;
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error = 'Invalid Record ID';
				return $this->_display_notification( array( "type" => "error", "message" => $error ) );
			}
			
			switch( $action_to_perform ){
			case "view_invoice_app":
			break;
			case "view_invoice_app_i_menu":
			break;
			case "view_details2":
				$this->class_settings["add_buttons"] = 1;
			break;
			default:
				$this->class_settings["hide_buttons"] = 1;
			break;
			}
			
			//$this->_refresh_parent_data_info();
			if( ! $error ){
				$this->class_settings["current_record_id"] = $_POST["id"];
				$this->class_settings["parent_data_id"] = $this->class_settings["current_record_id"];
				$e["event"] = $this->_get_record();
				
				$table = $this->table_name_sub;
				$actual_name_of_class = 'c'.ucwords( $table );
				$module = new $actual_name_of_class();
				$module->class_settings = $this->class_settings;
				$module->class_settings["action_to_perform"] = "get_specific_parent_data_items";
				
				$module->class_settings['return_data'] = 1;
				$_POST[ $module->default_reference ] = $this->class_settings["current_record_id"];
				
				$e['materials'] = $module->$table();
				
				$this->class_settings['item'] = $e['event'];
				$this->class_settings['items'] = $e['materials'];
				
				$materials_used = 0;
				$title = "parent_data Manifest";
				$filename = 'production-manifest.php';
			}
			//expensesPrescription-dispatched-partial-pharmacy
			if( isset( $e["event"]["status"] ) ){
				switch( $action_to_perform ){
				case 'process_draft_sales':
					$data = isset( $e[ 'event' ][ 'data' ] ) ? json_decode( $e[ 'event' ][ 'data' ], true ) : array();
						// print_r( $data );exit();
					if( isset( $data[ 'item' ] ) && ! empty( $data[ 'item' ] ) ){
						$c = new cCart();
						$c->class_settings = $this->class_settings;
						$c->class_settings[ 'action_to_perform' ] = 'checkout';

						unset( $data[ 'save_as_draft' ] );

						$_POST[ 'json' ] = $data;
						$_POST["json"]["term2"] = $this->table_name;
						$_POST["json"]["extra_reference"] = $e["event"]["id"];
						$_POST["json"]["sales_status"] = "sold";

						$r = $c->cart();
						
						if( $card_view ){
							$r[ 'status' ] = 'new-status';
							$r[ 'javascript_functions' ] = array( 'nwImenuJSC.resubmitForm' );
							
							if( isset( $r[ 'html' ] ) ){
								$r[ 'html_replacement' ] = $r[ 'html' ];
								unset($r[ 'html' ]);
							}
						}else if( isset( $_GET[ 'empty_container' ] ) && $_GET[ 'empty_container' ] ){
							$r[ 'status' ] = 'new-status';
							$r[ 'html_replacement' ] = '&nbsp;';
							$r[ 'html_replacement_selector' ] = '#'.$_GET[ 'empty_container' ];
							$r[ 'javascript_functions' ] = array( '$nwProcessor.reload_datatable' );
							
							if( isset( $r[ 'html' ] ) ){
								$r[ 'html_replacement' ] = $r[ 'html' ];
								unset($r[ 'html' ]);
							}
						}
						
						return $r;
					}else{
						return $this->_display_notification( array( "type" => "error", "message" => "No Items were found" ) );
					}
				break;
				}

				switch( $e["event"]["status"] ){
				case 'prescription-out-patient':
				case 'prescription-in-patient':
				case 'prescription-pending':
				case 'prescription-dispatched-partial':
				case 'prescription-dispatched':
				case 'prescription-dispatched-partial-pharmacy':
				case 'prescription-dispatched-pharmacy':
				case 'prescription-dispatched-none':
				case 'stock-request':
				case 'stock-dispatch':
				case "stock-request-approved":
				break;
				case "pending-order":
					$filename = 'invoice.php';
					$title = $this->label;
					$e['event']["sales_status"] = $e['event']['status'];
					$e['event_items'] = $e['materials'];
				break;
				case "sales-order":
					$filename = 'picking-slip.php';
					$title = "Sales Order Picking Slip";
				break;
				case "materials-utilized":
				case "damaged-materials":
					$materials_used = 1;
					$title = "Materials Utilized";
					
					switch( $e["event"]["status"] ){
					case "damaged-materials":
						$materials_used = 3;
						$title = "Damaged Materials";
					break;
					}
				break;
				default:
					if( isset( $e["event"]["status"] ) && $e["event"]["status"] == "materials-transfer" ){
						$materials_used = 2;
						$title = "Stock Transfer";
					}else{
						$expenditure = new cExpenditure();
						$expenditure->class_settings = $this->class_settings;			
						$expenditure->class_settings["action_to_perform"] = "get_specific_produced_items";
						$e['expenses'] = $expenditure->expenditure();
					}
					
					$this->class_settings["reference_table"] = $this->table_name;
					
					$inventory = new cInventory();
					$inventory->class_settings = $this->class_settings;
					$inventory->class_settings["action_to_perform"] = "get_specific_produced_items";
					$e['produced_items'] = $inventory->inventory();
				break;
				}	
			}
			
			if( isset( $e["event"]["reference_table"] ) ){
				switch( $e["event"]["reference_table"] ){
				case "project_task_status":
				case "cart":
					if( ! $title )$title = "Stock Requisition Slip";
					$filename = 'general-picking-slip.php';
				break;
				}
			}
			// print_r( $e );exit();
			
			if( isset( $e["event"]["status"] ) ){
				switch( $e["event"]["status"] ){
				case "direct-deductions":
					$filename = 'general-picking-slip.php';
					$title = "Direct Stock Deductions";
				break;
				case 'prescription-out-patient':
				case 'prescription-in-patient':
				case 'prescription-pending':
				case 'prescription-dispatched-partial':
				case 'prescription-dispatched':
				case 'prescription-dispatched-partial-pharmacy':
				case 'prescription-dispatched-pharmacy':
				case 'prescription-dispatched-none':
					$title = $this->label;
					$filename = 'prescription-slip.php';
				break;
				case 'stock-dispatch':
				case 'stock-request':
				case "stock-request-approved":
					$title = "Material Request";
				break;
				}
			}
			
			switch( $action_to_perform ){
			case "view_invoice_info1":
			case "view_invoice_info":
				foreach( $e['materials'] as & $v ){
					$item_details = get_items_details( array( "id" => $v["item"] ) );
					
					if( isset( $item_details["description"] ) ){
						$v["desc"] = $item_details["description"];
						$v["barcode"] = $item_details["barcode"];
						$v["type"] = $item_details["type"];
					}else{
						$v["desc"] = "";
						$v["barcode"] = "";
						$v["type"] = "";
					}
					
					$v["serial_num"] = doubleval( $v["serial_num"] );
					$v["quantity_instock"] = doubleval( $v["quantity_instock"] );
					$v["quantity"] = doubleval( $v["quantity"] );
					
					$v["quantity_expected"] = $v["quantity"];
					$v["quantity_supplied"] = $v["quantity"];
					$v["quantity_ordered"] = $v["quantity"];
					$v["cost"] = doubleval( $item_details["cost_price"] );
					$v["cost_price"] =  doubleval( $item_details["cost_price"] );
					
					$v["discount"] = 0;
					$v["tax"] = 0;
					
				}
				
				$js = array( 'nwInventory.rePopulateCart' );
				switch( $action_to_perform ){
				case "view_invoice_info1":
					$e['event']["reference"] = $this->class_settings["current_record_id"];
					$e['event']["reference_table"] = $this->table_name;
					$e['purchased_items'] = $e['materials'];
					$js = array( 'nwCart.rePopulateCart' );
				break;
				}
				
				return array(
					'data' => $e,
					'status' => 'new-status',
					'javascript_functions' => $js,
				);
			break;
			}
			
			if( isset( $e["event"]["id"] ) && isset( $this->class_settings["add_buttons"] ) && $this->class_settings["add_buttons"] && method_exists( $this, "_apply_basic_data_control" ) ){
				$this->_apply_basic_data_control( $e["event"] );
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $handle1;
				$this->class_settings[ 'data' ][ 'selected_record' ] = $e["event"]["id"];
				$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
				
				$this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
				$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
				$e["more_actions"] = $this->_get_html_view();
			}
			
			if( isset( $_GET["no_column"] ) && $_GET["no_column"] ){
				$e["no_column"] = $_GET["no_column"];
			}
			
			if( $empty_container ){
				$e["empty_container"] = $empty_container;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/'.$filename );
			$this->class_settings[ 'data' ] = $e;
			$this->class_settings[ 'data' ]["table"] = $this->table_name;
			$this->class_settings[ 'data' ]["prefix"] = isset( $this->prefix )?$this->prefix:'';
			$this->class_settings[ 'data' ]["title"] = $title;
			$this->class_settings[ 'data' ]["backend"] = 1;
			$this->class_settings[ 'data' ]["materials_used"] = $materials_used;
			
			if( isset( $this->class_settings["show_print_button"] ) )
				$this->class_settings[ 'data' ]["backend"] = 0;
			
			if( isset( $this->class_settings["hide_buttons"] ) )
				$this->class_settings[ 'data' ]["hide_buttons"] = 1;
			
			if( ( isset( $this->class_settings["show_small_invoice"] ) && $this->class_settings["show_small_invoice"] ) ){
				$this->class_settings[ 'data' ][ "pos" ] = 1;
			}
			
			switch( $action_to_perform ){
			case "view_invoice_app_i_menu":
				$this->class_settings[ 'data' ][ "i_menu" ] = 1;
			break;
			}
			
			if( ( isset( $this->class_settings["i_menu2"] ) && $this->class_settings["i_menu2"] ) ){
				$this->class_settings[ 'data' ][ "i_menu" ] = $this->class_settings["i_menu2"];
			}
			
			
			$html = $this->_get_html_view();
			
			$summary_data = array();
			switch( $action_to_perform ){
			case "view_invoice_app":
			case "view_invoice_app_i_menu":
				return array(
					'html_replacement' => $html,
					'html_replacement_selector' => "#invoice-receipt-container",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			break;
			case "view_invoice_items":
				$crt = $e[ 'event' ][ 'child_reference_table' ];
				foreach( $e[ 'event_items' ] as $md ){
					$mdd = json_decode( $md[ 'data' ], 1 );
					$mdd[ 'crt' ] = $crt;
					$summary_data = $mdd;
				}
				// print_r( $e ); exit;
				return $summary_data;
			break;
			}
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				$hh = "#modal-replacement-handle";
				if( isset( $_GET["modal_handle"] ) && $_GET["modal_handle"] && $handle ){
					$hh = $handle;
				}
				
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => $hh,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => array( 'set_function_click_event' ),
				);
			}
			
			$this->class_settings["modal_title"] = $title;
			$this->class_settings["modal_dialog_style"] = " min-width:40%; ";
			return $this->_launch_popup( $html, '#dash-board-main-content-area', array() );
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			
			if( isset( $this->class_settings["parent_data"]['teller_info_id'] ) && $this->class_settings["parent_data"]['teller_info_id'] ){
				$this->class_settings[ 'data' ]['exit_command'] = 'navigate_to_capture_teller_info';
			}
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'html_prepend' => $returning_html_data,
				//'javascript_functions' => array( 'prepare_new_record_form_new' ),
			);
			
		}
		
		protected function _refresh_parent_data_info(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Invalid parent_data Record</h4>Please Select a parent_data Record to Refresh';
				return $err->error();
			}
			$parent_data_id = $_POST["id"];
			$this->class_settings["parent_data_id"] = $parent_data_id;
			
			//get parent_data items
			$parent_data_items = new cParent_data_items();
			$parent_data_items->class_settings = $this->class_settings;
			$parent_data_items->class_settings["action_to_perform"] = "get_specific_parent_data_items";
			$items = $parent_data_items->parent_data_items();
			
			//get expenses
			$expenditure = new cExpenditure();
			$expenditure->class_settings = $this->class_settings;
			$expenditure->class_settings["action_to_perform"] = "get_total_expenditure";
			$total = $expenditure->expenditure();
			
			$units = 0;
			$cost = 0;
			
			if( is_array( $items ) && ! empty( $items ) ){
				foreach( $items as $item ){
					$units += $item["quantity"];
					$cost += ( $item["quantity"] * $item["cost"] );
				}
			}
			if( isset( $total[0]["TOTAL"] ) ){
				$this->class_settings["update_fields"][ "extra_cost" ] = doubleval( $total[0]["TOTAL"] );
			}
			
			$this->class_settings["update_fields"][ "quantity" ] = $units;
			$this->class_settings["update_fields"][ "cost" ] = $cost;
			
			return $this->_update_table_field();
		}
		
	}
?>
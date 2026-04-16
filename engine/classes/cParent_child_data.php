<?php
	/**
	 * parent_child_data Class
	 *
	 * @used in  				parent_child_data Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	parent_child_data
	 */

	/*
	|--------------------------------------------------------------------------
	| parent_child_data Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	class cParent_child_data extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'parent_child_data';
		public $table_name_static = 'parent_child_data';
		
		public $default_reference = '';
		
		public $label = 'Parent Child Data';
		
		private $associated_cache_keys = array(
			'parent_child_data',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array(
			'production_id' => 'parent_child_data001',
			'item_id' => 'parent_child_data002',
			'cost' => 'parent_child_data003',
			'quantity' => 'parent_child_data004',
			
			'extra_cost' => 'parent_child_data005',
			'product_type' => 'parent_child_data006',
			
			'amount_due' => 'parent_child_data007',
			'currency' => 'parent_child_data008',
			'quantity_instock' => 'parent_child_data009',
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
			
		function __construct(){
			
		}
	
		function parent_child_data(){
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
			case 'get_specific_parent_child_data':
			case 'view_all_parent_child_data_editable':
			case 'view_all_parent_child_data':
				$returned_value = $this->_view_all_parent_child_data();
			break;
			case 'delete_parent_child_data':
				$returned_value = $this->_delete_parent_child_data();
			break;
			case 'delete_children_only':
			case 'delete_materials':
				$returned_value = $this->_delete_materials();
			break;
			case 'filter_parent_child_data_search_all':
				$returned_value = $this->_filter_parent_child_data_search_all();
			break;
			}
			
			return $returned_value;
		}
		
		protected function _filter_parent_child_data_search_all(){
			$where = "";
			
			if( isset( $_POST["item_id"] ) && $_POST["item_id"] ){
				$this->class_settings["item_id"] = $_POST["item_id"];
			}
			
			if( isset( $this->class_settings["item_id"] ) && $this->class_settings["item_id"] )
				$where = " AND `".$this->table_fields["item_id"]."` = '".$this->class_settings["item_id"]."' ";
			
			/*
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"] );
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"] );
			}
			
			if( isset( $this->class_settings["start_date"] ) && doubleval( $this->class_settings["start_date"] ) ){
				$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $this->class_settings["end_date"] ) && doubleval( $this->class_settings["end_date"] ) ){
				$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			*/
			
			unset( $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] );
			if( $where )$_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = $where;
			
			return array(
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
			);
		}
		
		protected function _delete_materials(){
			switch( $this->class_settings["action_to_perform"] ){
			case 'delete_children_only':
				if( ! ( isset( $this->class_settings[ "parent_ids" ] ) && is_array( $this->class_settings[ "parent_ids" ] ) && !empty( $this->class_settings[ "parent_ids" ] ) ) ){
					return 0;
				}
				$d = $this->class_settings[ "parent_ids" ];
				
				switch( $this->table_name ){
				case "stock_request_items":
					$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `record_status` = '0' WHERE `".$this->table_fields['parent_id']."` IN ( ".implode( ',', $d )." ) "; 
				break;
				default:
					$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `record_status` = '0' WHERE `".$this->table_fields['production_id']."` IN ( ".implode( ',', $d )." ) "; 
				break;
				}
			break;
			default:
				
				if( ! ( isset( $this->class_settings[ 'production_id' ] ) ) ){
					return 0;
				}
				$select = " `modified_by` = '".$this->class_settings["user_id"]."', `modification_date` = '".$this->class_settings[ 'production_id' ]."', `record_status` = '0' ";
				$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET ".$select." where `".$this->table_fields[ "production_id" ]."` = '".$this->class_settings[ 'production_id' ]."' ";
			break;
			}
			
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
		
		protected function _delete_parent_child_data(){
			//check for duplicate record
			
			if( ! ( isset( $_GET["month"] ) && $_GET["month"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords( $this->table_name );
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = 'Invalid Parent Child DataID';
				return $err->error();
			}
			
			$this->class_settings[ 'current_record_id' ] = $_GET["month"];
			
			$member_id = "";
			if( isset( $_GET["budget"] ) && $_GET["budget"] )
				$member_id = $_GET["budget"];
			
			$_POST['id'] = $this->class_settings[ 'current_record_id' ];
			$_POST['mod'] = 'delete-'.md5( $this->table_name );
			
			$this->_delete_records();
			
			$return["status"] = "new-status";
			$return["html_removal"] = "#parent_child_data-" . $this->class_settings[ 'current_record_id' ];
			
			unset( $return["html"] );
			return $return;
		}
		
		protected function _view_all_parent_child_data(){
			
			if( ! isset( $this->class_settings["production_id"] ) )return 0;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`id`, `serial_num`, `creation_date`, `modification_date`, `".$val."` as '".$key."'";
			}
			
			$reference = "production_id";
			
			switch( $this->table_name ){
			case "stock_request_items":
				$reference = "parent_id";
			break;
			}
			
			//PREPARE FROM DATABASE
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `".$this->table_fields[ $reference ]."` = '".$this->class_settings["production_id"]."' ";
			
			//echo $query; exit;
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$bills = execute_sql_query($query_settings);
			$form = "";
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'get_specific_parent_child_data':
				return $bills;
			break;
			case 'view_all_parent_child_data_editable':
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "item_id":	
					case "quantity":	
					case "cost":
					break;
					case "production_id":
						$this->class_settings["hidden_records_css"][ $val ] = 1;
						
						if( isset( $this->class_settings[ $key ] ) )
							$this->class_settings["form_values_important"][ $val ] = $this->class_settings[ $key ];
					break;
					default:
						$this->class_settings["hidden_records"][ $val ] = 1;
					break;
					}
				}
				
				$this->class_settings[ 'form_action_todo' ] = 'save_new_parent_child_data';
				
				$form1 = $this->_generate_new_data_capture_form();
				$form = $form1["html"];
			break;
			}
			
			$this->class_settings[ 'data' ][ 'no_edit' ] = 1;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/form-control-view.php' );
			$this->class_settings[ 'data' ]['items'] = $bills;
			$this->class_settings[ 'data' ]['form'] = $form;
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new', 'set_function_click_event' ),
			);
		}
		
	}
?>
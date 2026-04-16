<?php
/**
 * reports_ui Class
 *
 * @used in  				reports_ui Function
 * @created  				Hyella Nathan | 22:48 | 05-Jul-2023
 * @database table name   	reports_ui
 */
	
	class cReports_ui extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'reports_ui';
		
		public $default_reference = '';
		
		public $label = 'Reports UI';
		
		private $associated_cache_keys = array(
			'reports_ui',
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/reports_ui.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/reports_ui.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function reports_ui(){
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'homepage':
			case 'homepage_personal':
				$returned_value = $this->_homepage();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		public function _homepage(){
			$error = '';
			$error_typ = 'error';
			$err_params = array();
			
			$filename = '';
			$tablename = '';
			$title = '';
			$modal = 0;
			$html = '';
			
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			
			$data = array();
			$data[ 'plugin' ] = $this->plugin;
			$data[ 'table' ] = $this->table_name;
			$js = array( "prepare_new_record_form_new" , "set_function_click_event" );
			
			$handle = "dash-board-main-content-area";
			if( isset( $this->class_settings["html_replacement_selector"] ) && $this->class_settings["html_replacement_selector"] ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			
			switch( $action_to_perform ){
			case 'homepage':
			case 'homepage_personal':
				$no_side_bar = isset( $_GET[ 'no_side_bar' ] ) ? $_GET[ 'no_side_bar' ] : '';
				$date_range = isset( $_POST[ 'date_range' ] ) ? $_POST[ 'date_range' ] : '';
				$report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : '';

				$filename = 'homepage';
				$addQuery = [];

				if( $date_range ){
					// Define the start and end dates based on the selected option
				    $endDate = date('Y-m-d'); // Today's date as end date
				    $startDate = '';

				    switch( $date_range ){
			        case 'this_month':
			            $startDate = date('Y-m-01'); // First day of the current month
		            break;
			        case 'last_month':
			            $startDate = date('Y-m-d', strtotime('first day of last month')); // First day of last month
			            $endDate = date('Y-m-t', strtotime('last month')); // Last day of last month
		            break;
			        case 'last3_month':
			            $startDate = date('Y-m-d', strtotime('-2 months')); // 3 months ago from today
		            break;
			        case 'ytd':
			            $startDate = date('Y-01-01'); // First day of the current year
		            break;
			        case 'last_year':
			            $startDate = date('Y-01-01', strtotime('last year')); // First day of last year
			            $endDate = date('Y-12-t', strtotime('last year')); // Last day of last year
		            break;
			        case 'last_3_years':
			            $startDate = date('Y-m-d', strtotime('-2 years')); // 3 years ago from today
		            break;
			        default:
			            // Handle any other cases or errors
		            break;
				    }

				    if( $startDate ){
				    	$nid = get_new_id();
					    $addQuery[ '1' ][ 'data' ][ $nid ] = array(
					        "field" => "creation_date",
					        "condition_text" => "BETWEEN",
					        "condition" => "between",
					        "search_key" => "end_date",
					        "search_value" => $endDate,
					        "start_date" => $startDate,
					        "end_date" => $endDate,
					        "logical_operator" => ""
					    );
				    }
				}

				$rb = 'reports_bay';
				$al = $this->plugin_instance->load_class( array( 'class' => array( $rb ), 'initialize' => 1 ) );

				$al[ $rb ]->class_settings[ 'overide_select' ] = " id, `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'name' ] ."` as 'name', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'endpoint' ] ."` as 'endpoint', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'title' ] ."` as 'title', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'type' ] ."` as 'type', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'data' ] ."` as 'data' ";
				$al[ $rb ]->class_settings[ 'where' ] = " AND `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'status' ] ."` = 'active' ";

				$data[ 'rdata' ] = $al[ $rb ]->_get_records( array( 'index_field' => 'id' ) );

				switch( $action_to_perform ){
				case 'homepage_personal':
					
					$al[ $rb ]->class_settings[ 'overide_select' ] = " id, `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'name' ] ."` as 'name', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'endpoint' ] ."` as 'endpoint', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'title' ] ."` as 'title', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'type' ] ."` as 'type', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'data' ] ."` as 'data', `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'parent_report' ] ."` as 'parent_report' ";
					$al[ $rb ]->class_settings[ 'where' ] = " AND `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'status' ] ."` = 'active' AND `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'staff_responsible' ] ."` = '". $this->class_settings[ 'user_id' ] ."' AND ( `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'parent_report' ] ."` IS NOT NULL OR `". $al[ $rb ]->table_name ."`.`". $al[ $rb ]->table_fields[ 'parent_report' ] ."` <> '' ) ";

					$data[ 'rpdata' ] = $al[ $rb ]->_get_records( array( 'index_field' => 'parent_report' ) );

				break;
				}
				
				$data[ 'add_url' ] = '&daction='.$this->plugin.'&dtodo=execute&dnwp_action='.$rb.'&dnwp_todo=exec_report_url';
				
				$data["report"] = $report;

				$data["date_range"] = $date_range;
				$data["no_side_bar"] = $no_side_bar;
				$data["addQuery"] = $addQuery;
				$data["logo"] = isset( $_POST["logo"] )?$_POST["logo"]:'';
				$data["logo"] = ( isset( $_POST["dashboard_logo"] ) && $_POST["dashboard_logo"] )?$_POST["dashboard_logo"]:$data["logo"];

			break;
			}
			
			if( $error ){
				$e = array_merge( array( "type" => $error_typ, "message" => $error ), $err_params );
				return $this->_display_notification( $e );
			}
			
			if( ! $html ){
				$data["action_to_perform"] = $action_to_perform;
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( $this->view_path .'/' . $filename );

				$html = $this->_get_html_view();
			}
			
			if( $modal ){
				$this->class_settings["modal_title"] = $title;
				return $this->_launch_popup( $html, '#'.$handle, $js );
			}
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => '#'.$handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
			'more_actions' => array(),
		);
	}
	
	function reports_ui(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/reports_ui.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/reports_ui.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>
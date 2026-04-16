<?php
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 06:42 | 30-Jun-2023
 */
class cNwp_endpoint extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'nwp_endpoint';

	public $plugin_options = array(
		"frontend_tabs" => array(
			"e_point" => array(
				"title" => "ID4D",
				"type" => "replace",
				"action" => "?action=all_reports&todo=display_inventory_menu&current_tab=e_point",
				"access_role" => "",
				"module_name" => "frontend_modules",
				"dashboard" => 1,
				"dashboard_icon" => "icon-group text-info",
			),
			"e_point2" => array(
				"title" => "ID4D:FE",
				"type" => "replace",
				"action" => "?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=display_home&current_tab=other-invest",
				"access_role" => "",
				"module_name" => "frontend_modules",
				"dashboard" => 1,
				"dashboard_icon" => "icon-group text-info",
			),
		),
		"main_menu" => array(
			"idhome_mgt" => array(
				"title" => "API Mgt.",
				"class" => "customers",
				"action" => "display_app_view_manage_consultation",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "e_point" => 1, "report_bay" => 1 ),
				"sub_menu" => array(
					'ep_about', "ld_api", "ld_alogs", "json_load", "create_index"
				),
			),
		),
		"sub_menu" => array(
			"ep_about" => array(
				"title" => "About",
				"class" => "nwp_endpoint",
				"action" => "display_plugin_details",
				"development" => 1,
			),
			"ld_alogs" => array(
				"title" =>  "API Logs",
				"class" => "nwp_endpoint",
				"icon" => "mdi mdi-file-document-multiple-outline",
				"sub_menu" => array(
					"ld_log2", "ld_log", "lg_load_log"
				),
			),
			"ld_api" => array(
				"title" =>  "API Endpoints",
				"full_title" =>  "API Endpoints Manager",
				"class" => "nwp_endpoint",
				"action" => "execute&nwp_action=endpoint&nwp_todo=display_all_records_frontend",
				"icon" => "mdi mdi-link-variant"
			),
			"ld_log" => array(
				"title" =>  "Failed API Logs",
				"full_title" =>  "Failed API Logs",
				"class" => "nwp_endpoint",
				"action" => "execute&nwp_action=endpoint_log&nwp_todo=display_all_records_frontend&status=failed",
				"icon" => "mdi mdi-alert-circle-outline"
			),
			"ld_log2" => array(
				"title" =>  "All API Logs",
				"full_title" =>  "API Logs",
				"class" => "nwp_endpoint",
				"action" => "execute&nwp_action=endpoint_log&nwp_todo=display_all_records_frontend",
				"icon" => "mdi mdi-file-document-multiple-outline"
			),
			"spreadsheet" => array(
				"title" =>  "Spreadsheet",
				"prefix" =>  "MANAGE SPREADSHEET - ",
				"full_title" =>  "Spreadsheet",
				"class" => "nwp_endpoint",
				"action" => "execute&nwp_action=react_handler&nwp_todo=manage_spreadsheet",
			),
			"json_load" => array(
				"title" =>  "File Dataload",
				"full_title" =>  "Load Data via",
				"class" => "nwp_endpoint",
				"action" => "execute&nwp_action=react_handler&nwp_todo=manage_fileload",
				"icon" => "mdi mdi-file-upload-outline"
			),
			"create_index" => array(
				"title" =>  "Refresh Data Source",
				"full_title" =>  "Refresh Report Data Source",
				"class" => "nwp_endpoint",
				"action" => "execute&nwp_action=react_handler&nwp_todo=create_index",
				"icon" => "mdi mdi-refresh"
			),
			"lg_load_log" => array(
				"title" =>  "Data Loading Logs",
				"prefix" =>  "MANAGE File DATALOAD - ",
				"full_title" =>  "Bulk Data Loading Logs",
				"class" => "nwp_logging",
				"action" => "execute&nwp_action=log_main_logger&nwp_todo=display_all_records_frontend",
				"icon" => "mdi mdi-progress-download"
			)
		)
	);
	
	public $label = 'cNwp_endpoint';
	public $dir_path = __FILE__;
	
	function nwp_endpoint(){
		
		$returned_value = array();
		
		$this->class_settings['current_module'] = '';
		
		switch ( $this->class_settings['action_to_perform'] ){
			case "display_plugin_details":
			case "get_plugin_details":
				$returned_value = $this->_get_plugin_details();
			break;
			case "execute":
				$returned_value = $this->_execute();
			break;
			case "test":
				$returned_value = $this->_test();
			break;
		}
		
		return $returned_value;
	}
	
	public static function getMaxFileSize(){
		//return acceptable file size in bytes
		if( class_exists('cNwp_app_core') && cNwp_app_core::$def_cs["isTesting"] && cNwp_app_core::$def_cs["setTestParams"] ){
			//8kb
			return 8000;
		}
		//110MB
		return 110000000;
	}
}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>
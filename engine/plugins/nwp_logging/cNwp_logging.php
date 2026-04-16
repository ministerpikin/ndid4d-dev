<?php
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 19:08 | 08-Jul-2023
 */
class cNwp_logging extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'nwp_logging';
	
	public $label = 'Log Management';
	public $dir_path = __FILE__;

	public $plugin_options = array(
		"main_menu" => array(
			"logmgt" => array(
				"title" => "Audit Mgt.",
				"class" => "customers",
				"action" => "display_app_view_manage_consultation",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "e_point" => 1, "report_bay" => 1 ),
				"sub_menu" => array(
					"lg_about", "lg_config", "laudit", "laudit_view", "laudit_daccess", "audit_trail_backup"
				),
			),
		),
		"sub_menu" => array(
			"lg_about" => array(
				"title" => "About",
				"class" => "nwp_logging",
				"action" => "display_plugin_details",
				"development" => 1,
			),
			"lg_config" => array(
				"title" => "Audit Config.",
				"full_title" => "Audit Trail Config.",
				"class" => "nwp_logging",
				"action" => "execute&nwp_action=audit_trail&nwp_todo=audit_config",
				"icon" => "mdi mdi-cog-outline"
			),
			"laudit" => array(
				"title" =>  "System Trail",
				"full_title" =>  "System Audit Trail",
				"class" => "nwp_logging",
				"action" => "execute&nwp_action=audit_trail&nwp_todo=display_all_records_frontend",
				"icon" => "mdi mdi-timeline-check-outline"
			),
			"laudit_view" => array(
				"title" =>  "All Trails",
				"full_title" =>  "All Audit Trails",
				"class" => "nwp_logging",
				"action" => "execute&nwp_action=audit_trail&nwp_todo=manage_data_access_view",
				"icon" => "mdi mdi-timeline-text-outline"
			),
			"laudit_daccess" => array(
				"title" =>  "Data Access",
				"full_title" =>  "Data Access Mgt",
				"class" => "nwp_logging",
				"action" => "execute&nwp_action=audit_trail&nwp_todo=display_data_access_view",
				"icon" => "mdi mdi-database-lock"
			),
			"audit_trail_backup" => array(
				"title" =>  "Back-Ups",
				"full_title" =>  "Back-Ups",
				"class" => "nwp_logging",
				"action" => "execute&nwp_action=audit_trail&nwp_todo=audit_trail_backup",
				"icon" => "mdi mdi-database-arrow-up-outline"
			),
		)
	);
	
	function nwp_logging(){
		
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
		}
		
		return $returned_value;
	}
	
}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>
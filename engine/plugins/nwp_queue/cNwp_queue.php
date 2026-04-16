<?php
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 10:28 | 26-Sep-2023
 */
class cNwp_queue extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'nwp_queue';
	
	public $label = 'Queue';
	public $dir_path = __FILE__;


	public $plugin_options = array(
		"main_menu" => array(
			"qt_home_mgt" => array(
				"title" => "Background Queue.",
				"class" => "customers",
				"action" => "display_app_view_manage_consultation",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "e_point" => 1, "report_bay" => 1 ),
				"sub_menu" => array(
					'q_about', "v_queue", "s_queue",
				),
			),
		),

		"sub_menu" => array(
			"q_about" => array(
				"title" => "About",
				"class" => "nwp_queue",
				"action" => "display_plugin_details",
				"development" => 1,
			),
			"v_queue" => array(
				"title" => "View Queue",
				"class" => "nwp_queue",
				"action" => "execute&nwp_action=job_queue&nwp_todo=display_all_records_frontend",
				"icon" => "mdi mdi-eye-outline"
				// "development" => 1,
			),
			"s_queue" => array(
				"title" => "Queue Settings",
				"class" => "nwp_queue",
				"action" => "execute&nwp_action=job_queue&nwp_todo=queue_config",
				"icon" => "mdi mdi-tune"
				// "development" => 1,
			),

		)
	);
	
	function nwp_queue(){
		
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
			case 'run_job_queue':
				$returned_value = $this->_run_job_queue();
			break;
		}
		
		return $returned_value;
	}

	public function _run_job_queue( array $params ){
		if( $params ){
			$tb = "job_queue";
			$jQ = $this->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) )[ $tb ];
			return $jQ->_run_background_task( $params );			
		}
	}
	
}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>
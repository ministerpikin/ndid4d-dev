<?php
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 22:48 | 05-Jul-2023
 */
class cNwp_reports extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $refresh_cache_after_save_line_items = 0;
	
	public $table_name = 'nwp_reports';

	private $widget_key; //php 8.2 Dynamic Property Notice
	private $end_point_dev; //php 8.2 Dynamic Property Notice
	
	public $label = 'Reports';
	public $dir_path = __FILE__;
	
	public $plugin_options = array(
		"frontend_tabs" => array(
			"report_bay" => array(
				"type" => "merge",	//merge[combine with default submenu], remove[eliminates the menu], replace[overwrites default submenu]
				"title" => "Reports Bay",
				"action" => "?action=all_reports&todo=display_inventory_menu&current_tab=report_bay",
				"access_role" => "d80d45819d34925068980bfcfb44e978",
				"module_name" => "frontend_modules",
				"store_filter" => array(
					"all" => 1,
					//"sub_store" => 1,
				),
			),
		),
		"main_menu" => array(
			"finance" => array(
				"type" => "merge",	//merge[combine with default submenu], remove[eliminates the menu], replace[overwrites default submenu]
				"sub_menu" => array(
					7 => array( "tele_health_pmtx" ),
				),
			),
			"reports_dashboards" => array(
				"title" => "Dashboards",
				"class" => "nwp_reports",
				"action" => "xx",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "report_bay" => 1 ),
				"sub_menu" => array( 
					"indicator_dashboard", "nin_enrolments", "device_mgt2", "device_mgt", "project_dashboard", "report_dashboard2", "report_dashboard3", "social_dashboard", "comm_i_dashboard", "communication_dashboard", "financial_dashboard", "suspicious_dashboard",
				),
			),
			"reports_bay" => array(
				"title" => "Reports Bay",
				"class" => "nwp_reports",
				"action" => "xx",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "report_bay" => 1 ),
				"sub_menu" => array(
					"report_dashboard76",
					"rp_about", "generate_report", "saved_reports", "list_box_options", "report_dashboard", "report_dashboard78", "mis_status", "kibana_frame", "rabbit_mq_frame"
				),
			),
			"access_controlX" => array(
				"title" => "Access Control",
				"class" => "nwp_reports",
				"action" => "xx",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "report_bay" => 1 ),
				"sub_menu" => array(
					"user_accounts", "pending_user_accounts", "revision_history_dashboard", "user_access_roles", "logged_in_usersX", "database_modelX", "database_tablesX", "database_fieldsX"
				),
			),
		),
		"sub_menu" => array(
			"logged_in_usersX" => array(
				"title" => "Auth. Trail",
				"full_title" => "Users Authentication Trail",
				"class" => "logged_in_users",
				"action" => "display_all_records_full_view",
				"icon" => "mdi mdi-fingerprint"
			),
			"audit_trailX" => array(
				"development" => 1,
				"title" => "Audit Trail",
				"class" => "audit",
				"action" => "display_audit_trail_analysis_view",
			),
			"database_fieldsX" => array(
				"development" => 1,
				"title" => "Database Fields",
				"class" => "database_fields",
				"action" => "display_all_records_full_view",
				"development" => 1,
				"icon" => "mdi mdi-table-column-plus-after"
			),
			"database_tablesX" => array(
				"development" => 1,
				"title" => "Database Tables",
				"class" => "database_table",
				"action" => "display_all_records_full_view",
				"icon" => "mdi mdi-table",
				"development" => 1,
			),
			"database_modelX" => array(
				"development" => 1,
				"title" => "Data Model (ERD)",
				"class" => "audit",
				"action" => "display_erd",
				"icon" => "mdi mdi-graph-outline",
				"development" => 1,
			),
			"data_accessX" => array(
				"development" => 1,
				"title" => "Data Access",
				"class" => "audit",
				"action" => "display_data_access_view",
			),
			"generate_report" => array(
				"title" => "Generate Report",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=geenrate_report",
				'development' => 1,
				"icon" => "mdi mdi-file-chart"
			),
			"saved_reports" => array(
				"title" => "Saved Reports",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=saved_reports",
				"icon" => "mdi mdi-file-document-outline"
			),
			"report_dashboard" => array(
				"title" => "Report Dashboard",
				"class" => "nwp_reports",
				// "development" => 1,
				"action" => "execute&nwp_action=reports_bay&nwp_todo=report_dashboard",
				"icon" => "mdi mdi-chart-box-outline"
			),
			"report_dashboard76" => array(
				"title" => "Homepage",
				"class" => "nwp_reports",
				"development" => 1,
				"action" => "execute&nwp_action=reports_ui&nwp_todo=homepage&html_replacement_selector=mis-container&no_side_bar=1",
				"icon" => "mdi mdi-home"
			),

			"report_dashboard78" => array(
				"title" => "Report Mails",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=report_config&nwp_todo=display_all_records_frontend&html_replacement_selector=mis-container",
				"icon" => "mdi mdi-email-outline"
			),

			"mis_status" => array(
				"title" => "MIS Health",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=report_config&nwp_todo=m_and_e_status&html_replacement_selector=mis-container",
				"icon" => "mdi mdi-heart-pulse"
			),

			"kibana_frame" => array(
				"title" => "Kibana",
				"class" => "nwp_endpoint",
				"development" => 1,
				"action" => "execute&nwp_action=react_handler&nwp_todo=kibana_iframe&html_replacement_selector=mis-container",
				"icon" => "mdi mdi-monitor-dashboard"
			),

			"rabbit_mq_frame" => array(
				"title" => "Rabbit MQ",
				"class" => "nwp_endpoint",
				"development" => 1,
				"action" => "execute&nwp_action=react_handler&nwp_todo=rabbit_mq_iframe&html_replacement_selector=mis-container",
				"icon" => "mdi mdi-rabbit"
			),

			"report_dashboard2" => array(
				"title" => "Environmental & Social Safeguards",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=environmental_and_social_safeguard_indicators",
				"icon" => "mdi mdi-leaf"
			),
			"report_dashboard3" => array(
				"title" => "GRM Indicators",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=grm_dashboard",
				"icon" => "mdi mdi-alert-circle-outline"
			),
			"communication_dashboard" => array(
				"title" => "Enrolee Satisfaction and Dissatisfaction",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=comm_dashboard",
				"icon" => "mdi mdi-emoticon-outline"
			),
			"comm_i_dashboard" => array(
				"title" => "Internal / External Communications",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=comm_i_dashboard",
				"icon" => "mdi mdi-message-text-outline"
			),
			"social_dashboard" => array(
				"title" => "Social Accountability Indicators",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=social_dashboard",
				"icon" => "mdi mdi-account-group-outline"
			),
			"suspicious_dashboard" => array(
				"title" => "Suspicious Enrolment Activity",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=suspicious_dashboard",
				"icon" => "mdi mdi-account-alert-outline"
			),
			"financial_dashboard" => array(
				"title" => "Financial Indicators",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=financial_dashboard",
				"icon" => "mdi mdi-currency-usd"
			),
			"project_dashboard" => array(
				"title" => "PDO - NIDB",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=project_dashboard",
				"icon" => "mdi mdi-bank-outline"
			),
			"indicator_dashboard" => array(
				"title" => "Project Development Indicators",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=indicator_dashboard",
				"icon" => "mdi mdi-chart-line"
			),
			"device_mgt2" => array(
				"title" => "Enrolment Devices - Pending",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=device_dashboard2",
				"icon" => "mdi mdi-clock-outline"
			),
			"device_mgt" => array(
				"title" => "Enrolment Devices - Approved",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=device_dashboard",
				"icon" => "mdi mdi-check-circle-outline"
			),
			"nin_enrolments" => array(
				"title" => "Enrolment Report Indicators",
				"class" => "nwp_reports",
				"action" => "execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=nin_enrolments",
				"icon" => "mdi mdi-account-multiple-check"
			),
		),
	);

	public $end_point1 = 'https://webapp.hyella.com.ng/demo/emr-v1.3/engine/api/';
	public $app_key = '';
	public $app_id = '';

	function __construct(){
		Parent::__construct();

		if( defined( 'NWP_V3_ENDPOINT' ) && NWP_V3_ENDPOINT ){
			$this->end_point1 = NWP_V3_ENDPOINT;
			$this->end_point_dev = NWP_V3_ENDPOINT;
		}

		$this->app_key = get_websalter();
		$this->widget_key = $this->app_key;
		if( defined( 'NWP_V3_APP_SECRET' ) && NWP_V3_APP_SECRET ){
			$this->app_key = NWP_V3_APP_SECRET;
		}
		$this->app_key = md5( $this->app_key );

		if( defined( 'NWP_V3_APP_ID' ) && NWP_V3_APP_ID ){
			$this->app_id = NWP_V3_APP_ID;
		}
	}

	private static function hashPasswordForNinVerification( $password ){
		$p = "";
		$e = "113621440243785421499955306133900099987164309503876199371900611085975699194905621710442876441889195302451922443555354266645737454327409509639333989384262385729949578624044207610948821627355876693570108394899808569346703874513552461157771585312437842555207875241788331401870311503661882350734256428011446552231";
		$m = "99656440840574176563305385521896948249485597887868788305755844436736813735716889384156081404108856785411701458057572807701609821377138238971482595936817351313377639458003034637351529602924774615106031875065736828376549082962569871367654360928995574432638495308492887000005021125506027838956077501182295786099";

		if( $password ){
			$hash = hash("sha256", $password);
			$hash = bin2hex( $hash );
			$hash = gmp_init($hash, 16);
			$e = gmp_init( $e );
			$m = gmp_init( $m );
			$pm = gmp_powm($hash, $e, $m);
			$p = base64_encode( gmp_strval( $pm ) );
		}
		return $p;
	}

	private static function retrieveNIN( $nin ){
		$error = "<h4><b>Unknown Error</b></h4><p>There's has been a technical error retrieving NIN</p>";
		$username = 'usertest1a';
		$password = 'abc12345';
		$orgid = '1101';

		$url = defined("NVS_SERVER_URL") && NVS_SERVER_URL ? NVS_SERVER_URL : 'http://10.254.253.19:8080/nvs/IdentitySearch?wsdl';
		$username = defined("NVS_SERVER_USERNAME") && NVS_SERVER_USERNAME ? NVS_SERVER_USERNAME : $username;
		$password = defined("NVS_SERVER_PASSWORD") && NVS_SERVER_PASSWORD ? NVS_SERVER_PASSWORD : $password;
		$orgid = defined("NVS_SERVER_ORGID") && NVS_SERVER_ORGID ? NVS_SERVER_ORGID : $orgid;

		$client = new SoapClient( $url );
		
		$npassword = self::hashPasswordForNinVerification( $password );
		
		$response = $client->createTokenString( array(
			'username' => $username,
			'password' => $npassword,
			'orgid' => $orgid
		) );

		$token  = isset( $response->return ) && $response->return && (base64_decode($response->return, true) !== false) ? $response->return : '';
		
		if( $token ){
			$response = $client->searchByNIN( array(
				'token' => $token,
				'nin' => $nin
			) );

			if( isset($response->return->data->nin) && $response->return->data->nin ){
				return json_encode( $response->return->data);
			}else{
				$error = "<h4><b>Invalid NIN</b></h4><p>NIN Details does not exist</p>";
			}
		}else{
			$error = "<h4><b>Invalid Operation</b></h4><p>Unable to authenticate and generate token</p>";
		}

		return array( 'error' => $error, 'response' => $response );
	}

	public static function validateNin( $params = array() ){

		if( $params ){

			if( isset( $params['nin'] ) && $params['nin'] ){
				
				try{
					
					$a = self::retrieveNIN( $params['nin'] );
					
					$a = is_string( $a ) ? json_decode( $a, true ) : $a;

					if( is_array( $a ) && isset( $a['nin'] ) && $a['nin'] ){
						return $a;
					}else{
						if( isset($a['error']) && $a['error'] ){
							$error = $a['error'];
						}else{
							$error = "<h4><b>NIN Details Unavailable</b></h4><p>Provided NIN Details do not exist</p>";
						}
						$access = get_accessed_functions();
						$super = !is_array( $access ) && $access == 1 ? 1 : 0;
						if( $super || (isset( $access['accessible_functions'][ 'users.nvs_api.nvs_api' ] ) && $access['accessible_functions'][ 'users.nvs_api.nvs_api' ]) ){
							$dd = json_encode( $a['response'], JSON_PRETTY_PRINT );
							$error .= '<details>';
								$error .= "<summary>NVS Response / Details</summary>";
								$error .= '<textarea class="form-control" style="height:10em;" readonly>'; 
									$error .= $dd ; 
								$error .= '</textarea>';
							$error .= '</details>';							
						}
					}

				}catch (Exception $e){
					$error = "<h4><b>NIMC Verification Failed</b></h4><p>NIN Verification Failed.</p><p>". $e->getMessage() ."</p>";
				}

			}else{
				$error = "<h4><b>Invalid Parameters</b></h4><p>NIN is missing</p>";
			}

			return [ 'error' => $error ];
		}
	}
	
	function nwp_reports(){
		
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

	public function get_endpoint( $opt = array() ){
		$rtype = isset( $opt[ 'request_type' ] ) ? $opt[ 'request_type' ] : '';
		$etype = isset( $opt[ 'endpoint_type' ] ) ? $opt[ 'endpoint_type' ] : '';
		$custom_get = isset( $opt[ 'custom_get' ] ) ? $opt[ 'custom_get' ] : '';
		$hash = isset( $opt[ 'hash' ] ) ? $opt[ 'hash' ] : null;

		$ep = $this->end_point1;

		$rand = rand();

		$input = array();
		$input['get'] = $custom_get;
		$input['get']['app_secret'] = $this->app_key;
		$input['get']['token'] = $rand;
		$input['get']['public_key'] = md5( $rand . $input['get']['app_secret'] );
		$input['get']['cid'] = $this->app_id;

		if( $hash && ! empty( $hash ) ){
			$hask_key = '';
			$hh = $hash;
			foreach( $hh as $hk => $hv ){
				if( isset( $input[ 'get' ][ $hk ] ) && $input[ 'get' ][ $hk ] ){
					$hask_key .= $input[ 'get' ][ $hk ];
				}
			}
			$hask_key = md5( $hask_key );
			$hash_value = md5( $hask_key . get_websalter() );

			$input['get']['nwp_hash'] = get_file_hash( [ "hash" => 1, "file_id" => $hash_value, "no_session" => 1 ] );
			$input['get']['nwp_hash_id'] = $hask_key;
		}

		$output = array(
			'end_point_type' => $etype,
			'end_point' => $ep,
			'request_type' => $rtype,
			'input' => $input,
		);
		
		$url = $ep.get_report_endpoint( $output );
		$url .= $this->report_endpoint_action;

		return $url;
	}

	public $report_endpoint_action = '&daction=nwp_reports&dtodo=execute&dnwp_action=reports_bay&dnwp_todo=exec_report_url';
	
}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>

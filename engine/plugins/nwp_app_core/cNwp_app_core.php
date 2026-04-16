<?php
//MandE merge started 02-oct-23
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 17:29 | 08-Mar-2021
 */

// class cNwp_app_core extends cPlugin_class{
class cNwp_app_core {
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'nwp_app_core';
	
	public $label = 'App Core';
	public $dir_path = __FILE__;

	public static $def_cs = [];
	public static $def_cs_active = []; //@steve 24-01-2023
	
	public $plugin_options = array();

	public static $laravelENV = array(
		'DATABASE_NAME' => 'database.connections.mysql.database',
		'DATABASE_HOST' => 'database.connections.mysql.host',
		'DATABASE_PASSWORD' => 'database.connections.mysql.password',
		'DATABASE_USER' => 'database.connections.mysql.username',
		'DB_PORT' => 'database.connections.mysql.port',
		'DEVELOPMENT_MODE' => 'app.debug',
		'NWP_APP_TITLE' => 'app.name',
		'NWP_APP_VERSION' => 'app.version',
		//'MAINTENANCE_MODE' => 'database.connections.mysql.port',
	);

	public static function app( $opt = [] ){
		
		$pagepointer = isset( $opt[ 'pagepointer' ] ) ? $opt[ 'pagepointer' ] : '';
		$defaultUser = isset( $opt[ 'defaultUser' ] ) ? $opt[ 'defaultUser' ] : '';
		$returnObject = isset( $opt[ 'returnObject' ] ) ? $opt[ 'returnObject' ] : false;
		$loggedIn = isset( $opt[ 'loggedIn' ] ) ? $opt[ 'loggedIn' ] : false;
		$isTesting = isset( $opt[ 'isTesting' ] ) ? $opt[ 'isTesting' ] : false;
		$setTestParams = isset( $opt[ 'setTestParams' ] ) ? $opt[ 'setTestParams' ] : false;
		$openConnection = isset( $opt[ 'openConnection' ] ) ? $opt[ 'openConnection' ] : false;
		$skip_headers = isset( $opt['skip_headers'] ) ? $opt['skip_headers'] : false;

		self::$def_cs[ 'calling_page' ] = $pagepointer;
		self::$def_cs[ 'isTesting' ] = $isTesting;
		self::$def_cs[ 'setTestParams' ] = $setTestParams;
		
		$http_origin = isset( $_SERVER['HTTP_ORIGIN'] )?$_SERVER['HTTP_ORIGIN']:'';

		if( !$skip_headers ){
			switch ( $http_origin ){  
			case "http://hotel.hyella.com":
			case "http://localhost":
			case "http://192.168.43.41":
			case "https://192.168.43.41":
			case "https://webapp.hyella.com.ng":
			case "http://localhost:819":
			case "http://192.168.8.113:819":
			case "http://floramartins.com":
			case "https://floramartins.com":
				header("Access-Control-Allow-Origin: $http_origin");
			break;
			default:
				header("Access-Control-Allow-Origin: *");
			break;
			}
			
			
		    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		    header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Allow-Headers: origin, x-requested-with, content-type');			
		}

		
		$GLOBALS["mobile_framework"] = isset( $_POST["mobile_framework"] )?$_POST["mobile_framework"]:'';
		
	    if( isset( $_GET['browser'] ) && $_GET['browser'] == 'ie' ){
	        
	        if( isset( $_GET['iefixsid'] ) && $_GET['iefixsid'] )
	            $_COOKIE["PHPSESSID"] = $_GET['iefixsid'];
	        
	        $postmethod = 1;
	        if( isset( $_GET['formmethod'] ) && $_GET['formmethod'] == 'get' )$postmethod = 0;
	        
	        if( isset( $HTTP_RAW_POST_DATA ) && $HTTP_RAW_POST_DATA ){
	            $post = explode('&',$HTTP_RAW_POST_DATA);
	            foreach( $post as $pv ){
	                $p = explode('=',$pv);
	                if( isset( $p[1] ) ){
	                    $key = urldecode($p[0]);
	                        if( $postmethod )
	                            $_POST[ $key ] = urldecode($p[1]);
	                        else
	                            $_GET[ $key ] = urldecode($p[1]);
	                }
	            }
	        }
	        
	        if( isset( $_GET['ietokenfix'] ) && $_GET['ietokenfix'] ){
	            $_POST['processing'] = $_GET['ietokenfix'];
	            //define('SKIP_USE_OF_FORM_TOKEN', 1);
	        }
	    }
	    
		if( isset( $_GET[ 'nwp_silentr' ] ) && $_GET[ 'nwp_silentr' ] ){
	    	$GLOBALS[ 'nwp_silentr' ] = $_GET[ 'nwp_silentr' ];
	    	$_GET[ 'default' ] = 'default';
	    	$background_data_upload_only_session = 1;
	    	$_SESSION[ 'key' ] = 'DeNi87^7g^%^$Crtvfgxz32vb.:sd_';
			unset( $_GET[ 'nwp_silentr' ] );
	    }

		//CONFIGURATION
		if( ! ( isset( $skip_required_files ) && $skip_required_files ) ){
			//if( ! isset( $pagepointer ) )$pagepointer = '../';
			if( ! isset( $pagepointer ) )$pagepointer = dirname( dirname( __FILE__ ) ) . '/';
	        $display_pagepointer = '';

			self::db_connect( $opt );
		    $database_connection = self::$def_cs[ 'database_connection' ];
		    $database_name = self::$def_cs[ 'database_name' ];

			require_once $pagepointer . "settings/Setup.php";
			// require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/doctrine/doctrine.php';

	        if( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] ){
	            $s = explode("/", $_SERVER['REQUEST_URI'] );
	            $pr = get_project_data();
	            foreach( $s as $ss ){
	                if( $ss && ! strrchr( $ss, '?' ) ){
	                    $display_pagepointer = $pr['domain_name'] . 'engine/';
	                    break;
	                }
	            }
	        }
		}
		
		if( defined("NWP_EXPIRED_LICENSE") && NWP_EXPIRED_LICENSE ){
			$return = array(
				"err" => "Expired Subscription",
				"msg" => NWP_EXPIRED_LICENSE,
				"theme" => "note note-danger alert alert-danger",
				"typ" => "uerror",
				
				'status' => 'new-status',
				'message' => 'Expired Subscription',
			);
			$website_object = json_encode( $return );
			echo $website_object; exit;
		}
		
		
		/*
		if( isset( $_GET["action"] ) && $_GET["action"] == "development" ){
			if( isset( $_SESSION["abx"] ) )echo $_SESSION["abx"];
			//$_SESSION["abx"] = 1;
			echo 'a';
			exit;
		}
		*/
		if( ! $isTesting ){
			if( ( self::isDevelopmentMode() && ( defined("NWP_PERFORMANCE_LOG") && NWP_PERFORMANCE_LOG ) || ( defined("NWP_LIVE_TESTING") && NWP_LIVE_TESTING ) ) ){
				if( isset( $_GET['action'] ) && $_GET['action'] ){
					$logx["mem_start"] = memory_get_usage();
					$logx["start"] = microtime(true);
					$dtt = date("U");
					$logx["name"] = $_GET['action'] . '-' . $_GET["todo"] .'-' . $dtt . '.json';
					$logx["data"] = array( "get" => $_GET, "post" => $_POST, "time" => $dtt );
				}
			}
		}
		
		if( isset( $_GET['nwp_target'] ) && $_GET['nwp_target'] ){
			$GLOBALS["nwp_target"] = $_GET;
			$_GET = array();
			$_GET['action'] = 'audit';
			$_GET['todo'] = 'nwp_target';
		}
		
		
		$page = 'ajax_request_processing_script';
		
		if( isset($classes[ $page ]) && $classes[ $page ] ){ //@steve phpunit error : undefined variable
			//INCLUDE CLASSES
			$class = $classes[$page];
							// print_r( $package_config );exit;
			//sleep(10);
			$existing_class = array();
			foreach( $class as $required_php_file ){
				if( file_exists( $pagepointer . "classes/" . $required_php_file . ".php" ) ){
					require_once $pagepointer . "classes/" . $required_php_file . ".php";
					$existing_class[ $required_php_file ] = 1;
				}else{
					//echo $required_php_file; exit;//$class; 
					log_m( array( "missing_file" => $required_php_file ) );
				}
			}			
		}
		
		if( isset( self::$def_cs[ 'package_config' ]["plugins_loaded"] ) && is_array( self::$def_cs[ 'package_config' ]["plugins_loaded"] ) && ! empty( self::$def_cs[ 'package_config' ]["plugins_loaded"] ) ){
			foreach( self::$def_cs[ 'package_config' ]["plugins_loaded"] as $rk => $required_php_file ){
				if( file_exists( $pagepointer . "plugins/" . $rk . "/" . $required_php_file . ".php" ) ){
					$GLOBALS["plugins"][ $rk ] = 'c'.ucwords( $rk );
					//$GLOBALS["plugin_classes"] = $classes;
					require_once $pagepointer . "plugins/" . $rk . "/" . $required_php_file . ".php";
				}else{
					//echo $required_php_file; exit;//$class; 
					log_m( array( "missing_file" => $required_php_file ) );
				}
			}
		}
		
		if( isset( self::$def_cs[ 'nwp_package_class' ] ) && ! isset( self::$def_cs[ 'nwp_package_class' ][$page] ) && ! empty( self::$def_cs[ 'nwp_package_class' ] ) ){
			self::$def_cs[ 'nwp_package_class' ][$page] = self::$def_cs[ 'nwp_package_class' ];
		}
		
		if( isset( self::$def_cs[ 'nwp_package_class' ] ) && isset( self::$def_cs[ 'nwp_package_class' ][$page] ) && is_array( self::$def_cs[ 'nwp_package_class' ][$page] ) && ! empty( self::$def_cs[ 'nwp_package_class' ][$page] ) ){
			foreach( self::$def_cs[ 'nwp_package_class' ][$page] as $required_php_file ){
				if( ! isset( $existing_class[ $required_php_file ] ) ){
					if( file_exists( $pagepointer . "classes/" . $required_php_file . ".php" ) ){
						require_once $pagepointer . "classes/" . $required_php_file . ".php";
						$GLOBALS["classes"][ 'ajax_request_processing_script' ][] = $required_php_file;
						$existing_class[ $required_php_file ] = 1;
					}else{
						//echo $required_php_file; exit;//$class; 
						log_m( array( "missing_package_file" => $required_php_file ) );
					}
				}
			}
			unset( $existing_class );
		}
		
		if( isset( $_POST["nw_p_action"] ) && $_POST["nw_p_action"] && isset( $_POST["nw_p_todo"] ) && $_POST["nw_p_todo"] ){
			if( ! ( (isset($_GET['action']) && isset( $_GET['todo'] )) && $_GET['todo'] && $_GET['action'] ) ){
				$_GET['action'] = $_POST["nw_p_action"];
				$_GET['todo'] = $_POST["nw_p_todo"];
				
				$audit_params = $_POST;
				auditor("", "rebuild_cache", "rebuild_cache", $audit_params );
				
				set_time_limit( 90 );
			}
		}
		
		
		if( function_exists("check_for_idle_timeout") && check_for_idle_timeout() ){
			auditor("", "login", "idle_logout", array( "comment" => "idle session logout" ) );
			$_GET['action'] = 'users';
			$_GET['todo'] = 'sign_out';	//not yet coded
		}
		
		$website_object = '';
		$skip_authentication = false;
		
		//check for logged in
		if( ( isset( $_GET['default'] ) && $_GET['default'] == "default" ) || $loggedIn ){
			
			if( $defaultUser ){
				$u = get_record_details( array( "id" => $defaultUser, "table" => "users" ) );
			}

			$current_user_session_details = array(
				'id' => ($defaultUser?$defaultUser:( ( defined("SET_DEFAULT_USER") && SET_DEFAULT_USER )?SET_DEFAULT_USER:'system' ) ),
				//'id' => ( ( defined("SET_DEFAULT_USER") && SET_DEFAULT_USER )?SET_DEFAULT_USER:'system' ),
				'email' => isset( $u["email"] ) ? $u["email"] :'system',
				'fname' => isset( $u["firstname"] ) ? $u["firstname"] :'system',
				'lname' => isset( $u["lastname"] ) ? $u["lastname"] :'system',
				'privilege' => 'system',
				'login_time' => 'system',
				'verification_status' => 'system',
				'remote_user_id' => 'system',
				'country' => 'system',
			);
			$skip_authentication = true;
		}
		
		if( defined("SET_USER") && SET_USER ){
			$u = get_record_details( array( "id" => SET_USER, "table" => "users" ) );
			
			$current_user_session_details = array(
				'id' => SET_USER,
				'email' => isset( $u["email"] )?$u["email"]:'system',
				'fname' => isset( $u["firstname"] )?$u["firstname"]:'system',
				'lname' => isset( $u["lastname"] )?$u["lastname"]:'system',
				'privilege' => 'system',
				'login_time' => 'system',
				'verification_status' => 'system',
				'remote_user_id' => 'system',
				'country' => 'system',
			);
			$skip_authentication = true;
		}
		
		switch( $GLOBALS["mobile_framework"] ){
		case "framework7":
			define( 'HYELLA_MOBILE', $GLOBALS["mobile_framework"] );
			$skip_authentication = true;
		break;
		default:
			$auth_check = 1;
			if( ! ( isset( $_GET['action'] ) && $_GET['action'] ) )$_GET[ 'action' ] = '';
				
			switch( $_GET['action'] ){
			case "travel_pricing":
			case "travel_bookings":
			case "online_payment":
			case "authentication":
			case "appsettings":
			case "nwp_nape":
				$auth_check = 0;
			break;
			case "mobile_app":
				switch( $_GET['todo'] ){
				case 'authenticate_license':
				case 'check_for_license':
					$auth_check = 0;
				break;
				}
			break;
			case "stores":
				switch( $_GET['todo'] ){
				case 'get_stores_list':
					$auth_check = 0;
				break;
				}
			break;
			case "users":
				switch( $_GET['todo'] ){
				case 'authenticate_employee':
				case 'users_registration':
				
				case 'authenticate_app_mobile':
				case 'authenticate_app':
				case 'reset_password_app_mobile':
				
				case 'app_check_logged_in_user':
				case 'app_check_logged_in_user4':
				case 'app_check_logged_in_user3':
				case 'app_check_logged_in_user2':
				case 'app_check_logged_in_user':
				case 'app_check_logged_in_user5':
				
				case "sign_out":
				case "lock_account":
					$auth_check = 0;
				break;
				}
			break;
			}
			
			if( isset( $_GET["nwp_action"] ) ){
				switch( $_GET["nwp_action"] ){
				case "npf_dashboard":
					$auth_check = 0;	//npf hack for mobile : temp measure correct later
				break;
				case "shop_order":
					if( isset( $_GET["nwp_todo"] ) ){
						switch( $_GET["nwp_todo"] ){
						case "online_acces2":
							$auth_check = 0;
						break;
						}
					}
				break;
				case "shop_item":
					if( isset( $_GET["nwp_todo"] ) ){
						switch( $_GET["nwp_todo"] ){
						case "get_website_data":
							$auth_check = 0;
						break;
						}
					}
				break;
				case "odk_frontend_users":
					if( isset( $_GET["nwp_todo"] ) ){
						switch( $_GET["nwp_todo"] ){
						case "save_reg_finish":
						case "save_signup_form":
							$auth_check = 0;
						break;
						}
					}
				break;
				}
			}
			
			if( $auth_check ){
				//print_r( $current_user_session_details ); exit;
				if( ! ( isset( $current_user_session_details["id"] ) && $current_user_session_details["id"] ) ){
					$return = array(
						'html' => '1',
						'method_executed' => "1",
						'appsettings_properties' => "1",
						'status' => 'reload-page',
						'message' => 'Super administrator account already set',
					);
					if( isset( $_GET["nwp_rurl"] ) && $_GET["nwp_rurl"] ){
						$return = array(
							"status" => "new-status",
							"redirect_url" => $_GET["nwp_rurl"],
							"message" => "You are not logged in",
						);
					}
					$website_object = json_encode( $return, JSON_PRETTY_PRINT );
				}
			}else{
				$skip_authentication = true;
			}
				
			
		break;
		}
		
		// print_r( $current_user_session_details );
		if( ! $website_object ){
			if((isset($_GET['action']) && isset($_GET['todo'])) && $_GET['todo'] && $_GET['action']){
				$classname = $_GET['action'];
				$action = $_GET['todo'];
				
				$development = isset( $_GET["development_mode_off"] )?$_GET["development_mode_off"]:0;
				
				$frontend = isset( $_GET["frontend"] )?$_GET["frontend"]:'';
				$branch = ( isset( $_GET["branch"] ) && $_GET["branch"] && $_GET["branch"] != '-' )?$_GET["branch"]:'';
				$current_store = ( isset( $_GET["current_store"] ) && $_GET["current_store"] && $_GET["current_store"] != '-' )?$_GET["current_store"]:'';
				$container = isset( $_GET["html_replacement_selector"] )?$_GET["html_replacement_selector"]:'';
				$pcontainer = isset( $_GET["phtml_replacement_selector"] )?$_GET["phtml_replacement_selector"]:'';
				if(isset($_GET['module']))$_SESSION['module'] = $_GET['module'];
				
				$settings = array(
					'display_pagepointer' => $display_pagepointer,
					'pagepointer' => $pagepointer,
					'user_cert' => $current_user_session_details,
					'database_connection' => $database_connection,
					'database_name' => $database_name, 
					'classname' => $classname, 
					'action' => $action,
					'language' => SELECTED_COUNTRY_LANGUAGE,
					'debug' => isset( $global_debug )?$global_debug:'',
					'running_in_background' => isset( $skip_background_upload )?$skip_background_upload:0,
					'html_replacement_selector' => $container,
					'phtml_replacement_selector' => $pcontainer,
					'current_store' => $current_store,
					'branch' => $branch,
					'frontend' => $frontend,
					'nwp_action' => isset( $_GET["nwp_action"] )?$_GET["nwp_action"]:'',
					'nwp_todo' => isset( $_GET["nwp_todo"] )?$_GET["nwp_todo"]:'',
					'nwp_repro' => isset( $_GET["nwp_repro"] )?$_GET["nwp_repro"]:'',
					'development_mode_off' => $development,
					'skip_authentication' => $skip_authentication,
				);

				if( isset( $entityManager ) && $entityManager ){
			
					// print_r( $entityManager );exit;

					// $userRepository = $entityManager->getRepository(cCustomers::class);
					// print_r( $userRepository );exit;

					// $_SESSION['doctrineORM'] = $entityManager;
				}

				if( defined("HYELLA_MOBILE") && HYELLA_MOBILE ){
					$settings["mobile"] = HYELLA_MOBILE;
				}
				
				$_SESSION['reuse_settings'] = $settings;
				//sleep(15);

				$website_object = self::_reuse_class( $settings ); // @steve required $cls settings to be static for Job queue workers
				if( ! $openConnection ){
					if( isset( $database_connection ) && $database_connection ){
						mysqli_close( $database_connection );
					}
				}
			}
		}
		
		if( $returnObject ){
			return $website_object;
		}
		
		if( ! ( isset( $return_website_object ) && $return_website_object ) ){
			//header("Content-Type: application/json; charset-utf-8");
			//header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
			if( isset( $_GET[ 'pretty_print' ] ) && $_GET[ 'pretty_print' ] ){
				echo '<pre>';
			}
			if( gettype( $website_object ) == 'object' ){
				$website_object = json_encode( get_object_vars( $website_object ) );
			}
			echo $website_object;
			if( isset( $_GET[ 'pretty_print' ] ) && $_GET[ 'pretty_print' ] ){
				echo '</pre>';
			}
		}

		if( isset( $logx["name"] ) ){
			$logx["end"] = microtime(true);
			$logx["mem_end"] = memory_get_usage();
			$logx["output"] = $website_object;

			if( ! is_dir( $pagepointer.'tmp/request-log/' ) ){
				create_folder( $pagepointer.'tmp/request-log/', '', '' );
			}
			
			file_put_contents( $pagepointer.'tmp/request-log/'. $logx["name"], json_encode( $logx ) );
		}

	}

	// @steve required $cls settings to be static for Job queue workers
	public static function _reuse_class( $settings = array() ){
		
		$nwp_hash = nwp_request_hash_key( array( "test" => 1 ) );
		$error_msg = '';
		$error_status = '';
		
		$pagepointer = '';
		if( isset( $settings[ 'pagepointer' ] ) )
			$pagepointer = $settings[ 'pagepointer' ];
		
		$display_pagepointer = '';
		if( isset( $settings[ 'display_pagepointer' ] ) )
			$display_pagepointer = $settings[ 'display_pagepointer' ];
		
		$user_cert = array();
		if( isset( $settings[ 'user_cert' ] ) )
			$user_cert = $settings[ 'user_cert' ];
		
		if( function_exists("__logged_in_users_validate") && ! ( isset( $settings[ 'skip_authentication' ] ) && $settings[ 'skip_authentication' ] ) ){
			if( ! __logged_in_users_validate( $user_cert, session_id() ) ){
				$error_msg = '<h4><strong>Expired Session</strong></h4><p>Please login to your account</p>';
				$_SESSION = array();
				$error_status = "reload-page";
			}
		}
		
		$database_connection = '';
		if( isset( $settings[ 'database_connection' ] ) )
			$database_connection = $settings[ 'database_connection' ];
			
		$database_name = '';
		if( isset( $settings[ 'database_name' ] ) )
			$database_name = $settings[ 'database_name' ];
			
		$classname = '';
		if( isset( $settings[ 'classname' ] ) )
			$classname = $settings[ 'classname' ];
		
		$action = '';
		if( isset( $settings[ 'action' ] ) )
			$action = $settings[ 'action' ];
			
		$language = '';
		if( isset( $settings[ 'language' ] ) )
			$language = $settings[ 'language' ];
			
		$debug = '';
		if( isset( $settings[ 'debug' ] ) )
			$debug = $settings[ 'debug' ];
			
		$running_in_background = '';
		if( isset( $settings[ 'running_in_background' ] ) )
			$running_in_background = $settings[ 'running_in_background' ];

		if( ! in_array( $user_cert[ 'privilege' ], array( '1300130013', 'system' ) ) && get_hyella_development_mode() && ! ( defined( 'MAINTENANCE_MODE' ) && intval( MAINTENANCE_MODE ) ) ){
			$abc = 'System';
			$de = ' in';
			$fij = ' Maintenance';
			$error['typ'] = 'serror';
			$error['err'] = '<h4><strong>'. $abc.$de.$fij .'</strong></h4>';
			$error['msg'] = '<p>Please contact your support team</p>';
			$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
				$error['html'] .= '<h3><strong>Undefined Class</strong></h3>';
				$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
			$error['html'] .= '</div>';
			
			return json_encode($error);
		}
		
		if( $error_msg ){
			$error = array();
			$error['typ'] = 'serror';
			$error['err'] = '';
			$error['msg'] = $error_msg;
			$error['html'] = $error_msg;
			if( $error_status ){
				$error['status'] = $error_status;
			}
			return json_encode($error);
		}

		$log_history = array();
		if( defined( 'ACTIVATE_BROWSER_HISTORY' ) && ACTIVATE_BROWSER_HISTORY ){
			// Get request with current_tab or get_children
			if( isset( $_GET[ 'is_menu' ] ) && $_GET[ 'is_menu' ] ){
				$log_history[ 'data' ][ 'post' ] = $_POST;
				$log_history[ 'data' ][ 'get' ] = $_GET;
			}
		}
		
		$first_request = 0;
		switch( $classname ){
		case "users":
			switch( $action ){
				case "app_check_logged_in_user2":
					$first_request = 1;
				break;
			}
		break;
		}

		//Check for Permission
		//echo permission($userid,$action,$classname,$action,$database_connection,$database_name);
		if( $nwp_hash || ( isset( $settings[ 'skip_authentication' ] ) && $settings[ 'skip_authentication' ] ) ){
			
			$callStartTime = microtime(true);
			
			$cls = array(
				'database_connection' => $database_connection,
				'database_name' => $database_name,
				'calling_page' => $pagepointer,
				'calling_page_display' => $display_pagepointer,
				
				'user_id' => $user_cert['id'],
				'user_full_name' => $user_cert['fname'] . ' ' . $user_cert['lname'],
				'user_fname' => $user_cert['fname'],
				'user_lname' => $user_cert['lname'],
				'user_email' => $user_cert['email'],
				'priv_id' => $user_cert['privilege'],
				'photograph' => isset( $user_cert['photograph'] )?$user_cert['photograph']:'',
				'user_store' => isset( $user_cert['store'] )?$user_cert['store']:"",
				'user_dept' => isset( $user_cert['department'] )?$user_cert['department']:"",
				'user_plugin' => isset( $user_cert['plugin'] )?$user_cert['plugin']:"",
				'user_table' => isset( $user_cert['table'] )?$user_cert['table']:"",
				'user_role' => isset( $user_cert['role'] )?$user_cert['role']:"",
				'force_password_change' => isset( $user_cert['force_password_change'] )?$user_cert['force_password_change']:"",
				'verification_status' => $user_cert[ 'verification_status' ],
				'remote_user_id' => $user_cert[ 'remote_user_id' ],
				'html_replacement_selector' => isset( $settings[ 'html_replacement_selector' ] )?$settings[ 'html_replacement_selector' ]:'',
				'phtml_replacement_selector' => isset( $settings[ 'phtml_replacement_selector' ] )?$settings[ 'phtml_replacement_selector' ]:'',
				
				'action_to_perform' => $action,
				
				'running_in_background' => $running_in_background,
				'debug' => $debug,
				'language' => $language,
			);

			if( isset( $settings[ 'doctrineORM' ] ) && $settings[ 'doctrineORM' ] ){
				// $cls[ 'doctrineORM' ] = $settings[ 'doctrineORM' ];
			}
			
			$GLOBALS["user_cert"] = $user_cert;
			
			$audit_params = array( "class" => $classname, "action" => $action );
			
			if( isset( $settings[ 'nwp_action' ] ) && $settings[ 'nwp_action' ] && isset( $settings[ 'nwp_todo' ] ) && $settings[ 'nwp_todo' ] ){
				$cls[ 'nwp_action' ] = $settings[ 'nwp_action' ];
				$cls[ 'nwp_todo' ] = $settings[ 'nwp_todo' ];
				
				$audit_params[ 'nwp_todo' ] = $settings[ 'nwp_todo' ];
				$audit_params[ 'nwp_action' ] = $settings[ 'nwp_action' ];
			}
			
			$cp = $audit_params;
		
			if( isset( $settings[ 'current_store' ] ) && $settings[ 'current_store' ] ){
				$GLOBALS[ 'current_store' ] = $settings[ 'current_store' ];
				$cls[ 'current_store' ] = $settings[ 'current_store' ];
			}
			
			if( isset( $settings[ 'branch' ] ) && $settings[ 'branch' ] ){
				$GLOBALS[ 'branch' ] = $settings[ 'branch' ];
				set_current_customer( array( "branch" => $settings[ 'branch' ] ) );
				$cls[ 'branch' ] = $settings[ 'branch' ];
			}
		
			if( isset( $settings[ 'frontend' ] ) && $settings[ 'frontend' ] ){
				$GLOBALS[ 'frontend' ] = $settings[ 'frontend' ];
				$cls[ 'frontend' ] = $settings[ 'frontend' ];
			}
		
			if( isset( $settings[ 'mobile' ] ) && $settings[ 'mobile' ] ){
				$cls[ 'mobile' ] = $settings[ 'mobile' ];
			}else{
				//$cls[ 'nw_mobile_browser' ] = is_mobile();
			}
			nw_set_breadcrum( array( "source" => "reuse_class" ) );
		
			$cp["class_settings"] = $cls;
			
			//$cs = '';//check_second_level_authentication( $cp );
			$cs = check_second_level_authentication( $cp );
			if( isset( $cs["action"] ) && $cs["action"] && isset( $cs["todo"] ) && $cs["todo"] ){
				$classname = $cs["action"];
				$action = $cs["todo"];
				
				$cls["action_to_perform"] = $action;
			}
			
			//set page title = 11-jan-23
			$page_title = '';
			if( isset( $_GET[ 'title' ] ) && $_GET[ 'title' ] ){
				$page_title = rawurldecode( $_GET[ 'title' ] );
			}else if( isset( $_GET[ 'menu_title2' ] ) && $_GET[ 'menu_title2' ] ){
				$page_title = rawurldecode( $_GET[ 'menu_title2' ] );
			}else if( isset( $_GET[ 'menu_title' ] ) && $_GET[ 'menu_title' ] ){
				$page_title = rawurldecode( $_GET[ 'menu_title' ] );
			}
			
			$actual_name_of_class = 'c'.ucwords($classname);
			
			if( class_exists( $actual_name_of_class ) ){
				$module = new $actual_name_of_class();
				
				$module->class_settings = $cls;

				self::$def_cs_active = $cls;
				
				$data = $module->$classname();	
				
				if( isset( $module->plugin_hooks[ $cls[ 'action_to_perform' ] ][ 'type' ] ) && $module->plugin_hooks[ $cls[ 'action_to_perform' ] ][ 'type' ] ){
						// print_r( 'mike' );exit();
					switch( $module->plugin_hooks[ $cls[ 'action_to_perform' ] ][ 'type' ] ){
					case 'process':

						$ph = $module->plugin_hooks[ $cls[ 'action_to_perform' ] ];
						
						$rd = add_nwp_plugin_options( array( "type" => $cls[ 'action_to_perform' ], "class_settings" => $module->class_settings, "params" => array( "class_data" => $data ) ) );

						if( ! empty( $rd ) ){
							$data = $rd;
						}

					break;
					}

				}
				
				if( isset( $GLOBALS["nwp_full_text_search"] ) && ! empty( $GLOBALS["nwp_full_text_search"] ) ){
					if( class_exists( "cNwp_full_text_search" ) ){
						$fts = new cNwp_full_text_search();
						$fts->class_settings = $cls;
						$fts->class_settings['tables'] = $GLOBALS["nwp_full_text_search"];
						$fts->class_settings['action_to_perform'] = 'execute';
						$fts->class_settings['nwp_action'] = 'index_queue';
						$fts->class_settings['nwp_todo'] = 'add_to_index_queue';
						$fts->nwp_full_text_search();
					}
					unset( $GLOBALS["nwp_full_text_search"] );
				}
				
				$callEndTime = microtime(true);
				$audit_params["duration"] = $callEndTime - $callStartTime;
				
				auditor("", "page_view", $classname.'::'.$action , $audit_params, $cls ); //@steve support for Nwp_logging Plugin
				
				//CHECK FOR SEARCH QUERY
				if( isset( $_SESSION['key'] ) ){
					$sq = md5('search_query'.$_SESSION['key']);
					if( isset($_SESSION[$sq][$classname]['query']) && $_SESSION[$sq][$classname]['query'] ){
						$data['search_query'] = $_SESSION[$sq][$classname]['query'];
					}
				}
				
				if( is_array( $data ) ){
					$set_hash = 0;
					if( ! ( isset( $settings[ 'development_mode_off' ] ) && $settings[ 'development_mode_off' ] ) ){
						//set page title = 11-jan-23
						$data["page_title"] = trim( strip_tags( $page_title ) );
						if( $data["page_title"] == '&nbsp;' ){
							unset( $data["page_title"] );
						}

						if( isset( $data[ 'javascript_functions' ] ) && is_array( $data[ 'javascript_functions' ] ) && in_array( '$nwProcessor.recreateDataTables', $data[ 'javascript_functions' ] ) ){
							unset( $_GET[ 'is_menu' ] );
						}
						
						if( isset( $data[ 'html_replacement' ] ) && $data[ 'html_replacement' ] && isset( $_GET[ 'is_menu' ] ) && $_GET[ 'is_menu' ] && isset( $_GET[ 'menu_title' ] ) && $_GET[ 'menu_title' ] ){
							$hrow = '
								<div class="page-title-box d-sm-flex align-items-center justify-content-between">
									<h4 class="mb-sm-0 text-primary">'. urldecode( $_GET[ 'menu_title' ] ) .'</h4>
								</div>';
							$data[ 'html_replacement' ] = $hrow . $data[ 'html_replacement' ];
						}
						
						$data["development"] = get_hyella_development_mode();
						$data["audit_params"] = $audit_params;
						
						if( $nwp_hash != 1 ){
							if( isset( $settings[ 'nwp_repro' ] ) && $settings[ 'nwp_repro' ] ){
								$set_hash = 1;
							}else{
								$data["nwp_hash"] = nwp_request_hash_key( array( "clear" => $nwp_hash ) );
								//$data["nwp_hashs"] = $_SESSION["nwp_hash"];
							}
						}
						
						$nb = 'cNwp_client_notification';
						$nbc = 'cn_user_settings';
						if( class_exists( $nb ) && isset( $user_cert[ 'id' ] ) && $user_cert[ 'id' ] && ( ( isset( $_POST["nwp_client_notification"] ) && $_POST["nwp_client_notification"] ) || ( isset( $_GET["nwp_client_notification"] ) && $_GET["nwp_client_notification"] ) ) ){
							if( isset( $_GET["nwp_client_notification"] ) && $_GET["nwp_client_notification"] ){
								$_POST["nwp_client_notification"] = $_GET["nwp_client_notification"];
							}
							$nb = new $nb();
							$nb->class_settings = $cp["class_settings"];
							$bb = $nb->load_class( array( 'initialize' => 1, 'class' => array( $nbc ) ) );

							if( isset( $bb[ $nbc ]->table_name ) ){
								$bb[ $nbc ]->class_settings[ 'action_to_perform' ] = 'set_notification_settings';
								$bb[ $nbc ]->class_settings[ 'user_details' ] = $user_cert;
								
								$data['data'][ 'nwp_client_notification_data' ] = $bb[ $nbc ]->$nbc();
							}
						}
						
						if( defined( 'HYELLA_V3_OPEN_IN_NEW_TAB' ) ){
							$data[ "activate_open_new_tab" ] = HYELLA_V3_OPEN_IN_NEW_TAB;
						}
					}else{
						if( $nwp_hash != 1 ){
							$set_hash = 1;
						}
					}
					
					if( $set_hash ){
						nwp_request_hash_key( array( "set" => $nwp_hash ) );
					}

					// Set a var to log request on client side (log_history)
					if( ! empty( $log_history ) ){
						$data[ "log_history" ] = $log_history;
					}
					
					if( $first_request ){
						if( defined("NWP_EXPIRING_LICENSE") && NWP_EXPIRING_LICENSE ){
							$expl = explode(",", NWP_EXPIRING_LICENSE );
							if( isset( $expl[0] ) && isset( $expl[1] ) ){
								$expld = doubleval( $expl[0] ) - ($expl[1] * 3600 * 24);
								
								if( date("U") >= doubleval( $expl[0] ) ){
									$data = array(
										"err" => "Subscription has Expired",
										"msg" => 'expired on ' . date("d-M-Y", doubleval( $expl[0] ) ),
										"theme" => "note note-danger alert alert-danger",
										"typ" => "uerror",
										"html_replacement" => '<div class="alert alert-danger note note-danger">Expired on ' . date("d-M-Y", doubleval( $expl[0] ) ) . '</div>',
										"html_replacement_selector" => "#usersLoginForm",
										
										'status' => 'new-status',
									);
								}else if( date("U") >= $expld ){
									$data = array_merge( array(
										"err" => "Subscription will Expire Soon",
										"msg" => 'expires on ' . date("d-M-Y", doubleval( $expl[0] ) ),
										"theme" => "note note-warning alert alert-warning",
										"manual_close" => 1,
										"typ" => "uerror",
										
										'status' => 'new-status',
									), $data );
								}
							}
						}
					}

					// Set a var to log request on client side (log_history)
					
					return json_encode($data, JSON_PRETTY_PRINT);
					//return json_encode($data, JSON_PRETTY_PRINT);
				}else{
					nwp_request_hash_key( array( "set" => $nwp_hash ) );

					if( ! $data ){
						$error['typ'] = 'serror';
						$error['err'] = '<h4><strong>Empty/Undefined Method</strong></h4>';
						$error['msg'] = '<p>'. $cls["action_to_perform"] .' probably does not exist in '. $actual_name_of_class .'</strong></p>';
						$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
							$error['html'] .= '<h3><strong>Undefined Class</strong></h3>';
							$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
						$error['html'] .= '</div>';
						
						return json_encode($error, JSON_PRETTY_PRINT);
					}

					return $data;
				}
			}else{

				$error['typ'] = 'serror';
				$error['err'] = '<h4><strong>Undefined Class</strong></h4>';
				$error['msg'] = $actual_name_of_class.' does not exist';
				$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
					$error['html'] .= '<h3><strong>Undefined Class</strong></h3>';
					$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
				$error['html'] .= '</div>';
				
				return json_encode($error, JSON_PRETTY_PRINT);
			}
		}else{
			
			$error['typ'] = 'serror';
			$error['err'] = 'Restricted Access';
			$error['msg'] = 'Access denied';
			$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
				$error['html'] .= '<h3>Restricted Access</h3>';
				$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
			$error['html'] .= '</div>';
			
			return json_encode($error);
		}
	}

	public static function reuse_classX( $opt = [] ){
		
		$nwp_hash = nwp_request_hash_key( array( "test" => 1 ) );
		$error_msg = '';
		$error_status = '';
		
		$pagepointer = '';
		if( isset( $settings[ 'pagepointer' ] ) )
			$pagepointer = $settings[ 'pagepointer' ];
		
		$display_pagepointer = '';
		if( isset( $settings[ 'display_pagepointer' ] ) )
			$display_pagepointer = $settings[ 'display_pagepointer' ];
		
		$user_cert = array();
		if( isset( $settings[ 'user_cert' ] ) )
			$user_cert = $settings[ 'user_cert' ];
		
		if( function_exists("__logged_in_users_validate") && ! ( isset( $settings[ 'skip_authentication' ] ) && $settings[ 'skip_authentication' ] ) ){
			if( ! __logged_in_users_validate( $user_cert, session_id() ) ){
				$error_msg = '<h4><strong>Expired Session</strong></h4><p>Please login to your account</p>';
				$_SESSION = array();
				$error_status = "reload-page";
			}
		}
		
		$database_connection = '';
		if( isset( $settings[ 'database_connection' ] ) )
			$database_connection = $settings[ 'database_connection' ];
			
		$database_name = '';
		if( isset( $settings[ 'database_name' ] ) )
			$database_name = $settings[ 'database_name' ];
			
		$classname = '';
		if( isset( $settings[ 'classname' ] ) )
			$classname = $settings[ 'classname' ];
		
		$action = '';
		if( isset( $settings[ 'action' ] ) )
			$action = $settings[ 'action' ];
			
		$language = '';
		if( isset( $settings[ 'language' ] ) )
			$language = $settings[ 'language' ];
			
		$debug = '';
		if( isset( $settings[ 'debug' ] ) )
			$debug = $settings[ 'debug' ];
			
		$running_in_background = '';
		if( isset( $settings[ 'running_in_background' ] ) )
			$running_in_background = $settings[ 'running_in_background' ];

		if( ! in_array( $user_cert[ 'privilege' ], array( '1300130013', 'system' ) ) && get_hyella_development_mode() && ! ( defined( 'MAINTENANCE_MODE' ) && MAINTENANCE_MODE ) ){
			$abc = 'System';
			$de = ' in';
			$fij = ' Maintenance';
			$error['typ'] = 'serror';
			$error['err'] = '<h4><strong>'. $abc.$de.$fij .'</strong></h4>';
			$error['msg'] = '<p>Please contact your support team</p>';
			$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
				$error['html'] .= '<h3><strong>Undefined Class</strong></h3>';
				$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
			$error['html'] .= '</div>';
			
			return json_encode($error);
		}
		
		if( $error_msg ){
			$error = array();
			$error['typ'] = 'serror';
			$error['err'] = '';
			$error['msg'] = $error_msg;
			$error['html'] = $error_msg;
			if( $error_status ){
				$error['status'] = $error_status;
			}
			return json_encode($error);
		}

		$log_history = array();
		if( defined( 'ACTIVATE_BROWSER_HISTORY' ) && ACTIVATE_BROWSER_HISTORY ){
			// Get request with current_tab or get_children
			if( isset( $_GET[ 'is_menu' ] ) && $_GET[ 'is_menu' ] ){
				$log_history[ 'data' ][ 'post' ] = $_POST;
				$log_history[ 'data' ][ 'get' ] = $_GET;
			}
		}
		
		$first_request = 0;
		switch( $classname ){
		case "users":
			switch( $action ){
				case "app_check_logged_in_user2":
					$first_request = 1;
				break;
			}
		break;
		}

		//Check for Permission
		//echo permission($userid,$action,$classname,$action,$database_connection,$database_name);
		if( $nwp_hash || ( isset( $settings[ 'skip_authentication' ] ) && $settings[ 'skip_authentication' ] ) ){
			
			$callStartTime = microtime(true);
			
			$cls = array(
				'database_connection' => $database_connection,
				'database_name' => $database_name,
				'calling_page' => $pagepointer,
				'calling_page_display' => $display_pagepointer,
				
				'user_id' => $user_cert['id'],
				'user_full_name' => $user_cert['fname'] . ' ' . $user_cert['lname'],
				'user_fname' => $user_cert['fname'],
				'user_lname' => $user_cert['lname'],
				'user_email' => $user_cert['email'],
				'priv_id' => $user_cert['privilege'],
				'photograph' => isset( $user_cert['photograph'] )?$user_cert['photograph']:'',
				'user_store' => isset( $user_cert['store'] )?$user_cert['store']:"",
				'user_dept' => isset( $user_cert['department'] )?$user_cert['department']:"",
				'user_plugin' => isset( $user_cert['plugin'] )?$user_cert['plugin']:"",
				'user_table' => isset( $user_cert['table'] )?$user_cert['table']:"",
				'user_role' => isset( $user_cert['role'] )?$user_cert['role']:"",
				'force_password_change' => isset( $user_cert['force_password_change'] )?$user_cert['force_password_change']:"",
				'verification_status' => $user_cert[ 'verification_status' ],
				'remote_user_id' => $user_cert[ 'remote_user_id' ],
				'html_replacement_selector' => isset( $settings[ 'html_replacement_selector' ] )?$settings[ 'html_replacement_selector' ]:'',
				'phtml_replacement_selector' => isset( $settings[ 'phtml_replacement_selector' ] )?$settings[ 'phtml_replacement_selector' ]:'',
				
				'action_to_perform' => $action,
				
				'running_in_background' => $running_in_background,
				'debug' => $debug,
				'language' => $language,
			);

			if( isset( $settings[ 'doctrineORM' ] ) && $settings[ 'doctrineORM' ] ){
				// $cls[ 'doctrineORM' ] = $settings[ 'doctrineORM' ];
			}
			
			$GLOBALS["user_cert"] = $user_cert;
			
			$audit_params = array( "class" => $classname, "action" => $action );
			
			if( isset( $settings[ 'nwp_action' ] ) && $settings[ 'nwp_action' ] && isset( $settings[ 'nwp_todo' ] ) && $settings[ 'nwp_todo' ] ){
				$cls[ 'nwp_action' ] = $settings[ 'nwp_action' ];
				$cls[ 'nwp_todo' ] = $settings[ 'nwp_todo' ];
				
				$audit_params[ 'nwp_todo' ] = $settings[ 'nwp_todo' ];
				$audit_params[ 'nwp_action' ] = $settings[ 'nwp_action' ];
			}
			
			$cp = $audit_params;
		
			if( isset( $settings[ 'current_store' ] ) && $settings[ 'current_store' ] ){
				$GLOBALS[ 'current_store' ] = $settings[ 'current_store' ];
				$cls[ 'current_store' ] = $settings[ 'current_store' ];
			}
			
			if( isset( $settings[ 'branch' ] ) && $settings[ 'branch' ] ){
				$GLOBALS[ 'branch' ] = $settings[ 'branch' ];
				set_current_customer( array( "branch" => $settings[ 'branch' ] ) );
				$cls[ 'branch' ] = $settings[ 'branch' ];
			}
		
			if( isset( $settings[ 'frontend' ] ) && $settings[ 'frontend' ] ){
				$GLOBALS[ 'frontend' ] = $settings[ 'frontend' ];
				$cls[ 'frontend' ] = $settings[ 'frontend' ];
			}
		
			if( isset( $settings[ 'mobile' ] ) && $settings[ 'mobile' ] ){
				$cls[ 'mobile' ] = $settings[ 'mobile' ];
			}else{
				//$cls[ 'nw_mobile_browser' ] = is_mobile();
			}
			nw_set_breadcrum( array( "source" => "reuse_class" ) );
		
			$cp["class_settings"] = $cls;
			
			//$cs = '';//check_second_level_authentication( $cp );
			$cs = check_second_level_authentication( $cp );
			if( isset( $cs["action"] ) && $cs["action"] && isset( $cs["todo"] ) && $cs["todo"] ){
				$classname = $cs["action"];
				$action = $cs["todo"];
				
				$cls["action_to_perform"] = $action;
			}
			
			//set page title = 11-jan-23
			$page_title = '';
			if( isset( $_GET[ 'title' ] ) && $_GET[ 'title' ] ){
				$page_title = rawurldecode( $_GET[ 'title' ] );
			}else if( isset( $_GET[ 'menu_title2' ] ) && $_GET[ 'menu_title2' ] ){
				$page_title = rawurldecode( $_GET[ 'menu_title2' ] );
			}else if( isset( $_GET[ 'menu_title' ] ) && $_GET[ 'menu_title' ] ){
				$page_title = rawurldecode( $_GET[ 'menu_title' ] );
			}
			
			$actual_name_of_class = 'c'.ucwords($classname);
			
			if( class_exists( $actual_name_of_class ) ){
				$module = new $actual_name_of_class();
				
				$module->class_settings = $cls;
				
				$data = $module->$classname();	
				
				if( isset( $module->plugin_hooks[ $cls[ 'action_to_perform' ] ][ 'type' ] ) && $module->plugin_hooks[ $cls[ 'action_to_perform' ] ][ 'type' ] ){
						// print_r( 'mike' );exit();
					switch( $module->plugin_hooks[ $cls[ 'action_to_perform' ] ][ 'type' ] ){
					case 'process':

						$ph = $module->plugin_hooks[ $cls[ 'action_to_perform' ] ];
						
						$rd = add_nwp_plugin_options( array( "type" => $cls[ 'action_to_perform' ], "class_settings" => $module->class_settings, "params" => array( "class_data" => $data ) ) );

						if( ! empty( $rd ) ){
							$data = $rd;
						}

					break;
					}

				}
				
				if( isset( $GLOBALS["nwp_full_text_search"] ) && ! empty( $GLOBALS["nwp_full_text_search"] ) ){
					if( class_exists( "cNwp_full_text_search" ) ){
						$fts = new cNwp_full_text_search();
						$fts->class_settings = $cls;
						$fts->class_settings['tables'] = $GLOBALS["nwp_full_text_search"];
						$fts->class_settings['action_to_perform'] = 'execute';
						$fts->class_settings['nwp_action'] = 'index_queue';
						$fts->class_settings['nwp_todo'] = 'add_to_index_queue';
						$fts->nwp_full_text_search();
					}
					unset( $GLOBALS["nwp_full_text_search"] );
				}
				
				$callEndTime = microtime(true);
				$audit_params["duration"] = $callEndTime - $callStartTime;
				
				auditor("", "page_view", $classname.'::'.$action , $audit_params, $cls ); //@steve support for Nwp_logging Plugin
				
				//CHECK FOR SEARCH QUERY
				if( isset( $_SESSION['key'] ) ){
					$sq = md5('search_query'.$_SESSION['key']);
					if( isset($_SESSION[$sq][$classname]['query']) && $_SESSION[$sq][$classname]['query'] ){
						$data['search_query'] = $_SESSION[$sq][$classname]['query'];
					}
				}
				
				if( is_array( $data ) ){
					$set_hash = 0;
					if( ! ( isset( $settings[ 'development_mode_off' ] ) && $settings[ 'development_mode_off' ] ) ){
						//set page title = 11-jan-23
						$data["page_title"] = trim( strip_tags( $page_title ) );
						if( $data["page_title"] == '&nbsp;' ){
							unset( $data["page_title"] );
						}

						if( isset( $data[ 'javascript_functions' ] ) && is_array( $data[ 'javascript_functions' ] ) && in_array( '$nwProcessor.recreateDataTables', $data[ 'javascript_functions' ] ) ){
							unset( $_GET[ 'is_menu' ] );
						}
						
						if( isset( $data[ 'html_replacement' ] ) && $data[ 'html_replacement' ] && isset( $_GET[ 'is_menu' ] ) && $_GET[ 'is_menu' ] && isset( $_GET[ 'menu_title' ] ) && $_GET[ 'menu_title' ] ){
							$hrow = '
								<div class="page-title-box d-sm-flex align-items-center justify-content-between">
									<h4 class="mb-sm-0 text-primary">'. $_GET[ 'menu_title' ] .'</h4>
								</div>';
							$data[ 'html_replacement' ] = $hrow . $data[ 'html_replacement' ];
						}
						
						$data["development"] = get_hyella_development_mode();
						$data["audit_params"] = $audit_params;
						
						if( $nwp_hash != 1 ){
							if( isset( $settings[ 'nwp_repro' ] ) && $settings[ 'nwp_repro' ] ){
								$set_hash = 1;
							}else{
								$data["nwp_hash"] = nwp_request_hash_key( array( "clear" => $nwp_hash ) );
								//$data["nwp_hashs"] = $_SESSION["nwp_hash"];
							}
						}
						
						$nb = 'cNwp_client_notification';
						$nbc = 'cn_user_settings';
						if( class_exists( $nb ) && isset( $user_cert[ 'id' ] ) && $user_cert[ 'id' ] && ( ( isset( $_POST["nwp_client_notification"] ) && $_POST["nwp_client_notification"] ) || ( isset( $_GET["nwp_client_notification"] ) && $_GET["nwp_client_notification"] ) ) ){
							if( isset( $_GET["nwp_client_notification"] ) && $_GET["nwp_client_notification"] ){
								$_POST["nwp_client_notification"] = $_GET["nwp_client_notification"];
							}
							$nb = new $nb();
							$nb->class_settings = $cp["class_settings"];
							$bb = $nb->load_class( array( 'initialize' => 1, 'class' => array( $nbc ) ) );

							if( isset( $bb[ $nbc ]->table_name ) ){
								$bb[ $nbc ]->class_settings[ 'action_to_perform' ] = 'set_notification_settings';
								$bb[ $nbc ]->class_settings[ 'user_details' ] = $user_cert;
								
								$data['data'][ 'nwp_client_notification_data' ] = $bb[ $nbc ]->$nbc();
							}
						}
						
						if( defined( 'HYELLA_V3_OPEN_IN_NEW_TAB' ) ){
							$data[ "activate_open_new_tab" ] = HYELLA_V3_OPEN_IN_NEW_TAB;
						}
					}else{
						if( $nwp_hash != 1 ){
							$set_hash = 1;
						}
					}
					
					if( $set_hash ){
						nwp_request_hash_key( array( "set" => $nwp_hash ) );
					}

					// Set a var to log request on client side (log_history)
					if( ! empty( $log_history ) ){
						$data[ "log_history" ] = $log_history;
					}
					
					if( $first_request ){
						if( defined("NWP_EXPIRING_LICENSE") && NWP_EXPIRING_LICENSE ){
							$expl = explode(",", NWP_EXPIRING_LICENSE );
							if( isset( $expl[0] ) && isset( $expl[1] ) ){
								$expld = doubleval( $expl[0] ) - ($expl[1] * 3600 * 24);
								
								if( date("U") >= doubleval( $expl[0] ) ){
									$data = array(
										"err" => "Subscription has Expired",
										"msg" => 'expired on ' . date("d-M-Y", doubleval( $expl[0] ) ),
										"theme" => "note note-danger alert alert-danger",
										"typ" => "uerror",
										"html_replacement" => '<div class="alert alert-danger note note-danger">Expired on ' . date("d-M-Y", doubleval( $expl[0] ) ) . '</div>',
										"html_replacement_selector" => "#usersLoginForm",
										
										'status' => 'new-status',
									);
								}else if( date("U") >= $expld ){
									$data = array_merge( array(
										"err" => "Subscription will Expire Soon",
										"msg" => 'expires on ' . date("d-M-Y", doubleval( $expl[0] ) ),
										"theme" => "note note-warning alert alert-warning",
										"manual_close" => 1,
										"typ" => "uerror",
										
										'status' => 'new-status',
									), $data );
								}
							}
						}
					}

					// Set a var to log request on client side (log_history)
					
					return json_encode($data, JSON_PRETTY_PRINT);
					//return json_encode($data, JSON_PRETTY_PRINT);
				}else{
					nwp_request_hash_key( array( "set" => $nwp_hash ) );

					if( ! $data ){
						$error['typ'] = 'serror';
						$error['err'] = '<h4><strong>Empty/Undefined Method</strong></h4>';
						$error['msg'] = '<p>'. $cls["action_to_perform"] .' probably does not exist in '. $actual_name_of_class .'</strong></p>';
						$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
							$error['html'] .= '<h3><strong>Undefined Class</strong></h3>';
							$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
						$error['html'] .= '</div>';
						
						return json_encode($error, JSON_PRETTY_PRINT);
					}

					return $data;
				}
			}else{

				$error['typ'] = 'serror';
				$error['err'] = '<h4><strong>Undefined Class</strong></h4>';
				$error['msg'] = $actual_name_of_class.' does not exist';
				$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
					$error['html'] .= '<h3><strong>Undefined Class</strong></h3>';
					$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
				$error['html'] .= '</div>';
				
				return json_encode($error, JSON_PRETTY_PRINT);
			}
		}else{
			
			$error['typ'] = 'serror';
			$error['err'] = 'Restricted Access';
			$error['msg'] = 'Access denied';
			$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
				$error['html'] .= '<h3>Restricted Access</h3>';
				$error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
			$error['html'] .= '</div>';
			
			return json_encode($error);
		}
	}

	public static function startSession( $opt = [] ){
		//if( ! isset( $background_data_upload_only_session ) ){
		if( ! ( isset( $opt["doNotLoadSession"] ) && $opt["doNotLoadSession"] ) ){
			ini_set('session.use_cookies', 1);
			
			$time_limit = 0;
			if( defined("HYELLA_ENABLE_SESSION_TIMEOUT") && HYELLA_ENABLE_SESSION_TIMEOUT ){
				define( 'HYELLA_SESSION_TIMEOUT_LIMIT' , HYELLA_ENABLE_SESSION_TIMEOUT );
				
				if( defined("HYELLA_SESSION_TIMEOUT_LIMIT") ){
					$time_limit = doubleval( HYELLA_SESSION_TIMEOUT_LIMIT );
					if( $time_limit ){
						ini_set("session.gc_maxlifetime", $time_limit * 2 );
					}
				}
			}
			
			if( ! $time_limit ){
				ini_set("session.gc_maxlifetime", 3600 * 4 );
			}
			
			if( isset( $GLOBALS["session_id"] ) && $GLOBALS["session_id"] ){
				session_id( $GLOBALS["session_id"] );
			}
			
			session_start();
			
			if( $time_limit ){
				$now_time = date("U");
				if( isset( $_SESSION["idle_timeout"] ) && $now_time > ( $_SESSION["idle_timeout"] + $time_limit ) ){
					define("IDLE_TIMEOUT", 1 );
				}
				$_SESSION["idle_timeout"] = $now_time;
			}
			
		}else{
			//echo ' session off';
		}
		
		if( ! ( isset( $opt["doNotLoadENV"] ) && $opt["doNotLoadENV"] ) ){
			self::loadENV();
		}
	}
	
	public static function db_connect( $opt = [] ){
		if( isset( self::$def_cs[ 'database_connection' ] ) ){
			return;
		}

		self::startSession( $opt );

		$database_connection = '';
		$database_host_ip_address = self::$def_cs[ 'database_host_ip_address' ];
		$database_user = self::$def_cs[ 'database_user' ];
		$database_user_password = self::$def_cs[ 'database_user_password' ];;
		$database_name = self::$def_cs[ 'database_name' ];;
		
		if( ! ( defined("HYELLA_LYTICS_SERVER") && HYELLA_LYTICS_SERVER ) ){
			try{
				$database_connection = mysqli_connect( $database_host_ip_address, $database_user, $database_user_password );
			}catch(Exception $e){
				self::error( array( 'output' => true, 'title' => 'DB Connection Failed', 'message' => 'Could not connect to DB with '.$database_user.'@'.$database_host_ip_address.' in Nwp_app_core: ' . $e->getMessage() ) );
				exit;
			}
		}
		

		if( ! $database_connection ){
			self::error( array( 'output' => true, 'title' => 'DB Connection Failed', 'message' => 'Could not connect to DB with '.$database_user.'@'.$database_host_ip_address.' in Nwp_app_core: ' ) ); 
			exit;
		}
		
		if( isset( $_SESSION["test_server"] ) && $_SESSION["test_server"] ){
			$database_name .= '_test';
		}
		
		if( ! ( defined("HYELLA_LYTICS_SERVER") && HYELLA_LYTICS_SERVER ) ){
			try{
				if( $database_connection ){
					if( ! mysqli_select_db ( $database_connection , $database_name ) ){
						$m = '';
						if( isset( $_SESSION["test_server"] ) && $_SESSION["test_server"] ){
							unset( $_SESSION["test_server"] );
							$m = '<p><a href="">Click here to return to live database</a></p>';
							
							$a = array( "status" => "new-status", "html_replacement_selector" => "body" , "html_replacement" => "<h1>" . 'Missing Database: ' . $database_name . "</h1>" . $m );
							echo json_encode( $a );
							exit;
						}
						
						self::error( array( 'output' => true, 'title' => 'DB Connection Failed', 'message' => 'Could not select DB '. $database_name .' with '.$database_user.'@'.$database_host_ip_address.' in Nwp_app_core: I' ) ); 
						exit;
					}
				}
			}catch(Exception $e){
				self::error( array( 'output' => true, 'title' => 'DB Connection Failed', 'message' => 'Could not select DB '. $database_name .' with '.$database_user.'@'.$database_host_ip_address.' in Nwp_app_core: ' . $e->getMessage() ) ); 
				exit;
			}
			
		}

		self::$def_cs[ 'database_connection' ] = $database_connection;
		
	}

	public static function error( $settings = array() ){
		$error = [];
		
		$error['typ'] = 'serror';
		$error['err'] = isset( $settings["title"] )?$settings["title"]:'';
		$error['msg'] = isset( $settings["message"] )?$settings["message"]:'';
		$error['html'] = '<div>';
			$error['html'] .= '<h3>'.$error['err'].'</h3>';
			$error['html'] .= '<p>'.$error['msg'].'</p>';
		$error['html'] .= '</div>';
		
		//$error['trace'] = array_column( debug_backtrace(), 'line', 'file' );
		$error['trace'] = debug_backtrace();
		
		if( isset( $settings["output"] ) && $settings["output"] ){
			echo json_encode($error); exit;
		}
		
		return json_encode($error);
	}
	
	public static function loadENV( $opt = [] ){
		date_default_timezone_set('Africa/Lagos');
		setlocale(LC_CTYPE, 'POSIX');

		$nwp_env = [];
		$localServer = $_SERVER;

		if( isset( $GLOBALS['provisioning']['constants'] ) && $GLOBALS['provisioning']['constants'] ){
			//use .env values to overwrite the constants defined in provisioning
			$localServer =  $GLOBALS['provisioning']['constants'];
		}else{
			$opt['ignore_default'] = true;
		}

		if( ! empty( $localServer ) ){
			//initial db connection parameters incase it does not exist in the provisioning file, so it can pick it from the SERVER variables
			if( ! ( isset( $opt[ 'ignore_default' ] ) && $opt[ 'ignore_default' ] ) ){
				foreach( self::$laravelENV as $sk => $sv ){
					if( $sk == 'DATABASE_HOST' && ! isset( $localServer[ $sk ] ) ){
						$localServer[ $sk ] = 'localhost';
					}else if( ! isset( $localServer[ $sk ] ) && strpos( strtolower( $sk ), 'database_' ) > -1 ){
						$localServer[ $sk ] = 'undefined_' . $sk;
					}
				}
			}

			foreach( $localServer as $sk => $sv ){
				$GLOBALS['env'][ $sk ] =  $sv;

				if( substr($sk, -5) == '_FILE' ){
					if( $sk == 'NWP_ENV_FILE' ){
						if( file_exists($sv) ){
							$clean_env =  self::nwp_enc22( file_get_contents( $sv ), false );
						}

						$nwp_env = json_decode( $clean_env, 1 );
					}else{
						if( file_exists($sv) ){
							$GLOBALS['env'][ $sk ] =  file_get_contents( $sv );
						}
					}
				}
			}
		}
		
		if( ! empty( $nwp_env ) ){
			$GLOBALS['env'] = array_merge( $GLOBALS['env'], $nwp_env );
		}
		if( ! isset( $GLOBALS['env']['NWP_APP_VERSION'] ) ){
			$GLOBALS['env']['NWP_APP_VERSION'] = self::getAppVersion();
		}
		//print_r($GLOBALS['env']); exit;

		//load provisioning constants
		if( isset( $GLOBALS['provisioning']['constants'] ) && $GLOBALS['provisioning']['constants'] ){
			if( isset( $GLOBALS['env'] ) && $GLOBALS['env'] ){
				foreach( $GLOBALS['env'] as $ck => $cv ){
					if( ! is_array( $cv ) ){
						define( $ck, $cv );
					}
				}
			}
			if( isset( $GLOBALS['provisioning']['classes'] ) && $GLOBALS['provisioning']['classes'] ){
				self::$def_cs[ 'nwp_package_class' ] = $GLOBALS['provisioning']['classes'];
			}
			
			if( isset( $GLOBALS['provisioning']['plugins'] ) && $GLOBALS['provisioning']['plugins'] ){
				self::$def_cs[ 'package_config' ]['plugins_loaded'] = $GLOBALS['provisioning']['plugins'];
			}

			self::$def_cs[ 'database_name' ] = isset( $GLOBALS['env']['DATABASE_NAME'] )?$GLOBALS['env']['DATABASE_NAME']:'';
			self::$def_cs[ 'database_user' ] = isset( $GLOBALS['env']['DATABASE_USER'] )?$GLOBALS['env']['DATABASE_USER']:'';
			self::$def_cs[ 'database_user_password' ] = isset( $GLOBALS['env']['DATABASE_PASSWORD'] )?$GLOBALS['env']['DATABASE_PASSWORD']:'';
			self::$def_cs[ 'database_host_ip_address' ] = isset( $GLOBALS['env']['DATABASE_HOST'] )?$GLOBALS['env']['DATABASE_HOST']:'';
		}else{
			if( dirname( dirname( dirname( __FILE__ ) ) ) . '/settings/provisioning.php' ){
				require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/settings/provisioning.php';

				if( isset( $nwp_package_class ) && $nwp_package_class ){
					self::$def_cs[ 'nwp_package_class' ] = $nwp_package_class;
				}
				if( isset( $package_config ) && $package_config ){
					self::$def_cs[ 'package_config' ] = $package_config;
				}

				self::$def_cs[ 'database_name' ] = $database_name;
				self::$def_cs[ 'database_user' ] = $database_user;
				self::$def_cs[ 'database_user_password' ] = $database_user_password;
				self::$def_cs[ 'database_host_ip_address' ] = $database_host_ip_address;
			}else{
				echo 'Missing Provisioning File'; exit;
			}
		}

		if( self::isDevelopmentMode() ){
			error_reporting (E_ALL);
		}else{
			error_reporting (~E_ALL);
		}

		//print_r( $GLOBALS['env'] ); exit;
		//tmp measure bcos
	}
	
	public static function getAppVersion( $pagepointer = '' ){
		if( ! $pagepointer ){
			$pagepointer = dirname( dirname( dirname( __FILE__ ) ) ) . '/';
		}
		
		$application_version = 'no-version';
		if( file_exists( $pagepointer . 'BUILD.hyella' ) ){
			//3. Open & Read all files in directory
			$ap = json_decode( file_get_contents( $pagepointer . 'BUILD.hyella' ), true );
			
			$package = 'custom';
			if( isset( $GLOBALS['env']['HYELLA_PACKAGE'] ) && $GLOBALS['env']['HYELLA_PACKAGE'] ){
				$package = $GLOBALS['env']['HYELLA_PACKAGE'];
				if( isset( $GLOBALS['env']['HYELLA_SUB_PACKAGE'] ) && $GLOBALS['env']['HYELLA_SUB_PACKAGE'] ){
					$package = $GLOBALS['env']['HYELLA_SUB_PACKAGE'];
				}
			}
			
			$application_version = isset( $ap[ $package ]["version"] )?$ap[ $package ]["version"]:'invalid';
		}
		
		return $application_version;
	}
	
	public static function isDevelopmentMode(){
		if( ( defined("DEVELOPMENT_MODE") && intval( DEVELOPMENT_MODE ) ) && ! defined("HYELLA_MODE") ){
			define("HYELLA_MODE", "development" );
		}
		if(  ( defined("HYELLA_MODE") && HYELLA_MODE == "development" ) ){
			return 1;
		}
	}
	
	public static function getUserCapabilities( $priv_id = '' ){
		$access = array();
	
		if( ! ( isset( $priv_id ) && $priv_id ) ){
			$key = md5( 'ucert' . $_SESSION['key'] );
			
			if( isset( $_SESSION[ $key ] ) ){
				$user_details = $_SESSION[$key];
				if( isset( $user_details["privilege"] ) && $user_details["privilege"] ){
					$priv_id = $user_details["privilege"];
				}
			}
		}

		// print_r( $_POST );
		if( ! ( isset( $priv_id ) && $priv_id ) && isset( $_GET[ 'user_privilege' ] ) && $_GET[ 'user_privilege' ] ){
			$priv_id = $_GET[ 'user_privilege' ];
		}
		
		if( isset( $priv_id ) && $priv_id ){
			
			if( $priv_id == "1300130013" ){
				return 1;
			}else{
				if( isset( $GLOBALS["access"][ "status" ]["stores"]["_all_caps_"] ) && $GLOBALS["access"][ "status" ]["stores"]["_all_caps_"] ){
					return 1;
				}
				/*
				$functions = get_access_roles_details( array( "id" => $priv_id ) );
				if( isset( $functions[ $priv_id ]["accessible_functions"] ) ){
					$a = explode( ":::" , $functions[ $priv_id ]["accessible_functions"] );
					if( is_array( $a ) && $a ){
						foreach( $a as $k => $v ){
							$access[ $v ] = get_functions_details( array( "id" => $v ) );
						}
					}
				}
				*/
				$access = isset( $GLOBALS["access"] )?$GLOBALS["access"]:array();
			}
		}
		return $access;
	}
	
	public static function getMenuOptions( $opt = array() ){
		extract( $opt );
		
		$data = [];
		$development = cNwp_app_core::isDevelopmentMode();
		// $development = 0;
		$access = cNwp_app_core::getUserCapabilities();
		$super = 0;
		if( ! is_array( $access ) && $access == 1 ){
			$super = 1;
		}
		
		$menu2 = cNwp_app_core::getLoadedPluginOptions( array( "type" => "main_menu", "data" => [] ) );
		$submenu2 = cNwp_app_core::getLoadedPluginOptions( array( "type" => "sub_menu", "data" => [] ) );
		
		$h = '';
		$first = 1;
		
		if( ! empty( $menu2 ) ){
			foreach( $menu2 as $mkey => $mval ){
				
				if( ! isset( $mval[ "tab" ][ $current_tab ] ) ){
					continue;
				}
				
				$show_menu = 0;
				$html2 = '';
				$html1 = '';
				
				$main_menu_key = $mkey;
				if( isset( $mval["sub_menu"] ) && is_array( $mval["sub_menu"] ) && ! empty( $mval["sub_menu"] ) ){
					$d2_subs = [];
					
					foreach( $mval["sub_menu"] as $smval ){
						if( isset( $submenu2[ $smval ] ) ){
							
							$menu_key = $smval;
							$show_menu = 1;
							
							$tooltip = '';
							if( isset( $submenu2[ $smval ]["tooltip"] ) && $submenu2[ $smval ]["tooltip"] ){ 
								$tooltip = $submenu2[ $smval ]["tooltip"];
							}
							
							if( isset( $submenu2[ $smval ]["sub_menu"] ) && is_array( $submenu2[ $smval ]["sub_menu"] ) && ! empty( $submenu2[ $smval ]["sub_menu"] ) ){
								
								$h_subs = '';
								$d1_subs = [];
								
								foreach( $submenu2[ $smval ]["sub_menu"] as $s2 ){
									if( isset( $submenu2[ $s2 ]["class"] ) ){
										$menu_key = $s2;
										//$menu_key = md5( $menu2_settings["prefix"] . $s2 );
										
										if( ( ! $development && ! isset( $submenu2[ $s2 ]["development"] ) ) || $development ){
											if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
												$h_subs .= '<li>';
													$h_attr = '';
													
													$tooltip2 = '';
													if( isset( $submenu2[ $s2 ]["tooltip"] ) && $submenu2[ $s2 ]["tooltip"] ){ 
														$tooltip2 = $submenu2[ $s2 ]["tooltip"];
													}

													if( isset( $submenu2[ $s2 ][ 'link' ] ) && $submenu2[ $s2 ][ 'link' ] ){
														$h_attr = isset( $submenu2[ $s2 ]["new_window"] ) ? ' target="_blank" ' : '';
													}else{
														$h_attr = 'action="?action='. $submenu2[ $s2 ]["class"] . '&todo=' . $submenu2[ $s2 ]["action"] . '&html_replacement_selector='.  $container2 . '&is_menu=1&current_tab='.$current_tab . ( isset( $submenu2[ $s2 ]["params"] ) ? $submenu2[ $s2 ]["params"] : '' ) .'&menu_title='. rawurlencode( ( isset( $submenu2[ $s2 ][ "full_title" ] ) && $submenu2[ $s2 ][ "full_title" ] )?$submenu2[ $s2 ][ "full_title" ]:$submenu2[ $s2 ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'" class="custom-single-selected-record-button" ';
													}

													$h_subs .= '<a href="'. ( isset( $submenu2[ $s2 ]["link"] ) && $submenu2[ $s2 ]["link"] ? $submenu2[ $s2 ]["link"] : '#' ) .'" override-selected-record="' . $customer_id . '" '. $h_attr .' title="'.$tooltip2.'">' . $submenu2[ $s2 ][ "title" ] . '</a>';
												$h_subs .= '</li>';
												
												if( $tree ){
													$h_attr = str_replace('action="?', '', $h_attr );
													$h_attr = str_replace('class="custom-single-selected-record-button"', '', $h_attr );
													$h_attr = str_replace('&', ':::', $h_attr );
													$h_attr = trim( $h_attr );
													
													$d1 = array(
														'id' => $h_attr,
														'tooltip' =>  $tooltip2,
														'text' =>  $submenu2[ $s2 ][ "title" ]
													);
													if( isset( $submenu2[ $s2 ]["icon"] ) && $submenu2[ $s2 ]["icon"] ){
														$d1['icon'] = $submenu2[ $s2 ]["icon"];
													}
													$d1_subs[] = $d1;
												}
												
											}
										}
									}
								}
								
								if( $h_subs ){
									if( $tree ){
										$d1 = array(
											'id' => $smval,
											'text' =>  $submenu2[ $smval ][ "title" ],
											'children' => $d1_subs
										);
										
										$d2_subs[] = $d1;
									}else{
										$html1 .= '<li class="app-sidebar__heading">';
										
										$html1 .=  $submenu2[ $smval ][ "title" ];
										$html1 .=  ' <i class="icon-caret-left pull-right"></i></li>';
										
										$html1 .= '<li><ul class="sub-menu hidden">' . $h_subs . '</ul>';
										$html1 .= '</li>';
									}
								}else{
									
									if( ( ! $development && ! isset( $submenu2[ $smval ]["development"] ) ) || $development ){
										if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
											
											$h_attr = 'action=';
											if( isset( $submenu2[ $smval ]["class"] ) ){
												$h_attr .= $submenu2[ $smval ]["class"];
											}
											
											if( isset( $submenu2[ $smval ]["action"] ) ){
												$h_attr .= '&todo=' . $submenu2[ $smval ]["action"];
											}
											$h_attr .= '&html_replacement_selector='. $container2 . '&is_menu=1&current_tab='.$current_tab.'&menu_title='. rawurlencode( ( isset( $submenu2[ $smval ][ "full_title" ] ) && $submenu2[ $smval ][ "full_title" ] )?$submenu2[ $smval ][ "full_title" ]:$submenu2[ $smval ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store; 
											
											if( $tree ){
												$h_attr = str_replace('&', ':::', $h_attr );
												$h_attr = trim( $h_attr );
												
												$d1 = array(
													'id' => $h_attr,
													'tooltip' =>  $tooltip,
													'text' =>  $submenu2[ $smval ][ "title" ]
												);
												if( isset( $submenu2[ $smval ]["icon"] ) && $submenu2[ $smval ]["icon"] ){
													$d1['icon'] = $submenu2[ $smval ]["icon"];
												}
												$d2_subs[] = $d1;
											}else{
												$html1 .= '<li>';
											
													$html1 .= '<a href="#" class="';
													if( isset( $submenu2[ $smval ]["class"] ) && $submenu2[ $smval ]["class"] ){ 
														$html1 .= 'custom-single-selected-record-button';
													}
													
													$html1 .= '" override-selected-record="' . $customer_id . '" action="?' . $h_attr;
													
													$html1 .= '" title="'. $tooltip .'">';
													$html1 .= $submenu2[ $smval ][ "title" ];
													
													$html1 .= '</a>';
													$html1 .= '</li>';
											}
											
										}
									}
									
								}
							}else{
								
								if( ( ! $development && ! isset( $submenu2[ $smval ]["development"] ) ) || $development ){
									if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
										$h_attr = '';

										if( isset( $submenu2[ $smval ][ 'link' ] ) && $submenu2[ $smval ][ 'link' ] ){
											$h_attr = isset( $submenu2[ $smval ]["new_window"] ) ? ' target="_blank" ' : '';
										}else{
											$h_attr = 'action="?action=' . $submenu2[ $smval ]["class"] . '&todo=' . $submenu2[ $smval ]["action"] . '&html_replacement_selector=' . $container2 . '&is_menu=1&current_tab='.$current_tab . ( isset( $submenu2[ $smval ]["params"] ) ? $submenu2[ $smval ]["params"] : '' ) .'&menu_title='. rawurlencode( ( isset( $submenu2[ $smval ][ "full_title" ] ) && $submenu2[ $smval ][ "full_title" ] )?$submenu2[ $smval ][ "full_title" ]:$submenu2[ $smval ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'" class="custom-single-selected-record-button" ';
										}
										
										if( $tree ){
											$h_attr = str_replace('action="?', '', $h_attr );
											$h_attr = str_replace('class="custom-single-selected-record-button"', '', $h_attr );
											$h_attr = str_replace('&', ':::', $h_attr );
											$h_attr = trim( $h_attr );
											
											$d1 = array(
												'id' => $h_attr,
												'tooltip' =>  $tooltip,
												'text' =>  $submenu2[ $smval ][ "title" ]
											);
											if( isset( $submenu2[ $smval ]["icon"] ) && $submenu2[ $smval ]["icon"] ){
												$d1['icon'] = $submenu2[ $smval ]["icon"];
											}
											$d2_subs[] = $d1;
										}else{
											$html1 .= '<li>';
												$html1 .= '<a href="'. ( isset( $submenu2[ $smval ]["link"] ) && $submenu2[ $smval ]["link"] ? $submenu2[ $smval ]["link"] : '#' ) .'" override-selected-record="' . $customer_id . '" '. $h_attr .'  title="'. $tooltip .'">' . $submenu2[ $smval ][ "title" ] . '</a>';
											$html1 .= '</li>';
										}
										
									}
								}
							}
						
						}
					}
					
					if( $tree ){
						if( ! empty( $d2_subs ) ){
							$d1 = array(
								'id' => $mkey,
								'text' =>  $mval["title"],
								'children' => $d2_subs
							);
							
							$data[] = $d1;
						}
					}else if( $html1 ){
					   $hidden = 'hidden';
					   $picon = 'icon-caret-left';
					   if( $first ){
						   $hidden = '';
						   $picon = 'icon-caret-down';
					   }
					   $first = 0;
					   
						$html2 .= '<li class="app-sidebar__heading">'. $mval["title"] .' <i class="'.$picon.' pull-right"></i></li>';
						
						$html2 .= '<li>';
						   /*
						   $html2 .= '<a href="javascript:;">';
						   $html2 .= '<i class="metismenu-icon ' . $mval["icon"] . '"></i>';
						   $html2 .= $mval["title"];
						   $html2 .= '<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>';
						   $html2 .= '</a>';
						   */
						   
						   $html2 .= '<ul class="sub-menu '. $hidden .'">';
						   $html2 .= $html1 . '</ul>';
						$html2 .= '</li>';
					}
				}else{
					if( isset( $access["accessible_functions"][ $main_menu_key ] ) || $super ){
						$tooltip = isset($mval['tooltip']) && $mval['tooltip'] ? $mval['tooltip'] : '';
						
						if( $tree ){
							$d1 = array(
								'id' => 'action=' . $mval["class"] . ':::todo=' . $mval["action"] . ':::menu_title='. rawurlencode( ( isset( $mval[ "full_title" ] ) && $mval[ "full_title" ] )?$mval[ "full_title" ]:$mval[ "title" ] ) .':::is_menu=1:::html_replacement_selector=' . $container2,
								'tooltip' =>  $tooltip,
								'text' =>  $mval["title"]
							);
							if( isset( $mval["icon"] ) && $mval["icon"] ){
								$d1['icon'] = $mval["icon"];
							}
							$data[] = $d1;
						}else{
							$html2 .= '<li>';
							   $html2 .= '<a href="#" class="custom-single-selected-record-button" override-selected-record="'. $customer_id . '" action="?action=' . $mval["class"] . '&todo=' . $mval["action"] . '&html_replacement_selector=' .  $container2 . '&menu_title='. rawurlencode( ( isset( $mval[ "full_title" ] ) && $mval[ "full_title" ] )?$mval[ "full_title" ]:$mval[ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'&is_menu=1&current_tab='.$current_tab.'"  title="'. $tooltip .'">';
							   $html2 .= '<i class="'. $mval["icon"] . '"></i> ';
							   $html2 .= '<span class="title">'.  $mval["title"] . '</span>';
							   $html2 .= '</a>';
							$html2 .= '</li>';
						}
						
					}
				}
				
				$h .= $html2;
			}
		}
		
		if( $data ){
			return $data;
		}
		
		return $h;
	}
	
	public static function getLoadedPluginOptions( $opt = array() ){
		$data = isset( $opt["data"] )?$opt["data"]:array();
		
		if( isset( $GLOBALS["plugins"] ) && is_array( $GLOBALS["plugins"] )  && ! empty( $GLOBALS["plugins"] ) ){
			$plugins = $GLOBALS["plugins"];
			
			if( isset( $opt["type"] ) ){
				switch( $opt["type"] ){
				case "sub_menu":
				case "main_menu":
				case "frontend_tabs":
				default:
					foreach( $plugins as $pl ){
						$plc = new $pl();
						/* if( method_exists( $plc, '__after_construct' ) ){
							// $this->__after_construct();
						} */
						if( isset( $plc->plugin_options[ $opt["type"] ] ) && ! empty( $plc->plugin_options[ $opt["type"] ] ) ){
							foreach( $plc->plugin_options[ $opt["type"] ] as $k => $v ){
								$typ = isset( $v["type"] )?$v["type"]:'';
								
								switch( $typ ){
								case "process":
									if( isset( $v["nwp_action"] ) && $v["nwp_action"] && isset( $v["nwp_todo"] ) && $v["nwp_todo"] && isset( $opt["class_settings"] ) && $opt["class_settings"] ){

										$plc->class_settings = $opt["class_settings"];
										$plc->class_settings["params"] = $opt["params"];
										$plc->class_settings["nwp_action"] = $v["nwp_action"];
										$plc->class_settings["nwp_todo"] = $v["nwp_todo"];

										$data = $plc->execute();
									}
								break;
								case "html":
									if( isset( $v["position"] ) && $v["position"] && isset( $v["nwp_action"] ) && $v["nwp_action"] && isset( $v["nwp_todo"] ) && $v["nwp_todo"] && isset( $opt["class_settings"] ) && $opt["class_settings"] ){
										$plc->class_settings = $opt["class_settings"];
										$plc->class_settings["params"] = $opt["params"];
										$plc->class_settings["nwp_action"] = $v["nwp_action"];
										$plc->class_settings["nwp_todo"] = $v["nwp_todo"];
										$data[ $v["position"] ][] = $plc->execute();
									}
								break;
								default:
									if( isset( $data[ $k ] ) ){
										switch( $typ ){
										case "merge":
											
											//print_r( $v ); exit;
											foreach( $v as $vk => $vv ){
												switch( $vk ){
												case "sub_menu":
													if( isset( $data[ $k ]["sub_menu"] ) && ! empty( $data[ $k ]["sub_menu"] ) && is_array( $data[ $k ]["sub_menu"] ) ){
														$vd = array();
														foreach( $data[ $k ]["sub_menu"] as $ik => $iv ){
															$vd[] = $iv;
															
															if( isset( $v["sub_menu"][ $ik ] ) ){
																if( is_array( $v["sub_menu"][ $ik ] ) ){
																	foreach( $v["sub_menu"][ $ik ] as $svd ){
																		$vd[] = $svd;
																	}
																}else{
																	$vd[] = $v["sub_menu"][ $ik ];
																}
															}
														}
														$data[ $k ]["sub_menu"] = $vd;
													}
												break;
												case "type":
												break;
												default:
													$data[ $k ][ $vk ] = $vv;
												break;
												}
											}
											//print_r( $data[ $k ] ); exit;
										break;
										case "remove":
											unset( $data[ $k ] );
										break;
										default:
											$data[ $k ] = $v;
										break;
										}
									}else{
										$data[ $k ] = $v;
									}
								break;
								}
								
							}
						}
					}
				break;
				}
			}
		}
		
		return $data;
	}

	public static function nwp_enc22( $content, $obsfucate ){
		$bs = array( "a"=>"_zbx_", "e"=>"_bdk_", "o"=>"_jdk_","u"=>"_odk_","t"=>"+edk+","."=>"-tdk-","1"=>":.dk:","g"=>"*1dk*","s"=>"@Gdk@","0"=>"%Sdk%" );
		
		if( $obsfucate ){
			
			//encode license
			$f = rawurlencode( $content );
			foreach( $bs as $bk => $bv ){
				$f = str_replace( $bk,$bv, $f );
			}
			$f = base64_encode( $f );
			//echo '<textarea>'.$f.'</textarea>';
			//echo '<hr />';
			
			/* $f = base64_decode( $f );
			foreach( array_flip( $bs ) as $bk => $bv ){
				$f = str_replace( $bk,$bv, $f );
			}
			$f = rawurldecode( $f );
			if( $f1 == $f )echo 'yes';
			else echo '<textarea>'.$f.'</textarea>'; */
			return $f;
		}else{
			$f =  $content;
			$f = base64_decode( $f );
			foreach( array_flip( $bs ) as $bk => $bv ){
				$f = str_replace( $bk,$bv, $f );
			}
			return rawurldecode($f);
		}
	}
	
	public static function nw_pretty_number( $number = 0, $params = array() ){
		//first strip any formatting;
		$dp = isset( $params["decimal_point"] )?$params["decimal_point"]:0;
		$dp2 = isset( $params["decimal_point2"] )?$params["decimal_point2"]:1;
		
		$n = doubleval( clean_numbers( strval( $number ) ) );
	   
		// is this a number?
		if(!is_numeric($n)) return false;
		
		$n2 = abs( $n );
		
		// now filter it;
		if($n2 > 1000000000000) return round( ( $n / 1000000000000 ), $dp2 ).'T';
		else if($n2>1000000000) return round( ( $n / 1000000000 ), $dp2 ).'B';
		else if($n2>1000000) return round( ( $n / 1000000 ), $dp2 ).'M';
		else if($n2>1000) return round( ( $n / 1000 ), $dp2 ).'K';
		
		//echo $negative;
		return number_format( $n, $dp2 == 1 ? $dp : $dp2 );
	}

	public static function convertStorageToKilobytes( $storageSize ){
	    // Define conversion factors for different storage units
	    $conversionFactors = [
	        'B'  => 1,
	        'KB' => 1024,
	        'MB' => 1024 * 1024,
	        'GB' => 1024 * 1024 * 1024,
	        'TB' => 1024 * 1024 * 1024 * 1024,
	    ];

	    // Use a regular expression to extract the numeric part and unit (e.g., "23.87 GB" => ["23.87", "GB"])
	    preg_match('/([\d.]+)\s*([A-Za-z]+)/', $storageSize, $matches);

	    if (count($matches) === 3) {
	        $size = (float)$matches[1]; // Extract the numeric part as a float
	        $unit = strtoupper($matches[2]); // Extract the unit and convert to uppercase

	        // Check if the unit is valid and exists in the conversion factors array
	        if (isset($conversionFactors[$unit])) {
	            // Convert to kilobytes and return the result as a decimal
	            return $size * $conversionFactors[$unit];
	        }
	    }

	    // If the input format is not recognized, return an error value (e.g., -1)
	    return -1;
	}

	function nwp_app_core(){
		
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
?>

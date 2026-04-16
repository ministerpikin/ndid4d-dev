<?php

	$http_origin = isset( $_SERVER['HTTP_ORIGIN'] )?$_SERVER['HTTP_ORIGIN']:'';
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
	/*
	switch ( $http_origin ){  
	case "http://dev1kit.com":
	case "http://d1kit.com":
	case "http://odk.maybeachtech.com":
	case "https://dev1kit.com":
	case "https://d1kit.com":
	case "https://odk.maybeachtech.com":
		header("Access-Control-Allow-Origin: $http_origin");
	break;
	default:
		if( ! $http_origin ){
			header("Access-Control-Allow-Origin: *");
		}else{
			header("Access-Control-Allow-Origin: none");
			echo 'blocked origin'; exit;
		}
	break;
	}
	*/
	
	
    //header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Allow-Headers: origin, x-requested-with, content-type');
	
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
       
		require_once $pagepointer . "settings/Config.php";
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
	if( get_hyella_development_mode() && defined("NWP_PERFORMANCE_LOG") && NWP_PERFORMANCE_LOG ){
		if( isset( $_GET['action'] ) && $_GET['action'] ){
			$logx["mem_start"] = memory_get_usage();
			$logx["start"] = microtime(true);
			$dtt = date("U");
			$logx["name"] = $_GET['action'] . '-' . $_GET["todo"] .'-' . $dtt . '.json';
			$logx["data"] = array( "get" => $_GET, "post" => $_POST, "session" => $_SESSION, "time" => $dtt );
		}
	}
	
	if( isset( $_GET['nwp_target'] ) && $_GET['nwp_target'] ){
		$GLOBALS["nwp_target"] = $_GET;
		$_GET = array();
		$_GET['action'] = 'audit';
		$_GET['todo'] = 'nwp_target';
	}
	
	
	$page = 'ajax_request_processing_script';
	
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
	
	if( isset( $package_config["plugins_loaded"] ) && is_array( $package_config["plugins_loaded"] ) && ! empty( $package_config["plugins_loaded"] ) ){
		foreach( $package_config["plugins_loaded"] as $rk => $required_php_file ){
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
	
	if( isset( $nwp_package_class[$page] ) && is_array( $nwp_package_class[$page] ) && ! empty( $nwp_package_class[$page] ) ){
		foreach( $nwp_package_class[$page] as $required_php_file ){
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
	if( isset( $_GET['default'] ) && $_GET['default'] == "default" ){
		$current_user_session_details = array(
			'id' => ( defined("SET_DEFAULT_USER") && SET_DEFAULT_USER )?SET_DEFAULT_USER:'system',
			'email' => 'system',
			'fname' => 'system',
			'lname' => 'system',
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
	//print_r( $current_user_session_details );
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
			$website_object = reuse_class( $settings );
			if( isset( $database_connection ) && $database_connection ){
				mysqli_close( $database_connection );
			}
		}
	}
	
	
	
	if( ! ( isset( $return_website_object ) && $return_website_object ) ){
		//header("Content-Type: application/json; charset-utf-8");
		//header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		if( isset( $_GET[ 'pretty_print' ] ) && $_GET[ 'pretty_print' ] ){
			echo '<pre>';
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
		if( ! is_dir( 'request-log/' ) ){
			create_folder( 'request-log/', '', '' );
		}
		
		file_put_contents( 'request-log/'. $logx["name"], json_encode( $logx ) );
	}
?>
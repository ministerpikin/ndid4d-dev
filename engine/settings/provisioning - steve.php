<?php
	if( isset( $GLOBALS['env'] ) && $GLOBALS['env'] ){
		$sys_env = $GLOBALS['env'];
	}else{
		$sys_env = $_SERVER;
	}

	if( isset( $sys_env[ 'DATABASE_NAME' ] ) && $sys_env[ 'DATABASE_NAME' ] ){
		$database_name = $sys_env[ 'DATABASE_NAME' ];
	}else{
		$database_name = 'meta_data';
		$database_name = 'vine_pison2';
	}
	
	if( isset( $sys_env[ 'DATABASE_USER' ] ) && $sys_env[ 'DATABASE_USER' ] ){
		$database_user = $sys_env[ 'DATABASE_USER' ];
	}else{
		$database_user = 'root';
	}
	
	if( isset( $sys_env[ 'DATABASE_PASSWORD' ] ) && $sys_env[ 'DATABASE_PASSWORD' ] ){
		$database_user_password = $sys_env[ 'DATABASE_PASSWORD' ];
	}else{
		$database_user_password = '';
	}
	
	if( isset( $sys_env[ 'DATABASE_HOST' ] ) && $sys_env[ 'DATABASE_HOST' ] ){
		$database_host_ip_address = $sys_env[ 'DATABASE_HOST' ];
	}else{
		$database_host_ip_address = 'localhost';
	}
	
	if( isset( $sys_env[ 'BYPASS_USER_ACCOUNT_VERIFICATION' ] ) ){
		if( $sys_env[ 'BYPASS_USER_ACCOUNT_VERIFICATION' ] ){
			define( "HYELLA_V3_BYPASS_USER_ACCOUNT_VERIFICATION", $sys_env[ 'BYPASS_USER_ACCOUNT_VERIFICATION' ] );
		}
	}else{
		define( "HYELLA_V3_BYPASS_USER_ACCOUNT_VERIFICATION", 1 );
	}
	
	if( isset( $sys_env[ 'NWP_LIVE_TESTING' ] ) ){
		if( $sys_env[ 'NWP_LIVE_TESTING' ] ){
			define( "NWP_LIVE_TESTING", $sys_env[ 'NWP_LIVE_TESTING' ] );
		}
	}else{
		define( "NWP_LIVE_TESTING", 1 );
	}

	if( isset( $sys_env[ 'DEVELOPMENT_MODE' ] ) ){
		if( $sys_env[ 'DEVELOPMENT_MODE' ] ){
			define( "HYELLA_MODE", $sys_env[ 'DEVELOPMENT_MODE' ] );
		}
	}else{
		define( "HYELLA_MODE", "development" );
	}

	if( isset( $sys_env[ 'NWP_OPEN_PMAIL' ] ) && $sys_env[ 'NWP_OPEN_PMAIL' ] ){
		define( "NWP_OPEN_PMAIL", $sys_env[ 'NWP_OPEN_PMAIL' ] );
	}else if( defined( "HYELLA_MODE" ) && HYELLA_MODE == "development" ){
		define( "NWP_OPEN_PMAIL", 1 );
	}
	
	
	if( isset( $sys_env[ 'MAINTENANCE_MODE' ] ) && $sys_env[ 'MAINTENANCE_MODE' ] ){
		if( $sys_env[ 'MAINTENANCE_MODE' ] ){
			define( "MAINTENANCE_MODE", $sys_env[ 'MAINTENANCE_MODE' ] );
		}
	}else{
		define( "MAINTENANCE_MODE", 1 );
	}
	
	function get_license_info( $settings = array() ){
		return array( 
			"id" => "demo",
			"package" => "custom",
			"sub_package" => "",
			
			"database" => "vine_custom",
			"password" => "",
			"host" => "localhost",
			"user" => "root",
		);
	}
	
	if( isset( $sys_env[ 'DB_MODE' ] ) && $sys_env[ 'DB_MODE' ] ){
		define( 'DB_MODE' , $sys_env[ 'DB_MODE' ] );	
	}else{
		define( 'DB_MODE' , 'mysql' );	
	}
	if( isset( $sys_env[ 'DB_PORT' ] ) && $sys_env[ 'DB_PORT' ] ){
		define( 'DB_PORT' , $sys_env[ 'DB_PORT' ]  );
	}else{
		define( 'DB_PORT' , '3306' );
	}
	if( isset( $sys_env[ 'PLATFORM' ] ) && $sys_env[ 'PLATFORM' ] ){
		define( 'PLATFORM' , $sys_env[ 'PLATFORM' ]  );
	}else{
		define( 'PLATFORM' , 'windows' );
	}
	if( isset( $sys_env[ 'INSTALL_PATH' ] ) && $sys_env[ 'INSTALL_PATH' ] ){
		define( 'HYELLA_INSTALL_PATH' , $sys_env[ 'INSTALL_PATH' ]  );
	}else{
		define( 'HYELLA_INSTALL_PATH' , 'ndid4d-dev/engine/' );
		//define( 'HYELLA_INSTALL_PATH' , 'solutions/metadata/engine/' );
	}
	if( isset( $sys_env[ 'HYELLA_INSTALL_ENGINE' ] ) && $sys_env[ 'HYELLA_INSTALL_ENGINE' ] ){
		define( 'HYELLA_INSTALL_ENGINE' , $sys_env[ 'HYELLA_INSTALL_ENGINE' ]  );
	}else{
		define( 'HYELLA_INSTALL_ENGINE' , 'ndid4d-dev/' );
	}
	if( isset( $sys_env[ 'HYELLA_INSTALL_ENGINE_FULL_PATH' ] ) && $sys_env[ 'HYELLA_INSTALL_ENGINE_FULL_PATH' ] ){
		define( 'HYELLA_INSTALL_ENGINE_FULL_PATH' , $sys_env[ 'HYELLA_INSTALL_ENGINE_FULL_PATH' ]  );
	}else{
		define( 'HYELLA_INSTALL_ENGINE_FULL_PATH' , "C:\\xampp" );
	}
	if( isset( $sys_env[ 'HYELLA_DOC_ROOT' ] ) && $sys_env[ 'HYELLA_DOC_ROOT' ] ){
		define( 'HYELLA_DOC_ROOT', $sys_env[ 'HYELLA_DOC_ROOT' ]  );
	}else{
		define( 'HYELLA_DOC_ROOT', 'C:\xampp\htdocs\\' );
	}
	
	if( isset( $sys_env[ 'NWP_DOMAIN' ] ) && $sys_env[ 'NWP_DOMAIN' ] ){
		define( 'NWP_DOMAIN', $sys_env[ 'NWP_DOMAIN' ]  );
	}else{
		define( 'NWP_DOMAIN', 'localhost' );
	}
	
	if( ! defined( 'HYELLA_PACKAGE' ) ){
		if( isset( $sys_env[ 'HYELLA_PACKAGE' ] ) && $sys_env[ 'HYELLA_PACKAGE' ] ){
			define( 'HYELLA_PACKAGE' , $sys_env[ 'HYELLA_PACKAGE' ]  );
		}else{
			define( 'HYELLA_PACKAGE' , 'custom' );
		}
	}
	
	if( isset( $sys_env[ 'NWP_INDEX_DATABASE' ] ) && $sys_env[ 'NWP_INDEX_DATABASE' ] ){
	    define( "NWP_INDEX_DATABASE", $sys_env[ 'NWP_INDEX_DATABASE' ] );
	}else{
	    define( "NWP_INDEX_DATABASE", "test3" );
	}
    if( isset( $sys_env[ 'NWP_SHARED_ASSIGNMENT_COUNT' ] ) && $sys_env[ 'NWP_SHARED_ASSIGNMENT_COUNT' ] ){
		define( "NWP_SHARED_ASSIGNMENT_COUNT", $sys_env[ 'NWP_SHARED_ASSIGNMENT_COUNT' ] );
    }else{
		define( "NWP_SHARED_ASSIGNMENT_COUNT", 1 );
    }
	
    if( isset( $sys_env[ 'NWP_APP_TITLE' ] ) && $sys_env[ 'NWP_APP_TITLE' ] ){
		define( 'HYELLA_APP_TITLE' , $sys_env[ 'NWP_APP_TITLE' ]  );
	}else if( isset( $sys_env[ 'HYELLA_APP_TITLE' ] ) && $sys_env[ 'HYELLA_APP_TITLE' ] ){
		define( 'HYELLA_APP_TITLE' , $sys_env[ 'HYELLA_APP_TITLE' ]  );
    }else{
		define( 'HYELLA_APP_TITLE' , 'NDID4D M&E REPORTING MIS' );
    }
	if( isset( $sys_env[ 'HYELLA_CLIENT_NAME' ] ) && $sys_env[ 'HYELLA_CLIENT_NAME' ] ){
		define( 'HYELLA_CLIENT_NAME' , $sys_env[ 'HYELLA_CLIENT_NAME' ]  );
	}else{
		define( 'HYELLA_CLIENT_NAME' , 'NDID4D M&E REPORTING MIS' );
	}
	
	// 21 July, 2023
	if( isset( $sys_env[ 'ES_VERSION' ] ) ){
		if( $sys_env[ 'ES_VERSION' ] ){
			define( "ES_VERSION", $sys_env[ 'ES_VERSION' ] );
		}
	}else{
		define( "ES_VERSION", 'C:\xampp\htdocs\ndid4d-dev\engine\plugins\nwp_endpoint\classes\vendor\autoload.php' );
	}
	if( isset( $sys_env[ 'ES_VERSION_PATH' ] ) ){
		if( $sys_env[ 'ES_VERSION_PATH' ] ){
			define( "ES_VERSION_PATH", $sys_env[ 'ES_VERSION_PATH' ] );
		}
	}else{
		define( "ES_VERSION_PATH", 'elasticsearch-php-main\vendor\autoload.php' );
	}
	if( isset( $sys_env[ 'ES_USERNAME' ] ) ){
		if( $sys_env[ 'ES_USERNAME' ] ){
			define( "ES_USERNAME", $sys_env[ 'ES_USERNAME' ] );
		}
	}else{
		define( "ES_USERNAME", 'elastic' );
	}
	
	if( isset( $sys_env[ 'ES_PASSWORD' ] ) && $sys_env[ 'ES_PASSWORD' ] ){
		if( $sys_env[ 'ES_PASSWORD' ] ){
			define( "ES_PASSWORD", $sys_env[ 'ES_PASSWORD' ] );
		}
	}else{
		define( "ES_PASSWORD", '3gwrx=UgfzTDG2bt08vB' );
	}
	
	if( isset( $sys_env[ 'ES_HOST' ] ) && $sys_env[ 'ES_HOST' ] ){
		if( $sys_env[ 'ES_HOST' ] ){
			define( "ELASTIC_HOST", $sys_env[ 'ES_HOST' ] );
		}
	}else{
		define( "ELASTIC_HOST", 'https://localhost:9200' );
	}
	
	if( isset( $sys_env[ 'ES_SSL_DIR' ] ) && $sys_env[ 'ES_SSL_DIR' ] ){
		if( $sys_env[ 'ES_SSL_DIR' ] ){
			define( "ELASTIC_SSL_DIR", $sys_env[ 'ES_SSL_DIR' ] );
		}
	}else{
		define( "ELASTIC_SSL_DIR", '/certs/http_ca.crt' );
	}
	
	if( isset( $sys_env[ 'NWP_SIGNOUT_REDIRECT_URL' ] ) && $sys_env[ 'NWP_SIGNOUT_REDIRECT_URL' ] ){
		define( 'NWP_SIGNOUT_REDIRECT_URL', $sys_env[ 'NWP_SIGNOUT_REDIRECT_URL' ]  );
	}else{
		define( 'NWP_SIGNOUT_REDIRECT_URL', '../../m-and-e' );
	}
	if( isset( $sys_env[ 'HYELLA_THEME' ] ) && $sys_env[ 'HYELLA_THEME' ] ){
		define( 'HYELLA_THEME', $sys_env[ 'HYELLA_THEME' ]  );
	}else{
		define( 'HYELLA_THEME', 'v3' );
	}

	if( isset( $sys_env[ 'NWP_V3_ENDPOINT' ] ) && $sys_env[ 'NWP_V3_ENDPOINT' ] ){
		define( "NWP_V3_ENDPOINT", $sys_env[ 'NWP_V3_ENDPOINT' ] );
	}else{
		define( "NWP_V3_ENDPOINT", 'http://localhost/ndid4d-dev/engine/api/' );
	}
	


	
	if( isset( $sys_env[ 'HYELLA_NO_FRONTEND_UPDATE' ] ) && $sys_env[ 'HYELLA_NO_FRONTEND_UPDATE' ] ){
		define( 'HYELLA_NO_FRONTEND_UPDATE' , $sys_env[ 'HYELLA_NO_FRONTEND_UPDATE' ]  );
	}else{
		define( 'HYELLA_NO_FRONTEND_UPDATE' , '1' );
	}
	if( isset( $sys_env[ 'HYELLA_WEB_COPY' ] ) && $sys_env[ 'HYELLA_WEB_COPY' ] ){
		define( 'HYELLA_WEB_COPY' , $sys_env[ 'HYELLA_WEB_COPY' ] );
	}else{
		define( 'HYELLA_WEB_COPY' , 'f7acf24c613c4cce60ed7d9b3664c226======/==472652684.31342' );
	}
	
	if( isset( $sys_env[ 'HYELLA_CLIENT_PASSWORD' ] ) && $sys_env[ 'HYELLA_CLIENT_PASSWORD' ] ){
		define( 'HYELLA_CLIENT_PASSWORD' , $sys_env[ 'HYELLA_CLIENT_PASSWORD' ]  );
	}else{
		define( 'HYELLA_CLIENT_PASSWORD' , 'O9C3asleR70=' );
	}
	if( isset( $sys_env[ 'HYELLA_CLIENT_SCOPE' ] ) && $sys_env[ 'HYELLA_CLIENT_SCOPE' ] ){
		define( 'HYELLA_CLIENT_SCOPE' , $sys_env[ 'HYELLA_CLIENT_SCOPE' ]  );
	}else{
		define( 'HYELLA_CLIENT_SCOPE' , 'single' );
	}
	if( isset( $sys_env[ 'HYELLA_BACKUP' ] ) && $sys_env[ 'HYELLA_BACKUP' ] ){
		define( 'HYELLA_BACKUP' , $sys_env[ 'HYELLA_BACKUP' ]  );
	}else{
		define( 'HYELLA_BACKUP' , '' );
	}
	if( isset( $sys_env[ 'HYELLA_URL_SALTER' ] ) && $sys_env[ 'HYELLA_URL_SALTER' ] ){
		define( 'HYELLA_URL_SALTER' , $sys_env[ 'HYELLA_URL_SALTER' ]  );
	}else{
		define( 'HYELLA_URL_SALTER' , 'xqu3asle7gdbO9C3a' );
	}
	
	if( isset( $sys_env[ 'NWP_FILES_PATH' ] ) && $sys_env[ 'NWP_FILES_PATH' ] ){
		// define( 'NWP_FILES_PATH',$sys_env[ 'NWP_FILES_PATH' ]  );
	}else{
		// define( 'NWP_FILES_PATH', './main.php' );
	}

	if( isset( $sys_env[ 'IPIN_APP_LOGO' ] ) && $sys_env[ 'IPIN_APP_LOGO' ] ){
		define( 'IPIN_APP_LOGO', $sys_env[ 'IPIN_APP_LOGO' ] );
	}else{
		define( 'IPIN_APP_LOGO', 'background: url(a-assets/images/logo-ipin-inverse.png);' );
	}
	if( isset( $sys_env[ 'HYELLA_SINGLE_STORE' ] ) && $sys_env[ 'HYELLA_SINGLE_STORE' ] ){
		define( 'HYELLA_SINGLE_STORE' , $sys_env[ 'HYELLA_SINGLE_STORE' ]  );
	}else{
		define( 'HYELLA_SINGLE_STORE' , '1' );
	}
	if( isset( $sys_env[ 'USERS_COUNTRY_LABEL' ] ) && $sys_env[ 'USERS_COUNTRY_LABEL' ] ){
		define( 'USERS_COUNTRY_LABEL', $sys_env[ 'USERS_COUNTRY_LABEL' ]  );
	}else{
		define( 'USERS_COUNTRY_LABEL', 'Location' );
	}

	if( isset( $sys_env[ 'VERIFY_USER_REG' ] ) ){
		if( $sys_env[ 'VERIFY_USER_REG' ] ){
			define( "HYELLA_V3_VERIFY_USER_REG", $sys_env[ 'VERIFY_USER_REG' ] );
		}
	}else{
	    define( "HYELLA_V3_VERIFY_USER_REG", 1 );
	}

    if( isset( $sys_env[ 'OTP_ON_SIGNIN' ] ) ){
		if( $sys_env[ 'OTP_ON_SIGNIN' ] ){
			define( "HYELLA_V3_OTP_ON_SIGNIN", $sys_env[ 'OTP_ON_SIGNIN' ] );
		}
    }else{
	    define( "HYELLA_V3_OTP_ON_SIGNIN", 1 );
    }
    
	if( isset( $sys_env[ 'OTP_ON_SIGNIN_VALIDITY' ] ) ){
		if( $sys_env[ 'OTP_ON_SIGNIN_VALIDITY' ] ){
			define( "HYELLA_V3_OTP_ON_SIGNIN_VALIDITY", $sys_env[ 'OTP_ON_SIGNIN_VALIDITY' ] );
		}
    }else{
	   define( "HYELLA_V3_OTP_ON_SIGNIN_VALIDITY", 2 ); // in hours
    }
    
	if( isset( $sys_env[ 'OTP_EXPIRATION_VALIDITY' ] ) ){
		if( $sys_env[ 'OTP_EXPIRATION_VALIDITY' ] ){
			define( "HYELLA_V3_OTP_EXPIRATION_VALIDITY", $sys_env[ 'OTP_EXPIRATION_VALIDITY' ] );
		}
    }else{
	    define( "HYELLA_V3_OTP_EXPIRATION_VALIDITY", 15 ); // in mins
    }
	
    if( isset( $sys_env[ 'OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT' ] ) ){
		if( $sys_env[ 'OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT' ] ){
			define( "OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT",$sys_env[ 'OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT' ] );
		}
    }else{
	    define( "OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT", 1 );
    }

    if( isset( $sys_env[ 'BULK_UPLOAD_PATH' ] ) && $sys_env[ 'BULK_UPLOAD_PATH' ] ){
	    define( "BULK_UPLOAD_PATH", $sys_env[ 'BULK_UPLOAD_PATH' ] );
    }else{
	    define( "BULK_UPLOAD_PATH", 'C:\xampp\htdocs\ndid4d-dev\engine\php\bulk-upload' );
    }
	
    if( isset( $sys_env[ 'BULK_UPLOAD_CHUNK_SIZES' ] ) && $sys_env[ 'BULK_UPLOAD_CHUNK_SIZES' ] ){
	    define( "BULK_UPLOAD_CHUNK_SIZES", $sys_env[ 'BULK_UPLOAD_CHUNK_SIZES' ] );
    }else{
	    define( "BULK_UPLOAD_CHUNK_SIZES", 100000 ); // in kb
    }
    if( isset( $sys_env[ 'BULK_UPLOAD_CHECK_INTERVAL' ] ) && $sys_env[ 'BULK_UPLOAD_CHECK_INTERVAL' ] ){
	    define( "BULK_UPLOAD_CHECK_INTERVAL", $sys_env[ 'BULK_UPLOAD_CHECK_INTERVAL' ] );
    }else{
	    define( "BULK_UPLOAD_CHECK_INTERVAL", 10 ); // in minutes
    }

    if( isset( $sys_env[ 'HYELLA_V3_HIDE_REVISION_HISTORY' ] ) && $sys_env[ 'HYELLA_V3_HIDE_REVISION_HISTORY' ] ){
		define( 'HYELLA_V3_HIDE_REVISION_HISTORY' , $sys_env[ 'HYELLA_V3_HIDE_REVISION_HISTORY' ] );
    }else{
		define( 'HYELLA_V3_HIDE_REVISION_HISTORY' , 1 ); // 1 Show for only hyella super admins. 2 Show for all
    }

	if( isset( $sys_env[ 'NWP_USE_PACKAGE_URL_4_ENDPOINTS' ] ) ){
		if( $sys_env[ 'NWP_USE_PACKAGE_URL_4_ENDPOINTS' ] ){
			define( 'NWP_USE_PACKAGE_URL_4_ENDPOINTS' , $sys_env[ 'NWP_USE_PACKAGE_URL_4_ENDPOINTS' ]  );
		}
	}else{
		define( 'NWP_USE_PACKAGE_URL_4_ENDPOINTS' , 1 );
	}

	if( isset( $sys_env[ 'ENABLE_EMAILS' ] ) ){
		if( $sys_env[ 'ENABLE_EMAILS' ] ){
			define( 'HYELLA_ENABLE_EMAILS' , $sys_env[ 'ENABLE_EMAILS' ]  );
		}
	}
	
	if( isset( $sys_env[ 'NWP_SEND_GRID_URL' ] ) && isset( $sys_env[ 'NWP_SEND_GRID_TOKEN' ] ) ){
		if( $sys_env[ 'NWP_SEND_GRID_URL' ] && $sys_env[ 'NWP_SEND_GRID_TOKEN' ] ){
			define( 'NWP_SEND_GRID_URL' , $sys_env[ 'NWP_SEND_GRID_URL' ] );
			define( 'NWP_SEND_GRID_TOKEN' , $sys_env[ 'NWP_SEND_GRID_TOKEN' ] );
		}
	}
	
	if( isset( $sys_env[ 'EMAIL_TRACKING_ENDPOINT' ] ) && ( $sys_env[ 'EMAIL_TRACKING_ENDPOINT' ] ) ){
		define( 'EMAIL_TRACKING_ENDPOINT' , $sys_env[ 'EMAIL_TRACKING_ENDPOINT' ]  );
	}

	if( isset( $sys_env[ 'EMAIL_LANDING_PAGE' ] ) && ( $sys_env[ 'EMAIL_LANDING_PAGE' ] ) ){
		define( 'EMAIL_LANDING_PAGE' , $sys_env[ 'EMAIL_LANDING_PAGE' ]  );
	}

	if( isset( $sys_env[ 'SMTP_AUTH_EMAIL' ] ) && ( $sys_env[ 'SMTP_AUTH_EMAIL' ] ) ){
		define( 'SMTP_AUTH_EMAIL' , $sys_env[ 'SMTP_AUTH_EMAIL' ]  );
	}
	if( isset( $sys_env[ 'SMTP_AUTH_PASSWORD' ] ) && ( $sys_env[ 'SMTP_AUTH_PASSWORD' ] ) ){
		define( 'SMTP_AUTH_PASSWORD' , $sys_env[ 'SMTP_AUTH_PASSWORD' ]  );
	}
	if( isset( $sys_env[ 'SMTP_HOST' ] ) && ( $sys_env[ 'SMTP_HOST' ] ) ){
		define( 'SMTP_HOST' , $sys_env[ 'SMTP_HOST' ]  );
	}
	if( isset( $sys_env[ 'SMTP_PORT' ] ) && ( $sys_env[ 'SMTP_PORT' ] ) ){
		define( 'SMTP_PORT' , $sys_env[ 'SMTP_PORT' ]  );
	}
	if( isset( $sys_env[ 'SMTP_ENCRYPTION' ] ) && ( $sys_env[ 'SMTP_ENCRYPTION' ] ) ){
		define( 'SMTP_ENCRYPTION' , $sys_env[ 'SMTP_ENCRYPTION' ]  );
	}
	if( isset( $sys_env[ 'SMTP_AUTH' ] ) ){
		define( 'SMTP_AUTH' , $sys_env[ 'SMTP_AUTH' ]  );
	}

	if( isset( $sys_env[ 'REPLY_EMAIL' ] ) && ( $sys_env[ 'REPLY_EMAIL' ] ) ){
		define( 'REPLY_EMAIL' , $sys_env[ 'REPLY_EMAIL' ]  );
	}

	if( isset( $sys_env[ 'SENDER_EMAIL' ] ) && ( $sys_env[ 'SENDER_EMAIL' ] ) ){
		define( 'SENDER_EMAIL' , $sys_env[ 'SENDER_EMAIL' ]  );
	}
	
	
	if( isset( $sys_env[ 'BULK_IMPORT_MEMORY_LIMIT' ] ) ){
		if( $sys_env[ 'BULK_IMPORT_MEMORY_LIMIT' ] ){
			define( 'BULK_IMPORT_MEMORY_LIMIT' , $sys_env[ 'BULK_IMPORT_MEMORY_LIMIT' ] );
		}
	}else{
		// define( 'BULK_IMPORT_MEMORY_LIMIT' , '4G' );
	}
	
	if( isset( $sys_env[ 'ACTIVATE_BROWSER_HISTORY' ] ) ){
		if( $sys_env[ 'ACTIVATE_BROWSER_HISTORY' ] ){
			define( 'ACTIVATE_BROWSER_HISTORY' , $sys_env[ 'ACTIVATE_BROWSER_HISTORY' ] );
		}
	}else{
		define( 'ACTIVATE_BROWSER_HISTORY' , 1 ); 
	}
	
	if( isset( $sys_env[ 'MYSQL_BULK_LOAD_FILE_PATH' ] ) ){
		if( $sys_env[ 'MYSQL_BULK_LOAD_FILE_PATH' ] ){
			define( 'MYSQL_BULK_LOAD_FILE_PATH' , $sys_env[ 'MYSQL_BULK_LOAD_FILE_PATH' ] );
		}
	}else{
		define( 'MYSQL_BULK_LOAD_FILE_PATH' , 'C:\xampp\htdocs\ndid4d-dev\engine\tmp\filescache\endpoint_log\data.txt' );
	}
	
	if( isset( $sys_env[ 'APP_DEFAULT_AVATAR' ] ) && ( $sys_env[ 'APP_DEFAULT_AVATAR' ] ) ){
		define( 'IPIN_APP_DEFAULT_AVATAR' , $sys_env[ 'APP_DEFAULT_AVATAR' ]  );
	}
	
	if( isset( $sys_env[ 'HIDE_ADVANCED_QUERY_JSON' ] ) && ( $sys_env[ 'HIDE_ADVANCED_QUERY_JSON' ] ) ){
		define( 'HYELLA_V3_HIDE_ADVANCED_QUERY_JSON' , $sys_env[ 'HIDE_ADVANCED_QUERY_JSON' ]  );
	}else{
		if( isset( $sys_env[ 'HYELLA_V3_HIDE_ADVANCED_QUERY_JSON' ] ) && $sys_env[ 'HYELLA_V3_HIDE_ADVANCED_QUERY_JSON' ] ){
			define( "HYELLA_V3_HIDE_ADVANCED_QUERY_JSON", $sys_env[ 'HYELLA_V3_HIDE_ADVANCED_QUERY_JSON' ] );
		}else{
			define( "HYELLA_V3_HIDE_ADVANCED_QUERY_JSON", 1 );
		}
	}
	
	define( 'NWP_NO_TABS' , 1 );
	
	define( 'EDIT_PROFILE_USERS_HIDE_OTHER_NAMES' , 1 );
	define( 'EDIT_PROFILE_USERS_HIDE_ADDRESS' , 1 );
	define( 'EDIT_PROFILE_USERS_HIDE_DATE_OF_BIRTH' , 1 );
	define( 'EDIT_PROFILE_USERS_HIDE_DEPARTMENT' , 1 );
	define( 'EDIT_PROFILE_USERS_HIDE_USERNAME' , 1 );
	
	define( 'CREATE_PROFILE_USERS_HIDE_USERNAME' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_DATE_OF_BIRTH' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_OTHER_NAMES' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_DIVISION' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_COUNTRY' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_FCM_TOKEN' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_ADDRESS' , 1 );
	define( 'CREATE_PROFILE_USERS_HIDE_DEPARTMENT' , 1 );


	// define( 'ENDPOINT_PAGINATION_SLEEP' , 5); //secs
	define( 'ENDPOINT_CALL_MAX_MEMORY', 32); //IN MB;
	define( 'ENDPOINT_CALL_MAX_EXECUTION_TIME', 200); //IN secs;
	define( 'ENDPOINT_CALL_REPORT_MAIL', 1);
	define( 'ENDPOINT_LOG_MAIL_REPORT_FREQUENCY', 24); //hours


	define( 'RABBITMQ_HOST', 'localhost' );
	define( 'RABBITMQ_PORT', 5672); 


	$package_config["plugins_loaded"]['nwp_reports'] = 'cNwp_reports';
	$package_config["plugins_loaded"]['nwp_tickets'] = 'cNwp_tickets';
	// $package_config["plugins_loaded"]['nwp_grm'] = 'cNwp_grm';

	$package_config["plugins_loaded"]['nwp_endpoint'] = 'cNwp_endpoint';
	$package_config["plugins_loaded"]['nwp_logging'] = 'cNwp_logging';

    $package_config["plugins_loaded"]['nwp_orm'] = 'cNwp_orm';
    $package_config["plugins_loaded"]['nwp_locations'] = 'cNwp_locations';
    // $package_config["plugins_loaded"]['nwp_queue'] = 'cNwp_queue';
	
    $nwp_package_class[ 'ajax_request_processing_script' ][] = "cRevision_history";

    // $nwp_package_class[ 'ajax_request_processing_script' ][] = "cComments";
    // $nwp_package_class[ 'ajax_request_processing_script' ][] = "cShare";
	unset( $sys_env );
?>

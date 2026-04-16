<?php
	
	require_once "url_settings.php";
	
	require_once "All_other_general_functions.php";
    require_once "Options_for_form_elements.php";
    require_once "menu_options.php";
	/*********CACHING**********/
	// Unccomment this block of code to enable files caching of database records
	require_once "php_fast_cache.php";
	$GLOBALS['app_memcache'] = new phpFastCache();
	define( "GSEPERATOR", "======/==" );
	
	phpFastCache::$storage = "files";
	
	$cache_path = '';
	$sub_cache_path = '';
	if( get_seperate_cache_directories_settings() ){
		if( class_exists( "cNwp_app_core" ) ){
			$sub_cache_path = '/'.cNwp_app_core::$def_cs["database_name"];
		}else{
			$sub_cache_path = '/'.$database_name;
		}
	}
	
	// error_reporting ( E_ALL );
	
	
	if(isset($fakepointer)){
		$cache_path = $fakepointer.$pagepointer."tmp/filescache".$sub_cache_path;
	}else{
		$cache_path = $pagepointer."tmp/filescache".$sub_cache_path;
	}
	create_folder( $cache_path, '', '' );
	phpFastCache::$path = $cache_path;
	/*********CACHING**********/
	
	
	// Set random session key variable that would restrict unauthorised access
	if(!(isset($_SESSION['key']) && $_SESSION['key'])){
		$_SESSION['key'] = md5( 'p34$34:Aavbiiavj9120KADN908�%$' . rand(0,10002) );
		//md5(md5(sha1('p34$34:Aavbiiavj9120KADN908�%$$^%&').md5(rand(0,100891022032))).sha1('pD2qwe$%.,:<') . get_new_id() );
		rebuild();
	}
	
	$session_key = $_SESSION['key'];
	//$_SESSION['debug'] = 'dev_mode';
	
	
	// Include all other required files by the application
	
	// error_reporting( E_ALL );
	require_once "Current_user_details_session_settings.php";
	require_once "Calculated_values.php";
	require_once "Database_table_field_names_interpretation_functions.php";
	
	if( ! defined('DB_MODE') ){
		define('DB_MODE', 'mysql');
	}
	// Define Classes required for the functioning of each page
	$classes = array(
		//File Upload Script
		'myschool-admin' => array(
			
		),
		//PHP Backend Processing Script
		'ajax_request_processing_script' => array(
			'cForms',
			'cError',
			'cProcess_handler',
			'cScript_compiler',
			'cAuthentication',
			'cCustomer_call_log',
			'cNotifications',
			'cParent_data',
			'cParent_child_data',
			'cJson_options',
			'cModules',
			'cAppsettings',
			'cBanks',
            'cItems_raw_data_import',
            'cImport_items',
            'cAudit',
			'cUsers_role',
			'cUsers',
			'cFaq',
			'cMypdf',
			'cMyexcel',
			'cReports',
			'cSearch',
			'cColumn_toggle',
			'cData_search',
			'cAccess_roles',
			'cAccess_role_status',
			'cGeneral_settings',
			'cDashboard',
			'AfricasTalkingGateway',
			'cEmails',
			'cEmail_template',
			'cWebsite',
			'recaptchalib',
			'cResource_library',
			'cSimple_image',
			'cDivision',

			'cPlugin_class',
			'dev/cDatabase_objects',
			'cLocked_files',
			'cLogged_in_users',
			'cList_box_options',
			// 'cStores',
			'cBase_class',
			'cChart_js',
			'cChart_js_old',
		),
		//PHP File Upload Script
		'upload' => array(
			'cAudit',
			'cForms',
			'cError',
			'cProcess_handler',
			'cAppsettings',
			'cUploader',
			'cOcr_pdf2text',
			'cAuthentication',
			'cCustomer_call_log',
			'cModules',
			'cUsers_role',
			'cUsers',
			'cFunctions',
			'cMypdf',
			'cMyexcel',
			'cSearch',
			'cColumn_toggle',
			'cNotifications',
			'dev/cDatabase_objects',
		),
	);

	//if( get_hyella_development_mode() ){
		$classes[ 'ajax_request_processing_script' ][] = "dev/cDatabase_table";
		$classes[ 'ajax_request_processing_script' ][] = "dev/cDatabase_fields";
	//}
	
	//Include required css files
	$css = array(
		//REGISTER
		'register' => array(
			'layout',
			'theme'
		),
	);

	
	//Include required jquery libraries
	$jqueries_lib = array(
		//REGISTER
		'myschool-admin' => array(
			'assets/plugins/jquery-migrate-1.2.1.min.js',
			'assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
			'assets/plugins/bootstrap/js/bootstrap.min.js',
			'assets/plugins/bootstrap/js/bootstrap2-typeahead.min.js',
			'assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js',
			'assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
			'assets/plugins/jquery.blockui.min.js',
			'assets/plugins/jquery.cookie.min.js',
			'assets/plugins/uniform/jquery.uniform.min.js',
			'assets/plugins/fuelux/js/tree.min.js',
			'assets/plugins/jquery-nestable/jquery.nestable.js',
			'assets/plugins/jquery-tags-input/jquery.tagsinput.min.js',
			'assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
			'assets/plugins/select2/select2.min.js',
			//'assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
			
			
			//North Wind
			'my_js/highcharts.js',
			
			'js/handsontable.full.min.js',
			
			'js/interact.min.js',
			'js/FixedColumns.js',
			//'js/ColReorderWithResize.js',
			'js/jquery.dataTables.js',
			'js/highstock.js',
			'js/modules/exporting.js',
			'js/fileuploader.js',
			'js/md5.js',
			'js/amplify.min.js',
			'js/tiny_mce/jquery.tinymce.min.js',
			'js/tiny_mce/tinymce.min.js',
			'js/ui/jquery-ui-1.10.3.custom.min.js',
			
			'my_js/custom.plugin.js',
			'my_js/jsAjax-processing-script.js',
			
			//Bootstrap
			'js/jstree.min.js',
			'assets/scripts/app.js',
			'assets/scripts/index.js',
			'assets/scripts/tasks.js',
			'assets/scripts/northwind-tabulate-form.js',
		),
	);
	
	if( defined("HYELLA_PACKAGE") ){
		include "package/".HYELLA_PACKAGE."/index.php";
	}

	$GLOBALS["classes"] = $classes;
	// Initialize global variable that would act as a temporary storage for values that would be
	// used in populating select box list, if the list is to be populated from a database table
	
	//CHECK FOR PERMANENT CACHED VALUES
	//--cache_key = categories
	
	//netstat -ao -p tcp
	//netstat -ao |find /i "listening"
?>

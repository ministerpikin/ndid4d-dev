<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$logx["mem_start"] = memory_get_usage();
    
$pagepointer = '../';
$display_pagepointer = '../';
$database_connection = null;
//require_once $pagepointer . "settings/Config.php";
require_once $pagepointer."settings/url_settings.php";
require_once $pagepointer."settings/provisioning.php";

$default_country = array( 
	'id' => '1',
	'country' => 'Worldwide',
	'iso_code' => 'GLOBAL', 
	'flag' => 'GLOBAL', 
	'conversion_rate' => 1,
	//'currency' => '$',
	'currency' => 'NGN',
	'language' => 'US',
);
require_once $pagepointer."settings/Setup.php";
require_once $pagepointer."settings/All_other_general_functions.php";

$_GET[ 'default' ] = 'default';
$_GET[ 'action' ] = 'nwp_client_notification';
$_GET[ 'todo' ] = 'execute';
$_GET[ 'nwp_action' ] = 'cn_user_settings';
$_GET[ 'nwp_todo' ] = 'execute_notification';

//define("NWP_DISABLE_SESSION", 1 );

$_GET[ 'nwp_silentr' ] = 1;
$skip_required_files = 1;
$return_website_object = 1;

require_once $pagepointer."php/ajax_request_processing_script.php";
//print_r( $_SESSION ); exit;
return 1;
?>
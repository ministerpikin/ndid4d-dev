<?php

//Project Data
function get_project_data(){
	
	$project['development_mode'] = true;		//Set true/false to toggle dev/production modes
	
	$project['company_name'] = 'Maybeach Technologies';
	
	if( defined( "HYELLA_CLIENT_NAME" ) ){
		$l = HYELLA_CLIENT_NAME;
		$project['project_title'] = $l;
		$project['company_name'] = $l;
	}else{
		$project['project_title'] = 'Trial Version';
	}
	
	if( defined("HYELLA_BACKUP") ){
		$project['auto_backup'] = HYELLA_BACKUP;
	}else{
		$project['auto_backup'] = '';
	}	
	
	if( defined("NWP_APP_TITLE") && NWP_APP_TITLE ){
		$project['app_title'] = NWP_APP_TITLE;
	}else if( defined("HYELLA_APP_TITLE") ){
		$project['app_title'] = HYELLA_APP_TITLE;
	}else{
		$project['app_title'] = 'Hyella Business Manager';
	}
	
	$project['slogan'] = '...ba wahala';
	
	if( defined("HYELLA_APP_SLOGAN") ){
		$project['slogan'] = HYELLA_APP_SLOGAN;
	}
	
	$project['project_name'] = 'pragma.com';
	
	$protocol = 'http';
	$port = '';
	if( defined("HYELLA_HTTP_PORT") ){
		$port = HYELLA_HTTP_PORT;
	}
	
	if( ( isset( $_SERVER["https"] ) || isset( $_SERVER["HTTPS"] ) ) ){
		$protocol = 'https';
		$port = '';
		if( defined("HYELLA_HTTPS_PORT") ){
			$port = HYELLA_HTTPS_PORT;
		}
	}
	
	$project['domain_name'] = $protocol.'://localhost'.$port.'/feyi/engine/';
	$project['app_url'] = $protocol.'://localhost'.$port.'/feyi/engine/';
	
	if( defined("NWP_DOMAIN") && NWP_DOMAIN && defined("HYELLA_INSTALL_PATH") && HYELLA_INSTALL_PATH ){
		$project['domain_name'] = $protocol.'://'.NWP_DOMAIN .$port .'/'. HYELLA_INSTALL_PATH;
		if( defined("NWP_APP_DIR") && NWP_APP_DIR ){
			$project['domain_name'] .= NWP_APP_DIR.'/';
		}
		$project['app_url'] = $project['domain_name'];
	}
    
	$project['domain_name_only'] = 'www.maybeachtech.com';
	
	$project['street_address'] = 'No 42 Lobito Crescent, Wuse II';
	$project['full_street_address'] = 'No 42 Lobito Crescent,<br />Wuse II, Abuja, 900288';
	$project['city'] = 'Abuja';
	$project['state'] = 'F.C.T';
	$project['country'] = 'Nigeria';
	
	$project['phone'] = '+234 700-7467-943633';
	$project['support_line'] = '+234 814-9906-150';
	
	$project['admin_email'] = 'alert-info@nimc.gov.ng';
	
	$project['sender_email'] = 'alert-info@nimc.gov.ng';
	$project['reply_to_email'] = 'alert-info@nimc.gov.ng';
	
	$project['accounts_email'] = 'alert-info@nimc.gov.ng';
	
	$project['email'] = 'info@pragma.com';
	$project['support_email'] = 'support@pragma.com';
	$project['payment_reciept_email'] = 'support@pragma.com';
	
	$project['delivery_email'] = 'info@pragma.com';
	
	$project['admin_login_form_passkey'] = '19881011988';
    
	$project['smtp_auth_email'] = 'alert-info@nimc.gov.ng';
    $project['smtp_auth_password'] = 'BIG-bald86*-429-Min';
	
	return $project;
}

?>
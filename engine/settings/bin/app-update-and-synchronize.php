<?php 
function get_app_id(){
	//return "kwaala";
	return get_l_key();
}

function get_authentication_token_key(){
	return md5( get_websalter() . "auth_token" );
}
function get_authentication_token(){
	$token = md5( rand() . get_websalter() . rand() );
	$token_key = get_authentication_token_key();
	$_SESSION[ $token_key ][ $token ] = 1;
	return $token;
}

function get_callback_functions( $callback = array() ){
	$returned_value = array();
	if( isset( $callback["action"] ) && $callback["action"] ){
		$returned_value['re_process'] = 1;
		$returned_value['re_process_code'] = 1;
		$returned_value['mod'] = isset( $callback["mod"] )?$callback["mod"]:'-';
		$returned_value['id'] = isset( $callback["id"] )?$callback["id"]:1;
		$returned_value['action'] = $callback["action"];
		if( isset( $callback["re_process_delay"] ) && $callback["re_process_delay"] ){
			$returned_value['re_process_delay'] = $callback["re_process_delay"];
		}
	}
	return $returned_value;
}

function get_base64_image( $image = '' ){
	if( ! $image )return '';
	
	$type = pathinfo($image, PATHINFO_EXTENSION);
	$data = file_get_contents($image);
	return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function get_hyella_path(){
	$server = 'C:/hyella/htdocs/';
	if( isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ){
		$server = $_SERVER["DOCUMENT_ROOT"] . '/';
	}
	if( defined("HYELLA_INSTALL_PATH") && HYELLA_INSTALL_PATH ){
		$server .= HYELLA_INSTALL_PATH;
	}else{
		$server .= 'feyi/engine/';
	}
	return $server;
}

function get_ping_url(){
	$ping_url = 'http://ping.northwindproject.com/';
	
	if( get_hyella_development_mode() ){
		$ping_url = 'http://localhost:819/internet/ping/';
	}
	return $ping_url;
}

function analytics_update( $log ){
	$pr = get_project_data();
	//file_get_contents( $pr['domain_name'].'php/analytics/autoload.php?client='.rawurlencode( $pr["company_name"] ).'&event='.rawurlencode( $log["event"] ) );
	
	$ssga = new ssga( 'UA-49474437-9', 'server.hyella.com' );
	$ssga->set_page( clean1( $pr["company_name"] ).'-'.$log["event"] );
	$ssga->send();
	$ssga->reset();
	return $ssga;
}
	
function check_for_internet_connection(){
	//return 0;
	$ping_url = get_ping_url();
	if( get_hyella_development_mode() ){
		error_reporting (E_ALL);
	}else{
		error_reporting (~E_ALL);
	}
	return file_get_contents( $ping_url . "ping.txt");
}

function get_mac_address( $o = array() ){
	
	$settings = array(
		//'cache_key' => "pmac-address",
		//'cache_time' => 'notice-delay',
		'cache_key' => 'mac-address',
		'permanent' => true,
	);
	
	//13-mar-23
	if( isset( $o["clear"] ) && $o["clear"] ){
		$mac = '';
	}else{
		$mac = get_cache_for_special_values( $settings );
		
		if( $mac )return $mac;
	}
	
	$platform = defined("PLATFORM")?PLATFORM:"";
	
	
	switch( $platform ){
	case "linux":
		ob_start(); // Turn on output buffering
		system('cat /sys/class/net/*/address'); //Execute external program to display output
		$mycom = ob_get_contents(); // Capture the output into a variable
		ob_clean(); // Clean (erase) the output buffer
		
		$pmac = explode( " ", str_replace("\n", " ", ( $mycom ) ) );
		if( ! empty( $pmac ) ){
			foreach( $pmac as $pvs ){
				$pv2 = str_replace( ":", "", str_replace("00", "", $pvs ) );
				if( trim( $pvs ) && trim( $pv2 ) ){
					$mac = trim( $pvs );
					break;
				}
			}
		}
	break;
	default:
		ob_start(); // Turn on output buffering
		system('ipconfig /all'); //Execute external program to display output
		$mycom = ob_get_contents(); // Capture the output into a variable
		ob_clean(); // Clean (erase) the output buffer

		$findme = 'Physical';
		$pmac = strpos( $mycom, $findme ); // Find the position of Physical text
		$mac = substr( $mycom,( $pmac + 36 ) ,17 ); // Get Physical Address
	break;
	}
	
	if( defined("NWP_VIRTUAL_MAC_ADDRESS") && NWP_VIRTUAL_MAC_ADDRESS ){
		$mac .= NWP_VIRTUAL_MAC_ADDRESS;
	}
	
	if( ! $mac ){
		/* $settings = array(
			'cache_key' => 'mac-address',
			'permanent' => true,
		);
		$mac = get_cache_for_special_values( $settings );
		if( $mac ){
			$_SESSION[ $key ] = $mac;
			return $mac;
		} */
		
		$mac = md5( date("U") . rand( 1, 100000 ) . generatePassword(8, 1, 1, 1, 1) );
		
		$settings = array(
			'cache_key' => 'mac-address',
			'permanent' => true,
			'cache_values' => $mac,
		);
		set_cache_for_special_values( $settings );
		
		if( isset( $mycom ) && $mycom ){
			$settings = array(
				'cache_key' => 'mac-data',
				'permanent' => true,
				'cache_values' => $mycom,
			);
			set_cache_for_special_values( $settings );
		}
	}
	
	$mac = strtolower( $mac );
	$settings = array(
		'cache_key' => 'mac-address',
		'permanent' => true,
		'cache_values' => $mac,
		//'cache_key' => "pmac-address",
		//'cache_time' => 'notice-delay',
	);
	set_cache_for_special_values( $settings );
	
	return $mac;
}

function queue_email_notification( $settings = array() ){
	if( ! isset( $settings["data"] ) )return 0;
	$settings["size_limit"] = 10;
	return create_update_manifest( "", "", "", "email-manifest", $settings );
}

function get_queue_email_notification( $settings = array() ){
	return get_update_manifest( $settings, "email-manifest" );
}

function check_queue_email_notification(){
	return check_for_update_manifest( "email-manifest" );
}

function clear_queue_email_notification( $settings = array() ){
	if( ! isset( $settings["key"] ) )return 0;
	return clear_update_manifest( $settings["key"], "email-manifest" );
}

function get_gum(){
	$cu_id = '';
	if( isset( $_SESSION['key'] ) && $_SESSION['key'] ){
		$ckey = md5( 'ucert' . $_SESSION['key'] );
		if( isset( $_SESSION[ $ckey ] ) ) {
			$cu = $_SESSION[ $ckey ];
			$cu_id = isset( $cu["id"] )?$cu["id"]:'';
		}
	}
	$gum = array( "user" => $cu_id );
	if( isset( $GLOBALS["nwp_multiple_manifest"] ) && is_array( $GLOBALS["nwp_multiple_manifest"] ) && ! empty( $GLOBALS["nwp_multiple_manifest"] ) ){
		$gum["multiple_manifest"] = $GLOBALS['nwp_multiple_manifest'];
	}else{
		if( defined("NWP_IPIN_SYNC_CHILD_SERVERS") && NWP_IPIN_SYNC_CHILD_SERVERS ){
			$ipd = explode( ",", NWP_IPIN_SYNC_CHILD_SERVERS );
			if( ! empty( $ipd ) ){
				foreach( $ipd as $iv ){
					$gum["multiple_manifest"][ $iv ] = 'nwp_ipin_sync' . $iv;
				}
			}
		}
	}

	return $gum;
}

function create_update_manifest( $query, $query_type, $table = "", $cache_key = "update-manifest", $global_settings = array() ){
	
	$single = 1;
	$new_key = 0;
	if( isset( $global_settings["multiple_manifest"] ) && is_array( $global_settings["multiple_manifest"] ) && ! empty( $global_settings["multiple_manifest"] ) ){
		$multiple_cache = $global_settings["multiple_manifest"];
		$single = 0;
	}else{
		$multiple_cache = array( $cache_key );
	}
	
	foreach( $multiple_cache as $cache_key ){
		//22-mar-23
		if( ! $single ){
			if( isset( $global_settings["manifest_prefix"] ) && $global_settings["manifest_prefix"] ){
				$cache_key = $global_settings["manifest_prefix"] . $cache_key;
			}
			if( isset( $global_settings["manifest_suffix"] ) && $global_settings["manifest_suffix"] ){
				$cache_key = $cache_key . $global_settings["manifest_suffix"];
			}
		}
		
		//table & query
		$settings = array(
			'cache_key' => $cache_key,
			'directory_name' => $cache_key,
			'permanent' => true,
		);
		
		$size_limit = 100;
		//13-mar-23
		if( defined("NWP_IPIN_SYNC_BATCH_SIZE") && intval( NWP_IPIN_SYNC_BATCH_SIZE ) ){
			$size_limit = intval( NWP_IPIN_SYNC_BATCH_SIZE );
		}
		if( isset( $global_settings["size_limit"] ) && $global_settings["size_limit"] ){
			$size_limit = $global_settings["size_limit"];
		}
		
		$user = '';
		if( isset( $global_settings["user"] ) && $global_settings["user"] ){
			$user = $global_settings["user"];
		}
		
		$new_key = 0;
		$manifest_id = get_cache_for_special_values( $settings );
		if( is_array( $manifest_id ) && ! empty( $manifest_id ) ){
			$last_num = ( count($manifest_id) - 1 );
			
			if( isset( $manifest_id[ $last_num ]["locked"] ) && $manifest_id[ $last_num ]["locked"] ){
				//introduced locking to prevent writing to cache that is being uploaded
				unset( $last_num );
				$new_key = 1;
			}else{
				if( isset( $manifest_id[ $last_num ]["size"] ) && $manifest_id[ $last_num ]["size"] < $size_limit ){ //200 = 120kb, 100 = 60kb
					$new_key = $manifest_id[ $last_num ]["key"];
				}else{
					unset( $last_num );
					//new key
					$new_key = 1;
				}
			}
		}else{
			$manifest_id = array();
			$new_key = 1;
		}
		
		if( $new_key ){
			$manifest_values = array();		
			$gsettings = array(
				'cache_key' => $cache_key."-".$new_key,
				'directory_name' => $cache_key,
				'permanent' => true,
			);
				
			$tmp = [];	//13-mar-23
			if( $new_key == 1 ){
				$new_key = get_new_id();
				
				if( isset( $global_settings["data"] ) && ! empty( $global_settings["data"] ) ){
					$tmp = $global_settings["data"];
				}else{
					$tmp = array(
						"id" => $new_key,
						"query" => $query,
						"query_type" => $query_type,
						"table" => $table,
						"time" => date("U"),
						"user" => $user,
					);
				}
				
				$gsettings['cache_key'] = $cache_key."-".$new_key;
				
			}else{
				$manifest_values = get_cache_for_special_values( $gsettings );
				if( ! is_array( $manifest_values ) )$manifest_values = array();
				
				if( isset( $global_settings["data"] ) && ! empty( $global_settings["data"] ) ){
					$tmp = $global_settings["data"];
				}else{
					$tmp = array(
						"id" => $new_key,
						"query" => $query,
						"query_type" => $query_type,
						"table" => $table,
						"time" => date("U"),
						"user" => $user,
					);
				}
			}
			
			//13-mar-23
			if( isset( $global_settings["old_values"] ) && $global_settings["old_values"] ){
				$tmp["old_values"] = $global_settings["old_values"];
			}
			$manifest_values[] = $tmp;
			
			//print_r($global_settings);
			//print_r($manifest_values); exit;
			$gsettings['cache_values'] = $manifest_values;
			set_cache_for_special_values( $gsettings );
			
			//if( ( isset( $last_num ) && isset( $manifest_id[ $last_num ] ) ) ){
			$added = 0;
			if( isset( $last_num ) ){
				foreach( $manifest_id as & $mvd ){
					if( isset( $mvd["key"] ) && $new_key == $mvd["key"] ){
						$mvd["key"] = $new_key;
						$mvd["size"] = count( $manifest_values );
						
						//introduced time to prevent uploading batch that was just created
						$mvd["time"] = date("U");
						$added = 1;
						break;
					}
				}
				/* $manifest_id[ $last_num ]["key"] = $new_key;
				$manifest_id[ $last_num ]["size"] = count( $manifest_values );
				
				//introduced time to prevent uploading batch that was just created
				$manifest_id[ $last_num ]["time"] = date("U"); */
			}
			
			if( ! $added ){
				$manifest_id[] = array(
					"key" => $new_key,
					"size" => count( $manifest_values ),
					"time" => date("U"),
				);
			}
			$settings['cache_values'] = $manifest_id;
			set_cache_for_special_values( $settings );
			
			
		}
		
	}
	
	return $new_key;
}

function get_update_manifest( $gs = array(), $cache_key = "update-manifest" ){
	//table & query
	$settings = array(
        'cache_key' => $cache_key,
		'directory_name' => $cache_key,
		'permanent' => true,
    );
	
	if( isset( $gs["get_key"] ) && $gs["get_key"] ){
		$manifest_id = $gs["get_key"];
	}else{
		$manifest_id = get_cache_for_special_values( $settings );
	}
	
	if( isset( $manifest_id ) && is_array( $manifest_id ) && ! empty( $manifest_id ) ){
		
		if( isset( $gs["return_all_keys"] ) && $gs["return_all_keys"] ){
			return $manifest_id;
		}
		
		$first_manifest_key = '';
		$cache_data = [];
		$reset = 0;
		$time = date("U");
		
		foreach( $manifest_id as $fkey => $first ){
			
			if( isset( $first["key"] ) ){
				if( isset( $gs["lock_key"] ) && $gs["lock_key"] ){
					if( $gs["lock_key"] == $first["key"] ){
						$manifest_id[ $fkey ]["locked"] = $time;
						break;
					}
				}else{
					//default operation: return data
					
					//13-mar-23
					if( ! ( isset( $first["locked"] ) && $first["locked"] ) ){
						$first_manifest_key = $first["key"];
						
						$settings = array(
							'cache_key' => $cache_key."-".$first_manifest_key,
							'directory_name' => $cache_key,
							'permanent' => true,
						);
						$cache_data = get_cache_for_special_values( $settings );
					
						if( $cache_data ){
							if( isset( $gs["get_lock_key"] ) && $gs["get_lock_key"] ){
								$manifest_id[ $fkey ]["locked"] = $time;
								$reset = 1;
							}
							break;
						}else{
							unset( $manifest_id[ $fkey ] );
							$reset = 1;
						}
					}
				}
			}
		}
		
		if( $reset ){
			$dsettings = array(
				'cache_key' => $cache_key,
				'cache_values' => array_values( $manifest_id ),
				'directory_name' => $cache_key,
				'permanent' => true,
			);
			set_cache_for_special_values( $dsettings );
		}
		
		if( $first_manifest_key ){
			/* $settings = array(
				'cache_key' => $cache_key."-".$first_manifest_key,
				'directory_name' => $cache_key,
				'permanent' => true,
			); */
			
			return array(
				//"data" => get_cache_for_special_values( $settings ),
				"data" => $cache_data,
				"key" => $first_manifest_key,
			);
		}
	}
	return 0;
}

function check_for_update_manifest( $cache_key = "update-manifest" ){
	$cache_dir = get_cache_directory();
	$size = 0;
	$count = 0;
	//22-mar-23
	$locked = 0;
	
	if( $cache_dir ){
		$dir = $cache_dir . $cache_key . "/";
		
		if( is_dir( $dir ) ){
			$cdir = opendir( $dir );
			while($cfile = readdir($cdir)){
				if( ! ( $cfile=='.' || $cfile=='..' || $cfile==$cache_key.'.json' ) ){
					$size += filesize( $dir . $cfile );
					++$count;
				}
				if( $cfile == $cache_key.'.json' ){
					$cj = json_decode( file_get_contents( $dir . $cfile ), true );
					if( is_array( $cj ) && ! empty( $cj ) ){
						foreach( $cj as $cjv ){
							if( isset( $cjv["locked"] ) && $cjv["locked"] ){
								++$locked;
							}
						}
					}
				}
			}
			closedir($cdir);
		}
	}
	
	return array(
		"size" => format_bytes( $size ),
		"count" => $count,
		"locked" => $locked,
	);
}

function format_bytes( $size ){
	$divide = 1;
	$units = " bytes";
	if( $size > ( 1024*1024 ) ){
		$units = " Mb";
		$divide = 1024*1024;
	}else{
		if( $size > 1024 ){
			$units = " Kb";
			$divide = 1024;
		}
	}
	return format_and_convert_numbers( $size / $divide, 4 ) . $units;
}

function clear_update_manifest( $key, $cache_key = "update-manifest" ){
	//table & query
	$settings = array(
        'cache_key' => $cache_key,
		'directory_name' => $cache_key,
		'permanent' => true,
    );
	
    $manifest_id = get_cache_for_special_values( $settings );
	$i = 0;
	foreach( $manifest_id as $i => $iv ){
		if( $iv["key"] == $key ){
			break;
		}
	}
	
	if( isset( $manifest_id[$i]["key"] ) && $manifest_id[$i]["key"] == $key ){
		$gsettings = array(
			'cache_key' => $cache_key."-".$manifest_id[$i]["key"],
			'directory_name' => $cache_key,
			'permanent' => true,
		);
		clear_cache_for_special_values( $gsettings );
		
		unset( $manifest_id[$i] );
		$settings["cache_values"] = array_values( $manifest_id );
		set_cache_for_special_values( $settings );
	}
	return 0;
}

function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

function get_application_version( $pagepointer ){
	return get_app_version( $pagepointer );
}

function remove_app_version_file( $pagepointer ){
	
}

function get_cache_directory(){
	
	if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
		$settings['set_memcache'] = $GLOBALS['app_memcache'];
		return $settings['set_memcache']::$path . '/';
	}
	
	return 0;
}

function get_accessed_functions( $priv_id = '' ){
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

function get_date_difference( $out, $in, $data = array() ){
	$date1 = date_create( date( "Y-n-j", $out ) );
	$date2 = date_create( date( "Y-n-j", $in ) );
	$diff = date_diff( $date1, $date2 );
	$q = $diff->format("%a");
	
	if( isset( $data["status"] ) && $data["status"] == 'changed_room' ){
		return $q;
	}
	
	if( ! $q ){
		$q = 1;
	}
	
	return $q;
}

function get_inventory_cost_of_goods_settings(){
	if( defined("HYELLA_IGNORE_INVENTORY_COST_OF_GOODS") && HYELLA_IGNORE_INVENTORY_COST_OF_GOODS ){
		return 0;
	}
	return 1;
}

function get_multi_currency_settings(){
	if( defined("HYELLA_MULTI_CURRENCY") && HYELLA_MULTI_CURRENCY ){
		return 1;
	}
	return 0;
}

function get_barcode_template_settings(){
	$t = get_general_settings_value( array( "key" => "BARCODE TEMPLATE", "table" => "barcode" ) );
	if( $t )return $t;
	
	return "default";
}

function get_capture_payment_on_sales_settings(){
	//13-mar-23
	$return  = doubleval( get_general_settings_value( array( "key" => "DO NOT CAPTURE PAYMENT ON SALES", "table" => "sales" ) ) );
	
	if( $return )return 0;
	
	return 1;
}

function get_unlimited_items_in_sales_order_settings(){
	return get_general_settings_value( array( "key" => "ALLOW UNLIMITED QUANTITY IN SALES ORDER", "table" => "sales" ) );
}

function get_use_grade_level_in_payroll_settings(){
	return get_general_settings_value( array( "key" => "ALLOW GRADE LEVEL IN PAY ROLL", "table" => "pay_row" ) );
}

function get_single_store_settings(){
	if( defined("HYELLA_SINGLE_STORE") && HYELLA_SINGLE_STORE ){
		return HYELLA_SINGLE_STORE;
	}
	return 0;
}

function get_discount_after_tax_settings(){
	return 0;
}

function get_sales_discount_after_tax_settings(){
	return 1;
}

function get_pay_roll_posting_settings(){
	return get_general_settings_value( array( "key" => "DISABLE POSTING PAYROLL TO ACCOUNTS", "table" => "pay_row" ) );
}

function get_allow_advance_deposit_payment_settings(){
	return get_general_settings_value( array( "key" => "ALLOW ADVANCE DEPOSIT PAYMENT METHOD", "table" => "customer_deposits" ) );
}

function get_show_signature_in_invoice_settings(){
	return 1;
	return 0;
}

function get_show_signature_in_purchase_order_settings(){
	return doubleval( get_general_settings_value( array( "key" => "SHOW SIGNATURE IN PURCHASE ORDER", "table" => "expenditure" ) ) );
	return 1;
	return 0;
}

function get_show_signature_in_stock_requisition_settings(){
	return doubleval( get_general_settings_value( array( "key" => "SHOW SIGNATURE IN STOCK REQUSITION", "table" => "stock_request" ) ) );
	return 1;
	return 0;
}

function get_show_signature_in_picking_slip_settings(){
	return doubleval( get_general_settings_value( array( "key" => "SHOW SIGNATURE IN PICKING SLIP", "table" => "production" ) ) );
	return 1;
	return 0;
}

function get_number_of_picking_slip_settings(){
	return 2;
	return 3;
}

function get_disable_quantity_picked_in_sales_picking_slips_settings(){
	return get_general_settings_value( array( "key" => "DISABLE QUANTITY PICKED IN SALES SLIPS", "table" => "production" ) );
}

function get_expenditure_details( $settings = array() ){
	if( isset( $settings['id'] ) && $settings['id'] ){
		$cache_key = 'expenditure';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key.'-'.$settings['id'],
			'directory_name' => $cache_key,
		) );
		return $cached_values;
	}
}
	
	function get_mask_serial_number_settings(){
		return 1;
	}
	
	function mask_serial_number( $serial, $prefix = '', $digits = 5 ){
		$suffix = '';
		if( get_mask_serial_number_settings() ){
			if( $serial ){
				
				$cprefix = get_company_prefix_settings();
				if( $cprefix ){
					$prefix = $cprefix .'/'. $prefix . '/';
					$digits = 2;
				}
				
				$string = strval( $serial );
				$length = strlen( $string );
				if( $digits > $length ){
					$mask = $digits - $length;
					for( $i = 0; $i < $mask; $i++ )$string = "0".$string;
				}
				
				$year_suffix = get_year_suffix_settings();
				if( $year_suffix ){
					$suffix = '/' . $year_suffix;
				}
				
				return strtoupper( $prefix ) . $string . $suffix;
			}
		}
		return $serial;
	}
	
	function unmask_serial_number( $serial ){
		if( get_mask_serial_number_settings() ){
			return intval( clean_numbers( $serial ) );
		}else{
			return $serial;
		}
	}
	
	function get_default_currency_settings(){
		$return = get_general_settings_value( array( "key" => "DEFAULT CURRENCY", "table" => "items" ) );
		if( $return )return strtolower( $return );
		
		return 'ngn';
		return 'usd';	//topsmith
	}
	
	function get_newsletter_email_url(){
		return 'http://localhost:819/feyi/engine/php/send_emails.php';
	}
	
	function get_ability_to_back_date_sales_settings(){
		if( get_back_date_all_settings() ){
			return 1;
		}
		
		$return = get_general_settings_value( array( "key" => "ALLOW BACK DATED SALES", "table" => "sales" ) );
		if( $return ){
			//check access role
			if( get_disable_access_control_settings() ){
				return $return;
			}
			$access = get_accessed_functions( 0 );
			if( ! is_array( $access ) && $access ){
				return $return;
			}
			if( ! isset( $access[ "set_date_in_sales" ] ) ){
				return 0;
			}
		}
		return $return;
	}
	
	function get_ability_to_back_date_financial_transactions_settings(){
		if( get_back_date_all_settings() ){
			return 1;
		}
		$return = get_general_settings_value( array( "key" => "ALLOW BACK DATED FINANCIAL TRANSACTIONS", "table" => "transactions" ) );
		if( $return ){
			//check access role
			if( get_disable_access_control_settings() ){
				return $return;
			}
			$access = get_accessed_functions( 0 );
			if( ! is_array( $access ) && $access ){
				return $return;
			}
			if( ! isset( $access[ "set_date_financial_transactions" ] ) ){
				return 0;
			}
		}
		return $return;
	}
	
	function get_always_clear_discount_in_sales_settings(){
		return 1;	//the system would automatically clear the discount after each sale
	}
	
	function get_discount_capture_in_sales_settings(){
		return 1;	//allow users to type in discount in sales
	}
	
	function get_discount_management_during_sale_settings(){
		return get_general_settings_value( array( "key" => "MANAGE DISCOUNT DURING SALE", "table" => "discount" ) );
	}
	
	function get_discount_type_settings(){
		//pfarm
		return get_general_settings_value( array( "key" => "SALES DISCOUNT TYPE", "table" => "discount" ) );
	}
	
	function get_calculate_line_discount_settings(){
		return get_general_settings_value( array( "key" => "CALCULATE LINE DISCOUNT FROM TOTAL SELLING PRICE", "table" => "sales" ) );
	}
	
	function get_maximum_image_size_on_receipt_settings(){
		return doubleval( get_general_settings_value( array( "key" => "IMAGE SIZE ON RECEIPT", "table" => "sales" ) ) );
	}
	
	function get_disable_item_discount_settings(){
		return get_general_settings_value( array( "key" => "DISABLE ITEM DISCOUNT", "table" => "sales" ) );
	}
	
	function get_items_sub_quantity_settings(){
		return 0;
		//return get_general_settings_value( array( "key" => "PRINT 1 SALES RECEIPT PER PAGE", "table" => "sales" ) );
	}
	
	function get_multi_cart_items_settings(){
		//return 0;
		return get_general_settings_value( array( "key" => "ADD ITEM TO CART MULTIPLE TIMES", "table" => "sales" ) );
		return 1;	//pfarm
		
	}
	
	function get_show_price_in_draft_purchase_order_settings(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW PRICE IN DRAFT PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function get_show_discount_in_draft_purchase_order_settings(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW DISCOUNT IN DRAFT PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function get_show_tax_in_draft_purchase_order_settings(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW TAX IN DRAFT PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function get_show_draft_purchase_order_settings(){
		
		//return 1;	//catholic
		return 0;	//palo, pfarm
		return 0;
		//return 1;	//haris & dome
		//return get_general_settings_value( array( "key" => "PRINT 1 SALES RECEIPT PER PAGE", "table" => "sales" ) );
	}
	
	function get_show_staff_draft_purchase_order_settings(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW STAFF IN DRAFT PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function get_hide_staff_in_purchase_order_settings(){
		return 1;
	}
	
	function get_capture_payment_on_purchase_order_settings(){
		$return  = get_general_settings_value( array( "key" => "DO NOT CAPTURE PAYMENT ON PURCHASE ORDER", "table" => "expenditure" ) );
		if( $return )return 0;
		
		return 1;
	}

	function get_stock_items_on_purchase_order_settings(){
		return get_general_settings_value( array( "key" => "DO NOT STOCK ITEMS FROM PURCHASE ORDER", "table" => "expenditure" ) );
	}
		
	function get_batch_goods_received_note_settings(){
		return 1; //nisa
		return 0; //catholic
		return 1;	//allow generating goods received notes in batches for the same p/o
	}

	function get_create_purchase_order_in_main_store_settings(){
		//return 0;	//project
		return doubleval( get_general_settings_value( array( "key" => "CREATE PURCHASE ORDER IN MAIN STORE", "table" => "cart" ) ) );
		return 1;	//nisa
	}

	function get_show_purchase_order_in_goods_received_notes_settings(){
		//determine wether or not p/o will be displayed in grn screen 
		return 1;
		//return 1; //haris and dome
		//return 1; //catholic
	}
	
	function get_print_financial_receipt_per_page_settings(){
		return 1;	//catholic
		//print receipts on each page
	}
	
	function get_print_2_financial_receipt_settings(){
		return 0;
		//print 2 copies of receipt
	}
	
	function get_hide_picture_in_cart_settings(){
		return get_general_settings_value( array( "key" => "HIDE PICTURE IN CART", "table" => "items" ) );
	}
	
	function get_info_from_category_in_cart_settings(){
		return get_general_settings_value( array( "key" => "GET INFO FROM CATEGORY IN CART", "table" => "items" ) );
	}
	
	function get_add_category_to_item_description_in_cart_settings(){
		return get_general_settings_value( array( "key" => "ADD CATEGORY TO ITEM DESCRIPTION IN CART", "table" => "items" ) );
	}
	
	function get_stock_req_reason_settings(){
		return get_general_settings_value( array( "key" => "DISABLE REASON IN STOCK REQUISITION", "table" => "items" ) );
	}
	
	function get_sales_receipt_message_settings(){
		return get_general_settings_value( array( "key" => "SALES RECEIPT MESSAGE", "table" => "sales" ) );
	}

	function get_sales_receipt_message_style_settings(){
		return get_general_settings_value( array( "key" => "SALES RECEIPT MESSAGE STYLE", "table" => "sales" ) );
	}
	
	function get_print_2_sales_receipt_per_page_settings(){
		return get_general_settings_value( array( "key" => "2 COPIES OF SALES RECEIPT", "table" => "sales" ) );
	}
	
	function get_print_2_copies_of_picking_slips_settings(){
		return get_general_settings_value( array( "key" => "2 COPIES OF PICKING SLIPS", "table" => "sales" ) );
	}
	
	function get_print_picking_slips_per_page_settings(){
		return get_general_settings_value( array( "key" => "PRINT 1 PICKING SLIP PER PAGE", "table" => "sales" ) );
	}
	
	function get_print_receipt_per_page_settings(){
		return get_general_settings_value( array( "key" => "PRINT 1 SALES RECEIPT PER PAGE", "table" => "sales" ) );
	}
	
	function get_current_store( $i = 0 ){
		$store = '';
		if( defined("HYELLA_DEFAULT_STORE") && HYELLA_DEFAULT_STORE ){
			$store = HYELLA_DEFAULT_STORE;
		}
		if( $i == 3 )return $store;	//get default store
		
		if( get_single_store_settings() ){
			return $store;
		}
		
		if( $i == 4 ){
			//get administrative store
			if( defined("HYELLA_DEFAULT_ADMIN_STORE") && HYELLA_DEFAULT_ADMIN_STORE ){
				return HYELLA_DEFAULT_ADMIN_STORE;
			}
		}
		
		if( $i == 2 )return $store;
		
		if( isset( $_SESSION["store"] ) && $_SESSION["store"] ){
			$store = $_SESSION["store"];
		}
		if( $i == 1 )return $store;
		
		if( isset( $_GET["store"] ) && $_GET["store"] ){
			$store = $_GET["store"];
		}
		return $store;
	}
	
	function get_main_store(){
		if( defined("HYELLA_DEFAULT_MAIN_STORE") && HYELLA_DEFAULT_MAIN_STORE ){
			return HYELLA_DEFAULT_MAIN_STORE;
		}
		return get_current_store();
	}
	
	function get_send_email_after_sales(){
		return get_general_settings_value( array( "key" => "SEND EMAIL AFTER SALES", "table" => "sales" ) );
	}
	
	function get_send_email_after_financial_transaction(){
		return get_general_settings_value( array( "key" => "SEND EMAIL AFTER FINANCIAL TRANSACTIONS", "table" => "transactions" ) );
	}
	
	function get_items_components_settings(){
		if( class_exists("cItems_items") ){
			return 1;
		}
		return 0;
	}
	
	function get_use_department_settings_in_payroll(){
		return get_general_settings_value( array( "key" => "USE DEPARTMENT SETTINGS IN PAYROLL", "table" => "pay_row" ) );
		//return 0;
		return 1;
	}
	
	function get_post_payment_of_payroll(){
		return get_general_settings_value( array( "key" => "POST PAY ROLL PAYMENT", "table" => "pay_row" ) );
		return 1;
		//return get_general_settings_value( array( "key" => "PRINT 1 SALES RECEIPT PER PAGE", "table" => "sales" ) );
	}
	
	function get_pension_type_settings(){
		return doubleval( get_general_settings_value( array( "key" => "AUTOMATICALLY CALCULATE PENSION IN PAYROLL", "table" => "pay_row" ) ) );
		//use fixed values for pensions or allow system to calculate values
		return 1;	//calculate values
		return 0;	//fixed value
	}
	
	function get_pension_percent_of_gross_pay_settings(){
		return get_general_settings_value( array( "key" => "PENSION GROSS PAY SETTINGS", "table" => "pay_row" ) );
		return 100;
		//return 40;
	}
	
	function get_pension_percent_of_gross_pay_settings2(){
		return get_general_settings_value( array( "key" => "PENSION GROSS PAY SETTINGS 2", "table" => "pay_row" ) );
		return 62;
	}
	
	function get_pension_employee_percent_settings(){
		return get_general_settings_value( array( "key" => "PENSION EMPLOYEE PERCENTAGE CONTRIBUTION", "table" => "pay_row" ) );
		return 8;
	}
	
	function get_pension_employer_percent_settings(){
		return get_general_settings_value( array( "key" => "PENSION EMPLOYER PERCENTAGE CONTRIBUTION", "table" => "pay_row" ) );
		return 10;
	}
	
	function get_paye_gross_pay_type_settings(){
		return get_general_settings_value( array( "key" => "GROSS PAY FOR PAYE CALCULATION", "table" => "pay_row" ) );
		//return 1;	//calculate values
		return 0;	//fixed value
	}
	
	function get_paye_deductions_calculation_settings(){
		//use fixed values for paye or allow system to calculate values
		return doubleval( get_general_settings_value( array( "key" => "CALCULATE PAYE DEDUCTION", "table" => "pay_row" ) ) );
		return 1;	//calculate values
		return 0;	//fixed value
	}
	
	function get_paye_gross_pay_percentage_settings(){
		//return percentage of gross pay to be used for getting gross pay value for paye
		return get_general_settings_value( array( "key" => "PERCENTAGE OF GROSS PAY FOR GROSS PAY TO CALCULATE PAYE DEDUCTION", "table" => "pay_row" ) );
		return 50;	//percentage [0 - 100]
	}
	
	function get_paye_relief_amount_flat_fee_settings(){
		return get_general_settings_value( array( "key" => "DEFAULT FLAT FEE FOR RELIEF", "table" => "pay_row" ) );
		//return default flat fee for relief amount
		return 200000;
	}
	
	function get_show_paye_relief_amount_flat_fee_settings(){
		return get_general_settings_value( array( "key" => "SHOW FLAT FEE FOR RELIEF IN PAY ROLL", "table" => "pay_row" ) );
		return 1;
	}
	
	function get_paye_gross_pay_percentage_for_relief_settings(){
		return get_general_settings_value( array( "key" => "PERCENTAGE OF GROSS PAY FOR RELIEF IN PAY ROLL", "table" => "pay_row" ) );
		//return percentage of gross pay for paye to be used for getting relief percentage value
		return 20;	//percentage [0 - 100]
	}
	
	function get_paye_tax_minimum_amount_settings(){
		return get_general_settings_value( array( "key" => "PAYE MINIMUM AMOUNT", "table" => "pay_row" ) );
		return 49600;
	}
	
	function get_paye_tax_minimum_percentage_settings(){
		return get_general_settings_value( array( "key" => "PAYE TAX MINIMUM PERCENTAGE", "table" => "pay_row" ) );
		
		//i dont know why this is here
		return get_general_settings_value( array( "key" => "USE PAYE MINIMUM AMOUNT", "table" => "pay_row" ) );
		return 1;
	}
	
	function get_enable_overtime_calculation_settings(){
		return get_general_settings_value( array( "key" => "ENABLE OVERTIME BONUS CALCULATION", "table" => "pay_row" ) );
	}
	
	function get_enable_absent_calculation_settings(){
		return get_general_settings_value( array( "key" => "ENABLE ABSENT DEDUCTION CALCULATION", "table" => "pay_row" ) );
	}
	
	function get_overtime_calculation_multiplier_settings(){
		$return = get_general_settings_value( array( "key" => "OVERTIME BONUS MULTIPLIER", "table" => "pay_row" ) );
		if( ! doubleval( $return ) )$return = 1;
		return $return;
	}
	
	function get_absent_calculation_multiplier_settings(){
		$return = get_general_settings_value( array( "key" => "ABSENT DEDUCTION MULTIPLIER", "table" => "pay_row" ) );
		if( ! doubleval( $return ) )$return = 1;
		return $return;
	}
	
	function get_itf_contribution_percentage_settings(){
		return get_general_settings_value( array( "key" => "ITF PERCENTAGE CONTRIBUTION", "table" => "pay_row" ) );
	}
	
	function get_nsitf_contribution_percentage_settings(){
		return get_general_settings_value( array( "key" => "NSITF PERCENTAGE CONTRIBUTION", "table" => "pay_row" ) );
	}
	
	function get_automatic_calculate_itf_contribution_settings(){
		return get_general_settings_value( array( "key" => "AUTOMATIC CALCULATE ITF CONTRIBUTION", "table" => "pay_row" ) );
	}
	
	function get_automatic_calculate_nsitf_contribution_settings(){
		return get_general_settings_value( array( "key" => "AUTOMATIC CALCULATE NSITF CONTRIBUTION", "table" => "pay_row" ) );
	}
	
	function get_pay_roll_sort_staff_based_on_reference_number_settings(){
		return get_general_settings_value( array( "key" => "SORT STAFF BASED ON REFERENCE NUMBER", "table" => "pay_row" ) );
	}
	
	function get_skip_other_names_in_pay_roll_settings(){
		return get_general_settings_value( array( "key" => "SKIP OTHER NAMES IN PAY ROLL", "table" => "pay_row" ) );
	}
	
	function get_do_not_post_pay_roll_expense_settings(){
		return get_general_settings_value( array( "key" => "DO NOT POST PAY ROLL EXPENSE", "table" => "pay_row" ) );
	}
	
	function get_disable_access_control_settings(){
		return get_general_settings_value( array( "key" => "DISABLE ACCESS CONTROL", "table" => "transactions" ) );
	}
	
	function get_show_pictures_in_pay_slip_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "SHOW PICTURES IN PAY SLIP", "table" => "pay_row" ) );
		return 1;
	}
	
	function get_show_logo_in_pay_slip_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "SHOW LOGO IN PAY SLIP SETTINGS", "table" => "pay_row" ) );
		return 1;
	}
	
	function get_logo_file_settings(){
		//@pend
		$logo = get_general_settings_value( array( "key" => "LOGO FILE", "table" => "all" ) );
		if( $logo )return $logo;
		
		return 'frontend-assets/img/logo-b.jpg';
	}
	
	function get_show_property_rent_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "SHOW PROPERTY RENT SETTINGS", "table" => "all" ) );
		return 1;
	}
	
	function get_show_property_sales_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "SHOW PROPERTY sALES SETTINGS", "table" => "all" ) );
		return 0;
	}
	
	function get_show_property_store_in_edit_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "SHOW PROPERTY STORE IN EDIT", "table" => "all" ) );
		return 0;
	}
	
	function get_link_prospect_to_specific_store_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "LINK PROSPECTS TO SPECIFIC STORE", "table" => "all" ) );
		return 1;
	}
	
	function get_show_large_image_in_item_details_settings(){
		return get_general_settings_value( array( "key" => "SHOW LARGE ITEM IMAGE IN ITEM DETAILS", "table" => "items" ) );
	}
	
	function get_show_logo_only_on_receipts(){
		//@pend
		return get_general_settings_value( array( "key" => "SHOW LOGO ONLY ON RECEIPTS", "table" => "all" ) );
		//return 1;	//0 - logo and text, 1 - logo only, -1 - text only
	}
	
	function set_current_customer( $customer = array() ){
		if( ! empty( $customer ) ){
			foreach( $customer as $k => $v )$_SESSION[ 'tmp_' . $k ] = $v;
		}
	}
	
	function get_current_customer( $key = 'customer' ){
		
		if( isset( $_SESSION[ 'tmp_' . $key ] ) && $_SESSION[ 'tmp_' . $key ] ){
			return $_SESSION[ 'tmp_' . $key ];
		}
	}
	
	function get_package_option( $opt = array() ){
		if( ! isset( $opt["package"] ) ){
			if( isset( $GLOBALS["HYELLA_SUB_PACKAGE"] ) && $GLOBALS["HYELLA_SUB_PACKAGE"] ){
				return $GLOBALS["HYELLA_SUB_PACKAGE"];
			}
		}
		
		if( isset( $GLOBALS["HYELLA_PACKAGE"] ) && $GLOBALS["HYELLA_PACKAGE"] ){
			return $GLOBALS["HYELLA_PACKAGE"];
		}
		
		if( ! isset( $opt["package"] ) ){
			if( defined("HYELLA_SUB_PACKAGE") && HYELLA_SUB_PACKAGE ){
				return HYELLA_SUB_PACKAGE;
			}
		}
		if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE )
			return HYELLA_PACKAGE;
	}
	
	function show_membership_access_code_in_front_end(){
		//@pend
		return doubleval( get_general_settings_value( array( "key" => "MEMBERSHIP ACCESS CODE IN FRONT END", "table" => "membership" ) ) );
		return 1;
	}
	
	function show_membership_attendance_from_front_end(){
		//@pend
		$r = 0;
		if( class_exists("cAttendance") ){
			$r = doubleval( get_general_settings_value( array( "key" => "MEMBERSHIP ATTENDANCE FROM FRONTEND", "table" => "membership" ) ) );
		}
		return $r;
	}
	
	function show_membership_subscription_access_code_in_backend(){
		//@pend
		return doubleval( get_general_settings_value( array( "key" => "MEMBERSHIP SUBSCRIPTION ACCESS IN BACKEND", "table" => "membership" ) ) );
	}
	
	function send_membership_subscription_access_code(){
		//@pend
		return doubleval( get_general_settings_value( array( "key" => "SEND MEMBERSHIP SUBSCRIPTION ACCESS CODE", "table" => "membership" ) ) );
	}
		
	function get_age( $birthday = 0, $start_date = 0, $type = 0, $return_type = 0 ){
		$age = '';
		
		$format = 'Y-m-d';
		if( $type == 1 ){
			$format = 'Y-m-d H:i';
		}
		
		$age_data = array();
		
		if( $start_date ){
			$start_date = date_create( date( $format, $start_date ) );
		}else{
			$start_date = date_create( date( $format ) );
		}
		
		if( doubleval( $birthday ) ){
			$sbirthday = date_create( date( $format, doubleval( $birthday ) ) );
			
			$diff = date_diff( $sbirthday, $start_date );
			$year = $diff->format("%y");
			$age_data["year"] = $year;
			$age_data["month"] = $diff->format("%m");
			$age_data["day"] = $diff->format("%d");
			$age_data["hour"] = $diff->format("%h");
			
			if( $year ){
				$age = $diff->format("%y yr(s) %m mth(s)");
				//30-dec-22
				if( ! $diff->format("%m") ){
					$age = $diff->format("%y yr(s)");
				}
				if( $return_type == 2 ){
					return $diff->format("%y yr(s)");
				}
			}else{
				$month = $diff->format("%m");
				$age_data["month"] = $month;
				if( $month ){
					$age = $diff->format("%m mth(s) %d day(s)");
					if( $return_type == 2 ){
						return $diff->format("%m mth(s)");
					}
				}else{
					$day = $diff->format("%d");	// %h hour(s)
					$age_data["day"] = $day;
					
					if( $day ){
						$age = $diff->format("%d day(s)");	// %h hour(s)
					}else{
						$hour = $diff->format("%h");
						$age_data["hour"] = $hour;
						
						if( $hour ){
							$age = $diff->format("%h hr(s) %i min(s)");	// %h hour(s)
						}else{
							$minute = $diff->format("%i");
							$age_data["minute"] = $minute;
							if( $minute ){
								$age = $diff->format("%i min(s)");	// %h hour(s)
							}else{
								$age = $diff->format("%s sec(s)");	// %h hour(s)
							}
						}
					}
				}
			}
			
		}
		
		if( $return_type ){
			$age_data["age"] = $age;
			return $age_data;
		}
		
		return $age;
	}
	
	function get_automatic_update_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "PERFORM AUTOMATIC DATA UPDATES", "table" => "all" ) );
		return 1;
	}
	
	function get_default_location(){
		$loc = "main.html";
		if( defined( "HYELLA_DEFAULT_LOCATION" ) && HYELLA_DEFAULT_LOCATION ){
			$loc = HYELLA_DEFAULT_LOCATION;
		}
		return $loc;
	}
	
	function check_for_last_automatic_update( $state = 0 ){
		$ckey = 'automatic-update';
		
		$settings = array(
			'cache_key' => $ckey,
			'permanent' => true,
		);
		$date = doubleval( get_cache_for_special_values( $settings ) );
		
		if( mktime(0,0,0, date("n"), date("j"), date("Y") ) > $date ){
			if( ! $state ){
				$settings = array(
					'cache_key' => $ckey,
					'permanent' => true,
					'cache_values' => date("U"),
				);
				set_cache_for_special_values( $settings );
			}
			return 1;
		}
	}
	
	function get_hyella_development_mode(){
		//@pend
		//return get_general_settings_value( array( "key" => "ENABLE DEVELOPMENT MODE", "table" => "all" ) );
		if( defined("HYELLA_MODE") && HYELLA_MODE == "development" ){
			return 1;
		}
	}
	
	function get_seperate_cache_directories_settings(){
		if( defined("HYELLA_SEPERATE_CACHE") && HYELLA_SEPERATE_CACHE ){
			return 1;
		}
	}
	
	function get_refresh_cache_in_background_settings(){
		//@pend
		//return get_general_settings_value( array( "key" => "REFRESH CACHE IN BACKGROUND", "table" => "all" ) );
		return 0;
	}
	
	function get_default_select2_limit(){
		//@pend
		//return get_general_settings_value( array( "key" => "DEFAULT SELECT2 LIMIT", "table" => "all" ) );
		return 100;
	}
	
	function get_backend_url(){
		return '../engine/';
	}
		
	function get_axis_type(){
		return array( 
			"category" => "Category", 
			"linear" => "Linear", 
			"datetime" => "Date / Time" 
		);
	}
	
	function get_axis_type_to_form_field( $axis = '' ){
		$return = array( 
			"category" => " 'text', 'select', 'textarea', 'calculated', 'calculations' ", 
			"linear" => " 'number', 'decimal' ", 
			"datetime" => " 'date-5', 'date-5time' ", 
		);
		
		if( isset( $return[ $axis ] ) && $return[ $axis ] ){
			return $return[ $axis ];
		}
	}
	
	function get_accessible_stores( $all_stores = array(), $user_privilege = '', $settings = array() ){
		/* if( ! ( is_array( $all_stores ) && ! empty( $all_stores ) ) )$all_stores = get_stores();
		
		$disabled_sub_store = isset( $settings["sub_store"] )?$settings["sub_store"]:0;
		$disabled_revenue = isset( $settings["sub_revenue"] )?$settings["sub_revenue"]:0;
		$disabled_cost = isset( $settings["sub_cost"] )?$settings["sub_cost"]:0;
		 */
		if( isset( $settings["for_edms"] ) && $settings["for_edms"] ){
			if( $user_privilege == "1300130013" || get_disable_access_control_settings() ){
				return '';
			}
			
		}else{
		
			if( isset( $GLOBALS[ 'branch' ] ) && $GLOBALS[ 'branch' ] ){
				return array( $GLOBALS[ 'branch' ] => $GLOBALS[ 'branch' ] );
			}
			/* 
			//remove inactive stores
			$stores_data = get_stores_data();
			foreach( $stores_data as $sval ){
				$a = json_decode( $sval["store_options"], true );
				if( isset( $a["store_status"] ) && $a["store_status"] == "in_active" ){
					unset( $all_stores[ $sval["id"] ] );
				}
				
				if( $disabled_sub_store ){
					if( isset( $a["disable_sub_store"] ) && $a["disable_sub_store"] == "yes" ){
						unset( $all_stores[ $sval["id"] ] );
					}
				}
				
				if( $disabled_revenue ){
					if( isset( $a["disable_revenue_collection"] ) && $a["disable_revenue_collection"] == "yes" ){
						unset( $all_stores[ $sval["id"] ] );
					}
				}
				
				if( $disabled_cost ){
					if( isset( $a["disable_cost_center"] ) && $a["disable_cost_center"] == "yes" ){
						unset( $all_stores[ $sval["id"] ] );
					}
				}
			}
			if( isset( $settings["return_all_active_stores"] ) && $settings["return_all_active_stores"] ){
				return $all_stores;
			}
			 */
			$no_super = isset( $settings["no_super"] )?$settings["no_super"]:0;
			$ms = get_main_store();
			$all_stores = array( $ms => get_name_of_referenced_record( array( "id" => $ms, "table" => "stores" ) ) ); 
			
			if( ! $no_super ){
				if( $user_privilege == "1300130013" || get_disable_access_control_settings() ){
					return $all_stores;
				}
			}
		}
		
		$access = get_accessed_functions( $user_privilege );
		$accessible_stores = array();
		
		if( isset( $settings["for_edms"] ) && $settings["for_edms"] ){
			if( ! is_array( $access ) && $access ){
				return '';
			}
			
			if( isset( $access["status"]["stores"]["access_all_stores"] ) ){
				return '';
			}else{
				if( isset( $access["status"]["stores"] ) && ! empty( $access["status"]["stores"] ) ){
					$accessible_stores = $access["status"]["stores"];
					unset( $accessible_stores["access_my_store"] );
				}
				
				if( isset( $GLOBALS["access"]["my_store"] ) && $GLOBALS["access"]["my_store"] ){
					$accessible_stores[ $GLOBALS["access"]["my_store"] ] = 1;
				}
			}
			
			return $accessible_stores;
		}else{
			//print_r( $access ); exit;
			if( ! $no_super ){
				if( ! is_array( $access ) && $access ){
					return $all_stores;
				}
			}
			
			if( isset( $access["status"]["stores"]["access_all_stores"] ) ){
				return $all_stores;
			}else{
				if( isset( $access["status"]["stores"]["access_my_store"] ) && $access["status"]["stores"]["access_my_store"] ){
					
					if( isset( $user_privilege ) && $user_privilege ){
						$key = md5( 'ucert' . $_SESSION['key'] );
						
						if( isset( $_SESSION[ $key ]["id"] ) && $_SESSION[ $key ]["id"] ){
							$ud = get_record_details( array( "id" => $_SESSION[ $key ]["id"], "table" => "users" ) );
							if( isset( $ud[ "country" ] ) && $ud[ "country" ] ){
								$store_id = $ud[ "country" ];
								$accessible_stores[ $store_id ] = get_name_of_referenced_record( array( "id" => $store_id, "table" => "stores" ) );
							}
						}
					}
					
					
					unset( $access["status"]["stores"]["access_my_store"] );
				}
				
				if( isset( $access["status"]["stores"] ) && ! empty( $access["status"]["stores"] ) ){
					foreach( $access["status"]["stores"] as $store_id => $store_v ){
						$accessible_stores[ $store_id ] = get_name_of_referenced_record( array( "id" => $store_id, "table" => "stores" ) );
					}
				}
			}
			
			if( isset( $GLOBALS["user_cert"]["store"] ) && $GLOBALS["user_cert"]["store"] ){
				$accessible_stores[ $GLOBALS["user_cert"]["store"] ] = get_name_of_referenced_record( array( "id" => $GLOBALS["user_cert"]["store"], "table" => "stores" ) );
			}
			
			if( ! empty( $accessible_stores ) ){
				return $accessible_stores;
				unset( $access );
				$access = 1;
			}
		}
	}
	
	function get_enter_eggs_in_crate_settings(){
		//pfarm
		return get_general_settings_value( array( "key" => "ENTER EGG CRATES IN FARM RECORDS", "table" => "farm_daily_record" ) );
		return 0;
	}
		
	function get_purchase_order_settings(){
		return 1;
		if( defined("HYELLA_TREATE_PURCHASE_ORDER_AS_SEPERATE_DOC") && HYELLA_TREATE_PURCHASE_ORDER_AS_SEPERATE_DOC ){
			return 1;
		}
	}
	
	function get_upload_data_to_server_only(){
		//return get_general_settings_value( array( "key" => "UPLOAD DATA TO SERVER ONLY", "table" => "all" ) );
		return 1; //bodyrox
		return 0; //fountain med
	}
	
	function get_defualt_bank_account_for_financial_transactions(){
		return strtolower( get_general_settings_value( array( "key" => "DEFAULT BANK ACCOUNT FOR TRANSACTIONS", "table" => "transactions" ) ) );
		return 'bank10'; //pfarm
	}
	
	function get_allow_multiple_financial_transactions_posting(){
		return get_general_settings_value( array( "key" => "ALLOW MULTIPLE TRANSACTIONS POSTING", "table" => "transactions" ) );
		return 0; //pfarm
	}
	
	function get_currency_version2(){
		if( defined("HYELLA_CURRENCY_VERSION_2") && HYELLA_CURRENCY_VERSION_2 ){
			return 1;
		}
		return 0;
	}
	
	function get_i_menu_store(){
		if( defined('HYELLA_I_MENU_STORE') && HYELLA_I_MENU_STORE ){
			return HYELLA_I_MENU_STORE;
		}
		return 0;
	}
	
	function get_categorized_customers_settings(){
		return get_general_settings_value( array( "key" => "CATEGORIZE CUSTOMERS", "table" => "sales" ) );
	}
	
	function skip_stores_list_in_cart(){
		
		$skip_stores = 0;
		if( isset( $_GET["budget"] ) && $_GET["budget"] == "frontend" ){
			$skip_stores = 1;
		}
		return $skip_stores;
	}
	
	function get_capture_user_passphrase_settings(){
		//return 1;
		return get_general_settings_value( array( "key" => "USERS PASS PHRASE", "table" => "users" ) );
	}
	
	function get_customer_label_based_on_package(){
		$label = 'Customer';
		
		switch( get_package_option() ){
		case "hospital":
			$label = 'Patient';
		break;
		case "property":
			$label = 'Tenant';
		break;
		case "cooperative":
			$label = 'Member';
		break;
		case "group_demo":
			$label = 'Employee';
		break;
		}
		return $label;
	}
	
	function __get_select_values( $option = array() ){
		$lbls = isset( $option['labels'] )?$option['labels']:array();
		
		$fd = array();
		if( isset( $lbls['form_field_options'] ) ){
			if( isset( $lbls[ 'data' ]["form_field_options_source"] ) && $lbls[ 'data' ]["form_field_options_source"] == 2 ){
				$fd = get_list_box_options( $lbls[ 'form_field_options' ], array( "return_type" => 2 ) );
				
			}else{
				$t1 = $lbls['form_field_options'];
				if( function_exists( $t1 ) ){
					$fd = $t1();
				}
			}
		}else{
			
			if( isset( $lbls[ 'options' ] ) && $lbls[ 'options' ] ){
				$ex = explode(";", $lbls[ 'options' ] );

				if( is_array( $ex ) && ! empty( $ex ) ){
					foreach( $ex as $ev ){
						if( $ev ){
							$es2 = explode(":", $ev );
							if( isset( $es2[0] ) && $es2[0] ){
								$fd[ $es2[0] ] = ( isset( $es2[1] ) && $es2[1] )?$es2[1]:$es2[0];
							}
						}
					}
				}
			}
		}
		
		return $fd;
	}
	
	function __get_value( $val, $key, $option = array() ){
		$value = $val;
		
		if( isset( $option["globals"]["fields"] ) && isset( $option["globals"]["labels"] ) ){
			$fields = $option["globals"]["fields"];
			$labels = $option["globals"]["labels"];
		}else{
			$fields = $GLOBALS["fields"];
			$labels = $GLOBALS["labels"];
		}
		
		//$pr = get_project_data();
		$pagepointer = isset( $option["pagepointer"] )?$option["pagepointer"]:'';
		$invert = isset( $option["invert"] )?$option["invert"]:0;
		$return_form_field = isset( $option["form_fields"] )?$option["form_fields"]:array();
		
		
		$raw = isset( $option["raw"] )?$option["raw"]:0;
		$tab_index = ( isset( $option["tab_index"] ) && $option["tab_index"] )?$option["tab_index"]:1;
		
		if( $invert ){
			$raw = 1;
		}
		
		if( ! empty( $return_form_field ) ){
			$a1 = array();
			$t = $tab_index;
			
			if( class_exists( 'cForms' ) ){
				$form = new cForms();
				$form->hide_form_labels = 1;
				$form->class_settings["calling_page"] = $pagepointer;
				if( isset( $option["always_allow_clear"] ) && $option["always_allow_clear"] ){
					$form->always_allow_clear = 1;
				}
				if( isset( $option["show_form_labels"] ) && $option["show_form_labels"] ){
					$form->hide_form_labels = 0;
				}
				//11-may-23
				if( defined("HYELLA_MOBILE") && HYELLA_MOBILE ){
					$form->mobile_framework = HYELLA_MOBILE;
				}
				
				//print_r( $return_form_field ); exit;
				
				foreach( $return_form_field as $fk => $fv ){
					
					if( isset( $fields[ $fk ] ) && isset( $labels[ $fields[ $fk ] ] ) ){
							// print_r( $return_form_field );
							// print_r( $labels );
							// print_r( $fields );exit;
						
						$fid = $fields[ $fk ];
						//echo $fid; exit;
						
						$aa = array();
						$has_value = false;
						if( isset( $fv["value"] ) && $fv["value"] ){
							$aa[ $fid ] = $fv["value"];
							$has_value = true;
						}
						
						if( isset( $option["form_values"][ $fk ] ) && $option["form_values"][ $fk ] ){
							$aa[ $fid ] = $option["form_values"][ $fk ];
							$has_value = true;
						}
						
						
						switch( $labels[ $fid ]["form_field"] ){
						case 'date-5time':
						case 'date-5':
						case 'datetime':
						case 'date':
						case 'date_time':
							if( isset( $option["text-date"] ) && isset( $aa[ $fid ] ) && $aa[ $fid ] ){
								$aa[ $fid ] = convert_date_to_timestamp( $aa[ $fid ] );
							}
						break;
						}
						
						if( isset( $fv["disabled"] ) && $fv["disabled"] ){
							$form->disable_form_element[ $fid ] = ' disabled="disabled" ';
						}
						
						if( isset( $fv["readonly"] ) && $fv["readonly"] ){
							$form->disable_form_element[ $fid ] = ' readonly="readonly" ';
						}
						
						if( isset( $fv["hidden"] ) && $fv["hidden"] ){
							$form->hide_record_css[ $fid ] = 1;
						}
						
						if( isset( $fv["source"] ) && $fv["source"] ){
							$labels[ $fid ]["source"] = $fv["source"];
						}
						
						if( isset( $fv["not_editable"] ) && $fv["not_editable"] ){
							$form->form_display_not_editable_value[ $fid ] = 1;
						}
						
						$ips = array(
							"field_id" => $fid,
							"form_label" => $labels,
							
							"populate_form_with_values" => $has_value,
							"mobile_class5" => '',
							"mobile_class4" => '',
							"mobile_class3" => '',
							"mobile_class2" => '',
							"mobile_class1" => '',
							
							"aa" => $aa,
							"t" => ++$t,
						);
						
						switch( $labels[ $fields[ $fk ] ]["form_field"] ){
						case 'text':
							// print_r( $labels );exit;
						break;
						}

						$a1[ $fk ]["label"] = isset( $labels[ $fields[ $fk ] ]["field_label"] ) ? $labels[ $fields[ $fk ] ]["field_label"] : '';
						
						if( isset( $labels[ $fields[ $fk ] ]["required_field"] ) && $labels[ $fields[ $fk ] ]["required_field"] == 'yes' ){
							$a1[ $fk ]["label"] .= ' <sup>*</sup>';
						}

						if( isset( $option[ 'mikee' ] ) ){
							// $ips[ 'xxxx' ] = 1;
							// print_r( $ips );exit;
						}
						$a1[ $fk ]["field"] = $form->nw_generate_form_field( $ips );
						if( isset( $form->tabindex ) && $form->tabindex ){
							$t = $form->tabindex;
						}
					}
				}
			}
			
			return $a1;
		}

		if( isset( $fields[ $key ] ) && isset( $labels[ $fields[ $key ] ] ) ){
			
			if( isset( $option["get_label"] ) && $option["get_label"] ){
				return $labels[ $fields[ $key ] ]["field_label"];
			}
			
			$l = $labels[ $fields[ $key ] ]["field_label"];
			$fd = array();
			
			switch( $labels[ $fields[ $key ] ]['form_field'] ){
			case 'radio':
			case "select":
			case 'checkbox':
			case "multi-select":
				$fd = __get_select_values( array( "labels" => $labels[ $fields[ $key ] ] ) );
			break;
			}
			
			switch( $labels[ $fields[ $key ] ]['form_field'] ){
			case "currency":
			case "decimal":
				if( $val ){
					if( $raw ){
						$value = doubleval( $val );
					}else{
						$value = format_and_convert_numbers( $val, 4 );
					}
				}
				
			break;
			case "decimal_long":
				if( $val ){
					$value = doubleval( $val );
					if( ! $raw ){
						$value = number_format( $value, 8 );
					}
				}
			break;
			case "color":
				if( ! $raw ){
					$value = '<span style="background-color:'. $value .';">'. $value .'</span>';
				}
			break;
			case "date-5":
			case "date":
				$filter = "d-M-Y";
				if( isset( $option["filter"] ) && $option["filter"] ){
					$filter = $option["filter"];
				}
				
				if( $invert ){
					$value = convert_date_to_timestamp( $val );
				}else{
					
					if( isset( $option["text-date"] ) && $val ){
						$val = convert_date_to_timestamp( $val );
					}
					
					if( $val && doubleval( $val ) )$value = date( $filter, doubleval( $val ) );
					else $value = '-';
				}
			break;
			case "date-5time":
				$filter = "d-M-Y H:i";
				if( isset( $option["filter"] ) && $option["filter"] ){
					$filter = $option["filter"];
				}
				
				if( $invert ){
					$value = convert_date_to_timestamp( $val );
				}else{
					if( $val && doubleval( $val ) )$value = date( $filter, doubleval( $val ) );
					else $value = '-';
				}
			break;
			case 'calculated':
				//$row_data = $sval;
				
				if( $invert ){
					$value = $val;
				}else{
					if( isset( $option["row_data"] ) && $option["row_data"] ){
						$row_data = $option["row_data"];
					}
					
					$row_data[ $fields[ $key ] ] = $val;
					$option_array2 = $row_data;
					//example in survey assignment: assigned_to
					$fdx = isset( $labels[ $fields[ $key ] ]['calculations'][ 'reference' ][ 'key2' ] ) ? $labels[ $fields[ $key ] ]['calculations'][ 'reference' ][ 'key2' ] : '';
					
					if( isset( $fields[ $fdx ] ) && isset( $option[ 'values' ][ $fdx ] ) && $option[ 'values' ][ $fdx ] ){
						$option_array2[ $fields[ $fdx ] ] = $option[ 'values' ][ $fdx ];
					}
					
					$return_array = '';
					if( isset( $option["source"] ) && $option["source"] ){
						$return_array = $option["source"];
					}
					
					$_data = evaluate_calculated_value(
						array(
							'source' => $return_array,
							'add_class' => '',
							'row_data' => $option_array2,
							'form_field_data' => $labels[ $fields[ $key ] ],
						) 
					);
					if( isset( $option[ 'mikes' ] ) && $option[ 'mikes' ] ){
						print_r( '<pre>' );
						print_r( evaluate_calculated_value(
						array(
								'source' => $return_array,
								'add_class' => '',
								'row_data' => $option_array2,
								'form_field_data' => $labels[ $fields[ $key ] ],
								'milks' => 1,
							) 
						) );
						print_r( '</pre>' );
					}
					if( isset( $_data['value'] ) && $_data['value'] )
						$value = $_data['value'];
					else
						$value = $val;
				}

			break;
			case 'signature':
			if( isset( $option[ 'mkss' ] ) && $option[ 'mkss' ] ){
				// print_r( $pagepointer );
				// print_r( "\n\n\n\n".get_uploaded_files( '../', $val, $l ) );exit;
			}
				$value = get_uploaded_files( $pagepointer, $val, $l );
			break;
			case 'file':
				if( $invert ){
					$value = $val;
				}else{
					$value = get_uploaded_files( $pagepointer, $val, $l );
				}
			break;
			case 'radio':
			case "select":
				$value = '';
				if( $val ){
					$val = strtolower( trim( $val ) );
				}
				
				if( $invert ){
					if( ! empty( $fd  ) ){
						array_walk_recursive( $fd, '' );
						$fd = array_flip( $fd );
					}
					
					$value = isset( $fd[ $val ] )?$fd[ $val ]:$val;
				}else{
					$value = isset( $fd[ $val ] )?$fd[ $val ]:$val;
				}
			break;
			case 'checkbox':
			case "multi-select":
				$value = '';
				if( $val ){
					$items = explode( ":::", $val );
				}else{
					$items = array();
				}
				$values = array();
				
				$funcs = $fd;
				
				if( $invert ){
					array_walk_recursive( $funcs, '' );
					$funcs = array_flip( $funcs );
				}

				if( ! empty( $items ) ){
					foreach( $items as $item ){
						if( ! trim( $item ) )continue;
						
						$item = strtolower( trim( $item ) );
						
						if( isset( $funcs[ $item ] ) ){
							$values[] = $funcs[ $item ];
						}
					}
				}
				$return_array = ", ";
				if( isset( $option["source"] ) && $option["source"] ){
					$return_array = $option["source"];
				}
				
				if( $invert ){
					if( $values ){
						$value = implode(":::", $values );
					}
				}else{
					if( $values ){
						$value = implode( $return_array , $values );
					}
				}
				
			break;
			case 'textarea-unlimited':
			case "textarea":
			
				if( $raw ){
					$value = $val;
				}else{
					if( $val ){
						$value = nl2br( $val );
					}
				}
				
			break;
			case "text":
				if( isset( $option[ 'mikee' ] ) && $option[ 'mikee' ] ){
					// print_r( $val );exit;
				}
				if( $invert ){
					$value = $val;
				}else{
					if( $val ){
						$value = ucwords( $val );
					}
				}
			break;
			case "time":
				if( $invert ){
					$value = $val;
				}else{
					$value = format_time( $val, 3 );
				}
			break;
			case "field_group":
				if( isset( $labels[ $fields[ $key ] ]['database_objects'] ) && $labels[ $fields[ $key ] ]['database_objects'] ){
					$value = get_nw_database_object( array( "field_id" => $fields[ $key ], "object_ids" => $labels[ $fields[ $key ] ]['database_objects'], "values" => $val ) );
				}
			break;
			default:
				$value = $val;
			break;
			}
			
			if( isset( $labels[ $fields[ $key ] ]['format_function'] ) && $labels[ $fields[ $key ] ]['format_function'] && function_exists( $labels[ $fields[ $key ] ]['format_function'] ) ){
				$ftt = $labels[ $fields[ $key ] ]['format_function'];
				$value = $ftt( $val );
			}
			
			
			if( isset( $option["summary"] ) && $option["summary"] && intval( $option["summary"] ) && strlen( $value ? $value : '' ) > intval( $option["summary"] ) ){
				$value = substr( $value, 0, intval( $option["summary"] ) ) . '...';
			}
			
			if( isset( $option["get_label_and_value"] ) && $option["get_label_and_value"] ){
				$return = array( "label" => $labels[ $fields[ $key ] ]["field_label"], "value" => $value );
				if( isset( $option["add_options"] ) && $option["add_options"] ){
					if( ! empty( $fd ) )$return["options"] = $fd;
				}
				return $return;
			}
			
			return $value;
			
		}
		
		return $value;
	}

	function disable_point_of_sale_settings(){
		//sales
		return get_general_settings_value( array( "key" => "DISABLE POINT OF SALE", "table" => "sales" ) );
		if( defined("HYELLA_MAIN_MENU_SELL_DISABLED") && HYELLA_MAIN_MENU_SELL_DISABLED ){
			return 1;
		}
	}
	
	function get_sales_invoice_caption_settings(){
		//sales
		return get_general_settings_value( array( "key" => "SALES INVOICE CAPTION", "table" => "sales" ) );
		if( defined("HYELLA_MAIN_MENU_INVOICE_CAPTION") && HYELLA_MAIN_MENU_INVOICE_CAPTION ){
			return HYELLA_MAIN_MENU_INVOICE_CAPTION;
		}
		return "All Sales Invoice";
	}
	
	function get_customers_caption_settings(){
		//customers
		return get_general_settings_value( array( "key" => "CUSTOMERS CAPTION", "table" => "customers" ) );
		if( defined("HYELLA_MAIN_MENU_CUSTOMER_CAPTION") && HYELLA_MAIN_MENU_CUSTOMER_CAPTION ){
			return HYELLA_MAIN_MENU_CUSTOMER_CAPTION;
		}
		return "Add New Customer";
	}
	
	function get_vendors_caption_settings(){
		//vendors
		return get_general_settings_value( array( "key" => "VENDORS CAPTION", "table" => "vendors" ) );
		if( defined("HYELLA_MAIN_MENU_VENDOR_CAPTION") && HYELLA_MAIN_MENU_VENDOR_CAPTION ){
			return HYELLA_MAIN_MENU_VENDOR_CAPTION;
		}
		return "Vendor";
	}
	
	function get_company_prefix_settings(){
		//all
		return get_general_settings_value( array( "key" => "COMPANY PREFIX", "table" => "all" ) );
		if( defined("HYELLA_COMPANY_PREFIX") && HYELLA_COMPANY_PREFIX ){
			return HYELLA_COMPANY_PREFIX;
		}
	}
	
	function get_year_suffix_settings(){
		//all
		return get_general_settings_value( array( "key" => "YEAR SUFFIX", "table" => "all" ) );
		if( defined("HYELLA_INCLUDE_YEAR_SUFFIX") && HYELLA_INCLUDE_YEAR_SUFFIX ){
			return date("y");
		}
	}
	
	function get_do_not_show_reference_in_receipt_heading_settings(){
		//all
		return get_general_settings_value( array( "key" => "DO NOT SHOW REFERENCE IN RECEIPT HEADING", "table" => "all" ) );
		return 1;
	}
	
	function get_allow_modify_date_of_expense_settings(){
		//transactions
		return get_general_settings_value( array( "key" => "ALLOW MODIFY DATE OF EXPENSE", "table" => "transactions" ) );
		return 1;
		
		//set to 1 to allow user define date of expense in project expense
		//set to 0 to use current system date
	}
	
	function get_use_date_of_expense_as_date_of_payment_settings(){
		//transactions
		
		return get_general_settings_value( array( "key" => "USE DATA OF EXPENSE AS DATA OF PAYMENT", "table" => "transactions" ) );
		return 1;
		//set to 1 to use the date expense as date of payment in project expense
		//set to 0 to use current system date
	}
	
	function get_edit_financial_transactions_data_settings(){
		//transactions
		
		return get_general_settings_value( array( "key" => "EDIT FINANCIAL TRANSACTIONS DATA", "table" => "transactions" ) );
		return 1;
		//set to 1 to enable user edit financial transactions
		//set to 0 to none
	}

	function get_ability_to_back_date_payment_capture_settings(){
		//skip this one
		if( get_back_date_all_settings() ){
			return 1;
		}
		//FORNOW I HAVE CHOOSED TO USE THE FINANCIAL TRANSACTIONS, BUT CORRECT THIS LATER
		$return = get_general_settings_value( array( "key" => "ALLOW BACK DATED FINANCIAL TRANSACTIONS", "table" => "transactions" ) );
		if( $return ){
			//check access role
			if( get_disable_access_control_settings() ){
				return $return;
			}
			$access = get_accessed_functions( 0 );
			if( ! is_array( $access ) && $access ){
				return $return;
			}
			if( ! isset( $access[ "set_date_financial_transactions" ] ) ){
				return 0;
			}
		}
		return $return;
	}
	
	function get_automatically_record_depreciation_expense(){
		//assets, not in use
		return get_general_settings_value( array( "key" => "AUTOMATICALLY RECORD DEPRECIATION", "table" => "assets" ) );
		
		//AUTOMATICALLY RECORD DEPRECIATION EXPENSE
		return 0;
	}
	
	function get_auto_create_asset_account_from_asset_category(){
		return get_general_settings_value( array( "key" => "AUTO CREATE ASSET ACCOUNT FROM ASSET CATEGORY", "table" => "assets_category" ) );
		//return 1;
	}
	
	function get_auto_create_depreciation_expense_account_from_asset_category(){
		//assets_category
		return get_general_settings_value( array( "key" => "AUTO CREATE DEPRECIATION EXPENSE ACCOUNT FROM ASSET CATEGORY", "table" => "assets_category" ) );
		
		//AUTOMATICALLY RECORD DEPRECIATION EXPENSE
		return 1;
	}
	
	function get_compulsory_vendor_reference_settings(){
		//cart
		
		return get_general_settings_value( array( "key" => "COMPULSORY VENDOR REFERENCE", "table" => "cart" ) );
		//COMPULSORY VENDOR REFERENCE IN P/O
		return 1;
	}
	
	function get_show_expiry_dates_settings(){
		//all
		return doubleval( get_general_settings_value( array( "key" => "SHOW EXPIRY DATES", "table" => "all" ) ) );
		return 1;
	}
	
	function get_match_expiry_dates_settings(){
		//all
		return doubleval( get_general_settings_value( array( "key" => "MATCH EXPIRY DATES", "table" => "all" ) ) );
		return 1;
	}
	
	function get_use_items_ordered_last_as_purchase_template_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "USE ITEMS ORDERED LAST AS PURCHASE TEMPLATE", "table" => "expenditure" ) );
		return 0;
	}
	
	function get_enable_loyalty_points_settings(){
		//loyalty_points
		if( defined( 'HYELLA_V3_LOYALTY_POINTS' ) && HYELLA_V3_LOYALTY_POINTS ){
			return get_general_settings_value( array( "key" => "ENABLE LOYALTY POINTS", "table" => "loyalty_points" ) );
		}
		return 0;
	}
	
	function get_enterprise_financial_reports_settings(){
		//transactions
		return get_general_settings_value( array( "key" => "ENTERPRISE FINANCIAL REPORTS", "table" => "transactions" ) );
		return 1;
	}
	
	function get_financial_report_types(){
		//skip this one
		$return = array(
			"income_statement_enterprise" => "Statement of Comprehensive Income [Enterprise]",
			"income_statement_stores" => "Statement of Comprehensive Income [Stores]",
			"income_expenditure_sheet" => "Income and Expenditure Sheet",
			
			//"trial_balance" => "Trial Balance",
			
			"balance_sheet_enterprise" => "Statement of Financial Position [Enterprise]",
		);
		
		return $return;
	}
	
	function get_disable_complimentary_staff(){
		//cart
		return get_general_settings_value( array( "key" => "DISABLE COMPLIMENTARY STAFF", "table" => "cart" ) );
		return 1;
	}
	
	function get_enable_complimentary_settings(){
		return get_general_settings_value( array( "key" => "ENABLE COMPLIMENTARY", "table" => "cart" ) );
	}
	
	function get_compulsory_customer_during_sale(){
		//cart
		return get_general_settings_value( array( "key" => "COMPULSORY CUSTOMER DURING SALES", "table" => "cart" ) );
		return 1;
	}
	
	function get_default_customer_during_sale(){
		//cart
		return get_general_settings_value( array( "key" => "DEFAULT CUSTOMER DURING SALES", "table" => "cart" ) );
	}
	
	function get_allow_remove_surcharge_during_sales(){
		return get_general_settings_value( array( "key" => "REMOVE ALL SURCHARGE DURING SALES", "table" => "cart" ) );
		return 1;
	}
	
	function get_allow_discount_comment_during_sales(){
		//sales
		return doubleval( get_general_settings_value( array( "key" => "ALLOW DISCOUNT COMMENT DURING SALES", "table" => "sales" ) ) );
		return 0;
	}
	
	function get_create_customer_account_for_staff(){
		//users
		return get_general_settings_value( array( "key" => "CREATE CUSTOMER ACCOUNT FOR STAFF", "table" => "users" ) );
		return 1;
	}
	
	function get_link_vendors_to_asset(){
		//assets
		return get_general_settings_value( array( "key" => "LINK VENDORS TO ASSET", "table" => "assets" ) );
		return 1;
	}
	
	function get_number_of_hours_for_delete(){
		//sales
		return get_general_settings_value( array( "key" => "NUMBER OF HOURS FOR DELETE", "table" => "sales" ) );
		return 24 * 3600 * 15;	//set this value to zero (0) to disable deleting of sales records
	}
	
	function get_use_city_ledger_settings(){
		//hotel_checkin
		return get_general_settings_value( array( "key" => "USE CITY LEDGER", "table" => "hotel_checkin" ) );
		if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE ){
			switch( HYELLA_PACKAGE ){
			case "hotel":
				return 1;
			break;
			}
		}
		
		return 0;
	}
	
	function get_use_optional_group_checkin_settings(){
		//hotel_checkin
		return get_general_settings_value( array( "key" => "USE OPTIONAL GROUP CHECKIN", "table" => "hotel_checkin" ) );
		return 1;
	}
	
	function get_use_manual_billing_for_hotel_guest_settings(){
		//hotel_checkin
		return get_general_settings_value( array( "key" => "USE MANUAL BILLING FOR HOTEL GUEST", "table" => "hotel_checkin" ) );
		return 1;
	}
	
	function get_add_all_bills_to_hotel_guest_bill_settings(){
		//hotel_checkin
		return get_general_settings_value( array( "key" => "ADD ALL BILLS TO HOTEL GUEST BILL", "table" => "hotel_checkin" ) );
		return 0;	//this will only return bills that were charged to room
		//return 1;	//this will return all bills based on date of checkin / checkout
	}
	
	function get_show_only_unpaid_bills_in_frontend_settings(){
		//hotel_checkin
		return get_general_settings_value( array( "key" => "SHOW ONLY UNPAID BILLS IN FRONTEND", "table" => "hotel_checkin" ) );
		return 1;	//nisa
	}
	
	function get_use_city_ledger_transfer_prompt_before_checkout_settings(){
		//hotel_checkin
		return get_general_settings_value( array( "key" => "USE CITY LEDGER TRANSFER PROMPT BEFORE CHECKOUT", "table" => "hotel_checkin" ) );
		return 1;	//nisa - prompt users to manually transfer funds to city ledger
	}
	
	function get_use_imported_goods_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "USE IMPORTED GOODS", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_stock_quantity_picked_label_settings(){
		//return 'Qty Issued';	//Qty Picked
		$a = get_general_settings_value( array( "key" => "STOCK ISSUE LABEL", "table" => "general_settings" ) );
		if( ! $a )$a = 'Qty Issued';
		return $a;
	}
	
	function get_stock_utilization_label_settings(){
		$a = get_general_settings_value( array( "key" => "STOCK UTILIZATION LABEL", "table" => "general_settings" ) );
		if( ! $a )$a = 'Stock Issue';
		return $a;
		return 'Stock Issue'; //Stock Utilization
	}
	
	function get_picking_slip_label_settings(){
		$a = get_general_settings_value( array( "key" => "WAY BILL LABEL", "table" => "general_settings" ) );
		if( ! $a )$a = 'Way Bill';
		return $a;
		
		return 'Way Bill';	//return 'Picking Slip';
	}
	
	function get_hide_default_item_list_settings(){
		//inventory
		return get_general_settings_value( array( "key" => "HIDE DEFAULTS ITEM LIST", "table" => "inventory" ) );
		return 0;	//light inventory	//nisa pharmacy
		return 1;	//heavy inventory users must search to retrieve items from the database during sales & po
	}
	
	function get_show_only_quantity_in_goods_received_note_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "SHOW ONLY QUANTITY IN GOODS RECEIVED NOTE", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_show_only_quantity_in_direct_purchase_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "SHOW ONLY QUANTITY IN DIRECT PURCHASE", "table" => "expenditure" ) );
		
		//direct purchase goods will show only quantity in default print-out
		return 0;	//direct purchase goods will show quantity & price in default print-out
	}
	
	function get_transfer_items_using_grn_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "TRANSFER ITEMS USING GRN", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_enable_capture_payment_from_sales_frontend_settings(){
		//sales
		return get_general_settings_value( array( "key" => "ENABLE CAPTURE PAYMENT FROM SALES FRONTEND", "table" => "sales" ) );
		switch( get_package_option() ){
		case "hotel":
			return 1;
		break;
		}
		
		return 0;
	}
	
	function get_enable_advance_deposit_register_settings(){
		//all
		return get_general_settings_value( array( "key" => "ENABLE ADVANCE REGISTER", "table" => "all" ) );
		return 0;
	}
	
	function get_weight_average_cost_calculation_type_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "WEIGHT AVERAGE COST CALCULATION TYPE", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_direct_purchase_and_restock_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "DIRECT PURCHASE AND RESTOCK", "table" => "expenditure" ) );
		return 0;
	}
	
	function get_transfer_consumables_settings(){
		//production
		return get_general_settings_value( array( "key" => "TRANSFER CONSUMABLES", "table" => "production" ) );
		return 0;
	}
	
	function get_use_department_as_salary_category_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "USE DEPARTMENT AS SALARY CATEGORY", "table" => "pay_row" ) );
		return 0;
	}
	
	function get_use_group_billing_for_sales_settings(){
		//all
		return get_general_settings_value( array( "key" => "USE GROUP BILLING FOR SALES", "table" => "all" ) );
		if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE ){
			switch( HYELLA_PACKAGE ){
			case "hotel":
				return 1;
			break;
			}
		}
		return 0;
	}
	
	function get_payment_schedule_for_bank_group_by_bank_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "PAYMENT SCHEDULE FOR BANK GROUP BY BANK", "table" => "pay_row" ) );
		return 1;	//group by bank
	}
	
	function get_staff_salary_schedule_group_by_category_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "STAFF SALARY SCHEDULE GROUP BY CATEGORY", "table" => "pay_row" ) );
		return 1;	//group staff salary schedule by category settings
	}
	
	function get_staff_salary_schedule_group_all_deduction_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "STAFF SALARY SCHEDULE GROUP BY DEDUCTIONS", "table" => "pay_row" ) );
		return 0;	//group staff salary schedule by deduction settings
	}
	
	function get_flag_purchase_goods_during_consumables_transfer_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "FLAG PURCHASE GOODS DURING GROUP ALL DEDUCTION", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_skip_purchase_goods_during_consumables_transfer_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "SKIP PURCHASE GOODS DURING CONSUMABLES TRANSFER", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_back_date_all_settings(){
		//all
		return get_general_settings_value( array( "key" => "BACK DATE ALL", "table" => "all" ) );
		return 0;	//nisa
		return 1;	//pfarm
	}
	
	function get_spread_other_cost_during_purchase_import_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "SPREAD OTHER COST DURING PURCHASE IMPORT", "table" => "expenditure" ) );
		return 1;	//cost of other charges is spread across the goods received note based on the units of goods received
		return 0;	//total cost of other charges is billed once goods are received partially
	}
	
	function get_use_enterprise_report_settings(){
		//transactions
		return get_general_settings_value( array( "key" => "USE ENTERPRISE REPORT SETTINGS", "table" => "transactions" ) );
		return 1;
		return 0;	//total cost of other charges is billed once goods are received partially
	}
	
	function get_use_minimal_customer_field_settings(){
		//skip this one
		return get_general_settings_value( array( "key" => "USE MINIMAL CUSTOMER FIELDS", "table" => "customers" ) );
	}
	
	function get_use_category_in_cash_book_analysis_report_settings(){
		//transactions
		return get_general_settings_value( array( "key" => "USE CATEGORY IN CASH BOOK ANALYSIS REPORT", "table" => "transactions" ) );
		return 1;	//pfarm
	}
	
	function get_sorting_order_for_financial_report_settings(){
		return get_general_settings_value( array( "key" => "SORTING ORDER FOR FINANCIAL REPORT", "table" => "transactions" ) );
		//return 1;	//pfarm //asc
		return 0;	//default & best option //desc
	}
	
	function get_validate_purchase_invoice_settings(){
		//expenditure
		return doubleval( get_general_settings_value( array( "key" => "VALIDATE PURCHASE INVOICE", "table" => "expenditure" ) ) );
		return 1;
	}
	
	function get_validate_sales_invoice_settings(){
		//sales
		return get_general_settings_value( array( "key" => "VALIDATE SALES INVOICE", "table" => "sales" ) );
		return 1;
	}
	
	function get_use_project_expense_for_vendor_bill_settings(){
		//vendor_bill
		return get_general_settings_value( array( "key" => "USE PROJECT EXPENSE FOR VENDOR BILL", "table" => "vendor_bill" ) );
		
		switch( get_package_option() ){
		case "project":
			return 1;
		break;
		}
		
		return 0;
	}
	
	function get_work_order_template_settings(){
		//vendor_bill
		return get_general_settings_value( array( "key" => "WORK ORDER TEMPLATE", "table" => "vendor_bill" ) );
		
		switch( get_package_option() ){
		case "project":
			return '';
		break;
		}
		
		return 'view-work-order';
	}
	
	function get_enable_medical_reports_settings(){
		
		switch( get_package_option() ){
		case "pharmacy":
			return 0;
		break;
		}
		
		return 1;
	}
	
	function get_show_work_order_settings(){
		//vendor_bill
		return get_general_settings_value( array( "key" => "SHOW WORK ORDER", "table" => "vendor_bill" ) );
		return 1; //nisa pharmacy
	}
	
	function get_created_by_text_on_invoice_settings(){
		//sales
		return get_general_settings_value( array( "key" => "CREATED BY TEXT ON INVOICE", "table" => "sales" ) );
		return 'Created By';
	}
	
	function get_validate_work_order_settings(){
		//vendor_bill
		return 0;	// permanently disabled due to workflow approval
		return get_general_settings_value( array( "key" => "VALIDATE WORK ORDER", "table" => "vendor_bill" ) );
	}
	
	function get_use_approval_workflow_for_work_order_settings(){
		//vendor_bill
		return get_general_settings_value( array( "key" => "USE APPROVAL WORKFLOW FOR WORK ORDER", "table" => "vendor_bill" ) );
		return 0;
	}
	
	function get_show_batch_number_on_grn_settings(){
		//expenditure
		return get_general_settings_value( array( "key" => "SHOW BATCH NUMBER ON GRN", "table" => "expenditure" ) );
		return 1;
	}
	
	function get_import_items_in_background_settings(){
		return 0;
		return 1;	//for windows
	}
	
	function get_import_items_opening_balance_as_grn_settings(){
		return 1;
	}
	
	function get_limit_sales_order_to_invoice_to_units_available_settings(){
		//orders
		//get_restrict_creation_of_sales_invoice_from_order_to_units_available_settings
		return get_general_settings_value( array( "key" => "LIMIT SALES ORDER TO INVOICE TO UNITS AVAILABLE", "table" => "orders" ) );
		return 1;	//nisa pharmacy
	}
	
	function get_show_requisition_settings(){
		//all
		return get_general_settings_value( array( "key" => "SHOW REQUISITION", "table" => "all" ) );
		return 1;	//nisa pharmacy
		return 0;
	}
	
	function get_show_human_resource_settings(){
		//users
		return get_general_settings_value( array( "key" => "SHOW HUMAN RESOURCE SETTINGS", "table" => "users" ) );
		return 1;	//nisa pharmacy
		return 0;
	}
	
	function get_show_pay_roll_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "SHOW PAY ROLL", "table" => "pay_row" ) );
		return 1;	//nisa pharmacy
		return 0;
	}
	
	function get_show_enterprise_accounting_settings(){
		//transactions
		return get_general_settings_value( array( "key" => "SHOW ENTERPRISE ACCOUNTING", "table" => "transactions" ) );
		return 1;	//nisa pharmacy
		return 0;
	}
	
	function get_show_sales_order_settings(){
		//orders
		return get_general_settings_value( array( "key" => "SHOW SALES ORDER", "table" => "orders" ) );
		return 1;	//nisa pharmacy
		return 0;
	}
	
	function get_administrative_store(){
		//stores
		if( defined("HYELLA_DEFAULT_ADMIN_STORE") && HYELLA_DEFAULT_ADMIN_STORE ){
			return HYELLA_DEFAULT_ADMIN_STORE;
		}
		return get_current_store( 4 );
	}
	
	function get_report_cooperative_contributions_in_financial_reports_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "REPORT COOPERATIVE CONTRIBUTIONS IN FINANCIAL REPORTS", "table" => "pay_row" ) );
		return 0;
	}
	
	function get_sms_characters_limit_settings(){
		//bulk_messages
		return get_general_settings_value( array( "key" => "SMS CHARACTERS LIMIT VALUE", "table" => "bulk_message" ) );
		return 160;
	}
	
	function get_show_void_transactions_settings(){
		//transactions
		return get_general_settings_value( array( "key" => "SHOW VOID TRANSACTIONS", "table" => "transactions" ) );
		switch( get_package_option() ){
		case "hotel":
		case "pharmacy":
			return 1;
		break;
		}
	}
	
	function get_use_detailed_vendors_settings(){
		//vendors
		return get_general_settings_value( array( "key" => "USE DETAILED VENDORS", "table" => "vendors" ) );
		switch( get_package_option() ){
		case "pharmacy":
			return 1;
		break;
		}
	}
	
	function get_project_admin_email(){
		$a = get_general_settings_value( array( "key" => "ADMIN EMAIL", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'maybeachtech@gmail.com';
	}
	
	function get_project_sender_email(){
		$a = get_general_settings_value( array( "key" => "EMAIL SENDER", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'maybeachtech@gmail.com';
	}
	
	function get_project_smtp_auth_email(){
		$a = get_general_settings_value( array( "key" => "SMTP EMAIL", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'newsletter2@floramichaels.com';
	}
	
	function get_project_smtp_auth_password(){
		$a = get_general_settings_value( array( "key" => "SMTP PASSWORD", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'apple-86*-429-Min';
	}
	
	function get_project_smtp_encryption(){
		$a = get_general_settings_value( array( "key" => "SMTP ENCRYPTION", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'tls';
	}
	
	function get_project_smtp_host(){
		$a = get_general_settings_value( array( "key" => "SMTP HOST", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'smtp.ipage.com';
	}
	
	function get_project_smtp_port(){
		$a = get_general_settings_value( array( "key" => "SMTP PORT", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 587;
	}
	
	function get_project_reply_to_email(){
		$a = get_general_settings_value( array( "key" => "REPLY TO EMAIL", "table" => "general_settings" ) );
		if( $a ){
			return $a;
		}
		return 'maybeachtech@gmail.com';
	}
	
	function get_project_africastalking_api(){
		return get_general_settings_value( array( "key" => "AFRICASTALKING API", "table" => "general_settings" ) );
	}
	
	function get_project_africastalking_from(){
		return get_general_settings_value( array( "key" => "AFRICASTALKING FROM", "table" => "general_settings" ) );
	}
	
	function get_project_africastalking_username(){
		return get_general_settings_value( array( "key" => "AFRICASTALKING USERNAME", "table" => "general_settings" ) );
	}
	
	function get_do_not_control_bank_accounts_settings(){
		//all
		return get_general_settings_value( array( "key" => "DO NOT CONTROL BANK ACCOUNTS", "table" => "all" ) );
		return 1;
	}
	
	function get_validate_new_record_count_down_settings(){
		//all
		return get_general_settings_value( array( "key" => "VALIDATE NEW RECORD COUNT DOWN", "table" => "all" ) );
		return 180 * 1000;	//number of seconds to allow for validation;	//leave as zero to have unlimited validation time
	}
	
	function get_validate_new_record_settings(){
		//all
		return get_general_settings_value( array( "key" => "VALIDATE NEW RECORD SETTINGS", "table" => "all" ) );
		return 0;
	}
	
	function get_app_manager_control_number_of_records_count(){
		//all
		return get_general_settings_value( array( "key" => "APP MANAGER CONTROL NUMBER OF RECORDS COUNT", "table" => "all" ) );
		return 5;
	}
	
	function get_maximum_number_of_dependents_settings(){
		//users
		return get_general_settings_value( array( "key" => "MAXIMUM NUMBER OF DEPENDENTS", "table" => "users" ) );
		return 5;
	}
	
	function get_mertanity_leave_days_for_locum_staff_settings(){
		//users
		return get_general_settings_value( array( "key" => "MATERNITY LEAVE DAYS FOR LOCUM STAFF", "table" => "users" ) );
		return 90;	//90 days
	}
	
	function get_pay_roll_disable_absent_days_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "PAY ROLL DISABLE ABSENT DAYS", "table" => "pay_row" ) );
		return 1;	//1 - to hide absent days box in pay roll
	}
	
	function get_pay_roll_disable_leave_days_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "PAY ROLL DISABLE LEAVE DAYS SETTINGS", "table" => "pay_row" ) );
		return 1;	//1 - to hide leave days box in pay roll
	}
	
	function get_pay_roll_disable_leave_allowance_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "PAY ROLL DISABLE LEAVE ALLOWANCE", "table" => "pay_row" ) );
		return 0;	//until we understand how this allowance is being calculated
		return 1;	//1 - to hide leave allowance box in pay roll
	}
	
	function get_pay_roll_disable_disciplinary_charges_settings(){
		//pay roll
		return get_general_settings_value( array( "key" => "PAY ROLL DISABLE DISPIPLINARY SETTINGS", "table" => "pay_row" ) );
		return 1;	//1 - to hide disciplinary charges box in pay roll
	}
	
	
	//new section
	function get_show_asset_insurance_settings(){
		return get_general_settings_value( array( "key" => "SHOW ASSETS INSURANCE AND MAINTENANCE", "table" => "assets" ) );
		return 1;	//1 - add assets insurance / maintenance functions to the project
	}
	
	function get_asset_custodian_from_users_settings(){
		return get_general_settings_value( array( "key" => "USERS SHOULD BE ASSETS CUSTODIAN", "table" => "assets" ) );
		return 1;	//1 - assets custodian will be retrieved from users database
		//0 - assets custodian will be a text box
	}
	
	function get_use_customers_control_account_settings(){
		switch( get_package_option() ){
		case "gynae":
		case "hospital":
		case "pharmacy":
			//return 1;
		break;
		case "hotel":
			return 0;
		break;
		}
	}
	
	function get_show_cost_price_in_requisition(){
		return get_general_settings_value( array( "key" => "SHOW COST PRICE IN REQUISITION", "table" => "stock_request" ) );
		return 1;
	}
	
	function get_validate_requisition_settings(){
		return get_general_settings_value( array( "key" => "VALIDATE REQUISITION", "table" => "stock_request" ) );
		return 1;
	}
	
	function get_local_backup_directory_settings(){
		return get_general_settings_value( array( "key" => "LOCAL BACKUP DIRECTORY", "table" => "general_settings" ) );
		return "F:\\back1";
	}
	
	function get_default_post_category_settings(){
		$cat = get_general_settings_value( array( "key" => "DEFAULT CATEGORY ID FOR POINT OF SALES", "table" => "sales" ) );
		//$cat = '10463682443';	//default category id that pos items will be loaded from
		$r = get_record_details( array( "id" => $cat, "table" => "category" ) );
		$r["category_id"] = $cat;
		return $r;
	}
	
	function get_enforce_payment_category_settings(){
		return get_general_settings_value( array( "key" => "ENFORCE PAYMENT CATEGORY IN CAPTURE PAYMENT WINDOW", "table" => "sales" ) );
		return 1;	//1 = payment category will be compulsory during payment capture
		//0 = payment category will be optional
	}
	
	function get_allow_multiple_items_per_purchase_order_settings(){
		return get_general_settings_value( array( "key" => "ALLOW MULTIPLE UNIQUE ITEMS IN A SINGLE PURCHASE ORDER", "table" => "expenditure" ) );
		return 1;	//1 = system will allow users to add multiple unique items to the same purchase order
		//0 = onlyone unique item will be added to the purchase order
	}
	
	function get_report_logo_file_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "REPORT LOGO FILE", "table" => "general_settings" ) );
		return 'frontend-assets/img/logo-b.jpg';
	}
	
	function get_report_split_fixed_asset_and_depreciation_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "SPLIT FIXED ASSET AND DEPRECIATION IN BALANCE SHEET", "table" => "transactions" ) );
		return 1;
	}
	
	function get_report_balance_sheet_notes_type_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "BALANCE SHEET NOTES REPORT FORMAT", "table" => "transactions" ) );
		return 'list';
	}
	
	function get_status_of_saved_requisition_settings( $x = array() ){
		//@pend
		if( isset( $x[ 'interstore' ] ) && $x[ 'interstore' ] ){
			return get_general_settings_value( array( "key" => "STATUS OF SAVED REQUISITION INTER-STORE", "table" => "general_settings" ) );
		}
		return get_general_settings_value( array( "key" => "STATUS OF SAVED REQUISITION", "table" => "general_settings" ) );
		return 'stock-request';
	}
	
	function get_next_status_of_unvalidated_requisition_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "NEXT STATUS OF UNVALIDATED REQUISITION", "table" => "general_settings" ) );
	}
	
	function get_status_of_saved_work_order_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "STATUS OF SAVED WORK ORDER", "table" => "vendor_bill" ) );
		return 'approved';
	}
	
	function get_next_status_of_pending_work_order_settings(){
		return get_general_settings_value( array( "key" => "NEXT STATUS OF PENDING WORK ORDER", "table" => "vendor_bill" ) );
	}
	
	function get_prevent_edit_stock_request_during_issue_of_requisition_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "PREVENT EDIT STOCK REQUEST DURING ISSUE OF REQUISITION", "table" => "general_settings" ) );
	}
	
	function get_display_only_available_stock_during_issue_of_requisition_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "DISPLAY ONLY AVAILABLE STOCK DURING ISSUE OF REQUISITION", "table" => "general_settings" ) );
	}
	
	function get_compulsory_date_cleared_to_stock_field_purchase_order_settings(){
		//@pend
		return get_general_settings_value( array( "key" => "COMPULSORY DATE CLEARED TO STOCK FIELD PURCHASE ORDER", "table" => "expenditure" ) );
	}
	
	function get_show_basic_purchase_order_settings(){
		return get_general_settings_value( array( "key" => "SHOW BASIC PURCHASE ORDER", "table" => "expenditure" ) );
	}
	
	function get_disable_direct_creation_of_new_purchase_order_settings(){
		return get_general_settings_value( array( "key" => "DISABLE DIRECT CREATION OF NEW PURCHASE ORDER", "table" => "expenditure" ) );
	}
	
	function get_compulsory_staff_responsible_at_point_of_sale_settings(){
		return get_general_settings_value( array( "key" => "COMPULSORY STAFF RESPONSIBLE AT POINT OF SALE", "table" => "sales" ) );
	}
	
	function get_show_staff_responsible_at_point_of_sale_settings(){
		return get_general_settings_value( array( "key" => "SHOW STAFF RESPONSIBLE AT POINT OF SALE", "table" => "sales" ) );
	}
	
	function get_staff_sales_percentage_commission_settings(){
		return get_general_settings_value( array( "key" => "STAFF SALES PERCENTAGE COMMISSION", "table" => "sales" ) );
	}
	
	function get_automatic_staff_sales_percentage_commission_payout_account_id_settings(){
		return get_general_settings_value( array( "key" => "AUTOMATIC STAFF SALES PERCENTAGE COMMISSION PAYOUT ACCOUNT ID", "table" => "sales" ) );
	}
	
	function get_automatic_staff_sales_commission_expense_account_id_settings(){
		return get_general_settings_value( array( "key" => "AUTOMATIC STAFF SALES COMMISSION EXPENSE ACCOUNT ID", "table" => "sales" ) );
	}
	
	function get_show_fixed_asset_settings(){
		return get_general_settings_value( array( "key" => "SHOW FIXED ASSETS", "table" => "assets" ) );
		//1 - enable fixed assets module
	}
	
	function get_show_only_pending_items_when_receiving_goods_settings(){
		return get_general_settings_value( array( "key" => "SHOW ONLY PENDING ITEMS WHEN RECEIVING GOODS", "table" => "expenditure" ) );
		//1 - show only pending items
		//0 - show both pending items and items that have been completely received
	}
	
	function get_only_central_store_can_process_requisition_settings(){
		return get_general_settings_value( array( "key" => "ONLY CENTRAL STORE CAN PROCESS REQUISITION", "table" => "stock_request" ) );
		//1 - Only the main store can process requisition
		//0 - All stores can process internal requisition
	}
	
	function get_reorder_and_max_order_check_for_purchase_requisition_settings(){
		return get_general_settings_value( array( "key" => "REORDER AND MAX ORDER CHECK FOR PURCHASE REQUISITION", "table" => "stock_request" ) );
		//2 - Only the central store is checked based on the Reorder and Max Order Level settings when raising purchase requisition
		//1 - Only the requesting store is checked based on the Reorder and Max Order Level settings
		//0 - All stores are checked
	}
	
	function get_populate_vendor_quotation_with_default_price_in_purchase_requisition_settings(){
		return get_general_settings_value( array( "key" => "POPULATE VENDOR QUOTATION WITH DEFAULT PRICE IN PURCHASE REQUISITION", "table" => "stock_request" ) );
		//3 - Populate quotation with price in purchase requisition and override with vendor last purchase price
		//2 - Populate quotation with vendor last purchase price only and ignore price in purchase requisition
		//1 - Populate quotation with price in purchase requisition
		//0 - Leave quotation empty
	}
	
	function get_alphabetic_order_of_stock_level_report_settings(){
		return get_general_settings_value( array( "key" => "ALPHABETIC ORDER OF STOCK LEVEL REPORT", "table" => "inventory" ) );
		//1 - Order stock level report in alphabetic order
		//0 - Order stock level report based on quantity
	}
	
	function get_mobile_framework(){
		if( isset( $GLOBALS["mobile_framework"] ) && $GLOBALS["mobile_framework"] ){
			return $GLOBALS["mobile_framework"];
		}
	}
	
	function get_nw_req_origin(){
		$origin = '';
		if( isset( $_SERVER['REQUEST_URI'] ) && $_SERVER['REQUEST_URI'] ){
			$or = explode('?', $_SERVER['REQUEST_URI'] );
			if( isset( $or[1] ) ){
				$origin = '?' . $or[1];
			}
		}
		return $origin;
	}
	
	function get_form_headers( $params = array() ){
		$a = array(
			'id', 'uid', 'user_priv', 'table', 'table_id', 'processing', 'origin', 'nw_more_data');
		$h = '';
		$origin = get_nw_req_origin();
		
		
		
		$table = isset( $params["table"] )?$params["table"]:'';
		$params["processing"] = generate_token( array( 'table' => $table ) );
		$params["origin"] = $origin;
		
		foreach( $a as $av ){
			
			switch( $av ){
			case "nw_more_data":
				if( isset( $params[ $av ] ) ){
					$v3 = $params[ $av ];
					if( is_array( $v3 ) ){
						$v3 = json_encode( $v3 );
					}
					
					$h .= '<textarea class="hyella-data" style="height:1px;" name="'.$av.'">'. $v3 .'</textarea>';
				}
			break;
			default:
				$v3 = ( isset( $params[ $av ] )?$params[ $av ]:'' );
				$h .= '<input type="hidden" name="'.$av.'" value="'. $v3 .'" />';
			break;
			}
			
		}
		if( defined("NWP_DEV_SERVER_ACTION") && NWP_DEV_SERVER_ACTION && get_hyella_development_mode() ){
			if( isset( $params['action'] ) && $params['action'] ){		
				$h .= '<pre>action: '.$params['action'].'</pre>';
			}
			$h .= '<pre>origin: '.$origin.'</pre>';
		}
		return $h;
	}
	
	function get_nw_database_object( $inputs = array() ){
		$hide_names = 0;

		$form_style = '';
		$h = '';
		$params = array();
		$sparams = array();
		$options = array();
		
		$xids = explode( ",", $inputs["object_ids"] );
		
		$xvalues = isset( $inputs["values"] )?$inputs["values"]:'';
		$show_form = isset( $inputs["get_form"] )?$inputs["get_form"]:0;
		$field_idx = isset( $inputs["field_id"] )?$inputs["field_id"]:'';
		
		if( ! $field_idx ){
			return 'No Field ID';
		}
		
		if( is_array( $xids ) && ! empty( $xids ) ){
		foreach( $xids as $xid ){
		
			if( $xid == 'undefined' )continue;
			if( ! $xid )continue;
			
			$ax = array();
			
			if( $xvalues ){
				$ax = is_array( $xvalues ) ? $xvalues : json_decode( $xvalues, true );
				if( isset( $ax["data"] ) ){
					$ax["name"] = '';
					$ax["id"] = $xid;
					$ax["field_id"] = $field_idx;
				}
			}
			
			if( ! ( isset( $ax["data"] ) ) ){
				$de = get_record_details( array( "id" => $xid, "table" => "database_objects", "force" => 1 ) );
				
				switch( $de["object_type"] ){
				case "html":
					return $de["data"];
				break;
				}
				
				if( isset( $de["data"] ) && $de["data"] ){
					$dx1 = json_decode( $de["data"], true );
					if( is_array( $dx1 ) && ! empty( $dx1 ) ){
						
						//echo '<pre>'; print_r( $dx ); echo '</pre>';
						$ax = array(
							"data" => $dx1,
							"field_id" => $field_idx,
							"name" => $de["object_name"],
							"id" => $xid,
						);
					}
				}
			}
					// echo '<pre>'; print_r( $ax ); echo '</pre>';
			
			$params = array();
			if( isset( $ax ) && is_array( $ax ) ){
				$params = $ax;
			}
			
			if( isset( $params["data"]["type"] ) && $params["data"]["type"] ){
				
				$xid = ( isset( $params["id"] )?$params["id"]:'' );
				
				$xvalues = isset( $params["values"][ $xid ] )?$params["values"][ $xid ]:array();
				/*
				if( isset( $params["data"]["property"]["show_name"] ) && $params["data"]["property"]["show_name"] ){
					//isset( $params["name"] )?$params["name"]:
					$name = ( isset( $params["data"]["property"]["title"] )?$params["data"]["property"]["title"]:'' );
					$h .= '<h4><strong>'. $name .'</strong></h4>';
				}
				*/

				if( isset( $params["data"]["property"]["show_name"] ) && $params["data"]["property"]["show_name"] == 2 ){
					//isset( $params["name"] )?$params["name"]:
					$name = ( isset( $params["data"]["property"]["title"] )?$params["data"]["property"]["title"]:'' );
					if( $h )$h .= '<hr>';
					$h .= '<h4><strong>'. $name .'</strong></h4>';
				}
				
				if( isset( $params["data"]["property"]["form_style"] ) && $params["data"]["property"]["form_style"] ){
					$form_style .= $params["data"]["property"]["form_style"];
				}
				
				$tb = '';
				
				switch( $params["data"]["type"] ){
				case "field_group":
					// print_r( $params["data"]["fields"] );exit;
					if( isset( $params["data"]["fields"] ) && is_array( $params["data"]["fields"] ) && ! empty( $params["data"]["fields"] ) ){
						
						/*if( empty( $sparams ) ){
							$sparams = $params;
						}else{
							if( ! isset( $sparams[ 'data' ][ 'fields' ] ) ){
								$sparams[ 'data' ][ 'fields' ] = array();
							}
							$sparams[ 'data' ][ 'fields' ] = array_merge( $sparams[ 'data' ][ 'fields' ], $params["data"]["fields"] );
						}*/

						$count = 0;;
						$esign = '';
										// print_r( $params );exit;
						foreach( $params["data"]["fields"] as $field_id => $table ){
							
							$tr = '';
							$tb2 = '';

							if( isset( $inputs[ 'limit_field' ] ) && $inputs[ 'limit_field' ] ){
								if( isset( $inputs[ 'limit_field' ] ) && $inputs[ 'limit_field' ] !== $field_id ){
									continue;
								}
								$table["cloned"] = 1;
							}
							
							if( isset( $table["form_field"] ) && $table["form_field"] ){
								
								switch( $table["form_field"] ){
								case "fields_in_table":
								case "table":
									
									if( isset( $table["rows"] ) && is_array( $table["rows"] ) && ! empty( $table["rows"] ) ){
										
										$dup_value = array();
										$stack = isset( $table[ 'no_stack' ] ) ? $table[ 'no_stack' ] : 0;
										$merge_cells = isset( $table["rows"]["property"]["merge_cells"] ) && $table["rows"]["property"]["merge_cells"] ? 1 : 0;

										foreach( $table["rows"] as $rk => $row ){

											$td_duplicate = '';
											$td = '';

											if( isset( $row["cells"] ) && is_array( $row["cells"] ) && ! empty( $row["cells"] ) ){
												
												foreach( $row["cells"] as $ck => $cell ){
													$attr = '';
													$v = '';
													
													switch( $table["form_field"] ){
													case "fields_in_table":
														if( isset( $cell["form_field"] ) && $cell["form_field"] ){
															
															if( isset( $cell["rowspan"] ) && $cell["rowspan"] ){
																$attr .= ' rowspan="'. $cell["rowspan"] .'" ';
															}

															if( isset( $cell["colspan"] ) && $cell["colspan"] ){
																$attr .= ' colspan="'. $cell["colspan"] .'" ';
															}
															$embed = isset( $cell[ 'embed' ] ) && $cell[ 'embed' ] ? 1 : 0;

															if( $embed ){
																if( ! isset( $cell[ 'class' ] ) )$cell[ 'class' ] = '';
																// $cell[ 'class' ] .= ' embed-field ';
															}

															$iattr = isset( $cell['attributes'] )?$cell['attributes']:'';
															// $cell['attributes'] = $iattr . ' data-key="'.$ck.'" data-nkey="nw-'.$ir.'" data-nfield="'.$field_id.'" data-nid="'.$xid.'" ';
															
															$ips = array();
															$ips[ $ck ] = array();
															$saved_data = array();
															
															$cell['display_position'] = 1;
															switch( $cell["form_field"] ){
															case 'checkbox':
															case 'radio':
																$cell['dbo_attributes'] = $iattr . ' data-nkey="'.$ck.'" data-nfield="'.$field_id.'" data-nid="'.$xid.'" ';

																if( isset( $xvalues[ $field_id ][ $ck ] ) ){
																	$saved_data[ $ck ] = implode(':::', array_values( $xvalues[ $field_id ][ $ck ] ) );
																}
															break;
															default:
																if( isset( $xvalues[ $field_id ]["nw-0"][ $ck ] ) ){
																	$saved_data[ $ck ] = $xvalues[ $field_id ]["nw-0"][ $ck ];
																}
															break;
															}
															// unset( $cell[ 'embed' ] );
															
															$gbs = array(
																"fields" => array( $ck => $ck ),
																"labels" => array( $ck => $cell ),
															);
															if( $show_form ){
																$d1 = __get_value( '', '', array( 'form_fields' => $ips, 'form_values' => $saved_data, 'globals' => $gbs, "text-date" => 1 ) );
																

																if( ! empty( $d1 ) ){
																	foreach( $d1 as $d1k => $d1v ){
																		$count++;
																		switch( $cell["form_field"] ){
																		case 'signature':
																			if( isset( $inputs[ 'table' ] ) && $inputs[ 'table' ] && isset( $cell[ 'embed_signature' ] ) && $cell[ 'embed_signature' ] ){
																				$daa = str_replace( '{', '', $cell[ 'embed_signature' ] );
																				$daa = str_replace( '}', '', $daa );
																				$d1v["label"] = str_replace( $cell[ 'embed_signature' ], '<div class="'. $daa .'-btn-container"></div>', $d1v["label"] );
																				$esign .= "setTimeout(function(){nwConsentForms.createSignButton( '". $inputs[ 'table' ] ."', '". $ck ."', '". $daa ."', 'Signed' );},". ($count*10) .");";
																			}
																		break;
																		}
																		if( ! $embed )$v .= '<div class="nw-object-field">';
																		
																		if( ! $embed )$v .= '<label><strong>'. $d1v["label"] .'</strong></label>';
																		
																		if( $hide_names )$vx = str_replace( 'name="'. $ck .'"', '',  $d1v["field"] );
																		else $vx = $d1v["field"];
																		
																		if( $embed ){
																			// print_r( $vx );exit;
																		}
																	
																		$vx = str_replace( 'id="'. $ck .'"', 'id="'. $ck .'-nw-0" data-key="'.$ck.'" data-nkey="nw-0" data-nfield="'.$field_id.'" data-nid="'.$xid.'"', $vx );

																		$skip = 0;
																		if( $embed ){
																			$vx = str_replace( '{field}', $vx, $d1v["label"] );
																			if( isset( $cell[ 'embed_key' ] ) && $cell[ 'embed_key' ] ){
																				$skip = 1;
																				$td = str_replace( $cell[ 'embed_key' ], $vx, $td );
																			}
																		}

																		if( ! $skip )$v .=$vx;
																		
																		if( ! $embed )$v .= '</div>';

																	}
																}
																
															}else{
																$oarr = array( 'get_label_and_value' => 1, 'globals' => $gbs, "text-date" => 1, "source" => 'form' );
																switch( $cell[ 'form_field' ] ){
																case 'date-5time':
																	if( isset( $saved_data[ $ck ] ) && strpos( $saved_data[ $ck ], 'T' ) > -1 ){
																		$saved_data[ $ck ] = strtotime( $saved_data[ $ck ] );
																	}
																break;
																case 'signature':
																	$oarr[ 'pagepointer' ] = '../';
																break;
																case 'calculated':
																	$oarr[ 'globals' ][ 'labels' ][ $ck ][ 'calculations' ][ 'variables' ] = array( array( $ck ) );
																// 	$oarr[ 'pagepointer' ] = '../';
																break;
																case 'time':
																	$oarr[ 'invert' ] = 1;
																break;
																}
																// echo '<pre>';
																// print_r( $saved_data );
																// print_r( $cell[ 'form_field' ] );
																// echo '</pre>';
																$d1 = __get_value( ( isset( $saved_data[ $ck ] )?$saved_data[ $ck ]:'' ), $ck, $oarr );

																if( isset( $d1["value"] ) && isset( $d1["label"] ) ){
																	$xvl = '<label><strong>'. $d1["label"] .'</strong></label><br />' . ( $d1["value"] ? $d1["value"] : '&nbsp;' );

																	if( $embed ){
																		$d1["value"] = '<span style="font-family: cursive;display: inline;"><u>'. $d1["value"] .'</u></span>';
																		$xvl = str_replace( '{field}', $d1["value"], $d1["label"] );
																		if( isset( $cell[ 'embed_key' ] ) && $cell[ 'embed_key' ] ){
																			$td = str_replace( $cell[ 'embed_key' ], $d1["value"], $td );
																		}
																	}

																	if( isset( $cell[ 'embed_signature' ] ) && $cell[ 'embed_signature' ] ){
																		$xvl = str_replace( $cell[ 'embed_signature' ], $d1["value"], $d1["label"] );
																	}

																	$v .= $xvl;
																}
															}
															
														}
													break;
													default:
														$v = isset( $cell["text"] )?$cell["text"]:'';
														
														if( isset( $cell["colspan"] ) && $cell["colspan"] ){
															$attr .= ' colspan="'. $cell["colspan"] .'" ';
														}
														// echo '<pre>'; print_r( $cell ); echo '</pre>';
														if( isset( $cell["rowspan"] ) && $cell["rowspan"] ){
															$attr .= ' rowspan="'. $cell["rowspan"] .'" ';
														}
														
														if( isset( $row["property"]["accept_values"] ) && $row["property"]["accept_values"] ){
															$ips = array();
															
															$ck = $cell["id"];
															
															$ips[ $ck ] = array();
															$saved_data = array();
															
															$cell['field_label'] = '';
															$cell['display_position'] = 1;
															$cell['show_only_input'] = 1;
															
															$iattr = isset( $cell['attributes'] )?$cell['attributes']:'';
															
															if( isset( $row["property"]["num_of_rows"] ) && intval( $row["property"]["num_of_rows"] ) ){
																$row_count = intval( $row["property"]["num_of_rows"] );
																for( $ir = 0; $ir < $row_count; $ir++ ){

																	$saved_data = array();
																	if( isset( $cell[ 'unique' ] ) && $cell[ 'unique' ] ){
																		$ck .= $ir;
																		$ips[ $ck ] = array();
																	}
																	
																	$cell['attributes'] = $iattr . ' data-key="'.$ck.'" data-nkey="nw-'.$ir.'" data-nfield="'.$field_id.'" data-nid="'.$xid.'" ';
																	$cell['placeholder'] = isset( $row["property"]["row_property"][ $ir ][ 'placeholder' ] ) ? $row["property"]["row_property"][ $ir ][ 'placeholder' ] : '';

																	if( ! ( isset( $cell["rows_text"][ $ir ] ) && ! is_array( $cell["rows_text"][ $ir ] ) ) ){
																		if( isset( $row["property"]["row_property"][ $ir ][ 'use_field' ][ 'form_field' ] ) && $row["property"]["row_property"][ $ir ][ 'use_field' ][ 'form_field' ] ){
																			$cell["form_field"] = $row["property"]["row_property"][ $ir ][ 'use_field' ][ 'form_field' ];
																		}
																		
																		if( isset( $row["property"]["row_property"][ $ir ][ 'use_field' ][ 'form_field_options' ] ) && $row["property"]["row_property"][ $ir ][ 'use_field' ][ 'form_field_options' ] ){
																			$cell["form_field_options"] = $row["property"]["row_property"][ $ir ][ 'use_field' ][ 'form_field_options' ];
																		}
																	}
																	
																	if( isset( $row["property"]["field_style"] ) && $row["property"]["field_style"] ){
																		if( ! isset( $cell["attributes"] ) ){
																			$cell["attributes"] = '';
																		}
																		$cell["attributes"] .= " style='". $row["property"]["field_style"] ."' ";
																	}

																	if( isset( $xvalues[ $field_id ][ "nw-" . $ir ][ $ck ] ) ){
																		$saved_data[ $ck ] = $xvalues[ $field_id ][ "nw-" . $ir ][ $ck ];
																	}

																	$gbs = array(
																		"fields" => array( $ck => $ck ),
																		"labels" => array( $ck => $cell ),
																	);
																	
																	if( ! isset( $dup_value[ $ir ] ) ){
																		$dup_value[ $ir ] = '';
																	}

																	if( isset( $cell["form_field"] ) ){
																		if( $show_form ){

																			$d1 = __get_value( '', '', array( 'form_fields' => $ips, 'form_values' => $saved_data, 'globals' => $gbs, "text-date" => 1, 'mikeeX' => 1 ) );
																			
																			if( isset( $d1[ $ck ]['field'] ) && $d1[ $ck ]['field'] ){
																				
																				if( isset( $row["property"]["td_cell_style"] ) && $row["property"]["td_cell_style"] ){
																					$attr .= " style='". $row["property"]["td_cell_style"] ."' ";
																				}

																				$dup_value[ $ir ] .= '<td '.$attr.' class="nw-object-field">' . ( $hide_names ? str_replace( 'name="'. $ck .'"', '', $d1[ $ck ]['field'] ) : $d1[ $ck ]['field'] ) .'</td>';
																			}
																		
																		}else{
																			// print_r( $cell );
																			if( $cell[ 'form_field' ] ){
																				switch( $cell[ 'form_field' ] ){
																				case 'date-5time':
																					$saved_data[ $ck ] = isset( $saved_data[ $ck ] ) ? strtotime( $saved_data[ $ck ] ) :'';
																				break;
																				}
																			}
																			
																			$d1 = __get_value( ( isset( $saved_data[ $ck ] )?$saved_data[ $ck ]:'' ), $ck, array( 'get_label_and_value' => 1, 'globals' => $gbs, "text-date" => 1 ) );
											
																			if( isset( $d1["value"] ) ){
																				$dup_value[ $ir ] .= '<td>' . ( $d1["value"] ? $d1["value"] : '&nbsp;' ) . '</td>';
																			}
																		}
																		
																	}else{
																		$rtsp = '';
																		if( isset( $row["property"]["row_text_style"] ) && $row["property"]["row_text_style"] ){
																			$rtsp = ' style="'. $row["property"]["row_text_style"] .'" ';
																		}
																		$dup_value[ $ir ] .= '<td '. $rtsp .'><strong>';
																		
																		if( isset( $cell["rows_text"][ $ir ] ) && ! is_array( $cell["rows_text"][ $ir ] ) ){
																			$dup_value[ $ir ] .= $cell["rows_text"][ $ir ];
																		}else if( isset( $cell["rows_text"][ $ir ][ 'id' ] ) && isset( $cell["rows_text"][ $ir ][ 'form_field' ] ) ){
																			$cell["rows_text"][ $ir ]['display_position'] = 1;
																			$cfl = $cell["rows_text"][ $ir ][ 'id' ];

																			if( isset( $cell[ 'attributes' ] ) && $cell[ 'attributes' ] ){
																				$cell["rows_text"][ $ir ][ 'attributes' ] = $cell[ 'attributes' ];
																			}

																			if( ! ( isset( $cell["rows_text"][ $ir ][ 'field_label' ] ) && $cell["rows_text"][ $ir ][ 'field_label' ] ) ){
																				if( isset( $cell["rows_text"][ $ir ][ 'text' ] ) && $cell["rows_text"][ $ir ][ 'text' ] ){
																					$cell["rows_text"][ $ir ][ 'field_label' ] = $cell["rows_text"][ $ir ][ 'text' ];
																				}elseif( isset( $cell["rows_text"][ $ir ][ 'id' ] ) && $cell["rows_text"][ $ir ][ 'id' ] ){
																					$cell["rows_text"][ $ir ][ 'field_label' ] = $cell["rows_text"][ $ir ][ 'id' ];
																				}
																			}

																			if( isset( $saved_data[ $ck ] ) && $saved_data[ $ck ] ){
																				$saved_data[ $cfl ] = $saved_data[ $ck ];
																			}

																			// print_r( $saved_data );exit;
																			$gbs = array(
																				"fields" => array( $cfl => $cfl ),
																				"labels" => array( $cfl => $cell["rows_text"][ $ir ] ),
																			);
																			
																			if( $show_form ){
																				$d1 = __get_value( 
																					'',
																					'', 
																					array( 
																						'form_fields' => array( $cfl => array() ), 
																						'form_values' => $saved_data, 
																						'globals' => $gbs, 
																						"text-date" => 1,
																						// "mikee" => 1,
																					) 
																					/*'drugxx', 
																					array( 
																						'get_label_and_value' => 1, 
																						'globals' => array(
																							'fields' => array(
																					            'drugxx' => 'drugxx',
																					        ),
																						    'labels' => array(
																					            'drugxx' => array(
																				                    'id' => 'drugxx',
																				                    'text' => 'Drugs',
																				                    'field_label' => '',
																				                    'form_field' => 'calculated',
																				                    'required_field' => 'yes',
																				                    'calculations' => array(
																			                            'type' => 'record-details',
																			                            'reference_table' => 'users',
																			                            'reference_keys' => array(
																		                                    '0' => 'firstname',
																		                                    '1' => 'lastname',
																		                                ),
																			                            'form_field' => 'text',
																			                            'variables' => array(
																		                                    '0' => array(
																	                                            '0' => 'witness',
																	                                        ),
																		                                ),
																			                        ),
																				                    'attributes' => 'action="?action=stores&todo=get_active_store" minlength="0"  data-key="drug1" data-nkey="nw-0" data-nfield="drugs_section" data-nid="dts2964875681"', 
																				                    'class' => ' select2 ',
																				                    'display_position' => '1',
																				                    'show_only_input' => '1',
																				                ),
																					        )
																						), 
																						// 'globals' => $gbs, 
																						// "text-date" => 1 
																					) */
																				);

																				if( isset( $d1[ $cfl ]['field'] ) && $d1[ $cfl ]['field'] ){
																					$dup_value[ $ir ] .= ( $hide_names ? str_replace( 'name="'. $ck .'"', '', $d1[ $cfl ]['field'] ) : $d1[ $cfl ]['field'] );
																				}elseif( isset( $d1["value"] ) ){
																					$dup_value[ $ir ] .= $d1["value"];
																				}

																			}else{
																				$d1 = __get_value( 
																					( isset( $saved_data[ $cfl ] )?$saved_data[ $cfl ]:'' ), 
																					$cfl, 
																					array( 
																						'get_label_and_value' => 1, 
																						'form_values' => $saved_data, 
																						'globals' => $gbs, 
																						"text-date" => 1,
																						"mikee" => 1,
																					)
																				);
																				if( isset( $d1["value"] ) ){
																					$dup_value[ $ir ] .= $d1["value"];
																				}
																			}
																			
																		}
																		
																		$dup_value[ $ir ] .= '</strong></td>';
																	}
																	
																}
															}
														}
													break;
													}
													
													if( isset( $row["property"]["hide_column_header"] ) && $row["property"]["hide_column_header"] ){
														if( ! $merge_cells )$td = '<td colspan="'. count( $row["cells"] ) .'" '.$attr.' >';
														$td .= '&nbsp;';
														if( ! $merge_cells )$td .= '</td>';
													}else{
														$skip_td = 0;

														if( $merge_cells ){
															$skip_td = 1;
														}
														if( isset( $cell[ 'skip_cell' ] ) && $cell[ 'skip_cell' ] ){
															$skip_td = 1;
														}

														if( isset( $cell[ 'colspan' ] ) && $cell[ 'colspan' ] )$attr .= ' colspan="'. $cell[ 'colspan' ] .'" ';

														if( ! $skip_td )$td .= '<td '.$attr.' >';
														$td .= $v;
														if( ! $skip_td )$td .= '</td>';
													}
													
													// switch( $cell["form_field"] ){
													// case 'checkbox':
													// 	// print_r( $v );exit;
													// break;
													// }
												}
												
											}else{
											}
											
											if( $td ){
												
												$attr = '';
											
												if( isset( $row["property"]["style"] ) && $row["property"]["style"] ){
													$attr .= ' style="'. $row["property"]["style"] .'" ';
												}
												
												if( isset( $row["property"]["class"] ) && $row["property"]["class"] ){
													$attr .= ' class="'. $row["property"]["class"] .'" ';
												}
												
												if( ! ( isset( $row["property"]["skip_row_header"] ) && $row["property"]["skip_row_header"] ) ){
													if( ! $merge_cells )$tr .= '<tr '.$attr.'>';
													$tr .= $td;
													if( ! $merge_cells )$tr .= '</tr>';
												}
													
												if( ! empty( $dup_value ) ){
													foreach( $dup_value as $dpv ){
														if( ! $merge_cells )$tr .= '<tr>';
														$tr .= $dpv;
														if( ! $merge_cells )$tr .= '</tr>';
													}
												}
											
												if( $stack )$dup_value = array();
												/*
												if( isset( $row["property"]["num_of_rows"] ) && intval( $row["property"]["num_of_rows"] ) ){
													$row_count = intval( $row["property"]["num_of_rows"] );
													for( $ir = 0; $ir < $row_count; $ir++ ){
														
														$tr .= '<tr>'. str_ireplace( 'nw-obj-row','nw-'. $ir, $td_duplicate ) .'</tr>';
													}
												}
												*/
											}
											
										}
									}
									
									if( $tr ){
										
										$yid = md5( $field_idx . $field_id . $xid. time() );
										
										$clone_button = '';
										$adcls = '';
										$tbattr = '';
										if( $show_form ){
											if( isset( $table["clone"] ) && $table["clone"] ){
												$clone_button .= '<button type="button" class="btn btn-sm default" onclick="$.fn.cProcessForm.cloneTable('. "'". $yid ."'" .', '. "'clone'" . ');">Clone</button>';
											}
											if( isset( $table["cloned"] ) && $table["cloned"] ){
												$clone_button .= '<button type="button" class="btn btn-sm default" onclick="$.fn.cProcessForm.cloneTable('. "'". $yid ."'" .' , '. "'remove'" . ');">Remove</button>';
											}
											if( isset( $table["div_class"] ) && $table["div_class"] ){
												$adcls .= ' '.$table["div_class"];
											}
											if( isset( $table["table_style"] ) && $table["table_style"] ){
												$tbattr .= ' style="'. $table["table_style"] .'" ';
											}
										}
										
										$tb2 .= '<div id="nw-obj-button-'. $yid . '" class="'. $adcls  .'" ><table '. $tbattr .' class="table table-bordered nw-objects-'.$table["form_field"].'" width="100%" id="nw-object-'. $yid .'" data-nfield="'.$field_id.'" data-nid="'.$xid.'" data-field="'.$field_idx.'">'. $tr .'</table>' . $clone_button.'</div>';
										// $tb .= $tb2;
									}
									
								break;
								case 'capture_multiple':
									if( isset( $table[ 'fields' ] ) && ! empty( $table[ 'fields' ] ) ){
										$filds = '';
										$tth = '';
										$cbdy = '';
										$ctot = array();
										$cfoot = '';
										// print_r( $xvalues );exit;
										foreach( $table[ 'fields' ] as $flk => $cell ){
											
											$ck = isset( $cell[ 'id' ] ) ? $cell[ 'id' ] : $flk;
											$ips = array();
											$ips[ $ck ] = array();

											if( ! isset( $cell[ 'text' ] ) ){
												$cell[ 'text' ] = '';
											}
											
											$cell['field_label'] = $cell[ 'text' ];
											$cell['display_position'] = 1;
											$cell['show_only_input'] = 1;
											$cell['class'] = 'clientFormField';
											$cell['attributes'] = ' no-auto-num="1" ';
											
											$gbs = array(
												"fields" => array( $ck => $ck ),
												"labels" => array( $ck => $cell ),
											);
											
											if( isset( $cell["form_field"] ) ){
												$tth .= '<th>'. $cell[ 'text' ] .'</th>';
												if( $show_form ){

													$d1 = __get_value( '', '', array( 'form_fields' => $ips, 'form_values' => $saved_data, 'globals' => $gbs, "text-date" => 1, "show_form_labels" => 1, 'mikee' => 1 ) );
													// print_r( $ips );
													// print_r( $gbs );
													// print_r( $d1 );exit;
													
													if( isset( $d1[ $ck ]['field'] ) && $d1[ $ck ]['field'] ){
														$filds .= $d1[ $ck ]['field'];
													}
												}
											}
										}

										$xvl = isset( $xvalues[ $field_id ][ $field_id ][ 'undefined' ] ) ? json_decode( $xvalues[ $field_id ][ $field_id ][ 'undefined' ], 1 ) : array();
										$table[ 'values' ] = $xvl;
										if( ! $show_form ){
											if( ! empty( $xvl ) ){
												foreach( $xvl as $fk => $fv ){
													$cbdy .= '<tr>';
													foreach( $table[ 'fields' ] as $flk => $cell ){

														$k2 = $cell[ 'id' ] . '_text';
														if( isset( $fv[ $k2 ] ) ){
															$cbdy .= '<td>'. $fv[ $k2 ] .'</td>';
														}else if( isset( $fv[ $cell[ 'id' ] ] ) ){
															$cbdy .= '<td>'. $fv[ $cell[ 'id' ] ] .'</td>';
														}else{
															$cbdy .= '<td>&nbsp;</td>';
														}

														if( ! isset( $ctot[ $cell[ 'id' ] ] ) ){
															$ctot[ $cell[ 'id' ] ] = 0;
														}
														switch( $cell[ 'form_field' ] ){
														case 'number':
															if( isset( $fv[ $cell[ 'id' ] ] ) ){
																$ctot[ $cell[ 'id' ] ] += floatval( $fv[ $cell[ 'id' ] ] );
															}
														break;
														}
													}
													$cbdy .= '</tr>';
												}

												if( isset( $table[ 'total' ] ) && $table[ 'total' ] && ! empty( $ctot ) ){
													// $cfoot .= '<tr><td colspan="'. ( count( $ctot ) + 1 ) .'">&nbsp</td></tr>';
													$cfoot .= '<tr>';
														foreach( $ctot as $tt ){
															$cfoot .= '<td><strong>'. $tt .'</strong></td>';
														}
													$cfoot .= '</tr>';
												}

											}
										}else{
											$tth .= '<th class="r"></th>';
										}

										$view = '';
										if( $show_form ){
											$view .= '
											<div class="row">
												<div class="col-md-12" id="client-form-'. $field_id .'">
													<textarea class="hyella-data" id="client-form-structure-'. $field_id .'">'. json_encode( $table ) .'</textarea>
													<textarea class="hyella-data form-gen-element" id="client-form-data-'. $field_id .'" data-nfield="'. $field_id .'" data-nid="'. $xid .'" data-nkey="'. $field_id .'" name="'. $field_id .'"></textarea>
													<div class="row">
														<div class="col-md-3">
															<div id="client-form-fields-'. $field_id .'" client-id="'. $field_id .'" class="client-form-fields" cart-item="training-participants">
																<input value="" class="form-control" type="hidden" name="cf_id" />

																'. $filds .'
																
																<input class="btn dark btn-block" id="client_form_submit_'. $field_id .'" value="Add Section" />
																
															</div>	
															
														</div>
														<div class="col-md-9">';
										}

														$view .= '
															<br />
															<div class="shopping-cart-table">
																<div class="table-responsive">
																	<table class="table table-striped table-hover bordered">
																		<thead>
																		   <tr>
																			  '. $tth .'
																		   </tr>
																		</thead>
																		<tbody id="client-table-view-'. $field_id .'">
																		  '. $cbdy .'
																		</tbody>
																		<tfoot>
																		   '. $cfoot .'
																		</tfoot>
																	</table>
																</div>
															</div>';

										if( $show_form ){
											$view .= '
															</div>
														</div>

													</div>
												</div>
												<script>
													nwClientForm.getSavedData("'. $field_id .'");
													nwClientForm.refreshClientForm("'. $field_id .'");
													nwClientForm.submitForm("'. $field_id .'");
												</script>
											';
										}

										
										$tb2 .= '<div id="nw-obj-button-'. $yid . '" class="'. $adcls  .'" >' . $view .'</div>';
									}
								break;
								default:
									$ips = array();
									$ips[ $field_id ] = array();
									$saved_data = array();
									
									$table['display_position'] = 1;
									
									$gbs = array(
										"fields" => array( $field_id => $field_id ),
										"labels" => array( $field_id => $table ),
									);
									
									if( isset( $xvalues[ $field_id ]["nw-0"][ $field_id ] ) ){
										$saved_data[ $field_id ] = $xvalues[ $field_id ]["nw-0"][ $field_id ];
									}
									
									if( $show_form ){
										
										$d1 = __get_value( '', '', array( 'form_fields' => $ips, 'form_values' => $saved_data, 'globals' => $gbs, "text-date" => 1 ) );
										
										if( ! empty( $d1 ) ){
											foreach( $d1 as $d1k => $d1v ){
												$tr .= '<div class="nw-object-field">';
													$tr .= '<label><strong>'. $d1v["label"] .'</strong></label>';
													
													if( $hide_names )$vx = str_replace( 'name="'. $field_id .'"', '',  $d1v["field"] );
													else $vx = $d1v["field"];
												
													$vx = str_replace( 'id="'. $field_id .'"', 'id="'. $field_id .'-nw-0" data-key="'.$field_id.'" data-nkey="nw-0" data-nfield="'.$field_id.'" data-nid="'.$xid.'"', $vx );
													
													$tr .= $vx;
												$tr .= '</div>';
											}
										}
									}else{
										$d1 = __get_value( ( isset( $saved_data[ $field_id ] )?$saved_data[ $field_id ]:'' ), $field_id, array( 'get_label_and_value' => 1, 'globals' => $gbs, "text-date" => 1 ) );
										
										if( isset( $d1["value"] ) && isset( $d1["label"] ) ){
											$tr .= '<label><strong>'. $d1["label"] .'</strong></label><br />' . $d1["value"];
										}
									}
									
									$tb2 .= $tr;
									// $tb .= $tr2;
								break;
								}
								
								if( $tb2 ){
									if( isset( $table["title"] ) && $table["title"] ){
										$tb2 = '<h5><strong>'. $table["title"] .'</strong></h5>' . $tb2;
									}
								}
							}
							$tb .= $tb2;
							// break;
						}
						
						$h .= $tb;
						if( $esign ){
							// print_r( $esign );exit;
							$h .= "<script>nwConsentForms.table = '". $inputs[ 'table' ] ."';nwConsentForms.init();". $esign ."</script>";
						}
						
					}else{
						$h .= 'Error: Undefined Data Object Table<br />';
					}
					
				break;
				case "html":
				default:
					$h .= 'Error: Invalid Data Object Type<br />';
				break;
				}
				
			}else{
				$h .= 'Error: Undefined Data Object Type<br />';
			}
		}
		}
		
		if( $show_form ){
			$h .= '<textarea name="'.$field_idx.'" class="nw-database_objects-store" style="display:none;">'. json_encode( $params ) .'</textarea>';
		}
		
		return '<div id="'.$field_idx.'-nw-object-container" data-field="'.$field_idx.'" class="nw-database_objects" style="'. $form_style .'">' . $h . '</div>';
	}
	
	
	function get_salary_calculation_percentage_settings(){
		return array(
			"basic_salary" => 30, 	//40
			"housing" => 30, //20
			"transport" => 10, 
			"hazard_allowance" => 10,
			"inconvenience_allowance" => 10,
			"utility" => 5,
			"lunch" => 5, 
			"bonus" => 0, 
			"leave_allowance" => 0, 
			"call_allowance" => 0, 
			"arrears_allowance" => 0, 
			"ara_allowance" => 0, 
			"compound_allowance" => 0, 
			"extra_duty_allowance" => 0,
			"paye_deduction" => 3,
			"pension_employee" => 4,
			"pension_employer" => 5,
		);
	}
	
	function get_specify_category_without_deduction_settings(){
		return 'interns_paid,interns_unpaid,nysc,it';		//doctors profession id gotten from general options
	}
	
	function get_specify_category_without_pension_deduction_settings(){
		return 'locum_part,locum_full';		//doctors profession id gotten from general options
	}
	
	function get_specify_category_without_nma_settings(){
		return 'locum_part,locum_full';		//doctors profession id gotten from general options
	}
	
	function get_doctors_profession_value_settings(){
		return get_general_settings_value( array( "key" => "DOCTORS UNIQUE ID FOR PROFESSION FIELD", "table" => "pay_row" ) );
		return 'bs18165776158';		//doctors profession id gotten from general options
	}
	
	function get_nma_deduction_value_settings(){
		return get_general_settings_value( array( "key" => "NMA DEDUCTION AMOUNT", "table" => "pay_row" ) );
		return 2000;	//nma fixed amount deduction for salary details
	}
	
	function get_hide_bill_after_sales_settings(){
		return get_general_settings_value( array( "key" => "HIDE BILL AFTER SALES", "table" => "sales" ) );
		//1 - do not show bill after sales
		//0 - show bill
	}
	
	function get_top_no_for_revenue_analysis_settings(){
		$no = intval( get_general_settings_value( array( "key" => "TOP NUMBER FOR REVENUE ANALYSIS", "table" => "transactions" ) ) );
		if( ! $no )$no = 5;
		return $no;
	}
	
	function get_enterprise_inventory_settings(){
		return doubleval( get_general_settings_value( array( "key" => "USE ENTERPRISE INVENTORY", "table" => "general_settings" ) ) );
	}
	
	function get_hyella_has_independent_branches_settings(){
		if( defined("HYELLA_HAS_INDEPENDENT_BRANCHES") && HYELLA_HAS_INDEPENDENT_BRANCHES ){
			return 1;
		}
	}
	
	function get_show_unpaid_customer_invoice_in_post_transactions(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW UNPAID CUSTOMER INVOICE IN POST TRANSACTIONS", "table" => "transactions" ) ) );
	}
	
	function get_show_unpaid_vendor_invoice_in_post_transactions(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW UNPAID VENDOR INVOICE IN POST TRANSACTIONS", "table" => "transactions" ) ) );
	}
	
	function get_quick_view_default_message_settings(){
		return '<div style="text-align:center;"><br /><br /><br /><h2>Quick View Window</h2><hr />Select a record by clicking on it</div>';
	}
	
	function get_payment_method_select_settings(){
		return doubleval( get_general_settings_value( array( "key" => "ALWAYS SELECT PAYMENT METHOD", "table" => "general_settings" ) ) );
	}
	
	function get_back_date_time_range(){
		return doubleval( get_general_settings_value( array( "key" => "BACK DATE TIME RANGE", "table" => "general_settings" ) ) ) * 86400;
	}
	
	function get_chart_of_accounts_code_settings(){
		return doubleval( get_general_settings_value( array( "key" => "COMPULSORY ACCOUNT CODES", "table" => "general_settings" ) ) );
	}
	
	function get_default_password_settings(){
		return get_general_settings_value( array( "key" => "DEFAULT USER ACCOUNT PASSWORD", "table" => "general_settings" ) );
	}
	
	function get_account_posting_type_settings(){
		return get_general_settings_value( array( "key" => "POST TRANSACTIONS INTO PARENT ACCOUNTS", "table" => "general_settings" ) );
	}
	
	function get_show_account_type_settings(){
		return get_general_settings_value( array( "key" => "SHOW ACCOUNT TYPE ON CHART OF ACCOUNTS REPORT", "table" => "general_settings" ) );
	}
	
	function get_auto_restrict_child_account_codes_settings(){
		return get_general_settings_value( array( "key" => "AUTO RESTRICT ACCOUNT CODES OF CHILD ACCOUNTS", "table" => "chart_of_accounts" ) );
	}
	
	function get_group_posting_by_ref_settings(){
		return get_general_settings_value( array( "key" => "GROUP POSTING OF TRANSACTIONS BY REF NO", "table" => "transactions" ) );
	}
	
	function get_approve_settings_after_posting_settings(){
		return get_general_settings_value( array( "key" => "HIDE APPROVE BUTTON AFTER SAVING POSTING", "table" => "transactions" ) );
	}
	
	function get_allow_future_date_settings(){
		//mistake in general settings, thus it works in reverse
		$al = doubleval( get_general_settings_value( array( "key" => "ALLOW FUTURE DATE", "table" => 'general_settings' ) ) );
		
		if( $al ){
			$al = 0;
		}else{
			$al = 1;
		}
		return $al;
	}
	
	function get_single_clinic_settings(){
		if( ! ( defined("HYELLA_MULTI_CLINIC") && HYELLA_MULTI_CLINIC ) ){
			return get_main_store();
		}
	}
	
	function get_list_box_options( $key = '', $opt = array() ){
		if( $key && isset( $opt["return_type"] ) ){
			$rd = get_record_details( array( "id" => md5( $key ), "table" => "list_box_options" ) );;
			
			if( isset( $rd["data"] ) ){
				$r = json_decode( $rd["data"], true );
				
				if( isset( $r["options"] ) && is_array( $r["options"] ) && ! empty( $r["options"] ) ){
					$r["data2"] = array();
					
					switch( $opt["return_type"] ){
					case 2:
						return $r["options"];
					break;
					}
					
					foreach( $r["options"] as $ek => $ev ){
						$r["data2"]["keys"][] = $ek;
						$r["data2"]["values"][] = $ev;
					}
					
					switch( $opt["return_type"] ){
					case 1:
						return $r["data2"];
					break;
					}
					
				}
				
			}
		}
	}
	
	
	function add_nwp_plugin_options( $opt = array() ){
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

	function get_nwp_plugin_hooks( $opt = array() ){
		$data = isset( $opt["data"] )?$opt["data"]:array();
		$key = isset( $opt["key"] )?$opt["key"]:'';
		$h = '';
		
		if( isset( $data[ $key ] ) && is_array( $data[ $key ] ) && ! empty( $data[ $key ] ) ){
			foreach( $data[ $key ] as $dk ){
				$typ = isset( $dk["type"] )?$dk["type"]:'';
				
				switch( $typ ){
				case "html":
					$h .= isset( $dk[ $typ ] )?$dk[ $typ ]:'';
				break;
				}
				
			}
		}
		
		return $h;
	}
	
	function get_file_hash( $opt = array() ){
        
        $cipher = "aes-128-gcm";
        if( isset( $opt["decrypt"] ) && isset( $opt["key"] ) ){
            /* if( defined( "MIS_ENCRYPT_URL" ) && MIS_ENCRYPT_URL ){
                //echo base64_decode( rawurldecode( $opt["decrypt"] ) ); exit;
                $cipher = MIS_ENCRYPT_URL;
                
                if (in_array($cipher, openssl_get_cipher_methods()))
                {
                    $b = explode(':::', base64_decode( rawurldecode( $opt["decrypt"] ) ) );
                    
                    if( isset( $b[1] ) ){
                        
                        $iv = ( $b[0] );
                        $id = $b[1];
                        $tag = 'durl';
                        return base64_decode( openssl_decrypt($id, $cipher, $opt["key"], 0, $iv, $tag) );
                    }
                    
                }
                
                
            } */
            return base64_decode( rawurldecode( $opt["decrypt"] ) );
        }else if( isset( $opt["encrypt"] ) && isset( $opt["key"] ) ){
            
            /* if( defined( "MIS_ENCRYPT_URL" ) && MIS_ENCRYPT_URL ){
                $cipher = MIS_ENCRYPT_URL;
                
                if (in_array($cipher, openssl_get_cipher_methods()))
                {
                    $ivlen = openssl_cipher_iv_length($cipher);
                    $iv = openssl_random_pseudo_bytes($ivlen);
                    $tag = 'durl';
                    return rawurlencode( base64_encode( openssl_encrypt( $opt["encrypt"], $cipher, $opt["key"], 0, $iv, $tag ) . ':::' . base64_encode($iv) ) );
                }
            } */
            
            return rawurlencode( base64_encode( $opt["encrypt"] ) );
        }
        
        $ss = defined( "MIS_SESSION_SALTER" ) ? MIS_SESSION_SALTER : '';
        $salter = defined( "HYELLA_URL_SALTER" ) ? HYELLA_URL_SALTER : '';
		
        $hash = '';
        if( isset( $opt["hash"] ) && isset( $opt["file_id"] ) ){
            
            switch( $opt["hash"] ){
            case 1:
                $day = '';
                if( isset( $opt["date_filter"] ) && $opt["date_filter"] ){
                    $day = strtotime( date( $opt["date_filter"] ) );
                }
                if( $ss && ! isset( $opt["no_session"] ) ){
                    $salter .= isset( $_SESSION["key"] )?$_SESSION["key"]:'';
                }
                $hash = md5( $salter . $day . $opt["file_id"] );
            break;
            }
        }
        
        return $hash;
    }

	function htmlentities_decode( $h = '' ){
		return html_entity_decode( $h );
	}
	
	function show_line_comment_in_purchase_order(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW LINE COMMENT IN PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function show_uom_in_purchase_order(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW UNIT OF MEASUREMENT IN PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function get_send_auto_email_for_purchase_order(){
		return doubleval( get_general_settings_value( array( "key" => "AUTO SEND EMAIL FOR PURCHASE ORDER", "table" => "expenditure" ) ) );
	}
	
	function get_send_auto_email_for_grn(){
		return doubleval( get_general_settings_value( array( "key" => "AUTO SEND EMAIL FOR GRN", "table" => "expenditure" ) ) );
	}
	
	function get_show_surcharge_in_grn(){
		return doubleval( get_general_settings_value( array( "key" => "SHOW SURCHARGE IN GOODS RECEIVED NOTE", "table" => "expenditure" ) ) );
	}
	
	function get_gset_discount_account(){
		return trim( get_general_settings_value( array( "key" => "DISCOUNT ACCOUNT", "table" => "hospital" ) ) );
	}
	
	function nwp_reload_callback( $o = array() ){
		if( isset( $o["type"] ) ){
			switch( $o["type"] ){
			case 'get':
				$r = array();
				if( isset( $o["ctype"] ) && isset( $_SESSION["nwp_rl_cb"][ $o["ctype"] ] ) ){
					
					switch( $o["ctype"] ){
					case "active_tab":
						$r = $_SESSION["nwp_rl_cb"][ $o["ctype"] ];
					break;
					}
					
					unset( $_SESSION["nwp_rl_cb"] );
				}
				return $r;
			break;
			default:
				if( isset( $o["callback"] ) ){
					$_SESSION["nwp_rl_cb"] = $o["callback"];
				}
			break;
			}
		}
	}
	
	function nwp_request_hash_key( $o = array() ){
		if( defined("NWP_USE_HASH") && NWP_USE_HASH ){
			if( isset( $o["test"] ) && $o["test"] ){
				if(  isset( $_GET["nwp_hash"] ) && isset( $_SESSION["nwp_hash"][ $_GET["nwp_hash"] ] ) ){
					return $_GET["nwp_hash"];
				}else if(  isset( $GLOBALS["nwp_target"]["nwp_hash"] ) && isset( $_SESSION["nwp_hash"][ $GLOBALS["nwp_target"]["nwp_hash"] ] ) ){
					return $GLOBALS["nwp_target"]["nwp_hash"];
				}
				return '';
			}else if( isset( $o["set"] ) && $o["set"] ){
				$_SESSION["nwp_hash"][ $o["set"] ] = 1;
				return $o["set"];
			}else if( isset( $o["clear"] ) && isset( $_SESSION["nwp_hash"][ $o["clear"] ] ) ){
				unset( $_SESSION["nwp_hash"][ $o["clear"] ] );
			}
			
			$hk = md5( rand() . NWP_USE_HASH . rand() );
			//$_SESSION["nwp_hash"] = array();
			$_SESSION["nwp_hash"][ $hk ] = 1;
			return $hk;
		}else{
			return 1;
		}
	}
	
	function nw_breadcrum_limit(){
		return '2';
	}
	
	function nw_clear_breadcrum( $o = array() ){
		if( isset( $o['index'] ) ){
			$idx = array_keys( $_SESSION["nw_bc"] );
			$as = array_search( $o['index'] , $idx );
			
			if( ! empty( $idx ) ){
				foreach( $idx as $i => $iv ){
					if( $i > $as && isset( $_SESSION["nw_bc"][ $iv ] ) ){
						unset( $_SESSION["nw_bc"][ $iv ] );
					}
				}
			}
		}
	}
	
	function nw_set_breadcrum( $o = array() ){
		$s = array();
		if( isset( $_GET['nw_breadcrum_index'] ) && $_GET['nw_breadcrum_index'] ){
			if( isset( $_SESSION["nw_bc"][ $_GET['nw_breadcrum_index'] ] ) ){
				nw_clear_breadcrum( array( "index" => $_GET['nw_breadcrum_index'] ) );
			}else{
				unset( $_SESSION["nw_bc"] );
			}
			$GLOBALS["nw_breadcrum"] = 1;
			$GLOBALS['nw_breadcrum_index'] = 1;
			unset( $_GET["nw_breadcrum_index"] );
		}
		
		if( isset( $_GET['nw_use_breadcrum'] ) && $_GET['nw_use_breadcrum'] ){
			$GLOBALS["nw_use_breadcrum"] = intval( $_GET['nw_use_breadcrum'] );
			unset( $_GET["nw_use_breadcrum"] );
		}
		
		if( isset( $_GET['nw_breadcrum'] ) && $_GET['nw_breadcrum'] ){
			$get = $_GET;
			unset( $get["nw_breadcrum"] );
			
			//$s["index"] = isset( $_SESSION["nw_bc"] )?count( $_SESSION["nw_bc"] ):0;
			$s["get"] = http_build_query( $get );
			$s["title"] = isset( $_GET["title"] )?rawurldecode( $_GET["title"] ):'Back';
			
			if( isset( $_GET["nwf_title"] ) && $_GET["nwf_title"] ){
				$s["title"] = rawurldecode( $_GET["nwf_title"] );
			}else if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
				$s["title"] = rawurldecode( $_GET["menu_title"] );
			}
			
			$s["id"] = isset( $_POST["id"] )?$_POST["id"]:'-';
			
			$s["steps"] = isset( $GLOBALS["nw_use_breadcrum"] )?$GLOBALS["nw_use_breadcrum"]:1;
			$s["index"] = md5( $s["get"] . $s["title"] . $s["id"] );
			
			if( isset( $_SESSION["nw_bc"][ $s["index"] ] ) ){
				nw_clear_breadcrum( array( "index" => $s["index"] ) );
			}
			
			$_SESSION["nw_bc"][ $s["index"] ] = $s;
			
			unset( $get );
		}
				//print_r( $_SESSION["nw_bc"] ); exit;
		//unset( $_SESSION["nw_bc"] );
		$GLOBALS["nw_breadcrum"] = $s;
	}
	
	function nw_get_breadcrum( $o = array() ){
		$h = '';
		//print_r($GLOBALS["nw_breadcrum"]); exit;
		$oty = isset( $o["type"] )?$o["type"]:'';
		
		if( isset( $GLOBALS["nw_breadcrum"]["get"] ) && $GLOBALS["nw_breadcrum"]["get"] ){
			switch( $oty ){
			case "modal":
				$h = '<a href="#" style="margin-left:10px;" class="modal-bread-crum custom-single-selected-record-button" override-selected-record="'. ( isset( $GLOBALS["nw_breadcrum"]["id"] )?$GLOBALS["nw_breadcrum"]["id"]:'' ) .'" action="?'. $GLOBALS["nw_breadcrum"]["get"] .'&modal=1&html_replacement_selector=modal-replacement-handle" title="Click Display"><i class="icon-refresh"></i> '. ( isset( $GLOBALS["nw_breadcrum"]["title"] )?$GLOBALS["nw_breadcrum"]["title"]:'' ) .'</a>';
			break;
			default:
			
				$back_count = isset( $GLOBALS["nw_use_breadcrum"] )?$GLOBALS["nw_use_breadcrum"]:0;
				$index = isset( $GLOBALS["nw_breadcrum"]["index"] )?$GLOBALS["nw_breadcrum"]["index"]:'';
				
				$nw_bcc = array();
				$idx = array();
				$p_index = '';
				if( isset( $_SESSION["nw_bc"] ) && is_array( $_SESSION["nw_bc"] ) ){
					$idx = array_keys( $_SESSION["nw_bc"] );
					$as = ( array_search( $index , $idx ) - 1 );
					$p_index = isset( $idx[ $as ] )?$idx[ $as ]:'';
					
					$xi = ( $as - $back_count );
					if( $xi < 0 )$xi = 0;
					
					for( $x = $xi; $x < $as; $x++ ) {
						//echo $x . '===';
						if( isset( $idx[ $x ] ) && isset( $_SESSION["nw_bc"][ $idx[ $x ] ]["get"] ) ){
							$nw_bcc[] = $_SESSION["nw_bc"][ $idx[ $x ] ];
						}
					}
					
					if( isset( $_SESSION["nw_bc"][ $p_index ]["get"] ) ){
						$nw_bcc[] = $_SESSION["nw_bc"][ $p_index ];
					}
					
					/* print_r( $back_count );
					print_r( $as );
					print_r( $nw_bcc );
					print_r( $_SESSION["nw_bc"] ); exit; */
				}
				
				
				if( ! empty( $nw_bcc ) ){
					foreach( $nw_bcc as $nw_bc ){
						$nt = ( isset( $nw_bc["title"] )?$nw_bc["title"]:'' );
						$nt1 = $nt;
						if( strlen( $nt ) > 15 )$nt1 = substr( $nt, 0, 12 ) . '...';
						
						$h .= '<a href="#" class="nw-breadcrum '.$oty.'-nw-breadcrum custom-single-selected-record-button" override-selected-record="'. ( isset( $nw_bc["id"] )?$nw_bc["id"]:'' ) .'" title="'. $nt .'" action="?'. $nw_bc["get"] .'&nw_breadcrum=1&nw_breadcrum_index='. $nw_bc["index"] .'&nw_use_breadcrum='. $nw_bc["steps"] .'">'. $nt1 .'</a> &rarr; ';
					}
				}
			break;
			}
			
		}
		//echo $h;
		//echo $h; exit;
		return $h;
	}

	function nwp_enc22( $content, $obsfucate ){
		$bs = array( "e"=>"_bdk_", "o"=>"_jdk_","u"=>"_odk_","t"=>"+edk+","."=>"-tdk-","1"=>":.dk:","g"=>"*1dk*","s"=>"@Gdk@","0"=>"%Sdk%" );
		
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
	
	function itemize_by_key( $o = array(), $key = '' ){
		if( $o && $key ){
			$_o = [];
			foreach ($o as $value) {
				if( isset( $value[ $key ] ) ){
					$_o[ $value[$key ]] = $value;

				}else throw new Exception("Given Key :[". $key ."] is not present in Array", 1);
			}
			if( $_o) return $_o;
		}
		return $o;
	}

	function itemize_by_key_multi( $o = array(), $key = '' ){
		if( $o && $key ){
			$_o = [];
			$a = [];
			foreach ($o as $value) {
				if( isset( $value[ $key ] ) ){
					$_o[ $value[ $key ] ][] = $value;

				}//else throw new Exception("Given Key :[". $key ."] is not present in Array", 1);
			}
			if( $_o){ return $_o; }
		}

		return $o;
	}

	function null_array( $o ){
		if( $o ){
			$keys = array_keys( $o );
			if( $keys ){
				foreach( $keys as $key ){
					if( isset($o[ $key ]) && $o[ $key ] ){
						return false;
					}
				}
			}
		}
		return true;
	}

	function unset_null_array( $o ){
		if( $o ){
			if( is_array( $o ) ){
				foreach ($o as $key => $value) {
					if( is_array($value) ){
						if( null_array( $value ) ){
							unset( $o[ $key ] );
						}else $o[ $key ] = unset_null_array( $value );
					}
				}
			}
		}
		return $o;
	}

	function array_columns(array $arr, array $keys){    
	    $keys = array_flip($keys);
	    return array_map(
	        function($a) use($keys) {
	            return array_intersect_key($a,$keys);
	        },
	        $arr
	    );
	}
	
	function _gdate($s, $e){
		$d = '';
		$year = $month = $day = '';
		if( $s && $e ){
			if( date("Y", $s) == date("Y", $e) ){
				$year = date("Y", $s);
				if( date("M", $s) == date("M", $e) ){
					$month = date("F", $s);
					if( date("d", $s) == date("d", $e) ){
						$day = date("jS", $s);
					}
				}
			}
		}

		if( $year ){
			$d = $year;
			if( $month ){
				$d = $month. ', '. $d;
				if( $day ){
					$d = $day .' '.$d;
				}else{
					$d = date("jS", $s)." to ".date("jS", $e).' '.$d;
				}
			}else{
				$d = date("jS F", $s)." to ".date("jS F", $e) . ', '.$d;
			}
		}else{

			if( $s ){
				$s = date("jS F, Y", $s);
			}else{
				$s = '&#8734;';
			}
			if( $e ){
				$e = date("jS F, Y", $e);
			}else{
				$e = '&#8734;';
			}
			$d = $s." to ". $e;
		}

		return $d;
	}
	
	function truncate_string($string,$length=100,$append="&hellip;") {
	  $string = trim($string);

	  if(strlen($string) > $length) {
	    $string = wordwrap($string, $length);
	    $string = explode("\n", $string, 2);
	    $string = $string[0] . $append;
	  }

	  return $string;
	}
	
	function nwp_add_created_by_to_value( $val, $o = array() ){
		if( isset( $o["record"]["created_by"] ) && $o["record"]["created_by"] ){
			$val .= '<br>by: <b>' . get_name_of_referenced_record( array( "id" => $o["record"]["created_by"], "table" => "users" ) ) . '</b>';
		}
		return $val;
	}
	
	function nwp_file_clean_up( $filename, $o = array() ){
		if( isset( $o["new"] ) && $o["new"] ){
			$perm = 0666;
			if( defined("NWP_FILE_PERM") ){
				$perm = NWP_FILE_PERM;
			}
			@chmod( $filename, $perm );
		}
	}
	
	function __calculate_percentage( array $a ){
		if( $a ){
			$w = 100 / count( $a );
			return round( array_reduce( array_map(function( $x ) use ( $w ){
				return ($x / 100) * $w;
			}, $a), function($c, $d){
				return $c + $d;
			}), 2);
		}
		return 0;
	}
	
	function _load_namespace_function_instance($func='', $opt = null){
		if( $func && defined('HR_VARIANT') && HR_VARIANT ){
			$_ = '_'. strtolower(HR_VARIANT);
			$cls = 'cNwp_human_resource'.$_ ;
			if( class_exists( $cls ) ){
				$nwp = new $cls();
				$nwp->load_class(['class' => [ $func ]]);
				if( isset( $nwp->namespace ) && $nwp->namespace ){
					$f = $nwp->namespace. '\\'.  $func ;
					if( function_exists( $f ) ){
						return $f( $opt );
					}
				}
			}
		}
	}
	function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			// return utf8_encode($mixed); php 8.2 Deprecated Notice
			return mb_convert_encoding($mixed, "UTF-8");
		}
		return $mixed;
	}																   
?>
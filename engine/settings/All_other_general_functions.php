<?php
/**
 * General Functions File
 *
 * @used in  				settings/Setup.php
 * @created  				none
 * @database table name   	none28
 */

/*
|--------------------------------------------------------------------------
| KOBOKO General Functions File
|--------------------------------------------------------------------------
|
| General functions would to perform several task in the KOBOKO
|
*/

function frontend( $site_url ){
	return str_replace( "engine/", "", $site_url );
}

function __date(){
    //
    $settings = array(
        'cache_key' => 'today-date',
    );
    $date = get_cache_for_special_values( $settings );
    if( ! $date ){
        $date = date("U");
        $settings = array(
            'cache_key' => 'today-date',
            'cache_values' => $date,
        );
        set_cache_for_special_values( $settings );
    }
    return $date;
}

function __cleardate(){
    $settings = array(
        'cache_key' => 'today-date',
    );
    clear_cache_for_special_values( $settings );
}

function clean3( $string ) {
	$string = str_replace(' ', '_', trim( $string ) ); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\_]/', '', $string); // Removes special chars.
}

function clean1( $string ) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
   return $string; // Removes special chars.
}

function clean_numbers( $string ) {
   //$string = str_replace('.', '_', $string); // Replaces all spaces with hyphens.
   $string = $string ? preg_replace('/[^0-9\-\.]/', '', $string) : ''; // Removes special chars.
   return $string; // Removes special chars.
}

function clean2( $string, $rep = ' ' ) {
   //$string = str_replace(' ', '__', $string); // Replaces all spaces with hyphens.
   
  // $string = preg_replace('/[^A-Za-z0-9\_]/', '', $string); // Removes special chars.
  //return preg_replace("/[^a-zA-Z0-9! @#$&()\\`.+,\/\[\]\}\{\-]/", '', $string); // Removes special chars.
  return preg_replace("/[^a-zA-Z0-9! @#$&()`.+,\[\]\}\{\-_]/", $rep, $string); // Removes special chars.
  // return str_replace('__', ' ', $string); // Removes special chars.
}

function clean_for_alphabets( $string ) {
   $string = trim($string); // Replaces all spaces with hyphens.
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z\-]/', '', $string); // Removes special chars.
   return str_replace('-', ' ', $string); // Removes special chars.
}

function strip_leading_zero( $val ){
	$val = preg_replace( '/[^A-Za-z0-9\.]/', '', trim( $val ) );
	//check if str value until u hit a number
	if( substr( $val , 0, 1 ) == '0' ){
		if( substr( $val , 1, 1 ) == '0' ){
			if( substr( $val , 2, 1 ) == '0' ){
				return substr( $val , 3 );
			}
			return substr( $val , 2 );
		}
		return substr( $val , 1 );
	}
	return $val;
}

//removes malicious code from data
function clean($value="", $data = '', $pagepointer = ''){
	if($value){
		$value = trim($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);
		$value = addslashes($value);
		$value = strtolower($value);
		
		switch($data){
		case "email":
			$email = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','1','2','3','4','5','6','7','8','9','0','@','_','.');
			
			if(strlen($value) < 50){
				$value = strtolower($value);
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
						
					}
				}
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "number":
			$email = array('1','2','3','4','5','6','7','8','9','0',',','.');
			
			if(strlen($value) < 50){
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
					}
				}
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "id":
			$email = array('a','b','c','d','e','f','1','2','3','4','5','6','7','8','9','0','_');
			
			if(strlen($value) < 50){
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
					}
				}
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "url":
			$email = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','_','.','/','=');
			
			if(strlen($value) < 50){
				$value = strtolower($value);
				for($x=0; $x<strlen($value); $x++){
					if(!(in_array($value[$x],$email))){
						//LOG MALICIOUS CODE AND MAIL ADMIN
						$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
						
						//REMOVE MALICIOUS CHARACTER
						$value[$x] = '';
						
					}
				}
				
			}else{
				//LOG MALICIOUS CODE AND MAIL ADMIN
				$mal['value'][] = '<span style="color:red;">To many characters detected in value = '.substr($value,0,500).'</span>';
				
				//CLEAR VALUE
				$value = '';
			}
		break;
		case "alphabets":
			$email = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','_','-');
			
			for($x=0; $x<strlen($value); $x++){
				if(!(in_array($value[$x],$email))){
					//LOG MALICIOUS CODE AND MAIL ADMIN
					$mal['value'][] = '<span style="color:red;">Malicious characters detected in value = '.$value.'</span>';
					
					//REMOVE MALICIOUS CHARACTER
					$value[$x] = '';
				}
			}
		break;
		}
		
		if(isset($mal['value']) && is_array($mal['value'])){
			//Record Threat in Audit Trail
			auditor_threat($pagepointer,$mal['value']);
			//Mail Threat to ADMIN
		}
		
		return $value;
	}
	return 0;
}

//Returns name of all values passed via HTTP GET REQUEST
function get_get_variables($no){
	$returning_html_data = '';
	if(isset($_GET)){
		foreach($_GET as $k => $v){
			if(!in_array($k,$no)){
				$returning_html_data .= '&'.$k.'='.$v;
			}
		}
	}
	return $returning_html_data;
}

//Returns the currently set unit of a Physical Quantity
function get_units_converting_to($physical_quantity){
	$un = md5('units'.$_SESSION['key']);
	if(isset($_SESSION[$un][$physical_quantity]) && $_SESSION[$un][$physical_quantity]){
		return str_replace(' ','_',$_SESSION[$un][$physical_quantity]);
	}
	return 0;
}

// Year-Month-Day 2020-11-01
function convert_date_to_timestamp( $date , $type = 0, $options = array() ){
	$date = trim( $date );
	$values = explode( '-' , $date );
	
	if( ( ! isset( $values[2] ) ) && strlen( $date ) == 8 ){
		$values[0] = substr( $date, 0, 4 );
		$values[1] = substr( $date, 4, 2 );
		$values[2] = substr( $date, 6, 2 );
	}
	
	if( ( ! isset( $values[2] ) ) ){
		$v = explode( '/' , $date );
		if( isset( $v[2] ) ){
			$values[0] = $v[2];
			$values[1] = $v[1];
			$values[2] = $v[0];
		}
	}
	$s = 0;
	$m = 0;
	$h = 0;
	// print_r( $date );exit;
	if( isset( $values[2] ) && strpos( $values[2], 'T' ) > -1 ){
		$x = substr( $values[2], strpos( $values[2], 'T' ) + 1 );
		$ex = explode( ":", $x );

		if( isset( $ex[0] ) && $ex[0] )$h = $ex[0];
		if( isset( $ex[1] ) && $ex[1] )$m = $ex[1];
		if( isset( $ex[2] ) && $ex[2] )$s = $ex[2];
	}
	
	if( isset( $values[0] ) && isset( $values[1] ) && isset( $values[2] ) ){
		$year = intval( $values[0] );
		$month = intval( $values[1] );
		$day = intval( $values[2] );
		
		switch( $type ){
		case 1:
			return mktime( 0, 0, 0, $month , $day , $year );
		break;
		case 2:
			return mktime( 23, 59, 59, $month , $day , $year );
		break;
		case 11:
			return mktime( $h, $m, 0, $month , $day , $year );
		break;
		case 22:
			return mktime( $h, $m, 59, $month , $day , $year );
		break;
		case 3:
			$hour = isset( $options["hour"] )?$options["hour"]:0;
			$minute = isset( $options["minute"] )?$options["minute"]:0;
			$second = isset( $options["second"] )?$options["second"]:0;
			
			return mktime( $hour, $minute, $second, $month , $day , $year );
		break;
		case 4:
			// print_r(  );
			return mktime( $h, $m, $s, $month , $day , $year );
		break;
		}
		return mktime( date("H"), date("i"), date("s"), $month , $day , $year );
	}
	
}

function convert_date_to_timestampP( $date , $type = 0 ){
	$date = trim( $date );
	$values = explode( '-' , $date );
	
	if( ( ! isset( $values[2] ) ) && strlen( $date ) == 8 ){
		$values[0] = substr( $date, 0, 4 );
		$values[1] = substr( $date, 4, 2 );
		$values[2] = substr( $date, 6, 2 );
	}
	
	$mon = array(
		'jan' => 1,
		'feb' => 2,
		'mar' => 3,
		'apr' => 4,
		'may' => 5,
		'jun' => 6,
		'jul' => 7,
		'aug' => 8,
		'sep' => 9,
		'oct' => 10,
		'nov' => 11,
		'dec' => 12,
	);
	
	if( ( ! isset( $values[2] ) ) ){
		$v = explode( '/' , $date );
		if( isset( $v[2] ) ){
			$values[0] = $v[2]; //$values[0] = $v[0];
			
			$values[1] = $v[1];//isset( $mon[ strtolower( $v[1] ) ] )?$mon[ strtolower( $v[1] ) ]:$v[1];
			$values[2] = $v[0];
		}
	}
	
	if( isset( $values[0] ) && isset( $values[1] ) && isset( $values[2] ) ){
		$year = 2017;
		$month = intval( $values[1] );
		$day = intval( $values[0] );
		
		switch( $type ){
		case 1:
			return mktime( 0, 0, 0, $month , $day , $year );
		break;
		case 2:
			return mktime( 23, 59, 59, $month , $day , $year );
		break;
		}
		return mktime( date("H"), date("i"), date("s"), $month , $day , $year );
	}
	
}

//Returns the default value of the naira equivalent of 1 USD
function get_naira_equivalent_of_one_us_dollar(){
	return 155;
}

//Converts Numbers from one format to another
function format_and_convert_numbers( $value_to_be_converted , $conversion_type = 0, $from_units = "", $currency_conversion_rate = 0 ){
	
	$value_to_be_converted = strip_tags( $value_to_be_converted );
	
	if($conversion_type==4){
		return number_format( doubleval( $value_to_be_converted ) , 2 );
	}
	
	
	$value_to_be_converted = clean_numbers( $value_to_be_converted );
	$value_to_be_converted = doubleval( $value_to_be_converted );
	
	if($conversion_type==2){
		return number_format($value_to_be_converted,4);
	}
	
	if($conversion_type==1){
		return number_format($value_to_be_converted,0);
	}
	
	if($conversion_type==3){
		return $value_to_be_converted;
	}
	
	if($conversion_type==6){
		return number_format( $value_to_be_converted , 2 );
	}
	
	if($conversion_type==7){
		if( $value_to_be_converted > 0 )
			return number_format( $value_to_be_converted , 2 );
		
		return '(' . number_format( abs( $value_to_be_converted ) , 2 ) . ')';
	}
	
	if($conversion_type==5){
		return number_format($value_to_be_converted,1);
	}
	
	//Volume
	if($conversion_type>10){
		$value_to_be_converted = $value_to_be_converted / 1;
		
		switch($conversion_type){
		case 24:	//Time
			//Obtain Based on Value Set while entering record ** tricky
			$from_units_array = get_time_units();
			if(isset($from_units_array[$from_units]))
				$from_units = $from_units_array[$from_units];
			else
				$from_units = 'months';
				
			$physical_quantity = 'time';
		break;
		case 18:	//Currency
			//Obtain Based on Value Set while entering record ** tricky
			$from_units_array = get_currency();
			
			if((isset($from_units) && !is_array($from_units)) && isset($from_units_array[$from_units]))
				$from_units = $from_units_array[$from_units];
			else
				$from_units = 'usd';
				
			$physical_quantity = 'currency';
		break;
		}
		
		if($from_units){
			//Get Current Unit of Volume
			$to_units = get_units_converting_to($physical_quantity);
			
			if(!$to_units){
				$to_units = $from_units;
			}
			
			switch($conversion_type){
			case 18:	//Currency
				$value_to_be_converted = currency_converter($value_to_be_converted, $from_units, $to_units, $currency_conversion_rate);
			break;
			case 24:	//Time
				$value_to_be_converted = time_converter($value_to_be_converted, $from_units, $to_units);
			break;
			}
			//Determine Number of Decimal Places to Display
			$deci_places = 2;
			if(abs($value_to_be_converted) < 0.01){
				$deci_places = 4;
			}
			if(abs($value_to_be_converted) < 0.0001){
				$deci_places = 8;
			}
			if($value_to_be_converted==0){
				$deci_places = 2;
			}
			
			return number_format($value_to_be_converted, $deci_places).' <span style="font-size:0.8em;">'.str_replace('_',' ',$to_units).'</span>';
		}
	}
	
	return number_format($value_to_be_converted,2);
}

function convert_currency( $usd_value , $direction = 'from usd' , $eliminate_symbol = 0 ){
    $usd_value = strip_tags($usd_value);
	$usd_value = trim($usd_value);
	$usd_value = str_replace(',','',$usd_value);
    
	$usd_value = doubleval( $usd_value );
    
    $country_id = SELECTED_COUNTRY_ID;
    $currency = SELECTED_COUNTRY_CURRENCY.' ';
    $rate = SELECTED_COUNTRY_CONVERSION_RATE;
    
	$ngn_rate = 1;
	if( defined("NIGERIAN_NAIRA_CONVERSION_RATE") ){
		$ngn_rate = NIGERIAN_NAIRA_CONVERSION_RATE * 1;
	}
    if( ! doubleval( $ngn_rate ) ){
        $ngn_rate = 1;
    }
    
    if( ! doubleval( $rate ) ){
        $rate = 1;
        $currency = '$ ';
        $country_id = '1228';
    }
    
    switch( $direction ){
    case 'get symbol':
        return $currency;
    break;
    case 'to usd':
        return ( $usd_value / $rate );
    break;
    case 'to ngn':
        $dollar = $usd_value / $rate;
        return ( $dollar * $ngn_rate );
    break;
    case 'from ngn':
        //naira to usd first
        $converted_value = $usd_value / $ngn_rate;
        
        //$converted_value = $converted_value * $rate;
        
        if($eliminate_symbol){
            return round( $converted_value , 2 );
        }
        
        switch( $country_id ){
        case '1157':
            return $currency . number_format( $converted_value , 0 );
        break;
        default:
            return $currency . number_format( $converted_value , 2 );
        break;
        }
    break;
    default:
        $converted_value = $usd_value * $rate;
        
        if($eliminate_symbol){
            return round( $converted_value , 2 );
        }
        
        switch( $country_id ){
        case '1157':
            return $currency . number_format( $converted_value , 0 );
        break;
        default:
            return $currency . number_format( $converted_value , 2 );
        break;
        }
    break;
    }
    
}

//Returns generated codes
function generateCodes( $plength, $include_letters , $include_capitals , $include_numbers , $include_punctuation ){

    // First we need to validate the argument that was given to this function
	$pwd = '';
    // If need be, we will change it to a more appropriate value.
    if(!is_numeric($plength) || $plength <= 0)
    {
        $plength = 8;
    }
    if($plength > 32)
    {
        $plength = 32;
    }

    // This is the array of allowable characters.
            $chars = "";

            if ($include_letters == true) { $chars .= 'abcdefghijklmnopqrstuvwxyz'; }
            if ($include_capitals == true) { $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
            if ($include_numbers == true) { $chars .= '0123456789'; }
            if ($include_punctuation == true) { $chars .= '£$%^&*()-_=+[{]};:@#~,<.>?'; }

            // If nothing selected just display 0's
            if ($include_letters == false AND $include_capitals == false AND $include_numbers == false AND $include_punctuation == false) {
                $chars .= '0';
            }

    // This is important:  we need to seed the random number generator
    //mt_srand( microtime() * 1000000 );
    mt_srand( date("U") );

    // Now we simply generate a random string based on the length that was
    // requested in the function argument
    for($i = 0; $i < $plength; $i++)
    {
        $key = mt_rand(0,strlen($chars)-1);
        $pwd = $pwd . $chars[$key];
    }

    // Finally to make it a bit more random, we switch some characters around
    for($i = 0; $i < $plength; $i++)
    {
        $key1 = mt_rand(0,strlen($pwd)-1);
        $key2 = mt_rand(0,strlen($pwd)-1);

        $tmp = $pwd[$key1];
        $pwd[$key1] = $pwd[$key2];
        $pwd[$key2] = $tmp;
    }

    // Convert into HTML
    $pwd = htmlentities($pwd, ENT_QUOTES);

    return $pwd;
}

//Get Client IP Address
function get_ip_address() {
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
					return $ip;
				}
			}
		}
	}
}

//Return the first few characters of a string
function return_first_few_characters_of_a_string( $data , $len = 158 ){
	
	$result_of_sql_queryaw = $data;
	$data = strip_tags($data);
	//Determine if string length is greater than 132
	if(strlen($data) > $len){
		/*
		//Get the character at position 132
		$char = substr($data,($len-1),1);
		
		//Check if $char is not spacebar; then seek next position of spacebar
		if(!($char==' ')){
			while($char!=' '){
				$char = substr($data,(++$len-1),1);
			}
		}
		*/
		$data = substr($data,0,$len);
		
		return $data.'...';
	}else{
		return $data;
	}
	//Take the last 12 characters of the short string
	///$locate = substr($data,-12,12);
	//Break original string by this amount
	///$pieces = explode($locate,$result_of_sql_queryaw);
	//Put back short string with html formatting
	///$data = $pieces[0].$locate;
	
}

//Write a file to disk
function write_file( $url , $file_name , $content_of_file ){
	$full_file_name = $url . $file_name;
	
	if( file_exists( $full_file_name ) ){			
		$fp = fopen( $full_file_name , "w" );
		fputs( $fp , $content_of_file );
		fclose( $fp );
	}
}

//Return contents of a file from disk
function read_file( $url = null , $file_name = '' ){
	$full_file_name = $url.$file_name;
	
	if( file_exists( $full_file_name ) ){
		
		$fp = fopen($full_file_name, "r");
		
		$contents_of_file = '';
		
		if( $fp ){
			while( ! feof( $fp ) ) {
				$contents_of_file .= fgets( $fp , 2024 );
			}
		}
		fclose( $fp );
		
		return $contents_of_file;
	}
}

//Insert a new record into a database table and return response of such operation as boolean
function create( $settings ){
	$database_name = $settings[ 'database_name' ]; 
	$action_to_perform = isset( $settings[ 'action_to_perform' ] ) ? $settings[ 'action_to_perform' ] : ''; 
	$parent_tb = isset( $settings[ 'parent_tb' ] ) ? $settings[ 'parent_tb' ] : ''; 
	$table_name = $settings[ 'table_name' ]; 
	$database_connection = $settings[ 'database_connection' ];
	$plugin = isset( $settings[ 'plugin' ] ) ? $settings[ 'plugin' ] : ''; 
	
	$fields = array();
	$values = array();

	$id = '';
	
	$odoo_condition = array();
	
	foreach( $settings[ 'field_and_values' ] as $field_name => $field_properties ){
		$v = "";
		$int = 0;
		if( isset( $field_properties[ 'form_field' ] ) ){
			switch( ( $field_properties[ 'form_field' ] ) ){
			case "currency":
			case "number":
			case "decimal":
			case "decimal_long":
			case 'date-5time':
			case 'date-5':
			case 'date':
			case 'datetime':
				$v = '0';
				$int = 1;
			break;
			}
		}
		
		if( isset( $field_properties[ 'value' ] ) ){
			if( $int ){
				if( ! doubleval( $field_properties[ 'value' ] ) ){
					$field_properties[ 'value' ] = $v;
				}
			}
			$v = addslashes( $field_properties[ 'value' ] );
		}
		
		
		switch( $field_name ){
		case "serial_num":
			$v = '0';
		break;
		case "id":
			if( $id ){
				$id .= ',';
			}
			$id .= $v;
		break;
		}
		
		$fields[] = $field_name;
		$values[] = $v;
		
		if( isset( $field_properties[ 'field_identifier' ] ) ){
			if( $field_properties[ 'field_identifier' ] == "id" )continue;
			
			$odoo_condition[] = array( "field" => $field_properties[ 'field_identifier' ], "value" => $v );
		}
	}
	
	//odoo
	if( isset( $settings["data_connection"] ) && $settings["data_connection"] ){
		//print_r( $odoo_condition ); exit;
		
		switch( $settings["data_connection"] ){
		case "odoo":
			if( function_exists( "connect_to_odoo" ) ){
				$odoo = connect_to_odoo();
				$ct = $table_name;
				$user = new $ct( $odoo );
				
				return $user->save_create( $odoo_condition );
			}
		break;
		}
	}
	
	$fields_of_database_table = implode(",", $fields);
	
	$values_to_be_inserted_into_each_field = "'".implode("','", $values)."'";
	
	$insert_statement = "INSERT INTO";
	$insert_keys = "";

	if( isset( $settings[ 'use_replace' ] ) && $settings[ 'use_replace' ] ){
		$insert_statement = "REPLACE INTO";
	}
	
	$query = $insert_statement." `".$database_name."`.`".$table_name."` ($fields_of_database_table) VALUES ($values_to_be_inserted_into_each_field)";
	//echo $query; exit;
	
    //return 1;
	//2. Run Query
	/***********************/
	//1 - SINGLE
	$query_settings = array(
		'database'=>$database_name,
		'connect'=>$database_connection,
		'query'=>$query,
		'query_type'=>'INSERT',
		'set_memcache'=>1,
		'tables'=>array($table_name),
		'skip_manifest' => ( isset($settings['skip_manifest']) && $settings['skip_manifest'] ) ? $settings['skip_manifest'] : 0
	);
	if( $plugin )$query_settings[ 'plugin' ] = $plugin;
	/***********************/
	
	$query_settings[ 'action_to_perform' ] = $action_to_perform;
	$query_settings[ 'table_name' ] = $table_name;
	$query_settings[ 'record_id' ] = $id;
	$query_settings[ 'parent_tb' ] = $parent_tb;

	$result_of_sql_query = execute_sql_query($query_settings);
	
	if(isset($result_of_sql_query) && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
		if( class_exists("cNwp_full_text_search") ){
			$GLOBALS["nwp_full_text_search"][ $table_name ] = 1;
		}
		
		return 1;
	}else{
		return 0;
	}
}

function update( $settings = array() ){
	
	$database_name = $settings[ 'database_name' ]; 
	$plugin = isset( $settings[ 'plugin' ] ) ? $settings[ 'plugin' ] : ''; 
	$action_to_perform = isset( $settings[ 'action_to_perform' ] ) ? $settings[ 'action_to_perform' ] : ''; 
	$parent_tb = isset( $settings[ 'parent_tb' ] ) ? $settings[ 'parent_tb' ] : ''; 
	$table_name = $settings[ 'table_name' ]; 
	$database_connection = $settings[ 'database_connection' ];
	
	$where2 = isset( $settings[ 'where' ] )?$settings[ 'where' ]:'';
	$where = isset( $settings[ 'where_fields' ] )?$settings[ 'where_fields' ]:'';
	$id = isset( $settings[ 'where_values' ] )?$settings[ 'where_values' ]:'';
	
	$condition = "AND";
	if( isset( $settings[ 'condition' ] ) )
		$condition = $settings[ 'condition' ];
	
	$qtype = 'UPDATE';
	if( isset( $settings[ 'delete' ] ) && $settings[ 'delete' ] ){
		$qtype = 'DELETE';
	}
	
	$fields = array();
	$values = array();
	
	$odoo_condition = array();
	
	foreach( $settings[ 'field_and_values' ] as $field_name => $field_properties ){
		if( ! ( ! isset( $field_properties[ 'value' ] ) ) ){
			// $v = addslashes( $field_properties[ 'value' ] );

			$v = addslashes( $field_properties[ 'value' ] );
			$int = 0;
			if( isset( $field_properties[ 'form_field' ] ) ){
				switch( ( $field_properties[ 'form_field' ] ) ){
				case "currency":
				case "number":
				case "decimal":
				case "decimal_long":
				case 'date-5time':
				case 'date-5':
				case 'date':
				case 'datetime':
					$v = '0';
					$int = 1;
				break;
				}
			}
		
			if( $int ){
				if( doubleval( $field_properties[ 'value' ] ) ){
					$v = doubleval( $field_properties[ 'value' ] );
				}
			}
			
			switch( $field_name ){
			case "id":
			case "creation_date":
			case "serial_num":
			case 'created_by':
			// case 'creator_role':
			// case 'created_source':
				continue 2;
			break;
			}
			
			$fields[] = $field_name;
			$values[] = $v;
			
			if( isset( $field_properties[ 'field_identifier' ] ) ){
				$odoo_condition[] = array( "field" => $field_properties[ 'field_identifier' ], "value" => $v );
			}
		}
	}
	// print_r( $fields );
	// print_r( $settings[ 'field_and_values' ] );
	// print_r( $values );exit;
	
	//odoo
	if( isset( $settings["data_connection"] ) && $settings["data_connection"] ){
		//print_r( $odoo_condition ); exit;
		
		switch( $settings["data_connection"] ){
		case "odoo":
			if( function_exists( "connect_to_odoo" ) ){
				$odoo = connect_to_odoo();
				$ct = $table_name;
				$user = new $ct( $odoo );
				
				return $user->save_edit( $id, $odoo_condition );
			}
		break;
		}
	}
	
	$fields_of_database_table = $fields;
	
	$value = $values;
	
	$allow_zeros = array(
		'record_status', 
		'product010', 
		'product012', 
		'product014', 
	);
	
	$retrieve_value2 = "";
	$retrieve_value = "";
	
	$k = 0;
	$deduct = 1;
	foreach($fields_of_database_table as $fields_of_database_tables){
		if( $k < count( $fields_of_database_table ) - 1 ){
			if(in_array($fields_of_database_table[$k], $allow_zeros)){
				if( isset($value[$k]) && $value[$k] !='undefined' ){
					if( $retrieve_value2 )
						$retrieve_value2 .= ", ";
						
					$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."' ";
					
				}
			}else{
				//if( $value[$k] && $value[$k] !='undefined' ){
				if( $value[$k] !='undefined' ){
					if( $retrieve_value2 )
						$retrieve_value2 .= ", ";
						
					$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."' ";
				}
			}
		}else{
			if(in_array($fields_of_database_table[$k], $allow_zeros)){
				if( isset($value[$k]) && $value[$k] !='undefined' ){
					if( $retrieve_value2 )
						$retrieve_value2 .= ", ";
						
					$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."'";
				}
			}else{
				//if( $value[$k] && $value[$k] !='undefined' ){
				if( $value[$k] !='undefined' ){
					if( $retrieve_value2 )
						$retrieve_value2 .= ", ";
						
					$retrieve_value2 .= "`".$fields_of_database_table[$k]."` = '".$value[$k]."'";
				}
			}
		}
		$k++;
	}
	$fields_of_database_table_value = $retrieve_value2;
	
	if( $where2 ){
		$where = $where2;
	}else{
		if(!empty($where)){
			$ids = explode("<>", $id);
			$wherens = explode(",", $where);
			$k = 0;
			foreach($wherens as $value){
				if($k<count($wherens)-1){
					$retrieve_value .= "`".$wherens[$k]."` = '".$ids[$k]."' ".$condition." ";
				}
				else{
					$retrieve_value .= "`".$wherens[$k]."` = '".$ids[$k]."'";
				}
				$k++;
			}
			$where = "WHERE $retrieve_value";
		}
	}
	
	$query = "UPDATE `".$database_name."`.`".$table_name."` SET $fields_of_database_table_value $where";
	
	//if( $table_name == 'sales' ){
	// echo $query;exit;
	// return $query;
	// exit;
	//}
	//2. Run Query
	/***********************/
	//1 - SINGLE
	$query_settings = array(
		'database' => $database_name,
		'connect' => $database_connection,
		'query' => $query,
		'query_type' => $qtype,
		'set_memcache' => 1,
		'tables' => array($table_name),
		'revision_history' => array( 'where' => $where, 'type' => 'front_end', 'comment' => '' ),
		'skip_manifest' => ( isset($settings['skip_manifest']) && $settings['skip_manifest'] ) ? $settings['skip_manifest'] : 0
	);
	if( $plugin )$query_settings[ 'plugin' ] = $plugin;
	
	$query_settings[ 'plugin' ] = $plugin;
	$query_settings[ 'action_to_perform' ] = $action_to_perform;
	$query_settings[ 'table_name' ] = $table_name;
	$query_settings[ 'record_id' ] = $id;
	$query_settings[ 'parent_tb' ] = $parent_tb;

	//13-mar-23: pass old values
	if( isset( $settings["old_values"] ) && $settings["old_values"] ){
		$query_settings[ 'old_values' ] = $settings["old_values"];
	}
	/***********************/
	
	$result_of_sql_query = execute_sql_query($query_settings);
	
	if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
        
		return 1;
	}else{
		return 0;
	}
}

//Return Search Query to be executed when particular records are being searched for in the database
//function search( $database , $table_name , $database_connection, $search_condition, $where, $value, $where_condition = "OR" ){
function search( $settings = array() ){
	
	$database_name = $settings[ 'database_name' ]; 
	$table_name = $settings[ 'table_name' ]; 
	$database_connection = $settings[ 'database_connection' ];
	
	$where = $settings[ 'where_fields' ];
	$id = $settings[ 'where_values' ];
	
	$condition = "AND";
	if( isset( $settings[ 'condition' ] ) )
		$condition = $settings[ 'condition' ];
	
	$where_condition = "AND";
	if( isset( $settings[ 'where_condition' ] ) )
		$where_condition = $settings[ 'where_condition' ];
	
	$fields = array();
	$values = array();
	$search_conditions = array();
	
	foreach( $settings[ 'field_and_values' ] as $field_name => $field_properties ){
		if( isset( $field_properties[ 'value' ] ) && $field_properties[ 'value' ] ){
			$fields[] = $field_name;
			$values[] = $field_properties[ 'value' ];
			
			$search_conditions[] = $field_properties[ 'search_condition' ];
		}
		
	}
	
	$fields_of_database_table = $fields;
	$wherens = $fields_of_database_table;
	
	$value = $values;
	
	$retrieve_value="";
	//$search_conditions = explode(",", $search_condition);
	
	if(!empty($where)){
		$values_to_be_inserted_into_each_field = $value;
		//$values_to_be_inserted_into_each_field = explode("<>", $value);
		
		//$wherens = explode(",", $where);
		$k = 0;
		foreach($wherens as $val){
			if($retrieve_value){
				if(isset($wherens[$k]) && isset($values_to_be_inserted_into_each_field[$k]) && isset($search_conditions[$k]) && $wherens[$k] && $values_to_be_inserted_into_each_field[$k] && $search_conditions[$k]){
					//Check the type of condition
					switch($search_conditions[$k]){
					case ">":
					case "<":
						$retrieve_value .= " ".$where_condition." ( `".$table_name."`.`".$wherens[$k]."`/1 ) ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
					break;
					/*
					case "REGEXP":
					case "regexp":
						$search_conditions[$k] = 'LIKE';
						$retrieve_value .= " ".$where_condition." ( `".$table_name."`.`".$wherens[$k]."` ) ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
					break;
					case "NOT REGEXP":
					case "not regexp":
						$search_conditions[$k] = 'NOT LIKE';
						$retrieve_value .= " ".$where_condition." ( `".$table_name."`.`".$wherens[$k]."` ) ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
					break;
					*/
					default:
						$retrieve_value .= " ".$where_condition." `".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
					break;
					}
				}
			}
			else{
				if(isset($wherens[$k]) && isset($values_to_be_inserted_into_each_field[$k]) && isset($search_conditions[$k]) && $wherens[$k] && $values_to_be_inserted_into_each_field[$k] && $search_conditions[$k]){
					//Check the type of condition
					switch($search_conditions[$k]){
					case ">":
					case "<":
						$retrieve_value = "( `".$table_name."`.`".$wherens[$k]."`/1 ) ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
					break;
					/*
					case "REGEXP":
					case "regexp":
						$search_conditions[$k] = 'LIKE';
						$retrieve_value = "`".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
					break;
					case "NOT REGEXP":
					case "not regexp":
						$search_conditions[$k] = 'NOT LIKE';
						$retrieve_value = "`".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '%".$values_to_be_inserted_into_each_field[$k]."%'";
					break;
					*/
					default:
						$retrieve_value = "`".$table_name."`.`".$wherens[$k]."` ".$search_conditions[$k]." '".$values_to_be_inserted_into_each_field[$k]."'";
					break;
					}
				}
			}
			$k++;
		}
		$where = $retrieve_value;
		
		if($where){
			$query = "SELECT * FROM `".$database_name."`.`".$table_name."` WHERE ".$where;
			
			//Return Search Query that would be used by ajax_server file
			return array("SELECT"," * FROM `".$database_name."`.`".$table_name."`"," WHERE ( ".$where." ) AND `".$table_name."`.`record_status`='1'");
			
			//$x = rand(876566,346353422224445432564398765);
			//write_file('','sql/'.$x.'update.php',$query);
		
			//return mysql_query($query,$database_connection);
		}
	}
	
	//Return Select a Valid Search Criteria
	return 0;
}

//Generates and returns new unique number
function get_new_id(){
	$_SESSION['last_generated_id'] = doubleval( get_cache_for_special_values( array( 'cache_key' => 'last_id' ) ) );

	//Initialize generated id serial number
	if( ! isset($_SESSION['generated_id_serial_number']) ){
		if( class_exists('cNwp_app_core') && isset(cNwp_app_core::$def_cs["isTesting"]) && cNwp_app_core::$def_cs["isTesting"] ){
			$_SESSION['generated_id_serial_number'] = rand( 1, 100 );
		}else{
			$_SESSION['generated_id_serial_number'] = 1;
		}
	}
	
	$timestamp = mktime(date('G'),date('i'),date('s'),date('n'),date('j'),(date("Y")-43));
	$timestamp += 99;
	
	if(isset($_SESSION['last_generated_id'])){
		if($timestamp==$_SESSION['last_generated_id']){
			++$_SESSION['generated_id_serial_number'];
		}else{
			
			if( class_exists('cNwp_app_core') && isset(cNwp_app_core::$def_cs["isTesting"]) && cNwp_app_core::$def_cs["isTesting"] ){
				$_SESSION['generated_id_serial_number'] = rand( 1, 100 );
			}else{
				$_SESSION['generated_id_serial_number'] = 1;
				//$_SESSION['generated_id_serial_number'] = rand( 10 , 79 );
			}
			
		}
	}
	
	//Set Last Generated ID
	$_SESSION['last_generated_id'] = $timestamp;
	
	$settings = array(
		'cache_key' => 'last_id',
		'cache_values' => $timestamp,
	);
	set_cache_for_special_values( $settings );

	$lu_sn = '';
	if( isset( $_SESSION["ucert"]["lu_sn"] ) && $_SESSION["ucert"]["lu_sn"] ){
		$lu_sn = $_SESSION["ucert"]["lu_sn"] . 'u';
	}
	return $lu_sn . $timestamp . $_SESSION['generated_id_serial_number'].rand(); //@steve -> weird problem where audit trail records carry duplicate ids, this began after merge
}

//Create A New Directory
function create_folder( $directory_name_1 , $directory_name_2 , $directory_name_3 ){
	//CREATE ITEM FOLDER
	
	if(!(is_dir( $directory_name_1 . $directory_name_2 . $directory_name_3 ))){
		$oldumask = umask(0);
		
		//mkdir(( $directory_name_1 . $directory_name_2 . $directory_name_3 ), 0777 );
		$perm = 0777;
		if( defined("NWP_DIR_PERM") ){
			$perm = NWP_DIR_PERM;
		}
		mkdir( ( $directory_name_1 . $directory_name_2 . $directory_name_3 ), $perm, true );
		//chmod( $directory_name_1 . $directory_name_2 . $directory_name_3 , 0775 );
		
		umask( $oldumask );
	}
	
	//Folder URL
	return $directory_name_1 . $directory_name_2 . $directory_name_3;
}

//Returns the time after an action occur
function time_passed_since_action_occurred( $seconds , $format = 0 ){
	
	//VALID LISTING
	if($seconds < 0){
		return 0;
	}else{
	
		//Initialization
		$t = $seconds.' seconds left';
		switch($format){
		case 2:
			$t = 'just now';
		break;
		}
		
		$tyears = '';
		
		$one_year = 31536000;
		if( $seconds > $one_year ){
			$new_seconds1 = ( $seconds / $one_year );
			$new_seconds = floor( $new_seconds1 );
			
			$seconds -= ( $one_year * $new_seconds );
			$tyears = $new_seconds . ' yr(s) ';
			
			switch($format){
			case 4:
				return round( $new_seconds1, 1 ) . ' yr(s) ';
			break;
			}
			
		}
			
		$div = array(60,60,24,30,12);
		$comp = array(1,2,2,2,2);
		$lbl = array('seconds left','minutes left','hours left','days left','months left');
		
		$label_for_time_only = array('secs','mins','hrs','days','mths');
		
		$label_for_ago = array('seconds ago','minutes ago','hours ago','days ago','months ago');
		
		//Test if time is in seconds
		$curve = 1;
		for($x=0; $x<sizeof($div); $x++){
			$ti = $seconds / $curve;
			if($ti > $comp[$x]){
				switch($format){
				case 0:
					$t = round($ti,1).'<span class="pleft"> '.$lbl[$x].'</span>';
				break;
				case 1:
					$t = round($ti,1).'<span class="pleft"> '.$lbl[$x].'</span>';
					$t .= '<div class="expire-date">('.date('jS M Y H:m:s',(date('U') + $seconds)).')</div>';
				break;
				case 2:
					$t = round($ti,1).'<span class="pleft"> '.$label_for_ago[$x].'</span>';
				break;
				case 5:
					$t = round($ti,2).' '.$label_for_time_only[$x];
				break;
				case 6:
					$t = round($ti,1).' '.$label_for_time_only[$x];
				break;
				case 3:		//Label for time only
					$t = round($ti,2).'<span class="pleft"> '.$label_for_time_only[$x].'</span>';
				break;
				}
			}
			$curve *= $div[$x];
		}
	}
	return $tyears . $t;
}

//Function to Insert a new record into a database table during a multiple write operation
function insert_new_record_into_table( $function_settings = array() ){
	if(isset($function_settings['table']) && isset($function_settings['database'])  && isset($function_settings['connect']) ){
		//GET FIELD COUNT
		$fields_of_database_table_count = 0;
		$query = "DESCRIBE `".$function_settings['database']."`.`".$function_settings['table']."`";
		$query_settings = array(
			'database'=>$function_settings['database'],
			'connect'=>$function_settings['connect'],
			'query'=>$query,
			'query_type'=>'DESCRIBE',
			'set_memcache'=>1,
			'tables'=>array($function_settings['table']),
		);
		$sql_result = execute_sql_query($query_settings);
		//print_r($sql_result); exit;
		
		$data_to_be_wriiten_to_database = array();
		
		$data_to_update_database = array();
		$update_conditions = array();
		
		$columns_count = 0;
		
		if($sql_result && is_array($sql_result)){
			foreach($sql_result as $sval){
				
				foreach( $function_settings[ 'dataset' ] as $key => $value ){
					if( isset( $value[ $sval[0] ] ) ){
						switch( $sval[0] ){
						case "serial_num":
							if( ! $value[ $sval[0] ] ){
								$value[ $sval[0] ] = '0';
							}
						break;
						default:
							if( ( ! doubleval( $value[ $sval[0] ] ) ) && isset( $sval[ 'Type' ] ) && ( preg_match( '/int/', $sval[ 'Type' ] ) || preg_match( '/decimal/', $sval[ 'Type' ] ) ) ){
								$value[ $sval[0] ] = '0';
							}
						break;
						}
						$data_to_be_wriiten_to_database[$key][] = $value[ $sval[0] ] ? addslashes( $value[ $sval[0] ] ) : '';
					}else{
						$v = '';
						if( isset( $sval[ 'Type' ] ) && ( preg_match( '/int/', $sval[ 'Type' ] ) || preg_match( '/decimal/', $sval[ 'Type' ] ) ) ){
							$v = '0';
						}
						$data_to_be_wriiten_to_database[$key][ $sval[0] ] = $v;
					}
				}
				
				++$columns_count;
			}
			
			$settings = $function_settings;
			
			//update operation instead of insert
			
			if( isset( $function_settings[ 'update_conditions' ] ) ){
				$settings['update_conditions'] = $function_settings[ 'update_conditions' ];
				$settings['update_dataset'] = $function_settings[ 'dataset' ];
				
				return _update_multiple_records( $settings );
			}
			
			$settings['dataset'] = $data_to_be_wriiten_to_database;
			$settings['column_count'] = $columns_count;
			
			return _put_multiple_records( $settings );
			
		}
	}
}

//Function that actually performs the write operation in the database 
function _put( $conn , $database_name , $database_table, $data_to_be_written, $x11 ){
	for($x=0; $x<$x11; $x++){
		$data_to_be_written[$x]= addslashes($data_to_be_written[$x]);
	}
	$_dat = "";
	foreach($data_to_be_written as $key => & $value){
		if($key == 0)$_dat = "'". $value ."'";
		else $_dat = $_dat.","."'". $value ."'";
	}
	
	//$x = rand(876566,346353422224445432564398765);
	//write_file('','sql/'.$x.'put_id.php',"INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_dat )");
	
	$q = "INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_dat )";
	
	$query_settings = array(
		'database'=>$database_name,
		'connect'=>$conn,
		'query'=>$q,
		'query_type'=>'INSERT',
		'set_memcache'=>1,
		'tables'=>array($database_table),
	);
	/***********************/

	$result_of_sql_query = execute_sql_query($query_settings);
	
	if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
		return 1; //"Database selected successfully<br>";
	}else{
		return 0;
	}
	
}
function array_depth( $arr = array() ){
	if( is_array( reset( $arr ) ) ) return array_depth( reset( $arr ) ) + 1;
	else return 1;
}									

//Function to insert multiple records to database
function _put_multiple_records( $function_settings = array() ){
	
	if(isset( $function_settings['table'] ) && isset($function_settings['database'])  && isset($function_settings['connect']) && isset( $function_settings['dataset'] ) && isset( $function_settings['column_count'] ) ){
	
		$table_records_set = $function_settings['dataset'];
		$_dat = '';
		
		$qq = "";
		$count = 0;
		$fields = "";
		
		foreach($table_records_set as $key => $data_to_be_written){
			
			if( ! $fields ){
				$fields = "`". implode("`, `", array_keys( $data_to_be_written ) ) . "`";
			}
			$_record_to_be_inserted = "'". implode("', '", $data_to_be_written ) . "'";
			
			/*
			$_record_to_be_inserted = '';
			for($x=0; $x<count($data_to_be_written); $x++){
				
				if($_record_to_be_inserted)$_record_to_be_inserted .= ","."'". $data_to_be_written[$x] ."'";
				else $_record_to_be_inserted = "'". $data_to_be_written[$x] ."'";
				
			}
			 */
			 
			//Enable When on Mysql
			if($_dat)$_dat .= ", (".$_record_to_be_inserted.")";
			else $_dat = " (".$_record_to_be_inserted.")";
			
			
			/*if( $count > 100 ){
				$qq .= "INSERT INTO `" . $function_settings['database'] . "`.`wag_member2` VALUES $_dat; ";
				$_dat = "";
				$count = 0;
			}
			++$count;*/
			
		}
		/*if( ! ( $count > 100 ) ){
			$qq .= "INSERT INTO `" . $function_settings['database'] . "`.`wag_member2` VALUES $_dat; ";
		}*/

		$insert_statement = "INSERT INTO";
		$insert_keys = "";

		if( isset( $function_settings[ 'use_replace' ] ) && $function_settings[ 'use_replace' ] ){
			$insert_statement = "REPLACE INTO";
		}
		
		//$x = rand(876566,346353422224445432564398765);
		//write_file('','sql/'.$x.'put_id.php',"INSERT INTO `".$database_name."`.`".$database_table."` VALUES ( $_dat )");
		//Enable for mysql
		$q = $insert_statement . " `" . $function_settings['database'] . "`.`" . $function_settings['table'] . "` VALUES $_dat";
		// file_put_contents("additional_wag_member.sql", $qq );
		// echo $q . ' ----- ';
		// exit;
		
		$query_settings = array(
			'database' => $function_settings['database'],
			'connect' => $function_settings['connect'],
			'query' => $q,
			'query_type' => 'INSERT',
			//'query_type' => 'EXECUTE_SYS',
			'set_memcache' => 1,
			'tables' => array( $function_settings['table'] ),
			'skip_manifest' => ( isset($function_settings['skip_manifest']) && $function_settings['skip_manifest'] ) ? $function_settings['skip_manifest'] : 0																							 
		);
		
		$result_of_sql_query = execute_sql_query($query_settings);
		
		
		if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
			return 1; //"Database selected successfully<br>";
		}else{
			return 0;
		}
	}
}

 function mysqlBulk(&$data, $table, $method = 'transaction', $options = array()) {
		
	  // Default options
	  if (!isset($options['query_handler'])) {
		  $options['query_handler'] = 'mysqli_query';
		  //$options['query_handler'] = 'mysql_query';
	  }
	  if (!isset($options['trigger_errors'])) {
		  $options['trigger_errors'] = true;
	  }
	  if (!isset($options['trigger_notices'])) {
		  $options['trigger_notices'] = true;
	  }
	  if (!isset($options['eat_away'])) {
		  $options['eat_away'] = false;
	  }
	  if (!isset($options['database'])) {
		  $options['database'] = false;
		  trigger_error('Database name is not specified',
				  E_USER_ERROR);
				  return false;
	  }
	  
	  if (!isset($options['in_file'])) {
		  // AppArmor may prevent MySQL to read this file.
		  // Remember to check /etc/apparmor.d/usr.sbin.mysqld
		  $options['in_file'] = $_SERVER['DOCUMENT_ROOT'] . MYSQL_BULK_LOAD_FILE_PATH;
	  }
	  $options['in_file'] = _wp_normalize_path( $options['in_file'] );
	  
	  if (!isset($options['link_identifier'])) {
		  $options['link_identifier'] = null;
	  }

	  // Make options local
	  extract($options);

	  // Validation
	  $table_name = '';
	  if (!is_array($data)) {
		  if ($trigger_errors) {
			  trigger_error('First argument "queries" must be an array',
				  E_USER_ERROR);
		  }
		  return false;
	  }
	  if (empty($table)) {
		  if ($trigger_errors) {
			  trigger_error('No insert table specified',
				  E_USER_ERROR);
		  }
		  return false;
	  }else{
		$table_name = $table;
		$table = '`'.$options['database'].'`.`'.$table.'`';
	  }
	  if (count($data) > 10000) {
		  if ($trigger_notices) {
			  trigger_error('It\'s recommended to use <= 10000 queries/bulk',
				  E_USER_NOTICE);
		  }
	  }
	  if (empty($data)) {
		  return 0;
	  }

	if( $table_name && class_exists("cNwp_full_text_search") ){
		$GLOBALS["nwp_full_text_search"][ $table_name ] = 1;
	}
	
	  if (!function_exists('__exe')) {
		  function __exe ($sql, $query_handler, $trigger_errors, $link_identifier = null) {
			
			mysqli_options($link_identifier,MYSQLI_OPT_LOCAL_INFILE, true );
			//echo $sql; exit;
			$query_settings = array(
				'database' => '',
				'connect' => $link_identifier,
				'query' => $sql,
				'query_type' => 'EXECUTE',
				'set_memcache' => 0,
				'tables' => array(),
			);
			execute_sql_query( $query_settings );
			mysqli_options($link_identifier,MYSQLI_OPT_LOCAL_INFILE, false );
			  /*
			  if ($link_identifier === null) {
				  $x = call_user_func($query_handler, $sql);
			  } else {
				  $x = call_user_func($query_handler, $sql, $link_identifier);
			  }
			  if (!$x) {
				  if ($trigger_errors) {
					  trigger_error(sprintf(
						  'Query failed. %s [sql: %s]',
						  mysql_error($link_identifier),
						  $sql
					  ), E_USER_ERROR);
					  return false;
				  }
			  }
			  */
			  return true;
		  }
	  }

	  if (!function_exists('__sql2array')) {
		  function __sql2array($sql, $trigger_errors) {
			  if (substr(strtoupper(trim($sql)), 0, 6) !== 'INSERT') {
				  if ($trigger_errors) {
					  trigger_error('Magic sql2array conversion '.
						  'only works for inserts',
						  E_USER_ERROR);
				  }
				  return false;
			  }

			  $parts   = preg_split("/[,\(\)] ?(?=([^'|^\\\']*['|\\\']" .
									"[^'|^\\\']*['|\\\'])*[^'|^\\\']" .
									"*[^'|^\\\']$)/", $sql);
			  $process = 'keys';
			  $dat     = array();

			  foreach ($parts as $k=>$part) {
				  $tpart = strtoupper(trim($part));
				  if (substr($tpart, 0, 6) === 'INSERT') {
					  continue;
				  } else if (substr($tpart, 0, 6) === 'VALUES') {
					  $process = 'values';
					  continue;
				  } else if (substr($tpart, 0, 1) === ';') {
					  continue;
				  }

				  if (!isset($data[$process])) $data[$process] = array();
				  $data[$process][] = $part;
			  }

			  return array_combine($data['keys'], $data['values']);
		  }
	  }

	  // Start timer
	  $start = microtime(true);
	  $count = count($data);

	  // Choose bulk method
	  switch ($method) {
		  case 'loaddata':
		  case 'loaddata_unsafe':
		  case 'loadsql_unsafe':
			  // Inserts data only
			  // Use array instead of queries

			  $buf    = '';
			  foreach($data as $i=>$row) {
				  if ($method === 'loadsql_unsafe') {
					  $row = __sql2array($row, $trigger_errors);
				  }
				  $buf .= implode('::::,', $row)."^^^^";
			  }
				
			  $fields = implode(', ', array_keys($row));

			  if (!@file_put_contents($in_file, $buf)) {
			  	if( $trigger_errors ){
				  trigger_error('Cant write to buffer file: "'.$in_file.'"', E_USER_ERROR);
			  	}
			  return false;
			  }
			  // print_r( $in_file );exit;

			  if ($method === 'loaddata_unsafe') {
				  if (!__exe("SET UNIQUE_CHECKS=0", $query_handler, $trigger_errors, $link_identifier)) return false;
				  if (!__exe("set foreign_key_checks=0", $query_handler, $trigger_errors, $link_identifier)) return false;
				  // Only works for SUPER users:
				  #if (!__exe("set sql_log_bin=0", $query_handler, $trigger_error)) return false;
				  if (!__exe("set unique_checks=0", $query_handler, $trigger_errors, $link_identifier)) return false;
			  }
				
				$et = " LOCAL ";
				if( defined("PLATFORM") ){
					switch( PLATFORM ){
					case "linux":
						$et = "";
					break;
					}
				}
			  if (!__exe("
				 LOAD DATA ".$et." INFILE '{$in_file}'
				 INTO TABLE {$table}
				 FIELDS TERMINATED BY '::::,'
				 LINES TERMINATED BY '^^^^'
				 ({$fields})
			 ", $query_handler, $trigger_errors, $link_identifier)) return false;

			  break;
	  }

	  // Stop timer
	  $duration = microtime(true) - $start;
	  $qps      = round ($count / $duration, 2);

	  if ($eat_away) {
		$data = array();
		//unlink( $in_file );
	  }

	  //@unlink($options['in_file']);

	  // Return queries per second
	  return $qps;
}

function _put_multiple_recordsBULK( $function_settings = array() ){
	
	if(isset( $function_settings['table'] ) && isset($function_settings['database'])  && isset($function_settings['connect']) && isset( $function_settings['dataset'] ) && isset( $function_settings['column_count'] ) ){
        
		$table_records_set = $function_settings['dataset'];
		$_dat = '';
		
        $i = 0;
        $query = "";
        
        $chunked_array = array_chunk( $table_records_set , 1000 );
        foreach( $chunked_array as $chunk ){
            if (false === ($qps = mysqlBulk($chunk, $function_settings['table'], 'loaddata', array(
            'query_handler' => 'mysql_query',
            'link_identifier' => $function_settings['connect'],
            'database' => $function_settings['database'],
            )))) {
                trigger_error('mysqlBulk failed!', E_USER_ERROR);
            } else {
                ++$i;
            }
        }
        
        //update cache
        
        $query_settings = array(
            'database' => $function_settings['database'],
            'connect' => 'connect',
            'query' => 'clear',
            'query_type' => 'CLEARCACHE',
            'set_memcache' => 1,
            'tables' => array( $function_settings['table'] ),
        );
        execute_sql_query($query_settings);
        
		if( class_exists("cNwp_full_text_search") ){
			$GLOBALS["nwp_full_text_search"][ $function_settings['table'] ] = 1;
		}
        return 1;
        /*
		foreach($table_records_set as $key => $data_to_be_written){
			$_record_to_be_inserted = '';
			
			for($x=0; $x<count($data_to_be_written); $x++){
				$data_to_be_written[$x] = addslashes($data_to_be_written[$x]);
				
				if($_record_to_be_inserted)$_record_to_be_inserted .= ","."'". $data_to_be_written[$x] ."'";
				else $_record_to_be_inserted = "'". $data_to_be_written[$x] ."'";
				
			}
			
			//Enable When on Mysql
			if($_dat)$_dat .= ", (".$_record_to_be_inserted.")";
			else $_dat = " (".$_record_to_be_inserted.")";
			
            ++$i;
            if( ! ( $i % 1000 ) ){
                
                $_dat = '';
                $query = "";
            }
            
		}
		
        if( $_dat ){
            $query = "INSERT INTO `" . $function_settings['database'] . "`.`" . $function_settings['table'] . "` VALUES $_dat ; ";
         }
        if( $query ){
            $query_settings = array(
                'database' => $function_settings['database'],
                'connect' => $function_settings['connect'],
                'query' => $query,
                'query_type' => 'INSERT',
                'set_memcache' => 1,
                'tables' => array( $function_settings['table'] ),
            );
        }
        
        $result_of_sql_query = execute_sql_query($query_settings);
        if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
			return 1; //"Database selected successfully<br>";
		}else{
			return 0;
		}
        */
		
         
	}
}

//Function to insert multiple records to database
function _update_multiple_records( $function_settings = array() ){
	
	if(isset( $function_settings['table'] ) && isset($function_settings['database'])  && isset($function_settings['connect']) && isset( $function_settings['dataset'] ) && isset( $function_settings['update_conditions'] ) ){
	
		$database_name = $function_settings[ 'database' ]; 
		$database_table = $function_settings[ 'table' ]; 
		$database_connection = $function_settings[ 'connect' ];
	
		$table_records_set = $function_settings[ 'update_dataset' ];
		
		$update_conditions = $function_settings[ 'update_conditions' ];
		
		$_dat = '';
		
		$all_update_queries = "";
		
		foreach($table_records_set as $index => $fields_to_be_written){
			$_record_to_be_inserted = "";
			
			foreach( $fields_to_be_written as $key => $value ){
				switch( $key ){
				case "id":
				case "created_role":
				case "created_by":
				case "creation_date":
				case "ip_address":
				case "record_status":
				break;
				default:
					if( $_record_to_be_inserted )
						$_record_to_be_inserted .= ", `".$database_table."`.`".$key."`='".$value."'";
					else
						$_record_to_be_inserted = "UPDATE `".$database_name."`.`".$database_table."` SET `".$database_table."`.`".$key."`='".$value."'";
				break;
				}
			}

			if( $_record_to_be_inserted ){
				//where condition
				if( isset( $update_conditions[ $index ]['where_fields'] ) && isset( $update_conditions[ $index ]['where_values'] ) ){
					
					$_record_to_be_inserted .= " WHERE `".$database_table."`.`".$update_conditions[ $index ]['where_fields']."` = '".$update_conditions[ $index ]['where_values']."' ";
					//echo $_record_to_be_inserted;
					//exit;
					$query_settings = array(
						'database' => $function_settings['database'],
						'connect' => $function_settings['connect'],
						'query' => $_record_to_be_inserted,
						'query_type' => 'UPDATE',
						'u_field' => $update_conditions[ $index ]['where_fields'],
						'u_value' => $update_conditions[ $index ]['where_values'],
						'set_memcache' => 1,
						'tables' => array( $function_settings['table'] ),
						'skip_manifest' => ( isset($function_settings['skip_manifest']) && $function_settings['skip_manifest'] ) ? $function_settings['skip_manifest'] : 0
					);
					
					$result_of_sql_query = execute_sql_query($query_settings);
					
					/*	
					if( $all_update_queries )
						$all_update_queries .= "; ".$_record_to_be_inserted;
					else
						$all_update_queries = $_record_to_be_inserted;
					*/
				}
			}
		}
		
		//echo $all_update_queries;
		//exit;
		
		return 1;
		/*
		$query_settings = array(
			'database' => $function_settings['database'],
			'connect' => $function_settings['connect'],
			'query' => $all_update_queries,
			'query_type' => 'UPDATE',
			'set_memcache' => 1,
			'tables' => array( $function_settings['table'] ),
		);
		
		$result_of_sql_query = execute_sql_query($query_settings);
		
		if($result_of_sql_query && is_array($result_of_sql_query) && isset($result_of_sql_query['success']) && $result_of_sql_query['success']==1){
			return 1; //"Database selected successfully<br>";
		}else{
			return 0;
		}
		*/
	}
}

function reglob( $pagepointer ){
	$dir = $pagepointer . "classes/";
	if( file_exists( $pagepointer . "license.hyella" ) )unlink( $pagepointer . "license.hyella" );
	return 1;
	foreach( glob( $dir . "*.php" ) as $filename ) {
		if ( is_file( $filename ) ) {
			$table_name = basename($filename , ".php");
			switch( $table_name ){
			case "cAudit":
			case "cForms":
			case "cError":
			case "cProcess_handler":
			case "cUploader":
			case "cAuthentication":
			case "cModules":
			case "cUsers_role":
			case "cUsers":
			case "cFunctions":
			case "cMypdf":
			case "cMyexcel":
			case "cSearch":
			case "cColumn_toggle":
			case "cNotifications":
			case "cAppsettings":
			break;
			default:
				unlink( $filename );
			break;
			}
		}
	}
}

//Initialize and Reuse a PHP Class to perform an action
function reuse_class( $settings = array() ){
	
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

function check_second_level_authentication( $opts = array() ){
	if( defined( "NWP_2ND_LEVEL_AUTH" ) && NWP_2ND_LEVEL_AUTH ){
		if( isset( $opts["action"] ) && isset( $opts["class"] ) ){
			if( isset( $GLOBALS["nwp_2nd_level_auth"] ) && isset( $GLOBALS["nwp_2nd_level_auth"][ $opts["class"] ][ $opts["action"] ] ) ){
				return array( "action" => "authentication", "todo" => "second_level_auth_form" );
			}
		}
	}
}

function log_m( $settings = array() ){
	$a = array( "status" => "new-status", "redirect_url" => "html-files/expired-message.php" );
	
	if( isset( $settings["missing_file"] ) && $settings["missing_file"] ){
		if( function_exists( "get_hyella_development_mode" ) && get_hyella_development_mode() ){
			$a = array( "status" => "new-status", "html_replacement_selector" => "body" , "html_replacement" => "<h1>Missing Class: " . $settings["missing_file"] . "</h1>" );
		}
	}
	
	echo json_encode( $a );
	exit;
}

//Check if Current User Permitted to perform certain actions
function permission( $user_cert , $action , $classname , $database_connection , $database_name, $settings = array() ){
	return 1;	//temp
	if( get_disable_access_control_settings() ){
		return 1;
	}
	
	if( ! ( isset( $settings["force"] ) && $settings["force"] ) ){
		return 1;
	}
	
	$role = "";
	if( isset( $user_cert["privilege"] ) && $user_cert["privilege"] ){
		$role = $user_cert["privilege"];
	}
	
	//super user
	if( $role == "1300130013" )return 1;
	
	$cache_key = "functions";
	$settings = array(
		'cache_key' => $cache_key."-".$action."-".$classname,
		'directory_name' => $cache_key,
		'permanent' => true,
	);
	$result = get_cache_for_special_values( $settings );
	
	
	if( isset( $result["id"] ) && $result["id"] ){
		
		$access = get_accessed_functions();
		if( ! is_array( $access ) && $access == 1 ){
			$super = 1;
		}
		
		if( isset( $access[ $result["id"] ] ) ){
			return 1;
		}
		//function is defined - thus access is denied by default
		return 0;
	}
	//function is not defined - thus accessible to all user groups
	return 1;
}

function log_in_console( $comment, $name = '' ){
	if( is_array( $comment ) ){
		$comment = json_encode( $comment );
	}
	if( ! $name )$name = "console";
	
	auditor( "" , "console", $name, $comment );
}

//Record an action in the audit trail
function auditor( $class_settings = "", $user_action = '' , $table = '' , $params = array(), $cls = array() ){
	
	if( ! is_array( $params ) ){
		$params = array( "comment" => $params );
	}
	
	switch( $user_action ){
	case "read":
		if( defined("HYELLA_EXCLUDE_READ_ACTIONS_FROM_AUDIT_TRAIL") && HYELLA_EXCLUDE_READ_ACTIONS_FROM_AUDIT_TRAIL ){
			return 0;
		}
	break;
	case "page_view":
		if( defined("HYELLA_EXCLUDE_PAGE_VIEWS_FROM_AUDIT_TRAIL") && HYELLA_EXCLUDE_PAGE_VIEWS_FROM_AUDIT_TRAIL ){
			return 0;
		}
	break;
	case "login":
		if( defined("HYELLA_EXCLUDE_LOGIN_ATTEMPT_FROM_AUDIT_TRAIL") && HYELLA_EXCLUDE_LOGIN_ATTEMPT_FROM_AUDIT_TRAIL ){
			return 0;
		}
	break;
	}
	
	if( class_exists( "cAudit" ) ){
		$module = new cAudit();
		// Support for NWP LOGGING AUDIT TRAIL

		if( class_exists( 'cNwp_app_core' ) && cNwp_app_core::$def_cs ){
			$module->class_settings = cNwp_app_core::$def_cs;
		}else{
			if( $cls ){
				$module->class_settings = $cls;
			}
		}

		$module->user_action = strtolower( $user_action );
		$module->table = $table;
		//$module->comment = $comment;
		$module->parameters = $params;
		
		$module->class_settings['action_to_perform'] = 'record';
		
		return $module->audit();
	}
}

//CONVERT KEY VALUE PAIR OF AN ARRAY INTO TWO ARRAYS FOR DROP DOWN LIST BOX
function convert_array_to_key_value_pair_for_selectbox( $function_name = '' ){
	$key = array();
	$val = array();
	$array = array();

	if( is_array( $function_name ) && ! empty( $function_name ) ){
		$array = $function_name;
	}else if( ! is_array( $function_name ) && function_exists( $function_name ) ){
		$array = $function_name();
	}
		
	if( ! empty( $array ) ){
		foreach($array as $array_key => $array_value){
			$key[] = $array_key;
			$val[] = $array_value;
		}
	}
	
	return array($key,$val);
}

function rebuild(){
	//return 1;
	$do = 0;
	$p = get_l_key();
	if( ! $p ){
		$pr = get_project_data();
		if( isset( $_POST["filter"] ) && $_POST["filter"] == "app" ){
			$a = array( "status" => "new-status", "redirect_url" => $pr["domain_name"] . 'html-files/expired-message.php' );
		}else{
			header( 'Location: ' . $pr["domain_name"] . 'html-files/expired-message.php' );
		}
		exit;
	}
	
	if( strlen($p) != 32 ){
		$do = 1;
	}
	
	$settings = array(
		'cache_key' => $p."-last",
		'permanent' => true,
	);
	$l = get_cache_for_special_values( $settings );
	
	$dl = date("U");
	if( $dl > $l ){
		$settings['cache_values'] = $dl;
		set_cache_for_special_values( $settings );
		
		$value = get_l_key1();
		$d = doubleval( clean_numbers( $p ) ) / $value;
		$_SESSION["release"] = $d;
		
		if( date("U") > $d )$do = 1;
		
	}else{
		$do = 1;
		//$pr = get_project_data();
		//header( 'Location: ' . $pr["domain_name"] . 'html-files/expired-message.php?time=unset' );
		//exit;
	}
	
	if( $do ){
		//reglob( "" );
	}
}

//Returns the properties of a database field name to be used in form generation
function get_form_field_type( $fields_of_database_table ){
	$fields_of_database_tables = array(
		'form_type' => 0,
		'field_id' => '',
		'view' => 0,
	);
	
	//1. Get Form Field Value
	$data = explode('_DT',$fields_of_database_table);
	
	//2. Return Form Field Value
	if(isset($data[0])){
		$fields_of_database_tables['field_id'] = $data[0];
	}
	if(isset($data[1])){
		$fields_of_database_tables['form_type'] = $data[1];
	}
	if(isset($data[3])){
		$fields_of_database_tables['view'] = $data[3];
	}
	
	return $fields_of_database_tables;
}

//Transform input data from search field of datatables to human readable format
function transform_search_value( $search_value, $form_field_dt, $table ){
	/*
	 *Transform input data from search field of datatables to understandable database format
	*/
	$search_value = trim(strtolower($search_value));
	
	$transformed_dt = null;
	if($form_field_dt['form_field']){
		//Check data type
		switch($form_field_dt['form_field']){
		case "select":
			//Get Corresponding Array
			//Get options function name
			if( isset( $form_field_dt['form_field_options'] ) ){					
				$option_function_name = $form_field_dt['form_field_options'];
				
				if(function_exists($option_function_name)){
					
					$options = $option_function_name();
						
					if(isset($options) && is_array($options)){
						foreach($options as $k_opt => $v_opt){
							if($search_value==trim(strtolower($v_opt))){
								$transformed_dt = $k_opt;
							}
						}
					}
				}
			}
		break;
		case "date":
			//Transform date
			$search_value = explode('-',$search_value);
			
			if(is_array($search_value) && count($search_value)==3){
				$day = 1;
				$month = 'jan';
				$year = date("Y");
				
				if(isset($search_value[0])){
					$day = $search_value[0];
				}else{
					//Set Session Variable for Timeline of All Day of Month
					
				}
				
				if(isset($search_value[1])){
					$month = $search_value[1];
				}else{
					//Set Session Variable for Timeline of All Months of Year
					
				}
				
				if(isset($search_value[2])){
					$year = $search_value[2];
				}else{
					//Set Session Variable for Timeline of All Years
					
				}
				$mon = array(
					'jan' => 1,
					'feb' => 2,
					'mar' => 3,
					'apr' => 4,
					'may' => 5,
					'jun' => 6,
					'jul' => 7,
					'aug' => 8,
					'sep' => 9,
					'oct' => 10,
					'nov' => 11,
					'dec' => 12,
				);
				
				//COMPILE DATE
				if(isset($mon[$month]))
					$transformed_dt = mktime(0,0,0,$mon[$month],$day,$year);
			}
		break;
		}
	}
	return $transformed_dt;
}

//Execute a SQL Query
function execute_sql_query( $settings = array() ){
	// print_r( $settings );
	if( class_exists( 'cNwp_orm' ) && isset( $settings[ 'query' ] ) && is_array( $settings[ 'query' ] ) ){
		$o = new cNwp_orm;
		if( ! ( isset( $settings[ 'db_mode' ] ) && $settings[ 'db_mode' ] ) ){
			$settings[ 'db_mode' ] = DB_MODE;
		}
		// $o->query_settings = $settings;
		return $o->_execute_query( $settings );
	}
	$cache_table_key = 'database_tables';
	$skip_manifest = ( isset($settings['skip_manifest']) && $settings['skip_manifest'] ) ? $settings['skip_manifest'] : 0;
	
	//13-mar-23
	if( isset( $GLOBALS["nwp_skip_manifest"] ) && $GLOBALS["nwp_skip_manifest"] == 'bg_process' ){
		$skip_manifest = 1;
	}
	
	$manifest = isset( $settings['manifest'] )?$settings['manifest']:'update-manifest';
	$cu_id = '';
	if( isset( $_SESSION['key'] ) && $_SESSION['key'] ){
		$ckey = md5( 'ucert' . $_SESSION['key'] );
		if( isset( $_SESSION[ $ckey ] ) ) {
			$cu = $_SESSION[ $ckey ];
			$cu_id = isset( $cu["id"] )?$cu["id"]:'';
		}
	}
	$gum = array( "user" => $cu_id );
	if( isset( $settings['multiple_manifest'] ) && is_array( $settings['multiple_manifest'] ) && ! empty( $settings['multiple_manifest'] ) ){
		$gum["multiple_manifest"] = $settings['multiple_manifest'];
	}else if( isset( $GLOBALS["nwp_multiple_manifest"] ) && is_array( $GLOBALS["nwp_multiple_manifest"] ) && ! empty( $GLOBALS["nwp_multiple_manifest"] ) ){
		$gum["multiple_manifest"] = $GLOBALS['nwp_multiple_manifest'];
	}
	
	//Uncomment to enable caching of queries result
	if( isset( $settings['set_memcache'] ) && $settings['set_memcache'] ){
		if( ( isset($GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
			$settings['set_memcache'] = $GLOBALS['app_memcache'];
		}else{
			unset( $settings['set_memcache'] );
		}
	}
	
	if( ! isset( $settings['table'] ) ){
		if( isset( $settings['tables'] ) ){
			$settings['table'] = implode(", ", $settings['tables'] );
		}
	}
	
	$index_field = '';
	if( isset( $settings['index_field'] ) && $settings['index_field'] ){
		$index_field = $settings['index_field'];
	}
	
	$index_type = '';
	if( isset( $settings['index_type'] ) && $settings['index_type'] ){
		$index_type = $settings['index_type'];
	}
	
	//10 Hours Expiry Time
	$cache_time = 3600*10;
	
	//Universal SQl QUERY FUNCTION
	if(isset($settings['database']) && isset($settings['connect']) && isset($settings['query']) && isset($settings['query_type'])){
		//Check Query Type
		switch($settings['query_type']){
		case "SELECT":
			
			//Get Query Key
			$cache_key = md5($settings['query']);
			
			//Check if Memcache is turned on
			if(isset($settings['set_memcache']) && $settings['set_memcache'] ){
			// if(isset($settings['set_memcache']) && $settings['set_memcache'] ){
				
				//Check if Cache Exists
				
				//$settings['set_memcache']->delete($cache_key);
				
				$array = $settings['set_memcache']->get($cache_key);
				if(isset($array) && is_array($array)){
					//Return Cached Data
					//$array[0]['type'] = 'memcache';
					//echo 'memcache';
					return $array;
				}
			}
			
			$callStartTime = microtime(true);
			
			//odoo integration
			if( isset( $settings["data_connection"] ) && $settings["data_connection"] ){
				switch( $settings["data_connection"] ){
				case "odoo":
					if( function_exists( "connect_to_odoo" ) ){
						if( isset( $settings["where_table"] ) && $settings["where_table"] ){
							if( isset( $settings["where_fields"] ) && is_array( $settings["where_fields"] ) && !empty( $settings["where_fields"] ) ){
								$odoo = connect_to_odoo();
								$ct = $settings["where_table"];
								$user = new $ct( $odoo );
								
								return $user->search( $settings["where_fields"] );
							}
						}
					}
				break;
				}
			}
			
			$audit_params = array( "query" => $settings['query'] );
			
			//Clean SQL QUERY
			try{
				$result_of_sql_query = mysqli_query( $settings['connect'], $settings['query'] );
			}catch( Exception $e ){
				$result_of_sql_query = 0;
			}
			
			//echo $settings['query'] . "\n\n";
			if ( ! $result_of_sql_query ) {
				if( ! ( isset( $settings['skip_log'] ) && $settings['skip_log'] ) ){
					
					switch( DB_MODE ){
					case 'mssql':
						$ex = sqlsrv_errors();
						$e = isset( $ex[0]["message"] )?$ex[0]["message"]:'';
					break;
					default:
						$e = mysqli_error( $settings['connect'] );
					break;
					}
					
					$callEndTime = microtime(true);
					$audit_params["comment"] = $e;
					$audit_params["duration"] = $callEndTime - $callStartTime;
					$audit_params["trace"] = array_column( debug_backtrace(), 'line', 'file' );
					auditor( "", "sql_error", $settings['table'] , $audit_params, [
						'database_name' => $settings['database'],
						'database_connection' => $settings['connect']
						] 
					);
					
					//trigger_error('Could not execute statement: '. $e['message'], E_USER_NOTICE);
					trigger_error('Could not execute statement: '. $audit_params["comment"] .' Q:'. $settings['query'], E_USER_ERROR );
					
					return $audit_params["comment"];
					
				}
			}

			//$result_of_sql_query = mysql_query($settings['query'],$settings['connect']);
			if($result_of_sql_query){
				$size = mysqli_num_rows( $result_of_sql_query );
				
				$audit_params["count"] = $size;
			
				//$startMemory = memory_get_usage();
				//echo $startMemory, ' bytes--++';
				
				//$array = array();
				if( $index_field ){
					$array = array();
				}else{
					$array = new SplFixedArray( $size );
				}
				
				//, OCI_ASSOC+OCI_RETURN_NULLS
				//OCI_NUM
				//while (($row = mysql_fetch_array( $result_of_sql_query )) != false) {
				$counter = 0;
				
                while( ($row = mysqli_fetch_assoc( $result_of_sql_query )) != false ){
					//$array[] = $row;
					if( isset( $row[ $index_field ] ) ){
						
						switch( $index_type ){
						case "multiple":
							$array[ $row[ $index_field ] ][] = $row;
						break;
						default:
							$array[ $row[ $index_field ] ] = $row;
						break;
						}
					}else{
						$array[ $counter ] = $row;
					}
					++$counter;
				}
				
				//echo memory_get_usage() - $startMemory, ' bytes'; exit;
				
				//Check if Memcache is turned on
				if(isset($settings['set_memcache']) && $settings['set_memcache']){
					
					if(isset( $settings['set_memcache_time'] ) && $settings['set_memcache_time'] ){
						$cache_time = $settings['set_memcache_time'];
					}
					
					//Cache Query Result
					$settings['set_memcache']->set($cache_key,$array,$cache_time);
					
				
					//Update Cached Tables Array
					if(isset($settings['tables']) && is_array($settings['tables'])){
						//Get Array of All Cached Tables Keys
						$table_keys = $settings['set_memcache']->get($cache_table_key);
						
						if(is_array($table_keys)){
							foreach($settings['tables'] as $table){
								$table_keys[ strtolower($table) ][ $cache_key ] = true;
							}
						}else{
							foreach($settings['tables'] as $table){
								$table_keys[ strtolower($table) ][ $cache_key ] = true;
							}
						}
						
						//Update Table Keys
						$settings['set_memcache']->set($cache_table_key,$table_keys,$cache_time);	//Set for two hours
					}
					
					//$settings['set_memcache']->delete($cache_table_key);	//Set for two hours
					//$settings['set_memcache']->delete($cache_key);	//Set for two hours
				}
				//echo 'database';
				//$array[0]['type'] = 'database';
				
				if( isset($settings['skip_log']) && $settings['skip_log'] ){
				}else{
					$callEndTime = microtime(true);
					$audit_params["duration"] = $callEndTime - $callStartTime;

					$acl = '';
					$atd = '';
					if( isset( $settings[ 'parent_tb' ] ) && $settings[ 'parent_tb' ] ){
						$acl = $settings[ 'parent_tb' ];
					}else if( isset( $settings[ 'table_name' ] ) && $settings[ 'table_name' ] ){
						$acl = $settings[ 'table_name' ];
					}else if( isset( $settings[ 'tables' ][0] ) && $settings[ 'tables' ][0] ){
						$acl = $settings[ 'tables' ][0];
					}
					if( isset( $settings[ 'action_to_perform' ] ) && $settings[ 'action_to_perform' ] ){
						$atd = $settings[ 'action_to_perform' ];
					}
					if( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
						$audit_params[ 'class' ] = $settings[ 'plugin' ];
						$audit_params[ 'nwp_action' ] = $acl;
						$audit_params[ 'nwp_todo' ] = $atd;
					}else{
						$audit_params[ 'class' ] = $acl;
						$audit_params[ 'action' ] = $atd;
					}

					if( isset( $settings[ 'record_id' ] ) ){
						$audit_params[ 'record_id' ] = $settings[ 'record_id' ];
					}
					if( isset( $settings[ 'table_name' ] ) ){
						$audit_params[ 'record_table' ] = $settings[ 'table_name' ];
					}elseif( isset( $settings[ 'tables' ][0] ) ){
						$audit_params[ 'record_table' ] = $settings[ 'tables' ][0];
					}
					if( isset( $settings[ 'record_plugin' ] ) ){
						$audit_params[ 'record_plugin' ] = $settings[ 'record_plugin' ];
					}elseif( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
						$audit_params[ 'record_plugin' ] = $settings[ 'plugin' ];
					}

					auditor( "", "read", $settings['table'] , $audit_params, [
						'database_name' => $settings['database'],
						'database_connection' => $settings['connect']
						] 
					 );
				}
				
				return (array) $array;
			}
		break;
		case "EXECUTE_MULTI":
		case "EXECUTE":
			$audit_params = $settings;
			auditor( "", "execute", $settings['table'] , $audit_params );
		case "EXECUTE_MULTI_SYS":
		case "EXECUTE_SYS":
			switch( DB_MODE ){
			case "mssql":
				//$settings['query'] = str_replace("`", '"', $settings['query'] );
				$result_of_sql_query = sqlsrv_query( $settings['connect'], $settings['query'] );
			break;
			default:
				switch($settings['query_type']){
				case "EXECUTE_MULTI":
				case "EXECUTE_MULTI_SYS":
					$result_of_sql_query = mysqli_multi_query( $settings['connect'], $settings['query'] );
					// while( mysqli_next_result($settings['connect']) ){}
					while( mysqli_more_results( $settings['connect'] ) ){ mysqli_next_result($settings['connect']); }
				break;
				default:
					$result_of_sql_query = mysqli_query( $settings['connect'], $settings['query'] );
				break;
				}
			break;
			}
			
			if (!$result_of_sql_query) {
				switch( DB_MODE ){
				case 'mssql':
					$ex = sqlsrv_errors();
					$e = isset( $ex[0]["message"] )?$ex[0]["message"]:'';
				break;
				default:
					$e = mysqli_error( $settings['connect'] );
				break;
				}
				$audit_params["trace"] = array_column( debug_backtrace(), 'line', 'file' );
				$audit_params["comment"] = $e;
				auditor( "", "sql_error", $settings['table'] , $audit_params, [
						'database_name' => $settings['database'],
						'database_connection' => $settings['connect']
						] 
					 );
				
				trigger_error('Could not execute statement: '. $audit_params["comment"] .' Q:'. $settings['query'], E_USER_NOTICE );
				
				return $audit_params["comment"];
			}
			//create_update_manifest( str_replace( "`".$settings['database']."`", "`@database`", $settings['query'] ) , $settings['query_type'], $settings['table'], $manifest, $gum );
			if( ! $skip_manifest ){
				create_update_manifest( str_replace( "`".$settings['database']."`", "`@database`", $settings['query'] ) , $settings['query_type'], $settings['table'], $manifest, $gum );
			}
			
			return array( 'success' => 1 );				   
		break;
		case "INSERT":
		case "DELETE":
		case "UPDATE":
			$callStartTime = microtime(true);
				
			switch( $settings[ 'query_type' ] ){
			case 'UPDATE':
			case 'DELETE':
				if( ! ( isset( $settings[ 'revision_history' ][ 'skip' ] ) && $settings[ 'revision_history' ][ 'skip' ] ) ){
					
					switch( $settings[ 'table' ] ){
					case 'payment':
					case 'calendar':
					case 'assets_category':
					case 'assets':
					case 'admission2':
					case 'files':
					case 'bed':
					case 'ward':
					case 'users':
					case 'users_current_work_history':
					case 'users_dependents':
					case 'users_disciplinary_history':
					case 'users_educational_history':
					case 'users_leave':			   
					case 'users_next_of_kin':
					case 'users_performance_appraisal_history':
					case 'users_performance_monitoring':
					case 'users_professional_association':
					case 'users_work_experience_history':	
					case 'training':					 
					case 'users_salary_details':

					case 'access_roles':
					case 'general_settings':
					case 'project_settings':
					case 'grade_level':
					case 'users_bonue':
					case 'list_box_options':
					case 'category':

					case 'antenatal_booking':
					case 'chart_of_accounts':
					case 'credit_limit':
					case 'consultation_settings':
					case 'departments':
					case 'stores':
					case 'parish_transaction':
					case 'online_payment':
					case 'hmo_credit_limit':
					case 'banks':
					case 'users_employee_profile':
					case 'vendors':
					case 'customers':
					case 'tele_health_customer':

						if( class_exists("cRevision_history") ){
							$rh = new cRevision_history();
							$rh->class_settings[ 'calling_page' ] = '../';
							$rh->class_settings[ 'language' ] = 'US';
							$rh->class_settings[ 'database_name' ] = $settings[ 'database' ];
							$rh->class_settings[ 'database_connection' ] = $settings[ 'connect' ];
							$rh->class_settings[ 'user_id' ] = '35991362173';
							$rh->class_settings[ 'priv_id' ] = '1300130013';
							$rh->class_settings[ 'rdata' ] = $settings;
							$rh->class_settings[ 'action_to_perform' ] = "set_revision_history";
							$rh->revision_history();
							
							//get old values - 13-mar-23
							if( ! ( isset( $settings['old_values'] ) && $settings['old_values'] ) ){
								if( isset( $rh->old_values ) && $rh->old_values ){
									$settings['old_values'] = $rh->old_values;
								}
							}
						}
					break;
					}
				}
			break;
			}
			
			//13-mar-23
			if( !$skip_manifest ){
				switch( strtoupper( $settings[ 'query_type' ] ) ){
				case 'UPDATE':
					//get old values - 13-mar-23
					if( ( isset( $settings['old_values'] ) && $settings['old_values'] ) ){
						$gum['old_values'] = $settings['old_values'];
					}else{
						$idfinder = '/[`"\']{1}id[`"\']{1}[\s]?=([\s]?[`"\']{1}\S+[`"\']?)/';
						preg_match($idfinder, $settings['query'], $match);
						if( isset( $match[0] ) &&  isset($match[1]) ){
							$query_settings = [];
							$query_settings['query'] = "SELECT * FROM `". $settings['database']. "`.`". $settings['table']."` WHERE ".$match[0];
							$query_settings["query_type"] = 'SELECT';
							$query_settings["set_memcache"] = 1;
							$query_settings["tables"] = array( $settings['table'] );
							$query_settings["table"] = $settings['table'];
							$query_settings["connect"] = $settings['connect'];
							$query_settings["database"] = $settings['database'];
							set_error_handler(function($e, $es){});
							$s = execute_sql_query( $query_settings );
							if( isset($s[0]['id']) && $s[0]['id'] ){
								$gum['old_values'] = $s[0];
								$gum['old_values']["_t"] = $settings['table'];
								$gum['old_values']["_s"] = 'preg';
							}
						}
					}
				break;
				}
			}
			
			// print_r( $settings['query'] );
			$result_of_sql_query = mysqli_query( $settings['connect'], $settings['query'] );
			
			$callEndTime = microtime(true);
			
			$audit_params = array(
				"query" => $settings["query"],
			);
			
			$acl = '';
			$atd = '';
			if( isset( $settings[ 'parent_tb' ] ) && $settings[ 'parent_tb' ] ){
				$acl = $settings[ 'parent_tb' ];
			}else if( isset( $settings[ 'table_name' ] ) && $settings[ 'table_name' ] ){
				$acl = $settings[ 'table_name' ];
			}else{
				$acl = $settings[ 'tables' ][0];
			}
			if( isset( $settings[ 'action_to_perform' ] ) && $settings[ 'action_to_perform' ] ){
				$atd = $settings[ 'action_to_perform' ];
			}
			if( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
				$audit_params[ 'class' ] = $settings[ 'plugin' ];
				$audit_params[ 'nwp_action' ] = $acl;
				$audit_params[ 'nwp_todo' ] = $atd;
			}else{
				$audit_params[ 'class' ] = $acl;
				$audit_params[ 'action' ] = $atd;
			}

			if( isset( $settings[ 'record_id' ] ) ){
				$audit_params[ 'record_id' ] = $settings[ 'record_id' ];
			}
			if( isset( $settings[ 'table_name' ] ) ){
				$audit_params[ 'record_table' ] = $settings[ 'table_name' ];
			}elseif( isset( $settings[ 'tables' ][0] ) ){
				$audit_params[ 'record_table' ] = $settings[ 'tables' ][0];
			}
			if( isset( $settings[ 'record_plugin' ] ) ){
				$audit_params[ 'record_plugin' ] = $settings[ 'record_plugin' ];
			}elseif( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
				$audit_params[ 'record_plugin' ] = $settings[ 'plugin' ];
			}

			//13-mar-23
			if( isset( $gum['old_values'] ) && $gum['old_values'] ){
				$audit_params["old_values"] = $gum['old_values'];
			}
			$audit_params["duration"] = $callEndTime - $callStartTime;
			
			auditor( "", $settings['query_type'], $settings['table'] , $audit_params, [
						'database_name' => $settings['database'],
						'database_connection' => $settings['connect']
						] 
					 );
			
			if (!$result_of_sql_query) {
				switch( DB_MODE ){
				case 'mssql':
					$ex = sqlsrv_errors();
					$e = isset( $ex[0]["message"] )?$ex[0]["message"]:'';
				break;
				default:
					$e = mysqli_error( $settings['connect'] );
				break;
				}
				
				$audit_params["trace"] = array_column( debug_backtrace(), 'line', 'file' );
				$audit_params["comment"] = $e;
				auditor( "", "sql_error", $settings['table'] , $audit_params, [
						'database_name' => $settings['database'],
						'database_connection' => $settings['connect']
						] 
					 );
				
				//trigger_error('Could not execute statement: '. $e['message'], E_USER_NOTICE);
				trigger_error('Could not execute statement: '. $audit_params["comment"] .' Q:'. $settings['query'], E_USER_NOTICE );
				
				return $audit_params["comment"];
			}else{
				if( class_exists("cNwp_full_text_search") ){
					$GLOBALS["nwp_full_text_search"][ $settings['table'] ] = 1;
				}
				
				if( !$skip_manifest ){
					//13-mar-23
					if( ( ! ( isset( $settings['table'] ) && $settings['table'] ) ) ){
						if( isset( $gum['old_values']["_t"] ) && $gum['old_values']["_t"] ){
							$settings['table'] = $gum['old_values']["_t"];
						}else if( isset( $settings['tables'] ) && is_array( $settings['tables'] ) ){
							foreach( $settings['tables'] as $tb ){
								$settings['table'] = $tb;
								break;
							}
						}
					}
					create_update_manifest( str_replace( "`".$settings['database']."`", "`@database`", $settings['query'] ) , $settings['query_type'], $settings['table'], $manifest, $gum );
				}
				
				//Clear Cache / Temporary Session 
				if( isset( $settings['tables'] ) && is_array( $settings['tables'] ) ){
					
					if(isset($settings['set_memcache']) && $settings['set_memcache']){
						//Get Array of All Cached Tables Keys
						$table_keys = $settings['set_memcache']->get($cache_table_key);
						
						if(is_array($table_keys)){
							//print_r($table_keys);
							
							foreach($settings['tables'] as $table_in_random_case){
								
								$table = strtolower($table_in_random_case); 
								
								if(isset($table_keys[$table]) && is_array($table_keys[$table])){
									
									//Clear All Cached Keys linked to updated table
									foreach($table_keys[$table] as $linked_cache_key => $linked_cache_value){
										
										//Delete Cache
										$settings['set_memcache']->delete($linked_cache_key);	
									}
									
									//Unset Table Key
									unset($table_keys[$table]);
								}
							}
						}
						
						//Update Table Keys
						$settings['set_memcache']->set($cache_table_key,$table_keys,$cache_time);	//Set for two hours
					}
				}
				
				return array( 'success' => 1 );
			}
		break;
		case 'CLEARCACHE':
            if(isset($settings['set_memcache']) && $settings['set_memcache']){
				$audit_params = $settings;
				auditor( "", $settings['query_type'], $settings['query_type'] , $audit_params, [
						'database_name' => $settings['database'],
						'database_connection' => $settings['connect']
						] 
					 );
			
                //Get Array of All Cached Tables Keys
                $table_keys = $settings['set_memcache']->get($cache_table_key);
                
                if(is_array($table_keys)){
                    //print_r($table_keys);
                    
                    foreach($settings['tables'] as $table_in_random_case){
                        
                        $table = strtolower($table_in_random_case); 
                            //print_r( $table ); exit;
                        
                        if(isset($table_keys[$table]) && is_array($table_keys[$table])){
                            //print_r( $table_keys[$table] ); exit;
                            //Clear All Cached Keys linked to updated table
                            foreach($table_keys[$table] as $linked_cache_key => $linked_cache_value){
                                
                                //Delete Cache
                                $settings['set_memcache']->delete($linked_cache_key);	
                            }
                            
                            //Unset Table Key
                            unset($table_keys[$table]);
                        }
                    }
                }
                
                //Update Table Keys
                $settings['set_memcache']->set($cache_table_key,$table_keys,$cache_time);	//Set for two hours
            }
        break;
		case 'DESCRIBE':
			
			$cache_key = md5($settings['query']);
			$array = array();
			
			if(isset($settings['set_memcache']) && $settings['set_memcache'])
				$settings['set_memcache']->delete($cache_key);
			
			switch( DB_MODE ){
			case "mssql":
				//get table name
				//echo $settings['query']; exit;
				//$table_name = str_replace( '"', "", str_replace( strtolower( 'DESCRIBE "'.$settings['database'].'".' ) , "", strtolower($settings['query']) ) );
				$table_name = str_replace( '"', "", str_replace( strtolower( 'DESCRIBE "'.$settings['database'].'"."dbo".' ) , "", strtolower($settings['query']) ) );
				
				//describe
				//echo $table_name; exit;
				/****** Script for SelectTopNRows command from SSMS  ******/
				$query = "SELECT COLUMN_NAME as 'Field', DATA_TYPE as 'Type', CHARACTER_MAXIMUM_LENGTH FROM ".$settings['database'].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."' ORDER BY ORDINAL_POSITION ASC";
				// $query = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM ".$settings['database'].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table_name."' ORDER BY ORDINAL_POSITION ASC";
				//echo $query; exit;
				$result_of_sql_query = sqlsrv_query( $settings['connect'], $query );
				
				if (!$result_of_sql_query) {
					//$e = mysql_error($oracle_query);
					//trigger_error('Could not execute statement: '. $e['message'], E_USER_ERROR);
				}

				while (($row = sqlsrv_fetch_array( $result_of_sql_query )) != false) {
					$array[] = $row;
				}
			break;
			default:
				$result_of_sql_query = mysqli_query( $settings['connect'], $settings['query'] );
				
				if ($result_of_sql_query) {
					while (($row = mysqli_fetch_array( $result_of_sql_query )) != false) {
						$array[] = $row;
					}
				}
			break;
			}
			
			return $array;
		break;
		}
	}
	return 0;
}

function get_l_key(){
	
	if( defined("HYELLA_WEB_COPY") && HYELLA_WEB_COPY ){
		
		$lss = explode( GSEPERATOR, HYELLA_WEB_COPY );
		
		if( isset( $lss[1] ) && $lss[1] ){
			$app_key = $lss[0];
			$_SESSION[ "app" ] = $app_key;
			return $app_key;
		}
	}else{
		error_reporting ( ~E_ALL );
		$pr = get_project_data();
		$ls = file_get_contents( $pr["domain_name"] . "license.hyella" );
		$lss = explode( GSEPERATOR, $ls );
		
		if( isset( $lss[1] ) && $lss[1] ){
			$app_key = md5( md5( $lss[0] ) );
			$_SESSION[ "app" ] = $app_key;
			return $app_key;
		}
	}
	return 0;
}

function get_l_key1(){
	if( defined("HYELLA_WEB_COPY") && HYELLA_WEB_COPY ){
		$lss = explode( GSEPERATOR, HYELLA_WEB_COPY );
		if( isset( $lss[1] ) && $lss[1] ){
			return $lss[1];
		}
	}else{
		$pr = get_project_data();
		$ls = file_get_contents( $pr["domain_name"] . "license.hyella" );
		$lss = explode( GSEPERATOR, $ls );
		if( isset( $lss[1] ) && $lss[1] ){
			return $lss[1];
		}
	}
	return 0;
}
	
function clear_cache_for_special_values_directory( $settings = array() ){
	if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
		
		$settings['set_memcache'] = $GLOBALS['app_memcache'];
		
		if( isset( $settings['permanent'] ) && $settings['permanent'] ){
			$dir = '';
			if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
				$dir = $settings['directory_name'] . '/';
			}
			
			$filename = $settings['set_memcache']::$path . '/' . $dir ;
			foreach( glob( $filename . "*.json" ) as $filename ) {
				if ( is_file( $filename ) ) {
					//13-mar-23
					$s1 = $settings;
					$s1["cache_key"] = pathinfo( $filename )["filename"];
					
					clear_cache_for_special_values( $s1 );
					
					if ( file_exists( $filename ) ) {
						unlink( $filename );
					}
				}
			}
		}
		
		return 1;
	}
	
	return 0;
}

//Cache Special Values to Files
function set_cache_for_special_values( $settings = array() ){
	if( isset( $settings['directory'] ) )
		$settings['directory_name'] = $settings['directory'];
	
	$permanent_only = 0;
	if( isset( $settings['permanent_only'] ) && $settings['permanent_only'] ){
		$permanent_only = 1;
	}
	
	if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
		//3 days
		$cache_time = 3600*3*24;
		
		if( isset( $settings['cache_time'] ) && $settings['cache_time'] ){
			switch( $settings['cache_time'] ){
			case 'mini-time':
				//20 minutes
				$cache_time = 60 * 20;
			break;
            case 'load-time':
				//3 minutes
				$cache_time = 60 * 3;
			break;
            case 'customers-time':
				//30 minutes
				$cache_time = 60 * 30;
			break;
            case 'token-time':
				//10 minutes
				$cache_time = 60 * 10;
			break;
            case 'notice-delay':
				//9 days
				$cache_time = 3600 * 9 * 24;
			break;
            default:
				if( isset( $settings[ 'real_cache_time' ] ) && doubleval( $settings[ 'real_cache_time' ] ) )$cache_time = $settings[ 'real_cache_time' ];
			break;
			}
		}
		
		$settings['set_memcache'] = $GLOBALS['app_memcache'];
		
		if( ! $permanent_only ){
			$settings['set_memcache']->set( $settings['cache_key'] , $settings['cache_values'] , $cache_time );
		}
		
		if( isset( $settings['permanent'] ) && $settings['permanent'] ){
			$dir = '';
			if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
				$dir = $settings['directory_name'] . '/';
				create_folder('' , $settings['set_memcache']::$path . '/' . $dir , '' );
			}
			
			$filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
			if( is_array( $settings['cache_values'] ) ){
				file_put_contents( $filename , json_encode( $settings['cache_values'] ) );
				nwp_file_clean_up( $filename, array( "new" => 1 ) );
			}else{
				file_put_contents( $filename , $settings['cache_values'] );
				nwp_file_clean_up( $filename, array( "new" => 1 ) );
			}
		}
		
		return 1;
	}
	
	return 0;
}

function clear_load_time_cache(){
    if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
		$s = array(
			'cache_key' => "bg-processor",
		);
		$bg = get_cache_for_special_values( $s );
		
		$s = array(
			'cache_key' => "bg-processor-backup",
		);
		$back = get_cache_for_special_values( $s );
		
		$s = array(
			'cache_key' => "bg-processor-stop",
		);
		$bg_stop = get_cache_for_special_values( $s );
		
		$s = array(
			'cache_key' => "pmac-address",
		);
		$mac = get_cache_for_special_values( $s );
		
		$settings['set_memcache'] = $GLOBALS['app_memcache'];
		$settings['set_memcache']->cleanup();
		
		if( $bg ){
			$settings = array(
				'cache_key' => "bg-processor",
				'cache_values' => 1,
				'cache_time' => 'mini-time',
			);
			set_cache_for_special_values( $settings );
		}
		
		if( $back ){
			$settings = array(
				'cache_key' => "bg-processor-backup",
				'cache_values' => 1,
				'cache_time' => 'mini-time',
			);
			set_cache_for_special_values( $settings );
		}
		
		if( $bg_stop ){
			$settings = array(
				'cache_key' => "bg-processor-stop",
				'cache_values' => 1,
				'cache_time' => 'mini-time',
			);
			set_cache_for_special_values( $settings );
		}
		if( $mac ){
			$settings = array(
				'cache_key' => "pmac-address",
				'cache_values' => $mac,
				'cache_time' => 'notice-delay',
			);
			set_cache_for_special_values( $settings );
		}
	}
}

//cache_reload_urls
function get_cache_reload_url( $key ){
    return 0;
}

//Cache Special Values to Files
function get_cache_for_special_values( $settings = array() ){
	if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
		
        $p = get_project_data();
        $url = $p['domain_name'].'engine/php/ajax_request_processing_script.php';
        //$url = 'http://localhost/dev-1/engine/php/ajax_request_processing_script.php';
        //$url = './engine/php/ajax_request_processing_script.php';
        $data = '';
        if( isset( $settings['data'] ) && $settings['data'] ){
            $data = $settings['data'];
        }
		$plain_text = false;
        if( isset( $settings['plain_text'] ) && $settings['plain_text'] ){
            $plain_text = $settings['plain_text'];
        }
        
		$settings['set_memcache'] = $GLOBALS['app_memcache'];
		
		if( isset( $settings['permanent'] ) && $settings['permanent'] ){
			$dir = '';
			if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
				$dir = $settings['directory_name'] . '/';
			}
			
			$filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
			
			if( isset( $settings[ 'folder' ] ) && $settings[ 'folder' ] && is_dir( $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] ) ){
			    //print_r( $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] );
				$retn = array();
			    foreach( scandir( $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] ) as $file ){
	        		if ( $file !== '.' && $file !== '..' ){
						
						if( $plain_text ){
							$retn[ str_replace( '.json', '', $file ) ] = file_get_contents( $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '/' . $file );
						}else{
							$retn[ str_replace( '.json', '', $file ) ] = json_decode( file_get_contents( $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '/' . $file ) , true );
						}
						
					}
				}
				return $retn;
			}else if( file_exists( $filename ) ){
				if( $plain_text ){
					return file_get_contents( $filename );
				}else{
					return json_decode( file_get_contents( $filename ) , true );
				}
			}else{
				//use file get content as fallback to recreate cache
                //for permanent cached values
                
                if( $dir ){
                    switch( $dir ){
                    case 'state_list':
                    case 'state_list/':
                    case 'cities_list/':
                    case 'cities_list':
                        $d = get_cache_reload_url( $dir );
                        if( isset( $d['url'] ) && isset( $d['action'] ) && isset( $d['todo'] ) ){
                            $url .= $d['url'].$data;
                            
                            if( isset( $_SESSION['reuse_settings'] ) && $_SESSION['reuse_settings'] ){
                                $_SESSION['reuse_settings']['action'] = $d['todo'];
                                $_SESSION['reuse_settings']['classname'] = $d['action'];
                                //reuse_class( $_SESSION['reuse_settings'] );
                            }
                            //file_get_contents( $url );
                        }
                    break;  
                    }
                    
                    $filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
                    if( file_exists( $filename ) ){
						if( $plain_text ){
							return file_get_contents( $filename ) ;
						}else{
							return json_decode( file_get_contents( $filename ) , true );
						}
                    }
                }
			}
		}
		$returning = $settings['set_memcache']->get( $settings['cache_key'] );
		
		if( empty( $returning ) ){
			//use file get content as fallback to recreate cache
			switch( $settings['cache_key'] ){
			case 'categories':
				$url .= '?action=categories&todo=get_categories';
			break;
            case 'site_users-all-users-countries':
				$url .= '?action=site_users&todo=get_all_users_countries';
                file_get_contents( $url );
			break;
			}
			//file_get_contents( $url );
		}else{
			return $returning;
		}
		
		return $settings['set_memcache']->get( $settings['cache_key'] );
	}
	
	return 0;
}

//Cache Special Values to Files
function clear_cache_for_special_values( $settings = array() ){
	if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
		
		$settings['set_memcache'] = $GLOBALS['app_memcache'];
		
		$settings['set_memcache']->delete( $settings['cache_key'] );
		
		if( isset( $settings['permanent'] ) && $settings['permanent'] ){
			$dir = '';
			if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
				$dir = $settings['directory_name'] . '/';
			}
			
			$filename = $settings['set_memcache']::$path . '/' . $dir . $settings['cache_key'] . '.json';
			if( file_exists( $filename ) ){
				unlink($filename);
			}
			
		}
		
		return 1;
	}
	
	return 0;
}

function get_divisional_report_key(){
	return 'divisional-reports';
}

//Convert money from one currency to another
function currency_converter( $amount, $from_units, $to_units, $currency_conversion_rate = 0 ){
	/*
	 *Converts currency from one unit to another
	*/
	$value_of_one_usd_in_naira = get_naira_equivalent_of_one_us_dollar();
	
	if(doubleval($currency_conversion_rate)){
		$value_of_one_usd_in_naira = $currency_conversion_rate;
	}
	
	$values_to_be_inserted_into_each_field = array(
		//Converting from usd to [usd, ngn, million usd, million ngn]
		'usd' => array(
			'usd'=>1,
			'ngn'=>$value_of_one_usd_in_naira,
			'million_usd'=>0.000001,
			'million_ngn'=>($value_of_one_usd_in_naira/1000000),
		),
		//Converting from ngn to [usd, ngn, million usd, million ngn]
		'ngn' => array(
			'ngn'=>1,
			'usd'=>(1/$value_of_one_usd_in_naira),
			'million_usd'=>((1/$value_of_one_usd_in_naira)/1000000),
			'million_ngn'=>0.000001,
		),
		//Converting from millionngn to [usd, ngn, million usd, million ngn]
		'million_ngn' => array(
			'ngn'=>1000000,
			'usd'=>((1/$value_of_one_usd_in_naira)*1000000),
			'million_usd'=>(1/$value_of_one_usd_in_naira),
			'million_ngn'=>1,
		),
		//Converting from millionusd to [usd, ngn, million usd, million ngn]
		'million_usd' => array(
			'ngn'=>($value_of_one_usd_in_naira * 1000000),
			'usd'=>1000000,
			'million_usd'=>1,
			'million_ngn'=>$value_of_one_usd_in_naira,
		),
	);
	//Check Base Unit
	if($amount && is_numeric($amount)){
		$from_units = strtolower(str_replace(" ","_",$from_units));
		$to_units = strtolower(str_replace(" ","_",$to_units));
		if(isset($values_to_be_inserted_into_each_field[$from_units]) && isset($values_to_be_inserted_into_each_field[$from_units][$to_units])){
			$amount = $amount * $values_to_be_inserted_into_each_field[$from_units][$to_units];
		}
	}
	
	if( $amount > 0 ){
		return round($amount , 2);
	}
	
	return $amount;
}

//Returns HTML for Select Combo Box that would be used in selecting 
//units of physical quantities for conversion
function units_select_box( $type , $defaultunits = "" ){
	/*
	 *Prepare Combo box that would be used in selecting units
	*/
	$returning_html_data = '';
	
	switch($type){
	case "volume":
		//Get all units of gas volumes
		$units = get_gas_volume_units();
	break;
	case "currency":
		//Get all units of currency
		$units = get_currency();
	break;
	case "kvalue":
		//Get all units of calorific values
		$units = get_calorific_units();
	break;
	case "time":
		//Get all units of time
		$units = get_time_units();
	break;
	case "currency_per_unit_kvalue":
		//Get all cuurency per unit kvalue
		$units = get_currencies_per_unit_kvalue();
	break;
	case "pressure":
		//Get all cuurency per unit kvalue
		$units = get_gas_pressure_units();
	break;
	case "volume_per_day":
		//Get all volume per day units
		$units = get_gas_volume_per_day_units();
	break;
	case "heating_value":
		//Get all heating content units
		$units = get_heating_value_units();
	break;
	}
	
	
	$un = md5('units'.$_SESSION['key']);
	
	//Check if user has selected an appropriate unit
	if(isset($_GET['selected_unit']) && $_GET['selected_unit'] && isset($_GET['physical_quantity']) && $_GET['physical_quantity']==$type){
		$defaultunits = $units[$_GET['selected_unit']];
		
		//Update Default Unit Stored in Session
		$_SESSION[$un][$_GET['physical_quantity']] = $defaultunits;
	}
	
	//Check for Stored Units in Session Variable
	if(isset($_SESSION[$un][$type]) && $_SESSION[$un][$type]){
		$defaultunits = $_SESSION[$un][$type];
	}
	
	$returning_html_data .= '<select name="'.$type.'" class="units-select-box" data-inline="true" data-mini="true" data-corners="false">';
		foreach($units as $k_unit => $v_unit){
			if(strtolower($defaultunits)==strtolower($v_unit)){
				$returning_html_data .= '<option value="'.$k_unit.'" selected="selected">'.$v_unit.'</option>';
			}else{
				$returning_html_data .= '<option value="'.$k_unit.'">'.$v_unit.'</option>';
			}
		}
	$returning_html_data .= '</select>';
	
	return $returning_html_data;
}

//Returns HTML for Select Combo Box that would be used in selecting 
//custom view options
function get_custom_view_options_select_box( $settings = array() ){
	/*
	 *Prepare Combo box that would be used in selecting custom view options
	*/
	$un = md5('viewport'.$_SESSION['key']);
	$default_view_port = '';
	
	//Check if user has selected an appropriate unit
	if(isset($_GET['viewport_selected_view']) && $_GET['viewport_selected_view'] && isset( $_GET['viewport_class_name'] ) && $_GET['viewport_class_name'] ){
		$default_view_port =  $_GET['viewport_selected_view'];
		
		//Update Default Unit Stored in Session
		$_SESSION[$un][ $_GET['viewport_class_name'] ] = $default_view_port;
	}
		
	if( isset( $settings['class_name'] ) && isset( $settings['option_list'] ) && is_array( $settings['option_list'] ) ){
		$returning_html_data = '';
		
		//Check for Stored Units in Session Variable
		if(isset($_SESSION[$un][ $settings['class_name'] ]) && $_SESSION[$un][ $settings['class_name'] ]){
			$default_view_port = $_SESSION[$un][ $settings['class_name'] ];
		}
		
		$returning_html_data .= '<select name="'.$settings['class_name'].'" id="view-options-select-box" data-inline="true" data-mini="true" data-corners="false" data-theme="f">';
			foreach( $settings['option_list'] as $key => $value ){
				if( strtolower( $default_view_port ) == strtolower( $key ) ){
					$returning_html_data .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
				}else{
					$returning_html_data .= '<option value="'.$key.'">'.$value.'</option>';
				}
			}
		$returning_html_data .= '</select>';
		
		return $returning_html_data;
	}
}

//Returns human readable form of advance search query
function convert_to_highlevel_query( $low_level_query , $table ){
	$high_level_query = '';
	
	
	//Store value of last field
	$last_field = array();
	
	//Get values of table fields
	$func = $table;
	if(function_exists($func))
		$form_label = $func();
	else
		$form_label = array();
	
	//ADD CREATION AND MODIFIED DATE FIELD
	$form_label['MODIFIED_DATE'] = 'Modified Date';
	$form_label['CREATION_DATE'] = 'Creation Date';
	
	if($low_level_query){
		//1. Remove all status conditions
		$high_level_query = str_replace(" AND `".$table."`.`record_status`='1'","",$low_level_query);
		$high_level_query = str_replace("`".$table."`.","",$high_level_query);
		
		//2. Remove all divisions
		$high_level_query = str_replace("/1","",$high_level_query);
		
		//3. Remove all `
		$high_level_query = str_replace("`","",$high_level_query);
		
		//4. Break Statement into words
		$words = explode(" ",$high_level_query);
		$high_level_query = '';
		if(is_array($words)){
			//5. Loop via all words and search for words with _dt in them
			foreach($words as & $word){
				if (preg_match("/".$table."/", $word)) {
					//Get Global Value for Field Names
					//$fields_of_database_table = get_form_field_type($word);
					
					if( isset( $form_label[$word] ) ){
						//Set form type of last field
						$last_field = $form_label[$word];
						
						//Check for label
						$word =  '<label>'.$form_label[$word][ 'field_label' ].'</label>';
					}
				}
				
				if( isset( $last_field[ 'form_field' ] ) ){
					//Check for Combo Value
					switch($last_field[ 'form_field' ]){
					case 'date-5':
					case 'date':
						//Convert to date
						$clean_word = str_replace("'","",$word);
						$clean_word = str_replace("%","",$clean_word);
						if(is_numeric($clean_word)){
							$word = "'".date("d-M-Y",($clean_word/1))."'";
							
							//Clear Last Field
							$last_field = array();
						}
					break;
					case 'select':
						//Get options function name
						$option_function_name = $last_field[ 'form_field_options' ];
						
						if( function_exists($option_function_name) ){
							
							$options = $option_function_name();
							
							$clean_word = str_replace("'","",$word);
							$clean_word = str_replace("%","",$clean_word);
							
							if(isset($options[$clean_word])){
								$word = "'".ucwords($options[$clean_word])."'";	
								
								//Clear Last Field
								$last_field = array();
							}
							
						}
					break;
					case 'multi-select':
						//Get options function name
						$option_function_name = $last_field[ 'form_field_options' ];
						
						if(function_exists($option_function_name)){
							
							$options = $option_function_name();
							
							$cleaned = str_replace("'","",$word);
							$cleaned = str_replace("%","",$cleaned);
							$clean_words = explode(":::",$cleaned);
							$newword = '';
							foreach($clean_words as $clean_word){
								if(isset($options[$clean_word])){
									if($newword)$newword .= ", '".ucwords($options[$clean_word])."'";	
									else $newword = "'".ucwords($options[$clean_word])."'";
									
									//Clear Last Field
									$last_field = array();
								}
							}
							
							if($newword)
								$word = $newword;
						}
					break;
					}
				}
				
				if (preg_match("/REGEXP/", $word)) {
					$word = 'CONTAINS';
				}
			}
		}
		
		$high_level_query = '<label>Filtered Records: </label>'.implode(" ",$words);
	}
	
	return $high_level_query;
}

//Returns HTML of checkboxes for use in selecting columns to be displayed in dataTables
function get_column_toggler_checkboxes( $column_name , $table , $module , $field_details ){
	//Determine Current State of Column
	//Toggle Column
	
	if( isset( $field_details['field_label'] ) && $field_details['field_label'] )
		$display_label = $field_details['field_label'];
	else
		$display_label = ucwords( str_replace( '_', ' ', $column_name ) );
	
	if( isset( $field_details[ 'default_field_label' ] ) && $field_details[ 'default_field_label' ] ){
		$display_label = $field_details[ 'default_field_label' ] . ' ('.$display_label.')';
	}
	
	$sq = md5('column_toggle'.$_SESSION['key']);
	
	//Hide Columns by default
	if( ! ( isset( $field_details['default_appearance_in_table_fields'] ) && $field_details['default_appearance_in_table_fields'] == 'show' ) ){
		$_SESSION[$sq][$table][$column_name] = 1;
	}
	
	if( isset($_SESSION[$sq][$table][$column_name]) ){
		$column_state = '';
	}else{
		$column_state = 'checked="checked"';
	}
	
	$cls = '';
	if( isset( $field_details[ 'is_mobile' ] ) && $field_details[ 'is_mobile' ] ){
		$cls = ' nwp-is-mobile ';
	}
	//Return Checkboxes of Columns to Toggle Display Status
	return '<li class="form-check mb-2"><label class="checkbox form-check-label"><input type="checkbox" class="'.$cls.' form-check-input" name="'.$column_name.'" function-id="column_toggle" function-class="column_toggle" column-toggle-table="'.$table.'" function-name="column_toggle" module-name="'.$module.'" module-id="'.$module.'" '.$column_state.'><small>'.$display_label.'</small></label></li>';
}

//Sets Session variable that would be used in caching list of values used in populating select

function get_external_options_for_caching( $settings = array() ){
	$project_data = get_project_data();
	
	if( isset( $settings[ 'request' ] ) && $settings[ 'request' ] ){
		
		$json = file_get_contents( $project_data['remote_server_request_url'] . '?request='.$settings[ 'request' ] );
		
		if($json){
			$returned_array = json_decode($json, true);
			
			asort($returned_array);
			
			//CACHE ALL OPTIONS LOADED FROM DATABASE
			$_SESSION['temp_storage'][ $settings[ 'request' ] ][ $settings[ 'request' ] ] = $returned_array;
			
		}
		
	}			
	
}

function pie_legend_chart(){
	$pr = get_project_data();
	return array(
		"chart" => array( "plotBackgroundColor" => null, "plotBorderWidth" => null, 'plotShadow' => false, 'type' => 'pie' ),
		"title" => array( "text" => $pr['company_name'] . " Monthly Expenditure Statistics" ),
		"subtitle" => array( "text" => "" ),			
		"tooltip" => array( 
			"pointFormat" => "{series.name}: <b>{point.percentage:.1f}%</b>",
		),
		"plotOptions" => array( 
			"pie" => array(
				"allowPointSelect" => true,
				"cursor" => 'pointer',
				"dataLabels" => array(
					"enabled" => false,
				),
				"showInLegend" => true,
			)
		),
		"series" => array(
			array(
				"colorByPoint" => true,
				"name" => 'No Data',
				"data" => array( 
					array( "name" => 'No Data', "y" => 100 ),
				),
			),
		),
	);
}

function basic_column_chart(){
	$pr = get_project_data();
	return array(
		"chart" => array( "type" => "column" ),
		"title" => array( "text" => $pr['company_name'] . " Annual Revenue / Expenditure Statistics" ),
		"subtitle" => array( "text" => "" ),
		"xAxis" => array( 
			"categories" => array( 'No Data' ),
			"crosshair" => "true" 
		),
		"yAxis" => array( 
			"min" => 0,
			"title" => array( "text" => "Revenue Generated in NGN" ),
		),
		"tooltip" => array( 
			"headerFormat" => "<span style='font-size:10px'>{point.key}</span><table>",
			"pointFormat" => "<tr><td style='font-size:10px; color:{series.color};padding:0'>{series.name}: </td><td style='padding:0; font-size:10px;'><b>{point.y:.2f}</b></td></tr>",
			"footerFormat" => "</table>",
			"shared" => "true",
			"useHTML" => "true",
		),
		"plotOptions" => array( 
			"column" => array(
				"pointPadding" => "0.2",
				"borderWidth" => "0"
			)
		),
		"series" => array( 
			array(
				"name" => "No Data",
				"data" => array( 0 )
			),
		),
	);
}

function column_line_pie_chart(){
	return array(
		"title" => array(
			"text" => 'Combination chart'
		),
		"xAxis" => array(
			"categories" => array('Apples', 'Oranges', 'Pears', 'Bananas', 'Plums')
		),
		"labels" => array(
			"items" => array(array(
				"html" => 'Total fruit consumption',
				"style" => array(
					"left" => '50px',
					"top" => '18px',
					"color" => "(Highcharts.theme && Highcharts.theme.textColor) || 'black'"
				)
			))
		),
		"series" => array(
			array(
				"type" => 'column',
				"name" => 'Jane',
				"data" => array(3, 2, 1, 3, 4)
			), array(
				"type" => 'column',
				"name" => 'John',
				"data" => array(2, 3, 5, 7, 6)
			), array(
				"type" => 'column',
				"name" => 'Joe',
				"data" => array(4, 3, 3, 9, 0)
			), array(
				"type" => 'spline',
				"name" => 'Average',
				"data" => array(3, 2.67, 3, 6.33, 3.33),
				"marker" => array(
					"lineWidth" => 2,
					"lineColor" => "Highcharts.getOptions().colors[3]",
					"fillColor" => 'white'
				)
			), array(
				"type" => 'pie',
				"name" => 'Total consumption',
				"data" => array(array(
					"name" => 'Jane',
					"y" => 13,
					"color" => "Highcharts.getOptions().colors[0]", 
				), array(
					"name" => 'John',
					"y" => 23,
					"color" => "Highcharts.getOptions().colors[1]",
				), array(
					"name" => 'Joe',
					"y" => 19,
					"color" => "Highcharts.getOptions().colors[2]",
				)),
				"center" => array(100, 80),
				"size" => 100,
				"showInLegend" => false,
				"dataLabels" => array(
					"enabled" => false
				)
			)
		)
	);
}

//combo boxes that are populated from the database tables
function get_options_for_caching( $database_names , $database_connection , $selected = "all" ){
	//CACHE ALL OPTIONS LOADED FROM DATABASE
	
	$options = array(
		array(
			'table'=>'functions',
			'field'=>'accessible_functions',
			'value'=>'functions001',
		),
		array(
			'table'=>'budgets',
			'field'=>'get_budgets',
			'value'=>'budgets001',
		),
		array(
			'table'=>'modules',
			'field'=>'modules_in_application',
			'value'=>'modules001',
		),
		array(
			'table'=>'access_roles',
			'field'=>'access_roles',
			'value'=>'access_roles001',
		),
		array(
			'table'=>'users',
			'field'=>'users_names',
			'value'=>'users001',
		),
		array(
			'table'=>'site_users',
			'field'=>'users_names',
			'value'=>'site_users001',
		),
	);
	
	foreach($options as $option){
		if((is_array($selected) && in_array($option['table'],$selected)) || $selected=='all'){
			//GET UNIQUE OPTIONS FOR CACHING
			
			//Prepare Database Query
			switch($option['table']){
			default:
				$query = "SELECT * FROM `".$database_names."`.`".$option['table']."` WHERE `".$option['table']."`.`record_status`='1'";
			break;
			}
			
			$query_settings = array(
				'database'=>$database_names,
				'connect'=>$database_connection,
				'query'=>$query,
				'query_type'=>'SELECT',
				'set_memcache'=>1,
				'tables'=>array( $option['table'] ),
			);
			$sql_result = execute_sql_query( $query_settings );
			
			if(isset($sql_result) && is_array($sql_result)){
				
				foreach($sql_result as $k_sql => $v_sql){
					if(is_array($v_sql)){
						
						switch($option['table']){
						case "users":
						case "site_users":
							//cache session value used for select box options
							$_SESSION['temp_storage'][ $option['table'] ][$option['field']][$v_sql['id']] = strtoupper(substr($v_sql[ $option['table'].'001'],0,1)).'. '.$v_sql[ $option['table'].'002'];
							
							//cache session value used for select box options
							$_SESSION['temp_storage'][ $option['table'] ]['users_names_full'][$v_sql['id']] = $v_sql[ $option['table'].'001'].' '.$v_sql[ $option['table'].'002'];
							
							//cache session value used for select box options
							if( $option['table'] == "site_users" ){
								$_SESSION['temp_storage'][ $option['table'] ]['users_email_addresses'][$v_sql['id']] = $v_sql[ $option['table'].'004'];
							}else{
								$_SESSION['temp_storage'][ $option['table'] ]['users_email_addresses'][$v_sql['id']] = $v_sql[ $option['table'].'003'];
							}
						break;
						case "budgets":
							//cache session value used for select box options
							$_SESSION['temp_storage'][ $option['table'] ][ $option['field'] ][$v_sql['id']] = strtoupper( $v_sql[ 'budgets001' ] ) . '-' . $v_sql[ 'budgets002' ] . '-' . get_select_option_value( array( 'id' => $v_sql[ 'budgets003' ], 'function_name' => 'get_cash_call_types' ) ). '-' . $v_sql[ 'budgets004' ];
						break;
						default:
							//cache session value used for select box options
							$_SESSION['temp_storage'][$option['field']][$v_sql['id']] = $v_sql[$option['value']];
							
							$_SESSION['temp_storage'][ $option['table'] ][$option['field']][$v_sql['id']] = $v_sql[$option['value']];
							
						break;
						}
					}
				}
				
				
			}
			
		}
		
	}
}

//Returns Application Password Salter
function get_websalter(){
	//Application Salter
	//return '10839hxec,.#02<@d439adsaSD05a7dcNSCIVue7^%FXtr^$£"£*(&"!£244SDFF##';
	if( defined("NWP_WEB_SALTER") && NWP_WEB_SALTER ){
		return NWP_WEB_SALTER;
	}
	return '10839h#<@ddcNSCIVu';
}

//Returns HTML data for Data Capture Form Titles
function get_add_new_record_form_heading_title( $title = '' ){
	return '<h3 id="form-title">'.$title.'</h3>';
}

//Generate Token form Authenticating Form
function generate_token( $settings = array() ){
	
	$frmtok = '';
	if( isset( $_SESSION['key'] ) ){
		$frmtok = md5( 'form_token' . $_SESSION['key'] );
	}
	
	if( isset( $settings['validate'] ) && $settings['validate'] ){
		$r = 0;
		$token = $settings['validate'];
		
		if( isset( $_SESSION[ $frmtok ][ $token ] ) ){
			unset( $_SESSION[ $frmtok ][ $token ] );
			$r = 1;
		}
		
		return $r;
	}
	
	$token = '';
	
	//Store duplicate value for comparison
	if( $frmtok ){
		$token = md5( rand() . $settings['table'] . date("U") );
		$_SESSION[ $frmtok ][ $token ] = $token;
		$_SESSION[ $frmtok ][ 'last' ] = $token;
	}
	
	return $token;
}

function send_mail( $settings = array() ){
	//return 1;
	
	$pagepointer = '';
	if( isset( $settings['pagepointer'] ) )
		$pagepointer = $settings['pagepointer'];
		
	$recipient_emails = array();
	if( isset( $settings['recipient_emails'] ) )
		$recipient_emails = $settings['recipient_emails'];
		
	$recipient_fullnames = array();
	if( isset( $settings['recipient_fullnames'] ) )
		$recipient_fullnames = $settings['recipient_fullnames'];
		
	$subject = '';
	if( isset( $settings['subject'] ) )
		$subject = $settings['subject'];
		
	$message = '';
	if( isset( $settings['message'] ) ){
        $message = $settings['message'];
        /*
		if( file_exists( $pagepointer . 'css/email-notification.css' ) ){
			$message .= '<style>' . file_get_contents( $pagepointer . 'css/email-notification.css' ) . '</style>';
		}
		$message .= '<div id="message-content">';
		$message .= '<div id="message-content-header">Gas Helix - Notification</div>';
		$message .= '<div id="message-content-body">' . $settings['message'] . '</div>';
		$message .= '</div>';
        */
	}
	
	$sender_email = '';
	if( isset( $settings['sender_email'] ) )
		$sender_email = $settings['sender_email'];
		
	$sender_fullname = '';
	if( isset( $settings['sender_fullname'] ) ){
		$sender_fullname = $settings['sender_fullname'];
	}
    
	$project = get_project_data();
	
	$project_title = '';
	if( isset( $project['project_title'] ) )
		$project_title = $project['project_title'];
	
    if( ! $sender_fullname )$sender_fullname = $project_title;
    
	$admin_email = 'pat3echo@gmail.com';
	if( isset( $project['admin_email'] ) ){
		$admin_email = $project['admin_email'];
	}
	
	if( function_exists("get_project_admin_email") ){
		$admin_email = get_project_admin_email();
	}
	
	$smtp_auth_email = '';
	if( isset( $project['smtp_auth_email'] ) )
		$smtp_auth_email = $project['smtp_auth_email'];
	
	if( function_exists("get_project_smtp_auth_email") ){
		$smtp_auth_email = get_project_smtp_auth_email();
	}
	if( defined( 'SMTP_AUTH_EMAIL' ) && SMTP_AUTH_EMAIL ){
		$smtp_auth_email = SMTP_AUTH_EMAIL;
	}
	
	$reply_email = $admin_email;
	if( isset( $project['reply_to_email'] ) && $project['reply_to_email'] ){
		$reply_email = $project['reply_to_email'];
	}
	
	if( function_exists("get_project_reply_to_email") ){
		$reply_email = get_project_reply_to_email();
	}
	if( defined( 'REPLY_EMAIL' ) && REPLY_EMAIL ){
		$reply_email = REPLY_EMAIL;
	}
	
	$sender_email = $smtp_auth_email;
	if( isset( $project['sender_email'] ) && $project['sender_email'] ){
		$sender_email = $project['sender_email'];
	}
	
	if( function_exists("get_project_sender_email") ){
		$sender_email = get_project_sender_email();
	}
	if( defined( 'SENDER_EMAIL' ) && SENDER_EMAIL ){
		$sender_email = SENDER_EMAIL;
	}
	//$sender_email = "no-reply@hyella.com";
	//$sender_email = $smtp_auth_email;
	
	$smtp_auth_password = '';
	if( isset( $project['smtp_auth_password'] ) )
		$smtp_auth_password = $project['smtp_auth_password'];
	
	if( function_exists("get_project_smtp_auth_password") ){
		$smtp_auth_password = get_project_smtp_auth_password();
	}
	if( defined( 'SMTP_AUTH_PASSWORD' ) && SMTP_AUTH_PASSWORD ){
		$smtp_auth_password = SMTP_AUTH_PASSWORD;
	}
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: ';
	
	$email_address_count = 0;
	$bcc_email = '';
	if( isset( $settings['bcc_emails'] ) && $settings['bcc_emails'] ){
		$bcc_email = trim( $settings['bcc_emails'] );
		$email_address_count = 1;
	}
    
	$cc_email = '';
	if( isset( $settings['cc_emails'] ) && $settings['cc_emails'] ){
		$cc_email = $settings['cc_emails'];
		$email_address_count = 1;
	}
    
	$email = "";
	$name = "";
	foreach( $recipient_emails as $_id => $_email ){
		if( $_email ){
			if( $email_address_count ){
				$headers .= ', '.$recipient_fullnames[$_id].' <'.$_email.'>';
				$email .= ', ' . $_email;
				$name .= ', ' . $recipient_fullnames[$_id];
			}else{
				$headers .= $recipient_fullnames[$_id].' <'.$_email.'>';
				$email = $_email;
				$name = $recipient_fullnames[$_id];
			} 
			
			++$email_address_count;
		}
	}
	$headers .= "\r\n";
	
	$headers .= 'From: '.$sender_fullname.' <'.$sender_email.'>' . "\r\n";
	$headers .= 'Bcc: '.$admin_email. "\r\n";
	
	if( ! $email_address_count ){
		$message = "Invalid Email Address";
		$subject = "Invalid Email Address, Message Not Sent";
	}
	
	//Write mail to tmp folder for reference
	$stored_mail = array();
	$stored_mail[ "bcc" ] = $admin_email;
	$stored_mail[ "email" ] = implode(' , ' , $recipient_emails);
	$stored_mail[ "subject" ] = $subject;
	$stored_mail[ "message" ] = rawurlencode( $message );
	$stored_mail[ "headers" ] = rawurlencode( $headers );
	$stored_mail[ "date" ] = date("U");
	
	$response = "Mailer Unsent";
	$status = 0;
		
	//Activate to send mail to email account when testing online
	$mails = '';
	//if( 1 == 2 && file_exists( $pagepointer.'classes/PHPMailer-master/PHPMailerAutoload.php' ) ){
	
	//Set who the message is to be sent to
	$found_mail = 0;
	$sg_rep = array();
	$mails = '';
	if( file_exists( $pagepointer.'classes/PHPMailer-master/PHPMailerAutoload.php' ) ){
		require_once $pagepointer.'classes/PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
	}
	
	if( ! empty( $recipient_emails ) ){
		foreach( $recipient_emails as $id => $email ){
			if( strpos( $email, "nil.nil@" ) > -1 ){
				continue;
			}
			if( filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
				if( isset( $mail ) )$mail->addAddress( $email , $recipient_fullnames[ $id ] );
				$mails .= $email;
				$sg_rep["to"][] = array( "email" => $email, "name" => $recipient_fullnames[ $id ] );
				$found_mail = 1;
			}
		}
	}
	
	if( $admin_email && filter_var( $admin_email, FILTER_VALIDATE_EMAIL ) ){
		if( isset( $mail ) )$mail->addBCC( $admin_email );
		$sg_rep["bcc"][] = array( "email" => $admin_email );
	}
	
	$ade = explode(',', $bcc_email );
	if( ! empty( $ade ) ){
		foreach( $ade as $ade2 ){
			if( strpos( $ade2, "nil.nil@" ) > -1 ){
				continue;
			}
			$ade3 = trim( $ade2 );
			if( $ade3 && filter_var( $ade3, FILTER_VALIDATE_EMAIL ) ){
				if( isset( $mail ) )$mail->addBCC( $ade3 );
				$sg_rep["bcc"][] = array( "email" => $ade3 );
				$found_mail = 1;
			}
		}
	}
	
	$ade = explode(',', $cc_email );
	if( ! empty( $ade ) ){
		foreach( $ade as $ade2 ){
			if( strpos( $ade2, "nil.nil@" ) > -1 ){
				continue;
			}
			$ade3 = trim( $ade2 );
			if( $ade3 && filter_var( $ade3, FILTER_VALIDATE_EMAIL ) ){
				if( isset( $mail ) )$mail->addCC( $ade3, $ade3 );
				$sg_rep["cc"][] = array( "email" => $ade3 );
				$found_mail = 1;
			}
		}
	}
	
	if( $found_mail ){
		$trackingEP = '';
		//$trackingEP = 'http://newsletter.hyella.com/tracked/';
		if( defined("EMAIL_TRACKING_ENDPOINT") && EMAIL_TRACKING_ENDPOINT ){
			$trackingEP = EMAIL_TRACKING_ENDPOINT;
		}
		
		$landingP = '#';
		if( defined("EMAIL_LANDING_PAGE") && EMAIL_LANDING_PAGE ){
			$landingP = EMAIL_LANDING_PAGE;
		}
		$message .= '<br /><br />';
		
		if( $trackingEP ){
			$message .= '<img style="width:1px; height:1px; float:right;" src="'.$trackingEP.'?email='.urlencode( strtolower( $mails ) ).'&msg='.urlencode( $subject ).'"/>';
		}
		
		$message .= '<div style="font-size: 12px; text-align: center; margin-top: 20px;">(C) '.date("d-M-Y").' <a href="'.$landingP.'" target="_blank">'.$project_title . ' - ' . $project["app_title"] . '</a></div>';
	}
	
	if( ! ( defined("HYELLA_ENABLE_EMAILS") && HYELLA_ENABLE_EMAILS ) ){
		$status = 1;
		$response = "Dummy Email was not sent because this module _ENABLE_EMAILS is not enabled";
		$subject = 'TEST EMAIL: ' . $subject;
	}else if( defined("NWP_SEND_GRID_URL") && NWP_SEND_GRID_URL && defined("NWP_SEND_GRID_TOKEN") && NWP_SEND_GRID_TOKEN ){
		$response = send_mail_sendgrid( $sender_email, $sender_fullname, $subject, $message, $sg_rep, $settings );
		$status = 1;
	}else if( isset( $mail ) ){

		//Tell PHPMailer to use SMTP
		$mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = ( isset( $settings["debug"] ) && $settings["debug"] )?2:0;

		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		
		if( $found_mail ){
			//Set the hostname of the mail server
			//$mail->Host = 'smtp.gmail.com';
			//$mail->Host = 'ssl://smtp.gmail.com'; - not in use
			
			//$mail->Host = 'smtp.ipage.com';
			$mail->Host = get_project_smtp_host();
			if( defined( 'SMTP_HOST' ) && SMTP_HOST ){
				$mail->Host = SMTP_HOST;
			}
			
			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			//$mail->Port = 587;
			//$mail->Port = 465;
			$mail->Port = get_project_smtp_port();
			if( defined( 'SMTP_PORT' ) && SMTP_PORT ){
				$mail->Port = SMTP_PORT;
			}
			
			//Set the encryption system to use - ssl (deprecated) or tls
			//$mail->SMTPSecure = 'tls';
			//$mail->SMTPSecure = 'ssl';
			$mail->SMTPSecure = get_project_smtp_encryption(); //'tls';
			if( defined( 'SMTP_ENCRYPTION' ) && SMTP_ENCRYPTION ){
				$mail->SMTPSecure = SMTP_ENCRYPTION;
			}

			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			if( defined( 'SMTP_AUTH' ) ){
				$mail->SMTPAuth = SMTP_AUTH;
			}

			//Username to use for SMTP authentication - use full email address for gmail
			//$mail->Username = 'pat3echo@gmail.com';
			//$mail->Username = 'info@basviewtech.com.ng';
			$mail->Username = $smtp_auth_email;

			//Password to use for SMTP authentication
			//$mail->Password = 'pat3001echo';
			//$mail->Password = 'wis-3free49-DOM';
			$mail->Password = $smtp_auth_password;

			//Set who the message is to be sent from
			//$mail->setFrom( $mail->Username , $sender_fullname );
			$mail->setFrom( $sender_email , $sender_fullname );

			//Set an alternative reply-to address
			//$mail->addReplyTo($mail->Username , $project_title);
			$mail->addReplyTo($reply_email , $project_title);
			
			//Set the subject line
			$mail->Subject = $subject;

			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			
			$mail->msgHTML( $message );

			//Replace the plain text body with one created manually
			//$mail->AltBody = 'This is a plain-text message body';

			//Attach an image file
			if( isset( $settings['attachments'] ) && is_array( $settings['attachments'] ) && ! empty( $settings['attachments'] ) ){
				foreach( $settings['attachments'] as $attach ){
					$mail->addAttachment( $attach );
				}
			}
			//$mail->addAttachment('images/phpmailer_mini.png');

			//send the message, check for errors
			//print_r( $mail );
			
			if( ! $mail->send() ){
				$response = "Mailer Error: " . $mail->ErrorInfo;
				$status = 0;
			}else{
				$status = 1;
				$response = "Message sent!";
			}
		}else{
			$response = "Invalid Recipient Email Address";
			$status = 0;
		}
		
		//echo $mail->ErrorInfo; exit;
	}
    //return $response;
	
	//Activate to send mail to email account when testing online
	//$status = $email_address_count;
	if( $email_address_count ){
		//$status = mail( $email , $subject , $message , $headers );
	}
	
	$f = $email;
	if( strlen( $email ) > 50 )$f = "";
	$file_name = $f.date("jS M Y H i").rand(1,9999).'.php';
	create_folder( '' , $pagepointer.'tmp/sent_mails' , '' );
	
	if( ! ( isset( $_SESSION["nwp_clr_mals"] ) && $_SESSION["nwp_clr_mals"] ) ){
		$_SESSION["nwp_clr_mals"] = 1;
		$start_time = date("U") - (3600*24*8);
		foreach( glob( $pagepointer.'tmp/sent_mails/' . "*.php" ) as $filename ) {
			if ( is_file( $filename ) ) {
				if( filemtime( $filename ) <= $start_time ){
					unlink( $filename );
				}
			}
		}
	}
	
	$stored_mail2 = json_encode( $stored_mail );
	if( function_exists("nwp_enc22") ){
		$stored_mail2 = nwp_enc22( $stored_mail2, 1 );
	}
	file_put_contents( $pagepointer.'tmp/sent_mails/' . $file_name , $stored_mail2 );
	if( get_hyella_development_mode() ){
		$response .= '<br /><a href="'.$project["domain_name"].'pmail.php?s='. rawurlencode( $pagepointer.'tmp/sent_mails/' . $file_name ) .'" target="_blank" title="View Mail">View Mail</a>';
	}
	$stop_key = "emails" . date( "d-m-Y-H" );
	$cache = get_cache_for_special_values( array( 'cache_key' => $stop_key, 'directory_name' => "emails", 'permanent' => true ) );
	if( ! is_array( $cache ) ){
		$cache = array();
	}
	$cache[] = array( "recipient" => $name." ".$email, "time" => date( "d-M-Y H:i" ), "subject" => $subject, "id" => $file_name, "status" => $status );
	
	set_cache_for_special_values( array( 'directory_name' => "emails", 'permanent' => true, 'cache_key' => $stop_key, 'cache_values' => $cache ) );
	
	return array( "status" => $status, "response" => $response, "cache_key" => $stop_key, "date" => date( "d-M-Y H:i" ) );
}

function send_mail_sendgrid( $sender_email, $sender_fullname, $subject, $message, $sg_rep, $settings ){
	$header = array();
	
	//$endpoint = 'https://api.sendgrid.com/v3/mail/send';
	$endpoint = NWP_SEND_GRID_URL;
	//$sender_email = 'pogbuitepu@challydoff.com';
	$url = $endpoint;
	
	//$header[] = "Host: " . $servername;
	$header[] = "content-type: application/json";
	$header[] = "Authorization: Bearer " . NWP_SEND_GRID_TOKEN;
	
	$ch = curl_init();
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	
	curl_setopt($ch,CURLOPT_POST, true );
	//curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode( $post_fields ) );
	
	$p2 = array();
	$p2["personalizations"][] = $sg_rep;
	//$p2["personalizations"][] = array( "to" => array( array( "email" => $mails, "name" =>  ) ), "bcc" => array( array( "email" => $admin_email ) ) );
	$p2["from"]["name"] = $sender_fullname;
	$p2["from"]["email"] = $sender_email;
	$p2["subject"] = $subject;
	$p2["content"][] = array( "type" => "text/html", "value" => $message );
	
	//print_r( $p2 ); exit;
	//curl_setopt($ch,CURLOPT_POSTFIELDS, '{"personalizations": [{"to": [{"email": "'.$mails.'"}], "bcc":[{"email": "'.$admin_email.'"}]}],"from": {"email": "'.$sender_email.'"},"subject": "'.$subject.'","content": [{"type": "text/html", "value": "'. htmlentities( $message ) .'"}]}' );
	curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode( $p2 ) );
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header );  
	//curl_setopt($ch, CURLOPT_HEADER, 1);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
	//execute post
	$result = curl_exec($ch);
	if( isset( $settings["debug"] ) && $settings["debug"] ){
		echo $result; exit;
	}
	
	//close connection
	curl_close($ch);
	return $result;
}

function send_mail2( $settings = array() ){
	//return 1;
	
	$pagepointer = '';
	if( isset( $settings['pagepointer'] ) )
		$pagepointer = $settings['pagepointer'];
		
	$recipient_emails = array();
	if( isset( $settings['recipient_emails'] ) )
		$recipient_emails = $settings['recipient_emails'];
		
	$recipient_fullnames = array();
	if( isset( $settings['recipient_fullnames'] ) )
		$recipient_fullnames = $settings['recipient_fullnames'];
		
	$subject = '';
	if( isset( $settings['subject'] ) )
		$subject = $settings['subject'];
		
	$message = '';
	if( isset( $settings['message'] ) ){
        $message = $settings['message'];
        /*
		if( file_exists( $pagepointer . 'css/email-notification.css' ) ){
			$message .= '<style>' . file_get_contents( $pagepointer . 'css/email-notification.css' ) . '</style>';
		}
		$message .= '<div id="message-content">';
		$message .= '<div id="message-content-header">Gas Helix - Notification</div>';
		$message .= '<div id="message-content-body">' . $settings['message'] . '</div>';
		$message .= '</div>';
        */
	}
	
	$sender_email = '';
	if( isset( $settings['sender_email'] ) )
		$sender_email = $settings['sender_email'];
		
	$sender_fullname = '';
	if( isset( $settings['sender_fullname'] ) ){
		$sender_fullname = $settings['sender_fullname'];
	}
    
	$project = get_project_data();
	
	$project_title = '';
	if( isset( $project['project_title'] ) )
		$project_title = $project['project_title'];
	
    if( ! $sender_fullname )$sender_fullname = $project_title;
    
	$admin_email = 'pat3echo@gmail.com';
	if( isset( $project['admin_email'] ) ){
		$admin_email = $project['admin_email'];
	}
	
	if( function_exists("get_project_admin_email") ){
		$admin_email = get_project_admin_email();
	}
	
	$smtp_auth_email = '';
	if( isset( $project['smtp_auth_email'] ) )
		$smtp_auth_email = $project['smtp_auth_email'];
	
	if( function_exists("get_project_smtp_auth_email") ){
		$smtp_auth_email = get_project_smtp_auth_email();
	}
	
	$reply_email = $admin_email;
	if( isset( $project['reply_to_email'] ) && $project['reply_to_email'] ){
		$reply_email = $project['reply_to_email'];
	}
	
	if( function_exists("get_project_reply_to_email") ){
		$reply_email = get_project_reply_to_email();
	}
	
	$sender_email = $smtp_auth_email;
	if( isset( $project['sender_email'] ) && $project['sender_email'] ){
		$sender_email = $project['sender_email'];
	}
	
	if( function_exists("get_project_sender_email") ){
		$sender_email = get_project_sender_email();
	}
	//$sender_email = "no-reply@hyella.com";
	//$sender_email = $smtp_auth_email;
	
	$smtp_auth_password = '';
	if( isset( $project['smtp_auth_password'] ) )
		$smtp_auth_password = $project['smtp_auth_password'];
	
	if( function_exists("get_project_smtp_auth_password") ){
		$smtp_auth_password = get_project_smtp_auth_password();
	}
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: ';
	
	$email = "";
	$name = "";
	$email_address_count = 0;
	foreach( $recipient_emails as $_id => $_email ){
		if( $_email ){				
			if( $email_address_count ){
				$headers .= ', '.$recipient_fullnames[$_id].' <'.$_email.'>';
				$email .= ', ' . $_email;
				$name .= ', ' . $recipient_fullnames[$_id];
			}else{
				$headers .= $recipient_fullnames[$_id].' <'.$_email.'>';
				$email = $_email;
				$name = $recipient_fullnames[$_id];
			} 
			
			++$email_address_count;
		}
	}
	$headers .= "\r\n";
	
	$headers .= 'From: '.$sender_fullname.' <'.$sender_email.'>' . "\r\n";
	$headers .= 'Bcc: '.$admin_email. "\r\n";
	
	if( ! $email_address_count ){
		$message = "Invalid Email Address";
		$subject = "Invalid Email Address, Message Not Sent";
	}
	
	//Write mail to tmp folder for reference
	$stored_mail = '<?php echo "<h1>Access Denied</h1>";'."\n\n";
		$stored_mail .= '$email = "'.implode(' , ' , $recipient_emails).'";'."\n\n";
		$stored_mail .= '$subject = "' . addslashes( $subject ) . '";'."\n\n";
		$stored_mail .= '$message = "' . addslashes( $message ) . '";'."\n\n";
		$stored_mail .= '$headers = "' . addslashes( $headers ) . '";'."\n\n";
		$stored_mail .= '$timestamp = "'.date("U").'";'."\n\n";
	$stored_mail .= '?>';
	
	$response = "Mailer Unsent";
	$status = 0;
		
	//Activate to send mail to email account when testing online
	$mails = '';
	if( ! ( defined("HYELLA_ENABLE_EMAILS") && HYELLA_ENABLE_EMAILS ) ){
		$status = 1;
		$subject = 'TEST EMAIL: ' . $subject;
	}else if( file_exists( $pagepointer.'classes/PHPMailer-master/PHPMailerAutoload.php' ) ){
		
        require_once $pagepointer.'classes/PHPMailer-master/PHPMailerAutoload.php';

		//Create a new PHPMailer instance
		$mail = new PHPMailer();

		//Tell PHPMailer to use SMTP
		$mail->isSMTP();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 2;

		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';

		//Set the hostname of the mail server
		//$mail->Host = 'smtp.gmail.com';
		//$mail->Host = 'ssl://smtp.gmail.com'; - not in use
		//$mail->Host = 'smtp.ipage.com';
		$mail->Host = 'mail.lagosarchdiocese.org';
		
		
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		//$mail->Port = 587;
		//$mail->Port = get_project_smtp_port();
		$mail->Port = 465;

		//Set the encryption system to use - ssl (deprecated) or tls
		//$mail->SMTPSecure = 'tls';
		$mail->SMTPSecure = 'ssl';

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = 'info@lagosarchdiocese.org';

		//Password to use for SMTP authentication
		$mail->Password = 'Password@2017';

		//Set who the message is to be sent from
		$mail->setFrom( 'info@habeeb.com' , 'HABEEB' );

		//Set an alternative reply-to address
		//$mail->addReplyTo($reply_email , $project_title);
		
		
		//Set who the message is to be sent to
		$mail->addAddress( 'pat2echo@gmail.com' , 'PATRICK 2' );
		//Set the subject line
		$mail->Subject = 'DAMPLE 1';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$message .= '<br /><br /><img style="width:1px; height:1px; float:right;" src="http://newsletter.hyella.com/tracked/?email='.urlencode( strtolower( $mails ) ).'&msg='.urlencode( $subject ).'"/><div style="font-size: 12px; text-align: center; margin-top: 20px;">(C) '.date("d-M-Y").' <a href="http://www.hyella.com/" target="_blank">'.$project_title . ' - ' . $project["app_title"] . '</a></div>';
		
		$mail->msgHTML( $message );

		//Replace the plain text body with one created manually
		//$mail->AltBody = 'This is a plain-text message body';

		//Attach an image file
		if( isset( $settings['attachments'] ) && is_array( $settings['attachments'] ) && ! empty( $settings['attachments'] ) ){
			foreach( $settings['attachments'] as $attach )
				$mail->addAttachment( $attach );
		}
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		
		if ( ! $mail->send()) {
			$response = "Mailer Error: " . $mail->ErrorInfo;
			$status = 0;
		} else {
			$status = 1;
			$response = "Message sent!";
		}
		
	}
	echo $response;
    //return $response;
	
}

function get_select_option_value( $settings = array() ){
	if( isset( $settings[ 'id' ] ) && $settings[ 'id' ] && isset( $settings[ 'function_name' ] ) && $settings[ 'function_name' ] ){
		
		if( function_exists( $settings[ 'function_name' ] ) ){
			if( isset( $settings[ 'settings' ] ) && $settings[ 'settings' ] ){
				$primary_categories = $settings[ 'function_name' ]( $settings[ 'settings' ] );
			}else{
				$primary_categories = $settings[ 'function_name' ]();
			}
		
			if( isset( $primary_categories[ $settings[ 'id' ] ] ) ){
				return $primary_categories[ $settings[ 'id' ] ];
			}
		
		}
	}
	
	return 'not available';
}

function get_records_from_database( $pagepointer, $app ){
	return 1;
	
	set_time_limit(0); //Unlimited max execution time
	
	$connection = 0;
	$connection = check_for_internet_connection();
	if( $connection == 1 ){
		//check if license has been renewed
		$version = get_application_version( $pagepointer );
		$mac_address = get_mac_address();
		$s = file_get_contents( "http://ping.northwindproject.com/l/?renewal_check=1&project=" . $app . "&mac_address=".$mac_address."&app_version=".$version );
		
		$ss = explode( ":::", $s );
		if( isset( $ss[0] ) && isset( $ss[1] ) && $ss[1] && isset( $ss[2] ) && $ss[2] ){
			$settings = array(
				'cache_key' => $app,
				'cache_values' => $ss[1],
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$settings = array(
				'cache_key' => $app."-last",
				'cache_values' => $ss[2],
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			if( isset( $_SESSION["skip_payload_download"] ) && $_SESSION["skip_payload_download"] ){
				unset( $_SESSION["skip_payload_download"] );
				return 1;
			}
			$path = $pagepointer . 'classes/' . $app . '.zip';
			
			/* Source File Name and Path */
			$remote_file = $ss[0];
			$local_file = $path;
			
			$s1 = file_get_contents( "http://ping.northwindproject.com/data/" . $ss[0] );
			
			/* Download $remote_file and save to $local_file */
			if ( $s1 ) {
				file_put_contents( $local_file, $s1 );
				
				$f = $path;
				$path1 = pathinfo( realpath( $f ), PATHINFO_DIRNAME );
				 
				$zip = new ZipArchive;
				$res = $zip->open($f);
				if ($res === TRUE) {
					$zip->extractTo( $path1 );
					$zip->close();
					
					ftp_close( $connect_it );
					
					unlink( $f );
					file_get_contents( "http://ping.northwindproject.com/l/?project=".$app."&delete=" . $remote_file  . "&mac_address=".$mac_address."&app_version=".$version );
					
					return 1;
				}else{
					ftp_close( $connect_it );
					return "Zip Archive Problem";
				}
			}else {
				return "File Not Found / FTP Connection Problem";
			}
		}else{
			unlink( $pagepointer . "license.hyella" );
			rebuild();
			exit;
		}
	}else{
		return "No Internet Connection";
	}
}

function create_report_directory( $settings = array(), $opt = array() ){
	
	$entity_directory = 'files';
	
	if( isset( $settings[ 'calling_page' ] ) && isset( $settings[ 'user_id' ] ) ){
		$directory = $settings[ 'calling_page' ] . $entity_directory . '/' . $settings[ 'user_id' ];
		$dir = create_folder('' , $directory , '' );
		
		//Create User Folder
		if( isset( $_POST[ 'current_module' ] ) && $_POST[ 'current_module' ] ){
			$directory = $directory . '/' . $_POST[ 'current_module' ];
			$dir = create_folder('' , $directory , '' );
		}
		
		//Check year
		$directory = $directory . '/' . date("Y");
		$dir = create_folder('' , $directory , '' );
			
		//Check month
		$md = date("F");
		if( isset( $opt[ 'add_day' ] ) && $opt[ 'add_day' ] ){
			$md = date("F") . ' - ' . date("m.d.y");
		}
		$directory = $directory . '/' . $md;
		$dir = create_folder('' , $directory , '' );
		
		return $dir;
	}
	
}

function load_language_file( $settings ){
	//print_r( $settings ); exit;
	if( isset( $settings['id'] ) && isset( $settings['pointer'] ) && isset( $settings['language'] ) ){
		
		if( file_exists( $settings['pointer'] . "locale/" . $settings['language'] . "/" . strtoupper( $settings['id'] ) . ".php" ) ){
			include $settings['pointer'] . "locale/" . $settings['language'] . "/" . strtoupper( $settings['id'] ) . ".php";
			return 1;
		}else{
			//load default language file
			return 0;
			//die("No language file");
		}
		
	}
}

function get_successful_authentication_url(){
	if( isset( $_SESSION[ 'successful_authentication_url'] ) ){
		$return = $_SESSION[ 'successful_authentication_url'];
		unset( $_SESSION[ 'successful_authentication_url'] );
		return $return;
	}
		
	return '?page=user-dashboard';
}

function set_successful_authentication_url( $url ){
	$_SESSION[ 'successful_authentication_url'] = $url;
}


function generatePassword($plength,$include_letters,$include_capitals,$include_numbers,$include_punctuation){

    // First we need to validate the argument that was given to this function
    // If need be, we will change it to a more appropriate value.
    if(!is_numeric($plength) || $plength <= 0)
    {
        $plength = 8;
    }
    if($plength > 32)
    {
        $plength = 32;
    }

    // This is the array of allowable characters.
            $chars = "";

            if ($include_letters == true) { $chars .= 'abcdefghijklmnopqrstuvwxyz'; }
            if ($include_capitals == true) { $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
            if ($include_numbers == true) { $chars .= '0123456789'; }
            if ($include_punctuation == true) { $chars .= '`¬£$%^&*()-_=+[{]};:@#~,<.>/?'; }

            // If nothing selected just display 0's
            if ($include_letters == false AND $include_capitals == false AND $include_numbers == false AND $include_punctuation == false) {
                $chars .= '0';
            }

    // This is important:  we need to seed the random number generator
    mt_srand(doubleval( microtime() ) * 1000000);
    // mt_srand(intval( microtime() ) * 1000000);

    // Now we simply generate a random string based on the length that was
    // requested in the function argument
	$pwd = '';
    for($i = 0; $i < $plength; $i++)
    {
        $key = mt_rand(0,strlen($chars)-1);
        $pwd = $pwd . $chars[$key];
    }

    // Finally to make it a bit more random, we switch some characters around
    for($i = 0; $i < $plength; $i++)
    {
        $key1 = mt_rand(0,strlen($pwd)-1);
        $key2 = mt_rand(0,strlen($pwd)-1);

        $tmp = $pwd[$key1];
        $pwd[$key1] = $pwd[$key2];
        $pwd[$key2] = $tmp;
    }

    // Convert into HTML
    $pwd = htmlentities($pwd, ENT_QUOTES);

    return $pwd;
}


function get_from_cached( $fsettings = array() ){
	if( isset( $fsettings[ 'cache_key' ] ) ){
		/*
		switch( $fsettings[ 'cache_key' ] ){
		case 'categories':
		break;
		}
		*/
		$cache_key = $fsettings[ 'cache_key' ];
		$settings = array(
			'cache_key' => $cache_key,
			'permanent' => true,
		);
		
		if( isset( $fsettings[ 'directory_name' ] ) && $fsettings[ 'directory_name' ] )
			$settings[ 'directory_name' ] = $fsettings[ 'directory_name' ];
		
		if( isset( $fsettings[ 'data' ] ) && $fsettings[ 'data' ] )
			$settings[ 'data' ] = $fsettings[ 'data' ];
		
		if( isset( $fsettings[ 'clear' ] ) && $fsettings[ 'clear' ] ){
			clear_cache_for_special_values( $settings );
			return 0;
		}
		
		//CHECK IF CACHE IS SET
		return get_cache_for_special_values( $settings );
	}
}

function get_general_settings_value( $settings = array() ){
	if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && $settings[ 'key' ] ){
		$general_settings = get_from_cached( array( 'cache_key' => 'general_settings' ) );
		
		if( isset( $settings[ 'country' ] ) && $settings[ 'country' ] && isset( $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ $settings[ 'country' ] ] ) ){
			return $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ $settings[ 'country' ] ];
		}
		
		if( isset( $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ 'default' ] ) ){
			return $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ][ 'default' ];
		}
	}
	
	return 0;
}

function get_project_settings_value( $settings = array() ){
	if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && $settings[ 'key' ] ){
		$cache_key = 'project_settings-'.strtolower( str_replace(' ', '_', $settings[ 'key' ]) );
		$ps_settings = get_from_cached( array( 'cache_key' => $cache_key, 'directory_name' => 'project_settings' ) );
		if( isset( $ps_settings[ 'key' ] ) && ( isset($ps_settings['value']) && $ps_settings['value'] ) ){
			return $ps_settings;
		}
	}
	
	return 0;
}

function get_general_settings_value_array( $settings = array() ){
	if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && is_array( $settings[ 'key' ] ) ){
		$general_settings = get_from_cached( array( 'cache_key' => 'general_settings' ) );
		
		foreach( $settings[ 'key' ] as $k =>  & $val ){
			if( isset( $settings[ 'country' ] ) && $settings[ 'country' ] && isset( $general_settings[ $settings[ 'table' ] ][ $k ][ $settings[ 'country' ] ] ) ){
				$val = $general_settings[ $settings[ 'table' ] ][ $k ][ $settings[ 'country' ] ];
			}else{
				if( isset( $general_settings[ $settings[ 'table' ] ][ $k ][ 'default' ] ) ){
					$val = $general_settings[ $settings[ 'table' ] ][ $k ][ 'default' ];
				}else{
					$val = '';
				}
			}
		}
		
		return $settings[ 'key' ];
	}
	
	return array();
}

function get_general_settings_object( $settings = array() ){
	if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && $settings[ 'table' ] && $settings[ 'key' ] ){
		$general_settings = get_from_cached( array( 'cache_key' => 'general_settings' ) );
		
		if( isset( $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ] ) ){
			return $general_settings[ $settings[ 'table' ] ][ $settings[ 'key' ] ];
		}
	}
	return 0;
}

function get_paynow_button( $settings = array() ){
	if( isset( $settings[ 'table' ] ) && isset( $settings[ 'key' ] ) && isset( $settings[ 'price' ] ) && $settings[ 'table' ] && $settings[ 'key' ] && $settings[ 'price' ] ){
		$table = $settings[ 'table' ];
		$price = $settings[ 'price' ];
		$key = $settings[ 'key' ];
		
		$caption = 'Pay Now';
		if( isset( $settings[ 'caption' ] ) && $settings[ 'caption' ] )
			$caption = $settings[ 'caption' ];
		
		$title = 'Pay Now';
		if( isset( $settings[ 'title' ] ) && $settings[ 'title' ] )
			$title = $settings[ 'title' ];
			
		$desc = '';
		if( isset( $settings[ 'desc' ] ) && $settings[ 'desc' ] )
			$desc = strip_tags( $settings[ 'desc' ] );
			
		$class = 'btn-primary ';
		if( isset( $settings[ 'class' ] ) && $settings[ 'class' ] )
			$class .= $settings[ 'class' ];
			
		$pagepointer = '';
		if( isset( $settings[ 'pagepointer' ] ) && $settings[ 'pagepointer' ] )
			$pagepointer = $settings[ 'pagepointer' ];
			
		//define what is being paid for
		$html = '<form method="get" action="'.$pagepointer.'engine/php/site_request_processing_script.php?" id="pay-now-navigation" class="form-navigate">';
			$html .= '<input type="hidden" name="action" value="checkout" />';
			$html .= '<input type="hidden" name="todo" value="billing_and_shipping_page" />';
			$html .= '<input type="hidden" name="table" value="'.$table.'" />';
			$html .= '<input type="hidden" name="price" value="'.$price.'" />';
			$html .= '<input type="hidden" name="key" value="'.$key.'" />';
			$html .= '<input type="hidden" name="desc" value="'.$desc.'" />';
			$html .= '<input type="submit" class="white-text btn '.$class.'" value="'.$caption.'" title="'.$title.'" />';
		$html .= '</form>';
		
		return $html;
	}
} 

function get_user_geolocation_data(){
	//echo 'Set-up geoip_record_by_name in all_other_general_function.php on line 2910';
    return array();
    //$r = geoip_record_by_name( '154.120.71.175' );
    $ip = '';
    if( isset( $_SESSION['temp_location']['ip_address'] ) && isset( $_SESSION['temp_location']['country_name'] ) ){
        return $_SESSION['temp_location'];
    }else{
       $ip = get_ip_address();
    }
    
	$r = geoip_record_by_name( $ip );
	if( isset( $r['country_name'] ) && isset( $r['country_code'] ) && isset( $r['region'] ) && isset( $r['city'] ) ){
		//get country id from name
        $countries = get_countries();
        $c_id = '';
        foreach( $countries as $id => $val ){
            if( strtolower( trim( $val ) ) == strtolower( trim( $r['country_name'] ) ) ){
                $c_id = $id;
                break;
            }
        }
        $_SESSION['temp_location'] = array(
			'country' => $r['country_name'],
			'country_id' => $c_id,
			'country_code' => $r['country_code'],
			
			'state_id' => '',
			'state_code' => '',
			'state' => $r['region'],
			
			'city' => $r['city'],
            'ip_address' => $ip,
		);
        return $_SESSION['temp_location'];
	}
	return array();
}

function interprete_website_page_url(){
	
	if( isset( $_GET['page'] ) && $_GET['page'] ){
		switch( $_GET['page'] ){
		case "register":
			$frame_id = $_GET['page'];
			
			$action = 'site_users';
			$todo = 'site_registration';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "welcome-new-user":
			$frame_id = $_GET['page'];
			
			$action = 'welcome_new_user';
			$todo = 'dashboard_welcome_message';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "store-page":
			$frame_id = $_GET['page'];
			
			$action = 'store';
			$todo = 'site_store_page';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "user-dashboard":
			$frame_id = $_GET['page'];
			
			$action = 'welcome_new_user';
			$todo = 'user_dashboard';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "print-leave_roaster":
		case "print-vendor_bill":
		case "print-project_expense":
		case "print-expenditure-manifest":
		case "print-transaction-draft":
		case "print-transaction":
		case "print-manifest":
		case "print-invoice":
		case "print-lab_request":
		case "print-prescription":
		case "print-hotel-invoice":
		case "print-hotel-bill-group":
		case "print-hotel-bill":
		case "print-appraisal":
		case "book-event":
		case "print-barcode":
		case "customer_details_print":
			$frame_id = $_GET['page'];
			
			$action = 'website';
			$todo = $_GET['page'];
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			
			
            $getparams = '';
            foreach( $_GET as $key => $val ){
                if($getparams)$getparams .= '&'.$key.'='.$val;
                else $getparams = $key.'='.$val;
            }
            $frame_src .= '&'.$getparams;
			
			/*
			if( isset( $_GET['record_id'] ) && $_GET['record_id'] )
				$frame_src .= '&record_id='.$_GET['record_id'];
			*/
		break;
		case "view-my-profile":
			$frame_id = $_GET['page'];
			
			$action = 'site_users';
			$todo = 'display_user_details';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "login":
			$frame_id = $_GET['page'];
			
			$action = 'site_users';
			$todo = 'site_users_authentication';
			
            $getparams = '';
            foreach( $_GET as $key => $val ){
                if($getparams)$getparams .= '&'.$key.'='.$val;
                else $getparams = $key.'='.$val;
            }
            
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
		break;
		case "google-login":
			$frame_id = $_GET['page'];
			unset( $_GET['page'] );
            
			$action = 'site_users';
			$todo = 'site_users_google_authentication';
            
            $getparams = '';
            foreach( $_GET as $key => $val ){
                if($getparams)$getparams .= '&'.$key.'='.$val;
                else $getparams = $key.'='.$val;
            }
            
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
		break;
		case "facebook-login":
			$frame_id = $_GET['page'];
			unset( $_GET['page'] );
            
			$action = 'site_users';
			$todo = 'site_users_facebook_authentication';
            
            $getparams = '';
            foreach( $_GET as $key => $val ){
                if($getparams)$getparams .= '&'.$key.'='.$val;
                else $getparams = $key.'='.$val;
            }
            
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo . '&' . $getparams;
		break;
		case "reset-password":
			$frame_id = $_GET['page'];
			
			$action = 'site_users';
			$todo = 'site_users_reset_password';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "inventory-manager":
			$frame_id = $_GET['page'];
			
			$action = 'product';
			$todo = 'site_inventory_manager';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "billing-and-shipping-page":
			$frame_id = $_GET['page'];
			
			$action = 'checkout';
			$todo = 'billing_and_shipping_page';
			
			$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
		break;
		case "sign-out":
			session_destroy();
            header('Location: ./');
		break;
		}
		
		if( ! isset( $frame_src ) ){
			$pages = get_website_pages();
			if( isset( $pages[ $_GET['page'] ]['page_name'] ) && $pages[ $_GET['page'] ]['page_name'] ){
				$frame_id = $_GET['page'];
				
				$todo = $pages[ $_GET['page'] ]['page_name'];
				$action = 'website';
				
				$frame_src = 'engine/php/site_request_processing_script.php?action=' . $action . '&todo=' . $todo;
			}
		}
		
	}
	
	if( isset( $action ) && isset( $todo ) ){
		$_GET['action'] = $action;
		$_GET['todo'] = $todo;
	}else{
        if( ! ( isset( $_GET['action'] ) && $_GET['action'] && isset( $_GET['todo'] ) && $_GET['todo'] ) ){
            $_GET['action'] = 'website'; 
            $_GET['todo'] = 'homepage';
        }
	}
}
function encrypt($data, $secret){
	return $data;
	//Generate a key from a hash
	$key = md5(utf8_encode($secret), true);

	//Take first 8 bytes of $key and append them to the end of $key.
	$key .= substr($key, 0, 8);

	//Pad for PKCS7
	$blockSize = mcrypt_get_block_size('tripledes', 'ecb');
	$len = strlen($data);
	$pad = $blockSize - ($len % $blockSize);
	$data .= str_repeat(chr($pad), $pad);

	//Encrypt data
	$encData = mcrypt_encrypt( 'tripledes', $key, $data, 'ecb' );

	return base64_encode($encData);
}

function decrypt($data, $secret){
	return $data;
	//Generate a key from a hash
	$key = md5(utf8_encode($secret), true);

	//Take first 8 bytes of $key and append them to the end of $key.
	$key .= substr($key, 0, 8);

	$data = base64_decode($data);

	$data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');

	$block = mcrypt_get_block_size('tripledes', 'ecb');
	$len = strlen($data);
	$pad = ord($data[$len-1]);

	return substr($data, 0, strlen($data) - $pad);
}

function tidy($html, $userConfig = FALSE ) {
	//return $html;
	// default tidyConfig. Most of these are default settings.
	$config = array(
		'show-body-only' => false,
		'clean' => true,
		'char-encoding' => 'utf8',
		'add-xml-decl' => true,
		'add-xml-space' => true,
		'output-html' => false,
		'output-xml' => false,
		'output-xhtml' => true,
		'numeric-entities' => false,
		'ascii-chars' => false,
		'doctype' => 'strict',
		'bare' => true,
		'fix-uri' => true,
		'indent' => true,
		'indent-spaces' => 4,
		'tab-size' => 4,
		'wrap-attributes' => true,
		'wrap' => 0,
		'indent-attributes' => true,
		'join-classes' => false,
		'join-styles' => false,
		'enclose-block-text' => true,
		'fix-bad-comments' => true,
		'fix-backslash' => true,
		'replace-color' => false,
		'wrap-asp' => false,
		'wrap-jste' => false,
		'wrap-php' => false,
		'write-back' => true,
		'drop-proprietary-attributes' => false,
		'hide-comments' => false,
		//'hide-endtags' => false,
		'literal-attributes' => false,
		'drop-empty-paras' => true,
		'enclose-text' => true,
		'quote-ampersand' => true,
		'quote-marks' => false,
		'quote-nbsp' => true,
		'vertical-space' => true,
		'wrap-script-literals' => false,
		'tidy-mark' => true,
		'merge-divs' => false,
		'repeated-attributes' => 'keep-last',
		'break-before-br' => true,
	);               
	
	if( is_array($userConfig) ) {
		$config = array_merge($config, $userConfig);           
	}

	if( class_exists("tidy") ){
		$tidy = new tidy();
		if( method_exists( $tidy, 'repairString' ) ){
			$output = $tidy->repairString($html, $config, 'UTF8');        
			return($output);
		}
	}
	
	return $html;
}

function reorder_fields_based_on_serial_number( $fields = array() , $form_label = array(), $options = array() ){
    
	$use_table_fields = isset( $options["use_table_fields"] )?$options["use_table_fields"]:0;
	
    $new_fields = array();
	//12-feb-23
    //$serial_num = is_array( $form_label ) && ! empty( $form_label ) ? count( $form_label ) + 100000 : 100000;
    $serial_num = is_array( $form_label ) && ! empty( $form_label ) ? count( $form_label ) + 10000 : 10000;

    foreach( $fields as $fk => $field_ids ){
        
		if( $use_table_fields ){
			$field_id = $field_ids;
		}else{
			$field_id = $field_ids[0];
		}
        
        
        if( $field_id == 'id' ){
            $new_fields[0] = $field_ids;
            continue;
        }
        $i = 1;
		
        if( isset( $form_label[$field_id]['serial_number'] ) && doubleval( $form_label[$field_id]['serial_number'] ) ){
            //12-feb-23
			$i = doubleval( $form_label[$field_id]['serial_number'] ) * 100000;
            //$i = doubleval( $form_label[$field_id]['serial_number'] ) * 1000000;
            $new_fields[ intval( $i ) ] = $field_ids;
        }else{
            $new_fields[ $serial_num ] = $field_ids;
        }
        
        $serial_num += $i;
    }
            // print_r( $new_fields );exit();

    ksort($new_fields);
	
	if( $use_table_fields ){
		$xfields = array();
		foreach( $fields as $k1 => $v1 ){
			$xfields[ $v1 ] = $k1;
		}
		
		$fields3 = array();
		foreach( $new_fields as $k2 => $v2 ){
			if( isset( $xfields[ $v2 ] ) ){
				$fields3[ $xfields[ $v2 ] ] = $v2;
			}
		}
		
		return $fields3;
	}
	
    return $new_fields;
}

function translate( $words ){
   //remove to enable translation
    return $words;
    
   $default_country_id = 'US'; 
   $lang = getenv("LANG");
   //$lang = 'FR';
   
   if( $lang && $default_country_id != $lang ){
        $return = get_words_translation( array( 'id' => md5( strtolower( trim( $words ) . $default_country_id . $lang ) ) ) );
        
        if( $return )return ucwords( $return );
   }
   
   return $words;
}

function translate_2( $settings = array() ){
    $default_country_id = 'US'; 
    $lang = getenv("LANG");
   
    $words = '';
    if( isset( $settings['words'] ) ){
        $words = $settings['words'];
    }
    
    //remove to enable translation
    return $words;
    
    if( isset( $settings['table'] ) && $settings['table'] && isset( $settings['field'] ) && $settings['field'] && isset( $settings['record_id'] ) && $settings['record_id'] ){
    
        if( $lang && $default_country_id != $lang ){
            
            $return = get_fields_translation( array( 'id' => md5( strtolower( $settings['table'] . $settings['field'] . $settings['record_id'] . $default_country_id . $lang ) ) ) );
        
            if( $return )return $return;
        }
        
    }
    
    return translate( $words );
}
    
function convert_kg_to_pounds_and_ounces($weight_in_kg = 1){
	//Convert Weight from kg to pounds and ounces
	$pounds = 0;
	$pounds = ($weight_in_kg * 2.20462262);
	
	$decimals = explode('.', $pounds);
	
	if(isset($decimals[0])){
		$pounds = $decimals[0];
	}
	
	$ounces = 0;
	if(isset($decimals[1])){
		$ounce = '0.'.$decimals[1];
		
		$ounces = (($ounce/1) * 16);
	}
	
	return array(
		'pounds'=>round($pounds),
		'ounces'=>round($ounces),
	);
}

function convert_cm_to_inches($dimension = 1){
	$inches = round($dimension * 0.393700787);
	
	return $inches;
}

function convert_table_name_to_code( $table_name ){
	$t = explode('_', $table_name);
    $code = '';
    foreach( $t as $tt ){
        if( isset( $tt[0] ) && $tt[0] )
            $code .= $tt[0];
    }
    return strtoupper($code);
}

function get_file_name_from_action( $action ){
	return str_replace('_', '-', $action );
}

function set_session_temp_storage( $key, $value ){
	$_SESSION[ 'tmp' ][ $key ] = $value;
}

function get_session_temp_storage( $key ){
	if( isset( $_SESSION[ 'tmp' ][ $key ] ) )
		return $_SESSION[ 'tmp' ][ $key ];
}

function get_report_content_header( $settings = array() ){
	$to = '';
	$ref = '';
	$from = '';
	$return = '';
	$logo = '';
	$blogo = '';
	$shake = '';
	
	if( isset( $settings['from'] ) && $settings['from']  ){
		$from = $settings['from'];
	}	
	if( isset( $settings['to'] ) && $settings['to'] ){
		$to = $settings['to'];
	}	
	if( isset( $settings['logo_url'] ) && $settings['logo_url'] ){
		if( isset( $settings['extension'] ) && $settings['extension'] == 'doc' ){
			$logo = '<img src="'.$settings['logo_url'].'" style="max-height:100px;" align="left" />';
			$shake = '&nbsp;&nbsp;';
		}else{
			$logo = '<img src="'.$settings['logo_url'].'" style="max-height:100px;" align="left" />';
			//$blogo = ' background:url('.$settings['logo_url'].') no-repeat 10px center; background-size:contain; min-height:60px; ';
		}
		//$logo = "";
		//$blogo = "";
	}
	
	if( isset( $settings['ref'] ) && $settings['ref'] )
		$ref = $settings['ref'];
	
	$address = '';
	if( isset( $settings['address'] ) && $settings['address'] ){
		$address = $settings['address'];
	}
		
	
	$pr = get_project_data();
	
	/*
	$return = '<div style="width:100%; font-size:24pt; text-align:center; position:relative; '.$blogo.'">'.$logo . strtoupper( $pr['project_title'] ) . $shake.'</div>
	<div style="width:100%; font-size:8pt; text-align:center; border-top:2px dotted #666; border-bottom:2px dotted #666;">'. strtoupper( $pr['app_title'] ) .' REPORT</div>';
	*/
	
	if( ! $logo ){
		$shake = strtoupper( $pr['project_title'] );
	}
	
	$return = '<table style="width:100%;">
		<tr>
			<td style="width:50%; '.$blogo.'" valign="middle">
			<div style="width:100%; font-size:24pt; position:relative;">'. $logo . $shake.'</div>
			</td>
			<td valign="top" align="right">'.$address.'</td>
		</tr>
	</table>';
	
	switch( get_package_option() ){
	case "catholic":
		$return = '<div style="width:100%; font-size:24pt; text-align:center; position:relative; ">' . strtoupper( $pr['project_title'] ) . $shake.'</div>
		<div style="width:100%; font-size:8pt; text-align:center; border-top:2px dotted #666; border-bottom:2px dotted #666;">'. strtoupper( $pr['app_title'] ) .' REPORT</div><br />';
	break;
	}
	
	if( $ref ){
		$return .= '<div style="font-size:12pt;"><table border="0" style="border-color:#fff; width:100%; margin-top:20px;">	<tbody>		<tr>			<td style="border:0; width:5%; font-weight:bold;  font-size:11pt;">From:			</td>			<td style="border-top:0; border-bottom:1px solid #333; border-left:0; border-right:0; width:40%; font-size:9pt;">			'.$from.'				</td>			<td style="border:0;width:4%;">				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			</td>			<td style="border:0;  width:5%;  font-weight:bold;  font-size:11pt;">				Ref:			</td>			<td style="border-top:0; border-left:0; border-right:0; border-bottom:1px solid #333; width:40%; font-size:9pt;">				'.$ref.'			</td>		</tr>		<tr>			<td style="border:0; width:5%; font-weight:bold;  font-size:11pt;">To:			</td>			<td style="border-top:0; border-bottom:1px solid #333; border-left:0; border-right:0; width:40%; font-size:9pt;">			'.$to.'				</td>			<td style="border:0;width:4%; ">				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			</td>			<td style="border:0;  width:5%;  font-weight:bold;  font-size:11pt;">				Date:			</td>			<td style="border-top:0; border-left:0; border-right:0; border-bottom:1px solid #333; width:40%; font-size:9pt;">			'.date("d-M-Y").'				</td>		</tr>	</tbody></table></div><br />';
	}
	
	return $return;
}

function get_export_and_print_popup( $table_id, $table_container, $type = "true", $report_type = 0, $options = array() ){
	$hide1 = 0;
	$hide2 = "";
	$lbl = "";

	// @steve 29-04-2024 Feature switch to enable the Signatory portions of the export component
	$hide_signatories = isset($options['hide_signatories']) && $options['hide_signatories'] ? $options['hide_signatories'] : 0;

	
	// print_r( $options );exit;
	$report_type = 1;
	switch( $report_type ){
	case 1:
		$hide1 = 1;
		$hide2 = " display:none; ";
		$lbl = "Export ";
	break;
	default:
	break;
	}
	
	if( ! ( defined("HYELLA_SAVE_SHARE_REPORT") && HYELLA_SAVE_SHARE_REPORT ) ){
		$options["save"] = 0;
		$options["insert"] = 0;
		$options["share"] = 0;
		$options["full_screen"] = 0;
	}
	/* 
	if( ! ( defined("HYELLA_EMAIL_REPORT") && HYELLA_EMAIL_REPORT ) ){
		$options["send_mail"] = 0;
	}
	 */
	$returning_html_data = '<span class="report-buttons-con">';
	
	//05-feb-22
	if( isset( $options["before_html"] ) && $options["before_html"] ){
		$returning_html_data .= $options["before_html"];
	}
	
	if( isset( $options["auto_load"] ) && $options["auto_load"] ){
		$returning_html_data .= '<button href="#" class="btn btn-sm btn-default hidden-print" onclick="$.fn.cProcessForm.nw_auto_load_link();"  title="Expand All Summaries" ><i class="icon-expand"></i>&nbsp;Expand All';
		$returning_html_data .= '</button>';
	}
	
	if( isset( $options["dcsv"] ) && $options["dcsv"] ){
		$returning_html_data .= '<button href="#" class="btn btn-sm btn-default hidden-print" onclick="$.fn.cProcessForm.downloadCSVfromHTMLTable('. "'". str_replace('#','', $table_container ) ."'" .');"  title="Download CSV File" ><i class="icon-download"></i>&nbsp;CSV';
		$returning_html_data .= '</button>';
		/*
		exportToCsv: function(filename, rows) {
			var processRow = function (row) {
				var finalVal = '';
				for (var j = 0; j < row.length; j++) {
					var innerValue = row[j] === null ? '' : row[j].toString();
					if (row[j] instanceof Date) {
						innerValue = row[j].toLocaleString();
					};
					var result = innerValue.replace(/"/g, '""');
					if (result.search(/("|,|\n)/g) >= 0)
						result = '"' + result + '"';
					if (j > 0)
						finalVal += ',';
					finalVal += result;
				}
				return finalVal + '\n';
			};

			var csvFile = '';
			for (var i = 0; i < rows.length; i++) {
				csvFile += processRow(rows[i]);
			}

			var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
			if (navigator.msSaveBlob) { // IE 10+
				navigator.msSaveBlob(blob, filename);
			} else {
				var link = document.createElement("a");
				if (link.download !== undefined) { // feature detection
					// Browsers that support HTML5 download attribute
					var url = URL.createObjectURL(blob);
					link.setAttribute("href", url);
					link.setAttribute("download", filename);
					link.style.visibility = 'hidden';
					document.body.appendChild(link);
					link.click();
					document.body.removeChild(link);
				}
			}
		}
		*/
	}
	
	$show_excel = 1;
	if( isset( $options["copy"] ) && $options["copy"] ){
		$returning_html_data .= '<button href="#" class="btn btn-sm btn-default hidden-print" onclick="$.fn.cProcessForm.selectElementContents('. "'". str_replace('#','', $table_container ) ."'" .');"  title="Copy & Paste into MS Excel File" ><i class="icon-copy"></i>&nbsp;Copy';
		$returning_html_data .= '</button>';
		$show_excel = 0;
	}
	
	//disabled export to excel, as csv replaces this
	if( isset( $options["hide_excel"] ) && $options["hide_excel"] ){
		$show_excel = 0;
	}
	$subject = isset( $options["subject"] )?$options["subject"]:'';
	// $show_excel = 1;

	if( isset( $options["save"] ) && $options["save"] ){
		
		$returning_html_data .= '<button href="#" class="btn btn-sm hidden-print btn-default pull-rightx quick-print-record direct-print" target="'.$table_container.'" data-share="save" title="Save Report" ><i class="icon-save"></i> Save';
		$returning_html_data .= '</button>';
		
	}
	
	if( isset( $options["insert"] ) && $options["insert"] ){
		
		$returning_html_data .= '<button href="#" class="btn btn-sm btn-default hidden-print pull-rightx quick-print-record direct-print" target="'.$table_container.'" data-share="2" title="Insert into existing Report" >Insert into...';
		$returning_html_data .= '</button>';
	}
	
	if( isset( $options["share"] ) && $options["share"] ){
		
		$returning_html_data .= '<button href="#" class="btn btn-sm hidden-print btn-default pull-rightx quick-print-record direct-print" target="'.$table_container.'" data-share="share" title="Share Report" >Share <i class="icon-share"></i>';
		$returning_html_data .= '</button>';
	}
	
	if( isset( $options["send_mail"] ) && $options["send_mail"] ){
		$emails = isset( $options["emails"] )?$options["emails"]:'';
		$prompt = isset( $options["prompt"] )?$options["prompt"]:0;
		
		$returning_html_data .= '<button href="#" class="btn btn-sm btn-default hidden-print view-report-fullscreen quick-print-record direct-print" target="'.$table_container.'" data-email-subject="'.$subject.'" data-emails="'.$emails.'" data-prompt="'.$prompt.'" title="Send as Email" >&nbsp;Send as Email <i class="icon-mail"></i>&nbsp;';
		$returning_html_data .= '</button>';
	}
	
	//05-feb-22
	if( isset( $options["after_html"] ) && $options["after_html"] ){
		$returning_html_data .= $options["after_html"];
	}
	
	$returning_html_data .= '<div class="btn-group hidden-print">';
	//$returning_html_data .= '<a href="#" class="btn btn-sm btn-default print-report-popup pop-up-button" data-rel="popup" title="Configure Report Settings" data-toggle="popover" data-placement="bottom">&nbsp;'.$lbl.'<i class="icon-circle-arrow-down fa fa-arrow-circle-o-down"></i>&nbsp;';
	
	$returning_html_data .= '<a href="#" class="btn btn-sm btn-default print-report-popup dropdown-toggle" title="Configure Report Settings" data-toggle="dropdown" data-placement="bottom" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">&nbsp;'.$lbl.'<i class="icon-circle-arrow-down fa fa-arrow-circle-o-down"></i>&nbsp;';
	$returning_html_data .= '</a>';
	
		//$returning_html_data .= '<div class="pop-up-content" style="display:none;"><ul class="show-hide-column-con" style="padding:0; margin:0; list-style:none; max-height:260px; overflow-y:auto; text-align:left;">';
		$returning_html_data .= '<div class="popover-contentx dropdown-menu hold-on-click" ><ul class="show-hide-column-con-2" style="padding: 10px 20px; box-shadow: 1px 3px 5px #ddd; margin:0; list-style:none; max-height:260px; overflow-y:auto; text-align:left;">';
		
			$returning_html_data .= '<li><form method="get" action="" name="report_settings_form" class="report-settings-form">';
			
				if( ! $hide1 )$returning_html_data .= '<label class="radio form-check-label"><input type="radio" name="report_type" checked="checked" value="mypdf" class="form-check-input" /> Portable Document Format (pdf)</label>';
				if( $hide1 )$returning_html_data .= '<label class="radio form-check-label"><input type="radio" name="report_type" checked="checked" value="mypdf" class="form-check-input" /> Export Report for Print</label>';
				
				if( $show_excel ){
					$returning_html_data .= '<label class="radio form-check-label"><input type="radio" name="report_type" value="myexcel" class="form-check-input" /> Excel [<small>only this page</small>]</label>';
				}
				
				if( isset( $options["csv"] ) && $options["csv"] ){
					$returning_html_data .= '<label class="radio form-check-label"><input type="radio" name="report_type" value="csv" class="form-check-input" /> CSV [<small>all pages</small>]</label>';
				}
				
				if( ! $hide1 )$returning_html_data .= '<label class="radio form-check-label" ><input type="radio" name="report_type" value="browser-pdf" class="form-check-input" />Fast Browser-based (pdf)</label>';
				// print_r( $subject );exit;
				$returning_html_data .= '<input type="text" class="form-gen-element" name="report_title" placeholder="Report Title" value="'.$subject.'" style="display:block;" />';
				$returning_html_data .= '<input type="text" class="form-gen-element" name="report_sub_title" placeholder="Report Sub-title" style="display:block;" />';
				
				$returning_html_data .= '<label class="checkbox form-check-label" style="display:block;"><input type="checkbox" name="exclude_header" value="on" class="form-check-input" /> Hide Heading</label>';
				
				$returning_html_data .= '<hr />';
				
				if( ! $hide1 )$returning_html_data .= '<input type="button" class="btn btn-small advance-print-preview" target="'.$table_container.'" target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Preview" value="Preview Data" export-type="'.$type.'" /> ';

				if( isset( $options[ 'plugin' ] ) && $options[ 'plugin' ] )$returning_html_data .= '<input type="hidden" value="'. $options[ 'plugin' ] .'" class="hidden-fields" name="plugin" /> ';
				if( isset( $options[ 'current_tab' ] ) && $options[ 'current_tab' ] )$returning_html_data .= '<input type="hidden" value="'. $options[ 'current_tab' ] .'" class="hidden-fields" name="current_tab" /> ';
				if( isset( $options[ 'selected_id' ] ) && $options[ 'selected_id' ] )$returning_html_data .= '<input type="hidden" value="'. $options[ 'selected_id' ] .'" class="hidden-fields" name="selected_id" /> ';
				if( isset( $options[ 'current_store' ] ) && $options[ 'current_store' ] )$returning_html_data .= '<input type="hidden" value="'. $options[ 'current_store' ] .'" class="hidden-fields" name="current_store" /> ';
				
				//$returning_html_data .= '<input type="button" class="btn btn-small direct-print advance-print" target="'.$table_container.'"  target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Export Data" value="Print" />';
				
				if( isset( $options[ 'print_url' ] ) && $options[ 'print_url' ] ){
					$returning_html_data .= '<input type="hidden" name="print_url" value="'. $options[ 'print_url' ] .'" /><hr />';
				}

				$returning_html_data .= '<input type="button" class="btn btn-small btn-primary advance-print" target="'.$table_container.'"  target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Export Data" value="Export" /><hr />';
				
				if( isset( $options["hidden_fields"] ) && $options["hidden_fields"] ){
					foreach( $options["hidden_fields"] as $s ){
						$returning_html_data .= '<input type="hidden" class="form-gen-element hidden-fields" name="'.$s["name"].'" value="'.$s["value"].'" />';
					}
				}
				
				if( ! $hide1 )$returning_html_data .= '<select name="orientation" class="form-gen-element input-small" style="display:block;"><option value="portrait">Portrait</option><option value="landscape">Landscape</option></select>';
				
				if( ! $hide1 )$returning_html_data .= '<select name="paper" class="form-gen-element input-small" style="display:block;"><option value="a0">A0</option><option value="a1">A1</option><option value="a2">A2</option><option value="a3">A3</option><option value="a4" selected="selected">A4</option><option value="letter">LETTER</option><option value="legal">LEGAL</option><option value="ledger">LEDGER</option><option value="tabloid">TABLOID</option><option value="executive">EXECUTIVE</option></select>';
				
				if( ! $hide1 )$returning_html_data .= '<label class="checkbox"><input type="checkbox" name="report_show_user_info" value="on" checked="checked" style="display:block;"/>Show User Info</label>';
				
				if( ! $hide1 )$returning_html_data .= '<select class="form-gen-element" name="report_template" ><option>Select Report Template</option></select>';

				if( !$hide_signatories ){

					$returning_html_data .= '<input class="form-gen-element" type="number" name="report_signatories" placeholder="Number of Signatories" max="5" style="display:block;" /><br />';
					
					$returning_html_data .= '<fieldset id="report-signatory-fields"><legend style="font-size:1.1em; font-weight:bold;">Define Signatory Fields</legend>';
						$returning_html_data .= '<div class="input-group input-prepend"><span class="input-group-addon add-on">1</span><input class="form-control signatory-fields" name="report_signatory_field[]" type="text" placeholder="Field 1" value="Position" /></div>';
						
						$returning_html_data .= '<div class="input-prepend input-group"><span class="input-group-addon add-on">2</span><input class="form-control signatory-fields" name="report_signatory_field[]" type="text" placeholder="Field 2" value="Name" /></div>';
						
						$returning_html_data .= '<div class="input-group input-prepend"><span class="input-group-addon add-on">3</span><input class="form-control signatory-fields" name="report_signatory_field[]" type="text" placeholder="Field 3" value="Signature" /></div>';
						
						$returning_html_data .= '<div class="input-group input-prepend"><span class="input-group-addon add-on">4</span><input class="form-control signatory-fields" name="report_signatory_field[]" type="text" placeholder="Field 4" value="Date" /></div>';
						
					$returning_html_data .= '</fieldset>';
					
					$returning_html_data .= '<hr />';

				}
				
				
				if( ! $hide1 )$returning_html_data .= '<input type="button" class="btn btn-small advance-print-preview" target="'.$table_container.'" target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Preview" value="Preview Data" export-type="'.$type.'" /> ';
				
				//$returning_html_data .= '<input type="button" class="btn btn-small direct-print advance-print" target="'.$table_container.'"  target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Export Data" value="Print" />';
				if( !$hide_signatories ){
					$returning_html_data .= '<input type="button" class="btn btn-small btn-primary advance-print" target="'.$table_container.'"  target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Export Data" value="Export" /><hr />';
				}
				
				//$returning_html_data .= '<input type="button" class="btn btn-small btn-primary advance-print" target="'.$table_container.'"  target-table="'.$table_id.'" merge-and-clean-data="'.$type.'" title="Export Data" value="Export" /><hr />';
				
			$returning_html_data .= '</li></form>';
		$returning_html_data .= '</ul>';
	
	$returning_html_data .= '</div>';
	
	/* if( isset( $options["full_screen"] ) && $options["full_screen"] ){
		$returning_html_data .= '<button href="#" class="btn btn-sm btn-default view-report-fullscreen hidden-print pull-rightx quick-print-record direct-print" target="'.$table_container.'" title="View Report in Full Screen" >&nbsp;Full Screen <i class="icon-external-link"></i>&nbsp;';
		$returning_html_data .= '</button>';
	} */
	
	$returning_html_data .= '</span>';
		
	return $returning_html_data;
}

function get_recent_activity_type_customers(){
	return "customers";
}

function get_recent_activity_type_invoices(){
	return "invoices";
}

function get_recent_reports_type_cash_calls(){
	return "cash-calls-reports";
}

function get_recent_activity_type_tendering(){
	return "tendering-and-contracts";
}

function get_recent_activity_type_exploration(){
	return "exploration";
}

function get_recent_activity_type_geophysics_plan_and_actual_performance(){
	return "geophysics_plan_and_actual_performance";
}

function set_recent_activity( $settings = array() ){
	$dir = "recent-activities";
	if( ! isset( $settings["type"] ) )
		return 0;		//cash-calls | monthly-cash-calls | divisional-reports | tendering-and-contracts
	
	if( ! ( isset( $settings["data"] ) && is_array( $settings["data"] ) ) )
		return 0;
	
	if( ! ( isset( $settings["data"][ "date" ] ) ) )
		return 0;
	
	if( ! ( isset( $settings["data"][ "user_id" ] ) ) )
		return 0;
	
	if( ! ( isset( $settings["data"][ "serial_num_and_table" ] ) ) )
		return 0;
	
	//get this month recent activities keys
	$month = date("M-Y");
	
	$csettings = array(
		'cache_key' => $month . "-keys",
		'permanent' => true,
		'directory_name' => $dir."-".$settings["type"],
	);
	$cached_values = get_cache_for_special_values( $csettings );
	if( ! ( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ) ){
		$cached_values = array();
	}
	$cached_values[ doubleval( $settings["data"][ "date" ] ) ] = $month . "-" . $settings["data"][ "date" ] . $settings["data"][ "user_id" ] . $settings["data"][ "serial_num_and_table" ];
	
	$csettings["cache_values"] = $cached_values;
	set_cache_for_special_values( $csettings );
	
	//get TOP 50 recent activities keys
	$csettings = array(
		'cache_key' => "top-50-keys",
		'permanent' => true,
		'directory_name' => $dir."-".$settings["type"],
	);
	$cached_values = get_cache_for_special_values( $csettings );
	if( ! ( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ) ){
		$cached_values = array();
	}
	if( count( $cached_values ) > 50 ){
		foreach( $cached_values as $k => $v ){
			unset( $cached_values[ $k ] );
			break;
		}
	}
	$cached_values[ doubleval( $settings["data"][ "date" ] ) ] = $month . "-" . $settings["data"][ "date" ] . $settings["data"][ "user_id" ] . $settings["data"][ "serial_num_and_table" ];
	
	$csettings["cache_values"] = $cached_values;
	set_cache_for_special_values( $csettings );
	
	$csettings = array(
		'cache_values' => $settings["data"],
		'cache_key' => $month . "-" . $settings["data"][ "date" ] . $settings["data"][ "user_id" ] . $settings["data"][ "serial_num_and_table" ],
		'permanent' => true,
		'directory_name' => $dir."-".$settings["type"],
	);
	set_cache_for_special_values( $csettings );
}

function get_recent_activity( $settings = array() ){
	$dir = "recent-activities";
	$limit = 10;
	if( ! isset( $settings["type"] ) )
		return 0;		//cash-calls | monthly-cash-calls | divisional-reports | tendering-and-contracts
	
	if( isset( $settings["limit"] ) && intval( $settings["limit"] ) )
		$limit = intval( $settings["limit"] );
	
	//get TOP 50 recent activities keys
	$csettings = array(
		'cache_key' => "top-50-keys",
		'permanent' => true,
		'directory_name' => $dir."-".$settings["type"],
	);
	$cached_values = get_cache_for_special_values( $csettings );
	if( $cached_values && is_array( $cached_values ) && ! empty( $cached_values ) ){
		krsort( $cached_values );
		$return = array();
		foreach( $cached_values as $k => $v ){
			$csettings = array(
				'cache_key' => $v,
				'permanent' => true,
				'directory_name' => $dir."-".$settings["type"],
			);
			$return[] = get_cache_for_special_values( $csettings );
		}
		return $return;
	}
	
	return 0;
}

function get_uploaded_files( $pagepointer, $value, $field_label = '', $view = '', $settings = array() ){
	if( ! ( $value && $pagepointer ) )return;
	
	$pr = get_project_data();
	
	$return_link = isset( $settings["return_link"] )?$settings["return_link"]:0;
	$showlink_only = isset( $settings["show_link_only"] )?$settings["show_link_only"]:0;
	$json = isset( $settings["json"] )?json_decode( $settings["json"], true ):array();
	
	$files = "";
	$add = "";
	//$add = "engine/";
	
	if( isset( $pr["domain_name"] ) ){
		$add = $pr["domain_name"] . $add;
	}
	$docs = explode(':::', $value );
	if ( is_array($docs) ){
	
		$sn = 0;
		
		$did = rand(120,15312);
		$hash = get_file_hash( array( "hash" => 1, "file_id" => $did, "date_filter" => 'd-M-Y' ) );
		
		if ( is_array($json) && ! empty($json) ){
			$docs = $json;
		}
		
		foreach($docs as $k_doc => $v_doc1 ){
			$title = '';
			if( is_array( $v_doc1 ) ){
				$v_doc = isset( $v_doc1["stored_name"] )?$v_doc1["stored_name"]:'';
				$title = isset( $v_doc1["title"] )?$v_doc1["title"]:'';
			}else{
				$v_doc = $v_doc1;
			}
			
			if( $v_doc && file_exists($pagepointer.$v_doc)){
				$get_ext[1] = '';
				$get_ext = explode(".",$v_doc);
				
				if( ! isset( $get_ext[1] ) ){
					$get_ext[1] = '';
				}
				
				if( $title ){
					$ttt = $title;
				}else{
					$ttt = ucwords( $field_label );
					if( $sn ){
						$ttt .= ' - ' . $sn;
					}
				}
				
				
				//$cn_path = $add.$v_doc;
				$cn_path = $add.'print.php?hash='.$hash.'&id='.$did.'&durl='. get_file_hash( array( "encrypt" => $v_doc, "key" => $hash ) ) .'&name='. rawurlencode( $ttt );
				if( $return_link ){
					return $cn_path;
				}
				
				if( $showlink_only ){
					
				}else{
					switch( $get_ext[1] ){
					case "jpg":
					case "jpeg":
					case "png":
					case "gif":
					case "svg":
						$files .= '<img src="'.$cn_path.'" style="border:2px solid #333; max-width:90%; max-height:75px;" /><br />';
					break;
					}
				}
				
				
				$files .= '<a href="'.$cn_path.'"  class="view-file-link-1 hidden-print no-print" target="_blank"  title="Click here to view '. $ttt .'">&rarr; '. $ttt .' [ '.$get_ext[1].' ] '.number_format((filesize($pagepointer.$v_doc)/(1024*1024)),2).' MB</a><br />';
				
				++$sn;
			}
		}
	}
	
	if( $view && $files ){
		$icls = 'btn btn-sm';
		$itrash = '<i class="icon-trash"></i> ';
		if( defined("HYELLA_MOBILE") && HYELLA_MOBILE ){
			//$icls = 'btn';
			$itrash = '<i class="material-icons nwp-mobile-icon">delete</i>';
		}
		$files .= '<a href="#" style="clear:both;" class="'.$icls.' dark pull-right remove-uploaded-file" alt="'.$view.'" default-image="none" >'.$itrash.'</a>';
	}
	return $files;
}

function format_time( $raw, $type = 0 ){
	switch( $type ){
	case 1:
		$raw = trim( str_replace(":", ".", $raw ) );
		return doubleval( $raw );
	break;
	case 2:
		$raw = trim( str_replace(":", ".", $raw ) );
	break;
	case 5:
		return $raw;
	break;
	}
	
	$raw = number_format( doubleval( $raw ), 2 );	
	$r = str_replace(".", ":", $raw );
	
	switch( $type ){
	case 3:
		$ex = explode(":", $r );
		if( isset( $ex[0] ) && isset( $ex[1] ) ){
			$r = date("h:i A", mktime( $ex[0], $ex[1], 0, 1, 1, date("Y") ) );
		}
	break;
	}
	
	return $r;
}

function run_in_background( $Command = "", $Priority = 0, $args_array = array() ){
	$args = '"0"';
	$development = get_hyella_development_mode();

	if( class_exists('cNwp_queue') && class_exists( 'cNwp_app_core' ) && cNwp_app_core::$def_cs_active ){
		$nwp = new cNwp_queue();
		$nwp->class_settings = cNwp_app_core::$def_cs_active;
		$nwp->class_settings['action_to_perform'] = 'run_job_queue';
		return $nwp->_run_job_queue( array(
			'command' => $Command,
			'priority' => $Priority,
			'arguments' => $args_array
		) );
	}
	
	if( isset( $args_array["action"] ) && isset( $args_array["todo"] ) ){
		if( ! isset( $args_array["reference"] ) )$args_array["reference"] = '';
		if( ! isset( $args_array["user_id"] ) )$args_array["user_id"] = '';
		
		if( isset( $args_array["no_session"] ) ){
			$args_array["key"] = '';
		}else{
			$args_array["key"] = session_id();
		}

		// implode_associative_array( glue, array, delimiter );
		$args = '"'.implode_associative_array( ':::', $args_array, '@@' ).'"';

		if( $development ){
			if ($args_array['action'] == 'audit'  && $args_array['todo'] == 'sync' ) {
				return;
			}
		}

	}else{	
		if( isset( $args_array["text"] ) && $args_array["text"] ){
			$args = '"'.$args_array["text"].'"';
		}
	}
	
	if( isset( $args_array["show_window"] ) && $args_array["show_window"] ){
		$development = 1;
	}
	
	if( $args && $development ){
		$development = 'hyella_development';
		$args .= ' "' . $development . '"';
	}else{
		$args .= ' "0"';
	}
	
	$wait_for_execution = 0;
	if( isset( $args_array["wait_for_execution"] ) && $args_array["wait_for_execution"] ){
		$wait_for_execution = 1;
	}
	
	if( isset( $args_array["argument3"] ) ){
		$args .= ' "' . $args_array["argument3"] . '"';
	}else{
		$args .= ' "0"';
	}
	
	if( isset( $args_array["argument4"] ) ){
		$args .= ' "' . $args_array["argument4"] . '"';
	}else{
		$args .= ' "0"';
	}
	
	if( isset( $args_array["argument5"] ) ){
		$args .= ' "' . $args_array["argument5"] . '"';
	}else{
		$args .= ' "0"';
	}
	
   if( defined("PLATFORM") && PLATFORM == "linux" ){
	   if( $Command ){
		    if( defined("NWP_ORIGIN_PATH") && NWP_ORIGIN_PATH ){
				$Command = NWP_ORIGIN_PATH ."/". $Command . ".php";
			}else{
				$Command = dirname( dirname( __FILE__ )  ) ."/php/". $Command . ".php";
			}
			
			$output=null;
			$retval=null;
			
			if( $wait_for_execution ){
				exec("php ".$Command." " .  addslashes( escapeshellarg( str_replace('"','', $args) ) ), $output, $retval);
			}else{
				// echo "\n" . $Command . "\n";
				// exec( "php ".$Command." " .  addslashes( escapeshellarg( str_replace('"','', $args) ) ) . " > /dev/null &");
				exec( "php ".$Command." " .  addslashes( escapeshellarg( str_replace('"','', $args) ) ) );
			}			
	   }
   }elseif( defined("PLATFORM") && PLATFORM == "mac" ){
	   
   }else{
		if( $Command ){
			$check = 1;
			switch( $Command ){
			case "openfile":
				$check = 0;
			break;
			}
			
			if( $check && defined("NWP_ORIGIN_PATH") && NWP_ORIGIN_PATH ){
				$Command = NWP_ORIGIN_PATH ."\\". $Command . ".bat";
			}elseif( isset( $args_array[ 'absolute_bat_path' ] ) && $args_array[ 'absolute_bat_path' ] ){
				$Command = $Command;
			}else{
				$Command = dirname( dirname( __FILE__ )  ) ."\\php\\". $Command . ".bat";
			}

			if( $wait_for_execution ){
				ob_start();
				system( $Command . "\" " . escapeshellarg($args));
				ob_end_clean();
			}else{
				pclose(popen("start \"HYELLA\" \"" . $Command . "\" " . escapeshellarg($args) , "r"));
			}
			
	   }
   }
}

function validate_phone_number( $phone = '' ){
	$validated_phone = '';
	
	//test for country code
	$first_character = substr( $phone, 0, 1 );
	
	if( $first_character != "+" ){
		if( $first_character == "0" ){
			$validated_phone = "+234" . substr( $phone, 1 );
		}else{
			$validated_phone = "+" . $phone;
		}
	}else{
		$validated_phone = $phone;
	}
	
	return $validated_phone;
}
	
function wmiWBemLocatorQuery( $query ) {
    if ( class_exists( '\\COM' ) ) {
        try {
            $WbemLocator = new \COM( "WbemScripting.SWbemLocator" );
            $WbemServices = $WbemLocator->ConnectServer( '127.0.0.1', 'root\CIMV2' );
            $WbemServices->Security_->ImpersonationLevel = 3;
            // use wbemtest tool to query all classes for namespace root\cimv2
            return $WbemServices->ExecQuery( $query );
        } catch ( \com_exception $e ) {
            echo $e->getMessage();
        }
    } elseif ( ! extension_loaded( 'com_dotnet' ) )
        trigger_error( 'It seems that the COM is not enabled in your php.ini', E_USER_WARNING );
    else {
        $err = error_get_last();
        trigger_error( $err['message'], E_USER_WARNING );
    }

    return false;
}

// _dir_in_allowed_path this is your function to detect if a file is withing the allowed path (see the open_basedir PHP directive)
function getSystemMemoryInfo( $output_key = '' ) {
    $keys = array( 'MemTotal', 'MemFree', 'MemAvailable', 'SwapTotal', 'SwapFree' );
    $result = array();

    try {
        // LINUX
        if ( function_exists("isWin") && ! isWin() ) {
            $proc_dir = '/proc/';
            $data = _dir_in_allowed_path( $proc_dir ) ? @file( $proc_dir . 'meminfo' ) : false;
            if ( is_array( $data ) )
                foreach ( $data as $d ) {
                    if ( 0 == strlen( trim( $d ) ) )
                        continue;
                    $d = preg_split( '/:/', $d );
                    $key = trim( $d[0] );
                    if ( ! in_array( $key, $keys ) )
                        continue;
                    $value = 1000 * floatval( trim( str_replace( ' kB', '', $d[1] ) ) );
                    $result[$key] = $value;
                }
        } else      // WINDOWS
        {
            $wmi_found = false;
            if ( $wmi_query = wmiWBemLocatorQuery( 
                "SELECT FreePhysicalMemory,FreeVirtualMemory,TotalSwapSpaceSize,TotalVirtualMemorySize,TotalVisibleMemorySize FROM Win32_OperatingSystem" ) ) {
                foreach ( $wmi_query as $r ) {
                    $result['MemFree'] = $r->FreePhysicalMemory * 1024;
                    $result['MemAvailable'] = $r->FreeVirtualMemory * 1024;
                    $result['SwapFree'] = $r->TotalSwapSpaceSize * 1024;
                    $result['SwapTotal'] = $r->TotalVirtualMemorySize * 1024;
                    $result['MemTotal'] = $r->TotalVisibleMemorySize * 1024;
                    $wmi_found = true;
                }
            }
            // TODO a backup implementation using the $_SERVER array
        }
    } catch ( Exception $e ) {
        echo $e->getMessage();
    }
    return empty( $output_key ) || ! isset( $result[$output_key] ) ? $result : $result[$output_key];
}

function get_convert_time_scale( $xfrom = '', $xto = '' ){
	$return = array(
		'second' => 1,
		'minute' => 60,
		'hour' => 3600,
		'hour2' => 7200,
		'hour4' => 14400,
		'hour6' => 21600,
		'hour8' => 28800,
		'hour12' => 43200,
		'day' => 86400,
		'week' => 604800,
		'month' => 2592000,
		'year' => 31536000,

		'qds' => 21600, // hour6
		'bd' => 43200, // hour12
		'od' => 86400, // day
		'stat' => 86400, // day
		'nocte' => 86400, // day
		'tds' => 28800, // hour8
	);
	
	if( $xfrom == $xto ){
		return 1;
	}
	
	if( $xfrom && $xto && isset( $return[ $xfrom ] ) && isset( $return[ $xto ] ) ){
		return $return[ $xfrom ] / $return[ $xto ];
	}
	
	return 1;
}

function get_width_for_button_columns( $table = '' ){
	'width:170px;';
}


function convert_number_to_word( $number = 0 ){
	$number = doubleval( $number );	//@bay: pls merge it this time - 27-dec-22
	if( ( ! defined("NWP_CUSTOM_NUMBER_FORMATTER") ) && class_exists("NumberFormatter") ){
		//echo 4343; exit;
		$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		return $f->format( $number );
	}else{
		if( ! $number )return 'ZERO';
		
		$num = strval( floor( $number ) );
		$words = array();
		$list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
			'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
		);
		$list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
		$list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
			'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
			'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
		);
		$num_length = strlen($num);
		$levels = (int) (($num_length + 2) / 3);
		$max_length = $levels * 3;
		$num = substr('00' . $num, -$max_length);
		$num_levels = str_split($num, 3);
		for ($i = 0; $i < count($num_levels); $i++) {
			$levels--;
			$hundreds = (int) ($num_levels[$i] / 100);
			$hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
			$tens = (int) ($num_levels[$i] % 100);
			$singles = '';
			if ( $tens < 20 ) {
				$tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
			} else {
				$tens = (int)($tens / 10);
				$tens = ' ' . $list2[$tens] . ' ';
				$singles = (int) ($num_levels[$i] % 10);
				$singles = ' ' . $list1[$singles] . ' ';
			}
			$tmp = $hundreds;
			if( $hundreds && $tens ){
				$tmp .= ' AND ';
			}
			$tmp .= $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
			
			$words[] = $tmp;
			
			
		} //end for loop
		$commas = count($words);
		if ($commas > 1) {
			$commas = $commas - 1;
		}
		$suffix = '';
		if( defined("NWP_NUMBER_TO_WORDS_SUFFIX") ){
			$suffix = NWP_NUMBER_TO_WORDS_SUFFIX;
			
		}
		return strtoupper( implode(' ', $words) . $suffix );
	}
}

function _wp_normalize_path( $path ) {
    $path = str_replace( '\\', '/', $path );
    $path = preg_replace( '|(?<=.)/+|', '/', $path );
    if ( ':' === substr( $path, 1, 1 ) ) {
        $path = ucfirst( $path );
    }
    return $path;
}

function _clean_array_values_of_non_utf8( & $item, $key ){
	$item = addslashes( iconv( "UTF-8", "ASCII//IGNORE", $item ) );
}

function clean_array_values2( & $item, $key ){
	$item = strtolower( trim( $item ) );
}

function get_app_version( $pagepointer = '' ){
	$application_version = 'no-version';
	if( file_exists( $pagepointer . 'BUILD.hyella' ) ){
		//3. Open & Read all files in directory
		$ap = json_decode( file_get_contents( $pagepointer . 'BUILD.hyella' ), true );
		$package = get_package_option();
		
		$application_version = isset( $ap[ $package ]["version"] )?$ap[ $package ]["version"]:'invalid';
	}
	
	return $application_version;
}

function get_digital_signature( $params = array() ){
	extract( $params );
	
	if( ! ( isset( $sign_reference ) && $sign_reference ) ){
		return 'Invalid Reference';
	}
	
	if( ! ( isset( $sign_input_name ) && $sign_input_name ) ){
		return 'Invalid Signature Input Name';
	}
	
	if( ! ( isset( $sign_container ) && $sign_container ) ){
		return 'Invalid Signature Container';
	}
	
	if( ! isset( $sign_reference_table ) )$sign_reference_table = '';
	if( ! isset( $sign_reference_todo ) )$sign_reference_todo = '';
	if( ! isset( $sign_reference_action ) )$sign_reference_action = '';
	if( ! isset( $sign_saved_signature ) )$sign_saved_signature = '';
	
	if( $sign_saved_signature ){
		$sign_saved_signature = '<img src="'. $sign_saved_signature .'" />';
	}
	
	if( isset( $sign_show_saved_singature_only ) && $sign_show_saved_singature_only ){
		$ax = '<div style="margin-bottom:-30px;">' . $sign_saved_signature . '</div>';
	}else{
		$ax = '<a href="#" class="btn btn-default custom-single-selected-record-button hidden-print" title="Click Here to Sign" override-selected-record="'. $sign_reference .'" action="?module=&action=barcode&todo=capture_digital_signature&html_replacement_selector='. $sign_container .'&input_name='. $sign_input_name .'&reference_table='. $sign_reference_table .'&reference_action='. $sign_reference_action .'&reference_todo='. $sign_reference_todo .'">Click Here to Sign</a><div id="'. $sign_container .'" style="margin-bottom:-20px;">'. $sign_saved_signature .'</div>';
	}
	
	$h_sign = '<div style="border:none; text-align:center;">';
		$h_sign .= '<br />'. $ax .'<br />________________________<br />';
		if( isset( $sign_text ) )$h_sign .= $sign_text;
		
		if( isset( $sign_show_input ) && $sign_show_input ){
			$h_sign .= '<input type="hidden" name="nw_signature['.$sign_input_name.'][sign]" class="class-'. $sign_input_name .'" id="id-'. $sign_input_name .'-sign" />';
		}
		
		if( isset( $signature_data["date"] ) && $signature_data["date"] ){
			$h_sign .= '<br />' . date("d-M-Y H:i", doubleval( $signature_data["date"] ) );
		}
	$h_sign .= '</div>';
	
	return $h_sign;
}

function nw_pretty_number( $number = 0, $params = array() ){
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

function check_for_idle_timeout(){
	if( defined("IDLE_TIMEOUT") && IDLE_TIMEOUT ){
		return 1;
	}
}

function get_quarters_in_a_year( $opt = array() ){
	
	$cyear = date("Y");
	$pyear = $cyear - 1;
	$grace_p = doubleval( isset( $opt["grace_period_in_days"] )?$opt["grace_period_in_days"]:0 );
	
	if( $grace_p < 0 ){
		$grace = -1;
	}else{
		$grace = ( isset( $opt["grace_period_in_days"] )?$opt["grace_period_in_days"]:0 ) * ( 86400 );	//2 weeks
	}
	
	$qtr = array(
		$cyear => array(
			array(
				'value' => 'q-1',
				'data-num' => '1',
				'start_timestamp' => mktime( 0, 0, 0, 1, 1, $cyear ),
				'end_timestamp' => mktime( 23, 59, 59, 3, 31, $cyear ),
				'data-start' => $cyear . "-01-01",
				'data-end' => $cyear . "-03-31",
				'text' => '1st Quarter',
			),
			array(
				'value' => 'q-2',
				'data-num' => '2',
				'start_timestamp' => mktime( 0, 0, 0, 4, 1, $cyear ),
				'end_timestamp' => mktime( 23, 59, 59, 6, 30, $cyear ),
				'data-start' => $cyear . "-04-01",
				'data-end' => $cyear . "-06-30",
				'text' => '2nd Quarter',
			),
			array(
				'value' => 'q-3',
				'data-num' => '3',
				'start_timestamp' => mktime( 0, 0, 0, 7, 1, $cyear ),
				'end_timestamp' => mktime( 23, 59, 59, 9, 30, $cyear ),
				'data-start' => $cyear . "-07-01",
				'data-end' => $cyear . "-09-30",
				'text' => '3rd Quarter',
			),
			array(
				'value' => 'q-4',
				'data-num' => '4',
				'start_timestamp' => mktime( 0, 0, 0, 10, 1, $cyear ),
				'end_timestamp' => mktime( 23, 59, 59, 12, 31, $cyear ),
				'data-start' => $cyear . "-10-01",
				'data-end' => $cyear . "-12-31",
				'text' => '4th Quarter',
			),
		),
		$pyear => array(
			array(
				'value' => 'q-4-1',
				'data-num' => '4',
				'start_timestamp' => mktime( 0, 0, 0, 10, 1, $pyear ),
				'end_timestamp' => mktime( 23, 59, 59, 12, 31, $pyear ),
				'data-start' => $pyear . "-10-01",
				'data-end' => $pyear . "-12-31",
				'text' => '4th Quarter',
			),
			//06-jan-23
			array(
				'value' => 'q-4-2',
				'data-num' => '3',
				'start_timestamp' => mktime( 0, 0, 0, 7, 1, $pyear ),
				'end_timestamp' => mktime( 23, 59, 59, 9, 30, $pyear ),
				'data-start' => $pyear . "-07-01",
				'data-end' => $pyear . "-09-30",
				'text' => '3rd Quarter',
			),
		),
	);
	
	$qh = '';
	if( ! empty( $qtr ) ){
		$today = date("U");
		$current_quarter = array();
		
		foreach( $qtr as $qk => $qv ){
			if( ! empty( $qv ) ){
				foreach( $qv as $qk1 => $qv1 ){
					if( $today >= $qv1["start_timestamp"] && $today <= $qv1["end_timestamp"] ){
						$current_quarter = $qv1;
						break 2;
					}
				}
			}
		}
		
		foreach( $qtr as $qk => $qv ){
			$qh2 = '';
			
			if( ! empty( $qv ) ){
				foreach( $qv as $qk1 => $qv1 ){
					
					if( $grace >= 0 ){
						if( isset( $current_quarter["end_timestamp"] ) && $qv1["end_timestamp"] < $current_quarter["end_timestamp"] ){
							if( ( $qv1["end_timestamp"] + $grace ) < $today ){
								continue;
							}else if( $grace ){
								$xdays = floor( ( ( $qv1["end_timestamp"] + $grace ) - $today ) / 86400 );
								
								$qv1["text"] .= ' (Grace Period: '. $xdays .' days left)';
							}
						}
					}
					
					$qh2 .= '<option ';
					if( isset( $current_quarter["value"] ) && isset( $qv1["value"] ) && $current_quarter["value"] == $qv1["value"] ){
						$qh2 .= ' selected="selected" ';
					}
					
					foreach( $qv1 as $qk2 => $qv2 ){
						$qh2 .= $qk2 . '="'.$qv2.'" ';
					}
					$qh2 .= '>'. $qv1["text"] . '</option>';
				}
			}
			
			if( $qh2 ){
				$qh .= '<optgroup label="'. $qk .'">' . $qh2 . '</optgroup>';
			}
			
		}
	}
	
	if( isset( $opt['return_select_options'] ) ){
		return $qh;
	}
}


function _convert_id_into_actions( $id = '', $a = array() ){
	
	$params = array();
	$d1 = isset( $a["delimiter1"] )?$a["delimiter1"]:':::';
	$d2 = isset( $a["delimiter2"] )?$a["delimiter2"]:'=';
	
	if( $id ){
		if( substr( $id, 0, 1) == '?' ){
			$id = str_replace('?','', $id );
		}
		$t1 = explode( $d1, $id );
		foreach( $t1 as $t2 ){
			$t3 = explode( $d2, $t2 );
			
			if( isset( $t3[1] ) ){
				$params[ $t3[0] ] = $t3[1];
			}
			
		}
	}
	
	return $params;
}

function __resort_array( $arr, $index_arr ) {
    $arr_new=array();
    foreach($index_arr as $i=>$v) {
        foreach($arr as $k=>$b) {
            if ($k==$v){
            	$arr_new[$k]=$b;
            	unset( $arr[ $k ] );
            }
        }
    }
    if( ! empty( $arr ) )array_merge( $arr_new, $arr );

    return $arr_new;
}

function color_inverse($color){
	$color = trim( $color );
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return '000000'; }
    $rgb = '';
    for ($x=0;$x<3;$x++){
        $c = 255 - hexdec(substr($color,(2*$x),2));
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
    }
    return '#'.$rgb;
}

function download_csv( $e = array(), $pointer = '', $name = '', $options = array() ){

	$transformed = array();
	/* 
	if( isset( $options[ "table" ] ) && $options[ "table" ] ){
		if( ! is_dir( $pointer . '/files/'. $options[ "table" ] ) ){
			create_folder( $pointer . '/files/'. $options[ "table" ], '', '' );
		}
	}
	 */
	 
	if( isset( $options[ "transformed" ] ) && $options[ "transformed" ] ){
		$transformed = $e;
	}else{
		if( ! empty( $e ) ){
			foreach( $e as $key => $value ){
				$transformed[][] = $key;
				$header = array();
				$x = 1;

				if( is_array( $value ) && ! empty( $value ) ){
					foreach( $value as $k => $v ){
						$body = array();
						foreach( $v as $k1 => $v1 ){
							if( $x )$header[] = $k1;
							$body[] = $v1;
						}
						if( $x )$transformed[] = $header;
						$transformed[] = $body;
						$x = 0;
					}
				}
				$transformed[][] = '';
			}
		}
	}

	$write_option = '';

	if( isset( $options[ 'overwrite' ] ) && $options[ 'overwrite' ] ){
		$write_option = 'w';
	}else{
		if( file_exists( $name .'.csv' ) ){
			$write_option = 'a';
		}else{
			$write_option = 'w';
		}
	}

	$fp = fopen( $name .'.csv', $write_option );
	
	foreach ($transformed as $fields) {
	    fputcsv($fp, $fields, ',', '"', "\\");
	}

	fclose($fp);
}

function get_file_upload_form_field( $settings = array() ){
	$field_id = isset( $settings["field_id"] )?$settings["field_id"]:'';
	$t = isset( $settings["t"] )?$settings["t"]:'';
	$attr = isset( $settings["attributes"] )?$settings["attributes"]:'';
	$acceptable_files = isset( $settings["acceptable_files_format"] )?$settings["acceptable_files_format"]:'';
	if( isset( $settings["hide_on_select"] ) && $settings["hide_on_select"] ){
		$attr .= ' hide_on_select="1" ';

		if( isset( $settings["value"] ) && $settings["value"] ){
			$attr .= ' data-value="1" ';
		}
	}

	if( isset( $settings["label"] ) && $settings["label"] ){
		$attr .= ' label="'. $settings["label"] .'" ';
	}
	if( isset( $settings["value"] ) && $settings["value"] ){
		$attr .= ' value="'. $settings["value"] .'" ';
	}
	$h_content_loop = '<input alt="file" type="hidden" class="'.$field_id.'-replace" '.$attr.' data-id="upload-box-'.$field_id.'" />';

	//$h_content_loop .= '<textarea class="form-control" name="'.$field_id.'_json" style="display:none;"></textarea>';

	//src="http://localhost/feyi/engine/files/resource_library/FIAPS EXCEL IMPORT GUIDE.jpg"
	
	$h_content_loop .= '<img id="'.$field_id.'-img" class="form-gen-element-image-upload-preview" style="display:none;" /><div class="controls cell-element upload-box " id="upload-box-'.$field_id.'">';

	//19-jun-23: 2echo
	$h_content_loop .= '<input type="file" class="form-control uploaded-file" name="'.$field_id.'" id="'.$field_id.'" acceptable-files-format="'.$acceptable_files.'" '.$attr.' /></div>';
	
	$h_content_loop .= '<textarea class="form-control" name="'.$field_id.'_json" style="display:none;"></textarea>';
	$h_content_loop .= '<span class="input-status"></span>';
	
	$fc = '';
	if( isset( $settings[ 'field_label' ] ) && $settings[ 'field_label' ] && isset( $settings["value"] ) && $settings["value"] && isset( $settings["pagepointer"] ) && $settings["pagepointer"] ){
		$fc = get_uploaded_files( $settings["pagepointer"] , $settings["value"], $settings[ 'field_label' ] , $field_id );
	}
	$h_content_loop .= '<div class="file-content" id="'.$field_id.'-file-content">'.$fc.'</div>';
	
	return $h_content_loop;
}

function get_database_tables2(){
	return get_database_tables( array( 'group_plugin2' => 1 ) );
}

function get_database_tables( $o = array() ){
	$ad = new cAudit();
	$opt = $o;
	$opt['no_labels'] = 1;
	$opt['no_fields'] = 1;
	$opt['for_database_table_name'] = 1;

	return $ad->_get_project_classes( $opt );
}

function get_report_filter_fields( $opt = array(), $params = array() ){
	$return = array();
	if( is_array( $opt ) && ! empty( $opt ) ){
		foreach( $opt as $k => $v ){
			$cl = 'c'.ucwords( $k );

			if( class_exists( $cl ) ){
				$cl = new $cl();
				$labels = $k();
				
				if( ! empty( $v ) ){
					foreach( $v as $field ){
						if( isset( $cl->table_fields[ $field ] ) && isset( $labels[ $cl->table_fields[ $field ] ] ) ){
							$return[ $field ] = $labels[ $cl->table_fields[ $field ] ];
							$return[ $field ][ 'add_empty' ] = 1;
							$return[ $field ][ 'custom' ] = 1;
							$return[ $field ][ 'required_field' ] = 'no';
							
							if( isset( $params["params"][ $field ] ) ){
								$return[ $field ] = array_merge( $return[ $field ], $params["params"][ $field ] );
							}
						}
					}
				}
			}
		}
	}
	return $return;
}

function get_query_transpose( $o = array() ){
	$r = array();
	
	if( isset( $o["length"] ) && isset( $o["date_field"] ) && isset( $o["start_date"] ) && $o["start_date"] && isset( $o["end_date"] ) && $o["end_date"] ){
		
		$sd = date_create( date( 'Y-m-d', $o["start_date"] - 86400 ) );
		$ed = date_create( date( 'Y-m-d', $o["end_date"] ) );
		
		if( $sd->format("%y") != $ed->format("%y") ){
			$time_frame = 'Y';
		}else if( $sd->format("%m") != $ed->format("%m") ){
			$time_frame = 'n';
		}else{
			$time_frame = 'j';
		}
		$t = date( $time_frame, $o["start_date"] );
		$l_start = $o["start_date"];
		
		$r["select"] = array();
		
		$o["length"] = intval( $o["length"] );
		if( $o["length"] > 10 ){
			$o["length"] = 10;
		}
		
		for( $i = 0; $i < $o["length"]; $i++ ){
			
			if( $t < 1 ){
				switch( $time_frame ){
				case 'j': //days
					$y = date( "Y", $l_start );
					$m = date( "n", $l_start ) - 1;
					
					if( $m < 1 ){
						$m = 12;
						$y--;
					}
					$t = date( "t", mktime( 0, 0, 0, $m, 1, $y ) );
				break;
				case 'n': //month
					$t = 1;
					$y = date( "Y", $l_start ) - 1;
					$m = 1;
				break;
				default:
				break 2;
				}
			}else{
				$y = date( "Y", $l_start );
				$m = date( "n", $l_start );
			}
			
			$l_name_type = 'd-M-Y';
			switch( $time_frame ){
			case 'j': //days
				$l_start = mktime(  0, 0, 0, $m, $t, $y );
				$l_start2 = mktime(  23, 59, 59, $m, $t, $y );
			break;
			case 'n': //months
				$l_name_type = 'M-Y';
				$l_start = mktime(  0, 0, 0, $t, 1, $y );
				$l_start2 = mktime(  23, 59, 59, $t, date( "t", $l_start ), $y );
			break;
			case 'Y': //years
				$l_name_type = 'Y';
				$l_start = mktime(  0, 0, 0, 1, 1, $t );
				$l_start2 = mktime(  23, 59, 59, 12, 31, $t );
			break;
			default:
			break 2;
			}
			
			$l_name = date( $l_name_type, $l_start );
			
			$r["selections"][] = $l_name;
			$r["select"][] = " SUM( IF( ". $o["date_field"] ." BETWEEN ".$l_start." AND ". $l_start2 .", ". $o["true"] .", ". $o["false"] ." ) ) as '". $l_name ."' ";
			
			$t--;
		}
		
		$r["start_date"] = $l_start;
	}
	return $r;
}

function implode_associative_array($glue, $array, $symbol = '=') {
    return implode($glue, array_map(
		function($k, $v) use($symbol) {
			return $k . $symbol . $v;
		},
		array_keys($array),
		array_values($array)
		)
	);
}

function __map_financial_accounts(){
	$r = array(
		"package_ledger" => "package_ledger",	//new 2.2
			
		"hmo_fee_for_service" => "hmo_fee_for_service",	//new
		"hmo_ledger" => "hmo_ledger",	//new
		"annual_leave" => "ls17646528735", //new
		"casual_leave" => "ls17741334778", //new
		"maternity_leave" => "ls17741352037", //new
		"leave_without_pay" => "ls17646545240", //new 2.1
		
		"fixed_asset" => "fixed_asset",
		"asset" => "fixed_asset",
		
		"fixed_asset_gain" => "gain_on_fixed_asset",	//new
		"fixed_asset_loss" => "loss_on_fixed_asset", //new
		
		"other_asset" => "other_asset",	//new
		"asset_receivables" => "asset_receivables",	//short term loans
		
		"customers_control" => "customers_control",
		"goods_in_transit_control" => "goods_in_transit_control",
		"suppliers_control" => "suppliers_control",
		
		"equity" => ( ( defined("HYELLA_COA_EQUITY") && HYELLA_COA_EQUITY )?HYELLA_COA_EQUITY:"equity" ),
		"owners_equity" => ( ( defined("HYELLA_COA_OEQUITY") && HYELLA_COA_OEQUITY )?HYELLA_COA_OEQUITY:"15976708728" ),
		
		"customer_refund" => "petty_cash",
		"bank_account" => "cash_book",
		"account_receivable" => "accounts_receivable",
		
		"inventory" => "inventory",
		"utilization" => "utilization",
		
		"marketing_expense" => "inventory_marketing_expense",
		"cost_of_goods_sold" => "cost_of_goods_sold",
		"operating_expense" => "operating_expense",
		
		"current_liabilities" => "current_liabilities",
		
		"value_added_tax" => ( ( defined("HYELLA_COA_VAT") && HYELLA_COA_VAT )?HYELLA_COA_VAT:"value_added_tax" ),
		"service_charge" => ( ( defined("HYELLA_COA_SERVICE_CHARGE") && HYELLA_COA_SERVICE_CHARGE )?HYELLA_COA_SERVICE_CHARGE:"service_charge" ),
		"service_tax" => ( ( defined("HYELLA_COA_SERVICE_TAX") && HYELLA_COA_SERVICE_TAX )?HYELLA_COA_SERVICE_TAX:"service_tax" ),
		
		"damaged_goods" => "damaged_items",
		"used_goods" => "purchase_of_materials",
		
		"account_payable" => "account_payable",
		
		"discount" => "",
		"revenue" => "revenue",
		"revenue_category" => "revenue_category",
		//map individual product sales, discount, inventory & cost_of_goods
		
		"revenue_sales" => "revenue_from_sales",
		"revenue_hotel" => "room_booking_revenue",
		
		"salary_bank_account" => "bank1",
		
		"staff_medical_account" => "staff_medical",
		"salary_advance_account" => "salary_advance",
		"salary_expense_account" => "salary",
		
		"payroll_liabilities" => "payroll_liabilities",
		"payroll_net_pay" => "payroll_net_pay",
		"payroll_paye" => "payroll_paye",
		"payroll_pension" => "payroll_pension",
		"payroll_other_deductions" => "payroll_other_deductions",
		"payroll_cooperative" => "payroll_cooperative",	//new
		"payroll_housing" => "payroll_housing",	//new
		
		"charge_from_deposit" => "bank1",
		"complimentary" => ( ( defined("HYELLA_COA_COMPLIMENTARY") && HYELLA_COA_COMPLIMENTARY )?HYELLA_COA_COMPLIMENTARY:"complimentary" ),
	);
	
	if( defined("NWP_STRICT_CHART_OF_ACCOUNTS") && NWP_STRICT_CHART_OF_ACCOUNTS ){
		$r["cost_of_goods_sold"] = "expenses";
		$r["operating_expense"] = "expenses";
		$r["marketing_expense"] = "expenses";
		$r["damaged_goods"] = "expenses";
		$r["used_goods"] = "expenses";
		
		$r["revenue"] = "revenue";
		$r["revenue_hotel"] = "revenue";
		$r["revenue_sales"] = "revenue";
		$r["revenue_category"] = "revenue";
	}
	
	return $r;
}
function is_mobile(){
	$uaFull = strtolower($_SERVER['HTTP_USER_AGENT']);
	$uaStart = substr($uaFull, 0, 4);

	$uaPhone = [ '(android|bb\d+|meego).+mobile', 'avantgo', 'bada\/', 'blackberry', 'blazer', 'compal', 'elaine', 'fennec', 'hiptop', 'iemobile', 'ip(hone|od)', 'iris', 'kindle', 'lge ', 'maemo', 'midp', 'mmp', 'mobile.+firefox', 'netfront', 'opera m(ob|in)i', 'palm( os)?', 'phone', 'p(ixi|re)\/', 'plucker', 'pocket', 'psp', 'series(4|6)0', 'symbian', 'treo', 'up\.(browser|link)', 'vodafone', 'wap', 'windows ce', 'xda', 'xiino'
	];

	$uaMobile = [ '1207', '6310', '6590', '3gso', '4thp', '50[1-6]i', '770s', '802s', 'a wa', 'abac|ac(er|oo|s\-)', 'ai(ko|rn)', 'al(av|ca|co)', 'amoi', 'an(ex|ny|yw)', 'aptu', 'ar(ch|go)', 'as(te|us)', 'attw', 'au(di|\-m|r |s )', 'avan', 'be(ck|ll|nq)', 'bi(lb|rd)', 'bl(ac|az)', 'br(e|v)w', 'bumb', 'bw\-(n|u)', 'c55\/', 'capi', 'ccwa', 'cdm\-', 'cell', 'chtm', 'cldc', 'cmd\-', 'co(mp|nd)', 'craw', 'da(it|ll|ng)', 'dbte', 'dc\-s', 'devi', 'dica', 'dmob', 'do(c|p)o', 'ds(12|\-d)', 'el(49|ai)', 'em(l2|ul)', 'er(ic|k0)', 'esl8', 'ez([4-7]0|os|wa|ze)', 'fetc', 'fly(\-|_)', 'g1 u', 'g560', 'gene', 'gf\-5', 'g\-mo', 'go(\.w|od)', 'gr(ad|un)', 'haie', 'hcit', 'hd\-(m|p|t)', 'hei\-', 'hi(pt|ta)', 'hp( i|ip)', 'hs\-c', 'ht(c(\-| |_|a|g|p|s|t)|tp)', 'hu(aw|tc)', 'i\-(20|go|ma)', 'i230', 'iac( |\-|\/)', 'ibro', 'idea', 'ig01', 'ikom', 'im1k', 'inno', 'ipaq', 'iris', 'ja(t|v)a', 'jbro', 'jemu', 'jigs', 'kddi', 'keji', 'kgt( |\/)', 'klon', 'kpt ', 'kwc\-', 'kyo(c|k)', 'le(no|xi)', 'lg( g|\/(k|l|u)|50|54|\-[a-w])', 'libw', 'lynx', 'm1\-w', 'm3ga', 'm50\/', 'ma(te|ui|xo)', 'mc(01|21|ca)', 'm\-cr', 'me(rc|ri)', 'mi(o8|oa|ts)', 'mmef', 'mo(01|02|bi|de|do|t(\-| |o|v)|zz)', 'mt(50|p1|v )', 'mwbp', 'mywa', 'n10[0-2]', 'n20[2-3]', 'n30(0|2)', 'n50(0|2|5)', 'n7(0(0|1)|10)', 'ne((c|m)\-|on|tf|wf|wg|wt)', 'nok(6|i)', 'nzph', 'o2im', 'op(ti|wv)', 'oran', 'owg1', 'p800', 'pan(a|d|t)', 'pdxg', 'pg(13|\-([1-8]|c))', 'phil', 'pire', 'pl(ay|uc)', 'pn\-2', 'po(ck|rt|se)', 'prox', 'psio', 'pt\-g', 'qa\-a', 'qc(07|12|21|32|60|\-[2-7]|i\-)', 'qtek', 'r380', 'r600', 'raks', 'rim9', 'ro(ve|zo)', 's55\/', 'sa(ge|ma|mm|ms|ny|va)', 'sc(01|h\-|oo|p\-)', 'sdk\/', 'se(c(\-|0|1)|47|mc|nd|ri)', 'sgh\-', 'shar', 'sie(\-|m)', 'sk\-0', 'sl(45|id)', 'sm(al|ar|b3|it|t5)', 'so(ft|ny)', 'sp(01|h\-|v\-|v )', 'sy(01|mb)', 't2(18|50)', 't6(00|10|18)', 'ta(gt|lk)', 'tcl\-', 'tdg\-', 'tel(i|m)', 'tim\-', 't\-mo', 'to(pl|sh)', 'ts(70|m\-|m3|m5)', 'tx\-9', 'up(\.b|g1|si)', 'utst', 'v400', 'v750', 'veri', 'vi(rg|te)', 'vk(40|5[0-3]|\-v)', 'vm40', 'voda', 'vulc', 'vx(52|53|60|61|70|80|81|83|85|98)', 'w3c(\-| )', 'webc', 'whit', 'wi(g |nc|nw)', 'wmlb', 'wonu', 'x700', 'yas\-', 'your', 'zeto', 'zte\-'
	];

	$isPhone = preg_match('/' . implode($uaPhone, '|') . '/i', $uaFull);
	$isMobile = preg_match('/' . implode($uaMobile, '|') . '/i', $uaStart);

	if($isPhone || $isMobile) {
		return 1;
	} else {
		// process normally
	}
}

function get_time_ago($date, $currentTime = 0 ) {
	$timestamp = intval( $date );
   
   $strTime = array("sec", "min", "hr", "day", "month", "year");
   $length = array("60","60","24","30","12","10");

   if( ! $currentTime ){
  	 $currentTime = time();
   }

   if($currentTime >= $timestamp) {
		$diff = $currentTime- $timestamp;
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
		$diff = $diff / $length[$i];
		}

		$diff = round($diff);
		return $diff . " " . $strTime[$i] . "(s)";
   }
}

function get_cl_label( $opt ){
	if( isset( $opt[ 'label' ][ 'form_field' ] ) && $opt[ 'label' ][ 'form_field' ] ){
		$lbl = $opt[ 'label' ][ 'field_label' ];
		if( isset( $opt[ 'label' ][ 'display_field_label' ] ) && $opt[ 'label' ][ 'display_field_label' ] )$lbl = $opt[ 'label' ][ 'display_field_label' ];

		return $lbl;
	}
	return '';
}

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
 } 

function get_extra_invoice_header_rows( $opt ) {
	$rtn = '';
	if( ! empty( $opt ) ){
		foreach( $opt as $op ){
			if( isset( $op[ 'key' ] ) && isset( $op[ 'value' ] ) ){
				$rtn .= '
				 <tr class="">
					<td>
						<strong>'. $op[ 'key' ] .':</strong>
					</td>
					<td>
						'. $op[ 'value' ] .'
					</td>
				 </tr>';
				
			}
		}
	}
	return $rtn;
}

include "bin/app-update-and-synchronize.php";
?>
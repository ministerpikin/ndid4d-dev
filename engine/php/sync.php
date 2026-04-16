<?php
	/*
	//local test for remote connection
	$pagepointer = '';
	$img["src"] = 'sync.zip';
	$img["name"] = 'Zebra ' . date("d-M-Y-H-i-s");
	$finfo = pathinfo( $img["src"] );
	
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.( $img["name"] ).'.'. $finfo[ 'extension' ] .'"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize( $pagepointer . $img["src"] ));
	readfile( $pagepointer . $img["src"] );
	exit;
	*/
	/* 
	define('FIRSTKEY','Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
	define('SECONDKEY','EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');
	function secured_encrypt($data, $type = 'encrypt'){
		$key = FIRSTKEY;
		
		if( $type == 'decrypt' ){
			//decrypt later....
			$c = base64_decode($data);
			$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
			$iv = substr($c, 0, $ivlen);
			$hmac = substr($c, $ivlen, $sha2len=32);
			$ciphertext_raw = substr($c, $ivlen+$sha2len);
			$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
			$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
			if (hash_equals($hmac, $calcmac))// timing attack safe comparison
			{
			}
			return $original_plaintext;
		}
		
		//$key previously generated safely, ie: openssl_random_pseudo_bytes
		$plaintext = $data;
		$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
		$iv = openssl_random_pseudo_bytes($ivlen);
		$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
		$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
		return $ciphertext;
	}
	$ent = base64_encode('jame the baller. Are you BACK?');
	echo $ent . '<br /><br />Dboy: ';
	echo base64_decode( $ent ); exit; 

	$ent = secured_encrypt('jame the baller. Are you BACK?', 'encrypt' );
	echo $ent . '<br /><br />Dboy: ';
	echo secured_encrypt( $ent , 'decrypt' ); exit;*/

	//CONFIGURATION
	$pagepointer = './';
	//require_once $pagepointer."settings/Config.php";
	define( 'PLATFORM' , 'linux' );
	$exclude_files = 1;
	require_once $pagepointer."settings/Setup.php";
	
	//$_POST["skip_download_check"] = 1;
	//$_POST["mac_address"] = '12';
	//$_POST["license"] = '5a08d5a670df90c94a33097b4ee25aa3';
	//check for posted data
	$tbc = array( 'parish', 'parishioner', 'parish_zones', 'payment_exemption', 'payment_template', 'people', 'pledge', 'sacrament', 'transactions', 'debit_and_credit', 'parish_transaction', 'users', 'assets', 'assets_category', 'chart_of_accounts', 'items', 'stores', 'vendors', 'customers' );
	
	$tbd = array();
	$table = '';
	if( isset( $_POST["table_data"] ) && $_POST["table_data"] ){
		$tbd = json_decode( $_POST["table_data"] , true );
	}
	if( isset( $_POST["table"] ) && $_POST["table"] ){
		$table = $_POST["table"];
	}
	//print_r( $_GET ); 
	//print_r( $_POST ); exit;
	
	//check for posted data
	if(  isset( $_GET["pub_k"] ) && isset( $_GET["mac"] ) && isset( $_GET["st"] ) && isset( $_GET["tk"] ) ){
		$mac_address = $_GET["mac"];
		
		//get license info
		$license = get_license_info( array( "id" => $_GET["pub_k"] ) );
		
		//print_r( $license );
		$log["tk"] = $_GET["tk"];
		$log["st"] = $_GET["st"];
		$log["mac"] = $_GET["mac"];
		$log["license"] = $_GET["pub_k"];
		$log["mac_address"] = $mac_address;
		$log["action"] = 'data-upload';
		
		if( isset( $license["private_key"] ) && isset( $license["database"] ) && isset( $license["password"] ) && isset( $license["host"] ) && isset( $license["user"] ) ){
			$valid_pk = 0;
			
			$fname = md5( $log["license"] . $log["st"] );
			$tk = md5( $fname . md5( $license["private_key"] . $mac_address ) );
			if( $tk == $log["tk"] ){
				$valid_pk = 1;
			}
			
			if( $valid_pk ){
				//echo 'val'; exit;
				// IP Adddress of Database Server Host PC
				$database_host_ip_address = $license["host"];
				$database_user = $license["user"];
				$database_user_password = $license["password"];
				$database_name = $license["database"];
				
				$database_connection = mysql_pconnect( $database_host_ip_address, $database_user, $database_user_password );
				//print_r($license);
				//echo mysql_error();
				//echo $database_connection; exit;
				if ( $database_connection ) {
					
					/****************************************************/
					/***********************PULL*************************/
					/****************************************************/
					$path = "tmp/db/".$mac_address."/" ;
					__create_folder( "tmp/db/".$mac_address."/", "", "" );
					
					$db_files = array();
					$sn = 0;
					
					//foreach( $tbc as $tb ){
					$rcount = 0;
					$dr = __get_d_data( $path, $fname, $database_name, $database_connection, $table, $tbd , $rcount );
					if( isset( $dr["files"] ) ){
						$db_files = $dr["files"];
					}
					
					//}
					
					//print_r( $_POST );
					//print_r( $tbd );
					//print_r( $db_files ); exit;
					//zip files
					//$local_file = "dump.zip";
					$local_file = $fname .".zip";
					if( create_zip2( $db_files, $path.$local_file ) ){
						//unlink( $path . $fname.$tb .".od1" );
						
						if( ! empty( $db_files ) ){
							foreach( $db_files as $dbf ){
								if( isset( $dbf["url"] ) && $dbf["url"] ){
									unlink( $dbf["url"] );
								}
							}
						}
						
						$img["src"] = $local_file;
						$img["name"] = $fname;
						$finfo = pathinfo( $img["src"] );
						
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="'.( $img["name"] ).'.'. $finfo[ 'extension' ] .'"');
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize( $path . $img["src"] ));
						readfile( $path . $img["src"] );
						
						unlink( $path . $local_file );
						exit;
					}else{
						//zip error
						echo json_encode( array( "status" => "Error Zipping Database" ) );
						
						$log["status"] = "Error Zipping Database";
						__analytics_update( $log );
						
						exit;
					}
					
				}else{
					//invalid license database
					echo json_encode( array( "status" => 'Could not connect to database: '  . mysql_error() ) );
					
					$log["status"] = "Error Zipping Database";
					__analytics_update( $log );
					
					exit;
				}
				
			}
			
		}else{
			echo json_encode( array( "status" => 'Invalid license file ' ) );
			$log["status"] = "Invalid license file";
			__analytics_update( $log );
			exit;
		}
	}
	mysql_close($database_connection); exit;
	
	function __get_d_data( $path, $fname, $database_name, $database_connection, $table, $tbd , $count = 0, $files = array() ){
		$update_logic = array();
		$sqlw = "";
		$sql = "";
		$tb = $table;
		$return = array();
		$return["files"] = $files;
		$return["count"] = $count;
		$no_mod = '';
		
		if( isset( $tbd[ $tb ]["modification_date"] ) && $tbd[ $tb ]["modification_date"] ){
			//$update_logic = 1;
			if( isset( $tbd[ $tb ]["msn"] ) && $tbd[ $tb ]["msn"] ){
				$sql1 = "SELECT MAX(`serial_num`) as 'msn' FROM `".$database_name."`.`".$table."`";
				$retval = mysql_query( $sql1, $database_connection );
				if( $retval ){
					$row = mysql_fetch_assoc( $retval );
					
					if( isset( $row['msn'] ) && doubleval( $row['msn'] ) > doubleval( $tbd[ $tb ]["msn"] ) ){
						$no_mod = 'serial_num';
						//continue with creation logic
						$update_logic[] = "( `serial_num` > " . $tbd[ $tb ]["msn"] . " )";
					}else{
						$no_mod = 'creation_date';
						$update_logic[] = "( `creation_date` >= " . $tbd[ $tb ]["creation_date"] . " AND `serial_num` > " . $tbd[ $tb ]["c_sn"] . " AND `id` != '". $tbd[ $tb ]["c_id"] ."' )";
					}
				}
			}
			
			if( ! $no_mod ){
				$update_logic[] = "( `modification_date` >= ".$tbd[ $tb ]["modification_date"]." )";
			}
			
			$sql = "SELECT * FROM `".$database_name."`.`".$table."` WHERE ". implode(" OR ", $update_logic ) ." LIMIT 0, 1000";
		}else{
			$sql = "SELECT * FROM `".$database_name."`.`".$table."` ".$sqlw." LIMIT 0, 1000";
		}
		
		//echo $sql; exit;
		if( $sql ){
			$retval = mysql_query( $sql, $database_connection );
			if( $retval ) {
				/* echo json_encode( array( "status" => 'Could not take data backup: '  . mysql_error() ) );
				//report error to admin email
				$log["status"] = 'Could not take data backup: '  . mysql_error();
				exit; */
				$rec = array();
				//while (($row = mysql_fetch_array( $retval )) != false) {
				while (($row = mysql_fetch_assoc( $retval )) != false) {
					$rec[] = $row;
				}
				
				file_put_contents( $path . $fname.$tb . ".od1", base64_encode( json_encode( array( $tb => $rec, "recall" => $no_mod ) ) ) );
				$return["count"] += count( $rec );
				unset( $rec );
				
				$return["files"][] = array( "url" => $path . $fname.$tb .".od1", "name" => $fname.$tb .".od1" );
				//$db_files[] = array( "url" => $path . $fname.$tb .".json", "name" => $fname.$tb .".json" );
				
			}
		}
		
		return $return;
	}
	
	function __create_folder( $directory_name_1 , $directory_name_2 , $directory_name_3 ){
		//CREATE ITEM FOLDER
		
		if(!(is_dir( $directory_name_1 . $directory_name_2 . $directory_name_3 ))){
			$oldumask = umask(0);
			
			mkdir(( $directory_name_1 . $directory_name_2 . $directory_name_3 ),0777);
			
			umask( $oldumask );
		}
		
		//Folder URL
		return $directory_name_1 . $directory_name_2 . $directory_name_3;
	}
?>
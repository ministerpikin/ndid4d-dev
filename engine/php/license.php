<?php
	$file = "";
	$name = "";
	
	$renewal_check = 0;
	$project = "";
	
	if( isset( $_GET["renewal_check"] ) && $_GET["renewal_check"] ){
		$renewal_check = 1;
	}
	
	if( isset( $_GET["project"] ) && $_GET["project"] ){
		switch( trim( $_GET["project"] ) ){
		case "8b4fd2dffca1fa8208730872acb456a8": //adebisi license
			$name = "adebisi";
			$project = $_GET["project"];
		break;
		}
	}
	
	if( isset( $_GET["delete"] ) && $_GET["delete"] ){
		$tmp_dir = dirname( dirname( __FILE__ ) ) . "/data/". $name ."/" . $_GET["delete"];
		if( file_exists( $tmp_dir ) ){
			unlink( $tmp_dir );
		}
		exit;
	}
	
	if( $name )
		$file = dirname( dirname( __FILE__ ) ) . "/expiry_dates/".$name.".txt";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: HYELLA PROJECT LICENSE SERVER <ping@northwindproject.com>' . "\r\n";
	$headers .= 'Bcc: maybeachtech@gmail.com ' . "\r\n";	
	
	
	//check for activate license
	if( $file && file_exists( $file ) ){
		$license = file_get_contents( $file );
		
		if( ! $renewal_check ){
			if( date("U") > ( doubleval( $license ) - ( 3600 * 24 * 30 ) ) ){
				mail("pat2echo@gmail.com", strtoupper($name) . ": License Expiring in 30 Days", strtoupper($name) . ": License Expiring in 30 Days. On the ".date("d-M-Y H:i", doubleval( $license ) ), $headers );
			}
		}
		
		if( date("U") < ( doubleval( $license ) + 3600 ) ){
			if( $renewal_check ){
				//create classes file download link
				$f = dirname( dirname( __FILE__ ) ) . "/expiry_dates/classes/".$project.".zip";
				if( file_exists( $f ) ){
					$tmp_dir = dirname( dirname( __FILE__ ) ) . "/data/". $name ."/";
					$tmp_name = md5( rand(0,900) . "salmon-cooking" );
					copy( $f, $tmp_dir . $tmp_name . ".zip" );
					if( file_exists( $tmp_dir . $tmp_name . ".zip" ) ){
						echo $tmp_name.".zip:::" . $license;
						mail("pat2echo@gmail.com", strtoupper($name) . ": Successful License Activation Request", strtoupper($name) . ": Successful License Activation Request. For License Expiring on the ".date("d-M-Y H:i", doubleval( $license ) ), $headers );
					}else{
						mail("pat2echo@gmail.com", strtoupper($name) . ": Could not Copy License Activation File", strtoupper($name) . ": Could not Copy License Activation File. For License Expiring on the ".date("d-M-Y H:i", doubleval( $license ) ), $headers );
					}
				}else{
					mail("pat2echo@gmail.com", strtoupper($name) . ": FAILED License Activation Request", strtoupper($name) . ": FAILED License Activation Request. For License Expiring on the ".date("d-M-Y H:i", doubleval( $license ) ), $headers );
				}
			}else{
				echo 1;
				exit;
			}
		}
		exit;
	}
	
	if( $name ){
		mail("pat2echo@gmail.com", strtoupper($name) . ": Cannot Find License File on Server", strtoupper($name) . ": Cannot Find License File on Server", $headers );
	}
?>
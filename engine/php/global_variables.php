<?php
	//linux cleansing bcos of escapeshellarg in run_in_background
	if( is_array( $argv ) && ! empty( $argv ) ){
		foreach( $argv as $agk => & $agv ){
			$agv = str_replace("'","", $agv );
		}
	}
	
	if( isset( $argv[1] ) && $argv[1] ){
		$p = array_chunk( preg_split('(:::|@@)', $argv[1] ), 2 );
		if( ! empty( $p ) ){
			foreach( $p as $p1 ){
				if( isset( $p1[0] ) && isset( $p1[1] ) ){
					
					switch( $p1[0] ){
					case "user_id":
						$_POST[ $p1[0] ] = $p1[1];
					break;
					case "reference":
						$_POST[ 'id' ] = $p1[1];
					break;
					case "session_id":
						if( $p1[1] )$GLOBALS[ $p1[0] ] = $p1[1];
					break;
					case 'operation':
					default:
						$_GET[ $p1[0] ] = $p1[1];
					break;
					}
				}
			}
		}
	}

	// print_r( $argv[1] );echo'mike';exit;
	
	if( ! ( isset( $_POST["user_id"] ) && $_POST["user_id"] ) ){
		$_GET['default'] = "default";
	}else{
		if( $_POST["user_id"] == "anonymous" ){
			$_GET['default'] = "default";
		}else{
			define("SET_USER", $_POST["user_id"] );
		}
	}

	$skip_background_upload = 1;

?>
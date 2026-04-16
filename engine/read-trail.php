<?php 
	//include "classes/cAudit.php";
	//$audit = new cAudit();
	if( ! isset( $pagepointer ) )$pagepointer = './';
    if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
		require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
		cNwp_app_core::startSession();
	}
	//require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	
	if( ! ( isset( $current_user_session_details['fname'] ) && $current_user_session_details['fname'] ) ){
		exit;
	}
	
	$super = 0;
	$access = get_accessed_functions();
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	if( ! $super ){
		header('Location: ' . $pagepointer );
		exit;
	}
	
	$type = '';
	if( isset( $_GET["t"] ) && $_GET["t"] ){
		$type = $_GET["t"];
	}
	
	if( isset( $_GET["info"] ) && $_GET["info"] ){
		date_default_timezone_set('Africa/Lagos');
		phpinfo();
		exit;
	}
?>
<body style="background:#333; color:#fff; font-family:monospace; font-size:16px;">
<?php	
	if( isset( $_GET["j"] ) && $_GET["j"] ){
		//echo rawurldecode( $_GET["j"] );
		$jd = json_decode( rawurldecode( $_GET["j"] ), true );
		//print_r( $jd ); exit;
		if( isset( $jd["f"] ) && file_exists( $jd["f"] ) && isset( $jd["k"] ) ){
			$filename = $jd["f"];
			
			$a = file_get_contents( $filename );
			$ab = json_decode( $a, true );
			
			if( isset( $ab[ $jd["k"] ] ) && is_array( $ab[ $jd["k"] ] ) ){
				$ab[ $jd["k"] ]["tr"] = '';
				__show_trail_summary( $ab[ $jd["k"] ] );
				
				echo '<br /><hr /><pre>';
				print_r( $ab[ $jd["k"] ] );
				echo '</pre>';
			}
		}
		exit;
	}
	
	if( isset( $_GET["rid"] ) && $_GET["rid"] ){
		//show audit trail history for specific record
		$sid = $_GET["rid"];
		$bl = [];
		set_time_limit(0);
		foreach( glob( $fpagepointer."tmp/tr/*.json" ) as $filename ) {
			if ( is_file( $filename ) ) {
				$jb = json_decode( file_get_contents( $filename ), true );
				if( is_array( $jb ) && ! empty( $jb ) ){
					foreach( $jb as $jv ){
						if( isset( $jv["parameters"]["query"] ) && $jv["parameters"]["query"] && strpos( $jv["parameters"]["query"], "'". $sid ."'" ) > -1 ){
							$jv["time"] = date("d-M-Y H:i:s", doubleval( $jv["date"] ) );
							$jv["file"] = $filename;
							$bl[ doubleval( $jv["date"] ) ][] = $jv;
						}
					}
				}
			}
		}
		ksort( $bl );
		print_r( $bl );
		exit;
	}
	
	$fpagepointer = '';/* 
	if( defined("HYELLA_INSTALL_PATH") && HYELLA_INSTALL_PATH ){
		if( (  isset( $_SERVER["DOCUMENT_ROOT"] ) && $_SERVER["DOCUMENT_ROOT"] ) ){
			$fpagepointer = $_SERVER["DOCUMENT_ROOT"] .'/'. HYELLA_INSTALL_PATH;
		}
	} */
	if( defined("NWP_FILES_PATH") && NWP_FILES_PATH ){
		$fpagepointer = NWP_FILES_PATH;
	}
	
	
	$a1 = file_get_contents( $fpagepointer."tmp/tr/stamp.php" );
	if( $a1 ){
		?>
		<h3 style="text-align:center; border-bottom:1px dotted #fff;">HYELLA ACTIVE TRAIL as @ <?php echo date("d-M-Y H:i", doubleval( $a1 ) ); ?></h3>
		<?php
		
		if( defined("NWP_COLLATE_AUDIT_TRAIL") && NWP_COLLATE_AUDIT_TRAIL ){
			$tf = NWP_COLLATE_AUDIT_TRAIL;
			$tr2 = [];
			foreach( glob( $fpagepointer . 'tmp/'.$tf . "/*.json" ) as $filename ) {
				if ( is_file( $filename ) ) {
					if( $type == 'request' ){
						$pf = pathinfo( $filename );
						__get_show_trail_summary( $filename, $pf["basename"] );
					}else{
						$jb = json_decode( file_get_contents( $filename ), true );
						if( is_array( $jb ) && ! empty( $jb ) ){
							foreach( $jb as $jv ){
								if( isset( $jv["trail"] ) && $jv["trail"] ){
									$tr2[ $jv["trail"] ][ $filename ] = $filename;
								}
							}
						}
					}
				}
			}
			
			if( ! empty( $tr2 ) ){
				foreach( $tr2 as $tr => $trv ){
					if( $tr == 'page_view' )continue;
					
					if( ! empty( $trv ) ){
						$grp = 2;
						foreach( $trv as $filename ){
							__get_show_trail_summary( $filename, $tr, $grp );
							$grp = 0;
						}
						echo '</details>';
					}
				}
			}
		}else{
			foreach( array( 'tr', 'sql_error', 'read', 'console', 'login' ) as $tr ){
				$filename = $fpagepointer . "tmp/". $tr ."/" . $a1 . ".json";
				__get_show_trail_summary( $filename, $tr );
				
			}
		}
	}
	
	if( isset( $_GET["clear"] ) && $_GET["clear"] ){
		
		if( isset( $_SESSION["clear_trail"] ) ){
			unset( $_SESSION["clear_trail"] );
		}else{
			$_SESSION["clear_trail"] = 1;
			
			include $fpagepointer."classes/cAudit.php";
			$audit = new cAudit();
			$audit->class_settings["user_id"] = 'read-trail';
			$audit->class_settings["calling_page"] = $fpagepointer;
			$audit->class_settings["new_trail"] = 1;
			$audit->_new_day();
		}
	}
	
	function __get_show_trail_summary( $filename, $tr, $grp = 1 ){
		if( file_exists( $filename ) ){
			if( $grp ){
				echo '<details open style="border:1px dashed; margin-bottom:20px;">';
				echo '<summary><span>' . strtoupper( $tr ) . '</span></summary>';
			}
			
			$a = file_get_contents( $filename );
			$ab = json_decode( $a, true );
			
			krsort( $ab );
			
			foreach( $ab as $k => $sv ){
				$sv["tr"] = $tr;
				
				__show_trail_summary( $sv );
				echo '<br /><a href="?j='. rawurlencode( json_encode( array( "f" => $filename, "k" => $k ) ) ) .'" target="_blank" style="color:#9393f1;">details</a>';
				echo '<br /><br />';
			}
			
			if( $grp == 1 ){
				echo '</details>';
			}
		}
	}
	
	function __show_trail_summary( $sv = array() ){
		
		if( isset( $sv["date"] ) && $sv["date"] )echo date("H:i", doubleval( $sv["date"] ) ) . '<br />';
		if( isset( $sv["comment"] ) )echo 'comment:<br />' . $sv["comment"] . '<br />';
		if( isset( $sv["parameters"]["comment"] ) )echo 'comment:<br />' . $sv["parameters"]["comment"] . '<br />';
		if( $sv["tr"] == 'login' && isset( $sv["table"] ) )echo $sv["table"] . '<br />';
		
		if( isset( $sv["parameters"]["query"] ) && $sv["parameters"]["query"] )echo '<div contenteditable="true">' . $sv["parameters"]["query"] . '</div>';
	}
?>
</body>
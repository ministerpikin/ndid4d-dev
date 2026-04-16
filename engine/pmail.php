<body >
	<style type="text/css">
		.styled {border-collapse: collapse;margin: 25px 0;font-size: 0.9em;font-family: sans-serif;min-width: 400px;box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);}
		.styled thead tr {background-color: #009879;color: #ffffff;text-align: left;}
		.styled th,.styled td { padding: 12px 15px;}
		.styled tbody tr {border-bottom: 1px solid #dddddd;}
		.styled tbody tr:nth-of-type(even) { background-color: #f3f3f3; }
		.styled tbody tr:last-of-type { border-bottom: 2px solid #009879; }
		.styled tbody tr.active-row { font-weight: bold;color: #009879; }
	</style>
<?php 
	if( ! isset( $pagepointer ) )$pagepointer = './';
	if( ! isset( $pagepointer ) )$pagepointer = './';
    if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
		require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
		cNwp_app_core::startSession();
	}
	
	//define("HYELLA_LYTICS_SERVER", 1);	//tmp measure, until fully migrated to app_core
	//require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	
	if( defined( "NWP_OPEN_PMAIL" ) && NWP_OPEN_PMAIL ){
		$super = 1;
	}else{
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
	}
	
	$fpagepointer = '';
	if( isset( $_GET["s"] ) && $_GET["s"] ){
		$rurl = rawurldecode( $_GET["s"] );
		// print_r( $rurl );exit;
		if( file_exists( $rurl ) ){
			$h = json_decode( nwp_enc22( file_get_contents( $rurl ), 0 ), true );
			if( isset( $h["subject"] ) )echo '<title>'.$h["subject"].'</title>';
			if( isset( $h["subject"] ) )echo '<h4 styleX="color:#fff;">SUBJECT: '.$h["subject"].'</h4>';
			if( isset( $h["email"] ) )echo '<h4 styleX="color:#fff;">RECIPIENT: '.$h["email"].'</h4>';
			if( isset( $h["message"] ) )echo '<div>'.rawurldecode( $h["message"] ).'</div>';
		}else{
			echo 'Data not found';
		}
	}else{
		$start_time = date("U") - (3600*24*90);
		$dir = 'tmp/sent_mails/';
		echo "<table class='styled'>";
		// echo '<ol>';
		$files = glob( $dir . "*.php" );
		usort( $files, function( $a, $b ) { return -(filemtime($a) - filemtime($b) ); } );
		// echo "<table>";
		foreach( $files as $filename ) {
			if ( is_file( $filename ) ) {
				
				if( filemtime( $filename ) < $start_time ){
					unlink( $filename );
				}else{
					echo "<tr>";
						echo "<td style='width: 60%'>";
						// echo '<li style="margin-bottom:10px;"><a href="?s='. rawurlencode( $filename ) .'" target="_blank" title="View Mail">'. basename($filename , ".php") .' - '. date("d-M-Y H:i:s", filemtime( $filename ) ).'</a></li>';
							echo '<li style=""><a href="?s='. rawurlencode( $filename ) .'" target="_blank" title="View Mail">'. basename($filename , ".php") .'</a></li>';
						echo "</td>";
						echo "<td style='width: 20%'>";
							echo  date("d-M-Y", filemtime( $filename ) );
						echo "</td>";
						echo "<td style='width: 20%'>";
							echo  date("H:i:s a", filemtime( $filename ) );
						echo "</td>";
					echo "</tr>";
				}
				
			}
		}
		// echo '</ol>';
		echo "</table>";
		//echo 'Invalid Source';
	}
?>
</body>
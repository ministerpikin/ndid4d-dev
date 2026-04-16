<body style="background:#333; color:#fff; font-family:monospace; font-size:16px;">
<?php 
	if( ! isset( $pagepointer ) )$pagepointer = './';
    if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
		require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
		cNwp_app_core::loadENV();
	}
	require_once $pagepointer."settings/Config.php";
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
	
	$fpagepointer = '';
	if( defined("NWP_FILES_PATH") && NWP_FILES_PATH ){
		$fpagepointer = NWP_FILES_PATH;
	}
	
	$search_key = 'table';
	$search_values = array( 'consultation' => 1 );
	$search_key = 'user';
	$search_values = array( 'urs2843713342' => 1 );
	$res = array();
	$count = 0;
	
	//windows performance check: winsat disk -drive c > "c:\xampp\htdocs\feyi\perf.txt"
	//linux performance check: sudo hdparm -Tt /dev/sda
	
	$dir = $fpagepointer."php/request-log/";
	if( is_dir( $dir ) ){
		
		$size = 0;
		$count = 0;
		$all = array( "mem" => 0, "time" => 0, "mem_count" => 0, "mem_min" => 0, "mem_max" => 0, "time_count" => 0, "time_max" => 0, "time_min" => 0 );
		foreach( scandir( $dir ) as $file){
			
			if( is_file( $dir.$file ) ){
				
				$p = json_decode( file_get_contents( $dir.$file ), true );
				if( isset( $p["mem_end"] ) && isset( $p["mem_start"] ) ){
					++$all["mem_count"];
					$i = doubleval( $p["mem_end"]) - doubleval( $p["mem_start"]);
					$all["mem"] += $i;
					if( $i > $all["mem_max"] ){
						$all["mem_max"] = $i;
					}
					if( $all["mem_min"] == 0 ){
						$all["mem_min"] = $i;
					}
					if( $i < $all["mem_min"] ){
						$all["mem_min"] = $i;
					}
				}
				
				if( isset( $p["end"] ) && isset( $p["start"] ) ){
					++$all["time_count"];
					$i = ( doubleval( $p["end"] ) - doubleval( $p["start"] ) );
					
					if( $i > $all["time_max"] ){
						$all["time_max"] = $i;
					}
					if( $all["time_min"] == 0 ){
						$all["time_min"] = $i;
					}
					if( $i < $all["time_min"] ){
						$all["time_min"] = $i;
					}
					$all["time"] += $i;
				}
				
				$count++;
			}
		}
		//echo ( $size / $count );
		
		$h = '';
		foreach( array("time" => array( 'divisor' => 1000, 'unit' => 'ms' ), "mem" => array( 'divisor' => (1024 * 1024), 'unit' => 'MB' ) ) as $k => $v ){
			$h .= '<tr><td>' . strtoupper( $k ) . '</td>';
				$ct = ( ( isset( $all[ $k."_count" ] ) && $all[ $k."_count" ] )?( $all[ $k."_count" ] ):0 );
				
				$h .= '<td>'. ( ( isset( $all[ $k ] ) && $all[ $k ] && $ct )?( number_format( $all[ $k ]/ ($ct * $v["divisor"] ), 4 ) . ' ' . $v["unit"] ):'' ) .'</td>';
				
				$h .= '<td>'. ( ( $ct )?number_format( $ct, 2 ):0 ) .'</td>';
				
				$ct = ( ( isset( $all[ $k."_max" ] ) && $all[ $k."_max" ] )?( $all[ $k."_max" ] ):0 );
				$h .= '<td>'. ( ( $ct )?( number_format( $ct / $v["divisor"], 4 ) . ' ' . $v["unit"] ):0 ) .'</td>';
				
				$ct = ( ( isset( $all[ $k."_min" ] ) && $all[ $k."_min" ] )?( $all[ $k."_min" ] ):0 );
				$h .= '<td>'. ( ( $ct )?( number_format( $ct / $v["divisor"], 4 ) . ' ' . $v["unit"] ):0 ) .'</td>';
				
			$h .= '</tr>';
		}
		// print_r( $p );exit;
		
		if( $h ){
			echo '<table cellspacingx="10" cellpadding="10" border="1"><thead><tr><th>PARAMETER</th><th>AVG. PERFORMANCE PER REQUEST</th><th>NUMBER OF REQUESTS</th><th>MAX. PERFORMANCE</th><th>MIN. PERFORMANCE</th></tr></thead><tbody>'.$h.'</tbody></table>';
		}
	}
?>
</body>
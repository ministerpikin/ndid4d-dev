<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$hasTable = 1;
	$html1 = '';
	$html1 = '<table class="table table-striped table-hover bordered" style="width:100%;"><thead><tr><th>SN</th><th>User</th><th>Total Days</th><th>% Activity</th></tr></thead><tbody>';
	$html = '';
	
	if( isset( $data[ "data" ] ) && is_array( $data[ "data" ] ) && $data[ "data" ] ){
		foreach( $data[ "data" ] as $k1 => $dv ){
			
			$html1 .= '<tr><td colspan="4"><h4><strong>'. $k1 .'</strong></h4></td></tr>';
			$sn = 0;
			//echo $k1;
			//print_r( '<hr />' );
			
			foreach( $dv as $k2 => $dv1 ){
				
				//$html1 .= '<h4>'. $k2 .'</h4>';
				//print_r( $k2 );
				//print_r( '<hr />' );
				
				foreach( $dv1 as $k3 => $dv2 ){
					
					//print_r( $k3 );
					//print_r( '<hr />' );
					
					$html = '';
					$serial = 0;
					$total = 0;
					
					foreach( $dv2 as $k4 => $dv4 ){
						
						$html .= '<tr>';
							$html .= '<td>';
								$html .= ++$serial;
							$html .= '</td>';
							$html .= '<td>';
								$html .= $k4;
							$html .= '</td>';
							$html .= '<td class="r">';
								$html .= number_format( doubleval( $dv4 ), 0 );
							$html .= '</td>';
						$html .= '</tr>';
						
						$total += $dv4;
						//print_r( $dv4 );
						//print_r( '<hr />' );
					}
					
					
					$html .= '<tr>';
						$html .= '<td></td>';
						$html .= '<td><strong>TOTAL</strong></td>';
						$html .= '<td class="r"><strong>';
							$html .=  number_format( doubleval( $total ), 0 );
						$html .= '</strong></td>';
					$html .= '</tr>';
					
					$html1 .= '<tr>';
					$html1 .= '<td>'. ++$sn .'</td>';
					$html1 .= '<td><details><summary title="Click to view details">'. strtoupper( $k3 ) . ' &rarr;</summary>';
					
					$html1 .= '<table class="table no-csv table-striped table-hover bordered" style="width:100%;"><thead><tr><th>SN</th><th>Date</th><th>Number of Actions</th></tr></thead><tbody>'. $html .'</tbody></table>';
					
					$html1 .= '</details>';
					
					$html1 .= '</td>';
					$html1 .= '<td class="r">'. number_format( $serial, 0 ) .'</td>';
					$html1 .= '<td class="r">'. ( $count?( number_format( ( $total / $count) * 100, 2 ) ):'' ) .'</td>';
					$html1 .= '</tr>';
					
					/* $html1 .= '<details>';
					$html1 .= '<summary>';
					$html1 .= '<h4><span class="btn btn-block btn-default" style="text-align:left;">&nbsp; &rarr; '. strtoupper( $k3 ) .'<code style="float:right; margin-right:5px;">TOTAL DAYS: '.number_format( $serial, 0 ).'</code></span></h4>';
					$html1 .= '</summary>';
					
					$html1 .= '<table class="table table-striped table-hover bordered" style="width:100%;"><thead><tr><th>SN</th><th>Date</th><th>Number of Actions</th></tr></thead><tbody>'. $html .'</tbody></table>';
					
					$html1 .= '</details>'; */
				}
			}
		}
	}
	
	$html1 .= '</tbody></table>';
?>
</div>
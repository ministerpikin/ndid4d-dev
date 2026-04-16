<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$super = 0;
	$access = get_accessed_functions();
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$html1 = '';
	$html = '';
	
	$filename = '';
	$pr = get_project_data();
	$url = $pr['domain_name'] . 'read-trail.php';
	$s_summary = 0;
	//$s_summary = 1;
	
	if( isset( $data[ "data" ] ) && is_array( $data[ "data" ] ) && $data[ "data" ] ){
		foreach( $data[ "data" ] as $k1 => $dv ){
			
			if( ! $s_summary ){
				$html1 .= '<h4><strong>'. $k1 .'</strong></h4>';
			}
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
					
					foreach( $dv2 as $k4 => $dv4 ){
						
						if( is_array( $dv4 ) && ! empty( $dv4 ) ){
							
							foreach( $dv4 as $k5 => $dv5 ){
								if( isset( $index[ $dv5 ]["user"] ) && isset( $index[ $dv5 ]["date"] ) ){
									
									//print_r( $index[ $dv4 ] );
									
									$html .= '<tr>';
										$html .= '<td>';
											$html .= ++$serial;
										$html .= '</td>';
										$html .= '<td>';
											
											if( $super ){
												$html .= '<a href="'.$url.'?j='. rawurlencode( json_encode( array( "f" => $index[ $dv5 ]["file_name"], "k" => isset( $index[ $dv5 ]["id"] )?$index[ $dv5 ]["id"]:'' ) ) ) .'" target="_blank" style1="color:#9393f1;">';
											}else{
												$html .= '<a>';
											}
												$html .= date("d-M-Y H:i", doubleval( $index[ $dv5 ]["date"] ) );
											$html .= '</a>';
											
										$html .= '</td>';
										$html .= '<td>';
											$html .= get_name_of_referenced_record( array( 'id' => $index[ $dv5 ]["user"], 'table' => 'users' ) )	;
										$html .= '</td>';
										$html .= '<td>';
											if( isset( $index[ $dv5 ]["user_action"] ) )$html .= $index[ $dv5 ]["user_action"];
										$html .= '</td>';
										$html .= '<td>';
											if( isset( $index[ $dv5 ]["table"] ) )$html .= $index[ $dv5 ]["table"];
										$html .= '</td>';
									$html .= '</tr>';
								}
							}
							
						}else{
							if( isset( $index[ $dv4 ]["user"] ) && isset( $index[ $dv4 ]["date"] ) ){
								
								//print_r( $index[ $dv4 ] );
								
								$html .= '<tr>';
									$html .= '<td>';
										$html .= ++$serial;
									$html .= '</td>';
									$html .= '<td>';
										
										if( $super ){
											$html .= '<a href="'.$url.'?j='. rawurlencode( json_encode( array( "f" => $index[ $dv4 ]["file_name"], "k" => isset( $index[ $dv4 ]["id"] )?$index[ $dv4 ]["id"]:'' ) ) ) .'" target="_blank" >';
										}else{
											$html .= '<a>';
										}
										
											$html .= date("d-M-Y H:i", doubleval( $index[ $dv4 ]["date"] ) );
											
										$html .= '</a>';
										
									$html .= '</td>';
									$html .= '<td>';
										$html .= get_name_of_referenced_record( array( 'id' => $index[ $dv4 ]["user"], 'table' => 'users' ) )	;
									$html .= '</td>';
									$html .= '<td>';
										if( isset( $index[ $dv4 ]["user_action"] ) )$html .= $index[ $dv4 ]["user_action"];
									$html .= '</td>';
									$html .= '<td>';
										if( isset( $index[ $dv4 ]["table"] ) )$html .= $index[ $dv4 ]["table"];
									$html .= '</td>';
								$html .= '</tr>';
							}
						}
						
						//print_r( $dv4 );
						//print_r( '<hr />' );
					}
					
					if( $s_summary ){
						
						$html1 .= '<tr>';
						$html1 .= '<td>'. strtoupper( get_name_of_referenced_record( array( "id" => $k3, "table" => "users" ) ) ) .'</td><td>'.number_format( $serial, 0 ).'</td>';
						$html1 .= '</tr>';
					}else{
						$html1 .= '<details>';
						$html1 .= '<summary>';
						$html1 .= '<h4><span class="btn btn-block btn-default" style="text-align:left;">&nbsp; &rarr; '. strtoupper( get_name_of_referenced_record( array( "id" => $k3, "table" => "users" ) ) ) .'<code style="float:right; margin-right:5px;">TOTAL ACTIONS: '.number_format( $serial, 0 ).'</code></span></h4>';
						$html1 .= '</summary>';
						
						$html1 .= '<table class="table table-striped table-hover bordered" style="width:100%;"><thead><tr><th>SN</th><th>Date</th><th>User</th><th>Action</th><th>Data Source</th></tr></thead><tbody>'. $html .'</tbody></table>';
						
						$html1 .= '</details>';
					}
					
					
					
				}
			}
			
		}
		
		
		if( $s_summary ){
			$html1 .= '<table class="table table-striped">'. $html1 .'</table>';
		}
	}
	
?>
</div>
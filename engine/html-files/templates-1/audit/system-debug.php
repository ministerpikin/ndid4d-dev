<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	//echo '<pre>';print_r( $data );echo '</pre>'; 
	$html2 = '<h1>Automated System Debugger: '.date("d-M-Y H:i:s").'</h1>';
	$html1 = '';
	foreach( $data as $dk => $dv ){
		if( is_array( $dv ) ){
			//$html1 .= '<h3>' . $dk . '</h3>';
			
			$tit = strtoupper( $dk );
			if( isset( $dv["title"] ) && $dv["title"] ){
				$tit = $dv["title"];
				unset( $dv["title"] );
			}
			
			$html1 .= '<details>';
			$html1 .= '<summary>';
			$html1 .= '<h3><span class="btn btn-block btn-default" style="text-align:left;">&nbsp; &rarr; '. $tit .'<code style="float:right; margin-right:5px;">ERRORS: '.number_format( doubleval( isset( $dv["err"] )?count( $dv["err"] ):0 ), 0 ).'</code></span></h3>';
			$html1 .= '</summary>';
			
			foreach( $dv as $dvk => $dvv ){
				if( is_array( $dvv ) ){
					$html1 .= '<b>'.strtoupper( $dvk ).'</b><ol>';
					switch( $dvk ){
					case "err":
						$html1 .= '<li class="danger-text text-danger" style="margin-bottom:10px;">'. implode( '</li><li class="danger-text text-danger" style="margin-bottom:10px;">', $dvv ) .'</li>';
					break;
					case "fix":
						$html1 .= '<li class="danger-success text-success" style="margin-bottom:10px;">'. implode( '</li><li class="danger-success text-success" style="margin-bottom:10px;">', $dvv ) .'</li>';
					break;
					default:
						$html1 .= '<li style="margin-bottom:10px;">'. implode( '</li><li style="margin-bottom:10px;">', $dvv ) .'</li>';
					break;
					}
					$html1 .= '</ol>';
				}
			}
			
			
			$html1 .= '</details>';
		}else{
			if( $dk == "updated" || $dk == "finished" ){
				$html2 .= '<p><b>' . strtoupper( $dk ) . '</b>: '.date( "d-M-Y H:i:s", doubleval( $dv ) ).'</p>';
			}
		}
	}
	
	echo '<div contenteditable="true"><div contenteditable="false">' . $html2 . $html1 . '</div></div>';
?>
</div>
<?php
	if( is_array( $note ) && ! empty( $note ) ){
		$hn = '';
		foreach( $note as $av ){
			if( isset( $av["message"] ) ){
				$hn .= '<div class="note note-'.( isset( $av["type"] )?$av["type"]:'' ).'">';
				if( isset( $av["title"] ) && $av["title"] ){
					$hn .= '<h4><strong>' . $av["title"] .'</strong></h4>';
				}
				$hn .= '<p>'. $av["message"] .'</p></div>';
			}
		}
		echo $hn;
	}else{
		if( $note )echo '<div class="note note-warning">'. $note .'</div>';
	}

	$h = '';
	if( ( ! empty( $data[ 'general_tasks' ] ) && is_array( $data[ 'general_tasks' ] ) ) || ( ! empty( $data[ 'general_tasks2' ] ) && is_array( $data[ 'general_tasks2' ] ) ) ){
			
		$h .= '<h5><strong>Tasks</strong></h5>';
		$h .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menux" role="menu" style="width:100%;">';
		
		$has = 0;
		if( ! empty( $data[ 'general_tasks' ] ) && is_array( $data[ 'general_tasks' ] ) ){
	// echo '<pre>'; print_r( strrpos( $e[ 'status' ], '.', 2 ) ); echo '</pre>';
			$has = 1;
			$h .= __perpare_buttons( $data[ 'general_tasks' ], $unique_key, $pid, $container );
		}
		
		if( ! empty( $data[ 'general_tasks2' ] ) && is_array( $data[ 'general_tasks2' ] ) ){
			if( $has )$h .= '<li class="divider"></li>';
			$h .= __perpare_buttons( $data[ 'general_tasks2' ], $unique_key, $pid, $container );
		}
		
		$h .= '</ul></div>';
	}


	// echo '<pre>'; print_r( $ags ); echo '</pre>';
	if( ! empty( $ags ) ){
		foreach( $ags as $agd ){
			if( isset( $agd[ 'title' ] ) && ! empty( $agd[ 'actions' ] ) ){
				
				$h .= '<h5><strong>'. $agd[ 'title' ] .'</strong></h5>';
				$h .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menux" role="menu" style="width:100%;">';
				
				foreach( $agd[ 'actions' ] as $kav => $av ){
					if( isset( $av[ 'action' ] ) && $av[ 'action' ] && isset( $av[ 'todo' ] ) && $av[ 'todo' ] ){
						$clasx = 'open-activity-window';
						if( isset( $av[ 'do_not_open_in_new_window' ] ) && $av[ 'do_not_open_in_new_window' ] )$clasx = '';

						$xid = ( isset( $av["custom_id"] ) && $av["custom_id"] )?$av["custom_id"]:$pid;
						
						$ti = '';
						$attrx = '';

						if( isset( $av[ 'new_window' ] ) && $av[ 'new_window' ] ){
							$attrx = ' target="_blank" ';
						}
						
						$ak = $av["action"] . '.' . $av["todo"];
						if( isset( $actions[ $ak ]["count"] ) ){
							$ti = ' title="Performed '. $actions[ $ak ]["count"] .' time(s)" ';
						}
						$params2 = ( isset( $av[ "params" ] ) ? $av[ "params" ] : '' );

						$h .= '<li role="presentation"><a clickme='. $kav .' '.$ti.' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&html_replacement_selector=general-activity-window-'. $unique_key .'&container='. $container . $params2 .'" override-selected-record="'. $xid .'" class="custom-single-selected-record-button '. $clasx .'" '. $attrx .' unique_key="'. $unique_key .'">'. $av["title"] .'</a></li>';
					}
				}
				
				$h .= '</ul></div><br>';
			}
		}
	}
	
	if( ! empty( $data[ 'general_actions' ] ) && is_array( $data[ 'general_actions' ] ) ){
		//$b = $h ? '<br />' : '';
		$b = '';
		
		$b .= '<h5><strong>General Actions</strong></h5>';
		$b .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menux" role="menu" style="width:100%;">';
		
		
		
		foreach( $data[ 'general_actions' ] as $kav => $av ){
			$axt = '';
			if( isset( $av["action"] ) && $av["action"] == 'comments' && $av["todo"] == 'add_comment' ){
				$axt = '&modal_callback=nwOpenModule.loadLatestComment';
			}
			$xid = ( isset( $av["custom_id"] ) && $av["custom_id"] )?$av["custom_id"]:$pid;
			
			$attrx = '';

			if( isset( $av[ 'new_window' ] ) && $av[ 'new_window' ] ){
				$attrx = ' target="_blank" ';
			}

			$b .= '<li role="presentation"><a clickme='. $kav .' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&container='. $container . $axt .'&table='. $tb . ( isset( $av[ 'params' ] ) ? $av[ 'params' ] : '' ) .'" '. $attrx .' override-selected-record="'. $xid .'" class="custom-single-selected-record-button">'. $av["title"] .'</a></li>';
		}
		
		
		$b .= '</ul></div>';
		$h .= $b;
	}
	
	echo $h;
?>
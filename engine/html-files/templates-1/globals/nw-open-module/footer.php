<?php 
	if( isset( $data[ 'footer_buttons' ] ) && $data[ 'footer_buttons' ] ){
		$hx = '';
		
		foreach( $data[ 'footer_buttons' ] as $oak => $oav ){
			$xid = ( isset( $oav["custom_id"] ) && $oav["custom_id"] )?$oav["custom_id"]:$pid;
			$attr = ( isset( $oav["attr"] ) && $oav["attr"] )?$oav["attr"]:'';
			$act = '?action='. $oav[ 'action' ] . '&todo='.$oav[ 'todo' ];
			
			if( isset( $tfoot ) && $tfoot ){
				if( ! isset( $oav[ 'class' ] ) )$oav[ 'class' ] = 'dark';
				$oav[ 'class' ] .= ' btn-sm ';
				$oav['title'] = isset( $oav[ 'text' ] )?$oav[ 'text' ]:'';
				
				if( strpos( $oav[ 'todo' ], "return_comment" ) > -1 ){
					$oav[ 'text' ] = '&larr; Return';
					$oav[ 'class' ] .= ' pull-left ';
				}else if( strpos( $oav[ 'todo' ], "submit_comment" ) > -1 ){
					$oav[ 'text' ] = 'Approve &rarr;';
					$oav[ 'class' ] .= ' pull-right ';
				}
				
				if( isset( $oav[ 'confirm_prompt' ] ) && $oav[ 'confirm_prompt' ] ){
					$oav[ 'confirm_prompt' ] = $oav['title'];
				}
			}
		
			$hx .= '<a href="#" '. ( isset( $oav[ 'attr' ] ) ? $oav[ 'attr' ] : '' ) .' class="btn '. ( isset( $oav[ 'class' ] ) ? $oav[ 'class' ] : 'dark' ) .' custom-single-selected-record-button" override-selected-record="'. $xid .'" action="'. $act .'&html_replacement_selector='. $container.$attr.'" '. ( isset( $oav[ 'confirm_prompt' ] ) ? ' confirm-prompt="'. $oav[ 'confirm_prompt' ] .'" ' : '' ) .' '. ( isset( $oav[ 'title' ] ) ? ' title="'. $oav[ 'title' ] .'" ' : '' ) .'>'. ( isset( $oav[ 'text' ] ) ? $oav[ 'text' ] : 'Submit' ) .'</a>';
		}
		echo $hx;
	}
?>
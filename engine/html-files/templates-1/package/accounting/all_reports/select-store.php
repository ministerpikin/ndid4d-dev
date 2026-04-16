<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	//echo '<pre>';print_r( $data );echo '</pre>';
	$h1 = '';
	$html = '';
	//$dashboards = get_accessible_stores( array(), '', array( 'no_super' => 1 ) );
	//echo '<pre>';print_r( $dashboards );echo '</pre>';
	if( isset( $data["stores"] ) && is_array( $data["stores"] ) && ! empty( $data["stores"] ) ){
		$ps = array();
		foreach( $data["stores"] as $key => $vx ){
			if( isset( $vx["parent"] ) && $vx["parent"] ){
				
			}else{
				//20-jun-23: 2echo
				$cls = 'custom-single-selected-record-button';
				$url = 'javascript:;';
				$act2 = '?action=stores&todo=change_store&add_sel=1';
				
				if( isset( $vx["url"] ) && $vx["url"] ){
					$url = $vx["url"];
					$cls = '';
				}
				
				if( isset( $vx["site"] ) && $vx["site"] ){
					$act2 .= '&site=1';
				}
				
				$html .= '<a href="'.$url.'" action="'.$act2.'" override-selected-record="'. $vx["id"] .'" class=" list-group-item '.$cls.'"><big><strong>'. strtoupper( ( isset( $vx[ 'text' ] ) ? $vx[ 'text' ] : '' ) ) .'</strong></big></a>';
			}
		}
		
		if( $html ){
			//$h1 .= '<br />';
			$usr = '';
			if( isset( $user_info["user_initials"] ) )$usr = $user_info["user_initials"];
			$h1 .= '<h2 style="color:#fff;"><small style="color:#ddd;">Welcome '. $usr .',<br /></small><strong>Select Your Work Station</strong></h2>';
			$h1 .= '<div class="main-cardx mb-3x cardx">';
				$h1 .= '<ul class="list-group">';
					$h1 .= $html;
				$h1 .= '</ul>';
			$h1 .= '</div>';
		}
	}
	
	$h2 = '';
	if( defined("NWP_HR_PORTAL") && NWP_HR_PORTAL && class_exists( 'cNwp_human_resource' ) ){
		$h2 .= '<a href="javascript:;" action="?action=nwp_human_resource&todo=execute&nwp_action=frontend_users&nwp_todo=portal_access" override-selected-record="none" class=" list-group-item custom-single-selected-record-button"><big><strong>HR Portal</strong></big></a>';
	}
	
	echo '<div class="row"><div class="col-md-4"></div><div class="col-md-4">';
	if( $h1 ){
		echo $h1;
	}else{
		echo '<a href="javascript:;" action="?action=stores&todo=change_store&add_sel=1" override-selected-record="none" class=" list-group-item custom-single-selected-record-button"><big><strong>My Work Station</strong></big></a>';
	}
	echo $h2 . '</div></div>';
?>
</div>
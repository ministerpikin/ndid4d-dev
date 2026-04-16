<?php
	$current_store = isset( $data["current_store"] )?$data["current_store"]:''; 
	$current_tab = isset( $data["current_tab"] )?$data["current_tab"]:''; 
	$current_tab = 'report'; 
	$current_tab = 'report_bay'; 
	$development = get_hyella_development_mode();
	$customer_id  = isset( $data["id"] )?$data["id"]:'-'; 

	$h = '';
	$first = 1;

	$menu2 = system_version2_menu();
	$submenu2 = system_version2_submenu();

	foreach( $menu2 as $mkey => $mval ){
		
		if( ! isset( $mval[ "tab" ][ $current_tab ] ) ){
			continue;
		}
		
		$show_menu = 0;
		$html2 = '';
		$html1 = '';
		
		$main_menu_key = $mkey;

			if( isset( $mval["sub_menu"] ) && is_array( $mval["sub_menu"] ) && ! empty( $mval["sub_menu"] ) ){
		
				foreach( $mval["sub_menu"] as $smval ){
					if( isset( $submenu2[ $smval ] ) ){
						
						$menu_key = $smval;
						$show_menu = 1;
						$tooltip = '';
						if( isset( $submenu2[ $smval ]["tooltip"] ) && $submenu2[ $smval ]["tooltip"] ){ 
							$tooltip = $submenu2[ $smval ]["tooltip"];
						}
						
						if( isset( $submenu2[ $smval ]["sub_menu"] ) && is_array( $submenu2[ $smval ]["sub_menu"] ) && ! empty( $submenu2[ $smval ]["sub_menu"] ) ){
							
							$h_subs = '';
							
							foreach( $submenu2[ $smval ]["sub_menu"] as $s2 ){
								if( isset( $submenu2[ $s2 ] ) ){
									$menu_key = $s2;
									
									if( ( ! $development && ! isset( $submenu2[ $s2 ]["development"] ) ) || $development ){
										if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
											
											$h_subs .= '<li class="nav-item">';
												$h_attr = '';
												
												$tooltip2 = '';
												if( isset( $submenu2[ $s2 ]["tooltip"] ) && $submenu2[ $s2 ]["tooltip"] ){ 
													$tooltip2 = $submenu2[ $s2 ]["tooltip"];
												}

												if( isset( $submenu2[ $s2 ][ 'link' ] ) && $submenu2[ $s2 ][ 'link' ] ){
													$h_attr = isset( $submenu2[ $s2 ]["new_window"] ) ? ' target="_blank" ' : '';
													$h_attr .= ' class="nav-link" ';
												}else{
													$h_attr = 'action="?action='. $submenu2[ $s2 ]["class"] . '&todo=' . $submenu2[ $s2 ]["action"] . '&html_replacement_selector='.  $container . '&is_menu=1&current_tab='.$current_tab . ( isset( $submenu2[ $s2 ]["params"] ) ? $submenu2[ $s2 ]["params"] : '' ) .'&menu_title='. rawurlencode( ( isset( $submenu2[ $s2 ][ "full_title" ] ) && $submenu2[ $s2 ][ "full_title" ] )?$submenu2[ $s2 ][ "full_title" ]:$submenu2[ $s2 ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'" class="custom-single-selected-record-button nav-link" ';
												}

												$h_subs .= '<a href="'. ( isset( $submenu2[ $s2 ]["link"] ) && $submenu2[ $s2 ]["link"] ? $submenu2[ $s2 ]["link"] : '#' ) .'" override-selected-record="' . $customer_id . '" '. $h_attr .'  title="'.$tooltip2.'">' . $submenu2[ $s2 ][ "title" ] . '</a>';
											$h_subs .= '</li>';
											
										}
									}
								}
							}
							
							if( $h_subs ){
								$html1 .= '<li class="nav-item">';
								
								$html1 .= '<a href="#'.$smval.'" class="nav-link menu-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="'.$smval.'" askey="t-'.$smval.'">';
									if ( isset( $submenu2[ $smval ]['icon'] ) && $submenu2[ $smval ]['icon'] ) {
										$html1 .= '<i class="'. $submenu2[ $smval ]['icon'] .'"></i>';
									}
									$html1 .= '<span data-key="t-'. strtolower( str_replace(' ', '-',  $submenu2[ $smval ][ "title" ] ) ) .'">' . $submenu2[ $smval ][ "title" ] . '</span>';
								$html1 .= '</a>';
								
								$html1 .= '<div class="collapse menu-dropdown" id="'.$smval.'">';
									$html1 .= '<ul class="nav nav-sm flex-column">';
									$html1 .= $h_subs;
									$html1 .= '</ul>';
								$html1 .= '</div>';
								
								$html1 .= '</li>';
							}else{
								
								if( ( ! $development && ! isset( $submenu2[ $smval ]["development"] ) ) || $development ){
									if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
										$html1 .= '<li>';
										
											$html1 .= '<a href="#" class="';
											if( isset( $submenu2[ $smval ]["class"] ) && $submenu2[ $smval ]["class"] ){ 
												$html1 .= 'custom-single-selected-record-button menu-link';
											}
											
											$html1 .= '" override-selected-record="' . $customer_id . '" action="?action=';
											if( isset( $submenu2[ $smval ]["class"] ) ){
												$html1 .= $submenu2[ $smval ]["class"];
											}
											
											if( isset( $submenu2[ $smval ]["action"] ) ){
												$html1 .= '&todo=' . $submenu2[ $smval ]["action"];
											}
											$html1 .= '&html_replacement_selector='. $container . '&is_menu=1&current_tab='.$current_tab.'&menu_title='. rawurlencode( ( isset( $submenu2[ $smval ][ "full_title" ] ) && $submenu2[ $smval ][ "full_title" ] )?$submenu2[ $smval ][ "full_title" ]:$submenu2[ $smval ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'"  title="'. $tooltip .'">';

											if ( isset( $submenu2[ $smval ]['icon'] ) && $submenu2[ $smval ]['icon'] ) {
												$html1 .= '<i class="'. $submenu2[ $smval ]['icon'] .'"></i>';
											}

											$html1 .= '<span>'. $submenu2[ $smval ][ "title" ] . '</span>';
										
										$html1 .= '</a>';
										$html1 .= '</li>';
									}
								}
							}
						}else{
							if( ( ! $development && ! isset( $submenu2[ $smval ]["development"] ) ) || $development ){
								if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
									$html1 .= '<li class="nav-item">';
									
										$h_attr = '';

										if( isset( $submenu2[ $smval ][ 'link' ] ) && $submenu2[ $smval ][ 'link' ] ){
											$h_attr = isset( $submenu2[ $smval ]["new_window"] ) ? ' target="_blank" ' : '';
											$h_attr .= ' class="nav-link" ';
										}else{
											$h_attr = 'action="?action=' . $submenu2[ $smval ]["class"] . '&todo=' . $submenu2[ $smval ]["action"] . '&html_replacement_selector=' . $container . '&is_menu=1&current_tab='.$current_tab . ( isset( $submenu2[ $smval ]["params"] ) ? $submenu2[ $smval ]["params"] : '' ) .'&menu_title='. rawurlencode( ( isset( $submenu2[ $smval ][ "full_title" ] ) && $submenu2[ $smval ][ "full_title" ] )?$submenu2[ $smval ][ "full_title" ]:$submenu2[ $smval ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'" class="custom-single-selected-record-button nav-link menu-link" ';
										}

									$html1 .= '<a href="'. ( isset( $submenu2[ $smval ]["link"] ) && $submenu2[ $smval ]["link"] ? $submenu2[ $smval ]["link"] : '#' ) .'" override-selected-record="' . $customer_id . '" '. $h_attr .'  title="'. $tooltip .'" data-key="t-'.$smval.'">';
									
									if( isset( $submenu2[ $smval ]["icon"] ) && $submenu2[ $smval ]["icon"] )$html1 .= '<i class="'.$submenu2[ $smval ]["icon"].'"></i> ';
									
									$html1 .= '<span>' . $submenu2[ $smval ][ "title" ] . '</span>';
									if( isset( $submenu2[ $smval ]["flag"] ) && $submenu2[ $smval ]["flag"] ){
										$fcls = isset( $submenu2[ $smval ]["flag_color"] )?$submenu2[ $smval ]["flag_color"]:'danger';
										$html1 .= '<span class="badge badge-pill bg-'.$submenu2[ $smval ]["flag_color"].'" data-key="t-hot">'.$submenu2[ $smval ]["flag"].'</span>';
									}
									$html1 .= '</a>';
									
									$html1 .= '</li>';
								}
							}
						}
					}
				}
				
				if( $html1 ){
				   $hidden = 'hidden';
				   $picon = 'icon-caret-left';
				   if( $first ){
					   $hidden = '';
					   $picon = 'icon-caret-down';
				   }
				   $picon = '';
				   $first = 0;
				   
					$html2 .= '<li class="menu-title"><span data-key="t-'. md5( $mval["title"] ) .'">'. $mval["title"] .' <i class="'.$picon.' pull-right"></i></span></li>';
					
					$html2 .= $html1;
				}
			}else{
				
				if( isset( $access["accessible_functions"][ $main_menu_key ] ) || $super ){
					$html2 .= '<li class="nav-item">';
					   $html2 .= '<a href="javascript:;" class="nav-link custom-single-selected-record-button" override-selected-record="'. $customer_id . '" action="?action=' . $mval["class"] . '&todo=' . $mval["action"] . '&html_replacement_selector=' .  $container . '&menu_title='. rawurlencode( ( isset( $mval[ "full_title" ] ) && $mval[ "full_title" ] )?$mval[ "full_title" ]:$mval[ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'&is_menu=1&current_tab='.$current_tab.'"  title="'. $tooltip .'" data-key="t-'.$main_menu_key.'">';
					   if( isset( $mval["icon"] ) && $mval["icon"] )$html2 .= '<i class="'. $mval["icon"] . '"></i> ';
					   $html2 .= '<span class="title"> '.  $mval["title"] . '</span>';
					   $html2 .= '</a>';
					$html2 .= '</li>';
				}
			}
			
			$h .= $html2;
		
		//}
	}

	echo $h;
?>
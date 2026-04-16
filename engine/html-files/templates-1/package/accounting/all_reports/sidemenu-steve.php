<?php 
	$show_parents = 1;
	$return_items = 0;
	
	if( ! ( isset( $current_tab ) && $current_tab ) ){
		$current_tab = isset( $data["current_tab"] )?$data["current_tab"]:''; 
	}
	
	$development = get_hyella_development_mode();
	$current_store = isset( $data["current_store"] )?$data["current_store"]:''; 
	
	if( ! ( isset( $customer_id  ) && $customer_id  ) ){
		$customer_id  = isset( $data["id"] )?$data["id"]:'-'; 
	}
	
	if( ! ( isset( $sidemenu_theme  ) && $sidemenu_theme  ) ){
		$sidemenu_theme_cls  = 'side-menu-dark';
		$sidemenu_theme  = 'background:#494a4a; color:#fff;'; 
	}else{
		$sidemenu_theme_cls = '';
	}
	
	
	//make shift
	//$super = 1;
	
	if( isset( $data["menu_type"] ) ){
		switch( $data["menu_type"] ){
		case 1:	//return only menu
			$show_parents = 0;
			$return_items = 1;
			$add_content_margin = 0;
		break;
		}
	}
	
	if( $show_parents ){
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<!-- BEGIN SIDEBAR -->
<div class="app-sidebar sidebar-shadow <?php echo $sidemenu_theme_cls; ?>">
	<div class="app-header__logo">
		<div class="logo-src"></div>
		<div class="header__pane ml-auto">
			<div>
				<button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>
			</div>
		</div>
	</div>
	<div class="app-header__mobile-menu">
		<div>
			<button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</button>
		</div>
	</div>
	<div class="app-header__menu">
		<span>
			<button type="button" class="btn-icon btn-icon-only btn btn-success btn-sm mobile-toggle-header-nav">
				<span class="btn-icon-wrapper">
					<i class="fa fa-ellipsis-v fa-w-6"></i>
				</span>
			</button>
		</span>
	</div>    
	<div class="scrollbar-sidebar" style="<?php echo $sidemenu_theme; ?>">
		<div class="app-sidebar__inner" style="max-height: 100%; overflow-y: auto;">
			<ul class="vertical-nav-menu">
<?php
		
		if( isset( $dashboard_link  ) ){
			echo $dashboard_link;
		}
	}
		
		if( ! isset( $menu2 ) ){
			$package = get_package_option( array( "package" => 1 ) );
			
			if( function_exists( $package . "_version2_menu" ) ){
				$f = $package . "_version2_menu";
				$menu2 = $f();
			}
			
			if( function_exists( $package . "_version2_submenu" ) ){
				$f = $package . "_version2_submenu";
				$submenu2 = $f();
			}
			
			if( function_exists( $package . "_version2_menu_settings" ) ){
				$f = $package . "_version2_menu_settings";
				$menu2_settings = $f();
			}
			
		}
		// echo '<pre>';print_r( $menu2 );echo '</pre>';
		
		$container2 = '';
		if( isset( $data["container"] ) ){
			$container2 = $data["container"] . '-sub';
		}
		
		$h = '';
		$first = 1;
		
		foreach( $menu2 as $mkey => $mval ){
			
			if( ! isset( $mval[ "tab" ][ $current_tab ] ) ){
				continue;
			}
			
			$show_menu = 0;
			$html2 = '';
			$html1 = '';
			
			$main_menu_key = $mkey;
			//$main_menu_key = md5( $menu2_settings["main_prefix"] . $mkey );
			
			//if( isset( $access["accessible_functions"][ $main_menu_key ] ) || $super ){
			
				if( isset( $mval["sub_menu"] ) && is_array( $mval["sub_menu"] ) && ! empty( $mval["sub_menu"] ) ){
			
					foreach( $mval["sub_menu"] as $smval ){
						if( isset( $submenu2[ $smval ] ) ){
							
							$menu_key = $smval;
							//$menu_key = md5( $menu2_settings["prefix"] . $smval );
							//echo $menu_key . '<br />';
							
							//$html1 .= '<li>';
							$show_menu = 1;
							
							$tooltip = '';
							if( isset( $submenu2[ $smval ]["tooltip"] ) && $submenu2[ $smval ]["tooltip"] ){ 
								$tooltip = $submenu2[ $smval ]["tooltip"];
							}
							
							if( isset( $submenu2[ $smval ]["sub_menu"] ) && is_array( $submenu2[ $smval ]["sub_menu"] ) && ! empty( $submenu2[ $smval ]["sub_menu"] ) ){
								
								$h_subs = '';
								
								foreach( $submenu2[ $smval ]["sub_menu"] as $s2 ){
									if( isset( $submenu2[ $s2 ]["class"] ) ){
										$menu_key = $s2;
										//$menu_key = md5( $menu2_settings["prefix"] . $s2 );
										
										if( ( ! $development && ! isset( $submenu2[ $s2 ]["development"] ) ) || $development ){
											if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
												$h_subs .= '<li>';
													$h_attr = '';
													
													$tooltip2 = '';
													if( isset( $submenu2[ $s2 ]["tooltip"] ) && $submenu2[ $s2 ]["tooltip"] ){ 
														$tooltip2 = $submenu2[ $s2 ]["tooltip"];
													}

													if( isset( $submenu2[ $s2 ][ 'link' ] ) && $submenu2[ $s2 ][ 'link' ] ){
														$h_attr = isset( $submenu2[ $s2 ]["new_window"] ) ? ' target="_blank" ' : '';
													}else{
														$h_attr = 'action="?action='. $submenu2[ $s2 ]["class"] . '&todo=' . $submenu2[ $s2 ]["action"] . '&html_replacement_selector='.  $container2 . '&is_menu=1&current_tab='.$current_tab . ( isset( $submenu2[ $s2 ]["params"] ) ? $submenu2[ $s2 ]["params"] : '' ) .'&menu_title='. rawurlencode( ( isset( $submenu2[ $s2 ][ "full_title" ] ) && $submenu2[ $s2 ][ "full_title" ] )?$submenu2[ $s2 ][ "full_title" ]:$submenu2[ $s2 ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'" class="custom-single-selected-record-button" ';
													}

													$h_subs .= '<a href="'. ( isset( $submenu2[ $s2 ]["link"] ) && $submenu2[ $s2 ]["link"] ? $submenu2[ $s2 ]["link"] : '#' ) .'" override-selected-record="' . $customer_id . '" '. $h_attr .'  title="'.$tooltip2.'">' . $submenu2[ $s2 ][ "title" ] . '</a>';
												$h_subs .= '</li>';
											}
										}
									}
								}
								
								if( $h_subs ){
									$html1 .= '<li class="app-sidebar__heading">';
									
									$html1 .=  $submenu2[ $smval ][ "title" ];
									$html1 .=  ' <i class="icon-caret-left pull-right"></i></li>';
									
									$html1 .= '<li><ul class="sub-menu hidden">' . $h_subs . '</ul>';
									$html1 .= '</li>';
								}else{
									
									if( ( ! $development && ! isset( $submenu2[ $smval ]["development"] ) ) || $development ){
										if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
											$html1 .= '<li>';
											
											$html1 .= '<a href="#" class="';
											if( isset( $submenu2[ $smval ]["class"] ) && $submenu2[ $smval ]["class"] ){ 
												$html1 .= 'custom-single-selected-record-button';
											}
											
											$html1 .= '" override-selected-record="' . $customer_id . '" action="?action=';
											if( isset( $submenu2[ $smval ]["class"] ) ){
												$html1 .= $submenu2[ $smval ]["class"];
											}
											
											if( isset( $submenu2[ $smval ]["action"] ) ){
												$html1 .= '&todo=' . $submenu2[ $smval ]["action"];
											}
											$html1 .= '&html_replacement_selector='. $container2 . '&is_menu=1&current_tab='.$current_tab.'&menu_title='. rawurlencode( ( isset( $submenu2[ $smval ][ "full_title" ] ) && $submenu2[ $smval ][ "full_title" ] )?$submenu2[ $smval ][ "full_title" ]:$submenu2[ $smval ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'"  title="'. $tooltip .'">';
											$html1 .=  $submenu2[ $smval ][ "title" ];
											
											$html1 .= '</a>';
											$html1 .= '</li>';
										}
									}
									
								}
							}else{
								if( ( ! $development && ! isset( $submenu2[ $smval ]["development"] ) ) || $development ){
									if( isset( $access["accessible_functions"][ $menu_key ] ) || $super ){
										$html1 .= '<li>';
										
											$h_attr = '';

											if( isset( $submenu2[ $smval ][ 'link' ] ) && $submenu2[ $smval ][ 'link' ] ){
												$h_attr = isset( $submenu2[ $smval ]["new_window"] ) ? ' target="_blank" ' : '';
											}else{
												$h_attr = 'action="?action=' . $submenu2[ $smval ]["class"] . '&todo=' . $submenu2[ $smval ]["action"] . '&html_replacement_selector=' . $container2 . '&is_menu=1&current_tab='.$current_tab . ( isset( $submenu2[ $smval ]["params"] ) ? $submenu2[ $smval ]["params"] : '' ) .'&menu_title='. rawurlencode( ( isset( $submenu2[ $smval ][ "full_title" ] ) && $submenu2[ $smval ][ "full_title" ] )?$submenu2[ $smval ][ "full_title" ]:$submenu2[ $smval ][ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'" class="custom-single-selected-record-button" ';
											}

										$html1 .= '<a href="'. ( isset( $submenu2[ $smval ]["link"] ) && $submenu2[ $smval ]["link"] ? $submenu2[ $smval ]["link"] : '#' ) .'" override-selected-record="' . $customer_id . '" '. $h_attr .'  title="'. $tooltip .'">' . $submenu2[ $smval ][ "title" ] . '</a>';
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
					   $first = 0;
					   
						$html2 .= '<li class="app-sidebar__heading">'. $mval["title"] .' <i class="'.$picon.' pull-right"></i></li>';
						
						$html2 .= '<li>';
						   /*
						   $html2 .= '<a href="javascript:;">';
						   $html2 .= '<i class="metismenu-icon ' . $mval["icon"] . '"></i>';
						   $html2 .= $mval["title"];
						   $html2 .= '<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>';
						   $html2 .= '</a>';
						   */
						   
						   $html2 .= '<ul class="sub-menu '. $hidden .'">';
						   $html2 .= $html1 . '</ul>';
						$html2 .= '</li>';
					}
				}else{
					if( isset( $access["accessible_functions"][ $main_menu_key ] ) || $super ){
						$html2 .= '<li>';
						   $html2 .= '<a href="#" class="custom-single-selected-record-button" override-selected-record="'. $customer_id . '" action="?action=' . $mval["class"] . '&todo=' . $mval["action"] . '&html_replacement_selector=' .  $container2 . '&menu_title='. rawurlencode( ( isset( $mval[ "full_title" ] ) && $mval[ "full_title" ] )?$mval[ "full_title" ]:$mval[ "title" ] ) .'&branch='. $customer_id .'&current_store='. $current_store .'&is_menu=1&current_tab='.$current_tab.'"  title="'. $tooltip .'">';
						   $html2 .= '<i class="'. $mval["icon"] . '"></i> ';
						   $html2 .= '<span class="title">'.  $mval["title"] . '</span>';
						   $html2 .= '</a>';
						$html2 .= '</li>';
					}
				}
				
				$h .= $html2;
			
			//}
		}
		
		if( $return_items ){
			$returned_data["menu_items"] = '';
			
			if( isset( $dashboard_link  ) ){
				$returned_data["menu_items"] .= $dashboard_link;
			}
			$returned_data["menu_items"] .= $h;
		}else{
			echo $h;
		}
		
	if( $show_parents ){
	?>
			</ul>
		</div>
	</div>
</div>
<!-- END SIDEBAR -->
<script type="text/javascript">
	/*
	$('li.app-sidebar__heading')
	.on("click", function( e ){
		
		$(this)
		.siblings()
		.find("i")
		.removeClass("icon-caret-down")
		.addClass("icon-caret-left");
		
		$(this)
		.siblings()
		.next()
		.find("ul")
		.addClass("hidden");
		
		$(this)
		.find("i")
		.removeClass("icon-caret-left")
		.addClass("icon-caret-down");
		
		$(this)
		.next()
		.find("ul:first")
		.hide()
		.removeClass("hidden")
		.slideDown();
		
	});
	*/
	setTimeout( function(){ $('#dashboard-link').click(); }, 800 );
</script>
</div>
<?php } ?>
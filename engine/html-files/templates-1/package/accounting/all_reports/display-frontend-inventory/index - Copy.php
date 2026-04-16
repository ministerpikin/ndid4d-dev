<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:''; 
	
	$current_tab = '';
	$dp = '';
	
	$container2 = '';
	if( isset( $data["container"] ) ){
		$container2 = $data["container"] . '-sub';
	}
	
	include dirname( dirname( __FILE__ ) ) . "/sidemenu.php"; 
?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar navbar-collapse collapse">
 
	<ul class="page-sidebar-menu">
	<!-- BEGIN SIDEBAR TOGGLER BUTTON
	<li>
	   <div class="sidebar-toggler hidden-phone"></div>
	</li>
	BEGIN SIDEBAR TOGGLER BUTTON -->
	<li class="start active ">
	    <a href="#" onclick="$.fn.cCallBack.submitForm( '<?php if( isset( $data["search_form_id"] ) )echo $data["search_form_id"]; ?>' );">
	   <i class="icon-home"></i> 
	   <span class="title">Dashboard</span>
	   <span class="selected"></span>
	   </a>
	</li>
	<?php 
		$customer_id = '-';
		
		$menu2 = accounting_version2_menu();
		$submenu2 = accounting_version2_submenu();
		
		$menu2_settings = accounting_version2_menu_settings();
		
		$container2 = '';
		if( isset( $data["container"] ) ){
			$container2 = $data["container"] . '-sub';
		}
		
		foreach( $menu2 as $mkey => $mval ){
			
			$show_menu = 0;
			$html1 = '';
			
			$main_menu_key = md5( $menu2_settings["main_prefix"] . $mkey );
			
			if( isset( $access[ $main_menu_key ] ) || $super ){
			
			if( isset( $mval["sub_menu"] ) && is_array( $mval["sub_menu"] ) && ! empty( $mval["sub_menu"] ) ){
				
				$html1 .= '<li>';
				   $html1 .= '<a href="javascript:;">';
				   $html1 .= '<i class="' . $mval["icon"] . '>"></i>';
				   $html1 .= '<span class="title">' . $mval["title"] . '</span>';
				   $html1 .= '<span class="arrow "></span>';
				   $html1 .= '</a>';
				   $html1 .= '<ul class="sub-menu">';
				
						foreach( $mval["sub_menu"] as $smval ){
							if( isset( $submenu2[ $smval ] ) ){
								
							$menu_key = md5( $menu2_settings["prefix"] . $smval );
							//echo $menu_key . '<br />';
								
							if( isset( $access[ $menu_key ] ) || $super ){
								
								$html1 .= '<li>';
								$show_menu = 1;
								if( isset( $submenu2[ $smval ]["sub_menu"] ) && is_array( $submenu2[ $smval ]["sub_menu"] ) && ! empty( $submenu2[ $smval ]["sub_menu"] ) ){
									
									
									$html1 .= '<a href="#" class="';
									if( isset( $submenu2[ $smval ]["class"] ) && $submenu2[ $smval ]["class"] ){ 
										$html1 .= 'custom-single-selected-record-button';
									}
									
									$html1 .= '" override-selected-record="' . $customer_id . '" action="?action=';
									if( isset( $submenu2[ $smval ]["class"] ) )$html1 .= $submenu2[ $smval ]["class"];
									$html1 .= '&todo=' . $submenu2[ $smval ]["action"] . '&html_replacement_selector='. $container2 . '">';
									$html1 .=  $submenu2[ $smval ][ "title" ];
									
									$html1 .= '<span class="arrow "></span>';
									$html1 .= '</a>';
									$html1 .= '<ul class="sub-menu">';
									
									foreach( $submenu2[ $smval ]["sub_menu"] as $s2 ){
										if( isset( $submenu2[ $s2 ] ) ){
											$menu_key = md5( $menu2_settings["prefix"] . $s2 );
											
											if( isset( $access[ $menu_key ] ) || $super ){
												$html1 .= '<li>';
													$html1 .= '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $customer_id . '" action="?action='. $submenu2[ $s2 ]["class"] . '&todo=' . $submenu2[ $s2 ]["action"] . '&html_replacement_selector='.  $container2 . '">' . $submenu2[ $s2 ][ "title" ] . '</a>';
												$html1 .= '</li>';
											}
										}
									}
									
									$html1 .= '</ul>';
								}else{
									$html1 .= '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $customer_id . '" action="?action=' . $submenu2[ $smval ]["class"] . '&todo=' . $submenu2[ $smval ]["action"] . '&html_replacement_selector=' . $container2 . '">' . $submenu2[ $smval ][ "title" ] . '</a>';
								}
								$html1 .= '</li>';
							}
							
							}
						}
						
				   $html1 .= '</ul>';
				$html1 .= '</li>';
				
			}else{
				$html1 .= '<li>';
				   $html1 .= '<a href="#" class="custom-single-selected-record-button" override-selected-record="'. $customer_id . '" action="?action=' . $mval["class"] . '&todo=' . $mval["action"] . '&html_replacement_selector=' .  $container2 . '">';
				   $html1 .= '<i class="'. $mval["icon"] . '"></i> ';
				   $html1 .= '<span class="title">'.  $mval["title"] . '</span>';
				   $html1 .= '</a>';
				$html1 .= '</li>';
			}
			
			//if( $show_menu ){
				echo $html1;
			//}
			}
		}
	?>
	
 </ul>
 
</div>
<!-- END SIDEBAR -->

<div style="margin-left:250px; min-height:800px;">
	
	<div id="<?php echo $container2; ?>">
		<div class="row">
			<div class="col-md-5">
				<?php 
					//$data["show_access_menu"] = '';
					//include "customer-info.php"; 
				?>
			</div>
			<div class="col-md-7">
				<div id="special-point-container2"></div>
				<div id="profile-details">
				<?php 
					//$data['customer'][0] = $data['customer'];
					//include "profile-details.php";  
				?>
				</div>
			</div>
		</div>
	</div>
	
</div>

</div>
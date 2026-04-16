<div class="row">
	<?php 
		$h1 = '';
		$tabs = frontend_tabs();
		
		$current_state = get_current_customer( "state" );
		
		$access = get_accessed_functions();
		$super = 0;
		if( ! is_array( $access ) && $access == 1 ){
			$super = 1;
		}
		if( ! $super ){
			//make shift
			if( isset( $access["states"][ $current_state ] ) ){
				$state = $current_state;
			}else if( isset( $access["states"] ) && ! empty( $access["states"] ) ){
				foreach( $access["states"] as $st => $sv ){
					$state = $st;
					break;
				}
			}
			
			if( isset( $state ) && $state ){
				set_current_customer( array( 'state' => $state ) );
				
				$stl = strtoupper( get_name_of_referenced_record( array( "id" => $state, "table" => "state_list" ) ) );
				
				$tabs["ssr-tab"]["title"] .= " - " . $stl;
				$tabs["socu-tab"]["title"] .= " - " . $stl;
			}
		}
		
		$html = '';
		if( ! empty( $tabs ) ){
			$limit = 0;
			foreach( $tabs as $key => $value ){
				
				if( isset( $value[ "dashboard" ] ) && $value[ "dashboard" ] ){
					if( ! $super ){
						
						$yes_access_role = 0;
						if( isset( $access["accessible_functions"][ "frontend_tabs." . $key ] ) ){
							$yes_access_role = 1;
						}
						if( ! $yes_access_role )continue;
					}
					++$limit;
					
					if( $limit > 12 )continue;

					$html .= '<div class="col-sm-6">';
						$html .= '<button class="btn-icon-vertical btn-square btn-transition btn btn-outline-link custom-single-selected-record-button" action="'. $value[ 'action' ] .'" override-selected-record="1"><strong><i class="'. ( isset( $value[ 'dashboard_icon' ] ) ? $value[ 'dashboard_icon' ] : '' ) .' btn-icon-wrapper btn-icon-lg mb-3" style="opacity:1;"> </i>'. $value[ 'title' ] .'</strong></button>';
					$html .= '</div>';
				}
			}
			
			if( $html ){
				$h1 .= '<h4 class="card-title"><strong>Quick Access</strong></h4>';
				$h1 .= '<div class="card">';
					$h1 .= '<div class="grid-menu grid-menu-2col">';
						$h1 .= '<div class="no-gutters row">';
							$h1 .= $html;
						$h1 .= '</div>';
					$h1 .= '</div>';
				$h1 .= '</div><br />';
				
				//echo '<div class="col-md-12">' . $h1 . '</div></div><br /><div class="row">';
				//$h1 = '';
			}
		}
		
		$dashboards = array();
		if( function_exists("dashboard_dashboard_menus") ){
			$dashboards = dashboard_dashboard_menus();
		}
		
		$menus = array();
		$package = get_package_option( array( "package" => 1 ) );	
		if( function_exists( $package . "_version2_submenu" ) ){
			$f = $package . "_version2_submenu";
			$menus = $f();
		}
			
		$html = '';
		if( ! empty( $dashboards ) ){
			
			foreach( $dashboards as $key => $value ){
				if( isset( $menus[ $key ] ) && $menus[ $key ] && ( isset( $access["accessible_functions"][ $key ] ) || $super ) ){
					$html .= '<a href="#" action="?action='. ( isset( $menus[ $key ][ 'class' ] ) ? $menus[ $key ][ 'class' ] : '' ) .'&todo='. ( isset( $menus[ $key ][ 'action' ] ) ? $menus[ $key ][ 'action' ] : '' ) .'&html_replacement_selector='. $container2 .'" override-selected-record="-" class=" list-group-item custom-single-selected-record-button"><strong>'. ( isset( $value[ 'title' ] ) ? $value[ 'title' ] : '' ) .'</strong></a>';
				}
			}
			
			if( $html ){
				$h1 .= '<div class="main-cardx mb-3x cardx">';
				//$h1 .= '<br />';
				$h1 .= '<h4 class="card-title"><strong>Dashboards</strong></h4>';
					$h1 .= '<ul class="list-group">';
						$h1 .= $html;
					$h1 .= '</ul>';
				$h1 .= '</div>';
			}
		}
		
		if( $h1 ){
			echo '<div class="col-md-4">' . $h1 . '</div>';
		}
		?>
	
	<div class="col-md-4">
		<h4 class="card-title"><strong>Notifications</strong></h4>
		<?php
			$comments = isset( $data[ 'comments' ] ) ? $data[ 'comments' ] : 0;
			$tickets = isset( $data[ 'tickets' ] ) ? $data[ 'tickets' ] : 0;
			$training_participants = isset( $data[ 'training_participants' ] ) ? $data[ 'training_participants' ] : 0;
			$notifications = isset( $data[ 'notifications' ] ) ? $data[ 'notifications' ] : 0;
		?>
		<div class="main-card mb-3 card">
			<div class="grid-menu grid-menu-2col">
				<div class="no-gutters row">
					<div class="col-sm-6">
						<!--<a class="custom-single-selected-record-button" action="?action=tags&todo=display_shared_with_me&get_children=1&html_replacement_selector=<?php //echo $container2; ?>"override-selected-record="1">-->
						<a class="custom-single-selected-record-button" action="?action=share&todo=display_shared_with_me_unread&get_children=1&html_replacement_selector=<?php echo $container2; ?>"override-selected-record="1">
							<div class="widget-chart widget-chart-hover">
								<div class="icon-wrapper rounded-circle" style="display: flex;">
									<div class="icon-wrapper-bg bg-primary"></div>
									<i class="icon-folder-open text-primary"></i></div>
								<div class="widget-numbers"><?php echo nw_pretty_number( $comments ); ?></div>
								<div class="widget-subheading">Shared with me</div>
							</div>
						</a>
					</div>
					<div class="col-sm-6">
						<a class="custom-single-selected-record-button" action="?action=notifications&todo=display_my_notifications&html_replacement_selector=<?php echo $container2; ?>"override-selected-record="1">
							<div class="widget-chart widget-chart-hover">
								<div class="icon-wrapper rounded-circle" style="display: flex;">
									<div class="icon-wrapper-bg bg-info"></div>
									<i class="icon-bell text-info"></i>
								</div>
								<div class="widget-numbers"><?php echo nw_pretty_number( $notifications ); ?></div>
								<div class="widget-subheading">System Notice</div>
							</div>
						</a>
					</div>
					<?php if( ! defined("HYELLA_NO_MORE_DATA") ){ ?>
					<div class="col-sm-6">
						<a class="custom-single-selected-record-button" action="?action=tickets&todo=my_assigned_tickets&html_replacement_selector=<?php echo $container2; ?>"override-selected-record="1">
							<div class="widget-chart widget-chart-hover">
								<div class="icon-wrapper rounded-circle" style="display: flex;">
									<div class="icon-wrapper-bg bg-danger"></div>
									<i class="icon-bookmark-empty"></i>
								</div>
								<div class="widget-numbers"><?php echo nw_pretty_number( $tickets ); ?></div>
								<div class="widget-subheading">Assigned Tickets</div>
							</div>
						</a>
					</div>
					<div class="col-sm-6">
						<a class="custom-single-selected-record-button" action="?action=training&todo=my_trainings&html_replacement_selector=<?php echo $container2; ?>"override-selected-record="1">
							<div class="widget-chart widget-chart-hover br-br">
								<div class="icon-wrapper rounded-circle" style="display: flex;">
									<div class="icon-wrapper-bg bg-success"></div>
									<i class="icon-truck"></i></div>
								<div class="widget-numbers"><?php echo nw_pretty_number( $training_participants ); ?></div>
								<div class="widget-subheading">Trainings</div>
							</div>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<?php
			if( isset( $data["stats"]["unprocessed"] ) && is_array( $data["stats"]["unprocessed"] ) && ! empty( $data["stats"]["unprocessed"] ) ){
				//echo '<pre>'; print_r( $data["stats"] ); echo '</pre>';
				?>
				<h4 class="card-title"><strong>Metrics</strong></h4>
				<ul class="list-group">
					<?php 
						$colors = array( "danger", "primary", "success", "warning" );
						$int = 0;
						
						foreach( $data["stats"]["unprocessed"] as $tval ){
							$color_text = isset( $colors[ $int ] )?$colors[ $int ]:'danger';
							
							
							$sub_text = '';
							
							if( isset( $tval["data"][0]["in_states"] ) ){
								$sub_text = 'In ' . number_format( intval( $tval["data"][0]["in_states"] ), 0 ) . ' state(s)';
							}else if( isset( $tval["data"][0]["in_lgas"] ) ){
								$sub_text = 'In ' . number_format( intval( $tval["data"][0]["in_lgas"] ), 0 ) . ' local government(s)';
							}
							?>
							<li class="list-group-item">
								<div class="widget-content p-0">
									<div class="widget-content-outer">
										<div class="widget-content-wrapper">
											<div class="widget-content-left">
												<div class="widget-heading"><?php echo $tval["title"]; ?></div>
												<div class="widget-subheading"><?php echo $sub_text; ?></div>
											</div>
											<div class="widget-content-right">
												<div class="widget-numbers text-<?php echo $color_text; ?>"><?php if( isset( $tval["data"][0]["count"] ) )echo nw_pretty_number( $tval["data"][0]["count"] ); ?></div>
											</div>
										</div>
									</div>
								</div>
							</li>
							<?php
							++$int;
						}
					?>
					<!--<li class="list-group-item"><a href="#" class="btn btn-sm green btn-block" disabled action="">Open Dashboard <i class="icon-external-link"></i></a></li>-->
				</ul>
				<?php
			}
		?>

	</div>
	
	
	<div class="col-md-4"><!-- 
		<h4 class="card-title"><strong>Recent Comments</strong></h4>
		<div id="recent-comments"></div>
		
		<br />
		<br /> -->
		
		<!-- <h4 class="card-title"><strong>Recent Reports</strong></h4> -->
		<div id="recent-reports"></div>
		<!--
		<ul class="list-group">
			<li class="justify-content-between list-group-item">Cras justo odio <span class="badge badge-secondary badge-pill">14</span></li>
			<li class="justify-content-between list-group-item">Dapibus ac facilisis in <span class="badge badge-secondary badge-pill">2</span></li>
			<li class="justify-content-between list-group-item">Morbi leo risus <span class="badge badge-secondary badge-pill">1</span></li>
		</ul>
		-->
	</div>
	
	
</div>

<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		// echo '<pre>';print_r( $data );echo '</pre>'; 
		$table = isset($data['table']) && $data['table'] ? $data['table'] : '';
		$plugin = isset($data['plugin']) && $data['plugin'] ? $data['plugin'] : '';
	?>
	<style>
		#display-area{ height: 95vh; }
		.op-sidemenu .app-sidebar{ width: 300px; }
		.op-sidemenu li a{ padding: 0 0 40px; color: #c3beb6; }
		.op-sidemenu li a:hover{ color: #000; }
		.main-content-area .app-main__outer{ padding-left: 300px; }
		.op-sidemenu .scrollbar-sidebar{ background: #032558 !important;  }
		#main-body{ padding: 0 30px; }
		.app-sidebar__heading-2{ text-transform: uppercase; font-size: 0.9rem; margin: 0.2rem 0; font-weight: 560; white-space: nowrap; cursor: pointer; position: relative;}
	</style>
	<div class="row">
		<div class="col-md-3">
			<div class="sidebar-area">
				<?php
					$gparams = '&action='.($plugin ? $plugin : $table) . '&todo='.( $plugin ? 'execute' : 'format_react_data' ) . ( $plugin ? '&nwp_action='.$table .'&nwp_todo=format_react_data' : '').'&html_replacement_selector=display-area';
					$options = get_dashboard_sidemenu();
					if( $options ){
						foreach ($options as &$opt) {
							$h = '';
							// $opt['action'] = '?_a='.(isset($opt['action']) && $opt['action']) ? $opt['action'] : '';
							if (isset($opt['sub_menu']) && $opt['sub_menu']) {
								$h = '<li>';
									$h .= '<ul class="sub-menu hidden">';
										foreach ($opt['sub_menu'] as &$_opt) {
											// $_opt['action'] = '&_a='.(isset($_opt['action']) && $_opt['action']) ? $_opt['action'] : '';
											$h .= '<a href="javascript:void(0);" class="custom-single-selected-record-button '. (isset($_opt['class']) && $_opt['class'] ? $_opt['class'] : '').'"  override-selected-record="-" action="?_a='. (isset($_opt['action']) && $_opt['action'] ? $_opt['action'] . $gparams : '' ). '" ' . ( isset($_opt['attributes']) && $_opt['attributes'] ? $_opt['attributes'] : '').' >'. (isset($_opt['icon']) && $_opt['icon'] ? '<i class="'. $_opt['icon'] .'"></i>&nbsp;' : ''). (isset($_opt['title']) && $_opt['title'] ? $_opt['title'] : '-').'</a>';
										}
									$h .= '</ul>';
								$h .= '</li>';
							}

							if( $h ){ ?>
								<li class="app-sidebar__heading-2">
									<a>
										<?php 
											echo ( isset($opt['icon'] ) && $opt['icon'] ? '<i class="'. $opt['icon'] .'"></i>&nbsp;' : '' );
											echo ( isset($opt['title'] ) && $opt['title'] ? $opt['title'] : '-' );
										?>
									</a>
								</li>
								<?php echo $h; ?>
							<?php 
							}else{ ?>
								<li class="app-sidebar__heading-2">
									<a href="#" class="custom-single-selected-record-button <?php echo ( isset($opt['class'] ) && $opt['class'] ? $opt['class'] : '' ); ?>"  override-selected-record="-" action="?_a=<?php echo (isset($opt['action']) && $opt['action']) ? $opt['action'] . $gparams : '' ?>" <?php echo ( isset($opt['attributes'] ) && $opt['attributes'] ? $opt['attributes'] : '' ); ?> ><?php echo (isset($opt['icon']) && $opt['icon'] ? '<i class="'. $opt['icon'] .'"></i>&nbsp;' : ''); ?><?php echo ( isset($opt['title'] ) && $opt['title'] ? $opt['title'] : '-' ); ?></a>
								</li>
							<?php }
						}
					}
				?>
			</div>
		</div>
		<div class="col-md-9">
			<div id="display-area" style="overflow: scroll;"></div>
		</div>
	</div>
</div>
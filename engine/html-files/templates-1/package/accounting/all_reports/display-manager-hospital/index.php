<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}else{
		$access["accessible_functions"]["frontend_tabs.home-tab"] = 1;
	}
	
	$avatar = '';
	$logo = '';
	if( defined("IPIN_APP_LOGO") && IPIN_APP_LOGO ){
		$logo = IPIN_APP_LOGO;
	}
	
	if( defined("IPIN_APP_DEFAULT_AVATAR") && IPIN_APP_DEFAULT_AVATAR ){
		$avatar = IPIN_APP_DEFAULT_AVATAR;
	}
	//print_r( $user_info ); exit;
	
	//echo '<pre>'; print_r( $user_info ); echo '</pre>';
	//echo '<pre>'; print_r( $access ); echo '</pre>';
	
	$tabs = frontend_tabs();
	// echo '<pre>'; print_r( $tabs ); echo '</pre>';
	
	$no_of_visible_tabs = 0;
	if( function_exists("get_hospital_number_of_visible_tabs_settings") ){
		$no_of_visible_tabs = get_hospital_number_of_visible_tabs_settings();
	}
	
	if( ! $no_of_visible_tabs ){
		$no_of_visible_tabs = 7;
	}
	/* 
	if( ! $super ){
		$key = 'a5ae06fe11454a32ae405212de7992a6';
		if( ! isset( $access[ $key ] ) ){
			$app_ref = get_current_customer( 'parish' );
			if( $app_ref ){
				$tabs["parish-tab"]["title"] = get_name_of_referenced_record( array( "id" => $app_ref, "table" => "parish" ) );
			}
		}
	}
	 */
	$visible_tabs_count = 0;
	
	$accessible_tabs = array();
	if( $super ){
		$accessible_tabs = $tabs;
	}else{
		foreach( $tabs as $tk => $tval ){
			
			$yes_access_role = 0;
			if( isset( $access["accessible_functions"][ "frontend_tabs.".$tk ] ) ){
			//if( ( isset( $tval["access_role"] ) && isset( $access[ $tval["access_role"] ] ) ) ){
				$yes_access_role = 1;
			}else if( isset( $tval[ 'no_access' ] ) && $tval[ 'no_access' ] ){
				$yes_access_role = 1;
			}
			
			if( ! $yes_access_role )continue;
			
		}
	}
	
	if( empty( $accessible_tabs ) ){
		?>
		<div class="note note-danger"><h4>Access Denied</h4><p>Please contact your administrator</p></div>
		<?php
	}else{
		$pic = isset( $user_info["photograph"] )?$user_info["photograph"]:'';
		$pr = get_project_data();
		
		if( $pic && file_exists( $pagepointer . $pic ) ){
			$pic = $pr["domain_name"] . $pic;
		}else{
			$app_url = isset( $pr["app_url"] )?$pr["app_url"]:'';
			$pic = $app_url . ( $avatar ?$avatar:'files/resource_library/images/avatars/1.jpg' );
		}
		
		$color_h = '';
		if( isset( $_SESSION["test_server"] ) && $_SESSION["test_server"] ){
			$color_h = 'background:#ffa6a6 !important;';
			?>
			<div id="test-mode-container" style="position: fixed;    top: 15px;    left: 50%;    transform: translate(-50%, -20%);    -ms-transform: translate(-50%, -20%);    z-index: 99999999;    background: #f3eded;    padding: 4px;    color: red;    border-radius: 8px !important;    box-shadow: 1px 1px 2px 1px #c53b3b;"><strong>IMPORTANT: TEST MODE ACTIVE</strong></div>
			<?php
		}
?>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
		
        <div class="app-header header-shadow" >
            <div class="app-header__logo">
                <div class="logo-src" style="<?php echo $logo; ?>"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar" onclick="">
                            <span class="hamburger-box nwp-not-opacity">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box nwp-not-opacity">
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
            </div>    <div class="app-header__content">
                <div class="app-header-left" >
                    <!--
					<div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Type to search">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
					-->
					<ul id="main-tabs" class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
						<?php 
							$more_tabs = '';
							$active = 'active';
	
							$a = array();
							$a["ctype"] = 'active_tab';
							$a["type"] = 'get';
							$a2 = nwp_reload_callback( $a );
							if( isset( $a2["tab_key"] ) && isset( $accessible_tabs[ $a2["tab_key"] ] ) ){
								$accessible_tabs[ $a2["tab_key"] ]['active'] = 1;
								$active = '';
							}
							
							foreach( $accessible_tabs as $tk => $tval ){
								
								$attrx = '#'. $tk;
								$attrx2 = 'custom-single-selected-record-button empty-tab ';
								$attrx3 = 'nav-link ';
								
								$attrx4 = 'data-toggle="tab" role="tab"';
								
								if( isset( $tval["active"] ) && $tval["active"] ){
									$active = 'active';
								}
								
								if( isset( $tval["link"] ) && $tval["link"] ){
									$attrx = $tval["link"];
									$attrx2 = '';
									$attrx4 = '';
								}
								
								$tval["action"] .= '&menu_title=' . rawurlencode( $tval["title"] );
								$tval["action"] .= '&is_menu=1&tab_key=' . $tk;
								
								if( $visible_tabs_count > $no_of_visible_tabs ){
									$more_tabs .= '<li class="nav-item "><a href="'. $attrx .'"  class="'.$attrx2.' more-tab" id="'. $tk .'" override-selected-record="1" action="'. $tval["action"] .'" data-toggle="tab"><span id="'. $tk .'-span">'. $tval["title"] .'</span></a></li>';
								}else{
								?>
								<li class="nav-item "><a href="<?php echo $attrx; ?>" class="<?php echo $attrx3 . $attrx2 . $active; ?>" id="<?php echo $tk; ?>"  override-selected-record="1" action="<?php echo $tval["action"]; ?>" <?php echo $attrx4; ?>><span id="<?php echo $tk; ?>-span"><?php echo $tval["title"]; ?></span></a></li>
								<?php
								}
								
								$active = '';
								
								++$visible_tabs_count;
							}
							
							if( $more_tabs ){
							?>
							<li class="dropdown nav-item" id="more-tab-handle">
							   <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><span>More <!--<i class="icon-angle-down"></i>--></span></a>
							   <ul class="dropdown-menu" role="menu">
								  <?php echo $more_tabs; ?>
							   </ul>
							</li>
							<?php
							}
						   ?>
						   
					</ul>
				</div>
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="<?php echo $pic; ?>" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <a href="#" tabindex="0" class="custom-single-selected-record-button dropdown-item" action="?action=users&todo=display_my_profile_manager" title="My Profile Manager" override-selected-record="-" >My Profile</a>
                                            
											<div tabindex="-1" class="dropdown-divider"></div>
											
											<a href="switch-user.html" class="dropdown-item">
												<small><i class="icon-key"></i> Switch User</small>
											  </a>
											  <div tabindex="-1" class="dropdown-divider"></div>
											  <a href="../engine/sign_out?action=signout" class="dropdown-item">
												<small><i class="icon-power-off"></i> Sign Out</small>
											  </a>
											 
											<div tabindex="-1" class="dropdown-divider"></div>
                                            <a href="#"class="dropdown-item" tabindex="0" onclick="alert( '<?php echo $pr["project_title"] . '\nVersion: '. get_app_version( $pagepointer ); ?>' );">About</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                       <?php if( isset( $user_info["user_initials"] ) )echo $user_info["user_initials"]; ?>
                                    </div>
                                    <div class="widget-subheading">
                                       <?php if( isset( $user_info["user_email"] ) )echo substr( $user_info["user_email"], 0, 20 ); ?>
                                    </div>
                                </div>
								<!--
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow p-1 btn btn-success btn-sm show-toastr-example">
                                        <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                                    </button>
                                </div>
								-->
                            </div>
                        </div>
                    </div>        
				</div>
				
            </div>
        </div>   
		<div class="app-main" id="dash-board-main-content-area">
			<div style="text-align:center;">
				<br />
				<br />
				<br />
				<img src="hospital-assets/img/ajax-loading.gif" />
				<br />
				<br />
				<br />
			</div>
			
		</div>
    </div>
<script type="text/javascript">
	
	$('ul#main-tabs')
	.find("a.nav-link")
	.add("a.empty-tab")
	.on("click", function( e ){
		
		$('ul#main-tabs')
		.find("a.nav-link")
		.add("a.empty-tab")
		.removeClass("active");
		
		$(this)
		.addClass("active");
		
	});
	
	$('button.mobile-toggle-header-nav')
	.on("click", function( e ){
		if( $('ul#main-tabs').is(":visible") ){
			$('.app-header__content')
			.add('.app-header-left')
			.css("visibility", "hidden")
			.css("opacity", 0);
			
			$('.app-header__content')
			.css("top", "0");
			//.css("position", "relative")
			
			$('ul#main-tabs')
			.hide();
		}else{
			$('.app-header__content')
			.add('.app-header-left')
			.css("visibility", "visible")
			.css("opacity", 1);
			
			//.css("position", "relative")
			
			$('ul#main-tabs')
			.show();
			
			$('.app-header__content')
			.css("top", "70px")
			.css("height", "auto")
			.css("width", "100%")
			.css("left", "0");
		}
	});
	
	$('button.close-sidebar-btn')
	.add('button.mobile-toggle-nav')
	.add('button.mobile-toggle-header-na')
	.on("click", function( e ){
		
		if( $('.app-sidebar').css('transform') == "none" ){
			$('.fixed-sidebar .app-main .app-main__outer').css('padding-left', "0px");
			$('.app-sidebar').css('transform', "translateX(-280px)");
		}else{
			$('.fixed-sidebar .app-main .app-main__outer').css('padding-left', "280px");
			$('.app-sidebar').css('transform', "none");
		}
		
		$nwProcessor.reload_datatable();
	});
	
	$('body')
	.on("click", 'li.app-sidebar__heading', function( e ){
		
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
</script>
<?php } ?>
</div>
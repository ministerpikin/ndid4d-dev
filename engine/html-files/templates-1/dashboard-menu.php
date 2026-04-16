<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="portlet box tabbable backend-menu" style="background:#fff; margin-bottom:0; border-bottom:1px solid #ddd;">
	<div class="portlet-title">
		<div class="caption">
			<a style="margin-right:5px;" class="pull-left btn-sm btn blue" href="">RELOAD</a>
			<div class="pull-left">
				<ul class="nav nav-tabs">
					
				   <?php
						$package_tabs_before = array();
						$package_tabs_after = array();
						$dev_mode = get_hyella_development_mode();
						
						$pko = get_package_option( array( "package" => 1 ) );
						
						$active = 'active';
						if( file_exists( dirname( __FILE__ ) . "/package/".$pko."/dashboard-page-tab.php" ) ){
							include "package/".$pko."/dashboard-page-tab.php";
						}
						
						$hide_menu["requisition"] = 1;
						if( function_exists("get_show_requisition_settings") && get_show_requisition_settings() ){
							$hide_menu["requisition"] = 0;
						}
						
						$hide_menu["work_order"] = 1;
						if( function_exists("get_show_work_order_settings") && get_show_work_order_settings() ){
							$hide_menu["work_order"] = 0;
						}
						
						$hide_menu["fixed_asset"] = 1;
						if( function_exists("get_show_fixed_asset_settings") && get_show_fixed_asset_settings() ){
							$hide_menu["fixed_asset"] = 0;
						}
						
						$hide_menu["human_resource"] = 1;
						if( function_exists("get_show_human_resource_settings") && get_show_human_resource_settings() ){
							$hide_menu["human_resource"] = 0;
						}
						
						$tabs = array(
							"portlet_tab_inventory" => array(
								"title" => "INVENTORY",
								//"action" => "?action=appointment2&todo=display_manage_appointment2",
								"hide_menu" => isset( $hide_menu["inventory"] )?$hide_menu["inventory"]:0,
								"access_role" => "11658504055",
							),
							"portlet_tab_customers" => array(
								"title" => "SALES",
								"hide_menu" => isset( $hide_menu["sales"] )?$hide_menu["sales"]:0,
								"access_role" => "10743691457",
							),
							"portlet_tab_requisition" => array(
								"title" => "REQUISITION",
								"hide_menu" => isset( $hide_menu["requisition"] )?$hide_menu["requisition"]:0,
								"access_role" => "fs17388018741",
							),
							"portlet_tab_purchase" => array(
								"title" => "PURCHASE",
								"hide_menu" => isset( $hide_menu["purchase"] )?$hide_menu["purchase"]:0,
								"access_role" => "14426317223",
							),
							"portlet_tab_work_order" => array(
								"title" => "WORK ORDER",
								"hide_menu" => isset( $hide_menu["work_order"] )?$hide_menu["work_order"]:0,
								"access_role" => "14426317223",
							),
							"portlet_tab2" => array(
								"title" => "REPORTS",
								"access_role" => "10859351455",
							),
							"portlet_tab_accounting" => array(
								"title" => "FINANCE",
								"access_role" => "11209887664",
							),
							"portlet_tab_fixed_assets" => array(
								"title" => "FIXED ASSETS",
								"hide_menu" => isset( $hide_menu["fixed_asset"] )?$hide_menu["fixed_asset"]:0,
								"access_role" => "11209887664",
							),
							"portlet_tab_hr" => array(
								"title" => "HR",
								"hide_menu" => isset( $hide_menu["human_resource"] )?$hide_menu["human_resource"]:0,
								"access_role" => "fs17389536828",
							),
							"portlet_tab4" => array(
								"title" => "SYSTEM SETTINGS",
								"access_role" => "10859352637",
							),
							"portlet_tab5" => array(
								"title" => "TRASH BIN",
								"hide_menu" => 1,
								//"access_role" => "",
							),
						);
						
						$tabs = array_merge( $package_tabs_before, $tabs, $package_tabs_after );
						
						$more_tabs = '';
						$no_of_visible_tabs = 9;
						$visible_tabs_count = 0;
						
						foreach( $tabs as $tk => $tval ){
							
							if( isset( $tval["hide_menu"] ) && $tval["hide_menu"] ){
								continue;
							}
							
							if( ! $super ){
								$yes_access_role = 0;
								if( isset( $tval["access_role"] ) && isset( $access[ $tval["access_role"] ] ) ){
									$yes_access_role = 1;
								}
								
								if( ! $yes_access_role )continue;
							}
							
							$actions = '';
							$more_tab_class = '';
							
							if( $visible_tabs_count > $no_of_visible_tabs ){
								$more_tab_class = 'more-tab';
							}
							
							if( isset( $tval["action"] ) && $tval["action"] ){
								$actions = ' class="custom-single-selected-record-button empty-tab '.$more_tab_class.'" override-selected-record="1" action="'. $tval["action"] .'" display-title="'. $tval["title"] .'" ';
							}else{
								$actions = ' class="'.$more_tab_class.'" ';
							}
							
							if( $visible_tabs_count > $no_of_visible_tabs ){
								$more_tabs .= '<li ><a href="#'. $tk .'" id="handle-'. $tk .'" '.$actions.' data-toggle="tab">'. $tval["title"] .'</a></li>';
							}else{
							?>
							<li class="<?php echo $active; ?>"><a href="#<?php echo $tk; ?>" <?php echo $actions; ?> id="handle-<?php echo $tk; ?>" data-toggle="tab"><?php echo $tval["title"]; ?></a></li>
							<?php
							}
							
							$active = '';
							
							++$visible_tabs_count;
						}
						
						if( $more_tabs ){
						?>
						<li class="dropdown" id="more-tab-handle">
						   <a href="#" class="dropdown-toggle btn default" data-toggle="dropdown">More <i class="icon-angle-down"></i></a>
						   <ul class="dropdown-menu" role="menu">
							  <?php echo $more_tabs; ?>
						   </ul>
						</li>
						<?php
						}
						?>
				   
					 <?php if( $super && get_hyella_development_mode() ){ ?>
					<li class=""><a href="#portlet_tab_development" class="custom-action-button1" function-id="8270082579" function-class="dashboard" function-name="display_main_menu_full_view" module-id="1412705497" module-name="Dashboard" data-toggle="tab">SYS DEV</a></li>
					<?php } ?>
				   
				</ul>
			</div>
		</div>
		
		<!-- BEGIN USER DROPDOWN -->
		<div class="pull-right btn-group">
			<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"><i class="icon-user"></i> <span id="user-info-user-name" class="username"><?php if( isset( $user_info["user_initials"] ) && $user_info["user_initials"] ){ echo $user_info["user_initials"]; } ?></span> <i class="icon-angle-down"></i></button>
			<ul class="dropdown-menu">
				<li>
					<a href="#" class="custom-action-button" id="8270082573" function-id="8270082579" function-class="users" function-name="display_my_profile_manager" module-id="1412705497" module-name="Users Manager" title="My Profile Manager" budget-id="-" month-id="-">
						<i class="icon-user"></i> My Profile
					</a>
				  </li>
				  <li class="divider"></li>
				  <li><a href="../sign-in/switch-user.html"><i class="icon-cogs"></i> Switch User</a>
				  <li class="divider"></li>
				  <li><a href="sign_out?action=signout"><i class="icon-key"></i> Sign Out</a>
				 </li>
			</ul>
		</div>
		<!-- END USER DROPDOWN -->
		
	</div>
	<!-- BEGIN RIBBON -->
	<div class="portlet-body">
		<div class="tab-content">
			<?php
				$key = "10859351455";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="tab-pane" id="portlet_tab2">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					<?php
						if( HYELLA_PACKAGE == "farm" ){
						$key = "15340597771";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="farm_daily_record" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Egg Production Report">
							<i class="icon-book" ></i>
							<div>Egg Reports</div>
						</a>
					</div>
					<?php }  ?>
					<?php }  ?>
					
					<?php
						if( HYELLA_PACKAGE == "hotel" ){
						$key = "15340601513";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="hotel_checkin" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Hotel Room Reports">
							<i class="icon-book" ></i>
							<div>Hotel Room Reports</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						//$key = "15340603366";
						//if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="hotel_checkin" function-name="display_night_reports_full_view" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Nightly Audits of All Transactions">
							<i class="icon-book" ></i>
							<div>Night Audit</div>
						</a>
					</div>
					<?php //}  ?>
					<?php } ?>
					
					<?php
						if( HYELLA_PACKAGE == "hospital" && get_enable_medical_reports_settings() ){
						$key = "show_medical_report";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="all_reports" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View / Record of hospital">
							<i class="icon-book" style=""></i>
							<div>Medical Reports</div>
						</a>
					</div>
					<?php } ?>
					<?php } ?>
					
					<?php
						$key = "10858963073";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Sales Reports">
							<i class="icon-book" style=""></i>
							<div>Sales Reports</div>
						</a>
					</div>
					<?php }  ?>
					
					<?php
						$key = "10858965439";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Stock Level Reports">
							<i class="icon-book" style=""></i>
							<div>Stock Levels</div>
						</a>
					</div>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="items" function-name="display_bin_card3" module-id="1412705497" module-name="Reports" title="Click Here to View Stock Ledger">
							<i class="icon-book" style=""></i>
							<div>Stock Ledger</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "purchase_reports";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Purchase Reports">
							<i class="icon-book" style=""></i>
							<div>Purchase Reports</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "production_reports";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="production" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports Manager" title="Click Here to View Production Reports">
							<i class="icon-book" ></i>
							<div>Production Reports</div>
						</a>
					</div>
					<?php }  ?>
					
					<?php
						$key = "13015177734";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_cash_book_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Cash Book Reports">
							<i class="icon-book" ></i>
							<div>Cash Book Reports</div>
						</a>
					</div>
					<?php } ?>
					<?php
						$key = "13015179718";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_reports_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Reports">
							<i class="icon-book" ></i>
							<div>Financial Reports</div>
						</a>
					</div>
					
					<?php } ?>
					
					<?php
						if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE ){
							switch( HYELLA_PACKAGE ){
							case "catholic":
								?>
								<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
									<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_income_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Reports">
										<i class="icon-book" ></i>
										<div>Other Income Analysis</div>
									</a>
								</div>
								<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
									<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_priests_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Reports">
										<i class="icon-book" ></i>
										<div>Payment Analysis (Priests)</div>
									</a>
								</div>
								<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
									<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_parish_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Reports">
										<i class="icon-book" ></i>
										<div>Payment Analysis (Parish)</div>
									</a>
								</div>
								<?php
							break;
							}
						}
					?>
					
					<?php
						$key = "13015181930";
						$key1 = "13015183862";
						if( isset( $access[ $key ] ) || isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_receivables_and_payables_full_view" module-id="1412705497" module-name="Reports" title="Click Here to View Reports of Receivables & Payables">
							<i class="icon-book" ></i>
							<div>Receivables & Payables</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						/*
						$key = "13015183862";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_payables_full_view" module-id="1412705497" module-name="Accounts Payable" title="Click Here to View Reports">
							<i class="icon-book" ></i>
							<div>Accounts Payable</div>
						</a>
					</div>
					<?php } */ ?>
					
					<?php
						$key = "10858969535";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Reports" title="View Reports Generated by Me" function-id="1" function-class="reports" function-name="display_all_records_full_view">
							<i class="icon-book" style=""></i>
							<div>Archived Reports</div>
						</a>
					</div>
					<?php } ?>
					
				</div>
				<!-- END HORIZANTAL MENU -->
			</div>
			<?php } ?>
			
			<?php 
				if( file_exists( dirname( __FILE__ ) . "/package/".HYELLA_PACKAGE."/dashboard-page.php" ) )include "package/".HYELLA_PACKAGE."/dashboard-page.php";
			?>
			
			<?php
				$key = "11658504055";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="tab-pane" id="portlet_tab_inventory">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					<?php
						/*
						$key = "11658454349";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_app_inventory2" module-id="1412705497" module-name="Inventory" title="Click Here to View Materials Used">
							<i class="icon-book" ></i>
							<div>Items Mgt.</div>
						</a>
					</div>
					<?php } */  ?>
					
					<?php
						$key = "10859003347";
						$key1 = "10858982656";
						if( isset( $access[ $key1 ] ) || isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-3 col-md-2" style="text-align:center;">
						
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10859003347";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" id="8270082579" function-id="8270082579" function-class="items" function-name="display_all_records_full_view" module-id="1412705497" module-name="Inventory" title="Click Here to View List of Items in Inventory"><i class="icon-list"></i> All Items</a>
							<?php } ?>
							
						</div>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10858982656";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Inventory" title="Click Here to View All Categories" function-id="1" function-class="category" function-name="display_all_records_full_view"><i class="icon-list"></i> Items Category</a>
							<?php } ?>
							
						</div>
					</div>
					
					<?php } ?>
					
					<?php
						/*
						$key = "14501696074";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="items_items" function-name="display_all_records_full_view" module-id="1412705497" module-name="Inventory" title="Click Here to Manage Composite Items">
							<i class="icon-book"></i>
							<div>Composite Items</div>
						</a>
					</div>
					<?php } */ ?>
					
					<?php
						$key = "10858999561";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_inventory_picking_slips" module-id="1412705497" module-name="Inventory" title="Click Here to Issue out stock for internal use">
							<i class="icon-book"></i>
							<div><?php echo get_stock_utilization_label_settings(); ?></div>
						</a>
					</div>
					<?php } ?>
					
					<?php if( ! get_single_store_settings() ){ ?>
					
						<?php
							$key = "14501710670";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_app_transfer_inventory" module-id="1412705497" module-name="Inventory" title="Click Here to Transfer Stock">
								<i class="icon-book"></i>
								<div>Stock Transfer</div>
							</a>
						</div>
						<?php } ?>
						
						<?php if( get_transfer_consumables_settings() ){ ?>
						<?php
							$key = "consumables_transfer";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_app_transfer_consumables" module-id="1412705497" module-name="Inventory" title="Click Here to Transfer Consumables">
								<i class="icon-book"></i>
								<div>Consumables Transfer</div>
							</a>
						</div>
						<?php } ?>
						<?php } ?>
					<?php } ?>
					
					<?php
						$key = "11658468915";
						$key1 = "11658471437";
						if( isset( $access[ $key1 ] ) || isset( $access[ $key ] ) || $super ){
							
							if( class_exists("cInventory_ent") ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="de22405858311" function-id="de22405858311" function-class="inventory_ent" function-name="display_all_records_full_view" module-id="1412705497" module-name="Main Menu" title="Click Here to Manage Inventory">
							
							<i class="icon-book"></i>
							<div>Inventory Movement</div>
							
						</a>
						
					</div>
							<?php }else{ ?>
				
					<div class="col-sm-3 col-md-2" style="text-align:center;">
						<div class="btn-group btn-group-justified">
							<?php
								$key = "11658468915";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" id="8270082579" function-id="8270082579" function-class="inventory" function-name="display_custom_records_full_view" module-id="1412705497" module-name="Inventory" title="Click Here to Manage Inventory"><i class="icon-list"></i> Items Supply History</a>
							<?php } ?>
							
						</div>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "11658471437";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" id="8270082579" function-id="8270082579" function-class="production_items" function-name="display_custom_records_full_view" module-id="1412705497" module-name="Inventory" title="Click Here to View Materials Used"><i class="icon-list"></i> Items Usage History</a>
							<?php } ?>
							
						</div>
					</div>
					<?php } ?>
					
					<?php } ?>
					
					<?php 
						$key = "convert_stock";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-md-1 border-left border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_convert_stock_manager" module-id="1412705497" module-name="Inventory" title="Click Here to Convert Stock">
							<i class="icon-list" ></i>
							<div>Convert Stock</div>
						</a>
					</div>
					<?php } ?>
					
					<?php 
						$key = "stock_count";
						if( class_exists("cStock_count") ){
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-md-1 border-left border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_count" function-name="display_all_records_full_view" module-id="1412705497" module-name="Inventory" title="Click Here to take Physical Stock Count">
							<i class="icon-list" ></i>
							<div>Stock Count</div>
						</a>
					</div>
					<?php }
						}
					?>
					
					<?php
						$key = "delete_production_data";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_stock_deductions" module-id="1412705497" module-name="Inventory" title="Click Here to Generate Stock Stock Deductions">
							<i class="icon-download"></i>
							<div>Stock Deductions</div>
						</a>
					</div>
					<?php } ?>
					
					<?php 
						$key = "import_items";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="import_items" function-name="generate_excel_import_form" module-id="1412705497" module-name="Inventory" title="Click Here to Import Items from Excel">
							<i class="icon-share" ></i>
							<div>Import Items</div>
						</a>
					</div>
					<?php } ?>
					
					
					<?php
						$key = "14501759527";
						$key1 = "14501761333";
						if( isset( $access[ $key1 ] ) || isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-2" style="text-align:center;">
						<?php
							$key = "14501759527";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Purchase" title="Click Here to Produce Items" function-id="1" function-class="cart" function-name="display_production_cart"><i class="icon-list"></i> New Production Order</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "14501761333";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Purchase" month-id="-" budget-id="" function-id="1" function-class="production" function-name="display_all_records_full_view_production" title="Click Here to Production History"><i class="icon-list"></i> View Production History</a>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					
					
				  </div>
				<!-- END HORIZANTAL MENU -->
			</div>
			<?php } ?>
			
			<?php if( function_exists("get_show_requisition_settings") && get_show_requisition_settings() ){ ?>
				<?php
					$key = "fs17388018741";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="tab-pane" id="portlet_tab_requisition">
					  <!-- BEGIN HORIZANTAL MENU -->
					  <div class="row" style="margin:10px 0;">
						
						<?php
							$key = "fs17406148948";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_form_internal" module-id="1412705497" module-name="Requisition" title="Click Here to Manage Material Requests">
								<i class="icon-book"></i>
								<div>New Internal Requisition</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_form_purchase" module-id="1412705497" module-name="Requisition" title="Click Here to Manage Material Requests">
								<i class="icon-book"></i>
								<div>New Purchase Requisition</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_draft" module-id="1412705497" module-name="Requisition" title="Click Here to Manage Material Requests">
								<i class="icon-book"></i>
								<div>Pending Requisitions</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							
							$key = "fs17406154326";
							if( $dev_mode ){
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_process_stock_request" module-id="1412705497" module-name="Requisition" title="Click Here to Process Material Requests">
								<i class="icon-list"></i>
								<div>Process Material Request</div>
							</a>
						</div>
						<?php } ?>
						<?php } ?>
						
						<?php
							$key = "validate_requisition";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_pending" module-id="1412705497" module-name="Requisition" title="Click Here to Validate Requisition">
								<i class="icon-list"></i>
								<div>Validate Requisition (Procurement)</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_audit" module-id="1412705497" module-name="Requisition" title="Click Here to Validate Requisition">
								<i class="icon-list"></i>
								<div>Validate Requisition (Audit)</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_md" module-id="1412705497" module-name="Requisition" title="Click Here to Validate Requisition">
								<i class="icon-list"></i>
								<div>Validate Requisition (MD)</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "approved_requisition";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_approved" module-id="1412705497" module-name="Requisition" title="Click Here to Manage Approved Requisition">
								<i class="icon-list"></i>
								<div>Approved Requisition</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "returned_requisition";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_stock_request_unapproved" module-id="1412705497" module-name="Requisition" title="Click Here to Manage Declined Requisition">
								<i class="icon-list"></i>
								<div>Cancelled Requisition</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "fs17406158661";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_all_records_full_view" module-id="1412705497" module-name="Requisition" title="Click Here to View Requisition History">
								<i class="icon-list"></i>
								<div>Material Request History</div>
							</a>
						</div>
						<?php } ?>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="stock_request" function-name="display_merge_requisition_form" module-id="1412705497" module-name="Requisition" title="Click Here to Merge Requisition">
								<i class="icon-book"></i>
								<div>Merge Requisition</div>
							</a>
						</div>
						
					  </div>
					<!-- END HORIZANTAL MENU -->
				</div>
				<?php } ?>
			<?php } ?>
			
			<?php if( function_exists("get_show_work_order_settings") && get_show_work_order_settings() ){ ?>
				<?php
					$key = "14426317223";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="tab-pane" id="portlet_tab_work_order">
					  <!-- BEGIN HORIZANTAL MENU -->
					  <div class="row" style="margin:10px 0;">
						
						<?php
							$key = "fs17406171116";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_work_order" module-id="1412705497" module-name="Purchase" title="Click Here to Create New Work Order">
								<i class="icon-book"></i>
								<div>New Work Order</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_vendor_bill_draft" module-id="1412705497" module-name="Work Orders" title="Click Here to Manage Pending Work Orders">
								<i class="icon-book"></i>
								<div>Pending Work Orders</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_vendor_bill_pending" module-id="1412705497" module-name="Work Orders" title="Click Here to Approve Work Orders">
								<i class="icon-list"></i>
								<div>Approve Work Orders (Procurement)</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_vendor_bill_audit" module-id="1412705497" module-name="Work Orders" title="Click Here to Approve Work Orders">
								<i class="icon-list"></i>
								<div>Approve Work Orders (Audit)</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_vendor_bill_md" module-id="1412705497" module-name="Work Orders" title="Click Here to Approve Work Orders">
								<i class="icon-list"></i>
								<div>Approve Work Orders (MD)</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_vendor_bill_approved" module-id="1412705497" module-name="Work Orders" title="Click Here to Manage Approved Work Orders">
								<i class="icon-list"></i>
								<div>Approved Work Orders</div>
							</a>
						</div>
						
						<?php if( function_exists( "get_validate_work_order_settings" ) && get_validate_work_order_settings() ){ ?>
						<?php
							$key = "fs17387931317";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_validate_records_full_view" module-id="1412705497" module-name="Sales" title="Click Here to Validate Job Completion Certificate">
								<i class="icon-list" style=""></i>
								<div>Validate Job Completion Cert.</div>
							</a>
						</div>
						<?php } ?>
						<?php } ?>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_vendor_bill_unapproved" module-id="1412705497" module-name="Work Orders" title="Click Here to Manage Cancelled Work Orders">
								<i class="icon-list"></i>
								<div>Cancelled Work Orders</div>
							</a>
						</div>
						
						<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendor_bill" function-name="display_all_records_full_view" module-id="1412705497" module-name="Work Orders" title="Click Here to View Work Orders History">
								<i class="icon-list"></i>
								<div>Work Order History</div>
							</a>
						</div>
						<?php } ?>
						
					  </div>
					<!-- END HORIZANTAL MENU -->
				</div>
				<?php } ?>
			<?php } ?>
			
			<?php
				$key = "14426317223";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="tab-pane" id="portlet_tab_purchase">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					
					<?php
						$key = "10858988569";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="vendors" function-name="display_all_records_full_view" module-id="1412705497" module-name="Purchase" title="Click Here to Manage Vendor(s)">
							<i class="icon-book"></i>
							<div>Manage Vendor(s)</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "12039003247";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_custom_records_full_view" module-id="1412705497" module-name="Purchase" title="Click Here to View Purchase Orders & GRN History">
							<i class="icon-book"></i>
							<div>Purchase Orders & GRN History</div>
						</a>
					</div>
					<?php } ?>
						
					<?php
						if( get_use_imported_goods_settings() ){
							$key = "fs17406176141";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_purchase_order_import" module-id="1412705497" module-name="Purchase" title="Click Here to Make New Purchase Order">
								<i class="icon-book"></i>
								<div>New Purchase Order (Detailed)</div>
							</a>
						</div>
						<?php } ?>
					<?php } ?>
					
					<?php
						if( get_direct_purchase_and_restock_settings() ){
						$key = "14501736842";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_purchase_order4" module-id="1412705497" module-name="Purchase" title="Click Here to Make New Purchase Order">
							<i class="icon-book" ></i>
							<div>Purchase Goods</div>
						</a>
					</div>
					<?php } ?>
					<?php } ?>
						
					<?php
						if( function_exists("get_show_basic_purchase_order_settings") && get_show_basic_purchase_order_settings() ){
							$key = "12039004363";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_purchase_order2" module-id="1412705497" module-name="Purchase" title="Click Here to Make New Purchase Order">
								<i class="icon-book"></i>
								<div>New Purchase Order (Simple)</div>
							</a>
						</div>
						<?php } ?>
					<?php } ?>
					
					<?php
						if( function_exists("get_validate_purchase_invoice_settings") && get_validate_purchase_invoice_settings() ){
						?>
						
						<?php
						$key = "fs17387928352";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_validate_records_full_view" module-id="1412705497" module-name="Purchase" title="Click Here to Make New Purchase Order">
								<i class="icon-book"></i>
								<div>Validate Vendors Invoice</div>
							</a>
						</div>
							
						<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_all_records_full_view_approved" module-id="1412705497" module-name="Purchase" title="Click Here to Make New Purchase Order">
								<i class="icon-book"></i>
								<div>Approved Vendors Invoice</div>
							</a>
						</div>
						<?php } ?>
						
					<?php } ?>
					
					<?php
						$key = "12039005452";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_goods_received_note" module-id="1412705497" module-name="Purchase" title="Click Here to Create New Goods Received Note">
							<i class="icon-book"></i>
							<div>Goods Received Note</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "12039006349";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_general_goods_received_note" module-id="1412705497" module-name="Purchase" title="Click Here to Restock Produced Goods">
							<i class="icon-book"></i>
							<div>Restock Goods</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "14501753252";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left border-right " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_debit_note" module-id="1412705497" module-name="Purchase" title="Click Here to Create New Debit Note">
							<i class="icon-book"></i>
							<div>Debit Note</div>
						</a>
					</div>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_custom_debit_note_full_view" module-id="1412705497" module-name="Sales" title="Click Here to View All Debit Notes">
							<i class="icon-book"></i>
							<div>View All Debit Notes</div>
						</a>
					</div>
					<?php } ?>
					
				  </div>
				<!-- END HORIZANTAL MENU -->
			</div>
			<?php } ?>
			
			<?php
				$key = "10743691457";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="tab-pane" id="portlet_tab_customers">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					<?php
						$key = "10858996623";
						$key1 = "10858983830";
						if( isset( $access[ $key1 ] ) || isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-2" style="text-align:center;">
						<?php
							$key = "10858996623";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Sales" title="Click Here to View All Sales Invoice / Receipts" function-id="1" function-class="sales" function-name="display_custom_records_full_view"><i class="icon-book"></i> All Sales Invoice</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "10858983830";
							if( isset( $access[ $key ] ) || $super ){ 
						?>
						<div class="btn-group btn-group-justified">
							<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Sales" title="Click Here to View All <?php echo get_customer_label_based_on_package(); ?>s" function-id="1" function-class="customers" function-name="display_all_records_full_view"><i class="icon-user"></i> Add New <?php echo get_customer_label_based_on_package(); ?></a>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					
					<?php 
						if( function_exists("get_show_sales_order_settings") && get_show_sales_order_settings() ){
						$key = "fs17406188743";
						if( isset( $access[ $key ] ) || $super ){ 
					?>
					<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="orders" function-name="display_all_records_full_view" module-id="1412705497" module-name="Sales" title="Click Here to View Pending Orders">
							<i class="icon-book"></i>
							<div>All Orders</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "fs17406191366";
						if( isset( $access[ $key ] ) || $super ){ 
					?>
					<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_seperate_sales_order_cart" module-id="1412705497" module-name="Sales" title="Take new Order">
							<i class="icon-book"></i>
							<div>New Order</div>
						</a>
					</div>
					<?php 
						}  
						} 
					?>
					
					<?php
						$key = "14501791034";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_shopping_cart2" module-id="1412705497" module-name="Sales" title="Click Here to Make Direct Sales">
							<i class="icon-book" style=""></i>
							<div>Point of Sale</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "12039068333";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_sales_order_cart2" module-id="1412705497" module-name="Sales" title="Click Here to Create New Sales Invoice">
							<i class="icon-book" style=""></i>
							<div>New Sales Invoice</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "fs17406203816";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_shopping_cart2_expense" module-id="1412705497" module-name="Sales" title="Click Here to Staff Welfare">
							<i class="icon-book" style=""></i>
							<div>Staff Welfare</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "11658465754";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right border-left" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="sales" function-name="display_picking_slips_view" module-id="1412705497" module-name="Sales" title="Click Here to View Picking Slips / Delivery Notes">
							<i class="icon-book"></i>
							<div><?php echo get_picking_slip_label_settings(); ?></div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "fs17406210413";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" id="8270082579" function-id="8270082579" function-class="sales" function-name="get_sales_orders_without_picking_slips" module-id="1412705497" module-name="Sales" title="Click Here to View Undelivered Sales Order">
							<i class="icon-shopping-cart" style=""></i>
							<div>Pending <?php echo get_picking_slip_label_settings(); ?></div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "fs17406215712";
						if( isset( $access[ $key ] ) || $super ){
							if( get_enable_loyalty_points_settings() ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="loyalty_points" function-name="display_all_records_full_view" module-id="1412705497" module-name="Loyalty Points" title="Click Here to View / Manage Loyalty Points">
							<i class="icon-book" ></i>
							<div>Loyalty Points</div>
						</a>
					</div>
					<?php } ?>
					<?php } ?>
					
					<?php
						$key = "10858980143";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="discount" function-name="display_all_records_full_view" module-id="1412705497" module-name="Discount Manager" title="Click Here to View / Modify Discounts for Sales">
							<i class="icon-book" ></i>
							<div>Discount</div>
						</a>
					</div>
					<?php } ?>
					
					
					
					<?php
						$key = "credit_notes";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="cart" function-name="display_credit_note_view2" module-id="1412705497" module-name="Sales" title="Click Here to Raise Credit Note for Return of Sold Goods">
							<i class="icon-book"></i>
							<div>Credit Note</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "fs17406271817";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="expenditure" function-name="display_custom_credit_note_full_view" module-id="1412705497" module-name="Sales" title="Click Here to View All Credit Notes">
							<i class="icon-book"></i>
							<div>View All Credit Notes</div>
						</a>
					</div>
					<?php } ?>
					
				</div>
				<!-- END HORIZANTAL MENU -->
			</div>
			<?php } ?>
			
			<?php
				$key = "11209887664";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="tab-pane" id="portlet_tab_accounting">
				  <!-- BEGIN HORIZANTAL MENU -->
				  <div class="row" style="margin:10px 0;">
					<?php
						$key = "13015167859";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="chart_of_accounts" function-name="display_all_records_full_view" module-id="1412705497" module-name="Chart of Accounts" title="Click Here to View Chart of Accounts">
							<i class="icon-book" ></i>
							<div>Chart of Accounts</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "13015170756";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="display_all_records_full_view" module-id="1412705497" module-name="Transaction" title="Click Here to View Transactions">
							<i class="icon-book" ></i>
							<div>View Transactions</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "fs17406278620";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="debit_and_credit" function-name="display_all_records_full_view" module-id="1412705497" module-name="Financial Accounts" title="Click Here to View Debit & Credit">
							<i class="icon-book" style=""></i>
							<div>Debit & Credit</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "13015173446";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions_draft" function-name="display_all_records_full_view" module-id="1412705497" module-name="Transaction" title="Click Here to View Transactions">
							<i class="icon-book" ></i>
							<div>View Drafts</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "13015175710";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="transactions" function-name="post_new_transaction" module-id="1412705497" module-name="Transaction" title="Click Here to Post Transaction">
							<i class="icon-book" ></i>
							<div>Post Transaction</div>
						</a>
					</div>
					<?php } ?>
					
					
					<?php if( function_exists("get_show_pay_roll_settings") && get_show_pay_roll_settings() ){ ?>
						<?php
							$key = "10858990473";
							$key1 = "10858988569";
							$key2 = "10858983830";
							$key3 = "14501832038";
							if( isset( $access[ $key3 ] ) || isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || isset( $access[ $key2 ] ) || $super ){
						?>
						<div class="col-sm-5 col-md-3 border-right" style="text-align:center;">
						
							<div class="btn-group btn-group-justified">
								<?php
									$key = "10858990473";
									if( isset( $access[ $key ] ) || $super ){
								?>
								<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Financial Accounts" title="Click Here to View All Pay Roll" function-id="1" function-class="pay_row" function-name="display_pay_roll_post_view"><i class="icon-list"></i> Pay Roll</a>
								<?php } ?>
								
								 <?php
									$key = "10858988569";
									if( defined("HYELLA_SHOW_ACCOUNTING_VENDORS") && HYELLA_SHOW_ACCOUNTING_VENDORS && isset( $access[ $key ] ) || $super ){
								?>
								 <a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Financial Accounts" month-id="-" budget-id="" function-id="1" function-class="vendors" function-name="display_all_records_full_view" title="Click Here to view vendors"><i class="icon-cogs"></i> Vendors</a>
								 <?php } ?>
								 
							</div>
							<div class="btn-group btn-group-justified">
								<?php
									$key = "14501832038";
									if( isset( $access[ $key ] ) || $super ){
								?>
								<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="pay_roll_post" function-name="display_all_records_full_view" module-id="30642723443" module-name="Financial Accounts" title="Pay Roll List"><i class="icon-list-alt"></i> Pay Roll Posting</a>
								<?php } ?>
								
								<?php
									$key = "10858983830";
									if( defined("HYELLA_SHOW_ACCOUNTING_CUSTOMERS") && HYELLA_SHOW_ACCOUNTING_CUSTOMERS && isset( $access[ $key ] ) || $super ){
								?>
								<a href="#" class="btn btn-sm btn-default btn-bordered custom-action-button align-left" module-name="Financial Accounts" title="Click Here to View All <?php echo get_customer_label_based_on_package(); ?>s" function-id="1" function-class="customers" function-name="display_all_records_full_view"><i class="icon-book"></i> <?php echo get_customer_label_based_on_package(); ?>s</a>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
					
					<?php if( function_exists("get_show_enterprise_accounting_settings") && get_show_enterprise_accounting_settings() ){ ?>
						<?php
							$key = "14501845328";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Store Manager" month-id="-" budget-id="" function-id="1" function-class="prepaid_expenses" function-name="display_all_records_full_view" title="Click Here to update Prepaid Expenses">
								<i class="icon-list" ></i>
								<div>&nbsp;Prepaid Expenses&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "14501846744";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<!--
						<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Store Manager" month-id="-" budget-id="" function-id="1" function-class="loan_management" function-name="display_all_records_full_view" title="Click Here to Manage Loan Disbursement">
								<i class="icon-list" ></i>
								<div>&nbsp;Loans&nbsp;</div>
							</a>
						</div>
						-->
						<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Store Manager" month-id="-" budget-id="" function-id="1" function-class="budget" function-name="display_all_records_full_view" title="Click Here to Update Budget">
								<i class="icon-list" ></i>
								<div>&nbsp;Budget&nbsp;</div>
							</a>
						</div>
						<?php } ?>
					
						<?php
							$key = "13015175710";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="budget_items" function-name="display_all_records_full_view" module-id="1412705497" module-name="Transaction" title="Click Here to View Budget Items">
								<i class="icon-book" ></i>
								<div>Budget Items</div>
							</a>
						</div>
						<?php } ?>
						
						<?php if( function_exists("get_show_void_transactions_settings") && get_show_void_transactions_settings() ){ ?>
						<?php
							$key = "fs17406286226";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" id="8270082579" function-id="8270082579" function-class="void_transactions" function-name="display_all_records_full_view" module-id="1412705497" module-name="Transactions" title="Click Here to View Void Transactions">
								<i class="icon-list" style=""></i>
								<div>Void Transactions</div>
							</a>
						</div>
						<?php } ?>
						<?php } ?>
						
						<?php
							$key = "14502122328";
							if( class_exists("cBank_reconciliation") ){
								if( isset( $access[ $key ] ) || $super ){
							?>
							<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
								<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="de23303914672" function-id="de23303914672" function-class="bank_reconciliation" function-name="display_all_records_full_view" module-id="1412705497" module-name="Main Menu" title="Click Here to Manage Bank Reconciliation">
									<i class="icon-book"></i>
									<div>Bank Reconciliation</div>
								</a>
							</div>
							<?php } ?>
						<?php } ?>
						
					<?php } ?>
				</div>
				<!-- END HORIZANTAL MENU -->
			</div>
			<?php } ?>
			
			<?php if( function_exists("get_show_fixed_asset_settings") && get_show_fixed_asset_settings() ){ ?>
				<div class="tab-pane" id="portlet_tab_fixed_assets">
					  <!-- BEGIN HORIZANTAL MENU -->
					  <div class="row" style="margin:10px 0;">
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets" function-name="display_asset_manager_view" title="Click Here to Manage Fixed Assets">
						<i class="icon-list" ></i>
						<div>&nbsp;Manage Fixed Assets&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets" function-name="display_all_records_full_view_pending" title="Click Here to Manage Fixed Assets whose parameters have not been captured">
						<i class="icon-list" ></i>
						<div>&nbsp;Pending Fixed Assets&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets" function-name="display_all_records_full_view" title="Click Here to Manage All Fixed Assets">
						<i class="icon-list" ></i>
						<div>&nbsp;All Fixed Assets&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502125452";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets_category" function-name="display_all_records_full_view" title="Click Here to Manage Fixed Assets Categories">
						<i class="icon-list" ></i>
						<div>&nbsp;Fixed Assets Category&nbsp;</div>
					</a>
				</div>
				<?php } ?>
		
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets_log" function-name="display_all_records_full_view" title="Click Here to View Fixed Assets History">
						<i class="icon-list" ></i>
						<div>&nbsp;Fixed Assets History&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets_insurance" function-name="display_all_records_full_view" title="Click Here to Manage Insurance">
						<i class="icon-list" ></i>
						<div>&nbsp;Insurance History&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="assets_maintenance" function-name="display_all_records_full_view" title="Click Here to Manage Maintenance">
						<i class="icon-list" ></i>
						<div>&nbsp;Maintenance History&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="cart" function-name="display_purchase_of_fixed_asset" title="Click Here to Manage Fixed Assets Purchase Order">
						<i class="icon-list" ></i>
						<div>&nbsp;Purchase Order&nbsp;</div>
					</a>
				</div>
				<?php } ?>
				
				<?php
					$key = "14502122328";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
					<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Fixed Assets" month-id="-" budget-id="" function-id="1" function-class="cart" function-name="display_sales_order_of_fixed_asset" title="Click Here to Manage Fixed Assets Sales Order">
						<i class="icon-list" ></i>
						<div>&nbsp;Sales Order&nbsp;</div>
					</a>
				</div>
				<?php } ?>
						
				</div>
				</div>
			<?php } ?>
			
			<?php if( function_exists("get_show_human_resource_settings") && get_show_human_resource_settings() ){ ?>
				<?php
					$key = "fs17389536828";
					if( isset( $access[ $key ] ) || $super ){
				?>
				<div class="tab-pane" id="portlet_tab_hr">
					  <!-- BEGIN HORIZANTAL MENU -->
					  <div class="row" style="margin:10px 0;">
						
						<?php
							$key = "manage_employees";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_manage_employees" module-id="1412705497" module-name="HR" title="Manage Employee Details" budget-id="-" month-id="-">
								<i class="icon-user" style=""></i>
								<div>&nbsp;Manage Employees&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "nominal_roll";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_all_employees_records" module-id="1412705497" module-name="HR" title="Nominal Roll" budget-id="-" month-id="-">
								<i class="icon-list-alt" style=""></i>
								<div>&nbsp;Nominal Roll&nbsp;</div>
							</a>
						</div>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_all_employees_records_inactive" module-id="1412705497" module-name="HR" title="List of Inactive Employees" budget-id="-" month-id="-">
								<i class="icon-list-alt" style=""></i>
								<div>&nbsp;In-active Employees&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						<?php


							$key = "current_work_history";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_current_work_history" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Current Work History" budget-id="-" month-id="-">
								<i class="icon-list" style=""></i>
								<div>&nbsp;Current Work History&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "performance_appraisal";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_performance_appraisal_history" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Performance Appraisal" budget-id="-" month-id="-">
								<i class="icon-list" style=""></i>
								<div>&nbsp;Performance Appraisal&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						
						<?php
							$key = "educational_history";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_educational_history" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Educational History" budget-id="-" month-id="-">
								<i class="icon-list" style=""></i>
								<div>&nbsp;Educational History&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "work_experience";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_work_experience_history" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Work Experience History" budget-id="-" month-id="-">
								<i class="icon-list" style=""></i>
								<div>&nbsp;Work Experience&nbsp;</div>
							</a>
						</div>
						<?php }  ?>
						
						<?php
							$key = "next_of_kin";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_next_of_kin" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Next of Kin" budget-id="-" month-id="-">
								<i class="icon-list" style=""></i>
								<div>&nbsp;Next of Kin&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "dependents";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_dependents" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Dependent" budget-id="-" month-id="-">
								<i class="icon-list" style=""></i>
								<div>&nbsp;Dependent(s)&nbsp;</div>
							</a>
						</div>
						<?php } 


						?>
						
						<?php
							$key = "employees_history";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_employees_history_center" module-id="1412705497" module-name="HR" title="Employees History" budget-id="-" month-id="-">
								<i class="icon-list" ></i>
								<div>Employees History</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "10858990473";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="pay_row" function-name="display_pay_roll_post_view" module-id="1412705497" module-name="HR" title="Pay Roll" budget-id="-" month-id="-">
								<i class="icon-list" ></i>
								<div>Pay Roll</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							if( class_exists("cLeave_requests") ){
								
							$key = "leave_administration";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_leave_admin_center" module-id="1412705497" module-name="HR" title="Leave Administration" budget-id="-" month-id="-">
								<i class="icon-list" ></i>
								<div>Leave Administration</div>
							</a>
						</div>
						<?php } ?>
						
						<?php
							$key = "leave_reports";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="leave_roaster" function-name="display_leave_reports_full_view" module-id="1412705497" module-name="HR" title="Click Here to View Reports">
								<i class="icon-book" ></i>
								<div>Leave Reports</div>
							</a>
						</div>
						<?php 
							}  ?>
						
						<?php
							$key = "leave_types";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="leave_types" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Click Here to View Reports">
								<i class="icon-book" ></i>
								<div>Leave Types</div>
							</a>
						</div>
						<?php 
							}  ?>
						
						<?php
							$key = "users_employee_profile";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_employee_profile" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Click Here to View Reports">
								<i class="icon-book" ></i>
								<div>Employees</div>
							</a>
						</div>
						<?php 
							} 
							} 
						?>
						
						<?php
							if( $dev_mode ){
							$key = "manage_countries";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="country_list" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Manage Countries" budget-id="-" month-id="-">
								<i class="icon-list" ></i>
								<div>&nbsp;Manage Countries&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						<?php } ?>
						
						<?php
							if( $dev_mode ){
							$key = "users_attendance";
							if( isset( $access[ $key ] ) || $super ){
						?>
						<div class="col-md-1 border-right  " style="text-align:center;">
							<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_attendance" function-name="display_all_records_full_view" module-id="1412705497" module-name="HR" title="Employees Attendance" budget-id="-" month-id="-">
								<i class="icon-list" ></i>
								<div>&nbsp;Employees Attendances&nbsp;</div>
							</a>
						</div>
						<?php } ?>
						<?php } ?>
						
					  </div>
					<!-- END HORIZANTAL MENU -->
				</div>
				<?php } ?>
			<?php } ?>
			
			<?php
				$key = "10859352637";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="tab-pane" id="portlet_tab4">
				<div class="row" style="margin:10px 0;">
					 <?php
						$key = "10859007954";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users" function-name="display_all_records_full_view" module-id="1412705497" module-name="Users Manager" title="Users Manager" budget-id="-" month-id="-">
							<i class="icon-user" style=""></i>
							<div>&nbsp;Users Manager&nbsp;</div>
						</a>
					</div>
					<?php } ?>

					<?php
						$key = "10859007954";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="users_employee_profile" function-name="display_all_records_full_view" module-id="1412705497" module-name="Employees Manager" title="Users Manager" budget-id="-" month-id="-">
							<i class="icon-user" style=""></i>
							<div>&nbsp;Employees Manager&nbsp;</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "10859015076";
						$key1 = "10859027774";
						$key2 = "10859016228";
						$key3 = "10859035524";
						if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || isset( $access[ $key2 ] ) || isset( $access[ $key3 ] ) || $super ){
					?>
					<div class="col-sm-5 col-md-3 border-right" >
						<?php
							$key = "10859015076";
							$key1 = "10859027774";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10859015076";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="departments" function-name="display_all_records_full_view" module-id="30642723443" module-name="Settings" title="Designations / Departments"><i class="icon-list-alt"></i> Departments</a>
							<?php } ?>
							
							<?php
								$key = "10859027774";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="access_roles" function-name="display_all_records_full_view" module-id="30642723443" module-name="Access Roles" title="Access Roles"><i class="icon-list-alt"></i> Access Roles</a>
							<?php } ?>
							
						</div>
						<?php } ?>
						
						
						<?php
							$key1 = "10859035524";
							$key = "10859015076";
							if( isset( $access[ $key ] ) || isset( $access[ $key1 ] ) || $super ){
						?>
						<div class="btn-group btn-group-justified">
							<?php
								$key = "10859015076";
								if( isset( $access[ $key ] ) || $super ){
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" id="7979957710" function-id="7979957710" function-class="grade_level" function-name="display_all_records_full_view" module-id="30642723443" module-name="Grade Level" title="Grade Level"><i class="icon-list-alt"></i> Grade Level</a>
							<?php } ?>
							
							<?php
								$key = "10859035524";
								if( isset( $access[ $key ] ) || $super ){
									//display_all_records_full_view
							?>
							<a href="#" class="btn btn-sm btn-default custom-action-button align-left" function-id="8585015678" function-class="audit" function-name="display_audit_trail_analysis_view" module-id="8585007445" module-name="Audit Trail" title="Audit Trail"><i class="icon-list-alt"></i> Audit Trail</a>
							<?php } ?>
						</div>
						<?php } ?>
						
					</div>
					<?php } ?>
					
					<?php
						$key = "10859044552";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="general_settings" function-name="display_all_records_full_view" module-id="1412705497" module-name="Settings" title="General Settings Manager" budget-id="-" month-id="-">
							<i class="icon-cogs" style=""></i>
							<div>&nbsp;General Settings&nbsp;</div>
						</a>
					</div>
					<?php if( class_exists("cProject_settings") ){ ?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="general_settings" function-name="show_project_settings" module-id="1412705497" module-name="Settings" title="Project Settings Manager" budget-id="-" month-id="-">
							<i class="icon-cogs" style=""></i>
							<div>&nbsp;Project Settings&nbsp;</div>
						</a>
					</div>
					<?php } ?>
					<?php } ?>
					
					<?php
						$key = "10859013761";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="audit" function-name="display_admin_panel_view" module-id="1412705497" module-name="Admin Dashboard" title="Click Here to Manage Admin Dashboard">
							<i class="icon-cogs" style=""></i>
							<div>Admin Control Panel</div>
						</a>
					</div>
					<?php } ?>
					
					
					<?php
						$key = "10858981458";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" module-name="Store Manager" month-id="-" budget-id="" function-id="1" function-class="stores" function-name="display_all_records_full_view" title="Click Here to view stores">
							<i class="icon-list" style=""></i>
							<div>&nbsp;Revenue & Cost Centres&nbsp;</div>
						</a>
					</div>
					<?php } ?>
					
					<?php
						$key = "12039065468";
						if( isset( $access[ $key ] ) || $super ){
					?>
					<div class="col-sm-2 col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="banks" function-name="display_all_records_full_view" module-id="1412705497" module-name="General Options" title="Click Here to Manage General Options">
							<i class="icon-edit" style=""></i>
							<div>General Options</div>
						</a>
					</div>
					<?php } ?>
					
					
				</div>
					
			</div>
			<?php } ?>
			
			<?php
				if( $super && $dev_mode ){
			?>
			<div class="tab-pane" id="portlet_tab_development">
				<div class="row" style="margin:10px 0;">
					<div class="col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="database_table" function-name="display_all_records_full_view" module-id="1412705497" module-name="Class Manager" title="Class Manager" budget-id="-" month-id="-">
							<i class="icon-list" style="color:#DFC96F;"></i>
							<div>&nbsp;Tables&nbsp;</div>
						</a>
					</div>
					<div class="col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="database_fields" function-name="display_all_records_full_view" module-id="1412705497" module-name="Class Manager" title="Class Parameters" budget-id="-" month-id="-">
							<i class="icon-list" style="color:#DFC96F;"></i>
							<div>&nbsp;Fields&nbsp;</div>
						</a>
					</div>
					<div class="col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="database_objects" function-name="display_all_records_full_view" module-id="1412705497" module-name="Class Manager" title="Class Parameters" budget-id="-" month-id="-">
							<i class="icon-list" style="color:#DFC96F;"></i>
							<div>&nbsp;Objects&nbsp;</div>
						</a>
					</div>
					<div class="col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="audit" function-name="upgrade_database_from_database" module-id="1412705497" module-name="Class Manager" title="Class Parameters" budget-id="-" month-id="-">
							<i class="icon-list" style="color:#DFC96F;"></i>
							<div>&nbsp;Upgrade Database&nbsp;</div>
						</a>
					</div>
					
					
					<div class="col-md-1 border-left border-right" style="text-align:center;">
						
						<a href="#" class="icon-btn top-bar-icon custom-action-button-old" id="7979957710" function-id="7979957710" function-class="functions" function-name="display_all_records_full_view" module-id="30642723443" module-name="App Settings" title="Functions">
							<i class="icon-list-alt" style="color:#DFC96F;"></i>
							<div>&nbsp;Functions&nbsp;</div>
						</a>
					</div>
					<div class="col-md-1 border-left border-right" style="text-align:center;">
						
						<a href="#" id="8585015678" class="icon-btn top-bar-icon custom-action-button-old" function-id="8585015678" function-class="modules" function-name="display_all_records_full_view" module-id="8585007445" module-name="App Settings" title="Modules">
							<i class="icon-list-alt" style="color:#DFC96F;"></i>
							<div>Modules</div>
						</a>
					</div>
					
					<div class="col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="chart_of_accounts" function-name="generate_codes_for_accounts" module-id="1412705497" module-name="Chart of Accounts" title="Chart of Accounts" budget-id="-" month-id="-">
							<i class="icon-list" style="color:#DFC96F;"></i>
							<div>&nbsp;Auto Assign Codes&nbsp;</div>
						</a>
					</div>
					
					<div class="col-md-1 border-right  " style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="8270082579" function-id="8270082579" function-class="chart_of_accounts_config" function-name="display_all_records_full_view" module-id="1412705497" module-name="Chart of Accounts Config" title="Chart of Accounts Config" budget-id="-" month-id="-">
							<i class="icon-list" style="color:#DFC96F;"></i>
							<div>&nbsp;Chart of Accounts Config&nbsp;</div>
						</a>
					</div>
					
					
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" id="8270082579" function-id="8270082579" function-class="sales" function-name="get_sales_orders_without_picking_slips" module-id="1412705497" module-name="Sales" title="Click Here to View Undelivered Sales Order">
							<i class="icon-shopping-cart" style=""></i>
							<div>Pending Picking Slips</div>
						</a>
					</div>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" id="8270082579" function-id="8270082579" function-class="sales" function-name="get_picking_slips_without_sales_orders" module-id="1412705497" module-name="Sales" title="Click Here to View Picking Slips with Issues">
							<i class="icon-shopping-cart" style=""></i>
							<div>Picking Slips with Issues</div>
						</a>
					</div>
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" id="8270082579" function-id="8270082579" function-class="sales" function-name="get_sales_without_accounting" module-id="1412705497" module-name="Sales" title="Click Here to View Sales with Issues">
							<i class="icon-shopping-cart" style=""></i>
							<div>Sales without Financial Implication</div>
						</a>
					</div>
					
					<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
						<a href="#" class="icon-btn top-bar-icon custom-action-button" id="8270082579" function-id="8270082579" function-class="sales" function-name="get_accounting_without_sales" module-id="1412705497" module-name="Sales" title="Click Here to View Sales with Issues">
							<i class="icon-shopping-cart" style=""></i>
							<div>Revenue without Sales</div>
						</a>
					</div>
					
				</div>
			</div>
			<?php } ?>
			
		</div>
	</div>
	<!-- END RIBBON -->
</div>
</div>
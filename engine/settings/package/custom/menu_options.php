<?php 
	function custom_version2_submenu(  $o = array()  ){
		$return = array(
			
			"finance_report" => array(
				"title" => "Financial Statements",
				"action" => "reports_center&report=finance_report",
				"class" => 'all_reports',
			),
			"sales_report" => array(
				"title" => "Sales",
				"action" => "reports_center&report=sales_report",
				"class" => 'all_reports',
			),
			"procurement_report" => array(
				"title" => "Procurement",
				"action" => "reports_center&report=procurement_report",
				"class" => 'all_reports',
			),
			"requisition_report" => array(
				"title" => "Requisition",
				"action" => "reports_center&report=requisition_report",
				"class" => 'all_reports',
			),
			"fixed_assets_report" => array(
				"title" => "Fixed Assets",
				"action" => "reports_center&report=fixed_assets_report",
				"class" => 'all_reports',
			),
			
			/*travel booking*/
			"tb_history" => array(
				"title" => "Bookings History",
				"action" => "display_all_records_frontend",
				"class" => 'travel_bookings',
			),
			"tb_pending" => array(
				"title" => "Pending",
				"action" => "display_all_records_frontend&status=pending",
				"class" => 'travel_bookings',
			),
			"tb_paid" => array(
				"title" => "Paid",
				"action" => "display_all_records_frontend&status=paid",
				"class" => 'travel_bookings',
			),
			"tb_verified" => array(
				"title" => "Verified",
				"action" => "display_all_records_frontend&status=verified",
				"class" => 'travel_bookings',
			),
			"tb_cancel" => array(
				"title" => "Cancelled",
				"action" => "display_all_records_frontend&status=cancel",
				"class" => 'travel_bookings',
			),
			"tb_pricing" => array(
				"title" => "Destinations & Pricing",
				"action" => "display_all_records_frontend",
				"class" => 'travel_pricing',
			),
			/*travel booking*/
			
			"f_family" => array(
				"title" => "Family",
				"action" => "display_all_records_frontend",
				"class" => 'family',
			),
			"f_people" => array(
				"title" => "People",
				"action" => "display_all_records_frontend&status=cancel",
				"class" => 'people',
			),
			"f_people_family" => array(
				"title" => "People Family",
				"action" => "display_all_records_frontend&status=cancel",
				"class" => 'people_family',
			),


			"nwp_nape_about" => array(
				"title" => "About",
				"class" => "nwp_nape",
				"action" => "display_plugin_details",
			),
			"manage_plans" => array(
				"title" => "Manage Plans",
				"action" => "execute&nwp_action=nape_plans&nwp_todo=display_all_records_frontend",
				"class" => 'nwp_nape',
			),
			"manage_customers" => array(
				"title" => "Customers",
				"action" => "display_all_customers_list",
				"class" => 'customers',
			),
			"manage_libraries" => array(
				"title" => "NAPE Libraries",
				"class" => 'tags_library',
				"action" => "display_my_library&get_children=1",
			),
			"manage_subscriptions" => array(
				"title" => "NAPE Subscriptions",
				"action" => "display_all_customers_list",
				"sub_menu" => array( 'nape_pending', 'nape_paid', 'nape_cancel' ),
			),
			"manage_categories" => array(
				"title" => "Categories",
				"class" => 'nwp_nape',
				"action" => "execute&nwp_action=nape_category&nwp_todo=display_all_records_frontend",
			),
			"manage_nape_items" => array(
				"title" => "Items",
				"class" => 'nwp_nape',
				"action" => "execute&nwp_action=nape_items&nwp_todo=display_all_records_frontend",
			),
			"nape_pending" => array(
				"title" => "Pending",
				"class" => 'nwp_nape',
				"action" => "execute&nwp_action=nape_subscription&nwp_todo=display_all_records_frontend&status=pending",
			),
			"nape_paid" => array(
				"title" => "Paid",
				"class" => 'nwp_nape',
				"action" => "execute&nwp_action=nape_subscription&nwp_todo=display_all_records_frontend&status=paid",
			),
			"nape_cancel" => array(
				"title" => "Cancelled",
				"class" => 'nwp_nape',
				"action" => "execute&nwp_action=nape_subscription&nwp_todo=display_all_records_frontend&status=cancel",
			),


			"sales_h" => array(
				"title" => "Sales History",
				"class" => "sales",
				"action" => "display_all_records_frontend",
				"tooltip" => "Sales History",
			),
			"credit_note_h" => array(
				"title" => "Credit Notes History",
				"class" => "expenditure",
				"action" => "display_custom_credit_note_full_view",
				"tooltip" => "Credit Notes History",
			),
			"sales_invoice" => array(
				"title" => "New Sales Invoice",
				"class" => "cart",
				"action" => "display_sales_order_cart2",
			),
		);
		
		if( function_exists("system_version2_submenu") ){
			$return = array_merge( $return, system_version2_submenu( array( "skip_plugins" => 1 ) ) );
		}
		
		if( ! ( isset( $o["skip_plugins"] ) && $o["skip_plugins"] ) ){
			$return = add_nwp_plugin_options( array( "type" => "sub_menu", "data" => $return ) );
		}
		
		return $return;
	}
	
	function custom_version2_menu_settings(){
		return array(
			"prefix" => 'avs_',
			"main_prefix" => 'avm_',
			"module_name" => 'custom_version2_submenu',
			"main_module_name" => 'custom_version2_menu',
		);
	}
	
	function custom_version2_menu(){
		$return = array(
			/* "fdashboard" => array(
				"title" => "Home",
				"class" => "assets",
				"action" => "display_map_view",
				"icon" => "icon-globe",
				"tab" => array( "family_tree" => 1 ),
				"access" => "14426540071",
				"sub_menu" => array(
					"f_family", "f_people", "f_people_family", "f_dan"
				),
			),
			"dashboard" => array(
				"title" => "Home",
				"class" => "assets",
				"action" => "display_map_view",
				"icon" => "icon-globe",
				"tab" => array( "travel_booking" => 1 ),
				"access" => "14426540071",
				"sub_menu" => array(
					"procurement_dashboard", "hr_dashboard", "assets_dashboard"
				),
			),
			"bookings" => array(
				"title" => "Bookings",
				"class" => "1",
				"action" => "1",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "travel_booking" => 1 ),
				"sub_menu" => array(
					"tb_pending", "tb_paid", "tb_verified", "tb_cancel", "tb_history",
				),
			),
			"nape" => array(
				"title" => "NAPE",
				"class" => "1",
				"action" => "1",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "nape" => 1 ),
				"sub_menu" => array(
					 "nwp_nape_about", "manage_plans", "manage_customers", "manage_libraries", "manage_categories", "manage_nape_items", "manage_subscriptions"
				),
			),
			"sales" => array(
				"title" => "Sales",
				"class" => "1",
				"action" => "1",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "travel_booking" => 1, "nape" => 1 ),
				"sub_menu" => array(
					"sales_h", "credit_note_h",
				),
			),
			"set_up" => array(
				"title" => "Set-up",
				"class" => "customers",
				"action" => "display_app_view_manage_consultation",
				"icon" => "icon-stethoscope",
				"access" => "14426540071",
				"tab" => array( "travel_booking" => 1, "nape" => 1 ),
				"sub_menu" => array(
					  "documentation_preview", "tb_pricing", "user_accounts", "user_access_roles", "manage_options_list", "audit_trail"
				),	//"manage_branch_offices", "manage_units", 
			),
			"report" => array(
				"title" => "Financial Reports",
				"class" => "assets",
				"action" => "display_map_view",
				"icon" => "icon-globe",
				"tab" => array( "travel_booking" => 1, "nape" => 1 ),
				"access" => "14426540071",
				"sub_menu" => array(
					"cash_book_report", "receivables_and_payables", "finance_report", "inventory_report", "procurement_report", "sales_report" 
				),
			), */
		);
		
		if( function_exists("system_version2_menu") ){
			$return = array_merge( $return, system_version2_menu( array( "skip_plugins" => 1 ) ) );
		}
		
		if( ! ( isset( $o["skip_plugins"] ) && $o["skip_plugins"] ) ){
			$return = add_nwp_plugin_options( array( "type" => "main_menu", "data" => $return ) );
		}
		
		return $return;
	}
	
?>
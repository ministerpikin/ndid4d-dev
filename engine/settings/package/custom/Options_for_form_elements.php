<?php 
	include "menu_options.php";
	include "report_settings.php";
	
	function get_cache_refresh_keys(){
		return array( 
			"users" => "banks",
			"banks" => "category",
			"category" => "items", 
			"items" => "vendors", 
			//"vendors" => "discount", 
			"vendors" => "assets",
			
			"assets" => "assets_category",
			"assets_category" => "grade_level",
			"grade_level" => "discount",
			
			"discount" => "customers", 
			"customers" => "stores",
			"stores" => "sales",
			"sales" => "production",
			
			"production" => "departments",
			
			//"production" => "departments", 
			"departments" => "general_settings",
			"general_settings" => "modules",
			"modules" => "functions",
			"functions" => "access_roles",
			"access_roles" => "finish",
		);
	}
	
	
	function custom_get_options_type(){
		return array(
			'travel_location' => 'Travel Location',
			'travel_trip_class' => 'Travel Trip Class',
		);
	}
	
	function get_travel_state_of_bookings(){
		return array(
			'pending' => 'Pending',
			'paid' => 'Paid',
			'verified' => 'Verified',
			'cancel' => 'Cancelled',
		);
	}
	
	function get_travel_booking_trip_class(){
		return array(
			'executive' => 'Executive',
			'vip' => 'VIP',
			'vvip' => 'VVIP',
		);
	}
	
	function get_travel_booking_price_types(){
		return array(
			'flat_rate' => 'Flat Rate',
			'per_person' => 'Per Person'
		);
	}
	
	function get_alive_dead(){
		return array(
			'alive' => 'Alive',
			'dead' => 'Dead',
		);
	}
	
	function frontend_tabs(){
		$return2 = array(
			"reports-tab" => array(
				"title" => "Reports",
				"action" => "?action=all_reports&todo=display_report_menu&current_tab=report",
				"access_role" => "e039540b469a42cb2484ee61d3c25259",
			),
			"admin-tab" => array(
				"title" => "System",
				//"link" => "../engine/",
				//"action" => "",
				"action" => "?action=all_reports&todo=display_system_menu&current_tab=system",
				"access_role" => "e039540b469a42cb2484ee61d3c25259",
				"module_name" => "frontend_modules",
			),
		);
		$return = array();
		
		$pk = get_package_option();
		switch( $pk ){
		case "family_tree":
		case "travel_booking":
			$return = array(
				"home-tab" => array(
					"title" => "Home",
					"action" => "?action=all_reports&todo=display_inventory_menu&current_tab=" . $pk,
					"access_role" => "d80d45819d34925068980bfcfb44e978",
					"no_access" => 1,
				),
			);
		break;
		case "edms":
			$return = array(
				"home-tab" => array(
					"title" => "<i class='icon-home'>&nbsp;</i>",
					"action" => "?action=all_reports&todo=display_home_menu&type=workflow",
					"access_role" => "home",
					"module_name" => "frontend_modules",
					"no_access" => 1,
				),
			);
			unset( $return2["reports-tab"] );
		break;
		case "hr":
			$return = array(
				/* "home-tab" => array(
					"title" => "<i class='icon-home'>&nbsp;</i>",
					"action" => "?action=all_reports&todo=display_home_menu&type=workflow",
					"access_role" => "home",
					"module_name" => "frontend_modules",
					"no_access" => 1,
				), */
				"hr-tab" => array(
					"title" => "Human Resources",
					"action" => "?action=all_reports&todo=display_inventory_menu&current_tab=hr",
					"access_role" => "d80d45819d34925068980bfcfb44e978",
				),
			);
			unset( $return2["reports-tab"] );
		break;
		case "nape":
			/* $return = array(
				"home-tab" => array(
					"title" => "<i class='icon-home'>&nbsp;</i>",
					"action" => "?action=all_reports&todo=display_home_menu",
					"access_role" => "home",
					"module_name" => "frontend_modules",
					"no_access" => 1,
				),
			);
			$x = array(
				"title" => "NAPE",
				"action" => "?action=all_reports&todo=display_inventory_menu&current_tab=" . $pk,
				"access_role" => "d80d45819d34925068980bfcfb44e978",
				"no_access" => 1,
			);
			array_unshift( $return2, $x ); */
			$return = array(
				"home-tab" => array(
					"title" => "<i class='icon-home'>&nbsp;</i>",
					"action" => "?action=all_reports&todo=display_inventory_menu&current_tab=" . $pk,
					"access_role" => "home",
					"module_name" => "frontend_modules",
					"no_access" => 1,
				),
			);
			unset( $return2["reports-tab"] );
		break;
		default:
			if( ! get_hyella_development_mode() ){
				return array();
			}
		break;
		}
		
		return add_nwp_plugin_options( array( "type" => "frontend_tabs", "data" => array_merge( $return, $return2 ) ) );
	}
	
	function frontend_tabs_select(){
		$a = array();
		foreach( frontend_tabs() as $k => $v ){
			$a[ $k ] = $v["title"];
		}
		return $a;
	}

	function get_cart_status(){
		return array(
			'pending' => 'Pending',
			'in-progress' => 'In Progress',
			'paid' => 'Paid',
		);
	}
?>
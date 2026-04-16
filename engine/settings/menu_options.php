<?php 
	function system_version2_submenu( $o = array() ){
		$return = array(
			"data_access" => array(
				"title" => "Data Access",
				"class" => "audit",
				"action" => "display_data_access_view",
			),
			"nwp_ft_search" => array(
				"title" => "Full Text Index",
				"action" => "nwp_full_text_search",
				"sub_menu" => array(
					"nwp_ft_search_plugin_info", "nwp_ft_search_search", "nwp_ft_search_pending", "nwp_ft_search_complete", "nwp_ft_search_log"
				),
			),
			"nwp_ft_search_plugin_info" => array(
				"title" => "About",
				"class" => "nwp_full_text_search",
				"action" => "display_plugin_details",
			),
			"nwp_ft_search_search" => array(
				"title" => "Search",
				"class" => "nwp_full_text_search",
				"action" => "execute&nwp_action=index_queue&nwp_todo=search_index",
			),
			"nwp_ft_search_pending" => array(
				"title" => "Pending Index",
				"class" => "nwp_full_text_search",
				"action" => "execute&nwp_action=index_queue&nwp_todo=display_all_records_frontend",
			),
			"nwp_ft_search_complete" => array(
				"title" => "Complete Index",
				"class" => "nwp_full_text_search",
				"action" => "execute&nwp_action=indexed&nwp_todo=display_all_records_frontend",
			),
			"nwp_ft_search_log" => array(
				"title" => "Index Log",
				"class" => "nwp_full_text_search",
				"action" => "execute&nwp_action=index_log&nwp_todo=display_all_records_frontend",
			),
			"documentation" => array(
				"title" => "Help Builder",
				"action" => "nwp_help",
				"sub_menu" => array(
					"documentation_plugin_info", "documentation_new", "documentation_view", "documentation_preview"
				),
			),
			"documentation_plugin_info" => array(
				"title" => "About",
				"class" => "nwp_help",
				"action" => "display_plugin_details",
			),
			"documentation_new" => array(
				"title" => "New",
				"class" => "nwp_help",
				"action" => "execute&nwp_action=help_documentation&nwp_todo=display_capture_window",
			),
			"documentation_view" => array(
				"title" => "View",
				"class" => "nwp_help",
				"action" => "execute&nwp_action=help_documentation&nwp_todo=display_all_records_frontend",
			),
			"documentation_preview" => array(
				"title" => "User Guide",
				"class" => "nwp_help",
				"action" => "execute&nwp_action=help_documentation&nwp_todo=display_help",
			),
			
			"user_accounts" => array(
				"title" => "User Accounts",
				"class" => "users",
				"action" => "display_all_records_full_view",
				"icon" => "mdi mdi-account-outline"
				//"action" => "display_all_records_frontend",
			),
			"pending_user_accounts" => array(
				"title" => "Pending Accounts",
				"class" => "users",
				"action" => "display_all_records_full_view&status=pending",
				"icon" => "mdi mdi-account-clock-outline"
				//"action" => "display_all_records_frontend",
			),
			"revision_history_dashboard" => array(
				"title" => "Revision Dashboard",
				"class" => "revision_history",
				"action" => "view_dashboard",
				//"action" => "display_all_records_frontend",
				"icon" => "mdi mdi-history"
			),
			"user_access_roles" => array(
				"title" => "Access Roles",
				"class" => "access_roles",
				"action" => "display_all_records_full_view",
				"icon" => "mdi mdi-account-key-outline"
			),
			"api_clients" => array(
				"title" => "API Clients",
				"class" => "api",
				"action" => "display_all_records_full_view",
				//"action" => "display_all_records_frontend",
			),
			"locked_files" => array(
				"title" => "Locked Files",
				"class" => "locked_files",
				"action" => "display_all_records_full_view",
			),
			"logged_in_users" => array(
				"title" => "Logged-in Users",
				"class" => "logged_in_users",
				"action" => "display_all_records_full_view",
			),
			"audit_trail" => array(
				"title" => "Audit Trail",
				"class" => "audit",
				"action" => "display_audit_trail_analysis_view",
				"icon" => "mdi mdi-fingerprint"
			),
			"admin_control_panel" => array(
				"title" => "Admin Control Panel",
				"class" => "audit",
				"action" => "display_admin_panel_view",
			),
			"departments" => array(
				"title" => "Departments",
				"class" => "departments",
				"action" => "display_all_records_full_view",
			),
			"stores" => array(
				"title" => "Stores",
				"class" => "stores",
				"action" => "display_all_records_frontend",
			),
			"general_settings" => array(
				"title" => "General Settings",
				"class" => "general_settings",
				"action" => "display_all_records_frontend",
			),
			"list_box_options" => array(
				"title" => "List Box Options",
				"class" => "list_box_options",
				"action" => "display_all_records_frontend",
				"icon" => "mdi mdi-format-list-bulleted"
			),
			"project_settings" => array(
				"title" => "Project Settings",
				"class" => "project_settings",
				"action" => "display_all_records_frontend",
			),
			"database_model" => array(
				"title" => "Data Model (ERD)",
				"class" => "audit",
				"action" => "display_erd",
				"development" => 1,
			),
			"database_migrate" => array(
				"title" => "Migrate Data",
				"class" => "audit",
				"action" => "move_data_from_old_fields_to_new_prompt",
				"development" => 1,
			),
			"database_upgrade" => array(
				"title" => "Database Upgrade",
				"class" => "audit",
				"action" => "upgrade_database_prompt",
				"development" => 1,
			),
			"database_objects" => array(
				"title" => "Database Objects",
				"class" => "database_objects",
				"action" => "display_all_records_full_view",
				"development" => 1,
			),
			"database_fields" => array(
				"title" => "Database Fields",
				"class" => "database_fields",
				"action" => "display_all_records_full_view",
				"development" => 1,
			),
			"database_tables" => array(
				"title" => "Database Tables",
				"class" => "database_table",
				"action" => "display_all_records_full_view",
				"development" => 1,
			),
			
			"manage_locations" => array(
				"title" => "Locations",
				"class" => "country_list",
				"sub_menu" => array( "manage_country", "manage_state_list", "manage_lga_list", "manage_tribe_list" ),
			),
			"manage_country" => array(
				"title" => "Countries",
				"class" => "country_list",
				"action" => "display_all_records_frontend",
			),
			"manage_state_list" => array(
				"title" => "States",
				"class" => "state_list",
				"action" => "display_all_records_frontend",
			),
			"manage_lga_list" => array(
				"title" => "LGA",
				"class" => "lga_list",
				"action" => "display_all_records_frontend",
			),
			"manage_tribe_list" => array(
				"title" => "Tribe",
				"class" => "tribe_list",
				"action" => "display_all_records_frontend",
			),
			"manage_options_list" => array(
				"title" => "More Options",
				"class" => "banks",
				"action" => "display_all_records_frontend",
			),

			"import_items" => array(
				"title" => "Import Items",
				"class" => "banks",
				"sub_menu" => array( "import_with_mapping", "import_without_mapping", "update_from_excel" ),
			),
			"import_with_mapping" => array(
				"title" => "Import With Mapping",
				"class" => "import_items",
				"action" => "pre_import&step=excel_import_form_options_map",
			),
			"import_without_mapping" => array(
				"title" => "Import Without Mapping",
				"class" => "import_items",
				"action" => "pre_import&step=excel_import_form_options_nomap",
			),
			"update_from_excel" => array(
				"title" => "Update From Excel",
				"class" => "import_items",
				"action" => "pre_import&step=excel_import_form_options",
			),
			"files" => array(
				"title" => "Files",
				"class" => "files",
				"action" => "display_all_records_frontend",
			),
			"comments" => array(
				"title" => "Comments",
				"class" => "comments",
				"action" => "display_all_records_frontend",
			),
			"folders" => array(
				"title" => "Folders",
				"class" => "tags",
				"action" => "display_all_records_frontend",
			),
			"folders-item" => array(
				"title" => "Folder Items",
				"class" => "tags_item",
				"action" => "display_all_records_frontend",
			),
			"share" => array(
				"title" => "Share",
				"class" => "share",
				"action" => "display_all_records_frontend",
			),

			"form-builder" => array(
				"title" => "Form Builder",
				"action" => "",
				"sub_menu" => array(
					"form-builder-new",
					"form-builder-view",
				),
			),
			"form-builder-new" => array(
				"title" => "New Form",
				"class" => "database_forms",
				"action" => "display_canvas",
			),
			"form-builder-view" => array(
				"title" => "View Forms",
				"class" => "database_forms",
				"action" => "display_all_records_frontend",
			),
			
			"test_db" => array(
				"title" => "Switch Database",
				"class" => "audit",
				"action" => "switch_to_test_database",
			),
		);
		
		//19-jun-23: 2echo
		if( defined("USERS_COUNTRY_LABEL") && USERS_COUNTRY_LABEL ){
			if( ( isset( $return["stores"] ) && $return["stores"] ) ){
				$return["stores"][ "title" ] = USERS_COUNTRY_LABEL;
			}
		}
		
		//$return = add_nwp_plugin_options( array( "type" => "sub_menu", "data" => $return ) );
		if( ! ( isset( $o["skip_plugins"] ) && $o["skip_plugins"] ) ){
			$return = add_nwp_plugin_options( array( "type" => "sub_menu", "data" => $return ) );
		}
		
		return $return;
	}
	
	function system_version2_menu( $o = array() ){
		$return = array(
			"access_control" => array(
				"title" => "Access Control",
				"class" => "dashboard",
				"action" => "",
				"icon" => "icon-cogs",
				"access" => "14426540071",
				"tab" => array( "system" => 1 ),
				"sub_menu" => array(
					"user_accounts", /*"pending_user_accounts",*/ "revision_history_dashboard", /*"api_clients",*/ "user_access_roles", "locked_files", "logged_in_users", "audit_trail"
				),
			),
			// "utilities" => array(
			// 	"title" => "Utilities",
			// 	"class" => "dashboard",
			// 	"action" => "",
			// 	"icon" => "icon-cogs",
			// 	"access" => "14426540071",
			// 	"tab" => array( "system" => 1 ),
			// 	"sub_menu" => array(
			// 		"comments", "files", "share", "folders", "folders-item", "form-builder"
			// 	),
			// ),
			"system_settings" => array(
				"title" => "System Settings",
				"class" => "dashboard",
				"action" => "",
				"icon" => "icon-cogs",
				"access" => "14426540071",
				"tab" => array( "system" => 1 ),
				"sub_menu" => array(
					/*"test_db", "admin_control_panel", "stores", "departments",*/ "general_settings", "manage_options_list", "project_settings", "list_box_options", "manage_locations",/* "documentation"*/
				),
			),
			"dev_archives" => array(
				"title" => "Archives",
				"class" => "dashboard",
				"action" => "",
				"icon" => "icon-cogs",
				"access" => "14426540071",
				"tab" => array( "system" => 1 ),
				"sub_menu" => array(
					"data_access", /*"a_social_registry", "import_items", "nwp_ft_search"*/
				),
			),
			"sys_dev" => array(
				"title" => "Development",
				"class" => "dashboard",
				"action" => "",
				"icon" => "icon-cogs",
				"access" => "14426540071",
				"tab" => array( "system" => 1 ),
				"sub_menu" => array(
					/*"database_tables", "database_fields", "database_objects",*/ "database_model", "database_upgrade", "database_migrate"
				),
			),
		);
		
		if( ! ( isset( $o["skip_plugins"] ) && $o["skip_plugins"] ) ){
			$return = add_nwp_plugin_options( array( "type" => "main_menu", "data" => $return ) );
		}
		
		return $return;
	}
	
?>
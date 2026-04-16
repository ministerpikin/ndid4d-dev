<?php 
	//testing
	//$package_config["plugins_loaded"]['nwp_help'] = 'cNwp_help';
	
	foreach( $classes[ 'ajax_request_processing_script' ] as $i => $_cls ){
		switch( $_cls ){
		case "cInventory":
		case "cItems":
		case "cCustomers":
		case "cCategory":
		case "cSite_users":
			unset( $classes[ 'ajax_request_processing_script' ][$i] );
		break;
		}
	}
	
	$classes[ 'ajax_request_processing_script' ][] = "package/custom/cAll_reports";
	$classes[ 'ajax_request_processing_script' ][] = "cProject_settings";
	
	switch( get_package_option() ){
	case "travel_booking":
		$classes[ 'ajax_request_processing_script' ][] = "package/enterprise/cOnline_payment";
		$classes[ 'ajax_request_processing_script' ][] = "cQuotation";
		
		$classes[ 'ajax_request_processing_script' ][] = "package/accounting/cCategory";
		$classes[ 'ajax_request_processing_script' ][] = "package/accounting/cInventory";
		$classes[ 'ajax_request_processing_script' ][] = "package/enterprise/cInventory_ent";
		$classes[ 'ajax_request_processing_script' ][] = "package/accounting/cCustomers";
		$classes[ 'ajax_request_processing_script' ][] = "package/accounting/cItems";
		
		$classes[ 'ajax_request_processing_script' ][] = "package/custom/cTravel_bookings";
		$classes[ 'ajax_request_processing_script' ][] = "package/custom/cTravel_pricing";
	break;
	case "edms":
	case "nape":
		$classes[ 'ajax_request_processing_script' ][] = "package/enterprise/cOnline_payment";
		$classes[ 'ajax_request_processing_script' ][] = "cQuotation";
		$classes[ 'ajax_request_processing_script' ][] = "dev/cDatabase_forms";
		$classes[ 'ajax_request_processing_script' ][] = "cComments";
		
		$classes[ 'ajax_request_processing_script' ][] = "cFiles";
		$classes[ 'ajax_request_processing_script' ][] = "cShare";
		$classes[ 'ajax_request_processing_script' ][] = "cTags";
		$classes[ 'ajax_request_processing_script' ][] = "cTags_item";
	break;
	case "family_tree":
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cFamily_tree";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cPeople";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cFamily";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cPeople_family";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cPeople_spouse";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cVillage";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cVillage_admins";
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cFamily_admins";
		
		$classes[ 'ajax_request_processing_script' ][] = "package/family_tree/cRoots_verification";
	break;
	case "hr":
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cPay_roll_post";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cPay_row";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cPay_roll_auto_generate";
		
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_next_of_kin";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_educational_history";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_dependents";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_work_experience_history";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_current_work_history";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_performance_appraisal_history";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_professional_association";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_disciplinary_history";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_salary_details";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_employee_profile";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cGuarantors";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_attendance";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cUsers_expense";
		
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cLeave_types";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cLeave_roaster";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cLeave_requests";
		$classes[ 'ajax_request_processing_script' ][] = "package/human-resource/cEmployees_request";
		
		$classes[ 'ajax_request_processing_script' ][] = "cLga_list";
		//$classes[ 'ajax_request_processing_script' ][] = "cTribe_list";
	break;
	}
?>
<?php

function get_report_bay_type(){
	return array(
		'card' => 'Card',
		'bar' => 'Bar Graph',
		'hbar' => 'Horizontal Bar Graph',
		'pie' => 'Pie Chart',
		'line' => 'Line',
		'pline' => 'Percent Line',
		'pline2' => 'Percent Line 2',
		'nested_card' => 'Nested Card',
		'key_value' => 'Key Value',
		'report_value' => 'Report Value'
	);
}

function get_report_bay_category(){
	return array(
		'auto' => 'Query Builder',
		'custom' => 'Custom Query',
	);
}

function get_report_hash_options( $opt = array() ){
	$w = [];
	
	$w["hash_keys"] = array(
		'download_med_rec' => array(
			'strict_hash' => 1,
			'keys' => array(
				'cid' => 1,
				'exclude_fields' => 1,
				'exclude_fields' => 1,
			),
		),
	);

	if( isset( $opt[ 'rkey' ] ) && isset( $w[ 'hash_keys' ][ $opt[ 'rkey' ] ] ) && $w[ 'hash_keys' ][ $opt[ 'rkey' ] ] ){
		return $w[ 'hash_keys' ][ $opt[ 'rkey' ] ];
	}

	return $w;
}

function get_report_endpoint( $opt = array() ){

	$params = '';
	$params = '?nwp_request=' . $opt['request_type'];
	
	if( isset( $opt['input']['get'] ) && ! empty( $opt['input']['get'] ) ){
		foreach( $opt['input']['get'] as $k => $v ){
			switch( $k ){
			case "app_secret_function":
			case "public_key_function":
			break;
			case "app_secret":
			case "public_key":
				$v1 = '';
				// eval( '$v1 = ' . $v . ';' );
				$params .= '&' . $k . '=' . $v;
			break;
			default:
				$params .= '&' . $k . '=' . $v;
			break;
			}
		}
	}
	
	return $params;
}

function get_all_report_types(){
	if( class_exists('cAudit') ){
		$ad = new cAudit();
		$opt = [];
		$opt['group_plugin'] = 1;
		$opt['no_labels'] = 1;
		$opt['no_fields'] = 1;
		$opt['for_database_table_name'] = 1;
		$opt['selected_class_only'] = [
			'cNwp_reports' => 1,
			'cNwp_grm' => 1,
			'cNwp_device_management' => 1
		];

		$opt['excluded_plugin_classes'] = [
			'report_config' => 1,
			'reports_ui' => 1,
			'reports_bay' => 1,
			"frequent_changes" => 1,
			"non_frequent_changes" => 1,
			"frequent_changes_sr" => 1,
			"non_frequent_changes_sr" => 1,
			"project_development" => 1,

			"device_mgt_store" => 1,
			"device_mgt_store_sr" => 1,
		];

		$r = $ad->_get_project_classes( $opt );
		return $r;
	}	
}

function get_report_source_types( $access = array() ){
	$a = get_all_report_ac_types();
	$access = !$access ? get_accessed_functions() : $access;
	$super = !is_array($access) && $access == 1 ? 1 : 0;

	if( !$super && $a ){
		foreach ($a as $key => $value) {
			if( !(isset($access['accessible_functions']['report_config.rt_opt.' . $key ]) && $access['accessible_functions']['report_config.rt_opt.' . $key ]) ){
				unset($a[ $key ]);
			}
		}
	}
	return $a;
}

function get_vat_mapping(){
	return array(
		'es_monitoring' => 'environmental_and_social_safeguard_indicators',
		'social_accountability_monitoring' => 'social_dashboard',
		'communications' =>	'comm_dashboard',
		'suspicious_activity' => 'suspicious_dashboard',
		'financial_indicators_on_enrolment_services' => 'financial_dashboard',
		'grm_data' => 'grm_dashboard',
		'project_development' => 'project_dashboard',
		'device_mgt_sr' => 'device_dashboard2',
		'device_mgt' => 'device_dashboard',
		'es_monitoring2' => 'nin_enrolments',
		'indicators' => 'indicator_dashboard'
	);
}

function get_all_report_indicators(){
	return get_list_box_options('report_indicators', array('return_type' => 2) );
}

function get_report_indicators(){
	$a = get_all_report_indicators();
	$access = get_accessed_functions();
	$super = !is_array($access) && $access == 1 ? 1 : 0;
	if( !$super && $a ){
		foreach ($a as $key => $value) {
			if( !(isset($access['accessible_functions']['reports_bay.rt_in_opt.' . $key ]) && $access['accessible_functions']['reports_bay.rt_in_opt.' . $key ]) ){
				unset( $a[ $key ] );
			}
		}
	}
	return $a;
}

function get_all_report_ac_types(){
	$a = get_all_report_types();
	$b = array_column($a, 'classes');
	$b[] = [ 'es_monitoring2' => "Enrolment Statistics" ];
	return array_merge(...$b);
}

function get_elastic_field_mappings( $form_field ){
	$fm = [];
	if( $form_field ){
		switch ( $form_field ) {
			case 'email':
	        case 'checkbox':
	        case 'select':
	        case 'radio':
	        case 'calculated':
	        case 'text':	//06-oct-23
	            $fm['type'] = 'keyword';
            break;
	        case 'date-5':
	        case 'date-5time':
	            $fm['type'] = 'date';
	            $fm['format'] = 'epoch_second';
            break;
	        case 'decimal':
	        case 'decimal_long':
	            $fm['type'] = 'float'; // Use 'float' for decimal fields
            break;
	        case 'number':
	            $fm['type'] = 'integer'; // Use 'integer' for number fields
            break;
	        default:
	            $fm['type'] = 'text';
            break;
		}
	}

	return $fm;
}

function default_suspicious_activity_classification( $suspicious_activity ){
	if( isset( $suspicious_activity['type'] ) && $suspicious_activity['type'] ){
		return "enrolment";
	}
}

function default_suspicious_activity_status( $suspicious_activity ){
	if( isset( $suspicious_activity['type'] ) && $suspicious_activity['type'] ){
		return "suspicious";
	}
}

function get_suspicious_activity_type(){
	return array(
		'device_inactivity' => 'Device Inactivity',
		'multiple_attempts' => 'Multiple Login Attempts',
		'different_fep_login' => 'Different FEP'
	);
}

function get_report_key(){
	return array(
		"environmental_and_social_safeguard_indicators" => "Environmental and Social Safeguards",
		"grm_dashboard" => "Grieviance Redress Mechanism",
		"comm_dashboard" => "Enrolee Satisfaction",
		"social_dashboard" => "Social Accountability Monitoring",
		"suspicious_dashboard" => "Suspicious Enrolment Activity",
		"financial_dashboard" => "Financial Indicators",
		"project_dashboard" => "Project Development Objectives - NIDB",
		"indicator_dashboard" => "Project Development Objectives (Revised)",
		"comm_i_dashboard" => "Internal and External Communications",
		"device_dashboard" => "Approved Enrollment Devices",
		"device_dashboard2" => "Pending Enrollment Device",
		"nin_enrolments" => "Enrollment Statistics",
	);
}

?>
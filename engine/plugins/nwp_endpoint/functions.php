<?php
	//define("NWP_SUPPORTED_FILE_UPLOAD_FORMATS", "csv,json,xml" );
	//define("NWP_SUPPORT_FILE_UPLOAD_TEXTAREA", 1 );

	function get_supported_load_type(){
		$r = array(
			//'json' => 'JSON',
			'csv' => 'CSV',
			//'xml' => 'XML'
		);

		if( defined("NWP_SUPPORTED_FILE_UPLOAD_FORMATS") && NWP_SUPPORTED_FILE_UPLOAD_FORMATS ){
			$supported_formats = explode( ",", NWP_SUPPORTED_FILE_UPLOAD_FORMATS );
			foreach( $supported_formats as $sv ){
				$r[ $sv ] = strtoupper( $sv );
			}
		}

		return $r;
	}


	function get_endpoint_status(){
		$a = get_active_inactive();
		$a['in_progress'] = 'In-Progress';
		return $a;
	}

	function get_request_type(){
		return array(
			'get' => 'GET',
			'post' => 'POST',
			'put' => 'PUT',
			'patch' => 'PATCH',
			'delete' => 'DELETE'
		);
	}

	function get_pre_request_type(){
		return array(
			'auth' => 'Authentication',
			'auth_bearer' => 'Authentication (Bearer Token)'
		);
	}

	function get_callback_action(){
		return array(
			'none' => 'None',
			'load_data' => 'Load Data Into Table'
		);
	}

	function get_recurrence_time(){
		return array(
			// 'none' => 'None',
			// 'ss' => 'Seconds',
			'min' => 'Minute(s)',
			'hour' => 'Hour(s)',
			'days' => 'Day(s)',
			'week' => 'Week(s)',
			'year' => 'Year(s)'
		);
	}

	function get_dashboard_sidemenu(){
		return array(
			// 'dashboard' => array(
			// 	'icon' => 'mdi mdi-speedometer',
			// 	'title' => 'Dashboard',
			// 	'action' => 'manage_dashboard',
			// 	'href' => '/dashboard'
			// ),
			'spreadsheet' => array(
				'icon' => 'mdi mdi-table',
				'title' => 'Spreadsheet',
				'action' => 'manage_spreadsheet&nwp_tab=1',
				'href' => '/spreadsheet'
			),
			'fileload' => array(
				'icon' => 'mdi mdi-file-upload',
				'title' => 'File Load',
				'action' => 'manage_fileload&nwp_tab=1',
				'href' => '/fileload'
			),
		);
	}

	function updateNestedArrayValue(array &$nestedArray, array $keys, $value) {
	    $currentKey = array_shift( $keys );
	    if (count($keys) === 0) {
	        $nestedArray[$currentKey] = $value;
	    } else {
	        if (isset($nestedArray[$currentKey]) && is_array($nestedArray[$currentKey])) {
	            updateNestedArrayValue($nestedArray[$currentKey], $keys, $value);
	        } else {
	            $nestedArray[ $currentKey ] = [];
	            updateNestedArrayValue( $nestedArray[ $currentKey], $keys, $value);
	        }
	    }

	    return $nestedArray;
	}	

	function get_e_log_status(){
		return array(
			'failed' => 'Failed',
			'rerun' => 'Re-Ran',
			'p_rerun' => 'Re-Ran (Purged)',
			'purged' => 'Purged',
			'success' => 'Successful'
		);
	}

	function endpoint_log_access_buttons(){
		return array(
			// 'sb1' => 'Re-Run',
			// 'sb2' => 'Purge Data & Re-Run',
			'sb3' => 'Purge Data',
		);
	}

	/**
	 * Prepares log data for storage.
	 *
	 * This function iterates through an associative array of log data and converts any nested arrays within the data to JSON encoded strings. 
	 * This ensures that the log data can be easily stored and retrieved later.
	 *
	 * @param array $log An associative array containing log data. Keys should be strings and values can be of any type.
	 * 
	 * @return array The modified log data with any nested arrays converted to JSON strings.
	 * 
	 * @throws No Exception If there is an error encoding data to JSON.
	 */
	function fLog(array $log){
		foreach($log as $k => $sval){
			if( is_array( $sval ) ){
				$log[ $k ] = json_encode($sval);
			}
		}
		return $log;
	}

	function pull_cache_values( array $settings ){
		$r = [];
		if( ( isset( $GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
			$settings['set_memcache'] = $GLOBALS['app_memcache'];
			if( isset( $settings['permanent'] ) && $settings['permanent'] ){
				$dir = '';
				if( isset( $settings['directory_name'] ) && $settings['directory_name'] ){
					$dir = $settings['directory_name'] . '/';
					$sn = 1;
					foreach( glob( $settings['set_memcache']::$path . '/' . $dir . $settings['directory_name'] . '*.json') as $filename ) {
						$hfi = json_decode( file_get_contents( $filename ), true);
						if( is_array( $hfi ) && isset( $hfi[ 'id' ] ) ){

							if( isset( $settings['return_cache']) && $settings['return_cache'] ){
								if( isset($settings['return_cache']['count']) && $settings['return_cache']['count'] ){
									if( $sn >= (int)$settings['return_cache']['count'] ){
										return $r;
									}
								}
								$r[] = $hfi;
								$sn++;
							}else{
								$_t = false;
								if( isset($settings['filter']['function']) && $settings['filter']['function'] ){
									$t = $settings['filter']['function'];
									if( is_string($settings['filter']['function']) && function_exists( $settings['filter']['function'] ) || is_callable( $settings['filter']['function'] ) ){
										$_t = $settings['filter']['function']( $hfi );
									}
								}
								if( !$_t ){
									if( isset($settings['filter']['key']) && $settings['filter']['key'] && isset($settings['filter']['value']) && $settings['filter']['value'] ){
										$_t = isset($hfi[ $settings['filter'][ 'key' ] ]) && $hfi[ $settings['filter'][ 'key' ] ] == $settings['filter']['value'];
									}
								}
								if( $_t ){
									if( isset($settings['filter']['index_field']) && $settings['filter']['index_field'] && isset($hfi[ $settings['filter']['index_field'] ]) && $hfi[ $settings['filter']['index_field'] ] ){
										$r[ $hfi[ $settings[ 'filter' ]['index_field'] ] ] = $hfi;
									}else{
										$r[] = $hfi;
									}
								}								
							}
						}
					}
				}
			}
		}
		return $r;
	}

	function get_database_tables3(){
		$ad = new cAudit();
		$opt = array();
		$opt['no_labels'] = 1;
		$opt['no_fields'] = 1;
		$opt['group_plugin2'] = 1;
		$opt['for_database_table_name'] = 1;
		$opt['selected_class_only'] = array(
			'cNwp_reports' => 1,
			'cNwp_device_management' => 1,
			'cNwp_enrolment_data' => 1
		);

		$opt['excluded_plugin_classes'] = array(
			'reports_bay' => 1,
			'report_config' => 1,
			'reports_ui' => 1,

			'frequent_changes' => 1,
			'frequent_changes_sr' => 1,
			'non_frequent_changes' => 1,
			'non_frequent_changes_sr' => 1,

			'enrolment_device' => 1,
			'enrolment_agent_activity' => 1,
			'enrolment_fingerprint' => 1,
			'enrolment_photo' => 1,
			'enrolment_demographic' => 1,
			"enrolment_document" => 1,
			"enrolment_signature" => 1,
			"enrolment_preview" => 1,
			"enrolment_liveness" => 1,
			"enrolment_iris" => 1,
			"enrolment_metadata" => 1,


		);

		if( get_hyella_development_mode() ){
			$opt['selected_class_only']['cUsers'] = 1;
		}

		return $ad->_get_project_classes( $opt );
	}

	function format_time_value( $val ){
		$strtotime = strtotime( $val );
		if( $strtotime !== false ){
			return $strtotime;
		}
		return (int)$val;
	}
?>
<?php
/**
 * enrolment_metadata Class
 *
 * @used in  				enrolment_metadata Function
 * @created  				Bay4 Mike | 08:05 | 26-Sep-2023
 * @database table name   	enrolment_metadata
 */
	
	class cEnrolment_metadata extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'enrolment_metadata';
		
		public $default_reference = '';
		
		public $label = 'Enrolment Metadata';
		
		private $associated_cache_keys = array(
			'enrolment_metadata',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 0,
				
				'utility_buttons' => array(
					'comments' => 1,
					//'tags' => 1,
					'view_details' => 1,
				),
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
		);
			
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/enrolment_metadata.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/enrolment_metadata.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function enrolment_metadata(){
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete_from_popup2':
			case 'delete_from_popup':
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_frontend_history':
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
			case "display_all_records_others":
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'edit_popup_form_in_popup':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'view_details2':
			case 'view_details':
			case 'control_panel':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'save_line_items':
				$returned_value = $this->_save_line_items();
			break;
			case 'bg_execute':
				$returned_value = $this->_bg_execute();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _bg_execute(){

			$_GET = array();
			$_POST = array();

			if( ! ( defined( 'ENROL_DATA_CHECK_INTERVAL' ) && intval( ENROL_DATA_CHECK_INTERVAL ) ) ){
				return 'Undefined ENROL_DATA_CHECK_INTERVAL';
			}

			if( ! ( defined( 'ENROL_DATA_PATH' ) && ENROL_DATA_PATH ) ){
				return 'Undefined ENROL_DATA_PATH';
			}

			$bdpk = "bg-". $this->table_name ."-in-progress";

			$bdpk_exec = 0;

			$settings = array( 'cache_key' => $bdpk, );
			$s = get_cache_for_special_values( $settings );
			if( get_hyella_development_mode() ){
				$s = 0;
			}

			if( $s ){

			}else{

				$settings = array(
					'cache_key' => $bdpk,
					'cache_values' => 1,
				);
				$s = set_cache_for_special_values( $settings );
				
				$cut = time();
				$next_check2 = 0;
				$ty_mins = 60 * intval( ENROL_DATA_CHECK_INTERVAL ); // 10mins
				$dirname = $this->table_name;
				

				$path1 = $this->class_settings["calling_page"] . 'tmp/filescache/' . $this->class_settings[ 'database_name' ] . '/' . $this->table_name ;
				$path = $path1 . '/enrol_data.json';

				$path2 = ENROL_DATA_PATH;
				$path3 = ENROL_DATA_PATH . '-loaded';

				// if( file_exists( ( $path ) ) ){
				if( 0 && file_exists( ( $path ) ) ){
					// echo 'yeah';
					$ltc = json_decode( file_get_contents( $path ), true );
					if( isset( $ltc["next_check"] ) ){
						$next_check2 = doubleval( $ltc["next_check"] );
					}
				}else{
					$next_check2 = $cut - $ty_mins;
				}
				// print_r( $path.'---' );

				if( $cut > $next_check2 ){

					if( file_exists( $path2 ) && dir( $path2 ) ){
						if( defined("BULK_IMPORT_MEMORY_LIMIT") && BULK_IMPORT_MEMORY_LIMIT ){
							ini_set('memory_limit', BULK_IMPORT_MEMORY_LIMIT );
						}else{
							ini_set('memory_limit', '4G' );
						}

					    foreach( scandir( $path2 ) as $file ){
					    	$history = array();

					        if ( $file !== '.' && $file !== '..' ) {

					        	$read_message = "Reading File - '". $file ."'\n";
					        	echo $read_message;
					        	$history[] = $read_message;

					        	$content = file_get_contents( $path2 . '/' . $file );
					        	if( $content ){
					        		$json = json_decode( $content, 1 );

					        		if( is_array( $json ) && ! empty( $json ) ){

										$rr = $this->_loadEnrolDataFile( $json, $file );
										if( isset( $rr[ 'error' ] ) && $rr[ 'error' ] ){
						        			$read_message = $rr[ 'error' ] ."'\n";
						        			echo $read_message;
						        			$history[] = $read_message;
										}

					        		}else{

					        			$read_message = "Corrupt JSON - '". $file ."'\n";
					        			echo $read_message;
					        			$history[] = $read_message;

					        		}
					        		
					        	}else{

					        		$read_message = "Empty File - '". $file ."'\n";
					        		echo $read_message;
					        		$history[] = $read_message;

					        	}
					        	// print_r( file_get_contents( $path2 . '/' . $file ) );exit;
					        	
					        	if( ! file_exists( $path3 ) ){
					        		create_folder( $path3, '', '' );
					        	}

			       				rename( $path2 . '/' . $file, $path3 . '/' . $file );

				        	}

						}
					}

					$ltc[ 'next_check' ] = $cut + $ty_mins;
					if( ! file_exists( $path1 ) ){
						create_folder( $path1, '', '' );
					}
					file_put_contents( $path, json_encode( $ltc ) );
					//print_r( $path.'---' );
					//print_r( $next_check2 );exit;
				}

				$settings = array( 'cache_key' => $bdpk );
				$s = clear_cache_for_special_values( $settings );
				
			}

			if( $bdpk_exec ){
				return [ 'executed' => 1 ];
			}else{
				return;
			}

		}

		protected function _loadEnrolDataFile( $json = [], $filename = '' ){
			$error = '';

			if( is_array( $json ) && ! empty( $json ) ){

				$key = 'deviceInformation';
				if( isset( $json[ $key ] ) && $json[ $key ] ){
					$this->_createDevice( $json[ $key ], $json );
				}
				
				$key = 'agentInformation';
				if( isset( $json[ $key ] ) && $json[ $key ] ){
					$this->_createAgent( $json[ $key ], $json );
				}

				$this->_createHeartbeats( $json );

				$this->_createEnrolment( $json, $filename );
				
			}else{
				$error = 'Invalid JSON Data';
			}

			if( $error ){
				return [ 'error' => $error ];
			}

		}

		protected function _createDevice( $device = [], $metatdata = [] ){
			if( isset( $device[ 'deviceId' ] ) && $device[ 'deviceId' ] ){
				$device_id = md5( $device[ 'deviceId' ] );

				$date = date("Y-m-dTH:i:s");
				$type = 'pc';
				if( isset( $device[ 'imei' ] ) && $device[ 'imei' ] ){
					$type = 'mobile';
				}

				$model;
				if( isset( $device[ 'model' ] ) && $device[ 'model' ] ){
					$model = $device[ 'model' ];
				}

				$status = 'active';
				$center = '';
				if( isset( $device[ 'regCenter' ] ) && $device[ 'regCenter' ] ){
					$center = $device[ 'regCenter' ];
				}
				$custodian = '';
				if( isset( $metatdata[ 'agentInformation' ][ 'agentNin' ] ) && $metatdata[ 'agentInformation' ][ 'agentNin' ] ){
					$custodian = md5( $metatdata[ 'agentInformation' ][ 'agentNin' ] );
				}
				$purpose = '';
				if( isset( $metatdata[ 'enrollmentUsecase' ] ) && $metatdata[ 'enrollmentUsecase' ] ){
					$purpose = $metatdata[ 'enrollmentUsecase' ];
				}
				$serial_number = '';
				$storage = 0;
				if( isset( $device[ 'totalStorage' ] ) && $device[ 'totalStorage' ] ){
					$storage = cNwp_app_core::convertStorageToKilobytes( $device[ 'totalStorage' ] );
				}
				$os_name = isset( $device[ 'osName' ] ) ? $device[ 'osName' ] : '';
				$os_version = isset( $device[ 'osVersion' ] ) ? $device[ 'osVersion' ] : '';
				$app_version = isset( $device[ 'appVersion' ] ) ? $device[ 'appVersion' ] : '';
				$processor_speed = isset( $device[ 'processorSpeed' ] ) ? $device[ 'processorSpeed' ] : '';
				$avail_storage = isset( $device[ 'availableStorage' ] ) ? cNwp_app_core::convertStorageToKilobytes( $device[ 'availableStorage' ] ) : '';
				$ram = isset( $device[ 'ramSize' ] ) ? cNwp_app_core::convertStorageToKilobytes( $device[ 'ramSize' ] ) : '';
				$imsi = isset( $device[ 'imsi' ] ) ? $device[ 'imsi' ] : '';
				$imei = isset( $device[ 'imei' ] ) ? $device[ 'imei' ] : '';
				$network_strength = isset( $metatdata[ 'networkStrength' ] ) ? $metatdata[ 'networkStrength' ] : '';
				$network_type = isset( $metatdata[ 'networkType' ] ) ? $metatdata[ 'networkType' ] : '';
				$latitude = isset( $metatdata[ 'latitude' ] ) ? (float)$metatdata[ 'latitude' ] : 0;
				$longitude = isset( $metatdata[ 'longitude' ] ) ? (float)$metatdata[ 'longitude' ] : 0;
				$capture_time = isset( $metatdata[ 'captureDate' ] ) ? date("Y-m-dTH:i:s", (int)$metatdata[ 'captureDate' ]) : 0;
				$update_time = $date;
				$battery = '';
				$camera = '';
				$screen_size = '';
				$screen_resolution = '';
				$architecture = '';
				$gps_module = '';
				$fingerprint = '';
				$otg = '';
				$printer = '';
				$country = '';
				if( isset( $metatdata[ 'raceInformation' ][ 'countryOfOrigin' ] ) && $metatdata[ 'raceInformation' ][ 'countryOfOrigin' ] ){
					$country = $metatdata[ 'raceInformation' ][ 'countryOfOrigin' ];
					switch( strtolower( $country ) ){
					case 'nga':
						$country = 'nigeria';
					break;
					}
				}
				$state = '';
				$lga = '';
				$dd = json_encode( $device );

				$ed = 'enrolment_device';
				$al = $this->plugin_instance->load_class( [ 'class' => [ $ed ], 'initialize' => 1 ] );

				$al[ $ed ]->class_settings[ 'current_record_id' ] = $device_id;
				$e = $al[ $ed ]->_get_record();

				$line_items = [];
				if( ! ( isset( $e[ 'id' ] ) && $e[ 'id' ] ) ){

					$_POST[ 'id' ] = '';
					$_POST[ 'tmp' ] = $device_id;

					$line_items = [
						'date' => $date,
						'type' => $type,
						'model' => $model,
						'status' => $status,
						'center' => $center,
						'custodian' => $custodian,
						'purpose' => $purpose,
						'serial_number' => $serial_number,
						'storage' => $storage,
						'os_name' => $os_name,
						'os_version' => $os_version,
						'processor_speed' => $processor_speed,
						'avail_storage' => $avail_storage,
						'ram' => $ram,
						'imsi' => $imsi,
						'imei' => $imei,
						'network_strength' => $network_strength,
						'network_type' => $network_type,
						'latitude' => $latitude,
						'longitude' => $longitude,
						'capture_time' => $capture_time,
						'update_time' => $update_time,
						'battery' => $battery,
						'camera' => $camera,
						'screen_size' => $screen_size,
						'screen_resolution' => $screen_resolution,
						'architecture' => $architecture,
						'gps_module' => $gps_module,
						'fingerprint' => $fingerprint,
						'otg' => $otg,
						'printer' => $printer,
						'country' => $country,
						'state' => $state,
						'lga' => $lga,
						'data' => $dd,
					];

				}else{

					$_POST[ 'id' ] = $device_id;

					$line_items = [
						'custodian' => $custodian,
						'purpose' => $purpose,
						'serial_number' => $serial_number,
						'storage' => $storage,
						'os_name' => $os_name,
						'os_version' => $os_version,
						'processor_speed' => $processor_speed,
						'avail_storage' => $avail_storage,
						'ram' => $ram,
						'imsi' => $imsi,
						'imei' => $imei,
						'network_strength' => $network_strength,
						'network_type' => $network_type,
						'latitude' => $latitude,
						'longitude' => $longitude,
						'capture_time' => $capture_time,
						'update_time' => $update_time,
						'battery' => $battery,
						'camera' => $camera,
						'screen_size' => $screen_size,
						'screen_resolution' => $screen_resolution,
						'architecture' => $architecture,
						'gps_module' => $gps_module,
						'fingerprint' => $fingerprint,
						'otg' => $otg,
						'printer' => $printer,
						'country' => $country,
						'state' => $state,
						'lga' => $lga,
						'data' => $dd,
					];

				}

				$al[ $ed ]->class_settings[ 'update_fields' ] = $line_items;
				$r = $al[ $ed ]->_update_table_field();

				// print_r( $r );exit;

			}
		}

		protected function _createAgent( $agent = [] ){
			if( isset( $agent[ 'agentNin' ] ) && $agent[ 'agentNin' ] ){
				$device_id = md5( $agent[ 'agentNin' ] );

				$date = date("Y-m-dTH:i:s");
				$agentfirstname = isset( $agent[ 'agentFirstName' ] ) ? $agent[ 'agentFirstName' ] : '';
				$agentlastname = isset( $agent[ 'agentLastName' ] ) ? $agent[ 'agentLastName' ] : '';
				$agentemail = isset( $agent[ 'agentEmail' ] ) ? $agent[ 'agentEmail' ] : '';
				$agentnin = isset( $agent[ 'agentNin' ] ) ? $agent[ 'agentNin' ] : '';
				$gender = '';
				$disability_status = '';
				$disability_types = '';
				$partner = '';
				$country = '';
				$state = '';
				$lga = '';

				$ed = 'enrolment_agent';
				$al = $this->plugin_instance->load_class( [ 'class' => [ $ed ], 'initialize' => 1 ] );

				$al[ $ed ]->class_settings[ 'current_record_id' ] = $device_id;
				$e = $al[ $ed ]->_get_record();

				$line_items = [];
				if( ! ( isset( $e[ 'id' ] ) && $e[ 'id' ] ) ){

					$_POST[ 'id' ] = '';
					$_POST[ 'tmp' ] = $device_id;

					$line_items = [
						'date' => $date,
						'agentfirstname' => $agentfirstname,
						'agentlastname' => $agentlastname,
						'agentemail' => $agentemail,
						'agentnin' => $agentnin,
						'gender' => $gender,
						'disability_status' => $disability_status,
						'disability_types' => $disability_types,
						'partner' => $partner,
						'country' => $country,
						'state' => $state,
						'lga' => $lga,
					];

					$al[ $ed ]->class_settings[ 'update_fields' ] = $line_items;
					$r = $al[ $ed ]->_update_table_field();

				}

			}
		}

		protected function _createEnrolment( $json = [], $filename = '' ){
			if( isset( $json[ 'uniqueId' ] ) && $json[ 'uniqueId' ] ){
				$enrol_id = $json[ 'uniqueId' ];

				$date = time();
				$device = isset( $json[ 'deviceInformation' ][ 'deviceId' ] ) ? md5( $json[ 'deviceInformation' ][ 'deviceId' ] ) : '';
				$center = isset( $json[ 'regCenter' ] ) ? $json[ 'regCenter' ] : '';
				$agent = isset( $json[ 'agentInformation' ][ 'agentNin' ] ) ? md5( $json[ 'agentInformation' ][ 'agentNin' ] ) : '';
				$partner = '';
				$endtoendtime = isset( $json[ 'endToEndTime' ] ) ? (double)$json[ 'endToEndTime' ] : '';
				$demotime = isset( $json[ 'demoTime' ] ) ? (double)$json[ 'demoTime' ] : '';
				$pid = isset( $json[ 'pId' ] ) ? $json[ 'pId' ] : '';
				$cid = isset( $json[ 'cId' ] ) ? $json[ 'cId' ] : '';
				$tag = isset( $json[ 'tag' ] ) ? $json[ 'tag' ] : '';
				$uniqueid = isset( $json[ 'uniqueId' ] ) ? $json[ 'uniqueId' ] : '';
				$longitude = isset( $json[ 'longitude' ] ) ? (float)$json[ 'longitude' ] : '';
				$latitude = isset( $json[ 'latitude' ] ) ? (float)$json[ 'latitude' ] : '';
				$networktype = isset( $json[ 'networkType' ] ) ? $json[ 'networkType' ] : '';
				$capturemode = isset( $json[ 'captureMode' ] ) ? $json[ 'captureMode' ] : '';
				$networkstrength = isset( $json[ 'networkStrength' ] ) ? $json[ 'networkStrength' ] : '';
				$capturedate = isset( $json[ 'captureDate' ] ) ? ( (double)$json[ 'captureDate' ] ) / 1000 : '';
				$appname = isset( $json[ 'appName' ] ) ? $json[ 'appName' ] : '';
				$filename = $filename;
				$appid = isset( $json[ 'appId' ] ) ? $json[ 'appId' ] : '';
				$starttime = isset( $json[ 'startTime' ] ) ? ( (double)$json[ 'startTime' ] ) / 1000 : '';
				$endtime = isset( $json[ 'endTime' ] ) ? ( (double)$json[ 'endTime' ] ) / 1000 : '';
				$enrollmentusecase = isset( $json[ 'enrollmentUsecase' ] ) ? $json[ 'enrollmentUsecase' ] : '';
				$regcenter = isset( $json[ 'regCenter' ] ) ? $json[ 'regCenter' ] : '';
				$countryoforigin = isset( $json[ 'raceInformation' ][ 'countryOfOrigin' ] ) ? $json[ 'raceInformation' ][ 'countryOfOrigin' ] : '';
				$nationality = isset( $json[ 'raceInformation' ][ 'nationality' ] ) ? $json[ 'raceInformation' ][ 'nationality' ] : '';
				$locationgenerationtime = isset( $json[ 'locationGenerationTime' ] ) ? strtotime( $json[ 'locationGenerationTime' ] ) : '';
				$fingerattempt = isset( $json[ 'fingerAttempt' ] ) && ! empty( $json[ 'fingerAttempt' ] ) ? '1' : '0';
				$photoattempt = isset( $json[ 'photoAttempt' ] ) && ! empty( $json[ 'photoAttempt' ] ) ? '1' : '0';
				$irisattempt = isset( $json[ 'irisAttempt' ] ) && ! empty( $json[ 'irisAttempt' ] ) ? '1' : '0';
				$demoattempt = isset( $json[ 'demoAttempt' ] ) && ! empty( $json[ 'demoAttempt' ] ) ? '1' : '0';
				$documentattempt = isset( $json[ 'documentAttempt' ] ) && ! empty( $json[ 'documentAttempt' ] ) ? '1' : '0';
				$signatureattempt = isset( $json[ 'signatureAttempt' ] ) && ! empty( $json[ 'signatureAttempt' ] ) ? '1' : '0';
				$previewattempt = isset( $json[ 'previewAttempt' ] ) && ! empty( $json[ 'previewAttempt' ] ) ? '1' : '0';
				$livenessattempt = isset( $json[ 'livenessAttempt' ] ) && ! empty( $json[ 'livenessAttempt' ] ) ? '1' : '0';
				$data = json_encode( $json );

				$ed = 'enrolment_metadata';
				$al = $this->plugin_instance->load_class( [ 'class' => [ $ed ], 'initialize' => 1 ] );

				$line_items = [];

				$line_items = [
					'new_id' => $enrol_id,
					'date' => $date,
					'device' => $device,
					'center' => $center,
					'agent' => $agent,
					'partner' => $partner,
					'endtoendtime' => $endtoendtime,
					'demotime' => $demotime,
					'pid' => $pid,
					'cid' => $cid,
					'tag' => $tag,
					'uniqueid' => $uniqueid,
					'longitude' => $longitude,
					'latitude' => $latitude,
					'networktype' => $networktype,
					'capturemode' => $capturemode,
					'networkstrength' => $networkstrength,
					'capturedate' => $capturedate,
					'appname' => $appname,
					'filename' => $filename,
					'appid' => $appid,
					'starttime' => $starttime,
					'endtime' => $endtime,
					'enrollmentusecase' => $enrollmentusecase,
					'regcenter' => $regcenter,
					'countryoforigin' => $countryoforigin,
					'nationality' => $nationality,
					'locationgenerationtime' => $locationgenerationtime,
					'fingerattempt' => $fingerattempt,
					'photoattempt' => $photoattempt,
					'irisattempt' => $irisattempt,
					'demoattempt' => $demoattempt,
					'documentattempt' => $documentattempt,
					'signatureattempt' => $signatureattempt,
					'previewattempt' => $previewattempt,
					'livenessattempt' => $livenessattempt,
					'data' => $data,
				];

				$al[ $ed ]->class_settings[ 'line_items' ] = [ $line_items ];
				$al[ $ed ]->class_settings[ 'use_replace' ] = 1;
				$r = $al[ $ed ]->_save_line_items();

			}
		}

		protected function _createHeartbeats( $json = [] ){

		    $hbs = [
		    	"fingerAttempt" => 'enrolment_fingerprint',
		    	"photoAttempt" => 'enrolment_photo',
		    	"demoAttempt" => 'enrolment_demographic',
		    	"irisAttempt" => 'enrolment_iris',
		    	"documentAttempt" => 'enrolment_document',
		    	"signatureAttempt" => 'enrolment_signature',
		    	"previewAttempt" => 'enrolment_preview',
		    	"livenessAttempt" => 'enrolment_liveness',
		    ];

		    $al = $this->plugin_instance->load_class( [ 'class' => [ "enrolment_fingerprint","enrolment_photo","enrolment_demographic","enrolment_iris","enrolment_document","enrolment_signature","enrolment_preview","enrolment_liveness" ], 'initialize' => 1 ] );

			if( isset( $json[ 'uniqueId' ] ) && $json[ 'uniqueId' ] ){

				$date = time();
				$enrol_id = isset( $json[ 'uniqueId' ] ) ? $json[ 'uniqueId' ] : '';
				$center = isset( $json[ 'regCenter' ] ) ? md5( $json[ 'regCenter' ] ) : '';
				$agent = isset( $json[ 'agentInformation' ][ 'agentNin' ] ) ? md5( $json[ 'agentInformation' ][ 'agentNin' ] ) : '';
				$device = isset( $json[ 'deviceInformation' ][ 'deviceId' ] ) ? md5( $json[ 'deviceInformation' ][ 'deviceId' ] ) : '';
				$partner = '';
				$device_model = isset( $json[ 'deviceInformation' ][ 'model' ] ) ? $json[ 'deviceInformation' ][ 'model' ] : '';
				$app_version = isset( $json[ 'deviceInformation' ][ 'appVersion' ] ) ? $json[ 'deviceInformation' ][ 'appVersion' ] : '';
				$os = isset( $json[ 'deviceInformation' ][ 'osName' ] ) ? $json[ 'deviceInformation' ][ 'osName' ] : '';
				$os_version = isset( $json[ 'deviceInformation' ][ 'osVersion' ] ) ? $json[ 'deviceInformation' ][ 'osVersion' ] : '';
				$longitude = isset( $json[ 'longitude' ] ) ? (float)$json[ 'longitude' ] : '';
				$latitude = isset( $json[ 'latitude' ] ) ? (float)$json[ 'latitude' ] : '';
				$app_name = isset( $json[ 'appName' ] ) ? $json[ 'appName' ] : '';
				$app_id = isset( $json[ 'appId' ] ) ? $json[ 'appId' ] : '';

				$country = '';
				if( isset( $json[ 'raceInformation' ][ 'countryOfOrigin' ] ) && $json[ 'raceInformation' ][ 'countryOfOrigin' ] ){
					$country = $json[ 'raceInformation' ][ 'countryOfOrigin' ];
					switch( strtolower( $country ) ){
					case 'nga':
						$country = 'nigeria';
					break;
					}
				}
				$state = '';
				$lga = '';
				$data = '';

				foreach( $hbs as $key => $tb ){
					if( isset( $al[ $tb ]->class_settings ) && $al[ $tb ]->class_settings && isset( $json[ $key ][ 'attemptBreakdowns' ] ) && ! empty( $json[ $key ][ 'attemptBreakdowns' ] ) ){
						$line_items = [];
						$line_item = [
							'date' => $date,
							'enrol_id' => $enrol_id,
							'partner' => $partner,
							'center' => $center,
							'agent' => $agent,
							'device' => $device,
							'partner' => $partner,
							'device_model' => $device_model,
							'app_version' => $app_version,
							'os' => $os,
							'os_version' => $os_version,
							'longitude' => $longitude,
							'latitude' => $latitude,
							'app_name' => $app_name,
							'app_id' => $app_id,
							'country' => $country,
							'state' => $state,
							'lga' => $lga,
							'data' => $data,
						];

						$line_item[ 'attempts' ] = isset( $json[ $key ][ 'numberOfAttempts' ] ) ? $json[ $key ][ 'numberOfAttempts' ] : '';
						$line_item[ 'overall_duration' ] = isset( $json[ $key ][ 'duration' ] ) ? $json[ $key ][ 'duration' ] : '';
						$line_item[ 'overall_threshold_version' ] = isset( $json[ $key ][ 'thresholdVersion' ] ) ? $json[ $key ][ 'thresholdVersion' ] : '';


						foreach( $json[ $key ][ 'attemptBreakdowns' ] as $vb ){

							$line_item[ 'name' ] = isset( $vb[ 'name' ] ) ? $vb[ 'name' ] : '';
							// $line_item[ 'device' ] = isset( $vb[ 'device' ] ) ? md5( $vb[ 'device' ] ) : '';
							$line_item[ 'status' ] = isset( $vb[ 'status' ] ) ? $vb[ 'status' ] : '';
							$line_item[ 'duration' ] = isset( $vb[ 'duration' ] ) ? $vb[ 'duration' ] : '';
							$line_item[ 'threshold_type' ] = isset( $vb[ 'thresholdType' ] ) ? $vb[ 'thresholdType' ] : '';
							$line_item[ 'start_time' ] = isset( $vb[ 'startTime' ] ) && $vb[ 'startTime' ] ? strtotime( $vb[ 'startTime' ] ) : '';
							$line_item[ 'end_time' ] = isset( $vb[ 'endTime' ] ) && $vb[ 'endTime' ] ? strtotime( $vb[ 'endTime' ] ) : '';
							$line_item[ 'acquisition_mode' ] = isset( $vb[ 'acquisitionMode' ] ) ? $vb[ 'acquisitionMode' ] : '';
							$line_item[ 'acquisition_duration' ] = isset( $vb[ 'acquisitionDuration' ] ) ? $vb[ 'acquisitionDuration' ] : '';
							$line_item[ 'threshold_version' ] = isset( $vb[ 'thresholdVersion' ] ) ? $vb[ 'thresholdVersion' ] : '';
							$line_item[ 'passive_liveness_result' ] = isset( $vb[ 'passiveLivenessResult' ] ) ? $vb[ 'passiveLivenessResult' ] : '';


							if( isset( $vb[ 'results' ] ) && $vb[ 'results' ] ){
								foreach( $vb[ 'results' ] as $vb2 ){
									$line_item[ 'value' ] = isset( $vb2[ 'value' ] ) ? $vb2[ 'value' ] : '';
									$line_item[ 'property' ] = isset( $vb2[ 'property' ] ) ? $vb2[ 'property' ] : '';

									$line_items[] = $line_item;
								}
							}else{
								$line_items[] = $line_item;
							}
						}

						$al[ $tb ]->class_settings[ 'line_items' ] = $line_items;
						$al[ $tb ]->_save_line_items();

					}
				}

			}
		}

		protected function _view_details2(){
			
			$filename = '';
				
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$error_msg = '';
			$hide_buttons = isset( $_GET[ 'hide_buttons' ] ) ? $_GET[ 'hide_buttons' ] : '';
			$e_type = 'error';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg = 'Invalid Reference';
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
			case 'view_details2':
				if( ! $error_msg ){
					$this->class_settings["current_record_id"] = $_POST[ 'id' ];
					$e = $this->_get_record();
					if( isset( $e["id"] ) ){
						
					}else{
						$error_msg = 'Unable to retrieve patient details';
					}
				}
			break;
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
				if( ! $error_msg ){
					$opt = array();
					if( isset( $_GET[ 'click_btn' ] ) && $_GET[ 'click_btn' ] )$opt[ 'click_btn' ] = $_GET[ 'click_btn' ];
					$id = $e['id'];
					$opt[ 'id' ] = $id;

					$ak = 'heartbeatas';
					$opt[ 'action_groups' ][ $ak ]['title'] = 'Manage Heartbeats';
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_fingerprint&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Fingerprint',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_photo&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Photo',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_liveness&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Liveness',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_signature&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Signature',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_iris&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Iris',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_demographic&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Demographic',
					);
					
					$opt[ 'action_groups' ][ $ak ][ 'actions' ][] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_preview&nwp_todo=display_all_records_frontend&hide_title=1&disable_btn=1&enrol_id='.$e[ 'id' ],
						'title' => 'Enrolment Preview',
					);
					
					$opt[ 'general_actions' ][ 'view_device' ] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_device&nwp_todo=view_details',
						'title' => 'View Device Details',
						'custom_id' => $e[ 'device' ],
					);

					$opt[ 'general_actions' ][ 'view_agent' ] = array(
						'action' => $this->plugin,
						'todo' => 'execute&nwp_action=enrolment_agent&nwp_todo=view_details',
						'title' => 'View Agent Details',
						'custom_id' => $e[ 'agent' ],
					);

					$opt[ 'hide_files' ] = 1;
					// $opt[ 'hide_comments' ] = 1;
					
					$opt[ 'plugin' ] = $this->plugin;
					$opt[ 'details_todo' ] = 'view_details';
					$opt[ 'title_text' ] = "Enrolment Data <b>" . $e[ 'uniqueid' ] . '</b>';
					$_GET[ 'modal' ] = 1;

					$this->class_settings[ 'open_module' ] = $opt;

					//set_current_customer( array( "customer" => $id ) );
					return $this->_open_module();
				}
			break;
			case 'view_details2':
				$_GET["modal"] = 1;
				$filename = "view-details.php";
					
				if( ! $error_msg ){
					if( isset( $e["id"] ) ){
						$this->_apply_basic_data_control( $e );
						
						/* $stb = array();
						if( isset( $e["status2"] ) ){
							switch( $e["status2"] ){
							case "sent_to_nurse":
								$stb = array( 'an_vi' => 1 );
							break;
							}
						}
						if( empty( $stb ) ){
							$stb = array( 'of' => 1 );
						} 
						
						$this->class_settings[ 'data' ][ 'standalone_buttons' ] = $stb;
						*/
						
						//$this->class_settings[ 'data' ][ 'more_data' ][ 'additional_params' ] = '&reference=' . $e["id"] . '&reference_table=' . $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
						$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
						$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
						
						$b["more_actions"] = $this->_get_html_view();
						$b["id"] = $e["id"];
						//$b["auth_data"] = $qd;
						
						$this->class_settings["data_items"] = array( $e["id"] => $e );
						$this->class_settings["other_params"] = $b;
					}else{
						$error_msg = 'Unable to Retrieve Record';
					}
				}
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			if( $hide_buttons )$this->class_settings[ 'data' ][ 'hide_buttons' ] = $hide_buttons;
			
			$this->class_settings[ 'data' ][ 'no_columns' ] = isset( $_GET["no_column"] )?$_GET["no_column"]:'';
			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
				if( $filename ){
					$this->class_settings["html_filename"] = array( $this->view_path . $filename );
				}
			}else{
				if( $filename ){
					$this->class_settings["html_filename"] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
			}
			
			$this->class_settings["modal_dialog_style"] = "width:40%;";
				
			$return = $this->_view_details();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_details2':
				if( isset( $return["html_replacement_selector"] ) && $return["html_replacement_selector"] ){
					$return["html_replacement_selector"] = $handle;
				}
			break;
			}
			
			return $return;
		}
		
		protected function _search_form2(){
			$where = "";
			
			foreach( array( 'customer', 'staff_responsible' ) as $key ){
				if( isset( $this->table_fields[ $key ] ) && isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
					//$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
					$where .= " AND `".$this->table_fields[ $key ]."` = '".$_POST[ $this->table_fields[ $key ] ]."' ";
				}
			}
			
			$key = 'date';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] ] , 1 );
				$where .= " AND `".$this->table_fields[ $key ]."` >= " . $this->class_settings[ $key ];
			}
			
			$key = 'date';
			$skey = "_range";

			if( isset( $_POST[ $this->table_fields[ $key ] . $skey ] ) && $_POST[ $this->table_fields[ $key ] . $skey ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] . $skey ] , 2 );

				$where .= " AND `".$this->table_fields[ $key ]."` <= " . $this->class_settings[ $key ];
			}
			
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}

		protected function _display_all_records_full_view2(){
			$where = '';
			$join = '';
			$group = '';
			$disable_btn = isset( $_GET[ 'disable_btn' ] ) ? $_GET[ 'disable_btn' ] : '';
			$spilt_screen = 0;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			$join_from = isset( $_GET[ 'join_from' ] ) ? $_GET[ 'join_from' ] : '';
			
			switch( $action_to_perform ){
			case "display_all_records_full_view_search":
			case "display_all_records_frontend_history":
				$show_form = 1;
				unset( $this->class_settings[ "full_table" ] );
			break;
			case "display_all_records_frontend":
				$spilt_screen = 0;
			break;
			case "display_all_records_others":
				$spilt_screen = 0;

				if( $join_from && $id ){

					$key = $join_from;

					$ed = 'enrolment_'.$key;
					$al = $this->plugin_instance->load_class( [ 'class' => [ $ed ], 'initialize' => 1 ] );

					$join = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $ed ."` ON `". $ed ."`.`id` = `". $this->table_name ."`.`". $this->table_fields[ $key ] ."` AND `". $ed ."`.`record_status` = '1' AND `". $ed ."`.`id` = '". $id ."' ";

					$this->class_settings['prefix_title'] = get_name_of_referenced_record( array( 'id' => $id, 'table' => 'enrolment_'.$key ) ) . ' - ';

					// $group = " GROUP BY `". $this->table_name ."`.`id` ";
					// $this->label = 'Yoooooooooo';

					$this->class_settings[ 'action_to_perform' ] = 'display_all_records_frontend';
				}

			break;
			}

			if( $join ){
				$this->class_settings[ 'filter' ][ 'join' ] = $join;
				$this->class_settings[ 'filter' ][ 'join_count' ] = $join;
			}
			
			if( $group ){
				$this->class_settings[ 'filter' ][ 'group' ] = $group;
				$this->class_settings[ 'filter' ][ 'group_count' ] = $group;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 0;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				unset( $this->basic_data['more_actions'] );
			}
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_history":
				$this->datatable_settings[ "show_edit_button" ] = 1;
				unset( $this->basic_data['more_actions'] );
			break;
			}
			
			if( $show_form ){
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
				$this->class_settings[ 'add_empty_select_option' ] = 1;
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "date":
						$this->class_settings["form_values_important"][ $val ] = date("U");
						$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					case "staff_responsible":
					case "customer":
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					default:
						$this->class_settings["hidden_records"][$val] = 1;
					break;
					}
				}
			}

			if( $spilt_screen ){
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;
				
				$this->datatable_settings["split_screen"] = array();
				
				$this->datatable_settings["datatable_split_screen"] = array(
					'action' => '?action='. $this->plugin .'&todo=execute&nwp_action=' . $this->table_name . '&nwp_todo=view_details2&no_column=1',
					'col' => 4,
					'content' => get_quick_view_default_message_settings(),
				);
			}
			
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2(){
			
			$ddx = array();
			$error = '';
			$e = array();
			$etype = 'error';
			$save_app = 1;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'save_app_changes':
			break;
			}
			
			if( $error ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error ) );
			}
			
			switch( $save_app ){
			case 1:
				$return = $this->_save_app_changes();
			break;
			case 2:
				$return = $this->_save_changes();
			break;
			case 3:
				$return = $this->_update_table_field();
			break;
			case 4:
				$return = $this->_save_line_items();
				if( $return )$return = array( 'saved_record_id' => 1 );
			break;
			}
			
			// print_r( $return );exit;
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = "#dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				switch( $action_to_perform ){
				case 'save_app_changes':
				break;
				}
				
			}
			
			// Send notification to assigned persons
			switch( $action_to_perform ){
			case 'save_new_popup':
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$action_to_perform = $this->class_settings['action_to_perform'];
			//$this->class_settings["skip_link"] = 1;
			
			switch ( $action_to_perform ){
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
			break;
			default:
			break;
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		
		protected function _apply_basic_data_control( $e = array() ){
			/* if( ! isset( $e["status"] ) ){
				return;
			} */
			
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
			
			/* 
			$show_cancel = 0;
			switch( $e["status"] ){
			case "cancelled":
			case "in_active":
				$show_cancel = 1;
			break;
			}

			if( ! $show_cancel ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1'] );
			}
			 */
			 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),
		);
	}
	
	function enrolment_metadata(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/enrolment_metadata.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/enrolment_metadata.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){

					$key = $return[ "fields" ][ "device" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_device',
						'reference_plugin' => 'nwp_enrolment_data',
						'reference_keys' => array( 'model' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_device&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "agent" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_agent',
						'reference_plugin' => 'nwp_enrolment_data',
						'reference_keys' => array( 'agentfirstname', 'agentlastname' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_agent&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "center" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'enrolment_center',
						'reference_plugin' => 'nwp_enrolment_data',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_center&nwp_todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
				}
				return $return[ "labels" ];
			}
		}
	}
?>
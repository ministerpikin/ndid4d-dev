<?php
	/**
	 * users Class
	 *
	 * @used in  				users Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	users
	 */
	
	class cUsers extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $build_list = 1;
		public $build_list_condition = '';
		public $build_list_field = 'firstname';
		public $build_list_field1 = 'lastname';
		
		public $customer_source = '';
		public $default_reference = '';
		public $table_name = 'users';
		
		public $end_point1 = '';
		public $app_key = '';
		public $app_id = '';
		public $end_point_dev = ''; //php 8.2 deprecated error
		public $widget_key = ''; //php 8.2 deprecated error
		
		public $label = 'Users Manager';
		
		private $associated_cache_keys = array(
			'users',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $prevent_duplicate = array(
			'condition' => 'OR',
			'field_settings' => array( 'username' => array( 'skip_if_empty' => 1 ), 'email' => array( 'skip_if_empty' => 1 ) ),
			'fields' => array( 'email', 'username' ),
			'error_title' => 'Duplicate User',
			'error_message' => 'There is an existing user account:',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,	//(add, eidt, view, delete, export) will be available in access control
			//'exclude_from_crud' => array(),
			'special_actions' => array(
				'nvs_api' => array(
					'todo' => 'nvs_api',
					'type' => '',
					'title' => 'View NVS Response'
				)
			),
			'more_actions' => array(
				'password' => array(
					'todo' => 'edit_password',
					'title' => 'Change Password',
					'text' => 'Change Password',
				),
				'passphrase' => array(
					'todo' => 'edit_passphrase',
					'title' => 'Change Passphrase',
					'text' => 'Change Passphrase',
				),
				'activate_acc' => array(
					'multiple' => 1,
					'todo' => 'activate_account',
					'title' => 'Activate Account(s)',
					'text' => 'Activate Account(s)',
				),
				'actions' => array(
					'title' => 'More',
					'data' => array(
						'd1' => array(
							'lock' => array(
								'multiple' => 1,
								'todo' => 'lock_account',
								'title' => 'Lock Account(s)',
								'text' => 'Lock Account(s)',
							),
							'unlock' => array(
								'multiple' => 1,
								'todo' => 'unlock_account',
								'title' => 'Unlock Account(s)',
								'text' => 'Unlock Account(s)',
							),
							'sotp' => array(
								'todo' => 'show_otp',
								'title' => 'Show OTP',
								'text' => 'Show OTP',
							),
						),
					),
				),
			),
		);
		
        public $table_fields = array();
        public $old_table_fields = array(
			'email' => 'users004',
			'other_names' => 'users003',
			
			'firstname' => 'users001',
			'lastname' => 'users002',
			
			'password' => 'users006',
			'confirmpassword' => 'users007',
			'oldpassword' => 'users008',
			
			'phone_number' => 'users005',
            
			// 'category' => 'users040',	//new
			
			// 'type' => 'users046',	//new 1.1
			'division' => 'users041',	//new 1.1
			
			'department' => 'users010',
			// 'grade_level' => 'users020',
			// 'rank' => 'users027',
			// 'rank_text' => 'users045',	//new 1.1
			
			'date_of_birth' => 'users011',
			'sex' => 'users012',
			'address' => 'users013',
			
			// 'means_of_identification' => 'users042',	//new 1.1
			// 'other_identification' => 'users043',	//new 1.1
			// 'identification_number' => 'users044',	//new 1.1
			
			// 'date_employed' => 'users014',
			// 'date_of_confirmation' => 'users028',
			
			'ref_no' => 'users015',
			// 'serial_number' => 'users023',
			// 'file_number' => 'users029',
			// 'account_number' => 'users016',
			// 'bank_name' => 'users017',
            // 'pfa' => 'users021',
            // 'pfa_pin' => 'users033',
			
			// 'tin' => 'users022',
			// 'tax_office_location' => 'users037',
			// 'housing_scheme' => 'users038',
			// 'housing_scheme_number' => 'users039',
			
			// 'health_insurance' => 'users034',
			// 'health_pin' => 'users035',
			
			'photograph' => 'users018',
			// 'push_notification_id' => 'users019',
            
			'role' => 'users009',
			
			// 'nationality' => 'users024',
			'state' => 'users025',
			// 'city' => 'users026',
			
			// 'qualification' => 'users030',
			'status' => 'users031',
			// 'reason' => 'users032',
		);
        
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
				'utility_buttons' => array(
					// 'comments' => 1,
					//'share' => 1,
					// 'attach_file' => 1,
					// 'tags' => 1,
					// 'view_details' => 1,
					'revision_history' => 1,
					// 'get_collaborators' => 1,
				),
				
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
			'hide_signatories' => 1             //Determines whether or not to enable the Signatory portions of the export component
		);


		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/users.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/users.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
			switch( get_package_option() ){
			case "catholic":
				$k = 'users047';
				$this->branch_field = "parish";
				$this->table_fields["parish"] = $k;
			break;
			case "essential":
				$this->datatable_settings[ 'show_add_new' ] = 0;
				$this->datatable_settings[ 'show_delete_button' ] = 0;
			break;
			}

			if( defined( 'NWP_V3_ENDPOINT' ) && NWP_V3_ENDPOINT ){
				$this->end_point1 = NWP_V3_ENDPOINT;
				$this->end_point_dev = NWP_V3_ENDPOINT;
			}

			$this->app_key = get_websalter();
			$this->widget_key = $this->app_key;
			if( defined( 'HYELLA_V3_TELE_HEALTH_APP_SECRET' ) && HYELLA_V3_TELE_HEALTH_APP_SECRET ){
				$this->app_key = HYELLA_V3_TELE_HEALTH_APP_SECRET;
			}
			$this->app_key = md5( $this->app_key );

			if( defined( 'HYELLA_V3_TELE_HEALTH_APP_ID' ) && HYELLA_V3_TELE_HEALTH_APP_ID ){
				$this->app_id = HYELLA_V3_TELE_HEALTH_APP_ID;
			}
			
			if( ! ( defined( 'HYELLA_V3_OTP_ON_SIGNIN' ) && HYELLA_V3_OTP_ON_SIGNIN ) ){
				unset( $this->basic_data[ 'more_actions' ][ 'actions' ][ 'data' ][ 'd1' ][ 'sotp' ] );
			}
		}
	
		function users(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_passphrase':
			case 'edit_password':
			case 'create_new_record':
			case 'edit':
			case 'edit_my_profile':
			case 'edit_my_password':
			case 'display_my_password_change':
				$returned_value = $this->_generate_new_data_capture_form2();
			break;
			case 'display_all_records':
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records2();
			break;
			case 'save':
				$returned_value = $this->_save_changes2();
			break;
			case 'display':
				$returned_value = $this->_display();
			break;
			case 'users_registration':
				$returned_value = $this->_users_registration_process();
			break;
			case 'verify_email_address':
				$returned_value = $this->_verify_email_address();
			break;
			case 'display_user_details':
				$returned_value = $this->_display_user_details();
			break;
			case 'change_user_password':
				$returned_value = $this->_change_user_password();
			break;
			case 'get_all_users_details':
				$returned_value = $this->_get_all_users_details();
			break;
			case 'get_user_details':
				$returned_value = $this->_get_customer_call_log();
			break;
			case 'authenticate_employee':
				$returned_value = $this->_authenticate_employee();
			break;
			case "display_all_employees_records_front":
			case "display_all_employees_records_inactive_front":
			case "display_all_employees_records_inactive":
			case "display_all_employees_records":
			case "view_nominal_roll_front":
			case "view_nominal_roll":
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'view_my_profile_dashboard':
			case 'view_my_profile':
			case 'display_my_profile_manager':
			case 'edit_my_signature':
				$returned_value = $this->_display_my_profile_manager();
			break;
			case 'save_update_profile':
				$returned_value = $this->_save_update_profile();
			break;
			case 'get_all_admin_users':
				$returned_value = $this->_get_all_admin_users();
			break;
			case 'save_signatures':
			case 'capture_signature':
			case 'capture_capture_signature':
				$returned_value = $this->_capture_signature2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
				
				$this->class_settings["return_data"] = 0;
				$this->_get_all_users_details();
			break;
			case 'display_app_manager_users':
			case 'display_app_manager_personnel':
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_edit_popup_form_status':
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'authenticate_user':
			case 'authenticate_app_mobile':
			case 'authenticate_app':
				$returned_value = $this->_authenticate_app();
			break;
			case 'app_check_logged_in_user4':
			case 'app_check_logged_in_user3':
			case 'app_check_logged_in_user2':
			case 'app_check_logged_in_user':
			case 'app_check_logged_in_user5':
				$returned_value = $this->_app_check_logged_in_user();
			break;
			case 'new_user_popup':
			case 'new_personnel_popup':
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form_status':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list':
			case 'search_list2':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log2();
			break;
			case "view_details2":
				$_GET["modal"] = 1;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "save_change_user_password":
			case "save_change_my_password":
			case "save_change_my_profile":
				$returned_value = $this->_save_change_user_password();
			break;
			case "get_select2":
			case "get_users_select2":
				$returned_value = $this->_get_users_select2();
			break;
			case "authenticate_passphrase":
				$returned_value = $this->_authenticate_passphrase();
			break;
			case "refresh_all_staff_customer_details":
				$returned_value = $this->_refresh_all_staff_customer_details();
			break;
			case "update_fields":
				$returned_value = $this->_update_table_field();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case "load_image_capture":
				$returned_value = $this->_load_image_capture();
			break;
			case "save_captured_image":
			case "save_captured_signature":
				$returned_value = $this->_save_captured_image();
			break;
			case "save_line_items":
				$returned_value = $this->_save_line_items();
			break;
			case "get_email_of_users_based_on_capability":
				$returned_value = $this->_get_email_of_users_based_on_capability();
			break;
			case "sign_out":
				$returned_value = $this->_sign_out();
			break;
			case "store_extracted_line_items_data":
				$returned_value = $this->_store_extracted_line_items_data();
			break;
			case "get_record":
				$returned_value = $this->_get_record();
			break;
			case "unlock_account":
			case "lock_account":
			case "activate_account":
				$returned_value = $this->_lock_account();
			break;
			case "migrate_old_users":
				$returned_value = $this->_migrate_old_users();
			break;
			case "view_quick_report":
				$returned_value = $this->_view_quick_report();
			break;
			case "search_birthdays":
				$returned_value = $this->_search_birthdays();
			break;
			case "activate_user_account":
				$returned_value = $this->_activate_user_account();
			break;
			case "verify_otp":
				$returned_value = $this->_verify_otp();
			break;
			case "resend_otp":
				$returned_value = $this->_resend_otp();
			break;
			case "x":
				$returned_value = $this->_x();
			break;
			case 'show_otp':
				$returned_value = $this->_custom_capture_form_version_2();
			break;
			case 'test_get_otp':
				$returned_value = $this->_test_cases_data();
			break;
			}
			
			return $returned_value;
		}

		protected function _test_cases_data(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			switch ($action_to_perform) {
				case 'test_get_otp':
					if( isset($_POST['email']) && $_POST['email'] ){
						$this->class_settings['where'] = " AND `". $this->table_fields['email'] ."` = '". $_POST['email'] ."' ";
						$esx = $this->_get_records();
						$esx = isset($esx[0]) && $esx[0] ? $esx[0] : [];
						if( $esx ){
							return $esx['data'];
						}
					}
				break;
			}

			return [ 'data' => [] ];
		}
		
		protected function _custom_capture_form_version_2(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = 'validate-bill-and-finalize-discharge';
			
			$modal = 0;
			$params = '';
			$html = '';
			$close_modal = '';
			$error = '';
			$etype = 'error';
			$error_container = '';
			$manual_close = 0;

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			
			$data = array();
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'show_otp':
				$filename = 'generate-report';
				$modal = 1;

				if( $id ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$e = $this->_get_record();

					if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
						$ed = $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];

						if( isset( $ed[ 'otp' ][ 'value' ] ) && $ed[ 'otp' ][ 'value' ] ){
							$exp = 0;
							if( isset( $ed[ 'otp' ][ 'date' ] ) && $ed[ 'otp' ][ 'date' ] && defined( 'HYELLA_V3_OTP_EXPIRATION_VALIDITY' ) && HYELLA_V3_OTP_EXPIRATION_VALIDITY ){
								$exp = HYELLA_V3_OTP_EXPIRATION_VALIDITY * 60; //convert to seconds
								$exp += doubleval( $ed[ 'otp' ][ 'date' ] );
							}
							// print_r( $exp );exit;
							$error = '<h4>OTP: <strong>'. $ed[ 'otp' ][ 'value' ] .'</strong></h4>';
							if( $exp ){
								if( $exp > time() ){
									$error .= 'Will expire in '.get_time_ago( time(), $exp );
								}else{
									$error .= 'Expired '.get_time_ago( $exp ) . ' ago';
								}
							}
							$etype = 'info';
							$manual_close = 1;
						}else{
							$error = '<h4><strong>No OTP Found</strong></h4>No OTP was found for this user';
							$etype = 'warning';
						}
					}else{
						$error = '<h4><strong>Invalid User Reference</strong></h4>';
					}

				}else{
					$error = '<h4><strong>Undefined User Reference</strong></h4>';
				}
			break;
			}

			if( $error ){
				$ex = array( "type" => $etype, "message" => $error );
				if( $error_container ){
					$ex[ 'do_not_display' ] = 1;
					$ex[ 'html_replacement_selector' ] = $error_container;
				}
				if( $close_modal ){
					$ex[ 'callback' ][] = array( $close_modal );
				}
				if( $manual_close ){
					$ex[ 'manual_close' ] = 1;
				}
				return $this->_display_notification( $ex );
			}

			$data[ 'params' ] = '&html_replacement_selector=' . $handle . $params;
							// print_r( $html );exit;
				
			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( $this->view_path.'/'.$filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			if( $modal ){
				return $this->_launch_popup( $html, "#" . $handle, array( 'set_function_click_event', 'prepare_new_record_form_new' ) );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
			
			if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
				// $return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
				// $return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
			}
			return $return;
		}

		private function _x(){
			$tb = 'es_monitoring';

			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => " SELECT * FROM `". $tb ."` ",
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $tb ),
			);
			$old = execute_sql_query($query_settings);


			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => array(
					'values' => $old
				),
				'db_mode' => 'elasticsearch',
				'query_type' => 'INSERT',
				'set_memcache' => 1,
				'tables' => array( $tb ),
			);
			$old = execute_sql_query($query_settings);

			
			print_r( $old );exit;
		}

		private function _delete_records2(){
			$r = $this->_delete_records();
			if( isset( $r[ "deleted_records" ] ) && is_array( $r[ "deleted_records" ] ) && ! empty( $r[ "deleted_records" ] ) ){

				if( class_exists( 'cLogged_in_users' ) ){
					$lg = new cLogged_in_users();
					$lg->class_settings = $this->class_settings;
					
					foreach( $r[ 'deleted_records' ] as $rk => $rv ){
						$lg->_update_log( array( 'action' => 'account_deleted', 'user_id' => $rk ) );
					}
				}

			}
			return $r;
		}

		private function _resend_otp( $opt = [] ){
			$error = '';
			$etype = 'error';
			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			$id = isset( $opt[ 'id' ] ) ? $opt[ 'id' ] : $id;
			$js = [];

			if( $id ){
				$this->class_settings[ 'current_record_id' ] = $id;
				$ee = $this->_get_record();
				// print_r( $e );exit;

				if( isset( $ee[ 'id' ] ) && $ee[ 'id' ] ){
					$otp_val = generatePassword(6,1,0,1,0);

					$ed = isset( $ee[ 'data' ] ) ? json_decode( $ee[ 'data' ], 1 ) : [];
					$ed[ 'otp' ] = [
						'value' => $otp_val,
						'date' => time(),
						'status' => 'unused',
					];

					switch( $this->class_settings[ 'action_to_perform' ] ){
					case 'resend_otp':
					break;
					default:
						if( defined( 'OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT' ) && OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT ){
							if( isset( $opt[ 'd1' ] ) && $opt[ 'd1' ] ){
								$ed[ 'otp' ][ 'device_id' ] = $opt[ 'd1' ];
							}else{
								//disabled until fingerprint js fix is applied
								//$error = '<h4><strong>Missing Authentication Parameter</strong></h4>Please contact your system administrator';
							}
						}
					break;
					}

					if( ! $error ){
						$query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`". $this->table_fields[ 'data' ] ."` = '". json_encode( $ed ) ."', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE `".$this->table_name."`.`id` = '" . $ee[ 'id' ] . "' AND `".$this->table_name."`.`record_status`='1'";
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'UPDATE',
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						
						$save = execute_sql_query($query_settings);
						
						if( class_exists( 'cLogged_in_users' ) ){
							$lg = new cLogged_in_users();
							$lg->class_settings = $this->class_settings;
							
							$lg->_update_log( array( 'action' => 'otp_generation', 'user_id' => $ee[ 'id' ] ) );
						}

						$this->class_settings[ 'cache_where' ] = " AND id = '". $ee[ 'id' ] ."' ";
						$this->class_settings[ 'current_record_id' ] = 'pass_condition';
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						
						$this->_get_record();

						if( defined( 'HYELLA_V3_OTP_EXPIRATION_VALIDITY' ) && HYELLA_V3_OTP_EXPIRATION_VALIDITY ){
							$not = '<br><strong>NOTE:</strong> This OTP is only valid for '.HYELLA_V3_OTP_EXPIRATION_VALIDITY.' min(s)';
						}

						$this->_send_email_notification( array(
							"subject" => "OTP Confirmation",
							"content" => "Use this otp to sign into the M&E MIS".$not,
							"info" => '<br />Your OTP is: <b>'. $otp_val .'</b>',
							"single_email"  => $ee[ 'email' ],
							"single_name" => $ee[ 'firstname' ] . ' ' . $ee[ 'lastname' ]
						) );

						$error = '<h4><strong>New OTP Generated</strong></h4>Please check your email for the new code';
						$etype = 'success';
						$js[] = 'nwOTPForm.init';
					}

				}else{
					$error = '<h4><strong>Invalid User Reference</strong></h4>Please contact your system administrator';
				}
			}else{
				$error = '<h4><strong>Undefined User Reference</strong></h4>Please contact your system administrator';
			}

			if( ! $error ){
				$error = '<h4><strong>Unknown Error</strong></h4>Please contact your system administrator';
			}

			return $this->_display_notification( array( "type" => $etype, "message" => $error, 'callback' => $js ) );

		}

		private function _verify_otp(){
			$error = '';
			$etype = 'error';
			$fparams = isset( $_POST[ 'formParams' ] ) ? json_decode( $_POST[ 'formParams' ], 1 ) : [];
			$otp = isset( $_POST[ 'otp' ] ) ? $_POST[ 'otp' ] : '';

			if( $otp ){
				if( isset( $fparams[ 'id' ] ) && $fparams[ 'id' ] ){
					$id = $fparams[ 'id' ];

					$this->class_settings[ 'current_record_id' ] = $id;
					$e = $this->_get_record();
					// print_r( $e );exit;

					if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
						$dd = $e[ 'data' ] ? json_decode( $e[ 'data' ], 1 ) : [];

						if( isset( $dd[ 'otp' ][ 'value' ] ) && $dd[ 'otp' ][ 'value' ] ){
							if( trim( $dd[ 'otp' ][ 'value' ] ) === trim( $otp ) ){

								if( defined( 'HYELLA_V3_OTP_EXPIRATION_VALIDITY' ) && HYELLA_V3_OTP_EXPIRATION_VALIDITY ){
									$dt = HYELLA_V3_OTP_EXPIRATION_VALIDITY * 60;
									$dt += doubleval( $dd[ 'otp' ][ 'date' ] );

									if( $dt < time() ){
										$error = '<h4><strong>OTP Expired</strong></h4>Please resend a new otp';
									}
								}

								if( ! $error ){
									$dd[ 'otp' ][ 'status' ] = 'used';
									$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `". $this->table_fields[ 'data' ] ."` = '". json_encode( $dd ) ."' WHERE `".$this->table_name."`.`record_status` = '1' AND id = '". $id ."' ";
									
									$query_settings = array(
										'database' => $this->class_settings['database_name'] ,
										'connect' => $this->class_settings['database_connection'] ,
										'query' => $query,
										'query_type' => 'UPDATE',
										'set_memcache' => 1,
										'tables' => array( $this->table_name ),
									);
									execute_sql_query($query_settings);
									
									$this->class_settings[ 'cache_where' ] = " AND id = '". $id ."' ";
									$this->class_settings[ 'current_record_id' ] = 'pass_condition';
									$this->class_settings[ 'do_not_check_cache' ] = 1;
									
									$this->_get_record();
									
									if( class_exists( 'cLogged_in_users' ) ){
										$lg = new cLogged_in_users();
										$lg->class_settings = $this->class_settings;
										
										$lg->_update_log( array( 'action' => 'otp_validation', 'user_id' => $id ) );
									}

									$this->class_settings[ 'password_raw' ] = 1;
									$this->class_settings[ 'action_to_perform' ] = 'authenticate_app';
									$this->class_settings[ 'no_otp' ] = 1;
									$_POST[ 'email' ] = $e[ 'email' ];
									$_POST[ 'password' ] = $e[ 'password' ];

									return $this->users();
								}

							}else{
								$error = '<h4><strong>Incorrect OTP</strong></h4>Please try again or resend the OTP';
							}
						}else{
							$error = '<h4><strong>No OTP Found</strong></h4>';
						}

					}else{
						$error = '<h4><strong>Invalid User Reference</strong></h4>Please contact your system administrator';
					}
				}else{
					$error = '<h4><strong>Undefined User Reference</strong></h4>Please contact your system administrator';
				}
			}else{
				$error = '<h4><strong>Undefined OTP</strong></h4>Please input an OTP';
			}

			if( ! $error ){
				$error = '<h4><strong>Unknown Error</strong></h4>Please contact your system administrator';
			}

			return $this->_display_notification( array( "type" => $etype, "message" => $error ) );

			return $this->_display_html_only( [ 'html' => $r[ 'html' ] ] );
		}

		private function _activate_user_account(){
			$error = '';
			$etype = 'error';

			if( isset( $_GET[ '_u' ] ) && $_GET[ '_u' ] ){
				$id = $_GET[ '_u' ];

				$this->class_settings[ 'current_record_id' ] = $id;
				$e = $this->_get_record();

				if( isset( $e[ 'status' ] ) && $e[ 'status' ] !== 'pending_activation' ){
					$etype = 'info';
					$error = '<h4><strong>Invalid Status</strong></h4>This user is not pending authorization.<br>Please contact your system administrator';
				}elseif( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
					$st = 'active';

					if( ! ( defined( 'HYELLA_V3_BYPASS_USER_ACCOUNT_VERIFICATION' ) && HYELLA_V3_BYPASS_USER_ACCOUNT_VERIFICATION ) ){
						$st = 'pending';
					}
					
					$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `". $this->table_fields[ 'status' ] ."` = '". $st ."' WHERE `".$this->table_name."`.`record_status`='1' AND id = '". $id ."' ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					execute_sql_query($query_settings);
					
					$this->class_settings[ 'cache_where' ] = " AND id = '". $id ."' ";
					$this->class_settings[ 'current_record_id' ] = 'pass_condition';
					$this->class_settings[ 'do_not_check_cache' ] = 1;
					
					$this->_get_record();

					$error = '<h4><strong>Account Activated Successfully</strong></h4>';
					$etype = 'success';

				}else{
					$error = '<h4><strong>Invalid User Reference</strong></h4>Please contact your system administrator';
				}
			}else{
				$error = '<h4><strong>Undefined User Reference</strong></h4>Please contact your system administrator';
			}

			if( ! $error ){
				$error = '<h4><strong>Unknown Error</strong></h4>Please contact your system administrator';
			}

			$r = $this->_display_notification( array( "type" => $etype, "message" => $error ) );

			return $this->_display_html_only( [ 'html' => $r[ 'html' ] ] );
		}

		private function _capture_signature2(){

			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			switch( $action_to_perform ){
			case 'capture_capture_signature':
				//signed signature
				$image = isset($_POST['image']) && $_POST['image'] ? $_POST['image'] : '';
				if( $image ){
					$this->class_settings['current_record_id'] = $this->class_settings['user_id'];
					$e = $this->_get_record();
					$data = isset($e['data']) && $e['data'] ? json_decode( $e['data'], true ) : [];
					
					$tpath = 'files/'.$this->table_name;
					$fpath = $this->class_settings['calling_page'].$tpath;
					if( defined("NWP_FILES_PATH") && NWP_FILES_PATH ){
						$apdir = ( defined("NWP_APP_DIR")?NWP_APP_DIR:'app' ) . '/';
						$tpath = ( defined("NWP_FILE_DIR")?( NWP_FILE_DIR . '/' ):'' ).$this->table_name;
						
						$fpath = NWP_FILES_PATH.$apdir.$tpath;
					}
					create_folder( $fpath, '', '' );
					if( is_dir( $fpath ) ){
						$image = substr($image,strpos($image,",")+1);
						$image = base64_decode($image);
							
						$imageName = get_new_id('usign') . '.png';
						file_put_contents( $fpath . '/' . $imageName, $image );
						if( file_exists( $fpath . '/' . $imageName ) ){
							
							if( isset($data['image']) && $data['image'] ){
								unset( $data['image'] );
							}
							
							$data['file'] = $tpath . '/' . $imageName;
							$data['file_source'] = 'signed';
							$_POST = array( 'id' => $this->class_settings['user_id'], 'data' => json_encode($data) );
							$return = $this->_save_app_changes();
							if( isset($return['saved_record_id']) && $return['saved_record_id'] ){
								return array(
									'status' => 'new-status',
									'javascript_functions' => ['nwSign.setSignBox']
								);
							}else $error_msg = "<h4><b>Error Saving Signature</b></h4><p>Signature could not be saved</p>";
						}else{
							$error_msg = "<h4><b>Unable to Save Signature</b></h4><p>Please check dir persmissions</p>";
						}
					}else{
						$error_msg = "<h4><b>Invalid Upload Dir</b></h4><p>".$fpath."</p>";
					}

				}else $error_msg = "<h4><b>Invalid Signature</b></h4><p>Image not received</p>";
			break;
			case 'save_signatures':
				//uploaded signature
				$sbox = isset($_POST['signbox']) && $_POST['signbox'] ? boolval($_POST['signbox']) : false;
				$file = isset($_POST['signature_1']) && $_POST['signature_1'] ? $_POST['signature_1'] : '';
				$cb = isset($_POST['cb']) && $_POST['cb'] ? $_POST['cb'] : '';
				if( !$sbox && !$file ){
					$error_msg = "<h4><b>Invalid Signature</b></h4><p>No signatures were received. Kindly try again later</p>";
				}elseif( !$sbox && $file ){
					$this->class_settings['current_record_id'] = $this->class_settings['user_id'];
					$e = $this->_get_record();
					$data = isset($e['data']) && $e['data'] ? json_decode( $e['data'], true ) : [];
					$data['file'] = $file;
					$data['file_source'] = 'uploaded';
					if( isset($data['image']) && $data['image'] ){
						unset( $data['image'] );
					}

					$_POST = array( 'id' => $this->class_settings['user_id'], 'data' => json_encode($data) );
					$return = $this->_save_app_changes();
				}

				if( $sbox || (isset($return['saved_record_id']) && $return['saved_record_id'] ) ) {
					$e_type = 'success';
					$error_msg = "<h4><b>Signature Saved Successfully</b></h4><p>Signature has been saved successfully</p>";
					if( $cb ){
						$n_params['callback'] = [$cb];
					}
				}
			break;
			default:
				return $this->_capture_signature();
			break;
			}
			
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
			
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
			
			return $this->_display_notification( $n_params );


		}

		private function _migrate_old_users(){
			
			$r = array( 'serial_num', 'creator_role', 'created_by', 'creation_date', 'modified_by', 'modification_date', 'ip_address', 'device_id', 'record_status' );

			$query = " SELECT `id` ";
			$dr = array();
			$ue = new cUsers_employee_profile();
			
			$x = array();
			$x2 = array();
			$y = array();
			$y2 = array();
			
			$y[] = 'id';
			$y2[] = 'id';
			
			foreach( $this->table_fields_old as $tk => $tv ){
				$dr[] = $tv;
				$query .= ", `". $tv ."` as '". $tk ."' ";
				
				if( isset( $this->table_fields[ $tk ] ) ){
					$x[] = $this->table_fields[ $tk ];
					$x2[] = " `". $this->table_fields[ $tk ] ."` = `".$tv."` ";
				}
				if( isset( $ue->table_fields[ $tk ] ) ){
					$y2[] = $tv;
					$y[] = $ue->table_fields[ $tk ];
				}
			}
			
			foreach( $r as $rk => $rv ){
				$y[] = $rv;
				$y2[] = $rv;
				
				$dd = next( $r );
				$query .= ", `". $rv ."` ";
			}
			//$query .= " FROM `".$this->class_settings["database_name"]."`.`users_old` ";
			$query .= " FROM `".$this->class_settings["database_name"]."`.`users` ";
			//echo $query; exit;
			
			echo "UPDATE `". $this->table_name ."` SET ". implode( ", ", $x2 ) .";<br /><br />";
				
			echo "INSERT INTO `". $ue->table_name ."` (`". implode( "`,`", $y ) ."`) SELECT `". implode( "`, `", $y2 ) ."` FROM `". $this->table_name ."`;<br /><br />";
			
			echo "ALTER TABLE `". $this->table_name ."` DROP ". implode( ", DROP ", $dr ) .";<br /><br />";
			exit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( 'users_old' ),
			);
			$old = execute_sql_query($query_settings);

			// $ue = array();
			$line_items_users = array();
			$line_items_users_employee = array();

			$q1 = '';
			$q2 = '';

			foreach( $old as $o ){
				$x = array();
				$x1 = array();
				$y = array();
				$y1 = array();


				foreach( $o as $key => $value ){
					if( isset( $this->table_fields[ $key ] ) ){
						$x[] = $this->table_fields[ $key ];
						$x1[] = addslashes( $value );
					}
					if( ! empty( $ue ) && isset( $ue->table_fields[ $key ] ) ){
						$y[] = $ue->table_fields[ $key ];
						$y1[] = addslashes( $value );
					}
				}
				$x[] = 'id';
				$x1[] = $o[ 'id' ];
				
				$y[] = 'id';
				$y1[] = get_new_id();
				
				foreach( $r as $v ){
					if( isset( $o[ $v ] ) ){
						$x[] = $v;
						$x1[] = $o[ $v ];
						$y[] = $v;
						$y1[] = $o[ $v ];
					}
				}

				
				$q1 .= " INSERT INTO `". $this->table_name ."` (`". implode( "`,`", $x ) ."`) VALUES ('". implode( "','", $x1 ) ."');<br /><br />";
				
				$q2 .= " INSERT INTO `". $ue->table_name ."` (`". implode( "`,`", $y ) ."`) VALUES ('". implode( "','", $y1 ) ."');<br /><br />";
			// echo '<pre>';
			// print_r( $q1 );exit;
			// echo '</pre>';
			}
			
			//echo $q1;
			//echo $q2;
		}

		private function _lock_account(){
			$action_to_perform = $this->class_settings['action_to_perform'];
			$bulk_update_parameters = array();
			$bulk_update_condition = array();
			
			$success_msg_text = '';
			$success_msg = '';
			$error_msg = '';
			$js = array( '$nwProcessor.reload_datatable' );
			$pass = 0;
			
			
			switch ( $action_to_perform ){
			case "activate_account":
				$bulk_update_parameters[] = " `".$this->table_fields["status"]."` = 'active' ";
				
				$bulk_update_condition[] = " `modified_by` != '". $this->class_settings[ 'user_id' ] ."' ";
				$bulk_update_condition[] = " `".$this->table_fields["status"]."` = 'pending' ";
				
				$success_msg_text = '<h4><i class="icon icon-unlock"></i>Activated</h4>User account is active';
			break;
			case "unlock_account":
				$bulk_update_parameters[] = " `".$this->table_fields["locked"]."` = 'no' ";
				$bulk_update_condition[] = " `".$this->table_fields["locked"]."` != 'no' ";
				
				$success_msg_text = '<h4><i class="icon icon-unlock"></i>Unlocked</h4>User account is enabled';
			break;
			case "lock_account":
				$bulk_update_parameters[] = " `".$this->table_fields["locked"]."` = 'yes' ";
				$bulk_update_condition[] = " `".$this->table_fields["locked"]."` != 'yes' ";
				
				$success_msg_text = '<h4><i class="icon icon-lock"></i>Locked</h4>User account is disabled';
			break;
			case 'auto_assign_previous_months':
				$pass = 1;
			break;
			}
			
			$ids = array();
			
			if( ! empty( $bulk_update_parameters ) || $pass ){
				
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					$ids = array( "'" . $_POST["id"] . "'" );
				}
				
				if( isset( $_POST["ids"] ) && $_POST["ids"] ){
					$xp = explode(":::", $_POST["ids"] );
					
					if( is_array( $xp ) && ! empty( $xp ) ){
						foreach( $xp as $xp1 ){
							
							$ids[] = "'" . $xp1 . "'";
							
						}
					}
				}
				
				if( empty( $ids ) ){
					$error_msg = '<h4>Invalid Selection</h4>Please select records by clicking on them';
				}else{

					switch ( $action_to_perform ){
					case "activate_account":
						if( count( $ids ) > 1 ){
							$error_msg = '<h4>Invalid Selection</h4>Please select only one record';
						}else{
							$this->class_settings[ 'current_record_id' ] = str_replace( "'", '', $ids[0] );
							$e = $this->_get_record();

							if( isset( $e[ 'id' ] ) && $e[ 'id' ] ){
								$md = md5( $e["password"] . $e["confirmpassword"] . $e[ 'modified_by' ] . get_websalter() );
								$bulk_update_condition[] = " `device_id` = '". $md ."' ";
							}else{
								$error_msg = '<h4>Invalid Selection</h4>Unable to retrieve account selection';
							}
						}
					break;
					}
					
					$date = date("U");
					$ip_address = get_ip_address();
					
					switch ( $action_to_perform ){
					default:
						
						$bulk_update_condition[] = " `id` IN ( ". implode( ", ", $ids ) ." ) ";
						
						$bulk_update_parameters[] = " `ip_address` = '". $ip_address ."' ";
						$bulk_update_parameters[] = " `modification_date` = ". $date ." ";
						$bulk_update_parameters[] = " `modified_by` = '". $this->class_settings["user_id"] ."' ";
						
						$update_cache_where = " AND " . implode(" AND " , $bulk_update_parameters );
						
					break;
					}
					
					if( ! $error_msg ){
						
						//echo $query;
						//exit;
						
						$query = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET ". implode(", ", $bulk_update_parameters ) ." WHERE `".$this->table_name."`.`record_status`='1' AND " . implode(" AND ", $bulk_update_condition );
						
						$query_settings = array(
							'database' => $this->class_settings['database_name'] ,
							'connect' => $this->class_settings['database_connection'] ,
							'query' => $query,
							'query_type' => 'UPDATE',
							'set_memcache' => 1,
							'tables' => array( $this->table_name ),
						);
						execute_sql_query($query_settings);
						
						$this->class_settings[ 'cache_where' ] = $update_cache_where;
						$this->class_settings[ 'current_record_id' ] = 'pass_condition';
						$this->class_settings[ 'do_not_check_cache' ] = 1;
						
						$this->_get_record();
						
						$success_msg = $success_msg_text;

						if( class_exists("cLogged_in_users") ){
							$st = '';

							switch ( $action_to_perform ){
							case "activate_account":
								$st = 'activate_account';
							break;
							case "unlock_account":
								$st = 'unlocked_account';
							break;
							case "lock_account":
								$st = 'locked_account';
							break;
							}
							
							if( $st ){
								foreach( $ids as $xid ){
									$lg = new cLogged_in_users();
									$lg->class_settings = $this->class_settings;
									$lg->class_settings["user_id"] = str_replace( "'", '', $xid );
									
									$lg->_update_log( array( 'action' => $st ) );
								}
							}
						}

					}
				}
			}
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>';
			}
			
			if( $success_msg ){
				$return = $this->_display_notification( array( "type" => "success", "message" => $success_msg ) );
				
				if( ! empty( $js ) ){
					$return['javascript_functions'] = $js;
				}
				$return["status"] = 'new-status';
				unset( $return["html"] );
					
				return $return;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => "error", "message" => $error_msg ) );
			}
		}
		
		private function _sign_out(){
			
			auditor("", "login", "user_logout", array( "comment" => "User: " . $this->class_settings["user_full_name"] ), $this->class_settings );
			
			unset( $_SESSION["key"] );
			$_SESSION = array();
		
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			
			//session_destroy();
			
			$returned_data = array(
				"status" => "new-status",
				//"redirect_url" => "index.html",
				"redirect_url" => $this->class_settings[ 'project_data' ][ "domain_name" ],
			);
			
			return $returned_data;
		}
		
		private function _get_email_of_users_based_on_capability(){
			
			$cap_where = '';
			$cap = '';
			$a1 = new cAccess_roles();
			
			if( isset( $this->class_settings["capability"] ) && $this->class_settings["capability"] ){
				$cap = $this->class_settings["capability"];
				$cap_where = " `".$a1->table_name."`.`".$a1->table_fields["accessible_functions"]."` regexp '". $cap ."' ";
			}
			
			if( isset( $this->class_settings["capabilities"] ) && is_array( $this->class_settings["capabilities"] ) && ! empty( $this->class_settings["capabilities"] ) ){
				$caps = $this->class_settings["capabilities"];
				
				$cwhere = array();
				
				foreach( $caps as $cap ){
					$cwhere[] = " `".$a1->table_name."`.`".$a1->table_fields["accessible_functions"]."` regexp '". $cap ."' ";
				}
				
				$cap_where = implode( " AND ", $cwhere );
			}
			
			if( isset( $this->class_settings["specific_users"] ) && $this->class_settings["specific_users"] ){
				$cap_where = " ( `".$this->table_name."`.`id` IN ( ". implode( ',', $this->class_settings["specific_users"] ) ." ) ) ";
			}
			
			if( isset( $this->class_settings["users_filter"] ) && is_array( $this->class_settings["users_filter"] ) && ! empty( $this->class_settings["users_filter"] ) ){
				
				$ucondition = isset( $this->class_settings["users_filter_condition"] )?$this->class_settings["users_filter_condition"]:'';
				
				if( ! $ucondition )$ucondition = " AND ";
				
				foreach( $this->class_settings["users_filter"] as $k => $v ){
					
					$cwhere = array();
					
					if( isset( $this->table_fields[ $k ] ) ){
						$cwhere[] = " `".$this->table_name."`.`".$this->table_fields[ $k ]."` = '". $v ."' ";
					}
					
					if( ! empty( $cwhere ) )$cap_where .= " ".( $cap_where ? $ucondition : '' )." ( " . implode( " AND ", $cwhere ) . " ) ";
					// $cap_where .= " ".$ucondition." ( " . implode( " AND ", $cwhere ) . " ) ";
				}
			}
				// print_r( $cwhere	 );exit;
			
			if( $cap_where ){
				
				if( isset( $this->class_settings["exclude_capabilities"] ) && is_array( $this->class_settings["exclude_capabilities"] ) && ! empty( $this->class_settings["exclude_capabilities"] ) ){
					$caps = $this->class_settings["exclude_capabilities"];
					
					$cwhere = array();
					
					foreach( $caps as $cap ){
						$cwhere[] = " `".$a1->table_name."`.`".$a1->table_fields["accessible_functions"]."` not regexp '". $cap ."' ";
					}
					
					$cap_where .= " AND " . implode( " AND ", $cwhere );
				}
				
				$this->class_settings["overide_select"] = " `".$this->table_name."`.`id` as 'id', `".$this->table_fields["email"]."` as 'email' ";
				
				$this->class_settings["join"] = " JOIN `".$this->class_settings["database_name"]."`.`".$a1->table_name."` ON `".$a1->table_name."`.`id` = `".$this->table_name."`.`".$this->table_fields["role"]."` ";
				
				
				if( ! ( isset( $this->class_settings["get_ids"] ) && $this->class_settings["get_ids"] ) ){
					$cap_where .= " AND `".$this->table_name."`.`".$this->table_fields["email"]."` != '' ";
				}
				
				$this->class_settings["where"] = " AND ".$cap_where." AND `".$a1->table_name."`.`record_status` = '1' ";
				
				// print_r( $this->class_settings["where"] );exit;
				
				return $this->_get_all_customer_call_log();
			}
		}
		
		private function _get_email_of_users_based_on_capability_old(){
			
			$cap_where = '';
			$cap = '';
			$a1 = new cAccess_roles();
			
			if( isset( $this->class_settings["capability"] ) && $this->class_settings["capability"] ){
				$cap = $this->class_settings["capability"];
				$cap_where = " `".$a1->table_name."`.`".$a1->table_fields["accessible_functions"]."` regexp '". $cap ."' ";
			}
			
			if( isset( $this->class_settings["capabilities"] ) && is_array( $this->class_settings["capabilities"] ) && ! empty( $this->class_settings["capabilities"] ) ){
				$caps = $this->class_settings["capabilities"];
				
				$cwhere = array();
				
				foreach( $caps as $cap ){
					$cwhere[] = " `".$a1->table_name."`.`".$a1->table_fields["accessible_functions"]."` regexp '". $cap ."' ";
				}
				
				$cap_where = implode( " AND ", $cwhere );
			}
			
			
			if( isset( $this->class_settings["users_filter"] ) && is_array( $this->class_settings["users_filter"] ) && ! empty( $this->class_settings["users_filter"] ) ){
				
				$ucondition = isset( $this->class_settings["users_filter_condition"] )?$this->class_settings["users_filter_condition"]:'';
				
				if( ! $ucondition )$ucondition = " AND ";
				
				foreach( $this->class_settings["users_filter"] as $k => $v ){
					
					$cwhere = array();
					
					if( isset( $this->table_fields[ $k ] ) ){
						$cwhere[] = " `".$this->table_name."`.`".$this->table_fields[ $k ]."` = '". $v ."' ";
					}
					
					$cap_where .= " ".$ucondition." ( " . implode( " AND ", $cwhere ) . " ) ";
				}
			}
			
			if( $cap_where ){
				
				if( isset( $this->class_settings["exclude_capabilities"] ) && is_array( $this->class_settings["exclude_capabilities"] ) && ! empty( $this->class_settings["exclude_capabilities"] ) ){
					$caps = $this->class_settings["exclude_capabilities"];
					
					$cwhere = array();
					
					foreach( $caps as $cap ){
						$cwhere[] = " `".$a1->table_name."`.`".$a1->table_fields["accessible_functions"]."` not regexp '". $cap ."' ";
					}
					
					$cap_where .= " AND " . implode( " AND ", $cwhere );
				}
				
				$this->class_settings["overide_select"] = " `".$this->table_name."`.`id` as 'id', `".$this->table_fields["email"]."` as 'email' ";
				
				$this->class_settings["join"] = " JOIN `".$this->class_settings["database_name"]."`.`".$a1->table_name."` ON `".$a1->table_name."`.`id` = `".$this->table_name."`.`".$this->table_fields["role"]."` ";
				
				
				if( ! ( isset( $this->class_settings["get_ids"] ) && $this->class_settings["get_ids"] ) ){
					$cap_where .= " AND `".$this->table_name."`.`".$this->table_fields["email"]."` != '' ";
				}
				
				$this->class_settings["where"] = " AND ".$cap_where." AND `".$a1->table_name."`.`record_status` = '1' ";
				
				
				return $this->_get_all_customer_call_log();
			}
		}
		
		private function _save_captured_image(){

			$return = array();
			
			if( ! ( isset( $_POST["image"] ) && $_POST["image"] && isset( $_GET["id"] ) && $_GET["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Image</h4><p>Image file was not received</p>';
				return $err->error();
			}
			
			$_POST["id"] = $_GET["id"];
			
			//echo $_POST["json"]; exit;
			$dir = $this->class_settings["calling_page"] . "files/" . $this->table_name;
			$dir1 = "files/" . $this->table_name;
			if( ! is_dir( $dir ) ){
				create_folder( "", "", $dir );
			}
			
			$id = get_new_id();

			$ext = '.jpg';
			switch( $this->class_settings[ 'action_to_perform' ] ){
			case "save_captured_image":
				$ext = '.png';
			break;
			}
			
			$data = base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $_POST["image"] ) );
			file_put_contents( $dir . "/" . $id . $ext , $data );
			
			$field = 'photograph';
			if( isset( $this->class_settings[ 'image_field' ] ) && $this->class_settings[ 'image_field' ] ){
				$field = $this->class_settings[ 'image_field' ];
			}

			$this->class_settings["update_fields"] = array(
				$field => $dir1 . "/" . $id . $ext,
			);
			$r = $this->_update_table_field();
			
			if( isset( $u->class_settings[ 'employee_class' ] ) && $u->class_settings[ 'employee_class' ] ){
				return $r["saved_record_id"];
			}

			if( isset( $r["saved_record_id"] ) && $r["saved_record_id"] ){
				/*
				$err = new cError('010011');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Successful Image Capture</h4><p>Image file has been saved</p>';
				$return = $err->error();
				
				unset( $return["html"] );
				$return["status"] = "new-status";
				$return["javascript_functions"] = array();
				
				$return["stored_path"] = $dir1 . "/" . $id . ".jpg";
				$return["full_path"] = $this->class_settings[ 'project_data' ][ "domain_name" ] . $return["stored_path"];
				*/
				$this->class_settings["action_to_perform"] = "view_employee_profile";
				return $this->_display_manage_employees_details();
				//return $return;
			}
			
			return $r;
		}
		
		protected function _load_image_capture(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Employee</h4><p>Please select an employee and try again</p>';
				return $err->error();
			}
			
			$id = $_POST["id"];
			
			$this->class_settings[ 'data' ][ 'id' ] = $id;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/image-capture.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				"html_replacement_selector" => "#capture-container",
				"html_replacement" => $returning_html_data,
				"status" => "new-status",
				//"javascript_functions" => array( "nwInventory.activateRecentSupplyItems" )
			);
		}
		
		protected function _last_user_check(){
			
			$this->class_settings["overide_select"] = " `id`, `serial_num` ";
			$this->class_settings["where"] = " AND `".$this->table_fields["status"]."` = 'active' AND `created_by` = '".$this->class_settings["user_id"]."' ORDER BY `creation_date` DESC LIMIT 1 ";
			$data = $this->_get_all_customer_call_log();
			
			if( isset( $data[0]["id"] ) && $data[0]["id"] ){
				$ed = $this->_check_user_parameters( $data );
				
				$dmsg = '<p>Please check the records of the employee shown above and ensure all fields are properly field</p>';
				
				if( isset( $ed[ $data[0]["id"] ]["counter"] ) && isset( $ed[ $data[0]["id"] ]["check_counter"] ) ){
					if( $ed[ $data[0]["id"] ]["counter"] == $ed[ $data[0]["id"] ]["check_counter"] ){
						return '';	//success
					}
					
					//get display message
					$dmsg .= '<ol>';
					
					foreach( $ed[ $data[0]["id"] ] as $k => $v ){
						switch( $k ){
						case "counter":
						case "check_counter":
						break;
						default:
							if( $v["count"] == 0 ){
								$dmsg .= '<li>'. $v["label"] .'</li>';
							}
						break;
						}
					}
					
					$dmsg .= '</ol>';
				}
				
				return '<h4>Check '. get_name_of_referenced_record( array( "id" => $data[0]["id"], "table" => "users" ) ) .'</h4>' . $dmsg;
			}
			
			//return '<h4>Unknown Error</h4><p>Please check the record of the last created employee</p>';
		}
		
		protected function _check_user_parameters( $e = array() ){
			
			$checklist = array(
				"users_next_of_kin",
				"users_educational_history",
				//"users_dependents",
				"users_work_experience_history",
				//"users_current_work_history",
				//"users_performance_appraisal_history",
				//"users_professional_association",
				//"users_disciplinary_history",
				"users_salary_details",
			);
			
			$checklist_status = array();
			
			$date = date("U");
			
			if( is_array( $e ) && ! empty( $e ) ){
				foreach( $e as $edata ){
					$counter = 0;
					
					foreach( $checklist as $table ){
						
						$actual_name_of_class = 'c'.ucwords( $table );
						
						if( class_exists( $actual_name_of_class ) ){
							$module = new $actual_name_of_class();
							$module->class_settings = $this->class_settings;
							$module->class_settings["action_to_perform"] = "get_data";
							$module->class_settings["where"] = " AND `".$module->table_fields[ $module->default_reference ]."` = '".$edata["id"]."' ";
							$module->class_settings["overide_select"] = " COUNT(*) as 'count' ";
							$dx = $module->$table();
							
							$checklist_status[ $edata["id"] ][ $table ] = array(
								"count" => 0,
								"label" => $module->label,
								"last_check" => $date,
							);
							
							if( ( isset( $dx[0]["count"] ) && $dx[0]["count"] ) ){
								++$counter;
								$checklist_status[ $edata["id"] ][ $table ]["count"] = $dx[0]["count"];
							}
						}
					}
					
					$checklist_status[ $edata["id"] ]["check_counter"] = count( $checklist );
					$checklist_status[ $edata["id"] ]["counter"] = $counter;
				}
			}
			
			return $checklist_status;
		}
		
		protected function _search_birthdays(){
			$select = "";
			$where = " WHERE `".$this->table_name."`.`record_status` = '1' ";
			
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = " `".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$this->table_name."`.`created_by`, `".$this->table_name."`.`modification_date`, `creation_date`, `".$this->table_name."`.`modified_by`, `".$this->table_name."`.`".$val."` as '".$key."'";
			}
			
			$start_date = ( isset( $_POST["start_date"] ) && $_POST["start_date"] )? convert_date_to_timestamp( $_POST["start_date"], 1 ) : date("U");
			$end_date = ( isset( $_POST["end_date"] ) && $_POST["end_date"] )? convert_date_to_timestamp( $_POST["end_date"], 2 ) : date("U");
			
			$report_type = isset($_POST['report_type']) && $_POST['report_type'] ? $_POST['report_type'] : 'upcoming_birthdays';
			$date_field = 'date_of_birth';
			
			switch( $this->class_settings["action_to_perform"] ){
			case "search_birthdays":
				switch ($report_type) {
					case 'wedding_anniversary':
						$date_field = isset($this->table_fields['wedding_anniversary']) && $this->table_fields['wedding_anniversary'] ? $report_type : $date_field;
					break;
					case 'work_anniversary':
						$date_field = isset($this->table_fields['date_employed']) && $this->table_fields['date_employed'] ? 'date_employed' : $date_field;
					break;
				}

				$where .= " AND `".$this->table_name."`.`".$this->table_fields["date_of_birth"]."` != 0 ";
				$where .= " AND from_unixtime( `".$this->table_name."`.`".$this->table_fields[ $date_field ]."`, '%m.%d' ) >= '".date( "m.d", $start_date )."' ";
				$where .= " AND from_unixtime( `".$this->table_name."`.`".$this->table_fields[ $date_field ]."`, '%m.%d' ) <= '".date( "m.d", $end_date )."' ";
				
				$where .= " ORDER BY from_unixtime( `".$this->table_name."`.`".$this->table_fields[ $date_field ]."`, '%m.%d' ) ASC ";
			break;
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` " . $where;
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$sql = execute_sql_query( $query_settings );
			
			$this->class_settings[ 'data' ]['start_date'] = $start_date;
			$this->class_settings[ 'data' ]['end_date'] = $end_date;
			$this->class_settings[ 'data' ]['table'] = $this->table_name;
			$this->class_settings[ 'data' ]['items'] = $sql;
			$this->class_settings[ 'data' ]['date_field'] = $date_field;
			$this->class_settings[ 'data' ]['report_type'] = $report_type;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/upcoming-birthdays.php' );
			$html = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#data-table-section",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				//'javascript_functions' => array( 'set_function_click_event' ),
			);
		}
		
		protected function _view_quick_report(){
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = '<h4>Invalid Report Type</h4>';
				return $err->error();
			}
			$report_type = $_POST["id"];
			$title = 'View Reports';
			
			$table = $this->table_name;
			
			$this->class_settings[ 'data' ]['table'] = $this->table_name;
			$this->class_settings[ 'data' ]['report_type'] = $report_type;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/quick-report-form.php' );
			$html = $this->_get_html_view();
			
			if( isset( $_GET["modal"] ) && $_GET["modal"] ){
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $html,
					'html_replacement_selector' => "#modal-replacement-handle",
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
				);
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/zero-out-negative-budget' );
			$this->class_settings[ 'data' ]["html_title"] = $title;
			$this->class_settings[ 'data' ]['html'] = $html;
			$this->class_settings[ 'data' ]["modal_dialog_style"] = "width:70%;";
			
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $returning_html_data,
				'html_prepend_selector' => "#dash-board-main-content-area",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'prepare_new_record_form_new' ),
			);
		}
		
		protected function _display_employee_profile( $e = array() ){
			
			$table = $this->table_name;
			
			//get number of dependents
			if( get_show_human_resource_settings() ){
				if( class_exists("cUsers_dependents") ){
					
					$config = new cUsers_dependents();
					$config->class_settings = $this->class_settings;
					$config->class_settings["current_record_id"] = $e["id"];
					$config->class_settings["action_to_perform"] = "get_number_of_dependents";
					$e["number_of_dependents"] = $config->users_dependents();
					
				}
			}
			
			$this->class_settings[ 'data' ][ 'table' ] = $table;
			$this->class_settings[ 'data' ][ 'labels' ] = $table();
			$this->class_settings[ 'data' ][ 'fields' ] = $this->table_fields;
			$this->class_settings[ 'data' ][ 'event' ] = $e;
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/employee-profile.php' );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => "#dash-board-main-content-area",
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event' ),
			);
		}
		
		protected function _display_manage_employees_details(){
			$this->label = 'Employee';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid '.ucwords( $this->label ).' Details';
				return $err->error();
			}
			$project = $_POST["id"];
			
			$js = array();
			
			$html_handle = "#page-sub-content";
			$table = 'cart';
			
			$this->class_settings["current_record_id"] = $project;
			if( ! ( isset( $this->class_settings["reference"] ) && $this->class_settings["reference"] ) ){
				$this->class_settings["reference"] = $this->class_settings["current_record_id"];
			}
			$e = $this->_get_customer_call_log();
			
			switch( $this->class_settings["action_to_perform"] ){
			case "manage_next_of_kin":
				$table = 'users_next_of_kin';
				$action = 'display_app_manager';
			break;
			case "manage_educational_history":
				$table = 'users_educational_history';
				$action = 'display_app_manager';
			break;
			case "view_employee_profile":
				$return = $this->_display_employee_profile( $e );
				$table = '';
			break;
			case "view_details2":
				return $this->_display_employee_profile( $e );
			break;
			case "manage_salary_history":
				$table = 'users_educational_history';
				$action = 'display_app_manager';
			break;
			case "manage_payslips_history":
				$table = 'users_educational_history';
				$action = 'display_app_manager';
			break;
			case "manage_dependents":
				$table = 'users_dependents';
				$action = 'display_app_manager';
			break;
			case "manage_performance_appraisal":
				$table = 'users_performance_appraisal_history';
				$action = 'display_app_manager';
			break;
			case "manage_work_history":
				$table = 'users_current_work_history';
				$action = 'display_app_manager';
			break;
			case "manage_work_experience":
				$table = 'users_work_experience_history';
				$action = 'display_app_manager';
			break;
			case "manage_professional_association":
				$table = 'users_professional_association';
				$action = 'display_app_manager';
			break;
			case "manage_disciplinary_history":
				$table = 'users_disciplinary_history';
				$action = 'display_app_manager';
			break;
			case "manage_salary_details":
				$table = 'users_salary_details';
				$action = 'display_app_manager';
			break;
			case "manage_leave_history":
				$table = 'leave_requests';
				$action = 'display_app_manager';
			break;
			}
			
			$html = '';
			
			if( isset( $e["id"] ) && $e["id"] ){
				if( $table ){
					$rt = "c" . ucwords( $table );
					
					$cart = new $rt();
					$cart->class_settings = $this->class_settings;
					$cart->class_settings["action_to_perform"] = $action;
					$return = $cart->$table();
				}
				
				if( isset( $return["html_replacement_selector"] ) ){
					$return["html_replacement_selector"] = $html_handle;
					$return["javascript_functions"][] = 'nwResizeWindow.resizeWindowHeight';
				}
				return $return;
			}
		}
		
		protected function _search_employee(){
			//populate dashboard
			if( ! ( isset( $_POST["employee"] ) && $_POST["employee"] ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = 'Invalid '.ucwords( $this->table_name ).' Details';
				return $err->error();
			}
			$employee = $_POST["employee"];
			
			$this->class_settings["current_record_id"] = $employee;
			$e = $this->_get_customer_call_log();
			
			if( isset( $e["id"] ) && $e["id"] ){
				set_current_customer( array( "current_employee" => $e["id"] ) );
			}
			
			return array(
				'do_not_reload_table' => 1,
				'data' => $e,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'nwUsers.activateTabs' ),
			);
			
		}
		
		protected function _display_manage_employees(){
			$filename = 'display-employees-manager';
			
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle =  "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$this->class_settings[ 'data' ][ 'action_to_perform' ] = $action_to_perform;
			
			switch( $action_to_perform ){
			case "display_employees_history_center":
				$filename = str_replace( "_", "-", $this->class_settings["action_to_perform"] ) . '.php';
				$this->class_settings[ 'data' ][ 'title' ] = 'Employees History Center';
			break;
			case "display_leave_admin_center":
				$filename = str_replace( "_", "-", $this->class_settings["action_to_perform"] ) . '.php';
				$this->class_settings[ 'data' ][ 'title' ] = 'Leave Administration Center';
			break;
			case "display_manage_employees2":
				$this->class_settings[ 'data' ][ 'frontend' ] = 1;
				
				$js[] = 'nwUsers.init';
			break;
			}
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
			$returning_html_data = $this->_get_html_view();
			
			return array(
				'html_replacement_selector' => $handle,
				'html_replacement' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
		}
		
		protected function _update_all_staff_as_customer( $return = array() ){
			if( isset( $return['record']["id"] ) && $return['record']["id"] ){
				$tmp = isset( $_POST["id"] )?$_POST["id"]:'';
				
				$_POST["customer"] = $return['record']["id"] . $this->table_name;
				$config = new cCustomers();
				$config->class_settings = $this->class_settings;
				$config->class_settings["action_to_perform"] = "search_customer_only";
				$c = $config->customers();
				
				if( isset( $c[0]["id"] ) && $c[0]["id"] ){
					$_POST["id"] = $c[0]["id"];
				}else{
					$_POST["id"] = '';
					$_POST["tmp"] = $return['record']["id"] . $this->table_name;
				}
				
				$config->class_settings["action_to_perform"] = "update_fields";
				$config->class_settings["update_fields"] = array(
					"name" => $return['record']["firstname"] . ' ' . $return['record']["lastname"],
					"last_name" => $return['record']["lastname"],
					"address" => $return['record']["address"],
					"phone" => $return['record']["phone_number"],
					"email" => $return['record']["email"],
					"photograph" => $return['record']["photograph"],
					"sex" => $return['record']["sex"],
					"date_of_birth" => $return['record']["date_of_birth"],
				);
				$config->customers();
				
				if( $tmp ){
					$_POST["id"] = $tmp;
				}
			}
		}
		
		protected function _refresh_all_staff_customer_details(){
			set_time_limit(0); //Unlimited max execution time
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				if( $select )$select .= ", `".$val."` as '".$key."'";
				else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
			}
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status` = '1' ";
			
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_items = execute_sql_query($query_settings);
			
			if( ! empty( $all_items ) && is_array( $all_items ) ){
				foreach( $all_items as $sval ){
					$return["record"] = $sval;
					$this->_update_all_staff_as_customer( $return );
				}
			}
			
		}
		
		protected function _save_changes2(){
			$rank = '';
			$qualification = '';
			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';

			if( defined("NIN_VERIFICATION_ON_ACCOUNT_CREATION") && NIN_VERIFICATION_ON_ACCOUNT_CREATION ){
				$error = "";
				if( isset($_POST['nin']) && $_POST['nin'] ){
					if( class_exists('cNwp_reports') ){
						$v = cNwp_reports::validateNin( array( 'nin' => $_POST['nin'] ) );
						if( isset($v[ 'error' ]) && $v[ 'error' ] ){
							$error = $v['error'];
						}else{
							foreach (['firstname', 'surname', 'email', 'gender'] as $_key) {
								if( !(isset($v[ $_key ]) && $v[ $_key ] ) ){
									$error = "<h4><b>Invalid NIN Details</b></h4><p>NIN is missing a necessary field: ". $_key ."</p>";
								}
							}
							
							if( !$error ){
								$_POST[ $this->table_fields['firstname'] ] = $v['firstname'];
								$_POST[ $this->table_fields['lastname'] ] = $v['surname'];
								$_POST[ $this->table_fields['email'] ] = $v['email'];
								$_POST[ $this->table_fields['sex'] ] = ($v['gender'] == 'm' ? 'male' : 'female');
							}
						}
					}
				}else{
					if( isset($_POST['3_uvh']) && $_POST['3_uvh'] ){
						$error = "<h4><b>Invalid Submission</b></h4><p>Kindly provide a valid NIN</p>";
					}
				}

				if( $error ){
					return $this->_display_notification( array( 'type' => 'error', 'message' => $error, 'manual_close' => 1 ) );
				}
			}

			$return = $this->_save_changes();
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				if( class_exists( 'cLogged_in_users' ) ){
					$lg = new cLogged_in_users();
					$lg->class_settings = $this->class_settings;
					
					$lg->_update_log( array( 'action' => $id ? 'account_edited' : 'account_created', 'user_id' => $return[ 'saved_record_id' ] ) );
				}

				switch( $return[ 'record' ][ 'status' ] ){
				case 'pending_activation':
				
					$url = $this->get_endpoint( array( "source" => "emr", "fsource" => "" ) );
					$url .= '&daction=users&dtodo=activate_user_account&_u='.$return["saved_record_id"];

					$this->_send_email_notification( array(
						"subject" => "USER EMAIL VERIFICATION",
						"content" => "Use this link to verify your account <p>If the link is broken, you may verify your email by copying the URL below to your web browser.</p><p style='color:blue;text-decoration:underline;'><i>". $url ."</i></p>",
						"info" => '<br /><a target="_blank" href="'. $url .'" title="Verify your email" class="btnx redx">Click here to verify your email</a>',
						"single_email"  => $return[ 'record' ][ 'email' ],
						"single_name" => $return[ 'record' ][ 'firstname' ] . ' ' . $return[ 'record' ][ 'lastname' ]
					) );

				break;
				}
			}
			return $return;
		}

		public function get_endpoint( $opt = array() ){
			$rtype = isset( $opt[ 'request_type' ] ) ? $opt[ 'request_type' ] : '';
			$etype = isset( $opt[ 'endpoint_type' ] ) ? $opt[ 'endpoint_type' ] : '';
			$custom_get = isset( $opt[ 'custom_get' ] ) ? $opt[ 'custom_get' ] : '';
			$hash = isset( $opt[ 'hash' ] ) ? $opt[ 'hash' ] : null;

			$ep = $this->end_point1;

			$rand = rand();

			$input = array();
			$input['get'] = $custom_get ? $custom_get : [];
			$input['get']['app_secret'] = $this->app_key;
			$input['get']['token'] = $rand;
			$input['get']['public_key'] = md5( $rand . $input['get']['app_secret'] );
			$input['get']['cid'] = $this->app_id;

			if( $hash && ! empty( $hash ) ){
				$hask_key = '';
				$hh = $hash;
				foreach( $hh as $hk => $hv ){
					if( isset( $input[ 'get' ][ $hk ] ) && $input[ 'get' ][ $hk ] ){
						$hask_key .= $input[ 'get' ][ $hk ];
					}
				}
				$hask_key = md5( $hask_key );
				$hash_value = md5( $hask_key . get_websalter() );

				$input['get']['nwp_hash'] = get_file_hash( [ "hash" => 1, "file_id" => $hash_value, "no_session" => 1 ] );
				$input['get']['nwp_hash_id'] = $hask_key;
			}

			$output = array(
				'end_point_type' => $etype,
				'end_point' => $ep,
				'request_type' => $rtype,
				'input' => $input,
			);
			// print_r( $output );exit;
			
			$url = $ep.get_Endpoint( $output );

			return $url;
		}
		
		protected function _authenticate_passphrase(){
			
			if( ! get_capture_user_passphrase_settings() ){
				//inactive pass phrase settings
				$error_title = 'Passphrase Settings Disabled';
				$error_msg = 'Please contact your administrator to enable USER Passphrase Settings from the backend of HYELLA';
			}
			
			if( isset( $_POST["passphrase"] ) && $_POST["passphrase"] ){
				$p = strtolower( trim( $_POST["passphrase"] ) );
				
				$this->class_settings["where"] = " AND `".$this->table_fields["passphrase"]."` = '".md5( $p . get_websalter() )."' ";
				//$this->class_settings["where"] = " AND `".$this->table_fields["passphrase"]."` = '".$p."' ";
				$data = $this->_get_all_customer_call_log();
				if( isset( $data[0]["id"] ) && $data[0]["id"] ){
					$this->class_settings[ 'skip_password' ] = true;
					
					$authenticated = 0;
					if( $data[0]["id"] == $this->class_settings["user_id"] ){
						$authenticated = 1;
					}else{
						//Login New User
						$authentication = new cAuthentication();
						$authentication->class_settings = $this->class_settings;
						
						$authentication->class_settings["username"] = $data[0]["email"];
						$authentication->class_settings["password"] = 1;
						
						$authentication->class_settings["action_to_perform"] = 'confirm_username_and_password';
						$returned_data  = $authentication->authentication();
						
						if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
							$authenticated = 1;
						}
					}
					
					
					if( $authenticated ){
						$this->class_settings["user_id"] = $data[0]["id"];
						$this->class_settings["user_email"] = $data[0]["email"];
							
						//check for access role
						$access_right = 1;
						/*
						if( ! get_disable_access_control_settings() ){
							$access_right = 0;
							$access = get_accessed_functions( 0 );
							if( ( ! is_array( $access ) && $access ) || isset( $access[ "delete_sales" ] ) ){
								$access_right = 1;
							}
						}
						*/
						
						if( $access_right ){
							return array(
								'do_not_reload_table' => 1,
								'status' => 'new-status',
								'data' => array( "id" => $this->class_settings["user_id"], "email" => $this->class_settings["user_email"] ),
								'javascript_functions' => array( "$.fn.hyellaIMenu.processPendingRequest" ),
							);
						}else{
							$error_title = 'Access Denied';
							$error_msg = 'Please contact your administrator';
						}
						
					}else{
						//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
						$error_title = 'Unable to Authenticate';
						$error_msg = 'Please contact your administrator';
						
						if( isset( $returned_data["msg"] ) && $returned_data["msg"] ){
							$error_msg = $returned_data["msg"];
						}
					}
					
				}else{
					$error_title = 'Wrong Passphrase';
					$error_msg = 'Please enter your user account passphrase';
				}
			}else{
				$error_title = 'Empty Passphrase';
				$error_msg = 'Please enter your user account passphrase';
			}
			
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'cusers.php';
			$err->method_in_class_that_triggered_error = '_users_registration_process';
			$err->additional_details_of_error = $error_msg;
			$err->error_title = $error_title;
			return $err->error();
			
		}
		
		protected function _get_employees_in_department(){
			
			$container = '';
			if( isset( $_GET["container"] ) && $_GET["container"] ){
				$container = $_GET["container"];
			}
			
			$html = '<option value=""></option>';
			
			if( isset( $_POST["id"] ) && $_POST["id"] ){
				$this->class_settings["return_data"] = 1;
				$this->class_settings["department"] = $_POST["id"];
				
				$this->class_settings[ "data" ][ "users" ] = $this->_get_users_select2();
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/users-select-option.php' );
				$returning_html_data = $this->_get_html_view();
				
				if( $returning_html_data )$html = $returning_html_data;
			}
			
			return array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "select#".$container,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
			);
		}
		
		protected function _get_users_select2(){
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "lastname":
				case "phone_number":
				case "ref_no":
				case "email":
				case "firstname":
					if( $select )$select .= ", `". $this->table_name ."`.`".$val."` as '".$key."'";
					else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `". $this->table_name ."`.`".$val."` as '".$key."'";
				break;
				}
			}
			
			$where = isset( $this->class_settings['where'] ) && $this->class_settings['where'] ? $this->class_settings['where'] : '';
			$join = isset( $this->class_settings['join'] ) && $this->class_settings['join'] ? $this->class_settings['join'] : '';
			
			$cap = isset( $_GET[ 'capability' ] ) ? $_GET[ 'capability' ] : '';
			if( $cap ){
				$ac = new cAccess_roles;
				$ac->class_settings = $this->class_settings;
				$ac->class_settings[ 'overide_select' ] = " id ";
				$ac->class_settings[ 'where' ] = " AND `". $ac->table_fields[ 'data' ] ."` LIKE '%,\"". $cap ."\":1%' ";
				$rt = $ac->_get_records();
				// print_r( $rt );exit;
				if( isset( $rt[0][ 'id' ] ) && $rt[0][ 'id' ] ){
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'role' ] ."` IN ( '". implode( "','", array_column( $rt, 'id' ) ) ."' ) ";
				}else{
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'role' ] ."` IN ( '". $cap ."' ) ";
				}
			}

			if( $where )unset( $this->class_settings['where'] );
			if( $join )unset( $this->class_settings['join'] );
			
			if( isset( $_GET[ 'use_access_roles' ] ) && $_GET[ 'use_access_roles' ] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["role"]."` IN ( '". implode( "','", explode( ',', $_GET[ 'use_access_roles' ] ) ) ."' ) ";
			}
			
			$limit = '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$_POST["term"] = trim( $_POST["term"] );
				$where .= " AND ( `".$this->table_name."`.`".$this->table_fields["email"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["ref_no"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["firstname"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["lastname"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["phone_number"]."` regexp '".$_POST["term"]."' ) ";
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}

			$dpet_set = doubleval( get_general_settings_value( array( "key" => "FILTER WHOM TO SEE BY DEPARTMENT UPON ADDING TO QUEUE", "table" => "hospital" ) ) );
			$check_dept = isset( $_GET[ 'check_dept_queue' ] ) ? $_GET[ 'check_dept_queue' ] : '';
			if( $dpet_set && $check_dept ){
				if( isset( $_POST[ 'department' ] ) ){
					if( $_POST[ 'department' ] ){
						$this->class_settings["department"] = $_POST[ 'department' ];
					}else{
						return array( "items" => array() );
					}
				}
			}

			$dpet_set = doubleval( get_general_settings_value( array( "key" => "FILTER CONSULTANT BASED ON DEPARTMENT UPON ADMISSION", "table" => "hospital" ) ) );
			$check_dept = isset( $_GET[ 'check_dept_admission' ] ) ? $_GET[ 'check_dept_admission' ] : '';
			if( $dpet_set && $check_dept ){
				if( isset( $_POST[ 'department' ] ) ){
					if( $_POST[ 'department' ] ){
						$this->class_settings["department"] = $_POST[ 'department' ];
					}else{
						return array( "items" => array() );
					}
				}
			}
			
			if( isset( $this->class_settings["department"] ) && $this->class_settings["department"] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["department"]."` = '" . $this->class_settings["department"] . "' ";
			}
			
			if( isset( $this->table_fields["parish"] ) && isset( $_GET["parish"] ) && $_GET["parish"] ){
				$where .= " AND `".$this->table_name."`.`".$this->table_fields["parish"]."` = '" . $_GET["parish"] . "' ";
			}
			
			if( function_exists("get_hyella_has_independent_branches_settings") && get_hyella_has_independent_branches_settings() ){
				$branch = get_current_customer( "branch" );
				if( $branch ){
					if( isset( $this->branch_field ) && $this->branch_field && isset( $this->table_fields[ $this->branch_field ] ) ){
						$where .= " AND `".$this->table_name."`.`". $this->table_fields[ $this->branch_field ] ."` = '" . $branch . "' ";
					}
				}
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ". $join ." WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields["firstname"]."`, `".$this->table_name."`.`".$this->table_fields["lastname"]."` ".$limit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			$all_items = execute_sql_query($query_settings);
			
			if( isset( $this->class_settings["return_data"] ) && $this->class_settings["return_data"] ){
				return $all_items;
			}
			
			$return = array( "items" => array() );
			
			if( ! empty( $all_items ) && is_array( $all_items ) ){
				foreach( $all_items as $sval ){
					$name = $sval["firstname"] . ' '. $sval["lastname"];
					
					$return["items"][] = array( "id" => $sval["id"], "serial_num" => $sval["serial_num"], "text" => $name . ' - ' . strtoupper( $sval["ref_no"] ), "name" => $name, "phone" => $sval["phone_number"], "email" => $sval["email"] );
				}
			}
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}
		
		protected function _search_form2(){
			$where = "";
			
			if( isset( $_SESSION[ $this->table_name ] ) && isset( $_SESSION[ $this->table_name ][ 'filter_keep' ] ) && isset( $_SESSION[ $this->table_name ][ 'filter_keep' ][ 'where' ] ) && $_SESSION[ $this->table_name ][ 'filter_keep' ][ 'where' ] ){
				$where = $_SESSION[ $this->table_name ][ 'filter_keep' ][ 'where' ];
			}
			
			$key = 'firstname';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` regexp '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'lastname';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` regexp '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'email';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` regexp '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'phone_number';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` regexp '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'division';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'department';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'status';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'grade_level';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'category';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'role';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'type';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}

		protected function _display_all_records_full_view2(){
			/* $this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$returning_html_data = $this->_get_html_view();
			$this->class_settings[ "custom_edit_button" ] = $returning_html_data;
			 */
			 
			$where = "";
			$show_form = 1;
			$this->class_settings[ "return_new_format" ] = 1;

			$status = isset( $_GET[ 'status' ] ) ? $_GET[ 'status' ] : '';
			
			if( ! get_capture_user_passphrase_settings() ){
				unset( $this->basic_data["more_actions"]["passphrase"] );
			}
			
			$this->class_settings[ "add_empty_select_option" ] = 1;
			
			if( isset( $this->class_settings["button_params"] ) && $this->class_settings["button_params"] ){
				$this->datatable_settings["button_params"] = $this->class_settings["button_params"];
			}
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "firstname":
				//case "sex":
				case "type":
				case "lastname":
				case "phone_number":
				case "email":
				case "role":
				case "grade_level":
				case "category":
				case "division":
				case "department":
				case "status":
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
				break;
				default:
					$this->class_settings["hidden_records"][$val] = 1;
				break;
				}
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_nominal_roll_front":
			case "view_nominal_roll":
			case "display_all_employees_records":
			case "display_all_employees_records_inactive":
			case "display_all_employees_records_front":
			case "display_all_employees_records_inactive_front":
				$this->datatable_settings["show_edit_password_button"] = 0;
				$this->datatable_settings["show_edit_passphrase_button"] = 0;
				$this->datatable_settings["show_delete_button"] = 0;
				
				$this->label = 'Nominal Roll';
				
				switch( $this->class_settings["action_to_perform"] ){
				case "display_all_employees_records_inactive":
				case "display_all_employees_records_inactive_front":
					$this->label = 'List of In-active Employees';
					
					$where = " AND `".$this->table_name."`.`".$this->table_fields["status"]."` = 'in_active' ";
				break;
				case "display_all_employees_records":
				case "display_all_employees_records_front":
					$this->label = 'List of Active Employees';
					
					$where = " AND `".$this->table_name."`.`".$this->table_fields["status"]."` = 'active' ";
				break;
				}
				
				switch( $this->table_name ){
				case "users":
					$this->class_settings["hidden_records"][ $this->table_fields["role"] ] = 1;
				break;
				}
			break;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_nominal_roll_front":
				$this->class_settings["frontend"] = 1;
			break;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_nominal_roll_front":
			case "view_nominal_roll":
			case "display_all_employees_records_front":
			case "display_all_employees_records":
			case "display_all_employees_records_inactive":
			case "display_all_employees_records_inactive_front":
				if( function_exists("get_hyella_has_independent_branches_settings") && get_hyella_has_independent_branches_settings() ){
					$branch = get_current_customer( "branch" );
					if( $branch ){
						if( isset( $this->branch_field ) && $this->branch_field && isset( $this->table_fields[ $this->branch_field ] ) ){
							$where .= " AND `".$this->table_name."`.`". $this->table_fields[ $this->branch_field ] ."` = '" . $branch . "' ";
						}
					}
				}
			break;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "view_nominal_roll_front":
			case "view_nominal_roll":
				$this->datatable_settings["show_add_new"] = 0;
				$this->datatable_settings["show_edit_button"] = 0;
			break;
			}
			
			if( isset( $_GET[ 'type' ] ) && $_GET[ 'type' ] ){
				switch( $_GET[ 'type' ] ){
				case 'surgeons':
					$ob = new cOperation_booking();
					$ob->class_settings = $this->class_settings;

					$this->class_settings[ 'filter' ][ 'join' ] = " JOIN `". $this->class_settings[ 'database_name' ] ."`.`". $ob->table_name ."` ON `". $this->table_name ."`.`id` REGEXP `". $ob->table_name ."`.`". $ob->table_fields[ 'surgeons' ] ."` ";

					$this->class_settings[ 'filter' ][ 'join_count' ] = $this->class_settings[ 'filter' ][ 'join' ];
					$this->class_settings[ 'filter' ][ 'group' ] = " GROUP BY `". $this->table_name ."`.`id` ";
					$show_form = 0;
				break;
				}
			}
			
			if( $status ){
				switch( $status ){
				case 'pending':
					// print_r( $this->class_settings[ 'user_id' ] );exit;
					$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'status' ] ."` = '". $status ."' AND `". $this->table_name ."`.`modified_by` <> '". $this->class_settings[ 'user_id' ] ."' ";
					
					$this->datatable_settings[ 'show_refresh_cache' ] = 0;
					$this->datatable_settings[ 'show_add_new' ] = 0;
					$this->datatable_settings[ 'show_edit_button' ] = 0;
					$this->datatable_settings[ 'show_delete_button' ] = 0;
					$ma = $this->basic_data[ 'more_actions' ];
					$this->basic_data[ 'more_actions' ] = array();
					$this->basic_data[ 'more_actions' ]['activate_acc'] = $ma[ 'activate_acc' ];
					
					$show_form = 0;
					$this->class_settings[ "full_table" ] = 1;
				break;
				}
			}else{
				//$where .= " AND `". $this->table_name ."`.`". $this->table_fields[ 'status' ] ."` IN ( 'active', 'in_active' ) ";
				unset( $this->basic_data[ 'more_actions' ][ 'activate_acc' ] );
			}

			if( defined("HYELLA_DIABLE_USER_PASSWORD_CHANGE") && HYELLA_DIABLE_USER_PASSWORD_CHANGE ){
				unset( $this->basic_data['more_actions']['password'] );
			}

			// print_r($this->basic_data);
			
			
			if( $where ){
				$this->class_settings[ 'filter' ] = array();
				$this->class_settings[ 'filter' ][ 'where' ] = $where;
				$_SESSION[ $this->table_name ][ 'filter_keep' ][ 'where' ] = $where;
			}
			
			if( $show_form ){
				$this->class_settings[ "show_form" ] = $show_form;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
			}
			return $this->_display_all_records_full_view();
		}
		
		protected function _display_app_view2(){
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$hidden_fields = array();
			
			switch( $action_to_perform ){
			case "display_app_manager_users":
				$hidden_fields = array( "password" => "NOT NULL", "status" => "active" );
				$this->label = 'Users';
				$this->class_settings[ 'data' ][ 'new_button_todo' ] = 'new_user_popup';
			break;
			case "display_app_manager_personnel":
				$hidden_fields = array( "date_employed" => "NOT NULL", "status" => "active" );
				$this->label = 'Personnel';
				$this->class_settings[ 'data' ][ 'new_button_todo' ] = 'new_personnel_popup';
			break;
			}
			
			switch( $action_to_perform ){
			case "display_app_manager_users":
			case "display_app_manager_personnel":
				
				if( isset( $_POST["id"] ) && $_POST["id"] && $_POST["id"] != '-' ){
					$hidden_fields[ "parish" ] = $_POST["id"];
					$this->class_settings[ 'data' ][ 'reference_value' ] = $_POST["id"];
				}
				
				$this->class_settings[ 'data' ][ 'reference' ] = 'parish';
			break;
			}
			
			switch( $action_to_perform ){
			case "display_app_manager_users":
			case "display_app_manager_personnel":
				//$this->class_settings[ 'data' ][ 'hide_new_button' ] = 1;
				$this->class_settings["action_to_perform"] = 'display_app_manager';
			break;
			}
			
			$this->class_settings["hidden_table_fields"] = $hidden_fields;
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
		}
		
		protected function _save_app_changes2(){
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//check for project details & update
				
			break;
			case 'save_edit_popup_form_status':
				$this->class_settings["action_to_perform"] = 'save_new_popup';
			break;
			}
			
			$return = $this->_save_app_changes();
			
			switch( $action_to_perform ){
			case 'save_edit_popup_form_status':
				if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
					$err = new cError('010011');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = '_resend_verification_email';
					$err->additional_details_of_error = '<h4>Successful Status Update</h4>';
					$r = $err->error();
					
					$r["status"] = "new-status";
					$r["html_replacement_selector"] = "#modal-replacement-handle";
					$r["html_replacement"] = $r["html"];
					
					unset( $r["html"] );
					
					return $r;
				}
			break;
			case 'save_new_popup':
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$this->class_settings["skip_link"] = 1;
			//$this->label = 'Employee';
			
			$this->class_settings[ "hidden_records" ][ $this->table_fields["password"] ] = 1;
			
			if( isset( $this->table_fields["passphrase"] ) ){
				$this->class_settings[ "hidden_records" ][ $this->table_fields["passphrase"] ] = 1;
			}
			
			if( isset( $this->table_fields["confirmpassword"] ) )$this->class_settings[ "hidden_records" ][ $this->table_fields["confirmpassword"] ] = 1;
			if( isset( $this->table_fields["oldpassword"] ) )$this->class_settings[ "hidden_records" ][ $this->table_fields["oldpassword"] ] = 1;

			switch( $this->table_name ){
			case 'users':
				
				$this->class_settings[ "hidden_records" ][ $this->table_fields["role"] ] = 1;
			break;
			}
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			$action_to_perform = $this->class_settings['action_to_perform'];
			
			switch( $action_to_perform ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					$this->class_settings["modal_title"] = 'Edit: ' . get_name_of_referenced_record( array( "id" => $_POST["id"], "table" => "users" ) );
				}
				
				$this->class_settings["override_defaults"] = 1;
				
				if( isset( $this->table_fields["parish"] ) ){
					$this->class_settings[ "hidden_records" ][ $this->table_fields["parish"] ] = 1;
				}
				
				if( get_show_human_resource_settings() ){
					$this->class_settings[ "hidden_records" ][ $this->table_fields["rank"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["rank_text"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["department"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["grade_level"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["category"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["qualification"] ] = 1;
					
					$this->class_settings[ "hidden_records" ][ $this->table_fields["status"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["reason"] ] = 1;
					$this->class_settings[ "hidden_records" ][ $this->table_fields["division"] ] = 1;
				}
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["customer"] ] = 1;
				
				$this->class_settings[ "hidden_records" ][ $this->table_fields["role"] ] = 1;
				$this->class_settings['form_submit_button'] = 'Update '.$this->label.' &rarr;';
			break;
			case 'edit_popup_form_status':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Update Status ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					$this->class_settings["modal_title"] = 'Update Status: ' . get_name_of_referenced_record( array( "id" => $_POST["id"], "table" => ( isset($this->customer_source) && $this->customer_source ? $this->customer_source : $this->table_name) ) );
				}
				
				foreach( $this->table_fields as $k => $v ){
					$this->class_settings[ "hidden_records" ][ $v ] = 1;
				}
				
				unset( $this->class_settings[ "hidden_records" ][ $this->table_fields["status"] ] );
				if( isset( $this->table_fields["reason"] ) ){
					unset( $this->class_settings[ "hidden_records" ][ $this->table_fields["reason"] ] );
					
					$this->class_settings[ 'special_element_class' ][ $this->table_fields["reason"] ] = ' form-element-required-field ';
					$this->class_settings[ 'disable_form_element' ][ $this->table_fields["reason"] ] = ' required="required" ';
				}
				//form-element-required-field
				
				switch( $this->table_name ){
				case 'users':
					$this->class_settings[ "hidden_records" ][ $this->table_fields["role"] ] = 1;
				break;
				}
				
				$this->class_settings['form_submit_button'] = 'Update '.$this->label.' Status &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_' . $this->class_settings['action_to_perform'];
			break;
			default:
				switch( $action_to_perform ){
				case "new_popup_form":
					if( isset( $_GET["small_screen"] ) && $_GET["small_screen"] ){
						$this->class_settings[ "hidden_records" ][ $this->table_fields["data"] ] = 1;
						unset( $this->class_settings[ "hidden_records" ][ $this->table_fields["password"] ] );
			
						if( isset( $this->table_fields["confirmpassword"] ) ){
							unset( $this->class_settings[ "hidden_records" ][ $this->table_fields["confirmpassword"] ] );
						}
					}
				break;
				}
				
				switch( get_package_option() ){
				case "catholic":
					
					switch( $action_to_perform ){
					case 'new_user_popup':
					case 'new_personnel_popup':
						
						if( function_exists("get_hyella_has_independent_branches_settings") && get_hyella_has_independent_branches_settings() ){
							$branch = get_current_customer( "branch" );
							if( $branch ){
								if( isset( $this->branch_field ) && $this->branch_field && isset( $this->table_fields[ $this->branch_field ] ) ){
									$this->class_settings[ 'form_values_important' ][ $this->table_fields[ $this->branch_field ] ] = $branch;
									
									$this->class_settings[ 'hidden_records_css' ][ $this->table_fields[ $this->branch_field ] ] = 1;
								}
							}
						}
						
						$this->class_settings[ 'form_values_important' ][ $this->table_fields["status"] ] = 'active';
						$this->class_settings[ 'hidden_records_css' ][ $this->table_fields["status"] ] = 1;
					break;
					}
					
					switch( $action_to_perform ){
					case 'new_user_popup':
						$this->label = 'User Account';
			
						$this->class_settings[ "hidden_records" ] = array();
						unset( $this->class_settings[ "hidden_records_css" ][ $this->table_fields["role"] ] );
						
						$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
						
						foreach( $this->table_fields as $k => $v ){
							switch( $k ){
							case "parish":
							case "confirmpassword":
							case "password":
							case "role":
							case "firstname":
							case "lastname":
							case "email":
							case "phone":
							case "photograph":
							case "status":
							break;
							default:
								$this->class_settings[ "hidden_records" ][ $v ] = 1;
							break;
							}
						}
					break;
					case 'new_personnel_popup':
						foreach( $this->table_fields as $k => $v ){
							switch( $k ){
							case "push_notification_id":
							case "passphrase":
							case "oldpassword":
							case "confirmpassword":
							case "password":
							//case "role":
								$this->class_settings[ "hidden_records" ][ $v ] = 1;
							break;
							case "date_employed":
								$this->class_settings[ "form_extra_options" ][ $v ]['required_field'] = 'yes';
								$this->class_settings[ "form_values_important" ][ $v ] = date("U");
							break;
							case "status":
								$this->class_settings[ "form_values_important" ][ $v ] = "active";
							break;
							default:
							break;
							}
						}
						
					break;
					}
					
				break;
				default:
					/* if( get_show_human_resource_settings() ){
						$this->class_settings[ "hidden_records" ][ $this->table_fields["rank_text"] ] = 1;
						
						//check last active user details
						
						$r = $this->_last_user_check();
						if( $r ){
							$err = new cError('010014');
							$err->action_to_perform = 'notify';
							$err->html_format = 2;
							$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
							$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
							$err->additional_details_of_error = $r;
							return $err->error();
						}
						
					} */
				break;
				}
				
			break;
			}
			
			
			return $this->_new_popup_form();
		}
		
		protected function _search_customer_call_log2(){
			$where = "";
			$filename = 'item-list.php';
			$title = '';
			$filter = 0;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'search_list2':
				$filter = 1;
				$where = " AND `".$this->table_name."`.`".$this->table_fields[ "role" ]."` != '1300130013' ";
			case 'search_list':
				if( isset( $this->class_settings[ "where" ] ) && $this->class_settings[ "where" ] ){
					$where .= $this->class_settings[ "where" ];
				}
			break;
			default:
				if( ! ( isset( $_POST[ $this->default_reference ] ) && $_POST[ $this->default_reference ] ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid '.ucwords( $this->customer_source ).'</h4>Please select a '.$this->customer_source.' first';
					return $err->error();
				}
				
				$where = " AND `".$this->table_name."`.`".$this->table_fields[ $this->default_reference ]."` = '".$_POST[ $this->default_reference ]."' ";
			break;
			}
			
			$allow = 0;
				
			if( $filter && isset( $_POST[ "field" ] ) && $_POST[ "field" ] ){
				
				$field = $_POST[ "field" ];
				
				switch( $field ){
				case "_most_recent":
					
					if( isset( $_POST[ "search_number" ] ) && intval( $_POST[ "search_number" ] ) ){
						$limit = intval( $_POST[ "search_number" ] );
						$allow = 1;
						
						$this->class_settings["limit"] = " LIMIT " . $limit;
						$title = "Most Recent ".$limit." Record(s)";
					}else{
						$where = '';	//prevents requesting for all records
					}
					
				break;
				default:
				
					if( isset( $this->table_fields[ $_POST[ "field" ] ] ) ){
						
						$val = $this->table_fields[ $_POST[ "field" ] ];
						$t = $this->table_name;
						$lbl = $t();
						
						if( isset( $lbl[ $val ][ "form_field" ] ) ){
							
							switch( $lbl[ $val ][ "form_field" ] ){
							case "date-5":
								
								if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
									$where .= " AND `".$val."` >= " . convert_date_to_timestamp( $_POST["start_date"], 1 );
								}
								
								if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
									$where .= " AND `".$val."` <= " . convert_date_to_timestamp( $_POST["end_date"], 1 );
								}
								
								if( ! $where )$allow = 1;
							break;
							default:
								
								if( isset( $_POST[ "search" ] ) ){
									if( $_POST[ "search" ] ){
										$where = " AND `".$this->table_name."`.`".$val."` REGEXP '".$_POST[ "search" ]."' ";
									}else{
										
										if( isset( $_POST[ "search_select" ] ) && $_POST[ "search_select" ] ){
											$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_select" ]."' ";
										}else{
											if( isset( $_POST[ "search_number" ] ) && $_POST[ "search_number" ] ){
												$where = " AND `".$this->table_name."`.`".$val."` = '".$_POST[ "search_number" ]."' ";
											}else{
												$allow = 1;
											}
										}
										
									}
								}
							break;
							}
						}
					}
					
				}
			
				if( ! ( $where || $allow ) ){
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Invalid Search Criteria</h4>Please try again';
					return $err->error();
				}
				
				unset( $_POST["start_date"] );
				unset( $_POST["end_date"] );
			}
			
			$hidden_field = array();
			if( isset( $_POST["hidden_field"] ) && is_array( $_POST["hidden_field"] ) && ! empty( $_POST["hidden_field"] ) ){
				foreach( $this->table_fields as $k => $v ){
					if( isset( $_POST["hidden_field"][ $k ] ) ){
						
						switch( $_POST["hidden_field"][ $k ] ){
						case "NULL":
							$where .= " AND ( `".$this->table_fields[ $k ]."` IS NULL OR `".$this->table_fields[ $k ]."` = '' ) ";
						break;
						case "NOT NULL":
							$where .= " AND ( `".$this->table_fields[ $k ]."` IS NOT NULL AND `".$this->table_fields[ $k ]."` != '' ) ";
						break;
						default:
							$where .= " AND `".$this->table_fields[ $k ]."` = '". $_POST["hidden_field"][ $k ] ."' ";
						break;
						}
						
					}
				}
				
				$kk = "start_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$kk = "end_date";
				if( isset( $_POST["hidden_field"][ $kk ] ) && $_POST["hidden_field"][ $kk ] ){
					$_POST[ $kk ] = $_POST["hidden_field"][ $kk ];
				}
				
				$hidden_field = $_POST["hidden_field"];
			}
			
			if( isset( $_POST["start_date"] ) && $_POST["start_date"] ){
				$this->class_settings["start_date"] = convert_date_to_timestamp( $_POST["start_date"], 1 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
				else $where = " AND `".$this->table_fields["date"]."` >= " . $this->class_settings["start_date"];
			}
			
			if( isset( $_POST["end_date"] ) && $_POST["end_date"] ){
				$this->class_settings["end_date"] = convert_date_to_timestamp( $_POST["end_date"], 2 );
				if( $where )$where .= " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
					else $where = " AND `".$this->table_fields["date"]."` <= " . $this->class_settings["end_date"];
			}
			
			$this->class_settings["where"] = $where;
			$data = $this->_get_all_customer_call_log();
			
			if( isset( $this->class_settings['return_data'] ) && $this->class_settings['return_data'] ){
				return $data;
			}
			
			if( empty( $data ) ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>No '.ucwords( $this->label ).' Record(s)</h4>';
				$return = $err->error();
				$returning_html_data = $return["html"];
			}else{
				$this->class_settings[ 'data' ][ "table" ] = $this->table_name;
				$this->class_settings[ 'data' ][ "items" ] = $data;
				
				$tb = $this->table_name;
				$this->class_settings[ 'data' ][ 'report_title' ] = $title;
				$this->class_settings[ 'data' ][ 'table_fields' ] = $this->table_fields;
				$this->class_settings[ 'data' ][ 'table_labels' ] = $tb();
				$this->class_settings[ 'data' ][ 'action' ] = $action_to_perform;
				$this->class_settings[ 'data' ][ 'hidden_field' ] = $hidden_field;
				
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				
				if( isset( $this->use_package ) && $this->use_package ){
					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
				
				$returning_html_data = $this->_get_html_view();
			}
			
			$return = array();
			
			$return["status"] = "new-status";						
			$return["html_replacement_selector"] = "#".$this->table_name."-record-search-result";
			$return["html_replacement"] = $returning_html_data;
			$return["javascript_functions"] = array( "set_function_click_event" );
			
			return $return;
		}
		
		private function _app_check_logged_in_user(){
			$js = array();

			$defaultLogin = 'index.html';
			$defaultLogin = isset( $_GET[ 'HYELLA_DEFAULT_LOGIN' ] ) && $_GET[ 'HYELLA_DEFAULT_LOGIN' ]  ? $_GET[ 'HYELLA_DEFAULT_LOGIN' ] : $defaultLogin;

			$returned_data = array(
				"status" => "new-status",
				"redirect_url" => $defaultLogin,
				//"redirect_url" => $this->class_settings[ 'project_data' ][ "domain_name" ],
			);
			if( defined( "NWP_FILES_PATH" ) && NWP_FILES_PATH ){
				$returned_data["redirect_url"] = NWP_FILES_PATH;
			}
			$fparams = isset( $_POST[ 'formParams' ] ) && $_POST[ 'formParams' ] ? json_decode( $_POST[ 'formParams' ], 1 ) : [];

			$main_url = "main.html";
			if( defined( "HYELLA_DEFAULT_LOCATION" ) && HYELLA_DEFAULT_LOCATION ){
				$main_url = HYELLA_DEFAULT_LOCATION;
			}
			$store = get_current_store(1);
			
			if( $store ){
				if( defined( "HYELLA_DEFAULT_LOCATION_2" ) && HYELLA_DEFAULT_LOCATION_2 ){
					$main_url = HYELLA_DEFAULT_LOCATION_2;
				}
			}
			
			$f = "$.fn.pHost.displayUserDetails";
			switch( $this->class_settings["action_to_perform"] ){
			case 'app_check_logged_in_user5':
				$f = "$.fn.pHost.displayUserDetails4";
			break;
			case 'app_check_logged_in_user4':
				$f = "$.fn.pHost.displayUserDetails3";
			break;
			case 'app_check_logged_in_user3':
				$f = "$.fn.pHost.displayUserDetails2";
			break;
			case 'app_check_logged_in_user2':
				$returned_data = array(
					"status" => "new-status",
					'html_replacement_selector' => "#registered-company",
					'html_replacement' => $this->class_settings[ 'project_data' ][ "company_name" ],
					
					'html_replacement_selector_one' => '#logo-container',
					'html_replacement_one' => '<img src="'.$this->class_settings[ 'project_data' ][ "domain_name" ].'logo-b.png" />',
				);
			break;
			}
			
			$key = md5('ucert'.$_SESSION['key']);
			
			// print_r( $returned_data );exit;
			if( isset($_SESSION[$key]) ){
				$user_details = $_SESSION[$key];
				//print_r($user_details); exit;
				$user_info = $user_details;
				
				$js[] = $f;
				$returned_data = array(
					"success" => 1,
					"status" => "new-status",
					"user_details" => $user_details,
					"javascript_functions" => $js,
				);
				
				$main_url = isset( $_GET[ 'HYELLA_DEFAULT_LOCATION' ] ) && $_GET[ 'HYELLA_DEFAULT_LOCATION' ]  ? $_GET[ 'HYELLA_DEFAULT_LOCATION' ] : $main_url;
				$main_url = isset( $fparams[ 'HYELLA_DEFAULT_LOCATION' ] ) && $fparams[ 'HYELLA_DEFAULT_LOCATION' ] ? $fparams[ 'HYELLA_DEFAULT_LOCATION' ] : $main_url;
				
				switch( $this->class_settings["action_to_perform"] ){
				case 'app_check_logged_in_user2':
					
					$returned_data = array(
						"success" => 1,
						"status" => "new-status",
						"redirect_url" => $main_url,
					);
					
				break;
				}
				
				if( defined( "HYELLA_NO_FRONTEND_UPDATE" ) ){
					if( HYELLA_NO_FRONTEND_UPDATE ){
						$returned_data["html_removal"] = "#update-app-container";
					}
				}
				
				//force notification
				$nb = 'cNwp_client_notification';
				if( class_exists( $nb ) ){
					$nb = new $nb();
					$nb->force_client_notification( $this->table_name );
				}
				
				if( defined("HYELLA_BACKEND_ACCESS_TABLES") && HYELLA_BACKEND_ACCESS_TABLES ){
					$bat = explode(",", HYELLA_BACKEND_ACCESS_TABLES );
					if( ! empty( $bat ) && is_array( $bat ) ){
						if( isset( $user_info["table"] ) && $user_info["table"] && ! in_array( $user_info["table"], $bat ) ){
							
							if( defined("HYELLA_EXTERNAL_ENDPOINT") && HYELLA_EXTERNAL_ENDPOINT ){
								return array(
									"success" => 1,
									"status" => "new-status",
									"redirect_url" => HYELLA_EXTERNAL_ENDPOINT,
								);
							}
							return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>" ) );
						}
					}
				}
			}

			if( function_exists( "get_app_version" ) ){
				$returned_data["html_replacement_two"] = get_app_version( $this->class_settings[ 'calling_page' ] );
				$returned_data["html_replacement_selector_two"] = "#year-of-app";
			}

			$returned_data[ 'javascript_functions' ] = $js;
			
			return $returned_data;
		}
		
		private function _authenticate_app(){
			$callback = isset( $_GET[ 'callback' ] ) && $_GET[ 'callback' ] ? $_GET[ 'callback' ] : '';

			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$otp = 0;
			if( defined( 'HYELLA_V3_OTP_ON_SIGNIN' ) && HYELLA_V3_OTP_ON_SIGNIN ){
				$otp = 1;
			}

			if( isset( $this->class_settings[ 'no_otp' ] ) && $this->class_settings[ 'no_otp' ] ){
				$otp = 0;
			}
			
			$error_type = 'error';
			$error_msg = '';
			$table = isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ? $_POST[ 'table' ] : 'users';
			
			$fparams = isset( $_POST[ 'formParams' ] ) && $_POST[ 'formParams' ] ? json_decode( $_POST[ 'formParams' ], 1 ) : [];
			
			if( ( isset( $_POST["email"] ) || isset( $_POST["username"] ) ) && isset( $_POST["password"] ) ){
				$email = trim( ( isset( $_POST["email"] ) && $_POST["email"] )?$_POST["email"]:'' );
				$email_key = 'email';
				if( ! $email ){
					$email = ( isset( $_POST["username"] ) && $_POST["username"] )?$_POST["username"]:'';
					$email_key = 'username';
				}
				
				$pass = $_POST["password"];
			}else{
				$error_msg = "Please provide valid email & password";
			}

			if( ! $error_msg ){
				//Login New User
				$authentication = new cAuthentication();
				$authentication->class_settings = $this->class_settings;
				
				$authentication->class_settings["table"] = $table;
				$authentication->class_settings["username_key"] = $email_key;
				$authentication->class_settings["username"] = $email;
				if( isset( $this->class_settings[ 'password_raw' ] ) && $this->class_settings[ 'password_raw' ] ){
					$authentication->class_settings["password"] = $pass;
				}else{
					$authentication->class_settings["password"] = md5( $pass . get_websalter() );
				}
				$authentication->class_settings["upassword"] = $pass;

				if( $otp ){
					$authentication->class_settings["do_not_set_session"] = 1;
				}
				
				$authentication->class_settings["action_to_perform"] = 'confirm_username_and_password';
				$returned_data  = $authentication->authentication();
				// print_r( $returned_data );exit;
				
				if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){

					$access = get_accessed_functions();
					$super = 0;
					if( ! is_array( $access ) && $access == 1 ){
						$super = 1;
					}

					if( $super ){
						$otp = 0;
					}

					if( defined( 'HYELLA_V3_OTP_ON_SIGNIN_VALIDITY' ) && HYELLA_V3_OTP_ON_SIGNIN_VALIDITY && $returned_data[ 'user_details' ][ 'user_id' ] ){
						$this->class_settings[ 'current_record_id' ] = $returned_data[ 'user_details' ][ 'user_id' ];
						$re = $this->_get_record();
						$rd = isset( $re[ 'data' ] ) ? json_decode( $re[ 'data' ], 1 ) : [];

						if( isset( $rd[ 'otp' ][ 'status' ] ) && $rd[ 'otp' ][ 'status' ] !== 'unused' ){
							$tt = HYELLA_V3_OTP_ON_SIGNIN_VALIDITY * 60 * 60; //convert to seconds
							$tnow = time();
							// print_r( time() . '------------' );
							// print_r( $tt + $rd[ 'otp' ][ 'date' ] );exit;

							if( isset( $rd[ 'otp' ][ 'date' ] ) ){
								if( $tnow < ( $tt + $rd[ 'otp' ][ 'date' ] ) ){
									$dotp = isset( $fparams[ 'device_otp' ] ) ? $fparams[ 'device_otp' ] : '';
									$procd = 0;
									if( $dotp && $dotp === md5( $rd[ 'otp' ][ 'value' ] ) ){
										$procd = 1;
									}

									if( $procd && defined( 'OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT' ) && OTP_SIGNIN_VALIDITY_CHECK_FINGERPRINT ){
										$procd = 0;
										if( isset( $fparams[ 'd2' ] ) && $fparams[ 'd2' ] ){
											if( isset( $rd[ 'otp' ][ 'device_id' ] ) && $rd[ 'otp' ][ 'device_id' ] ){
												if( md5( $rd[ 'otp' ][ 'device_id' ] ) == $fparams[ 'd2' ] ){
													$procd = 1;
												}
												// code...
											}else{
												// $error_msg = "Access Denied. Failed Data Integrity Check";
												// $audit_params["login_failed"] = 3; 
											}
										}else{
											$error_msg = "Missing Login Parameters. Please contact you administrators";
											$audit_params["login_failed"] = 3;
										}
									}

									if( $procd ){
										
										$a = new cAuthentication;
										$a->class_settings = $this->class_settings;
										$a->_set_session( $returned_data[ 'user_details' ] );

										$otp = 0;

									}
								}
							}
						}
					}
					//Get Logged in User Details
					$user_details = array();
					$key = md5('ucert'.$_SESSION['key']);
					if( $otp && isset( $returned_data[ 'user_details' ][ 'user_id' ] ) && $returned_data[ 'user_details' ] ){

						$this->class_settings[ 'current_record_id' ] = $returned_data[ 'user_details' ][ 'user_id' ];
						$ee = $this->_get_record();

						if( isset( $ee[ 'id' ] ) && $ee[ 'id' ] ){

							$rx = $this->_resend_otp( [ 'id' => $ee[ 'id' ], 'd1' => ( isset( $fparams[ 'd1' ] ) ? $fparams[ 'd1' ] : '' ) ] );

							if( isset( $rx[ 'typ' ] ) && $rx[ 'typ' ] == 'uerror' && isset( $rx[ 'msg' ] ) ){
								$error_msg = $rx[ 'msg' ];
							}else{

								$this->class_settings[ "data" ][ "fparams" ] = $fparams;
								$this->class_settings[ "data" ][ "table" ] = $table;
								$this->class_settings[ "data" ][ "user" ] = $ee;
								$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/otp-form.php' );
								$html = $this->_get_html_view();
								
								return array(
									'do_not_reload_table' => 1,
									'html_replacement' => $html,
									'html_replacement_selector' => "#sign-in-form-container",
									'method_executed' => $this->class_settings['action_to_perform'],
									'status' => 'new-status',
									'javascript_functions' => [ 'prepare_new_record_form_new', 'set_function_click_event' ],
								);

							}
						}else{
							$error_msg = "Unable to retrieve user record";
						}
					}elseif( isset($_SESSION[$key]) ){
						
						$user_details = $_SESSION[$key];
						
						$loc = get_default_location();
						$loc = isset( $_GET[ 'HYELLA_DEFAULT_LOCATION' ] ) && $_GET[ 'HYELLA_DEFAULT_LOCATION' ]  ? $_GET[ 'HYELLA_DEFAULT_LOCATION' ] : $loc;
						$loc = isset( $fparams[ 'HYELLA_DEFAULT_LOCATION' ] ) && $fparams[ 'HYELLA_DEFAULT_LOCATION' ] ? $fparams[ 'HYELLA_DEFAULT_LOCATION' ] : $loc;
						
						$key = md5( 'ucert' . $_SESSION['key'] );
						if( isset( $_SESSION[ $key ] ) ){
							$user_details = $_SESSION[ $key ];
							$user_info = $user_details;
							
							//get access_roles
							$allow_update = 0;
														
							if( ! $super ){
								$mys = 0;
								if( isset( $access[ "status" ]["stores"]["access_all_stores"] ) && $access[ "status" ]["stores"]["access_all_stores"] ){
									$mys = 1;
								}else if( isset( $access[ "status" ]["stores"]["access_my_store"] ) && $access[ "status" ]["stores"]["access_my_store"] ){
									$mys = 1;
								}
								
								if( $mys && isset( $user_info["id"] ) ){
									
									$ud = get_record_details( array( "id" => $user_info["id"], "table" => "users" ) );
									if( isset( $ud[ "country" ] ) && $ud[ "country" ] ){
										$_SESSION["store"] = $ud[ "country" ];
									}
								}
							}
							
							/* 
							$super = 0;
							$access = array();
							if( isset( $user_info["privilege"] ) && $user_info["privilege"] ){
								
								if( $user_info["privilege"] == "1300130013" ){
									$super = 1;
								}else{
									$functions = get_access_roles_details( array( "id" => $user_info["privilege"] ) );
									if( isset( $functions[ $user_info["privilege"] ]["accessible_functions"] ) ){
										$a = explode( ":::" , $functions[ $user_info["privilege"] ]["accessible_functions"] );
										if( is_array( $a ) && $a ){
											foreach( $a as $k => $v ){
												$access[ $v ] = $v;
											}
										}
									}
								}
							} */
							
							$key = 'allow_update_of_application'; //allow update
							if( ( isset( $access[ $key ] ) ||  $super ) ){
								$allow_update = 1;
							}
							
							$allow_update = 0;
							if( $allow_update ){
								$loc = "../engine/?activity=update";
								
								if( get_automatic_update_settings() && check_for_last_automatic_update( 1 ) ){
									$loc = "../engine/?activity=update&automatic=1";
								}
							}
						}
						
						if( defined("HYELLA_BACKEND_ACCESS_TABLES") && HYELLA_BACKEND_ACCESS_TABLES ){
							$bat = explode(",", HYELLA_BACKEND_ACCESS_TABLES );
							if( ! empty( $bat ) && is_array( $bat ) ){
								if( isset( $user_info["table"] ) && $user_info["table"] && ! in_array( $user_info["table"], $bat ) ){
									if( defined("HYELLA_EXTERNAL_ENDPOINT") && HYELLA_EXTERNAL_ENDPOINT ){
										return array(
											"status" => "new-status",
											"redirect_url" => HYELLA_EXTERNAL_ENDPOINT,
										);
									}
									return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>" ) );
								}
							}
						}

						switch( $action_to_perform ){
						case "authenticate_app_mobile":
							$callback = "hyellaMobile.finishLogin";
						case "authenticate_user":
							unset( $user_details["functions"] );
							$user_details["full_name"] = $user_details["lname"] . ' ' . $user_details["fname"];
							
							$d2["user_data"] = $user_details;
							
							$returned_data = array(
								"status" => "new-status",
								"data" => $d2,
								"javascript_functions" => array( $callback ),
							);
						break;
						default:
							$returned_data = array(
								"status" => "new-status",
								"redirect_url" => $loc,
							);
						break;
						}
						
						
						$audit_params["login_success"] = 1;
					}else{
						//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
						$error_msg = "Session initialization error please try again";
						
						$audit_params["login_failed"] = 2;
					}
				}else{
					//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
					$error_msg = "User could not be authenticated. Please provide valid email & password";
					if( isset( $returned_data["msg"] ) && $returned_data["msg"] ){
						$error_msg = $returned_data["msg"];
					}
					
					$audit_params["login_failed"] = 1;
				}
				
				$audit_params["login_user_id"] = $email;
				auditor("", "login", "login", $audit_params, $this->class_settings );
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "message" => $error_msg, "type" => $error_type ) );
			}

			return $returned_data;
		}
		
		private function _get_all_admin_users(){
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case 'email':
				case 'other_names':			
				case 'firstname':
				case 'lastname':
				case 'phone_number':
				case 'role':
				case 'ref_no':
				case 'grade_level':
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				break;
				}
			}
			
			$where = '';
			
			if( function_exists("get_hyella_has_independent_branches_settings") && get_hyella_has_independent_branches_settings() ){
				$branch = get_current_customer( "branch" );
				if( $branch ){
					if( isset( $this->branch_field ) && $this->branch_field && isset( $this->table_fields[ $this->branch_field ] ) ){
						$where .= " AND `".$this->table_name."`.`". $this->table_fields[ $this->branch_field ] ."` = '" . $branch . "' ";
					}
				}
			}
			
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status` = '1' AND `".$this->table_name."`.`". $this->table_fields["status"] ."` = 'active' " . $where;
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			return execute_sql_query($query_settings);
			
		}
		
		private function _save_update_profile(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			$return = $this->_save_changes2();
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/user-info.php' );
			
			unset( $this->class_settings[ 'do_not_check_cache' ] );
			$this->class_settings[ 'data' ]["user_info"] = $this->_get_customer_call_log();
			
			$returning_html_data = $this->_get_html_view();
			
			unset( $return["html"] );
			
			$return['html_replacement'] = $returning_html_data;
			$return['html_replacement_selector'] = "#user-info-page";
			$return['status'] = 'new-status';
			
			return $return;
		}
		
		private function _get_user_profile(){
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/user-info.php' );
			$this->class_settings[ 'data' ]["action_to_performed"] = $this->class_settings["action_to_perform"];
			$this->class_settings[ 'data' ]["labels"] = users();
			$this->class_settings[ 'data' ]["fields"] = $this->table_fields;
			$this->class_settings[ 'data' ]["user_info"] = $this->_get_customer_call_log();
			return $this->_get_html_view();
		}
		
		private function _display_my_profile_manager(){
			//DISPLAY BUDGET DETAILS FULL VIEW
			/*------------------------------*/
			
			$selector = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$selector = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			$filename = 'display-my-profile-manager';
			
			switch( $action_to_perform ){
			case 'view_my_profile_dashboard':
			case 'view_my_profile':
				$selector = "#user-info-page";
				$this->class_settings[ 'current_record_id' ] = $this->class_settings["user_id"];
				$returning_html_data = $this->_get_user_profile();
			break;
			//19-jun-23: 2echo
			default:
				switch ($action_to_perform) {
					case 'edit_my_signature':
						$selector = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $selector : "#user-info-page";
						$filename = 'signatures.php';
						$this->class_settings[ 'current_record_id' ] = $this->class_settings["user_id"];
						$this->class_settings['data']['user'] = $this->_get_record();
						$this->class_settings['data']['callback'] = isset($this->class_settings['callback']) && $this->class_settings['callback'] ? $this->class_settings['callback'] : [];
					
						if( isset($this->class_settings['data']['user']['id']) && $this->class_settings['data']['user']['id'] ){
							unset( $this->class_settings['data']['user']['password'] );
							unset( $this->class_settings['data']['user']['confirmpassword'] );
						}
					break;
					case 'display_my_profile_manager':	
						$this->class_settings[ 'current_record_id' ] = $this->class_settings["user_id"];
					default:
						$this->class_settings[ 'data' ]['user_info_html'] = $this->_get_user_profile();
					break;
				}
				$this->class_settings[ 'data' ][ 'action_to_perform' ] = $action_to_perform;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/'.$filename );
				$returning_html_data = $this->_get_html_view();
			break;
			}
			
			return array(
				'html_replacement' => $returning_html_data,
				'html_replacement_selector' => $selector,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
			);
		}
		
		private function _generate_new_data_capture_form2(){
			$returning_html_data = array();
			
			$this->class_settings['form_class'] = 'activate-ajax';
			
			if( isset( $_GET["role_ids"] ) && $_GET["role_ids"] ){
				$this->class_settings['form_extra_options'][ $this->table_fields["role"] ]['attributes'] = ' action="?action=access_roles&todo=get_select2&role_type=users&role_ids='. implode( ",", explode( ":::", $_GET["role_ids"] ) ) .'" minlength="0" ';
			}
			
			if( ! isset( $this->class_settings['hidden_records'] ) ){
				foreach( array( 'user_type', 'data', 'oldpassword' ) as $us ){
					if( isset( $this->table_fields[$us ] ) ){
						$this->class_settings['hidden_records'][ $this->table_fields[ $us ] ] = 1;
					}
				}
			}
			
			if( isset( $this->table_fields["passphrase"] ) ){
				$this->class_settings[ "hidden_records" ][ $this->table_fields["passphrase"] ] = 1;
				/* if( get_capture_user_passphrase_settings() ){
					unset( $this->class_settings[ "hidden_records" ][ $this->table_fields["passphrase"] ] );
				} */
			}
			
			switch ( $this->class_settings['action_to_perform'] ) {
				case 'create_new_record':
					if( defined('NIN_VERIFICATION_ON_ACCOUNT_CREATION') && NIN_VERIFICATION_ON_ACCOUNT_CREATION ){
						$this->class_settings['custom_fields'] = array(
							'nin' => array(
								'field_label' => 'NIN',
								'required_field' => 'yes',
								'form_field' => 'password',
								'class' => ' not-password ',
								'attributes' => ' pattern="\\d{11}" ',
								'title' => " NIN must be 11 digits ",
								'display_position' => 'display-in-table-row',
								'serial_number' => 10.2,
							),
							'3_uvh' => array(
								'field_label' => 'Hidden',
								'required_field' => 'yes',
								'form_field' => 'number',
								'class' => '',
								'attributes' => '',
								'display_position' => 'display-in-table-row',
								'serial_number' => 18.2,
							)
						);

						$this->class_settings['hidden_records_css'][ '3_uvh' ] = 1;
						$this->class_settings['form_values_important'][ '3_uvh' ] = 1;

						foreach ( $this->table_fields as $key => $value) {
							switch ( $key ) {
								case 'firstname':
								case 'lastname':
								case 'email':
								case 'sex':
									$this->class_settings['hidden_records'][ $value ] = 1;
								break;
							}
						}
					}
				break;
			}

			$show_headings = 0;
			$apply_profile_create = 0;

			switch ( $this->class_settings['action_to_perform'] ){
				case 'edit':
					$show_headings = 1;
					
					$this->class_settings['hidden_records'][ $this->table_fields['password'] ] = 1;
					$this->class_settings['hidden_records'][ $this->table_fields['confirmpassword'] ] = 1;
					
					if( isset( $this->table_fields['passphrase'] ) ){
						$this->class_settings['hidden_records'][ $this->table_fields['passphrase'] ] = 1;
					}
					
					$apply_profile_create = 1;
				break;
				case 'edit_my_profile':
					$_POST["id"] = $this->class_settings["user_id"];
					$_POST["mod"] = 'edit-' . md5( $this->table_name );
					$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_change_my_profile';
					unset( $_GET[ 'id' ] );
					
					//19-jun-23: 2echo
					foreach( $this->table_fields as $key => $val ){
						switch( $key ){
						case "firstname":
						case "lastname":
						case "other_names":
						case "email":
						case "phone_number":
						case "address":
						case "photograph":
						case "date_of_birth":
						case "username":
							if( defined( "EDIT_PROFILE_USERS_HIDE_" . strtoupper( $key ) ) && constant( "EDIT_PROFILE_USERS_HIDE_" . strtoupper( $key ) ) ){
								$this->class_settings['hidden_records'][ $val ] = 1;
							}else if( defined( "EDIT_PROFILE_USERS_" . strtoupper( $key ) ) ){
								$this->class_settings['form_display_not_editable_value'][ $val ] = 1;
								if( constant( "EDIT_PROFILE_USERS_" . strtoupper( $key ) ) ){
									unset( $this->class_settings['form_display_not_editable_value'][ $val ] );
								}
							}
						break;
						default:
							$this->class_settings['hidden_records'][ $val ] = 1;
						break;
						}
					}
				break;
				case 'display_my_password_change':
				case 'edit_my_password':
					$_POST["id"] = $this->class_settings["user_id"];
					$_POST["mod"] = 'edit-' . md5( $this->table_name );
					$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=save_change_my_password';
					unset( $_GET[ 'id' ] );
					
					switch ( $this->class_settings['action_to_perform'] ){
					case 'display_my_password_change':
						$this->class_settings[ 'form_action' ] .= '&sign_out=1';
					break;
					}
					
					unset( $this->class_settings['hidden_records'][ $this->table_fields['oldpassword'] ] );
					foreach( $this->table_fields as $key => $val ){
						switch( $key ){
						case "oldpassword":
						case "confirmpassword":
							$this->class_settings['form_values_important'][ $val ] = '';
						break;
						case "password":
							$this->class_settings['form_extra_options'][ $val ]['field_label'] = 'New Password';
							$this->class_settings['form_values_important'][ $val ] = '';
						break;
						default:
							$this->class_settings['hidden_records'][ $val ] = 1;
						break;
						}
					}
				break;
				case 'edit_password':
					$_POST["mod"] = 'edit-' . md5( $this->table_name );
					
					foreach( $this->table_fields as $key => $val ){
						switch( $key ){
						case "password":
						case "confirmpassword":
						break;
						default:
							$this->class_settings['hidden_records'][ $val ] = 1;
						break;
						}
					}
				break;
				case 'edit_passphrase':
					$_POST["mod"] = 'edit-' . md5( $this->table_name );
					
					foreach( $this->table_fields as $key => $val ){
						switch( $key ){
						case "passphrase":
							unset( $this->class_settings['hidden_records'][ $val ] );
						break;
						default:
							$this->class_settings['hidden_records'][ $val ] = 1;
						break;
						}
					}
				break;
				default:
					$show_headings = 1;
					$apply_profile_create = 1;
					
					if( defined("USERS_NEW_ACCOUNT_WITH_DEFAULT_PASSWORD") && USERS_NEW_ACCOUNT_WITH_DEFAULT_PASSWORD && function_exists("get_default_password_settings") ){
						$upassword = get_default_password_settings();
						
						if( $upassword && isset( $this->table_fields["password"] ) && ! isset( $this->class_settings['hidden_records'][ $this->table_fields["password"] ] ) ){
							$this->class_settings['hidden_records'][ $this->table_fields["password"] ] = 1;
							$this->class_settings['hidden_records'][ $this->table_fields["confirmpassword"] ] = 1;
							
							$this->use_custom_fields = 1;
							$this->custom_fields = array_merge( array(
								'i_' => array(
									'field_label' => '',
									'raw_html' => '<b>NOTE: New accounts are given a default password of ['.$upassword.']</b><br><br>Users are forced to change this password after their first login',
									'form_field' => 'html',
									
									'display_position' => 'display-in-table-row',
									'serial_number' => 1,
								) ), $this->custom_fields );
						}
					}
				break;
			}

			if( $apply_profile_create ){
				//11-sep-23: 2echo
				foreach( $this->table_fields as $key => $val ){
					if( defined( "CREATE_PROFILE_USERS_HIDE_" . strtoupper( $key ) ) && constant( "CREATE_PROFILE_USERS_HIDE_" . strtoupper( $key ) ) ){
						$this->class_settings['hidden_records'][ $val ] = 1;
					}else if( defined( "CREATE_PROFILE_USERS_HIDE_" . strtoupper( $key ) ) ){
						$this->class_settings['form_display_not_editable_value'][ $val ] = 1;
						if( constant( "CREATE_PROFILE_USERS_HIDE_" . strtoupper( $key ) ) ){
							unset( $this->class_settings['form_display_not_editable_value'][ $val ] );
						}
					}
				}
			}

			/* if( defined("USERS_COUNTRY_LABEL") && USERS_COUNTRY_LABEL ){
				$this->class_settings['form_extra_options'][ $this->table_fields['country'] ]['field_label'] = USERS_COUNTRY_LABEL;
			} */
			
			if( ! $show_headings ){
				$this->class_settings["do_not_show_headings"] = 1;
			}
			$return = $this->_generate_new_data_capture_form();
			
			$handle = "#user-info-page";
			
			switch ( $this->class_settings['action_to_perform'] ){
				case 'display_my_password_change':
				
					if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
						$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
					}
					
					if( isset( $return["html"] ) && $return["html"] ){
						$return["html"] = '<div class="row"><div class="col-md-4 col-md-offset-4 well"><div class="note note-warning">Welcome '. ( isset( $this->class_settings["user_full_name"] )?$this->class_settings["user_full_name"]:'' ) .'!<br><h4>Change Your Password</h4>Change your password, sign-out and sign-in again to begin using the system</div>'. $return["html"] .'</div></div>';
					}
				break;
			}
			
			switch ( $this->class_settings['action_to_perform'] ){
				case 'display_my_password_change':
				case 'edit_my_password':
				case 'edit_my_profile':
					if( isset( $return["html"] ) && $return["html"] ){
						return array(
							'html_replacement' => $return["html"],
							'html_replacement_selector' => $handle,
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ) 
						);
					}
					//user-info-page
				break;
			}
			
			return $return;
		}

		private function _display_user_details(){
			
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = array();
			$processing_status = $this->_save_changes2();
			
			if( is_array( $processing_status ) && !empty( $processing_status ) ){
				$result_of_all_processing = $processing_status;
			}
			
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
				
			//Hide certain form fields
			$this->class_settings[ 'hidden_records' ] = array(
				'users003' => 1,
				'users004' => 1,
				'users005' => 1,
				'users014' => 1,
				'users015' => 1,
				'users016' => 1,
			);
			
			//Form button caption
			$this->class_settings[ 'form_submit_button' ] = 'Update Changes';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _save_change_user_password(){
			$result_of_all_processing = array();
			$save = 0;
			$sign_out = isset( $_GET["sign_out"] )?$_GET["sign_out"]:'';
			
			switch( $this->class_settings["action_to_perform"] ){
			case "save_change_my_profile":
				$save = 1;
				unset( $_POST[ $this->table_fields["oldpassword"] ] );
				unset( $_POST[ $this->table_fields["password"] ] );
				unset( $_POST[ $this->table_fields["confirmpassword"] ] );
			break;
			}
			
			if( isset( $this->table_fields["role"] ) )unset( $_POST[ $this->table_fields["role"] ] );
			
			//CHECK FOR OLD PASSWORD
			if( isset( $_POST[ $this->table_fields["oldpassword"] ] ) ){
				
				if( $_POST[ $this->table_fields["oldpassword"] ] ){
				
					//TEST OLD PASSWORD TO ENSURE IT MATCHES STORED PASSWORD
					$query = "SELECT `id` FROM `" . $this->class_settings['database_name'] . "`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`id` = '" . $this->class_settings['user_id'] . "' AND `" . $this->table_name . "`.`".$this->table_fields["password"]."` = '" . md5( $_POST[ $this->table_fields["oldpassword"] ] . get_websalter() ) . "' AND `" . $this->table_name . "`.`record_status`='1' ";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'SELECT',
						'set_memcache' => 0,
						'tables' => array( $this->table_name ),
					);
					$sql_result = execute_sql_query($query_settings);
					
					if( isset( $sql_result[0]["id"] ) ){
						//DESTROY OLD PASSWORD FIELD
						unset( $_POST[ $this->table_fields["oldpassword"] ] );
						$save = 1;
					}
				
				}
				
				//RETURN NON-MATCHING OLD PASSWORD ERROR
				$err = new cError('000103');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cusers.php';
				$err->method_in_class_that_triggered_error = '_change_user_password';
				
				if( isset( $query ) && $query ){
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 138';
				}
				
				$result_of_all_processing = $err->error();
			}
			
			if( $save ){
				$result_of_all_processing = $this->_save_changes2();
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "save_change_my_password":	
			case "save_change_my_profile":
				if( isset( $result_of_all_processing["saved_record_id"] ) && $result_of_all_processing["saved_record_id"] && isset( $result_of_all_processing["html"] ) ){
					$result_of_all_processing["html_replacement"] = $result_of_all_processing["html"];
					$result_of_all_processing["html_replacement_selector"] = "#user-info-page";
					$result_of_all_processing["status"] = "new-status";
					unset( $result_of_all_processing["html"] );
					
					if( $sign_out ){
						$pr = get_project_data();
						$result_of_all_processing["redirect_url"] = $pr["domain_name"] . 'sign_out?action=signout';
					}
					
					return $result_of_all_processing;
				}
			break;
			}
			
			return $result_of_all_processing;
		}
		
		private function _change_user_password(){
			//CHECK FOR SUBMITTED FORM DATA
			$result_of_all_processing = $this->_save_change_user_password();
			
			
			//SET VARIABLES FOR EDIT MODE
			$_POST['id'] = $this->class_settings['user_id'];
			$_POST['mod'] = 'edit-'.md5( $this->table_name );
			
			//GENERATE REGISTRATION FORM WITH USER DETAILS
			//Disable appearance of all headings on forms
			$this->class_settings['do_not_show_headings'] = true;
			
			//Form button caption
			$this->class_settings['hidden_records'] = array();
			$this->class_settings[ 'action_to_perform' ] = 'edit_password';
			$this->class_settings[ 'form_submit_button' ] = 'Change Password';
			
			$returned_data = $this->_generate_new_data_capture_form();
			
			if( ! empty ( $result_of_all_processing ) && isset( $result_of_all_processing['html'] ) ){
				$result_of_all_processing['html'] = $returned_data['html'];
				
				return $result_of_all_processing;
			}
			
			return $returned_data;
		}
		
		private function _verify_email_address(){
			//PROJECT DATA
			$project = get_project_data();
			
			$generate_form = true;
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			if(isset($_GET['verify']) && $_GET['verify']){
				
				//Get User Details prior to verification / authentication
				$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE md5(`".$this->table_name."`.`id`) = '" . $_GET['verify'] . "' AND `".$this->table_name."`.`record_status`='1' AND `".$this->table_name."`.`users016` = '10'";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				$email_address = '';
				$password = '';
				$user_id = '';
				$first_name = '';
				$last_name = '';
				
				if(isset($sql_result[0])){
					
					$email_address = $sql_result[0]['users007'];
					$password = $sql_result[0]['users004'];
					$user_id = $sql_result[0]['id'];
					
					$first_name = $sql_result[0]['users001'];
					$last_name = $sql_result[0]['users002'];
					
					//Verify user account if not already verified
					$query = "UPDATE `".$this->class_settings['database_name']."`.`" . $this->table_name . "` SET `".$this->table_name."`.`users016`='20', `".$this->table_name."`.`modification_date`='" . date("U") . "' WHERE md5(`".$this->table_name."`.`id`) = '" . $_GET['verify'] . "' AND `".$this->table_name."`.`record_status`='1'";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					
					$save = execute_sql_query($query_settings);
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError('000001');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'cusers.php';
					$err->method_in_class_that_triggered_error = '_verify_email_address';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 160';
					
					$result_of_all_processing = $err->error();
				}
				
				if( isset( $save['success'] ) && $save['success'] == 1 ){
					//SUCCESSFUL VERIFICATION
					
					if( $email_address && $user_id && $password ){
						
						//Send Successful Email Verification Message
						$email = new cEmails();
						$email->class_settings = array(
							'database_connection' => $this->class_settings[ 'database_connection' ],
							'database_name' => $this->class_settings[ 'database_name' ],
							'calling_page' => $this->class_settings[ 'calling_page' ],
							
							'user_id' => $user_id ,
							
							'action_to_perform' => 'send_mail',
						);
						
						$email->destination['email'][] = $email_address;
						$email->destination['full_name'][] = ucwords( $first_name . ' ' . $last_name );
						$email->destination['id'][] = $user_id;
						
						$email->message_type = 30;	//Successful Registration template
						$email->sender = $project;
						
						$email->emails();
					}
					
					//Prevent Form Generation
					$generate_form = false;
					
					//Login New User
					$classname = 'authentication';
					$actual_name_of_class = 'c'.ucwords($classname);
					
					$module = new $actual_name_of_class();
					
					$module->class_settings = array(
						'database_connection' => $this->class_settings[ 'database_connection' ],
						'database_name' => $this->class_settings[ 'database_name' ],
						'calling_page' => $this->class_settings[ 'calling_page' ],
						
						'username' => $email_address ,
						'password' => $password ,
						
						'action_to_perform' => 'confirm_username_and_password',
					);
					$returned_data = $module->$classname();
					
					if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
						//LOG SUCCESSFUL OPERATION IN AUDIT TRAIL
						//Auditor
						auditor( $this->class_settings , 'verified_account' , $this->table_name , 'user account with id ' . $user_id . ' in the table was verified ', $this->class_settings );
						
						//Redirect to appropriate page
						header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'profile' );
						exit;
					}else{
						//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
						$err = new cError('000014');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cusers.php';
						$err->method_in_class_that_triggered_error = '_users_registration_process';
						$err->additional_details_of_error = '';
						$result_of_all_processing = $err->error();
						
						header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'sign-in' );
						exit;
					}
				}else{
					//UNSUCCESSFUL VERIFICATION
					$err = new cError('000015');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cusers.php';
					$err->method_in_class_that_triggered_error = '_users_registration_process';
					$err->additional_details_of_error = '';
					$result_of_all_processing = $err->error();
				}
				
			}
			
			//2. NO DATA - GENERATE REGISTRATION FORM
			if( $generate_form ){
				//Disable appearance of all headings on forms
				$this->class_settings['do_not_show_headings'] = true;
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records' ] = array(
					$this->table_fields[ 'oldpassword' ] => 1,
					
				);
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records_css' ] = array(
					$this->table_fields[ 'role' ] => 1,
				);
				
				//Set Agreement Text
				$this->class_settings[ 'agreement_text' ] = 'I agree to the ' . $project['project_title'] . ' <a href="' . $this->class_settings['calling_page'] . 'footer/terms_of_agreement" class="special">Terms of Service</a> and <a href="' . $this->class_settings['calling_page'] . 'footer/privacy_policy" class="special">Privacy Policy</a>';
				
				
				return array_merge( $result_of_all_processing , $this->_generate_new_data_capture_form() );
			}
		}
		
		private function _users_registration_process(){
			//PROJECT DATA
			$project = get_project_data();
			
			//INITIALIZE RETURNING ARRAY
			$result_of_all_processing = array();
			
			//SET VARIABLE TO GENERATE FORM
			$generate_form = true;
			
			$email_exists = false;
			
			//1. CHECK FOR EXISTING EMAIL ADDRESS
			if( isset( $_POST['users004'] ) && $_POST['users004'] ){
			
				$query = "SELECT * FROM `" . $this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE `" . $this->table_name . "`.`users007`='" . $_POST['users004'] . "' AND `" . $this->table_name . "`.`record_status`='1' ";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( isset($sql_result[0]) ){
				
					$email_exists = true;
					
				}
				
			}
			
			$processing_status = null;
			
			if( $email_exists ){
				//EXISTING EMAIL ADDRESS ERROR
				$err = new cError('000102');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cusers.php';
				$err->method_in_class_that_triggered_error = '_users_registration_process';
				$err->additional_details_of_error = '';
				$processing_status = $err->error();
				
			}else{
				//CHECK FOR SUBMITTED FORM DATA
				$processing_status = $this->_save_changes2();
			}
			
			//2. FOUND SUBMITTED FORM DATA - PROCESS THE DATA
			if( $processing_status && is_array( $processing_status ) ){
				//Process Response
				if( isset( $processing_status[ 'typ' ] ) ){
				
					switch( $processing_status[ 'typ' ] ){
					case 'saved':
						//Prevent Form Generation
						$generate_form = false;
						
						//Login New User
						$classname = 'authentication';
						$actual_name_of_class = 'c'.ucwords($classname);
						
						$module = new $actual_name_of_class();
						
						$module->class_settings = array(
							'database_connection' => $this->class_settings[ 'database_connection' ],
							'database_name' => $this->class_settings[ 'database_name' ],
							'calling_page' => $this->class_settings[ 'calling_page' ],
							
							'username' => $_POST[ 'users004' ],
							'password' => md5( $_POST[ 'users006' ] . get_websalter() ) ,
							
							'action_to_perform' => 'confirm_username_and_password',
						);
						$returned_data = $module->$classname();
						
						if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
							//Get Logged in User Details
							$user_details = array();
							$key = md5('ucert'.$_SESSION['key']);
							if( isset($_SESSION[$key]) ){
								$user_details = $_SESSION[$key];
								
								//Send Email Verification Message
								$email = new cEmails();
								$email->class_settings = array(
									'database_connection' => $this->class_settings[ 'database_connection' ],
									'database_name' => $this->class_settings[ 'database_name' ],
									'calling_page' => $this->class_settings[ 'calling_page' ],
									
									'user_id' => $user_details[ 'id' ],
									
									'action_to_perform' => 'send_mail',
								);
								
								$email->destination['email'][] = $user_details[ 'email' ];
								$email->destination['full_name'][] = ucwords($user_details[ 'fname' ] . ' ' . $user_details[ 'lname' ] );
								$email->destination['id'][] = $user_details[ 'id' ];
								
								$email->message_type = 1;	//Successful Registration template
								$email->sender = $project;
								
								$email->emails();
								
								$email->message_type = 2;	//Verification template
								$email->emails();
								
								//Redirect to appropriate page
								header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'profile' );
								exit;
							}
						}else{
							//ERROR STATING THAT REGISTERED USER WAS UNABLE TO BE AUTHENTICATED
							$err = new cError('000014');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cusers.php';
							$err->method_in_class_that_triggered_error = '_users_registration_process';
							$err->additional_details_of_error = '';
							$processing_status = $err->error();
							
							header( 'location: ' . $this->class_settings[ 'calling_page' ] . 'sign-in' );
							exit;
						}
						
						//Return Saved Succcessfully Message
						return $processing_status;
					break;
					default:
						//Return Error Message
						$result_of_all_processing = $processing_status;
					break;
					}
					
				}
			}
			
			//3. NO DATA - GENERATE REGISTRATION FORM
			if( $generate_form ){
				//Disable appearance of all headings on forms
				$this->class_settings['do_not_show_headings'] = true;
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records' ] = array(
					$this->table_fields[ 'oldpassword' ] => 1,
				);
				
				//Hide certain form fields
				$this->class_settings[ 'hidden_records_css' ] = array(
					$this->table_fields[ 'role' ] => 1,
				);
				
				$this->class_settings[ 'form_submit_button' ] = 'Register';
				
				//Set Agreement Text
				$this->class_settings[ 'agreement_text' ] = 'I agree to the ' . $project['project_title'] . ' <a href="' . $this->class_settings['calling_page'] . 'footer/terms_of_agreement" class="special">Terms of Service</a> and <a href="' . $this->class_settings['calling_page'] . 'footer/privacy_policy" class="special">Privacy Policy</a>';
				
				
				return array_merge( $result_of_all_processing , $this->_generate_new_data_capture_form() );
			}
		}
		
		private function _get_all_users_details(){
			$returned_data = array();
			
			$cache_key = $this->table_name;
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case 'email':
				case 'other_names':
				case 'firstname':
				case 'lastname':
				case 'bank_name':
				case 'department':
				case 'ref_no':
				case 'photograph':
				case 'account_number':
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`id`, `".$val."` as '".$key."'";
				break;
				}
			}
			
            $this->class_settings['where'] = " WHERE `record_status` = '1' AND `".$this->table_fields["role"]."` != '1300130013' ";
            
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ".$this->class_settings['where'];
			//echo $query; exit;
			//$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if( isset( $this->class_settings["return_data"] ) && $this->class_settings["return_data"] ){
				return $sql_result;
			}
			
            $id = '';
            
			$returned_data = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $s_val ){
					$returned_data[ "default" ][ $s_val['id'] ] = $s_val["firstname"] . " " . $s_val["lastname"];
					
					$returned_data[ "info" ][ $s_val['id'] ] = $s_val;
				}
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key.'-all-users',
					'cache_values' => $returned_data[ "default" ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
				//Cache Settings
				$settings = array(
					'cache_key' => $cache_key.'-all-users-info',
					'cache_values' => $returned_data[ "info" ],
					'directory_name' => $cache_key,
					'permanent' => true,
				);
				set_cache_for_special_values( $settings );
				
			}
			return $returned_data;
		}
		
		public function __before_save_changes(){
			if( ! ( defined( 'HYELLA_V3_BYPASS_USER_ACCOUNT_VERIFICATION' ) && HYELLA_V3_BYPASS_USER_ACCOUNT_VERIFICATION ) ){
				if( isset( $_POST[ $this->table_fields[ 'status' ] ] ) && $_POST[ $this->table_fields[ 'status' ] ] == 'in_active' ){
					//skip inactive accounts: 17-feb-23
				}else{
					if( isset( $_POST["id"] ) && $this->class_settings["user_id"] == $_POST["id"] ){
						//same account
					}else{
						$_POST[ $this->table_fields[ 'status' ] ] = 'pending';
					}
				}
			}

			if( ! ( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ) ){
				if( defined( 'HYELLA_V3_VERIFY_USER_REG' ) && HYELLA_V3_VERIFY_USER_REG ){
					if( isset( $_POST["id"] ) && $this->class_settings["user_id"] == $_POST["id"] ){
						//same account
					}else{
						$_POST[ $this->table_fields[ 'status' ] ] = 'pending_activation';
					}
				}
			}
			
			if( isset( $_POST["id"] ) && ! $_POST["id"] ){
				if( defined("USERS_NEW_ACCOUNT_WITH_DEFAULT_PASSWORD") && USERS_NEW_ACCOUNT_WITH_DEFAULT_PASSWORD && function_exists("get_default_password_settings") ){
					$upassword = get_default_password_settings();
					
					if( $upassword && isset( $this->table_fields["password"] ) && ! isset( $this->class_settings['hidden_records'][ $this->table_fields["password"] ] ) ){
						$_POST[ $this->table_fields[ 'password' ] ] = $upassword;
						$_POST[ $this->table_fields["confirmpassword"] ] = $upassword;
					}
				}
			}
			
			$pp = '';
			if( isset( $_POST[ $this->table_fields[ 'password' ] ] ) && $_POST[ $this->table_fields[ 'password' ] ] && isset( $_POST[ $this->table_fields[ 'confirmpassword' ] ] ) && $_POST[ $this->table_fields[ 'confirmpassword' ] ] ){
				$pp = md5( $_POST[ $this->table_fields[ 'password' ] ].get_websalter() ) . md5( $_POST[ $this->table_fields[ 'confirmpassword' ] ].get_websalter() );
			}else if( isset( $_POST[ "id" ] ) && $_POST[ "id" ] ){
				$u = get_record_details( array( "id" => $_POST[ "id" ], "table" => "users" ) );
				if( isset( $u[ 'password' ] ) && $u[ 'password' ] && isset( $u[ 'confirmpassword' ] ) && $u[ 'confirmpassword' ] ){
					$pp = $u[ 'password' ] . $u[ 'confirmpassword' ];
				}
			}
			
			$_POST[ 'device_id' ] = md5( $pp . $this->class_settings[ 'user_id' ] . get_websalter() );

			if( isset($_POST['id']) && $_POST['id'] && $_POST['id'] == $this->class_settings['user_id'] ){
				if( isset($_POST[ $this->table_fields['status'] ]) && $_POST[ $this->table_fields['status'] ] ){
					unset( $_POST[ $this->table_fields['status'] ] );
				}
				/*else{
					$this->class_settings['current_record_id'] = $_POST['id'];
					$e = $this->_get_record();
					if( isset($e['status']) && $e['status'] ){
						switch ( $e['status'] ) {
							case 'value':
								// code...
							break;
						}
					}
				}*/
			}
		}
	}

	function users(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/users.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/users.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				if( isset( $return[ "fields" ] ) ){
					
					$key = $return[ "fields" ][ "role" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'access_roles',
						'reference_keys' => array( 'role_name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=access_roles&todo=get_select2&role_type=users" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';

					$key = $return[ "fields" ][ "division" ];
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					
					$key = $return[ "fields" ][ "department" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'departments',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'placeholder' ] = 'Department';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=departments&todo=get_select2" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';

					$key = $return[ "fields" ][ "status" ];
					$return[ "labels" ][ $key ][ 'form_field_options' ] = 'get_user_status';

					switch( get_package_option() ){
					case "catholic":
						$key = 'users047';
						$return[ "labels" ][ $key ][ 'calculations' ] = array(
							'type' => 'record-details',
							'reference_table' => 'parish',
							'reference_keys' => array( 'name', 'address' ),
							'form_field' => 'text',
							'variables' => array( array( $key ) ),
						);
						$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=parish&todo=get_select2"  ';
						$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';

					break;
					}
					
					if( isset( $return[ "fields" ][ "country" ] ) ){
						$key = $return[ "fields" ][ "country" ];
						$return[ "labels" ][ $key ][ 'calculations' ] = array(
							'type' => 'record-details',
							'reference_table' => 'stores',
							//'reference_table' => 'country_list',
							'reference_keys' => array( 'name' ),
							'form_field' => 'text',
							'variables' => array( array( $key ) ),
							
							'title' => 'SBU',
							'action' => 'stores',
							//'action' => 'country_list',
							'todo' => 'get_select2',
							'minlength' => '0',
							'data-key-field' => 'country',
						);
						//19-jun-23: 2echo
						if( defined("USERS_COUNTRY_LABEL") && USERS_COUNTRY_LABEL ){
							$return[ "labels" ][ $key ][ 'field_label' ] = USERS_COUNTRY_LABEL;
							$return[ "labels" ][ $key ][ 'display_field_label' ] = $return[ "labels" ][ $key ][ 'field_label' ];
							$return[ "labels" ][ $key ][ 'text' ] = $return[ "labels" ][ $key ][ 'field_label' ];
							$return[ "labels" ][ $key ][ 'calculations' ][ 'title' ] = $return[ "labels" ][ $key ][ 'field_label' ];
						}
						$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
						$return[ "labels" ][ $key ][ 'class' ] = ' selected-state selected-country select2 allow-clear ';
						
					}
					
					if( defined("USERS_STATE_LABEL") && USERS_STATE_LABEL ){
						$key = $return[ "fields" ][ "state" ];
						$return[ "labels" ][ $key ][ 'field_label' ] = USERS_STATE_LABEL;
						$return[ "labels" ][ $key ][ 'display_field_label' ] = $return[ "labels" ][ $key ][ 'field_label' ];
						$return[ "labels" ][ $key ][ 'text' ] = $return[ "labels" ][ $key ][ 'field_label' ];
						$return[ "labels" ][ $key ][ 'calculations' ][ 'title' ] = $return[ "labels" ][ $key ][ 'field_label' ];
						
						$return[ "labels" ][ $key ][ 'calculations' ] = array(
							'type' => 'record-details',
							'reference_table' => 'state_list',
							'reference_keys' => array( 'name' ),
							'form_field' => 'text',
							'variables' => array( array( $key ) ),
							
							'title' => 'State',
							'action' => 'state_list',
							'todo' => 'get_select2',
							'todo2' => '&source=users',
							'data-key-field' => 'state',
							'data-params' => '.selected-country',
							'minlength' => '0',
						);
						$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
						$return[ "labels" ][ $key ][ 'class' ] = ' selected-state selected-lga selected-ward select2 ';
						$return[ "labels" ][ $key ][ 'serial_number' ] = 122.2;
					}else{
						$key = $return[ "fields" ][ "state" ];
						unset( $return[ "labels" ][ $key ] );
					}
					
					if( defined("USERS_LGA_LABEL") && USERS_LGA_LABEL ){
						$key = $return[ "fields" ][ "lga" ];
						$return[ "labels" ][ $key ][ 'calculations' ] = array(
							'type' => 'record-details',
							'reference_table' => 'lga_list',
							'reference_keys' => array( 'name' ),
							'form_field' => 'text',
							'variables' => array( array( $key ) ),
							
							'key' => 'state_list',
							'title' => 'LGA',
							'action' => 'lga_list',
							'todo' => 'get_select2',
							'todo2' => '&source=users',
							'data-params' => '.selected-state',
							'data-key-field' => 'lga',
							'minlength' => '0',
						);
						$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
						
						$return[ "labels" ][ $key ][ 'class' ] = ' selected-lga selected-ward select2 ';
						$return[ "labels" ][ $key ][ 'serial_number' ] = 122.3;
					}else{
						$key = $return[ "fields" ][ "lga" ];
						unset( $return[ "labels" ][ $key ] );
					}
				}
				
				if( isset( $return[ "fields" ] ) && $return[ "fields" ] ){
					foreach( $return[ "fields" ] as $fk => $fv ){
						$key = $return[ "fields" ][ $fk ];
						
						if( defined("REMOVE_USERS_FIELD_" . strtoupper( $fk ) ) && defined("REMOVE_USERS_FIELD_" . strtoupper( $fk ) ) ){
							unset( $return[ "labels" ][ $key ] );
						}else{
							if( defined("COMPULSORY_USERS_FIELD_" . strtoupper( $fk ) ) && defined("COMPULSORY_USERS_FIELD_" . strtoupper( $fk ) ) ){
							$return[ "labels" ][ $key ]["required_field"] = 'yes';
							}else if( defined("OPTIONAL_USERS_FIELD_" . strtoupper( $fk ) ) && defined("OPTIONAL_USERS_FIELD_" . strtoupper( $fk ) ) ){
								$return[ "labels" ][ $key ]["required_field"] = '';
							}
							
							if( defined("LABEL_USERS_FIELD_" . strtoupper( $fk ) ) && defined("LABEL_USERS_FIELD_" . strtoupper( $fk ) ) ){
								$return[ "labels" ][ $key ][ 'field_label' ] = constant( "LABEL_USERS_FIELD_" . strtoupper( $fk ) );
								$return[ "labels" ][ $key ][ 'display_field_label' ] = $return[ "labels" ][ $key ][ 'field_label' ];
								$return[ "labels" ][ $key ][ 'text' ] = $return[ "labels" ][ $key ][ 'field_label' ];
								$return[ "labels" ][ $key ][ 'calculations' ][ 'title' ] = $return[ "labels" ][ $key ][ 'field_label' ];
							}
						}
					}
				}
				

				return $return[ "labels" ];
			}
		}
	}

	function get_Endpoint( $opt = array() ){

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

	/*
	UPGRADE
	ALTER TABLE `users`  ADD `device_id` VARCHAR(100) NOT NULL  AFTER `ip_address`;
	ALTER TABLE `users` CHANGE `record_status` `record_status` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

	ALTER TABLE `users`  ADD `serial_num` INT(11) NOT NULL  AFTER `users032`;

	SELECT @i :=0;
	UPDATE users SET serial_num = ( SELECT @i := @i +1 ) ;
	ALTER TABLE users DROP PRIMARY KEY;

	ALTER TABLE `users` ADD PRIMARY KEY(`serial_num`);
	ALTER TABLE `users` ADD UNIQUE(`id`);
	ALTER TABLE `users` CHANGE `serial_num` `serial_num` INT(11) NOT NULL AUTO_INCREMENT;
	*/
?>

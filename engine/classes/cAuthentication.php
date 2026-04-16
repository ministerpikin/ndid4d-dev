<?php
	/**
	 * Authentication Class
	 *
	 * @used in  				Authentication
	 * @created  				15:53 | 27-12-2013
	 * @database table name   	users | 
	 */

	/*
	|--------------------------------------------------------------------------
	| Authentication Function that occurs during login
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to authenticate users against database
	| records.
	|
	*/
	
	class cAuthentication{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name_static = 'authentication';
		private $cache_key = 'authentication';
		
		private $table_name = 'users';
		private $admin_table_name = '';
		
		public $table_fields = array(
			'firstname' => 'users001',
			'lastname' => 'users002',
			
			'email' => 'users004',
			'password' => 'users006',
		);
			
		function __construct(){
			$u = new cUsers();

			$this->table_fields[ 'firstname' ] = $u->table_fields[ 'firstname' ];
			$this->table_fields[ 'lastname' ] = $u->table_fields[ 'lastname' ];
			$this->table_fields[ 'email' ] = $u->table_fields[ 'email' ];
			$this->table_fields[ 'password' ] = $u->table_fields[ 'password' ];
		}
		
		function authentication(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'confirm_username_and_password':
				$returned_value = $this->_confirm_username_and_password();
			break;
			case 'reset_user_password':
				$returned_value = $this->_reset_user_password();
			break;
			case 'forgot_password_form':
			case 'users_login_process':
				$returned_value = $this->_users_login_process();
			break;
			case 'set_session':
				$returned_value = $this->_set_session2();
			break;
			case 'cancel_second_level_auth_form':
			case 'save_second_level_auth_form2':
			case 'save_second_level_auth_form':
			case 'second_level_auth_form':
				$returned_value = $this->_second_level_auth_form();
			break;
			case 'authenticate_user':
			case 'authenticate_app_mobile':
			case 'authenticate_app':
				$returned_value = $this->_authenticate_app();
			break;
			case 'resend_verification_email':
				$returned_value = $this->_resend_verification_email();
			break;
			case 'verify_email_address':
				$returned_value = $this->_verify_email_address();
			break;
			case 'verify_otp':
				$returned_value = $this->_verify_otp();
			break;
			}
			
			return $returned_value;
		}
		
		
		protected function _verify_otp( $o = array() ){
			$err = '';
			$etype = 'error';
			$_n = array();
		
			$handle = "dashboard-main-content-area";
			if( isset( $this->class_settings['html_replacement_selector'] ) && $this->class_settings['html_replacement_selector'] ){
				$this->class_settings['data']['html_replacement_selector'] = $this->class_settings['html_replacement_selector'];
				$handle = $this->class_settings['html_replacement_selector'];
			}
		
		
			$action_to_perform = $this->class_settings['action_to_perform'];

			$vcode = isset( $_POST['vcode'] ) && $_POST['vcode'] ? $_POST['vcode'] : '';
			$email = isset( $_POST['email'] ) && $_POST['email'] ? $_POST['email'] : '';
			$table = isset( $_POST['table'] ) && $_POST['table'] ? $_POST['table'] : '';

			$log_user_id = isset( $_POST['log_user_id'] ) && $_POST['log_user_id'] ? $_POST['log_user_id'] : 0;
			// Function to verify otp
			$return = [];
			if( $log_user_id ){
				$this->class_settings['username_key'] = 'email';
				$this->class_settings['skip_password'] = 1;
				$this->class_settings['username'] = $email;
				$this->class_settings['table'] = $table;
				$this->class_settings['action_to_perform'] = 'confirm_username_and_password';

				$return = $this->_confirm_username_and_password();
				if( is_array( $return ) && isset( $return[ 'typ' ] ) && $return[ 'typ' ] == 'authenticated' ){
					$return['success'] = 1;	
				}
			}

			return $return;

			$err = $err ? $err : '<h4>Unknown error</h4><p>Please contact support or try again later</p>';
			
			$_n['type'] = $etype;
		
			$_n['message'] = $err;
		
			return $this->_display_notification( $_n );
		}
				
		
		
		// private function _second_level_auth_form(){
		// 	$action_to_perform = $this->class_settings["action_to_perform"];
		// 	$error_msg = '';
			
		// 	switch( $action_to_perform ){
		// 	case "cancel_second_level_auth_form":
		// 		$id = isset( $_POST["id"] )?$_POST["id"]:'';
				
		// 		if( $id ){
		// 			$settings = array(
		// 				'cache_key' => $this->cache_key . $id,
		// 				'cache_time' => 'load-time',
		// 			);
		// 			clear_cache_for_special_values( $settings );
		// 		}
				
		// 		return array(
		// 			'do_not_reload_table' => 1,
		// 			'html_removal' => '#nw-second-level-auth-container',
		// 			'method_executed' => $this->class_settings['action_to_perform'],
		// 			'status' => 'new-status',
		// 		);
		// 	break;
		// 	case "save_second_level_auth_form":
		// 		//handles auth
		// 		$id = isset( $_POST["id"] )?$_POST["id"]:'';
		// 		$user = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:'';
		// 		$password = isset( $_POST["password"] )?$_POST["password"]:'';
		// 		$correct_password = 0;
				
		// 		if( $password && $user ){
					
		// 			$u = get_record_details( array( "id" => $user, "table" => "users", "force" => 1 ) );
					
		// 			$hashed = md5( $password . get_websalter() );
					
		// 			if( isset( $u["password"] ) && $u["password"] == $hashed ){
		// 				$correct_password = 1;
		// 			}
					
		// 			if( $correct_password ){
		// 				if( $id ){
							
		// 					$settings = array(
		// 						'cache_key' => $this->cache_key . $id,
		// 						'cache_time' => 'load-time',
		// 					);
		// 					$cache_data = get_cache_for_special_values( $settings );
							
		// 					if( isset( $cache_data["class_settings"] ) && $cache_data["class_settings"] && isset( $cache_data["get"] ) && isset( $cache_data["post"] ) ){
		// 						$return = array(
		// 							'do_not_reload_table' => 1,
		// 							'html_removal' => '#nw-second-level-auth-container',
		// 							'method_executed' => $this->class_settings['action_to_perform'],
		// 							'status' => 'new-status',
		// 						);
							
		// 						$return['re_process'] = 1;
		// 						$return['re_process_code'] = 1;
		// 						$return['mod'] = '-';
		// 						$return['id'] = $id;
		// 						$return['action'] = "?action=authentication&todo=save_second_level_auth_form2";
								
		// 						return $return;
		// 					}else{
		// 						$error_msg = '<h4>Missing Parameters</h4><p>Please close this window and try again</p>';
		// 					}
		// 				}else{
		// 					$error_msg = '<h4>Invalid Reference</h4><p>Please close this window and try again</p>';
		// 				}
		// 			}else{
		// 				$error_msg = '<h4>Incorrect Password</h4><p>Please try again</p>';
		// 			}
					
		// 		}else{
		// 			$error_msg = '<h4>Undefined User or Password</h4><p>Please try again. If problem persists sign-out and sign-in</p>';
		// 		}
				
		// 		if( ! $error_msg ){
		// 			$error_msg = '<h4>Unknown Error</h4>';
		// 		}
				
		// 	break;
		// 	case "save_second_level_auth_form2":
		// 		$id = isset( $_POST["id"] )?$_POST["id"]:'';
				
		// 		if( $id ){
					
		// 			$settings = array(
		// 				'cache_key' => $this->cache_key . $id,
		// 				'cache_time' => 'load-time',
		// 			);
		// 			$cache_data = get_cache_for_special_values( $settings );
		// 			clear_cache_for_special_values( $settings );
					
		// 			if( isset( $cache_data["class_settings"] ) && $cache_data["class_settings"] && isset( $cache_data["get"] ) && isset( $cache_data["post"] ) ){
						
		// 				$_POST = $cache_data["post"];
		// 				$_GET = $cache_data["get"];
						
		// 				if( isset( $_GET["action"] ) && $_GET["action"] && isset( $_GET["todo"] ) && $_GET["todo"] ){
							
		// 					$cache_data["class_settings"]["database_connection"] = $this->class_settings["database_connection"];
		// 					$cache_data["class_settings"]["database_name"] = $this->class_settings["database_name"];
		// 					$cache_data["class_settings"]["action_to_perform"] = $_GET["todo"];
							
		// 					$classname = $_GET["action"];
		// 					$actual_name_of_class = 'c'.ucwords( $classname );
			
		// 					$module = new $actual_name_of_class();
		// 					$module->class_settings = $cache_data["class_settings"];
							
		// 					return $module->$classname();
		// 				}else{
		// 					$error_msg = '<h4>Missing Critical Parameters</h4><p>Please close this window and try again</p>';
		// 				}
		// 			}else{
		// 				$error_msg = '<h4>Missing Parameters</h4><p>Please close this window and try again</p>';
		// 			}
		// 		}else{
		// 			$error_msg = '<h4>Invalid Reference</h4><p>Please close this window and try again</p>';
		// 		}
				
		// 		if( ! $error_msg ){
		// 			$error_msg = '<h4>Unknown Error</h4>';
		// 		}
				
		// 	break;
		// 	}
			
			
		// 	if( $error_msg ){
		// 		$err = new cError('010014');
		// 		$err->action_to_perform = 'notify';
		// 		$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
		// 		$err->method_in_class_that_triggered_error = '_resend_verification_email';
		// 		$err->additional_details_of_error = $error_msg;
		// 		return $err->error();
		// 	}
			
		// 	$id = md5( get_new_id() . $this->class_settings["user_id"] );
			
		// 	$cache_data = array( "get" => $_GET, "post" => $_POST, "class_settings" => $this->class_settings );
			
		// 	$settings = array(
		// 		'cache_key' => $this->cache_key . $id,
		// 		'cache_values' => $cache_data,
		// 		'cache_time' => 'load-time',
		// 	);
		// 	set_cache_for_special_values( $settings );
			
		// 	$this->class_settings[ 'data' ]['id'] = $id;
		// 	$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/second-level-auth.php' );
		// 	$html = $this->_get_html_view();
			
		// 	return array(
		// 		'do_not_reload_table' => 1,
		// 		'html_prepend' => $html,
		// 		'html_prepend_selector' => "body",
		// 		'method_executed' => $this->class_settings['action_to_perform'],
		// 		'status' => 'new-status',
		// 		'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
		// 	);
		// }

		public function _verify_email_address( $p = array() ){
			$error_type = 'error';
			$error = '';
			
			if( isset( $p["table"] ) && isset( $p["fields"] ) && isset( $p["verification_field"] ) && isset( $p["fields"][ $p["verification_field"] ] ) ){
				foreach( array( "d", "id", "sn", "t", "tp" ) as $av ){
					if( isset( $_GET[ $av ] ) && $_GET[ $av ] ){
						
					}else{
						$error = 'Invalid URL parameters';
					}
				}
			}else{
				$error = 'Invalid verification settings';
			}
			
			if( ! $error ){
				if( $_GET[ "sn" ] == md5( $_GET[ "d" ] . $_GET[ "tp" ] . $_GET[ "t" ] . $_GET[ "id" ] . get_websalter() ) ){
					$vs = isset( $p["verification_status"] )?$p["verification_status"]:'verified';
					$s["where"] = " `id` = '". $_GET[ "d" ] ."' ";
					$s["field_and_values"][ $p["fields"][ $p["verification_field"] ] ] = array(
						"key" => $p["verification_field"],
						"value" => $vs,
					);
					$s["database_table"] = $p["table"];
					
					
					$b = new cCustomer_call_log();
					$b->class_settings = $this->class_settings;
					$b->table_fields = $p["fields"];
					$b->table_name = $p["table"];
					$b->refresh_cache_after_save_line_items = 1;
					$br = $b->_update_records( $s );
					
					if( $br ){
						$error_type = "success";
						$error = '<h4>Account Successfully Verified</h4>';
					}else{
						$error = 'Unable to update record';
					}
				}else{
					$error = 'Access Violation';
				}
			}
			
			if( ! $error ){
				$error = 'Unknown Error';
			}
			return $this->_display_notification( array( 'type' => $error_type, 'message' => $error ) );
		}
		
		public function _resend_verification_email( $p = array() ){
			$error_type = 'error';
			$error = '';
			if( isset( $p["email_field"] ) && isset( $p["table"] ) && isset( $p["record"][ $p["email_field"] ] ) && isset( $p["record"][ "id" ] ) ){
				$plugin = isset( $p["plugin"] )?$p["plugin"]:'';
				$msg = isset( $p["message"] )?$p["message"]:'';
				if( ! $msg ){
					$msg = 'Click on the link to verify your email address';
				}
				
				$pr = get_project_data();
				$new_password = rand(1,99999);
				$hashed_password = md5( $p["record"][ "id" ] . $plugin . $p["table"] . $new_password . get_websalter() );
				
				$url = isset( $p["url"] )?$p["url"]:( $pr["domain_name"] .'?page=verify-email-address' );
				
				$vl = $url .'&d='. $p["record"][ "id" ] .'&id='. $new_password .'&sn='. $hashed_password .'&t='.$p["table"].'&tp='.$plugin;
				$msg1 = '<br /><strong>EMAIL VERIFICATION LINK:</strong><br /><a target="_blank" href="'. $vl .'" title="Verify your '. $pr["project_title"] .' email" class="btnx redx">'.$vl.'</a>';
				
				
				$ar["subject"] = isset( $p["subject"] )?$p["subject"]:'Email Address Verification';
				$ar["info"] = $msg1;
				$ar["content"] = $msg;
				
				$ar["single_email"] = $p["record"][ $p["email_field"] ];
				$ar["single_name"] = ( isset( $p["name_field"] ) && isset( $p["record"][ $p["name_field"] ] ) )?$p["record"][ $p["name_field"] ]:$ar["single_email"];
				
				$b = new cCustomer_call_log();
				$b->class_settings = $this->class_settings;
				$r = $b->_send_email_notification( $ar );
				
				if( isset( $r["success"] ) && $r["success"] ){
					$error_type = "success";
					$error = $r["success"];
				}else if( isset( $r["response"] ) && $r["response"] ){
					$error = $r["response"];
				}
			}else{
				$error = 'Undefined Verification Email Settings';
			}
			//$error_type = "success";
			//$error = 'Unknown Error';
			if( ! $error ){
				$error = 'Unknown Error';
			}
			return $this->_display_notification( array( 'type' => $error_type, 'message' => $error ) );
		}
		
		private function _second_level_auth_form(){
			$action_to_perform = $this->class_settings["action_to_perform"];
			$error_msg = '';
			
			switch( $action_to_perform ){
			case "cancel_second_level_auth_form":
				$id = isset( $_POST["id"] )?$_POST["id"]:'';
				
				if( $id ){
					$settings = array(
						'cache_key' => $this->cache_key . $id,
						'cache_time' => 'load-time',
					);
					clear_cache_for_special_values( $settings );
				}
				
				return array(
					'do_not_reload_table' => 1,
					'html_removal' => '#nw-second-level-auth-container',
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
				);
			break;
			case "save_second_level_auth_form":
				//handles auth
				$id = isset( $_POST["id"] )?$_POST["id"]:'';
				$user = isset( $this->class_settings["user_id"] )?$this->class_settings["user_id"]:'';
				$password = isset( $_POST["password"] )?$_POST["password"]:'';
				$correct_password = 0;
				
				if( $password && $user ){
					
					$u = get_record_details( array( "id" => $user, "table" => "users", "force" => 1 ) );
					
					$hashed = md5( $password . get_websalter() );
					
					if( isset( $u["password"] ) && $u["password"] == $hashed ){
						$correct_password = 1;
					}
					
					if( $correct_password ){
						if( $id ){
							
							$settings = array(
								'cache_key' => $this->cache_key . $id,
								'cache_time' => 'load-time',
							);
							$cache_data = get_cache_for_special_values( $settings );
							
							if( isset( $cache_data["class_settings"] ) && $cache_data["class_settings"] && isset( $cache_data["get"] ) && isset( $cache_data["post"] ) ){
								$return = array(
									'do_not_reload_table' => 1,
									'html_removal' => '#nw-second-level-auth-container',
									'method_executed' => $this->class_settings['action_to_perform'],
									'status' => 'new-status',
								);
							
								$return['re_process'] = 1;
								$return['re_process_code'] = 1;
								$return['mod'] = '-';
								$return['id'] = $id;
								$return['action'] = "?action=authentication&todo=save_second_level_auth_form2";
								
								return $return;
							}else{
								$error_msg = '<h4>Missing Parameters</h4><p>Please close this window and try again</p>';
							}
						}else{
							$error_msg = '<h4>Invalid Reference</h4><p>Please close this window and try again</p>';
						}
					}else{
						$error_msg = '<h4>Incorrect Password</h4><p>Please try again</p>';
					}
					
				}else{
					$error_msg = '<h4>Undefined User or Password</h4><p>Please try again. If problem persists sign-out and sign-in</p>';
				}
				
				if( ! $error_msg ){
					$error_msg = '<h4>Unknown Error</h4>';
				}
				
			break;
			case "save_second_level_auth_form2":
				$id = isset( $_POST["id"] )?$_POST["id"]:'';
				
				if( $id ){
					
					$settings = array(
						'cache_key' => $this->cache_key . $id,
						'cache_time' => 'load-time',
					);
					$cache_data = get_cache_for_special_values( $settings );
					clear_cache_for_special_values( $settings );
					
					if( isset( $cache_data["class_settings"] ) && $cache_data["class_settings"] && isset( $cache_data["get"] ) && isset( $cache_data["post"] ) ){
						
						$_POST = $cache_data["post"];
						$_GET = $cache_data["get"];
						
						if( isset( $_GET["action"] ) && $_GET["action"] && isset( $_GET["todo"] ) && $_GET["todo"] ){
							
							$cache_data["class_settings"]["database_connection"] = $this->class_settings["database_connection"];
							$cache_data["class_settings"]["database_name"] = $this->class_settings["database_name"];
							$cache_data["class_settings"]["action_to_perform"] = $_GET["todo"];
							
							$classname = $_GET["action"];
							$actual_name_of_class = 'c'.ucwords( $classname );
			
							$module = new $actual_name_of_class();
							$module->class_settings = $cache_data["class_settings"];
							
							return $module->$classname();
						}else{
							$error_msg = '<h4>Missing Critical Parameters</h4><p>Please close this window and try again</p>';
						}
					}else{
						$error_msg = '<h4>Missing Parameters</h4><p>Please close this window and try again</p>';
					}
				}else{
					$error_msg = '<h4>Invalid Reference</h4><p>Please close this window and try again</p>';
				}
				
				if( ! $error_msg ){
					$error_msg = '<h4>Unknown Error</h4>';
				}
				
			break;
			}
			
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = '_resend_verification_email';
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
			
			$id = md5( get_new_id() . $this->class_settings["user_id"] );
			
			$cache_data = array( "get" => $_GET, "post" => $_POST, "class_settings" => $this->class_settings );
			
			$settings = array(
				'cache_key' => $this->cache_key . $id,
				'cache_values' => $cache_data,
				'cache_time' => 'load-time',
			);
			set_cache_for_special_values( $settings );
			
			$this->class_settings[ 'data' ]['id'] = $id;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/second-level-auth.php' );
			$html = $this->_get_html_view();
			
			return array(
				'do_not_reload_table' => 1,
				'html_prepend' => $html,
				'html_prepend_selector' => "body",
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => array( 'set_function_click_event', 'prepare_new_record_form_new' ),
			);
		}
		
		private function _set_session2(){
			if( isset( $this->class_settings["user_details"]["user_id"] ) && $this->class_settings["user_details"]["user_id"] ){
				$user_details = $this->class_settings["user_details"];
				return $this->_set_session( $user_details );	
			}
		}
		
		private function _users_login_process(){
			$returned_data = array();
			
			//DETERMINE TYPE OF FORM - LOGIN / RESET PASSWORD
			$login_form = true;
			if( isset( $_GET['form_type'] ) && $_GET['form_type'] == 'reset' ){
				$login_form = false;
			}
			
			switch( $this->class_settings["action_to_perform"] ){
			case "forgot_password_form":
				$login_form = false;
			break;
			}
			
			//DETERMINE TYPE OF LOGIN - ADMIN / USERS
			//Users login
			$classname = 'users';
			
			//Get project data
			$project = get_project_data();
			
			//Check login type
			if( isset( $project['admin_login_form_variable_name'] ) && isset( $_GET[ $project['admin_login_form_variable_name'] ] ) && $_GET[ $project['admin_login_form_variable_name'] ] == $project['admin_login_form_passkey'] ){
				//Admin login
				$classname = 'administrators';
			}
			
			//CHECK FOR SUBMITTED FORM DATA AND AUTHENTICATE
			if( isset( $_POST['table'] ) && $_POST['table'] == $classname ){
				
				//Validate form token
				$validated_token = false;
				//$validated_token = true;
				if(isset($_SESSION['key'])){
					
					if( ( isset( $_POST['processing'] ) && generate_token( array( 'validate' => $_POST['processing'] ) ) ) ){
					
						//TOKEN VALIDATED
						$validated_token = true;
					}

				}
				
				if( $validated_token ){
					//Get User Details
					$email = '';
					$password = '';
					
					if( isset( $_POST[ $this->table_fields['email'] ] ) ){
						$email = trim( $_POST[ $this->table_fields['email'] ] );
						if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
							$email = '';
						}
					}
						
					if( isset( $_POST[ $this->table_fields['password'] ] ) )
						$password = $_POST[ $this->table_fields['password'] ];
					
					$report_error = true;
					
					//Authenticate User
					if( $email && $password ){
						$report_error = false;
						
                        $valid_token = 1; //$valid_token = 0;
                        //check for valid token
                        $settings = array(
                            'cache_key' => 't-'.md5( $email ),
                        );
                        $stored_token = get_cache_for_special_values( $settings );
                        
                        if( isset( $_GET['n'] ) && $_GET['n'] ){
                            clear_cache_for_special_values( $settings );
                            
                            if( $_GET['n'] == $stored_token ){
                                $valid_token = 1;
                            }else{
                                if( $stored_token ){
                                    $err = new cError('000026');
                                    $err->action_to_perform = 'notify';
                                    $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                                    $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                                    $err->additional_details_of_error = 'invalid authentication token';
                                    
                                    return $err->error();
                                }
                            }
                        }
                        
                        if( ! $valid_token )$this->class_settings[ 'do_not_set_session' ] = 1;
                        
						$this->class_settings[ 'username' ] = $email;
						$this->class_settings[ 'password' ] = md5( $password . get_websalter() );
						
						$returned_data = $this->_confirm_username_and_password();
						
						if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
							
                            if( ! $valid_token ){
                                //set token
                                $token = md5( date("U") . get_websalter() );
                                //Cache Settings
                                $settings = array(
                                    'cache_key' => 't-'.md5( $email ),
                                    'cache_values' => $token,
                                    'cache_time' => 'token-time',
                                );
                                set_cache_for_special_values( $settings );
                                
                                //send email to user
                                $this->class_settings['message_type'] = 15;
                                $this->class_settings['project_data'] = get_project_data();
                                $this->class_settings[ 'mail_certificate' ]['login_link'] = '<a href="https://'.$this->class_settings['project_data']['domain_name_only'].'/engine/?t='.$token.'" target="_blank" title="Click here to Login">Click here to Login</a>';
                                $this->class_settings[ 'mail_certificate' ]['validity_period'] = '10 minutes';
                                
                                $this->class_settings[ 'user_full_name' ] = isset( $returned_data['user_details']['fname'] )?$returned_data['user_details']['fname'].' '.$returned_data['user_details']['lname']:'';
                                
                                $this->class_settings[ 'user_email' ] = $email;
                                $this->class_settings[ 'user_id' ] = isset( $returned_data['user_details']['user_id'] )?$returned_data['user_details']['user_id']:'';
                                
                                $this->_send_email();
                                
                                session_destroy();
                                
                                //return verification token sent to email address message
                                $err = new cError('010015');
                                $err->action_to_perform = 'notify';
                                return $err->error();
                            }    
                            
							//Redirect to dashboard
							$school_setup_page_html_content = '';
							if( file_exists( ( $this->class_settings['calling_page'] . 'html-files/templates-1/dashboard-page.php' ) ) ){
								$this->class_settings[ 'html' ] = array( 'html-files/templates-1/dashboard-page.php' );
								$school_setup_page_html_content = $this->_get_html_view();
							}
							
							//GET SCHOOL PROPERTIES
							$settings = array(
								'cache_key' => 'school_properties',
							);
							$cached_values = get_cache_for_special_values( $settings );
			
							$returning_array = array(
								'html' => $school_setup_page_html_content,
								'method_executed' => $this->class_settings['action_to_perform'],
								'school_properties' => $cached_values,
								'status' => 'redirect-to-dashboard',
								'reload' => 1,
								'message' => 'Successfully created and authenticated',
							);
							
							return $returning_array;
						}
						
					}

					$u = new cUsers();
					
					//Reset User Password
					if( $email && ! $login_form ){
						$report_error = false;
						
						$this->class_settings[ 'user_email' ] = $email;
						$this->class_settings[ 'username' ] = $email;
						$this->class_settings[ 'email_field' ] = $this->table_fields['email'];
						$this->class_settings[ 'password_field' ] = $this->table_fields['password'];
						$this->class_settings[ 'confirm_password_field' ] = 'users007';
						
						$this->class_settings[ 'firstname_field' ] = $u->table_fields[ 'firstname' ];
						$this->class_settings[ 'lastname_field' ] = $u->table_fields[ 'lastname' ];
						
						$this->table_name = $classname;
						
						return $this->_reset_user_password();
					}
					
					if( $report_error ){
						
						//Failed Authentication - wrong username
						$err = new cError('010101');
						$err->action_to_perform = 'notify';
						
						$returned_data = $err->error();
					}
					
				}else{
					//RETURN INVALID TOKEN ERROR
					$err = new cError('000002');
					$err->action_to_perform = 'notify';
					
					$returned_data = $err->error();
				}
			}
			
			//GENERATE LOGIN FORM
			$actual_name_of_class = 'c'.ucwords($classname);
			
			$module = new $actual_name_of_class();
			
			$module->class_settings = array(
				'database_connection' => $this->class_settings[ 'database_connection' ],
				'database_name' => $this->class_settings[ 'database_name' ],
				'calling_page' => $this->class_settings[ 'calling_page' ],
				
				'user_id' => '',
				'priv_id' => '',
				
				//'do_not_show_headings' => true,
				'hidden_records' => array(
					$u->table_fields[ 'firstname' ] => 1,
					$u->table_fields[ 'lastname' ] => 1,
					$u->table_fields[ 'other_names' ] => 1,
					$u->table_fields[ 'phone_number' ] => 1,
					$u->table_fields[ 'password' ] => 1,
					$u->table_fields[ 'confirmpassword' ] => 1,
					$u->table_fields[ 'oldpassword' ] => 1,
					$u->table_fields[ 'role' ] => 1,
					$u->table_fields[ 'department' ] => 1,
					$u->table_fields[ 'date_of_birth' ] => 1,
					$u->table_fields[ 'sex' ] => 1,
					$u->table_fields[ 'address' ] => 1,
					$u->table_fields[ 'date_employed' ] => 1,
					$u->table_fields[ 'ref_no' ] => 1,
					'users016' => 1,
					'users017' => 1,
					'users018' => 1,
					'users019' => 1,
					'users020' => 1,
					'users021' => 1,
					'users022' => 1,
					'users023' => 1,
					'users024' => 1,
					'users025' => 1,
					'users026' => 1,
					'users027' => 1,
					'users028' => 1,
					'users029' => 1,
					'users030' => 1,
					'users031' => 1,
					'users032' => 1,
					'users033' => 1,
					'users034' => 1,
					'users035' => 1,
					'users036' => 1,
					'users037' => 1,
					'users038' => 1,
					'users039' => 1,
					'users040' => 1,
					'users041' => 1,
				),
				
				'form_class' => 'skip-validation',
				'form_action' => '?action=authentication&todo=users_login_process&form_type=reset',
				'form_submit_button' => 'Request Password Reset',
				'forgot_password_link' => '<a href="?form_type" class="special" rel="external">Go Back to Login</a><br /><br />',
                
				'form_heading_title' => 'Login to your account',
				'hide_clear_form_button' => 1,
				
				'action_to_perform' => 'create_new_record',
			);
			
			if( $login_form ){
				//Display Password Field
				unset( $module->class_settings[ 'hidden_records' ][ 'users006' ] );
				
				$module->class_settings[ 'form_action' ] = '?action=authentication&todo=users_login_process';
				
                if( isset( $_GET['tt'] ) && $_GET['tt'] )$module->class_settings[ 'form_action' ] .= '&n='.$_GET['tt'];
                
				$module->class_settings[ 'forgot_password_link' ] = '<a href="#?form_type=reset" class="custom-single-selected-record-button special" action="?action=authentication&todo=forgot_password_form" override-selected-record="1">Forgot your password?</a><br /><br />';
				
				$module->class_settings[ 'form_submit_button' ] = 'Sign In';
			}
			
			//Clear Previously Submitted Data
			unset( $_POST[ $u->table_fields[ 'email' ] ] );
			unset( $_POST[ $u->table_fields[ 'password' ] ] );
			
			$result_of_all_processing = $module->$classname();
			
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/suggest-users.php' );
			$this->class_settings[ 'data' ][ "users" ] = array( 'html-files/templates-1/suggest-users.php' );
			$result_of_all_processing['html'] .= $this->_get_html_view();
			
			if( ! empty ( $returned_data ) && isset( $returned_data['html'] ) ){
				
				$returned_data['html'] = $result_of_all_processing['html'];
				$returned_data['method_executed'] = $this->class_settings['action_to_perform'];
				$returned_data['status'] = 'authenticate-user';
				
				return $returned_data;
			}
			
			return $result_of_all_processing;
		}
		
		public function _set_table_fields( $tb ){
			$cl = 'c'.ucwords( $tb );

			switch( $tb ){
			case $this->table_name:
				return;
			break;
			}

			if( class_exists( $cl ) ){
				$cl = new $cl();

				$key = 'firstname';
				switch( $cl->table_name ){
				case 'customers':
				case 'tele_health_customer':
					$key = 'first_name';
				break;
				}
				$this->table_fields[ $key ] = $cl->table_fields[ $key ];

				$key = 'lastname';
				switch( $cl->table_name ){
				case 'customers':
					$key = 'name';
				break;
				case 'tele_health_customer':
					$key = 'last_name';
				break;
				}
				$this->table_fields[ $key ] = $cl->table_fields[ $key ];

				$key = 'email';
				$this->table_fields[ $key ] = $cl->table_fields[ $key ];

				$key = 'password';
				$this->table_fields[ $key ] = $cl->table_fields[ $key ];

				$this->table_name = $cl->table_name;
			}
		}
		
		private function _authenticate_app(){
			$callback = isset( $_POST[ 'callback' ] ) && $_POST[ 'callback' ] ? $_POST[ 'callback' ] : '';
			$redirect_url = isset( $_POST[ 'redirect_url' ] ) && $_POST[ 'redirect_url' ] ? $_POST[ 'redirect_url' ] : '';
			$log_user_id = isset( $_POST[ 'log_user_id' ] ) && $_POST[ 'log_user_id' ] ? $_POST[ 'log_user_id' ] : '';

			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$error_type = 'error';
			$error_msg = '';
			$plugin = '';
			$table = isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ? $_POST[ 'table' ] : 'users';
			$email_key = isset( $_POST[ 'username_key' ] ) && $_POST[ 'username_key' ] ? $_POST[ 'username_key' ] : 'email';
			
			if( ( isset( $_POST["email"] ) || isset( $_POST["username"] ) ) && isset( $_POST["password"] ) ){
				$email = trim( ( isset( $_POST["email"] ) && $_POST["email"] )?$_POST["email"]:'' );
				if( ! $email ){
					$email = ( isset( $_POST["username"] ) && $_POST["username"] )?$_POST["username"]:'';
					$email_key = 'username';
				}
				
				$pass = $_POST["password"];
			}else{
				$error_msg = "Please provide valid email & password";
			}

			switch( $table ){
			case 'users':
			case 'hr':
			case 'staff':
			case 'customer':
			case 'coop':
			break;
			default:
				$plugin = isset( $_POST[ 'plugin' ] ) && $_POST[ 'plugin' ] ? $_POST[ 'plugin' ] : '';
			break;
			}

			if( $plugin ){
				$nwp = 'c' . ucwords( $plugin );
				if( class_exists( $nwp ) ){
					$nwp = new $nwp();
					$nwp->class_settings = $this->class_settings;
					$nwp->load_class( array( 'class' => array( $table ) ) );
				}else{
					$error_msg = "The Plugin Class '". $nwp ."' does not exist";
				}
			}

			if( ! $error_msg ){
				//Login New User
				$this->class_settings["plugin"] = $plugin;
				$this->class_settings["table"] = $table;
				$this->class_settings["username_key"] = $email_key;
				$this->class_settings["username"] = $email;
				$this->class_settings["password"] = md5( $pass . get_websalter() );
				$this->class_settings["upassword"] = $pass;
				
				$this->class_settings["action_to_perform"] = 'confirm_username_and_password';
				$returned_data  = $this->authentication(); 
				
					// print_r( $returned_data );exit;
				if( is_array($returned_data) && isset( $returned_data[ 'typ' ] ) && $returned_data[ 'typ' ] == 'authenticated' ){
					//Get Logged in User Details
					$user_details = array();	
					$key = md5('ucert'.$_SESSION['key']);
					if( isset($_SESSION[$key]) ){
						
						$user_details = $_SESSION[$key];

						$loc = get_default_location();
						
						$key = md5( 'ucert' . $_SESSION['key'] );
						if( isset( $_SESSION[ $key ] ) ){
							$user_details = $_SESSION[ $key ];
							$user_info = $user_details;

							//get access_roles
							$super = 0;
							$allow_update = 0;
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
							}
							
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
						
						
						switch( $action_to_perform ){
						case "authenticate_app_mobile":
							if( ! $callback )$callback = "hyellaMobile.finishLogin";
						case "authenticate_user":
							unset( $user_details["functions"] );
							$user_details["full_name"] = $user_details["lname"] . ' ' . $user_details["fname"];
							
							$d2["user_data"] = $user_details;
							
							$returned_data = array(
								"status" => "new-status",
								"data" => $d2,
								"javascript_functions" => array( $callback ),
							);

							if( isset( $redirect_url ) && $redirect_url ){
								$returned_data[ 'redirect_url' ] = $redirect_url;
							}
						break;
						default:
							$returned_data = array(
								"status" => "new-status",
								"redirect_url" => $loc,
							);
						break;
						}

						if( $log_user_id ){
							$returned_data[ 'log_user_id' ] = $log_user_id;
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

		private function _confirm_username_and_password(){
			$table1 = 'users_role';
			
			$username = $this->class_settings[ 'username' ];
			$username_key = isset( $this->class_settings[ 'username_key' ] )?$this->class_settings[ 'username_key' ]:'';
			$upassword = isset( $this->class_settings[ 'upassword' ] )?$this->class_settings[ 'upassword' ]:'';
			
			$password = isset( $this->class_settings[ 'password' ] ) ? $this->class_settings[ 'password' ] : '';
			$table = isset( $this->class_settings[ 'table' ] ) ? $this->class_settings[ 'table' ] : 'users';
			$plugin = isset( $this->class_settings[ 'plugin' ] ) ? $this->class_settings[ 'plugin' ] : '';
            
			$skip_password = false;
            if( isset( $this->class_settings[ 'skip_password' ] ) )
                $skip_password = $this->class_settings[ 'skip_password' ];
			
			$project = get_project_data();
			
			//1. DETERMINE LOGIN TYPE
			//users login
			
			//2. OBTAIN RECORD WITH SAME USERNAME
			$u = '';
			switch( $table ){
			case 'customer':
			case 'coop':
				$u = new cCustomers();
			break;
			case 'hr':
			case 'staff':
				$u = new cUsers_employee_profile();
			break;
			case 'users':
			default:
				$u = 'c'.$table;
				if( class_exists( $u ) ){
					$u = new $u();
				}else{
					return $this->_display_notification( array( 'type' => 'error', 'Invalid Table for Authentiaction' ) );
				}
			break;
			}

			$u->class_settings = $this->class_settings;
			$this->_set_table_fields( $u->table_name );

			$ac = new cAccess_roles();
			//2. OBTAIN RECORD WITH SAME USERNAME
			/* 
			$query = "SELECT `".$this->table_name."`.`users006` as 'password', `".$this->table_name."`.`id`, `".$this->table_name."`.`users009` as 'privilege', `".$table1."`.`access_roles001` as 'role', `".$this->table_name."`.`users018` as 'photograph', `".$this->table_name."`.`users001` as 'firstname', `".$this->table_name."`.`users002` as 'lastname', `".$this->table_name."`.`users004` as 'email', `".$this->table_name."`.`users003` as 'remote_user_id' FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."`, `" . $this->class_settings[ 'database_name' ] . "`.`".$table1."` WHERE `".$this->table_name."`.`record_status` = '1' AND `".$this->table_name."`.`users009` =  `".$table1."`.`id` AND `".$this->table_name."`.`users004` = '" . $username . "' LIMIT 1";
			 */
			
			if( ! isset( $u->table_fields[ $username_key ] ) ){
				$username_key = 'email';
			}

			$select = array();

			$no_role = 0;
			$where = array();
			$where[] = " `".$this->table_name."`.`record_status` = '1' ";
			$join = '';

			switch( $table ){
			case 'coop':
			case 'customer':
			case 'customers':
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["first_name"] . "` as 'firstname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["name"] . "` as 'lastname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["phone"] . "` as 'phone_number' ";
				$select[] = " 'customer' as 'privilege' ";
				$select[] = " 'customer' as 'role' ";
			break;
			case 'staff':
			case 'hr':
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["firstname"] . "` as 'firstname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["lastname"] . "` as 'lastname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["status"] . "` as 'status' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["phone_number"] . "` as 'phone_number' ";
			break;
			case 'tele_health_customer':
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["first_name"] . "` as 'firstname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["last_name"] . "` as 'lastname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["status"] . "` as 'status' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["phone"] . "` as 'phone_number' ";
				$select[] = " '". $u->table_name ."' as 'privilege' ";
				$select[] = " '".$u->label."' as 'role' ";

				// $where[] = " AND ( `".$u->table_name."`.`" . $u->table_fields[ 'status' ] . "` = 'active' ) ";
				$send_verify_email = intval( get_general_settings_value( array( 'key' => 'ALLOW VERIFICATION VIA EMAIL 4 TELE-HEALTH REGISTRATION', 'table' => 'hospital' ) ) );
				if( $send_verify_email ){
					
				}
			break;
			case 'eosic_frontend_users':
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["locked"] . "` as 'locked' ";
				$select[] = " '". $u->table_name ."' as 'privilege' ";
				$select[] = " '".$u->label."' as 'role' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["firstname"] . "` as 'firstname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["lastname"] . "` as 'lastname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["status"] . "` as 'status' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["phone_number"] . "` as 'phone_number' ";
			break;
			default:
			case 'users':
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["locked"] . "` as 'locked' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["role"] . "` as 'privilege' ";
				$select[] = " `".$ac->table_name."`.`".$ac->table_fields["role_name"]."` as 'role' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["firstname"] . "` as 'firstname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["lastname"] . "` as 'lastname' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["status"] . "` as 'status' ";
				$select[] = " `".$u->table_name."`.`" . $u->table_fields["phone_number"] . "` as 'phone_number' ";
				if( isset( $u->table_fields["department"] ) )$select[] = " `".$u->table_name."`.`" . $u->table_fields["department"] . "` as 'department' ";
				
				// $where[] = " AND `".$u->table_name."`.`" . $u->table_fields["role"] . "` =  `".$ac->table_name."`.`id` ";

				$join = "JOIN `" . $this->class_settings[ 'database_name' ] . "`.`".$ac->table_name."` ON `".$u->table_name."`.`" . $u->table_fields["role"] . "` =  `".$ac->table_name."`.`id` AND `".$ac->table_name."`.`record_status` = '1' ";
			break;
			}
			// print_r( $select );exit;

			$select[] = " `".$u->table_name."`.`serial_num` ";
			$select[] = " `".$u->table_name."`.`id` ";
			$select[] = " `".$u->table_name."`.`modification_date` ";
			$select[] = " `".$u->table_name."`.`" . $u->table_fields["password"] . "` as 'password' ";
			if( isset( $u->table_fields["photograph"] ) )$select[] = " `".$u->table_name."`.`" . $u->table_fields["photograph"] . "` as 'photograph' ";
			$select[] = " `".$u->table_name."`.`" . $u->table_fields["email"] . "` as 'email' ";

			$where[] = " AND ( `".$u->table_name."`.`" . $u->table_fields[ $username_key ] . "` = '" . $username . "' ) ";

			$query = "SELECT ". implode( ",", $select ) ." FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."` ". $join ." WHERE ". implode( " ", $where ) ." LIMIT 1";
			
			$tables = array( $this->table_name, $table1 );
			if( isset( $this->class_settings[ 'tables' ] ) ){
				$tables = $this->class_settings[ 'tables' ];
			}

			if( isset( $this->class_settings[ 'query' ] ) ){
				$query = $this->class_settings[ 'query' ];
			}
			
			$query_settings = array(
				'database' => $this->class_settings[ 'database_name' ] ,
				'connect' => $this->class_settings[ 'database_connection' ] ,
				'query' => $query ,
				'query_type' => 'SELECT',
				'tables' => $tables,
			);
			$sql_result = execute_sql_query($query_settings);
			// print_r( $sql_result );exit;
			
			$stored_password = '';
			$user_id = '';
			$user_details = array();
			$now = date("U");
			$more_info = '';
			$cutom_error = '';
			
			if($sql_result && is_array($sql_result)){
				$locked = 0;
				$account_status = 0;

				foreach($sql_result as $sval){
					
					$stored_password = $sval['password'];
					$user_id = $sval['id'];

					switch( $table ){
					case 'tele_health_customer':
						switch( $sval[ 'status' ] ){
						case 'active':
							// code...
						break;
						case 'verified':
							$cutom_error = 'This account is not activated';
						break;
						default:
							$cutom_error = 'This account is '.$sval[ 'status' ];
						break;
						}
					break;
					}
							
					$locked = 0;
					$account_status = 0;
					if( isset( $sval["locked"] ) && $sval["locked"] == 'yes' ){
						$locked_time = doubleval( get_general_settings_value( array( "key" => "LOCKED ACCOUNT WAIT TIME", "table" => "users" ) ) );
						
						$locked = 1;
						if( $locked_time && isset( $sval["modification_date"] ) ){
							if( ( $sval["modification_date"] + $locked_time ) < $now ){
								$locked = 0;
							}else{
								$more_info = '<p>Your account will be automatically unlocked at: '.date("d-M-Y H:i", ( $sval["modification_date"] + $locked_time ) ).'</p>';
							}
						}
					}
					
					if( isset( $sval["status"] ) && $sval["status"] != 'active' ){
						$account_status = 1;
					}
					//21-may-23
					if( isset($this->class_settings['skip_status_check']) && $this->class_settings['skip_status_check'] ){
						$account_status = 0;
						unset($this->class_settings['skip_status_check']);
					}										   
					
					$user_details = array(
						'user_id' => $user_id,
						'serial_num' => isset( $sval['serial_num'] ) ? $sval['serial_num'] : '',
						'photograph' => isset( $sval['photograph'] ) ? $sval['photograph'] : '',
						'user_privilege' => isset( $sval['privilege'] ) ? $sval['privilege'] : '',
						'user_role' => isset( $sval['role'] ) ? $sval['role'] : '' ,
						'phone_number' => isset( $sval['phone_number'] ) ? $sval['phone_number'] : '' ,
						//'accessible_functions' => isset( $sval['accessible_functions'] ) ? $sval['accessible_functions'] : '',
						'fname' => isset( $sval['firstname'] ) ? $sval['firstname'] : '',
						'lname' => isset( $sval['lastname'] ) ? $sval['lastname'] : '',
						'email' => isset( $sval['email'] ) ? $sval['email'] : '',
						'verification_status' => isset( $sval['verification_status'] ) ? $sval['verification_status'] : '',
						'department' => isset( $sval['department'] ) ? $sval['department'] : '',
						'remote_user_id' => isset( $sval['remote_user_id'] ) ? $sval['remote_user_id'] : '',
						'table' => isset( $table ) ? $table : '',
						'plugin' => isset( $plugin ) ? $plugin : '',
					);
					
					if( $upassword && function_exists("get_default_password_settings") ){
						$dpass = get_default_password_settings();
						if( $dpass && $dpass == $upassword ){
							$user_details['force_password_change'] = 1;
						}
					}
					
					/*
					if( isset( $sval["data"] ) && $sval["data"] ){
						$dx = json_decode( $sval["data"], true );
						if( isset( $dx["accessible_functions"] ) && ! empty( $dx["accessible_functions"] ) ){
							$user_details["access"] = $dx;
						}
					}
					*/
				}

				if( $cutom_error ){		
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = $cutom_error;
					return $err->error();
				}
				
				if( $account_status ){
					auditor("", "login", "disabled_account", array( "comment" => "Username: " . $username ), $this->class_settings );
					
					if( class_exists("cLogged_in_users") ){
						$lg = new cLogged_in_users();
						$lg->class_settings = $this->class_settings;
						$lg->class_settings["user_id"] = $user_id;
						
						$lg->_update_log( array( 'action' => 'inactive_account' ) );
					}
					
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Disabled Account</h4><p>Please contact your administrators to activate your account</p>';
					return $err->error();
				}
				
				if( $locked ){
					auditor("", "login", "locked_account", array( "comment" => "Username: " . $username ), $this->class_settings );
					
					$err = new cError('010014');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;
					$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
					$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
					$err->additional_details_of_error = '<h4>Locked Account</h4><p>Please contact your administrators to unlock your account</p>' . $more_info;
					return $err->error();
				}
			}else{
				//Failed Authentication - wrong username
				$err = new cError('010101');
				$err->action_to_perform = 'notify';
				
				auditor("", "login", "incorrect_username", array( "comment" => "Username: " . $username ), $this->class_settings );
				
				return $err->error();
			}
			
			//4. COMPARE PASSWORD
			if( $skip_password || $stored_password == $password ){
				$err = new cError('010103');
				$err->action_to_perform = 'notify';
				
                if( isset( $this->class_settings[ 'do_not_set_session' ] ) && $this->class_settings[ 'do_not_set_session' ] ){
                    $returned_value_value = 1;
                }else{
                    //Set session variables for currently logged in user
                    $returned_value_value = $this->_set_session($user_details);
                }

				if( $returned_value_value == 1 ){
					if( class_exists("cLogged_in_users") ){
						$lg = new cLogged_in_users();
						$lg->class_settings = $this->class_settings;
						$lg->class_settings["action_to_perform"] = "control_user_session";
						$lg->class_settings["user_id"] = $user_id;
						
						$lg->logged_in_users();
					}
					
					//INSERT LOGIN TIME INTO ACCESS LOG TABLE
					auditor("", "login", "successful", array( "comment" => "Username: " . $username ), $this->class_settings );
					
					$return = $err->error();
					
					$return['user_details'] = $user_details;
					
					return $return;
				}else{
					switch($returned_value_value){
					case -1:
						//report unset session key variable
						auditor("", "login", "no_session_key", array( "elevel" => "fatal", "comment" => "Authentication: No session key set" ), $this->class_settings );
						
						$err = new cError('000004');
						$err->action_to_perform = 'notify';
						return $err->error();
					break;
					default:
						auditor("", "login", "no_session_key", array( "elevel" => "fatal", "comment" => "Authentication: No hashed session key set" ), $this->class_settings );
						
						$err = new cError('010104');
						$err->action_to_perform = 'notify';
						return $err->error();
					break;
					}
				}
			}else{
				//Failed Authentication - wrong password
				$uname = '';
				if( isset( $user_details["fname"] ) ){
					$uname = $user_details["fname"] . ' ' . $user_details["lname"];
				}
				
				auditor("", "login", "incorrect_password", array( "comment" => "User: " . $uname ), $this->class_settings );
				
				if( ! isset( $_SESSION["failed_count"][ $user_id ] ) ){
					$_SESSION["failed_count"][ $user_id ] = 0;
				}
				++$_SESSION["failed_count"][ $user_id ];
			
					if( $_SESSION["failed_count"][ $user_id ] >= 3 ){
					//lock account
					$_POST["id"] = $user_id;
					
					$u = new cUsers();
					$u->class_settings = $this->class_settings;
					$u->class_settings["user_id"] = 'system';
					$u->class_settings["action_to_perform"] = 'lock_account';
					$u->users();
					
					if( class_exists("cLogged_in_users") ){
						$lg = new cLogged_in_users();
						$lg->class_settings = $this->class_settings;
						$lg->class_settings["user_id"] = $user_id;
						
						$lg->_update_log( array( 'action' => 'locked_account' ) );
					}

					$_SESSION["failed_count"][ $user_id ] = 0;
				}
				
				$err = new cError('010102');	//disabled for security reasons
				//$err = new cError('010101');
				$err->action_to_perform = 'notify';
				
				return $err->error();
			}
		}
		
		private function _reset_user_password(){
			$callback = isset( $_POST[ 'callback' ] ) && $_POST[ 'callback' ] ? $_POST[ 'callback' ] : '';
			$redirect_url = isset( $_POST[ 'redirect_url' ] ) && $_POST[ 'redirect_url' ] ? $_POST[ 'redirect_url' ] : '';
			
			$fparams = isset( $_POST[ 'formParams' ] ) && $_POST[ 'formParams' ] ? json_decode( $_POST[ 'formParams' ], 1 ) : [];

			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
			}

			if( ! ( isset( $_POST[ 'email' ] ) && $_POST[ 'email' ] ) ){
				return $this->_display_notification( array( 'type' => 'error', 'message' => 'Invalid Email Address' ) );
			}

			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$error_type = 'error';
			$error_msg = '';
			$email = $_POST[ 'email' ];
			$table = isset( $_POST[ 'table' ] ) && $_POST[ 'table' ] ? $_POST[ 'table' ] : 'users';
			$username_key = isset( $_POST[ 'username_key' ] ) && $_POST[ 'username_key' ] ? $_POST[ 'username_key' ] : 'email';
			
			$plugin = $u = '';
			switch( $table ){
			case 'users':
				$u = new cUsers();
			break;
			case 'customer':
			case 'customers':
			case 'coop':
				$u = new cCustomers();
			break;
			case 'hr':
			case 'staff':
				$u = new cUsers_employee_profile();
			break;
			default:
				$plugin = isset( $_POST[ 'plugin' ] ) && $_POST[ 'plugin' ] ? $_POST[ 'plugin' ] : $plugin;
			break;/* 
			default:
				return $this->_display_notification( array( 'type' => 'error', 'message' => 'Invalid Table for Authentiaction' ) );
			break; */
			}
			
			if( $plugin ){
				$nwp = 'c' . ucwords( $plugin );
				if( class_exists( $nwp ) ){
					$nwp = new $nwp();
					$nwp->class_settings = $this->class_settings;
					$ups = $nwp->load_class( array( 'class' => array( $table ), 'initialize' => 1 ) );
					$u = $ups[ $table ];
				}else{
					$error_msg = "The Plugin Class '". $nwp ."' does not exist";
				}
			}

			if( ! $error_msg ){
				$this->_set_table_fields( $u->table_name );

				if( ! isset( $u->table_fields[ $username_key ] ) ){
					$username_key = 'email';
				}

				$select = array();
				$where[] = " `".$this->table_name."`.`record_status` = '1' ";
				$join = '';

				if( isset( $this->class_settings["select"] ) && is_array( $this->class_settings["select"] ) && ! empty( $this->class_settings["select"] ) ){
					$select = $this->class_settings["select"];
				}else{
					switch( $table ){
					case 'users':
					case 'staff':
					case 'hr':
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["firstname"] . "` as 'firstname' ";
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["lastname"] . "` as 'lastname' ";
					break;
					case 'coop':
					case 'customer':
					case 'customers':
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["first_name"] . "` as 'firstname' ";
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["name"] . "` as 'lastname' ";
					break;
					case 'tele_health_customer':
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["first_name"] . "` as 'firstname' ";
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["last_name"] . "` as 'lastname' ";
					break;
					}

					if( isset( $u->table_fields[ 'email' ] ) && $u->table_fields[ 'email' ] ){
						$select[] = " `".$u->table_name."`.`" . $u->table_fields["email"] . "` as 'email' ";
					}else{
						return $this->_display_notification( array( 'type' => 'error', 'message' => 'Invalid Table for Authentiaction' ) );
					}
					$select[] = " `".$u->table_name."`.`id` ";
				}


				// $where[] = " AND MD5(`" . $this->table_fields[ $username_key ] . "`) = '" . md5( $this->class_settings[ 'user_email' ] ) . "' ";
				$where[] = " AND `" . $this->table_fields[ $username_key ] . "` = '" . $email . "' ";

				$q = "SELECT ". implode( ",", $select ) ." FROM `" . $this->class_settings[ 'database_name' ] . "`.`".$this->table_name."` ". $join ." WHERE ". implode( " ", $where ) ." LIMIT 1";
				
				/***********************/
				//1 - SINGLE
				$query_settings = array(
					'database' => $this->class_settings[ 'database_name' ],
					'connect' => $this->class_settings[ 'database_connection' ],
					'query' => $q,
					'query_type' => 'SELECT',
					'tables' => array( $u->table_name ),
				);
				/***********************/
				
				$query_result = execute_sql_query($query_settings);

				// print_r( $query_result );exit;
				if( $query_result && is_array( $query_result ) && $query_result[0] ){
					
					//SET USER ID
					$user_id = $query_result[0]['id'];
					
					//First Name & Last Name
					$user_fname = $query_result[0][ 'firstname' ];
					$user_lname = $query_result[0][ 'lastname' ];
					
					//Create new password
					// $new_password = 'uuui18181';
					$new_password = generatePassword(8,1,1,1,0);
					if( defined("USERS_RESET_ACCOUNT_WITH_DEFAULT_PASSWORD") && USERS_RESET_ACCOUNT_WITH_DEFAULT_PASSWORD && function_exists("get_default_password_settings") ){
						$upassword = get_default_password_settings();
						if( $upassword ){
							$new_password = $upassword;
						}
					}
					$hashed_password = md5( $new_password . get_websalter() );
					
					//Update records
					$query = "UPDATE `".$this->class_settings['database_name']."`.`" . $u->table_name . "` SET `".$u->table_name."`.`" . $u->table_fields[ 'password' ] . "`='" . $hashed_password . "', `".$u->table_name."`.`modification_date`='" . date("U") . "' WHERE `".$u->table_name."`.`id` = '" . $user_id . "' AND `".$u->table_name."`.`record_status`='1' LIMIT 1";
					
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query,
						'query_type' => 'UPDATE',
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					
					$saved = execute_sql_query($query_settings);
					//print_r( $saved ); exit;
					//Auditor
					auditor( $this->class_settings[ 'calling_page' ] , '' , $user_id , $this->class_settings[ 'user_email' ] , $this->class_settings[ 'database_connection' ] , $this->class_settings[ 'database_name' ] , 'password reset' , $this->table_name , 'user ' . $this->class_settings[ 'user_email' ] . ' password reset at ' . date("j-M-Y H:i") );
					
					//if( isset( $saved["success"] ) && $saved["success"] ){
						$pr = get_project_data();
						//MAIL NEW PASSWORD
						$cname = ( isset( $pr[ 'company_name' ] ) ? $pr[ 'company_name' ] : '' );
						$this->class_settings['message_type'] = 100;	//Password Reset
						
						$a[ 'user_full_name' ] =  ucwords($user_fname.' '.$user_lname);
						$a[ 'user_id' ] = $user_id;
						$a[ 'user_email' ] = $email;
						
						$this->class_settings[ 'subject' ] = $cname . ' Password Reset';
						$bf = '';
						if( isset( $this->class_settings[ 'before_message' ] ) && $this->class_settings[ 'before_message' ] ){
							$bf = $this->class_settings[ 'before_message' ];
						}
						
						$this->class_settings[ 'message' ] = $bf . 'New '. $cname .' Password: ' . $new_password . '<br /><br />Generated by '. $cname .' On: ' . date("j-M-Y H:i");
						
						if( isset( $this->class_settings[ 'after_message' ] ) && $this->class_settings[ 'after_message' ] ){
							$this->class_settings[ 'message' ] .= $this->class_settings[ 'after_message' ];
						}
						
						$this->class_settings[ 'mail_certificate' ] = array(
							'new_password' => $new_password,
							'created_on' => date("j-M-Y H:i"),
						);
						
						//$msg1 = '<br /><strong>NEW PASSWORD:</strong>'. $new_password .'<br />';
						//$msg1 .= '<br /><strong>GENERATED AT:</strong>'. date("j-M-Y H:i") .'<br />';
				
				
						$ar["subject"] = $cname . ' Password Reset';;
						//$ar["info"] = $msg1;
						$ar["content"] = $this->class_settings[ 'message' ];
						
						$ar["single_email"] = $email;
						$ar["single_name"] = ucwords($user_fname.' '.$user_lname);
						
						$b = new cCustomer_call_log();
						$b->class_settings = $this->class_settings;
						$r = $b->_send_email_notification( $ar );
						
						$error = '';
						if( isset( $r["success"] ) && $r["success"] ){
							$error_type = "success";
							$error = $r["success"]; 

						}else if( isset( $r["response"] ) && $r["response"] ){
							$error = $r["response"];
						}
						
					//print_r( $this->_send_email( $a ) );
						//$this->_send_email( $a );
					//}
					
					//Return Successful password reset
					$err = new cError('010106');
					$err->action_to_perform = 'notify';
					$err->html_format = 2;

					$err->additional_details_of_error = $error;
					if( get_hyella_development_mode() )$err->additional_details_of_error .= '<br />' . $new_password;
					
					$err->class_that_triggered_error = 'cAuthentication.php';
					
					$return = $err->error();
					
					$loc = get_default_location();
					
					$return["html_replacement"] = $return["html"];
					$return["html_replacement_selector"] = $handle;
					$return["status"] = 'new-status';
					$return["success"] = 1;
					
					unset( $return["html"] );
					
					return $return;
					
				}
				
				//Auditor
				auditor( $this->class_settings[ 'calling_page' ] , '' , '' , $this->class_settings[ 'user_email' ] , $this->class_settings[ 'database_connection' ] , $this->class_settings[ 'database_name' ] , 'password reset failed' , $this->table_name , 'user ' . $this->class_settings[ 'user_email' ] . ' failed to reset password at ' . date("j-M-Y H:i") );
				
				//Return Failed Password Reset - invalid email
				$err = new cError('010105');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAuthentication.php';
				
				return $err->error();
			}
			
			return $this->_display_notification( array( 'type' => 'error', 'message' => $error_msg ) );
		}
		
		private function _send_email( $a = array() ){
			//Send Successful Email Verification Message
			if( isset( $a[ 'user_email' ] ) && isset( $a[ 'user_full_name' ] ) && isset( $a[ 'user_id' ] ) && $a[ 'user_id' ] && $a[ 'user_full_name' ] && $a[ 'user_email' ] && $a[ 'user_id' ] ){
				$email = new cEmails();
				$email->class_settings = $this->class_settings;
				
				$email->class_settings[ 'action_to_perform' ] = 'send_mail';
				
				$email->class_settings[ 'destination' ]['email'][] = $a[ 'user_email' ];
				$email->class_settings[ 'destination' ]['full_name'][] = $a[ 'user_full_name' ];
				$email->class_settings[ 'destination' ]['id'][] = $a[ 'user_id' ];
				
				$email->mail_certificate = $this->class_settings[ 'mail_certificate' ];
				
				$x = $email->emails();
				// print_r( $x );
			}
			
		}
		
		public function _set_session( $user_details ){
			if(isset($_SESSION['key']) && $_SESSION['key']){
				
				//1. VALIADTE INPUT DATA
				if(isset($user_details['user_id']) && $user_details['user_id']){
					
					//2. ENCRYPT SESSION ID
					$key = md5('ucert'.$_SESSION['key']);
					if( isset( $_SESSION[$key] ) )
						unset($_SESSION[$key]);
					
					//3. GET USER BIO DETAILS FROM USERS CLASS
					
					//4. SET SESSION
					$_SESSION[$key] = array(
						//Set First Name and Last Name
						'fname' => ucwords($user_details['fname']),
						'lname' => ucwords($user_details['lname']),
						'serial_num' => ucwords($user_details['serial_num']),
						'phone_number' => ucwords($user_details['phone_number']),
						
						//Abbreviated Name
						'initials' => ucwords(substr($user_details['fname'],0,1).' '.$user_details['lname']),
						
						'email' => strtolower($user_details['email']),
						
						//Set User Role
						'role' => ucwords($user_details['user_role']),
						
						//Profile Picture
						'photograph' => isset( $user_details['photograph'] ) ? $user_details['photograph'] : '',
						'department' => isset( $user_details['department'] ) ? $user_details['department'] : '',
						
						//Set User ID
						'id' => $user_details['user_id'],
						
						//Set User Privilege
						'privilege' => $user_details['user_privilege'],
						
						//Accessible Functions
						//'functions' => $user_details['accessible_functions'],
						
						//Set Login Time
						'login_time' => date("U"),
						
						//Set Account Verification Status
						'verification_status' => $user_details['verification_status'],
						
						'remote_user_id' => $user_details[ 'remote_user_id' ],
						'force_password_change' => isset( $user_details[ 'force_password_change' ] )?$user_details[ 'force_password_change' ]:'',
						'table' => isset( $user_details[ 'table' ] )?$user_details[ 'table' ]:'',
						'plugin' => isset( $user_details[ 'plugin' ] )?$user_details[ 'plugin' ]:'',
					);
					
					//force notification
					$nb = 'cNwp_client_notification';
					if( class_exists( $nb ) ){
						$nb = new $nb();
						$nb->force_client_notification( 'authentication' );
					}
				}
				//5. RETURN SUCCESS
				if(isset($_SESSION[$key]) && is_array($_SESSION[$key])){
					$_SESSION["ucert"]["lu_sn"] = isset( $user_details['serial_num'] )?$user_details['serial_num']:'';
					return 1;
				}else{
					//log error
					return 0;
				}
			}else{
				//unset session key variable
				return -1;
			}
		}
		
		//BASED METHODS
		private function _get_html_view(){
			$script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			
			unset($this->class_settings[ 'data' ]);
			unset($this->class_settings[ 'html' ]);
			
			return $script_compiler->script_compiler();
		}
		
		function _display_notification( $params = array() ){
			$code = '010014';
			$msg = isset( $params["message"] )?$params["message"]:'';
			$do_not_display = isset( $params["do_not_display"] )?$params["do_not_display"]:0;
			
			$html_add = isset( $params["html_add"] )?$params["html_add"]:'';
			$html_replacement = isset( $params["html_replacement"] )?$params["html_replacement"]:'';
			
			$html_replacement_selector = isset( $params["html_replacement_selector"] )?$params["html_replacement_selector"]:'';
			$js = isset( $params["callback"] )?$params["callback"]:array();
			
			if( isset( $params["type"] ) ){
				switch( $params["type"] ){
				case "success":
					$code = '010011';
				break;
				case "info":
					$code = '010016';
				break;
				case "no_language":
					$code = '000017';
				break;
				}
			}
			
			$err = new cError( $code );
			$err->html_format = 2;
			$err->action_to_perform = 'notify';
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $msg;
			$return = $err->error();
			
			if( $html_replacement_selector ){
				$return["html_replacement_selector"] = $html_replacement_selector;
				$return["html_replacement"] = $return["html"];
				$return["status"] = "new-status";
				
				if( $html_add ){
					$return["html_replacement"] = $return["html"] . $html_add;
				}
				
				if( $html_replacement ){
					$return["html_replacement"] = $html_replacement;
				}
				
				if( $do_not_display ){
					unset( $return["msg"] );
					unset( $return["typ"] );
				}
				
				unset( $return["html"] );
			}
			
			if( ! empty( $js ) ){
				$return["status"] = "new-status";
				unset( $return["html"] );
				
				$return["javascript_functions"] = $js;
			}
			
			return $return;
		}
	}
?>
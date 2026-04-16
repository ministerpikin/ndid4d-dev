<?php 
	// Initialize current user details array
	if( ! isset( $default_country ) ){
		$default_country = array(
            'id' => '1',
            'country' => 'Worldwide',
            'iso_code' => 'GLOBAL', 
            'flag' => 'GLOBAL', 
            'conversion_rate' => 1,
            //'currency' => '$',
            'currency' => 'NGN',
            'language' => 'US',
        );
	}
	
	$current_user_session_details = array(
		'id' => '',
		'email' => '',
		'fname' => '',
		'lname' => '',
		'privilege' => '',
		'role' => '',
		'photograph' => '',
		'login_time' => '',
		'verification_status' => '',
		'remote_user_id' => '',
		'country' => '',
		'department' => '',
		'store' => '',
		'table' => '',
		'plugin' => '',
	);
	
	// Get current user details session key variable
	$current_user_details_session_key = md5( 'ucert' . $_SESSION['key'] );
	
    $user_country_id = '';
    
	// Get current user details session settings
	if( isset( $_SESSION[ $current_user_details_session_key ] ) ) {
		
		$current_user_session_details = $_SESSION[ $current_user_details_session_key ];
		
		if( isset( $current_user_session_details['country'] ) && $current_user_session_details['country'] ){
			$user_country_id = $current_user_session_details['country'];
		}
		
		$access = array();
		if( isset( $current_user_session_details["privilege"] ) && $current_user_session_details["privilege"] ){
			if( $current_user_session_details["privilege"] == "1300130013" ){
				$super = 1;
				$access["super"] = 1;
			}else if( isset( $current_user_session_details["privilege"] ) && $current_user_session_details["privilege"] == 'hr-portal' ){
			}else{
				
				$u = get_record_details( array( "id" => $current_user_session_details["id"], "table" => "users" ) );
				
				if( isset( $u["status"] ) && $u["status"] == 'active' && isset( $u["locked"] ) && $u["locked"] != 'yes' ){
					$xval = get_record_details( array( "id" => $current_user_session_details["privilege"], "table" => "access_roles" ) );
					if( isset( $xval["id"] ) ){
						if( isset( $xval["data"] ) && $xval["data"] ){
							$dx = json_decode( $xval["data"], true );
							if( isset( $dx["accessible_functions"] ) && ! empty( $dx["accessible_functions"] ) ){
								$access = $dx;
								
								//MIS - customization
								if( isset( $access["status"]["access_my_state"] ) && $access["status"]["access_my_state"] ){
									if( isset( $u["state"] ) && $u["state"] ){
										$access["status"]["access_my_state"] = $u["state"];
										$access["states"][ $u["state"] ] = 1;
										
										if( isset( $access["status"]["access_my_lga"] ) && $access["status"]["access_my_lga"] ){
											if( isset( $u["lga"] ) && $u["lga"] ){
												$access["status"]["access_my_lga"] = $u["lga"];
												$access["lga"][ $u["state"] ][ $u["lga"] ] = 1;
											}
										}else{
											$access["lga"][ $u["state"] ][ "all" ] = 1;
										}
									}
								}
								
								if( ! ( isset( $access[ "status" ]["stores"]["access_all_stores"] ) && $access[ "status" ]["stores"]["access_all_stores"] ) ){
									if( isset( $access[ "status" ]["stores"]["access_my_store"] ) && $access[ "status" ]["stores"]["access_my_store"] ){
										
										if( isset( $u[ "country" ] ) && $u[ "country" ] ){
											$access["my_store"] = $u[ "country" ];
											$access[ "status" ]["stores"][ $access["my_store"] ] = 1;
											$current_user_session_details["store"] = $access["my_store"];
										}
									}
								}
							}
						}
						
						$a = $xval["accessible_functions"] ? explode( ":::" , $xval["accessible_functions"] ) : [];
						if( is_array( $a ) && ! empty( $a ) ){
							foreach( $a as $k => $v ){
								$access[ $v ] = 1;
							}
						}
						
					}
				}else{
					auditor("", "users", "in_active_account", array( "comment" => "account is in active" ) );
					unset( $_SESSION[ $current_user_details_session_key ] );
					$current_user_session_details = array(
						'id' => '',
						'email' => '',
						'fname' => '',
						'lname' => '',
						'privilege' => '',
						'role' => '',
						'photograph' => '',
						'login_time' => '',
						'verification_status' => '',
						'remote_user_id' => '',
						'country' => '',
						'department' => '',
						'store' => '',
						'table' => '',
						'plugin' => '',
					);
				}
			}
		}
		//print_r( $access ); exit;
		$GLOBALS["access"] = $access;
		
	}
    
    if( ! $user_country_id ){
        //estimate user location as user is not logged in
        $user_loc_data = get_user_geolocation_data();
        if( isset( $user_loc_data['country_id'] ) && $user_loc_data['country_id'] )
            $user_country_id = $user_loc_data['country_id'];
    }
    
    $default_country_id = $user_country_id;
    
    //get country details
    if( isset( $_SESSION['country']['id'] ) ){
        $country_details = $_SESSION['country'];
    }else{
        if( $default_country_id == '1' ){
            $country_details = $default_country;
        }else{
            $country_details = get_countries_details( array( 'id' => $default_country_id ) );
        }
    }
    
    $a = array( 'id', 'country', 'iso_code', 'conversion_rate', 'currency', 'language' );
    foreach( $a as $key ){
        switch( $key ){
        case 'language':
            include "Language_translator.php";
        break;
        case 'iso_code':    
            if( isset( $country_details[ $key ] ) && $country_details[ $key ] ){
                define( strtoupper( 'selected_country_flag' ) , $country_details[ $key ] );
                define( strtoupper( 'selected_country_'.$key ) , $country_details[ $key ] );
            }else{
                define( strtoupper( 'selected_country_flag' ) , $default_country[ $key ] );
                define( strtoupper( 'selected_country_'.$key ) , $default_country[ $key ] );
            }
        break;
        default:
            if( isset( $country_details[ $key ] ) && $country_details[ $key ] ){
                define( strtoupper( 'selected_country_'.$key ) , $country_details[ $key ] );
            }else{
                define( strtoupper( 'selected_country_'.$key ) , $default_country[ $key ] );
            }
        break;
        }
    }
    
    $nigeria_details = get_countries_details( array( 'id' => '1157' ) );
    if( isset( $nigeria_details['conversion_rate'] ) && $nigeria_details['conversion_rate'] ){
    	define( 'NIGERIAN_NAIRA_CONVERSION_RATE', doubleval( $nigeria_details['conversion_rate'] ) );
    }
	//print_r($current_user_session_details);
?>
<?php 
	/**
	 * Calculated Values
	 *
	 * @used in  				classes/cForms.php, includes/ajax_server_json_data.php
	 * @created  				none
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Calculated Values
	|--------------------------------------------------------------------------
	|
	| Functions that define which functions to use in populating select combo 
	| boxes during form generation and dataTables population
	|
	*/
	function evaluate_calculated_value( $settings = array() ){
		if( ! isset( $pagepointer ) )$pagepointer = '../';
		if( ! isset( $fakepointer ) )$fakepointer = '';

		//print_r( $settings ); exit;
		$return = array(
			'value' => '',
			'class' => '',
		);
		
		if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ] ) && isset( $settings[ 'row_data' ] ) && is_array( $settings[ 'row_data' ] ) ){
			
			$var1 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 0 ];
			
            $extra = '';
			$txp_name = $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ];
			switch( $txp_name ){
			case 'total-feed-consumption':
			case 'total-egg-production':
				$var2 = '';
				$return = array(
					'value' => 0,
					'class' => '',
				);
                
				$type = 1;
				switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ){
				case 'total-feed-consumption':
					$type = 2;
				break;
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ][ 0 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ][ 0 ];
				
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$today = doubleval( $settings[ 'row_data' ][ $var1 ] );
					$pen = $settings[ 'row_data' ][ $var2 ];
					
					$p_details = get_days_production_booklet_details( array( "date" => $today ) );
					
					if( isset( $p_details ) && is_array( $p_details ) ){
						
						foreach( $p_details as $val ){
							if( $pen != $val["pen"] )continue;
							
							if( $type == 1 ){
								$return["value"] += doubleval( $val['first_pick'] ) + doubleval( $val['second_pick'] ) + doubleval( $val['third_pick'] ) + doubleval( $val['fourth_pick'] );
							}elseif( $type == 2 ){
								//feed consumption
								$return["value"] += doubleval( $val['first_feed'] ) + doubleval( $val['second_feed'] ) + doubleval( $val['third_feed'] ) + doubleval( $val['fourth_feed'] );
							}
						}
					}
				}
			break;
			case 'workflow_status':
				$return = array(
					'value' => '',
					'class' => '',
				);
				$vll = isset( $settings[ 'add_class' ] ) && isset( $settings[ 'row_data' ][ $settings[ 'add_class' ] ] ) ? $settings[ 'row_data' ][ $settings[ 'add_class' ] ] : '';
				if( $vll ){
					if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
						$c = get_record_details( array( 'id' => $settings[ 'row_data' ][ $var1 ], 'table' => 'workflow_settings' ) );

						if( isset( $c["data"] ) && $c["data"] ){
							$sdd = json_decode( urldecode( $c[ 'data' ] ), true );

							// print_r( $sdd[ 'status' ][ $vll ][ 'name' ] );exit;
							if( isset( $sdd[ 'status' ][ $vll ][ 'name' ] ) && $sdd[ 'status' ][ $vll ][ 'name' ] ){
								$return["value"] = '<b>'.$sdd[ 'status' ][ $vll ][ 'name' ].'</b>';
							}elseif( isset( $sdd[ 'status' ][ $vll ][ 'text' ] ) && $sdd[ 'status' ][ $vll ][ 'text' ] ){
								$return["value"] = '<b>'.$sdd[ 'status' ][ $vll ][ 'text' ].'</b>';
							}
						}
					}
				}

				if( ! $return["value"] ){
					if( $vll && isset( $settings[ 'form_field_data' ][ 'format_function' ] ) && $settings[ 'form_field_data' ][ 'format_function' ] ){
						$tfn = $settings[ 'form_field_data' ][ 'format_function' ];
						$return["value"] = $tfn( $vll );
					}else if( $vll ){
						$return["value"] = $vll;
					}
				}

			break;
			case 'workflow_ref_info':
				$return = array(
					'value' => '',
					'class' => '',
				);
				$vll = isset( $settings[ 'add_class' ] ) && isset( $settings[ 'row_data' ][ $settings[ 'add_class' ] ] ) ? $settings[ 'row_data' ][ $settings[ 'add_class' ] ] : '';
				if( defined( 'HYELLA_V3_WOFKRLOW_BULK_APPROVAL' ) && HYELLA_V3_WOFKRLOW_BULK_APPROVAL && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'ref_field' ] ) && isset( $settings[ 'row_data' ][ $settings[ 'form_field_data' ][ 'calculations' ][ 'ref_field' ] ] ) && $settings[ 'row_data' ][ $settings[ 'form_field_data' ][ 'calculations' ][ 'ref_field' ] ] ){
					if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
						$c = get_record_details( array( 'id' => $settings[ 'row_data' ][ $var1 ], 'table' => 'workflow_settings' ) );

						if( isset( $c["data"] ) && $c["data"] ){
							$sdd = json_decode( urldecode( $c[ 'data' ] ), true );
							if( isset( $sdd[ 'table' ] ) && isset( $sdd[ 'table' ] ) ){
								$xtodo = 'view_details';
								if( isset( $sdd[ 'review_view_details' ] ) && $sdd[ 'review_view_details' ] ){
									$xtodo = $sdd[ 'review_view_details' ];
								}
								$act = '?module=&action='. $sdd[ 'table' ] .'&todo='.$xtodo;
								if( isset( $sdd[ 'plugin' ] ) && $sdd[ 'plugin' ] ){
									$act = '?module=&action='. $sdd[ 'plugin' ] .'&todo=execute&nwp_action='. $sdd[ 'table' ] .'&nwp_todo='.$xtodo;
								}
								$return["value"] = '<a class="custom-single-selected-record-button" title="View '. $sdd[ 'title' ] .' details in a new tab" action="'. $act .'" override-selected-record="'. $settings[ 'row_data' ][ $settings[ 'form_field_data' ][ 'calculations' ][ 'ref_field' ] ] .'" mod="1" targetX="_blank" href="#"><i class="icon-external"></i> View Details</a>';
							}
						}
					}

				}

			break;
			case 'record-details':
				$return = array(
					'value' => '',
					'class' => '',
				);
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'reference_table' ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'reference_keys' ] ) ){
					$multiple = isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'multiple' ] )?$settings[ 'form_field_data' ][ 'calculations' ][ 'multiple' ]:0;
					
					$account = $settings[ 'row_data' ][ $var1 ];
					$accounts = array( $account );
					
					if( $multiple ){
						$accounts = explode(",", $account );
					}
					
					$return["value"] = $account;
					$dx = array();
					
					$meta = isset( $settings[ 'form_field_data' ][ 'calculations' ]["reference_keys_metadata"] )?$settings[ 'form_field_data' ][ 'calculations' ]["reference_keys_metadata"]:array();
					
					foreach( $accounts as $account ){
						
						if( isset( $meta["overwrite_function"] ) && function_exists( $meta["overwrite_function"] ) ){
							$fnc = $meta["overwrite_function"];
							unset( $meta["overwrite_function"] );
							$meta["row_data"] = $settings[ 'row_data' ];
							$dx[] = $fnc(  $meta);
						}else{
						
							$c = get_record_details( array( "id" => $account, "table" => $settings[ 'form_field_data' ][ 'calculations' ][ 'reference_table' ] ) );
							
							if( is_array( $c ) && ! empty( $c ) ){
								$rkeys = $settings[ 'form_field_data' ][ 'calculations' ][ 'reference_keys' ];
								if( is_array( $rkeys ) && ! empty( $rkeys ) ){
									$return["value"] = '';
									
									foreach( $rkeys as $ckey ){
										if( isset( $c[ $ckey ] ) && isset( $c[ "id" ] ) ){
											if( $ckey == "serial_num" ){
												$c[ $ckey ] = '['.$c[ $ckey ].']';
											}
											
											if( isset( $meta[ $ckey ]["function"] ) && function_exists( $meta[ $ckey ]["function"] ) ){
												$fnc = $meta[ $ckey ]["function"];
												unset( $meta[ $ckey ]["function"] );
												$meta[ $ckey ]["id"] = $c[ $ckey ];
												$meta[ $ckey ]["row_data"] = $settings[ 'row_data' ];
												$c[ $ckey ] = $fnc(  $meta[ $ckey ] );
											}
											
											if( isset( $dx[ $c[ "id" ] ] ) && $dx[ $c[ "id" ] ] ){
												$dx[ $c[ "id" ] ] .= ' ' . $c[ $ckey ];
											}else{
												$dx[ $c[ "id" ] ] = $c[ $ckey ];
											}
										}else if( isset( $c[ "id" ] ) ){
											if( isset( $dx[ $c[ "id" ] ] ) )$dx[ $c[ "id" ] ] .= $ckey;
										}
									}
									
								}
							}else{
								$dx[] = trim($account);
							}
						}
					}
					
					$show_in_form = isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'show_in_form' ] )?$settings[ 'form_field_data' ][ 'calculations' ][ 'show_in_form' ]:0;
					
					$return["value"] = implode( ', ', $dx );
					
					$adx = isset( $settings[ 'form_field_data' ][ 'calculations' ]['data_fields'] )?$settings[ 'form_field_data' ][ 'calculations' ]['data_fields']:array();
					
					if( is_array( $adx ) && ! empty( $adx ) ){
						
						foreach( $adx as $adk => $adv ){
							if( isset( $adv['key'] ) && isset( $settings[ 'row_data' ][ $adv['key'] ] ) ){
								$ddx = json_decode( $settings[ 'row_data' ][ $adv['key'] ], true );
								if( isset( $ddx[ $adk ] ) && $ddx[ $adk ] ){
									if( $adk == 'walkin_patient' && isset( $ddx["walkin_patient_serial_num"] ) && $ddx["walkin_patient_serial_num"] ){
										$ddx[ $adk ] .= ' ['. $ddx["walkin_patient_serial_num"] .']';
									}
									$return["value"] .= '<br />--'.$ddx[ $adk ];
								}
							}
						}
					}
					
					if( $show_in_form ){
						if( isset( $settings['source'] ) && $settings['source'] == 'form' ){
							$return["value"] = $dx;
						}else{
							$delim = ', ';
							if( isset( $settings['source'] ) && $settings['source'] ){
								$delim =  $settings['source'];
							}
							$return["value"] = implode( $delim, $dx );
						}
					}
					
				}
			break;
			case 'item-details':
				$return = array(
					'value' => '',
					'class' => '',
				);
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$c = get_items_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );

					if( ! isset( $c[ 'id' ] ) ){
						$c = get_record_details( array( 'id' => $settings[ 'row_data' ][ $var1 ], 'table' => 'sub_items' ) );
					}

					$return["value"] = $settings[ 'row_data' ][ $var1 ];
					if( isset( $c["description"] ) )$return["value"] = $c["description"] . " (".$c["barcode"].")";
				}
			break;
			case 'hmo-details-from-customer':
				$return = array(
					'value' => '',
					'class' => '',
				);
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$c = get_record_details( array( 'id' => $settings[ 'row_data' ][ $var1 ], 'table' => 'customers' ) );

					$return["value"] = "Private";
					if( isset( $c["type_organisation"] ) && $c["type_organisation"] ){
						$c1 = get_record_details( array( 'id' => $c[ 'type_organisation' ], 'table' => 'hmo' ) );

						$return["value"] = $c1["name"];
					}
				}
			break;
			case 'item-details-consultation':
				$return = array(
					'value' => $settings[ 'row_data' ][ $var1 ],
					'class' => '',
				);
				
				$c_field = isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'condition_field' ] )?$settings[ 'form_field_data' ][ 'calculations' ][ 'condition_field' ]:'';
				$d_field = isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'data_field' ] )?$settings[ 'form_field_data' ][ 'calculations' ][ 'data_field' ]:'';
				
				$tb_key = 'items';
				$jdx = array();
				if( isset( $settings[ 'row_data' ][ $c_field ] ) ){
					switch( $settings[ 'row_data' ][ $c_field ] ){
					case "vac_plan":
						$jdx = json_decode( isset( $settings[ 'row_data' ][ $d_field ] )?$settings[ 'row_data' ][ $d_field ]:'', true );
						$tb_key = 'vac_plan';
						//print_r( $jdx ); exit;
					break;
					}
				}
				
				
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && $settings[ 'row_data' ][ $var1 ] ){
					$cx = explode( ",", $settings[ 'row_data' ][ $var1 ] );
					
					if( ! empty( $cx ) ){
						$rtx = array();
						
						foreach( $cx as $cxv ){
							
							switch( $tb_key ){
							case "vac_plan":
								if( isset( $jdx["cache"][ $tb_key ] ) ){
									$rtx[ $cxv ] = $jdx["cache"][ $tb_key ];
								}
							break;
							default:
								$c = get_name_of_referenced_record( array( "id" => $cxv, "table" => $tb_key, "return_data" => 1 ) );
								if( isset( $c["description"] ) ){
									$rtx[ $cxv ] = $c["description"] . " [".$c["barcode"]."] = " . convert_currency( $c["selling_price"] );
								}else if( isset( $c["name"] ) ){
									$rtx[ $cxv ] = $c["name"];
								}else{
									$rtx[ $cxv ] = $cxv;
								}
							break;
							}
							//$c = get_items_details( array( "id" => $cxv ) );
							
						}
						
						$delimit = "<br /><br />";
						if( isset( $settings['source'] ) && $settings['source'] == 'form' ){
							$delimit = ":::";
							$return["value"] =  $rtx;
						}else{
							if( isset( $settings['source'] ) && $settings['source'] ){
								$delimit =  $settings['source'];
							}
							$return["value"] =  implode( $delimit, $rtx );
						}
						
					}
					
				}
			break;
			case 'item-details-2':
				$return = array(
					'value' => isset( $settings[ 'row_data' ][ $var1 ] ) ? $settings[ 'row_data' ][ $var1 ] : '',
					'class' => '',
				);
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && $settings[ 'row_data' ][ $var1 ] ){
					$cx = explode( ",", $settings[ 'row_data' ][ $var1 ] );
					
					if( ! empty( $cx ) ){
						$rtx = array();
						
						foreach( $cx as $cxv ){
							$c = get_items_details( array( "id" => $cxv ) );
							if( isset( $c["description"] ) )$rtx[] = $c["description"];
						}
						
						$delimit = "<br />";
						if( isset( $settings['source'] ) && $settings['source'] == 'form' ){
							$delimit = ', ';
						}else{
							if( isset( $settings['source'] ) && $settings['source'] ){
								$delimit =  $settings['source'];
							}
						}
						
						$return["value"] =  implode( $delimit, $rtx );
					}
					
				}
			break;
			case 'vendor-customer-id':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					switch( $settings[ 'row_data' ][ $var2 ] ){
					case "returned":
						$c = get_customers_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );
						if( isset( $c["name"] ) && $c["name"] ){
							$return["value"] = $c["name"];
						}else{
							$return["value"] = $settings[ 'row_data' ][ $var1 ];
						}
						//$return["value"] = $settings[ 'row_data' ][ $var1 ];
					break;
					default:
						$c = get_vendors_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );
						if( isset( $c["name"] ) && $c["name"] ){
							$return["value"] = $c["name"];
						}else{
							$return["value"] = $settings[ 'row_data' ][ $var1 ];
						}
						$return["value"] = $settings[ 'row_data' ][ $var1 ];
					break;
					}
					
				}
				//$return["value"] = $var2;
				return $return;
			break;
			case 'pen-days':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$today = doubleval( $settings[ 'row_data' ][ $var2 ] );
					
					$pen_details = get_pen_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( $today && isset( $pen_details['date'] ) && $pen_details['date'] ){
						$pen = doubleval( $pen_details['date'] );
						$days = ( ( $today - $pen ) / ( 3600*24 ) ) + 1;
						$return["value"] = $days;
					}
				}
				return $return;
			break;
			case 'pen-details':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] )  ){
					
					$pen_details = get_pen_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $pen_details['date'] ) && $pen_details['date'] ){
						$return["value"] = $pen_details['pen_name'] . " - " . date("M-Y", doubleval( $pen_details['date'] ) );
					}
				}
				return $return;
			break;
			case 'pen-weeks':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$today = doubleval( $settings[ 'row_data' ][ $var2 ] );
					
					$pen_details = get_pen_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( $today && isset( $pen_details['date'] ) && $pen_details['date'] ){
						$pen = doubleval( $pen_details['date'] );
						$days = ceil( ( ( ( $today - $pen ) / ( 3600*24 ) ) + 1 ) / 7 );
						$return["value"] = $days;
					}
				}
				return $return;
			break;
			case 'production-ref-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_production_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>".$sales_details['serial_num']."-".$sales_details['id']."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'membership_plan':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "membership_plan" ) );
					
					if( isset( $sales_details['description'] ) && $sales_details['description'] ){
						$return["value"] = $sales_details['description'];
					}
				}
				
				return $return;
			break;
			case 'hmo':
			case 'ward':
			case 'vendors':
			case 'users':
			case 'customers':
				$var2 = '';
				// print_r( $txp_name );
				// print_r( $settings );
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$ab = array("id" => trim( $settings[ 'row_data' ][ $var1 ] ), "table" => $txp_name );
					if( isset( $settings[ 'source' ] ) && $settings[ 'source' ] == 'form' ){
						$ab[ 'html_friendly' ] = 1;
					}
					
					$return["value"] = get_name_of_referenced_record( $ab );
					// print_r( $return );exit;
					$adx = isset( $settings[ 'form_field_data' ][ 'calculations' ]['data_fields'] )?$settings[ 'form_field_data' ][ 'calculations' ]['data_fields']:array();
					
					if( is_array( $adx ) && ! empty( $adx ) ){
						
						foreach( $adx as $adk => $adv ){
							if( isset( $adv['key'] ) && isset( $settings[ 'row_data' ][ $adv['key'] ] ) ){
								$ddx = json_decode( $settings[ 'row_data' ][ $adv['key'] ], true );
								if( isset( $ddx[ $adk ] ) && $ddx[ $adk ] ){
									if( $adk == 'walkin_patient' && isset( $ddx["walkin_patient_serial_num"] ) && $ddx["walkin_patient_serial_num"] ){
										$ddx[ $adk ] = $ddx[ $adk ].' ['. $ddx["walkin_patient_serial_num"] .']';
									}
									if( $adk == 'walkin_patient' ){
										$ddx[ $adk ] = '<b>'.$ddx[ $adk ].'</b>';
									}
									$return["value"] .= '<br />--'.$ddx[ $adk ];
								}
							}
						}
					}
					
				}
				
				return $return;
			break;
			case 'student-name':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "site_users" ) );
					
					if( isset( $sales_details['first_name'] ) && $sales_details['first_name'] ){
						$return["value"] = $sales_details['first_name'] . ' ' . $sales_details['last_name'] . ' (' . $sales_details['admission_number'] . ')';
					}
				}
				
				return $return;
			break;
			case 'users':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "users" ) );
					
					if( isset( $sales_details['firstname'] ) && $sales_details['firstname'] ){
						$return["value"] = $sales_details['firstname'] . ' ' . $sales_details['lastname'] . ' - '.strtoupper( $sales_details['ref_no'] );
					}
				}
				
				return $return;
			break;
			case 'membership-registration':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "membership_registration" ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>#". mask_serial_number( $sales_details['serial_num'] )."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'sales-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_sales_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>#".mask_serial_number( $sales_details['serial_num'], 'S' )."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'payment-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$return["value"] = "<strong>#".mask_serial_number( $settings[ 'row_data' ][ $var1 ], 'PMT' )."</strong>";
					
					/* if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
						
						if( isset( $sales_details['date'] ) && $sales_details['date'] ){
							$return["value"] = "<strong>#".mask_serial_number( $sales_details['serial_num'], 'S' )."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
						}
					} */
				}
				
				return $return;
			break;
			case 'sales-order-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "orders" ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>#".mask_serial_number( $sales_details['serial_num'], 'S' )."</strong><br />".date("d-M-Y H:i", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'hotel-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "hotel_checkin" ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>#".mask_serial_number( $sales_details['serial_num'], 'H' )."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'vendor-bill-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_record_details( array("id" => $settings[ 'row_data' ][ $var1 ], "table" => "vendor_bill" ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>#".mask_serial_number( $sales_details['serial_num'], 'VI' )."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'sales-receipt-num-2':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_sales_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$return["value"] = "<strong>" . mask_serial_number2( $sales_details )."</strong>";
					}
				}
				
				return $return;
			break;
			case 'date-age':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && doubleval( $settings[ 'row_data' ][ $var1 ] ) ){
					$return["value"] = date("d-M-Y", doubleval( $settings[ 'row_data' ][ $var1 ] ) ) . "<br /><strong>".get_age( $settings[ 'row_data' ][ $var1 ],0,1 ) . "</strong>";
				}
				
				return $return;
			break;
			case 'date-age2':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && $settings[ 'row_data' ][ $var1 ] ){
					$return["value"] = $settings[ 'row_data' ][ $var1 ] . "<br /><strong>".get_age( $settings[ 'row_data' ][ "date" ],0,1 ) . "</strong>";
				}
				
				return $return;
			break;
			case 'text-reference':
				$var2 = isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] )?$settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ]:'';
				if( isset( $settings[ 'row_data' ][ $var2 ] ) && $settings[ 'row_data' ][ $var2 ] ){
					$return["value"] = $settings[ 'row_data' ][ $var2 ] . "<br /><b>" .$settings[ 'row_data' ][ $var1 ] . "</b>";
				}
				
				return $return;
			break;
			case 'expenditure-receipt-num':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$sales_details = get_expenditure_details( array("id" => $settings[ 'row_data' ][ $var1 ] ) );
					
					if( isset( $sales_details['date'] ) && $sales_details['date'] ){
						$alpha = 'P';
						switch( $sales_details["status"] ){
						case 'draft-purchase-ordered':
						case 'draft-purchase-order':
							$alpha = 'DP';
						break;
						case "returned":
							$alpha = 'RG';
						break;
						case "unvalidated_stocked":
						case "stocked":
						case "stock":
							$alpha = 'GR';
						break;
						}
						$return["value"] = "<strong>#".mask_serial_number( $sales_details['serial_num'], $alpha )."</strong><br />".date("d-M-Y", doubleval( $sales_details['date'] ) );
					}
				}
				
				return $return;
			break;
			case 'account-name':
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$account_type = '';
					$account_source = '';
					$multiple = isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'multiple' ] )?$settings[ 'form_field_data' ][ 'calculations' ][ 'multiple' ]:0;
					
					if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) && isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 2 ] ) ){
						$var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
						$var3 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 2 ];
						$account_type = isset( $settings[ 'row_data' ][ $var2 ] )?$settings[ 'row_data' ][ $var2 ]:'';
						$account_source = isset( $settings[ 'row_data' ][ $var3 ] )?$settings[ 'row_data' ][ $var3 ]:'';
					}
					
					$account = $settings[ 'row_data' ][ $var1 ];
					$accounts = array( $account );
					
					if( $multiple ){
						$accounts = explode(",", $account );
					}
					
					$return["value"] = '';
					$dx = array();
					
					foreach( $accounts as $account ){
						$txv = array(
							"account" => $account,
							"account_type" => $account_type,
							"account_source" => $account_source,
						);
						$d = get_debit_and_credit_info( $txv );
						
						$dx[] = $d["title"];
					}
					
					$return["value"] = implode( ', ', $dx );
				}
				return $return;
			break;
			case 'debit-transactions':
			case 'debit-draft-transactions':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					
					switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ){
					case 'debit-draft-transactions':
						$tx = get_debit_and_credit_details( array( "id" => $settings[ 'row_data' ][ $var1 ], 'draft' => 1 ) );
					break;
					default:
						$tx = get_debit_and_credit_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );
					break;
					}
					
					if( is_array( $tx ) && ! empty( $tx ) ){
						$return["value"] = "";
						$ix = 0;
						
						foreach( $tx as $txv ){
							if( $txv['type'] != "debit" )continue;
							++$ix;
							if( $ix > 3 ){
								$return["value"] .= "[...]";
								break;
							}
							$d = get_debit_and_credit_info( $txv );
							$title = $d["title"];
							$price = $d["amount"];

							$title_prefix = '';
							if( isset( $d['title_prefix'] ) && $d['title_prefix'] ){
								$title_prefix = $d['title_prefix'] . ": ";
							}
							
							$return["value"] .= "<strong>" . $title_prefix . $title . "</strong>: <span style='text-align:right; float:right;'>". format_and_convert_numbers( $price , 4 )."</span><br /><br />";
						}
					}
				}
				
				return $return;
			break;
			case 'credit-transactions':
			case 'credit-draft-transactions':
				$var2 = '';
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ){
					case 'credit-draft-transactions':
						$tx = get_debit_and_credit_details( array( "id" => $settings[ 'row_data' ][ $var1 ], 'draft' => 1 ) );
					break;
					default:
						$tx = get_debit_and_credit_details( array( "id" => $settings[ 'row_data' ][ $var1 ] ) );
					break;
					}
					
					
					if( is_array( $tx ) && ! empty( $tx ) ){
						$return["value"] = "";
						//print_r($tx); exit;
						$ix = 0;
						foreach( $tx as $txv ){
							if( $txv['type'] != "credit" )continue;
							++$ix;
							if( $ix > 3 ){
								$return["value"] .= "[...]";
								break;
							}
							
							$d = get_debit_and_credit_info( $txv );
							$title = $d["title"];
							$price = $d["amount"];

							$title_prefix = '';
							if( isset( $d['title_prefix'] ) && $d['title_prefix'] ){
								$title_prefix = $d['title_prefix'] . ": ";
							}
							
							$return["value"] .= "<strong>" . $title_prefix . $title . "</strong>: <span style='text-align:right; float:right;'>". format_and_convert_numbers( $price , 4 )."</span><br /><br />";
						}
					}
				}
				
				return $return;
			break;
			case 'state-id':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
					
                if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
					$sid = $settings[ 'row_data' ][ $var2 ];
                    
                    $return["value"] = get_state_name( array( 'country_id' => $id, 'state_id' => $sid ) );
				}
				return $return;
			break;
			case 'cities-id':
				$var2 = '';
				$return = array(
					'value' => '',
					'class' => '',
				);
					
                if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ] ) )
                    $var2 = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 1 ];
                    
				if( isset( $settings[ 'row_data' ][ $var1 ] ) && isset( $settings[ 'row_data' ][ $var2 ] ) ){
					$sid = $settings[ 'row_data' ][ $var1 ];
					$cid = $settings[ 'row_data' ][ $var2 ];
                    
                    $return["value"] = get_city_name( array( 'city_id' => $cid, 'state_id' => $sid ) );
				}
				return $return;
			break;
			case 'difference':
				if( isset( $settings[ 'row_data' ][ 'row_class' ] ) && $settings[ 'row_data' ][ 'row_class' ] == 'total-heading' ){
					return $return;
				}
				
				
				if( isset( $var1[ 'type' ] ) && isset( $var1[ 'variables' ] ) ){
						switch( $var1[ 'type' ] ){
						case "has_value":
							foreach( $var1[ 'variables' ] as $v ){
								if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
									$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
									if( $vv ){
										$return['value'] = $vv;
										break;
									}
								}
							}
						break;
						default:
							if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
								$return['value'] = format_and_convert_numbers( $settings[ 'row_data' ][ $var1 ] , 3 );
							}else{
								$return['value'] = 0;
							}
						break;
						}
				}else{
					if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
						$return['value'] = format_and_convert_numbers( $settings[ 'row_data' ][ $var1 ] , 3 );
					}else{
						$return['value'] = 0;
					}
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ] ) ){
					
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 1 ] as $var2 ){
						
						if( isset( $settings[ 'row_data' ][ $var2 ] ) && $settings[ 'row_data' ][ $var2 ] ){
							$return['value'] -= format_and_convert_numbers( $settings[ 'row_data' ][ $var2 ] , 3 );
						}
						
					}
					
				}	
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'negative_class' ] ) && $return['value'] < 0 )
					$return['class'] = $settings[ 'form_field_data' ][ 'calculations' ][ 'negative_class' ];
			break;
			case 'has_value':
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] = $vv;
								break;
							}
						}
					}
				}
				//$return['value'] = 780;
			break;
            case 'site-user-id':
				if( isset( $settings[ 'row_data' ][ $var1 ] ) ){
					$id = $settings[ 'row_data' ][ $var1 ];
                    
					$v = get_users( array( 'id' => $id ) );
                    
                    if( isset( $v['email'] ) ){
                        $return = array(
							'value' => ucwords( $v[ "firstname" ] . " ".$v["lastname"] ),
							'class' => '',
						);
                    }
				}
			break;
			case "total-egg-production-ent":
			case 'addition':
				$return['value'] = 0;
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] += $vv;
							}
						}
					}
				}
				
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'subtrend' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'subtrend' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] -= $vv;
							}
						}
					}
				}
			break;
			case 'multiplication':
				$return['value'] = 1;
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] ) ){
					foreach( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ] as $v ){
						if( isset( $settings[ 'row_data' ][ $v[0] ] ) && $settings[ 'row_data' ][ $v[0] ] ){
							$vv = format_and_convert_numbers( $settings[ 'row_data' ][ $v[0] ] , 3 );
							if( $vv ){
								$return['value'] *= $vv;
							}
						}
					}
				}
			break;
			default:
				if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 0 ] ) )
					$return["value"] = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ][ 0 ];
				else
					$return["value"] = $settings[ 'form_field_data' ][ 'calculations' ][ 'variables' ][ 0 ];
			break;
			}
			
			switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'type' ] ){
			case "total-egg-production-ent":
				if( $return["value"] )$return["value"] = $return["value"]." { <strong>". format_and_convert_numbers($return["value"]/30, 5 ) ." crates </strong>}";
				return $return;
			break;
			}
		}
		
		if( isset( $settings[ 'form_field_data' ][ 'calculations' ][ 'form_field' ] ) ){
			switch( $settings[ 'form_field_data' ][ 'calculations' ][ 'form_field' ] ){
			case 'decimal':
				$return['value'] = format_and_convert_numbers( $return['value'] , 4 );
			break;
			case 'number':
				$return['value'] = format_and_convert_numbers( $return['value'] , 1 );
			break;
			case 'currency':
                $a = $return["value"];
                
                if( isset( $settings[ 'form_field_data' ]['default_currency_field'] ) && isset( $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] ) && $settings['row_data'][  $settings[ 'form_field_data' ]['default_currency_field'] ] && $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] != 'undefined' ){
                    $direction = 'from ' . trim( $settings['row_data'][ $settings[ 'form_field_data' ]['default_currency_field'] ] );
                    $a = convert_currency( $return["value"] , $direction , 1 );
                }
				$return["value"] = convert_currency( $a );
			break;
			}
		}
		
		return $return;
	}
	
	//Returns formatted value that would be displayed for each record of the monthly cash calls table
	function prepare_line_items_for_row_data( $settings = array() ){
		$dataset = array();
		
		$cache = array();
		$space_cache = array();
		$tmp_dataset = array();
		$tmp_count = 0;
		
		$modified_dataset = array();
		
		$code_properties = array();
		
		$table = $settings[ 'table' ];
		$func = $settings[ 'table' ];
		
		$clear_row_values = 0;
		
		if( isset( $settings[ 'dataset' ] ) && is_array( $settings[ 'dataset' ] ) && isset($settings[ 'dataset' ][0]) ){
			$dataset = $settings[ 'dataset' ];
			foreach( $dataset[0] as $k => $v ){
				$space_cache[ $k ] = '';
			}
			$space_cache['row_class'] = 'space';
			$d = $space_cache;
			$d['id'] = '9';
			
			$dataset[] = $d;
			
			$insert_total_row = false;
			
			$previous_group_parent = '';
			
			$total_row_index = array();
			
			foreach( $dataset as $key => & $data ){
				
				if( $key == 0 )$clear_row_values = 1;
				
				$a_heading = 0;
				$terminate_current = 0;
				
				if( ! isset( $cache[ $data['code'] ] ) ){
					$cache[ $data['code'] ] = get_codes_id_and_parent( $data );
					//$cache[ $dataset[ $key + 1 ]['code'] ]['data'] = $data;
				}
				if( isset( $dataset[ $key + 1 ] ) ){
					$cache[ $dataset[ $key + 1 ]['code'] ] = get_codes_id_and_parent( $dataset[ $key + 1 ] );
					//$cache[ $dataset[ $key + 1 ]['code'] ]['data'] = $dataset[ $key + 1 ];
				}
				
				//get all parents
				
				$parents = array();
				$p = explode('.', $cache[ $data['code'] ][ 'parent' ] );
				if( empty( $p ) )$p = array( $cache[ $data['code'] ][ 'parent' ] );
				foreach( $p as $pp ){
					if( empty( $parents ) )$parents[] = implode('.', $parents ) . $pp;
					else $parents[] = implode('.', $parents ) .'.'. $pp;
				}
				
				if( ! empty( $parents ) ){
					foreach( $data as $k => $v ){
						$cc = $v;
						$c1 = 0;
						switch( $k ){
						case 'description':
						case 'id':
						case 'code':
						case 'remark':
						case 'modification_date':
						case 'creation_date':
						case 'record_status':
						break;
						default:
							$cc = doubleval( $v );
							$c1 = 1;
						break;
						}
						foreach( $parents as $pp ){
							if( isset( $cache[ $pp ][ 'data' ][ $k ] ) && $c1 )
								$cache[ $pp ][ 'data' ][ $k ] += $cc;
							else
								$cache[ $pp ][ 'data' ][ $k ] = $cc;
						}
					}
				}
				
				
				//next row
				if( isset( $dataset[ $key + 1 ] ) && isset( $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] ) ){
					if( $cache[ $data['code'] ][ 'parent' ] == $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] ){
						
					}else{
						//different parents - terminate previous row
						if( isset( $dataset[ $key - 1 ] ) && $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] == $data['code'] ){
							if( $cache[ $dataset[ $key - 1 ]['code'] ][ 'parent' ] == $cache[ $data['code'] ]['parent'] ){
								
								$clear_row_values = 1;
								
								$tmp_dataset = $dataset[ $key - 1 ];
								/*
								$p_code = $cache[ $dataset[ $key - 1 ]['code'] ][ 'parent' ];
								if( isset( $cache[ $p_code ][ 'data' ] ) )
									$tmp_dataset = $cache[ $p_code ][ 'data' ];
								*/
								//$tmp_dataset = $cache[ $dataset[ $key - 1 ]['code'] ][ 'data' ];
								
								$tmp_dataset['description'] = '<strong><small>TOTAL '.$tmp_dataset['code'].' </small></strong>';
								$tmp_dataset['code'] = '&nbsp;';
								$tmp_dataset['row_class'] = 'total';
								$modified_dataset[] = $tmp_dataset;
								
								$modified_dataset[] = $space_cache;
								
							}
						}else{
							if( isset( $dataset[ $key - 1 ] ) && $cache[ $dataset[ $key - 1 ]['code'] ][ 'parent' ] == $cache[ $data['code'] ]['parent'] ){
								$terminate_current = 1;
								
								$modified_dataset[] = $data;
								
								$tmp_dataset = $data;
								$p_code = $cache[ $data['code'] ][ 'parent' ];
								if( isset( $cache[ $p_code ]['data'] ) ){
									$tmp_dataset = $cache[ $p_code ]['data'];
								}
								
								$desc = '';
								if( isset( $cache[ $p_code ][ 'description' ] ) ){
									$desc = $cache[ $p_code ][ 'description' ];
								}
								
								$tmp_dataset['description'] = '<strong><small>TOTAL '.$p_code.' </small></strong>';
								$tmp_dataset['code'] = '&nbsp;';
								$tmp_dataset['row_class'] = 'total';
								
								$modified_dataset[] = $tmp_dataset;
								
								$modified_dataset[] = $space_cache;
								
								//recursive function
								$parent_code = $cache[ $data['code'] ][ 'parent' ];
								if( isset( $cache[ $parent_code ][ 'parent' ] ) && $cache[ $parent_code ][ 'parent' ] != $cache[ $dataset[ $key + 1 ]['code'] ][ 'parent' ] ){
									
									$tmp_dataset = $data;
									$desc = '';
									
									$p_code = $cache[ $parent_code ][ 'parent' ];
									if( isset( $cache[ $p_code ]['data'] ) ){
										$tmp_dataset = $cache[ $p_code ]['data'];
									}
									
									if( isset( $cache[ $cache[ $parent_code ][ 'parent' ] ][ 'description' ] ) )
										$desc = $cache[ $cache[ $parent_code ][ 'parent' ] ][ 'description' ];
									
									$tmp_dataset['code'] = '&nbsp;';
									$tmp_dataset['description'] = '<strong><small>TOTAL '. $cache[ $parent_code ][ 'parent' ] .' </small></strong>';
									$tmp_dataset['row_class'] = 'total';
									$modified_dataset[] = $tmp_dataset;
									
									$modified_dataset[] = $space_cache;
									
								}
							}
						}
					}
				}else{
					//last row
				}
				
				if( ! $terminate_current ){
					if( $clear_row_values ){
						$clear_row_values = 0;
						foreach( $data as $k => & $v ){
							switch( $k ){
							case 'description':
							case 'id':
							case 'code':
							case 'remark':
							case 'modification_date':
							case 'creation_date':
							case 'record_status':
							break;
							default:
								$v = '';
							break;
							}
						}
						$data['row_class'] = 'total-heading';
					}
					
					$modified_dataset[] = $data;
				}else{
					$clear_row_values = 1;
				}
				
			}
		
		}
		//print_r($dataset);
		//print_r($cache);
		//exit;
		return $modified_dataset;
	}
	
	function get_codes_id_and_parent( $data ){
		$cache = array();
		$cache[ 'codes' ] = explode( '.', $data['code'] );
		$cache[ 'description' ] = $data['description'];
		
		$i = count( $cache[ 'codes' ] );
		if( isset( $cache[ 'codes' ][ $i - 1 ] ) ){
			$c = $cache[ 'codes' ];
			unset( $c[ $i - 1 ] );
			$cache[ 'parent' ] = implode( '.', $c );
		}else{
			$cache[ 'parent' ] = $data['code'];
		}
		return $cache;
	}
	
?>
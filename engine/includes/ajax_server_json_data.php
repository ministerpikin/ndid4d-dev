<?php
	/* Total data set length */
	$iTotal = count($arr);
	
	if(!isset($_GET['sEcho']))$_GET['sEcho']=1;
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	//Get Application Users Names [F. Last Name]
	
	//write_file('','ds.txt',$id2val->txt);
	if(isset($_GET['iDisplayStart']))$sn = $_GET['iDisplayStart'];
	else $sn = 0;
	
	//CHECK WHETHER OR NOT TO DISPLAY TOTAL FOR SUMS
	if(isset($json_settings['show_total']) && $json_settings['show_total']){
		//Display Further Details
		if(isset($json_settings['show_total']) && $json_settings['show_total'] && isset($json_settings['show_total_function']) && $json_settings['show_total_function']){
			//Set Fixed First Row for Table
			$output['aaData'][0] = array();
		}
	}
	
	$record_id = '';
	
	$special_summed_values = array();
	
	$summed_values = array();
	$sub_summed_values = array();
	
	$summed_values_units = array();
	$summed_values_form_type = array();
	
	$special_table_formatting_top_row = array();
	$special_table_formatting_bottom_summed_values = array();
	
	$field_trans = array();
	if( isset( $g_more_data["show_selection"]["index_fields"]["share_id"] ) ){
		$field_trans["date-5"] = 'date-5time';
	}

	$dir = "includes/datatable-settings/".$real_table;
	$settings_dir = $dir . "/settings.json";
	$db_settings = array();
	if( file_exists( $fakepointer.$pagepointer . $settings_dir ) ){
		$db_settings = json_decode( file_get_contents( $fakepointer.$pagepointer . $settings_dir ), true );
	}
	
	for($count_records=0; $count_records<count($arr); $count_records++ )
	{
		$aRow = $arr[$count_records];
		
		$display_show_details = 0;
		
		$record_id = '<span style="font-size:0px; display:none;>'.$aRow['id'].'</span>';
		
		$skip_loop = 1;
		
		$row = array();
		
		if($skip_loop){
			//CHECK WHETHER OR NOT TO DISPLAY DETAILS
			if($json_settings['show_details']){
				$row[0] = '<span class="remove-before-export"></span>';
				$returning_html_data = '';
				$button_style = '';

				if( ! empty( $db_settings ) ){
					foreach( $db_settings as $db_s ){
						if( isset( $db_s[ 'type' ] ) && $db_s[ 'type' ] ){
							$paint = '';

							switch( $db_s[ 'type' ] ){
							case 'specific_records':
								if( isset( $db_s[ 'field' ] ) && isset( $db_s[ 'path' ] ) && isset( $aRow[ $db_s[ 'field' ] ] ) && $aRow[ $db_s[ 'field' ] ] && isset( $db_s[ 'style' ] ) ){
									$data_dir = $fakepointer.$pagepointer . "tmp/filescache/".$database_name."/datatable-settings/".$real_table."/". $aRow[ $db_s[ 'field' ] ] .".json";
									if( file_exists( $data_dir ) ){
										$db_itmd = json_decode( file_get_contents( $data_dir ), true );

										if( isset( $db_itmd[ 'color' ] ) && $db_itmd[ 'color' ] ){
											$paint = $db_itmd[ 'color' ];
										}
									}
								}
							break;
							case 'all_records':
								if( isset( $db_s[ 'field' ] ) && isset( $aRow[ $db_s[ 'field' ] ] )  && isset( $db_s[ 'options' ][ $aRow[ $db_s[ 'field' ] ] ] ) && isset( $db_s[ 'options' ][ $aRow[ $db_s[ 'field' ] ] ][ 'color' ] ) ){
									$paint = $db_s[ 'options' ][ $aRow[ $db_s[ 'field' ] ] ][ 'color' ];
								}
							break;
							}

							if( $paint ){
								switch( $db_s[ 'style' ] ){
								case 'button':
									$button_style .= ' background:'. $paint .'; ';
								break;
								}
							}
						}
					}
				}

				if( isset( $selection_button_title ) && $selection_button_title ){
					$pattr = '';
					$pcls = '';
					if( $selection_button_action ){
						$epx = '';
						if( ! empty( $selection_button_action_field_values ) ){
							foreach( $selection_button_action_field_values as $sv ){
								if( isset( $aRow[ $sv ] ) ){
									$epx .= '&' . $sv . '=' . $aRow[ $sv ];
								}
							}
						}
						$pattr = ' action="'. $selection_button_action . $epx .'" ';
						$pcls = 'custom-single-selected-record-button';
					}
					
					$epx2_id = $aRow[ "id" ];
					$epx2_link = '';
					$epx2 = '';
					$pr1 = '';
					$pr2 = '';
					
					if( isset( $g_more_data["show_selection"]["button_action2"] ) && $g_more_data["show_selection"]["button_action2"] ){
						
						if( isset( $g_more_data["show_selection"]["field_id"] ) && isset( $aRow[ $g_more_data["show_selection"]["field_id"] ] ) ){
							$epx2_id = $aRow[ $g_more_data["show_selection"]["field_id"] ];
						}
						
						$epx2 = $g_more_data["show_selection"]["button_action2"];
						if( isset( $g_more_data["show_selection"]["switch_action2"] ) && ! empty( $g_more_data["show_selection"]["switch_action2"] ) ){
							foreach( $g_more_data["show_selection"]["switch_action2"] as $sv ){
								if( isset( $sv["field"] ) && isset( $sv["values"] ) && is_array( $sv["values"] ) && isset( $aRow[ $sv["field"] ] ) && in_array( $aRow[ $sv["field"] ], $sv["values"] ) ){
									
									if( isset( $sv["href"] ) && $sv["href"] ){
										$epx2 = $sv["href"];
										$epx2_link = '1';
									}else if( isset( $sv["action"] ) && $sv["action"] ){
										$epx2 = $sv["action"];
									}
									
									if( isset( $sv["field_id"] ) && isset( $aRow[ $sv["field_id"] ] ) ){
										$epx2_id = $aRow[ $sv["field_id"] ];
									}
									
									if( isset( $sv["field_type"] ) ){
										switch( $sv["field_type"] ){
										case 'file':
											$epx2 .= '&hash=' . get_file_hash( array( "hash" => 1, "file_id" => $epx2_id, "date_filter" => 'd-M-Y' ) );
										break;
										}
									}
									
								}
							}
						}
						
						
						if( isset( $g_more_data["show_selection"]["button_action_field_values2"] ) && ! empty( $g_more_data["show_selection"]["button_action_field_values2"] ) ){
							foreach( $g_more_data["show_selection"]["button_action_field_values2"] as $sv => $sv1 ){
								if( isset( $aRow[ $sv ] ) ){
									$epx2 .= '&nwf_' . $sv1 . '=' . rawurlencode( $aRow[ $sv ] );
								}
							}
						}
						
					}
					
					$b_attr = '';
					if( isset( $g_more_data["show_selection"]["button_attr_direct"] ) && $g_more_data["show_selection"]["button_attr_direct"] ){
						$b_attr = $g_more_data["show_selection"]["button_attr_direct"];
					}
					
					switch( $selection_button_title ){
					case 'none':
					break;
					case 'ext':
					case 'icon':
						$bf = '';
						if( isset( $g_more_data["show_selection"]["button_field1"] ) && isset( $aRow[ $g_more_data["show_selection"]["button_field1"] ] ) && $aRow[ $g_more_data["show_selection"]["button_field1"] ] ){
							$bf = $aRow[ $g_more_data["show_selection"]["button_field1"] ];
						
						}else if( isset( $g_more_data["show_selection"]["button_field"] ) && isset( $aRow[ $g_more_data["show_selection"]["button_field"] ] ) ){
							$bf = $aRow[ $g_more_data["show_selection"]["button_field"] ];
						}else if( isset( $g_more_data["show_selection"]["button_field_direct"] ) && $g_more_data["show_selection"]["button_field_direct"] ){
							$bf = $g_more_data["show_selection"]["button_field_direct"];
						}
						
						switch( $selection_button_title ){
						case 'ext':
							$bfx = explode(".", $bf );
							if( isset( $bfx[ count( $bfx ) - 1 ] ) ){
								$bf = $bfx[ count( $bfx ) - 1 ];
							}
						break;
						}
						
						$xstyle = '';
						$xclass = 'fa fa-file-download fa-2x';
						
						switch( $bf ){
						case 'pdf':
						case '.pdf':
							$xstyle = 'color:red;';
							$xclass = 'fa fa-file-pdf fa-2x';
						break;
						case '.odt':
						case 'odt':
						case '.docx':
						case 'docx':
						case 'doc':
						case '.doc':
							$xstyle = 'color:blue;';
							$xclass = 'fa fa-file-word fa-2x';
						break;
						case '.csv':
						case 'xlsx':
						case '.xls':
							$xstyle = 'color:green;';
							$xclass = 'fa fa-file-excel fa-2x';
						break;
						case 'pptx':
						case '.ppt':
							$xstyle = 'color:orange;';
							$xclass = 'fa fa-file-powerpoint fa-2x';
						break;
						case 'library_child':
						case 'tags':
							$xstyle = 'color:#fbc324;';
							$xclass = 'fa fa-folder-open fa-2x';
						break;
						case 'library':
							$xstyle = 'color:#fbc324;';
							$xclass = 'fa fa-folder fa-2x';
						break;
						case '.bmp':
						case 'tiff':
						case '.gif':
						case 'jpeg':
						case '.jpg':
						case '.png':
							$xstyle = 'color:#555;';
							$xclass = 'fa fa-file-image fa-2x';
						break;
						case '.mp4': case 'mp4':
						case 'webm':
						case '.ogv': case 'ogv':
						case '.wav': case 'wav':
						case '.mp3': case 'mp3':
						case '.mov': case 'mov':
						case 'mkv':	case '.mkv':
							$xstyle = 'color:#f7a000;';
							$xclass = 'fa fa-file-video fa-2x';
						break;
						}
						
						
						if( $epx2 ){
							$epx2 .= '&sb_type=' . $selection_button_title . '&sb_ext=' . $bf;
							
							$pr1 = '<a href="javascript:;" class="custom-single-selected-record-button remove-before-export datatables-child-click" override-selected-record="'.$aRow['id'].'" title="Open" action="'. $epx2 .'" '.$b_attr.'>';
							
							if( $epx2_link ){
								$epx2 .= '&id=' . $epx2_id;
								$pr1 = '<a href="'.$epx2.'" class="remove-before-export datatables-stop-propagation" title="Open" target="_blank">';
							}
							
							$pr2 = '</a>';
						}
						
						$row[0] = $pr1 . '<i style="'.$xstyle.'" class="remove-before-export '.$xclass.' '.$pcls.'" override-selected-record="'.$epx2_id.'" '.$pattr.'></i>' . $pr2;
						
					break;
					case 'notice':
						$btnt = $selection_button_title;
						if( isset( $g_more_data["show_selection"]["button_text"] ) ){
							$btnt = $g_more_data["show_selection"]["button_text"];
						}
						$row[0] = '<button href="#" class="btn-xs btn btn-default remove-before-export '.$pcls.'"  override-selected-record="'.$aRow['id'].'" '.$pattr.' '.$b_attr.'>'. $btnt .'</button>';
					break;
					default:
						$row[0] = '<button href="#" class="btn-xs btn dark remove-before-export '.$pcls.'"  override-selected-record="'.$aRow['id'].'" '.$pattr.' '.$b_attr.'>'. $selection_button_title .'</button>';
					break;
					}
					
					if( isset( $selection_show_details ) && $selection_show_details ){
						$display_show_details = 1;
						if( isset( $row[0] ) )$returning_html_data = $row[0];
					}
				}else{
					
					$returning_html_data = '<button href="#" class="datatables-details btn-xs btn btn-default '.$future_request.' remove-before-export" '. ( $button_style ? 'style="'. $button_style .'"' : '' ) .' title="Click to View Details" jid="'.$aRow['id'].'" ><i style="font-size:10px;" class="fa fa-2x fa-angle-double-down icon-chevron-down mdi mdi-chevron-down"></i></button>';
					
					$display_show_details = 1;
				}
				
				if( $display_show_details ){
					$returning_html_data .= '<div style="display:none;"><div id="main-details-table-'.$aRow['id'].'"><table id="the-main-details-table-'.$aRow['id'].'" class="main-details-table table" style="max-width:920px; width:99%;"><tbody>';
				}
			}
			
			
			$DT_RowClass = '';
			
			//for ( $i=0 ; $i<count($aColumns) ; $i++ )
				// print_r($form_label);
				//print_r($aColumns);
				//print_r($aRow); exit;
			foreach ( $aColumns as $i => $val_i)
			{
				
				if( $aRow[ $aColumns[$i] ] ){
					$aRow[ $aColumns[$i] ] = iconv( "UTF-8", "ASCII//IGNORE", $aRow[ $aColumns[$i] ] );
				}
				
				//Get Field Info
				$field = array(
					'form_field' => '',
					'display_position' => '',
					'field_label' => 'undefined',
				);
				if( isset( $form_label[ $aColumns[$i] ] ) && is_array( $form_label[ $aColumns[$i] ] ) ){
					$field = $form_label[ $aColumns[$i] ];
				}
				$show_field = false;
				
				switch($aColumns[$i]){
				case 'created_by':
				case 'modified_by':
					$show_field = true;
					$field[ 'field_label' ] = ucwords( str_replace( '_', ' ', $aColumns[$i] ) );
					$field[ 'form_field' ] = 'select';
					$field[ 'form_field_options' ] = 'get_users_names';
                    
					$field[ 'form_field' ] = 'calculated';
                    $field[ 'calculations' ] = array(
                        'type' => 'site-user-id',
                        'form_field' => 'text',
                        'variables' => array( array( $aColumns[$i] ) ),
                    );
				break;
				case 'creation_date':
				case 'modification_date':
					$show_field = true;
					$field[ 'field_label' ] = ucwords( str_replace( '_', ' ', $aColumns[$i] ) );
					$field[ 'form_field' ] = 'date-5time';
				break;
				}
				
				switch($aColumns[$i]){
				case "id":
					//CHECK WHETHER OR NOT TO DISPLAY SERIAL NUMBER
					if($json_settings['show_serial_number']){
						$esel = '';
						if( isset( $g_more_data["show_selection"]["index_fields"] ) && is_array( $g_more_data["show_selection"]["index_fields"] ) && ! empty( $g_more_data["show_selection"]["index_fields"] ) ){
							$ifd = array();
							foreach( $g_more_data["show_selection"]["index_fields"] as $ifk => $ifv ){
								if( isset( $aRow[ $ifk ] ) ){
									$ifd[ $ifv ] = $aRow[ $ifk ];
								}
							}
							if( ! empty( $ifd ) ){
								$esel = '<textarea class="datatables-record-data" style="display:none;">'. json_encode( $ifd ) .'</textarea>';
							}
						}
						$row[] = '<b id="'.$aRow['id'].'" class="datatables-record-id" style="font-size:0.8em;">'.++$sn.'</b>' . $esel;
					}
					if( isset( $aRow['serial_num'] ) && $aRow['serial_num'] ){
                    $returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
                        
                        $returning_html_data .= '<td class="details-section-container-label" width="30%">Ref. No.';
                        $returning_html_data .= '</td>';
                        $returning_html_data .= '<td class="details-section-container-value">';
                            $returning_html_data .= $aRow['serial_num'];
                        $returning_html_data .= '</td>';
                    $returning_html_data .= '</tr>';
					}
                    $returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
                        $returning_html_data .= '<td class="details-section-container-label" width="30%">ID';
                        $returning_html_data .= '</td>';
                        $returning_html_data .= '<td class="details-section-container-value">';
                            $returning_html_data .= $aRow['id'];
                        $returning_html_data .= '</td>';
                    $returning_html_data .= '</tr>';
				break;
				case "record_status":
				case "ip_address":
				break;
				default:
					
					//Check to skip field
					if( ( ( isset( $field['display_position'] ) && ( $field['display_position'] == 'more-details' || $field['display_position'] != 'do-not-display-in-table' || ( $field['display_position'] == 'display-in-admin-table' && isset($admin_user) ) ) ) && ( isset( $field['field_label'] ) && $field['field_label'] != 'undefined' ) ) || $show_field ){
						//START - CHECK WHETHER OR NOT TO DISPLAY DETAILS
						if( $display_show_details ){
							
							
							//Display Field in Details Section (name_dtX_dtY_dtZ | where Z = 5)
							if(isset( $field['field_label'] ) ){
							$returning_html_data .= '<tr class="details-section-container-row details-section-container-row-'.$aColumns[$i].'" jid="'.$aColumns[$i].'">';
								$returning_html_data .= '<td class="details-section-container-label" width="30%">';
										$returning_html_data .= $field['field_label'];
								$returning_html_data .= '</td>';
								
								$returning_html_data .= '<td class="details-section-container-value">';
								
								//Check for Combo Box Interpretation
								switch($field['form_field']){
								case "picture":
								case "file":
								case 'text-file':
									//Uploaded Document
									if($aRow[ $aColumns[$i] ]){
										if( isset( $skip_page_pointers_for_files ) )
											$returning_html_data .= get_uploaded_files( $skip_page_pointers_for_files, $aRow[ $aColumns[$i] ], $field['field_label'] );
										else
											$returning_html_data .= get_uploaded_files( $pagepointer, $aRow[ $aColumns[$i] ], $field['field_label'] );
									}else{
										$returning_html_data .= 'not available';
									}
								break;
                                case 'calculated':
									$run = 0;
									if($aRow[ $aColumns[$i] ]){
										$run = 1;
									}
									if( isset( $field[ 'calculations' ][ 'always_calc' ] ) && $field[ 'calculations' ][ 'always_calc' ] ){
										$run = 1;
									}

									if( $run ){
										$_data = evaluate_calculated_value( 
											array(
												'add_class' => $aColumns[$i],
												'row_data' => $aRow,
												'form_field_data' => $field,
											) 
										);
										
										if( isset( $_data['value'] ) )
											$returning_html_data .= $_data['value'];
									}
                                break;
								default:
									//Get options function name
									$returning_html_data .= __get_value( $aRow[ $aColumns[$i] ], "a", array( "globals" => array( "labels" => array( "a" => $field ), "fields" => array( "a" => "a" ) ) ) );
								break;
								}
							$returning_html_data .= '</td>';
							
							$returning_html_data .= '</tr>';
							}
							
						}//END - CHECK WHETHER OR NOT TO DISPLAY DETAILS
					
					}
					
					if( $field['display_position'] == 'do-not-display-in-table'  ){
						//Do not display field at all
					}
					
					if( $field['display_position'] != 'more-details' && $field['display_position'] != 'do-not-display-in-table' && ( isset( $field['field_label'] ) && $field['field_label'] != 'undefined' ) ){
						$cell_data = '';
						$real_cell_data = '';
						
						//Check for Combo Box Interpretation
						switch($field['form_field']){
						case "picture":
						case 'file':
						case 'text-file':
							//Uploaded Document
							$real_cell_data = $aRow[ $aColumns[$i] ];
							
							$files = '';
							if($aRow[ $aColumns[$i] ]){
								if( isset( $skip_page_pointers_for_files ) )
									$files = get_uploaded_files( $skip_page_pointers_for_files, $aRow[ $aColumns[$i] ], $field['field_label'] );
								else
									$files = get_uploaded_files( $pagepointer, $aRow[ $aColumns[$i] ], $field['field_label'] );
							}else{
								$files .= 'not available';
							}
							$cell_data = $files;
						break;
						case 'calculated':
							$run = 0;
							if($aRow[ $aColumns[$i] ]){
								$run = 1;
							}
							if( isset( $field[ 'calculations' ][ 'always_calc' ] ) && $field[ 'calculations' ][ 'always_calc' ] ){
								$run = 1;
							}

							if( $run ){
								$_data = evaluate_calculated_value( 
									array(
										'add_class' => $aColumns[$i],
										'row_data' => $aRow,
										'form_field_data' => $field,
									) 
								);
								$cell_data = "";
								if( isset( $_data['value'] ) )
									$cell_data = $_data['value'];
								
								if( isset( $_data['class'] ) )
									$DT_RowClass .= ' '.$_data['class'];
							}
						break;
						default:
							if( isset( $field_trans[ $field['form_field'] ] ) ){
								$field['form_field'] = $field_trans[ $field['form_field'] ];
							}
							//Get options function name
							$real_cell_data = $aRow[ $aColumns[$i] ];
							$cell_data = __get_value( $real_cell_data, "a", array( "globals" => array( "labels" => array( "a" => $field ), "fields" => array( "a" => "a" ) ), "summary" => 200 ) );
						break;
						}
						
						if( isset( $field['format_function_dt'] ) && $field['format_function_dt'] && function_exists( $field['format_function_dt'] ) ){
							$ftt = $field['format_function_dt'];
							$cell_data = $ftt( $cell_data, array( "record" => $aRow, "label" => $field ) );
						}
						
						$row[] = $cell_data;
					}
				break;
				}
			}
			
			if( $i ){
				// print_r($row);
				// print_r($field); exit;
			}
			
			//CHECK WHETHER OR NOT TO DISPLAY DETAILS
			if( $display_show_details ){
				//Display Further Details
				if(isset($json_settings['show_details_more']) && $json_settings['show_details_more'] ){
					if( isset($json_settings['special_details_functions']) && is_array($json_settings['special_details_functions']) ){
						foreach($json_settings['special_details_functions'] as $function_name_to_call){
							$more_details = $function_name_to_call."_more_details";
							if(function_exists($more_details)){
								$returning_html_data .= '<tr>';
									$returning_html_data .= '<td colspan="2">';
										$returning_html_data .= $more_details( $aRow , $database_name , $database_connection , $function_name_to_call , $pagepointer );
									$returning_html_data .= '</td>';
								$returning_html_data .= '</tr>';
							}
						}
					}
				}
				
				$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
				$returning_html_data .= '</div>';
				$returning_html_data .= '</div>';
				
				$row[0] = $returning_html_data;
			}
			
			$row["DT_RowClass"] = $DT_RowClass;
			/* 
			if( $table == 'product' && ! ( $aRow['product018'] + ( $aRow['product019']*3600 ) > __date() && $aRow['product018'] + 1 < __date() ) ){
                //&& $aRow['product'] == '10' || ( isset( $aRow[ 'ip_address' ] ) && $aRow[ 'ip_address' ] == 'total-row' )
				$row["DT_RowClass"] = 'expired-product';
			}
			*/
			 
			if( isset( $table_real ) && $table_real == 'cash_calls_reporting_view' && isset( $aRow['row_class'] ) ){
				switch( $aRow['row_class'] ){
				case "space":
					$row["DT_RowClass"] = 'line-items-space-row';
				break;
				case "total":	
				case "total-heading":
					$row["DT_RowClass"] = 'line-items-total-row';
				break;
				}
			}
			$output['aaData'][] = $row;
			
		}
		
	}
?>
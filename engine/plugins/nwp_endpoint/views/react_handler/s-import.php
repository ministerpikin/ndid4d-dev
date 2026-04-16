<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	// echo '<pre>';print_r( $data ); echo '</pre>'; 
	$h = 'How to Import Bulk Data';
	$h1 = '';
	$h2 = '';
	$x = 0;
	$show_line_action = defined('SHOW_FILE_UPLOAD_LINE_ACTION') && SHOW_FILE_UPLOAD_LINE_ACTION ? 1 :0;
	if( $x ){
		$h1 .= '<div class="row">
					<div class="col-md-6">';
	}
				$table = isset( $data["table"] )?$data["table"]:'';
				
				if( isset( $data["title"] ) && $data["title"] ){
					$h .= ' into ' . $data["title"];
				}
				$nt = isset($data['file_url']) && $data['file_url'] ? '<p>1. Download the Template' : "<p>1. Create a CSV File using the fields at the end of the table</p>";
				$bg = 1;
				$cn_path = "";
				if( isset($data['file_url']) && $data['file_url'] ){
					$h1 .= $nt;
					$pr = get_project_data();
					$sn1 = 0;
					$did = rand(4340, 56312);
					$hash = get_file_hash( array( "hash" => 1, "file_id" => $did, "date_filter" => 'd-M-Y' ) );
					$cn_path = $pr["domain_name"].'print.php?hash='.$hash.'&id='.$did.'&durl='. get_file_hash( array( "encrypt" => $data['file_url'], "key" => $hash ) ) .'&name='. rawurlencode( isset($data['title']) && $data['title'] ? $data['title'] . ' CSV Template' : 'Import Template' );
					$cn_path = '<a href="' . $cn_path . '" title="Click to open Import Template" class="btn dark btn-sm" target="_blank">Download</a>';
					
					if( get_hyella_development_mode() ){
						$cn_path .= '<a style="margin-left: 15px;" href="#" title="Click to open Import Template" action="?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=refresh_file_upload_template&dt='. ( isset($data['dt']) && $data['dt'] ? $data['dt'] : '') .'&html_replacement_selector=import-tutorial-view" class="btn dark btn-sm custom-single-selected-record-button" override-selected-record="-">Refresh Template'. ( isset($data['refreshed']) && $data['refreshed'] ? '&nbsp;&nbsp;<i class="mdi mdi-check"></i>' : '' ) .'</a>';
					}


					$menu = array(
						// array(
						// 	'plugin' => 'nwp_endpoint',
						// 	'action' => 'react_handler',
						// 	'todo' => 'add_new_fields',
						// 	'html_replacement_selector' => 'import-tutorial-view',
						// 	'label' => 'Add Field',
						// 	'title' => 'Click to open Add new field',
						// 	'id' => ( isset($data['dt']) && $data['dt'] ? $data['dt'] : '-'),
						// 	'class' => ' btn dark btn-sm '
						// ),
						array(
							'plugin' => 'nwp_logging',
							'action' => 'log_main_logger',
							'todo' => 'display_all_records_frontend&function=file_load&table='. $table,
							// 'html_replacement_selector' => 'import-tutorial-view',
							'label' => 'Activity Log',
							'title' => 'Click to manage log loaded',
							'id' => '-',
							'class' => ' btn dark btn-sm '
						),
						array(
							'plugin' => 'nwp_reports',
							'action' => 'reports_bay',
							'todo' => 'export_as_csv2',
							// 'html_replacement_selector' => 'import-tutorial-view',
							'label' => 'Export As CSV',
							'title' => 'Click to manage log loaded',
							'id' => ( isset($data['dt']) && $data['dt'] ? $data['dt'] : '-'),
							'class' => ' btn dark btn-sm '
						),
					);


					if( !$x ){
						foreach ($menu as $laction) {
							$id = isset($laction['id']) && $laction['id'] ? $laction['id'] : '';
							$cls = 'custom-single-selected-record-button';
							$act = '?action=';
							if( isset( $laction['plugin']) && $laction['plugin'] ){
								$act .= $laction['plugin'] . '&todo=execute&nwp_action=';
							}
							$act .= $laction['action'];
							if( isset($laction['plugin']) && $laction['plugin'] ){
								$act .= '&nwp_todo=';
							}else{
								$act .= '&todo=';
							}

							$act .= $laction['todo'];
							if( isset($laction['html_replacement_selector']) && $laction['html_replacement_selector'] ){
								$act .= '&html_replacement_selector='.$laction['html_replacement_selector'];
							}

							if( isset($laction['class']) && $laction['class'] ){
								$cls .= ' '.$laction['class'];
							}

							$cn_path .= '<a style="margin-left: 15px;" href="#" title="" action="'. $act .'" class="'. $cls .'" override-selected-record="'. $id .'">'. ( isset($laction['label']) && $laction['label'] ? $laction['label'] : 'Unknown Label' ) .'</a>';
						}
					}


					$h1 .= '<br />' .$cn_path .'</p>';
					$bg = 0;
				}
				
				$h1 .= '<p>'. (!$bg ? '2' : '1') .'. Fill the Template with relevant data.<br /><br />';

	if( $x ){
		$h1 .= '</div>';
			$h1 .= '<div class="col-md-6">';
				$h1 .= '<p><b>Other Options:</b></p>';
				$h1 .= '<ol>';
				foreach ($menu as $laction) {
					$id = isset($laction['id']) && $laction['id'] ? $laction['id'] : '';
					$cls = 'custom-single-selected-record-button';
					$act = '?action=';
					if( isset( $laction['plugin']) && $laction['plugin'] ){
						$act .= $laction['plugin'] . '&todo=execute&nwp_action=';
					}
					$act .= $laction['action'];
					if( isset($laction['plugin']) && $laction['plugin'] ){
						$act .= '&nwp_todo=';
					}else{
						$act .= '&todo=';
					}

					$act .= $laction['todo'];
					if( isset($laction['html_replacement_selector']) && $laction['html_replacement_selector'] ){
						$act .= '&html_replacement_selector='.$laction['html_replacement_selector'];
					}

					if( isset($laction['class']) && $laction['class'] ){
						$cls .= ' '.$laction['class'];
					}

					$h1 .= '<li><a style="margin-left: 15px;" href="#" title="" action="'. $act .'" class="'. $cls .'" override-selected-record="'. $id .'">'. ( isset($laction['label']) && $laction['label'] ? $laction['label'] : 'Unknown Label' ) .'</a></li>';
				}
				$h1 .= '</ol>';
			$h1 .= '</div>';
		$h1 .= '</div>';
	}

	$h1 .= '<p><b>NOTE:</b> Fields in red are *required and some fields has special data types. Use the table below to identify the data types</p>';
	$h1 .= '<p><b>NOTE:</b> Fields with a default value option would be populated with those values if not value is detected in the uploaded file</p>';

	$sn = 0;
	
	$tfs = array();
	
	if( isset( $data["import_fields"] ) && is_array( $data["import_fields"] ) && ! empty( $data["import_fields"] ) ){
		foreach( $data["import_fields"] as $k => $v ){
			if( isset( $data["fields"][ $k ] ) && isset( $data["labels"][ $v ] ) ){

				if( isset($data["labels"][ $v ]["data"]["hide_on_import_table"]) && $data["labels"][ $v ]["data"]["hide_on_import_table"] ){
					continue;
				}

				$show_btns = 0;
				if( isset($data["labels"][ $v ]["data"]["added_field"]) && $data["labels"][ $v ]["data"]["added_field"] ){
					$show_btns = 1;	
				}

				$style = '';
				if( isset( $data["labels"][ $v ]["required_field"] ) && $data["labels"][ $v ]["required_field"] == 'yes' ){
					$style = 'color:#d32111;';
				}
								
				$type = '';
				switch( $data["labels"][ $v ]['form_field'] ){
					case "radio":
					case "checkbox":
					case "multi-select":
					case "select":
						$type = 'requires special values';
						$ff = [];
						if( isset( $data["labels"][ $v ]['form_field_options'] ) && $data["labels"][ $v ]['form_field_options'] ){
							$f = $data["labels"][ $v ]['form_field_options'];
							if( function_exists( $f ) ){
								$ff = $f();
							}else{
								if( isset( $data["labels"][ $v ]["data"]["form_field_options_source"] ) &&  $data["labels"][ $v ]["data"]["form_field_options_source"] == "2"  ){
									$ff = get_list_box_options( $data["labels"][ $v ]['form_field_options'], array( "return_type" => 2 ) );
								}
							}

							if( is_array( $ff ) && ! empty( $ff ) ){
								$type = '<details>';
									$type .= '<summary class="btn btn-sm btn-default">View Options</summary>';
									$type .= '<pre>';
									$type .= '<span style="color:blue;">keys : values</span><br>';
									foreach( $ff as $fkk => $fvv ){
										if( $fkk ){
											$type .= $fkk . ' : ' . $fvv . '<br>';
										}
									}
									$type .= '<small class="text-danger"><i>Use keys as values in CSV</i></small>';
									$type .= '</pre>';
								$type .= '</details>';
							}
						}
					break;
					case "calculated":
						$type = 'requires special values';
						$sel_val = '';
						$ktb = '';
						// $show_btns = 0;
						
						if( isset( $data["labels"][ $v ]['calculations']['reference_plugin'] ) && $data["labels"][ $v ]['calculations']['reference_plugin'] && isset( $data["labels"][ $v ]['calculations']['reference_table'] ) && $data["labels"][ $v ]['calculations']['reference_table'] ){
							$ktb = $data["labels"][ $v ]['calculations']['reference_table'];
							$sel_val = 'type=plugin:::key='. $data["labels"][ $v ]['calculations']['reference_plugin'] .':::value='. $ktb .'';

						}else if( isset( $data["labels"][ $v ]['calculations']['reference_table'] ) && $data["labels"][ $v ]['calculations']['reference_table'] ){
							$ktb = $data["labels"][ $v ]['calculations']['reference_table'];
							$sel_val = 'type=base:::key=*base:::value='. $ktb .'';
							
						}
						
						$ifil = '';
						if( isset( $data["labels"][ $v ]['calculations']['ifilter'] ) && ! empty( $data["labels"][ $v ]['calculations']['ifilter'] ) ){
							// $ifil = rawurlencode( json_encode( $data["labels"][ $v ]['calculations']['ifilter'] ) );
						}
						$type = '<a href="javascript:;" action="?action=audit&todo=display_data_view&filter='.$ifil.'" target="_blank" class="custom-single-selected-record-button"  override-selected-record="'. $sel_val .'">requires special values <i class="icon-external-link"></i></a>';

						if ( isset( $data["labels"][ $v ]['display_options']['disable_special_value_link'] ) && $data["labels"][ $v ]['display_options']['disable_special_value_link'] ) {
							$type = str_replace('target="_blank"', '', $type );
							$type = str_replace('custom-single-selected-record-button', '', $type );
						}
					break;
					case "date-5time":
					case "datetime":
						$type = '<pre>'. 'YYYY-MM-DD HH:MM:SS'.'</pre>';
					break;
					case "date":
					case "date-5":
						$type = '<pre>'. 'YYYY-MM-DD'.'</pre>';
					break;
					case 'number':
						$type = '<pre>'. 'Number'.'</pre>';
					break;
					default:
						$type = '<pre>'. 'Text'.'</pre>';
					break;
				}

				$line_actions = '';
				if( $show_btns ){
					$line_actions .= '<div class="mx-2">';
						$line_actions .= '<a style="margin-right: 5px;" override-selected-record="'. $k .'" class="custom-single-selected-record-button btn btn-sm btn-info" action="?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=edit_new_fields&dt='. ( isset($data['dt']) && $data['dt'] ? $data['dt'] : '' ) .'&html_replacement_selector=import-tutorial-view"><i class="mdi mdi-pencil"></i></a>';
						$line_actions .= '<a override-selected-record="'. $k .'" confirm-prompt="delete this field? This will PURGE all data on this data source" class="custom-single-selected-record-button btn btn-sm btn-danger" action="?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=delete_fields&dt='. ( isset($data['dt']) && $data['dt'] ? $data['dt'] : '' ) .'&html_replacement_selector=import-tutorial-view"><i class="icon-trash"></i></a>';
					$line_actions .= '</div>';
				}else{
					$line_actions = "<p class='badge badge-soft-danger text-center'>N/A</p>";
				}

				$tfs[] = $k;
				$h2 .= '<tr style="'. $style .'">';
					$h2 .= '<td>'. ++$sn .'</td>';
					$h2 .= '<td >'. $k .'</td>';
					$h2 .= '<td>'. $data["labels"][ $v ]['field_label'] .'</td>';
					$h2 .= '<td class="text-center">'. $type . '</td>';
					if( $show_line_action ){
						$h2 .= '<td>'. $line_actions .'</td>';
					}
				$h2 .= '</tr>';
			}
		}
		
		if( $h2 ){
			$h1 .= '<div class="report-table-preview-20">';
				$h1 .= '<table class="table table-striped table-bordered table-hover" cellspacing="0" style="width:100%;">';
					$h1 .= '<thead>';
						$h1 .= '<tr>';
							$h1 .= '<th>S/N</th>';
							$h1 .= '<th>Column</th>';
							$h1 .= '<th>Description</th>';
							$h1 .= '<th>Acceptable Values</th>';
							if( $show_line_action ){
								$h1 .= '<th>Action</th>';
							}
						$h1 .= '</tr>';
					$h1 .= '</thead>';
					$h1 .= '<tbody>';
				
						$h1 .= $h2;
				
					$h1 .= '</tbody>';
				$h1 .= '</table>';
			$h1 .= '</div>';
		}
		if( ! empty( $tfs ) ){
			//$h1 .= '<p><b>NOTE:</b> You can create a template by making these fields the first(1st) row of your excel/csv file<br /><pre>'.implode("\t", $tfs ).'</pre></p>';
			$h1 .= '<p '. ( $bg ? 'class="text-info"' : '' ) .' ><b>NOTE:</b> You can create a template by copying and pasting these fields in the first(1st) row of your excel/csv file<br /><pre>'.implode("\t", $tfs ).'</pre></p>';
		}
		//$h .= ' into ' . $data["title"];
	}
	
	$h1 .= '<p>4. Save your file in CSV (Comma delimited) format</p>';
	$h1 .= '<p>5. Upload the file</p>';
	
	echo '<h4>'.$h.'</h4><hr />' . $h1;
?>
</div>

	
</div>

<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	//echo '<pre>';print_r( $data ); echo '</pre>'; 
	$h = 'How to Import Bulk Data';
	$h1 = '';
	$h2 = '';
	
	$table = isset( $data["table"] )?$data["table"]:'';
	
	if( isset( $data["title"] ) && $data["title"] ){
		$h .= ' into ' . $data["title"];
	}
	
	$h1 .= '<p>1. Download the Template</p>';
	$h1 .= '<p>2. Fill the Template with relevant data.<br /><br />NOTE: Some fields are *required and some fields has special data typesUse the table below to identify the data types</p>';
	$sn = 0;
	
	$tfs = array();
	
	if( isset( $data["import_fields"] ) && is_array( $data["import_fields"] ) && ! empty( $data["import_fields"] ) ){
		foreach( $data["import_fields"] as $k => $v ){
			if( isset( $data["fields"][ $k ] ) && isset( $data["labels"][ $data["fields"][ $k ] ] ) ){
				$style = '';
				$required_field = 0;
				if( isset( $v["required_field"] ) && $v["required_field"] == 'yes' ){
					$required_field = 1;
				}
				
				if( $required_field ){
					$style = 'color:#d32111;';
				}
				
				$type = '';
				switch( $data["labels"][ $data["fields"][ $k ] ]['form_field'] ){
				case "radio":
				case "checkbox":
				case "multi-select":
				case "select":
					$type = 'requires special values';
					if( isset( $data["labels"][ $data["fields"][ $k ] ]['form_field_options'] ) && $data["labels"][ $data["fields"][ $k ] ]['form_field_options'] ){
						$f = $data["labels"][ $data["fields"][ $k ] ]['form_field_options'];
						if( function_exists( $f ) ){
							$ff = $f();
							if( is_array( $ff ) && ! empty( $ff ) ){
								$type = '<details><summary class="btn btn-sm btn-default">View Options</summary>';
									$type .= '<pre>'. implode( '<br />', array_values( $ff ) ) .'</pre>';
									$type .= '<pre>'. implode( '<br />', array_keys( $ff ) ) .'</pre>';
								$type .= '</details>';
							}
						}
					}
				break;
				case "calculated":
					$type = 'requires special values';
					
					if( isset( $data["labels"][ $data["fields"][ $k ] ]['calculations']['reference_table'] ) && $data["labels"][ $data["fields"][ $k ] ]['calculations']['reference_table'] ){
						$ktb = $data["labels"][ $data["fields"][ $k ] ]['calculations']['reference_table'];
						
						$ifil = '';
						if( isset( $data["labels"][ $data["fields"][ $k ] ]['calculations']['ifilter'] ) && ! empty( $data["labels"][ $data["fields"][ $k ] ]['calculations']['ifilter'] ) ){
							$ifil = rawurlencode( json_encode( $data["labels"][ $data["fields"][ $k ] ]['calculations']['ifilter'] ) );
						}

						$sval = ' type=base:::key=*base:::value='. $ktb;
						if( isset( $data["labels"][ $data["fields"][ $k ] ]['calculations'][ 'reference_plugin' ] ) && $data["labels"][ $data["fields"][ $k ] ]['calculations'][ 'reference_plugin' ] ){
							$sval = ' type=plugin:::key='. $data["labels"][ $data["fields"][ $k ] ]['calculations'][ 'reference_plugin' ] .':::value='. $ktb;
						}

						$type = '<a href="javascript:;" action="?action=audit&todo=display_data_view&filter='.$ifil.'" target="_blank" class="custom-single-selected-record-button"  override-selected-record="'. $sval .'">requires special values <i class="icon-external-link"></i></a>';
					}
				break;
				case "date-5time":
				case "date-5":
				case "datetime":
				case "date":
					$type = '<pre>'. 'YYYY-MM-DD'.'</pre>';
				break;
				}
				
				$tfs[] = $k;
				$h2 .= '<tr style="'. $style .'">';
					$h2 .= '<td>'. ++$sn .'</td>';
					$h2 .= '<td >'. $k .'</td>';
					$h2 .= '<td>'. $data["labels"][ $data["fields"][ $k ] ]['field_label'] .'</td>';
					$h2 .= '<td>'. $type . '</td>';
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
			$h1 .= '<p><b>NOTE:</b> You can create a template by copying and pasting these fields in the first(1st) row of your excel/csv file<br /><pre>'.implode("\t", $tfs ).'</pre></p>';
		}
		//$h .= ' into ' . $data["title"];
	}
	
	$h1 .= '<p>4. Save your file in CSV (Comma delimited) format</p>';
	$h1 .= '<p>5. Upload the file</p>';
	
	echo '<h4>'.$h.'</h4><hr />' . $h1;
?>
</div>
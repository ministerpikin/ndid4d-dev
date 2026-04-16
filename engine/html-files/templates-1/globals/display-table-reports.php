<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	.title{
		text-align:center;
		font-size:1.1em;
	}
</style>
<?php
	
$fields = isset( $data['fields'] )?$data['fields']:array();
$labels = isset( $data['labels'] )?$data['labels']:array();

$GLOBALS["fields"] = $fields;
$GLOBALS["labels"] = $labels;

$html = '';
$hash_key = isset( $data[ 'report_reference_key' ] ) ? $data[ 'report_reference_key' ] : '';
$title = isset( $data[ 'report_title' ] ) ? $data[ 'report_title' ] : '';

$post = isset( $data[ 'post' ] ) ? $data[ 'post' ] : array();
$title = isset( $post[ 'report_title' ] ) ? $post[ 'report_title' ] : $title;
	// echo '<pre>'; print_r( $title ); echo '</pre>';
$based_on = isset( $post[ 'based_on' ] ) ? $post[ 'based_on' ] : '';
$start_date = isset( $post[ 'start_date' ] ) ? $post[ 'start_date' ] : '';
$end_date = isset( $post[ 'end_date' ] ) ? $post[ 'end_date' ] : '';
$report_type = isset( $post[ 'report_type' ] ) ? $post[ 'report_type' ] : '';

$based_on_text = isset( $data[ 'based_on_text' ] ) ? $data[ 'based_on_text' ] : '';

$action = isset( $data[ 'action' ] ) ? $data[ 'action' ] : '';
$todo = isset( $data[ 'todo' ] ) ? $data[ 'todo' ] : '';

$location = isset( $data[ 'location' ] ) ? $data[ 'location' ] : '';
$group_by = isset( $data[ 'group_by' ] ) && $data[ 'group_by' ] ? $data[ 'group_by' ] : '';

$hash_key = isset( $data[ 'report_reference_key' ] ) ? $data[ 'report_reference_key' ] : '';

$no_sum = isset( $data[ 'do_not_sum' ] ) ? $data[ 'do_not_sum' ] : array();
$text_fields = isset( $data[ 'text_fields' ] ) ? $data[ 'text_fields' ] : array();
$text_fields2 = array();

$key_label = isset( $data[ 'key_label' ] ) ? $data[ 'key_label' ] : array();

$html = '';
if( ( isset( $data[ 'hide_header' ] ) && $data[ 'hide_header' ] ) ){
	$title = '';
}
if( $title ){
	$html .= '<div class="report-table-preview-20">	';
		$html .= '<div class="table-responsive">';
			$html .= '<table class="table table-striped table-hover bordered" cellspacing="0" style="width:30%;">';
				$html .= '<tr><td>Report Title</td><td>'. $title .'</td></tr>';
				$html .= '<tr><td>Period</td><td>From: <strong>'. $start_date .'</strong> To: <strong>'. $end_date .'<strong></td></tr>';
				$html .= $based_on_text ? '<tr><td>Based On</td><td>'. $based_on_text .'</td></tr>' : '';
				$html .= $location ? '<tr><td>Location</td><td>'. $location .'</td></tr>' : '';
			$html .= '</table>';
		$html .= '</div>';
	$html .= '</div><br>';
}

	// echo "<pre>"; print_r( $key_label );echo "</pre>"; 
if( isset( $data["data"] ) && is_array( $data["data"]  ) && ! empty( $data["data"] ) ){
	if( isset( $data[ 'key1' ] ) && isset( $data[ 'key2' ] ) && $data[ 'key2' ] ){
		$hide_total_vertical = 0;
		$hide_total_horizontal = 0;
		$fn = '';

		$tb_name = $data[ 'key1' ];
		$kk = $data[ 'key2' ];

		ksort( $data[ 'data' ] );

		if( isset( $data[ 'hide_total_vertical' ] ) && $data[ 'hide_total_vertical' ] )$hide_total_vertical = 1;
		if( isset( $data[ 'hide_total_horizontal' ] ) && $data[ 'hide_total_horizontal' ] )$hide_total_horizontal = 1;

		switch( $report_type ){
		case 'list_individuals_waiting_to_benefit':
		case 'ssn_mines_intervention':
		case 'no_of_state_benefit_interventions':
		case 'individuals_waiting_to_benefit':
		case 'individuals_benefiting':
		case 'ssn_mines_without_beneficiary':
		case 'households_benefiting':
			$hide_total_vertical = 1;
		break;
		}

		switch( $report_type ){
		case 'cbt_leader_enumerator':
			$hide_total_vertical = 1;
			$tb_name = 'users';
			$kk = 'supervisor';
		break;
		}

		$based_on_data = function_exists( $fn ) ? $fn() : array();

		$subtitle = '';
		
		$row_heading = '';
		$row_body = '';
		$col_span = 5;
		$tx = array();
		$tx2 = array();
		$tx_total = array();
		$rank = array();
		
		foreach( $data["data"] as $k =>  $v ){

			foreach( $v as $v1 => $v2 ){
				$dv = $v2[ $kk ];
				if( ! isset( $tx[ $dv ][ $kk ] ) ){
					$xx = get_name_of_referenced_record( array( "id" => $dv, "table" => $tb_name ) );
					$tx[ $dv ][ $kk ] = $xx ? $xx : ucwords( $dv );
				}
				
				$dvt = $k;
				if( ! isset( $tx[ $dv ][ "rtype" ][ $k ] ) ){
					$tx[ $dv ][ "rtype" ][ $k ] = 0;
				}
				
				$key3 = '';

				if( ! empty( $text_fields ) ){
					foreach($text_fields as $key => $v) {
						if( $v && isset( $v2[ $key ] ) && $v2[ $key ] ){
							$tx[ $dv ][ "rtype" ][ $k ] = $v2[ $key ];
							$key3 = $key;
							$text_fields2[ $k ] = $key3;
							break;
						}
					}
				}
				
				if( ! $key3 ){
					if( ! isset( $no_sum[ $k ] ) ){
						$tx[ $dv ][ "rtype" ][ $k ] += intval($v2[ 'count' ] );
					}else{
						$tx[ $dv ][ "rtype" ][ $k ] = $v2[ 'count' ];
					}
				}

				switch( $report_type ){
				case 'enumerator_survey':
					if( ! isset( $tx[ $dv ][ "rtype" ][ 'total' ] ) ){
						$tx[ $dv ][ "rtype" ][ 'total' ] = 0;
					}
					$tx[ $dv ][ "rtype" ][ 'total' ] += $v2[ 'total' ];
				break;
				}
				
				if( ! isset( $rank[ $dv ] ) ){
					$rank[ $dv ] = 0;
				}
				$rank[ $dv ] += 1;
				
				if( ! isset( $tx2[ $k ] ) ){
					$tx2[ $k ] = isset( $types[ $k ] )?$types[ $k ]:$k;
					$tx_total[ $k ] = 0;
				}
			}
		
		}
		
				// echo '<pre>'; print_r( $tx ); echo '</pre>';
		$row_heading .= '<th>S/N</th>';
		$row_heading .= '<th>'. ucwords( $kk ) .'</th>';
				
		switch( $report_type ){
		case 'enumerator_survey':
			$tx2[ 'total' ] = 'Total (Distinct)';
			$tx_total[ 'total' ] = 0;
		break;
		case 'cbt_leader_enumerator':
			switch( $based_on ){
			case 'enumerators':
				$tx2[ 'households' ] = 'Number of Records';
				$tx2[ 'enumerators' ] = 'Number of Enumerators';
			break;
			}
		break;
		}
		
		$sn = 0;
		
		if( ! empty( $tx ) ){
			foreach( $tx2 as $sval1 ){
				$vv = '';
				switch( $report_type ){
				default:
					$vv = isset( $based_on_data[ $sval1 ] ) ? $based_on_data[ $sval1 ] : ucwords( $sval1 );
				break;
				case 'survey_enumerator':
					$vv = get_name_of_referenced_record( array( 'id' => $sval1, 'table' => 'survey' ) );
				break;
				}
				$row_heading .= '<th>'. $vv .'</th>';
			} 
			if( ! $hide_total_vertical )$row_heading .= '<th>Total</th>';
			
			arsort( $rank );
			
			foreach( $rank as $dk => $dv ){
				if( isset( $tx[ $dk ] ) ){
					$sval = $tx[ $dk ];
					
					$row_body .= '<tr>';
						$row_body .= '<td>'. ++$sn .'</td>';
						$row_body .= '<td>'. ( isset( $key_label[ $sval[ $kk ] ] ) ? $key_label[ $sval[ $kk ] ] : $sval[ $kk ] ) .'</td>';
						
						$total = 0;
						foreach( $tx2 as $sk => $sval1 ){
							// echo '<pre>'; print_r( $sk ); echo '</pre>';
							
							$text = '';
							if( isset( $sval[ "rtype" ][ $sk ] ) ){
								$text = $sval[ "rtype" ][ $sk ];
								if( ! isset( $no_sum[ $sk ] ) ){
									$text = ( isset( $text_fields2[ $sk ] ) && isset( $text_fields[ $text_fields2[ $sk ] ] ) )? $sval[ "rtype" ][ $sk ] : number_format( $sval[ "rtype" ][ $sk ], 0 );
									
									//$text = number_format( $sval[ "rtype" ][ $sk ], 0 );
									
									switch( $report_type ){
									case 'enumerator_survey':
										if( $sk !== 'total' ){
											$total += $sval[ "rtype" ][ $sk ];
										}
									break;
									default:
										$total += $sval[ "rtype" ][ $sk ];
									break;
									}
									$tx_total[ $sk ] += $sval[ "rtype" ][ $sk ];
								}
							}
							$row_body .= '<td class="r number">'.$text.'</td>';
						}
						
						if( ! $hide_total_vertical )$row_body .= '<td class="r number"><strong>'.number_format( $total, 0 ).'</strong></td>';
						
					$row_body .= '</tr>';
				}
			}
			
			if( ! $hide_total_horizontal ){
				$row_body .= '<tr>';
					$row_body .= '<td></td>';
					$row_body .= '<td><strong>TOTAL</strong></td>';
					
					$total = 0;
					foreach( $tx2 as $sk => $sval1 ){
						$text = '';
						if( isset( $tx_total[ $sk ] ) ){
							$text = number_format( $tx_total[ $sk ], 0 );
							switch( $report_type ){
							case 'enumerator_survey':
								if( $sk !== 'total' ){
									$total += $tx_total[ $sk ];
								}
							break;
							default:
								$total += $tx_total[ $sk ];
							break;
							}
						}
						$row_body .= '<td class="r number"><strong>'.$text.'</strong></td>';
					}
					
						if( ! $hide_total_vertical )$row_body .= '<td class="r number"><strong>'.number_format( $total, 0 ).'</strong></td>';
					
				$row_body .= '</tr>';
			}
		}
		
		$table_class = 'report-table-preview';

			$hidden_fields = array(
				array(
					"name" => "reference",
					"value" => $hash_key,
				),
				array(
					"name" => "reference_table",
					"value" => $report_type,
				)
			);
			echo '<div id="report-preview-container-id">';
			if( ! ( isset( $data[ 'hide_header_buttons' ] ) && $data[ 'hide_header_buttons' ] ) ){
				echo get_export_and_print_popup( ".table" , "#quick-print-container", "", 0, array( "share" => 1, "save" => 1, "copy" => 1, "hidden_fields" => $hidden_fields, "subject" => $title ) ) . "</div><br /><br />";
			}
				echo '<div id="quick-print-container">';
				echo $html; 
				?>
				<div class="<?php echo $table_class; ?>">
					<table class="table table-striped table-bordered table-hover" cellspacing="0" style="width:100%;">
						<thead>
						<tr><?php echo $row_heading; ?></tr>
						</thead>
						<tbody>
							<?php echo $row_body; ?>
						</tbody>
					</table>
				</div>
				<?php
				echo '</div>';
			echo '</div>';
		if( ! ( isset( $data[ 'hide_chart_button' ] ) && $data[ 'hide_chart_button' ] ) ){
		?>
			<form id="display-report-type-form" class="activate-ajax" action="?action=chart_js&todo=execute_chart_filter&return_complete_single=<?php echo $report_type; ?>&html_replacement_selector=single-chart-container&query_action=<?php echo $action; ?>&query_todo=<?php echo $todo; ?>&type=basic_filter">
				<?php if( ! empty( $post ) ){
					
					foreach( $post as $pk => $pv ){
						if( is_array( $pv ) ){
					?>
						<textarea style="display:none;" name="<?php echo $pk; ?>"><?php echo json_encode($pv); ?></textarea>
					<?php
						}else{
					?>
						<input type="hidden" name="<?php echo $pk; ?>" value="<?php echo $pv; ?>" >
					<?php
						}
					}
				} ?>
				<div class="row">
					<div class="col-md-12">
						<input class="btn dark" value="View Chart" type="submit" />
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-12" id="single-chart-container"></div>
			</div>
		<?php
		}

	}else{
		?>
		<div class="note note-warning">Invalid Data Keys</div>
		<?php	
	}
}else{
	?>
	<h3><strong>No Records Found.</strong></h3><h4><br><br>Please check the <strong>Start Date</strong> and <strong>End Date</strong> or adjust some other filters</h4>
	<?php
}
if( isset( $GLOBALS["labels"] ) )unset( $GLOBALS["labels"] );
if( isset( $GLOBALS["fields"] ) )unset( $GLOBALS["fields"] );
?>
</div>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style>
		.table-light-variant {
			--vz-table-color: #000;
			--vz-table-bg: #f3f6f9;
			--vz-table-border-color: #dbdde0;
			--vz-table-striped-bg: #eef1f4;
			--vz-table-striped-color: #000;
			--vz-table-active-bg: black;
			--vz-table-active-color: #fff;
			--vz-table-hover-bg: black;
			--vz-table-hover-color: var(--vz-primary);
			color: var(--vz-table-color);
			border-color: var(--vz-table-border-color)
		}

		.table-light-variant thead tr th{
			background:#6338b3;
			color: #fff;
		}
	</style>	
	<?php
		$hide_filter = 1;
		include 'title-row.php'; 
		$ar1 = [];
		foreach ($data['rdata'] as $xval) {
			switch ( $xval['id'] ) {
				// Had to hard code this to make the percentage work for now 😬
				case 'ray20u3487599531499371906':  // Successful Digital Authentications In The Context Of Service Delivery	
				case 'ray20u34875929411839272429': // Persons who have NIN
				case 'ray20u3490207611541147811': // Live births registered digitally and assigned a NIN
					$rpc_data = isset($xval['custom_data']) && $xval['custom_data'] ? json_decode( $xval['custom_data'], true ) : [];
					$ar = isset( $rpc_data['actual_result']) ? doubleval($rpc_data['actual_result']) : null;
					$ar1[ $xval['id'] ] = $ar;
				break;
			}
		}

		// echo "<pre>";
		// print_r($)
		// echo "</pre>";
		
		$reports = itemize_by_key_multi( $data['rdata'], 'group');
		// echo "<pre>"; print_r( $reports ); echo"</pre>";
		$groups = get_list_box_options('get_reportbay_group', array( 'return_type' => 2 ));
		$unit_of_m = get_list_box_options('get_unit_of_measure', array( 'return_type' => 2 ));
		$yes_no = get_yes_no();

		$h = "";
		$h1 = "";
		$sn = 1;

		if( $reports ){
			foreach ( $reports as $group_key => $value ) {
				$h.= '<tr>';
					$t = isset( $groups[ $group_key ] ) && $groups[ $group_key ] ? $groups[ $group_key ] : $group_key;
					$h.= '<td class="text-center" colspan="7"><b class="text-capitalize">'. $t .'</b></td>';
				$h.= '</tr>';
				
				$h1 = "";

				foreach ( $value as $report_item ) {
					$rpc_data = isset($report_item['custom_data']) && $report_item['custom_data'] ? json_decode( $report_item['custom_data'], true ) : [];
					$unit = isset( $rpc_data['unit_of_measure']) && $rpc_data['unit_of_measure'] && isset($unit_of_m[ $rpc_data['unit_of_measure'] ]) && $unit_of_m[ $rpc_data['unit_of_measure'] ] ? $unit_of_m[ $rpc_data['unit_of_measure'] ] : '<span class="badge badge-soft-danger text-center">N/A</span>';
					
					$bl = isset( $rpc_data['baseline']) ? $rpc_data['baseline'] : null;
					$et = isset( $rpc_data['end_target']) ? $rpc_data['end_target'] : null;
					$ar = isset( $rpc_data['actual_value'] ) && $rpc_data['actual_value'] ? $rpc_data['actual_value'] : ( isset( $rpc_data['actual_result']) ? $rpc_data['actual_result'] : null );

					
					$cr = '';
					$pa = '';

					$cl_bl = ''; $cl_et = ''; $cl_ar = ''; $cl_cr = ''; $cl_pa = '';
					
					if( isset($rpc_data['unit_of_measure']) && $rpc_data['unit_of_measure'] ){
						$cl_bl = 'text-center';
						$cl_et = 'text-center';
						$cl_ar = 'text-center';
						$cl_cr = 'text-center';
						$cl_pa = 'text-center';

						switch ( $rpc_data['unit_of_measure'] ) {
							case 'number':
							case 'percent':
								// $cl_bl = 'r';
								// $cl_et = 'r';
								// $cl_ar = 'r';
								// $cl_cr = 'r';
								// $cl_pa = 'r';
								$et = doubleval( $et );
								$ar = doubleval( $ar );
								$bl = doubleval( $bl );
								$pa = doubleval( $pa );
								
								switch( $report_item['id'] ){
									 // Had to hard code this to make the percentage work for now 😬
									case 'ray20u3487602261908160820': // Successful Digital Authentications On Behalf Of Women
										if( isset($ar1[ 'ray20u3487599531499371906' ]) && $ar1[ 'ray20u3487599531499371906' ] && $ar ){
											$ar = ( ( $ar/ $ar1[ 'ray20u3487599531499371906' ] ) * 100);
										}
									break;
									case 'ray20u3487605721840722473': // Persons With NINs In The Bottom Two Poverty Quintiles
										if( isset($ar1[ 'ray20u34875929411839272429' ]) && $ar1[ 'ray20u34875929411839272429' ] && $ar ){
											$ar = ( ( $ar1[ 'ray20u34875929411839272429' ] / $ar ) * 100);
										}
									break;
									case 'ray20u34902079711280715857': // New NIN Holders Who Have Been Issued A Basic Authenticator
										if( isset($ar1[ 'ray20u3490207611541147811' ]) && $ar1[ 'ray20u3490207611541147811' ] && $ar ){
											$ar = ( ( $ar / $ar1[ 'ray20u3490207611541147811' ] ) * 100);
										}
									break;
								}


								$cr = $bl +  $ar;
								if( $cr && $et ){
									$pa = round( (($cr/$et) * 100), 2);
									switch ( $report_item['id'] ) {
										case 'ray20u3487605721840722473':
											$pa = $ar;
										break;
									}

									$cr = round($cr, 2);
									$ar = round($ar, 2);

									$pa = number_format( $pa, 1 ) . '%';
								}

								switch ( $rpc_data['unit_of_measure'] ) {
									case 'percent':
										$bl .= '%';
										$et .= '%';
										$ar .= '%';
										$cr .= '%';
										$pa .= ((strpos($pa, '%') === false) ? '%' : '');
									break;
									default:
										$bl = number_format( $bl );
										$et = number_format( $et );
										$ar = number_format( $ar );
										$cr = number_format( $cr );										
									break;
								}
							break;
							case 'yes_no':
								$bl = isset( $yes_no[ $bl ] ) && $yes_no[ $bl ] ? $yes_no[ $bl ] : $bl;
								$et = isset( $yes_no[ $et ] ) && $yes_no[ $et ] ? $yes_no[ $et ] : $et;
								$ar = isset( $yes_no[ $ar ] ) && $yes_no[ $ar ] ? $yes_no[ $ar ] :  $ar;
								$cr = isset( $yes_no[ $cr ] ) && $yes_no[ $cr ] ? $yes_no[ $cr ] :  $cr;
								if( $bl == $cr ){
									$pa = "100%";
								}else{
									$pa = "0%";
								}
							break;
						}
					}

					if( $bl === null ){
						$bl = '<span class="badge badge-soft-danger text-center">N/A</span>';
						// $cl_bl = '';
					}
					
					if( !$et === null ){
						$et = '<span class="badge badge-soft-danger text-center">N/A</span>';
						// $cl_et = '';
					}

					if( !$ar ){
						$ar = '<span class="badge badge-soft-info text-center">N/A</span>';
						// $cl_ar = '';
					}
										
					if( !$cr ){
						$cr = '<span class="badge badge-soft-danger text-center">N/A</span>';
						// $cl_cr = '';
					}
					
					if( !$pa ){
						$pa = '<span class="badge badge-soft-danger text-center">N/A</span>';
						// $cl_pa = '';
					}

					$h1 .= '<tr id="tr-'. $report_item['id'] .'">';
						$h1 .= '<td>'. $sn .'</td>';
						$h1 .= '<td>'. $report_item['name'] . ' ' . ( $report_item['title'] ) .'</td>';
						$h1 .= '<td>'. $unit .'</td>';
						$h1 .= '<td class="'. $cl_bl .'">'. $bl .'</td>';
						$h1 .= '<td class="'. $cl_et .'">'. $et .'</td>';
						$h1 .= '<td class="'. $cl_ar .'" id="ar-'. $report_item['id'] .'" >'. $ar .'</td>';
						$h1 .= '<td class="'. $cl_cr .'">'. $cr .'</td>';
						$h1 .= '<td class="'. $cl_pa .'">'. $pa .'</td>';
					$h1 .= '</tr>';
					$sn++;
				}

				$h .= $h1;
			}
		}
	?>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered table-hover table-light-variant table-striped">
				<thead>
					<tr>
						<th class="text-center" rowspan="2">S/N</th>
						<th class="text-center" rowspan="2">PDO Indicators</th>
						<th rowspan="2">Unit of Measure</th>
						<th class="text-center" colspan="6">Actual</th>
					</tr>
					<tr>
						<th>Baseline</th>
						<th>End Target</th>
						<th>Actual Result</th>
						<th>Current Result<br>( Baseline + Actual Result )</th>
						<th>% Achieved</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if( $h ){
							echo $h;
						}else{ ?>
							<div class="note note-warning">
								<h4><b>Indicator Data Unavailable</b></h4>
								<p>There was an error displaying indicator data. Kindly contact the Technical Team</p>
							</div>
						<?php }
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

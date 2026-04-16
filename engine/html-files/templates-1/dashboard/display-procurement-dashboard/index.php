<div <?php set_hyella_source_path( __FILE__, 1 ); ?> style="width:100%;">
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	//echo '<a href="../engine/php/ajax_request_processing_script.php?action=quotation&todo=login&view=1" target="_blank">Login</a>';
	
	$last_month = isset( $data["last_month"] )?$data["last_month"]:0;
	
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:''; 
	//echo '<pre>'; print_r( $data["low_stock"] ); echo '</pre>';
	//echo '<pre>'; print_r( $data["assets"] ); echo '</pre>';
	//echo '<pre>'; print_r( $data["assets2"] ); echo '</pre>';
	//echo '<pre>'; print_r( $data["requisitions"] ); echo '</pre>';
	//echo '<pre>'; print_r( $data["supplies"] ); echo '</pre>';
	
	$req = array();
	if( isset( $data["requisitions"] ) && is_array( $data["requisitions"] ) && ! empty( $data["requisitions"] ) ){
		$req["structure"] = array(
			'stock-request-unvalidated' => array( 
				'title' => 'Submitted',
				'color' => 'bg-info',
			),
			'stock-request-returned' => array( 
				'title' => 'Returned',
				'color' => 'bg-danger',
			),
			'stock-request-validated' => array( 
				'title' => 'Approved',
				'color' => 'bg-success progress-bar-striped',
			),
		);
		
		$rtotal = 0;
		
		$req[ "purchase_requisition" ][ "total" ] = 0;
		$req[ "internal_requisition" ][ "total" ] = 0;
		
		foreach( $data["requisitions"] as $sval ){
			
			$rtotal += doubleval( $sval["count"] );
			
			$req[ $sval["type"] ][ "percent" ] = 0;
			
			if( ! isset( $req[ $sval["type"] ][ "total" ] ) ){
				$req[ $sval["type"] ][ "total" ] = 0;
			}
			
			$req[ $sval["type"] ][ "total" ] += doubleval( $sval["count"] );
			$req[ $sval["type"] ][ $sval["status"] ] = doubleval( $sval["count"] );
			
		}
		
		if( $rtotal ){
			$req[ "purchase_requisition" ][ "percent" ] = ( $req[ "purchase_requisition" ][ "total" ] / $rtotal ) * 100;
			$req[ "internal_requisition" ][ "percent" ] = ( $req[ "internal_requisition" ][ "total" ] / $rtotal ) * 100;
		}
		
	}
	
	if( isset( $data["store"] ) && $data["store"] ){
		echo '<h4>'. get_name_of_referenced_record( array( "id" => $data["store"], "table" => "stores" ) ) .'</h4>';
	}
?>
<div class="row">
	<div class="col-md-12 col-lg-7">
		<?php 
			$tp = '-'; 
			$tpp = 0;
			$tp2 = 0;
			if( isset( $data["purchase"]["total"] ) && $data["purchase"]["total"] ){ 
				$tpp = 75;
				$tp2 = doubleval( $data["purchase"]["total"] );
				$tp = nw_pretty_number( $data["purchase"]["total"] ); 
			}
			
			$parray = array(
				'past_due' => array( 
					'title' => 'Past Due Date',
					'color' => 'bg-danger',
				),
				'awaiting_supply' => array( 
					'title' => 'Awaiting Supply',
					'color' => 'bg-info',
				),
				'partial_supply' => array( 
					'title' => 'Partial Supply',
					'color' => 'bg-success progress-bar-striped',
				),
			);
		?>
		<div class="card-header">Pending Purchase Order</div>
		<div class="card mb-3">
			<div class="row">
				<div class="col-md-4" style="text-align:center; font-size:1.6em;">
					<div class="counter" data-cp-percentage="<?php echo $tpp; ?>" data-cp-color="#FF9900" data-text="<?php echo $tp; ?>" data-line-width="25"></div>
				</div>
				<div class="col-md-8">
					<div class="card-body" >
						<br />
						<?php 
							foreach( $parray as $pk => $pv ){ 
								$text = '';
								$percent = 0;
								
								if( $tp2 > 0 ){
									if( isset( $data["purchase"][ $pk ] ) && $data["purchase"][ $pk ] ){
										$text = number_format( doubleval( $data["purchase"][ $pk ] ), 0 );
										
										$percent = ( doubleval( $data["purchase"][ $pk ] ) / $tp2 ) * 100;
									}
								}
						?>
						<label class="card-titlex fsize-1"><strong><?php echo $pv["title"]; ?></strong></label>
						<div class="mb-3 progress" >
							<div class="progress-bar <?php echo $pv["color"]; ?>" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent; ?>%;"><?php echo $text; ?></div>
						</div>
						<?php } ?>
					
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-5">
		<div class="card" style="min-height:285px;">
			<div class="card-header">Low Stock Level</div>
			<div class="card-body">
				<?php
				if( isset( $data["low_stock"]["html_replacement"] ) )echo $data["low_stock"]["html_replacement"];
				/*
				<table class="mb-0 table table-striped">
				<thead>
				<tr>
					<th>#</th>
					<th>Item</th>
					<th>Stock Level</th>
					<th>Reorder Level</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<th scope="row">1</th>
					<td>Mark</td>
					<td>Otto</td>
					<td>@mdo</td>
				</tr>
				<tr>
					<th scope="row">2</th>
					<td>Jacob</td>
					<td>Thornton</td>
					<td>@fat</td>
				</tr>
				<tr>
					<th scope="row">3</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
				<tr>
					<th scope="row">4</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
				<tr>
					<th scope="row">5</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
				</tbody>
			</table>
				*/
				?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<?php if( isset( $req["structure"] ) ){ ?>
	<div class="col-md-4 col-lg-5">
		<div class="card-header">Internal Requisitions</div>
		<div class="card mb-3 ">
					<div class="card-body" >
			<div class="row">
				<div class="col-md-4" style="text-align:center; font-size:1.6em;">
					<?php $ckey = "internal_requisition"; if( isset( $req[ $ckey ][ "total" ] ) ){ ?>
					<div class="counter" data-cp-percentage="<?php echo $req[ $ckey ][ "percent" ]; ?>" data-cp-color="#FF675B" data-text="<?php echo nw_pretty_number( $req[ $ckey ][ "total" ] ); ?>" style="width:150px; height:150px;"></div>
					<?php } ?>
				</div>
				<div class="col-md-8">
					<div class="cardx" >
						<br />
						<?php 
							if( isset( $req["structure"] ) ){
								$tp2 = isset( $req[ $ckey ][ "total" ] )?$req[ $ckey ][ "total" ]:0;
								
								foreach( $req["structure"] as $pk => $pv ){ 
									$text = '';
									$percent = 0;
									
									if( $tp2 > 0 ){
										if( isset( $req[ $ckey ][ $pk ] ) && $req[ $ckey ][ $pk ] ){
											$text = number_format( doubleval( $req[ $ckey ][ $pk ] ), 0 );
											
											$percent = ( doubleval( $req[ $ckey ][ $pk ] ) / $tp2 ) * 100;
										}
									}
						?>
						<label class="card-titlex fsize-1"><strong><?php echo $pv["title"]; ?></strong></label>
						<div class="mb-3 progress" >
							<div class="progress-bar <?php echo $pv["color"]; ?>" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent; ?>%;"><?php echo $text; ?></div>
						</div>
						<?php 
								} 
							} 
						?>
					
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-lg-5">
		<div class="card-header">Purchase Requisitions</div>
		<div class="card mb-3 ">
		<div class="card-body" >
			<div class="row">
				<div class="col-md-4" style="text-align:center; font-size:1.6em;">
					<?php $ckey = "purchase_requisition"; if( isset( $req[ $ckey ][ "total" ] ) ){ ?>
					<div class="counter" data-cp-percentage="<?php echo $req[ $ckey ][ "percent" ]; ?>" data-cp-color="#FF675B" data-text="<?php echo nw_pretty_number( $req[ $ckey ][ "total" ] ); ?>" style="width:150px; height:150px;"></div>
					<?php } ?>
				</div>
				<div class="col-md-8">
					<div class="cardx" >
						<br />
						<?php 
							if( isset( $req["structure"] ) ){
								$tp2 = isset( $req[ $ckey ][ "total" ] )?$req[ $ckey ][ "total" ]:0;
								
								foreach( $req["structure"] as $pk => $pv ){ 
									$text = '';
									$percent = 0;
									
									if( $tp2 > 0 ){
										if( isset( $req[ $ckey ][ $pk ] ) && $req[ $ckey ][ $pk ] ){
											$text = number_format( doubleval( $req[ $ckey ][ $pk ] ), 0 );
											
											$percent = ( doubleval( $req[ $ckey ][ $pk ] ) / $tp2 ) * 100;
										}
									}
						?>
						<label class="card-titlex fsize-1"><strong><?php echo $pv["title"]; ?></strong></label>
						<div class="mb-3 progress" >
							<div class="progress-bar <?php echo $pv["color"]; ?>" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent; ?>%;"><?php echo $text; ?></div>
						</div>
						<?php 
								} 
							} 
						?>
					
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="col-md-4 col-lg-2">
		<div class="card-header"><?php echo date("F, Y"); ?></div>
		<div class="card mb-3 widget-chart text-left">
			<?php 
				$supplies = array();
				$supplies["quantity"] = 0;
				$supplies["quantity_percent"] = 0;
				$supplies["quantity_color"] = 'text-primary';
				$supplies["quantity_direction"] = 'fa fa-angle-up';
				
				$supplies["quantity_ordered"] = 0;
				$supplies["quantity_ordered_percent"] = 0;
				$supplies["quantity_ordered_color"] = 'text-primary';
				$supplies["quantity_ordered_direction"] = 'fa fa-angle-up';
				
				if( isset( $data["supplies"] ) && is_array( $data["supplies"] ) && ! empty( $data["supplies"] ) ){
					foreach( $data["supplies"] as $sval ){
						$supplies[ $sval["month"] ] = $sval;
					}
					
					$this_month = date("F-Y");
					if( isset( $supplies[ $this_month ] ) ){
						$supplies["quantity"] = doubleval( $supplies[ $this_month ]["quantity"] );
						$supplies["quantity_ordered"] = doubleval( $supplies[ $this_month ]["quantity_ordered"] );
					}
					
					
					$prev_month = date("F-Y", $last_month );
					if( isset( $supplies[ $prev_month ] ) ){
						
						$pq = doubleval( $supplies[ $prev_month ]["quantity"] );
						if( $pq ){
							$pc = ( ( $supplies["quantity"] - $pq ) / $pq ) * 100;
							$dp = 0;
							if( abs( $pc ) < 1 ){
								$dp = 2;
							}
							$supplies["quantity_percent"] = number_format( abs( $pc ), $dp );
							
							if( $pc < 0 ){
								$supplies["quantity_color"] = 'text-danger';
								$supplies["quantity_direction"] = 'fa fa-angle-down';
							}
						}
						
						
						$pq = doubleval( $supplies[ $prev_month ]["quantity_ordered"] );
						if( $pq ){
							$pc = ( ( $supplies["quantity"] - $pq ) / $pq ) * 100;
							$dp = 0;
							if( abs( $pc ) < 1 ){
								$dp = 2;
							}
							
							$supplies["quantity_ordered_percent"] = number_format( abs( $pc ) , $dp );
							
							if( $pc < 0 ){
								$supplies["quantity_color"] = 'text-danger';
								$supplies["quantity_direction"] = 'fa fa-angle-down';
							}
						}
					}
				}
			?>
			<div class="widget-chart-content">
				<div class="widget-subheading">Items Received</div>
				<div class="widget-numbers"><?php echo nw_pretty_number( $supplies["quantity"] ); ?></div>
				<div class="widget-description <?php echo $supplies["quantity_color"]; ?>"><span class="pr-1"><?php echo $supplies["quantity_percent"]; ?>%</span><i class="<?php echo $supplies["quantity_direction"]; ?>"></i>
				</div>
				<hr />
				<div class="widget-subheading">Items Ordered</div>
				<div class="widget-numbers"><?php echo nw_pretty_number( $supplies["quantity_ordered"] ); ?></div>
				<div class="widget-description <?php echo $supplies["quantity_ordered_color"]; ?>"><span class="pr-1"><?php echo $supplies["quantity_ordered_percent"]; ?>%</span><i class="<?php echo $supplies["quantity_ordered_direction"]; ?>"></i>
				</div>
			</div>
		</div>
	</div>
</div>
<br />

<div class="row">
	<?php 
		$keys = isset( $data["assets"]["keys"] )?$data["assets"]["keys"]:array();
		if( is_array( $keys ) && ! empty( $keys ) ){
	?>
	<div class="col-md-4 col-lg-4">
		<div class="card-header">Assets Summary</div>
		<div class="card mb-3 widget-content">
			<div class="widget-content-outer">
				<?php 
					foreach( $keys as $key ){
						
						$text_label = ' ';
						
						if( isset( $data["assets"][ $key ]['no_label_prefix'] ) && $data["assets"][ $key ]['no_label_prefix'] ){
							$text_label = '';
						}
						
						if( isset( $data["assets"][ $key ]['html'] ) && $data["assets"][ $key ]['html'] ){
							echo $data["assets"][ $key ]['html'];
						}
						
						if( isset( $data["assets"][ $key ]['count'][0]['count'] ) && $data["assets"][ $key ]['count'][0]['count'] ){
							?>
							<div class="widget-content-wrapper">
								<div class="widget-content-left">
									<div class="widget-heading"><?php echo $data["assets"][ $key ]['label']; ?></div>
									<div class="widget-subheading"><?php echo 'From: ' . date( "d-M-Y", $data["assets"][ $key ]['count'][0]['date'] ); ?></div>
								</div>
								<div class="widget-content-right">
									<div class="widget-numbers text-success"><?php echo number_format( $data["assets"][ $key ]['count'][0]['count'], 0 ) . $text_label; ?></div>
								</div>
							</div>
							<hr />
							
							<?php
								/*
								$astart = '';
								$aend = '';
								if( isset( $data[ $key ][ 'action' ] ) && $data[ $key ][ 'action' ] ){
									$id = $customer;
									$id = ( isset( $data[ $key ]['count'][0]['id'] ) )?$data[ $key ]['count'][0]['id']:$customer;
									
									$astart = '<a href="#" class="custom-single-selected-record-button" override-selected-record="'. $id .'" action="'. $data[ $key ][ 'action' ] .'" title="View '.  $data[ $key ]['label'] .'">';
									$aend = '</a>';
								}
								echo $astart . number_format( $data[ $key ]['count'][0]['count'], 0 ) . $text_label . $data[ $key ]['label'] . ' ( ' . date( "d-M-Y", $data[ $key ]['count'][0]['date'] ) . ' ) ' . $aend;
								*/
						}
						
					}
				?>
			</div>
		</div>
	</div>
	<?php
		}
	?>
	
	<?php 
		$ckey = 'upcoming_maintenance';
		$keys = isset( $data["assets2"][ $ckey ]["count"] )?$data["assets2"][$ckey]["count"]:array();
		$serial = 0;
		$today = date("U");
		
		if( is_array( $keys ) && ! empty( $keys ) ){
	?>
	<div class="col-md-4 col-lg-4">
		<div class="card" >
			<div class="card-header">Upcoming Maintenance</div>
			<div class="card-body">
				<table class="mb-0 table table-striped">
				<thead>
				<tr>
					<th>#</th>
					<th>Assets</th>
					<th>Maintenance Date</th>
				</tr>
				</thead>
				<tbody>
				<?php 
					foreach( $keys as $key ){
						
						$text_label = get_name_of_referenced_record( array( "id" => $key["asset"], "table" => "assets", "link" => 1 ) );
						if( ! $text_label ){
							$text_label = $key["name"];
						}
						
						$style = '';
						if( $key["date"] < $today ){
							$style = ' style="color:red;" ';
						}
						?>
						<tr <?php echo $style; ?>>
							<th scope="row"><?php echo ++$serial; ?></th>
							<td><?php echo $text_label; ?></td>
							<td><?php echo date("d-M-Y", doubleval( $key["date"] ) ); ?></td>
						</tr>
						<?php
					}
				?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
	<?php
		}
	?>
	
	<?php 
		$ckey = 'expiring_insurance';
		$keys = isset( $data["assets2"][ $ckey ]["count"] )?$data["assets2"][$ckey]["count"]:array();
		$serial = 0;
		$today = date("U");
		
		if( is_array( $keys ) && ! empty( $keys ) ){
	?>
	<div class="col-md-4 col-lg-4">
		<div class="card" >
			<div class="card-header">Insurance Expiring Soon</div>
			<div class="card-body">
				<table class="mb-0 table table-striped">
				<thead>
				<tr>
					<th>#</th>
					<th>Assets</th>
					<th>Insurance Expiry Date</th>
				</tr>
				</thead>
				<tbody>
				<?php 
					foreach( $keys as $key ){
						
						$text_label = get_name_of_referenced_record( array( "id" => $key["asset"], "table" => "assets", "link" => 1 ) );
						if( ! $text_label ){
							$text_label = $key["name"];
						}
						
						$style = '';
						if( $key["date"] < $today ){
							$style = ' style="color:red;" ';
						}
						?>
						<tr <?php echo $style; ?>>
							<th scope="row"><?php echo ++$serial; ?></th>
							<td><?php echo $text_label; ?></td>
							<td><?php echo date("d-M-Y", doubleval( $key["date"] ) ); ?></td>
						</tr>
						<?php
					}
					/* 
					if( $serial < 7 ){
						for( $i = $serial; $i < 7; $i++ ){
							echo '<tr><td colspan="3">&nbsp;</td></tr>';
						}
					} */
				?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
	<?php
		}
	?>
</div>

<script type="text/javascript" class="auto-remove">
	<?php 
		if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; 
	?>
</script>
</div>
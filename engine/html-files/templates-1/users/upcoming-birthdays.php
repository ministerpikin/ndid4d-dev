<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php
		$report_type = isset($data['report_type']) && $data['report_type'] ? $data['report_type'] : 'upcoming_birthdays';
		$date_field = isset($data['date_field']) && $data['date_field'] ? $data['date_field'] : 'date_of_birth';

		$ttl = 'Birthday';
		switch( $report_type ){
			case 'wedding_anniversary':
			case 'work_anniversary':
				$ttl = ucwords( str_replace(['-', '_'], ' ', $report_type) );
			break;
		}
	?>
	<div class=" shopping-cart-table" >
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered">
				<thead>
				   <tr>
					  <th></th>
					  <th align="center">Category</th>
					  <th align="center">Name</th>
					  <th align="center">Phone</th>
					  <th align="center">Email</th>
					  <th align="center"><?php echo $ttl; ?></th>
					  <th align="center">Comment</th>
				   </tr>
				</thead>
				<tbody>
				   <?php
				   $last_id = 0;
				   $previous_id = 0;
				   
				   $total_amount = 0;
				   $total_int = 0;
				   $now = date("U");
				   
					if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
						
						$serial = 0;
						$today = isset( $data['start_date'] )?$data['start_date']:date("U");
						$etoday = isset( $data['end_date'] )?$data['end_date']:date("U");
						
						foreach( $data["items"] as $sval ){
							$sval[ $date_field ] = doubleval( $sval[ $date_field ] );
							$birth_day = mktime( 23, 59, 59, date( "m", $sval[ $date_field ] ), date( "j", $sval[ $date_field ] ), date("Y") );
							$birth_day_next_year = mktime( 23, 59, 59, date( "m", $sval[ $date_field ] ), date( "j", $sval[ $date_field ] ), date("Y") + 1 );
							?>
							<tr class="item-record" id="<?php echo $sval["id"]; ?>" >
							  <td >
								<?php echo ++$serial; ?>
							  </td>
							  <td>
								<?php echo get_name_of_referenced_record( array( "id" => $sval["category"], "table" => "banks" ) ); ?>
							  </td>
							  <td>
								<?php echo $sval["firstname"] . ' ' . $sval["lastname"]; ?>
							  </td>
							  <td >
								<?php echo $sval["phone_number"]; ?>
							  </td>
							  <td >
								<?php echo $sval["email"]; ?>
							  </td>
							  <td >
								<?php if( $sval[ $date_field ] )echo date("jS F" , $sval[ $date_field ] ); ?>
							  </td>
							  <td >
								<?php 
									if( $now > $birth_day ){
										$seconds = $birth_day_next_year - $now;
										echo time_passed_since_action_occurred( $seconds, 0 );
									}else{
										$seconds = $birth_day - $now;
										echo time_passed_since_action_occurred( $seconds, 0 );
									}
								?>
							  </td>
							</tr><?php
							$last_id = $sval["id"];
							if( ! $previous_id )$previous_id = $sval["id"];
							}
						
					}
				?>
				</tbody>
			</table>			
		</div>
	</div>
</div>